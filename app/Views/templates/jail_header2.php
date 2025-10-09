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
    //echo '<meta http-equiv="Content-Security-Policy" content="default-src \'self\' ' . $allow_image_url['host'] . ' http://xxxx \'unsafe-inline\' \'unsafe-eval\';  font-src \'self\' data: ">';//todo:do a review of it. it was commented because it was blocking loading of data:image in <img/> tag
    ?>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= HEADER_TITLE ?> </title>
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
    <script type="text/javascript" src="<?= base_url('assets/js/jquery-main.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/js/newcase/jquery_form_validation.js'); ?>" ></script>
    <script type="text/javascript" src="<?= base_url('assets/js/jquery_validation.js'); ?>" ></script>
    <script type="text/javascript" src="<?= base_url('assets/js/jsencrypt.min.js'); ?>" ></script>
    <script type="text/javascript" src="<?= base_url('assets/js/jquery-ui.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/vendors/jquery/dist/chart.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/js/header.js'); ?>" ></script>

</head>

<?php
$bg_color = "#EDEDED;";
$bg_border = "#EDEDED;border-right: 0.1px solid rgba(92, 80, 80, 0.3)";
?>
<body class="nav-sm"  style="background-color:<?php echo $bg_color; ?>">
<div class="container body" >
    <div class="main_container">
        <div class="col-md-3 left_col " style="background-color:<?php echo $bg_border; ?>">
            <div class="left_col scroll-view" style="background-color:<?php echo $bg_color; ?>">
                <div class="navbar nav_title" style="background-color:<?php echo $bg_border; ?>">
                    <a href="<?= base_url('login'); ?>" class="site_title"><span class="text_color"><i class="fa fa-gavel"></i> <span>e-Filing</span></span></a>
                </div>
                <div class="clearfix"></div>
                <hr class="hr_class">

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                    <div class="show_md_div legend list-unstyled visible-lg visible-md center nav-md" style="display:none  !important;">
                        <div class="row">

                            <div class="show_md_div legend list-unstyled visible-lg visible-md center nav-md" style="display:none  !important;">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-md-offset-1">
                                        <ul class="legend list-unstyled">
                                            <li> <a href="<?= base_url('jail_dashboard'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-home"></i> Home</a>
                                            <li> <a href="<?php echo base_url('jailPetition/NewCase'); ?>" class="btn btn-default btn-xs " style=" width: 100%;text-align: left;"><i class="fa fa-database"></i> e-File</a> </li>
                                            <!--<li> <a href="<?/*= base_url('mycases'); */?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-cog"></i> My Cases</a> </li>
                                            <li> <a href="<?/*= base_url('mycause_list'); */?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-cog"></i> Cause List</a> </li>
                                            <?php
