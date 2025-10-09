<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<?php $crnt_dt = date("d-m-Y"); ?>
@extends('layout.advocateApp')
@section('content')
    <style>
        .table_heading {
            font-size: large;
            font-weight: bold;
            text-align: center;
            display: block;
            width: 100%;
            word-wrap: break-word;
            font-weight: bold;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="dash-card">
                                <div class="title-sec">
                                    <h5 class="unerline-title"> Physical Hearing Reports </h5>
                                    <a href="<?php echo base_url('physical_hearing/reports'); ?>" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                </div>
                                <div class="row g-3 align-items-center" id="show_result">
                                    <div class="col-auto">
                                        <label for="tdate" class="col-form-label">Listing Date : </label>
                                    </div>
                                    <div class="col-auto" style="width: 25%;">
                                        <input type="text" class="form-control cus-form-ctrl datepick" id="listing_dt" placeholder="DD-MM-YYYY" name="listing_dt" maxlength="10" value="<?= $crnt_dt ; ?>">
                                    </div>
                                    <div class="col-auto">
                                        <span id="passwordHelpInline" class="form-text">
                                        <button class="btn btn-primary" onclick="Get_result_function()">Get Data</button>
                                        </span>
                                    </div>
                                    <section class="col-sm-12">
                                        <div id="divConsentEntries" style="display: block;"></div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<script>
    // $(function () {
    //     $('.datepick').datepicker({
    //         format: 'dd-mm-yyyy',
    //         todayHighlight: true,
    //         autoclose:true
    //     });
    // });
    $(document).ready(function() {
        var today = new Date();
        var startYear = 1984;
        var startDate = new Date(startYear, 1, 1);
        $('#listing_dt').datepicker({
            format: "dd-mm-yyyy",
            showOtherMonths: true,
            selectOtherMonths: true,
            changeMonth: true,
            changeYear: true,
            // endDate: today,
            autoclose: true
        });
    });
    // function Get_result_function(){
        
    //     var date_chk=document.getElementById("listing_dt").value;
    //     var CSRF_TOKEN = 'CSRF_TOKEN';
    //     var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    //     $.ajax({
    //         type: 'POST',
    //         url: "<?=base_url('Advocate_listing/advocate_rpt_srch')?>",
    //         beforeSend: function (xhr) {
    //             $("#divConsentEntries").html('');
    //         },
    //         data:{CSRF_TOKEN: CSRF_TOKEN_VALUE , srch_date_data: date_chk },
    //     }).beforeSend(function(){
    //         showLoader();
    //     })
    //     .done(function (resultData) {
    //         $("#divConsentEntries").html(resultData);
    //         $("#printButton").show();
    //         $('#loader-wrapper').hide();
    //     })
    //     .fail(function () {
    //         alert("ERROR, Please Contact Server Room");
    //         $('#loader-wrapper').hide();
    //         $("#divConsentEntries").html();
    //     });
    //     $('#loader-wrapper').hide();
    // }

    function Get_result_function() {
        var date_chk = document.getElementById("listing_dt").value;
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        
        $.ajax({
            type: 'POST',
            url: "<?=base_url('Advocate_listing/advocate_rpt_srch')?>",
            data: { CSRF_TOKEN: CSRF_TOKEN_VALUE, srch_date_data: date_chk },
            beforeSend: function(xhr) {
                showLoader();
                $("#divConsentEntries").html('');
            },
            success: function(resultData) {
                $('#loader-wrapper').hide();
                $("#divConsentEntries").html(resultData);
                $("#printButton").show();
            },
            error: function() {
                alert("ERROR, Please Contact Server Room");
                $('#loader-wrapper').hide();
                $("#divConsentEntries").html('');
            }
        });
    }

    function PrintDiv() {
        var divContents = document.getElementById("divConsentEntries").innerHTML;
        var printWindow = window.open('', '', 'height=1000,width=1200');
        printWindow.document.write('<html><body >');
        printWindow.document.write(divContents);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    } 
    function showLoader() {
        $('#loader-wrapper').show();
        setTimeout(function() {
            $('#loader-wrapper').hide();
        }, 5000); // Hides the loader after 3 seconds
    }
</script>
@endpush