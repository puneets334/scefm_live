<?php
$stages_array = array('', Draft_Stage, Initial_Defected_Stage,  E_REJECTED_STAGE);
if (!in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
    $hidepencilbtn='true';
} else{
    $hidepencilbtn='false';
}
?>
<div class="panel panel-default">
    <div class="panel-body">        
        <div class="col-md-12 col-sm-12 col-xs-12">             
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr class="success">
                        <th>#</th>
                        <th>Type</th>
                        <th style="width: 30%;">Party Name</th>
                        <?php
                        $party_type_hd='';
                        if (!empty($extra_parties_list) && array_search('I', array_column($extra_parties_list, 'party_type')) !== FALSE) { $party_type_hd = 'I';}
                        if (!empty($party_type_hd) && $party_type_hd=='I') {
                            echo '<th>Age / D.O.B</th>';
                        }
                        ?>
                        <th>Contact</th>
                        <th>Address</th>
                        <?php if($hidepencilbtn!='true') { ?>
                            <th class="efiling_search">Action</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    if(is_array($extra_parties_list)) {
                        foreach ($extra_parties_list as $exp) {
                            $is_party_type=$exp['party_type'];
                            if ($exp['party_type']=='I'){
                                $party_type='Individual';
                            } elseif ($exp['party_type']=='D1') {
                                $party_type='State Department';
                            } elseif ($exp['party_type']=='D2') {
                                $party_type='Central Department';
                            } elseif ($exp['party_type']=='D3') {
                                $party_type='Other Organisation';
                            } else{
                                $party_type='';
                            }
                            $relation = ' ' . $exp['relation'] . '/O ';
                            $dob = isset($exp['party_dob']) && !empty($exp['party_dob']) && $exp['party_dob']!='--' ? date('d-m-Y', strtotime($exp['party_dob'])) : '';
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
                                <td data-key="Type"><?php echo_data(strtoupper($e_type)); ?></td>
                                <?php if ($is_party_type == 'I') { ?>
                                    <td data-key="Party Name">
                                        <?php
                                        if (!empty($party_type)) { echo_data(strtoupper($party_type));echo '<br>'; }
                                        echo_data($exp['org_post_not_in_list']=='t'?$exp['org_post_name'].', ':strtoupper($exp['authdesc'].', ')) . echo_data(strtoupper($exp['org_dept_not_in_list']=='t'?$exp['org_dept_name']:$exp['deptname'])); ?>
                                        <?php if (!empty($exp['party_name']) && !empty($exp['relative_name'])){?><?php echo_data(strtoupper($exp['party_name'])) ?><br/><?php echo_data($relation)?> <br/><?php echo_data(strtoupper($exp['relative_name'])); ?><?php }?>
                                    </td>
                                    <td data-key="Age / D.O.B"><?php if (!empty($exp['party_name'])){ echo_data($exp['party_age'] . ' Yrs'); } if (!empty($dob)){ echo '(' . $dob . ' )';} ?></td>
                                <?php } elseif ($is_party_type == 'D1' || $is_party_type == 'D2') { ?>
                                    <td data-key="Party Name"> <?php if (!empty($party_type)){echo_data(strtoupper($party_type));echo '<br>';}if (!empty($exp['fetch_org_state_name'])){echo_data(strtoupper($exp['fetch_org_state_name'].', '));}
                                        echo_data($exp['org_post_not_in_list']=='t'?$exp['org_post_name'].', ':strtoupper($exp['authdesc'].', ')) . echo_data(strtoupper($exp['org_dept_not_in_list']=='t'?$exp['org_dept_name']:$exp['deptname'])); ?>
                                    </td>
                                    <?php if (!empty($party_type_hd) && $party_type_hd=='I'){ ?><td data-key="Age / D.O.B"></td><?php }?>
                                <?php } elseif ($is_party_type == 'D3') { ?>
                                    <td data-key="Party Name"><?php if (!empty($party_type)){echo_data(strtoupper($party_type));echo '<br>';}
                                        echo_data($exp['org_post_not_in_list']=='t'?$exp['org_post_name'].', ':strtoupper($exp['authdesc'].', ')) . echo_data(strtoupper($exp['org_dept_not_in_list']=='t'?$exp['org_dept_name']:$exp['deptname']));?>
                                        ] </td>
                                    <?php if (!empty($party_type_hd) && $party_type_hd=='I'){ ?><td data-key="Age / D.O.B"></td><?php } ?>
                                <?php } ?>
                                <td data-key="Contact">
                                    <?php
                                    echo_data($exp['mobile_num']);
                                    echo'<br/>';
                                    echo_data(strtoupper($exp['email_id']));
                                    ?>
                                </td>
                                <td data-key="Address"><?php echo_data($party_address); ?></td>
                                <?php if($hidepencilbtn!='true') { ?>
                                    <td data-key="Action" class="efiling_search">
                                        <a href="<?php echo base_url('newcase/extra_party/' . url_encryption($exp['id'])); ?>">Edit</a>
                                        &nbsp;&nbsp;&nbsp;
                                        <a href="<?php echo base_url('newcase/deleteParty/' . url_encryption($exp['id'])); ?>">Delete</a>
                                    </td>
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