<?php

namespace App\Models\MiscellaneousDocs;


use CodeIgniter\Model;

class GetDetailsModel extends Model
{

    function __construct()
    {
        parent::__construct();
        $db      = \Config\Database::connect();
    }

    public function get_case_details($registration_id)
    {

        // $builder = $this->db->table('efil.tbl_efiling_nums en');
        // $builder->select("en.*, misc_ia.*, sc.diary_no, sc.diary_year, sc.reg_no_display, sc.cause_title, sc.c_status, sc.p_no, sc.r_no, GROUP_CONCAT(ci.party_name SEPARATOR ',') AS intervenorname");
        // $builder->join('efil.tbl_misc_docs_ia misc_ia', 'en.registration_id = misc_ia.registration_id');
        // $builder->join('efil.tbl_sci_cases sc', 'misc_ia.diary_no = sc.diary_no AND misc_ia.diary_year = sc.diary_year');
        // $builder->join('efil.tbl_case_intervenors ci', 'misc_ia.registration_id = ci.registration_id', 'left');
        // $builder->where('en.registration_id', $registration_id);
        // $builder->where('en.is_deleted', false);
        // $builder->where('misc_ia.is_deleted', false);
        // $builder->groupBy('misc_ia.registration_id, en.registration_id, misc_ia.id, sc.reg_no_display, sc.cause_title, sc.c_status, sc.p_no, sc.r_no, sc.diary_no, sc.diary_year');

        $builder = $this->db->table('efil.tbl_efiling_nums en');
        $builder->select('en.*, misc_ia.*, sc.diary_no, sc.diary_year, sc.reg_no_display, sc.cause_title, sc.c_status, sc.p_no, sc.r_no, string_agg(ci.party_name, \',\') as intervenorname');
        $builder->join('efil.tbl_misc_docs_ia misc_ia', 'en.registration_id = misc_ia.registration_id');
        $builder->join('efil.tbl_sci_cases sc', 'misc_ia.diary_no = sc.diary_no AND misc_ia.diary_year = sc.diary_year');
        $builder->join('efil.tbl_case_intervenors ci', 'misc_ia.registration_id = ci.registration_id', 'left');
        $builder->where('en.registration_id', $registration_id);
        $builder->where('en.is_deleted', FALSE);
        $builder->where('misc_ia.is_deleted', FALSE);
        $builder->groupBy('en.registration_id, misc_ia.registration_id, misc_ia.id, sc.reg_no_display, sc.cause_title, sc.c_status, sc.p_no, sc.r_no, sc.diary_no, sc.diary_year');

        $query = $builder->get();
        if ($query && $query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
}
