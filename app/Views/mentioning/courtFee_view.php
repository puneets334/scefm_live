<div class="panel panel-default">
    <h4 style="text-align: center;color: #31B0D5"> Pay Court Fee </h4>
    <div class="panel-body">
        <?php
        $attribute = array('class' => 'form-horizontal', 'name' => 'court_fee_details', 'id' => 'court_fee_details', 'autocomplete' => 'off');
        echo form_open('mentioning/courtFee/add_court_fee_details', $attribute);
        //$total_court_fee = $court_fee_details[0]['orders_challendged'] * $court_fee_details[0]['court_fee'];
        ?>
        <div>
            <div class="col-md-12 col-sm-12 col-xs-12">             
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr class="success">
                            <th>Cost Per Page ( <i class="fa fa-rupee"></i> )</th>
                            <th>Uploaded Page(s)</th>                        
                            <th>Total Cost ( <i class="fa fa-rupee"></i> )</th>                        
                            <th>Already Paid ( <i class="fa fa-rupee"></i> )</th>
                            <th>To Pay ( <i class="fa fa-rupee"></i> )</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            $printing_cost_total = (int) $uploaded_pages_count * (int) $_SESSION['estab_details']['printing_cost'];
                            $printing_cost_already_paid = 0;
                            $printing_cost_to_be_paid = $printing_cost_total - $printing_cost_already_paid;
                            ?>
                    <input type="hidden" id="print_fee_details" name="print_fee_details" value="<?php echo_data(url_encryption($uploaded_pages_count
                            .'$$'.$printing_cost_total.'$$'.$printing_cost_already_paid.'$$'.$printing_cost_to_be_paid)); ?>" />
                            <td><?php echo_data($_SESSION['estab_details']['printing_cost']); ?></td>
                            <td><?php echo_data($uploaded_pages_count); ?></td>
                            <td><?php echo_data($printing_cost_total); ?></td>
                            <td><?php echo_data($printing_cost_already_paid); ?></td>
                            <td><?php echo_data($printing_cost_to_be_paid); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 ccol-md-6 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Court Fee ( <i class="fa fa-rupee"></i> )
                                <span style="color: red">*</span></label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input type="text" id="usr_court_fee" name="usr_court_fee" minlength="1" maxlength="5" class="form-control input-sm" placeholder="Court Fee Amount"/> 
                                    <span class="input-group-addon" data-placement="bottom"  data-toggle="popover" data-content="Petitioner name should be in characters (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                        <i class="fa fa-question-circle-o" ></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> 
            <div class="col-lg-6 ccol-md-6 col-sm-12 col-xs-12">
                <!--                code to show amount to be paid-->
            </div>
        </div>


        <div class="clearfix"></div><br><br>
        <div class="text-center">
            <?php
            if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
                $prev_url = base_url('documentIndex');
                $next_url = base_url('shareDoc');
            }elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
                $prev_url = base_url('documentIndex');
                $next_url = base_url('shareDoc');
            }elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
                $prev_url = base_url('documentIndex');
                $next_url = base_url('shareDoc');
            }elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MENTIONING) {
                $prev_url = base_url('uploadDocuments');
                $next_url = base_url('affirmation');
            } else {
                $prev_url = '#';
                $next_url = '#';
            }
            ?>
            <a href="<?= $prev_url ?>" class="btn btn-primary btnPrevious" type="button">Previous</a>
            <input type="submit" class="btn btn-success" id="pay_fee" name="submit" value="PAY">
            <a href="<?= $next_url ?>" class="btn btn-primary btnNext" type="button">Next</a>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<?php 
if(isset($payment_details) && !empty($payment_details)){
    $this->load->view('shcilPayment/payment_list_view');
}
?>
