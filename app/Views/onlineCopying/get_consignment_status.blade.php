<?php
    $postage_response = article_tracking_offline(str_replace(' ', '', (trim($_POST['cn']))));
    
    $postage_response = $postage_response;

    if ($postage_response['Status'] == 'success') {
        ?>

        <div class="card col-12 mt-2 pl-0 pr-0">
            <div class="card-header bg-primary text-white font-weight-bolder">Consignment No. : <?= $_POST['cn'] ?>
            </div>
            <div class="card-body">

                <div id="radioBtn" class="btn-group pb-2">
                    <a class="btn btn-primary btn-sm active" data-toggle="timeline_table_toggle" data-title="timeline_show">Timeline</a>
                    <a class="btn btn-primary btn-sm notActive" data-toggle="timeline_table_toggle" data-title="table_show">Table</a>
                </div>
                <div class="table-sec">
                    <div class="table-responsive">
                        <table id="datatable-responsive" class="table table-striped custom-table show_table_data d-none">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Event</th>
                        <th>Office</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    $track_rpt_arr=array();
                    foreach ($postage_response['DataValue'] as $value) {

                        array_push($track_rpt_arr,array("DATE"=>date('d-M-Y', strtotime($value['event_date'])) ,"TIME"=>date('h:i A', strtotime($value['event_time'])) ,
                            "EVENT"=>$value['event_type'],"OFFICE"=>$value['office']));
                        ?>

                        <tr>
                            <td data-key="Date"><?= date('d-M-Y', strtotime($value['event_date'])) ?></td>
                            <td data-key="Time"><?= date('h:i A', strtotime($value['event_time'])) ?></td>
                            <td data-key="Event"><?= $value['event_type'] ?></td>
                            <td data-key="Office"><?= $value['office'] ?></td>
                        </tr>


                    <?php } ?>
                    </tbody>
                </table>
                    </div>
                </div>

                <!--//xxxxx TRACKING START xxxxx-->
                <div class="mt-2 mb-2" id="show_tracking">
                    <div class="row">
                        <div class="col-md-12 ">
                            <ul class="timeline" style="list-style-type: none;">
                                <?php
                                foreach($track_rpt_arr as $val_track_rpt){
                                    $date=$val_track_rpt['DATE'];
                                    $time=$val_track_rpt['TIME'];
                                    $event=$val_track_rpt['EVENT'];
                                    $office=$val_track_rpt['OFFICE'];

                                    /*echo  $date . '/ ' . $time . '/ ' . $event . '/ ' . $office ;
                                    echo "<br>"; */
                                    ?>
                                    <li>
                                        <p style="color: blueviolet; "><?= $date . ' ( '.$time . ' )' ?> </p>
                                        <p><b>Event: &nbsp;</b> <?= $event . ' / '?> <b>Office: &nbsp;</b><?=  $office;?></p>
                                    </li>
                                <?php }  ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--//xxxxxx TRACKING END xxxxx-->

            </div>
        </div>


        <?php
    } else {
        ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Please Enter Valid Consignment No.</strong></div>
        <?php
    }
?>