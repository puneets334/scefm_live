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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>eFiling | Admin </title>
    <link href="<?= base_url('assets/vendors/bootstrap/dist/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendors/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendors/nprogress/nprogress.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/build/css/custom.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/build/css/custom.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/jquery-ui.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/select2.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/header_css.css'); ?>" rel="stylesheet">

    <script type="text/javascript"> var base_url = '<?php echo base_url(); ?>';</script>
    <?php
    if(uri_string() == 'superAdmin/DefaultController/registrationForm' || uri_string() == 'filingAdmin' || uri_string() == 'filingAdmin/DefaultController'){
        ?>
        <script type="text/javascript" src="<?= base_url('assets/js/jquery-main.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/multiselect/bootstrap-multiselect.js'); ?>"></script>
        <link href="<?= base_url('assets/multiselect/bootstrap-multiselect.css'); ?>" rel="stylesheet">

    <?php
    }
    else{
        ?>
        <script type="text/javascript" src="<?= base_url('assets/js/jquery-main.js'); ?>"></script>
    <?php
    }
    ?>

    <script src="<?= base_url('assets/js/jquery-ui.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/js/jquery_validation.js'); ?>" ></script>
    <script type="text/javascript" src="<?= base_url('assets/js/header.js'); ?>" ></script>
    <script>
    /*function openNav() {
      document.getElementById("mySidenav").style.width = "250px";
    }
    function closeNav() {
      document.getElementById("mySidenav").style.width = "0";
    }*/



    $(document).on('click', '#menu_toggle', function () {
        var element_get = $('body').attr('class');
        if (element_get == 'nav-sm') {
            $('.show_sm_div').removeClass("visible-xs visible-sm");
            $('.show_sm_div').show();
            $('.show_md_div').removeClass("visible-lg visible-md");
            $('.show_md_div').hide();

        } else {
            $('.show_md_div').show();
            $('.show_sm_div').addClass('visible-sm visible-xs');
            $('.show_sm_div').hide();
        }

    });
    </script>
    <style>

    .sidenav {
      height: 100%;
      width: 0;
      position: fixed;
      z-index: 1;
      top: 0;
      left: 0;
      background-color: #fafafa;
      overflow-x: hidden;
      transition: 0.5s;
      padding-top: 60px;
    }

    .sidenav a {
      padding: 8px 8px 8px 32px;
      text-decoration: none;
      color: #818181;
      display: block;
      transition: 0.3s;
    }

    .sidenav a:hover {
      color: black;
    }
    .sidenav ul li a:hover {
      color: black;
    }
    .sidenav .closebtn {
      position: absolute;
      top: 0;
      right: 25px;
      font-size: 36px;
      margin-left: 50px;
    }

    @media screen and (max-height: 450px) {
      .sidenav {padding-top: 15px;}
      .sidenav a {font-size: 10px;}
    }
.resources{display: none !important;}
    </style>
