<?php

namespace App\Controllers\Register;

use App\Controllers\BaseController;
use App\Libraries\Slice;
// if (!defined('BASEPATH')) exit('No direct script access allowed');

class AdvOtp extends BaseController {

    protected $slice;
    protected $session;

    public function __construct() {
        parent::__construct();
        // $this->load->library('slice');
        $this->slice = new Slice();
        $this->session = \Config\Services::session();
    }
    
    public function index() {
        if(!isset($_SESSION['adv_details'])){
            return redirect()->to(base_url('/'));
        }
        if(isset($_SESSION['adv_details']) && $_SESSION['adv_details']['register_type'] != 'Forget Password'){ 
            if(empty($_SESSION['adv_details']['mobile_no']) || empty($_SESSION['adv_details']['email_id'])){
                return redirect()->to(base_url('register'));
            }
        }        
        unset($_SESSION['profile_image']);
        
        
    	/*$captcha_value = captcha_generate();
        $data['captcha']['image'] = $captcha_value['image'];
        $data['captcha']['word'] = $captcha_value['word'];*/
        /*$this->load->view('login/login_header');
        $this->load->view('register/adv_otp_view',$data);
		$this->load->view('login/login_footer');*/
        $this->render('responsive_variant.authentication.adv_otp_view');
    }

    function verify() {
        /*$data['captcha']['image'] = $captcha_value['image'];
        $data['captcha']['word'] = $captcha_value['word'];        
        $userCaptcha = escape_data($_POST['userCaptcha']);  
        if ($this->session->userdata("captchaWord") != $userCaptcha) {
            $this->session->setFlashdata('msg', 'Invalid Captcha!'); 
            return redirect()->to(base_url('register/AdvOtp'));
        } */
        // if($_SESSION['adv_details']['register_type'] == 'Forget Password') {
        //     $this->form_validation->set_rules('adv_mobile_otp', 'Mobile OTP', 'numeric|trim|is_required|min_length[6]|max_length[6]');
        //     $this->form_validation->set_rules('adv_email_otp', 'Email OTP', 'numeric|min_length[6]|max_length[6]|trim|is_required'); 
        // } else {
        //     $this->form_validation->set_rules('adv_mobile_otp', 'Mobile OTP', 'required|numeric|trim|is_required|min_length[6]|max_length[6]');
        //     $this->form_validation->set_rules('adv_email_otp', 'Email OTP', 'required|numeric|min_length[6]|max_length[6]|trim|is_required');
        //     $this->session->setFlashdata('msg', 'Mobile OTP And Email OTP Required');
        // }
        // $this->form_validation->set_error_delimiters('<div class="uk-alert-danger">', '</div>');
        // if ($this->form_validation->run() == FALSE) { 
        //     $captcha_value = captcha_generate();
        //     $data['captcha']['image'] = $captcha_value['image'];
        //     $data['captcha']['word'] = $captcha_value['word'];
        // 	/*$this->load->view('login/login_header');
        // 	$this->load->view('register/adv_otp_view',$data);
		// 	$this->load->view('login/login_footer');*/
        //     $this->render('responsive_variant.authentication.adv_otp_view',$data);
        // } else {
        $validation =  \Config\Services::validation();
        if($_SESSION['adv_details']['register_type'] == 'Forget Password') {
            $rules=[
                "adv_mobile_otp" => [
                    "label" => "Mobile OTP",
                    "rules" => "numeric|trim|is_required|min_length[6]|max_length[6]"
                ],
                "adv_email_otp" => [
                    "label" => "Email OTP",
                    "rules" => "numeric|min_length[6]|max_length[6]|trim|is_required"
                ],
            ];
        } else {
            $rules=[
                "adv_mobile_otp" => [
                    "label" => "Mobile OTP",
                    "rules" => "required|numeric|trim|is_required|min_length[6]|max_length[6]"
                ],
                "adv_email_otp" => [
                    "label" => "Email OTP",
                    "rules" => "required|numeric|min_length[6]|max_length[6]|trim|is_required"
                ],
            ];
        }
        if ($this->validate($rules) === FALSE) {
            $data = [
                'validation' => $this->validator,
                'currentPath' => $this->slice->getSegment(1) ?? 'public',
            ];
            $captcha_value = captcha_generate();
            $data['captcha']['image'] = $captcha_value['image'];
            $data['captcha']['word'] = $captcha_value['word'];
            $this->render('responsive_variant.authentication.adv_otp_view', $data);
        } else {
            date_default_timezone_set('Asia/Kolkata');
            $currentTime=date("H:i");
            if($_SESSION['adv_details']['register_type'] != 'Forget Password'){
        	if(isset($_POST['adv_mobile_otp']) && !empty($_POST['adv_mobile_otp']) || isset($_POST['adv_email_otp']) && !empty($_POST['adv_email_otp'])){				
				/* Added on 13-03-2023 by Amit Tripathi for checking OTP expiration */
                if(strtotime($currentTime)>strtotime($_SESSION['adv_details']['end_time'])){
                    $mobile_status ='expired';
                } else if($_SESSION['adv_details']['mobile_otp'] == $_POST['adv_mobile_otp']){
					$mobile_status ='done';
				} else{
					$mobile_status ='fail';
				}
                if(strtotime($currentTime)>strtotime($_SESSION['adv_details']['end_time'])){
                    $email_status ='expired';
                } else if($_SESSION['adv_details']['email_otp'] == $_POST['adv_email_otp']){
					$email_status ='done';
				} else{
					$email_status ='fail';
				}
        		$_SESSION['verify_details'] =  array('mobile_verified'=>$mobile_status, 'email_verified'=>$email_status);
                if ($mobile_status == 'expired' || $email_status == 'expired'){
                    $captcha_value = captcha_generate();
                    $data['captcha']['image'] = $captcha_value['image'];
                    $data['captcha']['word'] = $captcha_value['word'];
                    $this->session->setFlashdata('msg', 'OTP Expired.');
                    $this->render('responsive_variant.authentication.adv_otp_view',$data);
                } else if($mobile_status == 'done' && $email_status == 'done'){
                    $this->session->setFlashdata('msg', 'OTP Verification Successful !');
                    if(!empty($_SESSION['adv_details']['register_type']) && $_SESSION['adv_details']['register_type'] == 'Advocate'){
                        $_SESSION['self_register_arguing_counsel'] = true;
                        return redirect()->to(base_url('arguingCounselCompleteDetails'));
                    } else{
                        $_SESSION['self_register_arguing_counsel'] = false;
                        return redirect()->to(base_url('register/AdvSignUp'));
                    }
                } else{
                    $captcha_value = captcha_generate();
                    $data['captcha']['image'] = $captcha_value['image'];
                    $data['captcha']['word'] = $captcha_value['word'];
                    $this->session->setFlashdata('msg', 'OTP Verification Failed');
                    $this->render('responsive_variant.authentication.adv_otp_view',$data);
                }
            }
        }
        if($_SESSION['adv_details']['register_type'] == 'Forget Password'){
                if(strtotime($currentTime)>= strtotime($_SESSION['adv_details']['end_time'])){
                    $mobile_status ='expired';
                } else if($_SESSION['adv_details']['mobile_otp'] == $_POST['adv_mobile_otp']){
                    $mobile_status ='done';
                } else{
                    $mobile_status ='fail';
                }
                if(strtotime($currentTime)>= strtotime($_SESSION['adv_details']['end_time'])){
                    $email_status ='expired';
                } else if($_SESSION['adv_details']['email_otp'] == $_POST['adv_email_otp']){
                    $email_status ='done';
                } else{
                    $email_status ='fail';
                }
                $_SESSION['verify_details'] =  array('mobile_verified'=>$mobile_status, 'email_verified'=>$email_status);                
                if($email_status == 'done'){
                    $this->session->setFlashdata('msg', 'OTP Verification Successful !');  
                    return redirect()->to(base_url('register/ForgetPassword/update_password'));
                } elseif ($mobile_status == 'done') {
                    $this->session->setFlashdata('msg', 'OTP Verification Successful !');  
                    return redirect()->to(base_url('register/ForgetPassword/update_password'));
                } elseif ($mobile_status == 'expired' || $email_status == 'expired') {
                    $this->session->setFlashdata('msg', 'OTP Expired !');
                    return redirect()->to(base_url('register/ForgetPassword'));
                } else{
                    $this->session->setFlashdata('msg', 'OTP Verification Failed'); 
                    return redirect()->to(base_url('register/ForgetPassword'));
                }   
            }
        }
    }

