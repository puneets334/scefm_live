<?php

namespace App\Models\Certificate;
use CodeIgniter\Model;

class DetailsModel extends Model {

    function __construct() {
        parent::__construct();
        $db = \Config\Database::connect();  
    }

    function get_efiling_number($registration_id) {
        $builder=$this->db->table("efil.tbl_efiling_nums");
        $builder->select("efiling_no");
        $builder->where("registration_id",$registration_id);
        $query=$builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_case_id($diary_no,$diary_year) {
        $builder=$this->db->table("efil.tbl_sci_cases");
        $builder->select("id");
        $builder->where("diary_no like ",$diary_no);
        $builder->where("diary_year like ",$diary_year);
        $query=$builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function add_details($details,$registration_id) {
        $this->db->transStart();
        $builder=$this->db->table('efil.tbl_certificate_request');
        $builder->INSERT($details);
        $update_data = array(
            'deactivated_on' => date('Y-m-d H:i:s'),
            'is_active' => FALSE,
            'updated_by' => $_SESSION['login']['id'],
            'updated_by_ip' => getClientIP()
        );
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('is_active', TRUE);
        $builder->UPDATE('efil.tbl_efiling_num_status', $update_data);
        $insert_data = array(
            'registration_id' => $registration_id,
            'stage_id' => CERTIFICATE_E_FILED,
            'activated_on' => date('Y-m-d H:i:s'),
            'is_active' => TRUE,
            'activated_by' => $_SESSION['login']['id'],
            'activated_by_ip' => getClientIP()
        );
        $builder=$this->db->table('efil.tbl_efiling_num_status');
        $builder->INSERT($insert_data);
        if ($this->db->insertID()) {
            $this->db->transComplete();
        }
        if ($this->db->transStatus() === TRUE) {
            return true;
        } else {
            return false;
        }
    }

}