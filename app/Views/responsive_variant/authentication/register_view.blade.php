@extends('layout.frontApp')
@section('content')
<?php
$segment = service('uri');
$session = service('session');
?>
<style>
    .regester-links {
        margin: 0px 100px;
    }

    label {
        color: black;
    }
</style>

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
                        <div class="banner-txts">
                            <?php
                            if ($segment->getSegment(2) == 'AdvocateOnRecord') {
                                $title = 'Advocate On Record';
                                $adv_type_select = USER_ADVOCATE;
                            } else {
                                $title = 'Party In Person';
                                $adv_type_select = USER_IN_PERSON;
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
                        <div class="alert alert-danger text-center flashmessage" role="alert">
                            <b>{{ $validation->getError('adv_mobile')}}</b>
                        </div>
                        @endif
                        @if(isset($validation) && !empty($validation->getError('adv_email')))
                        <div class="alert alert-danger text-center flashmessage" role="alert">
                            <b>{{ $validation->getError('adv_email')}}</b>
                        </div>
                        @endif
                        <div class="httxt">
                            <h4><?php echo $title; ?><strong> (Registration)</strong></h4>
                        </div>

                        <?php
                        //pr(stringDecreption($_SESSION['session_not_register_type_user']));
                        $attributes = array("class" => "form-horizontal", "id" => "loginform", "name" => "loginform", 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
                        echo form_open(base_url("register/DefaultController/adv_get_otp"), $attributes);
                        ?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="text" name="adv_type_select" style="display: none;" id="adv_type_select" value="<?php echo url_encryption($adv_type_select); ?>">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                    <label for="" class="form-label"></label>
                                    <!-- <input type="hidden" name="adv_type_select" id="adv_type_select" value="<?php //echo idEncryptionCustom($adv_type_select); ?>"> -->
                                    <!-- <a class=""> -->
                                    <label><input class="form-check-input" type="radio" name="not_register_type_user" id="new_advocate" onclick="HideEkycDiv('offline_proceed')" name="not_register_type_user" value="<?php echo stringEncreption('not_register_ekyc'); ?>" checked> Offline</label>
                                    <!-- </a> -->
                                    <!-- <a class=""> -->
                                    <label><input class="form-check-input" type="radio" name="not_register_type_user" id="ekyc" onclick="showHideDiv('ekyc_upload_share')" value="<?php echo stringEncreption('not_register_other'); ?>"> Paperless KYC </label>
                                    <!-- </a> -->
                                </div>
                            </div>
                        </div>
                        <br />
                        <input type="hidden" name="register_type" value="<?php echo $title; ?>">
                        <input type="hidden" name="ctvrg">
                        <?php echo $session->setFlashdata('msg'); ?>
                        <div id="ekyc_upload_share" style="display: none;">

                            <input hidden id="user_login_type" name="adv_search1" value="<?php echo idEncryptionCustom('new_advocate_register'); ?>">
                            <!--<input hidden id="user_login_type" name="adv_type1" value="<?php /*echo htmlentities(stringEncreption('1'), ENT_QUOTES); */ ?>">-->
                            <input type="hidden" id="user_login_type" name="adv_type1" value="<?php echo idEncryptionCustom($adv_type_select); ?>">
                            <p class="">
                                <a style="width: 90%;" class="btn quick-btn" target="_blank" href="https://resident.uidai.gov.in/offlineaadhaar">Visit to Download Offline Aadhaar Zip File</a>
                                <button type="button" class="btn btn-primary" data-toggle="tooltip" data-html="true" title="Enter ‘Aadhaar Number’ or ‘VID’ and mentioned ‘Security Code’ in screen, then click on ‘Send OTP’ or ‘Enter TOTP’. The OTP will be sent to the registered Mobile Number for the given Aadhaar number or VID. TOTP will be available on m-Aadhaar mobile Application of UIDAI. Enter the OTP received/TOTP. Enter a Share Code which be the password for the ZIP file and click on ‘Download’ button
                                        The Zip file containing the digitally signed XML will be downloaded to device wherein the above mentioned steps have been performed.">?</button>
                            </p>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Choose Offline Aadhaar Zip File (max size 5mb).</label>
                                        <input type="file"  accept=".zip"  class="form-control cus-form-ctrl" id="ekyc_zip_file" name="ekyc_zip_file">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label"></label>
                                        <input class="form-control cus-form-ctrl" type="password" name="share_code" id="share_code" placeholder="Share Code" minlength="4" maxlength="4">
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div id="offline_proceed">
                            <!-- <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label"></label>
                                        <input class="form-control cus-form-ctrl ekyc_adv" type="text" name="adv_mobile" id="adv_mobile" maxlength="10" minlength="10" placeholder="Mobile" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label"></label>
                                        <input class="form-control cus-form-ctrl ekyc_adv" type="text" name="adv_email" id="adv_email" placeholder="Email ID">
                                    </div>
                                </div>
                            </div> -->
                        
                            
                        </div>
                        <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label"></label>
                                        <input class="form-control cus-form-ctrl " required type="text" name="adv_mobile" id="adv_mobile" maxlength="10" minlength="10" placeholder="Mobile" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label"></label>
                                        <input class="form-control cus-form-ctrl " required type="text" name="adv_email" id="adv_email" placeholder="Email ID">
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
                            <button class="btn quick-btn" id="submit">SEND OTP</button>
                        </div>
                        <?php echo form_close(); ?>
                        <div class="regester-txts">
                        <center><a href="{{base_url()}}" class="col-sm-3 col-md-3 btn quick-btn">LOGIN</a></center>
                        <h6 class="htsmall">Register As :</h6>
                        <div class="regester-links">
                            <a href="{{base_url('register')}}" class="blue-txt">Individual (Party In Person)</a>
                            <span class="gray-txt">Or</span>
                            <a href="{{base_url('register/AdvocateOnRecord')}}" class="blue-txt"> AOR</a>
                            <!-- <span class="gray-txt">Or</span>
                                <a href="{{base_url('arguingCounselRegister')}}" class="blue-txt">Advocate</a> -->
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
    <script src="<?= base_url() . 'assets/newDesign/' ?>js/jquery351.min.js"></script>
    <script src="<?= base_url() . 'assets/newDesign/' ?>js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() . 'assets/newDesign/' ?>js/jquery.easy-ticker.min.js"></script>
    <script src="<?= base_url() . 'assets/newDesign/' ?>js/wow.min.js"></script>
    <script src="<?= base_url() . 'assets/newDesign/' ?>js/owl.carousel.js"></script>
    <script src="<?= base_url() . 'assets/newDesign/' ?>js/custom.js"></script>
    <script type="text/javascript">
        <?php if(isset($_SESSION['session_not_register_type_user']) && !empty($_SESSION['session_not_register_type_user'])){?>
        <?php   if(stringDecreption($_SESSION['session_not_register_type_user'])=='not_register_other'){ ?>
            showHideDiv('ekyc_upload_share');
        <?php }else{  ?>
                HideEkycDiv('offline_proceed');
        <?php } }?>
       // HideEkycDiv('offline_proceed');
       var ty = 1;
        function showHideDiv(ele) {
            var srcElement = document.getElementById(ele);
            var srcElement2 = document.getElementById('offline_proceed');
            if (srcElement != null) {
                if (srcElement.style.display == "block") {} else {
                    srcElement.style.display = 'block';
                    srcElement2.style.display = 'none';
                    document.getElementById("new_advocate").checked = false;
                    document.getElementById("ekyc").checked = true;
                    $('#ekyc_zip_file').attr('required',true);
                    $('#share_code').attr('required',true);
                 
                    ty = 2;
                    //window.scrollBy(0, 60);
                }
                return false;
            }
        }

        function HideEkycDiv(ele) {
            var srcElement = document.getElementById(ele);
            var srcElement2 = document.getElementById('ekyc_upload_share');
            if (srcElement != null) {
                if (srcElement.style.display == "block") {} else {
                    // alert(srcElement2);
                    srcElement.style.display = 'block';
                    srcElement2.style.display = 'none';
                    document.getElementById("ekyc").checked = false;
                    document.getElementById("new_advocate").checked = true;
                    $('#ekyc_zip_file').attr('required',false);
                    $('#share_code').attr('required',false); 
                    
                    ty = 1;
                    //window.scrollBy(0, 60);
                }
                return false;
            }
        }
        $(document).ready(function() {
            $(document).on('click', '#submit', function(event) {
                // event.preventDefault(); // Prevent default form submission
                // Check file size (optional)
                if(ty == 2){
                    const maxSizeInBytes = '<?=OFFLINE_AADHAAR_EKYC_ZIP_ALLOWABLE_FILE_SIZE; ?>';
                    const file = $('#ekyc_zip_file')[0].files[0];
                    // Check file type (optional)
                    const allowedTypes = ['application/zip', 'application/x-zip-compressed'];
                    // Check if a file has been selected
                    if ($('#ekyc_zip_file').val() === '') {
                        event.preventDefault();
                        alert('Please select a file.');
                        return;
                    }else if (file.size > maxSizeInBytes) {
                        event.preventDefault();
                        alert('File is too large. Maximum allowed size is 2 MB.');
                        return;
                    }else if (!allowedTypes.includes(file.type)) {
                        event.preventDefault();
                        alert('Invalid file type. Please select a ZIP file.');
                        return;
                    }else{
                        $('#loginform').submit();
                    }
                }else{
                    $('#loginform').submit();
                }
            });
        });
    </script>
