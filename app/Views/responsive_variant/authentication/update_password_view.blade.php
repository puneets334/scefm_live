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
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
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
                            <!-- <img src="<?= base_url() . 'assets/newDesign/' ?>images/SCI-banner.png" alt="" class="img-fluid"> -->
                            <img src="<?= base_url() . 'assets/newDesign/' ?>images/logo-full.png" alt="" class="img-fluid logo-at-banner">
                        </div>
                        <div class="banner-txts">
                            <h5>SC-EFM </h5>
                            <h6>E-Filing Module</h6>
                            <h6>Supreme Court of India</h6>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-5 col-lg-5 login-section">
                    <div class="login-s-inner">
                        <?php $session = session(); ?>
                        <?php if (session()->getFlashdata('not_match')) : ?>
                                   
                                   <div class="text-danger" style="border: 2px solid red; background-color: #ffcccb; padding: 10px; border-radius: 5px;">
                                       <b><?= session()->getFlashdata('not_match') ?></b>
                                   </div>
                               <?php endif; ?> 
                        <div class="loin-form">
                            <?php
                            $attributes = array("class" => "form-horizontal", "id" => "loginform", "name" => "loginform", 'autocomplete' => 'off');
                            echo form_open("register/ForgetPassword/update_user_password", $attributes);
                                $segment = service('uri'); 
                                $title = 'Enter New Password';
                                 ?>
                                <div class="httxt">
                                    <h4> <?php echo $title; ?> </h4>
                                </div>
                                <input type="hidden" name="salt" id="salt" value="<?= base64_encode(random_bytes(32)) ?>">
                                <input type="hidden" name="register_type" value="<?php echo $title; ?>">
                               
                                <input type="text" style="display: none;" name="_token" value="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Password</label>
                                            <input type="password" class="form-control cus-form-ctrl" id="password" name="password" maxlength="20" autocomplete="off" placeholder="Password" onchange="changeData(this)"> 
                                            <?php if (isset($validation) && $validation->hasError('password')): ?>
                                                <div class="text-danger">
                                                    <?= $validation->getError('password'); ?>
                                                </div>
                                            <?php endif; ?>
                                            <span style="font-size: 12px; color: #746c6c;">Password: Min 8 characters, 1 uppercase, 1 lowercase, 1 number, 1 special.</span> 
                                        </div>
                                        <input id="txt_password" name="txt_password" type="hidden">
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Confirm Password</label>
                                            <input class="form-control cus-form-ctrl" type="password" name="confirm_password" id="confirm_password" autocomplete="off" maxlength="20"  placeholder="Confirm Password" onchange="changeData(this)">
                                            <?php if (isset($validation) && $validation->hasError('confirm_password')): ?>
                                                <div class="text-danger">
                                                    <?= $validation->getError('confirm_password'); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="mb-3">
                                                <button type="submit" name="submit" value="submit" class="btn quick-btn">UPDATE PASSWORD</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <a href="{{base_url()}}" class="btn quick-btn" style="width: 25% !important;">Login ?</a>
                                    </div>
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
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Login Area End  -->
    <footer>
        <!-- Footer Top Section Start -->
        <div class="footer-top-sec">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-5 copyright-sec">
                        <p>Content Owned by Supreme Court of India</p>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-7 contact-txt">
                        <p>Please email your complaint/issue(s) along with relevant screenshot(s) to – <span class="blue-txt"> efiling[at]sci[dot]nic[dot]in </span></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Top Section End -->
    </footer>
    <script src="<?= base_url() . 'assets/newDesign/' ?>js/jquery351.min.js"></script>
    <script src="<?= base_url() . 'assets/newDesign/' ?>js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() . 'assets/newDesign/' ?>js/jquery.easy-ticker.min.js"></script>
    <script src="<?= base_url() . 'assets/newDesign/' ?>js/wow.min.js"></script>
    <script src="<?= base_url() . 'assets/newDesign/' ?>js/owl.carousel.js"></script>
    <script src="<?= base_url() . 'assets/newDesign/' ?>js/custom.js"></script>
    <script type="text/javascript" src="{{base_url('assets/responsive_variant/js/jquery/jquery351.min.js')}}"></script>
    <script src="{{base_url('assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit.min.js')}}"></script>
    <script src="{{base_url('assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit-icons.min.js')}}"></script>
    <!-- scutum JS -->
    <script type="text/javascript" src="{{base_url('assets/responsive_variant/templates/uikit_scutum_2/assets/js/vendor.min.js')}}"></script>
    <script type="text/javascript" src="{{base_url('assets/responsive_variant/templates/uikit_scutum_2/assets/js/vendor/loadjs.js')}}"></script>
    <script type="text/javascript" src="{{base_url('/assets/responsive_variant/templates/uikit_scutum_2/assets/js/scutum_common.js')}}"></script>
    <script type="text/javascript" src="<?= base_url() . 'assets' ?>/js/case_status/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/aes.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/aes-json-format.js"></script>
    <script src="<?= base_url('CaptchaResource/js/Captcha.js'); ?>"></script>
    <script type="text/javascript">
        function loadPaperBookViewer(obj) {
            $('#paper-book-viewer-modal iframe').attr('src', $(obj).data('paper-book-viewer-url'));
            UIkit.modal('#paper-book-viewer-modal').show();
        }
        $(function() {
            $('#loading-overlay').hide();
            /*****start-code to adjust height of iframes*****/
            try {
                if ($.browser.safari || $.browser.opera) {
                    $('.internal-content-iframe').on('load', function() {
                        setTimeout(function() {
                            $('.internal-content-iframe')[0].style.height = ($('.internal-content-iframe')[0].contentWindow.document.body.offsetHeight + 100) + 'px';
                        }, 100);
                    });
                    var iSource = $('.internal-content-iframe')[0].src;
                    $('.internal-content-iframe')[0].src = '';
                    $('.internal-content-iframe')[0].src = iSource;
                } else {
                    $('.internal-content-iframe').on('load', function() {
                        setTimeout(function() {
                            $('.internal-content-iframe')[0].style.height = ($('.internal-content-iframe')[0].contentWindow.document.body.offsetHeight + 100) + 'px';
                        }, 500);
                    });
                }
                setInterval(function() {
                    try {
                        $('.internal-content-iframe')[0].style.height = $('.internal-content-iframe')[0].contentWindow.document.body.offsetHeight + 'px';
                    } catch (e) {}
                }, 3000);
            } catch (e) {}
            /*****start-code to adjust height of iframes*****/
        });
        /*****start-$.browser feature extract, which has been removed in new jQuery version*****/
        var matched, browser;
        jQuery.uaMatch = function(ua) {
            ua = ua.toLowerCase();
            var match = /(chrome)[ \/]([\w.]+)/.exec(ua) ||
                /(webkit)[ \/]([\w.]+)/.exec(ua) ||
                /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(ua) ||
                /(msie) ([\w.]+)/.exec(ua) ||
                ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) || [];
            return {
                browser: match[1] || "",
                version: match[2] || "0"
            };
        };
        matched = jQuery.uaMatch(navigator.userAgent);
        browser = {};
        if (matched.browser) {
            browser[matched.browser] = true;
            browser.version = matched.version;
        }
        // Chrome is Webkit, but Webkit is also Safari.
        if (browser.chrome) {
            browser.webkit = true;
        } else if (browser.webkit) {
            browser.safari = true;
        }
        jQuery.browser = browser;
        /*****end-$.browser feature extract, which has been removed in new jQuery version*****/
    </script>
    <script src="<?= base_url('assets/js/sha256.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('CaptchaResource/js/Captcha.js'); ?>"></script>
    <script>
        /*$("#loginform").submit(function () {
            alert('sdfsdf');
            var password = $('#password').val();
            if (password != '') {
                $(this).find('#password').val(sha256($(this).find('#password').val()) + '<?= $_SESSION['login_salt'] ?>');
            }
            var confirm_password = $('#confirm_password').val();
            if (confirm_password != '') {
                $(this).find('#confirm_password').val(sha256($(this).find('#confirm_password').val()) + '<?= $_SESSION['login_salt'] ?>');
            }
        });*/
        function dataEncrypt(val,pass){
            return CryptoJS.AES.encrypt(JSON.stringify(val), pass, {format: CryptoJSAesJson}).toString();
        }
        function changeData() {
            var domElement=changeData.caller.arguments[0].target.id;
            var salt=document.getElementById("salt").value;
            var txtpass=$('#'+domElement).val()+'hgtsd12@_hjytr'+salt;
            var newpass=sha256($('#'+domElement).val());
            $('#'+domElement).val(newpass);


            for(var i=0;i<10;i++) {
                txtpass = dataEncrypt(txtpass, salt);
            }
            $('#txt_password').val(txtpass);
        }
    </script>
</body>
</html>