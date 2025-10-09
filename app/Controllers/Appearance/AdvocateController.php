<?php

namespace App\Controllers\Appearance;

use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;
use CodeIgniter\Database\Query;
// use App\Models\Appearance\AdvocateModel;
use Config\Database;

class AdvocateController extends BaseController
{
    
    // protected $AdvocateModel;
    protected $db;
    protected $session;
    protected $request;
    protected $validation;
    protected $e_services;
    protected $Efiling_webservices;

    public function __construct()
    {
        parent::__construct();
        // $this->AdvocateModel = new AdvocateModel();
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->validation = \Config\Services::validation();
        $this->e_services = \Config\Database::connect('e_services');
        $this->Efiling_webservices = new Efiling_webservices();
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
    }

    public function index() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
    }

    public function listed_cases() {
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $pager = \Config\Services::pager();
        $aor_code='';
        if(!empty(getSessionData('login')['aor_code'])) {
            $aor_code=getSessionData('login')['aor_code'];
            // $list= $this->AdvocateModel->getListedCases($aor_code);
            $list= $this->Efiling_webservices->getListedCases($aor_code);
            $data['heading'] = 'CAUSE LIST';
            return $this->render('appearance.listed_cases', @compact('data','list'));
        } else {
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    public function modal_appearance() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $posted_data = $this->request->getPost();
        $aor_code = getSessionData('login')['aor_code'];
        // $data['added_data'] = $this->AdvocateModel->getAddedAdvocatesInDiary($posted_data);
        // $data['is_submitted'] = $this->AdvocateModel->getSubmittedAdvocatesInDiary($posted_data);
        $data['added_data'] = $this->Efiling_webservices->getAddedAdvocatesInDiary($aor_code, $posted_data['diary_no'], $posted_data['next_dt']);
        $data['is_submitted'] = $this->Efiling_webservices->getSubmittedAdvocatesInDiary($aor_code, $posted_data['diary_no'], $posted_data['next_dt']);
        return $this->render('appearance.modal_appearance', @compact('data','posted_data','added_data'));        
    }

    public function display_appearance_slip() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $posted_data = $this->request->getPost();
        $aor_code = getSessionData('login')['aor_code'];     
        // $data['slip_data'] = $this->AdvocateModel->getSubmittedAdvocatesInDiary($posted_data);
        $data['slip_data'] = $this->Efiling_webservices->getSubmittedAdvocatesInDiary($aor_code, $posted_data['diary_no'], $posted_data['next_dt']);
        return $this->render('appearance.display_appearance_slip', @compact('data','posted_data','slip_data'));
    }

    public function modal_appearance_save() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $postData = $this->request->getPost();
        $request = service('request');
        $session = session();
        $aor_code = getSessionData('login')['aor_code'];
        $timeoutValidation = $this->timeoutValidation($request->getPost('next_dt'));
        if ($timeoutValidation) {
            return $this->response->setJSON(['status' => 'timeout']);
        }
        $rules = [
            'advocate_type' => [
                'rules' => 'required|in_list[Adv., Sr. Adv., AOR, Attorney General for India, Solicitor General, A.S.G., Advocate General, Sr. A.A.G., A.A.G., D.A.G., Amicus Curiae]',
                'errors' => [
                    'in_list' => 'The selected advocate type is invalid.'
                ]
            ],
            'advocate_title' => [
                'rules' => 'required|in_list[Mr.,Mrs.,Ms.,M/s,Dr.,None]',
                'errors' => [
                    'in_list' => 'The selected advocate title is invalid.'
                ]
            ],
            'advocate_name' => [
                'rules' => 'required|string|max_length[100]|min_length[3]',
                'errors' => [
                    'max_length' => 'The advocate name cannot exceed 100 characters.',
                    'min_length' => 'The advocate name must be at least 3 characters long.'
                ]
            ],
        ];
        $this->validation->setRules($rules);
        if (!$this->validation->withRequest($request)->run()) {
            return $this->response->setJSON(['status' => 'error', 'data' => $this->validation->getErrors()]);
        }
        $data = [
            'diary_no' => esc($request->getPost('diary_no')),
            'list_date' => esc($request->getPost('next_dt')),
            'appearing_for' => esc($request->getPost('appearing_for')),
            'item_no' => esc($request->getPost('brd_slno')),
            'court_no' => esc($request->getPost('courtno')),
            'advocate_type' => esc($request->getPost('advocate_type')),
            'advocate_title' => $request->getPost('advocate_title') === 'None' ? '' : esc($request->getPost('advocate_title')),
            'advocate_name' => esc(trim($request->getPost('advocate_name'))),
            'aor_code' => $aor_code
        ];
        $currentDiaryNo = $session->get('diary_no');
        $appearPriority = $session->get('appear_priority', 1);
        if ($currentDiaryNo) {
            if ($currentDiaryNo == $request->getPost('diary_no')) {
                $session->set('appear_priority', $appearPriority + 1);
            } else {
                $session->set('diary_no', $request->getPost('diary_no'));
                $session->set('appear_priority', 1);
            }
        } else {
            $session->set('diary_no', $request->getPost('diary_no'));
            $session->set('appear_priority', 1);
        }
        $data['priority'] = $session->get('appear_priority');
        $result = $this->e_services->table('appearing_in_diary')->insert($data);
        if($result) {
            $insertID = $this->e_services->insertID();
        } 
        // $insertID = $this->Efiling_webservices->saveAppearanceConfirmationData($data);
        if($insertID) {
            $log = $data;
            $log['original_id']      = $insertID;
            $log['action_type']      = 'INSERT';
            $log['is_submitted']     = 0;
            $log['action_timestamp'] = date('d-m-Y');
            
            $sql = "INSERT INTO appearing_in_diary_log (original_id, action_type, action_timestamp, diary_no, list_date, court_no, item_no, aor_code, appearing_for, priority, advocate_type, advocate_title, advocate_name, is_submitted, is_active) VALUES (:original_id:, :action_type:, :action_timestamp:, :diary_no:, :list_date:, :court_no:, :item_no:, :aor_code:, :appearing_for:, :priority:, :advocate_type:, :advocate_title:, :advocate_name:, :is_submitted:, :is_active:)";
            $this->e_services->query($sql, $log);

            $data['entry_time'] = date('d-m-Y h:i:s A');
            $data['id'] = $insertID;
            return response()->setJson(array('status' => 'success','data' => $data));
        } else{
            return response()->setJson(array('status' => 'error','data' => $insertID));
        }
        return $this->response->setJSON(['status' => 'error', 'data' => $e->getMessage()]);
    }

    public function reportIndex() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $data['heading'] = "Advocates Appearing";
        return $this->render('appearance.report', @compact('data'));
    }

    public function appearingReport() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $request = service('request');
        $heading = "Advocates Appearing";
        // $cause_list_date = date('Y-m-d', strtotime($this->request->getPost('cause_list_date')));
        $cause_list_date = $this->request->getPost('cause_list_date');
        $cause_list_array = array();
        $dt = explode('/', $cause_list_date);
        $cause_list_date = $dt[2] . '-' . $dt[1] . '-' . $dt[0];
        $aor_code = getSessionData('login')['aor_code'];
        $cause_list = getAppearingDiaryNosOnly($cause_list_date, $aor_code);
        $list = array();
        if(!empty($cause_list)) {
            foreach($cause_list as $key => $cl) {
                $cause_list_array[$key]['diary_no'] = $cl->diary_no;
                $cause_list_array[$key]['list_date'] = $cl->list_date;
                $cause_list_array[$key]['court_no'] = $cl->court_no;
                $cause_list_array[$key]['item_no'] = $cl->item_no;
                $cause_list_array[$key]['appearing_for'] = $cl->appearing_for;
                $cause_list_array[$key]['diary_details'] = eCopyingGetFileDetails($cl->diary_no);
                // $cause_list_array[$key]['diary_details'] = $this->AdvocateModel->getDiaryDetails($cl->diary_no);
                // $cause_list_array[$key]['advocate_name'] = $this->AdvocateModel->getAppearingAdvocates($cl);
                $cause_list_array[$key]['advocate_name'] = $this->Efiling_webservices->getAppearingAdvocates($aor_code, $cl->diary_no, $cl->list_date);
            }
            // $cause_list_date = $this->request->getPost('cause_list_date');
            $list = $cause_list_array;
        }
        return $this->render('appearance.report', @compact('heading','cause_list_date','list'));
    }   

    public function confirm_final_submit() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $request = service('request');
        $box = $this->request->getPost();
        $timout_validation = $this->timeout_validation($box['next_dt']);
        if ($timout_validation) {
            return response()->setJSON(array('status' => 'timeout'));
            exit;
        }
        $myValue = array();
        parse_str($box['array_id'], $myValue);
        $total_updated = 0;
        if(isset($myValue['sortable_id']) && !empty($myValue['sortable_id'])) {
            foreach ($myValue['sortable_id'] as $key => $value) {
                $update['priority'] = $key;
                $update['is_submitted'] = '1';
                $upData = $this->Efiling_webservices->updateAppearanceConfirmationData($value, $update);
                unset($update);
                if ($upData) {
                    $total_updated += 1;
                    $sql = "SELECT * FROM appearing_in_diary WHERE id = $value";
                    $original = $this->e_services->query($sql)->getRow();
                    if ($original) {
                        $data = [
                            'original_id'      => $original->id,
                            'action_type'      => 'FINAL SUBMIT',
                            'action_timestamp' => date('d-m-Y'),
                            'diary_no'         => $original->diary_no,
                            'list_date'        => $original->list_date,
                            'court_no'         => $original->court_no,
                            'item_no'          => $original->item_no,
                            'aor_code'         => $original->aor_code,
                            'appearing_for'    => $original->appearing_for,
                            'priority'         => $key,
                            'advocate_type'    => $original->advocate_type,
                            'advocate_title'   => $original->advocate_title,
                            'advocate_name'    => $original->advocate_name,
                            'is_submitted'     => $original->is_submitted,
                            'is_active'        => $original->is_active,
                            'entry_date'       => $original->entry_date,
                            'updated_date'     => $original->updated_date,
                        ];
                        $sql1 = "INSERT INTO appearing_in_diary_log (original_id, action_type, action_timestamp, diary_no, list_date, court_no, item_no, aor_code, appearing_for, priority, advocate_type, advocate_title, advocate_name, is_submitted, is_active, entry_date, updated_date) VALUES (:original_id:, :action_type:, :action_timestamp:, :diary_no:, :list_date:, :court_no:, :item_no:, :aor_code:, :appearing_for:, :priority:, :advocate_type:, :advocate_title:, :advocate_name:, :is_submitted:, :is_active:, :entry_date:, :updated_date:)";
                        $this->e_services->query($sql1, $data);
                    }
                }
            }
            if ($total_updated > 0) {
                if(session('mobile')) {
                    $case_no_exploded_in = explode("IN", strtoupper($box['case_no']));
                    $sms_data['sms_content'] = "The appearance slip for case no. ".$case_no_exploded_in[0]." submitted by you on ".date('d-m-Y')." time ".date('h:i:s a')." is forwarded to court master of court room no. ".$box['courtno'].". -Supreme Court of India.";
                    $sms_data['mobile_no'] = session('mobile');
                    $sms_data['template_id'] = config("constants.SMS_TEMPLATE_APPEARANCE_SLIP_SUBMITTED");
                    sendSMS(38, $sms_data['mobile_no'], $sms_data['sms_content'], $sms_data['template_id']);
                }
                return $this->response->setJSON([
                    'status' => 'success',
                    'case_no' => $box['case_no'],
                    'cause_title' => $box['cause_title'],
                    'diary_no' => $box['diary_no'],
                    'next_dt' => $box['next_dt'],
                    'appearing_for' => $box['appearing_for'],
                    'brd_slno' => $box['brd_slno'],
                    'courtno' => $box['courtno']
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error']);
            }
        } else {
            return $this->response->setJSON(['status' => 'error']);
        }
    }

    public function add_from_case_advocate_master_list() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $request = service('request');
        $data = $this->request->getPost();
        $previous_list_date= $this->Efiling_webservices->getPreviousListingDate($data, '', '');
        if($previous_list_date) {
            $previous_list_advocates= $this->Efiling_webservices->getPreviousListAdvocates($data, $previous_list_date, '');
        } else {
            $previous_list_advocates = "";
        }
        return $this->render('appearance.master_advocates_page', @compact('data','previous_list_advocates'));
    }

    public function master_list_submit() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $request = service('request');
        $timeoutValidation = $this->timeout_validation($request->getPost('next_dt'));
        if ($timeoutValidation) {
            return $this->response->setJSON('timeout');
        }
        $data = $request->getPost('array');
        // $a = $this->AdvocateModel->getAdvocateMasterList($data);        
        $a = $this->Efiling_webservices->getAdvocateMasterList($data);        
        $display = [];
        foreach ($a as $a_value) {
            $insert = [
                'diary_no'        => $request->getPost('diary_no'),
                'list_date'       => $request->getPost('next_dt'),
                'appearing_for'  => $request->getPost('appearing_for'),
                'item_no'        => $request->getPost('brd_slno'),
                'court_no'       => $request->getPost('courtno'),
                'advocate_type'  => $a_value->advocate_type,
                'advocate_title' => $a_value->advocate_title,
                'advocate_name'  => $a_value->advocate_name,
                'aor_code'       => session()->get('aor_code')
            ];
            $session = session();
            if ($session->has('diary_no')) {
                if ($session->get('diary_no') == $request->getPost('diary_no')) {
                    $session->set('appear_priority', $session->get('appear_priority') + 1);
                } else {
                    $session->set('diary_no', $request->getPost('diary_no'));
                    $session->set('appear_priority', 1);
                }
            } else {
                $session->set('diary_no', $request->getPost('diary_no'));
                $session->set('appear_priority', 1);
            }
            $insert['priority'] = $session->get('appear_priority');
            $builder = $this->e_services->table('appearing_in_diary');
            $value = $builder->insert($insert, true);
            $data = [
                'action_type'      => 'INSERT',
                'original_id'      => $value,
                'action_timestamp' => now(),
                'diary_no'         => $insert['diary_no'],
                'list_date'        => $insert['list_date'],
                'court_no'         => $insert['court_no'],
                'item_no'          => $insert['item_no'],
                'aor_code'         => $insert['aor_code'],
                'appearing_for'    => $insert['appearing_for'],
                'priority'         => $insert['priority'],
                'advocate_type'    => $insert['advocate_type'],
                'advocate_title'   => $insert['advocate_title'],
                'advocate_name'    => $insert['advocate_name'],
                'is_submitted'     => 0,
                'is_active'        => 1
            ];
            $sql = "INSERT INTO appearing_in_diary_log (action_type, original_id, action_timestamp, diary_no, list_date, court_no, item_no, aor_code, appearing_for, priority, advocate_type, advocate_title, advocate_name, is_submitted, is_active) VALUES (:action_type:, :original_id:, :action_timestamp:, :diary_no:, :list_date:, :court_no:, :item_no:, :aor_code:, :appearing_for:, :priority:, :advocate_type:, :advocate_title:, :advocate_name:, :is_submitted:, :is_active:)";
            $this->e_services->query($sql, $data);
            array_push($display, array(
                'id' =>$value,
                'next_dt' => $request->input('next_dt'),
                'advocate_type' => $a_value->advocate_type,
                'advocate_title' => $a_value->advocate_title,
                'advocate_name' => $a_value->advocate_name,
                'entry_time' => date('d-m-Y h:i:s A') )
            );
            unset($insert);
        }
        return $this->response->setJSON($display);
    }

    public function remove_advocate() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $request = service('request');
        $timeoutValidation = $this->timeoutValidation($request->getPost('next_dt'));
        if ($timeoutValidation) {
            return $this->response->setJSON(['status' => 'timeout']);
        }
        $nextDt = $this->request->getPost('next_dt');
        $id = $this->request->getPost('id');
        $isActive = '0';
        $data['is_active'] = 0;
        $fas = "fa-trash-restore";
        $btnColor = "btn-warning";
        $msg = "Removed Successfully.";
        // $builder = $this->e_services->table('appearing_in_diary');  
        $data = ['is_active' => $isActive];
        // $value = $builder->where('id', $id)->update($data);
        $value = $this->Efiling_webservices->removeAppearanceConfirmationData($id, $data);
        if($value){
            $record = "SELECT * FROM appearing_in_diary WHERE id = $id";
            $record = $this->e_services->query($record)->getRow();
            if ($record) {
                $data = [
                    'original_id'      => $record->id,
                    'action_type'      => 'DELETE',
                    'action_timestamp' => date('d-m-Y'),
                    'diary_no'         => $record->diary_no,
                    'list_date'        => $record->list_date,
                    'court_no'         => $record->court_no,
                    'item_no'          => $record->item_no,
                    'aor_code'         => $record->aor_code,
                    'appearing_for'    => $record->appearing_for,
                    'priority'         => $record->priority,
                    'advocate_type'    => $record->advocate_type,
                    'advocate_title'   => $record->advocate_title,
                    'advocate_name'    => $record->advocate_name,
                    'is_submitted'     => $record->is_submitted,
                    'is_active'        => $record->is_active,
                    'entry_date'       => $record->entry_date,
                    'updated_date'     => $record->updated_date,
                ];
                $sql = "INSERT INTO appearing_in_diary_log (original_id, action_type, action_timestamp, diary_no, list_date, court_no, item_no, aor_code, appearing_for, priority, advocate_type, advocate_title, advocate_name, is_submitted, is_active, entry_date, updated_date) VALUES (:original_id:, :action_type:, :action_timestamp:, :diary_no:, :list_date:, :court_no:, :item_no:, :aor_code:, :appearing_for:, :priority:, :advocate_type:, :advocate_title:, :advocate_name:, :is_submitted:, :is_active:, :entry_date:, :updated_date:)";
                $this->e_services->query($sql, $data);
            }
            return $this->response->setJSON([
                'status' => 'success',
                'next_dt' => $nextDt,
                'id' => $id,
                'is_active' => $isActive,
                'fas' => $fas,
                'btn_color' => $btnColor,
                'msg' => $msg
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'next_dt' => $nextDt,
                'id' => $id,
                'is_active' => $isActive,
                'fas' => $fas,
                'btn_color' => $btnColor
            ]);
            return response()->setJSON(array('status' => 'error', 'next_dt' => $request->input('next_dt'), 'id' => $request->input('id'),'is_active' => $data['is_active'],'fas' => $fas,'btn_color' => $btn_color));
        }
    }  

    public function timeout_validation($list_date) {
        if ($list_date == CURRENT_DATE && date('H:i:s') > APPEARANCE_ALLOW_TIME) {
            return true;
        } else {
            return false;
        }
    }

    public function timeoutValidation($listDate) {
        $currentDate = CURRENT_DATE;
        $appearanceAllowTime = APPEARANCE_ALLOW_TIME;
        if ($listDate == $currentDate && date('H:i:s') > $appearanceAllowTime) {
            return true;
        } else {
            return false;
        }
    }    
 
}