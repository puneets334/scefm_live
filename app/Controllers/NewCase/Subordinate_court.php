<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Subordinate_court extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('common/common_model');
        $this->load->model('newcase/New_case_model');
        $this->load->model('newcase/Dropdown_list_model');
        $this->load->model('newcase/Get_details_model');
        $this->load->library('webservices/highcourt_webservices');
        /*Changes done on 04 September 2020*/
        $this->load->model('newcase/DeleteSubordinateCourt_model');
    }

    public function index() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('admindashboard');
            exit(0);
        }
        if (check_party() !=true) {  //func added on 15 JUN 2021
            log_message('CUSTOM', "Please enter every party details before moving to further tabs.");
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please enter every party details before moving to further tabs.</div>');
            redirect('newcase/extra_party');
        }
        //$stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, E_REJECTED_STAGE);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            redirect('newcase/view');
            exit(0);
        }

        $data['sc_case_type'] = $this->Dropdown_list_model->get_sci_case_type();
        $data['main_subject_cat'] = $this->Dropdown_list_model->get_main_subject_category();
        $criminal_case=0;
        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {

            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $data['subordinate_court_details'] = $this->Get_details_model->get_subordinate_court_details($registration_id);
            $this->Get_details_model->get_case_table_ids($registration_id);
            $casedetails=$this->Get_details_model->get_new_case_details($registration_id);
            $sc_casetype=$casedetails[0]['sc_case_type_id'];
            $data['sc_case_typeId_div_hide']=$sc_casetype;
            $criminal_case_type_id=array(10,12,14,26,2,28,6,4,8,18,20,29,16,33,35,36,41);
            if(in_array($sc_casetype,$criminal_case_type_id))
                $criminal_case=1;
        }
        $caseData = array();
        $caseData['court_type'] = !empty($casedetails[0]['court_type']) ? $casedetails[0]['court_type'] : NULL;
        $data['hc_value'] ='';
        $data['hc_bench_value'] ='';
        $data['state_value'] ='';
        $data['district_value'] ='';
        $data['agency_state_value'] ='';
        $data['agency_name_value'] ='';
        if(isset($caseData['court_type']) && !empty( $caseData['court_type'])){
            $court_type =  (int)$caseData['court_type'];
            $params = array();
            switch($court_type){
                case 1:
                    $estab_id = !empty($casedetails[0]['estab_id']) ? $casedetails[0]['estab_id'] : NULL; // high court estab_id
                    $estab_code = !empty($casedetails[0]['estab_code']) ? $casedetails[0]['estab_code'] : NULL; //bench estab_code
                    if(isset($estab_id) && !empty($estab_id) && isset($estab_code) && !empty($estab_code)){
                        $params['hc_id'] = $estab_id;
                        $params['est_code'] = $estab_code;
                        $highcourtData = $this->common_model->getHighCourtDetailsByHcIdAndEstabCode($params);
                        if(isset($highcourtData) && !empty($highcourtData)){
                            $data['hc_value'] = escape_data(url_encryption($highcourtData[0]->hc_id . "##" . $highcourtData[0]->hname ));
                            $data['hc_bench_value'] = escape_data(url_encryption($highcourtData[0]->bench_id . "##" . $highcourtData[0]->bname. "##" . $highcourtData[0]->est_code)) ;
                        }
                    }
                    break;
                case 3:
                    $state_id = !empty($casedetails[0]['state_id']) ? $casedetails[0]['state_id'] : NULL;
                    $district_id = !empty($casedetails[0]['district_id']) ? $casedetails[0]['district_id'] : NULL;
                    $arr = array();
                    $arr['state_id'] = $state_id;
                    $arr['district_id'] = $district_id;
                    $arr['court_type'] = $court_type;
                    $districtData = $this->common_model->getDistrictDataByCourtTypeAndStateIdDistrictId($arr);
                    if(isset($districtData) && !empty($districtData)){
                        $data['state_value'] = escape_data(url_encryption($districtData[0]->state_code . '#$' . $districtData[0]->state_name . '#$' . $districtData[0]->state_name));
                        $data['district_value'] = escape_data(url_encryption($districtData[0]->district_code . '#$' . $districtData[0]->district_name));
                    }
                    break;
                case 4:
                    break;
                case 5:
                    $state_id = !empty($casedetails[0]['state_id']) ? $casedetails[0]['state_id'] : NULL;
                    $estab_id = !empty($casedetails[0]['estab_id']) ? $casedetails[0]['estab_id'] : NULL;
                    $agencyArr = array();
                    $agencyArr['state_id'] = $state_id;
                    $agencyArr['estab_id'] = $estab_id;
                    $agencyData = $this->common_model->getStateAgencyDetailsByStateIdAndEstabId($agencyArr);
                    if(isset($agencyData) && !empty($agencyData)){
                        $data['agency_state_value'] = url_encryption($agencyData[0]->cmis_state_id . '#$' . $agencyData[0]->agency_state);
                        $data['agency_name_value']  = url_encryption($agencyData[0]->id . "##" . $agencyData[0]->agency_name. "##" . $agencyData[0]->short_agency_name );
                    }
                    break;
                default:
            }
        }
        //get total is_dead_minor
        $params = array();
        $params['registration_id'] = !empty($_SESSION['efiling_details']['registration_id']) ? $_SESSION['efiling_details']['registration_id'] : NULL;
        $params['is_dead_minor'] = true;
        $params['is_deleted'] = 'false';
        $params['is_dead_file_status'] ='false';
        $params['total'] =1;
        $isdeaddata = $this->Get_details_model->getTotalIsDeadMinor($params);
        if(isset($isdeaddata[0]->total) && !empty($isdeaddata[0]->total)){
            $total = $isdeaddata[0]->total;
            log_message('CUSTOM', "Please fill '.$total.' remaining dead/minor party details");
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please fill '.$total.' remaining dead/minor party details</div>');
            redirect('newcase/lr_party');
            exit(0);
        }
        $data['caseData'] = $caseData;
        $data['criminal_case']=$criminal_case;
        $data['sc_casetypearr']=$sc_casetype;
        $this->load->view('templates/header');
        $this->load->view('newcase/new_case_view', $data);
        $this->load->view('templates/footer');
    }

    public function update_subordinate_court_details() {

        $earlierCourtArr=url_decryption($_POST['earlierCourtArr']);
        $earlierCourtData=explode('@@@',$earlierCourtArr);
        $id=$earlierCourtData[0];
        $registration_id=$earlierCourtData[1];
        $court_type=$earlierCourtData[2];
        $case_type_id=$earlierCourtData[3];
        $casetype_name=$earlierCourtData[4];

        if (!empty($registration_id) && $registration_id !=null && !empty($id) && $id !=null) {

            $subordinate_court_details = $this->Get_details_model->get_subordinate_court_details($registration_id,$id);

            $caseData = array();
            $caseData['court_type'] =$court_type;
            if(!empty($court_type)){
                $court_type =  (int)$court_type;
                $params = array();
                switch($court_type){
                    case 1: //High Court
                        $est_code = $subordinate_court_details[0]['estab_code']; //bench estab_code
                        if(isset($est_code) && !empty($est_code)){
                            $data['case_type_list'] = $this->Dropdown_list_model->hc_case_types($est_code);
                        }
                        break;
                    case 3: //District Court or Lower Court
                        $est_code = $subordinate_court_details[0]['estab_code'];
                        if (!empty($est_code)) {
                            $params=array('type'=>4,'est_code'=>$est_code,);
                            $data['case_type_list'] = $this->common_model->getSubordinateCourtData($params);
                        }
                        break;
                    case 4: //Supreme Court
                        $data['case_type_list'] = $this->Dropdown_list_model->get_sci_case_type();
                        break;
                    case 5: //State Agency
                        $agency_id = $subordinate_court_details[0]['estab_id'];
                        if (!empty($agency_id) && !empty($court_type)) {
                            $data['case_type_list'] = $this->Dropdown_list_model->icmis_state_agencies_case_types($agency_id, $court_type);
                        }
                        break;
                    default:
                }
            }
            $data['caseData'] = $caseData;
            $data['subordinate_court_details']=$subordinate_court_details;
            $this->load->view('newcase/earlier_court_updateModal', $data);
        }else{
            echo 'Some error ! Please Try again';
        }
    }

    public function update_subordinate_court() {
        //echo 'under the process';
        $id=url_decryption($_POST['earlier_court_id']);
        $registration_id=url_decryption($_POST['registration_id']);
        $radio_selected_court=$_POST['radio_selected_court'];
        if ($_POST['radio_selected_court'] == '1') {

            $case_type_ids = explode('##', url_decryption($_POST['case_type_id']));
            $case_type_name=$_POST['hc_case_type_name'];

        } elseif ($_POST['radio_selected_court'] == '3') {

            $case_type_ids = explode('##', url_decryption($_POST['dc_case_type_id']));
            $case_type_name=$_POST['dc_case_type_name'];

        } elseif ($_POST['radio_selected_court'] == '4') {

            $case_type_ids = explode('#$', url_decryption($_POST['sci_case_type_id']));
            $case_type_name=$_POST['sci_case_type_name'];

        } else if($_POST['radio_selected_court'] == '5'){

            $case_type_ids = explode('##', url_decryption($_POST['agency_case_type_id']));
            $case_type_name=$_POST['agency_case_type_name'];

        }

        if ($case_type_ids[0]=='0'){
            $case_type_id=$case_type_ids[0];
            $case_type_name=$case_type_name;
        }else{
            $case_type_id=$case_type_ids[0];
            $case_type_name=$case_type_ids[1];
        }

        $update_case_details = array(
            'case_type_id' =>$case_type_id,
            'casetype_name' =>$case_type_name,

        );

        //echo '<pre>';print_r($update_case_details);echo '</pre>';exit();

        if (isset($registration_id) && !empty($registration_id) && !empty($id) && $id !=null) {
            $status = $this->New_case_model->update_subordinate_court_info_newcase($id,$registration_id, $update_case_details);
            if ($status) {
                reset_affirmation($registration_id);
                echo '2@@@' . htmlentities('Earlier Court Update successfully!', ENT_QUOTES);
            } else {
                echo '1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
            }
        }

    }
    public function add_subordinate_court_details() {


        $registration_id = $_SESSION['efiling_details']['registration_id'];

        if (!empty($_SESSION['search_case_data_save']['date_of_decision'])) {
            $decision_date = $_SESSION['search_case_data_save']['date_of_decision'];
            $status = TRUE;
        } else {
            $decision_date = NULL;
            $status = FALSE;
        }
        $Sc_Case_TypeId=$_POST['Sc_Case_TypeId'];

        if($Sc_Case_TypeId==7 || $Sc_Case_TypeId==8){
            $Is_Judgment_Challenged = '1' ;
            $Judgment_Type = '';

        }else{

            if (!empty($_POST['order_date'])) {
                if (!preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/", $_POST['order_date'])) {
                    echo '1@@@' . htmlentities('Invalid Date of birth date format !', ENT_QUOTES);
                    exit(0);
                }
                $date = explode('/', $_POST['order_date']);
                $decision_date = $date[2] . '-' . $date[1] . '-' . $date[0];
            } else {
                $decision_date = $_SESSION['search_case_data_save']['date_of_decision'];
            }
            $Is_Judgment_Challenged = $_POST['judgement_challenged'];
            $Judgment_Type = $_POST['judgement_type'];
        }

        if ($_POST['radio_selected_court'] == '1') {

            $case_type_array = url_decryption($_POST['case_type_id']);
            $case_type_ids = explode('##', $case_type_array);
            $case_type_name=$_POST['hc_case_type_name'];
            $case_number=$_POST['hc_case_number'];
            $case_year=url_decryption($_POST['hc_case_year']);
            $cnr_number=$_POST['cnr'];
            $hc = explode('##', url_decryption($_POST['hc_court']));
            $bench = explode('##', url_decryption($_POST['hc_court_bench']));
            $estabid = $hc[0];
            $estab_code = $bench[2];
            $estab_name = $hc[1];
            $court_name = $estab_name . " High Court";
            $state_id = 0;
            $state_name = 0;
            $district_id = 0;
        } elseif ($_POST['radio_selected_court'] == '3') {

            $case_type_array = url_decryption($_POST['dc_case_type_id']);
            $case_type_ids = explode('##', $case_type_array);
            $case_type_name=$_POST['dc_case_type_name'];
            $cnr_number=$_POST['cnr'];
            $state = explode('#$', url_decryption($_POST['state']));
            $case_number=$_POST['dc_case_number'];
            $case_year=url_decryption($_POST['dc_case_year']);
            $state_id = $state[0];
            $state_name = $state[1];

            $district = explode('#$', url_decryption($_POST['district']));
            $district_id = $district[0];
            $district_name = $district[1];

            $establishment = explode('#$', url_decryption($_POST['establishment']));
            $estab_code = $establishment[0];
            $estab_name = $establishment[1];
            $estabid = $establishment[2];
        } elseif ($_POST['radio_selected_court'] == '4') {

            $case_type_array = url_decryption($_POST['sci_case_type_id']);
            $case_type_ids = explode('#$', $case_type_array);
            $district_id = 0;
            $case_number=$_POST['sci_case_number'];
            $case_year=url_decryption($_POST['sci_case_year']);
        }


        if ($case_type_ids[0]=='0'){
            $case_type_id=$case_type_ids[0];
            $case_type_name=$case_type_name;
        }else{
            $case_type_id=$case_type_ids[0];
            $case_type_name=$case_type_ids[1];
        }

        $case_details = array(
            'registration_id' => $registration_id,
            'court_type' => $_POST['radio_selected_court'],
            'state_id' => !empty($state_id)?$state_id:0,
            'district_id' => !empty($district_id)?$district_id:0,
            'agency_code' => 0,
            'pet_name' => $_SESSION['search_case_data_save']['pet_name'],
            'res_name' => $_SESSION['search_case_data_save']['res_name'],
            /*'case_type_id' => (isset($case_type_ids[0]) && !empty($case_type_ids[0]))?$case_type_ids[0]:$_SESSION['search_case_data_save']['case_type_id'],
            'case_num' => $_SESSION['search_case_data_save']['case_num'],
            'case_year' => $_SESSION['search_case_data_save']['case_year'],
            'cnr_num' => $_SESSION['search_case_data_save']['cnr_num'],*/
            //'case_type_id' => (isset($case_type_ids[0]) && !empty($case_type_ids[0]))?$case_type_ids[0]:$_SESSION['search_case_data_save']['case_type_id'],
            'case_type_id' => (isset($case_type_id) && !empty($case_type_id))?$case_type_id:$_SESSION['search_case_data_save']['case_type_id'],
            'case_num' => !empty($_SESSION['search_case_data_save']['case_num'])?$_SESSION['search_case_data_save']['case_num']:$case_number,
            'case_year' => !empty($_SESSION['search_case_data_save']['case_year'])?$_SESSION['search_case_data_save']['case_year']:$case_year,
            'cnr_num' => !empty($_SESSION['search_case_data_save']['cnr_num'])?$_SESSION['search_case_data_save']['cnr_num']:$cnr_number,
            'impugned_order_date' => $decision_date,
            'is_judgment_challenged' => $Is_Judgment_Challenged,
            'judgment_type' => $Judgment_Type,
            'status' => $status,
            // 'decision_date' => $decision_date,
            //'casetype_name' => (isset($case_type_ids[1]) && !empty($case_type_ids[1]))?$case_type_ids[1]:$_SESSION['search_case_data_save']['case_type_name'],
            'casetype_name' => (isset($case_type_name) && !empty($case_type_name))?$case_type_name:$_SESSION['search_case_data_save']['case_type_name'],
            'estab_id' => (isset($estabid) && !empty($estabid))?$estabid:NULL,
            'estab_name' => (isset($estab_name) && !empty($estab_name))?$estab_name:$_SESSION['search_case_data_save']['court_est_name'],
            'estab_code' => (isset($estab_code) && !empty($estab_code))?$estab_code:$_SESSION['search_case_data_save']['est_code'],
            'case_status' => $_SESSION['search_case_data_save']['case_status'],
            'state_name' => $state_name,
            'district_name' => $district_name
        );
        //echo '<pre>';print_r($case_details);echo '</pre>';exit();
        // State Agency
        if($_POST['radio_selected_court'] == '5'){
            $agency_state = explode('#$', url_decryption($_POST['agency_state']));
            $case_details['state_id']=$agency_state[0];
            $case_details['state_name']=$agency_state[1];
            $agency = explode('##', url_decryption($_POST['agency']));
            $case_details['agency_code']='1';
            $case_details['estab_id']=$agency[0];
            $case_details['estab_name']=$agency[1];
            $case_details['estab_code']=$agency[2];
            $agency_case_type = explode('##', url_decryption($_POST['agency_case_type_id']));
            $case_details['case_type_id']=$agency_case_type[0];
            if ($agency_case_type[0]=='0'){
                $case_details['casetype_name']=$_POST['agency_case_type_name'];
            }else{
                $case_details['casetype_name']=$agency_case_type[1];
            }


            $case_details['case_num']=$_POST['case_number'];
            $case_details['case_year']=url_decryption($_POST['case_year']);
        }
        // echo '<pre>';print_r($case_details);echo '</pre>';exit();
        $casedetails=$this->Get_details_model->get_new_case_details($registration_id);
        $sc_casetype=$casedetails[0]['sc_case_type_id'];
        $criminal_case_type_id=array(10,12,14,26,2,28,6,4,8,18,20,29,16,33,35,36,41);
        if(in_array($sc_casetype,$criminal_case_type_id) || $this->session->userdata('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
            if(isset($_POST['fir_state']) && !empty($_POST['fir_state'])){
                $fir_state = explode('#$', url_decryption($_POST['fir_state']));

                $fir_state_id = $fir_state[0];
                $fir_state_name = $fir_state[1];

                $fir_district = explode('#$', url_decryption($_POST['fir_district']));
                $fir_district_id = $fir_district[0];
                $fir_district_name = $fir_district[1];
                $fir_police_station = explode('#$', url_decryption($_POST['fir_policeStation']));
                $fir_police_station_id = $fir_police_station[0];
                $fir_police_station_name = $fir_police_station[1];
                $complete_fir_no = '';
                $fir_year = url_decryption($_POST['fir_year']);
                if (!empty($fir_police_station_id)) {
                    $no = $_POST['fir_number'];
                    $filled_int = sprintf("%04d", $no);
                    $year = substr($fir_year, -2);
                    $complete_fir_no = $fir_police_station_id . $filled_int . $year;
                }
                $fir_details = array(
                    'registration_id' => $registration_id,
                    'state_id' => !empty($fir_state_id) ? $fir_state_id : 0,
                    'district_id' => !empty($fir_district_id) ? $fir_district_id : 0,
                    'police_station_id' => !empty($fir_police_station_id) ? $fir_police_station_id : 0,
                    'state_name' => $fir_state_name,
                    'district_name' => !empty($fir_district_name) ? $fir_district_name : 'ALL',
                    'police_station_name' => !empty($fir_police_station_name) ? $fir_police_station_name : $_POST['police_station_name'],
                    'fir_no' => !empty($_POST['fir_number']) ? $_POST['fir_number'] : '',
                    'fir_year' => !empty($fir_year) ? $fir_year : null,
                    'complete_fir_no' => !empty($complete_fir_no) ? $complete_fir_no : $_POST['complete_fir_number'],
                    'is_deleted' => 'false',
                    'status' => 'true',
                    'updated_by' => $_SESSION['login']['id'],
                    'updated_by_ip' => getClientIP(),
                    'updated_on' => date('Y-m-d H:i:s')
                );
            }
            else{
                $fir_details = null;
            }
        }
        else
            $fir_details = null;

        if (isset($registration_id) && !empty($registration_id)) {
            $subordinate_court_details = $this->Get_details_model->get_subordinate_court_details($registration_id);
            if(!empty($this->session->userdata('efiling_details')['ref_m_efiled_type_id']) && $this->session->userdata('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT){
                $status = $this->New_case_model->add_subordinate_court_info($registration_id, $case_details, CAVEAT_BREAD_SUBORDINATE_COURT,$fir_details,$subordinate_court_details);
                if ($status) {
                    reset_affirmation($registration_id);
                    echo '2@@@' . htmlentities('Details added successfully!', ENT_QUOTES) . '@@@' . base_url('caveat/defaultController/processing' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_CAVEAT . '#' . $_SESSION['efiling_details']['stage_id'])));
                } else {
                    echo '1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
                }
            }
            else
            {
                $status = $this->New_case_model->add_subordinate_court_info($registration_id, $case_details, NEW_CASE_SUBORDINATE_COURT,$fir_details,$subordinate_court_details);
                if ($status) {
                    reset_affirmation($registration_id);
                    echo '2@@@' . htmlentities('Details added successfully!', ENT_QUOTES) . '@@@' . base_url('newcase/defaultController/' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_NEW_CASE . '#' . $_SESSION['efiling_details']['stage_id'])));
                } else {
                    echo '1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
                }
            }

        }
        unset($_SESSION['search_case_data_save']);
    }

    //This call is once in a while functionality to update HC & Bench master table i.e: m_tbl_high_courts_bench
    function hc_bench_from_api(){
        if(!$this->input->is_cli_request()){
            echo "This script can only be accessed via the command line" . PHP_EOL;
            return;
        }
        $high_courts = $this->highcourt_webservices->states();
        $high_courts = json_decode($high_courts, true);
        if(isset($high_courts['state'])){
            foreach ($high_courts['state'] as $courts) {
                $this->Dropdown_list_model->insert_to_table('efil.m_tbl_high_courts_bench', array('hc_id'=>$courts['state_code'], 'name'=>$courts['state_name']));
            }
        }
        $hc_list = $this->Dropdown_list_model->high_courts();
        foreach ($hc_list as $hc){
            $hc_benches = $this->highcourt_webservices->bench($hc['hc_id']);
            $hc_benches = json_decode($hc_benches, true);
            if(isset($hc_benches['benches'])){
                foreach ($hc_benches['benches'] as $bench) {
                    $this->Dropdown_list_model->insert_to_table('efil.m_tbl_high_courts_bench', array('hc_id'=>$hc['hc_id'], 'name'=>$bench['bench_name'], 'bench_id'=>$bench['bench_id'], 'est_code'=>$bench['est_code']));
                }
            }
        }
    }

    //This call is once in a while functionality to update HC & Bench master table i.e: m_tbl_high_courts_bench
    function hc_case_types_from_api(){
        if(!$this->input->is_cli_request()){
            echo "This script can only be accessed via the command line" . PHP_EOL;
            return;
        }
        $bench_list = $this->Dropdown_list_model->hc_bench(0,0);
        foreach ($bench_list as $bench){
            $hc_case_types = $this->highcourt_webservices->caseTypeMaster($bench['est_code']);
            $hc_case_types = json_decode($hc_case_types, true);
            if(isset($hc_case_types)){
                foreach ($hc_case_types as $hc_case_type) {
                    $this->Dropdown_list_model->insert_to_table('efil.m_tbl_high_courts_case_types', array('est_code'=>$bench['est_code'], 'case_type'=>$hc_case_type['case_type'], 'type_name'=>$hc_case_type['type_name'] ));
                    // $dropDownOptions .= '<option value="' . escape_data(url_encryption($hc_case_type['case_type'] . "##" . $hc_case_type['type_name'] )) . '">' . escape_data(strtoupper($hc_case_type['type_name'])) . '</option>';
                }
            }
        }
    }

    //This call is once in a while functionality to update HC & Bench master table i.e: m_tbl_high_courts_bench
    function dc_estab_from_api(){
        if(!$this->input->is_cli_request()){
            echo "This script can only be accessed via the command line" . PHP_EOL;
            return;
        }
        $states = file_get_contents(DISTRICT_COURT_URL.'states');
        $states = json_decode($states, true);
        if(isset($states['state'])){
            foreach ($states['state'] as $state) {
                $state_code = $state['state_code'];
                $state_name = $state['state_name'];
                $districts = file_get_contents(DISTRICT_COURT_URL.'districts/'.$state_code);
                $districts = json_decode($districts, true);
                if(isset($districts['district'])){
                    foreach ($districts['district'] as $district){
                        $district_code = $district['dist_code'];
                        $district_name = $district['dist_name'];
                        $complexes = file_get_contents(DISTRICT_COURT_URL.'courtComplex/'.$state_code.'/'.$district_code);
                        $complexes = json_decode($complexes, true);
                        if(isset($complexes['complex1'])){
                            foreach ($complexes['complex1'] as $complex){
                                $estab_code = $complex['est_code'];
                                $estab_name = $complex['court_est_name'];
                                $x = array('state_code'=>$state_code,
                                    'state_name'=>$state_name,
                                    'district_code'=>$district_code,
                                    'district_name'=>$district_name,
                                    'estab_code'=>$estab_code,
                                    'estab_name'=>$estab_name);
                                if(!is_null($estab_code))
                                    $this->Dropdown_list_model->insert_to_table('efil.m_tbl_district_courts_establishments',$x);
                                else
                                    continue;
                            }
                        }
                    }
                }

            }
        }
    }

    function dc_case_types_from_api(){
        /*if(!$this->input->is_cli_request()){
            echo "This script can only be accessed via the command line" . PHP_EOL;
            return;
        }*/
        $estab_list = $this->Dropdown_list_model->get_district_court_establishments();
        //var_dump($estab_list);
        foreach ($estab_list as $estab){
            /*echo '<pre>';
            print_r($estab);*/
            $dc_case_types = file_get_contents(DISTRICT_COURT_URL.'webServiceCaseTypeMaster/'.$estab['estab_code']);
            $dc_case_types = json_decode($dc_case_types, true);
            if(isset($dc_case_types)){
                foreach ($dc_case_types as $dc_case_type) {
                    $this->Dropdown_list_model->insert_to_table('efil.m_tbl_district_courts_case_types', array('est_code'=>$estab['estab_code'], 'case_type'=>$dc_case_type['case_type'], 'type_name'=>$dc_case_type['type_name'] ));
                }
            }
        }
    }



    /*changes started on 04 September 2020*/
    public function DeleteSubordinateCourt($delete_id) {
        $delete_id = url_decryption($delete_id);
        if (empty($delete_id)) {
            if(!empty($this->session->userdata('efiling_details')['ref_m_efiled_type_id']) && $this->session->userdata('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT){
                $_SESSION['MSG'] = message_show("fail", "Invalid ID."); // this msg is not being displayed. checking required
                redirect('caveat/subordinate_court');
                exit(0);
            }
            else{
                $_SESSION['MSG'] = message_show("fail", "Invalid ID."); // this msg is not being displayed. checking required
                redirect('newcase/Subordinate_court');
                exit(0);
            }
        }
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            if(!empty($this->session->userdata('efiling_details')['ref_m_efiled_type_id']) && $this->session->userdata('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT){
                redirect('caveat/subordinate_court');
                exit(0);
            }
            else{
                redirect('newcase/Subordinate_court');
                exit(0);
            }
        }
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);

        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            if(!empty($this->session->userdata('efiling_details')['ref_m_efiled_type_id']) && $this->session->userdata('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT){
                redirect('caveat/subordinate_court');
                exit(0);
            }
            else{
                redirect('newcase/Subordinate_court');
                exit(0);
            }
        }
        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $params =array();
            $params['table_name'] = "efil.tbl_fir_details";
            $params['whereFieldName'] = "ref_tbl_lower_court_details_id";
            $params['whereFieldValue'] = $delete_id;
            $params['is_deleted'] = "false";
            $result= $this->common_model->getData($params);
            $firData = false;
            if(isset($result) && !empty($result)){
                $firData = true;
            }
            $delete_status = $this->DeleteSubordinateCourt_model->delete_case_subordinate_court($registration_id, $delete_id,$firData);
            if ($delete_status) {
                reset_affirmation($registration_id);
                $_SESSION['MSG'] = message_show("success", "Earlier Court Details deleted successfully."); // this msg is not being displayed. checking required
            } else {
                $_SESSION['MSG'] = message_show("fail", "Some error. Please try again."); // this msg is not being displayed. checking required
            }
            if(!empty($this->session->userdata('efiling_details')['ref_m_efiled_type_id']) && $this->session->userdata('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT){
                redirect('caveat/defaultController/processing/' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_CAVEAT . '#' . $_SESSION['efiling_details']['stage_id'])));
                exit(0);
            }
            else{
                redirect('newcase/subordinate_court');
                //redirect('newcase/defaultController/' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_NEW_CASE . '#' . $_SESSION['efiling_details']['stage_id'])));
                exit(0);
            }

        }
    }

    /*End of changes*/


}
