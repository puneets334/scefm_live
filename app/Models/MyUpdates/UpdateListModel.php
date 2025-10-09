<?php

namespace App\Models\MyUpdates;
use CodeIgniter\Model;

class Update_list_model extends Model {

    function __construct() {
        parent::__construct();
        $common_db = $this->load->database('common_db', TRUE);
        $db = \Config\Database::connect();
    }

    public function get_diary_received_data($bar_id, $day, $from_date = null, $next_date = null) {
        if ($day == 'between') {
            $where = "DATE(diary_no_rec_date) BETWEEN :from_date: AND :next_date:";
            $params = ['from_date' => $from_date, 'next_date' => $next_date];
        } elseif ($day == 'today') {
            $where = 'DATE(diary_no_rec_date) = CURDATE()';
            $params = [];
        } elseif ($day == 'yesterday') {
            $where = 'DATE(diary_no_rec_date) = DATE_ADD(CURDATE(), INTERVAL -1 DAY)';
            $params = [];
        } elseif ($day == 'tomorrow') {
            $where = 'DATE(diary_no_rec_date) = DATE_ADD(CURDATE(), INTERVAL 1 DAY)';
            $params = [];
        } else {
            return false; // Invalid $day parameter
        }
        $sql = "SELECT DISTINCT m.diary_no, reg_no_display, pet_name, res_name, pno, rno,
            CASE WHEN DATEDIFF(h.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h.next_dt 
            ELSE '' END AS 'next_date' 
            FROM main m 
            LEFT JOIN heardt h ON m.diary_no = h.diary_no 
            WHERE $where 
            AND pet_adv_id = :bar_id:";
        $params['bar_id'] = $bar_id;
        $query = $this->db->query($sql, $params);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_defects_notified_data($bar_id, $day, $from_date = null, $next_date = null) {
        if ($day == 'between') {
            $where = "DATE(diary_no_rec_date) BETWEEN :from_date: AND :next_date:";
            $params = ['from_date' => $from_date, 'next_date' => $next_date];
        } elseif ($day == 'today') {
            $where = 'DATE(diary_no_rec_date) = CURDATE()';
            $params = [];
        } elseif ($day == 'yesterday') {
            $where = 'DATE(diary_no_rec_date) = DATE_ADD(CURDATE(), INTERVAL -1 DAY)';
            $params = [];
        } elseif ($day == 'tomorrow') {
            $where = 'DATE(diary_no_rec_date) = DATE_ADD(CURDATE(), INTERVAL 1 DAY)';
            $params = [];
        } else {
            return false; // Invalid $day parameter
        }
        $sql = "SELECT DISTINCT m.diary_no, reg_no_display, pet_name, res_name, pno, rno,
            CASE WHEN DATEDIFF(h.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 THEN h.next_dt 
            ELSE '' END AS 'next_date' 
            FROM main m 
            LEFT JOIN heardt h ON m.diary_no = h.diary_no 
            JOIN obj_save os ON m.diary_no = os.diary_no 
            WHERE pet_adv_id = :bar_id: 
            AND DATE(save_dt) BETWEEN :from_date: AND :next_date: 
            AND os.display = 'Y'
            AND $where";
        $params['bar_id'] = $bar_id;
        $query = $this->db->query($sql, $params);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_IA_data($bar_id, $day, $from_date = null, $next_date = null) {
        if ($day == 'between') {
            $where = "DATE(diary_no_rec_date) BETWEEN :from_date: AND :next_date:";
            $params = ['from_date' => $from_date, 'next_date' => $next_date];
        } elseif ($day == 'today') {
            $where = 'DATE(diary_no_rec_date) = CURDATE()';
            $params = [];
        } elseif ($day == 'yesterday') {
            $where = 'DATE(diary_no_rec_date) = DATE_ADD(CURDATE(), INTERVAL -1 DAY)';
            $params = [];
        } elseif ($day == 'tomorrow') {
            $where = 'DATE(diary_no_rec_date) = DATE_ADD(CURDATE(), INTERVAL 1 DAY)';
            $params = [];
        } else {
            return false; // Invalid $day parameter
        }
        $doc_code = "(8)";
        $sql = "SELECT DISTINCT m.diary_no, reg_no_display, pet_name, res_name, pno, rno,
            CASE WHEN DATEDIFF(h.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 
            THEN h.next_dt ELSE '' END AS 'next_date' 
            FROM docdetails d 
            INNER JOIN advocate a ON d.diary_no = a.diary_no 
            INNER JOIN bar b ON a.advocate_id = b.bar_id 
            JOIN main m ON m.diary_no = d.diary_no 
            LEFT JOIN heardt h ON m.diary_no = h.diary_no 
            WHERE d.doccode IN $doc_code 
            AND d.display = 'Y' 
            AND DATE(d.ent_dt) BETWEEN :from_date: AND :next_date: 
            AND b.bar_id = :bar_id: 
            AND $where";
        $params['bar_id'] = $bar_id;
        $query = $this->db->query($sql, $params);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_misc_data($bar_id, $day, $from_date = null, $next_date = null) {
        // Set the WHERE condition based on the day parameter
        if ($day == 'between') {
            $where = "DATE(diary_no_rec_date) BETWEEN :from_date: AND :next_date:";
            $params = ['from_date' => $from_date, 'next_date' => $next_date];
        } elseif ($day == 'today') {
            $where = 'DATE(diary_no_rec_date) = CURDATE()';
            $params = [];
        } elseif ($day == 'yesterday') {
            $where = 'DATE(diary_no_rec_date) = DATE_ADD(CURDATE(), INTERVAL -1 DAY)';
            $params = [];
        } elseif ($day == 'tomorrow') {
            $where = 'DATE(diary_no_rec_date) = DATE_ADD(CURDATE(), INTERVAL 1 DAY)';
            $params = [];
        } else {
            return false; // Invalid $day parameter
        }
        $doc_code = '(8)';
        $sql = "SELECT DISTINCT m.diary_no, reg_no_display, pet_name, res_name, pno, rno,
            CASE WHEN DATEDIFF(h.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 
            THEN h.next_dt ELSE '' END AS 'next_date' 
            FROM docdetails d 
            INNER JOIN advocate a ON d.diary_no = a.diary_no 
            INNER JOIN bar b ON a.advocate_id = b.bar_id 
            JOIN main m ON m.diary_no = d.diary_no 
            LEFT JOIN heardt h ON m.diary_no = h.diary_no 
            WHERE d.doccode NOT IN $doc_code 
            AND d.display = 'Y' 
            AND DATE(d.ent_dt) BETWEEN :from_date: AND :next_date: 
            AND b.bar_id = :bar_id: 
            AND $where";
        $params['bar_id'] = $bar_id;
        $query = $this->db->query($sql, $params);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_re_filed_data($bar_id, $day, $from_date = null, $next_date = null) {
        // Set the WHERE condition based on the day parameter
        if ($day == 'between') {
            $where = "DATE(diary_no_rec_date) BETWEEN :from_date: AND :next_date:";
            $params = ['from_date' => $from_date, 'next_date' => $next_date];
        } elseif ($day == 'today') {
            $where = 'DATE(diary_no_rec_date) = CURDATE()';
            $params = [];
        } elseif ($day == 'yesterday') {
            $where = 'DATE(diary_no_rec_date) = DATE_ADD(CURDATE(), INTERVAL -1 DAY)';
            $params = [];
        } elseif ($day == 'tomorrow') {
            $where = 'DATE(diary_no_rec_date) = DATE_ADD(CURDATE(), INTERVAL 1 DAY)';
            $params = [];
        } else {
            return false; // Invalid $day parameter
        }
        // Build the SQL query using parameter binding
        $sql = "SELECT DISTINCT m.diary_no, reg_no_display, pet_name, res_name, pno, rno,
            CASE WHEN DATEDIFF(h.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 
            THEN h.next_dt ELSE '' END AS 'next_date' 
            FROM fil_trap f 
            JOIN main m ON f.diary_no = m.diary_no 
            LEFT JOIN heardt h ON m.diary_no = h.diary_no 
            WHERE f.remarks = 'FDR -> SCR' 
            AND DATE(f.disp_dt) BETWEEN :from_date: AND :next_date: 
            AND m.pet_adv_id = :bar_id: 
            UNION 
            SELECT DISTINCT m.diary_no, reg_no_display, pet_name, res_name, pno, rno,
            CASE WHEN DATEDIFF(h.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 
            THEN h.next_dt ELSE '' END AS 'next_date' 
            FROM fil_trap_his f 
            JOIN main m ON f.diary_no = m.diary_no 
            LEFT JOIN heardt h ON m.diary_no = h.diary_no 
            WHERE f.remarks = 'FDR -> SCR' 
            AND DATE(f.disp_dt) BETWEEN :from_date: AND :next_date: 
            AND m.pet_adv_id = :bar_id:";
        $params['bar_id'] = $bar_id;
        // Execute the query
        $query = $this->db->query($sql, $params);
        // Check if there are any rows returned
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray(); // Return the result as an array
        } else {
            return false; // Return false if no rows are found
        }
    }

    public function get_registered_data($bar_id, $day, $from_date = null, $next_date = null) {
        // Set the WHERE condition based on the day parameter
        if ($day == 'between') {
            $where = "DATE(diary_no_rec_date) BETWEEN :from_date: AND :next_date:";
            $params = ['from_date' => $from_date, 'next_date' => $next_date];
        } elseif ($day == 'today') {
            $where = 'DATE(diary_no_rec_date) = CURDATE()';
            $params = [];
        } elseif ($day == 'yesterday') {
            $where = 'DATE(diary_no_rec_date) = DATE_ADD(CURDATE(), INTERVAL -1 DAY)';
            $params = [];
        } elseif ($day == 'tomorrow') {
            $where = 'DATE(diary_no_rec_date) = DATE_ADD(CURDATE(), INTERVAL 1 DAY)';
            $params = [];
        } else {
            return false; // Invalid $day parameter
        }
        $sql = "SELECT DISTINCT m.diary_no, reg_no_display, pet_name, res_name, pno, rno,
            CASE WHEN DATEDIFF(h.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 
            THEN h.next_dt ELSE '' END AS 'next_date' 
            FROM main m 
            LEFT JOIN heardt h ON m.diary_no = h.diary_no 
            WHERE m.active_fil_no IS NOT NULL 
            AND m.fil_no IS NOT NULL 
            AND DATE(m.active_fil_dt) BETWEEN :from_date: AND :next_date: 
            AND m.pet_adv_id = :bar_id: 
            UNION 
            SELECT DISTINCT m.diary_no, reg_no_display, pet_name, res_name, pno, rno,
            CASE WHEN DATEDIFF(h.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 
            THEN h.next_dt ELSE '' END AS 'next_date' 
            FROM advocate a 
            JOIN main m ON a.diary_no = m.diary_no 
            LEFT JOIN heardt h ON m.diary_no = h.diary_no 
            WHERE a.display = 'Y' 
            AND m.active_fil_no IS NOT NULL 
            AND m.fil_no IS NOT NULL 
            AND DATE(m.active_fil_dt) BETWEEN :from_date: AND :next_date: 
            AND a.advocate_id = :bar_id:";
        $params['bar_id'] = $bar_id;
        // Execute the query
        $query = $this->db->query($sql, $params);
        // Check if there are any rows returned
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray(); // Return the result as an array
        } else {
            return false; // Return false if no rows are found
        }
    }

    public function get_caveat_data($bar_id, $day, $from_date = null, $next_date = null) {
        // Set the WHERE condition based on the day parameter
        if ($day == 'between') {
            $where = "DATE(diary_no_rec_date) BETWEEN :from_date: AND :next_date:";
            $params = ['from_date' => $from_date, 'next_date' => $next_date];
        } elseif ($day == 'today') {
            $where = 'DATE(diary_no_rec_date) = CURDATE()';
            $params = [];
        } elseif ($day == 'yesterday') {
            $where = 'DATE(diary_no_rec_date) = DATE_ADD(CURDATE(), INTERVAL -1 DAY)';
            $params = [];
        } elseif ($day == 'tomorrow') {
            $where = 'DATE(diary_no_rec_date) = DATE_ADD(CURDATE(), INTERVAL 1 DAY)';
            $params = [];
        } else {
            return false; // Invalid $day parameter
        }
        $sql = "SELECT COUNT(*) AS total 
            FROM caveat c
            JOIN caveat_diary_matching cdm ON c.caveat_no = cdm.caveat_no 
            JOIN main m ON m.diary_no = cdm.diary_no
            WHERE DATE(c.diary_no_rec_date) BETWEEN :from_date: AND :next_date: 
            AND c.pet_adv_id = :bar_id: 
            AND cdm.display = 'Y'";
        $params['bar_id'] = $bar_id;
        // Execute the query
        $query = $this->db->query($sql, $params);
        // Check if there are any rows returned
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray(); // Return the result as an array
        } else {
            return false; // Return false if no rows are found
        }
    }

    public function get_disposed_data($bar_id, $day, $from_date = null, $next_date = null) {
        // Set the WHERE condition based on the day parameter
        if ($day == 'between') {
            $where = "DATE(diary_no_rec_date) BETWEEN :from_date: AND :next_date:";
            $params = ['from_date' => $from_date, 'next_date' => $next_date];
        } elseif ($day == 'today') {
            $where = 'DATE(diary_no_rec_date) = CURDATE()';
            $params = [];
        } elseif ($day == 'yesterday') {
            $where = 'DATE(diary_no_rec_date) = DATE_ADD(CURDATE(), INTERVAL -1 DAY)';
            $params = [];
        } elseif ($day == 'tomorrow') {
            $where = 'DATE(diary_no_rec_date) = DATE_ADD(CURDATE(), INTERVAL 1 DAY)';
            $params = [];
        } else {
            return false; // Invalid $day parameter
        }
        $sql = "SELECT DISTINCT m.diary_no, reg_no_display, pet_name, res_name, pno, rno,
            CASE WHEN DATEDIFF(h.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 
            THEN h.next_dt ELSE '' END AS 'next date' 
            FROM dispose d 
            JOIN main m ON d.diary_no = m.diary_no 
            LEFT JOIN heardt h ON m.diary_no = h.diary_no 
            WHERE d.ord_dt BETWEEN :from_date: AND :next_date: 
            AND d.c_status = 'D' 
            AND (m.pet_adv_id = :bar_id: OR a.advocate_id = :bar_id:)
            AND (m.pet_adv_id = :bar_id: OR a.advocate_id = :bar_id:)
            AND a.display = 'Y'";
        $params['bar_id'] = $bar_id;
        // Execute the query
        $query = $this->db->query($sql, $params);
        // Check if there are any rows returned
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray(); // Return the result as an array
        } else {
            return false; // Return false if no rows are found
        }
    }

    public function get_judgment_data($bar_id, $day, $from_date, $next_date) {
        $type = 'J'; // Assuming 'J' is the type for judgments
        // Set the WHERE condition based on the day parameter
        if ($day == 'between') {
            $where = "orderdate BETWEEN :from_date: AND :next_date:";
            $params = ['from_date' => $from_date, 'next_date' => $next_date];
        } elseif ($day == 'today') {
            $where = 'orderdate = CURDATE()';
            $params = [];
        } elseif ($day == 'yesterday') {
            $where = 'orderdate = DATE_ADD(CURDATE(), INTERVAL -1 DAY)';
            $params = [];
        } elseif ($day == 'tomorrow') {
            $where = 'orderdate = DATE_ADD(CURDATE(), INTERVAL 1 DAY)';
            $params = [];
        } else {
            return false; // Invalid $day parameter
        }
        $sql = "SELECT DISTINCT m.diary_no, reg_no_display, pet_name, res_name, pno, rno,
            CASE WHEN DATEDIFF(h.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 
            THEN h.next_dt ELSE '' END AS 'next date' 
            FROM ordernet o 
            JOIN main m ON o.diary_no = m.diary_no 
            LEFT JOIN heardt h ON m.diary_no = h.diary_no 
            WHERE o.display = 'Y' 
            AND $where
            AND o.type = :type:
            AND o.pet_adv_id = :bar_id:
            UNION 
            SELECT DISTINCT m.diary_no, reg_no_display, pet_name, res_name, pno, rno,
            CASE WHEN DATEDIFF(h.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 
            THEN h.next_dt ELSE '' END AS 'next date' 
            FROM ordernet o  
            JOIN advocate a ON o.diary_no = a.diary_no 
            JOIN main m ON m.diary_no = o.diary_no 
            LEFT JOIN heardt h ON m.diary_no = h.diary_no 
            WHERE a.display = 'Y' 
            AND o.display = 'Y' 
            AND $where
            AND o.type = :type:
            AND a.advocate_id = :bar_id:";
        $params['type'] = $type;
        $params['bar_id'] = $bar_id;
        // Execute the query
        $query = $this->db->query($sql, $params);
        // Check if there are any rows returned
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray(); // Return the result as an array
        } else {
            return false; // Return false if no rows are found
        }
    }

    public function get_daily_data($bar_id, $day, $from_date, $next_date) {
        $type = '0'; // Assuming '0' is the type for daily data
        // Set the WHERE condition based on the day parameter
        if ($day == 'between') {
            $where = "orderdate BETWEEN :from_date: AND :next_date:";
            $params = ['from_date' => $from_date, 'next_date' => $next_date];
        } elseif ($day == 'today') {
            $where = 'orderdate = CURDATE()';
            $params = [];
        } elseif ($day == 'yesterday') {
            $where = 'orderdate = DATE_ADD(CURDATE(), INTERVAL -1 DAY)';
            $params = [];
        } elseif ($day == 'tomorrow') {
            $where = 'orderdate = DATE_ADD(CURDATE(), INTERVAL 1 DAY)';
            $params = [];
        } else {
            return false; // Invalid $day parameter
        }
        $sql = "SELECT DISTINCT m.diary_no, reg_no_display, pet_name, res_name, pno, rno,
            CASE WHEN DATEDIFF(h.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 
            THEN h.next_dt ELSE '' END AS 'next date' 
            FROM ordernet o 
            JOIN main m ON o.diary_no = m.diary_no 
            LEFT JOIN heardt h ON m.diary_no = h.diary_no 
            WHERE o.display = 'Y' 
            AND $where
            AND o.type = :type:
            AND o.pet_adv_id = :bar_id:
            UNION 
            SELECT DISTINCT m.diary_no, reg_no_display, pet_name, res_name, pno, rno,
            CASE WHEN DATEDIFF(h.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 
            THEN h.next_dt ELSE '' END AS 'next date' 
            FROM ordernet o  
            JOIN advocate a ON o.diary_no = a.diary_no 
            JOIN main m ON m.diary_no = o.diary_no 
            LEFT JOIN heardt h ON m.diary_no = h.diary_no 
            WHERE a.display = 'Y' 
            AND o.display = 'Y' 
            AND $where
            AND o.type = :type:
            AND a.advocate_id = :bar_id:";
        $params['type'] = $type;
        $params['bar_id'] = $bar_id;
        // Execute the query
        $query = $this->db->query($sql, $params);
        // Check if there are any rows returned
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray(); // Return the result as an array
        } else {
            return false; // Return false if no rows are found
        }
    }

    public function get_certified_copy_data($bar_id, $day, $from_date, $next_date) {
        $copy_in = '(1,3)'; // Assuming this is a list of copy categories
        // Set the WHERE condition based on the day parameter
        if ($day == 'between') {
            $where = "date(application_receipt) BETWEEN :from_date: AND :next_date:";
            $params = ['from_date' => $from_date, 'next_date' => $next_date];
        } elseif ($day == 'today') {
            $where = 'date(application_receipt) = CURDATE()';
            $params = [];
        } elseif ($day == 'yesterday') {
            $where = 'date(application_receipt) = DATE_ADD(CURDATE(), INTERVAL -1 DAY)';
            $params = [];
        } elseif ($day == 'tomorrow') {
            $where = 'date(application_receipt) = DATE_ADD(CURDATE(), INTERVAL 1 DAY)';
            $params = [];
        } else {
            return false; // Invalid $day parameter
        }
        $filed_by = 1; // Assuming filed_by value
        $sql = "SELECT DISTINCT m.diary_no, reg_no_display, pet_name, res_name, pno, rno,
            CASE WHEN DATEDIFF(h.next_dt, CURRENT_DATE()) BETWEEN 0 AND 60 
            THEN h.next_dt ELSE '' END AS 'next date' 
            FROM copying_order_issuing_application_new c 
            JOIN bar b ON c.advocate_or_party = b.aor_code 
            JOIN main m ON m.diary_no = c.diary 
            LEFT JOIN heardt h ON m.diary_no = h.diary_no 
            WHERE $where
            AND copy_category IN $copy_in 
            AND filed_by = :filed_by:
            AND bar_id = :bar_id:";
        $params['filed_by'] = $filed_by;
        $params['bar_id'] = $bar_id;
        // Execute the query
        $query = $this->db->query($sql, $params);
        // Check if there are any rows returned
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray(); // Return the result as an array
        } else {
            return false; // Return false if no rows are found
        }
    }

}