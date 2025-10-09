
<style>
    .info-box{float:left; width:100%;}
    .info-box-content
    {
        width: 90%;
    }

    .info-box-icon
    {
        width:6px;
        height: 120px;
    }
    .info-box-content
    {
        margin-left: 10px;

    }
    .info-box
    {
        min-height: 50px;

    }
    .panel_card .info-box{box-shadow: 2px 4px 2px #909090;
        border: 1px solid #c1b7b7;
        border-radius: 5px;}
    .info-box-number{font-size: 30px;}
   /* .bg-gray{background: #e8e9ea !important;}*/

    .bg-gray{background: #ecece9 !important;}
    .bg-shadow
    {
        box-shadow: 5px 10px !important;
        color: #d0d0d0 !important;
        width: 90%;
    }
    .info-box-text
    {
        color:black;
    }
</style>

<div class="x_panel panel panel-default">
<div class="panel-body">

           <!-- Main content -->

                <div class="info-box bg-gray ">
                    <div class="panel_card" >
                        <section class="content-header">
                            <h3>
                                Petitions
                                <small></small>
                            </h3>

                        </section>

                        <div class="col-md-3 col-sm-6 col-xs-12" >
                            <div class="info-box bg-shadow">

                                <span class="info-box-icon bg-green"></span>


                                <div class="info-box-content" >
                                    <span class="info-box-text" style="margin-bottom: 5px;">Filed</span>
                                    <span class="info-box-number"><a href="<?php echo base_url()?>jail_dashboard/DefaultController/get_filed_petitions/1" style="color:black;">
                                        <i class="glyphicon glyphicon-list-alt"></i>
                                            <?php  if(isset($total_filed_petitions) && !empty($total_filed_petitions)) {echo $total_filed_petitions;}else { echo '0'; }  ?></a></span>


                                </div>
                                <!-- /.info-box-content -->
                            </div></div>
                        <div class="col-md-3 col-sm-6 col-xs-12" >
                            <div class="info-box bg-shadow">

                                <span class="info-box-icon bg-red"></span>


                                <div class="info-box-content" >
                                    <span class="info-box-text" style="margin-bottom: 5px;">Pending</span>
                                    <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_filed_petitions/2" style="color:black;">
                                            <i class="glyphicon glyphicon-time"></i>
                                        <?php
                                        if(isset($total_pending_petitions) && !empty($total_pending_petitions))
                                        {echo $total_pending_petitions;}else { echo '0'; }  ?></a></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div></div>
                        <div class="col-md-3 col-sm-6 col-xs-12" >
                            <div class="info-box bg-shadow">

                                <span class="info-box-icon bg-yellow"></span>


                                <div class="info-box-content" >
                                    <span class="info-box-text" style="margin-bottom: 5px;">Disposed</span>
                                    <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_filed_petitions/3" style="color:black;">
                                            <img src="assets/images/disposed.png" height="80px" alt="" />
                                        <?php  if(isset($total_disposed_petitions) && !empty($total_disposed_petitions))
                                        {echo $total_disposed_petitions;}else { echo '0'; }  ?></a></span>

                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>

                    </div></div>

                    <div class="info-box bg-gray " >
                        <div class="row">
                        <div class="col-md-6" style="border-right:1px solid #fff;">
                    <div class="panel_card" >


                        <section class="content-header">
                            <h3>
                                Incomplete Petitions
                                <small></small>
                            </h3>

                        </section>



                        <div class="col-md-6 col-sm-6 col-xs-12" >
                            <div class="info-box bg-shadow">

                                <span class="info-box-icon bg-green"></span>


                                <div class="info-box-content" >
                                    <span class="info-box-text" style="margin-bottom: 5px;">Draft</span>
                                <?php    $href = base_url("jail_dashboard/stage_list/" . htmlentities(url_encryption(Draft_Stage, ENT_QUOTES)));?>
                                    <span class="info-box-number"><a href="<?php echo $href?>" style="color:black;">
                                          <i class="glyphicon glyphicon-th-list"></i>

                                            <?php echo htmlentities($count_efiling_data[0]->total_drafts, ENT_QUOTES); ?>
                                        </a> </span>

                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12" >
                            <div class="info-box bg-shadow">

                                <span class="info-box-icon bg-red"></span>


                                <div class="info-box-content" >
                                    <span class="info-box-text" style="margin-bottom: 5px;">Pending for Approval</span>
                                    <?php    $href = base_url("jail_dashboard/stage_list/" . htmlentities(url_encryption(Initial_Approaval_Pending_Stage, ENT_QUOTES)));?>
                                    <span class="info-box-number"><a href="<?php echo $href?>" style="color:black;">
                                            <i class="glyphicon glyphicon-pencil"></i>

                                            <?php echo htmlentities($count_efiling_data[0]->total_pending_acceptance, ENT_QUOTES); ?>
                                        </a>  </span>

                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>

                    </div>
                  </div>

                  <div class="col-md-6">
                      <section class="content-header">
                          <h3>
                              Virtual Interaction
                          </h3>
                      </section>


                      <div class="col-md-6 col-sm-6 col-xs-12" >
                          <div class="info-box bg-shadow">

                              <span class="info-box-icon bg-green"><i class="ion ion-ios-gear-outline"></i></span>



                              <div class="info-box-content" >
                                  <span class="info-box-text" style="margin-bottom: 5px;">Today</span>
                                  <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_vcDetails/1" style="color:black;">
                                          <span class="glyphicon glyphicon-sound-stereo" ></span>
                                          <?php  if(isset($total_today_vc) && !empty($total_today_vc)) {echo $total_today_vc;}else { echo '0'; }  ?></a></span>

                              </div>
                              <!-- /.info-box-content -->
                          </div></div>
                      <div class="col-md-6 col-sm-6 col-xs-12" >
                          <div class="info-box bg-shadow">

                              <span class="info-box-icon bg-green"><i class="ion ion-ios-gear-outline"></i></span>


                              <div class="info-box-content" >
                                  <span class="info-box-text" style="margin-bottom: 5px;">This week</span>
                                  <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_vcDetails/2" style="color:black;">
                                          <span class="glyphicon glyphicon-calendar" height="40px"></span>

                                          <?php  if(isset($total_week_vc) && !empty($total_week_vc)) {echo $total_week_vc;}else { echo '0'; }  ?></a></span>

                              </div>
                              <!-- /.info-box-content -->
                          </div></div>
                  </div>
                 </div>
                    </div>


                <div class="info-box bg-gray" >
                    <div class="panel_card">



                        <div class="col-lg-6" style="border-right:1px solid #fff;">
                            <section class="content-header">
                                <h3>
                                    Custody Certificate
                                    <small></small>
                                </h3>

                            </section>


                            <div class="col-md-4 col-sm-6 col-xs-12" >
                                <div class="info-box bg-shadow">

                                    <span class="info-box-icon bg-red"><i class="ion ion-ios-gear-outline"></i></span>


                                    <div class="info-box-content" >
                                        <span class="info-box-text" style="margin-bottom: 5px;">Pending</span>
                                        <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_certificationDetails/cc/p" style="color:black;">
                                                <i class="glyphicon glyphicon-hourglass" ></i>
                                            <?php  if(isset($total_custody_Pen) && !empty($total_custody_Pen)) {echo $total_custody_Pen;}else { echo '0'; }  ?></a></span>

                                    </div>
                                    <!-- /.info-box-content -->
                                </div></div>

                            <div class="col-md-4 col-sm-6 col-xs-12" >
                                <div class="info-box bg-shadow">

                                    <span class="info-box-icon bg-green"><i class="ion ion-ios-gear-outline"></i></span>


                                    <div class="info-box-content" >
                                        <span class="info-box-text" style="margin-bottom: 5px;">Draft</span>
                                        <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_certificationDetails/cc/d" style="color:black;">
                                                <i class="glyphicon glyphicon-th-list"></i>
                                            <?php  if(isset($total_custody_Draft) && !empty($total_custody_Draft)) {echo $total_custody_Draft;}else { echo '0'; }  ?></a></span>

                                    </div>
                                    <!-- /.info-box-content -->
                                </div></div>
                            <div class="col-md-4 col-sm-6 col-xs-12" >
                                <div class="info-box bg-shadow">

                                    <span class="info-box-icon bg-orange"><i class="ion ion-ios-gear-outline"></i></span>


                                    <div class="info-box-content" >
                                        <span class="info-box-text" style="margin-bottom: 5px;">Completed</span>
                                        <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_certificationDetails/cc/c" style="color:black;">
                                            <i class="glyphicon glyphicon-ok"></i>
                                            <?php  if(isset($total_custody_com) && !empty($total_custody_com)) {echo $total_custody_com;}else { echo '0'; }  ?></a></span>

                                    </div>
                                    <!-- /.info-box-content -->
                                </div></div>
                        </div>


                        <div class="col-lg-6">

                            <section class="content-header">
                                <h3>
                                    Surrender Certificate
                                    <small></small>
                                </h3>

                            </section>


                            <div class="col-md-4 col-sm-6 col-xs-12" >
                                <div class="info-box bg-shadow">

                                    <span class="info-box-icon bg-red"><i class="ion ion-ios-gear-outline"></i></span>


                                    <div class="info-box-content" >
                                        <span class="info-box-text" style="margin-bottom: 5px;">Pending</span>
                                        <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_certificationDetails/sc/p" style="color:black;">
                                                <i class="glyphicon glyphicon-hourglass" ></i>
                                            <?php  if(isset($total_sur_Pen) && !empty($total_sur_Pen)) {echo $total_sur_Pen;}else { echo '0'; }  ?></a></span>

                                    </div>
                                    <!-- /.info-box-content -->
                                </div></div>

                            <div class="col-md-4 col-sm-6 col-xs-12" >
                                <div class="info-box bg-shadow">

                                    <span class="info-box-icon bg-green"><i class="ion ion-ios-gear-outline"></i></span>


                                    <div class="info-box-content" >
                                        <span class="info-box-text" style="margin-bottom: 5px;">Draft</span>
                                        <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_certificationDetails/sc/d" style="color:black;">
                                                <i class="glyphicon glyphicon-th-list"></i>
                                            <?php  if(isset($total_sur_Draft) && !empty($total_sur_Draft)) {echo $total_sur_Draft;}else { echo '0'; }  ?></a></span>

                                    </div>
                                    <!-- /.info-box-content -->
                                </div></div>
                            <div class="col-md-4 col-sm-6 col-xs-12" >
                                <div class="info-box bg-shadow">

                                    <span class="info-box-icon bg-orange"><i class="ion ion-ios-gear-outline"></i></span>

                                    <div class="info-box-content" >
                                        <span class="info-box-text" style="margin-bottom: 5px;">Completed</span>
                                        <span class="info-box-number"><a href="<?php echo base_url()?>index.php/jail_dashboard/DefaultController/get_certificationDetails/sc/c" style="color:black;">
                                                 <i class="glyphicon glyphicon-ok"></i>
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