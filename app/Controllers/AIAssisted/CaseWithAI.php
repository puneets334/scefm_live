<?php

namespace App\Controllers\AIAssisted;

use App\Controllers\BaseController;
use App\Models\AIAssisted\CommonCasewithAIModel; 
use Config\Database;

class CaseWithAI extends BaseController {

    protected $Common_casewithAI_model;
    protected $db;

    public function __construct() {
        parent::__construct();
        $this->Common_casewithAI_model = new CommonCasewithAIModel();
        $this->db = Database::connect();
        if (getSessionData('login') == '') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if(isset($_SESSION['login']) && !in_array($_SESSION['login']['id'],AIASSISTED_USER_IN_LIST)){
            return redirect()->to(base_url('adminDashboard'));
        }
    }


    public function showCaseCrud($registration_id = null){
        unset($_SESSION['casewithAI']);
        $registration_id=str_replace('_','#',@$registration_id);
        $tab = @$_REQUEST['tab'];
        $this->render('AIAssisted.case_with_AI_crud', @compact('registration_id','tab'));
    }

    public function index() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
        if (isset($_SESSION['login']) && !in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }
        $data['sc_case_type'] = $this->Common_casewithAI_model->get_sci_case_type();
        $data['uploaded_docs_IITM'] = $this->Common_casewithAI_model->get_uploaded_pdfs($_SESSION['login']['id']);
        $this->render('AIAssisted.case_with_AI_upload_pdf_view', @compact('data'));
    }

    public function get_AIAssist_case_efling() {
        $id=url_decryption(escape_data($this->request->getGet("form_submit_val")));
        if (!empty($id)) {
            $response=casewithAI($id);
            if ($response && isset($_SESSION['casewithAI']) && !empty($_SESSION['casewithAI'])){
                if (isset($_SESSION['casewithAI'][0]['registration_id']) && !empty($_SESSION['casewithAI'][0]['registration_id'])) {
                    $filing_type = E_FILING_TYPE_NEW_CASE;
                    $params_url_segment = '/'.url_encryption(trim($_SESSION['casewithAI'][0]['registration_id'].'#'.$filing_type.'#1'));
                    $redirect_url_prefix = 'case/crud'.$params_url_segment;
                    echo '2@@@'.base_url($redirect_url_prefix); exit(0);
                } else{
                    echo '2@@@'.base_url('case/crud'); exit(0);
                }
            }
        } else{
            echo '1@@@' . htmlentities('Upload Draft Petition file is required', ENT_QUOTES);exit(0);
        }
    }

    public function is_upload_pdf() {
        if (isset($_POST['sc_case_type'])){
            $sc_case_type = url_decryption(escape_data($this->request->getPost("sc_case_type")));
        }
        $doc_title = 'Draft Petition';
        if (isset($_POST['doc_title'])){
            $doc_title = strtoupper(escape_data($this->request->getPost("doc_title")));
        }
        if ($doc_title=='DRAFT PETITION'){ $breadcrumb_step_no=NEW_CASE_CASE_DETAIL; }
        if ($msg = isValidPDF('pdfDocFile')) {
            echo '1@@@' . htmlentities($msg, ENT_QUOTES); exit(0);
        }
        $breadcrumb_step_no = NEW_CASE_UPLOAD_DOCUMENT;
        // (A) ERROR - NO FILE UPLOADED
        if (!isset($_FILES["pdfDocFile"])) {
            echo '1@@@' . htmlentities('Upload Draft Petition file is required', ENT_QUOTES);
            exit(0);
        }
        // (B) FLAGS & "SETTINGS" // (B1) ACCEPTED & UPLOADED MIME-TYPES
        $accept = ["application/pdf"];
        $upmime = strtolower($_FILES["pdfDocFile"]["type"]);
        // (B2) SOURCE & DESTINATION
        $source = $_FILES["pdfDocFile"]["tmp_name"];
        $destination = $_FILES["pdfDocFile"]["name"];
        // (C) SAVE UPLOAD ONLY IF ACCEPTED FILE TYPE
        if (in_array($upmime, $accept)) {
            $doc_hash_value = hash_file('sha256', $_FILES['pdfDocFile']['tmp_name']);
            $uploaded_on = date('Y-m-d H:i:s');
            $sub_created_by = 0;
            $uploaded_by = isset($_SESSION['login']['id']) ? $_SESSION['login']['id'] : null;
            $data = array(
                'file_size' => $_FILES['pdfDocFile']['size'],
                'file_type' => $_FILES['pdfDocFile']['type'],
                'doc_title' => $doc_title,
                'doc_hashed_value' => $doc_hash_value,
                'uploaded_by' => $uploaded_by,
                'uploaded_on' => $uploaded_on,
                'upload_ip_address' => getClientIP(),
                'is_active_iitm' => false,
                'sc_case_type' => !empty($sc_case_type)  ? $sc_case_type:null,
                'api_stage_id' => 1,
            );
            $insert_id = $this->Common_casewithAI_model->upload_pdfs($data, $_FILES['pdfDocFile']['tmp_name'], $breadcrumb_step_no);
            if ($insert_id == 'trans_failed') {
                session()->setFlashdata('msg', '<div class="alert alert-success fade in">Transaction failed ! Please Try Again.</div>');
                return redirect()->to(base_url('casewithAI'));
            } elseif ($insert_id == 'upload_fail') {
                session()->setFlashdata('err', '<div class="alert alert-danger fade in">Document uploading failed due to some technical reason.</div>');
                return redirect()->to(base_url('casewithAI'));
            } elseif (!empty($insert_id)) {
                $result_data_extract=$this->Common_casewithAI_model->get_casewithAI_data_extract_details($insert_id);
                $filePath='';
                if (!empty($result_data_extract)) {
                    $filePath=$result_data_extract[0]['file_path'];
                }
                if (file_exists($filePath)) {
                    session()->setFlashdata('msg', '<div class="alert alert-success fade in">Document uploaded successfully.</div>');
                    return redirect()->to(base_url('casewithAI'));
                } else{
                    session()->setFlashdata('err', '<div class="alert alert-danger fade in">Document uploading failed due to some technical reason!</div>');
                    return redirect()->to(base_url('casewithAI'));
                }
            }
            session()->setFlashdata('msg', '<div class="alert alert-success fade in">Document ready to upload.</div>');
            return redirect()->to(base_url('casewithAI'));
        } else {
            session()->setFlashdata('err', 'Only PDF are allowed');
            return redirect()->to(base_url('casewithAI'));
        }
    }

    public function json_decode($id) {
        $id = url_decryption($id);
        if (empty($id)){
            echo 'Document id is required';exit();
        }
        $builder = $this->db->table('efil.tbl_uploaded_pdfs_jsonai');
        $builder->SELECT("*");        
        $builder->WHERE('id', $id);
        $builder->WHERE('is_deleted', FALSE);
        $builder->orderBy('uploaded_on','DESC');
        $builder->LIMIT(1);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $response_result = $query->getResultArray();
            if (!empty($response_result)) {
                $decoded_data = json_decode($response_result[0]['iitm_api_json'], TRUE);
                if (!empty($decoded_data)) {
                    echo "<pre>";
                    echo json_encode($decoded_data, JSON_PRETTY_PRINT);
                    echo "</pre>";
                } else{
                    echo 'Data not found';
                }

            }
        } else{
            echo 'Data not found';
        }
        exit();
    }

    public function jsonapiIITM($id) {
        $id = url_decryption($id);
        if (empty($id)){
            echo 'Document id is required';exit();
        }
        $builder = $this->db->table('efil.tbl_uploaded_pdfs_jsonai');
        $builder->SELECT("*");        
        $builder->WHERE('is_deleted', FALSE);
        $builder->WHERE('id', $id);
        $builder->orderBy('uploaded_on','DESC');
        $builder->LIMIT(1);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $response_result = $query->getResultArray();
            if (!empty($response_result)) {
                $response_result=$response_result[0]['iitm_api_json'];
                echo "<pre>";
                echo json_encode($response_result, JSON_PRETTY_PRINT);
                echo "</pre>";
            }
        } else{
            echo 'Data not found';
        }
        exit();
    }

    public function json_decode_active_session(){
        echo 'Json API data extract=<pre>'; print_r($_SESSION['casewithAI']);exit();
    }

    public function report() {
        $builder = $this->db->table('efil.tbl_round_robin_api_iitm');
        $builder->SELECT("*");        
        $builder->WHERE('is_deleted', FALSE);
        $builder->orderBy('last_triggered_started_on', 'DESC');
        $query = $builder->get();
        $result=  $query->getResultArray();
        $data['cronIITM'] =$result;
        $this->render('AIAssisted.case_with_AI_cron_view', @compact('data'));
    }

    public function defect_list($id) {
        $id = url_decryption($id);
        if (empty($id)) {
            echo 'Document id is required';
            exit();
        }
        $builder = $this->db->table('efil.tbl_uploaded_pdfs_jsonai');
        $builder->SELECT("*");        
        $builder->WHERE('iitm_api_json is not null');
        $builder->WHERE('id', $id);
        $builder->WHERE('is_deleted', FALSE);
        $builder->orderBy('uploaded_on', 'DESC');
        $builder->LIMIT(1);
        $query = $builder->get();
        $result = $query->getResultArray();
        $defect = array();
        if (!empty($result)) {
            if (isset($result) && isset($result[0]['iitm_api_json_defect']) && !empty($result[0]['iitm_api_json_defect']) && $result[0]['iitm_api_json_defect'] !=null) {
                if(strtolower($result[0]['iitm_api_json_defect']) !='internal server error') {
                    $iitm_api_json_defect_decode = json_decode($result[0]['iitm_api_json_defect'], TRUE);
                    if (!empty($iitm_api_json_defect_decode)) {
                        if (!empty($iitm_api_json_defect_decode)) {
                            $defect = $iitm_api_json_defect_decode;
                        }
                    }
                }
            }
            if (empty($defect)) {
                if (isset($result) && !empty($result[0]['iitm_api_json'])) {
                    $iitm_api_json_decode = json_decode($result[0]['iitm_api_json'], TRUE);
                    if (!empty($iitm_api_json_decode)) {
                        if (!empty($iitm_api_json_decode)) {
                            $defect = $iitm_api_json_decode['defect'];
                        }
                    }
                }
            }
        }
        $data['defect_list'] =$defect;
        $this->render('AIAssisted.defect_list_view', @compact('data'));
    }

}