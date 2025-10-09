<?php

namespace App\Controllers\Register;

use App\Controllers\BaseController;
use App\Models\Register\RegisterModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\Slice;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\Files\UploadedFile;
use Config\Services;
use DOMDocument;
use Exception;
use XMLSecEnc;
use XMLSecurityDSig;
use ZipArchive;

// if (!defined('BASEPATH')) exit('No direct script access allowed');

class DefaultController extends BaseController {

    protected $Register_model;
    protected $slice;
    protected $form_validation;
    protected $session;
    protected $efiling_webservices;
    protected $encryption;
    protected $upload;

    public function __construct() {
        parent::__construct();
        // $this->load->model('register/Register_model');
        // $this->load->library('webservices/efiling_webservices');
        // $this->load->library('slice');
        require_once APPPATH . 'ThirdParty/eSign/XMLSecEnc.php';
        require_once APPPATH . 'ThirdParty/eSign/XMLSecurityDSig.php';
        require_once APPPATH . 'ThirdParty/eSign/XMLSecurityKey.php';
        $this->Register_model = new RegisterModel();
        $this->slice = new Slice();
        $this->efiling_webservices = new Efiling_webservices();
        $this->session = \Config\Services::session();
        $this->encryption = \Config\Services::encryption();
        helper(['form']);
    }

    public function index() {
        // unset($_SESSION['kyc_configData']);
        // unset($_SESSION['adv_details']);
        // unset($_SESSION['register_data']);
        /* $captcha_value = captcha_generate();
        $data['captcha']['image'] = $captcha_value['image'];
        $data['captcha']['word'] = $captcha_value['word'];*/
        $this->render('responsive_variant.authentication.register_view');
    }

    /* function check_and_save_zip_file_temporary() {
        $filename = $_FILES['ekyc_zip_file']['name'];
        $source = $_FILES['ekyc_zip_file']['tmp_name'];
        $type = $_FILES['ekyc_zip_file']['type'];
        $name = explode('.', $filename);
        $target = 'uploaded_docs/ekyc/temp/';
        $accepted_types = array('application/zip','application/x-zip-compressed','multipart/x-zip','application/s-compressed');
        foreach($accepted_types as $mime_type) {
            if($mime_type == $type) {
                $okay = true;
                break;
            }
        }
        //Safari and Chrome don't register zip mime types. Something better could be used here.
        $okay = strtolower($name[1]) == 'zip' ? true: false;
        if(!$okay) {
            die("Please choose a zip file!!");
        }
        mkdir($target);
        $saved_file_location = $target . $filename;
        if(!(move_uploaded_file($source, $saved_file_location))) {
            die("There was a problem. Sorry!");
        }
        return $saved_file_location;
    }*/

