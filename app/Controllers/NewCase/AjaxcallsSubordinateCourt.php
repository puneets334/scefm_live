<?php
namespace App\Controllers\Newcase;

use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\Common\CommonModel;
use App\Models\NewCase\DropdownListModel;
use App\Models\NewCase\GetDetailsModel;
use App\Libraries\webservices\Highcourt_webservices;
use App\Models\NewCase\DeleteSubordinateCourtModel;

class AjaxcallsSubordinateCourt extends BaseController {

    protected $Dropdown_list_model;
    protected $Get_details_model;
    protected $efiling_webservices;
    protected $common_model;
    protected $highcourt_webservices;
    protected $DeleteSubordinateCourt_model;
    protected $request;
    protected $validation;

    public function __construct() {
        parent::__construct();

        $this->Dropdown_list_model = new DropdownListModel();
        $this->Get_details_model = new GetDetailsModel();
        $this->efiling_webservices = new Efiling_webservices();
        $this->common_model = new CommonModel();
        $this->highcourt_webservices = new Highcourt_webservices();
        $this->DeleteSubordinateCourt_model = new DeleteSubordinateCourtModel();
        $this->request = \Config\Services::request();
        $this->validation = \Config\Services::validation();
    }

    function get_establishment_list() {

        $state_array = explode('#$', url_decryption(escape_data($_POST['state_id'])));
        $state_id = $state_array[0];
        $district_array = explode('#$', url_decryption(escape_data($_POST['get_distt_id'])));
        $district_id = $district_array[0];
        if (isset($state_id) && preg_match("/^[0-9]*$/", $state_id) && strlen($state_id) <= INTEGER_FIELD_LENGTH && isset($district_id) && preg_match("/^[0-9]*$/", $district_id) && strlen($district_id) <= INTEGER_FIELD_LENGTH) {

            if (!empty($state_id) && !empty($district_id)) {
                $params=array('type'=>3,'state_code'=>$state_id,'district_code'=>$district_id,);
                $result = $this->common_model->getSubordinateCourtData($params);
                    if (count($result)) {
                        echo '<option value=""> Select Establishment </option>';
                               foreach ($result as $value) {
                                        echo '<option  value="' . htmlentities(url_encryption($value['estab_code'] . '#$' . $value['estab_name']), ENT_QUOTES) . '">' . htmlentities(strtoupper($value['estab_name']), ENT_QUOTES) . '</option>';
                                }
                    } else {
                        echo '<option value=""> Select Establishment </option>';
                    }

            } else {
                echo '<option value=""> Select Establishment</option>';
            }
        } else {
            echo '<option value=""> Select Establishment</option>';
        }
    }





    function bypass_hc()
    {
        //  $ans=$this->common_model->bypass_hc();
        $subordinate_court_details = $this->Get_details_model->get_subordinate_court_details($_SESSION['efiling_details']['registration_id']);
        $ans=$this->common_model->bypass_hc($_SESSION['efiling_details']['registration_id'],$subordinate_court_details);

        if($ans==0)
        {
            // transaction completed
            echo "2@ Proceeding to Next Stage";
            //redirect('uploadDocuments') ;
        }
        else
        {
            echo "1@ Some Error. Please Try again";
        }
    }

