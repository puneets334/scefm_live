<?php

namespace App\Controllers\FilingAdmin;

use App\Controllers\BaseController;
use App\Models\FilingAdmin\FilingAdminModel;
use App\Models\Report\ReportModel;
use App\Models\AdminDashboard\AdminDashboardModel;

class DefaultController extends BaseController
{

    protected $FilingAdminModel;
    protected $ReportModel;
    protected $session;
    protected $AdminDashboardModel;
    protected $slice;
    protected $agent;

    public function __construct()
    {
        parent::__construct();
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $this->FilingAdminModel = new FilingAdminModel;
        $this->ReportModel = new ReportModel;
        $this->AdminDashboardModel = new AdminDashboardModel();
        $this->session = \Config\Services::session();
        $this->agent = \Config\Services::request()->getUserAgent();
        // $this->slice = new Slice();
        helper(['form']);
    }

    public function index()
    {
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        if (empty(getSessionData('login'))) {
            return redirect()->to(base_url('/'));
        }
        $allowed_users_array = array(USER_EFILING_ADMIN);
        if (getSessionData('login') != '' && !in_array(getSessionData('login.ref_m_usertype_id'), $allowed_users_array)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $data = array();
        $params = array();
        $params['user_type'] = USER_ADMIN;
        $params['not_in_user_id'] = unserialize(USER_NOT_IN_LIST); //array(2660,2659,2658,2657,2656,2647);
        $response = $this->FilingAdminModel->getAssignedUser($params);
        $data['users'] = $response;
        $params = array();
        $params['is_active'] = true;
        $params['id'] = array(1, 2, 4, 12);
        // $filingType = $this->FilingAdminModel->getAllRecordFromTable($params);
        // $data['filingType'] = $filingType;
        $users_array = array(USER_ADMIN, USER_ADMIN_READ_ONLY, USER_EFILING_ADMIN);
        $users_read_array = array(USER_ADMIN_READ_ONLY, USER_EFILING_ADMIN);
        if (in_array($this->session->get('login')['ref_m_usertype_id'], $users_array)) {
            if (in_array($this->session->get('login')['ref_m_usertype_id'], $users_read_array)) {
                $AllUserCount = 1;
                $AllUserCountData = array_merge($this->session->get('login'), array('AllUserCount' => $AllUserCount));
                $this->session->set(array('login' => $AllUserCountData));
            } else {
                $AllUserCount = 0;
                $AllUserCountData = array_merge($this->session->get('login'), array('AllUserCount' => $AllUserCount));
                $this->session->set(array('login' => $AllUserCountData));
            }
        }
        $data['stage_list'] = $this->ReportModel->get_stage();
        $data['count_efiling_data'] = $this->AdminDashboardModel->get_efilied_nums_stage_wise_count();
        return $this->render('filingAdmin.fillingAdminDashboard', $data);
    }

    public function getEmpDetailsByUserId()
    {
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $resp = array();
        if (empty(session()->get('login')['ref_m_usertype_id'])) {
            return redirect()->to(base_url('/'));
        }
        $allowed_users_array = [USER_EFILING_ADMIN];
        if (!in_array(session()->get('login')['ref_m_usertype_id'], $allowed_users_array)) {
            //return redirect()->to('filingAdmin');
        }
        $output = [];
        $userId = $this->request->getPost('userId');
        if ($userId) {
            $params = [
                'user_type' => USER_ADMIN,
                'not_in_user_id' => unserialize(USER_NOT_IN_LIST),
                'userId' => $userId,
                'type' => 1
            ];
            $response = $this->FilingAdminModel->getAssignedUserByUserId($params);
            if (!empty($response[0])) {
                $resp = array(
                    'status' => 'success',
                    'message' => 'User data has been fetched successfully!',
                );
                $response[0] = array_merge((array)$response[0], $resp);
                $output = json_encode($response[0]);
            } else {
                $output = json_encode(['status' => 'error', 'message' => 'No Records Found!']);
            }
        } else {
            $output = json_encode(['status' => 'error', 'message' => 'No Records Found!']);
        }
        echo $output;
        exit(0);
    }

    public function updateUserRole()
    {
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $session = session();
        helper(['form', 'url']);
        if (empty($session->get('login')['ref_m_usertype_id'])) {
            return redirect()->to(base_url('/'));
        }
        $allowed_users_array = [USER_EFILING_ADMIN]; // Assuming USER_EFILING_ADMIN is defined somewhere
        if (!in_array($session->get('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('filingAdmin'));
        }
        $userId = $this->request->getPost('userId');
        $emp_name = $this->request->getPost('emp_name');
        $empid = (int) $this->request->getPost('empid');
        $filing_type = $this->request->getPost('filing_type');
        $pp_a = $this->request->getPost('pp_a');
        $attend = $this->request->getPost('attend');
        $output = [];
        $message = '';
        $validationStatus = true;
        if (empty($emp_name)) {
            $validationStatus = false;
            $output = [
                'status' => 'error',
                'message' => 'Please fill emp name.',
                'fieldId' => 'emp_name'
            ];
        }
        if (empty($empid)) {
            $validationStatus = false;
            $output = [
                'status' => 'error',
                'message' => 'Please fill emp Id.',
                'fieldId' => 'empid'
            ];
        }
        if (empty($pp_a)) {
            $validationStatus = false;
            $output = [
                'status' => 'error',
                'message' => 'Please select party in person/advocate.',
                'fieldId' => 'pp_a'
            ];
        }
        if (empty($attend)) {
            $validationStatus = false;
            $output = [
                'status' => 'error',
                'message' => 'Please select present/absent.',
                'fieldId' => 'attend'
            ];
        }
        if ($validationStatus) {
            $filingAdminModel = new FilingAdminModel();
            $updateData = [
                'attend' => $attend,
                'pp_a' => $pp_a,
                'updated_on' => date('Y-m-d H:i:s'),
                'update_ip' => $this->request->getIPAddress()
            ];
            $updateRes = $filingAdminModel->updateTableData('efil.tbl_users', 'id', $userId, $updateData);
            if ($updateRes) {
                $deletedby = !empty($session->get('login')['id']) ? $session->get('login')['id'] : NULL;
                $roleArr = [
                    'deletedby' => $deletedby,
                    'is_active' => 0,
                    'deletedat' => date('Y-m-d H:i:s'),
                    'deleted_ip' => $this->request->getIPAddress()
                ];
                $updateRole = $filingAdminModel->updateTableData('efil.tbl_filing_admin_assigned_file', 'user_id', $userId, $roleArr);
                if ($updateRole && !empty($filing_type)) {
                    $fileTypeInsert = [];
                    foreach ($filing_type as $v) {
                        $tmp = [
                            'user_id' => $userId,
                            'createdby' => !empty($session->get('login')['id']) ? $session->get('login')['id'] : NULL,
                            'createdat' => date('Y-m-d H:i:s'),
                            'file_type_id' => $v,
                            'created_ip' => $this->request->getIPAddress()
                        ];
                        $fileTypeInsert[] = $tmp;
                    }
                    $res = $filingAdminModel->insertBatchData('efil.tbl_filing_admin_assigned_file', $fileTypeInsert);
                    if ($res) {
                        $output = [
                            'status' => 'success',
                            'message' => 'User role has been updated successfully!'
                        ];
                    } else {
                        $output = [
                            'status' => 'error',
                            'message' => 'Something went wrong, Please try again later!'
                        ];
                    }
                } else {
                    $tmp = [
                        'user_id' => $userId,
                        'createdby' => !empty($session->get('login')['id']) ? $session->get('login')['id'] : NULL,
                        'createdat' => date('Y-m-d H:i:s'),
                        'file_type_id' => 0,
                        'created_ip' => $this->request->getIPAddress()
                    ];
                    $result = $filingAdminModel->insertData('efil.tbl_filing_admin_assigned_file', $tmp);
                    if ($result) {
                        $output = [
                            'status' => 'success',
                            'message' => 'User role has been updated successfully!'
                        ];
                    } else {
                        $output = [
                            'status' => 'error',
                            'message' => 'Something went wrong, Please try again later!'
                        ];
                    }
                }
            }
        }
        return $this->response->setJSON($output);
    }

    public function userListing()
    {
        if (empty(getSessionData('login'))) {
            return redirect()->to(base_url('/'));
        } else {
            is_user_status();
        }
        $allowed_users_array = array(USER_EFILING_ADMIN);
        if (!in_array($this->session->get('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return response()->redirect(base_url("redirect_on_login"));
            exit(0);
        }
        $data = array();
        $params = array();
        $params['user_type'] = USER_ADMIN;
        $params['not_in_user_id'] = unserialize(USER_NOT_IN_LIST); //array(2660,2659,2658,2657,2656,2647);
        $response = $this->FilingAdminModel->getAssignedUser($params);
        $data['users'] = $response;
        $params = array();
        $params['table_name'] = "efil.m_tbl_efiling_type";
        $params['is_active'] = true;
        $params['id'] = array(1, 2, 4, 12);
        $filingType = $this->FilingAdminModel->getAllRecordFromTable($params);
        $data['filingType'] = $filingType;
        $users_array = array(USER_ADMIN, USER_ADMIN_READ_ONLY, USER_EFILING_ADMIN);
        $users_read_array = array(USER_ADMIN_READ_ONLY, USER_EFILING_ADMIN);
        if (in_array($this->session->get('login')['ref_m_usertype_id'], $users_array)) {
            if (in_array($this->session->get('login')['ref_m_usertype_id'], $users_read_array)) {
                $AllUserCount = 1;
                $AllUserCountData = array_merge($this->session->get('login'), array('AllUserCount' => $AllUserCount));
                $this->session->set(array('login' => $AllUserCountData));
            } else {
                $AllUserCount = 0;
                $AllUserCountData = array_merge($this->session->get('login'), array('AllUserCount' => $AllUserCount));
                $this->session->set(array('login' => $AllUserCountData));
            }
        }
        return $this->render('filingAdmin.userList', $data);
    }

    public function userFileTransferForm()
    {
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $data = array();
        if (empty(getSessionData('login.ref_m_usertype_id'))) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $allowed_users_array = array(USER_EFILING_ADMIN);
        if (!in_array(getSessionData('login.ref_m_usertype_id'), $allowed_users_array)) {
            return redirect()->to(base_url('filingAdmin'));
            exit(0);
        }
        $params = array();
        $params['user_type'] = USER_ADMIN;
        $params['not_in_user_id'] = unserialize(USER_NOT_IN_LIST); //array(2660,2659,2658,2657,2656,2647);
        $response = $this->FilingAdminModel->getAssignedUser($params);
        $userArr = array();
        if (isset($response) && !empty($response)) {
            foreach ($response as $k => $v) {
                $tmp = array();
                $tmp['user_id'] = !empty($v->user_id) ? $v->user_id : NULL;
                $tmp['first_name'] = !empty($v->first_name) ? $v->first_name : NULL;
                $userArr[] = $tmp;
            }
        }
        $data['users'] = $response;
        return $this->render('filingAdmin.userFileTransfer', $data);
    }

    public function getEmpCaseData()
    {
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        if (empty($this->session->get('login')['ref_m_usertype_id'])) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $allowed_users_array = array(USER_EFILING_ADMIN);
        if (!in_array($this->session->get('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('filingAdmin'));
            exit(0);
        }
        $output = array();
        if (!empty($_POST['userId'])) {
            $userId = $this->request->getPost('userId');
            $params = array();
            $params['allocated_to'] = $userId;
            if (!empty($_POST['userId'])) {
                $params['type'] = 1;
            } else {
                $params['type'] = 2;
            }
            $params['stage_id'] = array(Initial_Approaval_Pending_Stage);
            $response = $this->FilingAdminModel->getAssignedCaseByUserId($params);
            if (isset($response) && !empty($response)) {
                $tmpArr = array();
                $tmpArr['status'] = 'success';
                $tmpArr['caseData'] = $response;
                $arr = array();
                $arr['user_type'] = USER_ADMIN;
                $arr['not_in_user_id'] = unserialize(USER_NOT_IN_LIST); //array(2660,2659,2658,2657,2656,2647);
                $arr['userId'] = $userId;
                $arr['type'] = 2;
                $transferUser = $this->FilingAdminModel->getAssignedUserByUserId($arr);
                $option = ' <option value="">Select</option>';
                if (isset($transferUser) && !empty($transferUser)) {
                    foreach ($transferUser as $k => $v) {
                        $option .= '<option value="' . $v->id . '">' . $v->first_name . '</option>';
                        // $option .='<option value="">'.$v['first_name'].'</option>';
                    }
                }
                $tmpArr['trasferUser'] = $option;
                $output = json_encode($tmpArr);
            } else {
                $output = json_encode(array('status' => 'error', 'message' => 'No Records Found!'));
            }
        } else {
            $output = json_encode(array('status' => 'error', 'message' => 'No Records Found!'));
        }
        echo $output;
        exit(0);
    }

    public function fileTransferToAnOtherUser()
    {
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        if (empty($this->session->get('login')['ref_m_usertype_id'])) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $allowed_users_array = array(USER_EFILING_ADMIN);
        if (!in_array($this->session->get('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('filingAdmin'));
            exit(0);
        }
        $output = array();
        $postData = json_decode(file_get_contents('php://input'), true);
        if (isset($postData['userFile']) && !empty($postData['userFile']) && !empty($postData['typeUser']['file_transfer_to_user']) && isset($postData['typeUser']['file_transfer_to_user'])) {
            $registrationArr = $postData['userFile'];
            $file_transfer_from = (int)$postData['typeUser']['file_transfer_from'];
            $file_transfer_to_user = (int)$postData['typeUser']['file_transfer_to_user'];
            if (isset($file_transfer_from) && !empty($file_transfer_from)) {
                $params = array();
                $params['userId'] = $file_transfer_from;
                $fromUserRoleData = $this->FilingAdminModel->getUserRoleByUserId($params);
                $fromRole = !empty($fromUserRoleData[0]->file_type_id) ? explode(',', $fromUserRoleData[0]->file_type_id) : NULL;
                $fromUserId = !empty($fromUserRoleData[0]->user_id) ? $fromUserRoleData[0]->user_id : NULL;
                $arr = array();
                $arr['userId'] = $file_transfer_to_user;
                $toUserRoleData = $this->FilingAdminModel->getUserRoleByUserId($arr);
                $toRole = !empty($toUserRoleData[0]->file_type_id) ? explode(',', $toUserRoleData[0]->file_type_id) : NULL;
                $toUserId = !empty($toUserRoleData[0]->user_id) ? $toUserRoleData[0]->user_id : NULL;
                $regArr = array();
                $regArr['registration_id'] = $registrationArr;
                $fileType = $this->FilingAdminModel->getFileTypeByRegistrationId($regArr);
                $ctn = 0;
                $efilingDetails = '';
                if (isset($fileType) && !empty($fileType)) {
                    foreach ($fileType as $k => $v) {
                        $efiling_no = !empty($v->efiling_no) ? $v->efiling_no : NULL;
                        $registration_id = !empty($v->registration_id) ? (int)$v->registration_id : NULL;
                        if (in_array($v->ref_m_efiled_type_id, $fromRole) && (in_array($v->ref_m_efiled_type_id, $toRole))) {
                            $updateArr = array();
                            $updateArr['allocated_to'] = $toUserId;
                            $updateArr['updated_by'] = $_SESSION['login']['id'];
                            $updateArr['updated_on'] = date('Y-m-d H:i:s');
                            $updateArr['updated_by_ip'] = getClientIP();
                            $fileallocationArr = array();
                            $fileallocationArr['table_name'] = "efil.tbl_efiling_nums";
                            $fileallocationArr['whereFieldName'] = "registration_id";
                            $fileallocationArr['whereFieldValue'] = $registration_id;
                            $fileallocationArr['updateArr'] = $updateArr;
                            $res = $this->FilingAdminModel->updateTableData($fileallocationArr['table_name'], $fileallocationArr['whereFieldName'], $fileallocationArr['whereFieldValue'], $fileallocationArr['updateArr']);
                            if (isset($res) && !empty($res)) {
                                $upEfilingAllocation = array();
                                $upEfilingAllocation['admin_id'] = $toUserId;
                                $upEfilingAllocation['reason_to_allocate'] = "A";
                                $upEfilingAllocation['updated_by'] = $_SESSION['login']['id'];
                                $upEfilingAllocation['updated_on'] = date('Y-m-d H:i:s');
                                $upEfilingAllocation['update_ip'] = getClientIP();
                                $fileArr = array();
                                $fileArr['table_name'] = "efil.tbl_efiling_allocation";
                                $fileArr['whereFieldName'] = "registration_id";
                                $fileArr['whereFieldValue'] = $registration_id;
                                $fileArr['updateArr'] = $upEfilingAllocation;
                                $result = $this->FilingAdminModel->updateTableData($fileArr['table_name'], $fileArr['whereFieldName'], $fileArr['whereFieldValue'], $fileArr['updateArr']);
                                if (isset($result) && !empty($result)) {
                                    $output = json_encode(array('status' => 'success', 'message' => 'File allocation successful.', 'failedTotal' => $ctn, 'efiling_no' => $efilingDetails));
                                } else {
                                    $output = json_encode(array('status' => 'error', 'message' => 'Something went wrong. Please try again later.'));
                                }
                            } else {
                                $output = json_encode(array('status' => 'error', 'message' => 'Something went wrong. Please try again later.'));
                            }
                        } else {
                            $ctn++;
                            $efilingDetails .= $efiling_no . ',';
                            $output = json_encode(array('status' => 'error', 'message' => 'User is not authorized.', 'failedTotal' => $ctn, 'efiling_no' => $efilingDetails));
                        }
                    }
                } else {
                    $output = json_encode(array('status' => 'error', 'message' => 'Please select file.'));
                }
            } else {
                $arr = array();
                $arr['userId'] = $file_transfer_to_user;
                $toUserRoleData = $this->FilingAdminModel->getUserRoleByUserId($arr);
                $toRole = !empty($toUserRoleData[0]->file_type_id) ? explode(',', $toUserRoleData[0]->file_type_id) : NULL;
                $toUserId = !empty($toUserRoleData[0]->user_id) ? $toUserRoleData[0]->user_id : NULL;
                $regArr = array();
                $regArr['registration_id'] = $registrationArr;
                $fileType = $this->FilingAdminModel->getFileTypeByRegistrationId($regArr);
                $ctn = 0;
                $efilingDetails = '';
                if (isset($fileType) && !empty($fileType)) {
                    foreach ($fileType as $k => $v) {
                        $efiling_no = !empty($v->efiling_no) ? $v->efiling_no : NULL;
                        $registration_id = !empty($v->registration_id) ? (int)$v->registration_id : NULL;
                        if ((in_array($v->ref_m_efiled_type_id, $toRole))) {
                            $updateArr = array();
                            $updateArr['allocated_to'] = $toUserId;
                            $updateArr['updated_by'] = $_SESSION['login']['id'];
                            $updateArr['updated_on'] = date('Y-m-d H:i:s');
                            $updateArr['updated_by_ip'] = getClientIP();
                            $fileallocationArr = array();
                            $fileallocationArr['table_name'] = "efil.tbl_efiling_nums";
                            $fileallocationArr['whereFieldName'] = "registration_id";
                            $fileallocationArr['whereFieldValue'] = $registration_id;
                            $fileallocationArr['updateArr'] = $updateArr;
                            $res = $this->FilingAdminModel->updateTableData($fileallocationArr['table_name'], $fileallocationArr['whereFieldName'], $fileallocationArr['whereFieldValue'], $fileallocationArr['updateArr']);
                            if (isset($res) && !empty($res)) {
                                $upEfilingAllocation = array();
                                $upEfilingAllocation['admin_id'] = $toUserId;
                                $upEfilingAllocation['reason_to_allocate'] = "A";
                                $upEfilingAllocation['updated_by'] = $_SESSION['login']['id'];
                                $upEfilingAllocation['updated_on'] = date('Y-m-d H:i:s');
                                $upEfilingAllocation['update_ip'] = getClientIP();
                                $fileArr = array();
                                $fileArr['table_name'] = "efil.tbl_efiling_allocation";
                                $fileArr['whereFieldName'] = "registration_id";
                                $fileArr['whereFieldValue'] = $registration_id;
                                $fileArr['updateArr'] = $upEfilingAllocation;
                                $result = $this->FilingAdminModel->updateTableData($fileArr['table_name'], $fileArr['whereFieldName'], $fileArr['whereFieldValue'], $fileArr['updateArr']);
                                if (isset($result) && !empty($result)) {
                                    $output = json_encode(array('status' => 'success', 'message' => 'File allocation successful.', 'failedTotal' => $ctn, 'efiling_no' => $efilingDetails));
                                } else {
                                    $output = json_encode(array('status' => 'error', 'message' => 'Something went wrong. Please try again later.'));
                                }
                            } else {
                                $output = json_encode(array('status' => 'error', 'message' => 'Something went wrong. Please try again later.'));
                            }
                        } else {
                            $ctn++;
                            $efilingDetails .= $efiling_no . ',';
                            $output = json_encode(array('status' => 'error', 'message' => 'User is not authorized.', 'failedTotal' => $ctn, 'efiling_no' => $efilingDetails));
                        }
                    }
                } else {
                    $output = json_encode(array('status' => 'error', 'message' => 'Please select file.'));
                }
            }
        } else {
            $output = json_encode(array('status' => 'error', 'message' => 'Something went wrong. Please try again later.'));
        }
        echo $output;
        exit(0);
    }
}
