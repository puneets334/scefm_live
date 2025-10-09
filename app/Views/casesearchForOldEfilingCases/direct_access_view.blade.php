@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard-section dashboard-tiles-area"></div>
                <div class="dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="dash-card">
                                <div class="right_col " role="main">
                                    <div id="page-wrapper">
                                        <div class="panel-default">
                                            <div class="form-response" id="msg" role="alert" data-auto-dismiss="5000"></div>
                                            <h4 style="text-align: center;color: #31B0D5"> Case Search </h4>
                                            <div class="panel panel-default">
                                                <?php
                                                $attribute = array('class' => 'form-horizontal', 'id' => 'search_case_details', 'name' => 'search_case_details', 'autocomplete' => 'off');
                                                echo form_open('#', $attribute);
                                                ?>
                                                    <input type="hidden" id="search_filing_type" name="search_filing_type" value="diary">
                                                    <input type="hidden" id="diaryno" name="diaryno" value="123">
                                                    <input type="hidden" id="diary_year" name="diary_year" value="2019">
                                                    <input type="hidden" id="is_direct_access" name="is_direct_access" value=true>
                                                    <?php $_SESSION['efiling_type'] = 'ia'; ?>
                                                    <div class="form-group">
                                                        <div class="col-md-offset-5">
                                                            <input type="submit" class="btn btn-primary" id="search_sc_case" value="SEARCH">
                                                        </div>
                                                    </div>
                                                <?php echo form_close(); ?>
                                                <br>
                                                <div id="show_search_result_diary"></div>
                                                <div id="show_search_result"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script type="text/javascript">
    $(document).ready(function () {
        $('#search_case_details').on('submit', function () {
            if ($('#search_case_details').valid()) {
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('#show_search_result_diary').html('');
                $('#show_search_result').html('');
                $(".form-response").html('');
                $(".form-response").hide();
                $.ajax({
                    type: "POST",
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
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        }
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