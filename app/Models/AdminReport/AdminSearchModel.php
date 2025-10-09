<?php

namespace App\Models\AdminReport;
use CodeIgniter\Model;

class AdminSearchModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function get_list_doc_fromDate_toDate($params=array()) {
        $output = false;
        if (isset($params['from_date']) && !empty($params['from_date']) && isset($params['to_date']) && !empty($params['to_date']) && isset($params['sc_case_type']) && !empty($params['sc_case_type'])) {
            $sql =  "SELECT DISTINCT ten.efiling_no,
                tcd.sc_diary_num,
                tcd.sc_diary_year,
                DATE(tens.deactivated_on) AS diarized_on,
                ct.skey AS casetype,
                CONCAT('".DOCUMENT_CASETYPE_URL."', tup.file_path) AS path,
                tup.file_path AS ducs_path
            FROM efil.tbl_efiling_nums ten
            INNER JOIN efil.tbl_efiling_num_status tens 
                ON ten.registration_id = tens.registration_id
                AND tens.stage_id = 8 
                AND CAST(tens.deactivated_on AS DATE) BETWEEN '" . $params['from_date'] . "' AND '" . $params['to_date'] . "'
            INNER JOIN efil.tbl_case_details tcd 
                ON ten.registration_id = tcd.registration_id
            INNER JOIN efil.tbl_uploaded_pdfs tup 
                ON ten.registration_id = tup.registration_id
                AND tup.is_active = TRUE
            INNER JOIN icmis.casetype ct 
                ON tcd.sc_case_type_id = ct.casecode
            WHERE tcd.sc_diary_num IS NOT NULL 
                AND tcd.sc_case_type_id IN (" . implode(",", $params['sc_case_type']) . ")
            ORDER BY ten.efiling_no";
            $query = $this->db->query($sql);
            if (count($query->getResult()) >= 1) {
                $result = $query->getResultArray();
                return $result;
            } else {
                return FALSE;
            }
        }
    }

}