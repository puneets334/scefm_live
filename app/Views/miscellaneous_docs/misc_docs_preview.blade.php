<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
<?php if(!empty(getSessionData('login')) && (getSessionData('login')['ref_m_usertype_id'] == USER_EFILING_ADMIN || getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN)) { ?>
    @extends('layout.app')
    @section('content')
<?php } ?>

<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SC</title>
    <link rel="stylesheet" type="text/css" href="<?= base_url() . 'assets/newAdmin/' ?>css/jquery.dataTables.min.css">
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/font-awesome.min.css" rel="stylesheet">
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/animate.css" rel="stylesheet">
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/material.css" rel="stylesheet" />
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/style.css" rel="stylesheet">
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/fullcalendar.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
    <link href="<?= base_url() . 'assets' ?>/css/select2.min.css" rel="stylesheet">
</head>
<body>
<?php
if (!isset($efiling_search_header)) { ?> 
    @include('miscellaneous_docs.misc_docs_breadcrumb');
<?php } 
$stages_array = array('', Draft_Stage, Initial_Defected_Stage,  E_REJECTED_STAGE);
if (isset(getSessionData('efiling_details')['stage_id']) && !in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
    $hidepencilbtn = 'true';
} else {
    $hidepencilbtn = 'false';
}
$collapse_class = '';
?>
<div class="mainPanel">
    <div class="panelInner">
        <div class="middleContent">
            <div class="container-fluid">
                <div class="row">
                    <div class="tab-content">
                        <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="tab-form-inner">
                                <div class="row">
                                    <div style="float: right">
                                        <button id="collapseAll" onclick="toggleAllAccordions()" class="btn btn-primary pull-right mb-3"> Collapse All </button>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="accordion view-accordion acrdion-with-edit" id="accordionExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" data-bs-toggle="collapse" aria-controls="collapseOne"> Case Details </button>
                                                </h2>
                                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="x_panel">
                                                            <div class="row">
                                                                <div class="col-12 col-md-4 col-lg-4">
                                                                    <div class="form-group row">
                                                                        <label class="control-label col-3 text-right" for="filing_no"><b>Diary No. :</b> </label>
                                                                        <div class="col-9">
                                                                            <p> <?php echo_data(!empty($case_details[0]) ? $case_details[0]['diary_no'] . ' / ' . $case_details[0]['diary_year'] : ''); ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-4 col-lg-4">
                                                                    <div class="form-group row">
                                                                        <label class="control-label col-3 text-right" for="filing_no"><b>Registration No.:</b>
                                                                        </label>
                                                                        <div class="col-9">
                                                                            <p> <?php echo_data($case_details[0]['reg_no_display']); ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-4 col-lg-4">
                                                                    <div class="form-group row">
                                                                        <label class="control-label col-3 text-right" for="filing_no"><b>Cause Title :</b>
                                                                        </label>
                                                                        <div class="col-9">
                                                                            <p> <?php
                                                                                echo_data($case_details[0]['cause_title']);
                                                                                ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-4 col-lg-4">
                                                                    <div class="form-group row">
                                                                        <label class="control-label col-3 text-right" for="case_status"><b>Case Status:</b></label>
                                                                        <div class="col-9">
                                                                            <p> <?php echo $case_details[0]['c_status'] == 'D' ? 'Disposed' : 'Pending'; ?></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <div class="row">
                                                    <h2 class="accordion-header col-sm-12" id="headingTwo">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                            <?php
                                                            if (!isset($filing_for_details) || empty($filing_for_details)) { ?>
                                                                <font style="color:red;"><b>Filing For</b></font>
                                                            <?php } else { ?><b>Filing For</b><?php } ?>
                                                        </button>
                                                    </h2>
                                                    <?php
                                                    if ($hidepencilbtn != 'true') { ?>
                                                        <div class="col-sm-1">
                                                            <!-- <a href="<?php //echo base_url('on_behalf_of'); ?>"><i style="color:black; padding-top: 20px !important;" class="fa fa-pencil efiling_search"></i></a> -->
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div id="collapseTwo" class="accordion-collapse collapse <?php echo $collapse_class; ?>" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <?php render('on_behalf_of.filing_for_parties_list_view', @compact('filing_for_details')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <div class="row">
                                                    <h2 class="accordion-header col-sm-12" id="headingThree">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" <?php echo $collapse_class; ?>>
                                                            <?php
                                                            if (!isset($hidepencilbtn) || empty($hidepencilbtn)) { ?><font style="color:red;">
                                                                    <b>Documents 2</b>
                                                                </font><?php } else { ?>
                                                                <b>Documents</b><?php } ?>
                                                        </button>
                                                    </h2>
                                                    <div class="collapse" id="demo_9">
                                                        <div class="x_panel">
                                                            <?php if ($hidepencilbtn != 'true') { ?>
                                                                <div class="text-right"><a href="<?php echo base_url('documentIndex'); ?>"><i class="fa fa-pencil efiling_search"></i></a></div>
                                                            <?php } ?>
                                                            <?php render('documentIndex.preview_misc_doc_ia_index_doc_list'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="collapseThree" class="accordion-collapse collapse <?php echo $collapse_class; ?>" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <?php render('documentIndex.preview_misc_doc_ia_index_doc_list', ['efiled_docs_list' => $efiled_docs_list, 'uploaded_docs' => $uploaded_docs]); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <div class="row">
                                                    <h2 class="accordion-header col-sm-12" id="headingFour">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour" <?php echo $collapse_class; ?>>
                                                            <b>Fees Paid</b>
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="collapseFour" class="accordion-collapse collapse <?php echo $collapse_class; ?>" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <?php render('shcilPayment.payment_list_view', ['payment_details' => $payment_details]); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php render('modals'); ?>
                        </div>
                    </div>
                </div>
                <div class="row m-3">
                    <div class="col-md-12 text-center">
                        <?php $Array = array(Draft_Stage, Initial_Defected_Stage, DEFICIT_COURT_FEE, I_B_Defected_Stage, I_B_Rejected_Stage, E_REJECTED_STAGE);
                        $segment = service('uri');
                        $_SESSION['efiling_details']['gras_payment_status'] = 'Y';
                        if ($segment->getSegment(2) == 'view') {
                            if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON) {
                                if (in_array($_SESSION['efiling_details']['stage_id'], $Array)) {
                                    if (in_array(MISC_BREAD_COURT_FEE, explode(',', $_SESSION['efiling_details']['breadcrumb_status']))) {
                                        if ((isset($_SESSION['efiling_details']['gras_payment_status']) && $_SESSION['efiling_details']['gras_payment_status'] != 'P') ||
                                            (isset($_SESSION['efiling_details']['gras_payment_status']) && $_SESSION['efiling_details']['gras_payment_status'] == 'Y' && $_SESSION['efiling_details']['payment_verified_by'] != NULL &&
                                                (isset($_SESSION['efiling_details']['is_payment_defecit']) && $_SESSION['efiling_details']['is_payment_defecit'] == 't' || isset($_SESSION['efiling_details']['is_payment_defective']) && $_SESSION['efiling_details']['is_payment_defective'] == 't')
                                            )
                                        ) {
                                            // echo '<a href="' . base_url('miscellaneous_docs/FinalSubmit') . '" class="quick-btn">Final Submit</a>';
                                            // $finalButton = '<a href="' . base_url('miscellaneous_docs/FinalSubmit') . '" class="btn btn-success btn-sm">Final Submit</a>';
                                        } else {
                                            // echo 'f';
                                        }
                                    }
                                    if ($_SESSION['efiling_details']['stage_id'] == Draft_Stage) { ?>
                                        <!-- <a class="btn btn-danger btn-sm" onclick="ActionToTrash('UAT')">Trash</a> -->
                                        <!-- <a href="javascript:void(0)" class="quick-btn gradient-btn" onclick="ActionToTrash('UAT')">Trash</a> -->
                                        <?php
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- </div> -->
<!-- </div>
</div> -->
@if(isset($_SESSION['login']) && (getSessionData('login')['ref_m_usertype_id'] == USER_EFILING_ADMIN || getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN))
    @endsection
@endif
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/newAdmin/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
<script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/sha256.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url() . 'assets' ?>/js/jquery.validate.js"></script>
<script>
    function toggleAllAccordions() {
        var button = document.getElementById("collapseAll");
        var accordionHeaders = document.querySelectorAll(".accordion-header button");
        var accordionCollapses = document.querySelectorAll(".accordion-collapse");
        var isCollapsed = Array.from(accordionHeaders).some(function (header) {
            return !header.classList.contains("collapsed");
        });
        if (isCollapsed) {
            button.innerHTML = "Expand all";
            accordionHeaders.forEach(function (header) {
                header.classList.add("collapsed");
            });
            accordionCollapses.forEach(function (collapse) {
                collapse.classList.remove("show");
            });
        } else {
            button.innerHTML = "Collapse all";
            accordionHeaders.forEach(function (header) {
                header.classList.remove("collapsed");
            });
            accordionCollapses.forEach(function (collapse) {
                collapse.classList.add("show");
            });
        }
    }
</script>
</body>
</html> 