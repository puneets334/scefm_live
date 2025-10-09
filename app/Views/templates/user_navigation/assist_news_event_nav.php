<?php

if ($this->uri->segment(2) == 'notice_circulars' || $this->uri->segment(1) == 'contact_us' || $this->uri->segment(2) == 'performas') {
    // STARTS HELP MENU
    ?>
    <li><a style="background-color: #737373 !important;"><i class="fa fa-question-circle" aria-hidden="true"></i>Assistance</a></li>
    <div class="clearfix"></div><br>

    <div class="show_md_div visible-lg visible-md" style="display:none !important;">
        <ul class="nav side-menu">
            <li><a href="<?= base_url('assistance/notice_circulars') ?>"><span class="text_color"><i class="fa fa-newspaper-o"></i>Circulars</span></a></li>
            <li><a href="<?= base_url('assistance/notice/listview/') ?>"><span class="text_color"><i class="fa fa-file-text-o"></i>Notice & Form</span></a></li>
            <li><a href="<?= base_url('contact_us') ?>"><span class="text_color"><i class="fa fa-users"></i> Contact us </span></a></li>
            <li><a href="<?= base_url('assistance/performas') ?>"><span class="text_color"><i class="fa fa-file-text-o icon_font_css"></i> Proformas </span></a></li>
            <li><a href="https://efiling.ecourts.gov.in/assets/downloads/efiling-User-manual.pdf" target="_blank"><span class="text_color"><i class="fa fa-info-circle"></i>User Manual</span></a></li>
        </ul>
    </div>


    <div class="side-menu_new show_sm_div visible-xs visible-sm center nav-sm" style="font-size:13px; font-weight: bold;">
        <ul  class="user_news_event">
            <a href="<?= base_url('assistance/notice_circulars/') ?>"><li style="">
                    <div>Notice and Circulars</div>
                    <div class="hover_text_user_news_event hover_text_css">Circulars</div>
                    <span><i class="fa fa-newspaper-o icon_font_css"></i></span></li></a>
        </ul>
        <ul  class="user_noticeview">
            <a href="<?= base_url('assistance/performas') ?>"><li style="">
                    <div> Proformas </div>
                    <div class="hover_text_user_noticeview hover_text_css"> Proformas </div>
                    <span><i class="fa fa-file-text-o icon_font_css"></i></span></li></a>
        </ul>
        <ul  class="new_contact_us">
            <a href="<?= base_url('contact_us') ?>"><li style="">
                    <div>Contact us</div>
                    <div class="hover_text_new_contact_us hover_text_css">Contact us</div>
                    <span><i class="fa fa-users icon_font_css"></i></span></li></a>
        </ul>
        <ul  class="user_manual">
            <!--<a href="https://efiling.ecourts.gov.in/assets/downloads/efiling-User-manual.pdf">-->
            <a href="<?= base_url('assets/downloads/User_manual_e_filing_Supreme_Court.pdf') ?>" target="_blank">
                <li style="">
                    <div>User Manual</div>
                    <div class="hover_text_user_manual hover_text_css">User Manual</div>
                    <span><i class="fa fa-info-circle icon_font_css"></i></span></li></a>
        </ul>
    </div>


<?php } ?>