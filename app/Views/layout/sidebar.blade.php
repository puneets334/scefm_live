<?php
// $segment = App\Libraries\Slice();
$segment = service('uri');
date_default_timezone_set('Asia/Kolkata');
?>
<style>
    .dashboardLeftNav li ul.submenu li a:hover {
        border-bottom: 1px solid #7e7e7e69 !important;
        color: #fff;
        padding: 10px 5px 10px 57px;
        border-radius: 20px 0 0 20px;
    }
    .blink-new {
        background-color: #ff3d00;
        color: #fff;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        animation: blinker 1s linear infinite;
        display: inline-block;
    }
</style>
<div class="sidePanel hide">
    <div class="leftPanel">
        <div class="dashLeftNavSection">
            <div class="menu-close-sec">
                <a href="javascript:void(0)" class="main-menu-close"> <span class="mdi mdi-close-circle-outline"></span></a>
            </div>
            <?php
            $profile_model = new \App\Models\Profile\ProfileModel();
            $profile = !empty(getSessionData('login')) ? $profile_model->getProfileDetail(getSessionData('login')['userid']) : '';
            $get_valu = help_id_url(uri_string());
            $help_page = explode('.', $get_valu);
            $login_time = !empty(getSessionData('login')) ? $profile_model->userLastLogin(getSessionData('login')['id']) : '';
            $last_login = (!empty($login_time) && $login_time->login_time != '') ? date('d-m-Y h:i:s A', strtotime($login_time->login_time)) : null;
            if (getSessionData('photo_path') != '') {
                // $profile_photo = str_replace('/photo/', '/' . 'thumbnail' . '/', getSessionData('login')['photo_path']);
                $profile_photo = base_url($profile->photo_path);
            } else {
                $profile_photo = base_url('assets/images/alt-image.png');
            }
            ?>
            <div class="menu-profile-sec">
                <div class="profile-img">
                    <img src="<?= base_url() . 'assets/newAdmin/' ?>images/profile-img.png" alt="">
                </div>
                <div class="profile-info">
                    <?php //echo '<pre>'; pr($_SESSION); ?>
                    <h6>
                    <?php if(!empty(getSessionData('login')) && getSessionData('login')['aor_code'] == 10017){ ?>
                        <?= !empty(getSessionData('login')) ? getSessionData('login')['first_name'] : '' ?>
                        <?php  } else { ?>
                            <?= !empty(getSessionData('login')) ? strtoupper(getSessionData('login')['first_name']) : '' ?>
                        <?php } ?>
                       
                        <p></p>
                        <!-- <p style="color: white;">(<?//= !empty(getSessionData('login')) ? getSessionData('login')['aor_code'] : ''?>)</p> -->
                    </h6>
                    @if(!empty(getSessionData('login')) && getSessionData('login')['ref_m_usertype_id'] == USER_ADVOCATE)
                        <p style="color: white;">Advocate on Record (Code - {{getSessionData('login')['aor_code']}})</p>
                        <a href="<?= base_url('profile') ?>" class="profile-link link-txt"><span class="mdi mdi-circle-edit-outline"></span></a>
                        <a href="<?= base_url('profile') ?>" class="profile-lnk link-txt">User Profile</a>
                    @elseif(!empty(getSessionData('login')) && getSessionData('login')['ref_m_usertype_id']==USER_IN_PERSON)
                        <p style="color: white;">Party in Person</p>
                        <a href="<?= base_url('profile') ?>" class="profile-link link-txt"><span class="mdi mdi-circle-edit-outline"></span></a>
                        <a href="<?= base_url('profile') ?>" class="profile-lnk link-txt">User Profile</a>
                    @endif
                </div>
            </div>
            <nav class=" mean-nav">
                <ul class="dashboardLeftNav accordion" id="accordionExample">
                    <?php
                    if((!empty(getSessionData('login')['ref_m_usertype_id'])) && (getSessionData('login')['ref_m_usertype_id'] == USER_SUPER_ADMIN )) { ?>
                        <!-- {{-- <li class="premium"><a href="javascript:void(0)" class="btn-link">Dashboard</a> </li> --}} -->
                        <!-- <li class="premium"><a href="<?= base_url('superAdmin') ?>" class="btn-link">Home</a> </li> -->
                        <li class="premium"><a href="<?php echo base_url('superAdmin/DefaultController/registrationForm'); ?>" class="btn-link">Registration</a> </li>
                        <li class="premium"><a href="<?php echo base_url('superAdmin/DefaultController'); ?>" class="btn-link">User List</a></li>
                        <?php if ($segment->getSegment(1) == 'superAdmin') { ?>
                        <?php } else if ($segment->getSegment(1) == 'profile') { ?>
                            <li class="health "><a href="<?= base_url('profile') ?>">Profile</a></li>
                            <div class="clearfix"></div><br>
                        <?php } ?>
                    <?php } else if((!empty(getSessionData('login')['ref_m_usertype_id'])) && (getSessionData('login')['ref_m_usertype_id'] == USER_EFILING_ADMIN  )) { ?>
                        <li class="premium"><a href="<?= base_url('filingAdmin') ?>" class="btn-link">Dashboard</a> </li>
                        <li class="premium"><a href="<?php echo base_url('filingAdmin/userListing'); ?>" class="btn-link">User List</a> </li>
                        <li class="premium"><a href="<?php echo base_url('filingAdmin/userFileTransferForm'); ?>" class="btn-link">User File Transfer</a> </li>
                        <li class="premium"><a href="<?php echo base_url('adminReport/DefaultController/reportForm'); ?>" class="btn-link">Work Done Reports</a> </li>
                        <li class="premium"><a href="<?= base_url('report/search') ?>" class="btn-link">Reports</a> </li>
                        <li class="premium"><a href="<?php echo base_url('admin/noc_vakalatnama') ?>" class="btn-link">NOC Vakaltnama</a> </li>
                        <?php if ($segment->getSegment(1) == 'superAdmin') { ?>
                        <?php } else if ($segment->getSegment(1) == 'profile') { ?>
                            <li class="health "><a href="<?= base_url('profile') ?>">Profile</a></li>
                            <div class="clearfix"></div><br>
                        <?php } ?>
                    <?php } else if((!empty(getSessionData('login')['ref_m_usertype_id'])) && (getSessionData('login')['ref_m_usertype_id'] == USER_ADVOCATE)) { ?>
                        <li class="premium"><a href="<?= base_url('dashboard_alt') ?>" class="btn-link">Dashboard</a> </li>
                        <li class="premium"><a href="<?php echo base_url('cases') ?>" class="btn-link">Cases</a> </li>
                        <li class="premium"><a href="<?php echo base_url('assistance/notice_circulars') ?>" class="btn-link">Support</a> </li>
                        <li class="premium"><a href="<?php echo base_url('resources/hand_book') ?>" class="btn-link">Resources</a> </li>
                        <li class="premium"><a href="<?php echo base_url('admin/noc_vakalatnama/get_transferred_cases') ?>" class="btn-link">Transferred cases</a> </li>
                        <li class="premium"><a href="<?php echo base_url('register/arguingCounsel') ?>" class="btn-link">Add Advocate</a> </li>
                        <?php if(is_vacation_advance_list_duration()) { ?>
                            <li class="premium"><a href="<?php echo base_url('vacation/advance') ?>" class="btn-link">Partial Court Working Days<span class="blink-new">NEW</span></a> </li>
                        <?php } ?>
                        <?php  if(is_vacation_supp_advance_list_duration()) { ?>
                            <li class="premium"><a href="<?php echo base_url('vacation_supp/advance') ?>" class="btn-link">Partial Court Working Days (Supplementary)<span class="blink-new">NEW</span></a> </li>
                        <?php } ?>
                        <li class="premium">                    
                            <a href="javascript:void(0)" class="accordion-button collapsed btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">Physical Hearing<span><i class="fas fa-chevron-down"></i></span></a>
                            <ul id="collapseOne" class="submenu accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <li><a class="btn-link" href="<?php echo base_url('physical_hearing') ?>">Consent for VC</a></li>
                                <li><a class="btn-link" href="<?php echo base_url('physical_hearing/reports') ?>">Reports</a></li>                                
                            </ul>                        
                        </li>
                        <li class="premium">                    
                            <a href="javascript:void(0)" class="accordion-button collapsed btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Appearance<span><i class="fas fa-chevron-down"></i></span></a>
                            <ul id="collapseTwo" class="submenu accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <li><a class="btn-link" href="<?php echo base_url('advocate/listed_cases') ?>">Cause List</a></li>
                                <li><a class="btn-link" href="<?php echo base_url('advocate/report') ?>">Reports</a></li>                                
                            </ul>                        
                        </li>
                        <li class="premium">
                            <a href="javascript:void(0)" class="accordion-button collapsed btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">eCopying<span><i class="fas fa-chevron-down"></i></span></a>
                            <ul id="collapseThree" class="submenu accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/copy_search'); ?>">Copy Status</a></li>
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/track_consignment'); ?>">Track</a></li>
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/case_search'); ?>">Application</a></li>
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/applicant_address'); ?>">Address</a></li> 
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/faq'); ?>">FAQ's</a></li>
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/screen_reader'); ?>">Screen Reader</a></li>
                                <li><a class="btn-link" href="https://registry.sci.gov.in/api/callback/bharat_kosh/eCopyingPublic_manual.pdf" target="_blank">Manual</a></li>
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/contact_us'); ?>">Contact Us</a></li>
                            </ul>
                        </li>
                        <!-- <li class="premium"><a href="<?php // echo base_url('clerks/Clerk_Controller/add_clerk') ?>" class="btn-link">Add Clerk</a> </li> -->
                        <!-- Start Generate and Download Template -->
                        <!-- <li class="premium"><a href="<?php // echo base_url('admin/PrepareTemplate_Controller/prepared_templates_download?case=P'); ?>" class="btn-link">Download Templates</a> </li> -->
                        <!-- <li class="premium"><a href="<?php // echo base_url('generate_template/GenerateTemplate_Controller/index?case=P'); ?>" class="btn-link">Generate Template</a> </li> -->
                        <!-- End Generate and Download Template -->
                        <?php if ($segment->getSegment(1) == 'superAdmin') { ?>
                        <?php } else if ($segment->getSegment(1) == 'profile') { ?>
                            <li class="health "><a href="<?= base_url('profile') ?>">Profile</a></li>
                            <div class="clearfix"></div><br>
                        <?php } ?>
                    <?php } else if((!empty(getSessionData('login.ref_m_usertype_id')) && (getSessionData('login.ref_m_usertype_id') == SR_ADVOCATE  ))) { ?>
                        <li class="premium"><a href="<?= base_url('dashboard') ?>" class="btn-link">Dashboard</a> </li>
                        <li class="premium"><a href="<?php echo base_url('cases') ?>" class="btn-link">My Cases</a> </li>
                        <!-- <li class="premium"><a href="<?php echo base_url('assistance/notice_circulars') ?>" class="btn-link">Support</a> </li> -->
                    <?php } else if ((!empty(getSessionData('login')['ref_m_usertype_id'])) && (getSessionData('login')['ref_m_usertype_id'] == AUTHENTICATED_BY_AOR || getSessionData('login')['ref_m_usertype_id'] == APPEARING_COUNCIL)) { ?>

                        <li class="premium">
                            <a href="javascript:void(0)" class="accordion-button collapsed btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">eCopying<span><i class="fas fa-chevron-down"></i></span></a>
                            <ul id="collapseFour" class="submenu accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/copy_search'); ?>">Copy Status</a></li>
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/track_consignment'); ?>">Track</a></li>
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/case_search'); ?>">Application</a></li>
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/applicant_address'); ?>">Address</a></li> 
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/faq'); ?>">FAQ's</a></li>
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/screen_reader'); ?>">Screen Reader</a></li>
                                <li><a class="btn-link" href="https://registry.sci.gov.in/api/callback/bharat_kosh/eCopyingPublic_manual.pdf" target="_blank">Manual</a></li>
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/contact_us'); ?>">Contact Us</a></li>
                            </ul>
                        </li>
                    <?php } else if((!empty(getSessionData('login.ref_m_usertype_id')) && (getSessionData('login.ref_m_usertype_id') == ARGUING_COUNSEL || getSessionData('login.ref_m_usertype_id') == USER_IN_PERSON))) { ?>
                        <li class="health "><a href="<?= base_url('dashboard_alt') ?>">Home</a></li>
                        <li class="premium"><a href="<?php echo base_url('assistance/notice_circulars') ?>" class="btn-link">Support</a> </li>
                        <li class="premium"><a href="<?php echo base_url('resources/hand_book') ?>" class="btn-link">Resources</a> </li>
                        <!-- <li class="premium"><a href="<?php echo base_url('generate_template/GenerateTemplate_Controller/index?case=P'); ?>" class="btn-link">Generate Template</a> </li> -->
                        <li class="premium">
                            <a href="javascript:void(0)" class="accordion-button collapsed btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">eCopying<span><i class="fas fa-chevron-down"></i></span></a>
                            <ul id="collapseFive" class="submenu accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/copy_search'); ?>">Copy Status</a></li>
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/track_consignment'); ?>">Track</a></li>
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/case_search'); ?>">Application</a></li>
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/applicant_address'); ?>">Address</a></li> 
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/faq'); ?>">FAQ's</a></li>
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/screen_reader'); ?>">Screen Reader</a></li>
                                <li><a class="btn-link" href="https://registry.sci.gov.in/api/callback/bharat_kosh/eCopyingPublic_manual.pdf" target="_blank">Manual</a></li>
                                <li><a class="btn-link" href="<?php echo base_url('online_copying/contact_us'); ?>">Contact Us</a></li>
                            </ul>
                        </li>
                    <?php } else { ?>
                        <?php if ($segment->getSegment(1) == 'registrarActionDashboard') { ?>
                            <!-- <li class="health "><a href="<?= base_url('adminDashboard') ?>">Home</a></li> -->
                        <?php } else { ?>
                            <?php if((!empty(getSessionData('login')['ref_m_usertype_id'])) && (getSessionData('login')['ref_m_usertype_id'] != USER_ADMIN_READ_ONLY  )) { ?>
                                <!-- <li class="health "><a href="<?= base_url('adminDashboard') ?>" class="btn-link">Home</a></li> -->
                                <li class="health "><a href="<?php echo base_url('NewRegister/Advocate'); ?>" class="btn-link">Registration</a></li>
                                <!-- <li class="health "><a href="<?= base_url('Admin/Supadmin/change_case_status') ?>" class="btn-link">Administration</a></li> -->
                                <li class="health ">
                                    <a href="javascript:void(0)" class="accordion-button collapsed btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">Administration</a>
                                    <ul id="collapseSix" class="submenu accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                        <!-- <li><a class="btn-link" href="<?= base_url('Admin/Supadmin/change_case_status') ?>">Change Case Status</a></li> -->
                                        <li><a class="btn-link" href="<?= base_url('assistance/notice_circulars/') ?>">Notice and Circulars</a></li>
                                        <li><a class="btn-link" href="<?= base_url('assistance/performas/') ?>">Proformas</a></li>
                                    </ul>
                                </li>

                                <!-- <li class="health "><a href="<?= base_url('assistance/notice_circulars/') ?>" class="btn-link">Assistance</a></li> -->
                                <li class="health ">
                                    <a href="javascript:void(0)" class="accordion-button collapsed btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">Assistance</a>
                                    <ul id="collapseSeven" class="submenu accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                                        <li><a class="btn-link" href="<?= base_url('assistance/notice_circulars/') ?>">Notice and Circulars</a></li>
                                        <li><a class="btn-link" href="<?= base_url('assistance/performas/') ?>">Proformas</a></li>
                                        <li><a class="btn-link" href="<?= base_url('contact_us') ?>">Contact Us</a></li>
                                        <li><a class="btn-link" href="<?= base_url('/assets/downloads/User_manual_e_filing_Supreme_Court.pdf') ?>">User Manual</a></li>
                                    </ul>
                                </li>
                                <li class="health "><a href="<?php echo base_url('adminReport/DefaultController/reportForm'); ?>" class="btn-link">Work Done Reports</a></li>
                            <?php } ?>
                            <li class="health "><a href="<?= base_url('adminDashboard') ?>" class="btn-link">Dashboard</a></li>
                            <li class="health "><a href="<?php echo base_url('report/search') ?>" class="btn-link">Reports</a></li>
                        <?php } ?>
                        <?php  if(!empty(getSessionData('login')['ref_m_usertype_id']) && getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN) { ?>
                            <li class="health "><a href="<?= base_url('adminReport/Reports/search') ?>" class="btn-link">Send Mail (SC-eFM Statistics)</a></li>
                            <li class="health "><a href="<?= base_url('adminReport/search') ?>" class="btn-link">Download Files Casewise</a></li>
                            <?php
                            $allowed_ids_for_noc_vakalatnama = array(6086,6105,6108,6109,6088,9474);
                            $current_id = getSessionData('login')['id'];
                            if (in_array($current_id, $allowed_ids_for_noc_vakalatnama)) { ?>
                                <li class="health "><a href="<?= base_url('admin/noc_vakalatnama') ?>" class="btn-link">NOC Vakaltnama</a></li>
                                <?php
                            }
                        }
                        ?>
                        <!--Added on 03-11-2023 to allow Shahnawaz for downloading PDF files-->
                        <?php if(!empty(getSessionData('login')['ref_m_usertype_id']) && getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN_READ_ONLY && !empty(getSessionData('login')['userid']) && (getSessionData('login')['userid'] == 'SCI4599' || getSessionData('login')['userid'] == 'SCI4578' || getSessionData('login')['userid'] == 'SCI4581')) { ?>
                            <li class="health "><a href="<?= base_url('adminReport/search') ?>" class="btn-link">Download Files Casewise</a></li>
                        <?php } ?>
                        <!-- <?php  // if (!empty(getSessionData('login')) && in_array(getSessionData('login')['userid'], ICMIS_IITM_EFILE_COMPARISON_USERS)) { ?>
                            <li class="health "><a href="<?// = base_url('adminReport/UploadedPdfJsonComparison'); ?>" class="btn-link">Metadata Comparison Report</a> </li>
                        <?php // } ?>
                        <?php  // if(!empty(getSessionData('login')['ref_m_usertype_id']) && getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN) { ?>
                            <li class="health "><a href="<?// = base_url('adminReport/EfileStages'); ?>" class="btn-link">Manage E-file Stages</a> </li>
                        <?php // } ?> -->
                        <?php if ($segment->getSegment(1) == 'adminDashboard') { ?>
                        <?php } else if ($segment->getSegment(1) == 'profile') { ?>
                            <li><a href="<?= base_url('profile') ?>" style="background-color: #737373 !important;"><i class="fa fa-dashboard "></i> PROFILE </a></li>
                            <div class="clearfix"></div><br>
                        <?php } if ($segment->getSegment(2) == 'stageList') {
                            @include('templates.admin_navigation.admin_stage_list_nav');
                        }
                        /* if ($segment->getSegment(2) == 'supadmin' || (!empty($segment->getSegment(3)) &&  ($segment->getSegment(3) == 'change_case_status' || $segment->getSegment(3) == 'contact' || $segment->getSegment(3) == 'view' || $segment->getSegment(3) == 'add_contact' || $segment->getSegment(3) == 'case_type' || $segment->getSegment(3) == 'add_case_type'))) {
                            $this->load->view('templates/admin_navigation/administration_nav');
                        } */
                        if ($segment->getSegment(2) == 'notice_circulars' || $segment->getSegment(1) == 'contact_us' || $segment->getSegment(2) == 'performas') {
                            @include('templates.user_navigation.assist_news_event_nav');
                        }
                        if ($segment->getSegment(2) == 'Advocate') {
                            // $this->load->view('templates/admin_navigation/new_regisiter_nav');
                        }
                    }
                    ?>
                    <?php 
                    if (!empty($_SESSION['login']) && in_array($_SESSION['login']['ref_m_usertype_id'], array(USER_ADMIN)) && $_SESSION['login']['userid'] == 'SC-ADMIN') {
                    ?>
                    <li class="health "><a href="<?= base_url('AIAssisted/Report_search'); ?>" class="btn-link text-success">Reports AIAssisted Cases</a></li>
                    <?php } ?>
                    <li class="report"><a href="<?= base_url('logout') ?>" class="btn-link">Logout</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>