<div class="right_col" role="main">
    <div id="page-wrapper">
        <script>
            function openModal() {
                document.getElementById('modal_loader').style.display = 'block';
                document.getElementById('loader_img').style.display = 'block';
                document.getElementById('fade_loader').style.display = 'block';
            }
            function closeModal() {
                document.getElementById('modal_loader').style.display = 'none';
                document.getElementById('fade_loader').style.display = 'none';
            }

        </script>
        <div id="fade_loader"></div>
        <div id="modal_loader">
            <img id="loader_img" style="margin-top: 30px;margin-left: 30px;display:block;" src="<?php echo base_url(); ?>assets/images/loading-data.gif" />
        </div>
        <div class="row">            
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-response" id="msg" >
                    <?php
                    if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
                        echo $_SESSION['MSG'];
                    } unset($_SESSION['MSG']);
                    ?>
                </div>
                <div class="x_panel" id="add_mycases_frm" style="display: block;">                    
                    <div class="x_title">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">                            
                                <h2><i class="fa fa-plus"></i> <strong> Add Cases</strong> </h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <ul class="legend list-unstyled">
                                    <li>
                                        <a href="<?= base_url('mycases/cause_list'); ?>" class="btn btn-info btn-xs"><i class="fa fa-eye" style="font-size:15px"></i> Cause List</a>
                                        <a href="<?= base_url('mycases'); ?>" class="btn btn-primary btn-xs"> <i class="fa fa-plus" style="font-size:15px"></i> Add Case</a>
                                        <a href="<?= base_url('mycases/import_file'); ?>" class="btn btn-warning btn-xs"><i class="fa fa-upload" style="font-size:15px"></i> Import CNR </a>
                                        <a href="<?= base_url('mycases/calendar'); ?>" class="btn btn-default btn-xs"><i class="fa fa-calendar" style="font-size:15px"></i> My Calendar</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-sm-4"> </label>
                            <?php
                            $display = NULL;
                            if (ENABLE_FOR_HC == TRUE && ENABLE_FOR_ESTAB == TRUE) {
                                $display = 'none';
                                ?>
                                <div class="col-sm-7">
                                    <label class="radio-inline"><input type="radio" id="court" name="court_type" value="<?php echo htmlentities(url_encryption(1), ENT_QUOTES); ?>" onchange="displayCourt(this.value)"  maxlength="1" checked="">HIGH COURT</label>
                                    <label class="radio-inline"><input type="radio" id="lowwer_court" name="court_type" value="<?php echo htmlentities(url_encryption(2), ENT_QUOTES); ?>" onchange="displayCourt(this.value)"    maxlength="1">  LOWER COURT</label>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <?php
                        if (ENABLE_FOR_HC == TRUE) {
                            ?>
                            <div id="showHighCourt">
                                <?php
                                $attribute = array('class' => 'form-horizontal form-label-left', 'id' => 'search_mycases_hc', 'name' => 'search_mycases_hc', 'autocomplete' => 'off');
                                echo form_open('#', $attribute);
                                ?>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm" for="state"> High Court <span style="color: red">*</span> :</label>
                                    <div class="col-md-5 col-sm-6 col-xs-12">
                                        <select name="high_court_id" id="high_court_id" class="form-control col-md-7 col-xs-12 input-sm filter_select_dropdown">
                                            <option value="" title="Select">Select</option>
                                            <?php
                                            if (count($high_court_list)) {
                                                foreach ($high_court_list as $dataRes) {
                                                    $value = $dataRes['id'];
                                                    echo '<option  value="' . htmlentities(url_encryption($value), ENT_QUOTES) . '">' . $dataRes['hc_name'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm" for="state"></label>
                                    <div class="col-md-5 col-sm-6 col-xs-12">
                                        <label class="radio-inline"><input type="radio" id="cnr_no_hc" name="search_filing_type_hc" value="<?php echo url_encryption(1); ?>"  checked="checked"  onchange="display_file_in(this.value)"> CNR Number</label>
                                        <label class="radio-inline"><input type="radio" id="filing_no_hc" name="search_filing_type_hc" value="<?php echo url_encryption(2); ?>"   onchange="display_file_in(this.value)">Registration Number</label>
                                    </div>
                                </div>
                                <div class="form-group" id="cnr_no_id" >
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm" for="eshtablishment"> CNR No. <span style="color: red">*</span> :</label>
                                    <div class="col-md-5 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <input type="text" name="cino" id="cino" onkeyup="this.value = this.value.replace(/[^a-zA-Z0-9-]/g, '')" class="form-control col-md-7 col-xs-12 input-sm" placeholder="CNR Number" >
                                            <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Case Number Record should be of 16 characters (eg.MHAU010025162018). You can get CNR Number from https://www.ecourts.gov.in.">
                                                <i class="fa fa-question-circle-o" ></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div id="efiling_type" style="display: none">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm" for="case-type">  Case Type <span style="color: red">*</span> :</label>
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <select name="case_type_id" id="case_type_id" class="form-control col-md-7 col-xs-12 input-sm case_type_list_hc filter_select_dropdown" style="width: 100%">
                                                <option value="" title="Select">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm">Registration Number  <span style="color: red">*</span> :</label>
                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" name="case_number_hc" id="case_number_hc" class="form-control input-sm" placeholder="Registration Number" maxlength="6">

                                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Registration Number may be max. 6 characters length. It may contain only alphabets,digits and hyphens." data-original-title="" title="">
                                                    <i class="fa fa-question-circle-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <label class="control-label col-md-1 col-sm-1 col-xs-12 input-sm">Year <span style="color: red">*</span> :</label>
                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" name="case_year_hc" id="case_year_hc" class="form-control input-sm" placeholder="Year" minlength="4" maxlength="4">
                                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Year must be in 4 digits only." data-original-title="" title="">
                                                    <i class="fa fa-question-circle-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm"></label>
                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <div class="input-group">
                                                <span class="captcha-img"> <?php echo $captcha['image']; ?></span>
                                                <span><img src="<?php echo base_url('assets/images/refresh.png') ?>" height="20px" width="20px"  alt="refresh" class="refresh_cap" /></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <input type="text" autocomplete="off" name="userCaptcha" id="userCaptcha" placeholder="Captcha" maxlength="6" class="inpt" required="">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                                        <input type="submit" id="search_case_hc" name="search_case_hc" value="Search" class="btn btn-primary small">
                                    </div>
                                </div>
                                <?php echo form_close(); ?>

                            </div>

                            <?php
                        }
                        if (ENABLE_FOR_ESTAB == TRUE) {
                            ?>
                            <div id="showLowerCourt" style="display: <?php echo $display; ?>">
                                <?php
                                $attribute = array('class' => 'form-horizontal form-label-left', 'id' => 'search_mycases_dc', 'name' => 'search_mycases_dc', 'autocomplete' => 'off');
                                echo form_open('#', $attribute);
                                ?>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm" for="state"> State <span style="color: red">*</span> :</label>
                                    <div class="col-md-5 col-sm-6 col-xs-12">
                                        <select name="state_id" id="state_id" class="form-control col-md-7 col-xs-12 input-sm filter_select_dropdown" style="width: 100%">
                                            <option value="" title="Select">Select</option>
                                            <?php
                                            if (count($state_list)) {
                                                foreach ($state_list as $dataRes) {
                                                    foreach ($dataRes->state as $state) {
                                                        echo '<option  value="' . htmlentities(url_encryption($state->state_code . '#$' . $state->state_name), ENT_QUOTES) . '">' . $state->state_name . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm" for="District"> District <span style="color: red">*</span> :</label>
                                    <div class="col-md-5 col-sm-6 col-xs-12">
                                        <select name="district_list" id="district_list" class="form-control col-md-7 col-xs-12 input-sm filter_select_dropdown" style="width: 100%">
                                            <option value="" title="Select">Select</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm" for="establishment"> Court Establishment <span style="color: red">*</span> :</label>
                                    <div class="col-md-5 col-sm-6 col-xs-12">
                                        <select name="establishment_list" id="establishment_list" class="form-control col-md-7 col-xs-12 input-sm filter_select_dropdown" style="width: 100%">
                                            <option value="" title="Select">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm" for="state"></label>
                                    <div class="col-md-5 col-sm-6 col-xs-12">
                                        <label class="radio-inline"><input type="radio" id="cnrno_lw" name="search_filing_type_lw" value="<?php echo url_encryption(3); ?>"  checked="checked"  onchange="display_file_in(this.value)"> CNR Number</label>
                                        <label class="radio-inline"><input type="radio" id="filing_no_lw" name="search_filing_type_lw" value="<?php echo url_encryption(4); ?>"   onchange="display_file_in(this.value)">Case Number</label>
                                        <label class="radio-inline"><input type="radio" id="filing_no_lw" name="search_filing_type_lw" value="<?php echo url_encryption(5); ?>"   onchange="display_file_in(this.value)">Case Filing Number</label>
                                    </div>
                                </div>
                                <div class="form-group" id="cnr_no_lw">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm" for="eshtablishment"> CNR No. <span style="color: red">*</span> :</label>
                                    <div class="col-md-5 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <input type="text" name="cino" id="cino" onkeyup="this.value = this.value.replace(/[^a-zA-Z0-9-]/g, '')" class="form-control col-md-7 col-xs-12 input-sm" placeholder="CNR Number">
                                            <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Case Number Record should be of 16 characters (eg.MHAU010025162018).">
                                                <i class="fa fa-question-circle-o" ></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div id="efiling_type_lw" style="display: none">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm" for="case-type">  Case Type <span style="color: red">*</span> :</label>
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <select name="case_type_id" id="case_type_id1" class="form-control col-md-7 col-xs-12 case_type_list input-sm filter_select_dropdown" style="width: 100%">
                                                <option value="" title="Select">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm">Registration Number  <span style="color: red">*</span> :</label>
                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" name="case_number" id="case_number" class="form-control input-sm" placeholder="Registration Number" maxlength="6">
                                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Case Number may be max. 5 characters length. It may contain only alphabets,digits and hyphens." data-original-title="" title="">
                                                    <i class="fa fa-question-circle-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <label class="control-label col-md-1 col-sm-1 col-xs-12 input-sm">Year <span style="color: red">*</span> :</label>
                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" name="case_year" id="case_year" class="form-control input-sm" placeholder="Year" minlength="4" maxlength="4">
                                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Year must be in 4 digits only." data-original-title="" title="">
                                                    <i class="fa fa-question-circle-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="efiling_type_fil_no" style="display: none">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm" for="case-type">  Case Type <span style="color: red">*</span> :</label>
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <select name="case_type_id_fil" id="case_type_id_fil" class="form-control col-md-7 col-xs-12 case_type_list input-sm filter_select_dropdown" style="width: 100%">
                                                <option value="" title="Select">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm">Filing Number  <span style="color: red">*</span> :</label>
                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" name="fil_number" id="fil_number" class="form-control input-sm" placeholder="Filing Number" maxlength="6">
                                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Filing Number may be max. 6 characters length. It may contain only alphabets,digits and hyphens." data-original-title="" title="">
                                                    <i class="fa fa-question-circle-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <label class="control-label col-md-1 col-sm-1 col-xs-12 input-sm">Year <span style="color: red">*</span> :</label>
                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" name="fil_year" id="fil_year" class="form-control input-sm" placeholder="Year" minlength="4" maxlength="4">
                                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Year must be in 4 digits only." data-original-title="" title="">
                                                    <i class="fa fa-question-circle-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm"></label>
                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <div class="input-group">
                                                <span class="captcha-img"> <?php echo $captcha['image']; ?></span>
                                                <span><img src="<?php echo base_url('assets/images/refresh.png') ?>" height="20px" width="20px"  alt="refresh" class="refresh_cap" /></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <input type="text" autocomplete="off" name="userCaptcha" id="userCaptcha" placeholder="Captcha" maxlength="6" class="inpt" required="">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                                        <input type="submit" id="search_case_dc" name="search_case_dc" value="Search" class="btn btn-primary small">
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div id="case_result_filing"></div>
                <div id="case_result"></div>
                <div class="x_panel">
                    <?php include 'added_cnr_list.php'; ?> 
                </div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#state_id').change(function () {

        $('#establishment_list').val('');
        $('#district_list').val('');
        $('#cnrno_lw').prop('checked', true);
        $('#filing_no_lw').prop('checked', false);
        $('#cnr_no_lw').show();
        $('#efiling_type_lw').hide();
        $('#efiling_type_fil_no').hide();
        var get_state_id = $(this).val();

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
            url: "<?php echo base_url('Webservices/get_openAPI_district'); ?>",
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

    $('#district_list').change(function () {
        $('#establishment_list').val('');
        $('#cnrno_lw').prop('checked', true);
        $('#filing_no_lw').prop('checked', false);
        $('#cnr_no_lw').show();
        $('#efiling_type_lw').hide();
        $('#efiling_type_fil_no').hide();
        var get_distt_id = $(this).val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var state_id = $("#state_id option:selected").val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: state_id, get_distt_id: get_distt_id},
            url: "<?php echo base_url('Webservices/get_openAPI_Establishment'); ?>",
            success: function (data)
            {
                if (data.indexOf('ERROR') != -1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + data + " <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    $('#establishment_list').html('<option value=""> Select Establishment </option>');
                } else {
                    $('#msg').hide();
                    $('#establishment_list').html(data);
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
    $('#establishment_list').change(function () {
        $('#cnrno_lw').prop('checked', true);
        $('#filing_no_lw').prop('checked', false);
        $('#cnr_no_lw').show();
        $('#efiling_type_lw').hide();
        $('#efiling_type_fil_no').hide();
    });
    $('#high_court_id').change(function () {
        $('#cnr_no_hc').prop('checked', true);
        $('#filing_no_hc').prop('checked', false);
        $('#cnr_no_id').show();
        $('#efiling_type').hide();
        $('#efiling_type_fil_no').hide();
    });
</script>


<script type="text/javascript">
    function displayCourt(element) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('input[name=document_signed_used]').attr('checked', false);

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, filing_type: element},
            url: "<?php echo base_url('Dropdown_list/filing_type'); ?>",
            success: function (data)
            {
                if (data == '1') {

                    $('#case_type_id1').val('');
                    $('#case_number_hc').val('');
                    $('#case_year_hc').val('');
                    $("#case_result").html('');
                    $('#filing_no').prop('checked', false);
                    $('#cnr_no_hc').prop('checked', true);
                    document.getElementById("showLowerCourt").style.display = "none";
                    document.getElementById("showHighCourt").style.display = "block";
                    document.getElementById("cnr_no_id").style.display = "block";
                    document.getElementById("efiling_type").style.display = "none";


                } else if (data == '2') {
                    $('#cnrno_lw').prop('checked', true);
                    $('#filing_no_lw').prop('checked', false);
                    $('#cino').val('');
                    $("#case_result").html('');
                    $('#cnrno_lw').prop('checked', true);
                    document.getElementById("showLowerCourt").style.display = "block";
                    document.getElementById("cnr_no_lw").style.display = "block";
                    document.getElementById("showHighCourt").style.display = "none";
                    document.getElementById("efiling_type_lw").style.display = "none";
                    document.getElementById("efiling_type_fil_no").style.display = "none";

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
    }



</script>
<script>
    $(document).ready(function () {

        $('[name="efiling_no"],.efiling_no').on('keyup blur', function (e) {
            var number = $(this).val();
            var number = number.replace(/-/gi, "");
            if (e.keyCode != 8) {
                if (number.length == 2) {
                    $(this).val($(this).val() + '-');
                } else if (number.length == 8) {
                    $(this).val($(this).val() + '-');
                } else if (number.length == 13) {
                    $(this).val(number.substr(0, 2) + '-' + number.substr(2, 6) + '-' + number.substr(8, 5) + '-' + number.substr(13, 4));
                } else if (number.length > 16)
                {
                    $(this).val(number.substr(0, 2) + '-' + number.substr(2, 6) + '-' + number.substr(8, 5) + '-' + number.substr(13, 4));
                }
            }
        });

        //-----CASE SEARCH---------//
        $('#search_mycases_dc,#search_mycases_hc').on('submit', function () {
            
            if ($('#search_mycases_dc,#search_mycases_hc').valid()) {
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('#modal_loader').show();
                openModal();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('mycases/search_case_data'); ?>",
                    data: form_data,
                    async: false,
                    success: function (data) {
                        closeModal();

                        $('#modal_loader').hide();
                        var resArr = data.split('@@@');

                        if (resArr[0] == 1) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            setTimeout(function () {
                                $(".form-response").hide();
                            }, 2000);
                            $("#case_result").html('');
                        } else if (resArr[0] == 2) {
                            $("#case_result").show();
                            $("#case_result").html(resArr[1]);
                            $.ajax({
                                url: '<?= base_url('captcha_refresh') ?>',
                                success: function (data) {
                                    $('.captcha-img').replaceWith('<span class="captcha-img">' + data + '</span>');
                                    $('.inpt').val('');
                                }
                            });
                        } else if (resArr[0] == 3) {
                            $("#case_result_filing").html(resArr[1]);

                            $.ajax({
                                url: '<?= base_url('captcha_refresh') ?>',
                                success: function (data) {
                                    $('.captcha-img').replaceWith('<span class="captcha-img">' + data + '</span>');
                                    $('.inpt').val('');
                                }
                            });
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

        $('#show_add_form').on('click', function (event) {
            $('.filter_select_dropdown').select2();
            $('#add_mycases_frm').toggle();
            $('.filter_select_dropdown').select2();
        });
    });
    function display_file_in(element) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, filing_type: element},
            url: "<?php echo base_url('Dropdown_list/filing_type'); ?>",
            success: function (data)
            {

                if (data == '1') {
                    document.getElementById("cnr_no_id").style.display = "block";
                    document.getElementById("efiling_type").style.display = "none";
                    $("#case_result").show();
                    $("#case_result_filing").hide();
                    $('#case_type_id').val('');
                    $('#case_number_hc').val('');
                    $('#case_year_hc').val('');
                } else if (data == '2') {

                    document.getElementById("efiling_type").style.display = "block";
                    document.getElementById("cnr_no_id").style.display = "none";
                    $('#cino').val('');
                    $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        getCaseTypeList('HC');
                    });

                }
                if (data == '3') {

                    document.getElementById("cnr_no_lw").style.display = "block";
                    document.getElementById("efiling_type_lw").style.display = "none";
                    document.getElementById("efiling_type_fil_no").style.display = "none";
                    $('#case_number').val('');
                    $('#case_year').val('');
                } else if (data == '4') {
                    $("#case_result").hide();
                    document.getElementById("efiling_type_lw").style.display = "block";
                    document.getElementById("cnr_no_lw").style.display = "none";
                    document.getElementById("efiling_type_fil_no").style.display = "none";
                    $("input[name=cino]").val('');
                    $("input[name=fil_number]").val('');
                    $("input[name=fil_year]").val('');
                    $("input[name=case_type_id2]").val('');
                    $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        getCaseTypeList('DC');
                    });

                } else if (data == '5') {

                    document.getElementById("efiling_type_lw").style.display = "none";
                    document.getElementById("cnr_no_lw").style.display = "none";
                    document.getElementById("efiling_type_fil_no").style.display = "block";
                    $("#case_result").hide();
                    $("input[name=cino]").val('');
                    $("input[name=case_number]").val('');
                    $("input[name=case_year]").val('');
                    $("input[name=case_type_id]").val('');

                    $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        getCaseTypeList('DC');
                    });
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
    }

    function getCaseTypeList(efiling_for_type) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var court_type = $('[name="court_type"]:checked').val();
        if (efiling_for_type == 'HC') {
            var estab_id = $('#high_court_id').val();
            var case_url = "<?php echo base_url('Webservices/case_type_list'); ?>";
        } else if (efiling_for_type == 'DC') {
            estab_id = $('#establishment_list').val();
            var case_url = "<?php echo base_url('Webservices/OpenAPIcase_type_list'); ?>";
        }
        openModal();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, est_code: estab_id, court_type: court_type},
            url: case_url,
            success: function (data)
            {
                closeModal();
                if (efiling_for_type == 'HC') {
                    $('.case_type_list_hc').html(data);
                } else {
                    $('.case_type_list').html(data);
                }
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });

            },
            error: function () {
                closeModal();
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
        setTimeout(function () {
            closeModal();
        }, 20000);

    }

    function save_search_data(cnr_detail) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Mycases/add_case'); ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, cnr_detail: cnr_detail},
            beforeSend: function () {
                $('.add_loader').append(' <i class="status_refresh fa fa-refresh fa-spin"></i>');
            },
            success: function (data) {
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg').show();
                    $('.status_refresh').remove();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    setTimeout(function () {
                        $('#msg').hide();
                    }, 3000);
                } else if (resArr[0] == 2) {
                    $('#msg').show();
                    $('.status_refresh').remove();
                    $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    setTimeout(function () {
                       window.location.href = "<?php echo base_url('mycases/mycases_appearing_for'); ?>";
                    }, 1000);
                }
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });

            },
            error: function (result) {
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }

    function get_cnr_data(id) {
        var cnr_no = $('#cnr_number_data_' + id).val();
        var state_code = $('#cnr_state').val();
        var dist_code = $('#cnr_dist').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Mycases/get_cnr_data'); ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, cnr_no: cnr_no, state_code: state_code, dist_code: dist_code},

            success: function (data) {
                $("#case_result").show();
                var resArr = data.split('@@@');
                if (resArr[0] == 2) {

                    $("#case_result").html(resArr[1]);
                    $.ajax({
                        url: '<?= base_url('captcha_refresh') ?>',
                        success: function (data) {
                            $('.captcha-img').replaceWith('<span class="captcha-img">' + data + '</span>');
                            $('.inpt').val('');
                        }
                    });
                }
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });

            },
            error: function (result) {
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }

</script>