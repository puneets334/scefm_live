<header>
    <!-- Top Header Section End -->
    <div class="top-header">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-3 col-lg-6 top-left-nav wow fadeInDown">

                </div>
                <div class="col-12 col-sm-12 col-md-9 col-lg-6 top-right-nav wow fadeInDown">
           
                <ul>
                    <!-- <li><a href="#SkipContent" class="hide skiptomain" aria-label="Skip to main content" title="Skip to main content">Skip To Main Content</a></li> -->
                    <!-- <li><a class="hide" href="<?php // echo base_url('online_copying/screen_reader');?>">Screen Reader Access</a></li> -->
                    <li class="text-size">
                        <a href="javascript:void(0)"><img src="<?= base_url().'assets/newAdmin/'?>images/text-icon.svg" alt="" class="txt-icon"></a>
                        <div class="font-action-sec">
									<ul>
										<li>
											<a href="javascript:void(0)" id="text_resize_increase">A+</a>
										</li>
										<li>
											<a href="javascript:void(0)"  id="text_resize_reset">A</a>
										</li>
										<li>
											<a href="javascript:void(0)"   id="text_resize_decrease">A-</a>
										</li>
									</ul>
								</div>
                    </li>
                    <!-- <li>
                        <a href="javascript:void(0)" class="toph-icon"><i class="fas fa-sitemap"></i></a>
                    </li> -->
                    <li class="theme-color">
                        <a href="javascript:void(0)" class="whitebg">A</a>
                        <a href="javascript:void(0)" class="blackbg">A</a>
                    </li>
                    <!-- <li>
                        <select name="" id="" class="select-lang">
                            <option value="">English</option>
                            <option value="">Hindi</option>
                        </select>
                    </li> -->
                </ul>
         
                </div>
            </div>
        </div>
    </div>
    <!-- Top Header Section End -->
    <div id="SkipContent" tabindex="-1"></div>
    <!-- Logo Section Header Start -->
    <div class="logo-sec-wraper">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-6 col-lg-4 logo-sec wow fadeInUp">
                    <a class="logo-align" href="<?php echo base_url('/')?>">
                        <img src="<?= base_url() . 'assets/newDesign/' ?>images/logo.png" alt="emblem">
                        <div class="brand-text">
                            <h4>भारत का सर्वोच्च न्यायालय
                                <span> Supreme Court of India </span>
                                <span class="logo-sm-txt">|| यतो धर्मस्ततो जय: ||</span>
                            </h4>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-8 loginbtn-sec wow fadeInUp">
                    <div class="nav-wraper">
                        <nav class="navbar navbar-expand-lg navbar-light custom-nav  w-100 wow fadeInUp">
                            <button class="navbar-toggler" type="button" data-toggle="collapse"
                                data-target="#navbarSupportedContent">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav mr-auto">
                                    <li class="nav-item">
                                        <!-- <a class="active" href="index.html">Handbook </a> -->
                                        <!-- <a class="active"  href="<?php echo base_url('e-resources')?>">Handbook </a> -->
                                        <a class="nav-link <?= (current_url() == base_url('resources/hand_book')) ? 'active' : '' ?>"  href="<?= base_url('resources/hand_book') ?>">Handbook </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?= (current_url() == base_url('resources/video_tutorial/view')) ? 'active' : '' ?>"  href="<?= base_url('resources/video_tutorial/view') ?>">Video Tutorial</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?= (current_url() == base_url('resources/FAQ')) ? 'active' : '' ?>"  href="<?= base_url('resources/FAQ') ?>">FAQs</a>
                                    </li>
                                    <!-- <li class="nav-item">
                                        <a class="nav-link <?= (current_url() == base_url('resources/hand_book_old_efiling')) ? 'active' : '' ?>"  href="<?= base_url('resources/hand_book_old_efiling') ?>">Stats</a>
                                    </li> -->
                                    <li class="nav-item">
                                        <a class="nav-link <?= (current_url() == base_url('resources/Three_PDF_user_manual')) ? 'active' : '' ?>"  href="<?= base_url('resources/Three_PDF_user_manual') ?>">3PDF User Manual</a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Logo Section Header End -->
</header>