<?php
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
        <div class="col-md-12 col-sm-12 col-xs-12">             
            <!-- <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%"> -->
            <table id="datatable-responsive" class="table table-striped custom-table first-th-left" cellspacing="0" width="100%">            
                <thead>
                    <tr class="success">
                        <th>#</th>
                        <th>Type</th>
                        <?php if (@$respondent_details[0]['party_type'] == 'I') { ?>
                            <th>Respondent Name</th>
                            <th>Age / D.O.B</th>
                        <?php } if (@$respondent_details[0]['party_type'] == 'D1' || @$respondent_details[0]['party_type'] == 'D2') { ?>
                            <th>Respondent Name</th>
                        <?php } if (@$respondent_details[0]['party_type'] == 'D3') { ?>
                            <th>Respondent Name</th>
                        <?php } ?>
                        <th>Contact</th>
                        <th>Address</th>
                        <?php if(@$hidepencilbtn!='true'){ ?>
                            <th class="efiling_search">Action</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    if(is_array($respondent_details)) {
                        foreach ($respondent_details as $exp) {
                            $relation = ' ' . $exp['relation'] . '/O ';
                            $dob = isset($exp['party_dob']) && !empty($exp['party_dob'] && $exp['party_dob']!='--') ? date('d-m-Y', strtotime($exp['party_dob'])) : '';
                            $party_address = strtoupper($exp['address'] . ', ' . $exp['city'] . ', ' . $exp['addr_dist_name'] . ', ' . $exp['addr_state_name']);
                            if (isset($exp['pincode']) && !empty($exp['pincode'])) {
                                $party_address .= ' - ' . $exp['pincode'];
                            }
                            if ($exp['p_r_type'] == 'P') {
                                $e_type = 'Respondent';
                            } elseif ($exp['p_r_type'] == 'R') {
                                $e_type = 'Respondent';
                            }
                            ?>                    
                            <tr>
                                <td data-key="#"><?php echo_data($i++); ?></td>
                                <td data-key="Type"><?php echo_data(strtoupper($e_type)); ?></td>
                                <?php if (@$respondent_details[0]['party_type'] == 'I') { ?>
                                    <td data-key="Respondent Name"><?php echo_data(strtoupper($exp['party_name'])) ?><br/><?php echo_data($relation)?> <br/><?php echo_data(strtoupper($exp['relative_name'])); ?></td>
                                    <td data-key="Age / D.O.B"><?php echo_data($exp['party_age'] . ' Yrs (' . $dob . ' )'); ?></td>
                                <?php } elseif (@$respondent_details[0]['party_type'] == 'D1' || @$respondent_details[0]['party_type'] == 'D2') { ?>
                                    <td data-key="Respondent Name">
                                        <?php echo_data($exp['org_post_not_in_list']=='t'?$exp['org_post_name'].', ':strtoupper($exp['authdesc'].', ')) . echo_data(strtoupper($exp['org_dept_not_in_list']=='t'?$exp['org_dept_name']:$exp['deptname'])); ?>
                                        <br/>
                                        <span style="color: red;"><?php echo_data($exp['party_type']=='D1'?'State Department':'Central Department');?></span>
                                    </td>
                                <?php } elseif (@$respondent_details[0]['party_type'] == 'D3') { ?>
                                    <td data-key="Respondent Name">
                                        <?php echo_data($exp['org_post_not_in_list']=='t'?$exp['org_post_name'].', ':strtoupper($exp['authdesc'].', ')) . echo_data(strtoupper($exp['org_dept_not_in_list']=='t'?$exp['org_dept_name']:$exp['deptname']));?>
                                        <br/><span style="color: red;"><?php echo_data('Other Department');?></span>
                                    </td>
                                <?php } ?>
                                <td data-key="Contact">
                                    <?php
                                    echo_data($exp['mobile_num']);
                                    echo'<br/>';
                                    echo_data(strtoupper($exp['email_id']));
                                    ?>
                                </td>
                                <td data-key="Address"><?php echo_data($party_address); ?></td>
                                <?php if(@$hidepencilbtn!='true'){ ?>
                                    <td data-key="Action" class="efiling_search">
                                        <a href="<?php echo base_url('newcase/respondent/'); ?>">Edit</a>
                                        &nbsp;&nbsp;&nbsp;
                                        <!-- <a href="<?php // echo base_url('newcase/deleteParty/' . url_encryption($exp['id'])); ?>">Delete</a> -->
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