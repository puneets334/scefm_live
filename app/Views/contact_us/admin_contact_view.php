<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2> <i class="fa fa-envelope"></i> Contacts</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr class="success input-sm" role="row" >
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Mobile No.</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($result_data as $dataRes) {
                                    ?>
                                    <tr>
                                        <td width="4%"><?php echo htmlentities($i++, ENT_QUOTES); ?></td>  
                                        <td width="10%"><?php echo htmlentities($dataRes->first_name . ' ' . $dataRes->last_name, ENT_QUOTES) ?></td> 
                                        <td width="20%"><?php echo htmlentities($dataRes->moblie_number, ENT_QUOTES) ?> </td> 
                                        <td width="10%"><?php echo htmlentities($dataRes->emailid, ENT_QUOTES) ?></td>  
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
