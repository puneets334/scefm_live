<?php

namespace App\Models\IA;
use CodeIgniter\Model;

class GetDetailsModel extends Model {

    function __construct() {
        parent::__construct();
    }

    function get_case_details($registration_id) {
        $builder = $this->db->table('efil.tbl_efiling_nums en');
        $builder->SELECT("en.*,misc_ia.*,sc.reg_no_display,sc.cause_title,sc.c_status,sc.p_no,sc.r_no");
        $builder->JOIN('efil.tbl_misc_docs_ia misc_ia', 'en.registration_id = misc_ia.registration_id');
        $builder->JOIN('efil.tbl_sci_cases sc', 'misc_ia.diary_no = sc.diary_no AND misc_ia.diary_year = sc.diary_year');
        $builder->WHERE('en.registration_id', $registration_id);
        $builder->WHERE('en.is_deleted', FALSE);
        $builder->WHERE('misc_ia.is_deleted', FALSE);
        $query = $builder->get();
        // echo  $this->db->last_query();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

}