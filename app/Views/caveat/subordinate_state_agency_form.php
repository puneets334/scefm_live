<style>.caseno{height: 38px;} .year{padding: 0px 0px 0px 0px;background-color:#ffffff;border:none;border-radius: 0px;}</style>
<?php
$attribute = array('class' => 'form-horizontal form-label-left', 'name' => 'state_agency_form', 'id' => 'state_agency_form', 'autocomplete' => 'off');
echo form_open('#', $attribute);
?>

<div class="row"><br>
    <div class="col-sm-4 col-xs-12">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">State <span style="color: red">*</span>:</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <select  name="state_agency_state" id="state_agency_state" class="form-control input-sm filter_select_dropdown state_agency_state" required style="width: 100%">
                    <option>Select State</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-xs-12">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Agency <span style="color: red">*</span>:</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <select name="state_agency_name" id="state_agency_name" class="form-control input-sm filter_select_dropdown state_agency_name" required style="width: 100%" >
                    <option>Select Agency</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case Type <span style="color: red">*</span> :</label>
            <div class="col-md-6 col-sm-12 col-xs-12">

                <select name="state_agency_case_type" id="state_agency_case_type" class="form-control input-sm filter_select_dropdown state_agency_case_type" required style="width: 100%">
                    <option value="">Select Case Type</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-xs-12 agency_case_type_name" style="display:none;">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Case Type Name: <span style="color: red">*</span>:</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <input type="text"  name="agency_case_type_name" id="agency_case_type_name" class="form-control input-sm" placeholder="Enter Case Type Name..">
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Case No.And Year<span style="color: red">*</span> :</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <div class="input-group">
                    <input id="state_agency_case_no" name="state_agency_case_no" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" value=""  placeholder="Case No" required maxlength="7"  class="form-control input-sm state_agency_case_no caseno" type="text">
                    <span class="input-group-addon year" data-placement="bottom" data-toggle="popover" data-content="Case No. should be in digits only.">
                        <div class="input-group">
                    <select tabindex = '6' class="form-control input-sm filter_select_dropdown" id="case_year" name="case_year" style="width: 100%">
                        <option value="">Year</option>
                        <?php
                        $end_year = 47;
                        for ($i = 0; $i <= $end_year; $i++) {
                            $year = (int) date("Y") - $i;
                            echo '<option ' . $sel . ' value=' . url_encryption($year) . '>' . $year . '</option>';
                        }
                        ?>
                    </select>
                </div>
                    </span>
                </div>
            </div>
        </div>
    </div>
   <!-- <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case Year <span style="color: red">*</span> :</label>
            <div class="col-sm-2 col-md-4 col-xs-12" >

            </div>
        </div>
    </div>-->
</div>
<div class="row">
    <div class="col-sm-4 col-xs-12">
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-12 col-xs-12 input-sm">Impugned Order Date <span style="color: red">*</span>:</label>
            <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="input-group">
                    <input tabindex = '18' class="form-control" id="order_date" name="order_date" maxlength="10" placeholder="DD/MM/YYYY"  type="text" style="width: 80%">
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-xs-12">
        <div class="form-group" data-select2-id="87">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Impugned Order Challenged <span style="color: red">*</span>:</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <select tabindex="-1" name="judgement_challenged" id="judgement_challenged" class="form-control input-sm filter_select_dropdown " style="width: 100%" data-select2-id="judgement_challenged" aria-hidden="true">
                    <option value="1" data-select2-id="80">Yes</option>
                    <option value="0" data-select2-id="92">No</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-xs-12">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Impugned Order Type <span style="color: red">*</span>:</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <select tabindex="-1" name="judgement_type" id="judgement_type" class="form-control input-sm filter_select_dropdown " style="width: 100%" data-select2-id="judgement_type" aria-hidden="true">
                    <option value="F" data-select2-id="82">Final</option>
                    <option value="I" data-select2-id="95">Interim</option>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="row col-md-12">
    <input type="checkbox" id="firdetails" name="firdetails" value="firdetails">
    <label for="firdetails">Fir Details</label><br>
    <!--fir start -->
    <div id="firdetails_agency_state" data-select2-id="firdetails_agency_state" style="display: none;">
        <div class="row">
            <label class="control-label col-xs-12" style="font-size: large; text-align: center">FIR Details</label>
        </div>
        <div class="col-sm-5 col-xs-12">
            <div class="form-group">
                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">State <span style="color: red">*</span>:</label>
                <div class="col-md-7 col-sm-12 col-xs-12">
                    <select tabindex="-1" name="firstate_agency_state" id="firstate_agency_state" class="form-control input-sm filter_select_dropdown" style="width: 100%" >
                        <option value="">Select State</option>
                        <?php
                        if(isset($stateListFir) && !empty($stateListFir)){
                            foreach ($stateListFir as $k=>$v){
                                echo '<option value="'.url_encryption($v->cmis_state_id. '#$'.$v->agency_state).'">'.strtoupper($v->agency_state).'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-5 col-xs-12">
            <div class="form-group">
                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">District <span style="color: red">*</span>:</label>
                <div class="col-md-7 col-sm-12 col-xs-12">
                    <select tabindex="-1" name="firdistrict_agency_state" id="firdistrict_agency_state" class="form-control input-sm filter_select_dropdown" style="width: 100%" >
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
                        <select tabindex="-1" name="firpoliceStation_agency_state" id="firpoliceStation_agency_state" class="form-control input-sm filter_select_dropdown" style="width: 100%" >
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
                            <input tabindex="24" id="fir_number_agency_state" name="fir_number_agency_state" maxlength="10" onkeyup="return isNumber(event)" placeholder="FIR Number" class="form-control input-sm age_calculate" type="text">
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
                            <select tabindex="-1" class="form-control input-sm filter_select_dropdown" id="firyear_agency_state" name="firyear_agency_state" style="width: 100%" >
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
                            <input tabindex="26" id="police_station_name_agency_state" name="police_station_name_agency_state" placeholder="Police Station Name" class="form-control" type="text" >
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
                            <input tabindex="27" id="complete_fir_number_agency_state" name="complete_fir_number_agency_state" maxlength="15" onkeyup="return isNumber(event)" placeholder="FIR Number" class="form-control input-sm age_calculate" type="text">
                            <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="FIR Number" data-original-title="" title="">
                                <i class="fa fa-question-circle-o"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
            <?php
            $prev_redirect_url = 'caveat/extra_party';
            $next_redirect_url = 'uploadDocuments';
            ?>
            <a href="<?= base_url($prev_redirect_url) ?>" class="btn btn-primary" type="button">Previous</a>
            <input type="submit" class="btn btn-primary" id="state_agency_save" value="SAVE">
            <?php
            if(isset($subordinate_court_details) && !empty($subordinate_court_details)){
                echo '<a href="'. base_url($next_redirect_url).'" class="btn btn-primary" type="button">Next</a>';
            }
            ?>

        </div>
    </div>
</div>
<?php echo form_close();

?>

<div class="clearfix"></div>

<script>
    $(document).ready(function(){
        $('#order_date').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            maxDate: new Date
        });
        $('#state_agency_case_type').on('change', function () {
            var case_type_id = $(this).val();
            var case_type_selectedText = $("#state_agency_case_type option:selected").html();
            if(case_type_selectedText==='NOT IN LIST'){
                alert('Are you sure that it is not in list');
                $('.agency_case_type_name').show();
                $("#agency_case_type_name").prop('required',true);
            }else {
                $("#agency_case_type_name").prop('required',false);
                $('.agency_case_type_name').hide();}
        });
        $('#state_agency_state').change(function () {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#case_type_id').val('');
            var agency_state_id = $(this).val();
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, agency_state_id: agency_state_id, court_type: '5'},
                url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_state_agency_list'); ?>",
                success: function (data)
                {
                    $('#state_agency_name').html(data);
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
        $('#state_agency_name').change(function () {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#case_type_id').val('');
            var agency_id = $(this).val();
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, agency_id: agency_id, court_type: '5'},
                url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_state_agency_case_types'); ?>",
                success: function (data)
                {
                    $('#state_agency_case_type').html(data);
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




        $(document).on('change','#firstate_agency_state',function(){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#firdistrict').val('');
            var get_state_id = $(this).val();
            var court_type = parseInt($("#court_type option:selected").attr('data-select2-id'));
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
                url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_icmis_district_list'); ?>",
                success: function (data)
                {
                    $("#firdetails_agency_state #firdistrict_agency_state").html(data);
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
        $(document).on('change','#firdistrict_agency_state',function(){
            $('#fir_policeStation').val('');
            var court_type = parseInt($("#court_type option:selected").attr('data-select2-id'));
            var get_distt_id = $(this).val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var fir_state = $("#firstate_agency_state option:selected").val();
            var fir_district = $("#firdistrict_agency_state option:selected").val();
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, fir_state: fir_state, fir_district: fir_district},
                url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_police_station_fir'); ?>",
                success: function (data) {
                    $('#firpoliceStation_agency_state').html(data);
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

        // $(document).on('blur','#firpoliceStation_agency_state',function(){
        //         if( $.trim($('#police_station_name_agency_state').val()) !=='')
        //             $("#police_station_name_agency_state,#complete_fir_number_agency_state").prop("disabled", false);
        //         else
        //             $("#firpoliceStation_agency_state,#fir_number_agency_state, #firyear_agency_state").prop("disabled", true);
        //
        // });
        // $('#firpoliceStation').on('blur', function() {
        //     // var court_type = $("#court_type option:selected").val();
        //     var court_type = parseInt($("#court_type option:selected").attr('data-select2-id'));
        //     if(court_type == 110){
        //         if($("#fir_policeStation option:selected").val()!='')
        //             $("#sub_high_court_info_search_case,#policestation_name,#completefirnumber").prop("disabled", true);
        //         else
        //             $("#policestationname,#completefirnumber").prop("disabled", false);
        //     }
        //     else if(court_type == 2){
        //         if($("#fir_policeStation option:selected").val()!='')
        //             $("#policestationname,#completefirnumber").prop("disabled", true);
        //         else
        //             $("#police_station_name,#complete_fir_number").prop("disabled", false);
        //     }
        //
        // });
    });

</script>
<script type="text/javascript">
    //-----HIGH COURT CASE SEARCH---------//
    $(document).ready(function () {
        $(document).on('click','#firdetails',function(){
            var checkvalue = $('#firdetails').val();
            if(checkvalue == ''){
                $('#firdetails').prop('checked',true);
                $('#firdetails').val('1');
                $('#firdetails_agency_state').show();
            }
            else{
                $('#firdetails').prop('checked',false);
                $('#firdetails').val('');
                $('#firdetails_agency_state').hide();

            }

        });


        $(document).on('submit','#state_agency_form',function () {
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                var form_data = $(this).serialize();
                var court_type = $("#court_type option:selected").val();
                form_data = form_data+'&court_type='+court_type;
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('caveat/subordinate_court/add_state_agency'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('.add_loader').append(' <i class="status_refresh fa fa-refresh fa-spin"></i>');
                    },
                    success: function (data) {
                        var resArr = data.split('@@@');
                        if (resArr[0] == 1) {
                            $('#msg').show();
                            $('.status_refresh').remove();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        } else if (resArr[0] == 2) {
                            $('#msg').show();
                            $('.status_refresh').remove();
                            $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        }
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });

                    },
                    error: function (result) {
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });
            return false;
        });
    });

</script>