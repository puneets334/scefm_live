<?php
namespace App\Controllers\Caveat;
use App\Controllers\BaseController;
use App\Models\Caveat\CaveateeModel;
use App\Models\Caveat\SubordinateCourtModel;
use App\Models\NewCase\ViewModel;
use App\Models\Common\CommonModel;
use App\Models\NewCase\NewCaseModel;
use App\Models\NewCase\GetDetailsModel;
use App\Models\NewCase\DropdownListModel;
class Subordinate_court extends BaseController {

    protected $Common_model;
   // protected $Caveat_common_model;
    protected $Caveatee_model;
    protected $New_case_model;
    protected $Subordinate_court_model;
    protected $View_model;
    protected $db;
    protected $Dropdown_list_model;
    protected $Get_details_model;
    protected $request;
    protected $validation;

    public function __construct() {
        parent::__construct();
        $this->Caveatee_model = new CaveateeModel();
        $this->Dropdown_list_model = new DropdownListModel();
        $this->Common_model = new CommonModel();
        $this->New_case_model = new NewCaseModel();
        $this->Subordinate_court_model = new SubordinateCourtModel();
        $this->Get_details_model = new GetDetailsModel();
        $this->View_model = new ViewModel();
        $this->request = \Config\Services::request();
        $this->validation = \Config\Services::validation();
        
    }

