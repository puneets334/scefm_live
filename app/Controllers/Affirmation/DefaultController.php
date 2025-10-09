<?php

namespace App\Controllers\Affirmation;

use App\Controllers\BaseController;
use App\Models\Affirmation\AffirmationModel;
use App\Models\Affirmation\EsignSignatureModel;
use App\Models\DocumentIndex\DocumentIndexModel;
use App\Models\DocumentIndex\DocumentIndexSelectModel;
use App\Models\NewCase\GetDetailsModel;
use App\Models\ShcilPayment\PaymentModel;
use App\Models\UploadDocuments\UploadDocsModel;
use TCPDF;
class DefaultController extends BaseController {

    protected $DocumentIndex_model;
    protected $DocumentIndex_Select_model;
    protected $Payment_model;
    protected $Get_details_model;
    protected $Affirmation_model;
    protected $Esign_signature_model;
    protected $UploadDocs_model;

    public function __construct() {
        parent::__construct();
        $this->DocumentIndex_model = new DocumentIndexModel();
        $this->DocumentIndex_Select_model = new DocumentIndexSelectModel();
        $this->Payment_model = new PaymentModel();
        $this->Get_details_model = new GetDetailsModel();
        $this->Affirmation_model = new AffirmationModel();
        $this->Esign_signature_model = new EsignSignatureModel();
        $this->UploadDocs_model = new UploadDocsModel();
    }

