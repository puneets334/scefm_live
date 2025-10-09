<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Libraries\webservices\Efiling_webservices;
use App\Models\Admin\AdminDashboardModel;
use App\Models\Common\CommonModel;
use App\Models\Common\DropdownListModel;
use App\Models\Profile\ProfileModel;
use TCPDF;
use DateTime;

class Admin extends BaseController {

    protected $AdminDashboard_model;
    protected $Dropdown_list_model;
    protected $Profile_model;
    protected $Common_model;
    protected $efiling_webservices;
    protected $Admin_adv_model;

    public function __construct() {
        parent::__construct();
        $this->AdminDashboard_model = new AdminDashboardModel();
        $this->Dropdown_list_model = new DropdownListModel();
        $this->Profile_model = new ProfileModel();
        $this->Common_model = new CommonModel();
        $this->efiling_webservices = new Efiling_webservices();
    }

    public function index() {
        unset($_SESSION['efiling_details']);
        unset($_SESSION['search_case_data']);
        unset($_SESSION['estab_details']);
        unset($_SESSION['form_data']);
        unset($_SESSION['efiling_user_detail']);
        unset($_SESSION['pdf_signed_details']);
        unset($_SESSION['matter_type']);
        unset($_SESSION['crt_fee_and_esign_add']);
        unset($_SESSION['mobile_no_for_updation']);
        unset($_SESSION['email_id_for_updation']);
        unset($_SESSION['mobile_otp']);
        unset($_SESSION['email_otp']);
        unset($_SESSION['search_key']);
        unset($_SESSION['sbi_from_date']);
        unset($_SESSION['sbi_to_date']);
        $this->admin_dashboard();
    }

    public function admin_dashboard() {
        if ($this->session->userdata['login']['ref_m_usertype_id'] != USER_ADMIN && $this->session->userdata['login']['ref_m_usertype_id'] != USER_ACTION_ADMIN) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $data['count_efiling_data'] = $this->AdminDashboard_model->get_efilied_nums_stage_wise_count('');
        $this->render('admin.admin_dashboard', $data);
    }

