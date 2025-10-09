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
                        <!-- @if($session->getFlashdata('msg'))
                            <div class="alert alert-danger text-center flashmessage" role="alert">
                                {{ esc($session->getFlashdata('msg')) }}
                            </div>
                        @endif
                        @if($session->has('information'))
                        <div class="uk-text-primary">
                            <b>{{esc($session->get('information'))}}</b>
                        </div>
                        @endif
                        @if(isset($validation) && !empty($validation->getError('txt_username')))
                        <div class="alert alert-danger text-center flashmessage" role="alert">
                            <b>{{ $validation->getError('txt_username')}}</b>
                        </div>
                        @endif
                        @if(isset($validation) && !empty($validation->getError('txt_password')))
                        <div class="alert alert-danger text-center flashmessage" role="alert">
                            <b>{{ $validation->getError('txt_password')}}</b>
                        </div>
                        @endif -->
                        <div class="loin-form">
                            <?php
                            $attributes = array("class" => "form-horizontal", "id" => "loginform", "name" => "loginform", 'autocomplete' => 'off');
                            echo form_open("Register/ForgetPassword/adv_get_otp", $attributes);
                                $segment = service('uri');
                                if ($segment->getSegment(2) == 'AdvocateOnRecord') {
                                    $title = 'Advocate On Record';
                                } elseif ($segment->getSegment(2) == 'ForgetPassword') {
                                    $title = 'Forgot Password';
                                } else {
                                    $title = 'Party In Person';
                                }
                                ?>
                                <div class="httxt">
                                    <h4> <?php echo $title; ?> </h4>
                                </div>
                                <input type="hidden" name="register_type" value="<?php echo $title; ?>">
                                <?php if (session()->getFlashdata('msg')) : ?>
                                    <div class="alert alert-danger text-center flashmessage" role="alert">
                                        <div class="flas-msg-inner">
                                            <?= session()->getFlashdata('msg') ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (session()->getFlashdata('error')) : ?>
                                    <div class="alert alert-danger text-center flashmessage" role="alert">
                                        <div class="flas-msg-inner">
                                            <?= session()->getFlashdata('error') ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                @if(isset($validation) && !empty($validation->getError('adv_email')))
                                <div class="alert alert-danger text-center flashmessage" role="alert">
                                    <b>{{ $validation->getError('adv_email')}}</b>
                                </div>
                                @endif
                                @if(isset($validation) && !empty($validation->getError('adv_mobile')))
                                <div class="alert alert-danger text-center flashmessage" role="alert">
                                    <b>{{ $validation->getError('adv_mobile')}}</b>
                                </div>
                                @endif
                                <input type="text" style="display: none;" name="_token" value="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Mobile</label>
                                            <input type="text" class="form-control cus-form-ctrl" id="exampleInputEmail1" name="adv_mobile" minlength="10" maxlength="10" placeholder="Mobile">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 my-2 ">
                                        <div class="text-center">
                                            <h6 class="gray-txt">Or</h6>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Email</label>
                                            <input type="text" name="adv_email" class="form-control cus-form-ctrl" id="password" placeholder="Email ID">

                                        </div>
                                    </div>
                                </div>
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
                                                <button class="btn quick-btn ">SEND OTP</button>
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
    @endsection
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
            $('[name="txt_password"]').val(sha256($('[name="txt_password"]').val()) + '<?php echo isset($_SESSION['login_salt']) ? $_SESSION['login_salt'] : ''; ?>');
            if (password != '') {
                var pwd = sha256(password);
                var pwd2 = pwd + '<?php echo isset($_SESSION['login_salt']) ? $_SESSION['login_salt'] : ''; ?>';
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