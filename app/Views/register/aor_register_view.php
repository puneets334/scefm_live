<div class="col-md-12 col-sm-12 col-xs-12">  
    <a class="btn btn-default btn-xs" href="<?php echo htmlentities(base_url(), ENT_QUOTES); ?>" style="float: right;margin-right: 130px;"><br><?php echo lang('go_back'); ?></a>
</div>

<div id="wrapper" class="scroll-area">  
    <div id="content" style="font-size: 16px;">  
        <div class="uk-section uk-section-default uk-background-muted">
            <div class="uk-container">
                <div class="uk-grid" data-ukgrid style="width: -webkit-fill-available;">
                    <div class="uk-width-2-3@m">
                        <h4 class="uk-text-bold"><span><?php echo lang('instruction'); ?></span></h4>
                        <article class="uk-section uk-section-small uk-padding-remove-top" >
                            <ul>
                                <p style="text-align: left;" class="link">
                                    <span>  ✔&nbsp;<i><?php echo lang('login_instr_1'); ?> </i></span></p>
                                <p style="text-align: left;" class="link">
                                    <span> ✔&nbsp; <i><?php echo lang('login_instr_2'); ?></i></span></p>
                                <p style="text-align: left;" class="link">
                                    <span>✔&nbsp; <i><?php echo lang('login_instr_3'); ?></i></span> </p>
                                <p style="text-align: left;" class="link">
                                    <span> ✔&nbsp; <i><?php echo lang('login_instr_4'); ?></i></span> </p>
                                <p style="text-align: left;" class="link">
                                    <span> ✔&nbsp; <i><?php echo lang('login_instr_5'); ?></i></span> </p>
                                <p style="text-align: left;" class="link">
                                    <span> ✔&nbsp; <i><?php echo lang('login_instr_6'); ?></i></span> </p>
                                <p style="text-align: left;" class="link">
                                    <span> ✔&nbsp; <i><?php echo lang('login_instr_7'); ?></i></span> </p>                                                                
                                <p style="text-align: left;" class="link">
                                    <span> ✔&nbsp; <i><?php echo lang('login_instr_8'); ?></i></span> </p>
                            </ul>
                        </article>
                    </div>
                    <div class="uk-width-1-3@m">

                        <div class="uk-width-medium uk-padding-small uk-position-z-index uk-back" uk-scrollspy="cls: uk-animation-fade">

                            <div class="uk-text-center uk-margin">
                                <img src="<?= base_url('assets/images/adv/NEAR.jpg'); ?>" alt="Logo">
                            </div>

                            <?php
                            $attributes = array("class" => "form-horizontal", "id" => "loginform", "name" => "loginform", 'autocomplete' => 'off');
                            echo form_open("register/AdvocateOnRecord/adv_get_otp", $attributes);
                            ?>   

                            <?php
                            if ($this->uri->segment(2) == 'AdvocateOnRecord') {
                                $title = 'Advocate On Record';
                            } else {
                                $title = 'Party In Person';
                            }
                            ?>

                            <center><h4><?php echo $title; ?><strong> (Registration)</strong></h4></center>

                            <input type="hidden" name="register_type" value="<?php echo $title; ?>">

                            <div  class="uk-width-medium uk-padding-small uk-position-z-index uk-back" uk-scrollspy="cls: uk-animation-fade">
                                <?php echo $this->session->flashdata('msg'); ?>
                                <fieldset class="uk-fieldset">
                                    <div class="uk-margin-small">
                                        <div class="uk-inline uk-width-1-1">
                                            <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: user"></span>
                                            <input class="inpt uk-input uk-border-pill uk-input-custom" type="text" name="adv_mobile"  placeholder="Mobile"  maxlength="10" minlength="10" autofocus  autocomplete="off" >
                                            <?php echo form_error('adv_mobile'); ?> 
                                        </div>
                                    </div>  
                                    <div class="uk-margin-small">
                                        <div class="uk-inline uk-width-1-1">
                                            <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: lock"></span>
                                            <input class="inpt uk-input uk-border-pill uk-input-custom" type="text" id="adv_email" autocomplete="off" name="adv_email" placeholder="Email ID" >
                                            <?php echo form_error('adv_email'); ?> 
                                        </div>
                                    </div> 

                                    <div>
                                        <span class="captcha-img"><?php echo $captcha['image']; ?>
                                        </span>
                                        <span><img src="<?php echo base_url('assets/images/refresh.png'); ?>" height="20px" width="20px"  alt="refresh" class="refresh_cap" /></span>
                                        <input type="text" autocomplete="off" name="userCaptcha" placeholder="Captcha" maxlength="6" class="inpt uk-input uk-border-pill uk-input-custom" value="<?php echo $captcha['word']; ?>" style="width:225px;float: right;">
                                        <?php echo form_error('userCaptcha'); ?> 
                                    </div> 
                                    <br>
                                    <div class="uk-margin-bottom">
                                        <button type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-1" name="btn_login" value="register" class="uk-button uk-button-primary uk-border-pill uk-width-1-1"> SEND OTP</button>
                                    </div> 
                                </fieldset>
                                <?php echo form_close(); ?>


                                <div>

                                </div>
                            </div>
                        </div>

                        <center>
                            New User? <a href="<?php echo base_url('register') ?>">Party In Person</a> |
                            <a href="<?php echo base_url('register/AdvocateOnRecord') ?>">AOR register</a> <br>
                            <a href="<?php echo base_url('register/ForgetPassword'); ?>">Forgot Password</a> |
                            <a href="<?php echo base_url('login'); ?>">Login</a>  
                        </center>   

                    </div>
                </div>
            </div>
        </div>

        <?php if (ENABLE_DISCLAIMER != '') { ?>
            <center><br><br>
                <div class="container-box" style="margin-top: -73px;padding: 0;background: rgba(243, 236, 236, 0.4)">
                    <p class="left-align" style="color: red; margin: 8px 0px 6px 0;"><?php echo lang('disclaimer'); ?></p>
                </div>
            </center>
        <?php } ?>
    </div><br><br>
</div>   

<script src="<?= base_url('assets/js/jquery.min.js'); ?>" type="text/javascript"></script>   
