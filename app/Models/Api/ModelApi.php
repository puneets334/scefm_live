<?php

namespace App\Models\Api;

use CodeIgniter\Model;

class ModelApi extends Model {

    function __construct() {
        parent::__construct();
        $db = \Config\Database::connect();
    }

    public function get_list_doc_cases_efiled($params=array()) {
        $output = array();
        $sc_diary_num = $sc_diary_year = '';
        if (isset($params['diary_no']) && !empty($params['diary_no'])) {
            $diary_no = $params['diary_no'];
            $sc_diary_num = substr($diary_no, 0, strlen($diary_no) - 4);
            $sc_diary_year = substr($diary_no, -4);
        }
        $builder = $this->db->table('efil.tbl_efiling_nums ten');
        $builder->DISTINCT();
        $builder->select(['tup.doc_title','tup.page_no as no_of_pages','tup.file_size','tup.file_type','tup.uploaded_on','tup.file_path','CONCAT(tcd.sc_diary_num,tcd.sc_diary_year) as diary_no','DATE(tens.deactivated_on) AS diarized_on','ct.skey AS casetype','ten.efiling_no','CONCAT(\'' . DOCUMENT_CASETYP_URL . '\', tup.file_path) AS path']);
        $builder->join('efil.tbl_efiling_num_status tens',"ten.registration_id = tens.registration_id AND  tens.is_active = 'true' and tens.is_deleted='false'",'inner');
        $builder->join('efil.tbl_case_details tcd', "ten.registration_id = tcd.registration_id and tcd.is_deleted='false'", 'inner');
        $builder->join('efil.tbl_uploaded_pdfs tup', "ten.registration_id = tup.registration_id AND tup.is_active = 'true' and tup.is_deleted='false'", 'inner');
        $builder->join('icmis.casetype ct', 'tcd.sc_case_type_id = ct.casecode', 'inner');
        $builder->where('tcd.sc_diary_num IS NOT NULL');
        if (isset($params['diary_no']) && !empty($params['diary_no']) && (!empty($sc_diary_num) && !empty($sc_diary_year))) {
            $builder->where('tcd.sc_diary_num', $sc_diary_num);
            $builder->where('tcd.sc_diary_year', $sc_diary_year);
        }
        if (isset($params['efiling_no']) && !empty($params['efiling_no'])) {
            $builder->where('ten.efiling_no', $params['efiling_no']);
        }
        $builder->WHERE('ten.ref_m_efiled_type_id', $params['ref_m_efiled_type_id']);
        $builder->WHERE('ten.is_active', true);
        $builder->WHERE('ten.is_deleted', false);
        $builder->orderBy('tup.uploaded_on');
        $query = $builder->get();
        $output['status'] = 'success';
        if ($query->getNumRows() >= 1) {
            $doc_list = $query->getResultArray();
            if(!empty($doc_list)) {
                $output['diary_no'] = $doc_list[0]['diary_no'];
                $output['efiling_no'] = $doc_list[0]['efiling_no'];
                $output['caset_ype'] = $doc_list[0]['casetype'];
                foreach ($doc_list as &$doc) {
                    unset($doc['casetype']);
                    unset($doc['efiling_no']);
                    unset($doc['diarized_on']);
                    unset($doc['diary_no']);
                    $output['doc_list'][] = $doc;
                }
            }
        } else{
            $output['diary_no'] = $params['diary_no'];
            $output['efiling_no'] = $params['efiling_no'];
            $output['doc_list'] = array();
        }
        return $output;
    }

}