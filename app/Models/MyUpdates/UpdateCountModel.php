<?php

namespace App\Models\MyUpdates;
use CodeIgniter\Model;

class Update_count_model extends Model {

    function __construct() {
        parent::__construct();
        $db = \Config\Database::connect();
        $common_db = $this->load->database('common_db', TRUE);
    }

    public function diary_received_count($bar_id, $from_date, $next_date) {
        $query = $this->db->table('main')
            ->selectCount('*')
            ->where('DATE(diary_no_rec_date) >=', $from_date)
            ->where('DATE(diary_no_rec_date) <=', $next_date)
            ->where('pet_adv_id', $bar_id)
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function defects_notified_count($bar_id, $from_date, $next_date) {
        $builder = $this->db->table('main m')
            ->selectCount('DISTINCT m.diary_no', 'total')
            ->join('obj_save os', 'm.diary_no = os.diary_no')
            ->where('m.pet_adv_id', $bar_id)
            ->where('DATE(os.save_dt) >=', $from_date)
            ->where('DATE(os.save_dt) <=', $next_date)
            ->where('os.display', 'Y');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return false;
        }
    }

    public function IA_count($bar_id, $from_date, $next_date) {
        $query = $this->db->table('docdetails d')
            ->selectCount('d.diary_no', 'total')
            ->join('bar b', 'd.advocate_id = b.aor_code')
            ->where('d.doccode !=', 8)
            ->where('d.display', 'Y')
            ->where('DATE(d.ent_dt) >=', $from_date)
            ->where('DATE(d.ent_dt) <=', $next_date)
            ->where('b.bar_id', $bar_id)
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return false;
        }
    }

    public function misc_count($bar_id, $from_date, $next_date) {
        $query = $this->db->table('docdetails d')
            ->selectCount('d.diary_no', 'total')
            ->join('bar b', 'd.advocate_id = b.aor_code')
            ->where('d.doccode !=', 8)
            ->where('d.display', 'Y')
            ->where('DATE(d.ent_dt) >=', $from_date)
            ->where('DATE(d.ent_dt) <=', $next_date)
            ->where('b.bar_id', $bar_id)
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return false;
        }
    }

    public function re_filed_count($bar_id, $from_date, $next_date) {
        $query1 = $this->db->table('fil_trap f')
            ->selectCount('f.diary_no', 'total')
            ->join('main m', 'f.diary_no = m.diary_no')
            ->where('f.remarks', 'FDR -> SCR')
            ->where('DATE(f.disp_dt) >=', $from_date)
            ->where('DATE(f.disp_dt) <=', $next_date)
            ->where('m.pet_adv_id', $bar_id)
            ->getCompiledSelect();
        $query2 = $this->db->table('fil_trap_his f')
            ->selectCount('f.diary_no', 'total')
            ->join('main m', 'f.diary_no = m.diary_no')
            ->where('f.remarks', 'FDR -> SCR')
            ->where('DATE(f.disp_dt) >=', $from_date)
            ->where('DATE(f.disp_dt) <=', $next_date)
            ->where('m.pet_adv_id', $bar_id)
            ->getCompiledSelect();
        $sql = "SELECT SUM(total) as total FROM ({$query1} UNION {$query2}) a";
        $query = $this->db->query($sql);
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return false;
        }
    }

    public function registered_count($bar_id, $from_date, $next_date) {
        $query1 = $this->db->table('main')
            ->select('diary_no')
            ->where('active_fil_no IS NOT NULL')
            ->where('fil_no IS NOT NULL')
            ->where('DATE(active_fil_dt) >=', $from_date)
            ->where('DATE(active_fil_dt) <=', $next_date)
            ->where('pet_adv_id', $bar_id)
            ->getCompiledSelect();
        $query2 = $this->db->table('advocate a')
            ->select('a.diary_no')
            ->join('main m', 'a.diary_no = m.diary_no')
            ->where('a.display', 'Y')
            ->where('m.active_fil_no IS NOT NULL')
            ->where('m.fil_no IS NOT NULL')
            ->where('DATE(m.active_fil_dt) >=', $from_date)
            ->where('DATE(m.active_fil_dt) <=', $next_date)
            ->where('a.advocate_id', $bar_id)
            ->getCompiledSelect();
        $sql = "SELECT COUNT(DISTINCT diary_no) as total FROM ({$query1} UNION {$query2}) a";
        $query = $this->db->query($sql);
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return false;
        }
    }

    public function caveat_count($bar_id, $from_date, $next_date) {
        $query = $this->db->table('caveat')
            ->selectCount('*', 'total')
            ->where('DATE(diary_no_rec_date) >=', $from_date)
            ->where('DATE(diary_no_rec_date) <=', $next_date)
            ->where('pet_adv_id', $bar_id)
            ->get();
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return false;
        }
    }

    public function disposed_count($bar_id, $from_date, $next_date) {
        $query1 = $this->db->table('dispose d')
            ->select('d.diary_no')
            ->join('main m', 'd.diary_no = m.diary_no')
            ->where('DATE(d.ord_dt) >=', $from_date)
            ->where('DATE(d.ord_dt) <=', $next_date)
            ->where('m.c_status', 'D')
            ->where('m.pet_adv_id', $bar_id)
            ->getCompiledSelect();
        $query2 = $this->db->table('dispose d')
            ->select('d.diary_no')
            ->join('main m', 'd.diary_no = m.diary_no')
            ->join('advocate a', 'm.diary_no = a.diary_no')
            ->where('a.display', 'Y')
            ->where('DATE(d.ord_dt) >=', $from_date)
            ->where('DATE(d.ord_dt) <=', $next_date)
            ->where('m.c_status', 'D')
            ->where('a.advocate_id', $bar_id)
            ->getCompiledSelect();

        $sql = "SELECT COUNT(DISTINCT diary_no) as total FROM ({$query1} UNION {$query2}) a";
        $query = $this->db->query($sql);
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return false;
        }
    }

    public function judgment_count($bar_id, $from_date, $next_date) {
        $type = 'J';
        $query1 = $this->db->table('ordnet o')
            ->select('m.diary_no')
            ->join('main m', 'o.diary_no = m.diary_no')
            ->where('o.display', 'Y')
            ->where('DATE(o.orderdate) >=', $from_date)
            ->where('DATE(o.orderdate) <=', $next_date)
            ->where('o.type', $type)
            ->where('m.pet_adv_id', $bar_id)
            ->getCompiledSelect();
        $query2 = $this->db->table('ordnet o')
            ->select('a.diary_no')
            ->join('advocate a', 'o.diary_no = a.diary_no')
            ->where('a.display', 'Y')
            ->where('o.display', 'Y')
            ->where('DATE(o.orderdate) >=', $from_date)
            ->where('DATE(o.orderdate) <=', $next_date)
            ->where('o.type', $type)
            ->where('a.advocate_id', $bar_id)
            ->getCompiledSelect();
        $sql = "SELECT COUNT(DISTINCT diary_no) as total FROM ({$query1} UNION {$query2}) a";
        $query = $this->db->query($sql);
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return false;
        }
    }

    public function daily_count($bar_id, $from_date, $next_date) {
        $type = '0';
        $sql = "SELECT COUNT(DISTINCT diary_no) AS total FROM (
            SELECT m.diary_no 
            FROM ordernet o 
            JOIN main m ON o.diary_no = m.diary_no  
            WHERE o.display = 'Y' 
            AND o.orderdate BETWEEN :from_date: AND :next_date: 
            AND type = :type: 
            AND pet_adv_id = :bar_id:
        UNION 
            SELECT a.diary_no 
            FROM ordernet o  
            JOIN advocate a ON o.diary_no = a.diary_no 
            WHERE a.display = 'Y' 
            AND o.display = 'Y' 
            AND o.orderdate BETWEEN :from_date: AND :next_date: 
            AND type = :type: 
            AND advocate_id = :bar_id:
        ) a";
        $query = $this->db->query($sql, [
            'from_date' => $from_date,
            'next_date' => $next_date,
            'type' => $type,
            'bar_id' => $bar_id
        ]);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function certified_copy_count($bar_id, $from_date, $next_date) {
        $copy_in = '(1,3)';
        $filed_by = 1;
        $sql = "SELECT COUNT(diary) AS total 
            FROM copying_order_issuing_application_new c 
            JOIN bar b ON c.advocate_or_party = b.aor_code 
            WHERE DATE(application_receipt) BETWEEN :from_date: AND :next_date: 
            AND copy_category IN $copy_in 
            AND filed_by = :filed_by: 
            AND bar_id = :bar_id:";
        $query = $this->db->query($sql, [
            'from_date' => $from_date,
            'next_date' => $next_date,
            'filed_by' => $filed_by,
            'bar_id' => $bar_id
        ]);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

}