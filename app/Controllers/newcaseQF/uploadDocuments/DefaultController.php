<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//require_once(ESIGN_JAVA_BRIDGE_URI);

class DefaultController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('newcaseQF/uploadDocuments/UploadDocs_model');
        $this->load->model('newcaseQF/documentIndex/DocumentIndex_model');
        $this->load->helper('file');
    }

    function check_login() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK,JAIL_SUPERINTENDENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            echo '1@@@Invalid attempt.';
            exit(0);
        }
    }

    public function index() {
        $this->check_login();
        //caveat view
        if (check_party() !=true) {  //func added on 15 JUN 2021
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please enter every party details before moving to further tabs.</div>');
            redirect('newcase/extra_party');
        }
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!empty($_SESSION['efiling_details']['stage_id']) && !in_array($_SESSION['efiling_details']['stage_id'], $stages_array) && $_SESSION['efiling_details']['efiling_type'] == 'new_case'){
            redirect('newcase/view');
            exit(0);
        }
        else if (!empty($_SESSION['efiling_details']['stage_id']) && !in_array($_SESSION['efiling_details']['stage_id'], $stages_array) && $_SESSION['efiling_details']['efiling_type'] == 'CAVEAT'){
            redirect('caveat/view');
            exit(0);
        }

        if ((!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) && $_SESSION['login']['ref_m_usertype_id']!=JAIL_SUPERINTENDENT) {
            redirect('dashboard');
            exit(0);
        }
        else if ((!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) && $_SESSION['login']['ref_m_usertype_id']==JAIL_SUPERINTENDENT) {
            redirect('jail_dashboard');
            exit(0);
        }
        //get total is_dead_minor
        $params = array();
        $params['registration_id'] = !empty($_SESSION['efiling_details']['registration_id']) ? $_SESSION['efiling_details']['registration_id'] : NULL;
        $params['is_dead_minor'] = true;
        $params['is_deleted'] = 'false';
        $params['is_dead_file_status'] ='false';
        $params['total'] =1;
        $this->load->model('newcase/Get_details_model');
        $isdeaddata = $this->Get_details_model->getTotalIsDeadMinor($params);
        if(isset($isdeaddata[0]->total) && !empty($isdeaddata[0]->total)){
            $total = $isdeaddata[0]->total;
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please fill '.$total.' remaining dead/minor party details</div>');
            redirect('newcase/lr_party');
            exit(0);
        }
        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {

            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $data['uploaded_docs'] = $this->UploadDocs_model->get_uploaded_pdfs($registration_id);
        }
        if($_SESSION['login']['ref_m_usertype_id']!=JAIL_SUPERINTENDENT)
            $this->load->view('templates/header');
        else if($_SESSION['login']['ref_m_usertype_id']==JAIL_SUPERINTENDENT)
            $this->load->view('templates/jail_header');
        $_SESSION['efiling_details']['ref_m_efiled_type_id']=E_FILING_TYPE_NEW_CASE;
        switch ($_SESSION['efiling_details']['ref_m_efiled_type_id']) {

            case E_FILING_TYPE_NEW_CASE : $this->load->view('newcaseQF/new_case_view', $data);
                break;
            case E_FILING_TYPE_MISC_DOCS : $this->load->view('miscellaneous_docs/miscellaneous_docs_view', $data);
                break;
            case E_FILING_TYPE_IA : $this->load->view('IA/ia_view', $data);
                break;
            case E_FILING_TYPE_MENTIONING : $this->load->view('mentioning/mentioning_view', $data);
                break;
            case E_FILING_TYPE_JAIL_PETITION : $this->load->view('jailPetition/new_case_view', $data);
                break;
                default : $this->load->view('newcase/new_case_view', $data);
            break;
        }
        $this->load->view('templates/footer');
    }

    public function upload_pdf() {

        $this->check_login();

        if (isset($_SESSION['efiling_details']) && !empty($_SESSION['efiling_details'])) {
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $ref_m_efiled_type_id = $_SESSION['efiling_details']['ref_m_efiled_type_id'];
            $curr_stage_id = $_SESSION['efiling_details']['stage_id'];
        } else {
            if($_SESSION['login']['ref_m_usertype_id']!=JAIL_SUPERINTENDENT)
            { redirect('dashboard');
            exit(0);
        }
        else if($_SESSION['login']['ref_m_usertype_id']==JAIL_SUPERINTENDENT)
        { redirect('jail_dashboard');
            exit(0);
        }}


        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if ((!in_array($curr_stage_id, $stages_array)) && $_SESSION['login']['ref_m_usertype_id']!=JAIL_SUPERINTENDENT) {
            redirect('dashboard');
            exit(0);
        }
        else if ((!in_array($curr_stage_id, $stages_array)) && $_SESSION['login']['ref_m_usertype_id']==JAIL_SUPERINTENDENT) {
            redirect('jail_dashboard');
            exit(0);
        }
        if((isset($_POST['caveateDocNum'])) && (!empty($_POST['caveateDocNum'])) && ($_POST['caveateDocNum'] == 1) &&
            (!empty($_SESSION['efiling_details']['ref_m_efiled_type_id']))  && ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT))
        {
            $this->load->model('common/Common_model');
            $params = array();
            $params['table_name'] ="efil.tbl_uploaded_pdfs";
            $params['whereFieldName'] ="registration_id";
            $params['whereFieldValue'] =$registration_id;
            $params['is_deleted'] = "false";
            $res = $this->Common_model->getData($params);
            if(isset($res) && !empty($res) && count($res) == 1){
                echo '1@@@' . htmlentities('Caveat document already uploaded.', ENT_QUOTES);
                exit(0);
            }
        }
        else{
            if ($msg = isValidPDF('pdfDocFile')) {
                echo '1@@@' . htmlentities($msg, ENT_QUOTES);
                exit(0);
            }

            $this->form_validation->set_rules('doc_title', 'Document Title', 'required|trim|min_length[3]|max_length[75]|validate_alpha_numeric_space_dot_hyphen');

            $this->form_validation->set_error_delimiters('<br/>', '');
            if (!$this->form_validation->run()) {
                echo '3@@@';
                echo form_error('doc_title');
                exit(0);
            }
            switch ($ref_m_efiled_type_id) {
                case E_FILING_TYPE_NEW_CASE : $breadcrumb_step_no = NEW_CASE_UPLOAD_DOCUMENT;
                    break;
                case E_FILING_TYPE_CAVEAT :  $breadcrumb_step_no = CAVEAT_BREAD_UPLOAD_DOC;
                    break;
                case E_FILING_TYPE_MISC_DOCS : $breadcrumb_step_no = MISC_BREAD_UPLOAD_DOC;
                    break;
                case E_FILING_TYPE_IA : $breadcrumb_step_no = IA_BREAD_UPLOAD_DOC;
                    break;
                case E_FILING_TYPE_MENTIONING : $breadcrumb_step_no = MEN_BREAD_UPLOAD_DOC;
                    break;
                case E_FILING_TYPE_JAIL_PETITION: $breadcrumb_step_no=JAIL_PETITION_UPLOAD_DOCUMENT;
                    break;
            }
            $doc_title = strtoupper(escape_data($this->input->post("doc_title")));
            if ($doc_title=='DRAFT PETITION'){ $breadcrumb_step_no=NEW_CASE_CASE_DETAIL; }
            //The following line starting from 164-177 commented by K.B.Pujari to remove PSPDFKit
            /*$filecontentPSPDF = file_get_contents($_FILES['pdfDocFile']['tmp_name']);
            $pspdfkit_document = uploadDocumentToPSPDFKit($filecontentPSPDF);
            $pspdfkit_document_id = $pspdfkit_document['document_id'];

            if(empty($pspdfkit_document_id)){
                echo '1@@@' . htmlentities('Failed. PsPDFkit issue. Contact Technical Support', ENT_QUOTES);
                exit(0);
            }

            if(showOCRAlert($pspdfkit_document_id)==true){
                echo '4@@@' . htmlentities('Failed. Please Upload OCR\'ed PDF With Text Content!', ENT_QUOTES);
                $this->DocumentIndex_model->deleteDocument($pspdfkit_document_id);
                exit(0);
            };*/
            $pspdfkit_document_id='';

            $doc_hash_value = hash_file('sha256', $_FILES['pdfDocFile']['tmp_name']);
            $uploaded_on = date('Y-m-d H:i:s');

            $sub_created_by = 0;
            $uploaded_by = $_SESSION['login']['id'];

            $data = array(
                'registration_id' => $registration_id,
                'efiled_type_id' => $ref_m_efiled_type_id,
                'file_size' => $_FILES['pdfDocFile']['size'],
                'file_type' => $_FILES['pdfDocFile']['type'],
                'doc_title' => $doc_title,
                'doc_hashed_value' => $doc_hash_value,
                'uploaded_by' => $uploaded_by,
                'uploaded_on' => $uploaded_on,
                'upload_ip_address' => getClientIP(),
                'sub_created_by' => $sub_created_by,
                'pspdfkit_document_id'=>$pspdfkit_document_id
            );
            $result = $this->UploadDocs_model->upload_pdfs($data, $_FILES['pdfDocFile']['tmp_name'], $breadcrumb_step_no);

            if ($result == 'trans_success') {
                echo '2@@@' . htmlentities('Document uploaded successfully', ENT_QUOTES);
                exit(0);
            } elseif ($result == 'trans_failed') {
                echo '1@@@' . htmlentities('Transaction failed ! Please Try Again.', ENT_QUOTES);
                exit(0);
            } elseif ($result == 'upload_fail') {
                echo '1@@@' . htmlentities('Document uploading failed due to some technical reason.', ENT_QUOTES);
                exit(0);
            } else {
                echo '1@@@' . htmlentities('Document Uploading Failed ! Please Try again.', ENT_QUOTES);
                exit(0);
            }
        }
    }

    function deletePDF() {
        $this->check_login();

        $input_array = explode('$$',url_decryption(escape_data($this->input->post("form_submit_val"))));
        
        if (count($input_array) != 2) {
            echo '1@@@Invalid Action';            
        }
        $pdf_id = $input_array[0];
        $registration_id = $input_array[1];
        if (empty($pdf_id)) {
            echo '1@@@Invalid attempt.';
            exit(0);            
        }
        if ($registration_id != $_SESSION['efiling_details']['registration_id']) {
            echo '1@@@Invalid attempt.';
            exit(0);            
        }


        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            echo '1@@@Delete of PDF is not allowed at this stage.';
            exit(0);            
        }
        switch ($_SESSION['efiling_details']['ref_m_efiled_type_id']) {
            case E_FILING_TYPE_NEW_CASE : $breadcrumb_to_remove = NEW_CASE_UPLOAD_DOCUMENT;
                break;
            case E_FILING_TYPE_MISC_DOCS : $breadcrumb_to_remove = MISC_BREAD_UPLOAD_DOC;
                break;
            case E_FILING_TYPE_IA : $breadcrumb_to_remove = IA_BREAD_UPLOAD_DOC;
                break;
            case E_FILING_TYPE_JAIL_PETITION: $breadcrumb_to_remove=JAIL_PETITION_UPLOAD_DOCUMENT;
                break;
            case E_FILING_TYPE_CAVEAT: $breadcrumb_to_remove=CAVEAT_BREAD_UPLOAD_DOC;
                break;
        }

        $isPdfDoc = $this->UploadDocs_model->isPdfDoc($pdf_id, $registration_id);

        if (!empty($isPdfDoc) && $_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE && !(in_array($_SESSION['efiling_details']['stage_id'], array(Draft_Stage,Initial_Defected_Stage,I_B_Defected_Stage)))) {
            echo '1@@@You cannot delete this file as the court fee has already been paid.';exit(0);
        }
        $result = $this->UploadDocs_model->deletePdfDoc($pdf_id, $registration_id, $breadcrumb_to_remove);
        switch ($_SESSION['efiling_details']['ref_m_efiled_type_id']) {
            case E_FILING_TYPE_NEW_CASE : $breadcrumb_to_remove_for_index = array(NEW_CASE_INDEX, NEW_CASE_AFFIRMATION);
                break;
            case E_FILING_TYPE_MISC_DOCS : $breadcrumb_to_remove_for_index = array(MISC_BREAD_DOC_INDEX, MISC_BREAD_AFFIRMATION);
                break;
            case E_FILING_TYPE_IA : $breadcrumb_to_remove_for_index = array(IA_BREAD_DOC_INDEX, IA_BREAD_AFFIRMATION);
                break;
            case E_FILING_TYPE_CAVEAT : $breadcrumb_to_remove_for_index = array(CAVEAT_BREAD_DOC_INDEX,CAVEAT_BREAD_COURT_FEE);
                break;
        }
        if(!is_null($breadcrumb_to_remove_for_index))
            $result2 = $this->DocumentIndex_model->delete_index_by_UploadFile_Pdf_Id($pdf_id, $breadcrumb_to_remove_for_index);
        if ($result) {
            reset_affirmation($registration_id);
            echo '2@@@PDF deleted successfully !';
            exit(0);
        } else {
            echo '1@@@Some Error ! Please try after some time.';
            exit(0);
        }
    }
    function file_download($name)
    {
        $this->load->helper('download');
        if($name=='S')
        {$pth    =   file_get_contents(base_url("download/surrender.pdf"));
        $nme    =   "Certificate of Surrender.pdf";}
        else if($name=='C')
        {$pth    =   file_get_contents(base_url("download/custody.pdf"));
            $nme    =   "Certificate of Custody.pdf";}
        force_download($nme, $pth);
    }
}
