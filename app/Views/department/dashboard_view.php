
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


                    </div></div>

                    <div class="info-box bg-gray " >
                        <div class="row">
                        <div class="col-md-6" style="border-right:1px solid #fff;">

                  </div>


                 </div>
                    </div>



            <!-- /.content -->
        </div>
</div>