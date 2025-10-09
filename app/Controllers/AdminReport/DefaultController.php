<?php

namespace App\Controllers\AdminReport;

use App\Controllers\BaseController;
use App\Models\AdminReport\AdminReportModel;
use App\Models\AdminDashboard\AdminDashboardModel;

class DefaultController extends BaseController
{
    protected $AdminReportModel;
    protected $AdminDashboardModel;
    protected $session;

    public function __construct()
    {
        parent::__construct();
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $this->AdminReportModel = new AdminReportModel();
        $this->AdminDashboardModel = new AdminDashboardModel();
        $this->session = \Config\Services::session();
    }
    public function index()
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
    }

    public function reportForm()
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if (empty(getSessionData('login')['ref_m_usertype_id'])) {
            return response()->redirect(base_url('/')); 
        }
        $allowed_users_array = array(USER_EFILING_ADMIN, USER_ADMIN);
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('filingAdmin'));
            exit(0);
        }
        return $this->render('adminReport.reports');
    }

    public function getReportData()
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if (empty(getSessionData('login')['ref_m_usertype_id'])) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $allowed_users_array = array(USER_EFILING_ADMIN, USER_ADMIN);
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('filingAdmin'));
            exit(0);
        }
        $data = array();
        $postData = json_decode(file_get_contents('php://input'), true);
        $from_date = NULL;
        $to_date = NULL;
        $output = array();
        $validatinError = true;
        if (isset($postData['from_date']) && !empty($postData['from_date'])) {
            $from_date = trim($postData['from_date']);
        } else {
            $output['status'] = 'error';
            $output['id'] = 'from_date';
            $output['msg'] = 'Please select from date.';
            $validatinError = false;
        }
        if (isset($postData['to_date']) && !empty($postData['to_date'])) {
            $to_date = trim($postData['to_date']);
        } else {
            $output['status'] = 'error';
            $output['id'] = 'to_date';
            $output['msg'] = 'Please select to date.';
            $validatinError = false;
        }
        $userNameArr = array();
        if (isset($validatinError) && !empty($validatinError)) {
            $ref_m_usertype_id = !empty(getSessionData('login')['ref_m_usertype_id']) ? (int)getSessionData('login')['ref_m_usertype_id'] : NULL;
            if (isset($ref_m_usertype_id) && !empty($ref_m_usertype_id)) {
                $allocatedUserDetails = $this->AdminReportModel->getAllocatedUserDetails();
                if (isset($allocatedUserDetails) && !empty($allocatedUserDetails)) {
                    foreach ($allocatedUserDetails as $k => $v) {
                        $userNameArr[$v['allocated_to']] = strtoupper($v['user_name']);
                    }
                    $userNameArr['all'] = 'ALL';
                }
                $allocatedArr = array();
                if (isset($allocatedUserDetails) && !empty($allocatedUserDetails)) {
                    $allocatedArr = array_column($allocatedUserDetails, 'allocated_to');
                }
                $allocatedArr['all'] = 'all';
                $params = array();
                $params['from_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $from_date)));
                $params['to_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $to_date)));
                $params['user_type'] = $ref_m_usertype_id;
                $file_type_id = unserialize(FILE_TYPE_ID);
                $params['file_type_id'] = $file_type_id;
                switch ($ref_m_usertype_id) {
                    case USER_EFILING_ADMIN:
                        if (isset($allocatedArr) && !empty($allocatedArr)) {
                            foreach ($allocatedArr as $k => $v) {
                                //file allocated
                                if ($k === 'all') {
                                    $params['allocated_to'] = null;
                                } else {
                                    $params['allocated_to'] = (int)$v;
                                }
                                $type = "file_allocated";
                                $params['type'] = $type;
                                $params['stage_id'] = New_Filing_Stage;
                                $file_allocated =   $this->AdminReportModel->getCountData($params);
                                $data[$v][$v]['file_allocated'] = !empty($file_allocated[0]) ? $file_allocated[0] : NULL;
                                unset($params['type']);
                                unset($params["stage_id"]);
                                $type = "file_diaries";
                                $params['type'] = $type;
                                $params['stage_id'] = I_B_Approval_Pending_Admin_Stage;
                                $file_diaries =   $this->AdminReportModel->getCountData($params);
                                $data[$v][$v]['file_diaries'] = !empty($file_diaries[0]) ? $file_diaries[0] : NULL;
                                unset($params["type"]);
                                unset($params["stage_id"]);

                                $type = "file_pending_diary_or_document";
                                $params['type'] = $type;
                                $params['stage_id'] = New_Filing_Stage;
                                $file_allocated =   $this->AdminReportModel->getCountData($params);
                                $data[$v][$v]['file_pending_diary_or_document'] = !empty($file_allocated[0]) ? $file_allocated[0] : NULL;
                                unset($params['type']);
                                unset($params["stage_id"]);
                                unset($params['allocated_to']);
                            }
                        }
                        break;
                    case USER_ADMIN:
                        $login_user_id = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;
                        //file allocated
                        $type = "file_allocated";
                        $params['type'] = $type;
                        $params['stage_id'] = New_Filing_Stage;
                        $params['login_user_id'] = $login_user_id;
                        $file_allocated =   $this->AdminReportModel->getCountData($params);
                        $data[$login_user_id][$login_user_id]['file_allocated'] = !empty($file_allocated[0]) ? $file_allocated[0] : NULL;
                        unset($params['type']);
                        unset($params["stage_id"]);
                        $type = "file_diaries";
                        $params['type'] = $type;
                        $params['stage_id'] = I_B_Approval_Pending_Admin_Stage;
                        $file_diaries =   $this->AdminReportModel->getCountData($params);
                        $data[$login_user_id][$login_user_id]['file_diaries'] = !empty($file_diaries[0]) ? $file_diaries[0] : NULL;
                        unset($params["type"]);
                        unset($params["stage_id"]);
                        $type = "file_pending_diary_or_document";
                        $params['type'] = $type;
                        $params['stage_id'] = New_Filing_Stage;
                        $params['login_user_id'] = $login_user_id;
                        $file_allocated =   $this->AdminReportModel->getCountData($params);
                        $data[$login_user_id][$login_user_id]['file_pending_diary_or_document'] = !empty($file_allocated[0]) ? $file_allocated[0] : NULL;
                        unset($params['type']);
                        unset($params["stage_id"]);
                        break;
                    default:
                }
                $output['status'] = 'success';
                $output['id'] = 'result';
                $output['msg'] = '<div style="background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 10px; border-radius: 5px;">
                    Record has been fetched successfully
                 </div>';
            } else {
                $output['status'] = 'success';
                $output['id'] = 'result';
                $output['msg'] = 'Something went wrong,Please try again later!';
            }
            $file_type_name = unserialize(FILE_TYPE_NAME);
            $tableData = '';
            foreach ($data as $key => $arr) {
                $ctn = 0;
                $tmpData1 = '';
                $tmpData2 = '';
                $tmpData3 = '';
                $tmpData4 = '';
                $tmpData5 = '';
                $tableData .= '<tr>';
                $tableData .= '<td data-key="Date">' . $userNameArr[$key] . '</td>';
                foreach ($arr[$key]['file_allocated'] as $k => $v) {
                    $fileTypeName = '';
                    $pipe = ' ';
                    if ($ctn <= 3) {
                        $pipe = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
                    }
                    if (array_key_exists($k, $file_type_name)) {
                        $fileTypeName = $file_type_name[$k];
                    }
                    $total = !empty($v) ? $v : 0;
                    $user_id = (is_numeric($key) == true) ? $key : 0;
                    $tmpData1 .= '<a class="pointer" href="' . base_url('adminReport/DefaultController/getFilingStageTypeData/?type=file_allocated&fileType=' . $fileTypeName . '&from_date=' . $from_date . '&to_date=' . $to_date . '&allocated_to=' . $user_id . ' ') . '">' . $total . $pipe . ' </a>';
                    $ctn++;
                }
                $tmpData1 = rtrim($tmpData1, ' | ');
                $tableData .= '<td data-key="Work Allocated">' . $tmpData1 . '</td>';
                $ctn = 0;
                if (isset($arr[$key]['file_approved'])) {
                    foreach ($arr[$key]['file_approved'] as $k => $v) {
                        $fileTypeName = '';
                        $pipe = ' ';
                        if ($ctn <= 3) {
                            $pipe = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
                        }
                        if (array_key_exists($k, $file_type_name)) {
                            $fileTypeName = $file_type_name[$k];
                        }
                        $total = !empty($v) ? $v : 0;
                        $user_id = (is_numeric($key) == true) ? $key : 0;
                        $tmpData2 .= '<a class="pointer" href="' . base_url('adminReport/DefaultController/getFilingStageTypeData/?type=file_approved&fileType=' . $fileTypeName . '&from_date=' . $from_date . '&to_date=' . $to_date .  '&allocated_to=' . $user_id . ' ') . '">' . $total . $pipe . ' </a>';
                        $ctn++;
                    }
                }
                $tableData .= '';
                $ctn = 0;
                if (isset($arr[$key]['file_rejected'])) {
                    foreach ($arr[$key]['file_rejected'] as $k => $v) {
                        $fileTypeName = '';
                        $pipe = ' ';
                        if ($ctn <= 3) {
                            $pipe = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
                        }
                        if (array_key_exists($k, $file_type_name)) {
                            $fileTypeName = $file_type_name[$k];
                        }
                        $total = !empty($v) ? $v : 0;
                        $user_id = (is_numeric($key) == true) ? $key : 0;
                        $tmpData3 .= '<a class="pointer" href="' . base_url('adminReport/DefaultController/getFilingStageTypeData/?type=file_rejected&fileType=' . $fileTypeName . '&from_date=' . $from_date . '&to_date=' . $to_date . '&allocated_to=' . $user_id . ' ') . '">' . $total . $pipe . ' </a>';
                        $ctn++;
                    }
                }
                $tableData .= '';
                $ctn = 0;
                foreach ($arr[$key]['file_diaries'] as $k => $v) {
                    $fileTypeName = '';
                    $pipe = ' ';
                    if ($ctn <= 3) {
                        $pipe = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
                    }
                    if (array_key_exists($k, $file_type_name)) {
                        $fileTypeName = $file_type_name[$k];
                    }
                    $total = !empty($v) ? $v : 0;
                    $user_id = (is_numeric($key) == true) ? $key : 0;
                    $tmpData4 .= '<a class="pointer" href="' . base_url('adminReport/DefaultController/getFilingStageTypeData/?type=file_diaries&fileType=' . $fileTypeName . '&from_date=' . $from_date . '&to_date=' . $to_date . '&allocated_to=' . $user_id . ' ') . '">' . $total . '  ' . $pipe . ' </a>';
                    $ctn++;
                }
                $tableData .= '<td data-key="No. Of Diary/Document No. Generated">' . $tmpData4 . '</td>';
                $tableData .= '';
                $ctn = 0;
                foreach ($arr[$key]['file_pending_diary_or_document'] as $k => $v) {
                    $fileTypeName = '';
                    $pipe = ' ';
                    if ($ctn <= 3) {
                        $pipe = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
                    }
                    if (array_key_exists($k, $file_type_name)) {
                        $fileTypeName = $file_type_name[$k];
                    }
                    $total = !empty($v) ? $v : 0;
                    $user_id = (is_numeric($key) == true) ? $key : 0;
                    $tmpData5 .= '<a class="pointer" href="' . base_url('adminReport/DefaultController/getFilingStageTypeData/?type=file_pending_diary_or_document&fileType=' . $fileTypeName . '&from_date=' . $from_date . '&to_date=' . $to_date . '&allocated_to=' . $user_id . ' ') . '">' . $total . $pipe . ' </a>';
                    $ctn++;
                }
                $tmpData5 = rtrim($tmpData5, ' | ');
                $tableData .= '<td data-key="Pending for Diary/Document No.">' . $tmpData5 . '</td>';
                $ctn = 0;
                $tableData .= '</tr>';
            }
            $table = '';
            $table .= '<table id="datatable-responsive" class="table table-striped custom-table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Date<hr>' . date('d/m/Y', strtotime($params['from_date'])) . ' - ' . date('d/m/Y', strtotime($params['to_date'])) . '</th>
                        <th>Work Allocated <br><hr>CASE | MISC | IA | CAVEAT | ALL</th>
                        <th>No. Of Diary/Document No. Generated <br><hr>CASE | MISC | IA | CAVEAT | ALL</th>
                        <th>Pending for Diary/Document No. <br><hr>CASE | MISC | IA | CAVEAT | ALL</th>
                    </tr>
                    <tbody>' . $tableData . '</tbody>
                </thead>
            </table>
            <script>
                $(document).ready(function() {
                    $("#loader-wrapper").show();
                    $("#datatable-responsive").DataTable({
                        "ordering": false,
                    });
                });
            </script>';
        }
        $output['table'] = $table;
        echo json_encode($output);
        exit(0);
    }
    public function getFilingStageTypeData()
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if (empty(getSessionData('login')['ref_m_usertype_id'])) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $allowed_users_array = array(USER_EFILING_ADMIN, USER_ADMIN);
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('filingAdmin'));
            exit(0);
        }
        $data = array();
        $params = array();
        $ref_m_usertype_id = !empty(getSessionData('login')['ref_m_usertype_id']) ? (int)getSessionData('login')['ref_m_usertype_id'] : NULL;
        $type = !empty($_GET['type']) ? $_GET['type'] : NULL;
        $fileType = !empty($_GET['fileType']) ? $_GET['fileType'] : NULL;
        $from_date = !empty($_GET['from_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $_GET['from_date']))) : NULL;
        $to_date = !empty($_GET['to_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $_GET['to_date']))) : NULL;
        $params['from_date'] = $from_date;
        $params['to_date'] = $to_date;
        $params['user_type'] = $ref_m_usertype_id;
        $params['type'] = $type;
        if (isset($type) && !empty($type) && isset($fileType) && !empty($fileType)) {
            if (isset($ref_m_usertype_id) && !empty($ref_m_usertype_id)) {
                switch ($ref_m_usertype_id) {
                    case USER_EFILING_ADMIN:
                        $params['allocated_to'] = !empty($_GET['allocated_to']) ? (int)$_GET['allocated_to'] : 0;
                        $allocatedUserDetails = $this->AdminReportModel->getAllocatedUserDetails();
                        $userNameArr = array();
                        if (isset($allocatedUserDetails) && !empty($allocatedUserDetails)) {
                            foreach ($allocatedUserDetails as $k => $v) {
                                $userNameArr[$v['allocated_to']] = strtoupper($v['user_name']);
                            }
                            $userNameArr['0'] = 'FILING ADMIN';
                        }
                        switch ($type) {
                            case 'file_allocated':
                                switch ($fileType) {
                                    case 'N':
                                        $params['file_type_id'] = array(E_FILING_TYPE_NEW_CASE);
                                        $params['stage_id'] = New_Filing_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Allocated';
                                        $tmpArr['file_type'] = 'New Case';
                                        $tmpArr['dateLevel'] = 'Allocated On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'M':
                                        $params['file_type_id'] = array(E_FILING_TYPE_MISC_DOCS);
                                        $params['stage_id'] = New_Filing_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Allocated';
                                        $tmpArr['file_type'] = 'Miscellaneous Doc.';
                                        $tmpArr['dateLevel'] = 'Allocated On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'I':
                                        $params['file_type_id'] = array(E_FILING_TYPE_IA);
                                        $params['stage_id'] = New_Filing_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Allocated';
                                        $tmpArr['file_type'] = 'IA';
                                        $tmpArr['dateLevel'] = 'Allocated On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'C':
                                        $params['file_type_id'] = array(E_FILING_TYPE_CAVEAT);
                                        $params['stage_id'] = New_Filing_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Allocated';
                                        $tmpArr['file_type'] = 'Caveat';
                                        $tmpArr['dateLevel'] = 'Allocated On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'A':
                                        $params['file_type_id'] =  unserialize(FILE_TYPE_ID);;
                                        $params['stage_id'] = New_Filing_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Allocated';
                                        $tmpArr['file_type'] = 'All';
                                        $tmpArr['dateLevel'] = 'Action Taken On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    default:
                                }
                                break;
                            case 'file_approved':
                                switch ($fileType) {
                                    case 'N':
                                        $params['file_type_id'] = array(E_FILING_TYPE_NEW_CASE);
                                        $params['stage_id'] = Transfer_to_IB_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Approved';
                                        $tmpArr['file_type'] = 'New Case';
                                        $tmpArr['dateLevel'] = 'Approved On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'M':
                                        $params['file_type_id'] = array(E_FILING_TYPE_MISC_DOCS);
                                        $params['stage_id'] = Transfer_to_IB_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Approved';
                                        $tmpArr['file_type'] = 'Miscellaneous Doc.';
                                        $tmpArr['dateLevel'] = 'Approved On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'I':
                                        $params['file_type_id'] = array(E_FILING_TYPE_IA);
                                        $params['stage_id'] = Transfer_to_IB_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Approved';
                                        $tmpArr['file_type'] = 'IA';
                                        $tmpArr['dateLevel'] = 'Approved On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'C':
                                        $params['file_type_id'] = array(E_FILING_TYPE_CAVEAT);
                                        $params['stage_id'] = Transfer_to_IB_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Approved';
                                        $tmpArr['file_type'] = 'Caveat';
                                        $tmpArr['dateLevel'] = 'Approved On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'A':
                                        $params['file_type_id'] =  unserialize(FILE_TYPE_ID);;
                                        $params['stage_id'] = Transfer_to_IB_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Approved';
                                        $tmpArr['file_type'] = 'All';
                                        $tmpArr['dateLevel'] = 'Action Taken On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    default:
                                }
                                break;
                            case 'file_rejected':
                                switch ($fileType) {
                                    case 'N':
                                        $params['file_type_id'] = array(E_FILING_TYPE_NEW_CASE);
                                        $params['stage_id'] = Initial_Defected_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Rejected';
                                        $tmpArr['file_type'] = 'New Case';
                                        $tmpArr['dateLevel'] = 'Rejected On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'M':
                                        $params['file_type_id'] = array(E_FILING_TYPE_MISC_DOCS);
                                        $params['stage_id'] = Initial_Defected_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Rejected';
                                        $tmpArr['file_type'] = 'Miscellaneous Doc.';
                                        $tmpArr['dateLevel'] = 'Rejected On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'I':
                                        $params['file_type_id'] = array(E_FILING_TYPE_IA);
                                        $params['stage_id'] = Initial_Defected_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Rejected';
                                        $tmpArr['dateLevel'] = 'Rejected On';
                                        $tmpArr['file_type'] = 'IA';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'C':
                                        $params['file_type_id'] = array(E_FILING_TYPE_CAVEAT);
                                        $params['stage_id'] = Initial_Defected_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Rejected';
                                        $tmpArr['file_type'] = 'Caveat';
                                        $tmpArr['dateLevel'] = 'Rejected On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'A':
                                        $params['file_type_id'] =  unserialize(FILE_TYPE_ID);;
                                        $params['stage_id'] = Initial_Defected_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Rejected';
                                        $tmpArr['file_type'] = 'All';
                                        $tmpArr['dateLevel'] = 'Action Taken On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    default:
                                }
                                break;
                            case 'file_diaries':
                                switch ($fileType) {
                                    case 'N':
                                        $params['file_type_id'] = array(E_FILING_TYPE_NEW_CASE);
                                        $params['stage_id'] = I_B_Approval_Pending_Admin_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Diaries';
                                        $tmpArr['file_type'] = 'New Case';
                                        $tmpArr['dateLevel'] = 'Diaries On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'M':
                                        $params['file_type_id'] = array(E_FILING_TYPE_MISC_DOCS);
                                        $params['stage_id'] = I_B_Approval_Pending_Admin_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Diaries';
                                        $tmpArr['file_type'] = 'Miscellaneous Doc.';
                                        $tmpArr['dateLevel'] = 'Diaries On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'I':
                                        $params['file_type_id'] = array(E_FILING_TYPE_IA);
                                        $params['stage_id'] = I_B_Approval_Pending_Admin_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Diaries';
                                        $tmpArr['file_type'] = 'IA';
                                        $tmpArr['dateLevel'] = 'Diaries On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'C':
                                        $params['file_type_id'] = array(E_FILING_TYPE_CAVEAT);
                                        $params['stage_id'] = I_B_Approval_Pending_Admin_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Diaries';
                                        $tmpArr['file_type'] = 'Caveat';
                                        $tmpArr['dateLevel'] = 'Diaries On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'A':
                                        $params['file_type_id'] =  unserialize(FILE_TYPE_ID);;
                                        $params['stage_id'] = I_B_Approval_Pending_Admin_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Diaries';
                                        $tmpArr['file_type'] = 'All';
                                        $tmpArr['dateLevel'] = 'Action Taken On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    default:
                                }
                                break;
                            case 'file_pending_diary_or_document':
                                switch ($fileType) {
                                    case 'N':
                                        $params['file_type_id'] = array(E_FILING_TYPE_NEW_CASE);
                                        $params['stage_id'] = New_Filing_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Allocated';
                                        $tmpArr['file_type'] = 'New Case';
                                        $tmpArr['dateLevel'] = 'Allocated On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'M':
                                        $params['file_type_id'] = array(E_FILING_TYPE_MISC_DOCS);
                                        $params['stage_id'] = New_Filing_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Allocated';
                                        $tmpArr['file_type'] = 'Miscellaneous Doc.';
                                        $tmpArr['dateLevel'] = 'Allocated On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'I':
                                        $params['file_type_id'] = array(E_FILING_TYPE_IA);
                                        $params['stage_id'] = New_Filing_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Allocated';
                                        $tmpArr['file_type'] = 'IA';
                                        $tmpArr['dateLevel'] = 'Allocated On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'C':
                                        $params['file_type_id'] = array(E_FILING_TYPE_CAVEAT);
                                        $params['stage_id'] = New_Filing_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Allocated';
                                        $tmpArr['file_type'] = 'Caveat';
                                        $tmpArr['dateLevel'] = 'Allocated On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'A':
                                        $params['file_type_id'] =  unserialize(FILE_TYPE_ID);;
                                        $params['stage_id'] = New_Filing_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] =  $userNameArr[$params['allocated_to']];
                                        $tmpArr['type'] = 'File Allocated';
                                        $tmpArr['file_type'] = 'All';
                                        $tmpArr['dateLevel'] = 'Action Taken On';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    default:
                                }
                                break;
                            default:
                        }
                        break;
                    case USER_ADMIN:
                        $login_user_id = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;
                        $params['login_user_id'] = $login_user_id;
                        switch ($type) {
                            case 'file_allocated':
                                switch ($fileType) {
                                    case 'N':
                                        $params['file_type_id'] = array(E_FILING_TYPE_NEW_CASE);
                                        $params['stage_id'] = New_Filing_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Allocated';
                                        $tmpArr['file_type'] = 'New Case';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'M':
                                        $params['file_type_id'] = array(E_FILING_TYPE_MISC_DOCS);
                                        $params['stage_id'] = New_Filing_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Allocated';
                                        $tmpArr['file_type'] = 'Miscellaneous Doc.';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'I':
                                        $params['file_type_id'] = array(E_FILING_TYPE_IA);
                                        $params['stage_id'] = New_Filing_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Allocated';
                                        $tmpArr['file_type'] = 'IA';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'C':
                                        $params['file_type_id'] = array(E_FILING_TYPE_CAVEAT);
                                        $params['stage_id'] = New_Filing_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Allocated';
                                        $tmpArr['file_type'] = 'Caveat';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'A':
                                        $params['file_type_id'] =  unserialize(FILE_TYPE_ID);;
                                        $params['stage_id'] = New_Filing_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Allocated';
                                        $tmpArr['file_type'] = 'All';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    default:
                                }
                                break;
                            case 'file_approved':
                                switch ($fileType) {
                                    case 'N':
                                        $params['file_type_id'] = array(E_FILING_TYPE_NEW_CASE);
                                        $params['stage_id'] = Transfer_to_IB_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Approved';
                                        $tmpArr['file_type'] = 'New Case';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'M':
                                        $params['file_type_id'] = array(E_FILING_TYPE_MISC_DOCS);
                                        $params['stage_id'] = Transfer_to_IB_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Approved';
                                        $tmpArr['file_type'] = 'Miscellaneous Doc.';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'I':
                                        $params['file_type_id'] = array(E_FILING_TYPE_IA);
                                        $params['stage_id'] = Transfer_to_IB_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Approved';
                                        $tmpArr['file_type'] = 'IA';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'C':
                                        $params['file_type_id'] = array(E_FILING_TYPE_CAVEAT);
                                        $params['stage_id'] = Transfer_to_IB_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Approved';
                                        $tmpArr['file_type'] = 'Caveat';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'A':
                                        $params['file_type_id'] =  unserialize(FILE_TYPE_ID);;
                                        $params['stage_id'] = Transfer_to_IB_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Approved';
                                        $tmpArr['file_type'] = 'All';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    default:
                                }
                                break;
                            case 'file_rejected':
                                switch ($fileType) {
                                    case 'N':
                                        $params['file_type_id'] = array(E_FILING_TYPE_NEW_CASE);
                                        $params['stage_id'] = Initial_Defected_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Rejected';
                                        $tmpArr['file_type'] = 'New Case';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'M':
                                        $params['file_type_id'] = array(E_FILING_TYPE_MISC_DOCS);
                                        $params['stage_id'] = Initial_Defected_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Rejected';
                                        $tmpArr['file_type'] = 'Miscellaneous Doc.';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'I':
                                        $params['file_type_id'] = array(E_FILING_TYPE_IA);
                                        $params['stage_id'] = Initial_Defected_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Rejected';
                                        $tmpArr['file_type'] = 'IA';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'C':
                                        $params['file_type_id'] = array(E_FILING_TYPE_CAVEAT);
                                        $params['stage_id'] = Initial_Defected_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Rejected';
                                        $tmpArr['file_type'] = 'Caveat';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'A':
                                        $params['file_type_id'] =  unserialize(FILE_TYPE_ID);;
                                        $params['stage_id'] = Initial_Defected_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Rejected';
                                        $tmpArr['file_type'] = 'All';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    default:
                                }
                                break;
                            case 'file_diaries':
                                switch ($fileType) {
                                    case 'N':
                                        $params['file_type_id'] = array(E_FILING_TYPE_NEW_CASE);
                                        $params['stage_id'] = I_B_Approval_Pending_Admin_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Diaries';
                                        $tmpArr['file_type'] = 'New Case';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'M':
                                        $params['file_type_id'] = array(E_FILING_TYPE_MISC_DOCS);
                                        $params['stage_id'] = I_B_Approval_Pending_Admin_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Diaries';
                                        $tmpArr['file_type'] = 'Miscellaneous Doc.';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'I':
                                        $params['file_type_id'] = array(E_FILING_TYPE_IA);
                                        $params['stage_id'] = I_B_Approval_Pending_Admin_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Diaries';
                                        $tmpArr['file_type'] = 'IA';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'C':
                                        $params['file_type_id'] = array(E_FILING_TYPE_CAVEAT);
                                        $params['stage_id'] = I_B_Approval_Pending_Admin_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Diaries';
                                        $tmpArr['file_type'] = 'Caveat';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    case 'A':
                                        $params['file_type_id'] =  unserialize(FILE_TYPE_ID);;
                                        $params['stage_id'] = I_B_Approval_Pending_Admin_Stage;
                                        $fileAllocatedData =   $this->AdminReportModel->getFileStatusWithFileType($params);
                                        $data['listData'] = $fileAllocatedData;
                                        $tmpArr = array();
                                        $tmpArr['from_date'] = $params['from_date'];
                                        $tmpArr['to_date'] = $params['to_date'];
                                        $tmpArr['user_type'] = !empty(getSessionData('login')['first_name']) ? strtoupper(getSessionData('login')['first_name']) : NULL;
                                        $tmpArr['type'] = 'File Diaries';
                                        $tmpArr['file_type'] = 'All';
                                        $data['typeDetails'] = $tmpArr;
                                        unset($params['file_type_id']);
                                        unset($params['stage_id']);
                                        break;
                                    default:
                                }
                                break;
                            default:
                        }
                }
            }
        }
        return $this->render('adminReport.listData', $data);
    }
    public function reportFormNew()
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if (empty(getSessionData('login')['ref_m_usertype_id'])) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $allowed_users_array = array(USER_EFILING_ADMIN, USER_ADMIN);
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('filingAdmin'));
            exit(0);
        }
        $data['count_efiling_data'] = $this->AdminDashboardModel->get_efilied_nums_stage_wise_count();
        $this->load->view('templates/admin_header');
        $this->load->view('adminReport/reports_new', $data);
        $this->load->view('templates/footer');
    }
    public function getReportDataNew()
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if (empty(getSessionData('login')['ref_m_usertype_id'])) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $allowed_users_array = array(USER_EFILING_ADMIN, USER_ADMIN);
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('filingAdmin'));
            exit(0);
        }
        $data = array();
        $postData = json_decode(file_get_contents('php://input'), true);
        $from_date = NULL;
        $to_date = NULL;
        $output = array();
        $validatinError = true;

        $userNameArr = array();
        if (isset($validatinError) && !empty($validatinError)) {
            $ref_m_usertype_id = !empty(getSessionData('login')['ref_m_usertype_id']) ? (int)getSessionData('login')['ref_m_usertype_id'] : NULL;
            if (isset($ref_m_usertype_id) && !empty($ref_m_usertype_id)) {
                $allocatedUserDetails = $this->AdminReportModel->getAllocatedUserDetails();
                if (isset($allocatedUserDetails) && !empty($allocatedUserDetails)) {
                    foreach ($allocatedUserDetails as $k => $v) {
                        $userNameArr[$v['allocated_to']] = strtoupper($v['user_name']);
                    }
                    $userNameArr['all'] = 'ALL';
                }
                $allocatedArr = array();
                if (isset($allocatedUserDetails) && !empty($allocatedUserDetails)) {
                    $allocatedArr = array_column($allocatedUserDetails, 'allocated_to');
                }
                $allocatedArr['all'] = 'all';
                $params = array();
                $params['user_type'] = $ref_m_usertype_id;
                $file_type_id = unserialize(FILE_TYPE_ID);
                $params['file_type_id'] = $file_type_id;
                switch ($ref_m_usertype_id) {
                    case USER_EFILING_ADMIN:
                        if (isset($allocatedArr) && !empty($allocatedArr)) {
                            foreach ($allocatedArr as $k => $v) {
                                //file allocated
                                if ($k === 'all') {
                                    $params['allocated_to'] = null;
                                } else {
                                    $params['allocated_to'] = (int)$v;
                                }
                                $type = "file_allocated";
                                $params['type'] = $type;
                                $params['stage_id'] = New_Filing_Stage;
                                $file_allocated =   $this->AdminReportModel->getCountDataNew($params);
                                $data[$v][$v]['file_allocated'] = !empty($file_allocated[0]) ? $file_allocated[0] : NULL;
                                unset($params['type']);
                                unset($params["stage_id"]);
                                //file approved
                                $type = "file_approved";
                                $params['type'] = $type;
                                $params['stage_id'] = Transfer_to_IB_Stage;
                                $file_approved =   $this->AdminReportModel->getCountDataNew($params);
                                $data[$v][$v]['file_approved'] = !empty($file_approved[0]) ? $file_approved[0] : NULL;
                                unset($params["type"]);
                                unset($params["stage_id"]);
                                //file rejected
                                $type = "file_rejected";
                                $params['type'] = $type;
                                $params['stage_id'] = Initial_Defected_Stage;
                                $file_rejected =   $this->AdminReportModel->getCountDataNew($params);
                                $data[$v][$v]['file_rejected'] = !empty($file_rejected[0]) ? $file_rejected[0] : NULL;
                                unset($params["type"]);
                                unset($params["stage_id"]);
                                //file diaries
                                $type = "file_diaries";
                                $params['type'] = $type;
                                $params['stage_id'] = I_B_Approval_Pending_Admin_Stage;
                                $file_diaries =   $this->AdminReportModel->getCountDataNew($params);
                                $data[$v][$v]['file_diaries'] = !empty($file_diaries[0]) ? $file_diaries[0] : NULL;
                                unset($params["type"]);
                                unset($params["stage_id"]);
                                unset($params['allocated_to']);
                            }
                        }
                        break;
                    case USER_ADMIN:
                        $login_user_id = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;
                        //file allocated
                        $type = "file_allocated";
                        $params['type'] = $type;
                        $params['stage_id'] = New_Filing_Stage;
                        $params['login_user_id'] = $login_user_id;
                        $file_allocated =   $this->AdminReportModel->getCountDataNew($params);
                        $data[$login_user_id][$login_user_id]['file_allocated'] = !empty($file_allocated[0]) ? $file_allocated[0] : NULL;
                        unset($params['type']);
                        unset($params["stage_id"]);
                        //file approved
                        $type = "file_approved";
                        $params['type'] = $type;
                        $params['stage_id'] = Transfer_to_IB_Stage;
                        $file_approved =   $this->AdminReportModel->getCountDataNew($params);
                        $data[$login_user_id][$login_user_id]['file_approved'] = !empty($file_approved[0]) ? $file_approved[0] : NULL;
                        unset($params["type"]);
                        unset($params["stage_id"]);
                        //file rejected
                        $type = "file_rejected";
                        $params['type'] = $type;
                        $params['stage_id'] = Initial_Defected_Stage;
                        $file_rejected =   $this->AdminReportModel->getCountDataNew($params);
                        $data[$login_user_id][$login_user_id]['file_rejected'] = !empty($file_rejected[0]) ? $file_rejected[0] : NULL;
                        unset($params["type"]);
                        unset($params["stage_id"]);
                        //file diaries
                        $type = "file_diaries";
                        $params['type'] = $type;
                        $params['stage_id'] = I_B_Approval_Pending_Admin_Stage;
                        $file_diaries =   $this->AdminReportModel->getCountDataNew($params);
                        $data[$login_user_id][$login_user_id]['file_diaries'] = !empty($file_diaries[0]) ? $file_diaries[0] : NULL;
                        unset($params["type"]);
                        unset($params["stage_id"]);
                        break;
                    default:
                }
                $output['status'] = 'success';
                $output['id'] = 'result';
                $output['msg'] = 'Record has been fetched successfully';
            } else {
                $output['status'] = 'success';
                $output['id'] = 'result';
                $output['msg'] = 'Something went wrong,Please try again later!';
            }
            $file_type_name = unserialize(FILE_TYPE_NAME);
            $tableData = '';
            foreach ($data as $key => $arr) {
                $ctn = 0;
                $tmpData1 = '';
                $tmpData2 = '';
                $tmpData3 = '';
                $tmpData4 = '';
                $tableData .= '<tr>';
                $tableData .= '<td>' . $userNameArr[$key] . '</td>';
                foreach ($arr[$key]['file_allocated'] as $k => $v) {
                    $fileTypeName = '';
                    $pipe = ' ';
                    if ($ctn <= 3) {
                        $pipe = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
                    }
                    if (array_key_exists($k, $file_type_name)) {
                        $fileTypeName = $file_type_name[$k];
                    }
                    $total = !empty($v) ? $v : 0;
                    $user_id = (is_numeric($key) == true) ? $key : 0;
                    $tmpData1 .= '<a class="pointer" href="' . base_url('adminReport/DefaultController/getFilingStageTypeData/?type=file_allocated&fileType=' . $fileTypeName . '&from_date=' . $from_date . '&to_date=' . $to_date . '&allocated_to=' . $user_id . ' ') . '">' . $total . $pipe . ' </a>';
                    $ctn++;
                }
                $tmpData1 = rtrim($tmpData1, ' | ');
                $tableData .= '<td>' . $tmpData1 . '</td>';
                $ctn = 0;
                foreach ($arr[$key]['file_approved'] as $k => $v) {
                    $fileTypeName = '';
                    $pipe = ' ';
                    if ($ctn <= 3) {
                        $pipe = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
                    }
                    if (array_key_exists($k, $file_type_name)) {
                        $fileTypeName = $file_type_name[$k];
                    }
                    $total = !empty($v) ? $v : 0;
                    $user_id = (is_numeric($key) == true) ? $key : 0;
                    $tmpData2 .= '<a class="pointer" href="' . base_url('adminReport/DefaultController/getFilingStageTypeData/?type=file_approved&fileType=' . $fileTypeName . '&from_date=' . $from_date . '&to_date=' . $to_date .  '&allocated_to=' . $user_id . ' ') . '">' . $total . $pipe . ' </a>';
                    $ctn++;
                }
                $tableData .= '<td>' . $tmpData2 . '</td>';
                $ctn = 0;
                foreach ($arr[$key]['file_rejected'] as $k => $v) {
                    $fileTypeName = '';
                    $pipe = ' ';
                    if ($ctn <= 3) {
                        $pipe = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
                    }
                    if (array_key_exists($k, $file_type_name)) {
                        $fileTypeName = $file_type_name[$k];
                    }
                    $total = !empty($v) ? $v : 0;
                    $user_id = (is_numeric($key) == true) ? $key : 0;
                    $tmpData3 .= '<a class="pointer" href="' . base_url('adminReport/DefaultController/getFilingStageTypeData/?type=file_rejected&fileType=' . $fileTypeName . '&from_date=' . $from_date . '&to_date=' . $to_date . '&allocated_to=' . $user_id . ' ') . '">' . $total . $pipe . ' </a>';
                    $ctn++;
                }
                $tableData .= '<td>' . $tmpData3 . '</td>';
                $ctn = 0;
                foreach ($arr[$key]['file_diaries'] as $k => $v) {
                    $fileTypeName = '';
                    $pipe = ' ';
                    if ($ctn <= 3) {
                        $pipe = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
                    }
                    if (array_key_exists($k, $file_type_name)) {
                        $fileTypeName = $file_type_name[$k];
                    }
                    $total = !empty($v) ? $v : 0;
                    $user_id = (is_numeric($key) == true) ? $key : 0;
                    $tmpData4 .= '<a class="pointer" href="' . base_url('adminReport/DefaultController/getFilingStageTypeData/?type=file_diaries&fileType=' . $fileTypeName . '&from_date=' . $from_date . '&to_date=' . $to_date . '&allocated_to=' . $user_id . ' ') . '">' . $total . '  ' . $pipe . ' </a>';
                    $ctn++;
                }
                $tableData .= '<td>' . $tmpData4 . '</td>';
                $tableData .= '</tr>';
            }
            $table = '';
            $table .= '<table id="datatable-responsive" class="table table-striped custom-table first-th-left" cellspacing="0">
                <thead>
                    <tr>
                        <th>Date<hr>
                            ' . date('d/m/Y', strtotime($params['from_date'])) . ' - ' . date('d/m/Y', strtotime($params['to_date'])) . '
                        </th>
                        <th>No. Of Applications Allocated <br><hr>CASE | MISC | IA | CAVEAT | ALL</th>
                        <th>No. Of Applications Approved<br><hr>CASE | MISC | IA | CAVEAT | ALL</th>
                        <th>No. Of Applications Rejected <br><hr>CASE | MISC | IA | CAVEAT | ALL</th>
                        <th>No. Of Applications Diaries <br><hr>CASE | MISC | IA | CAVEAT | ALL</th>
                    </tr>
                </thead>
                <tbody>' . $tableData . '</tbody>
            </table>
            <script>
                $(document).ready(function() {
                    $("#loader-wrapper").show();
                    $("#datatable-responsive").DataTable({
                        "ordering": false,
                    });
                });
            </script>';
        }
        $output['table'] = $table;
        echo json_encode($output);
        exit(0);
    }
    
}