/*                                            $xyz = FALSE;
                                            if ($xyz) {
                                                */?>
                                                <li> <a href="<?/*= base_url('add/clerk'); */?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-cog"></i> Settings</a> </li>
                                            <?php /*} */?>
                                            <li> <a href="<?/*= base_url('assistance/notice_ciruculars/'); */?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-question-circle"></i> Assistance</a> </li>
                                            </li>-->
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <ul class="nav side-menu" >

                        <?php if ($this->uri->segment(1) == 'myUpdates') { ?>

                            <li><a href="<?= base_url('dashboard') ?>" style="background-color: #737373 !important;"><i class="fa fa-dashboard "></i> DASHBOARD </a></li>
                            <div class="clearfix"></div><br>
                        <?php }if ($this->uri->segment(1) == 'case_details') { ?>

                            <li><a href="<?= base_url('case_details') ?>" style="background-color: #737373 !important;"><i class="fa fa-dashboard "></i> Case Details </a></li>
                            <div class="clearfix"></div><br>
                        <?php }if ($this->uri->segment(1) == 'dashboard') { ?>

                            <li><a href="<?= base_url('dashboard') ?>" style="background-color: #737373 !important;"><i class="fa fa-dashboard "></i> DASHBOARD </a></li>
                            <div class="clearfix"></div><br>
                        <?php } else if ($this->uri->segment(1) == 'profile') { ?>

                            <li><a href="<?= base_url('profile') ?>" style="background-color: #737373 !important;"><i class="fa fa-dashboard "></i> PROFILE </a></li>
                            <div class="clearfix"></div><br><?php
                        }else if ($this->uri->segment(1) == 'mycause_list') { ?>

                            <li><a href="<?= base_url('mycause_list') ?>" style="background-color: #737373 !important;"><i class="fa fa-dashboard "></i> CAUSE LIST </a></li>
                            <div class="clearfix"></div><br><?php
                        }

                       /* if (($this->uri->segment(1) == 'newcase') || ($this->uri->segment(1) == 'uploadDocuments')|| ($this->uri->segment(1) == 'affirmation') || ($this->uri->segment(1) == 'documentIndex') || ($this->uri->segment(1) == 'search') || ($this->uri->segment(2) == 'search') || ($this->uri->segment(1) . $this->uri->segment(3) == 'newcase/petitioner')) {
                            $this->load->view('templates/user_navigation/efile_nav');
                        }

                        if ($this->uri->segment(2) == 'stage_list') {
                            $this->load->view('templates/user_navigation/stage_list_nav');
                        }*/

                        if ($this->uri->segment(2) == 'clerk') {
                            $this->load->view('templates/user_navigation/setting_clerk_nav');
                        }

                        if ($this->uri->segment(2) == 'notice_circulars' || $this->uri->segment(2) == 'performas' || $this->uri->segment(1) == 'contact_us') {
                            $this->load->view('templates/user_navigation/assist_news_event_nav');
                        }
                        if ($this->uri->segment(1) == 'mycases') {
                            //$this->load->view('templates/user_navigation/mycases_nav');
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <!-- /sidebar menu -->
        </div>
        <!-- top user_navigation -->
        <div class="top_nav">
            <div class="nav_menu" id="navbar"  >
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>
                    <ul class="nav navbar-nav navbar-right">

                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 top_search visible-lg visible-md visible-sm visible-xs" style="margin-top:8px">
                            <div class="col-xs-12 top_search show_sm_div visible-sm visible-xs" >
                                <div class="btn-group"  style="float:right;"    >
                                    <ul class="legend list-unstyled">
                                       <!-- <li> <a href="<?/*= base_url('assistance/notice_circulars'); */?>" class="btn btn-default btn-xs" style="width: 100px;"><i class="fa fa-question-circle"></i> Assistance</a> </li>
                                        <?php
/*                                        $xyz = FALSE;
                                        if ($xyz) {
                                            */?>
                                            <li> <a href="<?/*= base_url('add/clerk'); */?>" class="btn btn-default btn-xs" style="width: 100px;"><i class="fa fa-cog"></i> Settings</a> </li>
                                        <?php /*} */?>
                                        <li> <a href="<?/*= base_url('mycases'); */?>" class="btn btn-default btn-xs" style="width: 100px;"><i class="fa fa-cog"></i> My Cases</a> </li>
                                        <li> <a href="<?/*= base_url('mycause_list'); */?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-list"></i> Cause List</a> </li>
                                       --> <li> <a href="<?php echo base_url('jailPetition/NewCase'); ?>" class="btn btn-default btn-xs" style="width: 100px;"><i class="fa fa-database"></i> e-File</a> </li>
                                        <li> <a href="<?= base_url('jail_dashboard'); ?>" class="btn btn-default btn-xs" style="width: 100px;"><i class="fa fa-home"></i> Home</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 top_search visible-lg visible-md visible-sm visible-xs">
                            <!--<div class="col-md-3"  >
                                <button class="btn btn-secondary btn-md"  type="button" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false" style=" border-radius: 0;margin-bottom: 0;">
                                    <i class="fa fa-search" style="font-size: 20px;" > </i>
                                </button>
                                <div class="dropdown-menu search-menu" style="width:300px;background-color: #ededed;right: unset;">
                                    <br>
                                    <li class="dropdown-item search-data" href="#"><?php
/*                                        $attribute = array('name' => 'search', 'id' => 'search', 'autocomplete' => 'off');
                                        echo form_open('search/search_result', $attribute);
                                        */?>
                                        <div class="input-group">
                                            <input class="form-control" name="search" id="search" maxlength="30" required placeholder="Efiling Search" type="text">
                                            <span class="input-group-btn">
                                                        <button class="btn btn-default" type="submit" value="1" name="submit"> Go!</button>
                                                    </span>
                                        </div>
                                        <?php /*echo form_close(); */?>
                                    </li>

                                    <li class="dropdown-item" href="#">
                                        <?php
/*                                        //$attribute = array('name' => 'search', 'id' => 'search', 'autocomplete' => 'off');
                                        //echo form_open('dashboard/lawsearch', $attribute);
                                        */?>

                                        <div class="input-group">
                                            <input class="form-control" name="search_case" id="search_case" maxlength="30" required placeholder="Law Search ..." type="text">
                                            <span class="input-group-btn">
                                                        <button class="btn btn-default" onclick="openlawsearch(event)"  type="" value="1" name="submit"> Go!</button>
                                                    </span>
                                        </div>
                                        <script>
                                            function openlawsearch(event) {
                                                var search_txt = $('#search_case').val();
                                                var uri = "https://indiacode.nic.in/handle/123456789/1362//simple-search?query=" + search_txt + "&btngo=&searchradio=acts";
                                                uri = encodeURI(uri);
                                                window.open(uri);
                                            }
                                        </script>
                                        <?php /*// echo form_close();    */?>
                                    </li>
                                    <div class="dropdown-divider"></div>
                                    <li class="dropdown-item" href="#"><a data-toggle="modal" href="#case_data_model" id="case_data" class="case_data_value"  title=""><span style='font-size: 14px;'> Case & Order National Search </span> </a></li>

                                </div>

                            </div>
-->
                            <div class="col-md-5" style="float: right;">
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
                                    $profile_photo = $profile[0]->photo_path;
                                }
                                $photo_url = base_url($profile_photo);
                                $photo_url = file_exists($photo_url) ? $photo_url : '#';
                                ?>
                                <div class="top_search visible-lg visible-md visible-sm visible-xs" style="padding-top: 3px;">
                                    <li class="visible-lg visible-md">
                                        <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="margin: -7px 0 -10px 0;">
                                            <img src="<?= $photo_url; ?>" alt="<?= $this->session->userdata['login']['first_name'] . ' ' . $l_name; ?>"><?= $this->session->userdata['login']['first_name'] . ' ' . $last_name; ?>
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
                        </div>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <?php $this->load->view('templates/integrated_search_view'); ?>
