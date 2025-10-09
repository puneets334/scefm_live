<div class="panel panel-default">
    <div class="col-sm-12 ">
        <div class="col-md-4 col-sm-12 "></div>
        <div class="form-group">
            <label class="control-label col-md-2 col-sm-12 "> <h4 style="text-align: center;color: #31B0D5">Legal Representative :</h4></label>
            <div class="col-md-3 col-sm-12 " style="padding-top: 7px">
                <?php
                //$selectPSide = $lrs_details[0]['id'] == 'P' ? 'selected=selected' : '';
                //$selectRSide = $lrs_details[0]['id'] == 5 ? 'selected=selected' : '';
                ?>
                <select tabindex = '1' name="lrs_type_id" id="lrs_type_id" class="form-control input-sm filter_select_dropdown">
                    <option value="">Select</option>
                    <?php
                    if (count($lrs_details)) {
                        $sel_val='false';
                        foreach ($lrs_details as $lrs) {

                            $sel = ($lrs['id'] == 5) ? 'selected=selected' : '';

                            $Lrs_id=trim((string)$lrs['id']);
                            if(isset($party_details[0]['lrs_remarks_id']) && $party_details[0]['lrs_remarks_id']==$Lrs_id ){
                                $sel = 'selected=selected';
                               // $sel = ($Lrs_id == $party_details[0]['lrs_remarks_id']) ? 'selected=selected' : '';
                                $sel_val='true';
                            }elseif(isset($party_details[0]['lrs_remarks_id']) && $sel_val=='true'){
                                $sel = '';
                            }
                            ?>
                            <option <?php echo $sel; ?> value="<?php echo_data(url_encryption(trim((string) $lrs['id']))); ?>"><?php echo_data((string) $lrs['lrs_remark']); ?></option>
                            <?php
                        }
                    }

                    ?>

                </select>
            </div>
        </div>
        <div class="col-md-3 col-sm-12 "></div>
    </div>
    <div class="panel-body">
        <?php
        $attribute = array('class' => 'form-horizontal', 'name' => 'add_lr_party', 'id' => 'add_lr_party', 'autocomplete' => 'off');
        echo form_open('#', $attribute);
        ?>
        <div class="col-md-6 col-sm-6 col-xs-12"> 
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Legal Representative As <span style="color: red">*</span>:</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <?php
                            $selectPSide = $party_details[0]['p_r_type'] == 'P' ? 'selected=selected' : '';
                            $selectRSide = $party_details[0]['p_r_type'] == 'R' ? 'selected=selected' : '';
                            ?>
                            <select tabindex = '1' name="p_r_type" id="p_r_type" class="form-control input-sm filter_select_dropdown">
                                <option value="">Select</option>
                                <option value="P" <?php echo $selectPSide; ?> >Petitioner</option>
                                <option value="R" <?php echo $selectRSide; ?>>Respondent</option>                                
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Legal Representative Of <span style="color: red">*</span>:</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <select tabindex = '2' name="lr_of_party" id="lr_of_party" class="form-control input-sm filter_select_dropdown lr_of_party">
                                <option value="" title="Select">Select</option>
                                <?php
                                if (count($parties_list)) {
                                    foreach ($parties_list as $party) {
                                        $sel = ($party_details[0]['district_id'] == (string) $party->id_no) ? 'selected=selected' : '';
                                        ?>     
                                        <option <?php echo $sel; ?> value="<?php echo_data(url_encryption(trim((string) $party->id_no))); ?>"><?php echo_data((string) $party->name); ?></option>
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
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Party Is <span style="color: red">*</span>:</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <?php
                            $selectIndividual = $party_details[0]['party_type'] == 'I' ? 'selected=selected' : '';
                            $selectStateDept = $party_details[0]['party_type'] == 'D1' ? 'selected=selected' : '';
                            $selectCentralDept = $party_details[0]['party_type'] == 'D2' ? 'selected=selected' : '';
                            $selectOtherDept = $party_details[0]['party_type'] == 'D3' ? 'selected=selected' : '';
                            ?>
                            <select name="party_as" tabindex = '3' id="party_as" onchange="get_party_as(this.value)" class="form-control input-sm filter_select_dropdown">
                                <option value="I" <?php echo $selectIndividual; ?> >Individual</option>
                                <option value="D1" <?php echo $selectStateDept; ?>>State Department</option>
                                <option value="D2" <?php echo $selectCentralDept; ?>>Central Department</option>
                                <option value="D3" <?php echo $selectOtherDept; ?>>Other Organisation</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div id="indvidual_form">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Party Name
                                <span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <textarea id="party_name" tabindex = '4' name="party_name" minlength="3" maxlength="99" class="form-control input-sm sci_validation" placeholder="First Name Middle Name Last Name"  type="text" ><?php echo_data($party_details[0]['party_name']); ?></textarea>
                                    <span class="input-group-addon" data-placement="bottom"  data-toggle="popover" data-content="Party name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                        <i class="fa fa-question-circle-o" ></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row show_hide_base_on_org">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Relation <span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <?php
                                $selectSon = $party_details[0]['relation'] == 'S' ? 'selected=selected' : '';
                                $selectDaughter = $party_details[0]['relation'] == 'D' ? 'selected=selected' : '';
                                $selectWife = $party_details[0]['relation'] == 'W' ? 'selected=selected' : '';
                                $selectNotAvailable = $party_details[0]['relation'] == 'N' ? 'selected=selected' : '';
                                ?>
                                <select name="relation" id="relation" tabindex = '5' class="form-control input-sm filter_select_dropdown" style="width: 100%" >
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
                <div class="row show_hide_base_on_org">
                    <div class="col-sm-12 col-xs-12" id="rel_name">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">
                                Parent/Spouse Name <span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input id="relative_name" name="relative_name" tabindex = '6'  minlength="3" maxlength="99" placeholder="Name of Parent or Husband"  value="<?php echo_data($party_details[0]['relative_name']); ?>" class="form-control input-sm sci_validation"  type="text">
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please write name of father or mother or husband or other relative. Relative Name should be in characters ( only dot[.] and space are allowed ).">
                                        <i class="fa fa-question-circle-o" ></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row show_hide_base_on_org">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Date of Birth :</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input class="form-control has-feedback-left" id="party_dob" tabindex = '7' name="party_dob" value="<?php echo_data($party_details[0]['party_dob'] ? date('d/m/Y', strtotime($party_details[0]['party_dob'])) : ''); ?>" maxlength="10" readonly="" placeholder="DD/MM/YYYY"  type="text">
                                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Date of Birth.">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row show_hide_base_on_org">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Approximate Age <span style="color: red">*</span>:</label>
                            <div class="col-sm-2 col-md-4">
                                <div class="input-group">
                                    <?php
                                    if ($party_details[0]['party_age'] == 0 || $party_details[0]['party_age'] == '' || $party_details[0]['party_age'] == NULL) {
                                        $party_age = '';
                                    } else {
                                        $party_age = $party_details[0]['party_age'];
                                    }
                                    ?>
                                    <input id="party_age" name="party_age" maxlength="2" tabindex = '8' onkeyup="return isNumber(event)" placeholder="Age" value="<?php echo_data($party_age); ?>" class="form-control input-sm age_calculate" type="text">
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Approx. age in years only.">
                                        <i class="fa fa-question-circle-o"  ></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row show_hide_base_on_org">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-sm-5 input-sm">Gender <span style="color: red">*</span>:</label>
                            <div class="col-sm-7">
                                <?php
                                $gmchecked = $party_details[0]['gender'] == 'M' ? 'checked="checked"' : '';
                                $gfchecked = $party_details[0]['gender'] == 'F' ? 'checked="checked"' : '';
                                $gochecked = $party_details[0]['gender'] == 'O' ? 'checked="checked"' : '';
                                ?>
                                <label class="radio-inline"><input type="radio" name="party_gender" id="party_gender1" tabindex = '9' value="M" <?php echo $gmchecked; ?>>Male</label>
                                <label class="radio-inline"><input type="radio" name="party_gender" id="party_gender2" tabindex = '10' value="F" <?php echo $gfchecked; ?>>Female</label>
                                <label class="radio-inline"><input type="radio" name="party_gender" id="party_gender3" tabindex = '11' value="O" <?php echo $gochecked; ?>>Other</label>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            <div id="org_form" style="display: none">
                <div class="row" id="org_state_row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">State Name <span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <select name="org_state" id="org_state" class="form-control input-sm filter_select_dropdown org_state" tabindex = '12'>                                    
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="otherOrgState" style="display: none">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Other State Name<span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <textarea id="org_state_name" tabindex = '13' name="org_state_name" minlength="5" maxlength="99" class="form-control input-sm" placeholder="Other State Name"  type="text"><?php echo_data($party_details[0]['org_state_name']); ?></textarea>
                                    <span class="input-group-addon" data-placement="bottom"  data-toggle="popover" data-content="Other State Name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                        <i class="fa fa-question-circle-o" ></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Department Name <span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <select name="org_dept" id="org_dept" tabindex = '14' class="form-control input-sm filter_select_dropdown org_dept">                                    
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="otherOrgDept" style="display: none">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Other Department<span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <textarea id="org_dept_name" tabindex = '15' name="org_dept_name" minlength="5" maxlength="99" class="form-control input-sm" placeholder="Other State Name"  type="text"><?php echo_data($party_details[0]['org_state_name']); ?></textarea> 
                                    <span class="input-group-addon" data-placement="bottom"  data-toggle="popover" data-content="Other Department Name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                        <i class="fa fa-question-circle-o" ></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Post Name <span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <select name="org_post" id="org_post" tabindex = '16' class="form-control input-sm filter_select_dropdown org_post">                                    
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="otherOrgPost" style="display: none">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Other Post<span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <textarea id="org_post_name" tabindex = '17' name="org_post_name" minlength="5" maxlength="99" class="form-control input-sm" placeholder="Other Post Name"  type="text"><?php echo_data($party_details[0]['org_state_name']); ?></textarea> 
                                    <span class="input-group-addon" data-placement="bottom"  data-toggle="popover" data-content="Other Post Name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                        <i class="fa fa-question-circle-o" ></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Email <?php
                            if ($_SESSION['estab_details']['is_pet_email_required'] == 't') {
                                echo '<span id="party_email_req" value="1" style="color: red">*</span>';
                            }
                            ?>:</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <input id="party_email" tabindex = '18' name="party_email" placeholder="Email" value="<?php echo_data($party_details[0]['email_id']); ?>" class="form-control input-sm" type="email" minlength="6" maxlength="49">
                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter Party valid email id. (eg : abc@example.com)">
                                    <i class="fa fa-question-circle-o" ></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Mobile <?php
                            if ($_SESSION['estab_details']['is_pet_mobile_required'] == 't') {
                                echo '<span id="pet_mobile_req" value="1" style="color: red">*</span>';
                            }
                            ?> :</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <input id="party_mobile" tabindex = '19' name="party_mobile" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Mobile" value="<?php echo_data($party_details[0]['mobile_num']); ?>" class="form-control input-sm" type="text" minlength="10" maxlength="10">
                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Mobile No. should be of 10 digits only.">
                                    <i class="fa fa-question-circle-o"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> <div id="address_label_name"> Address <span style="color: red">*</span>:</div></label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <textarea name="party_address" tabindex = '20' id="party_address" placeholder="H.No.,  Street no, Colony,  Land Mark" class="form-control input-sm sci_validation" minlength="3" maxlength="249"><?php echo_data($party_details[0]['address']); ?></textarea>
                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter House No, Street No, Sector, Colony and Landmarks. Please Select District and Taluka from the below mentioned field. Do not repeat District and Taluka in Address fields and District and Taluka Fields. Address can be alphanumeric (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                    <i class="fa fa-question-circle-o" ></i>
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Country <span id="country_span"></span>:</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <select name="country_id" id="country_id" tabindex = '21' class="form-control input-sm filter_select_dropdown">
                                <option value="">Select Country</option>
                                <?php
                                if(isset($countryList) && !empty($countryList)){
                                    foreach ($countryList as $val) {
                                        if(isset($party_details[0]['country_id']) && !empty($party_details[0]['country_id'])){
                                            $sel = (url_encryption($val->id) == url_encryption($party_details[0]['country_id'])) ? "selected=selected" : '';
                                        }
                                        else{
                                            $sel = ($val->id == 96) ? "selected=selected" : '';
                                        }
                                        echo '<option '.$sel.' value="'. url_encryption(trim($val->id)).'">'. strtoupper($val->country_name).'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">PIN Code :</label>
                        <div class="col-sm-7 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <input id="party_pincode" name="party_pincode" tabindex = '22' onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" placeholder="Pincode" value="<?php echo_data($party_details[0]['pincode']); ?>" class="form-control input-sm" type="text" minlength="6" maxlength="6">
                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Pincode should be 6 digits only.">
                                    <i class="fa fa-question-circle-o"></i>
                                    <a href="https://www.indiapost.gov.in/vas/pages/findpincode.aspx" target="_blank">Pin Code Locator</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> <div id="address_label_name"> City <span id="city_span" style="color: red">*</span>:</div></label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <input id="party_city" name="party_city" tabindex = '23' placeholder="City" value="<?php echo_data($party_details[0]['city']); ?>" class="form-control input-sm sci_validation" type="text" minlength="3" maxlength="49">
                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter City name.">
                                    <i class="fa fa-question-circle-o" ></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> State <span id="state_span" style="color: red">*</span>:</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <select name="party_state" id="party_state" tabindex = '24' class="form-control input-sm filter_select_dropdown">
                                <option value="" title="Select">Select State</option>
                                <?php
                                $stateArr = array();
                                if (count($state_list)) {
                                    foreach ($state_list as $dataRes) {
                                        $sel = ($party_details[0]['state_id'] == $dataRes->cmis_state_id ) ? "selected=selected" : '';
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
            </div>
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> District <span  id="district_span" style="color: red">*</span>:</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <select name="party_district" id="party_district" tabindex = '25' class="form-control input-sm filter_select_dropdown party_district">
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
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
                <a href="<?= base_url('newcase/extra_party') ?>" class="btn btn-primary" tabindex = '30' type="button">Previous</a>
                <?php
                $p_r_type_petitioners='P'; $p_r_type_respondents='R'; $registration_id = $_SESSION['efiling_details']['registration_id'];$step=11;
                $breadcrumb_statusGet = explode(',', $_SESSION['efiling_details']['breadcrumb_status']);
                $breadcrumb_status=count($breadcrumb_statusGet);
                if (!empty($_SESSION['efiling_details']['no_of_petitioners'])){
                    $total_petitioners = get_extra_party_P_or_R($p_r_type_petitioners);
                }else{$total_petitioners=0;}
                if (!empty($_SESSION['efiling_details']['no_of_respondents'])){
                    $total_respondents = get_extra_party_P_or_R($p_r_type_respondents);
                }else{$total_respondents=0;}
                $total_petitionersRemaining=($_SESSION['efiling_details']['no_of_petitioners'])-($total_petitioners);
                $total_respondentsRemaining=($_SESSION['efiling_details']['no_of_respondents'])-($total_respondents);
                $br="<br/>";
                $extra_party_sms='Your limit of number of '.$_SESSION['efiling_details']['no_of_petitioners'].' petitioner(s) Remaining number of '.$total_petitionersRemaining.' add petitioner(s), and '.$br.'Your limit of number of '.$_SESSION['efiling_details']['no_of_respondents'].' respondents(s) Remaining number of '.$total_respondentsRemaining.' add respondents(s), if you want to add more petitioner(s) or respondent(s) first update number of petitioner or respondent in extra party tab.';
                $extra_party_sms='You have not entered all party details, please enter all party details to proceed to next tab.';
                if ($step >= $breadcrumb_status) {
                    //if ($_SESSION['efiling_details']['no_of_petitioners'] == $total_petitioners && $_SESSION['efiling_details']['no_of_respondents'] == $total_respondents) {
                    if ($_SESSION['efiling_details']['no_of_petitioners'] <= $total_petitioners && $_SESSION['efiling_details']['no_of_respondents'] <= $total_respondents) {
                        $newcase_lr_party = base_url('newcase/actSections');
                        $extra_party_check = '';
                    } else {
                        $newcase_lr_party = base_url('newcase/lr_party') . '#';
                        $extra_party_check = 'id="extra_party_sms"';
                    }
                }else{
                    $newcase_lr_party = base_url('newcase/actSections');
                    $extra_party_check = '';
                }
                if (isset($curr_party_id) && !empty($curr_party_id)) { ?>
                    <a href="<?= base_url('newcase/lr_party') ?>" class="btn btn-danger" tabindex = '29' type="button">CANCEL</a>
                    <input type="submit" class="btn btn-success" id="res_save" tabindex = '28' value="UPDATE">
                    <a href="<?= $newcase_lr_party ?>" <?= $extra_party_check; ?> class="btn btn-primary" tabindex = '27' type="button">Next</a>
                <?php } else { ?>
                    <input type="submit" class="btn btn-success" id="res_save" tabindex = '26' value="SAVE">
                <?php /*  if(!empty($lr_parties_list)){*/?><!--
                    <a href="<?/*= $newcase_lr_party */?>" <?/*= $extra_party_check; */?> class="btn btn-primary" tabindex = '28' type="button">Next</a>

                --><?php /*} */?>
                    <a href="<?= $newcase_lr_party ?>" <?= $extra_party_check; ?> class="btn btn-primary" tabindex = '28' type="button">Next</a>
                <?php }?>

            </div>
        </div>
        <input type="hidden" name="lr_total" id="lr_total" value="<?php echo $lr_total;?>" />
        <?php echo form_close();
        ?>  
    </div>
</div>

<?php $this->load->view('newcase/lr_party_list_view'); ?>
<script>
    $(".sci_validation").keyup(function(){
        var initVal = $(this).val();
        outputVal = initVal.replace(/[^a-z,^A-Z^0-9\.@,/'()\s"\-]/g,"").replace(/^\./,"");
        //validate_alpha_numeric_single_double_quotes_bracket_with_special_characters
        if (initVal != outputVal) {
            $(this).val(outputVal);
        }
    });

</script>
<script>
    $(document).ready(function(){
        $("#extra_party_sms").click(function(){
            var resArr = '<?=$extra_party_sms;?>';
            // alert(resArr);
            $('#msg').show();
            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
        });
    });
</script>
<script type="text/javascript">
    var state_Arr = '<?php echo json_encode($stateArr)?>';
    //----------Get District List----------------------//
    $('#party_state').change(function () {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#party_district').val('');

        var get_state_id = $(this).val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
            url: "<?php echo base_url('newcase/Ajaxcalls/get_districts'); ?>",
            success: function (data)
            {
                $('.party_district').html(data);
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

    });

<?php if (isset($party_details[0]) && !empty($party_details[0])) { ?>

        var selected_parent_party = '<?php echo_data(url_encryption($party_details[0]['parent_id'])); ?>';
        get_parent_parties_list('<?php echo_data($party_details[0]['p_r_type']); ?>', '<?php echo_data(url_encryption($party_details[0]['parent_id'])); ?>');

<?php } ?>


    //----------Get Parties List----------------------//
    $('#p_r_type').change(function () {

        var get_p_r_type = $(this).val();

        get_parent_parties_list(get_p_r_type);

    });

    function get_parent_parties_list(p_r_type, selected_parent_party) {


        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#lr_of_party').val('');

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, p_r_type: p_r_type, selected_parent_party: selected_parent_party},
            url: "<?php echo base_url('newcase/Ajaxcalls/get_case_parties_list'); ?>",
            success: function (data)
            {
                $('.lr_of_party').html(data);
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

    }

    //---------- Hide and show Individual and Org form----------------------//
    $(document).ready(function () {
        var party_as_sel = '<?php echo $party_details[0]['party_type']; ?>';

        if (party_as_sel != '') {
            get_party_as(party_as_sel);//--call to selected
        }
    });

    function get_party_as(value) {

        var party_as = value;
        if (party_as == 'I') {
            $('#indvidual_form').show();
            $('#org_form').hide();
            $('#org_state_row').show();
            $('#org_state').val('');
            $('#org_dept').val('');
            $('#org_post').val('');
            $('#otherOrgState').hide();
            $('#otherOrgDept').hide();
            $('#otherOrgPost').hide();
        } else {

            get_departments(party_as);
            get_posts();

            if (party_as == 'D3') {
                $('#indvidual_form').hide();
                $('#org_form').show();
                $('#org_state_row').hide();
                $('#otherOrgState').hide();
                $('#party_name').val('');
                $('#relation').val('');
                $('#relative_name').val('');
                $('#party_dob').val('');
                $('#party_age').val('');
                /*$('#party_gender1').val('');            
                 $('#party_gender2').val('');            
                 $('#party_gender3').val('');*/
            } else {
                $('#indvidual_form').hide();
                $('#org_form').show();
                $('#org_state_row').show();
                $('#party_name').val('');
                $('#relation').val('');
                $('#relative_name').val('');
                $('#party_dob').val('');
                $('#party_age').val('');

                /*$('#party_gender1').val('');            
                 $('#party_gender2').val('');            
                 $('#party_gender3').val('');            */
            }
        }
    }

    //---------- Organisation State Name----------------------//
    $('#org_state').change(function () {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var org_state = $(this).val();
        if (org_state == '<?php echo url_encryption(0); ?>') {
            $('#otherOrgState').show();
            $('#otherOrgDept').show();
            $('#otherOrgPost').show();
            $('#org_dept').val('<?php echo url_encryption(0); ?>');
            $('#org_post').val('<?php echo url_encryption(0); ?>');
        } else {
            $('#otherOrgState').hide();
            $('#otherOrgDept').hide();
            $('#otherOrgPost').hide();
            $('#org_dept').val('');
            $('#org_post').val('');
        }
    });

    //---------- Organisation Department Name----------------------//
    $('#org_dept').change(function () {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var org_dept = $(this).val();
        if (org_dept == '<?php echo url_encryption(0); ?>') {
            $('#otherOrgDept').show();
            $('#otherOrgPost').show();
            $('#org_post').val('<?php echo url_encryption(0); ?>');
        } else {
            $('#otherOrgDept').hide();
            $('#otherOrgPost').hide();
            $('#org_post').val('');
        }
    });


    //---------- Organisation Post Name----------------------//
    $('#org_post').change(function () {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var org_post = $(this).val();
        if (org_post == '<?php echo url_decryption(0); ?>') {
            $('#otherOrgPost').show();

        } else {
            $('#otherOrgPost').hide();
        }
    });


    function get_departments(party_is) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var selected_org_st_id = '<?php echo url_encryption($party_details[0]['org_state_id']); ?>';
        var selected_dept_id = '<?php echo url_encryption($party_details[0]['org_dept_id']); ?>';

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, party_is: party_is, selected_org_st_id: selected_org_st_id, selected_dept_id: selected_dept_id},
            url: "<?php echo base_url('newcase/Ajaxcalls/get_org_departments'); ?>",
            success: function (data)
            {
                $('.filter_select_dropdown').select2();
                var response = data.split('$$$$$');
                $('.org_state').html(response[0]);
                $('.org_dept').html(response[1]);
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
    }

    function get_posts() {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var selected_post_id = '<?php echo url_encryption($party_details[0]['org_post_id']); ?>';

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, selected_post_id: selected_post_id},
            url: "<?php echo base_url('newcase/Ajaxcalls/get_org_posts'); ?>",
            success: function (data)
            {
                $('.org_post').html(data);
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

    }

</script>


<script type="text/javascript">

    $(document).ready(function () {
        $('#add_lr_party').on('submit', function () {
            if ($('#add_lr_party').valid()) {
                var lr_total = $("#lr_total").val();
                // alert(lr_total);
                // return false;
                var form_data = $(this).serialize();
                form_data +='&lr_total='+lr_total;
                var lrs_id=$('#lrs_type_id').val();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url('newcase/lr_party/add_lr_party/' . $this->uri->segment(3)); ?>',
                    data: form_data + '&lrs_id=' + lrs_id,
                    async: false,
                    beforeSend: function () {
                        $('#pet_save').val('Please wait...');
                        $('#pet_save').prop('disabled', true);
                    },
                    success: function (data) {
                        $('#pet_save').val('SAVE');
                        $('#pet_save').prop('disabled', false);
                        var resArr = data.split('@@@');
                        if (resArr[0] == 1) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        } else if (resArr[0] == 2) {
                            $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            $('#msg').show();
                            window.location.href = resArr[2];

                        } else if (resArr[0] == 3) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        }
                        $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function () {
                        $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
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