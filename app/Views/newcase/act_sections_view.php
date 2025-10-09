<div class="panel panel-default">
    <h4 style="text-align: center;color: #31B0D5">Act(s)-Section(s) </h4>
    <div class="panel-body">
        <?php
        $attribute = array('class' => 'form-horizontal', 'name' => 'add_act_section', 'id' => 'add_act_section', 'autocomplete' => 'off');
        echo form_open('#', $attribute);
        ?>
<!--        <div class="col-sm-12" style="margin-left: 274px;margin-bottom: 15px;">-->
<!--            <input type="checkbox" class="form-check-input" name="not_in_list" value="" id="not_in_list">-->
<!--            <label class="form-check-label" for="not_in_list">Act (Not In List)</label>-->
<!--        </div>-->


        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Act <span style="color: red">*</span>:</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <select tabindex='2' name="case_acts" id="case_acts" class="form-control input-sm filter_select_dropdown">
                                <option value="" title="Select">Select Act</option>
                                <?php
                                if (count($acts_list)) {
                                    foreach ($acts_list as $dataRes) {
                                        ?>
                                        <option value="<?php echo_data(url_encryption(trim($dataRes->act_id))); ?>"><?php echo_data(strtoupper($dataRes->act_name . ' (' . $dataRes->act_year . ')')); ?> </option>;
                                        <?php
                                    }?>

<!--                                    <option value="--><?php //echo_data(url_encryption('others'))?><!--">NOT IN LIST</option>-->
                                <?php }
                                ?>
                            </select>
                        </div>

                        <?php // changes done on 05 September 2020?>
<!--                        <div id="other_act" style="display:none;" >-->
<!--                                <div class="row">-->
<!--                                    <div class="col-md-12 col-sm-3 col-xs-12">&nbsp</div>-->
<!--                                </div>-->
<!---->
<!--                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Act Name<span style="color: red">*</span>:</label>-->
<!--                                <div class="col-md-7 col-sm-12 col-xs-12">-->
<!--                                    <input type="text" name="act_name" id="act_name" class="form-control" value="" placeholder="Act Name">-->
<!--                                </div>-->
<!---->
<!--                                <div class="row">-->
<!--                                    <div class="col-md-12 col-sm-3 col-xs-12">&nbsp</div>-->
<!--                                </div>-->
<!---->
<!--                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Act No<span style="color: red">*</span>:</label>-->
<!--                                <div class="col-md-7 col-sm-7 col-xs-4">-->
<!--                                    <input type="number" name="act_no" id="act_no" class="form-control" value="" placeholder="Act No.">-->
<!--                                </div>-->
<!---->
<!--                                <div class="row">-->
<!--                                    <div class="col-md-12 col-sm-3 col-xs-12">&nbsp</div>-->
<!--                                </div>-->
<!---->
<!--                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Act Year<span style="color: red">*</span>:</label>-->
<!--                                <div class="col-md-7 col-sm-7 col-xs-4">-->
<!--                                    <input type="number" name="act_year" id="act_year" class="form-control" value="" placeholder="Act Year">-->
<!--                                </div>-->
<!--                        </div>-->

                        <?php    // end of changes?>


                        <div class="col-sm-12" style="margin-left: 268px;margin-top: 15px;">
                            <input type="checkbox" class="form-check-input" name="not_in_list" value="" id="not_in_list">
                            <label class="form-check-label" for="not_in_list">Act (Not In List)</label>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-12 col-xs-12 input-sm"> Sections :</label>
                        <div class="col-md-2 col-sm-3 col-xs-3">
                            <input  tabindex='3' id="section_1" name="section_1" placeholder="Section" minlength="1" maxlength="10" value="" class="form-control input-sm" type="text" minlength="1" maxlength="10">
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-3">
                            <input  tabindex ='4' id="section_2" name="section_2" placeholder="Sub-Section" minlength="1" maxlength="10" value="" class="form-control input-sm" type="text" minlength="1" maxlength="10">
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-3">
                            <input  tabindex = '5' id="section_3" name="section_3" placeholder="Sub-Section" minlength="1" maxlength="10" value="" class="form-control input-sm" type="text" minlength="1" maxlength="10">
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-3">
                            <input tabindex = '6' id="section_4" name="section_4" placeholder="Sub-Section" minlength="1" maxlength="10" value="" class="form-control input-sm" type="text" minlength="1" maxlength="10">
                        </div>
                    </div>
                </div>
            </div>                     
        </div>

                        <div class="row col-md-12" id="other_act" style="display: none;">
                            <div class="col-sm-6">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Act Name<span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12" >
                                    <input type="text" style="margin-left: 3px;width: 352px;" name="act_name" id="act_name" minlength="1" maxlength="75" class="form-control" value="" placeholder="Act Name">
                                </div>
                            </div>

                            <div class="col-sm-6" style="margin-left: -39px;">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Act No.<span style="color: red">(As given by state/central legislation.)</span>:</label>
                                    <div class="col-md-7 col-sm-7 col-xs-4">
                                        <input type="text" name="act_no" id="act_no" style="width: 405px" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" minlength="1" maxlength="5" class="form-control" value="" placeholder="Act No.">
                                    </div>
                            </div>
                            <div class="col-sm-6" style=" margin-top: 12px;margin-bottom: 15px; ">
                                           <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Act Year<span style="color: red"></span>:</label>
                                            <div class="col-md-7 col-sm-7 col-xs-4">
                                                <input type="text" name="act_year" style="margin-left: 3px;width: 352px;" id="act_year" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" minlength="4" maxlength="4" class="form-control" value="" placeholder="Act Year">
                                            </div>
                            </div>

                        </div>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
                <a href="<?= base_url('newcase/lr_party') ?>" class="btn btn-primary" tabindex = '7' type="button">Previous</a>
                <input  tabindex = '8' type="submit" class="btn btn-success" id="pet_save" value="SAVE">
                <?php if(!empty($act_sections_list)){?>
                <a href="<?= base_url('newcase/subordinate_court') ?>" class="btn btn-primary btnNext" id="actSectionButton" tabindex = '9' type="button" >Next</a>
                <?php }
                else{
                    echo '<a href="'.base_url('newcase/subordinate_court').'" class="btn btn-primary btnNext" id="actSectionButton" style="display:none;" tabindex = "9" type="button" >Next</a>';
                }
                ?>
            </div>
        </div>
        <?php echo form_close();
        ?>
        <?php $this->load->view('newcase/act_sections_list_view'); ?>
    </div>
