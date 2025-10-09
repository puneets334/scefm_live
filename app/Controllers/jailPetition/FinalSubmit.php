<?php

namespace App\Controllers\jailPetition;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\JailPetition\JailPetitionModel;
use App\Models\NewCase\GetDetailsModel;
use App\Models\DocumentIndex\DocumentIndexSelectModel;
use App\Models\DocumentIndex\DocumentIndexDropDownModel;

class FinalSubmit extends BaseController
{

    protected $Common_model;
    protected $JailPetitionModel;
    protected $Get_details_model;
    protected $DocumentIndex_Select_model;
    protected $DocumentIndex_DropDown_model;

    public function __construct()
    {
        parent::__construct();
        $this->Common_model = new CommonModel();
        $this->JailPetitionModel = new JailPetitionModel();
        $this->Get_details_model = new GetDetailsModel();
        $this->DocumentIndex_Select_model = new DocumentIndexSelectModel();
        $this->DocumentIndex_DropDown_model = new DocumentIndexDropDownModel();
    }

    public function validate()
    {
        $ans = $this->Common_model->valid_cde($_SESSION['efiling_details']['registration_id']);
        $arr_data = explode('-', $ans);
        $status = $arr_data[0];
        $status = (ltrim($status, ','));
        $chk_status = "13";
        if (!in_array(1, explode(',', $status))) {
            $final_outcome = 1;
        }
        if (!in_array(3, explode(',', $status))) {
            $final_outcome = $final_outcome . ',' . '3';
        }
        echo $final_outcome;
    }

    public function index()
    {
        $allowed_users_array = array(JAIL_SUPERINTENDENT);
        if (!empty($_SESSION['login']) && !in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('admindashboard'));
            exit(0);
        }
        if (empty($_SESSION['efiling_details']) || empty($_SESSION['estab_details'])) {
            return redirect()->to(base_url('jail_dashboard'));
            exit(0);
        }
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        /*The following portion written by Mr.Anshu as on dated 04092025 to check the case must have atlest one pdf uploaded and must have at least one index item : start*/
        $uploaded_pdf = $this->DocumentIndex_DropDown_model->get_uploaded_pdfs($registration_id);
        if (!empty($uploaded_pdf)) {
            foreach ($uploaded_pdf as $row) {
                $file_partial_path = $row['file_path'];
                $file_name = $row['file_name'];
                if (file_exists($file_partial_path)) {
                    $doc_title = $_SESSION['efiling_details']['efiling_no'] . '_' . str_replace(' ', '_', $row['doc_title']) . '.pdf';
                } else {
                    $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> Pdf File does not exist.</div>');
                    return redirect()->to(base_url('documentIndex'));
                    exit(0);
                }
            }
        } else {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> Pdf File does not exist.</div>');
            return redirect()->to(base_url('documentIndex'));
            exit(0);
        }
        $index_pdf_details = $this->DocumentIndex_Select_model->is_index_created($registration_id);
        if (empty($index_pdf_details)) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> Pdf file index is not complete.</div>');
            return redirect()->to(base_url('documentIndex'));
            exit(0);
        }
        /*The following portion written by Mr.Anshu as on dated 04092025 to check the case must have atlest one pdf uploaded and must have at least one index item : end*/
        $respondent_details = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => 'R', 'm_a_type' => 'M', 'party_id' => NULL, 'view_lr_list' => FALSE));
        $respondent_state = $respondent_details[0]['org_state_id'];
        if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_DEFICIT_COURT_FEE && (bool) $_SESSION['estab_details']['enable_payment_gateway']) {
            $next_stage = Transfer_to_IB_Stage;
        } elseif ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
            $next_stage = Draft_Stage;
        } elseif ($_SESSION['efiling_details']['stage_id'] == Draft_Stage) {
            $next_stage = Initial_Approaval_Pending_Stage;
        } elseif ($_SESSION['efiling_details']['stage_id'] == Initial_Defected_Stage) {
            $next_stage = Initial_Defects_Cured_Stage;
        } elseif ($_SESSION['efiling_details']['stage_id'] == DEFICIT_COURT_FEE) {
            $next_stage = DEFICIT_COURT_FEE_PAID;
        } elseif ($_SESSION['efiling_details']['stage_id'] == I_B_Defected_Stage) {
            $next_stage = I_B_Defects_Cured_Stage;
        } elseif ($_SESSION['efiling_details']['stage_id'] == I_B_Rejected_Stage || $_SESSION['efiling_details']['stage_id'] == E_REJECTED_STAGE) {
            $next_stage = Initial_Defects_Cured_Stage;
        } else {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Invalid Action.</div>');
            return redirect()->to(base_url('jail_dashboard'));
        }
        $result = $this->JailPetitionModel->updateJailPetitionStatus($registration_id, $next_stage, $respondent_state);
        if ($result) {
            $sentSMS = "Efiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " has been submitted and is pending for initial approval with efiling admin. - Supreme Court of India";
            $subject = "Submitted : Efiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']);
            $user_name = $_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'];
            send_mobile_sms($_SESSION['login']['mobile_number'], $sentSMS, SCISMS_Initial_Approval);
            send_mail_msg($_SESSION['login']['emailid'], $subject, $sentSMS, $user_name);
            $this->session->setFlashdata('msg', '<div class="alert alert-success text-center"> E-filing number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' submitted successfully for approval of E-filing Admin.!</div>');
            return redirect()->to(base_url('jail_dashboard'));
            exit(0);
        } else {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Submition failed. Please try again!</div>');
            return redirect()->to(base_url('jail_dashboard'));
            exit(0);
        }
    }
}
