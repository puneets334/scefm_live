<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
@extends('layout.app')
@section('content')
<link rel="stylesheet" type="text/css" href="<?= base_url() . 'assets/newAdmin/' ?>css/jquery.dataTables.min.css">
<style>
    th{font-size: 13px;color: #000;} 
    td{font-size: 13px;color: #000;}
    .no-paginations div#datatable-responsive_length {
        display: block !important;
    }
    .no-paginations div#datatable-responsive_paginate {
        display: block !important;
    }
    .dataTables_filter { margin: -45px 115px; }
</style>
<div class="container-fluid">
    <div class="row" >
        <div class="col-lg-12">
            <div class="col-md-12 col-sm-12 col-xs-12 dash-card">
                <div class="form-response" id="msg" >
                    <?php
                    if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
                        echo $_SESSION['MSG'];
                    }
                    unset($_SESSION['MSG']);
                    ?>
                </div>
                <div class="x_panel">
                    <div class="x_title"> 
                        <h3>
                            <?= isset($tabs_heading) ? htmlentities($tabs_heading, ENT_QUOTES) : '' ?>
                            <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right mb-2"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                        </h3>
                    </div>
                    <div class="x_content">  
                        <div class="table-wrapper-scroll-y my-custom-scrollbar no-paginations">
                            <table id="datatable-responsive" class="table table-striped table-border custom-table mt-4">
                                <thead>
                                    <tr class="success input-sm" role="row" >
                                        <?php if(isset($tab_head)){ foreach ($tab_head as $tab_hd) { ?>
                                            <th><?php echo htmlentities($tab_hd, ENT_QUOTES); ?> </th>
                                        <?php } } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    if(isset($result) && !empty($result)) {
                                        foreach ($result as $re) {
                                            $fil_no = $reg_no = $case_details = $cnr_number = $cino = '';
                                            $fil_ia_no = $reg_ia_no = $cause_title = $fil_case_no = $reg_case_no = $dairy_no = $lbl_for_doc_no = $fil_misc_doc_ia_no = '';
                                            $efiling_for_type_id = get_court_type($re->efiling_for_type_id);
                                            $efiling_types_array = array(E_FILING_TYPE_MISC_DOCS, E_FILING_TYPE_IA, E_FILING_TYPE_MENTIONING ,DEFICIT_COURT_FEE_PAID);
                                            if (in_array($re->ref_m_efiled_type_id, $efiling_types_array)) {
                                                if ($re->ref_m_efiled_type_id == E_FILING_TYPE_MISC_DOCS) {
                                                    $type = 'Misc. Docs';
                                                    $button_label="Register Documents";
                                                    $lbl_for_doc_no = '<b>Misc. Doc. No.</b> : ';
                                                    $redirect_url = base_url('miscellaneous_docs/defaultController');
                                                } elseif($re->ref_m_efiled_type_id == E_FILING_TYPE_IA) {
                                                    $type = 'Interim Application';
                                                    $button_label="Register IA";
                                                    $lbl_for_doc_no = '<b>IA Diary No.</b> : ';
                                                    $redirect_url = base_url('IA/defaultController');
                                                }else {
                                                    $type = 'Mentioning';
                                                    $lbl_for_doc_no = '';
                                                    $redirect_url = base_url('mentioning/DefaultController');
                                                }
                                                if (!empty($re->loose_doc_no) && $re->loose_doc_year != '') {
                                                    $fil_misc_doc_ia_no = $lbl_for_doc_no . escape_data($re->loose_doc_no) . ' / ' . escape_data($re->loose_doc_year) . '<br/> ';
                                                } else {
                                                    $fil_misc_doc_ia_no = '';
                                                }
                                                if ($re->diary_no != '' && $re->diary_year != '') {
                                                    $dairy_no = '<b>Diary No.</b> : ' . escape_data($re->diary_no) . ' / ' . escape_data($re->diary_year) . '<br/> ';
                                                } else {
                                                    $dairy_no = '';
                                                }
                                                if ($re->reg_no_display != '') {
                                                    $reg_no = '<b>Registration No.</b> : ' . escape_data($re->reg_no_display) . '<br/> ';
                                                } else {
                                                    $reg_no = '';
                                                }
                                                $case_details = $fil_ia_no . '<b>Filed In</b> <br>' . $dairy_no . $reg_no . $re->cause_title;
                                                if ($dairy_no != '') {
                                                    // $case_details= '<a onClick="open_case_status()"  href=""  title="show CaseStatus"  data-diary_no="'.$re->diary_no.'" data-diary_year="'.$re->diary_year.'">'.$case_details.'</a>';
                                                }
                                            } elseif ($re->ref_m_efiled_type_id == E_FILING_TYPE_NEW_CASE || $re->ref_m_efiled_type_id==E_FILING_TYPE_JAIL_PETITION) {
                                                $type = 'New Case';
                                                $button_label="Generate Diary Number";
                                                $cause_title = escape_data(strtoupper($re->ecase_cause_title));
                                                $cause_title = str_replace('VS.', '<b>Vs.</b>', $cause_title);
                                                if ($re->sc_diary_num != '') {
                                                    $dairy_no = '<b>Diary No.</b> : ' . escape_data($re->sc_diary_num).'/'.escape_data($re->sc_diary_year).  '<br/> ';
                                                } else {
                                                    $dairy_no = '';
                                                }
                                                if ($re->reg_no_display != '') {
                                                    $reg_no = '<b>Registration No.</b> : ' . escape_data($re->sc_display_num) . '<br/> ';
                                                } else {
                                                    $reg_no = '';
                                                }
                                                $case_details =  $dairy_no . $reg_no . $cause_title;
                                                if ($dairy_no != '') {
                                                    // $case_details = '<a onClick="open_case_status()"  href=""  title="show CaseStatus"  data-diary_no="\'.$re->diary_no.\'" data-diary_year="\'.$re->diary_year.\'">\'.$case_details.\'</a>\'';
                                                }
                                                if($re->ref_m_efiled_type_id == E_FILING_TYPE_NEW_CASE)
                                                    $redirect_url = base_url('newcase/defaultController');
                                                else if($re->ref_m_efiled_type_id==E_FILING_TYPE_JAIL_PETITION)
                                                    $redirect_url = base_url('jailPetition/defaultController');
                                            } elseif ($re->ref_m_efiled_type_id == E_FILING_TYPE_CAVEAT) {
                                                $type = 'Caveat';
                                                $button_label="Generate Caveat Number";
                                                $caveator_name ='';
                                                $caveatee_name ='';
                                                if(isset($re->pet_name) && !empty($re->pet_name)){
                                                    $caveator_name = $re->pet_name;
                                                } else if(isset($re->orgid) && !empty($re->orgid) && $re->orgid == 'D1'){
                                                    $caveator_name = 'State Department';
                                                } else if(isset($re->orgid) && !empty($re->orgid) && $re->orgid == 'D2'){
                                                    $caveator_name = 'Central Department';
                                                } else if(isset($re->orgid) && !empty($re->orgid) && $re->orgid == 'D3'){
                                                    $caveator_name = 'Other Organisation';
                                                }
                                                if(isset($re->res_name) && !empty($re->res_name)){
                                                    $caveatee_name = $re->res_name;
                                                } else if(isset($re->resorgid) && !empty($re->resorgid) && $re->resorgid == 'D1'){
                                                    $caveatee_name = 'State Department';
                                                } else if(isset($re->resorgid) && !empty($re->resorgid) && $re->resorgid == 'D2'){
                                                    $caveatee_name = 'Central Department';
                                                } else if(isset($re->resorgid) && !empty($re->resorgid) && $re->resorgid == 'D3'){
                                                    $caveatee_name = 'Other Organisation';
                                                }
                                                $cause_title = strtoupper($caveator_name.'<b> Vs. </b>'. $caveatee_name);
                                                $case_details =   $cause_title;
                                                if ($dairy_no != '') {
                                                    $case_details = '<a href="'.CASE_STATUS_API.'?d_no=' . $re->sc_diary_num . '&d_yr=' . $re->sc_diary_year . '" target="_blank">' . $case_details . '</a>';
                                                }
                                                $redirect_url = base_url('caveat/defaultController/processing');
                                            }
                                            //START DIFICIT COURT FEE..DEFICIT_COURT_FEE_E_FILED
                                            /* elseif ($re->stage_id == DEFICIT_COURT_FEE_PAID){*/
                                            elseif ($re->stage_id == DEFICIT_COURT_FEE_E_FILED){
                                                $type = 'Deficit Court Fee Paid';
                                                $button_label="Generate Diary Number";
                                                if ($re->diary_no != '' && $re->diary_year != '') {
                                                    $dairy_no = '<b>Diary No./ Diary Year.</b> : ' . escape_data($re->diary_no) . ' / ' . escape_data($re->diary_year) . '<br/> ';
                                                } else {
                                                    $dairy_no = '';
                                                }
                                                if ($re->reg_no_display != '') {
                                                    $reg_no = '<b>Registration No.</b> : ' . escape_data($re->reg_no_display) . '<br/> ';
                                                } else {
                                                    $reg_no = '';
                                                }
                                                $case_details =  '<b>Filed In</b> <br>' . $dairy_no . $reg_no . $re->cause_title;
                                                $redirect_url = base_url('deficitCourtFee/DefaultController/get_view_paid_deficitCourt');
                                            }
                                            ?>
                                            <tr>
                                                <td width="4%" class="sorting_1" tabindex="0" data-key="<?php echo htmlentities($data_key[0]); ?>"><?php echo htmlentities($i++, ENT_QUOTES); ?> </td>
                                                <?php
                                                //-----------------Final Submited List------------------------------//
                                                if ($stages == New_Filing_Stage) { 
                                                    $tentaive_date=$re->tentaive_date=isset($re->tentaive_date) && !empty($re->tentaive_date) ? '<br/><span style="color:red;"> Listed for : '. date("d-m-Y", strtotime($re->tentaive_date)).'</span>': '';
                                                    $case_details=$case_details.$tentaive_date
                                                    ?>
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>"><?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[2]); ?>"><?php echo htmlentities($type, ENT_QUOTES) ?></td>
                                                    <td data-key="<?php echo htmlentities($data_key[3]);?>"><?php echo $case_details; ?></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <?php if (getSessionData('login')['userid'] != SC_ADMIN) {
                                                        // if ($re->is_register == 'Y' || $re->is_register == '') { ?>
                                                            <!-- <td width="10%">  <a class="form-control btn-primary link_button" href="<?php // echo $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . New_Filing_Stage . '#' . $re->efiling_no)) ?>"> <?php // echo htmlentities('Action', ENT_QUOTES) ?></a></td> -->
                                                        <?php // } else {
                                                            ?>
                                                            <!-- <td width="10%"> <input type="submit"  class="form-control btn-primary link_button" Value="Action" onclick="advocateRegister()"/> </td> -->
                                                            <?php
                                                        // }
                                                        if ($re->account_status == 1 || $re->account_status == 0) { ?>
                                                        <td width="10%" data-key="<?php echo htmlentities($data_key[4]); ?>">  <a class="quick-btn" href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . New_Filing_Stage . '#' . $re->efiling_no)) ?>"> <?php echo htmlentities('Action', ENT_QUOTES) ?></a></td>
                                                        <?php } else {
                                                            ?>
                                                            <td width="10%" data-key="<?php echo htmlentities($data_key[4]);?>"> <input type="submit"  class="quick-btn" Value="Action" onclick="advocateRegister()"/> </td>
                                                            <?php
                                                        }
                                                    } else{
                                                        echo '<td width="10%"></td>';
                                                    }
                                                }
                                                //-----------------Payment Awaited List------------------------------//
                                                if ($stages == Payment_Awaited_Stage) {
                                                    ?>
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>"><a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . Payment_Awaited_Stage . '#' . $re->efiling_no)) ?>"><?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?></a></td>                                                
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[2]); ?>"><?php echo htmlentities($type, ENT_QUOTES) ?></td>                                                
                                                    <td data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo $case_details; ?></td>                                                
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[4]);?>"><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <?php
                                                }
                                                //-----------------For Compliance------------------------------//
                                                if ($stages == Initial_Defected_Stage) {
                                                    ?>        
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>">
                                                    <a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . Initial_Defected_Stage . '#' . $re->efiling_no)) ?>"> <?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?></a></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[2]); ?>"><?php echo htmlentities($type, ENT_QUOTES) ?></td>
                                                    <td data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo $case_details; ?></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[4]); ?>"><?php echo htmlentities(date("d/m/Y h.i.s A", strtotime($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <?php
                                                }
                                                //-----------------Dashboard -- Get CIS Status---------------//
                                                if ($stages == Transfer_to_CIS_Stage) {
                                                    ?>
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>"><a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . Initial_Defected_Stage . '#' . $re->efiling_no)) ?>"> <?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?></a></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[2]); ?>"><?php echo htmlentities($type, ENT_QUOTES) ?></td>
                                                    <td data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo $case_details; ?></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[4]); ?>"><?php echo htmlentities(date("d/m/Y h.i.s A", strtotime($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[5]); ?>">
                                                        <?php if (getSessionData('login')['userid'] != SC_ADMIN){?>
                                                        <input type="button"  class="btn btn-primary" Value="Get Status From ICMIS"  onclick="submitAction_CIS('<?php echo htmlentities(url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . Transfer_to_CIS_Stage . '#' . $re->efiling_no)), ENT_QUOTES); ?>')"  />
                                                        <?php }?>
                                                    </td>
                                                    <?php
                                                }
                                                //-----------------Initial Defects Cures List------------------------------//
                                                if ($stages == Initial_Defects_Cured_Stage) {
                                                    ?>
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>"><?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[2]); ?>"><?php echo htmlentities($type, ENT_QUOTES) ?></td>
                                                    <td data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo $case_details; ?></td>                                        
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[4]); ?>"><?php echo htmlentities(date("d/m/Y h.i.s A", strtotime($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <td width="10%" data-key="<?php echo htmlentities($data_key[5]); ?>">
                                                        <?php if (getSessionData('login')['userid'] != SC_ADMIN) { ?>
                                                            <a class="quick-btn" href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . Initial_Defects_Cured_Stage . '#' . $re->efiling_no)) ?>"> 
                                                                <?php echo htmlentities('Action', ENT_QUOTES) ?>
                                                            </a>
                                                        <?php } ?>
                                                    </td>            
                                                    <?php
                                                }
                                                //-----------------Transfer to CIS List------------------------------//
                                                if ($stages == Transfer_to_IB_Stage) { //echo $redirect_url; exit;
                                                    ?>
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>"> 
                                                        <a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . Transfer_to_IB_Stage . '#' . $re->efiling_no)) ?>"> 
                                                            <?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?>
                                                        </a>
                                                    </td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[2]); ?>"><?php echo htmlentities($type, ENT_QUOTES) ?></td>
                                                    <td data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo $case_details; ?></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[4]); ?>"><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <?php if (getSessionData('login')['userid'] != SC_ADMIN) { ?>
                                                        <?php if($re->ref_m_efiled_type_id == E_FILING_TYPE_IA || $re->ref_m_efiled_type_id == E_FILING_TYPE_MISC_DOCS){
                                                            ?>
                                                            <td width="16%" data-key="<?php echo htmlentities($data_key[5]); ?>"> <input type="submit" class="quick-btn" Value="<?php echo htmlentities($button_label, ENT_QUOTES) ?>"  onclick="callController('<?php echo htmlentities(url_encryption(trim($re->icmis_diary_no .$re->icmis_diary_year.'#'.$re->registration_id), ENT_QUOTES)); ?>')"  /></td>
                                                        <?php } else { ?>
                                                            <!--<td width="12%"> <input type="submit" class="btn-success input-sm" Value="<?php /*echo htmlentities($button_label, ENT_QUOTES) */?>"  onclick="TransferToSection('<?php /*echo htmlentities(url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . Transfer_to_IB_Stage . '#' . $re->efiling_case_reg_id . '#' . $re->efiling_no), ENT_QUOTES)); */?>')"  />-->
                                                            <td width="16%" data-key="<?php echo htmlentities($data_key[5]); ?>"><a class="quick-btn" href="<?php  echo $redirect_url.'/'. htmlentities(url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . Transfer_to_IB_Stage . '#' . $re->efiling_no), ENT_QUOTES)); ?>"><?php echo $button_label;?></a></td>
                                                        <?php } ?>
                                                    <?php } else { echo '<td width="16%"></td>'; } ?>
                                                <?php } ?>
                                                <?php
                                                //-----------------Pending Scrutiny List------------------------------//
                                                if ($stages == I_B_Approval_Pending_Admin_Stage) {
                                                    ?>
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>"><a href="<?=base_url('newcase/view/').url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . I_B_Approval_Pending_Admin_Stage . '#' . $re->efiling_no)) ?>"> <?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?></a></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[2]); ?>"><?php echo htmlentities('New Case', ENT_QUOTES) ?></td>                                                                    
                                                    <td data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo $case_details; ?></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[4]); ?>"><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[5]); ?>">
                                                        Automated
                                                        <?php if (getSessionData('login')['userid'] != SC_ADMIN){ ?>
                                                            <!--<input type="button"  class="btn btn-primary" Value="Get Scrutiny Status" onclick="submitAction_CIS('<?php /*echo htmlentities(url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . I_B_Approval_Pending_Admin_Stage . '#' . $re->efiling_no)), ENT_QUOTES); */?>', '<?php /*echo htmlentities($re->ref_m_efiled_type_id, ENT_QUOTES); */?>')"  />-->
                                                        <?php } ?>
                                                    </td>
                                                    <?php
                                                }
                                                //-----------------Defective Cured List------------------------------//
                                                if ($stages == I_B_Defects_Cured_Stage) {
                                                    ?> 
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>"><a target="_blank" href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . I_B_Defects_Cured_Stage)) ?>"><?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?> </a></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[2]); ?>"><?php echo htmlentities($type, ENT_QUOTES) ?></td>                                
                                                    <td data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo $case_details; ?></a></td>                                
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[4]); ?>"><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[5]); ?>">
                                                        <?php if (getSessionData('login')['userid'] != SC_ADMIN){ ?>
                                                            <!--<input type="button"  class="btn btn-primary" Value="Get Scrutiny Status" onclick="submitAction_CIS('<?php /*echo htmlentities(url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . I_B_Defects_Cured_Stage . '#' . $re->efiling_no)), ENT_QUOTES); */?>', '<?php /*echo htmlentities($re->ref_m_efiled_type_id, ENT_QUOTES); */?>')"  />-->
                                                        <?php } ?>
                                                    </td>
                                                    <?php
                                                }

                                                if ($stages == I_B_Defected_Stage && $mark_as_error == MARK_AS_ERROR) {
                                                    ?>
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>">
                                                        <a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . I_B_Defected_Stage . '#' . $re->efiling_no)) ?>">
                                                            <?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?>
                                                        </a>
                                                    </td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[2]); ?>"><?php echo htmlentities($type, ENT_QUOTES) ?></td>
                                                    <td data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo $case_details; ?></td>
                                                    <td data-key="<?php echo htmlentities($data_key[4]); ?>"><?php echo ($re->stage_id == MARK_AS_ERROR) ? 'Marked as errors' : 'Defected'; ?></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[5]); ?>"><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <?php
                                                }
                                                //-----------------Defective List------------------------------//
                                                if ($stages == I_B_Defected_Stage && empty($mark_as_error)) {
                                                    ?> 
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>">
                                                        <a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . I_B_Defected_Stage . '#' . $re->efiling_no)) ?>"> 
                                                            <?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?>
                                                        </a>
                                                    </td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[2]); ?>"><?php echo htmlentities($type, ENT_QUOTES) ?></td>                                
                                                    <td data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo $case_details; ?></td>                                
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[4]); ?>"><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <?php
                                                }
                                                //-----------------Rejected List------------------------------//
                                                if ($stages == I_B_Rejected_Stage) {
                                                    ?> 
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>"><a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . I_B_Rejected_Stage . '#' . $re->efiling_no)) ?>"> <?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?></a></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[2]); ?>"><?php echo htmlentities($type, ENT_QUOTES) ?></td>                                    
                                                    <td data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo $case_details; ?></td>                                
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[4]); ?>"><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <?php if ($re->stage_id == I_B_Rejected_Stage) { ?>
                                                        <td width="12%" data-key="<?php echo htmlentities($data_key[5]); ?>"><?php echo htmlentities('Filing Section', ENT_QUOTES); ?></td>
                                                    <?php } elseif ($re->stage_id == E_REJECTED_STAGE) { ?>
                                                        <td width="12%" data-key="<?php echo htmlentities($data_key[6]); ?>"><?php echo htmlentities('eFiling Admin', ENT_QUOTES); ?></td>
                                                        <?php
                                                    }
                                                }
                                                //-----------------e-Filed Cases List------------------------------//
                                                if ($stages == E_Filed_Stage) {
                                                    ?>
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>">
                                                        <a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . E_Filed_Stage . '#' . $re->efiling_no)) ?>"> 
                                                            <?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?>
                                                        </a>
                                                    </td>
                                                    <td data-key="<?php echo htmlentities($data_key[2]); ?>"><?php echo $type; ?></td>
                                                    <td data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo $case_details; ?></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[4]); ?>"><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <?php
                                                }
                                                //-----------------e-Filed Document List------------------------------//
                                                if ($stages == Document_E_Filed) {
                                                    ?>
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>"><a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . Document_E_Filed . '#' . $re->efiling_no)) ?>"> <?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?></a></td>                                    
                                                    <td data-key="<?php echo htmlentities($data_key[2]); ?>"><?php echo $case_details; ?></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <?php
                                                }
                                                //-----------------e-Filed Deficit List------------------------------//
                                                if ($stages == DEFICIT_COURT_FEE_E_FILED) {
                                                    ?>
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>"><a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . DEFICIT_COURT_FEE_E_FILED . '#' . $re->efiling_no)) ?>"> <?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?></a>
                                                    </td>
                                                    <td data-key="<?php echo htmlentities($data_key[2]); ?>"><?php echo $case_details; ?></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <?php
                                                }
                                                if ($stages == DEFICIT_COURT_FEE) {
                                                    ?>
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>">
                                                        <a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . DEFICIT_COURT_FEE . '#' . $re->efiling_no)) ?>">
                                                            <?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?>
                                                        </a>
                                                    </td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[2]); ?>"><?php echo htmlentities($type, ENT_QUOTES) ?></td>
                                                    <td data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo $case_details; ?></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[4]); ?>"><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <?php
                                                }
                                                if ($stages == LODGING_STAGE || $stages == DELETE_AND_LODGING_STAGE) {
                                                    ?>
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>">
                                                        <a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . DELETE_AND_LODGING_STAGE . '#' . $re->efiling_no)) ?>"> 
                                                            <?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?>
                                                        </a>
                                                    </td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[2]); ?>"><?php echo htmlentities($type, ENT_QUOTES) ?></td>
                                                    <td data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo $case_details; ?></td>
                                                    <?php
                                                    if ($re->stage_id == LODGING_STAGE) {
                                                        $stages_name = 'Idle';
                                                    } elseif ($re->stage_id == DELETE_AND_LODGING_STAGE) {
                                                        $stages_name = 'Idle and Deleted';
                                                    } elseif ($re->stage_id == TRASH_STAGE) {
                                                        $stages_name = 'Trashed';
                                                    } else{
                                                        $stages_name = 'Marked as errors';
                                                    }
                                                    ?>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[4]); ?>"><?php echo htmlentities($stages_name, ENT_QUOTES); ?></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[5]); ?>"><?php echo htmlentities(date("d/m/Y h.i.s A", strtotime($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <?php
                                                }
                                                if ($stages == IA_E_Filed) { 
                                                    ?>
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>"><a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . IA_E_Filed . '#' . $re->efiling_no)) ?>"> <?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?></a>
                                                    </td>                                  
                                                    <td data-key="<?php echo htmlentities($data_key[2]); ?>">
                                                        <!-- <a href="<?php //echo base_url('stage_list/view_data_cino/' . url_encryption(htmlentities($re->ia_cnr_num ?? '' . '#' . $re->efiling_for_id . '#' . $re->efiling_for_type_id, ENT_QUOTES))); ?>"> -->
                                                            <?php echo $case_details; ?>
                                                        <!-- </a> -->
                                                    </td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <?php
                                                }
                                                if ($stages == MENTIONING_E_FILED) {
                                                    ?>
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>"><a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . MENTIONING_E_FILED . '#' . $re->efiling_no)) ?>"> <?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?></a>
                                                    </td>                                  
                                                    <td data-key="<?php echo htmlentities($data_key[2]); ?>">
                                                        <!-- <a href="<?php //echo base_url('stage_list/view_data_cino/' . url_encryption(htmlentities($re->ia_cnr_num ?? '' . '#' . $re->efiling_for_id . '#' . $re->efiling_for_type_id, ENT_QUOTES))); ?>"> -->
                                                            <?php echo $case_details; ?>
                                                        <!-- </a> -->
                                                    </td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <?php
                                                }
                                                if ($stages == HOLD) {  
                                                    ?>
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>"><a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . IA_E_Filed . '#' . $re->efiling_no)) ?>"> <?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?></a>
                                                    </td>
                                                    <td data-key="<?php echo htmlentities($data_key[2]); ?>"><?php echo $case_details; ?></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[4]); ?>"><?php echo $re->efiling_type; ?></td>
                                                    <?php
                                                }
                                                if ($stages == DISPOSED) {
                                                    ?>
                                                    <td width="14%" data-key="<?php echo htmlentities($data_key[1]); ?>"><a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . IA_E_Filed . '#' . $re->efiling_no)) ?>"> <?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?></a></td>
                                                    <td data-key="<?php echo htmlentities($data_key[2]); ?>">
                                                        <!-- <a href="<?php //echo base_url('stage_list/view_data_cino/' . url_encryption(htmlentities($re->ia_cnr_num ?? '' . '#' . $re->efiling_for_id . '#' . $re->efiling_for_type_id, ENT_QUOTES))); ?>"> -->
                                                            <?php echo $case_details; ?>
                                                        </a>
                                                    </td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[3]); ?>"><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                                    <td width="12%" data-key="<?php echo htmlentities($data_key[4]); ?>"><?php echo $re->efiling_type; ?></td>
                                                <?php } ?>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- <div class="pagination-area-new">
                            <form method="post" action="<?php // echo base_url('adminDashboard/stageList/' . url_encryption($stages)); ?>">
                                <div class="pagination-new-inner">
                                    <div class="showing-details-limit" >
                                        <label for="limit" class="pull-left page-label"> Limit:</label>
                                        <div class="pull-left">
                                            <select id="limit" name="limit" class="form-control" onchange="$('#page_input').val(1);this.form.submit(); updateNames(this)">
                                                <option value="10" <?php // if ($limit == '10') { ?> selected="selected"<?php // } ?> > 10</option>
                                                <option value="25" <?php // if ($limit == '25') { ?> selected="selected"<?php // } ?> > 25</option>
                                                <option value="50" <?php // if ($limit == '50') { ?> selected="selected"<?php // } ?>  > 50</option>
                                                <option value="100" <?php // if ($limit == '100') { ?> selected="selected"<?php // } ?>  > 100</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="pagination-btns">
                                        <input type="hidden" name="page_no_use" id="page" value="<?php // echo $page; ?>" class="form-control">
                                        <div class="paging paging-prev">
                                            <button class="btn btn-sm btn-primary" onclick="$('#page_input').val(1); this.form.submit();">First</button>
                                            <button class="btn btn-sm btn-primary" onclick="$('#page_input').val(<?php // echo ($page == 1) ? 1 : ($page - 1); ?>); this.form.submit();">Prev</button>
                                        </div>
                                        <div class="paging paging-input">
                                            &nbsp;Page
                                            <input type="text" name="page" id="page_input" value="<?php // echo $page; ?>" class="form-control" onkeydown="if (event.keyCode == 13) { this.form.submit(); return false; }">
                                            of <?php // echo isset($pages) ? $pages : ''; ?>
                                            &nbsp;
                                        </div>
                                        <div class="paging paging-next">
                                            <button class="btn btn-sm btn-primary" onclick="$('#page_input').val(<?php // echo (isset($pages) && $page == $pages) ? $pages : ($page + 1); ?>); this.form.submit();">Next</button>
                                            <button class="btn btn-sm btn-primary" onclick="$('#page_input').val(<?php // echo isset($pages) ? $pages : ''; ?>); this.form.submit();">Last</button>
                                        </div>
                                    </div> 
                                </div>
                            </form>
                        </div> -->
                    </div>
                </div>
            </div>
	    </div>
	</div>
