<div class="panel panel-default">
    <h4 style="text-align: center;color: #31B0D5">Earlier Courts Information </h4>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12"> 

                <div class="form-group">
                    <label class="control-label col-md-1 col-sm-12 col-xs-12 input-sm col-md-offset-4" style="text-align: right;">Select Court <span style="color: red">*</span> :</label>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <select name="court_type" id="court_type" onchange="show_form_hc(this.value)" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                            <option value="<?php echo_data(url_encryption('1$$subCourtType')) ?>" title="Select">Lower Court</option>
<!--                            <option value="--><?php //echo_data(url_encryption('2$$subCourtType')) ?><!--" title="Select">Quasi Judicial</option>-->
                            <option value="<?php echo_data(url_encryption('3$$subCourtType')) ?>" title="Select">High Court</option>
                            <option value="<?php echo_data(url_encryption('5$$subCourtType')) ?>" title="Select">State Agency</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12" id="loader_div" style="">
       </div>
        <div class="clearfix"></div>
        <div id="subordinate_info_frm">
            <?php include 'subordinate_info_form.php'; ?> 
        </div>
        <div id="quasi_judicial_info_frm" style="display: none;">
            <?php include 'subordinate_quasi_jug_info_form.php'; ?> 
        </div>
        <div id="hc_info_frm" style="display: none;">
            <?php include 'subordinate_hc_info_form.php'; ?> 
        </div>

        <div id="state_agency_form" style="display: none;">
            <?php include 'subordinate_state_agency_form.php'; ?>
        </div>
        <?php
        if (isset($subordinate_court_details) && !empty($subordinate_court_details)) {
            include 'subordinate_listing_view.php';
        }
        ?>

    </div>

</div>

<script type="text/javascript">
    function show_form_hc(val) {
        $("#sub_high_court_info_search_case,#fir_policeStation,#fir_number, #fir_year").prop("disabled", false);
        $("#fir_policeStation,#fir_number, #fir_year").prop("disabled", false);
        $("#police_station_name").val('');
        $('#firdetailsdiv').prop('checked', false);
        $('#firdetailsdiv').val('');
        $('#firdetails').hide();
        $('#fir_details_div').prop('checked', false);
        $('#fir_details_div').val('');
        $('#fir_details').hide();
        if (val == '<?php echo_data(url_encryption('1$$subCourtType')); ?>') {
            document.getElementById('subordinate_info_frm').style.display = 'block';
            document.getElementById('hc_info_frm').style.display = 'none';
            document.getElementById('state_agency_form').style.display = 'none';
            document.getElementById('quasi_judicial_info_frm').style.display = 'none';
            $("#appellate_n_trail_court select").attr('disable',false);
            $("#appellate_n_trail_court select").css('display','block');
        } else if (val == '<?php echo_data(url_encryption('2$$subCourtType')); ?>') {
            document.getElementById('quasi_judicial_info_frm').style.display = 'block';
            document.getElementById('subordinate_info_frm').style.display = 'none';
            document.getElementById('hc_info_frm').style.display = 'none';
            document.getElementById('state_agency_form').style.display = 'none';
        } else if (val == '<?php echo_data(url_encryption('3$$subCourtType')); ?>') {
            document.getElementById('hc_info_frm').style.display = 'block';
            document.getElementById('subordinate_info_frm').style.display = 'none';
            document.getElementById('quasi_judicial_info_frm').style.display = 'none';
            document.getElementById('state_agency_form').style.display = 'none';
            $("#appellate_n_trail_court select").attr('disable',true);
            $("#appellate_n_trail_court select").css('display','none');
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type:"POST",
                url: "<?php echo base_url('caveat/AjaxCalls/getHighCourt'); ?>",
                cache:false,
                async:false,
                success:function (res){
                    $("#hc_court").html(res);
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error:function (e){
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }

            });
        }
        else if (val == '<?php echo_data(url_encryption('5$$subCourtType')); ?>') {
            document.getElementById('hc_info_frm').style.display = 'none';
            document.getElementById('subordinate_info_frm').style.display = 'none';
            document.getElementById('quasi_judicial_info_frm').style.display = 'none';
            $("#appellate_n_trail_court select").attr('disable',true);
            $("#appellate_n_trail_court select").css('display','none');
            document.getElementById('state_agency_form').style.display = 'block';
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type:"POST",
                url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_icmis_state_list'); ?>",
                cache:false,
                async:false,
                success:function (res){
                    $("#state_agency_state").html(res);
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error:function (e){
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }

            });
        }
    }




</script>