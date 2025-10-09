window.onload = function () {
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    setInterval(function () {
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE},
            url: window.location,
            success: function (data)
            {
                document.forms['submit_form'].submit();
                
            }
        });window.location.href=base_url+'/login';
    }, 1080000);
     var fiveMinutes =60*18,
        display = document.querySelector('#time');
    startTimer(fiveMinutes, display);
}

$(document).ready(function () {
   
    $(document).on('click', '.refresh_cap', function () {
        $.ajax({
            url: base_url + 'captcha_refresh',
            success: function (data) {
                $('.userCaptcha').val('');
                $('.captcha-img').replaceWith('<span class="captcha-img">' + data + '</span>');
            }
        });

    });
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy'
    });

    $(function () {
        $(".pop-me-over").popover();
    });
    $('[data-toggle="popover"]').popover({
        container: "body"
    });
    $('body').on('click', function (e) {
        if ($(e.target).data('toggle') !== 'popover'
                && $(e.target).parents('[data-toggle="popover"]').length === 0
                && $(e.target).parents('.popover.in').length === 0) {
            $('[data-toggle="popover"]').popover('hide');
        }
    });


});
function startTimer(duration, display) {
    var timer = duration, minutes, seconds;
    setInterval(function () {
        minutes = parseInt(timer / 60, 10)
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
            timer = duration;
        }
    }, 1000);
}

