<?php

namespace App\Controllers\Register;

use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\Register\RegisterModel;
use DateTime;
use App\Libraries\webservices\Ecoping_webservices;

class AdvSignUp extends BaseController
{
    protected $Register_model;
    protected $efiling_webservices;
    protected $request;
    protected $ecoping_webservices;
    public function __construct()
    {
        parent::__construct();
        $this->Register_model = new RegisterModel();
        $this->efiling_webservices = new Efiling_webservices();
        $this->ecoping_webservices = new Ecoping_webservices();
        $this->request = \Config\Services::request();
        helper(['form']);
    }

    public function index()
    {
        if (empty($_SESSION['adv_details']['mobile_no']) || empty($_SESSION['adv_details']['email_id'])) {
            return redirect()->to(base_url('register'));
        }
        unset($_SESSION['image_and_id_view']);
        $data['select_state'] = $this->Register_model->get_state_list();
        $data['advDetailsIcmis'] = $this->efiling_webservices->getBarTable($_SESSION['adv_details']['mobile_no'], $_SESSION['adv_details']['email_id']);
        $this->render('responsive_variant.authentication.adv_signup_view');
    }

    function get_dist_list()
    {
        $st_data = explode('#$', $_POST['state_id']);
        $st_id = $st_data[0];
        $result = $this->Register_model->get_district_list($st_id);
        echo '<option value=""> Select District </option>';
        foreach ($result as $district) {
            echo '<option  value="' . htmlentities($district['district_code'] . '#$' . $district['district_name'], ENT_QUOTES) . '">' . htmlentities(strtoupper($district['district_name']), ENT_QUOTES) . '</option>';
        }
    }

