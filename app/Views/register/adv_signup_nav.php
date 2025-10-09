
<link href="<?= base_url() . 'assets' ?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> 
        <link href="<?= base_url() . 'assets' ?>/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
        <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
        <link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery-ui.css"> 
        <link href="<?= base_url() . 'assets' ?>/build/css/custom.min.css" rel="stylesheet">
       <link href="<?= base_url() . 'assets' ?>/build/css/custom.css" rel="sty lesheet">
        <link href="<?= base_url() . 'assets' ?>/css/select2.min.css" rel="stylesheet"> 
        <link href="<?= base_url() . 'assets' ?>/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet"> 
        <link href="<?= base_url() . 'assets' ?>/css/uikit.min.css" rel="stylesheet">
        <link href="<?= base_url() . 'assets' ?>/css/login-dark.css" rel="stylesheet">
        <script type="text/javascript">
            var base_url = '<?php echo base_url(); ?>';
        </script>

        <script type="text/javascript" src="<?= base_url() . 'assets' ?>/js/jquery-main.js"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets' ?>/js/jquery-ui.min.js"></script>
        <script src="<?= base_url() . 'assets' ?>/js/bootstrap-datepicker.min.js"></script>
        <script language="javascript"> var base_url = "<?= base_url() ?>"</script>
        <script src="<?= base_url() . 'assets' ?>/js/jquery-ui.js"></script> 
        <style type="text/css">
	        	.error-tip{
	        		color:red;
	        	}
        </style>

<script>
    $(document).ready(function () {
        $('.filter_select_dropdown').select2();
    });
  
    $('#date_of_birth').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:-1",
        dateFormat: "dd/mm/yy",
        defaultDate: '-40y'
    }); 

    $('#state_id').change(function () {
        $('#district_list').val('');
        $('#establishment_list').val('');
        var get_state_id = $(this).val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
            url: "<?php echo base_url('register/AdvSignUp/get_dist_list'); ?>",
            success: function (data)
            {
                if (data.indexOf('ERROR') != -1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + data + " <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    $('#district_list').html('<option value=""> Select District </option>');

                } else {
                    $('#msg').hide();
                    $('#district_list').html(data);
                }
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }); 

     
</script>


<script type="text/javascript">
$(document).ready(function (e) {

    $("#uploadForm").on('submit',(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?php echo base_url('register/AdvSignUp/upload_photo'); ?>",
            type: "POST",
            data:  new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            success: function(data)
            {
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                        $('#msg').show();
                        $(".form-response").html("<p class='text-center alert alert-danger flashmessage message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' >X</span></p>");
                    }

            $("#targetLayer").html(data);
            },
            error: function() 
            {
            }           
       });
    }));
});
</script> 



<style type="text/css">
     
.bgColor {
max-width:98%;
height:150px;
background-color: #eeeeee;
border-radius:0px;
}
.bgColor label{
font-weight: bold;
color: #A0A0A0;
}
#targetLayer{
float:left;
width:150px;
height:150px;
text-align:center;
line-height:150px;
font-weight: bold;
color: #C0C0C0;
background-color: #F0E8E0;
border-bottom-left-radius: 4px;
border-top-left-radius: 4px;
}
#uploadFormLayer{
    /*float:left;*/ 
} 
.inputFile {
        padding: 5px;
    margin-top: -12px;
}
.image-preview {    
width:150px;
height:150px;
border-bottom-left-radius: 4px;
border-top-left-radius: 4px;
}

</style> 

<script type="text/javascript" src="<?= base_url() . 'assets' ?>/js/main_registration.js"></script>  
<script type="text/javascript" src="<?= base_url() . 'assets' ?>/js/jquery.validate.js"></script>   
<script src="<?= base_url() . 'assets' ?>/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/vendors/moment/min/moment.min.js"></script> 
<script src="<?= base_url() . 'assets' ?>/js/select2.min.js"></script>