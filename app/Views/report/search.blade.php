@extends('layout.app')
@section('content')
<link rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/templates/uikit_scutum_2/assets/css/main.min.css" />
<link type="text/css" rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/frameworks/uikit_3-4-1/css/uikit.min.css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/templates/uikit_scutum_2/assets/css/fonts/mdi_fonts.css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/templates/uikit_scutum_2/assets/css/materialdesignicons.min.css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<style>
    .add-new-area {
        display: none !important;
    }
    th {
        font-size: 13px;
        color: #000;
    }
    td {
        font-size: 13px;
        color: #000;
    }
    td .sci {
        font-size: 13px;
        color: #000;
    }
    @media (max-width: 767px){
        .custom-table td:nth-child(10), .custom-table td:nth-child(11), .custom-table td:nth-child(12), .custom-table td:nth-child(13) {    min-height: 30px;}
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-section dashboard-tiles-area"></div>
            <div class="dashboard-section">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="dash-card">
                            <div class="title-sec">
                                <h5 class="unerline-title">Reports </h5>
                            </div>
                            <div class="right_col" role="main">
                                <!-- <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div id="msg">
                                            <?php
                                            /*  if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
                                                echo $_SESSION['MSG'];
                                            } unset($_SESSION['MSG']); */
                                            ?>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="row">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <?php //if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
                                            if (isset($_SESSION['login']) && ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN_READ_ONLY || $_SESSION['login']['ref_m_usertype_id'] == USER_EFILING_ADMIN)) { ?>
                                                {{-- <h2><i class="fa fa-newspaper-o"></i>Report</h2> --}}
                                            <?php } else { ?>
                                                {{-- <h2><i class="fa fa-newspaper-o"></i>Search Cases</h2> --}}
                                            <?php } ?>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content">
                                            <?php
                                            $today = date('Y-m-d');
                                            $yesterday = date('Y-m-d', strtotime(' -1 day'));
                                            $daterange = $yesterday . ' to ' . $today;
                                            ?>
                                            <!--start akg-->
                                            <!-- <div id="widgets-container" ng-init="widgets.recentDocuments.byOthers.ifVisible=true;" class="" uk-grid="">
                                                <div ng-show="widgets.recentDocuments.byOthers.ifVisible" class="uk-first-column">
                                                    <div class="uk-card-default uk-box-shadow-xlarge uk-border-rounded documents-widget">
                                                        <div class="uk-flex-middle uk-grid-medium uk-grid uk-grid-stack pointer" style="text-decoration:none;padding:0.6rem 0.5rem 0.2rem 1rem;" uk-grid="" class="tablink" onclick="openSearch('ShowCases', this, 'uk-label-primary')" id="defaultOpen"> -->
                                                            <!-- <div>
                                                                <span class="uk-label uk-label-primary sc-padding sc-padding-small-ends uk-text-bold uk-text-large"><span class="glyphicon glyphicon-hand-right"></span></span>
                                                            </div> -->
                                                            <!-- <div class="uk-first-column">
                                                                <div> -->
                                                                    <!--<span class="uk-text-bold uk-text-primary uk-text-uppercase">Cases <span class="uk-text-small">(Soon to be listed)</span></span>-->
                                                                    <!-- <span class="uk-text-bold uk-text-primary uk-text-uppercase">Search by Other Details</span> -->
                                                                <!-- </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="defects-widget-container">
                                                    <div class="uk-card-default uk-box-shadow-xlarge uk-border-rounded defects-widget">
                                                        <div class="uk-flex-middle uk-grid-medium uk-grid uk-grid-stack pointer" style="text-decoration:none;padding:0.6rem 0.5rem 0.2rem 1rem;" uk-grid="" onclick="openSearch('ShowEfilingRequests', this, 'danger')"> -->
                                                            <!-- <div>
                                                                <span class="uk-label uk-label-danger sc-padding sc-padding-small-ends uk-text-bold uk-text-large"><span class="glyphicon glyphicon-hand-right"></span></span>
                                                            </div> -->
                                                            <!-- <div class="uk-first-column">
                                                                <span class="uk-text-bold uk-text-danger uk-text-uppercase">Search by E-filing Number</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="uk-card-default uk-box-shadow-xlarge uk-border-rounded applications-widget">
                                                        <div class="uk-flex-middle uk-grid-medium uk-grid uk-grid-stack pointer" style="text-decoration:none;padding:0.6rem 0.5rem 0.2rem 1rem;" uk-grid="" onclick="openSearch('ShowDiaryRequests', this, 'uk-label-warning')"> -->
                                                            <!-- <div>
                                                                <span class="uk-label uk-label-warning sc-padding sc-padding-small-ends uk-text-bold uk-text-large"><span class="glyphicon glyphicon-hand-right"></span></span>
                                                            </div> -->
                                                            <!-- <div class="uk-first-column">
                                                                <span class="uk-text-bold uk-text-warning uk-text-uppercase">Search by Diary Number</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <nav>
                                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Search by Other Details</button>
                                                    <button class="nav-link" id="nav-ShowEfilingRequests-tab" data-bs-toggle="tab" data-bs-target="#nav-ShowEfilingRequests" type="button" role="tab" aria-controls="nav-ShowEfilingRequests" aria-selected="false">Search by E-filing Number</button>
                                                    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Search by Diary Number</button>
                                                </div>
                                            </nav>
                                            <div class="tab-content" id="nav-tabContent">
                                                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                                    <?php //if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
                                                    if (isset($_SESSION['login']) && ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN_READ_ONLY || $_SESSION['login']['ref_m_usertype_id'] == USER_EFILING_ADMIN)) { ?>
                                                        <div class="row tabcontent" id="ShowCases">
                                                            <div class="row">
                                                                @if(isset($pending_data))
                                                                <?php
                                                                //var_dump($pending_data);
                                                                $pending_with_aor = 0;
                                                                $pending_with_registry = 0;
                                                                foreach ($pending_data as $data) {
                                                                    if ($data->meant_for == 'A') {
                                                                        $pending_with_aor = $data->efiled_cases;
                                                                    } else if ($data->meant_for == 'R') {
                                                                        $pending_with_registry = $data->efiled_cases;
                                                                    }
                                                                }
                                                                ?>
                                                                @endif
                                                                <div class="col-sm-12 col-xs-12 justify-content-center">
                                                                    <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-sm-8 col-xs-8" style="margin-top: 30px;">
                                                                            <div class="center-buttons " >
                                                                                <button type="button" class="quick-btn gradient-btn"> Pending With Advocate/PIP <span class="badge"> {{isset($pending_with_aor)?$pending_with_aor :''}}</span></button>
                                                                                <button type="button" class="quick-btn" data-bs-toggle="modal" data-bs-target="#myModal"> Pending With Registry <span class="badge">{{isset($pending_with_registry)?$pending_with_registry :''}}</span></button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4 col-xs-4" style="margin-top: 30px;">
                                                                            <div style="width: fit-content;padding-left: 10px;">
                                                                                <label class="radio-inline"> Last Updated On : <span style="color: blue">
                                                                                        @if(isset($last_updated_on))
                                                                                        <?= $last_updated_on != '0' ? date('d-m-Y h:i:s A', strtotime($last_updated_on)) : date('d-m-Y h:i:s A') ?>
                                                                                    </span></label>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="container"></div>
                                                                    </div>
                                                                    </div>
                                                                </div>
                                                                <br />
                                                                <!-- Modal -->
                                                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="casesPendingSinceHMDays" aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel"> Pending With Registry</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                <!-- <button type="button" class="close closeButton" data-dismiss="modal" data-close="1" aria-label="Close"> -->
                                                                                <!-- <span aria-hidden="true">&times;</span> -->
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <span id="customErrorMessage" style="text-align: center">
                                                                                    <h3>Daywise Pendency Bifurcation</h3>
                                                                                    <!--<ul style="font-weight: bold;">
                                                                                        <li>Total : <?/*=$day_wise_pending[0]->total*/ ?></li>
                                                                                        <li><1 Day : <?/*=$day_wise_pending[0]->zero_day*/ ?></li>
                                                                                        <li>1 Day : <?/*=$day_wise_pending[0]->one_day*/ ?></li>
                                                                                        <li>2 Days : <?/*=$day_wise_pending[0]->two_day*/ ?></li>
                                                                                        <li>3 Days : <?/*=$day_wise_pending[0]->three_day*/ ?></li>
                                                                                        <li>>3 Days : <?/*=$day_wise_pending[0]->more_than_three_day*/ ?></li>
                                                                                    </ul>-->
                                                                                    <table border="1" width="100%">
                                                                                        <th style="text-align: center">Pending Since</th>
                                                                                        <th style="text-align: center">Total</th>
                                                                                        @if(isset($day_wise_pending))
                                                                                        <tr>
                                                                                            <td data-key="Pending Since">Total </td>
                                                                                            <td data-key="Total"> <?= $day_wise_pending[0]->total ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td data-key="Pending Since">1 Day </td>
                                                                                            <td data-key="Total"> <?= $day_wise_pending[0]->zero_day ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td data-key="Pending Since">>1 Day </td>
                                                                                            <td data-key="Total"> <?= $day_wise_pending[0]->one_day ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td data-key="Pending Since">2 Days </td>
                                                                                            <td data-key="Total"> <?= $day_wise_pending[0]->two_day ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td data-key="Pending Since">3 Days </td>
                                                                                            <td data-key="Total"> <?= $day_wise_pending[0]->three_day ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td data-key="Pending Since">>3 Days </td>
                                                                                            <td data-key="Total"> <?= $day_wise_pending[0]->more_than_three_day ?></td>
                                                                                        </tr>
                                                                                        @endif
                                                                                    </table>
                                                                                </span>
                                                                                <span id="stageWiseBifurcation" style="text-align: center">
                                                                                    <h3>Stagewise Pendency Bifurcation</h3>
                                                                                    <table border="1" width="100%">
                                                                                        <th style="text-align: center">Stage</th>
                                                                                        <th style="text-align: center">Total</th>
                                                                                        <!--<ul style="font-weight: bold;">-->
                                                                                        @if(isset($pendency_bifurcation))
                                                                                        <?php
                                                                                        foreach ($pendency_bifurcation as $data) {
                                                                                            echo "<tr><td data-key='Stage'>" . $data->admin_stage_name . "</td><td data-key='Total'>  " . $data->total . "</td></tr>";
                                                                                        }
                                                                                        ?>
                                                                                        @endif
                                                                                        <!--</ul>-->
                                                                                    </table>
                                                                                </span>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" data-close="1" class="btn btn-secondary closeButton" data-bs-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-4">
                                                                <div class="col-sm-12 col-md-4 col-lg-2">
                                                                    <div class="form-group  mb-3">
                                                                        <!--<label class="control-label col-sm-2"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </label>-->
                                                                        <!--<div class="col-sm-12">
                                                                            <div class="input-group">
                                                                                <center> <label class="control-label">Pending/Completed</label></center>
                                                                                <select name="status" id="status" class="uk-select filter_select_dropdown ddlstatus">
                                                                                    <option value="P" title="Select">Pendinhg</option>
                                                                                    <option value="C" title="Select">Completed</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>-->
                                                                        <div class="col-sm-12 col-xs-12" style="margin-top: 30px;">
                                                                            <div style="width: fit-content;padding-left: 10px;">
                                                                                <label class="radio-inline input-lg"><input type="radio" checked name="status_type" value="P"> Pending</label>
                                                                                <label class="radio-inline input-lg"><input type="radio" name="status_type" value="C"> Completed</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-md-4 col-lg-3">
                                                                    <div class="form-group  mb-3">
                                                                        <!-- <label class="control-label col-sm-4"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </label> -->
                                                                        <!-- <div class="col-sm-8"> -->
                                                                            <div class="input-group">
                                                                                 <label class="control-label">Search Stages</label>
                                                                                <select class="form-select cus-form-ctrl" name="stage_id" id="stage_id" aria-label="Default select example">
                                                                                    <?php
                                                                                    echo '<option  value="' . htmlentities(url_encryption('All'), ENT_QUOTES) . '" title="Select">All</option>';
                                                                                    foreach ($stage_list as $row) {
                                                                                        //$sel= ($row['stage_id']==2) ? 'selected=selected' : '';
                                                                                        $sel = '';
                                                                                        echo '<option ' . $sel . ' value="' . htmlentities(url_encryption($row->stage_id), ENT_QUOTES) . '">' . htmlentities(strtoupper($row->admin_stage_name), ENT_QUOTES) . '</option>';
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        <!-- </div> -->
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-md-4 col-lg-3 divdaterange">
                                                                    <div class="form-group  mb-3">
                                                                        <label class="control-label col-sm-4">
                                                                            <center>Date Range</center>
                                                                            <div class="checkbox" style="display: none; margin-top: 6px;">
                                                                                &nbsp;<label><input type="radio" value="All" name="ActionFiledOn">All</label><label><input type="radio" value="Action" name="ActionFiledOn">Action</label><label><input type="radio" value="FiledOn" name="ActionFiledOn" checked>Filed On</label>
                                                                            </div>
                                                                        </label>
                                                                        <div class="col-sm-12">
                                                                            <input class="form-control cus-form-ctrl diary_date" tabindex="2" id="listing_date_range" name="listing_date_range" placeholder="DD/MM/YYYY-DD/MM/YYYY" type="text" value="">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-md-4 col-lg-3">
                                                                    <div class="form-group  mb-3">
                                                                        <!-- <label class="control-label"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label> -->
                                                                        <!-- <div class="col-sm-8"> -->
                                                                            <div class="input-group">
                                                                               <label class="control-label">Filing Type</label>
                                                                                <select class="form-select cus-form-ctrl" aria-label="Default select example" name="filing_type_id" id="filing_type_id">
                                                                                    <?php
                                                                                    echo '<option  value="' . htmlentities(url_encryption('All'), ENT_QUOTES) . '" title="Select" selected="selected">All</option>';
                                                                                    foreach ($efiling_type_list as $row) {
                                                                                        echo '<option  value="' . htmlentities(url_encryption($row->id), ENT_QUOTES) . '">' . htmlentities(strtoupper(str_replace("_", " ", $row->efiling_type)), ENT_QUOTES) . '</option>';
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        <!-- </div> -->
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-md-4 col-lg-3">
                                                                    <div class="form-group mb-3">
                                                                        <!-- <label class="control-label col-sm-4"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label> -->
                                                                        <!-- <div class="col-sm-8"> -->
                                                                            <div class="input-group">
                                                                               <label class="control-label">User Types</label>
                                                                                <select class="form-select cus-form-ctrl" aria-label="Default select example" name="users_id" id="users_id">
                                                                                    <?php
                                                                                    echo '<option  value="' . htmlentities(url_encryption('All'), ENT_QUOTES) . '" title="Select" selected="selected">All</option>';
                                                                                    foreach ($users__types_list as $row) {
                                                                                        echo '<option  value="' . htmlentities(url_encryption($row->id), ENT_QUOTES) . '">' . htmlentities(strtoupper($row->user_type), ENT_QUOTES) . '</option>';
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        <!-- </div> -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br /><br />
                                                            <hr />
                                                            <div class="row">
                                                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-5">
                                                                    <div class="col-md-12 col-sm-12 col-xs-12" style="display: none;" id="loader_div">
                                                                        <img id="loader_img" style="position: fixed;left: 50%;margin-top: -50px;margin-left: -100px;" src="<?php echo base_url(); ?>/assets/images/loading-data.gif">
                                                                    </div>
                                                                    <div class="col-md-12 col-sm-12 col-xs-12" id="status_refresh">
                                                                    <div class="row">
                                                                        <div class="col-5">&nbsp;</div>
                                                                        <div class="start-buttons col-4">
                                                                            <!-- <input type="submit" id="Reportsubmit" name="add_notice" value="Search" class="quick-btn btn-primary loadDataReport"> -->
                                                                            <button type="submit" id="Reportsubmit" name="add_notice" class="quick-btn loadDataReport">Search</button>
                                                                            <button onclick="location.href = '<?php echo base_url('report/search'); ?>'" class="gray-btn quick-btn" type="reset">Reset</button>
                                                                        </div>                                                                       
                                                                    </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="tab-pane fade" id="nav-ShowEfilingRequests" role="tabpanel" aria-labelledby="nav-ShowEfilingRequests-tab">
                                                    <br />
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-4 col-lg-3">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label">E-Filing Number:</label>
                                                                <input class="form-control cus-form-ctrl" id="efiling_no" name="efiling_no" placeholder="E-Filing Number..." type="text">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-2 col-lg-1">
                                                            <div class="form-group mb-3">
                                                                <label for="" class="form-label">Select Year</label>
                                                                <select class="form-select cus-form-ctrl" aria-label="Default select example" id="efiling_year" name="efiling_year" style="width: 100%">
                                                                    <?php
                                                                    $end_year = 48;
                                                                    for ($i = 0; $i <= $end_year; $i++) {
                                                                        $year = (int) date("Y") - $i;
                                                                        $sel = ($year == ((int) date("Y"))) ? 'selected=selected' : '';
                                                                        echo '<option ' . $sel . ' value=' . $year . '>' . $year . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-4 col-lg-8">
                                                            <label for="" class="form-label">&nbsp;</label>
                                                            <!-- <input type="submit" id="SearchEfilingNumbersubmit" name="add_notice" value="Search" class="quick-btn gray-btn SearchEfilingNumbersubmit"> -->
                                                            <button type="submit" id="SearchEfilingNumbersubmit" name="add_notice" class="quick-btn gray-btn SearchEfilingNumbersubmit" style="margin-top: 3.5%;">Search</button>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="form-group">
                                                        <label class="control-label col-sm-4">E-Filing Number:</label>
                                                        <div class="col-sm-4">
                                                            <input class="form-control" id="efiling_no" name="efiling_no" placeholder="E-Filing Number..." type="text">
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="input-group">
                                                                <select tabindex='25' class="form-control input-sm filter_select_dropdown" id="efiling_year" name="efiling_year" style="width: 100%">
                                                                    <?php
                                                                    // $end_year = 48;
                                                                    // for ($i = 0; $i <= $end_year; $i++) {
                                                                    //     $year = (int) date("Y") - $i;
                                                                    //     $sel = ($year == ((int) date("Y"))) ? 'selected=selected' : '';
                                                                    //     echo '<option ' . $sel . ' value=' . $year . '>' . $year . '</option>';
                                                                    // }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <input type="submit" id="SearchEfilingNumbersubmit" name="add_notice" value="Search" class="btn btn-success SearchEfilingNumbersubmit">
                                                        </div>
                                                    </div> -->
                                                </div>
                                                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                                                    <div class="row mt-4">
                                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                                            <div>
                                                                <label class="radio-inline input-lg"><input type="radio" checked name="search_filing_type" value="register"> Registration No</label>
                                                                <label class="radio-inline input-lg"><input type="radio" name="search_filing_type" value="diary"> Diary Number</label>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                    <!--</center><br><hr>-->
                                                    <div class="card-body diary box" style="display: none; background-color: #ffffff; border-color: #ffffff;">
                                                        <div class="row mt-3">
                                                            <div class="col-md-2 col-sm-2 col-xs-12">
                                                                <div class="form-group">
                                                                    <!--<label class="control-label input-lg"> Diary No. <span style="color: red">*</span>:</label>-->
                                                                    <!--<label class="control-label col-md-3 col-sm-12 col-xs-12 input-lg"> Diary No. <span style="color: red">*</span>:</label>-->
                                                                    <!-- <div class="col-md-9 col-sm-12 col-xs-12">-->
                                                                    <label for="exampleInputEmail1"> Diary No. <span style="color: red">*</span>:</label>
                                                                    <div class="input-group">
                                                                        <input id="diary_no" name="diary_no" maxlength="10" placeholder="Diary No." class="form-control cus-form-ctrl age_calculate" type="text" required>
                                                                        <!-- <span class="input-group-addon" data-placement="bottom" data-toggle="popover" title="Diary number should be digit only.">
                                                                            <i class="fa fa-question-circle-o"></i>
                                                                        </span> -->
                                                                    </div>
                                                                    <!--</div>-->
                                                                </div>
                                                            </div>
                                                            <div class=" col-md-2 col-sm-2 col-xs-12 ">
                                                                <div class="form-group">
                                                                    <label for="exampleInputEmail1">Diary Year <span style="color: red">*</span>:</label>
                                                                    <!--<label class="control-label col-md-3 col-sm-12 col-xs-12 input-lg">Diary Year <span style="color: red">*</span>:</label>-->
                                                                    <!--<div class="col-md-9 col-sm-12 col-xs-12">-->
                                                                        <div class="input-group">
                                                                            <!--<input id="diary_year" name="diary_year" maxlength=4" onblur="checkNumber(this.id)" placeholder="Diary Year" class="form-control input-lg age_calculate" type="text" required>
                                                                            <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Case year should be digits only.">
                                                                                <i class="fa fa-question-circle-o"  ></i>
                                                                            </span>-->
                                                                            <select class="form-control cus-form-ctrl filter_select_dropdown" id="diary_year" name="diary_year" style="width: 100%">
                                                                                <option value="">Select Year</option>
                                                                                <?php
                                                                                $end_year = 48;
                                                                                for ($i = 0; $i <= $end_year; $i++) {
                                                                                    $year = (int) date("Y") - $i;
                                                                                    $sel = ($year == ((int) date("Y"))) ? 'selected=selected' : '';
                                                                                    echo '<option ' . $sel . ' value=' . $year . '>' . $year . '</option>';
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    <!--</div>-->
                                                                </div>
                                                            </div>
                                                            <div class=" col-md-4 col-sm-6 col-xs-12 ">
                                                                <div class="col-md-offset-5" style="display: none;" id="submitBtn_dynamicalayDiary" style="margin-top: 25px;">
                                                                    <input type="submit" id="SearchDiaryNumbersubmit" name="add_notice" value="Search" class="quick-btn gray-btn SearchDiaryNumbersubmit" style="margin-top: 4%;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--width: 714px;margin-left: 675px;     padding-left: 335px;;margin-top: -46px;-->
                                                    <div class="card-body register box">
                                                        <div class="row mt-3">
                                                            <div class="col-sm-12 col-md-4 col-lg-3">
                                                                <div class="form-group mb-3">
                                                                    <label for="exampleInputEmail1"> Case Type <span style="color: red">*</span>:</label>
                                                                    <!--<label class="control-label col-md-4 col-sm-12 col-xs-12 input-lg"> Case Type <span style="color: red">*</span>:</label>-->
                                                                    <!--<div class="col-md-8 col-sm-12 col-xs-12">-->
                                                                        <select name="sc_case_type" id="sc_case_type" class="form-control cus-form-ctrl filter_select_dropdown" style="width:100%;" required>
                                                                            <option value="" title="Select">Select Case Type</option>
                                                                            <?php
                                                                            $sel = '';
                                                                            if (isset($sc_case_type)) {
                                                                                foreach ($sc_case_type as $dataRes) {
                                                                                    // $sel = ($new_case_details[0]['sc_case_type_id'] == (string) $dataRes->casecode ) ? "selected=selected" : '';
                                                                                    ?>
                                                                                    <option <?php echo $sel; ?> value="<?php echo_data(url_encryption(trim($dataRes->casecode))); ?>"><?php echo_data($dataRes->casename); ?> </option>;
                                                                                    <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    <!--</div>-->
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12 col-md-4 col-lg-3">
                                                                <div class="form-group mb-3">
                                                                    <label for="exampleInputEmail1"> Case No. <span style="color: red">*</span>:</label>
                                                                    <div class="input-group">
                                                                        <input id="case_number" name="case_number" maxlength="10" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Case No." class="form-control cus-form-ctrl age_calculate" type="text" required>
                                                                        <!-- <span class="input-group-addon" data-placement="bottom" data-toggle="popover" title="Related case number should be digits only.">
                                                                            <i class="fa fa-question-circle-o"></i>
                                                                        </span> -->
                                                                    </div>
                                                                    <!--</div>-->
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12 col-md-4 col-lg-3">
                                                                <div class="form-group mb-3">
                                                                    <label for="exampleInputEmail1"> Case Year <span style="color: red">*</span>:</label>
                                                                    <div class="input-group">
                                                                        <select class="form-control cus-form-ctrl filter_select_dropdown" id="case_year" name="case_year" style="width: 100%">
                                                                            <option value="">Select Year</option>
                                                                            <?php
                                                                            $end_year = 48;
                                                                            for ($i = 0; $i <= $end_year; $i++) {
                                                                                $year = (int) date("Y") - $i;
                                                                                $sel = ($year == ((int) date("Y"))) ? 'selected=selected' : '';
                                                                                echo '<option ' . $sel . ' value=' . $year . '>' . $year . '</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <!--</div>-->
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12 col-md-4 col-lg-3" id="submitBtn_dynamicalayCase">
                                                                <!-- <input type="submit" id="SearchCaseNumbersubmit" name="add_notice" value="Search" class="quick-btn gray-btn SearchCaseNumbersubmit"> -->
                                                                <button type="submit" id="SearchCaseNumbersubmit" name="add_notice" class="quick-btn gray-btn SearchCaseNumbersubmit" style="margin-top: 5%;">Search</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br /> <br /> <br />
                                            <!--end akg-->
                                            <div class="col-sm-12 col-xs-12 tabcontent" id="ShowEfilingRequests"></div>
                                            <!--//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX-->
                                            <div class="panel panel-default tabcontent" id="ShowDiaryRequests">
                                                <?php
                                                // $attribute = array('class' => 'form-horizontal', 'id' => 'search_case_details_pdf', 'name' => 'search_case_details_pdf', 'autocomplete' => 'off','novalidate'=>'novalidate');
                                                // echo form_open('#', $attribute);
                                                ?>
                                                <!--<center>  <br>-->
                                                <?php // echo form_close(); ?>
                                            </div>
                                            <!--//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX-->
                                        </div>
                                    </div>
                                </div>
                                <!------------Table--------------------->
                                <div class="row mt-3">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="x_panel">
                                            <div class="x_title">
                                                <h3 id="divTitle"></h3>
                                            </div>
                                            <div class="table-sec"> 
                                                <div class="table-responsive" style="display: none;">
                                                    <table id="datatable-responsive" class="table table-striped custom-table table-bordered dt-responsive nowrap" cellspacing="0" width="100%" >
                                                        <thead>
                                                            <tr class="success input-sm" role="row">
                                                                <th width="6%">S.N0.</th>
                                                                <th width="10%">Filing No. <br> Filed On</th>
                                                                <th width="8%">Type</th>
                                                                <th width="8%">Diary No.</th>
                                                                <th width="25%">Causetitle</th>
                                                                <th width="14%">Filed By</th>
                                                                <th width="10%">Stages</th>
                                                                <th width="10%">Created On</th>
                                                                <th width="10%">Last Updated On</th>
                                                                <th width="14%">Allocated To</th>
                                                                <th width="14%">Pending Since Day(s)</th>
                                                                <th width="14%">Currently With</th>
                                                                <th width="14%">In Software</th>
                                                            </tr>
                                                        </thead>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    <script src="<?= base_url() ?>assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit.min.js"></script>
    <script src="<?= base_url() ?>assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit-icons.min.js"></script>
    <!-- Case Status modal-end-->
    <script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?= base_url() . 'assets' ?>/js/jquery.min.js"></script>
    <!-- <script src="<?= base_url() . 'assets' ?>/js/jquery-ui.min.js"></script> -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
    <!-- <link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery-ui.css"> -->
    <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script src="<?= base_url() ?>assets/js/sha256.js"></script>
    <script src="<?= base_url() ?>assets/js/daterangepicker/daterangepicker.min.js"></script>
    <script src="<?= base_url() ?>assets/js/daterangepicker/moment.min.js"></script>
    <script src="<?= base_url() ?>assets/js/daterangepicker/daterangepicker.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/daterangepicker/daterangepicker.css">
    <script src="<?= base_url() ?>assets/newAdmin/js/jquery.dataTables.min.js"></script>
    <!-- <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script> -->
    <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
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
        // document.getElementById("defaultOpen").click();
        document.getElementById("nav-tab").click();
    </script>
    <script>
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
            //alert("rounak mishra" + inputValue);
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
                /*var xx= '<input type="submit" id="SearchCaseNumbersubmit" name="add_notice" value="Searchfdhgfdh" class="btn btn-success SearchCaseNumbersubmit">';
                $('#submitBtn_dynamicalayDiary').hide();
                $('#submitBtn_dynamicalayCase').html(xx);*/
            } /*else if (inputValue == 'efilingNO'){
                $('#efiling_no').val('');
            }*/
            var targetBox = $("." + inputValue);
            $(".box").not(targetBox).hide();
            $(targetBox).show();
            /*if(inputValue == 'register'){
                var xx= '<input type="submit" id="SearchCaseNumbersubmit" name="add_notice" value="Search" class="btn btn-success SearchCaseNumbersubmit">';
                $('#submitBtn_dynamicalayDiary').hide();
                $('#submitBtn_dynamicalayCase').html(xx);
            }*/
        });
    </script>
    <script>
        $(document).ready(function() {
        /* $("#diary_no").autocomplete({
                source: "<//?php echo base_url();?>report/search/search_diary_no",
                minLength: 2,
                select: function(event, ui) {
                    $("#diary_no").val(ui.item.value);
                    var v=ui.item.value;
                    if(v){
                        $("#Reportsubmit").click();
                    }
                }
            });
            $("#efiling_no").autocomplete({
                source: "<//?php echo base_url();?>report/search/search_efiling_no",
                minLength: 2,
                select: function(event, ui) {
                    $("#efiling_no").val(ui.item.value);
                    //$("#Reportsubmit").submit();
                }
            });*/
            $('.diary_date').daterangepicker ({
            /* autoclose: true,
                autoApply:true,
                showDropdowns: true,
                useCurrent: false,
                locale: {
                    //format: 'YYYY-MM-DD HH:mm:ss',
                    format: 'YYYY-MM-DD',
                    separator: " to "
                }*/
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
                    format: 'DD-MM-YYYY hh:mm:ss A',
                    separator: " to "
                }
            });
            $('#listing_date_range').val('<?=$daterange;?>');
        });
    </script>
    <script>
        $(function() {
            $.fn.dataTable.ext.errMode = 'none';
            $("#datatable-responsive").DataTable({
                "responsive": false,
                "lengthChange": false,
                "autoWidth": true,
                "buttons": ["copy", "csv", "excel", {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    },
                    {
                        extend: 'colvis',
                        text: 'Show/Hide'
                    }
                ],
                "bProcessing": true,
                "extend": 'colvis',
                "text": 'Show/Hide',
                "createdRow": function(row, data, dataIndex) {
                    // Adding data-key attribute to each cell dynamically
                    $(row).find('td').eq(0).attr('data-key', 'S.N0.');
                    $(row).find('td').eq(1).attr('data-key', 'Filing No. ');
                    $(row).find('td').eq(2).attr('data-key', 'Type');
                    $(row).find('td').eq(3).attr('data-key', 'Diary No.');
                    $(row).find('td').eq(4).attr('data-key', 'Causetitle');
                    $(row).find('td').eq(5).attr('data-key', 'Filed By');
                    $(row).find('td').eq(6).attr('data-key', 'Stages');
                    $(row).find('td').eq(7).attr('data-key', 'Created On');
                    $(row).find('td').eq(8).attr('data-key', 'Last Updated On');
                    $(row).find('td').eq(9).attr('data-key', 'Allocated To');
                    $(row).find('td').eq(10).attr('data-key', 'Pending Since Day(s)');
                    $(row).find('td').eq(11).attr('data-key', 'Currently With');
                    $(row).find('td').eq(12).attr('data-key', 'In Software');
                },
            });

        });
        // $(document).ready(function() {
        //     $('#datatable-responsive').DataTable({
        //         dom: 'lBfrtip',
        //         buttons: [{
        //             extend: 'pdf',
        //             title: 'Report List',
        //             filename: 'Report_pdf_file_name',
        //             orientation: 'landscape',
        //             pageSize: 'LEGAL'
        //         }, {
        //             extend: 'excel',
        //             title: 'Report List',
        //             filename: 'Report_excel_file_name'
        //         }, {
        //             extend: 'csv',
        //             filename: 'Report_csv_file_name'
        //         }, {
        //             extend: 'print',
        //             title: 'Report List',
        //             filename: 'Report_print_file_name'
        //         }],
        //         "pageLength": 50,
        //     });
        // });
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
            //loadData(search_type,ActionFiledOn,listing_date,stage_id_Defult,filing_type_id_Defult,users_id_Defult,diary_no_Defult,diary_year_Defult,efiling_no_Defult,efiling_year_Defult);
            //$("#datatable-responsive").DataTable();
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
                    type: "POST",
                    url: "<?php echo base_url('report/search/Get_search_case_details_rpt'); ?>",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, case_type:sc_case_type_id, caseNo:case_number, caseYr:case_year},
                    async: false,
                    /*beforeSend: function () {
                        $('#search_sc_case').val('Please wait...');
                        $('#search_sc_case').prop('disabled', true);
                    },*/

                    success: function (resultData) { 
                    //    alert(resultData);
                        /* return;*/
                        var rdata = JSON.parse(resultData);
                    //    alert(rdata);

                        if(rdata[0]['diary_no']===''){
                       alert('record not found'); 
                       return false;
                        }
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
                // var diary_year=$('#diary_year').val();
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
                $('#table-responsive').show();
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
                    url:  "<?php echo base_url('report/search/actionFiledon'); ?>?DateRange="+date+"&ActionFiledOn=" +ActionFiledOn+"&stage_id=" + stage_id+"&filing_type_id=" + filing_type_id+"&users_id=" + users_id+"&diary_no=" + diary_no+"&diary_year=" + diary_year+"&efiling_no=" + efiling_no+"&efiling_year=" + efiling_year+"&search_type=" + search_type +"&status_type=" + status_type,
                    contentType: "text/plain",
                    // dataType: 'json',
                    beforeSend: function(){
                        $('#divTitle').html('Loading...');
                    },
                    success: function (data) {
                        data = JSON.parse(data);
                        var Report_fromDate_toDate=data.status.Report_fromDate_toDate;
                        if(ActionFiledOn !='All') {
                            //alert(Report_fromDate_toDate);
                        }
                        $('#divTitle').html(Report_fromDate_toDate);
                        myJsonData = data;
                        populateDataTable(myJsonData);
                    },
                    error: function (e) {
                        $('#divTitle').html('There was an error with your request...');
                    }
                });
            }
            // populate the data table with JSON data
            function populateDataTable(data) {
                $(".dictbldata").show();
                $('.table-responsive').show();
                $("#datatable-responsive").DataTable().clear();
                var table = $('#datatable-responsive').DataTable();
                table
                    .clear()
                    .draw();
                var diary_no_m=''; var diary_year_m=''; var open_case_status=''; var reg_no='';
                var length = Object.keys(data.customers).length;
                var redirect_url = ''; var efiling_no=''; var rd=''; var v=''; var res_name=''; var pet_name=''; var cause_title=''; var cause_details=''; var diary_no=''; var diary_year='';
                if(length != 0){
                for(var i = 0; i < length+1; i++) {
                    var sn=i+1;
                    var report = data.customers[i];
                    if(typeof report !== 'undefined' && report !== null){
                    var stage_id=report.stage_id;
                    var documentIANumber='';
                    var caveatNumber='';
                    if(report.cause_title!=null){
                        cause_title=report.cause_title; 
                    }
                    else if(report.ecase_cause_title!=null){
                        cause_title=report.ecase_cause_title; 
                    }
                    if(report.pet_name!=null){
                        pet_name=report.pet_name+'Vs.';
                    }
                    else{ 
                        pet_name=''; 
                    }
                    if(report.res_name!=null){
                        res_name=report.res_name;
                    } else{
                        res_name='';
                    }
                    diary_no='';diary_year='';reg_no='';
                    /* if(report.diary_no!=null){diary_no='<b class="sci">Diary No: </b>'+report.diary_no+'/'; diary_no_m=report.diary_no;}else if(report.sc_diary_num!=null){diary_no='<b class="class="sci"">Diary No: </b>'+report.sc_diary_num+'/'; diary_no_m=report.sc_diary_num;}
                    if(report.diary_year!=null){diary_year=report.diary_year+'<br/>'; diary_year_m=report.diary_year;}else if(report.sc_diary_year!=null){diary_year=report.sc_diary_year+'<br/>'; diary_year_m=report.sc_diary_year;}*/
                    if(report.diary_no!=null){
                        diary_no=''+report.diary_no+'/'; 
                        diary_no_m=report.diary_no;
                    }
                    else if(report.sc_diary_num!=null){
                        diary_no=''+report.sc_diary_num+'/'; 
                        diary_no_m=report.sc_diary_num;
                    }
                    if(report.caveat_num!=null){
                        caveatNumber='Caveat No.:'+report.caveat_num+'/'; 
                        diary_no_m=report.caveat_num;
                    }
                    if(report.icmis_docnum!=null){
                        documentIANumber='(ICMIS Doc./IA No.:'+report.icmis_docnum+'/'; 
                        diary_no_m=report.icmis_docnum;
                    }
                    if(report.diary_year!=null){
                        diary_year=report.diary_year+'<br/>'; 
                        diary_year_m=report.diary_year;
                    }
                    else if(report.sc_diary_year!=null){
                        diary_year=report.sc_diary_year+'<br/>'; 
                        diary_year_m=report.sc_diary_year;
                    }
                    if(report.caveat_year!=null){
                        caveatNumber+=report.caveat_year+'<br/>'; 
                        diary_year_m=report.caveat_year;
                    }
                    if(report.icmis_docyear!=null && report.icmis_docnum!=null){
                        documentIANumber+=report.icmis_docyear+')<br/>'; 
                        diary_year_m=report.icmis_docyear;
                    }
                    if (report.reg_no_display != '' && report.sc_display_num !=null) {
                        reg_no = '<b>Registration No.</b> : ' +report.sc_display_num+ '<br/> ';
                    } else {
                        reg_no = '';
                    }
                    if(diary_no_m !=null && diary_year_m !=null){
                        open_case_status='href="#" onClick="open_case_status()"';
                    }else{ 
                        open_case_status='';
                    }
                    //cause_details= '<a '+open_case_status+' data-diary_no="'+diary_no_m+'" data-diary_year="'+diary_year_m+'">'+'<span class="sci">'+diary_no + diary_year + reg_no + cause_title+'<br/>'+pet_name+res_name+'</span>'+'</a>';
                    case_no=diary_no + diary_year + reg_no +documentIANumber+caveatNumber;
                    if(report.efiling_type!='CAVEAT'){
                        cause_details= '<a '+open_case_status+' data-diary_no="'+diary_no_m+'" data-diary_year="'+diary_year_m+'">'+'<span class="sci">'+cause_title+'<br/>'+pet_name+res_name+'</span>'+'</a>';
                    }
                    else{
                        cause_details='';
                    }
                    if(report.efiling_type !='' && report.efiling_type=='new_case') {
                        rd='newcase.defaultController'; //. equal to / required
                        v='/'+report.registration_id + '/' + report.ref_m_efiled_type_id + '/' + report.stage_id + '/' + report.efiling_no;
                    }
                    else if(report.efiling_type !='' && report.efiling_type=='misc_document') {
                        rd='miscellaneous_docs.defaultController'; //. equal to / required
                        v='/'+report.registration_id + '/' + report.ref_m_efiled_type_id + '/' + report.stage_id;
                    }
                    else if(report.efiling_type !='' && report.efiling_type=='IA') {
                        rd='IA.defaultController'; //. equal to / required
                        v='/'+report.registration_id + '/' + report.ref_m_efiled_type_id + '/' + report.stage_id;
                    }
                    else if(report.efiling_type !='' && report.efiling_type=='CAVEAT') {
                        rd='case.caveat.crud'; //. equal to / required
                        v='/'+report.registration_id + '/' + report.ref_m_efiled_type_id + '/' + report.stage_id;
                    }

                    var str_efiling_type=report.efiling_type;
                    var efiling_type=str_efiling_type.replace("_"," ");
                    var filed_by='';
                    if(report.ref_m_usertype_id==1){
                        filed_by=report.first_name + '<br>(AOR Code: '+ report.aor_code + ')';
                    }else if(report.ref_m_usertype_id==2){
                        filed_by=report.first_name + '<br>(Party in person)';
                    }
                    var allocated_to='';

                    if(report.allocated_to_user!='' && report.allocated_to_user!=null && report.meant_for!='C' ){
                        allocated_to=report.allocated_to_user;
                    }
                    var create_on = report.create_on;
                    var create_on_date = create_on.split(' '); 
                    var create_on_onlytime =create_on_date[1];
                    var create_on_now = create_on_onlytime.split(':'); 
                    var create_on_hours = create_on_now[0];
                    var create_on_ampm = create_on_hours >= 12 ? 'pm' : 'am';
                    create_on = formatDate(create_on);
                    create_on=create_on+' '+create_on_onlytime;
                    var date = report.activated_on;
                    var from_date = date.split(' '); 
                    var onlytime =from_date[1];
                    var now = onlytime.split(':'); 
                    var hours = now[0];
                    var ampm = hours >= 12 ? 'pm' : 'am';
                    // date = formatDate('yy-mm-dd', date);
                    // date=date+' '+onlytime;
                    redirect_url = "<?=base_url('report/search/view/')?>"+rd+v;
                    var date_filed_on="";
                    if(report.filed_on==null){
                    }
                    else{
                        date_filed_on = report.filed_on;
                        var from_date = date_filed_on.split(' '); var onlytime =from_date[1];
                        var now = onlytime.split(':'); var hours = now[0];
                        var ampm = hours >= 12 ? 'pm' : 'am';
                        date_filed_on = $.datepicker.formatDate("dd/mm/yy", $.datepicker.parseDate('yy-mm-dd', date_filed_on));
                        date_filed_on=date_filed_on+' '+onlytime;
                    }
                    efiling_no= '<a href="'+redirect_url+'">'+ report.efiling_no + '</a> <br>'+date_filed_on+'';
                    var current_dt = new Date();
                    var diff = new Date(new Date(current_dt) - new Date(report.activated_on));
                    var peding_since=report.pending_since;
                    if(peding_since>2){
                        peding_since="<span style='color: red;'><b>"+peding_since+"</b></span>";
                    }
                    if (report.stage_id=='<?php echo I_B_Defects_Cured_Stage;?>' && (report.efiling_type !='' && report.efiling_type=='new_case') && ('<?php echo isset($_SESSION['login']) ? $_SESSION['login']['ref_m_usertype_id'] : '' ?>' == <?php echo USER_ADMIN?>)){
                        var sci_admin_stage_name=report.admin_stage_name; var title_TTS='Transfer To Scrutiny';
                         sci_admin_stage_name= sci_admin_stage_name+'<br/> <a href="javaScript:void(0)" class="btn btn-primary text-white SciTransferToScrutiny" style="padding:0px;" type="button" data-registration_id="'+report.registration_id+'" data-efiling_no="'+report.efiling_no+'" data-efiling_type="'+efiling_type+'" data-diary_no="'+report.sc_diary_num+'" data-diary_year="'+report.sc_diary_year+'" >'+'<span>'+title_TTS+'</span>'+'</a>';
                    }else{
                        var sci_admin_stage_name=report.admin_stage_name;
                    }
                    $('#datatable-responsive').dataTable().fnAddData( [                            
                        sn,
                        efiling_no,
                        efiling_type,
                        case_no,
                        cause_details,
                        filed_by,
                        // report.admin_stage_name,
                        sci_admin_stage_name,
                        create_on,
                        date,
                        allocated_to,
                        peding_since,
                        report.meant_for,
                        report.portal,
                    ]);
                    if(sn==length) {
                        $('#loader_div').hide();
                        $('#status_refresh').show();
                    }
                }
                }
                }
            }
        });
        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) 
                month = '0' + month;
            if (day.length < 2) 
                day = '0' + day;

            return [year, month, day].join('-');
        }
    </script>
    <script>
    function open_case_status(){
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var diary_no = $("a:focus").attr('data-diary_no');
        var diary_year = $("a:focus").attr('data-diary_year');
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,diary_no:diary_no, diary_year:diary_year},
            beforeSend: function (xhr) {
                $("#view_case_status_data").html("<div style='margin:0 auto;margin-top:20px;width:100%;text-align: center;'><img src='<?=base_url()?>/assets/images/loading-data1.gif'></div>");
            },
            url: "<?php echo base_url('report/search/showCaseStatusReport'); ?>",
            success: function (data) {
                document.getElementById('view_case_status_data').innerHTML=data;
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function (result) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
        UIkit.modal('#case_status').toggle();
    }
    </script>

    <script>
    //update fee and send to icmis
    $(document).on('click','.SciTransferToScrutiny',function(){
        var registration_id = $(this).attr('data-registration_id');
        var efiling_no = $(this).attr('data-efiling_no');
        var diary_no = $(this).attr('data-diary_no');
        var diary_year = $(this).attr('data-diary_year');
        var efiling_type = $(this).attr('data-efiling_type');
        if(registration_id){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var postData = {CSRF_TOKEN: CSRF_TOKEN_VALUE, registration_id: registration_id,efiling_no:efiling_no,diary_no:diary_no,diary_year:diary_year,efiling_type:efiling_type};
            $.ajax({
                type: "GET",
                data: postData,
                url: "<?php echo base_url('admin/EfilingAction/transferToScrutiny'); ?>",
                dataType:'json',
                ContentType: 'application/json',
                cache:false,
                beforeSend: function() {
                    $("#loader_div").html('<img id="loader_img" style="position: fixed; left: 63%;margin-top: -164px;  margin-left: -100px;" src="<?php echo base_url('assets/images/loading-data.gif');?>">');
                    $('#createDiaryNo').append('<i class="status_refresh fa fa-refresh fa-spin"></i>');
                },
                success: function (res){
                    if(typeof res == 'string'){
                        res = JSON.parse(res);
                    }
                    if(res.status == 'success'){
                        alert(res.message);
                        $(".loadDataReport").click();
                        /*setTimeout(function(){
                            alert(res.message);
                        },2000);*/

                        //window.location.reload();
                    }
                    else{
                        alert(res.message);
                        //window.location.reload();
                    }
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function () {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        }

    });
</script>
@endpush