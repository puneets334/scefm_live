<?php

namespace App\Models\EfilingAction;
use CodeIgniter\Model;
class CaveatFinalSubmitModel extends Model {

    protected $session;
    
    function __construct() {
        parent::__construct();
        $db = \Config\Database::connect();
    }

    public function updateCaseStatus($registrationId, $nextStage) {        
        $this->db->transStart();
        $userTypeId = getSessionData('login')['ref_m_usertype_id'];
        $userId = getSessionData('login')['id'];
        $userIp = $_SERVER['REMOTE_ADDR'];
        $actionRes = false;
        $affectedRows = false;
        if ($userTypeId == USER_CLERK) {            
            $updateData = [
                'deactivated_on' => date('Y-m-d H:i:s'),
                'is_active' => true,
                'updated_by' => $userId,
                'updated_by_ip' => $userIp,
            ];
            $builder = $this->db->table('efil.tbl_efiled_docs');
            $builder->where('registration_id', $registrationId);
            $builder->update(['uploaded_by' => getSessionData('dept_adv_panel')['id']]);
            if ($this->db->affectedRows() > 0) {
                $builder = $this->db->table('efil.tbl_court_fee_payment');
                $builder->where('registration_id', $registrationId);
                $builder->update(['receipt_uploaded_by' => getSessionData('dept_adv_panel')['id']]);
            }          
        } else {
            $updateData = [
                'deactivated_on' => date('Y-m-d H:i:s'),
                'is_active' => false,
                'updated_by' => $userId,
                'updated_by_ip' => $userIp,
            ];
        }
        $builder = $this->db->table('public.tbl_efiling_case_status');
        $builder->where('registration_id', $registrationId);
        $builder->where('is_active', true);
        $builder->update($updateData);
        if ($this->db->affectedRows() > 0) {
            if ($userTypeId == USER_CLERK) {
                $createdBy = [
                    'created_by' => getSessionData('dept_adv_panel')['id'],
                    'allocated_on' => date('Y-m-d H:i:s'),
                ];
                $builder = $this->db->table('efil.tbl_efiling_nums');
                $builder->where('registration_id', $registrationId);
                $builder->update($createdBy);
                if ($this->db->affectedRows() > 0) {
                    $this->db->transComplete();
                }
            } else if ($nextStage == Initial_Approaval_Pending_Stage) {
                $efilingType = getSessionData('efiling_details')['efiling_type'];
                $allocatedTo = 0;
                $params = [];
                if (!empty($efilingType) && $efilingType == 'CAVEAT') {
                    $params['user_type'] = USER_ADMIN;
                    $params['loginId'] = $userId;
                    $params['pp_a'] = (getSessionData('login')['ref_m_usertype_id'] == 2) ? 'P' : 'A';
                    $params['not_in_user_id'] = unserialize(USER_NOT_IN_LIST);
                    $params['file_type_id'] = E_FILING_TYPE_CAVEAT;
                    $allocatedUser = $this->getAllocatedUser($params);
                    $allocatedTo = $allocatedUser[0]->id ?? 0;
                }
                $numsDataUpdate = [
                    'allocated_to' => $allocatedTo,
                    'allocated_on' => date('Y-m-d H:i:s'),
                ];                
                $builder = $this->db->table('efil.tbl_efiling_nums');
                $builder->where('registration_id', $registrationId);
                $builder->where('is_active', true);
                $builder->update($numsDataUpdate);
                if ($this->db->affectedRows() > 0) {
                    $data = [
                        'registration_id' => $registrationId,
                        'admin_id' => $allocatedTo,
                        'allocated_on' => date('Y-m-d H:i:s'),
                        'updated_by' => $userId,
                        'updated_on' => date('Y-m-d H:i:s'),
                        'update_ip' => $userIp,
                        'reason_to_allocate' => NULL,
                    ];
                    $builder = $this->db->table('efil.tbl_efiling_allocation');
                    $builder->insert($data);
                    if ($this->db->affectedRows() > 0) {    
                        if ($userTypeId == USER_ADVOCATE) {
                            $efilingForTypeId = getSessionData('efiling_details')['efiling_for_type_id'];
                            $efilingForId = getSessionData('efiling_details')['efiling_for_id'];
                            $registerInfo = $this->get_advocate_register_info($registrationId);
                            $actionRes = TRUE;
                            $advocateCode = $registerInfo[0]['advocate_code'] ?? '';
                        } else {
                            $actionRes = TRUE;
                        }
                    } else {
                        $actionRes = FALSE;
                    }
                } else {
                    $actionRes = FALSE;
                }
            } else {
                $actionRes = TRUE;
            }
            if ($actionRes) {             
                $proceed = false;
                if ($nextStage == Initial_Defects_Cured_Stage) {
                    $updateDef = [
                        'is_active' => false,
                        'is_defect_cured' => true,
                        'defect_cured_date' => date('Y-m-d H:i:s'),
                        'updated_by' => $userId,
                        'ip_address' => $userIp,
                    ];
                    $builder = $this->db->table('efil.tbl_initial_defects');
                    $builder->where('registration_id', $registrationId);
                    $builder->where('is_active', true);
                    $builder->update($updateDef);
                    if ($this->db->affectedRows() > 0) {
                        $proceed = TRUE;
                    }
                } else {
                    $proceed = TRUE;
                }
                if (getSessionData('efiling_details')['stage_id'] == Initial_Defected_Stage) {
                    $insertData = [
                        'registration_id' => $registrationId,
                        'stage_id' => Initial_Defects_Cured_Stage,
                        'activated_on' => date('Y-m-d H:i:s'),
                        'is_active' => TRUE,
                        'activated_by' => $userId,
                        'activated_by_ip' => $userIp,
                    ];
                } else {
                    $insertData = [
                        'registration_id' => $registrationId,
                        'stage_id' => Initial_Approaval_Pending_Stage,
                        'activated_on' => date('Y-m-d H:i:s'),
                        'is_active' => TRUE,
                        'activated_by' => $userId,
                        'activated_by_ip' => $userIp,
                    ];
                }
                $updateData = [
                    'deactivated_on' => date('Y-m-d H:i:s'),
                    'is_active' => false,
                    'activated_by' => $userId,
                    'activated_by_ip' => $userIp,
                ];
                $builder = $this->db->table('efil.tbl_efiling_num_status');
                $builder->where('registration_id', $registrationId);
                $builder->update($updateData);
                $builder->insert($insertData);
                $updateData = [
                    'registration_id' => $registrationId,
                    'stage_id' => Initial_Approaval_Pending_Stage,
                    'activated_on' => date('Y-m-d H:i:s'),
                    'is_active' => TRUE,
                    'activated_by' => $userId,
                    'activated_by_ip' => $userIp,
                ];
                $builder = $this->db->table('public.tbl_efiling_case_status');
                $builder->where('registration_id', $registrationId);
                $builder->update($updateData);
                if ($this->db->affectedRows() > 0) {
                    $this->db->transComplete();
                    return TRUE;
                } else {
                    $this->db->transComplete();
                    return FALSE;
                }
            }
        }
    }

