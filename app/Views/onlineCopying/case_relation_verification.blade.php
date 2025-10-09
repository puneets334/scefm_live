<?php
if(isset(session()->get('login')['impersonator_user']) && isset(session()->get('login')['impersonator_user']->is_active) && !empty(session()->get('login')['impersonator_user']) && session()->get('login')['impersonator_user']->is_active == 1){
    $extends = 'layout.advocateApp';
}else{
    $extends = 'layout.ecopyApp';
} ?>
@extends($extends)
@section('content')
<?php
extract($_POST);
//print_r($_POST);
//die;
date_default_timezone_set('Asia/Kolkata'); 
$clientIP = getClientIP();
$aotAuth = '';
if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == '2'){ //party
    $scipay = 10005;
    $requision_title = "Verification of Party";
    $asset_type = 5;
    $allowed_request = 'party';
}
else if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == '4'){ //third_party
    $scipay = 10006;
    $requision_title = "Verification of Third Party";
    $asset_type = 4; //affidavit
    $allowed_request = 'third_party';
}
else if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == '3'){ //appearing_counsel
    $scipay = 10004;
    $requision_title = "Verification of Appearing Counsel";
    $asset_type = 6;
    $allowed_request = 'appearing_counsel';
}
else{
    $aotAuth = "Not an Authenticated Request";
}

