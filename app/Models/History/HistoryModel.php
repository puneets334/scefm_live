<?php

namespace App\Models\History;
use CodeIgniter\Model;

class HistoryModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function get_new_case_details($registration_id) {
        $builder = $this->db->table('efil.tbl_case_details cd');
        $builder->SELECT("*");
        $builder->WHERE('cd.registration_id', $registration_id);
        $builder->WHERE('cd.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $new_case_details = $query->getResultArray();
            return $new_case_details;
        } else {
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
        } else {
            return false;
        }
    }

    function get_stages($registration_id) {
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->SELECT('*');
        $builder->JOIN('efil.m_tbl_dashboard_stages as ds', 'efil.tbl_efiling_num_status.stage_id=ds.stage_id', 'LEFT');
        $builder->WHERE('efil.tbl_efiling_num_status.registration_id', $registration_id);
        $builder->orderBy('efil.tbl_efiling_num_status.activated_on', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_court_fee_payment_history($regid) {
        $builder = $this->db->table('efil.tbl_court_fee_payment');
        $builder->SELECT('*');
        $builder->WHERE('efil.tbl_court_fee_payment.registration_id', $regid);
        $builder->WHERE('efil.tbl_court_fee_payment.is_deleted', FALSE);
        $builder->orderBy('id', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function get_allocated_history($registration_id) {
        $builder = $this->db->table('efil.tbl_efiling_allocation ea');
        $builder->SELECT("ea.*,concat(users.first_name,' ',users.last_name) as admin_name");
        $builder->JOIN('efil.tbl_users users', 'users.id = ea.admin_id', 'LEFT');
        $builder->WHERE('registration_id', $registration_id);
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
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
        $builder->orderBy('defect_date', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
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
        } else {
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
        } else {
            return false;
        }
    }

}