    function add_advocate()
    {
        if (!empty($_SESSION['profile_image']['profile_photo'])) {
            //
        } else if (!empty($_SESSION['kyc_configData']['UidData']['Pht'])) {
            //
        } else {
            if (empty($_SESSION['profile_image']['profile_photo'])) {
                $this->session->setFlashdata('advocate_image', 'Please Choose profile Photo.');
                $this->session->setFlashdata('old_name', isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : '');
                $this->session->setFlashdata('old_date_of_birth', isset($_POST['date_of_birth']) && !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : '');
                $this->session->setFlashdata('old_mobile', isset($_POST['mobile']) && !empty($_POST['mobile']) ? $_POST['mobile'] : '');
                $this->session->setFlashdata('old_email_id', isset($_POST['email_id']) && !empty($_POST['email_id']) ? $_POST['email_id'] : '');
                $this->session->setFlashdata('old_gender', isset($_POST['gender']) && !empty($_POST['gender']) ? $_POST['gender'] : '');
                $this->session->setFlashdata('old_address', isset($_POST['address']) && !empty($_POST['address']) ? $_POST['address'] : '');
                $this->session->setFlashdata('old_state_id', isset($_POST['state_id']) && !empty($_POST['state_id']) ? $_POST['state_id'] : '');
                $this->session->setFlashdata('old_district_list', isset($_POST['district_list']) && !empty($_POST['district_list']) ? $_POST['district_list'] : '');
                $this->session->setFlashdata('old_pincode', isset($_POST['pincode']) && !empty($_POST['pincode']) ? $_POST['pincode'] : '');
                return redirect()->to(base_url('register/AdvSignUp'));
            }
        }
        if (!empty($_POST['mobile']) || !empty($_POST['email_id'])) {
            if ($_SESSION['adv_details']['mobile_no'] != $_POST['mobile']) {
                $this->session->setFlashdata('msg', 'Invalid Mobile number.');
                return redirect()->to(base_url('register'));
            } elseif ($_SESSION['adv_details']['email_id'] != $_POST['email_id']) {
                $this->session->setFlashdata('msg', 'Invalid Emai ID.');
                return redirect()->to(base_url('register'));
            }
        } else {
            return redirect()->to(base_url('register'));
        }
        $rules = [
            "name" => [
                "label" => "Name",
                "rules" => "required|trim|min_length[2]|max_length[26]"
            ],
            "date_of_birth" => [
                "label" => "Date Of Birth",
                "rules" => "required|trim"
            ],
            "gender" => [
                "label" => "Gender",
                "rules" => "required|trim"
            ],
            "address" => [
                "label" => "Address",
                "rules" => "required|trim|validateAlphaNumericSingleDoubleQuotesBracketWithSpecialCharacters"
            ],
            "state_id" => [
                "label" => "State",
                "rules" => "required|trim"
            ],
            "district_list" => [
                "label" => "District",
                "rules" => "required|trim"
            ],
            "pincode" => [
                "label" => "Pincode",
                "rules" => "required|trim|numeric|min_length[6]|max_length[6]"
            ],
        ];
        if ($this->validate($rules) === FALSE) {
            $data['validation'] = $this->validator;
            $data['select_state'] = $this->Register_model->get_state_list();
            $this->session->setFlashdata('old_name', isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : '');
            $this->session->setFlashdata('old_date_of_birth', isset($_POST['date_of_birth']) && !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : '');
            $this->session->setFlashdata('old_mobile', isset($_POST['mobile']) && !empty($_POST['mobile']) ? $_POST['mobile'] : '');
            $this->session->setFlashdata('old_email_id', isset($_POST['email_id']) && !empty($_POST['email_id']) ? $_POST['email_id'] : '');
            $this->session->setFlashdata('old_gender', isset($_POST['gender']) && !empty($_POST['gender']) ? $_POST['gender'] : '');
            $this->session->setFlashdata('old_address', isset($_POST['address']) && !empty($_POST['address']) ? $_POST['address'] : '');
            $this->session->setFlashdata('old_state_id', isset($_POST['state_id']) && !empty($_POST['state_id']) ? $_POST['state_id'] : '');
            $this->session->setFlashdata('old_district_list', isset($_POST['district_list']) && !empty($_POST['district_list']) ? $_POST['district_list'] : '');
            $this->session->setFlashdata('old_pincode', isset($_POST['pincode']) && !empty($_POST['pincode']) ? $_POST['pincode'] : '');
            $this->render('responsive_variant.authentication.adv_signup_view', $data);
        } else {
            $aor_code = 0;
            $adv_sci_bar_id = 0;
            $bar_reg_no = '';
            if ($_SESSION['adv_details']['register_type'] == 'Advocate On Record') {
                $_SESSION['register_type_select'] = url_encryption(USER_ADVOCATE);
            } //added by anshu
            if (url_decryption($_SESSION['register_type_select']) == USER_ADVOCATE) {
                //Get Advocate Details from ICMIS
                // $advDetailsIcmis=$this->efiling_webservices->getBarTable($_POST['adv_mobile'],$_POST['adv_email']);//commented by anshu
                $advDetailsIcmis = $this->efiling_webservices->getBarTable($_SESSION['adv_details']['mobile_no'], $_SESSION['adv_details']['email_id']); //added by anshu
                if (count($advDetailsIcmis) > 0) {
                    $aor_code = $advDetailsIcmis[0]->userid;
                    $adv_sci_bar_id = $advDetailsIcmis[0]->adv_sci_bar_id;
                    $bar_reg_no = $advDetailsIcmis[0]->bar_reg_no;
                } else {
                    $this->session->setFlashdata('msg', 'Registration Failed! Record not available.');
                    return redirect()->to(base_url('register/AdvSignUp'));
                }
                //END
            }
            $st_data = explode('#$', $_POST['state_id']);
            $st_id = $st_data[0];
            $dist_data = explode('#$', $_POST['district_list']);
            $dist_id = $dist_data[0];
            $date_of_birth_post = str_replace('/', '-', $_POST['date_of_birth']);
            $date = new DateTime($date_of_birth_post);
            $date_of_birth = $date->format('d-m-Y');
            $one_time_password = $this->generateRandomString();
            $_SESSION['user_created_password'] = $one_time_password;
            $data = array(
                'userid' => strtoupper($_SESSION['adv_details']['mobile_no']),
                'password' => hash('sha256', $one_time_password),
                'ref_m_usertype_id' => url_decryption($_SESSION['register_type_select']),
                'first_name' => strtoupper($_POST['name']),
                'last_name' => NULL,
                'moblie_number' => $_SESSION['adv_details']['mobile_no'],
                'emailid' => strtoupper($_SESSION['adv_details']['email_id']),
                'adv_sci_bar_id' => $adv_sci_bar_id,
                'aor_code' => $aor_code,
                'bar_reg_no' => strtoupper($bar_reg_no),
                'gender' => url_decryption($_POST['gender']),
                'photo_path' => '',
                'admin_for_type_id' => 1,
                'admin_for_id' => 1,
                'account_status' => 0,
                'is_active' => 1,
                'refresh_token' => NULL,
                'dob' => $date_of_birth,
                'm_address1' => strtoupper($_POST['address']),
                'm_city' => $dist_data[1],
                'm_state_id' => $st_id,
                'm_district_id' => $dist_id,
                'm_pincode' => $_POST['pincode'],
                'create_ip' => get_client_ip(),
                'is_first_pwd_reset' => true,
            );
            if ($_SESSION['adv_details']['register_type'] == 'Advocate On Record') {
                if (!empty($_SESSION['profile_image']['profile_photo'])) {
                    $profie_photo = array('photo_path' => $_SESSION['profile_image']['profile_photo']);
                    $final_data = array_merge($data, $profie_photo);
                } else {
                    $final_data = $data;
                }
                $already_exist = $this->Register_model->check_already_reg_email($final_data['emailid']);
                if (!empty($already_exist)) {
                    $this->session->setFlashdata('msg', 'Already Registerd Email.');
                    return redirect()->to(base_url('register/AdvSignUp'));
                } else {
                    $add_adv = $this->Register_model->add_new_advocate_details($final_data);
                    if (!empty($add_adv)) {
                        //TODO:: Send Username and Password on email
                        $to_email = trim($_SESSION['adv_details']['email_id']);
                        $subject = "SC-EFM Registration Details";
                        $message = "Registered Successfully with user id: " . $_SESSION['adv_details']['mobile_no'] . " and one time password is: " . $one_time_password . " ,Please do not share it with any one.";
                        send_mail_msg($to_email, $subject, $message);
                        //END
                        $this->session->setFlashdata('msg_success', 'Registration Successful');
                        return redirect()->to(base_url(''));
                    } else {
                        $this->session->setFlashdata('msg', 'Registration Failed');
                        return redirect()->to(base_url('register/AdvSignUp'));
                    }
                }
            } else {
                $_SESSION['register_data'] = $data;
                return redirect()->to(base_url('register/AdvSignUp/upload'));
            }
        }
    }

    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function upload()
    {
        return render('responsive_variant.authentication.adv_upload_view');
    }

