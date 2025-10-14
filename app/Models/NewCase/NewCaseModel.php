<?php

namespace App\Models\Newcase;
use CodeIgniter\Model;

class NewCaseModel extends Model {

    function __construct() {
        parent::__construct();
    }

    function generate_efil_num_n_add_case_details($case_details) {
        $this->db->transStart();
        $curr_dt_time = date('Y-m-d H:i:s');
        // GET NEXT EFILING NUMBER
        $generated_efil_num = $this->gen_efiling_number();
        if ($generated_efil_num['efiling_num']) {
            // Saving data to efiling nums
            $result['registration_id'] = $this->add_efiling_nums($generated_efil_num, $curr_dt_time);
            if (isset($result['registration_id']) && !empty($result['registration_id'])) {
                $created_by = getSessionData('login')['id'];
                $adv_sci_bar_id=$_SESSION['login']['adv_sci_bar_id'];
                if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {
                    $aorData = getAordetailsByAORCODE($_SESSION['login']['aor_code']);
                    $created_by=!empty($aorData) ? $aorData[0]->id  : null;
                    $adv_sci_bar_id=!empty($aorData) ? $aorData[0]->adv_sci_bar_id  : null;
                    $_SESSION['login']['adv_sci_bar_id'] = $adv_sci_bar_id;
                }
                $case_details_create_data = array(
                    'registration_id' => $result['registration_id'],
                    'created_on' => $curr_dt_time,
                    'created_by' => $created_by,
                    'created_by_ip' => getClientIP()
                );
                $case_details = array_merge($case_details, $case_details_create_data);
                // INSERT NEW CASE DETAILS IN CASE PARTIES
                $this->add_update_case_details($result['registration_id'], $case_details, NEW_CASE_CASE_DETAIL);
                $adv_details = array(
                    'registration_id' => $result['registration_id'],
                    'adv_bar_id' => $adv_sci_bar_id,
                    'm_a_adv_type' => 'M',
                    'for_p_r_a' => 'P',
                    'adv_code' => $_SESSION['login']['aor_code']
                );
                // INSERT Advocate DETAILS
                $this->add_update_adv_details($result['registration_id'], $adv_details);
                // UPDATE CASE STAGE IN EFILING NUM STATUS TABLE
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

    function gen_efiling_number() {
        $builder=$this->db->table('efil.m_tbl_efiling_no');
        $builder->select('case_efiling_no,case_efiling_year');
        $builder->where('entry_for_type', getSessionData('estab_details')['efiling_for_type_id']);
        $builder->where('ref_m_establishment_id', getSessionData('estab_details')['efiling_for_id']);
        $query = $builder->get();
        $row = $query->getResult();
        $p_efiling_num = $row[0]->case_efiling_no;
        $year = $row[0]->case_efiling_year;
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
            $builder->where('case_efiling_no', $p_efiling_num);
            $builder->where('case_efiling_year', $year);
            $builder->where('entry_for_type', getSessionData('estab_details')['efiling_for_type_id']);
            $builder->where('ref_m_establishment_id', getSessionData('estab_details')['efiling_for_id']);
            $builder->set($update_data);
            $builder->update();
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
            $builder->where('case_efiling_no', $p_efiling_num);
            $builder->where('entry_for_type', getSessionData('estab_details')['efiling_for_type_id']);
            $builder->where('ref_m_establishment_id', getSessionData('estab_details')['efiling_for_id']);
            $builder->set($efiling_num_info);
            $builder->update();
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
        $num_pre_fix = "EC";
        $filing_type = E_FILING_TYPE_NEW_CASE;
        $stage_id = Draft_Stage;
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
            $aorData = getAordetailsByAORCODE($_SESSION['login']['aor_code']);
            $created_by=!empty($aorData) ? $aorData[0]->id  : 0;
            $sub_created_by = $_SESSION['login']['id'];
        } else {
            $created_by = $_SESSION['login']['id'];
            $sub_created_by = 0;
        }
        $efiling = sprintf("%'.05d\n", $generated_efil_num['efiling_num']);
        $estab_code = !empty(getSessionData('estab_details')['estab_code']) ? getSessionData('estab_details')['estab_code'] : '';
        $string = $num_pre_fix . $estab_code . $efiling . $generated_efil_num['efiling_year'];
        $efiling_num = preg_replace('/\s+/', '', $string);
        $efiling_num_data = array('efiling_no' => $efiling_num,
            'efiling_year' => (int)$generated_efil_num['efiling_year'],
            'efiling_for_type_id' => !empty(getSessionData('estab_details')['efiling_for_type_id']) != '' ? (int)getSessionData('estab_details')['efiling_for_type_id'] : null,
            'efiling_for_id' => !empty(getSessionData('estab_details')['efiling_for_id']) != '' ? (int)getSessionData('estab_details')['efiling_for_id'] : null,
            'ref_m_efiled_type_id' => $filing_type,
            'created_by' => $created_by,
            'create_on' => $curr_dt_time,
            'create_by_ip' => getClientIP(),
            'sub_created_by' => $sub_created_by,
            'portal_version'=>PORTAL_VERSION
        );
        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->insertBatch($efiling_num_data);
        if ($this->db->insertID()) {
            $session_data = array('registration_id' => $this->db->insertID(), 'efiling_no' => $efiling_num, 'stage_id' => $stage_id, 'breadcrumb_status' => '', 'efiling_for_name' => '');
            $sData = getSessionData('estab_details');
            $mdata = array_merge($sData, $session_data);
            // pr($mdata);
            setSessionData('efiling_details', $mdata);
            return $this->db->insertID();
        } else {
            return false;
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
            $builder->where('registration_id', $registration_id);
            $builder->where('is_active', TRUE);
            $builder->set($deactivate_curr_stage);
            $builder->update();
            if ($this->db->affectedRows() > 0) {
                $builder = $this->db->table('efil.tbl_efiling_num_status');
                $builder->insert($activate_next_stage);
                $sData = getSessionData('estab_details');
                $mdata = array_merge($sData, $activate_next_stage);
                // pr($mdata);
                setSessionData('efiling_details', $mdata);
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
            $builder->insert( $activate_next_stage);
            $sData = getSessionData('estab_details');
            $mdata = array_merge($sData, $activate_next_stage);
            // pr($mdata);
            setSessionData('efiling_details', $mdata);
            if ($this->db->insertID()) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function update_breadcrumbs($registration_id, $step_no) {
        $old_breadcrumbs = !empty(getSessionData('efiling_details')) ? getSessionData('efiling_details')['breadcrumb_status']. ',' . $step_no : '' ;
        $old_breadcrumbs_array = explode(',', $old_breadcrumbs);
        $new_breadcrumbs_array = array_unique($old_breadcrumbs_array);
        sort($new_breadcrumbs_array);
        $new_breadcrumbs = implode(',', $new_breadcrumbs_array);
        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->where('registration_id', $registration_id);
        $builder->set(array('breadcrumb_status' => $new_breadcrumbs));
        $builder->update();
        if ($this->db->affectedRows() > 0) {
            $setD = getSessionData('efiling_details');
            $setD['breadcrumb_status'] = $new_breadcrumbs;
            setSessionData('efiling_details', $setD);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function add_update_case_parties($registration_id, $data, $breadcrumb_step, $party_id = null) {
      
        if ($party_id) {
          
            $this->update_breadcrumbs($registration_id, $breadcrumb_step);
            $builder = $this->db->table('efil.tbl_case_parties');
            $builder->where('registration_id', $registration_id);
            $builder->where('id', $party_id);
            $builder->where('is_deleted', FALSE);
            $builder->set($data);
            $builder->update();
           
            if ($this->db->affectedRows() > 0) { 
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            $this->db->transStart();
            $this->update_breadcrumbs($registration_id, $breadcrumb_step);
            
            // $builder->insert($data);
            $is_parties = $this->is_tbl_case_parties($registration_id, $data);
            if (empty($is_parties)) {
                $builder = $this->db->table('efil.tbl_case_parties');
                $builder->insert($data);
            }
            if (isset($data['parent_id']) && $data['parent_id'] != NULL) {
                $this->update_party_lr_status($registration_id, $data['parent_id'], $data['create_on']);
            }
            $this->db->transComplete();
            if ($this->db->transStatus() === FALSE) {
                return FALSE;
            } else {
                return $this->db->insertID();
            }
        }
    }

    function delete_case_party($registration_id, $party_id) {
        $curr_dt_time = date('Y-m-d H:i:s');
        $data = array(
            'is_deleted' => TRUE,
            'deleted_by' => $_SESSION['login']['id'],
            'deleted_on' => $curr_dt_time,
            'deleted_by_ip' => getClientIP()
        );
        $builder = $this->db->table('efil.tbl_case_parties');
        $builder->where('registration_id', $registration_id);
        $builder->where('id', $party_id);
        $builder->where('is_deleted', FALSE);
        $builder->set($data);
        $builder->update();
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_party_lr_status($registration_id, $party_id, $curr_dt_time) {
        $data = array(
            'have_legal_heir' => TRUE,
            'updated_by' => $_SESSION['login']['id'],
            'updated_on' => $curr_dt_time,
            'updated_by_ip' => getClientIP()
        );
        $builder = $this->db->table('efil.tbl_case_parties');
        $builder->where('registration_id', $registration_id);
        $builder->where('party_id', $party_id);
        $builder->where('have_legal_heir', FALSE);
        $builder->set($data);
        $builder->update();
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function add_update_case_details($registration_id, $data, $breadcrumb_step, $case_details_id = null) {

        if ($case_details_id) {
            $this->db->transStart();
            if (in_array(getSessionData('login')['ref_m_usertype_id'], [USER_DEPARTMENT]) && !is_null(getSessionData('login')['aor_code'])) {
                $builder = $this->db->table('efil.department_filings');
                $builder->where('registration_id', $registration_id);
                $builder->set(['aor_code'=>trim(getSessionData('login')['aor_code'])]);
                $builder->update();
            }
            if (in_array(getSessionData('login')['ref_m_usertype_id'], [USER_ADVOCATE]) && isset($_POST['impersonated_department']) && !is_null($_SESSION['impersonated_department'])) {
                $builder=$this->db->table('efil.department_filings');
                $builder->where('registration_id', $registration_id);
                $query = $builder->get();
                $row = $query->getResult();
                if ($query->getNumRows()) {
                    $builder = $this->db->table('efil.department_filings');
                    $builder->where('id', $row[0]['id']);
                    $builder->set(['ref_department_id'=>trim(getSessionData('impersonated_department'))]);
                    $builder->update();
                } else {
                    $builder = $this->db->table('efil.department_filings');
                    $builder->insert(['registration_id'=>$registration_id, 'ref_department_id'=>getSessionData('impersonated_department'), 'aor_code'=>trim(getSessionData('login')['aor_code'])]);
                }
            }
            $builder = $this->db->table('efil.tbl_case_details');
            $builder->where('registration_id', $registration_id);
            $builder->where('id', $case_details_id);
            $builder->where('is_deleted', FALSE);
            $builder->set($data);
            $builder->update();
            $this->db->transComplete();
            if ($this->db->transStatus() === FALSE) {
                return FALSE;
            }
            if ($this->db->affectedRows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            $this->db->transStart();
            $this->update_breadcrumbs($registration_id, $breadcrumb_step);
            $builder = $this->db->table('efil.tbl_case_details');
            $builder->insert($data);
            if (in_array(getSessionData('login')['ref_m_usertype_id'], [USER_DEPARTMENT]) && !empty(getSessionData('login')['aor_code'])) {
                $builder = $this->db->table('efil.department_filings');
                $builder->insert(['registration_id'=>$registration_id, 'ref_department_id'=>getSessionData('login')['department_id'], 'aor_code'=>trim(getSessionData('login')['aor_code'])]);
            }
            if (in_array(getSessionData('login')['ref_m_usertype_id'], [USER_CLERK]) && !is_null(getSessionData('login')['aor_code'])) {
                $builder = $this->db->table('efil.clerk_filings');
                $builder->insert(['registration_id'=>$registration_id, 'ref_user_id'=>getSessionData('login')['id'], 'aor_code'=>trim(getSessionData('login')['aor_code'])]);
            }
            if (in_array(getSessionData('login')['ref_m_usertype_id'], [USER_ADVOCATE]) && isset($_POST['impersonated_department']) && !is_null($_SESSION['impersonated_department'])) {
                $builder=$this->db->table('efil.department_filings');
                $builder->where('registration_id', $registration_id);
                $query = $builder->get();
                $row = $query->getResult();
                if ($query->getNumRows()) {
                    $builder = $this->db->table('efil.department_filings');
                    $builder->where('id', $row[0]['id']);
                    $builder->set(['ref_department_id'=>trim($_SESSION['impersonated_department'])]);
                    $builder->update();
                } else{
                    $builder = $this->db->table('efil.department_filings');
                    $builder->insert(['registration_id'=>$registration_id, 'ref_department_id'=>$_SESSION['impersonated_department'], 'aor_code'=>trim($_SESSION['login']['aor_code'])]);
                }
            }
            $this->db->transComplete();
            // echo $this->db->last_query(); exit;
            if ($this->db->transStatus() === FALSE) {
                return FALSE;
            } else {
                return $this->db->insertID();
            }
        }
    }

    function add_update_adv_details($registration_id, $data) {
        $builder = $this->db->table('efil.tbl_case_advocates');
        $builder->insert($data);
        if ($this->db->insertID()) {
            return true;
        } else {
            return false;
        }
    }
    
    function add_subordinate_court_info($registration_id, $data, $breadcrumb_step,$fir_data,$subordinate_court_details,$certified_copy_details=array()) {
        $this->db->transStart();
        if($subordinate_court_details && $subordinate_court_details[0]['is_hc_exempted']=='t') {
            $curr_dt_time = date('Y-m-d H:i:s');
            $data_deleted = array(
                'is_deleted' => TRUE,
                'deleted_by' => $_SESSION['login']['id'],
                'deleted_on' => $curr_dt_time,
                'deleted_by_ip' => getClientIP()
            );
            $builder = $this->db->table('efil.tbl_lower_court_details');
            $builder->where('registration_id', $registration_id);
            $builder->where('is_deleted', FALSE);
            $builder->set($data_deleted);
            $builder->update();
        } else {
            $this->update_breadcrumbs($registration_id, $breadcrumb_step);
        }
        // pr($data);
        $builder = $this->db->table('efil.tbl_lower_court_details');
        $builder->insert($data);  
        $insert_id = $this->db->insertID();
        if($fir_data!=null) {
            $fir_data['ref_tbl_lower_court_details_id']=$insert_id;
            $builder = $this->db->table('efil.tbl_fir_details');
            $builder->insert($fir_data);
        }
        if (isset($data) && isset($data['court_type']) && !empty($data['court_type']) && !empty($insert_id)){
            if (isset($certified_copy_details) && !empty($certified_copy_details) && $certified_copy_details['application_no'] && $certified_copy_details['application_date'] && $certified_copy_details['exemption_filed']){
                $certified_copy_details_final=array_merge($certified_copy_details,['ref_tbl_lower_court_details_id'=>$insert_id]);
               $builder_certified_copy = $this->db->table('efil.tbl_certified_copy_details');
                $builder_certified_copy->insert($certified_copy_details_final);
            }
        }
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {

            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_subordinate_court_info_newcase($id,$registration_id,$data) {
        $builder = $this->db->table('efil.tbl_lower_court_details');
        $builder->where('id', $id);
        $builder->where('registration_id', $registration_id);
        $builder->where('is_deleted', FALSE);
        $builder->set($data);
        $builder->update();
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function add_extra_information($registration_id, $data, $cis_masters_values) {
        $this->db->transStart();
        $builder = $this->db->table('tbl_efiling_civil');
        $builder->where('ref_m_efiling_nums_registration_id', $registration_id);
        $builder->set($data);
        $builder->update();
        if ($this->db->affectedRows() > 0) {
            if ($cis_masters_values != NULL) {
                $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                if ($success) {
                    $this->db->transComplete();
                }
            } else {
                $this->db->transComplete();
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function add_subordinate($registration_id, $data, $cis_masters_values) {
        $this->db->transStart();
        $builder = $this->db->table('tbl_efiling_civil');
        $builder->where('ref_m_efiling_nums_registration_id', $registration_id);
        $builder->set($data);
        $builder->update();
        if ($this->db->affectedRows() > 0) {
            $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
            if ($success) {
                $this->db->transComplete();
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function add_act_section($registration_id, $data, $cis_masters_values) {
        $enable_reconsume = array(I_B_Rejected_Stage);
        if (in_array($_SESSION['efiling_details']['stage_id'], $enable_reconsume)) {
            $reconsume = TRUE;
        }
        if (count($data['act']) > 4) {
            $i = 1;
            foreach ($data['act'] as $k => $d1) {
                if ($i <= 4) {
                    $data1[$k] = $d1;
                } else {
                    $data2[$k] = $d1;
                }
                $i++;
            }
            $j = 1;
            foreach ($data['section'] as $k => $d1) {
                if ($j <= 4) {
                    $data1[$k] = $d1;
                } else {
                    $data2[$k] = $d1;
                }
                $j++;
            }
            $k = 0;
            $l = 0;
            foreach ($data2 as $k2 => $d2) {
                if (strstr($k2, 'under_act')) {
                    $temp['act'][$k] = $d2;
                    $k++;
                }
                if (strstr($k2, 'under_sec')) {
                    $temp['section'][$l] = $d2;
                    $l++;
                }
            }
            for ($l = 0, $sr = 5; $l < count($temp['act']); $l++, $sr++) {
                $tempdata[$l] = array(
                    'ref_m_efiling_nums_registration_id' => $registration_id,
                    'serialno' => $sr,
                    'acts' => $temp['act'][$l],
                    'section' => $temp['section'][$l],
                    'display' => 'Y',
                    'reconsume' => $reconsume
                );
            }
            $data2 = $tempdata;
        } else {
            foreach ($data['act'] as $k => $d1) {
                $temp[$k] = $d1;
            }
            foreach ($data['section'] as $k => $d1) {
                $temp[$k] = $d1;
            }
            $act_count = count($data['act']);
            if ($act_count == 1) {
                for ($i = 2; $i <= 4; $i++) {
                    $temp['under_act' . $i] = 0;
                    $temp['under_sec' . $i] = NULL;
                }
            } elseif ($act_count == 2) {
                for ($i = 3; $i <= 4; $i++) {
                    $temp['under_act' . $i] = 0;
                    $temp['under_sec' . $i] = NULL;
                }
            } elseif ($act_count == 3) {
                for ($i = 4; $i <= 4; $i++) {
                    $temp['under_act' . $i] = 0;
                    $temp['under_sec' . $i] = NULL;
                }
            }
            $temp['reconsume'] = $reconsume;
            $data1 = $temp;
        }
        $this->db->transStart();
        $builder = $this->db->table('tbl_extraact');
        $builder->delete(array('ref_m_efiling_nums_registration_id' => $registration_id));
        $builder = $this->db->table('tbl_efiling_civil');
        $builder->where('ref_m_efiling_nums_registration_id', $registration_id);
        $builder->set($data1);
        $builder->update();
        if ($this->db->affectedRows() > 0) {
            if (isset($data2) && !empty($data2)) {
                $builder = $this->db->table('tbl_extraact');
                $builder->insertBatch($data2);
                if ($this->db->affectedRows() > 0) {
                    $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                    if ($success) {
                        $this->db->transComplete();
                    }
                }
            } else {
                $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                if ($success) {
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

    function add_mvc($data) {
        $this->db->transStart();
        $builder = $this->db->table('tbl_mvc_t')
            ->INSERT($data);
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_mvc($registration_id, $data) {
        $this->db->transStart();
        $builder = $this->db->table('tbl_mvc_t')
            ->WHERE('ref_m_efiling_nums_registration_id', $registration_id)
            ->SET($data)
            ->UPDATE();
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function add_extra_party($data, $registration_id, $data_extra_pet_n_res) {
        $this->db->transStart();
        $builder = $this->db->table('tbl_efiling_civil_extra_party')
            ->INSERT($data);
        if ($this->db->insertID()) {
            $builder1 = $this->db->table('tbl_efiling_civil')
                ->WHERE('ref_m_efiling_nums_registration_id', $registration_id)
                ->SET($data_extra_pet_n_res)
                ->UPDATE();
            if ($this->db->affectedRows()) {
                $update_lrs = array('legal_heir' => 'Y');
                $builder2 = $this->db->table('tbl_efiling_civil_extra_party')
                    ->WHERE('ref_m_efiling_nums_registration_id', $registration_id)
                    ->WHERE('party_no', $data['parentid'])
                    ->SET($update_lrs)
                    ->UPDATE();
                if ($this->db->affectedRows()) {
                    $this->db->transComplete();
                } else {
                    $this->db->transComplete();
                }
                $this->db->transComplete();
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_extra_party($id, $data, $registration_id) {
        $this->db->transStart();
        $builder = $this->db->table('tbl_efiling_civil_extra_party')
            ->WHERE('id', $id)
            ->WHERE('ref_m_efiling_nums_registration_id', $registration_id)
            ->SET($data)
            ->UPDATE();
        if ($this->db->affectedRows()) {
            // $this->db->table('tbl_efiling_civil');
            // $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
            // $this->db->SET($data_extra_pet_n_res);
            // $this->db->UPDATE();
            if ($this->db->affectedRows()) {
                $this->db->transComplete();
            }
        } if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function get_Uploaded_Documents($reg_id) {
        $builder = $this->db->table('tbl_efiled_docs docs')
            ->SELECT('docs.doc_id,docs.doc_type_id,docs.doc_type_name,docs.sub_doc_type_id,
                docs.file_name,docs.page_no,docs.no_of_copies,docs.doc_type_name,docs.is_admin_checked,
                docs.uplodaded_by,docs.uploaded_on,docs.upload_ip_address,docs.file_uploaded_path,docs.doc_title,
                docs.doc_hashed_value')
            ->WHERE('docs.ref_m_efiling_nums_registration_id', $reg_id)
            ->orderBy('docs.doc_id');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }

    function getPdfDoc($pdf_id, $uploaded_by) {
        $builder = $this->db->table('tbl_efiled_docs')
            ->SELECT('tbl_efiled_docs.file_contents')
            ->WHERE('tbl_efiled_docs.doc_id', $pdf_id)
            ->WHERE('tbl_efiled_docs.uplodaded_by', $uploaded_by);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    function deletePdfDoc($pdf_id) {
        $builder = $this->db->table('tbl_efiled_docs')
            ->delete(array('doc_id' => $pdf_id));
        if ($this->db->affectedRows()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function delete_extra_party($id, $type, $extra_party_type, $extra_party_party_no) {
        $update_lrs_status = array('display' => 'N');
        $builder = $this->db->table('tbl_efiling_civil_extra_party')
            ->WHERE('id', $id)
            ->WHERE('ref_m_efiling_nums_registration_id', $_SESSION['efiling_details']['registration_id'])
            ->SET($update_lrs_status)
            ->UPDATE();
        if ($this->db->affectedRows() > 0) {
            $extra_party_count = $this->get_pet_n_res_count_civil($_SESSION['efiling_details']['registration_id']);
            if ($type == '1') {
                $data_extra_pet_n_res = array('pet_extracount' => $extra_party_count[0]['pet_extracount'] - 1);
            } elseif ($type == '2') {
                $data_extra_pet_n_res = array('res_extracount' => $extra_party_count[0]['res_extracount'] - 1);
            }
            $builder1 = $this->db->table('tbl_efiling_civil')
                ->WHERE('ref_m_efiling_nums_registration_id', $_SESSION['efiling_details']['registration_id'])
                ->SET($data_extra_pet_n_res)
                ->UPDATE();
            if ($this->db->affectedRows() > 0) {
                if ($extra_party_type == '1' && !empty($extra_party_party_no)) {
                    $builder2 = $this->db->table('tbl_efiling_civil_extra_party')
                        ->WHERE('parentid', $extra_party_party_no)
                        ->WHERE('ref_m_efiling_nums_registration_id', $_SESSION['efiling_details']['registration_id'])
                        ->SET($update_lrs_status)
                        ->UPDATE();
                    if ($this->db->affectedRows() >= 0) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                } else {
                    return TRUE;
                }
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function assign_party_no($reg_id) {
        $builder = $this->db->table('tbl_efiling_civil_extra_party')
            ->SELECT('max(party_no) AS party_no')
            ->WHERE('ref_m_efiling_nums_registration_id', $reg_id)
            ->WHERE('display', 'Y');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        }
    }

    function get_max_party_id($reg_id, $type, $lrs = NULL) {
        $builder = $this->db->table('tbl_efiling_civil_extra_party');
        $builder->SELECT('max(party_id::integer) AS party_id');
        $builder->WHERE('type', $type);
        if ($lrs == 0 || $lrs) {
            $builder->WHERE('parentid', $lrs);
        } else {
            $builder->WHERE('parentid', NULL);
        }
        $builder->WHERE('display', 'Y');
        $builder->WHERE('ref_m_efiling_nums_registration_id', $reg_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return FALSE;
        }
    }

    function get_extra_party_count($reg_id, $type) {
        $builder = $this->db->table('tbl_efiling_civil_extra_party')
            ->SELECT('count(CASE WHEN parentid = 0 THEN 1 END)-1 AS main_party_lrs,
                count(CASE WHEN parentid != 0 or parentid IS NULL THEN 1 END)
                as extry_party_lrs')
            ->WHERE('display', 'Y')
            ->WHERE('legal_heir', 'N')
            ->WHERE('type', $type)
            ->WHERE('ref_m_efiling_nums_registration_id', $reg_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_extra_party_details($registration_id) {
        $builder = $this->db->table('tbl_efiling_civil_extra_party')
            ->SELECT('*')
            ->WHERE('ref_m_efiling_nums_registration_id', $registration_id)
            ->WHERE('parentid', NULL)
            ->WHERE('display', 'Y')
            // $this->db->ORDER_BY("party_no", "asc")
            ->orderBy('type')
            ->orderBy('cast(party_id as float)');
        $query = $builder->get();
        return $query->getResult();
    }

    function get_LRs_details($registration_id) {
        $sql_query = "SELECT * FROM
            (SELECT parent_party.name parent_name, ecep.id, ecep.ref_m_efiling_nums_registration_id, ecep.party_no, ecep.parentid, ecep.name,ecep.father_name,
                ecep.extra_party_relation_name, ecep.extra_not_in_list_org, ecep.extra_party_org_name,ecep.address,ecep.extra_party_village_name,ecep.extra_party_ward_name,ecep.extra_party_town_name,
                ecep.extra_party_taluka_name,ecep.extra_party_distt_name,ecep.extra_party_state_name,ecep.pincode,ecep.pet_email,ecep.pet_mobile,ecep.type,ecep.other_info_flag,ecep.passportno,ecep.extra_party_o_state_name,
                ecep.panno,ecep.pet_phone,ecep.pet_fax,ecep.country,ecep.pet_nationality,ecep.pet_occu,ecep.extra_party_o_village_name,ecep.extra_party_o_ward_name,ecep.extra_party_o_town_name,
                ecep.extra_party_o_taluka_name,ecep.extra_party_o_taluka_name,ecep.extra_party_o_distt_name,ecep.altaddress
            FROM tbl_efiling_civil_extra_party ecep
            LEFT JOIN tbl_efiling_civil_extra_party parent_party  on parent_party.party_no = ecep.parentid
            WHERE ecep.ref_m_efiling_nums_registration_id = " . $registration_id . " and parent_party.ref_m_efiling_nums_registration_id = " . $registration_id . "
            AND ecep.parentid IS NOT NULL AND ecep.display = 'Y'
            UNION
            SELECT parent_party.pet_name parent_name, ecep.id, ecep.ref_m_efiling_nums_registration_id, ecep.party_no, ecep.parentid, ecep.name,ecep.father_name,
                ecep.extra_party_relation_name, ecep.extra_not_in_list_org, ecep.extra_party_org_name,ecep.address,ecep.extra_party_village_name,ecep.extra_party_ward_name,ecep.extra_party_town_name,
                ecep.extra_party_taluka_name,ecep.extra_party_distt_name,ecep.extra_party_state_name,ecep.pincode,ecep.pet_email,ecep.pet_mobile,ecep.type,ecep.other_info_flag,ecep.passportno,ecep.extra_party_o_state_name,
                ecep.panno,ecep.pet_phone,ecep.pet_fax,ecep.country,ecep.pet_nationality,ecep.pet_occu,ecep.extra_party_o_village_name,ecep.extra_party_o_ward_name,ecep.extra_party_o_town_name,
                ecep.extra_party_o_taluka_name,ecep.extra_party_o_taluka_name,ecep.extra_party_o_distt_name,ecep.altaddress
            FROM tbl_efiling_civil_extra_party ecep
            JOIN tbl_efiling_civil parent_party  on parent_party.ref_m_efiling_nums_registration_id = ecep.ref_m_efiling_nums_registration_id
            WHERE ecep.ref_m_efiling_nums_registration_id = " . $registration_id . "
            AND ecep.parentid = 0 AND ecep.type = 1 AND ecep.display = 'Y'
            UNION
            SELECT parent_party.res_name parent_name, ecep.id, ecep.ref_m_efiling_nums_registration_id, ecep.party_no, ecep.parentid, ecep.name,ecep.father_name,
                ecep.extra_party_relation_name, ecep.extra_not_in_list_org, ecep.extra_party_org_name,ecep.address,ecep.extra_party_village_name,ecep.extra_party_ward_name,ecep.extra_party_town_name,
                ecep.extra_party_taluka_name,ecep.extra_party_distt_name,ecep.extra_party_state_name,ecep.pincode,ecep.pet_email,ecep.pet_mobile,ecep.type,ecep.other_info_flag,ecep.passportno,ecep.extra_party_o_state_name,
                ecep.panno,ecep.pet_phone,ecep.pet_fax,ecep.country,ecep.pet_nationality,ecep.pet_occu,ecep.extra_party_o_village_name,ecep.extra_party_o_ward_name,ecep.extra_party_o_town_name,
                ecep.extra_party_o_taluka_name,ecep.extra_party_o_taluka_name,ecep.extra_party_o_distt_name,ecep.altaddress
            FROM tbl_efiling_civil_extra_party ecep
            JOIN tbl_efiling_civil parent_party  on parent_party.ref_m_efiling_nums_registration_id = ecep.ref_m_efiling_nums_registration_id
            WHERE ecep.ref_m_efiling_nums_registration_id = " . $registration_id . "
            AND ecep.parentid = 0 AND ecep.type = 2 AND ecep.display = 'Y') a
            ORDER BY a.id, a.party_no, a.parentid ASC";
        $query = $this->db->query($sql_query);
        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return FALSE;
        }
    }

    function get_extra_party_for_payment($registration_id) {
        $builder = $this->db->table('tbl_efiling_civil_extra_party')
            ->SELECT('id,party_no,orgid,name')
            ->WHERE('ref_m_efiling_nums_registration_id', $registration_id)
            ->WHERE('type', 1)
            ->orderBy("id", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    function fetch_e_party_details($id) {
        $builder = $this->db->table('tbl_efiling_civil_extra_party')
            ->SELECT('*')
            ->WHERE('id', $id)
            ->WHERE('ref_m_efiling_nums_registration_id', $this->session->userdata['efiling_details']['registration_id']);
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_efiling_for_details_on_reg_id($registration_id) {
        $builder = $this->db->table('efil.tbl_efiling_nums')
            ->SELECT("efiling_for_type_id,efiling_for_id")
            ->WHERE('registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    function get_hc_est_code($entry_for_type_id, $entry_for_id) {
        if ($entry_for_type_id == ENTRY_TYPE_FOR_HIGHCOURT) {
            $builder = $this->db->table('m_tbl_high_courts')
                ->SELECT("*")
                ->WHERE('id', $entry_for_id);
            $query = $builder->get();
            if ($query->getNumRows() >= 1) {
                $result = $query->getResult();
                $data = array('est_code' => $result[0]->hc_code);
                $this->session->set(array('national_code' => $data));
                return $result;
            } else {
                return false;
            }
        } elseif ($entry_for_type_id == ENTRY_TYPE_FOR_ESTABLISHMENT) {
            $builder = $this->db->table('m_tbl_establishments')
                ->SELECT("*")
                ->WHERE('id', $entry_for_id);
            $query = $builder->get();
            if ($query->getNumRows() >= 1) {
                $result = $query->getResult();
                $data = array('est_code' => $result[0]->est_code);
                $this->session->set(array('national_code' => $data));
                return $result;
            } else {
                return false;
            }
        } else {
            return FALSE;
        }
    }

    function get_receipts_doc($pdf_id) {
        $builder = $this->db->table('tbl_court_fee_payment')
            ->SELECT('receipt_name,reciept_uploaded_path')
            ->WHERE('id', $pdf_id);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    function update_deficitcourtfee($registration_id, $def_crt_fee) {
        $this->db->transStart();
        $update_data = array(
            'deactivated_on' => date('Y-m-d H:i:s'),
            'is_active' => FALSE,
            'updated_by' => $_SESSION['login']['id'],
            'updated_by_ip' => getClientIP()
        );
        $builder = $this->db->table('tbl_efiling_case_status')
            ->WHERE('registration_id', $registration_id)
            ->WHERE('is_active', TRUE)
            ->SET($update_data)
            ->UPDATE();
        if ($this->db->affectedRows() > 0) {
            $insert_data = array(
                'registration_id' => $registration_id,
                'stage_id' => DEFICIT_COURT_FEE,
                'activated_on' => date('Y-m-d H:i:s'),
                'is_active' => TRUE,
                'activated_by' => $_SESSION['login']['id'],
                'activated_by_ip' => getClientIP()
            );
            $builder1 = $this->db->table('tbl_efiling_case_status')
                ->INSERT($insert_data);
            if ($this->db->insertID()) {
                $builder2 = $this->db->table('tbl_court_fee_payment')
                    ->SELECT('max(id) max_id')
                    ->WHERE('registration_id', $registration_id)
                    ->WHERE('is_active', TRUE);
                $query = $builder2->get();
                $query_result = $query->getResult();
                $crt_fee_payment_max_id = $query_result[0]->max_id;
                $crt_fee_payment_exits = FALSE;
                $crt_fee_payment_update_status = FALSE;
                if ($crt_fee_payment_max_id != '' && $crt_fee_payment_max_id > 0) {
                    $crt_fee_payment_exits = TRUE;
                    $fee_data = array(
                        'is_payment_defective' => FALSE,
                        'is_payment_defecit' => TRUE,
                        'defecit_court_fee' => $def_crt_fee,
                        'payment_verified_date' => date('Y-m-d H:i:s'),
                        'payment_verified_by' => $_SESSION['login']['id']
                    );
                    $builder3 = $this->db->table('tbl_court_fee_payment')
                        ->WHERE('registration_id', $registration_id)
                        ->WHERE('is_active', TRUE)
                        ->WHERE('id', $crt_fee_payment_max_id)
                        ->SET($fee_data)
                        ->UPDATE();
                    if ($this->db->affectedRows() > 0) {
                        $crt_fee_payment_update_status = TRUE;
                    }
                }
                $initial_defect_exits = FALSE;
                $initial_defects_update_status = FALSE;
                $builder4 = $this->db->table('tbl_initial_defects')
                    ->SELECT('max(initial_defects_id) max_id')
                    ->WHERE('registration_id', $registration_id)
                    ->WHERE('is_defect_cured', TRUE)
                    ->WHERE('is_approved', FALSE);
                $query = $builder4->get();
                $query_result = $query->getResult();
                $initial_defect_max_id = $query_result[0]->max_id;
                if ($initial_defect_max_id != '' && $initial_defect_max_id > 0) {
                    $initial_defect_exits = TRUE;
                    $update_defect_data = array(
                        'is_approved' => TRUE,
                        'still_defective' => TRUE,
                        'approve_date' => date('Y-m-d H:i:s'),
                        'approved_by' => $_SESSION['login']['id']
                    );
                    $builder5 = $this->db->table('tbl_initial_defects')
                        ->WHERE('registration_id', $registration_id)
                        ->WHERE('is_defect_cured', TRUE)
                        ->WHERE('is_approved', FALSE)
                        ->WHERE('initial_defects_id', $initial_defect_max_id)
                        ->SET($update_defect_data)
                        ->UPDATE();
                    if ($this->db->affectedRows() > 0) {
                        $initial_defects_update_status = TRUE;
                    }
                }
                $initial_defects_insert_status = FALSE;
                if (($initial_defect_exits && $initial_defects_update_status) || (!$initial_defect_exits && !$initial_defects_update_status)) {
                    $insert = array(
                        'registration_id' => $registration_id,
                        'defect_remark' => "Deficit fee has to be paid Rupees :" . $def_crt_fee,
                        'defect_date' => date('Y-m-d H:i:s'),
                        'is_active' => TRUE,
                        'updated_by' => $_SESSION['login']['id'],
                        'ip_address' => getClientIP()
                    );
                    $builder6 = $this->db->table('tbl_initial_defects')->INSERT($insert);
                    if ($this->db->insertID()) {
                        $initial_defects_insert_status = TRUE;
                    }
                }
                if ($crt_fee_payment_exits && $crt_fee_payment_update_status && $initial_defect_exits && $initial_defects_update_status && $initial_defects_insert_status) {
                    $this->db->transComplete();
                } elseif (!$crt_fee_payment_exits && !$crt_fee_payment_update_status && $initial_defect_exits && $initial_defects_update_status && $initial_defects_insert_status) {
                    $this->db->transComplete();
                } elseif ($crt_fee_payment_exits && $crt_fee_payment_update_status && !$initial_defect_exits && !$initial_defects_update_status && $initial_defects_insert_status) {
                    $this->db->transComplete();
                } elseif (!$crt_fee_payment_exits && !$crt_fee_payment_update_status && !$initial_defect_exits && !$initial_defects_update_status && $initial_defects_insert_status) {
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

    function get_pet_n_res_count_civil($registration_id) {
        $builder = $this->db->table('tbl_efiling_civil')
            ->SELECT('pet_extracount,res_extracount')
            ->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $count = $query->getResultArray();
            return $count;
        } else {
            return 0;
        }
    }

    function get_efiling_for_details($efiling_for_type_id, $efiling_for_id) {
        if ($efiling_for_type_id == E_FILING_FOR_ESTABLISHMENT) {
            $builder = $this->db->table('m_tbl_establishments')
                ->SELECT("id,estname,est_code, ip_details, sms_gateway_url, establishment_email_id,
                    email_user_name, email_pwd, mail_host, enable_payment_gateway, is_charging_printing_cost, printing_cost,payment_gateway_params,pg_request_function,pg_response_function,sms_credentials,state_code")
                ->WHERE('id', $efiling_for_id)
                ->WHERE('display', 'Y')
                ->orderBy("estname", "asc");
        } elseif ($efiling_for_type_id == E_FILING_FOR_HIGHCOURT) {
            $builder = $this->db->table('m_tbl_high_courts')
                ->SELECT("id,hc_name estname,hc_code est_code, ip_details, sms_gateway_url, establishment_email_id,
                    email_user_name, email_pwd, mail_host, enable_payment_gateway, is_charging_printing_cost, printing_cost,payment_gateway_params,pg_request_function,pg_response_function,sms_credentials,state_code")
                ->WHERE('id', $efiling_for_id)
                ->WHERE('is_active', TRUE)
                ->orderBy("hc_name", "asc");
        }
        $query = $builder->get();
        return $query->getResult();
    }

    function get_doc_signature_status($reg_id) {
        $sql = " SELECT * from efil.esign_logs WHERE ( ref_registration_id = '" . $reg_id . "' AND is_data_valid = 'TRUE' AND ( type = 1 or type = 2 ) )";
        $query = $this->db->query($sql);
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE) {
            $sql2 = " SELECT * from efil.esign_logs WHERE ( ref_registration_id = '" . $reg_id . "' AND is_data_valid = 'TRUE' AND ( type = 3 ) )";
            $query2 = $this->db->query($sql2);
        }
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE) {
            if (($query->getNumRows() == 1) && ($query2->getNumRows() == 1)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else if ($_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON) {
            if (($query->getNumRows() == 1)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function get_esigned_file_size($txn_id, $reg_id) {
        $sql = "SELECT * FROM efil.esign_logs WHERE ref_registration_id = '" . $reg_id . "' AND request_txn_num ='" . $txn_id . "'";
        $query = $this->db->query($sql);
        if (($query->getNumRows())) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_esign_doc_pet($reg_id) {
        $sql = "SELECT * from efil.esign_logs WHERE ( ref_registration_id = '" . $reg_id . "' AND is_data_valid = 'TRUE' AND (  type = " . ESIGNED_DOCS_BY_PET .
                "or type = " . ESIGNED_DOCS_BY_ADV . "  ) )";
        $query = $this->db->query($sql);
        if (($query->getNumRows())) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_esign_doc_adv($reg_id) {
        $sql = " SELECT * from efil.esign_logs WHERE ( ref_registration_id = '" . $reg_id . "' AND is_data_valid = 'TRUE' AND (  type = " . ESIGNED_DOCS_BY_ADV3 . ") )";
        $query = $this->db->query($sql);
        if (($query->getNumRows())) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_everified_doc($reg_id) {
        $sql = " SELECT * from efil.esign_logs WHERE ( ref_registration_id = '" . $reg_id . "' AND is_data_valid = 'TRUE' AND (  type = " . EVERIFIED_DOCS_BY_MOB_OTP . ") )";
        $query = $this->db->query($sql);
        if (($query->getNumRows())) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_esign_failure_count($reg_id) {
        $sql = "SELECT count(errcode) errcode_count FROM esign_logs WHERE ref_registration_id = '" . $reg_id . "' AND is_data_valid = 'FALSE' AND errcode !='NA'
                    AND (  type = " . ESIGNED_DOCS_BY_PET . " OR type = " . ESIGNED_DOCS_BY_ADV . ") GROUP BY errcode";
        $query = $this->db->query($sql);
        if (($query->getNumRows())) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_state_max_establishment_id($state_id) {
        $builder = $this->db->table('m_tbl_establishments estab')
            ->SELECT('max(estab.id) max_id')
            ->JOIN('m_tbl_districts dist', 'estab.ref_m_tbl_districts_id=dist.id')
            ->WHERE('ref_m_tbl_states_id', $state_id)
            ->WHERE('dist.display', 'Y')
            ->WHERE('estab.display', 'Y')
            ->orderBy('estab.id')
            ->LIMIT(1);
        $query = $builder->get();
        $query_result = $query->getResult();
        $estab_max_id = $query_result[0]->max_id;
        return $estab_max_id;
    }

    function get_signed_details($registration_id) {
        $builder = $this->db->table('tbl_efiling_civil')
            ->SELECT('doc_signed_used,matter_type,macp')
            ->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_org_name($registration_id) {
        $builder = $this->db->table('tbl_cis_masters_values')
            ->SELECT("pet_org_name")
            ->WHERE('registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function get_national_code($reg_id) {
        if (!is_numeric($reg_id)) {
            return false;
        }
        $sql = " select *
                 from
                 efil.tbl_efiling_nums en
                 left join m_tbl_establishments est on est.id = en.efiling_for_id and en.efiling_for_type_id = 2
                 left join m_tbl_high_courts hc on hc.id = en.efiling_for_id and en.efiling_for_type_id = 1 and hc.is_active = 'TRUE'
                 where en.registration_id = '" . $reg_id . "' ";
        $query = $this->db->query($sql);
        if ($query->getNumRows() == 1) {
            $res = $query->getResultArray();
            if ($res[0]['efiling_for_type_id'] == ENTRY_TYPE_FOR_HIGHCOURT) {
                if ($res[0]['hc_code'] != NULL && $res[0]['hc_code'] != '') {
                    return $res[0]['hc_code'];
                } else {
                    return false;
                }
            } else if ($res[0]['efiling_for_type_id'] == ENTRY_TYPE_FOR_ESTABLISHMENT) {
                if ($res[0]['est_code'] != NULL && $res[0]['est_code'] != '') {
                    return $res[0]['est_code'];
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function get_max_item_number($id, $registration_id) {
        $sql = "select count(*) as count from tbl_efiling_mvc where updated_by_user_id = '" . $id . "' AND ref_m_efiling_nums_registration_id = '" . $registration_id . "' ";
        $query = $this->db->query($sql);
        $res = $query->getResultArray();
        return $res[0]['count'] + 1;
    }

    function mvc_add_data($data) {
        $builder = $this->db->table('tbl_efiling_mvc')->insert($data);
        if ($this->db->insertID()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_mvc_data($id) {
        if (is_numeric($id)) {
            $sql = "select * from tbl_efiling_mvc where id = " . $id;
            $query = $this->db->query($sql);
            $res = $query->getResultArray();
            return $res;
        } else {
            return NULL;
        }
    }

    function get_mvc_total_applications($id) {
        $sql = "select * from tbl_efiling_mvc where ref_m_efiling_nums_registration_id = '" . $id . "' ORDER BY id ASC";
        $query = $this->db->query($sql);
        $res = $query->getResult();
        return $res;
    }

    function mvc_update_data($data, $id) {
        $builder = $this->db->table('tbl_efiling_mvc')->WHERE('id', $id)->SET($data)->UPDATE();
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_application_data($table_id, $login_id, $efiling_for_type_id) {
        if ($efiling_for_type_id == E_FILING_FOR_HIGHCOURT) {
            $builder = $this->db->table('tbl_efiling_mvc mvc');
            $builder->SELECT('mvc.*,hc.hc_name estab_name');
            $builder->JOIN('efil.tbl_efiling_nums en', 'en.registration_id = mvc.ref_m_efiling_nums_registration_id', 'left');
            $builder->JOIN('m_tbl_high_courts hc', 'hc.id = en.efiling_for_id', 'left');
        } elseif ($efiling_for_type_id == E_FILING_FOR_ESTABLISHMENT) {
            $builder = $this->db->table('tbl_efiling_mvc mvc');
            $builder->SELECT('mvc.*,estab.estname estab_name');
            $builder->JOIN('efil.tbl_efiling_nums en', 'en.registration_id = mvc.ref_m_efiling_nums_registration_id', 'left');
            $builder->JOIN('m_tbl_establishments estab', 'estab.id = en.efiling_for_id', 'left');
        }
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON) {
            $builder->WHERE('mvc.updated_by_user_id', $login_id);
        }
        $builder->WHERE('mvc.id', $table_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $res = $query->getResultArray();
            return $res;
        } else {
            return false;
        }
    }

    function get_petitioner_data($registration_id) {
        if (is_numeric($registration_id)) {
            $sql = "select * from tbl_efiling_civil where ref_m_efiling_nums_registration_id = " . $registration_id;
            $query = $this->db->query($sql);
            $res = $query->getResultArray();
            return $res;
        } else {
            return false;
        }
    }

    function register_adv_bar_no($login_id, $hc_id, $court_type) {
        $data = array('login_id' => $login_id, 'high_court_id' => $hc_id, 'efiling_for_type_id' => $court_type, 'add_date' => date('Y-m-d H:i:s'), 'is_register' => 'N');
        $builder = $this->db->table('tbl_advocate_register')
            ->SELECT('login_id,high_court_id')
            ->WHERE('login_id', $login_id)
            ->WHERE('high_court_id', $hc_id)
            ->WHERE('efiling_for_type_id', $court_type)
            ->WHERE('is_active', TRUE);
        $query = $builder->get();
        $row = $query->getResultArray();
        if ($query->getNumRows() == 1) {
            return TRUE;
        } else {
            $builder1 = $this->db->table('tbl_advocate_register')->insert($data);
            return TRUE;
        }
    }

    function save_main_matter_data($registration_id, $data) {
        $this->db->transStart();
        $builder = $this->db->table('tbl_cis_masters_values')->WHERE('registration_id', $registration_id)->SET($data)->UPDATE();
        if ($this->db->affectedRows() > 0) {
            $main_matter_cino = array('main_matter_cino' => $data['main_matter_cnr_num']);
            $builder1 = $this->db->table('tbl_efiling_civil')->WHERE('ref_m_efiling_nums_registration_id', $registration_id)->SET($main_matter_cino)->UPDATE();
            if ($this->db->affectedRows() > 0) {
                $this->db->transComplete();
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function get_main_matter_details($registration_id) {
        $builder = $this->db->table('tbl_cis_masters_values')
            ->SELECT('registration_id,main_matter_cnr_num,main_matter_case_type_name ,main_matter_case_num,main_matter_case_year,main_matter_pet_name,main_matter_res_name,main_matter_court_name')
            ->WHERE('registration_id', $registration_id)
            ->orderBy("id", "asc");
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function delete_main_matter_data($registration_id) {
        $data = array(
            'main_matter_cnr_num' => NULL,
            'main_matter_case_num' => NULL,
            'main_matter_case_year' => NULL,
            'main_matter_case_type_name' => NULL,
            'main_matter_pet_name' => NULL,
            'main_matter_res_name' => NULL
        );
        $this->db->transStart();
        $builder = $this->db->table('tbl_cis_masters_values')->WHERE('registration_id', $registration_id)->SET($data)->UPDATE();
        if ($this->db->affectedRows() > 0) {
            $main_matter_cino = array('main_matter_cino' => NULL);
            $builder1 = $this->db->table('tbl_efiling_civil')->WHERE('ref_m_efiling_nums_registration_id', $registration_id)->SET($main_matter_cino)->UPDATE();
            if ($this->db->affectedRows() > 0) {
                $this->db->transComplete();
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function add_police_station($registration_id, $data, $cis_masters_values) {
        $this->db->transStart();
        $builder = $this->db->table('tbl_efiling_civil')->WHERE('ref_m_efiling_nums_registration_id', $registration_id)->SET($data)->UPDATE();
        if ($this->db->affectedRows() > 0) {
            if ($cis_masters_values != NULL) {
                $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                if ($success) {
                    $this->db->transComplete();
                }
            } else {
                $this->db->transComplete();
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function get_efiling_civil_master_value($registration_id) {
        $builder = $this->db->table('tbl_cis_masters_values')
            ->SELECT('*')
            ->WHERE('registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_IA_No($registration_id) {
        $builder = $this->db->table('tbl_misc_doc_filing as en')
            ->SELECT('count(id)')
            ->where('efiling_case_reg_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResult();
            return $result[0]->count;
        } else {
            return false;
        }
    }

    function get_allocated_to_details($registration_id) {
        $builder = $this->db->table('efil.tbl_efiling_nums')
            ->SELECT("efil.tbl_efiling_nums.*,concat(users.first_name,' ',users.last_name) as admin_name,(CASE WHEN efil.tbl_efiling_nums.efiling_for_type_id =" . E_FILING_FOR_ESTABLISHMENT . " THEN (SELECT concat(estname,', ',dist_name,', ',state)  FROM m_tbl_establishments est
                LEFT JOIN m_tbl_state st on est.state_code = st.state_id
                LEFT JOIN m_tbl_districts dist on est.ref_m_tbl_districts_id = dist.id
                WHERE est.id = efil.tbl_efiling_nums.efiling_for_id )
                ELSE (select concat(hc_name,' High Court') FROM m_tbl_high_courts hc
                WHERE hc.id = efil.tbl_efiling_nums.efiling_for_id) END )
                as efiling_for_name")
            ->JOIN('efil.tbl_users as users', 'users.id = efil.tbl_efiling_nums.allocated_to', 'LEFT')
            ->WHERE('efil.tbl_efiling_nums.registration_id', $registration_id)
            ->WHERE('efil.tbl_efiling_nums.is_active', TRUE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function get_submitted_on($registration_id) {
        $array = array(Initial_Approaval_Pending_Stage, I_B_Defects_Cured_Stage, Initial_Defects_Cured_Stage);
        $builder = $this->db->table('tbl_efiling_case_status')
            ->SELECT("*")
            ->WHERE('tbl_efiling_case_status.registration_id', $registration_id)
            ->whereIn('tbl_efiling_case_status.stage_id', $array)
            ->orderBy('status_id', 'DESC')
            ->limit(1, 0);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function add_high_court_info($registration_id, $data, $cis_masters_values) {
        $this->db->transStart();
        $update_date = array('is_deleted' => TRUE);
        $builder = $this->db->table('etrial_lower_court')
            ->WHERE('registration_id', $registration_id)
            ->WHERE('is_deleted', FALSE)
            // ->whereNotIn('id', $ids)
            ->SET($update_date)
            ->UPDATE();
        $builder1 = $this->db->table('etrial_lower_court')->insert($data);
        if ($this->db->insertID()) {
            if (!empty($cis_masters_values)) {
                $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                if ($success) {
                    $this->db->transComplete();
                }
            } else {
                $this->db->transComplete();
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_subordinate_court_info($id, $registration_id, $data, $cis_masters_values) {
        $this->db->transStart();
        $builder = $this->db->table('etrial_lower_court')
            ->WHERE('registration_id', $registration_id)
            ->WHERE('id', $id)
            ->SET($data)
            ->UPDATE();
        if ($this->db->affectedRows() > 0) {
            if (!empty($cis_masters_values)) {
                $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                if ($success) {
                    $this->db->transComplete();
                }
            } else {
                $this->db->transComplete();
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function get_lower_trial_court_details($registration_id, $form_type, $court_type) {
        $builder = $this->db->table('etrial_lower_court')
            ->SELECT('*')
            ->WHERE('registration_id', $registration_id)
            ->WHERE('sub_qj_high', $form_type)
            ->WHERE('lower_trial', $court_type)
            ->WHERE('is_deleted', FALSE)
            ->orderBy("id", "asc");
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function get_hc_details_subordinate_court($registration_id, $form_type, $court_type) {
        $builder = $this->db->table('etrial_lower_court elc')
            ->SELECT('elc.*,cis_mater.high_court_case_type')
            ->JOIN('tbl_cis_masters_values cis_mater', 'elc.registration_id = cis_mater.registration_id', 'LEFT')
            ->WHERE('elc.registration_id', $registration_id)
            ->WHERE('elc.sub_qj_high', $form_type)
            ->WHERE('elc.lower_trial', $court_type)
            ->WHERE('elc.is_deleted', FALSE)
            ->orderBy("elc.id", "asc");
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function get_sub_qj_hc_court_details($registration_id) {
        $builder = $this->db->table('etrial_lower_court')
            ->SELECT('*')
            ->WHERE('registration_id', $registration_id)
            ->WHERE('is_deleted', FALSE)
            ->orderBy("id", "asc");
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function update_organisation_value($registration_id, $data, $cis_masters_values) {
        $this->db->transStart();
        $builder = $this->db->table('tbl_efiling_civil')
            ->WHERE('ref_m_efiling_nums_registration_id', $registration_id)
            ->SET($data)
            ->UPDATE();
        if ($this->db->affectedRows() > 0) {
            if ($cis_masters_values != NULL) {
                $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                if ($success) {
                    $this->db->transComplete();
                }
            } else {
                $this->db->transComplete();
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_extra_party_org_value($registration_id, $id, $type, $data) {
        $this->db->transStart();
        $builder = $this->db->table('tbl_efiling_civil_extra_party')
            ->WHERE('ref_m_efiling_nums_registration_id', $registration_id)
            ->WHERE('id', $id)
            ->WHERE('type', $type)
            ->SET($data)
            ->UPDATE();
        if ($this->db->affectedRows() > 0) {
            $this->db->transComplete();
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function new_case_org_details($registration_id) {
        $builder = $this->db->table('tbl_efiling_civil ec')
            ->SELECT('ec.orgid,ec.not_in_list_org,ec.res_not_in_list_org,ec.resorgid,extra_party.name,extra_party.type,extra_party.orgid extra_party_orgid,extra_party.extra_not_in_list_org')
            ->JOIN('tbl_efiling_civil_extra_party extra_party', 'ec.ref_m_efiling_nums_registration_id = extra_party.ref_m_efiling_nums_registration_id', 'LEFT')
            ->WHERE('ec.ref_m_efiling_nums_registration_id', $registration_id)
            ->WHERE('extra_party.display', 'Y')
            ->orderBy('type', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function add_case_detail($registration_id, $cis_masters_values, $data) {
        $this->db->transStart();
        $builder = $this->db->table('tbl_efiling_civil')
            ->WHERE('ref_m_efiling_nums_registration_id', $registration_id)
            ->SET($data)
            ->UPDATE();
        if ($this->db->affectedRows() > 0) {
            if ($cis_masters_values != NULL) {
                $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                if ($success) {
                    $this->db->transComplete();
                }
            } else {
                $this->db->transComplete();
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function reset_subordinate_court_data($registration_id) {
        $this->db->transStart();
        $data = array('is_deleted' => TRUE);
        $builder = $this->db->table('etrial_lower_court')
            ->WHERE('registration_id', $registration_id)
            ->WHERE('is_deleted', FALSE)
            ->SET($data)
            ->UPDATE();
        if ($this->db->affectedRows() > 0) {
            $cis_masters_values = array(
                'app_court_state_name' => NULL,
                'app_court_distt_name' => NULL,
                'app_court_sub_court_name' => NULL,
                'app_court_sub_case_type' => NULL,
                'trial_court_state_name' => NULL,
                'trial_court_distt_name' => NULL,
                'trial_court_sub_court_name' => NULL,
                'trial_court_case_type' => NULL,
                'high_court_case_type' => NULL
            );
            $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
            if ($success) {
                $this->db->transComplete();
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function delete_mvc_details($id) {
        $builder = $this->db->table('tbl_efiling_mvc')
            ->WHERE('id', $id)
            ->WHERE('ref_m_efiling_nums_registration_id', $_SESSION['efiling_details']['registration_id'])
            ->DELETE();
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_pet_and_res_list($registration_id, $type) {
        $builder = $this->db->table('tbl_efiling_civil cl')
            ->SELECT('cl.id civilid,pet_name,res_name,exp.id expid,name,lname,extra_party_org_name,pet_legal_heir,res_legal_heir,party_no,party_id,type,exp.display,exp.parentid')
            ->JOIN('tbl_efiling_civil_extra_party exp', "cl.ref_m_efiling_nums_registration_id = exp.ref_m_efiling_nums_registration_id", 'LEFT')
            ->WHERE('cl.ref_m_efiling_nums_registration_id', $registration_id)
            ->WHERE('cl.display', 'Y');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_extra_parties_without_lrs($registration_id, $shuffle_party_type) {
        $builder = $this->db->table('tbl_efiling_civil_extra_party ex')
            ->SELECT('id,type, party_no, name, parentid, party_id, legal_heir')
            ->WHERE('ex.ref_m_efiling_nums_registration_id', $registration_id)
            ->WHERE('ex.parentid IS NULL')
            ->WHERE('ex.type', $shuffle_party_type)
            ->orderBy('type')
            ->orderBy('cast(party_id as float)');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_extra_parties_with_lrs($registration_id, $shuffle_party_type) {
        $builder = $this->db->table('tbl_efiling_civil_extra_party ex')
            ->SELECT('id,type, party_no, name, parentid, party_id, legal_heir')
            ->WHERE('ex.ref_m_efiling_nums_registration_id', $registration_id)
            ->WHERE('ex.type', $shuffle_party_type)
            // $this->db->WHERE('ex.display','Y')
            ->orderBy('type')
            ->orderBy('cast(party_id as float)');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function update_extra_party_position($data, $parent_data) {
        $this->db->transStart();
        $builder = $this->db->table('tbl_efiling_civil_extra_party')->updateBatch($data, 'id');
        if ($this->db->affectedRows() > 0) {
            if (!empty($parent_data)) {
                $builder1 = $this->db->table('tbl_efiling_civil_extra_party')->updateBatch($parent_data, 'id');
            }
            $this->db->transComplete();
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function add_efiled_case($data, $pet_mobile, $pet_email, $cis_masters_values, $estab_code, $state_code, $efiling_for_type_id, $entry_for_id, $breadcrumb, $extra_party_details, $efiling_civil_data, $subordinate_court_data, $mvc_details) {
        $this->db->transStart();
        if (isset($_SESSION['estab_details']['hc_code'])) {
            $efiling_for_type_id = ENTRY_TYPE_FOR_HIGHCOURT;
            $estab_code = $_SESSION['estab_details']['hc_code'];
        } else {
            $efiling_for_type_id = ENTRY_TYPE_FOR_ESTABLISHMENT;
            $estab_code = $_SESSION['estab_details']['estab_code'];
        }
        $curr_dt_time = date('Y-m-d H:i:s');
        if (empty($estab_code)) {
            return FALSE;
        }
        //  if ($_SESSION['wants_to_file'] == E_FILING_TYPE_NEW_CASE) {
        $result = $this->gen_share_efiling_number($estab_code, $efiling_for_type_id, $entry_for_id);
        $num_pre_fix = "EC";
        $filing_type = E_FILING_TYPE_NEW_CASE;
        $stage_id = Draft_Stage;
        if ($result) {
            if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
                $created_by = 0;
                $sub_created_by = $_SESSION['login']['id'];
            } else {
                $created_by = $_SESSION['login']['id'];
                $sub_created_by = 0;
            }
            $efiling = sprintf("%'.05d\n", $result['efiling_num']);
            $string = $num_pre_fix . $estab_code . $efiling . $result['efiling_year'];
            $efiling_num = preg_replace('/\s+/', '', $string);
            $efiling_num_data = array(
                'efiling_no' => $efiling_num,
                'efiling_year' => $result['efiling_year'],
                'efiling_for_type_id' => $efiling_for_type_id,
                'efiling_for_id' => $entry_for_id,
                'ref_m_efiled_type_id' => $filing_type,
                'created_by' => $created_by,
                'create_on' => $curr_dt_time,
                'create_by_ip' => getClientIP(),
                'sub_created_by' => $sub_created_by,
                'breadcrumb_status' => $breadcrumb,
            );
            $result['registration_id'] = $this->add_efiling_nums($efiling_num_data, '');
            if (isset($result['registration_id']) && !empty($result['registration_id'])) {
                $data['ref_m_efiling_nums_registration_id'] = $result['registration_id'];
                $session_data = array('registration_id' => $result['registration_id'], 'efiling_no' => $efiling_num);
                $this->session->set('efiling_details', $session_data);
                $cis_masters_values['filcase_type_name'] = $_SESSION['efiling_for_details']['case_type_name'];
                $cis_masters_values['bench_name'] = $_SESSION['efiling_for_details']['benchName'];
                $builder = $this->db->table('tbl_efiling_civil')->insert($data);
                if ($this->db->insertID()) {
                    $registration_id = $result['registration_id'];
                    if (count($extra_party_details) > 0) {
                        foreach ($extra_party_details as $extra_party) {
                            $extra_party_data[] = array(
                                'ref_m_efiling_nums_registration_id' => $registration_id,
                                'orgid' => $extra_party->orgid,
                                'name' => $extra_party->name,
                                'address' => $extra_party->address,
                                'pet_age' => $extra_party->pet_age,
                                'father_name' => $extra_party->father_name,
                                'father_flag' => $extra_party->father_flag,
                                'pet_religion' => $extra_party->pet_religion,
                                'pet_caste' => $extra_party->pet_caste,
                                'pet_sex' => $extra_party->pet_sex,
                                'type' => $extra_party->type,
                                'pet_occu' => $extra_party->pet_occu,
                                'pet_email' => $extra_party->pet_email,
                                'pet_mobile' => $extra_party->pet_mobile,
                                'state_id' => $extra_party->state_id,
                                'dist_code' => $extra_party->dist_code,
                                'taluka_code' => $extra_party->taluka_code,
                                'village_code' => $extra_party->village_code,
                                'town_code' => $extra_party->town_code,
                                'ward_code' => $extra_party->ward_code,
                                'pincode' => $extra_party->pincode,
                                'police_st_code' => $extra_party->police_st_code,
                                'other_info_flag' => $extra_party->other_info_flag,
                                'performaresflag' => $extra_party->performaresflag,
                                'passportno' => $extra_party->passportno,
                                'panno' => $extra_party->panno,
                                'pet_phone' => $extra_party->pet_phone,
                                'pet_fax' => $extra_party->pet_fax,
                                'country' => $extra_party->country,
                                'pet_nationality' => $extra_party->pet_nationality,
                                'altaddress' => $extra_party->altaddress,
                                'altstate_id' => $extra_party->altstate_id,
                                'altdist_code' => $extra_party->altdist_code,
                                'alttaluka_code' => $extra_party->alttaluka_code,
                                'altvillage_code' => $extra_party->altvillage_code,
                                'alttown_code' => $extra_party->alttown_code,
                                'altward_code' => $extra_party->altward_code,
                                'extra_party_org_name' => $extra_party->extra_party_org_name,
                                'extra_party_caste_name' => $extra_party->extra_party_caste_name,
                                'extra_party_state_name' => $extra_party->extra_party_state_name,
                                'extra_party_distt_name' => $extra_party->extra_party_distt_name,
                                'extra_party_taluka_name' => $extra_party->extra_party_taluka_name,
                                'extra_party_town_name' => $extra_party->extra_party_town_name,
                                'extra_party_ward_name' => $extra_party->extra_party_ward_name,
                                'extra_party_village_name' => $extra_party->extra_party_village_name,
                                'extra_party_ps_name' => $extra_party->extra_party_ps_name,
                                'extra_party_o_state_name' => $extra_party->extra_party_o_state_name,
                                'extra_party_o_distt_name' => $extra_party->extra_party_o_distt_name,
                                'extra_party_o_taluka_name' => $extra_party->extra_party_o_taluka_name,
                                'extra_party_o_town_name' => $extra_party->extra_party_o_town_name,
                                'extra_party_o_ward_name' => $extra_party->extra_party_o_ward_name,
                                'extra_party_o_village_name' => $extra_party->extra_party_o_village_name,
                                'extra_not_in_list_org' => $extra_party->extra_not_in_list_org,
                                'extra_party_religion_name' => $extra_party->extra_party_religion_name,
                                'extra_party_relation_name' => $extra_party->extra_party_relation_name,
                                'party_id' => $extra_party->party_id,
                                'parentid' => $extra_party->parentid,
                                'reconsume' => $extra_party->reconsume
                            );
                        }
                        $add_extra_party = $this->add_efiled_extra_party($extra_party_data);
                    }
                    if (count($efiling_civil_data[0]->serialno)) {
                        foreach ($efiling_civil_data as $efiling_civil) {
                            $act_data[] = array(
                                'ref_m_efiling_nums_registration_id' => $registration_id,
                                'serialno' => $efiling_civil->serialno,
                                'case_no' => $efiling_civil->case_no,
                                'acts' => $efiling_civil->acts,
                                'section' => $efiling_civil->section,
                                'display' => $efiling_civil->display,
                                'reconsume' => $efiling_civil->reconsume
                            );
                        }
                        $add_act = $this->add_act_data($act_data);
                    }
                    if ((!empty($subordinate_court_data[0]['registration_id']))) {
                        $court_data = array('lower_state_id' => $subordinate_court_data[0]['lower_state_id'],
                            'lower_dist_code' => $subordinate_court_data[0]['lower_dist_code'],
                            'lower_court_code' => $subordinate_court_data[0]['lower_court_code'],
                            'lower_cino' => $subordinate_court_data[0]['lower_cino'],
                            'lower_judge_name' => $subordinate_court_data[0]['lower_judge_name'],
                            'lower_court' => $subordinate_court_data[0]['lower_court'],
                            'filing_case' => $subordinate_court_data[0]['filing_case'],
                            'lower_court_dec_dt' => $subordinate_court_data[0]['lower_court_dec_dt'],
                            'lower_dist_code' => $subordinate_court_data[0]['lower_dist_code'],
                            'lregis_date' => $subordinate_court_data[0]['lregis_date'],
                            'lcc_applied_date' => $subordinate_court_data[0]['lcc_applied_date'],
                            'lcc_received_date' => $subordinate_court_data[0]['lcc_received_date'],
                            'case_no' => $subordinate_court_data[0]['case_no'],
                            'filing_no' => $subordinate_court_data[0]['filing_no'],
                            'lowerjocode' => $subordinate_court_data[0]['lowerjocode'],
                            'lower_taluka_code' => $subordinate_court_data[0]['lower_taluka_code'],
                            'lower_state_id' => $subordinate_court_data[0]['lower_state_id'],
                            'amd' => $subordinate_court_data[0]['amd'],
                            'qjnumber' => $subordinate_court_data[0]['qjnumber'],
                            'case_ref_no' => $subordinate_court_data[0]['case_ref_no'],
                            'date_of_order' => $subordinate_court_data[0]['date_of_order'],
                            'ljudid' => $subordinate_court_data[0]['ljudid'],
                            'judg_local_lang' => $subordinate_court_data[0]['judg_local_lang'],
                            'langflag' => $subordinate_court_data[0]['langflag'],
                            'oagainst' => $subordinate_court_data[0]['oagainst'],
                            'lower_trial' => $subordinate_court_data[0]['lower_trial'],
                            'sub_qj_high' => $subordinate_court_data[0]['sub_qj_high'],
                            'efilno' => $subordinate_court_data[0]['efilno'],
                            'registration_id' => $registration_id,
                            ' is_deleted' => $subordinate_court_data[0]['is_deleted'],
                            'reconsume' => $subordinate_court_data[0]['reconsume'],
                        );
                        $add_sub_court = $this->add_efiled_sub_court($court_data);
                    }
                    if (count($mvc_details) > 0) {
                        foreach ($mvc_details as $mvc) {
                            $mvc_data[] = array(
                                'ref_m_efiling_nums_registration_id' => $registration_id,
                                'other_po_stn' => $mvc->other_po_stn,
                                'case_no' => $mvc->case_no,
                                'item_no' => $mvc->item_no,
                                'police_stn_code' => $mvc->police_stn_code,
                                'other_police_stn' => $mvc->other_police_stn,
                                'fir_no' => $mvc->fir_no,
                                'year' => $mvc->year,
                                'accident_date' => $mvc->accident_date,
                                'accident_place' => $mvc->accident_place,
                                'compensation' => $mvc->compensation,
                                'insurance_company' => $mvc->insurance_company,
                                'vehicle_type' => $mvc->vehicle_type,
                                'vehicle_regn_no' => $mvc->vehicle_regn_no,
                                'display' => $mvc->display,
                                'accident_time' => $mvc->accident_time,
                                'dist_code' => $mvc->dist_code,
                                'taluka_code' => $mvc->taluka_code,
                                'fir_type_code' => $mvc->fir_type_code,
                                'driving_license' => $mvc->driving_license,
                                'issuing_authority' => $mvc->issuing_authority,
                                'owner_name' => $mvc->owner_name,
                                'lowner_name' => $mvc->lowner_name,
                                'lissuing_authority' => $mvc->lowner_name,
                                'laccident_place' => $mvc->laccident_place,
                                'cino' => $mvc->cino,
                                'efilno' => $mvc->efilno,
                                'econfirm' => $mvc->econfirm,
                                'state_id' => $mvc->state_id,
                                'state_id_name' => $mvc->state_id,
                                'dist_code_name' => $mvc->dist_code_name,
                                'taluka_code_name' => $mvc->taluka_code_name,
                                'police_stn_code_name' => $mvc->police_stn_code_name,
                                'injurytype' => $mvc->injurytype,
                                'father_husband_relation_of_petitioner' => $mvc->father_husband_relation_of_petitioner,
                                'father_husband_title_of_petitioner' => $mvc->father_husband_title_of_petitioner,
                                'father_husband_name_of_petitioner' => $mvc->father_husband_name_of_petitioner,
                                'petitioner_filing_on_behalf_of' => $mvc->petitioner_filing_on_behalf_of,
                                'party_name' => $mvc->party_name,
                                'father_husband_relation_of_party' => $mvc->father_husband_relation_of_party,
                                'father_husband_title_of_party' => $mvc->father_husband_title_of_party,
                                'father_husband_name_of_party' => $mvc->father_husband_name_of_party,
                                'address4' => $mvc->address4,
                                'age4' => $mvc->age4,
                                'occupation4' => $mvc->occupation4,
                                'name_and_address4' => $mvc->name_and_address4,
                                'monthly_income4' => $mvc->monthly_income4,
                                'name_age_dependents4' => $mvc->name_age_dependents4,
                                'details_property_damaged4' => $mvc->details_property_damaged4,
                                'does_person_pay_income_tax4' => $mvc->does_person_pay_income_tax4,
                                ' was_vechile_involved4' => $mvc->was_vechile_involved4,
                                'was_vechile_involved_full_description4' => $mvc->was_vechile_involved_full_description4,
                                'name_medical_officer4' => $mvc->name_medical_officer4,
                                'period_of_treatment4' => $mvc->period_of_treatment4,
                                'name_address_owner_vehicle4' => $mvc->name_address_owner_vehicle4,
                                'name_address_insurer_vehicle4' => $mvc->name_address_insurer_vehicle4,
                                'has_any_claim_lodged4' => $mvc->has_any_claim_lodged4,
                                'has_any_claim_lodged_if_yes_detail4' => $mvc->has_any_claim_lodged_if_yes_detail4,
                                'relationship_with_deceased4' => $mvc->relationship_with_deceased4,
                                'title_to_property_deceased4' => $mvc->title_to_property_deceased4,
                                'any_other_information' => $mvc->any_other_information,
                                'have_not_filed_any_other_application' => $mvc->have_not_filed_any_other_application,
                                'updated_by_user_id' => $mvc->updated_by_user_id,
                                'updated_datetime' => $mvc->updated_datetime,
                                'fir_type_code_name' => $mvc->fir_type_code_name,
                                'reconsume' => $mvc->reconsume,
                            );
                        }
                        $add_mvc = $this->add_efiled_mvc($mvc_data);
                    }
                    $success = $this->update_cis_master_values($result['registration_id'], $cis_masters_values);
                    if ($success) {
                        $data3 = array(
                            'registration_id' => $result['registration_id'],
                            'stage_id' => $stage_id,
                            'activated_on' => $curr_dt_time,
                            'activated_by' => getSessionData('login')['id'],
                            'activated_by_ip' => getClientIP(),
                        );
                        $builder1 = $this->db->table('tbl_efiling_case_status')->insert($data3);
                        if ($this->db->insertID()) {
                            $user_name = getSessionData('login')['first_name'] . ' ' . getSessionData('login')['last_name'];
                            $sentSMS = "Efiling No. " . efile_preview($efiling_num) . " generated from your efiling account & still pending for final submit. - Supreme Court of India";
                            $subject = "Efiling No. " . efile_preview($efiling_num) . " generated from your efiling account";
                            $Petsubject = "Efiling No. " . efile_preview($efiling_num) . " generated from for your petition";
                            $sentPetSMS = "Efiling No. " . efile_preview($efiling_num) . "   is generated for your petition filed by your Advocate" . $user_name . " and still pending for final submission.";
                            
                            if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE) {
                                $pet_user_name = $data['pet_name'];
                                if (!empty($data['pet_mobile'])) {
                                    send_petitioner_mobile_sms($data['pet_mobile'], $sentPetSMS);
                                }
                                if (!empty($data['pet_email'])) {
                                    send_petitioner_mail_msg($data['pet_email'], $Petsubject, $sentPetSMS, $pet_user_name);
                                }
                            }
                            send_mobile_sms($_SESSION['login']['mobile_number'], $sentSMS,SCISMS_Efiling_No_Generated);
                            send_mail_msg($_SESSION['login']['emailid'], $subject, $sentSMS, $user_name);
                            $this->db->transComplete();
                        }
                    }
                }
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return $result;
        }
    }

    function gen_share_efiling_number($estab_code, $efiling_for_type_id, $entry_for_id) {
        $builder = $this->db->table('efil.m_tbl_efiling_no')
            ->SELECT('efiling_no,efiling_year')
            ->WHERE('entry_for_type', $efiling_for_type_id)
            ->WHERE('ref_m_establishment_id', $entry_for_id);
        $query = $builder->get();
        $row = $query->getResultArray();
        $p_efiling_num = $row[0]['efiling_no'];
        $year = $row[0]['efiling_year'];
        if ($year < date('Y')) {
            $newYear = date('Y');
            $update_data = array(
                'efiling_no' => 1,
                'efiling_year' => $newYear,
                'updated_by' => getSessionData('login')['id'],
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by_ip' => getClientIP()
            );
            $builder1 = $this->db->table('efil.m_tbl_efiling_no')
                ->WHERE('efiling_no', $p_efiling_num)
                ->WHERE('efiling_year', $year)
                ->WHERE('entry_for_type', $efiling_for_type_id)
                ->WHERE('ref_m_establishment_id', $entry_for_id)
                ->SET($update_data)
                ->UPDATE();
            if ($this->db->affectedRows() > 0) {
                $data['efiling_num'] = 1;
                $data['efiling_year'] = $newYear;
                return $data;
            } else {
                $this->gen_share_efiling_number('', '', '');
            }
        } else {
            $gen_efiling_num = $p_efiling_num + 1;
            $efiling_num_info = array(
                'efiling_no' => $gen_efiling_num,
                'updated_by' => getSessionData('login')['id'],
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by_ip' => getClientIP()
            );
            $builder2 = $this->db->table('efil.m_tbl_efiling_no')
                ->WHERE('efiling_no', $p_efiling_num)
                ->WHERE('entry_for_type', $efiling_for_type_id)
                ->WHERE('ref_m_establishment_id', $entry_for_id)
                ->SET($efiling_num_info)
                ->UPDATE();
            if ($this->db->affectedRows() > 0) {
                $data['efiling_num'] = $gen_efiling_num;
                $data['efiling_year'] = $year;
                return $data;
            } else {
                $this->gen_share_efiling_number('', '', '');
            }
        }
    }

    function add_efiled_extra_party($data) {
        $builder = $this->db->table('tbl_efiling_civil_extra_party')->insertBatch($data);
        if ($this->db->insertID()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function add_act_data($data) {
        $builder = $this->db->table('tbl_extraact')->insertBatch($data);
        if ($this->db->insertID()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function add_court_data($data) {
        $builder = $this->db->table('tbl_extraact')->insertBatch($data);
        if ($this->db->insertID()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function add_efiled_sub_court($data) {
        $builder = $this->db->table('etrial_lower_court')->INSERT($data);
        if ($this->db->insertID()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function add_efiled_mvc($data) {
        $builder = $this->db->table('tbl_efiling_mvc')->insertBatch($data);
        if ($this->db->insertID()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_data($table, $filter_array, $select='*', $ordering='1') {
        $builder = $this->db->table($table)
            ->SELECT($select)
            ->WHERE($filter_array)
            ->orderBy($ordering);
        $query = $builder->GET();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            //echo $this->db->last_query();
            return FALSE;
        }
    }

    function get_efiled_by_user($user_id) {
        $builder = $this->db->table('users');
        $builder->SELECT('id,ref_m_usertype_id,first_name, last_name, moblie_number, emailid');
        $builder->WHERE('id', $user_id);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    public function is_tbl_case_parties($registration_id, $data) {
        if (!empty($registration_id) && !empty($data)  && $data['m_a_type']=='M' && ($data['p_r_type']=='P' || $data['p_r_type']=='R')) {
            $builder = $this->db->table('efil.tbl_case_parties');
            $builder->SELECT('*');
            $builder->WHERE('registration_id', $registration_id);
            $builder->WHERE('m_a_type', $data['m_a_type']);
            $builder->WHERE('p_r_type', $data['p_r_type']);
            $builder->WHERE('is_deleted', false);
            $query = $builder->get();
            if ($query->getNumRows() >= 1) {
                return $query->getResult();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    public function get_efiling_declaration_question(){
        $builder = $this->db->table('efil.m_checklist_new');
        $builder->SELECT('*');
        $builder->WHERE('question_no', '8888');
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getRowArray();
            return $result;
        } else {
            return false;
        }
    }


    public function check_efiling_declaration_answer($registration_id){
        $builder = $this->db->table('efil.tbl_check_list_transaction');
        $builder->SELECT('*');
        $builder->WHERE('question_no', '8888');
        $builder->WHERE('registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getRowArray();
            return $result;
        } else {
            return false;
        }
    }

    public function saved_efiling_declaration_question_answer($registration_id, $question_id, $question_no, $consent){
        if(!empty($question_id) && !empty($question_no) && !empty($consent)){
            $data_update =  array(
                'registration_id' => $registration_id,
                'question_no' => $question_no,
                'answer' => $consent,
                'created_by' => getSessionData('login')['userid'],
                'ref_m_check_list_new_id' =>  $question_id
            );

            $builder = $this->db->table('efil.tbl_check_list_transaction');
            $builder->SELECT('*');
            $builder->WHERE('question_no', $question_no);
            $builder->WHERE('registration_id', $registration_id);
            $query = $builder->get();
            if ($query->getNumRows() > 0) {
                $result = $query->getRowArray();
                return $this->db->table('efil.tbl_check_list_transaction')
                        ->WHERE('id', $result['id'])
                        ->SET($data_update)
                        ->UPDATE();
            } else {
               return $this->db->table('efil.tbl_check_list_transaction')->INSERT($data_update);
            }
        }else{
            return false;
        }
    }

}