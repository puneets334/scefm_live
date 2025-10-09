<?php

namespace App\Models\GetCISStatus;
use CodeIgniter\Model;

class GetCISStatusModel extends Model {

    function __construct() {
        parent::__construct();
    }

    function get_icmis_objections_list($registration_id) {
        $builder = $this->db->table('efil.tbl_icmis_objections');
        $builder->SELECT('*');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('obj_removed_date', NULL);
        $builder->WHERE('is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }

    function get_efiled_docs_list($registration_id, $ia_only) {
        $ia_doc_type = 8;
        $builder = $this->db->table('efil.tbl_efiled_docs ed');
        $builder->SELECT('ed.doc_id');
        $builder->JOIN('icmis.docmaster dm', 'ed.doc_type_id = dm.doccode AND ed.sub_doc_type_id = dm.doccode1 and dm.display!=\'N\'');
        $builder->WHERE('ed.registration_id', $registration_id);
        if ($ia_only) {
            $builder->WHERE('ed.doc_type_id', $ia_doc_type);
        }
        $builder->WHERE('ed.is_active', TRUE);
        $builder->WHERE('ed.is_deleted', FALSE);
        $builder->orderBy('ed.index_no');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function update_icmis_case_status($registration_id, $next_stage, $curr_dt, $case_details, $objections_insert, $objections_update, $documents_update, $efiling_type = null) {
        $this->db->transStart();
        if (isset($case_details) && !empty($case_details)) {
            if (isset($efiling_type) && !empty($efiling_type) && $efiling_type == 'new_case') {
                if (!empty($case_details)) {
                    $builder = $this->db->table('efil.tbl_case_details');
                    $builder->WHERE('registration_id', $registration_id);
                    $builder->update($case_details);
                } else {
                    $builder = $this->db->table('efil.tbl_case_details');
                    $builder->WHERE('registration_id', $registration_id);
                    $builder->updateBatch($case_details, 'registration_id');
                }
            } else if (!isset($efiling_type) && empty($efiling_type)) {
                $builder = $this->db->table('efil.tbl_case_details');
                $builder->WHERE('registration_id', $registration_id);
                $builder->updateBatch($case_details, 'registration_id');
            } else  if (isset($case_details) && !empty($case_details) && isset($efiling_type) && !empty($efiling_type) && $efiling_type == 'caveat') {
                $builder = $this->db->table('public.tbl_efiling_caveat');
                $builder->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
                $builder->update($case_details);
            }
        }
        if (isset($objections_insert) && !empty($objections_insert)) {
            $builder = $this->db->table('efil.tbl_icmis_objections');
            $builder->insertBatch($objections_insert);
        }
        if (isset($objections_update) && !empty($objections_update)) {
            $builder = $this->db->table('efil.tbl_icmis_objections');
            $builder->updateBatch($objections_update, 'id');
        }
        if (isset($documents_update) && !empty($documents_update)) {
            $builder = $this->db->table('efil.tbl_efiled_docs');
            $builder->updateBatch($documents_update, 'doc_id');
        }
        if ($next_stage) {
            $res = $this->update_next_stage($registration_id, $next_stage, $curr_dt);
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            $this->db->transComplete();
            return TRUE;
        }
    }

    function update_misc_doc_ia_status($registration_id, $next_stage, $curr_dt, $objections_insert, $objections_update, $documents_update) {
        $this->db->transStart();
        if (isset($objections_insert) && !empty($objections_insert)) {
            $builder = $this->db->table('efil.tbl_icmis_objections');
            $builder->insertBatch($objections_insert);
        }
        if (isset($objections_update) && !empty($objections_update)) {
            $builder = $this->db->table('efil.tbl_icmis_objections');
            $builder->updateBatch($objections_update, 'id');
        }
        if (isset($documents_update) && !empty($documents_update)) {
            $builder = $this->db->table('efil.tbl_efiled_docs');
            $builder->updateBatch($documents_update, 'doc_id');
        }
        if ($next_stage) {
            $this->update_next_stage($registration_id, $next_stage, $curr_dt);
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            $this->db->transComplete();
            return TRUE;
        }
    }

    function update_next_stage($registration_id, $next_stage, $curr_dt, $login_id = null) {
        $update_data = array(
            'deactivated_on' => $curr_dt,
            'is_active' => FALSE,
            'updated_by' => !empty($login_id) ? $login_id : null,
            'updated_by_ip' => $_SERVER['REMOTE_ADDR']
        );
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('is_active', TRUE);
        $builder->UPDATE($update_data);
        $insert_data = array(
            'registration_id' => $registration_id,
            'stage_id' => $next_stage,
            'activated_on' => $curr_dt,
            'is_active' => TRUE,
            'activated_by' => !empty($login_id) ? $login_id : null,
            'activated_by_ip' => $_SERVER['REMOTE_ADDR']
        );
        $builder->INSERT($insert_data);
        if ($this->db->insertID()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_stage_update_timestamp($registration_id, $stage_id) {
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->SELECT('activated_on');
        $builder->WHERE('stage_id', $stage_id);
        $builder->WHERE("registration_id", $registration_id);
        $builder->WHERE('is_active', TRUE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function getStageUpdateTmestampCaseCaveat($registration_id, $stage_id, $diaryStatus) {
        $output = false;
        if (isset($registration_id) && !empty($registration_id) && isset($stage_id) && !empty($stage_id) && isset($diaryStatus) && !empty($diaryStatus)) {
            $builder = $this->db->table('efil.tbl_efiling_num_status');
            switch ($diaryStatus) {
                case 'new_diary':
                    $builder->SELECT('activated_on');
                    $builder->WHERE('stage_id', $stage_id);
                    $builder->WHERE("registration_id", $registration_id);
                    $builder->WHERE('is_active', TRUE);
                    $query = $builder->get();
                    if ($query->getNumRows() >= 1) {
                        $output = $query->getResultArray();
                    } else {
                        $output = false;
                    }
                    break;
                case 'exist_diary':
                    $builder->SELECT('activated_on');
                    $builder->WHERE('stage_id', Transfer_to_IB_Stage);
                    $builder->WHERE("registration_id", $registration_id);
                    $builder->WHERE('is_active', false);
                    $query = $builder->get();
                    if ($query->getNumRows() >= 1) {
                        $output = $query->getResultArray();
                    } else {
                        $builder->SELECT('activated_on');
                        $builder->WHERE('stage_id', $stage_id);
                        $builder->WHERE("registration_id", $registration_id);
                        $builder->WHERE('is_active', TRUE);
                        $query = $builder->get();
                        if ($query->getNumRows() >= 1) {
                            $output = $query->getResultArray();
                        } else {
                            $output = false;
                        }
                    }
                    break;
                default:
                $output = false;
            }
        }
        return $output;
    }

    function update_case_status1($registration_id, $next_stage, $current_date_n_time) {
        if (!(isset(getSessionData('login')['id']) && !empty(getSessionData('login')['id']))) {
            $approved_by = '000';
        } else {
            $approved_by = getSessionData('login')['id'];
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
            $this->save_cron_status(array('responce' => json_encode($status_detail), 'cron_date' => $current_date_n_time, 'cron_type' => 'eFiling CIS Status', 'remote_ip' => $_SERVER['REMOTE_ADDR']));
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
                    'updated_by' => getSessionData('login')['id'],
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
                    'activated_by' => getSessionData('login')['id'],
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
                                        'approved_by' => getSessionData('login')['id']
                                    );
                                } elseif ($next_stage == I_B_Defected_Stage || $next_stage == I_B_Rejected_Stage) {
                                    $update_defect_data = array(
                                        'is_approved' => TRUE,
                                        'scrutiny_date' => $scrutiny_date,
                                        'still_defective' => TRUE,
                                        'approve_date' => date('Y-m-d H:i:s'),
                                        'approved_by' => getSessionData('login')['id']
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
    
    public function countPendingScrutiny($stage_ids, $total = null, $admin_for_type_id = null, $admin_for_id = null) {
        $builder = $this->db->table('efil.tbl_efiling_nums as en');
        if (isset($total) && !empty($total)) {
            $builder->SELECT('count(en.efiling_no) as total');
        } else {
            $builder->SELECT(array(
                'en.allocated_to', 'en.efiling_for_type_id', 'en.efiling_for_id', 'en.ref_m_efiled_type_id',
                'en.efiling_no', 'en.efiling_year', 'en.registration_id', 'en.allocated_on',
                'et.efiling_type', 'cs.stage_id', 'sc_case.diary_no', 'sc_case.diary_year'
            ));
        }
        $builder->JOIN('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id');
        $builder->JOIN('public.tbl_efiling_caveat as ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id', 'left');
        $builder->JOIN('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $builder->JOIN('efil.tbl_case_details as new_case_cd', 'en.registration_id = new_case_cd.registration_id', 'left');
        $builder->JOIN('efil.tbl_misc_docs_ia as mdia', 'en.registration_id = mdia.registration_id', 'left');
        $builder->JOIN('efil.tbl_sci_cases as sc_case', 'sc_case.diary_no=mdia.diary_no AND sc_case.diary_year = mdia.diary_year', 'left');
        $builder->JOIN('efil.tbl_users users', 'users.id=en.created_by', 'left');
        $builder->WHERE('cs.is_active', 'TRUE');
        $builder->WHERE('en.is_active', 'TRUE');
        $where = '(en.efiling_for_type_id=' . $admin_for_type_id . ' or en.efiling_for_type_id = ' . E_FILING_TYPE_CAVEAT . ')';
        $builder->WHERE($where);
        $builder->WHERE('en.efiling_for_id', $admin_for_id);
        $builder->whereIn('cs.stage_id', $stage_ids);
        $builder->WHERE('en.allocated_to !=', 0);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

}