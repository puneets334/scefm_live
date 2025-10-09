<!DOCTYPE html>
<html lang="en">
<head>
    @php
        header("X-Frame-Options: DENY");
        header("X-Content-Type-Options: nosniff");
        //header("Strict-Transport-Security: max-age=31536000");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    @endphp
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#343a40">
    <title>Login | SC-eFM @ Supreme Court of India</title>
    
    <base ref="{{ base_url() }}">
    <!-- added for case_status-start -->

    <link rel="stylesheet" href="<?= base_url().'assets'?>/css/case_status/bootstrap.min.css">
    <!--<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">-->
    <!-- added for case_status-end -->

    <link rel="stylesheet" href="{{base_url('assets/responsive_variant/templates/uikit_scutum_2/assets/css/main.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{base_url('assets/responsive_variant/frameworks/uikit_3-4-1/css/uikit.min.css')}}" />
    <link rel="stylesheet" href="{{base_url('assets/responsive_variant/templates/uikit_scutum_2/assets/css/fonts/mdi_fonts.css')}}" />
    <link rel="stylesheet" href="{{base_url('assets/responsive_variant/templates/uikit_scutum_2/assets/css/materialdesignicons.min.css')}}" />
    <!--<link href="<?/*= base_url('assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css'); */?>" rel="stylesheet">-->
    <link href="https://fonts.googleapis.com/css2?family=Barlow:ital,wght@0,100;0,300;0,400;0,500;0,600;0,700;1,100;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" media="none" onload="if(media!='all')media='all'">


    <!--<script type="text/javascript" href="https://code.jquery.com/jquery-3.5.1.js"></script>-->

    

    <script type="text/javascript" src="{{base_url('assets/responsive_variant/js/angularjs/angular.min.js')}}"></script>
    <!--<script type="text/javascript" src="{{base_url('assets/responsive_variant/js/angular/angular-sanitize.min.js')}}"></script>-->

    <?php if(isset($currentPath) && !empty($currentPath) && $currentPath == 'efiling_search') {?>
    <style>
        header{display: none !important;}
        .efiling_search{visibility: hidden !important;}
    </style>
    <?php } ?>

    <style type="text/css">
        *{
            font-family: 'Barlow', sans-serif !important;
            /*font-family: 'Playfair Display', serif;*/
        }
        .text-saffron{
            color:#d94d21;
        }
        .text-white{
            color:#ffffff !important;
        }
        .text-pitch-black{
            color:#000000 !important;
        }
        .bg-white{
            background-color:#ffffff;
        }
        .bg-saffron{
            background-color:#d94d21;
        }
        .bg-black{
            background-color:#222222;
        }
        .bg-pitch-black{
            background-color:#000000 !important;
        }
        .bg-dark{
            background-color: #343a40!important;
        }
        .bg-danger{
            background-color: #f0506e;
        }
        .bg-success{
            background-color: #32D296;
        }
        .bg-warning{
            background-color: #FAA05A;
        }
        .bg-danger-combo{
            background-color: #f0506e;
            color:#ffffff;
        }
        .bg-success-combo{
            background-color: #32D296;
            color:#ffffff;
        }
        .border-white{
            border-color:#ffffff;
        }
        .border-black{
            border-color:#222222;
        }
        .border{
            border: 1px solid;
        }
        .border-primary{
            border-color: #1e87f0;
        }
        .border-warning{
            border-color: #FAA05A;
        }
        .border-danger{
            border-color: #f0506e;
        }
        .border-dark{
            border-color: #343a40;
        }
        .border-pitch-black{
            border-color: #000000;
        }
        .border-muted{
            border-color: #999999;
        }
        .bg-saffron-light{
            background-color:#f7dbd2;/*#fbede8;*/
        }
        .bg-gray{
            background-color: #C0C0C0;
        }
        .form-blank{
            background-color: #ffffff !important;
            border-color: #ffffff !important;
        }
        #loading-overlay {
            position: fixed; /* Sit on top of the page content */
            display: block; /* Hidden by default */
            width: 100%; /* Full width (cover the whole page) */
            height: 100%; /* Full height (cover the whole page) */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1051; /* Specify a stack order in case you're using a different order for other elements */
        }
        .full-overlay {
            position: fixed; /* Sit on top of the page content */
            display: block; /* Hidden by default */
            width: 100%; /* Full width (cover the whole page) */
            height: 100%; /* Full height (cover the whole page) */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1051; /* Specify a stack order in case you're using a different order for other elements */
        }

        /***start-from bootstrap***/
        @media print {
            .d-print-none {
                display: none !important;
            }
            .d-print-inline {
                display: inline !important;
            }
            .d-print-inline-block {
                display: inline-block !important;
            }
            .d-print-block {
                display: block !important;
            }
            .d-print-table {
                display: table !important;
            }
            .d-print-table-row {
                display: table-row !important;
            }
            .d-print-table-cell {
                display: table-cell !important;
            }
            .d-print-flex {
                display: -webkit-box !important;
                display: -ms-flexbox !important;
                display: flex !important;
            }
            .d-print-inline-flex {
                display: -webkit-inline-box !important;
                display: -ms-inline-flexbox !important;
                display: inline-flex !important;
            }
        }
        /***end-from bootstrap***/

        /***start-modifications in scutum***/
        .uk-form-icon{
            transform: none;
            -webkit-transform: none;
        }
        .uk-notification .uk-notification-message{
            font-size:1.30rem;
        }
        /***end-modifications in scutum***/
    </style>
