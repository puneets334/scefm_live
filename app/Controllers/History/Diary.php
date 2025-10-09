<?php
namespace App\Controllers;

class Diary extends BaseController {

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

        $id = url_decryption($param);
        $InputArrray = explode('##', $id);   //0=>Diary no,1=>Diary year
        $diary_no = $InputArrray[0];
        $diary_year = $InputArrray[1];

        if (empty($diary_no) && empty($diary_year)) {
            $_SESSION['MSG'] = message_show("fail", 'Invalid request !');
            redirect(isset($_SERVER['HTTP_REFERER']));
            exit(0);
        }

        /* $url = API_ICMIS_STATISTICS_URI."?keys[]=cases.diaries." . $adv_bar_id . "." . $date . ".count&keys[]=cases.defects_notified." . $adv_bar_id . "." . $date . ".count&keys[]=cases.registered." . $adv_bar_id . "." . $date . ".count&keys[]=cases.refiled." . $adv_bar_id . "." . $date . ".count&keys[]=cases.ia." . $adv_bar_id . "." . $date . ".count&keys[]=cases.misc_dak." . $adv_bar_id . "." . $date . ".count&keys[]=cases.disposed." . $adv_bar_id . "." . $date . ".count&keys[]=cases.judgments." . $adv_bar_id . "." . $date . ".count&keys[]=cases.daily_orders." . $adv_bar_id . "." . $date . ".count&keys[]=cases.certified_copies." . $adv_bar_id . "." . $date . ".count&keys[]=cases.officer_reports." . $adv_bar_id . "." . $date . ".count&keys[]=cases.caveat." . $adv_bar_id . "." . $date . ".count";
          $json = curl_get_contents($url);
          $result = json_decode($json, true);
          $data = $result['data']; */

        // <?= CASE_STATUS_API
        // <!-- ?d_no=1342&d_yr=2012 -->

        // <!-- echo '<script type="text/javascript"> window.open("www.google.com", "_blank");</script>';
        // redirect($_SERVER['HTTP_REFERER']);
        // exit(0); -->
        /* $this->load->view('templates/header');
          $this->load->view('history/diary_history', $data);
          $this->load->view('templates/footer'); */
    }

}
