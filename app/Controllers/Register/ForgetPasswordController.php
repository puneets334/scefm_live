<?php

namespace App\Controllers\Register;

use App\Controllers\BaseController;
use App\Models\Register\RegisterModel;
use App\Libraries\Slice;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\Common\CommonModel;

class Auto_Sanitize
{
    function hex2bin($hexstr)
    {
        $n = strlen($hexstr);
        $sbin = "";
        $i = 0;
        while ($i < $n) {
            $a = substr($hexstr, $i, 2);
            $c = pack("H*", $a);
            if ($i == 0) {
                $sbin = $c;
            } else {
                $sbin .= $c;
            }
            $i += 2;
        }
        return $sbin;
    }
    function cryptoJsAesDecrypt($passphrase, $jsonString)
    {
        $jsondata = json_decode($jsonString, true);
        try {
            $salt = $this->hex2bin($jsondata["s"]);
            $iv  = $this->hex2bin($jsondata["iv"]);
        } catch (\Exception $e) {
            return null;
        }
        $ct = base64_decode($jsondata["ct"]);
        $concatedPassphrase = $passphrase . $salt;
        $md5 = array();
        $md5[0] = md5($concatedPassphrase, true);
        $result = $md5[0];
        for ($i = 1; $i < 3; $i++) {
            $md5[$i] = md5($md5[$i - 1] . $concatedPassphrase, true);
            $result .= $md5[$i];
        }
        $key = substr($result, 0, 32);
        $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
        return json_decode($data, true);
    }

    /**
     * Encrypt value to a cryptojs compatiable json encoding string
     *
     * @param mixed $passphrase
     * @param mixed $value
     * @return string
     */
    function cryptoJsAesEncrypt($passphrase, $value)
    {
        $salt = openssl_random_pseudo_bytes(8);
        $salted = '';
        $dx = '';
        while (strlen($salted) < 48) {
            $dx = md5($dx . $passphrase . $salt, true);
            $salted .= $dx;
        }
        $key = substr($salted, 0, 32);
        $iv  = substr($salted, 32, 16);
        $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
        $data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));
        return json_encode($data);
    }
}

class ForgetPasswordController extends BaseController
{
    protected $Register_model;
    protected $slice;
    protected $efiling_webservices;

    public function __construct()
    {
        parent::__construct();
        $this->Register_model = new RegisterModel();
        $this->slice = new Slice();
        $this->session = \Config\Services::session();
        $this->efiling_webservices = new Efiling_webservices();
        // $this->load->library('slice');
    }

    public function index()
    {
        session()->remove('captcha');
        return $this->render('responsive_variant.authentication.forget_password_view');
    }

