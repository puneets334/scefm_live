<?php
namespace App\Controllers;

class Type extends BaseController {

    public function __construct() {
        parent::__construct();

        $this->load->helper('functions');
        $this->load->library('webservices/efiling_webservices');
        $this->load->model('integrated_search/Dropdown_list_model');
    }

    public function index() {

        $this->highcourts();
    }

    public function highcourts() {

        $highcourt_list = $this->Dropdown_list_model->get_high_court_list();
        if (count($highcourt_list)) {

            echo '<option value=""> Select High Court</option>';
            foreach ($highcourt_list as $dataRes) {

                $value = $dataRes['id'] . '##' . $dataRes['hc_name'] . '##' . $dataRes['hc_code'] . '##' . E_FILING_FOR_HIGHCOURT;
                echo '<option  value="' . htmlentities($value, ENT_QUOTES) . '">' . $dataRes['hc_name'] . '</option>';
            }
        } else {
            echo '<option value=""> Select  High Court</option>';
        }
    }


    public function state() {
        $state_list = $this->efiling_webservices->getOpenAPIState();
        if (count($state_list)) {
            echo '<option value=""> Select State</option>';
            foreach ($state_list as $dataRes) {
                foreach ($dataRes->state as $state) {
                    echo '<option  value="' . htmlentities(url_encryption($state->state_code . '#$' . $state->state_name . '#$' . $state->state_name), ENT_QUOTES) . '">' . $state->state_name . '</option>';
                }
            }
        } else {
            echo '<option value=""> Select State </option>';
        }
    }

    public function get_openAPI_district() {

        $state_array = explode('#$', url_decryption(escape_data($_POST['state_id'])));
        $state_id = $state_array[0];
        if (isset($state_id) && preg_match("/^[0-9]*$/", $state_id) && strlen($state_id) <= INTEGER_FIELD_LENGTH) {
            if (!empty($state_id)) {
                $result = $this->efiling_webservices->getOpenAPIDistrict($state_id);
                if (!empty($status->status_code)) {
                    echo 'ERROR_' . $status->status;
                    exit(0);
                } else {
                    if (count($result)) {
                        echo '<option value=""> Select District </option>';
                        foreach ($result as $dataRes) {
                            foreach ($dataRes->district as $district) {
                                echo '<option  value="' . htmlentities(url_encryption($district->dist_code . '#$' . $district->dist_name), ENT_QUOTES) . '">' . htmlentities(strtoupper($district->dist_name), ENT_QUOTES) . '</option>';
                            }
                        }
                    } else {
                        echo '<option value=""> Select District </option>';
                    }
                }
            } else {
                echo '<option value=""> Select District</option>';
            }
        } else {
            echo '<option value=""> Select District</option>';
        }
    }

    function get_case_data_urls() {
        
        $state_array = explode('#$', url_decryption(escape_data($_POST['state_id'])));
        $state_id = $state_array[0];
        $district_array = explode('#$', url_decryption(escape_data($_POST['get_distt_id'])));
        $district_id = $district_array[0];

        echo $state_id . '##' . $district_id;
    }

}
