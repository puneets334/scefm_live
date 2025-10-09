<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= base_url() . 'assets/newDesign/' ?>images/logo.png" type="image/png" />
    <title>SC-eFM</title>
    <link href="<?= base_url() . 'assets/newDesign/' ?>css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?= base_url() . 'assets/newDesign/' ?>css/font-awesome.min.css" rel="stylesheet" />
    <link href="<?= base_url() . 'assets/newDesign/' ?>css/material.css" rel="stylesheet" />
    <link href="<?= base_url() . 'assets/newDesign/' ?>css/glyphicons.css" rel="stylesheet" />
    <link href="<?= base_url() . 'assets/newDesign/' ?>css/owl.carousel.min.css" rel="stylesheet" />
    <link href="<?= base_url() . 'assets/newDesign/' ?>css/owl.theme.default.css" rel="stylesheet">
    <link href="<?= base_url() . 'assets/newDesign/' ?>css/animate.css" rel="stylesheet">
    <link href="<?= base_url() . 'assets/newDesign/' ?>css/style.css" rel="stylesheet" />
    <link href="<?= base_url() . 'assets/newDesign/' ?>css/black-theme.css" rel="stylesheet" />
</head>
<style type="text/css">
    @media only screen and (min-width: 960px) {
        body{
            overflow-y:hidden;
        }
    }
</style>
<?php
$segment = service('uri');
$session = service('session');
?>
<body class="login-page">
    <header>
        <!-- Top Header Section End -->
        <div class="top-header">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-3 col-lg-6 top-left-nav wow fadeInDown"></div>
                    <div class="col-12 col-sm-12 col-md-9 col-lg-6 top-right-nav wow fadeInDown">
                    
                <ul>
                    <li><a href="#SkipContent" class="hide skiptomain" aria-label="Skip to main content" title="Skip to main content">Skip To Main Content</a></li>
                    <!-- <li><a class="hide" href="<?php // echo base_url('online_copying/screen_reader');?>">Screen Reader Access</a></li> -->
                    <li class="text-size">
                    <a href="javascript:void(0)"><img src="<?= base_url().'assets/newAdmin/'?>images/text-ixon.png" alt="" class="txt-icon"></a>
                    </li>
                    <!-- <li>
                        <a href="javascript:void(0)" class="toph-icon"><i class="fas fa-sitemap"></i></a>
                    </li> -->
                    <li class="theme-color">
                        <a href="javascript:void(0)" class="whitebg">A</a>
                        <a href="javascript:void(0)" class="blackbg">A</a>
                    </li>
                    <!-- <li>
                        <select name="" id="" class="select-lang">
                            <option value="">English</option>
                            <option value="">Hindi</option>
                        </select>
                    </li> -->
                </ul>
          
                    </div>
                </div>
            </div>
        </div>
        <!-- Top Header Section End -->
        <div id="SkipContent" tabindex="-1"></div>
        <!-- Logo Section Header Start -->
        <div class="logo-sec-wraper">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4 logo-sec wow fadeInUp">
                        <a class="logo-align" href="<?= base_url(); ?>">
                            <img src="<?= base_url() . 'assets/newDesign/' ?>images/logo.png" alt="emblem">
                            <div class="brand-text">
                                <h4>भारत का सर्वोच्च न्यायालय
                                    <span> Supreme Court of India </span>
                                    <span class="logo-sm-txt">|| यतो धर्मस्ततो जय: ||</span>
                                </h4>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-8 loginbtn-sec wow fadeInUp">
                        <div class="nav-wraper">
                            <nav class="navbar navbar-expand-lg navbar-light custom-nav  w-100 wow fadeInUp">
                                <button class="navbar-toggler" type="button" data-toggle="collapse"
                                    data-target="#navbarSupportedContent">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav mr-auto">
                                        <li class="nav-item">
                                            <!-- <a class="active" href="index.html">Handbook </a> -->
                                            <!-- <a class="active"  href="<?php echo base_url('e-resources')?>">Handbook </a> -->
                                            <a class="nav-link <?= (current_url() == base_url('resources/hand_book')) ? 'active' : '' ?>"  href="<?= base_url('resources/hand_book') ?>">Handbook </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link <?= (current_url() == base_url('resources/video_tutorial/view')) ? 'active' : '' ?>"  href="<?= base_url('resources/video_tutorial/view') ?>">Video Tutorial</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link <?= (current_url() == base_url('resources/FAQ')) ? 'active' : '' ?>"  href="<?= base_url('resources/FAQ') ?>">FAQs</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link <?= (current_url() == base_url('resources/hand_book_old_efiling')) ? 'active' : '' ?>"  href="<?= base_url('resources/hand_book_old_efiling') ?>">Stats</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link <?= (current_url() == base_url('resources/Three_PDF_user_manual')) ? 'active' : '' ?>"  href="<?= base_url('resources/Three_PDF_user_manual') ?>">3PDF User Manual</a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Logo Section Header End -->
    </header>
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
                            <?php
                            if ($segment->getSegment(2) == 'AdvocateOnRecord') {
                                $title = 'Advocate On Record';
                                $adv_type_select=USER_ADVOCATE;
                            } elseif ($segment->getSegment(1) == 'arguingCounselRegister') {
                                $title = 'Advocate';
                                $adv_type_select=USER_ADVOCATE;
                            } else {
                                $title = 'Party In Person';
                                $adv_type_select=USER_IN_PERSON;
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
                        <div>
                            <?php
                            $attributes = array("class" => "form-horizontal", "id" => "loginform", "name" => "loginform", 'autocomplete' => 'off');
                            echo form_open("register/ArguingCounselRegister/senOtp", $attributes);
                            ?>
                                <input type="hidden" name="ctvrg">
                                <input type="hidden" name="register_type" value="Advocate">
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
    <script src="<?= base_url('assets/js/jquery.min.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
    <script src="<?=base_url('CaptchaResource/js/Captcha.js');?>"></script>
    <script src="<?= base_url() . 'assets/newDesign/' ?>js/custom.js"></script>
<script type="text/javascript" src="<?= base_url().'assets'?>/js/case_status/bootstrap.min.js"></script>

    <script type="text/javascript">
        function showHideDiv(ele) {
            var srcElement = document.getElementById(ele);
            var srcElement2 = document.getElementById('offline_proceed');
            if (srcElement != null) {
                if (srcElement.style.display == "block") {
                } else {
                    srcElement.style.display = 'block';
                    srcElement2.style.display = 'none';
                    document.getElementById("new_advocate").checked = false;
                    document.getElementById("ekyc").checked = true;
                    //window.scrollBy(0, 60);
                }
                return false;
            }
        }
        function HideEkycDiv(ele) {
            var srcElement = document.getElementById(ele);
            var srcElement2 = document.getElementById('ekyc_upload_share');
            if (srcElement != null) {
                if (srcElement.style.display == "block") {
                } else {
                    // alert(srcElement2);
                    srcElement.style.display = 'block';
                    srcElement2.style.display = 'none';
                    document.getElementById("ekyc").checked = false;
                    document.getElementById("new_advocate").checked = true;
                    //window.scrollBy(0, 60);
                }
                return false;
            }
        }
    </script>   
</body>
</html>