</div>
<div class="modal fade" id="bsModal3" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_cis" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="mySmallModalLabel"></h4>
            </div>
            <div class="modal-body formresponse"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default close_cis" >OK</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<!-- <script src="<?= base_url() ?>assets/newAdmin/js/jquery.dataTables.min.js"></script> -->
<script>
    // $(document).ready(function() {
    //     window.onload = function() {
    //         var limitValue = '<?php // echo $limit; ?>';
    //         $('select[name="datatable-responsive_length"]').val(limitValue).change(); 
    //     };
    // });
    $(document).ready(function() {
        $('#datatable-responsive').DataTable({
            'paging' : true
        });
    });
    function TransferToSection(frm_num) {
        y = confirm("Are you sure want to transfer ?");
        if (y == false) {
            return false;
        }
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#loading_spinner').show();
        $.ajax({
            type: "POST",
            url: "<?= base_url(); ?>admin/EfilingAction/transfer_to_section",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, form_submit: frm_num},
            success: function (result) {
                var responce = result.split('||||');
                if (responce[0] == 'SUCCESS') {                    
                    window.location.href = '<?= base_url() ?>newcase/defaultController/' + responce[1];
                } else if (responce[0] == 'ERROR') {
                    $('#msg').show();
                    $('#msg').html(responce[1]);
                } else {
                    window.location.href = '<?= base_url() ?>adminDashboard';
                }
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function (result) {
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
    function hideMessageDiv() {
        document.getElementById('msg').style.display = "none";
    }
    setTimeout("hideMessageDiv()", 5000);
    $('.scr_date').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        maxDate: new Date(),
        dateFormat: "dd-mm-yy",
        yearRange: '2000:2099'
    });
    function callController(num) {
        window.location.href='<?php echo base_url('admin/EfilingAction/getCISData?diaryno=');?>'+num;
    }
    function submitAction_CIS(get_id) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        openModal();
        $.ajax({
            type: "POST",
            url: "<?= base_url('getCIS_status/get_efiling_num_status') ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, form_submit: get_id},
            success: function (result) {
                closeModal();
                var resArr = result.split('@@@');
                if (resArr[0] == 1) {
                    alert(resArr[1]);
                } else if (resArr[0] == 2) {
                    $('#msg_res').show();
                    $("#bsModal3").modal('show');
                    $("#mySmallModalLabel").html(resArr[1]);
                    $(".formresponse").html(resArr[2]);
                } else {
                    alert(resArr[0]);
                    location.reload();
                }
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function (result) {
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
    $(document).ready(function () {
        $(".close_cis").click(function () {
            $("#bsModal3").modal('hide');
            location.reload();
        });
    });
    function advocateRegister() {
        alert('Advocate\'s (who filed this efiling number) details are not present in your local CIS. His/her information is present under " Registeration " menu on left side. Please first get his/her details updated in local CIS and then update his/her status in efiling application from the same menu.')
    }
</script>
@endpush