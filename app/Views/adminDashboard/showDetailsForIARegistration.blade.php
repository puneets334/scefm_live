@extends('layout.app')
@section('content')
<div class="mainPanel ">
         <div class="panelInner">
            <div class="middleContent">
               <div class="container-fluid dash-card">
               <div class="center-content-inner comn-innercontent">
                           <div class="tab-content">
                              <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="row" >
			<div class="col-lg-12">
<div class="row">
    <h6 class="text-center fw-bold">Register E-Filed IA / Misc. Documents</h6>
</div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> Register E-Filed IA / Misc. Documents</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span id="customErrorMessage"></span>
                </div>
                <div class="modal-footer">
                    <button type="button"  data-close="1" class="btn btn-secondary closeButton" data-bs-dismiss="modal">Close</button>
                    <!--                <button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>




    <?php
    if(isset($details['details'][0]['board_type']) && $details['details'][0]['board_type']=='C')
        $board='Honble Chamber Court';
    elseif(isset($details['details'][0]['board_type']) && $details['details'][0]['board_type']=='J')
        $board='Honble Court';
    elseif(isset($details['details'][0]['board_type']) && $details['details'][0]['board_type']=='R')
        $board='Ld. Registrar Court';
    ?>
    <div class="title-sec">
            <h5 class="unerline-title">Case Details</h5>
    </div>
        <!-- <legend> Case Details</legend> -->
       <div class="col-md-12 col-sm-12 col-xs-12" align="center"> <label><?php if(isset($details['details'][0]['c_status']) && $details['details'][0]['c_status']=='P') echo '<font color=green>Case is Pending!</font>';
        elseif(isset($details['details'][0]['c_status']) && $details['details'][0]['c_status']=='D') echo '<font color=red>Case is Disposed!</font>';?></label>
           </div>
        <div class="col-md-12 col-sm-12 col-xs-12" align="center">
                          <label><?php if(isset($details['details'][0]['judgment_reserved_date']) && $details['details'][0]['judgment_reserved_date']!=null){
                      echo '<font color=red>Judgment is reserved for '.date('d-m-Y',strtotime($details['details'][0]['judgment_reserved_date'])) .'!</font>';?></label>
                <?php }
                elseif(isset($details['details'][0]['next_dt']) && $details['details'][0]['next_dt']!=null && empty($case_listing_details->listed[0]->next_dt)){
                echo '<font style="color:red; font-size:14px; font-weight: bold;">Case is proposed to be listed on '.date('d-m-Y',strtotime($details['details'][0]['next_dt'])) .' in '.$board.'!</font>';?></label>
               <?php }?>
            </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-sm-1 col-md-1 col-xs-1">
                <label> Case No. :</label>
            </div>
            <div class="col-sm-4 col-md-4 col-xs-4">
            <?php if(isset($details['details'][0]['reg_no_display']) && $details['details'][0]['reg_no_display']!=null){
            ?>
                <span style="color:black; font-size:14px; font-weight: bold;"> <?php echo $details['details'][0]['reg_no_display'].'@ '.$diaryno;?></span>
       <?php } else{
            $diary_number=substr($diaryno,0,-4);
            $diary_year=substr($diaryno,-4);
            ?>
                <span style="color:black; font-size:14px; font-weight: bold;"> <?php echo $diary_number.'/'.$diary_year;?></span>
    <?php  } ?>
        </div>
                <div class="col-sm-1 col-md-1 col-xs-1">
                    <label> Cause Title:</label>
                </div>
                <div class="col-sm-4 col-md-4 col-xs-4"><span style="color:black; font-size:14px; font-weight: bold;"><?php echo (isset($details['details'][0]['pet_name']) ? $details['details'][0]['pet_name'] : '').' Vs '.(isset($details['details'][0]['res_name']) ? $details['details'][0]['res_name'] : '');?> </span>
            </div>
        </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-sm-1 col-md-1 col-xs-1">
                <label> Registered On :</label>
            </div>
            <div class="col-sm-4 col-md-4 col-xs-4">
                <?php if(isset($details['details'][0]['active_fil_dt']) && $details['details'][0]['active_fil_dt']!=null){
                    ?>
                    <span style="color:black; font-size:14px; font-weight: bold;"> <?php echo date('d-m-Y',strtotime($details['details'][0]['active_fil_dt']));?></span>
                <?php } ?>
            </div>
            <div class="col-sm-1 col-md-1 col-xs-1">
                <label> Alloted to:</label>
            </div>
            <div class="col-sm-4 col-md-4 col-xs-4">
            <?php if(isset($details['details'][0]['name']) && $details['details'][0]['name']!==null){?>
                <span style="color:black; font-size:14px; font-weight: bold;"><?php echo $details['details'][0]['name'].' [ '.$details['details'][0]['empid'].' ] -'.$details['details'][0]['section_name'];?> </span>
                <?php } ?>
            </div>
        </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-sm-1 col-md-1 col-xs-1">
                <label> Subject Category :</label>
            </div>
            <div class="col-sm-4 col-md-4 col-xs-4">
                    <span style="color:black; font-size:14px; font-weight: bold;"> <?php echo (isset($details['details'][0]['sub_name1']) ? $details['details'][0]['sub_name1'] : '').' ('.(isset($details['details'][0]['category_sc_old']) ? $details['details'][0]['category_sc_old'] : '').')';?></span>
            </div>
            <div class="col-sm-1 col-md-1 col-xs-1">
                <label> State Agency:</label>
            </div>
            <div class="col-sm-4 col-md-4 col-xs-4">
                    <span style="color:black; font-size:14px; font-weight: bold;"><?php echo (isset($details['details'][0]['agency_state']) ? $details['details'][0]['agency_state'] : '');?> </span>
            </div>
        </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
                <?php if(isset($case_listing_details->listed[0]) && !empty($case_listing_details->listed[0])) {
                    ?>
                    <?php
                    if (strtotime($case_listing_details->listed[0]->next_dt) >= strtotime(date("Y-m-d"))) {
                        ?>
                        <span style="color:red; font-size:14px; font-weight: bold;">
                            <div class="col-sm-1 col-md-1 col-xs-1">
                                <label> Listed For :</label>
                            </div>
                            <div class="col-sm-4 col-md-4 col-xs-4">
                                <p > <?= date('d-m-Y', strtotime($case_listing_details->listed[0]->next_dt)) ?></p>
                            </div>
                        </span>
                    <?php  } else { ?>
                        <div class="col-sm-1 col-md-1 col-xs-1">
                            <label> Last Listed On :</label>
                        </div>
                        <div class="col-sm-4 col-md-4 col-xs-4">
                            <p style="color:black; font-size:14px; font-weight: bold;"> <?= date('d-m-Y', strtotime($case_listing_details->listed[0]->next_dt)) ?></p>
                        </div>
                    <?php } ?>
                <?php  } ?>
        </div>
        </div>

    <div class="title-sec">
            <h5 class="unerline-title">Documents Details</h5>
    </div>
       <!-- <legend>Documents Details</legend> -->
        <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-sm-1 col-md-1 col-xs-1">
                <label> Filed By :</label>
            </div>
            <div class="col-sm-4 col-md-4 col-xs-4">
                <span style="color:black; font-size:14px; font-weight: bold;"> <?php echo (isset($efiling_data[0]['first_name']) ? $efiling_data[0]['first_name'] : '').'  '.(isset($efiling_data[0]['last_name']) ? $efiling_data[0]['last_name'] : '').' ('.(isset($efiling_data[0]['aor_code']) ? $efiling_data[0]['aor_code'] : '').')';?></span>
            </div>
            <div class="col-sm-1 col-md-1 col-xs-1">
                <label> Filed On :</label>
            </div>
            <div class="col-sm-4 col-md-4 col-xs-4">
                <span style="color:black; font-size:14px; font-weight: bold;"> <?php echo isset($efiling_data[0]['uploaded_on']) ? date('d-m-Y',strtotime($efiling_data[0]['uploaded_on'])) : '';?></span>
            </div>
        </div>
        </div>
        <br/><br/>
        <?php //print_r($efiling_data);
        //print_r($registeredDocs);
        ?>
        <table class="table table-striped table-bordered  custom-table dt-responsive nowrap" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>#</th>
                <th>Document Title</th>
                <th>Document No.</th>
                <th>No. of Pages</th>
                <th>View</th>
                <th>Remarks</th>
                <!--<th>Attach With</th>-->
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i=1;
            $total = count($efiling_data);
            foreach ($efiling_data as $doc){
                $doc_num ='';
                $icmis_docyear ='';
                $docnumber ='';
                //$doc_path=base_url($doc->file_path.$doc->file_name);
                $doc_path=$doc['file'];
                if(isset($doc['icmis_docnum']) && !empty($doc['icmis_docnum']) && isset($doc['icmis_docyear']) && !empty($doc['icmis_docyear'])){
                    $total--;
                    $doc_num = $doc['icmis_docnum'];
                    $icmis_docyear = $doc['icmis_docyear'];
                    $docnumber = $doc_num.' / '.$icmis_docyear;
                }
                ?>
            <tr>
                <td data-key="#"><?php echo $i++;?></td>
                <td data-key="Document Title"><?php echo $doc['docdesc']; ?>  </td>
                <td data-key="Document No."><?php echo $docnumber; ?>  </td>
                <td data-key="No. of Pages"><?php echo $doc['page_no']; ?>  </td>
                <!-- <td data-key="View"><a href="<?php echo ('https://efiling.sci.gov.in/'.$doc_path); ?>" target="_blank"> View </a></td> -->
                <td data-key="View"><a href="<?php echo (base_url().$doc_path); ?>" target="_blank"> View </a></td>
                <td data-key="Remarks"><textarea class="form-control cus-form-ctrl" id="remarks_<?php echo $doc['doc_id'];?>"></textarea></td>

                <?php
                if(isset($doc_num) && !empty($doc_num)){ ?>
                    <td data-key=""></td>  
                    <td data-key=""></td>
               <?php }
                else {

                    ?>
                    <!--<td><select id="ddlAttach_<?/*=$doc->doc_id*/?>" onchange="flipActionButton(<?/*=$doc->doc_id*/?>)">
                            <option value="" selected="selected">Select</option>
                            <?php
/*                            $attache_with_doc_title="";
                            foreach($registeredDocs as $regDoc){
                                if($doc->attached_with_doc_id==$regDoc['doc_id']){
                                    echo "<option value='".$regDoc['doc_id']."' selected='selected'>".$regDoc['docdesc']."</option>";
                                    $attache_with_doc_title=$regDoc['doc_title'];
                                }
                                else{
                                    echo "<option value='".$regDoc['doc_id']."'>".$regDoc['docdesc']."</option>";
                                }
                            }*/?>
                        </select>
                    <br><br>
                        <label class="text-danger"><?/*=$attache_with_doc_title*/?></label>
                    </td>-->
                    <td data-key="">
                        <?php
                        if(empty($doc['attached_with_doc_id'])){
                            $registerDisplay="display: block";
                            $attachDisplay="display: none";
                        }
                        else{
                            $registerDisplay="display: none";
                            $attachDisplay="display: block";
                        }
                        ?>
                        <?php
                         if(empty($doc['attached_with_doc_id'])){
                        ?>
                        <div class="center-buttons">
                        <div id="divRegister_"<?=$doc['doc_id']?> class="<?= $registerDisplay; ?>">
                        <button type="button"  id="<?php echo $doc['doc_id'];?>"  onClick="register_doc(this.id)" class="quick-btn">Transfer to ICMIS</button>
                        <!-- <input type="button"  id="<?php echo $doc['doc_id'];?>" value="Transfer to ICMIS" onClick="register_doc(this.id)"> -->
                        </div>
                        <div id="divAttach_"<?=$doc['doc_id']?> class="<?= $attachDisplay; ?>">
                            <button type="button"  id="btnAttach_<?php echo $doc['doc_id'];?>" value="" onClick="no_action(<?=$doc['doc_id']?>)" class="quick-btn">No Action</button>
                            <!-- <input type="button"  id="btnAttach_<?php echo $doc['doc_id'];?>" value="No Action" onClick="no_action(<?=$doc['doc_id']?>)"> -->
                        </div>
                        </div>
                        
                        <?php } else {echo "No Action";}?>
                    </td>
                <?php
                }
                ?>

            </tr>
            <?php }?>
            </tbody>
        </table>
    <input type="hidden" name="remaining_doc" id="remaining_doc" data-remainingDoc="<?php echo $total;?>" />

