@extends('layout.app')
@section('content')
    <style>
        @media (max-width: 767px) {
            div#tableData .custom-table tr td:nth-child(3) {min-height: 55px;}
            div#tableData .custom-table tr td:nth-child(3) {
                min-height: 55px;
            }
        }
        @media (max-width: 767px){
            .custom-table td:nth-child(4) {
                min-height: 56px;

            }
        }
        @media (max-width: 767px){
            .custom-table td:nth-child(4) {
                min-height: 56px;
            }
        }
    </style>
    <div class="container-fluid">
        <div id="loader-wrapper" style="display: none;">
            <div id="loader"></div>
        </div>
        <div class="row" id="printData">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="dash-card">
                                <div class="title-sec">
                                    <h5 class="unerline-title">Select Dates </h5>
                                    <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-4 col-lg-3 col-xs-3">
                                        <label>From Date</label>
                                        <div class="form-group">
                                            <input autocomplete="off" type="text" name="from_date" id="from_date" class="form-control cus-form-ctrl">
                                            <span id="error_from_date"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-lg-3 col-xs-3">
                                        <label>To Date</label>
                                        <div class="form-group">
                                            <input autocomplete="off" type="text" name="to_date" id="to_date" class="form-control cus-form-ctrl">
                                            <span id="error_to_date"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-lg-3 col-xs-3">
                                        <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        <div class="form-group">
                                            <button type="button" name="getResult" id="getResult" class="btn quick-btn"> Get Reports</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div id="result"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="dashboard-section" >
                    <div class="row">
                        <div class="col-12">
                            <button class="pull-right quick-btn mb-2" style="display: none;" id="printButton" type="button" onclick="PrintDiv();">Print</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="dash-card" style="display: none;" id="tableData"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')    
    <script>
        $(document).ready(function() {
            $('#from_date').datepicker({
                changeMonth: true,
                changeYear: true,
                format: "dd/mm/yyyy",
                maxDate: new Date,
                defaultDate: '-6d'
            });
            $('#to_date').datepicker({
                changeMonth: true,
                changeYear: true,
                format: "dd/mm/yyyy",
                maxDate: new Date
            });
            var d = new Date();
            d.setDate(d.getDate() - 6);
            d = d.toLocaleString();
            if (d) {
                d = d.split(',')[0];
                $("#from_date").val(d);
            }
            var newdate = new Date();
            newdate.setDate(newdate.getDate());
            newdate = newdate.toLocaleString();
            if (newdate) {
                newdate = newdate.split(',')[0];
                $("#to_date").val(newdate);
            }
        });
        $(document).on('click', '#getResult', function() {
            $('#loader-wrapper').show(); 
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var validationError = true;
            if (fromDateObj == '') {
                alert("Please select from date.")
                $("#from_date").focus();
                validationError = false;
                return false;
            } else if (toDateObj == '') {
                alert("Please select to date.")
                $("#to_date").focus();
                validationError = false;
                return false;
            }
            var fromDateString = from_date.split('/').reverse().join('-');
            var toDateString = to_date.split('/').reverse().join('-');
            var fromDateObj = new Date(fromDateString);
            var toDateObj = new Date(toDateString);
            if (toDateObj <= fromDateObj) {
                alert('To Date Must be Greater Than From Date.');
                var validationError = false;
                return false;
            }
            if (validationError) {
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                var postData = {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    from_date: from_date,
                    to_date: to_date
                };
                $.ajax({
                    type: "POST",
                    data: JSON.stringify(postData),
                    url: "<?php echo base_url('adminReport/DefaultController/getReportData'); ?>",
                    dataType: 'json',
                    contentType: 'application/json',
                    cache: false,
                    async: false,
                    beforeSend: function() {
                        $('#loader-wrapper').show();
                    },
                    success: function(res) { 
                        $('#loader-wrapper').show();
                        if (typeof res == 'string') {
                            res = JSON.parse(res);
                        }
                        if (res.status == 'error') {
                            $("#tableData").show();
                            $("#" + res.id).focus();
                            $("#error_" + res.id).html('');
                            $("#error_" + res.id).html(res.msg);
                            $("#error_" + res.id).css({
                                "color": "red",
                                "margin": "10px 0px 0px 0px"
                            });
                            return false;
                        } else if (res.status == 'success') {
                            $("#tableData").show();
                            $("#loader_div").html('');
                            $('#getResult').html('Get Reports');
                            $("#" + res.id).html('');
                            $("#" + res.id).html(res.msg);
                            $("#" + res.id).css({
                                "color": "red",
                                "margin": "10px 0px 0px 0px"
                            });
                            $("#" + res.id).css({
                                "text-align": "center"
                            });
                            $("#tableData").html(res.table);
                            $("#printButton").show();
                            setTimeout(function() {
                                $('#loader-wrapper').hide(); 
                                $('#result').fadeOut('slow');
                            }, 1000);
                            return false;
                        }
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function() {
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });
            }
        });
        $(document).ready(function() {
            $('#datatable-responsive').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'pdf',
                    title: 'Admin Report List',
                    filename: 'Report_pdf_file_name'
                }, {
                    extend: 'excel',
                    title: 'Admin Report List',
                    filename: 'Report_excel_file_name'
                }, {
                    extend: 'csv',
                    title: 'Admin Report List',
                    filename: 'Report_csv_file_name'
                }, {
                    extend: 'print',
                    title: 'Admin Report List',
                    filename: 'Report_print_file_name'
                }]

            });
        });
        function PrintDiv() {
            var divContents = document.getElementById("tableData").innerHTML;
            var printWindow = window.open('', '', 'height=1000,width=1200');
            printWindow.document.write('<html><body >');
            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
@endpush