    public function index() {
        $allowed_users = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users)) {
            return response()->redirect(base_url('/'));
            exit(0);
        }
        $this->check_caveator_info();
        if (!empty(getSessionData('estab_details')) &&  !empty(getSessionData('efiling_details')) && (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT)) {
            $estab_code = getSessionData('estab_details')['estab_code'];
            $efiling_for_type_id = getSessionData('estab_details')['efiling_for_type_id'];
            $state_code = getSessionData('estab_details')['state_code'];
        } else {
            $_SESSION['MSG'] = message_show("fail", 'Invalid request !');
            return response()->redirect(base_url('dashboard'));
            exit(0);
        }
        $Array = array(Draft_Stage, Initial_Defected_Stage, I_B_Rejected_Stage, E_REJECTED_STAGE);
        if (!in_array(getSessionData('efiling_details')['stage_id'], $Array)) {
            return response()->redirect(base_url('caveat/view'));
            exit(0);
        }
        $registration_id = getSessionData('efiling_details')['registration_id'];
        if (!isset($registration_id)) {
            echo '1@@@' . htmlentities('Add Petitioner Details First', ENT_QUOTES);
            exit(0);
        }
        $data['stateListFir']=$this->Dropdown_list_model->get_states_list();
        $params = array();
        $type = 1; //state list
        $params['type']= $type;
        $stateData = $this->Common_model->getSubordinateCourtData($params);
        if(isset($stateData) && !empty($stateData)){
            $data['state_list'] = $stateData;
        }

        /*   similar to new case */
        $data['sc_case_type'] = $this->Dropdown_list_model->get_sci_case_type();
        $data['main_subject_cat'] = $this->Dropdown_list_model->get_main_subject_category();
        $criminal_case=0;$sc_casetype=0;
        if (isset(getSessionData('efiling_details')['registration_id']) && !empty(getSessionData('efiling_details')['registration_id'])) {

            $registration_id = getSessionData('efiling_details')['registration_id'];
            $data['subordinate_court_details'] = $this->Get_details_model->get_subordinate_court_details($registration_id);
            $this->Get_details_model->get_case_table_ids($registration_id);
           
            $casedetails=$this->Get_details_model->get_new_case_details($registration_id);
           // pr($casedetails);
           if($casedetails)
           {
            $sc_casetype=$casedetails[0]['sc_case_type_id'];
            $criminal_case_type_id=array(10,12,14,26,2,28,6,4,8,18,20,29,16,33,35,36,41);
            if(in_array($sc_casetype,$criminal_case_type_id))
                $criminal_case=1;
           }
            
        }
        $caseData = array();
        $caseData['court_type'] = !empty($casedetails[0]['court_type']) ? $casedetails[0]['court_type'] : NULL;
        $data['caseData'] = $caseData;
        $data['criminal_case']=$criminal_case;
        $data['sc_casetypearr']=$sc_casetype;
        $high_courts = $this->Dropdown_list_model->high_courts();
        $data['high_courts'] = $high_courts;
     
        $this->render('caveat.caveat_view', $data);
    }
    function check_caveator_info() {
        if (isset(getSessionData('efiling_for_details')['case_type_pet_title']) && !empty(getSessionData('efiling_for_details')['case_type_pet_title'])) {
            $case_type_pet_title = htmlentities(getSessionData('efiling_for_details')['case_type_pet_title'], ENT_QUOTES);
        } else {
            $case_type_pet_title = htmlentities('Caveator', ENT_QUOTES);
        }
        if (!in_array(CAVEAT_BREAD_CAVEATEE, explode(',', getSessionData('efiling_details')['breadcrumb_status']))) {
            $_SESSION['MSG'] = message_show("fail", 'Please complete <b>" ' . $case_type_pet_title . ' "</b> section.');
            return response()->redirect(base_url('caveat/caveator'));
            exit(0);
        }
    }

    public function appellate_n_trial_info() {
        $allowed_users = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $cin_or_case_array = explode('$$', url_decryption(escape_data($_POST['cin_or_case'])));
        $cin_or_case_data = explode('#$', $cin_or_case_array[0]);
        $cin_or_case = $cin_or_case_data[0];
        $filing_type_array = explode('$$', url_decryption(escape_data($_POST['filing_type'])));
        $filing_type_data = explode('#$', $filing_type_array[0]);
        $filing_type = $filing_type_data[0];
        if ($cin_or_case == 1) {
            $form_validate = 'subordinate_court_valid_case_number';
        } elseif ($filing_type == 1) {
            $form_validate = 'subordinate_court_valid_efiling_no';
        } else {
            $form_validate = 'subordinate_court_valid';
        }
        $this->form_validation->set_error_delimiters('<br>', '');
        if ($this->form_validation->run($form_validate) == FALSE) {
            echo '1@@@' . validation_errors();
            exit(0);
        }
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        if (!isset($registration_id)) {
            echo '1@@@' . htmlentities('Add Caveator Details First', ENT_QUOTES);
            exit(0);
        }
        $court_type_array = explode('$$', url_decryption(escape_data($_POST['court_type'])));
        $court_type_data = explode('#$', $court_type_array[0]);
        $court_type = $court_type_data[0];
        $cnr_num = str_replace('-', '', strtoupper(escape_data($_POST["cnr_number"])));

        //$case_type_array = explode('$$', url_decryption(escape_data($_POST['case_type'])));
        //$case_type_data = explode('##', $case_type_array[0]);
        $case_type_data = explode('##', url_decryption($_POST['case_type']));
         $case_type_id = $case_type_data[0];

        if ($case_type_data[0]=='0'){
            $case_type_name=$_POST['dc_case_type_name'];
        }else{
            $case_type_name = $case_type_data[1];
        }




        $case_number = escape_data($_POST["case_number"]);
        $case_year = escape_data($_POST["case_year"]);
        $judge_name = escape_data($_POST["judge_name"]);
        $decision_date = escape_data($_POST["decision_date"]);
        $cc_applied_date = escape_data($_POST["cc_applied_date"]);
        $cc_ready_date = escape_data($_POST["cc_ready_date"]);
        if ($decision_date != '') {
            $date = explode('/', $decision_date);
            $decision_date = $date[2] . '-' . $date[1] . '-' . $date[0];
        } else {
            $decision_date = NULL;
        }
        if ($cc_applied_date != '') {
            $date = explode('/', $cc_applied_date);
            $cc_applied_date = $date[2] . '-' . $date[1] . '-' . $date[0];
        } else {
            $cc_applied_date = NULL;
        }
        if ($cc_ready_date != '') {
            $date = explode('/', $cc_ready_date);
            $cc_ready_date = $date[2] . '-' . $date[1] . '-' . $date[0];
        } else {
            $cc_ready_date = NULL;
        }
        $appellate_court_type_array = explode('$$', url_decryption(escape_data($_POST['appellate_court_type'])));
        $app_cout_type_data = explode('#$', $appellate_court_type_array[0]);
        $appellate_trial = $app_cout_type_data[0];
        $app_info_id = url_decryption($_POST['app_info_id']);
        $trial_info_id = url_decryption($_POST['trial_info_id']);
        $sub_state_array = explode('$$', url_decryption($_POST['sub_state_id']));
        $state_array = explode('#$', $sub_state_array[0]);
        $state_id = $state_array[0];
        $state_name = $state_array[1];
        $sub_district_array = explode('$$', url_decryption($_POST['sub_district']));
        $district_array = explode('#$', $sub_district_array[0]);
        $district_id = $district_array[0];
        $district_name = $district_array[1];
        $subordinate_data_array = explode('$$', url_decryption(escape_data($_POST['subordinate_court'])));
        $subordinate_court_array = explode('#$', $subordinate_data_array[0]);
        $subordinate_court_id = $subordinate_court_array[0];
        $subordinate_court_name = $subordinate_court_array[1];
        $filing_type = !empty($filing_type) ? $filing_type : NULL;
        if ($cin_or_case == 1) {
            $lower_court = '4' . sprintf("%'.03d", $case_type_id) . sprintf("%'.07d", $case_number) . $case_year;
        } else {
            $lower_court = NULL;
        }
        $app_data = array(
            'registration_id' => $registration_id,
            'efilno' => $_SESSION['efiling_details']['efiling_no'],
            'lower_state_id' => $state_id,
            'lower_dist_code' => $district_id,
            'lower_court_code' => $subordinate_court_id,
            'lower_cino' => trim(strtoupper($cnr_num)),
            'lower_judge_name' => strtoupper($judge_name),
            'lower_court' => $lower_court,
            'sub_qj_high' => $court_type,
            'lower_trial' => 1,
            'filing_case' => $filing_type,
            'lower_court_dec_dt' => $decision_date,
            'lcc_applied_date' => $cc_applied_date,
            'lcc_received_date' => $cc_ready_date,
            'reconsume' => $reconsume,
            'appellate_trial' => $appellate_trial,
            'is_deleted' => FALSE,
            'case_no'=> !empty($_POST['case_number']) ? $_POST['case_number'] : NULL,
            'case_year'=>!empty($_POST['case_year']) ? url_decryption($_POST['case_year']) : NULL,
            'case_type'=>!empty($case_type_id) ? $case_type_id : NULL,
            'casetype_name'=>!empty($case_type_name) ? $case_type_name : NULL
        );
        //echo '<pre>';print_r($app_data);echo '</pre>';//exit();
        if ($appellate_trial == 1) {
            $cis_masters_values = array(
//                'app_court_state_name' => strtoupper($state_name),
//                'app_court_distt_name' => strtoupper($district_name),
//                'app_court_sub_court_name' => strtoupper($subordinate_court_name),
//                'app_court_sub_case_type' => strtoupper($case_type_name)
            );
        } elseif ($appellate_trial == 2) {
            $cis_masters_values = array(
//                'trial_court_state_name' => strtoupper($state_name),
//                'trial_court_distt_name' => strtoupper($district_name),
//                'trial_court_sub_court_name' => strtoupper($subordinate_court_name),
//                'trial_court_case_type' => strtoupper($case_type_name)
            );
        }

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
            'updated_by_ip' => $_SERVER['REMOTE_ADDR'],
            'updated_on' => date('Y-m-d H:i:s')
        );
        $check_details = $this->Subordinate_court_model->check_subordinate_entry($registration_id, array(3)); //1 =>subordinate 3=>High Court
        if (!empty($check_details)) {
            echo '1@@@' . htmlentities('High Court info already added!', ENT_QUOTES);
            exit(0);
        }
        $subordinate_details = $this->Subordinate_court_model->already_added_sub_court_info($registration_id, $appellate_trial);
        if (empty($subordinate_details)) {
            $result = $this->Subordinate_court_model->add_subordinate_court_info($registration_id, $app_data, $cis_masters_values);
        } else if ($subordinate_details[0]['appellate_trial'] == 1) {
            echo '1@@@' . htmlentities('First Appellate Court Information Already Added!', ENT_QUOTES);
            exit(0);
        } else if ($subordinate_details[0]['appellate_trial'] == 2) {
            echo '1@@@' . htmlentities('Trial Court Information Already Added!', ENT_QUOTES);
            exit(0);
        }
        if ($result) {
            if (!empty($firdetails) && $firdetails !=null && $firdetails=='firdetails' && !empty($fir_state_id) && $fir_state_id !=null){
                $insert_id = $this->Common_model->insertFir($fir_details);
            }

            echo '2@@@' . htmlentities('Court information added successfully!', ENT_QUOTES);
            log_message('CUSTOM', "Caveat:Subordinate court-Court information added successfully!");
        } else {
            echo '1@@@' . htmlentities('Some things went wrong. Please Try again', ENT_QUOTES);
        }
    }

    public function quasi_jud_info() {

        $allowed_users = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users)) {
            return response()->redirect(base_url('/'));
            exit(0);
        }

        $form_validate = 'quasi_jud_info';

        $this->form_validation->set_error_delimiters('<br>', '');
        if ($this->form_validation->run($form_validate) == FALSE) {
            echo '1@@@' . validation_errors();
            exit(0);
        }

        $registration_id = $_SESSION['efiling_details']['registration_id'];
        if (!isset($registration_id)) {
            echo '1@@@' . htmlentities('Add Caveator Details First', ENT_QUOTES);
            exit(0);
        }

        $court_type_array = explode('$$', url_decryption(escape_data($_POST['court_type'])));
        $court_type_data = explode('#$', $court_type_array[0]);
        $court_type = $court_type_data[0];

        $appellate_court_type_array = explode('$$', url_decryption(escape_data($_POST['appellate_court_type'])));
        $app_cout_type_data = explode('#$', $appellate_court_type_array[0]);
        $appellate_trial = $app_cout_type_data[0];

        $case_ref_no = escape_data($_POST['case_ref_no']);
        $case_order_dt = escape_data($_POST['case_order_dt']);

        $date = explode('/', $case_order_dt);
        $case_order_dt = $date[2] . '-' . $date[1] . '-' . $date[0];

        $quasi_app_data = array(
            'registration_id' => $registration_id,
            'efilno' => $_SESSION['efiling_details']['efiling_no'],
            'lower_state_id' => NULL,
            'lower_dist_code' => NULL,
            'lower_court_code' => NULL,
            'lower_cino' => NULL,
            'lower_judge_name' => NULL,
            'lower_court' => NULL,
            'sub_qj_high' => 2,
            'lower_trial' => 1,
            'filing_case' => 0,
            'qjnumber' => strtoupper($case_ref_no),
            'date_of_order' => $case_order_dt,
            'appellate_trial' => $appellate_trial,
            'is_deleted' => FALSE
        );

        $cis_masters_values = NULL;

        $check_details = $this->Subordinate_court_model->check_subordinate_entry($registration_id, array(1, 3)); //1 =>Subordinate Court 3=>High Court
        if (!empty($check_details)) {
            echo '1@@@' . htmlentities('Subordinate Court OR High Court info already added!', ENT_QUOTES);
            exit(0);
        }
        $subordinate_details = $this->Subordinate_court_model->already_added_sub_court_info($registration_id, $appellate_trial);

        if (empty($subordinate_details)) {
            $result = $this->Subordinate_court_model->add_subordinate_court_info($registration_id, $quasi_app_data, $cis_masters_values);
        } else if ($subordinate_details[0]['appellate_trial'] == 3) {
            echo '1@@@' . htmlentities('First Appellate Court Information Already Added!', ENT_QUOTES);
            exit(0);
        } else if ($subordinate_details[0]['appellate_trial'] == 4) {
            echo '1@@@' . htmlentities('Trial Court Information Already Added!', ENT_QUOTES);
            exit(0);
        } else {
            echo '1@@@' . htmlentities('Subordinate Court Information Already Added. Delete details and try again!', ENT_QUOTES);
            exit(0);
        }

        if ($result) {
            echo '2@@@' . htmlentities('Quasi judicial information added successfully!', ENT_QUOTES);
        } else {
            echo '1@@@' . htmlentities('Some things went wrong. Please Try again', ENT_QUOTES);
        }
    }

    public function search_case_data() {
        $allowed_users = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users)) {
             return response()->redirect(base_url('/'));
            exit(0);
        }
        $this->form_validation->set_error_delimiters('<br>', '');
        $this->form_validation->set_rules('case_type','Case Type','trim|required');
        $this->form_validation->set_rules('hc_court','High court','trim|required');
        $this->form_validation->set_rules('hc_court_bench','High court bench','trim|required');
        $this->form_validation->set_rules('hc_court_bench','High court bench','trim|required');
        $this->form_validation->set_rules('hcase_number','High court case no.','trim|required');
        $this->form_validation->set_rules('hcase_year','High court case year','trim|required');
        if ($this->form_validation->run('subordinate_court_search_case_re') == FALSE) {
            echo '1@@@' . validation_errors();
            exit(0);
        } else {
            if ($_POST > 0) {
//                if (!empty($_POST['case_type']) && !empty($_POST['case_number']) && !empty($_POST['534543534']) && !empty($_POST['userCaptcha'])) {
                if (!empty($_POST['case_type']) && !empty($_POST['hcase_number']) && !empty($_POST['hcase_year']) && !empty($_POST['hc_info_id'])) {
                    $case_number = $_POST['hcase_number'];
                    $case_year = $_POST['hcase_year'];
                    $case_type_id_array = explode('#$', url_decryption($_POST['case_type']));
                    $case_type_id = $case_type_id_array[0];

//                    if ($this->session->userdata['captchaWord'] != $_POST['userCaptcha']) {
//                        echo '1@@@' . htmlentities('Invalid Captcha', ENT_QUOTES);
//                        exit(0);
//                    }

                    //$dist_code = 1;
                    $high_court_code = $_SESSION['estab_details']['efiling_for_id'];
                    $state_code = $_SESSION['estab_details']['state_code'];
                    $estab_details = $this->Common_model->get_establishment_details(E_FILING_FOR_HIGHCOURT, $high_court_code);
                    if (!isset($estab_details) && empty($estab_details)) {
                        echo '1@@@' . htmlentities('Establishment details not found !', ENT_QUOTES);
                        exit(0);
                    }
                    // for testing $state_code =1 but value in empty in table
                    //$state_code =1;
                    if (!empty($_SESSION['estab_details']['estab_code']) && !empty($case_type_id) && !empty($case_number) && !empty($case_year))
                    {
                        $est_code = !empty($_POST['hc_court_bench']) ? url_decryption($_POST['hc_court_bench']) : NULL;
                        $case_type = !empty($_POST['case_type']) ? url_decryption($_POST['case_type']) : NULL;
                        $case_number = !empty($_POST['hcase_number']) ? $_POST['hcase_number'] : NULL;
                        $case_year = !empty($_POST['hcase_year']) ? $_POST['hcase_year'] : NULL;
                        $searchUrl = HIGH_COURT_URL.'by_case_no/'.$est_code.'/'.$case_type.'/'.$case_number.'/'.$case_year;
                        $case_result = file_get_contents($searchUrl);
                        if(isset($case_result) && !empty($case_result)){
                            $case_data = json_decode($case_result, true);
                        }
                        if(isset($case_data['casenos']['case1']['cino']) && !empty($case_data['casenos']['case1']['cino'])){
                            $cino = trim($case_data['casenos']['case1']['cino']);
                            $searchByCinoUrl = HIGH_COURT_URL.'by_cino/'.$cino;
                            $getSearchedCinData = file_get_contents($searchByCinoUrl);
                            if(isset($getSearchedCinData) && !empty($getSearchedCinData)){
                                $cinoData = json_decode($getSearchedCinData,true);
                            }
                        }
                        if (isset($cinoData) && !empty($cinoData)) {
                            $this->case_details($cinoData, $_POST['hc_info_id']);
                        } else {
                            echo '1@@@' . htmlentities('Case data not found !', ENT_QUOTES);
                            exit(0);
                        }
                    } else {
                        echo '1@@@' . htmlentities('Invalid request !', ENT_QUOTES);
                        exit(0);
                    }
                }
            }
        }
    }

    public function case_details($case_data, $hc_info_id) {

        $date_of_filing = (!empty($case_data['date_of_filing'])) ? date("d-m-Y", strtotime($case_data['date_of_filing'])) : NULL;
        $date_of_decision = (!empty($case_data['date_of_decision'])) ? date("d/m/Y", strtotime($case_data['date_of_decision'])) : NULL;
//        $case_no_year = $case_data['case_no'];
//        $case_type_id = substr($case_no_year, 1, 3);
//        $case_nums = substr($case_no_year, 4, -4);
//        $case_year = substr($case_no_year, -4);

        $enable_reconsume = array(I_B_Rejected_Stage);
        if (in_array($_SESSION['efiling_details']['stage_id'], $enable_reconsume)) {
            $reconsume = TRUE;
        }

        $case_details_searched = array(
            'cino' =>!empty($case_data['cino']) ? $case_data['cino'] : NULL,
            'type_name_id' => !empty($case_data['case_type_id']) ? $case_data['case_type_id'] : NULL,
            'type_name_name' => !empty($case_data['type_name_fil']) ? $case_data['type_name_fil'] : NULL,
            'case_nums' => !empty($case_data['case_type_id']) ? $case_data['case_type_id'] : NULL,
            'case_year' => !empty($case_data['fil_year']) ? $case_data['fil_year'] : NULL,
            'decision_date' => !empty($case_data['date_of_decision']) ? date('d/m/Y',strtotime($case_data['date_of_decision'])) : NULL,
            'reconsume' => $reconsume
        );

        $session_data = array('case_details_searched' => $case_details_searched);
        $this->session->set_userdata($session_data);
        echo '2@@@' . htmlentities(cin_preview($case_data['cino']) . '$#' . $case_data['type_name_reg'] . '$#' . ltrim($case_data['reg_no'], '0') . ' / ' . $case_data['fil_year'] . '$#' . $case_data['pet_name'] . '$#' . $case_data['res_name'] . '$#' . date('d/m/Y',strtotime($case_data['date_of_decision'])), ENT_QUOTES);
    }
    public function add_state_agency()
    {
        $allowed_users = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if ($this->form_validation->run() == FALSE) {
            echo '1@@@' . validation_errors();
            exit(0);
        } else {
            $this->form_validation->set_rules('court_type', 'Court type', 'required|trim');
            $this->form_validation->set_rules('state_agency_state', 'state name', 'required|trim');
            $this->form_validation->set_rules('state_agency_name', 'Agency name', 'required|trim');
            $this->form_validation->set_rules('state_agency_case_type', 'Case type', 'required|trim');
            $this->form_validation->set_rules('state_agency_case_no', 'Case No.', 'required|trim');
            $this->form_validation->set_rules('case_year', 'Case year', 'required|trim');
            $this->form_validation->set_rules('order_date', 'Impugned order date', 'required|trim');
            $this->form_validation->set_rules('judgement_challenged', 'Impugned order challenged', 'required|trim');
            $this->form_validation->set_rules('judgement_type', 'Impugned order type', 'required|trim');
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $enable_reconsume = array(I_B_Rejected_Stage);
            $reconsume = FALSE;
            if (in_array($_SESSION['efiling_details']['stage_id'], $enable_reconsume)) {
                $reconsume = TRUE;
            }
            $court_type = !empty($_POST['court_type']) ? url_decryption($_POST['court_type']) : NULL;
            $state_agency_state = !empty($_POST['state_agency_state']) ? url_decryption($_POST['state_agency_state']) : NULL;
            $state_agency_name = !empty($_POST['state_agency_name']) ? url_decryption($_POST['state_agency_name']) : NULL;
            $state_agency_case_type = !empty($_POST['state_agency_case_type']) ? url_decryption($_POST['state_agency_case_type']) : NULL;
            $state_agency_case_no = !empty($_POST['state_agency_case_no']) ? $_POST['state_agency_case_no'] : NULL;
            $case_year = !empty($_POST['case_year']) ? url_decryption($_POST['case_year']) : NULL;
            $order_date = !empty($_POST['order_date']) ? $_POST['order_date'] : NULL;
            $judgement_challenged = !empty($_POST['judgement_challenged']) ? $_POST['judgement_challenged'] : NULL;
            $judgement_type = !empty($_POST['judgement_type']) ? $_POST['judgement_type'] : NULL;
            $firdetails = trim($_POST['firdetails']);
            $firstate_agency_state = !empty($_POST['firstate_agency_state']) ? url_decryption($_POST['firstate_agency_state']) : NULL;
            $firdistrict_agency_state = !empty($_POST['firdistrict_agency_state']) ? url_decryption($_POST['firdistrict_agency_state']) : NULL;
            $firpoliceStation_agency_state = !empty($_POST['firpoliceStation_agency_state']) ? url_decryption($_POST['firpoliceStation_agency_state']) : NULL;
            $fir_number_agency_state = !empty($_POST['fir_number_agency_state']) ? $_POST['fir_number_agency_state'] : NULL;
            $firyear_agency_state = !empty($_POST['firyear_agency_state']) ? url_decryption($_POST['firyear_agency_state']) : NULL;
            $police_station_name_agency_state = !empty($_POST['police_station_name_agency_state']) ? $_POST['police_station_name_agency_state'] : NULL;
            $complete_fir_number_agency_state = !empty($_POST['complete_fir_number_agency_state']) ? $_POST['complete_fir_number_agency_state'] : NULL;

            $state_agency_case_type_id='';
            $case_type_name='';
            if(!empty($state_agency_case_type)){
                $state_agency_case_typeArr = explode('##',$state_agency_case_type);
                $state_agency_case_type_id=$state_agency_case_typeArr[0];
                if ($state_agency_case_typeArr[0]=='0'){
                     $case_type_name=$_POST['agency_case_type_name'];
                }else{
                    $case_type_name = $state_agency_case_typeArr[1];
                }
            }
            $lower_court = '2' . sprintf("%'.03d", $state_agency_case_type_id) . sprintf("%'.07d", $state_agency_case_no) . $case_year;
            if(!empty($state_agency_state)){
                $state_agency_stateArr = explode('#',$state_agency_state);
            }
            if(!empty($state_agency_name)){
                $state_agency_nameArr = explode('#',$state_agency_name);
            }


            $data = array(
                'registration_id' => $registration_id,
                'efilno' => $_SESSION['efiling_details']['efiling_no'],
                'lower_state_id' =>  !empty($state_agency_stateArr[0]) ? $state_agency_stateArr[0] : NULL,
                'lower_dist_code' => !empty($state_agency_nameArr[0]) ? $state_agency_nameArr[0] : NULL,
                'lower_court_code' => NULL,
                'lower_cino' => NULL,
                'lower_judge_name' => NULL,
                'lower_court' => $lower_court,
                'sub_qj_high' => 5,
                'filing_case' => 0,
                'date_of_order' => date('Y-m-d H:i:s',strtotime($order_date)),
                'is_judgment_challenged' => $judgement_challenged,
                'judgment_type' => $judgement_type,
                'reconsume' => $reconsume,
                'is_deleted' => FALSE,
                'case_no' => $state_agency_case_no,
                'case_year' => $case_year,
                'case_type' => !empty($state_agency_case_type_id) ? $state_agency_case_type_id : NULL,
                'casetype_name' => !empty($case_type_name) ? $case_type_name : NULL,
                'bench_code' => NULL,
                'court_id' => NULL,
                'create_modify'=>date('Y-m-d H:i:s')
            );

            $fir_state = explode('#$', $firstate_agency_state);
            $fir_state_id = $fir_state[0];
            $fir_state_name = $fir_state[1];
            $fir_district = explode('#$', $firdistrict_agency_state);
            $fir_district_id = $fir_district[0];
            $fir_district_name = $fir_district[1];
            $fir_police_station = explode('#$', $firpoliceStation_agency_state);
            $fir_police_station_id = $fir_police_station[0];
            $fir_police_station_name = $fir_police_station[1];
            $complete_fir_no = '';
            $fir_year = $firyear_agency_state;
            if (!empty($fir_police_station_id)) {
                $no = $fir_number_agency_state;
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
                'police_station_name' => !empty($fir_police_station_name) ? $fir_police_station_name : $police_station_name_agency_state,
                'fir_no' => !empty($fir_number_agency_state) ? $fir_number_agency_state : null,
                'fir_year' => !empty($firyear_agency_state) ? $firyear_agency_state : null,
                'complete_fir_no' => !empty($complete_fir_no) ? $complete_fir_no : $complete_fir_number_agency_state,
                'is_deleted' => 'false',
                'status' => 'true',
                'updated_by' => $_SESSION['login']['id'],
                'updated_by_ip' => $_SERVER['REMOTE_ADDR'],
                'updated_on' => date('Y-m-d H:i:s')
            );
           // echo '<pre>';print_r($fir_details);echo '</pre>';exit();

            $cis_masters_values = array(
                'high_court_case_type' => $case_type_name
            );
            $check_details = $this->Subordinate_court_model->check_subordinate_entry($registration_id, array(1,3,5)); //1 =>Subordinate Court 2=>Quasi Judiacial 3=>High Court,5=>state agency
            if (!empty($check_details[0]['sub_qj_high']) && $check_details[0]['sub_qj_high'] == 3) {
                echo '1@@@' . htmlentities('High Court info already added!', ENT_QUOTES);
                exit(0);
            } else if (!empty($check_details[0]['sub_qj_high']) && $check_details[0]['sub_qj_high'] == 1) {
                echo '1@@@' . htmlentities('Subordinate Court info already added!', ENT_QUOTES);
                exit(0);
            }
            else if (!empty($check_details[0]['sub_qj_high']) && $check_details[0]['sub_qj_high'] == 5) {
                echo '1@@@' . htmlentities('State Agency info already added!', ENT_QUOTES);
                exit(0);
            }
            else {
                $result = $this->Subordinate_court_model->add_subordinate_court_info($registration_id, $data, $cis_masters_values);
            }
            if ($result) {
                $fir_details['ref_tbl_lower_court_details_id'] = $result;
                if (!empty($firdetails) && $firdetails !=null && $firdetails=='firdetails' && !empty($fir_state_id) && $fir_state_id !=null){
                    $insert_id = $this->Common_model->insertFir($fir_details);
                }
                echo '2@@@' . htmlentities('State agency information has been added successfully!', ENT_QUOTES);
            } else {
                echo '1@@@' . htmlentities('Some things went wrong. Please Try again', ENT_QUOTES);
            }
        }
    }


    public function add_high_court_info() {
        $allowed_users = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (isset($_POST)) {
            $hc_info_id = url_decryption($_POST['hc_info_id']);
            if (!empty($hc_info_id)) {
                if (!$hc_info_id && empty($hc_info_id)) {
                    echo '1@@@' . htmlentities('Invalid request !', ENT_QUOTES);
                    exit(0);
                }
            }
            $cino = $_SESSION['case_details_searched']['cino'];
            $case_type_id = $_SESSION['case_details_searched']['type_name_id'];
            $case_number = $_SESSION['case_details_searched']['case_nums'];
            $case_year = $_SESSION['case_details_searched']['case_year'];
            $case_type_name = $_SESSION['case_details_searched']['type_name_name'];
            $decision_date = $_SESSION['case_details_searched']['decision_date'];

            $this->form_validation->set_rules('decision_date', 'Decision Date', 'required|trim|validate_date_dd_mm_yyyy');
            $this->form_validation->set_rules('cc_applied_date', 'CC Applied Date', 'trim|validate_date_dd_mm_yyyy');
            $this->form_validation->set_rules('cc_ready_date', 'CC Ready Date', 'trim|validate_date_dd_mm_yyyy');

            $decision_date = escape_data($this->input->post("decision_date"));
            $cc_applied_date = escape_data($this->input->post("cc_applied_date"));
            $cc_ready_date = escape_data($this->input->post("cc_ready_date"));


            if ($decision_date != '') {
                $date = explode('/', $decision_date);
                $decision_date = $date[2] . '-' . $date[1] . '-' . $date[0];
            }

            if ($cc_applied_date != '') {
                $date = explode('/', $cc_applied_date);
                $cc_applied_date = $date[2] . '-' . $date[1] . '-' . $date[0];
            } else {
                $cc_applied_date = NULL;
            }

            if ($cc_ready_date != '') {
                $date = explode('/', $cc_ready_date);
                $cc_ready_date = $date[2] . '-' . $date[1] . '-' . $date[0];
            } else {
                $cc_ready_date = NULL;
            }


            if (!empty($decision_date) && !empty($cc_applied_date)) {
                if ($decision_date > $cc_applied_date) {
                    echo '1@@@' . htmlentities('CC Applied Date is greater than Date of Decision !', ENT_QUOTES);
                    exit(0);
                }
            }
            if (!empty($cc_applied_date) && !empty($cc_ready_date)) {
                if ($cc_applied_date > $cc_ready_date) {
                    echo '1@@@' . htmlentities('CC Applied Date is greater OR equal to CC Ready Date !', ENT_QUOTES);
                    exit(0);
                }
            }

            $registration_id = $_SESSION['efiling_details']['registration_id'];

            $lower_court = '2' . sprintf("%'.03d", $case_type_id) . sprintf("%'.07d", $case_number) . $case_year;

            $enable_reconsume = array(I_B_Rejected_Stage);
            if (in_array($_SESSION['efiling_details']['stage_id'], $enable_reconsume)) {
                $reconsume = TRUE;
            }
            $case_type_data = explode('##', url_decryption($_POST['case_type']));
            $case_type_id = $case_type_data[0];

            if ($case_type_data[0]=='0'){
                 $case_type_name=$_POST['hc_case_type_name'];
            }else{
                $case_type_name = $case_type_data[1];
            }
            $data = array(
                'registration_id' => $registration_id,
                'efilno' => $_SESSION['efiling_details']['efiling_no'],
                'lower_state_id' => NULL,
                'lower_dist_code' => NULL,
                'lower_court_code' => NULL,
                'lower_cino' => trim(strtoupper($cino)),
                'lower_judge_name' => NULL,
                'lower_court' => $lower_court,
                'sub_qj_high' => 3,
                'lower_trial' => 3,
                'filing_case' => 0,
                'lower_court_dec_dt' => $decision_date,
                'lcc_applied_date' => $cc_applied_date,
                'lcc_received_date' => $cc_ready_date,
                'reconsume' => $reconsume,
                'is_deleted' => FALSE,
                'case_no'=> !empty($_POST['case_number']) ? $_POST['case_number'] : NULL,
                'case_year'=>!empty($_POST['case_year']) ? url_decryption($_POST['case_year']) : NULL,
                'case_type'=>!empty($case_type_id) ? $case_type_id : NULL,
                'casetype_name'=>!empty($case_type_name) ? $case_type_name : NULL,
                'bench_code'=>!empty($_POST['hc_court_bench']) ? url_decryption($_POST['hc_court_bench']) : NULL,
                'court_id'=>!empty($_POST['hc_court']) ? url_decryption($_POST['hc_court']) : NULL
            );
            //echo '<pre>';print_r($data); echo '</pre>'; exit;
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
                'updated_by_ip' => $_SERVER['REMOTE_ADDR'],
                'updated_on' => date('Y-m-d H:i:s')
            );

//            echo '<pre>'; print_r($_POST);
//            echo '<pre>'; print_r($fir_details); exit;

            $cis_masters_values = array(
                'high_court_case_type' => $case_type_name
            );
            $check_details = $this->Subordinate_court_model->check_subordinate_entry($registration_id, array(1,3)); //1 =>Subordinate Court 2=>Quasi Judiacial 3=>High Court
            if (!empty($check_details[0]['sub_qj_high']) && $check_details[0]['sub_qj_high'] == 3) {
                echo '1@@@' . htmlentities('High Court info already added!', ENT_QUOTES);
                exit(0);
            }
            else if (!empty($check_details[0]['sub_qj_high']) && $check_details[0]['sub_qj_high'] == 1) {
                echo '1@@@' . htmlentities('Subordinate Court info already added!', ENT_QUOTES);
                exit(0);
            }
            else {
                $result = $this->Subordinate_court_model->add_subordinate_court_info($registration_id, $data, $cis_masters_values);
            }
            if ($result) {
                $fir_details['ref_tbl_lower_court_details_id'] =$result;
                $insert_id=  $this->Common_model->insertFir($fir_details);
                unset($_SESSION['case_details_searched']);
                echo '2@@@' . htmlentities('High court information added successfully!', ENT_QUOTES);
            } else {
                echo '1@@@' . htmlentities('Some things went wrong. Please Try again', ENT_QUOTES);
            }
        } else {
            echo '1@@@' . htmlentities('Invalid request !', ENT_QUOTES);
            exit(0);
        }
    }

    public function reset_subordinate_court_data($id) {
        $allowed_users = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $id_array = escape_data($id);
        $id_data = explode('$$', url_decryption($id_array));
        $id = $id_data[0];

        if (count($id_data) != 2) {
            $_SESSION['MSG'] = message_show("fail", 'Data tempered !');
            return redirect()->to(base_url('caveat/subordinate_court'));
            exit(0);
        }

        if (empty($id)) {
            $_SESSION['MSG'] = message_show("fail", 'Data tempered !');
            return redirect()->to(base_url('caveat/subordinate_court'));
            exit(0);
        }
        $result = $this->Subordinate_court_model->reset_subordinate_court_data($id);
        if(isset($result) && !empty($result)) {
            $params = array();
            $params['table_name'] ="efil.tbl_fir_details";
            $params['whereFieldName'] ="ref_tbl_lower_court_details_id";
            $params['whereFieldValue'] = $id;
            $params['updateArr'] = array('is_deleted'=>true,'status'=>false,'updated_by'=>$this->session->userdata['login']['id'],'updated_by_ip'=>$_SERVER['REMOTE_ADDR'],'updated_on'=>date('Y-m-d H:i:s'));
            $updateStatus = $this->Common_model->updateTableData($params);
            if(isset($updateStatus) && !empty($updateStatus)){
                $_SESSION['MSG'] = message_show("success", 'Deleted Success Subordinate Court Information!');
                return redirect()->to(base_url('caveat/subordinate_court'));
                exit(0);
            }
            else{
                $_SESSION['MSG'] = message_show("fail", 'Reset fail. Please Try again !');
                return redirect()->to(base_url('caveat/subordinate_court'));
                exit(0);
            }

        } else {
            $_SESSION['MSG'] = message_show("fail", 'Reset fail. Please Try again !');
            return redirect()->to(base_url('caveat/subordinate_court'));
            exit(0);
        }
    }

    public function add_subordinate_info() {

        $allowed_users = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }

        $registration_id = $_SESSION['efiling_details']['registration_id'];
        if (!isset($registration_id)) {
            echo '1@@@' . htmlentities('Add Petitioner Details First', ENT_QUOTES);
            exit(0);
        }


        $this->form_validation->set_rules('sub_state_id', 'State', 'required|trim|validate_encrypted_value[1##state##required]');
        $this->form_validation->set_rules('sub_district', 'District', 'required|trim|validate_encrypted_value[1##district##required]');
        $this->form_validation->set_rules('subordinate_court', 'Subordinate Court Name', 'required|trim|validate_encrypted_value[1##subCourtList##required]');
        $this->form_validation->set_rules('case_number', 'Case Number', 'required|trim|min_length[1]|max_length[6]|is_natural');
        $this->form_validation->set_rules('case_year', 'Year', 'required|trim|exact_length[4]|is_natural');
        $this->form_validation->set_rules('cnr_number', 'CNR Number', 'required|trim|exact_length[18]|validate_cnr');
        $this->form_validation->set_rules('decision_date', 'Decision Date', 'required|trim|validate_date_dd_mm_yyyy');
        $this->form_validation->set_rules('cc_applied_date', 'CC Applied Date', 'trim|validate_date_dd_mm_yyyy');
        $this->form_validation->set_rules('cc_ready_date', 'CC Ready Date', 'trim|validate_date_dd_mm_yyyy');

        $this->form_validation->set_error_delimiters('<br>', '');

        if ($this->form_validation->run() === FALSE) {
            echo '1@@@' . validation_errors();
            exit(0);
        }


        $cnr_num = str_replace('-', '', strtoupper(escape_data($this->input->post("cnr_number"))));
        $judge_name = escape_data($this->input->post("judge_name"));
        $filing_type = escape_data($this->input->post("filing_type"));
        $case_number = escape_data($this->input->post("case_number"));
        $case_year = escape_data($this->input->post("case_year"));
        $decision_date = escape_data($this->input->post("decision_date"));
        $cc_applied_date = escape_data($this->input->post("cc_applied_date"));
        $cc_ready_date = escape_data($this->input->post("cc_ready_date"));
        $cin_or_case = escape_data($this->input->post("cin_or_case"));

        $sub_state_array = explode('$$', url_decryption($_POST['sub_state_id']));
        $state_array = explode('#$', $sub_state_array[0]);
        $state_id = $state_array[0];
        $state_name = $state_array[1];

        $sub_district_array = explode('$$', url_decryption($_POST['sub_district']));
        $district_array = explode('#$', $sub_district_array[0]);
        $district_id = $district_array[0];
        $district_name = $district_array[1];

        $subordinate_data_array = explode('$$', url_decryption(escape_data($_POST['subordinate_court'])));
        $subordinate_court_array = explode('#$', $subordinate_data_array[0]);
        $subordinate_court_id = $subordinate_court_array[0];
        $subordinate_court_name = $subordinate_court_array[1];

        $case_type_array = explode('$$', url_decryption(escape_data($_POST['case_type'])));
        $case_type_data = explode('#$', $case_type_array[0]);
        $case_type_id = $case_type_data[0];
        $case_type_name = $case_type_data[1];


        if ($case_year > date('Y')) {
            echo '1@@@' . htmlentities("Case Year is not greater than current year.", ENT_QUOTES);
            exit(0);
        }
        if ($decision_date != '') {

            $date = explode('/', $decision_date);
            $decision_date = $date[2] . '-' . $date[1] . '-' . $date[0];
        }
        if ($cc_applied_date != '') {

            $date = explode('/', $cc_applied_date);
            $cc_applied_date = $date[2] . '-' . $date[1] . '-' . $date[0];
        } else {
            $cc_applied_date = NULL;
        }

        if ($cc_ready_date != '') {

            $date = explode('/', $cc_ready_date);
            $cc_ready_date = $date[2] . '-' . $date[1] . '-' . $date[0];
        } else {
            $cc_ready_date = NULL;
        }


        if (!empty($decision_date) && !empty($cc_applied_date)) {
            if ($decision_date > $cc_applied_date) {
                echo '1@@@' . htmlentities('CC Applied Date is greater than Date of Decision  !', ENT_QUOTES);
                exit(0);
            }
        }
        if (!empty($cc_applied_date) && !empty($cc_ready_date)) {
            if ($cc_applied_date > $cc_ready_date) {
                echo '1@@@' . htmlentities('CC Applied Date is greater OR equal to CC Ready Date !', ENT_QUOTES);
                exit(0);
            }
        }

        $lower_court = '4' . sprintf("%'.03d", $case_type_id) . sprintf("%'.07d", $case_number) . $case_year;

        if ($cnr_num == '') {
            $cnr_num = NULL;
        }
        if ($filing_type == '') {
            $filing_type = 0;
        }

        $data = array(
            'lower_court_state' => $state_id,
            'lower_court_district' => $district_id,
            'lower_court_code' => $subordinate_court_id,
            'lower_cino' => trim(strtoupper($cnr_num)),
            'lower_judge_name' => strtoupper($judge_name),
            'filing_case' => $filing_type,
            'lower_court' => $lower_court,
            'lower_court_dec_dt' => $decision_date,
            'lcc_applied_date' => $cc_applied_date,
            'lcc_received_date' => $cc_ready_date
        );

        $cis_masters_values = array(
            'lower_court_name' => strtoupper($subordinate_court_name),
            'lower_court_case_type' => strtoupper($case_type_name)
        );

        $update = explode('$$', url_decryption($_POST['add_subordinate_crt']));

        if (url_decryption($_POST['add_subordinate_crt']) == 'add') {
            $message = htmlentities('Subordinate court information added successfully!', ENT_QUOTES);
        } elseif ($update[0] == 'update') {
            $message = htmlentities('Subordinate court information updated successfully!', ENT_QUOTES);
        }
        $result = $this->Subordinate_court_model->add_subordinate($registration_id, $data, $cis_masters_values);

        if ($result) {
            echo '2@@@' . $message;
        } else {
            echo '1@@@' . htmlentities('Some things went wrong. Please Try again!', ENT_QUOTES);
        }
    }
    public function searchSubordinateData(){
        $output = false;
        if(isset($_POST['cnr_number']) && !empty($_POST['cnr_number'])){
            //for cnr
            $cnr_number = !empty($_POST['cnr_number']) ? trim(str_replace('-','',$_POST['cnr_number']))  : NULL;
            if(isset($cnr_number) && !empty($cnr_number)){
                $url = DISTRICT_COURT_URL.'webServiceCIN'.'/'.$cnr_number;
                $searchData = json_decode(file_get_contents($url),true);
                //echo '<pre>'; print_r($searchData); exit;
                if(isset($searchData) && !empty($searchData)){
                    $court_est_name = !empty($searchData['court_est_name'])  ? $searchData['court_est_name'] : '';
                    $pet_name = !empty($searchData['pet_name'])  ? $searchData['pet_name'] : '';
                    $res_name = !empty($searchData['res_name'])  ? $searchData['res_name'] : '';
                    $type_name =  !empty($searchData['type_name'])  ? $searchData['type_name'] : '';
                    $reg_no =  !empty($searchData['reg_no'])  ? $searchData['reg_no'] : '';
                    $reg_year =  !empty($searchData['reg_year'])  ? $searchData['reg_year'] : '';
                    $pend_disp =  (!empty($searchData['pend_disp']) && $searchData['pend_disp'] == 'D')  ? Disposed : 'Pending';
                    $cnr = trim($_POST['cnr_number']);
                    $str .='<div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_content">
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr class="success">
                                    <th>#</th>
                                    <th>Court Name</th>
                                    <th>Parties</th>
                                    <th>Case Number </th>
                                    <th>Status </th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td><td>'.$court_est_name.'</td>
                                <td><strong>Petitioner :</strong>'.$pet_name.'<br>
                                <strong>Respondent :</strong>'.$res_name.'</td>
                                <td>'.$type_name.'  '.$reg_no.' /'.$reg_year.' <br>
                                <strong>CNR No. :</strong>'.$cnr.'</td>
                                <td>'.$pend_disp.'</td>
                                </tr>
                            </tbody></table></div></div>';
                    $output = '2@@@'.$str;
                }
                else{
                    $output .= '1@@@No Records Found!';
                }
            }
        }
        else{
            //for case no.
            $sub_state_id = !empty($_POST['sub_state_id']) ? url_decryption($_POST['sub_state_id'])  : NULL;
            $sub_district = !empty($_POST['sub_district']) ? url_decryption($_POST['sub_district'])  : NULL;
            $subordinate_court = !empty($_POST['subordinate_court']) ? url_decryption($_POST['subordinate_court'])  : NULL;
            $case_type_sub = !empty($_POST['case_type_sub']) ? url_decryption($_POST['case_type_sub'])  : NULL;
            $case_number = !empty($_POST['case_number']) ? trim($_POST['case_number'])  : NULL;
            $case_year = !empty($_POST['case_year']) ? trim($_POST['case_year'])  : NULL;
        if(isset($subordinate_court) && !empty($subordinate_court) && isset($case_type_sub) && !empty($case_type_sub) && isset($case_number) && !empty($case_number) && isset($case_year) && !empty($case_year))
            {
                $url = DISTRICT_COURT_URL.'webServiceCNRByCaseType'.'/'.$subordinate_court.'/'.$case_type_sub.'/'.$case_number.'/'.$case_year;
                $searchData = json_decode(file_get_contents($url),true);
                if(isset($searchData) && !empty($searchData)){
                    $establishment_name = !empty($_POST['establishment_name']) ? trim($_POST['establishment_name'])  : NULL;
                    $cino = !empty($_POST['casenos']['case1']['cino']) ? trim($_POST['casenos']['case1']['cino'])  : NULL;
                    $type_name = !empty($_POST['casenos']['case1']['type_name']) ? trim($_POST['casenos']['case1']['type_name'])  : NULL;
                    $reg_no = !empty($_POST['casenos']['case1']['reg_no']) ? trim($_POST['casenos']['case1']['reg_no'])  : NULL;
                    $reg_year = !empty($_POST['casenos']['case1']['reg_year']) ? trim($_POST['casenos']['case1']['reg_year'])  : NULL;
                    $pet_name = !empty($_POST['casenos']['case1']['pet_name']) ? trim($_POST['casenos']['case1']['pet_name'])  : NULL;
                    $res_name = !empty($_POST['casenos']['case1']['res_name']) ? trim($_POST['casenos']['case1']['res_name'])  : NULL;
                    $pend_disp ='';
                    $str .='<div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_content">
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr class="success">
                                    <th>#</th>
                                    <th>Court Name</th>
                                    <th>Parties</th>
                                    <th>Case Number </th>
                                    <th>Status </th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td><td>'.$establishment_name.'</td>
                                <td><strong>Petitioner :</strong>'.$pet_name.'<br>
                                <strong>Respondent :</strong>'.$res_name.'</td>
                                <td>'.$type_name.'  '.$reg_no.' /'.$reg_year.' <br>
                                <strong>CNR No. :</strong>'.$cino.'</td>
                                <td>'.$pend_disp.'</td>
                                </tr>
                            </tbody></table></div></div>';
                }
                else{
                    $output .= '1@@@No Records Found!';
                }

            }
        }
        echo $output;
        exit;
    }

    public function update_subordinate_court_details() {

        $earlierCourtArr=url_decryption($_POST['earlierCourtArr']);
        $earlierCourtData=explode('@@@',$earlierCourtArr);
         $id=$earlierCourtData[0];
         $registration_id=$earlierCourtData[1];
         $court_type=$earlierCourtData[2];
        //$case_type_id=$earlierCourtData[3];
        //$casetype_name=$earlierCourtData[4];

        if (!empty($registration_id) && $registration_id !=null && !empty($id) && $id !=null) {

            $caseData = array();
            $caseData['court_type'] =$court_type;

            $subordinate_data = $this->View_model->get_sub_qj_hc_court_details($registration_id,$id);
            if(isset($subordinate_data[0]['sub_qj_high']) && !empty($subordinate_data[0]['sub_qj_high']) && $subordinate_data[0]['sub_qj_high'] == 3){
                $case_type_id = !empty($subordinate_data[0]['case_type']) ? (int)$subordinate_data[0]['case_type'] : NULL;
                $est_code = !empty($subordinate_data[0]['bench_code']) ? $subordinate_data[0]['bench_code'] : NULL;
                $hc_id= !empty($subordinate_data[0]['court_id']) ? (int)$subordinate_data[0]['court_id'] : NULL;
                $params = array();
                if(isset($hc_id) && !empty($hc_id)){
                    $params['type'] = 1;
                    $params['hc_id'] = $hc_id;
                    $highCourtDetails = $this->View_model->getCourtDetails($params);
                    if(isset($highCourtDetails[0]['name']) && !empty($highCourtDetails[0]['name'])){
                        $highCourtName = $highCourtDetails[0]['name'];
                        $subordinate_data['0']['highCourtName'] = $highCourtName;
                    }
                }
                if(isset($hc_id) && !empty($hc_id) && isset($est_code) && !empty($est_code)){
                    $params['type'] = 2;
                    $params['hc_id'] = $hc_id;
                    $params['est_code'] = $est_code;
                    $benchDetails = $this->View_model->getCourtDetails($params);
                    if(isset($benchDetails[0]['name']) && !empty($benchDetails[0]['name'])){
                        $benchName = $benchDetails[0]['name'];
                        $subordinate_data['0']['benchName'] = $benchName;
                    }
                }
                if(isset($case_type_id) && !empty($case_type_id) && isset($est_code) && !empty($est_code)){
                    $params['type'] = 3;
                    $params['id'] = $case_type_id;
                    $params['est_code'] = $est_code;
                    $caseDetails = $this->View_model->getCourtDetails($params);
                    if(isset($caseDetails[0]['type_name']) && !empty($caseDetails[0]['type_name'])){
                        $caseName = $caseDetails[0]['type_name'];
                        $subordinate_data['0']['caseName'] = $caseName;
                    }
                }
            }


            if(!empty($court_type)){
                $court_type =  (int)$court_type;
                $params = array();
                switch($court_type){
                    case 1: //District Court or Lower Court
                         $est_code = $subordinate_data[0]['lower_court_code'];
                        if (!empty($est_code)) {
                            $params=array('type'=>4,'est_code'=>$est_code,);
                            $data['case_type_list'] = $this->common_model->getSubordinateCourtData($params);
                        }
                        break;
                    case 3: //High Court
                        $est_code = $subordinate_data[0]['bench_code']; //bench bench_code
                        $type = 3; //case list
                        $params['type']= $type;
                        $params['est_code'] = $est_code;
                        if(isset($est_code) && !empty($est_code)){
                            $data['case_type_list'] = $this->Common_model->getHighCourtData($params);
                        }

                        break;
                    case 4: //Supreme Court
                        $data['case_type_list'] = $this->Dropdown_list_model->get_sci_case_type();
                        break;
                    case 5://State Agency
                        echo $agency_id = $subordinate_data[0]['lower_dist_code'];
                        echo $court_type;
                        if (!empty($agency_id) && !empty($court_type)) {
                            $data['case_type_list'] = $this->Dropdown_list_model->icmis_state_agencies_case_types($agency_id,$court_type);
                        }
                        break;
                    default:
                }
            }
            $data['caseData'] = $caseData;
            $data['subordinate_court_details']=$subordinate_data;
            $this->load->view('caveat/earlier_court_updateModal', $data);
        }else{
            echo 'Some error ! Please Try again';
        }
    }

    public function update_subordinate_court() {
        $id=url_decryption($_POST['earlier_court_id']);
        $registration_id=url_decryption($_POST['registration_id']);
        $radio_selected_court=$_POST['radio_selected_court'];
        if ($_POST['radio_selected_court'] == '1') { //District Court or Lower Court

            $case_type_ids = explode('##', url_decryption($_POST['dc_case_type_id']));
            $case_type_name=$_POST['dc_case_type_name'];

        } elseif ($_POST['radio_selected_court'] == '3') { //High Court

            $case_type_ids = explode('##', url_decryption($_POST['case_type_id']));
            $case_type_name=$_POST['hc_case_type_name'];

        } elseif ($_POST['radio_selected_court'] == '4') { //Supreme Court

            $case_type_ids = explode('#$', url_decryption($_POST['sci_case_type_id']));
            $case_type_name=$_POST['sci_case_type_name'];

        } else if($_POST['radio_selected_court'] == '5'){ //State Agency

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
            'case_type' =>$case_type_id,
            'casetype_name' =>$case_type_name,

        );

        if (isset($registration_id) && !empty($registration_id) && !empty($id) && $id !=null) {
            $status = $this->New_case_model->update_subordinate_court_info($id,$registration_id, $update_case_details,"");
            if ($status) {
                echo '2@@@' . htmlentities('Earlier Court Update successfully!', ENT_QUOTES);
            } else {
                echo '1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
            }
        }

    }
}
