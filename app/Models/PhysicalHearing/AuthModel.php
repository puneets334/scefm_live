<?php
// if ( ! defined('BASEPATH')) exit('No direct script access allowed');

namespace App\Models\PhysicalHearing;

use CodeIgniter\Model;

class AuthModel extends Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $db = \Config\Database::connect();
    }

    function get_user($aor_code) {
        $sql = "select bar_id, title, name, mobile, email,paddress,aor_code from bar where aor_code=? and if_aor='Y' and isdead='N'";
        $query = $this->db->query($sql, array($aor_code));
        if($query -> num_rows() == 1){
            return $query->row_array();
        } else {
            return false;
        }
    }

	function getSelfDeclarationData($params=array()) {
		$output= false;
		$physical_hearing = $this->load->database('physical_hearing',TRUE);
		$physical_hearing->select('id,name,email_id,mobile,ref_attendee_type_id,display,created_on,next_dt,ref_attendee_type_id');
		if(isset($params['current_date']) && !empty($params['current_date']) && isset($params['mobile']) && !empty($params['mobile'])){
			$physical_hearing->where('DATE(next_dt)',date('Y-m-d',strtotime($params['current_date'])));
            // $physical_hearing->where('DATE(next_dt)',date('Y-m-d',strtotime('03-02-2020')));
			$physical_hearing->where('mobile',$params['mobile']);
			$physical_hearing->where('display','Y');
			$physical_hearing->from('attendee_details');
			$query = $physical_hearing->get();
			// echo $physical_hearing->last_query();
			$output =  $query->result_array();
		}
		return $output;
	}

	public function getSelfDeclarationTodayData($params) {
		$output= false;
		$physical_hearing = $this->load->database('physical_hearing',TRUE);
		if(isset($params['current_date']) && !empty($params['current_date']) && isset($params['mobile']) && !empty($params['mobile'])) {
			$physical_hearing->select('*');
			$physical_hearing->from('emp_self_declarion');
			$physical_hearing->where('DATE(for_date)',date('Y-m-d',strtotime($params['current_date'])));
			$physical_hearing->where('mobile',$params['mobile']);
			$physical_hearing->order_by('id','DESC');
			$physical_hearing->LIMIT(1);
			$query = $physical_hearing->get();
			$output =  $query->result_array();
		} else if(isset($params['fromDate']) && !empty($params['fromDate']) && isset($params['toDate']) && !empty($params['toDate'])) {
			$fromDate = date('Y-m-d',strtotime($params['fromDate']));
			$toDate = date('Y-m-d',strtotime($params['toDate']));
			$physical_hearing->select("esd.*,rat.description");
			$physical_hearing->from("emp_self_declarion as esd");
			$physical_hearing->join("ref_attendee_type as rat" ,"esd.category=rat.id","inner");
			$physical_hearing->where("DATE(esd.for_date) between '$fromDate' and '$toDate'");
			$physical_hearing->order_by("esd.id","DESC");
			$query = $physical_hearing->get();
			$output =  $query->result_array();
		} else if(isset($params['fromDate']) && !empty($params['fromDate'])) {
			$fromDate = date('Y-m-d',strtotime($params['fromDate']));
			$physical_hearing->select("esd.*,rat.description");
			$physical_hearing->from("emp_self_declarion as esd");
			$physical_hearing->join("ref_attendee_type as rat" ,"esd.category=rat.id","inner");
			$physical_hearing->where('DATE(esd.for_date) >=',date('Y-m-d',strtotime($params['fromDate'])));
			$physical_hearing->order_by("esd.id","DESC");
			$query = $physical_hearing->get();
			$output =  $query->result_array();
		} else if(isset($params['current_date']) && !empty($params['current_date'])) {
			$physical_hearing->select('esd.*,rat.description');
			$physical_hearing->from('emp_self_declarion as esd');
			$physical_hearing->join('ref_attendee_type as rat' ,'esd.category=rat.id','inner');
			$physical_hearing->where('DATE(esd.for_date)',date('Y-m-d',strtotime($params['current_date'])));
			$physical_hearing->order_by('esd.id','DESC');
			$query = $physical_hearing->get();
			$output =  $query->result_array();
		}
		return $output;
	}

}