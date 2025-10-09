<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\SuperAdmin\SuperAdminModel;

class DefaultController extends BaseController
{
    protected $efiling_webservices;
    protected $SuperAdminModel;
    protected $request;

    public function __construct()
    {
        parent::__construct();
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $this->SuperAdminModel = new SuperAdminModel();
        $this->efiling_webservices = new Efiling_webservices();
        $this->request = \Config\Services::request();
    }

    public function index()
    {
        $allowed_users_array = array(USER_SUPER_ADMIN);
        if (empty(getSessionData('login')) || !in_array(getSessionData('login.ref_m_usertype_id'), $allowed_users_array)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $data = array();
        $params = array();
        $params['user_type'] = USER_ADMIN;
        $params['loginId'] = !empty(getSessionData('login')['id']) ? getSessionData('login')['id'] : NULL;
        $params['not_in_user_id'] = unserialize(USER_NOT_IN_LIST); // array(2660,2659,2658,2657,2656,2647);
        $response = $this->SuperAdminModel->getAssignedUser($params);
        $data['users'] = $response;
        $params = array();
        $params['table_name'] = "efil.m_tbl_efiling_type";
        $params['is_active'] = true;
        $params['id'] = array(1, 2, 4, 12);
        $filingType = $this->SuperAdminModel->getAllRecordFromTable($params);
        $data['filingType'] = $filingType;
        return $this->render('superAdmin.userlisting', $data);
    }

    public function registrationForm()
    {
        $allowed_users_array = array(USER_SUPER_ADMIN);
        if (!empty(getSessionData('login')) && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('superAdmin'));
            exit(0);
        }
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $data = array();
        $params = array();
        $params['table_name'] = "efil.m_tbl_efiling_type";
        $params['is_active'] = true;
        $params['id'] = array(1, 2, 4, 12);
        $filingType = $this->SuperAdminModel->getAllRecordFromTable($params);
        $data['filingType'] = $filingType;
        return $this->render('superAdmin.registration', $data);
    }

