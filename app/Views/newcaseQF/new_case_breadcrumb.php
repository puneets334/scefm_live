<div class="right_col" role="main">
    <div id="page-wrapper">

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-response" id="msg" role="alert" data-auto-dismiss="5000">
                    <?php
                    if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {

                        echo $_SESSION['MSG'];
                    } unset($_SESSION['MSG']);
                    ?>
                </div>
                <?php echo $this->session->flashdata('msg'); ?>
                <div class="view_data">
                    <div id="modal_loader">
                        <img id="loader_img" style="margin-top: 30px;margin-left: 30px;display:block;" src="<?php echo base_url(); ?>assets/images/loading-data.gif" />
                    </div>
                    <div class="clearfix"></div> 
                    <?php
                    echo remark_preview($_SESSION['efiling_details']['registration_id'], $_SESSION['efiling_details']['stage_id']); ?>
                    <div class="x_panel x-panel_height">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 text-left">                        
                            <ul class="text-left1">
                                <li style="float: left;list-style-type: none;"><i class="badge" style="background-color: #01ADEF; color:#01ADEF; ">A</i> Active &nbsp;</li> 
                                <li style="float: left;list-style-type: none;"><i class="badge" style="background-color: #169F85; color:#169F85;">D</i> Done &nbsp;</li> 
                                <li style="float: left;list-style-type: none;"><i class="badge" style="background-color: #f0ad4e; color:#f0ad4e;">O</i> Optional &nbsp;</li>
                                <li style="float: left;list-style-type: none;"><i class="badge" style="background-color: #C11900; color:#C11900;">R</i> Required &nbsp;</li>
                            </ul>                        
                        </div>

                        <div class=" col-lg-8 col-md-8 col-sm-6 col-xs-12 text-right">
                            <?php
                            $Array = array(New_Filing_Stage, Initial_Defects_Cured_Stage);
                            if (($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) && in_array($_SESSION['efiling_details']['stage_id'], $Array)) {
                                ?>
                                <a data-toggle="modal" href="#approveModal" class="btn btn-success btn-sm">Approve</a> 

                                <a data-toggle="modal" href="#disapproveModal" class="btn btn-danger btn-sm" >Disapprove</a>
                                <a data-toggle="modal" href="#markAsErrorModal" class="btn btn-warning btn-sm" >Mark As Error</a>
                            <?php } ?>

                            <?php
                            $this->load->view('templates/user_efil_num_action_bar');
                            ?>
                        </div>
                    </div>
                    <div class="x_panel">
                        <div class="x_title">
                            <div class="registration-process" style="float: left; border: none;">
                                <h2><i class="fa fa-file"></i> <?php
                                    if ($_SESSION['breadcrumb_enable']['efiling_type'] == E_FILING_TYPE_NEW_CASE) {
                                        echo "Case Filing Form";
                                        $filing_num_label = 'eFiling No. : ';
                                        $lbl_history = 'eFiling History';
                                    } elseif ($_SESSION['breadcrumb_enable']['efiling_type'] == E_FILING_TYPE_CDE) {
                                        echo "Case Data Entry";
                                        $filing_num_label = 'CDE No. : ';
                                        $lbl_history = 'CDE History';
                                    } else {
                                        echo "Case Filing Form";
                                        $lbl_history = 'eFiling History';
                                    }

                                    if (($_SESSION['efiling_details']['stage_id'] == DRAFT_STAGE) || ($_SESSION['efiling_details']['stage_id'] == INITIAL_DEFECTED_STAGE) || ($_SESSION['efiling_details']['stage_id'] == TRASH_STAGE)) {
                                        $efiling_num_label_for_display = 'DRAFT-';;
                                        $efiling_num_button_background_class = 'btn-danger';
                                    } else {
                                        $efiling_num_label_for_display = '';
                                        $efiling_num_button_background_class = 'btn-success';
                                    }

                                    ?></h2>
                                <div class="clearfix"></div>                                
                            </div>
                            <div style="float: right">
                                <?php
                                if (isset($_SESSION['efiling_details']['efiling_no']) && !empty($_SESSION['efiling_details']['efiling_no'])) {
                                    echo '<a href="javascript::void(0); " class="btn '.$efiling_num_button_background_class.' btn-sm"  style="color: #ffffff"><strong id="copyTarget_EfilingNumber">' . $filing_num_label .$efiling_num_label_for_display. htmlentities(efile_preview($_SESSION['efiling_details']['efiling_no']), ENT_QUOTES) . '</strong></a> &nbsp;<strong id="copyButton" class="btn btn-danger btn-sm"  style="font-size: 14px;color:greenyellow;"><span class="glyphicon glyphicon-copy" style="font-size:14px;color:#ffffff;"></span></strong>';
                                    echo '&nbsp; <a class="btn btn-default btn-sm" href="' . base_url('history/efiled_case/view') . '">' . $lbl_history . ' </a>';
                                }
                                ?>

                                &nbsp; <a class="btn btn-info btn-sm" type="button" onclick="window.history.back()"> Back</a>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <?php //echo '<pre>';print_r($_SESSION['efiling_details']);echo '</pre>';
                        $StageArray = explode(',', $_SESSION['efiling_details']['breadcrumb_status']); $breadcrumb_status=count($StageArray);

                        $step=11;$p_r_type_petitioners='P'; $p_r_type_respondents='R'; $registration_id = $_SESSION['efiling_details']['registration_id'];
                        if (!empty($_SESSION['efiling_details']['no_of_petitioners'])){
                            $total_petitioners = get_extra_party_P_or_R($p_r_type_petitioners);
                        }else{$total_petitioners=0;}
                        if (!empty($_SESSION['efiling_details']['no_of_respondents'])){
                            $total_respondents = get_extra_party_P_or_R($p_r_type_respondents);
                        }else{$total_respondents=0;}

                        if (in_array($_SESSION['login']['ref_m_usertype_id'], array(USER_ADVOCATE, USER_IN_PERSON, USER_PDE))) {
                            $data['url_case_detail'] = base_url('newcaseQF/caseDetails');
                            $data['url_petitioner'] = base_url('newcaseQF/petitioner');
                            $data['url_respondent'] = base_url('newcaseQF/respondent');
                            $data['url_extra_party'] = base_url('newcaseQF/extra_party');
                            $data['url_add_lrs'] = base_url('newcaseQF/lr_party');
                            $data['url_act_section'] = base_url('newcaseQF/actSections');
                            $data['url_subordinate_court'] = base_url('newcaseQF/subordinate_court');
                            $data['url_case_create_index'] = base_url('documentIndex');
                            $data['url_case_upload_docs'] = base_url('newcaseQF/uploadDocuments');
                            $data['url_case_courtfee'] = base_url('newcaseQF/courtFee');
                            //$data['url_case_affirmation'] = base_url('affirmation');
                            $data['url_case_view'] = base_url('newcaseQF/view');


                        } elseif (($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT && $_SESSION['efiling_details']['stage_id'] == Draft_Stage) || ($_SESSION['login']['ref_m_usertype_id'] == USER_CLERK && in_array($_SESSION['efiling_details']['stage_id'], array(Draft_Stage, Initial_Defected_Stage)))) {

                            $data['url_case_affirmation'] = "#";
                        } else {
                            $data['url_case_detail'] = $data['url_petitioner'] = $data['url_respondent'] = $data['url_extra_info'] = $data['url_extra_party'] = $data['url_add_lrs'] = $data['url_act_section'] = $data['url_main_matter'] = $data['url_subordinate_court'] = $data['url_case_sign_method'] = $data['url_case_upload_docs'] = $data['url_case_courtfee'] = $data['url_case_affirmation'] = $data['url_case_view'] = '#';
                        }


                        $this->load->view('newcaseQF/new_case_appeal_ci_breadcrumb', $data);
                        ?>


                        <div class="modal fade" id="FinalSubmitModal" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Note :-  </h4>
                                    </div>
                                    <div class="modal-body">
                                        <br>
                                        <?php if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT) { ?>
                                            <ul>
                                                <?php if ($final_submit_action && !$final_submit_continue_action) { ?>
                                                    <li> Are you sure, you want to submit this case?</li>
                                                    <?php
                                                }

                                                if ($final_submit_action) {
                                                    ?>
                                                    <li> Click on <b> Submit</b> to submit this case to Selected Panel Advocate.</li>
                                                <?php } ?>
                                                <br>
                                            </ul>
                                            <div class=" text-center">
                                                <?php
                                                $ci = & get_instance();
                                                $ci->load->model('Department_model');
                                                $dept_all_adv_panel_list = $ci->Department_model->get_all_advocate_panel($_SESSION['login']['id']);

                                                $dept_adv_panel_list = $ci->Department_model->get_advocate_panel($_SESSION['login']['id']);
                                                $advocate = $ci->Department_model->get_alloted_advocate($_SESSION['efiling_details']['registration_id']);

                                                $action = base_url('stage_list/final_submit');
                                                $attribute = array('name' => 'submit_adv_panel', 'id' => 'submit_adv_panel', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
                                                echo form_open($action, $attribute);
                                                //print_r($_SESSION);
                                                ?>

                                                <div class="col-md-6 col-sm-6 col-xs-10">

                                                    <select id="advocate_list" name="advocate_list" class="form-control filter_select_dropdown " style="width: 100%"  >
                                                        <option value="">Practicing On this establishment</option>
                                                        <?php
                                                        foreach ($dept_adv_panel_list as $adv_panel) {

                                                            if ($advocate[0]['id'] == $adv_panel['user_id']) {
                                                                $sel = 'selected=selected';
                                                            } else {
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
                                                            } else {
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
                                        <?php } else { ?>
                                            <ul>
                                                <?php if ($final_submit_action && !$final_submit_continue_action) { ?>
                                                    <li> Are you sure, you want to submit this case?</li>
                                                    <?php
                                                }

                                                if ($final_submit_action && $final_submit_continue_action) {
                                                    ?>
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
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>   
