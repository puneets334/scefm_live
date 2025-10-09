<!DOCTYPE html>
<html>
<head>
	<!-- Favicon-->
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
	<script src="<?=base_url()?>assets/plugins/jQuery/jQuery.min.js"></script>
	<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
	<script src="<?= base_url() ?>assets/js/angular.min.js"></script>
    <style>
        .login-logo1 {
            background: url("<?=base_url()?>assets/img/logo1.png") no-repeat;
            width: 180px;
            height: 151px;
            display: block;
            text-indent: -9999px;
            text-align: right;
            margin-left: 180px;
        }

    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box" style="width: 450px;">
    <div class="login-logo1" ></div>

    <div class="login-logo" >

        <a href="../../index.php/auth/"><h3 class="text-blue"> <strong><?=APP_NAME_L1?></strong><br><?=APP_NAME_L2?><span style="vertical-align: middle">**</span> </h3></a>
        <!--<div class="well ">
            <h4 class="text-blue text-bold"> Consent for VC ( Tuesday only )</h4>
        </div>-->
    </div>
    <div class="login-box-body" >
        <?=$this->session->flashdata('msg'); unset($_SESSION['msg']);?>
        <?php //echo "Current version is PHP " . phpversion();
        $attributes = array("method"=>"post", "class" => "form-horizontal", "id" => "loginform", "name" => "loginform", "autocomplete"=>"off");
        echo form_open(base_url()."index.php/auth/getOtp", $attributes);?>
		<input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        <input type="hidden" name="ctvrg">
        <div id="divDetails">
            <input type="hidden" id="consent" class="checkType" name="type" value="1" >
            <!--<div class="form-group has-feedback">
                &nbsp;
                <input type="radio" id="consent" class="checkType" name="type" value="1" >
                <label for="consent" class="text-right" >eNominate Counsel/Clerk</label>
                &nbsp;&nbsp;
                <input type="radio" id="self_declaration"  class="checkType" name="type" value="2" >
                <label for="self_declaration" class="text-right">Self Declaration
                    eSubmission</label>

            </div>-->
            <div class="form-group has-feedback" id="aorField">
                <input class="form-control" id="aor_code" name="aor_code" type="text"  placeholder="AOR Code" required minlength="1" maxlength="10" onkeyup="this.value = this.value.replace(/[^0-9]/g, '')">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input class="form-control" id="mobile" name="mobile" maxlength="10" placeholder="Mobile Number" type="text" minlength="10" required onkeyup="this.value = this.value.replace(/[^0-9]/g, '')">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
          <!-- Start captcha-->
			<p>
				<img src="<?=base_url('/index.php/captcha/DefaultController');?>" id='captcha_image' style="height: 35px !important;">
				<img src="<?=base_url('CaptchaResource/images/speaker.png');?>" class="img-rounded pointer" onclick="playAudio();" alt="speaker" style="width: 42px;cursor: pointer;">
				<img src="<?=base_url('CaptchaResource/images/refresh.png');?>" class="img-circle pointer" onclick="refreshCaptcha();" alt="captcha Refresh" style="width:32px;border-radius: 50%;cursor: pointer;">
				<input type="text" class="form-control uk-input" name="userCaptcha" id="captchacode" placeholder="Enter captcha" minlength="6" maxlength="6" style="width: 30%;margin-left: 20px;display: inline;" required onkeyup="this.value = this.value.replace(/[^a-zA-Z0-90-9]/g, '')">
			</p>
			<?php //echo $this->load->view('Captcha/Captcha_view');?>
			<!-- End captcha-->
            <div class="form-group">
                <div class="col-xs-">
                    <input type="submit" name="get_otp" id="get_otp" value="Submit" class="btn btn-primary btn-block btn-flat">
                </div>
            </div>
            <div class="col-sm-12" style="margin:1px;" >
                        <span class="col-xs-12" style="color:#0073b7;font-weight: bold;text-align: left">
                            ** Note :
                            <p style= "text-align: justify;">1. The option to apply for video conference (VC) Link for hybrid hearing through this portal is available for Miscellaneous Day(s)i.e.Monday & Friday and/or any other day, which has been declared a Miscellaneous Hearing Day.</p>
                            <p style= "text-align: justify;">2. To get a VC Link, one must apply through this portal on or before 08:00 A.M. on the day of the listing of the case.</p>
                            <p style= "text-align: justify;">3. Please note that the Party-in-Person(s) need not apply for VC Link on this portal.The VC link for the court hearings on Miscellaneous Days i.e. Monday & Friday and/or any notified Miscellaneous Hearing Day to the Party-in-Person shall be pushed automatically through email and sms text message by the Registry through the case management software. </p>
                            <p style= "text-align: justify;">4. For <i>further information, please see <u> <a href="<?=base_url()?>assets/demo_video/01042022_102743.pdf">Circular dated 01.04.2022</a> </u></i></p>
                            </span>
            </div>

        </div>


        <div class="row">
            <span class="text-danger">
            <?php
            if(!empty($msg)){
                echo $msg;
            }
            ?>
            </span>
        </div>

        <!--<div id="divOTP" hidden>
            <div class="form-group has-feedback">
                <input class="form-control" id="otp" name="otp" placeholder="OTP" type="text">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group">
                <div class="col-xs-">
                    <button type="submit" name="btn_login" value="Login" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
            </div>
        </div>-->
        <?php echo form_close(); ?>

    </div>

    <div class="col-sm-12 col-xs-12 bg-danger" >
        <div class="text-center "><h3><a href="#" data-toggle="modal" data-target="#videoTM">Watch Demo </a></h3></div>
        <div class="modal fade" id="videoTM" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Demo - <i>Physical Hearing (With Hybrid Option)</i></h4>
                    </div>
                    <div class="modal-body">
                        <video controls id="video1" style="width: 100%; height: auto; margin:0 auto; frameborder:0;">
                            <source src="<?php echo base_url()?>assets/demo_video/Phy_hearing_demo.mp4" type="video/ogg">
                            Your browser doesn't support HTML5 video tag.
                        </video>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="well " style="margin-top:25px;text-align:center">
        <h4> <a href="<?/*=base_url()*/?>assets/generated_mpdf/Physcial_hearing_consent_for_vc.pdf"><h4 class="text-blue"> <strong>User Manual</h4></a></h4>
    </div>-->
</div>



<script src="<?=base_url('CaptchaResource/js/Captcha.js');?>"></script>
<script>
    $(document).ready(function(){
        $(document).on('click','.checkType',function(){
            if($(this).val() == 1){
                $("#aorField").show();
            }
            else if($(this).val() == 2){
                $("#aorField").hide();
            }
        });

    });

    $('#videoTM').on('shown.bs.modal', function () {
        $('#video1')[0].play();
    })
    $('#videoTM').on('hidden.bs.modal', function () {
        $('#video1')[0].pause();
    })


</script>
</body>
</html>
