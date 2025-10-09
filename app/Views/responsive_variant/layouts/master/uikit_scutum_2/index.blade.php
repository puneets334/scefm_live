@extends('layout.advocateApp')
@section('title', !empty(getSessionData('efiling_details')['stage_id']) ? ((getSessionData('efiling_details')['stage_id'] == 10 or
    getSessionData('efiling_details')['stage_id'] == 11) ? 'Refile case' : 'Register case') : '')
@section('heading', !empty(getSessionData('efiling_details')['stage_id']) ? ((getSessionData('efiling_details')['stage_id'] == 10 or
    getSessionData('efiling_details')['stage_id'] == 11) ? 'Refiling' : 'Register a case') : '')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-section dashboard-tiles-area"></div>
            <div class="dashboard-section">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="dash-card ml-2">
                            {{-- Page Title Start --}}
                            <div class="title-sec">
                                <h5 class="unerline-title">File An IA </h5>
                            </div>
                            {{-- Page Title End --}}
                            <div class="current-pg-title">
                                <h6>Case Search </h6>
                            </div>

                            {{-- Main Start --}}
                            <div class="row" style="margin:10px;">
                                <?php
                                    $attribute = array('class' => 'form-horizontal', 'id' => 'search_case_details', 'name' => 'search_case_details', 'autocomplete' => 'off', 'novalidate' => 'novalidate');
                                    echo form_open(base_url('case/refile_old_efiling_cases/crud/#'), $attribute);                                                       
                                ?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <!-- <?php echo base_url('case/search/search_case_details'); ?> -->
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                        <div class="radio-btns-inp mb-3">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input cus-form-check" type="radio" checked
                                                    name="search_filing_type" value="diary" id="search_filing_type"
                                                    checked>
                                                <label class="form-check-label" for="Registration No" style="width:125;"> Diary Number
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input cus-form-check" type="radio"
                                                    name="search_filing_type" id="search_filing_type" value="register">
                                                <label class="form-check-label" for="Diary Number" style="width:125;"> </span>Registration
                                                    No</label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div id="diarySec">
                                    <div class="row">

                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            <div class="row">
                                                <div class="row w-100 align-items-center">
                                                    <div class="col-5">
                                                        <label for="inputPassword6" class="col-form-label">Diary
                                                            No</label>
                                                    </div>
                                                    <div class="col-7 pe-0">
                                                        <input class="form-control cus-form-ctrl" id="diary_no"
                                                            name="diaryno" maxlength="10" placeholder="Diary No."
                                                            onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"
                                                            placeholder="Diary No" type="text" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="row">
                                                <div class="row w-100 align-items-center">
                                                    <div class="col-5">
                                                        <label for="inputPassword6" class="col-form-label"> Diary
                                                            Year</label>
                                                    </div>
                                                    <div class="col-7 pe-0">
                                                        <select class="form-select cus-form-ctrl"
                                                            aria-label="Default select example" id="diary_year"
                                                            name="diary_year" style="width: 100%" required>
                                                            <option value="">Select</option>
                                                            <?php
                                                                $end_year = 48;
                                                                for ($i = 0; $i <= $end_year; $i++) {
                                                                    $year = (int) date('Y') - $i;
                                                                    $sel = $year == ((int) date('Y')) ? 'selected=selected' : '';
                                                                    //   echo '<option ' . $sel . ' value=' . $year . '>' . $year . '</option>';
                                                                    echo '<option value=' . $year . '>' . $year . '</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            <div class="save-form-details">
                                                <div class="save-btns">
                                                    <button type="submit" class="quick-btn gray-btn" id="search_sc_case"
                                                        value="SEARCH">Search</button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div id="RegSec">

                                    <div class="row">
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="row">
                                                <div class="row w-100 align-items-center">
                                                    <div class="col-5">
                                                        <label for="inputPassword6" class="col-form-label">Case
                                                            Type</label>
                                                    </div>


                                                    <div class="col-7 pe-0">
                                                        <select class="form-select cus-form-ctrl" name="sc_case_type"
                                                            id="sc_case_type" aria-label="Default select example">
                                                            <option value="">Case Type</option>;
                                                            <?php
                                                            if(isset($sc_case_type)){
                                                                if (count($sc_case_type)) {
                                                                    foreach ($sc_case_type as $dataRes) {


                                                                        $sel = (isset($new_case_details[0]['sc_case_type_id']) && $new_case_details[0]['sc_case_type_id'] == (string) $dataRes->casecode) ? "selected=selected" : '';
                                                                        ?>
                                                                            <option data-case="{{$dataRes->casecode}}" <?php        echo $sel; ?>
                                                                                value="<?php  echo_data(url_encryption(trim($dataRes->casecode))); ?>">
                                                                                <?php echo_data($dataRes->casename); ?>
                                                                            </option>;
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                        ?>
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="row">
                                                <div class="row w-100 align-items-center">
                                                    <div class="col-5">
                                                        <label for="inputPassword6" class="col-form-label">Case
                                                            No</label>
                                                    </div>
                                                    <div class="col-7 pe-0">
                                                        <input class="form-control cus-form-ctrl" id="diary_no"
                                                            name="case_number" maxlength="10" placeholder="Diary No."
                                                            onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"
                                                            placeholder="Diary No" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="row">
                                                <div class="row w-100 align-items-center">
                                                    <div class="col-5">
                                                        <label for="inputPassword6" class="col-form-label">Case
                                                            Year</label>
                                                    </div>
                                                    <div class="col-7 pe-0">
                                                        <select class="form-select cus-form-ctrl"
                                                            aria-label="Default select example" id="case_year"
                                                            name="case_year" style="width: 100%">
                                                            <?php
                                                            $end_year = 48;
                                                            for ($i = 0; $i <= $end_year; $i++) {
                                                                $year = (int) date('Y') - $i;
                                                                //   $sel = $year == ((int) date('Y')) ? 'selected=selected' : '';
                                                                // echo '<option ' . $sel . ' value=' . $year . '>' . $year . '</option>';

                                                                echo '<option  value=' . $year . '>' . $year . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="save-form-details">
                                                <div class="save-btns">
                                                    <button type="submit" class="quick-btn gray-btn">Search</button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                </form>
                            </div>
                            <div class="row">
                                <div id="show_search_result_diary"></div>
                            </div>
                            <div class="row">
                                <div id="show_search_result"></div>
                            </div>
                            {{-- Main End --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('script')
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>

<script src="<?= base_url() ?>assets/newAdmin/js/jquery.validate.min.js"></script>

<script>
    $(document).ready(function () {
        <?php $_SESSION['efiling_type'] = 'ia'; ?>
        var inputValue = $('input[type="radio"]:checked').val();
        if (inputValue == 'register') {
            $('#diaryno').val('');
            $('#diary_year').val('');

            $("#show_search_result_diary").css("display", "none");
            $("#show_search_result").css("display", "block");
            $("#diarySec").css("display", "none");
            $("#RegSec").css("display", "block");
        } else if (inputValue == 'diary') {

            $('#sc_case_type').val('');
            $('#case_number').val('');
            $('#case_year').val('');
            $("#show_search_result").css("display", "none");
            $("#show_search_result_diary").css("display", "block");
            $("#RegSec").css("display", "none");
            $("#diarySec").css("display", "block");
        }
        var targetBox = $("." + inputValue);
        $(".box").not(targetBox).hide();
        $(targetBox).show();


        $('input[type="radio"]').click(function () {
            var inputValue = $(this).attr("value");
            if (inputValue == 'register') {
                $('#diaryno').val('');
                $('#diary_year').val('');

                $("#show_search_result_diary").css("display", "none");
                $("#show_search_result").css("display", "block");
                $("#diarySec").css("display", "none");
                $("#RegSec").css("display", "block");
            } else if (inputValue == 'diary') {
                $('#sc_case_type').val('');
                $('#case_number').val('');
                $('#case_year').val('');
                $("#show_search_result").css("display", "none");
                $("#show_search_result_diary").css("display", "block");
                $("#RegSec").css("display", "none");
                $("#diarySec").css("display", "block");
            }
            var targetBox = $("." + inputValue);
            $(".box").not(targetBox).hide();
            $(targetBox).show();
        });
    });
</script>
<style>
    .box {
        display: none;
        background-color: none;
    }

    div.box {
        height: 60px;
        padding: 0;
        overflow: unset;
        border: #fff;
        background-color: #fff;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $('#search_case_details').on('submit', function () {

            // if ($('#search_case_details').valid()) {
            var diary_no = $(this).val();

            var form_data = $(this).serialize();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#show_search_result_diary').html('');
            $('#show_search_result').html('');
            $(".form-response").html('');
            $(".form-response").hide();
            $.ajax({
                type: "GET",
                url: "<?php echo base_url('case/search/search_case_details'); ?>",
                data: form_data,
                async: false,
                beforeSend: function () {
                    $('#search_sc_case').val('Please wait...');
                    $('#search_sc_case').prop('disabled', true);
                },
                success: function (data) {
                    $('#search_sc_case').val('SEARCH');
                    $('#search_sc_case').prop('disabled', false);
                    var resArr = data.split('@@@');
                    if (resArr[0] == 1) {
                        $('#show_search_result_diary').html(resArr[1]);
                    } else if (resArr[0] == 2) {
                        $('#show_search_result').html(resArr[1]);
                    } else if (resArr[0] == 3) {
                        $(".form-response").show();
                        $('#msg').show();
                        $(".form-response").html(
                            "<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " +
                            resArr[1] +
                            "  <span class='close' onclick=hideMessageDiv()>X</span></p>"
                        );
                    }
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
            return false;
            // } else {
            //     alert("Form is invalid!");
            //     // return false;
            // }
        });
    });
</script>