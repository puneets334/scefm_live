<?php

namespace App\Models\login;
use CodeIgniter\Model;
use DateTime;

class LoginModel extends Model {

    protected $session;

    function __construct() {
        parent::__construct();
        $db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    function get_user($usr, $pwd, $if_loggable = true, $if_match_password = true) {
        $userid = strtoupper($usr);
        $sql="SELECT users.*, est.pg_request_function, est.pg_response_function, est.estab_code,tut.user_type
            FROM efil.tbl_users users 
            JOIN efil.m_tbl_establishments est ON 1 = 1
            LEFT JOIN efil.tbl_user_types tut ON tut.id=users.ref_m_usertype_id
            WHERE (upper(users.userid) = ? OR users.moblie_number = ?  OR users.emailid ilike ?)
            AND users.is_deleted = 'false' AND users.is_active = '1' AND tut.is_deleted = false ORDER by users.ref_m_usertype_id";
        $query =$this->db->query($sql, array($userid, $usr, $usr));
        if ($query->getNumRows() == 1) {
            $res_array = $query->getResult();
            if (isset($_SESSION['login_salt']) && !empty($_SESSION['login_salt'])) {
                if(!$if_match_password || $res_array[0]->password.$_SESSION['login_salt'] == $pwd) {
                    if($if_loggable) {
                        if ($res_array[0]->ref_m_usertype_id==USER_CLERK){
                            is_clerk_aor($res_array[0]->id,$res_array[0]->ref_m_usertype_id);
                        }
                        $builder = $this->db->table('efil.tbl_users');
                        $builder->WHERE('id', $res_array[0]->id);
                        $builder->UPDATE(array('login_ip' => getClientIP()));
                    }
                    return $res_array;
                } else { 
                    return false;
                }
            } else { 
                return false;
            }
        } else if ($query->getNumRows() > 1) {
            $res_array = $query->getResult();
            if (!$if_match_password || $res_array[0]->password.$_SESSION['login_salt'] == $pwd) {
                if($if_loggable) {
                    if ($res_array[0]->ref_m_usertype_id==USER_CLERK){
                        is_clerk_aor($res_array[0]->id,$res_array[0]->ref_m_usertype_id);
                    }
                    $builder = $this->db->table('efil.tbl_users');
                    $builder->WHERE('id', $res_array[0]->id);
                    $builder->UPDATE(array('login_ip' => getClientIP()));
                }
                return $res_array;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function check_otp($usr, $pwd, $otp) {
        $userid = strtoupper($usr);
        $builder = $this->db->table('efil.tbl_users as users');
        $builder->select('users.*, est.pg_request_function, est.pg_response_function, est.estab_code');
        $builder->join('efil.m_tbl_establishments est', '1 = 1');
        $builder->where('users.userid', $userid);
        $builder->orWhere('users.moblie_number', $usr);
        $builder->orWhere('users.emailid', $usr);
        $builder->where('users.mobile_otp', $otp);
        $query = $builder->get();
        $result = $query->getRow();
        if ($query->getNumRows() == 1) {
            $res_array = $query->getFirstRow();
            if ($res_array->password . $_SESSION['login_salt'] == $pwd) {
                return $res_array;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function storeOtp($usr, $pwd, $otp) {
        $userid = strtoupper($usr);
        $builder = $this->db->table('efil.tbl_users as users');
        $builder->select('users.*, est.pg_request_function, est.pg_response_function, est.estab_code');
        $builder->join('efil.m_tbl_establishments est', '1 = 1');
        $builder->where('users.userid', $userid);
        $builder->orWhere('users.moblie_number', $usr);
        $builder->orWhere('users.emailid', $usr);
        $builder->where('users.mobile_otp', $otp);
        $query = $builder->get();
        $result = $query->getRow();
        if ($query->getNumRows() == 1) {
            $res_array = $query->getFirstRow();
            if ($res_array->password . $_SESSION['login_salt'] == $pwd) {
                $builder = $this->db->table('efil.tbl_users');
                $builder->where('userid', $userid);
                $builder->orWhere('moblie_number', $usr);
                $builder->orWhere('emailid', $usr);
                $builder->update(array('mobile_otp' => $otp));
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function logUser($action, $data) {
        if ($action == 'login') {
            $sessionData = $this->session->get('login');
            $builder = $this->db->table('efil.tbl_users_login_log');
            $builder->insert($data);
            $insert_id = $this->db->insertID();
            $sessionData['log_id'] = $insert_id;
            $sessionData['processid'] = getmypid();
            $this->session->set("login", $sessionData);
            return true;
        } elseif ($action == 'logout') {           
            $builder = $this->db->table('efil.tbl_users_login_log');
            $builder->where('log_id', $data['log_id']);
            $builder->update(array('logout_time' => $data['logout_time']));
            return true;
        }
    }

    function get_state_name($state_id) {
        $builder = $this->db->table('m_tbl_state');
        $builder->SELECT('state');
        $builder->WHERE('state_id', $state_id);
        return $builder->get()->getRow()->state;
    }

    function check_user_mobile_number($mobile_number) {
        $builder = $this->db->table('users');
        $builder->SELECT('userid, moblie_number');
        $builder->WHERE('moblie_number', $mobile_number);
        $builder->orWhere('userid ', $mobile_number);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function check_user_mobile_number_user_alredy($mobile_number) {
        $builder = $this->db->table('users');
        $builder->select('userid, moblie_number');
        $builder->where('moblie_number', $mobile_number);
        $builder->where('userid !=', $mobile_number);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return true;
        } else {
            return false;
        }
    }

    function get_user_login_log_details($id) {
        $builder = $this->db->table('efil.tbl_users_login_log');
        $builder->where('login_id', $id);
        $builder->where('ip_address', getClientIP());
        $query = $builder->get();
        $result = $query->getRowArray();

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_user_login_log_details_with_user_agent($id) {
        $builder = $this->db->table('efil.tbl_users_login_log');
        $builder->select('*')
            ->where('login_id', $id)
            ->where('logout_time IS NULL', null, false)
            ->where("TO_CHAR(NOW()::DATE, 'dd-mm-yyyy') = TO_CHAR(login_time::DATE, 'dd-mm-yyyy')", null, false)
            ->orderBy('login_time', 'DESC')
            ->limit(1);        
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }
    
    public function is_user_status($id, $ref_m_usertype_id = null) {
        $builder = $this->db->table('efil.tbl_users_login_log');
        $query = $builder->select('*')
            ->where('login_id', $id)
            ->where('is_successful_login', true)
            ->orderBy('log_id', 'DESC')
            ->limit(1)
            ->get();
        if ($query->getRow()) {
            return $query->getRow();
        } else {
            return false;
        }
    }
    
    function get_failure_user_details($username) {
        $builder = $this->db->table('efil.tbl_users');
        $query = $builder->select('id')
            ->where('LOWER(userid)', strtolower($username))
            ->get();
        if ($query->getNumRows() >= 1) {
            $login_id = $query->getRow()->id;
            $builder = $this->db->table('efil.tbl_user_failure_login_log');
            $query = $builder->select('*')
                ->where('login_id', $login_id)
                ->where('is_valid', 'T')
                ->get();
            if ($query->getNumRows() >= 1) {
                $row = $query->getRow();
                $failure_attmpt = $row->failure_no_attmpt;
                $block_user = $row->block;
                $failure_attmpt++;
                $date1 = new DateTime($row->login_time);
                    $date2 = new DateTime(date('Y-m-d H:i:s'));
                    $interval = $date1->diff($date2);
                if ($interval->i < 15 && $block_user == 'T') {
                    return 1;
                } else {
                    $data = [
                        'login_id' => $login_id,
                        'failure_no_attmpt' => $failure_attmpt,
                        'ip_address' => getClientIP(),
                        'login_time' => date('Y-m-d H:i:s'),
                        'is_valid' => 'T',
                        'block' => ($failure_attmpt == 5) ? 'T' : 'F'
                    ];
                    $builder->where('login_id', $login_id)
                        ->where('is_valid', 'T')
                        ->update($data);
                }
            } else {
                $data = [
                    'login_id' => $login_id,
                    'failure_no_attmpt' => 1,
                    'ip_address' => getClientIP(),
                    'login_time' => date('Y-m-d H:i:s'),
                    'is_valid' => 'T',
                    'block' => 'F'
                ];
                $builder->insert($data);
            }
            return $query->getResult();
        } else {
            return false;
        }
    }
    
    function get_user_block_dtl($id) {
        $builder = $this->db->table('efil.tbl_user_failure_login_log');
        $query = $builder->select('*')
            ->where('login_id', $id)
            ->where('is_valid', 'T')
            ->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }
    
    function get_user_block_dtl_update($login_id) {
        $builder = $this->db->table('efil.tbl_user_failure_login_log');
        $data = [
            'login_id' => $login_id,
            'failure_no_attmpt' => 0,
            'ip_address' => getClientIP(),
            'login_time' => date('Y-m-d H:i:s'),
            'is_valid' => 'F',
            'block' => 'F'
        ];
        $builder->where('login_id', $login_id)
            ->where('is_valid', 'T')
            ->update($data);
    }
    
    function isNewUser($usr) {
        $builder = $this->db->table('efil.tbl_users as users');
        $builder->select('users.*');     
        $builder->where('users.is_first_pwd_reset', true);
        $builder->groupStart()
        ->orWhere('users.userid', $usr)
        ->orWhere('users.moblie_number', $usr)
        ->orWhere('users.emailid', $usr) 
        ->orWhere('users.emailid', strtoupper($usr));
        $builder->groupEnd();
        $query = $builder->get();
        $result = $query->getRow();
        if ($query->getNumRows() == 1) {          
            return true;
        } else {           
            return false;
        }
    }

    function get_user_for_ecopy($aor_code,$aor_mobile) {
        $userid = strtoupper($aor_code);
        $sql="SELECT users.*, est.pg_request_function, est.pg_response_function, est.estab_code,tut.user_type
            FROM efil.tbl_users users 
            JOIN efil.m_tbl_establishments est ON 1 = 1
            LEFT JOIN efil.tbl_user_types tut ON tut.id=users.ref_m_usertype_id
            WHERE (upper(users.userid) = ? OR users.moblie_number = ?  OR users.emailid ilike ?)
            AND users.is_deleted = 'false' AND users.is_active = '1' AND tut.is_deleted = false ORDER by users.ref_m_usertype_id";
            if(!empty($userid)){
                $query =$this->db->query($sql, array($userid, $userid, $userid));
            }elseif(!empty($aor_mobile)){
                $query =$this->db->query($sql, array($aor_mobile, $aor_mobile, $aor_mobile));
            }
        if ($query->getNumRows() >= 1) {
            return $query->getRow();
        } else {
            return false;
        }
    }

    function check_user($usr, $pwd, $if_match_password = true) {
        $userid = strtoupper($usr);
        $sql="SELECT *
            FROM efil.tbl_users 
            WHERE (upper(userid) = ? OR moblie_number = ?  OR emailid ilike ?) ORDER by ref_m_usertype_id";
        $query =$this->db->query($sql, array($userid, $usr, $usr));
        if ($query->getNumRows() == 1) {
            $res_array = $query->getResult();
            if (!empty($res_array) && isset($_SESSION['login_salt'])) {
                if(!$if_match_password || $res_array[0]->password.$_SESSION['login_salt'] == $pwd) {
                    return $res_array;
                } else { 
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}