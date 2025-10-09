<?php
if ($this->uri->segment(2) != 'view') {
    if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
        $this->load->view('miscellaneous_docs/misc_docs_breadcrumb');
    } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
        $this->load->view('IA/ia_breadcrumb');
    }elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MENTIONING) {
        $this->load->view('mentioning/mentioning_breadcrumb');
    }
}
?>

<div class="panel panel-default">
    <h4 style="text-align: center;color: #31B0D5">Mentioning Details</h4>
    <div class="panel-body">
        <?php if (!empty($listing) && !empty($_SESSION['efiling_details']['efiling_type']) && $_SESSION['efiling_details']['efiling_type']=='mentioning'){?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <?php if (!empty($listing->approval_status) && $listing->approval_status=='a'){
                        $sms='<span class="text-success">Mentioning request has been approved for'.$listing->approved_for_date.'</span>';
                    }else if(!empty($listing->approval_status) && $listing->approval_status=='r'){
                        $sms='<span class="text-danger">Mentioning request rejected, reason for rejection is :'.$listing->rejection_reason.'</span>';
                    }else{
                        $sms='<span class="text-danger">Mentioning request is still pending before competent authority.</span>';
                    }
                    echo 'Status: '.$sms;
                    ?>
                </div>
            </div>
        <?php }?>
        <br/>
        <?php
        $attribute = array('class' => 'form-horizontal', 'name' => 'listing_details', 'id' => 'listing_details', 'autocomplete' => 'off','enctype' => 'multipart/form-data');
        echo form_open('mentioning/Listing/add_details', $attribute);
        $holidays_list[]="";
        foreach($holidays as $key=>$value)
        {
            foreach($value as $key1=>$value1)
                $holidays_list[]=$value1->hdate;
        }

        ?>


        <div class="panel panel-default panel-info">

            <div class="panel-heading" style="height:42px;"><h5 style="text-align: left;"><b>Listing Details</b></h5></div>
            <div class="panel-body">

                <?php $checkedRequest_type_m='';$checkedRequest_type_o='';
                if (!empty($listing->request_type) && $listing->request_type !=null && $listing->request_type=='O'){
                    $checkedRequest_type_o='checked="checked"';
                }else{
                    $checkedRequest_type_m='checked="checked"';
                }
                $checkedFixed=''; $checkedRange='';$date_range='';
                if (!empty($listing)){
                    $action='disabled';
                }else{$action='';}
                if(!empty($listing->requested_listing_date_range) && $listing->requested_listing_date_range!=null){
                    $checkedRange='checked="checked"';
                    $str = trim(str_replace( array("[", "]", "(", ")"), '', $listing->requested_listing_date_range));
                    $date_range_part = explode(',', $str);

                    $first_dateP=new DateTime(str_replace('-','/',$date_range_part[0]));
                    $first_dateF= $first_dateP->format('Y/m/d');

                    $second_dateP = new DateTime(str_replace('-','/',$date_range_part[1]));
                    $second_dateF= $second_dateP->format('Y/m/d');
                    $date_range = trim($first_dateF) . '-' . trim($second_dateF) ;
                }else{ $checkedFixed='checked="checked"';}

                if (!empty($listing->requested_listing_date) && $listing->requested_listing_date!=null){
                    $listing_dateC = new DateTime(str_replace('-','/',$listing->requested_listing_date));
                     $listing_date= $listing_dateC->format('Y/m/d');
                }else{
                    $listing_date ='';
                }
                //echo $date_range;
                //echo '<pre>';print_r($_SESSION['efiling_details']);echo '</pre>';
                //echo '<pre>';print_r($listing);echo '</pre>';
                ?>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="input-group">
                            <label class="radio-inline"><input type="radio" name="request_type" id="request_type" class="request_type" value="M" <?=$checkedRequest_type_m;?>  <?=$action;?>><strong style="font-size: 16px;">Mention with Urgency Letter.</strong></label>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="input-group">
                            <label class="radio-inline"><input type="radio" name="request_type" id="request_type" class="request_type" value="O" <?=$checkedRequest_type_o;?>  <?=$action;?>><strong style="font-size: 16px;">Request for Oral Mention.</strong></label>
                        </div>
                    </div>
                </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-2 col-xs-6">
                    <div class="input-group">
                        <br/><br/>
                        <label class="radio-inline"><input type="radio" name="listing_option"  tabindex="1" onchange="changeSelect(this.value);" id="listing_option" class="listing_option_s" value="1" <?=$checkedFixed;?>  <?=$action;?>><strong style="font-size: 16px;">Fixed Date</strong></label>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="daterangeArea">
                    <div class="input-group"><br/><br/>
                        <label class="radio-inline"><input type="radio"  tabindex="1" onchange="changeSelect(this.value);" name="listing_option" id="listing_option" class="listing_option_r" value="2" <?=$checkedRange;?>  <?=$action;?>><strong style="font-size: 16px;">Date Range</strong></label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" id="datesingle">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <br/><br/>
                        <div class="input-group">
                            <input class="form-control datepick" tabindex="2" id="listing_date"  name="listing_date"  placeholder="DD/MM/YYYY"  type="text" value="<?php echo $listing_date;?>"  <?=$action;?>>
                            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                            <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Listing date.">
                                <i class="fa fa-question-circle-o"></i>
                        </div>
                    </div>
                </div>
            </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" id="daterange" style="display:none;">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <br/><br/>
                    <div class="input-group">
                        <input class="form-control daterangepick" tabindex="2" id="listing_date_range"  name="listing_date_range"  placeholder="DD/MM/YYYY-DD/MM/YYYY"  type="text" value="<?php echo $date_range;?>"  <?=$action;?>>
                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Listing date Range.">
                                <i class="fa fa-question-circle-o"></i>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp&nbsp&nbsp</div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="request_type_upload">
            <div class="row">
                <br/>
                <div class="form-group">
                    <label class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-left: 10%;font-size:12px;"><p id="text-size">Upload Document :</p>
                        <span>Fresh Matter: Upload Mentioning doc for Caveat Advocate<br/>
                            Regular Matter: Upload other side consent letter</span></label>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <input name="pdfDocFile" id="pdfDocFile" enctype="multipart/form-data" tabindex="3" type="file" <?=$action;?>>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12  col-xs-12">
            <div class="form-group">
                <label class="control-label" id="text-size">Synopsis of Urgency
                    <span style="color: red">*</span></label>
                <div class="input-group">
                    <textarea id="synopsis" tabindex="4" minlength="10" maxlength="800" rows="5" tabindex="1" type="text" name="synopsis" class="form-control input-sm" placeholder="Synopsis"  <?=$action;?>><?php echo $listing->synopsis_of_emergency;?></textarea>
                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter reason of urgency in brief (<?php /*echo VALIDATION_PREG_MATCH_MSG; */?>).">
                                        <i class="fa fa-question-circle-o" ></i>
                    </span>
                </div>
            </div>
        </div>
       <!-- <div class="col-lg-12 col-md-12 col-sm-12  col-xs-12">
                <label class="control-label">Listing<span style="color: red">*</span></label>
        </div>-->

    </div>
            <div class="row col-md-12">
                <input type="checkbox" id="engaged" name="engaged" value="" <?=$action;?>>
                <label for="engaged" class="control-label">&nbsp; Engaged in advocate
                </label>
            </div>
        </div>


        <!--<div class="col-lg-12 col-md-12 col-sm-12  col-xs-12">
            <div class="row">&nbsp</div>
            <div class="row">&nbsp</div>
        </div>-->

        <div class="panel panel-info" id="arguing_counsel_div" style="display: none;margin-top: 29px;">
            <div class="panel-heading" style="height:42px;"><h5 style="text-align: left"><b>Advocate</b></h5></div>
