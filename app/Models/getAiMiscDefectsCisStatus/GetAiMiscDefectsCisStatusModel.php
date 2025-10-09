<?php

namespace App\Models\getAiMiscDefectsCisStatus;
use CodeIgniter\Model;
use Config\Database;

class GetAiMiscDefectsCisStatusModel extends Model {

    function __construct() {
        parent::__construct();
    }

    function get_icmis_ai_misc_objections_list($registration_id) {
        $builder = $this->db->table('efil.tbl_icmis_ai_docs_objections');
        $builder->select('*')
            ->where('registration_id', $registration_id)
            ->where('obj_removed_date', null)
            ->where('is_deleted', false);
        $query = $builder->get();
        if ($query->getNumRows()>= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_efiled_ai_misc_docs_list($registration_id, $ia_only) {
        $ia_doc_type = 8;
        $builder = $this->db->table('efil.tbl_efiled_docs AS ed');
        $builder->select('ed.doc_id')
            ->join('icmis.docmaster AS dm', 'ed.doc_type_id = dm.doccode AND ed.sub_doc_type_id = dm.doccode1 AND dm.display != \'N\'')
            ->where('ed.registration_id', $registration_id)
            ->where('ed.is_active', TRUE)
            ->where('ed.is_deleted', FALSE)
            ->orderBy('ed.index_no');
        if ($ia_only) {
            $builder->where('ed.doc_type_id', $ia_doc_type);
        }
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function update_icmis_case_status($registration_id, $next_stage, $curr_dt, $case_details, $objections_insert, $objections_update, $documents_update,$efiling_type=null) {
        $this->db->transBegin();
        if (!empty($case_details)) {
            if ($efiling_type === 'new_case') {
                if (count($case_details) === 1) {
                    $this->db->table('efil.tbl_case_details')
                        ->where('registration_id', $registration_id)
                        ->update($case_details[0]);
                } else {
                    $this->db->table('efil.tbl_case_details')
                        ->updateBatch($case_details, 'registration_id');
                }
            } elseif ($efiling_type === 'caveat') {
                $this->db->table('public.tbl_efiling_caveat')
                    ->where('ref_m_efiling_nums_registration_id', $registration_id)
                    ->update($case_details);
            } else {
                $this->db->table('efil.tbl_case_details')
                    ->updateBatch($case_details, 'registration_id');
            }
        }
        if (!empty($objections_insert)) {
            $this->db->table('efil.tbl_icmis_ai_docs_objections')->insertBatch($objections_insert);
        }
        if (!empty($objections_update)) {
            $this->db->table('efil.tbl_icmis_ai_docs_objections')->updateBatch($objections_update, 'id');
        }
        if (!empty($documents_update)) {
            $this->db->table('efil.tbl_efiled_docs')->updateBatch($documents_update, 'doc_id');
        }
        if ($next_stage) {
            $res = $this->update_next_stage($registration_id, $next_stage, $curr_dt);
        }
        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            return false;
        }
        $this->db->transCommit();
        return true;     
    }
    
    function update_misc_doc_ia_status($registration_id, $next_stage, $curr_dt, $objections_insert, $objections_update, $documents_update) {
        $this->db->transBegin();
        if (!empty($objections_insert)) {
            $this->db->table('efil.tbl_icmis_ai_docs_objections')->insertBatch($objections_insert);
        }
        if (!empty($objections_update)) {
            $this->db->table('efil.tbl_icmis_ai_docs_objections')->updateBatch($objections_update, 'id');
        }
        if (!empty($documents_update)) {
            $this->db->table('efil.tbl_efiled_docs')->updateBatch($documents_update, 'doc_id');
        }
        if ($next_stage) { 
            $this->update_next_stage($registration_id, $next_stage, $curr_dt);
        }
        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            return false;
        }
        $this->db->transCommit();
        return true;
    }

    function update_next_stage($registration_id, $next_stage, $curr_dt) {
        $update_data = [
            'deactivated_on' => $curr_dt,
            'is_active'      => FALSE,
            'updated_by'     => $_SESSION['login']['id'],
            'updated_by_ip'  => $_SERVER['REMOTE_ADDR']
        ];
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->where('registration_id', $registration_id)
            ->where('is_active', true)
            ->update($update_data);
        $insert_data = [
            'registration_id' => $registration_id,
            'stage_id'        => $next_stage,
            'activated_on'    => $curr_dt,
            'is_active'       => TRUE,
            'activated_by'    => $_SESSION['login']['id'],
            'activated_by_ip' => $_SERVER['REMOTE_ADDR']
        ];
        $this->db->table('efil.tbl_efiling_num_status')->insert($insert_data);
        if ($this->db->insertID()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_ai_misc_stage_and_registration_id($efiling_no) {
        $builder = $this->db->table('efil.tbl_efiling_nums as en');
        $builder->select([
            'users.first_name as filed_by',
            'users.userid',
            'users.aor_code',
            'en.created_by',
            'cs.stage_id',
            'en.registration_id',
            'en.ref_m_efiled_type_id',
            'en.efiling_no',
            'cs.activated_on',
            'en.efiling_for_id',
            'efiling_type',
            'mdia.diary_no',
            'mdia.diary_year',
            'CONCAT(mdia.diary_no, mdia.diary_year) as diary_no',
        ]);
        $builder->join('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id');
        $builder->join('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id', 'left'); 
        $builder->join('efil.tbl_misc_docs_ia as mdia', 'en.registration_id = mdia.registration_id');
        $builder->join('efil.tbl_sci_cases as sc_case', 'sc_case.diary_no=mdia.diary_no AND sc_case.diary_year = mdia.diary_year', 'left'); 
        $builder->join('efil.tbl_users users', 'users.id=en.created_by', 'left'); 
        $builder->where('cs.is_active', true);
        $builder->where('en.is_active', true);
        $builder->where('mdia.diary_no is not null');
        $builder->where('mdia.diary_year is not null');
        $builder->where('en.efiling_no', $efiling_no);
        $builder->whereIn('cs.stage_id', [I_B_Approval_Pending_Admin_Stage, I_B_Defected_Stage, IA_E_Filed, Document_E_Filed]);
        $query = $builder->get();
        return $query->getResultArray();
        return $result;
    }

    function get_ai_misc_stage_update_timestamp($registration_id, $stage_id) {
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->select('activated_on')
            ->whereIn('stage_id', $stage_id)
            ->where('registration_id', $registration_id)
            ->where('is_active', true);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function getStageUpdateTmestampCaseCaveat($registration_id, $stage_id,$diaryStatus) {
        $output = false;
        if(isset($registration_id) && !empty($registration_id) && isset($stage_id) && !empty($stage_id) && isset($diaryStatus) && !empty($diaryStatus)) {
            switch($diaryStatus) {
                case 'new_diary' :
                    $builder = $this->db->table('efil.tbl_efiling_num_status');
                    $builder->select('activated_on')
                        ->where('stage_id', $stage_id)
                        ->where('registration_id', $registration_id)
                        ->where('is_active', true);
                    $query = $builder->get();
                    if ($query->getNumRows() >= 1) {
                       $output = $query->getRowArray(); 
                    } else {
                       $output = false;
                    }
                    break;
                case 'exist_diary' :
                    $builder = $this->db->table('efil.tbl_efiling_num_status');
                    $builder->select('activated_on')
                        ->where('stage_id', Transfer_to_IB_Stage)
                        ->where('registration_id', $registration_id)
                        ->where('is_active', false);            
                    $query = $builder->get();
                    if ($query->getNumRows() >= 1) {
                        $output = $query->getResultArray();
                    } else {
                        $builder = $this->db->table('efil.tbl_efiling_num_status');
                        $builder->select('activated_on')
                            ->where('stage_id', $stage_id)
                            ->where('registration_id', $registration_id)
                            ->where('is_active', true);
                        $query = $builder->get();
                        if ($query->getNumRows() >= 1) {
                            $output = $query->getRowArray(); 
                        } else {
                            $output = false;
                        }
                    }
                    break;
                default :
                $output =false;
            }
        }
        return $output;
    }

    function update_case_status1($registration_id, $next_stage, $current_date_n_time) {
        if (!(isset($_SESSION['login']['id']) && !empty($_SESSION['login']['id']))) {
            $approved_by = '000';
        } else {
            $approved_by = $_SESSION['login']['id'];
        }
        $update_data = [
            'deactivated_on' => $current_date_n_time,
            'is_active'      => FALSE,
            'updated_by'     => $approved_by,
            'updated_by_ip'  => $_SERVER['REMOTE_ADDR']
        ];
        $this->db->table('efil.tbl_efiling_num_status')
            ->where('registration_id', $registration_id)
            ->where('is_active', TRUE)
            ->update($update_data);
        $insert_data = [
            'registration_id' => $registration_id,
            'stage_id'        => $next_stage,
            'activated_on'    => $current_date_n_time,
            'is_active'       => TRUE,
            'activated_by'    => $approved_by,
            'activated_by_ip' => $_SERVER['REMOTE_ADDR']
        ];
        if ($this->db->affectedRows() > 0) {
            $this->db->table('efil.tbl_efiling_num_status')->insert($insert_data);
        }
    }

    function add_defect_message($data) {
        $this->db->table('tbl_initial_defects')->insert($data);
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
        $this->db->transBegin();
        if (!empty($case_data_to_update)) {
            $this->db->table('efil.tbl_case_details')->updateBatch($case_data_to_update, 'registration_id');
        }
        if (!empty($defects_to_update)) {
            $this->db->table('efil.tbl_initial_defects')->updateBatch($defects_to_update, 'initial_defects_id');
        }
        if (!empty($defects_to_insert)) {
            $this->db->table('efil.tbl_initial_defects')->insertBatch($defects_to_insert);
        }
        if (!empty($stage_to_update)) {
            foreach ($stage_to_update as $stage) {
                $this->update_case_status($stage['registration_id'], $stage['stage_id'], $current_date_n_time);
            }
        }
        if ($is_cron) {
            $status_detail = [
                'new_case_efiling_nums'   => $efiling_nums_new_case,
                'new_case_records_status' => $new_case_records['new_case_records_count'],
                'case_data_to_update'     => $case_data_to_update,
                'defects_to_update'       => $defects_to_update,
                'defects_to_insert'       => $defects_to_insert,
                'stage_to_update'         => $stage_to_update,
                'current_date_n_time'     => $current_date_n_time
            ];
            $this->save_cron_status([
                'responce' => json_encode($status_detail), 
                'cron_date' => $current_date_n_time, 
                'cron_type' => 'eFiling CIS Status', 'remote_ip' => $_SERVER['REMOTE_ADDR']
            ]);
        }
        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            return false;
        }
        $this->db->transCommit();
        return true;
    }

    function update_case_details_from_CIS($registration_id, $case_details, $next_stage, $defect) {
        $this->db->transBegin();
        $this->db->table('tbl_efiling_civil')
            ->where('ref_m_efiling_nums_registration_id', $registration_id)
            ->update($case_details);
        if ($this->db->affectedRows() > 0) {
            $stage_update = $this->Efiled_cases_model->update_case_status($registration_id,$next_stage);
            if ($stage_update) {
                if (!empty($defect)) {
                    $update_defects = $this->Efiled_cases_model->add_defect_message($defect);
                    if ($update_defects) {
                        $this->db->transCommit();
                        return true;
                    }
                } else {
                    $this->db->transCommit();
                    return true;
                }
            }
        }
        $this->db->transRollback();
        return false;
    }

    public function update_case_status_on_cino($registration_id, $curr_stage, $next_stage, $scrutiny_date, $obj_rej = NULL) {
        $this->db->transBegin();
        if (isset($registration_id) && !empty($registration_id)) {
            if ($registration_id != '') {
                $update_data = [
                    'deactivated_on' => date('Y-m-d H:i:s'),
                    'is_active'      => FALSE,
                    'updated_by'     => $_SESSION['login']['id'],
                    'updated_by_ip'  => $_SERVER['REMOTE_ADDR']
                ];
                $this->db->table('efil.tbl_efiling_num_status')
                    ->where('registration_id', $registration_id)
                    ->where('is_active', true)
                    ->update($update_data);
                $insert_data = [
                    'registration_id' => $registration_id,
                    'stage_id'        => $next_stage,
                    'activated_on'    => date('Y-m-d H:i:s'),
                    'is_active'       => TRUE,
                    'activated_by'    => $_SESSION['login']['id'],
                    'activated_by_ip' => $_SERVER['REMOTE_ADDR']
                ];
                if ($this->db->affectedRows() > 0) {
                    $this->db->table('efil.tbl_efiling_num_status')->insert($insert_data);
                    if ($this->db->insertID()) {
                        if ($curr_stage == I_B_Defects_Cured_Stage) {
                            $initial_defect_exits = FALSE;
                            $initial_defects_update_status = FALSE;
                            $builder = $this->db->table('tbl_initial_defects');
                            $builder->select('MAX(initial_defects_id) as max_id')
                                ->where('registration_id', $registration_id)
                                ->where('is_defect_cured', true)
                                ->where('is_approved', false);
                            $query = $builder->get();
                            $query_result = $query->getResult();
                            $initial_defect_max_id = $query_result[0]->max_id;
                            if ($initial_defect_max_id != '' && $initial_defect_max_id > 0) {
                                $initial_defect_exits = TRUE;
                                if ($next_stage == E_Filed_Stage) {
                                    $update_defect_data = [
                                        'is_approved'   => TRUE,
                                        'scrutiny_date' => $scrutiny_date,
                                        'approve_date'  => date('Y-m-d H:i:s'),
                                        'approved_by'   => $_SESSION['login']['id']
                                    ];
                                } elseif ($next_stage == I_B_Defected_Stage || $next_stage == I_B_Rejected_Stage) {
                                    $update_defect_data = [
                                        'is_approved'     => TRUE,
                                        'scrutiny_date'   => $scrutiny_date,
                                        'still_defective' => TRUE,
                                        'approve_date'    => date('Y-m-d H:i:s'),
                                        'approved_by'     => $_SESSION['login']['id']
                                    ];
                                }
                                $this->db->table('tbl_initial_defects')
                                    ->where('registration_id', $registration_id)
                                    ->where('is_defect_cured', true)
                                    ->where('is_approved', false)
                                    ->where('initial_defects_id', $initial_defect_max_id)
                                    ->update($update_defect_data);
                                if ($this->db->affectedRows() > 0) {
                                    $initial_defects_update_status = TRUE;
                                }
                            }
                        }
                        if ($curr_stage == I_B_Defects_Cured_Stage && $initial_defect_exits && $initial_defects_update_status) {
                            if ($obj_rej != NULL) {
                                $obj_rej['registration_id'] = $registration_id;
                                $this->db->table('tbl_initial_defects')->insert($obj_rej);
                                if ($this->db->insertID()) {
                                    $this->db->transCommit();
                                    return TRUE;
                                }
                            } else {
                                $this->db->transCommit();
                                return TRUE;
                            }
                        } elseif ($curr_stage == I_B_Approval_Pending_Admin_Stage) {                            
                            if ($obj_rej != NULL) {
                                $obj_rej['registration_id'] = $registration_id;
                                $this->db->table('tbl_initial_defects')->insert($obj_rej);
                                if ($this->db->insertID()) {
                                    $this->db->transCommit();
                                    return TRUE;
                                }
                            } else {
                                $this->db->transCommit();
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
        $this->db->table('cron_for_cis_log')->insert($data);
        if ($this->db->insertID()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function countPendingScrutiny($total= null,$stage_ids, $admin_for_type_id=null, $admin_for_id=null) {
        $builder =  $this->db->table('efil.tbl_efiling_nums as en');
        if(isset($total) && !empty($total)){
            $builder->SELECT('count(en.efiling_no) as total');
        } else {
            $builder->SELECT(array('en.allocated_to','en.efiling_for_type_id', 'en.efiling_for_id', 'en.ref_m_efiled_type_id',
                'en.efiling_no', 'en.efiling_year', 'en.registration_id', 'en.allocated_on',
                'et.efiling_type','cs.stage_id', 'sc_case.diary_no', 'sc_case.diary_year'
            ));
        }
        $builder->JOIN('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id');
        $builder->JOIN('public.tbl_efiling_caveat as ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id','left');
        $builder->JOIN('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $builder->JOIN('efil.tbl_case_details as new_case_cd', 'en.registration_id = new_case_cd.registration_id', 'left');
        $builder->JOIN('efil.tbl_misc_docs_ia as mdia', 'en.registration_id = mdia.registration_id', 'left');
        $builder->JOIN('efil.tbl_sci_cases as sc_case', 'sc_case.diary_no=mdia.diary_no AND sc_case.diary_year = mdia.diary_year', 'left');
        $builder->JOIN('efil.tbl_users users', 'users.id=en.created_by', 'left');
        $builder->WHERE('cs.is_active', 'TRUE');
        $builder->WHERE('en.is_active', 'TRUE');
        $where = '(en.efiling_for_type_id='.$admin_for_type_id .' or en.efiling_for_type_id = '.E_FILING_TYPE_CAVEAT.')';
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