    function adv_get_otp_old()
    {
        if (empty($_POST['userCaptcha'])) {
            $this->session->setFlashdata('msg', 'Captcha is required!');
            return redirect()->to(base_url('register/ForgetPassword'));
            exit(0);
        }
        // $this->form_validation->set_rules('userCaptcha', 'Captcha', 'required|trim|is_required');
        // $this->form_validation->set_rules('adv_mobile', 'Mobile', 'numeric|trim|is_required|min_length[10]|max_length[10]');
        // $this->form_validation->set_rules('adv_email', 'Email', 'valid_email|trim|is_required');
        // // $this->form_validation->set_rules('userCaptcha', 'Captcha', 'required|trim|is_required');
        // $this->form_validation->set_error_delimiters('<div class="uk-alert-danger">', '</div>');
        $rules = [
            "adv_email" => [
                "label" => "Email",
                "rules" => "valid_email|trim|is_required"
            ],
            "adv_mobile" => [
                "label" => "Mobile",
                "rules" => "numeric|trim|is_required|min_length[10]|max_length[10]"
            ],
            "userCaptcha" => [
                "label" => "Captcha",
                "rules" => "required|trim|is_required"
            ],
        ];
        if (empty($_POST['adv_mobile']) && empty($_POST['adv_email'])) {
            $this->session->setFlashdata('msg', 'Please Enter Mobile or Email!');
            return redirect()->to(base_url('register/ForgetPassword'));
        }
        if ($this->validate($rules) === FALSE) {
            $data = [
                'validation' => $this->validator,
            ];
            return $this->render('responsive_variant.authentication.forget_password_view');
        } else {
            if (getSessionData("captcha") != $_POST['userCaptcha']) {
                $this->session->setFlashdata('msg', 'Invalid Captcha!');
                return redirect()->to(base_url('register/ForgetPassword'));
                exit(0);
            }
            if (isset($_POST['adv_mobile']) && !empty($_POST['adv_mobile']) || isset($_POST['adv_email']) && !empty($_POST['adv_email'])) {
                $mobile_exist = $this->Register_model->check_already_reg_mobile($_POST['adv_mobile']);
                $email_exist = $this->Register_model->check_already_reg_email(strtoupper($_POST['adv_email']));
                if ($mobile_exist[0]['moblie_number'] != $_POST['adv_mobile']) {
                    $this->session->setFlashdata('msg', 'Not Registerd This Mobile Number!');
                    return redirect()->to(base_url('register/ForgetPassword'));
                } else if (strtoupper($email_exist[0]['emailid']) != strtoupper($_POST['adv_email'])) {
                    $this->session->setFlashdata('msg', 'Not Registerd This Email ID!');
                    return redirect()->to(base_url('register/ForgetPassword'));
                } else {
                    if (!empty($_POST['adv_mobile']))
                        $name_array = array('first_name' => $mobile_exist[0]['first_name'], 'last_name' => $mobile_exist[0]['last_name']);
                    if (!empty($_POST['adv_email']))
                        $name_array = array('first_name' => $email_exist[0]['first_name'], 'last_name' => $email_exist[0]['last_name']);
                    // $o = '123456';
                    // $mobile_otp_is = $o;
                    // $email_otp_is = $o;
                    $mobile_otp_is = $this->generateNumericOTP();
                    $email_otp_is = $this->generateNumericOTP();
                    // date_default_timezone_set('Asia/Kolkata');
                    $startTime = date("H:i");
                    $endTime = date("H:i", strtotime('+15 minutes', strtotime($startTime)));
                    $_SESSION['adv_details'] =  array_merge(array(
                        'mobile_no' => $_POST['adv_mobile'],
                        'mobile_otp' => $mobile_otp_is,
                        'email_id' => $_POST['adv_email'],
                        'email_otp' => $email_otp_is,
                        'register_type' => $_POST['register_type'],
                        'start_time' => $startTime,
                        'end_time' => $endTime
                    ), $name_array);
                    if (!empty($_POST['adv_mobile'])) {
                        $typeId = "38";
                        $mobileNo = trim($_POST['adv_mobile']);
                        $smsText = "OTP for changing SC-EFM password is: " . $mobile_otp_is . " ,Please do not share it with any one. - Supreme Court of India";
                        $reqeustt = service('request');
                        $cachet = service('cache');
                        $responsee = service('response');
                        $ipAddress = $reqeustt->getIPAddress();
                        $key = 'otp_request_' . $ipAddress;
                        if ($cachet->get($key) >= 5) {
                            return $responsee->setStatusCode(429)->setBody('Too many requests');
                        }
                        $cachet->save($key, $cachet->get($key) + 1, 60);
                        sendSMS($typeId, $mobileNo, $smsText, SCISMS_Change_Password_OTP);
                    } else if (!empty($_POST['adv_email'])) {
                        $to_email = trim($_POST['adv_email']);
                        $subject = "SC-EFM forget password OTP";
                        $message = "OTP for changing SC-EFM password is: " . $email_otp_is . " ,Please do not share it with any one.";
                        $reqeusttt = service('request');
                        $cachett = service('cache');
                        $responseet = service('response');
                        $ipAddress = $reqeusttt->getIPAddress();
                        $keyt = 'otp_mail_request_' . $ipAddress;
                        if ($cachett->get($keyt) >= 5) {
                            return $responseet->setStatusCode(429)->setBody('Too many requests');
                        }
                        $cachett->save($keyt, $cachett->get($keyt) + 1, 60);
                        send_mail_msg($to_email, $subject, $message);
                        //relay_mail_api($to_email, $subject, $message);
                    }
                    return redirect()->to(base_url('register/AdvOtp'));
                }
            }
        }
    }

