<?php
session_start();


$_SESSION[qrtext]=$out;
$this->load->view('templates/header.php');
$this->load->view('newcase/new_case_breadcrumb.php');

$qr_code_base64 = generate_qr_code(['data' => $out])->base64;

?>
<div class="panel panel-default" id="res">
    <h4 style="text-align: center;color: #31B0D5">OTP Verification for CASE DATA ENTRY </h4>
    <div class="panel-body">


        <div id="qr" > <!-- <a href="#" id="getqrcode">Please Download Qr Certificate</a>-->
        </div>   <input type="hidden"  size="50" class="form-control" name="qrcode_text" id="qrtext" value="<?php echo $out;?>">


        <div id='success'>
            <input type='hidden' id='otprec' value="<?php  echo $_SESSION['otp']?>">

            <input type="hidden" id ="otp" value="<?php echo $_SESSION['otp'] ?>">
            <input type="hidden" id ="endtime" value="<?php echo $_SESSION['endtime'] ?>">
            <div id='print'>
                <div class="form-group">
                    <label for="text"></label>
                    <input type="hidden" id="qr_img" value="<?php echo 'base_url()';?>.'/images/'.<?php echo $file_name1?>">
                    <div id="img_qr">     <center><img src="data:image/png;base64, <?=$qr_code_base64?>" height="200" width="200"></center>
                    </div>
                    <center><b><h2><?php echo $_SESSION['efiling_details']['efiling_no'] ?></h2></b></center>




                </div>
            </div>

            <div id="s" align="center"></div>
            <div id='senddiv' align='center'>  <button  id = 'sendotp' class="btn btn-success" type="button"  id="send" >Send OTP </button></div>

            <div align='center' id='showotp'> Enter OTP <input id="otptext"  type= "text" size="6" placeholder="otp">

                <div id="resend" >  <a id="resend_otp">Resend OTP</a>
                </div>
                <input class="btn btn-success"  id='verify' type="button" value="Verify Otp " >

            </div>
            <div id='finalsub'>
                <center> <input  type=button class="btn btn-success" type='button' id='finalsub' value='Final submit'>
                </center>
            </div>
            <!--  <input type="text" id="otptext" size="6" placeholder="otp">

                                   <input class= "btn btn-success" type='button' id='verify_otp' value='verify otp' onClick='v()'>


           </body>-->

        </div>
    </div>
</div>

<?php

$this->load->view('templates/footer.php');
//$a is the parametro which contains value of registration id
?>

<script type="text/javascript" >



    $(document).ready(function(){
        $('#showotp').hide();
        $('#finalsub').hide();
        $("#qr").hide();
        $("img_qr").hide();
    });

    $("#finalsub").click(function(){
        //  alert("button is clicked");
        // window.location.href="<?php //echo base_url('dashboard')?>";
        window.location.href="<?php echo base_url('newcase/finalSubmit/submitforcde')?>";
    });
    $("#getqrcode").click(function(){



        var curDate=new Date();
        var x=curDate.toString();
        var contents=document.getElementById('print').innerHTML;
        var a=window.open('','','height=500,width=500');
        a.document.write('<html>');
        a.document.write('<body><center><h2>Supreme Court of India</h2><br>');
        a.document.write(x);
        a.document.write(contents);
        a.document.write('<font color=red >Note: You are Required to Show this QR code at the Filing Counter along with the Documents <br><br>');
        a.document.write('<font color=red >Note:Without this QR Acknowledgement your efiling will not be considered for case data entry at filing counter ');
        a.document.write('</body></center></html>');
        a.document.close();
    });

    $('#resend_otp').on('click',function(){
        document.getElementById('s').innerHTML='<font color=green>OTP Sent Again.</font>' ;
        $.ajax({
            type:"POST",
            url:"<?= base_url('newcase/finalSubmit/resend_otp'); ?>"
        });
    });

    $("#sendotp").click(function(){
        $('#senddiv').hide(); // document.getElementById('s').innerHTML='<font color=green>One time password has been sent to your mobile and is valid for 5 minutes</font>';

        $('#showotp').show();
        $.ajax({
            //type: "POST",
            //data: '',
            url: "<?php echo base_url('newcase/finalSubmit/sendotp_cde'); ?>",
            success: function (data)
            {
                var resArr = data.split('@@@');
                if (resArr[0] == 2) {
                    // $('#msg').show();
                    //  document.getElementById('s').innerHTML=resArr[1] ;
                    document.getElementById('s').innerHTML='<font color=red>'+resArr[1]+'</font>' ;

                }
                if (resArr[0] == 1) {
                    document.getElementById('s').innerHTML='<font color=green>'+resArr[1]+'</font>' ;

                }
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }



        });
        return false;
    });





    $("#verify").click(function(){
        $('#showotp').hide();


        var otp_to_verity=$('#otptext').val();

        if(otp_to_verity==='')
        {
            alert('PLEASE ENTER OTP');
        }
        else
        {
            var s=$('#otptext').val();
            //   alert(s);
            $.ajax({
                type: "POST",

                url: "<?php echo base_url('newcase/finalSubmit/verify_otp'); ?>",
                data:{s:s},
                success: function (data)
                {
                    var resArr = data.split('@@@');
                    if (resArr[0] == 1) {

                        document.getElementById('s').innerHTML='<font color=green>'+resArr[1]+'</font>' ;
                        $('#qr').show();
                        $('#img_qr').show();
                        $('#finalsub').show();
                    }
                    if (resArr[0] == 2) {
                        document.getElementById('s').innerHTML='<font color=red>'+resArr[1]+'</font>' ;
                        $('#resend').show();
                        $('#showotp').show();
                        $('#otptext').val('');
                        $('#otptext').focus();
                        $('#senddiv').hide();
                        $('#finalsub').hide();
                    }
                    /*  if (resArr[0] == 3) {
                         document.getElementById('s').innerHTML='<font color=red>'+resArr[1]+'</font>' ;
                      $('#resend').show();
                      $('#showotp').show();
                      $('#otptext').val('');
                      $('#otptext').focus();
                      $('#senddiv').hide();
                      $('#finalsub').hide();
                    }*/
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function () {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }



            });


        }  // end of else
        return false;
    });



</script>
