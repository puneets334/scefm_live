<?php
namespace App\Controllers;
use App\Libraries\Slice;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\Login\LoginModel;
use stdClass;
use App\Libraries\webservices\Ecoping_webservices;
use App\Models\Common\CommonModel;
use CodeIgniter\Cookie\Cookie;
class DefaultController extends BaseController
{

    protected $session;
    protected $load;
    protected $security;
    protected $agent;
    protected $form_validation;
    protected $slice;
    protected $Login_model;
    protected $Login_device;
    protected $efiling_webservices;
    protected $ecoping_webservices;
    public function __construct()
    {
        parent::__construct();
        $this->Login_model = new LoginModel();
        $this->efiling_webservices = new Efiling_webservices();
        $this->ecoping_webservices = new Ecoping_webservices();
        $this->agent = \Config\Services::request()->getUserAgent();
        $this->session = \Config\Services::session();
        $this->slice = new Slice();
        helper(['form']);
        date_default_timezone_set('Asia/Kolkata');
    }

    function checkBrowserCompatibility()
    {
        //Check browser version
        $browser_version = array('Firefox' => 84, 'Chrome' => 87, 'Safari' => 604, 'Edg' => 89);
        if ($this->agent->isBrowser()) {
            $version = $this->agent->getVersion();
            $agentb = $this->agent->getBrowser();
            if (array_key_exists($agentb, $browser_version)) {
                if ((int)$version < $browser_version[$agentb]) {
                    $data['agent'] = $agentb;
                    return view('browser_version_error', $data);
                } else {
                    return true;
                }
            } else {
            }
        } elseif ($this->agent->isRobot()) {
            $this->Login_device = $this->agent->getRobot();
        } elseif ($this->agent->isMobile()) {
            $this->Login_device = $this->agent->getMobile();
        } else {
            $this->Login_device = 'Unidentified User Agent';
        }
        //echo $agent;
        /*echo $this->agent->platform();
        return view('error404');
        exit;*/
        //END
    }

    public function index()
    {
        // if(isset($_SESSION['login'])){
        //     return redirect()->to('redirect_on_login');
        // }
        // $this->preventLoggedInAccess();
        $data = [];
        $this->session->set('login_salt', $this->generateRandomString());
        return $this->render('responsive_variant.authentication.frontLogin', $data);
    }

