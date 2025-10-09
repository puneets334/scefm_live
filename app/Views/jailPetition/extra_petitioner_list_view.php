 
<div class="panel panel-default">
    <div class="panel-body">        
        <div class="col-md-12 col-sm-12 col-xs-12">             
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr class="success">
                        <th>#</th>
                        <th>Type</th>
                            <th>Party Name</th>
                            <th>Age / D.O.B</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($extra_parties_list as $exp) {
                        $relation = '   ' . $exp['relation'] . '/O   ';
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
                            <td><?php echo_data($i++); ?></td>
                            <td><?php echo_data(strtoupper($e_type)); ?></td>
                                <td><?php echo_data(strtoupper($extra_parties_list[0]['party_name'])); ?></td>
                                <td><?php echo_data($exp['party_age'] . ' Yrs (' . $dob . ' )'); ?></td>
                            <td><?php
                                echo_data($exp['mobile_num']);
                                echo'<br/>';
                                echo_data(strtoupper($exp['email_id']));
                                ?></td>
                            <td><?php echo_data($party_address); ?></td>
                            <td><a href="<?php echo base_url('jailPetition/Extra_petitioner/' . url_encryption($exp['id'])); ?>">Edit</a>&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url('jailPetition/deleteParty/' . url_encryption($exp['id'])); ?>">Delete</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>