<!--            <div class="panel-heading">Advocate</div>-->
            <div class="panel-body">
<!--        <div class="col-lg-12 col-md-12 col-sm-12  col-xs-12">
          <label class="control-label col-xs-12" style="font-size: large; text-align: left">2. Advocate</label>
        </div>-->
        <div class="col-lg-12 col-md-12 col-sm-12  col-xs-12">
                <div class="col-sm-4">
                    <label class="control-label" id="text-size">Name<span style="color: red">*</span></label>
                    <div class="input-group">
                        <input class="form-control" tabindex="5" name="council_name" id="council_name" maxlength="45" type="text" placeholder="Name">
                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter Arguing Council's Name.">
                                <i class="fa fa-question-circle-o"></i> </div>
                </div>
                <div class="col-sm-4">
                    <label class="control-label" id="text-size">E-mail<span style="color: red">*</span></label>
                    <div class="input-group">
                        <input class="form-control" tabindex="6" name="council_email" id="council_email" maxlength="35"  type="email" placeholder="Email">
                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter Arguing Council's E-mail Id.">
                                <i class="fa fa-question-circle-o"></i>
                    </div>
                </div>
                <div class="col-sm-4">
                    <label class="control-label" id="text-size">Mobile<span style="color: red">*</span></label>
                    <div class="input-group">
                        <input class="form-control" tabindex="7" name="council_mob" id="council_mob" placeholder="Mobile" type="text" maxlength="10" minlength="10" value="" >
                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter Arguing  Council's Mobile Number.">
                                <i class="fa fa-question-circle-o"></i>
                    </div>
                </div>
            </div>
        </div>

        </div>
    <br/><br/>
    </div>

        <div class="col-md-offset-5 col-md-4 col-sm-offset-3 col-sm-6 col-xs-12">
            <a href="<?= base_url('case_details'); ?>" tabindex = '8' class="btn btn-primary btnPrevious" type="button" <?=$action;?>>Previous</a>
            <input type="button" class="btn btn-success" id="send_otp" tabindex="9" name="send_otp" value="SEND OTP" <?=$action;?>>
        <br/>
            <label class="control-label" id="otp_label">Enter OTP<span style="color: red">*</span> <input type="text" name="otp" id="otp" tabindex="10"> &nbsp;&nbsp;&nbsp;</label>
                &nbsp;
            <span id="AreaResendOTPAlert" style="display:none;">Resend OTP:<span id="ResendOTPAlert"></span></span>
            <a href="#" id="resend_otp" class="btn btn-success" style="display:none;" <?=$action;?>>Resend OTP</a>

                <br/><br/>
            <input type="submit" class="btn btn-success" id="save_mentioning" tabindex="11" name="save_mentioning" value="VALIDATE OTP AND SUBMIT" <?=$action;?>>
        <?php
        echo form_close();
        ?>
    </div>


