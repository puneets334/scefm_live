<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 26/2/21
 * Time: 11:21 AM
 */
/*print_r($def_Data);
exit();*/
?>

@extends('responsive_variant.layouts.master.uikit_scutum_2.index')
@section('title', 'Dashboard')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')


<div id="widgets-container" ng-init="widgets.recentDocuments.byOthers.ifVisible=true;" class="uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-3@l ukchild-width-1-4@xl ukmargin-medium-top uk-grid-medium ukflex-between uk-grid" uk-grid="">
    <div ng-show="widgets.recentDocuments.byOthers.ifVisible" class="uk-first-column">
        <div class="uk-card-default uk-box-shadow-xlarge uk-border-rounded documents-widget" style="border-top:0.15rem dashed #ccc;">
            <div class="uk-flex-middle uk-grid-medium uk-grid uk-grid-stack pointer" style="text-decoration:none;padding:0.6rem 1.5rem 1.2rem 1rem;" uk-grid=""  class="tablink" onclick="openAdjournment('ShowmeDificitCourtFee', this, 'uk-label-primary')" id="defaultOpen">

                <div>
                    <!--<span class="uk-label uk-label-primary sc-padding sc-padding-small-ends uk-text-bold uk-text-large">{{count($scheduled_cases);}}</span>-->
                </div>
                <div class="uk-first-column">
                    <div>
                        <span class="uk-text-bold uk-text-primary uk-text-uppercase">Deficit Court Fee - To be paid </span>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="uk-card-default uk-box-shadow-xlarge uk-border-rounded applications-widget" style="border-top:0.15rem dashed #ccc;">
            <div class="uk-flex-middle uk-grid-medium uk-grid uk-grid-stack pointer" style="text-decoration:none;padding:0.6rem 1.5rem 1.2rem 1rem;" uk-grid="" onclick="openAdjournment('ShowMeAlreadyPaidCourtFee', this, 'uk-label-warning')">
                <div>
                    <!--<span class="uk-label uk-label-warning sc-padding sc-padding-small-ends uk-text-bold uk-text-large">{{count($existing_requests);}}</span>-->
                </div>
                <div class="uk-first-column">
                    <span class="uk-text-bold uk-text-warning uk-text-uppercase">Deficit Court Fee - Already paid</span>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-response" id="msg" role="alert" data-auto-dismiss="5000">
    <?php
    if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) { ?>

        <div class="alert alert-success" onclick="msg_hide()" id="hide_msg_div"><?= $_SESSION['MSG'];?></div>
    <?php } unset($_SESSION['MSG']);
    ?>
</div>
<!--<div class="form-group">
    <div class="col-sm-12 ">
        <?php
/*        if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {

            //echo $_SESSION['MSG']; */?>
            <div class="alert alert-success" ><?/*= $_SESSION['MSG'];*/?></div>
        <?php /*} unset($_SESSION['MSG']);
        */?>
    </div>
</div>-->
<?php echo $this->session->flashdata('msg'); ?>



