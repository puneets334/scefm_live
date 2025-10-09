
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>e-Filing Login</title>
        <link href="<?= base_url() . 'assets' ?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?= base_url() . 'assets' ?>/css/select2.min.css" rel="stylesheet">
        <link href="<?= base_url('assets/jsmaps/jsmaps.css') ?>" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() . 'assets' ?>/css/map_css.css" rel="stylesheet">
        <script type="text/javascript" src="<?= base_url('assets/js/jquery_slider.min.js'); ?>"></script>
        <script src="<?= base_url() . 'assets' ?>/js/select2.min.js"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
        <script src="<?= base_url('assets/jsmaps/jsmaps-libs.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/jsmaps/jsmaps-panzoom.js') ?>"></script>
        <script src="<?= base_url('assets/jsmaps/jsmaps.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/jsmaps/india.js') ?>" type="text/javascript"></script>
        
          
    </head>
    <body class="wrapper">

        
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header pull-right" ><button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="navbar-header">
                    <img src="<?= base_url() ?>assets/images/emb.png">
                </div>
                <div class="navbar-header pull-right">
                    <img  src="<?= base_url() ?>assets/images/ecourts-logo.png"> 
                </div>
                <ul class="nav navbar-nav">
                    <li><strong class="font_styles">High Courts & District Courts</strong><p class="font_class"><b>e-Filing Application</b></p></li>
                </ul>
                <div id="navbar" class="navbar-collapse collapse font_class">
                    <ul class="nav navbar-nav navbar-right">
<!--                        <li><a href="#"><b>Home </b></a> </li>-->
                        <li><a href="http://njdg.ecourts.gov.in/" target="_blank" ><b>NJDG </b></a> </li>
                        <li> <a href="http://supremecourtofindia.nic.in/" target="_blank" ><b>Supreme Court</b> </a> </li>
                        <li><a href="http://ecourts.gov.in/ecourts_home/static/highcourts.php" target="_blank"><b>High Courts </b> </a></li>
                        <li><a href="https://districts.ecourts.gov.in" target="_blank"><b>District Court </b> </a></li>
<!--                        <li><a href="#"><b>Contact Us</b>  </a></li>-->
                        <li><a href="#"></a></li>
                </div>
            </div>
        </nav>
        <br><br><br><br><br><br>
                
        
<!--            <div class="col-md-4">
                <div class="col-md-12 col-sm-12 col-xs-12  card-plate "  style="background: #d1d3d4;">
                    <div class="jsmaps-wrapper" id="india-map"></div> 
                </div>
            </div>-->

    <div id="my_carousel" class="carousel slide">
                        <ol class="carousel-indicators">
                            <li data-target = "#my_carousel" data-slide-to = "0" class="active"></li>
                            <li data-target = "#my_carousel" data-slide-to = "1"></li>
                            <li data-target = "#my_carousel" data-slide-to = "2"></li>
                            <li data-target = "#my_carousel" data-slide-to = "3"></li>
                            <li data-target = "#my_carousel" data-slide-to = "4"></li>
                        </ol>
                        <div class="carousel-inner">
                             <div class="item active">
                                <img src="<?php echo base_url('assets/images/banner1.jpg') ?>"  class="img-responsive" style="width: 100%;  height:378px;">
                            </div>
                            
                            <div class="item">
                                <img src="<?php echo base_url('assets/images/banner2.jpg') ?>"  class="img-responsive" style="width: 100%; height:378px;">
                            </div>
                            <div class="item">
                                <img src="<?php echo base_url('assets/images/banner3.jpg') ?>" class="img-responsive"  style="width: 100%;  height:378px;">
                            </div>

                            <div class="item">
                                <img src="<?php echo base_url('assets/images/banner4.jpg') ?>"  class="img-responsive" style="width: 100%; height:378px;" >
                            </div>
                           
                        </div>
                        <a class="carousel-control left" href="#my_carousel" data-slide = "prev">
                            <span class="icon-prev left"></span>
                        </a>

                        <a class="carousel-control right" href="#my_carousel" data-slide = "next">
                            <span class="icon-next right"></span>
                        </a>
                    </div>  

                </div>
                <div class="clearfix"></div>
                <div class="col-md-12 col-sm-12 col-xs-12 card-plate_news" style="margin-top: 30px;">
                    <div class="well" >
                        <span class="font_styles" style="color:#8B0000"><u><strong> News And Events </strong></u></span><span class="font_styles" style="float:right;"><strong><a href="https://efiling.ecourts.gov.in/assets/downloads/efiling-User-manual.pdf" style="color:#8B0000 !important;"> eFiling Manual </a></strong></span>
                      
                        <marquee class="marquee" direction="up" onmouseover="this.setAttribute('scrollamount', 0, 0);" onmouseout="this.setAttribute('scrollamount', 2, 0);" scrolldelay="10" scrollamount="2" >
                            <ul>
                                <?php
                                foreach ($news_list as $v) {

                                    $created_date = date('d-m-Y', strtotime($v['create_date']));
                                    // $created_date = "22-02-2019";

                                    $start_date = $created_date;
                                    $date = strtotime($start_date);
                                    $date = strtotime("+15 day", $date);
                                    $date = date('d-m-Y', $date);
                                    $current_date = date('d-m-Y');
                                    if ($current_date >= $date) {

                                        $new = "<span class='blinking font_class_new'><strong>NEW</strong></span>";
                                    }
                                    
                                    if ($v['file_name'] != '') {
                                        echo '<li> <a href="' . base_url('login/news_pdf/' . url_encryption($v['id']) ). '" target="_blank"><strong style="color:brown;">' . htmlentities($v['state_name'] . '- ',  ENT_QUOTES) . '</strong>' . htmlentities(' ' . $v['news_title'], ENT_QUOTES) . '(' . htmlentities(date('d-m-Y', strtotime($v['create_date'], ENT_QUOTES))) . ')</span>&nbsp;&nbsp;<img src='. base_url("assets/images/pdf.png").'><span style="color:#992900"></a>' . $new . '</li>';
                                    } else {
                                        echo '<li><strong style="color:brown;">' . htmlentities($v['state_name'] . '- ', ENT_QUOTES) . '</strong><span style="color:#992900">' . htmlentities(' ' . $v['news_title'], ENT_QUOTES) . '(' . htmlentities(date('d-m-Y', strtotime($v['create_date'], ENT_QUOTES))) . ')</span></li>';
                                    }
                                }
                                ?>
                            </ul>
                        </marquee>
                    </div> 
                </div> 
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="footer">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                © 2018 | All Rights Reserved | <a style="color:#ffffff;" href="https://www.sci.gov.in/" target="_blank">eCommittee, Supreme Court of India </a>
            </div>
        </div>
    </body>
    <script type="text/javascript">
        $(function () {
            $('#india-map').JSMaps({
                map: 'india',
                abbreviationColor: '#000000',
                abbreviationFontSize: 11,
                displayAbbreviationOnDisabledStates: true
            });

        });

    </script>
    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {
            $('select').addClass('filter_select_dropdown');
            $('.filter_select_dropdown').select2();
            // $( "span.select2-selection__arrow" ).replaceWith( $( ".select2-selection" ) );

            $('.carousel').carousel({
                interval: 3000
            })
        });

    </script>
</html>