    function upload_photo()
    {
        if ($_FILES["advocate_image"]['type'] != 'image/jpeg') {
            echo "1@@@" . 'Profile Image Only JPEG/JPG are allowed in document upload .';
            exit(0);
        }
        if (mime_content_type($_FILES["advocate_image"]['tmp_name']) != 'image/jpeg') {
            echo "1@@@" . 'Profile Image Only JPEG/JPG are allowed in document upload .';
            exit(0);
        }
        if (substr_count($_FILES["advocate_image"]['name'], '.') > 1) {
            echo "1@@@" . 'Profile Image No double extension allowed in JPEG/JPG .';
            exit(0);
        }
        if (preg_match("/[^0-9a-zA-Z\s.,-_ ]/i", $_FILES["advocate_image"]['name'])) {
            echo "1@@@" . 'Profile Image JPEG/JPG file name max. length can be 45 characters only. JPEG/JPG file name may contain digits, characters, spaces, hyphens and underscores .';
            exit(0);
        }
        if (strlen($_FILES["advocate_image"]['name']) > File_FIELD_LENGTH) {
            echo "1@@@" . 'Profile Image JPEG/JPG file name max. length can be 45 characters only. JPEG/JPG file name may contain digits, characters, spaces, hyphens and underscores.';
            exit(0);
        }
        if ($_FILES["advocate_image"]['size'] > UPLOADED_FILE_SIZE) {
            $file_size = (UPLOADED_FILE_SIZE / 1024) / 1024;
            echo "1@@@" . 'Profile Image JPEG/JPG uploaded should be less than ' . $file_size . ' MB.';
            exit(0);
        }
        $new_filename = time() . rand() . ".jpeg";
        if (isset($_FILES["advocate_id_prof"]) && $_FILES["advocate_id_prof"]['type'] == 'image/jpeg') {
            $new_pdf_extens = ".jpeg";
        }
        $photo_file_path = "user_images/photo/" . $new_filename;
        $data = array(
            'profile_photo' => base_url() . $photo_file_path
        );
        $_SESSION['profile_image'] = $data;
        $thumb = $this->image_upload('advocate_image', $photo_file_path, $new_filename);
        $file_path_thumbs = '';
        if (!$thumb) {
            $_SESSION['login']['photo_path'] = $file_path_thumbs;
            echo "1@@@" . 'Please Upload Image Is Requerd.';
        }
        if ($thumb) {
            echo '<img class="image-preview" src="' . $_SESSION['profile_image']['profile_photo'] . ' " class="upload-preview" height="94" width="94" />';
        }
    }

