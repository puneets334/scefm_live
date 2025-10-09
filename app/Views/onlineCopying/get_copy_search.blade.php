<?php
$track_horizonal_timeline = array();
$disposed_flag = array('F', 'R', 'D', 'C', 'W');
if (is_array($results) && count($results) > 0) {
    $row = $results;
    $case_no = "";
    $request_received_f = '<strong class="text-success">Received</strong>';
    array_push($track_horizonal_timeline, array("flag_type" => 'Request', "flag_description" => $request_received_f . ' ' . date("d-m-Y H:i:s", strtotime($row['application_receipt']))));
    if ($row['application_request'] == 'request') {
        $sqRes = getCopySearchResult($row);
        $data_asset_string = '';
        if (count($sqRes) > 0) {
            foreach ($sqRes as $data_asset) {
                if ($data_asset['verify_status'] == 1) {
                    $data_asset_status = ' <strong class="text-primary">Pending</strong>';
                }
                if ($data_asset['verify_status'] == 2) {
                    $data_asset_status = ' <strong class="text-success">Accepted</strong>';
                }
                if ($data_asset['verify_status'] == 3) {
                    $data_asset_status = ' <strong class="text-danger">Rejected</strong>';
                }
                $data_asset_string .= $data_asset['asset_name'] . $data_asset_status . ' (' . date("d-m-Y H:i:s", strtotime($data_asset['verify_on'])) . ')<br>';
            }
        }
        $asset_type_flag = '';
        if ($row['filed_by'] == 2) {
            $asset_type_flag = 5;
        } //party
        if ($row['filed_by'] == 3) {
            $asset_type_flag = 6;
        } //appearing counsel
        if ($row['filed_by'] == 4) {
            $asset_type_flag = 4;
        }
        $statusResult = getCopyStatusResult($row, $asset_type_flag);
        if (count($statusResult) > 0) {
            foreach ($statusResult as $data) {
               
                if ($data['verify_status'] == 1) {
                    $data_asset_status = ' <strong class="text-primary">Pending</strong>';
                }
                if ($data['verify_status'] == 2) {
                    $data_asset_status = ' <strong class="text-success">Accepted</strong>';
                }
                if ($data['verify_status'] == 3) {
                    $data_asset_status = ' <strong class="text-danger">Rejected</strong>';
                }
                //array_push($track_horizonal_timeline, array("flag_type"=> 'Verify '.$data_asset['asset_name'] ,"flag_description"=>$data_asset_status.' ('.date("d-m-Y H:i:s", strtotime($data_asset['verify_on'])).')'));
                $data_asset_string .= $data['asset_name'] . $data_asset_status . ' (' . date("d-m-Y H:i:s", strtotime($data['verify_on'])) . ')';
            }
            
        }
        array_push($track_horizonal_timeline, array("flag_type" => 'Verification', "flag_description" => $data_asset_string));
    }
    

        if ($row['reg_no_display'] != '') {
            $case_no = $row['reg_no_display'];
        }
        $case_no .= ' DNo. ' . substr($row['diary'], 0, -4) . '-' . substr($row['diary'], -4);
        if ($row['c_status'] == 'P') {
            $case_status = 'Pending';
        } else {
            $case_status = 'Disposed';
        }
?>

        <div class="card mt-2">
            <div class="card-header bg-primary text-white font-weight-bolder">Details</div>
            <div class="card-body">
                <div style="border-radius: 15px 15px 15px 15px;" class="p-2 m-1">
                    <div class="row">
                        <div class="col-md-4">Application No.: <span class="font-weight-bold text-gray"><?= ($row['application_request'] == 'application') ? $row['application_number_display'] : 'NA'; ?></span>
                        </div>
                        <div class="col-md-4">CRN: <span class="font-weight-bold text-gray"><?= $row['crn'] == '0' ? '' : $row['crn']; ?></span></div>
                        <div class="col-md-4">Date: <span class="font-weight-bold text-gray"><?= date("d-m-Y", strtotime($row['application_receipt'])); ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">Source: <span class="font-weight-bold text-gray"><?= $row['description']; ?></span></div>
                        <div class="col-md-4">Applied By: 
                            <span class="font-weight-bold text-gray">
                                <?php
                                    if ($row['filed_by'] == 1) {
                                        echo "AOR";
                                    }
                                    if ($row['filed_by'] == 2) {
                                        echo "Party";
                                    }
                                    if ($row['filed_by'] == 3) {
                                        echo "Appearing Counsel";
                                    }
                                    if ($row['filed_by'] == 4) {
                                        echo "Third Party";
                                    }
                                    if ($row['filed_by'] == 6) {
                                        echo "Authenticated By AOR";
                                    }
                                ?>
                            </span>
                        </div>
                        <div class="col-md-4">Applicant Name: <span class="font-weight-bold text-gray"><?= $row['name']; ?></span></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">Application Status: <span class="font-weight-bold text-gray"><?= (in_array($row['application_status'], $disposed_flag)) ?  "Action Completed" : "Pending" ?></span></div>
                        <div class="col-md-4">Delivery Mode: <span class="font-weight-bold text-gray">
                            <?php
                            if ($row['delivery_mode'] == 1) {
                                echo 'Post';
                                $resBarcode = getCopyBarcode($row);
                                if (!empty($resBarcode) && count($resBarcode) > 0) {
                                    $explode_barcode = explode(",", $resBarcode['barcode']);
                            ?>
                                <a href="#" onclick="mytrack_record()" id='myBtn'>Click to Track</a>
                            <?php
                                }
                            }
                            if ($row['delivery_mode'] == 2) {
                                echo "Counter";
                            }
                            if ($row['delivery_mode'] == 3) {
                                echo "Email";
                            }
                            ?></span>
                        </div>
                        <?php
                        if (($row['application_request'] == 'application')  && $row['delivery_mode'] != 3) {
                        ?>
                            <div class="col-md-4">Fee: <span class="font-weight-bold text-gray">Rs. <?= $row['court_fee'] + $row['postal_fee']; ?></span>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12">Case No.: <span class="font-weight-bold text-gray"><?= $case_no; ?></span></div>
                    </div>
                </div>






                <?php
                $rs = array();
                if (($row['application_request'] == 'application') ) {
                    $rs = getCopyApplication($row);
                }
                if (($row['application_request'] == 'request') ) {
                    $rs = getCopyRequest($row);
                }

                if (count($rs) > 0) {
                    $sno = 1;
                ?>
                    <div class="table-responsive fixed-table-body row m-1 p-1 application_document">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>SNo.</th>
                                    <th>Document Type</th>
                                    <th>Date</th>
                                    <th>Copies</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $section_data_avl='';
                                $snoo=0;
                                foreach ($rs as $row1) {
                                    if ($row['application_request'] == 'request' && $row['allowed_request'] == 'request_to_available') {
                                        $recv_from_sec = copyFormSentOn($row1);
                    
        
                                        if (count($recv_from_sec) > 0) {
                                            $section_data_avl = 'YES';
                                            $snoo2=0;
                                            foreach ($recv_from_sec as $row_section) {

                                                $snoo2++;
                                                $section_movement .= $snoo2 . ". Section " . $row_section['from_section1'] . " -> " . $row_section['to_section1'] . '<br>(' . date("d-m-Y H:i:s", strtotime($row_section['from_section_sent_on'])) . ")";
                                                $section_movement .= "<br>";
                                            }
                                            if (!empty($row1['path'])) {
                                                $section_movement .= "<strong class='text-success'>Uploaded</strong> " . date("d-m-Y H:i:s", strtotime($row1['updated_on'])) . "<br>";
                                            }
                                            if (empty($row1['path']) && $row1['request_status']) {
                                                $section_movement .= "<strong class='text-success'>Rejected</strong> " . date("d-m-Y H:i:s", strtotime($row1['updated_on'])) . "<br>";
                                            }
                                        }
                                    }
                                    $action_on_pdf='';
                                    if ($row['application_request'] == 'application' && $row['delivery_mode'] == 3) {
                                        $snoo++;
                                        //digital signed
                                        if ($row1['pdf_embed_on'] != null && $row1['pdf_embed_on'] != '0000-00-00 00:00:00')
                                            $action_on_pdf .= $snoo . ". Verify " . date("d-m-Y H:i:s", strtotime($row1['pdf_embed_on'])) . "<br>";
                                        if ($row1['pdf_digital_signature_on'] != null && $row1['pdf_digital_signature_on'] != '0000-00-00 00:00:00')
                                            $action_on_pdf .= "Digital Sign " . date("d-m-Y H:i:s", strtotime($row1['pdf_digital_signature_on'])) . "<br>";
                                        if ($row1['sent_to_applicant_on'] != null && $row1['sent_to_applicant_on'] != '0000-00-00 00:00:00')
                                            $action_on_pdf .= "Sent to user " . date("d-m-Y H:i:s", strtotime($row1['sent_to_applicant_on'])) . "<br>";
                                    }


                                ?>
                                    <tr>
                                        <td data-key="SNo.">
                                            <?= $sno++; ?>
                                        </td>
                                        <td data-key="Document Type">
                                            <?= $row1['order_name']; ?>
                                        </td>
                                        <td data-key="Date">
                                            <?= date("d-m-Y", strtotime($row1['order_date'])); ?>
                                        </td>
                                        <td data-key="Copies">
                                            <?= $row1['number_of_copies']; ?>
                                        </td>
                                    </tr>
                                <?php
                                }

                                if ($row['application_request'] == 'application' && $row['delivery_mode'] == 3) {
                                    //digital signed
                                    array_push($track_horizonal_timeline, array("flag_type" => 'Signature', "flag_description" => $action_on_pdf));
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>
            </div>
        </div>



    <?php
            if ($section_data_avl == 'YES' && $row['application_request'] == 'request' && $row['allowed_request'] == 'request_to_available') {
                array_push($track_horizonal_timeline, array("flag_type" => 'Movement', "flag_description" => $section_movement));
            }
        }
        //


        if (in_array($row['application_status'], $disposed_flag)) {

            //$data_disposed_content = "<strong class='text-danger'>".$row['status_description']."</strong>";
            $data_disposed_content = "<strong class='text-danger'>Action Completed</strong>";

            if ($row['application_status'] == 'D')
                array_push($track_horizonal_timeline, array("flag_type" => 'Disposed', "flag_description" => $data_disposed_content . ' ' . date("d-m-Y H:i:s", strtotime($row['dispatch_delivery_date']))));
            else
                array_push($track_horizonal_timeline, array("flag_type" => 'Disposed', "flag_description" => $data_disposed_content . ' ' . date("d-m-Y H:i:s", strtotime($row['updated_on']))));
        }

    ?>
    <!--START HORIZONTAL TIMELINE -->
    </div>
    <div class="card mt-2">
        <div class="card-header bg-primary text-white font-weight-bolder">Timeline
        </div>
        <div class="card-body">
            <div class="my-2" id="show_tracking">
                <div class="row">
                    <div class="col-md-12 ">

                        <ul class="timeline" style="list-style-type: none;">
                            <?php
                            foreach ($track_horizonal_timeline as $val_track_rpt) {

                                // print_r($val_track_rpt); exit;
                                $flag_type = $val_track_rpt['flag_type'];
                                $flag_description = $val_track_rpt['flag_description'];
                            ?>
                                <li>
                                    <h6 style="color: blueviolet; "><?= $flag_type ?> </h6>
                                    <p><b></b><?= $flag_description ?></p>

                                </li>
                            <?php
                            }



                            ?>

                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--END HORIZONTAL TIMELINE -->
                        </div>
    <style>
    ul.timeline {
        list-style-type: none;
        position: relative;
    }
    ul.timeline:before {
        content: ' ';
        background: #d4d9df;
        display: inline-block;
        position: absolute;
        left: 29px;
        width: 2px;
        height: 100%;
        z-index: 400;
    }
    ul.timeline > li {
        margin: 20px 0;
        padding-left: 35px;
    }
    ul.timeline > li:before {
        content: ' ';
        background: white;
        display: inline-block;
        position: absolute;
        border-radius: 50%;
        border: 3px solid #124cbf;
        left: 20px;
        width: 20px;
        height: 20px;
        z-index: 400;
    }


    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    /* The Close Button */
    .close {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }




        #app {
            padding: 50px 0;
        }
    .timeline1 {
        margin: 50px 0;
        list-style-type: none;
        display: flex;
        padding: 0;
        text-align: center;
    }
    .timeline1 li {
        transition: all 200ms ease-in;
    }
    .timestamp {
        width: 100%; 
    margin-bottom: 20px;
        padding: 0px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        font-weight: 100;
    }
    .status {
        padding: 0px 40px;
        display: flex;
        justify-content: center;
        border-top: 4px solid #3e70ff;
        position: relative;
        transition: all 200ms ease-in ;
    }

    .status span {
        font-weight: 600;
        padding-top: 20px;
    }
    .status span:before {
        content: '';
        width: 25px;
        height: 25px;
        background-color: #e8eeff;
        border-radius: 25px;
        border: 4px solid #3e70ff;
        position: absolute;
        top: -15px;
        left: calc(50% - 12px); 
    transition: all 200ms ease-in;
    }
    .swiper-control {
        text-align: right;
    }

    .swiper-container {
        width: 100%;
        height: 300px;
        /*margin: 50px 0;*/
        /*overflow: hidden;*/
        /*overflow: scroll;*/
        padding: 0 20px 30px 20px;
    }
    .swiper-slide {
        width: 300px;
        text-align: center;
        font-size: 14px;
    }
</style>


    <div class="row col-12 mb-3" id="result" style="overflow: auto !important;"></div>
    <div id="track_record"></div>
    <div id="myModal" class="modal">
        <!-- Modal content -->

        <?php
        if(isset($explode_barcode) && is_array($explode_barcode)){
        for ($k = 0; $k < count($explode_barcode); $k++) {
            $postage_response = article_tracking_offline($explode_barcode[$k]);
            $postage_response = json_decode($postage_response, true);
            //var_dump($postage_response);
        ?>

            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Consignment No. : <span class="font-weight-bolder"><?= $explode_barcode[$k] ?></span></p>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>

                <div class="my-2" id="show_tracking">
                    <div class="row">
                        <div class="col-md-12 ">

                            <ul class="timeline" style="list-style-type: none;">
                                <?php
                                if ($postage_response['Status'] == 'success') {
                                    foreach ($postage_response['DataValue'] as $value) {
                                ?>
                                        <li>
                                            <h6 style="color: blueviolet; "><?= date('d-M-Y (h:i A)', strtotime($value['event_date'])) ?> </h6>
                                            <p><b>Event:</b><?= $value['event_type'] . ' / ' ?> <b>Office:</b><?= $value['office'] ?></p>

                                        </li>
                                <?php
                                    }
                                } else {
                                    echo "<span class='text-danger'>No Records Found</span>";
                                }

                                ?>
                            </ul>

                        </div>
                    </div>
                </div>
            </div><!--END OF DIV class="modal-content" -->
        <?php } } ?>
    </div><!--END OF DIV class="modal" -->

<?php
    } else {
?>
    <div class="alert alert-danger alert-dismissible">
        <strong>No Record Found</strong>
    </div>
<?php
    }
?>

