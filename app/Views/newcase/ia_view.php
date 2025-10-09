<div class="row">
    <div class="panel panel-default panel-body">
        <div class="col-sm-5 col-xs-12">
            <?php
            $attribute = array('class' => 'form-horizontal form-label-left', 'id' => 'create_index_form', 'name' => 'create_index_form', 'autocomplete' => 'off');
            echo form_open('#', $attribute);
            ?>
            <div class="form-group main_div_show" >
                <label class="control-label col-sm-3 input-sm"> PDF File  <span style="color: red">*</span> :</label>
                <div class="col-sm-9">
                    <select tabindex = '1' id="pdfs_list" name="pdfs_list"  class="form-control input-sm filter_select_dropdown" style="width: 100%">
                        <option value="" title="Select">Select PDF File</option>
                        <?php
                        foreach ($uploaded_pdf as $pdf) {
                            echo '<option  value="' . escape_data(url_encryption($pdf['doc_id'])) . '">' . escape_data(strtoupper($pdf['doc_title'])) . '</option>';
                        }
                        ?>
                    </select>
                    <span id="doc_upload_error_message" class="text-danger"></span>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 input-sm">From Page No. <span style="color: red">*</span> :</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input tabindex = '2' type="text" id="page_no_from" name="page_no_from"  onkeyup="calculate_page_no(this.id, this.value);"  minlength="1" maxlength="3" placeholder="From" class="form-control input-sm page_no_from">
                        <span class="input-group-addon">
                            <i class="fa fa-question-circle-o" data-placement="bottom" data-toggle="popover" data-content="Only digits are allowed."></i>
                        </span>
                    </div>
                </div>
                <label class="control-label col-sm-3 input-sm"> To Page No. <span style="color: red">*</span> :</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input tabindex = '3' type="text" id="page_no_to" name="page_no_to" onkeyup="calculate_page_no(this.id, this.value);" minlength="1" maxlength="3" placeholder="To" class="form-control input-sm">
                        <span class="input-group-addon">
                            <i class="fa fa-question-circle-o" data-placement="bottom" data-toggle="popover" data-content="Only digits are allowed."></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group main_div_show">
                <label class="control-label col-sm-3 input-sm"> IA <span style="color: red">*</span> :</label>
                <div class="col-sm-9">
                    <select id="doc_type" tabindex = '4' name="doc_type"  class="form-control input-sm filter_select_dropdown" style="width: 100%">
                        <option value="" title="Select">Select IA</option>
                        <?php
                        foreach ($doc_type as $docs) {
                            echo '<option  value="' . escape_data(url_encryption($docs['doccode'])) . '">' . escape_data(strtoupper($docs['docdesc'])) . '</option>';
                        }
                        ?>
                    </select>
                    <span id="section_error_message" class="text-danger"></span>
                </div>
            </div>
            <div class="form-group main_div_show">
                <label class="control-label col-sm-3 input-sm"> IA Type :</label>
                <div class="col-sm-9">
                    <select id="sub_doc_type" tabindex = '5' name="sub_doc_type"  class="form-control input-sm filter_select_dropdown" style="width: 100%">
                        <option value="" title="Select">Select IA Type</option>

                    </select>
                    <span id="section_error_message" class="text-danger"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3 input-sm"> IA Title  <span style="color: red">*</span> :</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="text" id="doc_title"  tabindex = '6' name="doc_title" onkeyup="this.value = this.value.replace(/[^0-9A-Za-z-.() ]/g, '')" minlength="2" maxlength="150" placeholder="Document Title" class="form-control input-sm">
                        <span class="input-group-addon">
                            <i class="fa fa-question-circle-o" data-placement="bottom" data-toggle="popover" data-content="Title should be in alphanumeric (Only - . () and space are allowed)."></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>                               
            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-6">
                    <button type="submit" class="btn btn-success" tabindex = '7' id="save_docs" name="save_docs"> ADD
                        <i class="fa fa-spinner fa-spin save_spinner" style="font-size:15px;display: none;"></i></button>
                    <button class="btn btn-primary" tabindex = '8' type="reset" id="reset_frm">RESET</button>
                </div>
            </div>
            <?php echo form_close(); ?>

            <div id ="index_data">             
                <?php 
                    $this->load->view('newcase/ia_items_list_view');                                
                ?>
            </div>

        </div>
        <div class="col-sm-7 col-xs-12">
            <div class="x_panel">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">        
        <a href="<?= base_url('documentIndex'); ?>" class="btn btn-primary btnPrevious" type="button">Previous</a>
        <a href="<?= base_url('newcase/courtFee'); ?>" class="btn btn-primary btnNext" type="button">Next</a>
    </div>
</div>

<script type="text/javascript">

    $('#create_index_form').on('submit', function () {

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
                url: "<?php echo base_url('newcase/IA/add_index_item'); ?>",
                data: $(this).serialize(),
                async: false,
                success: function (data)
                {

                    $('.save_spinner').hide();
                    $('#save_doc').prop('disabled', false);
                    var resArr = data.split('@@@');

                    if (resArr[0] == 1) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        setTimeout(function () {
                            $('#msg').hide();
                        }, 3000);

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
                        var frame_src = document.getElementById('show_current_pdf').src;
                        var pdf_url = frame_src.split('#');
                        var frame_src_refresh = pdf_url[0] + '#page=' + resArr[1];
                        $("#show_current_pdf").attr("src", frame_src_refresh);
                        //--END : Load pdf on Last Page--//


                        $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[2] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                           
                           $('#index_data').html(resArr[3]);
                        });
                        setTimeout(function () {
                            $('#msg').hide();
                        }, 3000);
                    } else if (resArr[0] == 3) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        setTimeout(function () {
                            $('#msg').hide();
                        }, 3000);
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
    });

    $('#pdfs_list').change(function () {
        var uploaded = $('#pdfs_list').val();

        $('#save_doc').show();
        $('#show_category').hide();
        $('#page_completed').html('');
        if (uploaded == '') {
            $('#display_uploaded_document').html("");
            $('#datatable-responsive1 tbody:first').html('<tr><td colspan="6" class="text-center">No Document uploaded.</td></tr>');
            $("#show_current_pdf").attr("src", "");
            return false;
        }
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, document: uploaded},
            url: "<?php echo base_url('newcase/IA/load_pdf_viewer'); ?>",
            success: function (data)
            {
                var resArr = data.split('@@@');

                if (resArr[0] == 2) {

                    $('#view_docs').show();
                    $('#pdf-tab').trigger('click');
                    $('#page_no_from,#uploaded_pdf_page').val(resArr[1]);
                    $('#uploaded_pdf_total_page').val(resArr[2]);
                    $('#display_uploaded_document').html(resArr[3] + ', Total Pages : ' + resArr[2]);
                    $("#show_current_pdf").attr("src", resArr[4]);

                } else {
                    $('#view_docs').hide();
                }

            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    });


    $('#doc_type').change(function () {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#sub_doc_type').val('');

        var doc_type_code = $(this).val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, doc_type_code: doc_type_code},
            url: "<?php echo base_url('newcase/IA/get_doc_type'); ?>",
            success: function (data)
            {
                $('#sub_doc_type').html(data);
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

    function calculate_page_no(id, value) {

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
                url: "<?php echo base_url('newcase/IA/delete_index'); ?>",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, form_submit_val: value},
                success: function (data) {
                    var resArr = data.split('@@@');
                    if (resArr[0] == 1) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    } else if (resArr[0] == 2) {
                        $('#msg').show();
                        $('#index_data').html(resArr[2]);
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
</script>