    function upload_id_proof()
    {
        $file = $this->request->getFile('advocate_image');
        if($_FILES["advocate_image"]['type'] != 'image/jpeg') {
            $this->session->setFlashdata('msg', 'Only JPEG/JPG are allowed in ID proof');
            return redirect()->to(base_url('register/AdvSignUp/upload'));
            exit(0);
        }
        if(mime_content_type($_FILES["advocate_image"]['tmp_name']) != 'image/jpeg') {
            $this->session->setFlashdata('msg', 'Only JPEG/JPG are allowed in ID proof');
            return redirect()->to(base_url('register/AdvSignUp/upload'));
            exit(0);
        }
        if(substr_count($_FILES["advocate_image"]['name'], '.') > 1) {
            $this->session->setFlashdata('msg', 'Only JPEG/JPG are allowed in ID proof');
            return redirect()->to(base_url('register/AdvSignUp/upload'));
            exit(0);
        }
        if(preg_match("/[^0-9a-zA-Z\s.,-_ ]/i", $_FILES["advocate_image"]['name'])) {
            $this->session->setFlashdata('msg', 'ID proof Image JPEG/JPG file name max. length can be 45 characters only. JPEG/JPG file name may contain digits, characters, spaces, hyphens and underscores');
            return redirect()->to(base_url('register/AdvSignUp/upload'));
            exit(0);
        }
        if(strlen($_FILES["advocate_image"]['name']) > File_FIELD_LENGTH) {
            $this->session->setFlashdata('msg', 'ID proof Image JPEG/JPG file name max. length can be 45 characters only. JPEG/JPG file name may contain digits, characters, spaces, hyphens and underscores');
            return redirect()->to(base_url('register/AdvSignUp/upload'));
            exit(0);
        }
        if($_FILES["advocate_image"]['size'] > UPLOADED_FILE_SIZE) {
            $file_size = (UPLOADED_FILE_SIZE / 1024) / 1024;
            $this->session->setFlashdata('msg', 'ID proof Image JPEG/JPG uploaded should be less than ' . $file_size . ' MB');
            return redirect()->to(base_url('register/AdvSignUp/upload'));
            exit(0);
        }
        $new_filename = time() . rand() . ".jpeg";
        if ($_FILES["advocate_image"]['type'] == 'image/jpeg') {
            $new_pdf_extens = ".jpeg";
        }
        $photo_file_path = "user_images/photo/" . $new_filename;
        $data = array(
            'profile_photo' => base_url() . $photo_file_path
        );
        $_SESSION['image_and_id_view'] = $data;
        $_SESSION['profile_image'] = $data;
        $thumb = $this->image_upload('advocate_image', $photo_file_path, $new_filename);
        $file_path_thumbs = '';
        if (!$thumb) {
            $_SESSION['login']['photo_path'] = $file_path_thumbs;
            $this->session->setFlashdata('msg', 'Please Upload Image.');
            return redirect()->to(base_url('register/AdvSignUp/upload'));
        } else {
            $this->session->setFlashdata('msg', 'Successful.');
            return redirect()->to(base_url('register/AdvSignUp/upload'));
        }
    }

