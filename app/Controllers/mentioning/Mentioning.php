<?php
namespace App\Controllers;
class Mentioning extends BaseController{

    public function __construct() {
        parent::__construct();
        $this->load->model('common/Common_model');
        $this->load->model('mentioning/Get_details_model');
    }

    public function index() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }
        $data['app']='E-Mentioning';
        $this->load->view('templates/header');
        $this->load->view('mentioning/listing', $data);
        $this->load->view('templates/footer');
    }
}