    function isNewUser($userId)
    {
        $result =  $this->Login_model->isNewUser($userId);
        return $result;
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $this->checkBrowserCompatibility();
        //check user already logged in or not
        if (isset($_SESSION['login']) && !empty($_SESSION['login'])) {
            $this->redirect_on_login();
        } else {
            if (JANPARICHAY_CLIENT_ACTIVE) {
                if(isset($_SESSION['sid']) && !empty($_SESSION['sid'])) {
                    check_and_redirect_janparichay_session();
                }
            }
        }
        // if (!empty($this->session->getFlashdata('login'))) {
        //     $this->redirect_on_login();
        // }
        $validation =  \Config\Services::validation();
        //---Commented line are used for disable captcha----------------->

        if (empty($this->request->getPost('userType'))) {
            $rules = [
                "txt_username" => [
                    "label" => "User ID",
                    "rules" => "required|trim"
                ],
                "txt_password" => [
                    "label" => "Password",
                    "rules" => "required|trim"
                ],
                "userCaptcha" => [
                    "label" => "Captcha",
                    "rules" => "required|trim"
                ],
            ];
            if ($this->validate($rules) === FALSE) {
                $data = [
                    'validation' => $this->validator,
                    'currentPath' => $this->slice->getSegment(1) ?? 'public',
                ];
                $this->session->set('login_salt', $this->generateRandomString());
                return $this->render('responsive_variant.authentication.frontLogin', $data);
            } else {
                $max_requests = 5;
                $time_period = 120;
                $currentTime = time();
                $username = escape_data($_POST['txt_username']);
                
                // session()->set('user_agent', $_SERVER['HTTP_USER_AGENT']);
                // if (!session()->get('login_requests')) {
                //     session()->set('login_requests', 0);
                //     session()->set('login_first_request_time', $currentTime);
                // }

                // $firstRequestTime = session()->get('login_first_request_time');
                // $requestCount = session()->get('login_requests');
                // session()->set('user_agent', $_SERVER['HTTP_USER_AGENT']);
                // if ($currentTime - $firstRequestTime > $time_period) {
                //     session()->set('login_requests', 1);
                //     session()->set('login_first_request_time', $currentTime);
                // } else {
                //     if ($requestCount < $max_requests) {
                //         session()->set('login_requests', $requestCount + 1);
                //     } else {
                //         $text_msg = 'You have reached the maximum attempt limit';
                //         session()->setFlashdata('msg', $text_msg);
                //         return redirect()->to(base_url('/'));
                //     }
                // }
                if ($this->isNewUser($_POST['txt_username']) == 1) {
                    $userCaptcha = esc($_POST['userCaptcha']);
                    if ($this->session->get('captcha') != $userCaptcha) {
                        $this->session->setFlashdata('errMsg', 'Invalid Captcha!');
                        $this->session->setFlashdata('old_username', $_POST['txt_username']);
                        return response()->redirect(base_url('/'));
                    }
                    $passworddd = esc($_POST['txt_password']);
                    $rowww = $this->Login_model->get_user($_POST['txt_username'], $passworddd);
                    $checkUser = $this->Login_model->check_user($_POST['txt_username'], $passworddd);
                    if(!empty($checkUser) && $checkUser[0]->is_active == 0 && $checkUser[0]->is_deleted == true && $checkUser[0]->ref_m_usertype_id == ARGUING_COUNSEL) {
                        return redirect()->to(base_url('Register/ForgetPassword'));
                    }
                    if ($rowww) {
                        return redirect()->to(base_url('Register/ForgetPassword'));
                    } else {
                        $this->session->setFlashdata('errMsg', 'Invalid username or password.');
                        return response()->redirect(base_url('/'));
                        exit(0);
                    }
                    //return $this->render('responsive_variant.authentication.update_password_view');
                }
                
                if (isset($_POST['txt_username']) && !empty($_POST['txt_username']) && isset($_POST['txt_password']) && !empty($_POST['txt_password']) && isset($_POST['userCaptcha']) && !empty($_POST['userCaptcha'])) {
                    $username = esc($_POST['txt_username']);
                    $password = esc($_POST['txt_password']);
                    $userCaptcha = $_POST['userCaptcha'];
                    if ($username == NULL  || $password == NULL || preg_match('/[^A-Za-z0-9!@#$]/i', $password) || $userCaptcha == NULL || preg_match('/[^A-Za-z0-9]/i', $userCaptcha)) {
                        $this->session->setFlashdata('errMsg', 'Invalid username or password or Captcha!');
                        return response()->redirect(base_url('/'));
                    } elseif ($this->session->get('captcha') != $userCaptcha) {
                        $this->session->setFlashdata('errMsg', 'Invalid Captcha!');
                        $this->session->setFlashdata('old_username', $username);
                        return response()->redirect(base_url('/'));
                    } else {
                        $usr_fail_dtl = $this->Login_model->get_failure_user_details($username);
                        if($usr_fail_dtl==1){
                            $this->session->setFlashdata('errMsg', 'You are Blocked Try After 15 min');
                            log_message('info', 'You are Blocked Try After 15 min');
                            return redirect()->to(base_url('/'));
                            exit(0);

                        }
                        /*Non approved arguing counsel for ecopy*/
                        $checkUser = $this->Login_model->check_user($username, $password);
                        $impersonator_user = new stdClass();
                        //pr($checkUser);
                        if(!empty($checkUser)){
                            if($checkUser[0]->is_active == 0 && $checkUser[0]->is_deleted == true && $checkUser[0]->ref_m_usertype_id == ARGUING_COUNSEL){
                                $impersonated_user_authentication_mobile_otp = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                                $enc_otp=url_encryption($impersonated_user_authentication_mobile_otp);
                                $userdata = array(
                                    'email' => $checkUser[0]->emailid, 
                                    'mobile' => $checkUser[0]->moblie_number, 
                                    'authorized_bar_id' => $checkUser[0]->adv_sci_bar_id,
                                    'otp'=>$enc_otp, 
                                    'filed_by' => 3,
                                    
                                );
                            
                                $startTime=date("H:i");
                                $endTime = date("H:i", strtotime('+15 minutes', strtotime($startTime)));
                                $sess_data =  array(
                                    'email' => $checkUser[0]->emailid, 
                                    'mobile' => $checkUser[0]->moblie_number, 
                                    'authorized_bar_id' => $checkUser[0]->adv_sci_bar_id,
                                    'otp'=>$enc_otp, 
                                    'filed_by' => 3,
                                    'start_time' => $startTime,
                                    'end_time' => $endTime
                                );
                                $logindata = array(
                                    'id' => $checkUser[0]->id,
                                    'userid' => $checkUser[0]->userid,
                                    'ref_m_usertype_id' => $checkUser[0]->ref_m_usertype_id,
                                    'first_name' => $checkUser[0]->first_name,
                                    'last_name' => $checkUser[0]->last_name,
                                    'mobile_number' => $checkUser[0]->moblie_number,
                                    'emailid' => $checkUser[0]->emailid,
                                    'adv_sci_bar_id' => $checkUser[0]->adv_sci_bar_id,
                                    'aor_code' => $checkUser[0]->aor_code,
                                    'bar_reg_no' => $checkUser[0]->bar_reg_no,
                                    'gender' => $checkUser[0]->gender,
                                    'photo_path' => $checkUser[0]->photo_path,
                                    'login_active_session' => substr(number_format(time() * rand(), 0, '', ''), 0, 6),
                                    'admin_for_type_id' => $checkUser[0]->admin_for_type_id,
                                    'admin_for_id' => $checkUser[0]->admin_for_id,
                                    'account_status' => $checkUser[0]->account_status,
                                    'refresh_token' => $checkUser[0]->refresh_token,
                                    'impersonator_user' => $impersonator_user, //for efiling_assistant
                                    'processid' => getmypid(),
                                    'department_id' => $checkUser[0]->ref_department_id,
                                    'icmis_usercode' => $checkUser[0]->icmis_usercode
                                );
                                $sessiondata = array(
                                    'login' => $logindata
                                );
                                setSessionData('authenticated_by_aor_details', $sess_data);
                                $varificationEndTIme = date("Y-m-d H:i", strtotime('+15 minutes', strtotime($startTime)));
                                $authorizedByAorAdvocateVerification = $this->ecoping_webservices->saveauthenticatedByAorDteail($userdata);
                                $enc_otp_varID=url_encryption($impersonated_user_authentication_mobile_otp.'###'.$authorizedByAorAdvocateVerification['lastInsertedID'].'###'.$varificationEndTIme);
                                $verificationLink=base_url()."VerifyThroughtAOR/".$enc_otp_varID;
                                $message='<h1>Welcome to Our Service!</h1><p>Dear Sir/Ma\'am,</p><p>Please click the link below to  Authenticate Your User. Link is Valid for 15 minutes only:</p><p><span class="Object" role="link" id="OBJ_PREFIX_DWT44_com_zimbra_url"><a href="'.$verificationLink.'" target="_blank" rel="nofollow noopener noreferrer">Verify User</a></span></p>
                                <p>Best Regards,<br>Supreme Court of India</p>';
                                $vlink = '<a href="'.$verificationLink.'" target="_blank" rel="nofollow noopener noreferrer">Verify User</a>';
                                
                                send_mail_msg($checkUser[0]->emailid,'Authentication OTP for eFiling',$message,$checkUser[0]->first_name);
                                $this->session->set($sessiondata);
                                $this->logUser('login', $logindata);
                                return response()->redirect(base_url("ecopying_dashboard"));
                                exit();
                            }
                        }
                        /*Non approved arguing counsel for ecopy*/
                        /*****start-for efiling_assistant*****/
                        $impersonator_user = new stdClass();
                        $impersonated_user = new stdClass();
                        $mobile = '';
                        $email = '';
                        $user_parts = explode('#', $username);
                        if (count($user_parts) == 2) {
                            $impersonator_user = @$this->Login_model->get_user($user_parts[0], $password, false)[0];
                            if ((!empty($impersonator_user)) or (strcasecmp($user_parts[0], 'jail') == 0)) {
                                if ($impersonator_user->ref_m_usertype_id == 18 or (strcasecmp($user_parts[0], 'jail') == 0)) {
                                    $impersonated_user = @$this->Login_model->get_user($user_parts[1], $password, false, false)[0];
                                    if (@$impersonated_user->ref_m_usertype_id == 17) {
                                        $result = $this->efiling_webservices->jailAuthorityDetails($user_parts[1]);
                                        $mobile = $result[0]['spmobile'];
                                        $email = $result[0]['spemail'];
                                    }
                                    if (!empty($impersonated_user)) {
                                        if (empty($impersonatedUserAuthenticationMobileOtp)) {
                                            if ((!empty(@$impersonated_user->moblie_number)) or (!empty($mobile))) {
                                                $impersonated_user_authentication_mobile_otp = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                                                $_SESSION['impersonated_user_authentication_mobile_otp.' . $impersonated_user->id] = $impersonated_user_authentication_mobile_otp;
                                                if ($impersonated_user->ref_m_usertype_id == 17) {
                                                    $message = 'Authentication OTP for eFiling is: ' . $impersonated_user_authentication_mobile_otp . '. - Supreme Court of India';
                                                    @sendSMS(38, $mobile, $message, SCISMS_efiling_OTP);
                                                    send_mail_msg($email, 'Authentication OTP for eFiling', $message, $user_parts[1]);
                                                    $this->session->setFlashdata('information', 'Please enter the authentication OTP sent to intended user on her/his Mobile No. - ' . $mobile);
                                                    log_message('CUSTOM', "Please enter the authentication OTP sent to intended user on her/his Mobile No. - " . $mobile);
                                                } else {
                                                    @sendSMS(38, $impersonated_user->moblie_number, 'Authentication OTP for eFiling via eFiling Assistant is: ' . $impersonated_user_authentication_mobile_otp . '. - Supreme Court of India', SCISMS_Efiling_OTP_Via_Assistant);
                                                    $this->session->setFlashdata('information', 'Please enter the authentication OTP sent to intended user on her/his Mobile No. - ' . $impersonated_user->moblie_number);
                                                }
                                                $this->session->setFlashdata('user', $username);
                                                $this->session->setFlashdata('impersonated_user_authentication_mobile_otp', $impersonated_user_authentication_mobile_otp);
                                                return redirect()->to(base_url('/'));
                                                exit(0);
                                            } else {
                                                $this->session->setFlashdata('errMsg', 'Impersonated user has no registered mobile number');
                                                return response()->redirect(base_url('/'));
                                                exit(0);
                                            }
                                        } else if ($impersonatedUserAuthenticationMobileOtp == @$_SESSION['impersonated_user_authentication_mobile_otp.' . $impersonated_user->id]) {
                                            unset($_SESSION['impersonated_user_authentication_mobile_otp.' . $impersonated_user->id]);
                                            $row = $this->Login_model->get_user($user_parts[1], null, true, false);
                                        } else {
                                            $this->session->setFlashdata('errMsg', 'Invalid authentication OTP');
                                            return response()->redirect(base_url('/'));
                                            exit(0);
                                        }
                                    } else {
                                        $this->session->setFlashdata('errMsg', 'Invalid impersonated user');
                                        return response()->redirect(base_url('/'));
                                        exit(0);
                                    }
                                } else {
                                    $row = $this->Login_model->get_user($username, $password);
                                }
                            } else {
                                $this->session->setFlashdata('errMsg', 'Invalid user or password.');
                                return response()->redirect(base_url('/'));
                                exit(0);
                            }
                        } else {
                            $row = $this->Login_model->get_user($username, $password);
                        }
                        if ($row) {
                            $row = $this->Login_model->get_user($username, $password);
                            $impersonator_user = new stdClass();
                            if ($row) {
                                //Validation Controller
                                if(SELECTED_RESTRICT_USERS_ACTIVE && !empty($row[0]->aor_code)) {
                                    if (!in_array($row[0]->aor_code, SELECTED_RESTRICT_USERS_LIST)) {
                                        echo "<script>alert('".SELECTED_RESTRICT_USERS_ALERT_MESSAGE."'); window.location.href='" .SELECTED_RESTRICT_USERS_URL."';</script>";exit();
                                    }
                                }
                                // $alreadyLoggedInStatus=$this->Login_model->get_user_login_log_details_with_user_agent($row->id);
                                // if ($alreadyLoggedInStatus) {
                                //     if($alreadyLoggedInStatus[0]->logout_time == '' || $alreadyLoggedInStatus[0]->logout_time == null ){
                                //             // Set a flashdata message
                                //             $this->session->setFlashdata('msg', 
                                //             'You are already logged in'
                                //             );
                                //             return redirect()->to('/');
                                //     } 
                                // }
                                $impersonator_user = $row[0];
                                $usr_block = $this->Login_model->get_user_block_dtl($row[0]->id);
                                if (!empty($usr_block)) {
                                    foreach ($usr_block as $user_val) {
                                        $logintime = $user_val->login_time;
                                        $failure_no_attmpt = $user_val->failure_no_attmpt;
                                    }
                                    $currenttime = date("Y-m-d H:i:s");
                                    $fullHours = 0;
                                    $diff = strtotime($currenttime) - strtotime($logintime);
                                    $fullDays = floor($diff / (60 * 60 * 24));
                                    $fullMinutes = floor(($diff - ($fullDays * 60 * 60 * 24) - ($fullHours * 60 * 60)) / 60);
                                    if ($fullDays == 0 && $fullMinutes <= 5 && $failure_no_attmpt == 3) {
                                        $this->session->setFlashdata('errMsg', 'You are Blocked Try After 15 min');
                                        return response()->redirect(base_url('/'));
                                        exit(0);
                                    }
                                }
                                $user_name = ucwords($row[0]->first_name . ' ' . $row[0]->last_name);
                                //Check user role
                                if (logged_in_check_user_type($row[0]->ref_m_usertype_id)) {
                                    $this->session->setFlashdata('errMsg', 'You are not authorized !!');
                                    return response()->redirect(base_url('/'));
                                    exit(0);
                                }
                                $usr_block_update = $this->Login_model->get_user_block_dtl_update($row[0]->id);
                                $log_data = $this->Login_model->get_user_login_log_details($row[0]->id);
                                if ($log_data) {
                                    foreach ($log_data as $resdata) {
                                        $unauthorized_access = array_keys(array_column($log_data, 'block'), 't');
                                        $new_login_agent = array_keys(array_column($log_data, 'user_agent'), $_SERVER['HTTP_USER_AGENT']);
                                        if (isset($unauthorized_access[0]) && !empty($unauthorized_access[0])) {
                                            $this->session->setFlashdata('errMsg', 'Unauthorized Access.');
                                            return response()->redirect(base_url('/'));
                                            exit(0);
                                        }
                                        if (isset($new_login_agent) && empty($new_login_agent)) {
                                            $subject = 'New Login Detection on eFiling application';
                                            $Mail_message = 'We detected a login into your account from a new device on ' . date('d-m-Y') . ' at ' . date("h:i:s A") . "<br>Device: <b>" . $this->Login_device . '</b><br>IP Address: <b>' . getClientIP() . '</b><br>User Agent: <b>' . $_SERVER['HTTP_USER_AGENT'] . '</b><br>If you think that somebody logged in to your account against your will, you can block it from your profile on  efiling portal.';
                                        }
                                    }
                                } else {
                                    $subject = 'New Login Detection on eFiling application';
                                    $Mail_message = 'We detected a login into your account from a new device on ' . date('d-m-Y') . ' at ' . date("h:i:s A") . "<br>Device: <b>" . $this->Login_device . '</b><br>IP Address: <b>' . getClientIP() . '</b><br>User Agent: <b>' . $_SERVER['HTTP_USER_AGENT'] . '</b><br>If you think that somebody logged in to your account against your will, you blocked it from your profile on  efiling portal.';
                                    // send_mail_msg($row[0]->emailid, $subject, $Mail_message, $user_name);
                                }
                                $pg_request_function = $row[0]->pg_request_function;
                                $pg_response_function = $row[0]->pg_response_function;
                                $admin_estab_code = $row[0]->estab_code;
                                $logindata = array(
                                    'id' => $row[0]->id,
                                    'userid' => $row[0]->userid,
                                    'ref_m_usertype_id' => $row[0]->ref_m_usertype_id,
                                    'first_name' => $row[0]->first_name,
                                    'last_name' => $row[0]->last_name,
                                    'mobile_number' => $row[0]->moblie_number,
                                    'emailid' => $row[0]->emailid,
                                    'adv_sci_bar_id' => $row[0]->adv_sci_bar_id,
                                    'aor_code' => $row[0]->aor_code,
                                    'bar_reg_no' => $row[0]->bar_reg_no,
                                    'gender' => $row[0]->gender,
                                    'pg_request_fun' => $pg_request_function,
                                    'pg_response_fun' => $pg_response_function,
                                    'photo_path' => $row[0]->photo_path,
                                    'login_active_session' => substr(number_format(time() * rand(), 0, '', ''), 0, 6),
                                    'admin_for_type_id' => $row[0]->admin_for_type_id,
                                    'admin_for_id' => $row[0]->admin_for_id,
                                    'account_status' => $row[0]->account_status,
                                    'refresh_token' => $row[0]->refresh_token,
                                    'impersonator_user' => $impersonator_user, //for efiling_assistant
                                    'processid' => getmypid(),
                                    'department_id' => $row[0]->ref_department_id,
                                    'icmis_usercode' => $row[0]->icmis_usercode
                                );
                                $sessiondata = array(
                                    'login' => $logindata
                                );
                                $loggedinUserArray = array(USER_ADVOCATE, USER_IN_PERSON, ARGUING_COUNSEL);
                                if(in_array($row[0]->ref_m_usertype_id, $loggedinUserArray)){
                                    $verify = eCopyingVerifiedUser($row[0]->emailid, $row[0]->moblie_number, 0);
                                    // $verify = eCopyingVerifiedUserAOR($row[0]->emailid, $row[0]->moblie_number, 0, $row[0]->adv_sci_bar_id);
                                    if(!$verify){
                                        $filed_by = 0;
                                        if($row[0]->ref_m_usertype_id == USER_ADVOCATE){
                                            $filed_by = 1;
                                        }elseif($row[0]->ref_m_usertype_id == USER_IN_PERSON){
                                            $filed_by = 2;
                                        }elseif($row[0]->ref_m_usertype_id == ARGUING_COUNSEL){
                                            $filed_by = 3;
                                        }
                                        if($row[0]->ref_m_usertype_id != ARGUING_COUNSEL){
                                            if($row[0]->ref_m_usertype_id == USER_ADVOCATE){
                                                $userdata = array(
                                                    'email' => $row[0]->emailid, 
                                                    'mobile' => $row[0]->moblie_number, 
                                                    'authorized_bar_id' => $row[0]->adv_sci_bar_id, 
                                                    'filed_by' => $filed_by,
                                                    'c_status' => 1
                                                );
                                            }else{
                                                $userdata = array(
                                                    'email' => $row[0]->emailid, 
                                                    'mobile' => $row[0]->moblie_number, 
                                                    'authorized_bar_id' => $row[0]->adv_sci_bar_id, 
                                                    'filed_by' => $filed_by,
                                                    'c_status' => 0
                                                );
                                            }
                                            $startTime=date("H:i");
                                            $impersonated_user_authentication_mobile_otp = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                                            $varificationEndTIme = date("Y-m-d H:i", strtotime('+15 minutes', strtotime($startTime)));
                                            $authorizedByAorAdvocateVerification = $this->ecoping_webservices->saveauthenticatedByAorDteail($userdata);
                                            if($row[0]->ref_m_usertype_id != USER_ADVOCATE){
                                                $enc_otp_varID=url_encryption($impersonated_user_authentication_mobile_otp.'###'.$authorizedByAorAdvocateVerification->id.'###'.$varificationEndTIme);
                                                $verificationLink=base_url()."VerifyThroughtAOR/".$enc_otp_varID;
                                                $vlink = '<a href="'.$verificationLink.'" target="_blank" rel="nofollow noopener noreferrer">Verify User</a>';
                                                $message='<h1>Welcome to Our Service!</h1><p>Dear Sir/Ma\'am,</p><p>Please click the link below to  Authenticate Your User. Link is Valid for 15 minutes only:</p><p><span class="Object" role="link" id="OBJ_PREFIX_DWT44_com_zimbra_url"><a href="'.$verificationLink.'" target="_blank" rel="nofollow noopener noreferrer">Verify User</a></span></p>
                                                <p>Best Regards,<br>Supreme Court of India</p>';
                                                send_mail_msg($row[0]->emailid,'Authentication OTP for eFiling',$message,$row[0]->first_name);
                                            }
                                        }
                                        if($row[0]->ref_m_usertype_id == ARGUING_COUNSEL){
                                            $impersonated_user_authentication_mobile_otp = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                                            $enc_otp=url_encryption($impersonated_user_authentication_mobile_otp);
                                            $userdata = array(
                                                'email' => $row[0]->emailid, 
                                                'mobile' => $row[0]->moblie_number, 
                                                'authorized_bar_id' => $row[0]->adv_sci_bar_id,
                                                'otp'=>$enc_otp, 
                                                'filed_by' => 3,
                                                
                                            );
                                        
                                            $startTime=date("H:i");
                                            $endTime = date("H:i", strtotime('+15 minutes', strtotime($startTime)));
                                            $sess_data =  array(
                                                'email' => $row[0]->emailid, 
                                                'mobile' => $row[0]->moblie_number, 
                                                'authorized_bar_id' => $row[0]->adv_sci_bar_id,
                                                'otp'=>$enc_otp, 
                                                'filed_by' => 3,
                                                'start_time' => $startTime,
                                                'end_time' => $endTime
                                            );
                                            $varificationEndTIme = date("Y-m-d H:i", strtotime('+15 minutes', strtotime($startTime)));
                                            setSessionData('authenticated_by_aor_details', $sess_data);
                                            $authorizedByAorAdvocateVerification = $this->ecoping_webservices->saveauthenticatedByAorDteail($userdata);
                                            $enc_otp_varID=url_encryption($impersonated_user_authentication_mobile_otp.'###'.$authorizedByAorAdvocateVerification['lastInsertedID'].'###'.$varificationEndTIme);
                                            $verificationLink=base_url()."VerifyThroughtAOR/".$enc_otp_varID;
                                            $message='<h1>Welcome to Our Service!</h1><p>Dear Sir/Ma\'am,</p><p>Please click the link below to  Authenticate Your User. Link is Valid for 15 minutes only:</p><p><span class="Object" role="link" id="OBJ_PREFIX_DWT44_com_zimbra_url"><a href="'.$verificationLink.'" target="_blank" rel="nofollow noopener noreferrer">Verify User</a></span></p>
                                            <p>Best Regards,<br>Supreme Court of India</p>';

                                            $vlink = '<a href="'.$verificationLink.'" target="_blank" rel="nofollow noopener noreferrer">Verify User</a>';
                                            
                                            send_mail_msg($row[0]->emailid,'Authentication OTP for eFiling',$message,$row[0]->first_name);
                                        }
                                    }
                                }
                                $this->session->regenerate();
                                $this->session->set($sessiondata);
                                $idwithip = $row[0]->id.$this->request->getIPAddress();
                                $user_token = $idwithip.session()->get('login_salt');
                                $this->session->set('usertoken',$user_token);
                                $this->session->set([
                                    'user_id' => $row[0]->id,
                                    'user_agent' => $this->request->getUserAgent()->getAgentString(),
                                    'ip_address' => $this->request->getIPAddress(),
                                ]);
                                $this->logUser('login', $logindata);
                                $token = bin2hex(random_bytes(32));
                                // $cookie = new Cookie(
                                //     'csrf_cookie_name',
                                //     $token,
                                //     [
                                //         'expires' => time() + (86400 * 30), // 30 days
                                //         'path' => '/',
                                //         'secure' => false, // Only send over HTTPS
                                //         'httponly' => true, // Prevent JavaScript access
                                //         'samesite' => Cookie::SAMESITE_LAX
                                //     ]
                                // );

                                // // Set the cookie in the response
                                // $response = service('response');
                                // $response->setCookie($cookie);
                                $this->redirect_on_login();
                            } else {
                                $this->session->setFlashdata('errMsg', 'Invalid username or password.');
                                return response()->redirect(base_url('/'));
                                exit(0);
                            }
                        } else {
                            $this->session->setFlashdata('errMsg', 'Invalid username or password.');
                            return response()->redirect(base_url('/'));
                            exit(0);
                        }
                    }
                } else {
                    $this->session->setFlashdata('login_salt', $this->generateRandomString());
                    return $this->render('responsive_variant.authentication.frontLogin');
                }
            }
        } else {

            $rules = [
                "using" => [
                    "label" => "Aor Code and Aor Mobile",
                    "rules" => "required|trim|regex_match[/^[a-zA-Z0-9]+$/]"
                ]
            ];
            if ($this->request->getPost('userType') == 'AUTHENTICATED_BY_AOR') {
                $rules = [
                    'you_email' => [
                        "label" => "your Email",
                        "rules" => "required|trim|valid_email|regex_match[/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/]"
                    ]
                ];
                $rules = ['yr_mobile' => [
                        "label" => "Your Mobile No.",
                        "rules" => "required|trim|numeric|regex_match[/^[0-9]+$/]"
                    ]
                ];
            }
            $data['userEnteredData'] = array(
                'using' => esc($this->request->getPost('using')),
                'you_email' => esc($this->request->getPost('you_email')),
                'yr_mobile' => esc($this->request->getPost('yr_mobile')),
                'userType' => esc($this->request->getPost('userType'))
            );
            if ($this->request->getPost('using') == 'AOR Mobile') {
                $rules = ['aor_mobile' => [
                        "label" => "AOR Mobile",
                        "rules" => "required|trim|numeric|regex_match[/^[0-9]+$/]"
                    ]
                ];
                $data['userEnteredData']['aor_mobile'] = esc($this->request->getPost('aor_mobile'));
            } else {
                $rules = ['aor_code' => [
                        "label" => "AOR Code",
                        "rules" => "required|trim|numeric|regex_match[/^[0-9]+$/]"
                    ]
                ];
                $data['userEnteredData']['aor_code'] = esc($this->request->getPost('aor_code'));
            }
            if ($this->request->getPost('using') == 'AOR Mobile') {
                if(esc($this->request->getPost('aor_mobile')) == esc($this->request->getPost('yr_mobile'))){
                    $data['using'] = esc($this->request->getPost('using'));
                    $data['aor_flag'] = 'yes';
                    $this->session->setFlashdata('errMsg', 'AOR Mobile and Your Mobile cant not be same.');
                    
                    return $this->render('responsive_variant.authentication.frontLogin', $data);
                }
            }
            if ($this->validate($rules) === FALSE) {
                $this->session->set('login_salt', $this->generateRandomString());
                $data['using'] = esc($this->request->getPost('using'));
                $data['aor_flag'] = 'yes';
                return $this->render('responsive_variant.authentication.frontLogin', $data);
            } else {
                if (isset($_POST['yr_mobile']) && (substr($_POST['yr_mobile'],0,1) == 0 || substr($_POST['yr_mobile'],0,1) == 1 || substr($_POST['yr_mobile'],0,1) == 2 || substr($_POST['yr_mobile'],0,1) == 3 || substr($_POST['yr_mobile'],0,1) == 4 || substr($_POST['yr_mobile'],0,1) == 5)) {
                    $this->session->setFlashdata('msg', 'Mobile Number is not valid!');
                    return redirect()->to(base_url('/'));
                    exit(0);
                }
                $userCaptcha = esc($this->request->getPost('userCaptcha'));
                $result = $this->ecoping_webservices->getCopyBarcodeBymobileOrAorCOde(esc($this->request->getPost('aor_code')), esc($this->request->getPost('aor_mobile')));
                if(SELECTED_RESTRICT_USERS_ACTIVE && !empty(esc($this->request->getPost('aor_code')))) {
                    if (!in_array(esc($this->request->getPost('aor_code')), SELECTED_RESTRICT_USERS_LIST)) {
                        echo "<script>alert('".SELECTED_RESTRICT_USERS_ALERT_MESSAGE."'); window.location.href='" .SELECTED_RESTRICT_USERS_URL."';</script>";exit();
                    }
                }
                if ($this->request->getPost('impersonatedUserAuthenticationMobileOtp')){
                    $sessCaptch = session()->get('captcha');
                    if($userCaptcha != $sessCaptch){
                        $this->session->setFlashdata('errMsg', 'Invalid Captcha');
                        $data['using'] = $this->request->getPost('using');
                        $data['aor_flag'] = 'yes';
                        return $this->render('responsive_variant.authentication.frontLogin', $data);
                    }
                    $max_requests = 3; // Maximum number of requests
                    $time_period = 60; // Time period in seconds (e.g., 1 hour)
                    $currentTime=date("H:i");
                    if (!session()->get('otp_verify_requests')) {
                        session()->set('otp_verify_requests', 0);
                        session()->set('otp_verify_first_request_time', strtotime($currentTime));
                    }

                    $firstRequestTime = session()->get('otp_verify_first_request_time');
                    $requestCount = session()->get('otp_verify_requests');

                    if ($requestCount < $max_requests) {
                        session()->set('otp_verify_requests', $requestCount + 1);
                    } else {
                        // Maximum attempts reached
                        $text_msg = 'You have reached the maximum attempt limit'; // code to stop sms flooding
                        session()->setFlashdata('errMsg', $text_msg);
                        unset($_SESSION['otp_verify_requests']);
                        return redirect()->to(base_url('/'));
                    }

                    // if (strtotime($currentTime) - $firstRequestTime > $time_period) {
                    //     // Time period has elapsed, reset the request count
                    //     session()->set('otp_verify_requests', 1); // Start a new count
                    //     session()->set('otp_verify_first_request_time', strtotime($currentTime));
                    // } else {
                    //     // Increment the request count if within the time period
                    // }
                    
                    if (($this->request->getPost('impersonatedAorAuthenticationMobileOtp') == @$_SESSION['impersonated_aor_authentication_mobile_otp'] && $this->request->getPost('impersonatedUserAuthenticationMobileOtp')==@$_SESSION['impersonated_user_authentication_mobile_otp'] ) && $this->OtpIsValid($currentTime)) {
                        // $verify = eCopyingVerifiedUser($this->request->getPost('you_email'), $this->request->getPost('yr_mobile'), 1);
                        $verify = eCopyingVerifiedUserAOR(esc($this->request->getPost('you_email')), esc($this->request->getPost('yr_mobile')), 1, $result->bar_id);
                        if($verify){
                            unset($_SESSION['impersonated_user_authentication_mobile_otp.' . $result->bar_id]);
                            unset($_SESSION['impersonated_user_authentication_mobile_otp']);
                            unset($_SESSION['impersonated_aor_authentication_mobile_otp']);
                            $row = $this->Login_model->get_user_for_ecopy(esc($this->request->getPost('aor_code')), esc($this->request->getPost('aor_mobile')));
                            if (is_array($row) && !empty($row)) {
                                $impersonator_user = $row[0];
                                $logindata = array(
                                    'id' => $row[0]->id,
                                    'userid' => $row[0]->userid,
                                    'ref_m_usertype_id' => AUTHENTICATED_BY_AOR,
                                    'first_name' => $row[0]->first_name,
                                    'last_name' => $row[0]->last_name,
                                    'mobile_number' => $row[0]->moblie_number,
                                    'emailid' => $row[0]->emailid,
                                    'adv_sci_bar_id' => $row[0]->adv_sci_bar_id,
                                    'aor_code' => $row[0]->aor_code,
                                    'bar_reg_no' => $row[0]->bar_reg_no,
                                    'gender' => $row[0]->gender,
                                    'pg_request_fun' => null,
                                    'pg_response_fun' => null,
                                    'photo_path' => $row[0]->photo_path,
                                    'login_active_session' => substr(number_format(time() * rand(), 0, '', ''), 0, 6),
                                    'admin_for_type_id' => $row[0]->admin_for_type_id,
                                    'admin_for_id' => $row[0]->admin_for_id,
                                    'account_status' => $row[0]->account_status,
                                    'refresh_token' => $row[0]->refresh_token,
                                    'impersonator_user' => $impersonator_user, //for efiling_assistant
                                    'processid' => getmypid(),
                                    'department_id' => $row[0]->ref_department_id,
                                    'icmis_usercode' => $row[0]->icmis_usercode,
                                    'your_mobile' => esc($this->request->getPost('yr_mobile')),
                                    'your_email' => esc($this->request->getPost('you_email'))
                                );
                            } else {
                                $logindata = array(
                                    'id' => null,
                                    'userid' => esc($this->request->getPost('aor_code')),
                                    'ref_m_usertype_id' => AUTHENTICATED_BY_AOR,
                                    'first_name' => $result->name,
                                    'last_name' => null,
                                    'mobile_number' => $result->mobile,
                                    'emailid' => $result->email,
                                    'adv_sci_bar_id' => $result->bar_id,
                                    'aor_code' => esc($this->request->getPost('aor_code')),
                                    'bar_reg_no' => $result->bar_id,
                                    'gender' => $result->sex,
                                    'pg_request_fun' => null,
                                    'pg_response_fun' => null,
                                    'photo_path' => null,
                                    'login_active_session' => substr(number_format(time() * rand(), 0, '', ''), 0, 6),
                                    'admin_for_type_id' => null,
                                    'admin_for_id' => null,
                                    'account_status' => null,
                                    'refresh_token' => null,
                                    'impersonator_user' => [],
                                    'processid' => getmypid(),
                                    'department_id' => null,
                                    'icmis_usercode' => $result->bar_id,
                                    'your_mobile' => esc($this->request->getPost('yr_mobile')),
                                    'your_email' => esc($this->request->getPost('you_email'))
                                );
                            }
                            $sessiondata = array(
                                'login' => $logindata
                            );
                            $this->session->set($sessiondata);
                            // session()->set('user_agent', $_SERVER['HTTP_USER_AGENT']);
                            
                            $this->logUser('login', $logindata);
                            $this->redirect_on_login();
                        }else{
                            $data['using'] = esc($this->request->getPost('using'));
                            $data['aor_flag'] = 'yes';
                            $this->session->setFlashdata('msg_success', 'This email address is requested to be registered for eCopying Services of Supreme Court of India for (User Name, Applicant Type)');
                            return redirect()->to(base_url('/'));
                            // return $this->render('responsive_variant.authentication.frontLogin', $data);
                        }
                    } else {
                        $data['using'] = esc($this->request->getPost('using'));
                        $data['aor_flag'] = 'yes';
                        if($this->OtpIsValid($currentTime)){
                            $this->session->setFlashdata('errMsg', 'OTP Not Matched');
                        }else{
                            $this->session->setFlashdata('errMsg', 'OTP is Expired');   
                        }
                        
                        return $this->render('responsive_variant.authentication.frontLogin', $data);
                    }
                }elseif (!empty($result)) {
                    if(SELECTED_RESTRICT_USERS_ACTIVE && !empty($result->aor_code)) {
                        if (!in_array($result->aor_code, SELECTED_RESTRICT_USERS_LIST)) {
                            echo "<script>alert('".SELECTED_RESTRICT_USERS_ALERT_MESSAGE."'); window.location.href='" .SELECTED_RESTRICT_USERS_URL."';</script>";exit();
                        }
                    }
                    $data['aor_flag'] = 'yes';
                    $data['bar_id'] = $result->bar_id;
                    $data['using'] = esc($this->request->getPost('using'));
                    $data['yr_mobile'] = esc($this->request->getPost('yr_mobile'));
                    $impersonated_user_authentication_mobile_otp = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                    $impersonated_aor_authentication_mobile_otp = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                    $enc_otp=url_encryption($impersonated_user_authentication_mobile_otp);
                    $userdata = array(
                        'email' => esc($this->request->getPost('you_email')), 
                        'mobile' => esc($this->request->getPost('yr_mobile')), 
                        'authorized_bar_id' => $result->bar_id,
                        'otp'=>$enc_otp, 
                        'filed_by' => 6,
                        'c_status' => 0
                    );
                   
                    $startTime=date("H:i");
                    $endTime = date("H:i", strtotime('+15 minutes', strtotime($startTime)));
                    $varificationEndTIme = date("Y-m-d H:i", strtotime('+15 minutes', strtotime($startTime)));
                    $sess_data =  array(
                        'email' => esc($this->request->getPost('you_email')), 
                        'mobile' => esc($this->request->getPost('yr_mobile')),
                        'authorized_bar_id' => $result->bar_id,
                        'otp'=>$enc_otp, 
                        'filed_by' => 6,
                        'start_time' => $startTime,
                        'end_time' => $endTime
                    );
                    setSessionData('authenticated_by_aor_details', $sess_data);
                    $verify = eCopyingVerifiedUserAOR(esc($this->request->getPost('you_email')), esc($this->request->getPost('yr_mobile')), 1,$result->bar_id);
                    if(empty($verify)){
                        $authorizedByAorAdvocateVerification = $this->ecoping_webservices->saveauthenticatedByAorDteail($userdata);
                        $enc_otp_varID=url_encryption($impersonated_user_authentication_mobile_otp.'###'.$authorizedByAorAdvocateVerification['lastInsertedID'].'###'.$varificationEndTIme);
                        $verificationLink=base_url()."VerifyThroughtAOR/".$enc_otp_varID;
                        $Common_model = new CommonModel();
                        //$_SESSION['last_sms'] = time();
                        $_SESSION['last_sms_to_email'] = $result->email;
                        $_SESSION['last_sms_ip'] = get_client_ip();
                        $ip_address = get_client_ip();
                        // Define rate limit parameters
                        $max_requests = 5; // Maximum number of requests
                        $time_period = 5; // Time period in seconds (e.g., 1 hour)
                        $request_time_time_period=date('Y-m-d H:i:s', time() - $time_period);
                        $request_count = $Common_model->check_efiling_sms_email_log($result->email,$request_time_time_period,$ip_address);
                        if ($request_count >= $max_requests) {
                            // Too many requests, show an error message
                            $text_msg = 'Please wait ' . SMS_RESEND_LIMIT . ' seconds and then try again!'; // code to stop sms flooding
                            session()->setFlashdata('msg', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a> <p style="text-align: center;">' . $text_msg . '</p> </div>');
                            return FALSE;
                        }else{
                            $message='<h1>Welcome to Our Service!</h1><p>Dear Sir/Ma\'am,</p><p>Please click the link below to  Authenticate Your User. Link is Valid for 15 minutes only:</p><p><span class="Object" role="link" id="OBJ_PREFIX_DWT44_com_zimbra_url"><a href="'.$verificationLink.'" target="_blank" rel="nofollow noopener noreferrer">Verify User</a></span></p>
                            <p>Best Regards,<br>Supreme Court of India</p>';

                            $vlink = '<a href="'.$verificationLink.'" target="_blank" rel="nofollow noopener noreferrer">Verify User</a>';
                            
                            send_mail_msg(esc($this->request->getPost('you_email')),'Authentication for eFiling',$message,$result->name);
                        }
                        $request_countsms = $Common_model->check_efiling_sms_email_log($result->mobile,$request_time_time_period,$ip_address);
                        if ($request_countsms >= $max_requests) {
                            // Too many requests, show an error message
                            $text_msg = 'Please wait ' . SMS_RESEND_LIMIT . ' seconds and then try again!'; // code to stop sms flooding
                            session()->setFlashdata('msg', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a> <p style="text-align: center;">' . $text_msg . '</p> </div>');
                            return FALSE;
                        }else{
                            $sentSMS = 'Authentication OTP for AOR is: ' . $impersonated_aor_authentication_mobile_otp . '. - Supreme Court of India';
                            $sentUserSMS = 'Authentication OTP for User is: ' . $impersonated_user_authentication_mobile_otp . '. - Supreme Court of India';
                            sendSMS(38, $this->request->getPost('yr_mobile'),$sentUserSMS, SCISMS_GENERIC_TEMPLATE);
                            sendSMS(38, $result->mobile,$sentSMS, SCISMS_GENERIC_TEMPLATE);
                        }
                        $_SESSION['impersonated_user_authentication_mobile_otp.' . $result->bar_id] = $impersonated_user_authentication_mobile_otp;
                        $message = 'Authentication OTP for AOR is: ' . $impersonated_user_authentication_mobile_otp . '. - Supreme Court of India';
                        $_SESSION['impersonated_user_authentication_mobile_otp']=$impersonated_user_authentication_mobile_otp;
                        $_SESSION['impersonated_aor_authentication_mobile_otp']=$impersonated_aor_authentication_mobile_otp;
                        
                        $this->session->setFlashdata('msg', 'OTP has been Sent on AOR\'s Registered Mobile No. OTP is valid for 15 minutes');
                        return $this->render('responsive_variant.authentication.frontLogin',$data);
                    }else{
                        $_SESSION['last_sms_to_email'] = $result->email;
                        $_SESSION['last_sms_ip'] = get_client_ip();
                        $ip_address = get_client_ip();
                        $Common_model = new CommonModel();
                        $max_requests = 5; // Maximum number of requests
                        $time_period = 5; // Time period in seconds (e.g., 1 hour)
                        $request_time_time_period=date('Y-m-d H:i:s', time() - $time_period);
                        $request_countsms = $Common_model->check_efiling_sms_email_log($result->mobile,$request_time_time_period,$ip_address);
                        if ($request_countsms >= $max_requests) {
                            // Too many requests, show an error message
                            $text_msg = 'Please wait ' . SMS_RESEND_LIMIT . ' seconds and then try again!'; // code to stop sms flooding
                            session()->setFlashdata('msg', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a> <p style="text-align: center;">' . $text_msg . '</p> </div>');
                            return FALSE;
                        }else{
                            $sentSMS = 'Authentication OTP for AOR is: ' . $impersonated_aor_authentication_mobile_otp . '. - Supreme Court of India';
                            $sentUserSMS = 'Authentication OTP for User is: ' . $impersonated_user_authentication_mobile_otp . '. - Supreme Court of India';
                            sendSMS(38, $this->request->getPost('yr_mobile'),$sentUserSMS, SCISMS_GENERIC_TEMPLATE);
                            sendSMS(38, $result->mobile,$sentSMS,SCISMS_GENERIC_TEMPLATE);
                        }
                        $_SESSION['impersonated_user_authentication_mobile_otp.' . $result->bar_id] = $impersonated_user_authentication_mobile_otp;
                        $message = 'Authentication OTP for AOR is: ' . $impersonated_user_authentication_mobile_otp . '. - Supreme Court of India';
                        $_SESSION['impersonated_user_authentication_mobile_otp'] = $impersonated_user_authentication_mobile_otp;
                        $_SESSION['impersonated_aor_authentication_mobile_otp']=$impersonated_aor_authentication_mobile_otp;
                        $this->session->setFlashdata('msg', 'OTP has been Sent on AOR\'s Registered Mobile No. OTP is valid for 15 minutes');
                        return $this->render('responsive_variant.authentication.frontLogin',$data);
                    }
                } else {
                    $this->session->setFlashdata('errMsg', 'AOR Mobile No. OR AOR code Does Not Match');
                    $data['aor_flag'] = 'no';
                    $data['using'] = esc($this->request->getPost('using'));
                    return $this->render('responsive_variant.authentication.frontLogin', $data);
                }
            }
        }
    }
    private function OtpIsValid($currentTime)
    {
        if (strtotime($currentTime) <=strtotime(getSessionData('authenticated_by_aor_details')['end_time'])){
            return true;
        }
        return false;
    }
    public function otp()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $username = $this->session->get('login_cred')['user_name'];
        $password = $this->session->get('login_cred')['password'];
        $otp = $this->request->getPost('otp_one') . $this->request->getPost('otp_two') . $this->request->getPost('otp_three') . $this->request->getPost('otp_four') . $this->request->getPost('otp_five') . $this->request->getPost('otp_six');
        $checkotp = $this->Login_model->check_otp($username, $password, $otp);
        if ($checkotp) {
            $row = $this->Login_model->get_user($username, $password);
            $impersonator_user = new stdClass();
            if ($row) {
                $impersonator_user = $row[0];
                $usr_block = $this->Login_model->get_user_block_dtl($row[0]->id);
                if (!empty($usr_block)) {
                    foreach ($usr_block as $user_val) {
                        $logintime = $user_val->login_time;
                        $failure_no_attmpt = $user_val->failure_no_attmpt;
                    }
                    $currenttime = date("Y-m-d H:i:s");
                    $fullHours = 0;
                    $diff = strtotime($currenttime) - strtotime($logintime);
                    $fullDays = floor($diff / (60 * 60 * 24));
                    $fullMinutes = floor(($diff - ($fullDays * 60 * 60 * 24) - ($fullHours * 60 * 60)) / 60);
                    if ($fullDays == 0 && $fullMinutes <= 5 && $failure_no_attmpt == 3) {
                        $this->session->setFlashdata('msg', 'You are Blocked Try After 5 min');
                        return response()->redirect(base_url('/'));
                        exit(0);
                    }
                }
                $user_name = ucwords($row[0]->first_name . ' ' . $row[0]->last_name);
                //Check user role
                if (logged_in_check_user_type($row[0]->ref_m_usertype_id)) {
                    $this->session->setFlashdata('msg', 'You are not authorized !!');
                    return response()->redirect(base_url('/'));
                    exit(0);
                }
                $usr_block_update = $this->Login_model->get_user_block_dtl_update($row[0]->id);
                $log_data = $this->Login_model->get_user_login_log_details($row[0]->id);
                if ($log_data) {
                    foreach ($log_data as $resdata) {
                        $unauthorized_access = array_keys(array_column($log_data, 'block'), 't');
                        $new_login_agent = array_keys(array_column($log_data, 'user_agent'), $_SERVER['HTTP_USER_AGENT']);
                        if (isset($unauthorized_access[0]) && !empty($unauthorized_access[0])) {
                            $this->session->setFlashdata('msg', 'Unauthorized Access.');
                            return response()->redirect(base_url('/'));
                            exit(0);
                        }
                        if (isset($new_login_agent) && empty($new_login_agent)) {
                            $subject = 'New Login Detection on eFiling application';
                            $Mail_message = 'We detected a login into your account from a new device on ' . date('d-m-Y') . ' at ' . date("h:i:s A") . "<br>Device: <b>" . $this->Login_device . '</b><br>IP Address: <b>' . getClientIP() . '</b><br>User Agent: <b>' . $_SERVER['HTTP_USER_AGENT'] . '</b><br>If you think that somebody logged in to your account against your will, you can block it from your profile on  efiling portal.';
                        }
                    }
                } else {
                    $subject = 'New Login Detection on eFiling application';
                    $Mail_message = 'We detected a login into your account from a new device on ' . date('d-m-Y') . ' at ' . date("h:i:s A") . "<br>Device: <b>" . $this->Login_device . '</b><br>IP Address: <b>' . getClientIP() . '</b><br>User Agent: <b>' . $_SERVER['HTTP_USER_AGENT'] . '</b><br>If you think that somebody logged in to your account against your will, you blocked it from your profile on  efiling portal.';
                    // send_mail_msg($row[0]->emailid, $subject, $Mail_message, $user_name);
                }
                $pg_request_function = $row[0]->pg_request_function;
                $pg_response_function = $row[0]->pg_response_function;
                $admin_estab_code = $row[0]->estab_code;
                $logindata = array(
                    'id' => $row[0]->id,
                    'userid' => $row[0]->userid,
                    'ref_m_usertype_id' => $row[0]->ref_m_usertype_id,
                    'first_name' => $row[0]->first_name,
                    'last_name' => $row[0]->last_name,
                    'mobile_number' => $row[0]->moblie_number,
                    'emailid' => $row[0]->emailid,
                    'adv_sci_bar_id' => $row[0]->adv_sci_bar_id,
                    'aor_code' => $row[0]->aor_code,
                    'bar_reg_no' => $row[0]->bar_reg_no,
                    'gender' => $row[0]->gender,
                    'pg_request_fun' => $pg_request_function,
                    'pg_response_fun' => $pg_response_function,
                    'photo_path' => $row[0]->photo_path,
                    'login_active_session' => substr(number_format(time() * rand(), 0, '', ''), 0, 6),
                    'admin_for_type_id' => $row[0]->admin_for_type_id,
                    'admin_for_id' => $row[0]->admin_for_id,
                    'account_status' => $row[0]->account_status,
                    'refresh_token' => $row[0]->refresh_token,
                    //'dep_flag' => $row[0]->dep_flag,
                    //'case_flag' => $row[0]->case_flag,
                    //'doc_flag' => $row[0]->doc_flag,
                    //'efiling_flag' => $row[0]->efiling_flag,
                    //'dep_adv_flag' => $row[0]->dep_adv_flag,
                    'impersonator_user' => $impersonator_user, //for efiling_assistant
                    'processid' => getmypid(),
                    'department_id' => $row[0]->ref_department_id
                );
                $sessiondata = array(
                    'login' => $logindata
                );
                $this->session->set($sessiondata);
                $this->logUser('login', $logindata);
                $this->redirect_on_login();
            } else {
                $this->session->setFlashdata('msg', 'Invalid username or password !');
                return response()->redirect(base_url('/'));
                exit(0);
            }
        } else {
            $this->session->setFlashdata('msg', 'OTP Not matched !');
            return response()->redirect(base_url('/'));
            exit(0);
        }
    }

    function redirect_on_login()
    {
        // if(isset($_SERVER['HTTP_USER_AGENT']) && isset($_SESSION['user_agent'])){
        //     if($_SERVER['HTTP_USER_AGENT'] != $_SESSION['user_agent']){
        //         return response()->redirect(base_url('/'));
        //         exit(0);
        //     }
        // }
        if(isset(session()->get('login')['impersonator_user']) && isset(session()->get('login')['impersonator_user']->is_active) && !empty(session()->get('login')['impersonator_user']) && session()->get('login')['impersonator_user']->is_active == 1){
        }else{
            return response()->redirect(base_url("ecopying_dashboard"));
            exit();
        }
        if (!empty($this->session->get('login')) && $this->session->get('login')['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
            return response()->redirect(base_url("superAdmin"));
            exit();
        } elseif (!empty($this->session->get('login')) && $this->session->get('login')['ref_m_usertype_id'] == USER_DISTRICT_ADMIN) {
            return response()->redirect(base_url("adminDashboard/work_done"));
            exit();
        } elseif (!empty($this->session->get('login')) && $this->session->get('login')['ref_m_usertype_id'] == USER_MASTER_ADMIN) {
            return response()->redirect(base_url("adminDashboard/work_done"));
            exit();
        } elseif ((!empty($this->session->get('login')) && $this->session->get('login')['ref_m_usertype_id'] == USER_ADMIN) || (!empty($this->session->get('login')) && $this->session->get('login')['ref_m_usertype_id'] == USER_ACTION_ADMIN)) {
            return response()->redirect(base_url("adminDashboard"));
            exit();
        } elseif ((!empty(getSessionData('login')) && getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN_READ_ONLY)) {
            return response()->redirect(base_url("adminDashboard"));
            exit();
        } elseif (!empty($this->session->get('login')) && $this->session->get('login')['ref_m_usertype_id'] == USER_DEPARTMENT) {
            return response()->redirect(base_url('mycases/updation'));
            exit(0);
        } elseif (!empty($this->session->get('login')) && $this->session->get('login')['ref_m_usertype_id'] == USER_CLERK) {
            return response()->redirect(base_url('dashboard_alt'));
            exit(0);
        } elseif (!empty($this->session->get('login')) && $this->session->get('login')['ref_m_usertype_id'] == USER_BAR_COUNCIL) {
            return response()->redirect(base_url('Bar_council'));
            exit(0);
        } elseif (!empty($this->session->get('login')) && $this->session->get('login')['ref_m_usertype_id'] == JAIL_SUPERINTENDENT) {
            return response()->redirect(base_url('jail_dashboard'));
            exit(0);
        } else if (!empty($this->session->get('login')) && $this->session->get('login')['ref_m_usertype_id'] == USER_EFILING_ADMIN) {
            return response()->redirect(base_url("filingAdmin"));
            exit();
        } else if (!empty($this->session->get('login')) && $this->session->get('login')['ref_m_usertype_id'] == USER_ADVOCATE) {
            return response()->redirect(base_url("dashboard_alt"));
            exit();
        } else if (!empty($this->session->get('login')) && $this->session->get('login')['ref_m_usertype_id'] == AUTHENTICATED_BY_AOR) {
            return response()->redirect(base_url("ecopying_dashboard"));
            exit();
        } else if (!empty($this->session->get('login')) && $this->session->get('login')['ref_m_usertype_id'] == APPEARING_COUNCIL) {
            return response()->redirect(base_url("ecopying_dashboard"));
            exit();
        }
        /* elseif ($this->session->userdata['login']['account_status'] == ACCOUNT_STATUS_PENDING_APPROVAL || $this->session->userdata['login']['account_status'] == ACCOUNT_STATUS_OBJECTION || $this->session->userdata['login']['account_status'] == ACCOUNT_STATUS_REJECTED || $this->session->userdata['login']['account_status'] == ACCOUNT_STATUS_DEACTIVE || $this->session->userdata['login']['bar_approval_status'] == BAR_APPROVAL_STATUS_ON_HOLD || $this->session->userdata['login']['bar_approval_status'] == BAR_APPROVAL_STATUS_DEACTIVATED) {
            redirect('profile');
            exit(0);
        } */ else {
            return response()->redirect(base_url("dashboard"));
            exit();
        }
    }

    private function generateRandomString($length = 43)
    {
        // generates random string for login salt
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function logUser($action)
    {
        if ($action == 'login') {
            $data['login_id'] = getSessionData('login')['id'];
            $data['is_successful_login'] = 'true';
            $data['ip_address'] = getClientIP();
            $data['login_time'] = date('Y-m-d H:i:s');
            $data['session_detail'] = json_encode($_COOKIE);
            //$data['referrer'] = $_SERVER['HTTP_REFERER'];
            $data['referrer'] = getSessionData('login')['ref_m_usertype_id'];
            $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            //$data['url'] = $_SERVER['REQUEST_URI'];
            $data['processid'] = getSessionData('login')['processid'];
            $data['impersonator_user'] = json_encode(getSessionData('login')['impersonator_user']); //for efiling_assistant
            if(!empty(getSessionData('login')['id'])){
                $this->Login_model->logUser($action, $data);
            }
        } elseif ($action == 'logout') {
            $data['log_id'] = getSessionData('login')['log_id'];
            $data['session_detail'] = null;
            $data['logout_time'] = date('Y-m-d H:i:s');
            $this->Login_model->logUser($action, $data);
        }
        
    }
    public function VerifyThroughtAOR(){
        $request = $this->request;
        $uri = $request->getUri();
        $data = isset(explode('###',url_decryption($uri->getSegment(2)))[1]) ? array('id'=>explode('###',url_decryption($uri->getSegment(2)))[1]) : [];
        $time = isset(explode('###',url_decryption($uri->getSegment(2)))[2]) ? explode('###',url_decryption($uri->getSegment(2)))[2] : '';
        $crTime = date('Y-m-d H:i');
        $crTime = strtotime($crTime);
        $time = !empty($time) ? strtotime($time) : '';
        if($crTime > $time){
            $status=array('status' => 'expired');
            return $this->render('responsive_variant.authentication.VerifyThroughtAOR',$status);
        }
        $userVerified=$this->ecoping_webservices->ApproveAuthenticatedByAor($data);
        return $this->render('responsive_variant.authentication.VerifyThroughtAOR',$userVerified);
    }
    public function isMobileDevice()
    {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

    public function customDashboard()
    {
        return $this->render('layout.adminApp');
        //  echo "Welcome To Dashboard";
    }

    public function notFound()
    {
        return view('errors/html/error_404');
    }

    public function internalServerError()
    {
        return view('errors/html/error_500');
    }

    protected function preventLoggedInAccess()
    {
        if (session()->has('login')) {
            return response()->redirect('redirect_on_login');
        }
    }

    public function sendTestMsg(){
        $mobile = '6260380102';
        $message = 'Authentication OTP for eFiling is: 123456. - Supreme Court of India';
        pr(sendSMS(38, $mobile, $message, SCISMS_efiling_OTP));


        // $message='Welcome to Our Service! <p>Best Regards,<br>Supreme Court of India</p>';
        // pr(send_mail_JIO(esc('punit.sharma@velocis.co.in'),'Authentication for eFiling',$message));
            
        //     pr(send_mail_msg(esc('punit.sharma@velocis.co.in'),'Authentication for eFiling',$message,'Punit'));
    }
}