</head>
<body ngapp="efilingNearApp" ngcontroller="efilingNearController"><!--todo:find a solution to enable this....coz earlier this gave an issue wherein secure gate had initialized its own app & controller.-->

<div id="loading-overlay" class="uk-overlay-default uk-position-cover d-print-none">
    <div class="uk-flex uk-flex-center uk-flex-middle" uk-height-viewport uk-grid>
        <span uk-spinner="ratio: 2.2"></span>
    </div>
</div>
<div id="primary-overlay" class="full-overlay uk-overlay-primary uk-position-cover d-print-none" style="display:none;">
</div>
<script type="text/javascript">
    window.onbeforeunload = function(){
        $('#loading-overlay').show();
    };
</script>
<style type="text/css">
    @media only screen and (min-width: 960px) {
        body{
            overflow-y:hidden;
        }
    }
</style>
<main id="content-container" role="main" class="uk-container uk-container-expand">
    <input type="text" style="display: none" name="CSRF_TOKEN" value="{{ csrf_token() }}">

    <div class="ukgrid-collapse uk-position-relative uk-margin-small-top" style="z-index:2;" uk-grid>
        @section('pinned-main-offcanvas')
        <div class="uk-width-1-5 uk-visible@l" uk-margin>
            @include('responsive_variant.layouts.master.uikit_scutum_2.pinned_main_offcanvas')
        </div>
        @endsection
        <div id="content" class="uk-width-expand">
            @if(is_logged_in())
                <div id="heading-container" class="bgdark textwhite uk-margin-small-bottom">
                    <div class="uk-flex uk-flex-between@m uk-flex-right" ukgrid>
                        <h2 class="textwhite uk-margin-remove uk-visible@m ukwidth-expand"><a href="{{current_url()}}" class="uk-button-text uk-text-capitalize">@yield('heading')</a></h2>
                    </div>
                </div>
            @endif

            <div id="login-container" class="uk-child-width-1-1 uk-child-width-1-2@m uk-grid-small" uk-grid uk-height-viewport="offset-top:true">
                <div class="uk-visible@m" styl="margin-bottom:4rem;">
                    <!--<div class="uk-background-contain uk-height-1-1" style="background-image: url({{base_url('assets/responsive_variant/images/illustrations/undraw_setup_obqo_alt_1.png')}});">
            
                    </div>-->
                    <div class="ukflex ukflex-middle uk-width uk-background-center-center uk-background-norepeat uk-height-1-1" style="background-image: url({{base_url('assets/responsive_variant/images/sci/building/front_clipart.jpg')}});background-size:55rem">
                        <div class="uk-width uk-text-center" styl="margin-bottom:6rem;">
                            <div class="uk-label text-white uk-margin-small-bottom" style="background-color:#1e87f0;padding:0.3rem 1.3rem;font-size:2rem;font-weight:500;">
                                <i class="mdi mdi-cloud-tags sc-icon-44"></i>&nbsp;&nbsp;
                                SC-eFM
                            </div>
                            <!--<div class="uk-h3 uk-text-bold md-color-grey-600 uk-margin-remove-top uk-margin-large-bottom">Supreme Court Prison Connect Module</div>-->
                            <div class="uk-h3 uk-text-bold md-color-grey-600 uk-margin-remove-top uk-margin-large-bottom">E-Filing Module <p>Supreme Court Of India</p></div>
                        </div>
                    </div>
                </div>
                
                <div class="uk-flex uk-flex-middle">
                    <div class="uk-width uk-text-center">
                        <div class="uk-visible@m uk-text-lead uktext-bolder ukheading-small uk-margin-small-bottom" style="font-size:2rem !important;ont-weight:500;">
                            Login Here&nbsp;
                            <i class="mdi mdi-arrow-down-circle-outline"></i>
                        </div>
                        <div class="uk-hidden@m uk-label text-white uk-margin-small-bottom" style="background-color:#1e87f0;padding:0.3rem 1.3rem;font-size:2rem;font-weight:500;">
                            <i class="mdi mdi-cloud-tags sc-icon-44"></i>&nbsp;&nbsp;
                            SC-EFM
                        </div>
                        
                        <div class="uk-hidden@m uk-h4 uk-text-bold md-color-grey-600 uk-margin-remove-top uk-margin-large-bottom">E-Filing Module <p>Supreme Court Of India</p></div>
                        <?php $session = session(); ?>
                        <div class="ukwidth-4-5@s ukwidth-3-5@m uk-width-medium@s uk-width-large@m uk-align-center" uk-margin>
                            @if($session->has('msg'))
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
                            @endif
                            <div class="uk-grid-collapse" uk-grid>
                                <span class="uk-width-1-2 uk-text-bold uk-text-medium uktext-left">UserId or Mobile or Email Id</span>
                                <span class="uk-width-1-2 uk-text-bold uk-text-medium uktext-left">Password</span>
                            </div>
                            <div class="uk-card uk-card-body uk-padding-remove ukborder-rounded" style="border-radius:9px">
                                <?php  //echo $_SESSION["captcha"];
                                $attribute = array('class' => 'form_horizontal', 'name' => 'form_horizontal', 'id' => 'login-form','accept-charset'=>'utf-8', 'autocomplete' => 'off' ,'onsubmit'=>'enableSubmit();');
                                echo form_open(base_url('login'), $attribute);
                                ?>
                               <input type="text" style="display: none" name="_token" value="{{ csrf_token() }}">
                                <!--<form id="login-form" method="POST" action="{{base_url('login')}}" autocomplete="off">-->
                                    <input type="hidden" name="ctvrg">
                                    <div class="uk-grid-collapse" uk-grid>
                                        <input  aria-label="User or Email or Mobile" class="uk-input uk-width-1-2 uk-form-large uk-form-blank uk-text-bold uk-text-medium" styl="border-right: 0.001rem #ccc dashed;border-right-radius:0;" type="text"  name="txt_username" value="{{@$session->get('user')}}" placeholder="UserId/Mobile/Email Id" maxlength="60">
                                        <input name="txt_password" aria-label="Password" class="uk-input uk-width-1-2 uk-form-large uk-form-blank uk-text-bold uk-text-medium" styl="border-left: 0.001rem #ccc dashed;border-left-radius:0;" type="password" placeholder="Password" maxlength="128">
            
                                        @if(!empty($session->get('impersonated_user_authentication_mobile_otp')))
                                        <input name="impersonatedUserAuthenticationMobileOtp" aria-label="Mobile OTP" class="uk-input uk-width uk-form-large uk-form-blank uk-text-bold uk-text-medium" style="border-top: 0.001rem #ccc dashed;" type="text" placeholder="Mobile OTP">
                                        @endif
                                    </div>
                                    @include('Captcha.Captcha_view')
            
                                    <button type="submit" class="sc-button uk-button-secondary uk-width uk-border-none uk-button-large" style="height:3.2rem;border-radius:0 0 9px 9px;"><b>Sign In</b></button>
                                    <?= form_close() ?>
            
            
                            </div>
                        </div>
                        <div>
                            <a href="{{base_url('register/ForgetPassword')}}" class="uk-button uk-button-link uk-text-bold uk-text-danger">Forgot Password ?</a>
                        </div>
                        <div class="uk-margin-small-top">
                            <b class="uk-text-muted">Register as:</b><br>
                            <a href="{{base_url('register')}}" class="ukbutton ukbutton-link">Individual (Party in Person) </a>
                            &nbsp;&nbsp;&nbsp;or&nbsp;&nbsp;&nbsp;
                            <a href="{{base_url('register/AdvocateOnRecord')}}" class="ukbutton ukbutton-link">AOR</a>
                            &nbsp;&nbsp;&nbsp;or&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="{{base_url('arguingCounselRegister')}}" class="ukbutton ukbutton-link">Advocate</a>
                        </div>
                        <div class="uk-alert" >
                            <p class=" uk-text-large uk-text-bold">In case any user is unable to access the e-filing services on this portal,
                                please email your complaint/issue(s) along with relevant screenshot(s) to – <span class="uk-text-primary">efiling[at]sci[dot]nic[dot]in</span></p>
                        </div>
                        <div class="uk-alert" >
                            <div class="uk-margin-small-top">
                                <b class="uk-text-muted">Resources:</b><br>
                                <a href="{{base_url('e-resources')}}" class="ukbutton ukbutton-link">Handbook</a>
                                &nbsp;&nbsp;&nbsp;or&nbsp;&nbsp;&nbsp;
                                <a href="{{base_url('e-resources/video-tutorial')}}" class="ukbutton ukbutton-link">Video Tutorial</a>
                                &nbsp;&nbsp;&nbsp;or&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="{{base_url('e-resources/FAQs')}}" class="ukbutton ukbutton-link">FAQ</a>
                                &nbsp;&nbsp;&nbsp;or&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="{{base_url('adminReport/DisplayStatsReports')}}" target="_new" class="ukbutton ukbutton-link">Stats</a>
                                &nbsp;&nbsp;&nbsp;or&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="{{base_url('e-resources/Three_PDF_user_manual')}}" target="_new" class="ukbutton ukbutton-link">3PDF User Manual</a>
            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-hidden@m uk-width uk-height-small uk-background-cover uk-background-bottom-center uk-background-norepeat ukheight-1-1" style="background-image: url({{base_url('assets/responsive_variant/images/sci/building/front_clipart.jpg')}});"></div>
            
            
        </div>
    </div>
