<?php

namespace App\Models\Appearance;
use CodeIgniter\Model;
use Config\Database;
use Config\Services;

class AdvocateModel extends Model {

    protected $DBGroup = 'sci_cmis_final'; 
    protected $db3;
    protected $db2;

    public function __construct() {
        parent::__construct();
        $this->db3 = Database::connect('e_services'); 
        $this->db2 = Database::connect('sci_cmis_final'); 
    }

    public function getListedCases($aor_code) {
        $builder = $this->db2->table('main as m')
            ->join('heardt as h', 'h.diary_no = m.diary_no')
            ->join('cl_printed as c', 'c.next_dt = h.next_dt AND c.roster_id = h.roster_id AND c.part = h.clno')
            ->join('roster as r', 'r.id = h.roster_id')
            ->join('advocate as a', 'a.diary_no = m.diary_no')
            ->join('bar as b', 'b.bar_id = a.advocate_id')
            ->select('m.diary_no, m.reg_no_display, m.pet_name, m.res_name, h.next_dt, h.brd_slno, h.main_supp_flag, a.pet_res, r.courtno, m.pno, m.rno, m.c_status')
            ->where('b.aor_code', $aor_code)
            ->whereIn('m.c_status', ['P','D'])
            // ->where('h.next_dt >=', date('Y-m-d'))
            ->where('c.display', 'Y')
            ->where('r.display', 'Y')
            ->where('a.display', 'Y')
            ->orderBy('h.next_dt')
            ->orderBy('r.courtno')
            ->orderBy('h.brd_slno')
            ->distinct();
        $query = $builder->get();
        $results = $query->getResult();
        return $results;
    }
    
    public function get_pending_reg_other_matters_data($aor_code) {
        $subquery1 = $this->db->table('main m1')
        ->select('DISTINCT m1.diary_no, reg_no_display, pet_name, res_name, pno, rno')
        ->select('(CASE WHEN DATEDIFF(h1.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h1.next_dt ELSE "" END) AS next_date')
        ->join('heardt h1', 'm1.diary_no = h1.diary_no', 'left')
        ->join('advocate a1', 'm1.diary_no = a1.diary_no', 'left')
        ->where('a1.advocate_id', $aor_code)
        ->where('a1.display', 'Y')
        ->where('m1.c_status', 'P')
        ->where('a1.pet_res NOT IN', ['P', 'R'])
        ->where('m1.active_fil_no IS NOT NULL')
        ->where('m1.fil_no IS NOT NULL');
        $unionQuery = $this->db->table('temp')
            ->union($subquery1)
            ->orderBy('diary_no');
        $query = $this->db->table('(' . $unionQuery . ') temp')
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function getDiaryDetails($diary_no) {
        $builder = $this->db->table('main');
        $builder->where('diary_no', $diary_no);
        $query = $builder->get();
        $output = $query->getRow();
        return $output;
    }
    
    public function getAddedAdvocatesInDiary($data) {
        $session = \Config\Services::session();
        $builder = $this->db3->table('appearing_in_diary');        
        $builder->where('diary_no', $data['diary_no']);
        $builder->where('list_date', $data['next_dt']);
        $builder->where('is_active', '1');
        $builder->where('aor_code', getSessionData('login')['aor_code']);
        $builder->orderBy('priority');
        $query = $builder->get();
        if ($query === false) {
            $error = $this->db3->error();
            log_message('error', 'Query Error: ' . print_r($error, true));
            return false;
        }
        $result = $query->getResult();
        return $result;
    }

    public function getSubmittedAdvocatesInDiary($data) {
        $session = \Config\Services::session();
        $builder = $this->db3->table('appearing_in_diary');        
        $builder->where('diary_no', $data['diary_no']);
        $builder->where('list_date', $data['next_dt']);
        $builder->where('is_active', '1');
        $builder->where('is_submitted', '1');
        $builder->where('aor_code', getSessionData('login')['aor_code']);
        $builder->orderBy('priority');
        $value = $builder->get()->getResult();
        return $value;
    }

    public static function getAppearingDiaryNosOnly($cause_list_date) {
        $db3 = \Config\Database::connect('e_services'); 
        $session = \Config\Services::session();
        $aor_code = $session->get('login')['aor_code'];
        $builder = $db3->table('appearing_in_diary');    
        $builder->select('appearing_for, diary_no, item_no, court_no, list_date');
        $builder->where('is_active', '1');
        $builder->where('is_submitted', '1');
        $builder->where('list_date', $cause_list_date);
        $builder->where('aor_code', $aor_code);
        $builder->distinct();
        $value = $builder->get()->getResult();
        return $value;
    }

    public static function getAppearingAdvocates($raw_data) {
        $db3 = \Config\Database::connect('e_services'); 
        $session = \Config\Services::session();
        $aor_code = $session->get('login')['aor_code'];
        $builder = $db3->table('appearing_in_diary'); 
        $builder->where('is_active', '1');
        $builder->where('diary_no', $raw_data->diary_no);
        $builder->where('list_date', $raw_data->list_date);
        $builder->where('aor_code', $aor_code);
        $builder->orderBy('priority');
        $query = $builder->get();
        if ($query === false) {           
            return false;
        } else {
            $result = $query->getResult();           
        }
        return $result;
    }
    
    public static function getPreviousListingDate($p) {
        $db3 = \Config\Database::connect('e_services'); 
        $session = \Config\Services::session();
        $aor_code = $session->get('login')['aor_code'];
        $builder = $db3->table('appearing_in_diary'); 
        $builder->select('list_date');
        $builder->where('is_active', '1');
        $builder->where('diary_no', $p['diary_no']);
        $builder->where('list_date <', $p['next_dt']);
        $builder->where('aor_code', $aor_code);
        $builder->orderBy('list_date', 'DESC');
        $query = $builder->get();
        if ($query === false) {           
            return false;
        } else {
            $result = $query->getRow();           
        }
        return $result;
    }
    
    public static function getPreviousListAdvocates($a, $b) {
        $db3 = \Config\Database::connect('e_services'); 
        $session = \Config\Services::session();
        $aor_code = $session->get('login')['aor_code'];
        $builder = $db3->table('appearing_in_diary'); 
        $builder->where('is_active', '1');
        $builder->where('diary_no', $a['diary_no']);
        $builder->where('list_date', $b->list_date);
        $builder->where('aor_code', $aor_code);
        $builder->orderBy('priority', 'ASC');
        $query = $builder->get();
        if ($query === false) {           
            return false;
        } else {
            $result = $query->getResult();           
        }
        return $result;
    }

    public static function getAdvocateMasterList($a) {
        $db3 = \Config\Database::connect('e_services'); 
        $session = \Config\Services::session();
        $aor_code = $session->get('login')['aor_code'];
        $builder = $db3->table('appearing_in_diary'); 
        $builder->where('is_active', '1');
        $builder->whereIn('id', $a);
        $builder->where('aor_code', $aor_code);
        $builder->orderBy('priority', 'ASC');
        $query = $builder->get();
        if ($query === false) {           
            return false;
        } else {
            $result = $query->getResult();           
        }
        return $result;
    }

}