<?php
namespace App\Controllers;

class Contact extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('contact_us/Contactus_model');
    }

    public function index() {
        redirect('login');
        exit(0);
    }

    public function district_admin_contact_details() {
        if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_ADMIN) {
            $this->load->view('templates/admin_header');
            $data['result_data'] = $this->Contactus_model->get_district_admin_contact_results();
            $this->load->view('contact_us/admin_contact_view', $data);
            $this->load->view('templates/footer');
        }
    }

    public function super_admin_contact_details() {

        if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_DISTRICT_ADMIN) {
            $this->load->view('templates/admin_header');
            $data['result_data'] = $this->Contactus_model->get_super_admin_contact_details();
            $this->load->view('contact_us/admin_contact_view', $data);
            $this->load->view('templates/footer');
        }
    }

    public function master_admin_contact_details() {

        if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN) {
            $this->load->view('templates/admin_header');
            $data['result_data'] = $this->Contactus_model->get_master_admin_contact_details();
            $this->load->view('contact_us/admin_contact_view', $data);
            $this->load->view('templates/footer');
        }
    }

}

?>