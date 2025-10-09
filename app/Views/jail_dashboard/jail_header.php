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

<!-- Logo -->
<a href="<?php echo base_url(); ?>" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>A</b>LT</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>Jail</b> Module</span>

</a>
<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </a>
    <div class="row ">

        <center>
            <div class="login_box_logo"><img src="<?=base_url()?>assets/images/sci_logo_gold.png"></div>
        </center>

    </div>
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">


 <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <!--<img src="../../dist/img/user2-160x160.jpg" class="user-image" alt="User Image">-->
                    <span class="hidden-xs"><?php echo $this->session->userdata['login']['first_name'] ?></span>
                </a>
                <ul class="dropdown-menu">

                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <!--<div class="pull-left">
                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                        </div>-->
                        <div class="pull-right">
                            <a href="<?php echo base_url(); ?>login/logout" class="btn btn-default btn-flat">Sign out</a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>