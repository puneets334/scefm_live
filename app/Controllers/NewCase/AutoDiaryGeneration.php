<?php
namespace App\Controllers\NewCase;

use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\Admin\EfilingActionModel;
use App\Models\Citation\CitationModel;
use App\Models\Common\CommonModel;
use App\Models\Cron\DefaultModel;
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

class AutoDiaryGeneration extends BaseController
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
    protected $Default_model;
    protected $encrypter;
    protected $Get_CIS_Status_model;
    protected $Act_sections_model;
    protected $Citation_model;
    protected $request;
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
        $this->Default_model = new DefaultModel();
        $this->Get_CIS_Status_model = new GetCISStatusModel();
        $this->Act_sections_model = new ActSectionsModel();
        $this->Citation_model = new CitationModel();
        $this->encrypter = \Config\Services::encrypter();
        $this->request = \Config\Services::request();
    }
    public function byAOR($registration_id=NULL,$efiling_type=NULL,$bearerToken=NULL)
    {
        $registration_id = !empty($_SESSION['efiling_details']['registration_id']) ? $_SESSION['efiling_details']['registration_id'] : NULL;
        if (empty($registration_id)) {
            echo "<script>alert('registration id is required');
window.location.href='" . base_url() . "newcase/view';</script>";exit();
        } else {
            $registration_id = !empty($_SESSION['efiling_details']['registration_id']) ? $_SESSION['efiling_details']['registration_id'] : NULL;
            $efiling_type = !empty($_SESSION['efiling_details']['efiling_type']) ? $_SESSION['efiling_details']['efiling_type'] : NULL;
            $bearerToken = '3d07510c2c3348f58a04f63bc9a63296';
            $diaryGenerationStatus=null;
            if(!empty($bearerToken))
            {
                if($bearerToken=="3d07510c2c3348f58a04f63bc9a63296")
                {
                    if(!empty($registration_id) && !empty($efiling_type))
                    {
                        $stages_array = array(Transfer_to_IB_Stage); //Transfer_to_IB_Stage=8
                        $isCaseInApprovedStage=$this->Common_model->getApproveStagesCasesListDetails($registration_id,$stages_array); // Method to check and verify the case Approve status
                        if(!empty($isCaseInApprovedStage))
                        {
                            $efiling_no=$isCaseInApprovedStage[0]['efiling_no'];
                            $ref_m_efiled_type_id=$isCaseInApprovedStage[0]['ref_m_efiled_type_id'];
                            $diaryGenerationStatus=$this->getAllFilingDetailsByRegistrationId($efiling_no,$registration_id,$ref_m_efiled_type_id,$efiling_type); //function written for Auto diarization on 29072023
                            //echo $diaryGenerationStatus;
                            $diaryGenerationStatus=$diaryGenerationStatus;
                        }
                    }
                    else
                        echo  "Registration_id and Efiling_type are Empty !!!";
                }
                else
                    echo "You are not Authorized";
            }

            if($diaryGenerationStatus['success'] == 'success')
            {
                $this->session->setFlashdata('msg', '<div class="alert alert-success text-center text-bg">' . $diaryGenerationStatus['message'] . '</div>');
                $_SESSION['efiling_details']['stage_id'] = Initial_Approaval_Pending_Stage;
            }
            else
            {
                $this->session->setFlashdata('msg', '<div class="alert alert-success text-center font-weight-bold"> Your E-filing has been submitted successfully for Diary Number, Kindly check after 30 Minutes </div>');
            }
            return redirect()->to(base_url('newcase/view'));
           // return json_encode($diaryGenerationStatus);
        }

    }
    public function index($registration_id=NULL,$efiling_type=NULL,$bearerToken=NULL)
    {
        //$postData = json_decode(file_get_contents('php://input'), true);
        if(!empty($this->request->getGet())){
            $registration_id = !empty($this->request->getGet('registration_id'))?$this->request->getGet('registration_id'):NULL;
            $efiling_type = !empty($this->request->getGet('efiling_type'))?$this->request->getGet('efiling_type'):NULL;
            $bearerToken = !empty($this->request->getGet('token'))?$this->request->getGet('token'):NULL;
        }
        $diaryGenerationStatus=null;
        if(!empty($bearerToken))
        {
            if($bearerToken=="3d07510c2c3348f58a04f63bc9a63296")
            {
                if(!empty($registration_id) && !empty($efiling_type))
                {
                    $stages_array = array(Transfer_to_IB_Stage); //Transfer_to_IB_Stage=8
                    $isCaseInApprovedStage=$this->Common_model->getApproveStagesCasesListDetails($registration_id,$stages_array); // Method to check and verify the case Approve status
                    if(!empty($isCaseInApprovedStage))
                    {
                        $efiling_no=$isCaseInApprovedStage[0]['efiling_no'];
                        $ref_m_efiled_type_id=$isCaseInApprovedStage[0]['ref_m_efiled_type_id'];
                        $diaryGenerationStatus=$this->getAllFilingDetailsByRegistrationId($efiling_no,$registration_id,$ref_m_efiled_type_id,$efiling_type); //function written for Auto diarization on 29072023
                    }
                }
                else
                    echo  "Registration_id and Efiling_type are Empty !!!";
            }
            else
                echo "You are not Authorized";
        }
        echo json_encode($diaryGenerationStatus);
    }
    
    public function generateAutoDiary($registration_id,$efiling_type)
    {
        //$postData = json_decode(file_get_contents('php://input'), true);
        $diaryGenerationStatus=null;
        if(!empty($registration_id) && !empty($efiling_type))
        {
            $stages_array = array(Transfer_to_IB_Stage); //Transfer_to_IB_Stage=8
            $isCaseInApprovedStage=$this->Common_model->getApproveStagesCasesListDetails($registration_id,$stages_array); // Method to check and verify the case Approve status
            if(!empty($isCaseInApprovedStage))
            {
                $efiling_no=$isCaseInApprovedStage[0]['efiling_no'];
                $ref_m_efiled_type_id=$isCaseInApprovedStage[0]['ref_m_efiled_type_id'];
                $diaryGenerationStatus=$this->getAllFilingDetailsByRegistrationId($efiling_no,$registration_id,$ref_m_efiled_type_id,$efiling_type); //function written for Auto diarization on 29072023
                // echo $diaryGenerationStatus;
            }
        }
        else{
            $diaryGenerationStatus = "Registration_id and Efiling_type are Empty !!!";
        }
        return $diaryGenerationStatus;
    }
    private function getAllFilingDetailsByRegistrationId($efiling_no=NULL,$registration_id=NULL,$ref_m_efiled_type_id=NULL,$file_type=NULL)
    {
        $file_type = !empty($file_type) ?$file_type: NULL;
        $registration_id = !empty($registration_id) ?$registration_id:NULL;
        $finalArr = array();
        $finalArr['file_type'] = $file_type;
        if (isset($file_type) && !empty($file_type) && isset($registration_id) && !empty($registration_id)) {
            //get icmis usercode for diary_user_id
            $paramArray = array();
            $paramArray['registration_id'] = $registration_id;
            /*$icmisUserData = $this->Common_model->getIcmisUserCodeByRegistrationId($paramArray);
            $userIcmisCode = !empty($icmisUserData[0]->icmis_usercode) ? (int)$icmisUserData[0]->icmis_usercode : 0;    */
            $userIcmisCode=$this->efiling_webservices->getDiaryUserCodeFromICMIS();
            if(empty($userIcmisCode))
            {
                $userIcmisCode=ADMIN_AUTO_DIARY_ICMIS_USER_CODE;
            }else{
                $userIcmisCode=$userIcmisCode['icmis_diary_user_usercode'];
            }
            switch ($file_type) {
                case 'new_case' :
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
                                }
                                else{
                                    $tmpArr['pet_adv_id'] = PETITIONER_IN_PERSON;
                                    $tmpArr['adv_pp'] = 'P';
                                }
                            }
                        }
                    }
                    $courtData = $this->Common_model->getEarlierCourtDetailByRegistrationId($registration_id);
                    $court_type = !empty($case_details[0]['court_type']) ? (int)$case_details[0]['court_type'] : NULL;
                    if(isset($court_type) && !empty($court_type)){
                        switch($court_type){
                            case 1:
                                $tmpArr ['court_type'] = !empty($courtData[0]['court_type']) ? $courtData[0]['court_type'] : NULL;
                                $tmpArr ['cmis_state_id'] = !empty($courtData[0]['cmis_state_id']) ? $courtData[0]['cmis_state_id'] : NULL;
                                $tmpArr ['ref_agency_code_id'] = !empty($courtData[0]['ref_agency_code_id']) ? $courtData[0]['ref_agency_code_id'] : NULL;
                                break;
                            case 3:
                                $tmpArr ['court_type'] = !empty($courtData[0]['court_type']) ? $courtData[0]['court_type'] : NULL;
                                $tmpArr ['cmis_state_id'] = !empty($courtData[0]['cmis_state_id']) ? $courtData[0]['cmis_state_id'] : NULL;
                                $tmpArr ['ref_agency_code_id'] = !empty($courtData[0]['ref_agency_code_id']) ? $courtData[0]['ref_agency_code_id'] : NULL;
                                break;
                            case 4:
                                $tmpArr ['court_type'] = !empty($courtData[0]['court_type']) ? $courtData[0]['court_type'] : NULL;
                                break;
                            case 5:
                                $tmpArr ['court_type'] = !empty($courtData[0]['court_type']) ? $courtData[0]['court_type'] : NULL;
                                $tmpArr ['cmis_state_id'] = !empty($courtData[0]['cmis_state_id']) ? $courtData[0]['cmis_state_id'] : NULL;
                                $tmpArr ['ref_agency_code_id'] = !empty($courtData[0]['ref_agency_code_id']) ? $courtData[0]['ref_agency_code_id'] : NULL;
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
                            if(isset($v['registration_id']) && !empty($v['registration_id'])){
                                $params['table_name'] = 'efil.tbl_fir_details';
                                $params['whereFieldName'] = 'registration_id';
                                $params['whereFieldValue'] = (int)$v['registration_id'];
                                $firData = $this->Common_model->getData($params);
                                if(!isset($v['case_type_id']) && empty($v['case_type_id'])){
                                    $v['case_type_id'] = 0;
                                }
                                if(isset($firData) && !empty($firData) && count($firData)>0){
                                    $v['fir_details']['is_fir_details_exists'] =  true;
                                    $v['fir_details']['firData'] =  array($firData[0]);
                                }
                                else{
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
                case 'caveat' :
                    $registration_id = !empty($registration_id) ?$registration_id:NULL;
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
                                if ((isset($adv_sci_bar_id) && !empty($adv_sci_bar_id)) || $usertype==USER_IN_PERSON) {
                                    if($usertype==USER_IN_PERSON){
                                        $adv_sci_bar_id=616;
                                    }
                                    $getBarData = $this->Common_model->getBarDetailsById($adv_sci_bar_id);
                                    $bar_id = !empty($getBarData[0]['bar_id']) ? $getBarData[0]['bar_id'] : NULL;
                                    $pp = !empty($getBarData[0]['pp']) ? 'P' : 'A';
                                    $tmpArr['pet_adv_id'] = !empty($getBarData[0]['pp']) ? CAVEATOR_IN_PERSON :   $bar_id;
                                    $tmpArr['adv_pp'] = $pp;
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
                            if(isset($v['registration_id']) && !empty($v['registration_id'])){
                                $params['table_name'] = 'efil.tbl_fir_details';
                                $params['whereFieldName'] = 'registration_id';
                                $params['whereFieldValue'] = (int)$v['registration_id'];
                                $firData = $this->Common_model->getData($params);
                                if(!isset($v['case_type_id']) && empty($v['case_type_id'])){
                                    $v['case_type_id'] = 0;
                                }
                                if(isset($firData) && !empty($firData) && count($firData)>0){
                                    $v['fir_details']['is_fir_details_exists'] =  true;
                                    $v['fir_details']['firData'] =  array($firData[0]);
                                }
                                else{
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
        $finalArr= json_encode($finalArr);
        $response = '';
        
        if($file_type=='new_case'){
            $key=config('Encryption')->key;
            $encrypted_string = $this->encrypter->encrypt($finalArr, $key);
            $response = $this->efiling_webservices->generateCaseDiary($encrypted_string);
        }
        else if($file_type == 'caveat'){
            $key=config('Encryption')->key;
            $encrypted_string = $this->encrypter->encrypt($finalArr, $key);
            $response = $this->efiling_webservices->generateCaseDiary($encrypted_string);
        }
        $diaryStatus = '';
        $insertData=[];
        $response=json_decode($response,true);
        if(isset($response['status']) && strtoupper(trim($response['status']))=='SUCCESS'){

            $diaryStatus = 'new_diary';
            $filteredData = array();
            $typeGeneration ='';
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
            if($file_type=='new_case'){
                send_whatsapp_message($registration_id,$efiling_no," - filed with Diary Number $diaryNo");
            }
            if($file_type=='caveat'){
                send_whatsapp_message($registration_id,$efiling_no," - filed with Caveat Number $diaryNo");
            }
            $filteredData = ['case_detail'=>'1'];

            $typeGeneration ='diary no.';
            $insertData['diaryNo'] = $diaryNo;
            $insertData['alloted_to'] = $alloted_to;
            $insertData['insertedDocNums'] = $insertedDocNums;
            $insertData['status'] = $status;
            $insertData['selectedcheckBox'] = $filteredData;
            $insertData['diaryStatus'] = $diaryStatus;
        }
        else  if(isset($response['status']) && strtoupper(trim($response['status'])) == 'ERROR_ALREADY_IN_ICMIS') {
            $diaryStatus = 'exist_diary';
            $already_generated_diary_no=$response['efiled_cases']['diary_no'];
            $already_generated_created_at=$response['efiled_cases']['created_at'];
            if(!empty($already_generated_diary_no) && $already_generated_diary_no>0){
                $this->updateDiaryInAlreadyGenerated($registration_id,$already_generated_diary_no,$already_generated_created_at);
            }
        }
        else  if(isset($response['status']) && strtoupper(trim($response['status'])) == 'ERROR_MAIN') {

        }
        else if(isset($response['status']) && strtoupper(trim($response['status'])) == 'ERROR_CAVEAT'){

        }
        array_push($response, array("field_name" => "diaryStatus", "field_value" => $diaryStatus));
        $updateDiaryDetailsStatus=$this->updateDiaryDetails($insertData, $efiling_no, $registration_id, $ref_m_efiled_type_id, $file_type);
        return $updateDiaryDetailsStatus;


    }
    private function updateDiaryInAlreadyGenerated($registration_id,$already_generated_diary_no,$already_generated_created_at){
        //Update details in efil.tbl_case_details
        if(!empty($already_generated_diary_no) && isset($already_generated_diary_no)) {
            $diary_num = substr($already_generated_diary_no, 0, -4);
            $diary_year = substr($already_generated_diary_no, -4);
        }
        $arr['table_name'] = 'efil.tbl_case_details';
        $arr['whereFieldName'] = 'registration_id';
        $arr['whereFieldValue'] = (int)$registration_id;
        $upArr = array();
        $upArr['sc_diary_year'] = $diary_year;
        $upArr['sc_diary_date'] = date('Y-m-d H:i:s');
        $upArr['sc_diary_num'] = $diary_num;
        $arr['updateArr'] = $upArr;
        $res1 = $this->Common_model->updateTableData($arr);
        $login_id = !empty($_SESSION['login']['id']) ? $_SESSION['login']['id'] : ADMIN_AUTO_DIARY_USER_ID_FOR_EFM;
        //Update stage to 9
        $res2 =  $this->Default_model->update_next_stage($registration_id, I_B_Approval_Pending_Admin_Stage, $already_generated_created_at, $login_id);
    }
    private function updateDiaryDetails($postData, $efiling_no = NULL, $registration_id = NULL, $ref_m_efiled_type_id = NULL, $efiling_type = NULL)
    {

        $output = array();
        //$postData = json_decode(file_get_contents('php://input'), true);
        $registration_id = !empty($registration_id) ?$registration_id: NULL;
        $efiling_type = !empty($efiling_type) ?$efiling_type: NULL;
        $ref_m_efiled_type_id = !empty($ref_m_efiled_type_id) ?$ref_m_efiled_type_id: NULL;
        $efiling_no = !empty($efiling_no) ?$efiling_no: NULL;
        $diaryStatus = !empty($postData['diaryStatus']) ? $postData['diaryStatus'] : NULL;
        if (isset($registration_id) && !empty($registration_id)) {
            
            $diary_num = NULL;
            $diary_year = NULL;
            $arr = array();
            if(!empty($postData['diaryNo']) && isset($postData['diaryNo'])) {
                $diary_num = substr($postData['diaryNo'], 0, -4);
                $diary_year = substr($postData['diaryNo'], -4);
            }

            // echo $diary_num;
            // pr($diary_year);

            //alloted_to
            $alloted_to_name = NULL;
            if(!empty($postData['alloted_to']) && isset($postData['alloted_to'])) {
                $alloted = explode('~',$postData['alloted_to']);
                $alloted_to_emp_code = !empty($alloted[0]) ? $alloted[0] : NULL;
                $alloted_name = !empty($alloted[1]) ? $alloted[1] : NULL;
                if(isset($alloted_to_emp_code) && !empty($alloted_to_emp_code) && isset($alloted_name) && !empty($alloted_name)){
                    $alloted_to_name = strtoupper($alloted_name).' ('.$alloted_to_emp_code.' )';
                }
                else if(isset($alloted_name) && !empty($alloted_name)){
                    $alloted_to_name = strtoupper($alloted_name);
                }
            }
            $diaryNo = !empty($postData['diaryNo']) ? $postData['diaryNo'] : NULL;
            $status = !empty($postData['status']) ? $postData['status'] : NULL;
            $records_inserted = !empty($postData['records_inserted']) ? $postData['records_inserted'] : NULL;
            $insertedDocNums = !empty($postData['insertedDocNums']) ? $postData['insertedDocNums'] : NULL;
            if(isset($postData['selectedcheckBox']) && !empty($postData['selectedcheckBox'])){
                $checkBoxArr = array();
                $checkBoxArr['recordinserted'] = json_encode($postData['selectedcheckBox']);
                $checkBoxArr['diary_no'] = $diaryNo;
                $checkBoxArr['status'] = $status;
                $checkBoxArr['createdAt'] = date('Y-m-d H:i:s');
                $checkBoxArr['updatedAt'] = NULL;
                $checkBoxArr['createdby'] =ADMIN_AUTO_DIARY_USER_ID_FOR_EFM;
                $checkBoxArr['updatedby'] = NULL;
                $checkBoxArr['createdbyip'] = getClientIP();
                $checkBoxArr['registration_id'] = $registration_id;
                $table = "efil.tbl_diary_generation_checkbox";
                $this->Citation_model->insertData($table,$checkBoxArr);
            }
            if(isset($records_inserted) && !empty($records_inserted)){
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
                $inArr['createdby'] = ADMIN_AUTO_DIARY_USER_ID_FOR_EFM;
                $inArr['updatedby'] = NULL;
                $inArr['createdbyip'] = getClientIP();
                $inArr['registration_id'] = $registration_id;
                $table = 'efil.tbl_diary_generation_response';
                $this->Citation_model->insertData($table,$inArr);
            }
            switch ($efiling_type){
                case 'new_case' :
                    //update icmis_docnum in tbl_efiled_docs
                    // $insertedDocNums = array(0=>array('doc_id'=>3588,'docnum'=>2121));
                    if(isset($insertedDocNums) && !empty($insertedDocNums)){
                        foreach ($insertedDocNums as $k=>$v){
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
                        $docUpArr['icmis_diary_no'] = $diary_num.$diary_year;
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
                                $output = $this->getCisStatusForNewCase($registration_id, $efiling_no, Transfer_to_IB_Stage,$diaryStatus,$alloted_to_name,$ref_m_efiled_type_id,$efiling_type);
                                /*$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please enter every party details before moving to further tabs.</div>');
                                redirect('newcase/extra_party');*/
                                //  $output['success'] = 'success';
                                //  $output['message'] = 'Diary No.' .$diary_num.'/'.$diary_year.' has been created successfully!';
                                    

                            }
                            else {
                                $output['success'] = 'error';
                            }
                        }
                        else {
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
                        $docUpArr['icmis_diary_no'] = $diary_num.$diary_year;
                        $docUpArr['icmis_docyear'] = $diary_year;
                        $docUpArr['updated_on'] = date('Y-m-d H:i:s');
                        $doc['updateArr'] = $docUpArr;
                        $result = $this->Common_model->updateTableData($doc);
                        if (isset($result) && !empty($result)) {
                            if ($ref_m_efiled_type_id == E_FILING_TYPE_CAVEAT) {
                                $output = $this->getCisStatusForNewCase($registration_id, $efiling_no, Transfer_to_IB_Stage,$diaryStatus,'',$ref_m_efiled_type_id,$efiling_type);
                            }
                            else {
                                $output['success'] = 'error4';
                            }
                        }
                        else {
                            $output['success'] = 'error5';
                        }
                    } else {
                        $output['success'] = 'error6';
                    }
                    break;
                default :
                    $output;
            }
        }
        return $output;
    }
    private function getCisStatusForNewCase($registration_id, $efiling_num, $curr_stage,$diaryStatus = NULL,$alloted_to_name = NULL,$ref_m_efiled_type_id=NULL,$efiling_type=NULL)
    {

        $output = array();
        if (isset($registration_id) && !empty($registration_id) && isset($efiling_num)
            && !empty($efiling_num) && isset($curr_stage) && !empty($curr_stage)) {
            $efiling_type = !empty($efiling_type) ?$efiling_type: NULL;
            $ref_m_efiled_type_id = !empty($ref_m_efiled_type_id) ? $ref_m_efiled_type_id: NULL;
            $curr_dt = date('Y-m-d H:i:s');
            switch ($efiling_type){
                case 'new_case':
                    $params = array();
                    $params['table_name'] = 'efil.tbl_case_details';
                    $params['whereFieldName'] = 'registration_id';
                    $params['whereFieldValue'] = $registration_id;
                    $caseDetailsData = $this->Common_model->getData($params);
                    // pr($caseDetailsData);

                    if (isset($caseDetailsData[0]->sc_diary_num) && !empty($caseDetailsData[0]->sc_diary_num)
                        && isset($caseDetailsData[0]->sc_diary_year) && !empty($caseDetailsData[0]->sc_diary_year)){
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
                        $case_details['updated_by'] = ADMIN_AUTO_DIARY_USER_ID_FOR_EFM;
                        $case_details['updated_on'] = $curr_dt;
                        $case_details['updated_by_ip'] = getClientIP();
                        $stage_update_timestamp = $this->Get_CIS_Status_model->getStageUpdateTmestampCaseCaveat($registration_id, $curr_stage,$diaryStatus);
                        if(isset($stage_update_timestamp) && !empty($stage_update_timestamp)){
                            $next_stage = I_B_Approval_Pending_Admin_Stage;
                            $update_status = $this->Get_CIS_Status_model->update_icmis_case_status($registration_id, $next_stage, $curr_dt, $case_details, '', '', '',$efiling_type);
                            if(isset($update_status) && !empty($update_status)){
                                if($next_stage == I_B_Approval_Pending_Admin_Stage) {
                                    $cuase_title = '';
                                    if(!empty($caseDetailsData[0]->cause_title) &&  !empty($caseDetailsData[0]->cause_title)){
                                        $cuase_title = $caseDetailsData[0]->cause_title;
                                    }
                                    $createdby = !empty($caseDetailsData[0]->created_by) ? (int)$caseDetailsData[0]->created_by : NULL;
                                    if(isset($createdby) && !empty($createdby)){
                                        $params = array();
                                        $params['table_name'] ='efil.tbl_users';
                                        $params['whereFieldName'] ='id';
                                        $params['whereFieldValue'] = (int)$createdby;
                                        $params['is_active'] ='1';
                                        $userData = $this->Common_model->getData($params);
                                        $mobile = NULL;
                                        $email= NULL;
                                        $efiling_no = '';
                                        $first_name = '';
                                        $last_name ='';
                                        $userName = '';
                                        if(isset($userData) && !empty($userData)){
                                            $mobile = (!empty($userData[0]->moblie_number) && isset($userData[0]->moblie_number)) ? $userData[0]->moblie_number : NULL;
                                            $email= (!empty($userData[0]->emailid) && isset($userData[0]->emailid)) ? $userData[0]->emailid : NULL;
                                            $first_name= (!empty($userData[0]->first_name) && isset($userData[0]->first_name)) ? $userData[0]->first_name : NULL;
                                            $last_name= (!empty($userData[0]->last_name) && isset($userData[0]->last_name)) ? $userData[0]->last_name : NULL;
                                        }
                                    }
                                    if(isset($first_name) && !empty($first_name) && isset($last_name) && !empty($last_name)){
                                        $userName = $first_name.' '.$last_name;
                                    }
                                    else if(isset($first_name) && !empty($first_name)){
                                        $userName = $first_name;
                                    }
                                    $diary_No= $diary_no.' / '.$diary_year;
                                    $current_date = date('d-m-Y H:i:s');
                                    $arr = array();
                                    $arr['table_name'] ='efil.tbl_efiling_nums';
                                    $arr['whereFieldName'] ='registration_id';
                                    $arr['whereFieldValue'] = (int)$registration_id;
                                    $arr['is_active'] ='1';
                                    $efilingNoData = $this->Common_model->getData($arr);
                                    if(isset($efilingNoData[0]->efiling_no) && !empty($efilingNoData[0]->efiling_no)){
                                        $efiling_no = $efilingNoData[0]->efiling_no;
                                    }
                                    $smsRes = '';
                                    if(isset($mobile) && !empty($mobile)){
                                        //$message = 'Your case efiling no. '.$efiling_no.' , '.$cuase_title.' is filed with Diary No. '.$diary_No.' on '.$current_date.'';
                                        $message = 'Your case '.$efiling_no.' is filed with Diary No. '.$diary_No.' on '.$current_date.'. - Supreme Court of India';
                                        //$smsRes = send_mobile_sms($mobile,$message,SCISMS_Case_Filed_Diary_No);
                                        send_whatsapp_message($registration_id,$efiling_no," - filed with Diary Number $diary_No");
                                    }
                                    if(isset($email) && !empty($email)){
                                        $message = 'Dear '.$userName.', your case efiling no. '.$efiling_no.' , '.$cuase_title.' is filed with Diary No. '.$diary_No.' on '.$current_date.'';
                                        $to_email = $email;
                                        $subject = 'Diary No. Generation.';
                                        send_mail_msg($to_email, $subject, $message, $to_user_name="");
                                    }
                                    $output['success'] = 'success';
                                    if(isset($alloted_to_name) && !empty($alloted_to_name)){
                                        $output['message'] = 'Diary No.' .$diary_no.'/'.$diary_year.' has been created successfully! and allocated to  '.$alloted_to_name;
                                    }
                                    else{
                                        $output['message'] = 'Diary No.' .$diary_no.'/'.$diary_year.' has been created successfully!';
                                    }
                                } elseif ($next_stage == E_Filed_Stage) {
                                    $output['success'] = 'success';
                                    $output['message']='updated to Efiled Stage.';
                                }
                            }
                            else{
                                $output['success'] = 'errorsdfdsf';
                                $output['message']='';
                            }
                        }
                        else{
                            $output['success'] = 'error1 1';
                            $output['message']='';
                        }
                    }
                    else{
                        $output['success'] = 'error2 2';
                        $output['message']='';
                    }
                    break;
                case 'caveat':
                    $params = array();
                    $params['table_name'] = 'public.tbl_efiling_caveat';
                    $params['whereFieldName'] = 'ref_m_efiling_nums_registration_id';
                    $params['whereFieldValue'] = $registration_id;
                    $caveatDetailsData = $this->Common_model->getData($params);
                    if (isset($caveatDetailsData[0]->caveat_num) && !empty($caveatDetailsData[0]->caveat_num)
                        && isset($caveatDetailsData[0]->caveat_year) && !empty($caveatDetailsData[0]->caveat_year))
                    {
                        $diary_no = (int)$caveatDetailsData[0]->caveat_num;
                        $diary_year = (int)$caveatDetailsData[0]->caveat_year;
                        $sc_diary_date = !empty($caveatDetailsData[0]->caveat_num_date) ? $caveatDetailsData[0]->caveat_num_date : NULL;
                        $caveat_details = array();
                        $caveat_details['caveat_num'] = $diary_no;
                        $caveat_details['caveat_year'] = $diary_year;
                        $caveat_details['caveat_num_date'] = $sc_diary_date;
                        $caveat_details['updated_by'] = ADMIN_AUTO_DIARY_USER_ID_FOR_EFM;
                        $caveat_details['updated_at'] = $curr_dt;
                        $caveat_details['updated_by_ip'] = getClientIP();
                        
                        $stage_update_timestamp = $this->Get_CIS_Status_model->getStageUpdateTmestampCaseCaveat($registration_id, $curr_stage,$diaryStatus);
                        if(isset($stage_update_timestamp) && !empty($stage_update_timestamp)){
                            //$next_stage = I_B_Approval_Pending_Admin_Stage;
                            //Changed on 02-06-2021 for marking caveat as e-filed once caveat number is generated as per current practice.
                            $next_stage = E_Filed_Stage;
                            $update_status = $this->Get_CIS_Status_model->update_icmis_case_status($registration_id, $next_stage, $curr_dt, $caveat_details, '', '', '',$efiling_type);
                            if(isset($update_status) && !empty($update_status)) {
                                if($next_stage == E_Filed_Stage) {
                                    $cuase_title = '';
                                    if(!empty($caveatDetailsData[0]->pet_name) &&  !empty($caveatDetailsData[0]->res_name)){
                                        $cuase_title = $caveatDetailsData[0]->pet_name.' Vs '.$caveatDetailsData[0]->res_name;
                                    }
                                    else  if(!empty($caveatDetailsData[0]->pet_name)){
                                        $cuase_title = $caveatDetailsData[0]->pet_name;
                                    }
                                    $createdby = !empty($caveatDetailsData[0]->createdby) ? (int)$caveatDetailsData[0]->createdby : NULL;
                                    if(isset($createdby) && !empty($createdby)){
                                        $params = array();
                                        $params['table_name'] ='efil.tbl_users';
                                        $params['whereFieldName'] ='id';
                                        $params['whereFieldValue'] = (int)$createdby;
                                        $params['is_active'] ='1';
                                        $userData = $this->Common_model->getData($params);
                                        $mobile = NULL;
                                        $email= NULL;
                                        $efiling_no = '';
                                        $first_name = '';
                                        $last_name ='';
                                        $userName = '';
                                        if(isset($userData) && !empty($userData)){
                                            if(isset($userData) && !empty($userData)){
                                                $mobile = (!empty($userData[0]->moblie_number) && isset($userData[0]->moblie_number)) ? $userData[0]->moblie_number : NULL;
                                                $email= (!empty($userData[0]->emailid) && isset($userData[0]->emailid)) ? $userData[0]->emailid : NULL;
                                                $first_name= (!empty($userData[0]->first_name) && isset($userData[0]->first_name)) ? $userData[0]->first_name : NULL;
                                                $last_name= (!empty($userData[0]->last_name) && isset($userData[0]->last_name)) ? $userData[0]->last_name : NULL;
                                            }
                                        }
                                    }
                                    if(isset($first_name) && !empty($first_name) && isset($last_name) && !empty($last_name)){
                                        $userName = $first_name.' '.$last_name;
                                    }
                                    else if(isset($first_name) && !empty($first_name)){
                                        $userName = $first_name;
                                    }
                                    $caveatNo= $diary_no.' / '.$diary_year;
                                    $current_date = date('d-m-Y H:i:s');
                                    $message ='';
                                    $arr = array();
                                    $arr['table_name'] ='efil.tbl_efiling_nums';
                                    $arr['whereFieldName'] ='registration_id';
                                    $arr['whereFieldValue'] = (int)$registration_id;
                                    $arr['is_active'] ='1';
                                    $efilingNoData = $this->Common_model->getData($arr);
                                    if(isset($efilingNoData[0]->efiling_no) && !empty($efilingNoData[0]->efiling_no)){
                                        $efiling_no = $efilingNoData[0]->efiling_no;
                                    }
                                    if(isset($mobile) && !empty($mobile)){
                                        // $message = 'Your caveat efiling no. '.$efiling_no.' , '.$cuase_title.' is filed with Caveat No. '.$caveatNo.' on '.$current_date.'';
                                        $message = 'Your caveat efiling no. '.$efiling_no.' , '.$cuase_title.' is filed with Caveat No. '.$caveatNo.' on '.$current_date.' - Supreme Court of India';
                                        $smsRes = send_mobile_sms($mobile,$message,SCISMS_EFILING_CAVEAT);
                                        send_whatsapp_message($registration_id,$efiling_no,"- filed with Caveat Number $caveatNo");
                                    }
                                    if(isset($email) && !empty($email)){
                                        $message = 'Dear '.$userName.' ,your caveat efiling no. '.$efiling_no.' , '.$cuase_title.' is filed with Caveat No. '.$caveatNo.' on '.$current_date.'';
                                        $to_email = $email;
                                        $subject = 'Caveat No. Generation.';
                                        send_mail_msg($to_email, $subject, $message, $to_user_name="");

                                    }
                                    $output['success'] = 'success';
                                    $output['message']='Caveat No. '.$diary_no.'/'.$diary_year.' has been created successfully!.';
                                } /*elseif ($next_stage == E_Filed_Stage) {
                                        $output['success'] = 'success';
                                        $output['message']='updated to Efiled Stage.';
                                    }*/
                            }
                            else{
                                $output['success'] = 'error';
                                $output['message']='';
                            }
                        }
                        else{
                            $output['success'] = 'error';
                            $output['message']='';
                        }
                    }
                    else{
                        $output['success'] = 'error';
                        $output['message']='';
                    }
                    break;
                default :
                    $output['success'] = 'error';
                    $output['message']='';
                    break;
            }
        }
        else {
            $output['success'] = 'error 3';
            $output['message']='';
        }
        return $output;
    }

    public function autoDiaryCRON()
    {
        // TODO code here for getting the list of cases which are in initial approved stage
        $stages_array = array(Transfer_to_IB_Stage); // Transfer_to_IB_Stage = 8
        $casesListWithInitiallyApprovedStage = $this->Common_model->get_submitted_cases_for_cron(Transfer_to_IB_Stage, E_FILING_TYPE_NEW_CASE, USER_ADVOCATE); // get all cases list which are at Transfer_to_IB_Stage = 8
        if(is_array($casesListWithInitiallyApprovedStage) && count($casesListWithInitiallyApprovedStage) > 0){
            foreach ($casesListWithInitiallyApprovedStage as $case) {
                $response = $this->index($case['registration_id'], 'new_case', '3d07510c2c3348f58a04f63bc9a63296');
                $response = (array)json_decode($response, true);
                if (isset($response['success']) && $response['success'] == 'success') {
                    var_dump($case) . '<br>';
                    echo  $response['message'] . '<br><hr>';
                } else {
                    var_dump($case) . '<br>';;
                    echo  "Your E-filing has been submitted successfully for Diary Number, Kindly check after 30 minutes. <br><hr>";
                }
            }
        }else{
            echo  "All cases are updated. <br><hr>";
        }
    }

    // Auto Transfer to scrutiny - start
    public function updateRefiledCase($registration_id=null,$diary_no=null,$diary_year=null,$efiling_no=null){
       if(!empty($_POST)){
            $postData=$_POST;
        }
        $output = array();
        $registration_id = !empty($postData['registration_id']) ? (int)$postData['registration_id'] : NULL;
        $diary_no = !empty($postData['diary_no']) ? (int)$postData['diary_no'] : NULL;
        $diary_year = !empty($postData['diary_year']) ? (int)$postData['diary_year'] : NULL;
        $efiling_no = !empty($postData['efiling_no']) ? $postData['efiling_no'] : NULL;
        if(isset($registration_id) && !empty($registration_id) && !empty($diary_no) && !empty($diary_year) && !empty($efiling_no)){
            //go to scrutiny
            $diary_no = !empty($diary_no) ? (int)$diary_no : NULL;
            if(isset($diary_no) && !empty($diary_no)){

                $diary_id = $diary_no . $diary_year;
                #from AOR to fdr for scrutiny
                $this->efiling_webservices->updateFilTrapAORtoFDR($efiling_no);
                // $efiling_no = $_SESSION['efiling_details']['efiling_no'];
                $result =  $this->setReturnedByAdvocate($diary_id, $efiling_no,$registration_id);
                if(isset($result['doc_details']) && !empty($result['doc_details'])){
                    foreach ($result['doc_details'] as $k=>$v){
                        $tmpArr = array();
                        $tmpArr['icmis_diary_no'] = $diary_id;
                        $tmpArr['icmis_doccode'] = 8;
                        $tmpArr['icmis_docnum'] = (int)$v;
                        $tmpArr['icmis_docyear'] = $diary_year;
                        $tmpArr['icmis_iastat'] = 'P';
                        $updateArr = array();
                        $updateArr['table_name'] = "efil.tbl_efiled_docs";
                        $updateArr['whereFieldName'] = 'doc_id';
                        $updateArr['whereFieldValue'] = (int)$k;
                        $updateArr['updateArr'] = $tmpArr;
                        $this->Common_model->updateTableData($updateArr);
                    }
                }
                $params =array();
                $params['registration_id'] = $registration_id;
                $totalFeeData =  $this->Common_model->getTotalFeeByRegistrationId($params);
                $totalFee =0;
                if(isset($totalFeeData[0]->total) && !empty($totalFeeData[0]->total)){
                    $totalFee = (int)$totalFeeData[0]->total;
                }
                $params = array();
                $params['totalFee'] = $totalFee;
                $params['diaryNo'] = $diary_id;
                if($totalFee != 0){
                    $res =  $this->efiling_webservices->updateFeeByRegistrationId($params);
                }
                else{
                    $res=array();
                }

                if((isset($res['status']) && !empty($res['status'])) || $totalFee == 0){
                    $next_stage = I_B_Approval_Pending_Admin_Stage;
                    $_SESSION['efiling_details']['stage_id'] = I_B_Approval_Pending_Admin_Stage;
                    $this->Common_model->updateCaseStatus($registration_id, $next_stage);
                    $output['status'] = "success";
                    log_message('debug', "File has been transfer to scrutiny.");
                    $output['message'] = "File has been transfer to scrutiny.";
                }
                else{
                    $output['status'] = "error";
                    log_message('debug', "Something went wrong while updation of refiled case,Please try again later.");
                    $output['message'] = "Something went wrong,Please try again later.";
                }

            }
        }
        return json_encode($output);
        // exit(0);
    }
    
    
    public function updateRefiledCaseRefile($registration_id,$diary_no,$diary_year,$efiling_no) {
        $output = array();
        if(isset($registration_id) && !empty($registration_id) && !empty($diary_no) && !empty($diary_year) && !empty($efiling_no)){
            //go to scrutiny
            $diary_no = !empty($diary_no) ? (int)$diary_no : NULL;
            if(isset($diary_no) && !empty($diary_no)){
                $diary_year = substr($diary_year, -4);
                $diary_id = $diary_no . $diary_year;
                #from AOR to fdr for scrutiny
                $this->efiling_webservices->updateFilTrapAORtoFDR($efiling_no);
                // $efiling_no = $_SESSION['efiling_details']['efiling_no'];
                $result =  $this->setReturnedByAdvocate($diary_id, $efiling_no,$registration_id);
                if(isset($result['doc_details']) && !empty($result['doc_details'])){
                    foreach ($result['doc_details'] as $k=>$v){
                        $tmpArr = array();
                        $tmpArr['icmis_diary_no'] = $diary_id;
                        $tmpArr['icmis_doccode'] = 8;
                        $tmpArr['icmis_docnum'] = (int)$v;
                        $tmpArr['icmis_docyear'] = $diary_year;
                        $tmpArr['icmis_iastat'] = 'P';
                        $updateArr = array();
                        $updateArr['table_name'] = "efil.tbl_efiled_docs";
                        $updateArr['whereFieldName'] = 'doc_id';
                        $updateArr['whereFieldValue'] = (int)$k;
                        $updateArr['updateArr'] = $tmpArr;
                        $this->Common_model->updateTableData($updateArr);
                    }
                }
                $params = array();
                $params['registration_id'] = $registration_id;
                $totalFeeData =  $this->Common_model->getTotalFeeByRegistrationId($params);
                $totalFee = 0;
                if(isset($totalFeeData[0]->total) && !empty($totalFeeData[0]->total)){
                    $totalFee = (int)$totalFeeData[0]->total;
                }
                $params = array();
                $params['totalFee'] = $totalFee;
                $params['diaryNo'] = $diary_id;
                if($totalFee != 0) {
                    $res =  $this->efiling_webservices->updateFeeByRegistrationId($params);
                } else {
                    $res=array();
                }
                if((isset($res['status']) && !empty($res['status'])) || $totalFee == 0){
                    $next_stage = I_B_Approval_Pending_Admin_Stage;
                    $_SESSION['efiling_details']['stage_id'] = I_B_Approval_Pending_Admin_Stage;
                    $this->Common_model->updateCaseStatus($registration_id, $next_stage);
                    $output['status'] = "success";
                    log_message('debug', "File has been transfer to scrutiny.");
                    $output['message'] = "File has been transfer to scrutiny.";
                } else {
                    $output['status'] = "error";
                    log_message('debug', "Something went wrong while updation of refiled case,Please try again later.");
                    $output['message'] = "Something went wrong,Please try again later.";
                }
            }
        }
        return json_encode($output);
        // exit(0);
    }
    private function setReturnedByAdvocate($diary_id, $efiling_no,$registration_id=null)
    {
        $registration_id = !empty($registration_id) ? $registration_id : NULL;
        $efiled_docs_list = $this->View_model->get_index_items_list($registration_id,1);
        $docTempArr = array();
        $output= false;
        if (isset($efiled_docs_list) && !empty($efiled_docs_list)) {
            foreach ($efiled_docs_list as $k => $v) {
                $docTempArr[] = (object)$v;
            }
        }
        $documents = !empty($efiled_docs_list[0]) ? $docTempArr : array(new stdClass());
        if(count($documents)>0){
            $created_by = !empty($documents[0]->uploaded_by) ? $documents[0]->uploaded_by : NULL;
        }
        $pet_adv_id=0;
        if (isset($created_by) && !empty($created_by)) {
            $userData = $this->Common_model->getUserDetailsById($created_by);
            if (isset($userData) && !empty($userData)) {
                $adv_sci_bar_id = !empty($userData[0]['adv_sci_bar_id']) ? (int)$userData[0]['adv_sci_bar_id'] : NULL;
                if (isset($adv_sci_bar_id) && !empty($adv_sci_bar_id)) {
                    $getBarData = $this->Common_model->getBarDetailsById($adv_sci_bar_id);
                    $bar_id = !empty($getBarData[0]['bar_id']) ? $getBarData[0]['bar_id'] : NULL;
                    // $pet_adv_id = !empty($getBarData[0]['pp']) ? PETITIONER_IN_PERSON : $bar_id;
                    // This portion of code is commented on 13052025, as PP field has no significant for AOR or PIP in the BAR table as per ICMIS team
                    $pet_adv_id = !empty($bar_id) ? $bar_id : PETITIONER_IN_PERSON;
                }
                else{
                    $pet_adv_id = PETITIONER_IN_PERSON;
                }
            }
        }

        $assoc_arr = array('diary_id' => $diary_id, 'efiling_no' => $efiling_no,'pet_adv_id'=>$pet_adv_id,'documents' => $documents);
        $assoc_json = json_encode($assoc_arr);
        $key = config('Encryption')->key;
        $encrypted_string = $this->encrypter->encrypt($assoc_json, $key);
        $this->efiling_webservices->setReturnedByAdvocate($encrypted_string);
        $output = $this->efiling_webservices->saveRefiledIAData($encrypted_string);
        return $output;
    }
    // Auto Transfer to scrutiny - end

    // Refile - Old e-filing case- start
    public function updateOldEfilingRefiledCase($registration_id=null,$diary_no=null,$diary_year=null,$efiling_no=null,$refiled_by_aor_code=null){
        if(!empty($_POST)){
            $postData=$_POST;
        }
        $output = array();
        $registration_id = !empty($postData['registration_id']) ? (int)$postData['registration_id'] : NULL;
        $diary_no = !empty($postData['diary_no']) ? (int)$postData['diary_no'] : NULL;
        $diary_year = !empty($postData['diary_year']) ? (int)$postData['diary_year'] : NULL;
        $efiling_no = !empty($postData['efiling_no']) ? $postData['efiling_no'] :  $_SESSION['efiling_details']['efiling_no'];
        $refiled_by_aor_code = !empty($postData['refiled_by_aor_code']) ? $postData['refiled_by_aor_code'] :  $_SESSION['login']['aor_code'];

        /* get scrutiny user from ICMIS */
        $assoc_arr = array();
        $assoc_arr['diaryNo'] = $diary_no.$diary_year;
        $assoc_json = json_encode($assoc_arr);
        $key = config('Encryption')->key;
        $encrypted_string = $this->encrypter->encrypt($assoc_json, $key);
        $scrutinyUser = $this->efiling_webservices->getScrutinyUserByDiaryNo($encrypted_string);
        $scrutiny_usercode = !empty($scrutinyUser['user_details'][0]['usercode']) ? $scrutinyUser['user_details'][0]['usercode'] : NULL;

        if(isset($registration_id) && !empty($registration_id) && !empty($diary_no) && !empty($diary_year) && !empty($efiling_no)){
            //go to scrutiny
            $diary_no = !empty($diary_no) ? (int)$diary_no : NULL;
            $advocate_id = $this->session->userdata['login']['adv_sci_bar_id'];
            if(isset($diary_no) && !empty($diary_no)){
                $diary_id = $diary_no . $diary_year;
                #for creating data for efiled_document_table
                $tmpArr = array();
                $tmpArr['efiling_no'] = $efiling_no;
                $tmpArr['efiled_type'] = 'OEFCRF';
                $tmpArr['diary_no'] = $diary_id;
                $tmpArr['allocated_to'] = $scrutiny_usercode;
                $tmpArr['created_at'] =  date('Y-m-d H:i:s');
                $tmpArr['created_by'] = $refiled_by_aor_code;
                $tmpArr['updated_by_ip'] = getClientIP();
                $tmpArr['display'] = 'Y';

                $refile_result=$this->efiling_webservices->updateOldEfilingData($tmpArr); // update the refiled_case data into icmis table

                if((isset($refile_result['records_inserted']['refiled_old_efiling_case_efiled_docs']) && !empty($refile_result['records_inserted']['refiled_old_efiling_case_efiled_docs'])) ){

                    $output['status'] = "success";
                    log_message('debug', " ReFiled old e-filing cases document has been transfer to scrutiny user.");
                    $output['message'] = "File has been transfer to scrutiny.";
                }
                else{
                    $output['status'] = "error";
                    log_message('debug', "Something went wrong while updation of refiled case,Please try again later.");
                    $output['message'] = "Something went wrong,Please try again later.";
                }
                               
                /*$params =array();
                $params['registration_id'] = $registration_id;
                $totalFeeData =  $this->Common_model->getTotalFeeByRegistrationId($params);
                $totalFee =0;
                if(isset($totalFeeData[0]->total) && !empty($totalFeeData[0]->total)){
                    $totalFee = (int)$totalFeeData[0]->total;
                }
                $params = array();
                $params['totalFee'] = $totalFee;
                $params['diaryNo'] = $diary_id;
                if($totalFee != 0){
                    $res =  $this->efiling_webservices->updateFeeByRegistrationId($params);
                }
                else{
                    $res=array();
                }
                if((isset($res['status']) && !empty($res['status'])) || $totalFee == 0){

                    $output['status'] = "success";
                    log_message('CUSTOM', "ReFiled cases document has been transfer to scrutiny user.");
                    $output['message'] = "File has been transfer to scrutiny.";
                }
                else{
                    $output['status'] = "error";
                    log_message('CUSTOM', "Something went wrong while updation of refiled case,Please try again later.");
                    $output['message'] = "Something went wrong,Please try again later.";
                }*/

            }
        }
        return json_encode($output);
        // exit(0);
    }

    public function transferOldEfilingRefiledCasesCRON_deactivated_on_05102023()
    {
        $allOldEfilingSubmittedCasesList = $this->Common_model->get_all_old_efiling_submitted_cases_for_cron(REFILED_OLD_EFILING_CASE, OLD_CASES_REFILING, USER_ADVOCATE); //get old efiling all refiled cases which are at REFILED_OLD_EFILING_CASE(37) stage
        foreach ($allOldEfilingSubmittedCasesList as $case) {
            $diary_id = $case->diary_no . $case->diary_year;

            /* get scrutiny user from ICMIS */
            $assoc_arr = array();
            $assoc_arr['diaryNo'] = $diary_id;
            $assoc_json = json_encode($assoc_arr);
            $key = config('Encryption')->key;
            $encrypted_string = $this->encrypter->encrypt($assoc_json, $key);
            $scrutinyUser = $this->efiling_webservices->getScrutinyUserByDiaryNo($encrypted_string);
            $scrutiny_usercode = !empty($scrutinyUser['user_details'][0]['usercode']) ? $scrutinyUser['user_details'][0]['usercode'] : NULL;

            $tmpArr = array();
            $tmpArr['efiling_no'] = $case->efiling_no;
            $tmpArr['efiled_type'] = 'OEFCRF';
            $tmpArr['diary_no'] = $diary_id;
            $tmpArr['allocated_to'] = $scrutiny_usercode;
            $tmpArr['created_at'] = $case->create_on;
            $tmpArr['created_by'] = $case->aor_code;
            $tmpArr['updated_by_ip'] = $case->create_by_ip;
            $tmpArr['display'] = 'Y';
            //check whether the case has already transfer to ICMIS or Not
            $already_sycned_to_icmis_response=null;
            //date('Y-m-d', strtotime($case->create_on))
            $already_sycned_to_icmis_response = $this->efiling_webservices->checkRefiledCaseDataAlreadySyncedOrNot($case->diary_no,$case->diary_year,$case->create_on,$case->aor_code);

            if(empty($already_sycned_to_icmis_response->case_refiling_status[0]))
            {
                $response = $this->efiling_webservices->updateOldEfilingData($tmpArr); // update the refiled_case data into icmis=-refiled_old_efiling_case_efiled_docs table
                $response = (array)json_decode($response, true);

                if ($response['success'] == 'success') {
                    var_dump($case) . '<br>';
                    echo $response['message'] . '<br><hr>';
                } else {
                    //var_dump($case) . '<br>';;
                    echo "Old E-filing refiling data has been submitted successfully inserted for Diary Number : ".$diary_id.", Kindly check after 30 minutes. <br><hr>";
                }
            }
            else
            {
                echo "Diary No.:".$diary_id." Refiled data Already Synced <br><hr>";
                continue;
            }

            

        }
    }



        // Refile - Old e-filing case- stop


}


