<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
@extends('layout.frontApp')
@section('content')
<?php
$segment = service('uri');
$session = service('session');
$security = service('security');
?>

    <!-- Login Area Start  -->
    <div class="login-area">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-7 col-lg-7 login-banner">
                    <div class="login-banner-inner">
                        <div class="banimg-sec">
                            <!-- <img src="<?= base_url() . 'assets/newDesign/' ?>images/SCI-banner.png" alt=""
                                class="img-fluid"> -->
                                <img src="<?= base_url() . 'assets/newDesign/' ?>images/logo-full.png" alt=""
                                class="img-fluid logo-at-banner">
                        </div>
                        <div class="banner-txts">
                        <h5>SC-EFM </h5>
                        <h6>E-Filing Module</h6>
                        <h6>Supreme Court of India</h6>
                    </div>
                        <div class="banner-txts">
                            <?php
                            if ($segment->getSegment(2) == 'AdvocateOnRecord') {
                                $title = 'Advocate On Record';
                            } else {
                                $title = 'Party In Person';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-5 col-lg-5 login-section">
                    <div class="login-s-inner">
                        <?php $session = session(); ?>
                        @if($session->has('msg'))
                            <div class="alert alert-dismissible text-center flashmessage">
                                <b>{{ esc($session->get('msg')) }}</b>
                            </div>
                        @endif
                        @if($session->has('information'))
                            <div class="alert alert-dismissible text-center flashmessage">
                                <b>{{esc($session->get('information'))}}</b>
                            </div>
                        @endif
                        @if(isset($validation) && !empty($validation->getError('adv_mobile')))
                            <div class="alert alert-dismissible text-center flashmessage">
                                <b>{{ $validation->getError('adv_mobile')}}</b>
                            </div>
                        @endif
                        @if(isset($validation) && !empty($validation->getError('adv_email')))
                            <div class="alert alert-dismissible text-center flashmessage">
                                <b>{{ $validation->getError('adv_email')}}</b>
                            </div>
                        @endif
                        <div class="httxt">
                            <h4><?php echo $title; ?><strong> (Registration)</strong></h4>
                        </div>
                        <div class="loin-form" id="offline_proceed" >
                            <?php
                            $attributes = array("class" => "form-horizontal", "id" => "loginform", "name" => "loginform", 'autocomplete' => 'off');
                            echo form_open("register/AdvocateOnRecord/adv_get_otp", $attributes);
                                echo $session->setFlashdata('msg');
                                ?>
                                <input type="hidden" name="register_type" value="<?php echo $title; ?>">
                                <input type="hidden" name="ctvrg">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label"></label>
                                            <input class="form-control cus-form-ctrl" type="text" name="adv_mobile"  id="adv_mobile"  placeholder="Mobile"  maxlength="10" minlength="10" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label"></label>
                                            <input class="form-control cus-form-ctrl" type="text" name="adv_email" id="adv_email"  placeholder="Email ID">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="captchaimage">
                                            <div class="mb-3">
                                                @include('Captcha.Captcha_view')
                                                <!-- <input type="text" value="" placeholder="HrT5709KL" id="" name="" class="form-control cus-form-ctrl"> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <button class="btn quick-btn">SEND OTP</button>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                        <center><a href="{{base_url()}}" class="col-sm-3 col-md-3 btn quick-btn">LOGIN</a></center>
                        <div class="regester-txts">
                            <h6 class="htsmall">Register As :</h6>
                            <div class="regester-links">
                                <a href="{{base_url('register')}}" class="blue-txt">Individual (Party In Person)</a>
                                <span class="gray-txt">Or</span>
                                <a href="{{base_url('register/AdvocateOnRecord')}}" class="blue-txt"> AOR</a>
                                <span class="gray-txt">Or</span>
                                <a href="{{base_url('arguingCounselRegister')}}" class="blue-txt">Advocate</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Login Area End  -->
    @endsection
    <script src="<?=base_url('CaptchaResource/js/Captcha.js');?>"></script>

    <script type="text/javascript">
        $(function(){
        // grecaptcha.ready(function () {
                $("#loginform").one('submit', function (e) {
                    var form = this;
                    $('[name="ctvrg"]').val('<?=csrf_field()?>');
                    $(form).submit();
                /*  e.preventDefault();
                    grecaptcha.execute('6LfE13AUAAAAACYtRe104ECi3APlECcyJfbH3VrV', {action: '{{str_replace('-','_hyphen_',uri_string())}}'})
                        .then(function (token) {
                            $('[name="ctvrg"]').val(token);
                            $(form).submit();
                        });*/
                });
            //});
        });
    </script> 
</body>
</html>