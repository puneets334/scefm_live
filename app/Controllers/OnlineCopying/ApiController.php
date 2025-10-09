<?php
namespace App\Controllers\OnlineCopying;
use App\Controllers\BaseController;
use App\Models\OnlineCopying\CommonModel;
use Config\Database;
use App\Libraries\webservices\Ecoping_webservices;
use TCPDF;

class ApiController extends BaseController
{

    protected $session;
    protected $Common_model;
    protected $db2;
    protected $db3;
    protected $ecoping_webservices;

    public function __construct()
    {
        parent::__construct();
        $this->ecoping_webservices=new Ecoping_webservices();        
        $this->Common_model = new CommonModel();        
        
        //$this->checkUserofECopying();

        //unset($_SESSION['MSG']);
        //unset($_SESSION['msg']);
    }
    public function api_bharatkosh_payments(){


        $request = \Config\Services::request();
        // Get the POST data
        $amountToPay = $request->getPost('amount_to_pay');
        $serviceKey = $request->getPost('service_key');
        $applicantEmail = $request->getPost('applicant_email');
        $firstName = $request->getPost('first_name');
        $secondName = $request->getPost('second_name');
        $address = $request->getPost('address');
        $pincode = $request->getPost('pincode');
        $city = $request->getPost('city');
        $district = $request->getPost('district');
        $state = $request->getPost('state');
        $country = $request->getPost('country');
        $applicantMobile = $request->getPost('applicant_mobile');
        $serviceCharges = $request->getPost('service_charges');
        $feeInStamp = $request->getPost('fee_in_stamp');
        $postage = $request->getPost('postage');
        $loop_for_batch = $request->getPost('loop_for_batch');
        $application_ref_id=$request->getPost('application_ref_id');
        // Validate input
        if(!$amountToPay || !$serviceKey) {
            return "Not a valid data (B)";
        }
        // Get client IP
        $clientIP = $request->getIPAddress();
        // Load the model
        //$model = new BharatKoshModel();
        // Check if the service exists and the IP is allowed
        $service=$this->ecoping_webservices->getserviceIDAccordingIP($serviceKey,$clientIP);
        
        if(!$service) {
            return "Not a valid request (A)";
        }
        // Create CRN Payment
        $createCRN = createCRN($serviceKey); // Assuming this function is defined elsewhere
        $jsonCRN = $createCRN;
        if ($jsonCRN->Status !== "success") {
            return $jsonCRN->Status;
        }
         $OrderBatchMerchantBatchCode =  $jsonCRN->{'CRN'};
                $clientIP = getClientIP();
                $child_request = array();
                $loop_for_batch = 0;
                if ($serviceCharges > 0) {
                    $loop_for_batch++;
                }
                if ($feeInStamp > 0) {
                    $loop_for_batch++;
                }
                if ($postage > 0) {
                    $loop_for_batch++;
                }
                $orderBatchMerchantBatchCode = $jsonCRN->CRN;
                if($serviceKey == 10010){
                    //for rti
                    //TODO::to be change as per rti payment id
                    $orderContentId = ORDER_CONTENT_RTI;
                    $PaymentTypeIdForServiceCharges = PAYMENT_TYPE_ID_RTI;
                    $PaymentTypeIdForFeeInStamp = PAYMENT_TYPE_ID_FEE_STAMP_RTI;
                    $PaymentTypeIdForPostage = PAYMENT_TYPE_ID_POSTAGE_RTI;
                    $CartDescriptionForServiceCharges = CART_DESCRIPTION_FOR_SERVICE_CHARGES;
                    $CartDescriptionForFeeInStamp = CART_DESCRIPTION_FOR_FEE_STAMP_RTI;
                    $CartDescriptionForPostage = CART_DESCRIPTION_FOR_POSTAGE_RTI;
                }
                else{
                    //for copying
                    $orderContentId = ORDER_CONTENT_LIVE;
                    $PaymentTypeIdForServiceCharges = PAYMENT_TYPE_ID_LIVE;
                    $PaymentTypeIdForFeeInStamp = PAYMENT_TYPE_ID_FEE_STAMP;
                    $PaymentTypeIdForPostage = PAYMENT_TYPE_ID_POSTAGE;
                    $CartDescriptionForServiceCharges = CART_DESCRIPTION_FOR_SERVICE_CHARGES;
                    $CartDescriptionForFeeInStamp = CART_DESCRIPTION_FOR_FEE_STAMP_COPYING;
                    $CartDescriptionForPostage = CART_DESCRIPTION_FOR_POSTAGE_COPYING;
                }
              // Prepare data for insertion
              $data = [
                'department_code' => DEPARTMENT_CODE_LIVE,
                'order_batch_transactions' => $loop_for_batch, // Assuming one transaction
                'order_batch_merchant_batch_code' => $orderBatchMerchantBatchCode,
                'order_batch_total_amount' => number_format($amountToPay, 2, ".", ""),
                'installation_id' => INSTALLATION_ID_LIVE,
                'order_code' => $orderBatchMerchantBatchCode,
                'cart_description' => CART_DESCRIPTION_FOR_SERVICE_CHARGES,
                'order_content' => ORDER_CONTENT_LIVE,
                'payment_type_id' => PAYMENT_TYPE_ID_LIVE,
                'pao_code' => PAO_CODE,
                'ddo_code' => DDO_CODE,
                'payment_method_mode' => 'Online',
                'shopper_email_address' => $applicantEmail,
                'shipping_first_name' => $firstName,
                'shipping_last_name' => $secondName,
                'shipping_address1' => $address,
                'shipping_postal_code' => $pincode,
                'shipping_city' => $city,
                'shipping_state_region' => $district,
                'shipping_state' => $state,
                'shipping_country_code' => $country,
                'shipping_mobile_number' => $applicantMobile,
                'billing_first_name' => '',
                'billing_last_name' => '',
                'billing_address1' => '',
                'billing_postal_code' => '',
                'billing_city' => '',
                'billing_state_region' => '',
                'billing_state' => '',
                'billing_country_code' => '',
                'client_ip' => $clientIP,
                'service_user_id' => $serviceKey,
                'application_id' => $application_ref_id // Assuming you store this in session
            ];
            // Insert into bharat_kosh_request
            //$model->insertRequest($data); // Assuming you create a method in your model for this
            $baratkoshresult=bharaKoshDataServiceRequest($data);
            if($baratkoshresult){
                $loop_for_multi_item = 0;
                    if ($_POST['service_charges'] > 0) {
                        $loop_for_multi_item++;
                        /*$statement_batch = $dbo->prepare('INSERT INTO bharat_kosh_request_batch (
                        order_batch_merchant_batch_code, order_code, amount, cart_description, order_content, payment_type_id)
                        VALUES (:OrderBatchMerchantBatchCode, :OrderCode, :amount, :CartDescription, :OrderContent, :PaymentTypeId)');*/

                        if ($loop_for_multi_item == 1) {
                            $new_order_batch_code = $OrderBatchMerchantBatchCode;
                        } else {
                            $new_order_batch_code = $OrderBatchMerchantBatchCode . "-" . $loop_for_multi_item;
                        }  
                        bharaKoshDataBatchServiceRequest([
                            "OrderBatchMerchantBatchCode" => "$OrderBatchMerchantBatchCode",
                            "OrderCode" => $new_order_batch_code,
                            "amount" => number_format($_POST['service_charges'],2,".",""),//number_format($json_data['service_charges'], 2),
                            "CartDescription" => "$CartDescriptionForServiceCharges",
                            "OrderContent" => $orderContentId, //9570 for production server //7220 For UAT server
                            "PaymentTypeId" => $PaymentTypeIdForServiceCharges //9528 for production server //3132 For UAT server
                        ]);
                        $child_request[] = array("ChildAmount" => number_format($_POST['service_charges'],2,".",""), //number_format($json_data['service_charges'], 2),
                            "ChildOrderCode" => $new_order_batch_code,
                            "ChildCartDescription" => "$CartDescriptionForServiceCharges",
                            "ChildOrderContent" => $orderContentId,  //9570 for production server //7220 For UAT server
                            "ChildPaymentTypeId" => $PaymentTypeIdForServiceCharges); //9528 for production server //3132 For UAT server


                        if ($feeInStamp > 0) {
                            //                            echo "fee in stamp";
                            $loop_for_multi_item++;
                            /*$statement_batch = $dbo->prepare('INSERT INTO bharat_kosh_request_batch (
                             order_batch_merchant_batch_code, order_code, amount, cart_description, order_content, payment_type_id)
                            VALUES (:OrderBatchMerchantBatchCode, :OrderCode, :amount, :CartDescription, :OrderContent, :PaymentTypeId)');*/
                            if ($loop_for_multi_item == 1) {
                                $new_order_batch_code = $OrderBatchMerchantBatchCode;
                            } else {
                                $new_order_batch_code = $OrderBatchMerchantBatchCode . "-" . $loop_for_multi_item;
                            }
                            bharaKoshDataBatchServiceRequest([
                                "OrderBatchMerchantBatchCode" => "$OrderBatchMerchantBatchCode",
                                "OrderCode" => $new_order_batch_code,
                                "amount" => number_format($_POST['fee_in_stamp'],2,".",""), // number_format($json_data['fee_in_stamp'], 2),
                                "CartDescription" => "$CartDescriptionForFeeInStamp",
                                "OrderContent" => $orderContentId, //9570 for production server //7220 For UAT server
                                "PaymentTypeId" => $PaymentTypeIdForFeeInStamp //9527 for production server //3132 For UAT server
                            ]);

                            $child_request[] = array("ChildAmount" =>  number_format($_POST['fee_in_stamp'],2,".",""), //number_format($json_data['fee_in_stamp'], 2),
                                "ChildOrderCode" => $new_order_batch_code,
                                "ChildCartDescription" => "$CartDescriptionForFeeInStamp",
                                "ChildOrderContent" => $orderContentId, //9570 for production server //7220 For UAT server
                                "ChildPaymentTypeId" => $PaymentTypeIdForFeeInStamp); //9527 for production server //3132 For UAT server

                        }
                        if ($postage > 0) {
                            $loop_for_multi_item++;
                            
                            if ($loop_for_multi_item == 1) {
                                $new_order_batch_code = $OrderBatchMerchantBatchCode;
                            } else {
                                $new_order_batch_code = $OrderBatchMerchantBatchCode . "-" . $loop_for_multi_item;
                            }
                            bharaKoshDataBatchServiceRequest([
                                "OrderBatchMerchantBatchCode" => "$OrderBatchMerchantBatchCode",
                                "OrderCode" => $new_order_batch_code,
                                "amount" =>  number_format($_POST['postage'],2,".",""), //number_format($json_data['postage'], 2),
                                "CartDescription" => "$CartDescriptionForPostage",
                                "OrderContent" => $orderContentId, //9570 for production server //7220 For UAT server
                                "PaymentTypeId" => $PaymentTypeIdForPostage //9525 for production server //3132 For UAT server
                            ]);
                            $child_request[] = array("ChildAmount" => number_format($_POST['postage'],2,".",""), //number_format($json_data['postage'], 2),
                                "ChildOrderCode" =>$new_order_batch_code,
                                "ChildCartDescription" =>"$CartDescriptionForPostage",
                                "ChildOrderContent" =>$orderContentId, //9570 for production server //7220 For UAT server
                                "ChildPaymentTypeId" =>$PaymentTypeIdForPostage); //9525 for production server //3132 For UAT server
                        }
                        
                      }
                      $data['child_request']=$child_request;
                      $data['OrderBatchMerchantBatchCode']=$OrderBatchMerchantBatchCode;
                      $data['CartDescriptionForServiceCharges']=$CartDescriptionForServiceCharges;
                      $data['orderContentId']=$orderContentId;
                      $data['PaymentTypeIdForServiceCharges']=$PaymentTypeIdForServiceCharges;
                      $data['loop_for_batch']=$loop_for_batch;
                      $data['clientIP']=$clientIP;
                      return $this->render('onlineCopying.api_payment_request',$data); 
                    }
    }
}