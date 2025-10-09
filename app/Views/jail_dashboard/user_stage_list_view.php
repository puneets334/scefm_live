<div class="right_col" role="main">      
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-response" id="msg" >
                <?php
                if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
                    echo $_SESSION['MSG'];
                } unset($_SESSION['MSG']);
                ?></div>
            <div class="x_panel">
                <div class="x_title"> <h3><?= htmlentities($tabs_heading, ENT_QUOTES) ?> <span style="float:right;">  <a class="btn btn-info btn-sm" type="button" onclick="window.history.back()" /> Back</a></span></h3></div>
                <div class="x_content"> 
                    <div class="table-wrapper-scroll-y my-custom-scrollbar ">
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr class="success input-sm" role="row" >
                                    <?php foreach ($tab_head as $tab_hd) { ?>
                                        <th><?php echo htmlentities($tab_hd, ENT_QUOTES) ?> </th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $i = 1;

                                foreach ($result as $re) {

                                    $fil_no = $reg_no = $case_details = $cnr_number = $cino = '';
                                    $fil_ia_no = $reg_ia_no = $cause_title = $fil_case_no = $reg_case_no = $dairy_no = $lbl_for_doc_no = $fil_misc_doc_ia_no = '';
                                    $redirect_url="";
                                    $efiling_for_type_id = get_court_type($re->efiling_for_type_id);
                                    if ($re->ref_m_efiled_type_id == E_FILING_TYPE_JAIL_PETITION) {
                                        
                                        $type = 'New Case';
                                        
                                        $cause_title = escape_data(strtoupper($re->ecase_cause_title));
                                        $cause_title = str_replace('VS.', '<b>Vs.</b>', $cause_title);

                                        if ($re->sc_diary_num != '') {
                                            $dairy_no = '<b>Dairy No.</b> : ' . escape_data($re->sc_diary_num).'/'.escape_data($re->sc_diary_year). '<br/> ';
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
                                            $case_details = '<a href="<?=CASE_STATUS_API ?>?d_no=' . $re->sc_diary_num . '&d_yr=' . $re->sc_diary_year . '" target="_blank">' . $case_details . '</a>';
                                        }

                                        $redirect_url = base_url('jailPetition/defaultController');
                                    }
                                    ?>

                                    <tr>
                                        <td width="4%" class="sorting_1" tabindex="0"><?php echo $i++; ?> </td>

                                        <!--------------------Pending Acceptence-------------------------->
                                        <?php
                                        if ($stages == Initial_Approaval_Pending_Stage) {
                                            ?>                                        
                                            <td width="14%">
                                                <a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . Initial_Approaval_Pending_Stage)) ?>"> <?php echo "<b>" . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . "</b>" . "<br>" . htmlentities($re->efiling_for_name, ENT_QUOTES) . $allocated ?></a></td>
                                            <td  width="12%"><?php echo htmlentities($type, ENT_QUOTES) ?></td>
                                            <td><?php echo $case_details; ?></td>
                                            <td width="12%"><?php echo htmlentities(date("d/m/Y h.i.s A", strtotime($re->activated_on, ENT_QUOTES))); ?></td>
                                        <?php } ?>

                                        <!--------------------Draft------------------>

                                        <?php
                                        if ($stages == Draft_Stage) {
                                            ?>                                     

                                            <td width="14%"><?php echo "<b>" . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . "</b>" . "<br>" . htmlentities($re->efiling_for_name, ENT_QUOTES) . $allocated; ?></td>
                                            <td width="12%"><?php echo htmlentities($type, ENT_QUOTES) ?></td>
                                            <td><?php echo $case_details; ?></td>
                                            <td width="12%"><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                            <td width="12%"> 
                                                <a class="form-control btn btn-success" href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . Draft_Stage)) ?>"> <?php echo htmlentities("View", ENT_QUOTES) ?></a>  
                                            </td>


                                        <?php } ?>

                                        <!--------------------E-filed Cases------------------>

                                        <?php
                                        if ($stages == E_Filed_Stage) {
                                            ?> 
                                            <td width="14%">
                                                <a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . E_Filed_Stage)) ?>"> <?php echo "<b>" . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . "</b>" . "<br>" . htmlentities($re->efiling_for_name, ENT_QUOTES); ?></a>  
                                            </td>                                        
                                            <td><?php echo $case_details; ?></td>
                                            <td width="12%"><?php echo htmlentities(date("d/m/Y h.i.s A", strtotime($re->activated_on, ENT_QUOTES))); ?></td>

                                        <?php } ?>

                                        <?php
                                        if ($stages == I_B_Rejected_Stage) {
                                            ?>                                   
                                            <td width="14%">
                                                <a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . I_B_Rejected_Stage)) ?>"><?php echo "<b>" . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . "</b>" . "<br>" . htmlentities($re->efiling_for_name, ENT_QUOTES); ?></a>    
                                            </td>
                                            <td width="12%"><?php echo htmlentities($type, ENT_QUOTES) ?></td>
                                            <td><?php echo $case_details; ?></td>                                        
                                            <td width="12%"><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                            <?php if ($re->stage_id == I_B_Rejected_Stage) { ?>
                                                <td width="12%"><?php echo htmlentities('Filing Section', ENT_QUOTES); ?></td>
                                            <?php } elseif ($re->stage_id == E_REJECTED_STAGE) { ?>
                                                <td width="12%"><?php echo htmlentities('eFiling Admin', ENT_QUOTES); ?></td>
                                                <?php
                                            }
                                        }
                                        }
                                        ?>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<style>
    th{font-size: 13px;color: #000;} 
    td{font-size: 13px;color: #000;} 

</style>   <!-- /page content -->


