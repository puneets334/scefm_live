<style>
    .select2-container {
        width: 100% !important;
    }
</style>
<?php
$hidepencilbtn='';
$stages_array = array('', Draft_Stage, Initial_Defected_Stage,  E_REJECTED_STAGE);
if(isset(getSessionData('efiling_details')['stage_id'])) {
    if (!in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
        $hidepencilbtn='true';
    } else{
        $hidepencilbtn='false';
    }
}
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div id ="act_section_list_data" class="col-md-12 col-sm-12 col-xs-12">
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr class="success">
                        <th style="width: 5%">#</th>
                        <th style="width: 45%">Act</th>
                        <th style="width: 40%">Section</th>
                        <?php if($hidepencilbtn!='true') { ?>
                            <th class="efiling_search" style="width: 10%">Action</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    if(is_array($act_sections_list)) {
                        foreach ($act_sections_list as $act_list) {
                            if(!empty($act_list)) {
                                $act_sectionR=str_replace(")","",$act_list->act_section);
                                $act_sectionF=explode('(',$act_sectionR);
                                $act_section_1 = (is_array($act_sectionF) && !empty($act_sectionF[0])) ? $act_sectionF[0] : '';
                                $act_section_2=(is_array($act_sectionF) && !empty($act_sectionF[1])) ? $act_sectionF[1] : '';
                                $act_section_3=(is_array($act_sectionF) && !empty($act_sectionF[2])) ? $act_sectionF[2] : '';
                                $act_section_4=(is_array($act_sectionF) && !empty($act_sectionF[3])) ? $act_sectionF[3] : '';
                                $act_section_all=$act_section_1.'@@@'.$act_section_2.'@@@'.$act_section_3.'@@@'.$act_section_4;
                                $del_act_id = $id= url_encryption(@$act_list->id);
                                $act_id =  url_encryption(@$act_list->act_id);
                                $actYear = !empty(@$act_list->act_year) ? ' (' . @$act_list->act_year . ')' : '';
                                ?>
                                <tr>
                                    <td data-key="#"><?php echo_data($i++); ?></td>
                                    <?php if(@$act_list->is_approved == 'Y') { ?>
                                        <td data-key="Act">
                                            <?php echo_data((@$act_list->act_name) . ' (' . @$act_list->act_year . ')'); ?>
                                            <!--<button class="btn btn-success btn-sm">Approved</button>-->
                                        </td>
                                    <?php } else{ ?>
                                        <td data-key="Act">
                                            <?php $act_name_Year=echo_data((@$act_list->act_name) . $actYear); ?>
                                            <b><?php  echo_data('-- Pending for Approval');?></b>
                                            <?php
                                            $getData_Act=$del_act_id.'@@@'.$act_id.'@@@'.@$act_list->act_name.'@@@'.$actYear.'@@@'.$act_section_all;
                                            if(!empty(getSessionData('login')) && getSessionData('login.ref_m_usertype_id') == USER_ADMIN) { ?>
                                                <button class="efiling_search btn btn-success btn-sm" data-toggle="modal" href="#act_sections_approveModal" onclick="getDataApprovalModal(<?php echo $getData_Act; ?>)">Action Approve</button>
                                            <?php } ?>
                                        </td>
                                    <?php } ?>
                                    <td data-key="Section"><?php echo_data(@$act_list->act_section); ?></td>
                                    <?php if(@$hidepencilbtn!='true') { ?>
                                        <td data-key="Action" class="efiling_search"><a onclick="delete_act_section(<?php echo @$del_act_id; ?>)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a></td>
                                    <?php } ?>
                                </tr>
                                <?php
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="act_sections_approveModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="fa fa-pencil"></span> Confirmation</h4>
                <span id="msg-Act" class="form-response-Act" role="alert" data-auto-dismiss="5000" class="text-success"></span>
            </div>
            <div class="modal-body">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <input type="hidden" name="id_approval" id="id_approval" value="">
                    <input type="hidden" name="act_id" id="act_id" value="">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12 well">
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-12 col-xs-12 input-sm"> Act <span style="color: red">*</span>:</label>
                                <div class="col-md-10 col-sm-12 col-xs-12">
                                    <select tabindex='2' name="case_acts" id="case_acts" class="form-control input-sm filter_select_dropdown">
                                        <option value="" title="Select">Select Act</option>
                                        <?php
                                        if (count(@$act_sections_list)) {
                                            foreach (@$act_sections_list as $dataRes) {
                                                ?>
                                                <option value="<?php echo_data(url_encryption(trim(@$dataRes->act_id))); ?>"><?php echo_data(strtoupper(@$dataRes->act_name . ' (' . @$dataRes->act_year . ')')); ?> </option>;
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-12 col-xs-12 input-sm"> Sections :</label>
                            <div class="col-md-2 col-sm-3 col-xs-3">
                                <input  tabindex='3' id="section_1" name="section_1" placeholder="Section" value="" class="form-control input-sm" type="text" minlength="1" maxlength="10">
                            </div>
                            <div class="col-md-2 col-sm-3 col-xs-3">
                                <input  tabindex ='4' id="section_2" name="section_2" placeholder="Sub-Section" value="" class="form-control input-sm" type="text" minlength="1" maxlength="10">
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <input  tabindex = '5' id="section_3" name="section_3" placeholder="Sub-Section" value="" class="form-control input-sm" type="text" minlength="1" maxlength="10">
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <input tabindex = '6' id="section_4" name="section_4" placeholder="Sub-Section" value="" class="form-control input-sm" type="text" minlength="1" maxlength="10">
                            </div>
                        </div>
                    </div>
                </div>
                <center> <button  class="btn btn-success" onclick="update_act_section();">Update</button><br>OR<br></center>
                 <center>
                    <input type="hidden" name="master_act_id" id="master_act_id" value="">
                    <span id="act_text_approval_act_name_Year"></span>
                    <button type="submit" class="btn btn-success" onclick="approve_act_section();">Approve</button>
                </center>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
@section('script')
<script>
    function getDataApprovalModal(actArr) {
        var act_null='';
        $('#act_text_approval_act_name_Year').val(act_null);
        $('#act_id').val(act_null);
        $('#id_approval').val(act_null);
        $('#section_1').val(act_null);
        $('#section_2').val(act_null);
        $('#section_3').val(act_null);
        $('#section_4').val(act_null);
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('newcase/Ajaxcalls_act_section/get_master_acts_list'); ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, actArr:actArr},
            success: function (data) {
                var resArr = data.split('@@@');
                if (resArr[0] == 2) {
                    $('#case_acts').html(resArr[1]);
                    $('#id_approval').val(resArr[2]);
                    $('#act_id').val(resArr[3]);
                    $('#master_act_id').val(resArr[3]);
                    $('#act_text_approval_act_name_Year').html(resArr[4] + resArr[5]+ ' -- Pending for Approval ');
                    $('#section_1').val(resArr[6]);
                    $('#section_2').val(resArr[7]);
                    $('#section_3').val(resArr[8]);
                    $('#section_4').val(resArr[9]);
                }
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
    function update_act_section() {
        var case_acts   =$('#case_acts').val();
        var act_id   =$('#act_id').val();
        var id_approval   =$('#id_approval').val();
        var section_1=$('#section_1').val();
        var section_2=$('#section_2').val();
        var section_3=$('#section_3').val();
        var section_4=$('#section_4').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('newcase/Ajaxcalls_act_section/update_act_section'); ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,id_approval:id_approval,act_id:act_id,case_acts:case_acts,section_1:section_1,section_2:section_2,section_3:section_3,section_4:section_4},
            success: function (data) {
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg-Act').show();
                    $(".form-response-Act").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    $(".form-response-Act").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    $('#msg-Act').show();
                    //alert('location reload');
                    var seconds =3;
                    setInterval(function () {
                        seconds--;
                        if (seconds == 0) {
                            location.reload();
                        }
                    }, 1000);
                } else if (resArr[0] == 3) {
                    $('#msg-Act').show();
                    $(".form-response-Act").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                }
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
    function approve_act_section() {
        var master_act_id   =$('#master_act_id').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('newcase/Ajaxcalls_act_section/approve_act_section'); ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,master_act_id:master_act_id},
            success: function (data) {
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg-Act').show();
                    $(".form-response-Act").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    $(".form-response-Act").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    $('#msg-Act').show();
                    //alert('location reload');
                    var seconds =3;
                    setInterval(function () {
                        seconds--;
                        if (seconds == 0) {
                            location.reload();
                        }
                    }, 1000);
                } else if (resArr[0] == 3) {
                    $('#msg-Act').show();
                    $(".form-response-Act").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                }
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
</script>
@endsection