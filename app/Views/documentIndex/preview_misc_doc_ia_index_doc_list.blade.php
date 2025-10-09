<?php
$allowed_users = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
$user_stages = array(I_B_Approval_Pending_Stage, I_B_Defected_Stage, Document_E_Filed, IA_E_Filed);
$allowed_admins = array(USER_ADMIN);
$admin_stages = array(Transfer_to_CIS_Stage, Get_From_CIS_Stage, I_B_Approval_Pending_Admin_Stage, I_B_Defected_Stage, I_B_Defects_Cured_Stage, Document_E_Filed, IA_E_Filed);
$segment = service('uri');
?>
<div class="panel panel-default">
    <div class="panel-body">        
        <div class="col-md-12 col-sm-12 col-xs-12">             
            <table id="datatable-responsive" class="table table-striped custom-table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr class="success">
                        <th>#</th>
                        <!--<th>Document(s) Title</th>-->
                        <th>Index - Title</th>
                        <!--<th>Page No.</th>-->
                        <?php if (isset($_SESSION['login']) && ((in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users) && in_array($_SESSION['efiling_details']['stage_id'], $user_stages)) || (in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_admins) && in_array($_SESSION['efiling_details']['stage_id'], $admin_stages)))) { ?>
                            <th>Doc No.</th>
                        <?php } ?>
                        <?php if (isset($_SESSION['login']) && $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) { ?>                        
                            <!--<th>Admin Verified</th>-->
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sr = 1;
                    if(isset($efiled_docs_list) && !empty($efiled_docs_list)) {
                        foreach ($efiled_docs_list as $doc_list) {
                            ?>
                            <tr>
                                <td data-key="#"><?= $sr++; ?></td>
                                <td data-key="Index - Title">
                                    <?php
                                    if($segment->getSegment(1) == 'efiling_search') {
                                        // $encrypted_string = $this->encryption->encrypt($doc_list['doc_id']);
                                        $encrypted_string = integerEncreption($doc_list['doc_id']);
                                        ?>
                                        <a target="_blank" href="<?php echo base_url('documentIndex/WithoutLoginViewIndex/' . $encrypted_string ); ?>">
                                    <?php } else{ ?>
                                        <a target="_blank" href="<?php echo base_url('documentIndex/viewIndexItem/' . url_encryption($doc_list['doc_id'])); ?>">
                                    <?php } ?>
                                    <?php echo_data($doc_list['docdesc']); ?>
                                </td>
                                <!--<td><?/*= htmlentities($doc_list['page_no'], ENT_QUOTES); */?></td>-->
                                <?php if (isset($_SESSION['login']) && ((in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users) && in_array($_SESSION['efiling_details']['stage_id'], $user_stages)) || (in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_admins) && in_array($_SESSION['efiling_details']['stage_id'], $admin_stages)))) {
                                    ?>
                                    <td data-key="Doc No."><?= echo_data($doc_list['icmis_docnum'] . ' / ' . $doc_list['icmis_docyear']); ?></td>
                                <?php } ?>
                                <?php
                                if (isset($_SESSION['login']) && $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
                                    //$lbl_verified = $doc_list['page_no']['is_admin_checked'] == 't' ? 'Yes' : '';
                                    ?>                        
                                    <!--<td>
                                        <?php /*echo $lbl_verified; */?>
                                    </td>-->
                                <?php } ?>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
            <?php if (!empty($uploaded_docs )) {  ?> 
                @include('uploadDocuments.uploaded_doc_list')
            <?php } ?>
        </div>
    </div>
</div>