    function resend_otp(){ 
        unset($_SESSION['adv_details']['mobile_otp']);
        unset($_SESSION['adv_details']['email_otp']); 
        // $o = '123456';
        // $mobile_otp_is = $o;
        // $email_otp_is = $o; 
        $mobile_otp_is = $this->generateNumericOTP();
        $email_otp_is = $this->generateNumericOTP(); 
        if(empty($_SESSION['adv_details']['mobile_otp']) || empty($_SESSION['adv_details']['email_otp'])){
            date_default_timezone_set('Asia/Kolkata');
            $startTime=date("H:i");
            $endTime = date("H:i", strtotime('+15 minutes', strtotime($startTime)));
            $_SESSION['adv_details'] =  array('mobile_no'=>$_SESSION['adv_details']['mobile_no'], 
                'mobile_otp'=>$mobile_otp_is,
                'email_id'=>$_SESSION['adv_details']['email_id'],
                'email_otp'=>$email_otp_is,
                'register_type'=>$_SESSION['adv_details']['register_type'],
                'start_time'=>$startTime,
                'end_time'=>$endTime
            );
            return redirect()->to(base_url('register/AdvOtp'));
        }          
    }

    function generateNumericOTP($n = 6) {  
        $generator = "1357902468";  
        $result = "";  
        for ($i = 1; $i <= $n; $i++) { 
            $result .= substr($generator, (rand()%(strlen($generator))), 1); 
        }   
        return $result; 
    }

}