<?php
if (!empty(getSessionData('login'))) {
    $extends = 'layout.advocateApp';
} else {
    $extends = 'layout.frontApp';
}
?>
@extends($extends)
@section('content')
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/black-theme.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/responsive.css" rel="stylesheet">
<style>
    .row {
        padding-top: 10px;
    }

    form label {
        font-size: 17px;
    }
</style>

<div class="container-fluid">
    <div class="row card">
        <div class="col-lg-12">
            <div class="card-body">
                <div class="dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="dash-card">
                                {{-- Page Title Start --}}
                                <div class="title-sec">
                                    <h5 class="unerline-title">Add/Approve Advocate(s)</h5>
                                    @if(!empty(getSessionData('login')))
                                    <!-- <a href="javascript:void(0)" class="quick-btn pull-right" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                    <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                    @endif
                                </div>
                                @if(!empty(getSessionData('emailExistMessage')))
                                <div class="alert alert-dismissible text-center flashmessage">
                                    <b style="color: red;">{{ getSessionData('emailExistMessage') }}</b>
                                </div>
                                @endif
                                @if(!empty(getSessionData('mobileExistMessage')))
                                <div class="alert alert-dismissible text-center flashmessage">
                                    <b style="color: red;">{{ getSessionData('mobileExistMessage') }}</b>
                                </div>
                                @endif
                                @if(!empty(getSessionData('success')))
                                <div class="alert alert-dismissible text-center flashmessage">
                                    <b style="color: green;">{{ getSessionData('success') }}</b>
                                </div>
                                @endif
                                @if(!empty(getSessionData('error')))
                                <div class="alert alert-dismissible text-center flashmessage">
                                    <b style="color: red;">{{ getSessionData('error') }}</b>
                                </div>
                                @endif
                                <!-- @if(isset($validation))
                                    <?php // foreach($validation as $valid){
                                    ?>
                                    <div class="validation-errors">
                                        <b style="color: red;">{{ $valid }}</b>
                                    </div>
                                    <?php //} 
                                    ?>
                                @endif  -->

                                {{-- Page Title End --}}
                                {{-- Main Start --}}
                                <div class="uk-margin-small-top uk-border-rounded">
                                    <?php
                                    $bar_reg_no = '';
                                    if (isset($arguingCounselDetails['registration_code']) && !empty($arguingCounselDetails['registration_code'])) {
                                        $advocate_name = !empty($arguingCounselDetails['advocate_name']) ? $arguingCounselDetails['advocate_name'] : '';
                                        $mobile_number = !empty($arguingCounselDetails['mobile_number']) ? $arguingCounselDetails['mobile_number'] : '';
                                        $emailid = !empty($arguingCounselDetails['emailid']) ? $arguingCounselDetails['emailid'] : '';
                                        $bar_reg_no = !empty($arguingCounselDetails['bar_reg_no']) ? $arguingCounselDetails['bar_reg_no'] : '';
                                        $action = "saveArguingCounselCompleteDetails";
                                        $attribute = array('class' => 'form-horizontal form-label-left', 'enctype' => 'multipart/form-data', 'id' => 'saveArguingCounselCompleteDetails', 'name' => 'saveArguingCounselCompleteDetails', 'autocomplete' => 'off');
                                        echo form_open($action, $attribute);
                                    ?>

                                        <div class="uk-child-width-1-1 uk-child-width-1-2@m uk-grid-small uk-grid" uk-grid="" uk-height-viewport="offset-top:true" style="min-height: calc(100vh - 60.4062px);">
                                            <!-- left box start -->
                                            <div class="row">
                                                <div class="col">
                                                    <label class="form-label" for="form-stacked-text">Name <span style="color: red">*</span> :</label>
                                                    <input class="form-control cus-form-ctrl" type="text" name="name" id="name" placeholder="Name" value="<?php echo set_value('name'); ?>" maxlength="100" minlength="3" tabindex="1">
                                                    <div id="error_name"></div>
                                                    <?php if (isset($validation) && $validation->hasError('name')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('name'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col">
                                                    <label class="form-label" for="form-stacked-text">Email <span style="color: red">*</span> :</label>
                                                    <input class="form-control cus-form-ctrl" type="email" name="email" id="email" value="<?php echo $emailid; ?>" maxlength="100" placeholder="admin@gmail.com " tabindex="2" readonly />
                                                    <div id="error_email"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <label class="form-label" for="form-stacked-text">Mobile <span style="color: red">*</span> :</label>
                                                    <input class="form-control cus-form-ctrl" type="text" name="mobile" id="mobile" value="<?php echo $mobile_number; ?>" placeholder="9876543XXX" maxlength="10" tabindex="3" readonly>
                                                    <div id="error_mobile"></div>
                                                    <?php if (isset($validation) && $validation->hasError('mobile')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('mobile'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col">
                                                    <label class="form-label" for="form-stacked-select">AOR <span style="color: red">*</span> :</label>
                                                    <select class="form-control cus-form-ctrl" tabindex='4' name="aor" id="aor">
                                                        <option value="">Select AOR</option>
                                                        <?php
                                                        if (isset($aorList) && !empty($aorList)) {
                                                            foreach ($aorList as $k => $v) {
                                                                $name = '';
                                                                $name = $v['first_name'];
                                                                echo '<option value="' . $v['id'] . '">' . $name . ' - ( <b>' . $v['userid'] . '</b> )</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <div id="error_aor"></div>
                                                    <?php if (isset($validation) && $validation->hasError('aor')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('aor'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <label class="form-label" for="form-stacked-select">Relation <span style="color: red">*</span> :</label>
                                                    <select class="form-control cus-form-ctrl" tabindex='5' name="relation" id="relation">
                                                        <option value="">Select Relation</option>
                                                        <option value="S">Son Of</option>
                                                        <option value="D">Daughter Of</option>
                                                        <option value="W">Spouse Of</option>
                                                        <option value="N">Not Available</option>
                                                    </select>
                                                    <div id="error_relation"></div>
                                                    <?php if (isset($validation) && $validation->hasError('relation')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('relation'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col">
                                                    <label class="form-label" for="form-stacked-text">Relative Name <span style="color: red">*</span>:</label>
                                                    <input class="form-control cus-form-ctrl" type="text" value="<?php echo set_value('relation_name'); ?>" name="relation_name" id="relation_name" placeholder="Relative Name" maxlength="35" tabindex="6" />
                                                    <div id="error_relation_name"></div>
                                                    <?php if (isset($validation) && $validation->hasError('relation_name')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('relation_name'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12">
                                                    <label class="form-label" for="form-stacked-text">Chamber Address :</label>
                                                    <textarea class="form-control cus-form-ctrl" rows="2" name="c_address" id="c_address" placeholder="Address" maxlength="250" tabindex="7"><?php echo set_value('c_address'); ?></textarea>
                                                    <div id="error_c_address"></div>
                                                    <?php if (isset($validation) && $validation->hasError('c_address')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('c_address'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <!-- left box end -->
                                            <!-- right box start -->
                                            <div class="row">
                                                <div class="col">
                                                    <label class="form-label" for="form-stacked-text">Registration Bar Id Card <span style="color: red">*</span>Size: (<?php echo ((BAR_ID_CARD_SIZE / 1024) / 1024); ?>MB) Type:(pdf/jpeg/jpg):</label>
                                                    <input class="form-control cus-form-ctrl" type="file" accept=".pdf,.jpeg,.jpg" name="bar_id_card" id="bar_id_card" tabindex="8" />
                                                    <div id="error_bar_id_card"></div>
                                                    <?php if (isset($validation) && $validation->hasError('bar_id_card')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('bar_id_card'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col">
                                                    <label class="form-label" for="form-stacked-text">Bar Registration No. <span style="color: red">*</span> :</label>
                                                    <?php
                                                    $readony = "";
                                                    if (isset($bar_reg_no) && !empty($bar_reg_no)) {
                                                        $readony = "readonly";
                                                    }
                                                    ?>
                                                    <input class="form-control cus-form-ctrl" type="text" name="bar_reg_no" value="<?php echo ($bar_reg_no) ?  $bar_reg_no : set_value('bar_reg_no'); ?>" id="bar_reg_no" maxlength="30" placeholder="" tabindex="9" <?php echo $readony; ?> required />
                                                    <div id="error_bar_reg_no"></div>
                                                    <?php if (isset($validation) && $validation->hasError('bar_reg_no')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('bar_reg_no'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <label class="form-label" for="form-stacked-text">Pincode :</label>
                                                    <input class="form-control cus-form-ctrl" type="text" value="<?php echo set_value('c_pincode'); ?>" name="c_pincode" onkeyup="return isNumber(event)" id="c_pincode" placeholder="Pincode" maxlength="6" tabindex="10" />
                                                    <div id="error_c_pincode"></div>
                                                    <?php if (isset($validation) && $validation->hasError('c_pincode')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('c_pincode'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col">
                                                    <label class="form-label" for="form-stacked-select">State :</label>
                                                    <select class="form-control cus-form-ctrl" name="c_state" id="c_state" tabindex="11">
                                                        <option value="">Select State</option>
                                                        <?php
                                                        $stateArr = array();
                                                        if (isset($state_list) && !empty($state_list)) {
                                                            foreach ($state_list as $dataRes) {
                                                                echo '<option value="' . url_encryption(trim($dataRes->cmis_state_id)) . '">' . strtoupper($dataRes->agency_state) . '</option>';
                                                                $tempArr = array();
                                                                $tempArr['id'] = url_encryption(trim($dataRes->cmis_state_id));
                                                                $tempArr['state_name'] = strtoupper($dataRes->agency_state);
                                                                $stateArr[] = (object)$tempArr;
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <div id="error_c_state"></div>
                                                    <?php if (isset($validation) && $validation->hasError('c_state')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('c_state'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <label class="form-label" for="form-stacked-select">District :</label>
                                                    <select class="form-control cus-form-ctrl" name="c_district" id="c_district" tabindex="12">
                                                        <option value="">Select District</option>
                                                    </select>
                                                    <div id="error_c_district"></div>
                                                    <?php if (isset($validation) && $validation->hasError('c_district')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('c_district'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col">
                                                    <label class="form-label" for="form-stacked-text">City :</label>
                                                    <input class="form-control cus-form-ctrl" type="text" value="<?php echo set_value('c_city'); ?>" name="c_city" id="c_city" placeholder="City" maxlength="25" tabindex="13" />
                                                    <div id="error_c_city"></div>
                                                    <?php if (isset($validation) && $validation->hasError('c_city')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('c_city'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12">
                                                    <label style="margin-top: 35px;margin-bottom: 9px;" class="form-label"><input class="uk-checkbox" type="checkbox" name="same_as" id="same_as" tabindex="14" /> Same As Chamber Address :</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12">
                                                    <label class="form-label" for="form-stacked-text">Residential Address <span style="color: red">*</span> :</label>
                                                    <textarea class="form-control cus-form-ctrl" name="r_address" id="r_address" placeholder="Address" maxlength="250" tabindex="15"><?php echo set_value('r_address'); ?> </textarea>
                                                    <div id="error_r_address"></div>
                                                    <?php if (isset($validation) && $validation->hasError('r_address')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('r_address'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <label class="form-label" for="form-stacked-text">Pincode <span style="color: red">*</span> :</label>
                                                    <input class="form-control cus-form-ctrl" value="<?php echo set_value('r_pincode'); ?>" name="r_pincode" id="r_pincode" placeholder="Pincode" maxlength="6" tabindex="16" />
                                                    <div id="error_r_pincode"></div>
                                                    <?php if (isset($validation) && $validation->hasError('r_pincode')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('r_pincode'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col">
                                                    <label class="form-label" for="form-stacked-select">State <span style="color: red">*</span> :</label>
                                                    <select class="form-control cus-form-ctrl" name="r_state" id="r_state" tabindex="17">
                                                        <option value="">Select State</option>
                                                        <?php
                                                        if (isset($state_list) && !empty($state_list)) {
                                                            foreach ($state_list as $dataRes) {
                                                                echo '<option value="' . url_encryption(trim($dataRes->cmis_state_id)) . '">' . strtoupper($dataRes->agency_state) . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <div id="error_r_state"></div>
                                                    <?php if (isset($validation) && $validation->hasError('r_state')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('r_state'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <label class="form-label" for="form-stacked-text">City <span style="color: red">*</span> :</label>
                                                    <input class="form-control cus-form-ctrl" value="<?php echo set_value('r_city'); ?>" name="r_city" id="r_city" placeholder="City" maxlength="25" tabindex="18" />
                                                    <div id="error_r_city"></div>
                                                    <?php if (isset($validation) && $validation->hasError('r_city')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('r_city'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col">
                                                    <label class="form-label" for="form-stacked-select">District <span style="color: red">*</span> :</label>
                                                    <select class="form-control cus-form-ctrl" name="r_district" id="r_district" tabindex="19">
                                                        <option>Select District</option>
                                                    </select>
                                                    <div id="error_r_district"></div>
                                                    <?php if (isset($validation) && $validation->hasError('r_district')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('r_district'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <!-- right box end -->
                                            <div style="text-align: center;">
                                                <!-- <input type="submit" class="quick-btn mt-3" name="register" id="register" value="Register"> -->
                                                <button type="submit" class="quick-btn mt-3" name="register" id="register">Register</button>
                                                <button type="button" class=" quick-btn gray-btn mt-3" name="back" id="back" value="back" onclick="history.back()">Back</button>
                                            </div>

                                        </div>
                                    <?php
                                        echo form_close();
                                    } else if ((isset($arguingCounselDetails) && !empty($arguingCounselDetails)) &&  (!isset($arguingCounselDetails['registration_code']) && empty($arguingCounselDetails['registration_code']))) {
                                    ?>
                                        <div class="table-wrapper-scroll-y my-custom-scrollbar ">
                                            <div class="x_content">
                                            </div>
                                        </div>
                                    <?php } else if (!empty(getSessionData('login')['ref_m_usertype_id'])) {
                                        $action = "registerCounsel";
                                        $attribute = array('class' => 'form-horizontal form-label-left',  'id' => 'add_arguing_counsel', 'name' => 'add_arguing_counsel', 'autocomplete' => 'off');
                                        echo form_open($action, $attribute);
                                    ?>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <label class="form-label" for="form-stacked-text">Name <span style="color: red">*</span> :</label>
                                                <input class="form-control cus-form-ctrl" type="text" name="name" id="name" placeholder="Name" value="<?php echo set_value('name'); ?>" maxlength="100" minlength="3" tabindex="1">
                                                <div id="error_name"></div>
                                                <?php if (isset($validation) && $validation->hasError('name')): ?>
                                                    <div class="text-danger">
                                                        <?= $validation->getError('name'); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <label class="form-label" for="form-stacked-text">Email <span style="color: red">*</span> :</label>
                                                <input class="form-control cus-form-ctrl" type="email" name="email" id="email" value="<?php echo ($email_id) ? $email_id : set_value('email'); ?>" maxlength="100" placeholder="admin@gmail.com " tabindex="2" />
                                                <div id="error_email"></div>
                                                <?php if (isset($validation) && $validation->hasError('email')): ?>
                                                    <div class="text-danger">
                                                        <?= $validation->getError('email'); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="col-sm-12 col-md-6">
                                                <label class="form-label" for="form-stacked-text">Mobile <span style="color: red">*</span> :</label>
                                                <input class="form-control cus-form-ctrl" type="text" name="mobile" id="mobile" value="<?php echo ($mobile_no) ? $mobile_no : set_value('mobile'); ?>" placeholder="9876543XXX" maxlength="10" tabindex="3">
                                                <div id="error_mobile"></div>
                                                <?php if (isset($validation) && $validation->hasError('mobile')): ?>
                                                    <div class="text-danger">
                                                        <?= $validation->getError('mobile'); ?>
                                                    </div>
                                                <?php endif; ?>

                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <label class="form-label" for="form-stacked-text">Bar Registration No. :</label>
                                                <?php
                                                $readony = "";
                                                if (isset($bar_reg_no) && !empty($bar_reg_no)) {
                                                    $readony = "readonly";
                                                }
                                                ?>
                                                <input class="form-control cus-form-ctrl" type="text" name="bar_reg_no" value="<?php echo ($bar_reg_no) ? $bar_reg_no : set_value('bar_reg_no'); ?>" id="bar_reg_no" maxlength="30" placeholder="" tabindex="4" <?php echo $readony; ?> />
                                                <div id="error_bar_reg_no"></div>
                                                <?php if (isset($validation) && $validation->hasError('bar_reg_no')): ?>
                                                    <div class="text-danger">
                                                        <?= $validation->getError('bar_reg_no'); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div style="text-align: center;">
                                            <!-- <input type="submit" class="btn btn-primary quick-btn mt-3" name="add_arguing_counsel_submit" id="add_arguing_counsel_submit" value="Register"> -->
                                            <Button type="submit" class="quick-btn mt-3" name="add_arguing_counsel_submit" id="add_arguing_counsel_submit">Register</Button>
                                            <button type="button" class=" gray-btn quick-btn mt-3" name="back" id="back" value="back" onclick="history.back()">Back</button>
                                        </div>
                                    <?php
                                        echo form_close();
                                    } else if (!empty(getSessionData('self_register_arguing_counsel')) &&  (!empty(getSessionData('self_arguing_counsel')))) {
                                    ?>
                                        <div class="table-wrapper-scroll-y my-custom-scrollbar ">
                                            <div class="x_content">
                                            </div>
                                        </div>
                                    <?php } else if (!empty(getSessionData('self_register_arguing_counsel'))) {
                                        $action = "saveArguingCounselCompleteDetails";
                                        $attribute = array('class' => 'form-horizontal form-label-left', 'enctype' => 'multipart/form-data', 'id' => 'saveArguingCounselCompleteDetails', 'name' => 'saveArguingCounselCompleteDetails', 'autocomplete' => 'off');
                                        echo form_open($action, $attribute);
                                        $mobile_no = !empty(getSessiondata('adv_details')['mobile_no']) ? getSessiondata('adv_details')['mobile_no'] : '';
                                        $email_id = !empty(getSessiondata('adv_details')['email_id']) ? getSessiondata('adv_details')['email_id'] : '';
                                    ?>
                                        <div class="uk-child-width-1-1 uk-child-width-1-2@m uk-grid-small uk-grid" uk-grid="" uk-height-viewport="offset-top:true" style="min-height: calc(100vh - 60.4062px);">
                                            <!-- left box start -->
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <label class="form-label" for="form-stacked-text">Name <span style="color: red">*</span> :</label>
                                                    <input class="form-control cus-form-ctrl" type="text" name="name" id="name" placeholder="Name" value="<?php echo set_value('name'); ?>" maxlength="100" minlength="3" tabindex="1">
                                                    <div id="error_name"></div>
                                                    <?php if (isset($validation) && $validation->hasError('name')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('name'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <label class="form-label" for="form-stacked-text">Email <span style="color: red">*</span> :</label>
                                                    <input class="form-control cus-form-ctrl" type="email" name="email" id="email" value="<?php echo @$email_id; ?>" maxlength="100" placeholder="admin@gmail.com " tabindex="2" readonly />
                                                    <div id="error_email"></div>
                                                    <?php if (isset($validation) && $validation->hasError('email')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('email'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <label class="form-label" for="form-stacked-text">Mobile <span style="color: red">*</span> :</label>
                                                    <input class="form-control cus-form-ctrl" type="text" name="mobile" id="mobile" value="<?php echo @$mobile_no; ?>" placeholder="9876543XXX" maxlength="10" tabindex="3" readonly>
                                                    <div id="error_mobile"></div>
                                                    <?php if (isset($validation) && $validation->hasError('mobile')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('mobile'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <label class="form-label" for="form-stacked-select">AOR <span style="color: red">*</span> :</label>
                                                    <select class="form-control cus-form-ctrl" tabindex='4' name="aor" id="aor">
                                                        <option value="">Select AOR</option>
                                                        <?php
                                                        // pr($aorList);
                                                        if (isset($aorList) && !empty($aorList)) {
                                                            foreach ($aorList as $k => $v) {
                                                                $name = '';
                                                                if (isset($v['first_name']) && !empty($v['first_name']) && isset($v['last_name']) && !empty($v['last_name'])) {
                                                                    $name .= $v['first_name'] . ' ' . $v['last_name'];
                                                                } else  if (isset($v['first_name']) && !empty($v['first_name'])) {
                                                                    $name .= $v['first_name'];
                                                                }
                                                                echo '<option value="' . $v['id'] . '">' . $name . ' - ( <b>' . $v['userid'] . '</b> )</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <div id="error_aor"></div>
                                                    <?php if (isset($validation) && $validation->hasError('aor')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('aor'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <label class="form-label" for="form-stacked-select">Relation <span style="color: red">*</span> :</label>
                                                    <select class="form-control cus-form-ctrl" tabindex='5' name="relation" id="relation">
                                                        <option value="">Select Relation</option>
                                                        <option value="S">Son Of</option>
                                                        <option value="D">Daughter Of</option>
                                                        <option value="W">Spouse Of</option>
                                                        <option value="N">Not Available</option>
                                                    </select>
                                                    <div id="error_relation"></div>
                                                    <?php if (isset($validation) && $validation->hasError('relation')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('relation'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <label class="form-label" for="form-stacked-text">Relative Name <span style="color: red">*</span>:</label>
                                                    <input class="form-control cus-form-ctrl" type="text" value="<?php echo set_value('relation_name'); ?>" name="relation_name" id="relation_name" placeholder="Relative Name" maxlength="35" tabindex="6" />
                                                    <div id="error_relation_name"></div>
                                                    <?php if (isset($validation) && $validation->hasError('relation_name')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('relation_name'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <!-- left box end -->
                                            <!-- right box start -->
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12">
                                                    <label class="form-label" for="form-stacked-text">Chamber Address :</label>
                                                    <textarea class="form-control cus-form-ctrl" rows="2" name="c_address" id="c_address" placeholder="Address" maxlength="250" tabindex="7"><?php echo set_value('c_address'); ?></textarea>
                                                    <div id="error_c_address"></div>
                                                    <?php if (isset($validation) && $validation->hasError('c_address')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('c_address'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <label class="form-label" for="form-stacked-text">Registration Bar Id Card <span style="color: red">*</span>Size: (<?php echo ((BAR_ID_CARD_SIZE / 1024) / 1024); ?>MB) Type:(pdf/jpeg/jpg):</label>
                                                    <input class="form-control cus-form-ctrl" type="file" accept=".pdf,.jpeg,.jpg" name="bar_id_card" id="bar_id_card" tabindex="8" />
                                                    <div id="error_bar_id_card"></div>
                                                    <?php if (isset($validation) && $validation->hasError('bar_id_card')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('bar_id_card'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <label class="form-label" for="form-stacked-text">Bar Registration No. <span style="color: red">*</span> :</label>
                                                    <?php
                                                    $readony = "";
                                                    if (isset($bar_reg_no) && !empty($bar_reg_no)) {
                                                        $readony = "readonly";
                                                    }
                                                    ?>
                                                    <input class="form-control cus-form-ctrl" type="text" name="bar_reg_no" value="<?php echo ($bar_reg_no) ?  $bar_reg_no : set_value('bar_reg_no'); ?>" id="bar_reg_no" maxlength="30" placeholder="" tabindex="9" <?php echo $readony; ?> />
                                                    <div id="error_bar_reg_no"></div>
                                                    <?php if (isset($validation) && $validation->hasError('bar_reg_no')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('bar_reg_no'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <label class="form-label" for="form-stacked-text">Pincode :</label>
                                                    <input class="form-control cus-form-ctrl" type="text" value="<?php echo set_value('c_pincode'); ?>" name="c_pincode" onkeyup="return isNumber(event)" id="c_pincode" placeholder="Pincode" maxlength="6" tabindex="10" />
                                                    <div id="error_c_pincode"></div>
                                                    <?php if (isset($validation) && $validation->hasError('c_pincode')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('c_pincode'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <label class="form-label" for="form-stacked-select">State :</label>
                                                    <select class="form-control cus-form-ctrl" name="c_state" id="c_state" tabindex="11">
                                                        <option value="">Select State</option>
                                                        <?php
                                                        $stateArr = array();
                                                        if (isset($state_list) && !empty($state_list)) {
                                                            foreach ($state_list as $dataRes) {
                                                                echo '<option value="' . url_encryption(trim($dataRes->cmis_state_id)) . '">' . strtoupper($dataRes->agency_state) . '</option>';
                                                                $tempArr = array();
                                                                $tempArr['id'] = url_encryption(trim($dataRes->cmis_state_id));
                                                                $tempArr['state_name'] = strtoupper($dataRes->agency_state);
                                                                $stateArr[] = (object)$tempArr;
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <div id="error_c_state"></div>
                                                    <?php if (isset($validation) && $validation->hasError('c_state')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('c_state'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <label class="form-label" for="form-stacked-select">District :</label>
                                                    <select class="form-control cus-form-ctrl" name="c_district" id="c_district" tabindex="12">
                                                        <option value="">Select District</option>
                                                    </select>
                                                    <div id="error_c_district"></div>
                                                    <?php if (isset($validation) && $validation->hasError('c_district')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('c_district'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <label class="form-label" for="form-stacked-text">City :</label>
                                                    <input class="form-control cus-form-ctrl" type="text" value="<?php echo set_value('c_city'); ?>" name="c_city" id="c_city" placeholder="City" maxlength="25" tabindex="13" />
                                                    <div id="error_c_city"></div>
                                                    <?php if (isset($validation) && $validation->hasError('c_city')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('c_city'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12">
                                                    <label style="margin-top: 35px;margin-bottom: 9px;" class="form-label"><input class="uk-checkbox" type="checkbox" name="same_as" id="same_as" tabindex="14" /> Same As Chamber Address :</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12">
                                                    <label class="form-label" for="form-stacked-text">Residential Address <span style="color: red">*</span> :</label>
                                                    <textarea class="form-control cus-form-ctrl" name="r_address" id="r_address" placeholder="Address" maxlength="250" tabindex="15"><?php echo set_value('r_address'); ?> </textarea>
                                                    <div id="error_r_address"></div>
                                                    <?php if (isset($validation) && $validation->hasError('r_address')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('r_address'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <label class="form-label" for="form-stacked-text">Pincode <span style="color: red">*</span> :</label>
                                                    <input class="form-control cus-form-ctrl" value="<?php echo set_value('r_pincode'); ?>" name="r_pincode" id="r_pincode" placeholder="Pincode" maxlength="6" tabindex="16" />
                                                    <div id="error_r_pincode"></div>
                                                    <?php if (isset($validation) && $validation->hasError('r_pincode')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('r_pincode'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <label class="form-label" for="form-stacked-seleAdd/Approve Advocatect">State <span style="color: red">*</span> :</label>
                                                    <select class="form-control cus-form-ctrl" name="r_state" id="r_state" tabindex="17">
                                                        <option value="">Select State</option>
                                                        <?php

                                                        if (isset($state_list) && !empty($state_list)) {
                                                            foreach ($state_list as $dataRes) {
                                                                echo '<option value="' . url_encryption(trim($dataRes->cmis_state_id)) . '">' . strtoupper($dataRes->agency_state) . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <div id="error_r_state"></div>
                                                    <?php if (isset($validation) && $validation->hasError('r_state')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('r_state'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <label class="form-label" for="form-stacked-select">District <span style="color: red">*</span> :</label>
                                                    <select class="form-control cus-form-ctrl" name="r_district" id="r_district" tabindex="18">
                                                        <option>Select District</option>
                                                    </select>
                                                    <div id="error_r_district"></div>
                                                    <?php if (isset($validation) && $validation->hasError('r_district')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('r_district'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <label class="form-label" for="form-stacked-text">City <span style="color: red">*</span> :</label>
                                                    <input class="form-control cus-form-ctrl" value="<?php echo set_value('r_city'); ?>" name="r_city" id="r_city" placeholder="City" maxlength="25" tabindex="19" />
                                                    <div id="error_r_city"></div>
                                                    <?php if (isset($validation) && $validation->hasError('r_city')): ?>
                                                        <div class="text-danger">
                                                            <?= $validation->getError('r_city'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <!-- <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="radio-inline mb-2"><input type="checkbox" name="ecopyingCheck" id="ecopyingCheck" value="1">&nbsp;&nbsp;Applying for eCopying</label>
                                                    </div>
                                                </div> -->
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="mb-3">
                                                        <div class="card-body">
                                                            <div class="row m-1">
                                                                <div class="alert alert-warning alert-dismissible col-lg-12">
                                                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                                                    <strong>Unless there is request for Certified Copy/Digital Copy, your identity as claimed will not be verified.</strong>
                                                                </div>
                                                            </div>
                                                            <div class="row m-1">
                                                                <div class="alert alert-warning alert-dismissible">
                                                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                                                    <strong>Remove mask, spectacles or any other face covering.</strong>
                                                                </div>
                                                            </div>
                                                            <div class="left">
                                                                After you click start recording speak following things and record :<br>
                                                                1. Speak your full name<br>
                                                                2. Speak date of birth<br>
                                                                3. Speak this Code : <span class="font-weight-bolder text-danger"><?php echo $_SESSION['text_speak'] = rand_string(5); ?></span>
                                                                <br>
                                                                <div id="startButton" class="btn btn-primary btn-sm col-2 mt-2">
                                                                    Start Recording
                                                                </div>
                                                            </div>
                                                            <div class="center">
                                                                <div id="stopButton" class="button" style="display:none;">
                                                                    
                                                                </div>
                                                                <div id="cam_preview" style="display:none;">
                                                                    <h6 class="font-weight-bolder"> </h6>
                                                                    <video id="preview" width="340px" autoplay muted></video>
                                                                </div>
                                                                <div id="view_recording" style="display:none;">
                                                                    <h6 class="font-weight-bolder"></h6>
                                                                    <video id="recording" width="340px" controls></video>
                                                                </div>
                                                            </div>
                                                            <div class="bottom">
                                                                <pre id="log"></pre>
                                                            </div>
                                                            <form method="post" enctype="multipart/form-data">
                                                                <a id="downloadButton" class="button" style="display:none;">
                                                                    Download
                                                                </a>
                                                                <div id="video_action" class="left1 col-2" style="display:none;">
                                                                    <div id="tryAgain" class="btn btn-primary btn-sm mt-2">
                                                                    Try Again!
                                                                </div>

                                                                </div>
                                                            </form>
                                                            <div class="row show_msg col-12 mb-3"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="col-sm-12 col-md-6">
                                                    <label class="form-label" for="form-stacked-text">Upload Speech Recoreded <span style="color: red">*</span> :</label>
                                                    <input class="form-control cus-form-ctrl" type="file" value="" name="speech_recorded" id="speech_recorded"  placeholder="Speech file" maxlength="25" tabindex="19" />
                                                    <div id="error_r_city"></div>
                                                </div> -->
                                            <!-- right box end -->
                                            <div style="text-align: center;">
                                                <!-- <input type="submit" class="btn btn-primary quick-btn mt-3" name="register" id="register" value="Register"> -->
                                                <a id="saveButton" class="btn btn-primary" style="display: none"> Register </a>
                                                <!-- <button type="submit" class="quick-btn mt-3" name="register" id="register">Register</button> -->
                                                <button type="button" class=" gray-btn quick-btn mt-3" name="back" id="back" value="back" onclick="history.back()">Back</button>
                                            </div>
                                        </div>
                                    <?php
                                        echo form_close();
                                    }
                                    if (isset($selfArguingCounselData) && !empty($selfArguingCounselData)) {
                                        echo '<div class="x_content">
                                            <h2 class="textwhite uk-margin-remove uk-visible@m ukwidth-expand">Advocate Approval/Rejected</h2>
                                            <button class="uk-button-medium uk-button-primary actionType quick-btn" id="rejected" style="float: right;">Rejected</button>
                                            <button class="uk-button-medium uk-button-primary actionType quick-btn" id="approved" style="float: right;margin-right: 8px;margin-bottom: 12px;">Approved</button>
                                            <table id="datatable-responsive" class="table custom-table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr class="success input-sm" role="row" >
                                                        <th>S. No.</th>
                                                        <th>Name</th>
                                                        <th>Mobile No.</th>
                                                        <th>Email</th>
                                                        <th>Bar Reg. No.</th>
                                                        <th>
                                                            <label class="control-label" for="selectAll">
                                                                <input type="checkbox" name="selectAll" id="selectAll">
                                                                <span style="margin-left: 7px; font-size: 12px; color: #fff;" id="selectSpan">Select All</span>
                                                            </label>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>';
                                        $i = 1;
                                        foreach ($selfArguingCounselData as $value) {
                                            $first_name = !empty($value['first_name']) ? $value['first_name'] : '';
                                            $mobile = !empty($value['moblie_number']) ? $value['moblie_number'] : '';
                                            $email = !empty($value['emailid']) ? $value['emailid'] : '';
                                            $tbl_users_id = !empty($value['tbl_users_id']) ? $value['tbl_users_id'] : '';
                                            $bar_reg_no = !empty($value['bar_reg_no']) ? $value['bar_reg_no'] : '';
                                            echo '<tr>
                                                            <td data-key="S. No." width="4%">' . $i . '</td>
                                                            <td data-key="Name" width="10%">' . $first_name . '</td>
                                                            <td data-key="Mobile No." width="20%">' . $mobile . ' </td>
                                                            <td data-key="Email" width="10%">' . $email . '</td>
                                                            <td data-key="Bar Reg. No." width="10%">' . $bar_reg_no . '</td>
                                                            <td data-key="<label class="control-label" for="selectAll"><span style="margin-left: 7px; font-size: 12px; color: #fff;" id="selectSpan">Select All</span></label><input type="checkbox" id="checkbox_' . $i . '" class="selectAllcheckBox" data-userId="' . $tbl_users_id . '"/></td>
                                                        </tr>';
                                            $i++;
                                        }
                                        echo '</tbody>
                                            </table>
                                        </div>';
                                    }
                                    ?>
                                </div>
                                {{-- Main End --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>

<script>
    $(document).ready(function() {
        var state_Arr = '<?php echo isset($stateArr) ? json_encode($stateArr) : '' ?>';
        $(document).on('click', '#add_arguing_counsel_submit', function(e) {
            e.preventDefault();
            var nameRegPattern = /^[a-zA-Z‘_ ]+$/i;
            var emailPattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            var mobilePattern = /^\d{10}$/;
            var alphanumPattern = /^[a-zA-Z0-9]+$/i;
            var validation = true;
            var name = $.trim($("#name").val());
            var email = $.trim($("#email").val());
            var mobile = $.trim($("#mobile").val());
            var bar_reg_no = $.trim($("#bar_reg_no").val());
            if (name == '') {
                $("#name").focus();
                $("#error_name").text("Please fill name.");
                $("#error_name").css({
                    'color': 'red'
                });
                alert("Please fill name.");
                validation = false;
                return false;
            } else if (!nameRegPattern.test(name)) {
                $("#name").focus();
                $("#error_name").text("Please fill name only characters.");
                $("#error_name").css({
                    'color': 'red'
                });
                alert("Please fill name only characters.");
                validation = false;
                return false;
            } else if (name.length < 3 || name.length > 100) {
                $("#name").focus();
                $("#error_name").text("Please fill name between minimum 3 and maximum 100 characters.");
                $("#error_name").css({
                    'color': 'red'
                });
                alert("Please fill name between minimum 3 and maximum 100 characters.");
                validation = false;
                return false;
            } else if (email == '') {
                $("#email").focus();
                $("#error_email").text("Please fill email.");
                $("#error_email").css({
                    'color': 'red'
                });
                alert("Please fill email.");
                validation = false;
                return false;
            } else if (email && !emailPattern.test(email)) {
                $("#email").focus();
                $("#error_email").text("Please fill valid  email");
                $("#error_email").css({
                    'color': 'red'
                });
                alert("Please fill valid email");
                validation = false;
                return false;
            } else if (mobile == '') {
                $("#mobile").focus();
                $("#error_mobile").text("Please fill mobile.");
                $("#error_mobile").css({
                    'color': 'red'
                });
                alert("Please fill mobile.");
                validation = false;
                return false;
            } else if (mobile && !mobilePattern.test(mobile)) {
                $("#mobile").focus();
                $("#error_mobile").text("Please fill valid  mobile No.");
                $("#error_mobile").css({
                    'color': 'red'
                });
                alert("Please fill valid mobile No.");
                validation = false;
                return false;
            } else if (bar_reg_no && !alphanumPattern.test(bar_reg_no)) {
                $("#bar_reg_no").focus();
                $("#error_bar_reg_no").text("Please fill valid  bar reg. no.");
                $("#error_bar_reg_no").css({
                    'color': 'red'
                });
                alert("Please fill valid bar reg no.");
                validation = false;
                return false;
            } else if (validation) {
                $("#add_arguing_counsel").submit();
            }
        });
        $(document).on('click', '#register', function(e) {
            e.preventDefault();
            var nameRegPattern = /^[a-zA-Z‘_ ]+$/i;
            var emailPattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            var mobilePattern = /^\d{10}$/;
            var pincodePattern = /^\d{6}$/;
            var alphanumPattern = /^[a-zA-Z0-9 ]+$/i;
            var addressPattern = /^[a-zA-Z0-9\s,'-.]*$/i;
            var validation = true;
            var name = $.trim($("#name").val());
            var email = $.trim($("#email").val());
            var mobile = $.trim($("#mobile").val());
            var bar_reg_no = $.trim($("#bar_reg_no").val());
            var bar_id_card = $("#bar_id_card").val();
            var fileReg = /(.*?)\.(pdf|jpeg|jpg)$/;
            var relation = $("#relation option:selected").val();
            var relation_name = $("#relation_name").val();
            var bar_card_size = "<?php echo BAR_ID_CARD_SIZE; ?>";
            var c_address = $.trim($("textarea#c_address").val());
            var r_address = $.trim($("textarea#r_address").val());
            var c_pincode = $("#c_pincode").val();
            var c_city = $("#c_city").val();
            var c_state = $("#c_state option:selected").val();
            var c_district = $("#c_district").val();
            var r_pincode = $("#r_pincode").val();
            var r_city = $("#r_city").val();
            var r_state = $("#r_state option:selected").val();
            var r_district = $("#r_district").val();
            var bar_id_card_size = '';
            //  $("#r_district").prop('disabled', false);
            // $("#r_state").prop('disabled', false);
            if (bar_id_card) {
                bar_id_card_size = document.getElementById('bar_id_card').files[0]['size'];
            }
            if (name == '') {
                $("#name").focus();
                $("#error_name").text("Please fill name.");
                $("#error_name").css({
                    'color': 'red'
                });
                alert("Please fill name.");
                validation = false;
                return false;
            } else if (!nameRegPattern.test(name)) {
                $("#name").focus();
                $("#error_name").text("Please fill name only characters.");
                $("#error_name").css({
                    'color': 'red'
                });
                alert("Please fill name only characters.");
                validation = false;
                return false;
            } else if (name.length < 3 || name.length > 100) {
                $("#name").focus();
                $("#error_name").text("Please fill name between minimum 3 and maximum 100 characters.");
                $("#error_name").css({
                    'color': 'red'
                });
                alert("Please fill name between minimum 3 and maximum 100 characters.");
                validation = false;
                return false;
            } else if (email == '') {
                $("#email").focus();
                $("#error_email").text("Please fill email.");
                $("#error_email").css({
                    'color': 'red'
                });
                alert("Please fill email.");
                validation = false;
                return false;
            } else if (email && !emailPattern.test(email)) {
                $("#email").focus();
                $("#error_email").text("Please fill valid  email");
                $("#error_email").css({
                    'color': 'red'
                });
                alert("Please fill valid email");
                validation = false;
                return false;
            } else if (mobile == '') {
                $("#mobile").focus();
                $("#error_mobile").text("Please fill mobile.");
                $("#error_mobile").css({
                    'color': 'red'
                });
                alert("Please fill mobile.");
                validation = false;
                return false;
            } else if (mobile && !mobilePattern.test(mobile)) {
                $("#mobile").focus();
                $("#error_mobile").text("Please fill valid  mobile No.");
                $("#error_mobile").css({
                    'color': 'red'
                });
                alert("Please fill valid mobile No.");
                validation = false;
                return false;
            } else if (bar_reg_no && !alphanumPattern.test(bar_reg_no)) {
                $("#bar_reg_no").focus();
                $("#error_bar_reg_no").text("Please fill valid  bar reg. no.");
                $("#error_bar_reg_no").css({
                    'color': 'red'
                });
                alert("Please fill valid bar reg no.");
                validation = false;
                return false;
            } else if (relation == '') {
                $("#relation").focus();
                $("#error_relation").text("Please select relation.");
                $("#error_relation").css({
                    'color': 'red'
                });
                alert("Please select relation.");
                validation = false;
                return false;
            } else if (relation_name == '') {
                $("#relation_name").focus();
                $("#error_relation_name").text("Please fill relative name.");
                $("#error_relation_name").css({
                    'color': 'red'
                });
                alert("Please fill relative name.");
                validation = false;
                return false;
            } else if (relation_name && !nameRegPattern.test(relation_name)) {
                $("#relation_name").focus();
                $("#error_relation_name").text("Please fill relative name only characters.");
                $("#error_relation_name").css({
                    'color': 'red'
                });
                alert("Please fill relative name only characters.");
                validation = false;
                return false;
            } else if ($("#aor").length > 0 && $("#aor option:selected").val() == '') {
                $("#aor").focus();
                $("#error_aor").text("Please fill aor name.");
                $("#error_aor").css({
                    'color': 'red'
                });
                alert("Please fill aor name.");
                validation = false;
                return false;
            } else if (c_address == '' && r_address == '') {
                $("#c_address").focus();
                $("#error_c_addresse").text("Please fill at least one address.");
                $("#error_c_addresse").css({
                    'color': 'red'
                });
                alert("Please fill at least one address.");
                validation = false;
                return false;
            } else if (c_address && !addressPattern.test(c_address)) {
                $("#c_address").focus();
                $("#error_c_address").text("Please fill address alphanumeric with space and comma.");
                $("#error_c_address").css({
                    'color': 'red'
                });
                alert("Please fill address alphanumeric with space and comma.");
                validation = false;
                return false;
            } else if (c_address && c_pincode == '') {
                $("#c_pincode").focus();
                $("#error_c_pincode").text("Please fill pincode.");
                $("#error_c_pincode").css({
                    'color': 'red'
                });
                alert("Please fill pincode.");
                validation = false;
                return false;
            } else if (c_address && !pincodePattern.test(c_pincode)) {
                $("#c_pincode").focus();
                $("#error_c_pincode").text("Please fill pincode numeric.");
                $("#error_c_pincode").css({
                    'color': 'red'
                });
                alert("Please fill pincode numeric.");
                validation = false;
                return false;
            } else if (c_address && c_city == '') {
                $("#c_city").focus();
                $("#error_c_city").text("Please fill city.");
                $("#error_cc_city").css({
                    'color': 'red'
                });
                alert("Please fill city.");
                validation = false;
                return false;
            } else if (c_address && !alphanumPattern.test(c_city)) {
                $("#c_city").focus();
                $("#error_c_city").text("Please fill city alphanumeric.");
                $("#error_cc_city").css({
                    'color': 'red'
                });
                alert("Please fill city alphanumeric.");
                validation = false;
                return false;
            } else if (c_address && c_state == '') {
                $("#c_state").focus();
                $("#error_c_state").text("Please select state.");
                $("#error_c_state").css({
                    'color': 'red'
                });
                alert("Please select state.");
                validation = false;
                return false;
            } else if (c_address && c_district == '') {
                $("#c_district").focus();
                $("#error_c_district").text("Please select district.");
                $("#error_c_district").css({
                    'color': 'red'
                });
                alert("Please select district.");
                validation = false;
                return false;
            } else if (r_address && !addressPattern.test(r_address)) {
                $("#r_address").focus();
                $("#error_r_address").text("Please fill address alphanumeric with space dot comma.");
                $("#error_r_address").css({
                    'color': 'red'
                });
                alert("Please fill address alphanumeric with space dot comma.");
                validation = false;
                return false;
            } else if (r_address && r_pincode == '') {
                $("#r_pincode").focus();
                $("#error_r_pincode").text("Please fill pincode.");
                $("#error_r_pincode").css({
                    'color': 'red'
                });
                alert("Please fill pincode.");
                validation = false;
                return false;
            } else if (r_address && !pincodePattern.test(r_pincode)) {
                $("#r_pincode").focus();
                $("#error_r_pincode").text("Please fill pincode numeric.");
                $("#error_r_pincode").css({
                    'color': 'red'
                });
                alert("Please fill pincode numeric.");
                validation = false;
                return false;
            } else if (r_address && r_city == '') {
                $("#r_city").focus();
                $("#error_r_city").text("Please fill city.");
                $("#error_r_city").css({
                    'color': 'red'
                });
                alert("Please fill city.");
                validation = false;
                return false;
            } else if (r_address && !alphanumPattern.test(r_city)) {
                $("#r_city").focus();
                $("#error_r_city").text("Please fill city alphanumric.");
                $("#error_r_city").css({
                    'color': 'red'
                });
                alert("Please fill city alphanumric.");
                validation = false;
                return false;
            } else if (r_address && r_state == '') {
                $("#r_state").focus();
                $("#error_r_state").text("Please select state.");
                $("#error_r_state").css({
                    'color': 'red'
                });
                alert("Please select state.");
                validation = false;
                return false;
            } else if (r_district && r_district == '') {
                $("#r_district").focus();
                $("#error_r_district").text("Please select district.");
                $("#error_r_district").css({
                    'color': 'red'
                });
                alert("Please select district.");
                validation = false;
                return false;
            } else if (bar_id_card == '') {
                $("#bar_id_card").focus();
                $("#error_bar_id_card").text("Please select bar registration id card.");
                $("#error_bar_id_card").css({
                    'color': 'red'
                });
                alert("Please select bar registration id card.");
                validation = false;
                return false;
            } else if (!fileReg.test(bar_id_card)) {
                $("#bar_id_card").focus();
                $("#error_bar_id_card").text("Please select bar registration id card type(pdf/jpeg)");
                $("#error_bar_id_card").css({
                    'color': 'red'
                });
                alert("Please select bar registration id card type(pdf/jpeg)");
                validation = false;
                return false;
            } else if (bar_id_card_size > bar_card_size) {
                var filesizeMb = ((bar_card_size) / 1024) / 1024;
                $("#bar_id_card").focus();
                $("#error_bar_id_card").text("Please select bar registration id card less than " + filesizeMb + "MB");
                $("#error_bar_id_card").css({
                    'color': 'red'
                });
                alert("Please select bar registration id card less than " + filesizeMb + "MB");
                validation = false;
                return false;
            } else if (validation) {
                $("#saveArguingCounselCompleteDetails").submit();
            }
        });
        $('input[name="same_as"]').click(function() {
            if ($(this).is(":checked")) {
                var c_address = $("#c_address").val();
                var c_pincode = $("#c_pincode").val();
                var c_city = $("#c_city").val();
                var c_state = $("#c_state option:selected").val();
                var c_district = $("#c_district").val();
                if (c_address && c_pincode && c_city && c_state && c_district) {
                    $("#r_address").val(c_address);
                    $("#r_address").prop('readonly', true);
                    $("#r_pincode").val(c_pincode).trigger('blur');
                    $("#r_pincode").prop('readonly', true);
                    // $("#r_city").prop('readonly', true);
                    $("#r_city").val(c_city);
                    $("#r_state").prop('readonly', true);
                    setTimeout(function() {
                        if ($("#r_district").val()) {
                            $("#r_district").prop('readonly', true);
                        } else {
                            $("#r_district").val($("#c_district").val());
                            $("#r_district").prop('readonly', true);
                        }
                    }, 1000);
                } else {
                    $('#same_as').prop('checked', false);
                    alert("Please fill all fields of chamber address.");
                }
            } else if ($(this).is(":not(:checked)")) {
                $("#r_address").val('');
                $("#r_address").prop('readonly', false);
                $("#r_pincode").val('');
                $("#r_pincode").prop('readonly', false);
                $("#r_city").val('');
                $("#r_city").prop('readonly', false);
                $("#r_state").val('');
                $("#r_state").prop('readonly', false);
                $("#r_district").val('');
                $("#r_district").prop('readonly', false);
            }
        });
        $(document).on('change', '#c_state', function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#c_district').val('');
            var get_state_id = $(this).val();
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    state_id: get_state_id
                },
                url: "<?php echo base_url('newcase/Ajaxcalls/get_districts'); ?>",
                success: function(data) {
                    $('#c_district').html(data);
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
        $(document).on('change', '#r_state', function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#r_district').val('');
            var get_state_id = $(this).val();
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    state_id: get_state_id
                },
                url: "<?php echo base_url('newcase/Ajaxcalls/get_districts'); ?>",
                success: function(data) {
                    $('#r_district').html(data);
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
        $('#c_pincode').blur(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var pincode = $("#c_pincode").val();
            if (pincode) {
                $.ajax({
                    type: "POST",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        pincode: pincode
                    },
                    url: "<?php echo base_url('newcase/Ajaxcalls/getAddressByPincode'); ?>",
                    success: function(response) {
                        var taluk_name;
                        var district_name;
                        var state;
                        if (response) {
                            var resData = JSON.parse(response);
                            if (resData) {
                                taluk_name = resData[0]['taluk_name'].trim().toUpperCase();
                                district_name = resData[0]['district_name'].trim().toUpperCase();
                                state = resData[0]['state'].trim().toUpperCase();
                            }
                            if (taluk_name) {
                                $("#c_city").val('');
                                $("#c_city").val(taluk_name);
                            } else {
                                $("#c_city").val('');
                            }
                            if (state) {
                                if (state_Arr) {
                                    var stateObj = JSON.parse(state_Arr);
                                }
                                if (stateObj) {
                                    var singleObj = stateObj.find(
                                        item => item['state_name'] === state
                                    );
                                }
                                if (singleObj) {
                                    $('#c_state').val('');
                                    // $('#c_state').val(singleObj.id).select2().trigger("change");
                                    $('#c_state').val(singleObj.id);
                                } else {
                                    $('#c_state').val('');
                                }
                                if (district_name) {
                                    var stateId = $('#c_state').val();
                                    setDistrictUser(stateId, district_name);
                                }
                            } else {
                                $('#c_state').val('');
                            }
                        }
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
        });
        $('#r_pincode').blur(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var pincode = $("#r_pincode").val();
            if (pincode) {
                $.ajax({
                    type: "POST",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        pincode: pincode
                    },
                    url: "<?php echo base_url('newcase/Ajaxcalls/getAddressByPincode'); ?>",
                    success: function(response) {
                        var taluk_name;
                        var district_name;
                        var state;
                        if (response) {
                            var resData = JSON.parse(response);
                            if (resData) {
                                taluk_name = resData[0]['taluk_name'].trim().toUpperCase();
                                district_name = resData[0]['district_name'].trim().toUpperCase();
                                state = resData[0]['state'].trim().toUpperCase();
                            }
                            if (taluk_name) {
                                $("#r_city").val('');
                                $("#r_city").val(taluk_name);
                            } else {
                                $("#r_city").val('');
                            }
                            if (state) {
                                if (state_Arr) {
                                    var stateObj = JSON.parse(state_Arr);
                                }
                                if (stateObj) {
                                    var singleObj = stateObj.find(
                                        item => item['state_name'] === state
                                    );
                                }
                                if (singleObj) {
                                    $('#r_state').val('');
                                    // $('#r_state').val(singleObj.id).select2().trigger("change");
                                    $('#r_state').val(singleObj.id);
                                } else {
                                    $('#r_state').val('');
                                }
                                if (district_name) {
                                    var stateId = $('#r_state').val();
                                    setDistrictUser1(stateId, district_name);
                                }
                            } else {
                                $('#r_state').val('');
                            }
                        }
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
        });
        $(document).on('click', '#selectAll', function() {
            $('.selectAllcheckBox').each(function(index, obj) {
                if (this.checked === true) {
                    $(this).attr('checked', false);
                    $("#selectSpan").text("Select All");
                } else {
                    $(this).attr('checked', true);
                    $("#selectSpan").text("Deselect All");
                }
            });

        });
        $(document).on('click', '.actionType', function() {
            var type = $.trim($(this).attr('id'));
            var userIdArr = [];
            $('.selectAllcheckBox').each(function(index, obj) {
                if (this.checked === true) {
                    userIdArr.push($(this).attr('data-userid'));
                }
            });
            if (userIdArr.length === 0) {
                alert("Please select checkbox.");
                $("#checkbox_1").focus();
                $("#checkbox_1").css({
                    'border-color': 'red'
                });
                return false;
            } else if (confirm("Are you sure to advocate " + type + ' ?') == true) {
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                //var postData = {CSRF_TOKEN: CSRF_TOKEN_VALUE,userIdArr:userIdArr,type:type };
                $.ajax({
                    type: "POST",
                    //data: JSON.stringify(postData),
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        userIdArr: userIdArr,
                        type: type
                    },
                    url: "<?php echo base_url('register/ArguingCounsel/approveRejectedArguingCounsel'); ?>",
                    //async:false,
                    //cache:false,
                    //dataType:'json',
                    //ContentType: 'application/json',
                    success: function(res) {
                        if (res && typeof res == 'string') {
                            res = JSON.parse(res);
                        }
                        if (res.success = 'success') {
                            alert(res.message);
                            window.location.reload();
                        } else if (res.error = 'error') {
                            alert(res.message);
                            window.location.reload();
                        }
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

        });


    });

    function setDistrictUser(stateId, district_name) {
        if (stateId && district_name) {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    state_id: stateId
                },
                url: "<?php echo base_url('newcase/Ajaxcalls/getSelectedDistricts'); ?>",
                success: function(resData) {
                    if (resData) {
                        var districtObj = JSON.parse(resData);
                        var singleObj = districtObj.find(
                            item => item['district_name'] === district_name
                        );
                        var dist = '<option value="">Select District</option>';
                        districtObj.forEach(function(ele) {
                            dist += '<option value="' + ele.id + '">' + ele.district_name + '</option>';
                        });
                        $('#c_district').html(dist);
                        if (singleObj) {
                            // $('#c_district').val('');
                            // $('#c_district').val(singleObj.id).select2().trigger("change");
                            $('#c_district').val(singleObj.id);
                        } else {
                            $('#c_district').val('');
                        }
                    } else {
                        $('#c_district').val('');
                    }
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
    }

    function setDistrictUser1(stateId, district_name) {
        if (stateId && district_name) {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    state_id: stateId
                },
                url: "<?php echo base_url('newcase/Ajaxcalls/getSelectedDistricts'); ?>",
                success: function(resData) {
                    if (resData) {
                        var districtObj = JSON.parse(resData);
                        var singleObj = districtObj.find(
                            item => item['district_name'] === district_name
                        );
                        var dist = '<option value="">Select District</option>';
                        districtObj.forEach(function(ele) {
                            dist += '<option value="' + ele.id + '">' + ele.district_name + '</option>';
                        });
                        $('#r_district').html(dist);
                        if (singleObj) {
                            // $('#c_district').val('');
                            // $('#c_district').val(singleObj.id).select2().trigger("change");
                            $('#r_district').val(singleObj.id);
                        } else {
                            $('#r_district').val('');
                        }
                    } else {
                        $('#r_district').val('');
                    }
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
    }

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
</script>
<script>
    /*function validateImage() {
    var fileInput = document.getElementById('advocate_image');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg)$/i;
    var maxSize = 1 * 1024 * 1024; // 2MB in bytes
    
    // Check file type
    if (!allowedExtensions.exec(filePath)) {
        alert('Please upload a file with .jpg or .jpeg extension.');
        fileInput.value = ''; // Clear the input
        return false;
    }
    
    // Check file size
    if (fileInput.files[0].size > maxSize) {
        alert('The file size should not exceed 1 MB.');
        fileInput.value = ''; // Clear the input
        return false;
    }
}*/

    let preview_display = document.getElementById("cam_preview");
    let recording_display = document.getElementById("view_recording");
    let preview = document.getElementById("preview");
    let recording = document.getElementById("recording");
    let startButton = document.getElementById("startButton");
    let tryAgain = document.getElementById("tryAgain");
    let stopButton = document.getElementById("stopButton");
    let downloadButton = document.getElementById("downloadButton");
    let logElement = document.getElementById("log");

    let myfile = document.getElementById("myfile");
    let recordedBlob = '';
    recording_display.style.visibility = "hidden";
    let recordingTimeMS = 10000;

    function log(msg) {
        logElement.innerHTML += msg + "\n";
    }

    function wait(delayInMS) {
        return new Promise(resolve => setTimeout(resolve, delayInMS));
    }

    function startRecording(stream, lengthInMS) {
        let recorder = new MediaRecorder(stream);
        let data = [];

        recorder.ondataavailable = event => data.push(event.data);
        recorder.start();
        log(recorder.state + " for " + (lengthInMS / 1000) + " seconds...");

        let stopped = new Promise((resolve, reject) => {
            recorder.onstop = resolve;
            recorder.onerror = event => reject(event.name);
        });

        let recorded = wait(lengthInMS).then(
            () => recorder.state == "recording" && recorder.stop()
        );

        return Promise.all([
                stopped,
                recorded
            ])
            .then(() => data);
    }

    function stop(stream) {
        stream.getTracks().forEach(track => track.stop());
    }
    startButton.addEventListener("click", function() {
        $("#cam_preview").show();
        $("#startButton").hide();
        //$("#stopButton").show();

        navigator.mediaDevices.getUserMedia({
                video: true,
                audio: true
            }).then(stream => {
                preview.srcObject = stream;
                downloadButton.href = stream;
                preview.captureStream = preview.captureStream || preview.mozCaptureStream;
                return new Promise(resolve => preview.onplaying = resolve);
            }).then(() => startRecording(preview.captureStream(), recordingTimeMS))
            .then(recordedChunks => {
                // preview_display.style.visibility = "hidden";
                // recording_display.style.visibility = "visible";
                preview_display.style.visibility = "hidden";
                preview_display.style.display = "none";
                recording_display.style.visibility = "visible";
                recording_display.style.display = "block";
                stopButton.style.display = "block";
                tryAgain.style.display = "block";
                $("#cam_preview").hide();
                $("#view_recording").show();
                $("#video_action").show();
                recordedBlob = new Blob(recordedChunks, {
                    type: "video/webm"
                });

                recording.src = URL.createObjectURL(recordedBlob);
                recording.autoplay = true;
                downloadButton.href = recording.src;
                downloadButton.download = "RecordedVideo.webm";
                log("Successfully recorded " + recordedBlob.size + " bytes of " + recordedBlob.type + " media.");
                //log("Successfully recorded");
                $('#saveButton').show();
                // sendBlobToServer(recordedBlob);
            })
            .catch(err => {
                log("Error: " + err.message);
                // Reset UI on error
                preview_display.style.visibility = "visible";
                preview_display.style.display = "block";
                recording_display.style.visibility = "hidden";
                recording_display.style.display = "none";
                startButton.style.display = "block";
            });
    }, false);

    tryAgain.addEventListener("click", function() {
        // Reset UI elements
        preview_display.style.visibility = "visible";
        preview_display.style.display = "block";
        recording_display.style.visibility = "hidden";
        recording_display.style.display = "none";
        startButton.style.display = "block";
        stopButton.style.display = "none";
        tryAgain.style.display = "none";
        $('#saveButton').hide();
        // Clear existing video source
        recording.src = "";
        if (preview.srcObject) {
            stop(preview.srcObject);
            preview.srcObject = null;
        }

        // Clear recorded blob and log
        recordedBlob = '';
        logElement.innerHTML = '';
    }, false);

    // tryAgain.addEventListener("click", function() {
    //     $("#cam_preview").show();
    //     $("#startButton").hide();
    //     //$("#stopButton").show();

    //     navigator.mediaDevices.getUserMedia({
    //             video: true,
    //             audio: true
    //         }).then(stream => {
    //             preview.srcObject = stream;
    //             downloadButton.href = stream;
    //             preview.captureStream = preview.captureStream || preview.mozCaptureStream;
    //             return new Promise(resolve => preview.onplaying = resolve);
    //         }).then(() => startRecording(preview.captureStream(), recordingTimeMS))
    //         .then(recordedChunks => {
    //             preview_display.style.visibility = "hidden";
    //             recording_display.style.visibility = "visible";
    //             $("#cam_preview").hide();
    //             $("#view_recording").show();
    //             $("#video_action").show();
    //             recordedBlob = new Blob(recordedChunks, {
    //                 type: "video/webm"
    //             });

    //             recording.src = URL.createObjectURL(recordedBlob);
    //             downloadButton.href = recording.src;
    //             downloadButton.download = "RecordedVideo.webm";
    //             log("Successfully recorded " + recordedBlob.size + " bytes of " + recordedBlob.type + " media.");
    //             //log("Successfully recorded");

    //             // sendBlobToServer(recordedBlob);
    //         })
    //         .catch(log);
    // }, false);

    stopButton.addEventListener("click", function() {
        stop(preview.srcObject);
        $('#saveButton').show();
        $('#finalBtn').hide();
    }, false);



    $(document).on("click", "#saveButton", function() {

        var nameRegPattern = /^[a-zA-Z‘_ ]+$/i;
        var emailPattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        var mobilePattern = /^\d{10}$/;
        var pincodePattern = /^\d{6}$/;
        var alphanumPattern = /^[a-zA-Z0-9 ]+$/i;
        var addressPattern = /^[a-zA-Z0-9\s,'-.]*$/i;
        var validation = true;
        var name = $.trim($("#name").val());
        var email = $.trim($("#email").val());
        var mobile = $.trim($("#mobile").val());
        var bar_reg_no = $.trim($("#bar_reg_no").val());
        var bar_id_card = $("#bar_id_card").val();
        var fileReg = /(.*?)\.(pdf|jpeg|jpg)$/;
        var relation = $("#relation option:selected").val();
        var relation_name = $("#relation_name").val();
        var bar_card_size = "<?php echo BAR_ID_CARD_SIZE; ?>";
        var c_address = $.trim($("textarea#c_address").val());
        var r_address = $.trim($("textarea#r_address").val());
        var c_pincode = $("#c_pincode").val();
        var c_city = $("#c_city").val();
        var c_state = $("#c_state option:selected").val();
        var c_district = $("#c_district").val();
        var r_pincode = $("#r_pincode").val();
        var r_city = $("#r_city").val();
        var r_state = $("#r_state option:selected").val();
        var r_district = $("#r_district").val();
        var bar_id_card_size = '';
        //  $("#r_district").prop('disabled', false);
        // $("#r_state").prop('disabled', false);
        if (bar_id_card) {
            bar_id_card_size = document.getElementById('bar_id_card').files[0]['size'];
        }
        if (name == '') {
            $("#name").focus();
            $("#error_name").text("Please fill name.");
            $("#error_name").css({
                'color': 'red'
            });
            alert("Please fill name.");
            validation = false;
            return false;
        } else if (!nameRegPattern.test(name)) {
            $("#name").focus();
            $("#error_name").text("Please fill name only characters.");
            $("#error_name").css({
                'color': 'red'
            });
            alert("Please fill name only characters.");
            validation = false;
            return false;
        } else if (name.length < 3 || name.length > 100) {
            $("#name").focus();
            $("#error_name").text("Please fill name between minimum 3 and maximum 100 characters.");
            $("#error_name").css({
                'color': 'red'
            });
            alert("Please fill name between minimum 3 and maximum 100 characters.");
            validation = false;
            return false;
        } else if (email == '') {
            $("#email").focus();
            $("#error_email").text("Please fill email.");
            $("#error_email").css({
                'color': 'red'
            });
            alert("Please fill email.");
            validation = false;
            return false;
        } else if (email && !emailPattern.test(email)) {
            $("#email").focus();
            $("#error_email").text("Please fill valid  email");
            $("#error_email").css({
                'color': 'red'
            });
            alert("Please fill valid email");
            validation = false;
            return false;
        } else if (mobile == '') {
            $("#mobile").focus();
            $("#error_mobile").text("Please fill mobile.");
            $("#error_mobile").css({
                'color': 'red'
            });
            alert("Please fill mobile.");
            validation = false;
            return false;
        } else if (mobile && !mobilePattern.test(mobile)) {
            $("#mobile").focus();
            $("#error_mobile").text("Please fill valid  mobile No.");
            $("#error_mobile").css({
                'color': 'red'
            });
            alert("Please fill valid mobile No.");
            validation = false;
            return false;
        } else if (bar_reg_no && !alphanumPattern.test(bar_reg_no)) {
            $("#bar_reg_no").focus();
            $("#error_bar_reg_no").text("Please fill valid  bar reg. no.");
            $("#error_bar_reg_no").css({
                'color': 'red'
            });
            alert("Please fill valid bar reg no.");
            validation = false;
            return false;
        } else if (relation == '') {
            $("#relation").focus();
            $("#error_relation").text("Please select relation.");
            $("#error_relation").css({
                'color': 'red'
            });
            alert("Please select relation.");
            validation = false;
            return false;
        } else if (relation_name == '') {
            $("#relation_name").focus();
            $("#error_relation_name").text("Please fill relative name.");
            $("#error_relation_name").css({
                'color': 'red'
            });
            alert("Please fill relative name.");
            validation = false;
            return false;
        } else if (relation_name && !nameRegPattern.test(relation_name)) {
            $("#relation_name").focus();
            $("#error_relation_name").text("Please fill relative name only characters.");
            $("#error_relation_name").css({
                'color': 'red'
            });
            alert("Please fill relative name only characters.");
            validation = false;
            return false;
        } else if ($("#aor").length > 0 && $("#aor option:selected").val() == '') {
            $("#aor").focus();
            $("#error_aor").text("Please fill aor name.");
            $("#error_aor").css({
                'color': 'red'
            });
            alert("Please fill aor name.");
            validation = false;
            return false;
        } else if (c_address == '' && r_address == '') {
            $("#c_address").focus();
            $("#error_c_addresse").text("Please fill at least one address.");
            $("#error_c_addresse").css({
                'color': 'red'
            });
            alert("Please fill at least one address.");
            validation = false;
            return false;
        } else if (c_address && !addressPattern.test(c_address)) {
            $("#c_address").focus();
            $("#error_c_address").text("Please fill address alphanumeric with space and comma.");
            $("#error_c_address").css({
                'color': 'red'
            });
            alert("Please fill address alphanumeric with space and comma.");
            validation = false;
            return false;
        } else if (c_address && c_pincode == '') {
            $("#c_pincode").focus();
            $("#error_c_pincode").text("Please fill pincode.");
            $("#error_c_pincode").css({
                'color': 'red'
            });
            alert("Please fill pincode.");
            validation = false;
            return false;
        } else if (c_address && !pincodePattern.test(c_pincode)) {
            $("#c_pincode").focus();
            $("#error_c_pincode").text("Please fill pincode numeric.");
            $("#error_c_pincode").css({
                'color': 'red'
            });
            alert("Please fill pincode numeric.");
            validation = false;
            return false;
        } else if (c_address && c_city == '') {
            $("#c_city").focus();
            $("#error_c_city").text("Please fill city.");
            $("#error_cc_city").css({
                'color': 'red'
            });
            alert("Please fill city.");
            validation = false;
            return false;
        } else if (c_address && !alphanumPattern.test(c_city)) {
            $("#c_city").focus();
            $("#error_c_city").text("Please fill city alphanumeric.");
            $("#error_cc_city").css({
                'color': 'red'
            });
            alert("Please fill city alphanumeric.");
            validation = false;
            return false;
        } else if (c_address && c_state == '') {
            $("#c_state").focus();
            $("#error_c_state").text("Please select state.");
            $("#error_c_state").css({
                'color': 'red'
            });
            alert("Please select state.");
            validation = false;
            return false;
        } else if (c_address && c_district == '') {
            $("#c_district").focus();
            $("#error_c_district").text("Please select district.");
            $("#error_c_district").css({
                'color': 'red'
            });
            alert("Please select district.");
            validation = false;
            return false;
        } else if (r_address && !addressPattern.test(r_address)) {
            $("#r_address").focus();
            $("#error_r_address").text("Please fill address alphanumeric with space dot comma.");
            $("#error_r_address").css({
                'color': 'red'
            });
            alert("Please fill address alphanumeric with space dot comma.");
            validation = false;
            return false;
        } else if (r_address && r_pincode == '') {
            $("#r_pincode").focus();
            $("#error_r_pincode").text("Please fill pincode.");
            $("#error_r_pincode").css({
                'color': 'red'
            });
            alert("Please fill pincode.");
            validation = false;
            return false;
        } else if (r_address && !pincodePattern.test(r_pincode)) {
            $("#r_pincode").focus();
            $("#error_r_pincode").text("Please fill pincode numeric.");
            $("#error_r_pincode").css({
                'color': 'red'
            });
            alert("Please fill pincode numeric.");
            validation = false;
            return false;
        } else if (r_address && r_city == '') {
            $("#r_city").focus();
            $("#error_r_city").text("Please fill city.");
            $("#error_r_city").css({
                'color': 'red'
            });
            alert("Please fill city.");
            validation = false;
            return false;
        } else if (r_address && !alphanumPattern.test(r_city)) {
            $("#r_city").focus();
            $("#error_r_city").text("Please fill city alphanumric.");
            $("#error_r_city").css({
                'color': 'red'
            });
            alert("Please fill city alphanumric.");
            validation = false;
            return false;
        } else if (r_address && r_state == '') {
            $("#r_state").focus();
            $("#error_r_state").text("Please select state.");
            $("#error_r_state").css({
                'color': 'red'
            });
            alert("Please select state.");
            validation = false;
            return false;
        } else if (r_district && r_district == '') {
            $("#r_district").focus();
            $("#error_r_district").text("Please select district.");
            $("#error_r_district").css({
                'color': 'red'
            });
            alert("Please select district.");
            validation = false;
            return false;
        } else if (bar_id_card == '') {
            $("#bar_id_card").focus();
            $("#error_bar_id_card").text("Please select bar registration id card.");
            $("#error_bar_id_card").css({
                'color': 'red'
            });
            alert("Please select bar registration id card.");
            validation = false;
            return false;
        } else if (!fileReg.test(bar_id_card)) {
            $("#bar_id_card").focus();
            $("#error_bar_id_card").text("Please select bar registration id card type(pdf/jpeg)");
            $("#error_bar_id_card").css({
                'color': 'red'
            });
            alert("Please select bar registration id card type(pdf/jpeg)");
            validation = false;
            return false;
        } else if (bar_id_card_size > bar_card_size) {
            var filesizeMb = ((bar_card_size) / 1024) / 1024;
            $("#bar_id_card").focus();
            $("#error_bar_id_card").text("Please select bar registration id card less than " + filesizeMb + "MB");
            $("#error_bar_id_card").css({
                'color': 'red'
            });
            alert("Please select bar registration id card less than " + filesizeMb + "MB");
            validation = false;
            return false;
        } else if (validation) {
            var filename = "RecordedVideo.webm";
            //var filewebm = $('#speech_recorded')[0].files[0];
            //console.log('webmfile',filewebm);
            var fd1 = new FormData();
            fd1.append("video", recordedBlob, filename);
            fd1.append("name", $('#name').val());
            fd1.append("email", $('#email').val());
            fd1.append("mobile", $('#mobile').val());
            fd1.append("relation", $('#relation').val());
            fd1.append("relation_name", $('#relation_name').val());
            fd1.append("c_address", $('#c_address').val());
            fd1.append("bar_id_card", $('#bar_id_card')[0].files[0]);
            fd1.append("bar_reg_no", $('#bar_reg_no').val());
            fd1.append("c_pincode", $('#c_pincode').val());
            fd1.append("c_state", $('#c_state').val());
            fd1.append("c_district", $('#c_district').val());
            fd1.append("c_city", $('#c_city').val());
            fd1.append("r_address", $('#r_address').val());
            fd1.append("r_pincode", $('#r_pincode').val());
            fd1.append("isOnlineCopying", $('#ecopyingCheck').val());
            fd1.append("r_city", $('#r_city').val());
            fd1.append("r_state", $('#r_state').val());
            fd1.append("r_district", $('#r_district').val());
            fd1.append("aor", $('#aor').val());

            $.ajax({
                url: "<?php echo base_url('saveArguingCounselCompleteDetailsForOnlineCoping'); ?>",
                //dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                data: fd1,
                type: 'post',
                beforeSend: function() {
                    $('.show_msg').html('<table widht="100%" align="center"><tr><td>Loading...</td></tr></table>');
                },
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                success: function(data) {
                    location.reload();
                }
            });
        }


    });
    $(document).on('click', '#ecopyingCheck', function() {
        if ($(this).is(":checked")) {
            $('.left').show();
            $('#saveButton').show();
            $('#register').hide();
        } else {
            $('.left').hide();
            $('#register').show();
            $('#saveButton').hide();
        }

    })
</script>
@endsection