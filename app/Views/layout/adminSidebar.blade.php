
<?php $session = session(); 
$segment = App\Libraries\Slice();
?>

<div class="sidePanel hide">
    <div class="leftPanel">
        <div class="dashLeftNavSection">
            <div class="menu-close-sec">
                <a href="" class="main-menu-close">	<span class="mdi mdi-close-circle-outline"></span></a>
            </div>
            <div class="menu-profile-sec">
                <div class="profile-img">
                    <img src="<?= base_url().'assets/newAdmin/'?>images/profile-img.png" alt="">
                </div>
                <div class="profile-info">
                    <h6>Devesh Kumar <a href="javascript:void(0)" class="profile-link link-txt"><span class="mdi mdi-circle-edit-outline"></span></a></h6>
                    <a href="" class="profile-lnk link-txt">User Profile</a>
                </div>
            </div>
            <nav class=" mean-nav">
                <ul class="dashboardLeftNav accordion" id="accordionExample">
                    <?php
                    if((!empty($session->get('login')['ref_m_usertype_id'])) && ($session->get('login')['ref_m_usertype_id'] == USER_SUPER_ADMIN )){
                    ?>
                        <li class="premium"><a href="javascript:void(0)" class="btn-link">Dashboard</a> </li>
                        <li class="premium"><a href="<?= base_url('superAdmin'); ?>" class="btn-link">Home</a> </li>
                        <li class="premium"><a href="<?php echo base_url('superAdmin/DefaultController/registrationForm'); ?>" class="btn-link">Registration</a> </li>
                        <li class="premium"><a href="<?php echo base_url('superAdmin/DefaultController'); ?>" class="btn-link">User List</a> </li>
                        <?php if ($segment->getSegment(1) == 'superAdmin') { ?>
                        <?php } else if ($segment->getSegment(1) == 'profile') { ?>
                            <li class="health "><a href="<?= base_url('profile') ?>">Profile</a></li>
                            <div class="clearfix"></div><br><?php
                        }
                        ?>
                    <?php } else if((!empty($session->get('login')['ref_m_usertype_id'])) && ($session->get('login')['ref_m_usertype_id'] == USER_EFILING_ADMIN  )){
                        ?>
                        <li class="premium"><a href="<?= base_url('adminDashboard'); ?>" class="btn-link">Dashboard</a> </li>
                        <li class="premium"><a href="<?php echo base_url('filingAdmin/DefaultController'); ?>" class="btn-link">User Listing</a> </li>
                        <li class="premium"><a href="<?php echo base_url('filingAdmin/DefaultController/userFileTransferForm'); ?>" class="btn-link">User File Transfer</a> </li>
                        <li class="premium"><a href="<?php echo base_url('adminReport/DefaultController/reportForm'); ?>" class="btn-link">Work Done Reports</a> </li>
                        <li class="premium"><a href="<?= base_url('report'); ?>" class="btn-link">Reports</a> </li>
                        <li class="premium"><a href="<?= base_url('admin/noc_vakalatnama'); ?>" class="btn-link">NOC Vakaltnama</a> </li>
                        <?php if ($segment->getSegment(1) == 'superAdmin') { ?>
                        <?php } else if ($segment->getSegment(1) == 'profile') { ?>
                            <li class="health "><a href="<?= base_url('profile') ?>">Profile</a></li>
                            <div class="clearfix"></div><br><?php
                        }
                        ?>
                    <?php } else { ?>    
                        <?php
                        if ($segment->getSegment(1) == 'registrarActionDashboard') { ?>
                            <li class="health "><a href="<?= base_url('adminDashboard'); ?>">Home</a></li>
                        <?php } else { ?>
                            <?php if((!empty($session->get('login')['ref_m_usertype_id'])) && ($session->get('login')['ref_m_usertype_id'] != USER_ADMIN_READ_ONLY  )){?>
                                <li class="health "><a href="<?= base_url('adminDashboard'); ?>" class="btn-link">Home</a></li>
                                <li class="health "><a href="<?php echo base_url('newRegister/Advocate'); ?>" class="btn-link">Registration</a></li>
                                <li class="health "><a href="<?= base_url('admin/supadmin/change_case_status'); ?>" class="btn-link">Administration</a></li>
                                <li class="health "><a href="<?= base_url('assistance/notice_circulars/'); ?>" class="btn-link">Assistance</a></li>
                                <li class="health "><a href="<?php echo base_url('adminReport/DefaultController/reportForm'); ?>" class="btn-link">Work Done Reports</a></li>
                            <?php } ?>
                                <li class="health "><a href="<?= base_url('adminDashboard'); ?>" class="btn-link">Dashboard</a></li>
                                <li class="health "><a href="<?= base_url('report'); ?>" class="btn-link">Reports</a></li>
                        <?php } ?>
                        <?php  if(!empty($session->get('login')['ref_m_usertype_id']) && $session->get('login')['ref_m_usertype_id'] == USER_ADMIN) {?>
                            <li class="health "><a href="<?= base_url('adminReport/Reports/search'); ?>" class="btn-link">Send Mail (SC-eFM Statistics)</a></li>
                            <li class="health "><a href="<?= base_url('adminReport/search'); ?>" class="btn-link">Download Files Casewise</a></li>
                        <?php
                            $allowed_ids_for_noc_vakalatnama = array(6086,6105,6108,6109,6088,9474);
                            $current_id = $session->get('login')['id'];
                            if (in_array($current_id, $allowed_ids_for_noc_vakalatnama)) {?>
                                <li class="health "><a href="<?= base_url('admin/noc_vakalatnama'); ?>" class="btn-link">NOC Vakaltnama</a></li>
                            <?php   }
                        } ?>
                        <!--Added on 03-11-2023 to allow Shahnawaz for downloading PDF files-->
                        <?php  if(!empty($session->get('login')['ref_m_usertype_id']) && $session->get('login')['ref_m_usertype_id'] == USER_ADMIN_READ_ONLY && !empty($session->get('login')['userid']) && ($session->get('login')['userid'] == 'SCI4599' || $session->get('login')['userid'] == 'SCI4578' || $session->get('login')['userid'] == 'SCI4581')) {?>
                            <li class="health "><a href="<?= base_url('adminReport/search'); ?>" class="btn-link">Download Files Casewise</a></li>
                        <?php } ?>

                        <?php if ($segment->getSegment(1) == 'adminDashboard') { ?>
                        <?php } else if ($segment->getSegment(1) == 'profile') { ?>
                            <li><a href="<?= base_url('profile') ?>" style="background-color: #737373 !important;"><i class="fa fa-dashboard "></i> PROFILE </a></li>
                            <div class="clearfix"></div><br><?php
                        }
                        if ($segment->getSegment(2) == 'stageList') {
                            $this->load->view('templates/admin_navigation/admin_stage_list_nav');
                        }
                        if ($segment->getSegment(2) == 'supadmin' || $segment->getSegment(3) == 'change_case_status' || $segment->getSegment(3) == 'contact' ||
                            $segment->getSegment(3) == 'view' || $segment->getSegment(3) == 'add_contact' || $segment->getSegment(3) == 'case_type' ||
                            $segment->getSegment(3) == 'add_case_type') {
                            $this->load->view('templates/admin_navigation/administration_nav');
                        }
                        if ($segment->getSegment(2) == 'notice_circulars' || $segment->getSegment(1) == 'contact_us' || $segment->getSegment(2) == 'performas') {
                            $this->load->view('templates/user_navigation/assist_news_event_nav');
                        }
                        if ($segment->getSegment(2) == 'Advocate') {
                            $this->load->view('templates/admin_navigation/new_regisiter_nav');
                        }
                        ?>
                    <?php } ?>
                    <li class="health">
                        <a href="javascript:void(0)" class="accordion-button collapsed btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">Cases
                        </a>
                        <ul id="collapseOne" class="submenu accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <li><a href="javascript:void(0)">Demo Sub Menu</a></li>
                            <li><a href="javascript:void(0)">Demo Sub Menu</a></li>
                            <li><a href="javascript:void(0)">Demo Sub Menu</a></li>
                        </ul>
                    </li>
                    <li class="health ">
                        <a href="javascript:void(0)" class="accordion-button collapsed btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Add Advocate
                            </a>
                        <ul id="collapseTwo" class="submenu accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <li><a href="javascript:void(0)">Demo Sub Menu</a></li>
                            <li><a href="javascript:void(0)">Demo Sub Menu</a></li>
                            <li><a href="javascript:void(0)">Demo Sub Menu</a></li>
                        </ul>
                    </li>
                    <li class="health ">
                        <a href="javascript:void(0)" class="accordion-button collapsed btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Support 
                        </a>
                        <ul id="collapseThree" class="submenu accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <li><a href="javascript:void(0)">Demo Sub Menu</a></li>
                            <li><a href="javascript:void(0)">Demo Sub Menu</a></li>
                            <li><a href="javascript:void(0)">Demo Sub Menu</a></li>
                        </ul>
                    </li>
                    <li class="health ">
                        <a href="javascript:void(0)" class="accordion-button collapsed btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">Resources
                            </a>
                        <ul id="collapse4" class="submenu accordion-collapse collapse" aria-labelledby="heading4" data-bs-parent="#accordionExample">
                            <li><a href="javascript:void(0)">Demo Sub Menu</a></li>
                            <li><a href="javascript:void(0)">Demo Sub Menu</a></li>
                            <li><a href="javascript:void(0)">Demo Sub Menu</a></li>
                        </ul>
                    </li>
                    <li class="health ">
                        <a href="javascript:void(0)" class="accordion-button collapsed btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">Transferred Cases 
                        </a>
                        <ul id="collapse5" class="submenu accordion-collapse collapse" aria-labelledby="heading5" data-bs-parent="#accordionExample">
                            <li><a href="javascript:void(0)">Demo Sub Menu</a></li>
                            <li><a href="javascript:void(0)">Demo Sub Menu</a></li>
                            <li><a href="javascript:void(0)">Demo Sub Menu</a></li>
                        </ul>
                    </li>
                    <li class="health ">
                        <a href="javascript:void(0)" class="accordion-button collapsed btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapse6">Advance Vacation List
                        </a>
                        <ul id="collapse6" class="submenu accordion-collapse collapse" aria-labelledby="heading6" data-bs-parent="#accordionExample">
                            <li><a href="javascript:void(0)">Demo Sub Menu</a></li>
                            <li><a href="javascript:void(0)">Demo Sub Menu</a></li>
                            <li><a href="javascript:void(0)">Demo Sub Menu</a></li>
                        </ul>
                    </li>
                    <li class="premium"><a href="javascript:void(0)" class="btn-link">Settings</a> </li>
                    <li class="report"><a href="javascript:void(0)" class="btn-link">Logout</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>