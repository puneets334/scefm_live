<!DOCTYPE html>
<html lang="en">
    <head>

        <?php
        $allow_image_url = parse_url($this->session->userdata['login']['photo_path']);
        header("X-Frame-Options: DENY");
        header("X-XSS-Protection: 1; mode=block");
        header("X-Content-Type-Options: nosniff");
        header("Strict-Transport-Security: max-age=31536000");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        echo '<meta http-equiv="Content-Security-Policy" content="default-src \'self\' ' . $allow_image_url['host'] . ' http://xxxx \'unsafe-inline\' \'unsafe-eval\';  font-src \'self\' data: ">';
        ?>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?= HEADER_TITLE; ?> </title>
        <link href="<?= base_url() . 'assets' ?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?= base_url() . 'assets' ?>/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?= base_url() . 'assets' ?>/vendors/nprogress/nprogress.css" rel="stylesheet">


        <link href="<?= base_url() . 'assets' ?>/build/css/custom.min.css" rel="stylesheet">
        <link href="<?= base_url() . 'assets' ?>/build/css/custom.css" rel="stylesheet">
        <link href="<?= base_url() . 'assets' ?>/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
        <link href="<?= base_url() . 'assets' ?>/css/jquery-ui.css" rel="stylesheet">
        <link href="<?= base_url() . 'assets' ?>/css/select2.min.css" rel="stylesheet">

        <script type="text/javascript">
            var base_url = '<?php echo base_url(); ?>';
        </script>
        <script type="text/javascript" src="<?= base_url() . 'assets' ?>/js/jquery-main.js"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets' ?>/js/jquery_validation.js" ></script>
        <script type="text/javascript" src="<?= base_url() . 'assets' ?>/js/jsencrypt.min.js" ></script>
        <script type="text/javascript" src="<?= base_url() . 'assets' ?>/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/chart.js"></script>

    </head>
    <?php
    $bg_color = "#EDEDED;";
    $bg_border = "#EDEDED;border-right: 0.1px solid rgba(92, 80, 80, 0.3)";

