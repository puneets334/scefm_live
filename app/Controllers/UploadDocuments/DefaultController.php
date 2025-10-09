<?php
namespace App\Controllers\UploadDocuments;
use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\DocumentIndex\DocumentIndexDropDownModel;
use App\Models\DocumentIndex\DocumentIndexModel;
use App\Models\DocumentIndex\DocumentIndexSelectModel;
use App\Models\NewCase\GetDetailsModel;
use App\Models\ShcilPayment\PaymentModel;
use App\Models\UploadDocuments\UploadDocsModel;

class DefaultController extends BaseController {

    protected $UploadDocs_model;
    protected $DocumentIndex_model;
    protected $DocumentIndex_Select_model;
    protected $DocumentIndex_DropDown_model;
    protected $Payment_model;
    protected $Get_details_model;
    protected $request;
    protected $validation;
    protected $Common_model;

    public function __construct() {
        parent::__construct();
        $this->UploadDocs_model = new UploadDocsModel();
        $this->DocumentIndex_model = new DocumentIndexModel();
        $this->DocumentIndex_Select_model = new DocumentIndexSelectModel();
        $this->DocumentIndex_DropDown_model = new DocumentIndexDropDownModel();
        $this->Payment_model = new PaymentModel();
        $this->Get_details_model = new GetDetailsModel();
        $this->Common_model = new CommonModel();
        $this->request = \Config\Services::request();
        $this->validation = \Config\Services::validation();
    }

