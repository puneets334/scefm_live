<?php

namespace App\Models\Caveat;
use CodeIgniter\Model;

class CaveatCommonModel extends Model {
    protected $session;
    function __construct() {
        parent::__construct();
        $this->session = \Config\Services::session();
    }

    function get_efiling_civil_details($registration_id) {
		$builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->SELECT("tbl_efiling_nums.*,tbl_efiling_caveat.*,
            (CASE WHEN tbl_efiling_nums.efiling_for_type_id =" . E_FILING_FOR_ESTABLISHMENT . " 
                THEN (SELECT concat(estname,', ',dist_name,', ',state)  
                FROM public.m_tbl_establishments est
            LEFT JOIN efil.m_tbl_state st on est.state_code = st.state_id
            LEFT JOIN efil.m_tbl_districts dist on est.ref_m_tbl_districts_id = dist.id
            WHERE est.id = tbl_efiling_nums.efiling_for_id ) 
            ELSE (SELECT concat(hc_name,' High Court') FROM m_tbl_high_courts hc
            WHERE hc.id = tbl_efiling_nums.efiling_for_id) END ) 
            as efiling_for_name");
        $builder->JOIN('public.tbl_efiling_caveat', 'tbl_efiling_nums.registration_id = tbl_efiling_caveat.ref_m_efiling_nums_registration_id');
        $builder->WHERE('efil.tbl_efiling_nums.registration_id', $registration_id);
        $builder->WHERE('efil.tbl_efiling_nums.is_active', TRUE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function get_efiling_civil_master_value($registration_id) {
		$builder = $this->db->table('tbl_cis_masters_values');
        $builder->SELECT('*');
        $builder->WHERE('registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function count_efilied_nums_on_draft() {
        $created_by = getSessionData('login')['id'];
        $db = \Config\Database::connect();    
        $builder = $db->table('efil.tbl_efiling_nums as en');
        $builder->select('COUNT(CASE WHEN cs.stage_id IN (' . Draft_Stage . ') THEN 1 END) as total_drafts');
        $builder->join('tbl_efiling_case_status as cs', 'en.registration_id = cs.registration_id', 'INNER');
        $builder->where('cs.is_active', 'TRUE');
        $builder->where('en.is_active', 'TRUE');    
        if (session()->get('login')['ref_m_usertype_id'] == USER_CLERK) {
            $builder->where('en.sub_created_by', $created_by);
        } else {
            $builder->where('en.created_by', $created_by);
        }    
        $query = $builder->get();    
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }
    
    function case_type_id_in_array($national_code, $civil_or_criminal, $court_type, $state_code) {
		$builder = $this->db->table('m_tbl_cis_case_type');
        $builder->SELECT('array_agg("cis_case_type_id")');
        $builder->WHERE('national_code', $national_code);
        $builder->WHERE('efiling_for_type_id', $court_type);
        $builder->WHERE('state_code', $state_code);
        $builder->WHERE('is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

}