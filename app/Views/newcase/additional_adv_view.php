<div class="panel panel-default">
    <h4 style="text-align: center;color: #31B0D5">Additional Advocate </h4>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php
                $attribute = array('class' => 'form-horizontal', 'name' => 'add_additional_aor', 'id' => 'add_additional_aor', 'autocomplete' => 'off');
                echo form_open('#', $attribute);
                ?>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"></div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Advocate on Record <span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <select tabindex = '1' name="additional_aors" id="case_acts" class="form-control input-sm filter_select_dropdown">
                                    <option value="" title="Select">Select AOR</option>
                                    <?php
                                    if (count($aors_list)) {
                                        foreach ($aors_list as $dataRes) {
                                            ?>
                                            <option value="<?php echo_data(url_encryption(trim($dataRes->bar_id . '$$' . $dataRes->aor_code))); ?>"><?php echo_data(strtoupper($dataRes->title . ' ' . $dataRes->name . ' ( ' . $dataRes->aor_code . ' )')); ?> </option>;
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"></div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"></div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-center">
                        <a href="<?= base_url('newcase/subordinate_court') ?>" tabindex = '3' class="btn btn-primary" type="button">Previous</a>
                        <input  tabindex = '2' type="submit" class="btn btn-success" id="aor_save" value="ADD"> 
                        <a href="<?= base_url('newcase/additionalInfo') ?>" tabindex = '4' class="btn btn-primary btnNext" type="button">Next</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"></div>
                </div>
                <?php echo form_close(); ?>
                <div class="row">
                    <div id="aors_list_data" class="col-lg-12 col-sm-12 col-xs-12">
                        <?php $this->load->view('newcase/additional_adv_aors_list_view'); ?>
                    </div>
                </div>
            </div>            
        </div>        
    </div>
</div>

<script type="text/javascript">

    function delete_additional_adv(delete_id) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('newcase/additionalAdv/delete'); ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, delete_id: delete_id},
            success: function (data) {

                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    $('#msg').show();
                    //window.location.href = resArr[2];
                    if (resArr[3] == 'aor') {
                        $('#aors_list_data').html(resArr[2]);
                    } else if (resArr[3] == 'senior') {
                        $('#sr_list_data').html(resArr[2]);
                    }
                } else if (resArr[0] == 3) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                }
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
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

    $(document).ready(function () {
        $('#add_additional_aor').on('submit', function () {
            if ($('#add_additional_aor').valid()) {
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('newcase/additionalAdv/add_additional_aor'); ?>",
                    data: form_data,
                    async: false,
                    beforeSend: function () {
                        $('#aor_save').val('Please wait...');
                        $('#aor_save').prop('disabled', true);
                    },
                    success: function (data) {
                        $('#aor_save').val('SAVE');
                        $('#aor_save').prop('disabled', false);
                        var resArr = data.split('@@@');
                        if (resArr[0] == 1) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        } else if (resArr[0] == 2) {
                            $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            $('#msg').show();
                            //window.location.href = resArr[2];
                            $('#aors_list_data').html(resArr[2]);
                        } else if (resArr[0] == 3) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
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
                return false;
            } else {
                return false;
            }
        });
    }
    );



</script>