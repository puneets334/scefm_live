<?php
if(isset(session()->get('login')['impersonator_user']) && isset(session()->get('login')['impersonator_user']->is_active) && !empty(session()->get('login')['impersonator_user']) && session()->get('login')['impersonator_user']->is_active == 1){
    $extends = 'layout.advocateApp';
}else{
    $extends = 'layout.ecopyApp';
} ?>
@extends($extends)
@section('content')
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="dash-card dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class=" dashboard-bradcrumb">
                                <div class="left-dash-breadcrumb">
                                    <div class="page-title">
                                        <h5><i class="fa fa-file"></i> Copy Status</h5>
                                    </div>
                                    <div class="form-response" id="msg" role="alert" data-auto-dismiss="5000"></div>
                                </div>
                                <div class="ryt-dash-breadcrumb">
                                    <div class="btns-sec">
                                        <!-- <a href="javascript:void(0)" class="quick-btn pull-right mb-2" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                        <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 ">
                            <div class="crnt-page-head">
                                <div class="current-pg-title">
                                    <h6>Case Search </h6>
                                </div>
                                <div class="current-pg-actions"> </div>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="dash-card dashboard-section">
                    <div class="row">
                        <div class="panel panel-default">
                            <?php
                            $attribute = array('class' => 'form-horizontal', 'id' => 'search_case_details', 'name' => 'search_case_details', 'autocomplete' => 'off', 'novalidate' => 'novalidate');
                            echo form_open('#', $attribute);
                            ?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="title-sec">
                                <h5 class="unerline-title">Search By</h5>
                            </div>
                            <div style="text-align: left;">
                                <?php
                                if (!empty(getSessionData('MSG'))) {
                                    echo getSessionData('MSG');
                                }
                                if (!empty(getSessionData('msg'))) {
                                    echo getSessionData('msg');
                                }
                                ?>
                                <br>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label class="radio-inline input-lg"><input type="radio" checked name="search_filing_type" id="radio_ano" value="ANO"> Application No. &nbsp;</label><label class="radio-inline input-lg"><b>&nbsp;</b> </label>
                                    <label class="radio-inline input-lg"><input type="radio" id="radio_crn" name="search_filing_type" value="CRN">CRN</label>
                                </div>
                            </div>
                            <br>
                            <div id="search_application_no">
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                                        <div class="row mb-3">
                                            <!-- <div class="row w-100 align-items-center"> -->
                                                <div class="col-12">
                                                    <label for="inputPassword6" class="col-form-label">Type *</label>
                                                </div>
                                                <div class="col-12 pe-0">
                                                    <select id="application_type" name="application_type" class="form-select cus-form-ctrl"
                                                    aria-labelledby="application_type_addon">
                                                        <option value="-1">Select</option>
                                                        <?php
                                                        foreach ($category as $rows) {
                                                            ?>
                                                            <option value="<?php echo $rows['id'] ?>"><?php echo $rows['code']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            <!-- </div> -->
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                                        <div class="row mb-3">
                                            <!-- <div class="row w-100 align-items-center"> -->
                                                <div class="col-12">
                                                    <label for="inputPassword6" class="col-form-label">Application No</label>
                                                </div>
                                                <div class="col-12 pe-0">
                                                    <input class="form-control cus-form-ctrl" id="application_no" name="application_no" maxlength="10" placeholder="Application No." onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Diary No" type="text" required>
                                                </div>
                                            <!-- </div> -->
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                                        <div class="row mb-3">
                                            <!-- <div class="row w-100 align-items-center"> -->
                                                <div class="col-12">
                                                    <label for="inputPassword6" class="col-form-label"> Application Year</label>
                                                </div>
                                                <div class="col-12 pe-0">
                                                    <select class="form-select cus-form-ctrl" aria-label="Default select example" id="application_year" name="application_year" style="width: 100%" required>
                                                        <?php
                                                        $end_year = 48;
                                                        for ($i = 0; $i <= $end_year; $i++) {
                                                            $year = (int) date('Y') - $i;
                                                            $sel = $year == ((int) date('Y')) ? 'selected=selected' : '';
                                                            echo '<option ' . $sel . ' value=' . $year . '>' . $year . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            <!-- </div> -->
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3 col-sm-3 col-xs-12"> -->
                                        <div class="save-form-details">
                                            <div class="save-btns">
                                                <button type="button" class="quick-btn gray-btn" id="sub" value="SEARCH">Search</button>
                                            </div>
                                        </div>
                                    <!-- </div> -->
                                </div>
                            </div>
                            <div id="search_crn">
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="row">
                                            <div class="row mb-3 w-100 align-items-center">
                                                <div class="col-12 ">
                                                    <label for="inputPassword6" class="col-form-label">CRN</label>
                                                </div>
                                                <div class="col-12 pe-0">
                                                    <input class="form-control cus-form-ctrl" id="crn" name="crn" maxlength="15" placeholder="CRN" placeholder="Diary No" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3 col-sm-3 col-xs-12"> -->
                                        <div class="save-form-details">
                                            <div class="save-btns">
                                                <button type="button" id="sub" class="quick-btn gray-btn">Search</button>
                                            </div>
                                        </div>
                                    <!-- </div> -->
                                </div>
                            </div>
                            <?php echo form_close(); ?>

                            <br>
                            <div id="result"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
<script type="text/javascript">

        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        $(document).ready(function(){
            $("#search_crn").hide();
        });
$(document).on('click', '#radio_crn', function () {
    $("#search_crn").show();
    $("#search_application_no").hide();
    $('#result').html('');
});
$(document).on('click', '#radio_ano', function () {
    $("#search_application_no").show();
    $("#search_crn").hide();
    $('#result').html('');
});


$(document).on('click','#sub',function(){

        var application_type= $("#application_type").val();
        var application_no= $("#application_no").val();
        var application_year= $("#application_year").val();
        var crn = $("#crn").val();
        var flag = '';
        var regNum = new RegExp('^[0-9]+$');

        if($("#radio_ano").is(':checked')){
            flag = 'ano';
            if(!regNum.test(application_type)){
                alert("Please Select Type");
                $("#application_type").focus();
                return false;
            }
            if(!regNum.test(application_no)){
                alert("Please Fill Application No. in Numeric");
                $("#application_no").focus();
                return false;
            }
            if(!regNum.test(application_year)){
                alert("Please Fill Application Year in Numeric");
                $("#application_year").focus();
                return false;
            }
            if(application_no == 0){
                alert("Application No. Can't be Zero");
                $("#case_no").focus();
                return false;
            }
            if(application_year == 0){
                alert("Application Year Can't be Zero");
                $("#case_yr").focus();
                return false;
            }
        }
         else{
           flag = 'crn';
           if(crn.length !=15){
               alert("Please enter CRN");
               $('#crn').focus();
               return false;
           }
        }
        
        $.ajax({
            url:'<?php echo base_url("online_copying/get_copy_search"); ?>',
            cache: false,
            async: true,
            beforeSend: function () {
                $('#result').html('<table widht="100%" align="center"><tr><td>Loading...</td></tr></table>');
            },
            data: {flag:flag, crn:crn, application_type:application_type,application_no:application_no,application_year:application_year},
            type: 'GET',
            success: function(data, status) {
                $('#result').html(data);
               // $('#user_input_captcha').val("");
                //reload_captcha();
                //load_re_captcha();
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
      });


        function onloadCallback() {
            var textarea = document.getElementById("g-recaptcha-response-100000");
            textarea.setAttribute("aria-hidden", "true");
            textarea.setAttribute("aria-label", "do not use");
            textarea.setAttribute("aria-readonly", "true");
        }

        //XXXXXXXXX TRACK MODAL FUNCTION START XXXXXXXX

        function mytrack_record(){
            //alert("Rounak Mishra");
            var modal = document.getElementById("myModal");
            var btn = document.getElementById("myBtn");
            var span = document.getElementsByClassName("close")[0];
            modal.style.display = "block";
            span.onclick = function() {

                modal.style.display = "none";
            };
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

        }//End of function mytrack_record..

        //XXXXXXXXXX TRACK MODAL FUNCTION END  XXXXXXX



        </script>
        @endpush