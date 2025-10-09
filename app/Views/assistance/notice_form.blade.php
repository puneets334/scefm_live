<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div id="msg">
                <?php echo getSessionData('MSG'); ?>
            </div> 
        </div>
        <!------------Table--------------------->
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title"><h2><i class="fa fa-file-text-o" aria-hidden="true"></i>  Notice & Forms</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr class="success input-sm" role="row" >
                                <th width="8%">#</th>
                                <th width="20%">State</th>
                                <th>Notice & Forms </th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($notice_list as $value) {
                                ?>
                                <tr>
                                    <td data-key="#" width="2%"> <?php echo htmlentities($i++, ENT_QUOTES); ?></td> 
                                    <td data-key="State"> <?php echo htmlentities($value['state_name'], ENT_QUOTES); ?></td> 
                                    <td data-key="Notice & Forms "> <?php                                      
                                        if ($value['file_name'] != '') {
                                            ?>
                                        <i class="fa fa-file-pdf-o" aria-hidden="true" style="color:red"></i>&nbsp;<a href="<?php echo base_url('news_event/news_pdf/' . url_encryption($value['id'])); ?>" target="blank"><?php echo htmlentities($value['news_title'], ENT_QUOTES); ?></a>
                                        <?php } 
                                        elseif ($value['news_link'] != '') {
                                            ?>
                                        <a href="<?php echo $value['news_link']; ?>" target="blank"><?php echo htmlentities($value['news_title'], ENT_QUOTES); ?></a>
                                        <?php } 
                                        
                                        else echo htmlentities($value['news_title'], ENT_QUOTES); ?>
                                    </td> 
                                
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!------------Table--------------------->
    </div>
</div>