</main>

<div id="paper-book-viewer-modal" class="uk-modal-full" uk-modal="bg-close:false;esc-close:false;">
    <div class="uk-modal-dialog" uk-overflow-auto>
        <button class="uk-modal-close-full uk-close-large" type="button" uk-close></button>

        <iframe src="" height="100%" width="100%" scrolling frameborder="no" uk-height-viewport></iframe>
    </div>
</div>


<footer class="uk-margin-medium-top d-print-none">

</footer>
<script type="text/javascript" src="{{base_url('assets/responsive_variant/js/jquery/jquery351.min.js')}}"></script>

<script src="{{base_url('assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit.min.js')}}"></script>
<script src="{{base_url('assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit-icons.min.js')}}"></script>

<!-- scutum JS -->
<script type="text/javascript" src="{{base_url('assets/responsive_variant/templates/uikit_scutum_2/assets/js/vendor.min.js')}}"></script>
<script type="text/javascript" src="{{base_url('assets/responsive_variant/templates/uikit_scutum_2/assets/js/vendor/loadjs.js')}}"></script>
<script type="text/javascript" src="{{base_url('/assets/responsive_variant/templates/uikit_scutum_2/assets/js/scutum_common.js')}}"></script>

{{-- <script type="text/javascript" src="<?= base_url().'assets'?>/js/case_status/common.js"></script> --}}


