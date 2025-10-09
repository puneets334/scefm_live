<?php

namespace App\Controllers\IA;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\NewCase\NewCaseModel;
use App\Models\NewCase\DropdownListModel;
use App\Models\NewCase\GetDetailsModel;
use App\Models\DocumentIndex\DocumentIndexDropDownModel;
use App\Models\DocumentIndex\DocumentIndexSelectModel;

class FinalSubmit extends BaseController
{
    protected $Common_model;
    protected $DocumentIndex_DropDown_model;
    protected $DocumentIndex_Select_model;

    public function __construct()
    {
        parent::__construct();
        $this->Common_model = new CommonModel();
    }

    public function index()
    {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, AMICUS_CURIAE_USER);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
        if (empty($_SESSION['efiling_details']) || empty($_SESSION['estab_details'])) {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
        $ddm = new \App\Models\DocumentIndex\DocumentIndexDropDownModel();
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        /*The following portion written by Mr.Anshu as on dated 04092025 to check the case must have atlest one pdf uploaded and must have at least one index item : start*/
        $uploaded_pdf = $ddm->get_uploaded_pdfs($registration_id);
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
        $dsm = new \App\Models\DocumentIndex\DocumentIndexSelectModel();
        $index_pdf_details = $dsm->is_index_created($registration_id);
        if (empty($index_pdf_details)) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> Pdf file index is not complete.</div>');
            return redirect()->to(base_url('documentIndex'));
            exit(0);
        }
        /*The following portion written by Mr.Anshu as on dated 04092025 to check the case must have atlest one pdf uploaded and must have at least one index item : end*/
        $marked_defect_tobe_shown_stages = array(I_B_Defected_Stage, I_B_Rejected_Stage);
        if (in_array($_SESSION['efiling_details']['stage_id'], $marked_defect_tobe_shown_stages)) {
            $result_icmis = $this->Common_model->get_ia_docs_cis_defects_remarks($registration_id, FALSE);
            if (isset($result_icmis) && !empty($result_icmis)) {
                foreach ($result_icmis as $re) {
                    $aor_cured = (isset($re->aor_cured) && !empty($re->aor_cured)) ? $re->aor_cured : "f";
                    if ($aor_cured == 'f') {
                        $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> Please Mark All Defects Cured Before Final Submit...</div>');
                        return redirect()->to(base_url('documentIndex'));
                    }
                }
                // isRefilingCompulsoryIADefect($_SESSION['efiling_details']['registration_id'], $_SESSION['efiling_details']['stage_id']);
                if (!empty($_SESSION['efiling_details']['registration_id'])) {
                    return redirect()->to(base_url('efilingAction/IAMiscDocsRefiledFinalSubmit'));
                }
            }
        }
        $next_stage = 0;
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
            getSessionData('msg', '<div class="alert alert-danger text-center">Invalid Action.</div>');
            return redirect()->to(base_url('dashboard'));
        }
        $final_submit = TRUE;
        if ($final_submit) {
            $result = $this->Common_model->updateCaseStatus($registration_id, $next_stage);
            if ($result) {
                $sentSMS = "Efiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " has been submitted and is pending for initial approval with efiling admin. - Supreme Court of India";
                // log_message('CUSTOM', "Efiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " has been submitted and is pending for initial approval with efiling admin. - Supreme Court of India");
                $subject = "Submitted : Efiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']);
                $user_name = $_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'];
                send_mobile_sms($_SESSION['login']['mobile_number'], $sentSMS, SCISMS_Initial_Approval);
                send_mail_msg($_SESSION['login']['emailid'], $subject, $sentSMS, $user_name);
                // getSessionData('msg', '<div class="alert alert-success text-center"> E-filing number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' submitted successfully for approval of E-filing Admin.!</div>');
                // log_message('CUSTOM', "E-filing number ". efile_preview($_SESSION['efiling_details']['efiling_no'])." submitted successfully for approval of E-filing Admin.!");
                $_SESSION['efiling_details']['stage_id'] = Initial_Approaval_Pending_Stage;
                getSessionData('msg', '<div class="alert alert-success text-center"> E-filing number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' submitted successfully for approval of E-filing Admin.!</div>');
                $this->session->setFlashdata('msg', '<div class="alert alert-success text-center"> E-filing number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' submitted successfully for approval of E-filing Admin.!</div>');
                return redirect()->to(base_url('IA/view'));

            } else {
                getSessionData('msg', '<div class="alert alert-danger text-center">Submition failed. Please try again!</div>');
                // log_message('CUSTOM', "Submition failed. Please try again!");
                return redirect()->to(base_url('IA/view'));
            }
        }
    }
}
