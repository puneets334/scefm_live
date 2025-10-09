
<style>
    .info-box-content
    {
        width: 90%;
    }

    .info-box-icon
    {
        width:10px;
        height: 64px;
    }
    .info-box-content
    {
        margin-left: 10px;
    }
    .info-box
    {
        min-height: 50px;
    }
</style>

<div class="x_panel panel panel-default">
<div class="panel-body">

           <!-- Main content -->

                <div class="info-box bg-gray "><i class="ion ion-ios-gear-outline"></i>
                    <div class="row" >
                        <section class="content-header">
                            <h1>
                                Petitions
                                <small></small>
                            </h1>

                        </section>
<br/>
                        <div class="col-sm-2 col-sm-6 col-xs-12" >
                            <div class="info-box">

                                <span class="info-box-icon bg-green"><i class="ion ion-ios-gear-outline"></i></span>


                                <div class="info-box-content" >
                                    <span class="info-box-text" style="margin-bottom: 5px;">Filed</span>
                                    <span class="info-box-number"><a href="<?php echo base_url()?>jail_dashboard/DefaultController/get_filed_petitions/1" style="color:black;"><i class="fa fa-file-text" style="font-size:25px;color:#b8c7ce;" aria-hidden="true"></i>
                                            <?php  if(isset($total_filed_petitions) && !empty($total_filed_petitions)) {echo $total_filed_petitions;}else { echo '0'; }  ?></a></span>


                                </div>
                                <!-- /.info-box-content -->
                            </div></div>
                        <div class="col-sm-2 col-sm-6 col-xs-12" >
                            <div class="info-box">

                                <span class="info-box-icon bg-red"><i class="ion ion-ios-gear-outline"></i></span>


                                <div class="info-box-content" >
                                    <span class="info-box-text" style="margin-bottom: 5px;">Pending</span>
                                    <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_filed_petitions/2" style="color:black;"><i class="fa fa-file-text" style="font-size:25px;color:#b8c7ce;" aria-hidden="true"></i>
                                        <?php
                                        if(isset($total_pending_petitions) && !empty($total_pending_petitions))
                                        {echo $total_pending_petitions;}else { echo '0'; }  ?></a></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div></div>
                        <div class="col-sm-2 col-sm-6 col-xs-12" >
                            <div class="info-box">

                                <span class="info-box-icon bg-yellow"><i class="ion ion-ios-gear-outline"></i></span>


                                <div class="info-box-content" >
                                    <span class="info-box-text" style="margin-bottom: 5px;">Disposed</span>
                                    <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_filed_petitions/3" style="color:black;"><i class="fa fa-file-text" style="font-size:25px;color:#b8c7ce;" aria-hidden="true"></i>
                                        <?php  if(isset($total_disposed_petitions) && !empty($total_disposed_petitions))
                                        {echo $total_disposed_petitions;}else { echo '0'; }  ?></a></span>

                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>

                    </div></div>

                    <div class="info-box bg-gray " ><i class="ion ion-ios-gear-outline"></i>
                    <div class="row" >


                        <section class="content-header">
                            <h1>
                                Incomplete Petitions
                                <small></small>
                            </h1>

                        </section>

                        <br/>

                        <div class="col-sm-2 col-sm-6 col-xs-12" >
                            <div class="info-box">

                                <span class="info-box-icon bg-green"><i class="ion ion-ios-gear-outline"></i></span>


                                <div class="info-box-content" >
                                    <span class="info-box-text" style="margin-bottom: 5px;">Draft</span>
                                <?php    $href = base_url("jail_dashboard/stage_list/" . htmlentities(url_encryption(Draft_Stage, ENT_QUOTES)));?>
                                    <span class="info-box-number"><a href="<?php echo $href?>" style="color:black;">
                                            <i class="fa fa-file-text" style="font-size:25px;color:#b8c7ce;" aria-hidden="true"></i>

                                            <?php echo htmlentities($count_efiling_data[0]->total_drafts, ENT_QUOTES); ?>
                                        </a> </span>

                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>
                        <div class="col-sm-3 col-sm-6 col-xs-12" >
                            <div class="info-box">

                                <span class="info-box-icon bg-red"><i class="ion ion-ios-gear-outline"></i></span>


                                <div class="info-box-content" >
                                    <span class="info-box-text" style="margin-bottom: 5px;">Pending for Approval</span>
                                    <?php    $href = base_url("jail_dashboard/stage_list/" . htmlentities(url_encryption(Initial_Approaval_Pending_Stage, ENT_QUOTES)));?>
                                    <span class="info-box-number"><a href="<?php echo $href?>" style="color:black;">
                                            <i class="fa fa-file-text" style="font-size:25px;color:#b8c7ce;" aria-hidden="true"></i>

                                            <?php echo htmlentities($count_efiling_data[0]->total_pending_acceptance, ENT_QUOTES); ?>
                                        </a>  </span>

                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>

                    </div>
                    </div>

                <div class="info-box bg-gray " ><i class="ion ion-ios-gear-outline"></i>
                    <div class="row">


                        <section class="content-header">
                            <h1>
                                Virtual Interaction
                            </h1>
                        </section>

                        <br/>
                        <div class="col-sm-2 col-sm-6 col-xs-12" >
                            <div class="info-box">

                                <span class="info-box-icon bg-green"><i class="ion ion-ios-gear-outline"></i></span>



                                <div class="info-box-content" >
                                    <span class="info-box-text" style="margin-bottom: 5px;">Today</span>
                                    <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_vcDetails/1" style="color:black;"><i class="fa fa-file-text" style="font-size:25px;color:#b8c7ce;" aria-hidden="true"></i>
                                            <?php  if(isset($total_today_vc) && !empty($total_today_vc)) {echo $total_today_vc;}else { echo '0'; }  ?></a></span>

                                </div>
                                <!-- /.info-box-content -->
                            </div></div>
                        <div class="col-sm-2 col-sm-6 col-xs-12" >
                            <div class="info-box">

                                <span class="info-box-icon bg-green"><i class="ion ion-ios-gear-outline"></i></span>


                                <div class="info-box-content" >
                                    <span class="info-box-text" style="margin-bottom: 5px;">This week</span>
                                    <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_vcDetails/2" style="color:black;"><i class="fa fa-file-text" style="font-size:25px;color:#b8c7ce;" aria-hidden="true"></i>
                                            <?php  if(isset($total_week_vc) && !empty($total_week_vc)) {echo $total_week_vc;}else { echo '0'; }  ?></a></span>

                                </div>
                                <!-- /.info-box-content -->
                            </div></div>
                    </div>
                </div>

                <div class="info-box bg-gray" ><i class="ion ion-ios-gear-outline"></i>
                    <div class="row">

                        <section class="content-header">
                            <h1>
                                 Certification
                                <small></small>
                            </h1>

                        </section>


                        <div class="col-lg-6">
                            <section class="content-header">
                                <h1>
                                    Custody Certificate
                                    <small></small>
                                </h1>

                            </section>

                            <br/>
                            <div class="col-sm-2 col-sm-6 col-xs-12" >
                                <div class="info-box">

                                    <span class="info-box-icon bg-red"><i class="ion ion-ios-gear-outline"></i></span>


                                    <div class="info-box-content" >
                                        <span class="info-box-text" style="margin-bottom: 5px;">Pending</span>
                                        <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_certificationDetails/cc/p" style="color:black;"><i class="fa fa-file-text" style="font-size:25px;color:#b8c7ce;" aria-hidden="true"></i>
                                            <?php  if(isset($total_custody_Pen) && !empty($total_custody_Pen)) {echo $total_custody_Pen;}else { echo '0'; }  ?></a></span>

                                    </div>
                                    <!-- /.info-box-content -->
                                </div></div>

                            <div class="col-sm-2 col-sm-6 col-xs-12" >
                                <div class="info-box">

                                    <span class="info-box-icon bg-green"><i class="ion ion-ios-gear-outline"></i></span>


                                    <div class="info-box-content" >
                                        <span class="info-box-text" style="margin-bottom: 5px;">Draft</span>
                                        <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_certificationDetails/cc/d" style="color:black;"><i class="fa fa-file-text" style="font-size:25px;color:#b8c7ce;" aria-hidden="true"></i>
                                            <?php  if(isset($total_custody_Draft) && !empty($total_custody_Draft)) {echo $total_custody_Draft;}else { echo '0'; }  ?></a></span>

                                    </div>
                                    <!-- /.info-box-content -->
                                </div></div>
                            <div class="col-sm-3 col-sm-6 col-xs-12" >
                                <div class="info-box">

                                    <span class="info-box-icon bg-orange"><i class="ion ion-ios-gear-outline"></i></span>


                                    <div class="info-box-content" >
                                        <span class="info-box-text" style="margin-bottom: 5px;">Completed</span>
                                        <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_certificationDetails/cc/c" style="color:black;">
                                            <i class="fa fa-file-text" style="font-size:25px;color:#b8c7ce;" aria-hidden="true"></i>
                                            <?php  if(isset($total_custody_com) && !empty($total_custody_com)) {echo $total_custody_com;}else { echo '0'; }  ?></a></span>

                                    </div>
                                    <!-- /.info-box-content -->
                                </div></div>
                        </div>


                        <div class="col-lg-6">

                            <section class="content-header">
                                <h1>
                                    Surrender Certificate
                                    <small></small>
                                </h1>

                            </section>

                            <br/>


                            <div class="col-sm-2 col-sm-6 col-xs-12" >
                                <div class="info-box">

                                    <span class="info-box-icon bg-red"><i class="ion ion-ios-gear-outline"></i></span>


                                    <div class="info-box-content" >
                                        <span class="info-box-text" style="margin-bottom: 5px;">Pending</span>
                                        <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_certificationDetails/sc/p" style="color:black;"><i class="fa fa-file-text" style="font-size:25px;color:#b8c7ce;" aria-hidden="true"></i>
                                            <?php  if(isset($total_sur_Pen) && !empty($total_sur_Pen)) {echo $total_sur_Pen;}else { echo '0'; }  ?></a></span>

                                    </div>
                                    <!-- /.info-box-content -->
                                </div></div>

                            <div class="col-sm-2 col-sm-6 col-xs-12" >
                                <div class="info-box">

                                    <span class="info-box-icon bg-green"><i class="ion ion-ios-gear-outline"></i></span>


                                    <div class="info-box-content" >
                                        <span class="info-box-text" style="margin-bottom: 5px;">Draft</span>
                                        <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_certificationDetails/sc/d" style="color:black;"><i class="fa fa-file-text" style="font-size:25px;color:#b8c7ce;" aria-hidden="true"></i>
                                            <?php  if(isset($total_sur_Draft) && !empty($total_sur_Draft)) {echo $total_sur_Draft;}else { echo '0'; }  ?></a></span>

                                    </div>
                                    <!-- /.info-box-content -->
                                </div></div>
                            <div class="col-sm-3 col-sm-6 col-xs-12" >
                                <div class="info-box">

                                    <span class="info-box-icon bg-orange"><i class="ion ion-ios-gear-outline"></i></span>

                                    <div class="info-box-content" >
                                        <span class="info-box-text" style="margin-bottom: 5px;">Completed</span>
                                        <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_certificationDetails/sc/c" style="color:black;"><i class="fa fa-file-text" style="font-size:25px;color:#b8c7ce;" aria-hidden="true"></i>
                                                <?php  if(isset($total_sur_com) && !empty($total_sur_com)) {echo $total_sur_com;}else { echo '0'; }  ?></a></span>

                                    </div>
                                    <!-- /.info-box-content -->
                                </div></div>
                        </div>
                    </div>
                </div>

            <!-- /.content -->
        </div>
</div>

