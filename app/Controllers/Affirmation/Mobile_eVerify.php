<?php
namespace App\Controllers;

use App\Models\Affirmation\AffirmationModel;
use App\Models\Common\CommonModel;
use App\Models\NewCase\GetDetailsModel;
use App\Models\Newcase\NewCaseModel;
use TCPDF;

class Mobile_eVerify extends BaseController {

    protected $Common_model;
    protected $New_case_model;
    protected $Get_details_model;
    protected $Affirmation_model;

    public function __construct() {
        parent::__construct();
        $this->Common_model = new CommonModel();
        $this->New_case_model = new NewCaseModel();
        $this->Get_details_model = new GetDetailsModel();
        $this->Affirmation_model = new AffirmationModel();
    }

    public function index() {
        return redirect()->to(base_url('affirmation'));
        exit(0);
    }

    public function send_adv_litigent_mobile_otp_everified_doc() {
        unset($_SESSION['MOB_OTP_VERIFY_UPLOADED_DOCS']);
        unset($_SESSION['eVerified_mobile_otp']);
        if (empty($_POST['name'])) {
            echo '1@@@' . htmlentities('Enter Litigent Name', ENT_QUOTES);
            exit(0);
        } elseif (empty($_POST['mobile'])) {
            echo '1@@@' . htmlentities('Enter Litigent Mobile Number', ENT_QUOTES);
            exit(0);
        }
        if (!empty($_POST['mobile']) && !empty($_POST['name'])) {
            $validate_nums = validate_number($_POST['mobile'], TRUE, 10, 10, 'Mobile number');
            if ($validate_nums['response'] == FALSE) {
                echo '1@@@' . htmlentities($validate_nums['msg']['field_name'], ENT_QUOTES);
                exit(0);
            }
            $validate_name = validate_names($_POST['name'], TRUE, 3, 99, 'Name');
            if ($validate_name['response'] == FALSE) {
                echo '1@@@' . htmlentities($validate_name['msg']['field_name'], ENT_QUOTES);
                exit(0);
            }
            if (empty($_SESSION['eVerified_mobile_otp']['LITIGENT_MOB_OTP_VERIFY'])) {
                $mob_otp = rand(111111, 999999);
                $_SESSION['eVerified_mobile_otp']['LITIGENT_MOB_OTP_VERIFY'] = $mob_otp;
                $_SESSION['eVerified_mobile_otp']['LITIGENT_MOBILE'] = $_POST['mobile'];
                $_SESSION['eVerified_mobile_otp']['LITIGENT_NAME'] = $_POST['name'];
                $sentSMS = "OTP is " . $mob_otp . " for eVerify uploaded document with eFiling application. - Supreme Court of India";
                $result = send_mobile_sms($_POST['mobile'], $sentSMS,SCISMS_eVerify_document);
            }
        }
        if ($result) {
            echo '2@@@' . htmlentities('done', ENT_QUOTES);
        } else {
            echo '1@@@' . htmlentities('Some things went wrong. Please Try again', ENT_QUOTES);
        }
    }

