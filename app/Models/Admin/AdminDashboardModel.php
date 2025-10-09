<?php

namespace App\Models\Admin;
use CodeIgniter\Model;

class AdminDashboardModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function get_efilied_nums_stage_wise_count($stage_ids) {
        $created_by = $this->session->userdata['login']['id'];
        $builder = $this->db->table('tbl_efiling_nums as en');
        $builder->SELECT('COUNT (CASE WHEN stage_id = ' . New_Filing_Stage . ' THEN 0 END) as total_new_efiling,
            COUNT (CASE WHEN stage_id = ' . DEFICIT_COURT_FEE . ' THEN 0 END) as deficit_crt_fee,
            COUNT (CASE WHEN stage_id = ' . Initial_Defected_Stage . ' THEN 0 END) as total_not_accepted,
            COUNT (CASE WHEN stage_id = ' . Transfer_to_CIS_Stage . ' THEN 0 END) as total_available_for_cis,
            COUNT (CASE WHEN stage_id = ' . Get_From_CIS_Stage . ' THEN 0 END) as total_get_from_cis,
            COUNT (CASE WHEN stage_id = ' . Initial_Defects_Cured_Stage . ' OR stage_id = ' . DEFICIT_COURT_FEE_PAID . ' THEN 0 END) as total_refiled_cases,
            COUNT (CASE WHEN stage_id = ' . Transfer_to_IB_Stage . ' THEN 0 END) as total_transfer_to_efiling_sec,
            COUNT (CASE WHEN stage_id = ' . I_B_Approval_Pending_Admin_Stage . ' THEN 0 END) as total_pending_scrutiny,
            COUNT (CASE WHEN stage_id = ' . I_B_Defected_Stage . ' THEN 0 END) as total_waiting_defect_cured,
            COUNT (CASE WHEN stage_id = ' . I_B_Rejected_Stage . ' OR stage_id = ' . E_REJECTED_STAGE . ' THEN 0 END) as total_rejected,
            COUNT (CASE WHEN stage_id = ' . I_B_Defects_Cured_Stage . ' THEN 0 END) as total_defect_cured,
            COUNT (CASE WHEN stage_id = ' . LODGING_STAGE . '  OR stage_id =' . DELETE_AND_LODGING_STAGE . ' THEN 0 END) as total_lodged_cases,
            COUNT (CASE WHEN (stage_id = ' . E_Filed_Stage . ' AND en.ref_m_efiled_type_id = ' . E_FILING_TYPE_NEW_CASE . ') OR ( stage_id = ' . CDE_ACCEPTED_STAGE . ' AND ref_m_efiled_type_id = ' . E_FILING_TYPE_CDE . ' ) THEN 0 END) as total_efiled_cases,
            COUNT (CASE WHEN (stage_id = ' . Document_E_Filed . ' AND en.ref_m_efiled_type_id =' . E_FILING_TYPE_MISC_DOCS . ') THEN 0 END) as total_efiled_docs,
            COUNT (CASE WHEN stage_id = ' . DEFICIT_COURT_FEE_E_FILED . ' AND en.ref_m_efiled_type_id =' . E_FILING_TYPE_DEFICIT_COURT_FEE . ' THEN 0 END) as total_efiled_deficit,
            COUNT (CASE WHEN stage_id = ' . IA_E_Filed . ' AND en.ref_m_efiled_type_id = ' . E_FILING_TYPE_IA . ' THEN 0 END) as total_efiled_ia');
        $builder->JOIN('tbl_efiling_case_status as cs', 'en.registration_id = cs.registration_id', 'INNER');
        $builder->WHERE('cs.is_active', 'TRUE');
        $builder->WHERE('en.is_active', 'TRUE');
        $builder->WHERE('en.efiling_for_type_id', $_SESSION['login']['admin_for_type_id']);
        $builder->WHERE('en.efiling_for_id', $_SESSION['login']['admin_for_id']);
        if (!in_array(LODGING_STAGE, $stage_ids) && !in_array(DELETE_AND_LODGING_STAGE, $stage_ids)) {
            $builder->WHERE('en.allocated_to', $created_by);
        }
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }
    
    public function get_efiled_count($stage_ids, $efiled_type, $admin_for_type_id, $admin_for_id) {
        $builder = $this->db->table('tbl_efiling_nums as en');
        $builder->SELECT('COUNT(en.registration_id) as count');
        $builder->JOIN('tbl_efiling_case_status as cs', 'en.registration_id=cs.registration_id');
        $builder->JOIN('dynamic_users_table as users', 'users.id=en.created_by', 'left');
        $builder->WHERE('cs.is_active', 'TRUE');
        $builder->whereIn('cs.stage_id', $stage_ids);
        $builder->WHERE('en.efiling_for_type_id', $admin_for_type_id);
        $builder->WHERE('en.efiling_for_id', $admin_for_id);
        $builder->whereIn('en.ref_m_efiled_type_id', $efiled_type);
        $builder->WHERE('en.allocated_to', $_SESSION['login']['id']);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }
    
    function login_logs_details($frm1 = '', $frm2 = '') {
        $builder = $this->load->database(unserialize('dynamic_users_table as users'), TRUE);
        $builder1 = $this->db->table('users_login_log as log');
        $builder1->SELECT(array('log.*', 'users.id', 'users.first_name', 'users.last_name', 'users.ref_m_usertype_id'));
        $builder1->JOIN('dynamic_users_table as users', 'log.login_id=users.id', 'INNER');
        $builder1->WHERE("to_char(login_time,'YYYY-MM-DD')::date >= '" . $frm1 . "'");
        $builder1->WHERE("to_char(login_time,'YYYY-MM-DD')::date <= '" . $frm2 . "'");
        $builder1->orderBy('log.login_time DESC');
        $query = $builder1->get();
        if ($query->getNumRows() != 0) {
            return $query->getResult();
        } else {
            return false;
        }
    }
    
