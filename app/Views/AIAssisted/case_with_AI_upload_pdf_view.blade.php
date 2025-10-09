@extends('layout.advocateApp')
@section('content')
<style>
    .input-group-addon {
        padding: 0px!important;
    }
    .select2-container {
        width: 100% !important;
    }
    /*added by anshu 24 jul 2023*/
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }
    input:checked + .slider {
        background-color: #2196F3;
    }
    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }
    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }
    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }
    .slider.round:before {
        border-radius: 50%;
    }
</style>
<div class="container-fluid">
    <div class="row card">
        <div class="col-lg-12">
            <div class="card-body">
                <div class="dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-response" id="msg" style="background-color: green; color: #fff; text-align: center;"></div>
                            <div class="col-md-12 col-sm-12 col-xs-12" id="loader_div"></div>
                            <div class="dash-card">
                                <div class="title-sec">
                                    <h5 class="unerline-title">Upload Main Petitions for AI Assisted Case Filing</h5>
                                    @if(!empty(getSessionData('login')))
                                        <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                    @endif
                                </div>
                                {{-- Page Title End --}}
                                <div class="form-response mt-2"
                                    role="alert"
                                    data-auto-dismiss="5000">
                                    <?php
                                    if (!empty(session()->getFlashdata('msg'))) {
                                        echo session()->getFlashdata('msg');
                                    }
                                    if (!empty(session()->getFlashdata('err'))) {
                                        echo session()->getFlashdata('err');
                                    }
                                    ?>
                                </div>
                                {{-- Main Start --}}
                                <div class="uk-margin-small-top uk-border-rounded">
                                    <?php
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'add_case_details', 'id' => 'add_case_details', 'autocomplete' => 'off' , 'enctype' => 'multipart/form-data');
                                    echo form_open(base_url('AIAssisted/CaseWithAI/is_upload_pdf'), $attribute);
                                    // echo form_open('#', $attribute);
                                    ?>
                                        <!--start Upload Duc required="required" (Browse PDF)-->
                                        <label class="control-label col-md-6 col-sm-offset-1 col-sm-8 col-xs-12 input-sm"># This service is currently active for Special Leave Petition (Civil and Criminal) case types only.</label>
                                        <?= ASTERISK_RED_MANDATORY; ?>
                                        <h5 style="text-align: center;"><b>Upload Single PDF for a case</b> </h5>
                                        <div id="newCaseSaveForm-Stop">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-3">
                                                        <label class="form-label"> Case Type <span style="color: red">*</span>:</label>
                                                        <select tabindex="1" name="sc_case_type" id="sc_case_type" class="form-control cus-form-ctrl input-sm filter_select_dropdown sc_case_type" required>
                                                            <option value="" title="Select">Select Case Type</option>
                                                            <?php
                                                            if (!empty($data['sc_case_type'])) {
                                                                foreach ($data['sc_case_type'] as $dataRes) {
                                                                    ?>
                                                                    <option  value="<?php echo_data(url_encryption(trim($dataRes->casecode))); ?>"><?php echo_data($dataRes->casename); ?> </option>;
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-3">
                                                        <label class="form-label">Title <span style="color: red">*</span> :</label>
                                                        <div class="input-group cust-inline-back">
                                                            <input type="text" class="form-control cus-form-ctrl input-sm" tabindex="2" name="doc_title" id="doc_title" required="" placeholder="PDF Title" minlength="3" maxlength="75">
                                                            <span class="input-group-text input-group-addon" data-placement="bottom" data-toggle="popover" title=" PDF title max. length can be 75 characters only.  Only numbers, letters, spaces, hyphens,dots and underscores are allowed." data-original-title="" title="">
                                                                <i class="fa fa-question-circle-o"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <label class="form-label">Browse PDF <span style="color: red">*</span> :</label>
                                                    <input class="form-control cus-form-ctrl" tabindex="3" name="pdfDocFile" id="browser" tabindex="3" required="required" type="file">
                                                </div>
                                            </div>
                                            <!-- right box end -->
                                            <div style="text-align: center;">
                                                <button type="submit" class="quick-btn mt-3" id="pet_save" >UPLOAD</button>
                                                <button type="button" class=" quick-btn gray-btn mt-3" name="back" id="back" value="back" onclick="history.back()" >Back</button>
                                            </div>
                                            <div class="row">
                                                <div class="progress" style="display: none;">
                                                    <div class="progress-bar progress-bar-success myprogress" role="progressbar"  value="0" max="100" style="width:0%">0%</div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--#newCaseSaveForm-->
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        </div>
                        <br><br>
                        <hr />
                        <?php
                        if (!empty($data['uploaded_docs_IITM'])) {
                            echo '<script>$("#nextButton").show();</script>';
                            render('AIAssisted.uploaded_doc_list_IITM', @compact('data'));
                        } else{
                            echo '<script>$("#nextButton").hide();</script>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- Button to trigger tab close -->
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<script>
    $(document).ready(function() {
        $('#add_case_details').on('submit', function() {
            // window.open(targetUrl, '_blank');
            // window.open(targetUrl, '_blank');
            // window.close();
            $("#browser").prop('required', true);
            var myFile="";
            $('#newCaseSaveForm').hide();
            // $('#pet_save').hide();
            $('#browser').on('change',function(e){
                $("#loader_div").html('');
                myFile = $("#browser").val();
                var upld = myFile.split('.').pop();
                if(upld=='pdf'){
                    var draft_petition_file_browser = $.trim($("#browser").val());
                    if (draft_petition_file_browser.length == 0) {
                        alert('Upload document draft petition is required*');
                        $('#draft_petition_file_browser').append('Upload document draft petition is required*');
                        // swal("Cancelled", "", "error");
                        // $("#captchacode").focus();
                        return false;
                    }
                    var sc_case_type =$('[name="sc_case_type"]').val();
                    var doc_title =$('[name="doc_title"]').val();
                    if (sc_case_type.length == 0) {
                        alert('Case type is required*');
                        return false;
                    }
                    if (doc_title.length == 0) {
                        alert('Upload document title is required*');
                        return false;
                    }
                    $('#pet_save').hide();
                    // alert("File uploaded is pdf");
                    var fileName = e.target.files[0].name;
                    /*var file_data     = $("#browser").prop("files")[0];
                    alert(fileName + ' is the selected file.');
                    form_data.append("pdfDocFile", file_data);*/
                    var  file_data=  updateUploadFileName('browser');
                    if(file_data){
                        $('.progress').show();
                        var CSRF_TOKEN = 'CSRF_TOKEN';
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                        var fileName      = $("#browser").prop("files")[0].name;
                        var filedata      = $("#browser").prop("files")[0];
                        var formdata      = new FormData();
                        formdata.append("pdfDocFile", filedata);
                        // formdata.append("doc_title", 'DRAFT PETITION');
                        formdata.append("CSRF_TOKEN", CSRF_TOKEN_VALUE);
                        formdata.append("doc_title", $('[name="doc_title"]').val());
                        formdata.append("sc_case_type", sc_case_type);
                        // alert('The file "' + doc_title +  '" has been selected.');
                        $.ajax({
                            // dataType: 'script',
                            type: 'POST',
                            url: "<?php echo base_url('AIAssisted/CaseWithAI/is_upload_pdf'); ?>",
                            data: formdata,
                            contentType: false,
                            processData: false,
                            /*xhr: function () {
                                var xhr = new window.XMLHttpRequest();
                                xhr.upload.addEventListener("progress", function (evt) {
                                    if (evt.lengthComputable) {
                                        var percentComplete = evt.loaded / evt.total;
                                        percentComplete = parseInt(percentComplete * 100);
                                        $('.myprogress').html(percentComplete + '%');
                                        $('.myprogress').css('width', percentComplete + '%');
                                    }
                                }, false);
                                return xhr;
                            },*/
                            beforeSend: function () {
                                $("#loader_div").html('<img id="loader_img" style="margin-left: 45% !important;" src="<?php echo base_url('assets/images/loading-data.gif');?>">');
                                // $('#lower_court_save').prop('disabled', true);
                            },
                            success: function (data) {
                                $("#loader_div").html('');
                                $('.progress').hide();
                                // $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                                //     $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                //     // alert(result.CSRF_TOKEN_VALUE + '=='+ CSRF_TOKEN_VALUE);
                                // });
                                // $("#upload_doc").prop("disabled", true);
                                var resArr = data.split('@@@');
                                // alert(resArr[0]); exit;
                                if (resArr[0] == 1) {
                                    $('#msg').show();
                                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                    // $('.msg').show();
                                    // $("#result").html("<p class='message error' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                                    location.reload();
                                } else if (resArr[0] == 2) {
                                    $('#pet_save').show();
                                    $('#newCaseSaveForm').show();
                                    $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                    $('#msg').show();
                                    // $('.msg').show();
                                    // $("#result").html("<p class='message success' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                                    location.reload();
                                    /*var targetUrl = "<//?php echo base_url('case/crud'); ?>";
                                    window.parent.location.href=targetUrl;*/
                                    /*window.location.href="<//?php echo base_url('newcase/caseDetails')?>";*/
                                } else if (resArr[0] == 3) {
                                    $('#msg').show();
                                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                    // $('.msg').show();
                                    // $("#result").html("<p class='message error' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                                } else if (resArr[0] == 4) {
                                    $('#msg').show();
                                    $('.progress').hide();
                                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                    // $('.msg').show();
                                    // $("#result").html("<p class='message error' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                                }
                                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                });
                            },
                            error: function (e) {
                                $("#upload").prop("disabled", false);
                            }
                        });
                    }
                } else{
                    $('#pet_save').hide();
                    $('#newCaseSaveForm').hide();
                    alert("Only PDF are allowed");
                    // location.reload();
                }
            });
        });
    });
</script>