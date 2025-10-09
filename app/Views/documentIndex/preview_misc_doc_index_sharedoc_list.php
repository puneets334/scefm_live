<?php
$allowed_users = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
$user_stages = array(I_B_Approval_Pending_Stage, I_B_Defected_Stage, Document_E_Filed, IA_E_Filed);

$allowed_admins = array(USER_ADMIN);
$admin_stages = array(Transfer_to_CIS_Stage, Get_From_CIS_Stage, I_B_Approval_Pending_Admin_Stage, I_B_Defected_Stage, I_B_Defects_Cured_Stage, Document_E_Filed, IA_E_Filed);
?>
<div class="panel panel-default">
    <div class="panel-body">        
        <div class="col-md-12 col-sm-12 col-xs-12">             
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr class="success">
                        <th>#</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Mail Send On.</th>
                        <th>Sent By.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sr = 1;
                    foreach ($shareEmail_details as $doc_list) {
                        ?>
                        <tr>
                            <td><?= $sr++; ?></td>                    
                            <td><?= $doc_list['email'];?></td>
                            <td><?= $doc_list['name']; ?></td>
                            <td><?= $doc_list['sent_on_mail']; ?></td>
                            <td><?= $doc_list['first_name']; ?></td>
                        </tr>

                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>