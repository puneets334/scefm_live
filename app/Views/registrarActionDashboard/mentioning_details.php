<div class="x_panel">
    <div class="x_title"> <h3><?= htmlentities($tabs_heading, ENT_QUOTES) ?>
            <!--<span style="float:right;"> <a class="btn btn-info" type="button" onclick="window.history.back()" /> Back</a></span>-->
        </h3> </div>
    <div class="x_content">
        <div class="table-wrapper-scroll-y my-custom-scrollbar ">
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" style="color: black">
                <thead>
                <tr class="success input-sm" role="row" >
                    <th width="5%">Sl. No.</th>
                    <th width="15%">E-Filing Details</th>
                    <th width="20%">Case Details</th>
                    <th width="10%">Advocate Details</th>
                    <th width="15">Mentioning Details</th>
                    <th width="25%">Synopsis of Urgency</th>
                    <th width="15%">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php

                $i=1;
                //print_r($tableData);
                foreach($tableData as $data){ ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><?=$data->efiling_number?><br/>
                            <?=date('d-m-Y h:is A',strtotime($data->mentioned_on))?><br/>
                            <?=$data->first_name?></td>
                        <td><?=$data->diary_no?>/<?=$data->diary_year?>
                            <br/><?=$data->reg_no_display?>
                            <br/><?=$data->cause_title?>
                            <br/>
                            <span style="color: red;">
                            <?php
                            $j = array_search($data->diaryid, array_column($coramData, 'diary_no'));
                            $element = ($j !== false ? $coramData[$j] : null);
                            //print_r($element);
                            if(!empty($element->before_coram)){
                                echo "Special Before Coram:".$element->before_coram."<br/>";
                            }
                            if(!empty($element->not_before_coram)){
                                echo "Special Not Before Judges:".$element->not_before_coram."<br/>";
                            }
                            if(!empty($element->coram)){
                                echo "Coram:".$element->coram."<br/>";
                            }
                            ?></span>
                        </td>
                        <td><?=$data->arguing_counsel_name?>
                            <br/><?=$data->arguing_consel_mobile_no?>
                            <br/><?=$data->arguing_counsel_email?></td>
                        <td><?=$data->is_for_fixed_date=="t"?'Requested for Date':'Requested for Date Range'?>

                            <br/><?=$data->is_for_fixed_date=="t" && !empty($data->requested_listing_date)?date("d-m-Y", strtotime($data->requested_listing_date)):""?>
                            <?php
                            if($data->is_for_fixed_date!="t" && !empty($data->requested_listing_date_range)){
                                $dates=converDateRangeTodmY($data->requested_listing_date_range);
                                echo $dates[0].' to '.$dates[1];
                            }
                            ?>
                            <?=!empty($data->jname)?"<br/>Before: ".$data->jname:""?>
                        </td>
                        <td><div  id="showmore_<?=$data->id?>" class="showmore"><?=$data->synopsis_of_emergency?></div>
                            <a id="more" onclick='javascript:expandDiv("showmore_<?=$data->id?>");'>Read more </a>
                        </td>
                        <td>
                            <?php
                            //echo "status is: ".$data->approval_status;
                            if($this->session->userdata['login']['ref_m_usertype_id'] != USER_REGISTRAR_ACTION && (empty($data->approval_status) || trim($data->approval_status)=="")){
                                echo (empty($data->approval_status) or trim($data->approval_status)=="")?'Pending':'';
                            }
                            elseif (empty($data->approval_status) || trim($data->approval_status)==""){ ?>
                                <div id="divAction_<?=$data->id?>">
                                    <div class="input-group col-sm-12">
                                        <select id="ddlActionType_<?=$data->id?>" name="ddlActionType" onchange="javascript:showHideApprovalDate(this,<?=$data->id?>);">
                                            <option value="">Select Action</option>
                                            <option value="a">Approve</option>
                                            <option value="w">Approval Awaited</option>
                                            <option value="r">Reject</option>
                                        </select>
                                    </div>

                                    <div class="input-group date" id="divApprovedDate_<?=$data->id?>" style="display: none">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                        <input class="datepicker" type="text" class="form-control" id="approvedDate_<?=$data->id?>"
                                               value="<?=!empty($data->requested_listing_date)?date("d-m-Y", strtotime(htmlentities($data->requested_listing_date, ENT_QUOTES))):''?>">
                                    </div>
                                    <div class="input-group" id="divApprovalAwaited_<?=$data->id?>" style="display: none">
                                        <div class="input-group-addo">
                                            <span class="">Remarks: </span>
                                        </div>
                                        <input type="text" class="form-control" id="approvalAwaited_<?=$data->id?>"
                                               value="">
                                    </div>

                                    <input type="button" class="btn btn-success right" id="" name="" onclick="javascript:saveAction(<?=$data->id?>);" value="Save" style="float:right;">
                                </div>
                            <?php   }
                            elseif($data->approval_status=='a'){
                                echo "Approved for dated: ".date("d-m-Y", strtotime($data->approved_for_date));
                            }
                            elseif($data->approval_status=='w'){
                                echo "Approval awaited";
                            }
                            elseif($data->approval_status=='r'){
                                echo "Rejected";
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $('.datepicker').datepicker({
        dateFormat: 'dd-mm-yy',
        todayHighlight: true,
        autoclose:true,
        minDate: 0
    });
</script>