<script type="text/javascript" src="<?= base_url().'assets'?>/js/case_status/bootstrap.min.js"></script>

<script type="text/javascript" src="<?=base_url()?>assets/js/aes.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/aes-json-format.js"></script>
<script src="<?=base_url('CaptchaResource/js/Captcha.js');?>"></script>
<script type="text/javascript">
    function loadPaperBookViewer(obj){
        $('#paper-book-viewer-modal iframe').attr('src', $(obj).data('paper-book-viewer-url'));
        UIkit.modal('#paper-book-viewer-modal').show();
    }
    $(function () {
        $('#loading-overlay').hide();
        /*****start-code to adjust height of iframes*****/
        try {
            if ($.browser.safari || $.browser.opera) {
                $('.internal-content-iframe').on('load', function () {
                    setTimeout(function () {
                        $('.internal-content-iframe')[0].style.height = ($('.internal-content-iframe')[0].contentWindow.document.body.offsetHeight + 100) + 'px';
                    }, 100);
                });

                var iSource = $('.internal-content-iframe')[0].src;
                $('.internal-content-iframe')[0].src = '';
                $('.internal-content-iframe')[0].src = iSource;
            } else {
                $('.internal-content-iframe').on('load', function () {
                    setTimeout(function () {
                        $('.internal-content-iframe')[0].style.height = ($('.internal-content-iframe')[0].contentWindow.document.body.offsetHeight + 100) + 'px';
                    }, 500);
                });
            }
            setInterval(function () {
                try {
                    $('.internal-content-iframe')[0].style.height = $('.internal-content-iframe')[0].contentWindow.document.body.offsetHeight + 'px';
                }
                catch(e){}
            }, 3000);
        } catch(e){}
        /*****start-code to adjust height of iframes*****/
    });

    /*****start-$.browser feature extract, which has been removed in new jQuery version*****/
    var matched, browser;

    jQuery.uaMatch = function( ua ) {
        ua = ua.toLowerCase();

        var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
            /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
            /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
            /(msie) ([\w.]+)/.exec( ua ) ||
            ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
            [];

        return {
            browser: match[ 1 ] || "",
            version: match[ 2 ] || "0"
        };
    };

    matched = jQuery.uaMatch( navigator.userAgent );
    browser = {};

    if ( matched.browser ) {
        browser[ matched.browser ] = true;
        browser.version = matched.version;
    }

    // Chrome is Webkit, but Webkit is also Safari.
    if ( browser.chrome ) {
        browser.webkit = true;
    } else if ( browser.webkit ) {
        browser.safari = true;
    }

    jQuery.browser = browser;
    /*****end-$.browser feature extract, which has been removed in new jQuery version*****/
