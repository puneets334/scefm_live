<?php
if ($this->uri->segment(2) == 'Advocate') {
    ?> 
    <li class="highlight_tab"><a href="<?= base_url('admin/supadmin/change_case_status') ?>" style="background-color: #737373 !important;"><i class="fa fa-user fa-fw" ></i><strong style="color:#fff;">Administration</strong></a></li>
    <div class="show_md_div visible-lg visible-md" style="display:none !important;">
        <ul class="nav side-menu">
            <li class="highlight"><a href="<?= base_url('newRegister/Advocate') ?>"><span class="text_color"> New Advocate</span></a></li>            
            <li class="highlight"><a href="<?= base_url('assistance/notice_circulars') ?>"><span class="text_color">Activated</span></a></li>
            <li class="highlight"><a href="<?= base_url('assistance/performas') ?>"><span class="text_color">Rejected</span></a></li>
        </ul>
    </div>

    <div class="show_sm_div visible-xs visible-sm center nav-sm" style="font-size:13px; font-weight: bold;">
        <div class="side-menu_new">
            <ul  class="administration_status1"> 
                <a href="<?= base_url('newRegister/Advocate'); ?>">
                    <li>
                        <div>New Advocate</div>
                        <div class="hover_text_administration_status1 hover_text_css">New Advocate</div>
                        <span><i class="fa fa-outdent icon_font_css"></i></span>
                    </li></a>
            </ul>
            <ul  class="administration_status5"> 
                <a href="<?= base_url('newRegister/Advocate/users/') . url_encryption('1') ?>">
                    <li>
                        <div>Activated</div>
                        <div class="hover_text_administration_status5 hover_text_css">Activated</div>
                        <span><i class="fa fa-newspaper-o icon_font_css"></i></span>
                    </li></a>
            </ul>
            <ul  class="administration_status6"> 
                <a href="<?= base_url('newRegister/Advocate/users/') . url_encryption('0') ?>">
                    <li>
                        <div> Deactive </div>
                        <div class="hover_text_administration_status6 hover_text_css"> Deactive </div>
                        <span><i class="fa fa-files-o icon_font_css"></i></span>
                    </li></a>
            </ul> 
        </div>
    </div> 
<?php } ?>