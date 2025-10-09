<?php

namespace App\Models\AdminDashboard;

use CodeIgniter\Model;

class EfiledCasesModel extends Model {

    function __construct() {
        parent::__construct();
    }

    function get_registration_id($efilno) {
        $builder = $this->db->table('tbl_efiling_nums');
        $builder->SELECT('*');
        $builder->WHERE('efiling_no', $efilno);
        $query = $builder->get();
        if ($query->getNumRows() == 1) {
            $z = $query->getResultArray();
            return $z[0]['registration_id'];
        } else {
            return false;
        }
    }      

    function update_case_status($registration_id, $next_stage, $current_date_n_time) {
        if (!(isset($_SESSION['login']['id']) && !empty($_SESSION['login']['id']))) {
            $approved_by = '000';
        } else {
            $approved_by = $_SESSION['login']['id'];
        }
        $update_data = array(
            'deactivated_on' => $current_date_n_time,
            'is_active' => FALSE,
            'updated_by' => $approved_by,
            'updated_by_ip' => $_SERVER['REMOTE_ADDR']
        );
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('is_active', TRUE);
        $builder->UPDATE($update_data);
        $insert_data = array(
            'registration_id' => $registration_id,
            'stage_id' => $next_stage,
            'activated_on' => $current_date_n_time,
            'is_active' => TRUE,
            'activated_by' => $approved_by,
            'activated_by_ip' => $_SERVER['REMOTE_ADDR']
        );
        if ($this->db->affectedRows() > 0) {
            $builder = $this->db->table('efil.tbl_efiling_num_status');
            $builder->INSERT($insert_data);
            if ($this->db->insertID()) {
                
            }
        }
    }

