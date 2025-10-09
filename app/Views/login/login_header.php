<!DOCTYPE html>
<html  >
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>e-Filing Login</title>
        <link href="<?= base_url('assets/css/uikit.min.css'); ?>" rel="stylesheet">
        <link href="<?= base_url('assets/css/login-dark.css'); ?>" rel="stylesheet"> 
        <script type="text/javascript"> var base_url = '<?php echo base_url(); ?>';</script>
    </head> 
    <style> 
        @media only screen and (min-width: 321px)and (max-width: 1280px){ 
            .logo_img { 
                width: 145px !important;
            }
        }
        @media (min-width: 1281px) {
            .respansiv_font{
                font-size: 28px;
            }

        } 
        @media (min-width: 1025px) and (max-width: 1280px) {
            .respansiv_font{
                font-size: 17px;
            }
            .logo_img1{
                height:75px !important;
                width:auto !important;
            }
            .logo_img{
                height:80px !important;
                width:auto !important;
            }
        }

        @media (min-width: 768px) and (max-width: 1024px) {
            .respansiv_font{
                font-size: 17px;
            }
            .logo_img1{
                height:75px !important;
                width:auto !important;
            }
            .logo_img{
                height:80px !important;
                width:400px !important;
            }
        }

        @media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
            .respansiv_font{
                font-size: 17px;
            }
            .logo_img1{
                height:75px !important;
                width:auto !important;
            }
            .logo_img{
                height:80px !important;
                width:400px !important;
            }
        }
        @media (max-width: 768px) and (min-width: 1024px) and (orientation: landscape) {
            .respansiv_font{
                font-size: 17px;
            }
            .logo_img1{
                height:75px !important;
                width:auto !important;
            }
            .logo_img{
                height:80px !important;
                width:400px !important;
            }
        }

        @media (min-width: 481px) and (max-width: 767px) {
            .respansiv_font{
                font-size: 14px;
            }
        } 

        @media (min-width: 320px) and (max-width: 480px) {
            .respansiv_font{
                font-size: 13px;
            }
        }

        .uk-navbar {
            min-height: 50px;
        }
        .uk-navbar button.navbar-toggle {
            position: absolute;
            right: 0;
            top: 0;
            padding: 9px 10px;
            margin-top: 25px;
            margin-right: 100px;
            margin-bottom: 8px;
            background-color: transparent;
            background-image: none;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .uk-navbar button.navbar-toggle .icon-bar {
            display: block;
            width: 22px;
            height: 2px;
            border-radius: 1px;
            background-color: #888;
        }
        .uk-navbar button.navbar-toggle .icon-bar + .icon-bar {
            margin-top: 4px;
        }
        .uk-navbar .uk-navbar-nav {
            flex-wrap: wrap-reverse;
            align-self: flex-end;
        }
        .uk-navbar .uk-navbar-nav > li > a {
            min-height: auto;
            line-height: 3em;
        }
        @media (max-width: 959px) {
            .uk-navbar .toggle-target.collapsed {
                display: none;
            }
            .uk-navbar .toggle-target .uk-navbar-nav {
                display: block;
                top: 50px;
                position: absolute;
                left: 0;
                right: 0;
                width: 100%;
                background: #f8f8f8;
            }
            .uk-navbar .toggle-target .uk-navbar-nav li a {
                display: block;
                min-height: 0;
                line-height: 2em;
                padding: 0 15px !important;
                color: white;
            }
            .uk-navbar .toggle-target .uk-navbar-dropdown {
                width: 90%;
                min-width: 200px;
            }
            .uk-navbar .toggle-target .uk-navbar-dropdown[class*='uk-navbar-dropdown-bottom'] {
                margin-top: 0;
            }
        }
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0,0,0,0);
            border: 0;
        }
        a#logo {
            align-items:baseline
        }
        .uk-navbar-nav>li>a{
            color: white;
        }
        .uk-navbar-nav>li.uk-active>a{
            color: white;
        }
        .uk-navbar .uk-navbar-nav > li > a{
            line-height: 1em;
        }
        .uk-navbar-center:only-child, .uk-navbar-left, .uk-navbar-right{
            flex-wrap: nowrap;
        }
        .uk_container{
            max-width: 100%!important; 
            height:515px !important; 
            padding-left: 0px!important; 
            padding-right:0px!important;
        }
        .logo_img1{
            height:75px;
            width:auto;
        }
        .logo_img{
            height:80px;
            width:400px;
        }
        .ecourt_logo_img {
            height:80px;
            width:70px;
        }
    </style>
    <body style="background: white;">
    <?php if(true){ ?><!--for responsive_variant-->
        <section id="mainnav" class="respansiv_media" style=" background-image: url(<?= base_url('assets/images/adv/back.png'); ?>);">
            <nav  uk-navbar>
                <button type="button" class="navbar-toggle uk-hidden@m" uk-toggle="target:.toggle-target; cls:collapsed">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="uk-navbar-left">
                    <a class="uk-navbar-item uk-logo" style="color: #fff;font-size-adjust:0.6; font-family:cursive ;" href="#">
                        <img  src="<?= base_url('assets/images/adv/emb_sci.png'); ?>" backalt="ecourts logo" width="45" height="20" class="mainlogo">
                        <a class="uk-navbar-item" href="#"><img src="<?= base_url('assets/images/adv/NEAR.jpg'); ?>" alt="ecourts logo"  class="logo_img" style="height: 80px;"></a>
                </div> 
                <div class="uk-navbar-right toggle-target collapsed">
                    <div class="uk-navbar-right ">
                        <ul class="uk-navbar-nav uk-visiblem" style="color:white;z-index: 99;top: 100%; background-image: url(<?= base_url('assets/images/adv/back.png'); ?>);">
                            <li class="uk-active"><a href="<?php echo base_url(); ?>" ><b><?php echo $this->lang->line('home'); ?></b></a></li>
                            <li><a href="http://njdg.ecourts.gov.in/" target="_blank" ><b><?php echo $this->lang->line('njdg'); ?></b></a></li>
                            <li><a href="http://sci.gov.in/" target="_blank" ><b><?php echo $this->lang->line('supreme_court'); ?></b></b> </a> </li>
                            <li><a href="http://ecourts.gov.in/ecourts_home/static/highcourts.php" target="_blank"><b><?php echo $this->lang->line('high_court'); ?> </b> </a></li>
                            <li><a href="https://districts.ecourts.gov.in" target="_blank"><b><?php echo $this->lang->line('distt_court'); ?> </b> </a></li>
                        </ul> 
                    </div> 
                </div>

                <div class="uk-navbar-right toggle-target collapsed">
                    <div class="uk-navbar-right ">
                        <ul class="uk-navbar-nav" style="list-style: none;">
                            <li>
                                <a href="#" data-uk-icon="icon:down-arrow" class="nav_text_color" style=""> <?php echo $this->lang->line('language'); ?> </a>
                                <div class="uk-navbar-dropdown uk-navbar-dropdown-bottom-left">

                                    <ul class="uk-nav uk-navbar-dropdown-nav">
                                        <select onchange="javascript:window.location.href = '<?php echo base_url('LanguageSwitcher/switchLang/'); ?>' + this.value;">
                                            <option value="english" <?php if ($this->session->userdata('site_lang') == 'english') echo 'selected="selected"'; ?>>English</option>
                                            <option value="hindi" <?php if ($this->session->userdata('site_lang') == 'hindi') echo 'selected="selected"'; ?>>हिंदी</option>
                                        </select>

                                    </ul>

                                </div>
                            </li>
                        </ul>
                    </div> 
                </div>
                <div class="uk-navbar-right" >
                    <a class="uk-navbar-item uk-logo" href="#"><img class="img_res" src="<?= base_url('assets/images/adv/eCourts-logo2.svg'); ?>" alt="ecourts logo" width="80" height="70"></a>
                </div>
            </nav>
        </section>
<?php } ?>