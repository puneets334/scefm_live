


<style>.caseno{height: 38px;} .year{padding: 0px 0px 0px 0px;background-color:#ffffff;border:none;border-radius: 0px;}</style>
<?php
$attribute = array('class' => 'form-horizontal form-label-left', 'name' => 'appellate_n_trail_court', 'id' => 'appellate_n_trail_court', 'autocomplete' => 'off');
echo form_open('#', $attribute);
?>
<?php echo validation_errors();



?>
<br>
<div class="col-sm-12 col-md-12 col-xs-12">
    <div class="form-group">
        <label class="control-label col-sm-5 input-sm"> </label>
        <div class="col-md-7 col-sm-12 col-xs-12"> 
            <label class="radio-inline"><input type="radio" id="court_type" name="appellate_court_type" value="<?php echo_data(url_encryption('1$$courtInfo')) ?>"  maxlength="1" checked> <strong>First Appellate Court </strong></label>
            <label class="radio-inline"><input type="radio" id="court_type" name="appellate_court_type" value="<?php echo_data(url_encryption('2$$courtInfo')) ?>"  maxlength="1"> <strong>Trial Court Information </strong></label>
        </div>
    </div>
</div><br><br>

<div class="row"><br> 
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">State <span style="color: red">*</span> :</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <select name="sub_state_id" id="sub_state_id" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                    <option value="" title="Select">Select State</option>
                    <?php
                    if (isset($state_list) && !empty($state_list)) {
                        foreach ($state_list as $dataRes) {
                             echo '<option  value="' . htmlentities(url_encryption($dataRes['state_code'] ), ENT_QUOTES) . '">' . htmlentities(strtoupper($dataRes['state_name']), ENT_QUOTES) . '</option>';
                            }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> District <span style="color: red">*</span> :</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <select name="sub_district" id="sub_district" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                    <option value="" title="Select">Select District</option>
                    <?php
//                    if (count($distt_list)) {
//                        foreach ($distt_list as $dataRes) {
//
//                            echo '<option  value="' . htmlentities(url_encryption(trim((string) $dataRes->DISTRICTCODE . '#$' . (string) $dataRes->DISTRICTNAME . '$$district'), ENT_QUOTES)) . '">' . htmlentities((string) $dataRes->DISTRICTNAME, ENT_QUOTES) . '</option>';
//                        }
//                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Establishment <span style="color: red">*</span> :</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <select name="subordinate_court" id="subordinate_court"  class="form-control input-sm filter_select_dropdown" style="width: 100%">
                    <option value="" title="Select">Select</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> </label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <label class="radio-inline"><input type="radio" id="cin_number" data-cnr="1" name="cin_or_case" value="<?php echo_data(url_encryption('2$$filingType')); ?>" onclick="showHide(this.value);" checked="checked" maxlength="1"> CNR Number </label>
                <label class="radio-inline"><input type="radio" id="case_or_filing_no" data-cnr="2" name="cin_or_case" value="<?php echo_data(url_encryption('1$$filingType')); ?>" onclick="showHide(this.value);"  maxlength="1"> Case Number</label>
            </div>
        </div>
        <div id="show_cnr_box">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">CNR Number <span style="color: red">*</span> :</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <div class="input-group">
                    <input id="cnr_number" name="cnr_number" autocomplete="off" onkeyup="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')" style="text-transform:uppercase"  placeholder="CNR Number" maxlength="18" class="form-control input-sm cnr_number"  type="text">
                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Case Number Record should be of 18 characters (eg.MHAU01-002516-2018).">
                        <i class="fa fa-question-circle-o"></i>
                    </span>
                </div>
            </div>
        </div>
        <div id="show_case_no_box" style="display: none;">
            <div class="form-group">
                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case Type <span style="color: red">*</span> :</label>
                <div class="col-md-7 col-sm-12 col-xs-12">
                    <select name="case_type" id="case_type_sub" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                        <option value="">Select</option>
                        <?php
                        echo '</option><option value="'.url_encryption(0).'">NOT IN LIST</option>';
                        if(isset($case_type) && !empty($case_type)){
                            foreach ($case_type as $k=>$v){
                                echo '<option value="'.url_encryption(trim($v->casecode)).'">'.strtoupper($v->casename).'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
                <div class="form-group dc_case_type_name" style="display:none;">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Case Type Name: <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <input type="text"  name="dc_case_type_name" id="dc_case_type_name" class="form-control input-sm" placeholder="Enter Case Type Name..">
                    </div>
                </div>
            <div class="form-group">
                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> </label>
                <div class="col-md-7 col-sm-12 col-xs-12">
                    <label class="radio-inline"><input type="radio" id="case_no" name="filing_type" value="<?php echo_data(url_encryption('2$$filing_type')); ?>" onclick="showlabel(this.value);"  maxlength="1"> Case No</label>
                    <label class="radio-inline"><input type="radio" id="filing_no" name="filing_type" value="<?php echo_data(url_encryption('1$$filing_type')); ?>" onclick="showlabel(this.value);"  maxlength="1"> Filing No</label>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-5 input-sm"> <div id="label_name"> Case No.And Year<span style="color: red">*</span> :</div> </label>
                <div class="col-sm-5 col-sm-12 col-xs-12">
                    <div class="input-group">
                        <input id="case_number" name="case_number" autocomplete="off" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" value="<?php echo htmlentities($case_nums, ENT_QUOTES); ?>"  placeholder="Case No" maxlength="5"  class="form-control input-sm caseno" type="text">
                        <span class="input-group-addon year" data-placement="bottom" data-toggle="popover" data-content="Case No. should be in digits only.">
                           <div class="input-group">
                      <select tabindex = '10' class="form-control input-sm filter_select_dropdown" id="case_year" name="case_year" style="height: 37px!important;width:75px!important;">
                                <option value="">Year</option>
                          <?php
                          $end_year = 47;
                          for ($i = 0; $i <= $end_year; $i++) {
                              $year = (int) date("Y") - $i;
                              if (url_encryption($case_year) == url_encryption($year)) {
                                  $sel = 'selected=selected';
                              } else {
                                  $sel = '';
                              }
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
    </div>

    <div class="col-md-6 col-sm-12 col-xs-12">

        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Judge Name :</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <div class="input-group">
                    <input id="judge_name" name="judge_name" onkeyup="return isLetter(event)" placeholder="Judge Name" maxlength="30" class="form-control input-sm" type="text">
                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Judge Name should be in characters.">
                        <i class="fa fa-question-circle-o" ></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Date of Decision <span style="color: red">*</span> :</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <div class="input-group">
                    <input class="form-control has-feedback-left" name="decision_date"  id="decision_date" placeholder="DD/MM/YYYY"  type="text">
                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Valid Date of Decision.">
                        <i class="fa fa-question-circle-o" ></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">CC Applied Date  :</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <div class="input-group">
                    <input class="form-control has-feedback-left" onchange="compareDate(this.id);" name="cc_applied_date"  id="cc_applied_date" placeholder="DD/MM/YYYY"  type="text">
                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Valid Certified Copy Applied Date.">
                        <i class="fa fa-question-circle-o" ></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> CC Ready Date :</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <div class="input-group">
                    <input class="form-control has-feedback-left" onchange="compareDate(this.id);" name="cc_ready_date"  id="cc_ready_date" placeholder="DD/MM/YYYY"  type="text">
                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Valid Certified Copy Ready Date.">
                        <i class="fa fa-question-circle-o"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-md-offset-4 col-md-4  col-sm-6 col-xs-12">
        </div>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                <span class="input-group search_case_spin"> <button type="submit" name="subordinate_search" id="subordinate_search" class="btn btn-primary">Search</button></span>
            </div>
        </div>
    </div>
<div class="row" id="searchDataDiv"></div>
<div class="row col-md-12">
    <input type="checkbox" id="fir_details_div" name="fir_details_div" value="">
    <label for="fir_details_div">Fir Details</label><br>
</div>
<!--fir start -->
<div id="fir_details" data-select2-id="fir_details" style="display: none;">
    <div class="row">
        <label class="control-label col-xs-12" style="font-size: large; text-align: center">FIR Details</label>
    </div>
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
        $('#case_type_sub').on('change', function () {
            var case_type_id = $(this).val();
            var case_type_selectedText = $("#case_type_sub option:selected").html();
            if(case_type_selectedText==='NOT IN LIST'){
                alert('Are you sure that it is not in list');
                $('.dc_case_type_name').show();
                $("#dc_case_type_name").prop('required',true);
            }else {
                $("#dc_case_type_name").prop('required',false);
                $('.dc_case_type_name').hide();}
        });
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
                if($("#fir_policeStation option:selected").val()!='')
                    $("#sub_high_court_info_search_case,#police_station_name,#complete_fir_number").prop("disabled", true);
                else
                    $("#police_station_name,#complete_fir_number").prop("disabled", false);
            }
            else if(court_type == 2){
                if($("#fir_policeStation option:selected").val()!='')
                    $("#police_station_name,#complete_fir_number").prop("disabled", true);
                else
                    $("#police_station_name,#complete_fir_number").prop("disabled", false);
            }

        });
    });

