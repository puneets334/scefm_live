<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SC</title>
	<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
    <!-- <link rel="shortcut icon" href="<?= base_url() . 'assets/newAdmin/' ?>images/favicon.gif"> -->
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/font-awesome.min.css" rel="stylesheet">
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/animate.css" rel="stylesheet">
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/material.css" rel="stylesheet" />
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/style.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= base_url() . 'assets/newAdmin/' ?>css/jquery.dataTables.min.css">
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/fullcalendar.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery-ui.css">
    <link href="<?= base_url() . 'assets' ?>/css/select2.min.css" rel="stylesheet">
</head>
<body>
    <div class="mainPanel ">
        <div class="panelInner">
            <div class="middleContent">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 sm-12 col-md-12 col-lg-12 middleContent-left">
                            <div class="center-content-inner comn-innercontent">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                        {{-- Page Title Start --}}
                                        <!-- <div class="title-sec">
                                            <h5 class="unerline-title">Resources</h5>
                                        </div>
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item">
                                                <a href="{{base_url('resources/hand_book')}}" aria-current="page" class="nav-link {{(current_url() == base_url('support') || current_url() == base_url('resources/hand_book')) ? 'active' : ''}}">Hand Book</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{base_url('resources/video_tutorial/view')}}" aria-current="page" class="nav-link {{current_url() == base_url('resources/video_tutorial/view') ? 'active' : ''}}">Video Tutorial</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{base_url('resources/FAQ')}}" aria-current="page" class="nav-link {{current_url() == base_url('resources/FAQ') ? 'active' : ''}}">FAQ</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{base_url('resources/hand_book_old_efiling')}}" aria-current="page" class="nav-link {{current_url() == base_url('resources/hand_book_old_efiling') ? 'active' : ''}}">Refile Old Efiling Cases</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{base_url('resources/Three_PDF_user_manual')}}" aria-current="page" class="nav-link {{current_url() == base_url('resources/Three_PDF_user_manual') ? 'active' : ''}}">3PDF User Manual</a>
                                            </li>
                                        </ul> -->
                                        {{-- Page Title End --}}
                                        {{-- Main Start --}}
                                        <div class="uk-margin-small-top uk-border-rounded">
                                            @php
                                            $iframe_src_route_uri = 'resources/hand_book';
                                            if($tab == 'video-tutorial') {
                                                $iframe_src_route_uri = 'resources/video_tutorial/view';
                                            } else if($tab == 'FAQs') {
                                                $iframe_src_route_uri = 'resources/FAQ';
                                            } else if($tab == 'hand-book-old-efiling') {
                                                $iframe_src_route_uri = 'resources/hand_book_old_efiling';
                                            } else if($tab == 'three-pdf-user-manual') {
                                                $iframe_src_route_uri = 'resources/Three_PDF_user_manual';
                                            }
                                            @endphp
                                            <div class="ratio ratio-16x9">
                                                <iframe name="content-iframe" ukheight-viewport="offset-top:true;" src="<?= base_url($iframe_src_route_uri) ?>"></iframe>
                                            </div>
                                        </div>
                                        {{-- Main End --}}
                                    </div>
                                </div>
                            </div>
                            <!-- form--end  -->
                        </div>
                    </div>
                    <!-- tabs-section -start  -->
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.slim.min.js"></script>
    <script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?= base_url() . 'assets' ?>/js/jquery.min.js"></script>
    <script src="<?= base_url() . 'assets' ?>/js/jquery-ui.min.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script src="<?= base_url() ?>assets/js/sha256.js"></script>
    <script src="<?= base_url() ?>assets/newAdmin/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url() . 'assets' ?>/js/select2.min.js"></script>
    <script src="<?= base_url() . 'assets' ?>/js/select2-tab-fix.min.js"></script>
    <script type="text/javascript" src="<?= base_url() . 'assets' ?>/js/jquery.validate.js"></script>
</body>
</html>