</script>
<script src="<?= base_url('assets/js/sha256.js'); ?>" type="text/javascript"></script>
    <script src="<?=base_url('CaptchaResource/js/Captcha.js');?>"></script>
    <script type="text/javascript">
        $(function(){
            @if(empty(@$session->get('user')))
                $('[name="txt_username"]').focus();
            @else
                $('[name="txt_password"]').focus();
            @endif
    
        });
    </script>
    <script>
        function enableSubmit() {
            var form = this;
            var password= $('[name="txt_password"]').val(); //$('#txt_password').val();
            $('[name="txt_password"]').val(sha256($('[name="txt_password"]').val()) + '<?= $_SESSION['login_salt'] ?>');
            if (password !='') {
                var pwd=sha256(password);
                var pwd2=pwd+'<?=$_SESSION['login_salt'] ?>';
            }
        }
        var base_url = '{{ base_url() }}';
    </script>
    <?php if (isset($_SESSION['adv_details']['ForgetPasswordDone']) && ($_SESSION['adv_details']['ForgetPasswordDone']=='ForgetPasswordDone')){?>
        <script>
            setTimeout(function () { window.location.href="<?php echo base_url('login/logout')?>";  }, 2000);
        </script>
    <?php }?>

<style type="text/css">
    #content-container::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 0;
        pointer-events: none;
        height: 390px;
        background-color: #343a40;
        border-bottom-left-radius: 2.5rem;
    }
    
</style>

</body>
</html>
