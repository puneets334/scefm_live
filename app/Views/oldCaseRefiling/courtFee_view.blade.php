<div class="panel panel-default">
    <h6 class="text-center fw-bold mb-2">Pay eCourt Fee</h6>
    <h5 style="text-align: center;"><b>Please note that No Printing charges are required to be paid </b> </h5>
    <div class="panel-body">
        
        <?php
        
        $attribute = array('class' => 'form-horizontal', 'name' => 'court_fee_details', 'id' => 'court_fee_details', 'autocomplete' => 'off');
        echo form_open('miscellaneous_docs/courtFee/add_court_fee_details', $attribute);
        //$total_court_fee = $court_fee_details[0]['orders_challendged'] * $court_fee_details[0]['court_fee'];
        ?>
        <div>
            <div class="col-md-12 col-sm-12 col-xs-12" style="display: none;">

                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr class="success">
                            <th>Cost Per Page ( <i class="fa fa-rupee"></i> )</th>
                            <th>Uploaded Page(s)</th>
                            <th>Total Cost ( <i class="fa fa-rupee"></i> )</th>
                            <!-- <th>Already Paid ( <i class="fa fa-rupee"></i> )</th>
                            <th>To Pay ( <i class="fa fa-rupee"></i> )</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            $printing_cost_total = (int) $uploaded_pages_count * (int) $_SESSION['estab_details']['printing_cost'];
                            //comment 16 march 2021 by akg
                            /*$printing_cost_already_paid = 0;
                            $printing_cost_to_be_paid = $printing_cost_total - $printing_cost_already_paid;*/
                            ?>
                            <!--  //comment 16 march 2021 by akg
                             <input type="hidden" id="print_fee_details" name="print_fee_details" value="<?php /*echo_data(url_encryption($uploaded_pages_count
                                .'$$'.$printing_cost_total.'$$'.$printing_cost_already_paid.'$$'.$printing_cost_to_be_paid)); */ ?>" />-->
                            <td data-key='Cost Per Page ( <i class="fa fa-rupee"></i> )'><?php echo_data($_SESSION['estab_details']['printing_cost']); ?></td>
                            <td data-key='Uploaded Page(s)'><?php echo_data($uploaded_pages_count); ?></td>
                            <td data-key='Total Cost ( <i class="fa fa-rupee"></i> )'><?php echo_data($printing_cost_total); ?></td>
                            <!-- <td><?php /*echo_data($printing_cost_already_paid); */ ?></td>
                            <td><?php /*echo_data($printing_cost_to_be_paid); */ ?></td>-->
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12 ccol-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <?php //var_dump($court_fee_list1) 
                    ?>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <table id="datatable-responsive1" class="table table-striped table-bordered dt-responsive custom-table  nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr class="success">
                                    <th>#</th>
                                    <th>Court Fee Details </th>
                                    <th style="text-align: center">Amount ( <i class="fa fa-rupee"></i> )</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sr_no = 1;


                                /*foreach ($court_fee_list2 as $row)  {  */ ?><!--
                                <tr style="color: #0055aa;size: 20px;">
                                    <td><?/*=$sr_no*/ ?></td>
                                    <td> <?/*=$row['docdesc']  */ ?>
                                    </td>
                                    <td align="center"><i class="fa fa-rupee"></i> <?/*= $row['docfee'] */ ?></td>
                                </tr>
                                --><?php /* $sr_no++; }*/

                                    $doc_list_with_letter_of_inspection_file = array(150);
                                    $doc_list_with_affidavit = array(20, 50, 190);
                                    $doc_list_of_affidavit_attested_place = array(30, 60, 110, 170, 220);
                                    $doc_list_for_per_petition_calculation = array(84);
                                    $doc_list_for_per_lower_court_order_challanged_number = array(85, 86, 87, 817, 120, 827, 828, 829, 830, 831, 835, 895, 8328);
                                    $doc_list_of_zero_doc_fee_for_criminal_matter = array(842, 8120, 8137, 8144, 8152, 8153, 8160, 8252, 8271, 8302, 8324, 8346, 8368, 8385);
                                    // var_dump($court_fee_list3);
                                    
                                    $diary_no = (int)$court_fee_list3[0]['diary_no'] . (int)$court_fee_list3[0]['diary_year'];
                                    $case_nature = $court_fee_list3[0]['nature'];
                                    if (empty($case_nature)) {
                                        $case_nature = file_get_contents(ICMIS_SERVICE_URL . '/ConsumedData/caseNature?diaryNo=' . $diary_no);
                                    }
                                    $case_if_sclsc_status = file_get_contents(ICMIS_SERVICE_URL . '/ConsumedData/checkCaseIfSCLSCStatus?diaryNo=' . $diary_no);
                                    foreach ($court_fee_list3 as $row) {
                                        

                                        $doc = (int)$row['doccode'] . (int)$row['doccode1'];

                                        // if($sc_case_type_id==19)
                                        // {
                                        //     $case_nature='R'; //if the contempt petition casetype is selected then it can be treated as criminal matters thus no doc fees will be applicable
                                        // }


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
                                            <td data-key='#'><?= $sr_no ?></td>
                                            <td data-key='Court Fee Details '> <?= $row['docdesc'] ?><?= $doc_extra_details ?> </td>
                                            <td data-key='Amount ( <i class="fa fa-rupee"></i> )' align="center"><i class="fa fa-rupee"></i> <?= $doc_court_fee ?></td>
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

                                            /*if ($row['court_fee_calculation_helper_flag'] == 'Y') //DOC submit with Affidavit attested within Delhi
                                    {
                                        $doc_court_fee = $row['docfee'];
                                        $doc_extra_details=' (Attested within Delhi)';
                                    }
                                    else {
                                        $doc_court_fee = 0;
                                        $doc_extra_details=' (Attested outside of Delhi)';
                                    }*/
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
                                            <td data-key="#"><?= $sr_no ?></td>
                                            <td data-key="Court Fee Details"> <?= $row['docdesc'] ?> <?= $doc_extra_details ?> <?= $affidavit_no_of_copies ?></td>
                                            <td data-key='Amount ( <i class="fa fa-rupee"></i> )' align="center"><i class="fa fa-rupee"></i> <?= $doc_court_fee ?></td>
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
                                            <td data-key="#"><?= $sr_no;
                                                $doc ?></td>
                                            <td data-key="Court Fee Details"> <?= $row['docdesc'] ?><?= $doc_extra_details ?> </td>
                                            <td data-key='Amount ( <i class="fa fa-rupee"></i> )' align="center"><i class="fa fa-rupee"></i> <?= $doc_court_fee ?></td>
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
                                            <td data-key='Amount ( <i class="fa fa-rupee"></i> )' align="center"><i class="fa fa-rupee"></i>
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
                                            <td data-key='Amount ( <i class="fa fa-rupee"></i> )' align="center"><i class="fa fa-rupee"></i>
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
                                            <td data-key='Amount ( <i class="fa fa-rupee"></i> )' align="center"><i class="fa fa-rupee"></i> <?= (int)$doc_court_fee; ?></td>
                                        </tr>
                                    <?php
                                            $sr_no++;
                                        } else {
                                    ?>
                                        <tr style="color: #0055aa;size: 20px;">
                                            <td data-key="#"><?= $sr_no; ?></td>
                                            <td data-key="Court Fee Details"> <?= $row['docdesc']; ?> </td>
                                            <td data-key='Amount ( <i class="fa fa-rupee"></i> )' align="center"><i class="fa fa-rupee"></i>
                                                <?php if ($case_nature == 'C')
                                                    $doc_court_fee = (int)$row['docfee'];
                                                else
                                                    $doc_court_fee = 0;

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
                                ?>
                                <?php

                                if (isset($payment_details) && !empty($payment_details)) {
                                    foreach ($payment_details as $Efee) { ?>
                                        <?php if ($Efee['payment_status'] == 'Y' && $Efee['user_declared_extra_fee'] != 0) { ?>
                                            <tr style="color: #0055aa;size: 20px;">
                                                <td data-key="#"><?= $sr_no; ?></td>
                                                <td data-key="Court Fee Details"> Extra Court Fee </td>
                                                <td data-key='Amount ( <i class="fa fa-rupee"></i> )' align="center"><i class="fa fa-rupee"></i> <?= (int)$Efee['user_declared_extra_fee']; ?></td>
                                            </tr>
                                        <?php $sr_no++;
                                        } ?>
                                <?php  }
                                } ?>
                            </tbody>
                        </table>

                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="row justify-content-end align-items-end h-100 court-fee-sec">
                            <?= ASTERISK_RED_MANDATORY; ?>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-10 court-fee-box">
                                <div class="form-group  mb-2">
                                    <label class="control-label input-sm form-label">Want to pay more Court Fee ( <i class="fa fa-rupee"></i> )
                                        <span style="color: red">*</span></label>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="input-group ">
                                            <input type="text" onKeyPress="edValueKeyPress()" onKeyUp="edValueKeyPress(this)" id="user_declared_extra_fee" name="user_declared_extra_fee" minlength="1" maxlength="5" class="form-control input-lg cus-form-ctrl" placeholder="Court Fee Amount" value="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-12 col-xs-12 input-sm">Court Fee ( <i class="fa fa-rupee"></i> ) (To Pay)
                                        <span style="color: red">*</span></label>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="input-group ">
                                            <!--<label style="width: 70px;" class="form-control input-sm " <span class="input-group-addon" data-placement="bottom"  data-toggle="popover" data-content="Total Court Fee "><?/*=$court_fee;*/ ?></label>-->
                                            <?php
                                            $court_fee_already_paid = 0;
                                            $user_declared_extra_fee = 0;
                                            $user_declared_court_fee = 0;
                                            $uploaded_pages = 0;
                                            $printing_cost_already_paid = 0;
                                            if (isset($payment_details) && !empty($payment_details)) {
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
                                            //$total_court_fee=(int)$court_fee+(int)$printing_cost_to_be_paid;

                                            $printing_cost_total = (int)$printing_cost_to_be_paid + (int)$printing_cost_already_paid;
                                            $total_court_fee = (int)$court_fee + (int)$printing_cost_to_be_paid + (int)$printing_cost_already_paid;
                                            $user_declared_court_fee = $court_fee - $user_declared_court_fee;
                                            $pending_court_fee = $total_court_fee - $court_fee_already_paid_without_extra_fee;
                                            ?>
                                            <input type="hidden" id="print_fee_details" name="print_fee_details" value="<?php echo_data(url_encryption($uploaded_pages_count_pending . '$$' . $printing_cost_total . '$$' . $printing_cost_already_paid . '$$' . $printing_cost_to_be_paid . '$$' . $user_declared_court_fee)); ?>" />
                                            <input type="hidden" id="usr_court_fee_fixed" name="usr_court_fee_fixed" minlength="1" maxlength="5" class="form-control input-lg cus-form-ctrl" placeholder="Court Fee Amount" value="<?= $pending_court_fee; ?>" readonly />
                                            <input type="text" id="usr_court_fee" name="usr_court_fee" minlength="1" maxlength="5" class="form-control input-lg cus-form-ctrl" placeholder="Court Fee Amount" value="<?= $pending_court_fee; ?>" readonly /><br>
                                            <label style="margin-top: 10px;font-weight: bold">Total Court Fee : <?= $court_fee ?> + <?= $printing_cost_total; ?>= <i class="fa fa-rupee"></i> <?= $total_court_fee ?></label>
                                            <label style="margin-top: 2px;font-weight: bold;color: #2c4762">Court Fee Already paid: <i class="fa fa-rupee"></i> <?= $court_fee_already_paid ?> </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                
                            </div> -->
                        </div>
                    </div>
                    <!--<label style="margin-top: 2px;font-weight: bold;color: red;><i class="fa fa-disclaimer"></i>"THE COURT FEE CALCULATED AND SHOWN IN THIS PAGE IS AT PER THE CASE TYPE, EARLIER COURT AND CASE CATEGORY.ANY DEFICIT COURT FEE WHICH REMAINS AS DIFFERENCE SHALL BE RAISED AS DEFECT AT SCRUTINY STAGE AND THE PAYMENT TO SUCH EFFECT COMPLYING THE DEFECT RAISED SHALL BE MADE BY THE AOR/PARTY-IN-PERSON."</label>-->
                    <label style="margin-top: 2px;font-weight: bold;color: red;"><i class=" fa fa-disclaimer"></i>"THE COURT FEE CALCULATED AND SHOWN IN THIS PAGE IS AT PER THE CASE TYPE, EARLIER COURT AND CASE CATEGORY. ANY DEFICIT COURT FEES DEFECT MAY BE RAISED AT THE SCRUTINY STAGE AND DEFICIT PAYMENT TO BE PAID ACCORDINGLY."</label>
                </div>
            </div>
            <div class="col-lg-6 ccol-md-6 col-sm-12 col-xs-12">
                <!--                code to show amount to be paid-->
            </div>
        </div>


        <div class="clearfix"></div><br><br>
        <div class="text-center">
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
            } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == OLD_CASES_REFILING) {
                $prev_url = base_url('uploadDocuments/DefaultController');
                //$next_url = base_url('affirmation');
                $next_url = base_url('oldCaseRefiling/view');
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
            <?php if ($pending_court_fee <= 0) { ?>
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
    function edValueKeyPress(txb) {
        var pending_court_fee = <?= $pending_court_fee ?>;
        var txb_value = txb.value.replace(/[^0-9]/g, '');

        var usr_court_fee = $('#usr_court_fee_fixed').val();
        var morefee = $('#user_declared_extra_fee').val();
        if (morefee == '') {
            morefee = parseInt(0);
            $('#usr_court_fee').val(usr_court_fee)
        }
        var usr_court_fee_final = parseInt(usr_court_fee) + parseInt(morefee);

        $('#usr_court_fee').val(usr_court_fee_final);
        //alert(!(isNaN(parseInt(txb.value)))+'#'+parseInt(txb.value)+'#'+usr_court_fee_final);
        if (!(isNaN(parseInt(txb_value))) && parseInt(txb_value) > 0 && usr_court_fee_final > 0) {
            $('#payCourtFeeSection').empty();
            var payButtonHTML = $('<input type="submit" class="btn btn-success" id="pay_fee" name="submit" value="PAY">');
            $('#payCourtFeeSection').append(payButtonHTML);
            $('.btnNext').hide();
        } else {
            $('#payCourtFeeSection').empty();
            $('.btnNext').show();
        }

    }
</script>