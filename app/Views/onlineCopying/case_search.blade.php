<?php
if(isset(session()->get('login')['impersonator_user']) && isset(session()->get('login')['impersonator_user']->is_active) && !empty(session()->get('login')['impersonator_user']) && session()->get('login')['impersonator_user']->is_active == 1){
    $extends = 'layout.advocateApp';
}else{
    $extends = 'layout.ecopyApp';
} ?>
@extends($extends)
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="dash-card dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class=" dashboard-bradcrumb">
                                <div class="left-dash-breadcrumb">
                                    <div class="page-title">
                                        <h5><i class="fa fa-file"></i> Case Search</h5>
                                    </div>
                                    <div class="form-response" id="msg" role="alert" data-auto-dismiss="5000"></div>
                                </div>
                                <div class="ryt-dash-breadcrumb">
                                    <div class="btns-sec">
                                        <!-- <a href="javascript:void(0)" class="quick-btn pull-right mb-2" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                        <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 ">
                            <div class="crnt-page-head">
                                <div class="current-pg-title">
                                    <h6>Case Search </h6>
                                </div>
                                <div class="current-pg-actions"> </div>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="dash-card dashboard-section">
                    <div class="row">
                        <div class="panel panel-default">
                            <?php
                            $attribute = array('class' => 'form-horizontal', 'id' => 'search_case_details', 'name' => 'search_case_details', 'autocomplete' => 'off', 'novalidate' => 'novalidate');
                            echo form_open('#', $attribute);
                            ?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="title-sec">
                                <h5 class="unerline-title">Search By</h5>
                            </div>
                            <div style="text-align: left;">
                                <?php
                                if (!empty(getSessionData('MSG'))) {
                                    echo getSessionData('MSG');
                                }
                                if (!empty(getSessionData('msg'))) {
                                    echo getSessionData('msg');
                                }
                                ?>
                                <br>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label class="radio-inline input-lg"><input type="radio" checked name="rdbtn_select" id="radioct" value="1"> Case No. &nbsp;</label><label class="radio-inline input-lg"><b>&nbsp;</b> </label>
                                    <label class="radio-inline input-lg"><input type="radio" id="radiodn" name="rdbtn_select" value="0">Diary No.</label>
                                </div>
                            </div>
                            <br>
                            <div id="search_case">
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="row">
                                            <div class="row mb-3 w-100 align-items-center">
                                                <div class="col-12">
                                                    <label for="inputPassword6" class="col-form-label">Case Type <span style="color: red;">*</span></label>
                                                </div>
                                                <div class="col-12 pe-0">
                                                    <select id="selct" name="selct" class="form-select cus-form-ctrl"
                                                    aria-labelledby="application_type_addon">
                                                        <option value="-1">Select</option>
                                                        <?php
                                                        foreach ($caseType as $rows) {
                                                            ?>
                                                            <option value="<?php echo $rows->casecode ?>"><?php echo $rows->casename; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="row">
                                            <div class="row mb-3 w-100 align-items-center">
                                                <div class="col-12">
                                                    <label for="inputPassword6" class="col-form-label">Case No. <span style="color: red;">*</span></label>
                                                </div>
                                                <div class="col-12 pe-0">
                                                    <input class="form-control cus-form-ctrl" id="case_no" name="case_no" maxlength="6" placeholder="Case No." onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" type="text" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="row">
                                            <div class="row mb-3 w-100 align-items-center">
                                                <div class="col-12">
                                                    <label for="inputPassword6" class="col-form-label"> Case Year</label>
                                                </div>
                                                <div class="col-12 pe-0">
                                                    <select class="form-select cus-form-ctrl" aria-label="Default select example" id="case_yr" name="case_yr" style="width: 100%" required>
                                                        <option value="">Select Year</option>
                                                        <?php
                                                            $currently_selected = date('Y');
                                                            $earliest_year = 1950;
                                                            $latest_year = date('Y');
                                                            foreach (range($latest_year, $earliest_year) as $i) {
                                                                print '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                                            }
                                                            ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                    <div class="col-12">
                                                    <label for="inputPassword6" class="col-form-label"> &nbsp;</label>
                                                </div>
                                        <button type="button" class="quick-btn gray-btn" id="sub" value="SEARCH">Search</button>
                                    </div>
                                </div>
                            </div>
                            <div id="search_diary">
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="row">
                                            <div class="row mb-3 w-100 align-items-center">
                                                <div class="col-12">
                                                    <label for="t_h_cno" class="col-form-label">Diary No.</label>
                                                </div>
                                                <div class="col-12 pe-0">
                                                    <input class="form-control cus-form-ctrl" id="t_h_cno" name="t_h_cno" maxlength="5" placeholder="Diary No" type="text" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="row">
                                            <div class="row mb-3 w-100 align-items-center">
                                                <div class="col-12">
                                                    <label for="t_h_cyt" class="col-form-label"> Diary Year</label>
                                                </div>
                                                <div class="col-12 pe-0">
                                                    <select class="form-select cus-form-ctrl" aria-label="Default select example" id="t_h_cyt" name="t_h_cyt" style="width: 100%" required>
                                                        <option value="">Select Year</option>
                                                        <?php
                                                            $currently_selected = date('Y');
                                                            $earliest_year = 1950;
                                                            $latest_year = date('Y');
                                                            foreach (range($latest_year, $earliest_year) as $i) {
                                                                print '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                                            }
                                                            ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <div class="col-12">
                                            <label for="inputPassword6" class="col-form-label"> &nbsp;</label>
                                        </div>
                                        <button type="button" id="sub" class="quick-btn gray-btn">Search</button>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>

                            <br>
                            <div id="result"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
<script src="<?= base_url(); ?>assets/js/sweetalert.min.js"></script>
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/sweetalert.css">
<script type="text/javascript">
    var selectedNoOfOrder=0; 
    $(document).on('click', '#is_bail_order_addon', function () {
        var sel = $("#radioBtn").find('a.active').data('title');
        //alert(sel);
    });
    $(document).on('click', '#radioBtn a', function () {
    // $('#radioBtn a').on('click', function(){

        var sel = $(this).data('title');
        var tog = $(this).data('toggle');
        $('#'+tog).prop('value', sel);

        $('a[data-toggle="'+tog+'"]').not('[data-title="'+sel+'"]').removeClass('active').addClass('notActive');
        $('a[data-toggle="'+tog+'"][data-title="'+sel+'"]').removeClass('notActive').addClass('active');

        // var sel_active = $("#radioBtn .active").data('title');
        var bail_order = $("#radioBtn .active").data('title');
        if(bail_order == 'Y'){
            $("#num_copy").html('<option value="1">1</option>');
            $("#cop_mode").html('<option value="2">Counter</option>');
        }
        else{
            $("#num_copy").html('<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option>');
            $("#cop_mode").html('<option value="">Select</option><option value="1">By Speed Post</option><option value="2">Counter</option>');
        }
    });
    $(document).ready(function(){
        $("#search_diary").hide();
        $(document).on('click', '#radiodn', function () {
            $("#search_diary").show();
            $("#search_case").hide();
            $('#result').html('');
            $("#t_h_cno").prop('disabled', false);
            $("#t_h_cyt").prop('disabled', false);
            $("#selct").prop('disabled', true);
            $("#case_no").prop('disabled', true);
            $("#case_yr").prop('disabled', true);
            $("#t_h_cno").attr('required', true);
            $("#t_h_cyt").attr('required', true);
            $("#selct").val("-1");
            $("#case_no").val("");
            $("#case_yr").val("");
        });
        $(document).on('click', '#radioct', function () {
            $("#search_diary").hide();
            $("#search_case").show();
            $('#result').html('');
            $("#t_h_cno").prop('disabled', true);
            $("#t_h_cyt").prop('disabled', true);
            $("#t_h_cno").val("");
            $("#t_h_cyt").val("");
            $("#selct").removeAttr('disabled');
            $("#case_no").removeAttr('disabled');
            $("#case_yr").removeAttr('disabled');
            $("#t_h_cno").attr('required', false);
            $("#t_h_cyt").attr('required', false);
        });
                    
        function get_case_details_page(){
            var d_no=document.getElementById('t_h_cno').value;
            var d_yr=document.getElementById('t_h_cyt').value;
            get_res_all='';
            var t_h_cno, t_h_cyt, cstype, csno, csyr,fno;
            var regNum = new RegExp('^[0-9]+$');
            var chk_status=0;

            if($("#radioct").is(':checked')){
                cstype = $("#selct").val();
                csno = $("#case_no").val();
                csyr = $("#case_yr").val();
                chk_status=1;
                if(!regNum.test(cstype)){
                    alert("Please Select Casetype");
                    $("#selct").focus();
                    return false;
                }
                if(!regNum.test(csno)){
                    alert("Please Fill Case No in Numeric");
                    $("#case_no").focus();
                    return false;
                }
                if(!regNum.test(csyr)){
                    alert("Please Fill Case Year in Numeric");
                    $("#case_yr").focus();
                    return false;
                }
                if(csno == 0){
                    alert("Case No Can't be Zero");
                    $("#case_no").focus();
                    return false;
                }
                if(csyr == 0){
                    alert("Case Year Can't be Zero");
                    $("#case_yr").focus();
                    return false;
                }

            }
            else if($("#radiodn").is(':checked')){

                var t_h_cno=$('#t_h_cno').val();
                var t_h_cyt=$('#t_h_cyt').val();
                chk_status=2;
                if(!regNum.test(t_h_cno)){
                    alert("Please Fill Diary No in Numeric");
                    $("#case_no").focus();
                    return false;
                }
                if(!regNum.test(t_h_cyt)){
                    alert("Please Fill Diary Year in Numeric");
                    $("#case_yr").focus();
                    return false;
                }
                if(!t_h_cno)
                {
                    alert("Please enter Diary No.");
                    $('#t_h_cno').focus();
                    return false;
                }
                if(!t_h_cyt)
                {
                    alert("Please enter Diary Year");
                    $('#t_h_cyt').focus();
                    return false;
                }
                var fno = t_h_cno+t_h_cyt;
            }

            $.ajax({
                url:'<?php echo base_url('online_copying/get_case_details'); ?>',
                cache: false,
                async: true,
                beforeSend: function () {
                    $('#result').html('<table widht="100%" align="center"><tr><td>Loading...</td></tr></table>');
                },
                data: {d_no: d_no,d_yr:d_yr,fno:fno,ct:cstype,cn:csno,cy:csyr,chk_status:chk_status},
                type: 'POST',
                success: function(data, status) {
                    $('#result').html(data);
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        }
        $(document).on('click','#sub',function(){
            get_case_details_page();

        });



        var d_no = '<?php echo isset($_SESSION['session_d_no']) ? $_SESSION['session_d_no'] : '' ?>';
        var d_year = '<?php echo isset($_SESSION['session_d_year']) ? $_SESSION['session_d_year'] : '' ?>';
        if (d_no != '' && d_year != '') {
            document.getElementById('t_h_cno').value = d_no;
            document.getElementById('t_h_cyt').value = d_year;
            $('#radiodn').attr('checked', true);
            $("#search_diary").show();
            $("#search_case").hide();
            get_case_details_page();
        }


        $(document).on('change','#app_type',function(){
            var idd=$(this).val();
            var bail_order = $("#radioBtn .active").data('title');
            if(idd == 5){
                $("#cop_mode").html('<option value="3">Email</option>');
                $("#num_copy").html('<option value="1">1</option>');
                $(".is_chargable").removeClass("d-none");
            }
            else{
                if(bail_order == 'Y'){
                    $("#num_copy").html('<option value="1">1</option>');
                    $("#cop_mode").html('<option value="2">Counter</option>');
                }
                else{
                    $("#num_copy").html('<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option>');
                    $("#cop_mode").html('<option value="">Select</option><option value="1">By Speed Post</option><option value="2">Counter</option>');
                }
                $(".is_chargable").addClass("d-none");
            }
            $.ajax({
                url:'<?php echo base_url('online_copying/get_app_charge'); ?>',
                cache: false,
                async: true,
                data: {idd: idd},               
                type: 'POST',
                success: function(data, status) {
                    $('#sp_app_charge').html(data);
                    add_records();
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        });
                
              
        $(document).on('click','.cl_checks',function(){

            var app_type=$('#app_type').val();
            var num_copy=$('#num_copy').val();
            //   alert(app_type);
            if(app_type=='')
            {
                alert("Please select Application Category");
                $('.cl_checks').each(function(){
                    $(this).prop('checked',false);
                })
                return false;
            }
            else if(num_copy=='')
            {
                alert("Please enter No.of copies");
                $('#num_copy').focus();
                return false;
            }
            else 
            {
                //selectedNoOfOrder++;
                //alert();
                add_records();
            }
        });
             
        $(document).on('change','#num_copy',function(){
            add_records();
        });
        $(document).on('change','#cop_mode',function(){                  
            add_records();
        });
        $(document).on("click", "input[name='address_type_radio']", function () {
            add_records();
        });
    });


    $(document).on('click', '.cl_checks', function () {
        var order_type_id = $(this).data('order_type_id');
        var app_type = $("#app_type").val();
        if(order_type_id == 37){
            if(app_type !=5){
                $(this).prop('checked', false);
                alert('Soft Copy Requirement allowed with Application Category Digital Mode only');
            }
        }
    });
    function add_records()
    {
        
        $("#tb_added_doc").html("");
        var app_type=$('#app_type').val();
        var num_copy=$('#num_copy').val();
        var cop_mode=$('#cop_mode').val();
        if ($("#radio_address_type_1").is(':checked')) {
            var pincode = $("#radio_address_type_1").data('pincode');
        }
        else if ($("#radio_address_type_2").is(':checked')) {
            var pincode = $("#radio_address_type_2").data('pincode');
        }
        else if ($("#radio_address_type_3").is(':checked')) {
            var pincode = $("#radio_address_type_3").data('pincode');
        }
        var pincode_length = $.trim(pincode).length;
        if(pincode_length != 6){
            alert("Please select Address");
        }
        else if(app_type=='')
        {
            alert("Please select Application Category");
            $('.cl_checks').each(function(){
                $(this).prop('checked',false);
            })
            return false;
        }
        else if(num_copy=='')
        {
            alert("Please enter No.of copies");
            $('#num_copy').focus();
                return false;
            }
            else 
            {
                var o_array=[];var sno=0;      
                $('.cl_checks').each(function(){
                    var idd=$(this).attr('id');
                    if($(this).is(':checked'))
                    {
                        var i_array=[];
                        var sp_idd=idd.split('_'); 
                        var sporderdate=$('#sporderdate_'+sp_idd[1]).html();
                        var sptotalpages=$('#sptotalpages_'+sp_idd[1]).html();
                        var spjudgementorder=$('#spjudgementorder_'+sp_idd[1]).html();
                        var ischargable=$('#ischargable_'+sp_idd[1]).html();
                        var spjudgementordercode=$('#spjudgementordercode_'+sp_idd[1]).html();
                        var sp_certi_number_of_docs=$('#sp_certi_number_of_docs_'+sp_idd[1]).html();
                        var sp_certi_number_of_pages=$('#sp_certi_number_of_pages_'+sp_idd[1]).html();
                        var sp_uncerti_number_of_docs=$('#sp_uncerti_number_of_docs_'+sp_idd[1]).html();
                        var sp_uncerti_number_of_pages=$('#sp_uncerti_number_of_pages'+sp_idd[1]).html();
                        i_array[0]=sporderdate;
                        i_array[1]=sptotalpages;
                        i_array[2]=spjudgementorder;
                        i_array[3]=ischargable;
                        i_array[4]=spjudgementordercode;
                        i_array[5]=sp_certi_number_of_docs;
                        i_array[6]=sp_certi_number_of_pages;
                        i_array[7]=sp_uncerti_number_of_docs;
                        i_array[8]=sp_uncerti_number_of_pages;
                        o_array[sno]=i_array;
                        sno++;
                    }
                });
                var bail_order = $("#radioBtn .active").data('title');
                if(o_array == ''){
                    // alert('Please select applied for');
                }else{
                    $.ajax({
                        url:'<?php echo base_url('online_copying/get_tot_copy'); ?>',
                        cache: false,
                        async: true,
                        data: {app_type:app_type,num_copy:num_copy,cop_mode:cop_mode,pincode:pincode,bail_order:bail_order,o_array:o_array},
                        beforeSend: function () {
                            $('#tb_added_doc').html('<table widht="100%" align="center"><tr><td>Loading...</td></tr></table>');
                        },
                        type: 'POST',
                        success: function(data, status) {
                            $('#tb_added_doc').html("");
                            if(pincode_length == 6){
                                $('#tb_added_doc').html(data);
                            }                
                        },
                        error: function(xhr) {
                            alert("Error: " + xhr.status + " " + xhr.statusText);
                        }
                    });
                }
            }
        }
        var is_affidavit_uploaded = 0;
        $(document).on("change", ".affidavt_class", function () {
            $(".validation").remove(); // remove it
            var data1 = new FormData();
            data1.append('file',document.getElementById('affidavit').files[0]);          
            $.ajax({          
                url: "upload_documents.php?flag=affidavit",
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: data1,     
                beforeSend: function () {
                    $('.uploaded_affidavit_result').removeClass("d-none");
                    $('.uploaded_affidavit_result').html('<img src="../images/load.gif" height="28px" width="28px"/>');
                },
                type: 'post',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                success: function(data)  
                {               
                    if(data == 1){
                        is_affidavit_uploaded = 1;
                        $('.uploaded_affidavit_result').html('<i class="fa fa-check text-success" aria-hidden="true"></i>');
                    }
                    else{
                        $('.uploaded_affidavit_result').html('<span class="text-danger">'+data+'</span>');
                    }
                }  
            });         
        });
    
        var document_type = []; var order_date = []; var doc_detail = [];
        $(document).on("click", "#btn_case_verify", function () {
            var o_array=[];var sno=0; var copy_detail = '';
            $('.cl_checks').each(function(){
                var idd=$(this).attr('id');
                if($(this).is(':checked'))
                {
                    var i_array=[];
                    var sp_idd=idd.split('_');
                    var sporderdate=$('#sporderdate_'+sp_idd[1]).html();
                    var sptotalpages=$('#sptotalpages_'+sp_idd[1]).html();
                    var spjudgementordercode=$('#spjudgementordercode_'+sp_idd[1]).html();
                    var spjudgementorder=$('#spjudgementorder_'+sp_idd[1]).html();
                    var spfilepath=$('#spfilepath_'+sp_idd[1]).html();
                    var sp_certi_number_of_docs=$('#sp_certi_number_of_docs_'+sp_idd[1]).html();
                    var sp_certi_number_of_pages=$('#sp_certi_number_of_pages_'+sp_idd[1]).html();
                    var sp_uncerti_number_of_docs=$('#sp_uncerti_number_of_docs_'+sp_idd[1]).html();
                    var sp_uncerti_number_of_pages=$('#sp_uncerti_number_of_pages'+sp_idd[1]).html();
                    i_array[0]=sporderdate;
                    i_array[1] = sptotalpages;
                    i_array[2] = spjudgementordercode;
                    i_array[3] = spjudgementorder;
                    i_array[4] = spfilepath;
                    i_array[5]=sp_certi_number_of_docs;
                    i_array[6]=sp_certi_number_of_pages;
                    i_array[7]=sp_uncerti_number_of_docs;
                    i_array[8]=sp_uncerti_number_of_pages;
                    o_array[sno] = i_array;
                    copy_detail += sporderdate + ',' + sptotalpages + ',' + spjudgementordercode + ',' + spjudgementorder + ',' + spfilepath + ',' + sp_certi_number_of_docs + ',' + sp_certi_number_of_pages + ',' + sp_uncerti_number_of_docs + ',' + sp_uncerti_number_of_pages + '#';
                    sno++;
                }

            });
            var address_id = '';
            if ($("#radio_address_type_1").is(':checked')) {
                address_id = $("#radio_address_type_1").data('address_id');
            }
            else if ($("#radio_address_type_2").is(':checked')) {
                address_id = $("#radio_address_type_2").data('address_id');
            }
            else if ($("#radio_address_type_3").is(':checked')) {
                address_id = $("#radio_address_type_3").data('address_id');
            }
            
            var filed = '<?php echo isset($_SESSION["session_filed"]) ? $_SESSION["session_filed"] : '' ?>';
            
            var app_type = ''; var num_copy = ''; var cop_mode = ''; service_charges = '';
            var fee_in_stamp = ''; var amount_to_pay = ''; var postage = '';
            if(filed != 2){
                var app_type = $("#app_type").val();
                var num_copy = $("#num_copy").val();
                var cop_mode = $("#cop_mode").val(); 
                var service_charges = $('.amount_to_pay').data("service_charges");
                var fee_in_stamp = $('.amount_to_pay').data("fee_in_stamp");
                var amount_to_pay = $('.amount_to_pay').data("amount-to-pay");
                var postage = $('.amount_to_pay').data("postage");
            }

            // Initializing Variables With Regular Expressions
            // To Check Empty Form Fields.
            $('#show_error').html("");

            $(".validation").remove(); // remove it
            if($('input[type="checkbox"]:checked').length){
                selectedNoOfOrder=$('.cl_checks:checked').length
            }
            
             if($('.loadedOrders').val()){
                var loadedOrders=$('.loadedOrders').val();
             }else{
                var loadedOrders=0;
             }
            if(address_id.length == 0){
                $(".applicant_address").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Address Selection Required*</strong></div>');
                return false;
            } else if(filed == 4 && is_affidavit_uploaded == 0){
                $(".affidavt_class").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Affidavit Upload Required*</strong></div>');
                $(".affidavt_class").focus(); return false;
            } else if (app_type.length == 0 && filed != 2) {
                $("#app_type").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Application Category Required*</strong></div>');
                $("#app_type").focus(); return false;
            } else if (o_array.length == 0 && filed != 2) {
                $(".applied_check_boxes").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Applied For Required to Checked*</strong></div>');
                return false;
            } else if (cop_mode.length == 0 && filed != 2) {
                $("#cop_mode").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Copy Delivery Mode Required*</strong></div>');
                $("#cop_mode").focus(); return false;
            } else if ((cop_mode == 1 || cop_mode == 2) && (amount_to_pay == 0 || amount_to_pay == null)) {
                $("#tb_added_doc").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Unable to Calculate fee, Please check all inputs*</strong></div>');
                return false;
            } else if(loadedOrders!=selectedNoOfOrder){
            $(".above_error").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Selected Orders Completly Not loaded</strong></div>');

            } else{
            var redirect_url = '<?php echo base_url('online_copying/case_relation_verification'); ?>';
            swal({
                title: "Are you sure?",
                text: "Forwarding!",
                icon: "warning",
                buttons: true,
                showCancelButton: true,
                confirmButtonText: "OK",
                cancelButtonText: "Cancel",
                dangerMode: true,
            },function(willDelete){
                if (willDelete) {
                    //alert("ok"+is_affidavit_uploaded);          
                    // $.redirect(redirect_url, {address_id:address_id, app_type:app_type, num_copy:num_copy, cop_mode:cop_mode, copy_detail: copy_detail, service_charges: service_charges, fee_in_stamp:fee_in_stamp, amount_to_pay:amount_to_pay, postage:postage}); 
                    var form = $('<form>', {
                        'action': redirect_url,
                        'method': 'POST'
                    }).append($('<input>', {
                        'name': 'address_id',
                        'value': address_id,
                        'type': 'hidden'
                    })).append($('<input>', {
                        'name': 'app_type',
                        'value': app_type,
                        'type': 'hidden'
                    })).append($('<input>', {
                        'name': 'num_copy',
                        'value': num_copy,
                        'type': 'hidden'
                    })).append($('<input>', {
                        'name': 'cop_mode',
                        'value': cop_mode,
                        'type': 'hidden'
                    })).append($('<input>', {
                        'name': 'copy_detail',
                        'value': copy_detail,
                        'type': 'hidden'
                    })).append($('<input>', {
                        'name': 'service_charges',
                        'value': service_charges,
                        'type': 'hidden'
                    })).append($('<input>', {
                        'name': 'fee_in_stamp',
                        'value': fee_in_stamp,
                        'type': 'hidden'
                    })).append($('<input>', {
                        'name': 'amount_to_pay',
                        'value': amount_to_pay,
                        'type': 'hidden'
                    })).append($('<input>', {
                        'name': 'postage',
                        'value': postage,
                        'type': 'hidden'
                    }));

                    $(document.body).append(form);
                    form.submit();         
                }
                else {
                    //swal("Record is safe!");
                }
            });
        }
    });


    $(document).on("click", "#btn_payment", function () {
        //for payment / digital copy
        
        var o_array=[];var sno=0; var copy_detail = ''; var bail_order = $("#radioBtn .active").data('title');
        var total_documents = 0;
        $('.cl_checks').each(function(){
            var idd=$(this).attr('id');
            if($(this).is(':checked'))
            {
                var i_array=[];
                var sp_idd=idd.split('_');
                var sporderdate=$('#sporderdate_'+sp_idd[1]).html();
                var sptotalpages=$('#sptotalpages_'+sp_idd[1]).html();
                var spjudgementordercode=$('#spjudgementordercode_'+sp_idd[1]).html();
                var spjudgementorder=$('#spjudgementorder_'+sp_idd[1]).html();
                var spfilepath=$('#spfilepath_'+sp_idd[1]).html();
                var sp_certi_number_of_docs=$('#sp_certi_number_of_docs_'+sp_idd[1]).html();
                var sp_certi_number_of_pages=$('#sp_certi_number_of_pages_'+sp_idd[1]).html();
                var sp_uncerti_number_of_docs=$('#sp_uncerti_number_of_docs_'+sp_idd[1]).html();
                var sp_uncerti_number_of_pages=$('#sp_uncerti_number_of_pages'+sp_idd[1]).html();
                i_array[0]=sporderdate;
                i_array[1] = sptotalpages;
                i_array[2] = spjudgementordercode;
                i_array[3] = spjudgementorder;
                i_array[4] = spfilepath;
                i_array[5]=sp_certi_number_of_docs;
                i_array[6]=sp_certi_number_of_pages;
                i_array[7]=sp_uncerti_number_of_docs;
                i_array[8]=sp_uncerti_number_of_pages;
                o_array[sno] = i_array;
                copy_detail += sporderdate + ',' + sptotalpages + ',' + spjudgementordercode + ',' + spjudgementorder + ',' + spfilepath + ',' + sp_certi_number_of_docs + ',' + sp_certi_number_of_pages + ',' + sp_uncerti_number_of_docs + ',' + sp_uncerti_number_of_pages + ',' + spfilepath + '#';
                sno++;
                total_documents++;
            }
        });

        var address_id = '';
        if ($("#radio_address_type_1").is(':checked')) {
            address_id = $("#radio_address_type_1").data('address_id');
        }
        else if ($("#radio_address_type_2").is(':checked')) {
            address_id = $("#radio_address_type_2").data('address_id');
        }
        else if ($("#radio_address_type_3").is(':checked')) {
            address_id = $("#radio_address_type_3").data('address_id');
        }
        
        var filed = '<?php echo isset($_SESSION["session_filed"]) ? $_SESSION["session_filed"] : '' ?>';
        var app_type = $("#app_type").val();
        var num_copy = $("#num_copy").val();
        var cop_mode = $("#cop_mode").val(); 
        var service_charges = $('.amount_to_pay').data("service_charges");
        var fee_in_stamp = $('.amount_to_pay').data("fee_in_stamp");
        var amount_to_pay = $('.amount_to_pay').data("amount-to-pay");
        var postage = $('.amount_to_pay').data("postage");

        // Initializing Variables With Regular Expressions
        var name_regex = /^[a-zA-Z ]+$/;
        var email_regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/; // /^[w-.+]+@[a-zA-Z0-9.-]+.[a-zA-z0-9]{2,4}$/;
        var add_regex = /^[a-zA-Z0-9\s,.'-]{3,}$/;   //   /^[0-9a-zA-Z ]+$/;
        var zip_regex = /^[0-9]{6,}$/;

        // To Check Empty Form Fields.
        $('#show_error').html("");

        $(".validation").remove(); // remove it
        if($('input[type="checkbox"]:checked').length){
                selectedNoOfOrder=$('.cl_checks:checked').length
                
            }
        if($('.loadedOrders').val()){
                var loadedOrders=$('.loadedOrders').val();
                //alert(loadedOrders);
             }else{
                var loadedOrders=0;
             }
        
        
        if(address_id.length == 0){
            $(".applicant_address").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Address Selection Required*</strong></div>');
            return false;
        }
        else if (app_type.length == 0 && filed != 2) {
            $("#app_type").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Application Category Required*</strong></div>');
            $("#app_type").focus(); return false;
        }
        else if (bail_order == 'Y' && (num_copy > 1 || cop_mode == 3 || app_type == 5 || total_documents > 1)) {
            $("#radioBtn").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Please check rule for Ist Bail Order *</strong></div>');
            $("#radioBtn").focus();
            return false;
        }
        else if (o_array.length == 0 && filed != 2) {
            $(".applied_check_boxes").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Applied For Required to Checked*</strong></div>');
            return false;
        } else if (cop_mode.length == 0 && filed != 2) {
            $("#cop_mode").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Copy Delivery Mode Required*</strong></div>');
            $("#cop_mode").focus(); return false;
        } else if ((cop_mode == 1 || cop_mode == 2) && (amount_to_pay == 0 || amount_to_pay == null)) {
            $("#tb_added_doc").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Unable to Calculate fee, Please check all inputs*</strong></div>');
            return false;
        } else if($('input[name=confirm_validate]:checked').val() != 'confirm'){
            $(".above_error").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Select on Check Box - Click on I Agree to Terms & Conditions</strong></div>');
            return false;
        }else if(loadedOrders!=selectedNoOfOrder){
            $(".above_error").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1"><strong>Selected Orders Completly Not loaded</strong></div>');

        }else{
            var redirect_url = '<?php echo base_url('online_copying/sci_request'); ?>';
            swal({
                title: "Are you sure?",
                text: "Forwarding!",
                icon: "warning",
                buttons: true,
                showCancelButton: true, // Explicitly show cancel button
                confirmButtonText: "OK",
                cancelButtonText: "Cancel",
                dangerMode: true,
            },function(willDelete) {
                if (willDelete) {
                    // var params = {
                    //     address_id: address_id,
                    //     app_type: app_type,
                    //     num_copy: num_copy,
                    //     cop_mode: cop_mode,
                    //     copy_detail: copy_detail,
                    //     service_charges: service_charges,
                    //     fee_in_stamp: fee_in_stamp,
                    //     amount_to_pay: amount_to_pay,
                    //     postage: postage,
                    //     bail_order: bail_order
                    // };
                    // redirectWithParams(redirect_url, params);
                    // $.redirect(redirect_url, {address_id:address_id, app_type:app_type, num_copy:num_copy, cop_mode:cop_mode, copy_detail: copy_detail, service_charges:service_charges, fee_in_stamp:fee_in_stamp, amount_to_pay:amount_to_pay, postage:postage,bail_order:bail_order});

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = redirect_url;

                    // Append hidden inputs for each parameter
                    for (const [key, value] of Object.entries({
                    address_id,
                    app_type,
                    num_copy,
                    cop_mode,
                    copy_detail,
                    service_charges,
                    fee_in_stamp,
                    amount_to_pay,
                    postage,
                    bail_order
                    })) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                    }

                    // Append the form to the body and submit it
                    document.body.appendChild(form);
                    form.submit();
                }
                else {
                    //swal("Record is safe!");
                }
            });
        }
    });

    // function isNumber(evt) {
    //     evt = (evt) ? evt : window.event;
    //     var charCode = (evt.which) ? evt.which : evt.keyCode;
    //     if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    //         return false;
    //     }
    //     return true;
    // }
        
    $(document).on("change", "#affidavit", function () {
        var fileName = $(this).val().replace('C:\\fakepath\\', " ");
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    });

    $(document).on('click', '.already_id_proof_verified_edit', function () {
        $(".id_proof_validate1 .id_proof_validate2").removeClass("d-none");
        $(".attach_documents").removeClass("d-none");
        $(".id_proof_edit_permission").addClass("d-none");

        is_id_uploaded = 0;
    });

    $(document).on('click', '.btn_more_address', function () {
        var more_less = $(".btn_more_address").html();
        if(more_less == 'more...'){
            $(".btn_more_address").html("less...");
            $(".address_toggle_2").removeClass("d-none");
        }
        if(more_less == 'less...'){
            $(".btn_more_address").html("more...");
            $(".address_toggle_2").addClass("d-none");
        }
    });
    
</script>
@endpush