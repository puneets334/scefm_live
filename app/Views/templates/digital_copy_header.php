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
        <script type="text/javascript" src="<?= base_url('assets/js/jquery-main.js'); ?>"></script>
        <script src="<?= base_url('assets/js/jquery-ui.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/jquery_validation.js'); ?>" ></script>
        <script type="text/javascript" src="<?= base_url('assets/js/header.js'); ?>" ></script>

    </head>

    <?php
    $bg_color = "#EDEDED;";
    $bg_border = "#EDEDED;border-right: 0.1px solid rgba(92, 80, 80, 0.3)";
    ?>
    <body class="nav-sm" id="nav_id" style="background-color:<?php echo $bg_color; ?>">
        <div class="container body" >
            <div class="main_container " >
                <div class="col-md-3 left_col leftCol" style="background-color:<?php echo $bg_border; ?>; overflow:unset;" >
                    <div class="left_col scroll-view" style="background-color:<?php echo $bg_color; ?>;"> 
                        <div class="navbar nav_title  " style="background-color:<?php echo $bg_border; ?>; ">
                            <a href="<?= base_url('login'); ?>" class="site_title"><span class="text_color"><i class="fa fa-gavel"></i> <span>e-Filing</span></span></a>
                        </div>

                        <!--                        <ul class="nav navbar-nav navbar-right ">
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
                            $profile_photo = base_url('assets/images/user.png');
                        }
                        ?>
                                                        <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                            <img src="<?= $profile_photo ?>" alt="<?= $this->session->userdata['login']['first_name']; ?>"><?= $this->session->userdata['login']['first_name'] . ' ' . $last_name; ?>
                                                            <span class=" fa fa-angle-down"></span>
                                                        </a>
                                                        <ul class="dropdown-menu dropdown-usermenu pull-right">
                                                            <li><a href="<?= base_url('profile'); ?>"><i class="fa fa-user"></i> <strong>Profile</strong></a></li>
                                                            <li><a href="#"><?= '<strong>Last Login Details</strong>' . "<br>" . "Date : " . htmlentities($last_login, ENT_QUOTES) . "<br>" . "IP : " . htmlentities($login_time[0]->ip_address, ENT_QUOTES); ?></a></li>                                        
                                                            <li><a href="<?= base_url('login/logout'); ?>"><i class="fa fa-sign-out pull-right"></i><strong>Log Out</strong></a></li>
                                                        </ul>
                                                    </li>
                        
                                                </ul>-->
                        <div class="clearfix"></div>
                        <hr class="hr_class">
                        <div class="legend list-unstyled visible-lg  center">
                            <div class="row">
                                <div class="col-lg-1 col-md-1"></div>
                                <div class="col-lg-10 col-md-10">
                                    <div class="topnav" id="topnav_id">

                                        <?php
                                        if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN) {
                                            $href = base_url('admin');
                                        } elseif ($this->session->userdata['login']['ref_m_usertype_id'] == USER_DISTRICT_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
                                            $href = base_url('admin/work_done');
                                        }
                                        ?>

