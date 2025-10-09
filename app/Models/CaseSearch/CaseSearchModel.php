<?php

namespace App\Models\CaseSearch;
use CodeIgniter\Model;

class CaseSearchModel extends Model {

    function __construct() {
        parent::__construct();
        $db = \Config\Database::connect();

    }

    function get_sci_case_type() {
        $builder=$this->db->table('icmis.casetype');
        $builder->SELECT("casecode, casename");
        $builder->WHERE('display', 'Y');
        $builder->orderBy("casename", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    function get_diary_details($diary_no, $diary_year) {
        $builder=$this->db->table('efil.tbl_sci_cases');
        $builder->SELECT("*");
        $builder->WHERE('diary_no', $diary_no);
        $builder->WHERE('diary_year', $diary_year);
        $builder->WHERE('is_deleted', FALSE);
        $query = $builder->get();
        return $query->getResult();
    }

    function add_update_sci_cases_details($sc_case_details, $diary_data = NULL) {
        if ($diary_data == NULL) {
            $builder=$this->db->table('efil.tbl_sci_cases');
            $builder->INSERT($sc_case_details);
            if ($this->db->insertID()) {
                $_SESSION['tbl_sci_case_id']=$this->db->insertID();
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            $builder=$this->db->table('efil.tbl_sci_cases');
            $builder->WHERE('diary_no', $diary_data['diary_no']);
            $builder->WHERE('diary_year', $diary_data['diary_year']);
            $builder->set($sc_case_details);
            $builder->update();
            if ($this->db->affectedRows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }        
    }

    public function addInterVention($interventionData=array()) {
        if (isset($interventionData) && !empty($interventionData) && count($interventionData) > 0) {
            $builder=$this->db->table('efil.tbl_case_intervenors');
            $builder->INSERT($interventionData);
            if ($this->db->insertID()) {
                return $this->db->insertID();
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

}