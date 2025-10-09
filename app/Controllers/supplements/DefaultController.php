<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DefaultController extends CI_Controller
{


    public function __construct()
    {

        parent::__construct();

        $this->load->model('supplements/supplement_model');
        $this->load->model('appearing_for/Appearing_for_model');
        $this->load->model('supplements/Listing_proforma_model');
        $this->load->library('webservices/efiling_webservices');
        $this->load->library('TCPDF');
        $this->load->helper('file');
        $this->load->library('slice');
        $this->load->helper('functions');
    }


    public function pdfDocHome(){
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $radio_flag=0;
        if(is_null($registration_id))
            echo '<script type="text/javascript"> window.top.location.href = "' . base_url() . 'case/ancillary/form" </script>';
        $checklist_action = '';
        $checklist_action_type = '';
        $checklist_pdf_preview = '';
        $checklist_action_cancel = '';
        $filled_data = $this->supplement_model->get_data($registration_id);
        if (is_null($filled_data)) {
            $checklist_action = base_url() . 'case/ancillary/checklist';
            $checklist_action_type = 'html';
        } elseif ($filled_data['is_signed'] == 'f') {
            $checklist_action = base_url() . 'case/ancillary/checklist';
            $checklist_action_type = 'sign';
            $checklist_pdf_preview = base_url() . 'supplements/DefaultController/generate_checklist_pdf';

        } else {
            $checklist_action = base_url() . 'supplements/DefaultController/generate_checklist_pdf';
            $checklist_action_type = 'pdf';
            $checklist_action_cancel = base_url() . 'supplements/DefaultController/doc_cancel_checklist';
        }


        //CERTIFICATE IN SLP
        $slp_cert_sign_status = $this->get_sign_status(DOC_TYPE_SLP_CERT);
        $slp_cert_pdf_preview = '';
        if ($slp_cert_sign_status == 'A') {
            $radio_flag = 1;
            $slp_cert_action_type = 'sign';
            $slp_cert_action = base_url() . 'case/ancillary/checklist';
            $slp_cert_pdf_preview = base_url() . 'supplements/DefaultController/generate_slp_certificate_pdf/';
        } elseif ($slp_cert_sign_status == 'V') {
            $radio_flag = 0;
            $slp_cert_action_type = 'pdf';
            $slp_cert_action = base_url() . 'supplements/DefaultController/generate_slp_certificate_pdf';
        }

        //XXXXXXXXXXXXXXXXXXXX Listing Proforma XXXXXXXXXXXXXX
        $proforma_action = '';
        $proforma_action_type = '';
        $proforma_pdf_preview = '';
        $proforma_action_cancel = '';
        $filled_data_proforma = $this->Listing_proforma_model->get_pdf_data_check($registration_id);
        //print_r($filled_data_proforma); exit();

        if (empty($filled_data_proforma[0])) {
            //echo "1"; exit();
            $proforma_action = base_url() . 'supplements/listing_proforma_controller';
            $proforma_action_type = 'html';

        } elseif ($filled_data_proforma[0]['is_signed'] == 'f') {
            //echo "2"; exit();
            $proforma_action = base_url() . 'supplements/listing_proforma_controller/gen_listingProforma_editPdf';
            $proforma_action_type = 'sign';
            $proforma_pdf_preview = base_url() . 'supplements/listing_proforma_controller/gen_listingProforma_pdf';
        } else {
            //echo "3"; exit();
            $proforma_action = base_url() . 'supplements/listing_proforma_controller/gen_listingProforma_pdf';
            $proforma_action_type = 'pdf';
            $proforma_action_cancel = base_url() . 'supplements/listing_proforma_controller/doc_cancel_listingProforma';
        }
        //XXXXXXXXXXXXXXXXXXXX END  XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

        //AFFIDAVITS for SLP, TP, RP
        $affidavits_action = '';
        $affidavits_action_type = '';
        $affidavits_pdf_preview = '';
        if ($_SESSION['efiling_details']['database_type']=='E') {
            $case_detail = $this->supplement_model->table_data('efil.tbl_case_details', array('registration_id' => $registration_id));
            $sc_case_type_id = $case_detail[0]['sc_case_type_id'];
        }else{
            $sc_case_type_id=$_SESSION['efiling_details']['sc_case_type_id'];
        }
        $affidavits_filled_data = $this->supplement_model->get_affidavits_filled_data($registration_id, $sc_case_type_id);
       //echo '<pre>';print_r($affidavits_filled_data);exit();
        if (is_null($affidavits_filled_data[0])) {
            $affidavits_action = base_url() . 'supplements/DefaultController/affidavits';
            $affidavits_action_type = 'html';
        } elseif ($affidavits_filled_data[0]['is_deleted'] == 'f' && $affidavits_filled_data[0]['is_signed'] == 'f') {
            $affidavits_action = base_url() . 'supplements/DefaultController/affidavits';
            $affidavits_action_type = 'esign';
            $affidavits_pdf_preview = base_url() . 'supplements/DefaultController/generate_affidavit_pdf';
        } else {
            $affidavits_action = base_url() . 'supplements/DefaultController/generate_affidavit_pdf';
            $affidavits_action_type = 'pdf';
        }

        $data['pdfs'] = array(
            array('doc_name' => 'Advocate Checklist', 'action' => $checklist_action, 'type' => $checklist_action_type, 'doc_type' => DOC_TYPE_CHECKLIST, 'doc_preview' => $checklist_pdf_preview, 'radio_flag'=>0 , 'doc_cancel' => $checklist_action_cancel),
            array('doc_name' => 'Affidavit', 'action' => $affidavits_action, 'type' => $affidavits_action_type, 'doc_type' => DOC_TYPE_AFFIDAVIT, 'doc_preview' => $affidavits_pdf_preview, 'radio_flag'=>0),
            array('doc_name' => 'Certificate in SLP', 'action' => $slp_cert_action, 'type' => $slp_cert_action_type, 'doc_type' => DOC_TYPE_SLP_CERT, 'doc_preview' => $slp_cert_pdf_preview, 'radio_flag'=>$radio_flag ),
            array('doc_name' => 'Filing Index/Memo', 'action' => base_url() . 'supplements/DefaultController/generate_filingIndex_pdf', 'type' => 'pdf', 'radio_flag'=>0),
            array('doc_name' => 'Memo of Appearance', 'action' => base_url() . 'supplements/DefaultController/generate_memo_of_appearance_pdf', 'type' => 'pdf', 'radio_flag'=>0),
            array('doc_name' => 'Index of ROP', 'action' => base_url() . 'assets/downloads/record_of_proceedings.pdf', 'type' => 'pdf', 'radio_flag'=>0),
            array('doc_name' => 'Refiling Declaration', 'action' => base_url() . 'supplements/DefaultController/generate_refiling_declaration_pdf', 'type' => 'pdf', 'radio_flag'=>0),
            array('doc_name' => 'Listing Proforma', 'action' => $proforma_action, 'type' => $proforma_action_type, 'doc_type' => DOC_TYPE_PROFORMA, 'doc_preview' => $proforma_pdf_preview, 'radio_flag'=>0 , 'doc_cancel' => $proforma_action_cancel),

        );

        $this->load->view('templates/header');
        $this->load->view('supplements/pdf_home/pdf_list', $data);
        //$this->slice->view('supplements.pdf_home.pdf_list',$data);
        $this->load->view('templates/footer');

    }

    public function send_otp(){
        send_otp('signing document', $_POST['doc_type'], 1, 1);
        echo 1;
    }

    public function verifyOtp(){
        if (validate_otp($_POST['doc_type'], $_POST['otp'])) {

            if ($_POST['doc_type'] == DOC_TYPE_CHECKLIST) {
                echo $this->supplement_model->update_data('efil.tbl_check_list', array('is_signed' => 't'), array('registration_id' => $_SESSION['efiling_details']['registration_id']));
            } elseif ($_POST['doc_type'] == DOC_TYPE_PROFORMA) {
                echo $this->Listing_proforma_model->update_data_signaftr('efil.tbl_listing_proforma_pdf_gen', array('is_signed' => '1'), array('registration_id' => $_SESSION['efiling_details']['registration_id']));
            }
        }
    }

    function get_sign_status($doc_type){
        $sign_otp = $this->supplement_model->table_data('efil.tbl_sms_log', array('type_id' => $doc_type, 'tbl_efiling_num_id' => $_SESSION['efiling_details']['registration_id']));
        $validate_status = 'A';
        foreach ($sign_otp as $x) {
            if ($x['validate_status'] == 'A') {
                $validate_status = 'A';
                break;
            }
            elseif ($x['validate_status'] == 'V')
                $validate_status = 'V';
        }
        return $validate_status;
    }


    public function checklist()
    {
        if (isset($_POST['checklist_save'], $_SESSION['efiling_details']['registration_id'])) {
            $check_points = $_POST;
            unset($check_points['checklist_save']);
            $check_points['registration_id'] = $_SESSION['efiling_details']['registration_id'];
            $result = $this->supplement_model->upsert($check_points);
            if ($result == 1)
                $this->session->set_flashdata('msg', 'Data saved successfully');
            elseif ($result == 'signed')
                $this->session->set_flashdata('msg_error', 'Cannot modify data as it has already been signed');
            elseif ($result == 'duplicate_key')
                $this->session->set_flashdata('msg_error', 'Data already exist');
            echo '<script type="text/javascript"> window.top.location.href = "' . base_url() . 'case/ancillary/documents" </script>';
        }
        $data['filled_data'] = $this->supplement_model->get_data($_SESSION['efiling_details']['registration_id']);
        $data['form_data'] = $this->supplement_model->table_data('efil.m_checklist');
        $this->load->view('templates/header');
        $this->load->view('supplements/check_list/checklist', $data);
        $this->load->view('templates/footer');
    }


    function generate_checklist_pdf()
    {
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

        $pdf->SetFont('helvetica', '', 10, '', true);
        $pdf->AddPage();
        ob_end_clean();

        // create some HTML content
        $html = '<h3 style="text-align:center">ADVOCATES CHECK LIST</h3><p style="text-align:center">(To be certified by Advocate-on-Record)</p>
                <br/>
                <table>';
        $filled_data = $this->supplement_model->get_data($_SESSION['efiling_details']['registration_id']);
        $form_data = $this->supplement_model->table_data('efil.m_checklist');
        foreach ($form_data as $q) {
            $ans = ($filled_data[$q['field_name']] == 'Y') ? 'Yes' : (($filled_data[$q['field_name']] == 'N') ? 'No' : 'N.A.');
            if ($q['question_no'] == '16.a')
                $html .= '<br/><br/>  <tr>
                        <td width="10%" style="font-size: 15px;"><b>16.</b></td>
                        <td width="75%" style="font-size: 15px;">In a petition (PIL) under clause (d) of Rule 12(1) Order XXXVIII, the petition has disclosed</td>
                        <td width="15%" style="font-size: 15px; color: #0d47a1; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>';
            $html .= '<br/><br/>  <tr>
                        <td width="10%" style="font-size: 15px;"><b>' . $q['question_no'] . '.</b></td>
                        <td width="75%" style="font-size: 15px;">' . $q['question'] . '</td>
                        <td width="15%" style="font-size: 15px; color: #0d47a1; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $ans . '</td>
                    </tr>';
        }
        $html .= '<br/><br/><br/><br/>  <tr>
                        <td width="10%" style="font-size: 15px;"></td>
                        <td width="75%" style="font-size: 15px;"><b><p align="justify">I hereby declare that I have personally verified the petition and its contents and it is in conformity with the Supreme Court Rules, 2013. I certify that the above the above requirements of this checklist have been complied with. I further certify that all the documents necessary for the purpose of hearing of the matter have been filed.</p></b></td>
                        <td width="15%" style="font-size: 15px; color: #0d47a1; "></td>
                    </tr>';
        $html .= '</table><br/><br/><br/>';

        $html .= '<tr><td width="60%"></td><td width="30%">AOR NAME: ' . $_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'] . '</td></tr>';
        $html .= '<tr><td width="60%"></td><td width="30%">AOR CODE: ' . $_SESSION['login']['aor_code'] . '</td></tr>';
        $html .= '<tr><td width="60%"></td><td width="30%">CONTACT NO: ' . $_SESSION['login']['mobile_number'] . '</td></tr>';
        $html .= '<tr><td>NEW DELHI</td></tr>';
        $html .= '<tr><td>DATE: ' . date('d-m-Y') . '</td></tr>';

// output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        if ($filled_data['is_signed'] == 't') {
            $pdf->SetY(-30);
            $pdf->Write(0, "This contents of this PDF is signed by using OTP", '', 0, 'C', true, 0, false, false, 0);
        }
        else {
            // Get the page width/height
            $myPageWidth = $pdf->getPageWidth();
            $myPageHeight = $pdf->getPageHeight();

            // Find the middle of the page and adjust.
            $myX = ($myPageWidth / 2) - 75;
            $myY = ($myPageHeight / 2) + 25;

            // Set the transparency of the text to really light
            $pdf->SetAlpha(0.09);

            // Rotate 45 degrees and write the watermarking text
            $pdf->StartTransform();
            $pdf->Rotate(45, $myX, $myY);
            $pdf->SetFont("helvetica", "", 80);
            $pdf->Text($myX, $myY, "PREVIEW");
            $pdf->StopTransform();

            // Reset the transparency to default
            $pdf->SetAlpha(1);
        }

        $efiling_num = $_SESSION['efiling_details']['efiling_no'];
        $est_code = $_SESSION['estab_details']['estab_code'];
        $unsigned_pdf_path = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/unsigned_pdf/';
        /*if (!is_dir($unsigned_pdf_path)) {
            $uold = umask(0);
            if (mkdir('uploaded_docs/' . $est_code . '/' . $efiling_num . '/unsigned_pdf/', 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($unsigned_pdf_path . '/index.html', $html);
            }
            umask($uold);
        }*/
        $pdf->Output($unsigned_pdf_path . $efiling_num . '_Advocate_UnsignedCheckList.pdf', 'I');
    }


    function generate_filingIndex_pdf(){
        ob_start();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetPrintHeader(FALSE);
        $pdf->SetPrintFooter(FALSE);

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

        $pdf->SetFont('helvetica', '', 12, '', true);
        $pdf->AddPage();
        ob_end_clean();

        // create some HTML content
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $parties = $this->supplement_model->get_signers_list($registration_id);
        $case_details = $this->supplement_model->case_details($registration_id);
        
        $html = '<h3 style="text-align:center">IN THE SUPREME COURT OF INDIA</h3>
                 <h3 style="text-align:center">CIVIL APPELLATE JURISDICTION</h3>
                 <h3 style="text-align:center;font-weight: normal">'.$case_details[0]['casename'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; OF ' . date('Y') . '</h3>
                 <br/><br/><br/>
                 <h3 style="text-decoration: underline;">IN THE MATTER OF :--</h3>
                <br/>
                <table>
                <tr><td width="80%">' . $parties[0]['party_name'] . '</td> <td width="20%">(PETITIONER)</td> </tr><br/>
                <tr><td>VERSES</td></tr><br/>
                <tr><td width="80%">' . $parties[1]['party_name'] . '</td> <td width="20%">(RESPONDENT)</td> </tr>
                </table>
                <h3 style="text-align:center;text-decoration: underline;">FILING MEMO/INDEX</h3>
                <table border="1">
                <tr style="font-size: 13px; font-weight: bold;text-align: center;">
                        <td width="10%">S.No.</td>
                        <td width="50%">PARTICULARS OF DOCUMENTS FILED</td>
                        <td width="20%">COPIES</td>
                        <td width="20%">COURT FEES PAID</td>
                    </tr>';
        $indexed_doc_data = $this->supplement_model->table_data('efil.tbl_efiled_docs', array('registration_id' => $registration_id));
        $sno = 0;

        foreach ($indexed_doc_data as $q) {
            $sno++;
            $html .= '
                    <tr style="text-align: center">
                        <td width="10%">' . $sno . '</td>
                        <td width="50%" style="text-align: left">' . $q['doc_title'] . '</td>
                        <td width="20%" >' . $q['no_of_copies'] . '</td>
                        <td width="20%"></td>
                    </tr>';
        }
        $html .= '</table><br/><br/><br/>';

        $html .= '<tr><td width="60%"></td><td width="30%">AOR NAME: ' . $_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'] . '</td></tr>';
        $html .= '<tr><td width="60%"></td><td width="30%">AOR CODE: ' . $_SESSION['login']['aor_code'] . '</td></tr>';
        $html .= '<tr><td width="60%"></td><td width="30%">CONTACT NO: ' . $_SESSION['login']['mobile_number'] . '</td></tr>';
        $html .= '<tr><td>NEW DELHI</td></tr>';
        $html .= '<tr><td>DATE: ' . date('d-m-Y') . '</td></tr>';

// output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        $efiling_num = $_SESSION['efiling_details']['efiling_no'];
        $est_code = $_SESSION['estab_details']['estab_code'];
        $unsigned_pdf_path = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/unsigned_pdf/';
        $pdf->Output($unsigned_pdf_path . $efiling_num . '_Advocate_UnsignedCheckList.pdf', 'I');
    }


    function generate_slp_certificate_pdf($type=0){
        ob_start();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetPrintHeader(FALSE);
        $pdf->SetPrintFooter(FALSE);

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

        $pdf->SetFont('helvetica', '', 12, '', true);
        $pdf->AddPage();
        ob_end_clean();

        // create some HTML content
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $parties = $this->supplement_model->get_signers_list($registration_id);
        $facts = ($type==1)? 'No additional facts, documents or grounds
    have been taken therein or relied upon· in the Special Leave Petition.' : '';
        $html = '<h3 style="text-align:center">IN THE SUPREME COURT OF INDIA</h3>
                     <h3 style="text-align:center">CIVIL APPELLATE JURISDICTION</h3>
                     <h3 style="text-align:center;font-weight: normal">SPECIAL LEAVE PETITION (C) NO.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; OF ' . date('Y') . '</h3>
                     <br/><br/><br/>
                     <h3 style="text-decoration: underline;">IN THE MATTER OF :--</h3>
                    <br/>
                    <table>
                    <tr><td width="80%">' . $parties[0]['party_name'] . '</td> <td width="20%">(PETITIONER)</td> </tr><br/>
                    <tr><td>VERSES</td></tr><br/>
                    <tr><td width="80%">' . $parties[1]['party_name'] . '</td> <td width="20%">(RESPONDENT)</td> </tr>
                    </table>
                    <h3 style="text-align:center;">CERTIFICATE</h3>';
        $html .= '<p align="justify"  style="line-height: 2.2;">“Certified that the Special Leave Petition is confined only to the pleadings
    before the Court/Tribunal whose order is challenged and the other documents
    relied upon in those proceedings. '.$facts.' It is
    further certified that the copies of the annexures/documents attached to the
    Special Leave Petition is necessary to answer the question of law raised in
    the petition or to make out grounds urged in the Special Leave Petition for
    consideration of this Hon\'ble court. This Certificate is given on the basis of the
    instructions given by the Petitioner/person authorized by the Petitioner whose
    affidavit is filed in support of the S.L.P.”</p>';

        $html .= '<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td width="80%"></td><td width="20%">FILED BY:</td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>';
        $html .= '<tr><td width="70%"></td><td width="30%">' . $_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'] . '</td></tr>';
        $html .= '<tr><td>FILED ON: ' . date('d-m-Y') . '</td></tr>';

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');


        $sms_status = $this->get_sign_status(DOC_TYPE_SLP_CERT);
        if ($sms_status == 'A') {
            // Get the page width/height
            $myPageWidth = $pdf->getPageWidth();
            $myPageHeight = $pdf->getPageHeight();

            // Find the middle of the page and adjust.
            $myX = ($myPageWidth / 2) - 75;
            $myY = ($myPageHeight / 2) + 25;

            // Set the transparency of the text to really light
            $pdf->SetAlpha(0.09);

            // Rotate 45 degrees and write the watermarking text
            $pdf->StartTransform();
            $pdf->Rotate(45, $myX, $myY);
            $pdf->SetFont("helvetica", "", 80);
            $pdf->Text($myX, $myY, "PREVIEW");
            $pdf->StopTransform();

            // Reset the transparency to default
            $pdf->SetAlpha(1);
        }


        $efiling_num = $_SESSION['efiling_details']['efiling_no'];
        $est_code = $_SESSION['estab_details']['estab_code'];
        $unsigned_pdf_path = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/unsigned_pdf/';
        $pdf->Output($unsigned_pdf_path . $efiling_num . '_Advocate_UnsignedCheckList.pdf', 'I');
    }


    function generate_memo_of_appearance_pdf(){
        
        ob_start();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetPrintHeader(FALSE);
        $pdf->SetPrintFooter(FALSE);

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

        $pdf->SetFont('helvetica', '', 12, '', true);
        $pdf->AddPage();
        ob_end_clean();

        // create some HTML content
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $parties = $this->supplement_model->get_signers_list($registration_id);
        $case_details = $this->supplement_model->case_details($registration_id);
        $html = '<h3 style="text-align:center">IN THE SUPREME COURT OF INDIA</h3>
                     <h3 style="text-align:center">CIVIL APPELLATE JURISDICTION</h3>
                     <h3 style="text-align:center;font-weight: normal">'.$case_details[0]['casename'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; OF ' . date('Y') . '</h3>
                     <br/><br/><br/>
                     <h3 style="text-decoration: underline;">IN THE MATTER OF :--</h3>
                    <br/>
                    <table>
                    <tr><td width="80%">' . $parties[0]['party_name'] . '</td> <td width="20%">(PETITIONER)</td> </tr><br/>
                    <tr><td>VERSES</td></tr><br/>
                    <tr><td width="80%">' . $parties[1]['party_name'] . '</td> <td width="20%">(RESPONDENT)</td> </tr>
                    </table>
                    <h3 style="text-align:center;text-decoration: underline;">MEMO OF APPEARANCE</h3>';
        $html .= '<p align="justify"  style="line-height: 1.2;">TO,<br>THE REGISTRAR<br>SUPREME COURT OF INDIA<br>NEW DELHI<br/><br/>SIR,<br/></p>';
        $html .= '<p align="justify"  style="line-height: 2.0;">PLEASE ENTER MY APPEARANCE FOR THE ABOVE NAMED
PETITIONER/APPELLANT IN PERSON IN THE ABOVE MENTIONED
MATTER.</p>';

        $html .= '<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td width="70%"></td><td width="30%">YOURS FAITHFULLY</td></tr>';
        $html .= '<tr><td>DATED: ' . date('d-m-Y') . '</td></tr><tr><td></td></tr><tr><td></td></tr>';
        $html .= '<tr><td width="60%"></td><td width="30%">NAME: ' . $_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'] . '</td></tr>';
        $usertype = ($_SESSION['login']['ref_m_usertype_id']==1)?'ADVOCATE ON RECORD':(($_SESSION['login']['ref_m_usertype_id']==2)?'PETITIONER-IN-PERSON':'');
        $html .= '<tr><td width="60%"></td><td width="30%">'.$usertype.'</td></tr>';
        $html .= '<tr><td width="60%"></td><td width="30%">MOB NO: ' . $_SESSION['login']['mobile_number'] . '</td></tr>';
        $html .= '<tr><td width="60%"></td><td width="30%">E-MAIL ID: ' . $_SESSION['login']['emailid'] . '</td></tr>';

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        $efiling_num = $_SESSION['efiling_details']['efiling_no'];
        $est_code = $_SESSION['estab_details']['estab_code'];
        $unsigned_pdf_path = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/unsigned_pdf/';
        $pdf->Output($unsigned_pdf_path . $efiling_num . '_Advocate_UnsignedCheckList.pdf', 'I');
    }


    function generate_refiling_declaration_pdf(){
        ob_start();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetPrintHeader(FALSE);
        $pdf->SetPrintFooter(FALSE);

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

        $pdf->SetFont('helvetica', '', 12, '', true);
        $pdf->AddPage();
        ob_end_clean();

        // create some HTML content
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $parties = $this->supplement_model->get_signers_list($registration_id);
        $html = '<br/><br/><h3 style="text-align:center">DIARY NO :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OF ' . date('Y') . '</h3>
                 <br/><br/><br/>
                 <h3 style="text-align:center">DECLARATION</h3>
                <br/>';
        $html .= '<p align="justify"  style="line-height: 2.2;">All the defects have been duly cured. Whatever has been
added/deleted/modified in the petition is the result of curing of the
defects and nothing else. Except curing the defects, nothing has
been done. Paper-books are complete in all respects.</p>';
        $html .= "<table><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>";
        $html .= '<tr><td width="60%" height="50px;"></td><td width="30%"><b>Petitioner-in-person:</b> ' . $_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'] . '</td></tr>';
        $html .= '<tr><td width="60%" height="30px;"></td><td width="30%"><b>Date:</b> ' . date('d-m-Y') . '</td></tr>';
        $html .= '<tr><td width="60%" height="30px;"></td><td width="30%"><b>Contact No:</b> ' . $_SESSION['login']['mobile_number'] . '</td></tr>';
        $html .= '<tr><td width="60%" height="30px;"></td><td width="30%"><b>Email ID:</b> ' . $_SESSION['login']['emailid'] . '</td></tr>';
        $html .= '</table>';

// output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        $efiling_num = $_SESSION['efiling_details']['efiling_no'];
        $est_code = $_SESSION['estab_details']['estab_code'];
        $unsigned_pdf_path = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/unsigned_pdf/';
        $pdf->Output($unsigned_pdf_path . $efiling_num . '_Advocate_UnsignedCheckList.pdf', 'I');
    }

    //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX Listing proforma XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    public function index()
    {
        unset($_SESSION['efiling_details']);

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('adminDashboard');
            exit(0);
        }
        $this->load->model('newcase/Dropdown_list_model');
        $data['sc_case_type'] = $this->Dropdown_list_model->get_sci_case_type();
        $this->load->view('templates/header');
        $this->load->view('supplements/pdf_case_search_view',$data);
        $this->load->view('templates/footer');

    }//end of function index()..

    public function Pdf_search_case_details()
    {//echo "Rounak Mishra";exit();

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }

        //$diary_type = $_POST['search_filing_type'];
        $search_type=trim($_POST['search_filing_type']);
        $diary_no=trim($_POST['diaryno']);
        $diary_year=trim($_POST['diary_year']);
        $efiling_no=trim(str_replace("-", "", $_POST['efiling_no']));

        $case_type_id=url_decryption(trim($_POST['sc_case_type']));
        $case_number=trim($_POST['case_number']);
        $case_year=trim($_POST['case_year']);

        /*if (!(isset($_POST['is_direct_access']) && $_POST['is_direct_access'])) {
            $this->form_validation->set_rules('captchadata', 'Catcha', 'required|trim|validate_encrypted_value');
        }*/
        $this->form_validation->set_rules('search_filing_type', 'case type', 'required|trim|in_list[diary,register]');

        if ($search_type == 'diary') {
            $this->form_validation->set_rules('diaryno', 'Diary Number', 'required|trim|numeric|is_required');
            $this->form_validation->set_rules('diary_year', 'Diary Year', 'required|trim|numeric|is_required');
        } elseif ($search_type == 'register') {
            $this->form_validation->set_rules('sc_case_type', 'Case Type', 'required|trim|is_required|encrypt_check');
            $this->form_validation->set_rules('case_number', 'Case Number', 'required|trim|numeric|is_required');
            $this->form_validation->set_rules('case_year', 'Case Year', 'required|trim|numeric|is_required');
        } elseif ($search_type == 'efilingNO') {
            $this->form_validation->set_rules('efiling_no', 'E-Filing Number', 'required|trim|is_required');
        }

        $this->form_validation->set_error_delimiters('<br/>', '');

        if ($search_type == 'diary') {
            $response_type='Diary Number :'.$diary_no . '/'.$diary_year;

            //CHECK EXISTING DATABASE
            $web_service_result['efile_no_dtls'] = $this->supplement_model->get_efiling_num_basic_Details_pdf($search_type,$efiling_no,$diary_no,$diary_year,$case_number,$case_year,$case_type_id,$response_type);
            if (empty($web_service_result['efile_no_dtls']) && $web_service_result['efile_no_dtls']==false) {
                $web_service_result['efile_no_dtls'] = [];
                //CHECK EXISTING API ICMIS
                $web_service_result['efile_no_dtls'] = $this->supplement_model->search_case_or_parties_by_diaryNo($diary_no, $diary_year,$response_type);
            }
        } elseif ($search_type == 'register') {

             //CHECK EXISTING DATABASE
            $response_type='Case Number/Year :'.$case_number . '/'.$case_year;
            $web_service_result['efile_no_dtls'] = $this->supplement_model->get_efiling_num_basic_Details_pdf($search_type,$efiling_no,$diary_no,$diary_year,$case_number,$case_year,$case_type_id,$response_type);
            if (empty($web_service_result['efile_no_dtls']) && $web_service_result['efile_no_dtls']==false) {
                $web_service_result['efile_no_dtls'] = [];
                //CHECK EXISTING API ICMIS
                $web_service_result_api=$this->efiling_webservices->get_case_details_from_SCIS(escape_data($case_type_id), escape_data($case_number), escape_data($case_year));
               if (!empty($web_service_result_api->case_details[0])) {
                    $case_data = $web_service_result_api->case_details[0];
                    $diary_no = $case_data->diary_no;
                    $diary_year = $case_data->diary_year;
                    //CHECK EXISTING API ICMIS
                    $web_service_result['efile_no_dtls'] = $this->supplement_model->search_case_or_parties_by_diaryNo($diary_no, $diary_year,$response_type);
                }
            }

        } elseif ($search_type == 'efilingNO') {
            $response_type='E-Filing Number :'.$efiling_no;
            //CHECK EXISTING DATABASE
            $web_service_result['efile_no_dtls'] = $this->supplement_model->get_efiling_num_basic_Details_pdf($search_type,$efiling_no,$diary_no,$diary_year,$case_number,$case_year,$case_type_id,$response_type);


        }
        if (!empty($web_service_result['efile_no_dtls']) && $web_service_result['efile_no_dtls']!=null && !empty($_SESSION['efiling_details']['registration_id']) && $web_service_result['efile_no_dtls']!=false) {
               if ($search_type == 'diary') {

                  echo '1@@@';exit(0);

                } else if ($search_type == 'register') {

                    echo '2@@@';exit(0);

                } elseif ($search_type == 'efilingNO') {

                    echo '4@@@ sucessfully';exit(0);

               }else{ echo '3@@@ No Record found!';exit(0); }
        }else{
               echo '3@@@ No Record found!';exit(0);
        }

    }

