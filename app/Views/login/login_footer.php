
<script src="<?= base_url('assets/css/logincss/js/uikit.min.js'); ?>"></script>
<script src="<?= base_url('assets/css/logincss/js/uikit-icons.min.js'); ?>"></script> 
</body>
<?php if(true){ ?><!--for responsive_variant-->
<footer id="footer" class="footer" style="background-image:url(<?= base_url('assets/images/adv/back.png'); ?>);padding: 5px 0;z-index: 9;">  
    <div class="uk-container">
        <p class="uk-text-center" style="color: black;"><strong>© 2020 </strong> Supreme Court of India </p>
    </div>
</footer>
<style>
    .footer {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        background-color: gray;
        color: white;
        text-align: center;
    }
</style>
<?php } ?>
<script>
    $(document).ready(function () {
        $(document).on('click', '.refresh_cap', function () {
            $.ajax({
                url: base_url + 'captcha_refresh',
                success: function (data) {
                    $('.userCaptcha').val('');
                    $('.captcha-img').replaceWith(data);
                }
            });
        });
        });
        
</script>
</html> 