<style>.caseno{height: 38px;} .year{padding: 0px 0px 0px 0px;background-color:#ffffff;border:none;border-radius: 0px;}</style>
<?php
$attribute = array('class' => 'form-horizontal form-label-left', 'name' => 'sub_high_court_info_search_case', 'id' => 'sub_high_court_info_search_case', 'autocomplete' => 'off');
echo form_open('#', $attribute);
?>

<div class="row"><br>
    <div class="col-sm-4 col-xs-12">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">High Court <span style="color: red">*</span>:</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <select  name="hc_court" id="hc_court" class="form-control input-sm filter_select_dropdown hc_court" required style="width: 100%">
                    <option>Select High Court</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-xs-12">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Bench <span style="color: red">*</span>:</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <select name="hc_court_bench" id="hc_court_bench" class="form-control input-sm filter_select_dropdown hc_court_bench" required style="width: 100%" >
                    <option>Select Bench</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case Type <span style="color: red">*</span> :</label>
            <div class="col-md-6 col-sm-12 col-xs-12">

                <select name="case_type" id="case_type" class="form-control input-sm filter_select_dropdown case_type" required style="width: 100%">
                    <option value="">Select</option>
                    <?php
                    if(isset($case_type) && !empty($case_type)){
                    foreach ($case_type as $dataActs) {
                        $value = (string) $dataActs->CASETYPE;
                        if ($dataActs->FULLFORM != '') {
                            $full_form = '(' . htmlentities(strtoupper((string) $dataActs->casecode), ENT_QUOTES) . ')';
                        } else {
                            $full_form = '';
                        }
                        echo '<option ' . htmlentities($sel, ENT_QUOTES) . ' value="' . htmlentities(url_encryption(trim($dataActs->casecode . '#$' . strtoupper((string) $dataActs->casename) . $full_form . '$$case_type'), ENT_QUOTES)) . '">' . '' . htmlentities(strtoupper((string) $dataActs->casename), ENT_QUOTES) . $full_form . '</option>';
                    }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-xs-12 hc_case_type_name" style="display:none;">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Case Type Name: <span style="color: red">*</span>:</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <input type="text"  name="hc_case_type_name" id="hc_case_type_name" class="form-control input-sm" placeholder="Enter Case Type Name..">
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Case No. And Year<span style="color: red">*</span> :</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <div class="input-group">
                    <input id="hcase_number" name="hcase_number" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" value=""  placeholder="Case No" required maxlength="7"  class="form-control input-sm hcase_number caseno" type="text">
                    <span class="input-group-addon year" data-placement="bottom" data-toggle="popover" data-content="Case No. should be in digits only.">
                        <div class="input-group">
                      <select tabindex = '10' class="form-control input-sm filter_select_dropdown" id="hcase_year" name="hcase_year" style="height: 37px!important;width:75px!important;">
                                <option value="">Year</option>
                         <?php
                         $end_year = 47;
                         for ($i = 0; $i <= $end_year; $i++) {
                             $year = (int) date("Y") - $i;
                             if (url_encryption($data_to_be_populated['year']) == url_encryption($year)) {
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
    <!--<div class="col-md-4 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case Year <span style="color: red">*</span> :</label>
            <div class="col-sm-2 col-md-4 col-xs-12" >

            </div>
        </div>
    </div>-->
</div>
<div class="row">
    <div class="col-md-offset-4 col-md-4  col-sm-6 col-xs-12">
<!--        <div class="col-md-6 col-sm-6 col-xs-6">-->
<!--            <div class="input-group">-->
<!--                <img src="--><?php //echo base_url('uploaded_docs/captcha/' . $captcha['filename']); ?><!--" class="form-control captcha-img">-->
<!--                <span class="input-group-addon" data-placement="bottom" data-toggle="popover">-->
<!--                    <div class="input-group-text">-->
<!--                        <img src="--><?php //echo base_url('assets/images/refresh.png') ?><!--" height="20px" width="20px"  alt="refresh" class="refresh_cap" />-->
<!--                    </div>-->
<!--                </span>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="col-md-4 col-sm-6 col-xs-6">-->
<!--            <input type="text" id="userCaptcha" name="userCaptcha" class="form-control" maxlength="6"  autocomplete="off" placeholder="Captcha Text">-->
<!--        </div>-->
    </div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
            <span class="input-group search_case_spin"> <button type="submit" name="search_case1" id="search_case1" class="btn btn-primary" >Search</button></span>
        </div>
    </div>   
</div>




    <?php
        // $this->load->view('caveat/fir_details');
    ?>
<?php echo form_close();

?>

<div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
        <?php
        $prev_redirect_url = 'caveat/extra_party';
        $next_redirect_url = 'uploadDocuments';
        ?>
        <a href="<?= base_url($prev_redirect_url) ?>" class="btn btn-primary" type="button">Previous</a>
<!--        <a href="--><?//= base_url($next_redirect_url) ?><!--" class="btn btn-primary" type="button">Next</a>-->

        <?php
        if(isset($subordinate_court_details) && !empty($subordinate_court_details)){
            echo '<a href="'. base_url($next_redirect_url).'" class="btn btn-primary" type="button">Next</a>';
        }
        ?>

    </div>
</div>
<div class="clearfix"></div>
<div class="ln_solid"></div>
<div class="panel panel-default panel-body" id="case_result_hc" style="display: none;">
    <h4 style="text-align: center;color: #31b0d5"> Case Details </h4>
    <?php
    $attribute = array('class' => 'form-horizontal form-label-left', 'name' => 'high_court_info', 'id' => 'high_court_info', 'autocomplete' => 'off');
    echo form_open('#', $attribute);
    $case_type_pet_title = 'Appellant / Petitioner / Plaintiff / Complainant';
    $case_type_res_title = 'Opponent / Respondent / Defendant / Accused';
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6" id="hide_cnr_nums">
                <div class="form-group">
                    <label class="control-label col-md-4 text-right" for="cnr">CNR No. :</label>
                    <div class="col-md-8" id="cnr_nums" name="cnr_nums" style="margin-top: 8px;"></div>
                </div> 
            </div>
            <div class="col-md-6" >
                <div class="form-group" id="hide_case_type_name">
                    <label class="control-label col-md-4 text-right"  for="casetype">Case Type :</label>
                    <div class="col-md-8" id="case_type_name" name="case_type_name" style="margin-top: 8px;"></div>
                </div> 
            </div>
            <div class="col-md-6">
                <div class="form-group" id="hide_case_num_case_year">
                    <label class="control-label col-md-4 text-right" for="cnr">Case Number :</label>
                    <div class="col-md-8" id="case_num_case_year" name="case_num_case_year" style="margin-top: 8px;"> </div>
                </div> 
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6" id="hide_petitioner_name">
                <div class="form-group">
                    <label class="control-label col-md-4 text-right" for="petitioner"><?php echo_data($case_type_pet_title);?> :</label>
                    <div class="col-md-8" id="petitioner_name" name="petitioner_name" style="margin-top: 8px;"></div>
                </div> 
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6" id="hide_respondent_name">
                <div class="form-group">
                    <label class="control-label col-md-4 text-right" for="respondent_name"><?php echo_data($case_type_res_title);?> :</label>
                    <div class="col-md-8" id="respondent_name" name="respondent_name" style="margin-top: 8px;"> </div>
                </div> 
            </div>

            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Date of Decision :</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <div class="input-group">
                            <input class="form-control has-feedback-left" name="decision_date"  autocomplete="off" id="hc_decision_date" placeholder="DD/MM/YYYY"  type="text">
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
                            <input class="form-control has-feedback-left" autocomplete="off" name="cc_applied_date" value="" id="hc_cc_applied_date" placeholder="DD/MM/YYYY"  type="text">
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
                            <input class="form-control has-feedback-left" autocomplete="off" name="cc_ready_date" value="" id="hc_cc_ready_date" placeholder="DD/MM/YYYY"  type="text">
                            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                            <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Valid Certified Copy Ready Date.">
                                <i class="fa fa-question-circle-o"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            </br>
            <div class="row col-md-12">
                <input type="checkbox" id="firdetailsdiv" name="firdetailsdiv" value="">
                <label for="firdetailsdiv">Fir Details</label><br>
            </div>
            <!--fir start -->
            <div id="firdetails" data-select2-id="firdetails" style="display: none;">
                <div class="row">
                    <label class="control-label col-xs-12" style="font-size: large; text-align: center">FIR Details</label>
                </div>
                <div class="col-sm-5 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">State <span style="color: red">*</span>:</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <select tabindex="-1" name="fir_state" id="firstate" class="form-control input-sm filter_select_dropdown" style="width: 100%" >
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
                            <select tabindex="-1" name="fir_district" id="firdistrict" class="form-control input-sm filter_select_dropdown" style="width: 100%" >
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
                                <select tabindex="-1" name="fir_policeStation" id="firpoliceStation" class="form-control input-sm filter_select_dropdown" style="width: 100%" >
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
                                    <input tabindex="24" id="firnumber" name="fir_number" maxlength="10" onkeyup="return isNumber(event)" placeholder="FIR Number" class="form-control input-sm age_calculate" type="text">
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
                                    <select tabindex="-1" class="form-control input-sm filter_select_dropdown" id="firyear" name="fir_year" style="width: 100%" >
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
                                    <input tabindex="26" id="policestationname" name="police_station_name" placeholder="Police Station Name" class="form-control" type="text" >
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
                                    <input tabindex="27" id="completefirnumber" name="complete_fir_number" maxlength="15" onkeyup="return isNumber(event)" placeholder="FIR Number" class="form-control input-sm age_calculate" type="text">
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
                    $(document).on('change','#firstate',function(){
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
                                $("#firdetails #firdistrict").html(data);
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
                    $(document).on('change','#firdistrict',function(){
                        $('#fir_policeStation').val('');
                        var court_type = parseInt($("#court_type option:selected").attr('data-select2-id'));
                        var get_distt_id = $(this).val();
                        var CSRF_TOKEN = 'CSRF_TOKEN';
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                        var fir_state = $("#firstate option:selected").val();
                        var fir_district = $("#firdistrict option:selected").val();
                        $.ajax({
                            type: "POST",
                            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, fir_state: fir_state, fir_district: fir_district},
                            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_police_station_fir'); ?>",
                            success: function (data) {
                                $('#firpoliceStation').html(data);
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

                    $(document).on('blur','#policestationname',function(){
                        var court_type = parseInt($("#court_type option:selected").attr('data-select2-id'));
                        $("#sub_high_court_info_search_case,#firpoliceStation,#firnumber, #firyear").prop("disabled", false);
                        $("#fir_policeStation,#fir_number, #fir_year").prop("disabled", false);
                        if(court_type == 110){
                            if( $.trim($('#policestationname').val()) !=='')
                                $("#sub_high_court_info_search_case,#firpoliceStation,#firnumber, #firyear").prop("disabled", false);
                            else
                                $("#fir_policeStation,#firnumber, #firyear").prop("disabled", true);
                        }
                        else if(court_type == 2){
                            if( $.trim($('#police_station_name').val())=='')
                                $("#fir_policeStation,#firnumber, #firyear").prop("disabled", false);
                        }

                    });
                    $('#firpoliceStation').on('blur', function() {
                        // var court_type = $("#court_type option:selected").val();
                        var court_type = parseInt($("#court_type option:selected").attr('data-select2-id'));
                        if(court_type == 110){
                            if($("#fir_policeStation option:selected").val()!='')
                                $("#sub_high_court_info_search_case,#policestation_name,#completefirnumber").prop("disabled", true);
                            else
                                $("#policestationname,#completefirnumber").prop("disabled", false);
                        }
                        else if(court_type == 2){
                            if($("#fir_policeStation option:selected").val()!='')
                                $("#policestationname,#completefirnumber").prop("disabled", true);
                            else
                                $("#police_station_name,#complete_fir_number").prop("disabled", false);
                        }

                    });
                });

            </script>


            <!-- fir end -->

            <div class="col-md-offset-5 col-md-2 col-sm-offset-3 col-sm-6 col-xs-12">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                    <input type="submit" class="btn btn-primary" id="lower_court_save" value="SAVE">
                </div>
            </div>
        </div>
    </div>

</div>
<?php echo form_close(); ?>  

<script type="text/javascript">
    //-----HIGH COURT CASE SEARCH---------//
    $(document).ready(function () {
        $('#case_type').on('change', function () {
            var case_type = $(this).val();
            var case_type_selectedText = $("#case_type option:selected").html();
            if(case_type_selectedText==='NOT IN LIST'){
                alert('Are you sure that it is not in list');
                $('.hc_case_type_name').show();
                $("#hc_case_type_name").prop('required',true);
            }else {
                $("#hc_case_type_name").prop('required',false);
                $('.hc_case_type_name').hide();
            }
        });
        $(document).on('click','#firdetailsdiv',function(){
            var checkvalue = $('#firdetailsdiv').val();
            if(checkvalue == ''){
                $('#firdetailsdiv').prop('checked',true);
                $('#firdetailsdiv').val('1');
                $('#firdetails').show();
            }
            else{
                $('#firdetailsdiv').prop('false');
                $('#firdetailsdiv').val('');
                $('#firdetails').hide();

            }

        });
        //get bench
        $("#hc_court").on('change',function(){
            var state_code = $(this).val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('caveat/AjaxCalls/getBench'); ?>",
                data: {state_code:state_code},
                success: function (data) {
                    $("#hc_court_bench").html(data);
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
        //get case type by bench
        $("#hc_court_bench").on('change',function(){
            var bench_est_code = $(this).val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('caveat/AjaxCalls/getCaseTypeByBenchEstabCode'); ?>",
                data: {bench_est_code:bench_est_code},
                success: function (data) {
                    $("#case_type").html(data);
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
        $('#sub_high_court_info_search_case').on('submit', function () {
            if ($('#sub_high_court_info_search_case').valid()) {
                var hc_info_id = $("#court_type option:selected").val();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                var form_data = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('caveat/subordinate_court/search_case_data'); ?>",
                    data: $(this).serialize() + '&hc_info_id=' + hc_info_id,
                    beforeSend : function(){
                        $("#loader_div").html('<img id="loader_img" style="position: fixed;left: 60%;margin-top: 117px;margin-left: -151px;"  src="<?php echo base_url('assets/images/loading-data.gif');?>">');
                    },
                    success: function (data) {
                        $("#loader_div").html('');
                        var resArr = data.split('@@@');
                        if (resArr[0] == 1) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            $("#case_result_hc").show();
                            $('#hide_cnr_nums').hide();
                            $('#hide_case_type_name').hide();
                            $('#hide_case_num_case_year').hide();
                            $('#hide_petitioner_name').hide();
                            $('#hide_respondent_name').hide();
                            $('#hc_decision_date').val('');
                            $('#hc_decision_date').attr('readonly',false);
                            $('#hc_decision_date').attr('required',true);
                            $('#hc_cc_applied_date').attr('required',true);
                            $('#hc_cc_ready_date').attr('required',true);
                        } else if (resArr[0] == 2) {
                            var resVal = resArr[1].split('$#');
                            $("#case_result_hc").show();
                            $('#hide_cnr_nums').show();
                            $('#hide_case_type_name').show();
                            $('#hide_case_num_case_year').show();
                            $('#hide_petitioner_name').show();
                            $('#hide_respondent_name').show();
                            $('#cnr_nums').html(resVal[0]);
                            $('#case_type_name').html(resVal[1]);
                            $('#case_num_case_year').html(resVal[2]);
                            $('#petitioner_name').html(resVal[3]);
                            $('#respondent_name').html(resVal[4]);
                            if(resVal[5]){
                                $('#hc_decision_date').val(resVal[5]);
                                $('#hc_decision_date').attr('readonly',true);
                            }
                            $('#hc_decision_date').attr('required',true);
                            $('#hc_cc_applied_date').attr('required',true);
                            $('#hc_cc_ready_date').attr('required',true);
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


        $('#high_court_info').on('submit', function () {
            if ($('#high_court_info').valid()) {
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                var form_data = $(this).serialize();
                var hc_court = $("#hc_court option:selected").val();
                var hc_court_bench = $("#hc_court_bench option:selected").val();
                var case_type = $("#case_type option:selected").val();
                var hc_case_type_name = $("#hc_case_type_name").val();
                var case_number = $("#hcase_number").val();
                var case_year = $("#hcase_year").val();
                form_data = form_data+'&hc_court='+hc_court+'&hc_court_bench='+hc_court_bench+'&case_type='+case_type+'&hc_case_type_name='+hc_case_type_name+'&case_number='+case_number+'&case_year='+case_year;
                // console.log(form_data);
                // return false;
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('caveat/subordinate_court/add_high_court_info'); ?>",
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
                            // setTimeout(function () {
                            //     $('#msg').hide();
                            // }, 3000);
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
            } else {
                return false;
            }
        });
    });

</script>