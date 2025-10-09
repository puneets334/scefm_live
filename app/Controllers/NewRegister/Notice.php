<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notice extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper();
        //load the login model
        $this->load->model('assistance/Notice_model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    public function index() {
        if (empty($this->uri->segment(3))) {
            if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN) {

                redirect('admin');
            } if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_DEPARTMENT) {

                redirect('assistance/notice/view');
            } elseif ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON || $_SESSION['login']['ref_m_usertype_id'] == USER_PDE || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK || $_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT) {
                redirect('dashboard');
            }
        }
    }

    public function view($id = NULL) {

//        if (($_SESSION['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN)) {
//            redirect('login');
//            exit(0);
//        }
        if (!in_array($this->session->userdata['login']['ref_m_usertype_id'],array(USER_SUPER_ADMIN,USER_ADMIN))) {
            redirect('login');
            exit(0);
        }
        if ($id != NULL) {
            $id = (int) url_decryption($id);
            $data['get_data'] = $this->Notice_model->get_notice_by_id($id, $_SESSION['login']['id']);
        }
        $data['result'] = $this->Notice_model->notice_list();
        $this->load->view('templates/admin_header');
        $this->load->view('assistance/notice_view', $data);
        $this->load->view('templates/footer');
    }

    public function add_notice() {
//        if ($_SESSION['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
//            redirect('login');
//            exit(0);
//        }
        if (!in_array($this->session->userdata['login']['ref_m_usertype_id'],array(USER_SUPER_ADMIN,USER_ADMIN))) {
            redirect('login');
            exit(0);
        }
        $InputArray = $this->input->post();
        $validate_name = validate_names($_POST['notice_title'], TRUE, 3, 99, 'Title');
        if ($validate_name['response'] == FALSE) {
            $_SESSION['MSG'] = message_show("fail", 'Enter Valid Title!');
            redirect('assistance/notice/view');
            exit(0);
        }

        if (!empty($_FILES['notice_doc']['name'])) {
            if ($msg = isValidPDF('notice_doc')) {
                $this->session->set_flashdata('MSG', $msg);
                redirect('assistance/notice');
            }

            $file_name = time() . rand() . ".pdf";
            $file_uploaded_path = base_url('Notice/' . $file_name);
        } else {
            $_SESSION['MSG'] = message_show("fail", 'Please upload File !');
            redirect('assistance/notice/view');
            exit(0);
        }

        $insert_array = array(
            'news_title' => $InputArray['notice_title'],
            'is_active' => TRUE,
            'file_name' => $file_name,
            'file_uploaded_path' => $file_uploaded_path,
            'created_by' => $_SESSION['login']['id'],
            'create_by_ip' => getClientIP(),
            'deactive_date' => date('Y-m-d'),
            'for_state_id' => $_SESSION['login']['admin_for_id'],
            'state_name' => $_SESSION['login']['state_name'],
            'is_notice' => TRUE
        );
        $result = $this->Notice_model->insert_notice($insert_array, $_FILES['notice_doc']['tmp_name']);

        if ($result) {
            $this->session->set_flashdata('MSG', '<div class="alert alert-success text-center">Notice Added Successfully</div>');
            redirect('assistance/notice/view');
        } else {
            $this->session->set_flashdata('MSG', '<div class="alert alert-danger text-center">Something Wrong Please try Again.</div>');
            redirect('assistance/notice/view');
        }
    }

    public function edit_notice($id) {
        /*if ($_SESSION['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            redirect('login');
            exit(0);
        }*/
        if (!in_array($this->session->userdata['login']['ref_m_usertype_id'],array(USER_SUPER_ADMIN,USER_ADMIN))) {
            redirect('login');
            exit(0);
        }

        $get_data = $this->Notice_model->get_notice_by_id($id, $_SESSION['login']['id']);
        $InputArray = $this->input->post();

        $this->form_validation->set_rules('notice_title', 'Title', 'required');


        if (empty($get_data)) {
            $this->session->set_flashdata('MSG', '<div class="alert alert-danger text-danger">Invalid Request</div>');
            redirect('assistance/notice/view');
        }

        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->error_array();
            $this->session->set_flashdata('notice_title', '<div class="text-danger">' . $error['notice_title'] . '</div>');
            redirect('assistance/notice/view' . url_encryption($id));
        } else {

            $config['upload_path'] = 'Notice/';
            $config['allowed_types'] = 'jpeg|jpg|png|pdf';
            $config['max_size'] = UPLOADED_FILE_SIZE;

            $this->load->library('upload', $config);
            $is_file_uploaded = FALSE;

            if (!empty($_FILES['notice_doc']['name'])) {

                if ($msg = isValidPDF('notice_doc')) {
                    $this->session->set_flashdata('MSG', $msg);
                    redirect('noitce/view');
                }
                $file_name = time() . rand() . ".pdf";
            } else {
                $file_name = $get_data[0]['file_name'];
            }

            $update_array = array(
                'news_title' => $InputArray['notice_title'],
                'is_active' => TRUE,
                'file_name' => $file_name,
                'file_uploaded_path' => base_url('Notice/' . $file_name),
                'updated_by' => $_SESSION['login']['id'],
                'update_date' => date("Y-m-d H:i:s"),
                'update_by_ip' => getClientIP(),
                'for_state_id' => $_SESSION['login']['enrolled_state_id'],
                'state_name' => $_SESSION['login']['state_name']
            );

            $result = $this->Notice_model->update_notice_by_id($id, $_SESSION['login']['id'], $update_array, $_FILES['notice_doc']['tmp_name']);
            if ($result) {
                $this->session->set_flashdata('MSG', '<div class="alert alert-success text-center">Notice Updated Successfully</div>');
                redirect('assistance/notice/view');
            } else {
                $this->session->set_flashdata('MSG', '<div class="alert alert-danger text-center">Something Wrong Please try Again.</div>');
                redirect('assistance/notice/view/' . $id);
            }
        }
    }

    public function deactive_notice($id) {
        /*if ($_SESSION['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            redirect('login');
            exit(0);
        }*/
        if (!in_array($this->session->userdata['login']['ref_m_usertype_id'],array(USER_SUPER_ADMIN,USER_ADMIN))) {
            redirect('login');
            exit(0);
        }
        $id = (int) url_decryption($id);
        $get_data = $this->Notice_model->get_notice_by_id($id, $_SESSION['login']['id']);
        if ($get_data[0]['is_active'] == 't') {
            $message = "Deactivated";
            $update_array = array(
                'deactive_date' => date('Y-m-d'),
                'is_active' => FALSE
            );
        } else {
            $message = "Activated";
            $update_array = array(
                'is_active' => TRUE
            );
        }
        $result = $this->Notice_model->update_status_notice_by_id($id, $_SESSION['login']['id'], $update_array);
        if ($result) {
            $this->session->set_flashdata('MSG', '<div class="alert alert-success text-center">Notice ' . $message . ' Successfully</div>');
            redirect('assistance/notice/view');
        } else {
            $this->session->set_flashdata('MSG', '<div class="alert alert-danger text-center">Something Wrong Please try Again.</div>');
            redirect('assistance/notice/view/' . $id);
        }
    }

    function notice_pdf($id) {
        $id = url_decryption($id);

        if (!$id) {
            $this->session->set_flashdata('MSG', '<div class="alert alert-danger text-center">Invalid Request.</div>');
            redirect('assistance/notice/view');
        } else {
            $result = $this->notice_model->view_notice_pdf($id);

            if (isset($result) && !empty($result)) {
                $file_name = $result[0]['file_name'];
                $file_uploaded_path = $result[0]['file_uploaded_path'];
                header("Content-Type: application/pdf");
                header("Content-Disposition:inline;filename=$file_name" . '.pdf');
                header('Content-Transfer-Encoding: binary');
                header('Accept-Ranges: bytes');
                @readfile($file_uploaded_path);
            }
        }
    }

    public function listview() {

       // if (($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE) || ($_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON) || ($_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) || $_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT) {
            $data['notice_list'] = $this->Notice_model->notice_list_by_category();
            $this->load->view('templates/user_header');
            $this->load->view('assistance/notice_form', $data);
            $this->load->view('templates/footer');
//        } else {
//            redirect('dashboard');
//            exit(0);
//        }
    }

}
