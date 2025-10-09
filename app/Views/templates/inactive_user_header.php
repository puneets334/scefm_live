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

        <title><?= HEADER_TITLE ?> </title>
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
    <body class="nav-md" style="background-color:<?php echo $bg_color; ?> ">
        <div class="container body" >
            <div class="main_container " >
                <div class="col-md-3 left_col leftCol" style="background-color:<?php echo $bg_border; ?>; overflow: auto;">
                    <div class="left_col scroll-view" style="background-color:<?php echo $bg_color; ?>;">
                        <div class="navbar nav_title" style="background-color:<?php echo $bg_border; ?>;">
                            <a href="<?= base_url('login'); ?>" class="site_title"><span class="text_color"><i class="fa fa-gavel"></i> <span>e-Filing</span></span></a>
                        </div>

                        <div class="clearfix"></div>
                        <hr class="hr_class">
                        <!-- sidebar menu -->
                        
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
                              $l_name = $this->session->userdata['login']['last_name'];
                            } else {
                                $l_name = '';
                            }
                            $last_name = strip_tags($l_name);
                            if (strlen($last_name) > 2) {

                                // truncate string
                                $stringCut = substr($last_name, 0, 2);
                                $endPoint = strrpos($stringCut, ' ');

                                //if the string doesn't contain any space then it will cut without word basis.
                                $last_name = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                $last_name .= '... ';
                            }
                              //echo $string;

                            $login_time = $CI->Profile_model->userLastLogin($this->session->userdata['login']['id']);
                            $last_login = ($login_time[0]->login_time != '') ? date('d-m-Y h:i:s A', strtotime($login_time[0]->login_time)) : NULL;
                            if ($this->session->userdata['login']['photo_path'] != '') {
                                $profile_photo = str_replace('/photo/', '/' . 'thumbnail' . '/', $this->session->userdata['login']['photo_path']);
                            } elseif (!empty($_SESSION['photo_path'])) {
                                $profile_photo = $_SESSION['photo_path'];
                            } else {
                                $profile_photo = base_url('assets/images/user.png');
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
                            </ul>
                        </nav>                     
                        
                    </div>
                </div>
                <!-- /top navigation -->



