<!-- page content -->


<div class="right_col" role="main">
    <div id="page-wrapper">
        <div class="clearfix"></div>
        <?php echo $this->session->flashdata('msg'); ?>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title" >
                        <?php 
                        if ($profile[0]->last_name == 'NULL') {
                            $last_name = '';
                        } else {
                            $last_name = ucfirst($profile[0]->last_name);
                        }
                        ?>
                        <div class="col-md-9 col-xs-6"><h2><?php echo ucfirst($profile[0]->first_name) . " " . $last_name; ?> <?php echo $this->lang->line('login_detail'); ?></h2>
                        </div> 
                        <div class="col-md-3 col-xs-6"><a class="btn btn-default btn-xs" href="<?= base_url(); ?>user_profile/profile" style="float:right;"> <?php echo $this->lang->line('bck_profile'); ?></a></div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-response" id="msg"> </div>  
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">   
                                <thead>
                                    <tr class="success input-sm" >
                                        <th>#</th>                                                        
                                        <th><?php echo $this->lang->line('ip_address'); ?> </th>
                                        <th> <?php echo $this->lang->line('user_agent'); ?> </th> 
                                        <th><?php echo $this->lang->line('last_login'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th> 

                                    </tr>
                                </thead> 
                                <tbody> 
                                    <?php
                                    $i = 1;
                                    foreach ($log_data as $log) {

                                        if ($log->block == 't') {
                                            $action = "<span class='btn btn-success btn-xs'>Unblock</span>";
                                            $status_val = 'Unblock';
                                            $display = 'pointer-events: block;';
                                        } else {
                                            $action = "<span class='btn btn-danger btn-xs'>Block</span>";
                                            $status_val = 'Block';
                                            $display = 'pointer-events: none;';
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo htmlentities($i++, ENT_QUOTES); ?> </td> 
                                            <td><?php echo htmlentities($log->ip_address, ENT_QUOTES); ?></td>                                                            
                                            <td><?php echo htmlentities($log->user_agent, ENT_QUOTES); ?></td>  
                                            <td><?php echo htmlentities(date("d-m-Y H:i:s", strtotime($log->login_time)), ENT_QUOTES); ?></td>  
                                            <td><a href="<?php echo base_url(); ?>profile/block_user/<?php echo htmlentities(url_encryption(trim($log->ip_address), ENT_QUOTES)) ?>/<?php echo htmlentities(url_encryption($log->login_id), ENT_QUOTES); ?>/<?php echo htmlentities(url_encryption($status_val), ENT_QUOTES); ?>" onclick="return confirm('Are you sure you want to do this action ?')"><?php echo $action; ?></a></td>  

                                        </tr>
                                    <?php } ?>
                                </tbody> 
                            </table>
                        </div>
                    </div>
                </div>

            </div> 



        </div>
    </div>
</div>
</div>
</div>
</div>
<!-- /page content -->
