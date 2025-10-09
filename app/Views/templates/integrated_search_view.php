<div class="modal fade" id="case_data_model" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <span style='text-align: center; font-size: 14px;'><center><strong>Case & Order National Search  </strong></center></span> <button type="button" class="close" data-dismiss="modal"> <span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <div class="form-group">
                    <div class="col-md-12 col-sm-6 col-xs-12">
                        <label class="radio-inline"><input type="radio" id="high_court" name="case_court_type" value="1" maxlength="11"  onchange="displayIntegratedSearch(this.value)"><?= $this->lang->line('high_court'); ?></label>
                        <label class="radio-inline"><input type="radio" id="dist_court" name="case_court_type" value="2" maxlength="11" onchange="displayIntegratedSearch(this.value)"><?= $this->lang->line('distt_court'); ?></label>
                    </div>
                </div>
                <div class="contact-response" id="mail_msg" ></div>
            </div>

            <div class="modal-body">
                <div class="row" id="high_div_hide" hidden>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-sm-2 input-sm"><?= $this->lang->line('high_court'); ?> <span style="color: red">*</span> :</label>
                                <div class="col-sm-8">
                                    <?php
                                    //  $CI = & get_instance();
                                    //   $CI->load->model('integrated_search/Dropdown_list_model');
                                    //   $high_court_list = $CI->Dropdown_list_model->get_high_court_list();
                                    ?>
                                    <select name="high_court_name" id="high_court_name" class="form-control col-md-7 col-xs-12 input-sm"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="district_div_hide" hidden>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12" id="contact"></div>
                        </div>
                        <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-sm-2 input-sm"><?= $this->lang->line('state'); ?><span style="color: red">*</span> :</label>
                                    <div class="col-sm-10">
                                        <select name="diststate" id="diststate" class="form-control col-md-7 col-xs-12 input-sm state" >
                                            <option value="" title="Select"><?= $this->lang->line('state'); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-sm-2 col-xs-4 input-sm"><?= $this->lang->line('district'); ?><span style="color: red">*</span> :</label>
                                    <div class="col-sm-10 col-xs-11">
                                        <select name="dist_district" id="dist_district" class="form-control col-md-7 col-xs-11 input-sm" style="width:100%">
                                            <option value="" title="Select"><?= $this->lang->line('district'); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div> </div>
                <div class="clearfix"></div><br>

                <div class="open_case_menu" style="display:none;">
                    <ul class="nav nav-tabs" style="">
                        <li  class="active" style="width:33%"><a data-toggle="tab" href="#menu1"><b>Case Status</b></a></li>
                        <li style="width:33%"><a data-toggle="tab" href="#menu2"><b>Court Order</b></a></li>
                        <li style="width:33%"><a data-toggle="tab" href="#menu3"> <b>Cause List</b></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="menu1" class="tab-pane fade in active">
                            <div class="x_panel">
                                <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class=" col-lg-3 col-md-3 col-sm-3 col-xs-5">   
                                            <a id="cs_num" href="" target="_blank" title="Case Number"><span class="round-icon"><i class="fa fa-file-text-o fa-2x " aria-hidden="true"></i> </span><br>Case Number</a>
                                        </div> <div class=" col-lg-3 col-md-3 col-sm-3 col-xs-5"><a id="cs_fir" href="" target="_blank" title="FIR Number"><span class="round-icon"><i class="fa fa-wpforms fa-2x" aria-hidden="true"></i></span><br>FIR Number</a>
                                        </div> <div class=" col-lg-3 col-md-3 col-sm-3 col-xs-5"> 
                                            <a id="cs_party" href="" target="_blank" title="Party Name"><span class="round-icon"><i class="fa fa-users fa-2x" aria-hidden="true"></i></span><br>Party Name</a>
                                        </div> <div class="col-lg-3 col-md-3 col-sm-3 col-xs-5">  
                                            <a id="cs_advo" href="" target="_blank" title="Advocate Name"><span class="round-icon"><i class="fa fa-user fa-2x" aria-hidden="true"></i></span><br>Advocate Name</a>
                                        </div> </div><div class="clearfix"></div><hr>
                                    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class=" col-lg-3 col-md-3 col-sm-3 col-xs-4"> 
                                            <a id="cs_code" href="" target="_blank" title="Case Code"><span class="round-icon"><i class="fa fa-files-o fa-2x" aria-hidden="true"></i></span><br>Case Code</a>
                                        </div> <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4"> 
                                            <a id="cs_act" href="" target="_blank" title=" Act"><span class="round-icon"><i class="fa fa-legal fa-2x" aria-hidden="true"></i></span><br>Act</a>
                                        </div> <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4"> 
                                            <a id="cs_case_type" href="" target="_blank" title="Case Type"><span class="round-icon"><i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i></span><br>Case Type</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="menu2" class="tab-pane fade">
                            <div class="x_panel">
                                <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4"> 
                                        <a id="cs_case_no" href="" target="_blank" title="Case Number"><span class="round-icon"><i class="fa fa-info fa-2x" aria-hidden="true"></i></span><br>Case Number</a>
                                    </div>  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">  <a id="cs_court_no" href="" target="_blank" title="Court Number"><span class="round-icon"><i class="fa fa-university fa-2x" aria-hidden="true"></i></span><br>Court Number</a>
                                    </div>  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">    <a id="cs_party_name" href="" target="_blank" title="Party Name"><span class="round-icon"><i class="fa fa-users fa-2x" aria-hidden="true"></i></span><br>Party Name</a>
                                    </div>  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">   <a id="cs_order_date" href="" target="_blank" title="Order Date"><span class="round-icon"><i class="fa fa-calendar fa-2x Order DATe" aria-hidden="true"></i></span><br>Order Date</a>
                                    </div>     
                                </div>
                            </div>
                        </div>
                        <div id="menu3" class="tab-pane fade">
                            <div class="x_panel">
                                <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <a id="cs_cause_list" href="" target="_blank" title="Cause List"><span class="round-icon"><i class="fa fa-info fa-2x" aria-hidden="true"></i></span><p style="word-wrap: break-word; width: 100%">Cause List</p></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div><br>

                <div class="open_hc_case_menu" style="display:none;">
                    <ul class="nav nav-tabs" style="">
                        <li  class="active" style="width:33%"><a data-toggle="tab" href="#menu4"><b>Case Status</b></a></li>
                        <li style="width:33%"><a data-toggle="tab" href="#menu5"><b>Court Order</b></a></li>
                        <li style="width:33%"><a data-toggle="tab" href="#menu6"> <b>Cause List</b></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="menu4" class="tab-pane fade in active">
                            <div class="x_panel">
                                <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class=" col-lg-3 col-md-3 col-sm-3 col-xs-5">   
                                            <a id="hc_cs_num" href="" target="_blank" title="Case Number"><span class="round-icon"><i class="fa fa-file-text-o fa-2x " aria-hidden="true"></i> </span><br>Case Number</a>
                                        </div> <div class=" col-lg-3 col-md-3 col-sm-3 col-xs-5"><a id="hc_cs_fir" href="" target="_blank" title="FIR Number"><span class="round-icon"><i class="fa fa-wpforms fa-2x" aria-hidden="true"></i></span><br>FIR Number</a>
                                        </div> <div class=" col-lg-3 col-md-3 col-sm-3 col-xs-5"> 
                                            <a id="hc_cs_party" href="" target="_blank" title="Party Name"><span class="round-icon"><i class="fa fa-users fa-2x" aria-hidden="true"></i></span><br>Party Name</a>
                                        </div> <div class="col-lg-3 col-md-3 col-sm-3 col-xs-5">  
                                            <a id="hc_cs_advo" href="" target="_blank" title="Advocate Name"><span class="round-icon"><i class="fa fa-user fa-2x" aria-hidden="true"></i></span><br>Advocate Name</a>
                                        </div> </div><div class="clearfix"></div><hr>
                                    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class=" col-lg-3 col-md-3 col-sm-3 col-xs-4"> 
                                            <a id="hc_cs_code" href="" target="_blank" title="Filing Number"><span class="round-icon"><i class="fa fa-files-o fa-2x" aria-hidden="true"></i></span><br>Filing Number</a>
                                        </div> <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4"> 
                                            <a id="hc_cs_act" href="" target="_blank" title=" Act"><span class="round-icon"><i class="fa fa-legal fa-2x" aria-hidden="true"></i></span><br>Act</a>
                                        </div> <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4"> 
                                            <a id="hc_cs_case_type" href="" target="_blank" title="Case Type"><span class="round-icon"><i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i></span><br>Case Type</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="menu5" class="tab-pane fade">
                            <div class="x_panel">
                                <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4"> 
                                        <a id="hc_cs_case_no" href="" target="_blank" title="Case Number"><span class="round-icon"><i class="fa fa-info fa-2x" aria-hidden="true"></i></span><br>Case Number</a>
                                    </div>  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4"><a id="hc_cs_court_no" href="" target="_blank" title="Judge Wise"><span class="round-icon"><i class="fa fa-university fa-2x" aria-hidden="true"></i></span><br>Judge Wise</a>
                                    </div>  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4"><a id="hc_cs_party_name" href="" target="_blank" title="Party Name"><span class="round-icon"><i class="fa fa-users fa-2x" aria-hidden="true"></i></span><br>Party Name</a>
                                    </div>  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4"><a id="hc_cs_order_date" href="" target="_blank" title="Order Date"><span class="round-icon"><i class="fa fa-calendar fa-2x Order DATe" aria-hidden="true"></i></span><br>Order Date</a>
                                    </div>     
                                </div>
                            </div>
                        </div>
                        <div id="menu6" class="tab-pane fade">
                            <div class="x_panel">
                                <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">
