<?php if (isset($payment_details) && !empty($payment_details)) { ?>
    <div class="panel panel-default"> 
        <div class="panel-body">
            <table id="courtFeeTable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr class="success">
                        <th>#</th>
                        <th>Order Number / Date</th>
                        <th>SHCIL Ref.No. / Date</th>
                        <th>Bank Name</th>
                        <th>Amount( <i class="fa fa-rupee"></i> )</th>   
                        <?php if ((bool) $_SESSION['estab_details']['is_charging_printing_cost']) { ?>
                            <th>Printing Cost ( <i class="fa fa-rupee"></i> )</th>
                            <th>Total Amount  ( <i class="fa fa-rupee"></i> )</th>
                        <?php } ?>
                        <th>Status</th>
<!--                        --><?php //if ($this->uri->segment(2) != 'view' && $this->uri->segment(1) != 'history' && $this->uri->segment(1) != 'affirmation') { ?>
<!--                            <th>Action</th>-->
<!--                            --><?php
//                        }
//                        if ($this->uri->segment(1) == 'affirmation') {
//                            if ($_SESSION['efiling_details']['doc_signed_used'] == SIGNED_AADHAR_CARD && $_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_DEFICIT_COURT_FEE) {
//                                ?>
<!--                                <th> eSign Status </th>-->
<!--                                --><?php
//                            }
//                        }
//                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php //echo '<pre>';print_r($payment_details);echo '</pre>';
                    $sno = 1;
                    $ref_m_usertype_id = !empty($_SESSION['login']['ref_m_usertype_id']) ? $_SESSION['login']['ref_m_usertype_id'] : NULL;
                    $stage_id = !empty($_SESSION['efiling_details']['stage_id']) ? $_SESSION['efiling_details']['stage_id'] : NULL;
                    foreach ($payment_details as $resData) {
                        $order_date = $resData['order_date'] ? date('d-m-Y h:i:s A', strtotime($resData['order_date'])) : '';
                        $txn_status_updated_on = $resData['txn_status_updated_on'] ? date('d-m-Y h:i:s A', strtotime($resData['txn_status_updated_on'])) : '';
                        $transaction_num = !empty($resData['transaction_num']) ? $resData['transaction_num'] : NULL;
                        $is_verified = !empty($resData['is_verified']) ? $resData['is_verified'] : NULL;
                        $is_locked = !empty($resData['is_locked']) ? $resData['is_locked'] : NULL;
                        $diaryNo = !empty($resData['caveat_num']) ? $resData['caveat_num'] : NULL;
                        $diaryYear = !empty($resData['caveat_year']) ? $resData['caveat_year'] : NULL;
                        $paymentPdf = '';
                        if(isset($resData['shcilpmtref']) && !empty($resData['shcilpmtref'])){
                            $paymentPdf = '<a href="'.base_url('shcilPayment/ViewPaymentChallan/' . url_encryption(htmlentities($resData['shcilpmtref'].'$$'.$resData['received_amt'], ENT_QUOTES))).'" target="_blank">Success  <i class="fa fa-file-pdf-o text-danger"></i> </a>';
                        }
                        else{
                            $paymentPdf  = ' Pending  ( <a class="pendingPaymentUpdate efiling_search" data-order-id="' . htmlentities(url_encryption($resData['registration_id'].'$$'.$resData['order_no'].'$$'.$resData['received_amt'])) . '">Update Status </a> )   ';
                        }
                        ?>
                        <tr>
                            <td data-key="#"><?= htmlentities($sno++, ENT_QUOTES); ?></td>
                            <td data-key="Order Number / Date"><?= htmlentities($resData['order_no'], ENT_QUOTES) .'</br>'.$order_date ?></td>
                            <td data-key="SHCIL Ref.No. / Date"><?= htmlentities($resData['transaction_num'], ENT_QUOTES) . '<br>' . htmlentities($txn_status_updated_on, ENT_QUOTES); ?></td>
                            <td data-key="Bank Name"><?= htmlentities($resData['bank_name'], ENT_QUOTES); ?></td>
                            <td data-key="Amount"><?= htmlentities($resData['user_declared_court_fee'], ENT_QUOTES); ?></td>
                            <?php if ((bool) $_SESSION['estab_details']['is_charging_printing_cost']) { ?>
                                <td data-key='Printing Cost ( <i class="fa fa-rupee"></i> )'><?= htmlentities($resData['printing_total'], ENT_QUOTES); ?></td>
                                <td data-key='Total Amount  ( <i class="fa fa-rupee"></i> )'><?= htmlentities($resData['user_declared_total_amt'], ENT_QUOTES); ?></td>
                            <?php } ?>
                            <td data-key="Status">  <?php echo $paymentPdf ;
                                $efiling_stages_array = array(Transfer_to_IB_Stage, DEFICIT_COURT_FEE_E_FILED,I_B_Approval_Pending_Admin_Stage,I_B_Defects_Cured_Stage);
                                if(!empty($ref_m_usertype_id) && $ref_m_usertype_id == USER_ADMIN && !empty($stage_id) && in_array($stage_id, $efiling_stages_array))
                                //if(!empty($ref_m_usertype_id) && $ref_m_usertype_id == USER_ADMIN && !empty($stage_id) && $stage_id == Transfer_to_IB_Stage)
                                {
                                    if(isset($transaction_num) && !empty($transaction_num) && !empty($is_verified) && isset($is_verified) && $is_verified == 'f'){
                                        echo '<a id="Verify'.$transaction_num.'" href="javaScript:void(0);" class="verifyFeeData efiling_search" data-actionType="verify" data-transaction_num="'.$transaction_num.'"><b>Verify</b></a>';
                                    }
                                    else  if(isset($transaction_num) && !empty($transaction_num) && !empty($is_verified) && isset($is_verified) && $is_verified == 't'){
                                        echo '<a class="verifiedFeeData"><b>Verified</b></a>';
                                    }
                                    if(isset($transaction_num) && !empty($transaction_num) && !empty($is_locked) && isset($is_locked) && $is_locked == 'f'  && !empty($is_verified) && isset($is_verified) && $is_verified == 't'){
                                        echo '<a href="javaScript:void(0);"  data-actionType="lock" class="verifyFeeData efiling_search" data-diaryNo="'.$diaryNo.'" data-diaryYear="'.$diaryYear.'" data-transaction_num="'.$transaction_num.'"><b>Lock</b></a>';
                                    }
                                    else  if(isset($transaction_num) && !empty($transaction_num) && !empty($is_locked) && isset($is_locked) && $is_locked == 't'){
                                        echo '<a class="verifiedFeeData"><b>Locked</b></a>';
                                    }
                                    echo '<a id="Verified'.$transaction_num.'" class="verifiedFeeData" style="display: none;"><b>Verified</b></a>';
                                    echo '<a id="Verifiedlock'.$transaction_num.'" href="javaScript:void(0);"  data-actionType="lock" class="verifyFeeData" style="display: none;" data-diaryNo="'.$diaryNo.'" data-diaryYear="'.$diaryYear.'" data-transaction_num="'.$transaction_num.'"><b>Lock</b></a>';
                                    echo '<a id="VerifiedLocked'.$transaction_num.'" class="verifiedFeeData" style="display: none;"><b>Locked</b></a>';
                                }

                            ?>
                                <input type="hidden" id="is_verified" value="<?=$is_verified.'#'.trim($resData['user_declared_court_fee'])?>"/>
                                <input type="hidden" id="is_locked" value="<?=$is_locked?>"/>
                            </td>
<!--                            --><?php //if ($this->uri->segment(2) != 'view' && $this->uri->segment(1) != 'history' && $this->uri->segment(1) != 'affirmation') { ?>
<!--                                <td>--><?php //if ($resData['payment_verified_by'] == NULL && $resData['payment_verified_date'] == NULL) { ?><!--<a href="--><?//= base_url('courtFee/offline/deleteReceipt/' . htmlentities(url_encryption(trim($resData['id']) . '$$del_receipt_id'), ENT_QUOTES)); ?><!--" onclick="return confirm('Are you sure to delete this record?')" ><i class="fa fa-trash"> Delete</i> --><?php //} ?><!--</a></td>-->
<!--                            --><?php //} ?><!--                            -->
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>

<style>
    .verifyFeeData{
        margin: 5px;
    }
    .verifiedFeeData{
        margin: 5px;
    }
</style>
<div class="modal fade" id="VerifyModal" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" style="width: 40%;">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="fa fa-pencil"></span>Court Fee <span id="fee_type"</span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="VerifyModalResult"></div>
                    <!--<div class="col-md-8 col-sm-8 col-xs-12 col-sm-offset-1">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-5 col-xs-5 input-sm uk-form-label"></label>
                            <div class="col-md-5 col-sm-5 col-xs-6">
                                <div  class="btn btn-success"  style="text-transform: capitalize; font-size:18px;"></div>
                            </div>
                        </div>
                    </div>
                    <br/> <br/>-->
                    <div class="col-md-8 col-sm-8 col-xs-12 col-sm-offset-2">
                        <div class="form-group">
                            <label class="control-label col-md-4 col-sm-4 col-xs-6 input-sm uk-form-label">Response Status:</label>
                            <div class="col-md-8 col-sm-8 col-xs-6">
                                <div id="RPSTATUS"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-12 col-sm-offset-2">
                        <div class="form-group">
                            <label class="control-label col-md-6 col-sm-6 col-xs-6 input-sm uk-form-label">Receipt number:</label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div id="receiptNumber"></div>
                            </div>
                        </div>
                    </div>
                    <!--start for lock-->
                    <div class="col-md-8 col-sm-8 col-xs-12 col-sm-offset-2 diaryNumberYear" style="display: none;">
                        <div class="form-group">
                            <label class="control-label col-md-6 col-sm-6 col-xs-6 input-sm uk-form-label">Diary number:</label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div id="diaryNumberYear"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 col-sm-8 col-xs-12 col-sm-offset-2 CFLNAME" style="display: none;">
                        <div class="form-group">
                            <label class="control-label col-md-6 col-sm-6 col-xs-6 input-sm uk-form-label">CFL Name:</label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div id="CFLNAME"></div>
                            </div>
                        </div>
                    </div>

                    <!--end for lock-->

                    <!--start for verify-->
                    <div class="col-md-8 col-sm-8 col-xs-12 col-sm-offset-2 DTISSUE" style="display: none;">
                        <div class="form-group">
                            <label class="control-label col-md-6 col-sm-6 col-xs-6 input-sm uk-form-label">Date of Issue:</label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div id="DTISSUE"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-12 col-sm-offset-2 CFAMT" style="display: none;">
                        <div class="form-group">
                            <label class="control-label col-md-6 col-sm-6 col-xs-6 input-sm uk-form-label">Court Fee:</label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <span id="CFAMT"></span> <i class="fa fa-rupee"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-12 col-sm-offset-2 STATUS" style="display: none;">
                        <div class="form-group">
                            <label class="control-label col-md-6 col-sm-6 col-xs-6 input-sm uk-form-label"><span id="STATUS_text"></span> Status:</label>
                            <div class="col-md-6 col-sm-6 col-xs-6 text-success">
                                <div id="STATUS"></div>
                            </div>
                        </div>
                    </div>
                    <!--end for verify-->

                </div>

                <div class="modal-footer">
                </div>
            </div>

        </div>
    </div>
</div>

<!-- end of code -->
<script>
    $(document).ready(function(){
    $(document).on('click','.verifyFeeData',function(){
        var type = $.trim($(this).attr('data-actionType'));
        var receiptNumber = $.trim($(this).attr('data-transaction_num'));
       // var receiptNumber ='DLCT0801D2121P';
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
                    // alert(data.status);
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
                        if (RPSTATUS=='FAIL'){
                            $('#RPSTATUS').css('color', 'red');
                            $('#STATUS').css('color', 'red');
                            $('.STATUS').css('color', 'red');
                            $('#fee_type').css('color', 'red');
                            $('#STATUS_text').html('Reason');
                        }else{
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


                        if (RPSTATUS=='SUCCESS' && status==true  && RCPTNO==receiptNumber){
                            var result=('type '+ type +'  RPSTATUS='+ RPSTATUS + '  status=' + status + '  RCPTNO='+ RCPTNO + '  receiptNumber='+ receiptNumber);
                        }else {
                            var result=('type '+ type +' verify Failed  '+'RPSTATUS='+ RPSTATUS + '  status=' + status + '  RCPTNO='+ RCPTNO + '  receiptNumber='+ receiptNumber);
                        }

                    }else{
                        var RPSTATUS = (data.res.LOCKTXN.LOCKRPDTL.RPSTATUS);
                        var RCPTNO = (data.res.LOCKTXN.LOCKRPDTL.RCPTNO);
                        var CFLNAME = (data.res.LOCKTXN.LOCKRPDTL.CFLNAME);
                        var diary_Year = (data.res.LOCKTXN.LOCKRPDTL.DIRYEAR);

                        var diary_No = (data.res.TXNHDR.DIRNO);
                        var slash='/';
                        var diaryNumberYear=diary_No+slash+diary_Year;

                        $('#Verify'+receiptNumber).hide();
                        $('#Verified'+receiptNumber).show();
                        $('#Verifiedlock'+receiptNumber).hide();
                        $('#VerifiedLocked'+receiptNumber).show();
                        // alert(data.res.RPSTATUS);
                        if (RPSTATUS !='SUCCESS'){
                            $('#RPSTATUS').css('color', 'red');
                            $('#fee_type').css('color', 'red');
                        }else{
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
                    }

                    $("#VerifyModal").modal('show');

                    $('.status_refresh').remove();
                    $.getJSON(base_url + "csrftoken", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        }
    });

    $(".pendingPaymentUpdate").click(function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var order_id = $(this).attr('data-order-id');
        $('.form-responce').remove();
        $(this).append(' <i class="status_refresh fa fa-refresh fa-spin"></i>');
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, order_id: order_id},
            url: base_url + "shcilPayment/paymentCheckStatus",
            success: function (data) {
                if (data=='SUCCESS|Status Successfully Updated.'){
                    alert(data);
                }
                $('.status_refresh').remove();
                window.location.reload();
                $.getJSON(base_url + "csrftoken", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
            });
        });
    });
</script>
