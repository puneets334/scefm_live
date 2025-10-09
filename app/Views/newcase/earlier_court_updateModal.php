
<div class="panel panel-default">
    <span id="msg-EarlierCourt" class="form-response-EarlierCourt" role="alert" data-auto-dismiss="5000" class="text-success"></span>
    <h4 style="text-align: center;color: #31B0D5"> Earlier Courts </h4>
    <div class="panel-body">
        <?php
        $attribute = array('class' => 'form-horizontal', 'name' => 'update_subordinate_court_details', 'id' => 'update_subordinate_court_details', 'autocomplete' => 'off');
        echo form_open('#', $attribute);
        $cause_title = explode(' Vs. ', $new_case_details[0]['cause_title']);
        ?>

        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <?php

                $court_type_name='';
                $court_type=$caseData['court_type'];
                if($subordinate_court_details[0]['case_type_id']==0 || $subordinate_court_details[0]['case_type_id']==null){$case_type_id_zero='block;'; }else{ $case_type_id_zero='none;';}

                $noHcEntry=$subordinate_court_details[0]['is_hc_exempted']=='t'?'checked="checked"':'';
                $noHCButton=$subordinate_court_details[0]['is_hc_exempted']=='t'?'style="display:none;':'';
                $scchecked = $court_type == '4' ? 'checked="checked"' : '';
                $hcchecked = $court_type == '1' ? 'checked="checked"' : '';
                $dcchecked = $court_type == '3' ? 'checked="checked"' : '';
                $dcchecked = $court_type == '5' ? 'checked="checked"' : '';
                if ($court_type==1){

                    $court_type_name='High Court';

                }elseif ($court_type==2){

                    $court_type_name='Other Court';

                }elseif ($court_type==3){

                    $court_type_name='District Court';

                }elseif ($court_type==4){

                    $court_type_name='Supreme Court';

                }elseif ($court_type==5){

                    $court_type_name='State Agency';
                }
                ?>
                <input type="hidden" name="earlier_court_id" value="<?php echo url_encryption($subordinate_court_details[0]['id']);?>">
                <input type="hidden" name="registration_id" value="<?php echo url_encryption($subordinate_court_details[0]['registration_id']);?>">
                <input type="hidden" name="radio_selected_court" value="<?php echo $court_type;?>">

                <div id="hc_entry_div">
                    <div class="form-group">
                        <label class="control-label col-sm-4 input-sm"> Court Type: <span style="color: green"><?php echo $court_type_name;?></span></label>

                    </div>
                    <hr>
                    <?php if ($court_type==4){?>
                    <div id="supreme_court_info" style="display: block;">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case Type <span style="color: red">*</span>:</label>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <select name="sci_case_type_id" tabindex = '4' id="sci_case_type_id" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                                                <option value="" title="Select">Select Case Type</option>
                                                <?php $sel = ($subordinate_court_details[0]['case_type_id'] ==0) ? 'selected=selected' : '';
                                                echo '<option '.$sel.' value="' . escape_data(url_encryption( "0##NOT IN LIST")) . '">NOT IN LIST</option>';
                                                foreach ($case_type_list as $case_type){
                                                    $sel = ($case_type->casecode == $subordinate_court_details[0]['case_type_id']) ? 'selected=selected' : '';
                                                    ?>
                                                    <option <?php echo $sel;?> value="<?php echo escape_data(url_encryption($case_type->casecode . "##" . $case_type->casename ))?>"><?php echo escape_data(strtoupper($case_type->casename));?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <?php } else if ($court_type==1){?>
                    <div id="high_court_info" style="display: block;">
                        <div class="row">

                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-12 col-sm-12 col-xs-12 input-sm">High Court :<?php echo $subordinate_court_details[0]['estab_name'];?></label>

                            </div>
                        </div>

                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-12 col-sm-12 col-xs-12 input-sm">Bench :<?php echo $subordinate_court_details[0]['bench_name'];?></label>
                            </div>
                        </div>

                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case Type <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <select tabindex = '8' name="case_type_id" id="case_type_id" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                                        <option value="" title="Select">Select Case Type</option>
                                        <?php $sel = ($subordinate_court_details[0]['case_type_id'] ==0) ? 'selected=selected' : '';
                                        echo '<option '.$sel.' value="' . escape_data(url_encryption( "0##NOT IN LIST")) . '">NOT IN LIST</option>';
                                        foreach ($case_type_list as $case_type){
                                            $sel = ($case_type['case_type'] == $subordinate_court_details[0]['case_type_id']) ? 'selected=selected' : '';
                                            ?>
                                            <option <?php echo $sel;?> value="<?php echo escape_data(url_encryption($case_type['case_type'] . "##" . $case_type['type_name'] ))?>"><?php echo escape_data(strtoupper($case_type['type_name']));?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12 hc_case_type_name" style="display:<?=$case_type_id_zero;?>">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Case Type Name: <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <input type="text"  name="hc_case_type_name" id="hc_case_type_name" value="<?php echo $subordinate_court_details[0]['casetype_name'];?>" class="form-control input-sm" placeholder="Enter Case Type Name..">
                                </div>
                            </div>
                        </div>


                    </div>

                    </div>
                    <?php } else if ($court_type==3){?>
                    <div id="district_court_info" style="display: block;">

                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-12 col-sm-12 col-xs-12 input-sm">State :<?php echo $subordinate_court_details[0]['state_name'];?></label>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-12 col-sm-12 col-xs-12 input-sm">District :<?php echo $subordinate_court_details[0]['district_name'];?></label>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-12 col-sm-12 col-xs-12 input-sm">Establishment :<?php echo $subordinate_court_details[0]['estab_name'];?></label>
                            </div>
                        </div>

                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case Type <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <select tabindex = '14' name="dc_case_type_id" id="dc_case_type_id" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                                        <option value="" title="Select">Select Case Type</option>
                                        <?php $sel = ($subordinate_court_details[0]['case_type_id'] ==0) ? 'selected=selected' : '';
                                        echo '<option '.$sel.' value="' . escape_data(url_encryption( "0##NOT IN LIST")) . '">NOT IN LIST</option>';
                                        foreach ($case_type_list as $case_type){
                                            $sel = ($case_type['case_type'] == $subordinate_court_details[0]['case_type_id']) ? 'selected=selected' : '';
                                            ?>
                                            <option <?php echo $sel;?> value="<?php echo escape_data(url_encryption($case_type['case_type'] . "##" . $case_type['type_name'] ))?>"><?php echo escape_data(strtoupper($case_type['type_name']));?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12 dc_case_type_name" style="display:<?=$case_type_id_zero;?>">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Case Type Name: <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <input type="text"  name="dc_case_type_name" id="dc_case_type_name" value="<?php echo $subordinate_court_details[0]['casetype_name'];?>" class="form-control input-sm" placeholder="Enter Case Type Name..">
                                </div>
                            </div>
                        </div>


                    </div>

                  <?php } else if ($court_type==5){?>
                    <div id="state_agency_info" style="display: block;">
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-12 col-sm-12 col-xs-12 input-sm">State :<?php echo $subordinate_court_details[0]['state_name'];?></label>
                            </div>
                        </div>

                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Agency :<?php echo trim($subordinate_court_details[0]['estab_name']);?></label>
                            </div>
                        </div>

                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case Type <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <select tabindex = '8' name="agency_case_type_id" id="agency_case_type_id" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                                        <option value="" title="Select">Select Case Type</option>
                                        <?php $sel = ($subordinate_court_details[0]['case_type_id'] ==0) ? 'selected=selected' : '';
                                        echo '<option '.$sel.' value="' . escape_data(url_encryption( "0##NOT IN LIST")) . '">NOT IN LIST</option>';
                                        foreach ($case_type_list as $case_type){
                                            $sel = ($case_type['case_type'] == $subordinate_court_details[0]['case_type_id']) ? 'selected=selected' : '';
                                            ?>
                                            <option <?php echo $sel;?> value="<?php echo escape_data(url_encryption($case_type['case_type'] . "##" . $case_type['type_name'] ))?>"><?php echo escape_data(strtoupper($case_type['type_name']));?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12 agency_case_type_name" style="display:<?=$case_type_id_zero;?>">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Case Type Name: <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <input type="text"  name="agency_case_type_name" id="agency_case_type_name" value="<?php echo $subordinate_court_details[0]['casetype_name'];?>" class="form-control input-sm" placeholder="Enter Case Type Name..">
                                </div>
                            </div>
                        </div>


                    </div>
                  <?php }?>

                    <div class="col-md-12 col-sm-12 col-xs-12" id="loader_div" style="display: none;">
                        <img id="loader_img" style="position: fixed;left: 50%;margin-top: -50px;margin-left: -100px;" src="<?php echo base_url(); ?>assets/images/loading-data.gif" />
                    </div>
                    <!----START : Show search Ressult---->
                    <div id="case_result"></div>
                    <!----END : Show search Ressult---->
                    <div class="clearfix"></div><br><br>

                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                            <input tabindex = '28' type="submit" class="btn btn-success" id="subcourt_save" value="Update">
                            </div>
                    </div>
                    <?php echo form_close();
                    ?>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {
       // get_fir_state_list();
        //toggle_entry_div();
        $('#case_type_id').on('change', function () {
            var case_type_id = $(this).val();
            var case_type_selectedText = $("#case_type_id option:selected").html();
            if(case_type_selectedText==='NOT IN LIST'){
                alert('Are you sure that it is not in list');
                $('.hc_case_type_name').show();
            }else {  $('.hc_case_type_name').hide();}
        });
        $('#dc_case_type_id').on('change', function () {
            var case_type_id = $(this).val();
            var case_type_selectedText = $("#dc_case_type_id option:selected").html();
            if(case_type_selectedText==='NOT IN LIST'){
                alert('Are you sure that it is not in list');
                $('.dc_case_type_name').show();
            }else {  $('.dc_case_type_name').hide();}
        });
        $('#agency_case_type_id').on('change', function () {
            var case_type_id = $(this).val();
            var case_type_selectedText = $("#agency_case_type_id option:selected").html();
            if(case_type_selectedText==='NOT IN LIST'){
                alert('Are you sure that it is not in list');
                $('.agency_case_type_name').show();
            }else {  $('.agency_case_type_name').hide();}
        });


        $('#update_subordinate_court_details').on('submit', function () {

            if ($('#update_subordinate_court_details').valid()) {
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('newcase/Subordinate_court/update_subordinate_court'); ?>",
                    data: form_data,
                    async: false,
                    beforeSend: function () {
                        $('#subcourt_save').val('Please wait...');
                        $('#subcourt_save').prop('disabled', true);
                    },
                    success: function (data) {
                        $('#subcourt_save').val('SAVE');
                        $('#subcourt_save').prop('disabled', false);
                        var resArr = data.split('@@@');
                        if (resArr[0] == 1) {
                            $('#msg-EarlierCourt').show();
                            $(".form-response-EarlierCourt").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        } else if (resArr[0] == 2) {
                            $(".form-response-EarlierCourt").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            $('#msg-EarlierCourt').show();
                            location.reload();
                            //window.location.href = resArr[2];
                        } else if (resArr[0] == 3) {
                            $('#msg-EarlierCourt').show();
                            $(".form-response-EarlierCourt").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
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