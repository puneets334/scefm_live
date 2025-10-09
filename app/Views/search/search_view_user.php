<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title"><h3>Search Results</h3></div>
                <div class="x_content">
                    <div class="table-wrapper-scroll-y my-custom-scrollbar ">
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr class="success input-sm" role="row" >
                                    <th>#</th>
                                    <th>eFiling No.</th>
                                    <th>Type</th>
                                    <th>Case Details</th>
                                    <th>Current Stage</th>
                                    <th>Activated On</th>
                                </tr>                            
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($result_data as $re) {
                                    $fil_no = $reg_no = $case_details = $cnr_number = $cino = '';
                                    $fil_ia_no = $reg_ia_no = $cause_title = $fil_case_no = $reg_case_no = $dairy_no = $lbl_for_doc_no = $fil_misc_doc_ia_no = '';

                                    $efiling_for_type_id = get_court_type($re->efiling_for_type_id);
                                    
                                    $efiling_types_array = array(E_FILING_TYPE_MISC_DOCS, E_FILING_TYPE_IA, E_FILING_TYPE_MENTIONING);
                                    
                                    if ( in_array($re->ref_m_efiled_type_id, $efiling_types_array)){

                                        if ($re->ref_m_efiled_type_id == E_FILING_TYPE_MISC_DOCS) {
                                            $type = 'Misc. Docs';
                                            $lbl_for_doc_no = '<b>Misc. Doc. No.</b> : ';
                                            $redirect_url = base_url('miscellaneous_docs/DefaultController');
                                        } elseif($re->ref_m_efiled_type_id == E_FILING_TYPE_IA) {
                                            $type = 'Interim Application';
                                            $lbl_for_doc_no = '<b>IA Diary No.</b> : ';
                                            $redirect_url = base_url('IA/DefaultController');
                                        }else{
                                            $type = 'Mentioning';
                                            $lbl_for_doc_no = '';
                                            $redirect_url = base_url('mentioning/DefaultController');
                                        }
                                        
                                        if ($re->loose_doc_no != '' && $re->loose_doc_year != '') {
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
                                            $case_details = '<a href="<?=\'CASE_STATUS_API\'?>?d_no=' . $re->diary_no . '&d_yr=' . substr($re->diary_year, -4) . '" target="_blank">' . $case_details . '</a>';
                                        }
                                        
                                    }elseif ($re->ref_m_efiled_type_id == E_FILING_TYPE_NEW_CASE) {
                                        
                                        $type = 'New Case';
                                        
                                        $cause_title = escape_data(strtoupper($re->ecase_cause_title));
                                        $cause_title = str_replace('VS.', '<b>Vs.</b>', $cause_title);

                                        if ($re->sc_diary_num != '') {
                                            $dairy_no = '<b>Diary No.</b> : ' . escape_data($re->sc_diary_num).'/'.escape_data($re->sc_diary_year). '<br/> ';
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

                                        $redirect_url = base_url('newcase/defaultController');
                                    }
                                    
                                    ?>
                                    <tr>
                                        <td width="4%" class="sorting_1" tabindex="0"><?php echo htmlentities($i++, ENT_QUOTES); ?> </td>
                                        <td width="14%"><a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . $re->stage_id)) ?>"> <?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?></a></td>
                                        <td width="10%"><?php echo htmlentities($type, ENT_QUOTES) ?></td>                                        
                                        <td><?php echo $case_details; ?></td>
                                        <td width="14%"><?php echo htmlentities($re->user_stage_name, ENT_QUOTES); ?></td>
                                        <td width="14%"><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
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
</style>