    public function getEmpDetails()
    {
        $allowed_users_array = array(USER_SUPER_ADMIN);
        if (!empty(getSessionData('login')) && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('superAdmin'));
            exit(0);
        }
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $output = array();
        if (!empty($this->request->getPost('empNo'))) {
            // $emp_id = trim(['empNo']);
            $emp_id = trim($this->request->getPost('empNo'));
            $params = [];
            $params['table_name'] = "efil.tbl_users";
            $params['whereFieldName'] = "emp_id";
            $params['whereFieldValue'] = (int)$emp_id;
            $params['is_active'] = '1';
            $userData = $this->SuperAdminModel->getData($params);
            if (isset($userData[0]->emp_id)) {
                $output = json_encode(array('status' => 'already', 'message' => 'User already exist!'));
            } else {
                $response = $this->efiling_webservices->getEmpDetailsByempId($emp_id);
                if (isset($response['user_details']) && !empty($response['user_details'])) {
                    $response['user_details'][0]['status'] = 'success';
                    $response['user_details'][0]['message'] = 'User data has been fetched successfully!';
                    $output = json_encode($response['user_details'][0]);
                } else {
                    $output = json_encode(array('status' => 'error', 'message' => 'No Records Found!'));
                }
            }
        }
        echo $output;
        exit(0);
    }

    public function addSciUser()
    {
        $allowed_users_array = array(USER_SUPER_ADMIN);
        if (!empty(getSessionData('login')) && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('superAdmin'));
            exit(0);
        }
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $postData = json_decode(file_get_contents('php://input'), true);
        $emp_name = '';
        $email = '';
        $empid = '';
        $mobile_no = '';
        $attend = '';
        $pp_a = '';
        $messageArr = array();
        $userid = '';
        $validationError = true;
        if (isset($postData['emp_name']) && !empty($postData['emp_name'])) {
            $emp_name = $postData['emp_name'];
        } else {
            $messageArr['status'] = "error";
            $messageArr['message'] = "Please fill emp. name.";
            $messageArr['id'] = "emp_name";
            $validationError = false;
        }
        if (isset($postData['email']) && !empty($postData['email'])) {
            $email =  $postData['email'];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $messageArr['status'] = "error";
                $messageArr['message'] = "Please fill valid email.";
                $messageArr['id'] = "email";
                $validationError = false;
            }
        }
        if (isset($postData['empid']) && !empty($postData['empid'])) {
            $empid = $postData['empid'];
        } else {
            $messageArr['status'] = "error";
            $messageArr['message'] = "Please fill emp. no.";
            $messageArr['id'] = "emp_name";
            $validationError = false;
        }
        if (isset($postData['attend']) && !empty($postData['attend'])) {
            $attend =  $postData['attend'];
        } else {
            $messageArr['status'] = "error";
            $messageArr['message'] = "Please fill emp. no.";
            $messageArr['id'] = "attend";
            $validationError = false;
        }
        if (isset($postData['mobile_no']) && !empty($postData['mobile_no'])) {
            $mobile_no =  $postData['mobile_no'];
            if (!preg_match('/^[0-9]{10}+$/', $mobile_no)) {
                $messageArr['status'] = "error";
                $messageArr['message'] = "Please fill valid mobile No.";
                $messageArr['id'] = "mobile";
                $validationError = false;
            }
        }
        if (isset($postData['pp_a']) && !empty($postData['pp_a'])) {
            $pp_a =  $postData['pp_a'];
        } else {
            $messageArr['status'] = "error";
            $messageArr['message'] = "Please select party in person/advocate.";
            $messageArr['id'] = "pp_a";
            $validationError = false;
        }
        $dob = !empty($postData['dob']) ? $postData['dob'] : NULL;
        $usercode = !empty($postData['usercode']) ? $postData['usercode'] : NULL;
        $filing_type = !empty($postData['filing_type']) ? $postData['filing_type'] : NULL;
        $pp_a =  !empty($postData['pp_a']) ? $postData['pp_a'] : NULL;
        if (isset($empid) && !empty($empid) && strlen($empid) == 4) {
            $userid = "SCI" . $empid;
        } else if (isset($empid) && !empty($empid) && strlen($empid) == 3) {
            $userid = "SCI0" . $empid;
        } else if (isset($empid) && !empty($empid) && strlen($empid) == 2) {
            $userid = "SCI00" . $empid;
        } else if (isset($empid) && !empty($empid) && strlen($empid) == 1) {
            $userid = "SCI000" . $empid;
        }
        $password = generateRandomString(10);
        $inArr = array();
        $inArr['userid'] = $userid;
        $inArr['emp_id'] = $empid;
        $inArr['ref_m_usertype_id'] = USER_ADMIN;
        $inArr['admin_for_type_id'] = USER_ADMIN;
        $inArr['admin_for_id'] = USER_ADVOCATE;
        $inArr['first_name'] = $emp_name;
        $inArr['moblie_number'] = $mobile_no;
        $inArr['emailid'] = $email;
        $inArr['account_status'] = 0;
        $inArr['created_on'] = date('Y-m-d H:i:s');
        $inArr['create_ip'] = getClientIP();
        $inArr['password'] = hash('sha256', $password);
        $inArr['is_active'] = 1;
        $inArr['attend'] = $attend;
        $inArr['dob'] = $dob;
        $inArr['icmis_usercode'] = $usercode;
        $inArr['pp_a'] = $pp_a;
        $tableName = "efil.tbl_users";
        // check to update
        $params['table_name'] = "efil.tbl_users";
        $params['whereFieldName'] = "emp_id";
        $params['whereFieldValue'] = (int)$empid;
        $params['is_active'] = '1';
        $userData = $this->SuperAdminModel->getData($params);
        if (isset($userData) && !empty($userData)) {
            $inArr['table_name'] = "efil.tbl_users";
            $response = $this->SuperAdminModel->updateTableData($inArr);
            if ($response) {
                $messageArr['status'] = "success";
                $messageArr['message'] = "User has been updated successfully!";
            }
        } else {
            if (isset($validationError) && !empty($validationError) && $validationError == true) {
                $insert_id = $this->SuperAdminModel->insertData($tableName, $inArr);
                $fileTypeInsert = array();
                $table_name = "efil.tbl_filing_admin_assigned_file";
                if (isset($insert_id) && !empty($insert_id)) {
                    if (isset($filing_type) && !empty($filing_type)) {
                        foreach ($filing_type as $k => $v) {
                            $tmp = array();
                            $tmp['user_id']  = $insert_id;
                            $tmp['createdby']  = !empty($this->session->userdata['login']['id']) ? $this->session->userdata['login']['id'] : NULL;
                            $tmp['createdat']  = date('Y-m-d H:i:s');
                            $tmp['file_type_id']  = $v;
                            $tmp['created_ip'] = getClientIP();
                            $fileTypeInsert[] = $tmp;
                        }
                        $response = $this->SuperAdminModel->insertBatchData($table_name, $fileTypeInsert);
                        if (isset($response) && !empty($response)) {
                            $smsRes = '';
                            if (isset($mobile_no) && !empty($mobile_no)) {
                                $message = 'Your userId: ' . $userid . ' and password: ' . $password;
                                //$smsRes = send_mobile_sms($mobile_no,$message);
                            }
                            if (isset($email) && !empty($email)) {
                                $message = 'Your userId: ' . $userid . ' and password: ' . $password;
                                $to_email = $email;
                                $subject = "User Registration.";
                                send_mail_msg($to_email, $subject, $message, $to_user_name = "");
                            }
                            $messageArr['status'] = "success";
                            $messageArr['message'] = "User has been added successfully!";
                        } else {
                            $messageArr['status'] = "error";
                            $messageArr['message'] = "Something went wrong,Please try again later!";
                        }
                    }
                } else {
                    $messageArr['status'] = "error";
                    $messageArr['message'] = "Something went wrong,Please try again later!";
                }
            }
        }
        echo json_encode($messageArr);
    }

    public function userListing()
    {
        $allowed_users_array = array(USER_SUPER_ADMIN);
        if (!empty(getSessionData('login')) && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('superAdmin'));
            exit(0);
        }
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $data = array();
        $params = array();
        $params['user_type'] = USER_ADMIN;
        $params['loginId'] = !empty($this->session->userdata['login']['id']) ? $this->session->userdata['login']['id'] : NULL;
        $params['not_in_user_id'] = unserialize(USER_NOT_IN_LIST); // array(2660,2659,2658,2657,2656,2647);
        $response = $this->SuperAdminModel->getAssignedUser($params);
        $data['users'] = $response;
        $params = array();
        $params['table_name'] = "efil.m_tbl_efiling_type";
        $params['is_active'] = true;
        $params['id'] = array(1, 2, 4, 12);
        $filingType = $this->SuperAdminModel->getAllRecordFromTable($params);
        $data['filingType'] = $filingType;
        $this->load->view('templates/admin_header');
        $this->load->view('superAdmin/userlisting', $data);
        $this->load->view('templates/footer');
    }
}