//$bg_color="#2196f3;"; 
    ?>
    <body class="nav-sm" style="background-color:<?php echo $bg_color; ?>">
        <!--<div class="container body" >-->
        <div class="container-fluid body" >
            <div class="main_container " >
                <div class="col-md-3 left_col leftCol" style="background-color:<?php echo $bg_border; ?>;">
                    <div class="left_col scroll-view" style="background-color:<?php echo $bg_color; ?>;">
                        <div class="navbar nav_title" style="background-color:<?php echo $bg_border; ?>;">
                            <a href="<?= base_url('login'); ?>" class="site_title"><span class="text_color"><i class="fa fa-gavel"></i> <span>e-Filing</span></span></a>
                        </div>

                        <div class="clearfix"></div>
                        <hr class="hr_class">
                        <!-- sidebar menu -->
                        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu" >

                            <div class="menu_section">

                                <div class="legend list-unstyled visible-lg visible-md visible-sm center">
                                    <div class="row">
                                        <div class="col-lg-1 col-md-1">

                                        </div>
                                        <div class="col-lg-10 col-md-10">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6">
                                                    <a href="<?= base_url('dashboard'); ?>" class="btn btn-info"><i class="fa fa-home" style="font-size:15px"></i>Home</a>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <a href="<?= base_url('case_file/type/' . htmlentities(url_encryption(E_FILING_TYPE_CDE), ENT_QUOTES)); ?>" class="btn btn-dark btn-sm"><i class="fa fa-database" style="font-size:15px"></i>CDE</a> 
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6">
                                                    <a href="<?= base_url('news_event/'); ?>" class="btn btn-warning btn-sm"><i class="fa fa-question-circle" style="font-size:15px"></i>Assistance</a>
                                                </div>
                                                <div class="col-lg-6 col-md-6">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-1">

                                        </div>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                <br>


                                <!--                                <h3>General</h3>-->
                                <ul class="nav side-menu" >
                                    <?php
                                    if ($this->uri->segment(1) == 'news_event' || $this->uri->segment(1) == 'Notice' || $this->uri->segment(2) == 'contact_us') {

                                        // STARTS HELP MENU
                                        ?>
                                        <li class="highlight_tab"><a  class="text_cursor active_tab text_position"><span class=" font_css"  style="color:#fff;"><i class="fa fa-question-circle" aria-hidden="true"></i>Assistance</span></a></li>
                                        <div class="show_md_div visible-lg visible-md" style="display:none !important;">
                                            <ul class="nav side-menu">
                                                <li class="highlight"><a href="<?= base_url('news_event/') ?>"><span class="text_color"><i class="fa fa-newspaper-o"></i>News & Events</span></a></li>
                                                <li class="highlight"><a href="<?= base_url('Notice/NoticeView/') ?>"><span class="text_color"><i class="fa fa-file-text-o"></i>Notice & Forms</span></a></li>
                                                <li class="highlight"><a href="<?= base_url('dashboard/contact_us') ?>"><span class="text_color" ><i class="fa fa-users"></i> Contact us </span></a></li>
                                                <li class="highlight"><a href="https://efiling.ecourts.gov.in/assets/downloads/efiling-User-manual.pdf" target="_blank"><span class="text_color"><i class="fa fa-info"></i>&nbsp;User Manual</span></a></li>
                                            </ul>
                                        </div>
                                        <div class="show_sm_div visible-xs visible-sm center nav-sm" style="font-size:13px; font-weight: bold;">
                                            <div class="main-menu"> <ul> <li> <a  href="<?= base_url('news_event/') ?>"><i class="fa fa-newspaper-o text_color fa-5x" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>News & Events<span class="nav-text efile_text" style="font-size:20px; display: table-cell !important; "> News & Events</span> </a> </li> </ul></div>
                                            <div class="main-menu"> <ul> <li> <a   href="<?= base_url('Notice/NoticeView/') ?>"><i class="fa fa-file-text-o fa-5x" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>Notice & Form<span class="nav-text efile_text" style="font-size:20px; display: table-cell !important; "> Notice & Form</span> </a> </li> </ul></div>
                                            <div class="main-menu"> <ul> <li> <a  href="<?= base_url('dashboard/contact_us') ?>"><i class="fa fa-users text_color fa-5x" >&nbsp;&nbsp;&nbsp;&nbsp;</i>Contact us<span class="nav-text efile_text" style="font-size:20px; display: table-cell !important; "> Contact us</span> </a> </li> </ul></div>
                                            <div class="main-menu"> <ul> <li> <a   href="https://efiling.ecourts.gov.in/assets/downloads/efiling-User-manual.pdf" target="_blank"><i class="fa fa-info-circle text_color fa-5x" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>User Manual &nbsp;&nbsp;<span class="nav-text efile_text" style="font-size:20px; display: table-cell !important; ">&nbsp;&nbsp;User Manual</span> </a> </li> </ul></div>
                                        </div> 
                                        <?php
                                        // ENDS HELP MENU
                                    } elseif ($this->uri->segment(1) == 'stage_list' || $this->uri->segment(1) == 'case_file' || $this->uri->segment(1) == 'new_case') {
                                        // Start User Dashboard Stages list
                                        ?>
                                        <li class="highlight_tab"><a  class="text_cursor active_tab text_position" href="<?php echo base_url('dashboard'); ?>"><span class=" font_css"  style="color:#fff;"><i class="fa fa-dashboard "></i>DASHBOARD</span></a></li>
                                        <div class="show_md_div visible-lg visible-md" style="display:none !important;">
                                            <ul class="nav side-menu">
                                                <li  class="highlight"><a href="<?= base_url() ?>stage_list/view/<?php echo htmlentities(url_encryption(Draft_Stage, ENT_QUOTES)); ?>"><span class="text_color" > <?php echo htmlentities($total_drafts[0]->count, ENT_QUOTES); ?>Draft<i class="number_css badge"> 237</i></span></a></li>
                                                <li  class="highlight"><a href="<?= base_url() ?>stage_list/view/<?php echo htmlentities(url_encryption(Initial_Approaval_Pending_Stage, ENT_QUOTES)); ?>"><span class="text_color" >Pending Approval<i class="number_css badge"> 48</i></span></a>  </li>
                                                <li  class="highlight"><a href="<?= base_url() ?>stage_list/view/<?php echo htmlentities(url_encryption(Initial_Defected_Stage, ENT_QUOTES)); ?>"><span class="text_color" >For Compliance<i class="number_css badge">0</i></span></a></li>
                                                <li  class="highlight"><a href="<?= base_url() ?>stage_list/view/<?php echo htmlentities(url_encryption(I_B_Approval_Pending_Stage, ENT_QUOTES)); ?>"><span class="text_color" >Pending Scrutiny <i class="number_css badge">0</i></span></a></li>
                                                <li  class="highlight"><a href="<?= base_url() ?>stage_list/view/<?php echo htmlentities(url_encryption(I_B_Defected_Stage, ENT_QUOTES)); ?>"><span class="text_color" >Defective<i class="number_css badge">0</i></span></a></li>
                                                <li  class="highlight"><a href="<?= base_url() ?>stage_list/view/<?php echo htmlentities(url_encryption(E_Filed_Stage, ENT_QUOTES)); ?>"><span class="text_color" >eFiled Cases<i class="number_css badge">0</i></span></a></li>
                                                <li  class="highlight"><a href="<?= base_url() ?>stage_list/view/<?php echo htmlentities(url_encryption(I_B_Rejected_Stage, ENT_QUOTES)); ?>"><span class="text_color" >Rejected eFiling No.'s<i class="number_css badge">0</i></span></a></li>
                                                <li  class="highlight"><a href="<?= base_url() ?>stage_list/view/<?php echo htmlentities(url_encryption(LODGING_STAGE, ENT_QUOTES)); ?>"><span class="text_color" >Trashed<i class="number_css badge">0</i></span></a></li>                                        
                                            </ul></div>

                                        <div class="show_sm_div visible-xs visible-sm center nav-sm" style="font-size:13px; font-weight: bold;">
                                            <div class="main-menu"> <ul> <li> <a href="<?php echo ($count_efiling_data[0]->total_drafts == '0' ) ? 'javascript:void(0)' : base_url('stage_list/view/' . htmlentities(url_encryption(Draft_Stage, ENT_QUOTES))); ?>"><img class="img-css" src="<?php echo base_url('assets/images/mycases/draft.svg'); ?>" height="35" width="40">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="nav-text" > Draft  <p class='badge font-badge nav-text'><?php echo htmlentities($count_efiling_data[0]->total_drafts, ENT_QUOTES); ?></p></span> </a> </li> </ul></div>
                                            <div class="main-menu"> <ul> <li> <a href="<?php echo ($count_efiling_data[0]->total_pending_acceptance == '0' ) ? 'javascript:void(0)' : base_url('stage_list/view/' . htmlentities(url_encryption(Initial_Approaval_Pending_Stage, ENT_QUOTES))); ?>"><img class="img-css" src="<?php echo base_url('assets/images/mycases/pending_acceptance.svg'); ?>"  height="35" width="40">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="nav-text"> Pending Approval   &nbsp;&nbsp;<p class='badge font-badge'><?php echo htmlentities($count_efiling_data[0]->total_pending_acceptance, ENT_QUOTES); ?></p></span> </a> </li> </ul></div>
                                            <div class="main-menu"> <ul> <li> <a href="<?php echo ($count_efiling_data[0]->total_not_accepted == '0' ) ? 'javascript:void(0)' : base_url('stage_list/view/' . htmlentities(url_encryption(Initial_Defected_Stage, ENT_QUOTES))); ?>"><img class="img-css" src="<?php echo base_url('assets/images/mycases/not accepted.svg'); ?>" height="35" width="40"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nav-text">&nbsp;&nbsp;&nbsp;      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;For Compliance  &nbsp;&nbsp;<p class='badge font-badge'><?php echo htmlentities($count_efiling_data[0]->total_not_accepted, ENT_QUOTES); ?></p></span> </a> </li> </ul></div>
                                            <div class="main-menu"> <ul> <li> <a href="<?php echo ($count_efiling_data[0]->total_not_accepted == '0' ) ? 'javascript:void(0)' : base_url('stage_list/view/' . htmlentities(url_encryption(I_B_Approval_Pending_Stage, ENT_QUOTES))); ?>"><img class="img-css" src="<?php echo base_url('assets/images/mycases/Pending scrutiny.svg'); ?>" height="35" width="40"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nav-text"> Pending Scrutiny  &nbsp;&nbsp;<p class='badge font-badge'><?php echo htmlentities($count_efiling_data[0]->total_not_accepted, ENT_QUOTES); ?></p></span> </a> </li> </ul></div>
                                            <div class="main-menu"> <ul> <li> <a href="<?php echo ($count_efiling_data[0]->total_filing_section_defective == '0' ) ? 'javascript:void(0)' : base_url('stage_list/view/' . htmlentities(url_encryption(I_B_Defected_Stage, ENT_QUOTES))); ?>"><img class="img-css" src="<?php echo base_url('assets/images/mycases/Defective.svg'); ?>" height="35" width="40">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<span class="nav-text"> Defective  &nbsp;&nbsp;<p class='badge font-badge'><?php echo htmlentities($count_efiling_data[0]->total_filing_section_defective, ENT_QUOTES); ?></p></span> </a> </li> </ul></div>
                                            <div class="main-menu"> <ul> <li> <a href="<?php echo ($count_efiling_data[0]->total_efiled_cases == '0' ) ? 'javascript:void(0)' : base_url('stage_list/view/' . htmlentities(url_encryption(E_Filed_Stage, ENT_QUOTES))); ?>"><img class="img-css" src="<?php echo base_url('assets/images/mycases/documents.svg'); ?>" height="35" width="40">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="nav-text"> eFiled Cases  &nbsp;&nbsp;<p class='badge font-badge'><?php echo htmlentities($count_efiling_data[0]->total_efiled_cases, ENT_QUOTES); ?></p></span> </a> </li> </ul></div>
                                            <div class="main-menu"> <ul> <li> <a href="<?php echo ($count_efiling_data[0]->total_rejected_cases == '0' ) ? 'javascript:void(0)' : base_url('stage_list/view/' . htmlentities(url_encryption(I_B_Rejected_Stage, ENT_QUOTES))); ?>"><img class="img-css" src="<?php echo base_url('assets/images/mycases/rejected.svg'); ?>" height="35" width="40"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="nav-text">  Rejected eFiling No.'s  &nbsp;&nbsp;<p class='badge font-badge'><?php echo htmlentities($count_efiling_data[0]->total_rejected_cases, ENT_QUOTES); ?></p></span> </a> </li> </ul></div>
                                            <div class="main-menu"> <ul> <li> <a href="<?php echo ($count_efiling_data[0]->total_lodged_cases == '0' ) ? 'javascript:void(0)' : base_url('stage_list/view/' . htmlentities(url_encryption(LODGING_STAGE, ENT_QUOTES))); ?>"><img class="img-css" src="<?php echo base_url('assets/images/mycases/trashed.svg'); ?>" height="35" width="40"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="nav-text"> Trashed  &nbsp;&nbsp;<p class='badge font-badge'><?php echo htmlentities($count_efiling_data[0]->total_lodged_cases, ENT_QUOTES); ?></p></span> </a> </li> </ul></div>
                                        </div>  
                                        <?php
                                        // Ends User Dashboard Stages list
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <!-- /sidebar menu -->
                    </div>
                </div>

                <!-- top navigation -->
                <div class="top_nav">
                    <div class="nav_menu">
                        <nav>
                            <div class="nav toggle  visible-sm visible-xs">
                                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                            </div>
                            <ul class="nav navbar-nav navbar-right">
                                <li class="">
                                    <?php
                                    $CI = & get_instance();
                                    $CI->load->model('Profile_model');

                                    if ($this->session->userdata['login']['last_name'] != 'NA' && $this->session->userdata['login']['last_name'] != 'NULL') {
                                        $last_name = $this->session->userdata['login']['last_name'];
                                    } else {
                                        $last_name = '';
                                    }

                                    $login_time = $CI->Profile_model->userLastLogin($this->session->userdata['login']['id']);
                                    $last_login = ($login_time[0]->login_time != '') ? date('d-m-Y h:i:s A', strtotime($login_time[0]->login_time)) : NULL;
                                    if ($this->session->userdata['login']['photo_path'] != '') {
                                        $profile_photo = str_replace('/photo/', '/' . 'thumbnail' . '/', $this->session->userdata['login']['photo_path']);
                                    } else {
                                        $profile_photo = base_url() . '/assets/images/user.png';
                                    }
                                    ?>
                                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <img src="<?= $profile_photo ?>" alt="..."><?= $this->session->userdata['login']['first_name'] . ' ' . $last_name; ?>
                                        <span class=" fa fa-angle-down"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                                        <li><a href="<?= base_url('profile'); ?>"><strong>Profile</strong></a></li>
                                        <li><a href="#"><?= '<strong>Last Login Details</strong>' . "<br>" . "Date : " . htmlentities($last_login, ENT_QUOTES) . "<br>" . "IP : " . htmlentities($login_time[0]->ip_address, ENT_QUOTES); ?></a></li>                                        
                                        <li><a href="<?= base_url('login/logout'); ?>"><i class="fa fa-sign-out pull-right"></i><strong>Log Out</strong></a></li>
                                    </ul>
                                </li>


                                <div class="col-md-4 col-sm-12 col-xs-12 top_search visible-lg visible-md visible-sm" style="margin-top: 13px">
                                    <?php
                                    $attribute = array('name' => 'search', 'id' => 'search', 'autocomplete' => 'off');
                                    echo form_open('dashboard/search', $attribute);
                                    ?>
                                    <div class="input-group">
                                        <input class="form-control" name="search" id="search" maxlength="30" required placeholder="Efiling Search" type="text">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit" value="1" name="submit"> Go!</button>
                                        </span>
                                    </div>
                                    <?php echo form_close(); ?>  
                                </div>
                                <div class="col-md-4 col-sm-12 col-xs-12 top_search visible-lg visible-md visible-sm" style="margin-top: 13px">
                                    <?php
                                    $attribute = array('name' => 'search', 'id' => 'search', 'autocomplete' => 'off');
                                    echo form_open('dashboard/search_efiling', $attribute);
                                    ?>
                                    <div class="input-group">
                                        <input class="form-control" name="search_case" id="search" maxlength="30" required placeholder="Law Search ..." type="text">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit" value="1" name="submit"> Go!</button>
                                        </span>
                                    </div>
                                    <?php echo form_close(); ?>  
                                </div>

                            </ul>
                        </nav>
                        <div class="col-xs-12 top_search visible-xs center-block" style="margin-top:20px;">
                            <?php
                            $attribute = array('name' => 'search', 'id' => 'search', 'autocomplete' => 'off');
                            echo form_open('dashboard/search', $attribute);
                            ?>
                            <div class="input-group">
                                <input class="form-control" name="search" id="search" required placeholder="Search for..." type="text">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit" value="1" name="submit"> Go!</button>
                                </span>
                            </div>
                            <?php echo form_close(); ?>  
                        </div> 
                        <div class="col-xs-12 top_search visible-sm visible-xs center-block" style="margin-top:20px;">
                            <ul class="legend list-unstyled">
                                <li>
                                    <a href="<?= base_url('dashboard'); ?>" class="btn btn-info btn-xs"><i class="fa fa-home"></i>Home</a>                                    
                                    <a href="<?= base_url('case_file/type/' . htmlentities(url_encryption(E_FILING_TYPE_CDE), ENT_QUOTES)); ?>" class="btn btn-dark btn-xs"><i class="fa fa-database"></i>Case Data Entry</a>                                                                        
                                    <a href="<?= base_url('news_event/'); ?>" class="btn btn-warning btn-xs"><i class="fa fa-question-circle"></i>Assistance</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /top navigation -->



