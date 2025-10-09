<?php

namespace App\Models\ContactUs;
use CodeIgniter\Model;



class Contactus_model extends Model {

    function __construct() {
        parent::__construct();
    }

    public function get_contactus_results() {
		$builder = $this->db->table('efil.tbl_admin_contact users');
        $builder->SELECT('users.*');
        $builder->WHERE('is_active', TRUE);
        $subQuery = $builder->getCompiledSelect();
		$builder = $this->db->table("$subQuery ) a");
        $builder->SELECT('a.*, case WHEN (a.admin_for_type_id = ' . E_FILING_FOR_HIGHCOURT . ' ) 
            THEN (SELECT hc_name from efil.m_tbl_high_courts WHERE id = a.admin_for_id) 
            ELSE 
            (SELECT concat( estab_name, \'@@@\', dist_name ,\'@@@\',state )
            FROM efil.m_tbl_state st 
            LEFT JOIN efil.m_tbl_districts dist ON st.state_id = dist.ref_m_tbl_states_id
            LEFT JOIN efil.m_tbl_establishments estab ON dist.id = estab.ref_m_tbl_districts_id
            WHERE estab.id = a.admin_for_id ORDER BY dist_name ,estab_name) END as estab_details', FALSE);
        $subQuery2 = $builder->getCompiledSelect();
		$builder = $this->db->table("($subQuery2 ) b");
        $builder->SELECT('email_id');
        $builder->SELECT("string_agg(id::varchar(101), '||||') as id", FALSE);
        $builder->SELECT("string_agg(estab_details,'||||') as estab_name", FALSE);
        $builder->groupBy("email_id");
        $query = $builder->get();
        return $query->getResult();
    }

    function get_district_admin_contact_results() {
		$builder = $this->db->table('dynamic_users_table');
        $builder->SELECT('*');
        $builder->JOIN('efil.m_tbl_districts dist', 'users.admin_for_id = dist.id', 'LEFT');
        $builder->JOIN('efil.m_tbl_establishments estab', 'estab.ref_m_tbl_districts_id = dist.id', 'LEFT');
        $builder->WHERE('users.is_active', TRUE);
        $builder->WHERE('users.ref_m_usertype_id', USER_DISTRICT_ADMIN);
        $builder->WHERE('estab.id', session()->get('login')['admin_for_id']);
        $query = $builder->get();
        return $query->getResult();
    }

    function get_super_admin_contact_details() {

        if (getSessionData('login')['admin_for_type_id'] == ENTRY_TYPE_FOR_HIGHCOURT) {
			$builder = $this->db->table('dynamic_users_table');
            $$builder->SELECT('*');
            $builder->JOIN('efil.m_tbl_high_courts estab', 'estab.state_code = users.admin_for_id');
            $builder->WHERE('users.is_active', TRUE);
            $builder->WHERE('users.ref_m_usertype_id', USER_SUPER_ADMIN);
            $builder->WHERE('estab.id', session()->get('login')['admin_for_id']);
        } elseif ($_SESSION['login']['admin_for_type_id'] == ENTRY_TYPE_FOR_ESTABLISHMENT) {
			$builder = $this->db->table('dynamic_users_table');
            $builder->SELECT('*');
            $builder->JOIN('efil.m_tbl_establishments estab', 'estab.state_code = users.admin_for_id');
            $builder->WHERE('users.is_active', TRUE);
            $builder->WHERE('users.ref_m_usertype_id', USER_SUPER_ADMIN);
            $builder->WHERE('estab.id', getSessionData('login')['admin_for_id']);
        } elseif ($_SESSION['login']['admin_for_type_id'] == USER_DISTRICT_ADMIN) {
			$builder = $this->db->table('dynamic_users_table');
            $builder->SELECT('*');
            $builder->JOIN('efil.m_tbl_districts estab', 'estab.ref_m_tbl_states_id = users.admin_for_id');
            $builder->WHERE('users.is_active', TRUE);
            $builder->WHERE('users.ref_m_usertype_id', USER_SUPER_ADMIN);
            $builder->WHERE('estab.id', session()->get('login')['admin_for_id']);
        }
        $query = $builder->get();
        return $query->getResult();
    }

    function get_master_admin_contact_details() {

        if ($_SESSION['login']['admin_for_type_id'] == ENTRY_TYPE_FOR_HIGHCOURT) {
			$builder = $this->db->table('dynamic_users_table');
            $builder->SELECT('*');
            $builder->JOIN('efil.m_tbl_high_courts estab', 'estab.id = users.admin_for_id');
            $builder->WHERE('users.is_active', TRUE);
            $builder->WHERE('users.admin_for_type_id', ENTRY_TYPE_FOR_HIGHCOURT);
            $builder->WHERE('users.ref_m_usertype_id', USER_MASTER_ADMIN);
            $builder->WHERE('estab.id', session()->get('login')['admin_for_id']);
        } elseif ($_SESSION['login']['admin_for_type_id'] == ENTRY_TYPE_FOR_ESTABLISHMENT) {
			$builder = $this->db->table('dynamic_users_table');
            $builder->SELECT('*');
            $builder->JOIN('efil.m_tbl_establishments estab', 'estab.id = users.admin_for_id');
            $builder->WHERE('users.is_active', TRUE);
            $builder->WHERE('users.admin_for_type_id', ENTRY_TYPE_FOR_ESTABLISHMENT);
            $builder->WHERE('users.ref_m_usertype_id', USER_MASTER_ADMIN);
            $builder->WHERE('estab.id', getSessionData('login')['admin_for_id']);
        }
        $query = $builder->get();
        return $query->getResult();
    }

}