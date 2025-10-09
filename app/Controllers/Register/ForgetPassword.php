<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auto_Sanitize {
    function hex2bin($hexstr)
    {
        $n = strlen($hexstr);
        $sbin="";
        $i=0;
        while($i<$n)
        {
            $a =substr($hexstr,$i,2);
            $c = pack("H*",$a);
            if ($i==0){$sbin=$c;}
            else {$sbin.=$c;}
            $i+=2;
        }
        return $sbin;
    }
    function cryptoJsAesDecrypt($passphrase, $jsonString)
    {
        $jsondata = json_decode($jsonString, true);
        try {
            $salt = $this->hex2bin($jsondata["s"]);
            $iv  = $this->hex2bin($jsondata["iv"]);
        } catch(\Exception $e) { return null; }
        $ct = base64_decode($jsondata["ct"]);
        $concatedPassphrase = $passphrase.$salt;
        $md5 = array();
        $md5[0] = md5($concatedPassphrase, true);
        $result = $md5[0];
        for ($i = 1; $i < 3; $i++) {
            $md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
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
            $dx = md5($dx.$passphrase.$salt, true);
            $salted .= $dx;
        }
        $key = substr($salted, 0, 32);
        $iv  = substr($salted, 32,16);
        $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
        $data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));
        return json_encode($data);
    }
}

