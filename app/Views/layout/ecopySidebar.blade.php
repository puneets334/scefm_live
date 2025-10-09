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
                // $profile_photo = base_url($profile->photo_path);
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
                    <li class="report"><a href="<?= base_url('logout') ?>" class="btn-link">Logout</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>