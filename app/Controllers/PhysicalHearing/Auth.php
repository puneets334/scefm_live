<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('common');
        $this->load->helper('encryptdecrypt');
        $this->load->helper('myarray');
        $this->load->helper('curl');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->database('icmis');
        $this->load->model('auth_model');
        $this->load->library('session');
    }

    public function index($msg=""){

        $data['page_title']='Login';
        if(!empty($msg)){
            $data['msg']=$msg;
        }
//		$this->load->helper('captcha');
//		$captchaArr = array(
//			'word'          => rand(1,99999),
//			'img_path'      => 'assets/captcha/images/',
//			'img_url'       => base_url('assets/captcha/images/'),
//			'font_path'     => 'system/fonts/texb.ttf',
//			'img_width'     => '150',
//			'img_height'    => 30,
//			'word_length'   => 8,
//			'colors'        => array(
//				'background'     => array(255, 255, 255),
//				'border'         => array(255, 255, 255),
//				'text'           => array(0, 0, 0),
//				'grid'           => array(255, 75, 100)
//			)
//		);
		//$data['captcha'] = create_captcha($captchaArr);
		//var_dump(create_captcha($captchaArr));
      // echo '<pre>' ;print_r($captchaArr);
        //exit;
        $this->load->view('physical_hearing/auth', $data);
    }
    public function resend_otp(){
		$params['mobile']=$this->session->loginData['mobile'];
		$res = sendOtp($params);
		if(isset($res) && !empty($res)){
			$resend_otp='Y';
			$this->session->set_flashdata('msg','<div class="alert alert-success text-center">Successfully Send Resend OTP.</div>');
		}else{
			$resend_otp='N';
			$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Error,Resend OTP not Send.</div>');
		}
		$session_data = $this->session->userdata('loginData');
		$session_data['resend_otp'] = $resend_otp;
		$this->session->set_userdata("loginData", $session_data);

		//$data['page_title']='Login';
		//$this->load->view('physical_hearing/otp_view', $data);
		redirect('Auth/otpForm');
	}
	public function otpForm(){
		$data['page_title']='Login';
		$this->load->view('physical_hearing/otp_view', $data);
	}
    public function getOtp(){
		$userCaptcha = $_POST['userCaptcha'];
		if ($_SESSION["captcha"] != $userCaptcha) {
			$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Error,Invalid Captcha!</div>');
			$this->index();
		     //redirect('Auth/otpForm');exit(0);
		}
else{
        $data['page_title']='Login';
        $tytpe='';
        $mobile='';
        $aor_code='';
        if(!empty($this->input->post('type'))){
            $type= (int)$this->input->post('type');
			$this->session->set_userdata('userType', $type);
           /* if(!is_recaptcha_valid(@$this->input->post('ctvrg'))){
                //$this->session->set_flashdata('msg', 'We are not able to verify you. Please try later or try with a different web browser / device.');
                $this->session->set_flashdata('msg','<div class="alert alert-danger text-center">We are not able to verify you. Please try later or try with a different web browser / device.</div>');
                $this->index();
                exit(0);
            }*/
			$paramArr = array();
        	switch($type){
				case 1:
					if(!empty($this->input->post('aor_code') ) &&  !empty($this->input->post('mobile'))){

						$loginData = $this->auth_model->get_user($this->input->post('aor_code'));
						//echo '<pre>';print_r($loginData);exit();
						if($loginData){
							$login_time = date('h:i:s');
							$endTime = strtotime("+15 minutes", strtotime($login_time));
							$logout_time=date('h:i:s', $endTime);
							$loginData['dec_type'] = $type;
							$loginData['if_logged_in'] = TRUE;
							$loginData['processid'] = getmypid();
							$loginData['user_id'] = $loginData['bar_id'];
							$loginData['user_type_id'] = $loginData['aor_code'];
							$loginData['login_time'] = $login_time;
							$loginData['logout_time'] = $logout_time;
							$loginData['login_time_inseconds'] =time();
							$loginData['login_active_session'] = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
							$this->session->set_userdata('loginData',$loginData);
							if($this->session->loginData['mobile']==$this->input->post('mobile')){ //for production
                            //if('8860012863'==$this->input->post('mobile')){
								$paramArr['mobile'] = $this->input->post('mobile');
								$res = sendOtp($paramArr);
								if(isset($res) && !empty($res)){
									$this->logUser($action = 'otp');
									redirect('Auth/otpForm');
									//$this->otpForm();
								}
								else{
									$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Error,Please try again later.</div>');
									$this->index();
								}
							}
							else{
								$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Wrong Mobile number entered!</div>');
								$this->index();
							}
						}
						else{
							$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Invalid AOR!</div>');
							$this->index();
						}
					}
					else{
						$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please enter AOR code and mobile< number/div>');
						$this->index();
					}
				break;
				case 2:
					if(!empty($this->input->post('mobile'))){
						$mobile = (int)$this->input->post('mobile');
						$params = array();
						$params['current_date'] = date('Y-m-d');
                        //$params['current_date'] = '2020-09-11';
						$params['mobile'] = $mobile;
						$res = $this->auth_model->getSelfDeclarationData($params);
						if(isset($res) && !empty($res) && count($res) == 1 && $res[0]['mobile'] == $mobile){
							$res[0]['dec_type'] = $type;
							$res[0]['post_mobile'] = $mobile;
							$this->session->set_userdata('loginData',$res[0]);
							$paramArr['mobile'] = $mobile;
							$res = sendOtp($paramArr);
							if(isset($res) && !empty($res)){
								//$this->logUser($action = 'otp');
								//$this->otpForm();
								redirect('Auth/otpForm');
							}
							else{
								$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Error,Please try again later.</div>');
								$this->index();
							}
						}
						else{
							$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Error,You are not authorised for today.</div>');
							$this->index();
						}

					}
					else{
						$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Please fill mobile number.</div>');
						$this->index();
					}
					break;
					default;
			}
		}
        else{
        	$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Please select Consent/Authorize Counsels OR Self Declaration.</div>');
			$this->index();
		}
    }
}
    public function doValidateOTP(){

        extract($_POST);
		 $userCaptcha = $_POST['userCaptcha'];
		if ($_SESSION["captcha"] != $userCaptcha) {
			$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Error,Invalid Captcha!</div>');
			redirect('auth/otpForm');
		}
        $postOtp = NULL;
        if(empty($otp)){
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please fill OTP received on your registered mobile number.</div>');
        }
		if(isset($otp) && !empty($otp)){
			$postOtp = (int)$otp;
		}
        if(isset($postOtp) && !empty($postOtp) &&  $postOtp ==$_SESSION['otp']){
			audit_trail_log('OTP','Verify successfully');
			$this->logUser($action = 'login');
			unset($_SESSION['otp']);
            redirect('Consent_VC');
            //echo "You are logged in.";
        }
        else{
           // $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Invalid OTP!</div>');
			//$this->otpForm();
if (isset($_SESSION['otp_attempts'])){
			$_SESSION['otp_attempts']=$_SESSION['otp_attempts'] + 1;
		}else{
			$_SESSION['otp_attempts']=1;
		}
		if ($_SESSION['otp_attempts'] > 5){
			$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please click on resend OTP!</div>');
		unset($_SESSION['otp_attempts']);
			//$this->otpForm();
		}else{
			$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Invalid OTP!</div>');
		}
		
	//redirect('Auth/otpForm');
$this->otpForm();

        }
    }
    public function logout(){
		$this->logUser($action = 'logout');
        unset($_SESSION);
        $this->session->sess_destroy();
        redirect('auth');
        exit(0);
    }
	private function logUser($action) {
		// logs the login and logout time
		if ($action == 'login') {
			$data['login_id'] = $this->session->userdata['loginData']['user_id'];
			$data['is_successful_login'] = 'true';
			$data['ip_address'] = get_client_ip();
			$data['login_time'] = date('Y-m-d H:i:s');
			//$data['session_detail'] = serialize($this->session->userdata());
			//$data['referrer'] = $_SERVER['HTTP_REFERER'];
			$data['referrer'] = $this->session->userdata['loginData']['user_type_id'];
			$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
			//$data['url'] = $_SERVER['REQUEST_URI'];
			$data['processid'] = $this->session->userdata['loginData']['processid'];
			$data['impersonator_user'] = json_encode($this->session->userdata['loginData']['user_type_id']);//for efiling_assistant
			audit_trail_log($action,'successfully logged in');
			audit_logUser($action, $data);
		} elseif ($action == 'logout') {
			$data['log_id'] = $this->session->userdata['loginData']['log_id'];
			$data['logout_time'] = date('Y-m-d H:i:s');
			audit_trail_log($action,'User logged out');
			audit_logUser($action, $data);
		}elseif ($action == 'otp') {
			audit_trail_log($action,'User send otp on mobile');
		}


	}
}
