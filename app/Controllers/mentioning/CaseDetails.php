<?php
namespace App\Controllers;

class CaseDetails extends BaseController {

    public function __construct() {
        parent::__construct();

        //$this->load->model('common/common_model');
        $this->load->model('mentioning/Get_details_model');
    }

    public function index() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }

        $stages_array = array('', Draft_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            redirect('mentioning/view');
            exit(0);
        }


        $this->load->view('templates/header');
        $this->load->view('mentioning/mentioning_view', $data);
        $this->load->view('templates/footer');
    }

}
