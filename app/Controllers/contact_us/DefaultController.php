<?php
namespace App\Controllers;

class DefaultController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('contact_us/Contactus_model');
    }

    public function index() {

//        $array_users = array(USER_ADVOCATE, USER_IN_PERSON, USER_PDE, USER_CLERK);
//        if (!in_array($this->session->userdata['login']['ref_m_usertype_id'], $array_users)) {
//            redirect('login');
//            exit(0);
//        }

        $data['result_data'] = $this->Contactus_model->get_contactus_results();
        $this->load->view('templates/header');
        $this->load->view('contact_us/contactus_view', $data);
        $this->load->view('templates/footer');
    }

    public function district_admin_contact_details() {

        $array_users = array(USER_ACTION_ADMIN, USER_MASTER_ADMIN, USER_ADMIN);
        if (!in_array($this->session->userdata['login']['ref_m_usertype_id'], $array_users)) {
            redirect('login');
            exit(0);
        }

        $this->load->view('templates/admin_header');
        $data['result_data'] = $this->Contactus_model->get_district_admin_contact_results();
        $this->load->view('contact_us/admin_contact_view', $data);
        $this->load->view('templates/footer');
    }

    public function super_admin_contact_details() {

        $array_users = array(USER_ACTION_ADMIN, USER_MASTER_ADMIN, USER_ADMIN, USER_DISTRICT_ADMIN);
        if (!in_array($this->session->userdata['login']['ref_m_usertype_id'], $array_users)) {
            redirect('login');
            exit(0);
        }

        $this->load->view('templates/admin_header');
        $data['result_data'] = $this->Contactus_model->get_super_admin_contact_details();
        $this->load->view('contact_us/admin_contact_view', $data);
        $this->load->view('templates/footer');
    }

    public function master_admin_contact_details() {

        $array_users = array(USER_ACTION_ADMIN);
        if (!in_array($this->session->userdata['login']['ref_m_usertype_id'], $array_users)) {
            redirect('login');
            exit(0);
        }

        $this->load->view('templates/admin_header');
        $data['result_data'] = $this->Contactus_model->get_master_admin_contact_details();
        $this->load->view('contact_us/admin_contact_view', $data);
        $this->load->view('templates/footer');
    }

}

?>