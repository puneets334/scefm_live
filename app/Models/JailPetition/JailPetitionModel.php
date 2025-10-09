<?php

namespace App\Models\JailPetition;

use CodeIgniter\Model;

class JailPetitionModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    function add_update_case_details($registration_id, $case_details, $petitioner_details, $respondent_details, $breadcrumb_step, $case_details_id = null, $petitioner_id = null, $respondent_id = null)
    {
        if ($case_details_id && $petitioner_id && $respondent_id) {
            $this->db->transStatus();
            $this->update_case_details($registration_id, $case_details, $case_details_id);
            $this->update_case_parties($registration_id, $petitioner_details, $petitioner_id);
            $this->update_case_parties($registration_id, $respondent_details, $respondent_id);
            $this->db->transComplete();
            if ($this->db->transStatus() === FALSE) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            $this->db->transStart();
            $this->update_breadcrumbs($registration_id, $breadcrumb_step);
            $builder = $this->db->table('efil.tbl_case_details');
            $builder->INSERT($case_details);
            $case_detail_id = $this->db->insertId();
            $builder = $this->db->table('efil.tbl_case_parties');
            $builder->INSERT($petitioner_details);
            $pet_id = $this->db->insertId();
            $builder = $this->db->table('efil.tbl_case_parties');
            $builder->INSERT($respondent_details);
            $res_id = $this->db->insertId();
            $this->db->transComplete();
            $ids = $case_detail_id . "##" . $pet_id . "##" . $res_id;
            if ($this->db->transStatus() === FALSE) {
                return FALSE;
            } else {
                return $ids;
            }
        }
    }

    function update_case_details($registration_id, $case_details, $case_details_id)
    {
        $builder = $this->db->table('efil.tbl_case_details');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('id', $case_details_id);
        $builder->WHERE('is_deleted', FALSE);
        $builder->UPDATE($case_details);
    }

    function update_breadcrumbs($registration_id, $step_no)
    {
        $old_breadcrumbs = getSessionData('efiling_details')['breadcrumb_status'] . ',' . $step_no;
        $old_breadcrumbs_array = explode(',', $old_breadcrumbs);
        $new_breadcrumbs_array = array_unique($old_breadcrumbs_array);
        sort($new_breadcrumbs_array);
        $new_breadcrumbs = implode(',', $new_breadcrumbs_array);
        getSessionData('efiling_details')['breadcrumb_status'] = $new_breadcrumbs;
        $builder = $this->db->table('efil.tbl_case_details');
        $builder->WHERE('registration_id', $registration_id);
        $builder->UPDATE(array('breadcrumb_status' => $new_breadcrumbs));
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function generate_efil_num_n_add_case_details($case_details, $petitioner_details, $respondent_details)
    {
        $this->db->transStart();
        $curr_dt_time = date('Y-m-d H:i:s');
        $generated_efil_num = $this->gen_efiling_number();
        if ($generated_efil_num['efiling_num']) {
            $result['registration_id'] = $this->add_efiling_nums($generated_efil_num, $curr_dt_time);
            if (isset($result['registration_id']) && !empty($result['registration_id'])) {
                $case_details_create_data = array(
                    'registration_id' => $result['registration_id'],
                    'created_on' => $curr_dt_time,
                    'created_by' => getSessionData('login')['id'],
                    'created_by_ip' => getClientIP()
                );
                $case_details = array_merge($case_details, $case_details_create_data);
                $petitioner_details = array_merge($petitioner_details, $case_details_create_data);
                $respondent_details = array_merge($respondent_details, $case_details_create_data);
                $this->add_update_case_details($result['registration_id'], $case_details, $petitioner_details, $respondent_details, JAIL_PETITION_CASE_DETAILS);
                $adv_details = array(
                    'registration_id' => $result['registration_id'],
                    'adv_bar_id' => 613,
                    'm_a_adv_type' => 'M',
                    'for_p_r_a' => 'P',
                    'adv_code' => 887
                );
                $this->add_update_adv_details($result['registration_id'], $adv_details);
                $this->update_efiling_num_status($result['registration_id'], $curr_dt_time, Draft_Stage);
                $this->db->transComplete();
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return $result;
        }
    }

    function gen_efiling_number()
    {
        $builder = $this->db->table('efil.m_tbl_efiling_no');
        $builder->SELECT('case_efiling_no,case_efiling_year');
        $builder->WHERE('entry_for_type', $_SESSION['estab_details']['efiling_for_type_id']);
        $builder->WHERE('ref_m_establishment_id', $_SESSION['estab_details']['efiling_for_id']);
        $query = $builder->get();
        $row = $query->getResultArray();
        $p_efiling_num = $row[0]['case_efiling_no'];
        $year = $row[0]['case_efiling_year'];
        if ($year < date('Y')) {
            $newYear = date('Y');
            $update_data = array(
                'case_efiling_no' => 1,
                'case_efiling_year' => $newYear,
                'case_updated_by' => getSessionData('login')['id'],
                'case_updated_on' => date('Y-m-d H:i:s'),
                'case_update_ip' => getClientIP()
            );
            $builder = $this->db->table('efil.m_tbl_efiling_no');
            $builder->WHERE('case_efiling_no', $p_efiling_num);
            $builder->WHERE('case_efiling_year', $year);
            $builder->WHERE('entry_for_type', getSessionData('efiling_details')['efiling_for_type_id']);
            $builder->WHERE('ref_m_establishment_id', getSessionData('efiling_details')['efiling_for_id']);
            $builder->UPDATE($update_data);
            if ($this->db->affectedRows() > 0) {
                $data['efiling_num'] = 1;
                $data['efiling_year'] = $newYear;
                return $data;
            } else {
                $this->gen_efiling_number();
            }
        } else {
            $gen_efiling_num = $p_efiling_num + 1;
            $efiling_num_info = array(
                'case_efiling_no' => $gen_efiling_num,
                'case_updated_by' => getSessionData('login')['id'],
                'case_updated_on' => date('Y-m-d H:i:s'),
                'case_update_ip' => getClientIP()
            );
            $builder = $this->db->table('efil.m_tbl_efiling_no');
            $builder->WHERE('case_efiling_no', $p_efiling_num);
            $builder->WHERE('entry_for_type', getSessionData('estab_details')['efiling_for_type_id']);
            $builder->WHERE('ref_m_establishment_id', getSessionData('estab_details')['efiling_for_id']);
            $result = $builder->UPDATE($efiling_num_info);
            if ($this->db->affectedRows() > 0) {
                $data['efiling_num'] = $gen_efiling_num;
                $data['efiling_year'] = $year;
                return $data;
            } else {
                $this->gen_efiling_number();
            }
        }
    }

    function add_efiling_nums($generated_efil_num, $curr_dt_time)
    {
        $num_pre_fix = "JP";
        $filing_type = E_FILING_TYPE_JAIL_PETITION;
        $stage_id = Draft_Stage;
        $created_by = getSessionData('login')['id'];
        $sub_created_by = 0;
        $efiling = sprintf("%'.05d\n", $generated_efil_num['efiling_num']);
        $string = $num_pre_fix . $_SESSION['estab_details']['estab_code'] . $efiling . $generated_efil_num['efiling_year'];
        $efiling_num = preg_replace('/\s+/', '', $string);
        $efiling_num_data = array(
            'efiling_no' => $efiling_num,
            'efiling_year' => $generated_efil_num['efiling_year'],
            'efiling_for_type_id' => $_SESSION['estab_details']['efiling_for_type_id'],
            'efiling_for_id' => $_SESSION['estab_details']['efiling_for_id'],
            'ref_m_efiled_type_id' => $filing_type,
            'created_by' => $created_by,
            'create_on' => $curr_dt_time,
            'create_by_ip' => getClientIP(),
            'sub_created_by' => $sub_created_by,
            'portal_version' => PORTAL_VERSION
        );
        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->INSERT($efiling_num_data);
        if ($this->db->insertId()) {
            $session = session();
            $session_data = array('registration_id' => $this->db->insertId(), 'efiling_no' => $efiling_num);
            setSessionData('efiling_details', $session_data);
            return $this->db->insertId();
        } else {
            return false;
        }
    }

    function update_efiling_num_status($registration_id, $curr_dt_time, $next_stage, $curr_stage = NULL)
    {
        $deactivate_curr_stage = array(
            'stage_id' => $curr_stage,
            'deactivated_on' => $curr_dt_time,
            'is_active' => FALSE,
            'updated_by' => $this->session->userdata['login']['id'],
            'updated_by_ip' => getClientIP(),
        );
        $activate_next_stage = array(
            'registration_id' => $registration_id,
            'stage_id' => $next_stage,
            'is_active' => TRUE,
            'activated_on' => $curr_dt_time,
            'activated_by' => $this->session->userdata['login']['id'],
            'activated_by_ip' => getClientIP(),
        );
        if ($curr_stage != NULL) {
            $builder = $this->db->table('efil.tbl_efiling_num_status');
            $builder->WHERE('registration_id', $registration_id);
            $builder->WHERE('is_active', TRUE);
            $builder->UPDATE($deactivate_curr_stage);
            if ($this->db->affectedRows() > 0) {
                $builder = $this->db->table('efil.tbl_efiling_num_status');
                $builder->INSERT($activate_next_stage);
                if ($this->db->insertId()) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            $builder = $this->db->table('efil.tbl_efiling_num_status');
            $builder->INSERT($activate_next_stage);
            if ($this->db->insertId()) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function add_update_adv_details($registration_id, $data)
    {
        $builder = $this->db->table('efil.tbl_case_advocates');
        $builder->INSERT($data);
        if ($this->db->insertId()) {
            return true;
        } else {
            return false;
        }
    }

    function update_case_parties($registration_id, $data, $party_id)
    {
        $builder = $this->db->table('efil.tbl_case_parties');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('id', $party_id);
        $builder->WHERE('is_deleted', FALSE);
        $builder->UPDATE($data);
    }

    function add_subordinate_court_info($registration_id, $data, $breadcrumb_step, $fir_data)
    {
        $this->db->transStart();
        $this->update_breadcrumbs($registration_id, $breadcrumb_step);
        $builder = $this->db->table('efil.tbl_lower_court_details');
        $builder->INSERT($data);
        $builder = $this->db->table('efil.tbl_fir_details');
        $builder->INSERT($fir_data);
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function delete_case_subordinate_court($registration_id, $id)
    {
        $curr_dt_time = date('Y-m-d H:i:s');
        $data = array('is_deleted' => TRUE);
        $builder = $this->db->table('efil.tbl_lower_court_details');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('id', $id);
        $builder->WHERE('is_deleted', FALSE);
        $builder->UPDATE($data);
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function add_update_case_parties($registration_id, $data, $breadcrumb_step, $party_id = null)
    {
        if ($party_id) {
            $builder = $this->db->table('efil.tbl_case_parties');
            $builder->WHERE('registration_id', $registration_id);
            $builder->WHERE('id', $party_id);
            $builder->WHERE('is_deleted', FALSE);
            $builder->UPDATE($data);
            if ($this->db->affectedRows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            $this->db->transStart();
            $builder = $this->db->table('efil.tbl_case_parties');
            $this->update_breadcrumbs($registration_id, $breadcrumb_step);
            $builder->INSERT($data);
            $this->db->transComplete();
            if ($this->db->transStatus() === FALSE) {
                return FALSE;
            } else {
                return $this->db->insertId();
            }
        }
    }

    function delete_case_party($registration_id, $party_id)
    {
        $curr_dt_time = date('Y-m-d H:i:s');
        $data = array(
            'is_deleted' => TRUE,
            'deleted_by' => getSessionData('login')['id'],
            'deleted_on' => $curr_dt_time,
            'deleted_by_ip' => getClientIP()
        );
        $builder = $this->db->table('efil.tbl_case_parties');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('id', $party_id);
        $builder->WHERE('is_deleted', FALSE);
        $builder->UPDATE($data);
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function updateJailPetitionStatus($registration_id, $next_stage, $respondent_state)
    {
        $states_II = array(511231, 355594, 349528, 184724, 167131, 541950, 599089, 185190, 61023);
        $states_IIA = array(1, 431772, 292979, 358033, 490133);
        $states_IIB = array(226817, 592019, 21945, 471342, 124509, 239687, 107620);
        $states_IIC = array(490809, 490506, 5711779, 464616, 402163, 168044);
        if (in_array($respondent_state, $states_II))
            $assignTo = 2656;
        else if (in_array($respondent_state, $states_IIA))
            $assignTo = 2657;
        else if (in_array($respondent_state, $states_IIB))
            $assignTo = 2658;
        else if (in_array($respondent_state, $states_IIC))
            $assignTo = 2659;
        else
            $assignTo = 2656;
        $this->db->transStart();
        $update_data = array(
            'deactivated_on' => date('Y-m-d H:i:s'),
            'is_active' => FALSE,
            'updated_by' => getSessionData('login')['id'],
            'updated_by_ip' => getClientIP()
        );
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('is_active', TRUE);
        $builder->UPDATE($update_data);
        if ($this->db->affectedRows() > 0) {
            if ($next_stage == Initial_Approaval_Pending_Stage) {
                $nums_data_update = array(
                    'allocated_to' => $assignTo,
                    'allocated_on' => date('Y-m-d H:i:s')
                );
                $builder = $this->db->table('efil.tbl_efiling_nums');
                $builder->WHERE('registration_id', $registration_id);
                $builder->WHERE('is_active', TRUE);
                $builder->UPDATE($nums_data_update);
                if ($this->db->affectedRows() > 0) {
                    $data = array(
                        'registration_id' => $registration_id,
                        'admin_id' => $assignTo,
                        'allocated_on' => date('Y-m-d H:i:s'),
                        'updated_by' => getSessionData('login')['id'],
                        'updated_on' => date('Y-m-d H:i:s'),
                        'update_ip' => getClientIP(),
                        'reason_to_allocate' => NULL
                    );
                    $builder = $this->db->table('efil.tbl_efiling_nums');
                    $builder->INSERT($data);
                    if ($this->db->insertId()) {
                        $action_res = TRUE;
                    } else {
                        $action_res = FALSE;
                    }
                } else {
                    $action_res = FALSE;
                }
            } else {
                $action_res = TRUE;
            }
            if ($action_res) {
                $insert_data = array(
                    'registration_id' => $registration_id,
                    'stage_id' => $next_stage,
                    'activated_on' => date('Y-m-d H:i:s'),
                    'is_active' => TRUE,
                    'activated_by' => getSessionData('login')['id'],
                    'activated_by_ip' => getClientIP()
                );
                $builder = $this->db->table('efil.tbl_efiling_num_status');
                $builder->INSERT($insert_data);
                if ($this->db->insertId()) {
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
}
