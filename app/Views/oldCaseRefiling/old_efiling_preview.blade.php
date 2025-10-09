
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SC</title>
	<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
    <!-- <link rel="shortcut icon" href="<?= base_url() . 'assets/newAdmin/' ?>images/favicon.gif"> -->
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
</head>
<?php if (!isset($efiling_search_header)) {
    render('oldCaseRefiling.old_efiling_breadcrumb');
}

$stages_array = array('', Draft_Stage, Initial_Defected_Stage,  E_REJECTED_STAGE);
if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
    $hidepencilbtn = 'true';
} else {
    $hidepencilbtn = 'false';
}
$hidepencilbtn = 'true';

?>

    <div class="panelInner">
        <div class="middleContent">
            <div class="container-fluid">
                <div class="row"> 
                        <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="tab-form-inner">
                                <div class="row">
                                    <div style="float: right" class="m-2">
                                        <?php
                                        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON) {
                                            $allowed_users_array = array(Initial_Approaval_Pending_Stage, I_B_Defects_Cured_Stage, Initial_Defects_Cured_Stage);
                                            if (in_array(getSessionData('efiling_details')['stage_id'], $allowed_users_array)) {
                                        ?>
                                                <a class="btn btn-success btn-sm" target="_blank" href="<?php echo base_url('acknowledgement/view'); ?>">
                                                    <i class="fa fa-download blink"></i> eFiling Acknowledgement
                                                </a>
                                        <?php
                                            }
                                        }
                                        ?>
                                        <button id="collapseAll" onclick="toggleAllAccordions()" class="btn btn-primary pull-right mb-3"> Collapse All </button>
                                        <!-- <a title="Click Here To View All Information" href="javascript:void(0);" class="btn btn-outline btn-primary btn-sm openall" style="float: right"><span class="fa fa-eye"></span>&nbsp;&nbsp; Expand All</a>
                                        <a title="Click Here To Close All Information" href="javascript:void(0);" class="btn btn-outline btn-info btn-sm closeall" style="float: right; "> <span class="fa fa-eye-slash"></span> Collapse All</a> -->
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="accordion" id="accordionExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" data-bs-toggle="collapse" aria-controls="collapseOne"> Case Details </button>
                                                </h2>
                                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    @include('case_details.case_details_view')
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" data-bs-toggle="collapse" aria-controls="collapseTwo"> Documents </button>
                                                </h2>
                                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    @include('documentIndex.preview_misc_doc_ia_index_doc_list')
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" data-bs-toggle="collapse" aria-controls="collapseThree"> Fees Paid </button>
                                                </h2>
                                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    @include('shcilPayment.payment_list_view')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 text-end">


                    <?php
                    $Array = array(Draft_Stage, Initial_Defected_Stage, DEFICIT_COURT_FEE, I_B_Defected_Stage, I_B_Rejected_Stage, E_REJECTED_STAGE);
                    if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON) {
                        if (in_array($_SESSION['efiling_details']['stage_id'], $Array)) {
                            // if (in_array(MISC_BREAD_AFFIRMATION, explode(',', $_SESSION['efiling_details']['breadcrumb_status']))) {
                            //  var_dump($_SESSION['efiling_details']['breadcrumb_status']);
                            $_SESSION['efiling_details']['gras_payment_status'] = 'Y';
                            if (in_array(MISC_BREAD_COURT_FEE, explode(',', $_SESSION['efiling_details']['breadcrumb_status']))) {

                                if (($_SESSION['efiling_details']['gras_payment_status'] != 'P') ||
                                    ($_SESSION['efiling_details']['gras_payment_status'] == 'Y' && $_SESSION['efiling_details']['payment_verified_by'] != NULL &&
                                        ($_SESSION['efiling_details']['is_payment_defecit'] == 't' || $_SESSION['efiling_details']['is_payment_defective'] == 't')
                                    )
                                ) {
                                    echo '<a href="' . base_url('oldCaseRefiling/FinalSubmit') . '" class="btn btn-success btn-sm text-center">Final Submit </a>';
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
 
<!-- <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="text-right">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right"> -->
<?php
// if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON) {
//     //if ($_SESSION['efiling_details']['stage_id'] != Draft_Stage) {
//     $allowed_users_array = array(Initial_Approaval_Pending_Stage, I_B_Defects_Cured_Stage, Initial_Defects_Cured_Stage);
//     if (in_array($_SESSION['efiling_details']['stage_id'], $allowed_users_array)) {
?>
<!-- <a class="btn btn-success btn-sm" target="_blank" href="<?php echo base_url('acknowledgement/view'); ?>">
                        <i class="fa fa-download blink"></i> eFiling Acknowledgement
                    </a> -->
<?php
//     }
// }
?>
<!-- </div>
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 visible-lg visible-md">
            <button class="btn btn-primary btn-sm openall" style="float: right">Expand All <i class="fa fa-eye"></i></button>
            <button class="btn btn-info btn-sm closeall hidden" style="float: right">Collapse All <i class="fa fa-eye-slash"></i></button>
        </div>
    </div>
    <div class="clearfix"></div> -->
<!--<div class="list-group-item1" style="background: #EDEDED; padding: 8px 6px 28px 10px; color: #555;" data-toggle="collapse" data-parent="#MainMenu">
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6"> 
            <b>Case Details</b>
        </div>-->
<!--<div class="col-lg-5 col-md-5 col-sm-6 col-xs-5 text-right">
            <?php
            /*            if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON) {
                if ($_SESSION['efiling_details']['stage_id'] != Draft_Stage) {
                    */ ?>
                    <a class="btn btn-success btn-xs" target="_blank" href="<?php /*echo base_url('acknowledgement/view'); */ ?>">
                        <i class="fa fa-download blink"></i> eFiling Acknowledgement
                    </a>
                    <?php
                    /*                }
            }
            */ ?>
        </div>
        <div class="col-lg-2 col-md-2 visible-lg visible-md" style="width: 22%;">
            <button class="btn btn-primary btn-xs openall" style="float: right">Expand All <i class="fa fa-eye"></i></button>
            <button class="btn btn-info btn-xs closeall hidden" style="float: right">Collapse All <i class="fa fa-eye-slash"></i></button> 
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" style="width: 3%;">
            <a href="#demo_1" class="list-group-item1" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu">
                <i class="fa fa-minus" style="float: right;"></i> <b> </a>
        </div>
    </div>-->


<!-- Code to change

    <div class="row">
                <div class="col-sm-11">
            <a href="#demo_7" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i><?php // if (!isset($subordinate_court_details) || empty($subordinate_court_details)) { 
                                                                                                                                                                                    ?><font style="color:orange;"> <b>Earlier Courts</b></font><?php // } else { 
                                                                                                                                                                                                                                                ?> <b>Earlier Courts</b><?php // } 
                                                                                                                                                                                                                                                                        ?></a>
            <div class="collapse" id="demo_7">
                <?php //render('newcase/subordinate_court_list'); 
                ?>
            </div>
                </div>
                <div class="col-sm-1" class="list-group-item" style="background: #EDEDED;" >
                    <a href="<?php //echo base_url('newcase/subordinate_court'); 
                                ?>"><i style="color:black; padding-top:10px;" class="fa fa-pencil"></i></a>
                </div>
            </div>

            end -->
<!-- <div class="x_panel" style="background: #EDEDED;">
        <div class="col-sm-11">
            <a href="#demo_1" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-minus" style="float: right;"></i> <b> Case Details</b></a>
            <div class="collapse in" id="demo_1">
                <div class="x_panel">
                    @include('case_details.case_details_view')
                </div>
            </div>
        </div> -->

<!-- <div class="row">
            <div class="col-sm-11">
                <a href="#demo_9" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i><?php if (!isset($efiled_docs_list) || empty($efiled_docs_list)) { ?><font style="color:red;"> <b>Documents</b></font><?php } else { ?> <b>Documents</b><?php } ?></a>
                <div class="collapse" id="demo_9">
                    <div class="x_panel">
                        <?php
                        //if ($hidepencilbtn != 'true') { 
                        ?>
                            <div class="text-right"><a href="<?php //echo base_url('uploadDocuments/DefaultController'); 
                                                                ?>"><i class="fa fa-pencil efiling_search"></i></a></div>
                        <?php //} 
                        ?>
                         {{-----@include('documentIndex.preview_misc_doc_ia_index_doc_list')---}}
                    </div>
                </div>
            </div>
            <?php
            if ($hidepencilbtn != 'true') { ?>
                <div class="col-sm-1" class="list-group-item" style="background: #EDEDED;">
                    <a href="<?php //echo base_url('uploadDocuments/DefaultController'); 
                                ?>"><i style="color:black; padding-top:10px;" class="fa fa-pencil efiling_search"></i></a>
                </div>
            <?php } ?>
        </div> -->


<!-- <div class="row">
            <div class="col-sm-11">
                <a href="#demo_10" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> -->
<?php if (!isset($payment_details) || empty($payment_details)) { ?>
    <font <!--style="color:red;" --> <!-- <b>Fees Paid</b> --></font><?php } else { ?> <!--  <b>Fees Paid</b> --><?php } ?>
</a>
<!-- <div class="collapse" id="demo_10">
                    <div class="x_panel">
                        <?php
                        if ($hidepencilbtn != 'true') { ?>
                            <div class="text-right"><a href="<?php //echo base_url('oldCaseRefiling/courtFee'); 
                                                                ?>"><i class="fa fa-pencil efiling_search"></i></a></div>
                        <?php } ?>
                        {{-----@include('shcilPayment.payment_list_view')--}}
                    </div>
                </div>
            </div>
            <?php
            if ($hidepencilbtn != 'true') { ?>
                <div class="col-sm-1" class="list-group-item" style="background: #EDEDED;">
                    <a href="<?php // echo base_url('oldCaseRefiling/courtFee'); 
                                ?>"><i style="color:black; padding-top:10px;" class="fa fa-pencil efiling_search"></i></a>
                </div>
            <?php } ?>
        </div>
    </div> -->

{{------------ @include('modals')-----}}
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
<script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/sha256.js"></script>
<script src="<?= base_url() . 'assets' ?>/newAdmin/js/jquery.dataTables.min.js"></script>
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