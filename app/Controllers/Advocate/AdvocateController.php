<?php

namespace App\Controllers\Advocate;
use App\Controllers\BaseController;
use CodeIgniter\Database\Query;
use App\Models\Advocate\AdvocateModel;
use Config\Database;

class AdvocateController extends BaseController
{
    protected $AdvocateModel;
    protected $db;
    protected $session;
    protected $request;
    protected $validation;
    protected $e_services;    
    public function __construct()
    {
        parent::__construct();
        $this->AdvocateModel = new AdvocateModel();            
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->validation = \Config\Services::validation();
        $this->e_services = \Config\Database::connect('e_services');
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        }else{
            is_user_status();
        }
    }

    public function index()
    {
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        }else{
            is_user_status();
        }
    }

    public function listed_cases() {
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        }else{
            is_user_status();
        }
        $pager = \Config\Services::pager();
        $aor_code='';
        if(!empty(getSessionData('login')['aor_code'])){
            $aor_code=getSessionData('login')['aor_code'];
            $list= $this->AdvocateModel->getListedCases($aor_code);
            $data['heading'] = 'CAUSE LIST';
            return $this->render('advocate.listed_cases', @compact('data','list'));
        } else {
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    public function modal_appearance()
    {
        $posted_data = $this->request->getPost();
        $data['added_data'] = $this->AdvocateModel->getAddedAdvocatesInDiary($posted_data);
        $data['is_submitted'] = $this->AdvocateModel->getSubmittedAdvocatesInDiary($posted_data);
        return $this->render('advocate.modal_appearance', @compact('data','posted_data','added_data'));        
    }

    public function display_appearance_slip() 
    {
        $posted_data = $this->request->getPost();     
        $data['slip_data'] = $this->AdvocateModel->getSubmittedAdvocatesInDiary($posted_data);
        return $this->render('advocate.display_appearance_slip', @compact('data','posted_data','slip_data'));
    }



    public function modal_appearance_save() 
    {
        $postData = $this->request->getPost();
        $request = service('request');
        $session = session();
        $aor_code=getSessionData('login')['aor_code'];
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
        // Validate request data          
        $this->validation->setRules($rules);
        if (!$this->validation->withRequest($request)->run()) {
            return $this->response->setJSON(['status' => 'error', 'data' => $this->validation->getErrors()]);
        }
        $data = [
            'diary_no' => $request->getPost('diary_no'),
            'list_date' => $request->getPost('next_dt'),
            'appearing_for' => $request->getPost('appearing_for'),
            'item_no' => $request->getPost('brd_slno'),
            'court_no' => $request->getPost('courtno'),
            'advocate_type' => $request->getPost('advocate_type'),
            'advocate_title' => $request->getPost('advocate_title') === 'None' ? '' : $request->getPost('advocate_title'),
            'advocate_name' => trim($request->getPost('advocate_name')),
            'aor_code' => $aor_code
        ];
        $currentDiaryNo = $session->get('diary_no');
        $appearPriority = $session->get('appear_priority', 1); // Default to 1 if not set
        if ($currentDiaryNo) {
            if ($currentDiaryNo == $request->getPost('diary_no')) {
                // Increment priority if diary_no matches
                $session->set('appear_priority', $appearPriority + 1);
            } else {
                // Update diary_no and reset priority
                $session->set('diary_no', $request->getPost('diary_no'));
                $session->set('appear_priority', 1);
            }
        } else {
            // Set diary_no and initialize priority
            $session->set('diary_no', $request->getPost('diary_no'));
            $session->set('appear_priority', 1);
        }
        $data['priority'] = $session->get('appear_priority');
        $builder = $this->e_services->table('appearing_in_diary');
        $builder->insert($data);
        $insertID = $this->e_services->insertID(); // Get the ID of the inserted row             
        $data['entry_time'] = date('d-m-Y h:i:s A');
        $data['id'] = $insertID;
        if ($insertID) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        } else {
            return $this->response->setJSON(['status' => 'error', 'data' => 'Insert failed']);
        }        
        return $this->response->setJSON(['status' => 'error', 'data' => $e->getMessage()]);
    }

    public function remove_advocate() {
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
        $builder = $this->e_services->table('appearing_in_diary');  
        $data = ['is_active' => $isActive];
        $value = $builder->where('id', $id)->set($data)->update();
        if($value){
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

    public function reportIndex() 
    {
        $data['heading'] = "Advocates Appearing";
        return $this->render('advocate.report', @compact('data'));
    }

    public function appearingReport() {
        $request = service('request');

        $heading = "Advocates Appearing";
        $cause_list_date = date('Y-m-d', strtotime($this->request->getPost('cause_list_date')));
        $cause_list_array = array();
        $cause_list= $this->AdvocateModel->getAppearingDiaryNosOnly($cause_list_date);
        foreach($cause_list as $key => $cl){
            $cause_list_array[$key]['diary_no'] = $cl->diary_no;
            $cause_list_array[$key]['list_date'] = $cl->list_date;
            $cause_list_array[$key]['court_no'] = $cl->court_no;
            $cause_list_array[$key]['item_no'] = $cl->item_no;
            $cause_list_array[$key]['appearing_for'] = $cl->appearing_for;
            $cause_list_array[$key]['diary_details'] = $this->AdvocateModel->getDiaryDetails($cl->diary_no);
            $cause_list_array[$key]['advocate_name'] = $this->AdvocateModel->getAppearingAdvocates($cl);
        }
        $cause_list_date = $this->request->getPost('cause_list_date');
        $list = $cause_list_array;
        return $this->render('advocate.report', @compact('heading','cause_list_date','list'));
    }   

    public function confirm_final_submit() {
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
        foreach ($myValue['sortable_id'] as $key => $value) {
            $update['priority'] = $key;
            $update['is_submitted'] = '1';
            $builder = $this->e_services->table('appearing_in_diary');  
            $result = $builder->where('id', $value)->where('is_active', '1')->set($update)->update();
            unset($update);
            if ($result) {
                $total_updated += 1;
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
    }

    public function add_from_case_advocate_master_list() {
        $request = service('request');
        $data = $this->request->getPost();
        $previous_list_date= $this->AdvocateModel->getPreviousListingDate($data);
        if($previous_list_date){
            $previous_list_advocates= $this->AdvocateModel->getPreviousListAdvocates($data,$previous_list_date);
        }
        else{
            $previous_list_advocates = "";
        }
        return $this->render('advocate.master_advocates_page', @compact('data','previous_list_advocates'));
    }

    public function master_list_submit() 
    {
        $request = service('request');
        // Timeout validation
        $timeoutValidation = $this->timeout_validation($request->getPost('next_dt'));
        if ($timeoutValidation) {
            return $this->response->setJSON('timeout');
        }

        $data = $request->getPost('array');
        $advocateModel = new AdvocateModel();
        $a = $advocateModel->getAdvocateMasterList($data);        
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
            $db3 = \Config\Database::connect('e_services'); 
            $session = \Config\Services::session();
            $builder = $db3->table('appearing_in_diary');
            $value = $builder->insert($insert, true);

            $display[] = [
                'id'             => $value,
                'next_dt'        => $request->getPost('next_dt'),
                'advocate_type'  => $a_value->advocate_type,
                'advocate_title' => $a_value->advocate_title,
                'advocate_name'  => $a_value->advocate_name,
                'entry_time'     => date('d-m-Y h:i:s A')
            ];
        }

        return $this->response->setJSON($display);
    }  

    public function timeout_validation($list_date) 
    {
        // Directly use the constants
        if ($list_date == CURRENT_DATE && date('H:i:s') > APPEARANCE_ALLOW_TIME) {
            return true;
        } else {
            return false;
        }
    }



    public function timeoutValidation($listDate) 
    {
        $currentDate = CURRENT_DATE;
        $appearanceAllowTime = APPEARANCE_ALLOW_TIME;
        if ($listDate == $currentDate && date('H:i:s') > $appearanceAllowTime) {
            return true;
        } else {
            return false;
        }
    }    
 
}