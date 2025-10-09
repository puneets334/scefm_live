<style>.pointer {cursor: pointer;}</style>
<div class="right_col" role="main">
    <div id="page-wrapper">
        <?php echo $this->session->flashdata('msg'); ?>
        <div id="msg">
            <?php
            if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) { echo $_SESSION['MSG']; } unset($_SESSION['MSG']);
            ?>
        </div>
        <!--start dashboard-->
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="x_panel tile fixed_height_320 overflow_hidden">
                <div class="x_title">
                    <h2>Registrar Action Dashboard</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content table-responsive">
                    <table  style="width:100%">
                        <tbody>
                        <tr>
                            <th>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><p>Stages</p></div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><p class="" >Total</p></div>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5"><p class="" >Mentioning with Urgency letter /  Oral Mentioning Requests</p></div>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <table class="tile_info1">
                                    <tbody>
                                    <?php if ($count_efiling_data[0]->pending_requests == 0) { ?>
                                    <tr>
                                    <?php } else { ?>
                                    <tr>
                                        <?php } ?>
                                        <td><p class="pointer" onclick="javascript:getMentioningDetails('P',null);"><i class = "fa fa-square green"></i>New Mentioning </a></p></td>
                                        <td><p class="pointer"  onclick="javascript:getMentioningDetails('P',null);""> <?php echo htmlentities($count_efiling_data[0]->pending_requests, ENT_QUOTES); ?></p> </td>
                                        <td style="text-align: center;"><a class="pointer"  onclick="javascript:getMentioningDetails('P','M');"><?php echo $count_efiling_data[0]->pending_requests_urgency_letter ;?></a>&nbsp;&nbsp;&nbsp;   /&nbsp;&nbsp;&nbsp;  <a class="pointer" onclick="javascript:getMentioningDetails('P','O');"> <?php echo $count_efiling_data[0]->pending_requests_oral ;?></a>
                                        </td>
                                    </tr>

                                    <?php if ($count_efiling_data[0]->approval_awaited_requests == 0) { ?>
                                    <tr>
                                    <?php } else { ?>
                                    <tr>
                                        <?php } ?>
                                        <td><p class="pointer" onclick="javascript:getMentioningDetails('w',null);"><i class="fa fa-square red"></i>Approval Awaited </p></td>
                                        <td><p class="pointer" onclick="javascript:getMentioningDetails('w',null);"><?php echo htmlentities($count_efiling_data[0]->approval_awaited_requests, ENT_QUOTES); ?></p></td>
                                        <td style="text-align: center;"><a class="pointer"  onclick="javascript:getMentioningDetails('w','M');"><?php echo $count_efiling_data[0]->approval_awaited_requests_urgency_letter ;?></a>&nbsp;&nbsp;&nbsp;   /&nbsp;&nbsp;&nbsp;  <a class="pointer" onclick="javascript:getMentioningDetails('w','O');"> <?php echo $count_efiling_data[0]->approval_awaited_requests_urgency_oral ;?></a>
                                    </tr>

                                    <?php if ($count_efiling_data[0]->approved_requests == 0) { ?>
                                    <tr>
                                    <?php } else { ?>
                                    <tr>
                                        <?php } ?>
                                        <td><p class="pointer" onclick="javascript:getMentioningDetails('a',null);"><i class="fa fa-square dark_blue"></i>Approved</p>  </td>
                                        <td><p class="pointer" onclick="javascript:getMentioningDetails('a',null);"><?php echo htmlentities($count_efiling_data[0]->approved_requests, ENT_QUOTES); ?></p></td>
                                        <td style="text-align: center;"><a class="pointer"  onclick="javascript:getMentioningDetails('a','M');"><?php echo $count_efiling_data[0]->approved_requests_urgemcy_letter ;?></a>&nbsp;&nbsp;&nbsp;   /&nbsp;&nbsp;&nbsp;  <a class="pointer" onclick="javascript:getMentioningDetails('a','O');"> <?php echo $count_efiling_data[0]->approved_requests_oral ;?></a>
                                    </tr>

                                    <?php if ($count_efiling_data[0]->rejected_requests == 0) { ?>
                                    <tr>
                                    <?php } else { ?>
                                    <tr>
                                        <?php } ?>
                                        <td><p class="pointer" onclick="javascript:getMentioningDetails('r',null);"><i class="fa fa-square purple"></i>Rejected</p></td>
                                        <td><p class="pointer" onclick="javascript:getMentioningDetails('r',null);"><?php echo htmlentities($count_efiling_data[0]->rejected_requests, ENT_QUOTES); ?></p></td>
                                        <td style="text-align: center;"><a class="pointer"  onclick="javascript:getMentioningDetails('r','M');"><?php echo $count_efiling_data[0]->rejected_requests_urgency_letter ;?></a>&nbsp;&nbsp;&nbsp;   /&nbsp;&nbsp;&nbsp;  <a class="pointer" onclick="javascript:getMentioningDetails('r','O');"> <?php echo $count_efiling_data[0]->rejected_requests_oral ;?></a>
                                    </tr>

                                    <?php if ($count_efiling_data[0]->all == 0) { ?>
                                    <tr>
                                    <?php } else { ?>
                                    <tr>
                                        <?php } ?>
                                        <?php $href = ($count_efiling_data[0]->total_transfer_to_efiling_sec == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(Transfer_to_IB_Stage, ENT_QUOTES))); ?>
                                        <td><p class="pointer" onclick="javascript:getMentioningDetails('0',null);"><i class="fa fa-square yellow"></i>All</p></td>
                                        <td><p class="pointer" onclick="javascript:getMentioningDetails('0',null);"><?php echo htmlentities($count_efiling_data[0]->all, ENT_QUOTES); ?></p></td>
                                        <td style="text-align: center;"><a class="pointer"  onclick="javascript:getMentioningDetails('0','M');"><?php echo $count_efiling_data[0]->all_urgency_letter ;?></a>&nbsp;&nbsp;&nbsp;   /&nbsp;&nbsp;&nbsp;  <a class="pointer" onclick="javascript:getMentioningDetails('0','O');"> <?php echo $count_efiling_data[0]->all_oral ;?></a>
                                    </tr>


                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="x_panel tile fixed_height_320 overflow_hidden">
                <div class="x_title">
                    <h2> Adjournment Letter </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content table-responsive">
                    <table  style="width:100%">
                        <tbody>
                        <tr>
                            <th>
                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><p>Listing Date</p></div>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5"><p class="text_position">Count</p></div>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <table class="tile_info">
                                    <tbody>
                                    <?php
                                    foreach ($date_count as $key => $value) { $listed_date=$value->listed_date_count['listed_date'];?>
                                    <tr class="pointer" onclick="javascript:getAdjournmentLetter('<?=$listed_date?>');">
                                        <td><p><i class="fa fa-square orange "></i><?=date('d-M-Y', strtotime($listed_date));?> </p></td>
                                        <td><p><?=$value->listed_date_count['listed_count'];?></p></td>
                                    </tr>
                                    <?php }?>

                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--end dashboard-->





