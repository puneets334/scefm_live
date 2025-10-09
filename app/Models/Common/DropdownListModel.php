<?php

namespace App\Models\Common;
use CodeIgniter\Model;

class DropdownListModel extends Model {

    protected $table = 'efil.m_tbl_high_courts';

    function __construct() {
        parent::__construct();
    }

    function get_state_list() {
        $builder = $this->db->table('efil.m_tbl_state');
        $builder->SELECT("state_id,state");
        $builder->orderBy("state", "asc");
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_state_list_Y() {
        $builder = $this->db->table($this->table);
        $builder->select('state')
            ->where('display', 'Y')
            ->orderBy('state', 'asc');
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_state_list_esl() {
        $stateid = $this->session->userdata['login']['admin_for_id'];
        $builder = $this->db->table('efil.m_tbl_state as st');
        $builder->SELECT("state_id,state");
        $builder->JOIN('dynamic_users_table as users', 'st.state_id = users.admin_for_id');
        $builder->WHERE('display', 'Y');
        $builder->WHERE('users.admin_for_id', $stateid);
        $builder->WHERE('users.id', $this->session->userdata['login']['id']);
        $builder->orderBy("state", "asc");
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_high_court_list() {
        $builder = $this->db->table($this->table);
        $builder->select('id, hc_name, hc_code, state_code');
        $builder->where('is_active', true);
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            
        }
        $builder->orderBy('hc_name', 'asc');
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_super_admin_high_court_list() {
        $builder = $this->db->table('efil.m_tbl_high_courts');
        $builder->SELECT("*");
        $builder->WHERE('is_active', 'TRUE');
        $builder->WHERE('parent_hc_id', NULL);
        $builder->orderBy("hc_name", "asc");
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_super_admin_state_list() {
        $builder = $this->db->table('efil.m_tbl_state');
        $builder->SELECT("state_id,state");
        $builder->WHERE('display', 'Y');
        $builder->orderBy("state", "asc");
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_district_admin_estab_list() {
        $builder = $this->db->table('efil.m_tbl_state');
        $builder->SELECT("state_id,state");
        $builder->WHERE('display', 'Y');
        $builder->WHERE('state_id', $_SESSION['login']['admin_for_id']);
        $builder->orderBy("state", "asc");
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_state_high_court_list() {
        $builder = $this->db->table('efil.m_tbl_high_courts as hc');
        $builder->SELECT("hc.id,hc.hc_name,hc_code");
        $builder->join('efil.m_tbl_state as st', 'st.ref_efil.m_tbl_high_courts_id = hc.id');
        $builder->join('dynamic_users_table as users', 'st.state_id = users.enrolled_state_id');
        $builder->WHERE('hc.is_active', 'TRUE');
        $builder->WHERE('parent_hc_id', NULL);
        $builder->WHERE('users.id', $this->session->userdata['login']['id']);
        $builder->orderBy("hc_name", "asc");
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_highcourt_code($hc_id) {
        $builder = $this->db->table('efil.m_tbl_high_courts');
        $builder->SELECT("id,hc_name,hc_code,enable_payment_gateway,state_code,enable_e_payment");
        $builder->WHERE('is_active', 'TRUE');
        $builder->WHERE('id', $hc_id);
        $builder->WHERE('parent_hc_id', NULL);
        $builder->orderBy("hc_name", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    function get_bench_court_list($id) {
        $builder = $this->db->table('efil.m_tbl_high_courts');
        $builder->SELECT("id,hc_name,hc_code");
        $builder->WHERE('is_active', 'TRUE');
        $builder->WHERE('parent_hc_id', $id);
        $builder->orderBy("hc_name", "asc");
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_district_list($state_id) {
        $builder = $this->db->table('m_tbl_districts');
        $builder->SELECT("id,ref_m_hc_id,dist_name,dist_code");
        $builder->WHERE('ref_efil.m_tbl_states_id', $state_id);
        $builder->WHERE('display', 'Y');
        $builder->orderBy("dist_name", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    function get_eshtablishment_list($ref_m_distt_id) {
        $builder = $this->db->table('m_tbl_establishments');
        $builder->SELECT("id,estname,est_code,state_code,enable_e_payment");
        $builder->WHERE('ref_m_tbl_districts_id', $ref_m_distt_id);
        $builder->WHERE('display', 'Y');
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            $builder->WHERE('is_live', TRUE);
        }
        $builder->orderBy("estname", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    function get_district_admin_eshtablishment_list($ref_m_distt_id) {
        $builder = $this->db->table('m_tbl_establishments');
        $builder->SELECT("id,estname,est_code,state_code");
        $builder->WHERE('ref_m_tbl_districts_id', $ref_m_distt_id);
        $builder->WHERE('display', 'Y');
        $builder->orderBy("estname", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    function get_eshtablishment_list_master_admin($ref_m_distt_id) {
        $builder = $this->db->table('m_tbl_establishments');
        $builder->SELECT("id,estname,est_code");
        $builder->WHERE('id', $ref_m_distt_id);
        $builder->WHERE('display', 'Y');
        $builder->orderBy("estname", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    function get_high_court_master_list($admin_for_id) {
        $builder = $this->db->table('efil.m_tbl_high_courts');
        $builder->SELECT("id,hc_name,state_code");
        $builder->WHERE('id', $admin_for_id);
        $builder->orderBy("hc_name", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    function get_eshtablishment_list1($ref_m_distt_id) {
        $builder = $this->db->table('tbl_admin_contact');
        $builder->SELECT("admin_for_id");
        $builder->WHERE('ref_m_distt_id', $ref_m_distt_id);
        $subQuery = $builder->getCompiledSelect();
        $new_builder = $this->db->table('m_tbl_establishments');
        $builder->SELECT("id,estname,est_code");
        $builder->WHERE('ref_m_tbl_districts_id', $ref_m_distt_id);
        $builder->whereNotIn('id', $subQuery, FALSE);
        $builder->WHERE('display', 'Y');
        $builder->orderBy("estname", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    function get_eshtablishment_list_new($admin_for_id, $ref_m_distt_id, $id) {
        $sql = "SELECT * FROM m_tbl_establishments 
        WHERE id NOT IN ( SELECT admin_for_id FROM tbl_admin_contact 
        WHERE frm_ref_id NOT IN 
        (SELECT frm_ref_id FROM  tbl_admin_contact as tmp where id = $id AND admin_for_type_id =" . ENTRY_TYPE_FOR_ESTABLISHMENT . " )) 
        AND ref_m_tbl_districts_id  = $ref_m_distt_id";
        $query = $this->db->query($sql);
        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function get_eshtablishment_code($establishment_id) {
        $builder = $this->db->table('m_tbl_establishments');
        $builder->SELECT("id,estname,est_code,enable_payment_gateway,state_code,enable_e_payment");
        $builder->WHERE('id', $establishment_id);
        $builder->WHERE('display', 'Y');
        $builder->orderBy("estname", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    function count_pet_and_res($reg_id) {
        $builder = $this->db->table('tbl_efiling_civil');
        $builder->SELECT("pet_extracount,res_extracount");
        $builder->WHERE('ref_m_efiling_nums_registration_id', $reg_id);
        $query = $builder->get();
        return $query->getResult();
    }

    function get_state_list_ajax($display = NULL) {
        $builder = $this->db->table('efil.m_tbl_state');
        $builder->SELECT("state_id,state");
        if ($display != NULL) {
            $builder->WHERE('display', 'Y');
        }
        $builder->orderBy("state", "asc");
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    function eshtablishment_list_checkbox_edit($ref_m_distt_id) {
        if ($ref_m_distt_id) {
            if (!empty($ref_m_distt_id)) {
                $result = $this->Dropdown_list_model->get_eshtablishment_list($ref_m_distt_id);
                return $result[0]->estname;
            } else {

            }
        }
    }

    function alloted_estab_id($id) {
        $sql = "SELECT ref.admin_for_id 
        FROM tbl_admin_contact tmp
        JOIN tbl_admin_contact ref ON ref.frm_ref_id = tmp.frm_ref_id 
        WHERE tmp.id = $id AND tmp.admin_for_type_id ='" . ENTRY_TYPE_FOR_ESTABLISHMENT . "'";
        $query = $this->db->query($sql);
        if ($query) {
            return $query->getResultArray();
        } else {
            return FALSE;
        }
    }

    function get_advocate_state_code() {
        $builder = $this->db->table('efil.m_tbl_state');
        $builder->SELECT("bar_state_code");
        $builder->orderBy("bar_state_code", "asc");
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function get_users_cases_list() {
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
            $created_by = "sub_created_by=" . $_SESSION['login']['id'];
        } else {
            $created_by = "created_by=" . $_SESSION['login']['id'];
        }
        $sql = "Select DISTINCT CASE WHEN efiling_for_type_id = 1 THEN (SELECT concat(1,'#',id,'#',hc_code,'#',hc_name,' HIGH COURT','#',state_code,'#',enable_e_payment) FROM efil.m_tbl_high_courts hc WHERE hc.id = en.efiling_for_id AND hc.is_active IS TRUE) 
        WHEN efiling_for_type_id = 2 THEN (SELECT concat(2,'#',estab.id,'#',est_code,'#', estname,'#', ref_efil.m_tbl_states_id,'#', ref_m_tbl_districts_id,'#', dist_code,'#', dist_name,'#', state_id,'#', state,'#', openapi_state_code,'#',state_code,'#',enable_e_payment) FROM m_tbl_establishments estab
        JOIN m_tbl_districts dist ON dist.id = estab.ref_m_tbl_districts_id
        JOIN efil.m_tbl_state st ON st.state_id = dist.ref_efil.m_tbl_states_id WHERE estab.id = en.efiling_for_id AND estab.display = 'Y' AND dist.display = 'Y'  ) END AS user_courts
        from tbl_efiling_nums en WHERE " . $created_by;
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return FALSE;
        }
    }

    function get_efiling_no($id) {
        $builder = $this->db->table('tbl_efiling_nums');
        $builder->SELECT('*');
        $builder->WHERE('created_by', $id);
        $builder->WHERE('ref_m_efiled_type_id', E_FILING_TYPE_NEW_CASE);
        $builder->orderBy('efiling_no');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

}