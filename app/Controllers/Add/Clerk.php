<?php

namespace App\Controllers\Add;

use App\Controllers\BaseController;
use App\Models\Common\CommonLoginModel;
use App\Models\Common\CommonModel;
use App\Models\Clerk\ClerkModel;
use App\Libraries\webservices\Efiling_webservices;

class Clerk extends BaseController {

    protected $Common_model;
    protected $Clerk_model;
    protected $Common_login_model;
    protected $efiling_webservices;

    public function __construct() {
        parent::__construct();
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        } else{
            is_user_status();
        }
        $this->Common_model = new CommonModel();
        $this->Clerk_model = new ClerkModel();
        $this->Common_login_model = new CommonLoginModel();
        $this->efiling_webservices = new Efiling_webservices();
    }

    public function index() {
        $this->clerk();
    }

    public function clerk() {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE && $_SESSION['login']['ref_m_usertype_id'] != USER_CLERK) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $data['clerk_user_list'] = $this->Clerk_model->get_clerk_users($_SESSION['login']['id']);
        $data['states'] = $this->Clerk_model->get_states_list();
        $this->render('clerk.add_clerk_view', $data);
    }

    public function add_Clerk() {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE && $_SESSION['login']['ref_m_usertype_id'] != USER_CLERK) {
            return redirect()->to(base_url('/'));
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
            $_SESSION['MSG'] = message_show("fail", 'Please Enter Valid Mobile Number.');
            return redirect()->to(base_url('add/clerk'));
        }
        if (!preg_match($email_pattern, $email)) {
            $_SESSION['MSG'] = message_show("fail", 'Please Enter Valid Email ID.');
            return redirect()->to(base_url('add/clerk'));
        }
        $check_email_already_present = $this->Common_login_model->check_email_already_present(strtoupper($email));
        $check_mobno_already_present = $this->Common_login_model->check_mobno_already_present(strtoupper($mobile));
        $check_usrid_already_present = $this->Common_login_model->check_usrid_already_present(strtoupper($userid));
        if ($check_email_already_present) {
            $_SESSION['MSG'] = message_show("fail", 'Same Email Id already Registered.');
            return redirect()->to(base_url('add/clerk'));
        }
        if ($check_mobno_already_present) {
            $_SESSION['MSG'] = message_show("fail", 'Same Mobile Number already Registered.');
            return redirect()->to(base_url('add/clerk'));
        }
        if ($check_usrid_already_present) {
            $_SESSION['MSG'] = message_show("fail", 'Same User ID already Registered.');
            return redirect()->to(base_url('add/clerk'));
        }
        if (preg_match('/[^0-9]/i', $ref_m_states_id[0])) {
            $_SESSION['MSG'] = message_show("fail", 'State is required ');
            return redirect()->to(base_url('add/clerk'));
        }
        if (preg_match('/[^0-9]/i', $district_id[0])) {
            $_SESSION['MSG'] = message_show("fail", 'District is required.');
            return redirect()->to(base_url('add/clerk'));
        }
        $validate_name = validate_names($address, TRUE, 4, 150, 'Address');
        if ($validate_name['response'] == FALSE) {
            $_SESSION['MSG'] = message_show("fail", htmlentities($validate_name['msg']['field_name'], ENT_QUOTES));
            return redirect()->to(base_url('add/clerk'));
        }
        if (preg_match("/[^0-9a-zA-Z\s.:,-_\/ ]/i", $city) || $city == NULL) {
            $_SESSION['MSG'] = message_show("fail", 'City  is required and can contain only characters,numbers,spaces and :,.-_/');
            return redirect()->to(base_url('add/clerk'));
        }
        if ($_POST['pincode'] != NULL) {
            $pincode = $_POST['pincode'];
            if (preg_match('/[^0-9]/i', $_POST['pincode']) || (strlen($_POST['pincode']) != 6)) {
                $_SESSION['MSG'] = message_show("fail", ' Pincode is required and can have only numbers of 6 digit length.');
                return redirect()->to(base_url('add/clerk'));
            }
        } else{
            $pincode = 0;
        }
        if ($ref_m_states_id == '') {
            $ref_m_states_id = 0;
        } else{
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
            'm_address1' => $address,
            'm_state_id' => $ref_m_states_id[0],
            'm_district_id' =>$district_id[0],
            'm_pincode' => $pincode,
            'bar_reg_no' => $_SESSION['login']['bar_reg_no'],
            'ref_m_id_proof_type' => 0,
            'create_ip' => $_SERVER['REMOTE_ADDR'],
            'dob' => '0',
            'm_city' => $city,
            'update_ip' => $_SERVER['REMOTE_ADDR'],
            'admin_role' => '0',
            'login_ip' => '0',
        );
        $result = $this->Clerk_model->add_clerk_user($data, $clerk_name);        
        if ($result) {
            $user_name = $userid;
            send_mail_msg($email, 'Your eFiling Username', 'Your eFiling  username :' . strtoupper($userid) );
            $sentSMS = 'Your eFiling Password ' . $login_pass . 'The login username has been sent to your registered email account. - Supreme Court of India';
            send_mobile_sms($mobile, $sentSMS,SCISMS_Login_Password_To_Email);            
            $_SESSION['MSG'] = message_show("success", 'Clerk Added Successfully');
            return redirect()->to(base_url('add/clerk'));
        } else{
            $_SESSION['MSG'] = message_show("fail", 'Something Wrong, Please try again later!');
            return redirect()->to(base_url('add/clerk'));
        }
        $this->render('clerk.add_clerk_view', $data);
    }

    public function edit($id) {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE && $_SESSION['login']['ref_m_usertype_id'] != USER_CLERK) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $id = url_decryption($id);
        $data['clerk_user_list'] = $this->Clerk_model->get_clerk_users($_SESSION['login']['id']);
        $data['states'] = $this->Clerk_model->get_states_list();
        if ($id) {
            $data['clerkData'] = $this->Clerk_model->get_clerk_details($id);
            $data['states'] = $this->Clerk_model->get_states_list();
            $data['district'] = $this->Clerk_model->get_districts_list($data['clerkData'][0]['m_state_id']);
            $this->render('clerk.add_clerk_view', $data);
        }
    }

    public function action($action_id, $status) {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE && $_SESSION['login']['ref_m_usertype_id'] != USER_CLERK) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (empty($action_id) || empty($status)) {
            $_SESSION['MSG'] = message_show("fail", 'Something Wrong! Please Try again letter.');
            return redirect()->to(base_url('add/clerk'));
        }
        $data['clerk_user_list'] = $this->Clerk_model->get_clerk_users($_SESSION['login']['id']);
        $action_id = url_decryption($action_id);
        $status = url_decryption(trim($status));
        if (preg_match('/[^0-9]/i', $action_id)) {
            $_SESSION['MSG'] = message_show("fail", 'Something Wrong! Please Try again letter.');
            return redirect()->to(base_url('add/clerk'));
        }
        $result = $this->Common_model->action_taken($action_id, $status);
        if ($result) {
            $_SESSION['MSG'] = message_show("success", 'Status Updated Successfully.');
            return redirect()->to(base_url('add/clerk'));
        } else{
            $_SESSION['MSG'] = message_show("fail", 'Something Wrong! Please Try again letter.');
            return redirect()->to(base_url('add/clerk'));
        }
    }

    public function update_clerk() {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE && $_SESSION['login']['ref_m_usertype_id'] != USER_CLERK) {
            return redirect()->to(base_url('/'));
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
            $_SESSION['MSG'] = message_show("fail", 'Please Enter Valid Mobile Number.');
            return redirect()->to(base_url('add/clerk'));
        }
        if (!preg_match($email_pattern, $email)) {
            $_SESSION['MSG'] = message_show("fail", 'Please Enter Valid Email ID.');
            return redirect()->to(base_url('add/clerk'));
        }
        $check_email_present = $this->Clerk_model->check_email_present(strtoupper($email), $clerk_id);
        $check_mobno_present = $this->Clerk_model->check_mobno_present($mobile, $clerk_id);
        if ($check_email_present) {
            $_SESSION['MSG'] = message_show("fail", 'Same Email Id already Registered.');
            return redirect()->to(base_url('add/clerk'));
        }
        if ($check_mobno_present) {
            $_SESSION['MSG'] = message_show("fail", 'Same Mobile Number already Registered.');
            return redirect()->to(base_url('add/clerk'));
        }
        if (preg_match('/[^0-9]/i', $ref_m_states_id[0])) {
            $_SESSION['MSG'] = message_show("fail", 'State is required.');
            return redirect()->to(base_url('add/clerk'));
        }
        if (preg_match('/[^0-9]/i', $district_id[0])) {
            $_SESSION['MSG'] = message_show("fail", 'District is required.');
            return redirect()->to(base_url('add/clerk'));
        }
        $validate_name = validate_names($address, TRUE, 4, 150, 'Address');
        if ($validate_name['response'] == FALSE) {
            $_SESSION['MSG'] = message_show("fail", htmlentities($validate_name['msg']['field_name'], ENT_QUOTES));
            return redirect()->to(base_url('add/clerk'));
        }
        if (preg_match("/[^0-9a-zA-Z\s.:,-_\/ ]/i", $city) || $city == NULL) {
            $_SESSION['MSG'] = message_show("fail", 'City  is required and can contain only characters,numbers,spaces and :,.-_/');
            return redirect()->to(base_url('add/clerk'));
        }
        if ($_POST['pincode'] != NULL) {
            $pincode = $_POST['pincode'];
            if (preg_match('/[^0-9]/i', $_POST['pincode']) || (strlen($_POST['pincode']) != 6)) {
                $_SESSION['MSG'] = message_show("fail", 'Pincode is required and can have only numbers of 6 digit length.');
                return redirect()->to(base_url('add/clerk'));
            }
        } else{
            $pincode = 0;
        }
        if ($ref_m_states_id == '') {
            $ref_m_states_id = 0;
        } else{
            $ref_m_states_id = $ref_m_states_id;
        }
        $data = array(
            'first_name' => $clerk_name,
            'moblie_number' => $mobile,
            'emailid' => $email,
            'm_address1' => $address,
            'm_pincode' => $pincode,
            'm_city' => $city,
            'update_ip' => $_SERVER['REMOTE_ADDR'],
            'm_state_id' => $ref_m_states_id[0],
            'm_district_id' =>$district_id[0],
        );
        $result = $this->Clerk_model->update_clerk_user($clerk_id, $data, $clerk_name);
        if ($result) {
            $_SESSION['MSG'] = message_show("success", 'Clerk Updated Successfully.');
            return redirect()->to(base_url('add/clerk'));
        } else{
            $_SESSION['MSG'] = message_show("fail", 'Something Wrong! Please Try again letter.');
            return redirect()->to(base_url('add/clerk'));
        }
        $this->render('clerk.add_clerk_view', $data);
    }

    public function get_district_list() {
        $state_array = explode('#$', url_decryption(escape_data($_POST['state_id'])));
        $state_id = $state_array[0];
        $result= $this->Clerk_model->get_districts_list($state_id);
        if (count($result) > 0) {
            echo '<option value=""> Select District </option>';
            foreach ($result  as $district) {
                echo '<option  value="' . htmlentities(url_encryption($district->dist_code . '#$' . $district->dist_name), ENT_QUOTES) . '">' . htmlentities(strtoupper($district->dist_name), ENT_QUOTES) . '</option>';
            }
        } else{
            echo '<option value=""> Select District </option>';
        }
    }
}