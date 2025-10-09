<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
<!-- <link rel="shortcut icon" href="<?= base_url() . 'assets/newAdmin/' ?>images/favicon.gif"> -->
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/font-awesome.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/animate.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/material.css" rel="stylesheet" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/style.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/responsive.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/black-theme.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?= base_url() . 'assets/newAdmin/' ?>css/jquery.dataTables.min.css">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/fullcalendar.css" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery-ui.css">
<link href="<?= base_url() . 'assets' ?>/css/select2.min.css" rel="stylesheet">
<div class="mainPanel ">
    <div class="panelInner">
        <div class="middleContent">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 sm-12 col-md-12 col-lg-12 middleContent-left">
                        <div class="center-content-inner comn-innercontent">
                            <div class="dash-card dashboard-section">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class=" dashboard-bradcrumb">
                                            <div class="left-dash-breadcrumb">
                                                <div class="page-title">
                                                    <?php
                                                    $commonHeading = '';
                                                    if (getSessionData('customEfil') == 'ia') {
                                                        unset($_SESSION['efiling_type']);
                                                        setSessionData('efiling_type', 'ia');
                                                        $commonHeading = 'File an IA';
                                                    } elseif (getSessionData('customEfil') == 'misc') {
                                                        unset($_SESSION['efiling_type']);
                                                        setSessionData('efiling_type', 'misc'); 
                                                        $commonHeading = 'File A Document';
                                                    } elseif (getSessionData('customEfil') == 'refile_old_efiling_cases') {
                                                        unset($_SESSION['efiling_type']);
                                                        setSessionData('efiling_type', 'refile_old_efiling_cases');                                                         
                                                        $commonHeading = 'Refile Old Efiling Case';
                                                    }
                                                    ?>
                                                    <h5><i class="fa fa-file"></i> {{$commonHeading}}</h5>
                                                </div>
                                                <div class="form-response" id="msg" role="alert" data-auto-dismiss="5000"></div>
                                            </div>
                                            <div class="ryt-dash-breadcrumb">
                                                <div class="btns-sec">
                                                    <!-- <a href="javascript:void(0)" class="quick-btn" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                                    <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 ">
                                        <div class="crnt-page-head">
                                            <div class="current-pg-title">
                                                <h6>Case Search </h6>
                                            </div>
                                            <div class="current-pg-actions"> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- tabs-section -start  -->
                            <div class="dash-card dashboard-section">
                                <div class="row">
                                    <div class="panel panel-default">
                                        <?php
                                        $attribute = array('class' => 'form-horizontal', 'id' => 'search_case_details', 'name' => 'search_case_details', 'autocomplete' => 'off', 'novalidate' => 'novalidate');
                                        echo form_open('#', $attribute);
                                        ?>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div style="text-align: center;">
                                            <?php
                                            if (!empty(getSessionData('MSG'))) {
                                                echo getSessionData('MSG');
                                            }
                                            if (!empty(getSessionData('msg'))) {
                                                echo getSessionData('msg');
                                            }
                                            $session = session();
                                            ?>
                                            @if(isset($validation) && !empty($validation->getError('search_filing_type')))
                                            <div class="text-danger">
                                                <b>{{ $validation->getError('search_filing_type')}}</b>
                                            </div>
                                            @endif
                                            @if(isset($validation) && !empty($validation->getError('diaryno')))
                                            <div class="text-danger">
                                                <b>{{ $validation->getError('diaryno')}}</b>
                                            </div>
                                            @endif
                                            @if(isset($validation) && !empty($validation->getError('diary_year')))
                                            <div class="text-danger">
                                                <b>{{ $validation->getError('diary_year')}}</b>
                                            </div>
                                            @endif
                                            @if(isset($validation) && !empty($validation->getError('sc_case_type')))
                                            <div class="text-danger">
                                                <b>{{ $validation->getError('sc_case_type')}}</b>
                                            </div>
                                            @endif
                                            @if(isset($validation) && !empty($validation->getError('case_number')))
                                            <div class="text-danger">
                                                <b>{{ $validation->getError('case_number')}}</b>
                                            </div>
                                            @endif
                                            @if(isset($validation) && !empty($validation->getError('case_year')))
                                            <div class="text-danger">
                                                <b>{{ $validation->getError('case_year')}}</b>
                                            </div>
                                            @endif
                                            <br>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label class="radio-inline input-lg"><input type="radio" checked name="search_filing_type" value="diary"> Diary Number  &nbsp;</label><label class="radio-inline input-lg"><b>OR</b> </label>
                                                <label class="radio-inline input-lg"><input type="radio" name="search_filing_type" value="register">Registration No</label>
                                            </div>
                                        </div>
                                        <br>
                                        <div id="diarySec">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="row mb-3 w-100 align-items-center">
                                                            <div class="col-12">
                                                                <label for="inputPassword6" class="col-form-label">Diary No</label>
                                                            </div>
                                                            <div class="col-12 pe-0">
                                                                <input class="form-control cus-form-ctrl" id="diaryno" name="diaryno" maxlength="10" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Diary No" type="text">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-3 col-xs-12">
                                                    <div class="row">
                                                        <div class="row mb-3 w-100 align-items-center">
                                                            <div class="col-12">
                                                                <label for="inputPassword6" class="col-form-label"> Diary Year</label>
                                                            </div>
                                                            <div class="col-12 pe-0">
                                                                <select class="form-select cus-form-ctrl" aria-label="Default select example" id="diary_year" name="diary_year" style="width: 100%">
                                                                    <?php
                                                                    $end_year = 48;
                                                                    for ($i = 0; $i <= $end_year; $i++) {
                                                                        $year = (int) date('Y') - $i;
                                                                        $sel = $year == ((int) date('Y')) ? 'selected=selected' : '';
                                                                        echo '<option ' . $sel . ' value=' . $year . '>' . $year . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5 col-sm-12 col-xs-12" style="margin-top: 35px;">
                                                    <div class="save-form-details">
                                                        <div class="save-btns">
                                                            <button type="submit" class="quick-btn" id="search_sc_case" value="SEARCH">Search</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="RegSec">
                                            <div class="row">
                                                <div class="col-md-3 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="row mb-3 w-100 align-items-center">
                                                            <div class="col-12">
                                                                <label for="inputPassword6" class="col-form-label">Case Type</label>
                                                            </div>
                                                            <div class="col-12 pe-0">
                                                                <select class="form-select cus-form-ctrl" name="sc_case_type" id="sc_case_type" aria-label="Default select example">
                                                                    <option value="">Case Type</option>;
                                                                    <?php
                                                                    if (count($sc_case_type)) {
                                                                        foreach ($sc_case_type as $dataRes) {
                                                                            $sel = (isset($new_case_details[0]['sc_case_type_id']) && $new_case_details[0]['sc_case_type_id'] == (string) $dataRes->casecode) ? "selected=selected" : '';
                                                                    ?>
                                                                            <option <?php echo $sel; ?> value="<?php echo_data(url_encryption(trim($dataRes->casecode))); ?>"><?php echo_data($dataRes->casename); ?> </option>;
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-3 col-xs-12">
                                                    <div class="row">
                                                        <div class="row mb-3 w-100 align-items-center">
                                                            <div class="col-12">
                                                                <label for="inputPassword6" class="col-form-label">Case No</label>
                                                            </div>
                                                            <div class="col-12 pe-0">
                                                                <input class="form-control cus-form-ctrl" id="case_number" name="case_number" maxlength="10" placeholder="Case No." onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" type="text">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-3 col-xs-12">
                                                    <div class="row">
                                                        <div class="row mb-3 w-100 align-items-center">
                                                            <div class="col-12">
                                                                <label for="inputPassword6" class="col-form-label">Case Year</label>
                                                            </div>
                                                            <div class="col-12 pe-0">
                                                                <select class="form-select cus-form-ctrl" aria-label="Default select example" id="case_year" name="case_year" style="width: 100%">
                                                                    <?php
                                                                    $end_year = 48;
                                                                    for ($i = 0; $i <= $end_year; $i++) {
                                                                        $year = (int) date('Y') - $i;
                                                                        $sel = $year == ((int) date('Y')) ? 'selected=selected' : '';
                                                                        echo '<option ' . $sel . ' value=' . $year . '>' . $year . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="save-form-details">
                                                        <div class="save-btns">
                                                            <button type="submit" class="quick-btn">Search</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php echo form_close(); ?>

                                        <br>
                                        <div id="msg">
                                            <?php $session = session(); ?>
                                            @if(isset($validation) && !empty($validation->getError('search_filing_type')))
                                            <div class="text-danger">
                                                <b>{{ $validation->getError('search_filing_type')}}</b>
                                            </div>
                                            @endif
                                            @if(isset($validation) && !empty($validation->getError('diaryno')))
                                            <div class="text-danger">
                                                <b>{{ $validation->getError('diaryno')}}</b>
                                            </div>
                                            @endif
                                            @if(isset($validation) && !empty($validation->getError('diary_year')))
                                            <div class="text-danger">
                                                <b>{{ $validation->getError('diary_year')}}</b>
                                            </div>
                                            @endif
                                            @if(isset($validation) && !empty($validation->getError('sc_case_type')))
                                            <div class="text-danger">
                                                <b>{{ $validation->getError('sc_case_type')}}</b>
                                            </div>
                                            @endif
                                            @if(isset($validation) && !empty($validation->getError('case_number')))
                                            <div class="text-danger">
                                                <b>{{ $validation->getError('case_number')}}</b>
                                            </div>
                                            @endif
                                            @if(isset($validation) && !empty($validation->getError('case_year')))
                                            <div class="text-danger">
                                                <b>{{ $validation->getError('case_year')}}</b>
                                            </div>
                                            @endif
                                        </div>
                                        <div id="show_search_result_diary_loader" style="text-align: center; display:none;">Please wait...</div>
                                        <div id="show_search_result_diary"></div>
                                        <div id="show_search_result"></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @push('script')
        <script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery-3.3.1.min.js"></script>
        <script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
        <script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
        <script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.slim.min.js"></script>
        <script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
        <script src="<?= base_url() . 'assets' ?>/js/jquery.min.js"></script>
        <script src="<?= base_url() . 'assets' ?>/js/jquery-ui.min.js"></script>
        <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
        <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
        <script src="<?= base_url() ?>assets/js/sha256.js"></script>
        <script src="<?= base_url() ?>assets/newAdmin/js/jquery.dataTables.min.js"></script>
        <script src="<?= base_url() . 'assets' ?>/js/select2.min.js"></script>
        <script src="<?= base_url() . 'assets' ?>/js/select2-tab-fix.min.js"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets' ?>/js/jquery.validate.js"></script>
      
        <script>
            $(document).ready(function() {
                $('#diaryno').attr('required', true);
                $('#diary_year').attr('required', true);

                var inputValue = $('input[type="radio"]:checked').val();
                if (inputValue == 'register') {
                    $('#diaryno').val('');
                    $('#diary_year').val('');
                    $('#diaryno').attr('required', false);
                    $('#diary_year').attr('required', false);
                    $('#sc_case_type').attr('required', true);
                    $('#case_number').attr('required', true);
                    $('#case_year').attr('required', true);
                    $("#show_search_result_diary").css("display", "none");
                    $("#show_search_result").css("display", "block");
                    $("#diarySec").css("display", "none");
                    $("#RegSec").css("display", "block");
                } else if (inputValue == 'diary') {
                    $('#diaryno').attr('required', true);
                    $('#diary_year').attr('required', true);
                    $('#sc_case_type').attr('required', false);
                    $('#case_number').attr('required', false);
                    $('#case_year').attr('required', false);
                    $('#sc_case_type').val('');
                    $('#case_number').val('');
                    $('#case_year').val('');
                    $("#show_search_result").css("display", "none");
                    $("#show_search_result_diary").css("display", "block");
                    $("#RegSec").css("display", "none");
                    $("#diarySec").css("display", "block");
                }
                var targetBox = $("." + inputValue);
                $(".box").not(targetBox).hide();
                $(targetBox).show();
                $('input[type="radio"]').click(function() {
                    var inputValue = $(this).attr("value");
                    if (inputValue == 'register') {
                        $('#diaryno').val('');
                        $('#diary_year').val('');
                        $('#diaryno').attr('required', false);
                        $('#diary_year').attr('required', false);
                        $('#sc_case_type').attr('required', true);
                        $('#case_number').attr('required', true);
                        $('#case_year').attr('required', true);
                        $("#show_search_result_diary").css("display", "none");
                        $("#show_search_result").css("display", "block");
                        $("#diarySec").css("display", "none");
                        $("#RegSec").css("display", "block");
                    } else if (inputValue == 'diary') {
                        $('#diaryno').attr('required', true);
                        $('#diary_year').attr('required', true);
                        $('#sc_case_type').attr('required', false);
                        $('#case_number').attr('required', false);
                        $('#case_year').attr('required', false);
                        $('#sc_case_type').val('');
                        $('#case_number').val('');
                        $('#case_year').val('');
                        $("#show_search_result").css("display", "none");
                        $("#show_search_result_diary").css("display", "block");
                        $("#RegSec").css("display", "none");
                        $("#diarySec").css("display", "block");
                    }
                    var targetBox = $("." + inputValue);
                    $(".box").not(targetBox).hide();
                    $(targetBox).show();
                });
            });
        </script>
        <style>
            .box {
                display: none;
                background-color: none;
            }

            div.box {
                height: 60px;
                padding: 0;
                overflow: unset;
                border: #fff;
                background-color: #fff;
            }
        </style>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#search_case_details').on('submit', function() {
                    var inputValue = $('input[type="radio"]:checked').val();
                    //  alert(inputValue);
                    if(inputValue == 'diary'){
                        $('#diaryno').attr('required', true);
                        $('#diary_year').attr('required', true);
                        $('#sc_case_type').attr('required', false);
                        $('#case_number').attr('required', false);
                        $('#case_year').attr('required', false);

                        var diary_no = $('#diaryno').val();
                        var diary_year = $('#diary_year').val();
                        // alert(diary_year);
                        var form_data = $(this).serialize();
                        if(diary_no==''){
                            alert('Please Enter Diary Number');
                            $('#diaryno').focus(); 
                            return false;
                        }
                        if(diary_year=='' || diary_year==null ){
                            alert('Please Select Diary Year'); 
                            $('#diary_year').focus(); 
                            return false;
                        }

                            var CSRF_TOKEN = 'CSRF_TOKEN';
                            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                            $('#show_search_result_diary').html('');
                            $('#show_search_result').html('');
                            $(".form-response").html('');
                            $(".form-response").hide();
                            $.ajax({
                            type: "GET",
                            url: "<?php echo base_url('case/search/search_case_details'); ?>",
                            data: form_data,
                            //async: false,
                            beforeSend: function() {
                                $('#show_search_result_diary_loader').css("display", "block");
                                $('#search_sc_case').attr('disabled','disabled');
                            },
                            success: function(data) { 
                                $('#show_search_result_diary_loader').css("display", "none");
                                $('#search_sc_case').removeAttr('disabled');
                                var resArr = data.split('@@@');
                                //console.log(resArr[1]);
                                
                                if (resArr[0] == 1) {
                                    $('#show_search_result_diary').html(resArr[1]);
                                } else if (resArr[0] == 2) {
                                    $('#show_search_result').html(resArr[1]);
                                } else if (resArr[0] == 3) {
                                    alert(resArr[1]); 
                                }
                                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                });
                            }
                            });
                        }


                        if(inputValue == 'register'){
                        $('#diaryno').attr('required', false);
                        $('#diary_year').attr('required', false);
                        $('#sc_case_type').attr('required', true);
                        $('#case_number').attr('required', true);
                        $('#case_year').attr('required', true);

                        var sc_case_type = $('#sc_case_type').val();
                        var case_number = $('#case_number').val();
                        var case_year = $('#case_year').val();
                        // alert(diary_year);
                        var form_data = $(this).serialize();
                        if(sc_case_type=='' || sc_case_type==null ){
                            alert('Please Select Case Type');
                            $('#sc_case_type').focus(); 
                            return false;
                        }
                        if(case_number=='' || case_number==null ){
                            alert('Please Enter Case Number'); 
                            $('#case_number').focus(); 
                            return false;
                        }
                        if(case_year=='' || case_year==null ){
                            alert('Please Select Case Year'); 
                            $('#case_year').focus(); 
                            return false;
                        }

                            var CSRF_TOKEN = 'CSRF_TOKEN';
                            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                            $('#show_search_result_diary').html('');
                            $('#show_search_result').html('');
                            $(".form-response").html('');
                            $(".form-response").hide();
                            $.ajax({
                            type: "GET",
                            url: "<?php echo base_url('case/search/search_case_details'); ?>",
                            data: form_data,
                           // async: false,
                            beforeSend: function() {
                                $('#show_search_result_diary_loader').css("display", "block");
                                $('#search_sc_case').attr('disabled','disabled');
                               
                            },
                            success: function(data) { 
                                $('#show_search_result_diary_loader').css("display", "none");
                                $('#search_sc_case').removeAttr('disabled');
                                var resArr = data.split('@@@');
                                //console.log(resArr[1]);

                                if (resArr[0] == 1) {
                                    $('#show_search_result_diary').html(resArr[1]);
                                } else if (resArr[0] == 2) {
                                    $('#show_search_result').html(resArr[1]);
                                } else if (resArr[0] == 3) {
                                    alert(resArr[1]);                                     
                                }
                                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                });
                            }
                            });
                        }
                    // if ($('#search_case_details').valid()) {
                    // var diary_no = $('#diaryno').val();
                    // var form_data = $(this).serialize();
                    // if(diary_no=='') {
                    //     alert("Please Enter Diary Number");
                    //     $('#diaryno').focus();
                    //     return false;
                    // }

                    // console.log(form_data); exit;
                    
                    return false;
                    // } else {
                    //     alert("Form is invalid!");
                    //     return false;
                    // }
                });
            });
        </script>
        <?php if (!empty(getSessionData('error'))): ?>
            <script>
                alert('hkfdghkghkhgfkdhghkdsf');
                swal({
                    title: "Error",
                    text: "Please note, this case is defective. Kindly, cure all defects notified by the Registry through Refiling option.",
                    icon: "<?php echo site_url('Your Icon Destination'); ?>",
                    button: false,
                    timer: 5000,
                });
            </script>
        <?php endif; ?>