<?php

namespace App\Controllers\MiscellaneousDocs;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\ShareDoc\ShareDocsModel;
use App\Models\DocumentIndex\DocumentIndexDropDownModel;
use App\Models\DocumentIndex\DocumentIndexSelectModel;

class FinalSubmit extends BaseController {

    protected $Common_model;
    protected $Share_docs_model;
    protected $DocumentIndex_DropDown_model;
    protected $DocumentIndex_Select_model;
    
    public function __construct() {
        parent::__construct();
        $this->Common_model = new CommonModel();
        $this->Share_docs_model = new ShareDocsModel();
    }

    public function index() {
        $index_data = '';
        $sentSMS = '';
        $next_stage = '';
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT,AMICUS_CURIAE_USER);
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
                if(!empty($_SESSION['efiling_details']['registration_id'])) {
                    return redirect()->to(base_url('efilingAction/IAMiscDocsRefiledFinalSubmit'));
                }
            }
        }
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
                $user_name = $_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'];
                //code added on 27 jan 21 to get diary num for mesg
                $diary_no=$this->Common_model->get_Diary_no_forMsg($registration_id);
                $diaryno=$diary_no[0]["diaryno"];
                //Code added on 25 jan 21 to send proper message to users other than login user.
                //XXXXXXXXXXXXXXX START 23-03-2021 XXXXXXXXXXXX
                if (!empty($registration_id)) {
                    $efiled_docs_list_mail = $this->Common_model->get_index_items_list_mail($registration_id);
                } else {
                    $efiled_docs_list_mail = NULL;
                }                
                if (!empty($efiled_docs_list_mail)) {
                    foreach ($efiled_docs_list_mail as $doc_list) {
                        /* $index_data .= '<a href="' . base_url('documentIndex/viewIndexItem/' . url_encryption($doc_list['doc_id'].'##'.'pdfmail')) . '" target="_blank">' . $doc_list['doc_title'] . '<br>( ' . $doc_list['docdesc'] . ')</a> ';*/                        
                        // $key=$this->config->item( 'encryption_key' );
                        $msg = $doc_list['doc_id'];
                        // $encrypted_string = $this->encrypt->encode($msg , $key);
                        $encrypted_string = integerEncreption($msg);
                        $index_data .= '<a href="' . base_url('documentIndex/WithoutLoginViewIndex/' . $encrypted_string) . '" target="_blank">' . $doc_list['doc_title'] . '<br>( ' . $doc_list['docdesc'] . ')</a> ';
                        /*$index_data .= '<a href="' . base_url('documentIndex/WithoutLoginViewIndex/' . $doc_list['doc_id']) . '" target="_blank">' . $doc_list['doc_title'] . '<br>( ' . $doc_list['docdesc'] . ')</a> ';*/
                    }
                } else {
                    /*$index_data .= '<tr><td colspan="4" class="text-center">No record found.</td></tr>';*/
                    $index_data .= 'No record found.';
                }
                //XXXXXXXXXXXXXXXX END XXXXXXXXXXXX
                $sentSMS .= "Document have been filed in Diary No. $diaryno as Efiling no. ". efile_preview($_SESSION['efiling_details']['efiling_no']) . " by Advocate $user_name  and is pending for initial approval with efiling admin. - Supreme Court of India";
                // log_message('CUSTOM', "Document have been filed in Diary No. $diaryno as Efiling no. ". efile_preview($_SESSION['efiling_details']['efiling_no']) . " by Advocate $user_name  and is pending for initial approval with efiling admin. - Supreme Court of India");
                $sentSMS .= '<br>';
                $sentSMS .=  'Folowing Details Are '.' ' .  $index_data;
                // till here
                $subject = "Submitted : Efiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']);
                $share_emails = $this->Common_model->get_share_email_details($registration_id);
                if (!empty($share_emails)) {
                    foreach ($share_emails as $emails) {
                        // send_mobile_sms($emails['email'], $sentSMS,SCISMS_Document_Filed_In_Diary);
                        $send_email=send_mail_msg($emails['email'], $subject, $sentSMS, ucwords($emails['name']));
                        // if($send_email=='success') {
                            $doc_email_updated = array(
                                'id' => $emails['id'],
                                'sent_on_mail' => date('Y-m-d H:i:s'),
                                'sent_on_mail_flag' => 'T'
                            );
                            $share_emails_updated = $this->Share_docs_model->add_doc_share_email_updated($emails['id'],$doc_email_updated);
                        // }
                    }
                }
                // Code to sent mail to login user
                $sentSMS = "Efiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " has been submitted and is pending for initial approval with efiling admin. - Supreme Court of India";
                // log_message('CUSTOM', "Efiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " has been submitted and is pending for initial approval with efiling admin. - Supreme Court of India");
                send_mobile_sms($_SESSION['login']['mobile_number'], $sentSMS,SCISMS_Initial_Approval);
                send_mail_msg($_SESSION['login']['emailid'], $subject, $sentSMS, $user_name);
                getSessionData('msg', '<div class="alert alert-success text-center"> E-filing number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' submitted successfully for approval of E-filing Admin.!</div>');
                $this->session->setFlashdata('msg','<div class="alert alert-success text-center"> E-filing number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' submitted successfully for approval of E-filing Admin.!</div>');
                $_SESSION['efiling_details']['stage_id']=Initial_Approaval_Pending_Stage;
                return redirect()->to(base_url('miscellaneous_docs/view'));
            } else{
                getSessionData('msg', '<div class="alert alert-danger text-center">Submition failed. Please try again!</div>');
                // log_message('CUSTOM', "Miscellaneous Docs- Submition failed. Please try again!");
                return redirect()->to(base_url('miscellaneous_docs/view'));
            }
        }
    }

}