<!--    <script src="--><?//= base_url() ?><!--assets/js/daterangepicker/jquery.min.js"></script>-->
    <script src="<?= base_url() . 'assets' ?>/js/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/daterangepicker/daterangepicker.min.js"></script>
    <script src="<?= base_url() ?>assets/js/daterangepicker/moment.min.js"></script>
    <script src="<?= base_url() ?>assets/js/daterangepicker/daterangepicker.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script src="<?= base_url() ?>assets/js/sha256.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<!--    <link rel="stylesheet" href="--><?//= base_url() ?><!--assets/css/AdminLTE.min.css">-->


    <style>
        input.disablereadonly:enabled {
            /* style declaration here */
            background: white;
        }

        #text-size{
            font-size:17px;
        }


    </style>




<script type="text/javascript">
    <?php if(!empty($date_range) && $date_range!=null){?>
    $('#daterange').show();
    $('#datesingle').hide();
    <?php }else{?>
    $('#daterange').hide();
    $('#datesingle').show();
    <?php }?>
 </script>

<script type="text/javascript">
    var excluded_dates=<?php echo json_encode($holidays_list) ?>;

    /*Start of changes*/


    $(document).on("focus",".datepick",function(){
           $('.datepick').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY'
                },
                drops:'down',
                singleDatePicker: true,
                showShortcuts: false,
                numberOfMonths: 2,
                minDate:moment(),
                maxDate:moment().add('20','day'),
                maxDays:5,
                stepMonths: '+0',
                yearRange: '-0:+1',
                beforeShowDay: function(date) {
                    date = $.datepicker.formatDate('YYYY-MM-DD', date);
                    var excluded = $.inArray(date, excluded_dates) > -1;
                    return [!excluded, ''];
                }
            });
    });


    $(document).on("focus",".daterangepick",function(){
        $('.daterangepick').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'
            },
            drops:'down',
            singleDatePicker: false,
            showShortcuts: false,
            numberOfMonths: 2,
            minDate:moment(),
            maxDate:moment().add('20','day'),
            maxDays:5,
            stepMonths: '+0',
            yearRange: '-0:+1',
            beforeShowDay: function(date) {
                date = $.datepicker.formatDate('YYYY-MM-DD', date);
                var excluded = $.inArray(date, excluded_dates) > -1;
                return [!excluded, ''];
            }
        });
    });
/*End of Changes*/


    function isEmpty(obj) {
        if (obj == null) return true;
        if (obj.length > 0)    return false;
        if (obj.length === 0)  return true;
        if (typeof obj !== "object") return true;

        // Otherwise, does it have any properties of its own?
        // Note that this doesn't handle
        // toString and valueOf enumeration bugs in IE < 9
        for (var key in obj) {
            if (hasOwnProperty.call(obj, key)) return false;
        }

        return true;
    }
