 //refreshCaptcha();
function refreshCaptcha(){
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = encodeURIComponent($('[name="_token"]').val());
    if(CSRF_TOKEN_VALUE){
        var img = document.images['captcha_image'];
        var rand = encodeURIComponent(Math.random()*1000);
        img.src = "/captcha/index?rand="+rand+'&CSRF_TOKEN='+CSRF_TOKEN_VALUE;
    }else{
        $('.text-red').html('The action you requested is not allowed (Invalid CSRF TOKEN)');
    }
}

function getCode() {
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        url: "/captcha/DefaultController/get_code",
        cache: false,
        async: true,
        data: {
            CSRF_TOKEN:CSRF_TOKEN_VALUE,id: 'getCode',
        },
        type: 'post',
        success: function (data, status) {
            if (data != 0) {
                var CaptchaCode=data;
                if (CaptchaCode.length != 0){
                    $('#current_captcha_code').val(CaptchaCode);
                    $('#captcha_code').val(CaptchaCode);
                }else{
                    $('#playAudio').hide();
                    alert('Captcha code audio not found');
                }
            }else {

            }
        }
    });
}
function playAudio() {
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    //var CSRF_TOKEN_VALUE=document.querySelector('input[name=CSRF_TOKEN]').value;
   // alert('CSRF_TOKEN_VALUE='+CSRF_TOKEN_VALUE);
    $.ajax({
        url: "/captcha/DefaultController/get_code",
        cache: false,
        async: true,
        data: {
            CSRF_TOKEN:CSRF_TOKEN_VALUE,id: 'getCode',
        },
        type: 'post',
        success: function (data, status) {
            if (data != 0) {
                var CaptchaCode=data;
                //var CaptchaCode=$('#current_captcha_code').val();
                if (CaptchaCode.length != 0){
                        const { captcha, spokenText } = generateCaptcha(CaptchaCode);
                        console.log(captcha);
                        const msg = new SpeechSynthesisUtterance();
                        msg.text = `${spokenText}`;
                        msg.lang = 'en-US';
                        window.speechSynthesis.speak(msg);
                }else{
                    $('#playAudio').hide();
                    alert('Captcha code audio not found');
                }
            }else if (data==0) {
                //alert(resArr[1]);
                $('.text-red').html(resArr[1]);
                //location.reload();
            }else {
                $('.text-red').html('The action you requested is not allowed (Invalid CSRF TOKEN)');
               // location.reload();
            }
        }
    });

}


function generateCaptcha(captcha) {
    const spokenText = Array.from(captcha)
        .map(char => {
            if(!isNaN(char)){
                return char;
            }
            // else{
                
            // }
            return (char === char.toUpperCase() ? `Capital ${char}` : (!isNaN(char)) ? char : `small ${char}`) ;
        })
        .join(", ");

    return { captcha, spokenText };
}

// function isInt(value) {
//     var x = parseFloat(value);
//     return !isNaN(value) && (x | 0) === x;
//   }