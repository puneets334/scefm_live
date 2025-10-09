<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
<!-- <link rel="shortcut icon" href="<?= base_url() . 'assets/newAdmin/' ?>images/favicon.gif"> -->
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/font-awesome.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/animate.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/material.css" rel="stylesheet" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/style.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/responsive.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/black-theme.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?= base_url() . 'assets/newAdmin/' ?>css/jquery.dataTables.min.css">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/fullcalendar.css" rel="stylesheet">
<!-- <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">-->
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css"> 
<link href="<?= base_url() . 'assets' ?>/css/select2.min.css" rel="stylesheet">
<style>
    /* .datepicker-dropdown {
        margin-top: 125px !important;
    } */

    span.select2 {
        width: 100% !important;

    }

    .datepicker-dropdown {
        background-color: #fff;
    }

    .error {
        color: red;
    }

    .datepicker-days {
        background-color: #ffffff;
    }
</style>
<div class="center-content-inner comn-innercontent">
    <div class="tab-content">
        <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <?php
            $attribute = ['class' => 'form-horizontal', 'name' => 'add_case_details', 'id' => 'add_case_details', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data'];
            echo form_open('#', $attribute);
            $cause_title = explode(' Vs. ', @$new_case_details[0]['cause_title'] ?? '');
            ?>
            <div class="tab-form-inner">
                <div class="row">
                    <h6 class="text-center fw-bold">Case Details</h6>
                </div>
                <?= ASTERISK_RED_MANDATORY ?>
                <div class="row" style="display: none;" id="errDiv">
                    <div class="alert alert-danger" role="alert" id="alertMsg"></div>
                </div>
                <div class="row">
                    <?php if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_DEPARTMENT,])) {
                    ?>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12">Select
                                        AOR <span style="color: red" class="astriks">*</span> :</label>
                                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                        <select name="impersonated_aor" id="impersonated_aor" required>
                                            <option value="" title="Select" >Select AOR</option>
                                            <?php
                                            if (count($department_aor)) {
                                                foreach ($department_aor as $dataRes) {
                                                    $sel = ($selected_aor[0]->aor_code == (string) $dataRes->aor_code) ? "selected=selected" : '';
                                            ?>
                                                    <option <?php echo $sel; ?> value="<?= $dataRes->aor_code ?>">
                                                        <?php echo_data($dataRes->name . ' (' . $dataRes->aor_code . ')'); ?> </option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br /> <br />
                    <?php } ?>
                    <?php if(in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK,])){ // For Departmment Filing
                        //var_dump($_SESSION['login']);var_dump($selected_aor);
                        ?>

                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">

                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Select AOR <span style="color: red">*</span> :</label>
                                    <div class="col-md-7 col-sm-12 col-xs-12">
                                        <select name="impersonated_aor" id="impersonated_aor" onchange="user_clerk_aor_is_gov(this.value)" class="form-control input-sm" required>
                                            <option value="" title="Select">Select AOR</option>
                                            <?php
                                            //if (count($clerk_aor)) {
                                            if (!empty($clerk_aor)) { //count testing ok
                                                foreach ($clerk_aor as $dataRes) {
                                                    $ref_department_id = !empty($dataRes->ref_department_id) && $dataRes->ref_department_id !=0 ? $dataRes->ref_department_id : 0;
                                                    $sel = ($selected_aor[0]->aor_code == (string) $dataRes->aor_code ) ? "selected=selected" : '';
                                                    ?>
                                                    <option <?php echo $sel; ?> value="<?=$dataRes->aor_code.'@@@'.$ref_department_id; ?>"><?php echo_data($dataRes->name.' ('.$dataRes->aor_code.')'); ?> </option>;
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
                        </div>
                        <br/> <br/>
                    <?php } ?>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="mb-3">
                            <label for="" class="form-label">Cause Title Petitioner <span style="color: red" class="astriks">*</span></label>
                            <textarea tabindex='1' rows="1" id="cause_pet" name="cause_pet" minlength="3" maxlength="99" class="form-control cus-form-ctrl" placeholder="Cause Title Petitioner" oninput="validateInput(event)" type="text" style="text-transform: uppercase" required ><?php echo_data(@$cause_title[0]); ?></textarea>
                            <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Petitioner name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>)." title="Petitioner name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                <i class="fa fa-question-circle-o"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="mb-3">

                            <label for="" class="form-label">Cause Title Respondent <span style="color: red" class="astriks">*</span></label>

                            <textarea tabindex='2' rows="1" id="cause_res" name="cause_res" minlength="3" maxlength="99" style="text-transform: uppercase" class="form-control cus-form-ctrl" placeholder="Cause Title Respondent" oninput="validateInput(event)" type="text" required><?php echo_data(@$cause_title[1] ?? ''); ?></textarea>


                            <span class="input-group-addon" data-placement="bottom" data-toggle="popover" title="Respondent name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                <i class="fa fa-question-circle-o"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="mb-3">
                            <label for="" class="form-label">Case Type <span style="color: red" class="astriks">*</span></label>
                            <select tabindex='3' name="sc_case_type" id="sc_case_type" class="form-control cus-form-ctrl filter_select_dropdown sc_case_type" required>
                                <option value="" title="Select">Select Case Type</option>
                                <?php
                                if (count($sc_case_type)) {
                                    foreach ($sc_case_type as $dataRes) {
                                        $sel = (@$new_case_details[0]['sc_case_type_id'] == (string) $dataRes->casecode) ? "selected=selected" : '';
                                ?>
                                        <option <?php echo $sel; ?> value="<?php echo_data(url_encryption(trim($dataRes->casecode) . '##' . 'abcd')); ?>"><?php echo_data($dataRes->casename); ?>
                                        </option>;
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <span id="subcatLoadData"></span>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="mb-3">
                            <label for="" class="form-label">Special Case Type <span style="color: red" class="astriks">*</span></label>
                            <?php
                            $selectNone = @$new_case_details[0]['sc_sp_case_type_id'] == '1' ? 'selected=selected' : '';
                            $selectJP = @$new_case_details[0]['sc_sp_case_type_id'] == '6' ? 'selected=selected' : '';
                            // $selectPUD = @$new_case_details[0]['sc_sp_case_type_id'] == '7' ? 'selected=selected' : '';
                            ?>
                            <select tabindex='4' name="sc_sp_case_type_id" id="sc_sp_case_type_id" class="form-control cus-form-ctrl filter_select_dropdown" style="width: 100%" required onchange="getdtmodel()">
                                <option value="" title="Select">Select Special Case Type</option>
                                <option <?php echo $selectNone; ?> value="1" selected>NONE</option>
                                <option <?php echo $selectJP; ?> value="6">JAIL PETITION</option>
                                <!-- <option <?php // echo $selectPUD; ?> value="7">PUD</option> -->
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4" id="dtsign">
                        <div class="mb-3 icon-input">
                            <label for="" class="form-label">Date of signature of jail incharge</label>
                            <input tabindex='5' class="form-control cus-form-ctrl datesignjail" id="datesignjail" name="datesignjail" value=" <?php  echo isset($new_case_details[0]) && !empty($new_case_details[0]['jail_signature_date']) ? date("d/m/Y", strtotime($new_case_details[0]['jail_signature_date'])) : ''; ?>" type="text" > 
                            <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Date of Birth." title="Please Enter Date of Birth.">
                                <i class="fa fa-question-circle-o"></i>
                            </span>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="mb-3">
                            <label for="" class="form-label">Main Category <span style="color: red" class="astriks">*</span></label>
                            <select tabindex='6' name="subj_cat_main" id="subj_cat_main" class="form-control cus-form-ctrl filter_select_dropdown" required>
                                <option value="" title="Select">Select Main Category </option>
                                <?php
                                if (count($main_subject_cat)) {
                                    foreach ($main_subject_cat as $dataRes) {
                                        $sel = (@$new_case_details[0]['subj_main_cat'] == $dataRes->id) ? "selected=selected" : '';
                                ?>
                                        <option <?php echo $sel; ?> value="<?php echo_data(url_encryption(trim($dataRes->id . '##' . $dataRes->subcode1))); ?>">
                                            <?php echo_data(strtoupper($dataRes->sub_name1));
                                            if (!empty($dataRes->category_sc_old)) {
                                                echo ' (' . $dataRes->category_sc_old . ')';
                                            }
                                            ?>
                                        </option>;
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="mb-3">
                            <label for="" class="form-label">Sub Category <span style="color: red">*</span></label>
                            <select tabindex='7' name="subj_sub_cat_1" id="subj_sub_cat_1" class="form-control cus-form-ctrl filter_select_dropdown subj_sub_cat_1" required>
                                <option value="" title="Select">Select Sub Category </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="mb-3">
                            <label for="" class="form-label">Special Category</label>
                            <select tabindex="8" id="special_category" name="special_category" class="form-control cus-form-ctrl filter_select_dropdown" required>
                                <?php
                                if (count($special_category)) {
                                    $sel = (@$new_case_details[0]['special_category'] == 0) ? "selected=selected" : ''; ?>
                                    <option <?php echo $sel; ?> value="<?php echo_data(url_encryption(0)); ?>"><?php echo_data(strtoupper('None')); ?>
                                    </option>
                                    <?php
                                    foreach ($special_category as $specCat) {
                                        $sel = (@$new_case_details[0]['special_category'] == $specCat->id) ? "selected=selected" : '';
                                    ?>
                                        <option <?php echo $sel; ?> value="<?php echo_data(url_encryption(trim($specCat->id))); ?>"><?php echo_data(strtoupper($specCat->category_name)); ?>
                                        </option>;
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-2 col-lg-2">
                                    <label for="" class="form-label">If SCLSC</label>
                                    <div class="form-check form-switch">
                                        <input tabindex="9" class="form-check-input if_sclsc" type="checkbox" name="if_sclsc" id="if_sclsc flexSwitchCheckDefault" <?php echo !empty(@$new_case_details[0]['if_sclsc']) && @$new_case_details[0]['if_sclsc'] == 1 ? 'checked' : ''; ?>>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-5 col-lg-5 toggle_amr">
                                    <label class="form-label">ANR No
                                        <span style="color: red">*</span>
                                    </label>
                                    <input id="sclsc_amr_no" class="form-control cus-form-ctrl" name="sclsc_amr_no" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" value="<?php echo @$new_case_details[0]['sclsc_amr_no']; ?>" minlength="1" maxlength="10" placeholder="Enter ANR No" type="text">
                                </div>
                                <div class="col-12 col-sm-12 col-md-5 col-lg-5 toggle_amr">
                                    <label class="form-label">ANR Year
                                        <span style="color: red">*</span>
                                    </label>
                                    <select id="sclsc_amr_year" name="sclsc_amr_year" class="form-control filter_select_dropdown">  </select>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <?php if(in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])){  ?>
                        <div class="row" style="display: none;" id="CLERK_AOR_is_govt_filing">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Whether filed by Government? </label>
                                    <div class="col-md-7 col-sm-12 col-xs-12">
                                        <label class="switch">
                                            <input type="checkbox" name="is_govt_filing" id="is_govt_filing" <?php echo (!empty($new_case_details[0]['is_govt_filing']) && ($new_case_details[0]['is_govt_filing'] ==1)) ? 'checked' : ''  ?>>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="mb-3">
                            <label for="" class="form-label">Number of Petitioner (s) <span style="color: red" class="astriks">*</span></label>
                            <input min="0" tabindex="10" max="9999" maxlength="4" pattern="\d*" id="no_of_petitioners" name="no_of_petitioners" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" value="<?php echo @$new_case_details[0]['no_of_petitioners']; ?>" minlength="1" class="form-control cus-form-ctrl" placeholder="No of Petitioner" type="text" required>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="mb-3">
                            <label for="" class="form-label">Number of Respondent (s) <span style="color: red" class="astriks">*</span></label>
                            <input min="0" tabindex="11" max="9999" maxlength="4" pattern="\d*" id="no_of_respondents" name="no_of_respondents" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" value="<?php echo @$new_case_details[0]['no_of_respondents']; ?>" minlength="1" class="form-control cus-form-ctrl" placeholder="No of Respondent" type="text" required>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="radio-btns-inp mb-3">
                            <div class="form-check form-check-inline">

                                <label class="form-label" for="inlineRadio1">Earlier Court Details <span class="pink-text"> (Order Challenged)</span></label>

                                <input tabindex="12" class="cus-form-check" type="checkbox" name="Earlier_not_court_type" id="Earlier_not_court_type" value="4" <?php echo !empty($new_case_details[0]['court_type']) && $new_case_details[0]['court_type'] == 4 ? 'checked' : ''; ?>>No Earlier Court</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="mb-3">
                            <label for="" class="form-label">Court Type <span style="color: red" class="astriks">*</span></label>
                            <select id="court_name" name="court_name" tabindex="13" class="form-control cus-form-ctrl filter_select_dropdown" required>
                                <option value="">Select Court Name</option>
                                <?php
                                if (!empty(@$new_case_details[0]['court_type'])) {
                                ?>
                                    <option value="4" <?php echo !empty($new_case_details[0]['court_type']) && $new_case_details[0]['court_type'] == 4 ? 'selected="selected"' : ''; ?>>Supreme Court</option>
                                    <option value="1" <?php echo !empty($new_case_details[0]['court_type']) && $new_case_details[0]['court_type'] == 1 ? 'selected="selected"' : ''; ?>>High Court</option>
                                    <option value="3" <?php echo !empty($new_case_details[0]['court_type']) && $new_case_details[0]['court_type'] == 3 ? 'selected="selected"' : ''; ?>>District Court</option>
                                    <option value="5" <?php echo !empty($new_case_details[0]['court_type']) && $new_case_details[0]['court_type'] == 5 ? 'selected="selected"' : ''; ?>>State Agency</option>
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



                    <?php if (!is_null(getSessionData('login')['department_id'])) { ?>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="mb-3">
                                <label for="" class="form-label">Whether filed by Government?</label>
                                <input class="form-check-input" type="checkbox" id="" name="is_govt_filing" id="is_govt_filing flexSwitchCheckDefault" <?php echo !empty(@$new_case_details[0]['is_govt_filing']) && @$new_case_details[0]['is_govt_filing'] == 1 ? 'checked' : ''; ?>>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4" id="supreme_court1" style="<?php echo !empty($supreme_court_bench) && isset($supreme_court_bench) ? 'display: block;' : 'display: none;'; ?>">
                        <div class="mb-3">
                            <label class="form-label">State Name <span style="color: red" class="astriks">*</span></label>
                            <select id="supreme_state_name" tabindex="14" name="supreme_state_name" class="form-control cus-form-ctrl filter_select_dropdown" readonly="readonly">
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
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4" id="supreme_court2" style="<?php echo !empty($supreme_court_bench) && isset($supreme_court_bench) ? 'display: block;' : 'display: none;'; ?>">
                        <div class="mb-3">
                            <label class="form-label">Bench
                                Name <span style="color: red" class="astriks">*</span></label>
                            <select id="supreme_bench_name" tabindex="15" name="supreme_bench_name" class="form-control cus-form-ctrl filter_select_dropdown" readonly="readonly">
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
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4" id="high_court_div1" style="<?php echo (!empty($high_court_bench) && isset($high_court_bench)) || $high_court_drop_down ? 'display: block;' : 'display: none;'; ?>">
                        <div class="mb-3">
                            <label class="form-label">High Court Name <span style="color: red" class="astriks">*</span></label>
                            <select id="high_courtname" tabindex="16" name="high_courtname" class="form-control cus-form-ctrl filter_select_dropdown">
                                <option value="">Select High Court</option>
                                <?php
                                if (isset($high_court_drop_down) && !empty($high_court_drop_down)) {
                                    foreach ($high_court_drop_down as $k => $v) {
                                        if ($v->hc_id == @$new_case_details[0]['estab_id']) {
                                            echo '<option selected="selected" value="' . escape_data(url_encryption($v->hc_id . '##' . $v->name)) . '">' . escape_data(strtoupper($v->name)) . '</option>';
                                        } else {
                                            echo '<option value="' . escape_data(url_encryption($v->hc_id . '##' . $v->name)) . '">' . escape_data(strtoupper($v->name)) . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4" id="high_court_div2" style="<?php echo (!empty($high_court_bench) && isset($high_court_bench)) || $high_court_drop_down ? 'display: block;' : 'display: none;'; ?>">
                        <div class="mb-3">
                            
                            <label class="form-label">Bench Name
                                <span style="color: red" class="astriks">*</span></label>
                            <select id="high_court_bench_name" tabindex="17" name="high_court_bench_name" class="form-control cus-form-ctrl filter_select_dropdown">
                                <option value="">Select Bench Name</option>
                                <?php

                                if (isset($high_court_bench) && !empty($high_court_bench)) {
                                    foreach ($high_court_bench as $k => $bench) {
                                        if ($bench['est_code'] == @$new_case_details[0]['estab_code']) {
                                            echo '<option selected="selected" value="' . escape_data(url_encryption($bench['bench_id'] . '##' . $bench['name'] . '##' . $bench['est_code'])) . '">' . escape_data(strtoupper($bench['name'])) . '</option>';
                                        } else {
                                            echo '<option value="' . escape_data(url_encryption($bench['bench_id'] . '##' . $bench['name'] . '##' . $bench['est_code'])) . '">' . escape_data(strtoupper($bench['name'])) . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4" id="district_court1" style="<?php echo !empty($state_list) && isset($state_list) ? 'display: block;' : 'display: none;'; ?>">
                        <div class="mb-3">
                            <label class="form-label">State
                                Name
                                <span style="color: red" class="astriks">*</span></label>
                            <select id="district_court_state_name" tabindex="18" name="district_court_state_name" class="form-control cus-form-ctrl filter_select_dropdown">
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
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4" id="district_court2" style="<?php echo !empty($state_list) && isset($state_list) ? 'display: block;' : 'display: none;'; ?>">
                        <div class="mb-3">
                            <label class="form-label">District
                                <span style="color: red" class="astriks">*</span></label>
                            <select id="district_court_district_name" tabindex="19" name="district_court_district_name" class="form-control cus-form-ctrl filter_select_dropdown">
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
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4" id="state_agency_div1" style="<?php echo !empty($state_agency_list) && isset($state_agency_list) ? 'display: block' : 'display: none;'; ?>">
                        <div class="mb-3">
                            <label class="form-label">State
                                Name
                                <span style="color: red" class="astriks">*</span></label>
                            <select id="state_agency" tabindex="20" name="state_agency" class="form-control cus-form-ctrl filter_select_dropdown">
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
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4" id="state_agency_div2" style="<?php echo (isset($state_agency_list) && !empty($state_agency_list)) ? 'display: block' : 'display: none;'; ?>">
                        <div class="mb-3">
                            <label class="form-label">Agency
                                Name
                                <span style="color: red" class="astriks">*</span></label>
                            <select id="state_agency_name" tabindex="21" name="state_agency_name" class="form-control cus-form-ctrl filter_select_dropdown">
                                <option value="">Select Agency</option>
                                <?php
                                if (isset($agencies) && !empty($agencies)) {
                                    foreach ($agencies as $agency) {
                                        if (isset($new_case_details[0]['estab_id']) && !empty($new_case_details[0]['estab_id']) && $new_case_details[0]['estab_id'] == $agency['id']) {
                                            echo '<option selected="selected" value="' . escape_data(url_encryption($agency['id'] . '##' . $agency['agency_name'] . '##' . $agency['short_agency_name'])) . '">' . escape_data(strtoupper($agency['short_agency_name'])) . ' - ' . escape_data(strtoupper($agency['agency_name'])) . '</option>';
                                        } else {
                                            echo '<option value="' . escape_data(url_encryption($agency['id'] . '##' . $agency['agency_name'] . '##' . $agency['short_agency_name'])) . '">' . escape_data(strtoupper($agency['short_agency_name'])) . ' - ' . escape_data(strtoupper($agency['agency_name'])) . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 my-3">
                        <div class="row">
                            <div class="progress" style="display: none;">
                                <div class="progress-bar progress-bar-success myprogress" role="progressbar" value="0" max="100" style="width:0%">0%</div>
                            </div>
                        </div>
                        <div class="save-btns text-center">
                            <?php if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) { ?>
                                <button type="submit" tabindex='22' class="quick-btn btn-success" value="">UPDATE
                                </button>
                                <a href="<?= base_url('newcase/petitioner') ?>" tabindex="24" class="quick-btn gray-btn" type="button" id="nextButton">NEXT</a>
                            <?php } else { ?>
                                <button type="submit" tabindex='23' class="quick-btn" id="pet_save" value="">SAVE
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- form--end  -->
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
<script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script> 
<script src="<?= base_url() ?>assets/js/sha256.js"></script>
<script src="<?= base_url() ?>assets/newAdmin/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/select2.min.js"></script>
{{-- @endsection --}}
<script>
    <?php
    if(isset($new_case_details[0]['if_sclsc']) && $new_case_details[0]['if_sclsc'] == 1){ ?>
    $(document).ready(function () {
        $(".toggle_amr").show();
        $(".if_sclsc").click(function() {
            if($(this).is(":checked")) {
                $(".toggle_amr").show(300);
            } else {
                $(".toggle_amr").hide(200);
            }
        });
    });
    <?php } else{ ?>
    $(document).ready(function () {
        $(".toggle_amr").hide();
        $(".if_sclsc").click(function() {
            if($(this).is(":checked")) {
                $(".toggle_amr").show(300);
            } else {
                $(".toggle_amr").hide(200);
            }
        });
    });
    <?php } ?>
    document.addEventListener('DOMContentLoaded', function() {
        const startYear = 1940;
        const currentYear = new Date().getFullYear();
        const yearDropdown = document.getElementById('sclsc_amr_year');
        for (let year = currentYear; year >= startYear; year--) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearDropdown.appendChild(option);
        }
        // Set the selected value
        <?php
        if((isset($new_case_details[0]['if_sclsc']) && $new_case_details[0]['if_sclsc'] == 1) && (isset($new_case_details[0]['sclsc_amr_year']) && !empty($new_case_details[0]['sclsc_amr_year']))){ ?>
        yearDropdown.value = "<?=@$new_case_details[0]['sclsc_amr_year'];?>"; // Make sure to use a string for the value
        <?php } ?>
    });
    $('#party_dob').datepicker({
        onSelect: function(value) { 
            var parts = value.split("/");
            var day = parts[0] && parseInt(parts[0], 10);
            var month = parts[1] && parseInt(parts[1], 10);
            var year = parts[2] && parseInt(parts[2], 10);
            var str = month + '/' + day + '/' + year;
            var today = new Date(),
                dob = new Date(str),
                age = new Date(today - dob).getFullYear() - 1970;
            $('#party_age').val(age);
        },
        changeMonth: true,
        changeYear: true,
        yedefaultarRange: "-100:-1",
        dateFormat: "dd/mm/yy",
        startDate: '-40y',
        "autoclose": true
    });

    $('#cause_date').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd/mm/yy",
        maxDate: new Date,
        "autoclose": true


    });
    $('#filing_date,#order_date').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd/mm/yy",

        maxDate: new Date,
        "autoclose": true
    });
    $('#decision_date,#cc_applied_date,#cc_ready_date,#offence_date,#charge_sheet_date,#accident_date,#fir_file_date,\n\
                   #trial_decision_date,#trial_cc_applied_date,#trial_cc_ready_date,#hc_decision_date,#hc_cc_applied_date,\n\
                   #hc_cc_ready_date,#app_case_order_dt,#trial_case_order_dt').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd/mm/yy",
        maxDate: new Date
    });
</script>
<?php if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {?>
<script>
    user_clerk_aor_is_gov('<?=isset($new_case_details[0]['is_govt_filing']) ? @$new_case_details[0]['is_govt_filing'] : null;?>');
    function user_clerk_aor_is_gov(id) {
        var string_selected = $.trim($("#impersonated_aor option:selected").val());
        var resArr = string_selected.split('@@@');
        if (resArr[1] == 1) {
            $('#CLERK_AOR_is_govt_filing').show();
        }else {
            $('#CLERK_AOR_is_govt_filing').hide();
        }
    }
</script>
<?php }?>
<script type="text/javascript">
    $("#cde").click(function() {
        $.ajax({
            url: "<?php echo base_url('newcase/finalSubmit/valid_cde'); ?>",
            success: function(data) {
                var dataas = data.split('?');
                var ct = dataas[0];
                var dataarr = dataas[1].slice(1).split(',');
                if ((dataarr[0] != 2) && (dataarr[0] != 3) && (dataarr[0] != 6) && (dataarr[0] !=
                        7)) {
                    window.location.href = "<?php echo base_url('newcase/finalSubmit/forcde'); ?>";
                }
                if ((dataarr[0]) == 2) {
                    alert("Petitioner details are mandatory to fill. Redirecting to page !!");
                    window.location.href = "<?php echo base_url('newcase/petitioner'); ?>";
                }
                if ((dataarr[0]) == 3) {
                    alert("Respondent details are mandatory to fill. Redirecting to page !!");
                    window.location.href = "<?php echo base_url('newcase/respondent'); ?>";
                }
                if (((dataarr[0]) == 7) && (ct != 5) && (ct != 6) && (ct != 7) && (ct != 8)) {
                    alert(
                        "Subordinate_courts details are mandatory to fill. Redirecting to page !!"
                    );
                    window.location.href = "<?php echo base_url('newcase/subordinate_court'); ?>";
                }
            },
            error: function() {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
        return false;
    });
    $("#efilaor").click(function() {
        $.ajax({
            url: "<?php echo base_url('newcase/AutoDiary/valid_efil'); ?>", // enabled this for auto diary generation
            success: function(data) {
                var dataas = data.split('?');
                var ct = dataas[0];
                var dataarr = dataas[1].slice(1).split(',');
                if ((dataarr[0] != 2) && (dataarr[0] != 3) && (dataarr[0] != 7) && (dataarr[0]) !=
                    8 && (dataarr[0]) != 10) {
                    alert("all completed");
                    window.location.href = "<?php echo base_url('newcase/AutoDiary'); ?>"; //ENABLED THIS FOR AUTO DIARY
                }
                if ((dataarr[0]) == 2) {
                    alert("Petitioner details are mandatory to fill. Redirecting to page !!");
                    window.location.href = "<?php echo base_url('newcase/petitioner'); ?>";
                }
                if ((dataarr[0]) == 3) {
                    alert("Respondent details are mandatory to fill. Redirecting to page !!");
                    window.location.href = "<?php echo base_url('newcase/respondent'); ?>";
                }
                if (((dataarr[0]) == 7) && (ct != 5) && (ct != 6) && (ct != 7) && (ct != 8)) {
                    alert(
                        "Subordinate_courts details are mandatory to fill. Redirecting to page !!"
                    );
                    window.location.href = "<?php echo base_url('newcase/subordinate_court'); ?>";
                }
                if (((dataarr[0]) == 8)) {
                    alert("Documents are not uploaded. Redirecting to page !!");
                    window.location.href = "<?php echo base_url('newcase/uploadDocuments'); ?>";
                }
                if (((dataarr[0]) == 10)) {
                    alert("Court Fees not paid. Redirecting to page !!");
                    window.location.href = "<?php echo base_url('newcase/courtFee'); ?>";
                }
            },
            error: function() {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
        return false;
    });
    $("#efilpip").click(function() {
        $.ajax({
            url: "<?php echo base_url('newcase/finalSubmit/valid_efil'); ?>",
            success: function(data) {
                var dataas = data.split('?');
                var ct = dataas[0];
                var dataarr = dataas[1].slice(1).split(',');
                if ((dataarr[0] != 2) && (dataarr[0] != 3) && (dataarr[0] != 7) && (dataarr[0]) !=
                    8 && (dataarr[0]) != 10) {
                    alert("all completed");
                    window.location.href = "<?php echo base_url('newcase/finalSubmit'); ?>";
                }
                if ((dataarr[0]) == 2) {
                    alert("Petitioner details are mandatory to fill. Redirecting to page !!");
                    window.location.href = "<?php echo base_url('newcase/petitioner'); ?>";
                }
                if ((dataarr[0]) == 3) {
                    alert("Respondent details are mandatory to fill. Redirecting to page !!");
                    window.location.href = "<?php echo base_url('newcase/respondent'); ?>";
                }
                if (((dataarr[0]) == 7) && (ct != 5) && (ct != 6) && (ct != 7) && (ct != 8)) {
                    alert(
                        "Subordinate_courts details are mandatory to fill. Redirecting to page !!"
                    );
                    window.location.href = "<?php echo base_url('newcase/subordinate_court'); ?>";
                }
                if (((dataarr[0]) == 8)) {
                    alert("Documents are not uploaded. Redirecting to page !!");
                    window.location.href = "<?php echo base_url('newcase/uploadDocuments'); ?>";
                }
                if (((dataarr[0]) == 10)) {
                    alert("Court Fees not paid. Redirecting to page !!");
                    window.location.href = "<?php echo base_url('newcase/courtFee'); ?>";
                }
            },
            error: function() {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
        return false;
    });
    $("#jail").click(function() {
        $.ajax({
            url: "<?php echo base_url('jailPetition/FinalSubmit/validate'); ?>",
            success: function(data) {
                var dataarr = data.slice(1).split(',');
                if ((dataarr[0] != 1) && (dataarr[0] != 3)) {
                    window.location.href = "<?php echo base_url('jailPetition/FinalSubmit'); ?>";
                }
                if ((dataarr[0]) == 1) {
                    alert("Basic Case details are mandatory to fill. Redirecting to page !!");
                    window.location.href = "<?php echo base_url('jailPetition/BasicDetails'); ?>";
                }
                if ((dataarr[0]) == 3) {
                    alert("Earlier Court Details are mandatory to fill. Redirecting to page !!");
                    window.location.href = "<?php echo base_url('jailPetition/Subordinate_court'); ?>";
                }
            },
            error: function() {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
        return false;
    });
</script>
<script>
    function ActionToAllUserCount() {
        var AllUserCount = document.querySelector('#AllUserCount').checked;
        window.location.href =
            "<?= base_url('adminDashboard/DefaultController/ActionToAllUserCount?AllUserCount=') ?>" + AllUserCount;
    }
</script>
<script type="text/javascript">
    <?php if (isset($new_case_details[0]['subj_sub_cat_1']) && !empty($new_case_details[0]['subj_sub_cat_1']) && $new_case_details[0]['subj_sub_cat_1'] == 222) { ?>
        var selected_sub_cat = '<?php echo_data(url_encryption($new_case_details[0]['subj_sub_cat_1'] . '##' . $new_case_details[0]['court_fee_calculation_helper_flag'])); ?>';
        get_matrimonial(selected_sub_cat);


    <?php } ?>
    <?php if (isset($new_case_details[0]['sc_case_type_id']) && !empty($new_case_details[0]['sc_case_type_id']) && $new_case_details[0]['sc_case_type_id'] == 7) { ?>
        var selected_case_type = '<?php echo_data(isset($new_case_details) ? url_encryption($new_case_details[0]['sc_case_type_id'] . '"##"' . $new_case_details[0]['court_fee_calculation_helper_flag']) : ''); ?>';
        get_matrimonial_for_casetype(selected_case_type);
    <?php } ?>

    <?php if (isset($new_case_details[0]) && !empty($new_case_details[0])) { ?>
        var selected_sub_cat = '<?php echo_data(isset($new_case_details[0]['subj_sub_cat_1']) ? url_encryption($new_case_details[0]['subj_sub_cat_1']) : ''); ?>';
        get_sub_category('subj_sub_cat_1', 'subj_cat_main', selected_sub_cat);
    <?php } ?>

    //----------Get Sub Category List----------------------//
    $('#subj_cat_main').change(function() {
        var selected_sub_cat = '<?php echo_data(isset($new_case_details) ? url_encryption(@$new_case_details[0]['subj_sub_cat_1']) : ''); ?>';
        selected_sub_cat = this.value;
        get_sub_category('subj_sub_cat_1', 'subj_cat_main', selected_sub_cat);
    });

    function get_sub_category(curr_item, parent_item, selected_val) {
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
        var selected_sub_cat = this.value;
        get_matrimonial(selected_sub_cat);
    });
    $('#sc_case_type').change(function() {
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

        var spcasetype_id = $('#sc_sp_case_type_id').val();
        if (spcasetype_id == 6) {
            $('#dtsign').show();
        } else {
            $('#datesignjail').val('');
            $('#dtsign').hide();
        }
    }

    var today = new Date();
    $('.datesignjail').datepicker({
        format: "dd/mm/yyyy",
        showOtherMonths: true,
        selectOtherMonths: true,
        changeMonth: true,
        changeYear: true,
        endDate: today,
        autoclose: true
    });
    $(".date").mousedown(function() {
        $(".ui-datepicker").addClass("active");
    });
    // $(function() {
    //     $("#datesignjail").datepicker({maxDate: 0});
    // });
</script>

<script type="text/javascript">
    $(document).ready(function() {

        var spcasetype_id = $('#sc_sp_case_type_id').val();

        var dateSignatureDiv = "<?php echo @$new_case_details[0]['jail_signature_date']; ?>";
        //alert(dateSignatureDiv);
        if (dateSignatureDiv && spcasetype_id == 6) {
            $('#dtsign').show();
        } else {
            $('#dtsign').hide();
        }


        $(".filter_select_dropdown").select2().on('select2-focus', function() {
            // debugger;
            $(this).data('select2-closed', true)
        });

        $('#test').mousedown(function() {
            event.preventDefault();
            $('#tableoptions').show();
        });

        $("#Earlier_not_court_type").change(function() {
            var not_court_type = $("input[name='Earlier_not_court_type']:checked").val();
            var supremeCourtStateOptions = '';
            var supremeCourtBenchOptions = '';
            var courtTypeListOptions = '';
            if (not_court_type == 4) {
                courtTypeListOptions =
                    '<option value="4" selected="selected">Supreme Court</option><option value="1">High Court</option><option value="3" >District Court</option><option value="5" >State Agency</option>';

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
            } else {
                courtTypeListOptions =
                    '<option value="4" >Supreme Court</option><option selected="selected" value="1">High Court</option><option value="3" >District Court</option><option value="5" >State Agency</option>';
                $("#high_court_div1").show();
                $("#high_court_div2").show();
                $("#supreme_court1").hide();
                $("#supreme_court2").hide();
                $("#district_court1").hide();
                $("#district_court2").hide();
                $("#state_agency_div1").hide();
                $("#state_agency_div2").hide();
                $("#court_name").html(courtTypeListOptions);
                $("#Earlier_not_court_type").prop("checked", false);
            }
        });
        $(document).on('change', '#court_name', function() {
            var courtId = parseInt($.trim($(this).val()));
            var supremeCourtStateOptions = '';
            var supremeCourtBenchOptions = '';
            if (courtId) {
                switch (courtId) {
                    case 4:
                        supremeCourtStateOptions =
                            '<option selected="selected" value="490506">DELHI</option>';
                        supremeCourtBenchOptions =
                            '<option selected="selected" value="10000">DELHI</option>';
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
                                    $('[name="CSRF_TOKEN"]').val(result
                                        .CSRF_TOKEN_VALUE);
                                });
                            },
                            error: function() {
                                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function(result) {
                                    $('[name="CSRF_TOKEN"]').val(result
                                        .CSRF_TOKEN_VALUE);
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
                        $.ajax({
                            type: "POST",
                            data: {
                                CSRF_TOKEN: CSRF_TOKEN_VALUE
                            },
                            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_state_list'); ?>",
                            success: function(data) {
                                $('#district_court_state_name').html(data);
                                $('#district_court_state_name').select2();
                                $("#Earlier_not_court_type").prop("checked", false);
                                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                    $('[name="CSRF_TOKEN"]').val(result
                                        .CSRF_TOKEN_VALUE);
                                });
                            },
                            error: function() {
                                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                    $('[name="CSRF_TOKEN"]').val(result
                                        .CSRF_TOKEN_VALUE);
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
                        $.ajax({
                            type: "POST",
                            data: {
                                CSRF_TOKEN: CSRF_TOKEN_VALUE
                            },
                            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_icmis_state_list'); ?>",
                            success: function(data) {
                                $('#state_agency').html(data);
                                $('#state_agency').select2();
                                $("#Earlier_not_court_type").prop("checked", false);
                                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                    $('[name="CSRF_TOKEN"]').val(result
                                        .CSRF_TOKEN_VALUE);
                                });
                            },
                            error: function() {
                                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                    $('[name="CSRF_TOKEN"]').val(result
                                        .CSRF_TOKEN_VALUE);
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
            supremeCourtBenchOptions =
                '<option value="">Select Bench</option><option selected="selected" value="10000">DELHI</option>';
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


<?php if (empty($_SESSION['efiling_details']['registration_id']) || !empty($_SESSION['efiling_details']['registration_id'])) { ?>
    <script>
        $("#browser").prop('required', false);
        $('#newCaseSaveForm').show();
        $('#add_case_details').on('submit', function() {
            // alert('here1');

            // if ($('#add_case_details').valid()) {
            // alert('here1111');

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
                            var district_court_district_name = $("#district_court_district_name option:selected")
                                .val();
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
                                // alert(resArr[1]);
                                $('#dangerAlert').show();
                                $('#dangerAlert').html(resArr[1]);
                            } else if (resArr[0] == 2) {
                                // alert(resArr[1]);
                                $('#successAlert').show();
                                $('#successAlert').html(resArr[1]);
                                location.reload();

                            } else if (resArr[0] == 3) {
                                // alert(resArr[1]);
                                $('#errDiv').show();
                                $('#alertMsg').html(resArr[1]);
                                var te = resArr[1];
                                if(te.includes("cause_pet")){                                 
                                    $('#cause_pet').val('');
                                    return false;
                                }
                                if(te.includes("cause_res")){                                  
                                    $('#cause_res').val('');
                                    return false;
                                }
                                setTimeout(function() {
                                    $('#errDiv').hide();
                                }, 3000);
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
            // } else {
            // alert('here1111000');

            //     return false;
            // }
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
            var upld = myFile.split('.').pop();
            if (upld == 'pdf') {
                var draft_petition_file_browser = $.trim($("#browser").val());
                if (draft_petition_file_browser.length == 0) {
                    alert('Upload document draft petition is required*');
                    $('#draft_petition_file_browser').append('Upload document draft petition is required*');
                    return false;
                }
                $('#pet_save').hide();
                var fileName = e.target.files[0].name;
                var file_data = updateUploadFileName('browser');
                if (file_data) {
                    $('.progress').hide();
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    var fileName = $("#browser").prop("files")[0].name;
                    var filedata = $("#browser").prop("files")[0];
                    var formdata = new FormData();
                    formdata.append("pdfDocFile", filedata);
                    formdata.append("CSRF_TOKEN", CSRF_TOKEN_VALUE);
                    var doc_title = $('[name="doc_title"]').val();
                    formdata.append("doc_title", $('[name="doc_title"]').val());
                    $.ajax({
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
                            });
                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                $('#msg').show();
                                $(".form-response").html(
                                    "<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " +
                                    resArr[1] +
                                    "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            } else if (resArr[0] == 2) {
                                $('#pet_save').show();
                                $('#newCaseSaveForm').show();
                            } else if (resArr[0] == 3) {
                                $('#msg').show();
                                $(".form-response").html(
                                    "<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " +
                                    resArr[1] +
                                    "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            } else if (resArr[0] == 4) {
                                $('#msg').show();
                                $('.progress').hide();
                                $(".form-response").html(
                                    "<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " +
                                    resArr[1] +
                                    "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
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
            }

        });


        $('#add_case_details').on('submit', function() {

            $("#browser").prop('required', true);
            // if ($('#add_case_details').valid()) {
                var draft_petition_file_browser = $.trim($("#browser").val());
                if (draft_petition_file_browser.length == 0) {
                    alert('Upload document draft petition is required*');
                    $('#draft_petition_file_browser').append('Upload document draft petition is required*');
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
                            var district_court_district_name = $("#district_court_district_name option:selected")
                                .val();
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
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $('.progress').hide();
                    var subj_sub_cat_2 = $('[name="subj_sub_cat_2"]').val();
                    if (typeof subj_sub_cat_2 === 'undefined') {
                        subj_sub_cat_2 = '';
                    }
                    var subj_sub_cat_3 = $('[name="subj_sub_cat_3"]').val();
                    if (typeof subj_sub_cat_3 === 'undefined') {
                        subj_sub_cat_2 = '';
                    }
                    var filedata = $("#browser").prop("files")[0];
                    var formdata = new FormData();
                    formdata.append("pdfDocFile", filedata);
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
                    formdata.append("district_court_district_name", $('[name="district_court_district_name"]')
                        .val());
                    formdata.append("supreme_state_name", $('[name="supreme_state_name"]').val());
                    formdata.append("supreme_bench_name", $('[name="supreme_bench_name"]').val());
                    formdata.append("state_agency", $('[name="state_agency"]').val());
                    formdata.append("state_agency_name", $('[name="state_agency_name"]').val());
                    formdata.append("datesignjail", $('[name="datesignjail"]').val());
                    formdata.append("court_name", $('[name="court_name"]').val());
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>newcase/caseDetails/add_case_details",
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
                                $(".form-response").html(
                                    "<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " +
                                    resArr[1] +
                                    "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            } else if (resArr[0] == 2) {
                                $(".form-response").html(
                                    "<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " +
                                    resArr[1] +
                                    "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                $('#msg').show();
                                //window.location.href = resArr[2]; 
                                location.reload();
                            } else if (resArr[0] == 3) {
                                $('#msg').show();
                                $(".form-response").html(
                                    "<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " +
                                    resArr[1] +
                                    "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
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
            // } else {
            //     return false;
            // }
        });

        function ActionToTrash(trash_type) {
            event.preventDefault();
            var trash_type = trash_type;
            var url = "";
            if (trash_type == '') {
                swal("Cancelled", "Your imaginary file is safe :)", "error");
                return false;
            } else if (trash_type == 'UAT') {
                url = "<?php echo base_url('userActions/trash'); ?>";
            } else if (trash_type == 'SLT') {
                url = "<?php echo base_url('stage_list/trash'); ?>";
            } else if (trash_type == 'EAT') {
                url = "<?php echo base_url('userActions/trash'); ?>";
            } else {
                swal("Cancelled", "Your imaginary file is safe :)", "error");
                return false;
            }
            //    alert('trash_type'+trash_type+'url='+url);//return false;
            swal({
                    title: "Do you really want to trash this E-Filing,",
                    text: "once it will be trashed you can't restore the same.",
                    type: "warning",
                    position: "top",
                    showCancelButton: true,
                    confirmButtonColor: "green",
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    buttons: ["Make Changes", "Yes!"],
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function(isConfirm) {
                    if (isConfirm) { // submitting the form when user press yes
                        var link = document.createElement("a");
                        link.href = url;
                        link.target = "_self";
                        link.click();
                        swal({
                            title: "Deleted!",
                            text: "E-Filing has been deleted.",
                            type: "success",
                            timer: 2000
                        });

                    } else {
                        //swal({title: "Cancelled",text: "Your imaginary file is safe.",type: "error",timer: 1300});
                    }

                });
        }
        
    </script>

<?php } ?>
<script>
    function validateInput(event) {
        var initVal = $(this).val();
        const input = event.target.value;
        outputVal = initVal.replace(/[^a-zA-Z0-9\.\/@_\\,'()\s"-]/g, "").replace(/^\./, "");
        if (initVal != outputVal) {
            $(this).val(outputVal);
        }
    } 

    function ActionToTrash(trash_type) {
        event.preventDefault();
        var trash_type =trash_type;
        var url="";
        if (trash_type==''){
            swal("Cancelled", "Your imaginary file is safe :)", "error");
            return false;
        }else if (trash_type=='UAT'){
            url="<?php echo base_url('userActions/trash'); ?>";
        }else if (trash_type=='SLT'){
            url="<?php echo base_url('stage_list/trash'); ?>";
        }else if (trash_type=='EAT'){
            url="<?php echo base_url('userActions/trash'); ?>";
        }else{
            swal("Cancelled", "Your imaginary file is safe :)", "error");
            return false;
        }
    //    alert('trash_type'+trash_type+'url='+url);//return false;
        swal({
                title: "Do you really want to trash this E-Filing,",
                text: "once it will be trashed you can't restore the same.",
                type: "warning",
                position: "top",
                showCancelButton: true,
                confirmButtonColor: "green",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                buttons: ["Make Changes", "Yes!"],
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function(isConfirm){
                if (isConfirm) {  // submitting the form when user press yes
                    var link = document.createElement("a");
                    link.href = url;
                    link.target = "_self";
                    link.click();
                    swal({ title: "Deleted!",text: "E-Filing has been deleted.",type: "success",timer: 2000 });

                } else {
                    //swal({title: "Cancelled",text: "Your imaginary file is safe.",type: "error",timer: 1300});
                }

            });
    }
</script>