@extends('layout.frontApp')
@section('content')
<div class="login-area">
    <div class="container">
        <div class="row" id="default">
        <div class="col-12 col-sm-12 col-md-6 col-lg-7 login-banner">
                <div class="login-banner-inner">
                    <div class="banimg-sec">
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
                    <div class="httxt">
                        <h4>Registration Verification <span class="loginAs"></span></h4>
                    </div>
                    <?php
                    $session = session();
                    if($session->has('success')) {
                        ?>
                        <div class="alert alert-success">
                            <b>{{ esc($session->get('success')) }}</b>
                        </div>
                        <?php
                    }
                    if($session->has('error')) {
                        ?>
                        <div class="alert alert-danger">
                            <b>{{ esc($session->get('error')) }}</b>
                        </div>
                    <?php } ?>
                    <div class="loin-form">
                        <?php
                        $action = base_url("matchRegistrationCode");
                        $attribute = array('class' => 'form-horizontal form-label-left', 'id' => 'matchRegistrationCode', 'name' => 'matchRegistrationCode', 'autocomplete' => 'off');
                        echo form_open($action, $attribute);
                        ?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Registration Code : <span style="color: red">*</span></label>
                                        <input class="form-control cus-form-ctrl" type="text" name="registration_code" id="registration_code" placeholder="Registration Code" maxlength="10" tabindex="1" required/>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="mb-3">
                                        <input type="submit" name="verifyRegistrationCode" id="verifyRegistrationCode" value="Verify Registration Code" class="mt-3 btn quick-btn btn-primary">
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
@endsection
@push('script')
<script>
    $(document).ready(function(){
        $(document).on('click','#verifyRegistrationCode',function(e){
            e.preventDefault();
            var barRegPattern = /^[a-zA-Z0-9]+$/i;
            var validation = true;
            var registration_code =  $.trim($("#registration_code").val());
            if(registration_code == '') {
                $("#registration_code").focus();
                $("#error_registration_code").text("Please fill registration code.");
                $("#error_registration_code").css({'color':'red'});
                alert("Please fill registration code.");
                validation = false;
                return false;
            } else if(!barRegPattern.test(registration_code)) {
                $("#registration_code").focus();
                $("#error_registration_code").text("Please fill valid registration code.");
                $("#error_registration_code").css({'color':'red'});
                alert("Please fill valid registration code.");
                validation = false;
                return false;
            } else if(registration_code.length < 10) {
                $("#registration_code").focus();
                $("#error_registration_code").text("Please fill registration code of 10 characters.");
                $("#error_registration_code").css({'color':'red'});
                alert("Please fill registration code of 10 characters.");
                validation = false;
                return false;
            } else if(validation) {
                $("#matchRegistrationCode").submit();
            }
        });
    });
</script>
@endpush