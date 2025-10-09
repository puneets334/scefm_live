<?php
namespace App\Controllers\UploadDocuments;
use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\Common\FormValidationModel;
use App\Models\UploadDocuments\UploadDocsModel;

class ViewDocument extends BaseController {
    protected $common_model;
    protected $Form_validation_model;
    protected $UploadDocs_model;
    protected $segment;
    public function __construct() {
        parent::__construct();
        $this->common_model = new CommonModel();
        $this->Form_validation_model = new FormValidationModel();
        $this->UploadDocs_model = new UploadDocsModel();
        $this->segment = service('uri');
    }

    public function _remap($param = NULL) {
        $uri = service('uri'); // Get the URI object
        if ($uri->getTotalSegments() >= 3) {
            $this->index($uri->getSegment(3));
        } else {
            $this->index(NULL);
        }
        if ($param == 'index') {
            echo "Invalid Access !";
            exit(0);
        }
        elseif($param =='showDeletedFiles')
        {
            $doc_id=$this->segment->getSegment(4);
            $this->showDeletedFiles($doc_id);
        }else {
            $this->index($param);
        }
    }

    public function index($doc_id) {
        $client_ip=getClientIP();
        if($client_ip)
        {
            $ip_address = explode(".", $client_ip);
            //if(($ip_address[0]==10 )  && ($ip_address[1]==40 || $ip_address[1]==25))
            if(($ip_address[0]==10)  && ($ip_address[1]==40 || $ip_address[1]==249 || $ip_address[1]==25 || $ip_address[1]==26 || $ip_address[1]==100))
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
    public function showDeletedFiles($doc_id) {
        $client_ip=getClientIP();
        if($client_ip)
        {
            $ip_address = explode(".", $client_ip);
            //if(($ip_address[0]==10 )  && ($ip_address[1]==40 || $ip_address[1]==25))
            if(($ip_address[0]==10)  && ($ip_address[1]==40 || $ip_address[1]==249 || $ip_address[1]==25 || $ip_address[1]==26 || $ip_address[1]==100))
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
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT,USER_ADMIN,USER_EFILING_ADMIN);
        $allowed_users_array1 = array(JAIL_SUPERINTENDENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
    }

}
