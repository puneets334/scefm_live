<?php

namespace App\Models\Register;

use CodeIgniter\Model;

class RegisterModel extends Model
{
    protected $table = 'tbl_users';
    protected $primaryKey = 'id';

    function __construct()
    {
        parent::__construct();
    }

    public function get_state_list()
    {
        $sql = "SELECT state_code, name AS state_name 
                FROM icmis.state 
                WHERE state_code != 0 AND district_code = 0 AND sub_dist_code = 0 
                AND village_code = 0 AND sci_state_id != 0 AND display = 'Y' 
                ORDER BY name";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_district_list($state_code)
    {
        $sql = "SELECT district_code, name AS district_name
                FROM icmis.state 
                WHERE state_code = ? AND district_code != 0 AND sub_dist_code = 0 
                AND village_code = 0 AND display = 'Y'
                ORDER BY name";
        $query = $this->db->query($sql, [$state_code]);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    public function add_new_advocate_details($data)
    {
        $builder = $this->db->table('efil.tbl_users');
        $builder->insert($data);
        if ($this->db->insertID()) {
            return $this->db->insertID();
        } else {
            return false;
        }
    }

    // public function check_already_reg_mobile($mobile,$withoutApprovedByAOR=null)
    // {
    //     $builder = $this->db->table('efil.tbl_users');
    //     $builder->select('moblie_number, first_name, last_name');
    //     $builder->where('moblie_number', $mobile);
    //     if (!empty($withoutApprovedByAOR) && $withoutApprovedByAOR !=null && $withoutApprovedByAOR=='Y') {
    //         $builder->WHERE('is_active','0');
    //     } else{
    //         $builder->WHERE('is_active','1');
    //     }
    //     $query = $builder->get();

    //     if ($query->getNumRows() >= 1) {
    //         return $query->getResultArray();
    //     } else {
    //         return false;
    //     }
    // }

    function check_already_reg_mobile($mobile,$withoutApprovedByAOR=null)
    {
        $builder = $this->db->table('efil.tbl_users');
        $builder->SELECT('moblie_number, first_name, last_name,id,userid');
        $builder->WHERE('moblie_number',$mobile);
        if (!empty($withoutApprovedByAOR) && $withoutApprovedByAOR !=null && $withoutApprovedByAOR=='Y') {
            $builder->WHERE('is_active', '0');
        } else {
            $builder->groupStart();
            $builder->WHERE('is_active', '1')->orWhere('is_active', '0');
            $builder->groupEnd();
        }
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    // public function check_already_reg_email($email,$withoutApprovedByAOR=null)
    // {
    //     $builder = $this->db->table('efil.tbl_users');
    //     $builder->select('emailid, first_name, last_name');
    //     $builder->where('UPPER(emailid)', $email);
    //     if (!empty($withoutApprovedByAOR) && $withoutApprovedByAOR !=null && $withoutApprovedByAOR=='Y'){
    //         $builder->WHERE('is_active','0');
    //     }else{
    //         $builder->WHERE('is_active','1');
    //     }
    //     $query = $builder->get();

    //     if ($query->getNumRows() >= 1) {
    //         return $query->getResultArray();
    //     } else {
    //         return false;
    //     }
    // }

    function check_already_reg_email($email,$withoutApprovedByAOR=null)
    {
        $builder = $this->db->table('efil.tbl_users');
        $builder->SELECT('emailid, first_name, last_name,id,userid');
        $builder->WHERE('UPPER(emailid)',strtoupper($email));
        if (!empty($withoutApprovedByAOR) && $withoutApprovedByAOR !=null && $withoutApprovedByAOR=='Y') {
            $builder->WHERE('is_active','0');
        } else {
            $builder->groupStart();
            $builder->WHERE('is_active', '1')->orWhere('is_active', '0');
            $builder->groupEnd();
        }
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function check_already_reg_user($mobile=null,$email=null)
    {
        $builder = $this->db->table('efil.tbl_users');
        $builder->select('moblie_number, first_name, last_name');
        
        if (!empty($mobile) && $mobile !=null) {
            $builder->where('moblie_number', $mobile);
        } elseif (!empty($email) && $email !=null){
            $builder->where('UPPER(emailid)', $email);
        }
        $builder->groupStart()
            ->groupStart()
                ->where('ref_m_usertype_id', ARGUING_COUNSEL)
                ->groupStart()
                    ->where('is_active', '1')
                    ->orWhere('is_active', '0')
                ->groupEnd()
                ->groupStart()
                    ->where('is_deleted', true)
                    ->orWhere('is_deleted', false)
                ->groupEnd()
            ->groupEnd()
            ->orGroupStart()
                ->where('ref_m_usertype_id !=', ARGUING_COUNSEL)
                ->where('is_active', '1')
            ->groupEnd()
        ->groupEnd();
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function getStateList()
    {
        // Your query to get state list
        return $this->findAll(); // Example, update according to your logic
    }

    function check_password($password)
    {
        $builder = $this->db->table('efil.tbl_users');
        $builder->SELECT('password');
        // $builder->FROM();
        $builder->WHERE('password', $password);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function update_user_password($password, $mobile, $email)
    {
        $builder = $this->db->table('efil.tbl_users');
        if (!empty($mobile)) {
            $builder->WHERE('moblie_number', $mobile);
        } else if (!empty($email)) {
            $builder->WHERE('emailid', $email);
            $builder->orWhere('emailid', strtoupper($email));
        }
        if (!empty($mobile) || !empty($email)) {
            $builder->SET($password);
            $builder->UPDATE();
            if ($this->db->affectedRows() > 0) {
                return true;
            } else {
                return false;
            }
        } else
            return false;
    }

    function check_already_reg_mobile_bar($mobile)
    {
        $builder = $this->db->table('icmis.bar');
        $builder->SELECT('mobile');
        // $builder->FROM();
        $builder->WHERE('mobile', $mobile);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function check_already_reg_email_bar($email)
    {
        $builder = $this->db->table('icmis.bar');
        $builder->SELECT('email');
        // $builder->FROM();
        $builder->WHERE('email', $email);
        $query = $builder->get();
        //echo $this->db->last_query();die;
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    /// Advocate start
    // function check_already_reg_mobile_arguing_counsel($mobile,$withoutApprovedByAOR=null)
    // {
    //     // $this->db->SELECT('advocate_name,mobile_number,emailid');
    //     // $this->db->FROM('dscr.tbl_arguing_counsels');
    //     // $this->db->WHERE('mobile_number',$mobile);
    //     // $this->db->WHERE('is_deleted',false);
    //     // $query = $this->db->get();
    //     // if ($query->num_rows() >= 1) {
    //     //     return $query->result_array();
    //     // } else {
    //     //     return false;
    //     // }
    //     $builder = $this->db->table('dscr.tbl_arguing_counsels');
    //     $builder->select('advocate_name,mobile_number,emailid');
    //     $builder->where('mobile_number', $mobile);
    //     if (!empty($withoutApprovedByAOR) && $withoutApprovedByAOR !=null && $withoutApprovedByAOR=='Y'){
    //         $builder->WHERE('is_deleted',true);
    //         $builder->WHERE('is_user_registered',true);
    //     } else{
    //         $builder->WHERE('is_deleted',false);
    //     }
    //     $query = $builder->get();

    //     if ($query->getNumRows() >= 1) {
    //         return $query->getResultArray();
    //     } else {
    //         return false;
    //     }
    // }

    function check_already_reg_mobile_arguing_counsel($mobile,$withoutApprovedByAOR=null)
    {
        $builder = $this->db->table('dscr.tbl_arguing_counsels');
        $builder->SELECT('advocate_name,mobile_number,emailid');
        $builder->WHERE('mobile_number',$mobile);
        if (!empty($withoutApprovedByAOR) && $withoutApprovedByAOR !=null && $withoutApprovedByAOR=='Y') {
            $builder->WHERE('is_deleted',true);
            $builder->WHERE('is_user_registered',true);
        } else {
            $builder->WHERE('is_deleted',false);
        }
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    // function check_already_reg_email_arguing_counsel($email,$withoutApprovedByAOR=null)
    // {
    //     // $this->db->SELECT('advocate_name,mobile_number,emailid');
    //     // $this->db->FROM('dscr.tbl_arguing_counsels');
    //     // $this->db->WHERE('emailid',$email);
    //     // $this->db->WHERE('is_deleted',false);
    //     // $query = $this->db->get();
    //     // //echo $this->db->last_query();die;
    //     // if ($query->num_rows() >= 1) {
    //     //     return $query->result_array();
    //     // } else {
    //     //     return false;
    //     // }
    //     $builder = $this->db->table('dscr.tbl_arguing_counsels');
    //     $builder->select('advocate_name,mobile_number,emailid');
    //     $builder->where('emailid', $email);
    //     if (!empty($withoutApprovedByAOR) && $withoutApprovedByAOR !=null && $withoutApprovedByAOR=='Y'){
    //         $builder->WHERE('is_deleted',true);
    //         $builder->WHERE('is_user_registered',true);
    //     } else{
    //         $builder->WHERE('is_deleted',false);
    //     }
    //     $query = $builder->get();

    //     if ($query->getNumRows() >= 1) {
    //         return $query->getResultArray();
    //     } else {
    //         return false;
    //     }
    // }

    function check_already_reg_email_arguing_counsel($email,$withoutApprovedByAOR=null)
    {
        $builder = $this->db->table('dscr.tbl_arguing_counsels');
        $builder->SELECT('advocate_name,mobile_number,emailid');
        $builder->WHERE('emailid',$email);
        if (!empty($withoutApprovedByAOR) && $withoutApprovedByAOR !=null && $withoutApprovedByAOR=='Y') {
            $builder->WHERE('is_deleted',true);
            $builder->WHERE('is_user_registered',true);
        } else {
            $builder->WHERE('is_deleted',false);
        }
        $query = $builder->get();
        //echo $this->db->last_query();die;
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function getArguingCounselData($params = array())
    {
        $output =  false;
        if (isset($params['id']) && !empty($params['id'])) {
            $builder = $this->db->table('dscr.tbl_arguing_counsels tac');
            $builder->SELECT('tac.*');
            // $builder->FROM();
            $builder->WHERE('tac.id', (int)$params['id']);
            $builder->WHERE('tac.is_deleted', FALSE);
            if (isset($params['registration_code']) && !empty($params['registration_code'])) {
                $builder->WHERE('tac.registration_code', (string)$params['registration_code']);
            }
            $query = $builder->get();
            $output = $query->getResultArray();
        }
        return $output;
    }

    public function getAorData($params = array())
    {
        $output =  false;
        if (isset($params['ref_m_usertype_id']) && !empty($params['ref_m_usertype_id'])) {
            $builder = $this->db->table('efil.tbl_users tu');
            $builder->SELECT('tu.id,case when (last_name is not null AND last_name !=\'NULL\') then concat(first_name,\' \' ,last_name) else first_name  end  as first_name, tu.userid');
            $builder->WHERE('tu.ref_m_usertype_id', (int)$params['ref_m_usertype_id']);
            $builder->WHERE('tu.is_deleted', FALSE);
            $builder->WHERE('tu.is_active', '1');
            $builder->WHERE('tu.first_name !=', '0');
            if (isset($params['userid']) && !empty($params['userid'])) {
                $builder->WHERE('tu.userid', (string)$params['userid']);
            }
            $builder->orderBy('tu.first_name', 'ASC');
            $query = $builder->get();
            $output = $query->getResultArray();
            return $output;
        }
        return $output;
    }

    public function getArguingDataForApproval($params = array())
    {
        $output =  false;
        if (isset($params['login_id']) && !empty($params['login_id'])) {
            // $this->db->SELECT('tu.first_name,tu.is_active,tu.emailid ,tu.moblie_number ,tac.is_pre_approved,tac.bar_reg_no,tac.tbl_users_id');
            // $this->db->FROM('efil.tbl_users tu');
            // $this->db->JOIN('dscr.tbl_arguing_counsels tac','tu.id=tac.tbl_users_id');
            // $this->db->WHERE('tac.approving_user_id',(int)$params['login_id']);
            // $this->db->WHERE('tac.account_status',1);
            // $this->db->WHERE('tu.is_active','0');
            // $this->db->WHERE('tu.is_deleted',true);
            // $this->db->WHERE('tac.is_deleted',true);
            // $this->db->ORDER_BY("tac.created_on", "DESC");
            // $query = $this->db->get();
            // $output = $query->result_array();
            $builder = $this->db->table('efil.tbl_users tu');
            $builder->select('tu.first_name,tu.is_active,tu.emailid ,tu.moblie_number ,tac.is_pre_approved,tac.bar_reg_no,tac.tbl_users_id');
            $builder->join('dscr.tbl_arguing_counsels tac', 'tu.id=tac.tbl_users_id');
            $builder->where('tac.approving_user_id', (int)$params['login_id']);
            $builder->where('tac.account_status', 1);
            $builder->where('tu.is_active', '0');
            $builder->where('tu.is_deleted', true);
            $builder->where('tac.is_deleted', true);
            $builder->orderBy("tac.created_on", "DESC");
            $query = $builder->get();
            $output = $query->getResultArray();
        }
        return $output;
    }

    public function getUserDetails($params = array())
    {
        $output = false;
        if (isset($params['userId']) && !empty($params['userId'])) {
            // $this->db->SELECT('tu.id,tu.first_name,tu.emailid,tu.moblie_number');
            // $this->db->FROM('efil.tbl_users tu');
            // $this->db->WHERE_IN('tu.id',$params['userId']);
            // $query = $this->db->get();
            // $output = $query->result_array();
            $builder = $this->db->table('efil.tbl_users tu');
            $builder->select('tu.id,tu.first_name,tu.emailid,tu.moblie_number');
            $builder->where('tu.id', $params['userId']);
            $query = $builder->get();
            $output = $query->getResultArray();
        }
        return $output;
    }

    function check_old_password($mobile, $password)
    {
        $builder = $this->db->table('efil.tbl_users');
        $builder->SELECT('password');
        // $builder->FROM();
        $builder->WHERE('moblie_number', $mobile);
        $builder->WHERE('password', $password);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
}