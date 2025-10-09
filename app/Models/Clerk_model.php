<?php
namespace App\Models;

use CodeIgniter\Model;
class Clerk_model extends Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function add_clerk_user($data, $clerk_name) {
        $this->db->trans_start();
        $this->db_2 = $this->load->database(unserialize(dynamic_users_table_connection), TRUE);
        $this->db_2->INSERT('users', $data);
        $user_id = $this->db_2->insert_id();

        if ($user_id) {


            $parent_ids = $_SESSION['login']['id'];



            $clerk_data = array('clerk_id' => $user_id, 'parent_ids' => $parent_ids, 'clerk_name' => $clerk_name);
            $this->db->INSERT('m_tbl_clerks', $clerk_data);

            $this->db->trans_complete();
        }
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function get_clerk_users($clerk_user_id) {

        $sql = "SELECT * FROM m_tbl_clerks cl 
                 JOIN  " . dynamic_users_table . " on users.id=cl.clerk_id
                 WHERE parent_ids=$clerk_user_id Order by created_datetime desc";

        $query = $this->db->query($sql);

        if ($query->num_rows() >= 1) {
            $result = $query->result_array();

            return $result;
        } else {
            return FALSE;
        }
    }

    function get_clerk_details($clerk_user_id) {

        $sql = "SELECT * FROM m_tbl_clerks cl 
                 JOIN  " . dynamic_users_table . " on users.id=cl.clerk_id
                  WHERE users.id=" . $clerk_user_id;

        $query = $this->db->query($sql);

        if ($query->num_rows() >= 1) {
            $result = $query->result_array();

            return $result;
        } else {
            return FALSE;
        }
    }

    function update_clerk_user($clerk_id, $data, $clerk_name) {

        $this->db_2 = $this->load->database(unserialize(dynamic_users_table_connection), TRUE);
        $this->db_2->WHERE('id', $clerk_id);
        $this->db_2->UPDATE('users', $data);

        if ($this->db_2->affected_rows() > 0) {
            $data_clerk = array('clerk_name' => $clerk_name);
            $this->db->WHERE('clerk_id', $clerk_id);
            $this->db->UPDATE('m_tbl_clerks', $data_clerk);
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    function check_email_present($email, $dep_id) {
        $this->db->SELECT('*');
        $this->db->FROM(dynamic_users_table);
        $this->db->WHERE('users.emailid', $email);
        $this->db->WHERE('users.id!=', $dep_id);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return TRUE;
        } else {
            return false;
        }
    }

    function check_mobno_present($mobile, $dep_id) {
        $this->db->SELECT('*');
        $this->db->FROM(dynamic_users_table);
        $this->db->WHERE('users.moblie_number', $mobile);
        $this->db->WHERE('users.id!=', $dep_id);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return TRUE;
        } else {
            return false;
        }
    }

    function get_advocate($clerk_user_id) {

        $sql = "SELECT users.id,users.first_name,users.last_name FROM " . dynamic_users_table . "  
                 JOIN  m_tbl_clerks cl on users.id=cl.parent_ids
                 WHERE cl.clerk_id=$clerk_user_id  Order by created_datetime desc";

        $query = $this->db->query($sql);

        if ($query->num_rows() >= 1) {
            $result = $query->result_array();

            return $result;
        } else {
            return FALSE;
        }
    }

}
