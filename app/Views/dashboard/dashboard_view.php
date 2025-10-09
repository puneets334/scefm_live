<?php $label_font_size = "14px;"; ?>
<div class="right_col" role="main">
    <div id="page-wrapper">
        <?php echo $this->session->flashdata('msg'); ?>
        <div class="form-response" id="msg"><?php
            if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
                echo $_SESSION['MSG'];
            }
            unset($_SESSION['MSG']);
            ?>
        </div>
        <?php
        $users_array = array(USER_ADVOCATE, USER_CLERK, USER_DEPARTMENT);
        if (in_array($_SESSION['login']['ref_m_usertype_id'], $users_array)) {
            ?>
            <?php
            $this->load->view('myUpdates/myUpdates_ribbon_view');
            ?>
            <?php
            $lbl_section_head = 'eFiling';
            ?>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="x_panel tile fixed_height_320 overflow_hidden">
                        <div class="x_title visible-lg visible-md">
                            <h2><?php echo $lbl_section_head; ?> (Before Acceptance by Court)</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_title visible-sm visible-xs">
                            <strong><?php echo $lbl_section_head; ?> (Before Acceptance by Court)</strong>
                            <div class="clearfix"></div>
                        </div>

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
                                            <p class="text_position">Count</p>
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <canvas class="canvasDoughnut" width="190" height="190" data-zr-dom-id="zr_0"
                                                style="width: 360px;width: 361px;width: 360px;width: 360px;"></canvas>
                                    </td>
                                    <td>
                                        <table class="tile_info">
                                            <tbody>
                                            <tr>
                                                <?php
                                                if ($count_efiling_data[0]->total_drafts == 0) {
                                                    $href = "javascript:void(0)";
                                                } else {
                                                    $href = base_url("dashboard/stage_list/" . htmlentities(url_encryption(Draft_Stage, ENT_QUOTES)));
                                                }
                                                ?>
                                                <td><a href="<?php echo $href; ?>"><p><i class="fa fa-square aero "></i>Draft
                                                        </p></a></td>
                                                <td><a href="<?php echo $href; ?>"
                                                    <p> <?php echo htmlentities($count_efiling_data[0]->total_drafts, ENT_QUOTES); ?> </p></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <?php
                                                if ($count_efiling_data[0]->total_pending_acceptance == 0) {
                                                    $href = "javascript:void(0)";
                                                } else {
                                                    $href = base_url("dashboard/stage_list/" . htmlentities(url_encryption(Initial_Approaval_Pending_Stage, ENT_QUOTES)));
                                                }
                                                ?>
                                                <td><a href="<?php echo $href; ?>"><p><i
                                                                    class="fa fa-square purple "></i>Pending Approval
                                                        </p></a></td>
                                                <td><a href="<?php echo $href; ?>"
                                                    <p> <?php echo htmlentities($count_efiling_data[0]->total_pending_acceptance, ENT_QUOTES); ?> </p></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <?php
                                                if ($count_efiling_data[0]->total_not_accepted == 0) {
                                                    $href = "javascript:void(0)";
                                                } else {
                                                    $href = base_url("dashboard/stage_list/" . htmlentities(url_encryption(Initial_Defected_Stage, ENT_QUOTES)));
                                                }
                                                ?>
                                                <td><a href="<?php echo $href; ?>"><p><i class="fa fa-square red "></i>For
                                                            Compliance</p></a></td>
                                                <td><a href="<?php echo $href; ?>"
                                                    <p> <?php echo htmlentities($count_efiling_data[0]->total_not_accepted, ENT_QUOTES); ?> </p></a>
                                                </td>
                                            </tr>
                                            <?php
                                            $xyz = FALSE;
                                            if ($xyz) {
                                                ?>
                                                <tr>
                                                    <?php
                                                    if ($count_efiling_data[0]->deficit_crt_fee == 0) {
                                                        $href = "javascript:void(0)";
                                                    } else {
                                                        $href = base_url("dashboard/stage_list/" . htmlentities(url_encryption(DEFICIT_COURT_FEE, ENT_QUOTES)));
                                                    }
                                                    ?>
                                                    <td><a href="<?php echo $href; ?>"><p><i
                                                                        class="fa fa-square dark_blue"></i>Pay Deficit
                                                                Fee</p></a></td>
                                                    <td><a href="<?php echo $href; ?>"
                                                        <p> <?php echo htmlentities($count_efiling_data[0]->deficit_crt_fee, ENT_QUOTES); ?> </p></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <?php
                                                if ($count_efiling_data[0]->total_pending_scrutiny == 0) {
                                                    $href = "javascript:void(0)";
                                                } else {
                                                    $href = base_url("dashboard/stage_list/" . htmlentities(url_encryption(I_B_Approval_Pending_Stage, ENT_QUOTES)));
                                                }
                                                ?>
                                                <td><a href="<?php echo $href; ?>"><p><i
                                                                    class="fa fa-square green "></i>Pending Scrutiny</p>
                                                    </a></td>
                                                <td><a href="<?php echo $href; ?>"
                                                    <p> <?php echo htmlentities($count_efiling_data[0]->total_pending_scrutiny, ENT_QUOTES); ?> </p></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <?php
                                                if ($count_efiling_data[0]->total_filing_section_defective == 0) {
                                                    $href = "javascript:void(0)";
                                                } else {
                                                    $href = base_url("dashboard/stage_list/" . htmlentities(url_encryption(I_B_Defected_Stage, ENT_QUOTES)));
                                                }
                                                ?>
                                                <td><a href="<?php echo $href; ?>"><p><i
                                                                    class="fa fa-square yellow "></i>Defective</p></a>
                                                </td>
                                                <td><a href="<?php echo $href; ?>"
                                                    <p> <?php echo htmlentities($count_efiling_data[0]->total_filing_section_defective, ENT_QUOTES); ?> </p></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <?php
                                                if ($count_efiling_data[0]->total_rejected_cases == 0) {
                                                    $href = "javascript:void(0)";
                                                } else {
                                                    $href = base_url("dashboard/stage_list/" . htmlentities(url_encryption(I_B_Rejected_Stage, ENT_QUOTES)));
                                                }
                                                ?>
                                                <td><a href="<?php echo $href; ?>"><p><i
                                                                    class="fa fa-square orange "></i>Rejected</p></a>
                                                </td>
                                                <td><a href="<?php echo $href; ?>"
                                                    <p> <?php echo htmlentities($count_efiling_data[0]->total_rejected_cases, ENT_QUOTES); ?> </p></a>
                                                </td>
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
                <?php
                $lbl_section_head = 'eFiling';
                $lbl_efiled_tab = 'Cases';
                ?>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="x_panel tile fixed_height_320 overflow_hidden">
                        <div class="x_title visible-lg visible-md">
                            <h2><?php echo $lbl_section_head; ?> (After Acceptance by Court)</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_title visible-sm visible-xs">
                            <strong><?php echo $lbl_section_head; ?> (After Acceptance by Court) </strong>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content table-responsive">
                            <table class="" style="width:100%">
                                <tbody>
                                <tr>
                                    <th style="width:37%;"></th>
                                    <th>
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                            <p class="">Stages</p>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                            <p class="text_position">Count</p>
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <canvas class="MyCases" width="190" height="190" data-zr-dom-id="zr_0"
                                                style="width: 360px;width: 361px;width: 360px;width: 360px;"></canvas>
                                    </td>
                                    <td>
                                        <table class="tile_info">
                                            <tbody>
                                            <tr>
                                                <?php
                                                if ($count_efiling_data[0]->total_efiled_cases == 0) {
                                                    $href = "javascript:void(0)";
                                                } else {
                                                    $href = base_url("dashboard/stage_list/" . htmlentities(url_encryption(E_Filed_Stage, ENT_QUOTES)));
                                                }
                                                ?>
                                                <td><a href="<?php echo $href; ?>"><p><i
                                                                    class="fa fa-square aero "></i><?php echo $lbl_efiled_tab; ?>
                                                        </p></a></td>
                                                <td><a href="<?php echo $href; ?>"
                                                    <p> <?php echo htmlentities($count_efiling_data[0]->total_efiled_cases, ENT_QUOTES); ?> </p></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <?php
                                                if ($count_efiling_data[0]->total_efiled_docs == 0) {
                                                    $href = "javascript:void(0)";
                                                } else {
                                                    $href = base_url("dashboard/stage_list/" . htmlentities(url_encryption(Document_E_Filed, ENT_QUOTES)));
                                                }
                                                ?>
                                                <td><a href="<?php echo $href; ?>"><p><i
                                                                    class="fa fa-square purple "></i>Documents</p></a>
                                                </td>
                                                <td><a href="<?php echo $href; ?>"
                                                    <p> <?php echo htmlentities($count_efiling_data[0]->total_efiled_docs, ENT_QUOTES); ?> </p></a>
                                                </td>
                                            </tr>
                                            <?php
                                            $xyz = FALSE;
                                            if ($xyz) {
                                                ?>
                                                <tr>
                                                    <?php
                                                    if ($count_efiling_data[0]->total_efiled_deficit == 0) {
                                                        $href = "javascript:void(0)";
                                                    } else {
                                                        $href = base_url("dashboard/stage_list/" . htmlentities(url_encryption(DEFICIT_COURT_FEE_E_FILED, ENT_QUOTES)));
                                                    }
                                                    ?>
                                                    <td><a href="<?php echo $href; ?>"><p><i
                                                                        class="fa fa-square dark_blue "></i>Paid Deficit
                                                                Fee</p></a></td>
                                                    <td><a href="<?php echo $href; ?>"
                                                        <p> <?php echo htmlentities($count_efiling_data[0]->total_efiled_deficit, ENT_QUOTES); ?> </p></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <?php
                                                if ($count_efiling_data[0]->total_efiled_ia == 0) {
                                                    $href = "javascript:void(0)";
                                                } else {
                                                    $href = base_url("dashboard/stage_list/" . htmlentities(url_encryption(IA_E_Filed, ENT_QUOTES)));
                                                }
                                                ?>
                                                <td><a href="<?php echo $href; ?>"><p><i
                                                                    class="fa fa-square green "></i>IA</p></a></td>
                                                <td><a href="<?php echo $href; ?>"
                                                    <p> <?php echo htmlentities($count_efiling_data[0]->total_efiled_ia, ENT_QUOTES); ?> </p></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <?php
                                                if ($count_efiling_data[0]->total_mentioning == 0) {
                                                    $href = "javascript:void(0)";
                                                } else {
                                                    $href = base_url("dashboard/stage_list/" . htmlentities(url_encryption(MENTIONING_E_FILED, ENT_QUOTES)));
                                                }
                                                ?>
                                                <td><a href="<?php echo $href; ?>"><p><i class="fa fa-square blue "></i>Mentioning
                                                        </p></a></td>
                                                <td><a href="<?php echo $href; ?>"
                                                    <p> <?php echo htmlentities($count_efiling_data[0]->total_mentioning, ENT_QUOTES); ?> </p></a>
                                                </td>
                                            </tr>

                                            <tr>
                                                <?php
                                                if ($count_efiling_data[0]->total_lodged_cases == 0) {
                                                    $href = "javascript:void(0)";
                                                } else {
                                                    $href = base_url("dashboard/stage_list/" . htmlentities(url_encryption(LODGING_STAGE, ENT_QUOTES)));
                                                }
                                                ?>
                                                <td><a href="<?php echo $href; ?>"><p><i
                                                                    class="fa fa-square yellow "></i>Trashed</p></a>
                                                </td>
                                                <td><a href="<?php echo $href; ?>"
                                                    <p> <?php echo htmlentities($count_efiling_data[0]->total_lodged_cases, ENT_QUOTES); ?> </p></a>
                                                </td>
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

        $('#prodTabs a[href="#today"]').trigger('click');
        if (typeof (Chart) === 'undefined') {
            return;
        }

        console.log('init_chart_doughnut');

        if ($('.canvasDoughnut').length) {

            var chart_doughnut_settings = {
                type: 'doughnut',
                tooltipFillColor: "rgba(51, 51, 51, 51, 51, 51, 51, 0.76)",
                data: {
                    labels: [
                        "Draft",
                        "Pending Approval",
                        "For Compliance",
                        "Pay Deficit Fee",
                        "Pending Scrutiny",
                        "Defective",
                        "Rejected"
                    ],
                    datasets: [{
                        data: [
                            <?php echo htmlentities($count_efiling_data[0]->total_drafts, ENT_QUOTES); ?>,
                            <?php echo htmlentities($count_efiling_data[0]->total_pending_acceptance, ENT_QUOTES); ?>,
                            <?php echo htmlentities($count_efiling_data[0]->total_not_accepted, ENT_QUOTES); ?>,
                            <?php echo htmlentities($count_efiling_data[0]->total_pending_scrutiny, ENT_QUOTES); ?>,
                            <?php echo htmlentities($count_efiling_data[0]->total_filing_section_defective, ENT_QUOTES); ?>,
                            <?php echo htmlentities($count_efiling_data[0]->total_rejected_cases, ENT_QUOTES); ?>],
                        backgroundColor: [
                            "#BDC3C7",
                            "#9B59B6",
                            "#E74C3C",
                            "#36CAAB",
                            "#f0ad4e",
                            "#FF7F50"
                        ],
                        hoverBackgroundColor: [
                            "#9CC2CB",
                            "#9B59B6",
                            "#E74C3C",
                            "#67b168",
                            "#f0ad4e",
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


        if ($('.MyCases').length) {

            var chart_doughnut_settings = {
                type: 'doughnut',
                tooltipFillColor: "rgba(51, 51, 51, 51,51,  0.55)",
                data: {
                    labels: [
                        "Cases",
                        "Documents",
                        "IA",
                        "Mentioning",
                        "Trased"
                    ],
                    datasets: [{
                        data: [
                            <?php echo htmlentities($count_efiling_data[0]->total_efiled_cases, ENT_QUOTES); ?>,
                            <?php echo htmlentities($count_efiling_data[0]->total_efiled_docs, ENT_QUOTES); ?>,
                            <?php echo htmlentities($count_efiling_data[0]->total_efiled_ia, ENT_QUOTES); ?>,
                            <?php echo htmlentities($count_efiling_data[0]->total_mentioning, ENT_QUOTES); ?>,
                            <?php echo htmlentities($count_efiling_data[0]->total_lodged_cases, ENT_QUOTES); ?>],
                        backgroundColor: [
                            "#BDC3C7",
                            "#9B59B6",
                            "#26B99A",
                            "#3498DB",
                            "#f0ad4e"
                        ],
                        hoverBackgroundColor: [
                            "#9CC2CB",
                            "#B370CF",
                            "#26B99A",
                            "#3498DB",
                            "#f0ad4e"
                        ]
                    }]
                },
                options: {
                    legend: false,
                    responsive: false
                }
            }

            $('.MyCases').each(function () {

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
    .yellow {
        color: #f0ad4e;
    }

    .orange {
        color: #FF7F50;
    }

    .dark_blue {
        color: #0040ff;
    }

    p:hover {
        background-color: #EDEDED !important;
    }

    .text_position {
        padding-left: 60%;
    }
</style> 