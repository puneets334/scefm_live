<?php

namespace App\Models\UserActions;

use CodeIgniter\Model; 
class UserActionsModel extends Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function finalSubmit($registration_id, $next_stage) {
        $curr_time = date('Y-m-d H:i:s');
        $status = FALSE;
        $this->db->transStart();
        if ($next_stage == Initial_Approaval_Pending_Stage) {
            $status = $this->assignEfilingNumber($registration_id, session()->get('estab_details')['efiling_for_type_id'], session()->get('estab_details')['efiling_for_id'], $curr_time);
        }
        if ($next_stage == Initial_Defects_Cured_Stage) {
            $status = $this->updateDefects($registration_id, $curr_time);
        }
        if ($next_stage == I_B_Defected_Stage) {
            $status = TRUE;
        }
        if ($status) {
            $status = $this->update_EfilingNum_NextStage($registration_id, $next_stage, $curr_time);
            if ($status) {
                $this->db->transComplete();
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function trashSubmit($registration_id, $next_stage) {
        $curr_time = date('Y-m-d H:i:s');
        $status = FALSE;
        $status = $this->update_EfilingNum_NextStage($registration_id, $next_stage, $curr_time);
        if ($status) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function updateDefects($registration_id, $curr_time) {
        $update_def = array(
            'is_active' => FALSE,
            'is_defect_cured' => TRUE,
            'defect_cured_date' => $curr_time,
            'updated_by' => session()->get('login')['id'],
            'ip_address' => getClientIP()
        );
        $builder = $this->db->table('efil.tbl_initial_defects');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('is_active', TRUE);
        $builder->SET($update_def);
        $builder->UPDATE();
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_EfilingNum_NextStage($registration_id, $next_stage, $curr_time) {
        $update_data = array(
            'deactivated_on' => $curr_time,
            'is_active' => FALSE,
            'updated_by' => session()->get('login')['id'],
            'updated_by_ip' => getClientIP()
        );
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('is_active', TRUE);
        $builder->SET($update_data);
        $builder->UPDATE();
        if ($this->db->affectedRows() > 0) {
            $insert_data = array(
                'registration_id' => $registration_id,
                'stage_id' => $next_stage,
                'activated_on' => $curr_time,
                'is_active' => TRUE,
                'activated_by' => session()->get('login')['id'],
                'activated_by_ip' => getClientIP()
            );
            $builder = $this->db->table('efil.tbl_efiling_num_status');
            $builder->INSERT($insert_data);
            if ($this->db->insertId()) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    function assign_efiling_number($registration_id, $efiling_for_type_id, $efiling_for_id, $curr_time) {
        $query = "SELECT users.id,  users.ref_m_usertype_id, users.admin_for_type_id, users.admin_for_id, count(allocated_on) efiling_num_count
            FROM efil.tbl_users users
            LEFT JOIN efil.tbl_efiling_nums en ON en.allocated_to = users.id AND to_char(en.allocated_on,'YYYY-MM-DD')::date ='" . date('Y-m-d') . "'
            WHERE users.admin_for_type_id ='" . $efiling_for_type_id . "' AND users.admin_for_id ='" . $efiling_for_id . "' AND
            users.is_deleted IS FALSE AND
            users.ref_m_usertype_id IN(" . USER_ADMIN . "," . USER_ACTION_ADMIN . ")                  
            GROUP BY users.id,users.ref_m_usertype_id,users.admin_for_type_id,users.admin_for_id ORDER BY efiling_num_count";
        $query = $this->db->query($query);
        if ($query->getNumRows() >= 1) {
            $builder = $this->db->table('efil.tbl_efiling_nums');
            $assign_to = $query->getResultArray();
            $nums_data_update = array(
                'allocated_to' => $assign_to[0]['id'],
                'allocated_on' => $curr_time
            );
            $builder->WHERE('registration_id', $registration_id);
            $builder->WHERE('is_active', TRUE);
            $builder->SET($nums_data_update);
            $builder->UPDATE();
            if ($this->db->affectedRows() > 0) {
                $data = array(
                    'registration_id' => $registration_id,
                    'admin_id' => $assign_to[0]['id'],
                    'allocated_on' => $curr_time,
                    'updated_by' => $_SESSION['login']['id'],
                    'updated_on' => $curr_time,
                    'update_ip' => getClientIP(),
                    'reason_to_allocate' => NULL
                );
                $builder->INSERT($data);
                if ($this->db->insertId()) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function get_share_email_details($registration_id) {
        $builder = $this->db->table('efil.tbl_doc_share_email');
        $builder->SELECT("*");
        $builder->WHERE('registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

}