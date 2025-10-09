<?php
$stages_array = array('', Draft_Stage, Initial_Defected_Stage,  E_REJECTED_STAGE);
$hidepencilbtn='';
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
        <div class="col-md-12 col-sm-12 col-xs-12">             
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr class="success">
                        <th>#</th>
                        <th>Party ID</th>
                        <th>Type</th>
                        <?php if (@$lr_parties_list[0]['party_type'] == 'I') { ?>
                            <th>LR Of</th>
                            <th>Legal Representative Party Name</th>
                            <th>Age / D.O.B</th>
                        <?php } if (@$lr_parties_list[0]['party_type'] == 'D1' || @$lr_parties_list[0]['party_type'] == 'D2') { ?>
                            <th>Legal Representative Name</th>
                        <?php } if (@$lr_parties_list[0]['party_type'] == 'D3') { ?>
                            <th>Legal Representative Name</th>
                        <?php } ?>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Lrs Remarks</th>
                        <?php if(@$hidepencilbtn!='true') { ?>
                            <th class="efiling_search">Action</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $pary_as = '';
                    if(is_array($lr_parties_list)) {
                        foreach ($lr_parties_list as $exp) {
                            $text = "2.4.1.3.5";
                            $partyIDGet = explode(".", $exp['party_id']); $partyID=implode(",",$partyIDGet); $part_id=explode(",", $partyID); asort($part_id);
                            if ($exp['party_type']=='I'){$party_type='Individual';}elseif ($exp['party_type']=='D1'){$party_type='State Department';}elseif ($exp['party_type']=='D2'){$party_type='Central Department';}elseif ($exp['party_type']=='D3'){$party_type='Other Organisation';}else{$party_type='';}
                            $relation = ' ' . $exp['relation'] . '/O ';
                            $dob = isset($exp['party_dob']) && !empty($exp['party_dob']) ? date('d-m-Y', strtotime($exp['party_dob'])) : '';
                            $party_address = strtoupper($exp['address'] . ', ' . $exp['city'] . ', ' . $exp['addr_dist_name'] . ', ' . $exp['addr_state_name']);
                            if (isset($exp['pincode']) && !empty($exp['pincode'])) {
                                $party_address .= ' - ' . $exp['pincode'];
                            }
                            if ($exp['p_r_type'] == 'P') {
                                $e_type = 'Petitioner';
                            } elseif ($exp['p_r_type'] == 'R') {
                                $e_type = 'Respondent';
                            }  
                            ?>                    
                            <tr>
                                <td data-key="#"><?php echo_data($i++); ?></td>
                                <td data-key="Party ID">
                                    <?php
                                    foreach($part_id as $x => $x_value) {
                                        if (!empty($x_value) && $x_value !=null) {
                                            echo $x_value.',';
                                        }
                                    }
                                    ?>
                                </td>
                                <td data-key="Type"><?php echo_data(strtoupper($e_type)); ?></td>
                                <?php if ($exp['party_type'] == 'I') { ?>
                                    <td data-key="LR Of"><?php echo_data(strtoupper($exp['lr_of_name'])); ?></td>
                                    <td data-key="Legal Representative Party Name"><?php if (!empty($party_type)){echo_data(strtoupper($party_type));echo '<br>';}
                                        if (!empty($exp['party_name']) && !empty($exp['relative_name'])){?><?php echo_data(strtoupper($exp['party_name'])) ?><br/><?php echo_data($relation)?> <br/><?php echo_data(strtoupper($exp['relative_name'])); ?><?php }?>
                                    </td>
                                    <td data-key="Age / D.O.B"><?php if (!empty($exp['party_age'])){ echo_data($exp['party_age'] . ' Yrs'); } if (!empty($dob)){ echo '(' . $dob . ' )';} ?></td>
                                <?php } elseif ($exp['party_type'] == 'D1') { ?>
                                    <td data-key="Legal Representative Name"><?php if (!empty($party_type)){echo_data(strtoupper($party_type));echo '<br>';}?> </td>
                                <?php } elseif($exp['party_type'] == 'D2') { ?>
                                    <td data-key="Legal Representative Name"><?php if (!empty($party_type)){echo_data(strtoupper($party_type));echo '<br>';}?> </td>
                                <?php } elseif ($exp['party_type'] == 'D3') { ?>
                                    <td data-key="Legal Representative Name"><?php if (!empty($party_type)){echo_data(strtoupper($party_type));echo '<br>';}?> </td>
                                <?php } ?>
                                <td data-key="Contact">
                                    <?php
                                    echo_data($exp['mobile_num']);
                                    echo'<br/>';
                                    echo_data(strtoupper($exp['email_id']));
                                    ?>
                                </td>
                                <td data-key="Address"><?php echo_data($party_address); ?></td>
                                <td data-key="Lrs Remarks"><?php echo_data(strtoupper($exp['lrs_remark'])); ?></td>
                                <?php
                                if($hidepencilbtn!='true'){ ?>
                                    <td data-key="Action" class="efiling_search"><a href="<?php echo base_url('newcase/lr_party/' . url_encryption($exp['id'])); ?>">Edit</a>&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url('newcase/deleteParty/' . url_encryption($exp['id'])); ?>">Delete</a></td>
                                <?php } ?>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>