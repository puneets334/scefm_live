<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CaseDetails extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('newcaseQF/common/Common_model');
        $this->load->model('newcaseQF/New_case_model');
        $this->load->model('newcaseQF/Dropdown_list_model');
        $this->load->model('newcaseQF/Get_details_model');

        $this->load->model('newcaseQF/uploadDocuments/UploadDocs_model');
        $this->load->model('newcaseQF/documentIndex/DocumentIndex_model');
        $this->load->library('webservices/highcourt_webservices');
        $this->load->model('newcaseQF/DeleteSubordinateCourt_model');
        $this->load->helper('file');
    }

    public function index() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('adminDashboard');
            exit(0);
        }
        //$stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage ,E_REJECTED_STAGE);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            redirect('newcaseQF/view');
            exit(0);
        }
        $caseData = array();
        $caseData['court_type'] =NULL;
        $data['sc_case_type'] = $this->Dropdown_list_model->get_sci_case_type();
        $data['main_subject_cat'] = $this->Dropdown_list_model->get_main_subject_category();
        //start Petitioner
        $data['state_listP'] = $this->Dropdown_list_model->get_address_state_list();
        $countryList = $this->Dropdown_list_model->getCountryList();
        $data['countryList'] = !empty($countryList) ? $countryList : NULL;
        //end Petitioner

        $court_type = NULL;
        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $data['new_case_details'] = $this->Get_details_model->get_new_case_details($registration_id);
            $court_type = (!empty($data['new_case_details'][0]['court_type']) && isset($data['new_case_details'][0]['court_type'])) ? (int)$data['new_case_details'][0]['court_type'] : NULL;
            $state_id = (!empty($data['new_case_details'][0]['state_id']) && isset($data['new_case_details'][0]['state_id'])) ? $data['new_case_details'][0]['state_id'] : NULL;
            $district_id = (!empty($data['new_case_details'][0]['district_id']) && isset($data['new_case_details'][0]['district_id'])) ? $data['new_case_details'][0]['district_id'] : NULL;
            $estab_code = (!empty($data['new_case_details'][0]['estab_code']) && isset($data['new_case_details'][0]['estab_code'])) ? $data['new_case_details'][0]['estab_code'] : NULL;
            $estab_id = (!empty($data['new_case_details'][0]['estab_id']) && isset($data['new_case_details'][0]['estab_id'])) ? $data['new_case_details'][0]['estab_id'] : NULL;
            $supreme_court_state = (!empty($data['new_case_details'][0]['supreme_court_state']) && isset($data['new_case_details'][0]['supreme_court_state'])) ? $data['new_case_details'][0]['supreme_court_state'] : NULL;
            $supreme_court_bench = (!empty($data['new_case_details'][0]['supreme_court_bench']) && isset($data['new_case_details'][0]['supreme_court_bench'])) ? $data['new_case_details'][0]['supreme_court_bench'] : NULL;
            if(isset($court_type) && !empty($court_type)) {
                switch ($court_type) {
                    case 1:
                        $high_courts = $this->Dropdown_list_model->high_courts();
                        $data['high_court_drop_down'] = $high_courts;
                        $params = array();
                        $params['type'] = 2;
                        $params['hc_id'] = (int)$estab_id;
                        $data['high_court_bench'] = $this->Common_model->getHighCourtData($params);
                        // echo '<pre>'; print_r($data['new_case_details'][0]); exit;
                        break;
                    case 3:
                        $state_id = !empty($data['new_case_details'][0]['state_id']) ? (int)$data['new_case_details'][0]['state_id'] : NULL;
                        $params = array('type' => 1);
                        $state_list = $this->Common_model->getSubordinateCourtData($params);
                        $data['state_list'] = $state_list;
                        $params = array('type' => 2, 'state_code' => $state_id);
                        $district_list = $this->Common_model->getSubordinateCourtData($params);
                        $data['district_list'] = $district_list;
                        break;
                    case 4:
                        $supreme_court_stateArr = array();
                        $supreme_court_stateArr[] = array('id' => 490506, 'name' => 'DELHI');
                        $data['supreme_court_state'] = $supreme_court_stateArr;
                        $supreme_court_benchArr = array();
                        $supreme_court_benchArr[] = array('id' => '10000', 'name' => 'DELHI');
                        $data['supreme_court_bench'] = $supreme_court_benchArr;
                        break;
                    case 5:
                        $state_agency_list = $this->Dropdown_list_model->get_states_list();
                        $data['state_agency_list'] = $state_agency_list;
                        $state_id = !empty($data['new_case_details'][0]['state_id']) ? (int)$data['new_case_details'][0]['state_id'] : NULL;
                        $court_type = 5;
                        $agencies = $this->Dropdown_list_model->icmis_state_agencies($state_id, $court_type);
                        $data['agencies'] = $agencies;
                        break;
                    default:
                }
            }
            $this->Get_details_model->get_case_table_ids($registration_id);

                $uploaded_docs = $this->UploadDocs_model->get_uploaded_pdfs($registration_id);
                if (!empty($uploaded_docs)){
                    $data['uploaded_docs'] = $uploaded_docs;
                }else{
                    $data['uploaded_docs']='';
                }


        }
        else{
            $high_courts = $this->Dropdown_list_model->high_courts();
            $data['high_court_drop_down'] = $high_courts;
        }
        $this->load->view('templates/header');
        $this->load->view('newcaseQF/new_case_view', $data);
        $this->load->view('templates/footer');
    }
    public function getSubSubjectCategory(){
        $subj_sub_cat_1 = explode('##', url_decryption(escape_data($this->input->post("main_category"))));
        var_dump($subj_sub_cat_1);
    }

    public function add_case_details_stop() {


        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('adminDashboard');
            exit(0);
        }

        $stages_array = array('', Draft_Stage, Initial_Defected_Stage);

        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            echo '1@@@' . htmlentities("Update in Petitioner details can be done only at 'Draft', 'For Compliance' stages'", ENT_QUOTES);
            exit(0);
        }
        /*start upload duc*/
        if ($msg = isValidPDF('pdfDocFile')) {
            echo '1@@@' . htmlentities($msg, ENT_QUOTES);
            exit(0);
        }
        // (A) ERROR - NO FILE UPLOADED
        if (!isset($_FILES["pdfDocFile"])) {
            // exit("No file uploaded");
            echo '1@@@' . htmlentities('Upload Draft Petition file is required', ENT_QUOTES);
            exit(0);
        }
        $accept = ["application/pdf"];
        $upmime = strtolower($_FILES["pdfDocFile"]["type"]);
        $source = $_FILES["pdfDocFile"]["tmp_name"];
        $destination = $_FILES["pdfDocFile"]["name"];
        if (in_array($upmime, $accept)) {
            $doc_title= escape_data($this->input->post("doc_title"));
            echo '2@@@' . htmlentities('Document ready to upload '.$doc_title, ENT_QUOTES);
            exit(0);
        } else {
            echo '1@@@' . htmlentities('Only PDF are allowed', ENT_QUOTES);
            exit(0);
        }
        /*end upload duc*/
        $court_fee_calculation_helper_flag='';
        $matrimonialCheck=$this->input->post("matrimonialCheck");$matrimonial=$this->input->post("matrimonial");
        if (!empty($matrimonialCheck) && $matrimonialCheck=='matrimonialCheck' && empty($matrimonial)){
            $this->form_validation->set_rules('matrimonial', 'Matrimonial Type', 'required');
        }elseif (!empty($matrimonialCheck) && $matrimonialCheck=='matrimonialCheck' && !empty($matrimonial)){
            $court_fee_calculation_helper_flag=$matrimonial;
        }
        $no_of_petitioners=trim($this->input->post("no_of_petitioners"));
        $no_of_respondents=trim($this->input->post("no_of_respondents"));
        if ($no_of_petitioners ==0){
            echo '1@@@' . htmlentities('Number of Petitioner(s) should be greater than 0.'); exit();
        }
        if ($no_of_respondents ==0){
            echo '1@@@' . htmlentities('Number of Respondent(s) should be greater than 0.'); exit();
        }
        $this->form_validation->set_rules('no_of_petitioners', 'No of Petitioner', 'trim|min_length[1]|numeric|is_required');
        $this->form_validation->set_rules('no_of_respondents', 'No of Respondents', 'trim|min_length[1]|numeric|is_required');
        $this->form_validation->set_rules('cause_pet', 'Cause Title Petitioner', 'required|trim|min_length[3]|max_length[99]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters');
        $this->form_validation->set_rules('cause_res', 'Cause Title Respondent', 'required|trim|min_length[3]|max_length[99]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters');
        $this->form_validation->set_rules('sc_case_type', 'Case Type', 'required|trim|is_required|validate_encrypted_value');
        $this->form_validation->set_rules('sc_sp_case_type_id', 'Special Case Type', 'required|trim|in_list[1,6,7]');
        $this->form_validation->set_rules('subj_cat_main', 'Main Category', 'required|trim|is_required|validate_encrypted_value');
        $this->form_validation->set_rules('subj_sub_cat_1', 'Sub Category 1', 'trim|is_required|validate_encrypted_value');
        $this->form_validation->set_rules('subj_sub_cat_2', 'Sub Category 2', 'trim|is_required|validate_encrypted_value');
        $this->form_validation->set_rules('subj_sub_cat_3', 'Sub Category 3', 'trim|is_required|validate_encrypted_value');
        // $this->form_validation->set_rules('question_of_law', 'Question of Law', 'required|trim|min_length[10]|max_length[450]|validate_alpha_numeric_with_special_characters');
        // $this->form_validation->set_rules('grounds', 'Grounds in brief', 'required|trim|min_length[10]|max_length[450]|validate_alpha_numeric_with_special_characters');
        // $this->form_validation->set_rules('interim_grounds', 'Interim Grounds', 'required|trim|min_length[10]|max_length[450]|validate_alpha_numeric_with_special_characters');
        // $this->form_validation->set_rules('main_prayer', 'Main Prayer', 'required|trim|min_length[10]|max_length[450]|validate_alpha_numeric_with_special_characters');
        // $this->form_validation->set_rules('interim_relief', 'Interim Relief', 'required|trim|min_length[10]|max_length[450]|validate_alpha_numeric_with_special_characters');
        $court_type = !empty($_POST['court_name']) ? (int)$_POST['court_name'] : NULL;
        if(isset($court_type) && !empty($court_type)){
            switch($court_type){
                case 1:
                    $this->form_validation->set_rules('high_courtname', 'High court name', 'required|trim');
                    $this->form_validation->set_rules('high_court_bench_name', 'High court bench name', 'trim|required');
                    break;
                case 3:
                    $this->form_validation->set_rules('district_court_state_name', 'State name', 'required|trim');
                    $this->form_validation->set_rules('district_court_district_name', 'District name', 'trim|required');
                    break;
                case 4:
                    $this->form_validation->set_rules('supreme_state_name', 'State name', 'required|trim');
                    $this->form_validation->set_rules('supreme_bench_name', 'Bench name', 'trim|required');
                    break;
                case 5:
                    $this->form_validation->set_rules('state_agency', 'State name', 'required|trim');
                    $this->form_validation->set_rules('state_agency_name', 'Agency name', 'trim|required');
                    break;
                default:
            }
        }
        $this->form_validation->set_error_delimiters('<br/>', '');
        if (!$this->form_validation->run()) {
            echo '3@@@';
            echo form_error('no_of_petitioner') .form_error('no_of_respondent') .form_error('cause_pet') . form_error('cause_res') . form_error('sc_case_type') . form_error('sc_sp_case_type_id') .
                form_error('subj_cat_main') . form_error('subj_sub_cat_1') . form_error('subj_sub_cat_2') . form_error('subj_sub_cat_3') .
                form_error('question_of_law') . form_error('grounds') . form_error('interim_grounds') . form_error('main_prayer') . form_error('interim_relief'). form_error('matrimonial');
            if(isset($court_type) && !empty($court_type)){
                switch($court_type){
                    case 1:
                        echo form_error('high_courtname') .form_error('high_court_bench_name');
                        break;
                    case 3:
                        echo form_error('district_court_state_name') .form_error('district_court_district_name');
                        break;
                    case 4:
                        echo form_error('supreme_state_name') .form_error('supreme_bench_name');
                        break;
                    case 5:
                        echo form_error('state_agency') .form_error('state_agency_name');
                        break;
                    default:
                }
            }
            exit(0);
        } else {
            $cause_title = strtoupper(escape_data($this->input->post("cause_pet"))) . ' Vs. ';
            $cause_title .= strtoupper(escape_data($this->input->post("cause_res")));

            $subj_cat_main = explode('##', url_decryption(escape_data($this->input->post("subj_cat_main"))));
            $subject_cat = $subj_cat_main[0];

            $subj_sub_cat_1 = explode('##', url_decryption(escape_data($this->input->post("subj_sub_cat_1"))));
            $subject_cat = isset($subj_sub_cat_1[0]) && !empty($subj_sub_cat_1[0]) ? $subj_sub_cat_1[0] : $subject_cat;
            $subj_sub_cat_1[0] = isset($subj_sub_cat_1[0]) && !empty($subj_sub_cat_1[0]) ? $subj_sub_cat_1[0] : 0;

            $subj_sub_cat_2 = explode('##', url_decryption(escape_data($this->input->post("subj_sub_cat_2"))));
            $subject_cat = isset($subj_sub_cat_2[0]) && !empty($subj_sub_cat_2[0]) ? $subj_sub_cat_2[0] : $subject_cat;
            $subj_sub_cat_2[0] = isset($subj_sub_cat_2[0]) && !empty($subj_sub_cat_2[0]) ? $subj_sub_cat_2[0] : 0;

            $subj_sub_cat_3 = explode('##', url_decryption(escape_data($this->input->post("subj_sub_cat_3"))));
            $subject_cat = isset($subj_sub_cat_3[0]) && !empty($subj_sub_cat_3[0]) ? $subj_sub_cat_3[0] : $subject_cat;
            $subj_sub_cat_3[0] = isset($subj_sub_cat_3[0]) && !empty($subj_sub_cat_3[0]) ? $subj_sub_cat_3[0] : 0;

            $question_of_law = !empty(strtoupper(escape_data($this->input->post("question_of_law")))) ? strtoupper(escape_data($this->input->post("question_of_law"))) : NULL;
            $grounds = !empty(strtoupper(escape_data($this->input->post("grounds")))) ? strtoupper(escape_data($this->input->post("grounds"))) : NULL;
            $interim_grounds = !empty(strtoupper(escape_data($this->input->post("interim_grounds")))) ? strtoupper(escape_data($this->input->post("interim_grounds"))) : NULL;
            $main_prayer = !empty(strtoupper(escape_data($this->input->post("main_prayer")))) ? strtoupper(escape_data($this->input->post("main_prayer"))) : NULL;
            $interim_relief = !empty(strtoupper(escape_data($this->input->post("interim_relief")))) ? strtoupper(escape_data($this->input->post("interim_relief"))) : NULL;
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $curr_dt_time = date('Y-m-d H:i:s');
            $state_id=NULL;
            $district_id=NULL;
            $agency_code=NULL;
            $estab_code=NULL;
            $estab_id=NULL;
            $supreme_court_state =NULL;
            $superem_court_bench=NULL;
            if(!empty($this->input->post("high_courtname")) && $court_type == 1){
                $high_courtname = explode('##', url_decryption(escape_data($this->input->post("high_courtname"))));
                $estab_id = !empty($high_courtname[0]) ? $high_courtname[0] : NULL;
            }
            if(!empty($this->input->post("high_court_bench_name")) && $court_type == 1){
                $high_court_bench_name = explode('##', url_decryption(escape_data($this->input->post("high_court_bench_name"))));
                $estab_code = !empty($high_court_bench_name[2]) ? $high_court_bench_name[2] : NULL;
                $_POST['state_agency_name'] = '';
            }
            if(!empty($this->input->post("district_court_state_name")) && $court_type == 3){
                $district_court_state_name = explode('#$', url_decryption(escape_data($this->input->post("district_court_state_name"))));
                $state_id = !empty($district_court_state_name[0]) ? $district_court_state_name[0] : NULL;
            }
            if(!empty($this->input->post("district_court_district_name")) && $court_type == 3){
                $district_court_district_name = explode('#$', url_decryption(escape_data($this->input->post("district_court_district_name"))));
                $district_id = !empty($district_court_district_name[0]) ? $district_court_district_name[0] : NULL;
            }
            if(!empty($this->input->post("supreme_state_name")) && $court_type == 4){
                $supreme_court_state = !empty($this->input->post("supreme_state_name")) ? $this->input->post("supreme_state_name") : NULL;
            }
            if(!empty($this->input->post("supreme_bench_name")) && $court_type == 4){
                $superem_court_bench = !empty($this->input->post("supreme_bench_name")) ? $this->input->post("supreme_bench_name") : NULL;
            }
            if(!empty($this->input->post("state_agency")) && $court_type == 5){
                $state_agency = explode('#$', url_decryption(escape_data($this->input->post("state_agency"))));
                $state_id = !empty($state_agency[0]) ? $state_agency[0] : NULL;
                $agency_code = 1;
            }
            if(!empty($this->input->post("state_agency_name")) && $court_type == 5){
                $state_agency_name = explode('##', url_decryption(escape_data($this->input->post("state_agency_name"))));
                $estab_id =  !empty($state_agency_name[0]) ? $state_agency_name[0] : NULL;

            }

            if($this->input->post("datesignjail")==''){
                $jailsignDt=null;
            }else{
                $jailsignDt=escape_data($this->input->post("datesignjail"));
            }


            $case_details = array(
                'cause_title' => $cause_title,
                'sc_case_type_id' => url_decryption(escape_data($this->input->post("sc_case_type"))),
                'sc_sp_case_type_id' => escape_data($this->input->post("sc_sp_case_type_id")),
                'subj_main_cat' => $subj_cat_main[0],
                'subj_sub_cat_1' => $subj_sub_cat_1[0],
                'subj_sub_cat_2' => $subj_sub_cat_2[0],
                'subj_sub_cat_3' => $subj_sub_cat_3[0],
                'subject_cat' => $subject_cat,
                'question_of_law' => $question_of_law,
                'grounds' => $grounds,
                'grounds_interim' => $interim_grounds,
                'main_prayer' => $main_prayer,
                'interim_relief' => $interim_relief,
                'court_fee_calculation_helper_flag' => $court_fee_calculation_helper_flag,
                'no_of_petitioners' => $no_of_petitioners,
                'no_of_respondents' => $no_of_respondents,
                'keywords' => NULL,
                'state_id'=>$state_id,
                'district_id'=>$district_id,
                'agency_code'=> $agency_code,
                'estab_code'=>$estab_code,
                'estab_id'=>$estab_id,
                'supreme_court_state'=>$supreme_court_state,
                'supreme_court_bench'=>$superem_court_bench,
                'court_type'=>$court_type,
                'jail_signature_date'=>$jailsignDt
            );
            if (isset($registration_id) && !empty($registration_id) && isset($_SESSION['case_table_ids']['case_details_id']) && !empty($_SESSION['case_table_ids']['case_details_id'])) {

                $case_details_update_data = array(
                    'updated_on' => $curr_dt_time,
                    'updated_by' => $this->session->userdata['login']['id'],
                    'updated_by_ip' => $_SERVER['REMOTE_ADDR']
                );

                $case_details = array_merge($case_details, $case_details_update_data);

                //UPDATE CASE DETAILS
                $p_r_type_petitioners='P'; $p_r_type_respondents='R'; $step=11;
                $breadcrumb_statusGet = explode(',', $_SESSION['efiling_details']['breadcrumb_status']);
                $breadcrumb_status=count($breadcrumb_statusGet);
                $total_petitioners = get_extra_party_P_or_R($p_r_type_petitioners);
                $total_respondents = get_extra_party_P_or_R($p_r_type_respondents);
                if ($step >= $breadcrumb_status) {
                    if ($total_petitioners > $no_of_petitioners) {

                        echo '1@@@' . htmlentities('You have already entered ' . $total_petitioners . ' petitioners(s),please delete petitioners to change no of petitioners in case details', ENT_QUOTES);
                        exit();
                    }
                    if ($total_respondents > $no_of_respondents) {

                        echo '1@@@' . htmlentities('You have already entered ' . $total_respondents . ' respondents(s),please delete respondents to change no of respondents in case details', ENT_QUOTES);
                        exit();
                    }
                }

                $status = $this->New_case_model->add_update_case_details($registration_id, $case_details, NEW_CASE_CASE_DETAIL, $_SESSION['case_table_ids']['case_details_id']);

                if ($status) {
                    //SESSION efiling_details
                    $this->Common_model->get_efiling_num_basic_Details($registration_id);
                    reset_affirmation($registration_id);
                    echo '2@@@' . htmlentities('Case details updated successfully!', ENT_QUOTES) . '@@@' . base_url('newcase/defaultController/' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_NEW_CASE . '#' . $_SESSION['efiling_details']['stage_id'])));
                } else {
                    echo '1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
                }
            } else {

                //GENERATE NEW EFILING NUM AND ADD NEW CASE DETAILS, CREATE DRAFT STAGE
                $result = $this->New_case_model->generate_efil_num_n_add_case_details($case_details);

                if ($result['registration_id']) {
                    //SESSION efiling_details
                    $this->Common_model->get_efiling_num_basic_Details($result['registration_id']);

                    $user_name = $_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'];
                    $efiling_num = $_SESSION['efiling_details']['efiling_no'];

                    $sentSMS = "Efiling No. " . efile_preview($efiling_num) . " generated from your efiling account & still pending for final submit. - Supreme Court of India";
                    $subject = "Efiling No. " . efile_preview($efiling_num) . " generated from your efiling account";

                    /* $Petsubject = "Efiling No. " . efile_preview($efiling_num) . " generated from for your petition";
                      $sentPetSMS = "Efiling No. " . efile_preview($efiling_num) . "   is generated for your petition filed by your Advocate" . $user_name . " and still pending for final submission.";

                      if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE) {

                      $pet_user_name = $data['pet_name'];
                      if (!empty($data['pet_mobile'])) {
                      send_petitioner_mobile_sms($data['pet_mobile'], $sentPetSMS);
                      }if (!empty($data['pet_email'])) {
                      send_petitioner_mail_msg($data['pet_email'], $Petsubject, $sentPetSMS, $pet_user_name);
                      }
                      } */

                    send_mobile_sms($_SESSION['login']['mobile_number'], $sentSMS,SCISMS_Efiling_No_Generated);
                    send_mail_msg($_SESSION['login']['emailid'], $subject, $sentSMS, $user_name);

                    echo '2@@@' . htmlentities('Case details added successfully', ENT_QUOTES) . '@@@' . base_url('newcase/defaultController/' . url_encryption(trim($result['registration_id'] . '#' . E_FILING_TYPE_NEW_CASE . '#' . Draft_Stage)));
                } else {
                    echo '1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
                }
            }
        }
    }
    public function add_case_details() {


        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('adminDashboard');
            exit(0);
        }

        $stages_array = array('', Draft_Stage, Initial_Defected_Stage);

        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            echo '1@@@' . htmlentities("Update in Petitioner details can be done only at 'Draft', 'For Compliance' stages'", ENT_QUOTES);
            exit(0);
        }
        /*start upload duc*/
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        if (empty($registration_id) && !isset($_SESSION['case_table_ids']['case_details_id']) && empty($_SESSION['case_table_ids']['case_details_id'])) {
            if ($msg = isValidPDF('pdfDocFile')) {
                echo '1@@@' . htmlentities($msg, ENT_QUOTES);
                exit(0);
            }
            // (A) ERROR - NO FILE UPLOADED
            if (!isset($_FILES["pdfDocFile"])) {
                // exit("No file uploaded");
                echo '1@@@' . htmlentities('Upload Draft Petition file is required', ENT_QUOTES);
                exit(0);
            }
            $accept = ["application/pdf"];
            $upmime = strtolower($_FILES["pdfDocFile"]["type"]);
            $source = $_FILES["pdfDocFile"]["tmp_name"];
            $destination = $_FILES["pdfDocFile"]["name"];
            if (in_array($upmime, $accept)) {
                /* $doc_title= escape_data($this->input->post("doc_title"));
                 echo '2@@@' . htmlentities('Document ready to upload '.$doc_title, ENT_QUOTES);
                 exit(0);*/
            } else {
                echo '1@@@' . htmlentities('Only PDF are allowed', ENT_QUOTES);
                exit(0);
            }
        }
        /*end upload duc*/
        $court_fee_calculation_helper_flag='';
        $matrimonialCheck=$this->input->post("matrimonialCheck");$matrimonial=$this->input->post("matrimonial");
        if (!empty($matrimonialCheck) && $matrimonialCheck=='matrimonialCheck' && empty($matrimonial)){
            $this->form_validation->set_rules('matrimonial', 'Matrimonial Type', 'required');
        }elseif (!empty($matrimonialCheck) && $matrimonialCheck=='matrimonialCheck' && !empty($matrimonial)){
            $court_fee_calculation_helper_flag=$matrimonial;
        }
        $no_of_petitioners=trim($this->input->post("no_of_petitioners"));
        $no_of_respondents=trim($this->input->post("no_of_respondents"));
        if ($no_of_petitioners ==0){
            echo '1@@@' . htmlentities('Number of Petitioner(s) should be greater than 0.'); exit();
        }
        if ($no_of_respondents ==0){
            echo '1@@@' . htmlentities('Number of Respondent(s) should be greater than 0.'); exit();
        }
        $this->form_validation->set_rules('no_of_petitioners', 'No of Petitioner', 'trim|min_length[1]|numeric|is_required');
        $this->form_validation->set_rules('no_of_respondents', 'No of Respondents', 'trim|min_length[1]|numeric|is_required');
        $this->form_validation->set_rules('cause_pet', 'Cause Title Petitioner', 'required|trim|min_length[3]|max_length[99]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters');
        $this->form_validation->set_rules('cause_res', 'Cause Title Respondent', 'required|trim|min_length[3]|max_length[99]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters');
        $this->form_validation->set_rules('sc_case_type', 'Case Type', 'required|trim|is_required|validate_encrypted_value');
        $this->form_validation->set_rules('sc_sp_case_type_id', 'Special Case Type', 'required|trim|in_list[1,6,7]');
        $this->form_validation->set_rules('subj_cat_main', 'Main Category', 'required|trim|is_required|validate_encrypted_value');
        $this->form_validation->set_rules('subj_sub_cat_1', 'Sub Category 1', 'trim|is_required|validate_encrypted_value');
        //$this->form_validation->set_rules('subj_sub_cat_2', 'Sub Category 2', 'trim|is_required|validate_encrypted_value');
        //$this->form_validation->set_rules('subj_sub_cat_3', 'Sub Category 3', 'trim|is_required|validate_encrypted_value');

        // $this->form_validation->set_rules('question_of_law', 'Question of Law', 'required|trim|min_length[10]|max_length[450]|validate_alpha_numeric_with_special_characters');
        // $this->form_validation->set_rules('grounds', 'Grounds in brief', 'required|trim|min_length[10]|max_length[450]|validate_alpha_numeric_with_special_characters');
        // $this->form_validation->set_rules('interim_grounds', 'Interim Grounds', 'required|trim|min_length[10]|max_length[450]|validate_alpha_numeric_with_special_characters');
        // $this->form_validation->set_rules('main_prayer', 'Main Prayer', 'required|trim|min_length[10]|max_length[450]|validate_alpha_numeric_with_special_characters');
        // $this->form_validation->set_rules('interim_relief', 'Interim Relief', 'required|trim|min_length[10]|max_length[450]|validate_alpha_numeric_with_special_characters');
        $court_type = !empty($_POST['court_name']) ? (int)$_POST['court_name'] : NULL;
        if(isset($court_type) && !empty($court_type)){
            switch($court_type){
                case 1:
                    $this->form_validation->set_rules('high_courtname', 'High court name', 'required|trim');
                    $this->form_validation->set_rules('high_court_bench_name', 'High court bench name', 'trim|required');
                    break;
                case 3:
                    $this->form_validation->set_rules('district_court_state_name', 'State name', 'required|trim');
                    $this->form_validation->set_rules('district_court_district_name', 'District name', 'trim|required');
                    break;
                case 4:
                    $this->form_validation->set_rules('supreme_state_name', 'State name', 'required|trim');
                    $this->form_validation->set_rules('supreme_bench_name', 'Bench name', 'trim|required');
                    break;
                case 5:
                    $this->form_validation->set_rules('state_agency', 'State name', 'required|trim');
                    $this->form_validation->set_rules('state_agency_name', 'Agency name', 'trim|required');
                    break;
                default:
            }
        }
        $this->form_validation->set_error_delimiters('<br/>', '');
        if (!$this->form_validation->run()) {
            echo '3@@@';
            echo form_error('no_of_petitioner') .form_error('no_of_respondent') .form_error('cause_pet') . form_error('cause_res') . form_error('sc_case_type') . form_error('sc_sp_case_type_id') .
                form_error('subj_cat_main') . form_error('subj_sub_cat_1') . form_error('subj_sub_cat_2') . form_error('subj_sub_cat_3') .
                form_error('question_of_law') . form_error('grounds') . form_error('interim_grounds') . form_error('main_prayer') . form_error('interim_relief'). form_error('matrimonial');
            if(isset($court_type) && !empty($court_type)){
                switch($court_type){
                    case 1:
                        echo form_error('high_courtname') .form_error('high_court_bench_name');
                        break;
                    case 3:
                        echo form_error('district_court_state_name') .form_error('district_court_district_name');
                        break;
                    case 4:
                        echo form_error('supreme_state_name') .form_error('supreme_bench_name');
                        break;
                    case 5:
                        echo form_error('state_agency') .form_error('state_agency_name');
                        break;
                    default:
                }
            }
            exit(0);
        } else {
            $cause_title = strtoupper(escape_data($this->input->post("cause_pet"))) . ' Vs. ';
            $cause_title .= strtoupper(escape_data($this->input->post("cause_res")));

            $subj_cat_main = explode('##', url_decryption(escape_data($this->input->post("subj_cat_main"))));
            $subject_cat = $subj_cat_main[0];

            $subj_sub_cat_1 = explode('##', url_decryption(escape_data($this->input->post("subj_sub_cat_1"))));
            $subject_cat = isset($subj_sub_cat_1[0]) && !empty($subj_sub_cat_1[0]) ? $subj_sub_cat_1[0] : $subject_cat;
            $subj_sub_cat_1[0] = isset($subj_sub_cat_1[0]) && !empty($subj_sub_cat_1[0]) ? $subj_sub_cat_1[0] : 0;

            $subj_sub_cat_2 = explode('##', url_decryption(escape_data($this->input->post("subj_sub_cat_2"))));
            $subject_cat = isset($subj_sub_cat_2[0]) && !empty($subj_sub_cat_2[0]) ? $subj_sub_cat_2[0] : $subject_cat;
            $subj_sub_cat_2[0] = isset($subj_sub_cat_2[0]) && !empty($subj_sub_cat_2[0]) ? $subj_sub_cat_2[0] : 0;

            $subj_sub_cat_3 = explode('##', url_decryption(escape_data($this->input->post("subj_sub_cat_3"))));
            $subject_cat = isset($subj_sub_cat_3[0]) && !empty($subj_sub_cat_3[0]) ? $subj_sub_cat_3[0] : $subject_cat;
            $subj_sub_cat_3[0] = isset($subj_sub_cat_3[0]) && !empty($subj_sub_cat_3[0]) ? $subj_sub_cat_3[0] : 0;

            $question_of_law = !empty(strtoupper(escape_data($this->input->post("question_of_law")))) ? strtoupper(escape_data($this->input->post("question_of_law"))) : NULL;
            $grounds = !empty(strtoupper(escape_data($this->input->post("grounds")))) ? strtoupper(escape_data($this->input->post("grounds"))) : NULL;
            $interim_grounds = !empty(strtoupper(escape_data($this->input->post("interim_grounds")))) ? strtoupper(escape_data($this->input->post("interim_grounds"))) : NULL;
            $main_prayer = !empty(strtoupper(escape_data($this->input->post("main_prayer")))) ? strtoupper(escape_data($this->input->post("main_prayer"))) : NULL;
            $interim_relief = !empty(strtoupper(escape_data($this->input->post("interim_relief")))) ? strtoupper(escape_data($this->input->post("interim_relief"))) : NULL;
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $curr_dt_time = date('Y-m-d H:i:s');
            $state_id=NULL;
            $district_id=NULL;
            $agency_code=NULL;
            $estab_code=NULL;
            $estab_id=NULL;
            $supreme_court_state =NULL;
            $superem_court_bench=NULL;
            if(!empty($this->input->post("high_courtname")) && $court_type == 1){
                $high_courtname = explode('##', url_decryption(escape_data($this->input->post("high_courtname"))));
                $estab_id = !empty($high_courtname[0]) ? $high_courtname[0] : NULL;
            }
            if(!empty($this->input->post("high_court_bench_name")) && $court_type == 1){
                $high_court_bench_name = explode('##', url_decryption(escape_data($this->input->post("high_court_bench_name"))));
                $estab_code = !empty($high_court_bench_name[2]) ? $high_court_bench_name[2] : NULL;
                $_POST['state_agency_name'] = '';
            }
            if(!empty($this->input->post("district_court_state_name")) && $court_type == 3){
                $district_court_state_name = explode('#$', url_decryption(escape_data($this->input->post("district_court_state_name"))));
                $state_id = !empty($district_court_state_name[0]) ? $district_court_state_name[0] : NULL;
            }
            if(!empty($this->input->post("district_court_district_name")) && $court_type == 3){
                $district_court_district_name = explode('#$', url_decryption(escape_data($this->input->post("district_court_district_name"))));
                $district_id = !empty($district_court_district_name[0]) ? $district_court_district_name[0] : NULL;
            }
            if(!empty($this->input->post("supreme_state_name")) && $court_type == 4){
                $supreme_court_state = !empty($this->input->post("supreme_state_name")) ? $this->input->post("supreme_state_name") : NULL;
            }
            if(!empty($this->input->post("supreme_bench_name")) && $court_type == 4){
                $superem_court_bench = !empty($this->input->post("supreme_bench_name")) ? $this->input->post("supreme_bench_name") : NULL;
            }
            if(!empty($this->input->post("state_agency")) && $court_type == 5){
                $state_agency = explode('#$', url_decryption(escape_data($this->input->post("state_agency"))));
                $state_id = !empty($state_agency[0]) ? $state_agency[0] : NULL;
                $agency_code = 1;
            }
            if(!empty($this->input->post("state_agency_name")) && $court_type == 5){
                $state_agency_name = explode('##', url_decryption(escape_data($this->input->post("state_agency_name"))));
                $estab_id =  !empty($state_agency_name[0]) ? $state_agency_name[0] : NULL;

            }

            if($this->input->post("datesignjail")==''){
                $jailsignDt=null;
            }else{
                $jailsignDt=escape_data($this->input->post("datesignjail"));
            }


            $case_details = array(
                'cause_title' => $cause_title,
                'sc_case_type_id' => url_decryption(escape_data($this->input->post("sc_case_type"))),
                'sc_sp_case_type_id' => escape_data($this->input->post("sc_sp_case_type_id")),
                'subj_main_cat' => $subj_cat_main[0],
                'subj_sub_cat_1' => $subj_sub_cat_1[0],
                'subj_sub_cat_2' => $subj_sub_cat_2[0],
                'subj_sub_cat_3' => $subj_sub_cat_3[0],
                'subject_cat' => $subject_cat,
                'question_of_law' => $question_of_law,
                'grounds' => $grounds,
                'grounds_interim' => $interim_grounds,
                'main_prayer' => $main_prayer,
                'interim_relief' => $interim_relief,
                'court_fee_calculation_helper_flag' => $court_fee_calculation_helper_flag,
                'no_of_petitioners' => $no_of_petitioners,
                'no_of_respondents' => $no_of_respondents,
                'keywords' => NULL,
                'state_id'=>$state_id,
                'district_id'=>$district_id,
                'agency_code'=> $agency_code,
                'estab_code'=>$estab_code,
                'estab_id'=>$estab_id,
                'supreme_court_state'=>$supreme_court_state,
                'supreme_court_bench'=>$superem_court_bench,
                'court_type'=>$court_type,
                'jail_signature_date'=>$jailsignDt
            );
            if (isset($registration_id) && !empty($registration_id) && isset($_SESSION['case_table_ids']['case_details_id']) && !empty($_SESSION['case_table_ids']['case_details_id'])) {

                $case_details_update_data = array(
                    'updated_on' => $curr_dt_time,
                    'updated_by' => $this->session->userdata['login']['id'],
                    'updated_by_ip' => $_SERVER['REMOTE_ADDR']
                );

                $case_details = array_merge($case_details, $case_details_update_data);

                //UPDATE CASE DETAILS
                $p_r_type_petitioners='P'; $p_r_type_respondents='R'; $step=11;
                $breadcrumb_statusGet = explode(',', $_SESSION['efiling_details']['breadcrumb_status']);
                $breadcrumb_status=count($breadcrumb_statusGet);
                $total_petitioners = get_extra_party_P_or_R($p_r_type_petitioners);
                $total_respondents = get_extra_party_P_or_R($p_r_type_respondents);
                if ($step >= $breadcrumb_status) {
                    if ($total_petitioners > $no_of_petitioners) {

                        echo '1@@@' . htmlentities('You have already entered ' . $total_petitioners . ' petitioners(s),please delete petitioners to change no of petitioners in case details', ENT_QUOTES);
                        exit();
                    }
                    if ($total_respondents > $no_of_respondents) {

                        echo '1@@@' . htmlentities('You have already entered ' . $total_respondents . ' respondents(s),please delete respondents to change no of respondents in case details', ENT_QUOTES);
                        exit();
                    }
                }

                $status = $this->New_case_model->add_update_case_details($registration_id, $case_details, NEW_CASE_CASE_DETAIL, $_SESSION['case_table_ids']['case_details_id']);

                if ($status) {
                    //SESSION efiling_details
                    $this->Common_model->get_efiling_num_basic_Details($registration_id);
                    reset_affirmation($registration_id);
                    echo '2@@@' . htmlentities('Case details updated successfully!', ENT_QUOTES) . '@@@' . base_url('newcase/defaultController/' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_NEW_CASE . '#' . $_SESSION['efiling_details']['stage_id'])));
                } else {
                    echo '1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
                }
            } else {

                //GENERATE NEW EFILING NUM AND ADD NEW CASE DETAILS, CREATE DRAFT STAGE
                $result = $this->New_case_model->generate_efil_num_n_add_case_details($case_details);

                if ($result['registration_id']) {
                    //SESSION efiling_details
                    $this->Common_model->get_efiling_num_basic_Details($result['registration_id']);

                    $user_name = $_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'];
                    $efiling_num = $_SESSION['efiling_details']['efiling_no'];

                    $sentSMS = "Efiling No. " . efile_preview($efiling_num) . " generated from your efiling account & still pending for final submit. - Supreme Court of India";
                    $subject = "Efiling No. " . efile_preview($efiling_num) . " generated from your efiling account";

                    /* $Petsubject = "Efiling No. " . efile_preview($efiling_num) . " generated from for your petition";
                      $sentPetSMS = "Efiling No. " . efile_preview($efiling_num) . "   is generated for your petition filed by your Advocate" . $user_name . " and still pending for final submission.";

                      if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE) {

                      $pet_user_name = $data['pet_name'];
                      if (!empty($data['pet_mobile'])) {
                      send_petitioner_mobile_sms($data['pet_mobile'], $sentPetSMS);
                      }if (!empty($data['pet_email'])) {
                      send_petitioner_mail_msg($data['pet_email'], $Petsubject, $sentPetSMS, $pet_user_name);
                      }
                      } */

                    send_mobile_sms($_SESSION['login']['mobile_number'], $sentSMS,SCISMS_Efiling_No_Generated);
                    send_mail_msg($_SESSION['login']['emailid'], $subject, $sentSMS, $user_name);

                    /*start upload duc data*/
                    $registration_id=$result['registration_id'];
                    $doc_title = strtoupper(escape_data($this->input->post("doc_title")));
                    $breadcrumb_step_no=NEW_CASE_CASE_DETAIL;
                    $pspdfkit_document_id='';

                    $doc_hash_value = hash_file('sha256', $_FILES['pdfDocFile']['tmp_name']);
                    $uploaded_on = date('Y-m-d H:i:s');

                    $sub_created_by = 0;
                    $uploaded_by = $_SESSION['login']['id'];
                    $ref_m_efiled_type_id = $_SESSION['efiling_details']['ref_m_efiled_type_id'];
                    $data_upload = array(
                        'registration_id' => $registration_id,
                        'efiled_type_id' => $ref_m_efiled_type_id,
                        'file_size' => $_FILES['pdfDocFile']['size'],
                        'file_type' => $_FILES['pdfDocFile']['type'],
                        'doc_title' => $doc_title,
                        'doc_hashed_value' => $doc_hash_value,
                        'uploaded_by' => $uploaded_by,
                        'uploaded_on' => $uploaded_on,
                        'upload_ip_address' => $_SERVER['REMOTE_ADDR'],
                        'sub_created_by' => $sub_created_by,
                        'pspdfkit_document_id'=>$pspdfkit_document_id
                    );
                    $result = $this->UploadDocs_model->upload_pdfs($data_upload, $_FILES['pdfDocFile']['tmp_name'], $breadcrumb_step_no);
                    /*end upload duc data*/

                    echo '2@@@' . htmlentities('Case details added successfully', ENT_QUOTES) . '@@@' . base_url('newcase/defaultController/' . url_encryption(trim($result['registration_id'] . '#' . E_FILING_TYPE_NEW_CASE . '#' . Draft_Stage)));
                } else {
                    echo '1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
                }
            }
        }
    }

    public function is_upload_pdf() {

            if ($msg = isValidPDF('pdfDocFile')) {
                echo '1@@@' . htmlentities($msg, ENT_QUOTES);
                exit(0);
            }
        $breadcrumb_step_no = NEW_CASE_UPLOAD_DOCUMENT;
        $doc_title = 'Draft Petition';

        // (A) ERROR - NO FILE UPLOADED
        if (!isset($_FILES["pdfDocFile"])) {
           // exit("No file uploaded");
            echo '1@@@' . htmlentities('Upload Draft Petition file is required', ENT_QUOTES);
            exit(0);
        }

// (B) FLAGS & "SETTINGS"
// (B1) ACCEPTED & UPLOADED MIME-TYPES
        $accept = ["application/pdf", "image/png"];
        $upmime = strtolower($_FILES["pdfDocFile"]["type"]);

// (B2) SOURCE & DESTINATION
        $source = $_FILES["pdfDocFile"]["tmp_name"];
        $destination = $_FILES["pdfDocFile"]["name"];

// (C) SAVE UPLOAD ONLY IF ACCEPTED FILE TYPE
        if (in_array($upmime, $accept)) {
            echo '2@@@' . htmlentities('Document ready to upload', ENT_QUOTES);
            exit(0);
        } else {
            echo '1@@@' . htmlentities('Only PDF are allowed', ENT_QUOTES);
            exit(0);
           // echo "$upmime NOT ACCEPTED";
        }

        echo '2@@@' . htmlentities('Document uploaded successfully', ENT_QUOTES);
        exit(0);


        }


}