    function get_high_court() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        unset($_SESSION['search_case_data_save']);
        $high_courts = $this->Dropdown_list_model->high_courts();
        $dropDownOptions = '<option value="">Select High Court</option>';
        foreach ($high_courts as $courts) {
            $dropDownOptions .= '<option value="' . escape_data(url_encryption($courts->hc_id . "##" . $courts->name )) . '">' . escape_data(strtoupper($courts->name)) . '</option>';
        }
        echo $dropDownOptions;
    }
 
    function get_hc_bench_list() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $hc = explode("##", url_decryption(escape_data($_POST['high_court_id'])));
        $high_court_id = $hc[0];
        $court_type = $_POST['court_type'];
        $dropDownOptions = '<option value="">Select High Court Bench</option>';
        if (!empty($high_court_id) && !empty($court_type)) {
            $hc_benches = $this->Dropdown_list_model->hc_bench($high_court_id);
            foreach ($hc_benches as $bench) {
                $dropDownOptions .= '<option value="' . escape_data(url_encryption($bench['bench_id'] . "##" . $bench['name']. "##" . $bench['est_code'] )) . '">' . escape_data(strtoupper($bench['name'])) . '</option>';
            }
        }
        echo $dropDownOptions;
    }

    function get_hc_case_type_list() {
        $hc_bench_id = explode("##", url_decryption(escape_data($_POST['hc_bench_id'])));
        $est_code = isset($hc_bench_id[2]) ? $hc_bench_id[2] : '';
        $court_type = $_POST['court_type'];
        $dropDownOptions='<option value="">Select Case Type</option>';
        if (!empty($est_code) && !empty($court_type)) {
            $hc_case_types = $this->Dropdown_list_model->hc_case_types($est_code);
            foreach ($hc_case_types as $hc_case_type) {
                $dropDownOptions .= '<option value="' . escape_data(url_encryption($hc_case_type->case_type . "##" . $hc_case_type->type_name )) . '">' . escape_data(strtoupper($hc_case_type->type_name)) . '</option>';
            }
        }
        $dropDownOptions .= '<option value="' . escape_data(url_encryption( "0##NOT IN LIST" )) . '">NOT IN LIST</option>';
        echo $dropDownOptions;
    }

    public function search_case_details() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, JAIL_SUPERINTENDENT);
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('/'));
        }
        unset($_SESSION['search_case_data_save']);
        $validation_rules = [];
        $validation_rules = [
            "selected_court" => [
                "label" => "Court Type",
                "rules" => "required|trim|in_list[1,2,3,4,5]"
            ],
        ];
        $selected_court_type = $_POST['selected_court'];

        if ($selected_court_type == '1') { //---High Court
            if(isset($_POST['cnr']) && trim($_POST['cnr'])==''){
                $case_type_array = url_decryption($_POST['case_type_id']);
                $case_type_ids = explode('##', $case_type_array);
                if ($case_type_ids[0]=='0'){
                    $validation_rules = [
                        "case_type_name" => [
                            "label" => "Case Type Name",
                            "rules" => "required|trim"
                        ],
                    ];
                }
                $validation_rules = [
                    "high_court_id" => [
                        "label" => "High Court",
                        "rules" => "required|trim"
                    ],
                    "hc_court_bench" => [
                        "label" => "High Court Bench",
                        "rules" => "required|trim"
                    ],
                    "case_type_id" => [
                        "label" => "Case Type",
                        "rules" => "required|trim"
                    ],
                    "case_number" => [
                        "label" => "Case Number",
                        "rules" => "required|trim|numeric"
                    ],
                    "case_year" => [
                        "label" => "Case Year",
                        "rules" => "required|trim"
                    ],
                ];
            }
            else{
                $validation_rules = [
                    "cnr" => [
                        "label" => "CNR Number",
                        "rules" => "required|trim|exact_length[16]|alpha_numeric|regex_match[/^[A-Za-z]{4}[0-9]{12}$/]"
                    ],
                ];
            }


        } elseif ($selected_court_type == '4') { //---Supreme Court
            $case_type_array = url_decryption($_POST['case_type_id']);
            $case_type_ids = explode('#$', $case_type_array);
            $validation_rules = [
                "case_type_id" => [
                    "label" => "Case Type",
                    "rules" => "required|trim"
                ],
                "case_number" => [
                    "label" => "Case Number",
                    "rules" => "required|trim|numeric"
                ],
                "case_year" => [
                    "label" => "Case Year",
                    "rules" => "required|trim"
                ],
            ];

        } elseif ($selected_court_type == '3') { //---District Court
            if(isset($_POST['cnr']) && trim($_POST['cnr'])==''){
                $case_type_array = url_decryption($_POST['case_type_id']);
                $case_type_ids = explode('##', $case_type_array);
                $validation_rules = [
                    "estab_id" => [
                        "label" => "Establishment",
                        "rules" => "required|trim"
                    ],
                    "case_type_id" => [
                        "label" => "Case Type",
                        "rules" => "required|trim"
                    ],
                    "case_number" => [
                        "label" => "Case Number",
                        "rules" => "required|trim|numeric"
                    ],
                    "case_year" => [
                        "label" => "Case Year",
                        "rules" => "required|trim"
                    ],
                ];
                if ($case_type_ids[0] == '0') {
                    $validation_rules["case_type_name"] = [
                        "label" => "Case Type Name",
                        "rules" => "required"
                    ];
                }
            }
            else{
                $validation_rules = [
                    "cnr" => [
                        "label" => "CNR Number",
                        "rules" => "required|trim|exact_length[16]|alpha_numeric|regex_match[/^[A-Za-z]{4}[0-9]{12}$/]"
                    ],
                ];
            }
        } elseif ($selected_court_type == '5') { //---Agency Court
            $agency_case_type = explode('##', url_decryption($_POST['case_type_id']));
            if ($agency_case_type[0]=='0'){
                $validation_rules = [
                    "case_type_name" => [
                        "label" => "Case Type Name",
                        "rules" => "required|trim"
                    ],
                ];
            }
        }
        $this->validation->setRules($validation_rules);
        // $this->form_validation->set_error_delimiters('<br/>', '');
        if ($this->validation->withRequest($this->request)->run() === FALSE) {
         
            echo '3@@@';
            echo $this->validation->getError('radio_selected_court') . $this->validation->getError('hc_court') . $this->validation->getError('case_type_id'). $this->validation->getError('case_type_name') . '<br />' . $this->validation->getError('case_number') . '<br />' . $this->validation->getError('case_year') . '<br />' . $this->validation->getError('cnr');
            exit(0);
        } else {

            if ($selected_court_type == '1') { //---High Court

                if(empty(trim($_POST['cnr'])) && !empty($_POST['high_court_id'])){

                    $hc = explode("##", url_decryption(escape_data($_POST['high_court_id'])));
                    $high_court_id = $hc[0];

                    $case_number = $_POST['case_number'];
                    $case_year = url_decryption($_POST['case_year']);

                    $case_type_id_array = explode('##', url_decryption($_POST['case_type_id']));
                    $case_type_id = $case_type_id_array[0];

                    $hc_bench_array = explode('##', url_decryption($_POST['hc_court_bench']));
                    $est_code = $hc_bench_array[2];
                    $string = $this->highcourt_webservices->by_case_no($est_code, $case_type_id, $case_number, $case_year);
                    $case_data_temp = json_decode($string, true);
                    $cino = !empty($case_data_temp) ? $case_data_temp['casenos']['case1']['cino'] : '';
                }

                if(!empty(trim($_POST['cnr']))) 

                    $cino = trim(strtoupper($_POST['cnr']));


                if ((!empty($high_court_id) && !empty($est_code) && !empty($case_type_id) && !empty($case_number) && !empty($case_year) ) || !empty($cino)) {
                    $case_data_array = null;

                    if(!empty($cino)){

                        $string = $this->highcourt_webservices->by_cino($cino);
                        $case_data_array = json_decode($string, true);
                        if(!empty($case_data_array)){
                            $case_data[0] = $case_data_array;
                            //var_dump($case_data_array);
                            $highcourt_name = $case_data[0]['court_est_name'];
                            $case_data[0]['case_no']='0000'.str_pad($case_data[0]['reg_no'],4,"0",STR_PAD_LEFT).$case_data[0]['reg_year'];
                        }else{
                            echo '1@@@' . htmlentities('Case data not found !', ENT_QUOTES);

                        exit(0);
                        }

                        
                    }
                    if (isset($case_data) && !empty($case_data) && isset($case_data[0]['pet_name'])) {

                        $this->case_details_table($highcourt_name, $case_data[0], 'CASE');
                    } else {

                        echo '1@@@' . htmlentities('Case data not found !', ENT_QUOTES);

                        exit(0);
                    }
                } else {

                    echo '1@@@' . htmlentities('Invalid request !', ENT_QUOTES);
                    exit(0);
                }
            } elseif ($selected_court_type == '4') { //---Supreme Court 
                $case_type_id_array = explode('#$', url_decryption($_POST['case_type_id']));
                $case_type_id = $case_type_id_array[0];
                $case_number = $_POST['case_number'];
                $case_year = url_decryption($_POST['case_year']);

                $web_service_result = $this->efiling_webservices->get_case_details_from_SCIS(escape_data($case_type_id), escape_data($case_number), escape_data($case_year));
                if (!empty($web_service_result->message)) {
                    echo '3@@@ No Record found!';
                    exit(0);
                } elseif (!empty($web_service_result->case_details[0])) {
                    $this->diary_details_table($web_service_result->case_details[0]);
                } else {
                    echo '3@@@ Some error!';
                    exit(0);
                }
            } elseif ($selected_court_type == '3') { //---District Court
                if(empty(trim($_POST['cnr'])) && !empty($_POST['case_type_id']) && !empty($_POST['estab_id']) && !empty($_POST['case_number'])&& !empty($_POST['case_year'])){
                    $estab_id_array = explode('#$', url_decryption($_POST['estab_id']));
                    $establishment_id = $estab_id_array[0];
                    $case_type_id_array = explode('##', url_decryption($_POST['case_type_id']));
                    $case_type_id = $case_type_id_array[0];
                    $case_number = $_POST['case_number'];
                    $case_year = url_decryption($_POST['case_year']);

                    if (!empty($establishment_id) && (!empty($case_type_id) || $case_type_id == 0) && !empty($case_number) && !empty($case_year) && !empty(E_FILING_FOR_ESTABLISHMENT)) {
                        $case_result = $this->efiling_webservices->getOpenAPIcaseHistoryWebService($establishment_id, $case_type_id, $case_number, $case_year);
                        if (isset($case_result[0]) && !empty($case_result[0])) {
                            $cino = isset($case_result[0]->casenos) ? $case_result[0]->casenos->case1->cino : '';
                        }
                        else {
                            echo '1@@@' . htmlentities('Case data not found !', ENT_QUOTES);
                            exit(0);
                        }
                    } else {
                        echo '1@@@' . htmlentities('Invalid request !', ENT_QUOTES);
                        exit(0);
                    }
                }

                if(!empty(trim($_POST['cnr'])))
                    $cino = trim(strtoupper($_POST['cnr']));

                if (isset($cino) && !empty($cino) && strlen($cino)==16) {
                    $case_data = $this->efiling_webservices->getOpenAPICNRSearch($cino);
                    if(isset($case_data[0]->status) && $case_data[0]->status=='INVALID_CNR'){
                        echo '1@@@' . htmlentities('Invalid CNR Number !', ENT_QUOTES);
                        exit(0);
                    }
                    // $this->est_case_details($case_data);
                    if (!empty($case_data) && !empty($case_data[0])) {
                        $this->est_case_details($case_data);
                    } else {
                        echo '1@@@' . htmlentities('Case data not found !', ENT_QUOTES);exit(0);
                    }
                } else {
                    echo '1@@@' . htmlentities('Case data not found !', ENT_QUOTES);
                    exit(0);
                }



            }
        }

        //-----START :  SEARCH FOR HIGH COURT-------//
    }

    public function case_details_table($court_name, $case_data, $hc_info_id) {
        if (isset($case_data) && !empty($case_data)) {

            $case_no_year = $case_data['case_no'];
            $case_type_id = substr($case_no_year, 1, 3);
            $case_nums = substr($case_no_year, 4, -4);
            $case_year = substr($case_no_year, -4);

            if (!empty($case_data['date_of_decision'])) {
                $decision_date = $case_data['date_of_decision'];
                $date = date('d/m/Y', strtotime($decision_date));
                $status = 'Disposed';
            } else {
                $decision_date = NULL;
                $status = 'Pending';
                $date = '';
            }

            $dropDownOptions = '<option value=""></option>';
            if (!empty($case_data['interimorder'])) {
                foreach ($case_data['interimorder'] as $or_dates){
                    $d = date('d/m/Y', strtotime($or_dates['order_date']));
                    $dropDownOptions .= '<option value="' . escape_data(url_encryption($d . "##" . $or_dates['order_no']. "##I" )) . '">' . escape_data($d." "."I-".$or_dates['order_details']) . '</option>';
                }
            }
            if (!empty($case_data['finalorder'])) {
                foreach ($case_data['finalorder'] as $or_dates){
                    $d = date('d/m/Y', strtotime($or_dates['order_date']));
                    $dropDownOptions .= '<option value="' . escape_data(url_encryption($d . "##" . $or_dates['order_no']. "##F" )) . '">' . escape_data($d." "."F-".$or_dates['order_details']) . '</option>';
                }
            }
            $search_case_data = array(
                'cnr_num' => $case_data['cino'],
                'case_num' => $case_nums,
                'case_year' => $case_year,
                'case_type_id' => !empty($case_data['case_type_id']) ? $case_data['case_type_id'] : null,
                'case_type_name' => !empty($case_data['type_name_fil']) ? $case_data['type_name_fil'] : null,
                'pet_name' => !empty($case_data['pet_name']) ? $case_data['pet_name'] : null,
                'res_name' => !empty($case_data['res_name']) ? $case_data['res_name'] : null,
                'type_name' => !empty($case_data['type_name_fil']) ? $case_data['type_name_fil'] : '',
                'date_of_decision' => !empty($case_data['date_of_decision']) ? $case_data['date_of_decision'] : null,
                'case_status' => $status,
                'court_est_name' => !empty($case_data['court_est_name']) ? $case_data['court_est_name'] : null,
                'est_code' => !empty($case_data['est_code']) ? $case_data['est_code'] : null,
            );
            //var_dump($search_case_data);
            $sessiondata = array('search_case_data_save' => $search_case_data);
            $this->session->set($sessiondata);


            echo '2@@@<div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_content">
                        <table id="datatable-responsive" class="table table-striped custom-table first-th-left" cellspacing="0" width="100%"> 
                            <thead>
                                <tr class="success">
                                    <th>#</th>
                                     <th>Court Name</th>
                                     <th>Parties </th>
                                     <th>Case Number </th>
                                     <th>Status </th>
                                </tr>
                            </thead>
                            <tbody>';
            echo '<tr>';
            echo '<td>' . htmlentities(1, ENT_QUOTES) . '</td>';
            echo '<td>' . htmlentities(ucwords($court_name), ENT_QUOTES) . '</td>';
            echo '<td><strong>Petitioner :</strong>' . htmlentities(ucwords($case_data['pet_name']), ENT_QUOTES) . '<br><strong>Respondent :</strong>  ' . htmlentities(ucwords($case_data['res_name']), ENT_QUOTES) . '</td>';
            echo '<td>' . htmlentities($case_data['type_name_fil'], ENT_QUOTES) . ' - ' . htmlentities(ltrim($case_nums, '0'), ENT_QUOTES) . ' / ' . htmlentities($case_year, ENT_QUOTES) . '<br><strong>CNR No. :</strong>' . htmlentities(cin_preview($case_data['cino'], ENT_QUOTES)) . '</td>';
            echo '<td>' . htmlentities(ucwords($status), ENT_QUOTES) . '</td>';
            echo '</tr>';
            echo '</tbody>';
            echo '</table>@@@' . $date.'@@@'.$dropDownOptions;
        }
    }

    public function diary_details_table($case_data) {

        if (isset($case_data) && !empty($case_data)) {

            if (!empty($case_data->ord_dt)) {
                $decision_date = $case_data->ord_dt;
                $date = date('d/m/Y', strtotime($decision_date));
            } else {
                $decision_date = NULL;
                $date = '';
            }

            if ($case_data->c_status == 'D') {
                $status = 'Disposed';
            } else {
                $status = 'Pending';
            }
            $search_case_data = array(
                'case_num' => ltrim(substr($case_data->active_fil_no, -6), '0'),
                'case_year' => $case_data->active_reg_year,
                'case_type_name' => $case_data->reg_no_display,
                'pet_name' => $case_data->pet_name,
                'res_name' => $case_data->res_name,
                'type_name' => $case_data->reg_no_display,
                'date_of_decision' => $case_data->ord_dt,
                'case_status' => $status
            );

            $sessiondata = array('search_case_data_save' => $search_case_data);
            $this->session->set($sessiondata);

            echo '2@@@<div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_content">
                        <table id="datatable-responsive" class="table table-striped custom-table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr class="success">
                                    <th>#</th>
                                    <th>Diary No.</th>
                                    <th>Cause Title</th>
                                    <th>Case Type </th>
                                    <th>Status </th>
                                </tr>
                            </thead>
                            <tbody>';
            echo '<tr>';
            echo '<td>' . htmlentities(1, ENT_QUOTES) . '</td>';
            echo '<td>' . htmlentities($case_data->diary_no . $case_data->diary_year, ENT_QUOTES) . '</td>';
            echo '<td><strong>Petitioner :</strong>' . htmlentities(ucwords($case_data->pet_name), ENT_QUOTES) . '<br><strong>Respondent :</strong>  ' . htmlentities(ucwords($case_data->res_name), ENT_QUOTES) . '</td>';
            echo '<td>' . htmlentities($case_data->reg_no_display, ENT_QUOTES) . '</td>';
            echo '<td>' . htmlentities(ucwords($status), ENT_QUOTES) . '</td>';
            echo '</tr>';
            echo '</tbody>';
            echo '</table>@@@' . $date;
        }
    }

    public function est_case_details($case_data) {
        //var_dump($case_data);
        $dropDownOptions = '<option value=""></option>';
        if (!empty($case_data[0]->interimorder)) {
            foreach ($case_data[0]->interimorder as $or_dates){
                $d = date('d/m/Y', strtotime($or_dates->order_date));
                $dropDownOptions .= '<option value="' . escape_data(url_encryption($d . "##" . $or_dates->order_no. "##I" )) . '">' . escape_data($d." "."I-".$or_dates->order_details) . '</option>';
            }
        }
        if (!empty($case_data[0]->finalorder)) {
            foreach ($case_data[0]->finalorder as $or_dates){
                $d = date('d/m/Y', strtotime($or_dates->order_date));
                $dropDownOptions .= '<option value="' . escape_data(url_encryption($d . "##" . $or_dates->order_no. "##F" )) . '">' . escape_data($d." "."F-".$or_dates->order_details) . '</option>';
            }
        }

        if (!empty($case_data[0]->date_of_decision)) {
            $decision_date = $case_data[0]->date_of_decision;
            $date = date('d/m/Y', strtotime($decision_date));
            $status = 'Disposed';
        } else {
            $decision_date = NULL;
            $status = 'Pending';
            $date = '';
        }
        $search_case_data = array(
            'cnr_num' => $case_data[0]->cino,
            'case_num' => $case_data[0]->reg_no,
            'case_year' => $case_data[0]->reg_year,
            'case_type_id' => $case_data[0]->case_type_id,
            'case_type_name' => $case_data[0]->type_name,
            'pet_name' => $case_data[0]->pet_name,
            'res_name' => $case_data[0]->res_name,
            'type_name' => $case_data[0]->type_name,
            'date_of_decision' => $case_data[0]->date_of_decision,
            'case_status' => $status,
            'court_est_name' => $case_data[0]->court_est_name,
            'est_code' => $case_data[0]->est_code,
        );
        $sessiondata = array('search_case_data_save' => $search_case_data);
        $this->session->set($sessiondata);

        echo '2@@@<div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_content">
                        <table id="datatable-responsive" class="table table-striped custom-table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr class="success">
                                    <th>#</th>
                                    <th>Court Name</th>
                                    <th>Parties</th>
                                    <th>Case Number </th>
                                    <th>Status </th>
                                </tr>
                            </thead>
                            <tbody>';

        echo '<tr>';
        echo '<td>' . htmlentities(1, ENT_QUOTES) . '</td>';
        echo '<td>' . htmlentities($case_data[0]->court_est_name, ENT_QUOTES) . '</td>';
        echo '<td><strong>Petitioner :</strong>' . htmlentities(ucwords($case_data[0]->pet_name), ENT_QUOTES) . '<br><strong>Respondent :</strong>  ' . htmlentities(ucwords($case_data[0]->res_name), ENT_QUOTES) . '</td>';
        echo '<td>' . htmlentities($case_data[0]->type_name . '-' . $case_data[0]->reg_no . ' / '.$case_data[0]->reg_year, ENT_QUOTES) . '<br><strong>CNR No. :</strong>' . htmlentities(cin_preview($case_data[0]->cino), ENT_QUOTES) . '</td>';
        echo '<td>' . htmlentities(ucwords($status), ENT_QUOTES) . '</td>';
        echo '</tr>';
        echo '</tbody>';
        echo '</table></div></div>';
        echo '</table>@@@' . $date.'@@@'.$dropDownOptions;
    }

    public function get_case_type_list() {
        $hc = explode("##", url_decryption(escape_data($_POST['high_court_id'])));
        $high_court_id = $hc[0];
        $court_type = $_POST['court_type'];
        if (!empty($high_court_id) && !empty($court_type)) {

            $get_result = $this->get_establishment_code($high_court_id, $court_type);

            $code = explode('@@@', $get_result);
            $national_code = $code[0];
            $state_code = $code[1];
            $result = $this->efiling_webservices->getCaseType($national_code, $court_type, $state_code);
            if (count($result)) {
                echo '<option value=""> Select Case Type</option>';
                foreach ($result as $dataActs) {

                    if (!empty($dataActs->PETITIONER)) {
                        $_SESSION['efiling_for_details']['case_type_pet_title'] = (string) $dataActs->PETITIONER;
                    }
                    if (!empty($dataActs->RESPONDENT)) {
                        $_SESSION['efiling_for_details']['case_type_res_title'] = (string) $dataActs->RESPONDENT;
                    }

                    $value = (string) $dataActs->CASETYPE;
                    if ($dataActs->FULLFORM != '') {
                        $full_form = '(' . htmlentities(strtoupper((string) $dataActs->FULLFORM), ENT_QUOTES) . ')';
                    } else {
                        $full_form = '';
                    }
                    echo '<option value="' . htmlentities(url_encryption(trim($value . '#$' . strtoupper((string) $dataActs->TYPENAME) . $full_form . '#$' . $dataActs->MACP), ENT_QUOTES)) . '">' . '' . htmlentities(strtoupper((string) $dataActs->TYPENAME), ENT_QUOTES) . $full_form . '</option>';
                }
            } else {
                echo '<option value=""> Select Case Type</option>';
            }
        } else {
            echo '<option value=""> Select Case Type</option>';
        }
    }

    function get_establishment_code($est_id, $court_type) {

        if (isset($est_id) && !empty($est_id) && isset($court_type) && !empty($court_type)) {
            if ($court_type == E_FILING_FOR_HIGHCOURT) {
                $result = $this->Dropdown_list_model->get_high_court_code($est_id);
                if (!empty($result)) {
                    return (string) $result[0]->hc_code . '@@@' . (string) $result[0]->state_code;
                }
            } elseif ($court_type == E_FILING_FOR_ESTABLISHMENT) {

                $result = $this->Dropdown_list_model->get_eshtablishment_code($est_id);
                if (!empty($result)) {
                    return (string) $result[0]->est_code . '@@@' . (string) $result[0]->state_code;
                }
            }
        }
    }

    /* FIR Functions  */
    function get_icmis_state_list()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        unset($_SESSION['search_case_data_save']);
        $state_list=$this->Dropdown_list_model->get_states_list();
        $dropDownOptions = '<option value="" selected="true" disabled="disabled">Select State</option>';

        foreach ($state_list as $dataRes) {

            $dropDownOptions .= '<option value="' . escape_data(url_encryption($dataRes->cmis_state_id . '#$' . $dataRes->agency_state)) . '">' . escape_data(strtoupper($dataRes->agency_state)) . '</option>';
        }

        echo $dropDownOptions;
    }

    public function get_icmis_district_list() {

        $state_array = explode('#$', url_decryption(escape_data($_POST['state_id'])));
        $state_id = $state_array[0];
        if (isset($state_id) && preg_match("/^[0-9]*$/", $state_id) && strlen($state_id) <= INTEGER_FIELD_LENGTH) {
            if (!empty($state_id)) {
                $result = $this->Dropdown_list_model->get_districts_list($state_id);
                if (count($result)) {
                    echo '<option value="" selected="true" disabled="disabled"> Select District</option>';
                   // echo '<option value="0"> All </option>';
                    foreach ($result as $dataRes) {
                            echo '<option data-id="'.$dataRes->id_no.'"  value="' . htmlentities(url_encryption($dataRes->id_no . '#$' . $dataRes->name), ENT_QUOTES) . '">' . htmlentities(strtoupper($dataRes->name), ENT_QUOTES) . '</option>';
                    }
                } else {
                    echo '<option value="" selected="true" disabled="disabled"> Select District </option>';
                }
            } else {
                echo '<option value="" selected="true" disabled="disabled"> Select District</option>';
            }
        } else {
            echo '<option value="" selected="true" disabled="disabled"> Select District</option>';
        }
    }

    public function get_police_station_list() {

        $state_array = explode('#$', url_decryption(escape_data($_POST['state_id'])));
        $state_id = $state_array[0];
        $district_array = explode('#$', url_decryption(escape_data($_POST['get_distt_id'])));
        $district_id = $district_array[0];
        if (isset($state_id) ) {

            if (!empty($state_id)) {
                
                $result = $this->efiling_webservices->get_police_station_list($state_id,$district_id);
                // pr($$result['policeStation']);
                if (!empty($result['policeStation']) && count($result['policeStation'])>0 ) {
                    echo '<option value="" selected="true" disabled="disabled"> Select Police Station </option>';
                    if(isset($result['policeStation'])){
                        foreach($result['policeStation'] as $dataRes)
                        {
                            echo '<option  value="' . htmlentities(url_encryption($dataRes['police_station_code'] . '#$' . $dataRes['police_station_name']), ENT_QUOTES) . '">' . htmlentities(strtoupper($dataRes['police_station_name'].'-----'.(isset($dataRes['police_district']) ? $dataRes['police_district'] : '')), ENT_QUOTES) . '</option>';
                        }
                    }
                    echo '<option value="" > Not in List </option>';

                } else {

                    echo '<option value="" selected="true" disabled="disabled"> Select Police Station </option>';
                    echo '<option value="" > Not in List </option>';

                }

            } else {
                echo '<option value="" selected="true" disabled="disabled"> Select Police Station   </option>';
                echo '<option value="" > Not in List </option>';
            }
        } else {
            echo '<option value="" selected="true" disabled="disabled"> Select Police Station </option>';
            echo '<option value="" > Not in List </option>';
        }
    }

    /*--Jail Function start-- */
    public function get_jail_list() {

        $state_array = explode('#$', url_decryption(escape_data($_POST['state_id'])));
        $state_id = $state_array[0];
        $district_array = explode('#$', url_decryption(escape_data($_POST['get_distt_id'])));
        $district_id = $district_array[0];
        if (isset($state_id) ) {

            if (!empty($state_id)) {
                $result = $this->efiling_webservices->get_police_station_list($state_id,$district_id);
                var_dump($result);
                if (count($result)) {
                    echo '<option value="" selected="true" disabled="disabled"> Select Jail </option>';
                    foreach($result['policeStation'] as $dataRes)
                    {
                        echo '<option  value="' . htmlentities(url_encryption($dataRes['Loc_Id']), ENT_QUOTES) . '">' . htmlentities(strtoupper($dataRes['Loc_Det'].'-----'.$dataRes['jail_name']), ENT_QUOTES) . '</option>';
                    }
                } else {
                    echo '<option value="" selected="true" disabled="disabled"> Select Jail </option>';
                }

            } else {
                echo '<option value="" selected="true" disabled="disabled"> Select Jail</option>';
            }
        } else {
            echo '<option value="" selected="true" disabled="disabled"> Select Jail</option>';
        }
    }

    /*--Jail Function end-- */

    function get_sci_case_type() {
        unset($_SESSION['search_case_data_save']);

        $sc_case_type = $this->Dropdown_list_model->get_sci_case_type();

        $dropDownOptions = '<option value="">Select Case Type</option>';

        foreach ($sc_case_type as $dataRes) {

            $dropDownOptions .= '<option value="' . escape_data(url_encryption($dataRes->casecode . '#$' . $dataRes->casename)) . '">' . escape_data(strtoupper($dataRes->casename)) . '</option>';
        }

        echo $dropDownOptions;
    }

    public function get_state_list() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        unset($_SESSION['search_case_data_save']);
        $params=array('type'=>1,);
        $state_list = $this->common_model->getSubordinateCourtData($params);
        if (count($state_list)) {
            echo '<option value=""> Select State</option>';
                foreach ($state_list as $state) {
                    echo '<option  value="' . escape_data(url_encryption($state['state_code'] . '#$' . $state['state_name'] . '#$' . $state['state_name'])) . '">' . escape_data(strtoupper($state['state_name'])) . '</option>';
                }
        } else {
            echo '<option value=""> Select State </option>';
        }
    }

    public function get_district_list() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }

       $state_array = explode('#$', url_decryption(escape_data($_POST['state_id'])));
        $state_id = $state_array[0];
        if (isset($state_id) && preg_match("/^[0-9]*$/", $state_id) && strlen($state_id) <= INTEGER_FIELD_LENGTH) {
            if (!empty($state_id)) {
                $params=array('type'=>2,'state_code'=>$state_id,);
                $result = $this->common_model->getSubordinateCourtData($params);
                    if (count($result)) {
                        echo '<option value=""> Select District </option>';
                            foreach ($result as $district) {
                                echo '<option  value="' . htmlentities(url_encryption($district['district_code'] . '#$' . $district['district_name']), ENT_QUOTES) . '">' . htmlentities(strtoupper($district['district_name']), ENT_QUOTES) . '</option>';
                            }

                    } else {
                        echo '<option value=""> Select District </option>';
                    }

            } else {
                echo '<option value=""> Select District</option>';
            }
        } else {
            echo '<option value=""> Select District</option>';
        }
    }

    

    function OpenAPIcase_type_list() {

        $est_id = explode("#$", url_decryption(escape_data($_POST['est_code'])));
         $est_code = $est_id[0];
        if (!empty($est_code)) {
            $params=array('type'=>4,'est_code'=>$est_code,);
            $result = $this->common_model->getSubordinateCourtData($params);
            if (count($result)) {
                echo '<option value=""> Select Case Type</option>';
                   foreach ($result as $caseType) {
                        echo '<option value="' . htmlentities(url_encryption(trim($caseType['case_type'] . '##' . strtoupper($caseType['type_name'])), ENT_QUOTES)) . '">' . '' . htmlentities(strtoupper($caseType['type_name']), ENT_QUOTES) . '</option>';
                    }
                echo '<option value="' . htmlentities(url_encryption(trim('0##NOT IN LIST'), ENT_QUOTES)) . '">NOT IN LIST</option> ';
            } else {
                echo '<option value=""> Select Case Type</option><option value="' . htmlentities(url_encryption(trim('0##NOT IN LIST'), ENT_QUOTES)) . '">NOT IN LIST</option> ';
            }
        } else {
            echo '<option value=""> Select Case Type</option><option value="' . htmlentities(url_encryption(trim('0##NOT IN LIST'), ENT_QUOTES)) . '">NOT IN LIST</option> ';
        }
    }

    function get_state_agency_list() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $hc = explode("#$", url_decryption(escape_data($_POST['agency_state_id'])));
        $state_id = $hc[0];
        $court_type = $_POST['court_type'];
        $dropDownOptions = '<option value="">Select Agency</option>';
        if (!empty($state_id) && !empty($court_type)) {
            $agencies = $this->Dropdown_list_model->icmis_state_agencies($state_id, $court_type);
            foreach ($agencies as $agency) {
                $dropDownOptions .= '<option value="' . escape_data(url_encryption($agency['id'] . "##" . $agency['agency_name']. "##" . $agency['short_agency_name'] )) . '">' . escape_data(strtoupper($agency['short_agency_name'])).' - '.escape_data(strtoupper($agency['agency_name'])) . '</option>';
            }
        }
        echo $dropDownOptions;
    }

    function get_state_agency_case_types() {
        $hc = explode("##", url_decryption(escape_data($_POST['agency_id'])));
        $agency_id = $hc[0];
        $court_type = $_POST['court_type'];
        $dropDownOptions = '<option value="">Select Agency Case Type</option>';
        if (!empty($agency_id) && !empty($court_type)) {
            $case_types = $this->Dropdown_list_model->icmis_state_agencies_case_types($agency_id, $court_type);
            foreach ($case_types as $case_type) {
                $dropDownOptions .= '<option value="' . escape_data(url_encryption($case_type['case_type'] . "##" . $case_type['type_name'] )) . '">' .escape_data(strtoupper($case_type['type_name'])) . '</option>';
            }
            $dropDownOptions .= '<option value="' . escape_data(url_encryption( "0##NOT IN LIST")) . '">NOT IN LIST</option>';
        }
        echo $dropDownOptions;
    }

    public function get_police_station_fir() {
        $state_array = explode('#$', url_decryption(escape_data($_POST['fir_state'])));
        $state_id = $state_array[0];
        $district_array = explode('#$', url_decryption(escape_data($_POST['fir_district'])));
        $district_id = $district_array[0];
        if (isset($state_id) ) {
            if (!empty($state_id)) {
                $result = $this->Dropdown_list_model->getPoliceStationList($state_id,$district_id);
                if (count($result)) {
                    echo '<option value="" selected="true" disabled="disabled"> Select Police Station </option>';
                    foreach($result as $k=>$dataRes)
                    {
                        echo '<option  value="' . htmlentities(url_encryption($dataRes->policestncd . '#$' . $dataRes->policestndesc), ENT_QUOTES) . '">' . htmlentities(strtoupper($dataRes->policestndesc), ENT_QUOTES) . '</option>';
                    }
                } else {
                    echo '<option value="" selected="true" disabled="disabled"> Select Police Station </option>';
                }

            } else {
                echo '<option value="" selected="true" disabled="disabled"> Select Police Station</option>';
            }
        } else {
            echo '<option value="" selected="true" disabled="disabled"> Select Police Station</option>';
        }
    }

}
