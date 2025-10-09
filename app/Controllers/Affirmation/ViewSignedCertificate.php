<?php

namespace App\Controllers\Affirmation;

use App\Controllers\BaseController;
use App\Models\Affirmation\AffirmationModel;

class ViewSignedCertificate extends BaseController {

    protected $Affirmation_model;

    public function __construct() {
        parent::__construct();
        $this->Affirmation_model = new AffirmationModel();
    }

    public function _remap($param = NULL) {
        $this->index($param);
    }

    public function index($doc_id) {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_ADMIN,JAIL_SUPERINTENDENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            $data['msg'] = 'Unauthorised Access !';
        }
        $doc_id = url_decryption(escape_data($doc_id));
        if (!(isset($data['msg']) && !empty($data['msg'])) && $doc_id != '') {
            $pdf = $this->Affirmation_model->view_signed_certificate($doc_id);
            $file_name = $pdf[0]->signed_pdf_file_name;
            $file_path = $pdf[0]->signed_pdf_partial_path . '/' . $pdf[0]->signed_pdf_file_name;
            $data['pdf']['file_path'] = $file_path;
            $data['pdf']['file_name'] = $file_name;
        } else {
            $data['msg'] = 'You are not allowed';
        }
        return $this->render('affirmation.view_certificate_pdf', $data);
    }

}