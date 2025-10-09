<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clerk extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Clerk_model');
        $this->load->model('register/Register_model');
        $this->load->model('adminDashboard/AdminDashboard_model');
        $this->load->model('common/Common_login_model');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('functions');
        $this->load->library('webservices/efiling_webservices');
        $this->load->database();
    }

    public function index() {
        $this->clerk();
    }

    public function clerk() {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE && $_SESSION['login']['ref_m_usertype_id'] != USER_CLERK) {
            redirect('login');
            exit(0);
        }

        $data['clerk_user_list'] = $this->Clerk_model->get_clerk_users($_SESSION['login']['id']);
        $data['states'] = $this->efiling_webservices->getOpenAPIState();
        $this->load->view('templates/header');
        $this->load->view('advocate/add_clerk_view', $data);
        $this->load->view('templates/footer');
    }

    public function add_Clerk() {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE && $_SESSION['login']['ref_m_usertype_id'] != USER_CLERK) {
            redirect('login');
            exit(0);
        }

        $clerk_id = url_decryption($_POST['clerk_id']);
        $email_pattern = "/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/";
        $clerk_name = trim(strtoupper($_POST['clerk_name']));
        $mobile = trim($_POST['mobile_no']);
        $email = trim(strtoupper($_POST['email_id']));
        $user_id = explode('@', $email);
        $userid = strtoupper($user_id[0]);
        $ref_m_usertype_id = USER_CLERK;
        $district_id = explode("#$", url_decryption($_POST['district']));
        $random_password = 'Clerk@1234'; //time().rand('10,100');
        $login_pass = hash('sha256', $random_password);
        $address = trim($_POST['address']);
        $ref_m_states_id = explode("#$", url_decryption($_POST['states_id']));
        $city = trim(strtoupper($_POST['city']));


        if (preg_match('/[^0-9]/i', $mobile) || strlen($mobile) != 10) {
            $this->session->set_flashdata('message', "<div class='text-danger'>Please Enter Valid Mobile Number.</div>");
            redirect('clerk');
        }
        if (!preg_match($email_pattern, $email)) {
            $this->session->set_flashdata('message', "<div class='text-danger'>Please Enter Valid Email ID.</div>");
            redirect('clerk');
        }

        $check_email_already_present = $this->Common_login_model->check_email_already_present(strtoupper($email));
        $check_mobno_already_present = $this->Common_login_model->check_mobno_already_present(strtoupper($mobile));
        $check_usrid_already_present = $this->Register_model->check_usrid_already_present(strtoupper($userid));

        if ($check_email_already_present) {
            $this->session->set_flashdata('message', "<div class='text-danger'>Same Email Id already Registered.</div>");
            redirect('clerk');
        }
        if ($check_mobno_already_present) {
            $this->session->set_flashdata('message', "<div class='text-danger'>Same Mobile Number already Registered. </div>");
            redirect('clerk');
        }
        if ($check_usrid_already_present) {
            $this->session->set_flashdata('message', "<div class='text-danger'>Same User ID already Registered</div>");
            redirect('clerk');
        }

        if (preg_match('/[^0-9]/i', $ref_m_states_id[0])) {
            $this->session->set_flashdata('message', "<div class='text-danger'>State is required and can have only numbers!</div>");
            redirect('clerk');
        }
        if (preg_match('/[^0-9]/i', $district_id[0])) {
            $this->session->set_flashdata('message', "<div class='text-danger'>District is required and can have only numbers!</div>");
            redirect('clerk');
        }
        $validate_name = validate_names($address, TRUE, 4, 150, 'Address');
        if ($validate_name['response'] == FALSE) {
            $this->session->set_flashdata('message', "<div class='text-danger'>" . htmlentities($validate_name['msg']['field_name'], ENT_QUOTES) . "</div>");
            redirect('clerk');
        }
        if (preg_match("/[^0-9a-zA-Z\s.:,-_\/ ]/i", $city) || $city == NULL) {
            $this->session->set_flashdata('message', "<div class='text-danger'>City  is required and can contain only characters,numbers,spaces and :,.-_/</div>");
            redirect('clerk');
        }
        if ($_POST['pincode'] != NULL) {
            $pincode = $_POST['pincode'];
            if (preg_match('/[^0-9]/i', $_POST['pincode']) || (strlen($_POST['pincode']) != 6)) {
                $this->session->set_flashdata('message', "<div class='text-danger'> Pincode is required and can have only numbers of 6 digit length </div>");
                redirect('clerk');
            }
        } else {
            $pincode = 0;
        }if ($ref_m_states_id == '') {
            $ref_m_states_id = 0;
        } else {
            $ref_m_states_id = $ref_m_states_id;
        }

        $data = array(
            'first_name' => $clerk_name,
            'moblie_number' => $mobile,
            'emailid' => $email,
            'userid' => $userid,
            'ref_m_usertype_id' => $ref_m_usertype_id,
            'password' => $login_pass,
            'last_name' => 'NA',
            'gender' => 0,
            'address1' => $address,
            'ref_m_states_id' => $ref_m_states_id[0],
            'pincode' => $pincode,
            'bar_reg_no' => $_SESSION['login']['bar_reg_no'],
            'ref_m_id_proof_type' => 0,
            'created_by_ip' => getClientIP(),
            'dob' => '0',
            'city' => $city,
            'updated_by_ip' => getClientIP(),
            'is_active' => TRUE,
            'case_flag' => $_POST['case_file'],
            'doc_flag' => $_POST['doc_file'],
            'njdg_st_id' => $ref_m_states_id[0],
            'njdg_st_name' => $ref_m_states_id[1],
            'njdg_dist_id' => $district_id[0],
            'njdg_dist_name' => $district_id[1]
        );

        $result = $this->Clerk_model->add_clerk_user($data,$clerk_name);
        if ($result) {
            $this->session->set_flashdata('message', "<div class='text-success'>Clerk Added Successfully.</div>");

            $user_name = $userid;
            send_mail_msg($email, 'Your eFiling Username', 'Your eFiling  </br>URL<b>' . base_url() . '</b>. </br>username : <b>' . strtoupper($userid) . '</b> ', $clerk_name);
            $sentSMS = 'Your eFiling Password ' . $login_pass . 'The login username has been sent to your registered email account. - Supreme Court of India';
            send_mobile_sms($mobile_no, $sentSMS,SCISMS_Login_Password_To_Email);
            redirect('clerk');
        } else {
            $this->session->set_flashdata('message', "<div class='text-danger'>Something Wrong, Please try again later.</div>");
            redirect('clerk');
        }

        $this->load->view('templates/header');
        $this->load->view('advocate/add_clerk_view', $data);
        $this->load->view('templates/footer');
    }

    public function edit($id) { 
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE && $_SESSION['login']['ref_m_usertype_id'] != USER_CLERK) {
            redirect('login');
            exit(0);
        }
        $id = url_decryption($id);
        $data['clerk_user_list'] = $this->Clerk_model->get_clerk_users($_SESSION['login']['id']);
        $data['states'] = $this->efiling_webservices->getOpenAPIState();

        if ($id) {

            $data['clerkData'] = $this->Clerk_model->get_clerk_details($id);
            $data['states'] = $this->efiling_webservices->getOpenAPIState();
            $data['district'] = $this->efiling_webservices->getOpenAPIDistrict($data['clerkData'][0]['njdg_st_id']);

            $this->load->view('templates/header');
            $this->load->view('advocate/add_clerk_view', $data);
            $this->load->view('templates/footer');
        }
    }

    public function action($action_id, $status) {

        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE && $_SESSION['login']['ref_m_usertype_id'] != USER_CLERK) {
            redirect('login');
            exit(0);
        }
        if (empty($action_id) || empty($status)) {
            $this->session->set_flashdata('message', "<div class='text-success'>Something Wrong! Please Try again letter.</div>");
            redirect('clerk');
        }
        $data['clerk_user_list'] = $this->Clerk_model->get_clerk_users($_SESSION['login']['id']);
        $action_id = url_decryption($action_id);
        $status = url_decryption(trim($status));
        if (preg_match('/[^0-9]/i', $action_id)) {
            $this->session->set_flashdata('message', "<div class='text-danger'>Something Wrong! Please Try again letter.</div>");
            redirect('clerk');
        }

        $result = $this->AdminDashboard_model->action_taken($action_id, $status);
        if ($result) {
            $this->session->set_flashdata('message', "<div class='text-success'>Status Updated Successfully .</div>");
            redirect('clerk');
        } else {
            $this->session->set_flashdata('message', "<div class='text-danger'>Something Wrong! Please Try again letter.</div>");
            redirect('clerk');
        }
    }

    public function update_clerk() {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE && $_SESSION['login']['ref_m_usertype_id'] != USER_CLERK) {
            redirect('login');
            exit(0);
        }
        $clerk_id = url_decryption($_POST['clerk_id']);

        $email_pattern = "/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/";
        $clerk_name = trim(strtoupper($_POST['clerk_name']));
        $mobile = trim($_POST['mobile_no']);
        $email = trim(strtoupper($_POST['email_id']));
        $address = trim($_POST['address']);
        $ref_m_states_id = explode("#$", url_decryption($_POST['states_id']));
        $city = trim(strtoupper($_POST['city']));
        $district_id = explode("#$", url_decryption($_POST['district']));

        if (preg_match('/[^0-9]/i', $mobile) || strlen($mobile) != 10) {
            $this->session->set_flashdata('message', "<div class='text-danger'>Please Enter Valid Mobile Number.</div>");
            redirect('clerk');
        }
        if (!preg_match($email_pattern, $email)) {
            $this->session->set_flashdata('message', "<div class='text-danger'>Please Enter Valid Email ID.</div>");
            redirect('clerk');
        }

        $check_email_present = $this->Clerk_model->check_email_present(strtoupper($email), $clerk_id);
        $check_mobno_present = $this->Clerk_model->check_mobno_present($mobile, $clerk_id);

        if ($check_email_present) {
            $this->session->set_flashdata('message', "<div class='text-danger'>Same Email Id already Registered.</div>");
            redirect('clerk');
        }
        if ($check_mobno_present) {
            $this->session->set_flashdata('message', "<div class='text-danger'>Same Mobile Number already Registered. </div>");
            redirect('clerk');
        }

        if (preg_match('/[^0-9]/i', $ref_m_states_id[0])) {
            $this->session->set_flashdata('message', "<div class='text-danger'>State is required and can have only numbers!</div>");
            redirect('clerk');
        }
        if (preg_match('/[^0-9]/i', $district_id[0])) {
            $this->session->set_flashdata('message', "<div class='text-danger'>District is required and can have only numbers!</div>");
            redirect('clerk');
        }
        $validate_name = validate_names($address, TRUE, 4, 150, 'Address');
        if ($validate_name['response'] == FALSE) {
            $this->session->set_flashdata('message', "<div class='text-danger'>" . htmlentities($validate_name['msg']['field_name'], ENT_QUOTES) . "</div>");
            redirect('clerk');
        }
        if (preg_match("/[^0-9a-zA-Z\s.:,-_\/ ]/i", $city) || $city == NULL) {
            $this->session->set_flashdata('message', "<div class='text-danger'>City  is required and can contain only characters,numbers,spaces and :,.-_/</div>");
            redirect('clerk');
        }
        if ($_POST['pincode'] != NULL) {
            $pincode = $_POST['pincode'];
            if (preg_match('/[^0-9]/i', $_POST['pincode']) || (strlen($_POST['pincode']) != 6)) {
                $this->session->set_flashdata('message', "<div class='text-danger'> Pincode is required and can have only numbers of 6 digit length </div>");
                redirect('clerk');
            }
        } else {
            $pincode = 0;
        } if ($ref_m_states_id == '') {
            $ref_m_states_id = 0;
        } else {
            $ref_m_states_id = $ref_m_states_id;
        }


        $data = array(
            'first_name' => $clerk_name,
            'moblie_number' => $mobile,
            'emailid' => $email,
            'address1' => $address,
            'pincode' => $pincode,
            'city' => $city,
            'updated_by_ip' => getClientIP(),
            'case_flag' => $_POST['case_file'],
            'doc_flag' => $_POST['doc_file'],
            'njdg_st_id' => $ref_m_states_id[0],
            'njdg_st_name' => $ref_m_states_id[1],
            'njdg_dist_id' => $district_id[0],
            'njdg_dist_name' => $district_id[1]
        );

        $result = $this->Clerk_model->update_clerk_user($clerk_id, $data,$clerk_name);
        if ($result) {
            $this->session->set_flashdata('message', "<div class='text-success'>Clerk Updated Successfully.</div>");
            redirect('clerk');
        } else {
            $this->session->set_flashdata('message', "<div class='text-danger'>Something Wrong, Please try again later.</div>");
            redirect('clerk');
        }
        $this->load->view('templates/header');
        $this->load->view('Advocate/add_clerk_view', $data);
        $this->load->view('templates/footer');
    }

    public function get_district_list() {

        $state_array = explode('#$', url_decryption(escape_data($_POST['state_id'])));
        $state_id = $state_array[0];
        if (isset($state_id) && preg_match("/^[0-9]*$/", $state_id) && strlen($state_id) <= INTEGER_FIELD_LENGTH) {
            if (!empty($state_id)) {
                $result = $this->efiling_webservices->getOpenAPIDistrict($state_id);
                if (!empty($status->status_code)) {
                    echo 'ERROR_' . $status->status;
                    exit(0);
                } else {
                    if (count($result)) {
                        echo '<option value=""> Select District </option>';
                        foreach ($result as $dataRes) {
                            foreach ($dataRes->district as $district) {
                                echo '<option  value="' . htmlentities(url_encryption($district->dist_code . '#$' . $district->dist_name), ENT_QUOTES) . '">' . htmlentities(strtoupper($district->dist_name), ENT_QUOTES) . '</option>';
                            }
                        }
                    } else {
                        echo '<option value=""> Select District </option>';
                    }
                }
            } else {
                echo '<option value=""> Select District</option>';
            }
        } else {
            echo '<option value=""> Select District</option>';
        }
    }

}
