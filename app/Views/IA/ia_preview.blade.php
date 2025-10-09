@if(getSessionData('login')['ref_m_usertype_id'] == USER_EFILING_ADMIN || getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN)
    @extends('layout.app')
    @section('content')
@endif 
<?php
$collapse_class = 'collapse';
$area_extended = false;
$hidepencilbtn='';
if (isset($ref_m_usertype_id) && !empty($ref_m_usertype_id) && $ref_m_usertype_id == USER_ADMIN && isset($stage_id) && !empty($stage_id) && $stage_id == Transfer_to_IB_Stage) {
    $collapse_class = 'collapse in';
    $area_extended = true;
}
$stages_array = array('', Draft_Stage, Initial_Defected_Stage, E_REJECTED_STAGE);
if(isset(getSessionData('efiling_details')['stage_id'])){
    if (!in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
        $hidepencilbtn = 'true';
    } else {
        $hidepencilbtn = 'false';
    }
}
?> 
            <div class="middleContent">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 sm-12 col-md-12 col-lg-12 middleContent-left">
                            <!-- Sec Start Here  -->
                            <?php if (isset($efiling_search_header)) { ?>
                                <style>
                                    .efiling_search {
                                        visibility: hidden !important;
                                    }
                                </style>
                                <?php
                            }
                            if (!isset($efiling_search_header)) {
                                render('IA.ia_breadcrumb', $details);
                            }
                            ?>
                            <div class="center-content-inner comn-innercontent">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                        <div class="tab-form-inner">
                                            <div class="row">
                                                <div style="float: right">
                                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
                                                    </div>
                                                    <button id="collapseAll" onclick="toggleAllAccordions()" class="btn btn-primary pull-right mb-3"> Collapse All </button>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="accordion view-accordion acrdion-with-edit" id="accordionExample">
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="headingOne">
                                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                                    Case Details
                                                                </button>
                                                            </h2>
                                                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <div class="x_panel">
                                                                        <?php render('case_details.case_details_view', ['case_details' => $case_details]); ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                       
                                                        <div class="accordion-item">
                                                            <div class="row">
                                                                <h2 class="accordion-header <?php if(isset($hidepencilbtn) && $hidepencilbtn != 'true') { ?> col-12 <?php } else { ?> col-sm-12 <?php } ?>" id="headingTwo">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" <?php echo $area_extended; ?>>
                                                                        <?php
                                                                        if (!isset($filing_for_details) || empty($filing_for_details)) { ?>
                                                                            <font style="color:red;"> <b>Filing For</b></font>
                                                                        <?php } else { ?> <b> Filing For</b><?php } ?>
                                                                        <?php
                                                                if ($hidepencilbtn != 'true') { ?>
                                                                    <!-- <div class="col-sm-1"> -->
                                                                        <a href="<?php echo base_url('on_behalf_of'); ?>"><i class="fa fa-pencil efiling_search"></i></a>
                                                                    <!-- </div> -->
                                                                <?php } ?>
                                                                    </button>
                                                                </h2>
                                                                
                                                            </div>
                                                            <div id="collapseTwo" class="accordion-collapse collapse <?php echo $collapse_class; ?>" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <?php render('on_behalf_of.filing_for_parties_list_view', ['filing_for_details' => $filing_for_details]); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="accordion-item">
                                                            <div class="row">
                                                                <h2 class="accordion-header <?php if(isset($hidepencilbtn) && $hidepencilbtn != 'true') { ?> col-sm-12 <?php } else { ?> col-sm-12 <?php } ?>" id="headingThree">
                                                                    <button class="accordion-button collapsed"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#collapseThree"
                                                                        aria-expanded="false"
                                                                        aria-controls="collapseThree" <?php echo $area_extended; ?>>
                                                                        <?php
                                                                        if (!isset($efiled_docs_list) || empty($efiled_docs_list)) { ?>
                                                                            <font style="color:red;"> <b>Documents</b></font>
                                                                        <?php } else { ?> <b>Documents</b><?php } ?>
                                                                        <?php
                                                                if ($hidepencilbtn != 'true') { ?>
                                                                    <!-- <div class="col-sm-1"> -->
                                                                        <a href="<?php echo base_url('documentIndex'); ?>"><i 
                                                                                class="fa fa-pencil efiling_search"></i></a>
                                                                    <!-- </div> -->
                                                                <?php } ?>
                                                                    </button>
                                                                </h2>
                                                                
                                                            </div>
                                                            <div id="collapseThree"
                                                                class="accordion-collapse collapse <?php echo $collapse_class; ?>"
                                                                aria-labelledby="headingThree"
                                                                data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <?php render('documentIndex.documentIndex_misc_ia_list_view', ['efiled_docs_list' => $efiled_docs_list, 'uploaded_docs' => $uploaded_docs]); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="accordion-item">
                                                            <div class="row">
                                                                <h2 class="accordion-header <?php if(isset($hidepencilbtn) && $hidepencilbtn != 'true') { ?> col-sm-12 <?php } else { ?> col-sm-12 <?php } ?>" id="headingFour">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour" <?php echo $area_extended; ?>>
                                                                        <?php if (!isset($payment_details) || empty($payment_details)) { ?><span style="color:red;"> <b>Fees Paid</b></span><?php } else { ?> <b>Fees Paid</b><?php } ?>
                                                                        <?php
                                                                if ($hidepencilbtn != 'true') { ?>
                                                                    <!-- <div class="col-sm-1"> -->
                                                                        <a href="<?php echo base_url('IA/courtFee'); ?>"><i class="fa fa-pencil efiling_search"></i></a>
                                                                    <!-- </div> -->
                                                                <?php } ?>
                                                                    </button>
                                                                </h2>
                                                                
                                                            </div>
                                                            <div id="collapseFour"
                                                                class="accordion-collapse collapse <?php echo $collapse_class; ?>"
                                                                aria-labelledby="headingFour"
                                                                data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <?php render('shcilPayment.payment_list_view', ['payment_details' => $payment_details]); ?>
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
                            <div class="row m-3">
                                <div class="col-md-12 text-center">
                                    <?php
                                    $final_submit_continue_action = '';
                                    $Array = array(Draft_Stage, Initial_Defected_Stage, DEFICIT_COURT_FEE, I_B_Defected_Stage, I_B_Rejected_Stage, E_REJECTED_STAGE);
                                    if (getSessionData('login')['ref_m_usertype_id'] == USER_ADVOCATE || getSessionData('login')['ref_m_usertype_id'] == USER_IN_PERSON) {
                                        if (in_array(getSessionData('efiling_details')['stage_id'], $Array)) {
                                            if (in_array(IA_BREAD_COURT_FEE, explode(',', getSessionData('efiling_details')['breadcrumb_status']))) {
                                                $_SESSION['efiling_details']['gras_payment_status'] = 'Y';
                                                if ((isset(getSessionData('efiling_details')['gras_payment_status']) &&    getSessionData('efiling_details')['gras_payment_status'] != 'P') ||
                                                    (isset(getSessionData('efiling_details')['gras_payment_status']) &&   getSessionData('efiling_details')['gras_payment_status'] == 'Y' && getSessionData('efiling_details')['payment_verified_by'] != NULL &&
                                                        (isset(getSessionData('efiling_details')['gras_payment_status']) &&   getSessionData('efiling_details')['is_payment_defecit'] == 't' || getSessionData('efiling_details')['is_payment_defective'] == 't')
                                                    )
                                                ) {
                                                    $final_submit_action = TRUE;
                                                    echo '<button type="button" class="quick-btn btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#FinalSubmitModal">Submit</button>';
                                                }
                                            }
                                            if (getSessionData('efiling_details')['stage_id'] == Draft_Stage) {
                                                $final_submit_continue_action = TRUE;
                                                ?>
                                                <!-- <a class="btn btn-danger btn-sm" onclick="ActionToTrash('UAT')">Trash</a> -->
                                                <?php
                                            }
                                        }
                                    }
                                    if (getSessionData('login')['ref_m_usertype_id'] == USER_DEPARTMENT) {
                                        if (in_array(getSessionData('efiling_details')['stage_id'], $Array)) {
                                            if (in_array(IA_BREAD_UPLOAD_DOC, explode(',', getSessionData('efiling_details')['breadcrumb_status']))) {
                                                if ((getSessionData('efiling_details')['gras_payment_status'] != 'P') ||
                                                    (getSessionData('efiling_details')['gras_payment_status'] == 'Y' && getSessionData('efiling_details')['payment_verified_by'] != NULL &&
                                                        (getSessionData('efiling_details')['is_payment_defecit'] == 't' || getSessionData('efiling_details')['is_payment_defective'] == 't')
                                                    )
                                                ) {
                                                    $final_submit_action = TRUE;
                                                    if (getSessionData('efiling_details')['stage_id'] == Draft_Stage) {
                                                        echo '<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#FinalSubmitModal">Submit</button>';
                                                    }
                                                }
                                            }
                                            if (getSessionData('efiling_details')['stage_id'] == Draft_Stage) {
                                                $final_submit_continue_action = TRUE;
                                            ?>
                                                <a class="btn btn-danger btn-sm" onclick="ActionToTrash('SLT')">Trash</a>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <!-- Sec End Here  -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- </div>  -->
@if(getSessionData('login')['ref_m_usertype_id'] == USER_EFILING_ADMIN || getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN)
    @endsection
@endif
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
    <script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script src="<?= base_url() ?>assets/js/sha256.js"></script>
    <script src="<?= base_url() ?>assets/newAdmin/js/jquery.dataTables.min.js"></script>
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
        $(document).ready(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url('newcase/Ajaxcalls/load_document_index'); ?>",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    res: 1
                },
                success: function(data) {
                    $('#index_data').html(data);
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    });
                },
                error: function() {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        });
    </script> 