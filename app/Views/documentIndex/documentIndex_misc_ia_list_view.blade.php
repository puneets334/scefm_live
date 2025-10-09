<?php
$allowed_users = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
$user_stages = array(I_B_Approval_Pending_Stage, I_B_Defected_Stage, Document_E_Filed, IA_E_Filed);
$allowed_admins = array(USER_ADMIN);
$admin_stages = array(Transfer_to_CIS_Stage, Get_From_CIS_Stage, I_B_Approval_Pending_Admin_Stage, I_B_Defected_Stage, I_B_Defects_Cured_Stage, Document_E_Filed, IA_E_Filed);
$uris = service('uri');
?>
<div class="panel panel-default">
    <div class="panel-body">        
        <div class="col-md-12 col-sm-12 col-xs-12">             
            <table id="datatable-responsive" class="table table-striped custom-table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr class="success">
                        <th>#</th>
                        <!--<th>Document(s) Title</th>
                        <th>Pages</th>-->
                        <th>Index - Title</th>
                        <?php if ((in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users) && in_array($_SESSION['efiling_details']['stage_id'], $user_stages)) || (in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_admins) && in_array($_SESSION['efiling_details']['stage_id'], $admin_stages))) { ?>
                            <th>Doc No.</th>
                        <?php } ?>
                        <?php if ($uris->getSegment(2) != 'view') { ?>
                            <th width="10%">Action</th>
                        <?php } ?>
                        <?php if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) { ?>
                            <!--<th>Admin Verified</th>-->
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sr = 1;
                    if(!empty($efiled_docs_list)){
                        foreach ($efiled_docs_list as $doc_list) {
                            ?>
                            <tr>
                                <td data-key="#"><?= $sr++; ?></td>
                                <td data-key="Index - Title">
                                    <?php
                                    if($uris->getSegment(1)== 'efiling_search') {
                                        $encrypted_string = url_encryption($doc_list['doc_id']);
                                        echo_data($doc_list['docdesc']);
                                        ?>
                                        <!-- <a target="_blank" href="<?php /*echo base_url('documentIndex/WithoutLoginViewIndex/' . $encrypted_string ); */?>">
                                            <?php /*echo_data($doc_list['doc_title']); */?>
                                            <img src="<?/*= base_url('assets/images/pdf.png') */?>"> <br/>
                                            (<?php /*echo_data($doc_list['docdesc']); */?>)<br/>
                                            <?php /*echo_data($doc_list['doc_hashed_value']); */?>
                                        </a>-->
                                        <?php
                                    } else{
                                        if (in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users) && in_array($_SESSION['efiling_details']['stage_id'], $user_stages)) {
                                            echo_data($doc_list['docdesc']);
                                            ?>
                                            <!-- <a target="_blank" href="<?php /*echo base_url('documentIndex/view_icmis_file/' . url_encryption($doc_list['doc_id'])); */?>">
                                                <?php /*echo_data($doc_list['doc_title']); */?>
                                                <img src="<?/*= base_url('assets/images/pdf.png') */?>"> <br/>
                                                (<?php /*echo_data($doc_list['docdesc']); */?>)<br/>
                                                <?php /*echo_data($doc_list['doc_hashed_value']); */?>
                                            </a> -->
                                        <?php } elseif (in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_admins) && in_array($_SESSION['efiling_details']['stage_id'], $admin_stages)) {
                                            echo_data($doc_list['docdesc']);
                                            ?>
                                            <!--<a target="_blank" href="<?php /*echo base_url('documentIndex/view_icmis_file/' . url_encryption($doc_list['doc_id'])); */?>">
                                                <?php /*echo_data($doc_list['doc_title']); */?>
                                                <img src="<?/*= base_url('assets/images/pdf.png') */?>"> <br/>
                                                (<?php /*echo_data($doc_list['docdesc']); */?>)<br/>
                                                <?php /*echo_data($doc_list['doc_hashed_value']); */?>
                                            </a>  -->
                                            <?php
                                        } else {
                                            echo_data($doc_list['docdesc']);
                                            ?>
                                            <!--<a target="_blank" href="<?php /*echo base_url('documentIndex/viewIndexItem/' . url_encryption($doc_list['doc_id'])); */?>">
                                                <?php /*echo_data($doc_list['doc_title']); */?>
                                                <img src="<?/*= base_url('assets/images/pdf.png') */?>"> <br/>
                                                (<?php /*echo_data($doc_list['docdesc']); */?>)<br/>
                                                <?php /*echo_data($doc_list['doc_hashed_value']); */?>
                                            </a>  -->
                                            <?php
                                        }
                                    }
                                    ?>
                                </td>
                                <!-- <td><?/*= htmlentities($doc_list['page_no'], ENT_QUOTES); */?></td>-->
                                <?php if ((in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users) && in_array($_SESSION['efiling_details']['stage_id'], $user_stages)) || (in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_admins) && in_array($_SESSION['efiling_details']['stage_id'], $admin_stages))) {
                                    ?>
                                    <td data-key="Doc No."><?= echo_data($doc_list['icmis_docnum'] . ' / ' . $doc_list['icmis_docyear']); ?></td>
                                    <?php
                                }
                                if ($uris->getSegment(2) != 'view') {
                                    ?>
                                    <td data-key="Action" width="10%"> 
                                        <?php if ($doc_list['is_admin_checked']) { ?> 
                                        <a onclick = "delete_index('<?php echo_data(url_encryption(trim($doc_list['doc_id'] . '$$' . $doc_list['registration_id']))); ?>')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                            <?php } ?>
                                    </td>
                                    <?php
                                }
                                if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
                                    // $lbl_verified = $doc_list['page_no']['is_admin_checked'] == 't' ? 'Yes' : '';
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