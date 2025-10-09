<div class="panel panel-default">
    <h4 style="text-align: center;color: #31B0D5">Extra Party Information </h4>
    <div class="panel-body">
        <?php
        if(!empty($this->session->flashdata('message'))){
            echo $this->session->flashdata('message');
        }
        if(!$_SESSION['MSG']){
            echo $_SESSION['MSG'];
        }
        $attribute = array('class' => 'form-horizontal form-label-left', 'name' => 'add_extra_party11', 'id' => 'add_extra_party11', 'autocomplete' => 'off');
        echo form_open('#', $attribute);

        ?>
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-sm-4 input-sm"> Type <span style="color: red">*</span> :</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">

                        <?php
                        if ($party_details[0]['type'] == '1') {
                            $checked_1 = 'checked="checked"';
                            $type_name = ucfirst($case_type_pet_title);
                            $pet_selected = true;
                        } elseif ($party_details[0]['type'] == '2') {
                            $checked_2 = 'checked="checked"';
                            $type_name = ucfirst($case_type_res_title);
                            $pet_selected = false;
                        } else {
                            $pet_selected = true;
                            $type_name = ucfirst($case_type_pet_title);
                        }
                        ?>
                        <input type="hidden" id="tbl_row_id" value="<?php echo url_encryption(trim($party_details[0]['id']) . '$$party_id'); ?>" name="tbl_row_id">
                        <?php if ($party_details[0]['type'] == '1') { ?>
                            <label class="radio-inline"><input type="radio" id="complainant_type" name="complainant_accused_type" onchange="respondentProforma(this.value);" value="1" <?php echo $checked_1; ?> required=""  maxlength="1"> <?php echo ucfirst($case_type_pet_title); ?> </label>
                            <label class="radio-inline"><input type="radio" id="accused_type" name="complainant_accused_type" onchange="respondentProforma(this.value);"  value="2" <?php echo $checked_2; ?>   maxlength="1"> <?php echo ucfirst($case_type_res_title); ?> </label>
                        <?php } elseif ($party_details[0]['type'] == '2') { ?>
                            <label class="radio-inline"><input type="radio" id="complainant_type" name="complainant_accused_type" onchange="respondentProforma(this.value);" value="1" <?php echo $checked_1; ?> required=""  maxlength="1"> <?php echo ucfirst($case_type_pet_title); ?> </label>
                            <label class="radio-inline"><input type="radio" id="accused_type" name="complainant_accused_type" onchange="respondentProforma(this.value);"  value="2" <?php echo $checked_2; ?>   maxlength="1"> <?php echo ucfirst($case_type_res_title); ?> </label>
                        <?php } else { ?>
                            <label class="radio-inline"><input type="radio" id="complainant_type" name="complainant_accused_type" onchange="respondentProforma(this.value);" value="1" <?php echo $checked_1; ?> required=""  maxlength="1" checked> <?php echo ucfirst($case_type_pet_title); ?> </label>
                            <label class="radio-inline"><input type="radio" id="accused_type" name="complainant_accused_type" onchange="respondentProforma(this.value);"  value="2" <?php echo $checked_2; ?>   maxlength="1"> <?php echo ucfirst($case_type_res_title); ?> </label>
                        <?php } ?>

<!--                        <label class="radio-inline"><input type="radio" id="complainant_type" name="complainant_accused_type" onchange="respondentProforma(this.value);" value="1"  required=""  maxlength="1"> --><?php //echo ucfirst($case_type_pet_title); ?><!-- </label>-->
<!--                        <label class="radio-inline"><input type="radio" id="accused_type" name="complainant_accused_type" onchange="respondentProforma(this.value);"  value="2"    maxlength="1"> --><?php //echo ucfirst($case_type_res_title); ?><!-- </label>-->

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Extra Party  Is :<span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <?php
                                $selectIndividual = $party_details[0]['orgid'] == 'I' ? 'selected=selected' : '';
                                $selectStateDept = $party_details[0]['orgid'] == 'D1' ? 'selected=selected' : '';
                                $selectCentralDept = $party_details[0]['orgid'] == 'D2' ? 'selected=selected' : '';
                                $selectOtherDept = $party_details[0]['orgid'] == 'D3' ? 'selected=selected' : '';
                                $showHideIndividual = 'display:block';
                                $showHideOtherIndividual = 'display:none';