    public function index() {
        if (check_party() !=true) {
            $this->session->setFlashData('msg', '<div class="alert alert-danger text-center">Please enter every party details before moving to further tabs.</div>');
            return redirect()->to(base_url('newcase/extra_party'));
        }
        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            //$index_pdf_details = $this->DocumentIndex_Select_model->unfilled_pdf_pages_for_index($registration_id);
            $index_pdf_details = $this->DocumentIndex_Select_model->is_index_created($registration_id);
            if (!empty($index_pdf_details)) {
                $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, JAIL_SUPERINTENDENT);
                if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
                    $_SESSION['MSG'] = message_show("fail", 'Unauthorised Access !');
                    return redirect()->to(base_url('adminDashboard'));
                    exit(0);
                }
                $pending_court_fee=getPendingCourtFee();
                if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
                    /*if (!in_array(NEW_CASE_COURT_FEE, explode(',', $_SESSION['efiling_details']['breadcrumb_status']))) {*/
                    if ((!in_array(NEW_CASE_COURT_FEE, explode(',', $_SESSION['efiling_details']['breadcrumb_status'])) && ($pending_court_fee>0))) {
                        $_SESSION['MSG'] = message_show("fail", 'Please Pay Court Fees First.');
                        log_message('CUSTOM', "Please Pay Court Fees First.");
                        return redirect()->to(base_url('newcase/courtFee'));
                        exit(0);
                    } else{
                        //update the payment breadcrumbs status when no payment required(- DONE BY KBPUJARI on 26/04/2023
                        if($pending_court_fee>0) {
                            $breadcrumb_to_update = NEW_CASE_COURT_FEE;
                            $update_courtfee_breadcrumb_status = $this->Payment_model->remove_breadcrumb($registration_id, $breadcrumb_to_update);
                        } else{
                            $breadcrumb_to_update = NEW_CASE_COURT_FEE;
                            $update_courtfee_breadcrumb_status = $this->Payment_model->update_breadcrumbs($registration_id, $breadcrumb_to_update);
                        }
                    }
                    $view_page = 'newcase/new_case_view';
                    $redirectURL = 'newcase/view';
                } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
                    if ((!in_array(MISC_BREAD_COURT_FEE, explode(',', $_SESSION['efiling_details']['breadcrumb_status']))) && ($pending_court_fee>0)) {
                        $_SESSION['MSG'] = message_show("fail", 'Please make Payment first.');
                        log_message('CUSTOM', "Please make Payment first.");
                        return redirect()->to(base_url('miscellaneous_docs/courtFee'));
                        exit(0);
                    } else{
                        //update the payment breadcrumbs status when no payment required(- DONE BY KBPUJARI on 26/04/2023
                        if($pending_court_fee>0) {
                            $breadcrumb_to_update = MISC_BREAD_COURT_FEE;
                            $update_courtfee_breadcrumb_status = $this->Payment_model->remove_breadcrumb($registration_id, $breadcrumb_to_update);
                        } else{
                            $breadcrumb_to_update = MISC_BREAD_COURT_FEE;
                            $update_courtfee_breadcrumb_status = $this->Payment_model->update_breadcrumbs($registration_id, $breadcrumb_to_update);
                        }
                    }
                    $view_page = 'miscellaneous_docs/miscellaneous_docs_view';
                    $redirectURL = 'miscellaneous_docs/view';
                } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
                    if ((!in_array(IA_BREAD_COURT_FEE, explode(',', $_SESSION['efiling_details']['breadcrumb_status']))) && ($pending_court_fee>0)) {
                        $_SESSION['MSG'] = message_show("fail", 'Please make Payment first.');
                        return redirect()->to(base_url('IA/courtFee'));
                        exit(0);
                    } else{
                        //update the payment breadcrumbs status when no payment required(- DONE BY KBPUJARI on 26/04/2023
                        if($pending_court_fee>0) {
                            $breadcrumb_to_update = IA_BREAD_COURT_FEE;
                            $update_courtfee_breadcrumb_status = $this->Payment_model->remove_breadcrumb($registration_id, $breadcrumb_to_update);
                        } else{
                            $breadcrumb_to_update = IA_BREAD_COURT_FEE;
                            $update_courtfee_breadcrumb_status = $this->Payment_model->update_breadcrumbs($registration_id, $breadcrumb_to_update);
                        }
                    }
                    $view_page = 'IA/ia_view';
                    $redirectURL = 'IA/view';
                } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MENTIONING) {
                    if ((!in_array(MEN_BREAD_COURT_FEE, explode(',', $_SESSION['efiling_details']['breadcrumb_status']))) && ($pending_court_fee>0)) {
                        $_SESSION['MSG'] = message_show("fail", 'Please make Payment first.');
                        return redirect()->to(base_url('mentioning/courtFee'));
                        exit(0);
                    } else{
                        //update the payment breadcrumbs status when no payment required(- DONE BY KBPUJARI on 26/04/2023
                        if($pending_court_fee>0) {
                            $breadcrumb_to_update = MEN_BREAD_COURT_FEE;
                            $update_courtfee_breadcrumb_status = $this->Payment_model->remove_breadcrumb($registration_id, $breadcrumb_to_update);
                        } else{
                            $breadcrumb_to_update = MEN_BREAD_COURT_FEE;
                            $update_courtfee_breadcrumb_status = $this->Payment_model->update_breadcrumbs($registration_id, $breadcrumb_to_update);
                        }
                    }
                    $view_page = 'mentioning/mentioning_view';
                    $redirectURL = 'mentioning/view';
                } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_JAIL_PETITION) {
                    $view_page = 'jailPetition/new_case_view';
                    $redirectURL = 'jailPetition/view';
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
                    $this->session->setFlashData('msg', '<div class="alert alert-danger text-center">Please fill '.$total.' remaining dead/minor party details</div>');
                    log_message('CUSTOM', "Please fill ".$total." remaining dead/minor party details");
                    return redirect()->to(base_url('newcase/lr_party'));
                    exit(0);
                }
                $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
                if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
                    $_SESSION['MSG'] = message_show("fail", 'Invalid Stage.');
                    return redirect()->to(base_url($redirectURL));
                    exit(0);
                }
                $registration_id = $_SESSION['efiling_details']['registration_id'];
                $data['esigned_docs_details'] = $this->Affirmation_model->get_esign_doc_details($registration_id);
                $data['certificate_lock_status'] = $this->Esign_signature_model->get_join_data('efil.esign_certificate ec', array('ec.efiling_no' => $_SESSION['efiling_details']['efiling_no'], 'ec.estab_code' => $_SESSION['estab_details']['estab_code']), array(array('table' => 'efil.tbl_case_parties cp', 'condition' => 'cp.id=ec.ref_tbl_case_parties_id', 'left')), 'ec.id asc');
                $party_sign_info = $this->Esign_signature_model->get_data('efil.esign_certificate', array('efiling_no' => $_SESSION['efiling_details']['efiling_no'], 'estab_code' => $_SESSION['estab_details']['estab_code']));
                if (($data['esigned_docs_details'] == FALSE || $data['esigned_docs_details'][0]->is_data_valid == FALSE) && $party_sign_info == FALSE) {
                    $this->gen_advocate_affirmation();
                }
                $this->render($view_page, $data);
            } else {
                $updateData = "";
                foreach ($index_pdf_details as $val) {
                    $updateData .= $val['doc_title'] . " , ";
                }
                $updateData = !empty($updateData) ? rtrim($updateData,' | ') : '';
                echo "<script>alert('$updateData Pdf file index is not complete');
                window.location.href='" . base_url() . "documentIndex';</script>";
                log_message('CUSTOM', "$updateData Pdf file index is not complete.");
                exit(0);
            }
        }
    }

    private function gen_advocate_affirmation() {
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $efiling_num = $_SESSION['efiling_details']['efiling_no'];
        $est_code = $_SESSION['estab_details']['estab_code'];
        $result = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => 'P', 'm_a_type' => 'M', 'party_id' => NULL, 'view_lr_list' => FALSE));
        $_SESSION['breadcrumb_enable']['efiling_type'] = $result[0]['ref_m_efiled_type_id'];
        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            //This code comment and add new line to show indexed file details in advocate affirmation pdf on 16/11/2020
            //$uploaded_docs = $this->UploadDocs_model->get_uploaded_pdfs($registration_id);
            //echo $registration_id;exit();
            //$uploaded_docs = $this->UploadDocs_model->get_uploaded_pdfs($registration_id);
            $uploaded_indexes = $this->DocumentIndex_Select_model->get_index_items_list($registration_id);
            $uploaded_docs = $this->UploadDocs_model->get_uploaded_pdfs($registration_id);
        }
        //----------------START PDF FOR Uploaded Document Hash-----------// 
        $up_doc_hash1 = '<tr><td colspan="4" style="font-size:11px">&nbsp;<br/><strong>Uploaded Documents :</strong></td></tr>';
        $up_doc_hash2 = '<tr><td colspan="4"><table cellspacing="0" cellpadding="1" border="1" style="font-size:11px"><thead>
            <tr style="background-color:#FFFF00;">
                <th style="width:5%" align="center"> # </th>
                <th style="width:20%" align="center"> Title ( Documents ) </th>
                <th style="width:65%"> File Hash </th>
                <th style="width:10%" align="center"> No. of Pages </th> 
            </tr></thead><tbody>';
        $sr = 1;
        $indx = 1;
        $up_doc_hash3 = "";
        $up_doc_hash4 = "";
        $up_doc_hash5 = "";
        $oath_note = "";
        if (isset($uploaded_docs) && !empty($uploaded_docs)) {
            foreach ($uploaded_docs as $doc) {
                $indx_txt=$doc['page_no'];
                $up_doc_hash3 .= '<tr>
                    <td style="width:5%" align="center">' . htmlentities($sr++, ENT_QUOTES) . '</td>
                    <td style="width:20%" align="center">' . htmlentities(strtoupper($doc['doc_title']), ENT_QUOTES) . '</td>
                    <td>' . $doc['doc_hashed_value']. '</td>
                    <td style="width:10%" align="center">' . htmlentities($indx_txt, ENT_QUOTES) . '</td>
                </tr>';
            }
        } else {
            $up_doc_hash3 .= '<tr><td align="center" colspan="4">No Documents Uploaded</td></tr>';
        }
        $up_doc_hash4 .= '</tbody></table>';
        $up_doc_hash5 = '<br/></td></tr>';
        $up_doc_hash = $up_doc_hash1 . $up_doc_hash2 . $up_doc_hash3 . $up_doc_hash4 . $up_doc_hash5 . $oath_note;
        // The following code written by K.B Pujari to display the indexes list
        $created_index_1 = '<tr><td colspan="4" style="font-size:11px">&nbsp;<br/><strong>Filed Indexes:</strong></td></tr>';
        $created_index_2 = '<tr><td colspan="4"><table cellspacing="0" cellpadding="1" border="1" style="font-size:11px"><thead>
            <tr style="background-color:#FFFF00;">
                <th style="width:5%" align="center"> # </th>
                <th style="width:70%" align="left"> Index </th>
            </tr></thead><tbody>';
        $sr = 1;
        $created_index_3 = "";
        if (isset($uploaded_indexes) && !empty($uploaded_indexes)) {
            foreach ($uploaded_indexes as $indexes) {
                $created_index_3 .= '<tr>
                    <td style="width:5%" align="center">' . htmlentities($sr++, ENT_QUOTES) . '</td>
                    <td>' . $indexes['docdesc']. '</td>                                   
                </tr>';
            }
        } else {
            $created_index_3 .= '<tr><td align="center" colspan="4">No Indexes Created</td></tr>';
        }
        $created_index_4 = '</tbody></table>';
        $created_index_5 = '<br/></td></tr>';
        $up_indexes_list=$created_index_1 . $created_index_2 . $created_index_3 . $created_index_4 . $created_index_5;
        //indexes code end
        $litigant_name = strtoupper($result[0]['party_name']);
        $litigant_details = 'I have explained him the contents in the pleadings. I have seen documents uploaded in support of the pleadings. '
                . '<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                . 'Litigant <strong>' . strtoupper($result[0]['party_name']) . '</strong>  has signed and verified the correctness of pleadings and uploaded documents in my presence';
        //  }
        $oath_part_pet_tbl1 = '<table cellspacing="0" cellpadding="1" style="font-size:13px"><tbody>';
        $oath_title1 = '<tr><td  colspan="2" style="text-align:center"><strong> Identification and Verification</strong></td></tr>';
        $oath_part_head = '<tr><td style="text-align:left"><strong>Efiling Number : ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . '</strong></td>
                                                <td style="text-align:right"><strong>Litigant Name : ' . strtoupper($litigant_name) . '</strong></td></tr>';
        $oath_part_pet1 = '<tr  colspan="2"><td></td></tr>';
        $oath_part_pet2 = '<tr><td colspan="2">'
            . 'The above named litigant, the pairokar/one of the petitoners/appellants/respondent in the above matter and such acquainted with the facts of the case.</td></tr><br>';
        $oath_part_pet2_2 = '<tr><td colspan="2">'
            . 'The facts stated in the accompanying petition are true and the rest are on information derived from the papers of the case and believed to be true.</td></tr><br>';
        $oath_part_pet2_3 = '<tr><td colspan="2">'
            . 'That no Special Leave/W.P./T.P./has been filed in the above matter earlier by me in the Honble Supreme Court against the impunged Order/Judgment. Decree for similar relief.</td></tr><br>';
        $oath_part_pet2_4 = '<tr><td colspan="2">'
            . 'The fact stated in the accompanying Misc. Petition for filing proof of surrender/exemption from filing of the impugned order/application for substitution of L.R.S. and others misc. petition with their annexures are true derived from the record of the case.</td></tr><br>';
        $oath_part_pet2_5 = '<tr><td colspan="2">'
            . 'I say that the facts stated in the Special Leave Petition and List of Dates are true and the submissions made therein are based on legal advice.</td></tr><br>';
        $oath_part_pet2_6 = '<tr><td colspan="2">'
            . 'That the annexures being enclosed with the petition, are true copies of their respective originals.</td></tr><br>';
        $oath_part11 = '<tr><td colspan="2" style="text-align:justify">  I undertake to submit signed copies of pleadings and original / certified copies of documents physically before the court within time limit prescribed  by Rules / Circulars issued by The Supreme Court of India further, hence verified on this <strong>' . date('d') . '</strong> Day of  <strong>' . date('M Y') . '.</strong><br><br></td></tr>';
        $oath_part_pet3 = '<tr><td colspan="2" style="text-align:right" ><strong>' . strtoupper($_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name']) . '</strong> </td></tr>';
        $oath_part_pet4 = '<tr><td colspan="2" style="text-align:right" >Bar Reg. No. <strong>' . strtoupper($_SESSION['login']['bar_reg_no']) . '</strong> </td></tr>';
        $oath_part_pet_tbl2 = '</tbody></table><br>';
        $oath_tbl_adv = $oath_part_pet_tbl1 . $oath_part_head . $oath_title1 . $oath_part_pet1 . $oath_part_pet2 .$oath_part_pet2_2.$oath_part_pet2_3.$oath_part_pet2_4.$oath_part_pet2_5.$oath_part_pet2_6. $oath_part11 . $oath_part_pet3 . $oath_part_pet4 . $up_doc_hash .$up_indexes_list. $oath_part_pet_tbl2;
        // -------------------------------- End of PDF for Advocate Affirmation -------------------------------------------//
        //------------------------Uploaded Receipts-----------------------//
        if (!(bool) $_SESSION['estab_details']['enable_payment_gateway']) {
            $temp_table_3 = '<h4> Fee Receipts :</h4><table cellspacing="0" cellpadding="1" border="1" style="font-size:11px"><thead><tr style="background-color:#FFFF00;color:#0000FF;">
                       <th style="width:5%" align="center"> # </th><th style="width:16%" align="center"> Transaction Number</th><th style="width:17%" align="center"> Transaction Date </th><th style="width:17%" align="center"> Court Fee </th> <th style="width:15%" align="center"> Uploaded On </th><th style="width:30%" align="center"> hash(SHA256)</th></tr></thead><tbody>';
            $sr = 1;
            if (isset($uploaded_receipts) && !empty($uploaded_receipts)) {
                foreach ($uploaded_receipts as $receipts) {
                    $temp_table_3 .= '<tr><td style="width:5%" align="center">' . htmlentities($sr++, ENT_QUOTES) . '</td><td style="width:16%" align="center">' . htmlentities($receipts['transaction_num'], ENT_QUOTES) . '</td><td style="width:17%" align="center">' . htmlentities(date('d-m-Y', strtotime($receipts['txn_complete_date'])), ENT_QUOTES) . '</td><td style="width:17%" align="center">' . htmlentities($receipts['user_declared_court_fee'], ENT_QUOTES) . '</td><td style="width:15%" align="center">' . htmlentities(date('d-m-Y H:i:s A', strtotime($receipts['receipt_upload_date'])), ENT_QUOTES) . '</td><td style="width:30%" align="center">' . htmlentities($receipts['receipts_hashed_value'], ENT_QUOTES) . '</td></tr>';
                }
            } else {
                $temp_table_3 .= '<tr><td align="center" style="width:100%" colspan="5">Fee not paid</td></tr>';
            }
            $temp_table_3 .= '</tbody></table>';
        } else {
            $temp_table_3 = '';
        }
        //----------------------End Uploaded receipts--------------------//
        ob_start();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(FALSE);
        $pdf->SetPrintFooter(TRUE);
        $pdf->SetAuthor('Computer Cell, SCI');
        $pdf->SetTitle('Computer Cell, SCI');
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->SetFont('helvetica', '', 8, '', true);
        $pdf->AddPage();
        ob_end_clean();
        if ((bool) $_SESSION['estab_details']['enable_payment_gateway']) {
            $pdf->writeHTML($oath_tbl_adv, true, false, false, false, '');
        } else {
            $pdf->writeHTML($oath_tbl_adv . $temp_table_3, true, false, false, false, '');
        }
        $pdf->lastPage();
        $unsigned_pdf_path = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/unsigned_pdf/';
        if (!is_dir($unsigned_pdf_path)) {
            $uold = umask(0);
            if (mkdir('uploaded_docs/' . $est_code . '/' . $efiling_num . '/unsigned_pdf/', 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($unsigned_pdf_path . '/index.html', $html);
            }
            umask($uold);
        }
        $pdf->Output($unsigned_pdf_path . $efiling_num . '_Advocate_UnsignedCertificate.pdf', 'F');
    }

}


