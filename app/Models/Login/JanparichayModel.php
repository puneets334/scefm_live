<?php

namespace App\Models\Login;

use CodeIgniter\Model;
use DateTime;

class JanparichayModel extends Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_user($usr, $if_loggable = true, $if_match_password = true)
    {
        $userid = strtoupper($usr);
        $sql = "SELECT users.*, est.pg_request_function, est.pg_response_function, est.estab_code,tut.user_type
            FROM efil.tbl_users users 
            JOIN efil.m_tbl_establishments est ON 1 = 1
            LEFT JOIN efil.tbl_user_types tut ON tut.id=users.ref_m_usertype_id
            WHERE (upper(users.userid) = ? OR users.moblie_number = ?  OR users.emailid ilike ?)
            AND users.is_deleted = 'false' AND users.is_active = '1' AND tut.is_deleted = false ORDER by users.id";
        $query = $this->db->query($sql, array("$userid", "$usr", "$usr"));
        // $res_array = $query->result(); echo "<pre>"; print_r($res_array); die;
        if ($query->getNumRows() >= 1) {
            $res_array = $query->getResult();
            if ($if_loggable) {
                $this->db->table('efil.tbl_users')->set(array('login_ip' => getClientIP()))->where('id', $res_array[0]->id)->update();
            }
            return $res_array;
        } else {
            return false;
        }
    }

    public function logUser($action, $data)
    {
        if ($action == 'login') {
            $this->db->table('efil.tbl_users_login_log')->insert($data);
            $insert_id = $this->db->insertID();
            $session_data = getSessionData('login');
            $session_data['log_id'] = $insert_id;
            setSessionData("login", $session_data);
            return true;
        } elseif ($action == 'logout') {
            $this->db->table('efil.tbl_users_login_log')->set('logout_time', $data['logout_time'])->where('log_id', $data['log_id'])->update();
            return true;
        }
    }

    function get_user_login_log_details($id)
    {
        $builder = $this->db->table('efil.tbl_users_login_log');
        $builder->select('*');
        $builder->where('login_id', $id);
        // $builder->where('ip_address', getClientIP());
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return FALSE;
        }
    }

    public function add_new_scefm_user($data)
    {
        $this->db->table('efil.tbl_users')->insert($data);
        if ($this->db->insertID()) {
            return $this->db->insertID();
        } else {
            return false;
        }
    }

    public function save_janparichay($data)
    {
        $this->db->table('efil.janparichay')->insert($data);
        if ($this->db->insertID()) {
            return $this->db->insertID();
        } else {
            return false;
        }
    }

    function is_janparichay_user_status($tbl_users_id = null)
    {
        $builder = $this->db->table('efil.janparichay');
        $builder->select('*');
        if (isset($tbl_users_id) && !empty($tbl_users_id)) {
            $builder->where('tbl_users_id', $tbl_users_id);
        }
        $builder->where('created_by_ip', getClientIP());
        $builder->where('LOWER(status)', 'success');
        $builder->where('date(created_on)', date("Y-m-d"));
        $builder->limit(1);
        $builder->orderBy("id", "DESC");
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray()[0];
        } else {
            return FALSE;
        }
    }
}
