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

$first_post_base64string = base64_encode(json_encode($_POST));
// $cop_mode = '';
if(isset($_POST['name'])){
    
    $data = base64_decode(trim($_POST['posted_values']));
    $json_data = json_decode($data, true);

    if(isset($_SESSION['user_address'])) {
       // var_dump($_SESSION['user_address']);
        foreach($_SESSION['user_address'] as $data_address) {
            if($data_address['id'] == $json_data['address_id']){
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
    $cop_mode = $json_data['cop_mode'];
    //if($json_data['cop_mode'] != 3 && $json_data['app_type'] != 5){
        //payment
        $scipay = 10001;
    //}
    $create_crn = createCRN($scipay);
    $json_crn =$create_crn;
    if ($json_crn->{'Status'} == "success") {
        $OrderBatchMerchantBatchCode = $json_crn->{'CRN'};

        $clientIP = getClientIP();

        $child_request = array();
        $loop_for_batch = 0;
        if ($json_data['service_charges'] > 0) {
            $loop_for_batch++;
        }
        if ($json_data['fee_in_stamp'] > 0) {
            $loop_for_batch++;
        }
        if ($json_data['postage'] > 0) {
            $loop_for_batch++;
        }
        $data = array(
            "DepartmentCode" => DEPARTMENT_CODE_LIVE,
            "OrderBatchTransactions" => "$loop_for_batch",
            "OrderBatchMerchantBatchCode" => "$OrderBatchMerchantBatchCode",
            "OrderBatchTotalAmount" => number_format($_SESSION['session_total_amount_to_pay'],2,".",""), //number_format($_SESSION['session_total_amount_to_pay'], 2),
            "InstallationId" => INSTALLATION_ID_LIVE,
            "OrderCode" => "$OrderBatchMerchantBatchCode",
            "CartDescription" => CART_DESCRIPTION_FOR_SERVICE_CHARGES,
            "OrderContent" => ORDER_CONTENT_LIVE,
            "PaymentTypeId" => PAYMENT_TYPE_ID_LIVE,
            "PAOCode" => PAO_CODE,
            "DDOCode" => DDO_CODE,
            "PaymentMethodMode" => "Online",
            "ShopperEmailAddress" => $_SESSION["applicant_email"],
            "ShippingFirstName" => $first_name,
            "ShippingLastName" => $second_name,
            "ShippingAddress1" => $postal_add,
            "ShippingAddress2" => "",
            "ShippingPostalCode" => $pincode,
            "ShippingCity" => $city,
            "ShippingStateRegion" => $district,
            "ShippingState" => $state,
            "ShippingCountryCode" => $country,
            "ShippingMobileNumber" => $_SESSION["applicant_mobile"],
            "BillingFirstName" => "",
            "BillingLastName" => "",
            "BillingAddress1" => "",
            "BillingAddress2" => "",
            "BillingPostalCode" => "",
            "BillingCity" => "",
            "BillingStateRegion" => "",
            "BillingState" => "",
            "BillingCountryCode" => "",
            "BillingMobileNumber" => "",
            "clientIP" => "$clientIP",
            "serviceUserID" => "$scipay"
        );
        bharaKoshDataServiceRequest($data);

//var_dump($statement);
        $loop_for_multi_item = 0;
        if ($json_data['service_charges'] > 0) {
            $loop_for_multi_item++;
            if ($loop_for_multi_item == 1) {
                $new_order_batch_code = $OrderBatchMerchantBatchCode;
            } else {
                $new_order_batch_code = $OrderBatchMerchantBatchCode . "-" . $loop_for_multi_item;
            }
            $statement_batch = array(
                "OrderBatchMerchantBatchCode" => "$OrderBatchMerchantBatchCode",
                "OrderCode" => $new_order_batch_code,
                "amount" => number_format($json_data['service_charges'],2,".",""),//number_format($json_data['service_charges'], 2),
                "CartDescription" => CART_DESCRIPTION_FOR_SERVICE_CHARGES,
                "OrderContent" => ORDER_CONTENT_LIVE,
                "PaymentTypeId" => PAYMENT_TYPE_ID_LIVE,
            );
            bharaKoshDataBatchServiceRequest($statement_batch);

            $child_request[] = array(
                "ChildAmount" => number_format($json_data['service_charges'],2,".",""), //number_format($json_data['service_charges'], 2),
                "ChildOrderCode" => $new_order_batch_code,
                "ChildCartDescription" => CART_DESCRIPTION_FOR_SERVICE_CHARGES,
                "ChildOrderContent" => ORDER_CONTENT_LIVE,
                "ChildPaymentTypeId" => CHILD_PAYMENT_TYPE_ID,
            ); 

        }
        if ($json_data['fee_in_stamp'] > 0) {
            $loop_for_multi_item++;
            if ($loop_for_multi_item == 1) {
                $new_order_batch_code = $OrderBatchMerchantBatchCode;
            } else {
                $new_order_batch_code = $OrderBatchMerchantBatchCode . "-" . $loop_for_multi_item;
            }
            $statement_batch = array(
                "OrderBatchMerchantBatchCode" => "$OrderBatchMerchantBatchCode",
                "OrderCode" => $new_order_batch_code,
                "amount" => number_format($json_data['fee_in_stamp'],2,".",""), // number_format($json_data['fee_in_stamp'], 2),
                "CartDescription" => CART_DESCRIPTION_FOR_FEE_STAMP_COPYING,
                "OrderContent" => ORDER_CONTENT_LIVE,
                "PaymentTypeId" => PAYMENT_TYPE_ID_LIVE,
            );
            bharaKoshDataBatchServiceRequest($statement_batch);

            $child_request[] = array(
                "ChildAmount" =>  number_format($json_data['fee_in_stamp'],2,".",""), //number_format($json_data['fee_in_stamp'], 2),
                "ChildOrderCode" => $new_order_batch_code,
                "ChildCartDescription" => CART_DESCRIPTION_FOR_FEE_STAMP_COPYING,
                "ChildOrderContent" => ORDER_CONTENT_LIVE,
                "ChildPaymentTypeId" => CHILD_PAYMENT_TYPE_ID_FEE_STAMP,
            ); 

        }
        if ($json_data['postage'] > 0) {
            $loop_for_multi_item++;
            if ($loop_for_multi_item == 1) {
                $new_order_batch_code = $OrderBatchMerchantBatchCode;
            } else {
                $new_order_batch_code = $OrderBatchMerchantBatchCode . "-" . $loop_for_multi_item;
            }
            $statement_batch = array(
                "OrderBatchMerchantBatchCode" => "$OrderBatchMerchantBatchCode",
                "OrderCode" => $new_order_batch_code,
                "amount" =>  number_format($json_data['postage'],2,".",""), //number_format($json_data['postage'], 2),
                "CartDescription" => "Postage",
                "OrderContent" => ORDER_CONTENT_LIVE,
                "PaymentTypeId" => PAYMENT_TYPE_ID_LIVE, 
            );
            bharaKoshDataBatchServiceRequest($statement_batch);

            $child_request[] = array(
                "ChildAmount" => number_format($json_data['postage'],2,".",""), //number_format($json_data['postage'], 2),
                "ChildOrderCode" => $new_order_batch_code,
                "ChildCartDescription" => "Postage",
                "ChildOrderContent" => ORDER_CONTENT_LIVE,
                "ChildPaymentTypeId" => CHILD_PAYMENT_TYPE_ID_POSTAGE,
            );  
        }


//online_copying table insert

        if ($scipay == '10001') {
            $requision_title = "COPYING REQUISTION";
            $allowed_request = "e_copying_prepaid";
        } else {
            $requision_title = "";
            $allowed_request = "0";
        }

        $dataArray = array(
            "diary" => $_SESSION['session_d_no'].$_SESSION['session_d_year'],
            "copy_category" => $json_data['app_type'],
            "application_reg_number" => '0',
            "application_reg_year" => '0',
            "application_receipt" => date('Y-m-d H:i:s'),
            "advocate_or_party" => '0',
            "court_fee" => $json_data['service_charges'] + $json_data['fee_in_stamp'],
            "delivery_mode" => $json_data['cop_mode'],
            "postal_fee" => number_format($json_data['postage'],2,".",""), //number_format($json_data['postage'], 2),
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
            "crn" => $OrderBatchMerchantBatchCode,
            "email" => $_SESSION["applicant_email"],
            "authorized_by_aor" => $_SESSION['session_authorized_bar_id'] > 0 ? $_SESSION['session_authorized_bar_id'] : '0',
            "allowed_request" => $allowed_request,
            "token_id" => '',
            "address_id" => $json_data['address_id']
        );

        $insert_application = insert_copying_application_online($dataArray); //insert application
        $json_insert_application = $insert_application;
        $json_insert_application->{'Status'};
        if ($json_insert_application->{'Status'} == "success") {

            $last_application_id = $json_insert_application->{'last_application_id'};


        $copy_detail = explode("#", $json_data['copy_detail']);
        $count_copy_details = count($copy_detail) - 1;

        for ($var = 0; $var < $count_copy_details; $var++) {
            $explode_copy_detail = explode(",", $copy_detail[$var]);

            //var_dump($copy_detail[$var]);
            $order_date = $explode_copy_detail[0];
            $order_pages = $explode_copy_detail[1];
            $spjudgementordercode = $explode_copy_detail[2];
            $spjudgementorder = $explode_copy_detail[3];
            $order_file_path = $explode_copy_detail[4];
            //order date and no. of pages and no. of copy required
            $document_array=array();
            $document_array = array(
                'order_type' => $spjudgementordercode,
                'order_date' => date('Y-m-d', strtotime($order_date)),
                'copying_order_issuing_application_id' => $last_application_id,
                'number_of_copies' => $json_data['num_copy'],
                'number_of_pages_in_pdf' => $order_pages,
                'path' => $order_file_path,
                'from_page' => 1,
                'to_page' => $order_pages,
                'order_type_remark' => '',
                'is_bail_order' => 'N',
            );
            $insert_application_documents = insert_copying_application_documents_online($document_array); //insert user assets
            $json_insert_application_documents = $insert_application_documents;


            //var_dump($json_insert_application_documents);
            if ($json_insert_application_documents->{'Status'} == "success") {

            }
            else{
                $array = array('status' => 'Unable to insert records');
            }
        }
        //exit();
        //OrderContent different for each head
        ////InstallationId given by pfms is unique for sci
        //installation id = 10017 new created
        $request = array(
            "DepartmentCode" => DEPARTMENT_CODE_LIVE,
            "OrderBatchTransactions" => "$loop_for_batch",
            "OrderBatchMerchantBatchCode" => "$OrderBatchMerchantBatchCode",
            "OrderBatchTotalAmount" => number_format($_SESSION['session_total_amount_to_pay'],2,".",""), //number_format($_SESSION['session_total_amount_to_pay'], 2),
            "InstallationId" => INSTALLATION_ID_LIVE,
            "OrderCode" => "$OrderBatchMerchantBatchCode",
            "CartDescription" => CART_DESCRIPTION_FOR_SERVICE_CHARGES,
            "OrderContent" => ORDER_CONTENT_LIVE,
            "PaymentTypeId" => PAYMENT_TYPE_ID_LIVE,
            "PAOCode" => PAO_CODE,
            "DDOCode" => DDO_CODE,
            "MultiHeadArray" => $child_request,
            "PaymentMethodMode" => "Online",
            "ShopperEmailAddress" => $_SESSION["applicant_email"],
            "ShippingFirstName" => $first_name,
            "ShippingLastName" => $second_name,
            "ShippingAddress1" => $postal_add,
            "ShippingAddress2" => "",
            "ShippingPostalCode" => $pincode,
            "ShippingCity" => $city,
            "ShippingStateRegion" => $district,
            "ShippingState" => $state,
            "ShippingCountryCode" => $country,
            "ShippingMobileNumber" => $_SESSION["applicant_mobile"],
            "BillingFirstName" => "",
            "BillingLastName" => "",
            "BillingAddress1" => "",
            "BillingAddress2" => "",
            "BillingPostalCode" => "",
            "BillingCity" => "",
            "BillingStateRegion" => "",
            "BillingState" => "",
            "BillingCountryCode" => "",
            "BillingMobileNumber" => "",
            "clientIP" => "$clientIP",
            "serviceUserID" => "$scipay"
        );

       $signedXML = bharatKoshRequest($request);
        $params = session_get_cookie_params();
            setcookie(session_name(), $_COOKIE[session_name()], time() + 60*60*24*30, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        ?>
        Redirecting to Payment Gateway ...
        <form id="myform" name="myform" target="_self" action="https://bharatkosh.gov.in/bkepay" method="post">
            <input type="hidden" name="bharrkkosh" value="<?= $signedXML; ?>">
            <input type="submit" name="name" value="CLICK" style="visibility: hidden;"/>
        </form>
        <script>
            window.onload = function () {
                document.forms['myform'].submit();
            }
        </script>

        <!--            <script type="text/javascript" src="js/jquery-1.9.1.js"></script>
                        <script type="text/javascript" src="js/jquery_redirect.js"></script>-->
        <!--            <script>
                        var signedXML = '';
                        $.redirect("https://bharatkosh.gov.in/bkepay", {bharrkkosh: signedXML});
                        </script> -->
        <?php
    }
    else{
        $array = array('status' => 'Unable to insert records');
    }
    }
    else{
            $array = array('status' => 'Permission Denied');
        }
  

    exit();        
            
    
}
if ($cop_mode == 1 OR $cop_mode == 2 OR $cop_mode == 3) {

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

    if( (($_SESSION['session_total_amount_to_pay'] == '0' OR $_SESSION['session_total_amount_to_pay'] == '0.00') && $cop_mode == 3 && $app_type == 5) || $bail_order == 'Y') {

        $copy_detail_temp = explode("#", $copy_detail);
        $count_copy_details = count($copy_detail_temp) - 1;

        $diary_no = $_SESSION['session_d_no'].$_SESSION['session_d_year'];
        //email & digital copy or first time bail order
        if($bail_order == 'Y') {
            //VALIDAITONS IF Ist BAIL ORDER THEN CHECK
            /*
            1. HE IS A AOR OR AUTHENTICATED BY AOR
            2. CHECK FROM SERVER SIDE, Is it Ist BAIL ORDER
            3. ENSURE SINGLE ORDER SELECTED
            4. ENSURE NUMBER OF COPIES REQUIRED SHOULD BE 1
            5. DELIVERY MODE TO BE COUNTER OR SPEED POST

            */
            $is_bail_applied = getBailApplied($diary_no, $_SESSION['applicant_mobile'], $_SESSION['applicant_email']);

            if ($_SESSION["session_filed"] != 1 && $_SESSION["session_filed"] != 6) {
                //VALIDATION 1. if not a AOR OR AUTHENTICATED BY AOR
                echo "You are not a AOR & not eligible to get Ist Bail Order fress of charge, GO BACK.";
                exit();
            }
            else if ($is_bail_applied != 'NO') {
                //2. CHECK FROM SERVER SIDE, Is it Ist BAIL ORDER
                echo "You have already availed Ist Bail Order free of charge, GO BACK.";
                exit();
            }
            else if ($count_copy_details > 1) {
                //3. ENSURE SINGLE ORDER SELECTED
                echo "Multiple selection of documents not allowed, GO BACK.";
                exit();
            }
            else if ($num_copy != 1) {
                //4. ENSURE NUMBER OF COPIES REQUIRED SHOULD BE 1
                echo "You are not eligible for $num_copy copies, GO BACK.";
                exit();
            }
            else if($cop_mode == 3 || $app_type == 5){
                //5. DELIVERY MODE TO BE COUNTER OR SPEED POST
                echo "Selected delivery mode not allowed, GO BACK.";
                exit();
            }
            else{
                $scipay = 10008; // free copy : ex:Ist bail order
                $allowed_request = "free_copy";
            }
        }
        else{
            $scipay = 10003; //digital copy
            $allowed_request = "digital_copy";
        }



        $create_crn = createCRN($scipay);//create crn
        $json_crn = $create_crn;
        if ($json_crn->{'Status'} == "success") {
            $OrderBatchMerchantBatchCode = $json_crn->{'CRN'};

            $clientIP = getClientIP();
            $dataArray = array(
                "diary" => $_SESSION['session_d_no'] . $_SESSION['session_d_year'],
                "copy_category" => $app_type,
                "application_reg_number" => '0',
                "application_reg_year" => '0',
                "application_receipt" => date('Y-m-d H:i:s'),
                "advocate_or_party" => '0',
                "court_fee" => '0',
                "delivery_mode" => $cop_mode,
                "postal_fee" => '0',
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
                "name" => $first_name . ' ' . $second_name,
                "mobile" => $_SESSION["applicant_mobile"],
                "address" => $postal_add . ' ' . $city . ' ' . $district . ' ' . $state . ' ' . $country . ' ' . $pincode,
                "application_number_display" => '',
                "temp_id" => '',
                "remarks" => '',
                "source" => '6',
                "send_to_section" => 'f',
                "crn" => $OrderBatchMerchantBatchCode,
                "email" => $_SESSION["applicant_email"],
                "authorized_by_aor" => $_SESSION['session_authorized_bar_id'] > 0 ? $_SESSION['session_authorized_bar_id'] : '0',
                "allowed_request" => $allowed_request,
                "token_id" => '',
                "address_id" => $address_id
            );

            $insert_application = insert_copying_application_online($dataArray); //insert application
            $json_insert_application = $insert_application;
            if ($json_insert_application->{'Status'} == "success") {
                $last_application_id = $json_insert_application->{'last_application_id'};
                $array = array('status' => 'success');
                $copy_detail_temp = explode("#", $copy_detail);
                $count_copy_details = count($copy_detail_temp) - 1;
                for ($var = 0; $var < $count_copy_details; $var++) {
                    $explode_copy_detail = explode(",", $copy_detail_temp[$var]);
                    $order_date = $explode_copy_detail[0];
                    $order_pages = $explode_copy_detail[1];
                    $spjudgementordercode = $explode_copy_detail[2];
                    $spjudgementorder = $explode_copy_detail[3];
                    $order_file_path = $explode_copy_detail[4];
                    //order date and no. of pages and no. of copy required
                    $document_array = array();
                    $document_array = array(
                        'order_type' => $spjudgementordercode,
                        'order_date' => date('Y-m-d', strtotime($order_date)),
                        'copying_order_issuing_application_id' => $last_application_id,
                        'number_of_copies' => $num_copy,
                        'number_of_pages_in_pdf' => $order_pages,
                        'path' => $order_file_path,
                        'from_page' => 1,
                        'to_page' => $order_pages,
                        'order_type_remark' => '',
                        'is_bail_order' => $bail_order == 'Y' ? 'Y' : 'N'
                    );

                    $insert_application_documents = insert_copying_application_documents_online($document_array); //insert user assets
                    $json_insert_application_documents = $insert_application_documents;
                    if ($json_insert_application_documents->{'Status'} == "success") {
                        //  $array = array('status' => 'success');
                    } else {
                        $array = array('status' => 'Unable to insert records');
                        exit();
                    }
                }

            } else {
                $array = array('status' => 'Permission Denied');
                exit();
            }

        }
    echo $array['status'];
    }

    $random_string=time();
?>
<form target="_self" method="post">
   <input type="hidden" name="posted_values" value="<?= $first_post_base64string; ?>">
   <div class="card">
      <div class="card-body">
         <div class="text-center">
            <h5>SUPREME COURT OF INDIA<BR>COPYING REQUISITION <?php if($bail_order == 'Y') { echo ' : <span class="text-danger">FIRST BAIL ORDER</span>'; } ?></u></h5>
            <h6><?= $_SESSION['session_cause_title']; ?></h6>
            <strong><?= $_SESSION['session_case_no']; ?></strong>
         </div>
      </div>
   </div>
   <div class="card">
      <div class="card-header bg-primary text-white font-weight-bolder">Copying Details</div>
      <div class="card-body">
         <div class="form-row">
            <div class="col-md-12">
               <div class="row ">
                  <div class="col-md-4 font-weight-bolder">
                     <label>Application Date:</label>
                  </div>
                  <div class="col-md-8">
                     <p class="text-left"><?= date('d-m-Y H:i:s'); ?></p>
                  </div>
               </div>
            </div>
            <div class="col-md-12">
               <div class="row">
                  <div class="col-md-4 font-weight-bolder">
                     <label>Application Category:</label>
                  </div>
                  <div class="col-md-8">
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
                        $copy_detail_temp = explode("#", $copy_detail);
                        $count_copy_details = count($copy_detail_temp) - 1;
                        
                        for($var = 0; $var<$count_copy_details; $var++){
                            $explode_copy_detail = explode(",", $copy_detail_temp[$var]);
                            echo $explode_copy_detail[3]." Order/File Date ".$explode_copy_detail[0]." Pages ".$order_pages = $explode_copy_detail[1]."<br>";
                        }
                        ?></p>
                  </div>
               </div>
            </div>
         </div>
         <div class="form-row">
            <div class="col-md-12">
               <div class="row">
                  <div class="col-md-4 font-weight-bolder">
                     <label>No. of Copies:</label>
                  </div>
                  <div class="col-md-8">
                     <p><?=$num_copy;?></p>
                  </div>
               </div>
            </div>
            <div class="col-md-12">
               <div class="row">
                  <div class="col-md-4 font-weight-bolder">
                     <label>Fee + Charges:</label>
                  </div>
                  <div class="col-md-8">
                     <p>
                        <?php
                           if($bail_order == 'Y'){
                               echo "N.A.";
                           }
                           else{
                               echo "Rs. ".number_format($_SESSION['session_total_amount_to_pay'],2)."/-";
                           }
                           ?>
                     </p>
                  </div>
               </div>
            </div>
            <div class="col-md-12">
               <div class="row">
                  <div class="col-md-4 font-weight-bolder">
                     <label>Delivery Mode:</label>
                  </div>
                  <div class="col-md-8">
                     <p><?php 
                        if($cop_mode == 1){
                            echo "By Speed Post";
                        }
                        if($cop_mode == 2){
                            echo "Counter";
                        }
                        if($cop_mode == 3){
                            echo "Email";
                        }
                        ?></p>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="card">
      <div class="card-header bg-primary text-white font-weight-bolder">Applicant Details</div>
      <div class="card-body">
         <div class="form-row">
            <div class="col-md-12">
               <div class="row">
                  <div class="col-md-4 font-weight-bolder">
                     <label>Applying As:</label>
                  </div>
                  <div class="col-md-8">
                     <p><?php 
                        if($_SESSION["session_filed"] == 1){
                            echo "Adovcate on Record";
                        }
                        if($_SESSION["session_filed"] == 2){
                            echo "Party of the case";
                        }
                        if($_SESSION["session_filed"] == 3){
                            echo "Appearing Counsel";
                        }
                        if($_SESSION["session_filed"] == 4){
                            echo "Third Party";
                        }
                        if($_SESSION["session_filed"] == 6){
                            echo "Authorized by AOR";
                        }                
                        ?></p>
                  </div>
               </div>
            </div>
            <div class="col-md-12">
               <div class="row">
                  <div class="col-md-4 font-weight-bolder">
                     <label>Mobile No.:</label>
                  </div>
                  <div class="col-md-8">
                     <p><?=$_SESSION["applicant_mobile"];?></p>
                  </div>
               </div>
            </div>
            <div class="col-md-12">
               <div class="row">
                  <div class="col-md-4 font-weight-bolder">
                     <label>Email:</label>
                  </div>
                  <div class="col-md-8">
                     <p><?=$_SESSION["applicant_email"];?></p>
                  </div>
               </div>
            </div>
         </div>
         <div class="form-row">
            <div class="col-md-12">
               <div class="row">
                  <div class="col-md-4 font-weight-bolder">
                     <label>Name:</label>
                  </div>
                  <div class="col-md-8">
                     <p><?= $first_name.' '.$second_name; ?></p>
                  </div>
               </div>
            </div>
            <div class="col-md-12">
               <div class="row">
                  <div class="col-md-4 font-weight-bolder">
                     <label>Address:</label>
                  </div>
                  <div class="col-md-8">
                     <p><?= $postal_add . ' ' . $city . ' ' . $district . ' ' . $state . ' ' . $country . ' ' . $pincode; ?></p>
                  </div>
               </div>
            </div>
         </div>
         <div class="form-row text-right">
            <div class="col-md-12">
               <?php
                  //if($cop_mode != 3 && $app_type != 5 && $bail_order != 'Y'){
                  if($bail_order == 'Y'){
                      ?><span class="text-success">Your application submitted successfully.</span><?php
                  }
                  else if($_SESSION['session_total_amount_to_pay'] > 0){
                  ?>
               <input style="background-color: #186329; color:#FFFFFF;" type="submit" name="name" value="CLICK TO PAY RS. <?=$_SESSION['session_total_amount_to_pay'];?>" class="btn"/>
               <?php }
                  else{
                      ?><span class="text-success">Your application submitted successfully.</span><?php
                  }?>
            </div>
         </div>
      </div>
   </div>
</form>
    <script type="text/javascript">
        function disableF5(e) { if ((e.which || e.keyCode) == 116 || (e.which || e.keyCode) == 82) e.preventDefault(); };

        $(document).ready(function(){
            $(document).on("keydown", disableF5);
        });
    </script>
            <?php

}
else{
    echo "Error";
}
?>
@endsection