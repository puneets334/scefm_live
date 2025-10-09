<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\Admin\EfilingActionModel;
use App\Models\Common\CommonModel;
use App\Models\IA\ViewModel;
use stdClass;

class EfilingAction extends BaseController {

    protected $Efiling_action_model;
    protected $Common_model;
    protected $efiling_webservices;
    protected $View_model;
    protected $request;
    protected $validation;
    protected $encrypt;
    public function __construct() {
        parent::__construct();
        $this->Efiling_action_model = new EfilingActionModel();
        $this->Common_model = new CommonModel();
        $this->efiling_webservices = new Efiling_webservices();
        $this->View_model = new ViewModel();
        $this->request = \Config\Services::request();
        $this->validation = \Config\Services::validation();
        $this->encrypt = \Config\Services::encrypter();
    }

    public function index() {
        $Array = array(New_Filing_Stage, Initial_Defects_Cured_Stage,HOLD,DISPOSED);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $Array)) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> ' . htmlentities('Invalid Action', ENT_QUOTES) . ' </div>');
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }

        $allowed_users_array = array(USER_ADMIN);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }

        if (empty($_SESSION['efiling_details']) || empty($_SESSION['estab_details'])) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> ' . htmlentities('Invalid Action', ENT_QUOTES) . ' </div>');
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }

        $regid = getSessionData('efiling_details')['registration_id'];
        $filing_type = getSessionData('efiling_details')['ref_m_efiled_type_id'];

        if ($filing_type == E_FILING_TYPE_CAVEAT) {
            $is_already_approved_CaveatNoGenerated = $this->Efiling_action_model->is_already_approved_CaveatNoGenerated($regid);
            if ($is_already_approved_CaveatNoGenerated) {
                $efiling_details = $is_already_approved_CaveatNoGenerated;
                if ($efiling_details->ref_m_efiled_type_id == E_FILING_TYPE_CAVEAT) {
                    $this->session->setFlashdata('msg', '<div class="alert alert-success text-center">This efiling detail already transfered to ICMIS as Caveat number ' . $efiling_details->caveat_num . $efiling_details->caveat_year . '</div>');
                }
                return redirect()->to(base_url('adminDashboard'));
                exit(0);
            }
        }
        
        if ($filing_type == E_FILING_TYPE_NEW_CASE) {
            $redirectURL = 'newcase/view';
        }elseif ($filing_type == E_FILING_TYPE_JAIL_PETITION) {
            $redirectURL = 'jailPetition/view';
        }  elseif ($filing_type == E_FILING_TYPE_MISC_DOCS) {
            $redirectURL = 'miscellaneous_docs/view';
        } elseif ($filing_type == E_FILING_TYPE_DEFICIT_COURT_FEE) {
            $redirectURL = 'Deficit_court_fee/view';
        } elseif ($filing_type == E_FILING_TYPE_IA) {
            $redirectURL = 'IA/view';
        } elseif ($filing_type == E_FILING_TYPE_CAVEAT) {
            $redirectURL = 'caveat/view';
        } else {
            $redirectURL = 'adminDashboard';
        }
       
        $data = $this->Efiling_action_model->approve_case($regid, $filing_type);

        if ($data) {
            $userdata = $this->Efiling_action_model->get_efiled_by_user($_SESSION['efiling_details']['created_by']);
            $sentSMS = "eFiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " has been initially accepted for further processing.";
            log_message('alert', "eFiling no.".efile_preview($_SESSION['efiling_details']['efiling_no'])."has been initially accepted for further processing.");

            $subject = "Initially accepted : eFiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']);
            log_message('alert', "Initially accepted : eFiling no. ".efile_preview($_SESSION['efiling_details']['efiling_no']));

            $user_name = $userdata[0]->first_name . ' ' . $userdata[0]->last_name;

            //send_mobile_sms($userdata[0]->moblie_number, $sentSMS,SCISMS_Efiling_Acceptance); //Commented till SMS facility not available in Admin Server.
            send_mail_msg($userdata[0]->emailid, $subject, $sentSMS, $user_name);

            $this->session->setFlashdata('msg', '<div class="alert alert-success text-center">E-filing Number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' Approved successfully !</div>');
            log_message('alert', "E-filing Number". efile_preview($_SESSION['efiling_details']['efiling_no'])." Approved successfully !");

            return redirect()->to(base_url('adminDashboard'));
            // exit(0);
        } else {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Approval Failded. Please try again!</div>');
            log_message('alert', "Approval Failded. Please try again!");
            
            // exit(0);
            return redirect()->to(base_url($redirectURL));
        }
    }
    public function transferCase()
    {
        if (empty($_SESSION['efiling_details']) || empty($_SESSION['estab_details'])) {
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }

        $registration_id = $_SESSION['efiling_details']['registration_id'];

        $result=$this->Efiling_action_model->transferCase($registration_id);
        if ($result) {
            $this->session->setFlashdata('msg', 'E-filing number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' successfully transferred to Section-X!');
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        } else {
            echo "error";

            $this->session->setFlashdata('msg', 'Transfer failed. Please try again!');
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }
    }


    public function submit_mentioning_order() {


        if (!($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (empty($_SESSION['efiling_details'])) {
            $this->session->setFlashdata('msg', htmlentities('Invalid Action', ENT_QUOTES));
            return redirect()->to(base_url('admin'));
            exit(0);
        }
        $filing_type = getSessionData('efiling_details')['ref_m_efiled_type_id'];

        if ($filing_type == E_FILING_TYPE_MENTIONING) {
            $redirectURL = 'mentioning/view';
        } else {
            $redirectURL = 'admin';
        }

        $_POST['remark'] = trim($_POST['remark']);

        if (empty($_POST['remark'])) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Orders on Mentioning Required.</div>');
            return redirect()->to(base_url($redirectURL));
            exit(0);
        }


        $Array = array(New_Filing_Stage, Initial_Defects_Cured_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $Array)) {
            $this->session->setFlashdata('msg', htmlentities('Invalid Action', ENT_QUOTES));
            return redirect()->to(base_url('admin'));
            exit(0);
        }

        $regid = getSessionData('efiling_details')['registration_id'];

        $remark = script_remove($this->request->getPost('remark'));
        $remark_length = strip_tags($remark);


        if (strlen($remark_length) > DISAPPROVE_REMARK_LENGTH) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Orders length should be max ' . DISAPPROVE_REMARK_LENGTH . ' characters!</div>');
            return redirect()->to(base_url($redirectURL));
            exit(0);
        }


        $data = $this->Efiling_action_model->submit_mentioning_order($regid, $remark);

        if ($data) {

            $userdata = $this->Efiling_action_model->get_efiled_by_user($_SESSION['efiling_details']['created_by']);
            $user_name = $userdata[0]->first_name . ' ' . $userdata[0]->last_name;


            if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MENTIONING) {
                $this->session->setFlashdata('msg', '<div class="alert alert-success text-center"> E-filing number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' submitted successfully !</div>');
                $sentSMS = "eFiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " has been initially accepted for further processing.";
                $templateId=SCISMS_Efiling_Acceptance;
                $subject = "Initially accepted : eFiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']);

            } else {
                $this->session->setFlashdata('msg', '<div class="alert alert-success text-center"> E-filing number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' disapproved successfully !</div>');
                $sentSMS = "eFiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " has been disapproved. Please cure the notified defects through eFiling portal. - Supreme Court of India";
                $templateId=SCISMS_Efiling_Disapproved;
                $subject = "Disapproved : eFiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']);
            }

            send_mobile_sms($userdata[0]->moblie_number, $sentSMS,$templateId);
            send_mail_msg($userdata[0]->emailid, $subject, $sentSMS, $user_name);

            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }
    }

    public function disapprove_case() {
        if (!($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }

        if (empty($_SESSION['efiling_details'])) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> ' . htmlentities('Invalid Action', ENT_QUOTES) . ' </div>');
            return redirect()->to(base_url('admin'));
            exit(0);
        }
        $filing_type = getSessionData('efiling_details')['ref_m_efiled_type_id'];


        if ($filing_type == E_FILING_TYPE_NEW_CASE) {
            $redirectURL = 'New_case/view';
        } else if ($filing_type == E_FILING_TYPE_JAIL_PETITION) {
            $redirectURL = 'jailPetition/view';
        } elseif ($filing_type == E_FILING_TYPE_MISC_DOCS) {
            $redirectURL = 'miscellaneous_docs/view';
        } elseif ($filing_type == E_FILING_TYPE_CAVEAT && $_SESSION['efiling_details']['efiling_type'] =='CAVEAT') {
            $redirectURL = 'caveat/view';
        } elseif ($filing_type == E_FILING_TYPE_DEFICIT_COURT_FEE && $_SESSION['efiling_details']['efiling_type'] !='caveat') {
            $redirectURL = 'Deficit_court_fee/view';
        } elseif ($filing_type == E_FILING_TYPE_IA) {
            $redirectURL = 'IA/view';
        } elseif ($filing_type == E_FILING_TYPE_MENTIONING) {
            $redirectURL = 'mentioning/view';
        }  else {
            $redirectURL = 'admin';
        }
        $_POST['remark'] = trim($_POST['remark']);
 
        if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE ||$_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_JAIL_PETITION ) {

            if (empty($_POST['remark'])) {
                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Reason for disapproval required.</div>');
                return redirect()->to(base_url($redirectURL));
                exit(0);
            }
        }

        $Array = array(New_Filing_Stage, Initial_Defects_Cured_Stage,HOLD);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $Array)) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> ' . htmlentities('Invalid Action', ENT_QUOTES) . ' </div>');
            return redirect()->to(base_url('admin'));
            exit(0);
        }

        $regid = getSessionData('efiling_details')['registration_id'];
        if ($filing_type == E_FILING_TYPE_DEFICIT_COURT_FEE && (bool) $_SESSION['estab_details']['enable_payment_gateway'] && $_SESSION['efiling_details']['efiling_type'] !='caveat') {
            // when payment gateway is enabled and fees is being filed then disapproval of it is not allowed.
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Action you have requested is not allowed.</div>');
            return redirect()->to(base_url('admin'));
            exit(0);
        }

        $remark = script_remove($this->request->getPost('remark'));
        $remark_length = strip_tags($remark);
        if(empty($remark)){
            $this->session->setFlashdata('msg','<div class="alert alert-danger text-center">Reason for disapproval required.</div>');
            return redirect()->to(base_url($redirectURL));
        }
        if (strlen($remark_length) > DISAPPROVE_REMARK_LENGTH) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Remark should be max ' . DISAPPROVE_REMARK_LENGTH . ' characters!</div>');
            return redirect()->to(base_url($redirectURL));
        }

        $data = $this->Efiling_action_model->disapprove_case($regid, $remark);
        
        if ($data) {
            $userdata = $this->Efiling_action_model->get_efiled_by_user($_SESSION['efiling_details']['created_by']);
            $sentSMS = "eFiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " has been disapproved. Please cure the notified defects through eFiling portal. - Supreme Court of India";
            $subject = "Disapproved : eFiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']);
            $user_name = $userdata[0]->first_name . ' ' . $userdata[0]->last_name;
            //send_mobile_sms($userdata[0]->moblie_number, $sentSMS,SCISMS_Efiling_Disapproved);  //Commented on 05-08-2023
            send_mail_msg($userdata[0]->emailid, $subject, $sentSMS, $user_name);
            $this->session->setFlashdata('msg', '<div class="alert alert-success text-center"> E-filing number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' disapproved successfully !</div>');
            return redirect()->to(base_url('adminDashboard'));
        }
    }

    public function markAsErrorCase() {
        if (!($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (empty($_SESSION['efiling_details'])) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> ' . htmlentities('Invalid Action', ENT_QUOTES) . ' </div>');
            return redirect()->to(base_url('admin'));
            exit(0);
        }
        $filing_type = getSessionData('efiling_details')['ref_m_efiled_type_id'];
        if ($filing_type == E_FILING_TYPE_NEW_CASE) {
            $redirectURL = 'New_case/view';
        } else if ($filing_type == E_FILING_TYPE_JAIL_PETITION) {
            $redirectURL = 'jailPetition/view';
        } elseif ($filing_type == E_FILING_TYPE_MISC_DOCS) {
            $redirectURL = 'MiscellaneousFiling/view';
        } elseif ($filing_type == E_FILING_TYPE_CAVEAT && $_SESSION['efiling_details']['efiling_type'] =='caveat') {
            $redirectURL = 'caveat/view';
        } elseif ($filing_type == E_FILING_TYPE_DEFICIT_COURT_FEE && $_SESSION['efiling_details']['efiling_type'] !='caveat') {
            $redirectURL = 'Deficit_court_fee/view';
        } elseif ($filing_type == E_FILING_TYPE_IA) {
            $redirectURL = 'IA/view';
        } elseif ($filing_type == E_FILING_TYPE_MENTIONING) {
            $redirectURL = 'mentioning/view';
        }  else {
            $redirectURL = 'admin';
        }
        $_POST['remark'] = trim($_POST['remark']);
        if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE ||$_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_JAIL_PETITION ) {

            if (empty($_POST['remark'])) {
                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Reason for disapproval required.</div>');
                return redirect()->to(base_url($redirectURL));
                exit(0);
            }
        }
        $Array = array(New_Filing_Stage, Initial_Defects_Cured_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $Array)) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> ' . htmlentities('Invalid Action', ENT_QUOTES) . ' </div>');
            return redirect()->to(base_url('admin'));
            exit(0);
        }
        $regid = getSessionData('efiling_details')['registration_id'];
        if ($filing_type == E_FILING_TYPE_DEFICIT_COURT_FEE && (bool) $_SESSION['estab_details']['enable_payment_gateway'] && $_SESSION['efiling_details']['efiling_type'] !='caveat') {
            // when payment gateway is enabled and fees is being filed then disapproval of it is not allowed.
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Action you have requested is not allowed.</div>');
            return redirect()->to(base_url('admin'));
            exit(0);
        }
        $remark = script_remove($this->request->getPost('remark'));
        $remark_length = strip_tags($remark);
        if (strlen($remark_length) > DISAPPROVE_REMARK_LENGTH) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Remark should be max ' . DISAPPROVE_REMARK_LENGTH . ' characters!</div>');
            return redirect()->to(base_url($redirectURL));
            exit(0);
        }
        $data = $this->Efiling_action_model->markAsErrorCaseByAdmin($regid, $remark);
        if ($data) {
          //  $userdata = $this->Efiling_action_model->get_efiled_by_user($_SESSION['efiling_details']['created_by']);
           // $sentSMS = "eFiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " has been marked as error.";
           // $subject = "Marked as error eFiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']);
           // $user_name = $userdata[0]->first_name . ' ' . $userdata[0]->last_name;
            //send_mobile_sms($userdata[0]->moblie_number, $sentSMS);
           // send_mail_msg($userdata[0]->emailid, $subject, $sentSMS, $user_name);
            // log_message('info', "E-filing number ". efile_preview($_SESSION['efiling_details']['efiling_no']) ."marked as error successfully !");
            $this->session->setFlashdata('msg', '<div class="alert alert-success text-center"> E-filing number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' marked as error successfully !</div>');
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }
    }

    public function transfer_to_section() {

        if (!($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN )) {
            echo 'ERROR||||<div class="alert alert-danger text-center">Invalid access.</div>';
            exit(0);
        }
        $id = $this->request->getPost('form_submit');
        $id = url_decryption($id);
        $InputArrray = explode('#', $id);   //0=>registration_id,1=>type,2=>stage

        if (empty($InputArrray[0]) && empty($InputArrray[1]) && empty($InputArrray[2])) {
            echo 'ERROR||||<div class="alert alert-danger text-center">Invalid access.</div>';
            exit(0);
        }

        if (!is_numeric($InputArrray[0]) && !is_numeric($InputArrray[1]) && !is_numeric($InputArrray[2])) {
            echo 'ERROR||||<div class="alert alert-danger text-center">Invalid access.</div>';
            exit(0);
        }

        $registration_id = $InputArrray[0];
        $filing_type = $InputArrray[1];
        $curr_stage = $InputArrray[2];
        $main_file_reg_id = (int) $InputArrray[3];
        if ($curr_stage != Transfer_to_IB_Stage) {
            echo 'ERROR||||<div class="alert alert-danger text-center">Invalid access.</div>';
            exit(0);
        }

        if ($filing_type == E_FILING_TYPE_IA && ($main_file_reg_id != 0)) {
            $prev_check = $this->Common_model->prev_main_filing_details($main_file_reg_id, Transfer_to_CIS_Stage . ',' . E_Filed_Stage);
            if (!$prev_check) {
                $main_file_stage_id = $this->Efiling_action_model->get_current_stage($main_file_reg_id);
                echo 'ERROR||||<div class="alert alert-danger text-center"><strong>Main efiling case no.</strong> is at <strong>' . $main_file_stage_id[0]['admin_stage_name'] . '</strong> stage, therefore this IA can not be Processed.</div>'; //. url_encryption(Transfer_to_IB_Stage);
                exit(0);
            }
        }

        if ($filing_type == E_FILING_TYPE_NEW_CASE || $filing_type == E_FILING_TYPE_JAIL_PETITION || $filing_type == E_FILING_TYPE_IA || $filing_type == E_FILING_TYPE_MISC_DOCS || $filing_type == E_FILING_TYPE_DEFICIT_COURT_FEE || $filing_type == E_FILING_TYPE_CAVEAT) {
            $stage_id = Transfer_to_CIS_Stage;
        } else {
            
        }

        $result = $this->Efiling_action_model->transfer_to_section($registration_id, $stage_id);

        if ($result) {
            $this->session->setFlashdata('MSG', '<div class="alert alert-success text-center">Efiling number transfered to filing section successfully!</div>');
            echo 'SUCCESS||||' . url_encryption(Transfer_to_IB_Stage);
        } else {

            echo 'ERROR||||<div class="alert alert-danger text-center">Failed to transfer!</div>';
        }
    }


    public function getCISData()
    {
        $diary = $this->request->getGet('diaryno');
        $diary= url_decryption($diary);
        $diary=explode('#', $diary);
        $diaryno=$diary[0];
        $registration_id=$diary[1];
        unset($_SESSION['estab_details']);
        unset($_SESSION['efiling_details']);
        // SETS $_SESSION['estab_details']
        $estab_details = $this->Common_model->get_establishment_details();
        if ($estab_details) {
            //SET $_SESSION['efiling_details']
            $efiling_num_details = $this->Common_model->get_efiling_num_basic_Details($registration_id);
        } else {
            return redirect()->to(base_url('/'));exit(0);
        }

        $data['details']=$this->efiling_webservices->getCISData($diaryno);
            $diary_number=substr($diaryno,0,-4);
            $diary_year=substr($diaryno,-4);
            if(!empty($diaryno))
            {
                $data['case_listing_details']=$this->efiling_webservices->getCaseListedDetail($diary_number,$diary_year);
            }
        $data['parties']=$this->efiling_webservices->get_parties($diaryno);
        $data['advocates']=$this->efiling_webservices->get_advocate_list($diaryno);
        $data['diaryno']=$diaryno;
        $data['efiling_data']=$this->Common_model->getDetailsByRegistrationId($registration_id);
        $registeredDocs=array();
        foreach($data['efiling_data'] as $index=>$doc){
            if(isset($doc->icmis_docnum) && !empty($doc->icmis_docnum) && isset($doc->icmis_docyear) && !empty($doc->icmis_docyear)){
                $registeredDocs[$index]['doc_id']=$doc->doc_id;
                $registeredDocs[$index]['doc_title']=$doc->doc_title;
            }
        }
        $data['registeredDocs']=$registeredDocs;
        $data['documents']=$this->Common_model->get_uploaded_documents($registration_id);
        $data['payment_details'] = $this->View_model->get_payment_details($registration_id);
        return $this->render('adminDashboard.showDetailsForIARegistration', $data);
    }


    public function registerDoc()
    {
        if (!($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $doc_id= !empty($this->request->getPost('doc_id')) ? $this->request->getPost('doc_id') : NULL;
        $diary_no= !empty($this->request->getPost('diary_no')) ? $this->request->getPost('diary_no') : NULL;
        $remarks=!empty($this->request->getPost('Remarks_data')) ? $this->request->getPost('Remarks_data') : NULL;
        $efiling_data=$this->Common_model->getDocDetailsById($doc_id);
        $registration_id = !empty($efiling_data[0]['registration_id']) ? (int)$efiling_data[0]['registration_id'] : NULL;
        if(isset($registration_id) && !empty($registration_id)){
          $params = array();
          $params['registration_id'] = $registration_id;
          $docFeeDetails =   $this->Common_model->getDocumentFeeByRegistrationId($params);
            $icmisUserData = $this->Common_model->getIcmisUserCodeByRegistrationId($params);
            $userIcmisCode = !empty($icmisUserData[0]->icmis_usercode) ? (int)$icmisUserData[0]->icmis_usercode : 0;
        }else{
            $userIcmisCode=$_SESSION['login']['icmis_usercode'];
        }
        $amicus_curiae_request_params = ['type' => 'D','value' => $diary_no];
        $amicus_curiae_advocate = json_decode(curl_get_contents(ICMIS_SERVICE_URL.'/ConsumedData/get_advocateDetails?' . http_build_query($amicus_curiae_request_params)));
        $advocate_id=0;
        if (isset($amicus_curiae_advocate->data) && !empty($amicus_curiae_advocate->data[0])){
            $advocate_id=$amicus_curiae_advocate->data[0]->advocate_id;
        }else{
            $advocate_id=$efiling_data[0]['aor_code'];
        }
        $advocate_id=empty($advocate_id) ? $efiling_data[0]['aor_code']  : $advocate_id;
        $doc_details_data=array(
            'efiling_no'=>$efiling_data[0]['efiling_no'],
            'doc_id'=>$doc_id,
            'diary_no'=>$diary_no,
            'doccode'=>$efiling_data[0]['doc_type_id'],
            'doccode1'=>$efiling_data[0]['sub_doc_type_id'],
            'iastat'=>'P',
            'usercode'=>$userIcmisCode,
            'ent_dt'=>date('Y-m-d H:i:s'),
            'display'=>'Y',
            'advocate_id'=>$advocate_id,
            'no_of_copy'=>$efiling_data[0]['no_of_copies'],
            'filedby'=>$efiling_data[0]['first_name'].' '.$efiling_data[0]['last_name'],
            'remarks'=>$remarks,
            'court_fee'=>(isset($docFeeDetails[0]->user_declared_court_fee) && !empty($docFeeDetails[0]->user_declared_court_fee)) ? $docFeeDetails[0]->user_declared_court_fee : 0,
            'printing_fee'=>(isset($docFeeDetails[0]->printing_total) && !empty($docFeeDetails[0]->printing_total)) ? $docFeeDetails[0]->printing_total : 0,
        );
        $doc_details_data=json_encode($doc_details_data);
            $key = config('Encryption')->key;
            $encrypted_string = $this->encrypt->encrypt($doc_details_data);

        $docnum=$this->efiling_webservices->registerDoc($encrypted_string);
        return json_encode($docnum);
        exit(0);
      }
      public function attachDoc(){
          if (!($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN)) {
              return redirect()->to(base_url('/'));
              exit(0);
          }
          $doc_id= !empty($this->request->getPost('doc_id')) ? $this->request->getPost('doc_id') : NULL;
          $diary_no= !empty($this->request->getPost('diary_no')) ? $this->request->getPost('diary_no') : NULL;
          $attach_with_doc_id= !empty($this->request->getPost('attach_with_doc_id')) ? $this->request->getPost('attach_with_doc_id') : NULL;
          $result=$this->Efiling_action_model->attach_doc($diary_no,$doc_id,$attach_with_doc_id);
          if($result){
              echo json_encode(array('status'=>'SUCCESS','message'=>'Doc attached successfully!'));
          }
          else{
              echo json_encode(array('status'=>'ERROR','message'=>'There is some problem while updating!'));
          }
          exit(0);
      }
      public function updateDocumentNumber(){
          if (!($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN)) {
              return redirect()->to(base_url('/'));
              exit(0);
          }
          $registration_id = !empty($_SESSION['efiling_details']['registration_id']) ? (int)$_SESSION['efiling_details']['registration_id'] : NULL;
          $doc_id= !empty($this->request->getPost('doc_id')) ? $this->request->getPost('doc_id') : NULL;
          $doc_number= !empty($this->request->getPost('doc_number')) ? $this->request->getPost('doc_number') : NULL;
          $remaining= !empty($this->request->getPost('remaining')) ? $this->request->getPost('remaining') : NULL;
          $efiling_data=$this->Common_model->getDocDetailsById($doc_id);
          $filed_type = (!empty($efiling_data[0]->doc_type_id) && isset($efiling_data[0]->doc_type_id)) ? $efiling_data[0]->doc_type_id : NULL ;
          $stage_id = 0;
          if(isset($filed_type) && !empty($filed_type) && $filed_type == 8){
              $stage_id = IA_E_Filed;
          }
          else{
              $stage_id = Document_E_Filed;
          }
          if(isset($doc_number) && !empty($doc_number) && isset($doc_id) && !empty($doc_id)){
            $updateArr = array();
            $updateArr['icmis_docyear'] = date('Y');
            $updateArr['icmis_docnum'] = $doc_number;
            $updateArr['icmis_doccode'] = !empty($efiling_data[0]->doc_type_id) ? $efiling_data[0]->doc_type_id : NULL;
            $updateArr['icmis_doccode1'] = !empty($efiling_data[0]->sub_doc_type_id) ? $efiling_data[0]->sub_doc_type_id : NULL;
            $updateArr['updated_on'] = date('Y-m-d H:i:s');
            $updateArr['update_ip_address'] = $_SERVER['REMOTE_ADDR'];
            $updateArr['updated_by'] = getSessionData('login')['id'];
            $updateArr['icmis_iastat'] = 'P';
            $params = array();
            $params['table_name'] ='efil.tbl_efiled_docs';
            $params['whereFieldName'] ='doc_id';
            $params['whereFieldValue'] = $doc_id;
            $params['updateArr'] = $updateArr;
            $upRes = $this->Common_model->updateTableData($params);
            if(isset($upRes) && !empty($upRes)){
                if(isset($registration_id) && !empty($registration_id) && isset($remaining) && !empty($remaining) && $remaining == 1){
                   $this->Common_model->changeIaMiscDocStage($registration_id,$stage_id);
                }
                return json_encode(array('status'=>'SUCCESS','message'=>'Doc No.'.$doc_number.' has been updated successfully!'));
                exit(0);
            }
            else{
                return json_encode(array('status'=>'ERROR','message'=>'Something went wrong,Pleaes try again later'));
                exit(0);
            }
        }
      }

    public function updateRefiledCase()
    {
        if (!($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $output = array();
        $postData = json_decode(file_get_contents('php://input'), true);
        $registration_id = !empty($postData['registration_id']) ? (int)$postData['registration_id'] : NULL;
        if (isset($registration_id) && !empty($registration_id)) {
            //go to scrutiny
            $diary_no = !empty($_SESSION['efiling_details']['diary_no']) ? (int)$_SESSION['efiling_details']['diary_no'] : NULL;
            if (isset($diary_no) && !empty($diary_no)) {
                $diary_id = $_SESSION['efiling_details']['diary_no'] . $_SESSION['efiling_details']['diary_year'];
                #from AOR to fdr for scrutiny
                $this->efiling_webservices->updateFilTrapAORtoFDR($_SESSION['efiling_details']['efiling_no']);
                $efiling_no = $_SESSION['efiling_details']['efiling_no'];
                $result =  $this->setReturnedByAdvocate($diary_id, $efiling_no);
                if (isset($result['doc_details']) && !empty($result['doc_details'])) {
                    foreach ($result['doc_details'] as $k => $v) {
                        $tmpArr = array();
                        $tmpArr['icmis_diary_no'] = $diary_id;
                        $tmpArr['icmis_doccode'] = 8;
                        $tmpArr['icmis_docnum'] = (int)$v;
                        $tmpArr['icmis_docyear'] = $_SESSION['efiling_details']['diary_year'];
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
                if (isset($totalFeeData[0]->total) && !empty($totalFeeData[0]->total)) {
                    $totalFee = (int)$totalFeeData[0]->total;
                }
                $params = array();
                $params['totalFee'] = $totalFee;
                $params['diaryNo'] = $diary_id;
                if ($totalFee != 0) {
                    $res =  $this->efiling_webservices->updateFeeByRegistrationId($params);
                } else {
                    $res = array();
                }
                if ((isset($res['status']) && !empty($res['status'])) || $totalFee == 0) {
                    $next_stage = I_B_Approval_Pending_Admin_Stage;
                    $_SESSION['efiling_details']['stage_id'] = I_B_Approval_Pending_Admin_Stage;
                    $this->Common_model->updateCaseStatus($registration_id, $next_stage);
                    $output['status'] = "success";
                    $output['message'] = "File has been transfer to scrutiny.";
                } else {
                    $output['status'] = "error";
                    $output['message'] = "Something went wrong,Please try again later.";
                }
            }
        }
        echo json_encode($output);
        exit(0);
    }

    public function setReturnedByAdvocate($diary_id, $efiling_no)
    {
        $registration_id = !empty($_SESSION['efiling_details']['registration_id']) ? $_SESSION['efiling_details']['registration_id'] : NULL;
        $efiled_docs_list = $this->View_model->get_index_items_list($registration_id, 1);
        $docTempArr = array();
        $output = false;
        if (isset($efiled_docs_list) && !empty($efiled_docs_list)) {
            foreach ($efiled_docs_list as $k => $v) {
                $docTempArr[] = (object)$v;
            }
        }
        $documents = !empty($efiled_docs_list[0]) ? $docTempArr : array(new stdClass());
        if (count($documents) > 0) {
            $created_by = !empty($documents[0]->uploaded_by) ? $documents[0]->uploaded_by : NULL;
        }
        $pet_adv_id = 0;
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
                } else {
                    $pet_adv_id = PETITIONER_IN_PERSON;
                }
            }
        }
        $assoc_arr = array('diary_id' => $diary_id, 'efiling_no' => $efiling_no, 'pet_adv_id' => $pet_adv_id, 'documents' => $documents);
        $assoc_json = json_encode($assoc_arr);
        $key = config('Encryption')->key;
        $encrypted_string = $this->encrypt->encrypt($assoc_json);
        $this->efiling_webservices->setReturnedByAdvocate($encrypted_string);
        $output = $this->efiling_webservices->saveRefiledIAData($encrypted_string);
        return $output;
    }

    public function hold() {
        $Array = array(New_Filing_Stage, Initial_Defects_Cured_Stage,HOLD,DISPOSED);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $Array)) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> ' . htmlentities('Invalid Action', ENT_QUOTES) . ' </div>');
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }
        $allowed_users_array = array(USER_ADMIN);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }

        if (empty($_SESSION['efiling_details']) || empty($_SESSION['estab_details'])) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> ' . htmlentities('Invalid Action', ENT_QUOTES) . ' </div>');
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }

        $regid = getSessionData('efiling_details')['registration_id'];
        $filing_type = getSessionData('efiling_details')['ref_m_efiled_type_id'];

        $data = $this->Efiling_action_model->hold_case($regid, $filing_type);
        if ($data) {
            $userdata = $this->Efiling_action_model->get_efiled_by_user($_SESSION['efiling_details']['created_by']);
            $sentSMS = "eFiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " has been initially accepted for further processing.";
            $subject = "Initially accepted : eFiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']);
            $user_name = $userdata[0]->first_name . ' ' . $userdata[0]->last_name;
            send_mail_msg($userdata[0]->emailid, $subject, $sentSMS, $user_name);
            $this->session->setFlashdata('msg', '<div class="alert alert-success text-center">E-filing Number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' Hold successfully !</div>');
            return redirect()->to(base_url('adminDashboard'));
        } else {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Hold Failded. Please try again!</div>');
            if ($filing_type == E_FILING_TYPE_NEW_CASE) {
                $redirectURL = 'newcase/view';
            }elseif ($filing_type == E_FILING_TYPE_JAIL_PETITION) {
                $redirectURL = 'jailPetition/view';
            }  elseif ($filing_type == E_FILING_TYPE_MISC_DOCS) {
                $redirectURL = 'miscellaneous_docs/view';
            } elseif ($filing_type == E_FILING_TYPE_DEFICIT_COURT_FEE) {
                $redirectURL = 'Deficit_court_fee/view';
            } elseif ($filing_type == E_FILING_TYPE_IA) {
                $redirectURL = 'IA/view';
            } elseif ($filing_type == E_FILING_TYPE_CAVEAT) {
                $redirectURL = 'caveat/view';
            } else {
                $redirectURL = 'adminDashboard';
            }
            return redirect()->to(base_url($redirectURL));
        }
    }

    public function noaction(){
        if (!($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $doc_id= !empty($this->request->getPost('doc_id')) ? $this->request->getPost('doc_id') : NULL;
        $result=$this->Efiling_action_model->no_action($doc_id);
        if($result){
            echo json_encode(array('status'=>'SUCCESS','message'=>'Updated successfully!'));
        }
        else{
            echo json_encode(array('status'=>'ERROR','message'=>'There is some problem while updating!'));
        }
        exit(0);
    }
    public function disposed() {//print_r($_SESSION['efiling_details']);exit();
        $Array = array(New_Filing_Stage, Initial_Defects_Cured_Stage,HOLD,DISPOSED);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $Array)) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> ' . htmlentities('Invalid Action', ENT_QUOTES) . ' </div>');
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }

        $allowed_users_array = array(USER_ADMIN);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }

        if (empty($_SESSION['efiling_details']) || empty($_SESSION['estab_details'])) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> ' . htmlentities('Invalid Action', ENT_QUOTES) . ' </div>');
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }

        $regid = getSessionData('efiling_details')['registration_id'];
        $filing_type = getSessionData('efiling_details')['ref_m_efiled_type_id'];

        $data = $this->Efiling_action_model->disposed_case($regid, $filing_type);
        if ($data) {
            $userdata = $this->Efiling_action_model->get_efiled_by_user($_SESSION['efiling_details']['created_by']);
            $sentSMS = "eFiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " has been initially accepted for further processing.";
            $subject = "Initially accepted : eFiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']);
            $user_name = $userdata[0]->first_name . ' ' . $userdata[0]->last_name;
            send_mail_msg($userdata[0]->emailid, $subject, $sentSMS, $user_name);
            $this->session->setFlashdata('msg', '<div class="alert alert-success text-center">E-filing Number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' Disposed successfully !</div>');
            return redirect()->to(base_url('adminDashboard'));
        } else {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Disposed Failded. Please try again!</div>');
            if ($filing_type == E_FILING_TYPE_NEW_CASE) {
                $redirectURL = 'newcase/view';
            }elseif ($filing_type == E_FILING_TYPE_JAIL_PETITION) {
                $redirectURL = 'jailPetition/view';
            }  elseif ($filing_type == E_FILING_TYPE_MISC_DOCS) {
                $redirectURL = 'miscellaneous_docs/view';
            } elseif ($filing_type == E_FILING_TYPE_DEFICIT_COURT_FEE) {
                $redirectURL = 'Deficit_court_fee/view';
            } elseif ($filing_type == E_FILING_TYPE_IA) {
                $redirectURL = 'IA/view';
            } elseif ($filing_type == E_FILING_TYPE_CAVEAT) {
                $redirectURL = 'caveat/view';
            } else {
                $redirectURL = 'adminDashboard';
            }
            return redirect()->to(base_url($redirectURL));
        }
    }

    // Cron Auto Transfer to scrutiny changes : start
    public function autoTransferToScrutiny_cron($diary_no = 'All') {
        $output = array(); // Initialize as an array to collect responses
        $result = $this->Efiling_action_model->get_pending_transferToScrutiny_list_cron($diary_no);
        
        if (!empty($result)) {
            foreach ($result as $row) {
                $registration_id = (isset($row['registration_id']) && !empty($row['registration_id'])) ? (int)$row['registration_id'] : NULL;
                $diary_no = (isset($row['sc_diary_num']) && !empty($row['sc_diary_num'])) ? (int)$row['sc_diary_num'] : NULL;
                $diary_year = (isset($row['sc_diary_year']) && !empty($row['sc_diary_year'])) ? (int)$row['sc_diary_year'] : NULL;
                $efiling_no = (isset($row['efiling_no']) && !empty($row['efiling_no'])) ? $row['efiling_no'] : NULL;
                
                if (!empty($registration_id) && !empty($efiling_no) && !empty($diary_no) && !empty($diary_year)) {
                    // Collect the response in the output array
                    $output[] = json_decode($this->autoTransferToScrutiny($registration_id, $diary_no, $diary_year, $efiling_no), true);
                } else {
                    $output[] = [
                        'status' => 'error',
                        'message' => "The registration_id field is required. $registration_id"
                    ];
                    log_message('info', "The registration_id field is required. $registration_id");
                }
            }
        } else {
            log_message('info', "All refiled case e-filing completed");
            $output[] = [
                'message' => "All refiled case e-filing completed"
            ];
        }
        
        // Encode the final output as JSON and echo it
        echo json_encode($output);
    }
    // Cron Auto Transfer to scrutiny changes : End
    // Admin Transfer to scrutiny changes : start
    public function transferToScrutiny(){
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
            $registration_id = (isset($_REQUEST['registration_id']) && !empty($_REQUEST['registration_id'])) ? (int)$_REQUEST['registration_id'] : NULL;
            $diary_no = (isset($_REQUEST['diary_no']) && !empty($_REQUEST['diary_no'])) ? (int)$_REQUEST['diary_no'] : NULL;
            $diary_year = (isset($_REQUEST['diary_year']) && !empty($_REQUEST['diary_year'])) ? (int)$_REQUEST['diary_year'] : NULL;
            $efiling_no = (isset($_REQUEST['efiling_no']) && !empty($_REQUEST['efiling_no'])) ? $_REQUEST['efiling_no'] : NULL;
            $response_validation = '';
            $output = array();
            if (empty($registration_id)) {
                $response_validation .= "The registration_id field is required.</br>";
            }
            if (empty($diary_no)) {
                $response_validation .= "The diary_no field is required.</br>";
            }
            if (empty($diary_year)) {
                $response_validation .= "The diary_year field is required.</br>";
            }
            if (empty($efiling_no)) {
                $response_validation .= "The efiling_no field is required.</br>";
            }
            if (!empty($response_validation)) {
                $output['status'] = "error";
                $output['message'] = $response_validation;
                $output=json_encode($output);
            }
            if (!empty($registration_id) && !empty($efiling_no) && !empty($diary_no) && !empty($diary_year)) {
                $output = $this->autoTransferToScrutiny($registration_id, $diary_no, $diary_year, $efiling_no);
            }
        }else{
            $output['message']= "You are not authorized!";
            $output=json_encode($output);
        }
      echo $output;exit(0);
    }
    public function autoTransferToScrutiny($registration_id,$diary_no,$diary_year,$efiling_no)
    {
        //echo $registration_id.'#'.$diary_no.'#'.$diary_year.'#'.$efiling_no;exit();
            $output = array();
            if(!empty($registration_id) && !empty($efiling_no) && !empty($diary_no) && !empty($diary_year)){
                //go to scrutiny
                if(isset($diary_no) && !empty($diary_no)){
                    $diary_id = $diary_no.$diary_year;
                    #from AOR to fdr for scrutiny
                    $this->efiling_webservices->updateFilTrapAORtoFDR($efiling_no);
                    $result =  $this->setReturnedByAdvocate($diary_id, $efiling_no);
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
                        log_message('info', "File has been transfer to scrutiny e-filing number :$efiling_no");
                        $output['message'] = " File has been transfer to scrutiny e-filing number :$efiling_no";
                    }
                    else{
                        $output['status'] = "error";
                        log_message('info', " Something went wrong while updation of refiled case e-filing number :$efiling_no,Please try again later.");
                        $output['message'] = " Something went wrong refiled case e-filing number :$efiling_no, Please try again later.";
                    }
                }
            }else{
                $output['status'] = "error";
                log_message('info', "Data not found refiled case e-filing number :$efiling_no");
                $output['message'] = "Data not found. refiled case e-filing number :$efiling_no";
            }
            return json_encode($output);
    }
    //Admin Transfer to scrutiny changes : End


}