</div>

<script type="text/javascript">

    function load_act_section_list() {
      //  $('#nextButton').hide();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('newcase/Ajaxcalls_act_section/get_act_sections_list'); ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, res: 1},
            success: function (data) {
                if(data.includes("No record found.")){
                    $("#actSectionButton").hide();
                }
                else{
                    $("#actSectionButton").show();
                }
                $('#act_section_list_data').html(data);
                $('#datatable-responsive').dataTable({
                    "initComplete": function () {
                        //$('#nextButton').show();
                        $(this).show();
                    }
                });

                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
    
    
    function delete_act_section(delete_id) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('newcase/Ajaxcalls_act_section/delete'); ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, delete_id:delete_id},            
            success: function (data) {

                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    $('#msg').show();
                    location.reload();
                   // load_act_section_list();
                } else if (resArr[0] == 3) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                }
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
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

        /*Changes done on 05 September 2020*/
        var i = 0;
        $(":input:not(:hidden)").each(function (i) { $(this).attr('tabindex', i + 1); });


        $('#case_acts').on('change',function()
        {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var act_id = $('#case_acts').val();
           // alert(act_id);
              $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, act_id: act_id},
                url: "<?php echo base_url('newcase/Ajaxcalls_act_section/decryptActId'); ?>",
                success: function (data)
                {
                    // if(data=='others')
                    // {
                    //     $('#other_act').show();
                    // }
                    // else{
                    //     $('#other_act').hide();
                    // }
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

        /*Changes end */

        $('#add_act_section').on('submit', function () {
            if ($('#add_act_section').valid()) {
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('newcase/actSections/add_act_section'); ?>",
                    data: form_data,
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
                            $("#actSectionButton").show();
                            location.reload();
                            //load_act_section_list();
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

        $("#not_in_list").on('click',function(){
            if ($('#not_in_list').is(":checked"))
            {
                $("#case_acts").prop("disabled", true);
                $("#not_in_list").val('others');
                $('#other_act').show();
            }
            else{
                $("#case_acts").prop("disabled", false);
                $("#not_in_list").val('');
                $('#other_act').hide();
                $("#act_name").val('');
                $("#act_no").val('');
                $("#act_year").val('');
            }
        });



    });

    

</script>