<!--start Captcha Resource-->
<div class="row">
<input type="text" style="display: none;" name="_token" value="{{ csrf_token() }}">
    <div class="col-4 col-sm-4 col-md-4 col-lg-4">
        <img src="<?=base_url('captcha/index');?>" id='captcha_image' style="height: 45px !important;">
    </div>
    <div class="col-3 col-sm-3 col-md-3 col-lg-3">
        <img src="<?=base_url('CaptchaResource/images');?>/speaker.png" class="img-rounded pointer" onclick="playAudio();" alt="speaker" style="width: 28px;cursor: pointer;">
        <img src="<?=base_url('CaptchaResource/images');?>/refresh.png" class="img-circle pointer" onclick="refreshCaptcha();" alt="captcha Refresh" style="width:28px;border-radius: 50%;cursor: pointer;">
    </div>
    <div class="col-5 col-sm-5 col-md-5 col-lg-5">
        <input type="text" class="form-control uk-input" name="userCaptcha" id="captchacode" placeholder="Enter captcha" minlength="6" maxlength="6" required>
    </div>
</div>
<script src="<?php echo base_url('CaptchaResource/js/Captcha.js'); ?>"></script>
<!--end Captcha Resource-->