
<li><a href="<?php echo base_url('newcase/caseDetails'); ?>" style="background-color: #737373 !important;"><i class="fa fa-dashboard "></i>&nbsp;  e-FILE </a></li>
<div class="clearfix"></div><br>
<div class="show_md_div visible-lg visible-md" style="display: none !important;">
    <ul class="nav side-menu">
        <li <?php echo $new_case_li_active; ?>><a href="<?php echo base_url('newcase/caseDetails/'); ?>"><span class="text_color" ><i class="fa fa-edit"></i> New Case </span></a> </li>
        <li <?php echo $misc_li_active; ?>><a href="<?php echo base_url('case/search/') . url_encryption('misc'); ?>"><span class="text_color" ><i class="fa fa-file"></i> Miscellaneous Docs </span></a></li>
        <li <?php echo $cf_li_active; ?>><a href="<?php echo base_url('case/search/') . url_encryption('ia'); ?>"><span class="text_color" ><i class="fa fa-paypal" aria-hidden="true"></i> IA</span></a></li>
        <li <?php echo $cf_li_active; ?>><a href="<?php echo base_url('case/search/') . url_encryption('mentioning'); ?>"><span class="text_color" ><i class="fa fa-paypal" aria-hidden="true"></i> Mentioning</span></a></li>
        <li <?php echo $misc_li_active; ?>><a href="<?php echo base_url('case/search/') . url_encryption('refile_old_efiling_cases'); ?>"><span class="text_color" ><i class="fa fa-file"></i> Refile old e-filing cases </span></a></li>
    </ul>
</div>
<div class="side-menu_new show_sm_div visible-xs visible-sm center nav-sm" style="font-size:13px; font-weight: bold;">
    <ul  class="case_file_type"> 
        <a href="<?php echo base_url('newcase/caseDetails/'); ?>">
            <li style=""><div>New Case </div>
                <div class="hover_text_case_file_type hover_text_css">New Case</div>
                <span><i class="fa fa-edit icon_font_css"></i></span>
            </li>
        </a>
    </ul> 
    <ul  class="whereToFile_type"> 
        <a href="<?php echo base_url('case/search/') . url_encryption('misc'); ?>">
            <li style=""><div>Miscellaneous Docs </div>
                <div class="hover_text_whereToFile_type hover_text_css">Misc Docs</div>
                <span><i class="fa fa-file icon_font_css"></i></span>
            </li>
        </a>
    </ul> 
    <ul  class="whereToFile_type5"> 
        <a href="<?php echo base_url('case/search/') . url_encryption('ia'); ?>">
            <li style=""><div>IA </div>
                <div class="hover_text_whereToFile_type5 hover_text_css">IA</div>
                <span><i class="fa fa-paypal icon_font_css"></i></span>
            </li>
        </a>
    </ul>
    <ul  class="whereToFile_type5"> 
        <a href="<?php echo base_url('case/search/') . url_encryption('mentioning'); ?>">
        <!--<a href="<?php /*echo base_url('mentioning/Mentioning'); */?>">-->
            <li style=""><div>Mentioning </div>
                <div class="hover_text_whereToFile_type5 hover_text_css">Mentioning</div>
                <span><i class="fa fa-paypal icon_font_css"></i></span>
            </li>
        </a>
    </ul>
    <ul  class="whereToFile_type">
        <a href="<?php echo base_url('case/search/') . url_encryption('refile_old_efiling_cases'); ?>">
            <li style=""><div>Refile Old E-filing Cases </div>
                <div class="hover_text_whereToFile_type hover_text_css">Refile Old E-filing Cases</div>
                <span><i class="fa fa-file icon_font_css"></i></span>
            </li>
        </a>
    </ul>
    <ul  class="whereToFile_type5">
        <a href="<?php echo base_url('case/search/') . url_encryption('citation'); ?>">
            <!--<a href="<?php /*echo base_url('mentioning/Mentioning'); */?>">-->
            <li style=""><div>Citations </div>
                <div class="hover_text_whereToFile_type5 hover_text_css">Citations</div>
                <span><i class="fa fa-at icon_font_css"></i></span>
            </li>
        </a>
    </ul>
</div> 