<!--                                                        <a id="cs_cause_list" href="" target="_blank" title="Cause List"><span class="round-icon"><i class="fa fa-info fa-2x" aria-hidden="true"></i></span><p style="word-wrap: break-word; width: 100%">Cause List</p></a>-->
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div><br>
            </div>
        </div>
    </div>
</div>

<script>

    $('.case_data_value').click(function () {

        $("#district_div_hide").hide();
        $("#high_court").attr('checked', 'checked');
        $("#high_div_hide").show();
        $("#open_case_menu").hide();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE},
            url: "<?php echo base_url('integrated_search/type/highcourts'); ?>",
            success: function (data)
            {

                $('#high_court_name').html(data);
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

    $('#diststate').change(function () {
        var get_state_id = $(this).val();
        $('#dist_district').val('');
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
            url: "<?php echo base_url('integrated_search/type/get_openAPI_district'); ?>",
            success: function (data)
            {
                $('#dist_district').html(data);
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

    $('#dist_district').change(function () {

        var get_distt_id = $(this).val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var state_id = $("#diststate option:selected").val();

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: state_id, get_distt_id: get_distt_id},
            url: "<?php echo base_url('integrated_search/type/get_case_data_urls'); ?>",
            success: function (data)
            {
                $('.open_case_menu').show();
                var result = data.split('##');
                var st_id = result[0];
                var dist_id = result[1];
                var cs_num_url = 'https://services.ecourts.gov.in/ecourtindia_v4_bilingual/cases/case_no.php?state=D&state_cd=' + st_id + '&dist_cd=' + dist_id;
                var cs_fir_url = 'https://services.ecourts.gov.in/ecourtindia_v4_bilingual/cases/fir1.php?state=D&state_cd=' + st_id + '&dist_cd=' + dist_id;
                var cs_party_url = 'https://services.ecourts.gov.in/ecourtindia_v4_bilingual/cases/ki_petres.php?state=D&state_cd=' + st_id + '&dist_cd=' + dist_id;
                var cs_advo_url = 'https://services.ecourts.gov.in/ecourtindia_v4_bilingual/cases/qs_civil_advocate.php?state=D&state_cd=' + st_id + '&dist_cd=' + dist_id;
                var cs_case_code_url = 'https://services.ecourts.gov.in/ecourtindia_v4_bilingual/cases/c_index.php?state=D&state_cd=' + st_id + '&dist_cd=' + dist_id;
                var cs_act_url = 'https://services.ecourts.gov.in/ecourtindia_v4_bilingual/cases/s_actwise.php?state=D&state_cd=' + st_id + '&dist_cd=' + dist_id;
                var cs_case_type_url = 'https://services.ecourts.gov.in/ecourtindia_v4_bilingual/cases/s_casetype.php?state=D&state_cd=' + st_id + '&dist_cd=' + dist_id;
                var cs_case_no_url = 'https://services.ecourts.gov.in/ecourtindia_v4_bilingual/cases/s_kiosk_order.php?state=D&state_cd=' + st_id + '&dist_cd=' + dist_id;
                var cs_court_no_url = 'https://services.ecourts.gov.in/ecourtindia_v4_bilingual/cases/s_order.php?state=D&state_cd=' + st_id + '&dist_cd=' + dist_id;
                var cs_party_name_url = 'https://services.ecourts.gov.in/ecourtindia_v4_bilingual/cases/s_partyorder.php?state=D&state_cd=' + st_id + '&dist_cd=' + dist_id;
                var cs_order_date_url = 'https://services.ecourts.gov.in/ecourtindia_v4_bilingual/cases/s_orderdate.php?state=D&state_cd=' + st_id + '&dist_cd=' + dist_id;
                var cs_cause_list_url = 'https://services.ecourts.gov.in/ecourtindia_v4_bilingual/cases/dailyboard.php?state=D&state_cd=' + st_id + '&dist_cd=' + dist_id;
                //var cs_case_code_url='https://services.ecourts.gov.in/ecourtindia_v4_bilingual/cases/c_index.php?state=D&state_cd=' + st_id + '&dist_cd='+ dist_id;
                $("#cs_num").attr("href", cs_num_url);
                $("#cs_fir").attr("href", cs_fir_url);
                $("#cs_party").attr("href", cs_party_url);
                $("#cs_advo").attr("href", cs_advo_url);
                $("#cs_code").attr("href", cs_case_code_url);
                $("#cs_act").attr("href", cs_act_url);
                $("#cs_case_type").attr("href", cs_case_type_url);
                $("#cs_case_no").attr("href", cs_case_no_url);
                $("#cs_court_no").attr("href", cs_court_no_url);
                $("#cs_party_name").attr("href", cs_party_name_url);
                $("#cs_order_date").attr("href", cs_order_date_url);
                $("#cs_cause_list").attr("href", cs_cause_list_url);
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


    $('#high_court_name').change(function () {

        $('.open_hc_case_menu').show();
        var data = $('#high_court_name').val();

        var result = data.split('##');
        var hc_id = result[0];
        var state_name = result[1];
        //var court_code = result[2];
        var court_code = result[3];

        var cs_num_url = 'https://services.ecourts.gov.in/ecourtindiaHC/cases/case_no.php?state_cd=' + hc_id + '&dist_cd=1&court_code=' + court_code + '&stateNm=' + state_name;
        var cs_fir_url = 'https://services.ecourts.gov.in/ecourtindiaHC/cases/fir1.php?state_cd=' + hc_id + '&dist_cd=1&court_code=' + court_code + '&stateNm=' + state_name;
        var cs_party_url = 'https://services.ecourts.gov.in/ecourtindiaHC/cases/ki_petres.php?state_cd=' + hc_id + '&dist_cd=1&court_code=' + court_code + '&stateNm=' + state_name;
        var cs_advo_url = 'https://services.ecourts.gov.in/ecourtindiaHC/cases/qs_civil_advocate.php?state_cd=' + hc_id + '&dist_cd=1&court_code=' + court_code + '&stateNm=' + state_name;
        var cs_case_code_url = 'https://services.ecourts.gov.in/ecourtindiaHC/cases/c_index.php?state_cd=' + hc_id + '&dist_cd=1&court_code=' + court_code + '&stateNm=' + state_name;
        var cs_act_url = 'https://services.ecourts.gov.in/ecourtindiaHC/cases/s_actwise.php?state_cd=' + hc_id + '&dist_cd=1&court_code=' + court_code + '&stateNm=' + state_name;
        var cs_case_type_url = 'https://services.ecourts.gov.in/ecourtindiaHC/cases/s_casetype.php?state_cd=' + hc_id + '&dist_cd=1&court_code=' + court_code + '&stateNm=' + state_name;


        var cs_case_no_url = 'https://services.ecourts.gov.in/ecourtindiaHC/cases/s_kiosk_order.php?state_cd=' + hc_id + '&dist_cd=1&court_code=' + court_code + '&stateNm=' + state_name;
        var cs_court_no_url = 'https://services.ecourts.gov.in/ecourtindiaHC/cases/s_order.php?state_cd=' + hc_id + '&dist_cd=1&court_code=' + court_code + '&stateNm=' + state_name;
        var cs_party_name_url = 'https://services.ecourts.gov.in/ecourtindiaHC/cases/s_partyorder.php?state_cd=' + hc_id + '&dist_cd=1&court_code=' + court_code + '&stateNm=' + state_name;
        var cs_order_date_url = 'https://services.ecourts.gov.in/ecourtindiaHC/cases/s_orderdate.php?state_cd=' + hc_id + '&dist_cd=1&court_code=' + court_code + '&stateNm=' + state_name;


        $("#hc_cs_num").attr("href", cs_num_url);
        $("#hc_cs_fir").attr("href", cs_fir_url);
        $("#hc_cs_party").attr("href", cs_party_url);
        $("#hc_cs_advo").attr("href", cs_advo_url);
        $("#hc_cs_code").attr("href", cs_case_code_url);
        $("#hc_cs_act").attr("href", cs_act_url);
        $("#hc_cs_case_type").attr("href", cs_case_type_url);
        $("#hc_cs_case_no").attr("href", cs_case_no_url);
        $("#hc_cs_court_no").attr("href", cs_court_no_url);
        $("#hc_cs_party_name").attr("href", cs_party_name_url);
        $("#hc_cs_order_date").attr("href", cs_order_date_url);
        $("#hc_cs_cause_list").attr("href", cs_cause_list_url);


    });
    $('.close').click(function () {
        $('#dist_district').val('');
        $('#diststate').val('');
        $('.open_case_menu').hide();
        $("#open_hc_case_menu").hide();
        $('#high_court_name').val('');
    });


    function displayIntegratedSearch(element) {
        var data = element;

        if (data == '1') {
            $(".open_case_menu").hide();
            $(".open_hc_case_menu").hide();
            $("#hc_cs_num").attr("href", "");
            $("#hc_cs_fir").attr("href", "");
            $("#hc_cs_party").attr("href", "");
            $("#hc_cs_advo").attr("href", "");
            $("#hc_cs_code").attr("href", "");
            $("#hc_cs_act").attr("href", "");
            $("#hc_cs_case_type").attr("href", "");
            $("#hc_cs_case_no").attr("href", "");
            $("#hc_cs_court_no").attr("href", "");
            $("#hc_cs_party_name").attr("href", "");
            $("#hc_cs_order_date").attr("href", "");
            $("#hc_cs_cause_list").attr("href", "");
            $("#district_div_hide").hide();
            $("#high_div_hide").show();
            $('#dist_district').val('');
            $('#diststate').val('');
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE},
                url: "<?php echo base_url('integrated_search/type/highcourts'); ?>",
                success: function (data)
                {
                    $('#high_court_name').html(data);
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
        if (data == '2') {
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $(".open_case_menu").hide();
            $(".open_hc_case_menu").hide();
            $(".open_hc_case_menu").css('display', 'none');
            $("#cs_num").attr("href", "");
            $("#cs_fir").attr("href", "");
            $("#cs_party").attr("href", "");
            $("#cs_advo").attr("href", "");
            $("#cs_code").attr("href", "");
            $("#cs_act").attr("href", "");
            $("#cs_case_type").attr("href", "");
            $("#cs_case_no").attr("href", "");
            $("#cs_court_no").attr("href", "");
            $("#cs_party_name").attr("href", "");
            $("#cs_order_date").attr("href", "");
            $("#cs_cause_list").attr("href", "");
            $('#high_state').val('');

            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, data1: '1'},
                url: "<?php echo base_url('integrated_search/type/state'); ?>",
                success: function (data)
                {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                    $('.state').html(data);

                },
                error: function () {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });

            $("#high_div_hide").hide();
            $('#dist_district').val('');
            $('#diststate').val('');
            $("#district_div_hide").show();
            $("#high_div_hide").hide();
        }


    }
    $(".main_menu_li").mouseover(function () {
        var main_class = $(this).attr('class');
        var title_text = $.trim($(this).text());
        var uniqueList = title_text.split(' ');
        var span_class = uniqueList[0] + '_title';

        $('p.' + uniqueList[0] + '_title').replaceWith($('<span class="' + uniqueList[0] + '_title">' + title_text + '</span>'));
        if (main_class == "main_menu_li current-page") {
            $('.' + span_class).css('background-color', '#B1B1B1');
        } else {
            $('.' + span_class).css('background-color', '#EDEDED');
        }
    });
    $('.main_menu_li').mouseout(function () {
        var title_text = $.trim($(this).text());
        var uniqueList = title_text.split(' ');

        $('span.' + uniqueList[0] + '_title').replaceWith($('<p class="' + uniqueList[0] + '_title">' + title_text + '</p>'));
    });

</script>

