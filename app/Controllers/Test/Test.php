<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 25/1/21
 * Time: 12:18 PM
 */

namespace App\Controllers\Test;

use App\Controllers\BaseController;
use App\Controllers\ShilclientController;

class Test extends BaseController {
    protected $encrypt;
    protected $TestModel;
    protected $schil;
    public function __construct()
    {
        parent::__construct();
        $this->encrypt = \Config\Services::encrypter();
        $this->schil = new ShilclientController();
    }
    public function index(){
        $msg = 'My secret message';
        $key = config('Encryption')->key;
        $encrypted_string = $this->encrypt->encrypt($msg, $key);

        //echo $encrypted_string = $this->encrypt->decode($encrypted_string, $key);
        //var_dump($encrypted_string);
        //http://localhost/out_service/index.php/filing/do_generate_diary/
        $or_request_params['diaryIds'] = $encrypted_string;
        echo 'http://localhost/out_service/index.php/filing/do_generate_diary/?'.http_build_query($or_request_params);
        echo file_get_contents('http://localhost/out_service/index.php/filing/do_generate_diary/?'.http_build_query($or_request_params));
        //echo $or_response = json_decode(file_get_contents('http://localhost/out_service/index.php/filing/do_generate_diary/?'.http_build_query($or_request_params)));
    }
    public function testSMS(){
       echo $registration_id = $_SESSION['efiling_details']['registration_id'];
       echo $efiling_no = $_SESSION['efiling_details']['efiling_no'];
       echo '<br>';
        $result=send_whatsapp_message($registration_id,$efiling_no,"generated from your efiling account & still pending for final submit.");

        var_dump($result); exit();

        //send_whatsapp_message($result['registration_id'],$efiling_num,"generated from your efiling account & still pending for final submit.");

        //$text="On the 28th of January, 1950, two days after India became a Sovereign Democratic Republic, the Supreme Court came into being. The inauguration took place in the Chamber of Princes in the Parliament building which also housed India's Parliament, consisting of the Council of States and the House of the People. It was here, in this Chamber of Princes, that the Federal Court of India had sat for 12 years between 1937 and 1950. This was to be the home of the Supreme Court";
        //$text="Video Conferenecing link for Reg. Virtual Court 2 at 2:00 PM on 09-03-2021 is https://sci.vcmeet.nic.in/join/sJWHjfpJmE";
        $text="Efiling No. ECSCIN01001342021 generated from your efiling account & still pending for final submit. - Supreme Court of India";
        $text="OTP for E-mentioning Diary No. 12/2021 is 123456 and is valid till 2021-03-15 12:03:05. Do not share the OTP with anyone. - Supreme Court of India";
        //$text="eFiling no. ECSCIN01001342021 has been disapproved. Please cure the notified defects through eFiling portal. - Supreme Court of India";
                //Efiling No. {#var#} generated from your efiling account & still pending for final submit. - Supreme Court of India
        //echo strlen($text).'<br/>';

        //$result=sendSMS(38,"8860012863,9871427893,8766204486",$text." . - Supreme Court of India","1107161234264170741");
        $text="Your case EC-SCIN01-00141-2021 is filed with Diary No. 123/2021 on 10-04-2021 10:23:16. - Supreme Court of India";
        $result=sendSMS(38,"8860012863",$text,SCISMS_Case_Filed_Diary_No);
        //1107161243622980738

        print_r($result);
    }
    public function testLowerCourt($registrationId){
        echo $this->TestModel->get_subordinate_court_without_cis_master($registrationId);
    }