class ForgetPassword extends CI_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->model('register/Register_model');
        $this->load->library('slice');
    }

    public function index() {
    	/*$captcha_value = captcha_generate();
        $data['captcha']['image'] = $captcha_value['image'];
        $data['captcha']['word'] = $captcha_value['word'];*/

        /*$this->load->view('login/login_header');
        $this->load->view('register/forget_password_view',$data);
		$this->load->view('login/login_footer');*/
        $this->slice->view('responsive_variant.authentication.forget_password_view');
    }

    function adv_get_otp(){
        if (empty($_POST['userCaptcha'])) {
            $this->session->set_flashdata('msg', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a > <p style="text-align: center;">Captcha is required!</p> </div>');
            redirect('register/ForgetPassword');
            exit(0);
        }
       /* if(!is_recaptcha_valid(@$_POST['ctvrg'])){
            $this->session->set_flashdata('msg', 'We are not able to verify you. Please try later or try with a different web browser / device.');
            redirect('register/ForgetPassword');
            exit(0);
        }*/
        /*$data['captcha']['image'] = $captcha_value['image'];
        $data['captcha']['word'] = $captcha_value['word'];
        
        $userCaptcha = escape_data($_POST['userCaptcha']); 
        
        if ($this->session->userdata("captchaWord") != $userCaptcha) {
            $this->session->set_flashdata('msg', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a > <p style="text-align: center;">Invalid Captcha!</p> </div>'); 
            redirect('register');
        }*/
        $this->form_validation->set_rules('userCaptcha', 'Captcha', 'required|trim|is_required');
    	$this->form_validation->set_rules('adv_mobile', 'Mobile', 'numeric|trim|is_required|min_length[10]|max_length[10]');
    	$this->form_validation->set_rules('adv_email', 'Email', 'valid_email|trim|is_required');
       // $this->form_validation->set_rules('userCaptcha', 'Captcha', 'required|trim|is_required');
        $this->form_validation->set_error_delimiters('<div class="uk-alert-danger">', '</div>');

         if(empty($_POST['adv_mobile']) && empty($_POST['adv_email']) ){
         	$this->session->set_flashdata('msg', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a > <p style="text-align: center;">Please Enter Mobile or Email !</p> </div>');
			redirect('register/ForgetPassword');
		}

        if ($this->form_validation->run() === FALSE) { 
           /*$captcha_value = captcha_generate();
            $data['captcha']['image'] = $captcha_value['image'];
        	$data['captcha']['word'] = $captcha_value['word'];*/

        	/*$this->load->view('login/login_header');
        	$this->load->view('register/forget_password_view',$data);
			$this->load->view('login/login_footer');*/
            $this->slice->view('responsive_variant.authentication.forget_password_view');
        }
        else {
            if ($_SESSION["captcha"] !=$_POST['userCaptcha']) {
                $this->session->set_flashdata('msg', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a > <p style="text-align: center;">Invalid Captcha!</p> </div>');
                redirect('register/ForgetPassword');
                exit(0);
            }
        	if(isset($_POST['adv_mobile']) && !empty($_POST['adv_mobile']) || isset($_POST['adv_email']) && !empty($_POST['adv_email'])){
				 
                $mobile_exist = $this->Register_model->check_already_reg_mobile($_POST['adv_mobile']);
                $email_exist = $this->Register_model->check_already_reg_email(strtoupper($_POST['adv_email']));
                
                if($mobile_exist[0]['moblie_number'] != $_POST['adv_mobile']){
                    $this->session->set_flashdata('msg', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a > <p style="text-align: center;">Not Registerd This Mobile Number!</p> </div>');
                    redirect('register/ForgetPassword');
                }else if(strtoupper($email_exist[0]['emailid']) != strtoupper($_POST['adv_email'])){
                    $this->session->set_flashdata('msg', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a > <p style="text-align: center;">Not Registerd This Email ID!</p> </div>');
                    redirect('register/ForgetPassword');
                }else{
                    if(!empty($_POST['adv_mobile']))
                        $name_array = array('first_name'=>$mobile_exist[0]['first_name'], 'last_name'=>$mobile_exist[0]['last_name']);
                    if(!empty($_POST['adv_email']))
                        $name_array = array('first_name'=>$email_exist[0]['first_name'], 'last_name'=>$email_exist[0]['last_name']);
                    $mobile_otp_is = $this->generateNumericOTP();
    				$email_otp_is = $this->generateNumericOTP();
                    date_default_timezone_set('Asia/Kolkata');
                    $startTime=date("H:i");
                    $endTime = date("H:i", strtotime('+15 minutes', strtotime($startTime)));
            		$_SESSION['adv_details'] =  array_merge(array('mobile_no'=>$_POST['adv_mobile'],
            		'mobile_otp'=>$mobile_otp_is,
            		'email_id'=>$_POST['adv_email'],
            		'email_otp'=>$email_otp_is,
            		'register_type'=>$_POST['register_type'],
                    'start_time'=>$startTime,
                    'end_time'=>$endTime), $name_array);
            		if(!empty($_POST['adv_mobile'])){
                        $typeId="38";
                        $mobileNo=trim($_POST['adv_mobile']);
                        $smsText="OTP for changing SC-EFM password is: ".$mobile_otp_is." ,Please do not share it with any one. - Supreme Court of India";
                        sendSMS($typeId,$mobileNo,$smsText,SCISMS_Change_Password_OTP);
                    }
                    else if(!empty($_POST['adv_email'])){
                        $to_email=trim($_POST['adv_email']);
                        $subject="SC-EFM forget password OTP";
                        $message="OTP for changing SC-EFM password is: ".$email_otp_is." ,Please do not share it with any one.";
                        send_mail_msg($to_email, $subject, $message);
                        //relay_mail_api($to_email, $subject, $message);
                    }
    				redirect('register/AdvOtp'); 
                }
			}
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
	function generateNumericOTP($n = 6) {  
	    $generator = "1357902468";  
	    $result = "";  
	    for ($i = 1; $i <= $n; $i++) { 
	        $result .= substr($generator, (rand()%(strlen($generator))), 1); 
	    }   
	    return $result; 
	} 

	function update_password(){
		$captcha_value = captcha_generate();
        $data['captcha']['image'] = $captcha_value['image'];
        $data['captcha']['word'] = $captcha_value['word'];

        /*$this->load->view('login/login_header');
        $this->load->view('register/update_password_view',$data);
		$this->load->view('login/login_footer');*/
        $this->slice->view('responsive_variant.authentication.update_password_view',$data);
	}



    function valid_password($password = '')
    {
        $password = trim($password);

        $regex_lowercase = '/[a-z]/';
        $regex_uppercase = '/[A-Z]/';
        $regex_number = '/[0-9]/';
        $regex_special = '/[!@#$%^&*()\-_=+{};:,<.>ยง~]/';

        if (empty($password))
        {
            return FALSE;
        }
        if (preg_match_all($regex_lowercase, $password) < 1)
        {
            return FALSE;
        }
        if (preg_match_all($regex_uppercase, $password) < 1)
        {
            return FALSE;
        }
        if (preg_match_all($regex_number, $password) < 1)
        {
            return FALSE;
        }
        if (preg_match_all($regex_special, $password) < 1)
        {
            return FALSE;
        }
        if (strlen($password) <= 8)
        {
            return FALSE;
        }
        if (strlen($password) > 32)
        {
            return FALSE;
        }
        return TRUE;
    }

    function decodeData($pass,$data){
        $sanitize = new Auto_Sanitize();
        for($i=0;$i<10;$i++){
            $data=$sanitize->cryptoJsAesDecrypt($pass, $data);
        }
        $dataArray= explode('hgtsd12@_hjytr',$data);
        return $dataArray[0];
    }

	function update_user_password(){
        $this->form_validation->set_rules('password', 'New Passwrod', 'required|trim|is_required');
        $this->form_validation->set_rules('confirm_password', 'Confirm Passwrod', 'required|trim|is_required');

        $this->form_validation->set_error_delimiters('<div class="uk-alert-danger">', '</div>');

        $password = $_POST['txt_password'];
        $password = str_replace("'","",$password);
        $salt=$_POST['salt'];
        $decoded_password=$this->decodeData($salt,$password);

        //echo $decoded_password; //exit(0);
		if ($this->form_validation->run() === FALSE) {

            $captcha_value = captcha_generate();
            $data['captcha']['image'] = $captcha_value['image'];
        	$data['captcha']['word'] = $captcha_value['word'];
            /*
        	$this->load->view('login/login_header');
        	$this->load->view('register/update_password_view',$data);
			$this->load->view('login/login_footer');*/
            $this->slice->view('responsive_variant.authentication.update_password_view',$data);
        }
		else if(!$this->valid_password($decoded_password)){
            $this->session->set_flashdata('msg', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a > <p style="text-align: center;">Password policy has to be followed.</p> </div>');
            redirect('register/ForgetPassword/update_user_password');
        }
        else {
          /*  $data['captcha']['image'] = $captcha_value['image'];
        $data['captcha']['word'] = $captcha_value['word'];

       $userCaptcha = escape_data($_POST['userCaptcha']);
        if ($this->session->userdata("captchaWord") != $userCaptcha) {
            $this->session->set_flashdata('msg', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a > <p style="text-align: center;">Invalid Captcha!</p> </div>');
            redirect('register/ForgetPassword/update_password');
        } */

            if($_POST['password'] != $_POST['confirm_password']){
        	 	$this->session->set_flashdata('msg', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a > <p style="text-align: center;">Confirm Passwrod Not match</p> </div>');
					redirect('register/ForgetPassword/update_user_password');
        	 	}

        	 	if(!empty($_SESSION['adv_details']['mobile_no'])){
 					$forget_mobile = $_SESSION['adv_details']['mobile_no'];
 				}

 				if(!empty($_SESSION['adv_details']['email_id'])){
 					$forget_email = $_SESSION['adv_details']['email_id'];
 				}

        	 	//$data = array('password' => hash('sha256',$_POST['confirm_password']));
 				$data = array('password' => $_POST['confirm_password']);
        	 	$passUpdate = $this->Register_model->update_user_password($data,$forget_mobile,$forget_email);

        	 	if($passUpdate){
                    $_SESSION['adv_details']['ForgetPasswordDone']='ForgetPasswordDone';
        	 		$this->session->set_flashdata('msg', '<div class="uk-alert-success" uk-alert> <a class="uk-alert-close" uk-close></a > <p style="text-align: center;">Passwrod Update Successful !</p> </div>');
                    redirect('login');
        	 	}else{
        	 		$this->session->set_flashdata('msg', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a > <p style="text-align: center;">Failed Passwrod update</p> </div>');
					redirect('register/ForgetPassword/update_user_password');
        	 	}
        }

	}

}