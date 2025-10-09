<!DOCTYPE html>
<html lang="en" style="background:url() ">
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

    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/jsAlert.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/style.css">
    <!--multiselect drown down  css and js files files-->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-multiselect.css" type="text/css"/>


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


    <script src="<?php echo base_url()?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="<?php echo base_url()?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/fastclick/fastclick.js"></script>
    <script src="<?php echo base_url()?>assets/js/app.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="<?php echo base_url()?>assets/js/alerts.js"></script>
    <script src="<?php echo base_url()?>assets/js/jsAlert.min.js"></script>

    <!---datatable for pagination-->
    <script src="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>

</head>
<style>
    .login_box_logo {
        width: 70px;
        height: 60px;
        border-radius: 100px;
        background: #fff;

    }
    .login_box_logo img {
        width: auto;
        height: 100%;
    }
</style>
<?php
$bg_color = "#EDEDED;";
$bg_border = "#EDEDED;border-right: 0.1px solid rgba(92, 80, 80, 0.3)";
?>
<body class="nav-sm"  style="background-color:<?php echo $bg_color; ?>">
<div class="container body" >
    <div class="main_container">
       <!-- <div class="col-md-3 left_col " >
            <div class="left_col scroll-view">-->
               <!-- <div style="background-color:<?php /*echo $bg_border; */?>">
                </div>
                <div class="clearfix"></div>
                <hr class="hr_class">-->

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
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
          <!--  </div>
            <!-- /sidebar menu -->
        <!-- </div>-->
        <!-- top user_navigation -->
        <div class="top_nav" style="margin-left: 0px;">
            <div class="nav_menu" id="navbar" >
                <nav>
                    <!--<div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>-->

                    <ul class="nav navbar-nav navbar-right" style="width: 100%;">

                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 top_search visible-lg visible-md visible-sm visible-xs" style="margin-top:8px">
                            <div class="col-xs-12 top_search show_sm_div visible-sm visible-xs" >
                               <!-- <left>-->
                                   <img src="<?=base_url()?>assets/images/sci_logo_gold.png" height="75">
                                <!--</left>-->
                                <div class="btn-group"  style="float:right;">
                                    <ul class="legend list-unstyled">
                                        <li> <a href="<?php echo base_url('jailPetition/NewCase'); ?>" class="btn btn-default btn-xs" style="width: 100px;"><i class="fa fa-database"></i> e-File</a> </li>
                                        <li> <a href="<?= base_url('jail_dashboard'); ?>" class="btn btn-default btn-xs" style="width: 100px;"><i class="fa fa-home"></i> Home</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5 col-lg-5 col-sm-12 col-xs-12 top_search visible-lg visible-md visible-sm visible-xs">
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

                                ?>
                                <div class="top_search visible-lg visible-md visible-sm visible-xs" style="padding-top: 3px;">
                                    <li class="visible-lg visible-md">
                                        <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="margin: -7px 0 -10px 0;">
                                            <?= $this->session->userdata['login']['first_name'] . ' ' . $last_name; ?>
                                            <span class=" fa fa-angle-down" style="display: -webkit-inline-box;"></span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-usermenu pull-right">
                                            <!--<li><a href="<?/*= base_url('profile'); */?>"><strong>Profile</strong></a></li>-->
                                            <li><a href="#"><?= '<strong>Last Login Details</strong>' . "<br>" . "Date : " . htmlentities($last_login, ENT_QUOTES); ?></a></li>
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
