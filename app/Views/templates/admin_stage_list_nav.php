<?php
if ($this->uri->segment(2) == 'stageList') {
    $CI = & get_instance();
    $CI->load->model('adminDashboard/AdminDashboard_model');
    //$count_efiling_data = $CI->AdminDashboard_model->get_efilied_nums_stage_wise_count();
    ?>


    <li><a style="background-color: #737373 !important;"><i class="fa fa-file-o "></i><strong style="color:#fff;">e-Filed Stages</strong></a></li>

    <div class="show_sm_div visible-xs visible-sm" style="margin-top: 6px;"> 
        <div class="side-menu_new">
            <ul  class="admin_stages1"> 
                <a href="<?php echo ($count_efiling_data[0]->total_new_efiling == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(New_Filing_Stage, ENT_QUOTES))); ?>">
                    <li>
                        <div>New Filing (<?php echo htmlentities($count_efiling_data[0]->total_new_efiling, ENT_QUOTES); ?>)</div>
                        <div class="hover_text_admin_stages1 hover_text_css">New Filing</div>
                        <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/draft.svg'); ?>"></span>
                    </li>
                </a>
            </ul>
            <ul  class="admin_stages2"> 
                <a href="<?php echo ($count_efiling_data[0]->total_not_accepted == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(Initial_Defected_Stage, ENT_QUOTES))); ?>">
                    <li>
                        <div>For Compliance (<?php echo htmlentities($count_efiling_data[0]->total_not_accepted, ENT_QUOTES); ?>)</div>
                        <div class="hover_text_admin_stages2 hover_text_css">Compliance</div>
                        <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/pending_acceptance.svg'); ?>"></span>
                    </li>
                </a>
            </ul>
            <ul  class="admin_stages3"> 
                <a href="<?php echo ($count_efiling_data[0]->deficit_crt_fee == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(DEFICIT_COURT_FEE, ENT_QUOTES))); ?>">
                    <li>
                        <div>Pay Deficit Fee (<?php echo htmlentities($count_efiling_data[0]->deficit_crt_fee, ENT_QUOTES); ?>)</div>
                        <div class="hover_text_admin_stages3 hover_text_css">Deficit Fee</div>
                        <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/not accepted.svg'); ?>"></span>
                    </li>
                </a>
            </ul>
            <ul  class="admin_stages4"> 
                <a href="<?php echo ($count_efiling_data[0]->total_refiled_cases == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(Initial_Defects_Cured_Stage, ENT_QUOTES))); ?>">
                    <li>
                        <div>Complied Objections (<?php echo htmlentities($count_efiling_data[0]->total_refiled_cases, ENT_QUOTES); ?>)</div>
                        <div class="hover_text_admin_stages4 hover_text_css">Complie Obj.</div>
                        <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/deficit_court_fee.svg'); ?>"></span>
                    </li>
                </a>
            </ul>
            <ul  class="admin_stages5"> 
                <a href="<?php echo ($count_efiling_data[0]->total_transfer_to_efiling_sec == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(Transfer_to_IB_Stage, ENT_QUOTES))); ?>">
                    <li>
                        <div>Transfer to CIS (<?php echo htmlentities($count_efiling_data[0]->total_transfer_to_efiling_sec, ENT_QUOTES); ?>)</div>
                        <div class="hover_text_admin_stages5 hover_text_css">Tran. to CIS</div>
                        <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/Pending scrutiny.svg'); ?>"></span>
                    </li>
                </a>
            </ul>
            <ul  class="admin_stages6"> 
                <a href="<?php echo ($count_efiling_data[0]->total_available_for_cis == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(Transfer_to_CIS_Stage, ENT_QUOTES))); ?>">
                    <li>
                        <div>Get CIS Status (<?php echo htmlentities($count_efiling_data[0]->total_available_for_cis, ENT_QUOTES); ?>)</div>
                        <div class="hover_text_admin_stages6 hover_text_css">CIS Status</div>
                        <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/Defective.svg'); ?>"></span>
                    </li>
                </a>
            </ul>
            <ul  class="admin_stages7"> 
                <a href="<?php echo ($count_efiling_data[0]->total_pending_scrutiny == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(I_B_Approval_Pending_Admin_Stage, ENT_QUOTES))); ?>">
                    <li>
                        <div>Pending Scrutiny (<?php echo htmlentities($count_efiling_data[0]->total_pending_scrutiny, ENT_QUOTES); ?>)</div>
                        <div class="hover_text_admin_stages7 hover_text_css">Pending Scr..  </div>
                        <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/documents.svg'); ?>"></span>
                    </li>
                </a>
            </ul>
            <ul  class="admin_stages8"> 
                <a href="<?php echo ($count_efiling_data[0]->total_waiting_defect_cured == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(I_B_Defected_Stage, ENT_QUOTES))); ?>">
                    <li>
                        <div>Defective (<?php echo htmlentities($count_efiling_data[0]->total_waiting_defect_cured, ENT_QUOTES); ?>)</div>
                        <div class="hover_text_admin_stages8 hover_text_css">Defective</div>
                        <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/deficit_court_fee.svg'); ?>"></span>
                    </li>
                </a>
            </ul>
            <ul  class="admin_stages9"> 
                <a href="<?php echo ($count_efiling_data[0]->total_defect_cured == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(I_B_Defects_Cured_Stage, ENT_QUOTES))); ?>">
                    <li>
                        <div>Defects Cured (<?php echo htmlentities($count_efiling_data[0]->total_defect_cured, ENT_QUOTES); ?>)</div>
                        <div class="hover_text_admin_stages9 hover_text_css">Defects Cure</div>
                        <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/book.svg'); ?>"></span>
                    </li>
                </a>
            </ul>
            <ul  class="admin_stages10"> 
                <a href="<?php echo ($count_efiling_data[0]->total_efiled_cases == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(E_Filed_Stage, ENT_QUOTES))); ?>">
                    <li>
                        <div>Cases (<?php echo htmlentities($count_efiling_data[0]->total_efiled_cases, ENT_QUOTES); ?>)</div>
                        <div class="hover_text_admin_stages10 hover_text_css">Cases</div>
                        <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/maintenance.svg'); ?>"></span>
                    </li>
                </a>
            </ul>
            <ul  class="admin_stages11"> 
                <a href="<?php echo ($count_efiling_data[0]->total_efiled_docs == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(Document_E_Filed, ENT_QUOTES))); ?>">
                    <li>
                        <div>Documents (<?php echo htmlentities($count_efiling_data[0]->total_efiled_docs, ENT_QUOTES); ?>)</div>
                        <div class="hover_text_admin_stages11 hover_text_css">Documents</div>
                        <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/rejected.svg'); ?>"></span>
                    </li>
                </a>
            </ul>
            <ul  class="admin_stages12"> 
                <a href="<?php echo ($count_efiling_data[0]->total_efiled_deficit == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(DEFICIT_COURT_FEE_E_FILED, ENT_QUOTES))); ?>">
                    <li>
                        <div>Paid Deficit Fee (<?php echo htmlentities($count_efiling_data[0]->total_efiled_deficit, ENT_QUOTES); ?>)</div>
                        <div class="hover_text_admin_stages12 hover_text_css">Deficit Fee</div>
                        <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/trashed.svg'); ?>"></span>
                    </li>
                </a>
            </ul>
            <?php if (ENABLE_E_FILE_IA_FOR_HC || ENABLE_E_FILE_IA_FOR_ESTAB) { ?>
                <ul  class="admin_stages13"> 
                    <a href="<?php echo ($count_efiling_data[0]->total_efiled_ia == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(IA_E_Filed, ENT_QUOTES))); ?>">
                        <li>
                            <div>IA (<?php echo htmlentities($count_efiling_data[0]->total_efiled_ia, ENT_QUOTES); ?>)</div>
                            <div class="hover_text_admin_stages13 hover_text_css">Deficit Fee</div>
                            <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/maintenance.svg'); ?>"></span>
                        </li>
                    </a>
                </ul>
            <?php } ?>
            <ul  class="admin_stages14"> 
                <a href="<?php echo ($count_efiling_data[0]->total_rejected == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(I_B_Rejected_Stage, ENT_QUOTES))); ?>">
                    <li>
                        <div>Rejected (<?php echo htmlentities($count_efiling_data[0]->total_rejected, ENT_QUOTES); ?>)</div>
                        <div class="hover_text_admin_stages14 hover_text_css">Deficit Fee</div>
                        <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/rejected.svg'); ?>"></span>
                    </li>
                </a>
            </ul>
            <ul  class="admin_stages15"> 
                <a href="<?php echo ($count_efiling_data[0]->total_lodged_cases == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(LODGING_STAGE, ENT_QUOTES))); ?>">
                    <li>
                        <div>Idle/Unprocess e-Filed No. (<?php echo htmlentities($count_efiling_data[0]->total_lodged_cases, ENT_QUOTES); ?>)</div>
                        <div class="hover_text_admin_stages15 hover_text_css">Unprocessed</div>
                        <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/trashed.svg'); ?>"></span>
                    </li>
                </a>
            </ul> 
        </div> 
    </div>
    <div class="show_md_div visible-lg visible-md" style="display: none !important;">
        <ul class="nav side-menu">        
            <li class="highlight"><a href="<?php echo ($count_efiling_data[0]->total_new_efiling == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(New_Filing_Stage, ENT_QUOTES))); ?>"><span class="text_color">New Filing <i class="number_css badge"> <?php echo htmlentities($count_efiling_data[0]->total_new_efiling, ENT_QUOTES); ?></i></span></a></li><div class="clearfix"></div>
            <li class="highlight"><a href="<?php echo ($count_efiling_data[0]->total_not_accepted == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(Initial_Defected_Stage, ENT_QUOTES))); ?>"><span class="text_color">For Compliance<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_not_accepted, ENT_QUOTES); ?></i></span></a>  </li><div class="clearfix"></div>
            <li class="highlight"><a href="<?php echo ($count_efiling_data[0]->deficit_crt_fee == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(DEFICIT_COURT_FEE, ENT_QUOTES))); ?>"><span class="text_color">Pay Deficit Fee<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->deficit_crt_fee, ENT_QUOTES); ?></i></span></a></li><div class="clearfix"></div>
            <li class="highlight"><a href="<?php echo ($count_efiling_data[0]->total_refiled_cases == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(Initial_Defects_Cured_Stage, ENT_QUOTES))); ?>"><span class="text_color">Complied Objections <i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_refiled_cases, ENT_QUOTES); ?></i></span></a></li><div class="clearfix"></div>
            <li class="highlight"><a href="<?php echo ($count_efiling_data[0]->total_transfer_to_efiling_sec == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(Transfer_to_IB_Stage, ENT_QUOTES))); ?>"><span class="text_color">Transfer to CIS<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_transfer_to_efiling_sec, ENT_QUOTES); ?></i></span></a></li><div class="clearfix"></div>
            <li class="highlight"><a href="<?php echo ($count_efiling_data[0]->total_available_for_cis == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(Transfer_to_CIS_Stage, ENT_QUOTES))); ?>"><span class="text_color" >Get CIS Status<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_available_for_cis, ENT_QUOTES); ?></i></span></a></li><div class="clearfix"></div>
            <li class="highlight"><a href="<?php echo ($count_efiling_data[0]->total_pending_scrutiny == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(I_B_Approval_Pending_Admin_Stage, ENT_QUOTES))); ?>"><span class="text_color">Pending Scrutiny<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_pending_scrutiny, ENT_QUOTES); ?></i></span></a></li><div class="clearfix"></div>
            <li class="highlight"><a href="<?php echo ($count_efiling_data[0]->total_waiting_defect_cured == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(I_B_Defected_Stage, ENT_QUOTES))); ?>"><span class="text_color" >Defective<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_waiting_defect_cured, ENT_QUOTES); ?></i></span></a></li><div class="clearfix"></div>
            <li class="highlight"><a href="<?php echo ($count_efiling_data[0]->total_defect_cured == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(I_B_Defects_Cured_Stage, ENT_QUOTES))); ?>"><span class="text_color" >Defects Cured<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_defect_cured, ENT_QUOTES); ?></i></span></a></li><div class="clearfix"></div>
            <li class="highlight"><a href="<?php echo ($count_efiling_data[0]->total_efiled_cases == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(E_Filed_Stage, ENT_QUOTES))); ?>" ><span class="text_color" >Cases<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_efiled_cases, ENT_QUOTES); ?></i></span></a></li><div class="clearfix"></div>
            <li class="highlight"><a href="<?php echo ($count_efiling_data[0]->total_efiled_docs == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(Document_E_Filed, ENT_QUOTES))) ?>"><span class="text_color" >Documents<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_efiled_docs, ENT_QUOTES); ?></i></span></a></li><div class="clearfix"></div>
            <li class="highlight"><a href="<?php echo ($count_efiling_data[0]->total_efiled_deficit == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(DEFICIT_COURT_FEE_E_FILED, ENT_QUOTES))); ?>"><span class="text_color" >Paid Deficit Fee<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_efiled_deficit, ENT_QUOTES); ?></i></span></a></li><div class="clearfix"></div>
            <li class="highlight"><a href="<?php echo ($count_efiling_data[0]->total_efiled_ia == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(IA_E_Filed, ENT_QUOTES))); ?>"><span class="text_color" >IA<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_efiled_ia, ENT_QUOTES); ?></i></span></a></li>  <div class="clearfix"></div>
            <li class="highlight"><a href="<?php echo ($count_efiling_data[0]->total_rejected == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(I_B_Rejected_Stage, ENT_QUOTES))) ?>"><span class="text_color" >Rejected<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_rejected, ENT_QUOTES); ?></i></span></a></li>  <div class="clearfix"></div>
            <li class="highlight"><a href="<?php echo ($count_efiling_data[0]->total_lodged_cases == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(I_B_Defected_Stage.'@'.MARK_AS_ERROR, ENT_QUOTES))) ?>"><span class="text_color" >Idle/Unprocessed e-Filed No.'s<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_lodged_cases, ENT_QUOTES); ?></i></span></a></li><div class="clearfix"></div>
        </ul>
    </div>
    <?php
    // Ends User Dashboard Stages list
}?>