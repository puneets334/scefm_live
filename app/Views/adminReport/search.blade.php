@extends('layout.app')
@section('content')
<link href="<?= base_url() ?>assets/css/bootstrap-multiselect.css" rel="stylesheet">
<link href="<?= base_url() ?>assets/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css" rel="stylesheet">
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-section dashboard-tiles-area"></div>
            <div class="dashboard-section">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="dash-card">
                            {{-- Page Title Start --}}
                            <div class="title-sec">
                                <h5 class="unerline-title"> Download Efiled Cases </h5>
                                <!-- <a href="javascript:void(0)" class="quick-btn pull-right mb-3" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                            </div>
                            {{-- Page Title End --}}
                            {{-- Main Start --}}
                            <div class="right_col" role="main">
                                <?php
                                $attributes = array("class" => "form-horizontal", "id" => "getResult", "name" => "getResult", 'autocomplete' => 'off');
                                echo form_open('#', $attributes);
                                ?>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="row" id="printData">
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="row mb-3 w-100 align-items-center">
                                                    <div class="col-12">
                                                    <label class="col-form-label" for="sc_case_type">Case Type <span style="color: red">*</span>:</label>
                                                    </div>
                                                    <div class="col-12 pe-0">
                                                        <select name="sc_case_type[]" id="sc_case_type" class=" js-example-basic-multiple form-select input-sm form-control cus-form-ctrl" aria-label="Default select example" required multiple="multiple" data-placeholder="Select Case Type.">
                                                            <!--<option value="" title="Select">Select Case Type</option>-->
                                                            <?php
                                                            if (!empty($sc_case_type)) {
                                                                foreach ($sc_case_type as $dataRes) {
                                                                    //$sel = ($new_case_details[0]['sc_case_type_id'] == (string) $dataRes->casecode ) ? "selected=selected" : ''; echo $sel;
                                                                    ?>
                                                                    <option  value="<?php echo_data(trim($dataRes->casecode)); ?>"><?php echo_data($dataRes->casename); ?> </option>;
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="row mb-3 w-100 align-items-center">
                                                    <div class="col-12"><label class="col-form-label" for="from_date">From Date</label></div>
                                                    <div class="col-12 pe-0">
                                                        <input type="text" name="from_date" id="from_date" class="form-control cus-form-ctrl">
                                                        <span id="error_from_date"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="row mb-3 w-100 align-items-center">
                                                    <div class="col-12"><label class="col-form-label" for="to_date">To Date</label></div>
                                                    <div class="col-12 pe-0">
                                                        <input type="text" name="to_date" id="to_date" class="form-control cus-form-ctrl">
                                                        <span id="error_to_date"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="save-btns" style="text-align: center;">
                                        <button type="button" class="quick-btn Download"> Download</button>
                                        <!-- <div id="loader_div" class="loader_div" style="display: none;"></div> -->
                                    </div>
                                <?php echo form_close(); ?>
								<div id="result" class="text-center mt-3"></div>
                            </div>
                        </div>
                        {{-- Main End --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script> 
<script src="<?= base_url() . 'assets' ?>/js/select2.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/bootstrap-multiselect.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/jquery.validate.js"></script>

<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<script>
    $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
    $(document).ready(function(){
        $('#from_date').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            maxDate: new Date
            //defaultDate: '-6d'
        });
        $('#to_date').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            maxDate: new Date
        });
        var d = new Date();
        //d.setDate(d.getDate()-6);
        d.setDate(d.getDate());
        d= d.toLocaleString();
        if(d){
            d= d.split(',')[0];
            $("#from_date").val(d);
        }
        var newdate = new Date();
        newdate.setDate(newdate.getDate());
        newdate = newdate.toLocaleString();
        if(newdate){
            newdate = newdate.split(',')[0];
            $("#to_date").val(newdate);
        }
        $(document).on('click','.Download',function(){
            var validationError = true;
            var selected=[];
            var sc_case_type = $("#sc_case_type").val();
            selected=sc_case_type;
            //alert("selected="+selected.length);
            if (selected.length==0){
                alert("Please select case type.");
                $("#sc_case_type").focus();
                validationError = false;
                return false;
            }
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            //alert('fromDate='+from_date+'fromDate='+to_date);
            var date1 = new Date(from_date.split('/')[0], from_date.split('/')[1] - 1, from_date.split('/')[2]);
            var date2 = new Date(to_date.split('/')[0], to_date.split('/')[1] - 1, to_date.split('/')[2]);
            if (date1 > date2) {
                // $('#result_load').hide();
                alert("To Date must be greater than From date");
                $("#to_date").focus();
                validationError = false;
                return false;
            } else{
                if(from_date.length == 0){
                    alert("Please select from date.");
                    $("#from_date").focus();
                    validationError = false;
                    return false;
                }
                else  if(to_date.length == 0){
                    alert("Please select to date.");
                    $("#to_date").focus();
                    validationError = false;
                    return false;
                }
            }
            if(validationError){
                //alert("Ready to ZIP Archive created");//return false;
                // $('.Download').hide();
                $('.loader_div').show();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url('adminReport/Search/get_list_doc_fromDate_toDate'); ?>",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, from_date: from_date,to_date:to_date,sc_case_type:sc_case_type},
                    beforeSend: function() {
                        // $("#loader_div").html("<table widht='100%' align='center'><tr><td><img src='<?php // echo base_url('assets/physical_hearing/load.gif');?>'></td></tr></table>");
                        $('.loader_div').show();
                    },
                    success: function (data) {
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                        // alert(data);
                        var resArr = data.split('@@@');
                        $('.msg').show();
                        $("#result").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                        $('.loader_div').hide();
                        $(".Download").prop("enabled", true);						
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function (e) {
                        $(".Download").prop("disabled", false);
                    }
                });
            }
        });
    });
</script>