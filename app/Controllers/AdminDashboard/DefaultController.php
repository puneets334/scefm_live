<?php

namespace App\Controllers\AdminDashboard;

use App\Controllers\BaseController;
use App\Models\AdminDashboard\AdminDashboardModel;
use App\Models\Report\ReportModel;

class DefaultController extends BaseController {

    protected $AdminDashboard_model;
    protected $Report_model;
	protected $session;

    public function __construct() {
        parent::__construct();
        if (empty($this->session->get('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $this->AdminDashboard_model = new AdminDashboardModel();
        $this->Report_model = new ReportModel();
        $this->session = \Config\Services::session();
    }

    public function index() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $users_array = array(USER_ADMIN,USER_ADMIN_READ_ONLY,USER_EFILING_ADMIN);
        $users_read_array = array(USER_ADMIN_READ_ONLY,USER_EFILING_ADMIN);
		if($this->session->get('login')) {
			if (in_array($this->session->get('login')['ref_m_usertype_id'], $users_array)) {
				if (in_array($this->session->get('login')['ref_m_usertype_id'], $users_read_array)) {
					$AllUserCount =1;
					$AllUserCountData=array_merge($this->session->get('login'),array('AllUserCount' =>$AllUserCount));					 
					$this->session->set(array('login' => $AllUserCountData));				 
				} else {
					$AllUserCount =0;
					$AllUserCountData=array_merge($this->session->get('login'),array('AllUserCount' =>$AllUserCount));					 
					$this->session->set(array('login' => $AllUserCountData));
				}				
				$data['count_efiling_data'] = $this->AdminDashboard_model->get_efilied_nums_stage_wise_count();
				$data['stage_list'] = $this->Report_model->get_stage();				 
				return $this->render('adminDashboard.admin_dashboard_view', $data);	
			} else {
				return response()->redirect(base_url('/'));
				exit(0);
			}
		} else {
            return response()->redirect(base_url('/'));
            exit(0);
        }
    }

    public function ActionToAllUserCount() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $users_array = array(USER_ADMIN);
        if (in_array($this->session->get('login')['ref_m_usertype_id'], $users_array)) {
            $AllUserCount =0;
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $AllUserCount=trim($_POST("AllUserCount"));
                $AllUserCount =  !empty($AllUserCount) && ($AllUserCount=='true') ? 1 : 0;
            }
            $AllUserCountData=array_merge($this->session->get('login'),array('AllUserCount' =>$AllUserCount));
            $this->session->setFlashData(array('login' => $AllUserCountData));
            return response()->redirect('adminDashboard');
        } else {
            return response()->redirect(base_url('/'));
            exit(0);
        }
    }

}