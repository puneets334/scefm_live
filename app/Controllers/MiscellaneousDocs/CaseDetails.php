<?php

namespace App\Controllers\MiscellaneousDocs;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\MiscellaneousDocs\GetDetailsModel;

class CaseDetails extends BaseController {

    protected $Common_model;
    protected $GetDetailsModel;

    public function __construct() {
        parent::__construct();
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        } else{
            is_user_status();
        }
        $this->GetDetailsModel = new GetDetailsModel();
        $this->Common_model = new CommonModel();
    }

    public function index() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, AMICUS_CURIAE_USER);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }
        $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            return redirect()->to(base_url('miscellaneous_docs/view'));
            exit(0);
        }
        /*$data['sc_case_type'] = $this->Dropdown_list_model->get_sci_case_type();
        $data['main_subject_cat'] = $this->Dropdown_list_model->get_main_subject_category();
        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $data['new_case_details'] = $this->Get_details_model->get_new_case_details($registration_id);
            $this->Get_details_model->get_case_table_ids($registration_id);
        }*/
        return $this->render('miscellaneous_docs.miscellaneous_docs_view');
    }

}