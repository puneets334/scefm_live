<?php

namespace App\Models\MyCases;
use CodeIgniter\Model;

class ListsModel extends Model {

    function __construct() {
        parent::__construct();
        $db = \Config\Database::connect();
        // $this->common_db = $this->load->database('common_db', TRUE);
    }

    public function get_total_matters($aor_code) {
        $subquery = $this->db->table('main')
            ->select('diary_no')
            ->where('pet_adv_id', $aor_code)
            ->get();
        $subquery2 = $this->db->table('advocate')
            ->select('diary_no')
            ->where('advocate_id', $aor_code)
            ->where('display', 'Y')
            ->get();
        $unionQuery = $this->db->table('temp')
            ->select('diary_no')
            ->union($subquery)
            ->union($subquery2)
            ->get();
        $query = $this->db->table($unionQuery)
            ->selectCount('DISTINCT(diary_no)')
            ->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_pending_matters($aor_code) {
        $subquery1 = $this->db->table('main')
            ->select('diary_no')
            ->where('pet_adv_id', $aor_code)
            ->where('c_status', 'P')
            ->get();
        $subquery2 = $this->db->table('advocate a')
            ->select('a.diary_no')
            ->join('main m', 'a.diary_no = m.diary_no')
            ->where('a.advocate_id', $aor_code)
            ->where('a.display', 'Y')
            ->where('m.c_status', 'P')
            ->get();
        $unionQuery = $this->db->table('temp')
            ->select('diary_no')
            ->union($subquery1)
            ->union($subquery2)
            ->get();
        $query = $this->db->table($unionQuery)
            ->selectCount('DISTINCT(diary_no)')
            ->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_pending_reg_matters($aor_code) {
        $subquery1 = $this->db->table('main')
            ->select('diary_no')
            ->where('pet_adv_id', $aor_code)
            ->where('c_status', 'P')
            ->where('active_fil_no IS NOT NULL')
            ->where('fil_no IS NOT NULL')
            ->get();
        $subquery2 = $this->db->table('advocate a')
            ->select('a.diary_no')
            ->join('main m', 'a.diary_no = m.diary_no')
            ->where('a.advocate_id', $aor_code)
            ->where('a.display', 'Y')
            ->where('m.c_status', 'P')
            ->where('a.active_fil_no IS NOT NULL')
            ->where('a.fil_no IS NOT NULL')
            ->get();
        $unionQuery = $this->db->table('temp')
            ->select('diary_no')
            ->union($subquery1)
            ->union($subquery2)
            ->get();
        $query = $this->db->table('(' . $unionQuery . ') temp')
            ->selectCount('DISTINCT(diary_no)')
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_pending_reg_pet_res_matters($aor_code) {
        $subquery1 = $this->db->table('main')
            ->select('diary_no, "Petitioner" as grp')
            ->where('pet_adv_id', $aor_code)
            ->where('c_status', 'P')
            ->where('active_fil_no IS NOT NULL')
            ->where('fil_no IS NOT NULL');
        $subquery2 = $this->db->table('advocate a')
            ->select('a.diary_no')
            ->select("CASE
                ['pet_res' => 'P', 'Petitioner'],
                ['pet_res' => 'R', 'Respondent'],
                ['pet_res NOT IN' => ['P', 'R'], 'Other']"
            )
            ->join('main m', 'a.diary_no = m.diary_no')
            ->where('a.advocate_id', $aor_code)
            ->where('a.display', 'Y')
            ->where('m.c_status', 'P')
            ->where('a.active_fil_no IS NOT NULL')
            ->where('a.fil_no IS NOT NULL');
        $unionQuery = $this->db->table('temp')
            ->select('diary_no, grp')
            ->union($subquery1)
            ->union($subquery2);
        $query = $this->db->table('(' . $unionQuery . ') temp')
            ->select('COUNT(DISTINCT(diary_no)) as count, grp')
            ->groupBy('grp')
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_pending_un_reg_matters($aor_code) {
        $subquery1 = $this->db->table('main')
            ->select('diary_no')
            ->where('pet_adv_id', $aor_code)
            ->where('c_status', 'P')
            ->groupStart()
            ->where('active_fil_no IS NULL', null, false)
            ->orWhere('fil_no IS NULL', null, false)
            ->groupEnd();
        $subquery2 = $this->db->table('advocate a')
            ->select('a.diary_no')
            ->join('main m', 'a.diary_no = m.diary_no')
            ->where('a.advocate_id', $aor_code)
            ->where('a.display', 'Y')
            ->where('m.c_status', 'P')
            ->groupStart()
            ->where('a.active_fil_no IS NULL', null, false)
            ->orWhere('a.fil_no IS NULL', null, false)
            ->groupEnd();
        $unionQuery = $this->db->table('temp')
            ->select('diary_no')
            ->union($subquery1)
            ->union($subquery2);
        $query = $this->db->table('(' . $unionQuery . ') temp')
            ->selectCount('DISTINCT(diary_no)')
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_pending_un_reg_pet_res_matters($aor_code) {
        $subquery1 = $this->db->table('main')
            ->select('diary_no, "Petitioner" as grp')
            ->where('pet_adv_id', $aor_code)
            ->where('c_status', 'P')
            ->groupStart()
            ->where('active_fil_no IS NULL', null, false)
            ->orWhere('fil_no IS NULL', null, false)
            ->groupEnd();
        $subquery2 = $this->db->table('advocate a')
            ->select('a.diary_no, 
            CASE 
                WHEN pet_res = "P" THEN "Petitioner"
                WHEN pet_res = "R" THEN "Respondent"
                ELSE "Other"
            END as grp')
            ->join('main m', 'a.diary_no = m.diary_no')
            ->where('a.advocate_id', $aor_code)
            ->where('a.display', 'Y')
            ->where('m.c_status', 'P')
            ->groupStart()
            ->where('a.active_fil_no IS NULL', null, false)
            ->orWhere('a.fil_no IS NULL', null, false)
            ->groupEnd();
        $unionQuery = $this->db->table('temp')
            ->select('diary_no, grp')
            ->union($subquery1)
            ->union($subquery2);
        $query = $this->db->table('(' . $unionQuery . ') temp')
            ->select('COUNT(DISTINCT diary_no) as count, grp')
            ->groupBy('grp')
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_disposed_matters($aor_code) {
        $subquery1 = $this->db->table('main')
            ->select('diary_no')
            ->where('pet_adv_id', $aor_code)
            ->where('c_status', 'D');
        $subquery2 = $this->db->table('advocate a')
            ->select('a.diary_no')
            ->join('main m', 'a.diary_no = m.diary_no')
            ->where('a.advocate_id', $aor_code)
            ->where('a.display', 'Y')
            ->where('m.c_status', 'D');
        $unionQuery = $this->db->table('temp')
            ->select('diary_no')
            ->union($subquery1)
            ->union($subquery2);
        $query = $this->db->table('(' . $unionQuery . ') temp')
            ->select('COUNT(DISTINCT diary_no) as count')
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_total_matters_data($aor_code) {
        $subquery1 = $this->db->table('main m1')
            ->select('DISTINCT m1.diary_no, reg_no_display, pet_name, res_name, pno, rno')
            ->select('(CASE WHEN DATEDIFF(h1.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h1.next_dt ELSE "" END) AS next_date')
            ->join('heardt h1', 'm1.diary_no = h1.diary_no', 'left')
            ->where('m1.pet_adv_id', $aor_code);
        $subquery2 = $this->db->table('main m2')
            ->select('DISTINCT m2.diary_no, reg_no_display, pet_name, res_name, pno, rno')
            ->select('(CASE WHEN DATEDIFF(h2.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h2.next_dt ELSE "" END) AS next_date')
            ->join('advocate a', 'm2.diary_no = a.diary_no', 'left')
            ->join('heardt h2', 'm2.diary_no = h2.diary_no', 'left')
            ->where('a.advocate_id', $aor_code)
            ->where('a.display', 'Y');
        $unionQuery = $this->db->table('temp')
            ->union($subquery1)
            ->union($subquery2)
            ->orderBy('diary_no');
        $query = $this->db->table('(' . $unionQuery . ') temp')
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_pending_matters_data($aor_code) {
        $subquery1 = $this->db->table('main m1')
            ->select('DISTINCT m1.diary_no, reg_no_display, pet_name, res_name, pno, rno')
            ->select('(CASE WHEN DATEDIFF(h1.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h1.next_dt ELSE "" END) AS next_date')
            ->join('heardt h1', 'm1.diary_no = h1.diary_no', 'left')
            ->where('m1.pet_adv_id', $aor_code)
            ->where('m1.c_status', 'P');
        $subquery2 = $this->db->table('main m2')
            ->select('DISTINCT m2.diary_no, reg_no_display, pet_name, res_name, pno, rno')
            ->select('(CASE WHEN DATEDIFF(h2.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h2.next_dt ELSE "" END) AS next_date')
            ->join('advocate a', 'm2.diary_no = a.diary_no', 'left')
            ->join('heardt h2', 'm2.diary_no = h2.diary_no', 'left')
            ->where('a.advocate_id', $aor_code)
            ->where('a.display', 'Y')
            ->where('m2.c_status', 'P');
        $unionQuery = $this->db->table('temp')
            ->union($subquery1)
            ->union($subquery2)
            ->orderBy('diary_no');
        $query = $this->db->table('(' . $unionQuery . ') temp')
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_disposed_matters_data($aor_code) {
        $subquery1 = $this->db->table('main m1')
            ->select('DISTINCT m1.diary_no, reg_no_display, pet_name, res_name, pno, rno')
            ->select('(CASE WHEN DATEDIFF(h1.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h1.next_dt ELSE "" END) AS next_date')
            ->join('heardt h1', 'm1.diary_no = h1.diary_no', 'left')
            ->where('m1.pet_adv_id', $aor_code)
            ->where('m1.c_status', 'D');
        $subquery2 = $this->db->table('main m2')
            ->select('DISTINCT m2.diary_no, reg_no_display, pet_name, res_name, pno, rno')
            ->select('(CASE WHEN DATEDIFF(h2.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h2.next_dt ELSE "" END) AS next_date')
            ->join('advocate a', 'm2.diary_no = a.diary_no', 'left')
            ->join('heardt h2', 'm2.diary_no = h2.diary_no', 'left')
            ->where('a.advocate_id', $aor_code)
            ->where('a.display', 'Y')
            ->where('m2.c_status', 'D');
        $unionQuery = $this->db->table('temp')
            ->union($subquery1)
            ->union($subquery2)
            ->orderBy('diary_no');
        $query = $this->db->table('(' . $unionQuery . ') temp')
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_pending_reg_matters_data($aor_code) {
        $subquery1 = $this->db->table('main m1')
            ->select('DISTINCT m1.diary_no, reg_no_display, pet_name, res_name, pno, rno')
            ->select('(CASE WHEN DATEDIFF(h1.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h1.next_dt ELSE "" END) AS next_date')
            ->join('heardt h1', 'm1.diary_no = h1.diary_no', 'left')
            ->where('m1.pet_adv_id', $aor_code)
            ->where('m1.c_status', 'P')
            ->where('m1.active_fil_no IS NOT NULL')
            ->where('m1.fil_no IS NOT NULL');
        $subquery2 = $this->db->table('main m2')
            ->select('DISTINCT m2.diary_no, reg_no_display, pet_name, res_name, pno, rno')
            ->select('(CASE WHEN DATEDIFF(h2.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h2.next_dt ELSE "" END) AS next_date')
            ->join('advocate a', 'm2.diary_no = a.diary_no', 'left')
            ->join('heardt h2', 'm2.diary_no = h2.diary_no', 'left')
            ->where('a.advocate_id', $aor_code)
            ->where('a.display', 'Y')
            ->where('m2.c_status', 'P')
            ->where('m2.active_fil_no IS NOT NULL')
            ->where('m2.fil_no IS NOT NULL');
        $unionQuery = $this->db->table('temp')
            ->union($subquery1)
            ->union($subquery2)
            ->orderBy('diary_no');
        $query = $this->db->table('(' . $unionQuery . ') temp')
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_pending_unreg_matters_data($aor_code) {
        $subquery1 = $this->db->table('main m1')
            ->select('DISTINCT m1.diary_no, reg_no_display, pet_name, res_name, pno, rno')
            ->select('(CASE WHEN DATEDIFF(h1.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h1.next_dt ELSE "" END) AS next_date')
            ->join('heardt h1', 'm1.diary_no = h1.diary_no', 'left')
            ->join('advocate a1', 'm1.diary_no = a1.diary_no', 'left')
            ->where('m1.pet_adv_id', $aor_code)
            ->where('m1.c_status', 'P')
            ->groupStart()
            ->where('m1.active_fil_no IS NULL')
            ->orWhere('m1.fil_no IS NULL')
            ->groupEnd();
        $subquery2 = $this->db->table('main m2')
            ->select('DISTINCT m2.diary_no, reg_no_display, pet_name, res_name, pno, rno')
            ->select('(CASE WHEN DATEDIFF(h2.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h2.next_dt ELSE "" END) AS next_date')
            ->join('heardt h2', 'm2.diary_no = h2.diary_no', 'left')
            ->join('advocate a2', 'm2.diary_no = a2.diary_no', 'left')
            ->where('a2.advocate_id', $aor_code)
            ->where('a2.display', 'Y')
            ->where('m2.c_status', 'P')
            ->groupStart()
            ->where('m2.active_fil_no IS NULL')
            ->orWhere('m2.fil_no IS NULL')
            ->groupEnd();
        $unionQuery = $this->db->table('temp')
            ->union($subquery1)
            ->union($subquery2)
            ->orderBy('diary_no');
        $query = $this->db->table('(' . $unionQuery . ') temp')
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_pending_reg_pet_matters_data($aor_code) {
        $subquery1 = $this->db->table('main m1')
            ->select('DISTINCT m1.diary_no, reg_no_display, pet_name, res_name, pno, rno')
            ->select('(CASE WHEN DATEDIFF(h1.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h1.next_dt ELSE "" END) AS next_date')
            ->join('heardt h1', 'm1.diary_no = h1.diary_no', 'left')
            ->join('advocate a1', 'm1.diary_no = a1.diary_no', 'left')
            ->where('m1.pet_adv_id', $aor_code)
            ->where('m1.c_status', 'P')
            ->where('m1.active_fil_no IS NOT NULL')
            ->where('m1.fil_no IS NOT NULL');
        $subquery2 = $this->db->table('main m2')
            ->select('DISTINCT m2.diary_no, reg_no_display, pet_name, res_name, pno, rno')
            ->select('(CASE WHEN DATEDIFF(h2.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h2.next_dt ELSE "" END) AS next_date')
            ->join('heardt h2', 'm2.diary_no = h2.diary_no', 'left')
            ->join('advocate a2', 'm2.diary_no = a2.diary_no', 'left')
            ->where('a2.advocate_id', $aor_code)
            ->where('a2.display', 'Y')
            ->where('m2.c_status', 'P')
            ->where('m2.active_fil_no IS NOT NULL')
            ->where('m2.fil_no IS NOT NULL')
            ->where('a2.pet_res', 'P');
        $unionQuery = $this->db->table('temp')
            ->union($subquery1)
            ->union($subquery2)
            ->orderBy('diary_no');
        $query = $this->db->table('(' . $unionQuery . ') temp')
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_pending_reg_res_matters_data($aor_code) {
        // Subquery 1: Petitioner matters
        $subquery1 = $this->db->table('main m1')
            ->select('DISTINCT m1.diary_no, reg_no_display, pet_name, res_name, pno, rno')
            ->select('(CASE WHEN DATEDIFF(h1.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h1.next_dt ELSE "" END) AS next_date')
            ->join('heardt h1', 'm1.diary_no = h1.diary_no', 'left')
            ->join('advocate a1', 'm1.diary_no = a1.diary_no', 'left')
            ->where('a1.advocate_id', $aor_code)
            ->where('a1.display', 'Y')
            ->where('m1.c_status', 'P')
            ->where('m1.active_fil_no IS NOT NULL')
            ->where('m1.fil_no IS NOT NULL')
            ->where('a1.pet_res', 'R');
        // Subquery 2: Other matters
        $subquery2 = $this->db->table('main m2')
            ->select('DISTINCT m2.diary_no, reg_no_display, pet_name, res_name, pno, rno')
            ->select('(CASE WHEN DATEDIFF(h2.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h2.next_dt ELSE "" END) AS next_date')
            ->join('heardt h2', 'm2.diary_no = h2.diary_no', 'left')
            ->join('advocate a2', 'm2.diary_no = a2.diary_no', 'left')
            ->where('a2.advocate_id', $aor_code)
            ->where('a2.display', 'Y')
            ->where('m2.c_status', 'P')
            ->where('m2.active_fil_no IS NOT NULL')
            ->where('m2.fil_no IS NOT NULL')
            ->whereNotIn('a2.pet_res', ['P', 'R']);
        // Union Query: Combine results from both subqueries
        $unionQuery = $this->db->table('temp')
            ->union($subquery1)
            ->union($subquery2)
            ->orderBy('diary_no');
        // Final query to fetch results from union query
        $query = $this->db->table('(' . $unionQuery . ') temp')
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_pending_unreg_pet_matters_data($aor_code) {
        // Subquery 1: Petitioner matters with unregistered status
        $subquery1 = $this->db->table('main m1')
            ->select('DISTINCT m1.diary_no, reg_no_display, pet_name, res_name, pno, rno')
            ->select('(CASE WHEN DATEDIFF(h1.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h1.next_dt ELSE "" END) AS next_date')
            ->join('heardt h1', 'm1.diary_no = h1.diary_no', 'left')
            ->join('advocate a1', 'm1.diary_no = a1.diary_no', 'left')
            ->where('a1.pet_adv_id', $aor_code)
            ->where('m1.c_status', 'P')
            ->where('m1.active_fil_no IS NULL')
            ->orWhere('m1.fil_no IS NULL');
        // Subquery 2: Petitioner matters with unregistered status and advocate display
        $subquery2 = $this->db->table('main m2')
            ->select('DISTINCT m2.diary_no, reg_no_display, pet_name, res_name, pno, rno')
            ->select('(CASE WHEN DATEDIFF(h2.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h2.next_dt ELSE "" END) AS next_date')
            ->join('heardt h2', 'm2.diary_no = h2.diary_no', 'left')
            ->join('advocate a2', 'm2.diary_no = a2.diary_no', 'left')
            ->where('a2.advocate_id', $aor_code)
            ->where('a2.display', 'Y')
            ->where('m2.c_status', 'P')
            ->where('m2.active_fil_no IS NULL')
            ->orWhere('m2.fil_no IS NULL')
            ->where('a2.pet_res', 'P');
        // Union Query: Combine results from both subqueries
        $unionQuery = $this->db->table('temp')
            ->union($subquery1)
            ->union($subquery2)
            ->orderBy('diary_no');
        // Final query to fetch results from union query
        $query = $this->db->table('(' . $unionQuery . ') temp')
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_pending_unreg_res_matters_data($aor_code) {
        // Subquery 1: Petitioner matters with unregistered respondent status
        $subquery1 = $this->db->table('main m1')
            ->select('DISTINCT m1.diary_no, reg_no_display, pet_name, res_name, pno, rno')
            ->select('(CASE WHEN DATEDIFF(h1.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h1.next_dt ELSE "" END) AS next_date')
            ->join('heardt h1', 'm1.diary_no = h1.diary_no', 'left')
            ->join('advocate a1', 'm1.diary_no = a1.diary_no', 'left')
            ->where('a1.advocate_id', $aor_code)
            ->where('a1.display', 'Y')
            ->where('m1.c_status', 'P')
            ->where('a1.pet_res', 'R')
            ->where('m1.active_fil_no IS NULL')
            ->orWhere('m1.fil_no IS NULL');
        // Union Query: Combine results from subquery
        $unionQuery = $this->db->table('temp')
            ->union($subquery1)
            ->orderBy('diary_no');
        // Final query to fetch results from union query
        $query = $this->db->table('(' . $unionQuery . ') temp')
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_pending_reg_other_matters_data($aor_code) {
        // Subquery 1: Petitioner matters with other (neither Petitioner nor Respondent) status
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
        // Union Query: Combine results from subquery
        $unionQuery = $this->db->table('temp')
            ->union($subquery1)
            ->orderBy('diary_no');
        // Final query to fetch results from union query
        $query = $this->db->table('(' . $unionQuery . ') temp')
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_pending_unreg_other_matters_data($aor_code) {
        // Subquery: Petitioner matters with unregistered (null active_fil_no or fil_no) and pet_res not in ('P', 'R')
        $subquery = $this->db->table('main m')
            ->select('DISTINCT m.diary_no, reg_no_display, pet_name, res_name, pno, rno')
            ->select('(CASE WHEN DATEDIFF(h.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h.next_dt ELSE "" END) AS next_date')
            ->join('heardt h', 'm.diary_no = h.diary_no', 'left')
            ->join('advocate a', 'm.diary_no = a.diary_no', 'left')
            ->where('a.advocate_id', $aor_code)
            ->where('a.display', 'Y')
            ->where('m.c_status', 'P')
            ->where(function ($builder) {
                $builder->where('m.active_fil_no', null)
                    ->orWhere('m.fil_no', null);
            })
            ->whereNotIn('a.pet_res', ['P', 'R'])
            ->orderBy('m.diary_no');
        // Final query to fetch results from subquery
        $query = $this->db->table('(' . $subquery . ') temp')
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

}