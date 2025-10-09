<div class="row">
    <div class="panel panel-default panel-body">
        <h5 style="text-align: left;"><b>All documents, Interlocutory Applications, if any, other than main petition are to be uploaded which are required for Court Fee calculation.</b> </h5>

        <div class="col-sm-5 col-xs-12">
            <?php
            if(isset($index_details[0]['doc_type_id']) && $index_details[0]['doc_type_id']!=''){
                $editDocType_id=$index_details[0]['doc_type_id'];
            }
            if(isset($index_details[0]['sub_doc_type_id']) && $index_details[0]['sub_doc_type_id']!=''){
                $pet_appellant=$index_details[0]['sub_doc_type_id'];
            }

            $attribute = array('class' => 'form-horizontal form-label-left', 'id' => 'create_index_form', 'name' => 'create_index_form', 'autocomplete' => 'off');
            echo form_open('#', $attribute);
            $dis=(!empty($index_details[0]['pdf_id']))? "disabled":'';
            ?>
            <div class="form-group main_div_show">
                <label class="control-label col-sm-3 input-sm"> PDF File  <!--<span style="color: red">*</span>--> :</label>
                <div class="col-sm-9">
                    <select id="pdfs_list" name="pdfs_list"  class="form-control input-sm filter_select_dropdown" style="width: 100%" <?php echo $dis;?>>
                        <option value="" title="Select">Select PDF File </option>
                        <?php

                        foreach ($uploaded_pdf as $pdf) {
                            $sel = ($index_details[0]['pdf_id'] == $pdf['doc_id']) ? "selected=selected" : '';
                            echo '<option '.$sel.' data-document_id="'.$pdf['doc_id'].'" data-document_path="'.$pdf['doc_title'].'" value="' . htmlentities(url_encryption($pdf['doc_id']), ENT_QUOTES) . '">' . htmlentities(strtoupper($pdf['doc_title']), ENT_QUOTES) . '</option>';
                        }
                        ?>
                    </select>

                    <span id="doc_upload_error_message" class="text-danger"></span>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="form-group main_div_show">
                <label class="control-label col-sm-3 input-sm"> Index Item <span style="color: red">*</span> :</label>
                <div class="col-sm-9">

                    <select id="doc_type" name="doc_type"  class="form-control input-sm filter_select_dropdown" style="width: 100%" onchange="getindxmodelcopies()">
                        <option value="" title="Select">Select Index Item</option>
                        <?php
                        foreach ($doc_type as $docs) {
                            $sel=($index_details[0]['doc_type_id'] == $docs['doccode'] ) ? "selected=selected" : '';
                            if ($docs['doccode'] == 8 && $docs['doccode1'] == 0) {
                                echo '<option '.$sel.'  value="' . htmlentities(url_encryption($docs['doccode']), ENT_QUOTES) . '">' . htmlentities(strtoupper($docs['docdesc']), ENT_QUOTES) . '</option>';
                            } else {
                                echo '<option '.$sel.' value="' . htmlentities(url_encryption($docs['doccode']), ENT_QUOTES) . '">' . htmlentities(strtoupper($docs['docdesc']), ENT_QUOTES) . ' (<span> Court Fee : ' . htmlentities(strtoupper($docs['docfee']), ENT_QUOTES) . '</span>)</option>';

                            }
                        }
                        ?>
                    </select>
                    <span id="section_error_message" class="text-danger"></span>
                </div>
            </div>

            <!--//XXXXXXXXXXXXXXXXXXXXXXXXXX-->

            <div class="form-group" id="indx_copies">
                <label class="control-label col-sm-3 input-sm">  No of Copies <span style="color: red">*</span> :</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="text" id="no_copies" name="no_copies"  value="<?php echo_data($index_details[0]['no_of_affidavit_copies']); ?>"  onkeyup="return isNumber(event)" placeholder="No of Copies" class="form-control input-sm" >

                        <span class="input-group-addon">
                            <i class="fa fa-question-circle-o" data-placement="bottom" data-toggle="popover" data-content="Title should be in numeric (Only Number are allowed)."></i>
                        </span>
                    </div>
                </div>
            </div>


            <!--//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX-->

            <div class="form-group main_div_show" id="subItemData">
                <label class="control-label col-sm-3 input-sm"> Index Sub Item :</label>
                <div class="col-sm-9">
                    <select id="sub_doc_type" name="sub_doc_type"  class="form-control input-sm filter_select_dropdown" style="width: 100%" onchange="getpetitionersappellantmodals()">
                        <option value="" title="Select">Select Index Sub Item</option>
                        <?php
                        if (($doc_res)) {
                            foreach ($doc_res as $dataRes) {
                                $sel=($index_details[0]['sub_doc_type_id'] == $dataRes['doccode1'] ) ? "selected=selected" : '';
                                echo '<option '.$sel.'  value="' . htmlentities(url_encryption($dataRes['doccode1']), ENT_QUOTES) . '">' . htmlentities(strtoupper($dataRes['docdesc']), ENT_QUOTES) . ' (<span> Court Fee : ' . htmlentities(strtoupper($dataRes['docfee']), ENT_QUOTES) . '</span>)</option>';
                            }
                        }
                        ?>

                    </select>
                    <span id="section_error_message" class="text-danger"></span>
                </div>
            </div>
            <!--//XXXXXXXXXXXXXXXXXXXXXXXXXX-->

            <div class="form-group" id="petitioners_appellant">
                <label class="control-label col-sm-6 input-sm">  No of Petitioners / appellant (Non-party) <span style="color: red">*</span> :</label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input type="text" id="no_of_petitioner_appellant" name="no_of_petitioner_appellant"  value="<?php echo_data($index_details[0]['no_of_petitioner_appellant']); ?>"  onkeyup="return isNumber(event)" placeholder="No of Petitioners/Appellant" class="form-control input-sm" >

                        <span class="input-group-addon">
                            <i class="fa fa-question-circle-o" data-placement="bottom" data-toggle="popover" data-content="Title should be in numeric (Only Number are allowed)."></i>
                        </span>
                    </div>
                </div>
            </div>


            <!--//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX-->
            <div class="form-group main_div_show">
                <span id="sub_doc_type_load"></span>
            </div>
           <!-- <div class="form-group">
                <label class="control-label col-sm-3 input-sm"> Index Title  <span style="color: red">*</span> :</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="text" id="doc_title" name="doc_title"  value="<?php /*echo_data($index_details[0]['doc_title']); */?>" onkeyup="this.value.replace(/[^0-9A-Za-z-.() ]/g, '')" minlength="2" maxlength="150" placeholder="Document Title" class="form-control input-sm">
                        <input type="hidden" id="doc_title_fixed_onetime"  value="<?php /*echo_data($index_details[0]['doc_title']); */?>" placeholder="Document Title" class="form-control input-sm">
                        <span class="input-group-addon">
                            <i class="fa fa-question-circle-o" data-placement="bottom" data-toggle="popover" data-content="Title should be in alphanumeric (Only - . () and space are allowed)."></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3 input-sm">From Page No. <span style="color: red">*</span> :</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="text" id="page_no_from" name="page_no_from"   value="<?php /*echo_data($index_details[0]['st_page']); */?>"   minlength="1" maxlength="3" placeholder="From" class="form-control input-sm page_no_from">
                        <span class="input-group-addon">
                            <i class="fa fa-question-circle-o" data-placement="bottom" data-toggle="popover" data-content="Only digits are allowed."></i>
                        </span>
                    </div>
                    <span id="from_page_error_message " class="text-danger"></span>
                </div>
                <label class="control-label col-sm-3 input-sm"> To Page No. <span style="color: red">*</span> :</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="text" id="page_no_to" name="page_no_to"  value="<?php /*echo_data($index_details[0]['end_page']); */?>"  minlength="1" maxlength="3" placeholder="To" class="form-control input-sm">
                        <span class="input-group-addon">
                            <i class="fa fa-question-circle-o" data-placement="bottom" data-toggle="popover" data-content="Only digits are allowed."></i>
                        </span>
                    </div>
                </div>
            </div>-->
            
            <div class="clearfix"></div>                               
            <div class="form-group">

                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-6">
                    <?php if(!empty($document_id)){?>
                    <input type="hidden" name="pdfs_list" value="<?php echo htmlentities(url_encryption($index_details[0]['pdf_id']), ENT_QUOTES);?>">
                    <button type="submit" class="btn btn-success" id="save_docs" name="save_docs"> UPDATE
                    <?php } else {?>
                    <button type="submit" class="btn btn-success" id="save_docs" name="save_docs"> ADD
                        <?php } ?>
                        <i class="fa fa-spinner fa-spin save_spinner" style="font-size:15px;display: none;"></i></button>
                    <button class="btn btn-primary" type="reset" id="reset_frm">RESET</button>
                </div>
            </div>
            <?php echo form_close(); ?>
            <div class="panel panel-default">
                <div class="panel-body">        
                    <div id ="index_data">             
                       <?php // INDEX LIST BEING POPULATED USING AJAX HERE  JAVASCRIPT FUNCTION reload_document_index  -- AJAXCALLS load_document_index METHOD  ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-7 col-xs-12">
            <div id="petition-content-viewer-section" style="height: 800px;width: 90%;"></div>

            <!--<div class="x_panel">
                <div class="x_content">
                    <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="pdf-tab" data-toggle="tab" href="#current_pdf_show" role="tab" aria-controls="pdf" aria-selected="false">PDF </a></li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade active" id="current_pdf_show" role="tabpanel" aria-labelledby="pdf-tab">

                            <div id="view_docs" style="display:none;">
                                <div class="">
                                    <span id="display_uploaded_document"></span>
                                    <span id="page_completed" class="text-danger blink"></span>
                                    <span id="uploaded_pdf_total_page"></span>
                                    <span id="uploaded_pdf_page"></span>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">

                                <iframe id="show_current_pdf" name="show_current_pdf" class="iframe" style="height: 600px;width: 650px;"></iframe>
                                <div>



                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>-->
        </div>
    </div>
    <div class="text-center">
        <?php
            if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
                $prev_url = base_url('newcaseQF/caseDetails');
                $next_url = base_url('newcaseQF/courtFee');
            }
            /* elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
                $next_url = base_url('miscellaneous_docs/courtFee');
            }elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {                
                $next_url = base_url('IA/courtFee');
            }
           else if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
                  $next_url = base_url('newcase/courtFee');
             }*/
            else {
                $next_url = '#';
            }
            ?>
        <a href="<?= base_url('newcaseQF/caseDetails'); ?>" class="btn btn-primary btnPrevious" type="button">Previous</a>
       <!-- <a onclick="<?php /*echo $next_url; */?>" id="nextButton" style="display: none;" class="btn btn-primary btnNext" type="button">Next</a>-->
        <a href="<?= $next_url ?>" <?= $next_url; ?> class="btn btn-primary" tabindex = '27' type="button">Next</a>



    </div>