    public function verify_adv_mobile_otp() {
        $adv_mob = '';
        $adv_name = '';
        $verified_mobile = '';
        unset($_SESSION['MOB_OTP_VERIFY_UPLOADED_DOCS']);
        if (!($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON || $_SESSION['login']['ref_m_usertype_id'] == JAIL_SUPERINTENDENT )) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (isset($_SESSION['efiling_details']) && !empty($_SESSION['efiling_details'])) {
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $efiling_num = $_SESSION['efiling_details']['efiling_no'];
            $est_code = $_SESSION['estab_details']['estab_code'];
        } else {
            $_SESSION['MSG'] = message_show("fail", 'Invalid request !');
            return redirect()->to(base_url('affirmation'));
            exit(0);
        }
        if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
            $redirect_url = 'new_case/affirmation';
        } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
            $redirect_url = 'miscellaneousFiling/affirmation';
        } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_DEFICIT_COURT_FEE) {
            $redirect_url = 'Deficit_court_fee/affirmation';
        } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
            $redirect_url = 'IA/affirmation';
        } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_JAIL_PETITION) {
            $redirect_url = 'jailPetition/affirmation';
        }
        $litigent_flag = url_decryption($_POST['litigent_flag']);
        $advocate_flag = url_decryption($_POST['adv_flag']);
        $mobile_otp = escape_data($_POST["mobile_otp"]);
        if (empty($mobile_otp)) {
            echo '1@@@' . htmlentities('Please Enter OTP', ENT_QUOTES);
            exit(0);
        }
        $otp = $_SESSION['eVerified_mobile_otp']['LITIGENT_MOB_OTP_VERIFY'];
        $litigent_mobile = $_SESSION['eVerified_mobile_otp']['LITIGENT_MOBILE'];
        $litigent_name = $_SESSION['eVerified_mobile_otp']['LITIGENT_NAME'];
        $validate_name = validate_number($mobile_otp, TRUE, 6, 6, 'Mobile OTP');
        if ($validate_name['response'] == FALSE) {
            echo '1@@@' . htmlentities($validate_name['msg']['field_name'], ENT_QUOTES);
            exit(0);
        }
        if ($otp != $mobile_otp) {
            echo '1@@@' . htmlentities('Wrong Mobile OTP.', ENT_QUOTES);
            exit(0);
        }
        $est_code = $_SESSION['estab_details']['estab_code'];
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $efiling_num = $_SESSION['efiling_details']['efiling_no'];
        $signed_pdf_full_path = getcwd() . '/uploaded_docs/' . $est_code . '/' . $efiling_num . '/signed_pdfs/';
        $signed_pdf_partial_path = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/signed_pdfs/';
        $signed_pdf_full_path_with_file_name = $signed_pdf_full_path . $efiling_num . '_advocate_otp_everified.pdf';
        $signed_pdf_partial_path_with_file_name = $signed_pdf_partial_path . $efiling_num . '_advocate_otp_everified.pdf';
        $signed_pdf_file_name = $efiling_num . '_advocate_otp_everified.pdf';
        $this->gen_mobile_advocate_affirmation($adv_mob, $adv_name);
        $type = ESIGNED_DOCS_BY_ADV;
        $name = $litigent_name;
        $mobile = $litigent_mobile;
        $data = array(
            'ref_registration_id' => $registration_id,
            'ref_efiling_no' => $efiling_num,
            'type' => $type,
            'otp_mobile_no' => $verified_mobile,
            'is_data_valid' => TRUE,
            'signed_pdf_full_path' => $signed_pdf_full_path,
            'signed_pdf_full_path_with_file_name' => $signed_pdf_full_path_with_file_name,
            'signed_pdf_partial_path' => $signed_pdf_partial_path,
            'signed_pdf_partial_path_with_file_name' => $signed_pdf_partial_path_with_file_name,
            'signed_pdf_file_name' => $signed_pdf_file_name,
            'name_as_on_aadhar' => $name,
            'otp_mobile_no' => $mobile,
            'signed_type' => 3
        );
        if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
            $breadcrumbs_to_update = NEW_CASE_AFFIRMATION;
        } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
            $breadcrumbs_to_update = MISC_BREAD_AFFIRMATION;
        } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
            $breadcrumbs_to_update = IA_BREAD_AFFIRMATION;
        } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MENTIONING) {
            $breadcrumbs_to_update = MEN_BREAD_AFFIRMATION;
        } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_JAIL_PETITION) {
            $breadcrumbs_to_update = JAIL_PETITION_AFFIRMATION;
        }
        $result = $this->Affirmation_model->upload_pet_adv_esign_docs($data, $registration_id, $breadcrumbs_to_update);
        if ($result) {
            unset($_SESSION['MOB_OTP_VERIFY_UPLOADED_DOCS']);
            echo '2@@@' . htmlentities('OTP verified successfully!', ENT_QUOTES);
            exit(0);
        } else {
            echo '1@@@' . htmlentities('Wrong Mobile OTP.', ENT_QUOTES);
            exit(0);
        }
    }

    public function gen_mobile_advocate_affirmation($adv_mobile, $adv_name) {
        $up_doc_hash3 = '';
        $up_doc_hash4 = '';
        $oath_note = '';
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $efiling_num = $_SESSION['efiling_details']['efiling_no'];
        $est_code = $_SESSION['estab_details']['estab_code'];
        $result = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => 'R', 'm_a_type' => 'M', 'party_id' => NULL, 'view_lr_list' => FALSE));
        $_SESSION['breadcrumb_enable']['efiling_type'] = $result['ref_m_efiled_type_id'];
        if ($result[0]['orgid'] != '' && $result[0]['orgid'] != '0') {
            $org_name = $this->New_case_model->get_org_name($registration_id);
        }
        $uploaded_docs = $this->Common_model->get_uploaded_documents($registration_id);
        //----------------------------------START PDF FOR Uploaded Document Hash-------------------------------------------// 
        $up_doc_hash1 = '<tr><td colspan="2" style="font-size:11px">&nbsp;<br/><strong>Uploaded Documents :</strong></td></tr>';
        $up_doc_hash2 = '<tr><td colspan="2"><table cellspacing="0" cellpadding="1" border="1" style="font-size:11px"><thead><tr style="background-color:#FFFF00;">
            <th style="width:5%" align="center"> # </th><th style="width:20%" align="center"> Title ( Documents ) </th><th style="width:25%" align="center"> Uploaded Documents  </th><th style="width:10%" align="center"> Pages </th> <th style="width:10%" align="center"> Index </th> <th style="width:30%" align="center">hash(SHA256)</th></tr></thead><tbody>';
        $sr = 1;
        $indx = 1;
        if (isset($uploaded_docs) && !empty($uploaded_docs)) {
            foreach ($uploaded_docs as $doc) {
                $st_indx = $indx;
                $indx += $doc->page_no;
                if ($doc->page_no != 1) {
                    $end_indx = $indx - 1;
                } else {
                    $end_indx = $st_indx;
                }
                $indx_txt = $st_indx . ' - ' . $end_indx;
                $up_doc_hash3 .= '<tr><td style="width:5%" align="center">' . htmlentities($sr++, ENT_QUOTES) . '</td><td style="width:20%" align="center">' . htmlentities(strtoupper($doc->doc_title), ENT_QUOTES) . '<br> ( ' . htmlentities(strtoupper($doc->doc_type_name), ENT_QUOTES) . ' )' . '</td><td style="width:25%" align="center">' . htmlentities($doc->file_name, ENT_QUOTES) . '</td><td style="width:10%" align="center">' . htmlentities($doc->page_no, ENT_QUOTES) . '</td><td style="width:10%" align="center">' . htmlentities($indx_txt, ENT_QUOTES) . '</td><td style="width:30%" align="center">' . htmlentities($doc->doc_hashed_value, ENT_QUOTES) . '</td></tr>';
            }
        } else {
            $up_doc_hash3 .= '<tr><td align="center" colspan="2">No Documents Uploaded</td></tr>';
        }
        $up_doc_hash4 .= '</tbody></table>';
        $up_doc_hash5 = '<br/></td></tr>';
        $up_doc_hash = $up_doc_hash1 . $up_doc_hash2 . $up_doc_hash3 . $up_doc_hash4 . $up_doc_hash5 . $oath_note;
        //----------------------------------End PDF FOR Uploaded Document Hash-------------------------------------------// 
        //----------------------------------START PDF FOR Advocate Affirmation-------------------------------------------//
        if ($result[0]['org_dept_id'] != '' && $result[0]['org_dept_id'] != '0') {
            $litigant_name = $org_name[0]['org_dept_name'];
            $litigant_details = 'I have explained the contents in the pleadings to the authorized representative of <strong>' . strtoupper($result[0]['party_name']) . '</strong> '
                . '<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' .
                'Authorized representative <strong>' . strtoupper($result[0]['name']) . '</strong>  has signed and verified the correctness of pleadings and uploaded documents  on behalf of <strong>' . strtoupper($litigant_name) . '</strong> .<br>'
                . '<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' .
                'I undertake to submit signed copies of pleadings and original / certified copies of documents physically before the court within time limit prescribed  by Rules / Notification issued by High Court further, hence verified on this <strong>' . date('d') . '</strong> Day of  <strong>' . date('M Y') . '.</strong>';
        } else {
            $litigant_name = $result[0]['party_name'];
            $litigant_details = 'I have explained him the contents in the pleadings. I have seen documents uploaded in support of the pleadings. '
                . '<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                . 'Litigant <strong>' . strtoupper($result[0]['pet_name']) . '</strong>  has signed and verified the correctness of pleadings and uploaded documents in my presence';
        }
        $oath_part_pet_tbl1 = '<table cellspacing="0" cellpadding="1" style="font-size:13px"><tbody>';
        $oath_title1 = '<tr><td  colspan="2" style="text-align:center"><strong> Identification and Verification</strong></td></tr>';
        $oath_part_head = '<tr><td style="text-align:left"><strong>Efiling Number : ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . '</strong></td>
            <td style="text-align:right"><strong>Litigant Name : ' . strtoupper($litigant_name) . '</strong></td></tr>';
        $oath_part_pet1 = '<tr  colspan="2"><td></td></tr>';
        $oath_part_pet2 = '<tr><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
            . 'The above named litigant <strong>' . strtoupper($litigant_name) . '</strong>  has filed his plaint/ complaint/ petition/ Appeal/ Application through me. ' . $litigant_details . ',</td></tr><br>';
        $oath_part_pet3 = '<tr><td colspan="2" style="text-align:right" ><strong>' . strtoupper($_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name']) . '</strong> </td></tr>';
        $oath_part_pet4 = '<tr><td colspan="2" style="text-align:right" >Bar Reg. No. <strong>' . strtoupper($_SESSION['login']['bar_reg_no']) . '</strong> </td></tr>';
        $oath_part_pet_tbl2 = '</tbody></table><br>';
        $oath_part11 = '<tr><td colspan="2" style="text-align:justify"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; I undertake to submit signed copies of pleadings and original / certified copies of documents physically before the court within time limit prescribed  by Rules / Notification issued by High Court further, hence verified on this <strong>' . date('d') . '</strong> Day of  <strong>' . date('M Y') . '.</strong><br><br></td></tr>';
        $oath_tbl_adv = $oath_part_pet_tbl1 . $oath_part_head . $oath_title1 . $oath_part_pet1 . $oath_part_pet2 . $oath_part_pet3 . $oath_part_pet4 . $up_doc_hash . $oath_part_pet_tbl2 . $oath_part11;
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
        if (!empty($adv_mobile) && !empty($adv_name)) {
            $adv_name = strtoupper($adv_name);
            $mob_last_no = str_repeat("*", strlen($adv_mobile) - 2) . substr($adv_mobile, -2) . '.';
            $temp_table_4 = '<br><br><p style="text-align:center;"><b>eVerified By : </b>' . $adv_name . ' <b>  On Date : </b>' . date("d-m-Y H:i:s A", strtotime(htmlentities(date('Y-m-d H:i:s'), ENT_QUOTES))) . '<b>  Using Mobile :</b> ' . htmlentities($mob_last_no, ENT_QUOTES) . '</p>';
        } else {
            $temp_table_4 = '';
        }
        //----------------------End Uploaded receipts--------------------//
        ob_start();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(FALSE);
        $pdf->SetPrintFooter(TRUE);
        $pdf->SetAuthor('eCommittee, SCI');
        $pdf->SetTitle('eCommittee, SCI');
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
            $pdf->writeHTML($oath_tbl_adv . $temp_table_4, true, false, false, false, '');
        } else {
            $pdf->writeHTML($oath_tbl_adv . $temp_table_3 . $temp_table_4, true, false, false, false, '');
        }
        $pdf->lastPage();
        $est_code = $_SESSION['estab_details']['estab_code'];
        $signed_pdf_path = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/signed_pdfs/';
        if (!is_dir($signed_pdf_path)) {
            $uold = umask(0);
            if (mkdir('uploaded_docs/' . $est_code . '/' . $efiling_num . '/signed_pdfs/', 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($signed_pdf_path . '/index.html', $html);
            }
            umask($uold);
        }
        $pdf->Output($signed_pdf_path . $efiling_num . '_advocate_otp_everified.pdf', 'F');
        unset($_SESSION['eVerified_mobile_otp']['ADV_MOB_OTP_VERIFY']);
        unset($_SESSION['eVerified_mobile_otp']['ADV_MOBILE']);
        unset($_SESSION['eVerified_mobile_otp']['ADV_NAME']);
    }

}