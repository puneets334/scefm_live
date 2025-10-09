<div class="panel panel-default">
    <div class="panel-body">        
        <div class="col-md-12 col-sm-12 col-xs-12">             
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr class="success">
                        <th>#</th>
                        <th>Uploaded Documents(s)</th>                        
                        <th>Pages</th>                        
<!--                        <th>Delete</th>-->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sr = 1;
                    foreach ($uploaded_docs as $doc) {
                        ?>
                        <tr>
                            <td><?= $sr++; ?></td>                    
                            <td><a target="_blank" href="<?php echo base_url('uploadDocuments/viewDocument/' . url_encryption($doc['doc_id'])); ?>">
                                    <?php echo_data($doc['doc_title']); ?>
                                    <img src="<?= base_url('assets/images/pdf.png') ?>"> <br/>
                                    <?php echo_data($doc['doc_hashed_value']); ?></a>                                
                            </td>
                            <td><?= htmlentities($doc['page_no'], ENT_QUOTES); ?></td>
                            <?php if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN) { ?>
                                <td>
                                    <?php
                                    if ($uploaded_docs[0]->is_admin_checked == 't') {
                                        echo '<i class="fa fa-check"></i>';
                                    } else {
                                        echo '<i class="fa fa-remove"></i>';
                                    }
                                    ?>
                                </td>
                            <?php } ?>

                            <?php if ($this->uri->segment(2) != 'view' && $this->uri->segment(2) != 'affirmation') { ?>
                                <td>
                                    &nbsp;&nbsp;&nbsp;
                                    <a href="javascript:void(0)" onclick="DocDeleteAction('<?php echo htmlentities(url_encryption(trim($docdoc_id), ENT_QUOTES)); ?>')"><i class="fa fa-trash"> Delete</i> </a>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>