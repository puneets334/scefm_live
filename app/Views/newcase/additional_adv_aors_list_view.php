<div class="panel panel-default">
    <div class="panel-body">        
        <div class="col-md-12 col-sm-12 col-xs-12">             
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr class="success">
                        <th style="width: 5%">#</th>
                        <th>Advocate On Record (Code)</th>
                        <?php if ($this->uri->segment(2) != 'view') { ?>
                            <th style="width: 10%">Action</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($saved_aors_list as $aor) {
                        ?>                              
                        <tr>
                            <td><?php echo_data($i++); ?></td>
                            <td><?php echo_data(strtoupper($aor->title . ' ' . $aor->name . ' ( ' . $aor->aor_code . ' )')); ?></td>
                            <?php if ($this->uri->segment(2) != 'view') { ?>
                                <td><a onclick="delete_additional_adv('<?php echo_data(url_encryption($aor->id . '$$' . $aor->registration_id . '$$aor')); ?>')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>