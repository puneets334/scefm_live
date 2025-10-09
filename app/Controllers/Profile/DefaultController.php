<?php

namespace App\Controllers\Profile;

use App\Controllers\BaseController;
use App\Models\AdjournmentLetter\AdjournmentModel;
use App\Models\AdminDashboard\StageListModel;
use App\Models\Common\CommonModel;
use App\Models\Register\RegisterModel;
use App\Models\Common\DropdownListModel;
use App\Models\Common\CommonLoginModel;
use App\Models\Profile\ProfileModel;
use App\Libraries\AutoSanitize;
use stdClass;

class DefaultController extends BaseController
{

    protected $Adjournment_model;
    protected $Stageslist_model;
    protected $Common_model;
    protected $Register_model;
    protected $Dropdown_list_model;
    protected $Common_login_model;
    protected $Profile_model;
    protected $session;
    protected $db;
    protected $slice;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->slice = [];
        parent::__construct();
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        }else{
            is_user_status();
        }
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT, USER_ADMIN, USER_ADMIN_READ_ONLY, USER_EFILING_ADMIN, SR_ADVOCATE, ARGUING_COUNSEL, USER_SUPER_ADMIN,AMICUS_CURIAE_USER);
        if (!in_array($this->session->get('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return response()->redirect(base_url('/'));
            exit(0);
        }
        $this->Adjournment_model = new AdjournmentModel();
        $this->Stageslist_model = new StageListModel();
        $this->Common_model = new CommonModel();
        $this->Register_model = new RegisterModel();
        $this->Dropdown_list_model = new DropdownListModel();
        $this->Common_login_model = new CommonLoginModel();
        $this->Profile_model = new ProfileModel();
        $this->db = \Config\Database::connect();
    }

    public function cryptoJsAesDecrypt($passphrase, $jsonString)
    {
        $jsondata = json_decode($jsonString, true);
        try {
            $salt = $this->hex2bin($jsondata["s"]);
            $iv  = $this->hex2bin($jsondata["iv"]);
        } catch (\Exception $e) {
            return null;
        }
        $ct = base64_decode($jsondata["ct"]);
        $concatedPassphrase = $passphrase . $salt;
        $md5 = array();
        $md5[0] = md5($concatedPassphrase, true);
        $result = $md5[0];
        for ($i = 1; $i < 3; $i++) {
            $md5[$i] = md5($md5[$i - 1] . $concatedPassphrase, true);
            $result .= $md5[$i];
        }
        $key = substr($result, 0, 32);
        $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
        return json_decode($data, true);
    }

    private function hex2bin($hexdata)
    {
        $bindata = '';
        for ($i = 0; $i < strlen($hexdata); $i += 2) {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }
        return $bindata;
    }

    /**
     * Encrypt value to a cryptojs compatiable json encoding string
     *
     * @param mixed $passphrase
     * @param mixed $value
     * @return string
     */
    function cryptoJsAesEncrypt($passphrase, $value)
    {
        $salt = openssl_random_pseudo_bytes(8);
        $salted = '';
        $dx = '';
        while (strlen($salted) < 48) {
            $dx = md5($dx . $passphrase . $salt, true);
            $salted .= $dx;
        }
        $key = substr($salted, 0, 32);
        $iv  = substr($salted, 32, 16);
        $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
        $data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));
        return json_encode($data);
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function index()
    {
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        }else{
            is_user_status();
        }
        $session = session();
        $session->remove('data_arrays');
        if ($session->get('login')) {
            $data = [];
            $data['AOR'] = [];
            $aor_code = (int) $session->get('login')['aor_code'];
            $session->remove('email_id_for_updation');
            $session->remove('mobile_no_for_updation');
            // pr($_SESSION);
            $data['high_court_list'] = $this->Dropdown_list_model->get_high_court_list();
            $data['profile'] = $this->Profile_model->getProfileDetail($session->get('login')['userid']);
            $user_type_id = session()->get('login.ref_m_usertype_id');
            $aor_code = session()->get('login.aor_code');
            $data['user_type_id'] = $user_type_id;
            $data['aor_code'] = $aor_code;
            $header_view = ($_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON || $_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK || $_SESSION['login']['ref_m_usertype_id'] == SR_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == AMICUS_CURIAE_USER) ? 'templates/header' : 'templates/admin_header';
            return render('profile.profile_view', $data);
        } else {
            return redirect()->to(base_url('/'));
        }
    }

    public function updateProfile($param)
    {
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        }else{
            is_user_status();
        }
        helper('captcha_helper');
        $session = session();
        if (getSessionData('login')) {
            $data['updatedata'] = $param;
            if (!in_array($param, array('email', 'contact', 'other', 'address', 'pass', 'estab'))) {
                return redirect()->to(base_url('profile'));
            }
            $data['state_list'] = $this->Register_model->get_state_list();
            $data['profile'] = $this->Profile_model->getProfileDetail(getSessionData('login')['userid']);
            $session->set('login_salt', $this->generateRandomString());
            // pr($data);
            return render('profile.profile_update_view', $data);
        } else {
            return redirect()->to(base_url('/'));
        }
    }

    function valid_password($password = '')
    {
        $password = trim($password);
        $regex_lowercase = '/[a-z]/';
        $regex_uppercase = '/[A-Z]/';
        $regex_number = '/[0-9]/';
        $regex_special = '/[!@#$%^&*()\-_=+{};:,<.>ยง~]/';
        if (empty($password)) {
            return FALSE;
        }
        if (preg_match_all($regex_lowercase, $password) < 1) {
            return FALSE;
        }
        if (preg_match_all($regex_uppercase, $password) < 1) {
            return FALSE;
        }
        if (preg_match_all($regex_number, $password) < 1) {
            return FALSE;
        }
        if (preg_match_all($regex_special, $password) >= 1) {
            return TRUE;
        }
        if (strlen($password) < 8) {
            return FALSE;
        }
        if (strlen($password) > 74) {
            return FALSE;
        }
        return TRUE;
    }

    public function decodeData($pass, $data)
    {
        for ($i = 0; $i < 10; $i++) {
            $data = $this->cryptoJsAesDecrypt($pass, $data);
        }
        $dataArray = explode('hgtsd12@_hjytr', $data);
        return $dataArray[0];
    }

    public function updatePass()
    {
        helper(['form', 'url']);
        $session = session();
        // $profileModel = new ProfileModel();
        if ($session->get('login')) {
            $currentpass = esc($this->request->getPost("currentpass"));
            $newpass = esc($this->request->getPost("newpass"));
            $confirmpass = esc($this->request->getPost("confirmpass"));
            $salt = $_SESSION['new_salt'];
            $x = explode($salt, $newpass);
            $y = explode($salt, $confirmpass);
            $newpass = $x[0];
            $confirmpass = $y[0];
            $rules = [
                'currentpass' => [
                    'label' => 'Current Password',
                    'rules' => 'trim|required'
                ],
                'newpass' => [
                    'label' => 'New Password',
                    'rules' => 'trim|required'
                ],
                'confirmpass' => [
                    'label' => 'Confirm Password',
                    'rules' => 'trim|required'
                ]
            ];
            if ($this->validate($rules)) {
                $password = str_replace("'", "", $this->request->getPost('txt_password'));
                
                $decoded_password = $this->decodeData($salt, $password);
                $valid_password = $this->valid_password($decoded_password);
                if (!$valid_password) {
                    $session->setFlashdata('msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage">Password policy has to be followed.</div>');
                    return redirect()->to(base_url('profile/updateProfile/pass'));
                }
                $current_password = $this->Profile_model->selectPassword($session->get('login')['userid']);
                $currentpass = $currentpass;
                if ($current_password[0]->password . $salt == $currentpass) {
                    if ($newpass . $salt != $password) {
                        if ($newpass == $confirmpass) {
                            $resultCheck = $this->Profile_model->selectPasswordCheck($session->get('login')['id'], $newpass);
                            if (!$resultCheck) {
                                $this->Profile_model->updatePasswordCheck($session->get('login')['id'], $newpass);
                                $this->Profile_model->updatePassword($session->get('login')['userid'], $newpass);
                                $session->setFlashdata('passwordUpdated', '<div class="alert alert-success alert-dismissible text-center flashmessage">Password Updated!</div>');
                                return redirect()->to(base_url('profile/updateProfile/pass'));
                            } else {
                                $session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage" data-auto-dismiss="9000">Entered new password was already used earlier, please enter different password.</div>');
                                return redirect()->to(base_url('profile/updateProfile/pass'));
                            }
                        } else {
                            $session->setFlashdata('msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage">Confirm Password does not match!</div>');
                            return redirect()->to(base_url('profile/updateProfile/pass'));
                        }
                    } else {
                        $session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage" data-auto-dismiss="9000">New password should not be same as current password!</div>');
                        return redirect()->to(base_url('profile/updateProfile/pass'));
                    }
                } else {
                    $session->setFlashdata('msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage">Invalid Current Password!</div>');
                    return redirect()->to(base_url('profile/updateProfile/pass'));
                }
            } else {
                $data['profile'] = $this->Profile_model->getProfileDetail($session->get('login')['userid']);
                $data['updatedata'] = 'pass';
                return render('profile.profile_update_view', $data);
            }
        } else {
            return redirect()->to(base_url('/'));
        }
    }

    public function updateEmail()
    {
        $session = session();
        if ($session->get('login')) {
            $emailid = escape_data($this->request->getPost("emailid"));
            $email_pattern = "/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/";
            if (!preg_match($email_pattern, $emailid)) {
                $this->session->setFlashdata('msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage">Wrong email address !!</div>');
                return redirect()->to(base_url('profile/updateProfile/email'));
            }
            $check_email = $this->Common_login_model->check_email_already_present(strtoupper($emailid));
            if ($check_email) {
                $this->session->setFlashdata('msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage">This email id already registered !</div>');
                return redirect()->to(base_url('profile/updateProfile/email'));
            }
            //set validations
            // $this->form_validation->set_rules("emailid", "Email Id", "trim|required|valid_email");
            // if ($this->form_validation->run() == TRUE) {
            $rules = [
                "emailid" => [
                    "label" => "Email Id",
                    "rules" => "required|trim|valid_email"
                ]
            ];
            if ($this->validate($rules) === TRUE) {
                //validation succeeds
                if (isset($_POST['update_email'])) {
                    $previous_emailid = $this->Profile_model->selectEmail($_SESSION['login']['userid']);
                    if ($previous_emailid[0]->emailid != $emailid) {
                        $rand = rand(111111, 999999);
                        //session otp storage
                        $login = getSessionData('login');
                        $login['email_otp'] = $rand;
                        setSessionData('login', $login);
                        $data['email_otp'] = $rand;
                        $data['emailid'] = $emailid;
                        setSessionData('email_id_for_updation', $emailid);
                        $subject = 'Validate your email with eFiling application';
                        $Mail_message = 'Your OTP is ' . $rand . ' to validate new email-id with eFiling applications.';
                        $user_name = $_SESSION['login']['first_name'] . " " . $_SESSION['login']['last_name'];
                        send_mail_msg($emailid, $subject, $Mail_message, $user_name);
                        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON || $_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK || $_SESSION['login']['ref_m_usertype_id'] == AMICUS_CURIAE_USER) {
                            // $this->load->view('templates/header');
                        } else {
                            // $this->load->view('templates/admin_header');
                        }
                        return render('profile.profile_update_view', $data);
                        // $this->load->view('templates/footer');
                    } else {
                        $this->session->setFlashdata('msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage">Enter different email Id!</div>');
                        return redirect()->to(base_url('profile'));
                    }
                }
            } else {
                $data['profile'] = $this->Profile_model->getProfileDetail(getSessionData('login')['userid']);
                $data['updatedata'] = 'email';
                if ($_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON || $_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
                    // $this->load->view('templates/header');
                } else {
                    // $this->load->view('templates/admin_header');
                }
                return render('profile.profile_update_view', $data);
                // $this->load->view('templates/footer');
            }
        } else {
            return redirect()->to(base_url('/'));
        }
    }

    public function updateContact()
    {
        $session = session();
        if ($session->get('login')) {
            $mobile_number = escape_data($this->request->getPost("moblie_number"));
            if (!isset($mobile_number)) {
                return redirect()->to(base_url('/'));
            }
            if (!preg_match("/^[0-9]{10}$/", $mobile_number)) {
                $this->session->setFlashdata('msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage">Wrong contact number !!</div>');
                return redirect()->to(base_url('profile/updateProfile/contact'));
            }
            $check_mobno = $this->Common_login_model->check_mobno_already_present(strtoupper($mobile_number));
            if ($check_mobno) {
                $this->session->setFlashdata('msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage">This mobile number already registered !</div>');
                return redirect()->to(base_url('profile/updateProfile/contact'));
            }
            // $this->form_validation->set_rules("moblie_number", "Contact", "trim|required|numeric|min_length[10]|max_length[10]");
            // if ($this->form_validation->run() == TRUE) {
            $rules = [
                "moblie_number" => [
                    "label" => "Contact",
                    "rules" => "trim|required|numeric|min_length[10]|max_length[10]"
                ]
            ];
            if ($this->validate($rules) === TRUE) {
                //validation succeeds
                if (isset($_POST['moblie_number'])) {
                    $previous_mobile_number = $this->Profile_model->selectContact($_SESSION['login']['userid']);
                    if ($previous_mobile_number[0]->moblie_number != $mobile_number) {
                        $rand = rand(111111, 999999);
                        //session otp storage
                        $login = getSessionData('login');
                        $login['mobile_otp'] = $rand;
                        setSessionData('login', $login);
                        setSessionData('mobile_no_for_updation', $mobile_number);
                        $data['mobile_otp'] = $rand;
                        $data['moblie_number'] = $mobile_number;
                        $sentSMS = "OTP is " . $rand . " to validate your new mobile no. with eFiling application. - Supreme Court of India";
                        $responseObj = send_mobile_sms($mobile_number, $sentSMS, SCISMS_New_Mobile_No_Validation);
                        $user_name = $session->get('login')['first_name'] . ' ' . $session->get('login')['last_name'];
                        // $this->load->view('templates/header');
                        return render('profile.profile_update_view', $data);
                        // $this->load->view('templates/footer');
                    } else {
                        $this->session->setFlashdata('msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage">Enter different Contact!</div>');
                        return redirect()->to(base_url('profile'));
                    }
                }
            } else {
                $data['profile'] = $this->Profile_model->getProfileDetail(getSessionData('login')['userid']);
                $data['updatedata'] = 'contact';
                if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON || $_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
                    // $this->load->view('templates/header');
                } else {
                    // $this->load->view('templates/admin_header');
                }
                return render('profile.profile_update_view', $data);
                // $this->load->view('templates/footer');
            }
        } else {
            return redirect()->to(base_url('/'));
        }
    }

    public function updateOther()
    {
        $session = session();
        if ($session->get('login')) {
            $other_contact_number = escape_data($this->request->getPost("other_contact_number"));
            if (!preg_match("/^[0-9]{10}$/", $other_contact_number)) {
                $this->session->setFlashdata('msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage">Other contact number should be 10 digit !</div>');
                return redirect()->to(base_url('profile/updateProfile/other'));
            }
            // $this->form_validation->set_rules("other_contact_number", "Other Contact No.", "trim|numeric|min_length[10]|max_length[10]");
            // if ($this->form_validation->run() == TRUE) {
            $rules = [
                "other_contact_number" => [
                    "label" => "Other Contact No.",
                    "rules" => "trim|numeric|min_length[10]|max_length[10]"
                ]
            ];
            if ($this->validate($rules) === TRUE) {
                //validation succeeds
                if (isset($_POST['update_other'])) {
                    $previous_other_contact_number = $this->Profile_model->selectOtherContact(getSessionData('login')['userid']);
                    if ($previous_other_contact_number[0]->other_contact_number != $other_contact_number) {
                        $this->Profile_model->updateOtherContact(getSessionData('login')['userid'], $other_contact_number);
                        $this->session->setFlashdata('msg', '<div class="alert alert-success alert-dismissible text-center flashmessage">Other Contact No. Updated successfully !</div>');
                        return redirect()->to(base_url('profile'));
                    } else {
                        $this->session->setFlashdata('msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage">Enter different Contact!</div>');
                        return redirect()->to(base_url('profile/updateProfile/other'));
                    }
                }
            } else {
                $data['profile'] = $this->Profile_model->getProfileDetail(getSessionData('login')['userid']);
                $data['updatedata'] = 'other';
                if ($_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON || $_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
                    // $this->load->view('templates/header');
                } else {
                    // $this->load->view('templates/admin_header');
                }
                return render('profile.profile_update_view', $data);
                // $this->load->view('templates/footer');
            }
        } else {
            return redirect()->to(base_url('/'));
        }
    }

    // public function updateAddress()
    // {
    //     $session = session();
    //     if ($session->get('login')) {
    //         $data['address1'] = escape_data($this->request->getPost("address1"));
    //         $data['address2'] = escape_data($this->request->getPost("address2"));
    //         $data['city'] = escape_data($this->request->getPost("city"));
    //         $data['pincode'] = escape_data($this->request->getPost("pincode"));
    //         if (!empty($this->request->getPost("ref_m_states_id"))) {
    //             $data['ref_m_states_id'] = escape_data(url_decryption($this->request->getPost("ref_m_states_id")));
    //         } else {
    //             $district_id = explode("#$", url_decryption($_POST['njdg_m_dist_id']));
    //             $ref_m_states_id = explode("#$", url_decryption($_POST['njdg_m_states_id']));
    //             $data['njdg_dist_id'] = $district_id[0];
    //             $data['njdg_dist_name'] = $district_id[1];
    //             $data['njdg_st_id'] = $ref_m_states_id[0];
    //             $data['njdg_st_name'] = $ref_m_states_id[1];
    //         }
    //         $this->form_validation->set_rules("address1", "Address1", "trim|required|max_length[101]|regex_match[/^[a-z0-9.:,-_ ]+$/]");
    //         $this->form_validation->set_rules("address2", "Address2", "trim|max_length[101]|regex_match[/^[a-z0-9.:,-_ ]+$/]");
    //         $this->form_validation->set_rules("city", "City", "trim|required|alpha_numeric_spaces|max_length[101]");
    //         $this->form_validation->set_rules("pincode", "Pincode", "trim|required|numeric|min_length[6]|max_length[6]");
    //         if ($this->form_validation->run() == TRUE) {
    //             $this->Profile_model->updateAddress(getSessionData('login')['userid'], $data);
    //             $this->session->setFlashdata('msg', '<div class="alert alert-success alert-dismissible text-center flashmessage">Address Updated successfully !</div>');
    //             redirect('profile');
    //         } else {
    //             $data['profile'] = $this->Profile_model->getProfileDetail(getSessionData('login')['userid']);
    //             $data['updatedata'] = 'address';
    //             if ($_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON || $_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
    //                 $this->load->view('templates/header');
    //             } else {
    //                 $this->load->view('templates/admin_header');
    //             }
    //             $this->load->view('profile/profile_update_view', $data);
    //             $this->load->view('templates/footer');
    //         }
    //     } else {
    //         return redirect()->to(base_url('login'));
    //     }
    // }

    public function emailSave()
    {
        $session = session();
        if ($session->get('login')) {
            if (isset($_POST['email_save'])) {
                $emailid = escape_data(getSessionData('email_id_for_updation'));
                $otp = escape_data($this->request->getPost("email_otp"));
                if (!preg_match("/^[0-9]*$/", $otp)) {
                    $this->session->setFlashdata('msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage">Wrong OTP !!</div>');
                    return redirect()->to(base_url('profile'));
                }
                if ($otp == getSessionData('login')['email_otp']) {
                    $this->Profile_model->updateEmail(getSessionData('login')['userid'], $emailid);
                    unset($_SESSION['email_otp']);
                    unset($_SESSION['email_id_for_updation']);
                    $this->session->setFlashdata('msg', '<div class="alert alert-success alert-dismissible text-center flashmessage">Email Updated successfully !</div>');
                    return redirect()->to(base_url('profile'));
                } else {
                    $this->session->setFlashdata('msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage">Invalid OTP!</div>');
                    return redirect()->to(base_url('profile'));
                }
            }
        } else {
            return redirect()->to(base_url('login'));
        }
    }

    public function mobileSave()
    {
        $session = session();
        if ($session->get('login')) {
            if (isset($_POST['mobile_save'])) {
                $mobile_number = escape_data(getSessionData('mobile_no_for_updation'));
                $otp = $this->request->getPost("mobile_otp");
                if ($otp == $session->get('login')['mobile_otp']) {
                    $result = $this->Profile_model->updateContact($session->get('login')['userid'], $mobile_number);
                    if ($result) {
                        unset($_SESSION['mobile_otp']);
                        unset($_SESSION['mobile_no_for_updation']);
                        $this->session->setFlashdata('msg', '<div class="alert alert-success alert-dismissible text-center flashmessage">Contact Updated successfully!</div>');
                        return redirect()->to(base_url('profile'));
                    } else {
                        $this->session->setFlashdata('msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage">Updation Failed! Contact may be exist!</div>');
                        return redirect()->to(base_url('profile'));
                    }
                } else {
                    $this->session->setFlashdata('msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage">Invalid OTP!</div>');
                    return redirect()->to(base_url('profile'));
                }
            }
        } else {
            return redirect()->to(base_url('/'));
        }
    }

    // function uploadPhoto()
    // {
    //     $upload_id_bar = $_POST['edit_new_adv_idproof'];
    //     $update_profile = $_POST['profile_pic_upload_id'];
    //     ///------START : Validation For IMAGE-------//
    //     if ($_FILES["advocate_image"]['type'] != 'image/jpeg') {
    //         $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Profile Image Only JPEG/JPG are allowed in document upload !</div>');
    //         return redirect()->to(base_url('profile'));
    //         exit(0);
    //     }
    //     if (mime_content_type($_FILES["advocate_image"]['tmp_name']) != 'image/jpeg') {
    //         $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Profile Image Only JPEG/JPG are allowed in document upload !</div>');
    //         return redirect()->to(base_url('profile'));
    //         exit(0);
    //     }
    //     if (substr_count($_FILES["advocate_image"]['name'], '.') > 1) {
    //         $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Profile Image No double extension allowed in JPEG/JPG !</div>');
    //         return redirect()->to(base_url('profile'));
    //         exit(0);
    //     }
    //     if (preg_match("/[^0-9a-zA-Z\s.,-_ ]/i", $_FILES["advocate_image"]['name'])) {
    //         $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Profile Image JPEG/JPG file name max. length can be 45 characters only. JPEG/JPG file name may contain digits, characters, spaces, hyphens and underscores !</div>');
    //         return redirect()->to(base_url('profile'));
    //         exit(0);
    //     }
    //     if (strlen($_FILES["advocate_image"]['name']) > File_FIELD_LENGTH) {
    //         $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Profile Image JPEG/JPG file name max. length can be 45 characters only. JPEG/JPG file name may contain digits, characters, spaces, hyphens and underscores!</div>');
    //         return redirect()->to(base_url('profile'));
    //         exit(0);
    //     }
    //     if ($_FILES["advocate_image"]['size'] > UPLOADED_FILE_SIZE) {
    //         $file_size = (UPLOADED_FILE_SIZE / 1024) / 1024;
    //         $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Profile Image JPEG/JPG uploaded should be less than ' . $file_size . ' MB!</div>');
    //         return redirect()->to(base_url('profile'));
    //         exit(0);
    //     }
    //     $new_filename = time() . rand() . ".jpeg";
    //     if ($_FILES["advocate_id_prof"]['type'] == 'image/jpeg') {
    //         $new_pdf_extens = ".jpeg";
    //     }
    //     if ($_FILES["advocate_id_prof"]['type'] == 'application/pdf') {
    //         $new_pdf_extens = ".pdf";
    //     }
    //     $new_pdf_file_name = time() . rand() . $new_pdf_extens;
    //     if ($_FILES["advocate_id_prof"]['type'] == 'image/jpeg') {
    //         $new_bar_pdf_extens = ".jpeg";
    //     }
    //     if ($_FILES["advocate_id_prof"]['type'] == 'application/pdf') {
    //         $new_bar_pdf_extens = ".pdf";
    //     }
    //     $new_bar_reg_name = time() . rand() . $new_bar_pdf_extens;
    //     $this->load->library('upload');
    //     $photo_file_path = "uploaded_docs/user_images/photo/" . $new_filename;
    //     $pdf_file_path = "uploaded_docs/user_images/id_proof/" . $new_pdf_file_name;
    //     $photo_thums_path = "uploaded_docs/user_images/thumbnail/" . $new_filename;
    //     $bar_reg_certificate_path = "uploaded_docs/user_images/barReg_Certificate/" . $new_bar_reg_name;
    //     $data = array(
    //         'profile_photo' => $photo_file_path,
    //         'profile_thumbnail' => $photo_thums_path,
    //         'id_proof_photo' => $pdf_file_path,
    //         'bar_reg_certificate' => $bar_reg_certificate_path
    //     );
    //     $id = $_SESSION['inserted_id'];
    //     $thumb = $this->image_upload('advocate_image', $photo_file_path, $new_filename);
    //     $upload_pdf = $this->pdf_file_upload('advocate_id_prof', $pdf_file_path, $new_pdf_file_name);
    //     $upload_pdf_bar_reg = $this->bar_reg_certificate_upload('bar_reg_certificate', $bar_reg_certificate_path, $new_bar_reg_name);
    //     if (!$thumb) {
    //         $_SESSION['login']['photo_path'] = $photo_file_path;
    //         $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Please Upload Image Is Requerd!</div>');
    //         return redirect()->to(base_url('profile'));
    //     } else if ($thumb && $upload_pdf && $upload_pdf_bar_reg) {
    //         $_SESSION['login']['photo_path'] = $photo_file_path;
    //         $_SESSION['photo_path'] = $photo_file_path;
    //         $_SESSION['image_and_id_view'] = $data;
    //         $_SESSION['profile_photo'] = $update_profile;
    //         $this->Profile_model->updatePhoto(getSessionData('login')['userid'], $photo_file_path);
    //         $this->session->setFlashdata('msg', '<div class="alert alert-success text-center flashmessage">Successful!</div>');
    //         return redirect()->to(base_url('profile'));
    //     } else {
    //         $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Updation Failed !</div>');
    //         return redirect()->to(base_url('profile'));
    //     }
    // }

    // function image_upload($images, $file_path, $file_temp_name)
    // {
    //     $thumbnail_path = 'uploaded_docs/user_images/thumbnail/';
    //     if (!is_dir($thumbnail_path)) {
    //         $uold = umask(0);
    //         if (mkdir('uploaded_docs/user_images/thumbnail/', 0777, true)) {
    //             $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
    //             write_file($thumbnail_path . '/index.html', $html);
    //         }
    //         umask($uold);
    //     }
    //     $photo_path = 'uploaded_docs/user_images/photo/';
    //     if (!is_dir($photo_path)) {
    //         $uold = umask(0);
    //         if (mkdir('uploaded_docs/user_images/photo/', 0777, true)) {
    //             $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
    //             write_file($thumbnail_path . '/index.html', $html);
    //         }
    //         umask($uold);
    //     }
    //     $config['upload_path'] = './uploaded_docs/user_images/photo/';
    //     $config['allowed_types'] = 'jpg|jpeg';
    //     $config['overwrite'] = TRUE;
    //     $config['file_name'] = $file_temp_name;
    //     $this->load->library('upload');
    //     $this->upload->initialize($config);
    //     $this->upload->do_upload($images);
    //     $uploadData = $this->upload->data();
    //     $filename = $uploadData['file_name'];
    //     $data['picture'] = $filename;
    //     $this->_generate_thumbnail($filename);
    //     return $data;
    // }

    // function _generate_thumbnail($picture)
    // {
    //     $this->load->library('image_lib');
    //     $config['image_library'] = 'gd2';
    //     $config['source_image'] = 'uploaded_docs/user_images/photo/' . $picture;
    //     $config['new_image'] = 'uploaded_docs/user_images/thumbnail/' . $picture;
    //     $config['maintain_ratio'] = TRUE;
    //     $config['width'] = 150;
    //     $config['height'] = 150;
    //     $this->load->library('image_lib', $config);
    //     $this->image_lib->initialize($config);
    //     $this->image_lib->resize();
    //     return true;
    // }

    // function pdf_file_upload($images, $file_path, $new_pdf_file_name)
    // {
    //     $id_path = 'uploaded_docs/user_images/id_proof/';
    //     if (!is_dir($id_path)) {
    //         $uold = umask(0);
    //         if (mkdir('uploaded_docs/user_images/id_proof/', 0777, true)) {
    //             $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
    //             write_file($thumbnail_path . '/index.html', $html);
    //         }
    //         umask($uold);
    //     }
    //     $config['upload_path'] = 'uploaded_docs/user_images/id_proof/';
    //     $config['allowed_types'] = 'jpg|jpeg';
    //     $config['overwrite'] = TRUE;
    //     $config['file_name'] = $new_pdf_file_name;
    //     $this->load->library('upload', $config);
    //     $this->upload->initialize($config);
    //     $this->upload->do_upload($images);
    //     $uploadData = $this->upload->data();
    //     return $uploadData;
    // }

    // function bar_reg_certificate_upload($images, $file_path, $new_pdf_file_name)
    // {
    //     $bar_cer_path = 'uploaded_docs/user_images/barReg_Certificate/';
    //     if (!is_dir($bar_cer_path)) {
    //         $uold = umask(0);
    //         if (mkdir('uploaded_docs/user_images/barReg_Certificate/', 0777, true)) {
    //             $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
    //             write_file($bar_cer_path . '/index.html', $html);
    //         }
    //         umask($uold);
    //     }
    //     $config['upload_path'] = 'uploaded_docs/user_images/barReg_Certificate/';
    //     $config['allowed_types'] = 'jpg|jpeg';
    //     $config['overwrite'] = TRUE;
    //     $config['file_name'] = $new_pdf_file_name;
    //     $this->load->library('upload', $config);
    //     $this->upload->initialize($config);
    //     $this->upload->do_upload($images);
    //     $uploadData = $this->upload->data();
    //     return $uploadData;
    // }

    // function updateProfile_adv()
    // {
    //     $user_id = $_SESSION['login']['id'];
    //     $profile_photo_path = url_decryption($_POST['photo_path']);
    //     $profile_photo = url_decryption($_POST['thumb_path']);
    //     $id_proof = url_decryption($_POST['id_proof_path']);
    //     $bar_reg_certificate = url_decryption($_POST['bar_reg_certificate']);
    //     $update_profile = $this->Profile_model->update_photo($user_id, $profile_photo_path, $id_proof, $bar_reg_certificate);
    //     if ($update_profile) {
    //         unset($_SESSION['image_and_id_view']);
    //         $_SESSION['photo_path'] = $profile_photo;
    //         $this->session->setFlashdata('msg_objection', '<div class="alert alert-success text-center flashmessage">Profile photo Updated Successful!</div>');
    //         return redirect()->to(base_url('profile'));
    //     }
    // }

    // function unset_upload()
    // {
    //     unset($_SESSION['image_and_id_view']);
    //     return redirect()->to(base_url('profile'));
    // }
    
    function upload_photo() {
        // $upload_id_bar = $_POST['edit_new_adv_idproof'];
        $update_profile = $_POST['profile_pic_upload_id'];
        ///------START : Validation For IMAGE-------//
        if ($_FILES["advocate_image"]['type'] != 'image/jpeg') {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Profile Image Only JPEG/JPG are allowed in document upload.</div>');
            return redirect()->to(base_url('profile'));
            exit(0);
        }
        if (mime_content_type($_FILES["advocate_image"]['tmp_name']) != 'image/jpeg') {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Profile Image Only JPEG/JPG are allowed in document upload.</div>');
            return redirect()->to(base_url('profile'));
            exit(0);
        }
        if (substr_count($_FILES["advocate_image"]['name'], '.') > 1) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Profile Image No double extension allowed in JPEG/JPG.</div>');
            return redirect()->to(base_url('profile'));
            exit(0);
        }
        if (preg_match("/[^0-9a-zA-Z\s.,-_ ]/i", $_FILES["advocate_image"]['name'])) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Profile Image JPEG/JPG file name max. length can be 45 characters only. JPEG/JPG file name may contain digits, characters, spaces, hyphens and underscores.</div>');
            return redirect()->to(base_url('profile'));
            exit(0);
        }
        if (strlen($_FILES["advocate_image"]['name']) > File_FIELD_LENGTH) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Profile Image JPEG/JPG file name max. length can be 45 characters only. JPEG/JPG file name may contain digits, characters, spaces, hyphens and underscores.</div>');
            return redirect()->to(base_url('profile'));
            exit(0);
        }
        if ($_FILES["advocate_image"]['size'] > UPLOADED_FILE_SIZE) {
            $file_size = (UPLOADED_FILE_SIZE / 1024) / 1024;
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Profile Image JPEG/JPG uploaded should be less than ' . $file_size . ' MB.</div>');
            return redirect()->to(base_url('profile'));
            exit(0);
        }
        $new_filename = time() . rand() . ".jpeg";
        if (isset($_FILES["advocate_id_prof"]) && $_FILES["advocate_id_prof"]['type'] == 'image/jpeg') {
            $new_pdf_extens = ".jpeg";
        }
        $photo_file_path = "public/uploaded_docs/user_images/photo/" . $new_filename;
        $data = array(
            'profile_photo' => base_url() . $photo_file_path
        );
        $_SESSION['profile_image'] = $data;
        $thumb = $this->image_upload('advocate_image', $photo_file_path, $new_filename);
        $file_path_thumbs = '';
        // if (!$thumb) {
        //     $_SESSION['login']['photo_path'] = $file_path_thumbs;
        //     $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Please Upload Image Is Requerd.</div>');
        //     return redirect()->to(base_url('profile'));
        //     exit(0);
        // }
        // if ($thumb) {
        //     echo '<img class="image-preview" src="' . $_SESSION['profile_image']['profile_photo'] . ' " class="upload-preview" height="94" width="94" />';
        // }
            if (!$thumb) {
            $_SESSION['login']['photo_path'] = $photo_file_path;
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Please Upload Image Is Requerd!</div>');
            return redirect()->to(base_url('profile'));
        } else if ($thumb) {
            $_SESSION['login']['photo_path'] = $photo_file_path;
            $_SESSION['photo_path'] = $photo_file_path;
            $_SESSION['image_and_id_view'] = $data;
            $_SESSION['profile_photo'] = $update_profile;
            $this->Profile_model->updatePhoto(getSessionData('login')['userid'], $photo_file_path);
            $this->session->setFlashdata('msg', '<div class="alert alert-success text-center flashmessage">Successful!</div>');
            return redirect()->to(base_url('profile'));
        } else {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Updation Failed !</div>');
            return redirect()->to(base_url('profile'));
        }
    }

    function image_upload($images, $file_path, $file_temp_name) {
        $thumbnail_path = 'public/uploaded_docs/user_images/thumbnail/';
        $photo_path = 'public/uploaded_docs/user_images/photo/';
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
            return $data;
        } else {
            return ['error' => 'File upload failed: ' . $file->getErrorString()];
        }
    }

    function create_directory($path) {
        if (!is_dir($path)) {
            $uold = umask(0);
            if (!mkdir($path, 0777, true)) {
                die('Failed to create directory: ' . $path . ' - Error: ' . error_get_last()['message']);
            }
            umask($uold);
        }
    }
    
    function create_thumbnail($filename, $thumbnail_path) {
        $config['source_image'] = 'public/uploaded_docs/user_images/photo/' . $filename;
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

    function logged_profile()
    {
        $session = session();
        if ($session->get('login')) {
            unset($_SESSION['email_id_for_updation']);
            unset($_SESSION['mobile_no_for_updation']);
            $data['profile'] = $this->Profile_model->getProfileDetail(getSessionData('login')['userid']);
            $data['log_data'] = $this->Profile_model->getloggedDetail(getSessionData('login')['id']);
            if ($_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON || $_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
            //     $this->load->view('templates/header');
            } else {
            //     $this->load->view('templates/admin_header');
            }
            return render('profile.logged_profile', $data);
            // $this->load->view('templates/footer');
        } else {
            return redirect()->to(base_url('/'));
        }
    }

    function block_user()
    {
        $uri = service('uri');
        $ip = url_decryption($uri->getSegment(3));
        $userid = url_decryption($uri->getSegment(4));
        $status = url_decryption($uri->getSegment(5));
        // $ip = url_decryption($this->uri->segment(3));
        // $userid = url_decryption($this->uri->segment(4));
        // $status = url_decryption($this->uri->segment(5));
        if (empty($ip) || empty($userid) || empty($status)) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Something Wrong! Please Try again letter.</div>");
            return redirect()->to(base_url('profile/logged_profile'));
        }
        $result = $this->Profile_model->change_block_status($userid, $ip, $status);
        if ($result) {
            $this->session->setFlashdata('message', "<div class='alert alert-success fade in'>Status Updated Successfully .</div>");
            return redirect()->to(base_url('profile/logged_profile'));
        } else {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Something Wrong! Please Try again letter.</div>");
            return redirect()->to(base_url('profile/logged_profile'));
        }
    }
    public function verify($usr_result=null,$adv_type_select=null) {
        $usr_result = $this->Common_login_model->get_login_multi_account(getSessionData('login')['mobile_number']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($this->request->getPost("adv_type_select"))) {
                $adv_type_select = !empty($this->request->getPost("adv_type_select")) ? escape_data(url_decryption($this->request->getPost("adv_type_select"))) : NULL;
                if (!empty($adv_type_select)) {
                    //$usr_result = $_SESSION['login_multi_account'];
                    $impersonator_user = new stdClass();
                    $impersonated_user = new stdClass();
                    foreach ($usr_result as $row) {
                        if ($adv_type_select == $row->ref_m_usertype_id) {
                            $pg_request_function = $row->pg_request_function;
                            $pg_response_function = $row->pg_response_function;
                            $admin_estab_code = $row->estab_code;
                            $logindata = array(
                                'id' => $row->id,
                                'userid' => $row->userid,
                                'ref_m_usertype_id' => $row->ref_m_usertype_id,
                                'first_name' => $row->first_name,
                                'last_name' => $row->last_name,
                                'mobile_number' => $row->moblie_number,
                                'emailid' => $row->emailid,
                                'adv_sci_bar_id' => $row->adv_sci_bar_id,
                                'aor_code' => $row->aor_code,
                                'bar_reg_no' => $row->bar_reg_no,
                                'gender' => $row->gender,
                                'pg_request_fun' => $pg_request_function,
                                'pg_response_fun' => $pg_response_function,
                                'photo_path' => $row->photo_path,
                                //'state_name' => $state_name,
                                'login_active_session' => substr(number_format(time() * rand(), 0, '', ''), 0, 6),
                                'admin_for_type_id' => $row->admin_for_type_id,
                                'admin_for_id' => $row->admin_for_id,
                                'account_status' => $row->account_status,
                                'refresh_token' => $row->refresh_token,
                                //'bar_approval_status' => $row->bar_approval_status,
                                'impersonator_user' => $impersonator_user,//for efiling_assistant
                                'processid' => getmypid(),
                                'department_id' => $row->ref_department_id
                            );
                            // $sessiondata = array(
                            //     'login' => $logindata
                            // );
                            unset($_SESSION['login']);
                            setSessionData('login', $logindata);
                            // $this->session->sess_regenerate(TRUE);
                            return redirect()->to(base_url('/'));exit();
                            //redirect("profile/DefaultController");exit();
                        }
                    }
                }
            }
        }
    }
}