    function inspect_files_within_zip($file_path=null) {
        $is_valid_file=false;
        if(!empty($file_path)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file_path);
            finfo_close($finfo);

            if ($mime_type !== 'application/zip') {
                $is_valid_file=false;
            }else{
                $allowable_file_formats=json_decode(OFFLINE_ADHAAR_EKYC_ZIP_ALLOWABLE_FILE_FORMAT);
                $zip = new \ZipArchive();
                $zip->open($file_path);
                $is_valid_file='';
                for( $i = 0; $i < $zip->numFiles; $i++ ) {
                    $filename = $zip->getNameIndex($i);
                    // echo $filename.'<br>';
                    $imageExtention = pathinfo($filename, PATHINFO_EXTENSION);
                    if(in_array($imageExtention, $allowable_file_formats)) {
                        $is_valid_file=true;
                        continue;
                    } else {
                        $is_valid_file=false;
                        break;
                    }
                }
                $zip->close();
            }
        }
        return $is_valid_file;
    }

    /* function inspect_files_within_zip($fileName) {
        $allowable_file_formats=json_decode(OFFLINE_ADHAAR_EKYC_ZIP_ALLOWABLE_FILE_FORMAT);
        $zip = new \ZipArchive();
        $zip->open("assets/ekyc/" . $fileName);
        $is_valid_file='';
        for( $i = 0; $i < $zip->numFiles; $i++ ) {
            $filename = $zip->getNameIndex($i);
            $imageExtention = pathinfo($filename, PATHINFO_EXTENSION);
            if(in_array($imageExtention, $allowable_file_formats)) {
                $is_valid_file=true;
                continue;
            } else {
                $is_valid_file=false;
                break;
            }
        }
        $zip->close();
        return $is_valid_file;
    } */

    function adv_get_otp() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $reqeusttt = service('request');
        $cachett = service('cache');
        $responseet = service('response');
        $ipAddresst = $reqeusttt->getIPAddress();
        $keyt = 'otp_mail_request_' . $ipAddresst;
        if ($cachett->get($keyt) >= 5) {
            return $responseet->setStatusCode(429)->setBody('Too many requests');
            exit(0);
        }
        $cachett->save($keyt, $cachett->get($keyt) + 1, 60);
        $mobile_exist = array();
        $email_exist = array();
        $_SESSION['register_type_select'] = isset($_POST['adv_type_select']) ? esc($_POST['adv_type_select']) : '';
        $_SESSION['session_not_register_type_user'] = ISSET($_POST['not_register_type_user']) ? esc($_POST['not_register_type_user']) : '';
        if (empty($_POST['userCaptcha'])) {
            $this->session->setFlashdata('msg', 'Captcha is required!');
            return redirect()->to(base_url('register'));
            exit(0);
        }
        $validation =  \Config\Services::validation();
        if ($_POST['register_type'] == 'Party In Person') {
            $rules=[
                "adv_mobile" => [
                    "label" => "Mobile",
                    "rules" => "required|trim|numeric|min_length[10]|max_length[10]"
                ],
                "adv_email" => [
                    "label" => "Email",
                    "rules" => "required|trim|valid_email"
                ],
                "userCaptcha" => [
                    "label" => "Captcha",
                    "rules" => "required|trim"
                ],
            ];
        }
        else{
            $rules=[
                "adv_mobile" => [
                    "label" => "Advocate Mobile",
                    "rules" => "required|trim|numeric|min_length[10]|max_length[10]"
                ],
                "adv_email" => [
                    "label" => "Advocate Email",
                    "rules" => "required|trim|valid_email"
                ],
                "userCaptcha" => [
                    "label" => "Captcha",
                    "rules" => "required|trim"
                ],
            ];
        }
        
        if ($this->validate($rules) === FALSE) {
            $data = [
                'validation' => $this->validator,
                'currentPath' => $this->slice->getSegment(1) ?? 'public',
            ];
            $this->session->set('login_salt', $this->generateRandomString());
            return $this->render('responsive_variant.authentication.register_view', $data);
        } else {
            if (isset($_SESSION["captcha"]) && $_SESSION["captcha"] != $_POST['userCaptcha']) {
                $this->session->setFlashdata('msg', 'Invalid Captcha!');
                return redirect()->to(base_url('register'));
                exit(0);
            }
            if (isset($_POST['adv_mobile']) && (substr($_POST['adv_mobile'],0,1) == 0 || substr($_POST['adv_mobile'],0,1) == 1 || substr($_POST['adv_mobile'],0,1) == 2 || substr($_POST['adv_mobile'],0,1) == 3 || substr($_POST['adv_mobile'],0,1) == 4 || substr($_POST['adv_mobile'],0,1) == 5)) {
                $this->session->setFlashdata('msg', 'Mobile Number is not valid!');
                return redirect()->to(base_url('register'));
                exit(0);
            }
            if (isset($_POST['adv_mobile']) && !empty(esc($_POST['adv_mobile'])) || isset($_POST['adv_email']) && !empty(esc($_POST['adv_email']))) {
                $mobile_already_exist = $this->Register_model->check_already_reg_mobile(esc($_POST['adv_mobile']));
                $email_already_exist = $this->Register_model->check_already_reg_email(esc($_POST['adv_email']));
                $uploaded_file_size=$_FILES["ekyc_zip_file"]['size'];
                if($uploaded_file_size > OFFLINE_AADHAAR_EKYC_ZIP_ALLOWABLE_FILE_SIZE) // allow  upto 50 kb size
                {
                    $this->session->setFlashdata('msg', 'File size are beyond allowable limit.');
                    return redirect()->to(base_url('register'));
                } 
                if (esc($_POST['register_type']) == 'Party In Person') {

                    if (!empty($mobile_already_exist)) {
                        if ($mobile_already_exist[0]['moblie_number'] == esc($_POST['adv_mobile'])) {
                            $this->session->setFlashdata('msg', 'Mobile Number Already Registerd! Please click on forgot password and reset your password!');
                            return redirect()->to(base_url('register'));
                        } elseif ($email_already_exist[0]['emailid'] == $_POST['adv_email']) {
                            $this->session->setFlashdata('msg', 'Email ID Already Registerd! Please click on forgot password and reset your password!');
                            return redirect()->to(base_url('register'));
                        }
                    }
                } else if (esc($_POST['register_type']) == 'Advocate On Record') {
                    if ($mobile_already_exist[0]['moblie_number'] == esc($_POST['adv_mobile'])) {
                        $this->session->setFlashdata('msg', 'Mobile Number Already Registerd! Please click on forgot password and reset your password!');
                        return redirect()->to(base_url('register/AdvocateOnRecord'));
                    }
                    if ($email_already_exist[0]['emailid'] == esc($_POST['adv_email'])) {
                        $this->session->setFlashdata('msg', 'Email ID Already Registerd! Please click on forgot password and reset your password!');
                        return redirect()->to(base_url('register/AdvocateOnRecord'));
                    }
                    $advDetailsIcmis=$this->efiling_webservices->getBarTable(esc($_POST['adv_mobile']),esc($_POST['adv_email']));
                    if ($advDetailsIcmis[0]->moblie_number != esc($_POST['adv_mobile']) || $advDetailsIcmis[0]->emailid != esc($_POST['adv_email'])) {
                        $this->session->setFlashdata('msg', 'Mobile Number And Email ID Not Valid for Bar! Please Contact SCI !');
                        return redirect()->to(base_url('register/AdvocateOnRecord'));
                    }
                }
                if (!empty($_FILES["ekyc_zip_file"]['name'])) {
                    $is_valid_files = '';
                    $uploaded_file_size=$_FILES["ekyc_zip_file"]['size'];
                    if($uploaded_file_size > OFFLINE_AADHAAR_EKYC_ZIP_ALLOWABLE_FILE_SIZE) // allow  upto 50 kb size
                    {
                        $this->session->setFlashdata('msg', 'File size are beyond allowable limit.');
                        return redirect()->to(base_url('register'));
                    } else{
                        $is_valid_files = true;
                    } 
                    // Load the request object
                    $request = \Config\Services::request();
                    // Get the uploaded file
                    $file = $request->getFile('ekyc_zip_file');
                    // Check if the file is valid and not moved
                    if ($file->isValid() && !$file->hasMoved()) {
                        $uploadPath = 'uploaded_docs/ekyc/';
                        // Ensure the upload path exists
                        if (!is_dir($uploadPath)) {
                            mkdir($uploadPath, 0777, true);
                        }
                        // Generate the destination path
                        $fileName = $file->getName();
                        $filePath = $uploadPath . $fileName;
                        // Move the file to the desired location
                        if ($file->move($uploadPath, $fileName)) {
                            $xfile = explode('.',$fileName);
                            if($xfile[1] != 'zip'){
                                unlink($filePath);
                                return redirect()->to(base_url('register'))->with('msg', 'Please upload Zip file only.');
                            }
                            $isValidFiles = $this->inspect_files_within_zip($filePath);
                            if ($isValidFiles) {
                                // Process the zip file
                                $zip = new ZipArchive;
                                if ($zip->open($filePath) === TRUE) {
                                    $extractedPath = $uploadPath . explode('.',$fileName)[0] . '.xml';
                                    // chmod($uploadPath, 777);
                                    if ($zip->setPassword($request->getPost('share_code'))) {
                                        if (!$zip->extractTo($uploadPath)) { 
                                            $zip->close();
                                            return redirect()->to(base_url('register'))->with('msg', 'Extraction failed (wrong password?)');
                                        }
                                    }

                                    $zip->close();
                                    $xml = simplexml_load_file($extractedPath); 
                                    $json = json_encode($xml);
                                    $dataConfig = json_decode($json, true);
                                    $_SESSION['kyc_configData'] = $dataConfig;
                                    $data['configData'] = $_SESSION['kyc_configData'];
                                    $data['mobile_addhar'] = $request->getPost('mobile');
                                    $data['email_addhar'] = $request->getPost('emailid');
                                    $reference_id = $dataConfig['@attributes']['referenceId'];
                                    $get_adhar_last_digit = substr(substr($reference_id, 0, 4), -1);
                                    if ($get_adhar_last_digit == 0) {
                                        $get_adhar_last_digit = 1;
                                    }
                                    $share_code = $request->getPost('share_code');
                                    $mobile_sha256_hash = $request->getPost('adv_mobile') . $share_code;
                                    $email_sha256_hash = $request->getPost('adv_email') . $share_code;
                                    for ($i = 1; $i <= $get_adhar_last_digit; $i++) {
                                        $mobile_sha256_hash = hash('sha256', $mobile_sha256_hash);
                                        $email_sha256_hash = hash('sha256', $email_sha256_hash);
                                    }
                                    $Poi = $dataConfig['UidData']['Poi']['@attributes'];
                                    if ($mobile_sha256_hash != $Poi['m']) {
                                        return redirect()->to(base_url('register'))->with('msg', 'Mobile should be the same as registered with Aadhar!');
                                    } else {
                                        $responseString = file_get_contents($extractedPath);
                                        $signature_match = $this->offlineKycResponse($responseString);
                                        if ($signature_match != 'Signature validated') {
                                            return redirect()->to(base_url('register'))->with('msg', 'Aadhar file tampered, please try with a fresh offline Aadhar!');
                                        }
                                    }
                                    // return redirect()->to(base_url('register'))->with('msg', 'Upload & Extract successfully.');
                                } else {
                                    return redirect()->to(base_url('register'))->with('msg', 'Failed to open zip file.');
                                }
                            } else {
                                unlink($filePath);
                                return redirect()->to(base_url('register'))->with('msg', 'Please upload the original offline Aadhaar eKYC Zip file.');
                            }
                        } else {
                            return redirect()->to(base_url('register'))->with('msg', 'Failed to upload zip file.');
                        }
                    } else {
                        return redirect()->to(base_url('register'))->with('msg', 'No file was uploaded or file is invalid.');
                    }
                }
                if(!empty(esc($_POST['adv_mobile'])))
                    $first_name = (!empty($mobile_exist)) ? $mobile_exist[0]['first_name'] : 'Sir/Ma\'am';
                    $last_name = (!empty($mobile_exist)) ? $mobile_exist[0]['last_name'] : '';
                    $name_array = array('first_name'=> $first_name, 'last_name'=> $last_name);
                if(!empty(esc($_POST['adv_email'])))
                    $first_name = (!empty($email_exist)) ? $email_exist[0]['first_name'] : 'Sir/Ma\'am';
                    $last_name = (!empty($email_exist)) ? $email_exist[0]['last_name'] : '';
                    $name_array = array('first_name'=> $first_name, 'last_name'=> $last_name);
                // $o = '123456';
                // $mobile_otp_is = $o;
                // $email_otp_is = $o;
                $mobile_otp_is = $this->generateNumericOTP();
                $email_otp_is = $this->generateNumericOTP();
                /* Code Added on 13-03-2023 by Amit Tripathi for OTP expiration */
                date_default_timezone_set('Asia/Kolkata');
                $startTime=date("H:i");
                $endTime = date("H:i", strtotime('+15 minutes', strtotime($startTime)));
                $sess_data =  array_merge(array(
                    'mobile_no' => esc($_POST['adv_mobile']),
                    'mobile_otp' => $mobile_otp_is,
                    'email_id' => esc($_POST['adv_email']),
                    'email_otp' => $email_otp_is,
                    'register_type' => esc($_POST['register_type']),
                    'start_time' => $startTime,
                    'end_time' => $endTime), $name_array);
                setSessionData('adv_details', $sess_data);
                /* END */
                if(!empty($_POST['adv_mobile'])) {
                    $typeId="38";
                    $mobileNo=trim(esc($_POST['adv_mobile']));
                    $smsText="OTP for Registration SC-EFM password is: ".$mobile_otp_is." ,Please do not share it with any one. - Supreme Court of India";
                    $reqeustt = service('request');
                    $cachet = service('cache');
                    $responsee = service('response');
                    $ipAddress = $reqeustt->getIPAddress();
                    $key = 'otp_request_' . $ipAddress;
                    if ($cachet->get($key) >= 5) {
                        return $responsee->setStatusCode(429)->setBody('Too many requests');
                    }
                    $cachet->save($key, $cachet->get($key) + 1, 60);
                    sendSMS($typeId,$mobileNo,$smsText,SCISMS_Registration_OTP);
                }
                if(!empty(esc($_POST['adv_email']))) {
                    $to_email=trim(esc($_POST['adv_email']));
                    $subject="SC-EFM Registration password OTP";
                    $message="OTP for Registration SC-EFM password is: ".$email_otp_is." ,Please do not share it with any one.";
                    $reqeusttt = service('request');
                    $cachett = service('cache');
                    $responseet = service('response');
                    $ipAddresst = $reqeusttt->getIPAddress();
                    $keyt = 'otp_mail_request_' . $ipAddress;
                    if ($cachett->get($keyt) >= 5) {
                        return $responseet->setStatusCode(429)->setBody('Too many requests');
                        exit(0);
                    }
                    $cachett->save($keyt, $cachett->get($keyt) + 1, 60);
                    send_mail_msg($to_email, $subject, $message);
                    // relay_mail_api($to_email, $subject, $message);
                }
                $this->session->setFlashdata('msg_success', 'OTP Sent Successfully. OTP is valid for 15 minutes only');  
                return redirect()->to(base_url('register/AdvOtp'));
            } else {
                $this->session->setFlashdata('login_salt', $this->generateRandomString());
                return $this->render('responsive_variant.authentication.register_view');
            }
        }
    }

    function offlineKycResponse($responseXML) {
        $doc = new DOMDocument('1.0', 'utf-8');
        // foreach ($arTests AS $testName=>$testFile) {
        $doc->encoding = 'utf-8';
        $doc->formatOutput = false;
        $doc->preserveWhiteSpace = true;
        $doc->loadXML($responseXML);
        //$doc->load("$responseXML");
        $objXMLSecDSig = new XMLSecurityDSig();
        $objDSig = $objXMLSecDSig->locateSignature($doc);
        if (! $objDSig) {
            throw new Exception("Cannot locate Signature Node");
        }
        $objXMLSecDSig->canonicalizeSignedInfo();
        $objXMLSecDSig->idKeys = array('wsu:Id');
        $objXMLSecDSig->idNS = array('wsu'=>'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd');
        $retVal = $objXMLSecDSig->validateReference();
        if (! $retVal) {
            throw new Exception("Reference Validation Failed");
        }
        $objKey = $objXMLSecDSig->locateKey();
        if (! $objKey ) {
            throw new Exception("We have no idea about the key");
        }
        $key = NULL;
        $objKeyInfo = XMLSecEnc::staticLocateKeyInfo($objKey, $objDSig);
        if (! $objKeyInfo->key && empty($key)) {
            $address_pem=base_url()."assets/ekyc/adhar_offline_kyc_public_key.pem";
            // $address_pem = $_SERVER['DOCUMENT_ROOT']."/bharatkosh/adhar_offline_kyc_public_key.pem";
            // $address_pem = "adhar_offline_kyc_public_key.pem";
            // $address_pem = "http://10.25.78.29/bharatkosh/adhar_offline_kyc_public_key.pem";
            $objKey->loadKey($address_pem, TRUE);
        }
        if ($objXMLSecDSig->verify($objKey) === 1) {
            return "Signature validated";
        } else {
            return "Failure";
        }
    }

    function generateNumericOTP($n = 6) {
        $generator = "1357902468";
        $result = "";
        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand() % (strlen($generator))), 1);
        }
        return $result;
    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}