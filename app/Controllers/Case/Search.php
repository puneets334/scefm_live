<?php

namespace App\Controllers\Case;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\MiscellaneousDocs\MiscellaneousDocsModel;
use App\Models\IA\IAModel;
use App\Models\Mentioning\MentioningModel;
use App\Models\Certificate\CertificateModel;
use App\Models\Citation\CitationModel;
use App\Models\Mentioning\ListingModel;
use App\Models\CaseSearch\CaseSearchModel;
use App\Models\AppearingFor\AppearingForModel;
use App\Models\OldCaseRefiling\OldEfilingDocsModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\Clerk\ClerkModel;
use App\Models\Consume\ConsumeDataModel;
use App\Models\Cron\DefaultModel;
use App\Models\FetchIcmisData\FetchDataModel;

class Search extends BaseController {

    protected $Common_model;
    protected $MiscellaneousDocsModel;
    protected $IAModel;
    protected $MentioningModel;
    protected $CertificateModel;
    protected $CitationModel;
    protected $ListingModel;
    protected $CaseSearchModel;
    protected $AppearingForModel;
    protected $OldEfilingDocsModel;
    protected $efiling_webservices;
    protected $slice;
    protected $form_validation;
    protected $session;
    protected $config;
    protected $request;
    protected $validation;
    protected $ConsumeDataModel;
    protected $DefaultModel;
    protected $FetchDataModel;
    protected $Clerk_model;

    public function __construct() {
        parent::__construct();
        $this->Common_model = new CommonModel();
        $this->MiscellaneousDocsModel = new MiscellaneousDocsModel();
        $this->IAModel = new IAModel();
        $this->MentioningModel = new MentioningModel();
        $this->CertificateModel = new CertificateModel();
        $this->CitationModel = new CitationModel();
        $this->ListingModel = new ListingModel();
        $this->CaseSearchModel = new CaseSearchModel();
        $this->AppearingForModel = new AppearingForModel();
        $this->OldEfilingDocsModel = new OldEfilingDocsModel();
        $this->efiling_webservices = new Efiling_webservices();
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->validation = \Config\Services::validation();
        $this->ConsumeDataModel = new ConsumeDataModel();
        $this->DefaultModel = new DefaultModel();
        $this->FetchDataModel = new FetchDataModel();
        $this->Clerk_model = new ClerkModel();
    }


