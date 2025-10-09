<?php
$this->load->view('certificate/certificate_breadcrumb');
?>
<style>
    .form-group {
        margin-top: 10px;
    }

</style>
<div class="panel panel-default">
    <h4 style="text-align: center;color: #31B0D5">Earlier Court Details</h4>
    <div class="panel-body">
        <?php
        $attribute = array('class' => 'form-horizontal', 'name' => 'certificate_details', 'id' => 'certificate_details', 'autocomplete' => 'off');
        echo form_open('certificate/AddDetails/add_certificate_request', $attribute);
        ?>
        <div class="row" id="LCDetails">

           <?php $i=0;
           $length=count($lowerCtDetails['details']);
           while($i<$length)
           {
               $caseno=$lowerCtDetails['details'][$i]['lct_caseno'];
               $state=$lowerCtDetails['details'][$i]['l_state'];
               $case_year=$lowerCtDetails['details'][$i]['lct_caseyear'];
               $agency=$lowerCtDetails['details'][$i]['agency_name'];
               $est_code=$lowerCtDetails['details'][$i]['estab_code'];
               $case_type=$lowerCtDetails['details'][$i]['type_sname'];
               echo "<div class='col-lg-4 col-md-4 col-sm-4  col-xs-4'>";
                   echo "<input type='checkbox' name='LC_data[]' id='LC$caseno$case_year' value='$state#$caseno#$case_year#$est_code#null'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$agency  $case_type   $caseno/$case_year";
                   echo "</div>";
               $i++;
           }

           ?>

        </div>
    <br/>
        <div class="row">
        <div class="form-group">
            <label class="control-label col-sm-3 input-sm"> Select Party <span style="color: red">*</span> :</label>
            <div class="col-md-5 col-sm-5 col-xs-5">
                <select name="party" id="party" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                    <option value="" title="select">Select Party</option>
                    <?php $i=0;
                        //print_r ($parties);
                        $length=count($parties['parties']);
                         while($i<$length)
                         {
                             $id=$parties['parties'][$i]['id'];
                             $name=$parties['parties'][$i]['parties'];
                             echo "<option  value='$id'>$name</option>";
                             $i++;
                         }
                    ?>
                </select>
            </div>
        </div>
        </div>
            <br/>
            <div class="row">
                <div class="col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">State<span style="color: red">*</span>:</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <select tabindex = '21' name="state_id" id="state_id" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                                <option value="" title="Select">Select State</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">District <span style="color: red">*</span>:</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <select tabindex = '22' name="district_id" id="district_id" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                                <option value="" title="Select">Select District</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Jail <span style="color: red">*</span>:</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <select tabindex = '23' name="jailCode" id="jailCode" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                                <option value="" title="Select">Select Jail</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        <div class="row">
           <center> <h1>Other case Details</h1></center>
        </div>
        <div class="row">
            <div class="form-group">
                <label class="control-label col-sm-4 input-sm"> Select Court <span style="color: red">*</span> :</label>
                <div class="col-md-7 col-sm-12 col-xs-12">
                    <label class="radio-inline"><input  tabindex = '2' type="radio" id="radio_selected_court" name="radio_selected_court" onchange="get_court_as(this.value)" value="1" maxlength="2" <?php echo $hcchecked; ?>> High Court </label>
                    <label class="radio-inline"><input tabindex = '3' type="radio" id="radio_selected_court" name="radio_selected_court" onchange="get_court_as(this.value)" value="3" maxlength="2" <?php echo $dcchecked; ?>> District Court </label>
                </div>
            </div>
        </div>
        <div id="high_court_info" style="display: none;">
            <div class="row">
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">High Court <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <select tabindex = '7' name="hc_court" id="hc_court" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                            <option value="" title="Select">Select High Court</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Bench <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <select name="hc_court_bench" id="hc_court_bench" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                            <option value="" title="Select">Select High Court Bench</option>
                        </select>
                    </div>
                </div>
            </div>
            </div>
            <br/>
            <div class="row">
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case Type <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <select tabindex = '8' name="case_type_id" id="case_type_id" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                            <option value="" title="Select">Select Case Type</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case No. <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <div class="input-group">
                            <input tabindex = '9'  id="case_number" name="case_number" maxlength="10" onkeyup="return isNumber(event)" placeholder="Case No."  class="form-control input-sm age_calculate" type="text">
                            <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Related Case Number">
                                <i class="fa fa-question-circle-o"  ></i>
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
                            <select tabindex = '10' class="form-control input-sm filter_select_dropdown" id="case_year" name="case_year" style="width: 100%">
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
                    </div>
                </div>
            </div>
        </div>
            <br/>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 ">
                    <div class="col-sm-4 col-xs-12 col-md-offset-5">
                        <div class="form-group">
                            <div class="col-md-offset-3">
                                <button type="button" id="add" onclick="addDetails();"  class="btn btn-success"> Add </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="district_court_info" style="display: none;">
            <div class="row">
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">State <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <select tabindex = '11' name="state" id="state" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                            <option value="" title="Select">Select State</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">District <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <select tabindex = '12' name="district" id="district" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                            <option value="" title="Select">Select District</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Establishment <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <select tabindex = '13' name="establishment" id="establishment" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                            <option value="" title="Select">Select Establishment</option>
                        </select>
                    </div>
                </div>
            </div>
            </div>
            <br/>
            <div class="row">
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case Type <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <select tabindex = '14' name="dc_case_type_id" id="dc_case_type_id" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                            <option value="" title="Select">Select Case Type</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case No. <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <div class="input-group">
                            <input tabindex = '15' id="dc_case_number" name="dc_case_number" maxlength="10" onkeyup="return isNumber(event)" placeholder="Case No."  class="form-control input-sm age_calculate" type="text">
                            <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Related Case Number">
                                <i class="fa fa-question-circle-o"  ></i>
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
                            <select tabindex = '16' class="form-control input-sm filter_select_dropdown" id="dc_case_year" name="dc_case_year" style="width: 100%">
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
                    </div>
                    </div>
                </div>
            </div>
        <br/>
        <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 ">
            <div class="col-sm-4 col-xs-12 col-md-offset-5">
                <div class="form-group">
                    <div class="col-md-offset-3">
                        <button type="button" id="add" onclick="addDetails();"  class="btn bg-olive btn-flat pull-right"><i class="fa fa-save"></i> Add </button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
        <br>
        <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12 ">
            <div class="col-sm-4 col-xs-12 col-md-offset-5">
                <div class="form-group">
                    <div class="col-md-offset-3">
                        <button type="button" id="save_surrender" onclick="record_save('S');"  class="btn btn-success"> Request Surrender Certificate </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-12 col-xs-12 ">
            <div class="col-sm-4 col-xs-12 col-md-offset-5">
                <div class="form-group">
                    <div class="col-md-offset-3">
                        <button type="button" id="save_custody" onclick="record_save('C');"  class="btn btn-success">Request Custody Certificate</button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        get_fir_state_list();
    });
    function get_court_as(court_as) {

        if (court_as == '1') {

            $('#district_court_info').hide();
            $('#high_court_info').show();
            get_high_court_list(court_as);
        } else if (court_as == '3') {

            $('#district_court_info').show();
            $('#high_court_info').hide();
            get_state_list();

        }
    }



    function addDetails(){
        var option=$("input[name='radio_selected_court']:checked"). val();
        if(option==3){
                        //District court
            var estab_id = $('#establishment option:selected').val();
            estname=$('#establishment option:selected').text();
            case_type = $('#dc_case_type_id option:selected').val();
            case_number = $('#dc_case_number').val();
            case_year = $('#dc_case_year option:selected').val();
            case_type_text = $('#dc_case_type_id option:selected').text();
            var state=$("#state").val();
            document.getElementById('LCDetails').innerHTML=document.getElementById('LCDetails').innerHTML+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "+
                "<input type='checkbox' name='LC_data[]' checked id='LC"+case_number+case_year+"' value="+state+"#"+case_number+"#"+case_year+"#"+estab_id+"#d"+">"+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+estname+" "+case_type_text+"-"+case_number+" / "+case_year+"";
            $('#state').empty().trigger('change');
            $('#establishment').empty().trigger('change');
            $('#dc_case_type_id').empty().trigger('change');
            $('#dc_case_year').empty().trigger('change');
            $('#district_court_info input').val('');

        }
        if(option==1)                        // High Court
        {
            var case_no=$("#case_number").val();
            var case_year_text=$("#case_year option:selected").text();
            var case_year=$("#case_year option:selected").val();
            var casetype=$("#case_type_id option:selected").text();
            var hc_court = $('#hc_court option:selected').val();
            var hc_court_bench = $('#hc_court_bench option:selected').val();
            var hc_court_name = $('#hc_court option:selected').text();
            document.getElementById('LCDetails').innerHTML=document.getElementById('LCDetails').innerHTML+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "+
                "<input type='checkbox' name='LC_data[]' checked id='LC"+case_no+case_year+"' value="+null+"#"+case_no+"#"+case_year+"#"+hc_court_bench+"#h"+">"+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+hc_court_name+" "+casetype+"-"+case_no+" / "+case_year_text+"";

            $('#hc_court').val('').trigger('change');
            $('#hc_court_bench').empty().trigger('change');
            $('#case_type_id').empty().trigger('change');
            $('#case_year').val('').trigger('change');
            $('#high_court_info input').val('');
        }
    }


    /*-------- start function save ------*/

    function record_save ($id){
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var hcCases='';
        $('#LCDetails').find("input:checked").each(function() {
            if($(this).is(':checked')){
                hcCases+= $(this).val()+',';
            }

        });
        var certificate_type=$id;
        var party= $("#party option:selected").val();
        var party_name=$("#party option:selected").text();
        var jailCode= $("#jailCode option:selected").val();
        var state='<?=$state?>';
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,party: party ,jailCode: jailCode,certificate_type: certificate_type,party_name:party_name,hcCases:hcCases,state:state},
            url: "<?php echo base_url('certificate/AddDetails/add_certificate_request'); ?>",

            success: function (data)
            {
                var resArr = data.split('@@@');
                if (resArr[0] == 1 || resArr[0] == 2) {
                    alert(resArr[1]);
                    if (confirm("Do you want to request more in the same diary number?"))
                        location.reload();
                    else
                        //$('#certificate_details').attr("disabled","disabled");
                        $("*", "#certificate_details").prop('disabled',true);
                }

                if (resArr[0] == 3) {
                    resArr[1]=resArr[1].replaceAll('<p>','');
                    resArr[1]=resArr[1].replaceAll('</p>','');
                    result = resArr[1].replaceAll('.', '.\r\n ');
                    alert(result);
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


    }//end of function record_save ..



    /*-------- end function save ------*/

    function get_high_court_list(court_as) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, selected_post_id: court_as},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_high_court'); ?>",
            success: function (data)
            {
                $('#hc_court').html(data);
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

    $('#hc_court').change(function () {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#case_type_id').val('');

        var high_court_id = $(this).val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, high_court_id: high_court_id, court_type: '1'},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_hc_bench_list'); ?>",
            success: function (data)
            {
                $('#hc_court_bench').html(data);
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

    //----------Get Case Type List----------------------//
    $('#hc_court_bench').change(function () {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#case_type_id').val('');

        var hc_bench_id = $(this).val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, hc_bench_id: hc_bench_id, court_type: '1'},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_hc_case_type_list'); ?>",
            success: function (data)
            {
                $('#case_type_id').html(data);
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

    function get_state_list() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#state').val('');
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_state_list'); ?>",
            success: function (data)
            {
                $('#state').html(data);
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

    //----------Get District List----------------------//
    $('#state').change(function () {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#district').val('');

        var get_state_id = $(this).val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_district_list'); ?>",
            success: function (data)
            {
                $('#district').html(data);
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

    $('#district').change(function () {
        $('#establishment').val('');

        var get_distt_id = $(this).val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var state_id = $("#state option:selected").val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: state_id, get_distt_id: get_distt_id},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_establishment_list'); ?>",
            success: function (data)
            {
                if (data.indexOf('ERROR') != -1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + data + " <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    $('#establishment').html('<option value=""> Select Establishment </option>');
                } else {
                    $('#msg').hide();
                    $('#establishment').html(data);
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

    $('#establishment').change(function () {

        var estab_id = $('#establishment').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, est_code: estab_id},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/OpenAPIcase_type_list'); ?>",
            success: function (data)
            {
                $('#dc_case_type_id').html(data);
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

  /* ------------------GET STATE LIST------------ */
    function get_fir_state_list()
    {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#state_id').val('');

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_icmis_state_list'); ?>",
            success: function (data)
            {
                $('#state_id').html(data);
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

    $('#state_id').change(function () {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#district_id').val('');

        var get_state_id = $(this).val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_icmis_district_list'); ?>",
            success: function (data)
            {
                $('#district_id').html(data);
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

    $('#district_id').change(function () {
        $('#jailCode').val('');

        var get_distt_id = $(this).val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var state_id = $("#state_id option:selected").val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: state_id, get_distt_id: get_distt_id},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_jail_list'); ?>",
            success: function (data) {
                $('#jailCode').html(data);
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




    /* xxxxxxxxxxxxxxxx */

</script>