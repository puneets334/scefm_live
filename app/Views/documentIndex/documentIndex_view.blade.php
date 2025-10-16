<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
<!-- <link rel="shortcut icon"
    href="<?= base_url() . 'assets/newAdmin/' ?>images/favicon.gif"> -->
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css"
    rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/font-awesome.min.css"
    rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/animate.css"
    rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/material.css"
    rel="stylesheet" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/style.css"
    rel="stylesheet">
<link rel="stylesheet"
    type="text/css"
    href="<?= base_url() . 'assets/newAdmin/' ?>css/jquery.dataTables.min.css">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/fullcalendar.css"
    rel="stylesheet">
<link rel="stylesheet"
    href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
<link rel="stylesheet"
    href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<link rel="stylesheet"
    href="<?= base_url() ?>assets/css/jquery-ui.css">
<link href="<?= base_url() . 'assets' ?>/css/select2.min.css"
    rel="stylesheet">
<style>
    .card.custom-card {
        padding: 20px;
        height: 100%;
    }

    .card.custom-card h5 {
        font-size: 16px;
        line-height: 24px;
        color: #0D48BE;min-height: 68px;
    }
    .table-responsive .dataTables_length select, .table-responsive .dataTables_filter input, div#index_data .dataTables_length select, div#index_data .dataTables_filter input{
    min-height: auto;
    padding: 4px 8px !important;
    line-height: 22px !important;min-width:70px;
}
</style>
<div class="center-content-inner comn-innercontent">
    <div class="tab-content">
        <div class="tab-pane active"
            id="home"
            role="tabpanel"
            aria-labelledby="home-tab">

            <div class="tab-form-inner">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="row" id="errDiv" style="display: none;">
                                <div class="alert alert-danger" role="alert" id="alertMsg"></div>
                            </div>
                            <div class="row" id="successAlert" style="display: none;">
                                <div class="alert alert-success" role="alert" id="successAlertMsg"></div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="card custom-card">
                                    <?php  //render('uploadDocuments.upload_document',['uploaded_docs' => @$uploaded_docs]);
                                    ?>

                                    @include('uploadDocuments.upload_document')
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="card custom-card">
                                    <?php
                                    $segment = service('uri');
                                    if (isset($uploaded_docs) && !empty($uploaded_pdf)) { ?>
                                        <div class="row">
                                            <h6 class="text-center fw-bold">Index</h6>
                                        </div>
                                        <?= ASTERISK_RED_MANDATORY ?>
                                        <h5 style="text-align: left;" class="mb-4">For computation of court fees, please use the options below carefully for all documents, interim application(s), if any, uploaded. </h5>
                                        <?php
                                        if (isset($index_details[0]['doc_type_id']) && $index_details[0]['doc_type_id'] != '') {
                                            $editDocType_id = $index_details[0]['doc_type_id'];
                                        }
                                        if (isset($index_details[0]['sub_doc_type_id']) && $index_details[0]['sub_doc_type_id'] != '') {
                                            $pet_appellant = $index_details[0]['sub_doc_type_id'];
                                        }
                                        $attribute = array('class' => 'form-horizontal form-label-left', 'id' => 'create_index_form', 'name' => 'create_index_form', 'autocomplete' => 'off');
                                        echo form_open('#', $attribute);
                                        $dis = "required";
                                        //$dis=(!empty($index_details[0]['pdf_id']))? "disabled":"required";
                                        ?>
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 main_div_show">
                                                <div class="mb-3">
                                                    <label for=""
                                                        class="form-label">PDF File <span style="color: red" class="astriks">*</span></label>
                                                    <select id="pdfs_list" name="pdfs_list" class="form-control cus-form-ctrl filter_select_dropdown" style="width: 100%" <?php echo @$dis; ?>>
                                                        <option value="" title="Select">Select PDF File </option>
                                                        <?php
                                                        if (!empty($uploaded_pdf) && count($uploaded_pdf) > 0) {
                                                            foreach ($uploaded_pdf as $pdf) {
                                                                $sel = (!empty($index_details) && $index_details[0]['pdf_id'] == $pdf['doc_id']) ? "selected=selected" : '';
                                                                echo '<option ' . $sel . ' data-document_id="' . $pdf['doc_id'] . '" data-document_path="' . $pdf['doc_title'] . '" value="' . htmlentities(url_encryption($pdf['doc_id']), ENT_QUOTES) . '">' . htmlentities(strtoupper($pdf['doc_title']), ENT_QUOTES) . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <span id="doc_upload_error_message" class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 main_div_show">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Index Item <span style="color: red" class="astriks">*</span></label>
                                                    <select id="doc_type" name="doc_type" class="form-control cus-form-ctrl filter_select_dropdown" style="width: 100%" onchange="getindxmodelcopies()" required>
                                                        <option value="" title="Select">Select Index Item</option>
                                                        <?php
                                                        if (isset($doc_type) && !empty($doc_type)) {
                                                            foreach ($doc_type as $docs) {
                                                                $sel = (!empty($index_details) && $index_details[0]['doc_type_id'] == $docs['doccode']) ? "selected=selected" : '';
                                                                if ($docs['doccode'] == 8 && $docs['doccode1'] == 0) {
                                                                    echo '<option ' . $sel . '  value="' . htmlentities(url_encryption($docs['doccode']), ENT_QUOTES) . '">' . htmlentities(strtoupper($docs['docdesc']), ENT_QUOTES) . '</option>';
                                                                } else {
                                                                    echo '<option ' . $sel . ' value="' . htmlentities(url_encryption($docs['doccode']), ENT_QUOTES) . '">' . htmlentities(strtoupper($docs['docdesc']), ENT_QUOTES) . '</span></option>'; //change on 28072023 FOR not showing court fee with the documents indexing
                                                                }
                                                            }
                                                        }

                                                        ?>
                                                    </select>
                                                    <span id="section_error_message" class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-4" id="indx_copies">
                                                <div class="mb-3">
                                                    <label for=""
                                                        class="form-label">No of Copies <span style="color: red" class="astriks">*</span></label>
                                                    <input type="text" id="no_copies" name="no_copies" value="<?php echo (!empty($index_details) ? $index_details[0]['no_of_affidavit_copies'] : ''); ?>" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="No of Copies" class="form-control cus-form-ctrl">
                                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="No of Copies should be in numeric (Only Number are allowed)." data-original-title="" title="No of Copies should be in numeric (Only Number are allowed).">
                                                        <i class="fa fa-question-circle-o"></i>
					                                </span>
                                                    <!-- <span class="input-group-addon">
                                                        <i class="fa fa-question-circle-o" data-placement="bottom" data-toggle="popover" data-content="Title should be in numeric (Only Number are allowed)."></i>
                                                    </span> -->
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 main_div_show" id="subItemData">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Index Sub Item <span style="color: red" class="astriks">*</span></label>
                                                    <select id="sub_doc_type" name="sub_doc_type" class="form-control cus-form-ctrl filter_select_dropdown" style="width: 100%" onchange="getpetitionersappellantmodals()">
                                                        <option value="" title="Select">Select Index Sub Item</option>
                                                        <?php
                                                        if (($doc_res)) {
                                                            foreach ($doc_res as $dataRes) {
                                                                $sel = (!empty($index_details) && $index_details[0]['sub_doc_type_id'] == $dataRes['doccode1']) ? "selected=selected" : '';
                                                                /*echo '<option '.$sel.'  value="' . htmlentities(url_encryption($dataRes['doccode1']), ENT_QUOTES) . '">' . htmlentities(strtoupper($dataRes['docdesc']), ENT_QUOTES) . ' (<span> Court Fee : ' . htmlentities(strtoupper($dataRes['docfee']), ENT_QUOTES) . '</span>)</option>';*/
                                                                echo '<option ' . $sel . '  value="' . htmlentities(url_encryption($dataRes['doccode1']), ENT_QUOTES) . '">' . htmlentities(strtoupper($dataRes['docdesc']), ENT_QUOTES) . '</span></option>'; //change on 28072023 FOR not showing court fee with the documents indexing
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <span id="section_error_message" class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-4" id="petitioners_appellant">
                                                <div class="mb-3">
                                                    <label for=""
                                                        class="form-label">No of Petitioners / appellant (Non-party) <span style="color: red" class="astriks">*</span></label>
                                                    <input type="text" id="no_of_petitioner_appellant" name="no_of_petitioner_appellant" value="<?php echo_data(!empty($index_details) ? $index_details[0]['no_of_petitioner_appellant'] : ''); ?>" onkeyup="return isNumber(event)" placeholder="No of Petitioners/Appellant" class="form-control cus-form-ctrl">

                                                    <span class="input-group-addon">
                                                        <i class="fa fa-question-circle-o" data-placement="bottom" data-toggle="popover" data-content="Title should be in numeric (Only Number are allowed)."></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 main_div_show">
                                                <div class="mb-3">
                                                    <span id="sub_doc_type_load"></span>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 my-3">
                                                <div class="save-btns text-center">
                                                        <?php if(!empty($document_id)){?>
                                                        <input type="hidden" name="pdfs_list" value="<?php echo (!empty($index_details) ? url_encryption($index_details[0]['pdf_id']) : ''); ?>">
                                                        <button type="submit" class="quick-btn" id="save_docs" name="save_docs"> UPDATE</button>
                                                            <?php } else {?>
                                                            <?php $stages_array_defected = array(I_B_Defected_Stage);
                                                            if (($_SESSION['efiling_details']['ref_m_efiled_type_id'] ==E_FILING_TYPE_IA  || $_SESSION['efiling_details']['ref_m_efiled_type_id'] ==E_FILING_TYPE_MISC_DOCS) && (in_array($_SESSION['efiling_details']['stage_id'], $stages_array_defected))) {?>
                                                            <?php  }else{ ?>
                                                        <button type="submit" class="quick-btn" id="save_docs" name="save_docs"> ADD</button>
                                                        <?php }?>
                                                        <?php } ?>
                                                </div>
                                            </div>
                                            <?php echo form_close(); ?>
                                            <div id="index_data">
                                                <?php // INDEX LIST BEING POPULATED USING AJAX HERE  JAVASCRIPT FUNCTION reload_document_index  -- AJAXCALLS load_document_index METHOD  
                                                ?>
                                            </div>
                                        <?php } ?>
                                        </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 my-3">
                            <div class="text-center">
                                <?php
                                if (!empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
                                    $previous_url = base_url('newcase/subordinate_court');
                                    $next_url = base_url('newcase/courtFee');                                    
                                } elseif (!empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
                                    $previous_url = base_url('on_behalf_of');
                                    $next_url = base_url('miscellaneous_docs/courtFee');
                                } elseif (!empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
                                    $previous_url = base_url('on_behalf_of');
                                    $next_url = base_url('IA/courtFee');
                                } else if (!empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
                                    $previous_url = base_url('caveat/subordinate_court');
                                    $next_url = base_url('newcase/courtFee');
                                } else if (!empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == OLD_CASES_REFILING) {
                                    $previous_url = base_url('case_details');
                                    $next_url = base_url('oldCaseRefiling/courtFee');
                                } else {
                                    $previous_url = '#';
                                    $next_url = '#';
                                }
                                ?>
                                <a href="<?= $previous_url ?>" <?= $previous_url; ?> class="btn quick-btn gray-btn btnPrevious" type="button">PREVIOUS</a>
                                <a href="<?= $next_url ?>" <?= $next_url; ?> id="nextButton" class="btn quick-btn" tabindex='27' type="button">NEXT </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    if (empty($doc_type)) {
        $doc_type = "";
    }
    ?>
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
    <script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script src="<?= base_url() ?>assets/js/sha256.js"></script>
    <script src="<?= base_url() ?>assets/newAdmin/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url() . 'assets' ?>/js/select2.min.js"></script>
    <script type="text/javascript" src="<?= base_url() . 'assets' ?>/js/jquery.validate.js"></script>
    {{-- @endsection --}}
    <script type="text/javascript">
        $(".filter_select_dropdown").select2().on('select2-focus', function() {
            // debugger;
            $(this).data('select2-closed', true)
        });
        /*Changes added on 11 September 2020 as a part of modification*/
        function index_title() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var doc_type_code = $('#doc_type').val();
            /***Changes start**/
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    doc_type_code: doc_type_code
                },
                url: "<?php echo base_url('documentIndex/Ajaxcalls/get_index_type'); ?>",
                success: function(data) {
                    if (data) {
                        var doc_type_json = '<?php echo json_encode($doc_type); ?>';
                        var doc_type = JSON.parse(doc_type_json);
                        var len = doc_type.length;
                        var i = 0;
                        for (i = 0; i < len; i++) {
                            if (parseInt(data) === parseInt(doc_type[i]['doccode'])) {
                                // $('#doc_title').val((doc_type[i]['docdesc']).replace(/\//g, ' '));
                                $('#doc_title_fixed_onetime').val((doc_type[i]['docdesc']).replace(/\//g, ' '));
                            }
                        }
                    }
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function() {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        }
        /*End of changes*/
        $(document).ready(function() {
            $("#subItemData").hide();
            reload_document_index();
            var editmode_docType = '<?php echo @$editDocType_id; ?>';
            if (editmode_docType == 11) {
                $('#indx_copies').show();
            } else {
                $('#indx_copies').hide();
            }
            var editmpde_pet_appellant = '<?php echo @$pet_appellant; ?>';
            if (editmpde_pet_appellant == 95) {
                $('#petitioners_appellant').show();
            } else {
                $('#petitioners_appellant').hide();
            }
            $('#pdfs_list').on('change', function() {
                var doc_id = (this.value);
                if (doc_id == '') {
                    $("#petition-content-viewer-section").html("");
                } else {
                    var pdf1 = "<?php echo base_url('uploadDocuments/viewDocument/'); ?>" + doc_id;
                    var title = $(this).data('document_title');
                    $("#display_pdf_title").html(title);
                    $("#petition-content-viewer-section").html('<embed  src="' + pdf1 + '" frameborder="0" width="90%" height="100%">');
                }
            });
        });

        $('#create_index_form').on('submit', function() {
            var indexItem = $('#doc_type option:selected').text();
            if (indexItem === "INTERLOCUTARY APPLICATION") {
                var subIndexItem = $('#sub_doc_type').val();
                if (subIndexItem === '') {
                    alert('Please select Sub Document type');
                    return false;
                }
            }
            var caveatDocNum = $("#caveatDocNum").attr('data-caveatdocnum');
            if (caveatDocNum && caveatDocNum == '2') {
                alert('Only two index file allowed.');
                return false;
            } else {
                <?php $id = !empty($index_details) ? $index_details[0]['pdf_id'] : ''; ?>
                var db_pdf_id = '<?php echo $id; ?>';
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
                        url: "<?php echo base_url('documentIndex/DefaultController/add_index_items'); ?>",
                        data: $(this).serialize() + '&caveatDocNum=' + caveatDocNum,
                        async: false,
                        success: function(data) {
                            index_title(); //change added on 11 September 2020
                            $('.save_spinner').hide();
                            $('#save_doc').prop('disabled', false);
                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                $('#errDiv').show();
                                $('#alertMsg').html(resArr[1]);
                                setTimeout(function () {
                                    $('#alertMsg').hide();
                                    $('#alertMsg').html();
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
                                /* Commented by Preeti Agrawal on 9.12.2020 to solve problem of reloading on add since show_current_pdf is not in use now
                                var frame_src = document.getElementById('show_current_pdf').src;
                                var pdf_url = frame_src.split('#');
                                var frame_src_refresh = pdf_url[0] + '#page=' + resArr[1];
                                $("#show_current_pdf").attr("src", frame_src_refresh);*/
                                //--END : Load pdf on Last Page--//
                                //$(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[2] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                // alert(resArr[2]);
                                $('#successAlert').show();
                                $('#successAlert').html(resArr[2]);
                                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                    reload_document_index();
                                    if (!isEmpty(db_pdf_id)) {
                                        window.location.href = "<?php echo base_url('documentIndex'); ?>";
                                    } else {
                                        $('#page_no_from,#page_no_to').val('');
                                        $('#doc_title').val('');
                                        $("#doc_type").val('').trigger('change');
                                        $("#sub_doc_type").val('').trigger('change');
                                    }
                                });
                                setTimeout(function() {
                                    $('#msg').hide();
                                }, 3000);
                                window.location.href = "#";
                            } else if (resArr[0] == 3) {
                                // $('#msg').show();
                                // $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                // setTimeout(function () {
                                //     $('#msg').hide();
                                // }, 3000);
                                // window.location.href = "#";
                                $('#errDiv').show();
                                $('#alertMsg').html(resArr[1]);
                                setTimeout(function () {
                                    $('#alertMsg').hide();
                                    $('#alertMsg').html();
                                }, 3000);
                            }
                        },
                        error: function() {
                            $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
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
        $('#doc_type').change(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#sub_doc_type').val('');
            var sub_doc_type = 0;
            var doc_type_code = $(this).val();
            index_title(); //Change added on 11 September 2020
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    doc_type_code: doc_type_code
                },
                url: "<?php echo base_url('documentIndex/Ajaxcalls/get_doc_type'); ?>",
                success: function(data) {
                    if ((data.trim()).length > 0) {
                        $('#sub_doc_type').html(data);
                        $('#index_sub_item').show();
                        $("#subItemData").show();
                    } else {
                        $('#index_sub_item').hide();
                        $("#subItemData").hide();
                    }
                    /*start filter affidavit and Attested */
                    $.ajax({
                        type: "POST",
                        data: {
                            CSRF_TOKEN: CSRF_TOKEN_VALUE,
                            doc_type_code: doc_type_code,
                            sub_doc_type: sub_doc_type
                        },
                        url: "<?php echo base_url('documentIndex/Ajaxcalls/get_sub_doc_type_check'); ?>",
                        success: function(data) {
                            $('#index_sub_item').show();
                            $('#sub_doc_type_load').html(data);
                            $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                            });
                        },
                        error: function() {
                            $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                            });
                        }
                    });
                    /*end filter affidavit and Attested */
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function() {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        });
        $('#sub_doc_type').change(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var cat_by_doc_title = $('#doc_title_fixed_onetime').val();
            var sub_doc_type = $(this).val();
            var doc_type_code = $("#doc_type option:selected").val();
            $('#doc_title').val('');
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    doc_type_code: doc_type_code,
                    sub_doc_type: sub_doc_type,
                    cat_by_doc_title: cat_by_doc_title
                },
                url: "<?php echo base_url('documentIndex/Ajaxcalls/get_sub_doc_type'); ?>",
                success: function(data) {
                    var cat_text_with_sub_text = (cat_by_doc_title + ' ' + data);
                    // $('#doc_title').val(cat_text_with_sub_text);
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function() {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        });

        function isEmpty(obj) {
            if (obj == null) return true;
            if (obj.length > 0) return false;
            if (obj.length === 0) return true;
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
            <?php
            $dpid = !empty($index_details) ? $index_details[0]['pdf_id'] : '';
            $dpc = !empty($index_details) ? $index_details[0]['total_pdf_pages'] : '';
            ?>
            var db_pdf_id = '<?php echo $dpid; ?>';
            var db_page_count = '<?php echo $dpc; ?>';
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
                if (!isEmpty(db_pdf_id))
                    uploaded_page = db_page_count;
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
            if (a == true) {
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url('documentIndex/Ajaxcalls/delete_index'); ?>",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        form_submit_val: value
                    },
                    success: function(data) {
                        var resArr = data.split('@@@');
                        if (resArr[0] == 1) {
                            // $('#msg').show();
                            // $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            alert(resArr[1]);
                        } else if (resArr[0] == 2) {
                            // $('#msg').show();
                            // $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            alert(resArr[1]);
                        }
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
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
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    res: 1
                },
                success: function(data) {
                    $('#index_data').html(data);
                    $('#datatableresponsive').dataTable({
                        "initComplete": function() {
                            $('#nextButton').show();
                            $(this).show();
                        }
                    });
                    $('#datatableresponsive').removeClass("table table-striped table-bordered dt-responsive nowrap dataTable no-footer").addClass("table table-striped custom-table upload-doc-table");
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    });
                },
                error: function() {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        }

        function getindxmodelcopies() {
            //alert("hualalalaal"); //return;
            var docType_ID = $('#doc_type').val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    doc_type_code: docType_ID
                },
                url: "<?php echo base_url('documentIndex/Ajaxcalls/get_index_type'); ?>",
                success: function(result) {
                    if (result == 11) {
                        $('#indx_copies').show();
                    } else {
                        $('#indx_copies').hide();
                    }
                }
            });
            //alert(docType_ID); return;
        }

        function getpetitionersappellantmodals() {
            //alert("hualalalaal"); //return false;
            //$('#petitioners_appellant').show();
            var SubdocType_ID = $('#sub_doc_type').val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    doc_type_code: SubdocType_ID
                },
                url: "<?php echo base_url('documentIndex/Ajaxcalls/get_index_type'); ?>",
                success: function(result) {
                    /*alert(result);
                    return false;*/
                    if (result == 95) {
                        $('#petitioners_appellant').show();
                        //$('#petitioners_appellant').focus();
                    } else {
                        $('#petitioners_appellant').hide();
                    }
                }
            });
            //alert(docType_ID); return;
        }
    </script>

    <script>
        $('#uploadDocument').on('submit', function() {
            var caveateDocNum = $("#caveateDocNum").attr('data-caveatedocnum');
            if (caveateDocNum && caveateDocNum == '1') {
                alert("Caveat document already uploaded.");
                return false;
            } else {
                if ($('#uploadDocument').valid()) {
                    var doc_title = $("#doc_title").val();
                    var file_data = updateUploadFileName('browser');
                    if (file_data) {
                        $('.progress').show();
                        var CSRF_TOKEN = 'CSRF_TOKEN';
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                        var fileName = $("#browser").prop("files")[0].name;
                        var filedata = $("#browser").prop("files")[0];
                        var formdata = new FormData();
                        formdata.append("pdfDocFile", filedata);
                        formdata.append("doc_title", doc_title);
                        formdata.append("CSRF_TOKEN", CSRF_TOKEN_VALUE);
                        $.ajax({
                            type: 'POST',
                            url: "<?php echo base_url('uploadDocuments/DefaultController/upload_pdf'); ?>",
                            data: formdata,
                            contentType: false,
                            processData: false,
                            beforeSend: function() {
                               $("#upload_doc").prop("disabled", true);
                            },
                            xhr: function() {
                            
                                var xhr = new window.XMLHttpRequest();
                                xhr.upload.addEventListener("progress", function(evt) {
                                    if (evt.lengthComputable) {
                                        var percentComplete = evt.loaded / evt.total;
                                        percentComplete = parseInt(percentComplete * 100);
                                        $('.myprogress').html(percentComplete + '%');
                                        $('.myprogress').css('width', percentComplete + '%');
                                    }
                                }, false);
                                return xhr;
                            },
                            success: function(data) {
                                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                });
                                $("#upload_doc").prop("disabled", false);
                                var resArr = data.split('@@@');
                                if (resArr[0] == 1) {
                                    alert(resArr[1]);
                                    $('.progress').hide();
                                    return false;
                                    // $('#msg').show();
                                    // $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                    // alert(resArr[1]);
                                    $('#dangerAlert').show();
                                    $('#dangerAlert').html(resArr[1]);
                                    setTimeout(function () {
                                        $('#dangerAlert').hide();
                                    }, 3000);
                                } else if (resArr[0] == 2) {
                                    // $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                    // $('#msg').show();
                                    // alert(resArr[1]);
                                    $('#successAlert').show();
                                    $('#successAlert').html(resArr[1]);
                                    location.reload();
                                } else if (resArr[0] == 3) {
                                    // $('#msg').show();
                                    // $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                    alert(resArr[1]);
                                } else if (resArr[0] == 4) {
                                    // $('#msg').show();
                                    $('.progress').hide();
                                    // $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                    // alert(resArr[1]);
                                    $('#dangerAlert').show();
                                    $('#dangerAlert').html(resArr[1]);
                                    setTimeout(function () {
                                        $('#dangerAlert').hide();
                                    }, 3000);
                                }
                                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                });
                            },
                            error: function(e) {
                                $("#upload_doc").prop("disabled", false);
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
        var file_data;

        function updateUploadFileName(upload_field_Id) {
            var max_file_size = "<?php echo UPLOADED_FILE_SIZE; ?>";
            var upload_file = $('#' + upload_field_Id).get(0).files[0];
            var file_type = upload_file.type;
            var file_size = upload_file.size;
            if (file_type == 'application/pdf' && file_size <= max_file_size) {
                var file_data = new FormData();
                var newFile_Name = new Date().getTime() + '.pdf';
                file_data.append('pdfDocFile', upload_file, newFile_Name);
                //file_data.append('doc_title', title);
                return file_data;
            } else {
                var max_file_size_mb = (max_file_size / 1024) / 1024;
                alert('Please select PDF type and size less than ' + max_file_size_mb + 'MB.');
                $("#pdf_error span").remove('');
                $("#pdf_error").append('<span>PDF type and size less than ' + max_file_size_mb + 'MB.</span>');
                $("#pdf_error span").css('color', 'red');
                $("#browser").val('');
                return false;
            }

        }

        function DocDeleteAction(value) {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            // var a = confirm("Are you sure that you really want to delete this record ?");
            var a = confirm("Once a document is deleted, index of that document will also be deleted. Do you really want to delete?");
            if (a == true) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('uploadDocuments/DefaultController/deletePDF'); ?>",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        form_submit_val: value
                    },
                    success: function(result) {
                        var resArr = result.split('@@@');
                        if (resArr[0] == 1) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        } else if (resArr[0] == 2) {
                            // $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            $('#successAlert').show();
                            $('#successAlert').html(resArr[1]);
                            // $('#msg').show();
                            location.reload();
                        }
                        $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function(result) {
                        $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });
            }
        }

        // $(".efilaor").click(function() {
        //     $.ajax({
        //         url: "<?php echo base_url('newcase/AutoDiary/valid_efil'); ?>", // enabled this for auto diary generation
        //         success: function(data) {
        //             var dataas = data.split('?');
        //             var ct = dataas[0];
        //             var dataarr = dataas[1].slice(1).split(',');
        //             if ((dataarr[0] != 2) && (dataarr[0] != 3) && (dataarr[0] != 7) && (dataarr[0]) != 8 && (dataarr[0]) != 10) {
        //                 alert("All Completed");
        //                 // window.location.href = "<?php echo base_url('newcase/AutoDiary') ?>"; //ENABLED THIS FOR AUTO DIARY
        //                 showLoaderAndRedirect("<?php echo base_url('newcase/AutoDiary') ?>");
        //             }
        //             if ((dataarr[0]) == 2) {
        //                 alert("Petitioner details are mandatory to fill. Redirecting to page !!");
        //                 // window.location.href = "<?php echo base_url('newcase/petitioner') ?>";
        //                 showLoaderAndRedirect("<?php echo base_url('newcase/petitioner') ?>");
        //             }
        //             if ((dataarr[0]) == 3) {
        //                 alert("Respondent details are mandatory to fill. Redirecting to page !!");
        //                 // window.location.href = "<?php echo base_url('newcase/respondent') ?>";
        //                 showLoaderAndRedirect("<?php echo base_url('newcase/respondent') ?>");
        //             }
        //             if (((dataarr[0]) == 7) && (ct != 5) && (ct != 6) && (ct != 7) && (ct != 8)) {
        //                 alert("Subordinate_courts details are mandatory to fill. Redirecting to page !!");
        //                 // window.location.href = "<?php echo base_url('newcase/subordinate_court') ?>";
        //                 showLoaderAndRedirect("<?php echo base_url('newcase/subordinate_court') ?>");
        //             }
        //             if (((dataarr[0]) == 8)) {
        //                 alert("Documents are not uploaded. Redirecting to page !!");
        //                 // window.location.href = "<?php echo base_url('newcase/uploadDocuments') ?>";
        //                 showLoaderAndRedirect("<?php echo base_url('newcase/uploadDocuments') ?>");
        //             }
        //             if (((dataarr[0]) == 10)) {
        //                 alert("Court Fees not paid. Redirecting to page !!");
        //                 // window.location.href = "<?php echo base_url('newcase/courtFee') ?>";
        //                 showLoaderAndRedirect("<?php echo base_url('newcase/courtFee') ?>");
        //             }
        //         },
        //         error: function() {
        //             $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
        //                 $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        //             });
        //         }
        //     });
        //     return false;
        // });
        
        function showLoaderAndRedirect(url) {
            // Show the loader
            document.getElementById('loader-wrapper').style.display = 'flex';
        
            // Delay to ensure the loader is visible for a moment
            setTimeout(function() {
                window.location.href = url;
            }, 500); // Adjust delay as needed
        }

        function ActionToTrash(trash_type) {
        event.preventDefault();
        var trash_type = trash_type;
        var url = "";
        if (trash_type == '') {
            swal("Cancelled", "Your imaginary file is safe :)", "error");
            return false;
        } else if (trash_type == 'UAT') {
            url = "<?php echo base_url('userActions/trash'); ?>";
        } else if (trash_type == 'SLT') {
            url = "<?php echo base_url('stage_list/trash'); ?>";
        } else if (trash_type == 'EAT') {
            url = "<?php echo base_url('userActions/trash'); ?>";
        } else {
            swal("Cancelled", "Your imaginary file is safe :)", "error");
            return false;
        }
        //    alert('trash_type'+trash_type+'url='+url);//return false;
        swal({
                title: "Do you really want to trash this E-Filing,",
                text: "once it will be trashed you can't restore the same.",
                type: "warning",
                position: "top",
                showCancelButton: true,
                confirmButtonColor: "green",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                buttons: ["Make Changes", "Yes!"],
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) { // submitting the form when user press yes
                    var link = document.createElement("a");
                    link.href = url;
                    link.target = "_self";
                    link.click();
                    swal({
                        title: "Deleted!",
                        text: "E-Filing has been deleted.",
                        type: "success",
                        timer: 2000
                    });

                } else {
                    //swal({title: "Cancelled",text: "Your imaginary file is safe.",type: "error",timer: 1300});
                }

            });
    }
    </script>