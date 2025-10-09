<div class="panel panel-default">
    <div class="panel-body">        
        <div class="col-md-12 col-sm-12 col-xs-12">             
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr class="success">
                        <th>#</th>
                        <th>Case Details</th>
                        <th>Cause Title</th>
                        <th>Decision Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($subordinate_court_details as $exp) {
                        $case_type = '<b>Case Type </b> : ' . $exp['casetype_name'] . '<br/> ';
                        $fil_no = '<b>Filing No.</b> : ' . (int) $exp['case_num'] . ' / ' . $exp['case_year'] . '<br/> ';
                        if (!empty($exp['cnr_num'])) {
                            $case_details = '<b>CNR No.</b> : ' . cin_preview($exp['cnr_num']) . '<br/> ' . $case_type . $fil_no;
                        } else {
                            $case_details = $case_type . $fil_no;
                        }
                        ?>                    
                        <tr>
                            <td><?php echo_data($i++); ?></td>
                            <td><?php echo $case_details; ?> </td>
                            <td><?php echo echo_data(strtoupper($exp['pet_name'] . ' Vs. ' . $exp['res_name'])); ?></td>
                            <!--<td><?php /*echo echo_data($exp['impugned_order_date']); */?></td>-->
                            <td><?php echo echo_data(!empty($exp['impugned_order_date'])?date('d-m-Y',strtotime($exp['impugned_order_date'])):''); ?></td>
                            <td><a href="<?php echo base_url('jailPetition/DeleteSubordinateCourt/' . url_encryption($exp['id'])); ?>">Delete</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>