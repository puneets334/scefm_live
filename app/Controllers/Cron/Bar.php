<?php

namespace App\Controllers\Cron;

use App\Controllers\BaseController;
use App\Models\Cron\DefaultModel;
use App\Libraries\webservices\Efiling_webservices;

class Bar extends BaseController
{

    protected $Default_model;
    protected $efiling_webservices;

    public function __construct()
    {
        parent::__construct();
        $this->Default_model = new DefaultModel();
        $this->efiling_webservices = new Efiling_webservices();
    }

    public function index($aor_code = null)
    {
        $AORDetails_data = file_get_contents(ICMIS_SERVICE_URL . '/ConsumedData/getAORDetails/?aor_code=' . $aor_code);
        if (!empty($AORDetails_data)) {
            $AORDetails = json_decode($AORDetails_data);
            $AORData = !empty($AORDetails->data) && (sizeof($AORDetails->data) == 1) ? array($AORDetails->data[0]) : $AORDetails->data;
            if (!empty($AORData)) {
                $response = $this->Default_model->get_aor_code_check_after_insert($AORData);
                echo $response;
            }
        }
    }
}
