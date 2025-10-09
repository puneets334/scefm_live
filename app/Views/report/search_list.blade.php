@extends('layout.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-section dashboard-tiles-area"></div>
            <div class="dashboard-section">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="dash-card">
                            <div class="title-sec">
                                <?php
                                // if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
                                if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN_READ_ONLY || $_SESSION['login']['ref_m_usertype_id'] == USER_EFILING_ADMIN) { ?>
                                    <h5 class="unerline-title">Report</h5>
                                <?php } else { ?>
                                    <h5 class="unerline-title">Search Cases</h5>
                                <?php } ?>
                                <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                            </div>
                            <div class="right_col" role="main">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div id="msg">
                                            <?php
                                            if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
                                                echo $_SESSION['MSG'];
                                            }
                                            unset($_SESSION['MSG']);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="x_panel">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="x_panel">
                                                    <div class="table-sec"> 
                                                        <div class="table-responsive">
                                                            <table id="datatable-responsive" class="table table-striped custom-table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr class="success input-sm" role="row">
                                                                        <th>S.N0.</th>
                                                                        <th>Filing No. <br> Filed On</th>
                                                                        <th>Type</th>
                                                                        <th>Diary No.</th>
                                                                        <th>Causetitle</th>
                                                                        <th>Filed By</th>
                                                                        <th>Stages</th>
                                                                        <th>Last Updated On</th>
                                                                        <th>Allocated To</th>
                                                                        <th>Pending Since Day(s)</th>
                                                                        <th>Currently With</th>
                                                                        <th>In Software</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $diary_no_m = '';
                                                                    $diary_year_m = '';
                                                                    $open_case_status = '';
                                                                    $reg_no = '';
                                                                    $length = count($report_list);
                                                                    $redirect_url = '';
                                                                    $efiling_no = '';
                                                                    $rd = '';
                                                                    $v = '';
                                                                    $res_name = '';
                                                                    $pet_name = '';
                                                                    $cause_title = '';
                                                                    $cause_details = '';
                                                                    $diary_no = '';
                                                                    $diary_year = '';
                                                                    $i = 1;
                                                                    foreach ($report_list as $report) {
                                                                        $stage_id = $report->stage_id;
                                                                        if ($report->cause_title != null) {
                                                                            $cause_title = $report->cause_title;
                                                                        } else if ($report->ecase_cause_title != null) {
                                                                            $cause_title = $report->ecase_cause_title;
                                                                        }
                                                                        if ($report->pet_name != null) {
                                                                            $pet_name = $report->pet_name . 'Vs.';
                                                                        } else {
                                                                            $pet_name = '';
                                                                        }
                                                                        if ($report->res_name != null) {
                                                                            $res_name = $report->res_name;
                                                                        } else {
                                                                            $res_name = '';
                                                                        }
                                                                        $diary_no = '';
                                                                        $diary_year = '';
                                                                        $reg_no = '';
                                                                        if ($report->diary_no != null) {
                                                                            $diary_no = '' . $report->diary_no . '/';
                                                                            $diary_no_m = $report->diary_no;
                                                                        } else if ($report->sc_diary_num != null) {
                                                                            $diary_no = '' . $report->sc_diary_num . '/';
                                                                            $diary_no_m = $report->sc_diary_num;
                                                                        }
                                                                        if ($report->diary_year != null) {
                                                                            $diary_year = $report->diary_year . '<br/>';
                                                                            $diary_year_m = $report->diary_year;
                                                                        } else if ($report->sc_diary_year != null) {
                                                                            $diary_year = $report->sc_diary_year . '<br/>';
                                                                            $diary_year_m = $report->sc_diary_year;
                                                                        }
                                                                        if ($report->reg_no_display != '' && $report->sc_display_num != null) {
                                                                            $reg_no = '<b>Registration No.</b> : ' . $report->sc_display_num . '<br/> ';
                                                                        } else {
                                                                            $reg_no = '';
                                                                        }
                                                                        if ($diary_no_m != null && $diary_year_m != null) {
                                                                            $open_case_status = 'href="#" onClick="open_case_status()"';
                                                                        } else {
                                                                            $open_case_status = '';
                                                                        }
                                                                        $case_no = $diary_no . $diary_year . $reg_no;
                                                                        if ($report->efiling_type != 'CAVEAT') {
                                                                            $cause_details = '<a ' . $open_case_status . ' data-diary_no="' . $diary_no_m . '" data-diary_year="' . $diary_year_m . '">' . '<span class="sci">' . $cause_title . '<br/>' . $pet_name . $res_name . '</span>' . '</a>';
                                                                        } else {
                                                                            $cause_details = '';
                                                                        }
                                                                        if ($report->efiling_type != '' && $report->efiling_type == 'new_case') {
                                                                            $rd = 'newcase.defaultController'; //. equal to / required
                                                                            $v = '/' . $report->registration_id . '/' . $report->ref_m_efiled_type_id . '/' . $report->stage_id . '/' . $report->efiling_no;
                                                                        } else if ($report->efiling_type != '' && $report->efiling_type == 'misc_document') {
                                                                            $rd = 'miscellaneous_docs.defaultController'; //. equal to / required
                                                                            $v = '/' . $report->registration_id . '/' . $report->ref_m_efiled_type_id . '/' . $report->stage_id . '/' . $report->efiling_no;
                                                                        } else if ($report->efiling_type != '' && $report->efiling_type == 'IA') {
                                                                            $rd = 'IA.defaultController'; //. equal to / required
                                                                            $v = '/' . $report->registration_id . '/' . $report->ref_m_efiled_type_id . '/' . $report->stage_id . '/' . $report->efiling_no;
                                                                        } else if ($report->efiling_type != '' && $report->efiling_type == 'CAVEAT') {
                                                                            $rd = 'case.caveat.crud'; //. equal to / required
                                                                            $v = '/' . $report->registration_id . '/' . $report->ref_m_efiled_type_id . '/' . $report->stage_id . '/' . $report->efiling_no;
                                                                        }
                                                                        $str_efiling_type = $report->efiling_type;
                                                                        $efiling_type = str_replace("_", " ", $str_efiling_type);
                                                                        $filed_by = '';
                                                                        if ($report->ref_m_usertype_id == 1) {
                                                                            $filed_by = $report->first_name . '<br>(AOR Code: ' . $report->aor_code . ')';
                                                                        } else if ($report->ref_m_usertype_id == 2) {
                                                                            $filed_by = $report->first_name . '<br>(Party in person)';
                                                                        }
                                                                        $allocated_to = '';
                                                                        //if($report->allocated_to_user!='' && $report->allocated_to_user!=null && $report->meant_for!='C' ){allocated_to=$report->allocated_to_user + '<br>(Emp Id: '+ $report->emp_id + ')';}
                                                                        if ($report->allocated_to_user != '' && $report->allocated_to_user != null && $report->meant_for != 'C') {
                                                                            $allocated_to = $report->allocated_to_user;
                                                                        }
                                                                        // $date = $report->activated_on;
                                                                        $date = !empty($report->activated_on) && ($report->activated_on != null) ? date('d-m-Y h:i:s A', strtotime($report->activated_on)) : '';
                                                                        $redirect_url = base_url('report/search/view/') . $rd . $v;
                                                                        $date_filed_on = "";
                                                                        if (isset($report->filed_on) && $report->filed_on != null) {
                                                                            $date_filed_on = isset($report->filed_on) ? $report->filed_on : '';
                                                                            $date_filed_on = !empty($date_filed_on) && ($date_filed_on != null) ? date('d-m-Y h:i:s A', strtotime($date_filed_on)) : '';
                                                                        }
                                                                        $efiling_no = '<a href="' . $redirect_url . '">' . $report->efiling_no . '</a> <br>' . $date_filed_on;
                                                                        $peding_since = get_count_days_FromDate_Todate($report->activated_on, date('Y-m-d')); //php function is working perfect
                                                                        if ($peding_since > 2) {
                                                                            $peding_since = "<span style='color: red;'><b>" . $peding_since . "</b></span>";
                                                                        }
                                                                        ?>
                                                                        <tr>
                                                                            <td data-key="S.N0."><?php echo $i++; ?> </td>
                                                                            <td data-key="Filing No. <br> Filed On"><?php echo $efiling_no; ?> </td>
                                                                            <td data-key="Type"><?php echo $efiling_type; ?> </td>
                                                                            <td data-key="Diary No."><?php echo $case_no; ?> </td>
                                                                            <td data-key="Causetitle"><?php echo $cause_details; ?> </td>
                                                                            <td data-key="Filed By"><?php echo $filed_by; ?> </td>
                                                                            <td data-key="Stages"><?php echo $report->admin_stage_name; ?> </td>
                                                                            <td data-key="Last Updated On"><?php echo $date; ?> </td>
                                                                            <td data-key="Allocated To"><?php echo $allocated_to; ?> </td>
                                                                            <td data-key="Pending Since Day(s)"><?php echo $peding_since; ?> </td>
                                                                            <td data-key="Currently With"><?php echo $report->meant_for; ?> </td>
                                                                            <td data-key="In Software"><?php echo $report->portal; ?> </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="case_status" uk-modal class="uk-modal-full">
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-full uk-close-large" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title"><div id="case_status_diary">CASE STATUS</div></h2>
        </div>
        <div class="uk-modal-body">
            <div id="view_case_status_data"></div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button" >Cancel</button>
        </div>
    </div>
</div>
@endsection
<!-- Case Status modal-start-->
<link rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/templates/uikit_scutum_2/assets/css/main.min.css" />
<link type="text/css" rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/frameworks/uikit_3-4-1/css/uikit.min.css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/templates/uikit_scutum_2/assets/css/fonts/mdi_fonts.css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/templates/uikit_scutum_2/assets/css/materialdesignicons.min.css" />
<script type="text/javascript" src="<?= base_url() ?>/assets/js/case_status/common.js"></script>
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet"> 
<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
<?php
// render('case_status.case_status_view');
?>
@push('script')
<script>
    $(document).ready(function() {
        $('#datatable-responsive').DataTable({
            dom: 'Bfrtip',
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
            }]

        });
    });
    function open_case_status() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var diary_no = $("a:focus").attr('data-diary_no');
        var diary_year = $("a:focus").attr('data-diary_year');
        $.ajax({
            type: "POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                diary_no: diary_no,
                diary_year: diary_year
            },
            beforeSend: function(xhr) {
                $("#view_case_status_data").html("<div style='margin:0 auto;margin-top:20px;width:100%;text-align: center;'><img src='<?= base_url() ?>/assets/images/loading-data1.gif'></div>");
            },
            url: "<?php echo base_url('report/search/showCaseStatusReport'); ?>",
            success: function(data) {
                document.getElementById('view_case_status_data').innerHTML = data;
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function(result) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
        UIkit.modal('#case_status').toggle();
    }
</script>
<style>
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
    div.box {
        height: 109px;
        /* padding: 10px; */
        /*overflow: auto;*/
        border: 1px solid #8080FF;
        /*background-color: #E5E5FF;*/
    }
</style>
@endpush