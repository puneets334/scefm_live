<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 30/5/21
 * Time: 4:35 PM
 */
?>
<div class="right_co" rol="main">
    <div id="page-wrapper">
        <div class="panel-default">
            <?php //echo $this->session->flashdata('msg'); ?>
            <div id="msg" class="form-response" style="display: block;"><?php
                echo $this->session->flashdata('msg');
                /*if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
                    echo $_SESSION['MSG'];
                } unset($_SESSION['MSG']);*/
                ?>
            </div>
            <h4 style="text-align: center;color: #31B0D5"> Case Search For PDF Genrate </h4>
            <div class="panel panel-default">

                <?php
                $attribute = array('class' => 'form-horizontal', 'id' => 'search_case_details_pdf', 'name' => 'search_case_details_pdf', 'autocomplete' => 'off','novalidate'=>'novalidate');
                echo form_open('#', $attribute);
                ?>
                <center>  <br>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <label class="radio-inline input-lg"><input type="radio" checked name="search_filing_type" value="efilingNO"> E-filing No</label>
                        <label class="radio-inline input-lg"><input type="radio"  name="search_filing_type" value="diary" > Diary Number</label>
                        <label class="radio-inline input-lg"><input type="radio" name="search_filing_type" value="register"> Registration No</label>

                    </div>
                </center>
                <br><hr>

                <div class="diary box" style="display:none; ">
                    <div class="col-md-6 col-sm-4 col-xs-12" >
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12 input-lg"> Diary No. <span style="color: red">*</span>:</label>
                            <div class="col-md-9 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input id="diaryno" name="diaryno" maxlength="10"  placeholder="Diary No."  class="form-control input-lg age_calculate" type="text" required>
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Diary number should be digit only.">
                                        <i class="fa fa-question-circle-o"  ></i>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class=" col-md-6 col-sm-6 col-xs-12 ">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12 input-lg">Diary Year <span style="color: red">*</span>:</label>
                            <div class="col-md-9 col-sm-12 col-xs-12">
                                <div class="input-group">
                                   <select tabindex = '25' class="form-control input-sm filter_select_dropdown" id="diary_year" name="diary_year" style="width: 100%">

                                        <?php
                                        $end_year = 48;
                                        for ($i = 0; $i <= $end_year; $i++) {
                                            $year = (int) date("Y") - $i;
                                            $sel = ($year == ((int) date("Y"))) ? 'selected=selected' : '';
                                            echo '<option ' . $sel . ' value=' .$year . '>' . $year . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="register box" style="display:none;background-color:none;">
                    <div class="col-md-4 col-sm-6 col-xs-12" >
                        <div class="form-group">
                            <label class="control-label col-md-4 col-sm-12 col-xs-12 input-lg"> Case Type <span style="color: red">*</span>:</label>
                            <div class="col-md-8 col-sm-12 col-xs-12">
                                <select name="sc_case_type" id="sc_case_type" class="form-control input-lg filter_select_dropdown"  style="width:100%;" required>
                                    <option value="" title="Select">Select Case Type</option>
                                    <?php
                                    if (count($sc_case_type)) {
                                        foreach ($sc_case_type as $dataRes) {
                                            $sel = ($new_case_details[0]['sc_case_type_id'] == (string) $dataRes->casecode ) ? "selected=selected" : '';
                                            ?>
                                            <option <?php echo $sel; ?> value="<?php echo_data(url_encryption(trim($dataRes->casecode))); ?>"><?php echo_data($dataRes->casename); ?> </option>;
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12  "  >
                        <div class="form-group">
                            <label class="control-label col-md-4 col-sm-12 col-xs-12 input-lg"> Case No. <span style="color: red">*</span>:</label>
                            <div class="col-md-8 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <!--<input id="case_number" name="case_number" maxlength="10" onblur="checkNumber(this.id)"   placeholder="Case No."  class="form-control input-lg age_calculate" type="text" required>-->
                                    <input id="case_number" name="case_number" maxlength="10" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"   placeholder="Case No."  class="form-control input-lg age_calculate" type="text" required>
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Related case number should be digits only.">
                                        <i class="fa fa-question-circle-o"  ></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12  " >
                        <div class="form-group">
                            <label class="control-label col-md-4 col-sm-12 col-xs-12 input-lg"> Case Year <span style="color: red">*</span>:</label>
                            <div class="col-md-8 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <select tabindex = '25' class="form-control input-sm filter_select_dropdown" id="case_year" name="case_year" style="width: 100%">

                                        <?php
                                        $end_year = 48;
                                        for ($i = 0; $i <= $end_year; $i++) {
                                            $year = (int) date("Y") - $i;
                                            $sel = ($year == ((int) date("Y"))) ? 'selected=selected' : '';
                                            echo '<option ' . $sel . ' value=' .$year . '>' . $year . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="efilingNO box" style="display: block;background-color: #ffffff;border-color: #ffffff;">
                    <div class="col-md-6 col-sm-4 col-xs-12" >
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12 input-lg"> E-filing No. <span style="color: red">*</span>:</label>
                            <div class="col-md-9 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input id="efiling_no" name="efiling_no" maxlength="20"   placeholder="E-filing No."  class="form-control input-lg age_calculate" type="text" required>
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="E-filing number should be Intered only.">
                                        <i class="fa fa-question-circle-o"  ></i>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12 input-lg"></label>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div class="input-group">
                            <img src="<?php /*echo base_url('captcha/' . $captcha['filename']); */?>" class="form-control captcha-img">
                            <span class="input-group-addon" data-placement="bottom" data-toggle="popover">
                                <div class="input-group-text">
                                    <input type="hidden" id="captchadata" name="captchadata" value="<?php /*echo_data(url_encryption($captcha['word'])); */?>" />
                                    <img src="<?php /*echo base_url('assets/images/refresh.png') */?>" height="20px" width="20px"  alt="refresh" class="refresh_cap" />
                                </div>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div class="input-group">
                            <input type="text" autocomplete="off" name="userCaptcha" id="userCaptcha" value="<?php /*echo $this->session->userdata['captchaWord']; */?>" placeholder="Captcha" maxlength="6" class="inpt form-control input-lg" required="">
                        </div>
                    </div>
                </div>-->

                <div class="form-group">
                    <div class="col-md-offset-5">
                        <input type="submit" class="btn btn-primary" id="search_sc_case" value="SEARCH">
                    </div>
                </div>

                <?php echo form_close(); ?>



            </div>

        </div>
    </div>
</div>
</div>
<script>
    $('input[type="radio"]').click(function () {

        var inputValue = $(this).attr("value");

        if (inputValue == 'register') {
            $('#diaryno').val('');
            $('#diary_year').val('');

        } else if (inputValue == 'diary') {
            $('#sc_case_type').val('');
            $('#case_number').val('');
            $('#case_year').val('');

        } else if (inputValue == 'efilingNO'){
            $('#efiling_no').val('');

        }
        var targetBox = $("." + inputValue);
        $(".box").not(targetBox).hide();
        $(targetBox).show();
    });
</script>
<style>
    .box{
        display: none;
        background-color: none;
    }
    div.box {
        height: 60px;
        padding: 0;
        overflow: unset;
        border:  #fff;
        background-color: #fff;
    }
</style>
<script type="text/javascript">

    $(document).ready(function () {
        $('#search_case_details_pdf').on('submit', function () {
            if ($('#search_case_details_pdf').valid()) {
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('supplements/defaultController/Pdf_search_case_details'); ?>",
                    data: form_data,
                    async: false,
                    beforeSend: function () {
                        $('#search_sc_case').val('Please wait...');
                        $('#search_sc_case').prop('disabled', true);
                    },
                    success: function (data) {

                        /*alert(data);
                        console.log(data);
                        return;*/
                        $('#search_sc_case').val('SEARCH');
                        $('#search_sc_case').prop('disabled', false);
                        var resArr = data.split('@@@');
                        //alert(resArr);
                        if (resArr[0] == 1) {
                            // window.top.location.href = "<//?=base_url('supplements/defaultController/pdfDocHome')?>";
                            window.top.location.href = "<?=base_url('case/ancillary/documents')?>";

                        }else if (resArr[0] == 2) {
                            // window.top.location.href = "<//?=base_url('supplements/defaultController/pdfDocHome')?>";
                            window.top.location.href = "<?=base_url('case/ancillary/documents')?>";

                        }else if (resArr[0] == 4) {
                           // window.top.location.href = "<//?=base_url('supplements/defaultController/pdfDocHome')?>";
                            window.top.location.href = "<?=base_url('case/ancillary/documents')?>";

                        }else if (resArr[0] == 3) {
                             $('#msg').show();
                            //alert("No Record found!!");
                            var msg_data= "No Record found!!";
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + msg_data + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        }
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
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
