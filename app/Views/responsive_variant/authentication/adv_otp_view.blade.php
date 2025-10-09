<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
@extends('layout.frontApp')
@section('content')
    <!-- Login Area Start  -->
    <div class="login-area">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-7 col-lg-7 login-banner">
                    <div class="login-banner-inner">
                        <div class="banimg-sec">
                            <!-- <img src="<?= base_url() . 'assets/newDesign/' ?>images/logo-full.png" alt="" class="img-fluid"> -->
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
                    <div class="login-s-inner loin-form">
                        <?php
                        $attributes = array("class" => "form-horizontal", "id" => "loginform", "name" => "loginform", 'autocomplete' => 'off');
                        echo form_open("Register/verify", $attributes);
                        ?>
                        <div class="httxt">
                            <h4><?=isset($_SESSION['adv_details']) ? $_SESSION['adv_details']['register_type'] : ''; ?></h4>
                        </div>
                        @if(isset($validation) && !empty($validation->getError('adv_email')))
                        <div class="uk-text-danger">
                            <b>{{ $validation->getError('adv_email')}}</b>
                        </div>
                        @endif
                        @if(isset($validation) && !empty($validation->getError('adv_mobile')))
                        <div class="uk-text-danger">
                            <b>{{ $validation->getError('adv_mobile')}}</b>
                        </div>
                        @endif
                        <?php
                        $segment = service('uri');
                        if ($segment->getSegment(2) == 'AdvocateOnRecord') {
                            $title = 'Advocate On Record';
                        } elseif ($segment->getSegment(2) == 'ForgetPassword') {
                            $title = 'Forgot Password';
                        } else {
                            $title = 'Party In Person';
                        }
                        ?>
                        <!-- <h4> <strong><?php // echo $title; ?></strong></h4> -->
                        <input type="hidden" name="register_type" value="<?php echo $title; ?>">
                        <?php if (session()->getFlashdata('msg_success')) : ?>
                                   
                                   <div class="text-success" style="border: 2px solid green; background-color: #e6ffe6; padding: 10px; border-radius: 5px;">
                                       <b><?= session()->getFlashdata('msg_success') ?></b>
                                   </div>
                               <?php endif; ?>
                        <?php if (session()->getFlashdata('msg')) : ?>
                            <div class="alert alert-danger text-center flashmessage" role="alert">
                                <div class="flas-msg-inner">
                                    <?= session()->getFlashdata('msg') ?>
                                </div>
                            </div>
                        <?php endif; ?><br>
                        @if(isset($validation) && !empty($validation->getError('adv_email')))
                        <div class="uk-text-danger">
                            <b>{{ $validation->getError('adv_email')}}</b>
                        </div>
                        @endif
                        @if(isset($validation) && !empty($validation->getError('adv_mobile')))
                        <div class="uk-text-danger">
                            <b>{{ $validation->getError('adv_mobile')}}</b>
                        </div>
                        @endif
                        <input type="text" style="display: none;" name="_token" value="{{ csrf_token() }}">
                        <?php if (isset($_SESSION['adv_details']) && $_SESSION['adv_details']['mobile_no']) { ?>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Mobile : <span class="blue-txt"><?php echo $_SESSION['adv_details']['mobile_no']; ?></span></label>
                                    <!--<input class="uk-input" type="text" value="<?php /*echo $_SESSION['adv_details']['mobile_otp']; */ ?>" name="adv_mobile_otp"  placeholder="Enter Mobile OTP" maxlength="6" minlength="6">-->
                                    <input class="form-control cus-form-ctrl" type="text" value="" name="adv_mobile_otp" placeholder="Enter Mobile OTP" maxlength="6" minlength="6">
                                    <?php if (isset($validation) && $validation->hasError('adv_mobile_otp')): ?>
                                            <div class="text-danger">
                                                <?= $validation->getError('adv_mobile_otp'); ?>
                                            </div>
                                        <?php endif; ?>
                                </div>
                                
                            </div>
                        <?php } ?>
                        <?php if (isset($_SESSION['adv_details']) && $_SESSION['adv_details']['email_id']) { ?>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Email Id : <span class="blue-txt"><?php echo $_SESSION['adv_details']['email_id']; ?></span></label>
                                    <!--<input class="uk-input" type="text" value="<?php /*echo $_SESSION['adv_details']['email_otp']; */ ?>" id="adv_email_otp" name="adv_email_otp" maxlength="6" minlength="6" placeholder="Enter Email OTP">-->
                                    <input class="form-control cus-form-ctrl" type="text" value="" id="adv_email_otp" name="adv_email_otp" maxlength="6" minlength="6" placeholder="Enter Email OTP">
                                    <?php if (isset($validation) && $validation->hasError('adv_email_otp')): ?>
                                            <div class="text-danger">
                                                <?= $validation->getError('adv_email_otp'); ?>
                                            </div>
                                        <?php endif; ?>
                                </div>
                                
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="captchaimage">
                                    <div class="mb-3">
                                        @include('Captcha.Captcha_view')
                                        {{-- <input type="text" value="" placeholder="HrT5709KL" id="" name="" class="form-control cus-form-ctrl"> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="mb-3">
                                        <button class="btn quick-btn ">VERIFY</button>
                                    </div>
                                </div>
                            </div>
                            </form>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Login Area End  -->
    @endsection
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
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
                ua.indexOf("compatible") < script && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) || [];
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
</body>
</html>