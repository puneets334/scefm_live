<?php
namespace App\Controllers;

class DefaultController extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $users_array = array(USER_ADVOCATE, USER_CLERK, USER_DEPARTMENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $users_array)) {
            redirect('login');
            exit(0);
        }

        if (!empty($_POST['from'] && $_POST['to_date'])) {
            $from_date = date('Y-m-d', strtotime($_POST['from']));
            $next_date = date('Y-m-d', strtotime($_POST['to_date']));
            $date = $from_date . '_to_' . $next_date;
        } else {
            $next_date = date("Y-m-d", strtotime("+1 week"));
            $from_date = date("Y-m-d");
            $date = $from_date . '_to_' . $next_date;
        }

        $from_date = $next_date = date("Y-m-d", strtotime("today"));

        $date = '2019-09-01_to_2019-09-07';
        $adv_bar_id = $_SESSION['login']['adv_sci_bar_id'];

        $url = API_ICMIS_STATISTICS_URI."?keys[]=cases.diaries." . $adv_bar_id . "." . $date . ".count&keys[]=cases.defects_notified." . $adv_bar_id . "." . $date . ".count&keys[]=cases.defects_notified." . $adv_bar_id . "." . $date . ".count&keys[]=cases.registered." . $adv_bar_id . "." . $date . ".count&keys[]=cases.refiled." . $adv_bar_id . "." . $date . ".count&keys[]=cases.ia." . $adv_bar_id . "." . $date . ".count&keys[]=cases.misc_dak." . $adv_bar_id . "." . $date . ".count&keys[]=cases.disposed." . $adv_bar_id . "." . $date . ".count&keys[]=cases.judgments." . $adv_bar_id . "." . $date . ".count&keys[]=cases.daily_orders." . $adv_bar_id . "." . $date . ".count&keys[]=cases.certified_copies." . $adv_bar_id . "." . $date . ".count&keys[]=cases.officer_reports." . $adv_bar_id . "." . $date . ".count";
        $json = curl_get_contents($url);
        $result = json_decode($json, true);
        $data = $result['data'];

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

        $data['myUpdatesCount']['diary_received'] = $data[$diaries_count_indx];
        $data['myUpdatesCount']['defects_notified_count'] = $data[$defects_notified_count_indx];
        $data['myUpdatesCount']['registered_count'] = $data[$registered_count_indx];
        $data['myUpdatesCount']['re_filed'] = $data[$refiled_count_indx];
        $data['myUpdatesCount']['IA_count'] = $data[$ia_count_indx];
        $data['myUpdatesCount']['misc_count'] = $data[$misc_dak_count_indx];
        $data['myUpdatesCount']['caveat_count'] = 0; //$data[$caveat_count_indx];
        $data['myUpdatesCount']['disposed_count'] = $data[$disposed_count_indx];
        $data['myUpdatesCount']['judgment_count'] = $data[$judgments_count_indx];
        $data['myUpdatesCount']['daily_count'] = $data[$daily_orders_count_indx];
        $data['myUpdatesCount']['certified_copy_count'] = $data[$certified_copies_count_indx];
        $data['myUpdatesCount']['office_report'] = $data[$officer_reports_count_indx];

        $data['listFor'] = 'today';

        $this->load->view('templates/header');
        $this->load->view('myUpdates/update_count_view', $data);
        $this->load->view('templates/footer');
    }

}