</div>


<div id="pdf-content-viewer" style="height: 100%"></div>


<script type="text/javascript">

/*Changes added on 11 September 2020 as a part of modification*/
    function index_title()
    {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var doc_type_code = $('#doc_type').val();
        /***Changes start**/
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, doc_type_code: doc_type_code},
            url: "<?php echo base_url('documentIndex/Ajaxcalls/get_index_type'); ?>",
            success: function (data)
            {
                if(data)
                {
                    var doc_type_json = '<?php echo json_encode($doc_type);?>';
                    var doc_type = JSON.parse(doc_type_json);
                    var len = doc_type.length;
                    var i=0;
                    for(i=0;i<len; i++)
                    {
                        if(parseInt(data) === parseInt(doc_type[i]['doccode']))
                        {
                            $('#doc_title').val((doc_type[i]['docdesc']).replace(/\//g,' '));
                            $('#doc_title_fixed_onetime').val((doc_type[i]['docdesc']).replace(/\//g,' '));
                        }
                    }

                }
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
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

  /*End of changes*/

    $(document).ready(function(){

        reload_document_index();
        var editmode_docType = '<?php echo $editDocType_id ; ?>';
        if(editmode_docType==11){
            $('#indx_copies').show();
        }else{
            $('#indx_copies').hide();
        }

        var editmpde_pet_appellant = '<?php echo $pet_appellant ; ?>';
        if(editmpde_pet_appellant==95){
            $('#petitioners_appellant').show();
        }else{
            $('#petitioners_appellant').hide();
        }

        $('#pdfs_list').on('change', function() {
            var doc_id=(this.value );
            if(doc_id =='')
            {
                $("#petition-content-viewer-section").html("");
            }
            else
            {
                var pdf1="<?php echo base_url('uploadDocuments/viewDocument/'); ?>"+doc_id;
                var title=$(this).data('document_title');
                $("#display_pdf_title").html(title);
                $("#petition-content-viewer-section").html('<embed  src="'+pdf1+'" frameborder="0" width="90%" height="100%">');
            }
        });
    });

    $('#create_index_form').on('submit', function () {
        var caveatDocNum = $("#caveatDocNum").attr('data-caveatdocnum');
        if(caveatDocNum && caveatDocNum == '2'){
            alert('Only two index file allowed.');
            return false;
        }
        else{
            var db_pdf_id='<?php echo $index_details[0]['pdf_id'];?>';
            if ($('#create_index_form').valid()) {
                var form_submit = $('#form_submit').val();
                if (form_submit == 'false') {
                    return false;
                }
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.save_spinner').show();
                $('#save_doc').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('documentIndex/DefaultController/add_index_item/'. $this->uri->segment(4)); ?>",
                    data: $(this).serialize()+'&caveatDocNum='+caveatDocNum,
                    async: false,
                    success: function (data)
                    {
                        /*alert(data);
                        console.log(data);
                        return;*/
                        index_title();  //change added on 11 September 2020
                        $('.save_spinner').hide();
                        $('#save_doc').prop('disabled', false);
                        var resArr = data.split('@@@');
                        if (resArr[0] == 1) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            setTimeout(function () {
                                $('#msg').hide();
                            }, 3000);
                            window.location.href = "#";

                        } else if (resArr[0] == 2) {

                            $('#msg').show();
                            $('#page_no_from,#page_no_to').val('');
                            $('#view_docs').show();
                            if (resArr[1] == 'all_page_done') {
                                $('#save_doc').hide();
                                $('#page_completed').html('<p>All page are completed.Choose another document.</p>');
                            } else {
                                $('#page_no_from,#uploaded_pdf_page').val(resArr[1]);
                                $('#doc_title,#filing_date').val('');
                            }

                            //--START : Load pdf on Last Page--//
                            /* Commented by Preeti Agrawal on 9.12.2020 to solve problem of reloading on add since show_current_pdf is not in use now
                            var frame_src = document.getElementById('show_current_pdf').src;
                            var pdf_url = frame_src.split('#');
                            var frame_src_refresh = pdf_url[0] + '#page=' + resArr[1];
                            $("#show_current_pdf").attr("src", frame_src_refresh);*/
                            //--END : Load pdf on Last Page--//


                            $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[2] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                reload_document_index();
                                if(!isEmpty(db_pdf_id)){
                                    window.location.href="<?php echo base_url('documentIndex');?>";}
                                else{
                                    $('#page_no_from,#page_no_to').val('');
                                    $('#doc_title').val('');
                                    $("#doc_type").val('').trigger('change');
                                    $("#sub_doc_type").val('').trigger('change');
                                }
                            });
                            setTimeout(function () {
                                $('#msg').hide();
                            }, 3000);
                            window.location.href = "#";
                        } else if (resArr[0] == 3) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            setTimeout(function () {
                                $('#msg').hide();
                            }, 3000);
                            window.location.href = "#";
                        }
                    },
                    error: function () {
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });
                return false;
            } else {
                return false;
            }
        }

    });


    $('#doc_type').change(function () {
       var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#sub_doc_type').val('');
       var sub_doc_type=0;
        var doc_type_code = $(this).val();
        index_title(); //Change added on 11 September 2020

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, doc_type_code: doc_type_code},
            url: "<?php echo base_url('documentIndex/Ajaxcalls/get_doc_type'); ?>",
            success: function (data)
            {
                if((data.trim()).length > 0){
                    $('#sub_doc_type').html(data);
                    $('#index_sub_item').show();
                    $("#subItemData").show();
                }
                else{
                    $('#index_sub_item').hide();
                    $("#subItemData").hide();
                }
                /*start filter affidavit and Attested */
                $.ajax({
                    type: "POST",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, doc_type_code: doc_type_code,sub_doc_type: sub_doc_type},
                    url: "<?php echo base_url('documentIndex/Ajaxcalls/get_sub_doc_type_check'); ?>",
                    success: function (data)
                    {
                        $('#index_sub_item').show();
                        $('#sub_doc_type_load').html(data);
                        console.log(data);
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function () {
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });
                /*end filter affidavit and Attested */
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });

    });