    public function adv_get_otp()
    {
        helper(['form', 'url']);
        // Check if captcha is empty
        if (empty($this->request->getPost('userCaptcha'))) {
            $this->session->setFlashdata('msg', 'Captcha is required!');
            return redirect()->to(base_url('Register/ForgetPassword'));
        }
        $reqeusttt = service('request');
        $cachett = service('cache');
        $responseet = service('response');
        $ipAddresst = $reqeusttt->getIPAddress();
        $keyt = 'otp_mail_request_' . $ipAddresst;
        if ($cachett->get($keyt) >= 5) {
            return $responseet->setStatusCode(429)->setBody('Too many requests');
            exit(0);
        }
        $cachett->save($keyt, $cachett->get($keyt) + 1, 60);
        if (!empty($this->request->getPost('adv_mobile')) && empty($this->request->getPost('adv_email'))) {
            $rules = [
                "adv_mobile" => [
                    "label" => "Mobile",
                    "rules" => "numeric|trim|min_length[10]|max_length[10]"
                ],
                "userCaptcha" => [
                    "label" => "Captcha",
                    "rules" => "required|trim"
                ],
            ];
        } elseif (!empty($this->request->getPost('adv_email')) && empty($this->request->getPost('adv_mobile'))) {
            $rules = [
                "adv_email" => [
                    "label" => "Email",
                    "rules" => "valid_email|trim"
                ],
                "userCaptcha" => [
                    "label" => "Captcha",
                    "rules" => "required|trim"
                ],
            ];
        } else {
            $rules = [
                "adv_mobile" => [
                    "label" => "Mobile",
                    "rules" => "numeric|trim|min_length[10]|max_length[10]"
                ],
                "userCaptcha" => [
                    "label" => "Captcha",
                    "rules" => "required|trim"
                ],
            ];
        }
        
        if ($this->validate($rules) === FALSE) {
            $data = [
                'validation' => $this->validator,
            ];
            session()->remove('captcha');
            $this->session->setFlashdata('validation', $this->validator);
            return $this->render('responsive_variant.authentication.forget_password_view', $data);
        }
        // Get input data
        $adv_mobile = $this->request->getPost('adv_mobile');
        $adv_email = $this->request->getPost('adv_email');
        // Check if either mobile or email is empty
        if (empty($adv_mobile) && empty($adv_email)) {
            session()->remove('captcha');
            $this->session->setFlashdata('msg', 'Please Enter Mobile or Email!');
            return redirect()->to(base_url('Register/ForgetPassword'));
        }
        // Check captcha validity
        // print_r(getSessionData("captcha")); echo '<br />'; pr($this->request->getPost('userCaptcha'));
        if (getSessionData("captcha") != $this->request->getPost('userCaptcha')) {
            session()->remove('captcha');
            $this->session->setFlashdata('msg', 'Invalid Captcha!');
            return redirect()->to(base_url('Register/ForgetPassword'));
        }
        
        // Check if mobile and email exist
        $mobile_exist = $this->Register_model->check_already_reg_mobile($adv_mobile);
        $email_exist = $this->Register_model->check_already_reg_email(strtoupper($adv_email));

        $check_user = $this->Register_model->check_already_reg_user($adv_mobile, strtoupper($adv_email));
        if (empty($check_user)) {
            session()->remove('captcha');
            $this->session->setFlashdata('msg', 'Not Registered With This Mobile Number or Email ID!');
            return redirect()->to(base_url('Register/ForgetPassword'));
        }
        // Set name array based on input
        $name_array = [];
        if (!empty($adv_mobile)) {
            $name_array = ['first_name' => $mobile_exist[0]['first_name'], 'last_name' => $mobile_exist[0]['last_name']];
        } elseif (!empty($adv_email)) {
            $name_array = ['first_name' => $email_exist[0]['first_name'], 'last_name' => $email_exist[0]['last_name']];
        }
        // Generate OTPs and set session data
        $mobile_otp_is = $this->generateNumericOTP();
        $email_otp_is = $this->generateNumericOTP();
        $startTime = date("H:i");
        $endTime = date("H:i", strtotime('+15 minutes', strtotime($startTime)));
        $_SESSION['adv_details'] = array_merge([
            'mobile_no' => $adv_mobile,
            'mobile_otp' => $mobile_otp_is,
            'email_id' => $adv_email,
            'email_otp' => $email_otp_is,
            'register_type' => $this->request->getPost('register_type'),
            'start_time' => $startTime,
            'end_time' => $endTime
        ], $name_array);
        // Send OTP via SMS or email
        if (!empty($adv_mobile)) {
            $sms_datetime = date('Y-m-d H:i:s');
            $ip_address = get_client_ip();
            $Common_model = new CommonModel();
            $max_requests = 5; // Maximum number of requests
            $time_period = 5; // Time period in seconds (e.g., 1 sec)
            $request_time_time_period = date('Y-m-d H:i:s', time() - $time_period);
            // $request_count = $Common_model->check_efiling_sms_mobile_no_log($adv_mobile, $request_time_time_period, $ip_address);
            $typeId = "38";
            $mobileNo = trim($adv_mobile);
            $smsText = "OTP for changing SC-EFM password is: " . $mobile_otp_is . " ,Please do not share it with any one. - Supreme Court of India";
            $reqeustt = service('request');
            $cachet = service('cache');
            $responsee = service('response');
            $ipAddress = $reqeustt->getIPAddress();
            $key = 'otp_request_' . $ipAddress;
            if ($cachet->get($key) >= 5) {
                $responsee->setStatusCode(429)->setBody('Too many requests');
                $text_msg = 'Please wait ' . SMS_RESEND_LIMIT . ' seconds and try again!'; // code to stop sms flooding
                session()->setFlashdata('error', $text_msg);
                return redirect()->to(base_url('Register/ForgetPassword'));
            }else{
                $cachet->save($key, $cachet->get($key) + 1, 60);
                sendSMS($typeId, $mobileNo, $smsText, SCISMS_Change_Password_OTP);
            }
        } elseif (!empty($adv_email)) {
            $Common_model = new CommonModel();
            //$_SESSION['last_sms'] = time();
            $_SESSION['last_sms_to_email'] = $adv_email;
            $_SESSION['last_sms_ip'] = get_client_ip();
            $ip_address = get_client_ip();
            // Define rate limit parameters
            $max_requests = 5; // Maximum number of requests
            $time_period = 5; // Time period in seconds (e.g., 1 hour)
            $request_time_time_period=date('Y-m-d H:i:s', time() - $time_period);
            $request_count = $Common_model->check_efiling_sms_email_log($adv_email,$request_time_time_period,$ip_address);
            if ($request_count >= $max_requests) {
                // Too many requests, show an error message
                $text_msg = 'Please wait ' . SMS_RESEND_LIMIT . ' seconds and then try again!'; // code to stop sms flooding
                session()->setFlashdata('msg', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a> <p style="text-align: center;">' . $text_msg . '</p> </div>');
                return FALSE;
            }else{
                $to_email = trim($adv_email);
                $subject = "SC-EFM forget password OTP";
                $message = "OTP for changing SC-EFM password is: " . $email_otp_is . " ,Please do not share it with any one.";
                send_mail_msg($to_email, $subject, $message);
            }
        }
        $this->session->setFlashdata('msg_success', 'OTP sent successfully! OTP is valid for 15 minutes only.');
        return $this->AdvOtp();
    }


    public function AdvOtp()
    {
        $session = session();
        if ($session->get('adv_details')['register_type'] != 'Forgot Password') {
            // pr($_SESSION);
            if (empty($session->get('adv_details')['mobile_no']) || empty($session->get('adv_details')['email_id'])) {
                return redirect()->to(base_url('Register/ForgetPassword'));
            }
        }
        $session->remove('profile_image');
        return $this->render('responsive_variant.authentication.adv_otp_view');
    }

    public function verify()
    {

        $request = \Config\Services::request();
        // pr($_SESSION);       
        $session = session();
        if (!$session->has('adv_details')) {
            return redirect()->to(base_url('register'));
        }
        $userCaptcha = $this->request->getPost('userCaptcha');
        $registerType = $session->get('adv_details')['register_type'];
        // date_default_timezone_set('Asia/Kolkata');
        $currentTime = date("H:i");

        if ($this->session->get('captcha') != $userCaptcha) {
            session()->remove('captcha');
            // pr($this->session->getFlashdata('captcha'));
            $this->session->setFlashdata('msg', 'Invalid Captcha!');
            return $this->render('responsive_variant.authentication.adv_otp_view');
        }
        // Initialize default validation rules for both OTPs, even before conditional logic
        $rules = [
            'adv_mobile_otp' => [
                "label" => "Mobile OTP",
                "rules" => 'required|numeric|trim|min_length[6]|max_length[6]',
            ],
            'adv_email_otp' => [
                "label" => "Email OTP",
                "rules" => 'required|numeric|trim|min_length[6]|max_length[6]',
            ],
        ];


        if ($registerType == 'Forgot Password') {

            // If only Mobile OTP is provided and Email OTP is empty
            if (!empty($this->request->getPost('adv_mobile_otp')) && empty($this->request->getPost('adv_email_otp'))) {
                $rules = [
                    'adv_mobile_otp' => [
                        "label" => "Mobile OTP",
                        "rules" => 'required|numeric|trim|min_length[6]|max_length[6]',
                    ]
                ];
                $this->session->setFlashdata('errmsg', 'Mobile OTP Required');
            }
            // If only Email OTP is provided and Mobile OTP is empty
            elseif (!empty($this->request->getPost('adv_email_otp')) && empty($this->request->getPost('adv_mobile_otp'))) {
                $rules = [
                    'adv_email_otp' => [
                        "label" => "Email OTP",
                        "rules" => 'required|numeric|trim|min_length[6]|max_length[6]',
                    ]
                ];
                $this->session->setFlashdata('msg', 'Email OTP Required');
            }
            // If neither OTP is provided, ensure a message is shown but rules remain intact
            elseif (empty($this->request->getPost('adv_mobile_otp')) && empty($this->request->getPost('adv_email_otp'))) {
                $this->session->setFlashdata('msg', 'Either Mobile OTP or Email OTP is required.');
            }
        }
        if ($this->validate($rules) === FALSE) {
            $data['validation'] = $this->validator;
            return $this->render('responsive_variant.authentication.adv_otp_view', $data);
        } 
        elseif ($this->session->get('captcha') != $userCaptcha) {
            // session()->remove('captcha');
            $this->session->setFlashdata('msg', 'Invalid Captcha!');
            return $this->render('responsive_variant.authentication.adv_otp_view');
        } 
        else {
            // Check OTP expiration and verification

            $max_requests = 3; // Maximum number of requests
            $time_period = 60; // Time period in seconds (e.g., 1 hour)

            if (!session()->get('otp_verify_requests')) {
                session()->set('otp_verify_requests', 0);
                session()->set('otp_verify_first_request_time', strtotime($currentTime));
            }

            $firstRequestTime = session()->get('otp_verify_first_request_time');
            $requestCount = session()->get('otp_verify_requests');

            if (strtotime($currentTime) - $firstRequestTime > $time_period) {
                // Time period has elapsed, reset the request count
                session()->set('otp_verify_requests', 1); // Start a new count
                session()->set('otp_verify_first_request_time', strtotime($currentTime));
            } else {
                // Increment the request count if within the time period
                if ($requestCount < $max_requests) {
                    session()->set('otp_verify_requests', $requestCount + 1);
                } else {
                    // Maximum attempts reached
                    $text_msg = 'You have reached the maximum attempt limit'; // code to stop sms flooding
                    session()->setFlashdata('msg', $text_msg);
                    return redirect()->to(base_url('/'));
                }
            }

            $mobile_status = $this->verifyOTP($currentTime, 'adv_mobile_otp', 'mobile_otp');
            $email_status = $this->verifyOTP($currentTime, 'adv_email_otp', 'email_otp');
            $remaining_attempts = $max_requests - session()->get('otp_verify_requests');
            $session->set('remaining_attempts', $remaining_attempts);
            $session->set('verify_details', ['mobile_verified' => $mobile_status, 'email_verified' => $email_status]);
            if ($registerType == 'Advocate') {

                if ($mobile_status == 1 && $email_status == 1) {
                    $session->set('self_register_arguing_counsel', true);
                    return redirect()->to(base_url('saveArguingCounselCompleteDetails'));
                } else {
                    if($remaining_attempts != 0){
                        $this->session->setFlashdata('msg', 'Incorrect OTP. (' . $max_requests . ' attempts allowed. Only ' . $remaining_attempts . ' attempts left.)');
                        return $this->render('responsive_variant.authentication.adv_otp_view');
                    }else{
                        $this->session->setFlashdata('otpmsg', 'You have reached the maximum attempt limit.');
                        return redirect()->to(base_url('/'));
                    }
                }
            } elseif ($registerType == 'Forgot Password') {
                if ($mobile_status == 1 || $email_status == 1) {
                    return $this->update_password();
                } else {
                    if($remaining_attempts != 0){
                        $this->session->setFlashdata('msg', 'Incorrect OTP. (' . $max_requests . ' attempts allowed. Only ' . $remaining_attempts . ' attempts left.)');
                        return $this->render('responsive_variant.authentication.adv_otp_view');
                    }else{
                        $this->session->setFlashdata('otpmsg', 'You have reached the maximum attempt limit.');
                        return redirect()->to(base_url('/'));
                    }
                }
            } else {
                if ($mobile_status == 1 && $email_status == 1) {
                    $session->set('self_register_arguing_counsel', false);
                    return redirect()->to(base_url('Register/AdvSignUp'));
                } else {
                    if($remaining_attempts != 0){
                        $this->session->setFlashdata('msg', 'Incorrect OTP. (' . $max_requests . ' attempts allowed. Only ' . $remaining_attempts . ' attempts left.)');
                        return $this->render('responsive_variant.authentication.adv_otp_view');
                    }else{
                        $this->session->setFlashdata('otpmsg', 'You have reached the maximum attempt limit.');
                        return redirect()->to(base_url('/'));
                    }
                }
            }



            // if ($mobile_status == 1 || $email_status == 1) {
            //     // pr($registerType);
            //     if ($registerType == 'Advocate') {               
            //         $session->set('self_register_arguing_counsel', true);                    
            //         return redirect()->to(base_url('saveArguingCounselCompleteDetails'));
            //     } elseif ($registerType == 'Forgot Password') {               
            //         // $session->set('self_register_arguing_counsel', true);                    
            //         // return redirect()->to(base_url('saveArguingCounselCompleteDetails'));
            //         return $this->update_password();
            //     } else {                   
            //         $session->set('self_register_arguing_counsel', false);
            //         return redirect()->to(base_url('Register/AdvSignUp'));
            //     }
            // } else {
            //     // echo 'Hello'; pr($_SESSION);
            //     // $captcha_value = captcha_generate_test();
            //     // $data['captcha']['image'] = $captcha_value['image'];
            //     // $data['captcha']['word'] = $captcha_value['word'];               
            //     $this->session->setFlashdata('msg', 'OTP Verification Failed');                
            //     return $this->render('responsive_variant.authentication.adv_otp_view');
            // }
        }
    }

    private function verifyOTP($currentTime, $postOtpKey, $sessionOtpKey)
    {
        if (isset(getSessionData('adv_details')[$sessionOtpKey]) && getSessionData('adv_details')[$sessionOtpKey] == $this->request->getPost($postOtpKey)) {
            if (strtotime($currentTime) <= strtotime(getSessionData('adv_details')['end_time'])) {
                return true;
            }
        }
        return false;
    }

    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function generateNumericOTP($n = 6)
    {
        $generator = "1357902468";
        $result = "";
        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand() % (strlen($generator))), 1);
        }
        return $result;
    }

    function update_password()
    {
        return $this->render('responsive_variant.authentication.update_password_view');
    }

    function valid_password($password = '')
    {
        $password = trim($password);
        $regex_lowercase = '/[a-z]/';
        $regex_uppercase = '/[A-Z]/';
        $regex_number = '/[0-9]/';
        $regex_special = '/[!@#$%^&*()\-_=+{};:,<.>ยง~]/';
        if (empty($password)) {
            return FALSE;
        }
        if (preg_match_all($regex_lowercase, $password) < 1) {
            return FALSE;
        }
        if (preg_match_all($regex_uppercase, $password) < 1) {
            return FALSE;
        }
        if (preg_match_all($regex_number, $password) < 1) {
            return FALSE;
        }
        if (preg_match_all($regex_special, $password) < 1) {
            return FALSE;
        }
        if (strlen($password) < 8) {
            return FALSE;
        }
        if (strlen($password) > 32) {
            return FALSE;
        }
        return TRUE;
    }

    function decodeData($pass, $data)
    {
        $sanitize = new Auto_Sanitize();
        for ($i = 0; $i < 10; $i++) {
            $data = $sanitize->cryptoJsAesDecrypt($pass, $data);
        }
        $dataArray = explode('hgtsd12@_hjytr', $data);
        return $dataArray[0];
    }

    function update_user_password()
    {

        $forget_mobile = '';
        $forget_email = '';

        $rules = [
            "password" => [
                "label" => "New Password",
                "rules" => "required|trim"
            ],
            "confirm_password" => [
                "label" => "Confirm Password",
                "rules" => "required|trim"
            ],
        ];
        $password = $_POST['txt_password'];
        $password = str_replace("'", "", $password);

        $salt = $_POST['salt'];
        // pr($salt);

        $decoded_password = $this->decodeData($salt, $password);
        // pr($decoded_password);

        // echo $decoded_password; exit(0);
        if ($this->validate($rules) === FALSE) {
            $data = [
                'validation' => $this->validator,
            ];
            return $this->render('responsive_variant.authentication.update_password_view', $data);
        } else if (!$this->valid_password($decoded_password)) {
            //$this->session->setFlashdata('msg', 'Password policy has to be followed.');
            //return redirect()->to(base_url('register/ForgetPassword/update_user_password'));
            $this->session->setFlashdata('msg', 'Failed. Password policy has to be followed.');
            return redirect()->to(base_url('Register/ForgetPassword'));
        } else {
            if ($_POST['password'] != $_POST['confirm_password']) {
                $this->session->setFlashdata('not_match', 'Confirm Password not matched!');
                // return redirect()->to(base_url('register/ForgetPassword/update_user_password'));
                return $this->render('responsive_variant.authentication.update_password_view');
            }
            if (!empty(getSessionData('adv_details')['mobile_no'])) {
                $forget_mobile = getSessionData('adv_details')['mobile_no'];
            }
            if (!empty(getSessionData('adv_details')['email_id'])) {
                $forget_email = getSessionData('adv_details')['email_id'];
            }
            // $data = array('password' => hash('sha256',$_POST['confirm_password']));
            $data = array(
                'password' => $_POST['confirm_password'],
                'is_first_pwd_reset' => false,
            );
            $resultCheck = $this->Register_model->check_old_password($forget_mobile, $_POST['confirm_password']);
            if ($resultCheck) {
                $this->session->setFlashdata('not_match', 'Entered password was already used earlier, please enter different password.');
                return $this->render('responsive_variant.authentication.update_password_view');
            } else{
                $passUpdate = $this->Register_model->update_user_password($data, $forget_mobile, $forget_email);
                if ($passUpdate) {
                    $_SESSION['adv_details']['ForgetPasswordDone'] = 'ForgetPasswordDone';
                    // session()->remove('captcha');
                    $this->session->setFlashdata('msg_success', 'Password Update Successful.');
                    // return redirect()->to('/');
                    return redirect()->to(base_url('/'));
                    exit(0);
                } else {
                    $this->session->setFlashdata('msg', 'Failed Password update!');
                    return redirect()->to(base_url('register/ForgetPassword/update_user_password'));
                }
            }
        }
    }

    public function AdvSignUp()
    {
        $session = session();
        // Check if mobile_no and email_id are set in session
        if (empty(getSessionData('adv_details')['mobile_no']) || empty(getSessionData('adv_details')['email_id'])) {
            return redirect()->to(base_url('register'));
        }
        // Unset image_and_id_view from session
        $session->remove('image_and_id_view');
        // Get state list and advocate details
        $data['select_state'] = $this->Register_model->get_state_list();
        $mobileNo = getSessionData('adv_details')['mobile_no'];
        $emailId = getSessionData('adv_details')['email_id'];
        $data['advDetailsIcmis'] = $this->efiling_webservices->getBarTable($mobileNo, $emailId);
        // Load views
        // echo view('responsive_variant/authentication/adv_signup_view', $data);
        // echo view('responsive_variant/authentication/adv_signup_nav');
        // return $this->render('responsive_variant.authentication.adv_signup_view',$data);
        // return $this->render('responsive_variant.authentication.adv_signup_nav',$data);
        $this->render('responsive_variant.authentication.adv_signup_view', $data);
    }
}