<!--//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-->
<div class="uk-background-default uk-border-rounded uk-grid-medium" uk-grid>
    <div id="pymt_dificit"></div>
        <div class="uk-width-expand uk-margin-medium-top uk-overflow-auto tabcontent" id="ShowmeDificitCourtFee">
            <h4 class="uk-heading-bullet uk-text-bold">Deficit Court Fee - To be paid </h4>
            <table class="uk-table uktable-justify uktable-striped uk-table-hover uk-table-divider" id="efiled-cases-table">
                <!--<table class="display" style="width:100%" id="efiled-cases-table">-->
                <thead>
                <tr class="uk-text-bold">
                    <th class="uk-text-bold d-print-none">#</th>
                    <!--<th class="uk-text-bold">eFiling No.</th>-->
                    <th class="uk-text-bold">Diary No./ Diary Year</th>
                    <!--<th class="uk-text-bold">Registration No.</th>-->
                    <th class="uk-text-bold">Cause Tittle</th>
                   <!-- <th class="uk-text-bold">Res Name</th>-->
                    <th class="uk-text-bold">Remark</th>
                    <th class="uk-text-bold">Save Date</th>
                    <th class="uk-text-bold d-print-none">Amount</th>
                    <th class="uk-text-bold d-print-none">Action</th>
                </tr>
                </thead>
                <!--Start-->
                <tbody>
                <?php


                $srno=1;
                $sr=1;
                $srtr='';
                foreach ($def_Data as $val_deficit1){
                   /* print_r($val_deficit1);
                    exit();*/
                foreach ($val_deficit1 as $val_deficit){
                    $diaryNo_sep=substr($val_deficit['diary_no'],0,-4);
                    $diaryYr_sep=substr($val_deficit['diary_no'],-4);
                    $sr_td=1;
                   ?>
                    <tr id="<?php echo 'row'.'_'.$sr;?>">
                        <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><?=$srno++ ;?></td>
                        <!--<td id="<?php /*echo 'row'.'_'.$sr.$sr_td++;*/?>"><?/*= $val_deficit['efiling_no'] ;*/?></td>-->
                        <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><?= $diaryNo_sep . ' / ' . $diaryYr_sep ;?></td>
                        <!--<td id="<?php /*echo 'row'.'_'.$sr.$sr_td++;*/?>"><?/*= $val_deficit['reg_no_display'] ;*/?></td>-->
                        <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><?= $val_deficit['pet_name'] . '  VS  ' . $val_deficit['res_name'];?></td>
                       <!-- <td id="<?php /*echo 'row'.'_'.$sr.$sr_td++;*/?>"><?/*= $val_deficit['res_name'] ;*/?></td>-->
                        <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><?= $val_deficit['remark'] ;?></td>
                        <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><?= $val_deficit['save_dt'] ;?></td>
                        <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><input type="text" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" value="" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Enter Amount" required/></td>
                        <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><button class="btn btn-primary action" id="btnAmt">PAY</button></td>


                    </tr>

                <?php
                    $sr++;
                    $sr_td++;
                    if($sr_td==11){
                        $sr_td=0;
                    }

                } }?>

                </tbody>
                <tfoot>
                <tr class="uk-text-bold">
                    <th class="uk-text-bold d-print-none">#</th>
                    <!--<th class="uk-text-bold">eFiling No.</th>-->
                    <th class="uk-text-bold">Diary No./Diary Year</th>
                    <!--<th class="uk-text-bold">Registration No.</th>-->
                    <th class="uk-text-bold">Pet Name</th>
                    <!--<th class="uk-text-bold">Res Name</th>-->
                    <th class="uk-text-bold">Remark</th>
                    <th class="uk-text-bold">Save Date</th>
                    <th class="uk-text-bold d-print-none">Amount</th>
                    <th class="uk-text-bold d-print-none">...</th>
                </tr>
                </tfoot>
                <!--END-->
            </table>
        </div>
        <!------------Table--------------------->
        <div class="uk-width-expand uk-margin-medium-top uk-overflow-auto tabcontent" id="ShowMeAlreadyPaidCourtFee">
            <h4 class="uk-heading-bullet uk-text-bold">Deficit Court Fee - Already paid</h4>
            <table class="uk-table uktable-justify uktable-striped uk-table-hover uk-table-divider" id="efiled-cases-table1">
                <!--<table class="display" style="width:100%" id="efiled-cases-table">-->
                <thead>
                <tr class="uk-text-bold">
                    <th class="uk-text-bold d-print-none">#</th>
                    <th class="uk-text-bold">eFiling No.</th>
                    <th class="uk-text-bold">Diary No./Diary Year</th>
                    <!--<th class="uk-text-bold">Registration No.</th>-->
                    <th class="uk-text-bold">Paid On.</th>
                    <th class="uk-text-bold">Received Amount</th>
                    <!--<th class="uk-text-bold">Remark</th>
                    <th class="uk-text-bold">Save Date</th>
                    <th class="uk-text-bold d-print-none">Amount</th>-->
                    <th class="uk-text-bold d-print-none">Payment Certificate</th>
                </tr>
                </thead>
                <!--Start-->
                <tbody>
                <?php
                $srno=1;
                $sr=1;
                $srtr='';
                foreach ($AlreadyPaid_CourtFee as $val_already_paid){
                   // print_r($AlreadyPaid_CourtFee);
                    $sr_td=1;
                    //print_r($val_deficit['diary_no']);?>
                    <tr id="<?php echo 'row'.'_'.$sr;?>">
                        <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><?=$srno++ ;?></td>
                        <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><?= $val_already_paid['efiling_no'] ;?></td>
                        <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><?= $val_already_paid['diary_no']. ' / ' .$val_already_paid['diary_year'] ;?></td>
                        <!--<td id="<?php /*echo 'row'.'_'.$sr.$sr_td++;*/?>"><?/*= $val_already_paid['registration_id'] ;*/?></td>-->
                        <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><?= $val_already_paid['created_on'] ;?></td>
                        <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"> &#8377; <?= $val_already_paid['received_amt'] ;?></td>
                        <td>

                        <?php
                        if (!empty($val_already_paid['shcilpmtref'])) {
                            echo '<a target="_blank" href="' . base_url('shcilPayment/ViewPaymentChallan/' . url_encryption(htmlentities($val_already_paid['shcilpmtref'].'$$'.$val_already_paid['received_amt'], ENT_QUOTES))) . '"> Success <img src="' . base_url('assets/images/pdf.png') . '" class="shimg">';
                        }


                        ?>
                        </td>


                    </tr>

                    <?php
                    $sr++;
                    $sr_td++;
                    if($sr_td==11){
                        $sr_td=0;
                    }

                }?>

                </tbody>
                <tfoot>
                <tr class="uk-text-bold">
                    <th class="uk-text-bold d-print-none">#</th>
                    <th class="uk-text-bold">eFiling No.</th>
                    <th class="uk-text-bold">Diary No./Diary Year</th>
                    <!--<th class="uk-text-bold">Registration No.</th>-->
                    <th class="uk-text-bold">Paid On.</th>
                    <th class="uk-text-bold">Received Amount</th>
                    <!--<th class="uk-text-bold">Remark</th>
                    <th class="uk-text-bold">Save Date</th>
                    <th class="uk-text-bold d-print-none">Amount</th>-->
                    <th class="uk-text-bold d-print-none">...</th>
                </tr>
                </tfoot>
                <!--END-->
            </table>
        </div>

    <!--</div>--><!--END OF DIV class="row"-->
