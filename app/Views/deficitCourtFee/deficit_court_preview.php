<?php $this->load->view('deficitCourtFee/deficit_breadcrumb'); ?>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="text-right">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
            <?php
            if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON) {
               // echo "jfbdskjfbdsjds";exit();
                //if ($_SESSION['efiling_details']['stage_id'] != Draft_Stage) {
                $allowed_users_array = array(Initial_Approaval_Pending_Stage,I_B_Defects_Cured_Stage,Initial_Defects_Cured_Stage);
                if (in_array($_SESSION['efiling_details']['stage_id'], $allowed_users_array)) {
                    ?>
                    <a class="btn btn-success btn-sm" target="_blank" href="<?php echo base_url('acknowledgement/view'); ?>">
                        <i class="fa fa-download blink"></i> eFiling Acknowledgement
                    </a>
                    <?php
                }
            }
            ?>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 visible-lg visible-md">
            <button class="btn btn-primary btn-sm openall" style="float: right">Expand All <i class="fa fa-eye"></i></button>
            <button class="btn btn-info btn-sm closeall hidden" style="float: right">Collapse All <i class="fa fa-eye-slash"></i></button>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="x_panel" style="background: #EDEDED;">
        <div class="col-sm-11">
            <a href="#demo_1" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-minus" style="float: right;"></i> <b> Case Details</b></a>
            <div class="collapse in" id="demo_1">
                <div class="x_panel">
                    <?php //$this->load->view('case_details/case_details_view', $data); ?>
                    <?php $this->load->view('deficitCourtFee/deficit_case_details_view', $data); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-11">
                <a href="#demo_10" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i><?php if(!isset($payment_details) || empty($payment_details)){?><font style="color:red;"> <b>Fees Paid</b></font><?php } else{?> <b>Fees Paid</b><?php } ?></a>
                <div class="collapse in" id="demo_10">
                    <div class="x_panel">
                        <!--<div class="text-right"><a href="<?php /*echo base_url('IA/courtFee'); */?>"><i class="fa fa-pencil"></i></a></div>-->
                        <?php $this->load->view('shcilPayment/payment_list_view'); ?>
                    </div>
                </div>
            </div>
        </div>
</div>

</div>
</div>
</div>
</div>
</div>
</div>
</div>

<script>
    $(document).ready(function () {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('newcase/Ajaxcalls/load_document_index'); ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, res: 1},
            success: function (data) {
                $('#index_data').html(data);
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
    });
</script>
