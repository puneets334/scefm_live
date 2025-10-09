<?php

namespace App\Controllers\DocumentIndex;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\Common\FormValidationModel;
use App\Models\DocumentIndex\DocumentIndexModel;
use App\Models\DocumentIndex\DocumentIndexSelectModel;

class ViewIndexItem extends BaseController
{
    protected $common_model;
    protected $Form_validation_model;
    protected $documentIndex_model;
    protected $DocumentIndex_Select_model;

    public function __construct()
    {
        parent::__construct();
        $this->common_model = new CommonModel();
        $this->Form_validation_model = new FormValidationModel();
        $this->documentIndex_model = new DocumentIndexModel();
        $this->DocumentIndex_Select_model = new DocumentIndexSelectModel();
    }

    public function index($doc_id)
    {
        $uri = service('uri');
        if ($uri->getTotalSegments() >= 3) {
            $doc_id = $uri->getSegment(3);
        }
        $this->check_login();
        $doc_id = url_decryption($doc_id);
        if (empty($doc_id) || !preg_match("/^[0-9]*$/", $doc_id)) {
            echo "Invalid Access!";
            exit(0);
        }
        $doc_details = $this->DocumentIndex_Select_model->get_index_item_file($doc_id);
        $file_partial_path = $doc_details[0]['file_path'];
        $file_name = $file_partial_path . $doc_details[0]['file_name'];
        $doc_title = $_SESSION['efiling_details']['efiling_no'] . '_' . str_replace(' ', '_', $doc_details[0]['file_name']) . '.pdf';
        if (file_exists($file_name)) {
            header("Content-Type: application/pdf");
            header("Content-Disposition:inline;filename = $doc_title");
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            @readfile($file_name);
            echo $file_name;
            exit(0);
        } else {
            echo "File does not !";
            exit(0);
        }
    }

    function check_login()
    {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_ADMIN, USER_ADMIN_READ_ONLY, USER_EFILING_ADMIN);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
    }
}
