<div class="panel panel-default">
    <?php if(session()->getFlashdata('validation_error') != '') { ?>
        <div class="row" id="errDiv">
            <div class="alert alert-danger" role="alert" id="alertMsg"><?php echo session()->getFlashdata('validation_error'); ?> </div>
        </div>
    <?php } ?>
    <h6 class="text-center fw-bold mb-2">Pay eCourt Fee</h6>
    <h5 style="text-align: center;"><b>Please note that No Printing charges are required to be paid</b> </h5>
    <div class="panel-body">
        <?php
        $attribute = array('class' => 'form-horizontal', 'name' => 'court_fee_details', 'id' => 'court_fee_details', 'autocomplete' => 'off');


        echo form_open('miscellaneous_docs/courtFee/add_court_fee_details', $attribute);
        ?>
        <div>
            <div class="col-md-12 col-sm-12 col-xs-12" style="display: none;">

                <div class="table-responsive">
                    <table id="datatable-responsive" class="table table-striped custom-table dataTable no-footer" cellspacing="0" width="100%">
                        <thead>
                            <tr class="success">
                                <th>Cost Per Page ( <i class="fa fa-rupee"></i> )</th>
                                <th>Uploaded Page(s)</th>
                                <th>Total Cost ( <i class="fa fa-rupee"></i> )</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php
                                $printing_cost_total = (int) @$uploaded_pages_count * (int) @$_SESSION['estab_details']['printing_cost'];
                                ?>
                                <td data-key='Cost Per Page ( <i class="fa fa-rupee"></i> )'><?php echo_data($_SESSION['estab_details']['printing_cost']); ?></td>
                                <td data-key='Uploaded Page(s)'><?php echo_data(@$uploaded_pages_count); ?></td>
                                <td data-key='Total Cost ( <i class="fa fa-rupee"></i> )'><?php echo_data(@$printing_cost_total); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12 ccol-md-12 col-sm-12 col-xs-12 mt-4">
                <?= ASTERISK_RED_MANDATORY; ?>
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <table id="datatable-responsive1 " class="table table-striped custom-table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr class="success">
                                    <th>#</th>
                                    <th>Court Fee Details</th>
                                    <th style="text-align: center">Amount ( <i class="fa fa-rupee"></i> )</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sr_no = 1;

                                $doc_list_with_letter_of_inspection_file = array(150);
                                $doc_list_with_affidavit = array(20, 50, 190);
                                $doc_list_of_affidavit_attested_place = array(30, 60, 110, 170, 220);
                                $doc_list_for_per_petition_calculation = array(84);
                                $doc_list_for_per_lower_court_order_challanged_number = array(85, 86, 87, 817, 120, 827, 828, 829, 830, 831, 835, 895, 8328);
                                $doc_list_of_zero_doc_fee_for_criminal_matter = array(842, 8120, 8137, 8144, 8152, 8153, 8160, 8252, 8271, 8302, 8324, 8346, 8368, 8385);
                                $diary_no = (int)@$court_fee_list3[0]['diary_no'] . (int)@$court_fee_list3[0]['diary_year'];
                                $case_nature = @$court_fee_list3[0]['nature'];
                                if (empty($case_nature)) {
                                    $case_nature = file_get_contents(ICMIS_SERVICE_URL . '/ConsumedData/caseNature?diaryNo=' . $diary_no);
                                }
                                $case_nature = trim($case_nature);
                                $case_if_sclsc_status = file_get_contents(ICMIS_SERVICE_URL . '/ConsumedData/checkCaseIfSCLSCStatus?diaryNo=' . $diary_no);

                                //TODO code for getting details of SC case nature which entry is done as lower court case if :done by kbp on 08122023
                                if ($case_nature == 'C' || empty($case_nature)) {
                                    $case_lct_details = file_get_contents(ICMIS_SERVICE_URL . '/ConsumedData/getCaseLowerCtDetailsWithCaseNature?diary_no=' . $diary_no);
                                    $case_lct_details = $case_lct_details ? json_decode($case_lct_details) : null;
                                    if (!empty($case_lct_details[0]->base_case_catetype_id)) {
                                        if ($case_lct_details[0]->base_case_catetype_id == 39); // Condition will be checked only for MA cases(i.e casetype id : 39)
                                        {
                                            $ma_lower_case_case_group = ($case_lct_details[0]->lct_case_case_grp) ? $case_lct_details[0]->lct_case_case_grp : null;
                                            if ($ma_lower_case_case_group == 'R')
                                                $case_nature = 'R';
                                        }
                                    }
                                }
                                if(!empty($court_fee_list3)){
                                foreach ($court_fee_list3 as $row) {
                                    $doc = (int)$row['doccode'] . (int)$row['doccode1'];
                                    if (in_array((int)$doc, $doc_list_with_affidavit, TRUE)) {
                                        $doc_court_fee = 0;
                                        $doc_extra_details = '';
                                        if ($row['court_fee_calculation_helper_flag'] == 'Y') //DOC submit with Affidavit flag
                                        {

                                            if ($case_nature == 'C')
                                                $doc_court_fee = $row['docfee'];
                                            else
                                                $doc_court_fee = 0;

                                            $doc_extra_details = ' (Submitted in the form of Affidavit)';
                                        } else {
                                            $doc_court_fee = 0;
                                            $doc_extra_details = ' ( Not submitted in the form of Affidavit)';
                                        }

                                        if ($case_if_sclsc_status == 1)  // code added on 19082023 by kbp for making court fee 0 : when any document/is filed in the if_sclsc case
                                        {
                                            $doc_court_fee = 0;
                                        }

                                ?>
                                        <tr style="color: #0055aa;size: 20px;">
                                            <td data-key="#"><?= $sr_no ?></td>
                                            <td data-key="Court Fee Details"> <?= $row['docdesc'] ?><?= $doc_extra_details ?> </td>
                                            <td data-key='Amount (₹)' align="center"><i class="fa fa-rupee"></i> <?= $doc_court_fee ?></td>
                                        </tr>

                                    <?php
                                        $sr_no++;
                                    } elseif (in_array((int)$doc, $doc_list_of_affidavit_attested_place, TRUE)) {
                                        $doc_court_fee = 0;
                                        $doc_extra_details = '';
                                        if ($case_nature == 'C')
                                            $doc_court_fee = $row['docfee'];
                                        else
                                            $doc_court_fee = 0;

                                        $affidavit_no_of_copies = '';
                                        if (!empty($row['no_of_affidavit_copies'])) {
                                            if ($row['no_of_affidavit_copies'] > 1) {
                                                $affidavit_no_of_copies = ' (No. of Copies:' . $row['no_of_affidavit_copies'] . ')';
                                                $doc_court_fee = $doc_court_fee * (int)$row['no_of_affidavit_copies'];
                                            }
                                        }

                                        if ($case_if_sclsc_status == 1)  // code added on 19082023 by kbp for making court fee 0 : when any document/is filed in the if_sclsc case
                                        {
                                            $doc_court_fee = 0;
                                        }

                                    ?>
                                        <tr style="color: #0055aa;size: 20px;">
                                            <td data-key='#'><?= $sr_no ?></td>
                                            <td data-key='Court Fee Details '> <?= $row['docdesc'] ?> <?= $doc_extra_details ?> <?= $affidavit_no_of_copies ?></td>
                                            <td data-key='Amount (₹)' align="center"><i class="fa fa-rupee"></i> <?= $doc_court_fee ?></td>
                                        </tr>

                                    <?php
                                        $sr_no++;
                                    } elseif (in_array((int)$doc, $doc_list_with_letter_of_inspection_file, TRUE)) {
                                        $doc_court_fee = 0;
                                        $doc_extra_details = '';
                                        if ($row['court_fee_calculation_helper_flag'] == 'Y') //Letter submitted as letter of inspection of file
                                        {
                                            if ($case_nature == 'C')
                                                $doc_court_fee = $row['docfee'];
                                            else
                                                $doc_court_fee = 0;

                                            $doc_extra_details = ' (Letter of inspection of file)';
                                        } else {
                                            $doc_court_fee = 0;
                                        }

                                        if ($case_if_sclsc_status == 1)  // code added on 19082023 by kbp for making court fee 0 : when any document/is filed in the if_sclsc case
                                        {
                                            $doc_court_fee = 0;
                                        }
                                    ?>
                                        <tr style="color: #0055aa;size: 20px;">
                                            <td data-key='#'><?= $sr_no;
                                                                $doc ?></td>
                                            <td data-key='Court Fee Details '> <?= $row['docdesc'] ?><?= $doc_extra_details ?> </td>
                                            <td data-key='Amount (₹)' align="center"><i class="fa fa-rupee"></i> <?= $doc_court_fee ?></td>
                                        </tr>
                                    <?php
                                        $sr_no++;
                                    } elseif (in_array((int)$doc, $doc_list_for_per_petition_calculation, TRUE)) {
                                    ?>
                                        <tr style="color: #0055aa;size: 20px;">
                                            <td data-key="#"><?= $sr_no ?></td>
                                            <td data-key="Court Fee Details"> <?= $row['docdesc'] ?> <br> (No. of petitioners
                                                : <?= $row['total_petitioners']; ?>)
                                            </td>
                                            <td data-key='Amount (₹)' align="center"><i class="fa fa-rupee"></i>
                                                <?php if ($case_nature == 'C')
                                                    $doc_court_fee = $row['docfee'];
                                                else
                                                    $doc_court_fee = 0;

                                                if ($case_if_sclsc_status == 1)  // code added on 19082023 by kbp for making court fee 0 : when any document/is filed in the if_sclsc case
                                                {
                                                    $doc_court_fee = 0;
                                                }

                                                ?>
                                                <?= (int)$doc_court_fee * (int)$row['total_petitioners'] ?></td>
                                        </tr>
                                    <?php
                                        $sr_no++;
                                    } elseif (in_array((int)$doc, $doc_list_for_per_lower_court_order_challanged_number, TRUE)) {
                                    ?>
                                        <tr style="color: #0055aa;size: 20px;">
                                            <td data-key="#"><?= $sr_no ?></td>
                                            <td data-key="Court Fee Details"> <?= $row['docdesc'] ?>
                                                <?php if ($row['order_challanged'] > 0) { ?>
                                                    (No. of order Challanged
                                                    : <?= $row['order_challanged']; ?>)
                                                <?php } ?>
                                            </td>
                                            <td data-key='Amount (₹)' align="center"><i class="fa fa-rupee"></i>
                                                <?php if ($case_nature == 'C')
                                                    $doc_court_fee = $row['docfee'];
                                                else
                                                    $doc_court_fee = 0;

                                                if ($case_if_sclsc_status == 1)  // code added on 19082023 by kbp for making court fee 0 : when any document/is filed in the if_sclsc case
                                                {
                                                    $doc_court_fee = 0;
                                                    echo  (int)$doc_court_fee;
                                                } else {
                                                    if ($row['order_challanged'] > 0) {
                                                        echo (int)$doc_court_fee * (int)$row['order_challanged'];
                                                    } else {
                                                        echo  (int)$doc_court_fee;
                                                    }
                                                }

                                                ?>
                                            </td>
                                        </tr>
                                    <?php
                                        $sr_no++;
                                    } elseif (in_array((int)$doc, $doc_list_of_zero_doc_fee_for_criminal_matter, TRUE)) {
                                        $doc_court_fee = 0;
                                        if ($case_nature == 'C')
                                            $doc_court_fee = (int)$row['docfee'];
                                        else
                                            $doc_court_fee = 0;


                                        if ($case_if_sclsc_status == 1)  // code added on 19082023 by kbp for making court fee 0 : when any document/is filed in the if_sclsc case
                                        {
                                            $doc_court_fee = 0;
                                        }

                                    ?>
                                        <tr style="color: #0055aa;size: 20px;">
                                            <td data-key="#"><?= $sr_no ?></td>
                                            <td data-key="Court Fee Details"> <?= $row['docdesc'] ?>
                                            </td>
                                            <td data-key='Amount (₹)' align="center"><i class="fa fa-rupee"></i> <?= (int)$doc_court_fee; ?></td>
                                        </tr>
                                    <?php
                                        $sr_no++;
                                    } else {

                                    ?>
                                        <tr style="color: #0055aa;size: 20px;">
                                            <td data-key="#"><?= $sr_no; ?></td>
                                            <td data-key="Court Fee Details"> <?= $row['docdesc']; ?> </td>
                                            <td data-key='Amount (₹)' align="center"><i class="fa fa-rupee"></i>
                                                <?php
                                                $doc_court_fee = 0;
                                                if ($case_nature == 'C') {
                                                    $doc_court_fee = (int)$row['docfee'];
                                                }
                                                if ($case_if_sclsc_status == 1)  // code added on 19082023 by kbp for making court fee 0 : when any document/is filed in the if_sclsc case
                                                {
                                                    $doc_court_fee = 0;
                                                }
                                                ?>
                                                <?= (int)$doc_court_fee ?></td>
                                        </tr>
                                <?php
                                        $sr_no++;
                                    }
                                }
                            }
                                ?>
                                <?php
                                if (is_array($payment_details) && count($payment_details) > 0) {
                                    foreach ($payment_details as $Efee) { ?>
                                        <?php if ($Efee['payment_status'] == 'Y' && $Efee['user_declared_extra_fee'] != 0) { ?>
                                            <tr style="color: #0055aa;size: 20px;">
                                                <td data-key="#"><?= $sr_no; ?></td>
                                                <td data-key="Court Fee Details"> Extra Court Fee </td>
                                                <td data-key='Amount (₹)' align="center"><i class="fa fa-rupee"></i> <?= (int)$Efee['user_declared_extra_fee']; ?></td>
                                            </tr>
                                        <?php $sr_no++;
                                        } ?>
                                <?php  }
                                }
                                ?>
                                <tr>
                                <td data-key="#"></td>
                                <td data-key="Court Fee Details"></td>
                                <td data-key="Amount ( ₹ )" colspan="3" style="text-align: center;padding-right: 71px;">
                                <?php if (@(int)$Efee['user_declared_extra_fee'] > 0) { ?>
                                    <label style="margin-top: 10px; font-weight: bold">Total : ₹ <?= @$court_fee + @$user_declared_extra_fee ?></label>
                                <?php } else { ?>
                                    <label style="margin-top: 10px; font-weight: bold">Total : ₹ <?= @$court_fee ?></label>
                                <?php } ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="row justify-content-end align-items-end h-100 court-fee-sec">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-10 court-fee-box">
                                <div class="form-group mb-2">
                                    <label class="control-label input-sm form-label">Want to pay more Court Fee ( <i class="fa fa-rupee"></i> )
                                        <span style="color: red">*</span></label>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="input-group ">
                                            <input type="text" onKeyPress="edValueKeyPress()" onKeyUp="edValueKeyPress(this)" id="user_declared_extra_fee" name="user_declared_extra_fee" minlength="1" maxlength="5" class="form-control input-lg cus-form-ctrl" placeholder="Court Fee Amount" value="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label input-sm form-label">Court Fee ( <i class="fa fa-rupee"></i> ) (To Pay)
                                        <span style="color: red">*</span></label>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <?php
                                            $court_fee_already_paid = 0;
                                            $user_declared_extra_fee = 0;
                                            $user_declared_court_fee = 0;
                                            $uploaded_pages = 0;
                                            $printing_cost_already_paid = 0;

                                            if (is_array($payment_details) && count($payment_details) > 0) {

                                                foreach ($payment_details as $payment) {
                                                    if ($payment['payment_status'] == 'Y')
                                                        $court_fee_already_paid = $court_fee_already_paid + (int)$payment['received_amt'];
                                                    $user_declared_court_fee = $user_declared_court_fee + (int)$payment['user_declared_court_fee'];
                                                    $user_declared_extra_fee = $user_declared_extra_fee + (int)$payment['user_declared_extra_fee'];
                                                    $uploaded_pages = $uploaded_pages + (int)$payment['uploaded_pages'];
                                                    $printing_cost_already_paid = $printing_cost_already_paid + (int)$payment['printing_total'];
                                                }
                                            }
                                            $court_fee_already_paid_without_extra_fee = $court_fee_already_paid - $user_declared_extra_fee;
                                            $uploaded_pages_count_pending = $uploaded_pages_count - $uploaded_pages;
                                            $printing_cost_to_be_paid = (int) $uploaded_pages_count_pending * (int) $_SESSION['estab_details']['printing_cost'];

                                            $printing_cost_total = (int)$printing_cost_to_be_paid + (int)$printing_cost_already_paid;
                                            $total_court_fee = (int)@$court_fee + (int)$printing_cost_to_be_paid + (int)$printing_cost_already_paid;
                                            $user_declared_court_fee = @$court_fee - $user_declared_court_fee;
                                            $pending_court_fee = $total_court_fee - $court_fee_already_paid_without_extra_fee;
                                            ?>
                                            <input type="hidden" id="print_fee_details" name="print_fee_details" value="<?php echo_data(url_encryption($uploaded_pages_count_pending . '$$' . $printing_cost_total . '$$' . $printing_cost_already_paid . '$$' . $printing_cost_to_be_paid . '$$' . $user_declared_court_fee)); ?>" />
                                            <input type="hidden" id="usr_court_fee_fixed" name="usr_court_fee_fixed" minlength="1" maxlength="5" class="form-control input-lg " placeholder="Court Fee Amount" value="<?= $pending_court_fee; ?>" readonly />
                                            <input type="text" id="usr_court_fee" name="usr_court_fee" minlength="1" maxlength="5" class="form-control input-lg cus-form-ctrl" placeholder="Court Fee Amount" value="<?= $pending_court_fee; ?>" readonly />
                                            <label style="margin-top: 10px;font-weight: bold">Total Court Fee : <?= @$court_fee ?> + <?= $printing_cost_total; ?>= <i class="fa fa-rupee"></i> <?= $total_court_fee ?> </label>
                                            <label style="margin-top:5px;font-weight: bold;color: #2c4762">Court Fee Already paid: <i class="fa fa-rupee"></i> <?= $court_fee_already_paid ?> </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-12">
                        <label style="margin-top: 10px;font-weight: bold;color: red;"><i class="fa fa-disclaimer"></i>"THE COURT FEE CALCULATED AND SHOWN IN THIS PAGE IS AT PER THE CASE TYPE, EARLIER COURT AND CASE CATEGORY. ANY DEFICIT COURT FEES DEFECT MAY BE RAISED AT THE SCRUTINY STAGE AND DEFICIT PAYMENT TO BE PAID ACCORDINGLY."</label>
                    </div>

                </div>
            </div>
            <div class="col-lg-6 ccol-md-6 col-sm-12 col-xs-12">
                <!--                code to show amount to be paid-->
            </div>
        </div>


        <div class="clearfix"></div>
        <div class="center-buttons">
            <?php
            /*if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
                $prev_url = base_url('uploadDocuments');
                $next_url = base_url('shareDoc');
            } else {
                $prev_url = '#';
                $next_url = '#';
            }*/
            ?>

            <?php
            if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
                $prev_url = base_url('documentIndex');
                $next_url = base_url('miscellaneous_docs/view');
            } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
                $prev_url = base_url('documentIndex');
                //$next_url = base_url('affirmation');
                $next_url = base_url('IA/view');
            } else {
                $prev_url = '#';
                $next_url = '#';
            }
            ?>
            <a href="<?= $prev_url ?>" class="btn quick-btn gray-btn btnPrevious" type="button">PREVIOUS</a>
            <span id="payCourtFeeSection">
                <?php if ($pending_court_fee > 0) { ?>
                    <input type="submit" class="btn btn-success" id="pay_fee" name="submit" value="PAY">
                <?php } ?>
            </span>
            <?php if ($pending_court_fee == 0) { ?>
                <a href="<?= $next_url ?>" class="btn quick-btn btnNext" type="button">NEXT</a>
            <?php } ?>

        </div>
        <?php echo form_close();  ?>
    </div>
</div>
<?php
if (isset($payment_details) && !empty($payment_details)) { ?>
    @include('shcilPayment.payment_list_view')
<?php }
?>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<script>
    var pending_court_fee = "<?= $pending_court_fee; ?>";
    if (pending_court_fee > 0) {
        $('.pay_fee').show();
        $('.pay_fee_next').hide();
    } else {
        $('.pay_fee').hide();
        $('.pay_fee_next').show();
    }

    function edValueKeyPress(txb) {
        txb.value = txb.value.replace(/[^0-9]/g, '');
        var usr_court_fee = $('#usr_court_fee_fixed').val();
        var morefee = $('#user_declared_extra_fee').val();
        if (morefee == '') {
            morefee = parseInt(0);
            $('#usr_court_fee').val(usr_court_fee)
        }
        var usr_court_fee_final = parseInt(usr_court_fee) + parseInt(morefee);
        $('#usr_court_fee').val(usr_court_fee_final);
        if (usr_court_fee_final > 0) {
            $('.pay_fee').show();
            $('.pay_fee_next').hide();
        } else {
            $('.pay_fee').hide();
            $('.pay_fee_next').show();
        }
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
</script>