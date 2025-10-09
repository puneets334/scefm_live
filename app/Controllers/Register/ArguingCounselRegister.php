<?php

namespace App\Controllers\Register;
use App\Controllers\BaseController;
use App\Models\Register\RegisterModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\Slice;

// if (!defined('BASEPATH')) exit('No direct script access allowed');

class ArguingCounselRegister extends BaseController {

    protected $Register_model;
    protected $slice;
    protected $form_validation;
    protected $session;
    protected $efiling_webservices;

    public function __construct() {
        parent::__construct();
        // $this->load->library('encrypt');
        // $this->load->model('register/Register_model');
        // $this->load->library('webservices/efiling_webservices');
        // $this->load->library('slice');
        // $this->load->helper('security');
        $this->Register_model = new RegisterModel();
        $this->slice = new Slice();
        $this->efiling_webservices = new Efiling_webservices();
        $this->session = \Config\Services::session();
        helper(['security']);
    }

    public function index(){ }

    public function addAarguingCounsel() {
        unset($_SESSION['adv_details']);
        /* $captcha_value = captcha_generate();
        $data['captcha']['image'] = $captcha_value['image'];
        $data['captcha']['word'] = $captcha_value['word'];*/
        $this->render('register.arguing_counsel_register_view');
    }

    public function senOtp() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        unset($_SESSION['self_register_arguing_counsel']);
        unset($_SESSION['self_arguing_counsel']);
        unset($_SESSION['arguingCounselId']);
        unset($_SESSION['aor_register']);
        // unset($_SESSION['captcha']);
        if(empty($_POST)){
            $this->session->setFlashdata('msg', 'Invalid Request!');
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if ((isset($_SESSION["captcha"]) && isset($_POST['userCaptcha'])) && ($_SESSION["captcha"] != $_POST['userCaptcha'])) {
            $this->session->setFlashdata('msg', 'Invalid Captcha!');
            return redirect()->to(base_url('arguingCounselRegister'));
            exit(0);
        }
        $rules = [
            "adv_mobile" => [
                "label" => "Mobile",
                "rules" => "required|numeric|trim|min_length[10]|max_length[10]"
            ],
            "adv_email" => [
                "label" => "Email",
                "rules" => "required|valid_email|trim"
            ],
            "userCaptcha" => [
                "label" => "userCaptcha",
                "rules" => "required|trim"
            ]
        ];
        if ($this->validate($rules) === FALSE) {
            $data = [
                'validation' => $this->validator,
            ];
            $errors = $this->validator->getErrors();
             
            if($_REQUEST['register_type']=='Advocate'){
                if(!empty($errors['adv_mobile'])){
                    $this->session->setFlashdata('msg', $errors['adv_mobile']); 
                }
                if(!empty($errors['adv_email'])){
                    $this->session->setFlashdata('msg', $errors['adv_email']); 
                }
                return redirect()->to(base_url('arguingCounselRegister')); 
            } 
            $this->addAarguingCounsel();
        } else {
            if (isset($_POST['adv_mobile']) && (substr($_POST['adv_mobile'],0,1) == 0 || substr($_POST['adv_mobile'],0,1) == 1 || substr($_POST['adv_mobile'],0,1) == 2 || substr($_POST['adv_mobile'],0,1) == 3 || substr($_POST['adv_mobile'],0,1) == 4 || substr($_POST['adv_mobile'],0,1) == 5)) {
                $this->session->setFlashdata('msg', 'Mobile Number is not valid!');
                return redirect()->to(base_url('arguingCounselRegister'));
                exit(0);
            }
            if (isset($_POST['adv_mobile']) && !empty($_POST['adv_mobile']) || isset($_POST['adv_email']) && !empty($_POST['adv_email'])) {
                $mobile_already_exist = $this->Register_model->check_already_reg_mobile($_POST['adv_mobile']);
                $email_already_exist = $this->Register_model->check_already_reg_email($_POST['adv_email']);
                $mobile_arguing_counsel_exist = $this->Register_model->check_already_reg_mobile_arguing_counsel($_POST['adv_mobile']);
                $email_arguing_counsel_exist = $this->Register_model->check_already_reg_email_arguing_counsel($_POST['adv_email']);
                if ($_POST['register_type'] == 'Advocate') {
                    if (isset($mobile_already_exist) && !empty($mobile_already_exist) && $mobile_already_exist[0]['moblie_number'] == $_POST['adv_mobile']) {
                        $this->session->setFlashdata('msg', 'Mobile Number Already Registerd! Please click on forgot password and reset your password!');
                        return redirect()->to(base_url('arguingCounselRegister'));
                    }
                    if (isset($email_already_exist) && !empty($email_already_exist) && $email_already_exist[0]['emailid'] == $_POST['adv_email']) {
                        $this->session->setFlashdata('msg', 'Email ID Already Registerd! Please click on forgot password and reset your password!');
                        return redirect()->to(base_url('arguingCounselRegister'));
                    }
                    if (isset($mobile_arguing_counsel_exist) && !empty($mobile_arguing_counsel_exist) && $mobile_arguing_counsel_exist[0]['mobile_number'] == $_POST['adv_mobile']) {
                        $this->session->setFlashdata('msg', 'Mobile Number Already Registerd! Please click on forgot password and reset your password!');
                        return redirect()->to(base_url('arguingCounselRegister'));
                    }
                    if (isset($email_arguing_counsel_exist) && !empty($email_arguing_counsel_exist) && $email_arguing_counsel_exist[0]['emailid'] == $_POST['adv_email']) {
                        $this->session->setFlashdata('msg', 'Email ID Already Registerd! Please click on forgot password and reset your password!');
                        return redirect()->to(base_url('arguingCounselRegister'));
                    }
                    // $advDetailsIcmis=$this->efiling_webservices->getBarTable($_POST['adv_mobile'],$_POST['adv_email']);
                    // if ($advDetailsIcmis[0]->moblie_number != $_POST['adv_mobile'] || $advDetailsIcmis[0]->emailid != $_POST['adv_email']) {
                    //     $this->session->setFlashdata('msg', 'Mobile Number And Email ID Not Vailid for Bar! Please Contact SCI !');
                    //     return redirect()->to(base_url('arguingCounselRegister'));
                    // }
                }
                // $o = '123456';
                // $mobile_otp_is = $o;
                // $email_otp_is = $o;
                $mobile_otp_is = $this->generateNumericOTP();
                $email_otp_is = $this->generateNumericOTP();
                $startTime=date("H:i");
                $endTime = date("H:i", strtotime('+15 minutes', strtotime($startTime)));
                $_SESSION['adv_details'] = array(
                    'mobile_no' => $_POST['adv_mobile'],
                    'mobile_otp' => $mobile_otp_is,
                    'email_id' => $_POST['adv_email'],
                    'email_otp' => $email_otp_is,
                    'register_type' => $_POST['register_type'],
                    'start_time'=>$startTime,
                    'end_time'=>$endTime
                );
                if(!empty($_POST['adv_mobile'])){
                    $mobileNo=trim($_POST['adv_mobile']);
                    $smsText="OTP for Registration SC-EFM password is: ".$mobile_otp_is.", Please do not share it with any one. - Supreme Court of India";
                    $reqeustt = service('request');
                    $cachet = service('cache');
                    $responsee = service('response');
                    $ipAddress = $reqeustt->getIPAddress();
                    $key = 'otp_request_' . $ipAddress;
                    if ($cachet->get($key) >= 5) {
                        return $responsee->setStatusCode(429)->setBody('Too many requests');
                    }
                    $cachet->save($key, $cachet->get($key) + 1, 60);
                    sendSMS(SMS_EMAIL_API_USER,$mobileNo,$smsText,SCISMS_Registration_OTP);
                }
                if(!empty($_POST['adv_email'])){
                    $to_email=trim($_POST['adv_email']);
                    $subject="SC-EFM Registration password OTP";
                    $message="OTP for Registration SC-EFM password is: ".$email_otp_is." ,Please do not share it with any one.";
                    $reqeustt = service('request');
                    $cache = service('cache');
                    $responsee = service('response');
                    $ipAddress = $reqeustt->getIPAddress();
                    $keyMail = 'otp_mail_request_' . $ipAddress;
                    if ($cache->get($keyMail) >= 5) {
                        return $responsee->setStatusCode(429)->setBody('Too many requests');
                    }
                    $cache->save($keyMail, $cache->get($keyMail) + 1, 60);
                    send_mail_msg($to_email, $subject, $message);
                }
                return redirect()->to(base_url('register/AdvOtp'));
            }
        }
    }

    function generateNumericOTP($n = 6) {
        $generator = "1357902468";
        $result = "";
        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand() % (strlen($generator))), 1);
        }
        return $result;
    }

}