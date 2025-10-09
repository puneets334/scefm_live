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
                                    $fil_ia_no = $reg_ia_no = $cause_title = $fil_case_no = $reg_case_no = '';

                                    $efiling_for_type_id = get_court_type($re->efiling_for_type_id);

                                    if ($re->ref_m_efiled_type_id == E_FILING_TYPE_MISC_DOCS) {
                                        $type = 'Misc. Docs';

                                        if ($re->case_type_name != '' && $re->misc_fil_no != '' && $re->misc_fil_year != '') {
                                            $fil_no = '<b>Filing No.</b> : ' . htmlentities($re->case_type_name, ENT_QUOTES) . ' / ' . htmlentities($re->misc_fil_no, ENT_QUOTES)
                                                    . ' / ' . htmlentities($re->misc_fil_year, ENT_QUOTES) . '<br/> ';
                                        } else {
                                            $fil_no = '';
                                        }

                                        if ($re->case_type_name != '' && $re->misc_reg_no != '' && $re->misc_reg_year != '') {
                                            $reg_no = '<b>Registration No.</b> : ' . htmlentities($re->case_type_name, ENT_QUOTES) . ' / ' . htmlentities($re->misc_reg_no, ENT_QUOTES)
                                                    . ' / ' . htmlentities($re->misc_reg_year, ENT_QUOTES) . '<br/> ';
                                        } else {
                                            $reg_no = '';
                                        }

                                        $case_details = '<b>Filed In</b> <br><b>CNR No.</b> : ' . htmlentities(cin_preview($re->cnr_num), ENT_QUOTES) . '<br/> ' . $fil_no . $reg_no . htmlentities($re->cause_title, ENT_QUOTES);

                                        $case_details = '<a href="' . base_url('stage_list/view_data_cino/' . url_encryption(htmlentities($re->cnr_num . '#' . $re->efiling_for_id . '#' . $re->efiling_for_type_id, ENT_QUOTES))) . '">' . $case_details . '</a>';
                                        $redirect_url = base_url('MiscellaneousFiling/processing');
                                    } elseif ($re->ref_m_efiled_type_id == E_FILING_TYPE_DEFICIT_COURT_FEE) {

                                        $type = 'Deficit Fee';

                                        if ($re->case_type_name != '' && $re->misc_fil_no != '' && $re->misc_fil_year != '') {
                                            $fil_no = '<b>Filing No.</b> : ' . htmlentities($re->case_type_name, ENT_QUOTES) . ' / ' . htmlentities($re->misc_fil_no, ENT_QUOTES)
                                                    . ' / ' . htmlentities($re->misc_fil_year, ENT_QUOTES) . '<br/> ';
                                        } else {
                                            $fil_no = '';
                                        }

                                        if ($re->case_type_name != '' && $re->misc_reg_no != '' && $re->misc_reg_year != '') {
                                            $reg_no = '<b>Registration No.</b> : ' . htmlentities($re->case_type_name, ENT_QUOTES) . ' / ' . htmlentities($re->misc_reg_no, ENT_QUOTES)
                                                    . ' / ' . htmlentities($re->misc_reg_year, ENT_QUOTES) . '<br/> ';
                                        } else {
                                            $reg_no = '';
                                        }

                                        $case_details = '<b>Filed In</b> <br><b>CNR No.</b> : ' . htmlentities(cin_preview($re->cnr_num), ENT_QUOTES) . '<br/> ' . $fil_no . $reg_no . htmlentities($re->cause_title, ENT_QUOTES);

                                        $case_details = '<a href="' . base_url('stage_list/view_data_cino/' . url_encryption(htmlentities($re->cnr_num . '#' . $re->efiling_for_id . '#' . $re->efiling_for_type_id, ENT_QUOTES))) . '">' . $case_details . '</a>';

                                        $redirect_url = base_url('Deficit_court_fee/processing');
                                    } elseif ($re->ref_m_efiled_type_id == E_FILING_TYPE_IA) {


                                        $type = 'Interim Application';

                                        if (strlen($re->cnr_num) == CNR_LENGTH) {
                                            $cino = $re->cnr_num;
                                            $cnr_number = '<b>CNR No.</b> : ' . htmlentities(cin_preview($re->cnr_num), ENT_QUOTES) . '<br/> ';
                                        } else {
                                            $efiling_number = '<b>Efiling-No.</b> : ' . htmlentities(cin_preview($re->cnr_num), ENT_QUOTES) . '<br/> ';
                                        }


                                        if ($re->ia_cnr_num != '') {
                                            $cino = $re->ia_cnr_num;
                                            $cnr_number = '<b>CNR No.</b> : ' . htmlentities(cin_preview($re->ia_cnr_num), ENT_QUOTES) . '<br/> ';
                                        }


                                        if ($re->ia_fil_case_type_name != '' && $re->ia_filno != '' && $re->ia_filyear != '') {
                                            $fil_ia_no = '<b>IA Filing No.</b> : ' . htmlentities($re->ia_fil_case_type_name, ENT_QUOTES) . ' / ' . htmlentities($re->ia_filno, ENT_QUOTES)
                                                    . ' / ' . htmlentities($re->ia_filyear, ENT_QUOTES) . '<br/> ';
                                        } else {
                                            $fil_ia_no = '';
                                        }

                                        if ($re->ia_reg_case_type_name != '' && $re->ia_regno != '' && $re->ia_regyear != '') {
                                            $reg_ia_no = '<b>IA Registration No.</b> : ' . htmlentities($re->ia_reg_case_type_name, ENT_QUOTES) . ' / ' . htmlentities($re->ia_regno, ENT_QUOTES)
                                                    . ' / ' . htmlentities($re->ia_regyear, ENT_QUOTES) . '<br/> ';
                                        } else {
                                            $reg_ia_no = '';
                                        }
                                        if (strlen($re->cnr_num) == CNR_LENGTH) {

                                            if ($re->case_type_name != '' && $re->misc_fil_no != '' && $re->misc_fil_year != '') {
                                                $fil_no = '<b>Filing No.</b> : ' . htmlentities($re->case_type_name, ENT_QUOTES) . ' / ' . htmlentities($re->misc_fil_no, ENT_QUOTES)
                                                        . ' / ' . htmlentities($re->misc_fil_year, ENT_QUOTES) . '<br/> ';
                                            } else {
                                                $fil_no = '';
                                            }

                                            if ($re->case_type_name != '' && $re->misc_reg_no != '' && $re->misc_reg_year != '') {
                                                $reg_no = '<b>Registration No.</b> : ' . htmlentities($re->case_type_name, ENT_QUOTES) . ' / ' . htmlentities($re->misc_reg_no, ENT_QUOTES)
                                                        . ' / ' . htmlentities($re->misc_reg_year, ENT_QUOTES) . '<br/> ';
                                            } else {
                                                $reg_no = '';
                                            }
                                        } else {
                                            if ($re->fil_case_type_name != '' && $re->fil_no != '' && $re->fil_year != '') {
                                                $fil_no = '<b>Filing No.</b> : ' . htmlentities($re->fil_case_type_name, ENT_QUOTES) . ' / ' . htmlentities($re->fil_no, ENT_QUOTES)
                                                        . ' / ' . htmlentities($re->fil_year, ENT_QUOTES) . '<br/> ';
                                            } else {
                                                $fil_no = '';
                                            }

                                            if ($re->reg_case_type_name != '' && $re->reg_no != '' && $re->reg_year != '') {
                                                $reg_no = '<b>Registration No.</b> : ' . htmlentities($re->reg_case_type_name, ENT_QUOTES) . ' / ' . htmlentities($re->reg_no, ENT_QUOTES)
                                                        . ' / ' . htmlentities($re->reg_year, ENT_QUOTES) . '<br/> ';
                                            } else {
                                                $reg_no = '';
                                            }
                                        }

                                        $case_details = $fil_ia_no . $reg_ia_no . '<b>Filed In</b> <br>' . $efiling_number . $cnr_number . $fil_no . $reg_no . htmlentities($re->cause_title, ENT_QUOTES);
                                        if ($cino != '') {
                                            $case_details = '<a href="' . base_url('stage_list/view_data_cino/' . url_encryption(htmlentities($cino . '#' . $re->efiling_for_id . '#' . $re->efiling_for_type_id, ENT_QUOTES))) . '">' . $case_details . '</a>';
                                        }

                                        $redirect_url = base_url('IA/processing');
                                    } elseif ($re->ref_m_efiled_type_id == E_FILING_TYPE_NEW_CASE) {

                                        if ($re->pet_name == "") {
                                            $petname = "Petitioner";
                                        } else {
                                            $petname = $re->pet_name;
                                        }
                                        if ($re->res_name == "") {
                                            $resname = "Respondent";
                                        } else {
                                            $resname = $re->res_name;
                                        }

                                        $type = 'New Case';
                                        $cause_title = htmlentities($petname . ' Vs. ' . $resname, ENT_QUOTES);

                                        if ($re->cino != '') {
                                            $cino = '<b>CNR No.</b> : ' . htmlentities(cin_preview($re->cino), ENT_QUOTES) . '<br/> ';
                                        } else {
                                            $cino = '';
                                        }
                                        if ($re->fil_case_type_name != '' && $re->fil_no != '' && $re->fil_year != '') {
                                            $fil_case_no = '<b>Filing No.</b> : ' . htmlentities($re->fil_case_type_name, ENT_QUOTES) . ' / ' . htmlentities($re->fil_no, ENT_QUOTES)
                                                    . ' / ' . htmlentities($re->fil_year, ENT_QUOTES) . '<br/> ';
                                        } else {
                                            $fil_case_no = '';
                                        }

                                        if ($re->reg_case_type_name != '' && $re->reg_no != '' && $re->reg_year != '') {
                                            $reg_case_no = '<b>Registration No.</b> : ' . htmlentities($re->reg_case_type_name, ENT_QUOTES) . ' / ' . htmlentities($re->reg_no, ENT_QUOTES)
                                                    . ' / ' . htmlentities($re->reg_year, ENT_QUOTES) . '<br/> ';
                                        } else {
                                            $reg_case_no = '';
                                        }


                                        $case_details = $cino . $fil_case_no . $reg_case_no . htmlentities($cause_title, ENT_QUOTES);
                                        if ($cino != '') {
                                            $case_details = '<a href="' . base_url('stage_list/view_data_cino/' . url_encryption(htmlentities($re->cino . '#' . $re->efiling_for_id . '#' . $re->efiling_for_type_id, ENT_QUOTES))) . '">' . $case_details . '</a>';
                                        }

                                        $redirect_url = base_url('new_case/processing');
                                    }
                                    ?>
                                    <tr>
                                        <td width="4%" class="sorting_1" tabindex="0"><?php echo htmlentities($i++, ENT_QUOTES); ?> </td>
                                        <td width="14%"><a href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . $re->Draft_Stage)) ?>"> <?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?></a></td>
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
