<?php
namespace App\Controllers;

class Lists extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->library('webservices/efiling_webservices');
    }

    public function _remap() {

        $this->index($this->uri->segment(3), $this->uri->segment(4));
    }

    public function index($type, $listFor) {

        $users_array = array(USER_ADVOCATE, USER_CLERK, USER_DEPARTMENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $users_array)) {
            redirect('login');
            exit(0);
        }

        $listRequestType = array('diary', 'defective', 'registered', 'refiled', 'caveat', 'ia', 'miscDoc', 'disposed', 'judgment', 'dailyOrder', 'certCopy', 'officeReport');
        $listForArray = array('yesterday', 'today', 'tomorrow', 'total');

        if (!in_array($type, $listRequestType) && !in_array($listFor, $listForArray)) {
            redirect('login');
            exit(0);
        }

        $adv_bar_id = $_SESSION['login']['adv_sci_bar_id'];

        if ($listFor == 'yesterday') {

            $list_head_period = 'YESTERDAY';
            $from_date = $next_date = date("Y-m-d", strtotime("yesterday"));
            $date = $from_date . '_to_' . $next_date;
            $date = '2019-09-02_to_2019-09-02';
        } elseif ($listFor == 'today') {

            $list_head_period = 'TODAY';
            $from_date = $next_date = date("Y-m-d", strtotime("today"));
            $date = $from_date . '_to_' . $next_date;
            $date = '2019-09-03_to_2019-09-03';
        } elseif ($listFor == 'tomorrow') {

            $list_head_period = 'TOMORROW';
            $from_date = $next_date = date("Y-m-d", strtotime("tomorrow"));
            $date = $from_date . '_to_' . $next_date;
            $date = '2019-09-04_to_2019-09-04';
        } elseif ($listFor == 'total') {

            $list_head_period = 'FROM ' . date("d-m-Y", strtotime("-7 Days")) . ' TO ' . date("d-m-Y", strtotime("+7 Days"));
            $from_date = date("Y-m-d", strtotime("-7 Days"));
            $next_date = date("Y-m-d", strtotime("+7 Days"));
            $date = $from_date . '_to_' . $next_date;
            $date = '2019-09-01_to_2019-09-07';
        }


        if ($type == 'diary') {

            $data['list_head'] = 'Diary Received ( ' . $list_head_period . ' )';
            $result = $this->get_data_list('diaries', $adv_bar_id, $date, $listFor);
            $result_index = 'cases.diaries.' . $adv_bar_id . '.' . $date;
            $data['myUpdatesList'] = $result[$result_index];
        } elseif ($type == 'defective') {

            $data['list_head'] = 'Defects Notified ( ' . $list_head_period . ' )';
            $result = $this->get_data_list('defects_notified', $adv_bar_id, $date, $listFor);
            $result_index = 'cases.defects_notified.' . $adv_bar_id . '.' . $date;
            $data['myUpdatesList'] = $result[$result_index];
        } elseif ($type == 'registered') {

            $data['list_head'] = 'Registered ( ' . $list_head_period . ' )';
            $result = $this->get_data_list('registered', $adv_bar_id, $date, $listFor);
            $result_index = 'cases.registered.' . $adv_bar_id . '.' . $date;
            $data['myUpdatesList'] = $result[$result_index];
        } elseif ($type == 'refiled') {

            $data['list_head'] = 'Re-filed ( ' . $list_head_period . ' )';
            $result = $this->get_data_list('refiled', $adv_bar_id, $date, $listFor);
            $result_index = 'cases.refiled.' . $adv_bar_id . '.' . $date;
            $data['myUpdatesList'] = $result[$result_index];
        } elseif ($type == 'caveat') {

            $data['list_head'] = 'Caveat ( ' . $list_head_period . ' )';
            $result = $this->get_data_list('caveat', $adv_bar_id, $date, $listFor);
            $result_index = 'cases.caveat.' . $adv_bar_id . '.' . $date;
            $data['myUpdatesList'] = $result[$result_index];
        } elseif ($type == 'ia') {

            $data['list_head'] = 'IA ( ' . $list_head_period . ' )';
            $result = $this->get_data_list('ia', $adv_bar_id, $date, $listFor);
            $result_index = 'cases.ia.' . $adv_bar_id . '.' . $date;
            $data['myUpdatesList'] = $result[$result_index];
        } elseif ($type == 'miscDoc') {

            $data['list_head'] = 'Misc. Documents ( ' . $list_head_period . ' )';
            $result = $this->get_data_list('misc_dak', $adv_bar_id, $date, $listFor);
            $result_index = 'cases.misc_dak.' . $adv_bar_id . '.' . $date;
            $data['myUpdatesList'] = $result[$result_index];
        } elseif ($type == 'disposed') {

            $data['list_head'] = 'Disposed ( ' . $list_head_period . ' )';
            $result = $this->get_data_list('disposed', $adv_bar_id, $date, $listFor);
            $result_index = 'cases.disposed.' . $adv_bar_id . '.' . $date;
            $data['myUpdatesList'] = $result[$result_index];
        } elseif ($type == 'judgment') {

            $data['list_head'] = 'Judgment ( ' . $list_head_period . ' )';
            $result = $this->get_data_list('judgments', $adv_bar_id, $date, $listFor);
            $result_index = 'cases.judgments.' . $adv_bar_id . '.' . $date;
            $data['myUpdatesList'] = $result[$result_index];
        } elseif ($type == 'dailyOrder') {

            $data['list_head'] = 'Daily Proceedings ( ' . $list_head_period . ' )';
            $result = $this->get_data_list('daily_orders', $adv_bar_id, $date, $listFor);
            $result_index = 'cases.daily_orders.' . $adv_bar_id . '.' . $date;
            $data['myUpdatesList'] = $result[$result_index];
        } elseif ($type == 'certCopy') {

            $data['list_head'] = 'Certified Copy ( ' . $list_head_period . ' )';
            $result = $this->get_data_list('certified_copies', $adv_bar_id, $date, $listFor);
            $result_index = 'cases.certified_copies.' . $adv_bar_id . '.' . $date;
            $data['myUpdatesList'] = $result[$result_index];
        } elseif ($type == 'officeReport') {

            $data['list_head'] = 'Office Report ( ' . $list_head_period . ' )';
            $result = $this->get_data_list('office_reports', $adv_bar_id, $date, $listFor);
            $result_index = 'cases.office_reports.' . $adv_bar_id . '.' . $date;
            $data['myUpdatesList'] = $result[$result_index];
        }


        $data['listFor'] = $listFor;

        $this->load->view('templates/header');
        $this->load->view('myUpdates/update_list_view', $data);
        $this->load->view('templates/footer');
    }

    public function get_data_list($type, $adv_bar_id, $date, $listFor) {

        if (READ_DATA_FROM_JSON) {
            $base_url = '';//base_url();
            if ($listFor == 'yesterday') {
                $json = file_get_contents($base_url . "json/icmis_statistics_yesterday.json");
            } elseif ($listFor == 'today') {
                $json = file_get_contents($base_url . "json/icmis_statistics_today.json");
            } elseif ($listFor == 'tomorrow') {
                $json = file_get_contents($base_url . "json/icmis_statistics_tommorrow.json");
            } elseif ($listFor == 'total') {
                $json = file_get_contents($base_url . "json/icmis_statistics_week.json");
            } else {
                $json = file_get_contents($base_url . "json/icmis_statistics_today.json");
            }
            $result = json_decode($json, true);
            $data = $result['data'];
            return $data;
        } else {

            $url = API_ICMIS_STATISTICS_URI."?keys[]=cases." . $type . "." . $adv_bar_id . "." . $date;
            $json = curl_get_contents($url);
            $result = json_decode($json, true);
            $data = $result['data'];
            return $data;
        }
    }

}
