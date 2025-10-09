<?php

namespace App\Controllers\AIAssisted;

use App\Controllers\BaseController;
use App\Models\AIAssisted\CommonCasewithAIModel;

class ViewDocument extends BaseController {

    protected $Common_casewithAI_model;

    public function __construct() {
        parent::__construct();
        $this->Common_casewithAI_model = new CommonCasewithAIModel();
    }

    public function index($doc_id) {
        $client_ip=getClientIP();
        if($client_ip) {
            $ip_address = explode(".", $client_ip);
            if(($ip_address[0]==10)  && ($ip_address[1]==40 || $ip_address[1]==249 || $ip_address[1]==25 || $ip_address[1]==26 || $ip_address[1]==100)) {
                ##do nothing
            } else{
                $this->check_login();
            }
        }
        $doc_id = url_decryption($doc_id);        
        if (empty($doc_id) || !preg_match("/^[0-9]*$/", $doc_id)) {
            echo "Invalid Access !";
            exit(0);
        }
        $doc_details = $this->Common_casewithAI_model->get_uploaded_pdf_file_IITM($doc_id);
        if (!empty($doc_details)) {
            $file_partial_path = $doc_details[0]['file_path'];
            $file_name = $doc_details[0]['file_name'];
            $doc_title = 'main_draft_petition_' . str_replace(' ', '_', $doc_details[0]['doc_title']) . '.pdf';
            if (file_exists($file_partial_path)) {
                header("Content-Type: application/pdf");
                header("Content-Disposition:inline;filename = $doc_title");
                header('Content-Transfer-Encoding: binary');
                header('Accept-Ranges: bytes');
                // @readfile($file_partial_path);
                // echo $file_name;
                ob_get_clean();
                readfile($file_partial_path);
                ob_end_flush();
            } else{
                echo "File does not exists !";
                exit(0);
            }
        } else{
            echo "File does not exists !";
            exit(0);
        }
    }

    public function showDeletedFiles($doc_id) {
        $doc_id = url_decryption($doc_id);
        if (empty($doc_id) || !preg_match("/^[0-9]*$/", $doc_id)) {
            echo "Invalid Access !";
            exit(0);
        }
        $doc_details = $this->Common_casewithAI_model->get_uploaded_pdf_file_IITM($doc_id);
        if (!empty($doc_details)) {
            $file_partial_path = $doc_details[0]['file_path'];
            $file_name = $doc_details[0]['file_name'];
            $doc_title = $_SESSION['efiling_details']['efiling_no'] . '_' . str_replace(' ', '_', $doc_details[0]['doc_title']) . '.pdf';
            if (file_exists($file_partial_path)) {
                header("Content-Type: application/pdf");
                header("Content-Disposition:inline;filename = $doc_title");
                header('Content-Transfer-Encoding: binary');
                header('Accept-Ranges: bytes');
                @readfile($file_partial_path);
                echo $file_name;
            } else{
                echo "File does not exists !";
                exit(0);
            }
        } else{
            echo "File does not exists !";
            exit(0);
        }
    }

    public function json_decode($id) {
        $id = url_decryption($id);
        if (empty($id)) {
            echo 'Document id is required';exit();
        }
        $builder = $this->db->table('efil.tbl_uploaded_pdfs_jsonai');
        $builder->SELECT("*");        
        $builder->WHERE('id', $id);
        $builder->WHERE('is_deleted', FALSE);
        $builder->ORDER_BY('uploaded_on','DESC');
        $builder->LIMIT(1);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $response_result = $query->getResultArray();
            if (!empty($response_result)) {
                $decoded_data = json_decode($response_result[0]['iitm_api_json'], TRUE);
                if (!empty($decoded_data)) {
                    echo '<pre>'; print_r($decoded_data);exit();
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
        $builder->ORDER_BY('uploaded_on','DESC');
        $builder->LIMIT(1);
        $query = $this->db->get();
        if ($query->getNumRows() >= 1) {
            $response_result = $query->getResultArray();
            if (!empty($response_result)) {
                $response_result=$response_result[0]['iitm_api_json'];
                echo '<pre>'; print_r($response_result);exit();
            }
        } else{
            echo 'Data not found';
        }
        exit();
    }

    function check_login() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT,USER_ADMIN,USER_EFILING_ADMIN);
        $allowed_users_array1 = array(JAIL_SUPERINTENDENT);
        if (isset($_SESSION['login']) && !in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
    }

}