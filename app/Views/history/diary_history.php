<div class="right_col" role="main">
    <?php echo $this->session->flashdata('msg'); ?>
    <div class="col-lg-12 col-xs-12">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="glyphicon glyphicon-book"></i> Case Status History</h2>
                        <div style="float: right">
                            <a class="btn btn-info btn-sm" onclick="window.history.back()" /> Back</a>
                            <a title="Click Here To View All Information" href="javascript:void(0);" class="btn btn-primary btn-sm openall" style="float: right">
                                <span class="fa fa-eye"></span> View All
                            </a>
                            <a title="Click Here To Close All Information" href="javascript:void(0);" class="btn btn-info btn-sm closeall" style="float: right;display: none;">
                                <span class="fa fa-eye-slash"></span> Close All
                            </a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content"> 

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="clearfix"></div>

                                <?php
                                $show_a_1 = false;

                                $a_1 = array();
                                $i = -1;

                                if ((isset($json_data['filing_no']) && $json_data['filing_no'] != NULL)) {
                                    $i++;
                                    $show_a_1 = true;
                                    $a_1[$i]['key'] = 'Case Code ';
                                    $a_1[$i]['value'] = $json_data['filing_no'];
                                }

                                if ((isset($json_data['type_name']) && $json_data['type_name'] != NULL) || (isset($json_data['fil_no']) && $json_data['fil_no'] != NULL) || (isset($json_data['fil_year']) && $json_data['fil_year'] != NULL)) {
                                    $i++;
                                    $show_a_1 = true;
                                    $a_1[$i]['key'] = 'Filing Number ';
                                    $a_1[$i]['value'] = $json_data['type_name'] . '/' . $json_data['fil_no'] . '/' . $json_data['fil_year'];
                                }

                                if ((isset($json_data['date_of_filing']) && $json_data['date_of_filing'] != NULL)) {
                                    $i++;
                                    $show_a_1 = true;
                                    $a_1[$i]['key'] = 'Date of Filing ';
                                    $a_1[$i]['value'] = date("d-m-Y", strtotime($json_data['date_of_filing']));
                                }

                                if ((isset($json_data['cino']) && $json_data['cino'] != NULL)) {
                                    $i++;
                                    $show_a_1 = true;
                                    $a_1[$i]['key'] = 'CNR ';
                                    $a_1[$i]['value'] = $json_data['cino'];
                                }

                                if ((isset($json_data['type_name']) && $json_data['type_name'] != NULL)) {
                                    $i++;
                                    $show_a_1 = true;
                                    $a_1[$i]['key'] = 'Case Type ';
                                    $a_1[$i]['value'] = $json_data['type_name'];
                                }

                                if ((isset($json_data['reg_no']) && $json_data['reg_no'] != NULL) || (isset($json_data['reg_year']) && $json_data['reg_year'] != NULL)) {
                                    $i++;
                                    $show_a_1 = true;
                                    $a_1[$i]['key'] = 'Registration Number ';
                                    $a_1[$i]['value'] = $json_data['reg_no'] . '/' . $json_data['reg_year'];
                                }

                                if ((isset($json_data['dt_regis']) && $json_data['dt_regis'] != NULL)) {
                                    $i++;
                                    $show_a_1 = true;
                                    $a_1[$i]['key'] = 'Registration Date ';
                                    $a_1[$i]['value'] = substr($json_data['dt_regis'], 8, 2) . '-' . substr($json_data['dt_regis'], 5, 2) . '-' . substr($json_data['dt_regis'], 0, 4);
                                }
                                ?>


                                <?php if ($show_a_1) { ?>
                                    <a href="#demo_1" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu" aria-expanded="true"><i class="fa fa-plus" style="float: right;"></i> <b>Case Details</b></a>
                                    <div class="collapse in" id="demo_1">
                                        <div class="x_panel">
                                            <div class="table-responsive">
                                                <table id="datatable-responsive" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                                                    <tbody>

                                                        <?php
                                                        $end_of_tr = false;

                                                        foreach ($a_1 as $k => $a) {
                                                            if ($k % 4 == 0 && $k == 0) {
                                                                echo '<tr>';
                                                                $end_of_tr = true;
                                                            } else if ($k % 4 == 0) {
                                                                echo '</tr>';
                                                                $end_of_tr = false;
                                                            }
                                                            echo '<td><strong>' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['key']) . ': </strong> <font color="#36366c">&nbsp;' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['value']) . '</font></td>';
                                                        }
                                                        if ($end_of_tr == true) {
                                                            echo '</tr>';
                                                            $end_of_tr = false;
                                                        }
                                                        ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>


                                <?php
                                $show_a_2 = false;

                                $a_2 = array();
                                $i = -1;

                                if ((isset($json_data['petNameAdd']) && $json_data['petNameAdd'] != NULL)) {
                                    $i++;
                                    $show_a_2 = true;
                                    $a_2[$i]['key'] = 'Petitioner ';
                                    $a_2[$i]['value'] = $json_data['petNameAdd'];
                                }

                                if ((isset($json_data['str_error']) && $json_data['str_error'] != NULL)) {
                                    $i++;
                                    $show_a_2 = true;
                                    $a_2[$i]['key'] = 'Extra Petitioner ';
                                    $a_2[$i]['value'] = $json_data['str_error'];
                                }
                                ?>

                                <?php if ($show_a_2) { ?>
                                    <a href="#demo_2" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Petitioner and Advocate</b></a>
                                    <div class="collapse" id="demo_2">
                                        <div class="x_panel">
                                            <div class="table-responsive">
                                                <table id="datatable-responsive" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                                                    <tbody>

                                                        <?php
                                                        $end_of_tr = false;

                                                        foreach ($a_2 as $k => $a) {
                                                            if ($k % 4 == 0 && $k == 0) {
                                                                echo '<tr>';
                                                                $end_of_tr = true;
                                                            } else if ($k % 4 == 0) {
                                                                echo '</tr>';
                                                                $end_of_tr = false;
                                                            }
                                                            echo '<td><strong>' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['key']) . ': </strong> <font color="#36366c">&nbsp;' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['value']) . '</font></td>';
                                                        }
                                                        if ($end_of_tr == true) {
                                                            echo '</tr>';
                                                            $end_of_tr = false;
                                                        }
                                                        ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>


                                <?php
                                $show_a_3 = false;

                                $a_3 = array();
                                $i = -1;

                                if ((isset($json_data['resNameAdd']) && $json_data['resNameAdd'] != NULL)) {
                                    $i++;
                                    $show_a_3 = true;
                                    $a_3[$i]['key'] = 'Respondent ';
                                    $a_3[$i]['value'] = $json_data['resNameAdd'];
                                }

                                if ((isset($json_data['str_error1']) && $json_data['str_error1'] != NULL)) {
                                    $i++;
                                    $show_a_3 = true;
                                    $a_3[$i]['key'] = 'Extra Respondent ';
                                    $a_3[$i]['value'] = $json_data['str_error1'];
                                }
                                ?>


                                <?php if ($show_a_3) { ?>
                                    <a href="#demo_3" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Respondent and Advocate</b></a>
                                    <div class="collapse" id="demo_3">
                                        <div class="x_panel">
                                            <div class="table-responsive">
                                                <table id="datatable-responsive" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                                                    <tbody>

                                                        <?php
                                                        $end_of_tr = false;

                                                        foreach ($a_3 as $k => $a) {
                                                            if ($k % 4 == 0 && $k == 0) {
                                                                echo '<tr>';
                                                                $end_of_tr = true;
                                                            } else if ($k % 4 == 0) {
                                                                echo '</tr>';
                                                                $end_of_tr = false;
                                                            }
                                                            echo '<td><strong>' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['key']) . ': </strong> <font color="#36366c">&nbsp;' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['value']) . '</font></td>';
                                                        }
                                                        if ($end_of_tr == true) {
                                                            echo '</tr>';
                                                            $end_of_tr = false;
                                                        }
                                                        ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>


                                <?php
                                $show_a_4 = false;

                                $a_4 = array();
                                $i = -1;

                                if ((isset($json_data['objection']) && $json_data['objection'] != NULL)) {
                                    $i++;
                                    $show_a_4 = true;
                                    $a_4[$i]['key'] = 'Objections ';
                                    $a_4[$i]['value'] = $json_data['objection'];
                                }
                                ?>

                                <?php if ($show_a_4) { ?>
                                    <a href="#demo_4" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Objections</b></a>
                                    <div class="collapse" id="demo_4">
                                        <div class="x_panel">
                                            <div class="table-responsive">
                                                <table id="datatable-responsive" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                                                    <tbody>

                                                        <?php
                                                        $end_of_tr = false;

                                                        foreach ($a_4 as $k => $a) {
                                                            if ($k % 4 == 0 && $k == 0) {
                                                                echo '<tr>';
                                                                $end_of_tr = true;
                                                            } else if ($k % 4 == 0) {
                                                                echo '</tr>';
                                                                $end_of_tr = false;
                                                            }
                                                            echo '<td><strong>' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['key']) . ': </strong> <font color="#36366c">&nbsp;' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['value']) . '</font></td>';
                                                        }
                                                        if ($end_of_tr == true) {
                                                            echo '</tr>';
                                                            $end_of_tr = false;
                                                        }
                                                        ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>


                                <?php
                                $show_a_5 = false;

                                $a_5 = array();
                                $i = -1;

                                if ((isset($json_data['act']) && $json_data['act'] != NULL)) {
                                    $i++;
                                    $show_a_5 = true;
                                    $a_5[$i]['key'] = 'Act ';
                                    $a_5[$i]['value'] = $json_data['act'];
                                }
                                ?>

                                <?php if ($show_a_4) { ?>
                                    <a href="#demo_5" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Acts</b></a>
                                    <div class="collapse" id="demo_5">
                                        <div class="x_panel">
                                            <div class="table-responsive">
                                                <?php echo $json_data['act'] . '</table>'; ?>


                                            </div>

                                        </div>
                                    </div>
                                <?php } ?>



                                <?php
                                $show_a_6 = false;

                                $a_6 = array();
                                $i = -1;

                                if ((isset($json_data['date_first_list']) && $json_data['date_first_list'] != NULL)) {
                                    $i++;
                                    $show_a_6 = true;
                                    $a_6[$i]['key'] = 'First Hearing Date ';
                                    $a_6[$i]['value'] = date("d-m-Y", strtotime($json_data['date_first_list']));
                                }

                                if ((isset($json_data['date_of_decision']) && $json_data['date_of_decision'] != NULL)) {
                                    $i++;
                                    $show_a_6 = true;
                                    $a_6[$i]['key'] = 'Date of Decision ';
                                    $a_6[$i]['value'] = date("d-m-Y", strtotime($json_data['date_of_decision']));
                                }

                                if ((isset($json_data['disp_nature']) && $json_data['disp_nature'] != NULL)) {
                                    $i++;
                                    $show_a_6 = true;
                                    $a_6[$i]['key'] = 'Case Status ';
                                    $a_6[$i]['value'] = $json_data['disp_nature'];
                                }

                                if ((isset($json_data['disp_name']) && $json_data['disp_name'] != NULL)) {
                                    $i++;
                                    $show_a_6 = true;
                                    $a_6[$i]['key'] = 'Case Status Main ';
                                    $a_6[$i]['value'] = $json_data['disp_name'];
                                }

                                if ((isset($json_data['court_no']) && $json_data['court_no'] != NULL) || (isset($json_data['desgname']) && $json_data['desgname'] != NULL)) {
                                    $i++;
                                    $show_a_6 = true;
                                    $a_6[$i]['key'] = 'Court number and Judge ';
                                    $a_6[$i]['value'] = $json_data['court_no'] . ', ' . $json_data['desgname'];
                                }
                                ?>

                                <?php if ($show_a_6) { ?>
                                    <a href="#demo_6" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Case Status</b></a>
                                    <div class="collapse" id="demo_6">
                                        <div class="x_panel">
                                            <div class="table-responsive">
                                                <table id="datatable-responsive" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                                                    <tbody>

                                                        <?php
                                                        $end_of_tr = false;

                                                        foreach ($a_6 as $k => $a) {
                                                            if ($k % 4 == 0 && $k == 0) {
                                                                echo '<tr>';
                                                                $end_of_tr = true;
                                                            } else if ($k % 4 == 0) {
                                                                echo '</tr>';
                                                                $end_of_tr = false;
                                                            }
                                                            echo '<td><strong>' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['key']) . ': </strong> <font color="#36366c">&nbsp;' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['value']) . '</font></td>';
                                                        }
                                                        if ($end_of_tr == true) {
                                                            echo '</tr>';
                                                            $end_of_tr = false;
                                                        }
                                                        ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>


                                <?php
                                $show_a_7 = false;

                                $a_7 = array();
                                $i = -1;

                                if ((isset($json_data['fir_details']) && $json_data['fir_details'] != NULL)) {
                                    $i++;
                                    $show_a_7 = true;
                                    $a_7[$i]['key'] = 'Fir Details ';
                                    $a_7[$i]['value'] = str_replace('^', ', ', $json_data['fir_details']);
                                }
                                ?>

                                <?php if ($show_a_7) { ?>
                                    <a href="#demo_7" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Fir Details</b></a>
                                    <div class="collapse" id="demo_7">
                                        <div class="x_panel">
                                            <div class="table-responsive">
                                                <table id="datatable-responsive" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                                                    <tbody>

                                                        <?php
                                                        $end_of_tr = false;

                                                        foreach ($a_7 as $k => $a) {
                                                            if ($k % 4 == 0 && $k == 0) {
                                                                echo '<tr>';
                                                                $end_of_tr = true;
                                                            } else if ($k % 4 == 0) {
                                                                echo '</tr>';
                                                                $end_of_tr = false;
                                                            }
                                                            echo '<td><strong>' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['key']) . ': </strong> <font color="#36366c">&nbsp;' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['value']) . '</font></td>';
                                                        }
                                                        if ($end_of_tr == true) {
                                                            echo '</tr>';
                                                            $end_of_tr = false;
                                                        }
                                                        ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>


                                <?php
                                $show_a_8 = false;

                                $a_8 = array();
                                $i = -1;

                                if ((isset($json_data['interimOrder']) && $json_data['interimOrder'] != NULL)) {
                                    $i++;
                                    $show_a_8 = true;
                                    $a_8[$i]['key'] = 'Interim Order ';
                                    $a_8[$i]['value'] = str_replace('Copyright © 2016-2017 eCommittee, Supreme Court of India. All rights reserved.', '', $json_data['interimOrder']);
                                }
                                ?>

                                <?php if ($show_a_8) { ?>
                                    <a href="#demo_8" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Interim Order</b></a>
                                    <div class="collapse" id="demo_8">
                                        <div class="x_panel">
                                            <div class="table-responsive">
                                                <table id="datatable-responsive" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                                                    <tbody>

                                                        <?php
                                                        $end_of_tr = false;

                                                        foreach ($a_8 as $k => $a) {
                                                            if ($k % 4 == 0 && $k == 0) {
                                                                echo '<tr>';
                                                                $end_of_tr = true;
                                                            } else if ($k % 4 == 0) {
                                                                echo '</tr>';
                                                                $end_of_tr = false;
                                                            }
                                                            echo '<td><strong>' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['key']) . ': </strong> <font color="#36366c">&nbsp;' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['value']) . '</font></td>';
                                                        }
                                                        if ($end_of_tr == true) {
                                                            echo '</tr>';
                                                            $end_of_tr = false;
                                                        }
                                                        echo '</tbody></table></tbody></table></tbody></table></tbody></table>';
                                                        ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>


                                <?php
                                $show_a_9 = false;

                                $a_9 = array();
                                $i = -1;

                                if ((isset($json_data['finalOrder']) && $json_data['finalOrder'] != NULL)) {
                                    $i++;
                                    $show_a_9 = true;
                                    $a_9[$i]['key'] = 'Final Order ';
                                    $a_9[$i]['value'] = str_replace('Copyright © 2016-2017 eCommittee, Supreme Court of India. All rights reserved.', '', $json_data['finalOrder']);
                                }
                                ?>

                                <?php if ($show_a_9) { ?>
                                    <a href="#demo_9" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Final Order</b></a>
                                    <div class="collapse" id="demo_9">
                                        <div class="x_panel">
                                            <div class="table-responsive">
                                                <table id="datatable-responsive" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                                                    <tbody>

                                                        <?php
                                                        $end_of_tr = false;

                                                        foreach ($a_9 as $k => $a) {
                                                            if ($k % 4 == 0 && $k == 0) {
                                                                echo '<tr>';
                                                                $end_of_tr = true;
                                                            } else if ($k % 4 == 0) {
                                                                echo '</tr>';
                                                                $end_of_tr = false;
                                                            }
                                                            echo '<td><strong>' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['key']) . ': </strong> <font color="#36366c">&nbsp;' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['value']) . '</font></td>';
                                                        }
                                                        if ($end_of_tr == true) {
                                                            echo '</tr>';
                                                            $end_of_tr = false;
                                                        }
                                                        echo '</tbody></table></tbody></table></tbody></table></tbody></table>';
                                                        ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>


                                <?php
                                $show_a_10 = false;

                                $a_10 = array();
                                $i = -1;

                                if ((isset($json_data['rejection']) && $json_data['rejection'] != NULL)) {
                                    $i++;
                                    $show_a_10 = true;
                                    $a_10[$i]['key'] = 'Final Order ';
                                    $a_10[$i]['value'] = $json_data['rejection'];
                                }
                                ?>

                                <?php if ($show_a_10) { ?>
                                    <a href="#demo_10" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Rejection</b></a>
                                    <div class="collapse" id="demo_10">
                                        <div class="x_panel">
                                            <div class="table-responsive">
                                                <table id="datatable-responsive" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                                                    <tbody>

                                                        <?php
                                                        $end_of_tr = false;

                                                        foreach ($a_10 as $k => $a) {
                                                            if ($k % 4 == 0 && $k == 0) {
                                                                echo '<tr>';
                                                                $end_of_tr = true;
                                                            } else if ($k % 4 == 0) {
                                                                echo '</tr>';
                                                                $end_of_tr = false;
                                                            }
                                                            echo '<td><strong>' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['key']) . ': </strong> <font color="#36366c">&nbsp;' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['value']) . '</font></td>';
                                                        }
                                                        if ($end_of_tr == true) {
                                                            echo '</tr>';
                                                            $end_of_tr = false;
                                                        }
                                                        echo '</tbody></table></tbody></table></tbody></table></tbody></table>';
                                                        ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>


                                <?php
                                $show_a_11 = false;

                                $a_11 = array();
                                $i = -1;

                                if ((isset($json_data['subordinateCourtInfoStr']) && $json_data['subordinateCourtInfoStr'] != NULL)) {
                                    $i++;
                                    $show_a_11 = true;
                                    $a_11[$i]['key'] = 'Subordinate Court Information ';
                                    $a_11[$i]['value'] = str_replace('^', '<br>', $json_data['subordinateCourtInfoStr']);
                                }
                                ?>

                                <?php if ($show_a_11) { ?>
                                    <a href="#demo_11" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Subordinate Court Information</b></a>
                                    <div class="collapse" id="demo_11">
                                        <div class="x_panel">
                                            <div class="table-responsive">
                                                <table id="datatable-responsive" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                                                    <tbody>

                                                        <?php
                                                        $end_of_tr = false;

                                                        foreach ($a_11 as $k => $a) {
                                                            if ($k % 4 == 0 && $k == 0) {
                                                                echo '<tr>';
                                                                $end_of_tr = true;
                                                            } else if ($k % 4 == 0) {
                                                                echo '</tr>';
                                                                $end_of_tr = false;
                                                            }
                                                            echo '<td><strong>' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['key']) . ': </strong> <font color="#36366c">&nbsp;' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['value']) . '</font></td>';
                                                        }
                                                        if ($end_of_tr == true) {
                                                            echo '</tr>';
                                                            $end_of_tr = false;
                                                        }
                                                        ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>


                                <?php
                                $show_a_12 = false;

                                $a_12 = array();
                                $i = -1;

                                if ((isset($json_data['historyOfCaseHearing']) && $json_data['historyOfCaseHearing'] != NULL)) {
                                    $i++;
                                    $show_a_12 = true;
                                    $a_12[$i]['key'] = 'History of Case Hearing ';
                                    $a_12[$i]['value'] = str_replace('Copyright © 2016-2017 eCommittee, Supreme Court of India. All rights reserved.', '', $json_data['historyOfCaseHearing']);
                                }
                                ?>

                                <?php if ($show_a_12) { ?>
                                    <a href="#demo_12" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>History of Case Hearing</b></a>
                                    <div class="collapse" id="demo_12">
                                        <div class="x_panel">
                                            <div class="table-responsive">
                                                <table id="datatable-responsive" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                                                    <tbody>

                                                        <?php
                                                        $end_of_tr = false;

                                                        foreach ($a_12 as $k => $a) {
                                                            if ($k % 4 == 0 && $k == 0) {
                                                                echo '<tr>';
                                                                $end_of_tr = true;
                                                            } else if ($k % 4 == 0) {
                                                                echo '</tr>';
                                                                $end_of_tr = false;
                                                            }
                                                            echo '<td><strong>' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['key']) . ': </strong> <font color="#36366c">&nbsp;' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['value']) . '</font></td>';
                                                        }
                                                        if ($end_of_tr == true) {
                                                            echo '</tr>';
                                                            $end_of_tr = false;
                                                        }
                                                        echo '</tbody></table></tbody></table></tbody></table></tbody></table>';
                                                        ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>


                                <?php
                                $show_a_13 = false;

                                $a_13 = array();
                                $i = -1;

                                if ((isset($json_data['writinfo']) && $json_data['writinfo'] != NULL)) {
                                    $i++;
                                    $show_a_13 = true;
                                    $a_13[$i]['key'] = 'Writ Information ';
                                    $a_13[$i]['value'] = str_replace('Copyright © 2016-2017 eCommittee, Supreme Court of India. All rights reserved.', '', $json_data['writinfo']);
                                }
                                ?>

                                <?php if ($show_a_13) { ?>
                                    <a href="#demo_13" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Writ Information</b></a>
                                    <div class="collapse" id="demo_13">
                                        <div class="x_panel">
                                            <div class="table-responsive">
                                                <table id="datatable-responsive" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                                                    <tbody>

                                                        <?php
                                                        $end_of_tr = false;

                                                        foreach ($a_13 as $k => $a) {
                                                            if ($k % 4 == 0 && $k == 0) {
                                                                echo '<tr>';
                                                                $end_of_tr = true;
                                                            } else if ($k % 4 == 0) {
                                                                echo '</tr>';
                                                                $end_of_tr = false;
                                                            }
                                                            echo '<td><strong>' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['key']) . ': </strong> <font color="#36366c">&nbsp;' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['value']) . '</font></td>';
                                                        }
                                                        if ($end_of_tr == true) {
                                                            echo '</tr>';
                                                            $end_of_tr = false;
                                                        }
                                                        echo '</tbody></table></tbody></table></tbody></table></tbody></table>';
                                                        ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>


                                <?php
                                $show_a_14 = false;

                                $a_14 = array();
                                $i = -1;

                                if ((isset($json_data['transfer']) && $json_data['transfer'] != NULL)) {
                                    $i++;
                                    $show_a_14 = true;
                                    $a_14[$i]['key'] = 'Case Transfers between Courts ';
                                    $a_14[$i]['value'] = $json_data['transfer'];
                                }
                                ?>

                                <?php if ($show_a_14) { ?>
                                    <a href="#demo_14" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Case Transfers between Courts</b></a>
                                    <div class="collapse" id="demo_14">
                                        <div class="x_panel">
                                            <div class="table-responsive">
                                                <table id="datatable-responsive" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                                                    <tbody>

                                                        <?php
                                                        $end_of_tr = false;

                                                        foreach ($a_14 as $k => $a) {
                                                            if ($k % 4 == 0 && $k == 0) {
                                                                echo '<tr>';
                                                                $end_of_tr = true;
                                                            } else if ($k % 4 == 0) {
                                                                echo '</tr>';
                                                                $end_of_tr = false;
                                                            }
                                                            echo '<td><strong>' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['key']) . ': </strong> <font color="#36366c">&nbsp;' . preg_replace('#<script(.*?)>(.*?)</script>#is', '', $a['value']) . '</font></td>';
                                                        }
                                                        if ($end_of_tr == true) {
                                                            echo '</tr>';
                                                            $end_of_tr = false;
                                                        }
                                                        echo '</tbody></table></tbody></table></tbody></table></tbody></table>';
                                                        ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row text-center"><a class="btn btn-info btn-sm" type="button" onclick="window.history.back()"> Back</a></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.closeall').click(function () {
            $('.collapse.in').collapse('hide');
            $('.closeall').hide();
            $('.openall').show();
        });
        $('.openall').click(function () {
            $('.collapse:not(".in")').collapse('show');
            $('.closeall').show();
            $('.openall').hide();
        });
    });
    $(document).ready(function () {
        $(".collapse").on("hide.bs.collapse", function () {
            var id = $(this).attr('id');
            var title = $('[href="#' + id + '"] b').html();
            $('[href="#' + id + '"]').html('<i class="fa fa-plus" style="float: right;"></i> <b>' + title + '</b>');

        });
        $(".collapse").on("show.bs.collapse", function () {
            var id = $(this).attr('id');
            var title = $('[href="#' + id + '"] b').html();
            $('[href="#' + id + '"]').html('<i class="fa fa-minus" style="float: right;"></i> <b>' + title + '</b>');
        });
    });

</script>
<style>
    #it{
        background-image:url();
        font-size:0;
        width:50px;
        height:50px;
    }​
</style>
