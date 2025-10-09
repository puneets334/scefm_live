<div class="panel panel-default">
    <div class="panel-body">        
        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
            <thead>
                <tr class="success">
                    <th colspan="3">Keywords</th>                    
                </tr>
            </thead>
            <tbody><?php if (isset($saved_keywords_list) && !empty($saved_keywords_list)) { ?>
                    <?php for ($i = 0; $i < count($saved_keywords_list); $i++) { ?>                              
                        <tr>                        
                            <td> <?php echo_data($saved_keywords_list[$i]->keyword_description); ?> 
                                <?php if ($this->uri->segment(2) != 'view') { ?>
                                    <a onclick="delete_keywords('<?php echo_data(url_encryption($saved_keywords_list[$i]->id . '$$' . $_SESSION['efiling_details']['registration_id'] . '$$keyword_id')); ?>')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                <?php } ?>
                            </td>

                            <?php $i = $i + 1; ?>
                            <?php if (isset($saved_keywords_list[$i]->keyword_description) && !empty($saved_keywords_list[$i]->keyword_description)) { ?>
                                <td> <?php echo_data($saved_keywords_list[$i]->keyword_description); ?> 
                                    <?php if ($this->uri->segment(2) != 'view') { ?>
                                        <a onclick="delete_keywords('<?php echo_data(url_encryption($saved_keywords_list[$i]->id . '$$' . $_SESSION['efiling_details']['registration_id'] . '$$keyword_id')); ?>')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                    <?php } ?>
                                </td>
                            <?php } else { ?>
                                <td></td>
                            <?php } ?>

                            <?php $i = $i + 1; ?>
                            <?php if (isset($saved_keywords_list[$i]->keyword_description) && !empty($saved_keywords_list[$i]->keyword_description)) { ?>
                                <td> <?php echo_data($saved_keywords_list[$i]->keyword_description); ?> 
                                    <?php if ($this->uri->segment(2) != 'view') { ?>
                                        <a onclick="delete_keywords('<?php echo_data(url_encryption($saved_keywords_list[$i]->id . '$$' . $_SESSION['efiling_details']['registration_id'] . '$$keyword_id')); ?>')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                        <?php } ?>    
                                </td>
                            <?php } else { ?>
                                <td></td>
                            <?php } ?>

                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr><td colspan="3">No record found.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>