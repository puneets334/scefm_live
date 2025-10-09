<?php

namespace App\Models\IA;
use CodeIgniter\Model;
class GetDetailsModel extends Model {

    function __construct() {
        parent::__construct();
        $db = \Config\Database::connect();
    }    

    function get_case_details($registration_id) {
        $builder = $this->db->table('efil.tbl_efiling_nums en');
        $builder->SELECT("en.*,misc_ia.*,sc.reg_no_display,sc.cause_title,sc.c_status,sc.p_no,sc.r_no,string_agg(ci.party_name, ',')  as intervenorname");
        $builder->JOIN('efil.tbl_misc_docs_ia misc_ia', 'en.registration_id = misc_ia.registration_id');
        $builder->JOIN('efil.tbl_sci_cases sc', 'misc_ia.diary_no = sc.diary_no AND misc_ia.diary_year = sc.diary_year');
        $builder->JOIN('efil.tbl_case_intervenors ci', 'misc_ia.registration_id = ci.registration_id ','left');
        $builder->WHERE('en.registration_id', $registration_id);
        $builder->WHERE('en.is_deleted', FALSE);
        $builder->WHERE('misc_ia.is_deleted', FALSE);
        $builder->groupBy('misc_ia.registration_id,en.registration_id,misc_ia.id,sc.reg_no_display,sc.cause_title,sc.c_status,sc.p_no,sc.r_no,sc.diary_no,sc.diary_year');
        $query = $builder->get();        
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_parties_details($registration_id) {
        $builder = $this->db->table('efil.tbl_efiling_nums en');
        $builder->SELECT("en.*,cp.*");
        $builder->JOIN('efil.tbl_misc_docs_ia misc_ia', 'en.registration_id = misc_ia.registration_id');
        $builder->JOIN('efil.tbl_sci_cases sc', 'misc_ia.diary_no = sc.diary_no AND misc_ia.diary_year = sc.diary_year');
        $builder->JOIN('efil.tbl_sci_cases_parties cp', 'misc_ia.diary_no = cp.diary_no AND misc_ia.diary_year = cp.diary_year');
        $builder->WHERE('en.registration_id', $registration_id);
        $builder->WHERE('en.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

}