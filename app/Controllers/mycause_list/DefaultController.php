<?php
namespace App\Controllers;

class DefaultController extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    public function _remap($param = NULL) {

        if ($param == 'index') {
            $this->index(NULL);
        } else {
            $this->index($param);
        }
    }

    public function index($param) {

        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE && $_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE_CIS && $_SESSION['login']['ref_m_usertype_id'] != USER_DEPARTMENT && $_SESSION['login']['ref_m_usertype_id'] != USER_CLERK) {
            redirect('login');
            exit(0);
        }

        $date = '2019-08-01';
        $adv_bar_id = $_SESSION['login']['adv_sci_bar_id'];

        if ($param == 'advance') {

            if (READ_DATA_FROM_JSON) {
                $base_url = '';//base_url();
                $json = file_get_contents($base_url . "json/cause_list_advance.json");
                $result = json_decode($json, true);
                $data_res = $result['data'];
            } else {
                $url = API_ICMIS_STATISTICS_URI."/live?keys[]=cause_list.advance." . $adv_bar_id . "." . $date;
                $json = curl_get_contents($url);
                $result = json_decode($json, true);
                $data_res = $result['data'];
            }
            $data['aor_cause_list'] = $data_res['cause_list.' . $param . '.' . $adv_bar_id . '.' . $date];
            $updatesData = $this->load->view('mycause_list/all_cause_list', $data);
            echo $updatesData;
        } elseif ($param == 'elimination') {

            if (READ_DATA_FROM_JSON) {
                $base_url = '';//base_url();
                $json = file_get_contents($base_url . "json/cause_list_elimination.json");
                $result = json_decode($json, true);
                $data_res = $result['data'];
            } else {
                $url = API_ICMIS_STATISTICS_URI."/live?keys[]=cause_list.elimination." . $adv_bar_id . "." . $date;
                $json = curl_get_contents($url);
                $result = json_decode($json, true);
                $data_res = $result['data'];
            }


            $data['aor_cause_list'] = $data_res['cause_list.' . $param . '.' . $adv_bar_id . '.' . $date];
            $updatesData = $this->load->view('mycause_list/all_cause_list', $data);
            echo $updatesData;
        } elseif ($param == 'cause_list') {

            if (READ_DATA_FROM_JSON) {
                $base_url = '';//base_url();
                $json = file_get_contents($base_url . "json/cause_list_all.json");
                $result = json_decode($json, true);
                $data_res = $result['data'];
            } else {
                $url = API_ICMIS_STATISTICS_URI."/live?keys[]=cause_list.all." . $adv_bar_id . "." . $date;
                $json = curl_get_contents($url);
                $result = json_decode($json, true);
                $data_res = $result['data'];
            }

            
            $data['aor_cause_list'] = $data_res['cause_list.all.' . $adv_bar_id . '.' . $date];
            $updatesData = $this->load->view('mycause_list/all_cause_list', $data);
            echo $updatesData;
        } else {

            if (READ_DATA_FROM_JSON) {
                $base_url = '';//base_url();
                $json = file_get_contents($base_url . "json/cause_list_all.json");
                $result = json_decode($json, true);
                $data_res = $result['data'];
            } else {

                $url = API_ICMIS_STATISTICS_URI."/live?keys[]=cause_list.all." . $adv_bar_id . "." . $date;
                $json = curl_get_contents($url);
                $result = json_decode($json, true);
                $data_res = $result['data'];
            }

            $param = 'all';
            $data['aor_cause_list'] = $data_res['cause_list.' . $param . '.' . $adv_bar_id . '.' . $date];

            $this->load->view('templates/header');
            $this->load->view('mycause_list/mycause_list_view', $data);
            $this->load->view('templates/footer');
        }
    }

}