//                                if(isset($selectIndividual) && !empty($selectIndividual) && !empty($party_details[0]['state_id'])){
//                                    $showHideIndividual = 'display:block';
//                                }
//                                else if(!empty($selectStateDept) || !empty($selectCentralDept) || !empty($selectOtherDept) && (!empty($party_details[0]['state_id']))){
//                                    $showHideOtherIndividual = 'display:block';
//                                }

                                ?>
                                <select tabindex = '1' name="party_is" id="party_is" onchange="get_caveator_as(this.value)" class="form-control input-sm filter_select_dropdown">
                                    <option <?php echo  (!empty($party_details[0]['orgid']) && $party_details[0]['orgid'] == 'I') ? 'selected="selected"' : ''?> value="I"  >Individual</option>
                                    <option <?php echo  (!empty($party_details[0]['orgid']) && $party_details[0]['orgid'] == 'D1') ? 'selected="selected"' : ''?>  value="D1" >State Department</option>
                                    <option <?php echo  (!empty($party_details[0]['orgid']) && $party_details[0]['orgid'] == 'D2') ? 'selected="selected"' : ''?>  value="D2" >Central Department</option>
                                    <option <?php echo  (!empty($party_details[0]['orgid']) && $party_details[0]['orgid'] == 'D3') ? 'selected="selected"' : ''?>  value="D3" >Other Organisation</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="indvidual_form" style="<?php echo $showHideIndividual;?>">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Extra Party  Name
                                    <span style="color: red">*</span></label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <textarea  tabindex = "2" id="pet_complainant" name="pet_complainant" minlength="3" maxlength="99" class="form-control input-sm sci_validation" placeholder="First Name Middle Name Last Name"  type="text"><?php echo (!empty($party_details[0]['name'])) ? trim($party_details[0]['name']) : ''  ?></textarea>
                                        <span class="input-group-addon" data-placement="bottom"  data-toggle="popover" data-content="Caveator name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
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
                                    <select tabindex = '3' name="pet_rel_flag" id="pet_rel_flag" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                                        <option value="" >Select Relation</option>
                                        <option  <?php  echo  (!empty($party_details[0]['father_flag']) && trim(strtoupper($party_details[0]['father_flag'])) == 'S') ? 'selected="selected"' : ''?> value="S">Son Of</option>
                                        <option <?php echo (!empty($party_details[0]['father_flag']) && trim(strtoupper($party_details[0]['father_flag'])) == 'D') ? 'selected="selected"' : ''?>  value="D">Daughter Of</option>
                                        <option  <?php echo (!empty($party_details[0]['father_flag']) && trim(strtoupper($party_details[0]['father_flag'])) == 'W') ? 'selected="selected"' : ''?>  value="W">Spouse Of</option>
                                        <option  <?php echo  (!empty($party_details[0]['father_flag']) && trim(strtoupper($party_details[0]['father_flag'])) == 'N') ? 'selected="selected"' : ''?>  value="N">Not Available</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row show_hide_base_on_org">
                        <div class="col-sm-12 col-xs-12" id="rel_name">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">
                                    Relative Name <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input tabindex = "4" id="relative_name" value="<?php echo (!empty($party_details[0]['father_name'])) ? trim($party_details[0]['father_name']) : ''  ?>" name="relative_name"  minlength="3" maxlength="99" placeholder="Name of Parent or Husband"   class="form-control input-sm sci_validation"  type="text">
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
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Date of Birth:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input tabindex = '5' value="<?php echo (!empty($party_details[0]['pet_dob'])) ? date('d/m/Y',strtotime(trim($party_details[0]['pet_dob']))) : ''  ?>" class="form-control has-feedback-left" id="pet_dob" name="pet_dob" maxlength="10" readonly="" placeholder="DD/MM/YYYY"  type="text">
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
                                        <input id="pet_age" tabindex = '6' value="<?php echo (!empty($party_details[0]['pet_age'])) ? trim($party_details[0]['pet_age']) : ''  ?>" name="pet_age" maxlength="2" onkeyup="return isNumber(event)" placeholder="Age"  class="form-control input-sm age_calculate" type="text">
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
                                    <label class="radio-inline"><input  <?php echo  (!empty($party_details[0]['pet_sex']) && trim($party_details[0]['pet_sex']) == 'M') ? 'checked="checked"' : ''?>  tabindex = '7' type="radio" name="pet_gender" id="pet_gender1" value="M">Male</label>
                                    <label class="radio-inline"><input <?php echo  (!empty($party_details[0]['pet_sex']) && trim($party_details[0]['pet_sex']) == 'F') ? 'checked="checked"' : ''?> tabindex = '8' type="radio" name="pet_gender" id="pet_gender2" value="F" >Female</label>
                                    <label class="radio-inline"><input <?php echo  (!empty($party_details[0]['pet_sex']) && trim($party_details[0]['pet_sex']) == 'O') ? 'checked="checked"' : ''?> tabindex = '9' type="radio" name="pet_gender" id="pet_gender3" value="O" >Other</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="org_form"  style="<?php echo $showHideOtherIndividual;?>">
                    <div class="row" id="org_state_row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">State Name <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <select tabindex = '10' name="org_state" id="org_state" class="form-control input-sm filter_select_dropdown org_state">
                                        <?php
                                        $select_organization ='';
                                        if(isset($state_list) && !empty($state_list)){
                                            foreach ($state_list as $k=>$v){
                                                if(!empty($party_details[0]['state_id']) && trim($party_details[0]['state_id']) == $v->cmis_state_id){
                                                    $select_organization = 'selected="selected"';
                                                }
                                                else{
                                                    $select_organization ='';
                                                }
                                                echo '<option '.$select_organization.' value="'.url_encryption($v->cmis_state_id).'">'.strtoupper($v->agency_state).'</option>';
                                            }
                                        }
                                        ?>
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
                                        <textarea tabindex = '11' id="org_state_name" name="org_state_name" minlength="5" maxlength="99" class="form-control input-sm" placeholder="Other State Name"  type="text"></textarea>
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
                                    <select name="org_dept" tabindex = '12' id="org_dept" class="form-control input-sm filter_select_dropdown org_dept">
                                        <?php
                                        $dept_sel ='';
                                        if(isset($dept_list) && !empty($dept_list)){
                                            foreach ($dept_list as $k=>$v){
                                                if(!empty($party_details[0]['extra_party_org_dept']) && trim($party_details[0]['extra_party_org_dept']) == $v->deptcode){
                                                    $dept_sel = 'selected="selected"';
                                                }
                                                else{
                                                    $dept_sel ='';
                                                }
                                                echo '<option '.$dept_sel.' value="'.url_encryption($v->deptcode).'">'.strtoupper($v->deptname).'</option>';
                                            }
                                        }
                                        ?>
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
                                        <textarea id="org_dept_name"  tabindex = '13' name="org_dept_name" minlength="5" maxlength="99" class="form-control input-sm" placeholder="Other State Name"  type="text"></textarea>
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
                                    <select name="org_post" id="org_post" tabindex = '14' class="form-control input-sm filter_select_dropdown org_post">
                                        <?php
                                        $post_sel ='';
                                        if(isset($post_list) && !empty($post_list)){
                                            foreach ($post_list as $k=>$v){
                                                if(!empty($party_details[0]['extra_party_org_post']) && trim($party_details[0]['extra_party_org_post']) == $v->authcode){
                                                    $post_sel = 'selected="selected"';
                                                }
                                                else{
                                                    $post_sel ='';
                                                }
                                                echo '<option '.$post_sel.' value="'.url_encryption($v->authcode).'">'.strtoupper($v->authdesc).'</option>';
                                            }
                                        }
                                        ?>

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
                                        <textarea id="org_post_name" name="org_post_name" tabindex = '15' minlength="5" maxlength="99" class="form-control input-sm" placeholder="Other Post Name"  type="text"></textarea>
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
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Email </label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input id="pet_email"  value="<?php echo (!empty($party_details[0]['pet_email'])) ? trim($party_details[0]['pet_email']) : ''  ?>" name="pet_email" placeholder="Email" tabindex = '16'  class="form-control input-sm" type="email" minlength="6" maxlength="49">
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter caveatee valid email id. (eg : abc@example.com)">
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
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Mobile:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input id="pet_mobile" name="pet_mobile" value="<?php echo (!empty($party_details[0]['pet_mobile'])) ? trim($party_details[0]['pet_mobile']) : ''  ?>"  tabindex = '17' onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Mobile"  class="form-control input-sm" type="text" minlength="10" maxlength="10">
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
                                    <textarea tabindex = '18' name="pet_address" id="pet_address" placeholder="H.No.,  Street no, Colony,  Land Mark" class="form-control input-sm sci_validation" minlength="3" maxlength="250" required><?php echo (!empty($party_details[0]['address'])) ? trim($party_details[0]['address']) : ''  ?></textarea>
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
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Pin Code :</label>
                            <div class="col-sm-7 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input id="party_pincode" value="<?php echo (!empty($party_details[0]['pincode'])) ? trim($party_details[0]['pincode']) : ''  ?>" name="party_pincode" tabindex = '19' onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Pincode"  class="form-control input-sm" type="text" minlength="6" maxlength="6">
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Pincode should be 6 digits only.">
                                    <i class="fa fa-question-circle-o"></i>
                                    <a href="https://www.indiapost.gov.in/vas/pages/findpincode.aspx" target="_blank">Locate Pin</a>
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> <div id="address_label_name"> City <span style="color: red">*</span>:</div></label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input id="party_city" tabindex = '20' value="<?php echo (!empty($party_details[0]['pet_city'])) ? trim($party_details[0]['pet_city']) : ''  ?>" name="party_city" placeholder="City"  class="form-control input-sm sci_validation" type="text" minlength="3" maxlength="49" required>
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
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> State <span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <select name="party_state" id="party_state" tabindex = '21' class="form-control input-sm filter_select_dropdown" required>
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
                                            $tempArr['state_name'] = strtoupper($dataRes->agency_state);
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
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> District <span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <select name="party_district" id="party_district" tabindex = '22' class="form-control input-sm filter_select_dropdown party_district" required>
                                    <option value="" title="Select">Select District</option>
                                    <?php
                                   if(!empty($district_list) && isset($district_list)) {
                                        foreach ($district_list as $dataRes) {
                                            $sel = ($party_details[0]['dist_code'] == $dataRes->id_no) ? 'selected=selected' : '';
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
        </div>
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Other Information :</label>
                    <div class="col-sm-1">
                        <?php
                        $otherInfoDiv = 'display: none;';
                        if(!empty($party_details[0]['other_info_flag']) && trim($party_details[0]['other_info_flag']) == 'Y'){
                            $otherInfoDiv = 'display: block;';
                        }
                        $performaresflagDiv = 'display: none;';
                        if(!empty($party_details[0]['performaresflag']) && trim($party_details[0]['performaresflag']) == 'on'){
                            $performaresflagDiv = 'display: block;';
                        }
                        ?>
                        <input  <?php echo  (!empty($party_details[0]['other_info_flag']) && trim($party_details[0]['other_info_flag']) == 'Y') ? 'checked="checked"' : ''?> id="other_information" tabindex="23" name="other_information" onchange="extraInfoDivShow(this);"  class="form-control input-sm" type="checkbox">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12" id="proforma_show" style="<?php echo $performaresflagDiv;?>">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Proforma Respondent :</label>
                    <div class="col-sm-1">
                        <input <?php echo  (!empty($party_details[0]['performaresflag']) && trim($party_details[0]['performaresflag']) == 'on') ? 'checked="checked"' : ''?> id="proforma_repo" tabindex="24" name="proforma_repo" class="form-control input-sm" type="checkbox">
                    </div>
                </div>
            </div>
        </div>

        <div id="extra_info_show" style="<?php echo $otherInfoDiv;?>">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <div class="row" >
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Passport No :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input value="<?php echo (!empty($party_details[0]['passportno'])) ? trim($party_details[0]['passportno']) : ''  ?>"  id="o_passport_no" tabindex="25" name="o_passport_no" placeholder="Passport No"  maxlength="9"  class="form-control input-sm" style="text-transform: uppercase;" type="text">
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Passport No.">
                                            <i class="fa fa-question-circle-o" ></i>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">PAN No  :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input id="o_pancard_no" tabindex="26" name="o_pancard_no" placeholder="PAN No" value="<?php echo (!empty($party_details[0]['panno'])) ? trim($party_details[0]['panno']) : ''  ?>" maxlength="10" class="form-control input-sm" style="text-transform: uppercase;"  type="text">
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Pan No.">
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
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Fax No :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input id="o_fax_no" tabindex="27" name="o_fax_no" onkeyup="return isNumber(event)" value="<?php echo (!empty($party_details[0]['pet_fax'])) ? trim($party_details[0]['pet_fax']) : ''  ?>"  maxlength="12" placeholder="Fax No" class="form-control input-sm add_dash" type="text">
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Fax No.">
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
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Phone No :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input id="o_phone_no" tabindex="28" name="o_phone_no" onkeyup="return isNumber(event)" value="<?php echo (!empty($party_details[0]['pet_phone'])) ? trim($party_details[0]['pet_phone']) : ''  ?>"  maxlength="12" placeholder="Phone No" class="form-control input-sm add_dash" type="text">
                                        <span class="input-group-addon">
                                            <i class="fa fa-question-circle-o" data-placement="bottom" data-toggle="popover" data-content="Phone No. should be numeric."></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Occupation :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input id="o_occupation" tabindex="29" name="o_occupation" onkeyup="return isLetter(event)" value="<?php echo (!empty($party_details[0]['pet_occu'])) ? trim($party_details[0]['pet_occu']) : ''  ?>"    placeholder="Occupation" class="form-control input-sm" type="text">
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Occupation should be in character only.">
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
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Country :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input id="o_country" tabindex="30" name="o_country"  maxlength="20" placeholder="Country" value="<?php echo (!empty($party_details[0]['country'])) ? trim($party_details[0]['country']) : ''  ?>"  class="form-control input-sm" type="text">
                                        <span class="input-group-addon">
                                            <i class="fa fa-question-circle-o" data-placement="bottom" data-toggle="popover" data-content="Country Name should be in charcters only."></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Nationality  :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input id="o_nationality"  tabindex="31" name="o_nationality" maxlength="20" value="<?php echo (!empty($party_details[0]['pet_nationality'])) ? trim($party_details[0]['pet_nationality']) : ''  ?>"  placeholder="Nationality" class="form-control input-sm"  type="text">
                                        <span class="input-group-addon">
                                            <i class="fa fa-question-circle-o" data-placement="bottom" data-toggle="popover" data-content="Nationality should be in charcters only."></i>
                                        </span>
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
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Alternate Address  :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <textarea name="o_alt_address" tabindex="32"  cols="1" rows="1" id="o_alt_address" placeholder="H.No.,  Street no,  City " class="form-control input-sm sci_validation" minlength="3" maxlength="250"><?php echo (!empty($party_details[0]['altaddress'])) ? trim($party_details[0]['altaddress']) : ''  ?></textarea>
                                        <span class="input-group-addon">
                                            <i class="fa fa-question-circle-o" data-placement="bottom" data-toggle="popover" data-content="Please enter House No, Street No, Sector, Colony and Landmarks. Please Select District and Taluka from the below mentioned field. Do not repeat District and Taluka in Address fields and District and Taluka Fields. Address can be alphanumeric (only ., : / are allowed )."></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Pin Code :</label>
                                <div class="col-sm-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input id="alt_party_pincode" name="alt_party_pincode" tabindex = "33" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Pincode" value="<?php echo (!empty($party_details[0]['alt_pincode'])) ? trim($party_details[0]['alt_pincode']) : ''  ?>"  class="form-control input-sm" type="text" minlength="6" maxlength="6">
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
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> <div id="address_label_name"> City :</div></label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input id="alt_party_city" tabindex = '34' name="alt_party_city" placeholder="City" value="<?php echo (!empty($party_details[0]['alt_city'])) ? trim($party_details[0]['alt_city']) : ''  ?>" class="form-control input-sm sci_validation" type="text" minlength="3" maxlength="49">
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
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> State:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <select name="alt_party_state" id="alt_party_state" tabindex = '35' class="form-control input-sm filter_select_dropdown" >
                                        <option value="" title="Select">Select State</option>
                                        <?php
                                        if (count($state_list)) {
                                            foreach ($state_list as $dataRes) {
                                                $sel = ($party_details[0]['altstate_id'] == $dataRes->cmis_state_id ) ? "selected=selected" : '';
                                                ?>
                                                <option <?php echo $sel; ?> value="<?php echo_data(url_encryption(trim($dataRes->cmis_state_id))); ?>"><?php echo_data(strtoupper($dataRes->agency_state)); ?> </option>;
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
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> District :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <select name="alt_party_district" id="alt_party_district" tabindex = '36' class="form-control input-sm filter_select_dropdown">
                                        <option value="" title="Select">Select District</option>
                                        <?php
                                        if (!empty($alt_district_list) && isset($alt_district_list)) {
                                            foreach ($alt_district_list as $dataRes) {
                                                $sel = ($party_details[0]['altdist_code'] == $dataRes->id_no) ? 'selected=selected' : '';
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
            </div>
        </div>
        <div class="form-group">
<!--            <div style="color: red; font-size: 13px; text-align: center">-->
<!--                <b>Note</b>: 1. Once you submit this efile, can not modify extra party.</br>-->
<!--                2. It is recommended to fill in Extra Party details though not mandatory.-->
<!--                </br>-->
<!---->
<!--            </div><br>-->
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                <a href="<?= base_url('caveat/caveatee') ?>" class="btn btn-primary" type="button">Previous</a>
                <?php
                if(isset($party_details[0]['id']) && !empty($party_details[0]['id'])){
                    echo '<input type="submit" class="btn btn-success" id="extra_party_save" value="UPDATE">';
                }
                else{
                    echo '<input type="submit" class="btn btn-success" id="extra_party_save" value="SAVE">';
                }
                ?>
                <?php if ($this->uri->segment(3)) { ?>
                    <a href="<?= base_url('caveat/extra_party') ?>" class="btn btn-info" type="button">Cancel</a>
                <?php }
                /*if(isset($extra_party_data) && !empty($extra_party_data)){
                    */?><!--
                    <a href="<?/*= base_url('caveat/subordinate_court') */?>" class="btn btn-primary" type="button">Next</a>
                --><?php
/*                }*/

                ?>
                <a href="<?= base_url('caveat/subordinate_court') ?>" class="btn btn-primary" type="button">Next</a>

            </div>
        </div>
        <?php echo form_close(); ?> 
        <div class="del-response"></div>
        <?php if (isset($extra_party_data) && !empty($extra_party_data)) { ?>
            <br>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">
                        <?php
                        $attribute = array('class' => 'form-horizontal form-label-left', 'name' => 'add_shuffle', 'id' => 'add_shuffle', 'autocomplete' => 'off');
                        echo form_open(base_url('caveat/reshuffled_extraparty'), $attribute);
                        ?>
                        <div class="col-md-12 col-sm-12 col-xs-12">

                            <div class="col-md-3 col-sm-12 col-xs-12">

                                <div class="form-group">
                                    <select name="shuffled_party" id="shuffled_party"  class="form-control input-sm filter_select_dropdown" style="width: 100%">
                                        <option>Select Moving Party</option>
                                        <?php foreach ($extra_party_data as $dataRes) { ?>
                                            <option value="<?php echo url_encryption($dataRes->party_id . '##' . $dataRes->party_no . "##" . $dataRes->type . "##" . $dataRes->id); ?>"><?php echo strtoupper($dataRes->name); ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="radio-inline"><input type="radio" id="position" name="position"  value="<?php echo url_encryption("1"); ?>"    maxlength="1">Just Before</label>
                                    <label class="radio-inline"><input type="radio" id="position" name="position"  value="<?php echo url_encryption("2"); ?>"   maxlength="1">Just After</label>
                                </div>  
                            </div>

                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <select name="shuffled_on" id="shuffled_on" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                                        <option>Select Place before/After </option>
                                        <?php foreach ($extra_party_data as $dataRes) { ?>
                                            <option value="<?php echo url_encryption($dataRes->party_id . '##' . $dataRes->party_no . "##" . $dataRes->type . "##" . $dataRes->id); ?>"><?php echo strtoupper($dataRes->name); ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-success" id="reshuffled" name="reshuffled" value="Move">
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>

                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr class="success">
                                    <th>#</th>
                                    <th width="15%">Type</th>
                                    <th width="30%">Name</th>
                                    <th>Age/D.O.B</th>
                                    <th>Address / Contact Details</th>
                                    <?php
                                    if ($_SESSION['efiling_details']['stage_id'] == Draft_Stage || $_SESSION['efiling_details']['stage_id'] == Initial_Defected_Stage) {
                                        ?>
                                        <th width="15%">Action</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($extra_party_data as $dataRes) {
                                    $relation_name ='';
                                    if(trim($dataRes->father_flag) == 'S'){
                                        $relation_name ='Son Of';
                                    }
                                    else if(trim($dataRes->father_flag) == 'D'){
                                        $relation_name ='Daughter Of';
                                    }
                                    else if(trim($dataRes->father_flag) == 'W'){
                                        $relation_name ='Spouse Of';
                                    }
                                    else if(trim($dataRes->father_flag) == 'N'){
                                        $relation_name ='Not Available';
                                    }
                                    else{
                                        $relation_name ='';
                                    }
                                    if ($dataRes->father_name != '') {
                                        $relation = ' <br> <strong>Relative</strong>: ' . strtoupper(escape_data($dataRes->father_name)) . "  (" . escape_data($relation_name) . ")";
                                    } else {
                                        $relation = '';
                                    }

                                    if ($dataRes->extra_not_in_list_org == 't') {
                                        $not_in_list_org_name = ' <br> <strong>Organisation </strong>: ' . strtoupper(escape_data($dataRes->extra_party_org_name));
                                    } else {
                                        $not_in_list_org_name = '';
                                    }


                                    $extra_party_address = strtoupper($dataRes->address);
                                    if (isset($dataRes->extra_party_village_name) && !empty($dataRes->extra_party_village_name)) {
                                        $extra_party_address .= ', ' . strtoupper(escape_data($dataRes->extra_party_village_name));
                                    }
                                    if (isset($dataRes->extra_party_ward_name) && !empty($dataRes->extra_party_ward_name)) {
                                        $extra_party_address .= ', ' . strtoupper(escape_data($dataRes->extra_party_ward_name));
                                    }
                                    if (isset($dataRes->extra_party_town_name) && !empty($dataRes->extra_party_town_name)) {
                                        $extra_party_address .= ', ' . strtoupper(escape_data($dataRes->extra_party_town_name));
                                    }
                                    if (isset($dataRes->extra_party_taluka_name) && !empty($dataRes->extra_party_taluka_name)) {
                                        $extra_party_address .= ', ' . strtoupper(escape_data($dataRes->extra_party_taluka_name));
                                    }
                                    if (isset($dataRes->extra_party_distt_name) && !empty($dataRes->extra_party_distt_name)) {
                                        $extra_party_address .= ', ' . strtoupper(escape_data($dataRes->extra_party_distt_name));
                                    }
                                    if (isset($dataRes->extra_party_state_name) && !empty($dataRes->extra_party_state_name)) {
                                        $extra_party_address .= ', ' . strtoupper(escape_data($dataRes->extra_party_state_name));
                                    }
                                    if (isset($dataRes->pincode) && !empty($dataRes->pincode)) {
                                        $extra_party_address .= ' - ' . escape_data($dataRes->pincode);
                                    }


                                    $type = ($dataRes->type == '1') ? ucwords($case_type_pet_title) : ucwords($case_type_res_title);
                                    $party_as_name ='';
                                    if(isset($dataRes->name) && !empty($dataRes->name)){
                                        $party_as_name = $dataRes->name;
                                    }
                                    else if(isset($dataRes->orgid) && !empty($dataRes->orgid) && $dataRes->orgid == 'D1'){
                                        $party_as_name = 'State Department';
                                    }
                                    else if(isset($dataRes->orgid) && !empty($dataRes->orgid) && $dataRes->orgid == 'D2'){
                                        $party_as_name = 'Central Department';
                                    }
                                    else if(isset($dataRes->orgid) && !empty($dataRes->orgid) && $dataRes->orgid == 'D3'){
                                        $party_as_name = 'Other Organisation';
                                    }
                                    if ($dataRes->pet_age == 0) {
                                        $extra_party_age = '';
                                    } else {
                                        $extra_party_age = $dataRes->pet_age;
                                    }
                                    $extraType =$dataRes->extra_party_is;
                                    if (!empty($extra_party_age)){$extra_party_age=$extra_party_age.'Yrs /' ;}
                                    $party_age_dob=!empty($dataRes->pet_dob) ? $extra_party_age.date('d-m-Y',strtotime($dataRes->pet_dob)):$extra_party_age;
                                    $extraType =$dataRes->extra_party_is;
                                    $extra_party_type_details='';
                                    if(isset($dataRes->orgid) && !empty($dataRes->orgid) && $dataRes->orgid != 'I'){
                                        $extra_party_org_dept_name=!empty($dataRes->extra_party_org_dept_name)? '<br/>, Department Name : '.$dataRes->extra_party_org_dept_name.'<br/>':'';
                                        $extra_party_org_post_name=!empty($dataRes->extra_party_org_post_name)? ', Post Name : '.$dataRes->extra_party_org_post_name.'<br/>':'';
                                        $extra_party_org_state_name=!empty($dataRes->extra_party_org_state_name)? ', State Name : '.$dataRes->extra_party_org_state_name:'';
                                        $extra_party_type_details =$extra_party_org_dept_name.$extra_party_org_post_name.$extra_party_org_state_name;
                                    }else if(isset($dataRes->orgid) && !empty($dataRes->orgid) && $dataRes->orgid == 'I'){
                                        $extra_party_type_details = ',Extra Party Name: '.$dataRes->name;
                                    }
                                    echo '<tr>';
                                    echo '<td>' . $i++ . '</td>';
                                    echo '<td>' . $extraType . '</td>';
                                    echo '<td>' . strtoupper(escape_data('Extra Party Type : '.$dataRes->extra_party_type.$extra_party_type_details)) . ' ' . $relation . ' ' . $not_in_list_org_name . '</td>';
                                    echo '<td>' . $party_age_dob .'</td>';
                                    echo '<td>' . $extra_party_address . '<br>' . strtoupper(escape_data($dataRes->pet_email)) . '<br>' . escape_data($dataRes->pet_mobile) . '</td>';

                                    if ($_SESSION['efiling_details']['stage_id'] == Draft_Stage || $_SESSION['efiling_details']['stage_id'] == Initial_Defected_Stage) {
                                        echo '<td>
                                            <a href="' . base_url('caveat/extra_party/' . url_encryption(trim(escape_data($dataRes->id)) . '$$party_id')) . '" class="btn btn-warning btn-xs" ><i class="fa fa-edit"></i> Edit</a>
                                            <a href="' . base_url('caveat/extra_party/delete/' . url_encryption(trim(escape_data($dataRes->id)))) . '/' . url_encryption(trim(escape_data($dataRes->type) . '#1#' . escape_data($dataRes->party_no))) . '" class="btn btn-danger btn-xs confirm"><i class="fa fa-trash"></i> Delete</a></td>';
                                    }
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
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
<script type="text/javascript">
    var state_Arr = '<?php echo json_encode($stateArr)?>';
    $('#alt_party_state').change(function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#alt_party_district').val('');
        var get_state_id = $(this).val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
            url: "<?php echo base_url('newcase/Ajaxcalls/get_districts'); ?>",
            success: function (data)
            {
                $('#alt_party_district').html(data);
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
    $('#alt_party_pincode').blur(function(){
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var pincode = $("#alt_party_pincode").val();
        if(pincode){
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, pincode : pincode},
                url: "<?php echo base_url('newcase/Ajaxcalls/getAddressByPincode'); ?>",
                success: function (response)
                {
                    var taluk_name;
                    var district_name;
                    var state;
                    if(response){
                        var resData = JSON.parse(response);
                        if(resData){
                            taluk_name = resData[0]['taluk_name'].trim().toUpperCase();
                            district_name = resData[0]['district_name'].trim().toUpperCase();
                            state = resData[0]['state'].trim().toUpperCase();
                        }
                        if(taluk_name){
                            $("#alt_party_city").val('');
                            $("#alt_party_city").val(taluk_name);
                        }
                        else{
                            $("#alt_party_city").val('');
                        }
                        if(state){
                            if(state_Arr){
                                var stateObj = JSON.parse(state_Arr);
                            }
                            if(stateObj){
                                var singleObj = stateObj.find(
                                    item => item['state_name'] === state
                                );
                            }
                            if(singleObj){
                                $('#alt_party_state').val('');
                                $('#alt_party_state').val(singleObj.id).select2().trigger("change");
                            }
                            else{
                                $('#alt_party_state').val('');
                            }
                            if(district_name){
                                var stateId = $('#alt_party_state').val();
                                setDistrict(stateId,district_name);
                            }
                        }
                        else{
                            $('#alt_party_state').val('');
                        }
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
        }
    });
    function setDistrict(stateId,district_name){
        if(stateId && district_name){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: stateId},
                url: "<?php echo base_url('newcase/Ajaxcalls/getSelectedDistricts'); ?>",
                success: function (resData)
                {
                    if(resData){
                        var districtObj = JSON.parse(resData);
                        var singleObj = districtObj.find(
                            item => item['district_name'] === district_name
                        );
                        if(singleObj){
                            $('#alt_party_district').val('');
                            $('#alt_party_district').val(singleObj.id).select2().trigger("change");
                        }
                        else{
                            $('#alt_party_district').val('');
                        }
                    }
                    else{
                        $('#alt_party_district').val('');
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
        }
    }
    function get_caveator_as(value) {
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
                $('#caveator_name').val('');
                $('#relation').val('');
                $('#relative_name').val('');
                $('#caveator_dob').val('');
                $('#caveator_age').val('');
                /*$('#party_gender1').val('');
                 $('#party_gender2').val('');
                 $('#party_gender3').val('');*/
            } else {
                $('#indvidual_form').hide();
                $('#org_form').show();
                $('#org_state_row').show();
                $('#caveator_name').val('');
                $('#relation').val('');
                $('#relative_name').val('');
                $('#caveator_dob').val('');
                $('#caveator_age').val('');

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
            $('#org_dept').select2();
            $('#org_post').val('<?php echo url_encryption(0); ?>');
            $('#org_post').select2();
        } else {
            $('#otherOrgState').hide();
            $('#otherOrgDept').hide();
            $('#otherOrgPost').hide();
            $('#org_dept').val('');
            $('#org_dept').select2();
            $('#org_post').val('');
            $('#org_post').select2();
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
            $('#org_post').select2();
        } else {
            $('#otherOrgDept').hide();
            $('#otherOrgPost').hide();
            $('#org_post').val('');
            $('#org_post').select2();
        }
    });


    //---------- Organisation Post Name----------------------//
    $('#org_post').change(function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var org_post = $(this).val();
        if (org_post == '<?php echo url_encryption(0); ?>') {
            $('#otherOrgPost').show();

        } else {
            $('#otherOrgPost').hide();
        }
    });










    function get_departments(party_is) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var selected_org_st_id = '<?php echo url_encryption($caveator_details[0]['org_state_id']); ?>';
        var selected_dept_id = '<?php echo url_encryption($caveator_details[0]['org_dept_id']); ?>';

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

        var selected_post_id = '<?php echo url_encryption($caveator_details[0]['org_post_id']); ?>';

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
    $(document).ready(function () {
        $('#add_extra_party11').on('submit', function () {
            var form_data = $(this).serialize();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('caveat/extra_party/add_extra_party/') . $this->uri->segment(3); ?>",
                data: form_data,
                beforeSend: function () {
                  $('#extra_party_save').val('Please wait...');
                  $('#extra_party_save').prop('disabled', true);
                },
                success: function (data) {
                    $('#extra_party_save').val('SAVE');
                    $('#extra_party_save').prop('disabled', false);
                    var resArr = data.split('@@@');
                    if (resArr[0] == 1) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");

                    } else if (resArr[0] == 2) {
                        $('#msg').show();

                        $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        window.location.href = '<?php echo base_url('caveat/extra_party'); ?>';
                    }

                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }, error: function () {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
            return false;
        });
    });
</script>

<script type="text/javascript">
    $('.confirm').on('click', function () {
        return confirm('Are you sure want to continue?');
    });
</script>
<script>
    //--------------------Get Caste-------------------------------//
    $('#ex_religion').change(function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#caste').val('');
        var get_rel_id = $(this).val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, get_rel_id: get_rel_id},
            url: "<?php echo base_url('Webservices/get_caste_list'); ?>",
            success: function (data)
            {
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    $('#caste').html(resArr[1]);
                }

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

    //----------------------end---------------------------------//

</script>
<script type="text/javascript">
    function extraInfoDivShow(element) {
        if (element.checked == true) {
            document.getElementById("extra_info_show").style.display = "block";
            $('#alt_party_state').select2();
            $('#alt_party_district').select2();

        } else if (element.checked == false) {
            document.getElementById("extra_info_show").style.display = "none";
        }
        else{
            document.getElementById("extra_info_show").style.display = "none";
        }

    }
    function respondentProforma(value) {

        if (value == '1') {
            var title = '<?php echo ucfirst($case_type_pet_title); ?>';
            if (title != '') {
                var title_name = title;
            } else {
                title_name = 'Caveator';
            }
            document.getElementById("proforma_show").style.display = "none";
           // document.getElementById('age_label_name').innerHTML = 'Age <span style="color: red">*</span> :';
           // document.getElementById('label_name_extra_party').innerHTML = title_name + ' <span style="color: red">*</span> :';
           // document.getElementById('label_name_extra_party_is').innerHTML = title_name + ' is ' + ' <span style="color: red">*</span> :';
           // document.getElementById('relation_label_name').innerHTML = title_name;
<?php
if ($_SESSION['estab_details']['is_extra_pet_state_required'] == 't' && $_SESSION['estab_details']['is_extra_res_state_required'] == 'f') {
    echo '$("#state_req").attr("value","1");';
    echo '$("#state_req").show();';
} elseif ($_SESSION['estab_details']['is_extra_pet_state_required'] == 'f' && $_SESSION['estab_details']['is_extra_res_state_required'] == 't') {
    echo '$("#state_req").attr("value","2");';
    echo '$("#state_req").hide();';
}

if ($_SESSION['estab_details']['is_extra_pet_district_required'] == 't' && $_SESSION['estab_details']['is_extra_res_district_required'] == 'f') {
    echo '$("#district_req").attr("value","1");';
    echo '$("#district_req").show();';
} elseif ($_SESSION['estab_details']['is_extra_pet_district_required'] == 'f' && $_SESSION['estab_details']['is_extra_res_district_required'] == 't') {
    echo '$("#district_req").attr("value","2");';
    echo '$("#district_req").hide();';
}
if ($_SESSION['estab_details']['is_extra_pet_mobile_required'] == 't' && $_SESSION['estab_details']['is_extra_res_mobile_required'] == 'f') {
    echo '$("#mobile_req").attr("value","1");';
    echo '$("#mobile_req").show();';
} elseif ($_SESSION['estab_details']['is_extra_pet_mobile_required'] == 'f' && $_SESSION['estab_details']['is_extra_res_mobile_required'] == 't') {
    echo '$("#mobile_req").attr("value","2");';
    echo '$("#mobile_req").hide();';
}
if ($_SESSION['estab_details']['is_extra_pet_email_required'] == 't' && $_SESSION['estab_details']['is_extra_res_email_required'] == 'f') {
    echo '$("#email_req").attr("value","1");';
    echo '$("#email_req").show();';
} elseif ($_SESSION['estab_details']['is_extra_pet_email_required'] == 'f' && $_SESSION['estab_details']['is_extra_res_email_required'] == 't') {
    echo '$("#email_req").attr("value","2");';
    echo '$("#email_req").hide();';
}
?>

        } else if (value == '2') {
            var title = '<?php echo ucfirst($case_type_res_title); ?>';
            if (title != '') {
                var title_name = title;
            } else {
                title_name = 'Caveatee';
            }
            document.getElementById("proforma_show").style.display = "block";
           // document.getElementById('age_label_name').innerHTML = 'Age :';
            //document.getElementById('label_name_extra_party').innerHTML = title_name + ' Name ' + ' <span style="color: red">*</span> :';
           // document.getElementById('label_name_extra_party_is').innerHTML = title_name + ' is ' + ' <span style="color: red">*</span> :';
           // document.getElementById('relation_label_name').innerHTML = title_name;
<?php
if ($_SESSION['estab_details']['is_extra_pet_state_required'] == 't' && $_SESSION['estab_details']['is_extra_res_state_required'] == 'f') {
    echo '$("#state_req").attr("value","2");';
    echo '$("#state_req").hide();';
} elseif ($_SESSION['estab_details']['is_extra_pet_state_required'] == 'f' && $_SESSION['estab_details']['is_extra_res_state_required'] == 't') {
    echo '$("#state_req").attr("value","1");';
    echo '$("#state_req").show();';
}
if ($_SESSION['estab_details']['is_extra_pet_district_required'] == 't' && $_SESSION['estab_details']['is_extra_res_district_required'] == 'f') {
    echo '$("#district_req").attr("value","2");';
    echo '$("#district_req").hide();';
} elseif ($_SESSION['estab_details']['is_extra_pet_district_required'] == 'f' && $_SESSION['estab_details']['is_extra_res_district_required'] == 't') {
    echo '$("#district_req").attr("value","1");';
    echo '$("#district_req").show();';
}
if ($_SESSION['estab_details']['is_extra_pet_mobile_required'] == 't' && $_SESSION['estab_details']['is_extra_res_mobile_required'] == 'f') {
    echo '$("#mobile_req").attr("value","2");';
    echo '$("#mobile_req").hide();';
} elseif ($_SESSION['estab_details']['is_extra_pet_mobile_required'] == 'f' && $_SESSION['estab_details']['is_extra_res_mobile_required'] == 't') {
    echo '$("#mobile_req").attr("value","1");';
    echo '$("#mobile_req").show();';
}
if ($_SESSION['estab_details']['is_extra_pet_email_required'] == 't' && $_SESSION['estab_details']['is_extra_res_email_required'] == 'f') {
    echo '$("#email_req").attr("value","2");';
    echo '$("#email_req").hide();';
} elseif ($_SESSION['estab_details']['is_extra_pet_email_required'] == 'f' && $_SESSION['estab_details']['is_extra_res_email_required'] == 't') {
    echo '$("#email_req").attr("value","1");';
    echo '$("#email_req").show();';
}
?>
        }
    }


    function showOrganisationExtraParty(element) {
        var sel_case_type = $("input:radio[name=complainant_accused_type]:checked").val();

        if (element == 2) {

            $('.show_hide_base_on_org').hide();
            $('#exp_not_in_list_org').prop("checked", false);
            document.getElementById("extraPartyOrgShow").style.display = "block";
            $('#not_in_list_org_name').hide();
            $('#proforma_show').show();

        } else if (element == 1) {
            document.getElementById("organisation_name").value = "";
            document.getElementById("compainant_accused").value = "";
            document.getElementById("address").value = "";
            document.getElementById("extraPartyOrgShow").style.display = "none";
            $('.show_hide_base_on_org').show();
            $('#not_in_list_org_name').hide();

            if (sel_case_type == '1') {
                var title_name = 'Caveator';
            } else if (sel_case_type == '2') {
                title_name = 'Caveatee';
            }
            document.getElementById('label_name_extra_party').innerHTML = title_name + ' Name ' + ' <span style="color: red">*</span> :';
            document.getElementById('label_name_extra_party_is').innerHTML = title_name + ' is ' + ' <span style="color: red">*</span> :';
        }
    }
    $('#rel_flag_1').change(function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var pet_rel_flag = $('#rel_flag_1').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, relation_id: pet_rel_flag},
            url: "<?php echo base_url('caveat/ajaxCalls/relative_required'); ?>",
            success: function (data)
            {
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    var res = resArr[1].split('#$');
                    var res_name = res[1].toUpperCase()
                    if (res[0] == '1' && res_name == 'SELF') {
                        document.getElementById('relative_label_name').innerHTML = 'Relative Name :';
                    } else {
                        document.getElementById('relative_label_name').innerHTML = 'Relative Name <span style="color: red">*</span> :';

                    }
                }
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
<script type="text/javascript">
    var sel_case_type = $("input:radio[name=complainant_accused_type]:checked").val();

    if (sel_case_type == 2) {
        $('#proforma_show').show();
    }
    //----------Get District List----------------------//
    $('#state_id').change(function () {

        $('#district').html('<option value="<?php echo url_encryption('0#$ $$district'); ?>"> Select District </option>');
        $('#town').html('<option value="<?php echo url_encryption('0#$ $$town'); ?>"> Select Town </option>');
        $('#taluka').html('<option value="<?php echo url_encryption('0#$ $$taluka'); ?>"> Select Taluka </option>');
        $('#ward').html('<option value="<?php echo url_encryption('0#$ $$ward'); ?>"> Select Ward </option>');
        $('#village').html('<option value="<?php echo url_encryption('0#$ $$village'); ?>"> Select Village </option>');

        var get_state_id = $(this).val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
            url: "<?php echo base_url('Webservices/get_district'); ?>",
            success: function (data)
            {
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    $('#district').html(resArr[1]);
                }
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

    //----------Get Taluka and Town List----------------------//
    $('#district').change(function () {

        $('#town').html('<option value="<?php echo url_encryption('0#$ $$town'); ?>"> Select Town </option>');
        $('#taluka').html('<option value="<?php echo url_encryption('0#$ $$taluka'); ?>"> Select Taluka </option>');
        $('#ward').html('<option value="<?php echo url_encryption('0#$ $$ward'); ?>"> Select Ward </option>');
        $('#village').html('<option value="<?php echo url_encryption('0#$ $$village'); ?>"> Select Village </option>');

        var get_distt_id = $(this).val();
        var get_state_id = $('#state_id').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, get_state_id: get_state_id, taluka_distt_id: get_distt_id},
            url: "<?php echo base_url('Webservices/get_taluka'); ?>",
            success: function (data)
            {
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    $('#taluka').html(resArr[1]);
                }

                $.getJSON("<?php echo base_url('csrf_token'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $.ajax({
                        type: "POST",
                        data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, get_state_id: get_state_id, town_distt_id: get_distt_id},
                        url: "<?php echo base_url('Webservices/get_town'); ?>",
                        success: function (data)
                        {
                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                $('#msg').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            } else if (resArr[0] == 2) {
                                $('#town').html(resArr[1]);
                            }

                            $.getJSON("<?php echo base_url('csrf_token'); ?>", function (result) {
                                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                get_ex_party_police_station_list(get_distt_id, result.CSRF_TOKEN_VALUE);
                            });
                        },
                        error: function () {
                            $.getJSON("<?php echo base_url('csrf_token'); ?>", function (result) {
                                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                            });
                        }
                    });
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrf_token'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });

    });

    function get_ex_party_police_station_list(get_distt_id, csrf)
    {
        var CSRF_TOKEN_VALUE = csrf;
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, ex_party_distt_id: get_distt_id, distt_id: get_distt_id},
            url: "<?php echo base_url('Webservices/get_police_station'); ?>",
            success: function (data)
            {
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    $('#ps_code').html(resArr[1]);
                }

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
    }
    //----------Get Village List----------------------//
    $('#taluka').change(function () {

        $('#ward').html('<option value="<?php echo url_encryption('0#$ $$ward'); ?>"> Select Ward </option>');
        $('#village').html('<option value="<?php echo url_encryption('0#$ $$village'); ?>"> Select Village </option>');

        var get_taluka_id = $(this).val();
        var get_distt_id = $('#district').val();
        var get_state_id = $('#state_id').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, get_state_id: get_state_id, taluka_distt_id: get_distt_id, get_taluka_id: get_taluka_id},
            url: "<?php echo base_url('Webservices/get_village_list'); ?>",
            success: function (data)
            {
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    $('#village').html(resArr[1]);
                }

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
    //----------Get Ward List----------------------//
    $('#town').change(function () {

        $('#ward').html('<option value="<?php echo url_encryption('0#$ $$ward'); ?>"> Select Ward </option>');
        var get_town_id = $(this).val();
        var get_state_id = $('#state_id').val();
        var distt_id = $('#district').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, get_state_id: get_state_id, distt_id: distt_id, get_town_id: get_town_id},
            url: "<?php echo base_url('Webservices/get_ward_list'); ?>",
            success: function (data)
            {
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    $('#ward').html(resArr[1]);
                }

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



    //----------Get District List----------------------//
    $('#o_state_id').change(function () {

        $('#o_district').html('<option value="<?php echo url_encryption('0#$ $$district'); ?>"> Select District </option>');
        $('#o_town').html('<option value="<?php echo url_encryption('0#$ $$town'); ?>"> Select Town </option>');
        $('#o_taluka').html('<option value="<?php echo url_encryption('0#$ $$taluka'); ?>"> Select Taluka </option>');
        $('#o_ward').html('<option value="<?php echo url_encryption('0#$ $$ward'); ?>"> Select Ward </option>');
        $('#o_village').html('<option value="<?php echo url_encryption('0#$ $$village'); ?>"> Select Village </option>');

        var get_state_id = $(this).val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
            url: "<?php echo base_url('Webservices/get_district'); ?>",
            success: function (data)
            {
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    $('#o_district').html(resArr[1]);
                }

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
    //----------Get Taluka and Town List----------------------//
    $('#o_district').change(function () {

        $('#o_town').html('<option value="<?php echo url_encryption('0#$ $$town'); ?>"> Select Town </option>');
        $('#o_taluka').html('<option value="<?php echo url_encryption('0#$ $$taluka'); ?>"> Select Taluka </option>');
        $('#o_ward').html('<option value="<?php echo url_encryption('0#$ $$ward'); ?>"> Select Ward </option>');
        $('#o_village').html('<option value="<?php echo url_encryption('0#$ $$village'); ?>"> Select Village </option>');

        var get_distt_id = $(this).val();
        var get_state_id = $('#o_state_id').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, get_state_id: get_state_id, taluka_distt_id: get_distt_id},
            url: "<?php echo base_url('Webservices/get_taluka'); ?>",
            success: function (data)
            {
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    $('#o_taluka').html(resArr[1]);
                }

                $.getJSON("<?php echo base_url('csrf_token'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $.ajax({
                        type: "POST",
                        data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, get_state_id: get_state_id, town_distt_id: get_distt_id},
                        url: "<?php echo base_url('Webservices/get_town'); ?>",
                        success: function (data)
                        {
                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                $('#msg').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            } else if (resArr[0] == 2) {
                                $('#o_town').html(resArr[1]);
                            }

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
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrf_token'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });

    });
    //----------Get Village List----------------------//
    $('#o_taluka').change(function () {

        $('#o_ward').html('<option value="<?php echo url_encryption('0#$ $$ward'); ?>"> Select Ward </option>');
        $('#o_village').html('<option value="<?php echo url_encryption('0#$ $$village'); ?>"> Select Village </option>');

        var get_state_id = $('#o_state_id').val();
        var get_distt_id = $('#o_district').val();
        var get_taluka_id = $(this).val();

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, get_state_id: get_state_id, taluka_distt_id: get_distt_id, get_taluka_id: get_taluka_id},
            url: "<?php echo base_url('Webservices/get_village_list'); ?>",
            success: function (data)
            {
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    $('#o_village').html(resArr[1]);
                }

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
    //----------Get Ward List----------------------//
    $('#o_town').change(function () {

        $('#o_ward').html('<option value="<?php echo url_encryption('0#$ $$ward'); ?>"> Select Ward </option>');

        var get_town_id = $(this).val();
        var get_state_id = $('#o_state_id').val();
        var distt_id = $('#o_district').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, get_state_id: get_state_id, distt_id: distt_id, get_town_id: get_town_id},
            url: "<?php echo base_url('Webservices/get_ward_list'); ?>",
            success: function (data)
            {
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    $('#o_ward').html(resArr[1]);
                }

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


    //----------Get Organisation Details----------------------//
    $('#organisation_name').change(function () {
        $('#compainant_accused').val('');
        $('#address').val('');
        var get_org_id = $(this).val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, organisation_id: get_org_id},
            url: "<?php echo base_url('caveat/ajaxCalls/get_org_details'); ?>",
            success: function (data)
            {
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    if (resArr[1] == 'select') {
                        $('#not_in_list_org_name').hide();
                    } else if (resArr[1] == 'not_in_list') {
                        $('#not_in_list_org_name').show();
                    } else {
                        $('#not_in_list_org_name').hide();
                        $('#compainant_accused').val(resArr[1]);
                        $('#address').val(resArr[2]);
                    }
                }
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