    public function login_logs() {
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
            if (isset($_POST['frm1']) && isset($_POST['frm2'])) {
                if (!isDate($_POST['frm1']) || !isDate($_POST['frm2'])) {
                    $_SESSION['MSG'] = message_show("fail", "From date OR To date found incorrect!");
                    return redirect()->to(base_url('admin/login_logs'));
                    exit(0);
                }
            }
            if (isset($_POST['frm1']) && isset($_POST['frm2']) && isDate($_POST['frm1']) && isDate($_POST['frm2'])) {
                if (isset($_POST['frm1'])) {
                    $frm1 = date('Y-m-d', strtotime($_POST['frm1']));
                } else {
                    $date = strtotime("-7 day");
                    $frm1 = date('Y-m-d', $date);
                }
                if (isset($_POST['frm2'])) {
                    $frm2 = date('Y-m-d', strtotime($_POST['frm2']));
                } else {
                    $frm2 = date('Y-m-d');
                }
            } else {
                $date = strtotime("-7 day");
                $frm1 = date('Y-m-d', $date);
                $frm2 = date('Y-m-d');
            }
            $data['login_detail'] = $this->AdminDashboard_model->login_logs_details($frm1, $frm2);
            $data['frm1'] = $frm1;
            $data['frm2'] = $frm2;
            $this->render('login.login_log', $data);
        } else {
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    function encrypt_check($str) {
        $str = (int) url_decryption($str);
        if (!is_integer($str)) {
            $this->session->setFlashdata('encrypt_check', 'The {field} not valid');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function user() {
        if (isset($_SESSION['login']) && $_SESSION['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
            $data['high_court_list'] = $this->Dropdown_list_model->get_super_admin_high_court_list();
            $data['state_list'] = $this->Dropdown_list_model->get_super_admin_state_list();
            $data['user_list'] = $this->AdminDashboard_model->get_user_list();
            $this->render('admin.add_user_view', $data);
        } else {
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    public function add_user() {
        if (isset($_SESSION['login']) && $_SESSION['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
            $court = escape_data($_POST("court_type"));
            if ($court != ENTRY_TYPE_FOR_HIGHCOURT && $court != ENTRY_TYPE_FOR_ESTABLISHMENT && !empty($_POST['court_type'])) {
                echo message_show("fail", "Please choose valid Court type !");
                exit(0);
            }
            $user_id = url_decryption($_POST('user_id'));
            $useridArr = explode('.', $_POST['user_id']);
            if (!empty($useridArr[0])) {
                if (!$user_id && !empty($_POST['user_id'])) {
                    echo message_show("fail", "Record ID tempered !");
                    exit(0);
                }
            }
            $admin_type = escape_data($_POST('admin_type'));
            if ($admin_type != USER_DISTRICT_ADMIN && $admin_type != USER_MASTER_ADMIN && $admin_type != USER_ADMIN && $admin_type != USER_ACTION_ADMIN) {
                echo message_show("fail", "Please choose valid Admin type !");
                exit(0);
            }
            if ($admin_type == USER_DISTRICT_ADMIN || ($court == ENTRY_TYPE_FOR_ESTABLISHMENT)) {
                $state_id = escape_data(url_decryption($_POST("state_id")));
                $district_id = escape_data(url_decryption($_POST("distt_court_list")));
                if (!$state_id && !empty($_POST['state_id'])) {
                    echo message_show("fail", "Please choose State !");
                    exit(0);
                }
                if (!$district_id && !empty($_POST['distt_court_list'])) {
                    echo message_show("fail", "Please choose District !");
                    exit(0);
                }
            }
            if (($admin_type == USER_ADMIN || $admin_type == USER_MASTER_ADMIN || $admin_type == USER_ACTION_ADMIN) && $court == ENTRY_TYPE_FOR_ESTABLISHMENT) {
                $establisment_id = escape_data(url_decryption($_POST("establishment_list")));
                if (!$establisment_id && !empty($_POST['establishment_list'])) {
                    echo message_show("fail", "Please choose Court Establishment !");
                    exit(0);
                }
            }
            if (($admin_type == USER_ADMIN || $admin_type == USER_MASTER_ADMIN || $admin_type == USER_ACTION_ADMIN) && $court == ENTRY_TYPE_FOR_HIGHCOURT) {
                $hc_id = escape_data(url_decryption($_POST("high_court_id")));
                if (!$hc_id && !empty($_POST['high_court_id'])) {
                    echo message_show("fail", "Please choose High Court !");
                    exit(0);
                }
            }
            $login_id = escape_data($_POST("login_id"));
            if (empty($user_id)) {
                if (preg_match('/[^A-Za-z0-9-]/i', $login_id) || $login_id == NULL) {
                    echo message_show("fail", "User id is required and can only contain characters,numbers and hyphen!");
                    exit(0);
                }
            }
            $first_name = escape_data($_POST("first_name"));
            if (preg_match('/[^A-Za-z\s]/i', $first_name) || $first_name == NULL) {
                echo message_show("fail", "First Name is required and can contain only characters and spaces!");
                exit(0);
            }
            $last_name = escape_data($_POST("last_name"));
            if (preg_match('/[^A-Za-z\s]/i', $last_name) || $last_name == NULL) {
                echo message_show("fail", "Last Name is required and can contain only characters and spaces!");
                exit(0);
            }
            $gender = escape_data($_POST("gender"));
            if (!($gender == 'Male' || $gender == 'Female' ) || $gender == NULL) {
                echo message_show("fail", "Gender is required or it is wrongly posted!");
                exit(0);
            }
            $mobile_no = escape_data($_POST("mobile_no"));
            if (preg_match('/[^0-9]/i', $mobile_no) || strlen($mobile_no) != 10) {
                echo message_show("fail", "Invalid Mobile Number!");
                exit(0);
            }
            $email_id = escape_data($_POST("email_id"));
            $email_pattern = "/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/";
            if (!preg_match($email_pattern, $email_id)) {
                echo message_show("fail", "Invalid Email ID!");
                exit(0);
            }
            if ($admin_type == USER_DISTRICT_ADMIN) {
                $enrolled_state_id = $state_id;
                $enrolled_district_id = $district_id;
                $id = $enrolled_district_id;
                $entry_for_type_id = USER_DISTRICT_ADMIN;
                $entry_for_id = $enrolled_district_id;
                $enrolled_establishment_id = 0;
                $admin_type_id = USER_DISTRICT_ADMIN;
                $fetch_details_for = USER_DISTRICT_ADMIN;
            } else {
                if ($court == ENTRY_TYPE_FOR_HIGHCOURT) {
                    $enrolled_state_id = 9999;
                    $enrolled_district_id = 0;
                    $id = $hc_id;
                    $entry_for_type_id = ENTRY_TYPE_FOR_HIGHCOURT;
                    $entry_for_id = $hc_id;
                    $enrolled_establishment_id = $hc_id;
                    $admin_type_id = $admin_type;
                    $fetch_details_for = ENTRY_TYPE_FOR_HIGHCOURT;
                } else {
                    $enrolled_state_id = $state_id;
                    $enrolled_district_id = $district_id;
                    $id = $establisment_id;
                    $entry_for_type_id = ENTRY_TYPE_FOR_ESTABLISHMENT;
                    $entry_for_id = $establisment_id;
                    $enrolled_establishment_id = $establisment_id;
                    $admin_type_id = $admin_type;
                    $fetch_details_for = ENTRY_TYPE_FOR_ESTABLISHMENT;
                }
            }
            $address1 = "NA";
            $city = "NA";
            $ref_m_states_id = $enrolled_state_id;
            $pincode = "NA";
            if (empty($user_id)) {
                $data2 = array(
                    'userid' => strtoupper($login_id),
                    'moblie_number' => $mobile_no,
                    'emailid' => $email_id,
                    'admin_for_type_id' => $entry_for_type_id,
                    'admin_for_id' => $entry_for_id,
                    'ref_m_usertype_id' => $admin_type
                );
            } else {
                $data2 = array(
                    'id' => $user_id,
                    'userid' => strtoupper($login_id),
                    'moblie_number' => $mobile_no,
                    'emailid' => $email_id,
                    'admin_for_type_id' => $entry_for_type_id,
                    'admin_for_id' => $entry_for_id,
                    'ref_m_usertype_id' => $admin_type
                );
            }
            $admin_existence = $this->AdminDashboard_model->check_if_admin_already_exist($data2);
            $err_msg = '';
            $K = 0;
            foreach ($admin_existence as $result) {
                if ($result['exists'] == 'loginid') {
                    $err_msg .= 'Chosen Login-Id not available.';
                } elseif ($result['exists'] == 'mobile') {
                    $err_msg .= '<br/>User with same mobile number already exists.';
                } elseif ($result['exists'] == 'email') {
                    $err_msg .= '<br/>User with same email-id already exists.';
                } elseif ($result['exists'] == 'admin') {
                    $err_msg .= '<br/>Admin with same role for this Establishment already exists.';
                } elseif ($result['exists'] == 'multiple_admin') {
                    $err_msg .= "<br/>Admin only with role of 'Action Admin' is allowed for this Establishment.";
                }
            }
            if (!empty($err_msg)) {
                echo message_show("fail", $err_msg);
                exit(0);
            } elseif (empty($err_msg) && !$admin_existence && $admin_type == USER_ACTION_ADMIN) {
                echo message_show("fail", "Admin with role of 'Action Admin' can be created only when an admin with role of 'Master Admin' for chosen establishment exists; First create that. By doing this you will enable multiple admins for chosen establishment. ");
                exit(0);
            }
            $random_password = 'Admin@1234'; //time().rand('10,100');
            $login_pass = hash('sha256', $random_password);
            $date = date("Y-m-d H:i:s");
            $data = array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'ref_m_usertype_id' => $admin_type,
                'moblie_number' => $mobile_no,
                'emailid' => $email_id,
                'gender' => $gender,
                'dob' => "NA",
                'address1' => "NA",
                'city' => "NA",
                'ref_m_states_id' => $enrolled_state_id,
                'admin_for_type_id' => $entry_for_type_id,
                'admin_for_id' => $entry_for_id,
                'enrolled_state_id' => $enrolled_state_id,
                'enrolled_district_id' => $enrolled_district_id,
                'enrolled_establishment_id' => $enrolled_establishment_id,
                'pincode' => "NA",
                'photo_size_uploaded' => "NA",
                'ref_m_id_proof_type' => 0,
                'bar_reg_no' => 'AAA-00-0000',
                'is_active' => TRUE
            );
            if ($court == ENTRY_TYPE_FOR_HIGHCOURT) {
                $data == array_merge($data, array('high_court_id' => $hc_id));
            }
            $new_request = array(
                'userid' => strtoupper($login_id),
                'password' => $login_pass,
                'created_datetime' => $date,
                'created_by_ip' => $_SERVER['REMOTE_ADDR'],
                'updated_by_ip' => ''
            );
            $update_request = array(
                'updated_datetime' => $date,
                'updated_by_ip' => $_SERVER['REMOTE_ADDR']
            );
            if (empty($user_id)) {
                $data = array_merge($data, $new_request);
            } else {
                $data = array_merge($data, $update_request);
            }
            if ($admin_type_id == USER_ADMIN) {
                $position = 'ADMIN';
            } elseif ($admin_type_id == USER_MASTER_ADMIN) {
                $position = 'MASTER ADMIN';
            } elseif ($admin_type_id == USER_DISTRICT_ADMIN) {
                $position = 'DISTRICT ADMIN';
            } elseif ($admin_type_id == USER_ACTION_ADMIN) {
                $position = 'ACTION ADMIN';
            }
            $fetch_data = $this->AdminDashboard_model->court_details($id, $fetch_details_for);
            if (empty($user_id)) {
                $result = $this->AdminDashboard_model->add_user($data);
                if ($result) {
                    $user_name = $first_name . ' ' . $last_name;
                    $sentMAIL = 'You have been assigned the role of ' . $position . ' for ' . $fetch_data[0]['location'] . ' in efiling portal.
                             Your eFiling  </br>URL<b>' . base_url() . '</b>. </br>Username : <b>' . strtoupper($login_id) . '</b> </br> Password : <b>' . $random_password . '</b>';
                    $sentSMS = 'You have been assigned the role of ' . $position . ' for ' . $fetch_data[0]['location'] . ' in efiling portal. The login credentials has been sent to your registered email account. - Supreme Court of India';
                    send_mail_msg($email_id, 'Your eFiling Username and password', $sentMAIL, $user_name);
                    send_mobile_sms($mobile_no, $sentSMS,SCISMS_Efiling_Role_Assignment);
                    echo '1@@@' . message_show("success", "Admin added successfully!");
                    exit(0);
                } else {
                    echo message_show("fail", "Some things went wrong. Please Try again!");
                    exit(0);
                }
            } else {
                $result = $this->AdminDashboard_model->update_user($data, $user_id);
                $user_name = $first_name . ' ' . $last_name;
                if ($result) {
                    $sentMAIL = 'You Profile details in efiling portal is updated by District.';
                    $sentSMS = 'You Profile details in efiling portal is updated by District. - Supreme Court of India';
                    send_mail_msg($email_id, 'Profle update', $sentMAIL, $user_name);
                    send_mobile_sms($mobile_no, $sentSMS,SCISMS_Efiling_Profile_Update);
                    echo '1@@@' . message_show("success", "Records updated successfully!");
                    exit(0);
                } else {
                    echo message_show("fail", "Update Failed !");
                    exit(0);
                }
            }
        } else {
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    public function edit($id) {
        $id = url_decryption($id);
        if ($id) {
            if (isset($_SESSION['login']) && $_SESSION['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
                $result = array('id' => $id);
                setSessionData('edit_id', $result);
                $data['resData'] = $this->AdminDashboard_model->get_user_details($id);
                if (!isset($data['resData'][0]) || $data['resData'][0] == "") {
                    $_SESSION['MSG'] = message_show("fail", "Sorry some things went wrong please try again later !!!");
                    return redirect()->to(base_url('admin/user'));
                    exit(0);
                } else {
                    if ($data['resData'][0]->admin_for_type_id == ENTRY_TYPE_FOR_HIGHCOURT) {
                        $data['high_court_list'] = $this->Dropdown_list_model->get_super_admin_high_court_list();
                        if ($data['resData'][0]->high_court_id != '') {
                            $data['benchcourt_list'] = $this->Dropdown_list_model->get_bench_court_list($data['resData'][0]->high_court_id);
                        }
                    } elseif ($data['resData'][0]->admin_for_type_id == ENTRY_TYPE_FOR_ESTABLISHMENT) {
                        $val = $data['resData'][0]->enrolled_state_id;
                        $dist_val = $data['resData'][0]->enrolled_district_id;
                        $data['state_list'] = $this->Dropdown_list_model->get_super_admin_state_list();
                        $data['districts_list'] = $this->Dropdown_list_model->get_district_list($val);
                        $data['eshtablishment_list'] = $this->Dropdown_list_model->get_eshtablishment_list($dist_val);
                    } elseif ($data['resData'][0]->admin_for_type_id == USER_DISTRICT_ADMIN) {
                        $val = $data['resData'][0]->enrolled_state_id;
                        $dist_val = $data['resData'][0]->enrolled_district_id;
                        $data['state_list'] = $this->Dropdown_list_model->get_super_admin_state_list();
                        $data['districts_list'] = $this->Dropdown_list_model->get_district_list($val);
                    }
                    $data['user_list'] = $this->AdminDashboard_model->get_user_list();
                    $this->render('admin.add_user_view', $data);
                }
            } else {
                return redirect()->to(base_url('/'));
                exit(0);
            }
        } else {
            $_SESSION['MSG'] = message_show("fail", "Sorry some things went wrong please try again later !!!");
            return redirect()->to(base_url('admin/user'));
            exit(0);
        }
    }

    public function action($action_id, $status) {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN && $_SESSION['login']['ref_m_usertype_id'] != USER_DISTRICT_ADMIN && $_SESSION['login']['ref_m_usertype_id'] != USER_MASTER_ADMIN) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $redirect_url = '';
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN) {
            $redirect_url = 'master_admin/user';
        } else if ($_SESSION['login']['ref_m_usertype_id'] == USER_DISTRICT_ADMIN) {
            $redirect_url = 'district_admin/user';
        } else if ($_SESSION['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
            $redirect_url = 'admin/user';
        }
        $action_id = url_decryption($action_id);
        if (!$action_id || $action_id == '') {
            $_SESSION['MSG'] = message_show("fail", "Input Values tampered. !");
            return redirect()->to(base_url($redirect_url));
            exit(0);
        }
        $resdata = $this->AdminDashboard_model->get_user_details($action_id);
        $admin_type = $resdata[0]->ref_m_usertype_id;
        if ($action_id && $status == 'Active') {
            $data2 = array(
                'id' => $resdata[0]->id,
                'userid' => $resdata[0]->userid,
                'moblie_number' => $resdata[0]->moblie_number,
                'emailid' => $resdata[0]->emailid,
                'admin_for_type_id' => $resdata[0]->admin_for_type_id,
                'admin_for_id' => $resdata[0]->admin_for_id,
                'ref_m_usertype_id' => $resdata[0]->ref_m_usertype_id
            );
            $admin_existence = $this->AdminDashboard_model->check_if_admin_already_exist($data2);
            $err_msg = '';
            $K = 0;
            foreach ($admin_existence as $result) {
                if ($result['exists'] == 'loginid') {
                    $err_msg .= 'Chosen Login-Id not available.';
                } elseif ($result['exists'] == 'mobile') {
                    $err_msg .= '<br/>User with same mobile number already exists.';
                } elseif ($result['exists'] == 'email') {
                    $err_msg .= '<br/>User with same email-id already exists.';
                } elseif ($result['exists'] == 'admin') {
                    $err_msg .= '<br/>Admin with same role for this Establishment already exists.';
                } elseif ($result['exists'] == 'multiple_admin') {
                    $err_msg .= "<br/>Admin only with role of 'Action Admin' is allowed for this Establishment.";
                }
            }
            if (!empty($err_msg)) {
                $_SESSION['MSG'] = message_show("fail", $err_msg);
                return redirect()->to(base_url($redirect_url));
                exit(0);
            } elseif (empty($err_msg) && !$admin_existence && $admin_type == USER_ACTION_ADMIN) {
                $_SESSION['MSG'] = message_show("fail", "Admin with role of 'Action Admin' can be created only when an admin with role of 'Master Admin' for chosen establishment exists; First create that. By doing this you will enable multiple admins for chosen establishment. ");
                return redirect()->to(base_url($redirect_url));
                exit(0);
            }
            $result = $this->AdminDashboard_model->action_taken($action_id, $status);
            if ($result) {
                $_SESSION['MSG'] = message_show("success", "Status updated successfully!");
                return redirect()->to(base_url($redirect_url));
                exit(0);
            } else {
                $_SESSION['MSG'] = message_show("fail", "Some error occured !");
                return redirect()->to(base_url($redirect_url));
                exit(0);
            }
        } elseif ($action_id && $status == 'Deactive') {
            if ($resdata[0]->ref_m_usertype_id == USER_MASTER_ADMIN) {
                $res_data = $this->AdminDashboard_model->get_action_admins_list($action_id);
                if ($res_data) {
                    $_SESSION['MSG'] = message_show("fail", "Invalid Action ! First De-activate all admin with role of 'Action Admin' for this establishment.");
                    return redirect()->to(base_url($redirect_url));
                    exit(0);
                }
            } elseif ($resdata[0]->ref_m_usertype_id == USER_ACTION_ADMIN) {
                $res_data = $this->AdminDashboard_model->admin_allocated_efiling_nums($action_id);
                if ($res_data) {
                    $_SESSION['MSG'] = message_show("fail", "Invalid Action ! eFiling numbers are allocated to this admin; First re-allocate to another admin. ");
                    return redirect()->to(base_url($redirect_url));
                    exit(0);
                }
            } elseif ($resdata[0]->ref_m_usertype_id == USER_ADMIN) {
                $res_data = $this->AdminDashboard_model->admin_allocated_efiling_nums($action_id);
                if ($res_data) {
                    $_SESSION['MSG'] = message_show("fail", "Invalid Action ! eFiling numbers are allocated to this admin. ");
                    return redirect()->to(base_url($redirect_url));
                    exit(0);
                }
            }
            $result = $this->AdminDashboard_model->action_taken($action_id, $status);
            if ($result) {
                $_SESSION['MSG'] = message_show("success", "Status updated successfully!");
                return redirect()->to(base_url($redirect_url));
                exit(0);
            } else {
                $_SESSION['MSG'] = message_show("fail", "Status updated fail!");
                return redirect()->to(base_url($redirect_url));
                exit(0);
            }
        } else {
            return redirect()->to(base_url($redirect_url));
            exit(0);
        }
    }

    public function admin_users_list() {
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
            $data['user_list'] = $this->AdminDashboard_model->get_admin_users($_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
            $this->render('admin.users_view', $data);
        } else {
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    public function efiling_details($id = NULL) {
        if ($id == NULL) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
            $id = url_decryption($id);
            $InputArrray = explode('#', $id);   //0=>created_by,1=>stage
            if (empty($InputArrray[0]) && empty($InputArrray[1])) {
                return redirect()->to(base_url('/'));
                exit(0);
            }
            if (!is_numeric($InputArrray[0]) && !is_numeric($InputArrray[1])) {
                return redirect()->to(base_url('/'));
                exit(0);
            }
            $data['user_list'] = $this->AdminDashboard_model->get_efiling_details($InputArrray[0], $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id'], $InputArrray[1]);
            $data['stage'] = $InputArrray[1];
            $this->render('admin.efiling_num', $data);
        } else {
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    public function profile() {
        if ($this->session->userdata['efiling_user'] != "") {
            if ($this->session->userdata['efiling_user']['status'] != "deactive") {
                $data['profile'] = $this->Profile_model->getuserProfileDetail($this->session->userdata['efiling_user']['registration_id']);
            } else {
                $data['profile'] = $this->Profile_model->getdectiveuserProfileDetail($this->session->userdata['efiling_user']['registration_id']);
            }
            $data['remark'] = $this->Profile_model->deactivated_user_reason($this->session->userdata['efiling_user']['registration_id']);
            $this->render('profile.users_profile', $data);
        } else {
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    public function deactive_user() {
        if ($this->session->userdata['efiling_user'] != "") {
            $regid = $this->session->userdata['efiling_user']['registration_id'];
            $remark = $_POST('remark', TRUE);
            $date = date("Y-m-d H:i:s");
            $data = array('user_id' => $regid, 'remark' => $remark, 'deactived_on' => $date);
            $this->Profile_model->deactive_adminuser($this->session->userdata['efiling_user']['registration_id']);
            $this->Profile_model->add_deactive_adminuser($data);
            return redirect()->to(base_url('admin/admin_users_list'));
            exit(0);
        } else {
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    public function admin_deactive_users_list() {
        $data['user_list'] = $this->AdminDashboard_model->deactive_admin_userlist($_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
        $this->render('admin.deactive_users', $data);
    }

    public function activated_user() {
        if ($this->session->userdata['efiling_user'] != "") {
            $regid = $this->session->userdata['efiling_user']['registration_id'];
            $result = $this->Profile_model->activate_adminuser($regid);
            $details = $this->AdminDashboard_model->get_user_details($regid);
            $subject = 'Your account  with username ' . $details[0]->userid . ' has been activated for eFiling application. - Supreme Court of India';
            $Mail_message = 'Your account with username ' . $details[0]->userid . ' has been activated for eFiling application.';
            $user_name = $details[0]->first_name . " " . $details[0]->last_name;
            send_mobile_sms($details[0]->moblie_number, $subject,SCISMS_Account_Activation);
            send_mail_msg($details[0]->emailid, $subject, $Mail_message, $user_name);
            if ($result) {
                $_SESSION['MSG'] = message_show("success", "Status updated successfully!");
                return redirect()->to(base_url('admin/new_registration'));
                exit(0);
            } else {
                $_SESSION['MSG'] = message_show("fail", "Some things wrong !.Please try after some time!");
                return redirect()->to(base_url('admin/new_registration'));
                exit(0);
            }
        } else {
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    public function reject_user_popup() {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADMIN && $_SESSION['login']['ref_m_usertype_id'] != USER_MASTER_ADMIN) {
            echo 'Invalid admin (Not Authorized)';
            exit(0);
        }
        $user_id = $_POST('user_id');
        if (is_integer(url_decryption($_POST('user_id')))) {
            echo 'ERROR||||Something Wrong';
        } else {
            echo 'SUCCESS||||<div class="modal fade" id="myModal" role="dialog">               
              <div class="modal-dialog">
              <div class="modal-content">
              <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Reason for Reject User</h4>
              </div>
              <form action="' . base_url('admin/reject_user_action') . '" id="reject_user_action" method="post">
              <input type="hidden" name="' . $this->security->get_csrf_token_name() . '" value="' . $this->security->get_csrf_hash() . '">
              <input type="hidden" name="user_id" value="' . $user_id . '">
              <div class="modal-body">
              <div id="reject_user_action_alerts"></div>
                <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor-one">
                    <div class="btn-group">
                        <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                        <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>                        
                    </div>
                    <div class="btn-group">
                        <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
                        <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>                        
                    </div>
                    <div class="btn-group">
                        <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
                        <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                        <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
                        <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
                    </div>                   
                    <div class="btn-group">
                        <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
                        <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
                    </div>
                </div>
                <div id="editor-reason_to_reject" class="editor-wrapper placeholderText" contenteditable="true"></div>
                <textarea class="form-control" name="reason" id="reason_to_reject"  required  style="display:none;"></textarea>
                <span class="help-block" style="float:right"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a class="btn btn-primary" id="reject_frm_submit">Submit</a>
            </div>
             </form>
              </div>
              </div>
              </div>';
        }
    }

    public function reject_user_action() {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADMIN && $_SESSION['login']['ref_m_usertype_id'] != USER_MASTER_ADMIN) {
            echo 'Invalid admin (Not Authorized)';
            exit(0);
        }
        $remark_length = '';
        $user_id = (int) url_decryption($_POST('user_id'));
        $reason = $_POST('reason');
        $remark = script_remove($reason);
        $remark_length == strip_tags($remark);
        if (strlen($remark_length) > DISAPPROVE_REMARK_LENGTH) {
            $this->session->setFlashdata('MSG', '<div class="alert alert-danger text-center">Reason should be max 500 characters!</div>');
            return redirect()->to(base_url('admin/new_registration'));
        }
        if (empty($remark)) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Reason for rejection required.</div>');
            return redirect()->to(base_url('admin/new_registration'));
        }
        $user_detail = $this->AdminDashboard_model->get_user_details($user_id);
        if ($user_detail[0]->is_active == 'f') {
            $update_array = array(
                'emailid' => 'NULL' . $user_detail[0]->id,
                'moblie_number' => 'NULL' . $user_detail[0]->id,
                'reson_for_rejection' => $reason
            );
            $result = $this->AdminDashboard_model->update_user_detail($user_detail[0]->id, $update_array);
            if ($result) {
                $subject = 'Your account has been rejected from eFiling application. - Supreme Court of India';
                $Mail_message = 'Your account has been rejected from eFiling application. </br><b>Due to Reason :</b> ' . $reason;
                $user_name = $user_detail[0]->first_name . " " . $user_detail[0]->last_name;
                send_mobile_sms($user_detail[0]->moblie_number, $subject,SCISMS_Account_Rejection);
                send_mail_msg($user_detail[0]->emailid, $subject, $Mail_message, $user_name);
                setSessionData('MSG', '<div class="alert alert-success">User rejected Successfully.</div>');
                return redirect()->to(base_url('admin/new_registration'));
            } else {
                setSessionData('MSG', '<div class="alert alert-danger">Something Wrong.</div>');
                return redirect()->to(base_url('admin/new_registration'));
            }
        } else {
            setSessionData('MSG', '<div class="alert alert-danger">Something Wrong.</div>');
            return redirect()->to(base_url('admin/new_registration'));
        }
    }

    public function rejected_list() {
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN) {
            $data['new_user'] = $this->Profile_model->new_registered_user('rejected');
            $this->render('admin.new_registered_user', $data);
        } else {
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    public function new_active_user() {
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN) {
            $data['new_user'] = $this->Profile_model->new_active_user();
            $this->render('admin.active_users', $data);
        } else {
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    public function transaction_details() {
        if (isset($_SESSION['login']) && $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN) {
            if (isset($_POST['from_date']) && isset($_POST['to_date'])) {
                if (!isDate($_POST['from_date']) || !isDate($_POST['to_date'])) {
                    $_SESSION['MSG'] = message_show("fail", "From date OR To date found incorrect!");
                    return redirect()->to(base_url('report/transaction_details'));
                    exit(0);
                }
            }
            if (isset($_POST['from_date']) && isset($_POST['to_date']) && isDate($_POST['from_date']) && isDate($_POST['to_date'])) {
                $from_date = date('Y-m-d', strtotime($_POST['from_date']));
                $to_date = $_POST['to_date'];
                $data['frm1'] = $from_date;
                $data['frm2'] = $to_date;
            } else {
                $d1 = new DateTime('first day of this month');
                $e1 = $d1->format('Y-m-d');
                $e2 = date('Y-m-d');
                $from_date = $e1;
                $to_date = $e2;
                $data['frm1'] = $from_date;
                $data['frm2'] = $to_date;
            }
            $data['transaction_data'] = $this->AdminDashboard_model->pending_transactions($from_date, $to_date);
            $this->render('report.transaction_details', $data);
        } else {
            return redirect()->to(base_url('/'));
        }
    }

    public function pending_advocate_list() {
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN) {
            $admin_for_type_id = $_SESSION['login']['admin_for_type_id'];
            $data['advocate_list'] = $this->AdminDashboard_model->pending_advocate_list($admin_for_type_id);
            $this->render('admin.pending_advocate_list', $data);
        } else {
            return redirect()->to(base_url('/'));
        }
    }

    public function advocate_info_admin($user_id, $user_type) {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_MASTER_ADMIN && $_SESSION['login']['ref_m_usertype_id'] != USER_ADMIN && $_SESSION['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $user_id = url_decryption($user_id);
        $user_type = url_decryption($user_type);
        if ($user_type != USER_IN_PERSON && $user_type != USER_ADVOCATE) {
            $this->session->setFlashdata('err_msg', 'Invalid Id !');
            return redirect()->to(base_url('admin/new_active_user'));
            exit(0);
        }
        if ($user_id) {
            if (!preg_match("/^[0-9]*$/", $user_id) || (strlen($user_id) > INTEGER_FIELD_LENGTH)) {
                $this->session->setFlashdata('err_msg', 'Please choose valid advocate !');
                return redirect()->to(base_url('admin/new_active_user'));
                exit(0);
            }
        } else {
            $this->session->setFlashdata('err_msg', 'Please choose valid advocate !');
            return redirect()->to(base_url('admin/new_active_user'));
            exit(0);
        }
        if ($user_type == USER_ADVOCATE || $user_type == USER_IN_PERSON) {
            $data['new_advocate_info'] = $this->Admin_adv_model->new_adv_details($user_id, $user_type);
            if ($data['new_advocate_info'][0]->request_type == ACCOUNT_REQUEST_EXISTS_BUT_UPDATE) {
                $data['adv_legacy'] = $this->Admin_adv_model->adv_legacy_data($user_id);
            }
            if ($data['new_advocate_info'][0]->request_type == ACCOUNT_REQUEST_EXISTS_BUT_NEW) {
                $data['adv_exists_location'] = $this->Admin_adv_model->adv_existed_location($user_id);
            }
        }
        $this->render('admin.advocate_info_admin', $data);
    }

    public function advocate_info($user_id, $user_type) {
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON || $_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN) {
            $user_id = url_decryption($user_id);
            $user_type = url_decryption($user_type);
            if ($user_type != USER_IN_PERSON && $user_type != USER_ADVOCATE) {
                $this->session->setFlashdata('err_msg', 'Invalid Id !');
                return redirect()->to(base_url('admin/new_active_user'));
                exit(0);
            }
            if ($user_id) {
                if (!preg_match("/^[0-9]*$/", $user_id) || (strlen($user_id) > INTEGER_FIELD_LENGTH)) {
                    $this->session->setFlashdata('err_msg', 'Please choose valid advocate !');
                    return redirect()->to(base_url('admin/new_active_user'));
                    exit(0);
                }
            } else {
                $this->session->setFlashdata('err_msg', 'Please choose valid advocate !');
                return redirect()->to(base_url('admin/new_active_user'));
                exit(0);
            }
            if ($user_type == USER_ADVOCATE) {
                $data['advocate_info'] = $this->AdminDashboard_model->advocate_info($user_id);
            } elseif ($user_type == USER_IN_PERSON) {
                $data['advocate_info'] = $this->AdminDashboard_model->user_inperson_info($user_id);
            }
            $this->render('admin.advocate_info', $data);
        } else {
            return redirect()->to(base_url('/'));
        }
    }

    public function get_adv_register_status() {
        if (($_SESSION['login']['ref_m_usertype_id'] != USER_ACTION_ADMIN && $_SESSION['login']['ref_m_usertype_id'] != USER_ADMIN && $_SESSION['login']['ref_m_usertype_id'] != USER_DISTRICT_ADMIN && $_SESSION['login']['ref_m_usertype_id'] != USER_MASTER_ADMIN && $_SESSION['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN)) {
            return redirect()->to(base_url('/'));
            exit(0);
        } else {
            if ($this->session->userdata['login']) {
                $row_id = url_decryption($_POST['row_id']);
                $admin_for_type_id = url_decryption($_POST['admin_for_type_id']);

                if ($row_id) {
                    if (!preg_match("/^[0-9]*$/", $row_id) || (strlen($row_id) > INTEGER_FIELD_LENGTH)) {
                        $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> Please choose valid advocate !</div>');
                    }
                } else {
                    $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> Please choose valid advocate !</div>');
                }
                if ($admin_for_type_id) {
                    if (!preg_match("/^[0-9]*$/", $admin_for_type_id) || (strlen($admin_for_type_id) > INTEGER_FIELD_LENGTH)) {
                        $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> Please choose valid advocate !</div>');
                    }
                } else {
                    $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> Please choose valid advocate !</div>');
                }
                $results = $this->AdminDashboard_model->advocate_details($row_id, $admin_for_type_id);

                if ($results > 0) {
                    $reg_info = $this->efiling_webservices->getAdvocateInfo($results[0]['national_code'], $results[0]['bar_reg_no'], $results[0]['efiling_for_type_id'], $results[0]['state_code'], $results[0]['state_code']);
                    if (isset($reg_info['advocate_code']) && !empty($reg_info['advocate_code']) && isset($reg_info['advocate_name']) && !empty($reg_info['advocate_name'])) {
                        $update = $this->AdminDashboard_model->advocate_status_update($results[0]['login_id'], $results[0]['efiling_for_type_id'], $reg_info['advocate_name'], $reg_info['advocate_code'], $row_id);
                        if ($update) {
                            $_SESSION['MSG'] = message_show("success", "Advocate information successfully validated from your CIS !");
                            echo 'done';
                        } else {
                            $_SESSION['MSG'] = message_show("fail", "Some things wrong !.Please try after some time!");
                            echo 'error';
                        }
                    } else {
                        $_SESSION['MSG'] = message_show("fail", "Advocate information not present in your CIS !");
                        echo 'error';
                    }
                }
            } else {
                return redirect()->to(base_url('/'));
            }
        }
    }

    public function action_to_active_user($action_id, $status) {
        if (($_SESSION['login']['ref_m_usertype_id'] != USER_ACTION_ADMIN && $_SESSION['login']['ref_m_usertype_id'] != USER_ADMIN && $_SESSION['login']['ref_m_usertype_id'] != USER_DISTRICT_ADMIN && $_SESSION['login']['ref_m_usertype_id'] != USER_MASTER_ADMIN && $_SESSION['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN)) {
            return redirect()->to(base_url('/'));
            exit(0);
        } else {
            $action_id = url_decryption($action_id);
            if ($action_id) {
                $result = $this->AdminDashboard_model->action_to_active_user($action_id, $status);
                $details = $this->AdminDashboard_model->get_user_details($action_id);
                $subject = 'Your account has been ' . $status . ' from eFiling application';
                $Mail_message = '<table width="100%" align="justify" cellspacing="0" cellpadding="0" style="max-width:800px;border:solid 1px #e6e6e6">
                    <tbody>
                        <tr>
                            <td align="left" valign="top" style="color:#2c2c2c;display:block;font-weight:300;
                                margin:0 auto;clear:both;border-bottom:1px solid #e6e6e6;background-color:#f9f9f9;
                                padding:20px" bgcolor="#F9F9F9">
                                <p style="padding:0;margin:0;font-size:16px;font-size:13px">
                                    Dear Sir/Madam ,</p><br> <p style="padding:0;margin:0;color:#565656;font-size:13px"></p>
                                <p align="justify" style="padding:0;margin:0;color:#565656;line-height:18px;font-size:13px">
                                    Your account has been ' . $status . ' from eFiling application.</p> <br />
                                <strong>Regards</strong>, <br>
                                eCommittee Team.<br>
                                Contact : 011- 24632071-72<br>
                                <strong style="background-color: #f4e542" >Disclaimer :   This is system generated mail so please do not reply to this mail. </strong></p>
                            </td> 
                        </tr> 
                    </tbody>
                </table>';
                $user_name = $details[0]->first_name . " " . $details[0]->last_name;
                send_mail_msg($details[0]->emailid, $subject, $Mail_message, $user_name);
                if ($result) {
                    $_SESSION['MSG'] = message_show("success", "Status updated successfully!");
                    return redirect()->to(base_url('admin/new_active_user'));
                    exit(0);
                } else {
                    $_SESSION['MSG'] = message_show("fail", "Some things wrong !.Please try after some time!");
                    return redirect()->to(base_url('admin/new_active_user'));
                    exit(0);
                }
            } else {
                $_SESSION['MSG'] = message_show("fail", "Some things wrong !.Please try after some time!");
                return redirect()->to(base_url('admin/new_active_user'));
                exit(0);
            }
        }
    }
    
    public function get_session_value() {
        $form_id = url_decryption($_POST['form_submit']);
        unset($_SESSION['efiling_user_detail']);
        if (isset($form_id) && !empty($form_id) && preg_match("/^[0-9]*$/", $form_id)) {
            $reg_id = $_SESSION['form_data']['reg_id_' . $form_id];
            $status = $_POST['status'];
            unset($_SESSION['form_data']);
            if (isset($reg_id) && !empty($reg_id) && isset($status) && !empty($status)) {
                $session_data = array('registration_id' => $reg_id, 'status' => $status);
                setSessionData('efiling_user', $session_data);
                echo 'true';
            } else {
                echo 'false';
            }
        } else {
            echo 'false';
        }
    }

    public function update_user_status() {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADMIN && $_SESSION['login']['ref_m_usertype_id'] != USER_MASTER_ADMIN) {
            echo 'Invalid admin (Not Authorized)';
            exit(0);
        }
        $user_id = url_decryption($_POST['user_id']);
        $validate_nums = validate_number($user_id, TRUE, 1, 7, 'User Id');
        if ($validate_nums['response'] == FALSE) {
            echo 'invalid_id';
            exit(0);
        }
        $result = $this->Profile_model->activate_adminuser($user_id);
        if ($result) {
            $details = $this->AdminDashboard_model->get_user_details($user_id);
            $subject = 'Your account  with username ' . $details[0]->userid . ' has been activated for eFiling application. - Supreme Court of India';
            $Mail_message = 'Your account with username ' . $details[0]->userid . ' has been activated for eFiling application.';
            $user_name = $details[0]->first_name . " " . $details[0]->last_name;
            send_mobile_sms($details[0]->moblie_number, $subject,SCISMS_Account_Activation);
            send_mail_msg($details[0]->emailid, $subject, $Mail_message, $user_name);
            echo 'activated';
            exit(0);
        } else {
            echo 'fail_to_update';
            exit(0);
        }
    }

    function efiling_no_allocated() {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ACTION_ADMIN) {
            echo '1@@@' . htmlentities('This action is not allowed.', ENT_QUOTES);
            exit(0);
        }
        $id = $_POST['allocate_to_user'];
        $reason_type = $_POST['reason_type'];
        $reason_to_allocate = $_POST['reason_to_allocate'];
        if ($id != NULL && $reason_type != NULL) {
            $id = url_decryption($id);
            $InputArray = explode('#', $id);   //0=>registration_id,1=>type,2=>stage,3=>admin_user_id
            $reason_type = url_decryption($reason_type);
            $InputArray_reason = explode('#$', $reason_type);
            if (empty($InputArray[0]) && empty($InputArray[1]) && empty($InputArray[2]) && empty($InputArray_reason[0]) && empty($InputArray_reason[1])) {
                echo '1@@@' . htmlentities('Invalid request.', ENT_QUOTES);
                exit(0);
            }
            if (!is_numeric($InputArray[0]) && !is_numeric($InputArray[1]) && !is_numeric($InputArray[2]) && !is_numeric($InputArray_reason[0])) {
                echo '1@@@' . htmlentities('Invalid request.', ENT_QUOTES);
                exit(0);
            }
            if ($InputArray_reason[0] == '4') {
                if (empty($reason_to_allocate)) {
                    echo '1@@@' . htmlentities('Reason can\'t blank !', ENT_QUOTES);
                    exit(0);
                } else if (!empty($reason_to_allocate)) {
                    $validate_name = validate_names($reason_to_allocate, TRUE, 3, 250, 'Reason');
                    if ($validate_name['response'] == FALSE) {
                        echo '1@@@' . htmlentities($validate_name['msg']['field_name'], ENT_QUOTES);
                        exit(0);
                    } else {
                        $reason = $reason_to_allocate;
                    }
                }
            } else {
                $reason = $InputArray_reason[1];
            }
            $registration_id = $InputArray[0];
            $ref_m_efiled_type_id = $InputArray[1];
            $stage_id = $InputArray[2];
            $admin_user_id = $InputArray[3];
            $curr_dt_time = date('Y-m-d H:i:s');
            $data = array(
                'registration_id' => $registration_id,
                'admin_id' => $admin_user_id,
                'allocated_on' => $curr_dt_time,
                'updated_by' => $_SESSION['login']['id'],
                'updated_on' => $curr_dt_time,
                'update_ip' => $_SERVER['REMOTE_ADDR'],
                'reason_to_allocate' => $reason
            );
            $result = $this->Common_model->efiling_no_allocated($data, $ref_m_efiled_type_id);
            if ($result) {
                if ($ref_m_efiled_type_id == E_FILING_TYPE_NEW_CASE) {
                    $redirect_url = base_url('efile/new_case/processing/');
                } elseif ($ref_m_efiled_type_id == E_FILING_TYPE_MISC_DOCS) {
                    $redirect_url = base_url('efile/miscellaneousFiling/processing/');
                } elseif ($ref_m_efiled_type_id == E_FILING_TYPE_DEFICIT_COURT_FEE) {
                    $redirect_url = base_url('efile/deficit_court_fee/processing/');
                } elseif ($ref_m_efiled_type_id == E_FILING_TYPE_IA) {
                    $redirect_url = base_url('efile/IA/processing/');
                }
                $_SESSION['MSG'] = message_show("success", "Reason added successfully !");
                echo '2@@@' . htmlentities($redirect_url . url_encryption(trim($id)), ENT_QUOTES);
                exit(0);
            } else {
                echo '1@@@' . htmlentities('Invalid request.', ENT_QUOTES);
                exit(0);
            }
        } else {
            echo '1@@@' . htmlentities('Invalid request.', ENT_QUOTES);
            exit(0);
        }
    }

    public function case_type() {
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_DISTRICT_ADMIN) {
            $data['state_list'] = $this->Dropdown_list_model->get_state_list_esl();
            $data['high_court_list'] = $this->Dropdown_list_model->get_super_admin_high_court_list();
            $data['case_type_list'] = $this->AdminDashboard_model->get_case_type_list();
            $this->render('admin.add_cis_case_type', $data);
        } else {
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }    

    public function get_admintype() {
        $state_id = url_decryption(escape_data($_POST['state_id']));
        $dist_id = url_decryption(escape_data($_POST['dist_id']));
        $estab_id = url_decryption(escape_data($_POST['establishment_id']));
        if (!empty($state_id) && preg_match("/^[0-9]*$/", $state_id) && !empty($dist_id) && preg_match("/^[0-9]*$/", $dist_id) && !empty($estab_id) && preg_match("/^[0-9]*$/", $estab_id)) {
            $result = $this->AdminDashboard_model->get_est_masteradmin_type($state_id, $dist_id, $estab_id);
            $est_result = $this->AdminDashboard_model->get_est_admin_type($state_id, $dist_id, $estab_id);
            if ($est_result) {
                $est_user = "user_exist";
            } else {
                $est_user = '';
            }
            echo $est_user . '$#<option value=""> Select</option>';
            if (!$result) {
                echo '<option value="' . htmlentities(USER_MASTER_ADMIN, ENT_QUOTES) . '">' . '' . htmlentities(strtoupper('Master Admin'), ENT_QUOTES) . '</option>';
            }
            echo '<option value="' . htmlentities(USER_ACTION_ADMIN, ENT_QUOTES) . '">' . '' . htmlentities(strtoupper('Action Admin'), ENT_QUOTES) . '</option>';
        }
    }

    function get_hc_masteradmin_type() {
        $state_id = url_decryption(escape_data($_POST['get_state_id']));
        if (!empty($state_id) && preg_match("/^[0-9]*$/", $state_id)) {
            if (!empty($state_id)) {
                $result = $this->AdminDashboard_model->get_hc_masteradmin_type($state_id);
                $hc_result = $this->AdminDashboard_model->get_hc_admin_type($state_id);
                if ($hc_result) {
                    $hc_user = "user_exist";
                } else {
                    $hc_user = '';
                }
                echo $hc_user . '$#<option value=""> Select</option>';
                if (!$result) {
                    echo '<option value="' . htmlentities(USER_MASTER_ADMIN, ENT_QUOTES) . '">' . '' . htmlentities(strtoupper('Master Admin'), ENT_QUOTES) . '</option>';
                }
                echo '<option value="' . htmlentities(USER_ACTION_ADMIN, ENT_QUOTES) . '">' . '' . htmlentities(strtoupper('Action Admin'), ENT_QUOTES) . '</option>';
            } else {
                echo '<option value=""> Select </option>';
            }
        } else {
            echo '<option value=""> Select</option>';
        }
    }

    public function reject_adv_popup() {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADMIN && $_SESSION['login']['ref_m_usertype_id'] != USER_MASTER_ADMIN) {
            echo 'Invalid admin (Not Authorized)';
            exit(0);
        }
        $user_id_reg_obj = url_decryption($_POST('user_id'));
        $user_id = explode('##', $user_id_reg_obj);
        if ($user_id[1] == 'objection') {
            $header_name = 'Objection';
        } elseif ($user_id[1] == 'rejection') {
            $header_name = 'Rejection';
        } elseif ($user_id[1] == 'objection_cis_exists') {
            $header_name = 'Objection';
        }
        if (is_integer($user_id[0])) {
            echo 'ERROR||||Something Wrong';
        } else {
            echo 'SUCCESS||||<div class="modal fade" id="myModal" role="dialog">               
              <div class="modal-dialog">
              <div class="modal-content">
              <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Reason for ' . $header_name . '</h4>
              </div>
              <form action="' . base_url('admin/reject_adv_action') . '" id="reject_user_action" method="post">
              <input type="hidden" name="' . $this->security->get_csrf_token_name() . '" value="' . $this->security->get_csrf_hash() . '">
              <input type="hidden" name="user_id" value="' . url_encryption($user_id[0] . '##' . $user_id[1]) . '">
              <div class="modal-body">
              <div id="reject_user_action_alerts"></div>
                <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor-one">                      
                    <div class="btn-group">
                        <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                        <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>                        
                    </div>
                    <div class="btn-group">
                        <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
                        <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>                        
                    </div>
                    <div class="btn-group">
                        <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
                        <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                        <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
                        <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
                    </div>                   
                    <div class="btn-group">
                        <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
                        <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
                    </div>
                </div>
                <div id="editor-reason_to_reject" class="editor-wrapper placeholderText" contenteditable="true"></div>
                <textarea class="form-control" name="reason" id="reason_to_reject"  required  style="display:none;"></textarea>
                <span class="help-block" style="float:right"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a class="btn btn-primary" id="reject_frm_submit">Submit</a>
            </div>
             </form>
              </div>
              </div>
              </div>';
        }
    }

    public function reject_adv_action() {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADMIN && $_SESSION['login']['ref_m_usertype_id'] != USER_MASTER_ADMIN && $_SESSION['login']['ref_m_usertype_id'] != USER_BAR_COUNCIL) {
            echo 'Invalid admin (Not Authorized)';
            exit(0);
        }
        $remark_length = '';
        $user_id = url_decryption($_POST('user_id'));
        $user_id_objection = explode('##', $user_id);
        if ($user_id_objection[1] == 'objection' || $user_id_objection[1] == 'objection_cis_exists' || $user_id_objection[1] == 'rejection') {
            $reason = $_POST('reason');
            $remark = script_remove($reason);
            $remark_length == strip_tags($remark);
            if (strlen($remark_length) > DISAPPROVE_REMARK_LENGTH) {
                $this->session->setFlashdata('MSG', '<div class="alert alert-danger text-center">Reason should be max 500 characters!</div>');
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
            if (empty($remark)) {
                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Reason for rejection required.</div>');
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        }
        $user_detail = $this->Admin_adv_model->new_adv_details($user_id_objection[0]);
        $updated_on_date = date("Y-m-d H:i:s");
        if ($user_id_objection[1] == 'activate') {
            $new_account_status = ACCOUNT_STATUS_ACTIVE;
            $account_update_level = ACCOUNT_UPDATED_BY_E_ADMIN;
            $update_by = $_SESSION['login']['id'];
            $acc_update_status_remark = NULL;
            $username = $user_detail[0]->userid;
            $adv_user_id = $user_detail[0]->id;
            $request_type = ACCOUNT_REQUEST_PRACTICE_PLACE;
            $request_status = ACCOUNT_REQUEST_STATUS_APPROVED;
            $active_status = 'TRUE';
            $cis_existence_code = NULL;
            $drag_n_drop_request_type = NULL;
            $drag_n_drop_request_status_curr = NULL;
            $drag_n_drop_request_status_new = NULL;
            $drag_n_drop_request_status_update_level = NULL;
        } elseif ($user_id_objection[1] == 'objection') {
            $new_account_status = ACCOUNT_STATUS_OBJECTION;
            $account_update_level = ACCOUNT_UPDATED_BY_E_ADMIN;
            $update_by = $_SESSION['login']['id'];
            $acc_update_status_remark = $reason;
            $username = $user_detail[0]->userid;
            $adv_user_id = $user_detail[0]->id;
            $request_type = ACCOUNT_REQUEST_PRACTICE_PLACE;
            $request_status = ACCOUNT_REQUEST_STATUS_OBJECTION;
            $active_status = 'TRUE';
            $cis_existence_code = NULL;
            $drag_n_drop_request_type = NULL;
            $drag_n_drop_request_status_curr = NULL;
            $drag_n_drop_request_status_new = NULL;
            $drag_n_drop_request_status_update_level = NULL;
        } elseif ($user_id_objection[1] == 'objection_cis_exists') {
            $new_account_status = ACCOUNT_STATUS_ACTIVE_BUT_OBJ;
            $account_update_level = ACCOUNT_UPDATED_BY_E_ADMIN;
            $update_by = $_SESSION['login']['id'];
            $acc_update_status_remark = $reason;
            $username = $user_detail[0]->userid;
            $adv_user_id = $user_detail[0]->id;
            $request_type = ACCOUNT_REQUEST_EXISTS_BUT_UPDATE;
            $request_status = ACCOUNT_REQUEST_STATUS_ACTIVE_BUT_OBJ;
            $active_status = 'TRUE';
            $cis_existence_code = NULL;
            $drag_n_drop_request_type = NULL;
            $drag_n_drop_request_status_curr = NULL;
            $drag_n_drop_request_status_new = NULL;
            $drag_n_drop_request_status_update_level = NULL;
        } elseif ($user_id_objection[1] == 'rejection') {
            $new_account_status = ACCOUNT_STATUS_REJECTED;
            $account_update_level = ACCOUNT_UPDATED_BY_E_ADMIN;
            $update_by = $_SESSION['login']['id'];
            $acc_update_status_remark = $reason;
            $username = $user_detail[0]->userid;
            $adv_user_id = $user_detail[0]->id;
            $request_type = ACCOUNT_REQUEST_PRACTICE_PLACE;
            $request_status = ACCOUNT_REQUEST_STATUS_REJECTED;
            $active_status = 'TRUE';
            $cis_existence_code = NULL;
            if ($user_detail[0]->ref_m_usertype_id == USER_IN_PERSON) {
                $drag_n_drop_request_type = NULL;
                $drag_n_drop_request_status_curr = NULL;
                $drag_n_drop_request_status_new = NULL;
                $drag_n_drop_request_status_update_level = NULL;
            } else {
                $drag_n_drop_request_type = ACCOUNT_REQUEST_NEW_PENDING;
                $drag_n_drop_request_status_curr = ACCOUNT_REQUEST_STATUS_PENDING_AT_PRACTICE_PLACE;
                $drag_n_drop_request_status_new = ACCOUNT_REQUEST_STATUS_REJECTED;
                $drag_n_drop_request_status_update_level = ACCOUNT_UPDATED_ON_PRACTICE_PLACE;
            }
        } elseif ($user_id_objection[1] == 'cis_check_status') {
            // new advocate check status from local CIS
            $new_account_status = ACCOUNT_STATUS_ACTIVE;
            $account_update_level = ACCOUNT_UPDATED_BY_E_ADMIN_CIS;
            $update_by = $_SESSION['login']['id'];
            $acc_update_status_remark = NULL;
            $adv_bar_reg_no = $user_detail[0]->bar_reg_no;
            $username = $user_detail[0]->userid;
            $adv_user_id = $user_detail[0]->id;
            $adv_estab_code = $_SESSION['login']['admin_estab_code'];
            $request_type = ACCOUNT_REQUEST_PRACTICE_PLACE;
            $request_status = ACCOUNT_REQUEST_STATUS_APPROVED;
            $active_status = 'TRUE';
            $cis_existence_code = NULL;
            $drag_n_drop_request_type = ACCOUNT_REQUEST_NEW_PENDING;
            $drag_n_drop_request_status_curr = ACCOUNT_REQUEST_STATUS_PENDING_AT_PRACTICE_PLACE;
            $drag_n_drop_request_status_new = ACCOUNT_REQUEST_STATUS_PENDING;
            $drag_n_drop_request_status_update_level = ACCOUNT_UPDATED_ON_PRACTICE_PLACE;
        } elseif ($user_id_objection[1] == 'cis_check_status_new_pending') {
            $account_update_level = ACCOUNT_UPDATED_BY_E_ADMIN_CIS;
            $adv_estab_code = $_SESSION['login']['admin_estab_code'];
            $adv_bar_reg_no = $user_detail[0]->bar_reg_no;
            $username = $user_detail[0]->userid;
            $adv_user_id = $user_detail[0]->id;
            $request_type = ACCOUNT_REQUEST_NEW_PENDING;
            $request_status = ACCOUNT_REQUEST_STATUS_APPROVED;
            $active_status = 'TRUE';
            $cis_existence_code = NULL;
            $drag_n_drop_request_type = NULL;
            $drag_n_drop_request_status_curr = NULL;
            $drag_n_drop_request_status_new = NULL;
            $drag_n_drop_request_status_update_level = NULL;
        } elseif ($user_id_objection[1] == 'cis_check_status_exists_update') {
            $new_account_status = ACCOUNT_STATUS_UPDATED;
            $account_update_level = ACCOUNT_UPDATED_BY_E_ADMIN_CIS;
            $adv_estab_code = $_SESSION['login']['admin_estab_code'];
            $adv_bar_reg_no = $user_detail[0]->bar_reg_no;
            $username = $user_detail[0]->userid;
            $adv_user_id = $user_detail[0]->id;
            $request_type = ACCOUNT_REQUEST_EXISTS_BUT_UPDATE;
            $request_status = ACCOUNT_REQUEST_STATUS_UPDATED;
            $active_status = 'TRUE';
            $cis_existence_code = NULL;
            $drag_n_drop_request_type = ACCOUNT_REQUEST_EXISTS_BUT_NEW;
            $drag_n_drop_request_status_curr = ACCOUNT_REQUEST_STATUS_PENDING_AT_PRACTICE_PLACE;
            $drag_n_drop_request_status_new = ACCOUNT_REQUEST_STATUS_PENDING;
            $drag_n_drop_request_status_update_level = ACCOUNT_UPDATED_ON_PRACTICE_PLACE;
        } elseif ($user_id_objection[1] == 'cis_check_status_exists_but_new') {
            $account_update_level = ACCOUNT_UPDATED_BY_E_ADMIN_CIS;
            $adv_estab_code = $_SESSION['login']['admin_estab_code'];
            $adv_bar_reg_no = $user_detail[0]->bar_reg_no;
            $username = $user_detail[0]->userid;
            $adv_user_id = $user_detail[0]->id;
            $request_type = ACCOUNT_REQUEST_EXISTS_BUT_NEW;
            $request_status = ACCOUNT_REQUEST_STATUS_APPROVED;
            $active_status = 'TRUE';
            $cis_existence_code = NULL;
            $drag_n_drop_request_type = NULL;
            $drag_n_drop_request_status_curr = NULL;
            $drag_n_drop_request_status_new = NULL;
            $drag_n_drop_request_status_update_level = NULL;
        }
        if ($user_id_objection[1] == 'cis_check_status' || $user_id_objection[1] == 'cis_check_status_new_pending' || $user_id_objection[1] == 'cis_check_status_exists_update' || $user_id_objection[1] == 'cis_check_status_exists_but_new') {
            $reg_info = $this->efiling_webservices->getAdvocateInfo(urlencode($adv_estab_code), urlencode($adv_bar_reg_no), $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['state_code'], $_SESSION['login']['state_code']);
            if (isset($reg_info['advocate_code']) && !empty($reg_info['advocate_code']) && isset($reg_info['advocate_name']) && !empty($reg_info['advocate_name'])) {
                $cis_existence_code = $reg_info['advocate_code'];
                $update_status = true;
            } else {
                $mes_objection_rejction = 'Advocate information not available in your local CIS !';
                setSessionData('MSG', '<div class="alert alert-danger text-center flashmessage">' . $mes_objection_rejction . ' </div>');
                echo 'SUCCESS||||';
                exit(0);
            }
        }
        if ($user_id_objection[1] != 'cis_check_status_new_pending' && $user_id_objection[1] != 'cis_check_status_exists_update' && $user_id_objection[1] != 'cis_check_status_exists_but_new') {
            $result = $this->Admin_adv_model->advocate_status_update($account_update_level, $update_by, $acc_update_status_remark, $username, $new_account_status);
        }
        if (($user_id_objection[1] == 'cis_check_status_new_pending' || $user_id_objection[1] == 'cis_check_status_exists_update' || $user_id_objection[1] == 'cis_check_status_exists_but_new') || ($result && ($user_id_objection[1] == 'objection' || $user_id_objection[1] == 'objection_cis_exists' || $user_id_objection[1] == 'rejection' || $user_id_objection[1] == 'activate' || $user_id_objection[1] == 'cis_check_status'))) {
            $estsb_request_update_status = $this->Admin_adv_model->adv_estab_request_status_update($adv_user_id, $request_type, $request_status, $drag_n_drop_request_type, $drag_n_drop_request_status_curr, $drag_n_drop_request_status_new, $drag_n_drop_request_status_update_level, $active_status, $_SESSION['login']['admin_estab_code'], $account_update_level, $cis_existence_code);
        }
        if (($user_id_objection[1] == 'cis_check_status_new_pending' || $user_id_objection[1] == 'cis_check_status_exists_update' || $user_id_objection[1] == 'cis_check_status_exists_but_new') || ($result && $estsb_request_update_status && ($user_id_objection[1] == 'objection' || $user_id_objection[1] == 'objection_cis_exists' || $user_id_objection[1] == 'rejection' || $user_id_objection[1] == 'activate' || $user_id_objection[1] == 'cis_check_status'))) {
            if ($user_id_objection[1] == 'activate') {
                $mes_objection_rejction = 'Account Activated';
                $subject = 'Your efiling account is activated.';
                $mail_message = 'Your account request for eFiling application is accpeted and account enabled as active.';
                setSessionData('MSG', '<div class="alert alert-success text-center flashmessage">User ' . $mes_objection_rejction . ' Successfully.</div>');
                echo 'SUCCESS||||';
            } elseif ($user_id_objection[1] == 'objection' || $user_id_objection[1] == 'objection_cis_exists') {
                $mes_objection_rejction = 'Request Placed under Objections';
                $subject = 'Your request for efiling account is placed under objection.';
                $mail_message = 'Your account request for eFiling application is placed under objections. </br><b>Due to  following Reason(s) : </b><br> ' . $reason;
                setSessionData('MSG', '<div class="alert alert-success text-center flashmessage">User ' . $mes_objection_rejction . ' Successfully.</div>');
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } elseif ($user_id_objection[1] == 'rejection') {
                $mes_objection_rejction = 'Account Request Rejected';
                $subject = 'Your account request for eFiling is rejected.';
                $mail_message = 'Your account request for eFiling application is rejected. </br><b>Due to following Reason(s) :</b><br> ' . $reason;
                setSessionData('MSG', '<div class="alert alert-success text-center flashmessage">User ' . $mes_objection_rejction . ' Successfully.</div>');
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } elseif ($user_id_objection[1] == 'cis_check_status') {
                $mes_objection_rejction = 'Advocate information validated from your local CIS and his account activated successfully.';
                $subject = 'Your efiling account is activated.';
                $mail_message = 'Your account request for eFiling application is accpeted and account enabled as active.';
                setSessionData('MSG', '<div class="alert alert-success text-center flashmessage">' . $mes_objection_rejction . ' .</div>');
                echo 'SUCCESS||||';
            } elseif ($user_id_objection[1] == 'cis_check_status_new_pending' || $user_id_objection[1] == 'cis_check_status_exists_update' || $user_id_objection[1] == 'cis_check_status_exists_but_new') {
                $mes_objection_rejction = 'Advocate information validated from your local CIS successfully.';
                if ($user_detail[0]->court_type == ENTRY_TYPE_FOR_HIGHCOURT) {
                    $court_name = $user_detail[0]->estab_name . ' High Court ( ' . $user_detail[0]->estab_code . ' )';
                } else {
                    $court_name = $user_detail[0]->estab_name . ', ' . $user_detail[0]->dist_name . ', ' . $user_detail[0]->state_name . ' ( ' . $user_detail[0]->estab_code . ' )';
                }
                $subject = 'Profile information updated in Court Establishment. - Supreme Court of India';
                $mail_message = 'Your profile information is updated successfully at ' . $court_name;
                setSessionData('MSG', '<div class="alert alert-success text-center flashmessage">' . $mes_objection_rejction . ' .</div>');
                echo 'SUCCESS||||';
            }
            $user_name = $user_detail[0]->first_name . " " . $user_detail[0]->last_name;
            send_mobile_sms($user_detail[0]->moblie_number, $subject,EFILING_PROFILES);
            send_mail_msg($user_detail[0]->emailid, $subject, $mail_message, $user_name);
        } else {
            setSessionData('MSG', '<div class="alert alert-danger text-center">Something Wrong.</div>');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }

    function print_profile() {
        ob_start();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(FALSE);
        $pdf->SetPrintFooter(TRUE);
        $pdf->SetAuthor('eCommittee, SCI');
        $pdf->SetTitle('eCommittee, SCI');
        $pdf->SetHeaderData('', 347, '', 054, '');
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->SetFont('helvetica', '', 10, '', true);
        $pdf->AddPage();
        ob_end_clean();
        $user_id = $_SESSION['login']['id'];
        $data['new_advocate_info'] = $this->Admin_adv_model->new_adv_details($user_id);
        $content_ack = $this->render('admin.print_profile', $data, TRUE);
        $output_file_name = $file_name_prefix . $_SESSION['efiling_details']['efiling_no'] . ".pdf";
        $pdf->writeHTML($content_ack . '', true, false, false, false, '');
        $img = $_SESSION['login']['photo_path'];
        $pdf->Output('example_001.pdf', 'I');
    }

    function uploadPhoto() {
        $file_path_thumbs = '';
        ///------START : Validation For IMAGE-------//
        if ($_FILES["advocate_image"]['type'] != 'image/jpeg') {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Profile Image Only JPEG/JPG are allowed in document upload !</div>');
            return redirect()->to(base_url('Advocate/loginProfile'));
            exit(0);
        }
        if (mime_content_type($_FILES["advocate_image"]['tmp_name']) != 'image/jpeg') {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Profile Image Only JPEG/JPG are allowed in document upload !</div>');
            return redirect()->to(base_url('Advocate/loginProfile'));
            exit(0);
        }
        if (substr_count($_FILES["advocate_image"]['name'], '.') > 1) {

            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Profile Image No double extension allowed in JPEG/JPG !</div>');
            return redirect()->to(base_url('Advocate/loginProfile'));
            exit(0);
        }
        if (preg_match("/[^0-9a-zA-Z\s.,-_ ]/i", $_FILES["advocate_image"]['name'])) {

            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Profile Image JPEG/JPG file name max. length can be 45 characters only. JPEG/JPG file name may contain digits, characters, spaces, hyphens and underscores !</div>');
            return redirect()->to(base_url('Advocate/loginProfile'));
            exit(0);
        }
        if (strlen($_FILES["advocate_image"]['name']) > File_FIELD_LENGTH) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Profile Image JPEG/JPG file name max. length can be 45 characters only. JPEG/JPG file name may contain digits, characters, spaces, hyphens and underscores!</div>');
            return redirect()->to(base_url('Advocate/loginProfile'));
            exit(0);
        }
        if ($_FILES["advocate_image"]['size'] > env('UPLOADED_FILE_SIZE')) {
            $file_size = (env('UPLOADED_FILE_SIZE') / 1024) / 1024;
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Profile Image JPEG/JPG uploaded should be less than ' . $file_size . ' MB!</div>');
            return redirect()->to(base_url('Advocate/loginProfile'));
            exit(0);
        }
        ///------END : Validation For IMAGE-------//
        ///------START : Validation For PDF-------// 
        if ($_FILES["advocate_id_prof"]['type'] != 'image/jpeg') {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Id Proof Only JPEG/JPG are allowed in document upload !</div>');
            return redirect()->to(base_url('Advocate/loginProfile'));
            exit(0);
        }
        if (mime_content_type($_FILES["advocate_id_prof"]['tmp_name']) != 'image/jpeg') {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Id Proof Only JPEG/JPG are allowed in document upload !</div>');
            return redirect()->to(base_url('Advocate/loginProfile'));
            exit(0);
        }
        if (substr_count($_FILES["advocate_id_prof"]['name'], '.') > 1) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Id Proof  No double extension allowed in JPEG/JPG document !</div>');
            return redirect()->to(base_url('Advocate/loginProfile'));
            exit(0);
        }
        if (preg_match("/[^0-9a-zA-Z\s.,-_ ]/i", $_FILES["advocate_id_prof"]['name'])) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Id Proof JPEG/JPG file name max. length can be 45 characters only. JPEG/JPG file name may contain digits, characters, spaces, hyphens and underscores !</div>');
            return redirect()->to(base_url('Advocate/loginProfile'));
            exit(0);
        }
        if (strlen($_FILES["advocate_id_prof"]['name']) > File_FIELD_LENGTH) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Id Proof JPEG/JPG file name max. length can be 45 characters only. JPEG/JPG file name may contain digits, characters, spaces, hyphens and underscores!</div>');
            return redirect()->to(base_url('Advocate/loginProfile'));
            exit(0);
        }
        if ($_FILES["advocate_id_prof"]['size'] > env('UPLOADED_FILE_SIZE')) {
            $file_size = (env('UPLOADED_FILE_SIZE') / 1024) / 1024;
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Id Proof JPEG/JPG uploaded should be less than ' . $file_size . ' MB!</div>');
            return redirect()->to(base_url('Advocate/loginProfile'));
            exit(0);
        }
        ///------END : Validation For PDF-------// 
        if ($_SESSION['advocate_register_type'] == 'adv_exits_search' || $_SESSION['advocate_register_type'] == 'new_advocate_register' . USER_ADVOCATE) {
            ///------START : Validation For IMAGE bar reg certificate-------//
            if ($_FILES["bar_reg_certificate"]['type'] != 'image/jpeg') {
                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Bar Reg. Certificate Only JPEG/JPG are allowed in document upload !</div>');
                return redirect()->to(base_url('Advocate/loginProfile'));
                exit(0);
            }
            if (mime_content_type($_FILES["bar_reg_certificate"]['tmp_name']) != 'image/jpeg') {
                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Bar Reg. Certificate Only JPEG/JPG are allowed in document upload !</div>');
                return redirect()->to(base_url('Advocate/loginProfile'));
                exit(0);
            }
            if (substr_count($_FILES["bar_reg_certificate"]['name'], '.') > 1) {
                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Bar Reg. Certificate No double extension allowed in JPEG/JPG !</div>');
                return redirect()->to(base_url('Advocate/loginProfile'));
                exit(0);
            }
            if (preg_match("/[^0-9a-zA-Z\s.,-_ ]/i", $_FILES["advocate_image"]['name'])) {
                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Bar Reg. Certificate JPEG/JPG file name max. length can be 45 characters only. JPEG/JPG file name may contain digits, characters, spaces, hyphens and underscores !</div>');
                return redirect()->to(base_url('Advocate/loginProfile'));
                exit(0);
            }
            if (strlen($_FILES["bar_reg_certificate"]['name']) > File_FIELD_LENGTH) {
                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Bar Reg. Certificate JPEG/JPG file name max. length can be 45 characters only. JPEG/JPG file name may contain digits, characters, spaces, hyphens and underscores!</div>');
                return redirect()->to(base_url('Advocate/loginProfile'));
                exit(0);
            }
            if ($_FILES["bar_reg_certificate"]['size'] > env('UPLOADED_FILE_SIZE')) {
                $file_size = (env('UPLOADED_FILE_SIZE') / 1024) / 1024;
                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Bar Reg. Certificate JPEG/JPG image uploaded should be less than ' . $file_size . ' MB!</div>');
                return redirect()->to(base_url('Advocate/loginProfile'));
                exit(0);
            }
        }
        ///------END : Validation For IMAGE bar reg certificate-------// 
        $new_filename = time() . rand() . ".jpeg";
        if ($_FILES["advocate_id_prof"]['type'] == 'image/jpeg') {
            $new_pdf_extens = ".jpeg";
        }
        if ($_FILES["advocate_id_prof"]['type'] == 'application/pdf') {
            $new_pdf_extens = ".pdf";
        }
        $new_pdf_file_name = time() . rand() . $new_pdf_extens;
        if ($_FILES["advocate_id_prof"]['type'] == 'image/jpeg') {
            $new_bar_pdf_extens = ".jpeg";
        }
        if ($_FILES["advocate_id_prof"]['type'] == 'application/pdf') {
            $new_bar_pdf_extens = ".pdf";
        }
        $new_bar_reg_name = time() . rand() . $new_bar_pdf_extens;
        $photo_file_path = "user_images/photo/" . $new_filename;
        $pdf_file_path = "user_images/id_proof/" . $new_pdf_file_name;
        $photo_thums_path = "user_images/thumbnail/" . $new_filename;
        $bar_reg_certificate_path = "user_images/barReg_Certificate/" . $new_bar_reg_name;
        $data = array(
            'profile_photo' => base_url() . $photo_file_path,
            'profile_thumbnail' => base_url() . $photo_thums_path,
            'id_proof_photo' => base_url() . $pdf_file_path,
            'bar_reg_certificate' => base_url() . $bar_reg_certificate_path
        );
        $id = $_SESSION['inserted_id'];
        $thumb = $this->image_upload('advocate_image', $photo_file_path, $new_filename);
        $upload_pdf = $this->pdf_file_upload('advocate_id_prof', $pdf_file_path, $new_pdf_file_name);
        $upload_pdf_bar_reg = $this->bar_reg_certificate_upload('bar_reg_certificate', $bar_reg_certificate_path, $new_bar_reg_name);
        if (!$thumb) {
            $_SESSION['login']['photo_path'] = $file_path_thumbs;
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Please Upload Image Is Requerd!</div>');
            return redirect()->to(base_url('Advocate/loginProfile'));
        }
        if (!$upload_pdf) {
            $_SESSION['login']['photo_path'] = $file_path_thumbs;
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Please id Proof Is Requerd!</div>');
            return redirect()->to(base_url('Advocate/loginProfile'));
        }
        if (!$upload_pdf_bar_reg) {
            $_SESSION['login']['photo_path'] = $file_path_thumbs;
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Please Upload Bar Reg. Certificate Is Requerd!</div>');
            return redirect()->to(base_url('Advocate/loginProfile'));
        }

        if ($thumb && $upload_pdf && $upload_pdf_bar_reg) {
            $_SESSION['login']['photo_path'] = $file_path_thumbs;
            $_SESSION['image_and_id_view'] = $data;
            $this->session->setFlashdata('msg', '<div class="alert alert-success text-center flashmessage">Successful!</div>');
            return redirect()->to(base_url('Advocate/loginProfile'));
        } else {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center flashmessage">Updation Failed !</div>');
            return redirect()->to(base_url('Advocate/loginProfile'));
        }
    }

    function image_upload($images, $file_path, $file_temp_name) {
        $thumbnail_path = 'user_images/thumbnail/';
        if (!is_dir($thumbnail_path)) {
            $uold = umask(0);
            if (mkdir('user_images/thumbnail/', 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($thumbnail_path . '/index.html', $html);
            }
            umask($uold);
        }
        $config['upload_path'] = 'user_images/photo/';
        $config['allowed_types'] = 'jpg|jpeg';
        $config['overwrite'] = TRUE;
        $config['file_name'] = $file_temp_name;
        $image_lib = \Config\Services::image();
        $image_lib->initialize($config);
        $image_lib->do_upload($images);
        $uploadData = $image_lib->data();
        $filename = $uploadData['file_name'];
        $data['picture'] = $filename;
        $this->_generate_thumbnail($filename);
        return $data;
    }

    function _generate_thumbnail($picture) {
        $this->load->library('image_lib');
        $config['image_library'] = 'gd2';
        $config['source_image'] = 'user_images/photo/' . $picture;
        $config['new_image'] = 'user_images/thumbnail/' . $picture;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 150;
        $config['height'] = 150;
        $image_lib = \Config\Services::image();
        $image_lib->initialize($config);
        $image_lib->resize(150, 150);
        return true;
    }

    function pdf_file_upload($images, $file_path, $new_pdf_file_name) {
        $config['upload_path'] = 'user_images/id_proof/';
        $config['allowed_types'] = 'jpg|jpeg';
        $config['overwrite'] = TRUE;
        $config['file_name'] = $new_pdf_file_name;
        $image_lib = \Config\Services::image();
        $image_lib->initialize($config);
        $image_lib->do_upload($images);
        $uploadData = $image_lib->data();
        return $uploadData;
    }

    function bar_reg_certificate_upload($images, $file_path, $new_pdf_file_name) {
        $bar_cer_path = 'user_images/barReg_Certificate/';
        if (!is_dir($bar_cer_path)) {
            $uold = umask(0);
            if (mkdir('user_images/barReg_Certificate/', 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($bar_cer_path . '/index.html', $html);
            }
            umask($uold);
        }
        $config['upload_path'] = 'user_images/barReg_Certificate/';
        $config['allowed_types'] = 'jpg|jpeg';
        $config['overwrite'] = TRUE;
        $config['file_name'] = $new_pdf_file_name;
        $image_lib = \Config\Services::image();
        $image_lib->initialize($config);
        $image_lib->do_upload($images);
        $uploadData = $image_lib->data();
        return $uploadData;
    }

    function updateProfile() {
        $user_id = $_SESSION['login']['id'];
        $profile_photo = url_decryption($_POST['thumb_path']);
        $id_proof = url_decryption($_POST['id_proof_path']);
        $bar_reg_certificate = url_decryption($_POST['bar_reg_certificate']);
        $update_profile = $this->Admin_adv_model->update_photo($user_id, $profile_photo, $id_proof, $bar_reg_certificate);
        if ($update_profile) {
            unset($_SESSION['image_and_id_view']);
            $this->session->setFlashdata('msg_objection', '<div class="alert alert-success text-center flashmessage">Profile photo, ID proof and Bar Reg. Certificate Updated Successful!</div>');
            return redirect()->to(base_url('Advocate/loginProfile'));
        }
    }

    function unset_upload() {
        unset($_SESSION['image_and_id_view']);
        return redirect()->to(base_url('Advocate/loginProfile'));
    }

    function case_allocation() {
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
            $data['high_court_list'] = $this->Dropdown_list_model->get_super_admin_high_court_list();
            $data['state_list'] = $this->Dropdown_list_model->get_super_admin_state_list();
            $this->render('admin.case_allocation_view', $data);
        }
    }

    function get_efiling_case() {
        $admin_id = url_decryption($_POST['admin_id']);
        $html1 = '<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="90%">
            <thead>
                <tr class="success input-sm" role="row" >
                    <th>#</th>
                    <th>Efiling No.</th>
                    <th>Select to allocate</th>
                </tr>
            </thead>
            <tbody>';
        $efiling_no = $this->AdminDashboard_model->get_efiling_num_list($admin_id);
        if ($efiling_no) {
            $i = 1;
            foreach ($efiling_no as $efile) {
                $html1 .= '<tr><td width="1%">' . htmlentities($i++, ENT_QUOTES) . '</td><td>'
                . htmlentities($efile->efiling_no, ENT_QUOTES) . '</td><td><input type="checkbox" name=efil[] value="' . htmlentities(url_encryption($efile->registration_id), ENT_QUOTES) . '"></td>   </tr>';
            }
        } else {
            $html1 .= '<tr><td colspan="2">No Records Found</td><td></tr>';
        }
        $html1 .= '</tbody></table>';
        echo '1@@@' . $html1;
    }

    function efiling_num_allocation_to() {
        $from_id = url_decryption($_POST['from_id']);
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN) {
            $redirect_url = 'admin/master_admin/case_allocation';
        } else if ($_SESSION['login']['ref_m_usertype_id'] == USER_DISTRICT_ADMIN) {
            $redirect_url = 'admin/district_admin/case_allocation';
        } else if ($_SESSION['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
            $redirect_url = 'admin/admin/case_allocation';
        }
        $to_id = url_decryption($_POST['to_id']);
        if (empty($from_id)) {
            $_SESSION['MSG'] = message_show("fail", "Enter From");
            return redirect()->to(base_url($redirect_url));
        }
        if (empty($to_id)) {
            $_SESSION['MSG'] = message_show("fail", "Enter To");
            return redirect()->to(base_url($redirect_url));
        }
        $efil_no=array();
        foreach ($_POST['efil'] as $efil) {
            $efil = url_decryption($efil);
            if (!empty($efil)) {
                $efil_no[] = $efil;
            }
        }
        if (count($efil_no) == 0) {
            $_SESSION['MSG'] = message_show("fail", "Select Efiling no.");
            return redirect()->to(base_url($redirect_url));
        }
        $result = $this->AdminDashboard_model->update_allocation($efil_no, $to_id, $from_id);
        if ($result) {
            $_SESSION['MSG'] = message_show("success", "Allocated Successfully!");
            return redirect()->to(base_url($redirect_url));
        } else {
            $_SESSION['MSG'] = message_show("fail", "Some error Occured");
            return redirect()->to(base_url($redirect_url));
        }
    }

    function get_estab_action_admin() {
        $$redirect_url = '';
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_DISTRICT_ADMIN) {
            $estab_id = url_decryption($_POST['est_id']);
            if (empty($estab_id)) {
                echo $_SESSION['MSG'] = message_show("fail", "Input Values tampered. !");
                return redirect()->to(base_url($redirect_url));
                exit(0);
            }
            $user_list = $this->AdminDashboard_model->district_action_admin($estab_id);
        } elseif ($_SESSION['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN) {
            $estab_id = url_decryption($_POST['est_id']);
            if (empty($estab_id)) {
                echo $_SESSION['MSG'] = message_show("fail", "Input Values tampered. !");
                return redirect()->to(base_url($redirect_url));
                exit(0);
            }
            $user_list = $this->AdminDashboard_model->get_master_action_admin($estab_id);
        } elseif ($_SESSION['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
            if ($_POST['court_type'] == 1) {
                $estab_id = url_decryption($_POST['high_court_id']);
                $user_list = $this->AdminDashboard_model->get_high_court_user($estab_id);
            }
            if ($_POST['court_type'] == 2) {
                $estab_id = url_decryption($_POST['establishment_list']);
                $state_id = url_decryption($_POST['state_id']);
                $dist_id = url_decryption($_POST['distt_court_list']);
                $user_list = $this->AdminDashboard_model->estab_action_admin($state_id, $dist_id, $estab_id);
            }
        }
        echo '1@@@<option value=""> Select</option>';
        if (!empty($user_list)) {
            foreach ($user_list as $dataRes) {
                echo '<option value="' . htmlentities(url_encryption($dataRes->id), ENT_QUOTES) . '">' . htmlentities(strtoupper($dataRes->first_name . ' ' . $dataRes->last_name), ENT_QUOTES) . '</option>';
            }
        }
    }

}