    public function index($efiling_type = null) {
        $efiling_type = getSessionData('efiling_type');
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, SR_ADVOCATE,AMICUS_CURIAE_USER);
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return response()->redirect(base_url('/'));
        }
        if ($efiling_type != 'misc' && $efiling_type != "ia" && $efiling_type != 'mentioning' && $efiling_type != 'citation' && $efiling_type != 'certificate' && $efiling_type != 'adjournment_letter' && $efiling_type != 'refile_old_efiling_cases') {
            unset($_SESSION['efiling_type']);
            return response()->redirect(base_url('dashboard'));
        } else{
            $data['estab_details'] =  $this->Common_model->get_establishment_details(); //for responsive__variant
            $data['sc_case_type'] = $this->CaseSearchModel->get_sci_case_type();
            if ($efiling_type == 'refile_old_efiling_cases') {
                $this->render('casesearchForOldEfilingCases.case_search_view', $data, TRUE);
            } else {
                $this->render('casesearch.case_search_view', $data, TRUE);
            }
        } 
    }

    public function search_case_details() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK,AMICUS_CURIAE_USER);
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return response()->redirect(base_url('/')); 
        }
        $diary_type = $_POST['search_filing_type'] ?? $this->request->getGet('search_filing_type');
        if ($diary_type == 'diary') {
            $this->validation->setRules([
                "search_filing_type" => [
                    "label" => "Case Type",
                    "rules" => "required|trim"
                ],
                "diaryno" => [
                    "label" => "Diary Number",
                    "rules" => "required|trim|min_length[1]|max_length[10]|numeric"
                ],
                "diary_year" => [
                    "label" => "Diary Year",
                    "rules" => "required|trim|min_length[4]|max_length[4]|numeric"
                ],
            ]);
        } elseif ($diary_type == 'register') {
            $this->validation->setRules([
                "search_filing_type" => [
                    "label" => "Case Type",
                    "rules" => "required|trim"
                ],
                "sc_case_type" => [
                    "label" => "Case Type",
                    "rules" => "required|trim"
                ],
                "case_number" => [
                    "label" => "Case Number",
                    "rules" => "required|trim|min_length[1]|max_length[10]|numeric"
                ],
                "case_year" => [
                    "label" => "Case Year",
                    "rules" => "required|trim|min_length[4]|max_length[4]|numeric"
                ],
            ]);
        }
        if ($this->validation->withRequest($this->request)->run() === FALSE) {
            $data = [
                'validation' => $this->validator,
            ];
        } else {
            if ($diary_type == 'diary') {
                $this->Common_model->get_establishment_details();
                $web_service_result = $this->efiling_webservices->get_case_diary_details_from_SCIS(escape_data($_POST['diaryno'] ?? $this->request->getGet('diaryno')), escape_data($_POST['diary_year'] ?? $this->request->getGet('diary_year')));
            } else if ($diary_type == 'register') {
                $web_service_result = $this->efiling_webservices->get_case_details_from_SCIS(url_decryption(escape_data($this->request->getGet('sc_case_type'))), escape_data($this->request->getGet('case_number')), escape_data($this->request->getGet('case_year')));
            }
            if (!empty($web_service_result->message)) {
                echo '3@@@ No Record found!';
                exit(0);
            } elseif (!empty($web_service_result->case_details[0])) {
                $diary_no = $web_service_result->case_details[0]->diary_no;
                $diary_year = $web_service_result->case_details[0]->diary_year;
                if (!empty($diary_no) && !empty($diary_year)) {
                    if ($_SESSION['login']['ref_m_usertype_id']==AMICUS_CURIAE_USER) {
                        $amicus_curiae_request_params = ['type' => 'D','value' => $diary_no.$diary_year];
                        $amicus_curiae_advocate = json_decode(curl_get_contents(ICMIS_SERVICE_URL.'/ConsumedData/get_advocateDetails?' . http_build_query($amicus_curiae_request_params)));
                        if (isset($amicus_curiae_advocate->data) && !empty($amicus_curiae_advocate->data[0]->is_ac !='Y')) {
                            echo '3@@@ No Record found!.';
                            exit(0);
                        }
                    }
                    $listing_data = $this->efiling_webservices->get_last_listed_details($diary_no, $diary_year);
                    if (isset($listing_data->listed) && is_array($listing_data->listed) && isset($listing_data->listed[0])) {
                        $data['listing_details'] = $listing_data->listed[0];
                    } else {
                        // Handle the case where $listing_data->listed is not set or not as expected
                        $data['listing_details'] = null;
                    }
                    $_SESSION['listing_details'] = $data['listing_details'];
                    $data['mentioning_request_details'] = $this->ListingModel->get_mentioning_request_details($diary_no, $diary_year);
                    $data['case_uncured_defects_details'] = $this->efiling_webservices->getCaseDefectDetails($diary_no, $diary_year); // add by kbpujari : To restrict AOR to file Misc.Document(s)/IA(s) in any of the defective matters, whether the case is filed  through efiling, new filing or filed physically
                    if (empty($data['case_uncured_defects_details']->defects)) {
                        //start check Mark All Defects Cured
                        $registration_id = $this->ConsumeDataModel->getRegistrationIdByDiaryNo($diary_no . $diary_year);
                        if (!empty($registration_id)) {
                            $current_stage = $this->DefaultModel->get_current_stage($registration_id);
                            $allowed_case_types = array('39', '9', '10', '25', '26', '19', '20');
                            if ($current_stage &&  !(in_array($web_service_result->case_details[0]->active_casetype_id, $allowed_case_types) || in_array($web_service_result->case_details[0]->casetype_id, $allowed_case_types))) {
                                if ($current_stage[0]['stage_id'] != E_Filed_Stage) {                                   
                                }
                            }
                        }
                    } else { 
                        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'){
                            echo '3@@@ Please note, this case is defective. Kindly, cure all defects notified by the Registry through Refiling option.';
                        }else{
                            $data['err'] = 'Please note, this case is defective. Kindly, cure all defects notified by the Registry through Refiling option.';
                            return $this->render('casesearch.search_result_view', $data);
                            exit(0);
                        }
                    }
                    $data['searched_case_details'] = $web_service_result->case_details[0];
                    if($_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
                        $data['clerk_aor'] = $this->Clerk_model->get_clerk_aor($_SESSION['login']['id']);
                        if (isset($registration_id) && !empty($registration_id)) {
                            $data['selected_aor'] = $this->Clerk_model->selected_aor_for_case($registration_id);
                        }
                    }
                    if ($diary_type == 'diary') {
                        if ((isset($_POST['is_direct_access']) && $_POST['is_direct_access'])) {
                            return $this->render('casesearch.search_result_view', $data);
                        } else{
                            echo '1@@@';
                            echo $this->render('casesearch.search_result_view', $data);
                        }
                    } else if ($diary_type == 'register') {
                        echo '2@@@';
                        echo $this->render('casesearch.search_result_view', $data, TRUE);
                    }
                } else {
                    $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">You are not appearing in this case. Please Select "Want to represent new litigant." option and proceed..</p></div>');
                    echo '3@@@ No Record found!';
                    exit(0);
                }
            } else {
                // echo '3@@@ Some error!';
                echo '3@@@ No Record found!';
                exit(0);
            }
        }
    }

    function save_searched_case_result() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, AMICUS_CURIAE_USER);        
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return response()->redirect(base_url('/')); 
        }
        $this->validation->setRules([
            'searched_details' => [
                'label' => 'Searched Details',
                'rules' => 'required|trim|encrypt_check'
            ],
            'confirm_response' => [
                'label' => 'Confirmation',
                'rules' => 'required|trim|in_list[yes,no]'
            ]
        ]);
        if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {
            $this->validation->setRules([
                'impersonated_aor' => [
                    'label' => 'AOR',
                    'rules' => 'trim|is_required'
                ]
            ]);
        }
        $searched_details = url_decryption(escape_data($_POST['searched_details']));
        $searched_details = explode('$$$', $searched_details);
        if (count($searched_details) != 8) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Data tempered !.</div>');
            return redirect()->to(base_url('case/search/' . url_encryption($_SESSION['efiling_type'])));
            exit(0);
        }
        if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {
            if (isset($_POST['impersonated_aor']) && !empty($_POST['impersonated_aor'])) {
                $_SESSION['login']['aor_code'] = $this->request->getPost("impersonated_aor");
            } else{
                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">AOR field is required.</div>');
                return redirect()->to(base_url('case/search/' . url_encryption($_SESSION['efiling_type'])));
                exit(0);
            }
        }        
        $parties_list = '';
        $diary_no = $searched_details[0];
        $diary_year = $searched_details[1];
        $order_date = $searched_details[2];
        $cause_title = $searched_details[3];
        $reg_no_display = $searched_details[4];
        $c_status = $searched_details[5];
        $pno = $searched_details[6];
        $rno = $searched_details[7];
        if (isset($_SESSION['parties_list'])) {
            $parties_list = $_SESSION['parties_list'];
        }
        $confirm_response = escape_data($_POST['confirm_response']);
        if ($confirm_response == 'yes') {
            $_SESSION['efiling_details'] = '';           
            if (isset($_POST['radio_appearing_for'])) {
                $intervenorName = "";
                $radio_appearing_for = escape_data($_POST['radio_appearing_for']);
                if($radio_appearing_for == 'E') {
                    $this->session->set('radio_appearing_for',$radio_appearing_for);
                    $appearance_exists = $this->MiscellaneousDocsModel->check_existence_of_appearing_for($diary_no,$diary_year);
                    if(!$appearance_exists){
                        $diary_number = $diary_no.$diary_year;
                        $advPartyDetails = $this->efiling_webservices->getAdvPartyMappingBydiaryNo($diary_number);
                        foreach($advPartyDetails as $index => $detail) {
                            $result = $this->FetchDataModel->updateAdvocatePartyDetails($detail);
                        }
                        $appearance_exists=$this->MiscellaneousDocsModel->check_existence_of_appearing_for($diary_no,$diary_year);
                        if(!$appearance_exists) {
                            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">You are not appearing in this case. Please Select "Want to represent new litigant." option and proceed..</p></div>');
                            return redirect()->to(base_url('case/search/' . url_encryption($_SESSION['efiling_type'])));
                        }
                    }
                }
                // CaseSearchModel
                // 12/11/2020 check for advocate exist
                else if ($radio_appearing_for == 'N') {
                    $this->session->set('radio_appearing_for', $radio_appearing_for);
                }
                // 12/11/2020
                else if ($radio_appearing_for == 'I') {
                    $this->session->setFlashdata('radio_appearing_for', $radio_appearing_for);
                    $intervenorName = !empty($_POST['txtIntervenorName']) ? $_POST['txtIntervenorName'] : NULL;
                }
            }
            //END
            $pet_sr_no_array = array();
            $pet_sr_no_show_array = array();
            $pet_party_name_array = array();
            $res_sr_no_array = array();
            $res_sr_no_show_array = array();
            $res_party_name_array = array();
            if(isset($parties_list) && !empty($parties_list)) {
                foreach ($parties_list as $party_list) {
                    if ($party_list->pet_res == 'P') {
                        $pet_sr_no_array[] = $party_list->sr_no;
                        $pet_sr_no_show_array[] = $party_list->sr_no_show;
                        $pet_party_name_array[] = $party_list->partyname;
                    } elseif ($party_list->pet_res == 'R') {
                        $res_sr_no_array[] = $party_list->sr_no;
                        $res_sr_no_show_array[] = $party_list->sr_no_show;
                        $res_party_name_array[] = $party_list->partyname;
                    }
                }
            }
            //--PETITIONER PARTY DETAILS---//
            $pet_sr_no = implode("##", $pet_sr_no_array);
            $pet_sr_no_show = implode("##", $pet_sr_no_show_array);
            $pet_party_name = implode("##", $pet_party_name_array);
            //--RESPONDENT PARTY DETAILS---//
            $res_sr_no = implode("##", $res_sr_no_array);
            $res_sr_no_show = implode("##", $res_sr_no_show_array);
            $res_party_name = implode("##", $res_party_name_array);
            $sci_cases_details = array(
                'reg_no_display' => $reg_no_display,
                'cause_title' => $cause_title,
                'c_status' => $c_status,
                'p_no' => $pno,
                'r_no' => $rno,
                'p_sr_no' => $pet_sr_no,
                'p_sr_no_show' => $pet_sr_no_show,
                'p_partyname' => $pet_party_name,
                'r_sr_no' => $res_sr_no,
                'r_sr_no_show' => $res_sr_no_show,
                'r_partyname' => $res_party_name
            );
            $curr_dt_time = date('Y-m-d H:i:s');
            $diary_saved = FALSE;
            $diary_already_exists = $this->CaseSearchModel->get_diary_details($diary_no, $diary_year);
            if (isset($diary_already_exists) && !empty($diary_already_exists)) {
                $_SESSION['tbl_sci_case_id'] = $diary_already_exists[0]->id;
                if ($diary_already_exists[0]->reg_no_display != $reg_no_display || $diary_already_exists[0]->cause_title != $cause_title || $diary_already_exists[0]->c_status != $c_status || $diary_already_exists[0]->p_no != $pno || $diary_already_exists[0]->r_no != $rno || $diary_already_exists[0]->p_sr_no != $pet_sr_no || $diary_already_exists[0]->p_sr_no_show != $pet_sr_no_show || $diary_already_exists[0]->p_partyname != $pet_party_name || $diary_already_exists[0]->r_sr_no != $res_sr_no || $diary_already_exists[0]->r_sr_no_show != $res_sr_no_show || $diary_already_exists[0]->r_partyname != $res_party_name) {
                    $sci_cases_details1 = array(
                        'updated_on' => $curr_dt_time,
                        'updated_by' => session()->get('login')['id'],
                        'updated_by_ip' => getClientIP()
                    );
                    $sci_cases_details = array_merge($sci_cases_details1, $sci_cases_details);
                    $diary_saved = $this->CaseSearchModel->add_update_sci_cases_details($sci_cases_details, array('diary_no' => $diary_no, 'diary_year' => $diary_year));
                } else {
                    $diary_saved = TRUE;
                }
            } else {
                $sci_cases_details1 = array(
                    'diary_no' => $diary_no,
                    'diary_year' => $diary_year,
                    'created_on' => $curr_dt_time,
                    'created_by' => session()->get('login')['id'],
                    'created_by_ip' => getClientIP()
                );
                $sci_cases_details = array_merge($sci_cases_details1, $sci_cases_details);
                $diary_saved = $this->CaseSearchModel->add_update_sci_cases_details($sci_cases_details, NULL);
            }
            // Flow Come to this point 200624
            if (!$diary_saved) {
                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Some error ! Please try after some time.</div>');
                return redirect()->to(base_url('dashboard'));
                exit(0);
            } else {
                $diary_details = array('diary_no' => $diary_no, 'diary_year' => $diary_year);
                if (isset($_SESSION['efiling_type']) && $_SESSION['efiling_type'] == 'misc') {
                    $registration_id = $this->MiscellaneousDocsModel->generate_efil_num_n_add_case_details($diary_details, $curr_dt_time, $radio_appearing_for);
                    $redirectURL = 'miscellaneous_docs/defaultController/' . url_encryption($registration_id . '#' . E_FILING_TYPE_MISC_DOCS . '#' . Draft_Stage);
                    $type = 'Efiled Misc. docs No. ';
                    $aor_id=$_SESSION['login']['id'];
                    if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {
                        if (isset($registration_id) && isset($_SESSION['login']['aor_code'])){
                            $this->Clerk_model->clerk_filings_insert($registration_id,$_SESSION['login']['aor_code']);
                            $aorData = getAordetailsByAORCODE($_SESSION['login']['aor_code']);
                            $aor_id=!empty($aorData) ? $aorData[0]->id:0;
                            if (empty($aor_id)){
                                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">*Please AOR field is required</p></div>');
                                $_SESSION['MSG']='<div class="alert alert-danger text-center">*Please AOR field is required</p></div>';
                                return redirect()->to(base_url('case/search/' . url_encryption($_SESSION['efiling_type']))); exit(0);
                            }
                        }
                    }
                    if (isset($_POST['radio_appearing_for']) && ($_POST['radio_appearing_for'] == 'E') && !empty($registration_id) && !empty(session()->get('login')['id'])) {
                        $data['appearing_for_details'] = $this->AppearingForModel->get_appearing_for_details($registration_id, $aor_id);
                        if(is_array($data['appearing_for_details']) && isset($data['appearing_for_details'])){
                            if (is_array($data['appearing_for_details']) && isset($data['appearing_for_details'][0]) && !empty($data['appearing_for_details'][0]['partytype']) && ($data['appearing_for_details'][0]['partytype'] == 'P') || ($data['appearing_for_details'][0]['partytype'] == 'R') || ($data['appearing_for_details'][0]['partytype'] == 'I') || ($data['appearing_for_details'][0]['partytype'] == 'N')) {
                                if (($data['appearing_for_details'][0]['partytype'] == 'I') || ($data['appearing_for_details'][0]['partytype'] == 'N')) {
                                    $redirectURL = 'uploadDocuments';
                                }
                            } else {
                                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">You are not appearing in this case. Please Select "Want to represent new litigant." option and proceed..</p></div>');
                                return redirect()->back();
                            }
                        } else {
                            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">You are not appearing in this case. Please Select "Want to represent new litigant." option and proceed..</p></div>');
                            return redirect()->back();
                        }
                        
                    }
                } elseif (isset($_SESSION['efiling_type']) && $_SESSION['efiling_type'] == 'ia') {
                    $intervenorNameString = NULL;
                    if (isset($intervenorName) && !empty($intervenorName) && count($intervenorName) > 0) {
                        $intervenorNameString = implode(',', $intervenorName);
                    }
                    $registration_id = $this->IAModel->generate_efil_num_n_add_case_details($diary_details, $curr_dt_time, $radio_appearing_for, '');
                    $redirectURL = 'IA/defaultController/' . url_encryption($registration_id . '#' . E_FILING_TYPE_IA . '#' . Draft_Stage);
                    $type = 'Efiled IA No. ';
                    $aor_id=$_SESSION['login']['id'];
                    if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {
                        if (isset($registration_id) && isset($_SESSION['login']['aor_code'])){
                            $this->Clerk_model->clerk_filings_insert($registration_id,$_SESSION['login']['aor_code']);
                            $aorData = getAordetailsByAORCODE($_SESSION['login']['aor_code']);
                            $aor_id=!empty($aorData) ? $aorData[0]->id:0;
                            if (empty($aor_id)){
                                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">*Please AOR field is required</p></div>');
                                $_SESSION['MSG']='<div class="alert alert-danger text-center">*Please AOR field is required</p></div>';
                                return redirect()->to(base_url('case/search/' . url_encryption($_SESSION['efiling_type']))); exit(0);
                            }
                        }
                    }
                    if (isset($_POST['radio_appearing_for']) && ($_POST['radio_appearing_for'] == 'E') && !empty($registration_id) && !empty(getSessionData('login')['id'])) {
                        $data['appearing_for_details'] = $this->AppearingForModel->get_appearing_for_details($registration_id, $aor_id);
                    }
                } elseif (isset($_SESSION['efiling_type']) && $_SESSION['efiling_type'] == 'mentioning') {
                    $registration_id = $this->MentioningModel->generate_efil_num_n_add_case_details($diary_details, $curr_dt_time);
                    $redirectURL = 'mentioning/defaultController/' . url_encryption($registration_id . '#' . E_FILING_TYPE_MENTIONING . '#' . Draft_Stage);
                    $type = 'Efiled Mentioning request No. ';
                } elseif (isset($_SESSION['efiling_type']) && $_SESSION['efiling_type'] == 'certificate') {
                    $registration_id = $this->CertificateModel->generate_efil_num_n_add_case_details($diary_details, $curr_dt_time);
                    $redirectURL = 'certificate/DefaultController/' . url_encryption($registration_id . '#' . E_FILING_TYPE_CERTIFICATE_REQUEST . '#' . Draft_Stage);
                    $type = 'Efiled certificate request No. ';
                } elseif (isset($_SESSION['efiling_type']) && $_SESSION['efiling_type'] == 'citation') {
                    $registration_id = $this->CitationModel->generate_efil_num_n_add_case_details($diary_details, $curr_dt_time);
                    $redirectURL = 'citation/defaultController/' . url_encryption($registration_id . '#' . E_FILING_TYPE_CITATION . '#' . Draft_Stage);
                    $type = 'Efiled citation No. ';
                } elseif (isset($_SESSION['efiling_type']) && $_SESSION['efiling_type'] == 'refile_old_efiling_cases') {
                    $registration_id = $this->OldEfilingDocsModel->generate_efil_num_n_add_case_details($diary_details, $curr_dt_time);
                    $redirectURL = 'oldCaseRefiling/defaultController/' . url_encryption($registration_id . '#' . OLD_CASES_REFILING . '#' . Draft_Stage);
                    $type = 'E-filing No. ';
                }
                unset($_SESSION['efiling_type']);
                if (isset($registration_id)  && !empty($registration_id)) {
                    $aor_id=$_SESSION['login']['id'];
                    if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {
                        if (isset($registration_id) && isset($_SESSION['login']['aor_code'])){
                            $aorData = getAordetailsByAORCODE($_SESSION['login']['aor_code']);
                            $aor_id=!empty($aorData) ? $aorData[0]->id:0;
                            if (empty($aor_id)){
                                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">*Please AOR field is required</p></div>');
                                $_SESSION['MSG']='<div class="alert alert-danger text-center">*Please AOR field is required</p></div>';
                                return redirect()->to(base_url('case/search/')); exit(0);
                            }
                        }
                    }
                    //add intervenor data
                    if (isset($intervenorName) && !empty($intervenorName) && count($intervenorName) > 0) {
                        for ($i = 0; $i < count($intervenorName); $i++) {
                            $interVentionArr = array();
                            $interVentionArr['registration_id'] = !empty($registration_id) ? $registration_id : NULL;
                            $interVentionArr['p_r_type'] = 'I';
                            $interVentionArr['party_name'] = !empty($intervenorName[$i]) ?  $intervenorName[$i] : NULL;
                            $interVentionArr['created_by'] = !empty($aor_id) ? $aor_id : NULL;
                            $interVentionArr['created_on'] = date('Y-m-d H:i:s');
                            $interVentionArr['created_by_ip'] = getClientIP();
                            $interVentionArr['updated_by'] = NULL;
                            $interVentionArr['updated_on'] = NULL;
                            $interVentionArr['updated_by_ip'] = NULL;
                            $interVentionArr['is_deleted'] = FALSE;
                            $interVentionArr['deleted_by'] = NULL;
                            $interVentionArr['deleted_on'] = NULL;
                            $interVentionArr['deleted_by_ip'] = NULL;
                            $this->CaseSearchModel->addInterVention($interVentionArr);
                        }
                    }
                    //efiling num generated and being redirected to case details page to proceed further.
                    $user_name = getSessionData('login')['first_name'] . ' ' . getSessionData('login')['last_name'];
                    if (!empty($_SESSION['efiling_details'])) {
                        $efiling_num = $_SESSION['efiling_details']['efiling_no'];
                    } else {
                        $efiling_num = '';
                    }
                    $sentSMS = $type . efile_preview($efiling_num) . " generated from your efiling account & still pending for final submit. - Supreme Court of India";
                    $subject = $type . efile_preview($efiling_num) . " generated from your efiling account";
                    send_mobile_sms($_SESSION['login']['mobile_number'], $sentSMS, SCISMS_Submit_Pending);
                    send_mail_msg($_SESSION['login']['emailid'], $subject, $sentSMS, $user_name);
                    return response()->redirect(base_url($redirectURL));
                } else {
                    $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">There are Some  Error ! Please try after some time.</div>');
                    return response()->redirect(base_url('dashboard'));
                }
            }
        } else {
            if ($confirm_response == 'no' && $_SESSION['efiling_type'] == 'misc') {
                // return redirect()->to(base_url('case/document/crud'));
                return redirect()->back();
            } elseif ($confirm_response == 'no' && $_SESSION['efiling_type'] == 'ia') {
                return redirect()->back();
            } elseif ($confirm_response == 'no' && $_SESSION['efiling_type'] == 'mentioning') {
                return redirect()->back();
            } elseif ($confirm_response == 'no' && $_SESSION['efiling_type'] == 'certificate') {
                return redirect()->back();
            } elseif ($confirm_response == 'no' && $_SESSION['efiling_type'] == 'citation') {
                return redirect()->back();
            }
            return redirect()->to(base_url('dashboard'));
        }
    }

    function get_direct_access_page() {
        $this->load->view('templates/header');
        $this->load->view('casesearch/direct_access_view');
        $this->load->view('templates/footer');
    }

    /* code written for old efiling cases : start */
    public function search_old_efiling_case_details() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK,AMICUS_CURIAE_USER);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $diary_type = $_REQUEST['search_filing_type'];
        $validation = \Config\Services::validation();
        $rules = [
            "search_filing_type" => [
                "label" => "Case Type",
                "rules" => "required|trim|in_list[diary,register]"
            ]
        ];
        if ($diary_type == 'diary') {
            $rules = [
                "diaryno" => [
                    "label" => "Diary Number",
                    "rules" => "required|trim|min_length[1]|max_length[10]|numeric"
                ],
                "diary_year" => [
                    "label" => "Diary Year",
                    "rules" => "required|trim|min_length[4]|max_length[4]|numeric"
                ]
            ];
        } 
        if ($diary_type == 'register') {
            $rules = [
                "sc_case_type" => [
                    "label" => "Case Type",
                    "rules" => "required|trim"
                ],
                "case_number" => [
                    "label" => "Case Number",
                    "rules" => "required|trim|min_length[1]|max_length[10]|numeric"
                ],
                "case_year" => [
                    "label" => "Case Year",
                    "rules" => "required|trim|min_length[4]|max_length[4]|numeric"
                ]
            ];
        }
        if ($this->validate($rules) === FALSE) {
            $data = [
                'validation' => $this->validator
            ];
            return $this->render('casesearchForOldEfilingCases.case_search_view', $data);
        } else {
            if ($diary_type == 'diary') {
                $web_service_result = $this->efiling_webservices->get_case_diary_details_from_SCIS(escape_data($this->request->getGet('diaryno')), escape_data($this->request->getGet('diary_year')));
            } else if ($diary_type == 'register') {
                $web_service_result = $this->efiling_webservices->get_case_details_from_SCIS(url_decryption(escape_data($_GET['sc_case_type'])), escape_data($_GET['case_number']), escape_data($_GET['case_year']));
            }
            if (!empty($web_service_result->message)) {
                echo '3@@@ No Record found!';
                exit(0);
            } elseif (!empty($web_service_result->case_details[0])) {
                $diary_no = $web_service_result->case_details[0]->diary_no;
                $diary_year = $web_service_result->case_details[0]->diary_year;
                if (!empty($diary_no) && !empty($diary_year)) {
                    $listing_data = $this->efiling_webservices->get_last_listed_details($diary_no, $diary_year);                    
                    $data['listing_details'] = !empty($listing_data->listed) ? $listing_data->listed[0] : '';
                    $_SESSION['listing_details'] = $data['listing_details'];
                    $data['old_efiling_cases'] = $this->Common_model->get_old_efiling_cases($diary_no . $diary_year, $_SESSION['login']['aor_code']);                    
                    if (empty($data['old_efiling_cases'])) {
                        echo '3@@@ Cases which are filed by you through old e-filing, Such cases can be re-filed with this option.';
                        exit(0);
                    } else {
                        // logic written for aor can only refile the already file old efilinf case after 3 days
                        $data['case_last_refiled_details'] = $this->efiling_webservices->checkInTheOldEfilingCasesList($diary_no, $diary_year);
                        if (!empty($data['case_last_refiled_details'])) {
                            $last_refiled_on = !empty($data['case_last_refiled_details']->case_refiling_status) ? $data['case_last_refiled_details']->case_refiling_status[0]->created_at : NULL;
                            $last_refiled_on = date('d-m-Y', strtotime($last_refiled_on));
                            $curDate = date('d-m-Y');
                            $datediff = strtotime($curDate) - strtotime($last_refiled_on);
                            $date_diff_in_no_of_days = round($datediff / (60 * 60 * 24));
                            if ($date_diff_in_no_of_days <= 3) {
                                echo '3@@@ Please note, You can re-filed this case again after 3 days from the last refiling date i.e.' . $last_refiled_on;
                                exit(0);
                            }
                        }
                    }
                    $data['case_uncured_defects_details'] = $this->efiling_webservices->getCaseDefectDetails($diary_no, $diary_year); // add by kbpujari : To restrict AOR to file Misc.Document(s)/IA(s) in any of the defective matters, whether the case is filed  through efiling, new filing or filed physically
                    if (empty($data['case_uncured_defects_details']->defects)) {
                        echo '3@@@ Please note, All the notified defects of this case,filed through old -efiling application has been already cured.';
                        exit(0);
                    }
                    $data['searched_case_details'] = $web_service_result->case_details[0];
                    $result = $this->render('casesearchForOldEfilingCases.search_result_view', $data);
                    if ($diary_type == 'diary') {
                        if ((isset($_POST['is_direct_access']) && $_POST['is_direct_access'])) {
                            echo $result;
                            $this->Common_model->get_establishment_details();
                            echo $this->render('casesearchForOldEfilingCases.search_result_view', $data);
                        } else {
                            echo '1@@@' . $result;
                            echo $this->render('casesearchForOldEfilingCases.search_result_view', $data);
                        }
                    } else if ($diary_type == 'register') {
                        echo '2@@@' . $result;
                        echo $this->render('casesearchForOldEfilingCases.search_result_view', $data);
                    }
                } else {
                    echo '3@@@ No Record found!';
                    exit(0);
                }
            } else {
                echo '3@@@ Some error!';
                exit(0);
            }
        }
    }

}