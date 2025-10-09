<?php declare(strict_types=1); 
if(isset(session()->get('login')['impersonator_user']) && isset(session()->get('login')['impersonator_user']->is_active) && !empty(session()->get('login')['impersonator_user']) && session()->get('login')['impersonator_user']->is_active == 1){
if(!in_array($_SESSION['login']['ref_m_usertype_id'],array(ARGUING_COUNSEL,SR_ADVOCATE))){
    
        $extends = 'layout.advocateApp';
    
}else{
    $extends = 'layout.app';
}
}else{
        $extends = 'layout.ecopyApp';
    }
 ?>
@extends($extends)
@section('content')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-section dashboard-tiles-area"></div>
            <div class="dashboard-section">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="dash-card">
                            {{-- Page Title Start --}}
                            <?php if (session()->getFlashdata('msg')) : ?>
                                <div class="alert alert-dismissible text-center flashmessage">
                                    <div class="flas-msg-inner">
                                        <?= session()->getFlashdata('msg') ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="title-sec">
                                <h5 class="unerline-title">My Profile </h5>
                                <div class="end-buttons my-0">
                                    <a class="quick-btn gray-btn" href="<?= base_url(); ?>profile/updateProfile/pass">Change Password</a>
                                    <!-- <a href="javascript:void(0)" class="quick-btn pull-right mb-2" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                    <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                </div>
                            </div>
                            {{-- Page Title End --}}
                            {{-- Main Start --}}
                            <div class="profile-details">
                                <div class="row mt-4">
                                    
                                    <div class="col-sm-12 col-md-12 col-lg-12 mb-3">
                                        <div class="col-md-7 col-xs-6">
                                            <h2> Profile (
                                                <?php
                                                $user_type = '';
                                                if (!empty($profile)) {
                                                    if ($profile->ref_m_usertype_id == USER_ADVOCATE) {
                                                        $user_type = 'Advocate';
                                                    } elseif ($profile->ref_m_usertype_id == USER_IN_PERSON) {
                                                        $user_type = 'Party in Person';
                                                    } elseif ($profile->ref_m_usertype_id == SR_ADVOCATE) {
                                                        $user_type = 'SR Advocate';
                                                    } elseif ($profile->ref_m_usertype_id == USER_ADMIN) {
                                                        $user_type = 'User Admin';
                                                    } elseif ($profile->ref_m_usertype_id == USER_EFILING_ADMIN) {
                                                        $user_type = 'Filing Admin';
                                                    } elseif ($profile->ref_m_usertype_id == USER_SUPER_ADMIN) {
                                                        $user_type = 'Super Admin';
                                                    } elseif ($profile->ref_m_usertype_id == AMICUS_CURIAE_USER) {
                                                        $user_type = 'Super Admin';
                                                    } elseif ($profile->ref_m_usertype_id == USER_CLERK) {
                                                        $user_type = 'User Clerk';
                                                    } elseif ($profile->ref_m_usertype_id == ARGUING_COUNSEL) {
                                                        $user_type = 'Arguing Counsel';
                                                    }
                                                    echo htmlentities($user_type, ENT_QUOTES);
                                                }
                                                ?>
                                                )
                                            </h2>
                                        </div>
                                    </div>
                                    <?php 
                                    if((getSessionData('login')['ref_m_usertype_id'] == 1) || (getSessionData('login')['ref_m_usertype_id'] == 2)) { echo ''; } else{ // pr($profile); ?>                                     
                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                            <div class="profile-photo-view-pg">
                                                <img class="img-thumbnail img-responsive" src="<?php echo empty($profile->photo_path) ? base_url('assets/newAdmin/images/profile-img.png') : base_url($profile->photo_path); ?>" >
                                                <div> 
                                                    <a class="" id="show_profile_pic"  maxlength="1">Change Profile Photo</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="col-12 col-sm-12 col-md-10 col-lg-10">
                                        <div class="usrprofile-details">
                                            <div class="row">
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                    <div class="row">
                                                        <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                            <label class="prof-label">Name :</label>
                                                        </div>
                                                        <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                            <div class="prof-outp">
                                                            <?php if(getSessionData('login')['aor_code'] == 10017){  
                                                                echo $profile->first_name   ;
                                                                  } else {  
                                                               echo strtoupper($profile->first_name) ;

                                                                } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                    <div class="row">
                                                        <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                            <label class="prof-label">Bar Reg No :</label>
                                                        </div>
                                                        <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                            <div class="prof-outp">
                                                                <?= $profile->bar_reg_no ?> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if((getSessionData('login')['ref_m_usertype_id'] == 1) || (getSessionData('login')['ref_m_usertype_id'] == 2)) { ?>
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                        <div class="row">
                                                            <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                                <label class="prof-label">AOR Code :</label>
                                                            </div>
                                                            <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                                <div class="prof-outp">
                                                                    <?php
                                                                    if ($profile->aor_code == 'NULL' || $profile->aor_code == '0' || $profile->aor_code == '') {
                                                                        echo '';
                                                                    } else {
                                                                        echo htmlentities($profile->aor_code, ENT_QUOTES);
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                        <div class="row">
                                                            <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                                <label class="prof-label">Enroll. Date :</label>
                                                            </div>
                                                            <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                                <div class="prof-outp">
                                                                    <?php
                                                                    // if ($profile['enroll_date'] !=null && !empty($profile->enroll_date) && $profile->enroll_date!='0000-00-00') {
                                                                    //     echo ucwords(htmlentities(date("d-m-Y", strtotime($profile->enroll_date)), ENT_QUOTES));
                                                                    // } else {
                                                                    //     echo 'N/A';
                                                                    // }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                    <div class="row">
                                                        <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                            <label class="prof-label">Gender :</label>
                                                        </div>
                                                        <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                            <div class="prof-outp">
                                                                <?php
                                                                $gender = '';
                                                                if ($profile->gender == 1 || $profile->gender == 'M') {
                                                                    $gender = 'Male';
                                                                } elseif ($profile->gender == 2 || $profile->gender == 'F') {
                                                                    $gender = 'Female';
                                                                } elseif ($profile->gender == 3 || $profile->gender == 'O') {
                                                                    $gender = 'Other';
                                                                }
                                                                echo $gender;
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                    <div class="row">
                                                        <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                            <?php if((getSessionData('login')['ref_m_usertype_id'] == 1) || (getSessionData('login')['ref_m_usertype_id'] == 2)) { ?>
                                                                <label class="prof-label">Permanent Address :</label>
                                                            <?php } else{ ?>
                                                                <label class="prof-label">Residential Address :</label>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                            <div class="prof-outp">
                                                                <?php echo htmlentities((string)$profile->m_address1, ENT_QUOTES); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-21 col-sm-12 col-md-6 col-lg-6">
                                                    <div class="row">
                                                        <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                            <label class="prof-label">Date of Birth :</label>
                                                        </div>
                                                        <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                            <div class="prof-outp">
                                                                <?php
                                                                if ($profile->dob != null && !empty($profile->dob) && $profile->dob != '0000-00-00') {
                                                                    $profiledob = str_replace('/', '-', $profile->dob);
                                                                    $date = new DateTime($profiledob);
                                                                    echo $date_of_birth = $date->format('d-m-Y');
                                                                } else {
                                                                    echo 'N/A';
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                    <div class="row">
                                                        <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                            <?php if((getSessionData('login')['ref_m_usertype_id'] == 1) || (getSessionData('login')['ref_m_usertype_id'] == 2)) { ?>
                                                                <label class="prof-label">Chamber Address :</label>
                                                            <?php } else{ ?>
                                                                <label class="prof-label">Office Address :</label>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                            <div class="prof-outp">
                                                                <?php echo htmlentities((string)$profile->m_address2, ENT_QUOTES); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                    <div class="row">
                                                        <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                            <label class="prof-label">Mobile No. :</label>
                                                        </div>
                                                        <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                            <div class="prof-outp">
                                                                <?php
                                                                if ($profile->moblie_number == '') {
                                                                    $moblie_number = '<span style="color:red;">' . htmlentities('Update Your Mobile No.', ENT_QUOTES) . '</span>';
                                                                } else {
                                                                    $moblie_number = htmlentities($profile->moblie_number, ENT_QUOTES);
                                                                }
                                                                echo $moblie_number;
                                                                if((getSessionData('login')['ref_m_usertype_id'] == 1) || (getSessionData('login')['ref_m_usertype_id'] == 2)) { 
                                                                    echo '';
                                                                } else{
                                                                    ?>
                                                                    <a class="edit-pen-btn" href="<?= base_url('profile/updateProfile/contact'); ?>"><i class="fa fa-edit"></i></a>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                    <div class="row">
                                                        <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                            <label class="prof-label">Email :</label>
                                                        </div>
                                                        <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                            <div class="prof-outp">
                                                                <?php
                                                                if (strpos($profile->emailid, '@')) {
                                                                    $emailid = htmlentities($profile->emailid, ENT_QUOTES);
                                                                } else {
                                                                    $emailid = '<span style="color:red;">' . htmlentities('Update Your Email ID ', ENT_QUOTES) . '</span>';
                                                                }
                                                                echo $emailid;
                                                                if((getSessionData('login')['ref_m_usertype_id'] == 1) || (getSessionData('login')['ref_m_usertype_id'] == 2)) { 
                                                                    echo '';
                                                                } else{
                                                                    ?> 
                                                                    <a class="edit-pen-btn" href="<?= base_url('profile/updateProfile/email'); ?>"><i class="fa fa-edit"></i></a>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="profile_pic" style="display: none;">
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <div style="color:#f3441c" class=""> 
                                            <center><p>NOTE : Please upload only JPG or JPEG. File name maximun length can be 40 characters including digits characters, spaces, hypens and underscore. maximum file size 1MB.</p></center>
                                        </div>
                                        <?php
                                        $attribute = array('class' => 'form-horizontal form-label-left', 'name' => 'advocate_login_uploads', 'id' => 'advocate_login_uploads', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
                                        echo form_open('profile/DefaultController/uploadPhoto', $attribute);
                                        ?>
                                            <div class="center-buttons">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <div class="input-group">   
                                                            <input id="advocate_image" accept="image/*" required name="advocate_image" placeholder="Profile Image" maxlength="50" class="form-control cus-form-ctrl" type="file">
                                                            <span style="color: red;"><?php // echo form_error('advocate_image'); ?></span>
                                                        </div> 
                                                    </div>
                                                </div>  
                                                <!-- <input class="quick-btn" name="profile_pic_upload_id" type="submit" value="UPDATE PHOTO"> -->
                                                
                                            </div>
                                            <div class="center-buttons">
                                                <button class="quick-btn" name="profile_pic_upload_id" type="submit">UPDATE PHOTO</button>
                                                <a class="quick-btn gray-btn" id="profile_pic_hide">CANCEL</a>
                                            </div>
                                        <?php echo form_close(); ?>                            
                                    </div>
                                    <?php
                                    if (isset($login_multi_account) && !empty($login_multi_account)){
                                    $attribute = array('class' => 'form_horizontal', 'name' => 'form_horizontal', 'id' => 'login-form','accept-charset'=>'utf-8', 'autocomplete' => 'off' ,'onsubmit'=>'enableSubmit();');
                                    echo form_open(base_url('profile/DefaultController/verify'), $attribute); ?>
                                    <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid uk-text-center" style="margin-left: 5%;">
                                        <b>I'd want to sign in to the roll of : </b>
                                        <?php
                                        foreach ($login_multi_account as $row){
                                        $checked = ($this->session->userdata['login']['ref_m_usertype_id'] == $row->ref_m_usertype_id) ? "checked" : '';
                                        ?>
                                        <label><input class="uk-radio" type="radio" name="adv_type_select" id="adv_type_select" value="<?php echo htmlentities(url_encryption($row->ref_m_usertype_id), ENT_QUOTES); ?>" <?=$checked;?>> <?=$row->user_type;?></label>
                                        <?php  } ?>
                                        <button type="submit" class="btn-primary" style="height:3.2rem;border-radius:9px;"><b>Switch account</b></button>
                                    </div>

                                    <input type="hidden" name="ctvrg">
                                    <?= form_close() ?>
                                    <?php  } ?>

                                </div>                                
                            </div>
                            {{-- Main End --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<script>
    function switchAccount(){
        var adv_type_select= $("input[type='radio'][name='adv_type_select']:checked").val();
        if (adv_type_select){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>profile/DefaultController",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,adv_type_select:adv_type_select},
                async: false,
                success: function (data) {
                    var targetUrl =resArr[1]; "<?php echo base_url('profile/DefaultController'); ?>";
                    window.parent.location.href=targetUrl;
                    //location.reload();
                    $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function () {
                    $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        }
    }
</script>
<script>
    $(document).ready(function() {
        $("#hide").click(function() {
            $("#update_new_adv_id").hide();
            $("#updating").show();
        });
        $("#reset").click(function() {
            $('#select2-hc_name-container').text('Select');
            $('#select2-hc_name-container').attr('title', 'Select');
            $('#hc_name').prop('selectedIndex', 0);
            $('#estab_list').val('');
            $('#select2-estab_list-container').text('Select');
            $('#select2-estab_list-container').attr('title', 'Select');
            $('#select2-st_id-container').text('Select State');
            $('#select2-st_id-container').attr('title', 'Select State');
            $('#st_id').prop('selectedIndex', 0);
            $('#select2-dt_district-container').text('Select District');
            $('#select2-dt_district-container').attr('title', 'Select District');
            $('#dt_district').prop('selectedIndex', 0);
            $('#hc_div_hide').show();
            $('#dt_div_hide').hide();
        });
        $("#show").click(function() {
            $("#update_new_adv_id").show();
            $("#updating").hide();
        });
        $("#show_profile_pic").click(function() {
            $("#profile_pic").show();
            $("#updating").hide();
        });
        $("#profile_pic_hide").click(function() {
            $("#profile_pic").hide();
            $("#updating").show();
        });
    });
</script>