<div class="row">
    <label class="control-label col-xs-12" style="font-size: large; text-align: center">FIR Details</label>
</div>
<div id="fir_details" data-select2-id="fir_details">
    <div class="col-sm-5 col-xs-12">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">State <span style="color: red">*</span>:</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <select tabindex="-1" name="fir_state" id="fir_state" class="form-control input-sm filter_select_dropdown" style="width: 100%" >
                    <option value="">Select State</option>
                    <?php
                    if(isset($stateListFir) && !empty($stateListFir)){
                        foreach ($stateListFir as $k=>$v){
                            echo '<option value="'.url_encryption($v->cmis_state_id. '#$'.$v->agency_state).'">'.strtoupper($v->agency_state).'</option>';
                        }
                    }
                    ?>
                    <option>Select State</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-5 col-xs-12">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">District <span style="color: red">*</span>:</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <select tabindex="-1" name="fir_district" id="fir_district" class="form-control input-sm filter_select_dropdown" style="width: 100%" >
                    <option>Select District</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-xs-12">
            <div class="form-group">
                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Police Station <span style="color: red">*</span>:</label>
                <div class="col-md-7 col-sm-12 col-xs-12">
                    <select tabindex="-1" name="fir_policeStation" id="fir_policeStation" class="form-control input-sm filter_select_dropdown" style="width: 100%" >
                        <option>Select Police Station </option>
                    </select>
                </div>
            </div>
            If Police Station not in list, please enter Police Station name and Complete FIR number below.
        </div>
        <div class="col-sm-4 col-xs-12">
            <div class="form-group">
                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> FIR No. <span style="color: red">*</span>:</label>
                <div class="col-md-7 col-sm-12 col-xs-12">
                    <div class="input-group">
                        <input tabindex="24" id="fir_number" name="fir_number" maxlength="10" onkeyup="return isNumber(event)" placeholder="FIR Number" class="form-control input-sm age_calculate" type="text">
                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="FIR Number" data-original-title="" title="">
                                <i class="fa fa-question-circle-o"></i>
                            </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-12">
            <div class="form-group">
                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Year <span style="color: red">*</span>:</label>
                <div class="col-md-3 col-sm-12 col-xs-12">
                    <div class="input-group">
                        <select tabindex="-1" class="form-control input-sm filter_select_dropdown" id="fir_year" name="fir_year" style="width: 100%" >
                            <option>Select Year</option>
                            <?php
                            $end_year = 47;
                            for ($i = 0; $i <= $end_year; $i++) {
                                $year = (int) date("Y") - $i;
                                echo '<option value=' . url_encryption($year) . '>' . $year . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-xs-12">
            <div class="form-group">
                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Police Station Name <span style="color: red">*</span>:</label>
                <div class="col-md-7 col-sm-12 col-xs-12">
                    <div class="input-group">
                        <input tabindex="26" id="police_station_name" name="police_station_name" placeholder="Police Station Name" class="form-control" type="text" >
                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Police Station Name" data-original-title="" title="">
                                <i class="fa fa-question-circle-o"></i>
                            </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-12">
            <div class="form-group">
                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Complete FIR No. <span style="color: red">*</span>:</label>
                <div class="col-md-7 col-sm-12 col-xs-12">
                    <div class="input-group">
                        <input tabindex="27" id="complete_fir_number" name="complete_fir_number" maxlength="15" onkeyup="return isNumber(event)" placeholder="FIR Number" class="form-control input-sm age_calculate" type="text">
                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="FIR Number" data-original-title="" title="">
                                <i class="fa fa-question-circle-o"></i>
                            </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $(document).on('change','#fir_state',function(){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#fir_district').val('');
            var get_state_id = $(this).val();
            var court_type = parseInt($("#court_type option:selected").attr('data-select2-id'));
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
                url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_icmis_district_list'); ?>",
                success: function (data)
                {
                    if(court_type == 110){
                        $("#sub_high_court_info_search_case #fir_district").html(data);
                    }
                    else{
                        $('#fir_district').html(data);
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

        });
            $(document).on('change','#fir_district',function(){
            $('#fir_policeStation').val('');
            var court_type = parseInt($("#court_type option:selected").attr('data-select2-id'));
            var get_distt_id = $(this).val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var fir_state = $("#fir_state option:selected").val();
            var fir_district = $("#fir_district option:selected").val();
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, fir_state: fir_state, fir_district: fir_district},
                url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_police_station_fir'); ?>",
                success: function (data) {
                    if(court_type == 110){
                        $("#sub_high_court_info_search_case #fir_policeStation").html(data);
                    }
                    else{
                        $('#fir_policeStation').html(data);
                    }
                    $("#sub_high_court_info_search_case,#fir_policeStation,#fir_number, #fir_year,#police_station_name,#complete_fir_number").prop("disabled", false);
                    $("#appellate_n_trail_court,#fir_policeStation,#fir_number, #fir_year,#police_station_name,#complete_fir_number").prop("disabled", false);
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

        $(document).on('blur','#police_station_name',function(){
            var court_type = parseInt($("#court_type option:selected").attr('data-select2-id'));
            $("#sub_high_court_info_search_case,#fir_policeStation,#fir_number, #fir_year").prop("disabled", false);
            $("#fir_policeStation,#fir_number, #fir_year").prop("disabled", false);
            if(court_type == 110){
                if( $.trim($('#police_station_name').val()) !=='')
                    $("#sub_high_court_info_search_case,#fir_policeStation,#fir_number, #fir_year").prop("disabled", false);
                else
                    $("#fir_policeStation,#fir_number, #fir_year").prop("disabled", true);
            }
            else if(court_type == 2){
                if( $.trim($('#police_station_name').val())=='')
                    $("#fir_policeStation,#fir_number, #fir_year").prop("disabled", false);
                else
                    $("#fir_policeStation,#fir_number, #fir_year").prop("disabled", true);
            }

        });
        $('#fir_policeStation').on('blur', function() {
           // var court_type = $("#court_type option:selected").val();
            var court_type = parseInt($("#court_type option:selected").attr('data-select2-id'));
            if(court_type == 110){
                if($("#fir_policeStation :option:selected").val()!='')
                    $("#sub_high_court_info_search_case,#police_station_name,#complete_fir_number").prop("disabled", true);
                else
                    $("#police_station_name,#complete_fir_number").prop("disabled", false);
            }
            else if(court_type == 2){
                if($("#fir_policeStation :option:selected").val()!='')
                    $("#police_station_name,#complete_fir_number").prop("disabled", true);
                else
                    $("#police_station_name,#complete_fir_number").prop("disabled", false);
            }

        });
    });

</script>