    function add_user($data) {
        $builder = $this->load->database(unserialize('dynamic_users_table as users'), TRUE);
        $builder->trans_start();
        $builder->INSERT('users', $data);
        if ($data['ref_m_usertype_id'] == USER_MASTER_ADMIN) {
            $data_update = array('ref_m_usertype_id' => USER_ACTION_ADMIN);
            $builder->WHERE('ref_m_usertype_id', USER_ADMIN);
            $builder->WHERE('admin_for_id', $data['admin_for_id']);
            $builder->WHERE('admin_for_type_id', $data['admin_for_type_id']);
            $builder->UPDATE('users', $data_update);
        }
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_user($data, $id) {
        $builder = $this->load->database(unserialize('dynamic_users_table as users'), TRUE);
        $builder->WHERE('id', $id);
        $builder->UPDATE('users', $data);
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_state_list() {
        $builder = $this->db->table('dynamic_users_table as users');
        $builder->SELECT('admin_for_id');
        $builder->WHERE('admin_for_id IS NOT NULL');
        $query = $builder->get();
        $query1_result = $query->getResult();
        $admin_for_id = array();
        foreach ($query1_result as $row) {
            $admin_for_id[] = $row->admin_for_id;
        }
        $state_id = implode(",", $admin_for_id);
        $ids = explode(",", $state_id);
        $builder1 = $this->db->table('efil.m_tbl_state');
        $builder1->SELECT("state_id,state");
        $builder1->whereNotIn('state_id', $ids);
        $builder1->orderBy("state", "asc");
        $query = $builder1->get();
        return $query->getResult();
    }

    function get_state_list_edit() {
        $builder = $this->db->table('efil.m_tbl_state');
        $builder->SELECT('state_id,state');
        $builder->orderBy("state", "asc");
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function get_user_list1() {
        $builder = $this->db->table('dynamic_users_table as users');
        $builder->SELECT('users.*,hc.hc_name,estab.estab_name,state.state');
        $builder->JOIN('efil.m_tbl_high_courts hc', 'hc.id=users.admin_for_id', 'left');
        $builder->JOIN('efil.m_tbl_establishments estab', 'estab.id=users.admin_for_id', 'left');
        $builder->JOIN('efil.m_tbl_state state', 'state.state_id =estab.state_code', 'left');
        $builder->WHERE('users.ref_m_usertype_id', USER_ADMIN);
        $builder->WHERE('( hc.state_code = ' . $_SESSION['login']['admin_for_id'] . ' OR estab.state_code = ' . $_SESSION['login']['admin_for_id'] . ')');
        $builder->orderBy("users.id", "desc");
        $query = $builder->get();
        return $query->getResult();
    }

    function get_user_list() {
        $builder = $this->db->table('users');
        $builder->SELECT("users.admin_for_type_id, hc.id, hc.hc_name hc_name, hc.state_code state_code, state.state, '' estab_distt, users.* 
            FROM efil.m_tbl_high_courts hc 
            LEFT JOIN efil.m_tbl_state state ON state.state_id = hc.state_code
            LEFT JOIN " . 'dynamic_users_table as users' . " ON hc.id = users.admin_for_id WHERE hc.is_active is true AND (hc.state_code = " . $_SESSION['login']['admin_for_id'] . ") AND users.admin_for_type_id = 1  AND users.ref_m_usertype_id IN( " . USER_ADMIN . ", " . USER_MASTER_ADMIN . ", " . USER_ACTION_ADMIN . "  )
            
            UNION 
            
            SELECT users.admin_for_type_id, estab.id, estab.estab_name estab_name, estab.state_code state_code, state.state,district1.dist_name estab_distt, users.* 
            FROM efil.m_tbl_establishments estab              
            LEFT JOIN efil.m_tbl_districts district1 ON estab.ref_m_tbl_districts_id = district1.id
            LEFT JOIN efil.m_tbl_state state ON state.state_id = district1.ref_m_tbl_states_id
            LEFT JOIN " . 'dynamic_users_table as users' . " ON estab.id = users.admin_for_id WHERE estab.display = 'Y' AND users.ref_m_usertype_id IN( " . USER_ADMIN . ", " . USER_MASTER_ADMIN . ", " . USER_ACTION_ADMIN . "  ) AND users.admin_for_type_id = 2 AND (estab.state_code = " . $_SESSION['login']['admin_for_id'] . ")
            
            UNION 
            
            SELECT users.admin_for_type_id, district.id, district.dist_name, district.dist_code state_code, state.state,'' estab_distt, users.* 
            FROM efil.m_tbl_districts district  
            LEFT JOIN efil.m_tbl_state state ON state.state_id = district.ref_m_tbl_states_id
            LEFT JOIN " . 'dynamic_users_table as users' . " ON district.id = users.admin_for_id WHERE district.display = 'Y' AND users.ref_m_usertype_id =" . USER_DISTRICT_ADMIN . " AND users.admin_for_type_id = " . USER_DISTRICT_ADMIN . " AND (district.ref_m_tbl_states_id = " . $_SESSION['login']['admin_for_id'] . ")");
        $subQuery = $builder->getCompiledSelect();  
        $builder1 = $this->db->table("($subQuery ) a");
        $builder1->SELECT('*');
        $query = $builder1->get();
        return $query->getResult();
    }

    function get_district_admin_list() {
        $builder = $this->db->table('dynamic_users_table as users');
        $builder->SELECT("users.admin_for_type_id, estab.id, estab.estab_name estab_name, estab.state_code state_code,district.dist_name estab_distt,  users.*");
        $builder->JOIN('efil.m_tbl_establishments estab ', 'estab.id = users.admin_for_id');
        $builder->JOIN('efil.m_tbl_districts district ', 'estab.ref_m_tbl_districts_id = district.id', 'LEFT');
        $builder->WHERE('estab.display', 'Y');
        $builder->WHERE('users.admin_for_type_id', ENTRY_TYPE_FOR_ESTABLISHMENT);
        $builder->WHERE('estab.ref_m_tbl_districts_id', $_SESSION['login']['admin_for_id']);
        $builder->whereIn('users.ref_m_usertype_id', USER_ADMIN . ", " . USER_MASTER_ADMIN . ", " . USER_ACTION_ADMIN, FALSE);
        $builder->orderBy('estab.estab_name', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function get_admin_list_master_admin() {
        $builder = $this->db->table('dynamic_users_table as users');
        $builder->SELECT("users.admin_for_type_id, estab.id, estab.estab_name estab_name, estab.state_code state_code,district.dist_name estab_distt,  users.*");
        $builder->JOIN('efil.m_tbl_establishments estab ', 'estab.id = users.admin_for_id');
        $builder->JOIN('efil.m_tbl_districts district ', 'estab.ref_m_tbl_districts_id = district.id', 'LEFT');
        $builder->WHERE('estab.display', 'Y');
        $builder->WHERE('users.admin_for_type_id', ENTRY_TYPE_FOR_ESTABLISHMENT);
        $builder->WHERE('estab.id', $_SESSION['login']['admin_for_id']);
        $builder->whereIn('users.ref_m_usertype_id', USER_ACTION_ADMIN, FALSE);
        $builder->orderBy('estab.estab_name', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function get_user_details($id) {
        $builder = $this->db->table('dynamic_users_table');
        $builder->WHERE('id', $id);
        $builder->LIMIT(1);
        $query = $builder->get();
        return $query->getResult();
    }

    function action_taken($action_id, $status) {
        $builder = $this->load->database(unserialize('dynamic_users_table as users'), TRUE);
        if ($status == 'Active') {
            $data = array('is_active' => TRUE);
            $builder->WHERE('id', $action_id);
            $query = $builder->UPDATE('users', $data);
            return true;
        } if ($status == 'Deactive') {
            $data = array('is_active' => FALSE);
            $builder->WHERE('id', $action_id);
            $query = $builder->UPDATE('users', $data);
            return true;
        }
    }

    function get_payment_receipt($id, $admin_for_type_id, $admin_for_id) {
        if (is_numeric($id)) {
            $sql = "select * from tbl_court_fee_payment where id = " . $id . " and entry_for_type_id = " . $admin_for_type_id . " and entry_for_id = " . $admin_for_id;
            $query = $this->db->query($sql);
            if ($query->getNumRows() == 1) {
                return $query->getResultArray();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function get_admin_users1($admin_for_typeid, $admin_for_id) {
        $builder = $this->db->table('dynamic_users_table as users');
        $builder->SELECT("id,userid, concat(first_name,' ',last_name) f_l_name, COUNT(userid) as count,ref_m_usertype_id ");
        $builder->JOIN('tbl_efiling_nums ', 'tbl_efiling_nums.created_by=users.id');
        $builder->where("tbl_efiling_nums.efiling_for_type_id", $admin_for_typeid);
        $builder->where("tbl_efiling_nums.efiling_for_id", $admin_for_id);
        $builder->where("users.is_active", TRUE);
        $builder->groupBy('userid');
        $builder->groupBy('first_name');
        $builder->groupBy('id');
        $builder->groupBy('last_name');
        $builder->groupBy('ref_m_usertype_id');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function get_admin_users($admin_for_typeid, $admin_for_id) {
        $sql = "select uid, userid,name,ref_m_usertype_id, string_agg(distinct (CASE WHEN stage_id = " . Draft_Stage . " THEN count::varchar END),',') as draft,  
                string_agg(distinct (CASE WHEN stage_id = " . Initial_Defected_Stage . " THEN count::varchar END),',') as Not_Accepted,
                string_agg(distinct (CASE WHEN stage_id = " . DEFICIT_COURT_FEE . " THEN count::varchar END),',') as Deficit,
                string_agg(distinct (CASE WHEN stage_id =  " . I_B_Defected_Stage . " THEN count::varchar END),',') as Defective
                 from 
                (select uid,userid,name,stage_id,ref_m_usertype_id,count(registration_id) 
                from 
                (select users.id as uid,users.ref_m_usertype_id,en.registration_id,en.created_by,users.userid,ec.stage_id,ec.activated_on,users.first_name||' '||users.last_name as name from tbl_efiling_nums en
                join " . 'dynamic_users_table as users' . " on users.id=en.created_by
                join tbl_efiling_case_status ec on ec.registration_id=en.registration_id 
                where ec.is_active is true and ec.stage_id in (" . Draft_Stage . "," . Initial_Defected_Stage . "," . DEFICIT_COURT_FEE . "," . I_B_Defected_Stage . ") and users.is_active is true and en.efiling_for_type_id=" . $admin_for_typeid . " and en.efiling_for_id=" . $admin_for_id . ")a
                group by userid,stage_id,ref_m_usertype_id,name,uid order by userid,stage_id)b
                group by userid,name,ref_m_usertype_id,uid order by draft DESC";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function get_efiling_details($id, $admin_for_typeid, $admin_for_id, $stageid) {
        $builder = $this->db->table('tbl_efiling_nums as en');
        $builder->SELECT(array('en.efiling_no', 'users.first_name', 'users.last_name', 'en.ref_m_efiled_type_id', 'en.efiling_year', 'en.registration_id', 'et.efiling_type', 'cs.stage_id',
            'cs.activated_on', 'ec.pet_name', 'cs.activated_on', 'ms.case_type_name', 'ms.cnr_num', 'res_name', 'ms.cause_title', 'ec.cino'));
        $builder->JOIN('tbl_efiling_case_status as cs', 'en.registration_id = cs.registration_id');
        $builder->JOIN('m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $builder->JOIN('tbl_efiling_civil as ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id', 'left');
        $builder->JOIN('tbl_misc_doc_filing as ms', 'ms.ref_m_efiling_nums_registration_id=en.registration_id ', 'left');
        $builder->JOIN('dynamic_users_table as users', 'users.id=en.created_by ', 'left');
        $builder->WHERE('cs.is_active', 'TRUE');
        $builder->WHERE('en.is_active', 'TRUE');
        $builder->WHERE('en.efiling_for_type_id', $admin_for_typeid);
        $builder->WHERE('en.efiling_for_id', $admin_for_id);
        $builder->WHERE('en.created_by', $id);
        $builder->WHERE('cs.stage_id', $stageid);
        $builder->orderBy('cs.activated_on', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function deactive_admin_userlist($admin_for_typeid, $admin_for_id) {
        $builder = $this->db->table('dynamic_users_table as users');
        $builder->SELECT("distinct(first_name),users.*");
        $builder->JOIN('tbl_efiling_nums ', 'tbl_efiling_nums.created_by=users.id');
        $builder->where("tbl_efiling_nums.efiling_for_type_id", $admin_for_typeid);
        $builder->where("tbl_efiling_nums.efiling_for_id", $admin_for_id);
        $builder->where("users.is_active", FALSE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function check_if_admin_already_exist($data2) {
        if (!empty($data2['id'])) {
            $user_id = "and id != '" . $data2['id'] . "'";
        } else {
            $user_id = "";
        }
        if ($data2['ref_m_usertype_id'] != USER_ACTION_ADMIN) {
            $sql_query = "SELECT 'admin' as exists, * FROM " . 'dynamic_users_table as users' . " WHERE ref_m_usertype_id  = '" . $data2['ref_m_usertype_id'] . "' and admin_for_type_id = '" . $data2['admin_for_type_id'] . "' and admin_for_id = '" . $data2['admin_for_id'] . "' AND is_active IS TRUE " . $user_id;
        }
        if ($data2['ref_m_usertype_id'] == USER_ADMIN) {
            $sql_query = "SELECT 'multiple_admin' as exists, * FROM " . 'dynamic_users_table as users' . " WHERE ref_m_usertype_id  IN('" . USER_MASTER_ADMIN . "','" . USER_ACTION_ADMIN . "') and admin_for_type_id = '" . $data2['admin_for_type_id'] . "' and admin_for_id = '" . $data2['admin_for_id'] . "' AND is_active IS TRUE ";
        }
        if ($data2['ref_m_usertype_id'] == USER_ACTION_ADMIN) {
            $sql_query = "SELECT 'no_masteradmin' as exists, * FROM " . 'dynamic_users_table as users' . " WHERE ref_m_usertype_id  IN('" . USER_MASTER_ADMIN . "') and admin_for_type_id = '" . $data2['admin_for_type_id'] . "' and admin_for_id = '" . $data2['admin_for_id'] . "' AND is_active IS TRUE ";
        }
        $sql_query .= " UNION SELECT 'loginid' as exists, * FROM " . 'dynamic_users_table as users' . " WHERE userid = '" . $data2['userid'] . "' $user_id";
        $sql_query .= " UNION SELECT 'mobile' as exists, * FROM " . 'dynamic_users_table as users' . " WHERE moblie_number = '" . $data2['moblie_number'] . "' $user_id";
        $sql_query .= " UNION SELECT 'email' as exists, * FROM " . 'dynamic_users_table as users' . " WHERE emailid = '" . $data2['emailid'] . "' $user_id";
        $query = $this->db->query($sql_query);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function check_if_master_admin_exist($data2) {
        $builder = $this->db->table('dynamic_users_table');
        $builder->SELECT("*");
        $builder->WHERE("is_active", TRUE);
        $builder->WHERE("ref_m_usertype_id", USER_MASTER_ADMIN);
        $builder->WHERE("admin_for_type_id", $data2['admin_for_type_id']);
        $builder->WHERE("admin_for_id", $data2['admin_for_id']);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }


    function pending_advocate_list($admin_for_type_id) {
        $builder = $this->db->table('tbl_advocate_register');        
        $builder->SELECT('users.id,users.ref_m_usertype_id, tbl_advocate_register.id row_id ,login_id,add_date,efiling_for_type_id,is_register,users.first_name,users.last_name,users.moblie_number,users.bar_reg_no,users.enrolled_state_id,users.enrolled_district_id,users.enrolled_establishment_id, users.high_court_id');
        $builder->JOIN('dynamic_users_table as users', 'users.id=tbl_advocate_register.login_id', 'left');
        $builder->WHERE('tbl_advocate_register.is_active', TRUE);
        $builder->WHERE('tbl_advocate_register.high_court_id', $_SESSION['login']['admin_for_id']);
        $builder->WHERE('tbl_advocate_register.efiling_for_type_id', $_SESSION['login']['admin_for_type_id']);
        $builder->orderBy("tbl_advocate_register.is_register");
        $builder->orderBy("tbl_advocate_register.id", "desc");
        $subQuery = $builder->getCompiledSelect();
        $builder1 = $this->db->table("($subQuery ) a");
        $builder1->SELECT('a.*, case when (a.enrolled_state_id is null OR a.enrolled_state_id = 0) then (select hc_name from efil.m_tbl_high_courts where id = a.high_court_id ) else 
            (SELECT concat(estab_name, \',\', dist_name ,\',\',state )  
            FROM efil.m_tbl_state st 
            LEFT JOIN efil.m_tbl_districts dist ON st.state_id = dist.ref_m_tbl_states_id
            LEFT JOIN efil.m_tbl_establishments estab ON dist.id = estab.ref_m_tbl_districts_id
            where estab.id = a.enrolled_establishment_id ) end as enrolled_for', FALSE);
        $query = $builder1->get();
        return $query->getResult();
    }

    function advocate_info($user_id) {
        $builder = $this->db->table('dynamic_users_table as users');    
        $builder->SELECT('users.*');
        $builder->WHERE('id', $user_id);
        $builder->WHERE('is_active', TRUE);
        $subQuery = $builder->getCompiledSelect();
        $builder1 = $this->db->table("($subQuery ) a");
        $builder1->SELECT('a.*, case when (a.enrolled_state_id is null OR a.enrolled_state_id = 0) then (select hc_name from efil.m_tbl_high_courts where id = a.high_court_id ) else 
            (SELECT concat(estab_name, \',\', dist_name ,\',\',state )  
            FROM efil.m_tbl_state st 
            LEFT JOIN efil.m_tbl_districts dist ON st.state_id = dist.ref_m_tbl_states_id
            LEFT JOIN efil.m_tbl_establishments estab ON dist.id = estab.ref_m_tbl_districts_id
            where estab.id = a.enrolled_establishment_id ) end as enrolled_for', FALSE);
        $query = $builder1->get();
        return $query->getResult();
    }

    public function user_inperson_info($user_id) {
        $builder = $this->db->table('dynamic_users_table as users');
        $builder->SELECT('*');
        $builder->WHERE('users.is_account_active', TRUE);
        $builder->WHERE('users.id', $user_id);
        $builder->LIMIT(1);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function count_new_registered_user($newORrejected = FALSE) {
        $admin_for_id = $_SESSION['login']['admin_for_id'];
        $builder = $this->db->table('dynamic_users_table as users');
        $builder->SELECT('COUNT(id)  as count');
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
        $builder->WHERE('users.ref_m_usertype_id NOT IN (' . USER_ADMIN . ',' . USER_SUPER_ADMIN . ',' . USER_MASTER_ADMIN . ',' . USER_ACTION_ADMIN . ')');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $row = $query->getRow();
            return $row->count;
        } else {
            return 0;
        }
    }

    function count_pending_advocate_list($admin_for_type_id) {
        $builder = $this->db->table('tbl_advocate_register');
        if ($admin_for_type_id == E_FILING_FOR_HIGHCOURT) {
            $builder->SELECT('COUNT(tbl_advocate_register.id)  as count');
            $builder->JOIN('dynamic_users_table as users', 'users.id=tbl_advocate_register.login_id');
            $builder->WHERE('tbl_advocate_register.is_active', TRUE);
            $builder->WHERE('tbl_advocate_register.high_court_id', $_SESSION['login']['admin_for_id']);
            $builder->WHERE('tbl_advocate_register.efiling_for_type_id', $_SESSION['login']['admin_for_type_id']);
        } elseif ($admin_for_type_id == E_FILING_FOR_ESTABLISHMENT) {
            $builder->SELECT('COUNT(tbl_advocate_register.id)  as count');
            $builder->JOIN('dynamic_users_table as users', 'users.id=tbl_advocate_register.login_id');
            $builder->WHERE('tbl_advocate_register.is_active', TRUE);
            $builder->WHERE('tbl_advocate_register.high_court_id', $_SESSION['login']['admin_for_id']);
            $builder->WHERE('tbl_advocate_register.efiling_for_type_id', $_SESSION['login']['admin_for_type_id']);
        }
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $row = $query->getRow();
            return $row->count;
        } else {
            return 0;
        }
    }

    function advocate_details($row_id, $admin_for_type_id) {
        $builder = $this->db->table('tbl_advocate_register');
        if ($admin_for_type_id == E_FILING_FOR_HIGHCOURT) {
            $builder->SELECT('tbl_advocate_register.login_id,' . E_FILING_FOR_HIGHCOURT . ' efiling_for_type_id,tbl_advocate_register.high_court_id,users.bar_reg_no,hc.hc_code national_code,hc.state_code');
            $builder->JOIN('dynamic_users_table as users', 'users.id=tbl_advocate_register.login_id');
            $builder->JOIN('efil.m_tbl_high_courts hc', 'hc.id=tbl_advocate_register.high_court_id', 'left');
        } elseif ($admin_for_type_id == E_FILING_FOR_ESTABLISHMENT) {
            $builder->SELECT('tbl_advocate_register.login_id,' . E_FILING_FOR_ESTABLISHMENT . ' efiling_for_type_id,tbl_advocate_register.high_court_id,users.bar_reg_no,estab.est_code national_code,estab.state_code');
            $builder->JOIN('dynamic_users_table as users', 'users.id=tbl_advocate_register.login_id');
            $builder->JOIN('efil.m_tbl_establishments estab', 'estab.id=tbl_advocate_register.high_court_id', 'left');
        }
        $builder->WHERE('tbl_advocate_register.is_active', TRUE);
        $builder->WHERE('tbl_advocate_register.id', $row_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return 0;
        }
    }

    function advocate_status_update($user_id, $efiling_for_type_id, $adv_name, $adv_code, $row_id) {
        $this->db->transStart();
        $data = array('is_register' => 'Y');
        $builder = $this->db->table('tbl_advocate_register');
        $builder->WHERE('id', $row_id);
        $builder->WHERE('efiling_for_type_id', $efiling_for_type_id);
        $builder->WHERE('is_active ', TRUE);
        $builder->UPDATE($data);
        if ($this->db->affectedRows() > 0) {
            $builder = $this->db->table('tbl_efiling_nums');
            $builder->SELECT('registration_id, efiling_for_type_id, efiling_for_id,ref_m_efiled_type_id');
            $builder->WHERE('created_by', $user_id);
            $builder->WHERE('efiling_for_type_id', $efiling_for_type_id);
            $builder->WHERE('is_active', TRUE);
            $query = $builder->get();
            $result = $query->getResultArray();
            foreach ($result as $value) {
                if ($value['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
                    $update_efiling_civil_data[] = array(
                        'ref_m_efiling_nums_registration_id' => $value['registration_id'],
                        'pet_adv' => $adv_name,
                        'pet_adv_cd' => $adv_code
                    );
                }
                if ($value['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS || $value['ref_m_efiled_type_id'] == E_FILING_TYPE_DEFICIT_COURT_FEE || $value['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
                    $update_misc_doc_data[] = array(
                        'ref_m_efiling_nums_registration_id' => $value['registration_id'],
                        'advocate_name' => $adv_name,
                        'advocate_code' => $adv_code
                    );
                }
            }
            if (!empty($update_efiling_civil_data)) {
                $builder = $this->db->table('tbl_efiling_civil');
                $builder->updateBatch($update_efiling_civil_data, 'ref_m_efiling_nums_registration_id');
            } if (!empty($update_misc_doc_data)) {
                $builder = $this->db->table('tbl_misc_doc_filing');
                $builder->updateBatch($update_misc_doc_data, 'ref_m_efiling_nums_registration_id');
            }

            if ($this->db->transStatus() === FALSE) {
                return FALSE;
            } else {
                $this->db->transComplete();
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    function action_to_active_user($action_id, $status) {
        $builder = $this->load->database(unserialize('dynamic_users_table as users'), TRUE);
        if ($status == 'Active') {
            $data = array('is_active' => TRUE, 'is_account_active' => TRUE);
            $builder->WHERE('id', $action_id);
            $query = $builder->UPDATE('users', $data);
            return true;
        } if ($status == 'Deactive') {
            $data = array('is_active' => FALSE, 'is_account_active' => FALSE);
            $builder->WHERE('id', $action_id);
            $query = $builder->UPDATE('users', $data);
            return true;
        }
    }

    function get_transaction_details($order_id) {
        $builder = $this->db->table('tbl_court_fee_payment');
        $builder->SELECT('*');
        $builder->WHERE('order_no', $order_id);
        $builder->WHERE('is_active', TRUE);
        $builder->LIMIT(1);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_contact_list() {
        $builder = $this->db->table('efil.tbl_admin_contact users');
        $builder->SELECT('users.*');
        $builder->WHERE('is_active', TRUE);
        $builder->WHERE('updated_by', $_SESSION['login']['id']);
        $subQuery = $builder->getCompiledSelect();
        $builder1 = $this->db->table("($subQuery ) a");
        $builder->SELECT('a.*, case WHEN (a.admin_for_type_id = ' . E_FILING_FOR_HIGHCOURT . ' ) 
            THEN (SELECT hc_name from efil.m_tbl_high_courts WHERE id = a.admin_for_id) 
            ELSE 
            (SELECT concat( estab_name, \'@@@\', dist_name ,\'@@@\',state )
            FROM efil.m_tbl_state st 
            LEFT JOIN efil.m_tbl_districts dist ON st.state_id = dist.ref_m_tbl_states_id
            LEFT JOIN efil.m_tbl_establishments estab ON dist.id = estab.ref_m_tbl_districts_id
            WHERE estab.id = a.admin_for_id ORDER BY dist_name ,estab_name) END as estab_details', FALSE);

        $subQuery2 = $builder->getCompiledSelect();
        $builder3 = $this->db->table("($subQuery2 ) b");
        $builder3->SELECT('email_id');
        $builder3->SELECT("string_agg(id::varchar(101), '||||') as id", FALSE);
        $builder3->SELECT("string_agg(estab_details,'||||') as estab_name", FALSE);
        $builder3->groupBy("email_id");
        $query = $builder3->get();
        return $query->getResult();
    }

    function add_contact($data) {
        $builder = $this->db->table('efil.tbl_admin_contact'); 
        $builder->INSERT($data);
        if ($this->db->insertID()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function add_contact_batch($data) {
        $builder = $this->db->table('efil.tbl_admin_contact');
        $builder->insertBatch($data);
        if ($this->db->insertID()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_contact_details($id) {
        $builder = $this->db->table('efil.tbl_admin_contact');
        $builder->WHERE('id', $id);
        $builder->LIMIT(1);
        $query = $builder->get();
        return $query->getResult();
    }

    function update_contact($data, $id, $frm_ref_id, $court_type) {
        if ($court_type == E_FILING_FOR_ESTABLISHMENT) {
            $builder = $this->db->table('efil.tbl_admin_contact');
            $builder->WHERE('frm_ref_id', $frm_ref_id);
            $query = $builder->DELETE();
            if ($query) {
                $builder = $this->db->table('efil.tbl_admin_contact');
                $builder->insertBatch($data);
                return true;
            } else {
                return false;
            }
        } elseif ($court_type == E_FILING_FOR_HIGHCOURT) {
            $builder = $this->db->table('efil.tbl_admin_contact');
            $builder->WHERE('frm_ref_id', $frm_ref_id);
            $query = $builder->UPDATE($data);
            if ($query) {
                return true;
            } else {
                return false;
            }
        }
    }

    function update_user_detail($user_id, $data) {
        $builder = $this->load->database(unserialize('dynamic_users_table as users'), TRUE);
        $builder = $this->db->table('users');
        $builder->set($data);
        $builder->WHERE('id', $user_id);
        $result = $builder->UPDATE();
        if ($this->db->affectedRows()) {
            return true;
        } else {
            return false;
        }
    }

    function court_details($id, $fetch_details_for) {
        if ($fetch_details_for == E_FILING_FOR_HIGHCOURT) {
            $builder = $this->db->table('efil.m_tbl_high_courts hc');
            $builder->SELECT("hc.hc_name as location");
            $builder->WHERE('id', $id);
            $query = $builder->get();
            return $query->getResultArray();
        }
        if ($fetch_details_for == USER_DISTRICT_ADMIN) {
            $builder = $this->db->table('efil.m_tbl_districts dist');
            $builder->SELECT("CONCAT(dist.dist_name,', ',state.state,', ',hc.hc_name) as location");
            $builder->JOIN('efil.m_tbl_state state', 'dist.ref_m_tbl_states_id = state.state_id');
            $builder->JOIN('efil.m_tbl_high_courts hc', 'hc.id = state.ref_efil.m_tbl_high_courts_id');
            $builder->WHERE('dist.id', $id);
            $query = $builder->get();
            return $query->getResultArray();
        }
        if ($fetch_details_for == ENTRY_TYPE_FOR_ESTABLISHMENT) {
            $builder = $this->db->table('efil.m_tbl_establishments estab');
            $builder->SELECT("CONCAT(estab.estab_name,', ',dist.dist_name,', ',state.state,', ',hc.hc_name) as location");
            $builder->JOIN('efil.m_tbl_districts dist', 'estab.ref_m_tbl_districts_id = dist.id');
            $builder->JOIN('efil.m_tbl_state state', 'dist.ref_m_tbl_states_id = state.state_id');
            $builder->JOIN('efil.m_tbl_high_courts hc', 'hc.id = state.ref_efil.m_tbl_high_courts_id');
            $builder->WHERE('estab.id', $id);
            $query = $builder->get();
            return $query->getResultArray();
        }
    }
    
    function add_reason($data) {
        $builder = $this->db->table('m_tbl_allocation_reason');
        $builder->INSERT($data);
        if ($this->db->insertID()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_reason() {
        $builder = $this->db->table('m_tbl_allocation_reason');
        $builder->SELECT('*');
        $builder->WHERE('created_by', $_SESSION['login']['id']);
        $builder->orderBy('created_on', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_reason_id($id) {
        $builder = $this->db->table('m_tbl_allocation_reason');
        $builder->SELECT('*');
        $builder->WHERE('id', $id);
        $builder->WHERE('created_by', $_SESSION['login']['id']);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function update_reason($data, $id) {
        $builder = $this->db->table('m_tbl_allocation_reason');
        $builder->WHERE('id', $id);
        $builder->WHERE('created_by', $_SESSION['login']['id']);
        $builder->UPDATE($data);
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_reason_status($action_id, $data) {
        $builder = $this->db->table('m_tbl_allocation_reason');
        $builder->WHERE('id', $action_id);
        $builder->WHERE('created_by', $_SESSION['login']['id']);
        $builder->UPDATE($data);
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    function get_admin_list_hc_master_admin() {
        $builder = $this->db->table('dynamic_users_table as users');
        $builder->SELECT("users.admin_for_type_id, hc.*,  users.*");
        $builder->JOIN('efil.m_tbl_high_courts hc ', 'hc.id = users.admin_for_id');
        $builder->WHERE('users.admin_for_type_id', ENTRY_TYPE_FOR_HIGHCOURT);
        $builder->WHERE('hc.id', $_SESSION['login']['admin_for_id']);
        $builder->whereIn('users.ref_m_usertype_id', USER_ACTION_ADMIN);
        $builder->orderBy('hc.hc_name', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function add_case_type_master($data) {
        $builder = $this->db->table('m_tbl_cis_case_type');
        $builder->INSERT($data);
        if ($this->db->insertID()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_case_type_list() {
        $builder = $this->db->table('m_tbl_cis_case_type cct');
        $builder->SELECT('cct.id,cct.state_code,cct.national_code,cct.efiling_for_type_id,cct.efiling_for_id,cct.civil_or_criminal,cct.cis_case_type_id,
                cct.cis_case_type_name ,cct.created_by,cct.created_on,cct.created_by_ip,
                cct.updated_by,cct.updated_on,cct.updated_by_ip,cct.is_deleted, CASE WHEN (cct.efiling_for_type_id = ' . E_FILING_FOR_HIGHCOURT . ' ) 
            THEN (SELECT hc_name from efil.m_tbl_high_courts WHERE id = cct.efiling_for_id) 
            ELSE 
            (SELECT concat( estab_name, \'@@@\', dist_name ,\'@@@\',state )
            FROM efil.m_tbl_state st 
            LEFT JOIN efil.m_tbl_districts dist ON st.state_id = dist.ref_m_tbl_states_id
            LEFT JOIN efil.m_tbl_establishments estab ON dist.id = estab.ref_m_tbl_districts_id
            WHERE estab.id = cct.efiling_for_id ORDER BY dist_name ,estab_name) END as estab_details');
        $builder->WHERE('cct.created_by', $_SESSION['login']['id']);
        $builder->WHERE('cct.is_deleted', FALSE);
        $builder->orderBy('cct.civil_or_criminal', 'ASC');
        $builder->orderBy('cct.cis_case_type_id', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function get_enable_case_type_list($national_code, $civil_or_criminal, $court_type, $state_code) {
        $builder = $this->db->table('m_tbl_cis_case_type');
        $builder->SELECT("*");
        $builder->WHERE('national_code', $national_code);
        $builder->WHERE('civil_or_criminal', $civil_or_criminal);
        $builder->WHERE('efiling_for_type_id', $court_type);
        $builder->WHERE('state_code', $state_code);
        $builder->WHERE('is_deleted', FALSE);
        $builder->orderBy('cis_case_type_id', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function delete_case_type_master($id) {
        $data = array('is_deleted' => TRUE);
        $builder = $this->db->table('m_tbl_cis_case_type');
        $builder->WHERE('id', $id);
        $builder->WHERE('created_by', $_SESSION['login']['id']);
        $builder->UPDATE($data);
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function case_type_id_in_array($national_code, $civil_or_criminal, $court_type, $state_code) {
        $builder = $this->db->table('m_tbl_cis_case_type');
        $builder->SELECT('array_agg("cis_case_type_id")');
        $builder->WHERE('national_code', $national_code);
        $builder->WHERE('efiling_for_type_id', $court_type);
        $builder->WHERE('state_code', $state_code);
        $builder->WHERE('is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    function decode_cron_status($date = null) {
        $builder = $this->db->table('cron_for_cis_log');
        $builder->SELECT('id,responce,cron_date');
        if (!empty($date)) {
            $builder->WHERE('cron_date', $date);
        }
        $builder->orderBy('cron_date', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function get_est_masteradmin_type($state_id, $dist_id, $estab_id) {
        $builder = $this->db->table('dynamic_users_table as users');
        $builder->SELECT('*');
        $builder->WHERE('users.enrolled_state_id', $state_id);
        $builder->WHERE('users.enrolled_district_id', $dist_id);
        $builder->WHERE('users.admin_for_id', $estab_id);
        $builder->WHERE('users.ref_m_usertype_id', USER_MASTER_ADMIN);
        $builder->WHERE('users.admin_for_type_id', E_FILING_FOR_ESTABLISHMENT);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function get_est_admin_type($state_id, $dist_id, $estab_id) {
        $builder = $this->db->table('dynamic_users_table as users');
        $builder->SELECT('*');
        $builder->WHERE('users.enrolled_state_id', $state_id);
        $builder->WHERE('users.enrolled_district_id', $dist_id);
        $builder->WHERE('users.admin_for_id', $estab_id);
        $builder->WHERE('users.admin_for_type_id', E_FILING_FOR_ESTABLISHMENT);
        $builder->whereIn('users.ref_m_usertype_id', array(USER_ADMIN, USER_MASTER_ADMIN, USER_ACTION_ADMIN));
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function get_hc_masteradmin_type($state_id) {
        $builder = $this->db->table('dynamic_users_table as users');
        $builder->SELECT('*');
        $builder->WHERE('users.admin_for_id', $state_id);
        $builder->WHERE('users.admin_for_type_id', E_FILING_FOR_HIGHCOURT);
        $builder->WHERE('users.ref_m_usertype_id', USER_MASTER_ADMIN);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function get_hc_admin_type($state_id) {
        $builder = $this->db->table('dynamic_users_table as users');
        $builder->SELECT('*');
        $builder->WHERE('users.admin_for_id', $state_id);
        $builder->WHERE('users.admin_for_type_id', E_FILING_FOR_HIGHCOURT);
        $builder->whereIn('users.ref_m_usertype_id', array(USER_ADMIN, USER_MASTER_ADMIN, USER_ACTION_ADMIN));
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function admin_allocated_efiling_nums($id) {
        $builder = $this->db->table('tbl_efiling_nums');
        $builder->SELECT('registration_id, efiling_no');
        $builder->WHERE('allocated_to', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return TRUE;
        } else {
            return false;
        }
    }

    function get_action_admins_list($id) {
        $builder = $this->db->table('users u1');
        $builder->SELECT('u1.userid');
        $builder->JOIN('users u2', 'u1.admin_for_type_id = u2.admin_for_type_id and u1.admin_for_id = u2.admin_for_id');
        $builder->WHERE('u1.ref_m_usertype_id', USER_ACTION_ADMIN);
        $builder->WHERE('u2.id', $id);
        $builder->WHERE('u1.is_active', true);
        $builder->WHERE('u2.is_active', true);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return TRUE;
        } else {
            return false;
        }
    }

    function get_master_action_admin($estab_id) {
        $builder = $this->db->table('dynamic_users_table as users');
        $builder->SELECT('*');
        $builder->WHERE('users.ref_m_usertype_id', USER_ACTION_ADMIN);
        $builder->WHERE('admin_for_id', $estab_id);
        $builder->WHERE('admin_for_type_id', $_SESSION['login']['admin_for_type_id']);
        $builder->WHERE('users.is_active', true);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function district_action_admin($estab_id) {
        $builder = $this->db->table('dynamic_users_table as users');
        $builder->SELECT("users.admin_for_type_id, estab.id, estab.estab_name estab_name, estab.state_code state_code,district.dist_name estab_distt,  users.*");
        $builder->JOIN('efil.m_tbl_establishments estab ', 'estab.id = users.admin_for_id');
        $builder->JOIN('efil.m_tbl_districts district ', 'estab.ref_m_tbl_districts_id = district.id', 'LEFT');
        $builder->WHERE('estab.display', 'Y');
        $builder->WHERE('users.admin_for_type_id', ENTRY_TYPE_FOR_ESTABLISHMENT);
        $builder->WHERE('estab.ref_m_tbl_districts_id', $_SESSION['login']['admin_for_id']);
        $builder->WHERE('users.admin_for_id', $estab_id);
        $builder->whereIn('users.ref_m_usertype_id', USER_ACTION_ADMIN);
        $builder->orderBy('estab.estab_name', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function get_efiling_num_list($id) {
        $builder = $this->db->table('tbl_efiling_nums');
        $builder->SELECT('registration_id, efiling_no');
        $builder->WHERE('allocated_to', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function update_allocation($efil_no, $to_id, $from_id) {
        $data = array('allocated_to' => $to_id);
        $builder = $this->db->table('tbl_efiling_nums');
        $builder->where('allocated_to', $from_id);
        $builder->whereIn('registration_id', $efil_no);
        $builder->update($data);
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_high_court_user($estab_id) {
        $builder = $this->db->table('efil.m_tbl_high_courts hc');
        $builder->SELECT('users.*');
        $builder->JOIN('dynamic_users_table as users', 'hc.id = users.admin_for_id');
        $builder->where('hc.is_active', TRUE);
        $builder->where('hc.state_code', $_SESSION['login']['admin_for_id']);
        $builder->where('users.admin_for_type_id ', ENTRY_TYPE_FOR_HIGHCOURT);
        $builder->where('users.ref_m_usertype_id ', USER_ACTION_ADMIN);
        $builder->where('users.admin_for_id ', $estab_id);
        $builder->where('users.is_active', TRUE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function estab_action_admin($state_id, $dist_id, $estab_id) {
        $builder = $this->db->table('dynamic_users_table as users');
        $builder->SELECT(" users.*");
        $builder->WHERE('users.admin_for_type_id', ENTRY_TYPE_FOR_ESTABLISHMENT);
        $builder->WHERE('users.enrolled_state_id', $state_id);
        $builder->WHERE('users.enrolled_district_id', $dist_id);
        $builder->WHERE('users.admin_for_id', $estab_id);
        $builder->WHERE('users.ref_m_usertype_id', USER_ACTION_ADMIN);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

}