<?php

namespace App\Models\AdminDashboard;

use CodeIgniter\Model;

class AdminDashboardModel extends Model
{
    protected $session;

    function __construct()
    {
        $db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        parent::__construct();
    }
    
    public function get_efilied_nums_stage_wise_count($stage_ids = array())
    {
        $session = \Config\Services::session();
        $created_by = $session->get('login')['id'];    
        $builder = $this->db->table('efil.tbl_efiling_nums as en');
        $builder->join('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id', 'inner');
        $builder->select(
            'COUNT(CASE WHEN stage_id = ' . New_Filing_Stage . ' THEN 1 END) as total_new_efiling, ' .
            'COUNT(CASE WHEN stage_id = ' . DEFICIT_COURT_FEE . ' THEN 1 END) as deficit_crt_fee, ' .
            'COUNT(CASE WHEN stage_id = ' . Initial_Defected_Stage . ' THEN 1 END) as total_not_accepted, ' .
            'COUNT(CASE WHEN stage_id = ' . Transfer_to_CIS_Stage . ' THEN 1 END) as total_available_for_cis, ' .
            'COUNT(CASE WHEN stage_id = ' . Get_From_CIS_Stage . ' THEN 1 END) as total_get_from_cis, ' .
            'COUNT(CASE WHEN stage_id = ' . Initial_Defects_Cured_Stage . ' OR stage_id = ' . DEFICIT_COURT_FEE_PAID . ' THEN 1 END) as total_refiled_cases, ' .
            'COUNT(CASE WHEN stage_id = ' . Transfer_to_IB_Stage . ' THEN 1 END) as total_transfer_to_efiling_sec, ' .
            'COUNT(CASE WHEN stage_id = ' . I_B_Approval_Pending_Admin_Stage . ' THEN 1 END) as total_pending_scrutiny, ' .
            'COUNT(CASE WHEN stage_id = ' . I_B_Defected_Stage . ' THEN 1 END) as total_waiting_defect_cured, ' .
            'COUNT(CASE WHEN stage_id = ' . I_B_Rejected_Stage . ' OR stage_id = ' . E_REJECTED_STAGE . ' THEN 1 END) as total_rejected, ' .
            'COUNT(CASE WHEN stage_id = ' . I_B_Defects_Cured_Stage . ' THEN 1 END) as total_defect_cured, ' .
            'COUNT(CASE WHEN stage_id = ' . LODGING_STAGE . ' OR stage_id =' . DELETE_AND_LODGING_STAGE . ' OR stage_id =' . MARK_AS_ERROR . ' THEN 1 END) as total_lodged_cases, ' .
            'COUNT(CASE WHEN (stage_id = ' . E_Filed_Stage . ' AND en.ref_m_efiled_type_id = ' . E_FILING_TYPE_NEW_CASE . ') OR (stage_id = ' . CDE_ACCEPTED_STAGE . ' AND ref_m_efiled_type_id = ' . E_FILING_TYPE_CDE . ') THEN 1 END) as total_efiled_cases, ' .
            'COUNT(CASE WHEN (stage_id = ' . Document_E_Filed . ' AND en.ref_m_efiled_type_id =' . E_FILING_TYPE_MISC_DOCS . ') THEN 1 END) as total_efiled_docs, ' .
            'COUNT(CASE WHEN stage_id = ' . DEFICIT_COURT_FEE_E_FILED . ' AND en.ref_m_efiled_type_id =' . E_FILING_TYPE_DEFICIT_COURT_FEE . ' THEN 1 END) as total_efiled_deficit, ' .
            'COUNT(CASE WHEN stage_id = ' . HOLD . ' THEN 1 END) as total_hold_cases, ' .
            'COUNT(CASE WHEN stage_id = ' . DISPOSED . ' THEN 1 END) as total_hold_disposed_cases, ' .
            'COUNT(CASE WHEN stage_id = ' . DEFICIT_COURT_FEE . ' THEN 1 END) as deficit_crt_fee, ' .
            'COUNT(CASE WHEN stage_id = ' . IA_E_Filed . ' AND en.ref_m_efiled_type_id = ' . E_FILING_TYPE_IA . ' THEN 1 END) as total_efiled_ia'
        );
        $builder->where('cs.is_active', 'TRUE');
        $builder->where('en.is_active', 'TRUE');
        if ($session->get('login')['AllUserCount'] != 1) {
            $where = '(en.efiling_for_type_id=' . $session->get('login')['admin_for_type_id'] . ' OR en.efiling_for_type_id = ' . E_FILING_TYPE_CAVEAT . ')';
            $builder->where($where);
            $builder->where('en.efiling_for_id', $session->get('login')['admin_for_id']);
            if (!empty($stage_ids)) {
                if (!in_array(LODGING_STAGE, $stage_ids) && !in_array(DELETE_AND_LODGING_STAGE, $stage_ids)) {
                    $builder->where('en.allocated_to', $session->get('login')['id']);
                }
            } else {
                if (!in_array(LODGING_STAGE, $stage_ids) && !in_array(DELETE_AND_LODGING_STAGE, $stage_ids) && $session->get('login')['userid'] != SC_ADMIN) {
                    $builder->where('en.allocated_to', $session->get('login')['id']);
                }
            }
        }
        $query = $builder->get();
        $output = $query->getResult();
        if ($query->getNumRows() >= 1) {
            return $output;
        } else {
            return false;
        }
    }
    
