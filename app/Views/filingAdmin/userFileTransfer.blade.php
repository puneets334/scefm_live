<?php declare(strict_types=1); ?>
@extends('layout.app')
@section('content')
    <style>
        .add-new-area {
            display: none !important;
        }
        @media (max-width: 767px){
            table#filetransfer td:nth-child(5) {min-height: 55px;}
            table#filetransfer td:nth-child(6) {min-height: 40px;}
            table#filetransfer td:nth-child(3) {min-height: 30px;}
        }
    </style>
    <div class="container-fluid">
        <div id="loader-wrapper" style="display: none;">
            <div id="loader"></div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard-section dashboard-tiles-area"></div>
                <div class="dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="dash-card">
                                {{-- Page Title Start --}}
                                <div class="title-sec">
                                    <h5 class="unerline-title">User File Transfer </h5>
                                    <!-- <a href="javascript:void(0)" class="quick-btn pull-right mb-3" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a>                                 -->
                                    <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                </div>
                                {{-- Page Title End --}}
                                {{-- Main Start --}}                               
                                <?php echo form_open(); ?>
                                    <div class="row">
                                        <input type="text" style="display: none;" name="CSRF_TOKEN" value="{{ csrf_hash() }}">
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="row mb-3 w-100 align-items-center">
                                                
                                                    <div class="col-12">
                                                        <label for="inputPassword6" class="col-form-label">Select User</label>
                                                    </div>
                                                    <div class="col-12 pe-0">
                                                        <select id="file_transfer" name="file_transfer"  class="form-select cus-form-ctrl"
                                                            aria-label="Default select example">
                                                            <option selected>Select</option>
                                                            @if(count($users) > 0)
                                                                @foreach($users as $k=>$v)
                                                                    <option value="{{isset($v->id)?$v->id:''}}">{{isset($v->first_name)?$v->first_name:''}}</option>
                                                                @endforeach
                                                            @else                                                    
                                                                <option value="0">File Not Allocated</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12" id="fileTransferUserDiv">
                                            <div class="row" >
                                                <div class="row mb-3 w-100 align-items-center">
                                                    <div class="col-12">
                                                        <label for="inputPassword6" class="col-form-label">File Transfer To
                                                            User</label>
                                                    </div>
                                                    <div class="col-12 pe-0">

                                                        <select id="file_transfer_to_user" name="file_transfer_to_user" class="form-select cus-form-ctrl" required>
                                                        </select>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12" id="buttonDiv">
                                            <button type="button"  class="quick-btn mrgT35" id="transferAll">File Transfer </button>
                                        </div>
                                    </div>
                                <?php echo form_close(); ?>
                                <div class="row mt-5" style="display: none;" id="fileTransferDiv">
                                    <div class="table-sec mob-view-selectall-tbl">
                                        <div class="mobile-view-selectall">
                                            <label for="selectAll">
                                                <span style="margin-left: 7px;" id="selectSpan">Select All</span>
                                                <input type="checkbox" name="selectAll" id="selectAll">
                                            </label>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped custom-table first-th-left" id="filetransfer">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Efiling No.</th>
                                                        <th>Cause Title</th>
                                                        <th>File Type</th>
                                                        <th>Diary No/Diary Year</th>
                                                        <th>
                                                            <label for="selectAll"><input type="checkbox" name="selectAll"
                                                                    id="selectAll">
                                                                <span style="margin-left: 7px;" id="selectSpan">
                                                                    Select All
                                                                </span>
                                                            </label>
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
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

