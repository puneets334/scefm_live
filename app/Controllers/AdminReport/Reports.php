<?php

namespace App\Controllers\AdminReport;

use App\Controllers\BaseController;
use App\Models\AdminReport\AdminReportsModel;

class Reports extends BaseController {

    protected $request;
    protected $AdminModel;

    public function __construct() {
        parent:: __construct();
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        }else{
            is_user_status();
        }
        $this->request = \Config\Services::request();
        $this->AdminModel = new AdminReportsModel();
    }

    public function search() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $allowed_users_array = array(USER_ADMIN);
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }
        return $this->render('adminReport.report_search');
    }

    public function index() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $data = array();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (empty(getSessionData('login')['ref_m_usertype_id'])) {
                echo "1@@@".'Permission denied please contact computer cell!';exit(0);
            }
            $allowed_users_array = array(USER_ADMIN);
            if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
                echo "1@@@".'Permission denied please contact computer cell';exit(0);
            }
            if (!empty($this->request->getGet('from_date'))) {
                $from_date = !empty($this->request->getGet('from_date')) ? date('Y-m-d', strtotime(str_replace('/', '-', $this->request->getGet('from_date')))) : NULL;
            } else {
                $validatinError = false;
                echo "1@@@".'Please select from date.';exit(0);
            }
            if(!empty($this->request->getGet('to_date'))) {
                $to_date = !empty($this->request->getGet('to_date')) ? date('Y-m-d',strtotime(str_replace('/','-',$this->request->getGet('to_date')))) : NULL;
            } else {
                $output['msg'] = 'Please select to date.';
                $validatinError = false;
                echo "1@@@".'Please select to date.';exit(0);
            }
            $data['request_method']='GET';
            $launch_date= $from_date;
            $postData['to_date'] = $to_date;
            $postData['from_date'] =$launch_date; // date('Y-m-d H:i:s', strtotime("-1 days"));
            $postData['current_date'] = date('Y-m-d');
        } else {
            $launch_date= date('Y-m-d',strtotime('01-05-2023'));
            $postData['to_date'] = date('Y-m-d', strtotime("-1 days"));
            $postData['from_date'] =$launch_date; // date('Y-m-d H:i:s', strtotime("-1 days"));
            $postData['current_date'] = date('Y-m-d');
        }
        $from_date = NULL;
        $to_date = NULL;
        $output = array();
        $validatinError = true;
        if(isset($postData['from_date']) && !empty($postData['from_date'])) {
            $from_date = trim($postData['from_date']);
        } else {
            $data['status'] = 'error';
            $data['id'] = 'from_date';
            $data['msg'] = 'Please select from date.';
            $validatinError = false;
        }
        if(isset($postData['to_date']) && !empty($postData['to_date'])) {
            $to_date = trim($postData['to_date']);
        } else {
            $data['status'] = 'error';
            $data['id'] = 'to_date';
            $data['msg'] = 'Please select to date.';
            $validatinError = false;
        }
        $data['heading_1st']='<b>Cases/Documents E-Filed between: </b>'.date("d-m-Y", strtotime($postData['from_date'])) . ' TO '.date("d-m-Y", strtotime($postData['to_date']));
        $data['heading_2nd']='<b>Cases/Documents E-Filed today (as on </b>'.date("d-m-Y h:i:s A").')';
        $userNameArr = array();
        if(isset($validatinError) && !empty($validatinError)) {
            $ref_m_usertype_id = 20;
            if(isset($ref_m_usertype_id) && !empty($ref_m_usertype_id)) {
                $allocatedUserDetails = $this->AdminModel->getAllocatedUserDetails();
                if(isset($allocatedUserDetails) && !empty($allocatedUserDetails)) {
                    foreach ($allocatedUserDetails as $k=>$v) {
                        $userNameArr[$v['allocated_to']] = strtoupper($v['user_name']);
                    }
                    $userNameArr['all'] = 'ALL';
                }
                $allocatedArr = array();
                if(isset($allocatedUserDetails) && !empty($allocatedUserDetails)) {
                    $allocatedArr = array_column($allocatedUserDetails,'allocated_to');
                }
                $data['file_allocated']=array();
                $data['file_allocated_current_date']=array();
                $allocatedArr['all'] = 'all';
                $params = array();
                $params['user_type'] = $ref_m_usertype_id;
                $file_type_id = unserialize(FILE_TYPE_ID);
                $params['file_type_id'] = $file_type_id;
                switch ($ref_m_usertype_id) {
                    case USER_EFILING_ADMIN:
                        if(isset($allocatedArr) && !empty($allocatedArr)) {
                            foreach ($allocatedArr as $k=>$v) {
                                // file allocated
                                if($k === 'all') {
                                    $params['allocated_to'] = null;
                                } else {
                                    $params['allocated_to'] = (int)$v;
                                }
                                $type="file_allocated";
                                $params['type'] = $type;
                                $params['stage_id'] = New_Filing_Stage;
                                $params['from_date'] = $from_date;
                                $params['to_date'] = $to_date;
                                $file_allocated =   $this->AdminModel->getCountData($params);
                                $data['file_allocated'] = !empty($file_allocated[0]) ? $file_allocated[0] : NULL;
                                if (!empty($postData['current_date'])) {
                                    $params['to_date']=''; $params['from_date']='';
                                    $params['to_date']=$postData['current_date'];
                                    $params['from_date'] = $postData['current_date'];
                                    $file_allocated_7days =   $this->AdminModel->getCountData($params);
                                    $data['file_allocated_current_date'] = !empty($file_allocated_7days[0]) ? $file_allocated_7days[0] : NULL;
                                }
                            }
                        }
                    break;
                    case USER_ADMIN:
                        $login_user_id = 3;
                        // file allocated
                        $type="file_allocated";
                        $params['type'] = $type;
                        $params['stage_id'] = New_Filing_Stage;
                        $params['login_user_id'] = $login_user_id;
                        $params['from_date'] = $from_date;
                        $params['to_date'] = $to_date;
                        $file_allocated =   $this->AdminModel->getCountData($params);
                        $data['file_allocated'] = !empty($file_allocated[0]) ? $file_allocated[0] : NULL;
                        if (!empty($postData['current_date'])) {
                            $params['to_date']=''; $params['from_date']='';
                            $params['to_date']=$postData['current_date'];
                            $params['from_date'] = $postData['current_date'];
                            $file_allocated_7days =   $this->AdminModel->getCountData($params);
                            $data['file_allocated_current_date'] = !empty($file_allocated_7days[0]) ? $file_allocated_7days[0] : NULL;
                        }
                    break;
                    default:
                }
                $data['status'] = 'success';
                $data['id'] = 'result';
                $data['msg'] = 'Record has been fetched successfully';
            } else {
                $data['status'] = 'success';
                $data['id'] = 'result';
                $data['msg'] = 'Something went wrong,Please try again later!';
            }
        }
        $data['to_email']=array('anshukumargupta92@gmail.com');
        $data['subject']='SC-eFM Statistics';
        $data['message']='Statistical Information';
        $data['view']='Statistical Information';
        $result=send_mail_cron($data);
        if($result != 'success') {
            echo "200@@@Sent Mail Failed. Please try again."; exit();
        }
        echo "200@@@Sent Mail".$result.' . Please Check Your Email.'; exit();
    }

    // Changes done on 08022024 by KBP
    public function newCRONReport() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $modelName = new \App\Models\AdminReport\AdminReportsModel();
        $data = array();
        $data['request_method'] = 'CRON';
        $file_type_id = unserialize(FILE_TYPE_ID);
        $params['file_type_id'] = $file_type_id;
        $launch_date= date('Y-m-d',strtotime('01-05-2023'));
        $postData['to_date'] = date('Y-m-d', strtotime("-1 days"));
        $postData['from_date'] =$launch_date; // date('Y-m-d H:i:s', strtotime("-1 days"));
        $postData['current_date'] = date('Y-m-d');
        $from_date = NULL;
        $to_date = NULL;
        $output = array();
        $validatinError = true;
        if(isset($postData['from_date']) && !empty($postData['from_date'])) {
            $from_date = trim($postData['from_date']);
        } else {
            $data['status'] = 'error';
            $data['id'] = 'from_date';
            $data['msg'] = 'Please select from date.';
            $validatinError = false;
        }
        if(isset($postData['to_date']) && !empty($postData['to_date'])) {
            $to_date = trim($postData['to_date']);
        } else {
            $data['status'] = 'error';
            $data['id'] = 'to_date';
            $data['msg'] = 'Please select to date.';
            $validatinError = false;
        }
        $data['heading_1st']='<b>Cases/Documents E-Filed between: </b>'.date("d-m-Y", strtotime($postData['from_date'])) . ' TO '.date("d-m-Y", strtotime($postData['to_date']));
        $data['heading_2nd']='<b>Cases/Documents E-Filed today (as on </b>'.date("d-m-Y h:i:s A").')';
        if(isset($validatinError) && !empty($validatinError)) {
            $ref_m_usertype_id = 20;
            if(isset($ref_m_usertype_id) && !empty($ref_m_usertype_id)) {
                $type = "file_allocated";
                $params['type'] = $type;
                $params['user_type'] = $ref_m_usertype_id;
                $params['stage_id'] = New_Filing_Stage;
                $params['from_date'] = $from_date;
                $params['to_date'] = $to_date;
                $file_allocated = $modelName->getCountDataNew($params);
                $data['file_allocated'] = !empty($file_allocated[0]) ? $file_allocated[0] : NULL;
                $govt_file_allocated = $modelName->getGovtCountDataNew($params);
                $data['govt_file_allocated'] = !empty($govt_file_allocated[0]) ? $govt_file_allocated[0] : NULL;
                if (!empty($postData['current_date'])) {
                    $params['to_date'] = ($postData['current_date'])?$postData['current_date']:'';
                    $params['from_date'] = ($postData['current_date'])?$postData['current_date']:'';
                    $file_allocated_7days = $modelName->getCountDataNew($params);
                    $data['file_allocated_current_date'] = !empty($file_allocated_7days[0]) ? $file_allocated_7days[0] : NULL;
                    $govt_file_allocated_7days = $modelName->getGovtCountDataNew($params);
                    $data['govt_file_allocated_current_date'] = !empty($govt_file_allocated_7days[0]) ? $govt_file_allocated_7days[0] : NULL;
                }
            } else {
                $data['status'] = 'success';
                $data['id'] = 'result';
                $data['msg'] = 'Something went wrong,Please try again later!';
            }
        }
        // $data['to_email']=array('sca.aktripathi@sci.nic.in','ppavan.sc@nic.in','reg.computercell@sci.nic.in','adreg.computercell@sci.nic.in','sca.kbpujari@sci.nic.in','sca.mohitjain@sci.nic.in','sg.office@sci.nic.in','office.regj2@sci.nic.in','reg.pavaneshd@sci.nic.in','ashish.js@nic.in','office.regj1@sci.nic.in','ca.shahnawaj@sci.nic.in','jca.shilpamalhotra@sci.nic.in');
        $data['to_email']=array('punit.sharma@velocis.co.in','ashutosh.gupta@velocis.co.in','anshukumargupta92@gmail.com');
        $data['subject']='SC-eFM Statistics';
        $data['message']='Statistical Information';
        $result=send_mail_cron($data);
        if($result != 'success') {
            echo "200@@@Sent Mail Failed. Please try again."; exit();
        }
        echo "200@@@Sent Mail".$result.' . Please Check Your Email.'; exit();
    }

}