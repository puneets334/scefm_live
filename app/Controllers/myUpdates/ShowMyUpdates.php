<?php
namespace App\Controllers;

class ShowMyUpdates extends BaseController {

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

        $users_array = array(USER_ADVOCATE, USER_CLERK, USER_DEPARTMENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $users_array)) {
            redirect('login');
            exit(0);
        }

        $adv_bar_id = $_SESSION['login']['adv_sci_bar_id'];

        if ($param == 'yesterday') {

            $from_date = $next_date = date("Y-m-d", strtotime("yesterday"));
            $date = $from_date . '_to_' . $next_date;
            $date = '2019-09-02_to_2019-09-02';
        } elseif ($param == 'today') {

            $from_date = $next_date = date("Y-m-d", strtotime("today"));
            $date = $from_date . '_to_' . $next_date;
            $date = '2019-09-03_to_2019-09-03';
        } elseif ($param == 'tomorrow') {

            $from_date = $next_date = date("Y-m-d", strtotime("tomorrow"));
            $date = $from_date . '_to_' . $next_date;
            $date = '2019-09-04_to_2019-09-04';
        } elseif ($param == 'total') {

            $from_date = date("Y-m-d", strtotime("-7 Days"));
            $next_date = date("Y-m-d", strtotime("+7 Days"));
            $date = $from_date . '_to_' . $next_date;
            $date = '2019-09-01_to_2019-09-07';
        } else {

            $param = 'today';
            $from_date = $next_date = date("Y-m-d", strtotime("today"));
            $date = $from_date . '_to_' . $next_date;
            $date = '2019-09-03_to_2019-09-03';
        }
        
        $diaries_count_indx = 'cases.diaries.' . $adv_bar_id . '.' . $date . '.count';
        $defects_notified_count_indx = 'cases.defects_notified.' . $adv_bar_id . '.' . $date . '.count';
        $registered_count_indx = 'cases.registered.' . $adv_bar_id . '.' . $date . '.count';
        $refiled_count_indx = 'cases.refiled.' . $adv_bar_id . '.' . $date . '.count';
        $ia_count_indx = 'cases.ia.' . $adv_bar_id . '.' . $date . '.count';
        $misc_dak_count_indx = 'cases.misc_dak.' . $adv_bar_id . '.' . $date . '.count';
        $caveat_count_indx = 'cases.caveat.' . $adv_bar_id . '.' . $date . '.count';
        $disposed_count_indx = 'cases.disposed.' . $adv_bar_id . '.' . $date . '.count';
        $judgments_count_indx = 'cases.judgments.' . $adv_bar_id . '.' . $date . '.count';
        $daily_orders_count_indx = 'cases.daily_orders.' . $adv_bar_id . '.' . $date . '.count';
        $certified_copies_count_indx = 'cases.certified_copies.' . $adv_bar_id . '.' . $date . '.count';
        $officer_reports_count_indx = 'cases.officer_reports.' . $adv_bar_id . '.' . $date . '.count';

        if (READ_DATA_FROM_JSON) {
            $base_url = '';//base_url();
            if ($param == 'yesterday') {
                $json = file_get_contents($base_url . "json/yesterday.json");
            } elseif ($param == 'today') {
                $json = file_get_contents($base_url . "json/today.json");
            } elseif ($param == 'tomorrow') {
                $json = file_get_contents($base_url . "json/tommorrow.json");
            } elseif ($param == 'total') {
                $json = file_get_contents($base_url . "json/week.json");
            } else {
                $json = file_get_contents($base_url . "json/yesterday.json");
            }

            $result = json_decode($json, true);
            $data = $result['data'];
           
            $data['myUpdatesCount']['diary_received'] = $data[$diaries_count_indx];
            $data['myUpdatesCount']['defects_notified_count'] = $data[$defects_notified_count_indx];
            $data['myUpdatesCount']['registered_count'] = $data[$registered_count_indx];
            $data['myUpdatesCount']['re_filed'] = $data[$refiled_count_indx];
            $data['myUpdatesCount']['IA_count'] = $data[$ia_count_indx];
            $data['myUpdatesCount']['misc_count'] = $data[$misc_dak_count_indx];
            $data['myUpdatesCount']['caveat_count'] = $data[$caveat_count_indx];
            $data['myUpdatesCount']['disposed_count'] = $data[$disposed_count_indx];
            $data['myUpdatesCount']['judgment_count'] = $data[$judgments_count_indx];
            $data['myUpdatesCount']['daily_count'] = $data[$daily_orders_count_indx];
            $data['myUpdatesCount']['certified_copy_count'] = $data[$certified_copies_count_indx];
            $data['myUpdatesCount']['office_report'] = $data[$officer_reports_count_indx];
            
        } else {

            $url = API_ICMIS_STATISTICS_URI."?keys[]=cases.diaries." . $adv_bar_id . "." . $date . ".count&keys[]=cases.defects_notified." . $adv_bar_id . "." . $date . ".count&keys[]=cases.registered." . $adv_bar_id . "." . $date . ".count&keys[]=cases.refiled." . $adv_bar_id . "." . $date . ".count&keys[]=cases.misc_dak." . $adv_bar_id . "." . $date . ".count&keys[]=cases.disposed." . $adv_bar_id . "." . $date . ".count&keys[]=cases.judgments." . $adv_bar_id . "." . $date . ".count&keys[]=cases.daily_orders." . $adv_bar_id . "." . $date . ".count&keys[]=cases.certified_copies." . $adv_bar_id . "." . $date . ".count&keys[]=cases.office_reports." . $adv_bar_id . "." . $date . ".count&keys[]=cases.caveat." . $adv_bar_id . "." . $date . ".count&keys[]=cases.ia." . $adv_bar_id . "." . $date . ".count";
            $json = curl_get_contents($url);
            $result = json_decode($json, true);
            $data = $result['data'];

            $data['myUpdatesCount']['diary_received'] = $data[$diaries_count_indx][0];
            $data['myUpdatesCount']['defects_notified_count'] = $data[$defects_notified_count_indx][0];
            $data['myUpdatesCount']['registered_count'] = $data[$registered_count_indx][0];
            $data['myUpdatesCount']['re_filed'] = $data[$refiled_count_indx][0];
            $data['myUpdatesCount']['IA_count'] = $data[$ia_count_indx][0];
            $data['myUpdatesCount']['misc_count'] = $data[$misc_dak_count_indx][0];
            $data['myUpdatesCount']['caveat_count'] = $data[$caveat_count_indx][0];
            $data['myUpdatesCount']['disposed_count'] = $data[$disposed_count_indx][0];
            $data['myUpdatesCount']['judgment_count'] = $data[$judgments_count_indx][0];
            $data['myUpdatesCount']['daily_count'] = $data[$daily_orders_count_indx][0];
            $data['myUpdatesCount']['certified_copy_count'] = $data[$certified_copies_count_indx][0];
            $data['myUpdatesCount']['office_report'] = $data[$officer_reports_count_indx][0];
        }

        $data['listFor'] = $param;
        $updatesData = $this->load->view('myUpdates/myUpdates_data_view', $data, TRUE);
        echo $updatesData;
    }

}