    function get_efilied_nums_submitted_count($stages)
    {
        $created_by = $this->session->userdata['login']['id'];
        $builder = $this->db->table('tbl_efiling_nums as en');
        $builder->join('tbl_efiling_case_status as cs', 'en.registration_id=cs.registration_id');
        $builder->select("COUNT(en.registration_id) as count");
        $builder->where('cs.is_active', 'TRUE');
        $builder->whereNotIn('cs.stage_id', $stages);
        $builder->where('en.created_by', $created_by);
        $query = $builder->get();
        $output = $query->getResult();
        if ($query->getNumRows() >= 1) {
            return $output;
        } else {
            return FALSE;
        }
    }

    public function getSearchResults($created_by, $search)
    {       
        $search = trim($search);
        $condition = '';    
        if (isset($search) && !empty($search)) {
            if ($this->session->get('login')['ref_m_usertype_id'] == USER_DEPARTMENT || $this->session->get('login')['ref_m_usertype_id'] == USER_CLERK) {
                $condition = "
                (cs.is_active = TRUE AND en.sub_created_by ='$created_by' AND en.efiling_no ILIKE '%" . $search . "%')
                OR 
                (cs.is_active = TRUE AND en.sub_created_by ='$created_by' AND cast(en.efiling_year as char) ILIKE'" . $search . "')
                OR
                (cs.is_active = TRUE AND en.sub_created_by ='$created_by' AND ec.pet_name ILIKE '%" . $search . "%')
                OR
                (cs.is_active = TRUE AND en.sub_created_by ='$created_by' AND ec.res_name ILIKE '%" . $search . "%')
                OR
                (cs.is_active = TRUE AND en.sub_created_by ='$created_by' AND ms.cause_title ILIKE '%" . $search . "%')";
            } else {
                $condition = "
                (cs.is_active = TRUE AND en.created_by ='$created_by' AND en.efiling_no ILIKE '%" . $search . "%')
                OR 
                (cs.is_active = TRUE AND en.created_by ='$created_by' AND cast(en.efiling_year as char) ILIKE'" . $search . "')
                OR
                (cs.is_active = TRUE AND en.created_by ='$created_by' AND ec.pet_name ILIKE '%" . $search . "%')
                OR
                (cs.is_active = TRUE AND en.created_by ='$created_by' AND ec.res_name ILIKE '%" . $search . "%')
                OR
                (cs.is_active = TRUE AND en.created_by ='$created_by' AND ms.cause_title ILIKE '%" . $search . "%')";
            }
        }
        $builder = $this->db->table('tbl_efiling_nums as en');
        $builder->select(array(
            'en.efiling_for_type_id', 'en.efiling_for_id', 'en.registration_id', 'en.efiling_no', 'en.efiling_year', 'en.ref_m_efiled_type_id',
            'en.created_by',
            'et.efiling_type', 'cs.stage_id', 'cs.activated_on', 'ds.user_stage_name',
            'ec.cino', 'ec.pet_name', 'ec.res_name', 'ec.fil_case_type_name', 'ec.fil_no', 'ec.fil_year',
            'ec.reg_case_type_name', 'ec.reg_no', 'ec.reg_year',
            'ia.ia_fil_case_type_name', 'ia.ia_filno', 'ia.ia_filyear', 'ia.ia_reg_case_type_name', 'ia.ia_regno', 'ia.ia_regyear', 'ia.cino ia_cnr_num',
            'ms.case_type_name', 'ms.cnr_num', 'ms.fil_no misc_fil_no', 'ms.fil_year misc_fil_year', 'ms.reg_no misc_reg_no', 'ms.reg_year misc_reg_year',
            'ms.cause_title', 'ms.efiling_case_reg_id'
        ));    
        $builder->join('tbl_efiling_case_status as cs', 'en.registration_id=cs.registration_id', 'left');
        $builder->join('m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id', 'left');
        $builder->join('tbl_efiling_civil as ec', 'en.registration_id=ec.ref_m_efiling_nums_registration_id', 'left');
        $builder->join('tbl_misc_doc_filing as ms', 'en.registration_id=ms.ref_m_efiling_nums_registration_id', 'left');
        $builder->join('eia_filing as ia', 'ia.registration_id = en.registration_id ', 'left');
        $builder->join('m_tbl_dashboard_stages as ds', 'cs.stage_id = ds.stage_id', 'left');
        $builder->join('dynamic_users_table as users', 'users.id=en.created_by', 'left');
        $builder->where('en.is_active', 'TRUE');    
        if ($this->session->get('login')['ref_m_usertype_id'] == USER_DEPARTMENT || $this->session->get('login')['ref_m_usertype_id'] == USER_CLERK) {
            $builder->where('en.sub_created_by', $created_by);
        } else {
            $builder->where('en.created_by', $created_by);
        }    
        if (!empty($condition)) {
            $builder->where("($condition)", NULL, FALSE);
        }    
        $builder->orderBy('cs.activated_on', 'ASC');    
        $query = $builder->get();    
        return $query->getResult();
    }
    
}