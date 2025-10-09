<style>
    .input-group-addon {
        padding: 0px !important;
    }

    .select2-container {
        width: 100% !important;
    }

    fieldset {
        min-width: 0;
        padding: revert;
        margin: 0;
        border: 1px solid #a39d9d96;
        background-color: #fbebeb;
    }

    legend {
        background-color: gray;
        color: white;
        padding: 5px 10px;
        width: auto !important;
        font-size: 15px !important;
    }
</style>
<div class="panel panel-default">
    <!-- <h4 style="text-align: center;color: #31B0D5"> Case Details </h4>-->
    <div class="panel-body">

        <?php
        $attribute = array('class' => 'form-horizontal', 'name' => 'add_case_details', 'id' => 'add_case_details', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
        echo form_open('#', $attribute);
        $cause_title = explode(' Vs. ', $new_case_details[0]['cause_title']);
        ?>
        <!--start Upload Duc required="required" (Browse PDF)-->
        <?php
        // echo '<pre>'; print_r($_SESSION);exit(0);
        // echo '<pre>'; print_r( $_SESSION['efiling_details']);
        ?>



        <!--start New Case Details-->
        <div id="newCaseSaveFormStop"></div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <fieldset>
                <legend> Case Details Information:</legend>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-md-8 col-sm-12 col-xs-12 input-sm">Earlier Courts Details(<span style="color: red">Order Challenged</span>)
                                        &nbsp;&nbsp;&nbsp;<input tabindex="8" type="checkbox" name="Earlier_not_court_type" id="Earlier_not_court_type" value="4" <?php echo (!empty($new_case_details[0]['court_type']) && $new_case_details[0]['court_type'] == 4) ? 'checked' : ''  ?>>No Earlier Court</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Court Type<span style="color: red">*</span></label>
                                    <div class="col-md-7 col-sm-12 col-xs-12">
                                        <select id="court_name" name="court_name" class="form-control input-sm filter_select_dropdown" required>
                                            <option value="">Select Court Name</option>
                                            <?php
                                            if (isset($new_case_details[0]['court_type']) && !empty($new_case_details[0]['court_type'])) {
                                            ?>
                                                <option value="4" <?php echo (!empty($new_case_details[0]['court_type']) && $new_case_details[0]['court_type'] == 4) ? 'selected="selected"' : ''  ?>>Supreme Court</option>
                                                <option value="1" <?php echo (!empty($new_case_details[0]['court_type']) && $new_case_details[0]['court_type'] == 1) ? 'selected="selected"' : ''  ?>>High Court</option>
                                                <option value="3" <?php echo (!empty($new_case_details[0]['court_type']) && $new_case_details[0]['court_type'] == 3) ? 'selected="selected"' : ''  ?>>District Court</option>
                                                <option value="5" <?php echo (!empty($new_case_details[0]['court_type']) && $new_case_details[0]['court_type'] == 5) ? 'selected="selected"' : ''  ?>>State Agency</option>
                                            <?php
                                            } else {
                                            ?>
                                                <option value="4">Supreme Court</option>
                                                <option value="1" selected="selected">High Court</option>
                                                <option value="3">District Court</option>
                                                <option value="5">State Agency</option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- supreme_court start-->
                            <div id="supreme_court1" <?php echo (!empty($supreme_court_bench) && isset($supreme_court_bench)) ? 'style="display:block"' : 'style="display:none"'; ?>>
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">State Name <span style="color: red">*</span></label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <select id="supreme_state_name" name="supreme_state_name" class="form-control input-sm filter_select_dropdown" readonly="readonly">
                                                <option value="">Select State Name</option>
                                                <?php
                                                if (isset($supreme_court_state) && !empty($supreme_court_state)) {
                                                    foreach ($supreme_court_state as $k => $v) {
                                                        if (isset($new_case_details[0]['supreme_court_state']) && !empty($new_case_details[0]['supreme_court_state']) && $new_case_details[0]['supreme_court_state'] == $v['id']) {
                                                            echo '<option value="' . $v['id'] . '" selected="selected">' . $v['name'] . '</option>';
                                                        } else {
                                                            echo '<option value="' . $v['id'] . '">' . $v['name'] . '</option>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="supreme_court2" class="row" <?php echo (!empty($supreme_court_bench) && isset($supreme_court_bench)) ? 'style="display:block"' : 'style="display:none"'; ?>>
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Bench Name<span style="color: red">*</span></label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <select id="supreme_bench_name" name="supreme_bench_name" class="form-control input-sm filter_select_dropdown" readonly="readonly">
                                                <option value="">Select Bench Name</option>
                                                <?php
                                                if (isset($supreme_court_bench) && !empty($supreme_court_bench)) {
                                                    foreach ($supreme_court_bench as $k => $v) {
                                                        if (isset($new_case_details[0]['supreme_court_bench']) && !empty($new_case_details[0]['supreme_court_bench']) && $new_case_details[0]['supreme_court_bench'] == $v['id']) {
                                                            echo '<option value="' . $v['id'] . '" selected="selected">' . $v['name'] . '</option>';
                                                        } else {
                                                            echo '<option value="' . $v['id'] . '">' . $v['name'] . '</option>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- supreme_court end-->
                            <!-- high_court start-->
                            <?php
                            //  echo '<pre>'; print_r($high_court_bench); exit;
                            ?>
                            <div id="high_court_div1" <?php echo ((!empty($high_court_bench) && isset($high_court_bench)) || $high_court_drop_down) ? 'style="display:block"' : 'style="display:none"'; ?>>
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">High Court Name
                                            <span style="color: red">*</span></label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <select id="high_courtname" name="high_courtname" class="form-control input-sm filter_select_dropdown">
                                                <option value="">Select High Court</option>
                                                <?php
                                                if (isset($high_court_drop_down) && !empty($high_court_drop_down)) {
                                                    foreach ($high_court_drop_down as $k => $v) {
                                                        if ($v['hc_id'] ==  $new_case_details[0]['estab_id'])
                                                            echo '<option selected="selected" value="' . escape_data(url_encryption($v['hc_id'] . "##" . $v['name'])) . '">' . escape_data(strtoupper($v['name'])) . '</option>';
                                                        else
                                                            echo '<option value="' . escape_data(url_encryption($v['hc_id'] . "##" . $v['name'])) . '">' . escape_data(strtoupper($v['name'])) . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="high_court_div2" <?php echo ((!empty($high_court_bench) && isset($high_court_bench)) || $high_court_drop_down) ? 'style="display:block"' : 'style="display:none"'; ?>>
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Bench Name
                                            <span style="color: red">*</span></label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <select id="high_court_bench_name" name="high_court_bench_name" class="form-control input-sm filter_select_dropdown">
                                                <option value="">Select Bench Name</option>
                                                <?php
                                                if (isset($high_court_bench) && !empty($high_court_bench)) {
                                                    foreach ($high_court_bench as $k => $bench) {
                                                        if ($bench['est_code'] == $new_case_details[0]['estab_code'])
                                                            echo  '<option selected="selected" value="' . escape_data(url_encryption($bench['bench_id'] . "##" . $bench['name'] . "##" . $bench['est_code'])) . '">' . escape_data(strtoupper($bench['name'])) . '</option>';
                                                        else
                                                            echo '<option value="' . escape_data(url_encryption($bench['bench_id'] . "##" . $bench['name'] . "##" . $bench['est_code'])) . '">' . escape_data(strtoupper($bench['name'])) . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- high_court end-->
                            <!-- district_court start-->
                            <div id="district_court1" <?php echo (!empty($state_list) && isset($state_list)) ? 'style="display:block"' : 'style="display:none"'; ?>>
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">State Name
                                            <span style="color: red">*</span></label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <select id="district_court_state_name" name="district_court_state_name" class="form-control input-sm filter_select_dropdown">
                                                <option value="">Select State</option>
                                                <?php
                                                if (isset($state_list) && !empty($state_list)) {
                                                    foreach ($state_list as $k => $state) {
                                                        if (isset($new_case_details[0]['state_id']) && !empty($new_case_details[0]['state_id']) && $new_case_details[0]['state_id'] == $state['state_code']) {
                                                            echo '<option selected="selected" value="' . escape_data(url_encryption($state['state_code'] . '#$' . $state['state_name'] . '#$' . $state['state_name'])) . '">' . escape_data(strtoupper($state['state_name'])) . '</option>';
                                                        } else {
                                                            echo '<option  value="' . escape_data(url_encryption($state['state_code'] . '#$' . $state['state_name'] . '#$' . $state['state_name'])) . '">' . escape_data(strtoupper($state['state_name'])) . '</option>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="district_court2" <?php echo (!empty($state_list) && isset($state_list)) ? 'style="display:block"' : 'style="display:none"'; ?>>
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">District
                                            <span style="color: red">*</span></label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <select id="district_court_district_name" name="district_court_district_name" class="form-control input-sm filter_select_dropdown">
                                                <option value="">Select District</option>
                                                <?php
                                                if (isset($district_list) && !empty($district_list)) {
                                                    foreach ($district_list as $k => $district) {
                                                        if (isset($new_case_details[0]['district_id']) && !empty($new_case_details[0]['district_id']) && $new_case_details[0]['district_id'] == $district['district_code']) {
                                                            echo '<option selected="selected" value="' . htmlentities(url_encryption($district['district_code'] . '#$' . $district['district_name']), ENT_QUOTES) . '">' . htmlentities(strtoupper($district['district_name']), ENT_QUOTES) . '</option>';
                                                        } else {
                                                            echo '<option  value="' . htmlentities(url_encryption($district['district_code'] . '#$' . $district['district_name']), ENT_QUOTES) . '">' . htmlentities(strtoupper($district['district_name']), ENT_QUOTES) . '</option>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- district_court end-->
                            <!-- state_agency start-->
                            <div id="state_agency_div1" <?php echo (!empty($state_agency_list) && isset($state_agency_list)) ? 'style="display:block"' : 'style="display:none"'; ?>>
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">State Name
                                            <span style="color: red">*</span></label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <select id="state_agency" name="state_agency" class="form-control input-sm filter_select_dropdown">
                                                <option value="">Select State</option>
                                                <?php
                                                if (isset($state_agency_list) && !empty($state_agency_list)) {
                                                    foreach ($state_agency_list as $dataRes) {
                                                        if (isset($new_case_details[0]['state_id']) && !empty($new_case_details[0]['state_id']) && $new_case_details[0]['state_id'] == $dataRes->cmis_state_id) {
                                                            echo '<option selected="selected" value="' . escape_data(url_encryption($dataRes->cmis_state_id . '#$' . $dataRes->agency_state)) . '">' . escape_data(strtoupper($dataRes->agency_state)) . '</option>';
                                                        } else {
                                                            echo '<option value="' . escape_data(url_encryption($dataRes->cmis_state_id . '#$' . $dataRes->agency_state)) . '">' . escape_data(strtoupper($dataRes->agency_state)) . '</option>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="state_agency_div2" <?php echo (!empty($state_agency_list) && isset($state_agency_list)) ? 'style="display:block"' : 'style="display:none"'; ?>>
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Agency Name
                                            <span style="color: red">*</span></label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <select id="state_agency_name" name="state_agency_name" class="form-control input-sm filter_select_dropdown">
                                                <option value="">Select Agency</option>
                                                <?php
                                                if (isset($agencies) && !empty($agencies)) {
                                                    foreach ($agencies as $agency) {
                                                        if (isset($new_case_details[0]['estab_id']) && !empty($new_case_details[0]['estab_id']) && $new_case_details[0]['estab_id'] == $agency['id']) {
                                                            echo '<option selected="selected" value="' . escape_data(url_encryption($agency['id'] . "##" . $agency['agency_name'] . "##" . $agency['short_agency_name'])) . '">' . escape_data(strtoupper($agency['short_agency_name'])) . ' - ' . escape_data(strtoupper($agency['agency_name'])) . '</option>';
                                                        } else {
                                                            echo '<option value="' . escape_data(url_encryption($agency['id'] . "##" . $agency['agency_name'] . "##" . $agency['short_agency_name'])) . '">' . escape_data(strtoupper($agency['short_agency_name'])) . ' - ' . escape_data(strtoupper($agency['agency_name'])) . '</option>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!--/main row-->
                        <!-- state_agency end-->

                    </div>
                    <div class="row">

                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">No of cases Challenged
                                    <span style="color: red">*</span></label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <select id="no_of_cases_challenged" name="no_of_cases_challenged" class="form-control input-sm filter_select_dropdown">
                                        <option value="">--Select--</option>
                                        <?php
                                        $end = 150;
                                        for ($i = 0; $i <= $end; $i++) {
                                            $term = $i;
                                            $sel = ($term == ((int) $i)) ? 'selected=selected' : '';
                                            echo '<option ' . $sel . ' value=' . $term . '>' . $term . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">No of Petitioner (S)
                                    <span style="color: red">*</span></label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <input id="no_of_petitioners" name="no_of_petitioners" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" minlength="1" class="form-control input-sm" placeholder="No of Petitioner" type="number">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">No of Respondent (S)
                                    <span style="color: red">*</span></label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <input id="no_of_respondents" name="no_of_respondents" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" minlength="1" class="form-control input-sm" placeholder="No of Respondent" type="number" required>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>

















                <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Cause Title Petitioner
                                <span style="color: red">*</span></label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <textarea tabindex='1' id="cause_pet" name="cause_pet" minlength="3" maxlength="99" class="form-control input-sm" placeholder="Cause Title Petitioner" type="text" required><?php echo_data($cause_title[0]); ?></textarea>
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Petitioner name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Cause Title Respondent
                                <span style="color: red">*</span></label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <textarea id="cause_res" name="cause_res" minlength="3" maxlength="99" class="form-control input-sm" placeholder="Cause Title Respondent" type="text" required><?php echo_data($cause_title[1]); ?></textarea>
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Respondent name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case Type <span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <select name="sc_case_type" id="sc_case_type" class="form-control input-sm filter_select_dropdown sc_case_type" required>
                                    <option value="" title="Select">Select Case Type</option>
                                    <?php
                                    if (count($sc_case_type)) {
                                        foreach ($sc_case_type as $dataRes) {
                                            $sel = ($new_case_details[0]['sc_case_type_id'] == (string) $dataRes->casecode) ? "selected=selected" : '';
                                    ?>
                                            <option <?php echo $sel; ?> value="<?php echo_data(url_encryption(trim($dataRes->casecode))); ?>"><?php echo_data($dataRes->casename); ?> </option>;
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Special Case Type <span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <?php
                                $selectNone = $new_case_details[0]['sc_sp_case_type_id'] == '1' ? 'selected=selected' : '';
                                $selectJP = $new_case_details[0]['sc_sp_case_type_id'] == '6' ? 'selected=selected' : '';
                                $selectPUD = $new_case_details[0]['sc_sp_case_type_id'] == '7' ? 'selected=selected' : '';
                                ?>
                                <select tabindex='4' name="sc_sp_case_type_id" id="sc_sp_case_type_id" class="form-control input-sm filter_select_dropdown" style="width: 100%" required onchange="getdtmodel()">
                                    <option value="" title="Select">Select Special Case Type</option>
                                    <option <?php echo $selectNone; ?> value="1" selected>NONE</option>
                                    <option <?php echo $selectJP; ?> value="6">JAIL PETITION</option>
                                    <option <?php echo $selectPUD; ?> value="7">PUD</option>
                                </select>
                            </div>
                        </div>
                    </div>





                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Main Category <span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <select tabindex='5' name="subj_cat_main" id="subj_cat_main" class="form-control input-sm filter_select_dropdown">
                                    <option value="" title="Select">Select Main Category </option>
                                    <?php
                                    if (count($main_subject_cat)) {
                                        foreach ($main_subject_cat as $dataRes) {
                                            $sel = ($new_case_details[0]['subj_main_cat'] == $dataRes->id) ? "selected=selected" : '';
                                    ?>
                                            <option <?php echo $sel; ?> value="<?php echo_data(url_encryption(trim($dataRes->id . '##' . $dataRes->subcode1))); ?>"><?php echo_data(strtoupper($dataRes->sub_name1));
                                                                                                                                                                    if (!empty($dataRes->category_sc_old)) {
                                                                                                                                                                        echo ' (' . $dataRes->category_sc_old . ')';
                                                                                                                                                                    } ?> </option>;
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Sub Category 1 :</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <select tabindex='6' name="subj_sub_cat_1" id="subj_sub_cat_1" class="form-control input-sm filter_select_dropdown subj_sub_cat_1">
                                    <option value="" title="Select">Select Sub Category 1 </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-sm-4 col-xs-12" id="dtsign">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Date of signature of jail incharge :</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input tabindex='5' class="form-control has-feedback-left" id="datesignjail" name="datesignjail" value="<?php echo $new_case_details[0]['jail_signature_date']; ?>" maxlength="10" readonly="" placeholder="DD/MM/YYYY" type="text">
                                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Date of Birth.">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <span id="subcatLoadData"></span>

                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12" id="divResult11">

                    </div>
                    <!--<div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Sub Category 2 :</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <select tabindex = '7' name="subj_sub_cat_2" id="subj_sub_cat_2" class="form-control input-sm filter_select_dropdown subj_sub_cat_2">
                                    <option value="" title="Select">Select Sub Category 2</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Sub Category 3 :</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <select tabindex = '8' name="subj_sub_cat_3" id="subj_sub_cat_3" class="form-control input-sm filter_select_dropdown subj_sub_cat_3">
                                    <option value="" title="Select">Select Sub Category 3</option>

                                </select>
                            </div>
                        </div>
                    </div>-->

                </div>
            </fieldset><br />
        </div>

        <!--end Case Details-->

        <!--start Petitioner-->
        <div class="col-md-12 col-sm-12 col-xs-12">
            <fieldset>
                <legend data-toggle="collapse" data-target="#demop" role="button">Petitioner Information:</legend>

                <div class="collapse" id="demop">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Petitioner Is <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <?php
                                    $selectIndividual = $party_details[0]['party_type'] == 'I' ? 'selected=selected' : '';
                                    $selectStateDept = $party_details[0]['party_type'] == 'D1' ? 'selected=selected' : '';
                                    $selectCentralDept = $party_details[0]['party_type'] == 'D2' ? 'selected=selected' : '';
                                    $selectOtherDept = $party_details[0]['party_type'] == 'D3' ? 'selected=selected' : '';
                                    ?>
                                    <select tabindex='1' name="party_as" id="party_as" onchange="get_party_as(this.value)" class="form-control input-sm filter_select_dropdown">
                                        <option value="I" <?php echo $selectIndividual; ?>>Individual</option>
                                        <option value="D1" <?php echo $selectStateDept; ?>>State Department</option>
                                        <option value="D2" <?php echo $selectCentralDept; ?>>Central Department</option>
                                        <option value="D3" <?php echo $selectOtherDept; ?>>Other Organisation</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="indvidual_form">
                            <div class="col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Petitioner Name
                                        <span style="color: red">*</span></label>
                                    <div class="col-md-7 col-sm-12 col-xs-12">
                                        <div class="input-group">
                                            <textarea tabindex='2' id="party_name" name="party_name" minlength="3" maxlength="99" class="form-control input-sm sci_validation" placeholder="First Name Middle Name Last Name" type="text"><?php echo_data($party_details[0]['party_name']); ?></textarea>
                                            <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Petitioner name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                                <i class="fa fa-question-circle-o"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="show_hide_base_on_org">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Relation <span style="color: red">*</span>:</label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <?php
                                            $selectSon = $party_details[0]['relation'] == 'S' ? 'selected=selected' : '';
                                            $selectDaughter = $party_details[0]['relation'] == 'D' ? 'selected=selected' : '';
                                            $selectWife = $party_details[0]['relation'] == 'W' ? 'selected=selected' : '';
                                            $selectNotAvailable = $party_details[0]['relation'] == 'N' ? 'selected=selected' : '';
                                            ?>
                                            <select tabindex='3' name="relation" id="relation" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                                                <option value="" title="Select">Select Relation</option>
                                                <option <?php echo $selectSon; ?> value="S">Son Of</option>
                                                <option <?php echo $selectDaughter; ?> value="D">Daughter Of</option>
                                                <option <?php echo $selectWife; ?> value="W">Spouse Of</option>
                                                <option <?php echo $selectNotAvailable; ?> value="N">Not Available</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="show_hide_base_on_org">
                                <div class="col-sm-4 col-xs-12" id="rel_name">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">
                                            Parent/Spouse Name <span style="color: red">*</span>:</label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <input tabindex='4' id="relative_name" name="relative_name" minlength="3" maxlength="99" placeholder="Name of Parent or Husband" value="<?php echo_data($party_details[0]['relative_name']); ?>" class="form-control input-sm sci_validation" type="text">
                                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please write name of father or mother or husband or other relative. Relative Name should be in characters ( only dot[.] and space are allowed ).">
                                                    <i class="fa fa-question-circle-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="show_hide_base_on_org">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Is Dead/Minor ? :</label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <?php
                                                if (isset($party_details[0]['is_dead_minor']) && !empty($party_details[0]['is_dead_minor']) && ($party_details[0]['is_dead_minor'] == 't' || $party_details[0]['is_dead_minor'] == 'f')) {
                                                ?>
                                                    <label class="radio-inline is_dead_class"><input <?php echo ($party_details[0]['is_dead_minor'] && $party_details[0]['is_dead_minor'] == 't') ? 'checked="checked" ' : '' ?> type="radio" id="is_dead_minor_1" value="1" name="is_dead_minor">Yes</label>
                                                    <label class="radio-inline is_dead_class"><input type="radio" id="is_dead_minor_2" value="0" name="is_dead_minor" <?php echo ($party_details[0]['is_dead_minor'] && $party_details[0]['is_dead_minor'] == 'f') ? 'checked="checked" ' : '' ?>>No</label>
                                                <?php
                                                } else {
                                                ?>
                                                    <label class="radio-inline is_dead_class"><input type="radio" id="is_dead_minor_1" value="1" name="is_dead_minor">Yes</label>
                                                    <label class="radio-inline is_dead_class"><input type="radio" id="is_dead_minor_2" value="0" name="is_dead_minor" checked>No</label>
                                                <?php
                                                }
                                                ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Date of Birth :</label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <input tabindex='5' class="form-control has-feedback-left" id="party_dob" name="party_dob" value="<?php echo (!empty($party_details[0]['party_dob'])) ? date('d/m/Y', strtotime($party_details[0]['party_dob'])) : ''; ?>" maxlength="10" readonly="" placeholder="DD/MM/YYYY" type="text">
                                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Date of Birth.">
                                                    <i class="fa fa-question-circle-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="show_hide_base_on_org">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <?php
                                        $style = '';
                                        $astrik = '';
                                        if (($party_details[0]['is_dead_minor'] == 'f') && !empty($party_details[0]['country_id']) && ($party_details[0]['country_id'] != 96)) {
                                            $style = '';
                                            $astrik = '';
                                        } else if (!empty($party_details[0]['is_dead_minor']) && $party_details[0]['is_dead_minor'] == 'f') {
                                            $style = 'style="color: red"';
                                            $astrik = '*';
                                        } else if (!empty($party_details[0]['is_dead_minor']) && ($party_details[0]['is_dead_minor'] == 't')) {
                                            $style = '';
                                            $astrik = '';
                                        } else {
                                            $style = 'style="color: red"';
                                            $astrik = '*';
                                        }
                                        ?>
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Approximate Age <span id="party_age_span" <?php echo $style; ?>><?php echo $astrik; ?></span>:</label>
                                        <div class="col-sm-2 col-md-4">
                                            <div class="input-group">
                                                <?php
                                                if ($party_details[0]['party_age'] == 0 || $party_details[0]['party_age'] == '' || $party_details[0]['party_age'] == NULL) {
                                                    $party_age = '';
                                                } else {
                                                    $party_age = $party_details[0]['party_age'];
                                                }
                                                ?>
                                                <input id="party_age" tabindex='6' name="party_age" maxlength="2" onkeyup="return isNumber(event)" placeholder="Age" value="<?php echo_data($party_age); ?>" class="form-control input-sm age_calculate" type="text">
                                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Approx. age in years only.">
                                                    <i class="fa fa-question-circle-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="show_hide_base_on_org">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-5 input-sm">Gender <span style="color: red">*</span>:</label>
                                        <div class="col-sm-7">
                                            <?php
                                            $gmchecked = $party_details[0]['gender'] == 'M' ? 'checked="checked"' : '';
                                            $gfchecked = $party_details[0]['gender'] == 'F' ? 'checked="checked"' : '';
                                            $gochecked = $party_details[0]['gender'] == 'O' ? 'checked="checked"' : '';
                                            ?>
                                            <label class="radio-inline"><input tabindex='7' type="radio" name="party_gender" id="party_gender1" value="M" <?php echo $gmchecked; ?>>Male</label>
                                            <label class="radio-inline"><input tabindex='8' type="radio" name="party_gender" id="party_gender2" value="F" <?php echo $gfchecked; ?>>Female</label>
                                            <label class="radio-inline"><input tabindex='9' type="radio" name="party_gender" id="party_gender3" value="O" <?php echo $gochecked; ?>>Other</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="org_form" style="display: none">
                            <div id="org_state_row">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">State Name <span style="color: red">*</span>:</label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <select tabindex='10' name="org_state" id="org_state" class="form-control input-sm filter_select_dropdown org_state">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="otherOrgState" style="display: none">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Other State Name<span style="color: red">*</span>:</label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <textarea tabindex='11' id="org_state_name" name="org_state_name" minlength="5" maxlength="99" class="form-control input-sm" placeholder="Other State Name" type="text"><?php echo_data($party_details[0]['org_state_name']); ?></textarea>
                                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Other State Name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                                    <i class="fa fa-question-circle-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Department Name <span style="color: red">*</span>:</label>
                                    <div class="col-md-7 col-sm-12 col-xs-12">
                                        <select name="org_dept" tabindex='12' id="org_dept" class="form-control input-sm filter_select_dropdown org_dept">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="otherOrgDept" style="display: none">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Other Department<span style="color: red">*</span>:</label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <textarea id="org_dept_name" tabindex='13' name="org_dept_name" minlength="5" maxlength="99" class="form-control input-sm" placeholder="Other State Name" type="text"><?php echo_data($party_details[0]['org_state_name']); ?></textarea>
                                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Other Department Name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                                    <i class="fa fa-question-circle-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Post Name <span style="color: red">*</span>:</label>
                                    <div class="col-md-7 col-sm-12 col-xs-12">
                                        <select name="org_post" id="org_post" tabindex='14' class="form-control input-sm filter_select_dropdown org_post">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="otherOrgPost" style="display: none">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Other Post<span style="color: red">*</span>:</label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <textarea id="org_post_name" name="org_post_name" tabindex='15' minlength="5" maxlength="99" class="form-control input-sm" placeholder="Other Post Name" type="text"><?php echo_data($party_details[0]['org_state_name']); ?></textarea>
                                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Other Post Name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                                    <i class="fa fa-question-circle-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Email <?php
                                                                                                            // if ($_SESSION['estab_details']['is_pet_email_required'] == 't') {
                                                                                                            echo '<span id="party_email_req" value="1" style="color: red">*</span>';
                                                                                                            //  }
                                                                                                            ?>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input id="party_email" name="party_email" placeholder="Email" tabindex='16' value="<?php echo_data($party_details[0]['email_id']); ?>" class="form-control input-sm" type="email" minlength="6" maxlength="49">
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter Petitioner valid email id. (eg : abc@example.com)">
                                            <i class="fa fa-question-circle-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Mobile <?php
                                                                                                            //if ($_SESSION['estab_details']['is_pet_mobile_required'] == 't') {
                                                                                                            echo '<span id="pet_mobile_req" value="1" style="color: red">*</span>';
                                                                                                            //  }
                                                                                                            ?> :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input id="party_mobile" name="party_mobile" tabindex='17' onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Mobile" value="<?php echo_data($party_details[0]['mobile_num']); ?>" class="form-control input-sm" type="text" minlength="10" maxlength="10">
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Mobile No. should be of 10 digits only.">
                                            <i class="fa fa-question-circle-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">
                                    <div id="address_label_name"> Address <span id="address_span" <?php echo $style; ?>><?php echo $astrik; ?></span>:</div>
                                </label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <textarea tabindex='18' name="party_address" id="party_address" placeholder="H.No.,  Street no, Colony,  Land Mark" class="form-control input-sm sci_validation" minlength="3"><?php echo_data($party_details[0]['address']); ?></textarea>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter House No, Street No, Sector, Colony and Landmarks. Please Select District and Taluka from the below mentioned field. Do not repeat District and Taluka in Address fields and District and Taluka Fields. Address can be alphanumeric (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                            <i class="fa fa-question-circle-o"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Country <span id="country_span"></span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <select name="country_id" id="country_id" tabindex='19' class="form-control input-sm filter_select_dropdown">
                                        <option value="">Select Country</option>
                                        <?php
                                        if (isset($countryList) && !empty($countryList)) {
                                            foreach ($countryList as $val) {
                                                if (isset($party_details[0]['country_id']) && !empty($party_details[0]['country_id'])) {
                                                    $sel = (url_encryption($val->id) == url_encryption($party_details[0]['country_id'])) ? "selected=selected" : '';
                                                } else {
                                                    $sel = ($val->id == 96) ? "selected=selected" : '';
                                                }
                                                echo '<option ' . $sel . ' value="' . url_encryption(trim($val->id)) . '">' . strtoupper($val->country_name) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Pin Code <?php echo '<span id="pet_pincode_req" value="1" style="color: red">*</span>'; ?>:</label>
                                <div class="col-sm-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input id="party_pincode" name="party_pincode" tabindex='20' onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Pincode" value="<?php echo_data($party_details[0]['pincode']); ?>" class="form-control input-sm" type="text" minlength="6" maxlength="6">
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Pincode should be 6 digits only.">
                                            <i class="fa fa-question-circle-o"></i>
                                            <a href="https://www.indiapost.gov.in/vas/pages/findpincode.aspx" target="_blank">Pin Code Locator</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">
                                    <div id="address_label_name"> City <span id="city_span" <?php echo $style; ?>><?php echo $astrik; ?></span>:</div>
                                </label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input id="party_city" tabindex='21' name="party_city" placeholder="City" value="<?php echo_data($party_details[0]['city']); ?>" class="form-control input-sm sci_validation" type="text" minlength="3" maxlength="49">
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter City name.">
                                            <i class="fa fa-question-circle-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> State <span id="state_span" <?php echo $style; ?>><?php echo $astrik; ?></span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <select name="party_state" id="party_state" tabindex='22' class="form-control input-sm filter_select_dropdown">
                                        <option value="" title="Select">Select State</option>
                                        <?php
                                        $sel = '';
                                        $stateArr = array();
                                        if (count($state_list)) {
                                            foreach ($state_list as $dataRes) {
                                                if (isset($party_details[0]['state_id']) && !empty($party_details[0]['state_id'])) {
                                                    $sel = ($party_details[0]['state_id'] == $dataRes->cmis_state_id) ? "selected=selected" : '';
                                                }
                                        ?>
                                                <option <?php echo $sel; ?> value="<?php echo_data(url_encryption(trim($dataRes->cmis_state_id))); ?>"><?php echo_data(strtoupper($dataRes->agency_state)); ?> </option>
                                        <?php
                                                $tempArr = array();
                                                $tempArr['id'] = url_encryption(trim($dataRes->cmis_state_id));
                                                $tempArr['state_name'] = strtoupper(trim($dataRes->agency_state));
                                                $stateArr[] = (object)$tempArr;
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> District <span id="district_span" <?php echo $style; ?>><?php echo $astrik; ?></span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <select name="party_district" id="party_district" tabindex='23' class="form-control input-sm filter_select_dropdown party_district">
                                        <option value="" title="Select">Select District</option>
                                        <?php
                                        if (count($district_list)) {
                                            foreach ($district_list as $dataRes) {
                                                $sel = ($party_details[0]['district_id'] == $dataRes->id_no) ? 'selected=selected' : '';
                                        ?>
                                                <option <?php echo $sel; ?> value="<?php echo_data(url_encryption(trim($dataRes->id_no))); ?>"><?php echo_data(strtoupper($dataRes->name)); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <br />
        </div>

        <!--end Petitioner-->

        <!--start Respondent-->
        <div class="col-md-12 col-sm-12 col-xs-12">
            <fieldset>
                <legend data-toggle="collapse" data-target="#demor" role="button">Respondent Information:</legend>
                <div class="collapse" id="demor">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Respondent Is <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <?php
                                    $selectIndividual = $party_details[0]['party_type'] == 'I' ? 'selected=selected' : '';
                                    $selectStateDept = $party_details[0]['party_type'] == 'D1' ? 'selected=selected' : '';
                                    $selectCentralDept = $party_details[0]['party_type'] == 'D2' ? 'selected=selected' : '';
                                    $selectOtherDept = $party_details[0]['party_type'] == 'D3' ? 'selected=selected' : '';
                                    ?>
                                    <select tabindex='1' name="party_as" id="party_as" onchange="get_party_as(this.value)" class="form-control filter_select_dropdown input-sm">
                                        <option value="I" <?php echo $selectIndividual; ?>>Individual</option>
                                        <option value="D1" <?php echo $selectStateDept; ?>>State Department</option>
                                        <option value="D2" <?php echo $selectCentralDept; ?>>Central Department</option>
                                        <option value="D3" <?php echo $selectOtherDept; ?>>Other Organisation</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="indvidual_form">
                            <div class="col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Respondent Name
                                        <span style="color: red">*</span></label>
                                    <div class="col-md-7 col-sm-12 col-xs-12">
                                        <div class="input-group">
                                            <textarea tabindex='2' id="party_name" name="party_name" minlength="3" maxlength="99" class="form-control input-sm sci_validation" placeholder="First Name Middle Name Last Name" type="text"><?php echo_data($party_details[0]['party_name']); ?></textarea>
                                            <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Respondent name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                                <i class="fa fa-question-circle-o"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="show_hide_base_on_org">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Relation <span style="color: red">*</span>:</label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <?php
                                            $selectSon = $party_details[0]['relation'] == 'S' ? 'selected=selected' : '';
                                            $selectDaughter = $party_details[0]['relation'] == 'D' ? 'selected=selected' : '';
                                            $selectWife = $party_details[0]['relation'] == 'W' ? 'selected=selected' : '';
                                            $selectNotAvailable = $party_details[0]['relation'] == 'N' ? 'selected=selected' : '';
                                            ?>
                                            <select tabindex='3' name="relation" id="relation" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                                                <option value="" title="Select">Select Relation</option>
                                                <option <?php echo $selectSon; ?> value="S">Son Of</option>
                                                <option <?php echo $selectDaughter; ?> value="D">Daughter Of</option>
                                                <option <?php echo $selectWife; ?> value="W">Spouse Of</option>
                                                <option <?php echo $selectNotAvailable; ?> value="N">Not Available</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="show_hide_base_on_org">
                                <div class="col-sm-4 col-xs-12" id="rel_name">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">
                                            Parent/Spouse Name <span style="color: red">*</span>:</label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <input tabindex='4' id="relative_name" name="relative_name" minlength="3" maxlength="99" placeholder="Name of Parent or Husband" value="<?php echo_data($party_details[0]['relative_name']); ?>" class="form-control input-sm sci_validation" type="text">
                                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please write name of father or mother or husband or other relative. Relative Name should be in characters ( only dot[.] and space are allowed ).">
                                                    <i class="fa fa-question-circle-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="show_hide_base_on_org">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Is Dead/Minor ? :</label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <?php
                                                if (isset($party_details[0]['is_dead_minor']) && !empty($party_details[0]['is_dead_minor']) && ($party_details[0]['is_dead_minor'] == 't' || $party_details[0]['is_dead_minor'] == 'f')) {
                                                ?>
                                                    <label class="radio-inline is_dead_class"><input <?php echo ($party_details[0]['is_dead_minor'] && $party_details[0]['is_dead_minor'] == 't') ? 'checked="checked" ' : '' ?> type="radio" id="is_dead_minor_1" value="1" name="is_dead_minor">Yes</label>
                                                    <label class="radio-inline is_dead_class"><input type="radio" id="is_dead_minor_2" value="0" name="is_dead_minor" <?php echo ($party_details[0]['is_dead_minor'] && $party_details[0]['is_dead_minor'] == 'f') ? 'checked="checked" ' : '' ?>>No</label>
                                                <?php
                                                } else {
                                                ?>
                                                    <label class="radio-inline is_dead_class"><input type="radio" id="is_dead_minor_1" value="1" name="is_dead_minor">Yes</label>
                                                    <label class="radio-inline is_dead_class"><input type="radio" id="is_dead_minor_2" value="0" name="is_dead_minor" checked>No</label>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Date of Birth :</label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <input tabindex='5' class="form-control has-feedback-left" id="party_dob" name="party_dob" value="<?php echo (!empty($party_details[0]['party_dob'])) ? date('d/m/Y', strtotime($party_details[0]['party_dob'])) : ''; ?>" maxlength="10" readonly="" placeholder="DD/MM/YYYY" type="text">
                                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Date of Birth.">
                                                    <i class="fa fa-question-circle-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="show_hide_base_on_org">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <?php
                                        $style = '';
                                        $astrik = '';
                                        if (($party_details[0]['is_dead_minor'] == 'f') && !empty($party_details[0]['country_id']) && ($party_details[0]['country_id'] != 96)) {
                                            $style = '';
                                            $astrik = '';
                                        } else if (!empty($party_details[0]['is_dead_minor']) && $party_details[0]['is_dead_minor'] == 'f') {
                                            $style = 'style="color: red"';
                                            $astrik = '*';
                                        } else if (!empty($party_details[0]['is_dead_minor']) && ($party_details[0]['is_dead_minor'] == 't')) {
                                            $style = '';
                                            $astrik = '';
                                        } else {
                                            $style = 'style="color: red"';
                                            $astrik = '*';
                                        }
                                        ?>
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Approximate Age <span id="party_age_span" <?php echo $style; ?>><?php echo $astrik; ?></span>:</label>
                                        <div class="col-sm-2 col-md-4">
                                            <div class="input-group">
                                                <?php
                                                if ($party_details[0]['party_age'] == 0 || $party_details[0]['party_age'] == '' || $party_details[0]['party_age'] == NULL) {
                                                    $party_age = '';
                                                } else {
                                                    $party_age = $party_details[0]['party_age'];
                                                }
                                                ?>
                                                <input tabindex='6' id="party_age" name="party_age" maxlength="2" onkeyup="return isNumber(event)" placeholder="Age" value="<?php echo_data($party_age); ?>" class="form-control input-sm age_calculate" type="text">
                                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Approx. age in years only.">
                                                    <i class="fa fa-question-circle-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="show_hide_base_on_org">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-5 input-sm">Gender <span style="color: red">*</span>:</label>
                                        <div class="col-sm-7">
                                            <?php
                                            $gmchecked = $party_details[0]['gender'] == 'M' ? 'checked="checked"' : '';
                                            $gfchecked = $party_details[0]['gender'] == 'F' ? 'checked="checked"' : '';
                                            $gochecked = $party_details[0]['gender'] == 'O' ? 'checked="checked"' : '';
                                            ?>
                                            <label class="radio-inline"><input type="radio" tabindex='7' name="party_gender" id="party_gender1" value="M" <?php echo $gmchecked; ?>>Male</label>
                                            <label class="radio-inline"><input type="radio" tabindex='8' name="party_gender" id="party_gender2" value="F" <?php echo $gfchecked; ?>>Female</label>
                                            <label class="radio-inline"><input type="radio" tabindex='9' name="party_gender" id="party_gender3" value="O" <?php echo $gochecked; ?>>Other</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="org_form" style="display: none">
                            <div id="org_state_row">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">State Name <span style="color: red">*</span>:</label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <select name="org_state" id="org_state" tabindex='10' class="form-control filter_select_dropdown input-sm org_state">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="otherOrgState" style="display: none">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Other State Name<span style="color: red">*</span>:</label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <textarea id="org_state_name" name="org_state_name" tabindex='11' minlength="5" maxlength="99" class="form-control input-sm" placeholder="Other State Name" type="text"><?php echo_data($party_details[0]['org_state_name']); ?></textarea>
                                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Other State Name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                                    <i class="fa fa-question-circle-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Department Name <span style="color: red">*</span>:</label>
                                    <div class="col-md-7 col-sm-12 col-xs-12">
                                        <select name="org_dept" id="org_dept" tabindex='12' class="form-control filter_select_dropdown input-sm org_dept">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="otherOrgDept" style="display: none">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Other Department<span style="color: red">*</span>:</label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <textarea id="org_dept_name" name="org_dept_name" tabindex='13' minlength="5" maxlength="99" class="form-control input-sm" placeholder="Other State Name" type="text"><?php echo_data($party_details[0]['org_state_name']); ?></textarea>
                                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Other Department Name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                                    <i class="fa fa-question-circle-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Post Name <span style="color: red">*</span>:</label>
                                    <div class="col-md-7 col-sm-12 col-xs-12">
                                        <select name="org_post" id="org_post" tabindex='14' class="form-control filter_select_dropdown input-sm org_post">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="otherOrgPost" style="display: none">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Other Post<span style="color: red">*</span>:</label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <textarea id="org_post_name" name="org_post_name" tabindex='15' minlength="5" maxlength="99" class="form-control input-sm" placeholder="Other Post Name" type="text"><?php echo_data($party_details[0]['org_state_name']); ?></textarea>
                                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Other Post Name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                                    <i class="fa fa-question-circle-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Email <?php
                                                                                                            if ($_SESSION['estab_details']['is_pet_email_required'] == 't') {
                                                                                                                echo '<span id="party_email_req" value="1" style="color: red">*</span>';
                                                                                                            }
                                                                                                            ?>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input id="party_email" name="party_email" placeholder="Email" tabindex='16' value="<?php echo_data($party_details[0]['email_id']); ?>" class="form-control input-sm" type="email" minlength="6" maxlength="49">
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter Respondent valid email id. (eg : abc@example.com)">
                                            <i class="fa fa-question-circle-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Mobile <?php
                                                                                                            if ($_SESSION['estab_details']['is_pet_mobile_required'] == 't') {
                                                                                                                echo '<span id="pet_mobile_req" value="1" style="color: red">*</span>';
                                                                                                            }
                                                                                                            ?> :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input id="party_mobile" name="party_mobile" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" tabindex='17' placeholder="Mobile" value="<?php echo_data($party_details[0]['mobile_num']); ?>" class="form-control input-sm" type="text" minlength="10" maxlength="10">
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Mobile No. should be of 10 digits only.">
                                            <i class="fa fa-question-circle-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">
                                    <div id="address_label_name"> Address <span id="address_span" <?php echo $style; ?>><?php echo $astrik; ?></span>:</div>
                                </label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <textarea name="party_address" tabindex='18' id="party_address" placeholder="H.No.,  Street no, Colony,  Land Mark" class="form-control input-sm sci_validation" minlength="3"><?php echo_data($party_details[0]['address']); ?></textarea>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter House No, Street No, Sector, Colony and Landmarks. Please Select District and Taluka from the below mentioned field. Do not repeat District and Taluka in Address fields and District and Taluka Fields. Address can be alphanumeric (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                            <i class="fa fa-question-circle-o"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Country <span id="country_span"></span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <select name="country_id" id="country_id" tabindex='19' class="form-control input-sm filter_select_dropdown">
                                        <option value="">Select Country</option>
                                        <?php
                                        if (isset($countryList) && !empty($countryList)) {
                                            foreach ($countryList as $val) {
                                                if (isset($party_details[0]['country_id']) && !empty($party_details[0]['country_id'])) {
                                                    $sel = (url_encryption($val->id) == url_encryption($party_details[0]['country_id'])) ? "selected=selected" : '';
                                                } else {
                                                    $sel = ($val->id == 96) ? "selected=selected" : '';
                                                }
                                                echo '<option ' . $sel . ' value="' . url_encryption(trim($val->id)) . '">' . strtoupper($val->country_name) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">PIN Code :</label>
                                <div class="col-sm-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input id="party_pincode" name="party_pincode" tabindex='20' onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" placeholder="Pincode" value="<?php echo_data($party_details[0]['pincode']); ?>" class="form-control input-sm" type="text" minlength="6" maxlength="6">
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Pincode should be 6 digits only.">
                                            <i class="fa fa-question-circle-o"></i>
                                            <a href="https://www.indiapost.gov.in/vas/pages/findpincode.aspx" target="_blank">Pin Code Locator</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">
                                    <div id="address_label_name"> City <span id="city_span" <?php echo $style; ?>><?php echo $astrik; ?></span>:</div>
                                </label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input id="party_city" name="party_city" tabindex='21' placeholder="City" value="<?php echo_data($party_details[0]['city']); ?>" class="form-control input-sm sci_validation" type="text" minlength="3" maxlength="49">
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter City name.">
                                            <i class="fa fa-question-circle-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> State <span id="state_span" <?php echo $style; ?>><?php echo $astrik; ?></span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <select name="party_state" id="party_state" tabindex='22' class="form-control filter_select_dropdown input-sm">
                                        <option value="" title="Select">Select State</option>
                                        <?php
                                        $stateArr = array();
                                        if (count($state_list)) {
                                            foreach ($state_list as $dataRes) {
                                                $sel = ($party_details[0]['state_id'] == $dataRes->cmis_state_id) ? "selected=selected" : '';
                                        ?>
                                                <option <?php echo $sel; ?> value="<?php echo_data(url_encryption(trim($dataRes->cmis_state_id))); ?>"><?php echo_data(strtoupper($dataRes->agency_state)); ?> </option>;
                                        <?php
                                                $tempArr = array();
                                                $tempArr['id'] = url_encryption(trim($dataRes->cmis_state_id));
                                                $tempArr['state_name'] = strtoupper(trim($dataRes->agency_state));
                                                $stateArr[] = (object)$tempArr;
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> District <span id="district_span" <?php echo $style; ?>><?php echo $astrik; ?></span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <select name="party_district" id="party_district" tabindex='23' class="form-control filter_select_dropdown input-sm party_district">
                                        <option value="" title="Select">Select District</option>
                                        <?php
                                        if (count($district_list)) {
                                            foreach ($district_list as $dataRes) {
                                                $sel = ($party_details[0]['district_id'] == $dataRes->id_no) ? 'selected=selected' : '';
                                        ?>
                                                <option <?php echo $sel; ?> value="<?php echo_data(url_encryption(trim($dataRes->id_no))); ?>"><?php echo_data(strtoupper($dataRes->name)); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </fieldset><br />
        </div>

        <!--end Respondent-->

        <!--start Action area-->
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">

                <?php if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) { ?>
                    <input type="submit" tabindex='15' class="btn btn-success" id="pet_saveStop" value="UPDATE">
                    <a href="<?= base_url('newcaseQF/uploadDocuments') ?>" class="btn btn-primary btnNext" type="button">Next</a>
                <?php } else { ?>
                    <input type="submit" class="btn btn-success" id="pet_saveStop" value="SAVE">
                    <a href="<?= base_url('newcaseQF/uploadDocuments') ?>" class="btn btn-primary btnNext" type="button">Next</a>
                <?php } ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="progress" style="display: none">
                <div class="progress-bar progress-bar-success myprogress" role="progressbar" value="0" max="100" style="width:0%">0%</div>
            </div>
        </div>
    </div>
    <!--end Action area-->

</div>
</div>
<?php
if (!empty($uploaded_docs)) {
    echo '<script>$("#nextButton").show();</script>';
    $this->load->view('uploadDocuments/uploaded_doc_list');
} else {
    echo '<script>$("#nextButton").hide();</script>';
}
?>

<script type="text/javascript">
    <?php if (isset($new_case_details[0]) && !empty($new_case_details[0]) && $new_case_details[0]['subj_sub_cat_1'] == 222) { ?>
        var selected_sub_cat = '<?php echo_data(url_encryption($new_case_details[0]['subj_sub_cat_1'] . '##' . $new_case_details[0]['court_fee_calculation_helper_flag'])); ?>';
        get_matrimonial(selected_sub_cat);


    <?php } ?>
    <?php if (isset($new_case_details[0]) && !empty($new_case_details[0]) && $new_case_details[0]['sc_case_type_id'] == 7) { ?>
        var selected_case_type = '<?php echo_data(url_encryption($new_case_details[0]['sc_case_type_id'] . '##' . $new_case_details[0]['court_fee_calculation_helper_flag'])); ?>';
        get_matrimonial_for_casetype(selected_case_type);
    <?php } ?>

    <?php if (isset($new_case_details[0]) && !empty($new_case_details[0])) { ?>
        var selected_sub_cat = '<?php echo_data(url_encryption($new_case_details[0]['subj_sub_cat_1'])); ?>';
        get_sub_category('subj_sub_cat_1', 'subj_cat_main', selected_sub_cat);

        /*   selected_sub_cat = '<//?php echo_data(url_encryption($new_case_details[0]['subj_sub_cat_2'])); ?>';
           get_sub_category('subj_sub_cat_2', 'subj_sub_cat_1', selected_sub_cat);

           selected_sub_cat = '<//?php echo_data(url_encryption($new_case_details[0]['subj_sub_cat_3'])); ?>';
           get_sub_category('subj_sub_cat_3', 'subj_sub_cat_2', selected_sub_cat);*/

    <?php } ?>

    //----------Get Sub Category List----------------------//
    $('#subj_cat_main').change(function() {
        // alert(this.value);
        var selected_sub_cat = '<?php echo_data(url_encryption($new_case_details[0]['subj_sub_cat_1'])); ?>';
        selected_sub_cat = this.value;

        get_sub_category('subj_sub_cat_1', 'subj_cat_main', selected_sub_cat);
    });

    function get_sub_category(curr_item, parent_item, selected_val) {
        // alert("curr_item "+curr_item+" parent_item: "+parent_item+" selected_val:"+selected_val);
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var selected_sub_cat = selected_val;
        var category_code = document.getElementById(parent_item).value;
        $.ajax({
            type: "POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                selected_sub_cat: selected_sub_cat,
                category_code: category_code
            },
            url: "<?php echo base_url('newcase/Ajaxcalls/get_sub_category'); ?>",
            success: function(data) {
                $(document.getElementById(curr_item)).html(data);
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function() {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }

    $('#subj_sub_cat_1').change(function() {
        // alert(this.value);
        var selected_sub_cat = this.value;
        get_matrimonial(selected_sub_cat);
    });
    $('#sc_case_type').change(function() {
        //alert(this.value);
        var selected_case_type = this.value;
        get_matrimonial_for_casetype(selected_case_type);
    });

    function get_matrimonial(selected_val, selected_val2) {

        var selected_sub_cat = selected_val;
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                selected_sub_cat: selected_sub_cat
            },
            url: "<?php echo base_url('newcase/Ajaxcalls/get_sub_cat_check'); ?>",
            success: function(data) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
                if (data) {
                    $('#subcatLoadData').html(data);

                } else {
                    var selected_case_type = $("#sc_case_type option:selected").val();
                    //get_matrimonial_for_casetype(selected_case_type);
                }
            },
            error: function() {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }

    function get_matrimonial_for_casetype(selected_val) {
        var selected_casetype = selected_val;
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                selected_casetype: selected_casetype
            },
            url: "<?php echo base_url('newcase/Ajaxcalls/get_casetype_check'); ?>",
            success: function(data) {
                $('#subcatLoadData').html(data);
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function() {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }


    function getdtmodel() {
        // alert("hualalalaal");
        var spcasetype_id = $('#sc_sp_case_type_id').val();
        //alert(spcasetype_id);
        if (spcasetype_id == 6) {

            $('#dtsign').show();
            /*var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!
            var yyyy = today.getFullYear();
            if (dd < 10) {
                dd = '0' + dd
            }
            if (mm < 10) {
                mm = '0' + mm
            }

            today = yyyy + '-' + mm + '-' + dd;
            document.getElementById("datesignjail").setAttribute("max", today);*/



        } else {
            $('#dtsign').hide();
        }

    }

    $('#datesignjail').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd/mm/yy",
        maxDate: new Date


    });
</script>

<script type="text/javascript">
    $(document).ready(function() {

        var spcasetype_id = $('#sc_sp_case_type_id').val();

        var dateSignatureDiv = "<?php echo $new_case_details[0]['jail_signature_date']; ?>";
        //alert(dateSignatureDiv);
        if (dateSignatureDiv && spcasetype_id == 6) {
            $('#dtsign').show();
        } else {
            $('#dtsign').hide();
        }


        $("#sc_sp_case_type_id").select2().on('select2-focus', function() {
            debugger;
            $(this).data('select2-closed', true)
        });

        $('#test').mousedown(function() {
            event.preventDefault();
            $('#tableoptions').show();
        });


        /*start old addcase code*/

        /*end old addcase code*/


        // earlier court data
        //Earlier_not_court_type
        $("#Earlier_not_court_type").change(function() {
            var not_court_type = $("input[name='Earlier_not_court_type']:checked").val();
            var supremeCourtStateOptions = '';
            var supremeCourtBenchOptions = '';
            var courtTypeListOptions = '';
            if (not_court_type == 4) {
                courtTypeListOptions = '<option value="4" selected="selected">Supreme Court</option><option value="1">High Court</option><option value="3" >District Court</option><option value="5" >State Agency</option>';

                supremeCourtStateOptions = '<option selected="selected" value="490506">DELHI</option>';
                supremeCourtBenchOptions = '<option selected="selected" value="10000">DELHI</option>';
                $("#high_court_div1").hide();
                $("#high_court_div2").hide();
                $("#supreme_court1").show();
                $("#supreme_court2").show();
                $("#district_court1").hide();
                $("#district_court2").hide();
                $("#state_agency_div1").hide();
                $("#state_agency_div2").hide();
                $("#court_name").html(courtTypeListOptions);
                $("#supreme_state_name").html(supremeCourtStateOptions);
                $("#supreme_bench_name").html(supremeCourtBenchOptions);
                //$("#Earlier_not_court_type").prop("checked", true);
            } else {
                courtTypeListOptions = '<option value="4" >Supreme Court</option><option selected="selected" value="1">High Court</option><option value="3" >District Court</option><option value="5" >State Agency</option>';

                $("#high_court_div1").show();
                $("#high_court_div2").show();
                $("#supreme_court1").hide();
                $("#supreme_court2").hide();
                $("#district_court1").hide();
                $("#district_court2").hide();
                $("#state_agency_div1").hide();
                $("#state_agency_div2").hide();
                $("#court_name").html(courtTypeListOptions);
                //$("input:radio[name=Earlier_not_court_type]:checked")[0].checked = false;
                $("#Earlier_not_court_type").prop("checked", false);
            }
        });
        $(document).on('change', '#court_name', function() {
            var courtId = parseInt($.trim($(this).val()));
            var supremeCourtStateOptions = '';
            var supremeCourtBenchOptions = '';
            //alert('courtId='+courtId)
            if (courtId) {
                switch (courtId) {
                    case 4:
                        supremeCourtStateOptions = '<option selected="selected" value="490506">DELHI</option>';
                        supremeCourtBenchOptions = '<option selected="selected" value="10000">DELHI</option>';
                        $("#high_court_div1").hide();
                        $("#high_court_div2").hide();
                        $("#supreme_court1").show();
                        $("#supreme_court2").show();
                        $("#district_court1").hide();
                        $("#district_court2").hide();
                        $("#state_agency_div1").hide();
                        $("#state_agency_div2").hide();
                        $("#supreme_state_name").html(supremeCourtStateOptions);
                        $("#supreme_bench_name").html(supremeCourtBenchOptions);
                        $("#Earlier_not_court_type").prop("checked", true);
                        break;
                    case 1: //High Court
                        var CSRF_TOKEN = 'CSRF_TOKEN';
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                        $("#high_court_div1").show();
                        $("#high_court_div2").show();
                        $("#supreme_court1").hide();
                        $("#supreme_court2").hide();
                        $("#district_court1").hide();
                        $("#district_court2").hide();
                        $("#state_agency_div1").hide();
                        $("#state_agency_div2").hide();
                        //alert('High court courtId='+courtId);
                        var CSRF_TOKEN = 'CSRF_TOKEN';
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url(); ?>newcase/Ajaxcalls_subordinate_court/get_high_court",
                            data: {
                                CSRF_TOKEN: CSRF_TOKEN_VALUE
                            },
                            async: false,
                            success: function(data) {
                                $("#high_courtname").html(data);
                                $("#high_courtname").select2();
                                //$("input:radio[name=Earlier_not_court_type]:checked")[0].checked = false;
                                $("#Earlier_not_court_type").prop("checked", false);
                                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function(result) {
                                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                });
                            },
                            error: function() {
                                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function(result) {
                                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                });
                            }
                        });
                        break;
                    case 3: //District court
                        var CSRF_TOKEN = 'CSRF_TOKEN';
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                        $("#high_court_div1").hide();
                        $("#high_court_div2").hide();
                        $("#supreme_court1").hide();
                        $("#supreme_court2").hide();
                        $("#state_agency_div1").hide();
                        $("#state_agency_div2").hide();
                        $("#district_court1").show();
                        $("#district_court2").show();
                        //alert('District Court courtId='+courtId)
                        $.ajax({
                            type: "POST",
                            data: {
                                CSRF_TOKEN: CSRF_TOKEN_VALUE
                            },
                            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_state_list'); ?>",
                            success: function(data) {
                                $('#district_court_state_name').html(data);
                                $('#district_court_state_name').select2();
                                //$("input:radio[name=Earlier_not_court_type]:checked")[0].checked = false;
                                $("#Earlier_not_court_type").prop("checked", false);
                                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                });
                            },
                            error: function() {
                                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                });
                            }
                        });
                        break;
                    case 5: //Agency Court
                        var CSRF_TOKEN = 'CSRF_TOKEN';
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                        $("#state_agency_div1").show();
                        $("#state_agency_div2").show();
                        $("#high_court_div1").hide();
                        $("#high_court_div2").hide();
                        $("#supreme_court1").hide();
                        $("#supreme_court2").hide();
                        $("#district_court1").hide();
                        $("#district_court2").hide();
                        //alert('Agency Court courtId='+courtId)
                        $.ajax({
                            type: "POST",
                            data: {
                                CSRF_TOKEN: CSRF_TOKEN_VALUE
                            },
                            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_icmis_state_list'); ?>",
                            success: function(data) {
                                $('#state_agency').html(data);
                                $('#state_agency').select2();
                                //$("input:radio[name=Earlier_not_court_type]:checked")[0].checked = false;
                                $("#Earlier_not_court_type").prop("checked", false);
                                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                });
                            },
                            error: function() {
                                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                });
                            }
                        });
                        break;
                    default:
                        $("#high_courtname").html('<option value="">Select High Court</option>');
                }
            }
        });
        $(document).on('change', '#supreme_state_name', function() {
            var stateId = parseInt($.trim($(this).val()));
            var supremeCourtBenchOptions = '';
            supremeCourtBenchOptions = '<option value="">Select Bench</option><option selected="selected" value="10000">DELHI</option>';
            $("#supreme_bench_name").html(supremeCourtBenchOptions);
            // $("#supreme_bench_name").select2();
        });

        $(document).on('change', '#district_court_state_name', function() {
            var stateId = $.trim($(this).val());
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    state_id: stateId
                },
                url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_district_list'); ?>",
                success: function(data) {
                    $('#district_court_district_name').html(data);
                    $('#district_court_district_name').select2();
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function() {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        });

        $(document).on('change', '#state_agency', function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var agency_state_id = $(this).val();
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    agency_state_id: agency_state_id,
                    court_type: '5'
                },
                url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_state_agency_list'); ?>",
                success: function(data) {
                    $('#state_agency_name').html(data);
                    $('#state_agency_name').select2();
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function() {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        });

        $(document).on('change', '#high_courtname', function() {
            var high_court_id = $.trim($(this).val());
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    high_court_id: high_court_id,
                    court_type: '1'
                },
                url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_hc_bench_list'); ?>",
                success: function(data) {
                    $('#high_court_bench_name').html(data);
                    $('#high_court_bench_name').select2();
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function() {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        });


    });
</script>


<?php if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) { ?>
    <script>
        $("#browser").prop('required', false);
        $('#newCaseSaveForm').show();
        $('#add_case_details').on('submit', function() {
            if ($('#add_case_details').valid()) {
                var validateFlag = true;
                var form_data = $(this).serialize();
                var court_name = $.trim($("#court_name option:selected").val());
                if (court_name) {
                    court_name = parseInt(court_name);
                    switch (court_name) {
                        case 1:
                            var high_courtname = $("#high_courtname option:selected").val();
                            var high_court_bench_name = $("#high_court_bench_name option:selected").val();
                            if (high_courtname == '') {
                                alert("Please select  high court name.");
                                $("#high_courtname").focus();
                                validateFlag = false;
                                return false;
                            } else if (high_court_bench_name == '') {
                                alert("Please select  high court bench name.");
                                $("#high_court_bench_name").focus();
                                validateFlag = false;
                                return false;
                            }
                            break;
                        case 3:
                            var district_court_state_name = $("#district_court_state_name option:selected").val();
                            var district_court_district_name = $("#district_court_district_name option:selected").val();
                            if (district_court_state_name == '') {
                                alert("Please select  state name.");
                                $("#district_court_state_name").focus();
                                validateFlag = false;
                                return false;
                            } else if (district_court_district_name == '') {
                                alert("Please select  district name.");
                                $("#district_court_district_name").focus();
                                validateFlag = false;
                                return false;
                            }
                            break;
                        case 4:
                            var supreme_state_name = $("#supreme_state_name option:selected").val();
                            var supreme_bench_name = $("#supreme_bench_name option:selected").val();
                            if (supreme_state_name == '') {
                                alert("Please select  state name.");
                                $("#supreme_state_name").focus();
                                validateFlag = false;
                                return false;
                            } else if (supreme_bench_name == '') {
                                alert("Please select  bench name.");
                                $("#supreme_bench_name").focus();
                                validateFlag = false;
                                return false;
                            }
                            break;
                        case 5:
                            var state_agency = $("#state_agency option:selected").val();
                            var state_agency_name = $("#state_agency_name option:selected").val();
                            if (state_agency == '') {
                                alert("Please select  state name.");
                                $("#state_agency").focus();
                                validateFlag = false;
                                return false;
                            } else if (state_agency_name == '') {
                                alert("Please select agency name.");
                                $("#state_agency_name").focus();
                                validateFlag = false;
                                return false;
                            }
                            break;
                        default:
                            validateFlag = false;

                    }
                } else {
                    alert("Please select  court Type.");
                    $("#court_name").focus();
                    validateFlag = false;
                    return false;
                }
                if (validateFlag) {
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>newcase/caseDetails/add_case_details",
                        data: form_data,
                        async: false,
                        beforeSend: function() {
                            $('#pet_save').val('Please wait...');
                            $('#pet_save').prop('disabled', true);
                        },
                        success: function(data) {
                            //   alert(data);
                            $('#pet_save').val('SAVE');
                            $('#pet_save').prop('disabled', false);
                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                $('#msg').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            } else if (resArr[0] == 2) {
                                $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                $('#msg').show();
                                //window.location.href = resArr[2];
                                location.reload();

                            } else if (resArr[0] == 3) {
                                $('#msg').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            }
                            $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function(result) {
                                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                            });
                        },
                        error: function() {
                            $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function(result) {
                                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                            });
                        }

                    });
                    return false;
                }
            } else {
                return false;
            }
        });
    </script>

<?php } else { ?>
    <script>
        $("#browser").prop('required', true);
        var myFile = "";
        $('#newCaseSaveForm').hide();
        $('#pet_save').hide();
        $('#browser').on('change', function(e) {

            myFile = $("#browser").val();
            console.log(myFile);
            var upld = myFile.split('.').pop();

            if (upld == 'pdf') {
                var draft_petition_file_browser = $.trim($("#browser").val());
                if (draft_petition_file_browser.length == 0) {
                    alert('Upload document draft petition is required*');
                    $('#draft_petition_file_browser').append('Upload document draft petition is required*');
                    //swal("Cancelled", "", "error");
                    //$("#captchacode").focus();
                    return false;
                }
                $('#pet_save').hide();
                // alert("File uploaded is pdf");
                var fileName = e.target.files[0].name;
                /*var file_data     = $("#browser").prop("files")[0];
                alert(fileName + ' is the selected file.');
                form_data.append("pdfDocFile", file_data);*/
                var file_data = updateUploadFileName('browser');
                if (file_data) {
                    $('.progress').hide();
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    var fileName = $("#browser").prop("files")[0].name;
                    var filedata = $("#browser").prop("files")[0];
                    var formdata = new FormData();
                    formdata.append("pdfDocFile", filedata);
                    //formdata.append("doc_title", 'DRAFT PETITION');
                    formdata.append("CSRF_TOKEN", CSRF_TOKEN_VALUE);
                    var doc_title = $('[name="doc_title"]').val();
                    formdata.append("doc_title", $('[name="doc_title"]').val());

                    // alert('The file "' + doc_title +  '" has been selected.');
                    $.ajax({
                        //dataType: 'script',
                        type: 'POST',
                        url: "<?php echo base_url('newcase/caseDetails/is_upload_pdf'); ?>",
                        data: formdata,
                        contentType: false,
                        processData: false,
                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total;
                                    percentComplete = parseInt(percentComplete * 100);
                                    $('.myprogress').html(percentComplete + '%');
                                    $('.myprogress').css('width', percentComplete + '%');
                                }
                            }, false);
                            return xhr;
                        },
                        success: function(data) {
                            $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                //alert(result.CSRF_TOKEN_VALUE + '=='+ CSRF_TOKEN_VALUE);
                            });
                            //$("#upload_doc").prop("disabled", true);
                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                $('#msg').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            } else if (resArr[0] == 2) {
                                $('#pet_save').show();
                                $('#newCaseSaveForm').show();
                                //$(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                //$('#msg').show();
                                // location.reload();
                            } else if (resArr[0] == 3) {
                                $('#msg').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            } else if (resArr[0] == 4) {
                                $('#msg').show();
                                $('.progress').hide();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            }
                            $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                            });
                        },
                        error: function(e) {
                            $("#upload").prop("disabled", false);
                        }
                    });
                }

            } else {
                $('#pet_save').hide();
                $('#newCaseSaveForm').hide();


                alert("Only PDF are allowed");
                // location.reload();
            }

        });


        $('#add_case_details').on('submit', function() {
            $("#browser").prop('required', true);
            if ($('#add_case_details').valid()) {
                var draft_petition_file_browser = $.trim($("#browser").val());
                if (draft_petition_file_browser.length == 0) {
                    alert('Upload document draft petition is required*');
                    $('#draft_petition_file_browser').append('Upload document draft petition is required*');
                    //swal("Cancelled", "", "error");
                    //$("#captchacode").focus();
                    return false;
                }
                var validateFlag = true;
                var form_data = $(this).serialize();
                var court_name = $.trim($("#court_name option:selected").val());
                if (court_name) {
                    court_name = parseInt(court_name);
                    switch (court_name) {
                        case 1:
                            var high_courtname = $("#high_courtname option:selected").val();
                            var high_court_bench_name = $("#high_court_bench_name option:selected").val();
                            if (high_courtname == '') {
                                alert("Please select  high court name.");
                                $("#high_courtname").focus();
                                validateFlag = false;
                                return false;
                            } else if (high_court_bench_name == '') {
                                alert("Please select  high court bench name.");
                                $("#high_court_bench_name").focus();
                                validateFlag = false;
                                return false;
                            }
                            break;
                        case 3:
                            var district_court_state_name = $("#district_court_state_name option:selected").val();
                            var district_court_district_name = $("#district_court_district_name option:selected").val();
                            if (district_court_state_name == '') {
                                alert("Please select  state name.");
                                $("#district_court_state_name").focus();
                                validateFlag = false;
                                return false;
                            } else if (district_court_district_name == '') {
                                alert("Please select  district name.");
                                $("#district_court_district_name").focus();
                                validateFlag = false;
                                return false;
                            }
                            break;
                        case 4:
                            var supreme_state_name = $("#supreme_state_name option:selected").val();
                            var supreme_bench_name = $("#supreme_bench_name option:selected").val();
                            if (supreme_state_name == '') {
                                alert("Please select  state name.");
                                $("#supreme_state_name").focus();
                                validateFlag = false;
                                return false;
                            } else if (supreme_bench_name == '') {
                                alert("Please select  bench name.");
                                $("#supreme_bench_name").focus();
                                validateFlag = false;
                                return false;
                            }
                            break;
                        case 5:
                            var state_agency = $("#state_agency option:selected").val();
                            var state_agency_name = $("#state_agency_name option:selected").val();
                            if (state_agency == '') {
                                alert("Please select  state name.");
                                $("#state_agency").focus();
                                validateFlag = false;
                                return false;
                            } else if (state_agency_name == '') {
                                alert("Please select agency name.");
                                $("#state_agency_name").focus();
                                validateFlag = false;
                                return false;
                            }
                            break;
                        default:
                            validateFlag = false;

                    }
                } else {
                    alert("Please select  court Type.");
                    $("#court_name").focus();
                    validateFlag = false;
                    return false;
                }
                if (validateFlag) {

                    //var CSRF_TOKEN_VALUE= '<//?php echo $this->security->get_csrf_hash();?>';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                    // alert('CSRF_TOKEN_VALUE='+CSRF_TOKEN_VALUE);
                    $('.progress').hide();
                    /*plan-A*/
                    /*var doc_title= $('[name="doc_title"]').val();

                    var no_of_petitioners= $('[name="no_of_petitioners"]').val();
                    var no_of_respondents= $('[name="no_of_respondents"]').val();
                    var cause_pet=$('[name="cause_pet"]').val();
                    var cause_res= $('[name="cause_res"]').val();
                    var sc_case_type= $('[name="sc_case_type"]').val();
                    var sc_sp_case_type_id= $('[name="sc_sp_case_type_id"]').val();
                    var subj_cat_main= $('[name="subj_cat_main"]').val();
                    var subj_sub_cat_1= $('[name="subj_sub_cat_1"]').val();
                    var subj_sub_cat_2= $('[name="subj_sub_cat_2"]').val();
                    var subj_sub_cat_3= $('[name="subj_sub_cat_3"]').val();



                    var high_courtname= $('[name="high_courtname"]').val();
                    var high_court_bench_name= $('[name="high_court_bench_name"]').val();
                    var district_court_state_name= $('[name="district_court_state_name"]').val();
                    var district_court_district_name= $('[name="district_court_district_name"]').val();
                    var supreme_state_name= $('[name="supreme_state_name"]').val();
                    var supreme_bench_name= $('[name="supreme_bench_name"]').val();
                    var state_agency= $('[name="state_agency"]').val();
                    var state_agency_name= $('[name="state_agency_name"]').val();
                    var datesignjail= $('[name="datesignjail"]').val();
                    var court_name= $('[name="court_name"]').val();*/

                    /*var matrimonialCheck= $('[name="matrimonialCheck"]').val();
                    var matrimonial=$('[name="matrimonial"]').val();
                    var question_of_law= $('[name="question_of_law"]').val();
                    var grounds= $('[name="grounds"]').val();
                    var interim_grounds= $('[name="interim_grounds"]').val();
                    var main_prayer= $('[name="main_prayer"]').val();
                    var interim_relief= $('[name="interim_relief"]').val();*/
                    /*plan-A*/

                    var subj_sub_cat_2 = $('[name="subj_sub_cat_2"]').val();
                    //alert(subj_sub_cat_2);
                    if (typeof subj_sub_cat_2 === 'undefined') {
                        subj_sub_cat_2 = '';
                    }
                    var subj_sub_cat_3 = $('[name="subj_sub_cat_3"]').val();
                    if (typeof subj_sub_cat_3 === 'undefined') {
                        subj_sub_cat_2 = '';
                    }
                    //alert(subj_sub_cat_2);
                    var filedata = $("#browser").prop("files")[0];
                    var formdata = new FormData();
                    formdata.append("pdfDocFile", filedata);

                    /*plan-b*/

                    formdata.append("CSRF_TOKEN", CSRF_TOKEN_VALUE);
                    formdata.append("doc_title", $('[name="doc_title"]').val());
                    formdata.append("no_of_petitioners", $('[name="no_of_petitioners"]').val());
                    formdata.append("no_of_respondents", $('[name="no_of_respondents"]').val());
                    formdata.append("cause_pet", $('[name="cause_pet"]').val());
                    formdata.append("cause_res", $('[name="cause_res"]').val());
                    formdata.append("sc_case_type", $('[name="sc_case_type"]').val());
                    formdata.append("sc_sp_case_type_id", $('[name="sc_sp_case_type_id"]').val());
                    formdata.append("subj_cat_main", $('[name="subj_cat_main"]').val());
                    formdata.append("subj_sub_cat_1", $('[name="subj_sub_cat_1"]').val());
                    formdata.append("subj_sub_cat_2", subj_sub_cat_2);
                    formdata.append("subj_sub_cat_3", subj_sub_cat_3);



                    formdata.append("high_courtname", $('[name="high_courtname"]').val());
                    formdata.append("high_court_bench_name", $('[name="high_court_bench_name"]').val());
                    formdata.append("district_court_state_name", $('[name="district_court_state_name"]').val());
                    formdata.append("district_court_district_name", $('[name="district_court_district_name"]').val());
                    formdata.append("supreme_state_name", $('[name="supreme_state_name"]').val());
                    formdata.append("supreme_bench_name", $('[name="supreme_bench_name"]').val());
                    formdata.append("state_agency", $('[name="state_agency"]').val());
                    formdata.append("state_agency_name", $('[name="state_agency_name"]').val());
                    formdata.append("datesignjail", $('[name="datesignjail"]').val());
                    formdata.append("court_name", $('[name="court_name"]').val());


                    /* formdata.append("matrimonialCheck", $('[name="matrimonialCheck"]').val());
                     formdata.append("matrimonial", $('[name="matrimonial"]').val());
                     formdata.append("question_of_law", $('[name="question_of_law"]').val());
                     formdata.append("grounds", $('[name="grounds"]').val());
                     formdata.append("interim_grounds", $('[name="interim_grounds"]').val());
                     formdata.append("main_prayer", $('[name="main_prayer"]').val());
                     formdata.append("interim_relief", $('[name="interim_relief"]').val());*/
                    /*plan-b*/
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>newcase/caseDetails/add_case_details",
                        //data: form_data,
                        /* data: {
                             no_of_petitioners:no_of_petitioners,no_of_respondents:no_of_respondents,cause_pet:cause_pet,cause_res:cause_res,
                             sc_case_type:sc_case_type,sc_sp_case_type_id:sc_sp_case_type_id,subj_cat_main:subj_cat_main,subj_sub_cat_1:subj_sub_cat_1,subj_sub_cat_2:subj_sub_cat_2,subj_sub_cat_3:subj_sub_cat_3,
                             high_courtname:high_courtname,high_court_bench_name:high_court_bench_name,district_court_state_name:district_court_state_name,district_court_district_name:district_court_district_name,supreme_state_name:supreme_state_name,supreme_bench_name:supreme_bench_name,
                             state_agency:state_agency,state_agency_name:state_agency_name,datesignjail:datesignjail,court_name:court_name
                             ,CSRF_TOKEN: CSRF_TOKEN_VALUE ,doc_title:doc_title
                             //,pdfDocFile:pdfDocFile
                         },*/
                        // async: false,
                        data: formdata,
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $('#pet_save').val('Please wait...');
                            $('#pet_save').prop('disabled', true);
                        },
                        success: function(data) {
                            // alert(data);
                            $('#pet_save').val('SAVE');
                            $('#pet_save').prop('disabled', false);
                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                $('#msg').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            } else if (resArr[0] == 2) {
                                $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                $('#msg').show();
                                //window.location.href = resArr[2];
                                location.reload();
                            } else if (resArr[0] == 3) {
                                $('#msg').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            }
                            $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function(result) {
                                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                            });
                        },
                        error: function() {
                            $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function(result) {
                                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                            });
                        }

                    });
                    return false;
                }
            } else {
                return false;
            }
        });
    </script>

<?php } ?>