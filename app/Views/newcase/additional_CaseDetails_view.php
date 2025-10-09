<div class="panel panel-default">
    <h4 style="text-align: center;color: #31B0D5"> Additional Case Details </h4>
    <div class="panel-body">


        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <?php
                $attribute = array('class' => 'form-horizontal', 'name' => 'add_keywords', 'id' => 'add_keywords', 'autocomplete' => 'off');
                echo form_open('#', $attribute);
                ?>
                <div class="row">
                    <input  tabindex = '1' type="hidden" id="input1" name="input1" value="<?php echo_data(url_encryption($saved_additionalCaseDetails[0]->keywords . '$$keyword_data')); ?>" />
                    <input tabindex = '2' type="hidden" id="action" name="action" value="add" />
                    <div class="form-group">
                        <label class="control-label col-lg-4 col-md-4 col-sm-12 col-xs-12 input-sm"> Keywords <span style="color: red">*</span>:</label>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                            <select tabindex = '3' name="keywords" id="keywords" class="form-control input-sm filter_select_dropdown">
                                <option value="" title="Select">Select Keywords </option>                                    
                                <?php
                                if (count($keywords_list)) {
                                    foreach ($keywords_list as $dataRes) {
                                        ?>
                                        <option value="<?php echo_data(url_encryption(trim($dataRes->id . '$$keyword_id'))); ?>"><?php echo_data(strtoupper($dataRes->keyword_description)); ?> </option>;
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group center">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5 text-center">
                            <input tabindex = '4' type="submit" class="btn btn-success" id="keyword_save" value="SAVE">
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-md-offset-5">
                        <div id = "keywords_data">
                            <?php $this->load->view('newcase/keywords_list_view'); ?>
                        </div>                        
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                <?php
                $attribute = array('class' => 'form-horizontal', 'name' => 'add_additionalCaseDetails', 'id' => 'add_additionalCaseDetails', 'autocomplete' => 'off');
                echo form_open('#', $attribute);
                ?>
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12 input-sm">Question of Law <span style="color: red">*</span>:</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <textarea name="question_of_law" id="question_of_law" placeholder="Question of Law in brief" class="form-control input-sm"><?php echo_data($saved_additionalCaseDetails[0]->question_of_law); ?></textarea>
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter House No, Street No, Sector, Colony and Landmarks. Please Select District and Taluka from the below mentioned field. Do not repeat District and Taluka in Address fields and District and Taluka Fields. Address can be alphanumeric (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                        <i class="fa fa-question-circle-o" ></i>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>  

                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12 input-sm">Grounds <span style="color: red">*</span>:</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <textarea name="grounds" id="grounds" placeholder="Grounds in brief" class="form-control input-sm"><?php echo_data($saved_additionalCaseDetails[0]->grounds); ?></textarea>
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter House No, Street No, Sector, Colony and Landmarks. Please Select District and Taluka from the below mentioned field. Do not repeat District and Taluka in Address fields and District and Taluka Fields. Address can be alphanumeric (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                        <i class="fa fa-question-circle-o" ></i>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div> 

                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12 input-sm">Interim Grounds <span style="color: red">*</span>:</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <textarea name="interim_grounds" id="interim_grounds" placeholder="Interim Grounds in brief" class="form-control input-sm"><?php echo_data($saved_additionalCaseDetails[0]->grounds_interim); ?></textarea>
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter House No, Street No, Sector, Colony and Landmarks. Please Select District and Taluka from the below mentioned field. Do not repeat District and Taluka in Address fields and District and Taluka Fields. Address can be alphanumeric (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                        <i class="fa fa-question-circle-o" ></i>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12 input-sm">Main Prayer <span style="color: red">*</span>:</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <textarea name="main_prayer" id="main_prayer" placeholder="Write Main Prayer in brief" class="form-control input-sm"><?php echo_data($saved_additionalCaseDetails[0]->main_prayer); ?></textarea>
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter House No, Street No, Sector, Colony and Landmarks. Please Select District and Taluka from the below mentioned field. Do not repeat District and Taluka in Address fields and District and Taluka Fields. Address can be alphanumeric (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                        <i class="fa fa-question-circle-o" ></i>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12 input-sm">Interim Relief <span style="color: red">*</span>:</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <textarea name="interim_relief" id="interim_relief" placeholder="Write Main Prayer in brief" class="form-control input-sm"><?php echo_data($saved_additionalCaseDetails[0]->interim_relief); ?></textarea>
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter House No, Street No, Sector, Colony and Landmarks. Please Select District and Taluka from the below mentioned field. Do not repeat District and Taluka in Address fields and District and Taluka Fields. Address can be alphanumeric (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                        <i class="fa fa-question-circle-o" ></i>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group center">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5 text-center">
                        <input type="submit" class="btn btn-success" id="cd_save" value="SAVE">
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>         
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">               
                <a href="<?= base_url('newcase/additionalAdv') ?>" class="btn btn-primary btnPrevious" type="button">Previous</a>                

                <a href="<?= base_url('newcase/uploadDocumenets') ?>" class="btn btn-primary btnNext" type="button">Next</a> 
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

    $(document).ready(function () {
        $('#add_keywords').on('submit', function () {
            if ($('#add_keywords').valid()) {
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>newcase/additionalCaseDetails/add_keywords",
                    data: form_data,
                    async: false,
                    beforeSend: function () {
                        $('#keyword_save').val('Please wait...');
                        $('#keyword_save').prop('disabled', true);
                    },
                    success: function (data) {
                        //   alert(data);
                        $('#keyword_save').val('SAVE');
                        $('#keyword_save').prop('disabled', false);
                        var resArr = data.split('@@@');
                        if (resArr[0] == 1) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        } else if (resArr[0] == 2) {
                            $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            $('#msg').show();
                            $('#keywords_data').html(resArr[2]);
                            $('#input1').val(resArr[3]);
                            //('#keywords').val(0);
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
    });

    $(document).ready(function () {
        $('#add_additionalCaseDetails').on('submit', function () {
            if ($('#add_additionalCaseDetails').valid()) {
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>newcase/additionalCaseDetails/add_additionalCaseDetails",
                    data: form_data,
                    async: false,
                    beforeSend: function () {
                        $('#cd_save').val('Please wait...');
                        $('#cd_save').prop('disabled', true);
                    },
                    success: function (data) {
                        //   alert(data);
                        $('#cd_save').val('SAVE');
                        $('#cd_save').prop('disabled', false);
                        var resArr = data.split('@@@');
                        if (resArr[0] == 1) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        } else if (resArr[0] == 2) {
                            $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            $('#msg').show();
                            // window.location.href = resArr[2];
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
    });

    function delete_keywords(delete_id) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var input1 = $('#input1').val(); 
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('newcase/additionalCaseDetails/add_keywords'); ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,input1 : input1, keywords: delete_id, action : 'delete'},
            success: function (data) {

                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    $('#msg').show();
                    $('#keywords_data').html(resArr[2]);
                    $('#input1').val(resArr[3]);
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
</script>