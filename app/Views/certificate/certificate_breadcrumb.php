<div class="right_col" role="main">
    <div id="page-wrapper">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-response" id="msg"><?php
                    if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
                        echo $_SESSION['MSG'];
                    } unset($_SESSION['MSG']);
                    ?>
                </div>
                <?php echo $this->session->flashdata('msg'); ?>
                <div class="view_data">
                    <div id="modal_loader">
                        <img id="loader_img" style="margin-top: 30px;margin-left: 30px;display:block;" src="<?php echo base_url('assets/images/loading-data.gif'); ?>" />
                    </div>
                    <div class="clearfix"></div>
                    <?php
                    echo remark_preview($_SESSION['efiling_details']['registration_id'], $_SESSION['efiling_details']['stage_id']);
                    ?>
                    <!-- <div class="x_panel x-panel_height">
                         <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 text-left">
                             <ul class="text-left1">
                                 <li style="float: left;list-style-type: none;"><i class="badge" style="background-color: #01ADEF; color:#01ADEF; ">A</i> Active &nbsp;</li>
                                 <li style="float: left;list-style-type: none;"><i class="badge" style="background-color: #169F85; color:#169F85;">D</i> Done &nbsp;</li>
                                 <li style="float: left;list-style-type: none;"><i class="badge" style="background-color: #f0ad4e; color:#f0ad4e;">O</i> Optional &nbsp;</li>
                                 <li style="float: left;list-style-type: none;"><i class="badge" style="background-color: #C11900; color:#C11900;">R</i> Required &nbsp;</li>
                             </ul>
                         </div>-->

                    <div class=" col-lg-8 col-md-8 col-sm-6 col-xs-12 text-right">
                        <?php
                        $Array = array(New_Filing_Stage, Initial_Defects_Cured_Stage);
                        if (($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) && in_array($_SESSION['efiling_details']['stage_id'], $Array)) {
                            ?>

                            <a data-toggle="modal" href="#disapproveModal" class="btn btn-success btn-sm">Submit Order</a>

                        <?php } ?>

                        <?php
                        $Array = array(Draft_Stage, Initial_Defected_Stage);

                        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

                        /*           if (in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {

                                       if (in_array($_SESSION['efiling_details']['stage_id'], $Array)) {

                                           if (in_array(MEN_BREAD_AFFIRMATION, explode(',', $_SESSION['efiling_details']['breadcrumb_status']))) {

                                               if (($_SESSION['efiling_details']['gras_payment_status'] != 'P') ||
                                                       ( $_SESSION['efiling_details']['gras_payment_status'] == 'Y' && $_SESSION['efiling_details']['payment_verified_by'] != NULL &&
                                                       ($_SESSION['efiling_details']['is_payment_defecit'] == 't' || $_SESSION['efiling_details']['is_payment_defective'] == 't')
                                                       )) {
                                                   echo '<a href="' . base_url('mentioning/FinalSubmit') . '" class="btn btn-success btn-sm">Final Submit</a>';
                                               }
                                           }
                                           */?><!--
                                    <?php /*if ($_SESSION['efiling_details']['stage_id'] == Draft_Stage) { */?>
                                        <a href="<?php /*echo base_url('list/user_stages/trash'); */?>" class="btn btn-danger btn-sm" onclick="return confirm('This action is irreversible. Are you sure that you really want to trash this record?  .')" >Trash</a>
                                        --><?php
                        /*                                    }
                                                        }
                                                    }*/
                        ?>
                    </div>
                </div>
                <div class="x_panel">
                    <div class="x_title">
                        <div class="registration-process" style="float: left; border: none;">
                            <h2><i class="glyphicon glyphicon-book"></i> E-File Certificate Request </h2>
                            <div class="clearfix"></div>
                        </div>
                        <div style="float: right">
                            <?php
                            if (isset($_SESSION['efiling_details']['efiling_no']) && !empty($_SESSION['efiling_details']['efiling_no'])) {
                                echo '<a href="javascript::void(0); " class="btn btn-danger btn-sm"  style="color: #ffffff"><strong id="copyTarget_EfilingNumber">' . $filing_num_label . htmlentities(efile_preview($_SESSION['efiling_details']['efiling_no']), ENT_QUOTES) . '</strong></a> &nbsp;<strong id="copyButton" class="btn btn-danger btn-sm"  style="font-size: 14px;color:greenyellow;"><span class="glyphicon glyphicon-copy" style="font-size:14px;color:#ffffff;"></span></strong>';
                            }
                            ?>
                            &nbsp; <a class="btn btn-default btn-sm" href="<?php echo base_url('history/efiled_case/view'); ?>"> eFiling History</a>
                            &nbsp; <a class="btn btn-info btn-sm" type="button" onclick="window.history.back()"> Back</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <?php
                        $StageArray = explode(',', $_SESSION['efiling_details']['breadcrumb_status']);

                        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON) {
                            $cnr_url = base_url('case_details');
                            $listing = base_url('certificate/AddDetails');
                            /*$upload_doc_url = base_url('uploadDocuments');
                            $doc_index_url = base_url('documentIndex');
                            $fee_url = base_url('mentioning/courtFee');
                            $aff_url = base_url('affirmation');
                            $shareDoc = base_url('shareDoc');*/
                        } else {
                            $cnr_url =$listing = '#';
                        }
                        ?>
                        <ul class="nav-breadcrumb">
                            <li>

                                <?php
                                if ($this->uri->segment(1) == 'case_details') {
                                    $ColorCode = 'background-color: #01ADEF';
                                    $status_color = 'first active';
                                } elseif (in_array(CERTIFICATE_BREAD_CASE_DETAILS, $StageArray)) {
                                    $ColorCode = 'background-color: #169F85;color:#ffffff;';
                                    $status_color = '';
                                } else {
                                    $ColorCode = 'background-color: #C11900;color:#ffffff;';
                                    $status_color = '';
                                }
                                ?>
                                <a href="<?= $cnr_url; ?>" class="<?php echo $status_color; ?>" style="z-index:9;"><span style="<?php echo $ColorCode; ?>">1</span> Case Details </a>

                            </li>
                            <li>

                                <?php
                                /*if ($this->uri->segment(1) == 'appearing_for') {
                                    $ColorCode = 'background-color: #01ADEF';
                                    $status_color = 'first active';
                                } elseif (in_array(MEN_BREAD_ARGUING_COUNCIL, $StageArray)) {
                                    $ColorCode = 'background-color: #169F85;color:#ffffff;';
                                    $status_color = '';
                                } else {
                                    $ColorCode = 'background-color: #C11900;color:#ffffff;';
                                    $status_color = '';
                                }
                                */?><!--
                                    <a href="<?/*= $arguing_council; */?>" class="<?php /*echo $status_color; */?>" style="z-index:8;"><span style="<?php /*echo $ColorCode; */?>">2</span> Arguing Council</a>

                                </li>-->
                            <li>

                                <?php
                                if ($this->uri->segment(1) == 'details' || $this->uri->segment(1) == 'certificate') {
                                    $ColorCode = 'background-color: #01ADEF';
                                    $status_color = 'first active';
                                } elseif (in_array(CERTIFICATE_BREAD_REQUEST_DETAILS, $StageArray)) {
                                    $ColorCode = 'background-color: #169F85;color:#ffffff;';
                                    $status_color = '';
                                } else {
                                    $ColorCode = 'background-color: #C11900;color:#ffffff;';
                                    $status_color = '';
                                }
                                ?>
                                <a href="<?= $listing; ?>" class="<?php echo $status_color; ?>" style="z-index:7;"><span style="<?php echo $ColorCode; ?>">2</span> Details</a>

                            </li>



                            <!--<li>

                                    <?php
                            /*                                    if ($this->uri->segment(1) == 'uploadDocuments') {
                                                                    $ColorCode = 'background-color: #01ADEF';
                                                                    $status_color = 'first active';
                                                                } elseif (in_array(MEN_BREAD_UPLOAD_DOC, $StageArray)) {
                                                                    $ColorCode = 'background-color: #169F85;color:#ffffff;';
                                                                    $status_color = '';
                                                                } else {

                                                                    $ColorCode = 'background-color: #C11900;color:#ffffff;';
                                                                    $status_color = '';
                                                                }
                                                                */?>
                                    <a href="<?/*= $upload_doc_url */?>" class="<?php /*echo $status_color; */?>" style="z-index:5;"><span style="<?php /*echo $ColorCode; */?>">4</span> Upload Document  </a>

                                </li>
                                <?php
                            /*                                $xyz = FALSE;
                                                            if ($xyz) {
                                                                */?>
                                    <li>
                                        <?php
                            /*                                        if ($this->uri->segment(1) == 'documentIndex') {
                                                                        $ColorCode = 'background-color: #01ADEF';
                                                                        $status_color = 'first active';
                                                                    } elseif (in_array(MEN_BREAD_DOC_INDEX, $StageArray)) {
                                                                        $ColorCode = 'background-color: #169F85;color:#ffffff;';
                                                                        $status_color = '';
                                                                    } else {
                                                                        $ColorCode = 'background-color: #C11900;color:#ffffff;';
                                                                        $status_color = '';
                                                                    }
                                                                    */?>
                                        <a href="<?/*= $doc_index_url */?>" class="<?php /*echo $status_color; */?>" style="z-index:5;"><span style="<?php /*echo $ColorCode; */?>">5</span> Index  </a>
                                    </li>
                                <?php /*} */?>
                                <li>

                                    <?php
                            /*                                    if ($this->uri->segment(2) == 'courtFee') {
                                                                    $ColorCode = 'background-color: #01ADEF';
                                                                    $status_color = 'first active';
                                                                } elseif (in_array(MEN_BREAD_COURT_FEE, $StageArray)) {
                                                                    $ColorCode = 'background-color: #169F85;color:#ffffff;';
                                                                    $status_color = '';
                                                                } else {
                                                                    if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
                                                                        $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
                                                                        $status_color = '';
                                                                    } else {
                                                                        $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
                                                                        $status_color = '';
                                                                    }
                                                                }
                                                                */?>
                                    <a href="<?/*= $fee_url; */?>" class="<?php /*echo $status_color; */?>" style="z-index:4;"><span style="<?php /*echo $ColorCode; */?>">5</span> Court Fee   </a>

                                </li>

                                <?php
                            /*                                $xyz = FALSE;
                                                            if ($xyz) {
                                                                */?>
                                    <li>

                                        <?php
                            /*                                        if ($this->uri->segment(2) == 'document_share') {
                                                                        $ColorCode = 'background-color: #01ADEF';
                                                                        $status_color = 'first active';
                                                                    } elseif (in_array(MEN_BREAD_SHARE_DOC, $StageArray)) {
                                                                        $ColorCode = 'background-color: #169F85;color:#ffffff;';
                                                                        $status_color = '';
                                                                    } else {
                                                                        $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
                                                                        $status_color = '';
                                                                    }
                                                                    */?>
                                        <a href="<?/*= $shareDoc; */?>" class="<?php /*echo $status_color; */?>" style="z-index:3;"><span style="<?php /*echo $ColorCode; */?>">7</span> Share Document  </a>

                                    </li>
                                <?php /*} */?>
                                <li>
                                    <?php
                            /*                                    if ($this->uri->segment(1) == 'affirmation') {
                                                                    $ColorCode = 'background-color: #01ADEF';
                                                                    $status_color = 'first active';
                                                                } elseif (in_array(MEN_BREAD_AFFIRMATION, $StageArray)) {

                                                                    $ColorCode = 'background-color: #169F85;color:#ffffff;';
                                                                    $status_color = '';
                                                                } else {
                                                                    if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
                                                                        $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
                                                                        $status_color = '';
                                                                    } else {
                                                                        $ColorCode = 'background-color: #C11900;color:#ffffff;';
                                                                        $status_color = '';
                                                                    }
                                                                }
                                                                */?>
                                    <a href="<?/*= $aff_url; */?>" class="<?php /*echo $status_color; */?>" style="z-index:2;"><span style="<?php /*echo $ColorCode; */?>">6</span> Certificate </a>
                                </li>
                                <li>
                                    <?php
                            /*                                    if ($this->uri->segment(2) == 'view') {
                                                                    $ColorCode = 'background-color: #01ADEF';
                                                                    $status_color = 'first active';
                                                                } elseif (in_array(MEN_BREAD_CASE_DETAILS, $StageArray) && in_array(MEN_BREAD_ON_BEHALF_OF, $StageArray) && in_array(MEN_BREAD_UPLOAD_DOC, $StageArray) && in_array(MEN_BREAD_AFFIRMATION, $StageArray)) {
                                                                    $ColorCode = 'background-color: #169F85;color:#ffffff;';
                                                                    $status_color = '';
                                                                } else {
                                                                    $ColorCode = 'background-color: #C11900;color:#ffffff;';
                                                                    $status_color = '';
                                                                }
                                                                */?>
                                    <a href="<?/*= base_url('mentioning/view') */?>" class="<?php /*echo $status_color; */?>" style="z-index:1;"><span style="<?php /*echo $ColorCode; */?>">7</span> View </a>
                                </li>-->
                        </ul>
                        <div class="clearfix"></div>

