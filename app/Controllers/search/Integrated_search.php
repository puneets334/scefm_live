<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Integrated_search extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('webservices/efiling_webservices');
        $this->load->model('common/Dropdown_list_model');
        $this->load->model('admin/Supadmin_model');
    }

    public function index() {

        $data['state_list'] = $this->efiling_webservices->getOpenAPIState();
        $this->load->view('templates/admin_header');
        $this->load->view('admin/test_view', $data);
        $this->load->view('templates/footer');
    }

    public function establishment() {

        if ($this->session->userdata['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            redirect('login');
            exit(0);
        }

        $data['high_court_list'] = $this->Supadmin_model->get_superadmin_high_court_list();
        $data['state_list'] = $this->Supadmin_model->get_admin_states();
        $data['estab_details'] = $this->Supadmin_model->get_estab_details();
        $this->load->view('templates/admin_header');
        $this->load->view('admin/add_establishment_view', $data);
        $this->load->view('templates/footer');
    }

    public function state() {
        $state_list = $this->efiling_webservices->getOpenAPIState();
        if (count($state_list)) {
            echo '<option value=""> Select State</option>';
            foreach ($state_list as $dataRes) {
                foreach ($dataRes->state as $state) {
                    echo '<option  value="' . htmlentities(url_encryption($state->state_code . '#$' . $state->state_name . '#$' . $state->state_name), ENT_QUOTES) . '">' . $state->state_name . '</option>';
                }
            }
        } else {
            echo '<option value=""> Select District </option>';
        }
    }

}