/****************************************** ANSHU ****************************/
    function generate_affidavit_pdf(){
        $registration_id= $_SESSION['efiling_details']['registration_id'];
        if (empty($registration_id)){ redirect('dashboard'); }
        if ($_SESSION['efiling_details']['database_type']=='E') {
            $case_detail = $this->supplement_model->table_data('efil.tbl_case_details', array('registration_id' => $registration_id));
            $sc_case_type_id = $case_detail[0]['sc_case_type_id'];
        }else{
            $sc_case_type_id = $_SESSION['efiling_details']['sc_case_type_id'];
        }

        $deponent_type_check='check';
        $deponent_type_check_data= $this->supplement_model->get_supplements($registration_id,$sc_case_type_id,$deponent_type_check,'pdf');
        $deponent_type=$deponent_type_check_data[0]['deponent_type'];

        if (empty($sc_case_type_id)){echo "<script>alert('$sc_case_type_id Case type is required!'); window.location.href='" . base_url() . "dashboard';</script>";}
        if (empty($deponent_type)){echo "<script>alert('$deponent_type Deponet type is required!'); window.location.href='" . base_url() . "dashboard';</script>";}
        ob_start();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetPrintHeader(FALSE);
        $pdf->SetPrintFooter(FALSE);

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

        $pdf->SetFont('helvetica', '', 12, '', true);
        $pdf->AddPage();
        ob_end_clean();

        // create some HTML content
        $affidavit_template= $this->supplement_model->get_supplements($registration_id,$sc_case_type_id,$deponent_type,'pdf');
        // echo '<pre>';print_r($affidavit_template);exit();
        /*if (empty($affidavit_template)){
            echo "<script>alert('$registration_id Registration number: is not complete for pdf please try again!');
               window.location.href='" . base_url() . "dashboard';</script>";
        }*/
        if (!empty($affidavit_template) && count($affidavit_template) > 0 && $affidavit_template[0]['sc_case_type_id']==13 || $affidavit_template[0]['sc_case_type_id']==14) {
            $parties = get_affidavit_pdf_details($affidavit_template[0]['sc_case_type_id']);
        }else{
            if ($_SESSION['efiling_details']['database_type']=='E') {
                $parties = $this->supplement_model->get_signers_list($registration_id);
                $parties = array([
                    'petitioner_name' => $parties[0]['party_name'],
                    'respondent_name' => $parties[1]['party_name'],
                ]);
            }else{
                $parties = array([
                    'petitioner_name' => $_SESSION['efiling_details']['pet_name'],
                    'respondent_name' => $_SESSION['efiling_details']['res_name'],
                ]);
            }
        }
        //echo '<pre>';print_r($parties);exit();
        $text = explode(PHP_EOL, $affidavit_template[0]['deponent']);
        foreach ($text as $line){
            $sentences = explode('$%', $line);
            $htmldeponent = '';
            foreach ($sentences as $x){
                $y = explode( '%$', $x);
                if(sizeof($y)==2){
                    if($y[0]=='relation'){
                        $htmldeponent .= '<span style="text-align:center;">&nbsp;&nbsp;' . $affidavit_template[0][$y[0]] . '</span>' . $y[1];
                    }else {
                        $htmldeponent .= '<b style="text-decoration: underline;text-align:center;">&nbsp;&nbsp;&nbsp;' . $affidavit_template[0][$y[0]] . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>' . $y[1];
                    }
                }
                else{
                    $htmldeponent.=$y[0].' '.$y[1];
                }
                //echo '<br/>';
            }
            //echo "<p>$html</p>";
        }

        $text = explode(PHP_EOL, $affidavit_template[0]['description']);
        foreach ($text as $line){
            $sentences = explode('$%', $line);
            $htmldescription = '';
            foreach ($sentences as $x){
                $y = explode( '%$', $x);
                if(sizeof($y)==2){
                    $htmldescription.='<span style="text-decoration: underline;">'.$affidavit_template[0][$y[0]].'</span> '.$y[1];
                }
                else{
                    $htmldescription.=$y[0];
                }
                //echo '<br/>';
            }
            $htmldes.= '<p>'.$htmldescription.'</p>';
        }
        //$htmldes;
        $casename_header='';
        if (!empty($affidavit_template[0]['case_year'])){
            $case_year=$affidavit_template[0]['case_year'];
        }else{ $case_year='20______';}
        if ($affidavit_template[0]['nature']=='C'){$nature='CIVIL';}else{$nature='CRIMINAL';}

        if(!empty($affidavit_template[0]['header'])){
            $casename_header='<h5 style="text-align:center;font-weight: normal">'.$affidavit_template[0]['header'].'&nbsp;NO.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; OF &nbsp;&nbsp; 20______</h5>'.'<h5 style="text-align:center;">IN</h5>';
        }else{
            $casename_header='<h5 style="text-align:center;font-weight: normal">'.$affidavit_template[0]['casename'].'&nbsp;NO.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; OF &nbsp;&nbsp; 20______</h5>';
        }
        if (!empty($affidavit_template[0]['slp_name']) && $affidavit_template[0]['slp_name']!=null){
            $slp_name='<p style="text-align:center;font-weight: normal">'.$affidavit_template[0]['slp_name'].'&nbsp;NO.&nbsp;&nbsp;&nbsp;&nbsp;<span style="text-decoration: underline;width:10%;">'.$affidavit_template[0]['case_num'].'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OF &nbsp;&nbsp;<span style="text-decoration: underline;width:10%;">'.$case_year.'</span></p>';
        }
        $html = '<h3 style="text-align:center">IN THE SUPREME COURT OF INDIA</h3>
                     <h5 style="text-align:center">'.$nature.' APPELLATE JURISDICTION</h5>
                     '.$casename_header.'
                     '.$slp_name.'
                     <h5 style="text-decoration: underline;">IN THE MATTER OF:</h5>
                    <br/>
                    <table>
                    <tr><td width="80%">'.$affidavit_template[0]['petitioner_name'].'</td> <td width="20%">..Petitioner(s)</td> </tr><br/>
                    <tr><td>Versus</td></tr><br/>
                    <tr><td width="80%">'.$parties[0]['respondent_name'].'</td> <td width="20%">..Respondent(s)</td> </tr>
                    </table>
                    <h3 style="text-align:center;text-decoration: underline;">A F F I D A V I T</h3>';


        $html.=$htmldeponent;
        $html.=$htmldes;

        $html.='<br><br><br><br><br><br>';

        $html.='<tr><td width="85%"></td><td width="15%">DEPONENT</td></tr>';
        $html.='<p align="justify"  style="line-height: 1.2;text-decoration: underline;">VERIFICATION</p>';
        $html.='<p align="justify"  style="line-height: 2.0;">'.$affidavit_template[0]['verification'].'</p>';
        $html.='<tr><td>Verified at __________ on ___________ 20_______</td></tr>';
        $html.='<tr><td width="85%"></td><td width="15%">DEPONENT</td></tr>';
        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        $efiling_num = $_SESSION['efiling_details']['efiling_no'];
        $est_code = $_SESSION['estab_details']['estab_code'];
        $unsigned_pdf_path = 'uploaded_docs/supplement/' . $efiling_num . '/'.$sc_case_type_id.'/unsigned_pdf/';
        if (!is_dir($unsigned_pdf_path)) {
            $uold = umask(0);
            if (mkdir($unsigned_pdf_path, 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($unsigned_pdf_path . '/index.html', $html);
            }
            umask($uold);
        }
        $pdf->Output($unsigned_pdf_path . $efiling_num . '_Unsigned_Affidavit.pdf', 'I');
    }



    public function affidavits() {
        $data['affidavit_template']=array();

        $registration_id= $_SESSION['efiling_details']['registration_id'];
        if (empty($registration_id)){ redirect('case/ancillary/form'); }
        if ($_SESSION['efiling_details']['database_type']=='E') {
            $case_detail = $this->supplement_model->table_data('efil.tbl_case_details', array('registration_id' => $registration_id));
            $sc_case_type_id = $case_detail[0]['sc_case_type_id'];
        }else{
            $sc_case_type_id = $_SESSION['efiling_details']['sc_case_type_id'];
        }
        if (empty($sc_case_type_id)){echo "<script>alert('$sc_case_type_id Case type id is required!'); window.location.href='" . base_url() . "dashboard';</script>";}
        $deponent_type_check='check';
        $data['affidavit_list'] = $this->supplement_model->get_supplements($registration_id,$sc_case_type_id,$deponent_type_check);
        $data['affidavit_list'][0]['deponent_type'];
        //echo '<pre>'; print_r($data);exit();
        $this->load->view('templates/header');
        $this->load->view('supplements/affidavits/deponent_template',$data);
        $this->load->view('templates/footer');
    }
    public function affidavit_template() {
        $deponent_type=url_decryption($_POST['deponent_type']);
        if(empty($deponent_type)){
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Deponent Type is required!</div>');
            echo '<div class="alert alert-danger text-center">Deponent Type is required!</div>';
            exit();
        }
        $registration_id= $_SESSION['efiling_details']['registration_id'];
        if (empty($registration_id)){ redirect('case/ancillary/form'); }
        if ($_SESSION['efiling_details']['database_type']=='E') {
            $case_detail = $this->supplement_model->table_data('efil.tbl_case_details', array('registration_id' => $registration_id));
            $sc_case_type_id = $case_detail[0]['sc_case_type_id'];
        }else{
            $sc_case_type_id = $_SESSION['efiling_details']['sc_case_type_id'];
        }
        if (empty($sc_case_type_id)){echo "<script>alert('$sc_case_type_id Case type id is required!'); window.location.href='" . base_url() . "case/ancillary/form';</script>";}
        $this->session->set_flashdata('msg',' ');

        $data['affidavit_list']=array();

        if(isset($_POST['affidavit_save']) || isset($_POST['affidavit_update']) && !empty($_SESSION['efiling_details']['registration_id'])){
            $check_points = $_POST;
            $check_points['registration_id']=$registration_id;
            $check_points['database_type']=$_SESSION['efiling_details']['database_type'];
            $affidavit_id=$_POST['affidavit_id'];
            if (isset($_POST['affidavit_update']) && isset($_POST['affidavit_id']) && !empty($_POST['affidavit_id'])){
                unset($check_points['affidavit_update']);unset($check_points['affidavit_id']); unset($check_points['deponent_type']);
                // echo '<pre>';print_r($check_points);exit();
                $resultupdate= $this->supplement_model->insert_update_supplements($check_points,$sc_case_type_id,$deponent_type,$registration_id);
                if ($resultupdate){
                    $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Updated successfully.</div>');
                }else{
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">This Affidavit record is complete for PDF!</div>');
                }
                redirect('supplements/DefaultController/affidavits');
            }else if(isset($_POST['affidavit_save'])){
                unset($check_points['affidavit_save']); unset($check_points['affidavit_id']);
                unset($check_points['deponent_type']);
                $last_id= $this->supplement_model->insert_update_supplements($check_points,$sc_case_type_id,$deponent_type);

                if ($last_id){
                    $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Data saved successfully.</div>');
                }else{
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Duplicate entry Something went wrong,Please try again later!</div>');
                }
                redirect('supplements/DefaultController/affidavits');
            }


        }


        $data['affidavit_template'] = $this->supplement_model->affidavit_template($sc_case_type_id,$deponent_type);
        if (!empty($data['affidavit_template']) && $data['affidavit_template']!=null && count($data['affidavit_template']) > 0) {
            $affidavit_list = $this->supplement_model->get_supplements($registration_id,$sc_case_type_id,$deponent_type);
            if (!empty($affidavit_list) && count($affidavit_list) > 0) {
                $data['affidavit_list'] = $affidavit_list;
            } else {
                if (!empty($data['affidavit_template']) && count($data['affidavit_template']) > 0) {
                    $affidavit_list_m = get_affidavit_pdf_details($sc_case_type_id);
                    if ($data['affidavit_template'][0]['nature'] == 'C') {
                        $nature = 'SPECIAL LEAVE PETITION (CIVIL)';
                        $nature_id = 1;
                    } else {
                        $nature = 'SPECIAL LEAVE PETITION (CRIMINAL)';
                        $nature_id = 2;
                    }
                    $affidavit_list_t = $this->supplement_model->check_case_number_year_by_registration_id($registration_id, $nature_id);
                    if (!empty($affidavit_list_t) && count($affidavit_list_t) > 0) {
                        $affidavit_list_slp = $affidavit_list_t;
                    } else {
                        $affidavit_list_slp = array(['slp_name' => $nature, 'case_num' => $data['check_case'][0]['case_num'], 'case_year' => $data['check_case'][0]['case_year']]);
                    }
                    $data['affidavit_list'][] = array_merge($affidavit_list_slp[0], $affidavit_list_m[0]);
                } else {
                    echo 'Affidavit template is not available by sc_case_type_id:' . $sc_case_type_id;
                    exit();
                }
            }
        }else{
            echo '<div class="alert alert-danger text-center">Deponent Type by Affidavit template is not available!</div>';
            exit();
        }
        $data['deponent_type']=$deponent_type;
        //echo '<pre>';print_r($_SESSION['efiling_details']);//exit();
        //echo '<pre>'; print_r($data);exit();
        $this->load->view('templates/header');
        $this->load->view('supplements/affidavits/generic_template',$data);
        $this->load->view('templates/footer');
    }
    
    
   
}

?>