<?php

namespace App\Models\Acknowledgement;
use CodeIgniter\Model;

class AcknowledgementModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function get_new_case_details($registration_id) {
        $builder = $this->db->table('efil.tbl_case_details cd');
        $builder->SELECT("*");
        $builder->JOIN( "icmis.casetype cs",'cd.sc_case_type_id =cs.casecode','LEFT');
        $builder->JOIN("efil.tbl_efiling_nums ten",'cd.registration_id=ten.registration_id','LEFT');
        $builder->WHERE('cd.registration_id', $registration_id);
        $builder->WHERE('cd.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $new_case_details = $query->getResultArray();
            return $query->getResultArray();
        } else{
            return FALSE;
        }
    }
    public function get_caveat_details($registration_id) {
        $builder = $this->db->table('efil.tbl_case_details cd');
        $builder->SELECT("*");
        $builder->JOIN( "icmis.casetype cs",'cd.sc_case_type_id =cs.casecode','LEFT');
        $builder->JOIN("efil.tbl_efiling_nums ten",'cd.registration_id=ten.registration_id','LEFT');
        $builder->WHERE('cd.registration_id', $registration_id);
        $builder->WHERE('cd.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $new_case_details = $query->getResultArray();
            return $query->getResultArray();
        } else{
            return FALSE;
        }
    }
    function get_efiled_by_user($user_id) {
        $builder = $this->db->table('efil.tbl_users');
        $builder->SELECT('id,ref_m_usertype_id,first_name, last_name, moblie_number, emailid');
        $builder->WHERE('id', $user_id);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else{
            return false;
        }
    }

    function get_stages($registration_id) {
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->SELECT('*');
        $builder->JOIN('efil.m_tbl_dashboard_stages as ds', 'efil.tbl_efiling_num_status.stage_id=ds.stage_id', 'LEFT');
        $builder->WHERE('efil.tbl_efiling_num_status.registration_id', $registration_id);
        $builder->ORDERBY('efil.tbl_efiling_num_status.activated_on', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else{
            return false;
        }
    }

    public function get_court_fee_payment_history($regid) {
        $builder = $this->db->table('efil.tbl_court_fee_payment');
        $builder->SELECT('*');
        $builder->WHERE('efil.tbl_court_fee_payment.registration_id', $regid);
        $builder->WHERE('efil.tbl_court_fee_payment.is_deleted', FALSE);
        $builder->ORDERBY('id', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResultArray();
            return $result;
        } else{
            return false;
        }
    }

    function get_allocated_history($registration_id) {
        $builder = $this->db->table('efil.tbl_efiling_allocation ea');
        $builder->SELECT("ea.*,concat(users.first_name,' ',users.last_name) as admin_name");
        $builder->JOIN('efil.tbl_users users', 'users.id = ea.admin_id', 'LEFT');
        $builder->WHERE('registration_id', $registration_id);
        $builder->ORDERBY('id', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else{
            return FALSE;
        }
    }

    public function get_intials_defects_for_history($regid) {
        if (!(isset($regid) && !empty($regid))) {
            return FALSE;
        }
        $builder = $this->db->table('efil.tbl_initial_defects');
        $builder->SELECT('*');
        $builder->WHERE('registration_id', $regid);
        $builder->ORDERBY('defect_date', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else{
            return false;
        }
    }

    function getPdfDoc($pdf_id, $uploaded_by) {
        $builder = $this->db->table('efil.tbl_efiled_docs');
        $builder->SELECT('tbl_efiled_docs.*');
        $builder->WHERE('tbl_efiled_docs.doc_id', $pdf_id);
        $builder->WHERE('tbl_efiled_docs.uploaded_by', $uploaded_by);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else{
            return false;
        }
    }
    
    function get_pdf_for_admin($pdf_id) {
        $builder = $this->db->table('efil.tbl_efiled_docs');
        $builder->SELECT('tbl_efiled_docs.file_name,tbl_efiled_docs.file_uploaded_path');
        $builder->WHERE('tbl_efiled_docs.doc_id', $pdf_id);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else{
            return false;
        }
    }

    function get_allocated_to_details($registration_id) {
        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->SELECT("tbl_efiling_nums.*,concat(users.first_name,' ',users.last_name) as admin_name,(CASE WHEN tbl_efiling_nums.efiling_for_type_id =" . E_FILING_FOR_ESTABLISHMENT . " THEN (SELECT concat(estab_name,', ',dist_name,', ',state)  FROM efil.m_tbl_establishments est
                LEFT JOIN efil.m_tbl_state st on est.state_code = st.state_id
                LEFT JOIN efil.m_tbl_districts dist on est.ref_m_tbl_districts_id = dist.id
                WHERE est.id = tbl_efiling_nums.efiling_for_id )
                ELSE (select concat(hc_name,' High Court') FROM efil.m_tbl_high_courts hc
                WHERE hc.id = tbl_efiling_nums.efiling_for_id) END )
                as efiling_for_name");
        $builder->JOIN('efil.tbl_users users', 'users.id = tbl_efiling_nums.allocated_to', 'LEFT');
        $builder->WHERE('tbl_efiling_nums.registration_id', $registration_id);
        $builder->WHERE('tbl_efiling_nums.is_active', TRUE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else{
            return false;
        }
    }

    function get_submitted_on($registration_id) {
        $array = array(Initial_Approaval_Pending_Stage, I_B_Defects_Cured_Stage, Initial_Defects_Cured_Stage);
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->SELECT("*");
        $builder->WHERE('tbl_efiling_num_status.registration_id', $registration_id);
        $builder->WHEREIN('tbl_efiling_num_status.stage_id', $array);
        $builder->ORDERBY('status_id', 'DESC');
        $builder->LIMIT(1, 0);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else{
            return false;
        }
    }

    public function get_payment_status($regid) {
        
        $builder = $this->db->table('efil.tbl_court_fee_payment');
        $builder->SELECT('*');
        $builder->WHERE('registration_id', $regid);
        $builder->ORDERBY('id', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else{
            return false;
        }
    }

    function get_IA_No($registration_id) {
        $builder = $this->db->table('efil.tbl_misc_doc_filing as en');
        $builder->SELECT('count(id)');
        $builder->WHERE('efiling_case_reg_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResult();
            return $result[0]->count;
        } else{
            return false;
        }
    }

    function get_payment_details($registration_id){
        $builder = $this->db->table('efil.tbl_court_fee_payment cfp');
        $builder->SELECT('*');
        $builder->WHERE('cfp.registration_id', $registration_id);
        $builder->WHERE('cfp.payment_status', 'Y');
        $builder->WHERE('cfp.is_deleted', FALSE);
        $builder->ORDERBY('cfp.id', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else{
            return FALSE;
        }
    }

}