</script>


<!-- fir end -->
    <?php
    //$this->load->view('caveat/fir_details');
    ?>

<div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
        <?php
        $prev_redirect_url = 'caveat/extra_party';
        $next_redirect_url = 'uploadDocuments';
        ?>
        <a href="<?= base_url($prev_redirect_url) ?>" class="btn btn-primary" type="button">Previous</a>
        <input type="submit" class="btn btn-success" id="lower_court_save" value="SAVE" disabled>
        <?php
        if(isset($subordinate_court_details) && !empty($subordinate_court_details)){
            echo '<a href="'. base_url($next_redirect_url).'" class="btn btn-primary" type="button">Next</a>';
        }
        ?>

    </div>
</div>


<?php echo form_close(); ?>  

<script type="text/javascript">

    $(document).ready(function () {
        $(document).on('click','#fir_details_div',function(){
            var checkvalue = $('#fir_details_div').val();
            if(checkvalue == ''){
                $('#fir_details_div').prop('checked',true);
                $('#fir_details_div').val('1');
                $('#fir_details').show();
            }
            else{
                $('#fir_details_div').prop('false');
                $('#fir_details_div').val('');
                $('#fir_details').hide();

            }

        });

        $('#appellate_n_trail_court').on('submit', function () {
            var court_type = $("#court_type option:selected").val();
            if ($('#appellate_n_trail_court').valid()) {
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('caveat/subordinate_court/appellate_n_trial_info'); ?>",
                    data: $(this).serialize() + '&court_type=' + court_type,
                    beforeSend: function () {
                        $('#lower_court_save').val('Please wait...');
                       $('#lower_court_save').prop('disabled', true);
                    },
                    success: function (data) {
                        $('#lower_court_save').val('SAVE');
                        $('#lower_court_save').prop('disabled', false);
                        var resArr = data.split('@@@');
                        if (resArr[0] == 1) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        } else if (resArr[0] == 2) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");

                            setTimeout(function () {
                                window.location.href = '<?php echo base_url('caveat/subordinate_court'); ?>';
                            }, 1000);
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
<script type="text/javascript">
    $("#subordinate_search").on('click',function (e){
        e.preventDefault();
        var flag = true;
        var sub_state_id = $("#sub_state_id option:selected").val();
        var sub_district = $("#sub_district option:selected").val();
        var subordinate_court = $("#subordinate_court option:selected").val();
        var case_type_sub = $("#case_type_sub option:selected").val();
        var case_number = $.trim($("#case_number").val());
        var case_year = $.trim($("#case_year").val());
        var cin_or_case = $('input[name="cin_or_case"]:checked').val();
        var cnr_number = $.trim($("#cnr_number").val());
        var searchData = {};
        searchData.sub_state_id = sub_state_id;
        searchData.sub_district = sub_district;
        searchData.subordinate_court = subordinate_court;
        searchData.case_type_sub = case_type_sub;
        searchData.case_number = case_number;
        searchData.case_year = case_year;
        searchData.cnr_number = cnr_number;
        var case_cnr_type = '';
        if($('input[name="cin_or_case"]:checked')){
            case_cnr_type =  $('input[name="cin_or_case"]:checked').attr('data-cnr');
        }
        if(sub_state_id == ''){
            alert("Please select state.");
            $("#sub_state_id").focus();
            flag = false;
            return false;
        }
       else  if(sub_district == ''){
            alert("Please select district.");
            $("#sub_district").focus();
            flag = false;
            return false;
        }
        else  if(subordinate_court == ''){
            alert("Please select subordinate court name.");
            $("#subordinate_court").focus();
            flag = false;
            return false;
        }
        else if(case_cnr_type == 2){
              if(case_type_sub == ''){
                    alert("Please select case type.");
                    $("#case_type_sub").focus();
                    flag = false;
                    return false;
                }
              else  if(case_number == ''){
                  alert("Please fill case number.");
                  $("#case_number").focus();
                  flag = false;
                  return false;
              }
              else  if(case_year == ''){
                  alert("Please fill case year.");
                  $("#case_year").focus();
                  flag = false;
                  return false;
              }
        }
        else if(case_cnr_type == 1){
            if(cnr_number == ''){
                alert("Please fill cnr number.");
                $("#cnr_number").focus();
                flag = false;
                return false;
            }
        }
        if(flag == true){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('caveat/subordinate_court/searchSubordinateData'); ?>",
                data: searchData,
                beforeSend: function () {
                    $("#loader_div").html('<img id="loader_img" style="position: fixed;left: 60%;margin-top: 153px;margin-left: -100px;" src="<?php echo base_url('assets/images/loading-data.gif');?>">');
                    $('#lower_court_save').prop('disabled', true);
                },
                success: function (data) {
                    $('#lower_court_save').val('SAVE');
                    $('#lower_court_save').prop('disabled', false);
                    $("#loader_div").html('');
                    var resArr = data.split('@@@');
                    if (resArr[0] == 1) {
                        $('#msg').show();
                        $("#searchDataDiv").html('<div style="text-align: center; margin-bottom: 13px;">'+resArr[1]+'</div>');
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    } else if (resArr[0] == 2) {
                                $("#searchDataDiv").html(resArr[1]);
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




    function showlabel(val) {
        if (val == '<?php echo_data(url_encryption('1$$filing_type')); ?>') {
            document.getElementById('label_name').innerHTML = 'Filing No. <span style="color: red">*</span> :';
            document.getElementById('label_name_year').innerHTML = 'Filing Year <span style="color: red">*</span> :';
            document.getElementsByName('case_number')[0].placeholder = 'Filing no';
            document.getElementsByName('case_year')[0].placeholder = 'year';
        } else {
            document.getElementById('label_name').innerHTML = 'Case No. <span style="color: red">*</span> :';
            document.getElementById('label_name_year').innerHTML = 'Case Year <span style="color: red">*</span> :';
            document.getElementsByName('case_number')[0].placeholder = 'Case no';
            document.getElementsByName('case_year')[0].placeholder = 'year';
        }
    }

    function showHide(val) {

        if (val == '<?php echo_data(url_encryption('1$$filingType')); ?>') {
            document.getElementById('show_case_no_box').style.display = 'block';
            document.getElementById('show_cnr_box').style.display = 'none';
            $('#case_no').prop('checked', true);
            //get case type
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var establish_code =  $('#subordinate_court option:selected').val();
            if(establish_code){
                $.ajax({
                    type: "POST",
                    /*data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, establish_code: establish_code},
                    url: "<//?php echo base_url('caveat/AjaxCalls/getCaseTypeByEstablishCode'); ?>",*/
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, est_code: establish_code},
                    url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/OpenAPIcase_type_list'); ?>",
                    success: function (data)
                    {
                        $('#case_type_sub').html(data);
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
            else{
                alert("Please select subordinate court name ");
                var not_in_list='<?php echo htmlentities(url_encryption(trim('0##NOT IN LIST'), ENT_QUOTES));?>';
                $("#case_type_sub").html('<option>Select Case Type</option><option value='+not_in_list+'>NOT IN LIST</option>');
                $("#subordinate_court").focus();
                return false;
            }
        }
        else if (val == '<?php echo_data(url_encryption('2$$filingType')); ?>') {
            document.getElementById('show_cnr_box').style.display = 'block';
            document.getElementById('show_case_no_box').style.display = 'none';
            $("#cnr_number").val('');
            $('#case_no').prop('checked', false);

        } else if (val == '<?php echo_data(url_encryption('3$$filingType')); ?>') {
            document.getElementById('trial_show_case_no_box').style.display = 'block';
            document.getElementById('trial_show_cnr_box').style.display = 'none';
        } else if (val == '<?php echo_data(url_encryption('4$$filingType')); ?>') {
            document.getElementById('trial_show_cnr_box').style.display = 'block';
            document.getElementById('trial_show_case_no_box').style.display = 'none';
        }
    }
        $("#subordinate_court").change(function(){
            $("#show_case_no_box").hide();
            $("#show_cnr_box").show();
            $('#case_no').prop('checked', true);
            //get case type
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var establish_code =  $('#subordinate_court option:selected').val();
            if($.trim($("input[name='cin_or_case']:checked").val()) == '92858549527252343956956055.BE16F496') {
                $("#show_case_no_box").show();
                $("#show_cnr_box").hide();
                if (establish_code) {
                    $.ajax({
                        type: "POST",
                        data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, establish_code: establish_code},
                        url: "<?php echo base_url('caveat/AjaxCalls/getCaseTypeByEstablishCode'); ?>",
                        success: function (data) {
                            $('#case_type_sub').html(data);
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
                } else {
                    alert("Please select subordinate court name ");
                    var not_in_list='<?php echo htmlentities(url_encryption(trim('0##NOT IN LIST'), ENT_QUOTES));?>';
                    $("#case_type_sub").html('<option value="">Select Case Type</option><option value='+not_in_list+'>NOT IN LIST</option>');
                    $("#case_type_sub").prop('required',true);
                    $("#subordinate_court").focus();
                    return false;
                }
            }
            if(establish_code == ''){
                alert("Please select subordinate court name ");
                var not_in_list='<?php echo htmlentities(url_encryption(trim('0##NOT IN LIST'), ENT_QUOTES));?>';
                $("#case_type_sub").html('<option value="">Select Case Type</option><option value='+not_in_list+'>NOT IN LIST</option>');
                $("#case_type_sub").prop('required',true);
                    $("#subordinate_court").focus();
                    return false;
            }
        });
    $('#sub_state_id').change(function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#sub_district').val('');
        $('#subordinate_court').val('');
        var get_state_id = $(this).val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
            url: "<?php echo base_url('caveat/AjaxCalls/getDistrict'); ?>",
            success: function (data)
            {
                $('#sub_district').html(data);
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
    $('#sub_district').change(function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#subordinate_court').val('');
        var get_distt_id = $(this).val();
        var get_state_id = $('#sub_state_id').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, get_state_id: get_state_id, get_distt_id: get_distt_id},
            url: "<?php echo base_url('caveat/AjaxCalls/getEstablishment'); ?>",
            success: function (data)
            {
                $('#subordinate_court').html(data);
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

    $('#trial_state_id').change(function () {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#trial_district').val('');
        $('#trial_subordinate_court').val('');
        var get_state_id = $(this).val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
            url: "<?php echo base_url('Webservices/get_district'); ?>",
            success: function (data)
            {
                $('#trial_district').html(data);
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
    $('#trial_district').change(function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#trial_subordinate_court').val('');
        var get_distt_id = $(this).val();
        var get_state_id = $('#trial_state_id').val();

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, get_state_id: get_state_id, get_distt_id: get_distt_id},
            url: "<?php echo base_url('Webservices/get_lower_court'); ?>",
            success: function (data)
            {
                $('#trial_subordinate_court').html(data);
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