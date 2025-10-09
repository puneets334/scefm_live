<?php

namespace App\Models\AdminDashboard;

use CodeIgniter\Model;

class StageListModel extends Model
{
    protected $session;
    function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();
    }
    
    public function get_efilied_nums_stage_wise_list_admin($stage_ids, $admin_for_type_id, $admin_for_id, $limit = null, $offset= null)
    {
        $builder =  $this->db->table('efil.tbl_efiling_nums as en');
        $builder->SELECT(array(
            'en.efiling_for_type_id', 'en.efiling_for_id', 'en.ref_m_efiled_type_id',
            'en.efiling_no', 'en.efiling_year', 'en.registration_id', 'en.allocated_on',
            'et.efiling_type','users.account_status',
            'cs.stage_id', 'cs.activated_on', 'en.sub_created_by',
            'new_case_cd.cause_title ecase_cause_title', 'new_case_cd.sc_diary_num', 'new_case_cd.sc_diary_year', 'new_case_cd.sc_diary_date', 'new_case_cd.sc_display_num', 'new_case_cd.sc_reg_date',
            'sc_case.diary_no', 'sc_case.diary_year', 'sc_case.reg_no_display', 'sc_case.cause_title', 'mdia.diary_no icmis_diary_no', 'mdia.diary_year icmis_diary_year', 'ec.pet_name', 'ec.res_name', 'ec.ref_m_efiling_nums_registration_id caveat_reg', 'ec.orgid', 'ec.resorgid','concat(mdia.diary_no,mdia.diary_year) diaryid'
        ));
        $builder->JOIN('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id');
        $builder->JOIN('public.tbl_efiling_caveat as ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id', 'left');
        $builder->JOIN('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $builder->JOIN('efil.tbl_case_details as new_case_cd', 'en.registration_id = new_case_cd.registration_id', 'left');
        $builder->JOIN('efil.tbl_misc_docs_ia as mdia', 'en.registration_id = mdia.registration_id', 'left');
        $builder->JOIN('efil.tbl_sci_cases as sc_case', 'sc_case.diary_no=mdia.diary_no AND sc_case.diary_year = mdia.diary_year', 'left');
        $builder->JOIN('efil.tbl_users users', 'users.id=en.created_by', 'left');
        $builder->WHERE('cs.is_active', 'TRUE');
        $builder->WHERE('en.is_active', 'TRUE');
        if (getSessionData('login')['AllUserCount'] != 1) {
            $where = '(en.efiling_for_type_id=' . $admin_for_type_id . ' or en.efiling_for_type_id = ' . E_FILING_TYPE_CAVEAT . ')';
            $builder->WHERE($where);
            $builder->WHERE('en.efiling_for_id', $admin_for_id);
            if (!in_array(LODGING_STAGE, $stage_ids) && !in_array(DELETE_AND_LODGING_STAGE, $stage_ids) && getSessionData('login')['userid'] != SC_ADMIN) {
                $builder->WHERE('en.allocated_to', getSessionData('login')['id']);
            }
        }
        $builder->whereIn('cs.stage_id', $stage_ids);
        if (in_array(New_Filing_Stage, $stage_ids) && ($admin_for_type_id == E_FILING_TYPE_CAVEAT)) {
            $builder->orderBy('cs.activated_on', 'ASC');
        } else {
            $builder->orderBy('cs.activated_on', 'DESC');
        }
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function get_current_stage($registration_id)
    {
        $builder =  $this->db->table('efil.tbl_efiling_num_status');
        $builder->SELECT('registration_id, efil.tbl_efiling_num_status.stage_id, m_tbl_dashboard_stages.user_stage_name, m_tbl_dashboard_stages.admin_stage_name');
        $builder->JOIN('efil.m_tbl_dashboard_stages', 'm_tbl_dashboard_stages.stage_id =  efil.tbl_efiling_num_status.stage_id');
        $builder->WHERE('efil.tbl_efiling_num_status.registration_id', $registration_id);
        $builder->WHERE(' efil.tbl_efiling_num_status.is_active', TRUE);
        $builder->orderBy(' efil.tbl_efiling_num_status.status_id', 'DESC');
        $builder->LIMIT(1);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getRow();
        } else {
            return false;
        }
    }
    
}