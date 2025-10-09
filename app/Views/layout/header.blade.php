<div class="mngmntHeader">
    <div class="menu-sec">
        <div class="mngmntLogoSection">
                <!-- Menu - Button Start  -->
            <div class="togglemenuSection">
                <button type="button" class="btn btn-sm togglebtn" id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
            </div>
            <!-- Menu - Button Start  -->
            <div class="brand-logo-sec">
                <!-- <div class="logo">
                <a  href="{{ base_url('redirect_on_login') }}" class="uk-icon-button" uk-icon="home"></a>
                </div> -->
                <div class="logo"><a href="{{ base_url('redirect_on_login') }}"><img src="<?= base_url().'assets/newAdmin/'?>images/logo.png" alt="  " title=" "></a></div>
                <div class="logoSubtitle">
                    <div class="brand-text">
                    <a href="{{ base_url('redirect_on_login') }}"><h4>भारत का सर्वोच्च न्यायालय
                            <span> Supreme Court of India </span>
                            <span class="logo-sm-txt">|| यतो धर्मस्ततो जय: ||</span>
                        </h4></a>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="mngmntUserSection">
            <div class="top-right-nav">
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
                                     <a href="javascript:void(0)" id="text_resize_reset">A</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" id="text_resize_decrease">A-</a>
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
            <?php
                $profile_model = new \App\Models\Profile\ProfileModel();
                $profile = !empty(getSessionData('login')) ? $profile_model->getProfileDetail(getSessionData('login')['userid']) : '';
                $get_valu = help_id_url(uri_string());
                $help_page = explode('.', $get_valu);

                $login_time = !empty(getSessionData('login')) ? $profile_model->userLastLogin(getSessionData('login')['id']) : '';
                $last_login = (!empty($login_time) && $login_time->login_time != '') ? date('d-m-Y h:i:s A', strtotime($login_time->login_time)) : NULL;
                $file_path = (isset($profile->photo_path) && $profile->photo_path != '') ? $_SERVER['DOCUMENT_ROOT'].parse_url($profile->photo_path, PHP_URL_PATH) : '';
                if (isset($profile->photo_path) && $profile->photo_path != '' && file_exists($file_path)) {
                    // $profile_photo = str_replace('/photo/', 'public/' . 'thumbnail' . '/', $profile->photo_path);
                    $profile_photo = base_url($profile->photo_path);
                } else {
                    // $profile_photo = base_url($profile->photo_path);
                    $profile_photo=base_url('assets/images/user.png');
                }
            ?>
            @if(!empty(getSessionData('login')))
                <div class="account-details">
                    <div class="userInfo">
                        <!-- <a href="#"><span class="fa fa-envelope-o icon-animated-vertical"></span></a> -->
                        <!-- <a href="#"><span class="fa fa-question animated bounceInDown"></span></a>  -->
                        <!-- <a href="#"	class="bell"><span class="fa fa-bell ringing"></span><span class="count">5</span></a> -->
                        <!-- <a	href="login.html" class="signOut"><span class="fa fa-sign-out"></span></a> -->
                    </div>
                    <div class="userInformation">
                        <!--userDetail-->
                        <div class="userDetail" id="usr-action-btn" tabindex="0" role="button">

                            <?php if(!empty(getSessionData('login')) && getSessionData('login')['aor_code'] == 10017){ ?>
                                <div class="userName"> <?= !empty(getSessionData('login')) ? getSessionData('login')['first_name'] : ''; ?> <i class="fas fa-chevron-down"></i>
                            <?php  } else { ?>
                                <?php if(!empty(getSessionData('login')) && getSessionData('login')['ref_m_usertype_id'] == AUTHENTICATED_BY_AOR){?>
                                <div class="userName"> <?= !empty(getSessionData('login')) ? strtoupper(getSessionData('login')['your_email']) : ''; ?> <i class="fas fa-chevron-down"></i>
                                <?php } else { ?>
                                    <div class="userName"> <?= !empty(getSessionData('login')) ? strtoupper(getSessionData('login')['first_name']) : ''; ?> <i class="fas fa-chevron-down"></i>
                                <?php } ?>
                            <?php } ?>
                            
                                <span class="division"><?= '<strong>Last Login: </strong>' . (!empty($last_login) ?  htmlentities($last_login, ENT_QUOTES) : '');
                                // . "<br>" . "IP : " . (!empty($login_time) ? htmlentities($login_time->ip_address, ENT_QUOTES) : '')
                                ?></span>
                            </div>
                            <div class="user-action-sec">
                                <ul>
                                    <?php if(!empty(getSessionData('login')) && getSessionData('login')['ref_m_usertype_id'] != AUTHENTICATED_BY_AOR){?>
                                    <li>
                                        <a href="<?= base_url('profile'); ?>">Profile</a>
                                    </li>
                                    <?php } ?>
                                    <li>
                                        <a href="<?= base_url('logout'); ?>">Log Out</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--userDetail-->

                        <!--userImg-->
                        <div class="userImgWrap">
                            <div class="userImg">
                                <!-- <a href="#"> -->
                                <img src="<?= $profile_photo; ?>" alt="User Image" width="56" height="56">
                                <!-- </a> -->
                            
                            </div>
                            <div class="user-action-sec">
                                <ul>
                                <?php if(!empty(getSessionData('login')) && getSessionData('login')['ref_m_usertype_id'] != AUTHENTICATED_BY_AOR){?>
                                    <li>
                                        <a href="<?= base_url('profile'); ?>">Profile</a>
                                    </li>
                                    <?php } ?>
                                    <li>
                                        <a href="<?= base_url('logout'); ?>">Log Out</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--userImg-->
                    </div>
                    
                </div>
            @endif
        </div>
    </div>			
</div>
<div id="SkipContent" tabindex="-1"></div>
