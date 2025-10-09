<div class="panel-body panel-body-in" style="background: white;"> 
    <?php $star_requered = '<span style="color: red">*</span>'; ?>
    <center><h3>Verify Your Self </h3> </center> 
    <?php echo $this->session->flashdata('msg'); ?>
    <div class="panel panel-default">  
        <div class="panel-body" style="padding:60px;padding-top: 0;padding-bottom: 0;">
            <?php
            $attribute = array('class' => 'form-horizontal form-label-left', 'name' => 'advocate_reg_info', 'id' => 'advocate_reg_info', 'autocomplete' => 'off', 'enctype' => "multipart/form-data");
            echo form_open('register/AdvSignUp/upload_id_proof', $attribute);
            ?>

            <div class="col-md-12 col-sm-12 col-xs-12"> 
                <center> 
                    <label class="radio-inline">
                        <input type="radio" name="optradio" checked>Upload ID Proof
                    </label>
                    <!--                <label class="radio-inline">
                                        <input type="radio" name="optradio"> Offline Aadhar
                                    </label> -->
                </center>
                <hr>
                <div class="form-group mb-3">  
                    <div class="col-md-6 col-sm-12 col-xs-12 " > 
                        <label style="float: right;"> Upload ID Proof :</label>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12 " > 
                        <input type="file" required id="advocate_image" name="advocate_image">  
                    </div>
                </div>

                <br>
                <?php if (!empty($_SESSION['image_and_id_view']['profile_photo'])) { ?>
                    <center>
                        <img src="<?php echo $_SESSION['image_and_id_view']['profile_photo']; ?>" class="img-thumbnail expand-img" style="height:400px;width:350px;">
                    </center>
                <?php } ?>
                <br>
                <div class="form-group "> 
                    <div class="col-md-4 col-sm-6 col-xs-12 col-md-offset-6" style="padding: 10px;">
                        <input type="submit" class="btn btn-success btn-sm" id="info_save" value="UPLOAD">
                        <?php if (!empty($_SESSION['image_and_id_view']['profile_photo'])) { ?>
                            <a href="<?php echo base_url('register/AdvSignUp/final_submit'); ?>" class="btn btn-success btn-sm"> FINAL SUBMIT </a>
                        <?php } ?>

                    </div>
                </div>

            </div> 


            <?php echo form_close(); ?>  
        </div>
    </div>
</div> 
</div> 

<script>
    $(document).ready(function () {
        $('.filter_select_dropdown').select2();
    });

    $('#date_of_birth').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:-1",
        dateFormat: "dd/mm/yy",
        defaultDate: '-40y'
    });

    $('#state_id').change(function () {
        $('#district_list').val('');
        $('#establishment_list').val('');
        var get_state_id = $(this).val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
            url: "<?php echo base_url('register/AdvSignUp/get_dist_list'); ?>",
            success: function (data)
            {
                if (data.indexOf('ERROR') != -1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + data + " <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    $('#district_list').html('<option value=""> Select District </option>');

                } else {
                    $('#msg').hide();
                    $('#district_list').html(data);
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


</script>
<?php $this->load->view('register/adv_signup_nav'); ?>