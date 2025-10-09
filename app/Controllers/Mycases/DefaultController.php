<?php
namespace App\Controllers;

class DefaultController extends BaseController {

    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION['login']) && empty($_SESSION['login'])) {
            redirect('login');
        }else{
            is_user_status();
        }
    }

    public function index($type = NULL) {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE && $_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE_CIS && $_SESSION['login']['ref_m_usertype_id'] != USER_DEPARTMENT && $_SESSION['login']['ref_m_usertype_id'] != USER_CLERK) {
            redirect('login');
            exit(0);
        }

        $date = '2020-01-01_to_2020-01-05';
        $adv_bar_id = $_SESSION['login']['adv_sci_bar_id'];



        if (READ_DATA_FROM_JSON) {
            $base_url = '';//base_url();
            $json = file_get_contents($base_url . "json/cases_count.json");
            $result = json_decode($json, true);
            $data = $result['data'];
        } else {
            $url = API_ICMIS_STATISTICS_URI."?keys[]=cases.all." . $adv_bar_id . "." . $date . ".count&keys[]=cases.all_pending." . $adv_bar_id . "." . $date . ".count&keys[]=cases.all_pending_registered." . $adv_bar_id . "." . $date . ".count&keys[]=cases.all_petitioner_advocate_registered." . $adv_bar_id . "." . $date . ".count&keys[]=cases.all_respondent_advocate_registered." . $adv_bar_id . "." . $date . ".count&keys[]=cases.all_other_advocate_registered." . $adv_bar_id . "." . $date . ".count&keys[]=cases.all_pending_unregistered." . $adv_bar_id . "." . $date . ".count&keys[]=cases.all_petitioner_advocate_unregistered." . $adv_bar_id . "." . $date . ".count&keys[]=cases.all_respondent_advocate_unregistered." . $adv_bar_id . "." . $date . ".count&keys[]=cases.all_other_advocate_unregistered." . $adv_bar_id . "." . $date . ".count&keys[]=cases.all_disposed." . $adv_bar_id . "." . $date . ".count";
            $json = curl_get_contents($url);
            $result = json_decode($json, true);
            $data = $result['data'];
        }

        $data['total'] = $data['cases.all.' . $adv_bar_id . '.' . $date . '.count'];
        $data['pending_matters'] = $data['cases.all_pending.' . $adv_bar_id . '.' . $date . '.count'];
        $data['pending_reg_matters'] = $data['cases.all_pending_registered.' . $adv_bar_id . '.' . $date . '.count'];
        $data['pending_reg_pet_matters'] = $data['cases.all_petitioner_advocate_registered.' . $adv_bar_id . '.' . $date . '.count'];
        $data['pending_reg_res_matters'] = $data['cases.all_respondent_advocate_registered.' . $adv_bar_id . '.' . $date . '.count'];
        $data['pending_un_reg_matters'] = $data['cases.all_pending_unregistered.' . $adv_bar_id . '.' . $date . '.count'];
        $data['pending_un_reg_pet_matters'] = $data['cases.all_petitioner_advocate_unregistered.' . $adv_bar_id . '.' . $date . '.count'];
        $data['pending_un_reg_res_matters'] = $data['cases.all_respondent_advocate_unregistered.' . $adv_bar_id . '.' . $date . '.count'];
        $data['disposed_matter'] = $data['cases.all_disposed.' . $adv_bar_id . '.' . $date . '.count'];


        if (READ_DATA_FROM_JSON) {
            $base_url = '';//base_url();
            $json = file_get_contents($base_url . "json/cases.all_pending_registered.json");
            $result = json_decode($json, true);
            $data_list = $result['data'];
        } else {
            $url = API_ICMIS_STATISTICS_URI."?keys[]=cases.all_pending_registered." . $adv_bar_id . "." . $date;
            $json = curl_get_contents($url);
            $result = json_decode($json, true);
            $data_list = $result['data'];
        }

        $data['pending_reg_matters_data'] = $data_list['cases.all_pending_registered.' . $adv_bar_id . '.' . $date];

        $this->load->view('templates/header');
        $this->load->view('mycases/mycases_updation_view', $data);
        $this->load->view('templates/footer');
    }

}
