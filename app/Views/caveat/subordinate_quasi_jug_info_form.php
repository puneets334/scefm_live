<?php
$attribute = array('class' => 'form-horizontal form-label-left', 'name' => 'quasi_jud_info', 'id' => 'quasi_jud_info', 'autocomplete' => 'off');
echo form_open('#', $attribute);
?>
<br>
<div class="col-sm-12 col-md-12 col-xs-12">
    <div class="form-group">
        <label class="control-label col-sm-5 input-sm"> </label>
        <div class="col-md-7 col-sm-12 col-xs-12"> 
            <label class="radio-inline"><input type="radio" id="court_type" name="appellate_court_type" value="<?php echo_data(url_encryption('3$$courtInfo')) ?>" maxlength="1"> <strong>First Appellate Court </strong></label>
            <label class="radio-inline"><input type="radio" id="court_type" name="appellate_court_type" value="<?php echo_data(url_encryption('4$$courtInfo')) ?>" maxlength="1"> <strong>Trial Court Information </strong></label>
        </div>
    </div>
</div><br><br>

<div class="row"><br> 
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case / Reference No.<span style="color: red">*</span> :</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <div class="input-group">
                    <input id="case_ref_no" name="case_ref_no" placeholder="Case / Reference No" maxlength="50"  class="form-control input-sm" type="text">
                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Case / Reference No . should be in alphanumeric (<?php echo VALIDATION_PREG_MATCH_MSG; ?>) with max value 50 character.">
                        <i class="fa fa-question-circle-o"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Order Date <span style="color: red">*</span> :</label>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <div class="input-group">
                    <input class="form-control has-feedback-left" name="case_order_dt" readonly="" id="case_order_dt" placeholder="DD/MM/YYYY" type="text">
                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Valid Order Date e.g.(DD/MM/YYYY).">
                        <i class="fa fa-question-circle-o"></i>
                    </span>
                </div>
            </div>
        </div>
    </div> 
</div>
<div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
        <?php
        $prev_redirect_url = 'caveat/extra_party';
        $next_redirect_url = 'uploadDocuments';
        ?>
        <a href="<?= base_url($prev_redirect_url) ?>" class="btn btn-primary" type="button">Previous</a>
        <input type="submit" class="btn btn-success" id="lower_court_save" value="SAVE">
        <a href="<?= base_url($next_redirect_url) ?>" class="btn btn-primary" type="button">Next</a>
    </div>
</div>
<?php echo form_close(); ?>  


<script type="text/javascript">
    $(document).ready(function () {
        $('#quasi_jud_info').on('submit', function () {
            if ($('#quasi_jud_info').valid()) {
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('caveat/subordinate_court/quasi_jud_info'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('#lower_court_save').val('Please wait...');
                        $('#lower_court_save').prop('disabled', true);
                    },
                    success: function (data) {
                        $('#lower_court_save').val('SAVE');
                        $('#lower_court_save').prop('disabled', false);
                        var resArr = data.split('@@@');
                        if (resArr[0] == 1) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        } else if (resArr[0] == 2) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");

                            setTimeout(function () {
                                window.location.href = '<?php echo base_url('caveat/subordinate_court'); ?>';
                            }, 1000);
                        }
                        $.getJSON("<?php echo base_url('csrf_token'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function () {
                        $.getJSON("<?php echo base_url('csrf_token'); ?>", function (result) {
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