<!--#################################-->
            <div class="col-md-12 col-sm-12 col-xs-12" id="divTableData">

            <?php
                //include("mentioning_details.php"); or include("adjournmentLetterMeList.php");
            ?>
            </div>

            <!--###################################################3-->

        </div>
    </div>
</div>
<script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>

<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery-ui.css">

<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/sha256.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/jquery.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/jquery-ui.min.js"></script>
<style>
    .yellow{
        color: #f0ad4e;
    }
    .orange{
        color:#FF7F50;
    }
    .dark_blue{
        color:#0040ff;
    }
    p:hover {
        background-color: #EDEDED !important;
    }.text_position{
        padding-left: 60%;
    }
    .showmore{
        height:100px;
        overflow:hidden;
    }
</style>
<script>
    function expandDiv(divId) {
        $('#'+divId).css({
            'height': 'auto'
        })
        return false;
    }
    function showHideApprovalDate(ddlId,idd){
        if(ddlId.value=='a'){
            $("#divApprovalAwaited_"+idd).hide();
            $("#divApprovedDate_"+idd).show(500)
        }
        else if(ddlId.value=='w'){
            $("#divApprovalAwaited_"+idd).show();
            $("#divApprovedDate_"+idd).hide(500);
        }
        else{
            $("#divApprovalAwaited_"+idd).hide();
            $("#divApprovedDate_"+idd).hide(500);
        }
    }



    function saveAction(idd){
        //alert(idd);
        var actionType=$('#ddlActionType_'+idd).val();
        var approvalDate=$('#approvedDate_'+idd).val();
        var approvalAwaitedRemarks=$('#approvalAwaited_'+idd).val();
        var actionName="";
       /* alert(actionType);
        alert(approvalDate);*/
        if(actionType==""){
            alert("Please select action.");
            $('#ddlActionType_'+idd).focus();
            return false;
        }
        else if(actionType=="a"){
            if( !approvalDate || approvalDate == ""){
                alert("Please fill proposed listing date.");
                $('#approvedDate_'+idd).focus();
                return false;
            }
            actionName="Approved for dated "+approvalDate;
        }
        else if(actionType=="w"){
            if( !approvalAwaitedRemarks || approvalAwaitedRemarks.trim() == ""){
                alert("Please fill remarks!.");
                $('#approvedDate_'+idd).focus();
                return false;
            }
            actionName="Approval awaited";
        }
        else if(actionType=="r"){
            actionName="Rejected";
        }
        $.post("<?=base_url()?>registrarActionDashboard/DefaultController/saveAction", {id: idd, actionType: actionType, approvalDate:approvalDate, approvalAwaitedRemarks: approvalAwaitedRemarks},function(result){
            if(result=="1"){
                alert("Data saved successfully!!");
                $("#divAction_"+idd).html(actionName);
            }
            else{
                alert("Error! "+result);
            }
        });
    }

    function getAdjournmentLetter(listed_date){
        $.post("<?=base_url()?>registrarActionDashboard/DefaultController/getAdjournmentLetterMe", {listed_date: listed_date},function(result){
            $("#divTableData").html(result);
            $("#datatable-responsive").dataTable();
        });
    }
    function getMentioningDetails(status="P",urgencyWith=null){
        $.post("<?=base_url()?>registrarActionDashboard/DefaultController/getMentioningDetails", {requestStatus: status,urgencyWith:urgencyWith},function(result){
            $("#divTableData").html(result);
            $("#datatable-responsive").dataTable();
        });
    }
    window.onload = function() {
       getMentioningDetails('P',null);
    };
   </script>

