<?php

namespace App\Controllers\Affirmation;

use App\Controllers\BaseController;
use App\Models\Affirmation\AffirmationModel;

class Reset extends BaseController {

    protected $Affirmation_model;

    public function __construct() {
        parent::__construct();
        $this->Affirmation_model = new AffirmationModel();
    }

    public function index() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK,JAIL_SUPERINTENDENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            $_SESSION['MSG'] = message_show("fail", 'Unauthorised Access !');
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }
        $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            $_SESSION['MSG'] = message_show("fail", 'Invalid Stage.');
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
        $docid = explode('$$', url_decryption($_POST['docid']));
        if ($docid[1] != 'reset') {
            $_SESSION['MSG'] = message_show("fail", 'Invalid Input.');
            return redirect()->to(base_url('affirmatioin'));
            exit(0);
        }
        $docid = $docid[0];
        $type = $_POST['type'];
        if (!(isset($docid) && !empty($docid))) {
            $_SESSION['MSG'] = message_show("fail", '<div class="alert alert-danger text-center">Invalid Input.</div>');
            return redirect()->to(base_url('affirmation'));
            exit(0);
        }
        $type = ESIGNED_DOCS_BY_ADV;
        $update_data = array('is_data_valid' => FALSE);
        if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
            $breadcrumb_to_remove = NEW_CASE_AFFIRMATION;
        } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
            $breadcrumb_to_remove = MISC_BREAD_AFFIRMATION;
        } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
            $breadcrumb_to_remove = IA_BREAD_AFFIRMATION;
        } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MENTIONING) {
            $breadcrumb_to_remove = MEN_BREAD_AFFIRMATION;
        }elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_JAIL_PETITION) {
            $breadcrumb_to_remove = JAIL_PETITION_AFFIRMATION;
        }
        $this->Affirmation_model->reset_affirmation($docid, $_SESSION['efiling_details']['registration_id'], $update_data, $breadcrumb_to_remove);
        return true;
    }

}