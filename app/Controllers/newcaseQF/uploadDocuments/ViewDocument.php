<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ViewDocument extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('common/common_model');
        $this->load->model('common/Form_validation_model');
        $this->load->model('uploadDocuments/UploadDocs_model');  
    }

    public function _remap($param = NULL) {

        if ($param == 'index') {
            echo "Invalid Access !";
            exit(0);
        } else {
            $this->index($param);
        }
    }

    public function index($doc_id) {
        $client_ip=getClientIP();
        if($client_ip)
        {
            $ip_address = explode(".", $client_ip);
            if(($ip_address[0]==10 )  && ($ip_address[1]==40 || $ip_address[1]==25))
            {
                ##do nothing
            } else
            {
                $this->check_login();
            }
        }

        $doc_id = url_decryption($doc_id);
        if (empty($doc_id) || !preg_match("/^[0-9]*$/", $doc_id)) {
            echo "Invalid Access !";
            exit(0);
        }

        $doc_details = $this->UploadDocs_model->get_uploaded_pdf_file($doc_id);
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
        } else {            
            echo "File does not exists !";
            exit(0);
        }
    }

    
    function check_login() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT,USER_ADMIN);
        $allowed_users_array1 = array(JAIL_SUPERINTENDENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('dashboard');
            exit(0);
        }
    }

}
