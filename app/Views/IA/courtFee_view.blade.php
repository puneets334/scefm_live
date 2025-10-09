<div class="panel panel-default">
    <h6 class="text-center fw-bold mb-2">Pay eCourt Fee</h6>
    <h5 style="text-align: center;"><b>Please note that No Printing charges are required to be paid</b> </h5>
    <div class="panel-body">
        <?php
        $attribute = array('class' => 'form-horizontal', 'name' => 'court_fee_details', 'id' => 'court_fee_details', 'autocomplete' => 'off');
        echo form_open('#', $attribute);
        $total_court_fee = $court_fee_details[0]['orders_challendged'] * $court_fee_details[0]['court_fee'];
        ?>

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Supreme Court Case Type : </label>
                <div class="col-md-6">
                    <p><?php echo_data($court_fee_details[0]['casename'] . 'Qwerty'); ?></p>
                </div>
            </div>

            <div class="row">
                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Subject Category : </label>
                <div class="col-md-6">
                    <?php echo_data($court_fee_details[0]['sub_name4'] . 'Dummy'); ?>
                </div>
            </div>

            <div class="row">
                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Orders Challenged : </label>
                <div class="col-md-6">
                    <?php echo_data($court_fee_details[0]['orders_challendged'] . 'Test'); ?>
                </div>
            </div>
            <div class="row">
                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Accordingly Calculated Fee : </label>
                <div class="col-md-6">
                    <?php echo_data($total_court_fee); ?>
                </div>
            </div>

        </div>
        <div class="clearfix"></div><br><br>
        <div class="text-center">
            <?php
            if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
                $prev_url = base_url('uploadDocuments');
                $next_url = base_url('miscellaneous_docs/document_share');
            }
            else if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
                $prev_url = base_url('uploadDocuments');
                //$next_url = base_url('affirmation');
                $next_url = base_url('IA/view');
            }
            else {
                $prev_url = '#';
                $next_url = '#';
            }
            ?>
            <a href="<?= $prev_url ?>" class="btn quick-btn gray-btn btnPrevious" type="button">PREVIOUS</a>
            <input type="submit" class="btn btn-success" id="pay_fee" name="submit" value="PAY">
            <a href="<?= $next_url ?>" class="btn quick-btn btnNext" type="button">NEXT</a>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function () {
        $('#court_fee_details').on('submit', function () {
            if ($('#court_fee_details').valid()) {
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('newcase/CourtFee/add_court_fee_details'); ?>",
                    data: form_data,
                    async: false,
                    success: function (data) {
                        var resArr = data.split('@@@');
                        if (resArr[0] == 1) {

                            $('#msg').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        }
                        $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });
                return false;
            } else {
                return false;
            }
        });
    });
</script>