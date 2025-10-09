<?php

namespace App\Models\Profile;

use CodeIgniter\Model;

class ProfileModel extends Model
{
    protected $table = 'efil.tbl_users';
    public function __construct()
    {
        parent::__construct();
    }

    public function getProfileDetail($userid)
    {
        $builder = $this->db->table('efil.tbl_users');
        $query = $builder->select('*')
            ->where('userid', $userid)
            ->get();

        if ($query->getNumRows() >= 1) {
            return $query->getRow(); 
        } else {
            return null; 
        }
    }

    public function getuserProfileDetail($userid)
    {
        $builder = $this->db->table('efil.tbl_users users');
        $builder->SELECT('*');
       $builder->WHERE('id', $userid);
        $builder->WHERE('users.is_active', 'TRUE');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    public function userLastLogin($userid)
    {
        $query = $this->db->table('efil.tbl_users_login_log log')
            ->select('log.login_time,log.ip_address')
            ->where('login_id', $userid)
            ->where('log.is_successful_login', 'TRUE')
            ->orderBy('log.log_id', 'DESC')
            ->limit(1, 1)
            ->get();
        $query->getRow();

        if ($query->getNumRows() >= 1) {
            $result = $query->getRow();
            return $result;
        } else {
            return false;
        }
    }

    public function deactivated_user_reason($userid)
    {
        $builder = $this->db->table('tbl_deactive_users');
        $builder->SELECT('*');
        // $builder->FROM();
        $builder->WHERE('user_id', $userid);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    public function selectEmail($userid)
    {
        $builder = $this->db->table('efil.tbl_users');
        $builder->SELECT('emailid');
        // $builder->FROM();
        $builder->WHERE('userid', $userid);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    public function selectContact($userid)
    {
        $builder = $this->db->table('efil.tbl_users');
        $builder->SELECT('moblie_number');
        // $builder->FROM();
        $builder->WHERE('userid', $userid);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    public function selectOtherContact($userid)
    {
        $builder = $this->db->table('efil.tbl_users');
        $builder->SELECT('other_contact_number');
        // $builder->FROM();
        $builder->WHERE('userid', $userid);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    public function selectPassword($userid)
    {
        $query = $this->db->table('efil.tbl_users')
            ->select('password')
            ->where('userid', $userid)
            ->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function get_user($usr, $pwd, $if_loggable = true, $if_match_password = true)
    {
        $builder = $this->db->table('efil.tbl_users users');
        $builder->SELECT("*");
        $builder->WHERE('users.userid', strtoupper($usr));
        $query = $builder->get();
        if ($query->getNextRow() == 1) {
            $res_array = $query->getResult(); 
            echo $res_array[0]->password;
            if (!$if_match_password || $res_array[0]->password . $_SESSION['login_salt'] == $pwd) { //$if_match_password for efiling_assistant
               
                if ($if_loggable) { //for efiling_assistant
                    $builder = $this->db->table('efil.tbl_users');
                    $builder->WHERE('id', $res_array[0]->id);
                    $builder->SET(array('login_ip' => getClientIP()));
                    $builder->UPDATE();
                }
                return $res_array;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function selectPasswordCheck($userid, $password = null)
    {
        $builder = $this->db->table('efil.tbl_users_password_history');
        $builder->select('*');
        $builder->where('userid', $userid);

        if (!empty($password)) {
            $builder->where('password', $password);
        }

        $builder->orderBy('id', 'DESC');
        $builder->limit(1);

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getRow();
        } else {
            return false;
        }
    }


    public function updatePasswordCheck($userid, $password)
    {
        // Get the previous password history
        $result = $this->selectPasswordCheck($userid);

        if ($result) {
            $pre_id = $result->id;
        }

        $date = new \DateTime();
        $date_time = $date->format('Y-m-d H:i:s');

        $data_create = [
            'password' => $password,
            'userid' => $_SESSION['login']['id'],
            'created_ip' => getClientIP(),
            'created_on' => $date_time,
            'status' => 1
        ];

        $data_update_pre = [
            'changed_ip' => getClientIP(),
            'changed_on' => $date_time,
            'status' => 0
        ];

        // Insert new password history
        $this->db->table('efil.tbl_users_password_history')->insert($data_create);

        // Update previous password history if it exists
        if (!empty($pre_id)) {
            $this->db->table('efil.tbl_users_password_history')
                ->where('id', $pre_id)
                ->where('userid', $userid)
                ->set($data_update_pre)
                ->update();
        }

        return true;
    }


    public function updatePassword($userid, $password)
    {
        $data = ['password' => $password];
        $result = $this->db->table('efil.tbl_users')
            ->where('userid', $userid)
            ->set($data)
            ->update();

        return $result !== false;
    }


    public function updateEmail($userid, $emailid)
    {
        $builder = $this->db->table('efil.tbl_users');
        // $builder->set();
        $builder->WHERE('userid', $userid);
        $builder->SET('emailid', $emailid);
        $result = $builder->UPDATE();
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function updateContact($userid, $moblie_number)
    {
        // $builder = $this->db->table('efil.tbl_users');
        // $builder->SELECT('*');
        // // $builder->FROM();
        // $builder->WHERE('moblie_number', $moblie_number);
        // $builder->WHERE("userid !='$userid'");
        // $query = $builder->get();
        // if (!$query->getNumRows()) {
        //     // $this->db->set();
        //     $builder->WHERE('userid', $userid);
        //     $builder->SET('moblie_number', $moblie_number);
        //     $result = $builder->UPDATE();
        //     if ($result) {
        //         return true;
        //     } else {
        //         return false;
        //     }
        // } else {
        //     return false;
        // }
        $builder = $this->db->table('efil.tbl_users');
        // Select and filter
        $builder->select('*')
            ->where('moblie_number', $moblie_number)
            ->where('userid !=', $userid);
        $query = $builder->get();
        if ($query->getNumRows() == 0) {
            // Update if the mobile number doesn't exist
            $data = ['moblie_number' => $moblie_number];
            $builder->where('userid', $userid)
                ->set($data)
                ->update();
            return $this->db->affectedRows() > 0;
        } else {
            return false;
        }
    }

    public function updateAddress($userid, $data)
    {
        $builder = $this->db->table('efil.tbl_users');
        // $this->db->set();
        $builder->WHERE('userid', $userid);
        $builder->SET($data);
        $result = $builder->UPDATE();
        if ($this->db->affectedRows()) {
            return $result;
        } else {
            return false;
        }
    }

    public function updateOtherContact($userid, $other_contact_number)
    {
        $builder = $this->db->table('efil.tbl_users');
        // $this->db->set();
        $builder->WHERE('userid', $userid);
        $builder->set('other_contact_number', $other_contact_number);
        $result = $builder->UPDATE();
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function updatePhoto($userid, $photo_data)
    {
        $builder = $this->db->table('efil.tbl_users');
        $builder->select('id');
        $builder->WHERE('userid', $userid);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getRow();
            $builder = $this->db->table('efil.tbl_users');
            // $this->db->set();
            $builder->WHERE('id', $result->id);
            $builder->set('photo_path', $photo_data);
            $res = $builder->update();
            if ($res) {
                return $res;
            } else {
                return false;
            }
        } else {
            return false;
        }
        
    }

    function deactive_adminuser($id)
    {
        $builder = $this->db->table('efil.tbl_users');
        // $this->db->set();
        $builder->WHERE('id', $id);
        $builder->SET('is_active', FALSE);
        $result = $builder->UPDATE();
        return true;
    }

    function activate_adminuser($id)
    {
        $builder = $this->db->table('efil.tbl_users');
        // $this->db->SET();
        $builder->WHERE('id', $id);
        $builder->SET('is_active', TRUE);
        $result = $builder->UPDATE();
        return true;
    }

    function add_deactive_adminuser($data)
    {
        $builder = $this->db->table('tbl_deactive_users');
        $builder->insert($data);
        return true;
    }

    public function getdectiveuserProfileDetail($userid)
    {
        $builder = $this->db->table('efil.tbl_users');
        $builder->SELECT('*');
        // $builder->FROM();
        $builder->WHERE('is_active', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    public function new_registered_user($newORrejected = FALSE)
    {
        $admin_for_id = $_SESSION['login']['admin_for_id'];
        $builder = $this->db->table('efil.tbl_users users');
        $builder->SELECT('*');
        // $builder->FROM();
        $builder->WHERE('users.is_active', FALSE);
        $builder->WHERE('users.is_account_active', TRUE);
        if ($newORrejected == 'rejected') {
            $builder->like('users.moblie_number', 'NULL');
        } else {
            $builder->notLike('users.moblie_number', 'NULL');
        }
        if ($_SESSION['login']['admin_for_type_id'] == ENTRY_TYPE_FOR_HIGHCOURT) {
            $builder->WHERE("(users.high_court_id= '$admin_for_id' OR users.bench_court = '$admin_for_id')");
        } else {
            $builder->WHERE('users.enrolled_establishment_id', $admin_for_id);
        }
        $builder->WHERE('users.ref_m_usertype_id not in (' . USER_ADMIN . ',' . USER_SUPER_ADMIN . ',' . USER_MASTER_ADMIN . ',' . USER_ACTION_ADMIN . ')');
        $builder->orderBy('users.first_name', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    public function new_active_user()
    {
        $admin_for_id = $_SESSION['login']['admin_for_id'];
        $builder = $this->db->table('efil.tbl_users users');
        $builder->SELECT('*');
        // $builder->FROM();
        if ($_SESSION['login']['admin_for_type_id'] == ENTRY_TYPE_FOR_HIGHCOURT) {
            $builder->where("(users.high_court_id= '$admin_for_id' OR users.bench_court = '$admin_for_id')");
        } else {
            $builder->WHERE('users.enrolled_establishment_id', $_SESSION['login']['admin_for_id']);
        }
        $builder->WHERE('users.ref_m_usertype_id not in (' . USER_ADMIN . ',' . USER_SUPER_ADMIN . ',' . USER_MASTER_ADMIN . ',' . USER_ACTION_ADMIN . ')');
        $builder->WHERE('users.is_active', TRUE);
        $builder->orderBy('users.first_name', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    public function updateProfilePic($id, $photo)
    {
        $builder = $this->db->table('efil.tbl_users');
        $builder->WHERE('id', $id);
        $builder->SET($photo);
        $result = $builder->UPDATE();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function estab_already_added_profile($id)
    {
        $builder = $this->db->table('tbl_advocate_establishments');
        $builder->SELECT('estab_code');
        // $builder->FROM();
        $builder->WHERE('adv_user_id', $id);
        $query = $builder->get();
        if ($query->getNumRows() >= 0) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    function update_photo($user_id, $profile_photo, $id_proof, $bar_reg_certificate)
    {
        $account_status = array(
            'photo_path' => $profile_photo
        );
        $builder = $this->db->table('efil.tbl_users');
        $builder->WHERE('id', $user_id);
        $builder->SET($account_status);
        $builder->UPDATE();
        if ($this->db->affectedRows()) {
            return true;
        } else {
            return false;
        }
    }

    function getloggedDetail($user_id)
    {
        $sql = "select ip_address,login_id,user_agent,login_time,block from ( 
                SELECT ip_address,login_id, user_agent,login_time,block,ROW_NUMBER()
                OVER (PARTITION BY ip_address,login_id,user_agent ORDER BY login_time DESC) 
                AS latest_login FROM efil.tbl_users_login_log)z where latest_login=1 and login_id=" . $user_id . " order by ip_address,login_id,user_agent,login_time";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return FALSE;
        }
    }

    function change_block_status($userid, $ip, $status)
    {
        $builder = $this->db->table('efil.tbl_users_login_log');
        if ($status == 'Unblock') {
            $data = array('block' => FALSE);
            $builder->WHERE('login_id', $userid);
            $builder->WHERE('ip_address', $ip);
            $builder->SET($data);
            $builder->UPDATE();
            return true;
        } else {
            $data = array('block' => TRUE);
            $builder->WHERE('login_id', $userid);
            $builder->WHERE('ip_address', $ip);
            $builder->SET($data);
            $builder->UPDATE();
            return true;
        }
    }

    function getLoginLogDetails($data)
    {
        $query = $this->db->table('efil.tbl_users tu')
            ->select('tu.moblie_number, tu.emailid, tu.adv_sci_bar_id, tull.is_successful_login, tull.ip_address, tull.login_time')
            ->join('efil.tbl_users_login_log tull', 'tull.login_id = tu.adv_sci_bar_id', 'left');
        if ($data['mobile']) {
            $query->where('tu.moblie_number', $data['mobile']);
        }
        if ($data['email']) {
            $query->where('tu.emailid', $data['email']);
        }
        if ($data['loginDate']) {
            $loginTime = date('Y-m-d', strtotime($data['loginDate']));
            $query->where('tull.login_time >=', $loginTime . ' 00:00:00');
            $query->where('tull.login_time <=', $loginTime . ' 23:59:59');
        }
        $query->where('tu.ref_m_usertype_id', 1);
        $query->where('tull.is_successful_login', TRUE);
        $result = $query->get();
        return $result->getResult();
    }
}