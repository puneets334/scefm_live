<?php
use SebastianBergmann\Type\FalseType;
$segment = service('uri');
$StageArray = !empty(getSessionData('breadcrumb_enable')['breadcrumb_status']) ? explode(',', getSessionData('efiling_details')['breadcrumb_status']) : array();
?>
<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/font-awesome.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/animate.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/material.css" rel="stylesheet" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/style.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/responsive.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/black-theme.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?= base_url() . 'assets/newAdmin/' ?>css/jquery.dataTables.min.css">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/fullcalendar.css" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<link href="<?= base_url() . 'assets' ?>/css/select2.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?=base_url();?>assets/css/sweetalert.css"> 
<?php
$disabled_status1='pointer-events: none; cursor: default;';
$allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT, JAIL_SUPERINTENDENT);
$allowed_users_trash = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
if((!empty(getSessionData('efiling_details')['stage_id']) && getSessionData('efiling_details')['stage_id']==DRAFT_STAGE) || (!empty(getSessionData('efiling_details')['stage_id']) && getSessionData('efiling_details')['stage_id']==INITIAL_DEFECTED_STAGE) || (!empty(getSessionData('efiling_details')['stage_id']) && getSessionData('efiling_details')['stage_id']== TRASH_STAGE)) {
    $efiling_num_label_for_display = 'DRAFT-';;
    $efiling_num_button_background_class = 'btn-danger';
} else{
    $efiling_num_label_for_display = '';
    $efiling_num_button_background_class = 'btn-success';
}
?>
<style>
    .obj_wrapper{
        min-height: 140px;
        max-height: 180px;
        background-color: #fff;
        border-collapse: separate;
        border: 1px solid #ccc;
        padding: 4px;
        box-sizing: content-box;
        box-shadow: rgba(0,0,0,.07451) 0 1px 1px 0 inset;
        overflow-y: scroll;
        outline: 0;
        border-radius: 3px;

    }
    .editor-wrapper {
        min-height: 200px !important;
        max-height: 250px;
    }
</style>
<div id="loader-wrapper" style="display: none;">
    <div id="loader"></div>
