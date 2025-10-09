<?php
if ($esigned_docs_details[0]->is_data_valid != 't') {
    // STARTS NEW CASE PARTY N PARY-IN-PERSON E-VERIFY FORM
    ?>
    <div id="msgdiv"></div>


    <div class="clearfix"></div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-4 col-xs-12 input-sm" for="document_signed"> Advocate Certificate  :</label>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <a href="<?= base_url('affirmation/viewUnsignedCertificate') ?>" target="_blank" onclick="return confirm('NOTE : eVerification is properly visible in Adobe Acrobat Reader DC ( Version 11 or greater).');"> <i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i> View Advocate Certificate</a></div>
    </div>
    <?php if (empty($_SESSION['eVerified_mobile_otp']['LITIGENT_MOB_OTP_VERIFY'])) {
        ?>
        <?php
        $attribute = array('class' => 'form-label-left', 'id' => 'case_send_mobile_otp', 'name' => 'case_send_mobile_otp', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
        echo form_open('#', $attribute);
        ?>
        <div class="clearfix"></div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm" >Advocate Name <span style="color: red">*</span> :</label>
            <div class="col-md-5 col-sm-6 col-xs-12">
                <div class="input-group">
                    <input id="name" name="name" placeholder="Name" class="form-control input-sm"  type="text" maxlength="50" value="<?php echo strtoupper($_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name']); ?>">
                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Name of Advocate as on Aadhaar." data-original-title="" title="">
                        <i class="fa fa-question-circle-o"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm" >Advocate Mobile <span style="color: red">*</span> :</label>
            <div class="col-md-5 col-sm-6 col-xs-12">
                <div class="input-group">
                    <input id="mobile" name="mobile" placeholder="Mobile" class="form-control input-sm" required="required" type="text" maxlength="10" value="<?php echo $_SESSION['login']['mobile_number']; ?>">
                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="" data-original-title="" title="">
                        <i class="fa fa-question-circle-o"></i>
                    </span>
                </div>
            </div>
            <div class="clearfix"></div>
            <label class="control-label col-md-5 col-sm-6 col-xs-12 input-sm" ></label>
            <div class="col-md-5 col-sm-6 col-xs-12">
                <div class="input-group">
                    <input type="button" name="verify" id="verify" class="btn btn-success" value="Send OTP">
                </div>
            </div>
        </div><div class="clearfix"></div> <br/><br/>
        </div>
        <?php echo form_close(); ?>
    <?php } else {
        ?>
        <div class="clearfix"></div>
        <?php
        $attribute = array('class' => 'form-label-left', 'id' => 'verify_otp', 'name' => 'verify_otp', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
        echo form_open('#', $attribute);
        ?>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm" > </label>
            <div class="col-md-5 col-sm-6 col-xs-12">
                <p style="color: green;"><i class="fa fa-check-circle"></i> One Time Password (OTP) has been sent to your mobile <?php echo $mask_number = str_repeat("*", strlen($_SESSION['eVerified_mobile_otp']['LITIGENT_MOBILE']) - 2) . substr($_SESSION['eVerified_mobile_otp']['LITIGENT_MOBILE'], -2) . '.'; ?></p>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm" > Mobile OTP <span style="color: red">*</span> :</label>
            <div class="col-md-5 col-sm-6 col-xs-12">
                <div class="input-group">
                    <input name="litigent_flag"  value="<?php echo url_encryption(1); ?>" type="hidden" >
                    <input id="mobile_otp" name="mobile_otp" placeholder="Mobile OTP" class="form-control input-sm" value="" required="required" type="text" maxlength="6">
                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Name of Advocate as on Aadhaar." data-original-title="" title="">
                        <i class="fa fa-question-circle-o"></i>
                    </span>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12 ">
                <input type="button" name="verify_mobile_otp" id="verify_mobile_otp" class="btn btn-success" value="Verify OTP">

            </div>
        </div>
        <div class="clearfix"></div> <br/><br/>
        <?php echo form_close(); ?>
        </div>
        <?php
    }
    ?>


    <?php
// ENDS NEW CASE PARTY N PARY-IN-PERSON E-SIGN FORM
}
?>



<div class="clearfix"></div><br>


<script>
    $(document).ready(function () {
        $('#verify').on('click', function () {
            var form_data = $('#case_send_mobile_otp').serialize() + '&type=' + 'litigent';
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('affirmation/docs/send_adv_litigent_mobile_otp_everified_doc'); ?>",
                data: form_data,
                async: false,
                success: function (data) {
                    var resArr = data.split('@@@');
                    if (resArr[0] == 1) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        setTimeout(function () {
                            $('#msg').hide();
                        }, 3000);
                    } else if (resArr[0] == 2) {
                        location.reload();
                    }

                    $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function () {
                    $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }

            });
        });
        $('#verify_advocate').on('click', function () {
            var form_data = $('#case_advocate_send_mobile_otp').serialize() + '&type=' + 'advocate';
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('New_case/send_adv_litigent_mobile_otp_everified_doc'); ?>",
                data: form_data,
                async: false,
                success: function (data) {

                    var resArr = data.split('@@@');
                    if (resArr[0] == 1) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        setTimeout(function () {
                            $('#msg').hide();
                        }, 3000);
                    } else if (resArr[0] == 2) {
                        location.reload();
                    }

                    $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function () {
                    $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }

            });
        });
        $('#verify_mobile_otp').on('click', function () {
            var form_data = $('#verify_otp').serialize();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('affirmation/docs/verify_adv_mobile_otp'); ?>",
                data: form_data,
                async: false,
                success: function (data) {

                    var resArr = data.split('@@@');
                    if (resArr[0] == 1) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        setTimeout(function () {
                            $('#msg').hide();
                        }, 3000);
                    } else if (resArr[0] == 2) {
                        location.reload();
                    }

                    $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function () {
                    $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }

            });
        });



    });
</script>
