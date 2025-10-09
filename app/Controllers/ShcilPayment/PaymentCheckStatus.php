<?php

namespace App\Controllers\ShcilPayment;

use App\Controllers\BaseController;
use App\Models\ShcilPayment\PaymentModel;

class PaymentCheckStatus extends BaseController {

    protected $Payment_model;
    protected $validation;

    public function __construct() {
        parent::__construct();
        $this->Payment_model = new PaymentModel();
        $this->validation = \Config\Services::validation();
    }

    public function index() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_ADMIN,AMICUS_CURIAE_USER);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {
            $order_details = explode('$$', url_decryption($_POST['order_id']));
            // 0 => registration_id, 1 => order_no , 2 => received_amt
            if (count($order_details) != 3) {
                echo 'ERROR|Data tempered';
                $_SESSION['MSG'] = message_show("fail", htmlentities('Data tempered .', ENT_QUOTES));
                exit(0);
            }
            // $order_details[2] = 113;
            // $order_details[1] = '8567200404090538';
            $pg_params = json_decode($_SESSION['estab_details']['payment_gateway_params'], true);
            $post_param = "login=" . $pg_params['login'] . "&txnid=" . $order_details[1] . "&amt=" . $order_details[2];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, STOCK_HOLDING_PAYMENT_STATUS_URL);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_param);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            $InputArray = (array) simplexml_load_string($result);
            $InputArray = $InputArray["@attributes"];
            curl_close($ch);
            if(isset($InputArray['txnStatus']) && $InputArray['txnStatus'] == 'DATA NOT FOUND'){
                return 'DATA NOT FOUND.';
                $_SESSION['MSG'] = message_show("fail", htmlentities('DATA NOT FOUND.', ENT_QUOTES));
            } else {
                $InputArray['settledate'] = ($InputArray['settledate'] == '-')? NULL : $InputArray['settledate'];
                $rules=[
                    "reconstatus" => [
                        "label" => "reconstatus",
                        "rules" => "in_list[RP,RS]"
                    ],
                    "login" => [
                        "label" => "login",
                        "rules" => "max_length[30]|alpha_numeric"
                    ],
                    "amt" => [
                        "label" => "amt",
                        "rules" => "min_length[1]|max_length[5]|numeric"
                    ],
                    "txnStatus" => [
                        "label" => "txnStatus",
                        "rules" => "max_length[10]|in_list[SUCCESS,PENDING,FAILED,INITIATED]"
                    ],
                ];
                // if ($this->validate($rules) === FALSE) {
                //     $error_array = $this->validation->getErrors();
                //     $error_json = array('response_error_msg' => json_encode($error_array));
                //     $this->Payment_model->updatePayment($order_details[0], $order_details[1], $error_json);
                //     $_SESSION['MSG'] = message_show("fail", "Invalid data in Payment response !");
                //     return redirect()->to(base_url('dashboard'));
                //     exit(0);
                // }
                
                $order_num = $InputArray['txnid'];
                $transaction_num = $InputArray['shcltxnid'];
                $transaction_date = $InputArray['settledate'];
                $received_amount = $InputArray['amt'];
                $shciltxnstatus = strtolower($InputArray['txnStatus']);
                if (strtolower($shciltxnstatus) == 'success') {
                    $payment_status = 'Y';
                    $sentSMS = "Fee received successfully for Efiling No. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " with Receipt No. " . $transaction_num . ", of amount Rs. " . $received_amount . "/-. - Supreme Court of India ";
                    $is_already_paid_court_fee=$this->Payment_model->is_already_paid_court_fee($order_details[0], $order_details[1]);
                    if (empty($is_already_paid_court_fee)){
                        send_whatsapp_message($_SESSION['efiling_details']['registration_id'],$_SESSION['efiling_details']['efiling_no']," Transaction Number: " . $transaction_num . ", Amount Rs. " . $received_amount . "/-. SUCCESSFULLY RECEIVED");
                    }
                    $smsTemplate=SCISMS_Payment_Success;
                    $subject = "Payment Success : Efiling No. " . efile_preview($_SESSION['efiling_details']['efiling_no']);
                } elseif (strtolower($shciltxnstatus) == 'pending') {
                    $payment_status = 'P';
                } elseif (strtolower($shciltxnstatus) == 'failed') {
                    $payment_status = 'F';

                    $sentSMS = "Fee payment for Efiling No. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " with Receipt No. " . $transaction_num . ",  of amount Rs. " . $received_amount . " failed due to some technical reason. If amount is deducted from your bank account, please check with your bank before retry. - Supreme Court of India";
                    send_whatsapp_message($_SESSION['efiling_details']['registration_id'],$_SESSION['efiling_details']['efiling_no']," Transaction Number: " . $transaction_num . ", Amount Rs. " . $received_amount . "/-.  FAILED. Please check your bank account for any deduction before retrying to make payment");
                    $smsTemplate=SCISMS_Payment_failed;
                    $subject = "Payment Failed : Efiling No. " . efile_preview($_SESSION['efiling_details']['efiling_no']);
                } elseif (strtolower($shciltxnstatus) == 'initiated') {
                    $payment_status = 'P';
                } else {
                    $payment_status = 'P';
                }
                $curr_dt_time = date('Y-m-d H:i:s');
                $save_payment_response = array(
                    'payment_status' => $payment_status,
                    'banktxnid' => $InputArray['banktxnid'],
                    'pgsptxnid' => $InputArray['pgsptxnid'],
                    'received_amt' => $received_amount,
                    'transaction_num' => $transaction_num,
                    'bank_name' => $InputArray['bank_name'],
                    'transaction_date' => $transaction_date,
                    'txn_status_update_method' => 'T',
                    'txn_status_updated_on' => $curr_dt_time,
                    'shcilpmtref' => $InputArray['shcilpmtref'],
                    'shciltxnstatus' => $shciltxnstatus,
                    'shcildesc' => isset($InputArray['desc']) ? $InputArray['desc'] : '',
                    'reconstatus' => $InputArray['reconstatus'],
                    'pg_track_response' => json_encode($InputArray)
                );
            

                if ($payment_status == 'P') {
                    $_SESSION['MSG'] = message_show("fail", htmlentities('Payment status still pending.', ENT_QUOTES));
                } else {
                    $result = $this->Payment_model->updatePayment($order_details[0], $order_details[1], $save_payment_response);
                    if ($result) {

                        $user_name = $_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'];
                        send_mobile_sms($_SESSION['login']['mobile_number'], $sentSMS,$smsTemplate);
                        send_mail_msg($_SESSION['login']['emailid'], $subject, $sentSMS, $user_name);


                        echo 'SUCCESS|Status Successfully Updated.';
                        $_SESSION['MSG'] = message_show("success", htmlentities('Status updated successfully.', ENT_QUOTES));
                    } else {
                        echo 'ERROR|Something wrong';
                        $_SESSION['MSG'] = message_show("fail", htmlentities('Error in status update .', ENT_QUOTES));
                    }
                }
            }
        }
    }

}
