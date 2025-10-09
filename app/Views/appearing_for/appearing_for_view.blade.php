<style>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    /* display: none; <- Crashes Chrome on hover */
    -webkit-appearance: none;
    margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
}

input[type=number] {
    -moz-appearance:textfield; /* Firefox */
}

</style>
<?php
$segment = service('uri');
if ($segment->getSegment(2) != 'view') {
    if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
        render('miscellaneous_docs.misc_docs_breadcrumb');
    } elseif (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
        render('IA.ia_breadcrumb');
    } elseif (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_MENTIONING) {
        render('mentioning.mentioning_breadcrumb');
    }
}
$appearing_for_details = $data['appearing_for_details'];
$parties_details = $data['parties_details'];
?>
<div class="panel panel-default">
    <div id="result" class="text-center"></div>
    <h6 class="text-center fw-bold mb-2">Appearing For Parties</h6>
    <div class="panel-body">
        <?php
        $attribute = array('class' => 'form-horizontal', 'name' => 'add_appearing_details', 'id' => 'add_appearing_details', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
        echo form_open('#', $attribute);
        if (!empty(getSessionData('radio_appearing_for')) && getSessionData('radio_appearing_for') == 'N' && !empty($appearing_for_details)) {
            ?>
            <div class="row mt-4 mb-4 mt-4">
                <label class="control-label col-md-1 col-sm-6 col-xs-1">Appearing For <span style="color: red">*</span> :</label>
                <div class="col-lg-9 col-md-9 col-sm-12  col-xs-12">
                    <div class="form-group">
                        <?php
                        if (!empty(getSessionData('radio_appearing_for')) && ((getSessionData('radio_appearing_for') == 'I') || (getSessionData('radio_appearing_for') == 'N'))) {
                            $petitioner_user_type_disabled = '';
                            $respondent_user_type_disabled = '';
                        } else {
                            if ($appearing_for_details[0]['partytype'] == 'P') {
                                $petitioner_user_type_disabled = '';
                                $respondent_user_type_disabled = 'disabled';
                            } elseif ($appearing_for_details[0]['partytype'] == 'R') {
                                $petitioner_user_type_disabled = 'disabled';
                                $respondent_user_type_disabled = '';
                            } else {
                                $petitioner_user_type_disabled = '';
                                $respondent_user_type_disabled = '';
                            }
                        }
                        $pet_default_checked = ($appearing_for_details[0]['partytype'] == 'P') ? 'checked' : NULL;
                        $res_checked = ($appearing_for_details[0]['partytype'] == 'R') ? 'checked' : NULL;
                        ?>
                        <label class="radio-inline">
                            <input type="radio" name="user_type" class="user_type_PR" value="P" <?php echo $petitioner_user_type_disabled; ?> <?php echo $pet_default_checked; ?>><strong>Petitioner / Complainant</strong>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="user_type" class="user_type_PR" value="R" <?php echo $respondent_user_type_disabled; ?> <?php echo $res_checked; ?>><strong>Respondent / Accused</strong>
                        </label>
                        <?php
                        if ($pet_default_checked) {
                            $party_name_array = explode('##', $parties_details[0]['p_partyname']);
                            $party_sr_no_array = explode('##', $parties_details[0]['p_sr_no']);
                        } else {
                            $party_name_array = explode('##', $parties_details[0]['r_partyname']);
                            $party_sr_no_array = explode('##', $parties_details[0]['r_sr_no']);
                        }
                        // $parties_list = array_combine($party_sr_no_array, $party_name_array);
                        $keys = range(1, count($party_name_array));
                        $parties_list = array_combine($keys, $party_name_array);
                        if (($appearing_for_details[0]['partytype'] == 'P') || ($appearing_for_details[0]['partytype'] == 'R')) {
                            $saved_appearing_for = $appearing_for_details[0]['appearing_for'];
                            $saved_appearing_for = explode('$$', $saved_appearing_for);
                            $saved_appearing_for_email = $appearing_for_details[0]['p_email'];
                            $saved_appearing_for_email = explode('$$', $saved_appearing_for_email);
                            $saved_appearing_for_mobile = $appearing_for_details[0]['p_mobile'];
                            $saved_appearing_for_mobile = explode('$$', $saved_appearing_for_mobile);
                        } else {
                            $saved_appearing_for = array();
                            $saved_appearing_for_email = NULL;
                            $saved_appearing_for_mobile = NULL;
                        } 
                        ?>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="onbehalf_table" class="table table-striped custom-table dataTable no-footer">
                    <thead>
                        <tr>
                            <th class="text-center">Party Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Mobile</th>
                            <th class="text-center">Select</th>
                        </tr>
                    </thead>
                    <tbody class="parties_list_data">
                        <?php
                        if (count($parties_list) > 0) {
                            $i = 1;
                            $email = '';
                            $mobile = '';
                            foreach ($parties_list as $key => $value) {
                                $selected = (in_array($key, $saved_appearing_for)) ? 'checked' : NULL;
                                $saved_sr_no = array_search($key, $saved_appearing_for);
                                if (isset($saved_appearing_for_email[$saved_sr_no]) && $saved_appearing_for_email[$saved_sr_no] != '') {
                                    $email = $selected ? $saved_appearing_for_email[$saved_sr_no] : NULL;
                                }
                                if (isset($saved_appearing_for_mobile[$saved_sr_no]) && $saved_appearing_for_mobile[$saved_sr_no] != '') {
                                    $mobile = $selected ? $saved_appearing_for_mobile[$saved_sr_no] : NULL;
                                }
                                $appearing_id = $appearing_for_details[0]['id'];
                                $appearing_contact_id = $appearing_for_details[0]['contact_tbl_id'];
                                ?>
                                <tr> 
                                    <td data-key="Party Name"><input class="form-control cus-form-ctrl" name="party_name[]" type="text" value="<?php echo_data($value); ?>"></td>
                                    <td data-key="Email"><input class="form-control cus-form-ctrl " name="party_email[]" type="email" placeholder="Email" value="<?php echo ($email); ?>"></td>
                                    <td data-key="Mobile"><input class="form-control cus-form-ctrl mbl-ctrl" name="party_mob[]" placeholder="Mobile" value="<?php echo_data($mobile); ?>" type="text" maxlength="10" id="mobiles_id_<?=$key?>" minlength="10" value="" onkeyup="check_mobile_numb(this.value, 'mobiles_id_<?=$key?>');"></td>
                                    <td data-key="Select" class="text-center"><input class="checkBoxClass" type="checkbox" name="selected_party[]" value="<?php echo url_encryption($i . '$$$' . $key . '$$$' . $appearing_id . '$$$' . $appearing_contact_id); ?>" <?php echo $selected; ?>></td>
                                </tr>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
        } else {
            ?>
            <center><span class="text-danger">
                [ Select party(s) to whom you represent in the present case. <br>
                It is recommended to provide email and mobile of parties, which you representing, for your own convenience. ]
            </span></center>
            <div class="row  mt-4 mb-4">
                <label class="form-label mt-0 col-lg-1 col-md-2 col-sm-12 col-xs-12 pe-0">Appearing For<span style="color: red">*</span> :</label>
                <div class="col-lg-9 col-md-9 col-sm-12  col-xs-12">
                    <div class="form-group">
                        <?php
                        if (!empty(getSessionData('radio_appearing_for')) && ((getSessionData('radio_appearing_for') == 'I') || (getSessionData('radio_appearing_for') == 'N'))) {
                            $petitioner_user_type_disabled = '';
                            $respondent_user_type_disabled = '';
                        } else {
                            if (isset($appearing_for_details[0]) && $appearing_for_details[0]['partytype'] == 'P') {
                                $petitioner_user_type_disabled = '';
                                $respondent_user_type_disabled = 'disabled';
                            } elseif (isset($appearing_for_details[0]) && $appearing_for_details[0]['partytype'] == 'R') {
                                $petitioner_user_type_disabled = 'disabled';
                                $respondent_user_type_disabled = '';
                            } else {
                                $petitioner_user_type_disabled = '';
                                $respondent_user_type_disabled = '';
                            }
                        }
                        $pet_default_checked = (isset($appearing_for_details[0]) && $appearing_for_details[0]['partytype'] == 'P') ? 'checked' : NULL;
                        $res_checked = (isset($appearing_for_details[0]) && $appearing_for_details[0]['partytype'] == 'R') ? 'checked' : NULL;
                        ?>
                        <label class="radio-inline"><input type="radio" name="user_type" class="user_type_PR" value="P" <?php echo $petitioner_user_type_disabled; ?> <?php echo $pet_default_checked; ?>><strong>Petitioner / Complainant</strong></label>
                        <label class="radio-inline"><input type="radio" name="user_type" class="user_type_PR" value="R" <?php echo $respondent_user_type_disabled; ?> <?php echo $res_checked; ?>><strong>Respondent / Accused</strong></label>
                        <?php
                        if ($pet_default_checked) {
                            $party_name_array = explode('##', $parties_details[0]['p_partyname']);
                            $party_sr_no_array = explode('##', $parties_details[0]['p_sr_no']);
                        } else {
                            $party_name_array = explode('##', $parties_details[0]['r_partyname']);
                            $party_sr_no_array = explode('##', $parties_details[0]['r_sr_no']);
                        }
                        $keys = range(1, count($party_name_array));
                        $parties_list = array_combine($keys, $party_name_array);
                        if (!empty($appearing_for_details[0]) && ($appearing_for_details[0]['partytype'] == 'P' || $appearing_for_details[0]['partytype'] == 'R')) {
                            $saved_appearing_for = $appearing_for_details[0]['appearing_for'];
                            $saved_appearing_for = explode('$$', $saved_appearing_for);
                            $saved_appearing_for_email = $appearing_for_details[0]['p_email'];
                            $saved_appearing_for_email = explode('$$', $saved_appearing_for_email ?? '');
                            $saved_appearing_for_mobile =  $appearing_for_details[0]['p_mobile'];
                            $saved_appearing_for_mobile = explode('$$', $saved_appearing_for_mobile ?? '');
                        } else {
                            $saved_appearing_for = NULL;
                            $saved_appearing_for_email = NULL;
                            $saved_appearing_for_mobile = NULL;
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="onbehalf_table" class="table table-striped custom-table dataTable no-footer">
                    <thead>
                        <tr>
                            <th class="text-center">Party Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Mobile</th>
                            <th class="text-center">Select</th>
                        </tr>
                    </thead>
                    <tbody class="parties_list_data">
                        <?php
                        // echo "<pre>"; print_r($parties_list); echo "</pre>";
                        if (count($parties_list) > 0) {
                            $i = 1;
                            $email = '';
                            $mobile = '';
                            foreach ($parties_list as $key => $value) {
                                $selected = isset($saved_appearing_for) && (in_array($key, $saved_appearing_for)) ? 'checked' : NULL;
                                $saved_sr_no = isset($saved_appearing_for) && array_search($key, $saved_appearing_for);
                                if (isset($saved_appearing_for_email[$saved_sr_no]) && $saved_appearing_for_email[$saved_sr_no] != '') {
                                    $email = $selected ? $saved_appearing_for_email[$saved_sr_no] : NULL;
                                }
                                if (isset($saved_appearing_for_mobile[$saved_sr_no]) && $saved_appearing_for_mobile[$saved_sr_no] != '') {
                                    $mobile = $selected ? $saved_appearing_for_mobile[$saved_sr_no] : NULL;
                                }
                                $appearing_id = isset($appearing_for_details[0]['id']) ? $appearing_for_details[0]['id'] : '';
                                $appearing_contact_id = isset($appearing_for_details[0]['contact_tbl_id']) ? $appearing_for_details[0]['contact_tbl_id'] : '';
                                ?>
                                <tr>
                                    <td data-key="Party Name">
                                        <input class="form-control cus-form-ctrl" name="party_name[]" type="text" value="<?php echo_data($value); ?>" readonly="">
                                    </td>
                                    <td data-key="Email"><input class="form-control cus-form-ctrl" name="party_email[]" type="email" placeholder="Email" value="<?php echo_data($email); ?>"></td>
                                    <td data-key="Mobile"><input class="form-control cus-form-ctrl mbl-ctrl" name="party_mob[]" placeholder="Mobile" value="<?php echo_data($mobile); ?>" type="text" id="mobiles_id_<?=$key?>"  maxlength="10" minlength="10" value="" onkeyup="check_mobile_numb(this.value, 'mobiles_id_<?=$key?>');"></td>
                                    <td data-key="Select" class="text-center"><input data-index='{{$appearing_contact_id}}' class="checkBoxClass" type="checkbox" name="selected_party[]" value="<?php echo url_encryption($i . '$$$' . $key . '$$$' . $appearing_id . '$$$' . $appearing_contact_id); ?>"
                                        <?php echo $selected; ?>>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
        <div class="text-center mt-4">
            <a href="<?= base_url('case_details'); ?>" class="btn quick-btn gray-btn btnPrevious" type="button">PREVIOUS</a>
            <?php if (isset($appearing_for_details[0]['appearing_for']) && !empty($appearing_for_details[0]['appearing_for'])) { ?>
                <input type="submit" class="btn btn-success" id="save_efiling_for" name="submit" value="UPDATE">
                <a href="<?= base_url('on_behalf_of'); ?>" class="btn quick-btn btnNext" type="button">NEXT</a>
            <?php } else { ?>
                <input type="submit" class="btn btn-success" id="save_efiling_for" name="submit" value="SAVE">
            <?php } ?>
        </div>
    <?php echo form_close(); ?>
</div>
</div>
</div>
</div>
</div>
</div>  
</div>
</div>
</div>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
<script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/sha256.js"></script>
<script src="<?= base_url() ?>assets/newAdmin/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url() . 'assets' ?>/js/jquery.validate.js"></script>
<script type="text/javascript">

    function check_mobile_numb(mob, id){
       var  value = mob.replace(/[^0-9]/g, "");
        if(value == ''){
            $('#'+id).val(value);
        }else{
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
             $('#'+id).val(value);
        }
    }


    $(document).ready(function() {
        $('#add_appearing_details').on('submit', function() {
            var selectedCases = [];
            $('#onbehalf_table input:checked').each(function() {
                selectedCases.push($(this).attr('value'));
            });
            if (selectedCases.length <= 0) {
                alert("Please Select at least one party for whom you are filing.");
                return false;
            }
            var len = $('[name=user_type]:checked').length;
            if (len === 0) {
                alert('Please Select Petitioner / Complainant OR Respondent / Accused');
                return false;
            }
            $(".user_type_PR").prop("disabled", false);
            var form_data = $(this).serialize();
            $('#modal_loader').show();
            //return false;
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('appearing_for/appearing_for/save_appearing_details'); ?>",
                data: form_data,
                success: function(data) {
                    $(".user_type_PR").prop("disabled", true);
                    $('#modal_loader').hide();
                    var resArr = data.split('@@@');
                    // alert(resArr[1]);
                    if (resArr[0] == 1) {
						$('.msg').show();
						$("#result").html("<p class='message success' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                        location.reload();
                    }
                    if (resArr[0] == 2) {
						$('.msg').show();
						$("#result").html("<p class='message error' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                        location.reload();
                    }
                    if (resArr[0] == 3) {
						$('.msg').show();
						$("#result").html("<p class='message error' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
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
            return false;
        });
    });

    $('[name="user_type"]').change(function() {
        var partytype = $(this).val();
        $('#modal_loader').show();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('appearing_for/ajaxcalls/get_parties_list'); ?>",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                partytype: partytype
            },
            success: function(data) {
                $('#modal_loader').hide();
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    $('.parties_list_data').html(resArr[1]);
                }
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function(result) {
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
</script>