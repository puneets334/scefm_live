
@extends('layout.frontApp')
@section('content')
<!-- Login Area Start  -->
<div class="login-area">
    <div class="container">
        <div class="row" id="default">
            <div class="col-12 col-sm-12 col-md-6 col-lg-7 login-banner">
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



            <div class="col-12 col-sm-12 col-md-6 col-lg-5 login-section">
                <div class="login-s-inner">
                    <?php $session = session(); ?>
                    @if($session->has('msg_success'))
                    <div class="text-success" style="border: 2px solid green; background-color: #e6ffe6; padding: 10px; border-radius: 5px;">
                        <b>{{ esc($session->get('msg_success')) }}</b>
                    </div>
                    @endif
                    @if($session->has('msg'))
                    <div class="text-success" style="border: 2px solid green; background-color: #e6ffe6; padding: 10px; border-radius: 5px;">
                        <b>{{ esc($session->get('msg')) }}</b>
                    </div>
                    @endif
                    @if($session->has('errMsg'))
                    <div class="text-danger">
                        <b>{{ esc($session->get('errMsg')) }}</b>
                    </div>
                    @endif
                    @if($session->has('otpmsg'))
                    <div class="text-danger" style="border: 2px solid red; background-color:rgb(248, 173, 189); padding: 10px; border-radius: 5px;">
                        <b>{{ esc($session->get('otpmsg')) }}</b>
                    </div>
                    @endif
                    @if($session->has('information'))
                    <div class="text-primary">
                        <b>{{esc($session->get('information'))}}</b>
                    </div>
                    @endif
                    @if(isset($validation) && !empty($validation->getError('txt_username')))
                    <div class="text-danger">
                        <b>{{ $validation->getError('txt_username')}}</b>
                    </div>
                    @endif
                    @if(isset($validation) && !empty($validation->getError('txt_password')))
                    <div class="text-danger">
                        <b>{{ $validation->getError('txt_password')}}</b>
                    </div>
                    @endif
                    @if($session->getFlashData('msg'))
                    <div class="text-danger">
                        <b>{{ esc($session->getFlashData('msg')) }}</b>
                    </div>
                    @endif
                    @if($session->getFlashData('error'))
                    <div class="text-danger">
                        <b>{{ esc($session->getFlashData('error')) }}</b>
                    </div>
                    @endif

                    <div class="httxt">
                        <h4>Login <span class="loginAs"></span></h4>
                    </div>
                    <div class="loin-form">
                            @if(!empty(JANPARICHAY_CLIENT_ACTIVE))
                            <div class="uk-card-body uk-padding-remove ukborder-rounded">
                                <a href="{{janparichay_login_url()}}" style="text-decoration: none;">
                                <!-- <a href="#" style="text-decoration: none;"> -->
                                    <div style="align-items: center; background-color: #002a5c; color: white;padding: 10px 20px;border-radius: 6px;font-size: 16px;font-weight: 500;box-shadow: 0 2px 5px rgba(0,0,0,0.2);cursor: pointer;">
                                        <img src="<?= base_url('assets/images/pehchaan-logo-blue.png') ?>" alt="Meri Pehchaan" style="height: 32px; margin-right: 10px;">
                                        Login AOR with Jan Parichay
                                    </div>
                                </a>
                            </div>
                            <br />
                            @endif
                        <?php  //echo $_SESSION["captcha"];
                        $attribute = array('class' => 'form_horizontal', 'name' => 'form_horizontal', 'id' => 'login-form', 'accept-charset' => 'utf-8', 'autocomplete' => 'off', 'onsubmit' => 'enableSubmit();');
                        echo form_open(base_url('login'), $attribute);
                        ?>
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                    <label for="" class="form-label">User Name <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control cus-form-ctrl" id="exampleInputEmail1" name="txt_username" value="<?= session()->getFlashdata('old_username') ? session()->getFlashdata('old_username') : '' ?>" placeholder="UserId/Mobile/Email Id" maxlength="60" required>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                    <label for="" class="form-label">Password <span style="color: red;">*</span></label>
                                    <input type="password" name="txt_password" class="form-control cus-form-ctrl" id="password" placeholder="Password" maxlength="128" value="" required>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="captchaDiv">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="captchaimage">
                                    <div class="mb-3">
                                        @include('Captcha.Captcha_view')
                                    </div>
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
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <!-- <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                        <label class="form-check-label gray-txt" for="exampleCheck1">Remember
                                            Me</label> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 text-right">
                                <div class="mb-3">
                                    <label class="form-label gray-txt">Forgot Password ? <a href="<?php echo base_url('Register/ForgetPassword'); ?>" class="blue-txt"> Click Here</a></label>
                                </div>
                            </div>
                        </div>
                        </form>
                        <div class="regester-txts">
                            <h6 class="htsmall">Login As :</h6>
                            <div class="regester-links">
                                <a href="javascript:;" class="blue-txt aor-login">Authenticated by AOR</a>
                            </div>
                        </div>
                        <div class="regester-txts">
                            <h6 class="htsmall">Register As :</h6>
                            <div class="regester-links">
                                <a href="{{base_url('register')}}" class="blue-txt">Individual (Party In Person)</a>
                                <span class="gray-txt">Or</span>
                                <a href="{{base_url('register/AdvocateOnRecord')}}" class="blue-txt"> AOR</a>
                                <span class="gray-txt">Or</span>
                                <a href="{{base_url('arguingCounselRegister')}}" class="blue-txt">Advocate/Appearing Counsel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="aor" style="display:none;">
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
                <div class="login-s-inner loin-form">
                    <?php $session = session(); ?>
                    <div class="httxt">
                        <h4>Login <span class="loginAs"></span></h4>
                    </div>
                    <div class="loin-form">
                        <?php if (session()->getFlashdata('msg')) : ?>
                            <div class="alert alert-success text-center flashmessage" role="alert">
                                <div class="flas-msg-inner" style="color:green;border:darkgreen">
                                    <?= session()->getFlashdata('msg') ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (session()->getFlashdata('errMsg')) : ?>
                            <div class="alert alert-danger text-center flashmessage" role="alert">
                                <div class="flas-msg-inner">
                                    <?= session()->getFlashdata('errMsg') ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php  //echo $_SESSION["captcha"];
                        $attribute = array('class' => 'form_horizontal', 'name' => 'form_horizontal', 'id' => 'login-form', 'accept-charset' => 'utf-8', 'autocomplete' => 'off', 'onsubmit' => 'enableSubmit();');
                        echo form_open(base_url('login'), $attribute);
                        ?>
                        <?= csrf_field() ?>
                        <input type="hidden" name="userType" class="userType" id="userType" value="AUTHENTICATED_BY_AOR">
                        <?php if (!empty($userEnteredData) && !empty($session->get('impersonated_user_authentication_mobile_otp'))) {
                                $dis11 = 'style="display: none;"';
                            } else {
                                $dis11 = 'style="display: block;"';
                            } ?>
                        <div class="row" <?=$dis11; ?>>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="row">
                                <div class="col-4">
                                    <label for="AOR Mobile" class="form-label">AOR Mobile</label>

                                    <input type="radio" class="using" name="using" value="AOR Mobile" <?php if ((!empty($userEnteredData) && $userEnteredData['using'] == 'AOR Mobile') || (empty($userEnteredData) && empty($userEnteredData['using']))) {
                                                                                                            echo "checked";
                                                                                                        } ?>>
                                </div>
                                <div class="col-4">
                                    <label for="AOR Code" class="form-label">AOR Code</label>
                                    <input type="radio" class="using" name="using" value="AOR Code" <?php if (!empty($userEnteredData) && $userEnteredData['using'] == 'AOR Code') {
                                                                                                        echo "checked";
                                                                                                    } ?>>
                                </div>
                            </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">

                            <?php if (!empty($userEnteredData) && (empty($session->get('impersonated_user_authentication_mobile_otp')) && $userEnteredData['using'] == 'AOR Mobile')) {
                                $dis = 'style="display: block;"';
                            } else {
                                $dis = 'style="display: none;"';
                            } ?>

                            <div class="mb-3" id="aorMobileBox" <?= $dis; ?>>
                                <label for="" class="form-label">AOR Mobile</label>
                                <input type="text" class="form-control cus-form-ctrl" id="aor_mobile" name="aor_mobile" placeholder="Mobile" minlength="10" pattern=".{10,10}" maxlength="10" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" value="<?= (!empty($userEnteredData['aor_mobile']) ? $userEnteredData['aor_mobile'] : '') ?>">
                            </div>

                            <?php if (!empty($userEnteredData) && (empty($session->get('impersonated_aor_authentication_mobile_otp')) && $userEnteredData['using'] == 'AOR Code')) {
                                $dis1 = 'style="display: block;"';
                            } else {
                                $dis1 = 'style="display: none;"';
                            }
                            ?>
                            <div class="mb-3" id="aorcodeBox" <?= $dis1; ?>>
                                <label for="" class="form-label">AOR Code</label>
                                <input type="text" class="form-control cus-form-ctrl" id="aor_code" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" name="aor_code" placeholder="AOR code" maxlength="5" value="<?= (!empty($userEnteredData['aor_code']) ? $userEnteredData['aor_code'] : '') ?>">
                            </div>
                            
                            @if(!empty($session->get('impersonated_aor_authentication_mobile_otp'))&& (!empty($userEnteredData['aor_code']) || !empty($userEnteredData['aor_mobile'])))
                                <div class="mb-3">
                                    <label for="" class="form-label">AOR OTP</label>
                                    <input name="impersonatedAorAuthenticationMobileOtp" maxlength="6" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" aria-label="Mobile OTP" class="form-control cus-form-ctrl uk-input uk-width uk-form-large uk-form-blank uk-text-bold uk-text-medium" style="border-top: 0.001rem #ccc dashed;" type="text" placeholder="Mobile OTP">
                                </div>
                            @endif
                            @if(!empty($session->get('impersonated_user_authentication_mobile_otp')) &&!empty($userEnteredData['yr_mobile']))
                                <div class="mb-3">
                                    <label for="" class="form-label">User OTP</label>
                                    <input name="impersonatedUserAuthenticationMobileOtp" maxlength="6" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" aria-label="Mobile OTP" class="form-control cus-form-ctrl uk-input uk-width uk-form-large uk-form-blank uk-text-bold uk-text-medium" style="border-top: 0.001rem #ccc dashed;" type="text" placeholder="Mobile OTP">
                                </div>
                            @endif
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 authenticatedByAor">
                            <div class="mb-3">
                                <label for="" class="form-label">Your Mobile No.</label>
                                <input type="text" name="yr_mobile" class="form-control cus-form-ctrl" id="yr_mobile" placeholder="Enter Your Mobile No." minlength="10" maxlength="10" pattern=".{10,10}" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" value="<?= (!empty($userEnteredData['yr_mobile']) ? $userEnteredData['yr_mobile'] : '') ?>">

                            </div>
                            
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 authenticatedByAor">
                            <div class="mb-3">
                                <label for="" class="form-label">Your Email</label>
                                <input type="email" name="you_email" class="form-control cus-form-ctrl" oninput="validateInput(event)" id="you_email" placeholder="Enter Your Email" maxlength="60" value="<?= (!empty($userEnteredData['you_email']) ? $userEnteredData['you_email'] : '') ?>">

                            </div>
                        </div>
                    </div>
                    @if(!empty($session->get('impersonated_user_authentication_mobile_otp'))&& (!empty($userEnteredData['aor_code']) || !empty($userEnteredData['aor_mobile'])))
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="captchaimage">
                                <div class="mb-3">
                                    @include('Captcha.Captcha_view')
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="mb-3">
                                <button class="btn quick-btn ">LOGIN</button>
                            </div>
                        </div>
                    </div>
                    </form>
                    <div class="regester-txts">
                        <h6 class="htsmall">Login As :</h6>
                        <div class="regester-links">
                            <a href="javascript:;" class="blue-txt aor-login">Authenticated by AOR</a>
                            <!--<a href="javascript:;" class="blue-txt apcil-login">Appearing Counsel</a>-->
                        </div>
                    </div>
                    <div class="regester-txts">
                        <h6 class="htsmall mt-2">Register As :</h6>
                        <div class="regester-links">
                            <a href="{{base_url('register')}}" class="blue-txt">Individual (Party In Person)</a>
                            <span class="gray-txt">Or</span>
                            <a href="{{base_url('register/AdvocateOnRecord')}}" class="blue-txt">AOR</a>
                            <span class="gray-txt">Or</span>
                            <a href="{{base_url('arguingCounselRegister')}}" class="blue-txt">Advocate/Appearing Counsel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Login Area End  -->
@endsection