<script>
    $(document).ready(function() {
       /*  $('#filetransfer').dataTable({
            "keys": false,
            "paging": false
        }); */
        $("#filetransfer").DataTable({
        "responsive": false,
        "lengthChange": false,
        "autoWidth": true,
        "bProcessing": true,
        "createdRow": function(row, data, dataIndex) {
            // Adding data-key attribute to each cell dynamically
            $(row).find('td').eq(0).attr('data-key', '#');
            $(row).find('td').eq(1).attr('data-key', 'Efiling No.');
            $(row).find('td').eq(2).attr('data-key', 'Cause Title');
            $(row).find('td').eq(3).attr('data-key', 'File Type');
            $(row).find('td').eq(4).attr('data-key', 'Diary No/Diary Year');
            // $(row).find('td').eq(5).attr('data-key', 'Select All');
            $(row).find('td').eq(5).attr('data-key', 'Select');
        }
    });
        $("#fileTransferUserDiv").hide();
        $("#buttonDiv").hide();
        $(document).on('change', '#file_transfer', function() {   
            var userId = $(this).val();
            if (userId == '') {
                alert("Please select user.");
                $("#file_transfer").focus();
                $("#file_transfer").css({
                    'border-color': 'red'
                });
                $("#filetransfer_wrapper").hide();
                $("#fileTransferDiv").hide();
                $("#fileTransferUserDiv").hide();
                $("#buttonDiv").hide();
                return false;
            } else {
                $('#loader-wrapper').show();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "POST",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        userId: userId
                    },
                    url: "<?php echo base_url('filingAdmin/DefaultController/getEmpCaseData'); ?>",
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(res) {
                        $('#loader-wrapper').hide();
                        if (res.caseData) {
                            $("#filetransfer_wrapper").show();
                            $("#fileTransferDiv").show();
                            $("#fileTransferUserDiv").show();
                            $("#buttonDiv").show();
                            var result = '';
                            if (res.caseData && typeof res.caseData == 'string') {
                                result = JSON.parse(res.caseData);
                            } else {
                                result = res.caseData;
                            }
                            $("#file_transfer_to_user").html('');
                            $("#file_transfer_to_user").html(res.trasferUser);
                            $("#filetransfer").DataTable().clear();
                            var length = Object.keys(result).length;
                            var ctn = 1;
                            for (var i = 0; i < length; i++) {
                                // cons    
                                var cause_title = (result[i].cause_title) ? result[i]
                                    .cause_title : '---';
                                var diarydetails = (result[i].diarydetails) ? result[i]
                                    .diarydetails : '---';
                                var efiling_no = (result[i].efiling_no) ? result[i]
                                    .efiling_no : '---';
                                var registration_id = (result[i].registration_id) ? result[
                                    i].registration_id : '';
                                var efiling_type = (result[i].efiling_type) ? result[i]
                                    .efiling_type.replace(/_/g, ' ').toUpperCase() : '---';
                                var action =
                                    '<input class="selectAllcheckBox" type="checkbox" data-registration_id="' +
                                    registration_id + '" data-efiling_no="' + efiling_no +
                                    '" id="filecheckbox_' + ctn +
                                    '" name="filecheckbox" />';
                                $('#filetransfer').dataTable().fnAddData([
                                    ctn,
                                    efiling_no,
                                    cause_title,
                                    efiling_type,
                                    diarydetails,
                                    action
                                ]);
                                ctn++;
                            }
                        } else {
                            alert(res.message);
                            $("#buttonDiv").hide();
                            $("#fileTransferDiv").hide();
                            $("#fileTransferUserDiv").hide();
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
        $(document).on('click', '#selectAll', function() {
            $('.selectAllcheckBox').each(function(index, obj) {
                if (this.checked === true) {
                    $(this).attr('checked', false);
                    $("#selectSpan").text("Select All");
                } else {
                    $(this).attr('checked', true);
                    $("#selectSpan").text("Deselect All");
                }
            });

        });

        $(document).on('click', '#transferAll', function() {
            var file_transfer = $("#file_transfer option:selected").val();
            var file_transfer_to_user = $("#file_transfer_to_user option:selected").val();
            var validatinFlag = true;
            var fileArr = [];
            if (file_transfer == '') {
                alert("Please select user.");
                $("#file_transfer").focus();
                $("#file_transfer").css({
                    'border-color': 'red'
                });
                validatinFlag = false;
                return false;
            } else if (file_transfer_to_user == '') {
                alert("Please select transfer user.");
                $("#file_transfer_to_user").focus();
                $("#file_transfer_to_user").css({
                    'border-color': 'red'
                });
                validatinFlag = false;
                return false;
            }
            $('.selectAllcheckBox').each(function(index, obj) {
                if (this.checked === true) {
                    fileArr.push($(this).attr('data-registration_id'));
                }
            });
            if ((validatinFlag) && (fileArr.length === 0)) {
                alert("Please select file.");
                $("#filecheckbox_1").focus();
                $("#filecheckbox_1").css({
                    'border-color': 'red'
                });
                return false;
            } else {
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                var typeUser = {};
                typeUser.file_transfer_from = file_transfer;
                typeUser.file_transfer_to_user = file_transfer_to_user;
                var postData = {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    userFile: fileArr,
                    typeUser: typeUser
                };
                $.ajax({
                    type: "POST",
                    data: JSON.stringify(postData),
                    url: "<?php echo base_url('filingAdmin/DefaultController/fileTransferToAnOtherUser'); ?>",
                    async: false,
                    cache: false,
                    dataType: 'json',
                    ContentType: 'application/json',
                    success: function(res) {
                        // return false;
                        if (res && typeof res == 'string') {
                            res = JSON.parse(res);
                        }
                        if (res.success = 'success') {
                            var message = '';
                            message += res.message;
                            var efiling_no = res.efiling_no;
                            if (res.failedTotal) {
                                message += ' and an unauthorized efiling No. ' +
                                    efiling_no + ' and total(s): ' + res.failedTotal;
                            }
                            alert(message);
                            window.location.reload();
                        } else if (res.success = 'error') {
                            alert(res.message);
                            window.location.reload();
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


    });
</script>
@endpush
