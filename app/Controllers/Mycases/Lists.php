<?php
namespace App\Controllers;

class Lists extends BaseController {

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

    public function index($type = NULL) {

        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE && $_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE_CIS && $_SESSION['login']['ref_m_usertype_id'] != USER_DEPARTMENT && $_SESSION['login']['ref_m_usertype_id'] != USER_CLERK) {
            redirect('login');
            exit(0);
        }

        $date = '2020-01-01_to_2020-01-05';
        $adv_bar_id = $_SESSION['login']['adv_sci_bar_id'];

        if (READ_DATA_FROM_JSON) {
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


        if ($type == 'total') {

            $result = $this->get_data_list('all', $adv_bar_id, $date);
            $result_index = 'cases.all.' . $adv_bar_id . '.' . $date;
            $data['total_matters_data'] = $result[$result_index];
        } elseif ($type == 'pending') {

            $result = $this->get_data_list('all_pending', $adv_bar_id, $date);
            $result_index = 'cases.all_pending.' . $adv_bar_id . '.' . $date;
            $data['pending_matters_data'] = $result[$result_index];
        } elseif ($type == 'disposed') {

            $result = $this->get_data_list('all_disposed', $adv_bar_id, $date);
            $result_index = 'cases.all_disposed.' . $adv_bar_id . '.' . $date;
            $data['disposed_matters_data'] = $result[$result_index];
        } elseif ($type == 'pending_reg') {

            $result = $this->get_data_list('all_pending_registered', $adv_bar_id, $date);
            $result_index = 'cases.all_pending_registered.' . $adv_bar_id . '.' . $date;
            $data['pending_reg_matters_data'] = $result[$result_index];
        } elseif ($type == 'pending_unreg') {

            $result = $this->get_data_list('all_pending_unregistered', $adv_bar_id, $date);
            $result_index = 'cases.all_pending_unregistered.' . $adv_bar_id . '.' . $date;
            $data['pending_unreg_matters_data'] = $result[$result_index];
        } elseif ($type == 'pending_reg_pet') {

            $result = $this->get_data_list('all_petitioner_advocate_registered', $adv_bar_id, $date);
            $result_index = 'cases.all_petitioner_advocate_registered.' . $adv_bar_id . '.' . $date;
            $data['pending_reg_pet_matters_data'] = $result[$result_index];
        } elseif ($type == 'pending_reg_res') {

            $result = $this->get_data_list('all_respondent_advocate_registered', $adv_bar_id, $date);
            $result_index = 'cases.all_respondent_advocate_registered.' . $adv_bar_id . '.' . $date;
            $data['pending_reg_res_matters_data'] = $result[$result_index];
        } elseif ($type == 'pending_reg_other') {

            $result = $this->get_data_list('all_other_advocate_registered', $adv_bar_id, $date);
            $result_index = 'cases.all_other_advocate_registered.' . $adv_bar_id . '.' . $date;
            $data['pending_reg_pet'] = $result[$result_index];
        } elseif ($type == 'pending_unreg_pet') {

            $result = $this->get_data_list('all_petitioner_advocate_unregistered', $adv_bar_id, $date);
            $result_index = 'cases.all_petitioner_advocate_unregistered.' . $adv_bar_id . '.' . $date;
            $data['pending_unreg_pet_matters_data'] = $result[$result_index];
        } elseif ($type == 'pending_unreg_res') {

            $result = $this->get_data_list('all_respondent_advocate_unregistered', $adv_bar_id, $date);
            $result_index = 'cases.all_respondent_advocate_unregistered.' . $adv_bar_id . '.' . $date;
            $data['pending_unreg_res_matters_data'] = $result[$result_index];
        } elseif ($type == 'pending_unreg_other') {

            $result = $this->get_data_list('all_petitioner_advocate_registered', $adv_bar_id, $date);
            $result_index = 'cases.all_petitioner_advocate_registered.' . $adv_bar_id . '.' . $date;
            $data['pending_reg_pet'] = $result[$result_index];
        } elseif ($type == 'all_other_advocate_unregistered') {

            $result = $this->get_data_list('all_other_advocate_unregistered', $adv_bar_id, $date);
            $result_index = 'cases.all_other_advocate_unregistered.' . $adv_bar_id . '.' . $date;
            $data['pending_reg_pet'] = $result[$result_index];
        } else {

            $result = $this->get_data_list('all_pending_registered', $adv_bar_id, $date);
            $result_index = 'cases.all_pending_registered.' . $adv_bar_id . '.' . $date;
            $data['pending_reg_matters_data'] = $result[$result_index];
        }

        $this->load->view('templates/header');
        $this->load->view('mycases/mycases_updation_view', $data);
        $this->load->view('templates/footer');
    }

    public function get_data_list($type, $adv_bar_id, $date) {



        if (READ_DATA_FROM_JSON) {
            $base_url = '';//base_url();
            if ($type == 'all') {
                $json = file_get_contents($base_url . "json/cases_all.json");
                $result = json_decode($json, true);
                $data = $result['data'];
                return $data;
            } elseif ($type == 'all_pending') {
                $json = file_get_contents($base_url . "json/cases.all_pending.json");
                $result = json_decode($json, true);
                $data = $result['data'];
                return $data;
            } elseif ($type == 'all_pending_registered') {
                $json = file_get_contents($base_url . "json/cases.all_pending_registered.json");
                $result = json_decode($json, true);
                $data = $result['data'];
                return $data;
            } elseif ($type == 'all_petitioner_advocate_registered') {
                $json = file_get_contents($base_url . "json/cases.all_petitioner_advocate_registered.json");
                $result = json_decode($json, true);
                $data = $result['data'];
                return $data;
            } elseif ($type == 'all_respondent_advocate_registered') {
                $json = file_get_contents($base_url . "json/cases.all_respondent_advocate_registered.json");
                $result = json_decode($json, true);
                $data = $result['data'];
                return $data;
            } elseif ($type == 'all_other_advocate_registered') {
                $json = file_get_contents($base_url . "json/cases.all_other_advocate_registered.json");
                $result = json_decode($json, true);
                $data = $result['data'];
                return $data;
            } elseif ($type == 'all_pending_unregistered') {
                $json = file_get_contents($base_url . "json/cases.all_pending_unregistered.json");
                $result = json_decode($json, true);
                $data = $result['data'];
                return $data;
            } elseif ($type == 'all_petitioner_advocate_unregistered') {
                $json = file_get_contents($base_url . "json/cases.all_petitioner_advocate_unregistered.json");
                $result = json_decode($json, true);
                $data = $result['data'];
                return $data;
            } elseif ($type == 'all_respondent_advocate_unregistered') {
                $json = file_get_contents($base_url . "json/cases.all_respondent_advocate_unregistered.json");
                $result = json_decode($json, true);
                $data = $result['data'];
                return $data;
            } elseif ($type == 'all_other_advocate_unregistered') {
                $json = file_get_contents($base_url . "json/cases.all_other_advocate_unregistered.json");
                $result = json_decode($json, true);
                $data = $result['data'];
                return $data;
            } elseif ($type == 'all_disposed') {
                $json = file_get_contents($base_url . "json/cases.all_disposed.json");
                $result = json_decode($json, true);
                $data = $result['data'];
                return $data;
            }
        } else {
            $url = API_ICMIS_STATISTICS_URI."?keys[]=cases." . $type . "." . $adv_bar_id . "." . $date;
            $json = curl_get_contents($url);
            $result = json_decode($json, true);
            $data = $result['data'];
            return $data;
        }
    }

}
