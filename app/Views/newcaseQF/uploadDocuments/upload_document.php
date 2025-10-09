<div class="panel panel-body panel-default">
    <h4 style="text-align: center;color: #31B0D5">Upload Document(s) </h4>
    <h5 style="text-align: center;"><b>All documents, Interlocutory Applications, if any, other than main petition are to be uploaded using this feature.</b> </h5>
    <?php
    $attribute = array('class' => 'form-horizontal', 'name' => 'uploadDocument', 'id' => 'uploadDocument', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
    echo form_open('#', $attribute);
    if($_SESSION['login']['ref_m_usertype_id']==JAIL_SUPERINTENDENT)
    {
    ?>
        <br/>
        <br/>
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">

                    <center> <label class="col-lg-9 col-md-9 col-sm-9 col-xs-9"><font color="red">Proforma for Custody and Surrender Certificate.Download,fill and upload.</font></label></center>
                    <a href="<?=base_url('uploadDocuments/DefaultController/file_download/S')?>"> Download Surrender Certificate</a>
                    <a href="<?=base_url('uploadDocuments/DefaultController/file_download/C')?>"> Download Custody Certificate</a>

            </div>
        </div>
        <br/>
        <br/>
<?php }
    ?>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
            <div class="row">
                <div class="form-group">
                    <label class="col-lg-2 col-md-2 col-sm-3 col-xs-12">Title <span style="color: red">*</span> :</label>
                    <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 input-group">                    
                        <input type="text" class="form-control input-sm" tabindex="2" name="doc_title" id="doc_title" required="" placeholder="PDF Title" minlength="3" maxlength="75">
                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content=" PDF title max. length can be 75 characters only.  Only numbers, letters, spaces, hyphens,dots and underscores are allowed." data-original-title="" title="">
                            <i class="fa fa-question-circle-o"></i>
                        </span>                    
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label class="col-lg-2 col-md-2 col-sm-3 col-xs-12">Browse PDF <span style="color: red">*</span> :</label>
                    <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12" id="pdf_error">
                        <input name="pdfDocFile" id="browser" tabindex="3" required="required" type="file">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

        </div>
    </div>
    <div class="row">
        
        <div class="text-center">
            <?php
            if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
                $prev_url = base_url('newcaseQF/caseDetails');
                $next_url = base_url('newcaseQF/courtFee');
            } /*elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
                $prev_url = base_url('on_behalf_of');
                $next_url = base_url('documentIndex');
            }elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
                $prev_url = base_url('on_behalf_of');
                $next_url = base_url('documentIndex');
            } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MENTIONING) {
                $prev_url = base_url('on_behalf_of');
                $next_url = base_url('mentioning/courtFee');
            }elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_JAIL_PETITION) {
                $prev_url = base_url('jailPetition/Subordinate_court');
                $next_url = base_url('affirmation');
            }
            else if(!empty($_SESSION['efiling_details']['ref_m_efiled_type_id']) && $_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT){
                $prev_url = base_url('caveat/subordinate_court');
                $next_url = base_url('documentIndex');
            }*/
            else {
                $prev_url = '#';
                $next_url = '#';
            }
            ?>
            <!--<a href="<?/*= $prev_url */?>" class="btn btn-primary btnPrevious" type="button">Previous</a>-->
            <input type="submit" class="btn btn-success" id="upload_doc" value="UPLOAD">
            <a href="<?= $next_url ?>" class="btn btn-primary btnNext" type="button" id="nextButton">Next</a>

        </div>
    </div>
    <div class="row">
        <div class="progress" style="display: none">
            <div class="progress-bar progress-bar-success myprogress" role="progressbar"  value="0" max="100" style="width:0%">0%</div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<?php
if (!empty($uploaded_docs)) {
    echo '<script>$("#nextButton").show();</script>';
    $this->load->view('newcaseQF/uploadDocuments/uploaded_doc_list');
}
?>
<?php
    $this->load->view('newcaseQF/documentIndex/documentIndex_view');
?>
<script>
    $('#uploadDocument').on('submit', function () {
        var caveateDocNum = $("#caveateDocNum").attr('data-caveatedocnum');
        if(caveateDocNum && caveateDocNum == '1'){
            alert("Caveat document already uploaded.");
            return false;
        }
        else{
            if ($('#uploadDocument').valid()) {
                 var doc_title = $("#doc_title").val();
                var  file_data=  updateUploadFileName('browser');
                if(file_data){
                    $('.progress').show();
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    var fileName      = $("#browser").prop("files")[0].name;
                    var filedata      = $("#browser").prop("files")[0];
                    var formdata      = new FormData();
                    formdata.append("pdfDocFile", filedata);
                    formdata.append("doc_title", doc_title);
                    formdata.append("CSRF_TOKEN", CSRF_TOKEN_VALUE);
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo base_url('newcaseQF/uploadDocuments/DefaultController/upload_pdf'); ?>",
                        data: formdata,
                        contentType: false,
                        processData: false,
                        xhr: function () {
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
                        },
                        success: function (data) {
                            $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                            });
                            //$("#upload_doc").prop("disabled", true);
                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                $('#msg').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            } else if (resArr[0] == 2) {
                                $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                $('#msg').show();
                                location.reload();
                            } else if (resArr[0] == 3) {
                                $('#msg').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            } else if (resArr[0] == 4) {
                                $('#msg').show();
                                $('.progress').hide();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
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
                return false;
            } else {
                return false;
            }
        }

    });
</script>