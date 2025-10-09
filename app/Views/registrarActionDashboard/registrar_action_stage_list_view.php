<div class="right_col" role="main">

    <div class="row"> 
        <script>
            function openModal() {
                document.getElementById('modal_loader').style.display = 'block';
                document.getElementById('loader_img').style.display = 'block';
                document.getElementById('fade_loader').style.display = 'block';
            }

            function closeModal() {
                document.getElementById('modal_loader').style.display = 'none';
                document.getElementById('fade_loader').style.display = 'none';
            }

        </script>

        <div id="fade_loader"></div>
        <div id="modal_loader">
            <img id="loader_img" style="margin-top: 30px;margin-left: 30px;display:block;" src="<?php echo base_url(); ?>assets/images/loading-data.gif" />
        </div>

    </div>
    <div class="modal fade" id="bsModal3" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close_cis" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="mySmallModalLabel"></h4>
                </div>
                <div class="modal-body formresponse">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default close_cis" >OK</button>

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="form-response" id="msg" >
            <?php
            if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
                echo $_SESSION['MSG'];
            } unset($_SESSION['MSG']);
            ?></div>
        <div class="x_panel">
            <div class="x_title"> <h3><?= htmlentities($tabs_heading, ENT_QUOTES) ?>
                    <!--<span style="float:right;"> <a class="btn btn-info" type="button" onclick="window.history.back()" /> Back</a></span>-->
                </h3> </div>
            <div class="x_content">
                <div class="table-wrapper-scroll-y my-custom-scrollbar ">
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr class="success input-sm" role="row" >
                            <th width="5%">Sl. No.</th>
                            <th width="20%">Case Details</th>
                            <th width="15%">Advocate Details</th>
                            <th width="15">Mentioning Details</th>
                            <th width="30%">Synopsis of Urgency</th>
                            <th width="15%">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i=1;
                        foreach($tableData as $data){ ?>
                            <tr>
                                <td><?=$i?></td>
                                <td><?=$data->diary_no?>/<?=$data->diary_year?>
                                    <br/><?=$data->reg_no_display?>
                                    <br/><?=$data->cause_title?></td>
                                <td><?=$data->arguing_counsel_name?>
                                    <br/><?=$data->arguing_consel_mobile_no?>
                                    <br/><?=$data->arguing_counsel_email?></td>
                                <td><?=!empty($data->is_for_fixed_date)?'Fixed Date':'Date Range'?>
                                    <br/><?=!empty($data->is_for_fixed_date)?$data->requested_listing_date:$data->requested_listing_date_range?></td>
                                <td><div  id="showmore_<?=$data->id?>" class="showmore"><?=$data->synopsis_of_emergency?></div>
                                    <a id="more" onclick='javascript:expandDiv("showmore_<?=$data->id?>");'>Read more </a>
                                </td>
                                <td>
                                    <?php
                                    if($this->session->userdata['login']['ref_m_usertype_id'] != USER_REGISTRAR_ACTION){
                                        echo empty($data->approval_status)?'Pending':'';
                                    }
                                    elseif (empty($data->approval_status)){ ?>
                                        <select id="ddlActionType_<?=$data->id?>" name="ddlActionType" onchange="javascript:showHideApprovalDate(this);">
                                            <option value="">Select Action</option>
                                            <option value="a">Approve</option>
                                            <option value="r">Reject</option>
                                        </select>

                                        <input type="button" class="btn btn-success right" id="" name="" value="Save" style="float:right;">
                                    <?php   }
                                    ?>
                                </td>
                            </tr>
                            <?php
                            $i++;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
<style>
    th{font-size: 13px;color: #000;} 
    td{font-size: 13px;color: #000;} 
</style>   <!-- /page content -->

<!-- footer content -->
<script>



    function TransferToSection(frm_num) {

        y = confirm("Are you sure want to transfer ?");
        if (y == false)
        {
            return false;
        }
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#loading_spinner').show();
        $.ajax({
            type: "POST",
            url: "<?= base_url(); ?>admin/EfilingAction/transfer_to_section",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, form_submit: frm_num},
            success: function (result) {

                //    openModal();
                var responce = result.split('||||');

                if (responce[0] == 'SUCCESS') {
                    window.location.href = '<?= base_url() ?>newcase/defaultController/' + responce[1];
                } else if (responce[0] == 'ERROR') {
                    $('#msg').show();
                    $('#msg').html(responce[1]);
                } else
                {
                    window.location.href = '<?= base_url() ?>adminDashboard';
                }
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function (result) {
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
    function hideMessageDiv() {
        document.getElementById('msg').style.display = "none";
    }
    setTimeout("hideMessageDiv()", 5000);



</script>

<script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>

<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery-ui.css">

<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/sha256.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/jquery.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/jquery-ui.min.js"></script>

<script>
    $('.scr_date').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        maxDate: new Date(),
        dateFormat: "dd-mm-yy",
        yearRange: '2000:2099'
    });
</script>

<script>


    function submitAction_CIS(get_id) {//alert(get_id);
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        openModal();
        $.ajax({
            type: "POST",
            url: "<?= base_url('getCIS_status/get_efiling_num_status') ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, form_submit: get_id},
            success: function (result) {


                closeModal();
                var resArr = result.split('@@@');

                if (resArr[0] == 1) {

                    alert(resArr[1]);
                } else if (resArr[0] == 2) {
                    $('#msg_res').show();
                    $("#bsModal3").modal('show');
                    $("#mySmallModalLabel").html(resArr[1]);
                    $(".formresponse").html(resArr[2]);

                } else {

                    alert(resArr[0]);
                    location.reload();

                }
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });

            },
            error: function (result) {
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
    $(document).ready(function () {
        $(".close_cis").click(function () {
            $("#bsModal3").modal('hide');
            location.reload();
        });

    });

    function advocateRegister() {
        alert('Advocate\'s (who filed this efiling number) details are not present in your local CIS. His/her information is present under " Registeration " menu on left side. Please first get his/her details updated in local CIS and then update his/her status in efiling application from the same menu.')
    }
    function expandDiv(divId) {
        $('#'+divId).css({
            'height': 'auto'
        })
        return false;
    }
    function showHideApprovalDate(ddlId) {
        console.log("I am here");
        console.log(ddlId.id);
        alert(ddlId.value);
    }
</script>