<!--                                        <a href="<?= $href; ?>" class="btn btn-info btn-xs"><i class="fa fa-home" style="font-size:15px"></i> Home</a>
                                        <a href="<?= base_url('admin/work_done'); ?>" class="btn btn-dark btn-xs"><i class="fa fa-database" style="font-size:15px"></i> Reports </a> <br>
                                        <a href="<?= base_url('digital_copy'); ?>" class="btn btn-default btn-xs"><i class="fa fa-file"></i> ePaper Book</a>-->

                                        <div class='clearfix'></div>
                                        <?php
                                        if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN) {
                                            $url = base_url("admin_stages/view/" . htmlentities(url_encryption(New_Filing_Stage, ENT_QUOTES)));
                                            $title = " eFiled";
                                            $width = "style=width:74px;";
                                        } if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_DISTRICT_ADMIN) {
                                            $url = base_url('district_admin/change_case_status/');
                                            $title = " Administration";
                                        }
                                        if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN) {
                                            $url = base_url('master_admin/change_case_status/');
                                            $title = " Administration";
                                        }
                                        if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
                                            $url = base_url('supadmin/change_case_status/');
                                            $title = " Administration";
                                        }
                                        ?> 
                                        <div class='clearfix'></div>
                                        <?php if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_DISTRICT_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) { ?>
                                        <!--                                            <a href="<?php echo $url; ?>" class="btn btn-primary btn-xs " <?php echo $width; ?>><i class="fa fa-database"><?php echo $title; ?></i></a>-->
                                        <?php } if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN) { ?>
                                        <!--                                            <a href="<?= base_url('admin/new_registration'); ?>" class="btn btn-success btn-xs" style="width:95px"><i class="fa fa-user-plus" style="font-size:12px"></i>Registration</a>-->
                                        <?php } ?><?php if ($this->session->userdata['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) { ?>
                                        <!--                                            <a href="<?= base_url('news_event/'); ?>" class="btn btn-warning btn-sm"><i class="fa fa-question-circle" style="font-size:15px"></i>Assistance</a>-->
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-lg-1 col-md-1"></div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <!-- sidebar menu -->
                        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                            <div class="menu_section">
                                <ul class="nav side-menu" >

                                    <?php if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_ADMIN && empty($this->uri->segment(2)) && $this->uri->segment(1) == 'admin' || $this->session->userdata['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN) { ?>
                                        <li class="highlight_tab"><a  class="text_cursor active_tab text_position"><i class="fa fa-files-o fa-fw" style="color:#fff;"></i> <strong style="color:#fff;"> Home</strong> </a> </li>
                                    <?php } ?>

                                    <?php if ($this->uri->segment(2) == 'work_done' || $this->uri->segment(2) == 'work_done_filter' || $this->uri->segment(2) == 'court_fee_details' || $this->uri->segment(2) == 'transaction_details' || $this->uri->segment(2) == 'admin_users_list') { ?>
                                        <li class="highlight_tab"><a class="text_cursor active_tab text_position" ><i class="fa fa-files-o fa-fw" style="color:#fff;"></i> <strong style="color:#fff;"> Reports</strong> </a> </li>
                                        <li class="highlight"><a href="<?= base_url('admin/work_done'); ?>"> <strong class="text_color ">  Work Done</strong></a></li>
                                        <li class="highlight"><a href="<?= base_url('admin/work_done_filter'); ?>"> <strong class="text_color">  efiling Done</strong></a></li>

                                        <?php if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) { ?>
                                            <li class="highlight"><a href="<?= base_url('admin/login_logs'); ?>"><strong class="text_color"> View Login Log Details </strong> </a></li>                                        
                                        <?php } ?>
                                        <?php if ($this->session->userdata['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN && $this->session->userdata['login']['ref_m_usertype_id'] != USER_DISTRICT_ADMIN && $this->session->userdata['login']['ref_m_usertype_id'] != USER_MASTER_ADMIN) { ?>
                                            <li class="highlight"><a href="<?= base_url('admin/court_fee_details') ?>"> <strong class="text_color">  Fees Paid</strong></a></li>                                               

                                            <?php
                                            if ($this->session->userdata['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN &&
                                                    $this->session->userdata['login']['ref_m_usertype_id'] != USER_DISTRICT_ADMIN && $this->session->userdata['login']['ref_m_usertype_id'] != USER_MASTER_ADMIN && ENABLE_TRANSACTION_DETAIL) {
                                                ?>
                                                <li class="highlight"><a href="<?= base_url('admin/transaction_details'); ?>"> <strong class="text_color"> Transaction Details</strong></a></li>
                                                <?php if (ENABLE_SBI_PAYMENT_GATEWAY ) { ?>                                                    
                                                    <li><a href="<?= base_url('sbi_payment_info'); ?>"> <strong class="text_color"> SBI MIS</strong></a></li>
                                                    <li><a href="<?= base_url('sbi_payment_info/payout_info'); ?>"> <strong class="text_color"> SBI Payout</strong></a></li>
                                                    <li><a href="<?= base_url('sbi_payment_info/sbi_report'); ?>"> <strong class="text_color"> Mismatch Report</strong></a></li>
                                                    <?php
                                                }
                                            }
                                            if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN) {
                                                ?>
                                                <li class="highlight"><a href="<?= base_url('admin/admin_users_list'); ?>"> <strong class="text_color">  Users' Actionable Pendency</strong></a></li>
                                                <?php
                                            }
                                        }
                                        ?>

                                    <?php } else if ($this->uri->segment(1) == 'digital_copy' || $this->uri->segment(1) == 'doclist') { ?>
                                        <li class="highlight_tab"><a class="text_cursor active_tab text_position" ><i class="fa fa-book fa-fw" style="color:#fff;"></i> <strong style="color:#fff;">ePaper Book</strong> </a> </li>
                                        <div class="side-menu_new show_sm_div visible-xs visible-sm center nav-sm" style="font-size:13px; font-weight: bold;margin: 13em 0 auto;">
                                            <ul  class="doclist_digital_copy1">  
                                                <a href="<?= base_url('doclist'); ?>"><li style=""><div>ePaper Book Structure </div>
                                                        <div class="hover_text_doclist_digital_copy1 hover_text_css">ePaper Stru.</div>
                                                        <span><i class="fa fa-files-o icon_font_css"></i></span></li></a>
                                            </ul> 
                                            <ul  class="doclist_digital_copy2"> 
                                                <a href="<?= base_url('digital_copy'); ?>"><li style=""><div>Create ePaper Book </div>
                                                        <div class="hover_text_doclist_digital_copy2 hover_text_css">Add ePaper </div>
                                                        <span><i class="fa fa-book icon_font_css"></i></span></li></a>
                                            </ul>
                                            <ul  class="doclist_digital_copy3"> 
                                                <a href="<?= base_url('digital_copy/view'); ?>"><li style=""><div>ePaper Book List </div>
                                                        <div class="hover_text_doclist_digital_copy3 hover_text_css">ePaper List</div>
                                                        <span><i class="fa fa-check icon_font_css"></i></span></li></a>
                                            </ul>
                                        </div>
    <!--                                        <li class="highlight"><a href="<?= base_url('doclist'); ?>"> <strong class="text_color "><i class="fa fa-files-o fa-fw"></i>ePaper Book Structure</strong></a></li>
                                        <li class="highlight"><a href="<?= base_url('digital_copy'); ?>"> <strong class="text_color "><i class="fa fa-book fa-fw"></i>  Create ePaper Book </strong></a></li>
                                        <li class="highlight"><a href="<?= base_url('digital_copy/view'); ?>"> <strong class="text_color "><i class="fa fa-list-ul fa-fw"></i> ePaper Book List </strong></a></li>-->

                                    <?php } ?>
                                    <?php
                                    if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN) {
                                        $CI = &get_instance();
                                        $CI->load->model('AdminDashboard_model');
                                        $total_reg = $CI->AdminDashboard_model->count_new_registered_user();
                                        $total_adv = $CI->AdminDashboard_model->count_pending_advocate_list($_SESSION['login']['admin_for_type_id']);
                                        ?>
                                        <?php if ($this->uri->segment(2) == 'new_registration' || $this->uri->segment(2) == 'pending_advocate_list' || $this->uri->segment(2) == 'new_active_user' || $this->uri->segment(2) == 'rejected_list') { ?>
                                            <li class="highlight_tab"><a class="active_tab "><i class="fa fa-user-plus fa-fw " style="color:#fff;"></i><strong style="color:#fff;"> Registration (<?php echo $total_reg + $total_adv; ?>)</strong> </a></li>
                                            <li class="highlight"><a href="<?= base_url('admin/new_registration'); ?>"> <strong class="text_color">Pending Party in person (<?php echo htmlentities($total_reg, ENT_QUOTES); ?>)</strong></a></li>
                                            <li class="highlight"><a href="<?= base_url('admin/pending_advocate_list'); ?>"> <strong class="text_color"> Pending Advocates (<?php echo htmlentities($total_adv, ENT_QUOTES); ?>)</strong></a></li> 
                                            <li class="highlight"><a href="<?= base_url('admin/new_active_user'); ?>"> <strong class="text_color">Activated Users</strong></a></li>
                                            <li class="highlight"><a href="<?= base_url('admin/rejected_list'); ?>"> <strong class="text_color"> Rejected </strong></a></li>    
                                            <?php
                                        }
                                    }
                                    ?>
                                    <?php
                                    if ($this->session->userdata['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
                                        if ($this->uri->segment(1) == 'news_event' || $this->uri->segment(1) == 'Notice' || $this->uri->segment(2) == 'master_admin_contact_details' || $this->uri->segment(2) == 'district_admin_contact_details' || $this->uri->segment(2) == 'super_admin_contact_details') {
                                            ?>
                                            <li class="highlight_tab">
                                                <a class="text_cursor active_tab"><i class="fa fa-user-plus fa-fw " style="color:#fff;"></i><strong style="color:#fff;"> Assistance</strong> </a></li>
                                            <li class="highlight"><a href="<?= base_url('news_event/'); ?>"><i class="fa fa-newspaper-o text_color"></i> <strong class="text_color"> News And Events </strong> </a></li>
                                            <li class="highlight"> <a href="https://efiling.ecourts.gov.in/adminHelp" target="_blank"><i class="fa fa-info-circle text_color"></i><strong class="text_color">  &nbsp;Admin Manual</strong> </a></li>
                                            <?php if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN) { ?>  
                                                <li class="highlight"><a href="<?= base_url('admin/master_admin_contact_details'); ?>"><i class="fa fa-user-secret text_color"></i>  <strong class="text_color"> Master Admin</strong></a></li>
                                            <?php } ?>
                                            <?php if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_ADMIN) { ?>
                                                <?php if ($this->session->userdata['login']['admin_for_type_id'] == ENTRY_TYPE_FOR_ESTABLISHMENT) { ?> 

                                                    <li class="highlight"><a href="<?= base_url('admin/district_admin_contact_details'); ?>"> <i class="fa fa-user-secret text_color"></i><strong class="text_color">  District Admin</strong></a></li>
                                                    <?php
                                                }
                                            }
                                            ?>                                               
                                            <?php if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_DISTRICT_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN || $this->session->userdata['login']['ref_m_usertype_id'] == USER_ADMIN) { ?>  
                                                <li class="highlight"><a href="<?= base_url('admin/super_admin_contact_details'); ?>"><i class="fa fa-user-secret text_color"></i> <strong class="text_color"> Super Admin</strong></a></li>

                                            <?php } ?>
                                            <?php
                                        }
                                    }


                                    if ($this->uri->segment(1) == 'admin_stages') {
                                        ?>
                                        <li class="highlight_tab"><a class="text_cursor active_tab" ><i class="fa fa-files-o fa-fw active_tab"  style="color:#fff;"></i> <strong style="color:#fff;">e-Filed Stages</strong> </a></li>
                                        <li class="highlight"><a href="<?= base_url("admin_stages/view/" . htmlentities(url_encryption(New_Filing_Stage, ENT_QUOTES))) ?>"><span class="text_color" >New Filing <i class="number_css badge"> 237</i></span></a></li><div class="clearfix"></div>
                                        <li class="highlight"><a href="<?= base_url("admin_stages/view/" . htmlentities(url_encryption(Initial_Defected_Stage, ENT_QUOTES))) ?>"><span class="text_color" >For Compliance<i class="number_css badge"> 48</i></span></a>  </li><div class="clearfix"></div>
                                        <li class="highlight"><a href="<?= base_url("admin_stages/view/" . htmlentities(url_encryption(DEFICIT_COURT_FEE, ENT_QUOTES))) ?>"><span class="text_color" >Pay Deficit Fee<i class="number_css badge">0</i></span></a></li><div class="clearfix"></div>
                                        <li class="highlight"><a href="<?= base_url("admin_stages/view/" . htmlentities(url_encryption(Initial_Defects_Cured_Stage, ENT_QUOTES))) ?>"><span class="text_color" >Complied Objections <i class="number_css badge">0</i></span></a></li><div class="clearfix"></div>
                                        <li class="highlight"><a href="<?= base_url("admin_stages/view/" . htmlentities(url_encryption(Transfer_to_IB_Stage, ENT_QUOTES))) ?>"><span class="text_color" >Transfer to CIS<i class="number_css badge">0</i></span></a></li><div class="clearfix"></div>
                                        <li class="highlight"><a href="<?= base_url("admin_stages/view/" . htmlentities(url_encryption(Transfer_to_CIS_Stage, ENT_QUOTES))) ?>"><span class="text_color" >Get CIS Status<i class="number_css badge">0</i></span></a></li><div class="clearfix"></div>
                                        <li class="highlight"><a href="<?= base_url("admin_stages/view/" . htmlentities(url_encryption(I_B_Approval_Pending_Admin_Stage, ENT_QUOTES))) ?>"><span class="text_color" >Pending Scrutiny<i class="number_css badge">0</i></span></a></li><div class="clearfix"></div>
                                        <li class="highlight"><a href="<?= base_url("admin_stages/view/" . htmlentities(url_encryption(I_B_Defected_Stage, ENT_QUOTES))) ?>"><span class="text_color" >Defective<i class="number_css badge">0</i></span></a></li><div class="clearfix"></div>
                                        <li class="highlight"><a href="<?= base_url("admin_stages/view/" . htmlentities(url_encryption(I_B_Defects_Cured_Stage, ENT_QUOTES))) ?>"><span class="text_color" >Defects Cured<i class="number_css badge">0</i></span></a></li><div class="clearfix"></div>
                                        <li class="highlight"><a href="<?= base_url("admin_stages/view/" . htmlentities(url_encryption(E_Filed_Stage, ENT_QUOTES))) ?>" ><span class="text_color" >Cases<i class="number_css badge">0</i></span></a></li><div class="clearfix"></div>
                                        <li class="highlight"><a href="<?= base_url("admin_stages/view/" . htmlentities(url_encryption(Document_E_Filed, ENT_QUOTES))) ?>"><span class="text_color" >Documents<i class="number_css badge">0</i></span></a></li><div class="clearfix"></div>
                                        <li class="highlight"><a href="<?= base_url("admin_stages/view/" . htmlentities(url_encryption(DEFICIT_COURT_FEE_E_FILED, ENT_QUOTES))) ?>"><span class="text_color" >Paid Deficit Fee<i class="number_css badge">0</i></span></a></li>  <div class="clearfix"></div>
                                        <li class="highlight"><a href="<?= base_url("admin_stages/view/" . htmlentities(url_encryption(IA_E_Filed, ENT_QUOTES))) ?>"><span class="text_color" >IA<i class="number_css badge">0</i></span></a></li>  <div class="clearfix"></div>
                                        <li class="highlight"><a href="<?= base_url("admin_stages/view/" . htmlentities(url_encryption(I_B_Rejected_Stage, ENT_QUOTES))) ?>"><span class="text_color" >Rejected<i class="number_css badge">0</i></span></a></li>  <div class="clearfix"></div>
                                        <li class="highlight"><a href="<?= base_url("admin_stages/view/" . htmlentities(url_encryption(I_B_Defected_Stage, ENT_QUOTES))) ?>"><span class="text_color" >Idle/Unprocessed e-Filed No.'s<i class="number_css badge">0</i></span></a></li> <div class="clearfix"></div>

                                        <?php
                                        // Ends User Dashboard Stages list
                                    }

                                    if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_DISTRICT_ADMIN || $this->uri->segment(1) == 'district_admin') {
                                        if ($this->uri->segment(2) == 'user' || $this->uri->segment(2) == 'contact' || $this->uri->segment(2) == 'change_case_status' || $this->uri->segment(2) == 'case_type') {
                                            ?>
                                            <li> <a class="text_cursor active_tab"><i class="fa fa-user-plus fa-fw text_color"></i><strong class="text_color"> Administration</strong> </a></li>

                                            <li class="highlight"><a href="<?= base_url('district_admin/change_case_status/') ?>"><span class="text_color" > Change Case Status</span></a></li>
                                            <li class="highlight"><a href="<?= base_url("district_admin/user") ?>"><span class="text_color" >Create New Admin</span></a></li>
                                            <li class="highlight"><a href="<?= base_url("district_admin/contact") ?>"><span class="text_color" >Create Contact</span></a></li>
                                            <li class="highlight"><a href="<?= base_url("admin/case_type") ?>"><span class="text_color">CIS Case Type for eFiling </span></a></li>

                                            <?php
                                            // Ends User Dashboard Stages list
                                        }
                                    }
                                    if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN || $this->uri->segment(1) == 'master_admin') {
                                        if ($this->uri->segment(2) == 'user' || $this->uri->segment(2) == 'change_case_status' || $this->uri->segment(2) == 'reason') {
                                            ?>
                                            <li> <a class="text_cursor active_tab"><i class="fa fa-user fa-fw text_color"></i><strong class="text_color"> Administration</strong> </a></li>
                                            <li class="highlight"><a href="<?= base_url('master_admin/change_case_status/') ?>"><span class="text_color" > Change Case Status</span></a></li>
                                            <li class="highlight"><a href="<?= base_url("master_admin/user") ?>"><span class="text_color" >Create New Admin</span></a></li>
                                            <li class="highlight"><a href="<?= base_url("master_admin/reason") ?>"><span class="text_color" >Efiling Number Allocation Reason</span></a></li>


                                            <?php
                                            // Ends User Dashboard Stages list
                                        }
                                    }
                                    if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN || $this->uri->segment(1) == 'supadmin') {
                                        if ($this->uri->segment(2) == 'change_case_status' || $this->uri->segment(2) == 'user' || $this->uri->segment(2) == 'contact' || $this->uri->segment(2) == 'view' || $this->uri->segment(2) == 'case_type' || $this->uri->segment(2) == 'establishment' || $this->uri->segment(2) == 'check_payment' || $this->uri->segment(2) == 'check_master' || $this->uri->segment(2) == 'go_live') {
                                            ?>
                                            <li > <a class="text_cursor active_tab" ><i class="fa fa-user fa-fw text_color"></i><strong class="text_color"> Administration</strong> </a></li>
                                            <li class="highlight"><a href="<?= base_url('supadmin/change_case_status/') ?>"><span class="text_color" > Change Case Status</span></a></li>
                                            <li class="highlight"><a href="<?= base_url("admin/user") ?>"><span class="text_color" >Create New Admin</span></a></li>
                                            <li class="highlight"><a href="<?= base_url("admin/contact") ?>"><span class="text_color" >Create Contact</span></a></li>
                                            <li class="highlight"><a href="<?= base_url('news_event/view') ?>"><span class="text_color" >Create News And Events</span></a></li>
                                            <li class="highlight"><a href="<?= base_url('Notice/view') ?>"><span class="text_color" >Upload Notice & Form</span></a></li>
                                            <li class="highlight"><a href="<?= base_url('admin/case_type') ?>"><span class="text_color" >CIS Case Type for eFiling </span></a></li>
                                            <li class="highlight"><a href="<?= base_url('Supadmin/establishment') ?>"><span class="text_color" >Add Establishment </span></a></li>
                                            <li class="highlight"><a href="<?= base_url('supadmin/check_payment') ?>"><span class="text_color" >Test Payment Getway </span></a></li>   
                                            <li class="highlight"><a href="<?= base_url('supadmin/check_master') ?>"><span class="text_color" >Check CIS Master </span></a></li>
                                            <li class="highlight"><a href="<?= base_url('supadmin/go_live') ?>"><span class="text_color" >Go Live </span></a></li>

                                            <?php
                                            // Ends User Dashboard Stages list
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