$('#sub_doc_type').change(function () {
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    var cat_by_doc_title=$('#doc_title_fixed_onetime').val();
    var sub_doc_type = $(this).val();
    var doc_type_code =  $("#doc_type option:selected").val();
    $('#doc_title').val('');
    $.ajax({
        type: "POST",
        data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, doc_type_code: doc_type_code,sub_doc_type: sub_doc_type,cat_by_doc_title:cat_by_doc_title},
        url: "<?php echo base_url('documentIndex/Ajaxcalls/get_sub_doc_type'); ?>",
        success: function (data)
        {
            var cat_text_with_sub_text=(cat_by_doc_title+' '+data);
            $('#doc_title').val(cat_text_with_sub_text);
            $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
            });
        },
        error: function () {
            $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
            });
        }
    });

});
function isEmpty(obj) {
    if (obj == null) return true;
    if (obj.length > 0)    return false;
    if (obj.length === 0)  return true;
    if (typeof obj !== "object") return true;

    // Otherwise, does it have any properties of its own?
    // Note that this doesn't handle
    // toString and valueOf enumeration bugs in IE < 9
    for (var key in obj) {
        if (hasOwnProperty.call(obj, key)) return false;
    }

    return true;
}

    function calculate_page_no(id, value) {
var db_pdf_id='<?php echo $index_details[0]['pdf_id'];?>';
var db_page_count='<?php echo $index_details[0]['total_pdf_pages'];?>';

        if (id == 'page_no_from') {
            var val1 = $('#page_no_to').val();
            var page_to_val = parseInt(val1, 10);
            if (page_to_val != '') {
                if (value > page_to_val) {
                    $('#form_error_message').html('From always less than to value');
                    $('#form_submit').val(false);
                } else {
                    $('#form_submit').val(true);
                    $('#form_error_message').html('');
                    $('#to_error_message').html('');
                }
            }
        } else if (id == 'page_no_to') {
            var val2 = $('#page_no_from').val();

            var page_from_val = parseInt(val2, 10);
            var uploaded_page = $('#uploaded_pdf_total_page').val();
            if(!isEmpty(db_pdf_id))
                uploaded_page=db_page_count;
            if (uploaded_page != '') {
                var total_page = parseInt(uploaded_page, 10);
            } else {
                total_page = 0;
            }
            if (page_from_val != '') {
                if (value > total_page) {
                    $('#form_error_message').html('');
                    $('#to_error_message').html('Exceed total page limit.Doc Total page ' + uploaded_page);
                    $('#page_no_to').val('');
                    $('#form_submit').val(false);
                } else if (page_from_val > value) {
                    $('#form_error_message').html('');
                    $('#to_error_message').html('To always greater than form value');
                    $('#page_no_to').val('');
                    $('#form_submit').val(false);
                } else {
                    $('#form_submit').val(true);
                    $('#form_error_message').html('');
                    $('#to_error_message').html('');
                }
            }
        }
    }
    function delete_index(value) {

        var a = confirm("Are you sure that you really want to delete this record?");
        if (a == true)
        {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url('documentIndex/Ajaxcalls/delete_index'); ?>",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, form_submit_val: value},
                success: function (data) {
                    var resArr = data.split('@@@');
                    if (resArr[0] == 1) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    } else if (resArr[0] == 2) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    }
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        reload_document_index();
                    });
                }
            });
        }
    }

    function reload_document_index() {
        $('#nextButton').hide();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('documentIndex/Ajaxcalls/load_document_index'); ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, res: 1},
            success: function (data) {
                
               $('#index_data').html(data);
               $('#datatable-responsive').dataTable({
                    "initComplete": function () {
                        $('#nextButton').show();
                        $(this).show();
                    }
                });
                
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }

function getindxmodelcopies(){
     //alert("hualalalaal"); //return;
    var docType_ID= $('#doc_type').val();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    $.ajax({
        type: "POST",
        data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, doc_type_code: docType_ID},
        url: "<?php echo base_url('documentIndex/Ajaxcalls/get_index_type'); ?>",
        success: function(result) {

            if(result==11){
                $('#indx_copies').show();

            }else{
                $('#indx_copies').hide();
            }


        }
    });

    //alert(docType_ID); return;

}

function getpetitionersappellantmodals(){
    //alert("hualalalaal"); //return false;
    //$('#petitioners_appellant').show();
    var SubdocType_ID= $('#sub_doc_type').val();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    $.ajax({
        type: "POST",
        data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, doc_type_code: SubdocType_ID},
        url: "<?php echo base_url('documentIndex/Ajaxcalls/get_index_type'); ?>",
        success: function(result) {
            /*alert(result);
            return false;*/

            if(result==95){
                $('#petitioners_appellant').show();
                //$('#petitioners_appellant').focus();

            }else{
                $('#petitioners_appellant').hide();
            }


        }
    });

    //alert(docType_ID); return;

}

</script>