</div>
<div class="dash-card dashboard-section">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <div class=" dashboard-bradcrumb">
                <div class="left-dash-breadcrumb">
                    <div class="page-title">
                        <h5>
                            <i class="fa fa-file"></i>
                            <?php
                            echo "Caveat Request In A Case";
                            $filing_type = 'Caveat Request In A Case';
                            $filing_num_label=$lbl_history='';
                            $style = '';
                            if (!empty(getSessionData('efiling_details')['stage_id']) && ((getSessionData('efiling_details')['stage_id'] == DRAFT_STAGE) || (getSessionData('efiling_details')['stage_id'] == INITIAL_DEFECTED_STAGE) || (getSessionData('efiling_details')['stage_id'] == TRASH_STAGE))) {
                                $efiling_num_label_for_display = 'DRAFT-';
                                $efiling_num_button_background_class = 'btn-danger';
                                $style = 'color: red';
                            } else{
                                $efiling_num_label_for_display = '';
                                $efiling_num_button_background_class = 'btn-success';
                                $style = 'color: green';
                            }
                            ?>
                        </h5>
                    </div>
                    <div class="page-breifs">
                        <ul>
                            <li><a class="blue-dot"><span class="mdi mdi-record"></span> Active </a></li>
                            <li><a class="green-dot"> <span class="mdi mdi-record"></span> Done </a></li>
                            <li><a class="yellow-dot"> <span class="mdi mdi-record"></span> Optional </a></li>
                            <li><a class="red-dot"> <span class="mdi mdi-record"></span> Required </a></li>
                        </ul>
                    </div>
                </div>
                <div class="ryt-dash-breadcrumb">
                    <div class="btns-sec">
                        <?php
                        /* if (!empty(getSessionData('efiling_details')) && !empty(getSessionData('efiling_details')['efiling_no'])) {
                            echo '<a href="javascript::void(0); " class="btn '.$efiling_num_button_background_class.' btn-sm"  style="color: #ffffff"><strong id="copyTarget_EfilingNumber">' . $filing_num_label .$efiling_num_label_for_display. htmlentities(efile_preview(getSessionData('efiling_details')['efiling_no']), ENT_QUOTES) . '</strong></a> &nbsp;<strong id="copyButton" class="btn btn-danger btn-sm"  style="font-size: 14px;color:greenyellow;"><span class="glyphicon glyphicon-copy" style="font-size:14px;color:#ffffff;"></span></strong>';
                            echo '&nbsp; <a class="btn btn-default btn-sm" href="' . base_url('history/efiled_case/view') . '">' . $lbl_history . ' </a>';
                        }*/
                        $ref_m_usertype_id = !empty($_SESSION['login']['ref_m_usertype_id']) ? $_SESSION['login']['ref_m_usertype_id'] : NULL;
                        $stage_id = !empty($_SESSION['efiling_details']['stage_id']) ? $_SESSION['efiling_details']['stage_id'] : NULL;
                        $diary_generate_button ='';
                        if(isset($ref_m_usertype_id) && !empty($ref_m_usertype_id) && $ref_m_usertype_id == USER_ADMIN && isset($stage_id) && !empty($stage_id) && $stage_id == Transfer_to_IB_Stage){
                            $diary_generate_button = '<a href="javascript:void(0)"class="quick-btn"  data-efilingtype="caveat" id="generateDiaryNoPop" type="button" >Generate Caveat No.</a>';
                        }
                        $caveat_no ='';
                        if(isset($efiling_civil_data[0]['caveat_num']) && !empty($efiling_civil_data[0]['caveat_num'])
                            && isset($efiling_civil_data[0]['caveat_year']) && !empty($efiling_civil_data[0]['caveat_year'])){
                            $caveat_no = $efiling_civil_data[0]['caveat_num']. ' / '.$efiling_civil_data[0]['caveat_year'];
                        }
                        if (isset($diary_generate_button) && !empty($diary_generate_button) && empty($caveat_no)) {
                            echo $diary_generate_button;
                        }
                        $Array = array(New_Filing_Stage, Initial_Defects_Cured_Stage);
                        if (((getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN) || (getSessionData('login')['ref_m_usertype_id'] == USER_ACTION_ADMIN)) && in_array(getSessionData('efiling_details')['stage_id'], $Array)) {
                            if (isset($efiling_civil_data[0]['orgid']) && $efiling_civil_data[0]['orgid'] == '0' && $efiling_civil_data[0]['not_in_list_org'] == 't' || isset($efiling_civil_data[0]['resorgid']) && $efiling_civil_data[0]['resorgid'] == '0' && $efiling_civil_data[0]['res_not_in_list_org'] == 't') { ?>
                                <a class="btn btn-success" data-toggle="popover"  data-placement="bottom" data-content="Caveator organisation not added. OR Caveatee organisation not added. OR Extra Party organisation not added.">Approve<i class="fa fa-question-circle-o"></i></a>
                            <?php } else{  ?>
                                <a data-bs-toggle="modal" href="#approveModal" class="btn quick-btn success-btn btn-success btn-sm" style="background-color:#169F85;">Approve</a>
                            <?php } ?>
                            <a data-bs-toggle="modal" href="#disapproveModal" class="btn quick-btn success-btn btn-danger btn-sm" style="background-color:#C11900;">Disapprove</a>
                            <?php
                        } 
                        $idle_Array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
                        if(!empty((getSessionData('login')['ref_m_usertype_id']) && getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN || getSessionData('login')['ref_m_usertype_id'] == USER_MASTER_ADMIN) && in_array(getSessionData('efiling_details')['stage_id'], $idle_Array) && $segment->getSegment(3) == url_encryption('idle')) { ?>
                            <a data-bs-toggle="modal" href="#lodges_cases" class="btn quick-btn success-btn btn-success btn-sm" style="background-color:#169F85;">Make Idle</a>
                            <a data-bs-toggle="modal" href="#delete_lodges_cases" class="btn quick-btn success-btn btn-danger btn-sm" style="background-color:#169F85;">Make Idle & Delete</a>
                            <?php
                        }
                        $allowedUsers = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
                        $Array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage, I_B_Rejected_Stage, E_REJECTED_STAGE);
                        ?>
                        <?php
                        if (in_array(getSessionData('login')['ref_m_usertype_id'], $allowedUsers)) {
                            if ((!empty(getSessionData('efiling_details')['stage_id']) && getSessionData('efiling_details')['stage_id'] == Initial_Defected_Stage) || (!empty(getSessionData('efiling_details')['stage_id']) && getSessionData('efiling_details')['stage_id'] == I_B_Defected_Stage)) {
                                echo '<div class="col-md-8"><h5>Please ensure that you have cured the defects notified by admin. Then only proceed with final submit.</h5></div>';
                            }
                            if (in_array(!empty(getSessionData('efiling_details')['stage_id']), $Array)) {
                                if (getSessionData('login')['ref_m_usertype_id'] != USER_CLERK) {
                                    // Comment By Amit Mishra as
                                    if (in_array(CAVEAT_BREAD_COURT_FEE, explode(',', getSessionData('efiling_details')['breadcrumb_status'])) 
                                        && !in_array(CAVEAT_BREAD_VIEW, explode(',', getSessionData('efiling_details')['breadcrumb_status']))
                                ) {
                                        echo '<a href="#" class="btn quick-btn btn-sm" onclick="showLoader()">Final Submit</a>';
                                        // echo '<a href="' . base_url('efilingAction/Caveat_final_submit') . '" class="btn quick-btn btn-sm" onclick="showLoader()">Final Submit</a>';
                                    }     
                                    // if (getSessionData('efiling_details')['stage_id'] == Draft_Stage) { }
                                } elseif (getSessionData('login')['ref_m_usertype_id'] == USER_CLERK) {
                                    if (in_array(CAVEAT_BREAD_DOC_INDEX, explode(',', getSessionData('efiling_details')['breadcrumb_status']))) {
                                        $action = base_url('efilingAction/Caveat_final_submit');
                                        $attribute = array('name' => 'submit_adv_id', 'id' => 'submit_adv_id', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
                                    }
                                }
                            }
                        }
                        ?> 
                        <a href="javascript:void(0)" class="quick-btn pull-right" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                    </div>                    
                </div>                
            </div>            
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 ">
            <div class="crnt-page-head">
                <div class="current-pg-title">
                    <h6>Caveat Filing Form</h6>
                </div>                
                <div class="current-pg-actions">                
                    <?php                    						
                    if (isset(getSessionData('efiling_details')['efiling_no']) && !empty(getSessionData('efiling_details')['efiling_no'])) {
                        echo '<a href="javascript::void(0); " class="quick-btn gray-btn '.$efiling_num_button_background_class.'" style="'.$style.'"  id="copyTarget_EfilingNumber">' . $filing_num_label . $efiling_num_label_for_display.htmlentities(efile_preview(getSessionData('efiling_details')['efiling_no']), ENT_QUOTES) . '</a><strong id="copyButton" class="quick-btn btn btn-danger btn-sm"  style="font-size: 14px;color:greenyellow;"><span class="fa fa-copy" style="font-size:14px;color:#ffffff;"></span></strong>';
                        echo '&nbsp; <a class="quick-btn gray-btn" href="' . base_url('history/efiled_case/view ') . '">eFiling History</a>';						
                    }
                    $stages_array = array(Draft_Stage);
                    if (!empty(getSessionData('efiling_details')['stage_id']) && in_array(!empty(getSessionData('login')) && getSessionData('login')['ref_m_usertype_id'], $allowed_users_trash) && in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) { ?>
                        <a href="javascript:void(0)" class="quick-btn gradient-btn" onclick="ActionToTrash('UAT')">Trash</a>
                    <?php } ?>
                </div>
            </div>
            <div class="col-12">
                <?php echo !empty(getSessionData('efiling_details')['stage_id']) ? remark_preview(getSessionData('efiling_details')['registration_id'], getSessionData('efiling_details')['stage_id']) : ''; ?>
            </div>
        </div>
    </div>
</div>

<!-- <div class="form-response mt-2" role="alert" data-auto-dismiss="5000"> -->
    <?php
    // if (!empty(getSessionData('msg'))) {
    //     echo getSessionData('msg');
    // }
    ?>
<!-- </div> -->
<!-- <div class="form-response mt-2" role="alert" data-auto-dismiss="5000"> -->
    <?php
    // if (!empty(getSessionData('MSG'))) {
    //     echo getSessionData('MSG');
    // }
    ?>
<!-- </div> -->
<div class="alert alert-success text-center" style="display: none;" role="alert" id="successAlert" data-auto-dismiss="5000"></div>
<div class="alert alert-danger text-center" style="display: none;" role="alert" id="dangerAlert" data-auto-dismiss="5000"></div>
<!-- tabs-section -start  -->
<div class="dash-card dashboard-section tabs-section">
    <div class="tabs-sec-inner">
        <!-- form--start  -->
        <form action="">
            <?php 
            $final_submit_action = False;
            $StageArray = !empty(getSessionData('efiling_details')['breadcrumb_status']) ? explode(',', getSessionData('efiling_details')['breadcrumb_status']) : array();
            $breadcrumb_status=count($StageArray);
            $step=11;
            $p_r_type_petitioners='P';
            $p_r_type_respondents='R';
            $registration_id = !empty(getSessionData('efiling_details')['registration_id']) ? getSessionData('efiling_details')['registration_id'] : '';
            if (!empty(getSessionData('efiling_details')['no_of_petitioners'])) {
                $total_petitioners = get_extra_party_P_or_R($p_r_type_petitioners);
            } else{
                $total_petitioners=0;
            }
            if (!empty(getSessionData('efiling_details')['no_of_respondents'])) {
                $total_respondents = get_extra_party_P_or_R($p_r_type_respondents);
            } else{
                $total_respondents=0;
            }
            if(isset(getSessionData('efiling_details')['stage_id']) && getSessionData('efiling_details')['stage_id'] == Draft_Stage) {
                if (!empty(getSessionData('login')) && in_array(getSessionData('login')['ref_m_usertype_id'], array(USER_ADVOCATE, USER_IN_PERSON, USER_PDE)) ) {
                    $final_submit_action = TRUE;
                    $final_submit_continue_action = TRUE;                
                    $url_caveator = base_url('caveat');
                    $url_caveatee = base_url('caveat/caveatee');
                    $url_extra_party = base_url('caveat/extra_party');
                    $url_subordinate_court = base_url('caveat/subordinate_court');
                    $upload_doc_url = base_url('uploadDocuments');
                    $doc_index_url = base_url('documentIndex');
                    $url_case_courtfee = base_url('newcase/courtFee');
                }
            } elseif (!empty(getSessionData('efiling_details')['stage_id']) && !empty(getSessionData('login')) && (getSessionData('login')['ref_m_usertype_id'] ==  USER_CLERK && in_array(getSessionData('efiling_details')['stage_id'], array(Draft_Stage, Initial_Defected_Stage)))) {			
                $final_submit_action = False;
                $url_caveator = base_url('caveat');
                $url_caveatee = base_url('caveat/caveatee');
                $url_extra_party = base_url('caveat/extra_party');
                $url_subordinate_court = base_url('caveat/subordinate_court');
                $upload_doc_url = base_url('uploadDocuments');
                $doc_index_url = base_url('documentIndex');
                $url_case_courtfee = base_url('newcase/courtFee');                            
            } else{					
                $url_case_detail = $url_caveator = $url_caveatee = $url_extra_party = $url_subordinate_court = $upload_doc_url = $doc_index_url = $url_case_courtfee = '#';
            }
            if (isset(getSessionData('efiling_for_details')['case_type_pet_title']) && !empty(getSessionData('efiling_for_details')['case_type_pet_title'])) {
                $case_type_pet_title = htmlentities(getSessionData('efiling_for_details')['case_type_pet_title'], ENT_QUOTES);
            } elseif (isset($efiling_civil_data[0]['case_type_pet_title']) && !empty($efiling_civil_data[0]['case_type_pet_title'])) {
                $case_type_pet_title = htmlentities($efiling_civil_data[0]['case_type_pet_title'], ENT_QUOTES);
            } else{
                $case_type_pet_title = htmlentities('Caveator', ENT_QUOTES);
            }
            if (isset(getSessionData('efiling_for_details')['case_type_res_title']) && !empty(getSessionData('efiling_for_details')['case_type_res_title'])) {
                $case_type_res_title = htmlentities(getSessionData('efiling_for_details')['case_type_res_title'], ENT_QUOTES);
            } elseif (isset($efiling_civil_data[0]['case_type_res_title']) && !empty($efiling_civil_data[0]['case_type_res_title'])) {
                $case_type_res_title = htmlentities($efiling_civil_data[0]['case_type_res_title'], ENT_QUOTES);
            } else{
                $case_type_res_title = htmlentities('Caveatee', ENT_QUOTES);
            }
            $breadCrumbsArray = !empty(getSessionData('efiling_details')['breadcrumb_status']) ? explode(',', getSessionData('efiling_details')['breadcrumb_status']) : array();
            ?>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <?php
                    if ($segment->getSegment(1) == 'caveat' && $segment->getSegment(2) == '') {
                        $ColorCode = 'background-color: #01ADEF';
                        $status_color = 'active';
                    } elseif (in_array(CAVEAT_BREAD_CAVEATOR, $breadCrumbsArray)) {
                        $ColorCode = 'background-color: #169F85;color:#ffffff;';
                        $status_color = '';
                    } else{
                        $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
                        $status_color = '';
                    }
                    ?>
                    <a href="<?= $url_caveator ?>" class="nav-link <?php echo $status_color; ?> "  data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true"><span class="tab-num" style="<?php echo $ColorCode; ?>">1</span>Caveator</a>
                </li>
                <li class="nav-item" role="presentation">
                    <?php
                    if ($segment->getSegment(2) == 'caveatee') {  
                        $ColorCode = 'background-color: #01ADEF';
                        $status_color = 'active';
                        $disabled_status='';
                    } elseif (in_array(CAVEAT_BREAD_CAVEATEE, $breadCrumbsArray)) {
                        $ColorCode = 'background-color: #169F85;color:#ffffff;';
                        $status_color = '';
                        $disabled_status='';
                    } else{
                        $ColorCode = 'background-color: #C11900;color:#ffffff;';
                        $status_color = '';
                        $disabled_status='pointer-events: none; cursor: default;';
                    }
                    ?>
                    <a href="<?= $url_caveatee ?>" class="nav-link <?php echo $status_color; ?>"  style="<?php if(!in_array(NEW_CASE_CASE_DETAIL, $StageArray)){ echo $disabled_status; }?>" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="false"><span class="tab-num" style="<?php echo $ColorCode; ?>">2</span>Caveatee</a>
                </li>
                <li> 
                    <?php
                    if ($segment->getSegment(2)  == 'extra_party') {
                        $ColorCode = 'background-color: #01ADEF';
                        $status_color = 'first active';
                    } elseif (in_array(CAVEAT_BREAD_EXTRA_PARTY, $breadCrumbsArray)) {
                        $ColorCode = 'background-color: #169F85;color:#ffffff;';
                        $status_color = '';
                    } else{
                        $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
                        $status_color = '';
                    }
                    ?>
                    <!--<a href="<?/*= $url_extra_party */?>" class="<?php /*echo $status_color; */?>" style="z-index:6;"><span style="<?php /*echo $ColorCode; */?>">3</span> Extra Party </a>-->
                </li>
                <li class="nav-item" role="presentation"> 
                    <?php
                    if ($segment->getSegment(2) == 'subordinate_court') {
                        $ColorCode = 'background-color: #01ADEF';
                        $status_color = 'active';
                        $disabled_status='';
                    } elseif (in_array(CAVEAT_BREAD_SUBORDINATE_COURT, $breadCrumbsArray)) {
                        $ColorCode = 'background-color: #169F85;color:#ffffff;';
                        $status_color = '';
                        $disabled_status='';
                    } else{
                        $ColorCode = 'background-color: #C11900;color:#ffffff;';
                        $status_color = '';
                        $disabled_status='pointer-events: none; cursor: default;';
                    }
                    ?>
                    <a href="<?= $url_subordinate_court ?>" class="nav-link <?php echo $status_color; ?>"  data-bs-target="#home" type="button" role="tab" aria-controls="home" style="<?php if(!in_array(CAVEAT_BREAD_SUBORDINATE_COURT, $StageArray)) { echo $disabled_status; } ?>" aria-selected="false"><span class="tab-num" style="<?php echo $ColorCode; ?>">3</span>Earlier Courts </a>
                </li>
                <li class="nav-item" role="presentation">
                    <?php
                    if ($segment->getSegment(1) == 'uploadDocuments' || $segment->getSegment(1) == 'documentIndex') {
                        $ColorCode = 'background-color: #01ADEF';
                        $status_color = 'active';
                        $disabled_status='';
                    } elseif (in_array(CAVEAT_BREAD_UPLOAD_DOC, $StageArray)) {                        
                        $ColorCode = 'background-color: #169F85;color:#ffffff;';
                        $status_color = '';
                        $disabled_status='';
                    } else{
                        $ColorCode = 'background-color: #C11900;color:#ffffff;';
                        $status_color = '';
                        $disabled_status='pointer-events: none; cursor: default;';
                    }
                    ?>
                    <a href="<?= $upload_doc_url ?>" class="nav-link <?php echo $status_color; ?>" type="button" style="z-index:4; <?php if(!in_array(CAVEAT_BREAD_UPLOAD_DOC, $StageArray)) { echo $disabled_status; } ?>" aria-selected="false"><span class="tab-num" style="<?php echo $ColorCode; ?> ">4</span>Upload Document / Index </a>
                </li>
                <li class="nav-item" role="presentation">
                    <?php
                    if ($segment->getSegment(2) == 'courtFee') {
                        $ColorCode = 'background-color: #01ADEF';
                        $status_color = 'active';
                        $disabled_status='';
                    } elseif (in_array(CAVEAT_BREAD_COURT_FEE, $breadCrumbsArray)) {
                        $bgcolor='background-color: #169F85;';
                        $ColorCode = $bgcolor . ";color:#ffffff";
                        $status_color = '';
                        $disabled_status='';
                    } else{
                        $bgcolor='background-color: #C11900;';
                        $ColorCode = $bgcolor . ";color:#ffffff";
                        $status_color = '';
                        $disabled_status='pointer-events: none; cursor: default;';
                    }
                    ?>
                    <a href="<?= $url_case_courtfee ?>" class="nav-link <?php echo $status_color; ?>"  style="<?php if(!in_array(CAVEAT_BREAD_COURT_FEE, $StageArray)) { echo $disabled_status; } ?>" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="false"><span class="tab-num" style="<?php echo $ColorCode; ?>">5</span>Pay Court Fee </a>
                </li>
                <li class="nav-item" role="presentation">
                    <?php
                    if ($segment->getSegment(2) == 'view') {
                        $ColorCode = 'background-color: #01ADEF';
                        $status_color = 'first active';
                        $disabled_status='';
                    } elseif (in_array(CAVEAT_BREAD_CAVEATOR, $StageArray) && in_array(CAVEAT_BREAD_CAVEATEE, $breadCrumbsArray)  && in_array(CAVEAT_BREAD_DOC_INDEX, $breadCrumbsArray)) {
                        $ColorCode = 'background-color: #169F85;color:#ffffff';
                        $status_color = '';
                        $disabled_status='';
                    } else{
                        $ColorCode = 'background-color: #C11900;color:#ffffff';
                        $status_color = '';
                        $disabled_status='pointer-events: none; cursor: default;';
                    }
                    ?>
                    <a href="<?= base_url('caveat/view') ?>" class="nav-link <?php echo $status_color; ?>"  data-bs-target="#home" type="button" role="tab" aria-controls="home" style="<?php if(!in_array(NEW_CASE_RESPONDENT, $StageArray)){ echo $disabled_status1;}?>" aria-selected="false"><span class="tab-num" style="<?php echo $ColorCode; ?>">6</span>View</a>
                </li>
            </ul>
        </form>
        <div class="modal fade" id="FinalSubmitModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Note :-  </h4>
                    </div>
                    <div class="modal-body">
                        <br>
                        <?php if (!empty(getSessionData('login')) && getSessionData('login')['ref_m_usertype_id'] == USER_DEPARTMENT) { ?>
                            <ul>
                                <?php if ($final_submit_action && !$final_submit_continue_action) { ?>
                                    <li> Are you sure, you want to submit this case?</li>
                                    <?php
                                }
                                if ($final_submit_action) { ?>
                                    <li> Click on <b> Submit</b> to submit this case to Selected Panel Advocate.</li>
                                <?php } ?>
                                <br>
                            </ul>
                            <div class=" text-center">
                                <?php
                                $action = base_url('stage_list/final_submit');
                                $attribute = array('name' => 'submit_adv_panel', 'id' => 'submit_adv_panel', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
                                echo form_open($action, $attribute);
                                ?>
                                    <div class="col-md-6 col-sm-6 col-xs-10">
                                        <select id="advocate_list" name="advocate_list" class="form-control filter_select_dropdown " style="width: 100%"  >
                                            <option value="">Practicing On this establishment</option>
                                            <?php
                                            foreach ($dept_adv_panel_list as $adv_panel) {
                                                if ($advocate[0]['id'] == $adv_panel['user_id']) {
                                                    $sel = 'selected=selected';
                                                } else{
                                                    $sel = "";
                                                }
                                                if (!empty($adv_panel['last_name']) && $adv_panel['last_name'] != NULL && $adv_panel['last_name'] != 'NULL') {
                                                    $adv_last_name = ' ' . $adv_panel['last_name'];
                                                }
                                                ?>
                                                <option value="<?php echo url_encryption($adv_panel['user_id']); ?>" <?php echo $sel; ?>><?php echo htmlentities(strtoupper($adv_panel['first_name'] . $adv_last_name), ENT_QUOTES); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-10">
                                        <select id="advocate_list1" name="advocate_list1" class="form-control filter_select_dropdown" style="width: 100%"  >
                                            <option value="">Select Panel Advocate</option>
                                            <?php
                                            foreach ($dept_all_adv_panel_list as $adv_panel1) {
                                                if ($advocate[0]['id'] == $adv_panel1['user_id']) {
                                                    $sel = 'selected=selected';
                                                } else{
                                                    $sel = "";
                                                }
                                                if (!empty($adv_panel1['last_name']) && $adv_panel1['last_name'] != NULL && $adv_panel1['last_name'] != 'NULL') {
                                                    $adv_last_name = ' ' . $adv_panel1['last_name'];
                                                }
                                                ?>
                                                <option value="<?php echo url_encryption($adv_panel1['user_id']); ?>" <?php echo $sel; ?>><?php echo htmlentities(strtoupper($adv_panel1['first_name'] . $adv_last_name), ENT_QUOTES); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <input type="submit" class="btn btn-success" name="Submit" value="Submit">
                                <?php echo form_close(); ?>
                            </div>
                        <?php } else{ ?>
                            <ul>
                                <?php if ($final_submit_action && !$final_submit_continue_action) { ?>
                                    <li> Are you sure, you want to submit this case?</li>
                                    <?php
                                }
                                if ($final_submit_action && $final_submit_continue_action) { ?>
                                    <li> Click on <b> Final Submit</b> to submit this case to eFiling admin.</li>
                                    <li> Click on <b> Final Submit & File IA </b> to submit this case to eFiling admin and continue with IA filing in this case.</li> 
                                <?php } ?>
                                <br>
                            </ul>
                            <div class=" text-center">
                                <?php if ($final_submit_action) { ?>
                                    <a href="<?php echo base_url('stage_list/final_submit'); ?>" class="btn btn-success">Final Submit</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php } if ($final_submit_action && $final_submit_continue_action) { ?>
                                    <a href="<?php echo base_url('stage_list/final_submit_with_ia'); ?>" class="btn btn-info">Final Submit & File IA</a> 
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $pending_court_fee = empty(getPendingCourtFee()) ? 0 : getPendingCourtFee(); ?>
        <div class="modal fade" id="approveModal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title"><span class="fa fa-pencil"></span> Confirmation</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure to Approve this E-filing number ? </p>
                    </div>
                    <div class="modal-footer">
                        <a href="<?php echo base_url('admin/efilingAction'); ?>" class="quick-btn btn btn-primary">Yes</a>
                        <a href="#" class="gray-btn btn btn-secondary" data-bs-dismiss="modal">No</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="transferModal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title"><span class="fa fa-pencil"></span> Confirmation</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure to transfer this E-filing number to Sec-X? </p>
                    </div>
                    <div class="modal-footer">
                        <a href="<?php echo base_url('admin/EfilingAction/transferCase'); ?>" class="quick-btn btn btn-primary">Yes</a>
                        <a href="#" class="gray-btn btn btn-secondary" data-bs-dismiss="modal">No</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="disapproveModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            <span class="fa fa-pencil">&nbsp;&nbsp;</span><?php echo $lbl = (isset($_SESSION['efiling_details']) && $_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MENTIONING) ? "Write Orders" : "Write Reason to Disapprove"; ?> 
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <?php
                    $attribute = array('name' => 'disapp_case', 'id' => 'disapp_case', 'autocomplete' => 'off');
                    if (isset($_SESSION['efiling_details']) && $_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MENTIONING) {
                        echo form_open(base_url('admin/EfilingAction/submit_mentioning_order'), $attribute);
                    } else{
                        echo form_open(base_url('admin/EfilingAction/disapprove_case'), $attribute);
                    }
                    ?>
                        <div class="modal-body">
                            <div id="disapprove_alerts"></div>                
                            <div class="clearfix"><br></div>
                            <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor-one">
                                <div class="btn-group">
                                    <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                                    <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
                                </div>
                                <div class="btn-group">
                                    <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
                                    <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
                                </div>
                                <div class="btn-group">
                                    <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
                                    <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                                    <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
                                    <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
                                </div>
                                <div class="btn-group">
                                    <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
                                    <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
                                </div>
                            </div>
                            <div id="editor-one" class="editor-wrapper placeholderText disapprovedText" contenteditable="true"></div>
                            <textarea name="remark" id="descr" style="display: none;"></textarea>
                            <span id="disapprove_count_word" style="float:right"></span>
                            <div class="clearfix"><br></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="quick-btn btn btn-primary" id="disapprove_me">Submit</button>
                            <button type="button" class="gray-btn btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    <?php echo form_close(); ?>  
                </div>
            </div>
        </div>
        <!-- mark as error start-->
        <div class="modal fade" id="markAsErrorModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            <span class="fa fa-pencil"></span>Write reason to mark as error.
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <?php
                    $attribute = array('name' => 'mark_as_error', 'id' => 'mark_as_error', 'autocomplete' => 'off');
                    echo form_open(base_url('admin/EfilingAction/markAsErrorCase'), $attribute);
                    ?>
                        <div class="modal-body">
                            <div id="disapprove_alerts"></div>
                            <div class="clearfix"><br></div>
                            <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor-one">
                                <div class="btn-group">
                                    <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                                    <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
                                </div>
                                <div class="btn-group">
                                    <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
                                    <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
                                </div>
                                <div class="btn-group">
                                    <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
                                    <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                                    <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
                                    <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
                                </div>
                                <div class="btn-group">
                                    <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
                                    <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
                                </div>
                            </div>
                            <div id="editor-one" class="editor-wrapper placeholderText disapprovedText" contenteditable="true"></div>
                            <textarea name="remark" id="descr" style="display: none;"></textarea>
                            <span id="disapprove_count_word" style="float:right"></span>
                            <div class="clearfix"><br></div>
                        </div>
                        <div class="modal-footer">
                            <a class="quick-btn btn btn-primary" id="markaserror">Submit</a>
                            <button type="button" class="gray-btn btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
        <!-- mark as error end-->
        <div class="modal fade" id="DeficitCourtFeeModal" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            <span class="fa fa-pencil"></span> Deficit Fee
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <?php
                    $attribute = array('name' => 'deficit_court_fees', 'id' => 'deficit_court_fees', 'autocomplete' => 'off');
                    echo form_open('admin/deficit_court_fees', $attribute);
                    ?>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label col-sm-4 input-sm">Deficit Fees to be paid<span style="color: red">*</span>:</label>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <input class="form-control" name="court_fees" id="court_fees" maxlength="6" required placeholder="Enter court fee" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="quick-btn btn btn-primary" value="Submit" name="submit">
                            <button type="button" class="gray-btn btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    <?php echo form_close(); ?>  
                </div>
            </div>
        </div>
        <div class="modal fade" id="generateDiaryNoDiv" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><span class="fa fa-pencil"></span> Check All Details </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="checkAllSections"></div>
                    <div class="col-md-12 col-sm-12 col-xs-12" id="loader_div"></div>
                    <a data-efilingType="<?php echo strtolower($filing_type);?>" class="btn btn-primary" id="createDiaryNo" type="button" style="margin-left: 224px;margin-bottom: 23px;">Generate Diary No.</a>
                </div>
            </div>
        </div>
        <div class="modal fade" id="holdModal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><span class="fa fa-pencil"></span> Confirmation</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure to Hold this E-filing number ? </p>
                    </div>
                    <div class="modal-footer">
                        <a href="<?php echo base_url('admin/efilingAction/hold'); ?>" class="quick-btn btn btn-primary">Yes</a>
                        <a href="#" class="gray-btn btn btn-secondary" data-bs-dismiss="modal">No</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="disposedModal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><span class="fa fa-pencil"></span> Confirmation</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure to Disposed this E-filing number ? </p>
                    </div>
                    <div class="modal-footer">
                        <a href="<?php echo base_url('admin/efilingAction/disposed'); ?>" class="quick-btn btn btn-primary">Yes</a>
                        <a href="#" class="gray-btn btn btn-secondary" data-bs-dismiss="modal">No</a>
                    </div>
                </div>
            </div>
        </div>
    <!-- </div>
</div> -->
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
<script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/sha256.js"></script>
<script src="<?= base_url() ?>assets/newAdmin/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url() . 'assets' ?>/js/jquery.validate.js"></script>    
<script src="<?=base_url();?>assets/js/sweetalert.min.js"></script>
<script>
    function ActionToTrash(trash_type) {       
        event.preventDefault();
        var trash_type =trash_type;
        var url="";
        if (trash_type=='') {
            swal("Cancelled", "Your imaginary file is safe :)", "error");
            return false;
        } else if (trash_type=='UAT') {
            url="<?php echo base_url('userActions/trash'); ?>";
        } else if (trash_type=='SLT') {
            url="<?php echo base_url('stage_list/trash'); ?>";
        } else if (trash_type=='EAT') {
            url="<?php echo base_url('userActions/trash'); ?>";
        } else {
            swal("Cancelled", "Your imaginary file is safe :)", "error");
            return false;
        }
        // alert('trash_type'+trash_type+'url='+url);//return false;
        swal({
            title: "Do you really want to trash this E-Filing,",
            text: "once it will be trashed you can't restore the same.",
            type: "warning",
            position: "top",
            showCancelButton: true,
            confirmButtonColor: "green",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            buttons: ["Make Changes", "Yes!"],
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm){
            if (isConfirm) {  // submitting the form when user press yes
                var link = document.createElement("a");
                link.href = url;
                link.target = "_self";
                link.click();
                swal({ title: "Deleted!",text: "E-Filing has been deleted.",type: "success",timer: 2000 });
            } else {
                //swal({title: "Cancelled",text: "Your imaginary file is safe.",type: "error",timer: 1300});
            }
        });
    }
    document.getElementById("copyButton").addEventListener("click", function() {
        copyToClipboardMsg(document.getElementById("copyTarget_EfilingNumber"), "copyButton");
    });
    // document.getElementById("copyButton2").addEventListener("click", function() {
    //     copyToClipboardMsg(document.getElementById("copyTarget2"), "copyButton");
    // });
    document.getElementById("pasteTarget").addEventListener("mousedown", function() {
        this.value = "";
    });
    function copyToClipboardMsg(elem, msgElem) {
        var EfilingNumber = document.getElementById('copyTarget_EfilingNumber').innerHTML;
        var succeed = copyToClipboard(elem);
        var msg;
        if (!succeed) {
            msg = "Copy not supported or blocked.  Press Ctrl+c to copy."
        } else {
            //msg = 'E-Filing Number: ' +EfilingNumber+' Copied.';
            msg = 'Copied';
        }
        if (typeof msgElem === "string") {
            msgElem = document.getElementById(msgElem);
        }
        msgElem.innerHTML = msg;
        /* setTimeout(function() {
            msgElem.innerHTML = "";
        }, 5000);*/
    }
    function copyToClipboard(elem) {
        // create hidden text element, if it doesn't already exist
        var targetId = "_hiddenCopyText_";
        var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA" || elem.tagName === "INPUT";
        var origSelectionStart, origSelectionEnd;
        if (isInput) {
            // can just use the original source element for the selection and copy
            target = elem;
            origSelectionStart = elem.selectionStart;
            origSelectionEnd = elem.selectionEnd;
        } else {
            // must use a temporary form element for the selection and copy
            target = document.getElementById(targetId);
            if (!target) {
                var target = document.createElement("textarea");
                target.style.position = "absolute";
                target.style.left = "-9999px";
                target.style.top = "0";
                target.id = targetId;
                document.body.appendChild(target);
            }
            target.textContent = elem.textContent;
        }
        // select the content
        var currentFocus = document.activeElement;
        target.focus();
        target.setSelectionRange(0, target.value.length);
        // copy the selection
        var succeed;
        try {
            succeed = document.execCommand("copy");
        } catch (e) {
            succeed = false;
        }
        // restore original focus
        if (currentFocus && typeof currentFocus.focus === "function") {
            currentFocus.focus();
        }
        if (isInput) {
            // restore prior selection
            elem.setSelectionRange(origSelectionStart, origSelectionEnd);
        } else {
            // clear temporary content
            target.textContent = "";
        }
        return succeed;
    }
    function get_objection(obj_id, obj_checked) {
        if (obj_checked.is(':checked')) {
            var obj = $("#obj_" + obj_id).text();
        }
        if (obj_checked.is(':unchecked')) {
            var uncheck = $("#obj_" + obj_id).text() + "<br><br>";
        }
        $('#objection_value').append(obj + "<br><br>");
        var objection = $('#objection_value').html();
        if (objection.indexOf(uncheck) != -1) {
            objection = objection.replace(uncheck, '');
            $('#objection_value').html(objection);
        } else {
            $('#objection_value').html(objection);
        }
    }
    $(document).ready(function () {
        // $(document).on("click", "#generateDiaryNoPop", function () {
        //     var sectionDetails ='';
        //     var sectionData ='';
        //     $("#checkAllSections").html('');
        //     $('ul.nav-breadcrumb li').each(function(i){
        //         i= i+1;
        //         var sectionDetails = $(this).text().trim();
        //         var sectionArr = sectionDetails.split(i);
        //         var name = sectionArr[1].trim().replace(/[-' ']/g,'_').toLowerCase();
        //         if(name == 'extra_party' || name == 'legal_representative'){
        //             sectionData +='<div class="row"><div class="col-sm-4">'+sectionDetails+'</div>' +
        //                 '<div class="col-sm-4"><input class="checkSection" type="radio" id="'+name+'" name="'+name+'" value="1"> <label for="'+name+'"> YES</label><span id="error_'+name+'"></span></div> ' +
        //                 '<div class="col-sm-2"><input  class="checkSection" type="radio" id="'+name+'1" name="'+name+'" value="0"> <label for="'+name+'1"> NO</label></div>' +
        //                 '<div class="col-sm-2"><input class="checkSection" type="radio" id="'+name+'2" name="'+name+'" value="2"> <label for="'+name+'2"> N/A</label></div></div>';
        //         }
        //         else{
        //             sectionData +='<div class="row"><div class="col-sm-4">'+sectionDetails+'</div>' +
        //                 '<div class="col-sm-4"><input class="checkSection" type="radio" id="'+name+'" name="'+name+'" value="1"> <label for="'+name+'"> YES</label><span id="error_'+name+'"></span></div> ' +
        //                 '<div class="col-sm-4"><input class="checkSection" type="radio" id="'+name+'1" name="'+name+'" value="0"> <label for="'+name+'1"> NO</label></div></div>';
        //         }
        //
        //          if(name == 'view' || name =='affirmation'){
        //          }
        //          else{
        //              $("#checkAllSections").append(sectionData);
        //          }
        //         sectionData = '';
        //     });
        // });
        // $(document).on('click','#createDiaryNo',function(){
        $(document).on('click','#generateDiaryNoPop',function(){
            var pending_court_fee='<?=$pending_court_fee?>';
            // if(pending_court_fee>0){
            //     var court_fee_payment_details = $('#is_verified').val();
            //     var result = court_fee_payment_details.split('#');
            //     if ((result[0] ==='f' && result[1]>0 && pending_court_fee > 0)){
            //         alert('Please verify court fee before generating diary number.');
            //         return false;
            //     }
            // }
            /*var court_fee_payment_details = $('#is_verified').val();
            var result = court_fee_payment_details.split('#');
            if ((result[0] ==='f' && result[1]>0 && pending_court_fee > 0)){
                alert('Please verify court fee before generating diary number.');
                return false;
            }*/
            var noError = true;
            var arr = [];
            var stepValue =[];
            var filteredData = [];
            var typeGeneration ='';
            var file_type = $(this).attr('data-efilingtype');
            // $('.checkSection').each(function(){
            //     var name = $(this).attr('name');
            //     var fieldValue =  $('input[name="'+name+'"]:checked').val();
            //     if(typeof fieldValue == 'undefined'){
            //         noError = false;
            //         arr.push(name);
            //     } else{
            //         if(fieldValue =='0'){
            //             $("#error_"+name).text('Select this field');
            //             $("#error_"+name).css( { "margin-left" : "6px", "color": "red"} );
            //             noError = false;
            //         } else{
            //             $("#error_"+name).text('');
            //             var checkObject = {};
            //             checkObject.field_name = name;
            //             checkObject.field_value=fieldValue;
            //             stepValue.push(checkObject);
            //             checkObject = {};
            //         }
            //     }
            // });
            // var arr = arr.filter(uniqueArr);
            // const  keys = ['field_name'];
            //  const  filteredData = stepValue.filter((s => o =>(k => !s.has(k) && s.add(k))
            //             (keys.map(k => o[k]).join('|')))
            //          (new Set)
            //      );
            //  if(arr){
            //      for(var i=0;i<arr.length;i++){
            //          $("#error_"+arr[i]).text('Select this field');
            //          $("#error_"+arr[i]).css( { "margin-left" : "6px", "color": "red"} );
            //          noError = false;
            //      }
            //  }
            if(file_type == 'new_case'){
                filteredData.push({field_name: "case_detail", field_value: "1"});
                filteredData.push({field_name: "petitioner", field_value: "1"});
                filteredData.push({field_name: "respondent", field_value: "1"});
                filteredData.push({field_name: "extra_party", field_value: "1"});
                filteredData.push({field_name: "legal_representative", field_value: "1"});
                filteredData.push({field_name: "act_section", field_value: "1"});
                filteredData.push({field_name: "earlier_courts", field_value: "1"});
                filteredData.push({field_name: "upload_document", field_value: "1"});
                filteredData.push({field_name: "index", field_value: "1"});
                filteredData.push({field_name: "pay_court_fee", field_value: "1"});
                typeGeneration ='diary no.';
            } else if(file_type == 'caveat'){
                filteredData.push({field_name: "caveator", field_value: "1"});
                filteredData.push({field_name: "caveatee", field_value: "1"});
                filteredData.push({field_name: "extra_party", field_value: "1"});
                filteredData.push({field_name: "subordinate_court", field_value: "1"});
                filteredData.push({field_name: "upload_document", field_value: "1"});
                filteredData.push({field_name: "index", field_value: "1"});
                filteredData.push({field_name: "pay_court_fee", field_value: "1"});
                typeGeneration ='caveat no.';
            }
            var conformRes =  confirm("Are you sure want to generate "+typeGeneration+" ?");
            if(noError && file_type && conformRes){
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                var postData = {CSRF_TOKEN: CSRF_TOKEN_VALUE, file_type: file_type};
                $.ajax({
                    type: "POST",
                    data: JSON.stringify(postData),
                    url: "<?php echo base_url('newcase/Ajaxcalls/getAllFilingDetailsByRegistrationId'); ?>",
                    dataType:'json',
                    ContentType: 'application/json',
                    cache:false,
                    async: false,
                    beforeSend: function() {
                        $("#loader_div").html('<img id="loader_img" style="position: fixed; left: 63%;margin-top: -164px;  margin-left: -100px;" src="<?php echo base_url('assets/images/loading-data.gif');?>">');
                        $('#createDiaryNo').append('<i class="status_refresh fa fa-refresh fa-spin"></i>');
                    },
                    success: function (data){
                        if(typeof data == 'string'){
                            data = JSON.parse(data);
                        }
                        // return false;
                        if(data){
                            $("#exampleModal").modal('show');
                            $('#generateDiaryNoDiv').modal('hide');
                            var diaryStatus ='';
                            var diaryNo = '';
                            var insertData = {};
                            var status = '';
                            var diaryData ='';
                            var alloted_to ='';
                            var insertedDocNums ='';
                            if(data.status == 'SUCCESS') {
                                diaryStatus = 'new_diary';
                                if (data.diary_no) {
                                    diaryNo = data.diary_no;
                                }
                                if (data.alloted_to) {
                                    alloted_to = data.alloted_to;
                                }
                                if (data.insertedDocNums) {
                                    insertedDocNums = data.insertedDocNums;
                                }
                                if (data.status) {
                                    status = data.status;
                                }
                                if (data.records_inserted) {
                                    insertData.records_inserted = data.records_inserted;
                                }
                                insertData.diaryNo = diaryNo;
                                insertData.alloted_to = alloted_to;
                                insertData.insertedDocNums = insertedDocNums;
                                insertData.status = status;
                                insertData.selectedcheckBox = filteredData;
                                insertData.diaryStatus = diaryStatus;
                            } else  if(data.status == 'ERROR_ALREADY_IN_ICMIS') {
                                var errorMessage = data.error.split(" ");
                                diaryData = errorMessage.pop();
                                diaryStatus = 'exist_diary';
                                insertData.records_inserted = {};
                                if(data.status) {
                                    status = data.status;
                                }
                                if(data.records_inserted) {
                                    insertData.records_inserted = data.records_inserted;
                                }
                                insertData.diaryNo = diaryData;
                                insertData.status = status;
                                insertData.selectedcheckBox = filteredData;
                                insertData.diaryStatus = diaryStatus;
                            } else  if(data.status == 'ERROR_MAIN') {
                                $("#customErrorMessage").html('');
                                $("#customErrorMessage").html(data.error);
                                $("#customErrorMessage").css('color','green');
                                $("#customErrorMessage").css({ 'font-size': '30px' });
                                return false;
                            } else if(data.status == 'ERROR_CAVEAT'){
                                $("#customErrorMessage").html('');
                                $("#customErrorMessage").html(data.error);
                                $("#customErrorMessage").css('color','green');
                                $("#customErrorMessage").css({ 'font-size': '30px' });
                                return false;
                            }
                            if(insertData){
                                if(file_type == 'new_case'){
                                    $('#createDiaryNo').html('Generate Diary No.');
                                    var errorMessage ='Diary no. generation has been successfully!';
                                } else{ 
                                    $('#createDiaryNo').html('Generate Caveat No.');
                                    var errorMessage ='Caveat no. generation has been successfully!';
                                }
                                $("#customErrorMessage").html('');
                                $("#customErrorMessage").html(errorMessage);
                                $("#customErrorMessage").css('color','green');
                                $("#customErrorMessage").css({ 'font-size': '30px' });
                                $.ajax({
                                    type: "POST",
                                    data: JSON.stringify(insertData),
                                    url: "<?php echo base_url('newcase/Ajaxcalls/updateDiaryDetails'); ?>",
                                    dataType:'json',
                                    ContentType: 'application/json',
                                    cache:false,
                                    beforeSend: function() {
                                        $('#createDiaryNo').append('<i class="status_refresh fa fa-refresh fa-spin"></i>');
                                    },
                                    success: function(updateData){
                                        // return false;
                                        $("#loader_div").html('');
                                        if(updateData.success == 'success'){
                                            $("#customErrorMessage").html('');
                                            $("#customErrorMessage").html(updateData.message);
                                        } else  if(updateData.success == 'error'){
                                            $("#customErrorMessage").html('');
                                            $("#customErrorMessage").html(updateData.message);
                                        } else{
                                            $("#customErrorMessage").html('');
                                            $("#customErrorMessage").html(updateData.message);
                                        }
                                    }
                                });
                            }
                            // else {
                            //     var errorMessage = data.error;
                            //     $("#customErrorMessage").html('');
                            //     $("#customErrorMessage").html(errorMessage);
                            //     $("#customErrorMessage").css('color','red');
                            //     $("#customErrorMessage").css({'font-size': '15px'});
                            //     $('#createDiaryNo').html('Generate Diary No.');
                            // }
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
        $(document).on('click','.closeButton',function(){
            var closeButtonAttr = $(this).attr('data-close');
            if(closeButtonAttr == 1){
                window.location.reload();
            }
        });
        $('#disapp_case input').on('change', function () {
            var checkedValue = $('input[name=crt_fee_status]:checked', '#disapp_case').val();
            $('#disapprove_alerts').hide();
            $('.change_div_color').css('background-color', '');
            if (checkedValue == '<?php echo url_encryption(3); ?>') {
                $('#def_crt_fee').focus();
                $('#def_crt_fee').attr('required', true);
                $('#def_crt_fee').attr('disabled', false);
            } else {
                $('#def_crt_fee').attr('required', false);
                $('#def_crt_fee').attr('disabled', true);
                $('#def_crt_fee').val('');
            }
        });
        $('#disapprove_me').click(function () {
            
            var temp = $('.disapprovedText').text();
            temp = $.trim(temp);
            var efiling_type_id = '<?php echo isset($_SESSION['efiling_details']) ? $_SESSION['efiling_details']['ref_m_efiled_type_id'] : ''; ?>';            
            if (efiling_type_id  !="") {
                $('#disapprove_alerts').show();
                if (temp.length > 500) {
                    $('#disapprove_alerts').show();
                    $('#disapprove_alerts').html("<p class='message invalid' id='msgdiv'>Maximum length 500 allowed. <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else {
                    if ($('#objection_value').html() != '') {
                        $('#obj_remarks').val($('#objection_value').html());
                    }
                    $('#descr').val($('#editor-one').html());
                    $('#disapp_case').submit();
                }
            }
        });
        $('#markaserror').click(function () {
            var temp = $('.disapprovedText').text();
            temp = $.trim(temp);
            var efiling_type_id = '<?php echo isset($_SESSION['efiling_details']) ? $_SESSION['efiling_details']['ref_m_efiled_type_id'] : ''; ?>';
            if (efiling_type_id  !="") {
                if(temp.length == 0){
                    alert("Please fill error note.");
                    $('.disapprovedText').focus();
                    return false;
                }
                else{
                    $('#disapprove_alerts').show();
                    if (temp.length > 500) {
                        $('#disapprove_alerts').show();
                        $('#disapprove_alerts').html("<p class='message invalid' id='msgdiv'>Maximum length 500 allowed. <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    } else {
                        $('textarea#descr').text($('.disapprovedText').text());
                        $('#mark_as_error').submit();
                    }
                }
            }
        });
        //update fee and send to icmis
        $(document).on('click','#transferToScrutiny',function(){
            var registration_id = $(this).attr('data-registration_id');
            if(registration_id){
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                var postData = {CSRF_TOKEN: CSRF_TOKEN_VALUE, registration_id: registration_id};
                $.ajax({
                    type: "POST",
                    data: JSON.stringify(postData),
                    url: "<?php echo base_url('admin/EfilingAction/updateRefiledCase'); ?>",
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
                            window.location.reload();
                        }
                        else{
                            alert(res.message);
                            window.location.reload();
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
    });
    $('#disapproveModal').on('hidden.bs.modal', function (e) {
        $(this).find('form').trigger('reset');
        $('#disapprove_alerts').hide();
        $('.change_div_color').css('background-color', '');
        $('.error-tip').hide();
    });
    $('#markAsErrorModal').on('hidden.bs.modal', function (e) {
        $(this).find('form').trigger('reset');
        $('#disapprove_alerts').hide();
        $('.change_div_color').css('background-color', '');
        $('.error-tip').hide();
    });
    function uniqueArr(value, index, self) {
        return self.indexOf(value) === index;
    }
    function getAmtValue(amt) {
        //var regex = /^[0-9]*\.?[0-9]*$/;
        var regex = /^[0-9]*$/;
        if (regex.test(amt)) {
            return true;
        } else {
            $('#def_crt_fee').val('');
            return false;
        }
    }

    function showLoader() {
        setTimeout(function() {
            $('#loader-wrapper').show();
        }, 1000);
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('checkFeeStatus'); ?>",
            cache:false,
            success: function (res){
                if (res > 0) {
                    alert('Please Pay Fee.');
                    $('#loader-wrapper').hide(); // Hide loader if fee not paid
                    return false; // Stop execution, no redirection
                } else {
                    // Update CSRF token and proceed with final submit
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        // Navigate to the href after 3 seconds if fee is paid
                        let timeLeft = 3;
                        const interval = setInterval(() => {
                            timeLeft--;
                            if (timeLeft <= 0) {
                                clearInterval(interval);
                                window.location.href = "<?php echo base_url('efilingAction/Caveat_final_submit'); ?>";
                            }
                        }, 1000);
                    }).fail(function() {
                        $('#loader-wrapper').hide();
                        alert('Failed to update CSRF token.');
                    });
                }
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
</script>
