<?php

namespace App\Models\Department;
use CodeIgniter\Model;

class DepartmentModel extends Model {

    function __construct() {
        parent::__construct();
    }

    function add_department_user($data, $department, $catagory) {
        $this->db->transStart();
        $builder = $this->db->table('users');
        $builder->INSERT($data);
        $dept_usr_id = $this->db->insertID();
        if ($dept_usr_id) {
            if ($department != NULL) {
                $builder1 = $this->db->table('m_tbl_departments');
                $builder1->SELECT('id,parent_ids as parent_ids,dep_user_id,catagory');
                $builder1->WHERE('dep_user_id', $_SESSION['login']['id']);
                $query = $builder1->get();
                $parent_dept_id = '';
                if ($query->getNumRows() >= 1) {
                    $result = $query->getResult();
                    $parent_id = substr($result[0]->parent_ids, 1, -1);
                    if (!empty($parent_id)) {
                        $parent_ids = "{" . $result[0]->id . ',' . $parent_id . "}";
                        $p_ids = explode(',', $parent_id);
                        $parent_dept_id = $p_ids[count($p_ids) - 1];
                    } else {
                        $parent_ids = "{" . $result[0]->id . "}";
                        $parent_dept_id = $result[0]->id;
                    }
                    $catag = $result[0]->catagory;
                } else {
                    $parent_ids = NULL;
                    $catag = $catagory;
                }
                $dep_data = array('department' => $department, 'parent_ids' => $parent_ids, 'dep_user_id' => $dept_usr_id, 'catagory' => $catag, 'created_by' => $_SESSION['login']['id']);
                $builder2 = $this->db->table('m_tbl_departments');
                $builder2->INSERT($dep_data);
                if (!empty($this->db->insertID()) && $_SESSION['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
                    $this->db->transComplete();
                } elseif (!empty($this->db->insertID())) {
                    $curr_dept_id = $this->db->insertID();
                    $update_query = "Update tbl_dept_advocate_panel set all_dept_ids=array_prepend($curr_dept_id, all_dept_ids) where parent_dept_id=" . $parent_dept_id;
                    $query_update = $this->db->query($update_query);
                    if ($query_update) {
                        $this->db->transComplete();
                    } else {
                        $this->db->transComplete();
                    }
                }
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function get_superadmin_department() {
        $builder = $this->db->table('m_tbl_departments dp');
        $builder->SELECT('*,users.is_active as active_user');
        $builder->JOIN('users', 'dp.dep_user_id=users.id');
        $builder->WHERE('created_by', $_SESSION['login']['id']);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
    
    function get_dept_details($dep_user_id) {
        $sql = "SELECT * FROM m_tbl_departments dp 
                 JOIN  'users' on users.id=dp.dep_user_id
                  WHERE users.id=" . $dep_user_id;
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function check_email_present($email, $dep_id) {
        $builder = $this->db->table('users');
        $builder->SELECT('*');
        $builder->WHERE('users.emailid', $email);
        $builder->WHERE('users.id!=', $dep_id);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return TRUE;
        } else {
            return false;
        }
    }

    function check_mobno_present($mobile, $dep_id) {
        $builder = $this->db->table('users');
        $builder->SELECT('*');
        $builder->WHERE('users.moblie_number', $mobile);
        $builder->WHERE('users.id!=', $dep_id);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return TRUE;
        } else {
            return false;
        }
    }    

    function update_department_user($dep_id, $data, $department, $catagory) {
        $builder = $this->db->table('users');
        $$builder->WHERE('id', $dep_id);
        $builder->UPDATE($data);
        if (!empty($department)) {
            if ($_SESSION['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
                $dep_data = array('department' => $department, 'catagory' => $catagory);
            } else {
                $dep_data = array('department' => $department);
            }
            $builder1 = $this->db->table('m_tbl_departments');
            $builder1->WHERE('dep_user_id', $dep_id);
            $builder1->UPDATE($dep_data);
        }
        if ($this->db_2->affected_rows() > 0 || $this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_dept_adv_panel($dep_user_id) {
        $sql = "SELECT *,users.id as user_id,dp.id as depid from users
                   JOIN tbl_dept_advocate_panel dap  on users.id=dap.advocate_id
                   JOIN m_tbl_departments dp on dap.parent_dept_id=dp.id
                   LEFT  JOIN m_tbl_state st on users.ref_m_states_id= st.state_id
                   LEFT JOIN m_tbl_districts dist on users.enrolled_district_id=dist.id
                   WHERE (select id as dept_id from m_tbl_departments where dep_user_id =" . $dep_user_id . ")=any(all_dept_ids) Order by created_datetime desc";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_dept_users($dep_user_id) {
        $sql = "SELECT *,users.is_active as active_user FROM m_tbl_departments dp 
                 JOIN  'users' on users.id=dp.dep_user_id
                WHERE (select id as dept_id from m_tbl_departments where dep_user_id = " . $dep_user_id . ") =any(parent_ids)  Order by created_datetime desc";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_department_aor($department_id) {
        $result = null;
        $builder = $this->db->table('efil.aor_department ad');
        $builder->select('ad.*, b.name');
        $builder->join('icmis.bar b', 'b.aor_code=ad.aor_code');
        $builder->WHERE('ref_department_id', $department_id);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResult();
        }
        return $result;
    }
    
    function get_aor_department($aor_code) {
        $output = null;
        $builder=$this->db->table('efil.aor_department ad');
        $builder->select('dep.*');
        $builder->join('efil.m_departments dep', 'dep.id =ad.ref_department_id');
        $builder->where('aor_code', $aor_code);
        $builder->orderBy('id','ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $output = $query->getResult();
        }
        return $output;
    }

    function selected_aor_for_case($registration_id) {
        $result = null;
        $builder = $this->db->table('efil.department_filings');
        $builder->where('registration_id', $registration_id);
        $query = $builder->get();
        $output = $query->getResult();
        if ($query->getNumRows() >= 1) {
            $result = $output;
        }
        return $result;
    }

    function get_advocate_data($first_name, $last_name, $mobile, $barcode) {
        $builder = $this->db->table('users');
        $builder->SELECT('users.id user_id,*');
        $builder->JOIN('m_tbl_state st', 'users.ref_m_states_id= st.state_id', 'LEFT');
        $builder->JOIN('m_tbl_districts dist', 'users.enrolled_district_id=dist.id', 'LEFT');
        if (!empty($mobile)) {
            $builder->orWhere('users.moblie_number', $mobile);
        }
        if (!empty($first_name)) {
            $builder->orLike('UPPER(users.first_name)', $first_name);
        }
        if (!empty($last_name)) {
            $builder->orLike('UPPER(users.last_name)', $last_name);
        }
        if (!empty($barcode)) {
            $builder->orWhere('users.bar_reg_no', $barcode);
        }
        $builder->WHERE('users.ref_m_usertype_id', USER_ADVOCATE);
        $builder->WHERE('users.is_active', TRUE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function delete_department_advocate($advid, $dept_id) {
        $builder = $this->db->table('tbl_dept_advocate_panel');
        $builder->WHERE('advocate_id', $advid);
        $builder->WHERE('dep_incharge_id', $dept_id);
        $builder->DELETE();
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function add_dept_advocate_data($data) {
        $builder = $this->db->table('tbl_dept_advocate_panel');
        $builder->insertBatch($data);
        if ($this->db->insertID()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_dep_adv_data($advocate_id, $parents) {
        $builder = $this->db->table('tbl_dept_advocate_panel');
        $builder->SELECT('advocate_id');
        $builder->WHERE('advocate_id', $advocate_id);
        $builder->WHERE('parent_dept_id', $parents);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_advocate_panel($dep_user_id) {
        $sql = "SELECT DISTINCT users.id user_id, users.first_name,users.last_name  from users
                    JOIN tbl_dept_advocate_panel dap  on users.id=dap.advocate_id
                    join  tbl_efiling_nums en on dap.advocate_id=en.created_by 
                    LEFT JOIN m_tbl_departments dp on dap.added_by=dp.id 
                    WHERE (select id as dept_id from m_tbl_departments where  dep_user_id =" . $dep_user_id . ")=any(all_dept_ids) and en.efiling_for_type_id = '" . $_SESSION['estab_details']['efiling_for_type_id'] . "' and en.efiling_for_id = '" . $_SESSION['estab_details']['efiling_for_id'] . "'";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_all_advocate_panel($dep_user_id) {
        $sql = "SELECT DISTINCT users.id user_id, users.first_name,users.last_name  from users
                     JOIN tbl_dept_advocate_panel dap  on users.id=dap.advocate_id
                    LEFT JOIN m_tbl_departments dp on dap.added_by=dp.id 
                     WHERE (select id as dept_id from m_tbl_departments where dep_user_id =" . $dep_user_id . ")=any(all_dept_ids)";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_dept_incharge_data($dep_id) {
        $builder = $this->db->table('users');
        $builder->SELECT('users.first_name, users.last_name');
        $builder->JOIN('m_tbl_departments dp', 'users.id=dp.dep_user_id');
        $builder->WHERE('dp.id', $dep_id);
        $builder->orderBy('created_datetime', 'desc');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_alloted_advocate($regid) {
        $builder = $this->db->table('users');
        $builder->SELECT('users.*');
        $builder->JOIN('tbl_efiling_nums en', 'users.id=en.created_by');
        $builder->WHERE('sub_created_by', $_SESSION['login']['id']);
        $builder->WHERE('en.registration_id', $regid);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_department_details($dep_user_id) {
        $builder = $this->db->table('m_tbl_departments dp');
        $builder->SELECT('id,parent_ids, parent_ids[array_length(parent_ids,1)] as parent,array_prepend("id", "parent_ids") as all_dep');
        $builder->WHERE('dep_user_id', $dep_user_id);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

}