/*Not required anymore due to change in User Interface*/
/*function changeDateRange()
{
    var date_range=$('#date_range').val();
    var date_range1=date_range.split('/');
    if(date_range1[1]<10)
       date_range1[1]= date_range1[1].slice(1);
    var days=parseInt($('#days').val());
    if(days!=0 && !isEmpty(date_range)) {
        var start_date = new Date(date_range1[2],date_range1[1]-1,date_range1[0]);
        var end_date = new Date(date_range1[2],date_range1[1]-1,date_range1[0]);
        end_date.setDate(start_date.getDate() + days);
        var day_end = end_date.getDate();
        var month_end = end_date.getMonth()+1;
        var year_end = end_date.getFullYear();
        var range_end_date=day_end+"/"+month_end+"/"+year_end;
        var day_start = start_date.getDate();
        var month_start = start_date.getMonth()+1;
        var year_start = start_date.getFullYear();
        var range_start_date=day_start+"/"+month_start+"/"+year_start;
        $('#date_range_value').val(range_start_date+" to "+range_end_date);
    }
}*/

function changeSelect($option)
{
    if($option==1)
    {
/* Start of changes*/
        $('#listing_date').val('');
        $('#daterange').hide();
        $('#datesingle').show();
/*

       $('#listing_date').removeAttr("disabled");
       $('#date_range').attr("disabled","disabled");
        $('#days').attr("disabled","disabled");
        $('#date_range_value').attr("disabled","disabled");
*/
/*End of changes*/

    }
    if($option==2)
    {
        /*Start of changes*/
        $('#listing_date').val('');
        $('#daterange').show();
        $('#datesingle').hide();
/*
    Comment changes on 15th September 2020
        $('#listing_date').attr("disabled","disabled");
        $('#date_range').removeAttr("disabled");
        $('#days').removeAttr("disabled");
        $('#date_range_value').removeAttr("disabled");
*/
/*End of changes*/
    }
}

