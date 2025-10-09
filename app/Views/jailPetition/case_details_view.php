
<div class="panel panel-default">
    <h4 style="text-align: center;color: #31B0D5"> Case Details </h4>
    <div class="panel-body">
        <?php
        $attribute = array('class' => 'form-horizontal', 'name' => 'add_case_details', 'id' => 'add_case_details', 'autocomplete' => 'off');
        echo form_open('#', $attribute);

        $cause_title = explode(' Vs. ', $new_case_details[0]['cause_title']);
        ?>
<div class="row" id="prisoner_list_div">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Select Petitioner/Prisoner<span style="color: red">*</span>:</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                    <select tabindex = '1' name="prisoner_list" id="prisoner_list" class="form-control input-sm filter_select_dropdown">
                        <option value="" title="Select">Select Prisoner</option>
                    </select>
            </div>
        </div>
    </div>
    <div class="col-sm-1 col-xs-12">
        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">OR</label>
    </div>
    <div class="col-sm-5 col-xs-12">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Enter Prisoner Id<span style="color: red">*</span>:</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <div class="input-group">
                    <input tabindex = '2'  id="prisoner_id" name="prisoner_id" pattern="^[A-Z][0-9]$" placeholder="Prisoner Id"  class="form-control input-sm age_calculate" type="text">
                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter Prisoner Id ">
                                <i class="fa fa-question-circle-o"  ></i>
                            </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 ">
        <div class="col-sm-4 col-xs-12 col-md-offset-5">
            <div class="form-group">
                <div class="col-md-offset-3">
                    <input tabindex = '3' type="button" id="search_prisoner" name="search_prisoner" value="Search" class="info btn-sm">
                </div>
            </div>
        </div>
    </div>
</div>
        <hr/>
<div class="row">
    <label class="control-label col-xs-12" style="font-size: large; text-align: center">PETITIONER DETAILS</label>
</div>
        <br/>
