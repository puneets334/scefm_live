<?php
if ($this->uri->segment(2) == 'stage_list') {
    // Start User Dashboard Stages list

    $CI = & get_instance();
    $CI->load->model('dashboard/Dashboard_model');
    $count_efiling_data = $CI->Dashboard_model->get_efilied_nums_stage_wise_count();
    ?> 
       
    <div class="side-menu_new show_sm_div visible-xs visible-sm center nav-sm" style="font-size:13px; font-weight: bold;">
        <ul  class="draft"> 
            <a href="<?php echo ($count_efiling_data[0]->total_drafts == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(Draft_Stage, ENT_QUOTES))); ?>">
                <li>
                    <div>Draft (<?php echo htmlentities($count_efiling_data[0]->total_drafts, ENT_QUOTES); ?>)</div>
                    <div class="hover_text_draft hover_text_css">Draft</div>
                    <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/draft.svg'); ?>"></span>
                </li>
            </a>
        </ul>
        <ul  class="pending_approval"> 
            <a href="<?php echo ($count_efiling_data[0]->total_pending_acceptance == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(Initial_Approaval_Pending_Stage, ENT_QUOTES))); ?>">
                <li>
                    <div>Pending Approval (<?php echo htmlentities($count_efiling_data[0]->total_pending_acceptance, ENT_QUOTES); ?>)</div>
                    <div class="hover_text_pending_approval hover_text_css">Pending Ap..</div>
                    <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/pending_acceptance.svg'); ?>"></span>
                </li>
            </a>
        </ul>
        <ul  class="for_compliance"> 
            <a href="<?php echo ($count_efiling_data[0]->total_not_accepted == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(Initial_Defected_Stage, ENT_QUOTES))); ?>">
                <li>
                    <div>For Compliance (<?php echo htmlentities($count_efiling_data[0]->total_not_accepted, ENT_QUOTES); ?>)</div>
                    <div class="hover_text_for_compliance hover_text_css">Compliance</div>
                    <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/not accepted.svg'); ?>"></span>
                </li>
            </a>
        </ul>
        <ul  class="user_deficit_fee"> 
            <a href="<?php echo ($count_efiling_data[0]->deficit_crt_fee == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(DEFICIT_COURT_FEE, ENT_QUOTES))); ?>">
                <li>
                    <div>Deficit Fee  (<?php echo htmlentities($count_efiling_data[0]->deficit_crt_fee, ENT_QUOTES); ?>)</div>
                    <div class="hover_text_user_deficit_fee hover_text_css">Deficit Fee</div>
                    <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/deficit_court_fee.svg'); ?>"></span>
                </li>
            </a>
        </ul>
        <ul  class="pending_scrutiny"> 
            <a href="<?php echo ($count_efiling_data[0]->total_not_accepted == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(I_B_Approval_Pending_Stage, ENT_QUOTES))); ?>">
                <li>
                    <div>Pending Scrutiny (<?php echo htmlentities($count_efiling_data[0]->total_not_accepted, ENT_QUOTES); ?>)</div>
                    <div class="hover_text_pending_scrutiny hover_text_css">Pending Scr..</div>
                    <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/Pending scrutiny.svg'); ?>"></span>
                </li>
            </a>
        </ul>
        <ul  class="stage_list1"> 
            <a href="<?php echo ($count_efiling_data[0]->total_filing_section_defective == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(I_B_Defected_Stage, ENT_QUOTES))); ?>">
                <li>
                    <div>Defective (<?php echo htmlentities($count_efiling_data[0]->total_filing_section_defective, ENT_QUOTES); ?>)</div>
                    <div class="hover_text_stage_list1 hover_text_css">Defective</div>
                    <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/Defective.svg'); ?>"></span>
                </li>
            </a>
        </ul>
        <ul  class="stage_list2"> 
            <a href="<?php echo ($count_efiling_data[0]->total_efiled_cases == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(E_Filed_Stage, ENT_QUOTES))); ?>">
                <li>
                    <div>eFiled Cases (<?php echo htmlentities($count_efiling_data[0]->total_efiled_cases, ENT_QUOTES); ?>)</div>
                    <div class="hover_text_stage_list2 hover_text_css">eFiled Cases</div>
                    <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/documents.svg'); ?>"></span>
                </li>
            </a>
        </ul>
        <ul  class="stage_list3"> 
            <a href="<?php echo ($count_efiling_data[0]->total_efiled_docs == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(Document_E_Filed, ENT_QUOTES))); ?>">
                <li>
                    <div>eFiled Documents (<?php echo htmlentities($count_efiling_data[0]->total_efiled_docs, ENT_QUOTES); ?>)</div>
                    <div class="hover_text_stage_list3 hover_text_css">eFiled Doc..</div>
                    <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/deficit_court_fee.svg'); ?>"></span>
                </li>
            </a>
        </ul>
        <ul  class="stage_list4"> 
            <a href="<?php echo ($count_efiling_data[0]->total_efiled_deficit == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(DEFICIT_COURT_FEE_E_FILED, ENT_QUOTES))); ?>">
                <li>
                    <div>eFiled Deficit Fee (<?php echo htmlentities($count_efiling_data[0]->total_efiled_deficit, ENT_QUOTES); ?>)</div>
                    <div class="hover_text_stage_list4 hover_text_css">Deficit Fee</div>
                    <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/book.svg'); ?>"></span>
                </li>
            </a>
        </ul>
        <ul  class="stage_list5"> 
            <a href="<?php echo ($count_efiling_data[0]->total_efiled_ia == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(IA_E_Filed, ENT_QUOTES))); ?>">
                <li>
                    <div>eFiled IA (<?php echo htmlentities($count_efiling_data[0]->total_efiled_ia, ENT_QUOTES); ?>)</div>
                    <div class="hover_text_stage_list5 hover_text_css">eFiled IA</div>
                    <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/maintenance.svg'); ?>"></span>
                </li>
            </a>
        </ul>
        <ul  class="stage_list6"> 
            <a href="<?php echo ($count_efiling_data[0]->total_rejected_cases == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(I_B_Rejected_Stage, ENT_QUOTES))); ?>">
                <li>
                    <div>Rejected eFiling No.'s (<?php echo htmlentities($count_efiling_data[0]->total_rejected_cases, ENT_QUOTES); ?>)</div>
                    <div class="hover_text_stage_list6 hover_text_css">Reject eFile</div>
                    <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/rejected.svg'); ?>"></span>
                </li>
            </a>
        </ul>
        <ul  class="stage_list7"> 
            <a href="<?php echo ($count_efiling_data[0]->total_lodged_cases == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(LODGING_STAGE, ENT_QUOTES))); ?>">
                <li>
                    <div>Trashed (<?php echo htmlentities($count_efiling_data[0]->total_lodged_cases, ENT_QUOTES); ?>)</div>
                    <div class="hover_text_stage_list7 hover_text_css">Trashed</div>
                    <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/trashed.svg'); ?>"></span>
                </li>
            </a>
        </ul>
    </div>                   

    <div class="show_md_div visible-lg visible-md" style="display: none !important;">
        <ul class="nav side-menu">
            <li><a href="<?php echo ($count_efiling_data[0]->total_drafts == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(Draft_Stage, ENT_QUOTES))); ?>"><span class="text_color"> Draft<i class="number_css badge"> <?php echo htmlentities($count_efiling_data[0]->total_drafts, ENT_QUOTES); ?></i></span></a></li>
            <li><a href="<?php echo ($count_efiling_data[0]->total_pending_acceptance == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(Initial_Approaval_Pending_Stage, ENT_QUOTES))); ?>"><span class="text_color">Pending Approval<i class="number_css badge"> <?php echo htmlentities($count_efiling_data[0]->total_pending_acceptance, ENT_QUOTES); ?></i></span></a></li>
            <li><a href="<?php echo ($count_efiling_data[0]->total_not_accepted == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(Initial_Defected_Stage, ENT_QUOTES))); ?>"><span class="text_color">For Compliance<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_not_accepted, ENT_QUOTES); ?></i></span></a></li>
            <li><a href="<?php echo ($count_efiling_data[0]->deficit_crt_fee == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(DEFICIT_COURT_FEE, ENT_QUOTES))); ?>"><span class="text_color">Deficit Fee <i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->deficit_crt_fee, ENT_QUOTES); ?></i></span></a></li>
            <li><a href="<?php echo ($count_efiling_data[0]->total_not_accepted == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(I_B_Approval_Pending_Stage, ENT_QUOTES))); ?>"><span class="text_color">Pending Scrutiny <i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_not_accepted, ENT_QUOTES); ?></i></span></a></li>
            <li><a href="<?php echo ($count_efiling_data[0]->total_filing_section_defective == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(I_B_Defected_Stage, ENT_QUOTES))); ?>"><span class="text_color">Defective<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_filing_section_defective, ENT_QUOTES); ?></i></span></a></li>
            <li><a href="<?php echo ($count_efiling_data[0]->total_efiled_cases == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(E_Filed_Stage, ENT_QUOTES))); ?>"><span class="text_color">eFiled Cases<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_efiled_cases, ENT_QUOTES); ?></i></span></a></li>
            <li><a href="<?php echo ($count_efiling_data[0]->total_efiled_docs == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(Document_E_Filed, ENT_QUOTES))); ?>"><span class="text_color">eFiled Documents<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_efiled_docs, ENT_QUOTES); ?></i></span></a></li>
            <li><a href="<?php echo ($count_efiling_data[0]->total_efiled_deficit == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(DEFICIT_COURT_FEE_E_FILED, ENT_QUOTES))); ?>"><span class="text_color">eFiled Deficit Fee<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_efiled_deficit, ENT_QUOTES); ?></i></span></a></li>
            <li><a href="<?php echo ($count_efiling_data[0]->total_efiled_ia == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(IA_E_Filed, ENT_QUOTES))); ?>" ><span class="text_color">eFiled IA<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_efiled_ia, ENT_QUOTES); ?></i></span></a></li>
            <li><a href="<?php echo ($count_efiling_data[0]->total_rejected_cases == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(I_B_Rejected_Stage, ENT_QUOTES))); ?>"><span class="text_color">Rejected eFiling No.'s<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_rejected_cases, ENT_QUOTES); ?></i></span></a></li>
            <li><a href="<?php echo ($count_efiling_data[0]->total_lodged_cases == '0' ) ? 'javascript:void(0)' : base_url('dashboard/stage_list/' . htmlentities(url_encryption(LODGING_STAGE, ENT_QUOTES))); ?>"><span class="text_color">Trashed<i class="number_css badge"><?php echo htmlentities($count_efiling_data[0]->total_lodged_cases, ENT_QUOTES); ?></i></span></a></li>
        </ul>
    </div>

<?php } ?>