    function add_defect_message($data) {
        $builder = $this->db->table('tbl_initial_defects');
        $builder->INSERT($data);
        if ($this->db->insertID()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_multi_records_CIS_Status($new_case_records, $efiling_nums_new_case, $current_date_n_time, $is_cron) {
        $case_data_to_update = $new_case_records['case_data_to_update'];
        $defects_to_update = $new_case_records['defects_to_update'];
        $defects_to_insert = $new_case_records['defects_to_insert'];
        $stage_to_update = $new_case_records['stage_to_update'];        
        $this->db->transStart();
        if (!empty($case_data_to_update)) {
            $builder = $this->db->table('efil.tbl_case_details');
            $builder->updateBatch($case_data_to_update, 'registration_id');
        }
        if (!empty($defects_to_update)) {
            $builder = $this->db->table('efil.tbl_initial_defects');
            $builder->updateBatch($defects_to_update, 'initial_defects_id');
        }
        if (!empty($defects_to_insert)) {
            $builder = $this->db->table('efil.tbl_initial_defects'); 
            $builder->insertBatch($defects_to_insert);
        }
        if (!empty($stage_to_update)) {
            foreach ($stage_to_update as $stage) {
                $this->update_case_status($stage['registration_id'], $stage['stage_id'], $current_date_n_time);
            }
        }
        if ($is_cron) {
            $status_detail = array(
                'new_case_efiling_nums' => $efiling_nums_new_case,
                'new_case_records_status' => $new_case_records['new_case_records_count'],
                'case_data_to_update' => $case_data_to_update,
                'defects_to_update' => $defects_to_update,
                'defects_to_insert' => $defects_to_insert,
                'stage_to_update' => $stage_to_update,                
                'current_date_n_time' => $current_date_n_time
            );
            $this->save_cron_status(array('responce' => json_encode($status_detail), 'cron_date' => $current_date_n_time, 'cron_type' => 'eFiling CIS Status', 'remote_ip'=>$_SERVER['REMOTE_ADDR']));
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            $this->db->transComplete();
            return TRUE;
        }
    }

    function update_case_details_from_CIS($registration_id, $case_details, $next_stage, $defect) {
        $this->db->transStart();
        $builder = $this->db->table("tbl_efiling_civil");
        $builder->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $builder->UPDATE($case_details);
        if ($this->db->affectedRows() > 0) {
            $stage_update = $this->Efiled_cases_model->update_case_status($registration_id, $next_stage);
            if ($stage_update) {
                if (!empty($defect) && $defect != NULL) {
                    $update_defects = $this->Efiled_cases_model->add_defect_message($defect);
                    if ($update_defects) {
                        $this->db->transComplete();
                    }
                } else {
                    $this->db->transComplete();
                }
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    public function update_case_status_on_cino($registration_id, $curr_stage, $next_stage, $scrutiny_date, $obj_rej = NULL) {
        $this->db->transStart();
        if (isset($registration_id) && !empty($registration_id)) {
            if ($registration_id != '') {
                $update_data = array(
                    'deactivated_on' => date('Y-m-d H:i:s'),
                    'is_active' => FALSE,
                    'updated_by' => $_SESSION['login']['id'],
                    'updated_by_ip' => $_SERVER['REMOTE_ADDR']
                );
                $builder = $this->db->table('efil.tbl_efiling_num_status');
                $builder->WHERE('registration_id', $registration_id);
                $builder->WHERE('is_active', TRUE);
                $builder->UPDATE($update_data);
                $insert_data = array(
                    'registration_id' => $registration_id,
                    'stage_id' => $next_stage,
                    'activated_on' => date('Y-m-d H:i:s'),
                    'is_active' => TRUE,
                    'activated_by' => $_SESSION['login']['id'],
                    'activated_by_ip' => $_SERVER['REMOTE_ADDR']
                );
                if ($this->db->affectedRows() > 0) {
                    $builder = $this->db->table('efil.tbl_efiling_num_status');
                    $builder->INSERT($insert_data);
                    if ($this->db->insertID()) {
                        if ($curr_stage == I_B_Defects_Cured_Stage) {
                            $initial_defect_exits = FALSE;
                            $initial_defects_update_status = FALSE;
                            $builder = $this->db->table('tbl_initial_defects');
                            $builder->SELECT('max(initial_defects_id) max_id');
                            $builder->WHERE('registration_id', $registration_id);
                            $builder->WHERE('is_defect_cured', TRUE);
                            $builder->WHERE('is_approved', FALSE);
                            $query = $builder->get();
                            $query_result = $query->getResult();
                            $initial_defect_max_id = $query_result[0]->max_id;
                            if ($initial_defect_max_id != '' && $initial_defect_max_id > 0) {
                                $initial_defect_exits = TRUE;
                                if ($next_stage == E_Filed_Stage) {
                                    $update_defect_data = array(
                                        'is_approved' => TRUE,
                                        'scrutiny_date' => $scrutiny_date,
                                        'approve_date' => date('Y-m-d H:i:s'),
                                        'approved_by' => $_SESSION['login']['id']
                                    );
                                } elseif ($next_stage == I_B_Defected_Stage || $next_stage == I_B_Rejected_Stage) {
                                    $update_defect_data = array(
                                        'is_approved' => TRUE,
                                        'scrutiny_date' => $scrutiny_date,
                                        'still_defective' => TRUE,
                                        'approve_date' => date('Y-m-d H:i:s'),
                                        'approved_by' => $_SESSION['login']['id']
                                    );
                                }
                                $builder = $this->db->table('tbl_initial_defects');
                                $builder->WHERE('registration_id', $registration_id);
                                $builder->WHERE('is_defect_cured', TRUE);
                                $builder->WHERE('is_approved', FALSE);
                                $builder->WHERE('initial_defects_id', $initial_defect_max_id);
                                $builder->UPDATE($update_defect_data);
                                if ($this->db->affectedRows() > 0) {
                                    $initial_defects_update_status = TRUE;
                                }
                            }
                        }
                        if ($curr_stage == I_B_Defects_Cured_Stage && $initial_defect_exits && $initial_defects_update_status) {
                            if ($obj_rej != NULL) {
                                $obj_rej['registration_id'] = $registration_id;
                                $builder = $this->db->table('tbl_initial_defects');
                                $builder->INSERT($obj_rej);
                                if ($this->db->insertID()) {
                                    $this->db->transComplete();
                                    return TRUE;
                                }
                            } else {
                                $this->db->transComplete();
                                return TRUE;
                            }
                        } elseif ($curr_stage == I_B_Approval_Pending_Admin_Stage) {
                            if ($obj_rej != NULL) {
                                $obj_rej['registration_id'] = $registration_id;
                                $builder = $this->db->table('tbl_initial_defects');
                                $builder->INSERT($obj_rej);
                                if ($this->db->insertID()) {
                                    $this->db->transComplete();
                                    return TRUE;
                                }
                            } else {
                                $this->db->transComplete();
                                return TRUE;
                            }
                        } else {
                            return FALSE;
                        }
                    }
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
    
    public function save_cron_status($data) {
        $builder = $this->db->table('cron_for_cis_log');
        $builder->INSERT($data);
        if ($this->db->insertID()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}