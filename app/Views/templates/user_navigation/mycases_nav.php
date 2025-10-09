<?php
if ($this->uri->segment(1) == 'mycases') {
    //$CI = & get_instance();
    //$CI->load->model('cases/Mycases_model');
    //$count_mycases_data = $CI->Mycases_model->mycases_matters_list_count($_SESSION['login']['id']);
// STARTS MY CASES MENU
    ?>
    <li><a href="<?= base_url('cases/mycases/updation'); ?>" style="background-color: #737373 !important;"><i class="fa fa-dashboard "></i>My CASES &nbsp; <?php echo $total_cases = $count_mycases_data[0]->total_registered + $count_mycases_data[0]->total_unregistered + $count_mycases_data[0]->total_under_objection; ?></a></li>
    <div class="clearfix"></div><br>

    <div class="side-menu_new show_sm_div visible-xs visible-sm center nav-sm" style="font-size:13px; font-weight: bold;">
        <ul  class="tomorrow"> 
            <a href="<?php echo ($count_mycases_data[0]->total_tomorrow == '0' ) ? 'javascript:void(0)' : base_url('cases/mycases/updation/' . url_encryption('tomorrow')); ?>">
                <li>
                    <div>Listed for Tomorrow (<?php echo htmlentities($count_mycases_data[0]->total_tomorrow, ENT_QUOTES); ?>)</div>
                    <div class="hover_text_tomorrow hover_text_css">List for To.</div>
                    <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/list_tommorrow.svg'); ?>"></span>
                </li>
            </a>
        </ul>
        <ul  class="upcoming"> 
            <a href="<?php echo ($count_mycases_data[0]->total_upcoming == '0' ) ? 'javascript:void(0)' : base_url('cases/mycases/updation/' . url_encryption('upcoming')); ?>">
                <li>
                    <div>Listed (For Next 7 Days) (<?php echo htmlentities($count_mycases_data[0]->total_upcoming, ENT_QUOTES); ?>)</div>
                    <div class="hover_text_upcoming hover_text_css">Next 7 Days</div>
                    <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/listed(in 7 day).svg'); ?>"></span>
                </li>
            </a>
        </ul>
        <ul  class="under_objection"> 
            <a href="<?php echo ($count_mycases_data[0]->total_under_objection == '0' ) ? 'javascript:void(0)' : base_url('cases/mycases/updation/' . url_encryption('objection')); ?>">
                <li>
                    <div>Under Objections  (<?php echo htmlentities($count_mycases_data[0]->total_under_objection, ENT_QUOTES); ?>)</div>
                    <div class="hover_text_under_objection hover_text_css">Under Obj..</div>
                    <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/rejected.svg'); ?>"></span>
                </li>
            </a>
        </ul>
        <ul  class="with_dated"> 
            <a href="<?php echo ($count_mycases_data[0]->total_dated == '0' ) ? 'javascript:void(0)' : base_url('cases/mycases/updation/' . url_encryption('dated')); ?>">
                <li>
                    <div>With Next Date (<?php echo htmlentities($count_mycases_data[0]->total_dated, ENT_QUOTES); ?>)</div>
                    <div class="hover_text_with_dated hover_text_css">With Date</div>
                    <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/listed(in 7 day).svg'); ?>"></span>
                </li>
            </a>
        </ul>
        <ul  class="without_dated"> 
            <a href="<?php echo ($count_mycases_data[0]->total_undated == '0' ) ? 'javascript:void(0)' : base_url('cases/mycases/updation/' . url_encryption('undated')); ?>">
                <li>
                    <div>Without Next Date (<?php echo htmlentities($count_mycases_data[0]->total_undated, ENT_QUOTES); ?>)</div>
                    <div class="hover_text_without_dated hover_text_css">Without Date</div>
                    <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/listed(in 7 day).svg'); ?>"></span>
                </li>
            </a>
        </ul>
        <ul  class="registered"> 
            <a href="<?php echo ($count_mycases_data[0]->total_registered == '0' ) ? 'javascript:void(0)' : base_url('cases/mycases/updation/' . url_encryption('reg')); ?>">
                <li>
                    <div>Registered (<?php echo htmlentities($count_mycases_data[0]->total_registered, ENT_QUOTES); ?>)</div>
                    <div class="hover_text_registered hover_text_css">Registered</div>
                    <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/Pending scrutiny.svg'); ?>"></span>
                </li>
            </a>
        </ul>
        <ul  class="unregistered"> 
            <a href="<?php echo ($count_mycases_data[0]->total_unregistered == '0' ) ? 'javascript:void(0)' : base_url('cases/mycases/updation/' . url_encryption('unreg')); ?>">
                <li>
                    <div>Unregistered (<?php echo htmlentities($count_mycases_data[0]->total_unregistered, ENT_QUOTES); ?>)</div>
                    <div class="hover_text_unregistered hover_text_css">Unregistered</div>
                    <span><img class="img_css" src="<?php echo base_url('assets/images/mycases/Updated.svg'); ?>"></span>
                </li>
            </a>
        </ul> 
    </div> 

    <div class="show_md_div visible-lg visible-md" style="display: none !important;">
        <ul class="nav side-menu">
            <li><a href="<?php echo ($count_mycases_data[0]->total_tomorrow == '0' ) ? 'javascript:void(0)' : base_url('cases/mycases/updation/' . url_encryption('tomorrow')); ?>"><span class="text_color">Listed for Tomorrow <i class="number_css badge"><?php echo htmlentities($count_mycases_data[0]->total_tomorrow, ENT_QUOTES); ?></i></span></a></li>
            <li><a href="<?php echo ($count_mycases_data[0]->total_upcoming == '0' ) ? 'javascript:void(0)' : base_url('cases/mycases/updation/' . url_encryption('upcoming')); ?>"><span class="text_color">Listed (For Next 7 Days)<i class="number_css badge"><?php echo htmlentities($count_mycases_data[0]->total_upcoming, ENT_QUOTES); ?></i></span></a></li>
            <li><a href="<?php echo ($count_mycases_data[0]->total_under_objection == '0' ) ? 'javascript:void(0)' : base_url('cases/mycases/updation/' . url_encryption('objection')); ?>"><span class="text_color">Under Objections<i class="number_css badge"><?php echo htmlentities($count_mycases_data[0]->total_under_objection, ENT_QUOTES); ?></i></span></a></li>
            <li><a href="<?php echo ($count_mycases_data[0]->total_dated == '0' ) ? 'javascript:void(0)' : base_url('cases/mycases/updation/' . url_encryption('dated')); ?>"><span class="text_color">With Next Date<i class="number_css badge"><?php echo htmlentities($count_mycases_data[0]->total_dated, ENT_QUOTES); ?></i></span></a></li>
            <li><a href="<?php echo ($count_mycases_data[0]->total_undated == '0' ) ? 'javascript:void(0)' : base_url('cases/mycases/updation/' . url_encryption('undated')); ?>"><span class="text_color">Without Next Date<i class="number_css badge"><?php echo htmlentities($count_mycases_data[0]->total_undated, ENT_QUOTES); ?></i></span></a></li>
            <li><a href="<?php echo ($count_mycases_data[0]->total_registered == '0' ) ? 'javascript:void(0)' : base_url('cases/mycases/updation/' . url_encryption('reg')); ?>"><span class="text_color">Registered<i class="number_css badge"><?php echo htmlentities($count_mycases_data[0]->total_registered, ENT_QUOTES); ?></i></span></a></li>
            <li><a href="<?php echo ($count_mycases_data[0]->total_unregistered == '0' ) ? 'javascript:void(0)' : base_url('cases/mycases/updation/' . url_encryption('unreg')); ?>"><span class="text_color">Unregistered<i class="number_css badge"><?php echo htmlentities($count_mycases_data[0]->total_unregistered, ENT_QUOTES); ?></i></span></a> </li>
        </ul>
    </div>
    <?php
// ENDS MY CASES MENU
}?>