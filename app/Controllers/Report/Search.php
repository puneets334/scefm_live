<?php

namespace App\Controllers\Report;

use App\Controllers\BaseController;
use App\Models\FilingAdmin\FilingAdminModel;
use App\Models\Report\ReportModel;
use App\Models\Common\Common\Dropdown_list_model;
use App\Libraries\webservices\Efiling_webservices;

class Search extends BaseController
{

    protected $ReportModel;
    protected $Dropdown_list_model;
    protected $session;
    protected $slice;
    protected $agent;
    protected $validation;
    protected $Efiling_webservices;

    public function __construct() {
        parent::__construct();
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $this->ReportModel = new ReportModel;
        $this->session = \Config\Services::session();
        $this->agent = \Config\Services::request()->getUserAgent();
        $this->validation = \Config\Services::validation();
        $this->Efiling_webservices = new Efiling_webservices();
        helper(['file']);
    }

    public function index() {
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $allowed_users_array = array(USER_ADMIN, USER_ADMIN_READ_ONLY, USER_EFILING_ADMIN);
        if (empty(getSessionData('login')) || !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('/'));
            exit(0);
        } 
        $data = [];
        $rm = new ReportModel();
        $data['pending_data'] = $rm->getUserTypeWisePendency();
        $data['day_wise_pending'] = $rm->getDayWisePendency();
        $data['pendency_bifurcation'] = $rm->getPendencyBifurcation();
        $data['last_updated_on'] = $rm->getLastUpdatedCron();
        $data['stage_list'] = $rm->get_stage();
        $data['efiling_type_list'] = $rm->get_efiling_type();
        $data['users__types_list'] = $rm->get_user_types();
        $data['sc_case_type'] = $rm->get_sci_case_type();
        return $this->render('report.search', $data);
    }

    public function search_diary_no() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $rm = new ReportModel();
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $data['diary_no'] = $rm->get_diary_no_search($q);
        }
    }

    public function search_efiling_no() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $rm = new ReportModel();
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $data['efiling_no'] = $rm->get_efiling_no_search($q);
        }
    }

    function actionFiledon() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $rm = new ReportModel();
        $search_type = escape_data(trim($_GET['search_type']));
        $diary_no = escape_data(trim($_GET['diary_no']));
        $diary_year = escape_data(trim($_GET['diary_year']));
        $efiling_no = escape_data(trim($_GET['efiling_no']));
        $efiling_year = escape_data(trim($_GET['efiling_year']));
        $ActionFiledOn = escape_data(trim($_GET['ActionFiledOn']));
        $DateRange = escape_data(trim($_GET['DateRange']));
        $filing_type_id = escape_data(trim(url_decryption($_GET['filing_type_id'])));
        $users_id = escape_data(trim(url_decryption($_GET['users_id'])));
        $stages = escape_data(url_decryption($_GET['stage_id']));
        $status_type = escape_data($_GET['status_type']);
        $dates = @explode('to', $DateRange);
        $fromDateF = @$dates[0];
        $toDateF = @$dates[1];
        $fromDate = escape_data(date("Y-m-d H:i:s", strtotime($fromDateF)));
        $toDate = escape_data(date("Y-m-d H:i:s", strtotime($toDateF)));
        $diary_data = ((!empty($diary_no) && $diary_no != NULL && $diary_no != 'null') && (!empty($diary_year) && $diary_year != NULL && $diary_year != 'null')) ? $diary_no . '/' . $diary_year : 'No Record Found'; 
        if (!empty($search_type) && $search_type != null && $search_type == 'All' && $search_type != 'Diary' && $search_type != 'efiling') {
            if ($status_type == 'C') {
                $Report_fromDate_toDate = 'Completed Report for Date :' . $fromDate . ' TO ' . $toDate;
            } else {
                $Report_fromDate_toDate = "Pending Report";
            }
        } elseif (!empty($search_type) && $search_type != null && $search_type == 'Diary' && $search_type != 'efiling' && $search_type != 'All') {
            $Report_fromDate_toDate = 'Diary Number : ' . $diary_data;
        } elseif (!empty($search_type) && $search_type != null && $search_type == 'efiling' && $search_type != 'Diary' && $search_type != 'All') {
            $Report_fromDate_toDate = 'E-Filing Number : ' . $efiling_no . '-' . $efiling_year;
        } else {
            $Report_fromDate_toDate = 'Data not found.';
        }
        $data = $rm->get_report_list($search_type, $ActionFiledOn, $DateRange, array($stages), $filing_type_id, $users_id, $diary_no, $diary_year, $efiling_no, $efiling_year, $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id'], $status_type);
        if (!empty($data) && $data != null && count($data) != 0 && $data[0]->efiling_no != null) {
            $status = array('search_type' => $search_type, 'status' => 'true', 'msg' => 'Data is found', 'Report_fromDate_toDate' => $Report_fromDate_toDate);
            $dataDBFinal11['customers'] = $data;
            $dataDBFinal11['status'] = $status;
            return json_encode($dataDBFinal11);
        } else {
            $status = array('search_type' => $search_type, 'status' => 'false', 'msg' => 'Data is not found.', 'Report_fromDate_toDate' => $Report_fromDate_toDate);
            $dataDBFinal11['customers'] = $data;
            $dataDBFinal11['status'] = $status;
            return json_encode($dataDBFinal11);
        }
    }

    function view() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $segment = service('uri');
        $uris = $segment->getSegment(4);
        $muri = str_replace('.', '/', $uris);
        $registration_id = $segment->getSegment(5);
        $type = $segment->getSegment(6);
        $stage = $segment->getSegment(7);
        $efiling_no = $segment->getSegment(8);
        $ids = $registration_id . '#' . $type . '#' . $stage . '#' . $efiling_no;
        $idss = url_encryption($ids);
        if (!empty($registration_id) && !empty($type) && !empty($stage)) {
            return redirect()->to($muri . '/' . $idss);
        } else {
            return redirect()->to(base_url('report/search'));
        }
    }

    public function showCaseStatusReport() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $diary_no = '';
        $diary_year = '';
        if (!empty($_POST["diary_no"])) {
            if (!empty($_POST["diary_year"])) {
                $diary_no = escape_data($_POST["diary_no"]);
                $diary_year = escape_data($_POST["diary_year"]);
            } else {
                $diary_no = escape_data(SUBSTR($_POST["diary_no"], 0, strlen($_POST["diary_no"]) - 4));
                $diary_year = escape_data(SUBSTR($_POST["diary_no"], -4));
            }
            $view_path = env('CASE_STATUS_API') . '?d_no=' . $diary_no . '&d_yr=' . $diary_year;
            $case_status_content = file_get_contents($view_path);
            if (empty($case_status_content))
                $case_status_content = 'Data Not Found';
        } else {
            $case_status_content = '<p>Data Not Found</p>';
        }
        echo $case_status_content;
    }

    function Get_search_case_details_rpt() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $web_service_result = $this->Efiling_webservices->get_case_details_from_SCIS(url_decryption(escape_data($_POST['case_type'])), escape_data($_POST['caseNo']), escape_data($_POST['caseYr']));
        if (!empty($web_service_result->case_details[0])) {
            $diary_no = $web_service_result->case_details[0]->diary_no;
            $diary_year = $web_service_result->case_details[0]->diary_year;
        } else {
            $diary_no = '';
            $diary_year = '';
        }
        $rcd_data[] = array('diary_no' => $diary_no, 'diary_year' => $diary_year);
        echo json_encode($rcd_data);
    }

    function list($stages) {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $allowed_users_array = array(USER_ADMIN, USER_ADMIN_READ_ONLY, USER_EFILING_ADMIN);
        if (empty(getSessionData('login')) || !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $rm = new ReportModel();
        // $stages = escape_data(url_decryption($_GET['stage_id']));
        $stages = escape_data(url_decryption($stages));
        $data = $rm->get_read_only_report_list(array($stages));
        if (!empty($data) && $data != null && count($data) != 0 && $data[0]->efiling_no != null) {
            $status = array('status' => 'true', 'msg' => 'Data is found');
            $dataDBFinal11['report_list'] = $data;
            $dataDBFinal11['status'] = $status;
            // echo json_encode($dataDBFinal11);
        } else {
            $status = array('status' => 'false', 'msg' => 'Data is not found.');
            $dataDBFinal11['report_list'] = $data;
            $dataDBFinal11['status'] = $status;
        }
        return $this->render('report.search_list', $dataDBFinal11);
    }

}