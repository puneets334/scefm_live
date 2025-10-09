<?php

use App\Models\PhysicalHearing\ConsentVCModel;
use App\Models\PhysicalHearing\AuditModel;
use App\Models\PhysicalHearing\HearingModel;

/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 6/2/20
 * Time: 7:04 PM
 */


// function get_client_ip() {
//     $ipaddress = '';
//     if (isset($_SERVER['HTTP_CLIENT_IP']))
//         $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
//     else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
//         $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
//     else if(isset($_SERVER['HTTP_X_FORWARDED']))
//         $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
//     else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
//         $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
//     else if(isset($_SERVER['HTTP_FORWARDED']))
//         $ipaddress = $_SERVER['HTTP_FORWARDED'];
//     else if(isset($_SERVER['REMOTE_ADDR']))
//         $ipaddress = $_SERVER['REMOTE_ADDR'];
//     else
//         $ipaddress = 'UNKNOWN';
//     return $ipaddress;
// }
// function getClientIP() {
// 	$ipaddress = '';
// 	if (isset($_SERVER['HTTP_CLIENT_IP']))
// 		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
// 	else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
// 		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
// 	else if(isset($_SERVER['HTTP_X_FORWARDED']))
// 		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
// 	else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
// 		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
// 	else if(isset($_SERVER['HTTP_FORWARDED']))
// 		$ipaddress = $_SERVER['HTTP_FORWARDED'];
// 	else if(isset($_SERVER['REMOTE_ADDR']))
// 		$ipaddress = $_SERVER['REMOTE_ADDR'];
// 	else
// 		$ipaddress = 'UNKNOWN';
// 	return $ipaddress;
// }
function convert_date($input_date, $required_format='Y-m-d'){
    return date($required_format, strtotime($input_date));
}
function array_date_formatter($array, $input_date_format, $required_date_format){
    foreach ($array as $k=>$v){
        if(!is_array($v)){
            foreach ($array as $key=>$value){
                $dt = is_string($value)?DateTime::createFromFormat($input_date_format, $value):false;
                if($dt !== false){
                    $array[$key]=date($required_date_format, strtotime($array[$key]));
                }
                else
                    continue;
            }
            return $array;
        }
        else{
            $sub_array = &$array[$k];
            $array[$k] = array_date_formatter($sub_array, $input_date_format, $required_date_format);
            return($array);
        }
    }
}
/*function array_date_formatter($array, $input_date_format, $required_date_format){
    foreach ($array as $k=>$v){
        if(!is_array($v)){
            foreach ($array as $key=>$value){
                $dt = DateTime::createFromFormat($input_date_format, $value);
                if($dt !== false){
                    $array[$key]=date($required_date_format, strtotime($array[$key]));
                }
                else
                    continue;
            }
        }
        else{
            $sub_array = &$array[$k];
            foreach ($sub_array as $key=>$value){
                $dt = DateTime::createFromFormat($input_date_format, $value);
                if($dt !== false){
                    $sub_array[$key]=date($required_date_format, strtotime($sub_array[$key]));
                }
                else
                    continue;
            }
        }
    }
    return $array;
}*/
function transpose($array) {
    $keys = array_keys($array);
    foreach ($array[$keys[0]] as $k => $v) {  // only iterate first "row"
        $result[] = array_combine($keys, array_column($array, $k));  // store each "column" as an associative "row"
    }
    return $result;
}
function isArrayEmpty($array) {
    foreach($array as $key => $val) {
        if (!empty($val))
            return false;
    }
    return true;
}
function removeKeyswithNullValues($array){
    foreach($array as $key=>$value){
        if(is_null($value) || $value == '')
            unset($array[$key]);
    }
    return $array;
}
if(!function_exists('sendOtp')){


function sendOtp($params=array())
	{
		// $CI = &get_instance();
		$mobile = '';
		$otp = rand(10000, 99999);
		$msg = false;
		// $CI->load->library('session');
        $session = \Config\Services::session();
		//$CI->session->set_userdata('otp', $otp);
		if (isset($params['mobile']) && !empty($params['mobile'])) {
			$_SESSION['otp']=$otp;
				if (trim($params['mobile']) == "") {
					return "Blank Mobile No.";
				}

				$mobile = $params['mobile'];
				//$mobile = '9525555516';
				//echo $otp.'to mobile'.$mobile; // for development
				//following commented court for production
			date_default_timezone_set('Asia/Kolkata');
			$sms_datetime=date('Y-m-d H:i:s');
			$ip_address = get_client_ip();
			// Define rate limit parameters
			$max_requests = 1; // Maximum number of requests
			$time_period = 5; // Time period in seconds (e.g., 1 hour)
			$request_time_time_period=date('Y-m-d H:i:s', time() - $time_period);
			// $CI->load->model('Audit_model');
            $Audit_model = new AuditModel();
			$request_count = $Audit_model->check_efiling_sms_mobile_no_log($mobile,$request_time_time_period,$ip_address);
			if ($request_count >= $max_requests) {
				// Too many requests, show an error message
				$text_msg = 'Please wait ' . env('SMS_RESEND_LIMIT') . ' seconds and then try again!!'; // code to stop sms flooding
				$session->setFlashdata('msg', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a> <p style="text-align: center;">' . $text_msg . '</p> </div>');
				return FALSE;
			}
				$url = "http://10.0.0.0/eAdminSCI/a-push-sms-gw?mobileNos=" . trim($mobile) . "&message=" . rawurlencode("OTP for Login is $otp. - Supreme Court of India") . "&typeId=14&templateId=" . PHYSICAL_HEARING_LOGIN_OTP . "&myUserId=NIC001001&myAccessId=root";
				//echo $url;
				$res = file_get_contents($url);
			$json = json_decode($res);
			//print_r($json);
			if ($json->{'responseFlag'} == "success") {
				//return "success";
				$msg = true;
				$data_email = array(
					'ip_address' => get_client_ip(),
					'mobile_no' => $mobile
				);
				$db_response_sms = $Audit_model->insert_efiling_sms_email_dtl($data_email);
			} else {
				//return "error";
				$msg = false;
			}

				if (isset($res) && !empty($res)) {
					$msg = true;
				}
		}
		return $msg;

	}

    // function is_recaptcha_valid($token){
    //     //return true;
    //     try {
    //         $validation_result = @json_decode(@file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, stream_context_create(array(
    //             'http' => array(
    //                 'header' => "Content-type: application/x-www-form-urlencoded\r\n",
    //                 'method' => 'POST',
    //                 'content' => http_build_query(array(
    //                     'response' => $token,
    //                     'secret' => '6LfE13AUAAAAAMic08Z-XTyBFIYxMNtQ31l0Th2G'
    //                 )),
    //             ),
    //         ))));
    //         if (count($validation_result) > 0) {
    //             if ($validation_result->success) {
    //                 if ($validation_result->score > 0.5) {
    //                     return true;
    //                 } else {
    //                     return false;
    //                 }
    //             }
    //         }
    //     } catch(Exception $e){}

    //     return false;
    // }


    function case_listed_in_daily_list_status($diary_no)
    {
        // $ci = &get_instance();
        // $ci->load->model('hearing_model');
        $hearing_model = new HearingModel();
        $case_listed_in_daily_list_date=$hearing_model->get_case_listed_in_daily_list($diary_no);
        // pr($case_listed_in_daily_list_date);
        if(!empty($case_listed_in_daily_list_date)) {
            $aud_nomination_status=checkEntryWithinAllowDateAndTime($case_listed_in_daily_list_date[0]['next_dt']);
        } else {
            $aud_nomination_status=1;
        }
        return  $aud_nomination_status;
    }
    
    function case_listed_in_daily_list_next_date($diary_no)
    {
        $case_next_date=null;
        // $ci = &get_instance();
        // $ci->load->model('hearing_model');
        $hearing_model = new HearingModel();

        $case_listed_in_daily_list_date=$hearing_model->get_case_listed_in_daily_list($diary_no);

        if(!empty($case_listed_in_daily_list_date))
        {
            $aud_nomination_status=checkEntryWithinAllowDateAndTime($case_listed_in_daily_list_date[0]['next_dt']);
        }
        else
            $aud_nomination_status = null;


        if($aud_nomination_status==1)
        {
            $case_next_date=$case_listed_in_daily_list_date[0]['next_dt'];
        }
        else
            $case_next_date=null;

        return  $case_next_date;
    }
}
function send_sms($mobile, $message, $templateId=''){
    //$mobile = SMS_TO_CONCERN; // for development only and should be commented in the production
    $msg=true;
	//$mobile='9525555516';
    if(isset($mobile) && !empty($mobile)){
        $url="http://10.0.0.0/eAdminSCI/a-push-sms-gw?mobileNos=".trim($mobile)."&message=".rawurlencode("$message")."&typeId=14&templateId=".$templateId."&myUserId=NIC001001&myAccessId=root";
        $res = file_get_contents($url);
        if(isset($res) && !empty($res)){
            $msg = true;
        }
        else
            $msg = false;
    }
    return $msg;
}

function send_email($to_email,$subject, $message,$attendee_mail=null,$attachment=null) {
    //$to_email = "sca.mohitjain@sci.nic.in, sca.kbpujari@sci.nic.in"; //for development
	//$to_email='';
	//$to_email = "anshukumargupta92@gmail.com"; //for development
    $attachment= str_replace('\/', '/', rawurldecode($attachment));
    $attachment=json_decode($attachment);
    $attachment=$attachment[0]->url;
    // $ci = &get_instance();
    // $ci->load->library('email');
    $email = \Config\Services::email();
    $config = array(
        'protocol' => 'smtp',
        'smtp_host' => 'ssl://smtp.mail.gov.in',
        'smtp_port' => 465,
        'smtp_user' => '',
        'smtp_pass' => '',
        'mailtype' => 'html',
        'charset' => 'utf-8'
    );
    $email->initialize($config);
    $email->setMailType("html");
    $email->setNewLine("\r\n");
    $email->setTo($to_email);
    $email->setFrom('causelists@nic.in', "Supreme Court of India");
    $email->setSubject($subject);
    $data = array(
        'message' => $message
    );
    if(is_null($attendee_mail))
        $email->setMessage(render('email.html_mail',$data, true));
    else if($attendee_mail==1)
        $email->setMessage(render('email.attendee_list_mail',$data, true));
    if(!empty($attachment)) {
        $mail_subject=$subject.'.pdf';
        $email->attach($attachment,'attachment', $mail_subject);
    }
    $response = $email->send();
    if ($response) {
        $result = 'success';
    } else {
        $result = 'error';
    }
    return $result;
}

function case_listed_on_tuesday_aud_status($listing_dates)
{
    $listing_dates=(is_array($listing_dates))?$listing_dates:array($listing_dates);

    foreach ($listing_dates as $list_date)
    {
        $aud_nomination_status = 1;
        $today = date("Y-m-d");
        $current_time = time();
        $allow_time = strtotime('13:00:00');
        $diff = date_diff(date_create($today), date_create($list_date));

        if (intval($diff->format("%R%a days")) > 0) // if hearing date is greater than today
        {
            if (intval($diff->format("%R%a days") == 1)) //consent can be given till 6 pm on one day before from the actual misc day hearing
            {
                if ($allow_time >= $current_time) {
                    $aud_nomination_status = 1;
                } else {
                    $aud_nomination_status = 0;
                }
            } else {
                $aud_nomination_status = 1;
            }
        } else {
            $aud_nomination_status = 0;
        }
    }
    return  $aud_nomination_status;
}
function case_listed_on_any_misc_day_aud_status($listing_dates)
{
    $listing_dates=(is_array($listing_dates))?$listing_dates:array($listing_dates);

    foreach ($listing_dates as $list_date)
    {
        $aud_nomination_status = 1;
        $today = date("Y-m-d");
        $current_time = time();
        //$allow_time = strtotime('13:00:00');
        $allow_time = strtotime('22:00:00'); // chan
        $diff = date_diff(date_create($today), date_create($list_date));

        if (intval($diff->format("%R%a days")) > 0) // if hearing date is greater than today
        {
            if (intval($diff->format("%R%a days") == 1)) //consent can be given till 10 pm on one day before from the actual misc day hearing
            {
                if ($allow_time >= $current_time) {
                    $aud_nomination_status = 1;
                } else {
                    $aud_nomination_status = 0;
                }
            } else {
                $aud_nomination_status = 1;
            }
        } else {
            $aud_nomination_status = 0;
        }
    }
    return  $aud_nomination_status;
}
function checkEntryWithinAllowDateAndTime($list_date)
{//return 1;
    $today = date("Y-m-d");
    /*$current_time = date("H:i:a");
    $allow_time = '08:00 am';
    $date_current_time = DateTime::createFromFormat('H:i a', $current_time);
    $date_allow_time = DateTime::createFromFormat('H:i a', $allow_time);*/

    $current_time = time();
    $allow_time = strtotime('08:00:00'); // as per SOP 08 am in the morning is bench mark for giving consent for the day hearing
    $diff=date_diff(date_create($today),date_create($list_date));
    //echo $diff->format("%R%a days");
    if(intval($diff->format("%R%a days"))==0)
    {
        if ($allow_time >= $current_time)
        {
            return 1;
        }
        else {
            return 2; //when the causelist date:time is greater than 8:am in the morning on the day of listing
        }
    }
    elseif(intval($diff->format("%R%a days"))<0) // not allowed to nominate for past date
    {
        return 3;
    }
    else
    {
        return 1;
        // if listing date is > today then no need to restrict nomination after 8 am
    }
}

//New method copied on 23-05-2022
function isListDateWithinSummerVacation()
{
    // //TODO - listing date check for summer vacation 2022
    // $toDayDate = strtotime(date("Y-m-d H:i:s"));
    // /*$toDayDate = strtotime('2022-05-23 01:00:00');*/
    // // $vacationDateBegin = strtotime("2022-05-22 24:00:00");
    // // $vacationDateEnd = strtotime("2022-07-08 23:59:00");
    // // Added for current year By Ashutosh Gupta
    // $vacationDateBegin = strtotime("2024-05-20 00:00:00");
    // $vacationDateEnd = strtotime("2024-07-08 23:59:59");
    // if($toDayDate > $vacationDateBegin && $toDayDate < $vacationDateEnd) {
    //     return 1;
    // } else {
    //     return 0;
    // }
    return 1;
}

function getNextMiscDayOfHearing()
{
    // $ci = &get_instance();
    // $ci->load->model('consent_VC_model');
    $consent_VC_model = new ConsentVCModel();
    $isTodayFallWithinSummerVacation=isListDateWithinSummerVacation();
    // pr($isTodayFallWithinSummerVacation);
    if($isTodayFallWithinSummerVacation) // if list date fall within summer vacation 2022
        $firstWorkingMiscDay=$consent_VC_model->getNextMDWorkingDayOfWeek(null,$isTodayFallWithinSummerVacation);
    else
        $firstWorkingMiscDay=$consent_VC_model->getNextMDWorkingDayOfWeek();
    $next_misc_working_date = isset($firstWorkingMiscDay) && !empty($firstWorkingMiscDay) ? $firstWorkingMiscDay[0]['working_date'] : date('Y-m-d');
    $aud_nomination_status=checkEntryWithinAllowDateAndTime($next_misc_working_date);
    if($aud_nomination_status!=1) {
        if($isTodayFallWithinSummerVacation)
            $firstWorkingMiscDay=$consent_VC_model->getNextMDWorkingDayOfWeek($show_next_misc_date_cases='show',$isTodayFallWithinSummerVacation);
        else
            $firstWorkingMiscDay=$consent_VC_model->getNextMDWorkingDayOfWeek($show_next_misc_date_cases='show');
    }
    // echo 'Hello'; pr($firstWorkingMiscDay);
    $next_misc_working_date = isset($firstWorkingMiscDay) && !empty($firstWorkingMiscDay) ? $firstWorkingMiscDay[0]['working_date'] : date('Y-m-d');
    //echo $next_misc_working_date;
    //$next_misc_working_date='2024-04-18';
    //echo $next_misc_working_date; 
    return $next_misc_working_date;
}

function set_cookie_with_samesite($name, $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = FALSE, $httponly = FALSE, $samesite = 'Lax')
{
	//set_cookie_with_samesite('PHPSESSID', $_COOKIE['PHPSESSID'], time() + 3600, '', '', FALSE, TRUE,TRUE, 'Lax'); // call by controller testing pending
	if (empty($expire)) {
		$expire = time() + 86500; // Default expiry time
	}
	if (empty($domain)) {
		$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : FALSE;
	}

	$cookie_params = [
		'expires' => $expire,
		'path' => $path,
		'domain' => $domain,
		'secure' => $secure,
		'httponly' => $httponly
	];

	// Set SameSite attribute if supported (PHP 7.3+)
	if (version_compare(PHP_VERSION, '7.3.0', '>=')) {
		$cookie_params['samesite'] = $samesite;
	}
   //var_dump($cookie_params['samesite']);
	setcookie($name, $value, $cookie_params);
	//var_dump(setcookie($name, $value, $cookie_params));
	//var_dump($_COOKIE);
	//echo '<pre>';print_r($cookie_params);//exit();
	//echo '<pre>';print_r($_COOKIE);exit();

}
// function is_user_status($loginid = null)
// {
// 	$ci = &get_instance();
// 	$ci->load->model('Audit_model');
// 	$ci->load->library('session');
// 	check_session_timeout();
// 	//$ci->load->database('physical_hearing');
// 	if (!empty($loginid) && $loginid != null) {
// 		$loginid = $loginid;
// 	} else {
// 		$loginid = $_SESSION['loginData']['user_id'];
// 	}
// 	$result = $ci->Audit_model->is_user_status($loginid, $_SESSION['loginData']['user_type_id']);
// 	//echo '<pre>';print_r($_SESSION);//exit();
// 	//echo '<pre>';print_r($result);exit();
// 	if (!empty($result) && $result != FALSE) {
// 		if ($result[0]->referrer == $_SESSION['loginData']['user_type_id'] && $result[0]->processid != $_SESSION['loginData']['processid']) {
// 			redirect(base_url('index.php/auth/logout'));
// 		} else {
// 			return false;
// 		}
// 	}
// }
function audit_trail_log($action,$data=null,$user_id=null)
{
	// $ci = &get_instance();
	// $ci->load->model('Audit_model');
	// $ci->load->library('session');
	//$ci->load->database('physical_hearing');
    $Audit_model = new AuditModel();
	if (!empty($user_id) && $user_id != null) {
		$user_id = $user_id;
	} else {
		$user_id = $_SESSION['loginData']['user_id'];
	}
	$result = $Audit_model->log($action,$user_id,$data);
}
function audit_logUser($action,$data)
{
	// $ci = &get_instance();
	// $ci->load->model('Audit_model');
	// $ci->load->library('session');
	//$ci->load->database('physical_hearing');
    $Audit_model = new AuditModel();
	$result = $Audit_model->logUser($action,$data);
}

function check_session_timeout()
{
	// $ci = &get_instance();
	// $ci->load->library('session');
	$timeout =session_expiration_time_inseconds;
	$current_time = time();
	if ($_SESSION['loginData']['login_time_inseconds']) {
		$last_activity = $_SESSION['loginData']['login_time_inseconds'];
		if (($current_time - $last_activity) > $timeout) {
			audit_trail_log('logout','User logged session out');
			return redirect()->to(base_url('index.php/auth/logout'));
		} else {
			$_SESSION['loginData']['login_time_inseconds'] = $current_time;
		}
	} else {
		//header('Location:'.base_url('index.php/auth/logout'));
		return redirect()->to(base_url('index.php/auth/logout'));
	}
}

function getAdvocateConsentDetails($date = null)
{
	$Consent_model = new App\Models\PhysicalHearing\ConsentModel;
    $session = \Config\Services::session();
	return $Consent_model->getAdvocateConsentDetails($date);
}

function objectToArraySelective($data) {
    if (is_object($data)) {
        return (array) $data;
    } elseif (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = objectToArraySelective($value);
        }
    }
    return $data;
}