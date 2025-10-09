<?php
namespace App\Models\Cron;

use CodeIgniter\Model;
use Config\Database;
class AiMiscDeftectsCronModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function get_ai_misc_efiled_nums_stage_wise_list_admin_cron($stage_ids, $efiled_type_ids) {
        $builder = $this->db->table('efil.tbl_efiling_nums as en');
        $builder->distinct()
            ->select([
                'cs.stage_id',
                'en.registration_id',
                'en.ref_m_efiled_type_id',
                'en.efiling_no',
                'cs.activated_on',
                'en.efiling_for_id',
                'efiling_type',
                'mdia.diary_no',
                'mdia.diary_year',
                'concat(mdia.diary_no, mdia.diary_year) as diary_no',
            ])
            ->join('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id')
            ->join('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id = et.id')
            ->join('efil.tbl_misc_docs_ia as mdia', 'en.registration_id = mdia.registration_id')
            ->join('efil.tbl_sci_cases as sc_case', 'sc_case.diary_no = mdia.diary_no AND sc_case.diary_year = mdia.diary_year', 'left')
            ->join('efil.tbl_users as users', 'users.id = en.created_by', 'left')
            ->where('cs.is_active', TRUE)
            ->where('en.is_active', TRUE)
            ->where('mdia.diary_no is not null')
            ->where('mdia.diary_year is not null')
            ->whereIn('en.ref_m_efiled_type_id', $efiled_type_ids)
            ->whereIn('cs.stage_id', $stage_ids)
            ->orderBy('cs.activated_on', 'DESC');
        $query = $builder->get();
        if($query->getNumRows() >= 1) {
            return $query->getResultObject();
        } else {
            return false;
        }

    }

    public function update_ai_misc_icmis_case_status($registration_id, $next_stage, $curr_dt, $case_details, $objections_insert, $objections_update, $efiling_type = null) {
        $current_stage = $this->get_current_stage($registration_id);
        if ($current_stage[0]['stage_id']==IA_E_Filed || $current_stage[0]['stage_id']==Document_E_Filed) {
            if (isset($objections_insert) && !empty($objections_insert)) {
                $builder = $this->db->table('efil.tbl_icmis_ai_docs_objections'); 
                $result = $builder->insertBatch($objections_insert);
            }
        } elseif (($current_stage[0]['stage_id']==I_B_Approval_Pending_Admin_Stage || $current_stage[0]['stage_id']==E_Filed_Stage) || ($current_stage[0]['stage_id']==I_B_Defected_Stage && $next_stage=E_Filed_Stage)) {
            if (isset($objections_update) && !empty($objections_update)) {
                $builder = $this->db->table('efil.tbl_icmis_ai_docs_objections'); 
                $result = $builder->updateBatch($objections_update, 'id');
            }
        } else {
            return TRUE;
        }
        if (($current_stage[0]['stage_id'] !=I_B_Defected_Stage) || ($current_stage[0]['stage_id']==I_B_Defected_Stage && $next_stage=E_Filed_Stage)) {
            if ($current_stage) {
                if ($current_stage[0]['stage_id'] == $next_stage) {
                    return FALSE;
                } else {
                    if ($next_stage) {
                        $res = $this->update_next_stage($registration_id, $next_stage, $curr_dt);
                    }
                }
            }
        }
        return TRUE;
    }

    function get_current_stage($registration_id) {
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->select('stage_id')
        ->where('is_active', TRUE)
        ->where('registration_id', $registration_id);
        $query = $builder->get();
        if($query->getNumRows() >= 1) {
           $result = $query->getResultArray();
           return $result;
        } else {
           return false;
        }
    }

    function update_next_stage($registration_id, $next_stage, $curr_dt) {
        $update_data = array(
            'deactivated_on' => $curr_dt,
            'is_active' => FALSE,
            'updated_by' => env('AUTO_UPDATE_CRON_USER'),
            'updated_by_ip' => getClientIP()
        );
        $this->db->table('efil.tbl_efiling_num_status')
        ->where('registration_id', $registration_id)
        ->where('is_active', TRUE)
        ->update($update_data);
        $insert_data = array(
            'registration_id' => $registration_id,
            'stage_id' => $next_stage,
            'activated_on' => $curr_dt,
            'is_active' => TRUE,
            'activated_by' => env('AUTO_UPDATE_CRON_USER'),
            'activated_by_ip' => getClientIP()
        );
        $this->db->table('efil.tbl_efiling_num_status')->insert($insert_data);
        if ($this->db->insertID()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function insertInDBwithInsertedId($tablename,$data) {
        $this->db->table($tablename)->insert($data);
        return $this->db->insertID();
    }

    function updateCronDetails($id) {
        $builder = $this->db->table('efil.tbl_cron_details')->set('completed_at', date('Y-m-d H:i:s'))->where('id', $id)->update();
    }

    public function get_efiled_nums_stage_wise_list_admin_cronIsDisposed($stage_ids, $admin_for_type_id, $admin_for_id) {
        $builder = $this->db->table('efil.tbl_efiling_nums AS en');
        $builder->select([
            'en.registration_id',
            'en.ref_m_efiled_type_id',
            'en.efiling_no',
            'cs.activated_on',
            'en.efiling_for_id',
            'et.efiling_type','new_case_cd.sc_diary_num','new_case_cd.sc_diary_year',
            "CONCAT(new_case_cd.sc_diary_num, new_case_cd.sc_diary_year) AS diary_no",
        ])
        ->distinct()
        ->join('efil.tbl_efiling_num_status AS cs', 'en.registration_id = cs.registration_id')
        ->join('efil.m_tbl_efiling_type AS et', 'en.ref_m_efiled_type_id = et.id')
        ->join('efil.tbl_case_details AS new_case_cd', 'en.registration_id = new_case_cd.registration_id')
        ->join('efil.tbl_users AS users', 'users.id = en.created_by', 'left')
        ->where('cs.is_active', TRUE)
        ->where('en.is_active', TRUE)
        ->where('en.ref_m_efiled_type_id', E_FILING_TYPE_NEW_CASE)
        ->where('new_case_cd.sc_diary_num IS NOT NULL')
        ->where('new_case_cd.sc_diary_year IS NOT NULL')
        ->where('en.efiling_for_type_id', $admin_for_type_id)
        ->where('en.efiling_for_id', $admin_for_id)
        ->whereIn('cs.stage_id', $stage_ids)
        ->orderBy('cs.activated_on', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

}