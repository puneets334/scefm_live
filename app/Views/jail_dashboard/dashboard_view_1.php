<?php $label_font_size = "14px;"; ?>
<div class="right_col" role="main">
    <div id="page-wrapper">
        <?php echo $this->session->flashdata('msg'); ?>
        <div class="form-response" id="msg"><?php
            if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
                echo $_SESSION['MSG'];
            } unset($_SESSION['MSG']);
            ?>
        </div>
        <?php
        $users_array = array(JAIL_SUPERINTENDENT);
        if (in_array($_SESSION['login']['ref_m_usertype_id'], $users_array)) {
            ?>
            <?php
            $lbl_section_head = 'eFiling';
            ?>
            <div class="row" >
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="x_panel tile fixed_height_320 overflow_hidden">
                        <div class="x_content table-responsive">
                            <table class="" style="width:100%">
                                <tbody>
                                <tr>
                                    <th style="width:37%;"><p></p></th>
                                    <th>
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                            <p class="">Stages</p>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                            <p class="text_position" >Count</p>
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <td><canvas class="canvasDoughnut" width="190" height="190" data-zr-dom-id="zr_0" style="width: 360px;width: 361px;width: 360px;width: 360px;"></canvas></td>
                                    <td>
                                        <table class="tile_info">
                                            <tbody>
                                            <tr>
                                                <?php
                                                if ($count_efiling_data[0]->total_drafts == 0) {
                                                    $href = "javascript:void(0)";
                                                } else {
                                                    $href = base_url("jail_dashboard/stage_list/" . htmlentities(url_encryption(Draft_Stage, ENT_QUOTES)));
                                                }
                                                ?>
                                                <td><a href="<?php echo $href; ?>"><p><i class="fa fa-square aero "></i>Draft</p></a></td>
                                                <td><a href="<?php echo $href; ?>"<p> <?php echo htmlentities($count_efiling_data[0]->total_drafts, ENT_QUOTES); ?> </p></a></td>
                                            </tr>
                                            <tr>
                                                <?php
                                                if ($count_efiling_data[0]->total_pending_acceptance == 0) {
                                                    $href = "javascript:void(0)";
                                                } else {
                                                    $href = base_url("jail_dashboard/stage_list/" . htmlentities(url_encryption(Initial_Approaval_Pending_Stage, ENT_QUOTES)));
                                                }
                                                ?>
                                                <td><a href="<?php echo $href; ?>"><p><i class="fa fa-square purple "></i>Pending Approval</p></a></td>
                                                <td><a href="<?php echo $href; ?>"<p> <?php echo htmlentities($count_efiling_data[0]->total_pending_acceptance, ENT_QUOTES); ?> </p></a></td>
                                            </tr>
                                            <tr>
                                                <?php
                                                if ($count_efiling_data[0]->total_rejected_cases == 0) {
                                                    $href = "javascript:void(0)";
                                                } else {
                                                    $href = base_url("jail_dashboard/stage_list/" . htmlentities(url_encryption(I_B_Rejected_Stage, ENT_QUOTES)));
                                                }
                                                ?>
                                                <td><a href="<?php echo $href; ?>"><p><i class="fa fa-square orange "></i>Rejected</p></a></td>
                                                <td><a href="<?php echo $href; ?>"<p> <?php echo htmlentities($count_efiling_data[0]->total_rejected_cases, ENT_QUOTES); ?> </p></a></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>
        <div class="clearfix"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        /*$('#prodTabs a[href="#today"]').trigger('click');
        if (typeof (Chart) === 'undefined') {
            return;
        }*/

        console.log('init_chart_doughnut');

        if ($('.canvasDoughnut').length) {

            var chart_doughnut_settings = {
                type: 'doughnut',
                tooltipFillColor: "rgba(51, 51, 51, 51, 51, 51, 51, 0.76)",
                data: {
                    labels: [
                        "Draft",
                        "Pending Approval",
                        "Rejected"
                    ],
                    datasets: [{
                        data: [
                            <?php echo htmlentities($count_efiling_data[0]->total_drafts, ENT_QUOTES); ?>,
                            <?php echo htmlentities($count_efiling_data[0]->total_pending_acceptance, ENT_QUOTES); ?>,
                            <?php echo htmlentities($count_efiling_data[0]->total_rejected_cases, ENT_QUOTES); ?>],
                        backgroundColor: [
                            "#BDC3C7",
                            "#9B59B6",
                            "#FF7F50"
                        ],
                        hoverBackgroundColor: [
                            "#9CC2CB",
                            "#9B59B6",
                            "#FF7F50"
                        ]
                    }]
                },
                options: {
                    legend: false,
                    responsive: false
                }
            }

            $('.canvasDoughnut').each(function () {
                var chart_element = $(this);
                var chart_doughnut = new Chart(chart_element, chart_doughnut_settings);
            });

        }
    });

    $(window).on('load', function () {
        $('#Objection_model').modal('show');
    });
</script>
<style>
    .yellow{
        color: #f0ad4e;
    }
    .orange{
        color:#FF7F50;
    }
    .dark_blue{
        color:#0040ff;
    }
    p:hover {
        background-color: #EDEDED !important;
    }.text_position{
         padding-left: 60%;
     }
</style>