    public function assign_efiling_number($efiling_for_type_id, $efiling_for_id) {
        $sql = "SELECT users.id, users.ref_m_usertype_id, users.admin_for_type_id, users.admin_for_id, count(allocated_on) as efiling_num_count
            FROM efil.tbl_users users
            LEFT JOIN efil.tbl_efiling_nums en ON en.allocated_to = users.id AND DATE(en.allocated_on) = :current_date:
            WHERE users.admin_for_type_id = :efiling_for_type_id: AND users.admin_for_id = :efiling_for_id: AND
            users.is_deleted IS FALSE AND users.id NOT IN(2656,2657,2658,2659,2660) AND
            users.ref_m_usertype_id IN(:user_admin:, :user_action_admin:)
            GROUP BY users.id, users.ref_m_usertype_id, users.admin_for_type_id, users.admin_for_id 
            ORDER BY efiling_num_count";
        $query = $this->db->query($sql, [
            'current_date' => date('Y-m-d'),
            'efiling_for_type_id' => $efiling_for_type_id,
            'efiling_for_id' => $efiling_for_id,
            'user_admin' => USER_ADMIN,
            'user_action_admin' => USER_ACTION_ADMIN
        ]);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function assign_admin_efiling_number($case_type_id) {
        $sql = "SELECT admin_id FROM tbl_admin_case_types WHERE case_types LIKE ?";
        $query = $this->db->query($sql, ['%,' . $case_type_id . ',%']);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return $this->assign_efiling_number($_SESSION['estab_details']['efiling_for_type_id'], $_SESSION['estab_details']['efiling_for_id']);
        }
    }

    public function get_advocate_register_info($registration_id) {
        $builder = $this->db->table('public.tbl_efiling_caveat');
        $builder->select('*');
        $builder->where('ref_m_efiling_nums_registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
    
    public function register_adv_bar_no($login_id, $hc_id, $court_type) {
        $builder = $this->db->table('tbl_advocate_register');
        $builder->select('login_id, establishment_id');
        $builder->where('login_id', $login_id);
        $builder->where('establishment_id', $hc_id);
        $builder->where('efiling_for_type_id', $court_type);
        $builder->where('is_active', true);
        $query = $builder->get();
        $row = $query->getResultArray();
        if ($query->getNumRows() == 1) {
            $update_data = [
                'add_date' => date('Y-m-d H:i:s'),
                'is_register' => 'N'
            ];
            $builder->where('login_id', $login_id);
            $builder->where('establishment_id', $hc_id);
            $builder->where('efiling_for_type_id', $court_type);
            $builder->where('is_active', true);
            $builder->update($update_data);
            if ($this->db->affectedRows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $insert_data = [
                'login_id' => $login_id,
                'establishment_id' => $hc_id,
                'efiling_for_type_id' => $court_type,
                'add_date' => date('Y-m-d H:i:s'),
                'is_register' => 'N'
            ];
            $builder->insert($insert_data);
            if ($this->db->insertID()) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function getAllocatedUser($params = array()) {
        $output = false;
        if (isset($params['user_type']) && !empty($params['user_type']) && isset($params['not_in_user_id']) && !empty($params['not_in_user_id']) && isset($params['file_type_id']) && !empty($params['file_type_id']) && isset($params['pp_a']) && !empty($params['pp_a'])) {
            $builder = $this->db->table('efil.tbl_users tu');
            $builder->select('tu.id, tfaaf.file_type_id')
                ->join('efil.tbl_filing_admin_assigned_file tfaaf', 'tu.id = tfaaf.user_id', 'left')
                ->join('efil.m_tbl_efiling_type mtet', 'tfaaf.file_type_id = mtet.id')
                ->where('tu.is_active', '1')
                ->where('tfaaf.is_active', '1')
                ->where('tfaaf.file_type_id', $params['file_type_id'])
                ->where('tu.ref_m_usertype_id', $params['user_type'])
                ->where('tu.pp_a', $params['pp_a'])
                ->whereNotIn('tu.id', $params['not_in_user_id'])
                ->groupBy('tu.id, tfaaf.file_type_id')
                ->orderBy('RANDOM()')
                ->limit(1);
            $query = $builder->get();
            $output = $query->getResult();
        }
        return $output;
    }

}