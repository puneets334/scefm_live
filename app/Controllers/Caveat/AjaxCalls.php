<?php
namespace App\Controllers;
require_once APPPATH .'controllers/Auth_Controller.php';
class AjaxCalls extends Auth_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('common/Common_model');
    }

    public function index() {
        redirect('dashboard');
        exit(0);
    }

    public function relative_required() {

        $this->form_validation->set_rules('relation_id', 'Relation', 'required|trim|validate_encrypted_value[1##relation##required]');
        $this->form_validation->set_error_delimiters('<br>', '');

        if ($this->form_validation->run() === FALSE) {
            echo '1@@@' . validation_errors();
            exit(0);
        }

        $rel_data = explode('$$', url_decryption(escape_data($_POST['relation_id'])));
        echo '2@@@' . $rel_data[0];
    }

    public function get_org_details() {
        $organisation_data = explode('$$', url_decryption(escape_data($_POST['organisation_id'])));
        $organisation_data = explode('#$', $organisation_data[0]);
        $organisation_id = $organisation_data[0];
        if ($organisation_data[1] != 'not_in_list') {
            $this->form_validation->set_rules('organisation_id', 'Organisation', 'required|trim|validate_encrypted_value[1##org##required]');
            $this->form_validation->set_error_delimiters('<br>', '');

            if ($this->form_validation->run() === FALSE) {
                echo '1@@@' . validation_errors();
                exit(0);
            }
        }


        if ($organisation_id == 'a0') {
            echo '2@@@select';
        } elseif ($organisation_id == '0') {
            echo '2@@@not_in_list';
        } else {
            echo '2@@@' . strtoupper(escape_data($organisation_data[1])) . '@@@' . strtoupper(escape_data($organisation_data[2]));
        }
    }

    public function getDistrict(){
        $option = '<option value="">Select District</option>';
        if(isset($_POST['state_id']) && !empty($_POST['state_id'])){
            $state_id = url_decryption($_POST['state_id']);
            $params = array();
            $type = 2; //district list
            $params['type']= $type;
            $params['state_code'] = (int)$state_id;
            $districtData = $this->Common_model->getSubordinateCourtData($params);
            if(isset($districtData) && !empty($districtData)){
                if(isset($districtData) && !empty($districtData)){
                    foreach($districtData as $k=>$v){
                        $option .= '<option value="'.url_encryption($v['district_code']).'">'.strtoupper($v['district_name']).'</option>';
                    }
                }
            }
        }
        echo $option; exit;
    }
    public function getEstablishment(){
        $option = '<option value="">Select Subordinate Court</option>';
        if(isset($_POST['get_state_id']) && !empty($_POST['get_state_id']) && !empty($_POST['get_distt_id'])){
            $state_code = (int)url_decryption($_POST['get_state_id']);
            $district_code = (int)url_decryption($_POST['get_distt_id']);
            $params = array();
            $type = 3; //establishment list
            $params['type']= $type;
            $params['state_code'] = $state_code;
            $params['district_code'] = $district_code;
            $establishData = $this->Common_model->getSubordinateCourtData($params);
            if(isset($establishData) && !empty($establishData)){
                foreach ($establishData as $k=>$v){
                    $option .= '<option value="'.url_encryption($v['estab_code']).'">'.strtoupper($v['estab_name']).'</option>';
                }
            }
        }
        echo $option; exit;
    }
    public function getCaseTypeByEstablishCode(){
        $option = '<option value="">Select Case Type</option><option value="' . htmlentities(url_encryption(trim('0##NOT IN LIST'), ENT_QUOTES)) .'">NOT IN LIST</option>';
        if(isset($_POST['establish_code']) && !empty($_POST['establish_code'])){
            $est_code = url_decryption($_POST['establish_code']);
            $params = array();
            $type = 4; //case type list
            $params['type']= $type;
            $params['est_code'] = $est_code;
            $caseTypeData = $this->Common_model->getSubordinateCourtData($params);
            if(isset($caseTypeData) && !empty($caseTypeData)){
                    foreach($caseTypeData as $k=>$v){
                        $option .= '<option value="'.url_encryption($v['case_type']. '##' . strtoupper($v['type_name'])).'">'.strtoupper($v['type_name']).'</option>';
                    }
                $option .= '<option value="' . htmlentities(url_encryption(trim('0##NOT IN LIST'), ENT_QUOTES)) .'">NOT IN LIST</option>';
            }
        }
        echo $option; exit;
    }
    public function getHighCourt(){
        $option = '<option value="">Select High Court</option>';
        $params = array();
        $type = 1; //high court list
        $params['type']= $type;
        $highCourtData = $this->Common_model->getHighCourtData($params);
            if(isset($highCourtData) && !empty($highCourtData)){
                foreach($highCourtData as $k=>$v){
                    $option .= '<option value="'.url_encryption($v['hc_id']).'">'.strtoupper($v['name']).'</option>';
                }
            }
        echo $option; exit;
    }
public function getBench(){
    $option = '<option value="">Select Bench</option>';
    if(isset($_POST['state_code']) && !empty($_POST['state_code'])){
        $highCourtId = url_decryption($_POST['state_code']);
        $params = array();
        $type = 2; //high court bench list
        $params['type']= $type;
        $params['hc_id'] = (int)$highCourtId;
        $benchData  = $this->Common_model->getHighCourtData($params);
            if(isset($benchData) && !empty($benchData)){
                foreach($benchData as $k=>$v){
                    $option .= '<option value="'.url_encryption($v['est_code']).'">'.strtoupper($v['name']).'</option>';
                }
            }
    }
    echo $option; exit;
}
    public function getCaseTypeByBenchEstabCode(){
        $option = '<option value="">Select Case Type</option><option value="'.htmlentities(url_encryption(trim('0##NOT IN LIST'), ENT_QUOTES)).'">NOT IN LIST</option>';
        if(isset($_POST['bench_est_code']) && !empty($_POST['bench_est_code'])){
            $bench_est_code = url_decryption($_POST['bench_est_code']);
            $params = array();
            $type = 3; //case list
            $params['type']= $type;
            $params['est_code'] = $bench_est_code;
            $caseTypeData  = $this->Common_model->getHighCourtData($params);
                if(isset($caseTypeData) && !empty($caseTypeData)){
                    foreach($caseTypeData as $k=>$v){
                        $option .= '<option value="'.url_encryption($v['case_type'].'##'.strtoupper($v['type_name'])).'">'.strtoupper($v['type_name']).'</option>';
                    }
                }
        }
        echo $option; exit;
    }

}
