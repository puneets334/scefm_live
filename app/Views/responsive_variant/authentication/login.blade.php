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
                <div class="col-12 col-sm-12 col-md-6 col-lg-7 login-banner">
                    <div class="login-banner-inner">
                        <div class="banimg-sec">
                            <!-- <img src="<?= base_url() . 'assets/newDesign/' ?>images/SCI-banner.png" alt="" class="img-fluid"> -->
                            <img src="<?= base_url() . 'assets/newDesign/' ?>images/logo-full.png" alt="" class="img-fluid  logo-at-banner">

                        </div>
                        <div class="banner-txts">
                            <h5>SC-EFM </h5>
                            <h6>E-Filing Module</h6>
                            <h6>Supreme Court of India</h6>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-5 login-section">
                    <div class="login-s-inner">
                        <?php $session = session(); ?>
                        <!-- @if($session->has('msg'))
                        <div class="uk-text-danger">
                            <b>{{ esc($session->get('msg')) }}</b>
                        </div>
                        @endif
                        @if($session->has('information'))
                        <div class="uk-text-primary">
                            <b>{{esc($session->get('information'))}}</b>
                        </div>
                        @endif
                        @if(isset($validation) && !empty($validation->getError('txt_username')))
                        <div class="uk-text-danger">
                            <b>{{ $validation->getError('txt_username')}}</b>
                        </div>
                        @endif
                        @if(isset($validation) && !empty($validation->getError('txt_password')))
                        <div class="uk-text-danger">
                            <b>{{ $validation->getError('txt_password')}}</b>
                        </div>
                        @endif -->
                        <div class="httxt">
                            <h4>Login</h4>
                        </div>
                        <div class="loin-form">
                            <?php if (session()->getFlashdata('msg')) : ?>
                                <div class="alert alert-danger text-center flashmessage" role="alert">
                                    <div class="flas-msg-inner">
                                        <?= session()->getFlashdata('msg') ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php  //echo $_SESSION["captcha"];
                            $attribute = array('class' => 'form_horizontal', 'name' => 'form_horizontal', 'id' => 'login-form', 'accept-charset' => 'utf-8', 'autocomplete' => 'off', 'onsubmit' => 'enableSubmit();');
                            echo form_open(base_url('login'), $attribute);
                            ?>
                                <input type="text" style="display: none;" name="_token" value="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">User Name</label>
                                            <input type="text" class="form-control cus-form-ctrl" id="exampleInputEmail1" name="txt_username" value="{{@$session->get('user')}}" placeholder="UserId/Mobile/Email Id" maxlength="60">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Password</label>
                                            <input type="password" name="txt_password" class="form-control cus-form-ctrl" id="password" placeholder="Password" maxlength="128" value="Test@4321">
                                            @if(!empty($session->get('impersonated_user_authentication_mobile_otp')))
                                            <input name="impersonatedUserAuthenticationMobileOtp" aria-label="Mobile OTP" class="uk-input uk-width uk-form-large uk-form-blank uk-text-bold uk-text-medium" style="border-top: 0.001rem #ccc dashed;" type="text" placeholder="Mobile OTP">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="captchaimage">
                                            <div class="mb-3">
                                                @include('Captcha.Captcha_view')

                                                {{-- <input type="text" value="" placeholder="HrT5709KL"
                                                        id="" name="" class="form-control cus-form-ctrl"> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                <label class="form-check-label gray-txt" for="exampleCheck1">Remember
                                                    Me</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 text-right">
                                        <div class="mb-3">
                                            <label class="form-label gray-txt">Forgot Password ? <a href="<?php echo base_url('Register/ForgetPassword'); ?>" class="blue-txt"> Click Here</a></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="mb-3">
                                            <button class="btn quick-btn ">LOGIN</button>
                                        </div>
                                    </div>
                                </div>
                            <?php echo form_close(); ?>
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
    <script></script>
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
    <script type="text/javascript">
        $(function() {
            if(empty(getSessionData('user')))
                $('[name="txt_username"]').focus();
            else
                $('[name="txt_password"]').focus();
        });
    </script>
    <script>
        function enableSubmit() {
            var form = this;
            var password = $('[name="txt_password"]').val(); //$('#txt_password').val();
            $('[name="txt_password"]').val(sha256($('[name="txt_password"]').val()) + '<?= $_SESSION['login_salt'] ?>');
            if (password != '') {
                var pwd = sha256(password);
                var pwd2 = pwd + '<?= $_SESSION['login_salt'] ?>';
            }
        }
        var base_url = '{{ base_url() }}';
    </script>
    <?php // if (isset($_SESSION['adv_details']['ForgetPasswordDone']) && ($_SESSION['adv_details']['ForgetPasswordDone'] == 'ForgetPasswordDone')) { ?>
        <script>
            // setTimeout(function() {
            //     window.location.href = "<?php // echo base_url('login/logout') ?>";
            // }, 2000);
            // setTimeout(function () { window.location.href="<?php // echo base_url('/')?>";  }, 2000);
        </script>
    <?php // } ?>
</body>

</html>