<div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div id="indvidual_form">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Petitioner Name
                                    <span style="color: red">*</span></label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <textarea tabindex = '4' id="petitioner_name" name="petitioner_name" minlength="3" maxlength="99" class="form-control input-sm" placeholder="First Name Middle Name Last Name"  type="text"><?php echo_data($petitioner_details[0]['party_name']); ?></textarea>
                                        <span class="input-group-addon" data-placement="bottom"  data-toggle="popover" data-content="Petitioner name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                        <i class="fa fa-question-circle-o" ></i>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Relation <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <?php
                                    $selectSon = $petitioner_details[0]['relation'] == 'S' ? 'selected=selected' : '';
                                    $selectDaughter = $petitioner_details[0]['relation'] == 'D' ? 'selected=selected' : '';
                                    $selectWife = $petitioner_details[0]['relation'] == 'W' ? 'selected=selected' : '';
                                    $selectNotAvailable = $petitioner_details[0]['relation'] == 'N' ? 'selected=selected' : '';
                                    ?>
                                    <select tabindex = '5' name="relation" id="relation" class="form-control input-sm filter_select_dropdown" style="width: 100%" >
                                        <option value="" title="Select">Select Relation</option>
                                        <option <?php echo $selectSon; ?> value="S">Son Of</option>
                                        <option <?php echo $selectDaughter; ?> value="D">Daughter Of</option>
                                        <option <?php echo $selectWife; ?> value="W">Spouse Of</option>
                                        <option <?php echo $selectNotAvailable; ?> value="N">Not Available</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12" id="rel_name">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">
                                    Parent/Spouse Name <span style="color: red">*</span> :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input tabindex = '6' id="relative_name" name="relative_name" onkeyup="return isLetter(event);" minlength="3" maxlength="99" placeholder="Name of Parent or Husband"  value="<?php echo_data($petitioner_details[0]['relative_name']); ?>" class="form-control input-sm"  type="text">
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please write name of father or mother or husband or other relative. Relative Name should be in characters ( only dot[.] and space are allowed ).">
                                        <i class="fa fa-question-circle-o" ></i>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Date of Birth :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input tabindex = '7' class="form-control has-feedback-left" id="petitioner_dob" name="petitioner_dob" value="<?php echo_data($petitioner_details[0]['party_dob'] ? date('d/m/Y', strtotime($petitioner_details[0]['party_dob'])) : ''); ?>" maxlength="10"  placeholder="DD/MM/YYYY"  type="text">
                                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Date of Birth.">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Age <span style="color: red">*</span> :</label>
                                <div class="col-sm-2 col-md-4">
                                    <div class="input-group">
                                        <?php
                                        if ($petitioner_details[0]['party_age'] == 0 || $petitioner_details[0]['party_age'] == '' || $petitioner_details[0]['party_age'] == NULL) {
                                            $party_age = '';
                                        } else {
                                            $petitioner_age = $petitioner_details[0]['party_age'];
                                        }
                                        ?>
                                        <input tabindex = '8' id="petitioner_age" name="petitioner_age" maxlength="2" onkeyup="return isNumber(event)" placeholder="Age" value="<?php echo_data($petitioner_age); ?>" class="form-control input-sm age_calculate" type="text">
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Approx. age in years only.">
                                        <i class="fa fa-question-circle-o"  ></i>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-sm-5 input-sm">Gender <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <?php
                                    $gmchecked = $petitioner_details[0]['gender'] == 'M' ? 'checked="checked"' : '';
                                    $gfchecked = $petitioner_details[0]['gender'] == 'F' ? 'checked="checked"' : '';
                                    $gochecked = $petitioner_details[0]['gender'] == 'O' ? 'checked="checked"' : '';
                                    ?>
                                    <label class="radio-inline"><input tabindex = '9' type="radio" name="petitioner_gender" id="petitioner_gender1" value="M" <?php echo $gmchecked; ?>>Male</label>
                                    <label class="radio-inline"><input tabindex = '10' type="radio" name="petitioner_gender" id="petitioner_gender2" value="F" <?php echo $gfchecked; ?>>Female</label>
                                    <label class="radio-inline"><input tabindex = '11' type="radio" name="petitioner_gender" id="petitioner_gender3" value="O" <?php echo $gochecked; ?>>Other</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="col-sm-6 col-xs-12">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Email :</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input id="petitioner_email" name="petitioner_email" placeholder="Email" tabindex = '12' value="<?php echo_data($petitioner_details[0]['email_id']); ?>" class="form-control input-sm" type="email" minlength="12" maxlength="50">
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter Petitioner valid email id. (eg : abc@example.com)">
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
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Mobile :</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input id="petitioner_mobile" name="petitioner_mobile" onkeyup="return isNumber(event)" tabindex = '13' placeholder="Mobile" value="<?php echo_data($petitioner_details[0]['mobile_num']); ?>" class="form-control input-sm" type="text" minlength="10" maxlength="10">
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
                                    <textarea name="petitioner_address" id="petitioner_address"  tabindex = '14' placeholder="H.No.,  Street no, Colony,  Land Mark" class="form-control input-sm"><?php echo_data($petitioner_details[0]['address']); ?></textarea>
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
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> State <span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <select name="petitioner_state" id="petitioner_state"  tabindex = '15' class="form-control input-sm filter_select_dropdown">
                                    <option value="" title="Select">Select State</option>
                                    <?php
                                    if (count($state_list)) {
                                        foreach ($state_list as $dataRes) {
                                            $sel = ($petitioner_details[0]['state_id'] == $dataRes->cmis_state_id ) ? "selected=selected" : '';
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
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> District <span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <select name="petitioner_district" id="petitioner_district" tabindex = '16' class="form-control input-sm filter_select_dropdown petitioner_district">
                                    <option value="" title="Select">Select District</option>
                                    <?php
                                    if (count($district_list)) {
                                        foreach ($district_list as $dataRes) {
                                            $sel = ($petitioner_details[0]['district_id'] ==  $dataRes->id_no) ? 'selected=selected' : '';
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
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">PIN Code :</label>
                            <div class="col-sm-7 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input id="petitioner_pincode" name="petitioner_pincode" tabindex = '17' onkeyup="return isNumber(event)" placeholder="Pincode" value="<?php echo_data($petitioner_details[0]['pincode']); ?>" class="form-control input-sm" type="text" minlength="6" maxlength="6">
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Pincode should be 6 digits only.">
                                    <i class="fa fa-question-circle-o"></i>
                                    <a href="https://www.indiapost.gov.in/vas/pages/findpincode.aspx" target="_blank">Pin Code Locator</a>
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</div>
        <hr/>
        <div class="row">
            <label class="control-label col-xs-12" style="font-size: large; text-align: center">RESPONDENT DETAILS</label>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div id="indvidual_form">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Respondent Name
                                    <span style="color: red">*</span></label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <textarea tabindex = '18' id="respondent_name" name="respondent_name" minlength="3" maxlength="99" class="form-control input-sm" placeholder="First Name Middle Name Last Name"  type="text"><?php echo_data($respondent_details[0]['org_state_name']); ?></textarea>
                                        <span class="input-group-addon" data-placement="bottom"  data-toggle="popover" data-content="Respondent name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                        <i class="fa fa-question-circle-o" ></i>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div id="indvidual_form">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> State <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <select name="respondent_state" id="respondent_state"  tabindex = '19' class="form-control input-sm filter_select_dropdown">
                                        <option value="" title="Select">Select State</option>
                                        <?php
                                        if (count($state_list)) {
                                            foreach ($state_list as $dataRes) {
                                                $sel = ($respondent_details[0]['org_state_id'] == $dataRes->cmis_state_id ) ? "selected=selected" : '';
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
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <label class="control-label col-xs-12" style="font-size: large; text-align: center">EARLIER PETITIONS/REQUESTS</label>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="indvidual_form">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Had Similar petitions/requests filed earlier? <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <?php
                                    $anchecked = $new_case_details[0]['earlier_applies'] == 'N' ? 'checked="checked"' : '';
                                    $aychecked = $new_case_details[0]['earlier_applies'] == 'Y' ? 'checked="checked"' : '';
                                    ?>
                                    <label class="radio-inline"><input tabindex = '20' type="radio" name="earlier_applies" id="earlier_applies1" value="N" <?php echo $anchecked; ?>>No</label>
                                    <label class="radio-inline"><input tabindex = '21' type="radio" name="earlier_applies" id="earlier_applies2" value="Y" <?php echo $aychecked; ?>>Yes</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">

                <?php if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) { ?>
                    <input type="submit" tabindex = '23' class="btn btn-success" id="pet_save" value="UPDATE">
                    <script>$('#prisoner_list_div').hide();</script>
                    <a href="<?= base_url('jailPetition/Extra_petitioner') ?>" class="btn btn-primary btnNext" tabindex='24' type="button">Next</a>
                <?php } else { ?>
                    <input tabindex = '22' type="submit"  class="btn btn-success" id="pet_save" value="SAVE">
                <?php } ?>
            </div>
        </div>
        <?php echo form_close();
        ?>  
    </div>
</div>
<script type="text/javascript">
function get_prisoner_list()
{
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        type: "POST",
        data: {CSRF_TOKEN: CSRF_TOKEN_VALUE},
        url: "<?php echo base_url('jailPetition/BasicDetails/prisonerList'); ?>",
       success: function (data)
        {
            $('#prisoner_list').html(data);
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
function isEmpty(obj) {
    if (obj == null) return true;
    if (obj.length > 0)    return false;
    if (obj.length === 0)  return true;
    if (typeof obj !== "object") return true;

    // Otherwise, does it have any properties of its own?
    // Note that this doesn't handle
    // toString and valueOf enumeration bugs in IE < 9
    for (var key in obj) {
        if (hasOwnProperty.call(obj, key)) return false;
    }

    return true;
}

$('#prisoner_id').on('input', function () {
    if( $.trim($('#prisoner_id').val())=='')
        $("#prisoner_list").prop("disabled", false);
    else
        $("#prisoner_list").prop("disabled", true);
});

$('#search_prisoner').click(function () {
    var prisoner_id_select = $("#prisoner_list option:selected").val();
    var prisoner_id_text=$("#prisoner_id").val();
    if(!isEmpty(prisoner_id_select))
        prisonerId=prisoner_id_select;
    else if(!isEmpty(prisoner_id_text))
            prisonerId=prisoner_id_text;
    $.ajax({
        type: "POST",
        data:{prisonerId: prisonerId},
        url:"<?php echo base_url('jailPetition/BasicDetails/prisonerDetail'); ?>",
        success: function (data){
            var obj =$.parseJSON(data);
            console.log(obj);
            $('#petitioner_name').val(obj.prisoner_name);
            $('#relative_name').val(obj.father_name);
            $('#petitioner_age').val(obj.age);
            $('#petitioner_address').val(obj.present_address);
            $('#petitioner_dob').val(obj.dob);
            if(obj.sex_code=='M') {
                $('input[name=petitioner_gender][value="M"]').prop('checked', true);
                $("#relation").select2().select2('val','S');
            }
            else if(obj.sex_code=='F'){
                $('input[name=petitioner_gender][value="M"]').prop('checked', true);
                $("#relation").select2().select2('val','D');
            }
            else if(obj.sex_code=='O')
            {
                $('input[name=petitioner_gender][value="M"]').prop('checked', true);
                $("#relation").select2().select2('val','S');
            }
        }
    });
});


$('#petitioner_state').change(function () {

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $('#petitioner_district').val('');

    var get_state_id = $(this).val();
    $.ajax({
        type: "POST",
        data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
        url: "<?php echo base_url('newcase/Ajaxcalls/get_districts'); ?>",
        success: function (data)
        {
            $('.petitioner_district').html(data);
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


    $(document).ready(function () {
        get_prisoner_list();


        $('#add_case_details').on('submit', function () {
            if ($('#add_case_details').valid()) {
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>jailPetition/BasicDetails/add_case_details",
                    data: form_data,
                    async: false,
                    beforeSend: function () {
                        $('#pet_save').val('Please wait...');
                        $('#pet_save').prop('disabled', true);
                    },
                    success: function (data) {
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
