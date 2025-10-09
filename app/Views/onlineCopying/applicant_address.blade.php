<?php
if(isset(session()->get('login')['impersonator_user']) && isset(session()->get('login')['impersonator_user']->is_active) && !empty(session()->get('login')['impersonator_user']) && session()->get('login')['impersonator_user']->is_active == 1){
    $extends = 'layout.advocateApp';
}else{
    $extends = 'layout.ecopyApp';
} ?>
@extends($extends)
@section('content')


<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet"> 
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>  
<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>assets/js/popper.min.js"></script>
<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/font-awesome.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/animate.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/material.css" rel="stylesheet" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/style.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?= base_url() . 'assets/newAdmin/' ?>css/jquery.dataTables.min.css">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/fullcalendar.css" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery-ui.css">
<link href="<?= base_url() . 'assets' ?>/css/select2.min.css" rel="stylesheet">



<style>
    .order-card { color: #fff; }
    .applicant_dashboard{ cursor: pointer; }
    .bg-c-blue { background: linear-gradient(45deg,#4099ff,#73b4ff);}
    .bg-c-green { background: linear-gradient(45deg,#2ed8b6,#59e0c5); }
    .bg-c-yellow { background: linear-gradient(45deg,#FFB64D,#ffcb80); }
    .bg-c-pink { background: linear-gradient(45deg,#FF5370,#ff869a); }
    .card { border-radius: 5px; -webkit-box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16); box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16); border: none;margin-bottom: 5px; -webkit-transition: all 0.3s ease-in-out;transition: all 0.3s ease-in-out; }
    .card .card-block { padding: 20px; }
    .order-card i { font-size: 26px; }
    .f-left { float: left; } 
    .f-right { float: right; } 
    input, select, textarea { text-transform: uppercase; }
</style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="dash-card dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class=" dashboard-bradcrumb">
                                <div class="left-dash-breadcrumb">
                                    <div class="page-title">
                                        <h5><i class="fa fa-file"></i> Online Certified Copy : Address</h5>
                                    </div>
                                    <div class="form-response" id="msg" role="alert" data-auto-dismiss="5000"></div>
                                </div>
                                <div class="ryt-dash-breadcrumb">
                                    <div class="btns-sec">
                                        <!-- <a href="javascript:void(0)" class="quick-btn pull-right" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                        <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                               
                </div>
                <div class="dash-card dashboard-section">
                    <div class="row">
                        <div class="panel panel-default">
                           
                        
                            <div style="text-align: left;">
                                <?php
                                if (!empty(getSessionData('MSG'))) {
                                    echo getSessionData('MSG');
                                }
                                if (!empty(getSessionData('msg'))) {
                                    echo getSessionData('msg');
                                }
                                ?>
                                <br>
                            </div>
                            <br>
                            <div id="">
                                <body class="bg03" oncontextmenu="return false;">
                                    <!-- <div class="container-fluid">         -->
                                        <form  method="post" action="<?= base_url('online_copying/applicant_address'); ?>">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div class="row tm-content-row tm-mt-big mt-2">
                                                <div class="tm-col tm-col-big">
                                                    <div class="bg-white tm-block">  
                                                        <div class="card mt-0">
                                                            <div class="card-header bg-primary text-white font-weight-bolder mt-0 mb-0">            
                                                                Applicant Address
                                                            </div>
                                                                <div class="card-body">
                                                                    <div class="row m-1">
                                                                        <div class="alert alert-warning alert-dismissible">
                                                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                                                            <strong>Unless there is request for Certified Copy/Digital Copy, your identity as claimed will not be verified.</strong>
                                                                        </div>
                                                                    </div>
                                                                    <!--HERE ALREADY ADDED ADDRESSES SHOWN WITH REMOVE EDIT BUTTON -->
                                                                    <div class="row">
                                                                        <?php
                                                                        if (!empty($userAddress)) 
                                                                        {
                                                                            $_SESSION['is_user_address_found'] = 'YES';
                                                                            foreach  ($userAddress  as $row) 
                                                                            {
                                                                                ?>
                                                                                <div class="col-md-4 p-2">
                                                                                    <div class="col-md-12 shadow p-3 mb-3 bg-white rounded">
                                                                                        <div class="card">
                                                                                            <div class="card-header bg-white pb-1 pt-1">
                                                                                                <div class="row pl-1">
                                                                                                    <div class=" col-6 text-left d-inline font-weight-bold text-black">
                                                                                                        <?// =$row['first_name']." ".$row['second_name'];?>
                                                                                                        <?=$row['first_name'];?>
                                                                                                    </div>
                                                                                                    <div class="col-6 text-right d-inline font-weight-bold text-black">
                                                                                                        <?=$row['address_type']?>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="card-body pb-1 pt-1">
                                                                                                <p>
                                                                                                    <?=$row['address'].", ".$row['city'].", "?>
                                                                                                    <br>
                                                                                                    <?=$row['district'].", ".$row['state']?>
                                                                                                    <br>
                                                                                                    <?=$row['pincode'].", ".$row['country']?>
                                                                                                </p>
                                                                                                <div class="row pl-1">
                                                                                                    <div class=" col-6 text-left d-inline">
                                                                                                        <a href="#" style="color: #fff;" class=" btn btn-danger btn_remove_address" data-address-id="<?=$row['id']?>">Remove</a>
                                                                                                        <!--<input type="button" class="btn btn-primary btn_remove_address" value="Remove" />-->
                                                                                                    </div>
                                                                                                    <div class="col-6 text-right d-inline">
                                                                                                        <!-- <a href="#" style="color: #fff;" class="quick-btn btn_edit_address" data-address-id="<?// =$row['id']?>" data-first_name="<?// =$row['first_name']?>" data-second_name="<?// =$row['second_name']?>" data-address="<?// =$row['address']?>" data-city="<?// =$row['city']?>"data-district="<?// =$row['district']?>" data-state="<?// =$row['state']?>" data-pincode="<?// =$row['pincode']?>" data-country="<?// =$row['country']?>" data-address_type="<?// =$row['address_type']?>">Edit</a> -->
                                                                                                        <a href="#" style="color: #fff;" class="quick-btn btn_edit_address" data-address-id="<?=$row['id']?>" data-first_name="<?=$row['first_name']?>" data-address="<?=$row['address']?>" data-city="<?=$row['city']?>"data-district="<?=$row['district']?>" data-state="<?=$row['state']?>" data-pincode="<?=$row['pincode']?>" data-country="<?=$row['country']?>" data-address_type="<?=$row['address_type']?>">Edit</a>
                                                                                                        <!--                                        <input type="button" class="btn btn-primary btn_edit_address" value="Edit" />-->
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>

                                                                    </div>
                                                                    <!--HERE ADD ADDRESS : ONE ADDRESS TO INCLUDE IS MANDATORY : SHOW ADD NEW ADDRESS BUTTON IF Required, max 3 ADDRESS LIMIT -->
                                                                    <?php
                                                                    if(!empty($userAddress) && (count($userAddress)== 1 OR count($userAddress) == 2))
                                                                    {
                                                                        ?>
                                                                        
                                                                        <div class="row mb-4">
                                                                            <div class="text-right d-inline">
                                                                                <button type="submit" name="add_new_address" class="btn quick-btn" value="Add New Address" >Add New Address</button>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    if(empty($userAddress)) 
                                                                    {
                                                                        if( is_array($userAdharVerify) && !empty($userAdharVerify)) 
                                                                        {
                                                                            foreach($userAdharVerify as $av) {
                                                                                $data_verifed_name = str_replace('.', ' ', $av['user_name']);
                                                                                $data_verify_name_explode = explode(" ", $data_verifed_name);
                                                                                $data_verify_first_name = trim($data_verify_name_explode[0]);
                                                                                // $data_verify_second_name = trim(implode(' ', array_slice(explode(" ", $data_verifed_name), 1)));
                                                                                $data_verifed_address = $data_verifed['address'];
                                                                                $data_verifed_city_name = str_replace('.', ' ', $av['city']);
                                                                                $data_verifed_state_name = str_replace('.', ' ', $av['state']);
                                                                                $data_verifed_pin = $av['pincode'];
                                                                                $data_verifed_district = $av['district'];
                                                                            }        

                                                                        }
                                                                        else
                                                                        {
                                                                            if ( isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 1)
                                                                            {
                                                                                if(!empty($userBarAddress)){
                                                                                if(count($userBarAddress)>0) {
                                                                                    foreach($userBarAddress as $bar) {  
                                                                                    }
                                                                                }
                                                                                }
                                                                            }
                                                                        }  
                                                                    }
                                                                    if(empty($userAddress) OR isset($_POST['add_new_address']))
                                                                    { 
                                                                        $uadd = (isset($_SESSION['login']['impersonator_user']) && !empty($_SESSION['login']['impersonator_user'])) ? $_SESSION['login']['impersonator_user'] : '';
                                                                        ?>
                                                                        <div class="row mob-p-sm-0  p-3 mb-3">
                                                                            <div class="row">
                                                                                <!-- <div class="row"> -->
                                                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                                                        <div class="form-group">
                                                                                            <label class="form-label col-lg-3 col-md-3 col-sm-3 col-xs-12 px-0">Address Type <span style="color: red" class="astriks">*</span></label>
                                                                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 px-0">
                                                                                                <label class="radio-inline text-black ms-0 mob-p-sm-0 ps-0 ">
                                                                                                    <input type="radio" name="rdbtn_select" id="home_address_type" value="Home" <?= isset($data_verify_address_type) && $data_verify_address_type == 'Home' ? 'checked' : '' ?>> Home
                                                                                                </label>
                                                                                                <label class="ml-2 radio-inline mob-p-sm-0">
                                                                                                    <input type="radio" name="rdbtn_select" id="work_address_type" value="Work" <?=isset($data_verify_address_type) &&  $data_verify_address_type == 'Home' ? 'checked' : '' ?>> Work
                                                                                                </label>
                                                                                                <label class="ml-2 radio-inline mob-p-sm-0">
                                                                                                    <input type="radio" name="rdbtn_select" id="other_address_type" value="Other" <?=isset($data_verify_address_type) &&  $data_verify_address_type == 'Home' ? 'checked' : '' ?>> Other
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                <!-- </div> -->
                                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                                                                    <div class="mb-3">
                                                                                        <label for="" class="form-label">Full Name <span style="color: red" class="astriks">*</span></label>
                                                                                        <input type="text" class="form-control cus-form-ctrl" 
                                                                                        value="<?php echo (isset($data_verify_first_name)?$data_verify_first_name:((isset($uadd) && !empty($uadd) && isset($uadd->first_name)) ? $uadd->first_name : '')); ?>" id="applicant_first_name" aria-labelledby="applicant_first_name_addon"  required <?php if(getSessionData('login')['ref_m_usertype_id'] == 1) { echo 'disabled'; } ?>>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                                                                    <div class="mb-3">
                                                                                        <label for="" class="form-label">Second Name <span style="color: red" class="astriks">*</span></label>
                                                                                        <input type="text" class="form-control cus-form-ctrl" value="<?php // echo (isset($data_verify_second_name)?$data_verify_second_name:((isset($uadd) && !empty($uadd) && isset($uadd->last_name)) ? $uadd->last_name : '')); ?>" id="applicant_second_name" aria-labelledby="applicant_second_name" required <?php // if(getSessionData('login')['ref_m_usertype_id'] == 1) { echo 'disabled'; } ?>>
                                                                                    </div>
                                                                                </div> -->
                                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                                                                    <div class="mb-3">
                                                                                        <label for="pincode" class="form-label">Pincode <span style="color: red" class="astriks">*</span></label>
                                                                                        <input type="text" class="form-control cus-form-ctrl" value="<?php echo (isset($data_verifed_pin)?$data_verifed_pin:((isset($uadd) && !empty($uadd) && isset($uadd->m_pincode)) ? $uadd->m_pincode : '')); ?>" id="pincode" maxlength='6' required>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <span id="subcatLoadData"></span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                                                                    <?php 
                                                                                    $adr = '';
                                                                                    if(isset($data_verifed_address)){
                                                                                        $adr = $data_verifed_address;
                                                                                    }else{
                                                                                        if((isset($uadd) && !empty($uadd) && isset($uadd->m_address1))){ 
                                                                                            $adr = $uadd->m_address1;
                                                                                        }else if((isset($uadd) && !empty($uadd) && isset($uadd->m_address2))){ 
                                                                                            $adr = $uadd->m_address2;
                                                                                        }else if((isset($uadd) && !empty($uadd) && isset($uadd->m_address1) && isset($uadd->m_address2))){ 
                                                                                            $adr = $uadd->m_address1.' '.$uadd->m_address2;
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                    <div class="mb-3">
                                                                                        <label for="" class="form-label">Address <span style="color: red" class="astriks">*</span></label>
                                                                                        <input type="text" class="form-control cus-form-ctrl" value="<?=$adr; ?>" id="postal_add" aria-labelledby="postal_add_addon"  required>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <span id="subcatLoadData"></span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                                                                    <div class="mb-3">
                                                                                        <label for="" class="form-label">City <span style="color: red" class="astriks">*</span></label>
                                                                                        <input type="text" class="form-control cus-form-ctrl" value="<?php echo (isset($data_verifed_city_name)?$data_verifed_city_name:((isset($uadd) && !empty($uadd) && isset($uadd->m_city)) ? $uadd->m_city : '')); ?>" id="applicant_city" aria-labelledby="applicant_city_addon"  required>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <span id="subcatLoadData"></span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                                                                    <?php 
                                                                                    $dt = '';
                                                                                    if(isset($data_verifed_district)){
                                                                                        $dt = $data_verifed_district;
                                                                                    }else if((isset($uadd) && !empty($uadd) && isset($uadd->m_district_id) && !empty($uadd->m_district_id))){
                                                                                        $st = !empty($uadd->m_district_id) ? getStateName($uadd->m_district_id) : '';
                                                                                    }
                                                                                    ?>
                                                                                    <div class="mb-3">
                                                                                        <label for="" class="form-label">District <span style="color: red" class="astriks">*</span></label>
                                                                                        <input type="text" class="form-control cus-form-ctrl" value="<?= $dt; ?>" id="applicant_district" aria-labelledby="applicant_district_addon"  required>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <span id="subcatLoadData"></span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                                                                    <?php 
                                                                                    $st = '';
                                                                                    if(isset($data_verifed_state_name)){
                                                                                        $st = $data_verifed_state_name;
                                                                                    }else if((isset($uadd) && !empty($uadd) && isset($uadd->m_state_id) && !empty($uadd->m_state_id))){
                                                                                        $st = !empty($uadd->m_state_id) ? getStateName($uadd->m_state_id) : '';
                                                                                    }
                                                                                    ?>
                                                                                    <div class="mb-3">
                                                                                        <label for="" class="form-label">State <span style="color: red" class="astriks">*</span></label>
                                                                                        <input type="text" class="form-control cus-form-ctrl" value="<?=$st; ?>" id="applicant_state" aria-labelledby="applicant_state_addon" required>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <span id="subcatLoadData"></span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                                                                    <div class="mb-3">
                                                                                        <label for="" class="form-label">Country <span style="color: red" class="astriks">*</span></label>
                                                                                        <input type="text" class="form-control cus-form-ctrl"  id="applicant_country" aria-labelledby="applicant_country_addon" value="India" required>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <span id="subcatLoadData"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>  
                                                                            <div class="col-md-12 form-row tm-btn-right">
                                                                            <button type="button" name="btn_save" id="btn_save" value="Save Address" data-button_action_type="save" class="quick-btn" >Save Address </button>
                                                                            </div>

                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <div id="result" class="show_msg" style="overflow: auto;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                 
                                        <div class="modal fade" id="modal-lg">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content myModal_content">

                                                </div>
                                            </div>
                                        </div>       
                                        <br>
                                        <div id="result"></div>
                                    <!-- </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')

<script src="<?=base_url();?>assets/js/sweetalert2@11.js"></script>
<link rel="stylesheet" href="<?=base_url();?>assets/css/sweetalert2.min.css"> 
<script>
    $(document).on('keyup','#pincode',function(){
        var pincode = $(this).val();
        if(pincode.length == 6){
            $.ajax({
                url: "<?php echo base_url('online_copying/get_pincode_details'); ?>",
                cache: false,
                async: true,
                data: {pincode:pincode},
                type: 'POST',
                dataType: "json",
                success: function(data, status) {
                    if(data.status == 'success'){
                        $('#applicant_city').val(data.taluk_name);
                        $('#applicant_district').val(data.district_name);
                        $('#applicant_state').val(data.state_name);
                    }
                    else{
                        $('#applicant_city, #applicant_district, #applicant_state').val("");

                    }
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }

            });
        }
    });
    // user_address_save
    $(document).on('keyup','#modal_pincode',function(){
        var pincode = $(this).val();
        if(pincode.length == 6)
        {
            $.ajax({
                url: "<?php echo base_url('online_copying/get_pincode_details'); ?>",
                cache: false,
                async: true,
                data: {pincode:pincode},                            
                type: 'POST',
                dataType: "json",
                success: function(data, status) {                                
                    if(data.status == 'success'){
                        $('#modal_applicant_city').val(data.taluk_name);
                        $('#modal_applicant_district').val(data.district_name);
                        $('#modal_applicant_state').val(data.state_name);
                    }
                    else{
                        $('#modal_applicant_city, #modal_applicant_district, #modal_applicant_state').val("");
                    }
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });   
        }
    });


    $(document).on('click', '.btn_edit_address', function() {
        $('#modal-lg').modal('show');
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $(".modal-content").html("");
        // $("#modal-lg").modal({backdrop: false});    
        $(".myModal_content").html("");
        var address_id = $(this).data("address-id");
        var first_name = $(this).data("first_name");
        // var second_name = $(this).data("second_name");
        var address = $(this).data("address");
        var city = $(this).data("city");
        var district = $(this).data("district");
        var state = $(this).data("state");
        var pincode = $(this).data("pincode");
        var country = $(this).data("country");
        var address_type = $(this).data("address_type");
        // $('#modal-lg').modal('show');
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('online_copying/user_address_edit'); ?>",
            cache: false,
            data: {
                address_id: address_id,
                address_type: address_type,
                first_name: first_name,
                // second_name: second_name,
                address: address,
                city: city,
                district: district,
                state: state,
                pincode: pincode,
                country: country
            },
            beforeSend: function () {
                $('.myModal_content').html('<table width="100%" align="center"><tr><td>Loading...</td></tr></table>');
            },
            success: function(data) {
                $(".myModal_content").html(data);
                
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ', status, error);
                $('.myModal_content').html('<table width="100%" align="center"><tr><td>Error loading data</td></tr></table>');
            }
        });
    });
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    $(document).on('click','#btn_save',function(){        
        
        url = '<?php echo base_url('online_copying/user_address_save'); ?>';     
        var address_type = '';
        if ($("#home_address_type").is(':checked')) {
            address_type = 'Home';
        }
        else if ($("#work_address_type").is(':checked')) {
            address_type = 'Work';
        }
        else{
            address_type = 'Other';
        }
        $(".validation").remove();
        var first_name = $("#applicant_first_name").val();
        // var second_name = $("#applicant_second_name").val();
        var postal_add = $("#postal_add").val();
        var city = $("#applicant_city").val();
        var district = $("#applicant_district").val();
        var state = $("#applicant_state").val();
        var country = $("#applicant_country").val();
        var pincode = $("#pincode").val();
        // Initializing Variables With Regular Expressions
        var name_regex = /^[a-zA-Z0-9\u00C0-\u017F\s\/,.&'@\-#]+$/;
        var city_regex = /^[a-zA-Z ]+$/;
        var email_regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/; // /^[w-.+]+@[a-zA-Z0-9.-]+.[a-zA-z0-9]{2,4}$/;
        var add_regex = /^[a-zA-Z0-9\s,.:'-/]{3,}$/;   //   /^[0-9a-zA-Z ]+$/;
        var zip_regex = /^[0-9]{6,}$/;
        
        if (!($("#home_address_type").is(':checked') || $("#work_address_type").is(':checked') || $("#other_address_type").is(':checked'))) {
            $("#other_address_type").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Address Type Required*</strong></div>');            
            return false;                                                        
        } else if (first_name.match(name_regex) == null || first_name.length == 0) {
            $("#applicant_first_name").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Full Name Required.*</strong></div>');            
            $("#applicant_first_name").focus(); return false;                                                        
        }
        // else if (second_name.match(name_regex) == null || second_name.length == 0) {
        //     $("#applicant_second_name").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Second Name Required. Use alphabets only.*</strong></div>');            
        //     $("#applicant_second_name").focus(); return false;                                           
        // }
        else if (pincode.match(zip_regex) == null || pincode.length != 6) {
            $("#pincode").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Valid Pincode Required*</strong></div>');
            $("#pincode").focus(); return false;
        } else if (postal_add.match(add_regex) == null || postal_add.length == 0) {
            $("#postal_add").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Address Required. Do not use Special Symbols</strong></div>');            
            $("#postal_add").focus(); return false;                                               
        } else if (city.match(city_regex) == null || city.length == 0) {
            $("#applicant_city").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>City Name Required*</strong></div>');            
            $("#applicant_city").focus(); return false;                                                        
        } else if (district.match(city_regex) == null || district.length == 0) {
            $("#applicant_district").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>District Name Required*</strong></div>');            
            $("#applicant_district").focus(); return false;                                                                             
        } else if (state.match(city_regex) == null || state.length == 0) {
            $("#applicant_state").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>State Name Required*</strong></div>');            
            $("#applicant_state").focus(); return false;                                                
        } else if (country.match(city_regex) == null || country.length == 0) {
            $("#applicant_country").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Country Name Required*</strong></div>');            
            $("#applicant_country").focus(); return false;                                                
        } else {
            $.ajax({
                type: 'POST',
                url: url,
                dataType: "json",
                cache: false,
                beforeSend: function () {
                    $('.show_msg').html('<table widht="100%" align="center"><tr><td>Loading...</td></tr></table>');
                },
                data:{
                    first_name: first_name,
                    // second_name: second_name,
                    address_type: address_type,
                    postal_add: postal_add,
                    city: city,
                    district: district,
                    state: state,
                    country: country,
                    pincode: pincode
                },                              
                success: function(data)
                {
                    $('.show_msg').html('');
                    if(data.status == 'success'){
                        Swal.fire({
                            title: 'SUCCESS!',
                            text: 'Successfully Saved',
                            icon: 'success',
                            confirmButtonText: 'Next'
                        }).then(function () {
                            window.location.href = '<?= base_url('online_copying/applicant_address'); ?>';
                        });
                    } else {
                        $('.show_msg').html('<div class="alert alert-danger alert-dismissible"><strong>'+data.status+'</strong></div>');
                    }
                }
            });
        }
    });
    $(document).on('click','#btn_address_edit_action',function(){
        url = '<?php echo base_url('online_copying/user_address_update'); ?>';  
        var address_type = '';
        if ($("#modal_home_address_type").is(':checked')) {
            address_type = 'Home';
        } else if ($("#modal_work_address_type").is(':checked')) {
            address_type = 'Work';
        } else {
            address_type = 'Other';
        }
        $(".validation").remove();
        var address_id = $(this).data("address_table_id");
        var first_name = $("#modal_applicant_first_name").val();
        // var second_name = $("#modal_applicant_second_name").val();
        var postal_add = $("#modal_postal_add").val();
        var city = $("#modal_applicant_city").val();
        var district = $("#modal_applicant_district").val();
        var state = $("#modal_applicant_state").val();
        var country = $("#modal_applicant_country").val();
        var pincode = $("#modal_pincode").val();
        // Initializing Variables With Regular Expressions
        var name_regex = /^[a-zA-Z0-9\u00C0-\u017F\s\/,.&'@\-#]+$/;
        var city_regex = /^[a-zA-Z ]+$/;
        var email_regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/; // /^[w-.+]+@[a-zA-Z0-9.-]+.[a-zA-z0-9]{2,4}$/;
        var add_regex = /^[a-zA-Z0-9\s,.:'-/]{3,}$/;   //   /^[0-9a-zA-Z ]+$/;
        var zip_regex = /^[0-9]{6,}$/;
        if (!($("#modal_home_address_type").is(':checked') || $("#modal_work_address_type").is(':checked') || $("#modal_other_address_type").is(':checked'))) {
            $("#modal_other_address_type").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Address Type Required*</strong></div>');
            return false;
        } else if (first_name.match(name_regex) == null || first_name.length == 0) {
            $("#modal_applicant_first_name").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Full Name Required.*</strong></div>');
            $("#modal_applicant_first_name").focus(); return false;
        }
        // else if (second_name.match(name_regex) == null || second_name.length == 0) {
        //     $("#modal_applicant_second_name").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Second Name Required. Use alphabets only.*</strong></div>');
        //     $("#modal_applicant_second_name").focus(); return false;
        // }
        else if (pincode.match(zip_regex) == null || pincode.length != 6) {
            $("#modal_pincode").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Valid Pincode Required*</strong></div>');
            $("#modal_pincode").focus(); return false;
        } else if (postal_add.match(add_regex) == null || postal_add.length == 0) {
            $("#modal_postal_add").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Address Required. Do not use Special Symbols</strong></div>');
            $("#modal_postal_add").focus(); return false;
        } else if (city.match(city_regex) == null || city.length == 0) {
            $("#modal_applicant_city").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>City Name Required*</strong></div>');
            $("#modal_applicant_city").focus(); return false;
        } else if (district.match(city_regex) == null || district.length == 0) {
            $("#modal_applicant_district").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>District Name Required*</strong></div>');
            $("#modal_applicant_district").focus(); return false;
        } else if (state.match(city_regex) == null || state.length == 0) {
            $("#modal_applicant_state").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>State Name Required*</strong></div>');
            $("#modal_applicant_state").focus(); return false;
        } else if (country.match(city_regex) == null || country.length == 0) {
            $("#modal_applicant_country").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Country Name Required*</strong></div>');
            $("#modal_applicant_country").focus(); return false;
        } else {
            $.ajax({
                type: 'POST',
                url: url,
                dataType: "json",
                cache: false,
                beforeSend: function () {
                    $('.show_msg_modal').html('<table widht="100%" align="center"><tr><td>Loading...</td></tr></table>');
                },
                data:{
                    address_id: address_id,
                    first_name: first_name,
                    // second_name: second_name,
                    address_type: address_type,
                    postal_add: postal_add,
                    city: city,
                    district: district,
                    state: state,
                    country: country,
                    pincode: pincode
                },
                success: function(data)
                {
                    $('.show_msg_modal').html('');
                    if(data.status == 'success') {
                        swal.fire({
                            title: "SUCCESS!",
                            text: "Successfully Edited",
                            icon: "success",
                            button: "Next",
                        }).then(function () {
                            window.location.href = '<?= base_url('online_copying/applicant_address'); ?>';
                            }
                        );
                    } else {
                        $('.show_msg_modal').html('<div class="alert alert-danger alert-dismissible"><strong>'+data.status+'</strong></div>');
                    }
                }
            });
        }
    });
    $(document).on('click','.btn_remove_address',function(){
        var address_id = $(this).data("address-id");
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('online_copying/user_address_remove'); ?>",
            dataType: "json",
            cache: false,
            data:{address_id: address_id},
            beforeSend: function () {
                $('.show_msg').html('<table widht="100%" align="center"><tr><td>Loading...</td></tr></table>');
            },
            success: function(data)
            {
                $('.show_msg').html('');
                if(data.status == 'success') {                     
                    Swal.fire({
                        title: 'SUCCESS!',
                        text: 'Successfully Removed',
                        icon: 'success',
                        button: 'Next'
                    }).then(function () {
                        window.location.href = '<?= base_url('online_copying/applicant_address'); ?>';
                    });
                } else {
                    $('.show_msg').html('<div class="alert alert-danger alert-dismissible"><strong>'+data.status+'</strong></div>');
                }
            }
        });
    });     
</script>
@endpush