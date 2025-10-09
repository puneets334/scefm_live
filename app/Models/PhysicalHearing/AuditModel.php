<?php
// if ( ! defined('BASEPATH')) exit('No direct script access allowed');

namespace App\Models\PhysicalHearing;

use CodeIgniter\Model;
class AuditModel extends Model
{

    function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
		// $this->load->database(); // Load default database
		// $this->db = $this->load->database('physical_hearing', TRUE);
    }

	public function log($action,$user_id, $data = null) {
		// Ensure the default database is being used
		/*$this->db->SELECT('*');
		$this->db->FROM('audit_trail');
		$query = $this->db->get();
		if ($query->num_rows() >= 1) {
			$result= $query->result();
		} else {
			$result= array();
		}
		echo '<pre>';print_r($result);exit();*/
    	//echo 'ANshu Audit_model';exit();
		$from_ip = get_client_ip();
		$builder = $this->db->table('audit_trail');
		$response = $builder->insert(array(
			'action' => $action,
			'user_id' => $user_id,
			'from_ip' => $from_ip,
			'log_data' => json_encode($data)
		));
		return $response;
	}
	//XXXXXXXXXXXXXXXXXXXXX work start Anshu Security Audit XXXXXXXXXXXXXXXXXXXXXXXXXXXX
	//Start Same user logged in in multiple browser

	function is_user_status($id,$ref_m_usertype_id=null) {
		$builder = $this->db->table('tbl_users_login_log');
		$builder->SELECT('*');
		$builder->WHERE('login_id', $id);
		#$builder->WHERE('ip_address', getClientIP());
		$builder->WHERE('is_successful_login', true);
		$builder->LIMIT(1);
		$builder->orderBy("log_id", "DESC");
		$query = $builder->get();
		/* echo $this->db->last_query(); exit();*/
		if ($query->getNumRows() >= 1) {
			return $query->getResult();
		} else {
			return FALSE;
		}
	}

	public function logUser($action, $data) {
		if ($action == 'login') {
			$builder = $this->db->table('tbl_users_login_log');
			$builder->insert($data);
			// $this->db->last_query();//die;
			$insert_id = $this->db->insertID();
			$session_data = $this->session->userdata('loginData');
			$session_data['log_id'] = $insert_id;
			$this->session->set_userdata("loginData", $session_data);
			return true;
		} elseif ($action == 'logout') {
			$builder = $this->db->table('tbl_users_login_log');
			$builder->set('logout_time', $data['logout_time']);
			$builder->WHERE('log_id', $data['log_id']);
			$builder->UPDATE();
			return true;
		}
	}
	//End Same user logged in in multiple browser

	public function get_user_block_dtl($id) {
		$builder = $this->db->table('tbl_user_failure_login_log');
		$builder->SELECT('*');
		$builder->WHERE('login_id', $id);
		$builder->WHERE('is_valid', 'T');
		$query = $builder->get();
		/* echo $this->db->last_query(); exit();*/
		if ($query->getNumRows() >= 1) {
			return $query->getResult();
		} else {
			return FALSE;
		}
	}
	//End of function get_user_block_dtl ..

	public function get_failure_user_details($username) {
		$builder = $this->db->table('users');
		$builder->SELECT('id');
		$builder->WHERE('LOWER(userid)', strtolower($username));
		$query = $builder->get();
		// echo $this->db->last_query(); exit();
		if ($query->getNumRows() >= 1) {
			foreach ($query->getResult() as $row) {
				$login_id= $row->id;
			}
			// echo $login_id;
			$builder1 = $this->db->table('tbl_user_failure_login_log');
			$builder1->SELECT('*');
			$builder1->WHERE('login_id', $login_id);
			$builder1->WHERE('is_valid', 'T');
			$query = $builder1->get();
			// echo $this->db->last_query();
			if ($query->getNumRows() >= 1){
				foreach ($query->getResult() as $row) {
					$failure_attmpt= $row->failure_no_attmpt;
					$block_user=$row->block;
				}
				$failure_attmpt=$failure_attmpt+1;
				if($block_user=='T') {
					return 1;
				} else {
					$data['login_id'] = $login_id;
					$data['failure_no_attmpt'] = $failure_attmpt;
					$data['ip_address'] = getClientIP();
					$data['login_time'] = date('Y-m-d H:i:s');
					$data['is_valid']='T';
					$data['block']='F';
					if($failure_attmpt==3){
						$data['block']='T';
					}
					$builder2 = $this->db->table('tbl_user_failure_login_log');
					$builder2->where('login_id', $login_id);
					$builder2->where('is_valid', 'T');
					$builder2->set($data);
					$builder2->update();
				}
			} else {
				$data['login_id'] = $login_id;
				$data['failure_no_attmpt'] = 1;
				$data['ip_address'] = getClientIP();
				$data['login_time'] = date('Y-m-d H:i:s');
				$data['is_valid']='T';
				$data['block']='F';
				/*print_r($data);
				exit();*/
				$builder3 = $this->db->table('tbl_user_failure_login_log');
				$builder3->INSERT($data);
			}
			return $query->getResult();
		} else {
			return FALSE;
		}
	}
	//End of Function get_failure_user_details..

	public function get_user_block_dtl_update($login_id) {
		$data['login_id'] = $login_id;
		$data['failure_no_attmpt'] = 0;
		$data['ip_address'] = getClientIP();
		$data['login_time'] = date('Y-m-d H:i:s');
		$data['is_valid']='F';
		$data['block']='F';
		$builder = $this->db->table('tbl_user_failure_login_log');
		$builder->where('login_id', $login_id);
		$builder->WHERE('is_valid', 'T');
		$builder->SET($data);
		$builder->update();
	}
	//End of function get_user_block_dtl ..

	//Start Password History is not maintained
	public function selectPasswordCheck($userid,$password=null) {
		$builder = $this->db->table('tbl_users_password_history');
		$builder->SELECT('*');
		$builder->WHERE('userid', $userid);
		if (!empty($password) && $password !=null){
			$builder->WHERE('password',md5($password));
		}
		$builder->orderBy('id', 'DESC');
		$builder->LIMIT(1);
		$query = $builder->get();//echo $this->db->last_query();exit();
		if ($query->getNumRows() >= 1) {
			$res_array = $query->getResult();
			return $res_array;
		} else {
			return false;
		}
	}

	public function updatePasswordCheck($userid, $password) {
		$result = $this->selectPasswordCheck($userid);
		// $date = new DateTime();
		// $date_time = $date->format('Y-m-d H:i:s');
		$date_time = date('Y-m-d H:i:s');
		$data_create = array(
			'password' => md5($password),
			'userid' => $_SESSION['login']['user_id'],
			'created_ip' => getClientIP(),
			'created_on' => $date_time,
			'status' => 1
		);
		$data_update_pre= array(
			'changed_ip' => getClientIP(),
			'changed_on' => $date_time,
			'status' =>0
		);
		$builder = $this->db->table('tbl_users_password_history');
		$builder->INSERT($data_create);
		if (!empty($result) && $result !=null){
			$pre_id = $result['id'];
			$pre_userid = $result['userid'];
			$builder->WHERE('id', $pre_id);
			$builder->WHERE('userid', $userid);
			$builder->SET($data_update_pre);
			$result = $builder->UPDATE();
			if ($result) {
				return $result;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	//End Password History is not maintained
	//SMS & EMAIL OTP FLOODING

	public function insert_efiling_sms_email_dtl($sms_details)
	{
		$builder = $this->db->table('physical_hearing.otp_requests');
		$builder->INSERT($sms_details);
		if ($this->db->insertID()) {
			return true;
		} else{
			return false;
		}
	}

	public function check_efiling_sms_email_log($to_email,$request_time_time_period,$ip_address)
	{
		$builder = $this->db->table('physical_hearing.otp_requests');
		$builder->where('ip_address', $ip_address);
		$builder->where('email', $to_email);
		$builder->where('request_time >', $request_time_time_period);
		$request_count = $builder->countAllResults();
		return $request_count;
	}

	public	function check_efiling_sms_mobile_no_log($mobile_no,$request_time_time_period,$ip_address)
	{
		$builder = $this->db->table('physical_hearing.otp_requests');
		$builder->where('ip_address', $ip_address);
		$builder->where('mobile_no', $mobile_no);
		$builder->where('request_time >', $request_time_time_period);
		$request_count = $builder->countAllResults();
		return $request_count;
	}

	//END SMS & EMAIL OTP FLOODING
	//XXXXXXXXXXXXXXXXXXXXX work End XXXXXXXXXXXXXXXXXXXXXXXXXXXX
}