    function image_upload($images, $file_path, $file_temp_name)
    {
        $thumbnail_path = 'user_images/thumbnail/';
        $photo_path = 'user_images/photo/';
        $this->create_directory($thumbnail_path);
        $this->create_directory($photo_path);
        $config['upload_path'] = $photo_path;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['overwrite'] = true;
        $config['file_name'] = $file_temp_name;
        $file = $this->request->getFile($images);
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $file->move($photo_path, $file_temp_name);
            $data['picture'] = $file_temp_name;
            $sourcePath = $photo_path . $file_temp_name;
            $destinationPath = WRITEPATH . 'uploads/thumbnails/' . $file_temp_name;
            return $data;
        } else {
            return ['error' => 'File upload failed: ' . $file->getErrorString()];
        }
    }

    function create_thumbnail($filename, $thumbnail_path)
    {
        $config['source_image'] = 'user_images/photo/' . $filename;
        $config['new_image'] = $thumbnail_path . $filename;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 150; // Set your desired thumbnail width
        $config['height'] = 150; // Set your desired thumbnail height
        // Load the image library
        $image_lib = \Config\Services::image();
        $image_lib->initialize($config);
        // Create the thumbnail
        if (!$image_lib->resize(150, 150)) {
            return ['error' => $image_lib->display_errors()];
        }
    }

    function create_directory($path)
    {
        if (!is_dir($path)) {
            $uold = umask(0);
            if (!mkdir($path, 0777, true)) {
                die('Failed to create directory: ' . $path . ' - Error: ' . error_get_last()['message']);
            }
            umask($uold);
        }
    }

    function final_submit()
    {
        $adv_data = $_SESSION['register_data'];
        $one_time_password = $this->generateRandomString();
        $profie_photo = array('photo_path' => $_SESSION['profile_image']['profile_photo'], 'password'=>hash('sha256',$one_time_password));
        $id_proof = array('id_proof_path' => $_SESSION['image_and_id_view']['profile_photo']);
        $adv_data1 = array_merge($adv_data, $profie_photo);
        $final_data = array_merge($adv_data1, $id_proof);
        $already_exist = $this->Register_model->check_already_reg_email($final_data['emailid']);
        if (!empty($already_exist)) {
            $this->session->setFlashdata('msg', 'Already Registerd Email.');
            return redirect()->to(base_url('register/AdvSignUp'));
        } else {
            
            $add_adv = $this->Register_model->add_new_advocate_details($final_data);
            if (!empty($add_adv)) {
                $to_email = trim($_SESSION['adv_details']['email_id']);
                $subject = "SC-EFM Registration Details";
                $message = "Registered Successfully with user id: " . $final_data['moblie_number'] . " and one time password is: " . $one_time_password . " , Please do not share it with any one.";
                sendSMS('38', $final_data['moblie_number'], $message, SCISMS_Change_Password_OTP);
                send_mail_msg($to_email, $subject, $message);
                //END
                $this->session->setFlashdata('msg_success', 'Registration Successful.');
                return redirect()->to(base_url('/'));
            } else {
                $this->session->setFlashdata('msg', 'Registration Failed');
                return redirect()->to(base_url('register/AdvSignUp'));
            }
        }
    }

    function final_submit_ecopying()
    {
        $adv_data = $_SESSION['register_data'];
        $one_time_password = $this->generateRandomString();
        $profie_photo = array('photo_path' => $_SESSION['profile_image']['profile_photo'], 'password'=>hash('sha256',$one_time_password));
        $id_proof = array('id_proof_path' => !empty($_SESSION['image_and_id_view']) ? $_SESSION['image_and_id_view']['profile_photo'] : '');
        $adv_data1 = array_merge($adv_data, $profie_photo);
        $final_data = array_merge($adv_data1, $id_proof);
        $already_exist = $this->Register_model->check_already_reg_email($final_data['emailid']);
        //pr($already_exist);
        if (!empty($already_exist)) {
            $this->session->setFlashdata('msg', 'Already Registerd Email.');
            return redirect()->to(base_url('register/AdvSignUp'));
        } else {
           
            // $one_time_password = $_SESSION['user_created_password']; //$this->generateRandomString();
            $add_adv = $this->Register_model->add_new_advocate_details($final_data);
            if (!empty($add_adv)) {
                $to_email = trim($_SESSION['adv_details']['email_id']);
                $subject = "SC-EFM Registration Details";
                $message = "Registered Successfully with user id: " . $final_data['moblie_number'] . " and one time password is: " . $one_time_password . " , Please do not share it with any one.";
                sendSMS('38', $final_data['moblie_number'], $message, SCISMS_Change_Password_OTP);
                send_mail_msg($to_email, $subject, $message);
                $clientIP = getClientIP();
                $new_name = "";
                $allowedExts = array("webm");
                $temp = explode(".", $_FILES["file"]["name"]);
                $extension = end($temp);
                $mime = mime_content_type($_FILES["file"]["tmp_name"]); //file name
                $allowed = array("video/webm", "webm", "video/x-matroska");
                $file_name = $_FILES['file']['name'];
                $file_type = $_FILES['file']['type'];
                $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if (empty($_FILES["file"]["name"])) {
                    $array = array('status' => 'Empty file can not be uploaded');
                } else if ($_FILES["file"]["error"] > 0) {
                    $error_code = "Issue at Server/Error Return Code: " . $_FILES["file"]["error"];
                    $array = array('status' => $error_code);
                } else if (!($fileExtension == "webm" && in_array($mime, $allowed) && in_array($file_type, $allowed))) {
                    $array = array('status' => 'Only Valid Video file allowed');
                } else if ($_FILES["file"]["size"] > 12000000) {
                    $array = array('status' => 'Not more then 12 mb allowed');
                } else if (!(in_array($extension, $allowedExts) && $_FILES["file"]["type"] == "video/webm")) {
                    $array = array('status' => 'Only Valid file allowed');
                } else {
                    //exit();
                    //$master_to_path = $_SERVER['DOCUMENT_ROOT'] . "/copy_verify/attachments/video/";
                    //$master_to_path = "/uploaded_docs";
                    // $master_to_path = SAN_ROOT."SAN_eCopy/copy_verify/attachments/video";
                    $master_to_path = "SAN_eCopy/copy_verify/attachments/video";
                    //$master_to_path = "/var/www/html/public/SAN_eCopy/copy_verify/attachments/video";
                    if (!file_exists($master_to_path)) {
                        //mkdir($master_to_path, 2770, true);
                    }
                    chdir($master_to_path);
                    getcwd();
                    $new_name = md5(uniqid(rand(), TRUE)) . '.' . $extension;
                    $new_name = $master_to_path . "/" . $new_name;
                    //echo $new_name;
                    //die;
                    if (file_exists($new_name)) {
                        $array = array('status' => 'Sorry File already exist.');
                    } else {
                        $results = $this->ecoping_webservices->getUserAssets($final_data['moblie_number'], $to_email);
                        if (sizeof($results) > 0) {
                            //     //already done so no need to insert again
                            $array = array('status' => 'Records Already Available');
                        } else {
                            //print_r($_FILES);
                            //die;
                            //$file = $this->request->getFile('file');
                            //print_r($_FILES);
                            //echo file_get_contents($_FILES["file"]["tmp_name"]);
                            //die;
                            //echo $new_name;
                            //die;
                            //print_r($final_data);
                            //die;
                            if (file_put_contents($new_name,file_get_contents($_FILES["file"]["tmp_name"]))) {
                                $asset_array = array(
                                    "mobile" => $final_data['moblie_number'],
                                    "email" =>$final_data["emailid"],
                                    "asset_type" => '3',
                                    "id_proof_type" => '0',
                                    "diary_no" => '0',
                                    "video_random_text" => $_SESSION['text_speak'],
                                    "file_path" => base_url().''.str_replace(SAN_ROOT,'',$new_name),
                                    "entry_time_ip" => $clientIP
                                );
                                
                                $insert_user_asset = $this->ecoping_webservices->insert_user_assets($asset_array); //insert user assets
                                //print_r($insert_user_asset);
                                //die;
                                $json_insert_user_asset = $insert_user_asset;
                                if ($json_insert_user_asset->{'Status'} == "success") {
                                    $array = array('status' => 'success');
                                    $this->session->setFlashdata('msg_success', 'Registration Successful.');
                                    $_SESSION['video_verify_status'] = 1;
                                } else {
                                    $this->session->setFlashdata('errMsg', 'Unable to insert records Register.');
                                    $array = array('status' => 'Unable to insert records');
                                }
                            } else {
                                $this->session->setFlashdata('errMsg', 'Not Allowed, Check Permission/Issue at Server.');
                                $array = array('status' => 'Not Allowed, Check Permission/Issue at Server');
                            }
                        }
                    }
                }
                //END
                
                return json_encode($array);
            } else {
                $this->session->setFlashdata('msg', 'Registration Failed');
                return redirect()->to(base_url('register/AdvSignUp'));
            }
        }
    }
}
