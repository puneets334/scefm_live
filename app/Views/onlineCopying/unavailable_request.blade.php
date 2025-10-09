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
                                        <h5><i class="fa fa-file"></i> Case Search</h5>
                                    </div>
                                    <div class="form-response" id="msg" role="alert" data-auto-dismiss="5000"></div>
                                </div>
                                <div class="ryt-dash-breadcrumb">
                                    <div class="btns-sec">
                                        <!-- <a href="javascript:void(0)" class="quick-btn gray-btn" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                        <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dash-card dashboard-section">
                    <div class="row">
                        <div class="panel panel-default">
                            <?php
                            $diary_no=$_SESSION['session_d_no'].$_SESSION['session_d_year'];
                            //VERIFICATION OF CASES VALIDATE, MAX 10 REQUEST per day ALLOWED
                            $stmt_check = eCopyingAvailableAllowedRequests($_SESSION["applicant_mobile"], $_SESSION["applicant_email"]);
                            if (count($stmt_check) > 0) {
                                $_SESSION['max_unavailable_copy_request_per_day'] = count($stmt_check);
                                if($_SESSION['max_unavailable_copy_request_per_day'] >=10){
                            ?>
                                <div class="alert alert-danger alert-dismissible">
                                    Max 10 unavailable document request reached per day. <a class='btn btn-danger' href='logout.php'>Exit</a>
                                </div>                  
                            <?php                
                                exit();
                                }        
                            }    
                            else{
                                $_SESSION['max_unavailable_copy_request_per_day'] = 0;
                            }

                            if(isset($_SESSION['unavailable_copy_requested_diary_no']) && $_SESSION['unavailable_copy_requested_diary_no'] == $diary_no){
                            ?>
                                <div class="alert alert-danger alert-dismissible">
                                    Please wait till completion of your previous request. 
                                    <!-- <a class='btn btn-danger' href='logout.php'>Exit</a> -->
                                </div>
                            <?php
                            // exit();
                            }else{

                            ?>

                            <div class="card mt-2" >
                                <div class="card-header bg-primary text-white font-weight-bolder mt-0 mb-0">
                                    Case Info
                                </div>
                                <div class="card-body">
                                    <div class="text-center " >
                                        <p><?= $_SESSION['session_cause_title']; ?></p>
                                        <strong><?= $_SESSION['session_case_no']; ?> (<?= $_SESSION['session_c_status'] =='P' ? '<span style="color: #0554DB;">Pending</span>' : '<span class="text-danger">Disposed</span>'; ?>)</strong>
                                        <p class="text-left">
                                            Applying as :<b>
                                            <?php if(getSessionData('login')['ref_m_usertype_id'] == AUTHENTICATED_BY_AOR){
                                                    echo "Authorized by AOR";
                                                }else{  if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 1){ echo "AOR";}
                                                    else if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 2){ echo "Party of the case"; }
                                                    else if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 3){ echo "Advocate/Appearing Counsel"; }
                                                    else if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 4){ echo "Third Party"; }
                                                    else if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 6){ echo "Authorized by AOR"; }
                                                }
                                                ?></b>
                                            <i class="fa fa-user text-info ml-5 " aria-hidden="true"></i> <?=$_SESSION['user_address'][0]['second_name'].' '.$_SESSION['user_address'][0]['first_name'];?> <i class="fa fa-phone-square text-info" aria-hidden="true"></i> <?=$_SESSION["applicant_mobile"];?> <i class="fa fa-envelope text-info" aria-hidden="true"></i> : <?=$_SESSION["applicant_email"];?></b>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <?php //third party not allowed in pending cases to request any copy
                            $res_fil_det = eCopyingGetFileDetails($diary_no);
                            if($res_fil_det[0]->c_status=='P' && (isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 4)){
                                ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>Pending Case! </strong>
                                    As per copying rules no copy is given to strangers in pending case.
                                </div>
                            <?php
                                // exit();
                            }else{
                            ?>


                            <div class="card col-md-12 mt-2 request_doc_toggle">
                                <div class="card-header bg-primary text-white font-weight-bolder">Request to make available document for eCopying</div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="input_fields_wrap">
                                                <div class="row col-md-12 mb-2">
                                                    <div class="col-md-4 form-group">
                                                        <span id="doctype_addon">Document Type</span>
                                                        <select name="document_type[]" aria-labelledby="doctype_addon" class="form-select cus-form-ctrl order_master document_type_class" required="">
                                                            <option value="">-Select-</option>
                                                            <?php
                                                            if(isset($_SESSION["session_filed"]) && ($_SESSION["session_filed"] == 3 || $_SESSION["session_filed"] == 4)){
                                                                $third_party_sub_qry = " id in (8,3,20,1,2) ";
                                                            }
                                                            else{
                                                                $third_party_sub_qry = "";
                                                            }
                                                            if(!empty($third_party_sub_qry)){
                                                                $third_party_sub_qry = urlencode($third_party_sub_qry);
                                                            }
                                                            $app_category = eCopyingGetDocumentType($third_party_sub_qry);
                                                            
                                                            foreach ($app_category as $row) {
                                                                ?>
                                                                <option data-mandate_date_of_order_type="<?=$row['mandate_date_of_order_type']?>" data-mandate_remark_of_order_type="<?=$row['mandate_remark_of_order_type']?>" value="<?=$row['id']?>"><?= ucwords(strtolower($row['order_type'])); ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <span id="order_file_dt_addon">Order/File Date</span>
                                                        <input name="order_date[]" aria-labelledby="order_file_dt_addon" class="form-control cus-form-ctrl bg-white" id="order_date_calendar" type="text" value="" minlength="10" maxlength="10" readonly required>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <span id="docdetail_addon">Document Detail</span>
                                                        <input name="doc_detail[]" aria-labelledby="docdetail_addon" type="text" class="form-control cus-form-ctrl" minlength="5" maxlength="100">
                                                    </div>
                                                    <div class="col-md-1 form-group">
                                                        <span id="action_addon">Action</span><br>
                                                        <button aria-labelledby="action_addon" class="add_field_button btn btn-sm btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row col-md-12 mt-2" style="text-align: center;">
                                <div class="row col-md-5">&nbsp;</div>
                                <div class="row col-md-2">
                                    <input type="button" name="btn_save" id="btn_save" value="Click To Confirm" class="btn btn-primary" />
                                </div>
                            </div>
                            <div class="row col-md-12">
                                <div class="above_error"></div>
                            </div>
                            <div class="note_unavailable_doc_request">
                            </div>
                            <?php } } ?>
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
<script src="<?= base_url(); ?>assets/js/sweetalert.min.js"></script>
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/sweetalert.css">
<script>

            $(document).ready(function() {
//$("#myModal").modal({backdrop: false});
                //$('[data-toggle="popover"]').popover();
                //$(".request_doc_toggle").hide();

                var max_fields = 5; //maximum input boxes allowed
                var wrapper = $(".input_fields_wrap"); //Fields wrapper
                var add_button = $(".add_field_button"); //Add button ID
                var x = 1; //initlal text box count
                var order_master = $(".order_master").html();
//alert(order_master);
                $(add_button).click(function(e){ //on add input button click
                    e.preventDefault();
                    if(x < max_fields){ //max input box allowed
                        x++; //text box increment
                        $(wrapper).append('<div class="row col-md-12 clearfix mb-2">\
                            <div class="col-md-4 form-group">\
                                <select name="document_type[]" class="form-control cus-form-ctrl document_type_class">'+order_master+'</select>\
                            </div>\
                            <div class="col-md-3 form-group">\
                                <input name="order_date[]" id="order_date_calendar" type="text" class="form-control cus-form-ctrl bg-white" minlength="10" maxlength="10" readonly required>\
                            </div>\
                            <div class="col-md-4 form-group">\
                                <input name="doc_detail[]" type="text" class="form-control cus-form-ctrl" minlength="5" maxlength="100">\
                            </div>\
                            <div class="col-md-1 form-group">\
                                <button aria-labelledby="action_addon" class="remove_field btn btn-sm btn-danger">\
                                    <i class="fa fa-trash" aria-hidden="true"></i>\
                                </button>\
                            </div>\
                        </div>'); //add input box
                    }
                    else{
                        alert('Maximum 5 documents request allowed');
                    }
                });


                $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
                    e.preventDefault(); $(this).parent().parent().remove(); x--;
                });

            });


            $(document).on("click", "#btn_save", function () {

                document_type = []; order_date = []; doc_detail = [];
                document_type_text = []; mandate_date_of_order_type = []; mandate_remark_of_order_type = [];
                all_doc_select = 1;


                $('.document_type_class option:selected').each(function(){
                    if($(this).val() > 0){
                        document_type.push($(this).val());
                        document_type_text.push($(this).text());
                        mandate_date_of_order_type.push($(this).data('mandate_date_of_order_type'));
                        mandate_remark_of_order_type.push($(this).data('mandate_remark_of_order_type'));
                    }
                    else{
                        all_doc_select = 0;
                        return false
                    }
                });

                if(all_doc_select != 1){
                    alert("Empty Document Type Not Allowed");
                    return false;
                }

                $('input[name^="order_date"]').each(function(input){
                    order_date.push($(this).val());
                });

                $('input[name^="doc_detail"]').each(function(input){
                    doc_detail.push($(this).val());
                });

                $('#show_error').html("");

                $(".validation").remove(); // remove it
                    //if doc request raised
                if(document_type.length > 0){
                    swal({
                        title: "Are you sure?",
                        text: "Ready to send Request",
                        icon: "warning",
                        buttons: true,
                        showCancelButton: true,
                        confirmButtonText: "Next!",
                        cancelButtonText: "Cancel",
                        dangerMode: true,
                    },
                    function(isConfirm) {
                            if (isConfirm) {
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo base_url('online_copying/requested_documents_save'); ?>",
                                    data:{document_type:document_type,order_date:order_date,doc_detail:doc_detail,mandate_date_of_order_type:mandate_date_of_order_type,mandate_remark_of_order_type:mandate_remark_of_order_type,document_type_text:document_type_text},
                                    cache: false,
                                    dataType: "json",
                                    success: function (data) {
                                        if(data.status == 'success'){
                                            swal({
                                                title: "Success!",
                                                text: "Your Request Forwarded Successfully.",
                                                icon: "success",
                                                buttons: true,
                                                showCancelButton: true,
                                                confirmButtonText: "Next!",
                                                cancelButtonText: "Cancel",
                                                dangerMode: true,
                                            },function(){
                                                    $(".note_unavailable_doc_request").html('<div class="alert alert-success alert-dismissible p-1 m-1 ml-3"><strong>Your Request Forwarded successfully.</strong></div>');
                                                    $(".request_doc_toggle").remove();
                                                    $(".unavailable_doc_request").remove();
                                                    $(".applicant_details_toggle").remove();
                                                    $(".confirm_validate_toggle").remove();
                                                    $("#btn_save").remove();
                                                }
                                            );
                                        }
                                        else{
                                            swal({
                                                title: "Error!",
                                                text: data.status,
                                                icon: "error",
                                                button: "Try Again!",
                                            })
                                        }
                                    }
                                });
                            }
                            else {
                                //swal("Record is safe!");
                            }
                        });
                }

            });

            $(document).on('click', '#order_date_calendar', function() {
    // Show the datepicker
    $(this).datepicker({
        format: 'dd/mm/yyyy', // Corrected the format to 'yyyy' instead of 'yyy'
        autoclose: true
    }).datepicker('show'); // Show the datepicker

    // Handle the changeDate event
    $(this).on('changeDate', function(ev) {
        // Set the value of the input to the selected date in the desired format
        $(this).val(moment(ev.date).format('DD/MM/YYYY')); // Use moment to format the date
        $(this).datepicker('hide'); // Hide the datepicker
    });
});

        </script>
@endpush