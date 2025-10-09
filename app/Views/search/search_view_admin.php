<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title"><h3>Search Results</h3></div>
                <div class="x_content">
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr class="success input-sm" role="row" >
                                <th>#</th>
                                <th>eFiling No.</th>
                                <th>Type</th>
                                <th>Case Details</th>
                                <th>Current Stage<br>Activated On</th>
                                <th>Action</th>
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
                                            $case_details = '<a href="<?=\'CASE_STATUS_API\'?>?d_no=' . $re->sc_diary_num . '&d_yr=' . $re->sc_diary_year . '" target="_blank">' . $case_details . '</a>';
                                        }

                                        $redirect_url = base_url('newcase/defaultController');
                                    }
                                ?>
                                <tr>
                                    <td width="4%" class="sorting_1" tabindex="0"><?php echo htmlentities($i++, ENT_QUOTES); ?> </td>
                                    <td width="14%"><?php echo htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) ?></td>
                                    <td width="10%"><?php echo htmlentities($type, ENT_QUOTES) ?></td>                                        
                                    <td><?php echo $case_details; ?></td>
                                    <td width="14%"><?php echo '<b>' . htmlentities($re->admin_stage_name, ENT_QUOTES); ?></b><br><?php echo date("d/m/Y h.i.s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))); ?></td>
                                    <?php
                                    if ($re->is_register == 'Y' || $re->is_register == '') {
                                        $not_in_account_label = '';
                                        if ($re->admin_user_id != $_SESSION['login']['id']) {
                                            $not_in_account_label = "Allocated to <br><b>" . $re->admin_name . '</b>';
                                        }
                                        if ($re->admin_user_id != $_SESSION['login']['id']) {
                                            ?>
                                            <td width="14%">
                                                <?php
                                                echo $not_in_account_label;
                                                $attribute = array('class' => 'add_reason', 'name' => 'frm_reason_' . $i . '', 'id' => 'frm_reason_' . $i . '', 'autocomplete' => 'off');
                                                echo form_open('#', $attribute);
                                                ?>
                                                <select id="reason_type_<?php echo $i; ?>" name="reason_type" class="allocation_reason" required="">
                                                    <option value="">Select</option>

                                                    <?php
                                                    if (count($reason_list)) {
                                                        foreach ($reason_list as $dataRes) {
                                                            echo '<option value="' . htmlentities(url_encryption(trim($dataRes->id . '#$' . $dataRes->reason), ENT_QUOTES)) . '">' . htmlentities((string) $dataRes->reason, ENT_QUOTES) . '</option>';
                                                        }
                                                        echo '<option value="' . url_encryption('4' . '#$Other') . '">Other</option>';
                                                    } else {
                                                        echo '<option value="' . url_encryption('4' . '#$Other') . '">Other</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <span id="write_reason_<?php echo $i; ?>" class="write_reason" style="display:none;"><input type="text" name="reason_to_allocate" id="reason_to_allocate_<?php echo $i; ?>" placeholder="Write reason.."></span>
                                                <input type="hidden" name="allocate_to_user" value="<?php echo url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . New_Filing_Stage . '#' . $re->admin_user_id)); ?>">
                                                <button type="submit" id="reason_to_allocate_<?php echo $i; ?>" name="add_reason_to_allocate" class="form-control btn-primary add_reason_to_allocate"> Add to my account & Action</button>
                                                <?php echo form_close(); ?>
                                            </td>
                                            <?php
                                        } else {
                                            ?>
                                            <td> <?php echo $not_in_account_label; ?> <a class="form-control btn-primary" href="<?= $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . New_Filing_Stage)) ?>"><?php echo htmlentities('Action', ENT_QUOTES) ?></a></td>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <td> <?php echo $not_in_account_label; ?> <input type="submit"  class="form-control btn-primary" Value="Action" onclick="advocateRegister()"/> </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    th{font-size: 13px;color: #000;} 
    td{font-size: 13px;color: #000;} 
</style>
<script type="text/javascript">

    $('.add_reason_to_allocate').on('click', function () {

        var row_id = this.id;
        var row_id_arr = row_id.split('_');
        var id = row_id_arr[3];

        if ($('#frm_reason_' + id).valid()) {
            var form_data = $('#frm_reason_' + id).serialize();

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#modal_loader').show();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('admin/efiling_no_allocated'); ?>",
                data: form_data,
                async: false,
                success: function (data) {
                    var resArr = data.split('@@@');
                    if (resArr[0] == 1) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        setTimeout(function () {
                            $(".form-response").hide();
                        }, 2000);

                    } else if (resArr[0] == 2) {
                        window.location.href = resArr[1];
                    }

                    $.getJSON("<?php echo base_url('csrf_token') ; ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function () {
                    $.getJSON("<?php echo base_url('csrf_token') ; ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
            return false;
        } else {
            return false;
        }

    });

    $('.allocation_reason').on('change', function () {

        var row_id = this.id;
        var row_id_arr = row_id.split('_');
        var id = row_id_arr[2];

        var reason_id = this.value;
        var match_val = reason_id.substr(0, 2);
        if (match_val == '48') {
            $('#write_reason_' + id).show();
            $('#reason_to_allocate_' + id).val();
            $('#reason_to_allocate_' + id).prop('required', true);

        } else {
            $('#reason_to_allocate_' + id).val('');
            $('#write_reason_' + id).hide();
            $('#reason_to_allocate_' + id).prop('required', false);
        }
    });
</script>