</head>
    <?php
    if ($this->uri->segment(1) == 'resources') { $resources='resources'; }else{$resources='';}
    /*$bg_color = "#EDEDED;";
    $bg_border = "#EDEDED;border-right: 0.1px solid rgba(92, 80, 80, 0.3)";*/
    ?>
    <body class="nav-sm" style="background-color:#ffffff;">
        <div class="container body" >
            <div class="main_container">
                <div class="col-md-3 left_col  <?=$resources;?>" style="background-color:#EDEDED;border-right: 0.1px solid rgba(92, 80, 80, 0.3)">
                    <div class="left_col scroll-view" style="background-color:#EDEDED;">
                        <div class="navbar nav_title" style="background-color:#EDEDED;border-right: 0.1px solid rgba(92, 80, 80, 0.3)">
                            <!--<a href="http://10.25.78.22:97/login" class="site_title"><span class="text_color"><i class="fa fa-gavel"></i> <span>e-Filing</span></span></a>-->

                            <a href="<?= base_url('login'); ?>" class="site_title"><span class="text_color"><i class="fa fa-home"></i></span></a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr_class">

                        <!-- sidebar menu -->
                        <?php
                        if((!empty($this->session->userdata('login')['ref_m_usertype_id'])) && ($this->session->userdata('login')['ref_m_usertype_id'] == USER_SUPER_ADMIN )){
                        ?>
                            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                                <div class="menu_section">
                                    <div class="show_md_div legend list-unstyled center nav-md" style="display: none;">
                                        <div class="row">
                                            <div class="show_md_div legend list-unstyled center nav-md" style="display: none;">
                                                <div class="row">
                                                    <div class="col-lg-10 col-md-10 col-md-offset-1">
                                                        <ul class="legend list-unstyled">
                                                            <li> <a href="<?= base_url('superAdmin'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-home"></i> Home</a></li>
                                                            <li> <a href="<?php echo base_url('superAdmin/DefaultController/registrationForm'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-database"></i> Registration</a> </li>
                                                            <li> <a href="<?php echo base_url('superAdmin/DefaultController'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-user"></i> User List</a> </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="nav side-menu">
                                        <?php if ($this->uri->segment(1) == 'superAdmin') { ?>

                                            <!--<li><a href="<?= base_url('dashboard') ?>" style="background-color: #737373 !important;"><i class="fa fa-dashboard "></i> DASHBOARD </a></li>
                                                                        <div class="clearfix"></div><br>-->
                                        <?php } else if ($this->uri->segment(1) == 'profile') { ?>

                                            <li><a href="<?= base_url('profile') ?>" style="background-color: #737373 !important;"><i class="fa fa-dashboard "></i> PROFILE </a></li>
                                            <div class="clearfix"></div><br><?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        <?php
                        }
                        else if((!empty($this->session->userdata('login')['ref_m_usertype_id'])) && ($this->session->userdata('login')['ref_m_usertype_id'] == USER_EFILING_ADMIN  )){
                        ?>
                            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                                <div class="menu_section">
                                    <div class="show_md_div legend list-unstyled center nav-md" style="display: none;">
                                        <div class="row">
                                            <div class="show_md_div legend list-unstyled center nav-md" style="display: none;">
                                                <div class="row">
                                                    <div class="col-lg-10 col-md-10 col-md-offset-1">
                                                        <ul class="legend list-unstyled">
                                                            <li> <a href="<?php echo base_url('filingAdmin/DefaultController'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-user"></i> User Listing</a> </li>
                                                            <li> <a href="<?php echo base_url('filingAdmin/DefaultController/userFileTransferForm'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-database"></i> User File Transfer</a> </li>
                                                            <li> <a href="<?php echo base_url('adminReport/DefaultController/reportForm'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-file"></i>Work Done Reports</a> </li>
                                                            <li> <a href="<?= base_url('adminDashboard'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-home"></i> Dashboard</a></li>
                                                            <li> <a href="<?= base_url('report'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-clipboard"></i> Reports</a> </li>
                                                            <li> <a href="<?= base_url('admin/noc_vakalatnama'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-clipboard"></i> NOC Vakaltnama</a> </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="nav side-menu">
                                        <?php if ($this->uri->segment(1) == 'superAdmin') { ?>

                                            <!--<li><a href="<?= base_url('dashboard') ?>" style="background-color: #737373 !important;"><i class="fa fa-dashboard "></i> DASHBOARD </a></li>
                                                                        <div class="clearfix"></div><br>-->
                                        <?php } else if ($this->uri->segment(1) == 'profile') { ?>

                                            <li><a href="<?= base_url('profile') ?>" style="background-color: #737373 !important;"><i class="fa fa-dashboard "></i> PROFILE </a></li>
                                            <div class="clearfix"></div><br><?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        <?php
                        }
                        else{
                            ?>
                            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                                <div class="menu_section">
                                    <div class="show_md_div legend list-unstyled center nav-md" style="display: none;">
                                        <div class="row">
                                            <div class="show_md_div legend list-unstyled center nav-md" style="display: none;">
                                                <div class="row">
                                                    <div class="col-lg-10 col-md-10 col-md-offset-1">
                                                        <ul class="legend list-unstyled">
                                                            <?php
                                                            if ($this->uri->segment(1) == 'registrarActionDashboard') { ?>
                                                                <li> <a href="<?= base_url('adminDashboard'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-home"></i> Home</a></li>


                                                            <?php } else { ?>
                                                               <?php if((!empty($this->session->userdata('login')['ref_m_usertype_id'])) && ($this->session->userdata('login')['ref_m_usertype_id'] != USER_ADMIN_READ_ONLY  )){?>
                                                                <li> <a href="<?= base_url('adminDashboard'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-home"></i> Home</a></li>
                                                                <li> <a href="<?php echo base_url('newRegister/Advocate'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-database"></i> Registration</a> </li>
                                                                <li> <a href="<?= base_url('admin/supadmin/change_case_status'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-cog"></i> Administration</a> </li>
                                                                <li> <a href="<?= base_url('assistance/notice_circulars/'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-question-circle"></i> Assistance</a> </li>
                                                                <!--<li> <a href="<?/*= base_url('assistance/performas/'); */?>" class="btn btn-default btn-xs" style="width: 100px;"><i class="fa fa-question-circle"></i> Performa(s)</a> </li>-->
                                                                <li> <a href="<?php echo base_url('adminReport/DefaultController/reportForm'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-file"></i>Work Done Reports</a> </li>
                                                                <?php } ?>
                                                                <li> <a href="<?= base_url('adminDashboard'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-home"></i> Dashboard</a></li>
                                                                <li> <a href="<?= base_url('report'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-clipboard"></i> Reports</a> </li>
                                                            <?php } ?>


                                                            <?php  if(!empty($this->session->userdata('login')['ref_m_usertype_id']) && $this->session->userdata('login')['ref_m_usertype_id'] == USER_ADMIN) {?>
                                                            <li> <a href="<?= base_url('adminReport/Reports/search'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-clipboard"></i> Send Mail (SC-eFM Statistics)</a> </li>
                                                            <li> <a href="<?= base_url('adminReport/search'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-clipboard"></i> Download Files Casewise</a> </li>

                                                            <?php
                                                                // these all are id od efil.tbl_users table.Task to show NOC vakalatnama option to the following users also along with Jagmohan ji , as per mail dated march 22, 2024
                                                                $allowed_ids_for_noc_vakalatnama = array(6086,6105,6108,6109,6088,9474);
                                                                $current_id = $this->session->userdata('login')['id'];

                                                                if (in_array($current_id, $allowed_ids_for_noc_vakalatnama)) {?>
                                                                    <li> <a href="<?= base_url('admin/noc_vakalatnama'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-clipboard"></i> NOC Vakaltnama</a> </li>
                                                                <?php   }
                                                            } ?>
                                                            <!--Added on 03-11-2023 to allow Shahnawaz for downloading PDF files-->
                                                            <?php  if(!empty($this->session->userdata('login')['ref_m_usertype_id']) && $this->session->userdata('login')['ref_m_usertype_id'] == USER_ADMIN_READ_ONLY && !empty($this->session->userdata('login')['userid']) && ($this->session->userdata('login')['userid'] == 'SCI4599' || $this->session->userdata('login')['userid'] == 'SCI4578' || $this->session->userdata('login')['userid'] == 'SCI4581')) {?>
                                                                <li> <a href="<?= base_url('adminReport/search'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-clipboard"></i> Download Files Casewise</a> </li>
                                                            <?php } ?>
                                                            <!--END-->

                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <ul class="nav side-menu">
                                        <?php
                                        //$this->load->view('templates/user_navigation/assist_news_event_nav'); ?>
                                        <?php if ($this->uri->segment(1) == 'adminDashboard') { ?>

                                            <!--<li><a href="<?= base_url('dashboard') ?>" style="background-color: #737373 !important;"><i class="fa fa-dashboard "></i> DASHBOARD </a></li>
                                                                        <div class="clearfix"></div><br>-->
                                        <?php } else if ($this->uri->segment(1) == 'profile') { ?>

                                            <li><a href="<?= base_url('profile') ?>" style="background-color: #737373 !important;"><i class="fa fa-dashboard "></i> PROFILE </a></li>
                                            <div class="clearfix"></div><br><?php
                                        }

                                        if ($this->uri->segment(2) == 'stageList') {
                                            $this->load->view('templates/admin_navigation/admin_stage_list_nav');
                                        }
                                        if ($this->uri->segment(2) == 'supadmin' || $this->uri->segment(3) == 'change_case_status' || $this->uri->segment(3) == 'contact' ||
                                            $this->uri->segment(3) == 'view' || $this->uri->segment(3) == 'add_contact' || $this->uri->segment(3) == 'case_type' ||
                                            $this->uri->segment(3) == 'add_case_type') {
                                            $this->load->view('templates/admin_navigation/administration_nav');
                                        }
                                        if ($this->uri->segment(2) == 'notice_circulars' || $this->uri->segment(1) == 'contact_us' || $this->uri->segment(2) == 'performas') {
                                            $this->load->view('templates/user_navigation/assist_news_event_nav');
                                        }

                                        if ($this->uri->segment(2) == 'Advocate') {
                                            $this->load->view('templates/admin_navigation/new_regisiter_nav');
                                        }
                                        ?>
                                        
                                    </ul>
                                </div>
                            </div>
                        <?php
                        }
                        ?>

                        <!-- sidebar menu -->
                    </div>
                </div>
                <!--<div class="container-fluid body" >-->

                <!-- top navigation -->
                    <div class="top_nav   <?=$resources;?>" >
                        <div class="nav_menu">
                            <nav>
                                <div class="nav toggle">
                                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                                </div>
                                <!--<span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>-->
                                <!-- <a href="<?/*= base_url('login'); */?>" class="site_title" style="display: inline;"><span class="text_color"><i class="fa fa-gavel"></i></span></a>-->
                                <ul class="nav navbar-nav navbar-right " style="margin-top: 5px;width: 80%;">
                                    <div class="col-md-3 col-sm-12 col-xs-12 top_search visible-lg visible-md visible-sm" >
                                        <!-- COPY START FROM HERE -->
                                        <?php
                                        $attribute = array('name' => 'search', 'id' => 'search', 'autocomplete' => 'off');
                                        echo form_open('search/Search_result', $attribute);
                                        if(!empty($this->session->userdata('login')['ref_m_usertype_id'])  && ($this->session->userdata('login')['ref_m_usertype_id'] = USER_SUPER_ADMIN)){
                                        ?>

                                        <?php
                                        }
                                        else if(!empty($this->session->userdata('login')['ref_m_usertype_id'])  && ($this->session->userdata('login')['ref_m_usertype_id'] = USER_SUPER_ADMIN)){
                                        ?>

                                        <?php
                                        }
                                        else{
                                            ?>

                                            <div class="input-group" style="margin-bottom: 2px;">
                                                <input class="form-control" name="search" id="search" maxlength="30" required placeholder="Search for..." type="text">
                                                <span class="input-group-btn"><button class="btn btn-default" type="submit" value="1" name="submit"> Go!</button></span>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <?php echo form_close(); ?>
                                    </div>
                                    <?php
                                    if(!empty($this->session->userdata('login')['ref_m_usertype_id'])  && ($this->session->userdata('login')['ref_m_usertype_id'] = USER_SUPER_ADMIN)){
                                    ?>
                                    <?php
                                        }
                                        else if(!empty($this->session->userdata('login')['ref_m_usertype_id'])  && ($this->session->userdata('login')['ref_m_usertype_id'] = USER_SUPER_ADMIN)){
                                        ?>
                                    <?php
                                        }
                                        else{
                                        ?>

                                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 top_search visible-lg visible-md visible-sm visible-xs" style="margin-top:8px">
                                        <div class="col-xs-12 top_search show_sm_div visible-sm visible-xs" >
                                            <div class="btn-group" >
                                                <ul class="legend list-unstyled">
                                                    <?php
                                                    if ($this->uri->segment(1) == 'registrarActionDashboard') { ?>
                                                        <li> <a href="<?= base_url('adminDashboard'); ?>" class="btn btn-default btn-xs" style="width: 100px;"><i class="fa fa-home"></i> Home</a></li>

                                                    <?php } else  if(!empty($this->session->userdata('login')['ref_m_usertype_id']) && $this->session->userdata('login')['ref_m_usertype_id'] != USER_SUPER_ADMIN){
                                                       ?>
                                                        <!--<li> <a href="<?/*= base_url('assistance/performas/'); */?>" class="btn btn-default btn-xs" style="width: 100px;"><i class="fa fa-question-circle"></i> Performa(s)</a> </li>-->
                                                        <li> <a href="<?= base_url('report'); ?>" class="btn btn-default btn-xs" style="width: 100px;"><i class="fa fa-clipboard"></i> Reports</a> </li>
                                                        <li> <a href="<?= base_url('assistance/notice_circulars/'); ?>" class="btn btn-default btn-xs" style="width: 100px;"><i class="fa fa-question-circle"></i> Assistance</a> </li>
                                                        <li> <a href="<?= base_url('admin/supadmin/change_case_status'); ?>" class="btn btn-default btn-xs" style="width: 110px;"><i class="fa fa-cog"></i> Administration</a> </li>
                                                        <li> <a href="<?php echo base_url('newRegister/Advocate'); ?>" class="btn btn-default btn-xs" style="width: 100px;"><i class="fa fa-database"></i> Registration</a> </li>
                                                        <li> <a href="<?= base_url('adminDashboard'); ?>" class="btn btn-default btn-xs" style="width: 100px;"><i class="fa fa-home"></i> Home</a></li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>

                                    <div class="col-md-3" style="float: right;">
                                        <?php
                                        if(!empty($this->session->userdata('login')['ref_m_usertype_id']) && $this->session->userdata('login')['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
                                        }
                                        else if(!empty($this->session->userdata('login')['ref_m_usertype_id']) && $this->session->userdata('login')['ref_m_usertype_id'] == USER_EFILING_ADMIN) {
                                        }
                                        else{
                                            ?>
                                            <div style="float: left;padding-top:5px; " >
                                                <a href="<?php echo site_url() . $help_page[0]; ?>" onclick="linkopen(event)"> <i class="fa fa-question-circle-o" style="font-size: 24px;"></i></a>
                                                <script>
                                                    function linkopen(event) {
                                                        event.preventDefault();
                                                        window.open("<?php echo site_url('help/View/info/') . $get_valu; ?>", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=500,width=700,height=700");
                                                    }
                                                </script>
                                            </div>
                                        <?php
                                        }
                                        ?>

                                        <?php
                                        $CI = & get_instance();
                                        $CI->load->model('profile/Profile_model');

                                        $profile = $CI->Profile_model->getProfileDetail($this->session->userdata['login']['userid']);

                                        $get_valu = help_id_url(uri_string());
                                        $help_page = explode('.', $get_valu);

                                        $login_time = $CI->Profile_model->userLastLogin($this->session->userdata['login']['id']);
                                        $last_login = ($login_time[0]->login_time != '') ? date('d-m-Y h:i:s A', strtotime($login_time[0]->login_time)) : NULL;

                                        if ($this->session->userdata['login']['photo_path'] != '') {
                                            $profile_photo = str_replace('/photo/', '/' . 'thumbnail' . '/', $_SESSION['login']['photo_path']);
                                        } else {
                                            //$profile_photo = $profile[0]->photo_path;
                                            $profile_photo=base_url('assets/images/alt-image.png');
                                        }
                                        ?>
                                        <div class="top_search visible-lg visible-md visible-sm visible-xs" style="padding-top: 3px;">
                                            <li class="visible-lg visible-md">
                                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="margin: -7px 0 -10px 0;">

                                                    <img src="<?= $profile_photo ?>" ><?= $this->session->userdata['login']['first_name'] . ' ' . $last_name; ?>
                                                    <span class=" fa fa-angle-down" style="display: -webkit-inline-box;"></span>
                                                </a>
                                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                                    <li><a href="<?= base_url('profile'); ?>"><strong>Profile</strong></a></li>
                                                    <li><a href="#"><?= '<strong>Last Login Details</strong>' . "<br>" . "Date : " . htmlentities($last_login, ENT_QUOTES) . "<br>" . "IP : " . htmlentities($login_time[0]->ip_address, ENT_QUOTES); ?></a></li>
                                                    <li><a href="<?= base_url('login/logout'); ?>"><i class="fa fa-sign-out pull-right"></i><strong>Log Out</strong></a></li>
                                                </ul>
                                            </li>
                                        </div>
                                    </div>
                                </ul>
                            </nav>
                        </div><!--END OF DIV class="nav_menu"-->
                    </div><!--END OF DIV class="top_nav"-->



                <!-- sidemenu start -->

                    <div id="mySidenav" class="sidenav">

                        <div class="row" style="margin-top: -64px;">
                            <div class="col-md-10">
                                <a href="http://localhost:81/login" class="site_title"><span class="text_color"><i class="fa fa-gavel"></i> <span>e-Filing</span></span></a>
                            </div>
                            <div class="col-md-2">
                                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                            </div>
                        </div>
                        <br><br>
                        <div class="row">
                            <div class="col-lg-10 col-md-10 col-md-offset-1">
                                <?php
                                if(!empty($this->session->userdata('login')['ref_m_usertype_id']) && ($this->session->userdata('login')['ref_m_usertype_id'] == USER_SUPER_ADMIN)) {
                                }
                                else if(!empty($this->session->userdata('login')['ref_m_usertype_id']) && ($this->session->userdata('login')['ref_m_usertype_id'] == USER_EFILING_ADMIN)) {
                                }
                                else{

                                ?>
                                <ul class="legend list-unstyled">
                                    <li> <a href="<?= base_url('assistance/notice_circulars/'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-home"></i> Home</a></li>
                                    <li> <a href="<?= base_url('newRegister/Advocate'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-cog"></i> Registration</a> </li>
                                    <li> <a href="<?= base_url('admin/supadmin/change_case_status'); ?>" class="btn btn-default btn-xs " style=" width: 100%;text-align: left;"><i class="fa fa-database"></i> Administration </a> </li>
                                    <li> <a href="<?= base_url('assistance/notice_circulars'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-question-circle"></i> Assistance</a> </li>
                                    <!--<li> <a href="<?/*= base_url('assistance/performas'); */?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-question-circle"></i> Performa(S)</a> </li>-->
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <!-- sidemenu end-->
                    </div><!--END OF DIV class="main_container"-->


        <!--</div>--><!--END OF DIV class="container body"-->
    <!--</body>
</html>-->




