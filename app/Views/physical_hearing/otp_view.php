<!DOCTYPE html>
<html>
<head>
	<link rel="icon" href="<?=base_url('assets/sci_logo.png')?>" type="image/x-icon">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$page_title?></title>
    <meta Http-Equiv="Cache-Control" Content="no-cache">
    <meta Http-Equiv="Pragma" Content="no-cache">
    <meta Http-Equiv="Expires" Content="0">


    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/AdminLTE.min.css">
	<?php
	?>
</head>
<body class="hold-transition login-page">

<div class="login-box">
    <div class="login-logo">
        <a href="<?=base_url();?>"><h3 class="text-blue"> <strong><?=APP_NAME_L1?></strong><br><?=APP_NAME_L2?></h3></a>
    </div>
    <div class="login-box-body" style="padding: 20px 40px 20px 40px;">
        <div class="row">
			<?php if (isset($_SESSION['loginData']['resend_otp'])){
				if ($_SESSION['loginData']['resend_otp']=='Y'){
				    echo '<div class="alert alert-success text-center">Successfully Send Resend OTP.</div>';
			    }else{
					echo '<div class="alert alert-danger text-center">Error,Resend OTP not Send.</div>';
				}
				unset($_SESSION['loginData']['resend_otp']);
				?>
			<?php } ?>
            <?=$this->session->flashdata('msg');?>
            <!--<span class="required-server"><?php /*echo form_error('userCaptcha','<p style="color:#F83A18">','</p>'); */?></span>-->
        </div>
        <?php unset($_SESSION['msg']);
        //echo '<pre>';print_r($_SESSION);
        //var_dump($_SESSION['otp']);
        $attributes = array("class" => "form-horizontal", "id" => "otpform", "name" => "otpform", "autocomplete"=>"off");
        echo form_open(base_url()."index.php/auth/doValidateOTP", $attributes);?>
        <div id="divOTP">
            <div class="form-group has-feedback">
                <input class="form-control" id="otp" maxlength="5" name="otp" placeholder="OTP" type="text" value="" minlength="5" required onkeyup="this.value = this.value.replace(/[^0-9]/g, '')">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
			<!-- Start captcha-->
			<p>
				<img src="<?=base_url('/index.php/captcha/DefaultController');?>" id='captcha_image' style="height: 35px !important;">
				<img src="<?=base_url('CaptchaResource/images/speaker.png');?>" class="img-rounded pointer" onclick="playAudio();" alt="speaker" style="width: 42px;cursor: pointer;">
				<img src="<?=base_url('CaptchaResource/images/refresh.png');?>" class="img-circle pointer" onclick="refreshCaptcha();" alt="captcha Refresh" style="width:32px;border-radius: 50%;cursor: pointer;">
				<input type="text" class="form-control uk-input" name="userCaptcha" id="captchacode" placeholder="Captcha" minlength="6" maxlength="6" style="width: 30%;margin-left: 20px;display: inline;" required onkeyup="this.value = this.value.replace(/[^a-zA-Z0-90-9]/g, '')">
			</p>
			<?php //echo $this->load->view('Captcha/Captcha_view');?>
			<!-- End captcha-->

			<div id="some_div"></div>
			<br/>
			<div class="form-group">
                <div class="col-xs-">
                    <button type="submit" name="btn_login" value="Login" id="otpSubmit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>



<script src="<?=base_url()?>assets/plugins/jQuery/jQuery.min.js"></script>
<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/js/angular.min.js"></script>
<script>
	$(document).ready(function(){
		var timeLeft = 30;
		var elem = document.getElementById('some_div');

		var timerId = setInterval(countdown, 1000);

		function countdown() {
			if (timeLeft == -1) {
				clearTimeout(timerId);
				doSomething();
			} else {

				if (timeLeft==0){
					elem.innerHTML ="<span id='resend_otp'> Resend OTP ? <a href='<?php echo base_url("index.php/auth/resend_otp") ?>'>click</a></span>";
				}else{
					elem.innerHTML = timeLeft + ' seconds remaining resend otp';
					timeLeft--;
				}

			}
		}
		/*setInterval(function() {
			$('#resend_otp').show();
			//alert("Message to alert every 5 seconds");
		}, 5000);*/
		$(document).on('click','#otpSubmit',function() {
		//	e.preventDefault();
			var otpreg = /^\d+$/;
			var otp = $("#otp").val();
			if(otp == ''){
				alert("Please fill OTP number.");
				$("#otp").focus();
				$("#otp").css('border-color','red');
				return false;
			}
			else if(otp && (!otpreg.test(otp))){
				alert("Please fill valid OTP number.");
				$("#otp").focus();
				$("#otp").val('');
				$("#otp").css('border-color','red');
				return false;
			}
			else{
				return true;
			}
		});

	});
</script>
</body>
</html>
