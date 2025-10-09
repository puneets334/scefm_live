<?php

namespace App\Controllers\Affirmation;

use App\Controllers\BaseController;

class ViewUnsignedCertificate extends BaseController {

    public function __construct() {
        parent::__construct();       
    }

    public function index() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK,JAIL_SUPERINTENDENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            $data['msg'] = 'Unauthorised Access !';
        }
        $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            $data['msg'] = 'Invalid Stage.';
        }
        if (!(isset($data['msg']) && !empty($data['msg']))) {
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $efiling_no = $_SESSION['efiling_details']['efiling_no'];
            $est_code = $_SESSION['estab_details']['estab_code'];
            $adv_unsigned_certificate_path = 'uploaded_docs/' . $est_code . '/' . $efiling_no . '/unsigned_pdf/' . $efiling_no . '_Advocate_UnsignedCertificate.pdf';
            $adv_unsigned_certificate_name = $efiling_no . '_Advocate_UnsignedCertificate.pdf';
            $data['pdf']['file_path'] = $adv_unsigned_certificate_path;
            $data['pdf']['file_name'] = $adv_unsigned_certificate_name;
        }
        $this->render('affirmation.view_certificate_pdf', $data);
    }
    
    public function get_certificate($est_code, $efiling_no){
        $data['pdf']['file_path'] = 'uploaded_docs/' . $est_code . '/' . $efiling_no . '/unsigned_pdf/' . $efiling_no . '_Advocate_UnsignedCertificate.pdf';
        $data['pdf']['file_name'] = $efiling_no . '_Advocate_UnsignedCertificate.pdf';
        $this->render('affirmation.view_certificate_pdf', $data);
    }

}