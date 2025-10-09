<?php

namespace App\Controllers\Newcase;

use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\Admin\EfilingActionModel;
use App\Models\Citation\CitationModel;
use App\Models\Common\CommonModel;
use App\Models\DocumentIndex\DocumentIndexDropDownModel;
use App\Models\DocumentIndex\DocumentIndexModel;
use App\Models\DocumentIndex\DocumentIndexSelectModel;
use App\Models\Filehashing\PdfHashTasksModel;
use App\Models\GetCISStatus\GetCISStatusModel;
use App\Models\IA\ViewModel;
use App\Models\Mentioning\ListingModel;
use App\Models\NewCase\ActSectionsModel;
use App\Models\NewCase\DropdownListModel;
use App\Models\NewCase\GetDetailsModel;
use App\Models\NewCase\NewCaseModel;
use stdClass;

class AutoDiary extends BaseController
{
    protected $Common_model;
    protected $Listing_model;
    protected $New_case_model;
    protected $Dropdown_list_model;
    protected $Get_details_model;
    protected $DocumentIndex_Select_model;
    protected $DocumentIndex_model;
    protected $PdfHashTasks_model;
    protected $efiling_webservices;
    protected $Efiling_action_model;
    protected $View_model;
    protected $DocumentIndex_DropDown_model;
    protected $encrypt;
    protected $Act_sections_model;
    protected $Citation_model;
    protected $Get_CIS_Status_model;
    protected $autoDiaryGeneration;
    public function __construct()
    {
        parent::__construct();
        #include
        $this->Common_model = new CommonModel();
        $this->Listing_model = new ListingModel();
        $this->New_case_model = new NewCaseModel();
        $this->Dropdown_list_model = new DropdownListModel();
        $this->Get_details_model = new GetDetailsModel();
        $this->DocumentIndex_Select_model = new DocumentIndexSelectModel();
        $this->DocumentIndex_model = new DocumentIndexModel();
        $this->PdfHashTasks_model = new PdfHashTasksModel();
        $this->efiling_webservices = new Efiling_webservices();
        $this->Efiling_action_model = new EfilingActionModel();
        $this->View_model = new ViewModel();
        $this->DocumentIndex_DropDown_model = new DocumentIndexDropDownModel();
        $this->Act_sections_model = new ActSectionsModel();
        $this->Citation_model = new CitationModel();
        $this->autoDiaryGeneration = new \App\Controllers\NewCase\AutoDiaryGeneration;
        $this->Get_CIS_Status_model = new GetCISStatusModel();
        $this->encrypt = \Config\Services::encrypter();
    }
    public function resend_otp()
    {
        // $text="E-mentioning Diary No. ".getSessionData('efiling_details')['diary_no']."/".getSessionData('efiling_details')['diary_year'];
        $text = "Reference No " . getSessionData('efiling_details')['efiling_no'];
        resend_otp(38, $text);
    }
    public function valid_efil()  // function added on 29.04.2020 for validation for cde 
    {
        $ans = $this->Common_model->valid_cde(getSessionData('efiling_details')['registration_id']);
        $arr_data = explode('-', $ans);
        $status = $arr_data[0];
        $status = (ltrim($status, ','));
        $ct = $arr_data[1];
        $valid_ct = array(5, 6, 7, 8);


        $final_outcome = '';
        if (($ct == $valid_ct[0]) || ($ct == $valid_ct[1]) || ($ct == $valid_ct[2]) || ($ct == $valid_ct[3])) {
            $chk_status = "1,2,3,6";
            $chk_status = "12367";
            //echo $chk_status;
            if (!in_array(1, explode(',', $status))) {
                $final_outcome = 1;
            }
            if (!in_array(2, explode(',', $status))) {
                $final_outcome = $final_outcome . ',' . '2';
            }
            if (!in_array(3, explode(',', $status))) {
                $final_outcome = $final_outcome . ',' . '3';
            }
            if (!in_array(6, explode(',', $status))) {
                $final_outcome = $final_outcome . ',' . '6';
            }
            if (!in_array(8, explode(',', $status))) {
                $final_outcome = $final_outcome . ',' . '8';
            }
        } else {

            if (!in_array(1, explode(',', $status))) {
                $final_outcome = 1;
            }
            if (!in_array(2, explode(',', $status))) {
                $final_outcome = $final_outcome . ',' . '2';
            }
            if (!in_array(3, explode(',', $status))) {
                $final_outcome = $final_outcome . ',' . '3';
            }
            if (!in_array(6, explode(',', $status))) {
                $final_outcome = $final_outcome . ',' . '6';
            }
            if (!in_array(7, explode(',', $status))) {
                $final_outcome = $final_outcome . ',' . '7';
            }
            if (!in_array(8, explode(',', $status))) {
                $final_outcome = $final_outcome . ',' . '8';
            }
        }

        if (!in_array(10, explode(',', $status))) {
            $final_outcome = $final_outcome . ',' . '10';
        }
        if (!in_array(12, explode(',', $status))) {
            $final_outcome = $final_outcome . ',' . '12';
        }
        echo $ct . '?' . $final_outcome;
    }  // end of the function
    public function index()
    {
        //var_dump($_SESSION);
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON);
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('dashboard'));
        }
        if (empty(getSessionData('efiling_details')) || empty(getSessionData('efiling_details'))) {
            return redirect()->to(base_url('dashboard'));
        }
        $registration_id = getSessionData('efiling_details')['registration_id'];
        if ($_SESSION['efiling_details']['stage_id']==Draft_Stage) {
            check_submaster_category($registration_id);
        }
        /*The following portion written by Mr.Anshu as on dated 09052024 to check the case must have atlest one pdf uploaded and must have at least one index item : start*/
        $uploaded_pdf = $this->DocumentIndex_DropDown_model->get_uploaded_pdfs($registration_id);
        if (!empty($uploaded_pdf)) {
            foreach ($uploaded_pdf as $row) {
                $file_partial_path = $row['file_path'];
                $file_name = $row['file_name'];
                if (file_exists($file_partial_path)) {
                    $doc_title = getSessionData('efiling_details')['efiling_no'] . '_' . str_replace(' ', '_', $row['doc_title']) . '.pdf';
                } else {
                    $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Pdf File does not exist.</div>');
                    return redirect()->to(base_url('documentIndex'));
                }
            }
        } else {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Pdf File does not exist.</div>');
            return redirect()->to(base_url('documentIndex'));
        }

        $index_pdf_details = $this->DocumentIndex_Select_model->is_index_created($registration_id);
        if (empty($index_pdf_details)) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Pdf file index is not complete.</div>');
            return redirect()->to(base_url('documentIndex'));
        }
        /*The following portion written by Mr.Anshu as on dated 09052024 to check the case must have atlest one pdf uploaded and must have at least one index item : end*/

        //Add Final Submit Validations for mark defect cured checkbox
        $marked_defect_tobe_shown_stages = array(I_B_Defected_Stage, I_B_Rejected_Stage);
        if (in_array(getSessionData('efiling_details')['stage_id'], $marked_defect_tobe_shown_stages)) {
            $result_icmis = $this->Common_model->get_cis_defects_remarks($registration_id, FALSE);
            //var_dump($result_icmis);
            if (isset($result_icmis) && !empty($result_icmis)) {
                foreach ($result_icmis as $re) {
                    $aor_cured = (isset($re->aor_cured) && !empty($re->aor_cured)) ? $re->aor_cured : "f";
                    if ($aor_cured == 'f') {
                        $this->session->setFlashdata('msg', 'Please Mark All Defects Cured Before Final Submit.');
                        return redirect()->to(base_url('documentIndex'));
                    }
                }
            }
        }
        ////////////////////////////
        if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_DEFICIT_COURT_FEE && (bool)$_SESSION['estab_details']['enable_payment_gateway']) {
            $next_stage = Transfer_to_IB_Stage;
        } elseif (getSessionData('login')['ref_m_usertype_id'] == USER_DEPARTMENT || getSessionData('login')['ref_m_usertype_id'] == USER_CLERK) {
            $next_stage = Draft_Stage;
        } elseif (getSessionData('efiling_details')['stage_id'] == Draft_Stage) {
            $next_stage = Initial_Approaval_Pending_Stage;
        } elseif (getSessionData('efiling_details')['stage_id'] == Initial_Defected_Stage) {
            $next_stage = Initial_Defects_Cured_Stage;
        } elseif (getSessionData('efiling_details')['stage_id'] == DEFICIT_COURT_FEE) {
            $next_stage = DEFICIT_COURT_FEE_PAID;
        } elseif (getSessionData('efiling_details')['stage_id'] == I_B_Defected_Stage) {
            $next_stage = I_B_Defects_Cured_Stage;
        } elseif (getSessionData('efiling_details')['stage_id'] == I_B_Rejected_Stage || getSessionData('efiling_details')['stage_id'] == E_REJECTED_STAGE) {
            $next_stage = Initial_Defects_Cured_Stage;
        } else {
            $this->session->setFlashdata('msg', 'Invalid Action.');
            return redirect()->to(base_url('dashboard'));
        }
        $result = $this->Common_model->updateCaseStatus($registration_id, $next_stage);
        // pr($result);
        if ($result) {
            $this->updatecrontableforhashing($registration_id, getSessionData('efiling_details')['efiling_no']);
            $diary_id = getSessionData('efiling_details')['diary_no'] . getSessionData('efiling_details')['diary_year'];
            $efiling_no = getSessionData('efiling_details')['efiling_no'];

            // $this->setReturnedByAdvocate($diary_id, $efiling_no);  // go to clerk
            //echo getSessionData('efiling_details')['efiling_no'];
            $sentSMS = "Efiling no. " . efile_preview(getSessionData('efiling_details')['efiling_no']) . " has been submitted and is pending for initial approval with efiling admin. - Supreme Court of India";
            $subject = "Submitted : Efiling no. " . efile_preview(getSessionData('efiling_details')['efiling_no']);
            $user_name = getSessionData('login')['first_name'] . ' ' . getSessionData('login')['last_name'];

            send_mobile_sms(getSessionData('login')['mobile_number'], $sentSMS, SCISMS_Initial_Approval);
            send_mail_msg(getSessionData('login')['emailid'], $subject, $sentSMS, $user_name);
            $this->session->setFlashdata('msg', '<div class="alert alert-success text-center"> E-filing number ' . efile_preview(getSessionData('efiling_details')['efiling_no']) . ' submitted successfully for approval of E-filing Admin.!</div>');
            $_SESSION['efiling_details']['stage_id'] = Initial_Approaval_Pending_Stage;
            //TODO:call function for auto-diary : Check the stage of case if the case is defective then diary no will not be executed
            $filing_type = getSessionData('efiling_details')['ref_m_efiled_type_id'];
            if (in_array(getSessionData('efiling_details')['stage_id'], $marked_defect_tobe_shown_stages)) {
                //diary no not generated and it will redirected to defect cured screen
            } else {
                $isSuccessfullyAproved = $this->Approve(); // method to Approve the case
                // pr($isSuccessfullyAproved);

                if (!empty($isSuccessfullyAproved)) {
                    $case_type = !empty(getSessionData('efiling_details')['efiling_type']) ? getSessionData('efiling_details')['efiling_type'] : NULL;
                    $autoGeneratedNo = $this->generateAutoDiary($registration_id, $case_type); //function written for Auto diarization in the last week
                    // $autoGeneratedNo = $this->autoDiaryGeneration->generateAutoDiary($registration_id, $case_type); //function written for Auto diarization in the last week
                    // $msg = json_decode(json_encode($autoGeneratedNo), true);
                    // var_dump($msg);
                    if ($filing_type == E_FILING_TYPE_NEW_CASE) {
                        $redirectURL = 'newcase/view';
                    } elseif ($filing_type == E_FILING_TYPE_MISC_DOCS) {
                        $redirectURL = 'miscellaneous_docs/view';
                    } elseif ($filing_type == E_FILING_TYPE_DEFICIT_COURT_FEE) {
                        $redirectURL = 'Deficit_court_fee/view';
                    } elseif ($filing_type == E_FILING_TYPE_IA) {
                        $redirectURL = 'IA/view';
                    } else {
                        $redirectURL = 'caveat/view';
                    }
                    return response()->redirect(base_url($redirectURL));
                }
            }
        } else {
            echo "error";

            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Submission failed. Please try again!</div>');
            return redirect()->to(base_url('dashboard'));
        }
        // }   //
    }
    private function Approve()
    {
        $Array = array(New_Filing_Stage, Initial_Defects_Cured_Stage);
        if (!in_array(getSessionData('efiling_details')['stage_id'], $Array)) {
            // pr('hi');
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> ' . htmlentities('Invalid Action', ENT_QUOTES) . ' </div>');
            return redirect()->to(base_url('dashboard'));
        }
        if (empty(getSessionData('efiling_details')) || empty($_SESSION['estab_details'])) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> ' . htmlentities('Invalid Action', ENT_QUOTES) . ' </div>');
            return redirect()->to(base_url('dashboard'));
        }

        $regid = getSessionData('efiling_details')['registration_id'];
        $filing_type = getSessionData('efiling_details')['ref_m_efiled_type_id'];

        $data = $this->Efiling_action_model->approve_case($regid, $filing_type);
        // echo '<pre>'; print_r($data); exit;

        if ($data) {
            $userdata = $this->Efiling_action_model->get_efiled_by_user(getSessionData('efiling_details')['created_by']);
            $sentSMS = "eFiling no. " . efile_preview(getSessionData('efiling_details')['efiling_no']) . " has been initially accepted for further processing.";
            log_message('info', "eFiling no." . efile_preview(getSessionData('efiling_details')['efiling_no']) . "has been initially accepted for further processing.");
            $subject = "Initially accepted : eFiling no. " . efile_preview(getSessionData('efiling_details')['efiling_no']);
            log_message('info', "Initially accepted : eFiling no. " . efile_preview(getSessionData('efiling_details')['efiling_no']));
            $user_name = $userdata[0]->first_name . ' ' . $userdata[0]->last_name;
            send_mobile_sms($userdata[0]->moblie_number, $sentSMS,SCISMS_Efiling_Acceptance); //Commented till SMS facility not available in Admin Server.
            send_mail_msg($userdata[0]->emailid, $subject, $sentSMS, $user_name);
            $this->session->setFlashdata('msg', '<div class="alert alert-success text-center">E-filing Number ' . efile_preview(getSessionData('efiling_details')['efiling_no']) . ' Approved successfully !</div>');
            log_message('info', "E-filing Number" . efile_preview(getSessionData('efiling_details')['efiling_no']) . " Approved successfully !");
            return 1;
            
        } else {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Approval Failed. Please try again!</div>');
            log_message('info', "Approval Failded. Please try again!");
            if ($filing_type == E_FILING_TYPE_NEW_CASE) {
                $redirectURL = 'newcase/view';
            } elseif ($filing_type == E_FILING_TYPE_MISC_DOCS) {
                $redirectURL = 'miscellaneous_docs/view';
            } elseif ($filing_type == E_FILING_TYPE_DEFICIT_COURT_FEE) {
                $redirectURL = 'Deficit_court_fee/view';
            } elseif ($filing_type == E_FILING_TYPE_IA) {
                $redirectURL = 'IA/view';
            } else {
                $redirectURL = 'caveat/view';
            }
            return response()->redirect(base_url($redirectURL));
        }
    }
    public function generateAutoDiary($registration_id = null, $case_type=null)
    {
        // echo $registration_id.'<br>';
        // pr(getSessionData('efiling_details')['efiling_type']);
        if (empty($registration_id)) {
            return NULL;
        } else {
            $curl = curl_init();
            $resid = !empty($registration_id) ? $registration_id : NULL;
            $caseType = !empty($case_type) ? $case_type : NULL;
            $token = '3d07510c2c3348f58a04f63bc9a63296';
            curl_setopt_array($curl, array(
                CURLOPT_URL => ADMIN_SERVER_URL.'newcase/AutoDiaryGeneration?registration_id=' . $resid . '&efiling_type=' . $caseType . '&token=' . $token,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => '',
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response, true);
            
            $filing_type = getSessionData('efiling_details')['ref_m_efiled_type_id'];
            if (isset($response) && !empty($response) && $response['success'] == 'success') {
                $this->session->setFlashdata('msg', '<div class="alert alert-success text-center text-bg"> ' . $response['message'] . '</div>');
                getSessionData('efiling_details')['stage_id'] = Initial_Approaval_Pending_Stage;
                // echo 'if';
                // print_r($response);
            // pr($response);
            } else {
                $this->session->setFlashdata('msg', '<div class="alert alert-success text-center font-weight-bold"> Your E-filing has been submitted successfully for Diary Number, Kindly check after 30 Minutes </div>');
            //    echo 'else';  print_r($response);
            // pr($response);
            }
            return response()->redirect(base_url('newcase/view'));
            // return redirect()->to(base_url('newcase/view'));
        }
    }


    public function getAllFilingDetailsByRegistrationId()
    {
        if (empty(getSessionData('login')['ref_m_usertype_id'])) {
            return redirect()->to(base_url('/'));
        }

        $file_type = !empty(getSessionData('efiling_details')['efiling_type']) ? getSessionData('efiling_details')['efiling_type'] : NULL;
        $registration_id = !empty(getSessionData('efiling_details')['registration_id']) ? getSessionData('efiling_details')['registration_id'] : NULL;
        $finalArr = array();
        $finalArr['file_type'] = $file_type;

        if (isset($file_type) && !empty($file_type) && isset($registration_id) && !empty($registration_id)) {
            //get icmis usercode for diary_user_id
            $paramArray = array();
            $paramArray['registration_id'] = $registration_id;
            $icmisUserData = $this->Common_model->getIcmisUserCodeByRegistrationId($paramArray);
            $userIcmisCode = !empty($icmisUserData[0]->icmis_usercode) ? (int)$icmisUserData[0]->icmis_usercode : 0;
            switch ($file_type) {
                case 'new_case':
                    $case_details = $this->Get_details_model->get_new_case_details($registration_id);
                    $tmpArr = array();
                    if (isset($case_details[0]) && !empty($case_details[0])) {
                        $tmpArr['casetype_id'] = !empty($case_details[0]['sc_case_type_id']) ? $case_details[0]['sc_case_type_id'] : NULL;
                        $tmpArr['diary_user_id'] = $userIcmisCode;
                        $tmpArr['pno'] = !empty($case_details[0]['no_of_petitioners']) ? $case_details[0]['no_of_petitioners'] : NULL;
                        $tmpArr['rno'] = !empty($case_details[0]['no_of_respondents']) ? $case_details[0]['no_of_respondents'] : NULL;

                        $mainParties=explode('Vs.',$case_details[0]['cause_title']);
                        $tmpArr['pet_name_causetitle']=$mainParties[0];
                        $tmpArr['res_name_causetitle']=$mainParties[1];
                        $tmpArr['subject_cat'] = !empty($case_details[0]['subject_cat']) ? $case_details[0]['subject_cat'] : NULL;
                        $tmpArr['subj_main_cat'] = !empty($case_details[0]['subj_main_cat']) ? $case_details[0]['subj_main_cat'] : NULL;
                        $tmpArr['sc_sp_case_type_id'] = !empty($case_details[0]['sc_sp_case_type_id']) ? $case_details[0]['sc_sp_case_type_id'] : NULL;
                        $tmpArr['jail_signature_date'] = !empty($case_details[0]['jail_signature_date']) ? $case_details[0]['jail_signature_date'] : NULL;
                        $tmpArr['efiling_no'] = !empty($case_details[0]['efiling_no']) ? $case_details[0]['efiling_no'] : NULL;
                        $tmpArr['if_sclsc']=!empty($case_details[0]['if_sclsc'])?$case_details[0]['if_sclsc']:0;
                        $tmpArr['sclsc_amr_no'] = !empty($case_details[0]['sclsc_amr_no']) ? $case_details[0]['sclsc_amr_no'] : NULL;
                        $tmpArr['sclsc_amr_year'] = !empty($case_details[0]['sclsc_amr_year']) ? $case_details[0]['sclsc_amr_year'] : NULL;
                        $tmpArr['special_category']=!empty($case_details[0]['special_category'])?$case_details[0]['special_category']:0;
                        $created_by = !empty($case_details[0]['created_by']) ? $case_details[0]['created_by'] : NULL;
                        if (isset($created_by) && !empty($created_by)) {
                            $userData = $this->Common_model->getUserDetailsById($created_by);
                            if (isset($userData) && !empty($userData)) {
                                $adv_sci_bar_id = !empty($userData[0]['adv_sci_bar_id']) ? (int)$userData[0]['adv_sci_bar_id'] : NULL;
                                if (isset($adv_sci_bar_id) && !empty($adv_sci_bar_id)) {
                                    $getBarData = $this->Common_model->getBarDetailsById($adv_sci_bar_id);
                                    $bar_id = !empty($getBarData[0]['bar_id']) ? $getBarData[0]['bar_id'] : NULL;
                                    /* $pp = !empty($getBarData[0]['pp']) ? 'P' : 'A';
                                    $tmpArr['pet_adv_id'] = !empty($getBarData[0]['pp']) ? PETITIONER_IN_PERSON : $bar_id; */
                                    // This portion of code is commented on 13052025, as PP field has no significant for AOR or PIP in the BAR table as per ICMIS team
                                    $pp = !empty($bar_id) ? 'A' : 'P';
                                    $tmpArr['pet_adv_id'] = !empty($bar_id) ? $bar_id : PETITIONER_IN_PERSON;
                                    $tmpArr['adv_pp'] = $pp;
                                } else {
                                    $tmpArr['pet_adv_id'] = PETITIONER_IN_PERSON;
                                    $tmpArr['adv_pp'] = 'P';
                                }
                            }
                        }
                    }
                    $courtData = $this->Common_model->getEarlierCourtDetailByRegistrationId($registration_id);
                    $court_type = !empty($case_details[0]['court_type']) ? (int)$case_details[0]['court_type'] : NULL;
                    if (isset($court_type) && !empty($court_type)) {
                        switch ($court_type) {
                            case 1:
                                $tmpArr['court_type'] = !empty($courtData[0]['court_type']) ? $courtData[0]['court_type'] : NULL;
                                $tmpArr['cmis_state_id'] = !empty($courtData[0]['cmis_state_id']) ? $courtData[0]['cmis_state_id'] : NULL;
                                $tmpArr['ref_agency_code_id'] = !empty($courtData[0]['ref_agency_code_id']) ? $courtData[0]['ref_agency_code_id'] : NULL;
                                break;
                            case 3:
                                $tmpArr['court_type'] = !empty($courtData[0]['court_type']) ? $courtData[0]['court_type'] : NULL;
                                $tmpArr['cmis_state_id'] = !empty($courtData[0]['cmis_state_id']) ? $courtData[0]['cmis_state_id'] : NULL;
                                $tmpArr['ref_agency_code_id'] = !empty($courtData[0]['ref_agency_code_id']) ? $courtData[0]['ref_agency_code_id'] : NULL;
                                break;
                            case 4:
                                $tmpArr['court_type'] = !empty($courtData[0]['court_type']) ? $courtData[0]['court_type'] : NULL;
                                break;
                            case 5:
                                $tmpArr['court_type'] = !empty($courtData[0]['court_type']) ? $courtData[0]['court_type'] : NULL;
                                $tmpArr['cmis_state_id'] = !empty($courtData[0]['cmis_state_id']) ? $courtData[0]['cmis_state_id'] : NULL;
                                $tmpArr['ref_agency_code_id'] = !empty($courtData[0]['ref_agency_code_id']) ? $courtData[0]['ref_agency_code_id'] : NULL;
                                break;
                            default:
                        }
                    }
                    $finalArr['case_details'] = !empty($tmpArr) ? array((object)$tmpArr) : array(new stdClass());

                    $main_petitioner_details = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => 'P', 'm_a_type' => 'M', 'party_id' => NULL, 'view_lr_list' => FALSE));
                    $tempMainArr = array();
                    if (isset($main_petitioner_details) && !empty($main_petitioner_details)) {
                        foreach ($main_petitioner_details as $k => $v) {
                            $tempMainArr[] = (object)$v;
                        }
                    }
                    $finalArr['main_petitioner'] = !empty($main_petitioner_details[0]) ? $tempMainArr : array(new stdClass());
                    $respondent_details = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => 'R', 'm_a_type' => 'M', 'party_id' => NULL, 'view_lr_list' => FALSE));
                    $tempResArr = array();
                    if (isset($respondent_details) && !empty($respondent_details)) {
                        foreach ($respondent_details as $k => $v) {
                            $tempResArr[] = (object)$v;
                        }
                    }

                    $finalArr['main_respondent'] = !empty($respondent_details[0]) ? $tempResArr : array(new stdClass());

                    //$extra_parties_list = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => NULL, 'm_a_type' => 'A', 'party_id' => NULL, 'view_lr_list' => FALSE));
                    $extra_parties_list = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => NULL, 'm_a_type' => 'A', 'party_id' => NULL, 'view_lr_list' => FALSE));
                    $tempExtArr = array();
                    if (isset($extra_parties_list) && !empty($extra_parties_list)) {
                        foreach ($extra_parties_list as $k => $v) {
                            $tempExtArr[] = (object)$v;
                        }
                    }
                    $finalArr['extra_party'] = !empty($extra_parties_list[0]) ? $tempExtArr : array(new stdClass());
                    $subordinate_court_details = $this->Get_details_model->getSubordinateCourtData($registration_id);
                    $court_type = ''; //for high court first
                    $subTempArr = array();
                    if (isset($subordinate_court_details) && !empty($subordinate_court_details)) {
                        foreach ($subordinate_court_details as $k => $v) {
                            $params = array();
                            if (isset($v['registration_id']) && !empty($v['registration_id'])) {
                                $params['table_name'] = 'efil.tbl_fir_details';
                                $params['whereFieldName'] = 'registration_id';
                                $params['whereFieldValue'] = (int)$v['registration_id'];
                                $firData = $this->Common_model->getData($params);
                                if (!isset($v['case_type_id']) && empty($v['case_type_id'])) {
                                    $v['case_type_id'] = 0;
                                }
                                if (isset($firData) && !empty($firData) && count($firData) > 0) {
                                    $v['fir_details']['is_fir_details_exists'] =  true;
                                    $v['fir_details']['firData'] =  array($firData[0]);
                                } else {
                                    $v['fir_details']['is_fir_details_exists'] =  false;
                                    $v['fir_details']['firData']  =  array(new stdClass());
                                }
                                $subTempArr[$k] = (object)$v;
                            }
                        }
                    }
                    $finalArr['earlier_courts'] = !empty($subordinate_court_details[0]) ? $subTempArr : array(new stdClass());

                    $efiled_docs_list = $this->View_model->get_index_items_list($registration_id);
                    $docTempArr = array();
                    if (isset($efiled_docs_list) && !empty($efiled_docs_list)) {
                        foreach ($efiled_docs_list as $k => $v) {
                            $docTempArr[] = (object)$v;
                        }
                    }
                    $finalArr['documents'] = !empty($efiled_docs_list[0]) ? $docTempArr : array(new stdClass());
                    $payment_details = $this->View_model->get_payment_details($registration_id);
                    $feeTempArr = array();
                    if (isset($payment_details) && !empty($payment_details)) {
                        foreach ($payment_details as $k => $v) {
                            $feeTempArr[] = (object)$v;
                        }
                    }
                    $finalArr['court_fee'] = !empty($payment_details[0]) ? $feeTempArr : array(new stdClass());

                    $act_sections_list = $this->Act_sections_model->get_act_sections_list($registration_id);
                    $actTempArr = array();
                    if (isset($act_sections_list) && !empty($act_sections_list)) {
                        foreach ($act_sections_list as $k => $v) {
                            $actTempArr[] = (object)$v;
                        }
                    }
                    $finalArr['act_section'] = !empty($act_sections_list[0]) ? $actTempArr : array(new stdClass());

                    break;
                case 'caveat':
                    $registration_id = !empty(getSessionData('efiling_details')['registration_id']) ? (int)getSessionData('efiling_details')['registration_id'] : NULL;
                    //caveator
                    $arr = array();
                    $arr['registration_id'] = $registration_id;
                    $arr['step'] = 3;
                    $caveator = $this->Common_model->getCaveatDataByRegistrationId($arr);
                    $createdBy = !empty($caveator[0]->createdby) ? (int)$caveator[0]->createdby : NULL;
                    $case_type_id = !empty($caveator[0]->case_type_id) ? (int)$caveator[0]->case_type_id : NULL;
                    $tmpArr = array();
                    if (isset($caveator) && !empty($caveator)) {
                        if (isset($createdBy) && !empty($createdBy)) {
                            $userData = $this->Common_model->getUserDetailsById($createdBy);
                            if (isset($userData) && !empty($userData)) {
                                $adv_sci_bar_id = !empty($userData[0]['adv_sci_bar_id']) ? (int)$userData[0]['adv_sci_bar_id'] : NULL;
                                $usertype = !empty($userData[0]['ref_m_usertype_id']) ? (int)$userData[0]['ref_m_usertype_id'] : NULL;
                                if ((isset($adv_sci_bar_id) && !empty($adv_sci_bar_id)) || $usertype == USER_IN_PERSON) {
                                    if ($usertype == USER_IN_PERSON) {
                                        $adv_sci_bar_id = 616;
                                    }
                                    $getBarData = $this->Common_model->getBarDetailsById($adv_sci_bar_id);
                                    $bar_id = !empty($getBarData[0]['bar_id']) ? $getBarData[0]['bar_id'] : NULL;
                                    $pp = !empty($getBarData[0]['pp']) ? 'P' : 'A';
                                    $tmpArr['pet_adv_id'] = !empty($getBarData[0]['pp']) ? CAVEATOR_IN_PERSON :   $bar_id;
                                    $tmpArr['adv_pp'] = $pp;
                                    // $tmpArr['diary_user_id'] = 1; // change in future
                                    $tmpArr['diary_user_id'] = $userIcmisCode;
                                    $tmpArr['casetype_id'] = $case_type_id;
                                }
                            }
                        }
                        $params = array();
                        $params['table_name'] = 'efil.tbl_efiling_nums';
                        $params['whereFieldName'] = 'registration_id';
                        $params['whereFieldValue'] = $registration_id;
                        $efilingData = $this->Common_model->getData($params);
                        if (isset($efilingData) && !empty($efilingData)) {
                            $tmpArr['efiling_no'] = !empty($efilingData[0]->efiling_no) ? $efilingData[0]->efiling_no : NULL;
                        }
                        $finalArr['caveat_details'] = !empty($tmpArr) ? array((object)$tmpArr) : array(new stdClass());
                        $finalArr['caveator'] = !empty($caveator[0]) ? array($caveator[0]) : array(new stdClass());
                    }
                    //caveatee
                    $arr = array();
                    $arr['registration_id'] = $registration_id;
                    $arr['step'] = 4;
                    $caveatee = $this->Common_model->getCaveatDataByRegistrationId($arr);
                    $finalArr['caveatee'] = !empty($caveatee[0]) ? array($caveatee[0]) : array(new stdClass());
                    //extra party
                    $arr = array();
                    $arr['registration_id'] = $registration_id;
                    $arr['step'] = 5;
                    $extra_party_details = $this->Common_model->getCaveatDataByRegistrationId($arr);
                    $finalArr['extra_party'] = !empty($extra_party_details[0]) ? $extra_party_details : array(new stdClass());
                    $subordinate_court_details = $this->Get_details_model->getSubordinateCourtData($registration_id);
                    $court_type = ''; //for high court first
                    $subTempArr = array();
                    if (isset($subordinate_court_details) && !empty($subordinate_court_details)) {
                        foreach ($subordinate_court_details as $k => $v) {
                            $params = array();
                            if (isset($v['registration_id']) && !empty($v['registration_id'])) {
                                $params['table_name'] = 'efil.tbl_fir_details';
                                $params['whereFieldName'] = 'registration_id';
                                $params['whereFieldValue'] = (int)$v['registration_id'];
                                $firData = $this->Common_model->getData($params);
                                if (!isset($v['case_type_id']) && empty($v['case_type_id'])) {
                                    $v['case_type_id'] = 0;
                                }
                                if (isset($firData) && !empty($firData) && count($firData) > 0) {
                                    $v['fir_details']['is_fir_details_exists'] =  true;
                                    $v['fir_details']['firData'] =  array($firData[0]);
                                } else {
                                    $v['fir_details']['is_fir_details_exists'] =  false;
                                    $v['fir_details']['firData']  =  array(new stdClass());
                                }
                                $subTempArr[$k] = (object)$v;
                            }
                        }
                    }
                    $finalArr['earlier_courts'] = !empty($subordinate_court_details[0]) ? $subTempArr : array(new stdClass());
                    //upload doc
                    $efiled_docs_list = $this->View_model->get_index_items_list($registration_id);
                    if (isset($efiled_docs_list) && !empty($efiled_docs_list)) {
                        $tmpArr = array();
                        foreach ($efiled_docs_list as $k => $v) {
                            $tmpArr[] = (object)$v;
                        }
                    }
                    $finalArr['documents'] = !empty($efiled_docs_list[0]) ? $tmpArr : array(new stdClass());
                    //payment Details
                    $payment_details = $this->View_model->get_payment_details($registration_id);
                    if (isset($payment_details) && !empty($payment_details)) {
                        $tempArr = array();
                        foreach ($payment_details as $k => $v) {
                            $tempArr[] = (object)$v;
                        }
                    }
                    $finalArr['court_fee'] = !empty($payment_details[0]) ? $tempArr : array(new stdClass());
                    break;
                default:
                    $finalArr;
                    break;
            }
        }
        $finalArr = json_encode($finalArr);
        $response = array();
        if ($file_type == 'new_case') {
            $key = config('Encryption')->key;
            $encrypted_string = $this->encrypt->encrypt($finalArr);
            $response = $this->efiling_webservices->generateCaseDiary($encrypted_string);
        } else if ($file_type == 'caveat') {
            $key = config('Encryption')->key;
            $encrypted_string = $this->encrypt->encrypt($finalArr);
            $response = $this->efiling_webservices->generateCaseDiary($encrypted_string);
        }
        $diaryStatus = '';
        $insertData = [];

        $response = json_decode($response, true);
        if (strtoupper(trim($response['status'])) == 'SUCCESS') {
            $diaryStatus = 'new_diary';
            $filteredData = array();
            $typeGeneration = '';
            if (isset($response['diary_no'])) {
                $diaryNo = $response['diary_no'];
            }
            if (isset($response['alloted_to'])) {
                $alloted_to = $response['alloted_to'];
            }
            if (isset($response['insertedDocNums'])) {
                $insertedDocNums = $response['insertedDocNums'];
            }
            if (isset($response['status'])) {
                $status = $response['status'];
            }
            if (isset($response['records_inserted'])) {
                $records_inserted = $response['records_inserted'];
            }

            /*array_push($filteredData, ["field_name" => "case_detail", "field_value" => "1"]);
            array_push($filteredData, ["field_name" => "petitioner", "field_value" => "1"]);
            array_push($filteredData, ["field_name" => "respondent", "field_value" => "1"]);
            array_push($filteredData, ["field_name" => "extra_party", "field_value" => "1"]);
            array_push($filteredData, ["field_name" => "legal_representative", "field_value" => "1"]);
            array_push($filteredData, ["field_name" => "act_section", "field_value" => "1"]);
            array_push($filteredData, ["field_name" => "earlier_courts", "field_value" => "1"]);
            array_push($filteredData, ["field_name" => "upload_document", "field_value" => "1"]);
            array_push($filteredData, ["field_name" => "index", "field_value" => "1"]);
            array_push($filteredData, ["field_name" => "pay_court_fee", "field_value" => "1"]);*/
            $filteredData = ['case_detail' => '1'];

            $typeGeneration = 'diary no.';
            $insertData['diaryNo'] = $diaryNo;
            $insertData['alloted_to'] = $alloted_to;
            $insertData['insertedDocNums'] = $insertedDocNums;
            $insertData['status'] = $status;
            $insertData['selectedcheckBox'] = $filteredData;
            $insertData['diaryStatus'] = $diaryStatus;
        } else  if (strtoupper(trim($response['status'])) == 'ERROR_ALREADY_IN_ICMIS') {
            $diaryStatus = 'exist_diary';
            /*array_push($filteredData, array("field_name" => "caveator", "field_value" => "1"));
            array_push($filteredData, array("field_name" => "caveatee", "field_value" => "1"));
            array_push($filteredData, array("field_name" => "extra_party", "field_value" => "1"));
            array_push($filteredData, array("field_name" => "subordinate_court", "field_value" => "1"));
            array_push($filteredData, array("field_name" => "upload_document", "field_value" => "1"));
            array_push($filteredData, array("field_name" => "index", "field_value" => "1"));
            array_push($filteredData, array("field_name" => "pay_court_fee", "field_value" => "1"));
            $typeGeneration = 'caveat no.';*/
        } else  if (strtoupper(trim($response['status'])) == 'ERROR_MAIN') {
        } else if (strtoupper(trim($response['status'])) == 'ERROR_CAVEAT') {
        }
        array_push($response, array("field_name" => "diaryStatus", "field_value" => $diaryStatus));
        $updateDiaryDetailsStatus = $this->updateDiaryDetails($insertData);
    }
    function verify_otp()
    {
        $otp = $_POST['s'];
        $otp_validate_response = validate_otp(38, $otp);
        if ($otp_validate_response == false) {
            echo '2@@@';
            echo "Invalid OTP";
            exit(0);
        } else {
            echo '1@@@OTP Verified';
        }
    }
    function updatecrontableforhashing($registration_id, $efiling_no)
    {
        $max_submit_count = $this->PdfHashTasks_model->get_max_submit_count($efiling_no);
        $current_submit_count = $max_submit_count + 1;
        $result = $this->DocumentIndex_Select_model->get_index_items_list($registration_id);
        foreach ($result as $res) {
            $registration_id = $res['registration_id'];
            $pspdfkit_document_id = $res['pspdfkit_document_id'];
            $title = $res['doc_title'];
            $pspdfkit_document = copyDocument($pspdfkit_document_id);
            $copied_pspdfkit_document_id = !empty($pspdfkit_document) ? $pspdfkit_document['document_id'] : null;
            if (!empty($copied_pspdfkit_document_id)) {
                $issaved = $this->PdfHashTasks_model->save_pdf_hash_task($registration_id, $efiling_no, $copied_pspdfkit_document_id, $pspdfkit_document_id, $current_submit_count, $title);
                if ($issaved) {
                    $this->DocumentIndex_model->setDuplicatePSPdfKitDocument($registration_id, $pspdfkit_document_id, $copied_pspdfkit_document_id);
                }
            }
        }
    }
    function setReturnedByAdvocate($diary_id, $efiling_no)
    {
        $assoc_arr = array('diary_id' => $diary_id, 'efiling_no' => $efiling_no);
        $assoc_json = json_encode($assoc_arr);
        $key = config('Encryption')->key;
        $encrypted_string = $this->encrypt->encrypt($assoc_json, $key);
        $this->efiling_webservices->setReturnedByAdvocate($encrypted_string);
    }
    public function updateDiaryDetails($postData)
    {
        if (empty(getSessionData('login')['ref_m_usertype_id'])) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $output = array();

        //$postData = json_decode(file_get_contents('php://input'), true);

        $registration_id = !empty(getSessionData('efiling_details')['registration_id']) ? getSessionData('efiling_details')['registration_id'] : NULL;
        $efiling_type = !empty(getSessionData('efiling_details')['efiling_type']) ? strtolower(getSessionData('efiling_details')['efiling_type']) : NULL;
        $ref_m_efiled_type_id = !empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) ? getSessionData('efiling_details')['ref_m_efiled_type_id'] : NULL;
        $efiling_no = !empty(getSessionData('efiling_details')['efiling_no']) ? getSessionData('efiling_details')['efiling_no'] : NULL;
        $diaryStatus = !empty($postData['diaryStatus']) ? $postData['diaryStatus'] : NULL;
        if (isset($registration_id) && !empty($registration_id)) {

            $diary_num = NULL;
            $diary_year = NULL;
            $arr = array();
            if (!empty($postData['diaryNo']) && isset($postData['diaryNo'])) {
                $diary_num = substr($postData['diaryNo'], 0, -4);
                $diary_year = substr($postData['diaryNo'], -4);
            }
            //alloted_to
            $alloted_to_name = NULL;
            if (!empty($postData['alloted_to']) && isset($postData['alloted_to'])) {
                $alloted = explode('~', $postData['alloted_to']);
                $alloted_to_emp_code = !empty($alloted[0]) ? $alloted[0] : NULL;
                $alloted_name = !empty($alloted[1]) ? $alloted[1] : NULL;
                if (isset($alloted_to_emp_code) && !empty($alloted_to_emp_code) && isset($alloted_name) && !empty($alloted_name)) {
                    $alloted_to_name = strtoupper($alloted_name) . ' (' . $alloted_to_emp_code . ' )';
                } else if (isset($alloted_name) && !empty($alloted_name)) {
                    $alloted_to_name = strtoupper($alloted_name);
                }
            }
            $diaryNo = !empty($postData['diaryNo']) ? $postData['diaryNo'] : NULL;
            $status = !empty($postData['status']) ? $postData['status'] : NULL;
            $records_inserted = !empty($postData['records_inserted']) ? $postData['records_inserted'] : NULL;
            $insertedDocNums = !empty($postData['insertedDocNums']) ? $postData['insertedDocNums'] : NULL;
            if (isset($postData['selectedcheckBox']) && !empty($postData['selectedcheckBox'])) {
                $checkBoxArr = array();
                $checkBoxArr['recordinserted'] = json_encode($postData['selectedcheckBox']);
                $checkBoxArr['diary_no'] = $diaryNo;
                $checkBoxArr['status'] = $status;
                $checkBoxArr['createdAt'] = date('Y-m-d H:i:s');
                $checkBoxArr['updatedAt'] = NULL;
                $checkBoxArr['createdby'] = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;;
                $checkBoxArr['updatedby'] = NULL;
                $checkBoxArr['createdbyip'] = getClientIP();
                $checkBoxArr['registration_id'] = $registration_id;
                $table = "efil.tbl_diary_generation_checkbox";
                $this->Citation_model->insertData($table, $checkBoxArr);
            }
            if (isset($records_inserted) && !empty($records_inserted)) {
                $insertDiaryData = array();
                $insertDiaryData['diaryNo'] = $diaryNo;
                $insertDiaryData['status'] = $status;
                $insertDiaryData['inserted_records'] = $records_inserted;
                $inArr = array();
                $inArr['recordinserted'] = json_encode($insertDiaryData);
                $inArr['diary_no'] = $diaryNo;
                $inArr['status'] = $status;
                $inArr['createdAt'] = date('Y-m-d H:i:s');
                $inArr['updatedAt'] = NULL;
                $inArr['createdby'] = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;;
                $inArr['updatedby'] = NULL;
                $inArr['createdbyip'] = getClientIP();
                $inArr['registration_id'] = $registration_id;
                $table = 'efil.tbl_diary_generation_response';
                $this->Citation_model->insertData($table, $inArr);
            }
            switch ($efiling_type) {
                case 'new_case':
                    //update icmis_docnum in tbl_efiled_docs
                    // $insertedDocNums = array(0=>array('doc_id'=>3588,'docnum'=>2121));
                    if (isset($insertedDocNums) && !empty($insertedDocNums)) {
                        foreach ($insertedDocNums as $k => $v) {
                            $doc_id = (int)$v['doc_id'];
                            $docnum = (int)$v['docnum'];
                            $upArray = [];
                            $upArray['table_name'] = 'efil.tbl_efiled_docs';
                            $upArray['whereFieldName'] = 'doc_id';
                            $upArray['whereFieldValue'] = $doc_id;
                            $docNumUpArr = array();
                            $docNumUpArr['icmis_docnum'] = $docnum;
                            $docNumUpArr['updated_on'] = date('Y-m-d H:i:s');
                            $upArray['updateArr'] = $docNumUpArr;
                            $this->Common_model->updateTableData($upArray);
                        }
                    }
                    $arr['table_name'] = 'efil.tbl_case_details';
                    $arr['whereFieldName'] = 'registration_id';
                    $arr['whereFieldValue'] = (int)$registration_id;
                    $upArr = array();
                    $upArr['sc_diary_year'] = $diary_year;
                    $upArr['sc_diary_date'] = date('Y-m-d H:i:s');
                    $upArr['sc_diary_num'] = $diary_num;
                    $arr['updateArr'] = $upArr;
                    $res = $this->Common_model->updateTableData($arr);
                    if (isset($res) && !empty($res)) {
                        $doc = array();
                        $doc['table_name'] = 'efil.tbl_efiled_docs';
                        $doc['whereFieldName'] = 'registration_id';
                        $doc['whereFieldValue'] = (int)$registration_id;
                        $docUpArr = array();
                        $docUpArr['icmis_diary_no'] = $diary_num . $diary_year;
                        $docUpArr['icmis_docyear'] = $diary_year;
                        $docUpArr['updated_on'] = date('Y-m-d H:i:s');
                        $doc['updateArr'] = $docUpArr;
                        $result = $this->Common_model->updateTableData($doc);
                        if (isset($result) && !empty($result)) {
                            if ($ref_m_efiled_type_id == E_FILING_TYPE_NEW_CASE) {
                                /*echo "me Tripathi k function me ghus gaya hu<br/>";
                                echo $registration_id.'<br/>';
                                echo $efiling_no.'<br/>';
                                echo Transfer_to_IB_Stage.'<br/>';
                                echo $diaryStatus.'<br/>';
                                echo $alloted_to_name.'<br/>';*/
                                $output = $this->getCisStatusForNewCase($registration_id, $efiling_no, Transfer_to_IB_Stage, $diaryStatus, $alloted_to_name);
                                //$this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Diary No :E-filing Number ' . efile_preview(getSessionData('efiling_details')['efiling_no']) . ' Approved successfully !</div>');
                                /*$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please enter every party details before moving to further tabs.</div>');
                                redirect('newcase/extra_party');*/
                            } else {
                                $output['success'] = 'error';
                            }
                        } else {
                            $output['success'] = 'error1';
                        }
                    } else {
                        $output['success'] = 'error2';
                    }
                    break;
                case 'caveat':
                    $arr['table_name'] = 'public.tbl_efiling_caveat';
                    $arr['whereFieldName'] = 'ref_m_efiling_nums_registration_id';
                    $arr['whereFieldValue'] = (int)$registration_id;
                    $upArr = array();
                    $upArr['caveat_year'] = $diary_year;
                    $upArr['caveat_num_date'] = date('Y-m-d H:i:s');
                    $upArr['caveat_num'] = $diary_num;
                    $arr['updateArr'] = $upArr;
                    $res = $this->Common_model->updateTableData($arr);
                    if (isset($res) && !empty($res)) {
                        $doc = array();
                        $doc['table_name'] = 'efil.tbl_efiled_docs';
                        $doc['whereFieldName'] = 'registration_id';
                        $doc['whereFieldValue'] = (int)$registration_id;
                        $docUpArr = array();
                        $docUpArr['icmis_diary_no'] = $diary_num . $diary_year;
                        $docUpArr['icmis_docyear'] = $diary_year;
                        $docUpArr['updated_on'] = date('Y-m-d H:i:s');
                        $doc['updateArr'] = $docUpArr;
                        $result = $this->Common_model->updateTableData($doc);
                        if (isset($result) && !empty($result)) {
                            if ($ref_m_efiled_type_id == E_FILING_TYPE_CAVEAT) {
                                $output = $this->getCisStatusForNewCase($registration_id, $efiling_no, Transfer_to_IB_Stage, $diaryStatus, '');
                            } else {
                                $output['success'] = 'error4';
                            }
                        } else {
                            $output['success'] = 'error5';
                        }
                    } else {
                        $output['success'] = 'error6';
                    }
                    break;
                default:
                    $output;
            }
        }
        var_dump($output);
        exit();
        json_encode($output);
    }
    function getCisStatusForNewCase($registration_id, $efiling_num, $curr_stage, $diaryStatus = NULL, $alloted_to_name = NULL)
    {
        if (empty(getSessionData('login')['ref_m_usertype_id'])) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $output = array();
        if (
            isset($registration_id) && !empty($registration_id) && isset($efiling_num)
            && !empty($efiling_num) && isset($curr_stage) && !empty($curr_stage)
        ) {
            $efiling_type = !empty(getSessionData('efiling_details')['efiling_type']) ? strtolower(getSessionData('efiling_details')['efiling_type']) : NULL;
            $ref_m_efiled_type_id = !empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) ? getSessionData('efiling_details')['ref_m_efiled_type_id'] : NULL;
            $curr_dt = date('Y-m-d H:i:s');
            switch ($efiling_type) {
                case 'new_case':
                    $params = array();
                    $params['table_name'] = 'efil.tbl_case_details';
                    $params['whereFieldName'] = 'registration_id';
                    $params['whereFieldValue'] = $registration_id;
                    $caseDetailsData = $this->Common_model->getData($params);
                    if (
                        isset($caseDetailsData[0]->sc_diary_num) && !empty($caseDetailsData[0]->sc_diary_num)
                        && isset($caseDetailsData[0]->sc_diary_year) && !empty($caseDetailsData[0]->sc_diary_year)
                    ) {
                        $objections_status = NULL;
                        $documents_status = NULL;
                        $diary_no = (int)$caseDetailsData[0]->sc_diary_num;
                        $diary_year = (int)$caseDetailsData[0]->sc_diary_year;
                        $sc_diary_date = !empty($caseDetailsData[0]->sc_diary_date) ? $caseDetailsData[0]->sc_diary_date : NULL;
                        $verification_date = !empty($caseDetailsData[0]->verification_date) ? $caseDetailsData[0]->verification_date : NULL;
                        $case_details = array();
                        $case_details['sc_diary_num'] = $diary_no;
                        $case_details['sc_diary_year'] = $diary_year;
                        $case_details['sc_diary_date'] = $sc_diary_date;
                        $case_details['updated_by'] = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;
                        $case_details['updated_on'] = $curr_dt;
                        $case_details['updated_by_ip'] = getClientIP();
                        $this->load->model('getCIS_status/Get_CIS_Status_model');
                        $stage_update_timestamp = $this->Get_CIS_Status_model->getStageUpdateTmestampCaseCaveat($registration_id, $curr_stage, $diaryStatus);
                        if (isset($stage_update_timestamp) && !empty($stage_update_timestamp)) {
                            $next_stage = I_B_Approval_Pending_Admin_Stage;
                            $update_status = $this->Get_CIS_Status_model->update_icmis_case_status($registration_id, $next_stage, $curr_dt, $case_details, '', '', '', $efiling_type);
                            if (isset($update_status) && !empty($update_status)) {
                                if ($next_stage == I_B_Approval_Pending_Admin_Stage) {
                                    $cuase_title = '';
                                    if (!empty($caseDetailsData[0]->cause_title) &&  !empty($caseDetailsData[0]->cause_title)) {
                                        $cuase_title = $caseDetailsData[0]->cause_title;
                                    }
                                    $createdby = !empty($caseDetailsData[0]->created_by) ? (int)$caseDetailsData[0]->created_by : NULL;
                                    if (isset($createdby) && !empty($createdby)) {
                                        $params = array();
                                        $params['table_name'] = 'efil.tbl_users';
                                        $params['whereFieldName'] = 'id';
                                        $params['whereFieldValue'] = (int)$createdby;
                                        $params['is_active'] = '1';
                                        $userData = $this->Common_model->getData($params);
                                        $mobile = NULL;
                                        $email = NULL;
                                        $efiling_no = '';
                                        $first_name = '';
                                        $last_name = '';
                                        $userName = '';
                                        if (isset($userData) && !empty($userData)) {
                                            $mobile = (!empty($userData[0]->moblie_number) && isset($userData[0]->moblie_number)) ? $userData[0]->moblie_number : NULL;
                                            $email = (!empty($userData[0]->emailid) && isset($userData[0]->emailid)) ? $userData[0]->emailid : NULL;
                                            $first_name = (!empty($userData[0]->first_name) && isset($userData[0]->first_name)) ? $userData[0]->first_name : NULL;
                                            $last_name = (!empty($userData[0]->last_name) && isset($userData[0]->last_name)) ? $userData[0]->last_name : NULL;
                                        }
                                    }
                                    if (isset($first_name) && !empty($first_name) && isset($last_name) && !empty($last_name)) {
                                        $userName = $first_name . ' ' . $last_name;
                                    } else if (isset($first_name) && !empty($first_name)) {
                                        $userName = $first_name;
                                    }
                                    $diary_No = $diary_no . ' / ' . $diary_year;
                                    $current_date = date('d-m-Y H:i:s');
                                    $arr = array();
                                    $arr['table_name'] = 'efil.tbl_efiling_nums';
                                    $arr['whereFieldName'] = 'registration_id';
                                    $arr['whereFieldValue'] = (int)$registration_id;
                                    $arr['is_active'] = '1';
                                    $efilingNoData = $this->Common_model->getData($arr);
                                    if (isset($efilingNoData[0]->efiling_no) && !empty($efilingNoData[0]->efiling_no)) {
                                        $efiling_no = $efilingNoData[0]->efiling_no;
                                    }
                                    $smsRes = '';
                                    if (isset($mobile) && !empty($mobile)) {
                                        //$message = 'Your case efiling no. '.$efiling_no.' , '.$cuase_title.' is filed with Diary No. '.$diary_No.' on '.$current_date.'';
                                        $message = 'Your case ' . $efiling_no . ' is filed with Diary No. ' . $diary_No . ' on ' . $current_date . '. - Supreme Court of India';
                                        $smsRes = send_mobile_sms($mobile, $message, SCISMS_Case_Filed_Diary_No);
                                        //send_whatsapp_message($registration_id,$efiling_no," -Diary Number $diary_No - Supreme Court of India");
                                    }
                                    if (isset($email) && !empty($email)) {
                                        $message = 'Dear ' . $userName . ', your case efiling no. ' . $efiling_no . ' , ' . $cuase_title . ' is filed with Diary No. ' . $diary_No . ' on ' . $current_date . '';
                                        $to_email = $email;
                                        $subject = 'Diary No. Generation.';
                                        send_mail_msg($to_email, $subject, $message, $to_user_name = "");
                                    }
                                    $output['success'] = 'success';
                                    if (isset($alloted_to_name) && !empty($alloted_to_name)) {
                                        $output['message'] = 'Diary No.' . $diary_no . '/' . $diary_year . ' has been created successfully! and allocated to  ' . $alloted_to_name;
                                    } else {
                                        $output['message'] = 'Diary No.' . $diary_no . '/' . $diary_year . ' has been created successfully!';
                                    }
                                } elseif ($next_stage == E_Filed_Stage) {
                                    $output['success'] = 'success';
                                    $output['message'] = 'updated to Efiled Stage.';
                                }
                            } else {
                                $output['success'] = 'errorsdfdsf';
                                $output['message'] = '';
                            }
                        } else {
                            $output['success'] = 'error';
                            $output['message'] = '';
                        }
                    } else {
                        $output['success'] = 'error';
                        $output['message'] = '';
                    }
                    break;
                case 'caveat':
                    $params = array();
                    $params['table_name'] = 'public.tbl_efiling_caveat';
                    $params['whereFieldName'] = 'ref_m_efiling_nums_registration_id';
                    $params['whereFieldValue'] = $registration_id;
                    $caveatDetailsData = $this->Common_model->getData($params);
                    if (
                        isset($caveatDetailsData[0]->caveat_num) && !empty($caveatDetailsData[0]->caveat_num)
                        && isset($caveatDetailsData[0]->caveat_year) && !empty($caveatDetailsData[0]->caveat_year)
                    ) {
                        $diary_no = (int)$caveatDetailsData[0]->caveat_num;
                        $diary_year = (int)$caveatDetailsData[0]->caveat_year;
                        $sc_diary_date = !empty($caveatDetailsData[0]->caveat_num_date) ? $caveatDetailsData[0]->caveat_num_date : NULL;
                        $caveat_details = array();
                        $caveat_details['caveat_num'] = $diary_no;
                        $caveat_details['caveat_year'] = $diary_year;
                        $caveat_details['caveat_num_date'] = $sc_diary_date;
                        $caveat_details['updated_by'] = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;
                        $caveat_details['updated_at'] = $curr_dt;
                        $caveat_details['updated_by_ip'] = getClientIP();
                        $stage_update_timestamp = $this->Get_CIS_Status_model->getStageUpdateTmestampCaseCaveat($registration_id, $curr_stage, $diaryStatus);
                        if (isset($stage_update_timestamp) && !empty($stage_update_timestamp)) {
                            //$next_stage = I_B_Approval_Pending_Admin_Stage;
                            //Changed on 02-06-2021 for marking caveat as e-filed once caveat number is generated as per current practice.
                            $next_stage = E_Filed_Stage;
                            $update_status = $this->Get_CIS_Status_model->update_icmis_case_status($registration_id, $next_stage, $curr_dt, $caveat_details, '', '', '', $efiling_type);
                            if (isset($update_status) && !empty($update_status)) {
                                if ($next_stage == E_Filed_Stage) {
                                    $cuase_title = '';
                                    if (!empty($caveatDetailsData[0]->pet_name) &&  !empty($caveatDetailsData[0]->res_name)) {
                                        $cuase_title = $caveatDetailsData[0]->pet_name . ' Vs ' . $caveatDetailsData[0]->res_name;
                                    } else  if (!empty($caveatDetailsData[0]->pet_name)) {
                                        $cuase_title = $caveatDetailsData[0]->pet_name;
                                    }
                                    $createdby = !empty($caveatDetailsData[0]->createdby) ? (int)$caveatDetailsData[0]->createdby : NULL;
                                    if (isset($createdby) && !empty($createdby)) {
                                        $params = array();
                                        $params['table_name'] = 'efil.tbl_users';
                                        $params['whereFieldName'] = 'id';
                                        $params['whereFieldValue'] = (int)$createdby;
                                        $params['is_active'] = '1';
                                        $userData = $this->Common_model->getData($params);
                                        $mobile = NULL;
                                        $email = NULL;
                                        $efiling_no = '';
                                        $first_name = '';
                                        $last_name = '';
                                        $userName = '';
                                        if (isset($userData) && !empty($userData)) {
                                            if (isset($userData) && !empty($userData)) {
                                                $mobile = (!empty($userData[0]->moblie_number) && isset($userData[0]->moblie_number)) ? $userData[0]->moblie_number : NULL;
                                                $email = (!empty($userData[0]->emailid) && isset($userData[0]->emailid)) ? $userData[0]->emailid : NULL;
                                                $first_name = (!empty($userData[0]->first_name) && isset($userData[0]->first_name)) ? $userData[0]->first_name : NULL;
                                                $last_name = (!empty($userData[0]->last_name) && isset($userData[0]->last_name)) ? $userData[0]->last_name : NULL;
                                            }
                                        }
                                    }
                                    if (isset($first_name) && !empty($first_name) && isset($last_name) && !empty($last_name)) {
                                        $userName = $first_name . ' ' . $last_name;
                                    } else if (isset($first_name) && !empty($first_name)) {
                                        $userName = $first_name;
                                    }
                                    $caveatNo = $diary_no . ' / ' . $diary_year;
                                    $current_date = date('d-m-Y H:i:s');
                                    $message = '';
                                    $arr = array();
                                    $arr['table_name'] = 'efil.tbl_efiling_nums';
                                    $arr['whereFieldName'] = 'registration_id';
                                    $arr['whereFieldValue'] = (int)$registration_id;
                                    $arr['is_active'] = '1';
                                    $efilingNoData = $this->Common_model->getData($arr);
                                    if (isset($efilingNoData[0]->efiling_no) && !empty($efilingNoData[0]->efiling_no)) {
                                        $efiling_no = $efilingNoData[0]->efiling_no;
                                    }
                                    if (isset($mobile) && !empty($mobile)) {
                                        // $message = 'Your caveat efiling no. ' . $efiling_no . ' , ' . $cuase_title . ' is filed with Caveat No. ' . $caveatNo . ' on ' . $current_date . '';
                                        $message = 'Your caveat efiling no. '.$efiling_no.' , '.$cuase_title.' is filed with Caveat No. '.$caveatNo.' on '.$current_date.' - Supreme Court of India';
                                        $smsRes = send_mobile_sms($mobile, $message, SCISMS_EFILING_CAVEAT);
                                        send_whatsapp_message($registration_id, $efiling_no, " - filed with Caveat Number $caveatNo");
                                    }
                                    if (isset($email) && !empty($email)) {
                                        $message = 'Dear ' . $userName . ' ,your caveat efiling no. ' . $efiling_no . ' , ' . $cuase_title . ' is filed with Caveat No. ' . $caveatNo . ' on ' . $current_date . '';
                                        $to_email = $email;
                                        $subject = 'Caveat No. Generation.';
                                        send_mail_msg($to_email, $subject, $message, $to_user_name = "");
                                    }
                                    $output['success'] = 'success';
                                    $output['message'] = 'Caveat No. ' . $diary_no . '/' . $diary_year . ' has been created successfully!.';
                                } /*elseif ($next_stage == E_Filed_Stage) {
                                        $output['success'] = 'success';
                                        $output['message']='updated to Efiled Stage.';
                                    }*/
                            } else {
                                $output['success'] = 'error';
                                $output['message'] = '';
                            }
                        } else {
                            $output['success'] = 'error';
                            $output['message'] = '';
                        }
                    } else {
                        $output['success'] = 'error';
                        $output['message'] = '';
                    }
                    break;
                default:
                    $output['success'] = 'error';
                    $output['message'] = '';
                    break;
            }
        } else {
            $output['success'] = 'error';
            $output['message'] = '';
        }
        return $output;
    }
}
