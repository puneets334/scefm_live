<?php
if ($this->uri->segment(2) == 'clerk') {
    // STARTS SETTINGS MENU
    ?>
    <li><a style="background-color: #737373 !important;" ><i class="fa fa-cog"></i>SETTINGS</a></li>
    <div class="clearfix"></div>
    <div class="show_sm_div visible-xs visible-sm center nav-sm" style="font-size:13px; font-weight: bold;">
        <div class="side-menu_new show_sm_div visible-xs visible-sm center nav-sm" style="font-size:13px; font-weight: bold;">
            <ul  class="clerk"> 
                <a href="<?= base_url('add/clerk') ?>">
                    <li>
                        <div>Add Clerk</div>
                        <div class="hover_text_clerk hover_text_css">Add Clerk</div>
                        <span><i class="fa fa-plus icon_font_css"></i></span>
                    </li>
                </a>
            </ul>
        </div>    
    </div>
<?php } ?>