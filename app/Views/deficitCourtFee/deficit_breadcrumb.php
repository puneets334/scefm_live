<div class="right_col" role="main">
    <div id="page-wrapper">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-response" id="msg"><?php
                    if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) { echo $_SESSION['MSG']; } unset($_SESSION['MSG']);
                    ?>
                </div>
                <?php echo $this->session->flashdata('msg'); ?>
                <div class="view_data">
                    <div id="modal_loader">
                        <img id="loader_img" style="margin-top: 30px;margin-left: 30px;display:block;" src="<?php echo base_url(); ?>assets/images/loading-data.gif" />
                    </div>
                    <div class="clearfix"></div>

                    <?php //echo remark_preview($_SESSION['deficitPay_details'][0], $_SESSION['efiling_details']['stage_id']); ?>
                    <?php
                    //print_r($_SESSION['deficitPay_details']);exit();
                    //echo $_SESSION['deficitPay_details'][3];exit();
                    //echo remark_preview($_SESSION['deficitPay_details'][3], $_SESSION['deficitPay_details'][2]); ?>

                    <div class="x_panel">
                        <div class="x_title">
                            <div class="registration-process" style="float: left; border: none;">
                                <h2><i class="glyphicon glyphicon-book"></i> Deficit Court Fee Payment  </h2>
                                <div class="clearfix"></div>
                            </div>
                            <div style="float: right">

                                <?php
                                if (isset($_SESSION['deficitPay_details'][3]) && !empty($_SESSION['deficitPay_details'][3])) {
                                    echo '<a href="javascript::void(0); " class="btn btn-danger btn-sm"  style="color: #ffffff"><strong id="copyTarget_EfilingNumber">' . htmlentities(efile_preview($_SESSION['deficitPay_details'][3]), ENT_QUOTES) . '</strong></a>&nbsp;<strong id="copyButton" class="btn btn-danger btn-sm"  style="font-size: 14px;color:greenyellow;"><span class="glyphicon glyphicon-copy" style="font-size:14px;color:#ffffff;"></span></strong> ';
                                }
                                ?>
                                &nbsp; <!--<a class="btn btn-default btn-sm" href="<?php /*echo base_url('history/efiled_case/view'); */?>"> eFiling History</a>-->
                                &nbsp; <a class="btn btn-info btn-sm" type="button" onclick="window.history.back()"> Back</a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>

