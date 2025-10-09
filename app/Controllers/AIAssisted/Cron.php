<?php

namespace App\Controllers\AIAssisted;

use App\Controllers\BaseController;
use App\Models\AIAssisted\CommonCasewithAIModel;

class Cron extends BaseController {

    protected $Common_casewithAI_model;

    public function __construct() {
        parent::__construct();
        $this->Common_casewithAI_model = new CommonCasewithAIModel();
    }

    public function index() {
        cron_json_data_extract_empt_callAPILB();
        exit();
    }

    public function lb() {
        cron_json_data_extract_empt_callAPILB();
        exit();
    }

    public function defect() {
        cron_json_data_extract_empt_callAPILB_defect();
        exit();
    }

    public function efiling_api_cron() {

    }

    public function icmice_cron() {

    }

    public function report() {
        // $result = array();
        $builder = $this->db->table('efil.tbl_round_robin_api_iitm');
        $builder->SELECT("*");        
        $builder->WHERE('is_deleted', FALSE);
        $builder->ORDER_BY('last_triggered_started_on', 'DESC');
        $query = $builder->get();
        $result = $query->getResultArray();
        $data['cronIITM'] = $result;
       $this->render('AIAssisted.case_with_AI_cron_view', @compact('data'));
    }

}