</div>
      <!--start IA and MISCDUC PaymentFee by akg-->
<div class="col-sm-12">
    <a class="list-group-item" style="background: #EDEDED; padding: 20px;" data-toggle="collapse" data-parent="#MainMenu"><?php if(!isset($payment_details) || empty($payment_details)){?><font style="color:red;"> <b>Fees Paid</b></font><?php } else{?> <b>Fees Paid</b><?php } ?></a>
     <?php
     if (isset($payment_details) && !empty($payment_details)) {
        render('shcilPayment.payment_list_view', ['payment_details' => $payment_details]);
    }
     ?>
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
      <!--end IA and MISCDUC PaymentFee-->
@endsection
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<style>
    fieldset {
        background-color: #eeeeee;
    }

    legend {
        background-color: gray;
        color: white;
        padding: 5px 10px;
    }


</style>

<script type="text/javascript">
    function flipActionButton(doc_id){
        var selected_item=$("#ddlAttach_"+doc_id).val();
        if(selected_item==""){
            /*$("#divAttach_"+doc_id).css("display", "none");
            $("#divRegister_"+doc_id).css("display", "block");*/
            $("#divAttach_"+doc_id).hide();
            $("#divRegister_"+doc_id).show();
            return false;
        }
        else {
            $("#divAttach_"+doc_id).hide();
            $("#divRegister_"+doc_id).hide();
            return false;
        }
    }
    function attach_doc(doc_id){
        diary_no='<?php echo $diaryno;?>';
        var attach_with_doc_id=$("#ddlAttach_"+doc_id).val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            url: "<?= base_url('admin/EfilingAction/attachDoc') ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,doc_id: doc_id,diary_no:diary_no,attach_with_doc_id:attach_with_doc_id},
            cache:false,
            dataType:'json',
            ContentType: 'application/json',
            success: function(result){
                if(typeof result == 'string'){
                    result = JSON.parse(result);
                }
                $("#exampleModal").modal('show');
                if(result.status == 'SUCCESS'){
                    var message = 'Attached Successfully!';
                    $("#customErrorMessage").html('');
                    $("#customErrorMessage").html(message);
                    setTimeout(function(){
                            window.location.reload(); },
                        2000);
                }
                else{
                    var message = result.error;
                    $("#customErrorMessage").html('');
                    $("#customErrorMessage").html(message);
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
    }
    function no_action(doc_id){
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            url: "<?= base_url('admin/EfilingAction/noaction') ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,doc_id: doc_id},
            cache:false,
            dataType:'json',
            ContentType: 'application/json',
            success: function(result){
                if(typeof result == 'string'){
                    result = JSON.parse(result);
                }
                $("#exampleModal").modal('show');
                if(result.status == 'SUCCESS'){
                    var message = 'Updated Successfully!';
                    $("#customErrorMessage").html('');
                    $("#customErrorMessage").html(message);
                    setTimeout(function(){
                            window.location.reload(); },
                        2000);
                }
                else{
                    var message = result.error;
                    $("#customErrorMessage").html('');
                    $("#customErrorMessage").html(message);
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
    }
    function register_doc(id)
    {
        diary_no='<?php echo $diaryno;?>';
        var Remarks_data=$('#remarks_'+id).val();

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var remaining = $("#remaining_doc").attr('data-remainingDoc');
        $.ajax({
            type: "POST",
            url: "<?= base_url('admin/EfilingAction/registerDoc') ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,doc_id: id,diary_no:diary_no,Remarks_data:Remarks_data},
            cache:false,
            dataType:'json',
            ContentType: 'application/json',
            success: function(result){
                if(typeof result == 'string'){
                     result = JSON.parse(result);
                }

                // return false;
                $("#exampleModal").modal('show');
                var today = new Date();
                var year = today.getFullYear();
                if(result.status == 'SUCCESS'){
                    var message = 'Document Number : '+result.doc_number+' / '+year;
                    $("#customErrorMessage").html('');
                    $("#customErrorMessage").html(message);
                    result.doc_id = id;
                    result.diary_no = diary_no;
                    result.remaining = remaining;
                    $.ajax({
                        type: "POST",
                        data: result,
                        url: "<?php echo base_url('admin/EfilingAction/updateDocumentNumber'); ?>",
                        dataType:'json',
                        ContentType: 'application/json',
                        cache:false,
                        success: function(updateData){
                            if(typeof updateData == 'string'){
                                updateData = JSON.parse(updateData);
                                var message = updateData.message;
                                if(updateData.status=='SUCCESS'){
                                    $("#customErrorMessage").html('');
                                    $("#customErrorMessage").html(message);
                                }else if(updateData.status=='ERROR'){
                                    $("#customErrorMessage").html('');
                                    $("#customErrorMessage").html(message);
                                }
                            }

                            // return false;
                             setTimeout(function(){
                                //window.location.reload();
                                     location.reload();
                                },
                                3000);
                        }
                    });
                    }
                    else{
                        var message = result.error;
                        $("#customErrorMessage").html('');
                        $("#customErrorMessage").html(message);
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
    }
    var base_url = '<?php echo base_url(); ?>';
    $(".check_stock_holding_payment_status").click(function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var order_id = $(this).attr('data-order-id');
        $('.form-responce').remove();
        $(this).append(' <i class="status_refresh fa fa-refresh fa-spin"></i>');
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, order_id: order_id},
            url: '<?php echo base_url('shcilPayment/paymentCheckStatus'); ?>',
            // url: base_url + "shcilPayment/paymentCheckStatus",
            success: function (data) {
                $('.status_refresh').remove();
                if (data=='SUCCESS|Status Successfully Updated.'){
                    alert(data);
                }
                window.location.reload();
                $.getJSON(base_url + "csrftoken", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    });
    $(document).on('click','.verifyFeeData',function(){
        var type = $.trim($(this).attr('data-actionType'));
        var receiptNumber = $.trim($(this).attr('data-transaction_num'));
        //alert(receiptNumber);return false;
        //var receiptNumber ='DLCT0801D2121P';
        var diaryNo = $.trim($(this).attr('data-diaryNo'));
        var diaryYear = $.trim($(this).attr('data-diaryYear'));
        //alert('type='+ type + '  receiptNumber=' + receiptNumber + '  diaryNo='+ diaryNo + '  diaryYear='+ diaryYear);
        if(type=='lock') {
            if (diaryNo ==='' && diaryYear ===''){
             alert('Please generate diary number first then try to lock court fee.');
                return false;
            }
        }
        if(type && receiptNumber){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('.form-responce').remove();
            $('.status_refresh').html('');
            $(this).append(' <i class="status_refresh fa fa-refresh fa-spin"></i>');
            var postData = {CSRF_TOKEN: CSRF_TOKEN_VALUE, type: type,receiptNumber:receiptNumber,diaryNo:diaryNo,diaryYear:diaryYear};
            $.ajax({
                type: "POST",
                url: base_url + "newcase/FeeVerifyLock_Controller/feeVeryLock",
                data: JSON.stringify(postData),
                cache:false,
                async:false,
                dataType: 'json' ,
                contentType: 'application/json',
                success: function (data) {
                    var status =(data.status);
                    if (type=='verify') {
                        var RPSTATUS = (data.res.CERTRPDTL.RPSTATUS);
                        var RCPTNO = (data.res.CERTRPDTL.RCPTNO);
                        var DTISSUE = (data.res.CERTRPDTL.DTISSUE);
                        var CFAMT = (data.res.CERTRPDTL.CFAMT);
                        var STATUS = (data.res.CERTRPDTL.STATUS);
                        $('#Verify'+receiptNumber).hide();
                        $('#Verified'+receiptNumber).show();
                        $('#Verifiedlock'+receiptNumber).show();
                        $('#VerifiedLocked'+receiptNumber).hide();
                        //alert(data.res.CERTRPDTL.RPSTATUS);
                        if (RPSTATUS=='FAIL') {
                            $('#RPSTATUS').css('color', 'red');
                            $('#STATUS').css('color', 'red');
                            $('.STATUS').css('color', 'red');
                            $('#fee_type').css('color', 'red');
                            $('#STATUS_text').html('Reason');
                        } else{
                            $('#RPSTATUS').css('color', 'green');
                            $('#fee_type').css('color', 'green');
                            $('.STATUS').css('color', 'green');
                            $('#STATUS_text').html('');
                        }
                        $('.DTISSUE').show();
                        $('.CFAMT').show();
                        $('.STATUS').show();
                        $('#fee_type').html('Verify');
                        $('#RPSTATUS').html(RPSTATUS);
                        $('#receiptNumber').html(RCPTNO);
                        $('#DTISSUE').html(DTISSUE);
                        $('#CFAMT').html(CFAMT);
                        $('#STATUS').html(STATUS);
                        $('#diaryNumberYear').html('');
                        $('#CFLNAME').html('');
                        $('.diaryNumberYear').hide();
                        $('.CFLNAME').hide();
                        if (RPSTATUS=='SUCCESS' && status==true  && RCPTNO==receiptNumber) {
                            var result=('type '+ type +'  RPSTATUS='+ RPSTATUS + '  status=' + status + '  RCPTNO='+ RCPTNO + '  receiptNumber='+ receiptNumber);
                        } else{
                            var result=('type '+ type +' verify Failed  '+'RPSTATUS='+ RPSTATUS + '  status=' + status + '  RCPTNO='+ RCPTNO + '  receiptNumber='+ receiptNumber);
                        }
                    } else{
                        var RPSTATUS = (data.res.LOCKTXN.LOCKRPDTL.RPSTATUS);
                        var RCPTNO = (data.res.LOCKTXN.LOCKRPDTL.RCPTNO);
                        var CFLNAME = (data.res.LOCKTXN.LOCKRPDTL.CFLNAME);
                        var diary_Year = (data.res.LOCKTXN.LOCKRPDTL.DIRYEAR);
                        var diary_No = (data.res.TXNHDR.DIRNO);
                        var slash='/';
                        var diaryNumberYear=diary_No+slash+diary_Year;
                        //var RPSTATUS = (data.res.RPSTATUS);
                        // var RCPTNO = (data.res.RCPTNO);
                        $('#Verify'+receiptNumber).hide();
                        $('#Verified'+receiptNumber).show();
                        $('#Verifiedlock'+receiptNumber).hide();
                        $('#VerifiedLocked'+receiptNumber).show();
                        // alert(data.res.RPSTATUS);
                        if (RPSTATUS !='SUCCESS') {
                            $('#RPSTATUS').css('color', 'red');
                            $('#fee_type').css('color', 'red');
                        } else{
                            $('#RPSTATUS').css('color', 'green');
                            $('#fee_type').css('color', 'green');
                        }
                        $('.diaryNumberYear').show();
                        $('.CFLNAME').show();
                        $('#fee_type').html('Locking');
                        $('#RPSTATUS').html(RPSTATUS);
                        $('#receiptNumber').html(RCPTNO);
                        $('#diaryNumberYear').html(diaryNumberYear);
                        $('#CFLNAME').html(CFLNAME);
                        $('#DTISSUE').html('');
                        $('#CFAMT').html('');
                        $('#STATUS').html('');
                        $('.DTISSUE').hide();
                        $('.CFAMT').hide();
                        $('.STATUS').hide();
                        if (status==true  && RCPTNO==receiptNumber) {
                            var result=('type '+ type +'  RPSTATUS='+ RPSTATUS + '  status=' + status + '  RCPTNO='+ RCPTNO + '  receiptNumber='+ receiptNumber+ '  Diary Number='+ diary_No+'/'+diary_Year+ '  CFLNAME='+ CFLNAME);
                        } else{
                            var result=('type '+ type +' verify Failed  '+'RPSTATUS='+ RPSTATUS + '  status=' + status + '  RCPTNO='+ RCPTNO + '  receiptNumber='+ receiptNumber);
                        }
                    }
                    // alert(result);
                    // $("#VerifyModalResult").html(result);
                    $("#VerifyModal").modal('show');
                    $('.status_refresh').remove();
                    $.getJSON(base_url + "csrftoken", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        }
        //location.reload();
    });
</script>