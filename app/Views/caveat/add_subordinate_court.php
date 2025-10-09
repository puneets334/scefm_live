<?php
if ($_SESSION['estab_details']['efiling_for_type_id'] == E_FILING_FOR_HIGHCOURT ) {
    include 'subordinate_court_hc.php';
}
else if($_SESSION['estab_details']['efiling_for_type_id'] == E_FILING_FOR_SUPREMECOURT ) {
    $this->load->view('templates/header');
    $this->load->view('caveat/caveat_breadcrumb');
    $this->load->view('caveat/subordinate_court_hc_caveat');
    $this->load->view('templates/footer');
    //include 'subordinate_court_hc_caveat.php';
}
else if ($_SESSION['estab_details']['efiling_for_type_id'] == E_FILING_FOR_ESTABLISHMENT) {
    ?>
    <div class="panel panel-default">
        <h4 style="text-align: center;color: #31B0D5">Earlier Courts Information </h4>
        <div class="panel-body">
            <?php
            $attribute = array('class' => 'form-horizontal form-label-left', 'name' => 'subordinate_court_info', 'id' => 'subordinate_court_info', 'autocomplete' => 'off');
            echo form_open('#', $attribute);
            ?>
            <div class="row">
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">State <span style="color: red">*</span> :</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <select name="sub_state_id" id="sub_state_id" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                                <option value="" title="Select">Select State</option>
                                <?php
                                if (count($state_list)) {
                                    foreach ($state_list as $dataRes) {
                                        if ($efiling_caveat_data[0]['lower_court_state'] == (string) $dataRes->STATECODE) {
                                            $sel = 'selected=selected';
                                        } else {
                                            $sel = "";
                                        }
                                        echo '<option ' . htmlentities($sel, ENT_QUOTES) . ' value="' . htmlentities(url_encryption(trim((string) $dataRes->STATECODE . '#$' . (string) $dataRes->STATENAME . '$$state'), ENT_QUOTES)) . '">' . htmlentities((string) $dataRes->STATENAME, ENT_QUOTES) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> District :</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <select name="sub_district" id="sub_district" class="form-control input-sm sub_district filter_select_dropdown" style="width: 100%">
                                <option value="" title="Select">Select District</option>
                                <?php
                                if (count($distt_list)) {
                                    foreach ($distt_list as $dataRes) {
                                        if ($efiling_caveat_data[0]['lower_court_district'] == (string) $dataRes->DISTRICTCODE) {
                                            $sel = 'selected=selected';
                                        } else {
                                            $sel = "";
                                        }
                                        echo '<option ' . htmlentities($sel, ENT_QUOTES) . ' value="' . htmlentities(url_encryption(trim((string) $dataRes->DISTRICTCODE . '#$' . (string) $dataRes->DISTRICTNAME . '$$district'), ENT_QUOTES)) . '">' . htmlentities((string) $dataRes->DISTRICTNAME, ENT_QUOTES) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Subordinate Court Name <span style="color: red">*</span> :</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <select name="subordinate_court" id="subordinate_court"  class="form-control input-sm subordinate_court filter_select_dropdown" style="width: 100%">
                                <option value="" title="Select">Select</option>

                                <?php
                                if (count($subordinate_court)) {
                                    foreach ($subordinate_court as $dataRes) {
                                        if ($efiling_caveat_data[0]['lower_court_code'] == (string) $dataRes->COURTCODE) {
                                            $sel = 'selected=selected';
                                        } else {
                                            $sel = "";
                                        }
                                        echo '<option  ' . $sel . ' value="' . htmlentities(url_encryption(trim((string) $dataRes->COURTCODE . '#$' . (string) $dataRes->COURTNAME . '$$subCourtList'), ENT_QUOTES)) . '">' . htmlentities((string) $dataRes->COURTNAME, ENT_QUOTES) . '</option>';
                                    }
                                }
                                ?>

                            </select>
                        </div>
                    </div>

                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">CNR Number <span style="color: red">*</span> :</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <div class="input-group">
                            <?php $lower_cino = ($efiling_caveat_data[0]['lower_cino']) ? cin_preview($efiling_caveat_data[0]['lower_cino']) : NULL; ?>
                            <input id="cnr_number" name="cnr_number" onkeyup="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')" style="text-transform:uppercase"  value="<?php echo htmlentities(trim($lower_cino), ENT_QUOTES); ?>"  placeholder="CNR Number" maxlength="18" class="form-control input-sm cnr_number"  type="text">
                            <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Case Number Record should be of 18 characters (eg.MHAU01-002516-2018).">
                                <i class="fa fa-question-circle-o"></i>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php
                        $lower_court_code = $efiling_caveat_data[0]['lower_court'];
                        $case_type_id = (int) substr($lower_court_code, 1, 3);
                        $case_nums = (int) substr($lower_court_code, 4, 7);
                        $case_year = (int) substr($lower_court_code, -4);
                        $case_nums = !empty($case_nums) ? $case_nums : NULL;
                        $case_year = !empty($case_year) ? $case_year : NULL;
                        ?>
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case Type <span style="color: red">*</span> :</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <select name="case_type" id="case_type" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                                <option value="">Select</option>
                                <?php
                              //  echo '<pre>'; print_r($case_type); exit;
                                if(isset($case_type) && !empty($case_type)) {
                                    foreach ($case_type as $case) {
                                        echo '<option  value="' . htmlentities(url_encryption(trim((string)$case->casecode . '#$' . (string)$case->casename . '$$caseType'), ENT_QUOTES)) . '">' . htmlentities((string)$case->casename, ENT_QUOTES) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> </label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <?php
                            if ($efiling_caveat_data[0]['filing_case'] == '1') {
                                $filechecked = 'checked="checked"';
                            } else {
                                $casechecked = 'checked="checked"';
                            }
                            ?>
                            <label class="radio-inline"><input type="radio" id="case_no" name="filing_type" value="2" onclick="showlabel(this.value);"  maxlength="1" <?php echo htmlentities($casechecked, ENT_QUOTES); ?>> Case No</label>
                            <label class="radio-inline"><input type="radio" id="filing_no" name="filing_type" value="1" onclick="showlabel(this.value);"  maxlength="1" <?php echo htmlentities($filechecked, ENT_QUOTES); ?>> Filing No</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-5 input-sm"> <div id="label_name"> Case No.<span style="color: red">*</span> :</div> </label>
                        <div class="col-sm-3 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <input id="case_number" name="case_number" onkeyup="return isNumber(event)" value="<?php echo htmlentities($case_nums, ENT_QUOTES); ?>"  placeholder="Case No" maxlength="5"  class="form-control input-sm" type="text">
                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Case No. should be in digits only.">
                                    <i class="fa fa-question-circle-o" ></i>
                                </span>
                            </div>
                        </div>
                        <label class="control-label col-sm-2 input-sm"> <div id="label_name_year"> Case Year <span style="color: red">*</span> :</div></label>
                        <div class="col-sm-2 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <input id="case_year" name="case_year" onkeyup="return isNumber(event)" value="<?php echo htmlentities($case_year, ENT_QUOTES); ?>" placeholder="Case Year" maxlength="4" class="form-control input-sm" type="text">
                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Year should be valid and in digits only.">
                                    <i class="fa fa-question-circle-o" ></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Judge Name :</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <input id="judge_name" name="judge_name" onkeyup="return isLetter(event)"  value="<?php echo htmlentities($efiling_caveat_data[0]['lower_judge_name'], ENT_QUOTES); ?>" placeholder="Judge Name" maxlength="30" class="form-control input-sm" type="text">
                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Judge Name should be in characters.">
                                    <i class="fa fa-question-circle-o" ></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Date of Decision :</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <input class="form-control has-feedback-left" name="decision_date" value="<?php echo htmlentities($efiling_caveat_data[0]['lower_court_dec_dt'], ENT_QUOTES) ? date('d/m/Y', strtotime($efiling_caveat_data[0]['lower_court_dec_dt'])) : ''; ?>" readonly=""  id="decision_date" placeholder="DD/MM/YYYY"  type="text">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Valid Date of Decision.">
                                    <i class="fa fa-question-circle-o" ></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">CC Applied Date  :</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <input class="form-control has-feedback-left" name="cc_applied_date" value="<?php echo htmlentities($efiling_caveat_data[0]['lcc_applied_date'], ENT_QUOTES) ? date('d/m/Y', strtotime($efiling_caveat_data[0]['lcc_applied_date'])) : ''; ?>" readonly=""  id="cc_applied_date" placeholder="DD/MM/YYYY"  type="text">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Valid Certified Copy Applied Date.">
                                    <i class="fa fa-question-circle-o" ></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> CC Ready Date :</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <input class="form-control has-feedback-left" name="cc_ready_date" value="<?php echo htmlentities($efiling_caveat_data[0]['lcc_received_date'], ENT_QUOTES) ? date('d/m/Y', strtotime($efiling_caveat_data[0]['lcc_received_date'])) : ''; ?>" readonly="" id="cc_ready_date" placeholder="DD/MM/YYYY"  type="text">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Valid Certified Copy Ready Date.">
                                    <i class="fa fa-question-circle-o"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                    <?php
                    $prev_redirect_url = 'caveat/extra_party';
                    $next_redirect_url = 'uploadDocuments';
                    ?>
                    <a href="<?= base_url($prev_redirect_url) ?>" class="btn btn-primary" type="button">Previous</a>
                    <?php if (empty($efiling_caveat_data[0]) || $efiling_caveat_data[0] == '') { ?>
                        <input type="hidden" name="add_subordinate_crt" value="<?php echo_data(url_encryption('add')); ?>">
                        <input type="submit" class="btn btn-success" id="lower_court_save" value="SAVE">
                    <?php } else { ?>
                        <input type="hidden" name="add_subordinate_crt" value="<?php echo_data(url_encryption('update$$' . $efiling_caveat_data[0]['id'])); ?>">
                        <input type="submit" class="btn btn-success" id="lower_court_save" value="UPDATE">
                    <?php } ?>
                    <a href="<?= base_url($next_redirect_url) ?>" class="btn btn-primary" type="button">Next</a>
                </div>
            </div>
            <?php echo form_close(); ?>  

        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {

            $('#subordinate_court_info').on('submit', function () {
                if ($('#subordinate_court_info').valid()) {
                    var form_data = $(this).serialize();
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('caveat/subordinate_court/add_subordinate_info'); ?>",
                        data: form_data,
                        beforeSend: function () {
                            $('#lower_court_save').val('Please wait...');
                            $('#lower_court_save').prop('disabled', true);
                        },
                        success: function (data) {
                            $('#lower_court_save').val('SAVE');
                            $('#lower_court_save').prop('disabled', false);
                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                $('#msg').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            } else if (resArr[0] == 2) {
                                $('#msg').show();
                                $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                setTimeout(function () {
                                    location.reload();
                                }, 1000);

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
                    return false;
                } else {
                    return false;
                }
            });
        });
    </script>
    <script type="text/javascript">
        function showlabel(val) {
            if (val == '1') {
                document.getElementById('label_name').innerHTML = 'Filing No. <span style="color: red">*</span>';
                document.getElementById('label_name_year').innerHTML = 'Filing Year <span style="color: red">*</span>';
                document.getElementsByName('case_number')[0].placeholder = 'Filing no';
                document.getElementsByName('case_year')[0].placeholder = 'year';
            } else {
                document.getElementById('label_name').innerHTML = 'Case No. <span style="color: red">*</span>';
                document.getElementById('label_name_year').innerHTML = 'Case Year <span style="color: red">*</span>';
                document.getElementsByName('case_number')[0].placeholder = 'Case no';
                document.getElementsByName('case_year')[0].placeholder = 'year';
            }
        }
        $(document).ready(function () {

            var val = '<?php echo $efiling_caveat_data[0]['filing_case']; ?>';
            if (val == '1') {
                document.getElementById('label_name').innerHTML = 'Filing No. <span style="color: red">*</span>';
                document.getElementById('label_name_year').innerHTML = 'Filing Year <span style="color: red">*</span>';
                document.getElementsByName('case_number')[0].placeholder = 'Filing no';
                document.getElementsByName('case_year')[0].placeholder = 'year';
            } else {
                document.getElementById('label_name').innerHTML = 'Case No. <span style="color: red">*</span>';
                document.getElementById('label_name_year').innerHTML = 'Case Year <span style="color: red">*</span>';
                document.getElementsByName('case_number')[0].placeholder = 'Case no';
                document.getElementsByName('case_year')[0].placeholder = 'year';
            }

        });

        $('#sub_state_id').change(function () {

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#sub_district').val('');
            var get_state_id = $(this).val();
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
                url: "<?php echo base_url('Webservices/get_distric'); ?>t",
                success: function (data)
                {
                    $('.sub_district').html(data);
                    $.getJSON("<?php echo base_url('csrf_token'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function () {
                    $.getJSON("<?php echo base_url('csrf_token'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });

        });

        $('#sub_district').change(function () {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#subordinate_court').val('');
            var get_distt_id = $(this).val();
            var get_state_id = $('#sub_state_id').val();

            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, get_state_id: get_state_id, get_distt_id: get_distt_id},
                url: "<?php echo base_url('Webservices/get_lower_court'); ?>",
                success: function (data)
                {
                    $('.subordinate_court').html(data);
                    $.getJSON("<?php echo base_url('csrf_token'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function () {
                    $.getJSON("<?php echo base_url('csrf_token'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });

        });
    </script>

<?php } ?>


