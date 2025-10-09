<script type="text/javascript">
    var count = 3;
    var redirect = "<?=base_url('supplements/DefaultController/pdfDocHome/')?>";

    function countDown(){
        var timer = document.getElementById("timer");
        if(count > 0){
            count--;
            timer.innerHTML = "This page will redirect in "+count+" seconds.";
            setTimeout("countDown()", 1000);
        }else{
            window.location.href = redirect;
        }
    }

</script>

<?php
if(trim($this->session->flashdata('msg'))!="" || trim($this->session->flashdata('msg_error'))!=""){
    echo '<span id="timer">
<script type="text/javascript">countDown();</script>
</span>';
}
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div id="msg"><?php echo $this->session->flashdata('msg');?></div>
    </div>
</div>
<div class="panel panel-default">
    <h4 style="text-align: center;color: #31B0D5">Affidavit Deponent Type</h4>
    <center>  <br>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <label class="radio-inline input-lg"><input type="radio" name="deponent_type" class="deponent_type" id="deponent_type_petitioner" value="<?php echo url_encryption('1');?>">Petitioner</label>
                <label class="radio-inline input-lg"><input type="radio" name="deponent_type" class="deponent_type" id="deponent_type_powerAttorney" value="<?php echo url_encryption('2');?>">Power of attorney</label>
                <label class="radio-inline input-lg"><input type="radio" name="deponent_type"  class="deponent_type" id="deponent_type_pairokar" value="<?php echo url_encryption('3');?>">Pairokar</label>

            </div>
        </div>
    </center>
    <br><hr>

</div>
<!------------vakalatnama--------------------->

<div id="affidavit_template_bind"></div>

<!------------vakalatnama--------------------->

<script>
    // $('#deponent_type_petitioner').trigger('click');
    $(document).ready(function () {
        <?php if(!empty($affidavit_list[0]['deponent_type'])){ ?>
        <?php if($affidavit_list[0]['deponent_type']==1){?>
        //alert('petitioner');
        $('#deponent_type_petitioner').trigger('click');
        <?php } else if($affidavit_list[0]['deponent_type']==2){?>
        $('#deponent_type_powerAttorney').trigger('click');
        <?php }else if($affidavit_list[0]['deponent_type']==3){ ?>
        $('#deponent_type_pairokar').trigger('click');
        <?php } ?>
        deponentloadData('<?=url_encryption($affidavit_list[0]['deponent_type'])?>');
        <?php } ?>
        function deponentloadData(deponent_type) {
            //alert(deponent_type);
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, deponent_type: deponent_type},
                url: "<?php echo base_url('supplements/DefaultController/affidavit_template'); ?>",
                success: function (data) {
                    $('#affidavit_template_bind').html(data);

                },
                error: function () {
                    $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });

        }


        $('.deponent_type').click(function () {

            var deponent_type = $(this).attr("value");
            //alert(deponent_type);
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,deponent_type:deponent_type},
                url:  "<?php echo base_url('supplements/DefaultController/affidavit_template'); ?>",
                success: function (data)
                {
                    $('#affidavit_template_bind').html(data);

                },
                error: function () {
                    $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });

        });
    });
</script>





