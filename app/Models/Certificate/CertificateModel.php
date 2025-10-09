<?php

namespace App\Models\Certificate;
use CodeIgniter\Model;

class CertificateModel extends Model {

    function __construct() {
        parent::__construct();
        $db = \Config\Database::connect();
    }

    function generate_efil_num_n_add_case_details($diary_details, $curr_dt_time) {
        $this->db->transStart();
        $generated_efil_num = $this->gen_efiling_number();
        if ($generated_efil_num['efiling_num']) {
            $registration_id = $this->add_efiling_nums($generated_efil_num, $curr_dt_time);
            if (isset($registration_id) && !empty($registration_id)) {
                $misc_docs_ia_details = array(
                    'registration_id' => $registration_id,
                    'ref_m_efiled_type_id' => E_FILING_TYPE_CERTIFICATE_REQUEST,
                    'diary_no' => $diary_details['diary_no'],
                    'diary_year' => $diary_details['diary_year'],
                    'adv_bar_id' => getSessionData('login')['adv_sci_bar_id'],
                    'adv_code' => getSessionData('login')['aor_code'],
                    'created_on' => $curr_dt_time,
                    'created_by' => getSessionData('login')['id'],
                    'created_by_ip' => getClientIP()
                );
                $status = $this->add_misc_docs_ia_details($registration_id, $misc_docs_ia_details, CERTIFICATE_BREAD_CASE_DETAILS);
                if ($status) {
                    $status = $this->update_efiling_num_status($registration_id, $curr_dt_time, Draft_Stage);
                    if ($status) {
                        $this->get_efiling_num_basic_Details($registration_id);
                        $this->db->transComplete();
                    }
                }
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return $registration_id;
        }
    }

    function gen_efiling_number() {
        $builder = $this->db->table('efil.m_tbl_efiling_no');
        $builder->SELECT('men_efiling_no,men_efiling_year');
        $builder->WHERE('entry_for_type', getSessionData('estab_details')['efiling_for_type_id']);
        $builder->WHERE('ref_m_establishment_id', getSessionData('estab_details')['efiling_for_id']);
        $query = $builder->get();
        $row = $query->getResultArray();
        $p_efiling_num = $row[0]['men_efiling_no'];
        $year = $row[0]['men_efiling_year'];
        if ($year < date('Y')) {
            $newYear = date('Y');
            $update_data = array('men_efiling_no' => 1,
                'men_efiling_year' => $newYear,
                'men_updated_by' => getSessionData('login')['id'],
                'men_updated_on' => date('Y-m-d H:i:s'),
                'men_update_ip' => getClientIP());
            $builder->WHERE('men_efiling_no', $p_efiling_num);
            $builder->WHERE('men_efiling_year', $year);
            $builder->WHERE('entry_for_type', getSessionData('estab_details')['efiling_for_type_id']);
            $builder->WHERE('ref_m_establishment_id', getSessionData('estab_details')['efiling_for_id']);
           $query= $builder->UPDATE('efil.m_tbl_efiling_no', $update_data);
            if ($this->db->affectedRows() > 0) {
                $data['efiling_num'] = 1;
                $data['efiling_year'] = $newYear;
                return $data;
            } else {
                $this->gen_efiling_number();
            }
        } else {
            $gen_efiling_num = $p_efiling_num + 1;
            $efiling_num_info = array('men_efiling_no' => $gen_efiling_num,
                'men_updated_by' => getSessionData('login')['id'],
                'men_updated_on' => date('Y-m-d H:i:s'),
                'men_update_ip' => getClientIP());
            $builder->WHERE('men_efiling_no', $p_efiling_num);
            $builder->WHERE('entry_for_type', getSessionData('estab_details')['efiling_for_type_id']);
            $builder->WHERE('ref_m_establishment_id', getSessionData('estab_details')['efiling_for_id']);
            $query = $builder->UPDATE('efil.m_tbl_efiling_no', $efiling_num_info);
            if ($this->db->affectedRows() > 0) {
                $data['efiling_num'] = $gen_efiling_num;
                $data['efiling_year'] = $year;
                return $data;
            } else {
                $this->gen_efiling_number();
            }
        }
    }

    function add_efiling_nums($generated_efil_num, $curr_dt_time) {
        $num_pre_fix = "CR";
        $filing_type = E_FILING_TYPE_CERTIFICATE_REQUEST;
        $stage_id = Draft_Stage;
        if (getSessionData('login')['ref_m_usertype_id'] == USER_DEPARTMENT || getSessionData('login')['ref_m_usertype_id'] == USER_CLERK) {
            $created_by = 0;
            $sub_created_by = getSessionData('login')['id'];
        } else {
            $created_by = getSessionData('login')['id'];
            $sub_created_by = 0;
        }
        $efiling = sprintf("%'.05d\n", $generated_efil_num['efiling_num']);
        $string = $num_pre_fix . getSessionData('estab_details')['estab_code'] . $efiling . $generated_efil_num['efiling_year'];
        $efiling_num = preg_replace('/\s+/', '', $string);
        $efiling_num_data = array('efiling_no' => $efiling_num,
            'efiling_year' => $generated_efil_num['efiling_year'],
            'efiling_for_type_id' => getSessionData('estab_details')['efiling_for_type_id'],
            'efiling_for_id' => getSessionData('estab_details')['efiling_for_id'],
            'ref_m_efiled_type_id' => $filing_type,
            'created_by' => $created_by,
            'create_on' => $curr_dt_time,
            'create_by_ip' => getClientIP(),
            'sub_created_by' => $sub_created_by,
            'portal_version'=>PORTAL_VERSION
        );
        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->INSERT($efiling_num_data);
        if ($this->db->insertID()) {
            $registration_id = $this->db->insertID();
            return $registration_id;
        } else {
            return FALSE;
        }
    }

    function add_misc_docs_ia_details($registration_id, $case_details, $breadcrumb_step) {
        $builder = $this->db->table('efil.tbl_misc_docs_ia');
        $builder->INSERT($case_details);
        if ($this->db->insertID()) {
            $update_status = $this->update_breadcrumbs($registration_id, $breadcrumb_step);
            if ($update_status) {
                $exists_appearing = $this->check_existence_of_appearing_for($case_details['diary_no'], $case_details['diary_year']);
                if ($exists_appearing) {
                    $update_status = $this->update_breadcrumbs($registration_id, MISC_BREAD_APPEARING_FOR);
                    return $update_status;
                } else {
                    return $update_status;
                }
            } else {
                return $update_status;
            }
        } else {
            return FALSE;
        }
    }

    function check_existence_of_appearing_for($diary_no, $diary_year) {
        $builder = $this->db->table('efil.tbl_appearing_for as appearing');
        $builder->SELECT("diary_num, diary_year");
        $builder->WHERE('appearing.diary_num', $diary_no);
        $builder->WHERE('appearing.diary_year', $diary_year);
        $builder->WHERE('appearing.userid', getSessionData('login')['id']);
        $builder->WHERE('appearing.is_deleted', 'FALSE');
        $query = $builder->get();
        if ($query->getNumRows() == 1) {
            return TRUE;
        } else {
            return false;
        }
    }

    public function get_efiling_num_basic_Details($registration_id) {
        $builder = $this->db->table('efil.tbl_efiling_nums as en');
        $builder->SELECT("en.registration_id, en.efiling_no, en.efiling_year, en.ref_m_efiled_type_id, et.efiling_type,
            en.efiling_for_type_id, en.efiling_for_id, 
            en.breadcrumb_status, en.signed_method, en.allocated_to,
            en.created_by, en.sub_created_by,
            misc_ia.diary_no, misc_ia.diary_year,
            cs.stage_id, cs.activated_on,
            (select payment_status from efil.tbl_court_fee_payment where registration_id = en.registration_id order by id desc limit 1 )");
        $builder->JOIN('efil"."tbl_misc_docs_ia as misc_ia', 'en.registration_id = misc_ia.registration_id', 'left');
        $builder->JOIN('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id');
        $builder->JOIN('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id = et.id');
        $builder->WHERE('cs.is_active', 'TRUE');
        $builder->WHERE('cs.is_deleted', 'FALSE');
        $builder->WHERE('en.is_active', 'TRUE');
        $builder->WHERE('en.is_deleted', 'FALSE');
        $builder->WHERE('en.registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $efiling_details = $query->getResultArray();
            $this->session->set_userdata(array('efiling_details' => $efiling_details[0]));
            return TRUE;
        } else {
            return false;
        }
    }

    function get_establishment_details() {
        $builder = $this->db->table('efil.m_tbl_establishments');
        $builder->SELECT(
            "id,estab_name, estab_code, access_ip,
            enable_payment_gateway, is_charging_printing_cost, printing_cost,payment_gateway_params,pg_request_function,pg_response_function,
            is_pet_state_required, is_pet_district_required, is_res_state_required, is_res_district_required, 
            is_pet_mobile_required, is_pet_email_required, is_res_mobile_required, is_res_email_required,
            is_extra_pet_state_required, is_extra_pet_district_required, is_extra_res_state_required, is_extra_res_district_required,
            is_extra_pet_mobile_required, is_extra_pet_email_required, is_extra_res_mobile_required, is_extra_res_email_required
        ");
        $builder->WHERE('display', 'Y');
        $builder->orderBy("estab_name", "asc");
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $estab_details = $query->getResultArray();
            setSessionData('estab_details', $estab_details[0]);
            getSessionData('estab_details')['efiling_for_type_id'] = E_FILING_FOR_SUPREMECOURT;
            getSessionData('estab_details')['efiling_for_id'] = escape_data($estab_details[0]['id']);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_efiling_num_status($registration_id, $curr_dt_time, $next_stage, $curr_stage = NULL) {
        $deactivate_curr_stage = array(
            'stage_id' => $curr_stage,
            'deactivated_on' => $curr_dt_time,
            'is_active' => FALSE,
            'updated_by' => getSessionData('login')['id'],
            'updated_by_ip' => getClientIP(),
        );
        $activate_next_stage = array(
            'registration_id' => $registration_id,
            'stage_id' => $next_stage,
            'is_active' => TRUE,
            'activated_on' => $curr_dt_time,
            'activated_by' => getSessionData('login')['id'],
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
                if ($this->db->insertID()) {
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
            if ($this->db->insertID()) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function update_breadcrumbs($registration_id, $step_no) {
        $old_breadcrumbs = getSessionData('efiling_details')['breadcrumb_status'] . ',' . $step_no;
        $old_breadcrumbs_array = explode(',', $old_breadcrumbs);
        $new_breadcrumbs_array = array_unique($old_breadcrumbs_array);
        sort($new_breadcrumbs_array);
        $new_breadcrumbs = implode(',', $new_breadcrumbs_array);
        getSessionData('efiling_details')['breadcrumb_status'] = $new_breadcrumbs;
        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->WHERE('registration_id', $registration_id);
        $builder->UPDATE(array('breadcrumb_status' => $new_breadcrumbs));
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function updateCaseStatus($registration_id, $next_stage) {
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
        $builder->UPDATE('efil.tbl_efiling_num_status', $update_data);
        if ($this->db->affectedRows() > 0) {
            if ($next_stage == Initial_Approaval_Pending_Stage) {
                $assign_to = $this->assign_efiling_number(getSessionData('estab_details')['efiling_for_type_id'], getSessionData('estab_details')['efiling_for_id']);
                $nums_data_update = array(
                    'allocated_to' => $assign_to[0]['id'],
                    'allocated_on' => date('Y-m-d H:i:s'));
                $builder = $this->db->table('efil.tbl_efiling_nums');
                $builder->WHERE('registration_id', $registration_id);
                $builder->WHERE('is_active', TRUE);
                $builder->UPDATE($nums_data_update);
                if ($this->db->affectedRows() > 0) {
                    $data = array('registration_id' => $registration_id,
                        'admin_id' => $assign_to[0]['id'],
                        'allocated_on' => date('Y-m-d H:i:s'),
                        'updated_by' => getSessionData('login')['id'],
                        'updated_on' => date('Y-m-d H:i:s'),
                        'update_ip' => getClientIP(),
                        'reason_to_allocate' => NULL);
                    $builder = $this->db->table('efil.tbl_efiling_allocation');
                    $builder->INSERT($data);
                    if ($this->db->insertID()) {
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
                $proceed = false;
                if ($next_stage == Initial_Defects_Cured_Stage || $next_stage == DEFICIT_COURT_FEE_PAID ) {
                    $update_def = array(
                        'is_active' => FALSE,
                        'is_defect_cured' => TRUE,
                        'defect_cured_date' => date('Y-m-d H:i:s'),
                        'updated_by' => getSessionData('login')['id'],
                        'ip_address' => getClientIP()
                    );
                    $builder = $this->db->table('efil.tbl_initial_defects');
                    $builder->WHERE('registration_id', $registration_id);
                    $builder->WHERE('is_active', TRUE);
                    $builder->UPDATE($update_def);
                    if ($this->db->affectedRows() > 0) {
                        $proceed = TRUE;
                    }
                } else {
                    $proceed = TRUE;
                }
                if ($proceed) {
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
                    if ($this->db->insertID()) {
                        $this->db->transComplete();
                    }
                }
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function assign_efiling_number($efiling_for_type_id, $efiling_for_id) {
        $sql = "SELECT users.id,  users.ref_m_usertype_id, users.admin_for_type_id, users.admin_for_id, count(allocated_on) efiling_num_count
        FROM efil.tbl_users users
        LEFT JOIN efil.tbl_efiling_nums en ON en.allocated_to = users.id AND to_char(en.allocated_on,'YYYY-MM-DD')::date ='" . date('Y-m-d') . "'
        WHERE users.admin_for_type_id ='" . $efiling_for_type_id . "' AND users.admin_for_id ='" . $efiling_for_id . "' AND
        users.is_deleted IS FALSE AND
        users.ref_m_usertype_id IN(" . USER_ADMIN . "," . USER_ACTION_ADMIN . ")                  
        GROUP BY users.id,users.ref_m_usertype_id,users.admin_for_type_id,users.admin_for_id ORDER BY efiling_num_count";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return array();
        }
    }
    
}