$(document).ready()
    {
        //$('#listing_date').attr("disabled","disabled");
        //$('#date_range').attr("disabled","disabled");
       // $('#days').attr("disabled","disabled");
       // $('#date_range_value').attr("disabled","disabled");
        $('#otp').hide();
        $('#otp_label').hide();
        $('#save_mentioning').hide();
        $('#resend_otp').hide();
    }

    $('#send_otp').on('click',function() {
        var flag= true;
        var synopsis = $("#synopsis").val();
        var engaged = $("#engaged").val();
        var council_name = $("#council_name").val();
        var council_email = $("#council_email").val();
        var council_mob = $("#council_mob").val();
        var alphaNum = /^[\w\-\s]+$/;
        //var alphaNum = ^[\w\-\s .\'/()\"%]+$;
        var numberReg = /^\d+$/;
        var emailReg  = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        if(synopsis == ''){
            alert("Please fill synopsis of urgency.");
            $("#synopsis").focus();
            $("#synopsis").css('border-color','red');
            flag = false;
            return false;
        }
        /*else if(!alphaNum.test(synopsis)){
            alert("Please remove special characters.")
            $("#synopsis").focus();
            $("#synopsis").val('');
            $("#synopsis").css('border-color','red');
            flag = false;
            return false;
        }*/
        else if(engaged){
            if(council_name == ''){
                alert("Please fill name.")
                $("#council_name").focus();
                $("#council_name").css('border-color','red');
                flag = false;
                return false;
            }
            else if(!alphaNum.test(council_name)){
                alert("Please remove special characters.")
                $("#council_name").focus();
                $("#council_name").val('');
                $("#council_name").css('border-color','red');
                flag = false;
                return false;
            }
           else if(council_email == ''){
                alert("Please fill email address.")
                $("#council_email").focus();
                $("#council_email").css('border-color','red');
                flag = false;
                return false;
            }
            else if(!emailReg.test(council_email)){
                alert("Please fill valid email address.")
                $("#council_email").focus();
                $("#council_email").val('');
                $("#council_email").css('border-color','red');
                flag = false;
                return false;
            }
            else if(council_mob == ''){
                alert("Please fill mobile number.")
                $("#council_mob").focus();
                $("#council_mob").css('border-color','red');
                flag = false;
                return false;
            }
            else if(council_mob.length > 10 || council_mob.length < 10){
                alert("Please fill valid mobile number.")
                $("#council_mob").focus();
                $("#council_mob").val('');
                $("#council_mob").css('border-color','red');
                flag = false;
                return false;
            }
            else if(!numberReg.test(council_mob)){
                alert("Please fill valid mobile number.")
                $("#council_mob").focus();
                $("#council_mob").val('');
                $("#council_mob").css('border-color','red');
                flag = false;
                return false;
            }
        }
        if(flag){
            $('#otp').show();
            $('#otp_label').show();
            $('#save_mentioning').show();
            //$('#resend_otp').show();
            $('#AreaResendOTPAlert').show();
            $('#send_otp').hide();
            var seconds =30;
            setInterval(function () {
                seconds--;
                $("#ResendOTPAlert").text(seconds);
                if (seconds == 0) {
                    $("#AreaResendOTPAlert").hide();
                    $("#resend_otp").show();

                }
            }, 1000);
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('mentioning/Listing/send_otp'); ?>"
            });
        }

    });

        $('#resend_otp').on('click',function(){
            $.ajax({
                type:"POST",
                url:"<?= base_url('mentioning/Listing/resend_otp'); ?>"
            });
        });
        $('#save_mentioning').on('click', function () {
            var listing_option = $("input[name='listing_option']:checked").val();
            var listing_date = $("#listing_date").val();
            var listing_date_range = $("#listing_date_range").val();
            if(listing_option == ''){
                alert("Please Choose Date Fixed Date or Date Range.");
                $("input[name='listing_option']").focus();
                $("input[name='listing_option']").css('border-color','red');
                flag = false;
                return false;
            }else if(listing_option ==1){
                if(listing_date =='') {
                    alert("Please Choose Date Fixed");
                    $("#listing_date").focus();
                    $("#listing_date").css('border-color', 'red');
                    flag = false;
                    return false;
                }
            }else if(listing_option ==2){
                if(listing_date_range =='') {
                    alert("Please Choose Date Date Range");
                    $("#listing_date_range").focus();
                    $("#listing_date_range").css('border-color', 'red');
                    flag = false;
                    return false;
                }
            }
            var form_data = $('#listing_details').serialize();

            /*Changes done on 17 September 2020*/
            var file_post = $('#listing_details')[0];
            var file_data = new FormData(file_post);
            file_data.append('',form_data);
            /*end of changes*/

            $('#modal_loader').show();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('mentioning/Listing/add_details'); ?>",
                //data: form_data,
                data: file_data,
         /*Change on 17 September 2020*/

                enctype: 'multipart/form-data',
                contentType: false,
                processData: false,
        /*End of Change*/
                success: function (data) {
                    $('#modal_loader').hide();
                    var resArr = data.split('@@@');
                    if (resArr[0] == 1) {
                        $('#msg').show();
                        $('#synopsis').attr('disabled', 'disabled');
                        $('input[name=listing_option]').attr("disabled",true);
                        $('input[name=request_type]').attr("disabled",true);
                        $('input[name=engaged]').attr("disabled",true);
                        $('#listing_date').attr('disabled', 'disabled');
                        $('#listing_date_range').attr('disabled', 'disabled');
                        $('#pdfDocFile').attr('disabled', 'disabled');

                        $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                       /*   setTimeout(function(){
                            location.reload();
                        });*/
                        $('#save_mentioning').attr("disabled","disabled");
                      /*  scrolled=scrolled-300;

                        $(".panel-default").animate({
                            scrollTop:  scrolled
                        });*/
                    }
                    if (resArr[0] == 2) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        /*setTimeout(function () {
                            location.reload();
                        });*/
                    }
                    if (resArr[0] == 4) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    }
                    if (resArr[0] == 3) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
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

    $("#engaged").on('click',function(){
    if($("#engaged").is(":checked")){
        $("#engaged").val(1);
        $("#arguing_counsel_div").show();
    }
    else{
        $("#arguing_counsel_div").hide();
        $("#engaged").val('');
        $("#council_name").val('');
        $("#council_email").val('');
        $("#council_mob").val('');
    }


});
    $(".request_type").change(function(){
        var request_type = $("input[name='request_type']:checked").val();
        var listing_option = $("input[name='listing_option']:checked").val();
        //alert('listing_option='+listing_option);
        if (request_type=='M') {
            $('#request_type_upload').show();
            $('#daterangeArea').show();
            if (listing_option==2) {
                $('#daterange').show();
                $('#datesingle').hide();
            }else{
                $('#daterange').hide();
                $('#datesingle').show();
                $('.listing_option_s').prop('checked', true);
            }
        }else {
            //alert(request_type);
            $('#listing_date').val('');
            $('#daterange').hide();
            $('#datesingle').show();
            $('#daterangeArea').hide();
            $('#request_type_upload').hide();
            $('.listing_option_s').prop('checked', true);
        }
    });


</script>
<?php if (!empty($listing->request_type) && $listing->request_type !=null && $listing->request_type=='O'){?>
 <script>
            $('#daterange').hide();
            $('#datesingle').show();
            $('#daterangeArea').hide();
            $('#request_type_upload').hide();
            $('.listing_option_s').prop('checked', true);
 </script>

  <?php }?>