<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

namespace App\Controllers\PhysicalHearing;

use App\Controllers\BaseController;
// use CodeIgniter\HTTP\ResponseInterface;
// use App\Models\PhysicalHearing\ConsentVCModel;
use App\Models\PhysicalHearing\AppearanceModel;
use App\Models\PhysicalHearing\AuthModel;
use App\Models\PhysicalHearing\HearingModel;

class Home extends BaseController {
    
	// protected $consent_VC_model;
	protected $appearance_model;
	protected $auth_model;
	protected $hearing_model;

    public function __construct() {
        parent::__construct();
        $dbs = \Config\Database::connect();
        $this->db = $dbs->connect();
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT, USER_ADMIN, USER_ADMIN_READ_ONLY, USER_EFILING_ADMIN, SR_ADVOCATE, ARGUING_COUNSEL);
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return response()->redirect(base_url('adminDashboard'));
            exit(0);
        }
		// if (!isset($_SESSION['loginData']) && empty($_SESSION['loginData'])) {
		// 	return redirect()->to(base_url('auth'));
		// } else {
		// 	is_user_status();
		// }
        // $this->load->helper('common');
        // $this->load->helper('encryptdecrypt');
        // $this->load->helper('myarray');
        // $this->load->helper('curl');
        // $this->load->helper('url');
        // $this->load->helper('form');
        // $this->load->database('icmis');
        // $this->load->model('consent_VC_model');
		helper(['common', 'encryptdecrypt', 'myarray', 'curl', 'url', 'form']); 
        // $this->consent_VC_model = new ConsentVCModel(); 
        $this->appearance_model = new AppearanceModel();
		$this->auth_model = new AuthModel();
		$this->hearing_model = new HearingModel();
       	// $this->getTodayData(); exit;
    }

    public function index() {
        if(!isset($this->session->loginData)) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Session Expired. Please login again.</div>');
            unset($this->session->loginData);
            return redirect()->to(base_url('auth'));
        } else {
			$type = !empty(getSessionData('login')['dec_type']) ? (int)getSessionData('login')['dec_type']  : NULL;
			if(!empty($type)) {
				switch ($type) {
					case 1:
							$data['page_title']='Case List';
							if(isset($_POST['list_date']))
								$data['case_list']=$this->appearance_model->getFutureListedMatters($this->session->loginData['bar_id'], null, $_POST['list_date']);
							else
								$data['case_list']=$this->appearance_model->getFutureListedMatters($this->session->loginData['bar_id'], null);

                            $this->load->view('physical_hearing/menu');
							$this->load->view('physical_hearing/case_list', $data);

					break;
					case 2:
						if(!empty(getSessionData('login')['post_mobile'])){
							$params = array();
							$params['current_date'] = date('Y-m-d');
							$params['mobile'] = (int)getSessionData('login')['post_mobile'];
							// $this->load->model('auth_model');
							//today exist data
							$todayData = $this->auth_model->getSelfDeclarationTodayData($params);
							$data['todayData'] = !empty($todayData) ? $todayData: NULL;
						}
						$data['page_title']='Supreme Court Of India Self Declaration Form (For Entrants In the High Security Zone)';
						//$this->load->view('physical_hearing/menu');
						$this->load->view('physical_hearing/emp_self_details', $data);
						break;
						default;
				}

			}
			else{
				return redirect()->to(base_url('auth'));
			}
        }
    }
    public function selfDeclarationForm()
	{
		if(!isset($this->session->loginData)){
			$this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Session Expired. Please login again.</div>');
			unset($this->session->loginData);
			return redirect()->to(base_url('auth'));
		}
		else {
			//echo '<pre>'; print_r($this->session->loginData); exit;
			if(!empty(getSessionData('login')['mobile'])){
				$params = array();
				$params['current_date'] = date('Y-m-d');
				$params['mobile'] = (int)getSessionData('login')['mobile'];
				// $this->load->model('auth_model');
				//today exist data
				$todayData = $this->auth_model->getSelfDeclarationTodayData($params);
				$data['todayData'] = !empty($todayData) ? $todayData: NULL;
			}
			$data['page_title'] = 'Supreme Court Of India Self Declaration Form (For Entrants In the High Security Zone)';
			$this->load->view('physical_hearing/menu');
			$this->load->view('physical_hearing/emp_self_details', $data);
		}
	}
    public function getAttendeeList(){
    	// $this->load->model('hearing_model');
		$result=$this->hearing_model->getAttendee();
		$attendeeArr = array();
		if(isset($result) && !empty($result)){
			foreach ($result as $k=>$v){
				$tmp =array();
				$tmp['id'] = $v['id'];
				$tmp['description'] = $v['description'];
				array_push($attendeeArr,(object)$tmp);
			}
		}
		echo json_encode(array('attendee'=>$attendeeArr));
		exit;
	}
	public function empSelfDeclarationData()
	{
		$data['page_title'] = 'Supreme Court Of India Self Declaration Form (For Entrants In the High Security Zone)';
		$this->load->view('physical_hearing/menu');
		$this->load->view('physical_hearing/emp_self_details_data', $data);

//		if(!isset($this->session->loginData)){
//		$this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Session Expired. Please login again.</div>');
//		unset($this->session->loginData);
//		return redirect()->to(base_url('auth'));
//	}
//	else {
//			$data['page_title'] = 'Supreme Court Of India Self Declaration Form (For Entrants In the High Security Zone)';
//			$this->load->view('physical_hearing/menu');
//			$this->load->view('physical_hearing/emp_self_details_data', $data);
//		}
	}
	public function getEmpSelfDeclarationData(){
		// $this->load->model('auth_model');
		$params =array();
		$params['current_date'] = date('Y-m-d');
		$res = $this->auth_model->getSelfDeclarationTodayData($params);
		if(isset($res) && !empty($res)){
			echo json_encode(array('status'=>true,'empSelfData'=>$res));
			exit;
		}
		else{
			echo json_encode(array('status'=>false,'empSelfData'=>''));
			exit;
		}
	}
	public function getSearchEmpSelfDeclarationData(){
		$postData = json_decode(file_get_contents('php://input'), true);
		$params =array();
		if(isset($postData['fromDate']) && !empty($postData['fromDate'])){
			$params['fromDate'] = trim($postData['fromDate']);
		}
		if(isset($postData['toDate']) && !empty($postData['toDate'])){
			$params['toDate'] = trim($postData['toDate']);
		}
		// $this->load->model('auth_model');
		$res ='';
		$res = $this->auth_model->getSelfDeclarationTodayData($params);
		if(isset($res) && !empty($res)){
			echo json_encode(array('status'=>true,'empSelfData'=>$res));
			exit;
		}
		else{
			echo json_encode(array('status'=>false,'empSelfData'=>''));
			exit;
		}
	}
	public function getTodayData(){
		if(!empty(getSessionData('login')['post_mobile'])){
			$params = array();
			$params['current_date'] = date('Y-m-d');
			$params['mobile'] = (int)getSessionData('login')['post_mobile'];
			// $this->load->model('auth_model');
			$todayData = $this->auth_model->getSelfDeclarationTodayData($params);
			$todayUserData = !empty($todayData) ? $todayData[0] : null;
			echo json_encode(array('success'=>true,'todayData'=>$todayUserData));
		}
		else if(!empty(getSessionData('login')['mobile'])){
			$params = array();
			$params['current_date'] = date('Y-m-d');
			$params['mobile'] = (int)getSessionData('login')['mobile'];
			// $this->load->model('auth_model');
			$todayData = $this->auth_model->getSelfDeclarationTodayData($params);
			$todayUserData = !empty($todayData) ? $todayData[0] : null;
			echo json_encode(array('success'=>true,'todayData'=>$todayUserData));
		}
	}

    public function welcomePage() {
		if(!isset($this->session->loginData)){
			$this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Session Expired. Please login again.</div>');
			unset($this->session->loginData);
			return redirect()->to(base_url('auth'));
		} else {
			$data = array();
			$data['page_title'] = "Dashboard";
			if (!empty(getSessionData('login')['post_mobile'])) {
				$params = array();
				$params['current_date'] = date('Y-m-d');
				$params['mobile'] = (int)getSessionData('login')['post_mobile'];
				// $this->load->model('auth_model');
				$res = $this->auth_model->getSelfDeclarationTodayData($params);
				$data['empData'] = !empty($res) ? $res[0] : NULL;
			}
			$this->load->view('physical_hearing/dashboard', $data);
		}
	}

}