    public function test_pay(){
        $mobile='9891713636';
        $email = 'test@test.com';
        $name='Mohit Jain';
        $usercode=4217;
        $responseSuccessURL = $GLOBALS['base_url']."/response-success-temp";
        $responseFailURL = $GLOBALS['base_url']."/response-fail";
        $total_amt=100;
        $date=date('d-M-Y H:i:s') ;
        $txnid=date('dMYHis')."_".'111';
        $data="dlsupcourt"."ourtdls"."NA"."EPS-DL-002".$txnid.$total_amt."0".$date;
        $hash=hash_hmac('sha512',$data,"1013061358req985191802");

        echo ' <form method="post" id="payment_form" action="https://www.shcileservices.com/OnlineE-Payment/sEpsePmtTrans">
                           <input type="hidden" name="login" value="dlsupcourt" />
                           <input type="hidden" name="pass" value="ourtdls" />
                           <input type="hidden" name="prodid" value="EPS-DL-002" />
                           <input type="hidden" name="txnid" value="'.$txnid .'" />
                           <input type="hidden" name="txnType" value="NA" />
                           <input type="hidden" name="amt" value="'.$total_amt.'" />
                           <input type="hidden" name="scamt" value="0" />
                           <input type="hidden" name=txndate value="'.$date.'" />
                           <input type="hidden" name="ru" value="'.$responseSuccessURL.'"/>
                           <input type="hidden" name="signature" value="'.$hash .'" />
                          <input type="hidden" name="udf1"  value="efiling" />
                           <input type="hidden" name="udf2"  value="'.$mobile.'" />
                           <input type="hidden" name="udf3"  value="'.$email.'" />
                           <input type="hidden" name="udf4"  value="0" />
                           <input type="hidden" name="udf5"  value="'.$total_amt.'" />
                           <input type="hidden" name="udf6"  value="efiling" />
                           <input type="hidden" name="udf7"  value="'.$name.'" />
                            <input type="hidden" name="udf8"  value="efiling" />
                             <input type="hidden" name="udf9"  value="efiling" />
                             <input type="hidden" name="addInfo"  value="efiling" />

                           <input type="submit" name="btn_payment" id="btn_payment" value="Make Payment"/>
 </form>';
    }


    public function test_pay_response($order_id){
        //$partyTxnId=trim('13Feb2023121934_29921_36812023');
        $partyTxnId=trim($order_id);

        $data = array(
            "login"=>"dlsupcourt",
            "partyTxnId" => $partyTxnId
        );

        $payload = json_encode($data);
        $ch = curl_init('https://www.shcilestamp.com/eCourtFee/japi/getEpsTxnStatus');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $s=date('d-M-Y-H:i:s');;
        $key="13Y21A12M31P07M";
        $sig = hash_hmac('sha256', $s, $key);
        $headers = [
            "Content-Type: application/json",
            "x-shcil-req-id: ".$s,
            "x-shcil-signature:".$sig,
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $response=json_decode($result);
        //$tid=trim($response->shclPmtRef);
        //$tidnew=trim($response->shclTxnId);
        //$fr=trim($response->desc);
        print_r($response);
    }



    public function test_pay_response2($partyTxnId, $amt){
        //$partyTxnId=trim('13Feb2023121934_29921_36812023');
        $partyTxnId=trim($partyTxnId);
        $post_param = "login=dlsupcourt&txnid=" . $partyTxnId. "&amt=" . $amt;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.shcileservices.com/OnlineE-Payment/sEpsGetTransStatus');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_param);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $InputArray = (array) simplexml_load_string($result);
        $InputArray = $InputArray['@attributes'];
        var_dump($InputArray);
    }

    public function check_challan($challan=null){
        if (empty($challan)){
            echo 'Challan Number is required ';exit();
        }
        $cert_response = $this->schil->get_response('request', array('certificate_number'=>$challan));
        echo '<pre>';print_r($cert_response);
        //var_dump($cert_response);
    }

    public function checkSMS(){
        
        echo $smsText = "Your eFiling Password fgdfg The login username has been sent to your registered email account. - Supreme Court of India";
        echo "<br/>";
        $response = sendSMS(38,'9953480625', $smsText,SCISMS_Login_Password_To_Email);
        print_r($response);
        die;
    }

    public function checkSMSMail(){
        
        // echo $smsText = "registration code. Verify Registration Code- SUPREME COURT OF INDIA";
        echo $smsText = 'Dear Punit Kumar Sharma, Your email is shashank.jain@aaatechnologies.co.in and password is aAbBcCdDeE <br><a href="' . base_url() . '">Click To Login</a>';
        echo "<br/>";
        $subject = "Login credentials";
        $response = send_mail_msg('shashank.jain@aaatechnologies.co.in', $subject, $smsText, $to_user_name = "arguing_counsel");

        // $response = sendSMS(38,'9009043550', $smsText,SCISMS_Login_Password_To_Email);
        // $response = sendSMS('9009043550', $smsText);
        // $response = send_mobile_sms('9009043550', $smsText);
        print_r($response);
        die;
    }

    public function send_whatsapp_message_test(){
        send_whatsapp_message_test(184087, 'ECSCIN01007432025', $sms_text = null);
    }

}