    function check_login() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK,JAIL_SUPERINTENDENT,AMICUS_CURIAE_USER);
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            echo '1@@@Invalid attempt.';
            exit(0);
        }
    }

    public function index($doc_id = NULL) {
       
        $this->check_login();
        $docd_id = '';
        if (isset($doc_id) && !empty($doc_id)) {
            $docd_id = url_decryption($doc_id);
            $data['document_id'] = $docd_id;
        }
        //caveat view
        if (check_party() !=true) {  //func added on 15 JUN 2021
            $this->session->setFlashdata('msg', 'Please enter every party details before moving to further tabs.');
            return redirect()->to(base_url('newcase/extra_party'));
        }
        
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!empty(getSessionData('efiling_details')['stage_id']) && !in_array(getSessionData('efiling_details')['stage_id'], $stages_array) && getSessionData('efiling_details')['efiling_type'] == 'new_case'){
            return redirect()->to(base_url('newcase/view'));
        }
        else if (!empty(getSessionData('efiling_details')['stage_id']) && !in_array(getSessionData('efiling_details')['stage_id'], $stages_array) && getSessionData('efiling_details')['efiling_type'] == 'CAVEAT'){
            return redirect()->to(base_url('caveat/view'));
        }

        if (!empty(getSessionData('efiling_details')['stage_id']) && (!in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) && $_SESSION['login']['ref_m_usertype_id']!=JAIL_SUPERINTENDENT) {
            return redirect()->to(base_url('dashboard'));
        }
        else if (!empty(getSessionData('efiling_details')['stage_id']) && (!in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) && $_SESSION['login']['ref_m_usertype_id']==JAIL_SUPERINTENDENT) {
            return redirect()->to(base_url('jail_dashboard'));
        }
        //get total is_dead_minor
        
        $params = array();
        $params['registration_id'] = !empty(getSessionData('efiling_details')['registration_id']) ? getSessionData('efiling_details')['registration_id'] : NULL;
        $params['is_dead_minor'] = true;
        $params['is_deleted'] = 'false';
        $params['is_dead_file_status'] ='false';
        $params['total'] =1;
        $isdeaddata = $this->Get_details_model->getTotalIsDeadMinor($params);
        if(isset($isdeaddata[0]->total) && !empty($isdeaddata[0]->total)){
            $total = $isdeaddata[0]->total;
            $this->session->setFlashdata('msg', 'Please fill '.$total.' remaining dead/minor party details');
            return redirect()->to(base_url('newcase/lr_party'));
            exit(0);
        }
        if (isset(getSessionData('efiling_details')['registration_id']) && !empty(getSessionData('efiling_details')['registration_id'])) {

            $registration_id = getSessionData('efiling_details')['registration_id'];

            $data['uploaded_docs'] = $this->UploadDocs_model->get_uploaded_pdfs($registration_id);
            $data['index_details'] = $this->DocumentIndex_Select_model->get_index_details($registration_id, $docd_id);
            $data['uploaded_pdf'] = $this->DocumentIndex_DropDown_model->get_uploaded_pdfs($registration_id);
            if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
                $ia_doc_type = 8;
                $data['doc_type'] = $this->DocumentIndex_DropDown_model->get_document_type(NULL);
            }
            elseif (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS){
                $data['doc_type'] = $this->DocumentIndex_DropDown_model->get_document_type(NULL);
            }
            else {
                //TODO update the document upload breadcrumb status(8), when user does not upload any document from upload document tab :27042023
                $breadcrumb_to_update = NEW_CASE_UPLOAD_DOCUMENT;
                if(!empty($data['uploaded_pdf']) && count($data['uploaded_pdf']) >0)
                {
                    $update_courtfee_breadcrumb_status = $this->Payment_model->update_breadcrumbs($registration_id, $breadcrumb_to_update);
                }
                else
                {
                    $update_courtfee_breadcrumb_status = $this->Payment_model->remove_breadcrumb($registration_id, $breadcrumb_to_update);
                }
                $data['doc_type'] = $this->DocumentIndex_DropDown_model->get_document_type(NULL);
            }
            
            if(!empty($data['index_details'])){
                $data['doc_res'] = $this->DocumentIndex_DropDown_model->get_sub_document_type($data['index_details'][0]['doc_type_id']);
            }else{
                $data['doc_res'] = [];
            }

            if(isset($_REQUEST['pspdfkitdocumentid']) && !empty($_REQUEST['pspdfkitdocumentid'])){
                $data['selected_pspdfkit_document_id'] = $_REQUEST['pspdfkitdocumentid'];
            }
        }
        // if($_SESSION['login']['ref_m_usertype_id']!=JAIL_SUPERINTENDENT)
        //     $this->load->view('templates/header');
        // else if($_SESSION['login']['ref_m_usertype_id']==JAIL_SUPERINTENDENT)
        //     $this->load->view('templates/jail_header');
        if(!empty(getSessionData('efiling_details'))) {
            switch (getSessionData('efiling_details')['ref_m_efiled_type_id']) {

                case E_FILING_TYPE_NEW_CASE :
                    return render('newcase.new_case_view', $data);
                    break;
                case E_FILING_TYPE_MISC_DOCS :
                    return render('miscellaneous_docs.miscellaneous_docs_view', $data);
                    break;
                case E_FILING_TYPE_IA :
                    return render('IA.ia_view', $data);
                    break;
                case E_FILING_TYPE_MENTIONING :
                    return render('mentioning.mentioning_view', $data);
                    break;
                case E_FILING_TYPE_JAIL_PETITION :
                    return render('jailPetition.new_case_view', $data);
                    break;
                case OLD_CASES_REFILING :
                    return render('oldCaseRefiling.old_efiling_view', $data);
                    break;
                    default :
                    return render('newcase.new_case_view', $data);
                break;
            }
        } else {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        // $this->load->view('templates/footer');
    }

    public function upload_pdf() {
        $this->check_login();

        if (!empty(getSessionData('efiling_details'))) {
            $registration_id = getSessionData('efiling_details')['registration_id'];
            $ref_m_efiled_type_id = getSessionData('efiling_details')['ref_m_efiled_type_id'];
            $curr_stage_id = getSessionData('efiling_details')['stage_id'];
        } else {
            if(getSessionData('login')['ref_m_usertype_id']!=JAIL_SUPERINTENDENT) {
                return redirect()->to(base_url('dashboard'));
                exit(0);
            } else if(getSessionData('login')['ref_m_usertype_id']==JAIL_SUPERINTENDENT) {
                return redirect()->to(base_url('jail_dashboard'));
                exit(0);
            }
        }
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if ((!in_array($curr_stage_id, $stages_array)) && getSessionData('login')['ref_m_usertype_id']!=JAIL_SUPERINTENDENT) {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        } else if ((!in_array($curr_stage_id, $stages_array)) && getSessionData('login')['ref_m_usertype_id']==JAIL_SUPERINTENDENT) {
            return redirect()->to(base_url('jail_dashboard'));
            exit(0);
        }
        if((isset($_POST['caveateDocNum'])) && (!empty($_POST['caveateDocNum'])) && ($_POST['caveateDocNum'] == 1) && (!empty(getSessionData('efiling_details')['ref_m_efiled_type_id']))  && (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT)) {
            $params = array();
            $params['table_name'] ="efil.tbl_uploaded_pdfs";
            $params['whereFieldName'] ="registration_id";
            $params['whereFieldValue'] =$registration_id;
            $params['is_deleted'] = "false";
            $res = $this->Common_model->getData($params);
            if(isset($res) && !empty($res) && count($res) == 1) {
                echo '1@@@' . htmlentities('Caveat document already uploaded.', ENT_QUOTES);
                exit(0);
            }
        } else {
            if ($msg = isValidPDF('pdfDocFile')) {
                echo '1@@@' . htmlentities($msg, ENT_QUOTES);
                exit(0);
            }
            $this->validation->setRules([
                "doc_title" => [
                    "label" => "Document Title",
                    "rules" => "required|trim|min_length[3]|max_length[75]|validate_alpha_numeric_space_dot_hyphen"
                ],
            ]);
            // $this->form_validation->set_error_delimiters('<br/>', '');
            if ($this->validation->withRequest($this->request)->run() === FALSE) {
                echo '3@@@';
                echo $this->validation->getError('doc_title');
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
                case OLD_CASES_REFILING: $breadcrumb_step_no= MISC_BREAD_UPLOAD_DOC;
                    break;
            }
            $doc_title = strtoupper(escape_data($this->request->getPost("doc_title")));
            if ($doc_title=='DRAFT PETITION'){ $breadcrumb_step_no=NEW_CASE_CASE_DETAIL; }
            $pspdfkit_document_id='';
            $doc_hash_value = hash_file('sha256', $_FILES['pdfDocFile']['tmp_name']);
            $uploaded_on = date('Y-m-d H:i:s');
            $sub_created_by = 0;
            $uploaded_by = $_SESSION['login']['id'];
            if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {
                $aorData = getAordetails_ifFiledByClerk($registration_id);
                if (isset($aorData) && !empty($aorData)) {
                    $uploaded_by=!empty($aorData) ? $aorData[0]->id  : 0;
                    $sub_created_by = $_SESSION['login']['id'];
                }
            }
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
        $input_array = explode('$$',url_decryption(escape_data($this->request->getPost("form_submit_val"))));        
        if (count($input_array) != 2) {
            echo '1@@@Invalid Action';            
        }
        $pdf_id = $input_array[0];
        $registration_id = $input_array[1];
        if (empty($pdf_id)) {
            echo '1@@@Invalid attempt.';
            exit(0);            
        }
        if ($registration_id != getSessionData('efiling_details')['registration_id']) {
            echo '1@@@Invalid attempt.';
            exit(0);            
        }
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
            echo '1@@@Delete of PDF is not allowed at this stage.';
            exit(0);            
        }
        switch (getSessionData('efiling_details')['ref_m_efiled_type_id']) {
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
            case OLD_CASES_REFILING :
                $breadcrumb_to_remove = array(MISC_BREAD_UPLOAD_DOC,MISC_BREAD_DOC_INDEX, MISC_BREAD_COURT_FEE);
                break;
        }
        $isPdfDoc = $this->UploadDocs_model->isPdfDoc($pdf_id, $registration_id);
        if (!empty($isPdfDoc) && $_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE && !(in_array(getSessionData('efiling_details')['stage_id'], array(Draft_Stage,Initial_Defected_Stage,I_B_Defected_Stage)))) {
            echo '1@@@You cannot delete this file as the court fee has already been paid.';exit(0);
        }
        $result = $this->UploadDocs_model->deletePdfDoc($pdf_id, $registration_id, $breadcrumb_to_remove);
        switch (getSessionData('efiling_details')['ref_m_efiled_type_id']) {
            case E_FILING_TYPE_NEW_CASE : $breadcrumb_to_remove_for_index = array(NEW_CASE_INDEX, NEW_CASE_AFFIRMATION);
                break;
            case E_FILING_TYPE_MISC_DOCS : $breadcrumb_to_remove_for_index = array(MISC_BREAD_DOC_INDEX, MISC_BREAD_AFFIRMATION);
                break;
            case E_FILING_TYPE_IA : $breadcrumb_to_remove_for_index = array(IA_BREAD_DOC_INDEX, IA_BREAD_AFFIRMATION);
                break;
            case E_FILING_TYPE_CAVEAT : $breadcrumb_to_remove_for_index = array(CAVEAT_BREAD_DOC_INDEX,CAVEAT_BREAD_COURT_FEE);
                break;
            case OLD_CASES_REFILING : $breadcrumb_to_remove_for_index = array(MISC_BREAD_UPLOAD_DOC,MISC_BREAD_DOC_INDEX, MISC_BREAD_COURT_FEE);
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

    function file_download($name) {
        $this->load->helper('download');
        if($name=='S') {
            $pth = file_get_contents(base_url("download/surrender.pdf"));
            $nme = "Certificate of Surrender.pdf";
        } else if($name=='C') {
            $pth = file_get_contents(base_url("download/custody.pdf"));
            $nme = "Certificate of Custody.pdf";
        }
        force_download($nme, $pth);
    }

}