</div>


<!-- Case Status modal-start-->
<link rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/templates/uikit_scutum_2/assets/css/main.min.css" />
<link type="text/css" rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/frameworks/uikit_3-4-1/css/uikit.min.css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/templates/uikit_scutum_2/assets/css/fonts/mdi_fonts.css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/templates/uikit_scutum_2/assets/css/materialdesignicons.min.css" />

<?php
$this->load->view('case_status/case_status_view');
?>
<script src="<?= base_url() ?>assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit.min.js"></script>
<script src="<?= base_url() ?>assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit-icons.min.js"></script>
<!-- Case Status modal-end-->


<script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/jquery.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/jquery-ui.min.js"></script>
<!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery-ui.css">
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/sha256.js"></script>
<script src="<?= base_url() ?>assets/js/daterangepicker/daterangepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/daterangepicker/moment.min.js"></script>
<script src="<?= base_url() ?>assets/js/daterangepicker/daterangepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/daterangepicker/daterangepicker.css">
<script src="<?=base_url() ?>assets/js/sweetalert-2.1.2.min.js"></script>



<script>

    //$(function () {
        $(document).ready(function(){
           // $('#msg').hide();
            var userTypeId = "<?php echo $_SESSION['login']['ref_m_usertype_id'];?>";
        //ngScope = angular.element($('[ng-app="dashboardApp"]')).scope();

        $('.documents-widget,.my-documents-widget,.applications-widget,.defects-widget [uk-drop]').on('shown', function(){
            $('#content > *:not(#widgets-container)').addClass('sci-blur-medium');
        }).on('hidden', function(){
            $('#content > *:not(#widgets-container)').removeClass('sci-blur-medium');
        });
        scutum.require(['datatables','datatables-buttons'], function () {
            //    $('#myTable').DataTable();
            /*$('#soon-to-be-listed-cases-table').DataTable({
                "pageLength": 100
            });*/
            $('#efiled-cases-table').DataTable( {
                //$('#efiled-cases-table').DataTable( {
                "bSort" : false,
                initComplete: function () {
                    this.api().columns().every( function (indexCol,thisCol) {
                        var columnIndex= [];
                        if(userTypeId && userTypeId == 19){
                            columnIndex[0] = 4;
                        }
                        else{
                            columnIndex[0] = 1;
                            columnIndex[1] = 3;
                        }
                        //console.log(this.context[0].aoColumns[this.selector.cols].sTitle);

                        if($.inArray( this.selector.cols, columnIndex )!== -1){
                            var column = this;
                            var select = $('<select class="uk-select"><option value="">'+this.context[0].aoColumns[this.selector.cols].sTitle+'?</option></select>')
                                .appendTo( $(column.header()).empty() )
                                .on( 'change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search( val ? '^'+val+'$' : '', true, false )
                                        .draw();
                                } );
                            column.data().unique().sort().each( function ( d, j ) {
                                select.append( '<option value="'+d+'">'+d+'</option>' )
                            } );
                        }
                    } );
                }
            } );
            //XXXXXXXXXXXXXXXXXXXXXX
            $('#efiled-cases-table1').DataTable( {
                //$('#efiled-cases-table').DataTable( {
                "bSort" : false,
                initComplete: function () {
                    this.api().columns().every( function (indexCol,thisCol) {
                        var columnIndex= [];
                        if(userTypeId && userTypeId == 19){
                            columnIndex[0] = 4;
                        }
                        else{
                            columnIndex[0] = 1;
                            columnIndex[1] = 3;
                        }
                        //console.log(this.context[0].aoColumns[this.selector.cols].sTitle);

                        if($.inArray( this.selector.cols, columnIndex )!== -1){
                            var column = this;
                            var select = $('<select class="uk-select"><option value="">'+this.context[0].aoColumns[this.selector.cols].sTitle+'?</option></select>')
                                .appendTo( $(column.header()).empty() )
                                .on( 'change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search( val ? '^'+val+'$' : '', true, false )
                                        .draw();
                                } );
                            column.data().unique().sort().each( function ( d, j ) {
                                select.append( '<option value="'+d+'">'+d+'</option>' )
                            } );
                        }
                    } );
                }
            } );
            //XXXXXXXXXXXXXXXXCXXXXXX
        });
    });//END OF CONDITION ready(function()..


    $(document).on('click',".action",function () {

        //alert("jai sri ram");
        var saveID = $(this).attr('id');
        //alert(saveID);
        var trid = $(this).closest('tr').attr('id');

        //alert(trid);

        var tdid1=trid + 1;
        var tdid2=trid + 2;
        var tdid3=trid + 3;
        var tdid4=trid + 4;
        var tdid5=trid + 5;
        var tdid6=trid + 6;
        var tdid7=trid + 7;
        var tdid8=trid + 8;
        var tdid9=trid + 9;
        var tdid10=trid + 10;
        var tdid11=trid + 11;

        var amt_pay=$('#'+tdid7).val();
        var DiaryNo=$('#'+tdid2).text();
        /*alert(DiaryNo);
        return;*/
        var id=$('#'+tdid1).text();
        /*var pet_name=$('#'+tdid3).text();
        var Res_name=$('#'+tdid4).text();*/
        var cause_title=$('#'+tdid3).text();
        //var Res_name=$('#'+tdid4).text();


        if (amt_pay=='') {
            $('#'+tdid6).append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong> Enter Amount Required* </strong></div>');
            $('#'+tdid7).focus();
            return false;
        }
        else
        {
            $.ajax({

                url: "<?php echo base_url('deficitCourtFee/DefaultController/record_data_deficit_insrt'); ?>",
                cache: false,
                async: true,
                data: {
                    id: id,
                    amt_pay:amt_pay,
                    DiaryNo:DiaryNo

                },
                /*beforeSend:function(){
                    $('#result').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },*/
                type: 'POST',
                success: function (resultData) {
                    /*alert(resultData);
                    console.log(resultData);
                    return;*/

                    /*$('#pymt_dificit').html(resultData);

                    $('#'+tdid11).hide();*/
                    if(resultData && resultData!=1){
                        //alert("hulalalal");

                        var rdata = JSON.parse(resultData);
                        /* var rcvd_amt = rdata[0]['received_amt'];
                         var paid_on_dt=rdata[0].created_on;
                         alert(rcvd_amt + '/' + paid_on_dt);
                         return;*/
                        //var adata = rdata['data'];
                        var showdata ='';
                        var tot_rec=0;
                        showdata = showdata +"<table style='margin-left: 100px'><thead><tr><th>"+'Paid On' +"</th>" ;
                        showdata = showdata +"<th>"+'Paid Amount' +"</th></tr>" ;

                        for (var key in rdata) {
                            tot_rec= tot_rec +1;
                            var rowdata = rdata[key];

                            if(rowdata['id']==1){
                                showdata = showdata +"<tr class='selected'";
                            } else{
                                showdata = showdata +"<tr class=''";
                            }
                            var mid = "s"+rowdata['id'];
                            showdata = showdata +" id='"+mid+"'>";

                            showdata = showdata +"<td>"+moment(rowdata['created_on']).format('DD-MM-YYYY hh:mm A')+"</td>";
                            showdata = showdata +"<td>"+rowdata['received_amt']+"</td></tr>";

                        }
                        showdata = showdata +"</thead></table>";
                        //$('#tablebody55').html(showdata);

                        var htmlContent = document.createElement("div");
                        htmlContent.innerHTML=showdata;
                       // alert("Rounak Mishra");

                        swal({
                            title: 'Aready Paid Info?',
                            text: "You won't be able to PAY this!" ,
                            icon: 'warning',
                            content: htmlContent,

                            html: true,
                            buttons: {
                                cancel: {
                                    text: "Cancel!",
                                    value: "cancel",
                                    visible: true,
                                },
                                catch: {
                                    text: "Yes, Pay it!",
                                    value: "pay",
                                },

                            },
                        }).then((value)=>{
                            switch(value) {

                                case "pay":

                                yup_paid_deficit_court_fee(id,amt_pay,DiaryNo,cause_title);

                                    break;

                                case "cancel":
                                    //alert("rounak mishra");
                                return;

                                    break;
                            }//End of switch Case..
                        });

                    }else{
                        //yup_paid_deficit_court_fee(id,amt_pay,DiaryNo,pet_name,Res_name);
                        yup_paid_deficit_court_fee(id,amt_pay,DiaryNo,cause_title);
                    }

                }//END OF SUCCESS Function()..
            });
        }//END OF ELSE CONDITION..
    });//END OF FUNCTION ACTION BTN SAVE..


    function yup_paid_deficit_court_fee($id,$amt_pay,$DiaryNo,$cause_title){ //$pet_name,$Res_name

       /* alert($id + $amt_pay + $DiaryNo + $cause_title);
        return;*/

        var id=$id;
        var amt_pay=$amt_pay;
        var DiaryNo=$DiaryNo;
        /*var PetName=$pet_name;
        var ResName=$Res_name;*/
        var cause_tittle=$cause_title;

        $.ajax({

            url: "<?php echo base_url('deficitCourtFee/DefaultController/record_data_deficit_insrt_paid'); ?>",
            cache: false,
            async: true,
            data: {
                id: id,
                amt_pay:amt_pay,
                DiaryNo:DiaryNo,
                /*PetName:PetName,
                ResName:ResName*/
                cause_tittle:cause_tittle

            },
            /*beforeSend:function(){
                $('#result').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
            },*/
            type: 'POST',
            success: function (resultData) {
                /* alert(resultData);
                console.log(resultData);
                return;*/
                $('#pymt_dificit').html(resultData);


            }//END OF SUCCESS Function()..
        });

        return;
    }//End of function yup_paid_deficit_court_fee()..



    function msg_hide() {
        $('#hide_msg_div').hide();
    }
</script>

<script>
    function openAdjournment(cityName,elmnt,color) {

        //alert("Rounak Mishra");
        //return;
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        /*tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].style.backgroundColor = "";
        }*/
        document.getElementById(cityName).style.display = "block";
        //elmnt.style.backgroundColor = color;

    }
    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
</script>


<style>
    .pointer {cursor: pointer;}
    /*#ShowCases {background-color:blue;}
    #ShowMeAdjournmentRequests {background-color:orange;}
    #ShowOthersAdjournmentRequests {background-color:red;}*/

</style>


@endsection