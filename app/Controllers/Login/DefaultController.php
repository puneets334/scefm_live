<?php
namespace App\Controllers;

class DefaultController extends BaseController {

    public function __construct() {
        parent::__construct();

        $this->load->database();

        $this->load->model('login/Login_model');
        $this->load->library('slice');
        $this->load->library('webservices/efiling_webservices');

        $ref = isset($_SERVER['HTTP_REFERER']);
        $refData = parse_url($ref);
        if (isset($refData['host']) != $_SERVER['SERVER_NAME'] && isset($refData['host']) != NULL) {
            redirect("login");
            exit(0);
        }
        if (count($_POST) > 0) {
          //echo  $_POST['CSRF_TOKEN']; echo'='.$this->security->get_csrf_hash();  exit();
            //if ($_POST['CSRF_TOKEN'] != $_SESSION['csrf_to_be_checked']) {
           /* if ($_POST['CSRF_TOKEN'] != $this->security->get_csrf_hash()) {
                $this->session->set_flashdata('msg', 'CSRF TOKEN is expired !');
                redirect('login');
                exit(0);
            }*/
        }

        //$_SESSION['csrf_to_be_checked'] = $this->security->get_csrf_hash();
    }

    public function index() {

        log_message('CUSTOM', "Application accessed ");
        //check user already logged in or not
        if (isset($_SESSION['login']) && !empty($_SESSION['login'])) {
            $this->redirect_on_login();
        }

        //---Commented line are used for disable captcha----------------->

       /* $this->form_validation->set_rules('txt_username', 'User ID', 'required|trim|max_length[320]|is_required');
        $this->form_validation->set_rules('txt_password', 'Password','required|trim|max_length[128]|is_required');*/

        $this->form_validation->set_rules('txt_username', 'User ID', 'required|trim|min_length[2]|max_length[40]|is_required');
        $this->form_validation->set_rules('txt_password', 'Password','required|trim|min_length[9]|max_length[128]|is_required');
        $this->form_validation->set_rules('userCaptcha', 'Captcha', 'required|trim|is_required');//for responsive_variant


        //$this->form_validation->set_error_delimiters('<div class="uk-alert-danger">', '</div>');//for responsive_variant
         if ($this->form_validation->run() == FALSE) {

           // $data['captcha'] = get_new_captcha();

            //$this->session->set_userdata('captchaWord', $data['captcha']['word']);
            $this->session->set_userdata('login_salt', $this->generateRandomString());

            /*$this->load->view('login/login_header');
            $this->load->view('login/login_view', $data);
            $this->load->view('login/login_footer');*/
             $this->slice->view('responsive_variant.authentication.login');
        }
        else {

        if (isset($_POST['txt_username']) && !empty($_POST['txt_username']) && isset($_POST['txt_password']) && !empty($_POST['txt_password']) && isset($_POST['userCaptcha']) && !empty($_POST['userCaptcha'])) { //for responsive_variant
        //if (isset($_POST['txt_username']) && !empty($_POST['txt_username']) && isset($_POST['txt_password']) && !empty($_POST['txt_password'])) {


            $username = escape_data($_POST['txt_username']);
            $password = escape_data($_POST['txt_password']);

            $impersonatedUserAuthenticationMobileOtp = escape_data(@$_POST['impersonatedUserAuthenticationMobileOtp']);//for efiling_assistant
            $userCaptcha = escape_data($_POST['userCaptcha']);

            if ($username == NULL  || $password == NULL || preg_match('/[^A-Za-z0-9!@#$]/i', $password) || $userCaptcha == NULL || preg_match('/[^A-Za-z0-9]/i', $userCaptcha)) {
                log_message('CUSTOM', "Invalid username or password or Captcha!");
                $this->session->set_flashdata('msg', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a > <p style="text-align: center;">Invalid username or password or Captcha!</p> </div>');
                redirect('login');
                exit(0);
            }
           // elseif ($this->session->userdata['captchaWord'] != $userCaptcha) {
            elseif ($_SESSION["captcha"] != $userCaptcha) {
                $this->session->set_flashdata('msg', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a > <p style="text-align: center;">Invalid Captcha!</p> </div>');
                redirect('login');
                exit(0);
            } //for responsive_variant
            if ($username == NULL  || $password == NULL) {
                log_message('CUSTOM', "Please provide both, user and password.");
                $this->session->set_flashdata('msg', 'Please provide both, user and password.');
                redirect('login');
                exit(0);
            }
            /*else if(!is_recaptcha_valid(@$_POST['ctvrg'])){
                $this->session->set_flashdata('msg', 'We are not able to verify you. Please try later or try with a different web browser / device.');
                redirect('login');
                exit(0);
            }*/
            else {

                /*****start-for efiling_assistant*****/
                $impersonator_user = new stdClass();$impersonated_user = new stdClass();
                $mobile='';$email='';
                $user_parts = explode('#', $username);
                if(count($user_parts) == 2){
                    $impersonator_user = @$this->Login_model->get_user($user_parts[0], $password, false)[0];
                    //$jailDetails=$this->efiling_webservices->getJailDetails($user_parts[1]);      used for otp for Delhi and Punjab jails
                    //if((!empty($impersonator_user)) or (strcasecmp($user_parts[0],'jail')==0 and ($jailDetails['jailDetails'][0]['State_Code']==19 or $jailDetails['jailDetails'][0]['State_Code']==30))){
                        //if($impersonator_user->ref_m_usertype_id == 18 or (strcasecmp($user_parts[0],'jail')==0 and ($jailDetails['jailDetails'][0]['State_Code']==19 or $jailDetails['jailDetails'][0]['State_Code']==30))) {
                    if((!empty($impersonator_user)) or (strcasecmp($user_parts[0],'jail')==0)){
                        if($impersonator_user->ref_m_usertype_id == 18 or (strcasecmp($user_parts[0],'jail')==0)) {
                            $impersonated_user = @$this->Login_model->get_user($user_parts[1], $password, false, false)[0];
                            if(@$impersonated_user->ref_m_usertype_id==17)
                            {
                                $result=$this->efiling_webservices->jailAuthorityDetails($user_parts[1]);
                                $mobile=$result[0]['spmobile'];
                                $email=$result[0]['spemail'];
                               // $mobile='9871754198';
                               // $email='sca.preetiagrawal@sci.nic.in';
                            }
                            if (!empty($impersonated_user)) {
                                if (empty($impersonatedUserAuthenticationMobileOtp)) {
                                    if ((!empty(@$impersonated_user->moblie_number)) or (!empty($mobile))) {
                                        $impersonated_user_authentication_mobile_otp = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                                        $_SESSION['impersonated_user_authentication_mobile_otp.'.$impersonated_user->id] = $impersonated_user_authentication_mobile_otp;

                                        if($impersonated_user->ref_m_usertype_id==17)
                                        {
                                            $message='Authentication OTP for eFiling is: ' . $impersonated_user_authentication_mobile_otp.'. - Supreme Court of India';
                                            @sendSMS(38, $mobile, $message,SCISMS_efiling_OTP);
                                            send_mail_msg($email,'Authentication OTP for eFiling' ,$message, $user_parts[1]);
                                            $this->session->set_flashdata('information', 'Please enter the authentication OTP sent to intended user on her/his Mobile No. - ' . $mobile);
                                            log_message('CUSTOM', "Please enter the authentication OTP sent to intended user on her/his Mobile No. - " . $mobile);
                                        }
                                        else {
                                            @sendSMS(38, $impersonated_user->moblie_number, 'Authentication OTP for eFiling via eFiling Assistant is: ' . $impersonated_user_authentication_mobile_otp.'. - Supreme Court of India',SCISMS_Efiling_OTP_Via_Assistant);
                                            $this->session->set_flashdata('information', 'Please enter the authentication OTP sent to intended user on her/his Mobile No. - ' . $impersonated_user->moblie_number);
                                        }

                                        $this->session->set_flashdata('user', $username);
                                        $this->session->set_flashdata('impersonated_user_authentication_mobile_otp', $impersonated_user_authentication_mobile_otp);
                                        redirect('login');
                                        exit(0);
                                    } else {
                                        $this->session->set_flashdata('msg', 'Impersonated user has no registered mobile number');
                                        redirect('login');
                                        exit(0);
                                    }
                                } else if ($impersonatedUserAuthenticationMobileOtp == @$_SESSION['impersonated_user_authentication_mobile_otp.'.$impersonated_user->id]) {
                                    unset($_SESSION['impersonated_user_authentication_mobile_otp.'.$impersonated_user->id]);

                                    $usr_result = $this->Login_model->get_user($user_parts[1], null, true, false);
                                } else {
                                    $this->session->set_flashdata('msg', 'Invalid authentication OTP');
                                    redirect('login');
                                    redirect('login');
                                    exit(0);
                                }
                            } else {
                                $this->session->set_flashdata('msg', 'Invalid impersonated user');
                                redirect('login');
                                exit(0);
                            }
                        }
                        else{
                            $usr_result = $this->Login_model->get_user($username, $password);
                        }
                    }
                    else{
                        $this->session->set_flashdata('msg', 'Invalid user or password.');
                        redirect('login');
                        exit(0);
                    }
                }
                else{

                    $usr_result = $this->Login_model->get_user($username, $password);
                }

                /*****end-for efiling_assistant*****/
                if ($usr_result) {
                    foreach ($usr_result as $row) {
                        $usr_block = $this->Login_model->get_user_block_dtl($row->id);
                        // print_r($usr_block);
                        foreach ($usr_block as $user_val) {
                            $logintime = $user_val->login_time;
                            $failure_no_attmpt=$user_val->failure_no_attmpt;
                        }

                        $currenttime = date("Y-m-d H:i:s");

                        $diff = strtotime($currenttime) - strtotime($logintime);
                        $fullDays = floor($diff / (60 * 60 * 24));
                        $fullMinutes = floor(($diff - ($fullDays * 60 * 60 * 24) - ($fullHours * 60 * 60)) / 60);
                        if ($fullDays == 0 && $fullMinutes <= 5 && $failure_no_attmpt==3) {
                            //return FALSE;
                            $this->session->set_flashdata('msg', 'You are Blocked Try After 5 min');
                            redirect('login');
                            exit(0);

                        }
                        else {
                            //Check user role
                            if(logged_in_check_user_type($row->ref_m_usertype_id)){
                                $this->session->set_flashdata('msg', 'You are not authorized !!');
                                redirect('login');
                                exit(0);
                            }


                            $usr_block_update = $this->Login_model->get_user_block_dtl_update($row->id);

                            $user_name = ucwords($row->first_name . ' ' . $row->last_name);

                            $log_data = $this->Login_model->get_user_login_log_details($row->id);

                            if (!empty($log_data)) {

                                foreach ($log_data as $resdata) {

                                    $unauthorized_access = array_keys(array_column($log_data, 'block'), 't');
                                    $new_login_agent = array_keys(array_column($log_data, 'user_agent'), $_SERVER['HTTP_USER_AGENT']);

                                    if (isset($unauthorized_access[0]) && !empty($unauthorized_access[0])) {
                                        $this->session->set_flashdata('msg', 'Unauthorized access');
                                        redirect('login');
                                        exit(0);
                                    }

                                    if (isset($new_login_agent) && empty($new_login_agent)) {
                                        // if ($this->isMobileDevice()) {
                                        //     $device = " Mobile";
                                        // } else {
                                        //     $device = "Desktop";
                                        // }
                                        $subject = 'New Login Detection on eFiling application';
                                        $Mail_message = 'We detected a login into your account from a new device on ' . date('d-m-Y') . ' at ' . date("h:i:s A") . "<br>Device: <b>" . $device . '</b><br>IP Address: <b>' . getClientIP() . '</b><br>User Agent: <b>' . $_SERVER['HTTP_USER_AGENT'] . '</b><br>If you think that somebody logged in to your account against your will, you can block it from your profile on  efiling portal.';
                                        //send_mail_msg($row->emailid, $subject, $Mail_message, $user_name);
                                    }
                                }
                            } else {
                                if ($this->isMobileDevice()) {
                                    $device = "Mobile";
                                } else {
                                    $device = "Desktop";
                                }
                                $subject = 'New Login Detection on eFiling application';
                                $Mail_message = 'We detected a login into your account from a new device on ' . date('d-m-Y') . ' at ' . date("h:i:s A") . "<br>Device: <b>" . $device . '</b><br>IP Address: <b>' . getClientIP() . '</b><br>User Agent: <b>' . $_SERVER['HTTP_USER_AGENT'] . '</b><br>If you think that somebody logged in to your account against your will, you blocked it from your profile on  efiling portal.';
                                log_message('CUSTOM', 'We detected a login into your account from a new device on ' . date('d-m-Y') . ' at ' . date("h:i:s A") . "<br>Device: <b>" . $device . '</b><br>IP Address: <b>' . getClientIP() . '</b><br>User Agent: <b>' . $_SERVER['HTTP_USER_AGENT'] . '</b><br>If you think that somebody logged in to your account against your will, you blocked it from your profile on  efiling portal.');
                                send_mail_msg($row->emailid, $subject, $Mail_message, $user_name);
                            }

                            $pg_request_function = $row->pg_request_function;
                            $pg_response_function = $row->pg_response_function;
                            $admin_estab_code = $row->estab_code;

                            $logindata = array(
                                'id' => $row->id,
                                'userid' => $row->userid,
                                'ref_m_usertype_id' => $row->ref_m_usertype_id,
                                'first_name' => $row->first_name,
                                'last_name' => $row->last_name,
                                'mobile_number' => $row->moblie_number,
                                'emailid' => $row->emailid,
                                'adv_sci_bar_id' => $row->adv_sci_bar_id,
                                'aor_code' => $row->aor_code,
                                'bar_reg_no' => $row->bar_reg_no,
                                'gender' => $row->gender,
                                'pg_request_fun' => $pg_request_function,
                                'pg_response_fun' => $pg_response_function,
                                'photo_path' => $row->photo_path,
                                //'state_name' => $state_name,
                                'login_active_session' => substr(number_format(time() * rand(), 0, '', ''), 0, 6),
                                //'dep_flag' => $row->dep_flag,
                                //'case_flag' => $row->case_flag,
                                //'doc_flag' => $row->doc_flag,
                                //'efiling_flag' => $row->efiling_flag,
                                //'dep_adv_flag' => $row->dep_adv_flag,
                                'admin_for_type_id' => $row->admin_for_type_id,
                                'admin_for_id' => $row->admin_for_id,
                                'account_status' => $row->account_status,
                                'refresh_token' => $row->refresh_token,
                                //'bar_approval_status' => $row->bar_approval_status,
                                'impersonator_user' => $impersonator_user,//for efiling_assistant
                                'processid' => getmypid(),
                                'department_id' => $row->ref_department_id
                            );
                            $sessiondata = array(
                                'login' => $logindata
                            );

                            //$this->session->sess_expiration='7200';

                            /*//TODO code for check if the user trying to logged in again from different browser added by KBPujari on 13032023
                            $alreadyLoggedInStatus=$this->Login_model->get_user_login_log_details_with_user_agent($row->id);
                            if($alreadyLoggedInStatus) {
                                $data['log_id'] = $row->id;
                                $data['logout_time'] = 'NOW()';
                                $this->Login_model->logUser('logout', $data);
                                unset($_SESSION);
                                $this->session->sess_destroy();
                                $this->session->set_flashdata('msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage">You are already logged in </div>');
                                redirect('login');
                            }*/

                            $this->session->set_userdata($sessiondata);
                        }//End of else part $fullDays==0 && $fullMinutes <= 5 ...
                    }//End of foreach loop..
                    $this->logUser($action = 'login');

                    $this->redirect_on_login();
                } else {
                    //XXXXXXXXXXXXXXXXXXXXXXXXX

                    $username = escape_data($_POST['txt_username']);
                    $usr_fail_dtl = $this->Login_model->get_failure_user_details($username);
                    if($usr_fail_dtl==1){
                        $this->session->set_flashdata('msg', 'You are Blocked Try After 5 min');
                        log_message('CUSTOM', 'You are Blocked Try After 5 min');
                        redirect('login');
                        exit(0);

                    }else{
                        //$this->session->set_flashdata('msg', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a > <p style="text-align: center;">Invalid username or password !</p> </div>');//for responsive_variant
                        $this->session->set_flashdata('msg', 'Invalid user or password!');
                        redirect('login');
                        exit(0);
                    }


                    //XXXXXXXXXXXXXXXXXXXXXXXXXX


                }
            }
        } else {

           // $data['captcha'] = get_new_captcha();

            //$this->session->set_userdata('captchaWord', $data['captcha']['word']);
            $this->session->set_userdata('login_salt', $this->generateRandomString());

            /*$this->load->view('login/login_header');
            $this->load->view('login/login_view', $data);
            $this->load->view('login/login_footer');*/
            $this->slice->view('responsive_variant.authentication.login');
        }
    }
    }

    function redirect_on_login() {

        if($this->session->userdata['login']['ref_m_usertype_id'] == USER_DISTRICT_ADMIN) {
            redirect("adminDashboard/work_done");
            exit();
        } elseif ($this->session->userdata['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN) {
            redirect("adminDashboard/work_done");
            exit();
        } elseif ($this->session->userdata['login']['ref_m_usertype_id'] == USER_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN) {
            redirect("adminDashboard");
            exit();
        } /*elseif ($this->session->userdata['login']['ref_m_usertype_id'] == USER_DEPARTMENT) {
            redirect('department');
            exit(0);
        }elseif ($this->session->userdata['login']['ref_m_usertype_id'] == USER_CLERK) {
            redirect('mycases/updation');
            exit(0);
        }*/  elseif ($this->session->userdata['login']['ref_m_usertype_id'] == USER_BAR_COUNCIL) {
            redirect('Bar_council');
            exit(0);
        } elseif($this->session->userdata['login']['ref_m_usertype_id'] == USER_REGISTRAR_ACTION || $this->session->userdata['login']['ref_m_usertype_id'] == USER_REGISTRAR_VIEW){
            redirect("registrarActionDashboard");
            exit();
        } elseif($this->session->userdata['login']['ref_m_usertype_id'] == USER_LIBRARY){
            redirect("citation/CitationController/libraryAdminDashBoard");
            exit();
        }
        elseif($this->session->userdata['login']['ref_m_usertype_id'] == JAIL_SUPERINTENDENT){
            redirect("jail_dashboard");
            exit();
        }
       else if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
            redirect("superAdmin");
            exit();
        }
       else if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_EFILING_ADMIN) {
           redirect("filingAdmin");
           exit();
       }
       elseif ($this->session->userdata['login']['ref_m_usertype_id'] == USER_ADMIN_READ_ONLY) {
        redirect("report/search");
        exit();
        }
        /* elseif ($this->session->userdata['login']['account_status'] == ACCOUNT_STATUS_PENDING_APPROVAL || $this->session->userdata['login']['account_status'] == ACCOUNT_STATUS_OBJECTION || $this->session->userdata['login']['account_status'] == ACCOUNT_STATUS_REJECTED || $this->session->userdata['login']['account_status'] == ACCOUNT_STATUS_DEACTIVE || $this->session->userdata['login']['bar_approval_status'] == BAR_APPROVAL_STATUS_ON_HOLD || $this->session->userdata['login']['bar_approval_status'] == BAR_APPROVAL_STATUS_DEACTIVATED) {
          redirect('profile');
          exit(0);
          } */ else {
            redirect("dashboard");
            exit();
        }
    }

    private function generateRandomString($length = 10) {

        // generates random string for login salt

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function logUser($action) {
        // logs the login and logout time
        if ($action == 'login') {
            $data['login_id'] = $this->session->userdata['login']['id'];
            $data['is_successful_login'] = 'true';
            $data['ip_address'] = getClientIP();
            $data['login_time'] = date('Y-m-d H:i:s');
            //$data['session_detail'] = serialize($this->session->userdata());
            //$data['referrer'] = $_SERVER['HTTP_REFERER'];
            $data['referrer'] = $this->session->userdata['login']['ref_m_usertype_id'];
            $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            //$data['url'] = $_SERVER['REQUEST_URI'];
            $data['processid'] = $this->session->userdata['login']['processid'];
            $data['impersonator_user'] = json_encode($this->session->userdata['login']['impersonator_user']);//for efiling_assistant
        } elseif ($action == 'logout') {
            $data['log_id'] = $this->session->userdata['login']['log_id'];
            $data['logout_time'] = date('Y-m-d H:i:s');
        }
        $this->Login_model->logUser($action, $data);
    }
    function isMobileDevice() {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }
}



?>