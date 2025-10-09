@extends('layout.advocateApp')
@section('content')
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet"> 
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>  
<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>assets/js/popper.min.js"></script>
<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/font-awesome.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/animate.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/material.css" rel="stylesheet" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/style.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?= base_url() . 'assets/newAdmin/' ?>css/jquery.dataTables.min.css">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/fullcalendar.css" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery-ui.css">
<link href="<?= base_url() . 'assets' ?>/css/select2.min.css" rel="stylesheet">
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
                                <h5 class="unerline-title"> Cause List </h5>
                                <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                            </div>
                            <div class="table-sec">
                                <div class="table-responsive">
                                    <table class="table table-striped custom-table first-th-left dt-responsive nowrap" id="datatable-responsive">
                                        <thead>
                                            <tr>
                                                <th>SNo.</th>
                                                <th>Listed On</th>
                                                <th>Court No.</th>
                                                <th>Item No.</th>
                                                <th>Case No.</th>
                                                <th>Cause Title</th>
                                                <th>For Appearance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // $sno = 1;
                                            foreach($list as $key => $advocate) {
                                                if($advocate['pno'] == 2) {
                                                    $pet_name = $advocate['pet_name']." AND ANR.";
                                                } elseif($advocate['pno'] > 2) {
                                                    $pet_name = $advocate['pet_name']." AND ORS.";
                                                } else{
                                                    $pet_name = $advocate['pet_name'];
                                                }
                                                if($advocate['rno'] == 2) {
                                                    $res_name = $advocate['res_name']." AND ANR.";
                                                } elseif($advocate['rno'] > 2) {
                                                    $res_name = $advocate['res_name']." AND ORS.";
                                                } else{
                                                    $res_name = $advocate['res_name'];
                                                }
                                                ?>
                                                <tr>
                                                    <td data-key="SNo."><?php  echo $key+1; ?></td>
                                                    <td data-key="Listed On">{{ date('d-m-Y', strtotime($advocate['next_dt'])) }}</td>
                                                    <td data-key="Court No.">{{ $advocate['courtno'] }}</td>
                                                    <td data-key="Item No.">{{ $advocate['brd_slno'] }}</td>
                                                    <td data-key="Case No.">{{ $advocate['reg_no_display'] ? $advocate['diary_no']:'' }}</td>
                                                    <td data-key="Cause Title"> {{ $pet_name }}<br> Vs.<br> {{ $res_name }}</td>
                                                    <td data-key="For Appearance">
                                                        <?php if($advocate['next_dt'] == CURRENT_DATE && date('H:i:s') > APPEARANCE_ALLOW_TIME) { ?>
                                                            <span data-courtno="{{$advocate['courtno']}}" data-toggle="modal" data-target="#modal-lg" class="badge badge-danger time_out_msg" style="padding:10px;">{{ MSG_TIME_OUT }}</span>
                                                        <?php } else { ?>
                                                            <button type="button" name="btn_click" class="btn_click quick-btn" data-bs-toggle="modal" data-bs-target="#exampleModal" data-diary_no="{{$advocate['diary_no']}}" data-next_dt="{{$advocate['next_dt']}}" data-appearing_for="{{$advocate['pet_res']}}" data-pet_name="{{$advocate['pet_name']}}" data-res_name="{{$advocate['res_name']}}" data-courtno="{{$advocate['courtno']}}" data-brd_slno="{{$advocate['brd_slno']}}" data-reg_no_display="{{$advocate['reg_no_display']}}">Click</button>
                                                        <?php } ?>
                                                    </td> 
                                                </tr>
                                            <?php } ?>
                                        </tbody>                                   
                                    </table>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content myModal_content"></div>
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
@push('script')
<script src="<?=base_url();?>assets/js/sweetalert2@11.js"></script>
<link rel="stylesheet" href="<?=base_url();?>assets/css/sweetalert2.min.css"> 
<script>
    // var token = $("meta[name='csrf-token']").attr("content");
    // $(function() {
    //     $('.table-sortable tbody').sortable({
    //         handle: 'span'
    //     });
    // });
    // var Toast = Swal.mixin({
    //     toast: true,
    //     position: 'top-end',
    //     showConfirmButton: false,
    //     timer: 3000
    // });
    function printErrorMsg (msg) {
        // $(".print-error-msg").find("ul").html('');
        // $(".print-error-msg").css('display','block');
        $.each( msg, function( key, value ) {
            Swal.fire({
                icon: 'error',
                title: value
            })
            return false;
            // $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
        });
    }
    $(document).on("click", ".btn_click", function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $("#modal-lg").modal('show');
        $(".myModal_content").html("");
        var diary_no = $(this).data('diary_no');
        var next_dt = $(this).data('next_dt');
        var appearing_for = $(this).data('appearing_for');
        var pet_name = $(this).data('pet_name');
        var res_name = $(this).data('res_name');
        var brd_slno = $(this).data('brd_slno');
        var courtno = $(this).data('courtno');
        var reg_no_display = $(this).data('reg_no_display');
        // $("#modal-lg").modal({backdrop: true});
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('advocate/modal_appearance'); ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,diary_no: diary_no,next_dt: next_dt,appearing_for: appearing_for,pet_name: pet_name,res_name: res_name,brd_slno: brd_slno, courtno: courtno, reg_no_display:reg_no_display},
            success: function (data) {
                // alert("Respose "+data);
                $(".myModal_content").html(data);
            }
        });
    });
    /* Save Add Advocate Functionality */
    $(document).on("click", ".btn_save", function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $(this).attr('disabled', true);
        $(".load_process").html('<i class="m-1 fas fa-1x fa-sync-alt fa-spin"></i>');
        var diary_no = $(this).data('diary_no');
        var next_dt = $(this).data('next_dt');
        var appearing_for = $(this).data('appearing_for');
        var brd_slno = $(this).data('brd_slno');
        var courtno = $(this).data('courtno');
        var advocate_type = $("#advocate_type").val();
        var advocate_title = $("#advocate_title").val();
        var advocate_name = $("#advocate_name").val();    
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('advocate/modal_appearance_save'); ?>",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                advocate_type: advocate_type,
                advocate_title: advocate_title,
                advocate_name: advocate_name,
                diary_no: diary_no,
                next_dt: next_dt,
                appearing_for: appearing_for,
                brd_slno: brd_slno,
                courtno: courtno
            },
            cache: false,
            dataType: "json",
            success: function (data) {
                // alert("Data is:  " + data);
                $('.btn_save').attr('disabled', false);
                $(".load_process").html('');
                if (data.status == 'timeout') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Time Out'
                    });
                    setTimeout(function() {
                        window.location.href = "/";
                    }, 2000);
                } else if (data.status == 'success') {
                    $("#advocate_type").prop('selectedIndex', 0);
                    $("#advocate_title").prop('selectedIndex', 0);
                    $("#advocate_name").val("");
                    $("#advocate_title").attr('disabled', false);
                    $("#advocate_name").prop("readonly", false);
                    // alert("Record Added Successfully.");
                    Swal.fire({
                        icon: 'success',
                        title: 'Record Added Successfully.'
                    });
                    // $('.table_added_advocates tr:last').after('<tr>'+
                    $('.sortable').append('<tr>'+
                        '<td><span class="drag_to_sort fas fa-arrows-alt"></span>'+
                        '<input type="hidden" name="sortable_id[]" value="'+data.data.id+'" />'+
                        '</td>'+
                        '<td>'+data.data.advocate_title+' '+data.data.advocate_name+', '+data.data.advocate_type+'</td>' +
                        '<td class="text-right py-0 align-middle">' +
                        '<span class="badge badge-light">'+data.data.entry_time+'</span>' +
                        '<div class="btn-group btn-group-sm advocate_remove_'+data.data.id+'">' +
                        '<a href="#" data-id="'+data.data.id+'" data-is_active="1" class="btn btn-danger advocate_remove" title="Remove"><i class="fas fa-trash"></i></a>' +
                        '</div>' +
                        '</td>' +
                        '</tr>'
                    );
                    // $(".table-sortable" ).load();
                } else{
                    printErrorMsg(data.data);
                }
            }
        });
    });
    /* Remove Advocate  */
    $(document).on("click", ".advocate_remove", function () {
        var id = $(this).data('id');
        var next_dt = $(this).data('next_dt');
        var is_active = $(this).data('is_active');
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        // alert(next_dt);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('advocate/remove_advocate'); ?>",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                next_dt:next_dt, 
                id: id, 
                is_active:is_active
            },
            cache: false,
            // dataType: "json",
            success: function (data) {
                if (data.status == 'timeout') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Time Out'
                    })
                    setTimeout(function () {
                        window.location.href = "/welcome";
                    }, 2000);
                } else if(data.status == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: data.msg
                    })
                    console.log(data.fas);
                    // $('.advocate_remove_'+data.id).html('<a href="#" data-next_dt="'+data.next_dt+'" data-id="'+data.id+'" data-is_active="'+data.is_active+'" class="btn '+data.btn_color+' advocate_remove" title="Click to Restore"><i class="fas '+data.fas+'"></i></a>');
                    $('.advocate_remove_'+data.id).closest("tr").remove();
                } else{
                    Swal.fire({
                        icon: 'error',
                        title: 'No Changes.'
                    })
                }
            }
        });
    });
    /* Apearance Final Submit  */
    $(document).on("click", ".final-submit", function () {
        var array_id = $('input[name="sortable_id[]"]').serialize();
        var diary_no = $(this).data('diary_no');
        var next_dt = $(this).data('next_dt');
        var appearing_for = $(this).data('appearing_for');
        var brd_slno = $(this).data('brd_slno');
        var courtno = $(this).data('courtno');
        var case_no = $(this).data('case_no');
        var cause_title = $(this).data('cause_title');
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        if ($("#certify_check_box").prop('checked')==false) {
            Swal.fire({
                icon: 'warning',
                title: 'Check Box Required',
                text: 'I certify check box must be checked',
                confirmButtonText: 'OK'
            });
            return false;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('advocate/confirm_final_submit'); ?>",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                case_no:case_no,
                cause_title:cause_title,
                diary_no:diary_no,
                next_dt:next_dt,
                appearing_for: appearing_for,
                brd_slno: brd_slno,
                courtno: courtno,
                array_id: array_id
            },
            cache: false,
            dataType: "json",
            success: function (data) {
                if(data.status == 'timeout') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Time Out'
                    })       
                    setTimeout(function(){window.location.href = "/welcome";}, 2000);
                } else if(data.status == 'success') {
                    // alert("Submitted Successfully");
                    Swal.fire({
                        icon: 'success',
                        title: 'Submitted Successfully.'
                    })
                    // $("#modal-lg .close").click();
                    $(".myModal_content").html("");
                    display_appearance_slip(data.case_no,data.cause_title,data.diary_no,data.next_dt,data.appearing_for,data.brd_slno,data.courtno);
                } else if(data.status == 'checkbox') {
                    console.log(data.data);
                    printErrorMsg(data.data);
                } else{
                    Swal.fire({
                        icon: 'error',
                        title: 'No Changes.'
                    })
                }
            }
        });
    });
    function display_appearance_slip(case_no,cause_title,diary_no,next_dt,appearing_for,brd_slno,courtno) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('advocate/display_appearance_slip'); ?>",
            data: {case_no:case_no, cause_title:cause_title, diary_no:diary_no,next_dt:next_dt, appearing_for: appearing_for,brd_slno: brd_slno, courtno: courtno},
            cache: false,
            // dataType: "text",
            success: function (data) {
                $(".myModal_content").html(data);
            }
        });
    }
    $(document).on("click", ".add_from_case_advocate_master_list", function () {
        var diary_no = $(this).data('diary_no');
        var next_dt = $(this).data('next_dt');
        var appearing_for = $(this).data('appearing_for');
        var brd_slno = $(this).data('brd_slno');
        var courtno = $(this).data('courtno');
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $(".display_master_list").toggle(800);
        // $(".myModal_content2").html("");
        // $("#modal-adv").modal({backdrop: true});
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('advocate/add_from_case_advocate_master_list'); ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, appearing_for: appearing_for,brd_slno: brd_slno, courtno: courtno,diary_no: diary_no,next_dt: next_dt},
            cache: false,
            // dataType: "json",
            success: function (data) {
                $(".display_master_list").html(data);
            }
        });
    });
    function chkall1(e){
        var elm=e.name;
        if(document.getElementById(elm).checked) {
            $('input[type=checkbox]').each(function () {
                if($(this).attr("name")=="chk_master_list_id")
                    this.checked=true;
            });
        } else{
            $('input[type=checkbox]').each(function () {
                if($(this).attr("name")=="chk_master_list_id")
                    this.checked=false;
            });
        }
    }
    $(document).on("click", ".master_list_submit", function () {
        var diary_no = $(this).data('diary_no');
        var next_dt = $(this).data('next_dt');
        var appearing_for = $(this).data('appearing_for');
        var brd_slno = $(this).data('brd_slno');
        var courtno = $(this).data('courtno');
        var array = [];
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $("input:checkbox[name=chk_master_list_id]:checked").each(function() {
            array.push($(this).val());
        });
        if (array.length === 0) {
            // Display an alert if no checkboxes are checked
            // alert("Please check at least one checkbox.");
            Swal.fire({
            icon: 'warning',
            title: 'Check Box Required',
            text: 'I certify check box must be checked',
            confirmButtonText: 'OK'
            });
            return false;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('advocate/master_list_submit'); ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, appearing_for: appearing_for,brd_slno: brd_slno, courtno: courtno, diary_no: diary_no,next_dt: next_dt, array: array },
            cache: false,
            dataType: "json",
            success: function (data) {
                if (data == 'timeout') {
                    swal.fire({
                        icon: 'error',
                        title: 'Time Out'
                    })
                    setTimeout(function () {
                        window.location.href = "/";
                    }, 2000);
                } else{
                    swal.fire({
                        icon: 'success',
                        title: 'Success'
                    })
                    $.each(data, function (i, item) {
                        $('.table_added_advocates tr:last').after('<tr>' +
                            '<td><span class="drag_to_sort fas fa-arrows-alt"></span>' +
                            '<input type="hidden" name="sortable_id[]" value="' + data[i].id + '" />' +
                            '</td>' +
                            '<td>' + data[i].advocate_title + ' ' + data[i].advocate_name + ', ' + data[i].advocate_type + '</td>' +
                            '<td class="text-right py-0 align-middle">' +
                            '<span class="badge badge-light">' + data[i].entry_time + '</span>' +
                            '<div class="btn-group btn-group-sm advocate_remove_' + data[i].id + '">' +
                            '<a href="#" data-next_dt="' + data[i].next_dt + '" data-id="' + data[i].id + '" data-is_active="1" class="btn btn-danger advocate_remove" title="Remove"><i class="fas fa-trash"></i></a>' +
                            '</div>' +
                            '</td>' +
                            '</tr>'
                        );
                    });
                    $(".display_master_list").toggle(800);
                }
            }
        });
    });
    $(document).on("click", ".time_out_msg", function () {
        $(".myModal_content").html("");
        $("#modal-lg").modal({backdrop: true});
        var courtno = $(this).data('courtno');
        var html = '';
        html += '<div class="modal-header">'+
            '<h4 class="modal-title">Appearance Slip - Time Out</h4>'+
            '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
            '<span>&times;</span>'+
            '</button>'+
            '</div>'+
            '<div class="modal-body">Please contact court master of court room no. '+courtno+'.</div>' +
            '<div class="modal-footer justify-content-between " >'+
            '<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>'+
            '</div>';
        $(".myModal_content").html(html);
    });
</script>
@endpush