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
        <title>eFiling | Library Admin </title>
        <link href="<?= base_url('assets/vendors/bootstrap/dist/css/bootstrap.min.css'); ?>" rel="stylesheet">
        <!--<link href="<?/*= base_url('assets/vendors/bootstrap/dist/css/bootstrap.min.css'); */?>" rel="stylesheet">-->
        <link href="<?= base_url('assets/vendors/font-awesome/css/font-awesome.css'); ?>" rel="stylesheet">
        <link href="<?= base_url('assets/vendors/nprogress/nprogress.css'); ?>" rel="stylesheet">
        <link href="<?= base_url('assets/build/css/custom.min.css'); ?>" rel="stylesheet">
        <link href="<?= base_url('assets/build/css/custom.css'); ?>" rel="stylesheet">
        <link href="<?= base_url('assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css'); ?>" rel="stylesheet">
        <link href="<?= base_url('assets/css/jquery-ui.css'); ?>" rel="stylesheet">
        <link href="<?= base_url('assets/css/select2.min.css'); ?>" rel="stylesheet">
        <link href="<?= base_url('assets/css/header_css.css'); ?>" rel="stylesheet">

        <script type="text/javascript"> var base_url = '<?php echo base_url(); ?>';</script>
       <script type="text/javascript" src="<?= base_url('assets/js/jquery-main.js');?>"></script>
         <script src="<?= base_url('assets/js/jquery-ui.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/jquery_validation.js');?>" ></script>
        <script type="text/javascript" src="<?= base_url('assets/js/header.js'); ?>" ></script>


<style>
    .nav_title {height: 42px!important; ;}
    .navbar {min-height: 42px !important;}
    .site_title {height: 52px!important;background: #EDEDED!important;}
    .hr_class{margin-top: 0px!important;}
</style>
    </head>
    <?php
    $bg_color = "#EDEDED;";
    $bg_border = "#EDEDED;border-right: 0.1px solid rgba(92, 80, 80, 0.3)";
    ?>
    <body class="nav-sm" style="background-color:<?php echo $bg_color; ?>">
        <div class="container body" >
            <div class="main_container">
                <div class="col-md-3 left_col" style="height:42px!important;">
                    <div class="left_col scroll-view" style="">
                        <div class="navbar nav_title" style="">
                            <a href="<?= base_url('login'); ?>" class="site_title" style="margin-top: -11px!important;"><span class="text_color"><i class="fa fa-gavel"></i> <span>e-Filing</span></span></a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr_class">

                        <!-- sidebar menu -->
                        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                            <div class="menu_section"> 
                                <div class="show_md_div legend list-unstyled visible-lg visible-md center nav-md" style="display:none  !important;">
                                    <div class="row">
                                        <div class="show_md_div legend list-unstyled visible-lg visible-md center nav-md" style="display:none  !important;">
                                            <div class="row">
                                                <div class="col-lg-10 col-md-10 col-md-offset-1">
                                                    <ul class="legend list-unstyled"> 
                                                        <li> <a href="<?= base_url('assistance/notice_circulars/'); ?>" class="btn btn-default btn-xs" style=" width: 100%;text-align: left;"><i class="fa fa-home"></i> Home</a>

                                                    </ul> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 


                                <ul class="nav side-menu" > 

                                    <?php if ($this->uri->segment(1) == 'adminDashboard') { ?>

                                        <!--                                        <li><a href="<?= base_url('dashboard') ?>" style="background-color: #737373 !important;"><i class="fa fa-dashboard "></i> DASHBOARD </a></li>
                                                                                <div class="clearfix"></div><br>-->
                                    <?php } else if ($this->uri->segment(1) == 'profile') { ?>

                                        <li><a href="<?= base_url('profile') ?>" style="background-color: #737373 !important;"><i class="fa fa-dashboard "></i> PROFILE </a></li>
                                        <div class="clearfix"></div><br><?php
                                    }


                                    ?>

                                </ul>
                            </div>
                        </div>
                        <!-- sidebar menu -->
                    </div>
                </div>
                <!-- top navigation -->
                <div class="top_nav" >
                    <div class="nav_menu">

                        <nav>
                            <div class="nav toggle">
                                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                            </div>
                            <ul class="nav navbar-nav navbar-right " style="margin-top: 5px;width: 80%;">
                                <div class="col-md-3 col-sm-12 col-xs-12 top_search visible-lg visible-md visible-sm" >
                                    <?php
                                    $attribute = array('name' => 'search', 'id' => 'search', 'autocomplete' => 'off');
                                    echo form_open('search/Search_result', $attribute);
                                    ?>
                                    <div class="input-group" style="margin-bottom: 2px;">
                                        <input class="form-control" name="search" id="search" maxlength="30" required placeholder="Search for..." type="text">
                                        <span class="input-group-btn"><button class="btn btn-default" type="submit" value="1" name="submit"> Go!</button></span>
                                    </div>
                                    <?php echo form_close(); ?>  
                                </div>



                                <div class="col-md-3" style="float: right;">
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
                                    ?>
                                    <div class="top_search visible-lg visible-md visible-sm visible-xs" style="padding-top: 3px;">
                                        <li class="visible-lg visible-md"> 
                                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="margin: -7px 0 -10px 0;">
                                                <img src="<?= $profile_photo ?>" alt="<?= $this->session->userdata['login']['first_name'] . ' ' . $l_name; ?>"><?= $this->session->userdata['login']['first_name'] . ' ' . $last_name; ?>
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

                    </div>
                </div>

