@extends('layout.advocateApp')
@section('content')
<style>.pointer {cursor: pointer;}</style>
<div class="container-fluid">
    <div class="row card">
        <div class="col-lg-12">
            <div class="card-body">
                <div class="dashboard-section">

    <div class="row">

            <div class="x_panel">

                    <div class="x_title">
                        <?php
                        if (!empty($_SESSION['login']) && $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) { ?>
                        <h2><i class="fa fa-newspaper-o"></i>AIAssisted Cases Report</h2>
                        <?php } else { ?>
                            <h2><i class="fa fa-newspaper-o"></i>AIAssisted Cases Search</h2>
                        <?php } ?>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <?php
                         $today=date('Y-m-d');
                         $yesterday=date('Y-m-d', strtotime(' -1 day'));
                         $daterange=$yesterday.' to '.$today;
                         ?>
                        <!--start akg-->
                        <div id="widgets-container" ng-init="widgets.recentDocuments.byOthers.ifVisible=true;" class="uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-3@l ukchild-width-1-4@xl ukmargin-medium-top uk-grid-medium ukflex-between uk-grid" uk-grid="">

                            <div class="defects-widget-container">
                                <div class="uk-card-default uk-box-shadow-xlarge uk-border-rounded defects-widget" style="border-top:0.15rem dashed #ccc;">
                                    <div class="uk-flex-middle uk-grid-medium uk-grid uk-grid-stack pointer" style="text-decoration:none;padding:0.6rem 0.5rem 0.2rem 1rem;" uk-grid="" onclick="openSearch('ShowEfilingRequests', this, 'danger')" id="defaultOpen">
                                        <div>
                                            <span class="uk-label uk-label-danger sc-padding sc-padding-small-ends uk-text-bold uk-text-large"><span class="glyphicon glyphicon-hand-right"></span></span>
                                        </div>
                                        <div class="uk-first-column">
                                            <span class="uk-text-bold uk-text-danger uk-text-uppercase">Search by E-filing Number</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="uk-card-default uk-box-shadow-xlarge uk-border-rounded applications-widget" style="border-top:0.15rem dashed #ccc;">
                                    <div class="uk-flex-middle uk-grid-medium uk-grid uk-grid-stack pointer" style="text-decoration:none;padding:0.6rem 0.5rem 0.2rem 1rem;" uk-grid="" onclick="openSearch('ShowDiaryRequests', this, 'uk-label-warning')">
                                        <div>
                                            <span class="uk-label uk-label-warning sc-padding sc-padding-small-ends uk-text-bold uk-text-large"><span class="glyphicon glyphicon-hand-right"></span></span>
                                        </div>
                                        <div class="uk-first-column">
                                            <span class="uk-text-bold uk-text-warning uk-text-uppercase">Search by Diary / Registration Number</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div ng-show="widgets.recentDocuments.byOthers.ifVisible" class="uk-first-column">
                                <div class="uk-card-default uk-box-shadow-xlarge uk-border-rounded documents-widget" style="border-top:0.15rem dashed #ccc;">
                                    <div class="uk-flex-middle uk-grid-medium uk-grid uk-grid-stack pointer" style="text-decoration:none;padding:0.6rem 0.5rem 0.2rem 1rem;" uk-grid=""  class="tablink" onclick="openSearch('ShowCases', this, 'uk-label-primary')">

                                        <div>
                                            <span class="uk-label uk-label-primary sc-padding sc-padding-small-ends uk-text-bold uk-text-large"><span class="glyphicon glyphicon-hand-right"></span></span>
                                        </div>
                                        <div class="uk-first-column">
                                            <div>
                                                <!--<span class="uk-text-bold uk-text-primary uk-text-uppercase">Cases <span class="uk-text-small">(Soon to be listed)</span></span>-->
                                                <span class="uk-text-bold uk-text-primary uk-text-uppercase">Search by Other Details</span>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                   <br/> <br/> <br/>
                        <!--end akg-->
                        <div class="row">
                            <div class="col-12 col-xs-12 tabcontent" id="ShowEfilingRequests">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="row">
                                            <label class="col-sm-6">E-Filing Number:</label>
                                            <div class="col-sm-6">
                                                <input class="form-control cus-form-ctrl "  id="efiling_no"  name="efiling_no"  placeholder="E-Filing Number..."  type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="col-12">
                                            <div class="input-group">
                                                <select tabindex = '25' class="form-control input-sm cus-form-ctrl  filter_select_dropdown" id="efiling_year" name="efiling_year" style="width: 100%">

                                                    <?php
                                                    $end_year = 48;
                                                    for ($i = 0; $i <= $end_year; $i++) {
                                                        $year = (int) date("Y") - $i;
                                                        $sel = ($year == ((int) date("Y"))) ? 'selected=selected' : '';
                                                        echo '<option ' . $sel . ' value=' .$year . '>' . $year . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="col-12">
                                            <input type="submit" id="SearchEfilingNumbersubmit" name="add_notice" value="Search" class="btn btn-success SearchEfilingNumbersubmit">
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <!--//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX-->

                            <div class="panel panel-default tabcontent" id="ShowDiaryRequests">

                                <?php
                                //$attribute = array('class' => 'form-horizontal', 'id' => 'search_case_details_pdf', 'name' => 'search_case_details_pdf', 'autocomplete' => 'off','novalidate'=>'novalidate');
                                //echo form_open('#', $attribute);
                                ?>
                                <!--<center>  <br>-->
                                    <div class="col-md-4 col-sm-8 col-xs-12" style="margin-top: 30px;">
                                <div style="width: fit-content;padding-left: 10px;">
                                    <label class="radio-inline input-lg"><input type="radio"  name="search_filing_type" value="register"> Registration No</label>
                                    <label class="radio-inline input-lg"><input type="radio" checked name="search_filing_type" value="diary" > Diary Number</label>

                                </div>
                                    </div>
                                <!--</center>
                                <br><hr>-->

                                <div class="card-body diary box" style="display:block;background-color: #ffffff;border-color: #ffffff;">
                                    <div class="col-md-4 col-sm-4 col-xs-12" >
                                        <div class="form-group">
                                            <!--<label class="control-label input-lg"> Diary No. <span style="color: red">*</span>:</label>-->
                                            <!--<label class="control-label col-md-3 col-sm-12 col-xs-12 input-lg"> Diary No. <span style="color: red">*</span>:</label>-->
                                           <!-- <div class="col-md-9 col-sm-12 col-xs-12">-->
                                            <label for="exampleInputEmail1"> Diary No. <span style="color: red">*</span>:</label>
                                                <div class="input-group">
                                                    <input id="diary_no" name="diary_no" maxlength="10"  placeholder="Diary No."  class="form-control input-lg age_calculate" type="text" required>
                                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Diary number should be digit only.">
                                                        <i class="fa fa-question-circle-o"  ></i>
                                                    </span>
                                                </div>

                                            <!--</div>-->
                                        </div>
                                    </div>
                                    <div class=" col-md-2 col-sm-2 col-xs-12 ">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Diary Year <span style="color: red">*</span>:</label>
                                                <div class="input-group">

                                                    <select class="form-control input-sm filter_select_dropdown" id="diary_year" name="diary_year" style="width: 100%">

                                                        <?php
                                                            $end_year = 48;
                                                            for ($i = 0; $i <= $end_year; $i++) {
                                                                $year = (int) date("Y") - $i;
                                                                $sel = ($year == ((int) date("Y"))) ? 'selected=selected' : '';
                                                                echo '<option ' . $sel . ' value=' .$year . '>' . $year . '</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            <!--</div>-->
                                        </div>
                                    </div>

                                    <div class=" col-md-2 col-sm-2 col-xs-12 ">

                                        <div class="col-md-offset-5" id="submitBtn_dynamicalayDiary" style="display: block;margin-top: 25px;">
                                            <input type="submit" id="SearchDiaryNumbersubmit" name="add_notice" value="Search" class="btn btn-success SearchDiaryNumbersubmit">


                                        </div>
                                    </div>
                                </div>

                                <div class="card-body register box" style="display: none;background-color: #ffffff;border-color: #ffffff;">
                                    <div class="col-md-4 col-sm-6 col-xs-12" >
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"> Case Type <span style="color: red">*</span>:</label>
                                                <select name="sc_case_type" id="sc_case_type" class="form-control input-lg filter_select_dropdown"  style="width:100%;" required>
                                                    <option value="" title="Select">Select Case Type</option>
                                                    <?php
                                                    //if (count($sc_case_type)) {
                                                    if (!empty($sc_case_type)) { //count testing ok
                                                        foreach ($sc_case_type as $dataRes) {
                                                           ?>
                                                            <option  value="<?php echo_data(url_encryption(trim($dataRes->casecode))); ?>"><?php echo_data($dataRes->casename); ?> </option>;
                                                            <?php
                                                        }
                                                    }
                                                    ?>

                                                </select>
                                            <!--</div>-->
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12  "  >
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"> Case No. <span style="color: red">*</span>:</label>

                                                <div class="input-group">
                                                    <input id="case_number" name="case_number" maxlength="10" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"   placeholder="Case No."  class="form-control input-lg age_calculate" type="text" required>
                                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Related case number should be digits only.">
                                                        <i class="fa fa-question-circle-o"  ></i>
                                                    </span>
                                                </div>

                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-3 col-xs-12  " >
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"> Case Year <span style="color: red">*</span>:</label>
                                                <div class="input-group">
                                                    <select class="form-control input-sm filter_select_dropdown" id="case_year" name="case_year" style="width: 100%">

                                                        <?php
                                                        $end_year = 48;
                                                        for ($i = 0; $i <= $end_year; $i++) {
                                                            $year = (int) date("Y") - $i;
                                                            $sel = ($year == ((int) date("Y"))) ? 'selected=selected' : '';
                                                            echo '<option ' . $sel . ' value=' .$year . '>' . $year . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-3 col-xs-12  " >
                                        <div class="col-md-offset-5" id="submitBtn_dynamicalayCase" style="display: block;margin-top: 25px;">
                                            <input type="submit" id="SearchCaseNumbersubmit" name="add_notice" value="Search" class="btn btn-success SearchCaseNumbersubmit">

                                        </div>
                                    </div>

                                </div>

                            </div>

                            <!--//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX-->


                        </div>

                        <?php //if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {?>

                        <div class="row tabcontent" id="ShowCases">

                        <div class="row">
                            <div class="col-sm-4 col-xs-12">
                                <div class="form-group">

                                    <div class="col-sm-12 col-xs-12" style="margin-top: 30px;">
                                        <div style="width: fit-content;padding-left: 10px;">
                                            <label class="radio-inline input-lg"><input type="radio" checked name="status_type" value="P">All AIAssisted</label>
                                            <label class="radio-inline input-lg"><input type="radio"  name="status_type" value="C" >Final Submit</label>
                                            <label class="radio-inline input-lg"><input type="radio"  name="status_type" value="w" >Without Final Submit</label>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <center> <label class="control-label">Search Stages</label></center>
                                            <select name="stage_id" id="stage_id" class="uk-select filter_select_dropdown">
                                                <?php
                                                echo '<option  value="' . htmlentities(url_encryption('All'), ENT_QUOTES) . '" title="Select">All</option>';
                                                foreach ($stage_list as $row) {
                                                    $sel= '';
                                                    echo '<option '.$sel.' value="' . htmlentities(url_encryption($row['stage_id']), ENT_QUOTES) . '">' . htmlentities(strtoupper($row['admin_stage_name']), ENT_QUOTES) . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <center> <label class="control-label">Case Type</label></center>
                                            <select name="filing_type_id" id="filing_type_id" class="uk-select filter_select_dropdown">
                                               <?php
                                                echo '<option  value="' . htmlentities(url_encryption('All'), ENT_QUOTES) . '" title="Select" selected="selected">All</option>';
                                                if (!empty($sc_case_type)) {
                                                    foreach ($sc_case_type as $dataRes) {
                                                        ?>
                                                        <option  value="<?php echo_data(url_encryption(trim($dataRes->casecode))); ?>"><?php echo_data($dataRes->casename); ?> </option>;
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-2 col-xs-12">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <center> <label class="control-label">User Types</label></center>
                                            <select name="users_id" id="users_id" class="uk-select filter_select_dropdown">
                                                 <?php
                                                echo '<option  value="' . htmlentities(url_encryption('All'), ENT_QUOTES) . '" title="Select" selected="selected">All</option>';
                                                foreach ($users_types_list as $row) {
                                                    echo '<option  value="' . htmlentities(url_encryption($row['id']), ENT_QUOTES) . '">' . htmlentities(strtoupper($row['user_type']), ENT_QUOTES).'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-2 col-xs-12 divdaterangeStop" style="visibility: hidden;">
                                <div class="form-group">
                                    <label class="control-label col-sm-11"><strong><center>Date Range</center></strong>
                                        <div class="checkbox" style="display: none;" >
                                            &nbsp;<label><input type="radio" value="All" name="ActionFiledOn">All</label><label><input type="radio" value="Action" name="ActionFiledOn">Action</label><label><input type="radio" value="FiledOn" name="ActionFiledOn" checked>Filed On</label>

                                        </div>
                                    </label>
                                    <div class="col-sm-12">
                                        <input class="form-control diary_date" tabindex="2" id="listing_date_range"  name="listing_date_range"  placeholder="DD/MM/YYYY-DD/MM/YYYY"  type="text" value="">
                                    </div>

                                </div>
                            </div>
                        </div>

                        <br/><br/><hr/>
                            <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                                <div class="col-md-12 col-sm-12 col-xs-12" id="loader_div" style="display:none;">
                                    <img id="loader_img" style="position: fixed;left: 50%;margin-top: -50px;margin-left: -100px;" src="<?php echo base_url(); ?>/assets/images/loading-data.gif">
                                </div>
                                    <div class="form-group" id="status_refresh">

                                    <input type="submit" id="Reportsubmit" name="add_notice" value="Search" class="btn btn-success loadDataReport">
                                  <button onclick="location.href = '<?php echo base_url('report'); ?>'" class="btn btn-primary" type="reset">Reset</button>
                                </div>
                            </div>
                            </div>
                        </div>
                        <?php //} ?>


            </div>
        </div>

    <!------------Table--------------------->

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            <div class="x_panel">
                <div class="x_title"> <h3 id="divTitle"></h3></div>
                <div class="x_content">
                    <div class="table-wrapper-scroll-y my-custom-scrollbar dictbldata">
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>

                            <tr class="success input-sm" role="row" >
                                <th width="6%">S.N0.</th>
                                <th width="10%">Case Type</th>
                                <th width="10%">Filing No. <br> Filed On</th>
                                <th width="8%">Type</th>
                                <th width="8%">Diary No.</th>
                                <th width="25%">Causetitle</th>
                                <th width="14%">Filed By</th>
                                <th width="10%">Stages</th>
                                <th width="10%">Created On</th>
                                <th width="10%">Uploaded On</th>
                            </tr>
                            </thead>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </div>
        <!------------Table--------------------->
    </div>
                </div>
</div>
</div>
@endsection
@push('script')

<!-- Case Status modal-start-->
<link rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/templates/uikit_scutum_2/assets/css/main.min.css" />
<link type="text/css" rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/frameworks/uikit_3-4-1/css/uikit.min.css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/templates/uikit_scutum_2/assets/css/fonts/mdi_fonts.css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/templates/uikit_scutum_2/assets/css/materialdesignicons.min.css" />

<script src="<?= base_url() ?>assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit.min.js"></script>
<script src="<?= base_url() ?>assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit-icons.min.js"></script>
<!-- Case Status modal-end-->


<script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/jquery.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/jquery-ui.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery-ui.css">
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/daterangepicker/daterangepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/daterangepicker/moment.min.js"></script>
<script src="<?= base_url() ?>assets/js/daterangepicker/daterangepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/daterangepicker/daterangepicker.css">

<script>
    function openSearch(cityName,elmnt,color) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].style.backgroundColor = "";
        }
        document.getElementById(cityName).style.display = "block";
        elmnt.style.backgroundColor = color;

    }
    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
</script>
<script>
    //$(".divdaterange").show();
    $('input[type="radio"][name="status_type"]').click(function () {

        var inputValue = $(this).attr("value");
        if(inputValue=='P'){
            $(".divdaterange").hide();
        }
        else if(inputValue=='C'){
            $(".divdaterange").show();
        }

    });
    $('input[type="radio"]').click(function () {

        var inputValue = $(this).attr("value");

        if (inputValue == 'diary') {
            $('#diaryno').val('');
            $('#diary_year').val('');
            $('#submitBtn_dynamicalayCase').hide();
            $('#submitBtn_dynamicalayDiary').show();

        } else if (inputValue == 'register') {
            $('#sc_case_type').val('');
            $('#case_number').val('');
            $('#case_year').val('');
            $('#submitBtn_dynamicalayCase').show();
            $('#submitBtn_dynamicalayDiary').hide();

        }
        var targetBox = $("." + inputValue);
        $(".box").not(targetBox).hide();
        $(targetBox).show();


    });
</script>
<script>
    $(document).ready(function() {
        $('.diary_date').daterangepicker ({
            autoclose: true,
            autoApply:true,
            showDropdowns: true,
            //useCurrent: false,
            timePicker: true,
            startDate: moment().startOf('hour').add(24, 'hour'),
            //endDate: moment().startOf('hour').add(32, 'hour'),
            endDate: moment().startOf('hour').add(24, 'hour'),
            locale: {
               // format: 'YYYY-MM-DD hh:mm:ss',//
                 format: 'YYYY-MM-DD hh:mm:ss A',
                separator: " to "
            }
        });
        $('#listing_date_range').val('<?=$daterange;?>');
    });
</script>


<script>
    $(document).ready(function() {
        $('#datatable-responsive').DataTable({
            dom: 'lBfrtip',
            buttons: [{
                extend: 'pdf',
                title: 'Report List',
                filename: 'Report_pdf_file_name',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }, {
                extend: 'excel',
                title: 'Report List',
                filename: 'Report_excel_file_name'
            }, {
                extend: 'csv',
                filename: 'Report_csv_file_name'
            }, {
                extend: 'print',
                title: 'Report List',
                filename: 'Report_print_file_name'
            }],
            "pageLength": 50,

        });
    });
</script>

<script type="text/javascript">
    $(function() {
        $(".divdaterange").hide();
        //$('#status_refresh').hide();
        //$('#loader_div').show();
        var diary_no_Defult='All';
        var diary_year_Defult='All';
        var efiling_no_Defult='All';
        var efiling_year_Defult='All';

        var ActionFiledOn = $("input[name='ActionFiledOn']:checked").val();
        var listing_date=$('#listing_date_range').val();
        var stage_id_Defult = $("#stage_id option:selected").val();
        var filing_type_id_Defult = $("#filing_type_id option:selected").val();
        var users_id_Defult = $("#users_id option:selected").val();
        var search_type='efiling';
        $(".dictbldata").hide();

        //SearchDiaryNumbersubmit
        $(".SearchDiaryNumbersubmit").click(function(e) {
            //alert("Rounak");
            e.preventDefault();
            //alert('welcome click =Search Diary Number submit');
            var diary_no=$('#diary_no').val();
            if(diary_no == null || diary_no=="") {
                alert('Please enter the diary number');
                $('#diary_no').focus();
                return false;
            }
            var diary_year=$('#diary_year').val();
            if(diary_year == null || diary_year=="") {
                alert('Please enter the diary Year');
                $('#diary_year').focus();
                return false;
            }
            var efiling_no='All';
            var efiling_year='All';
            var ActionFiledOnGet ='All';
            var date=$('#listing_date_range').val();
            var stage_id ='All';
            var filing_type_id = 'All';
            var users_id = 'All';
            var search_type='Diary';

            loadData(search_type,ActionFiledOnGet,date,stage_id,filing_type_id,users_id,diary_no,diary_year,efiling_no,efiling_year);
        });

        //SearchCaseNumbersubmit
        $(".SearchCaseNumbersubmit").click(function(e) {
            //alert("jai bholenath");
            e.preventDefault();
            //alert('welcome click =Search Diary Number submit');
            var sc_case_type_id=$('#sc_case_type').val();
            if(sc_case_type_id == null || sc_case_type_id=="") {
                alert('Please Select the Case Type');
                $('#sc_case_type').focus();
                return false;
            }
            var case_number=$('#case_number').val();
            if(case_number == null || case_number=="") {
                alert('Please Enter the Case Number');
                $('#case_number').focus();
                return false;
            }
            var case_year=$('#case_year').val();
            if(case_year == null || case_year=="") {
                alert('Please Enter the Case year');
                $('#case_year').focus();
                return false;
            }
            var efiling_no='All';
            var efiling_year='All';
            var ActionFiledOnGet ='All';
            var date=$('#listing_date_range').val();
            var stage_id ='All';
            var filing_type_id = 'All';
            var users_id = 'All';
            var search_type='Diary';
            //XXXXXXXXXXXXXXXXXXX

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "GET",
                url: "<?php echo base_url('AIAssisted/Report_search/Get_search_case_details_rpt'); ?>",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, case_type:sc_case_type_id, caseNo:case_number, caseYr:case_year},
                async: false,
                /*beforeSend: function () {
                    $('#search_sc_case').val('Please wait...');
                    $('#search_sc_case').prop('disabled', true);
                },*/
                success: function (resultData) {
                    /*alert(resultData);
                    console.log(resultData);
                    return;*/

                    var rdata = JSON.parse(resultData);
                    //console.log(rdata[0]['diary_no']);return;

                    var diary_no = rdata[0]['diary_no'];
                    var diary_year= rdata[0]['diary_year'];

                    if(diary_no !='' && diary_year !=''){

                        loadData(search_type,ActionFiledOnGet,date,stage_id,filing_type_id,users_id,diary_no,diary_year,efiling_no,efiling_year);
                    }


                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });

                }
            });


            //XXXXXXXXXXXXXXXXXX


        });

        $(".SearchEfilingNumbersubmit").click(function(e) {
            e.preventDefault();
            //alert('welcome click =Search Efiling Number submit');
            var efiling_no=$('#efiling_no').val();
            var efiling_year=$('#efiling_year').val();

            if(efiling_no == null || efiling_no=="") {
                alert('Please enter the e-filing number');
                return false;
            }
            var diary_no='All';
            var diary_year='All';
            var ActionFiledOnGet ='All';
            var date=$('#listing_date_range').val();
            var stage_id ='All';
            var filing_type_id = 'All';
            var users_id = 'All';
            var search_type='efiling';

            loadData(search_type,ActionFiledOnGet,date,stage_id,filing_type_id,users_id,diary_no,diary_year,efiling_no,efiling_year);
        });


        //end loadDataReport_users_view
        // Premade test data, you can also use your own

        $(".loadDataReport").click(function(e) {
            e.preventDefault();
            $(".dictbldata").hide();
            //var status_type = $("input[name='status_type']).val();
            var status_type=$('input[type="radio"][name="status_type"]:checked').val();
            var diary_no=$('#diary_no').val();
            var efiling_no=$('#efiling_no').val();
            var ActionFiledOnGet = $("input[name='ActionFiledOn']:checked").val();
            var date=$('#listing_date_range').val();
            var stage_id = $("#stage_id option:selected").val();
            var filing_type_id = $("#filing_type_id option:selected").val();
            var users_id = $("#users_id option:selected").val();
            var search_type='All';

            loadData(search_type,ActionFiledOnGet,date,stage_id,filing_type_id,users_id,diary_no,diary_year,efiling_no,efiling_year,status_type);
        });
        function loadData(search_type,ActionFiledOn,date,stage_id,filing_type_id,users_id,diary_no,diary_year,efiling_no,efiling_year,status_type) {
            if(date == null || date=="") {
                return false;
            }
            var datearray = date.split("to");
            var fromDateGet = datearray[0];
            var from_Date = fromDateGet.split("-");
            var fromDate = from_Date[2]+'-'+from_Date[1]+'-'+from_Date[0];

            var toDateGet = datearray[1];
            var to_Date = toDateGet.split("-");
            var toDate = to_Date[2]+'-'+to_Date[1]+'-'+to_Date[0];
            $('#divTitle').html('');
            if(ActionFiledOn !='All') {
                //$('#divTitle').html('Report for Date :' + fromDate + ' TO ' + toDate);
            }

            $.ajax({
                type: 'GET',
                url:  "<?php echo base_url('AIAssisted/Report_search/get_aiassisted_cases'); ?>?DateRange="+date+'&ActionFiledOn=' + ActionFiledOn+'&stage_id=' + stage_id+'&filing_type_id=' + filing_type_id+'&users_id=' + users_id+'&diary_no=' + diary_no+'&diary_year=' + diary_year+'&efiling_no=' + efiling_no+'&efiling_year=' + efiling_year+'&search_type=' + search_type +'&status_type=' + status_type,
                contentType: "text/plain",
                dataType: 'json',
                beforeSend: function(){
                    $('#divTitle').html('Loading...');
                },
                success: function (data) {
                    var Report_fromDate_toDate=data.status.Report_fromDate_toDate;
                    if(ActionFiledOn !='All') {
                        //alert(Report_fromDate_toDate);

                    }
                    $('#divTitle').html(Report_fromDate_toDate);
                    myJsonData = data;
                    populateDataTable(myJsonData);
                },
                error: function (e) {
                    console.log("There was an error with your request...");
                    console.log("error: " + JSON.stringify(e));
                    $('#divTitle').html('There was an error with your request...');
                }
            });
        }

        function populateDataTable(data) {
            $(".dictbldata").show();
            $("#datatable-responsive").DataTable().clear();
            var table = $('#datatable-responsive').DataTable();

            table
                .clear()
                .draw();
            table.on('order.dt search.dt', function () {
                table.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1; // Set Serial Number
                });
            }).draw();
            var diary_no_m=''; var diary_year_m=''; var open_case_status=''; var reg_no='';
            var length = Object.keys(data.customers).length;
            //var redirect_url = ''; var efiling_no=''; var rd=''; var v=''; var res_name=''; var pet_name=''; var cause_title=''; var cause_details=''; var diary_no=''; var diary_year='';

                for(var i = 0; i < length+1; i++) {
                    var redirect_url = ''; var efiling_no=''; var rd=''; var v=''; var res_name=''; var pet_name=''; var cause_title=''; var cause_details=''; var diary_no=''; var diary_year='';
                    var sn=i+1;
                    var report = data.customers[i];
                    var stage_id=report.stage_id;
                    var documentIANumber='';
                    var caveatNumber='';

                    if(report.cause_title!=null){cause_title=report.cause_title; }
                    else if(report.ecase_cause_title!=null){cause_title=report.ecase_cause_title; }

                    if(report.pet_name!=null){pet_name=report.pet_name+'Vs.';}else{ pet_name=''; }

                    if(report.res_name!=null){res_name=report.res_name;} else{res_name='';}
                    diary_no='';diary_year='';reg_no='';

                    if(report.diary_no!=null){diary_no=report.diary_no;}
                    if(report.diary_no!=null){diary_no=''+report.diary_no+'/'; diary_no_m=report.diary_no;}
                    else if(report.sc_diary_num!=null){diary_no=''+report.sc_diary_num+'/'; diary_no_m=report.sc_diary_num;}

                    if(report.diary_year!=null){diary_year=report.diary_year+'<br/>'; diary_year_m=report.diary_year;}
                    else if(report.sc_diary_year!=null){diary_year=report.sc_diary_year+'<br/>'; diary_year_m=report.sc_diary_year;}

                    if (report.reg_no_display != '' && report.sc_display_num !=null) {
                        reg_no = '<b>Registration No.</b> : ' +report.sc_display_num+ '<br/> ';
                    } else {
                        reg_no = '';
                    }
                    if(report.diary_no !=null){
                         open_case_status='href="#" onClick="open_case_status()"';
                    }else{ open_case_status='';}
                    case_no=diary_no+ diary_year + reg_no;
                    if(report.efiling_type!='CAVEAT'){
                        cause_details= '<span class="sci">'+cause_title+'<br/>'+pet_name+res_name+'</span>';
                    }
                    else{
                        cause_details='';
                    }


                    if(report.efiling_type !='' && report.efiling_type=='new_case') {
                        rd='newcase.defaultController'; //. equal to / required
                        v='/'+report.registration_id + '/' + report.ref_m_efiled_type_id + '/' + report.stage_id + '/' + report.efiling_no;
                    }
                    else if(report.efiling_type !='' && report.efiling_type=='misc_document') {
                    rd='miscellaneous_docs.DefaultController'; //. equal to / required
                    v='/'+report.registration_id + '/' + report.ref_m_efiled_type_id + '/' + report.stage_id;
                    }
                    else if(report.efiling_type !='' && report.efiling_type=='IA') {
                    rd='IA.DefaultController'; //. equal to / required
                    v='/'+report.registration_id + '/' + report.ref_m_efiled_type_id + '/' + report.stage_id;
                    }
                    else if(report.efiling_type !='' && report.efiling_type=='CAVEAT') {
                    rd='case.caveat.crud'; //. equal to / required
                    v='/'+report.registration_id + '/' + report.ref_m_efiled_type_id + '/' + report.stage_id;
                    }
                    var efiling_type='';
                    if(report.efiling_type !=null){
                        var str_efiling_type=report.efiling_type;
                        var efiling_type=str_efiling_type.replace("_"," ");
                    }

                    var filed_by='';

                    if(report.ref_m_usertype_id==1){filed_by=report.filed_by + '<br>(AOR Code: '+ report.aor_code + ')';}else if(report.ref_m_usertype_id==2){filed_by=report.filed_by + '<br>(Party in person)';}

                    var allocated_to='';

                    var create_on = report.create_on;
                    if(report.create_on==null){

                    }else {
                        var create_on_date = create_on.split(' ');
                        var create_on_onlytime = create_on_date[1];

                        var create_on_now = create_on_onlytime.split(':');
                        var create_on_hours = create_on_now[0];
                        var create_on_ampm = create_on_hours >= 12 ? 'pm' : 'am';
                        create_on = $.datepicker.formatDate("dd-mm-yy", $.datepicker.parseDate('yy-mm-dd', create_on));
                        create_on = create_on + ' ' + create_on_onlytime;
                    }
                    var date = report.uploaded_on;
                    var from_date = date.split(' '); var onlytime =from_date[1];

                    var now = onlytime.split(':'); var hours = now[0];
                    var ampm = hours >= 12 ? 'pm' : 'am';
                    date = $.datepicker.formatDate("dd/mm/yy", $.datepicker.parseDate('yy-mm-dd', date));
                    date=date+' '+onlytime;

                    redirect_url = "<?=base_url('report/search/view/')?>"+rd+v;
                    var date_filed_on="";
                    if(report.create_on==null){

                    }
                    else{
                        date_filed_on = report.create_on;
                        var from_date = date_filed_on.split(' '); var onlytime =from_date[1];

                        var now = onlytime.split(':'); var hours = now[0];
                        var ampm = hours >= 12 ? 'pm' : 'am';
                        date_filed_on = $.datepicker.formatDate("dd/mm/yy", $.datepicker.parseDate('yy-mm-dd', date_filed_on));
                        date_filed_on=date_filed_on+' '+onlytime;
                    }

                    if(report.efiling_no!=null) {
                        efiling_no = '<a href="' + redirect_url + '">' + report.efiling_no + '</a> <br>' + date_filed_on + '';
                    }


                    $('#datatable-responsive').dataTable().fnAddData( [
                        sn,
                        report.case_type,
                        efiling_no,
                        efiling_type,
                        case_no,
                        cause_details,
                        filed_by,
                        report.current_status,
                        create_on,
                        date,

                    ]);
                    if(sn==length) {
                        console.log('Done data Table');

                        $('#loader_div').hide();
                        $('#status_refresh').show();
                    }
                }

        }
    })();

</script>

<style>
    th{font-size: 13px;color: #000;}
    td{font-size: 13px;color: #000;}
    td .sci{font-size: 13px;color: #000;}

    div.box {
        height: 109px;
        /* padding: 10px; */
        /*overflow: auto;*/
        border: 1px solid #8080FF;
        /*background-color: #E5E5FF;*/
    }
    div.dt-buttons {
        position: relative;
        float: right!important;
    }
</style>

@endpush