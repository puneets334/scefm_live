<?php

namespace App\Controllers\AIAssisted;

use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\AIAssisted\ReportModel;

class ReportSearch extends BaseController {

    public $Report_model;
    public $efiling_webservices;

    public function __construct() {
        parent::__construct();
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $this->Report_model = new ReportModel();
        $this->efiling_webservices = new Efiling_webservices();
    }

    public function index() {
        $users_array = array(USER_ADMIN, SC_ADMIN);
        if (empty(getSessionData('login')) || !in_array(getSessionData('login.ref_m_usertype_id'), $users_array)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $data['stage_list'] = $this->Report_model->get_stage();
        $data['efiling_type_list'] = $this->Report_model->get_efiling_type();
        $data['users_types_list'] = $this->Report_model->get_user_types();
        $data['sc_case_type'] = $this->Report_model->get_sci_case_type();
        return $this->render('AIAssisted.report_cases_search', $data);
    }

    function get_aiassisted_cases() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
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
        $fromDateF = @$dates[0]; $toDateF = @$dates[1];
        $fromDate = escape_data(date("Y-m-d H:i:s", strtotime($fromDateF)));
        $toDate = escape_data(date("Y-m-d H:i:s", strtotime($toDateF)));
        if(!empty($search_type) && $search_type != null && $search_type == 'All' && $search_type != 'Diary' && $search_type != 'efiling') {
            if($status_type=='C') {
                $Report_fromDate_toDate='Completed Report for Date :'.$fromDate . ' TO '.$toDate;
            } else {
                $Report_fromDate_toDate="Pending Report";
            }
        } elseif(!empty($search_type) && $search_type!=null && $search_type=='Diary' && $search_type!='efiling' && $search_type!= 'All') {
            $Report_fromDate_toDate='Diary Number :'.$diary_no . '/'.$diary_year;
        } elseif(!empty($search_type) && $search_type!=null && $search_type=='efiling' && $search_type!='Diary' && $search_type!= 'All') {
            $Report_fromDate_toDate='E-Filing Number :'.$efiling_no . '-'.$efiling_year;
        } else {
            $Report_fromDate_toDate='Data not fount.';
        }
        $data = $this->Report_model->get_aiassisted_case_report_list($search_type,$ActionFiledOn,$DateRange,array($stages),$filing_type_id,$users_id,$diary_no,$diary_year,$efiling_no,$efiling_year,$_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id'],$status_type);
        if (!empty($data) && $data!=null && $data[0]->efiling_no!=null) {
            $status=array('search_type'=>$search_type,'status'=>'true','msg'=>'Data is found','Report_fromDate_toDate'=>$Report_fromDate_toDate);
            $dataDBFinal11['customers']=$data;
            $dataDBFinal11['status']=$status;
            echo json_encode($dataDBFinal11);
        } else {
            $status=array('search_type'=>$search_type,'status'=>'false','msg'=>'Data is not found.','Report_fromDate_toDate'=>$Report_fromDate_toDate);
            $dataDBFinal11['customers']=$data;
            $dataDBFinal11['status']=$status;
            echo json_encode($dataDBFinal11);
        }
    }

    function view() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $request = $this->request;
        $uri = $request->getUri();
        $uris = $uri->getSegment(4);
        $muri = str_replace('.','/',$uris);
        $registration_id = $uri->getSegment(5);
        $type = $uri->getSegment(6);
        $stage = $uri->getSegment(7);
        $efiling_no = $uri->getSegment(8);
        $ids = $registration_id.'#'.$type.'#'.$stage.'#'.$efiling_no;
        $idss = url_encryption($ids);
        if (!empty($registration_id) && !empty($type) && !empty($stage)) {
            return redirect()->to($muri.'/'.$idss);
        } else {
            return redirect()->to(base_url('report/refiled_cases_search'));
        }
    }

    function Get_search_case_details_rpt() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $web_service_result = $this->efiling_webservices->get_case_details_from_SCIS(url_decryption(escape_data($_GET['case_type'])), escape_data($_GET['caseNo']), escape_data($_GET['caseYr']));
        if(!empty($web_service_result->case_details[0])) {
            $diary_no = $web_service_result->case_details[0]->diary_no;
            $diary_year = $web_service_result->case_details[0]->diary_year;
        } else {
            $diary_no='';
            $diary_year='';
        }
        $rcd_data[]=array('diary_no' => $diary_no , 'diary_year' => $diary_year );
        echo json_encode($rcd_data);
    }

}