if(empty($aotAuth)){
if(isset($_SESSION['affidavit_file_name']))
    $affidavit_file_name_v = $_SESSION['affidavit_file_name'];
else
    $affidavit_file_name_v = '';



if(isset($_SESSION['user_address'])) {
    foreach($_SESSION['user_address'] as $data_address) {
        if($data_address['id'] == $address_id){
            $first_name = $data_address['first_name'];
            $second_name = $data_address['second_name'];
            $postal_add = $data_address['address'];
            $city = $data_address['city'];
            $district = $data_address['district'];
            $state = $data_address['state'];
            $country = $data_address['country'];
            $pincode = $data_address['pincode'];
            break;
        }
    }
}


    if ($scipay > 0) {
  
        //user case verification 
if(isset($_SESSION["session_filed"]) && ($_SESSION["session_filed"] == '2' || $_SESSION["session_filed"] == '3' || $_SESSION["session_filed"] == '4')) {
    


        
       
        $create_crn = createCRN($scipay);//for unavailable document request
       
        $json_crn = $create_crn;
        if ($json_crn->{'Status'} == "success") {
        $crn = $json_crn->{'CRN'};
        $userAssets=$ecoping_webservices->getUserAssets($_SESSION["applicant_mobile"],$_SESSION["applicant_email"]);
        //print_r($userAssets);                               
        /*$asset_array = array(
            "mobile" => $_SESSION["applicant_mobile"],
            "email" => $_SESSION["applicant_email"],
            "asset_type" => $asset_type,
            "id_proof_type" => '0',
            "diary_no" => $_SESSION['session_d_no'].$_SESSION['session_d_year'],
            "video_random_text" => '0',
            "file_path" => $affidavit_file_name_v,
            "entry_time_ip" => $clientIP
        );*/
        $asset_array = array(
            "mobile" => $_SESSION["applicant_mobile"],
            "email" => $_SESSION["applicant_email"],
            "asset_type" => $asset_type,
            "id_proof_type" => '0',
            "diary_no" => $_SESSION['session_d_no'].$_SESSION['session_d_year'],
            "video_random_text" => 0,
            "file_path" => (!empty($userAssets[0]['file_path'])?$userAssets[0]['file_path']:''),
            "entry_time_ip" => $clientIP
        );
        $insert_user_asset = insert_user_assets($asset_array); //insert user assets
        
        $json_insert_user_asset = json_decode($insert_user_asset);
        if ($json_insert_user_asset->{'Status'} == "success") {

        }
    
        
        $dataArray = array(
            "diary" => (isset($_SESSION['session_d_no']) && isset($_SESSION['session_d_year'])) ? $_SESSION['session_d_no'].$_SESSION['session_d_year'] : 0,
            "copy_category" =>(!empty($app_type)?$app_type:0),
            "application_reg_number" => '0',
            "application_reg_year" => '0',
            "application_receipt" => date('Y-m-d H:i:s'),
            "advocate_or_party" => '0',
            "court_fee" => isset($_SESSION['session_total_amount_to_pay']) ? $_SESSION['session_total_amount_to_pay'] : 0,
            "delivery_mode" => (!empty($cop_mode)?$cop_mode:0),
            //"postal_fee" => $postal_fee,
            'postal_fee'=>(!empty($fee_in_stamp)?$fee_in_stamp:0),
            "ready_date" => '',
            "dispatch_delivery_date" => '',
            "adm_updated_by" => '1',
            "updated_on" => date('Y-m-d H:i:s'),
            "is_deleted" => "0",
            "is_id_checked" => '',
            "purpose" => '',
            "application_status" => 'P',
            "defect_code" => '',
            "defect_description" => '',
            "notification_date" => '',
            "filed_by" => $_SESSION["session_filed"],
            "name" => $first_name.' '.$second_name,
            "mobile" => $_SESSION["applicant_mobile"],
            "address" => $postal_add.', '.$city.', '.$district.', '.$state.', '.$country.' - '.$pincode,
            "application_number_display" => '',
            "temp_id" => '',
            "remarks" => '',
            "source" => '6',
            "send_to_section" => 'f',
            "crn" => $crn,
            "email" => $_SESSION["applicant_email"],
            "authorized_by_aor" => isset($_SESSION['session_authorized_bar_id'])> 0 ? $_SESSION['session_authorized_bar_id'] : '0',
            "allowed_request" => $allowed_request,
            "token_id" => '',
            "address_id" => $address_id
        );
                     
        $insert_application = insert_copying_application_online($dataArray); //insert application
        
        $json_insert_application = $insert_application;
         
        if ($json_insert_application->{'Status'} == "success") {
            $last_application_id = $json_insert_application->{'last_application_id'};
            $copy_detail = explode("#", $copy_detail);
            $count_copy_details = count($copy_detail) - 1;
            
            for($var = 0; $var<$count_copy_details; $var++){
                $explode_copy_detail = explode(",", $copy_detail[$var]);
                $order_date = $explode_copy_detail[0];
                $order_pages = $explode_copy_detail[1];
                $spjudgementordercode = $explode_copy_detail[2];
                $spjudgementorder = $explode_copy_detail[3];
                $order_file_path = $explode_copy_detail[4];
                $document_array=array();
                $document_array = array(
                    'order_type' => $spjudgementordercode,
                    'order_date' => date('Y-m-d', strtotime($order_date)),
                    'copying_order_issuing_application_id' =>$last_application_id,
                    'number_of_copies' =>$num_copy,
                    'number_of_pages_in_pdf'=>$order_pages,
                    'path' =>$order_file_path,
                    'from_page' =>1,
                    'to_page' =>$order_pages,
                    'order_type_remark' =>'',
                    'is_bail_order' => 'N'
                );
                $insert_application_documents = insert_copying_application_documents_online($document_array); //insert user assets
                $json_insert_application_documents = $insert_application_documents;
                
                if ($json_insert_application_documents->{'Status'} == "success") {

                }
                else{
                    $array = array('status' => 'Unable to insert records');
                }
            }

            
            $sms = "Your request successfully submitted with CRN $crn for reference - Supreme Court Of India";
            $mobile = $_SESSION["applicant_mobile"];
            // $sms_response = sci_send_sms($mobile,$sms,'ecop',SCISMS_e_copying_crn_created);
            // $json = json_decode($sms_response);
            // if ($json->{'Status'} == "success") {
            //     $array = array('status' => 'success');
            // }
        }
        else{
            $array = array('status' => 'Unable to insert records');
        }
    }
    else{
        $array = array('status' => 'Permission Denied');
    }
    
    if ($json_insert_application->{'Status'} == "success" && $json_insert_user_asset->{'Status'} == "success") {
        $random_string=time();
    ?>

        <div class="card">

            <div class="card-body">
                <div class="text-center">
                    <h3>SUPREME COURT OF INDIA<BR><?= $requision_title; ?></h3>
                    <h4><?= $_SESSION['session_cause_title']; ?></h4>
                    <strong><?= $_SESSION['session_case_no']; ?></strong>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-info text-white font-weight-bolder">Copying Details</div>
            <div class="card-body">


                <div class="form-row">
                    <div class="col-md-6">
                        <div class="row ">
                            <div class="col-md-4 font-weight-bolder">
                                <label>CRN:</label>
                            </div>
                            <div class="col-md-6">
                                <p class="text-left"><?= $crn; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row ">
                            <div class="col-md-4 font-weight-bolder">
                                <label>Application Date:</label>
                            </div>
                            <div class="col-md-6">
                                <p class="text-left"><?= date('d-m-Y H:i:s'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
        if($scipay != '10005') { //if not a party
        ?>
        <div class="form-row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4 font-weight-bolder">
                        <label>Application Category:</label>
                    </div>
                    <div class="col-md-6">
                        <p><?php
                            // $db2 = \Config\Database::connect('sci_cmis_final'); // Connect to the 'sci_cmis_final' database
                            // $builder = $db2->table('master.copy_category'); // Specify the table name
                            // $builder->select('id, code, description');
                            // $builder->where('id', $app_type);
                            // $query = $builder->get();
                            // $row = $query->getRow(); // Fetch single row as an object

                            $db2 = \Config\Database::connect('sci_cmis_final'); // Connect to the 'sci_cmis_final' database
                            $builder = $db2->table('copy_category'); // Specify the table name
                            $builder->select('id, code, description');
                            $builder->where('id', $app_type);
                            $query = $builder->get();
                            $row = $query->getRow(); // Fetch single row as an object
                            echo $row->description;
                            ?></p>
                    </div>
                </div>
            </div>

        </div>


        <div class="form-row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4 font-weight-bolder">
                        <label>Applied For:</label>
                    </div>
                    <div class="col-md-8">
                        <p><?php
                            for ($var = 0; $var < $count_copy_details; $var++) {
                                $explode_copy_detail = explode(",", $copy_detail[$var]);
                                echo $explode_copy_detail[3] . " Order/File Date " . $explode_copy_detail[0] . " Pages " . $order_pages = $explode_copy_detail[1] . "<br>";
                            }
                            ?></p>
                    </div>
                </div>
            </div>
        </div>


        <div class="form-row">
            
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-6 font-weight-bolder">
                        <label>No. of Copies:</label>
                    </div>
                    <div class="col-md-6">
                        <p><?= $num_copy; ?></p>
                    </div>
                </div>
            </div>
            <?php //if ($scipay != 10003 && $cop_mode != 3) { //not a party
                ?>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-6 font-weight-bolder">
                            <label>Copying Fees:</label>
                        </div>
                        <div class="col-md-6">
                            <p><?= "Rs. " . $_SESSION['session_total_amount_to_pay']. "/-"; ?></p>
                        </div>
                    </div>
                </div>
            <?php //} ?>

            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-6 font-weight-bolder">
                        <label>Delivery Mode:</label>
                    </div>
                    <div class="col-md-6">
                        <p><?php
                            if ($cop_mode == 1) {
                                echo "By Speed Post";
                            }
                            if ($cop_mode == 2) {
                                echo "Counter";
                            }
                            if ($cop_mode == 3) {
                                echo "Email";
                            }
                            ?></p>
                    </div>
                </div>
            </div>

        </div>
        <?php
        }
        ?>

            </div>
        </div>


        <div class="card">
            <div class="card-header bg-info text-white font-weight-bolder">Applicant Details</div>
            <div class="card-body">


                <div class="form-row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-4 font-weight-bolder">
                                <label>Applied By:</label>
                            </div>
                            <div class="col-md-8">
                                <p><?php
                                    if ($_SESSION["session_filed"] == 1) {
                                        echo "Adovcate on Record";
                                    }
                                    if ($_SESSION["session_filed"] == 2) {
                                        echo "Party of the case";
                                    }
                                    if ($_SESSION["session_filed"] == 3) {
                                        echo "Appearing Council";
                                    }
                                    if ($_SESSION["session_filed"] == 4) {
                                        echo "Third Party";
                                    }
                                    if ($_SESSION["session_filed"] == 6) {
                                        echo "Authorized by AOR";
                                    }
                                    ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-4 font-weight-bolder">
                                <label>Mobile No.:</label>
                            </div>
                            <div class="col-md-8">
                                <p><?= $_SESSION["applicant_mobile"]; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-4 font-weight-bolder">
                                <label>Email:</label>
                            </div>
                            <div class="col-md-8">
                                <p><?= $_SESSION["applicant_email"]; ?></p>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="form-row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-4 font-weight-bolder">
                                <label>Name:</label>
                            </div>
                            <div class="col-md-8">
                                <p><?= $first_name . ' ' . $second_name; ?></p>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-8">

                        <div class="row">
                            <div class="col-md-2 font-weight-bolder">
                                <label>Address:</label>
                            </div>
                            <div class="col-md-10">
                                <p><?= $postal_add . ' ' . $city . ' ' . $district . ' ' . $state . ' ' . $country . ' ' . $pincode; ?></p>
                            </div>
                        </div>

                    </div>
                </div>

                <?php


                    ?>
                    <div class="form-row">
                        

                    <?php
                        if (isset($_SESSION['affidavit_file_name'])) {
                    ?>
                        <div class="col-md-4">

                            <div class="row">
                                <div class="col-md-8 font-weight-bolder">
                                    <label>Affidavit Uploaded ?:</label>
                                </div>
                                <div class="col-md-4">
                                    <p><?php if (isset($_SESSION['affidavit_file_name'])) {
                                            echo 'YES';
                                        } else {
                                            echo 'NO';
                                        } ?></p>
                                </div>
                            </div>

                        </div>
                        <?php } ?>
                    </div>
                <div>
                    <span class="text-success">Your request submitted successfully.</span>
                </div>


            </div>
        </div>
            <?php
        
    }
}
else{
    echo "Wrong Path...";
}
}
else{
    echo "Error";
}
}
else{ ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="dash-card dashboard-section">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class=" dashboard-bradcrumb">
                            <div class="left-dash-breadcrumb">
                                <div class="page-title">
                                    <h5><i class="fa fa-file"></i> Case Search</h5>
                                </div>
                                <div class="form-response" id="msg" role="alert" data-auto-dismiss="5000"></div>
                            </div>
                            <div class="ryt-dash-breadcrumb">
                                <div class="btns-sec">
                                    <!-- <a href="javascript:void(0)" class="quick-btn pull-right mb-2" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                    <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <?=$aotAuth; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }
    
// unset($_SESSION['session_d_no']);
// unset($_SESSION['session_d_year']);
//session_destroy();
?>
@endsection