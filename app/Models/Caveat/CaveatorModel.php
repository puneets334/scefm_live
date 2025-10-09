<?php

namespace App\Models\Caveat;
use CodeIgniter\Model;

class CaveatorModel extends Model {

	protected $session;
	
    function __construct() {
        parent::__construct();
		$this->session = \Config\Services::session();
    }

    public function setSession($session) {
        $this->session = $session;
    }

    public function add_caveator($data, $petmobile, $pet_email, $cis_masters_values) { 
        $estabDetails= getSessionData('estab_details');  
        $est_code='';
        if (!empty($estabDetails)) {
            if (!empty($estabDetails) && (!empty($estabDetails['hc_code']))) {
                $est_code = $estabDetails['hc_code'];
            } else {
                $est_code = $estabDetails['estab_code'];
            }
        }   
        $curr_dt_time = date('Y-m-d H:i:s');
        if (empty($est_code)) {
            return FALSE;
        }
        $result = $this->gen_efiling_number();
        $num_pre_fix = "EK";
        $filing_type = E_FILING_TYPE_CAVEAT;
        $stage_id = Draft_Stage;    
        if ($result) {
            if (getSessionData('login')['ref_m_usertype_id'] == USER_CLERK) {
                $aorData = getAordetailsByAORCODE($_SESSION['login']['aor_code']);
                $created_by = !empty($aorData) ? $aorData[0]->id : 0;
                $sub_created_by = getSessionData('login')['id'];
            } else {
                $created_by = getSessionData('login')['id'];
                $sub_created_by = 0;
            }
            $efiling = sprintf("%'.05d\n", $result['efiling_num']);
            $string = $num_pre_fix . $est_code . $efiling . $result['efiling_year'];
            $efiling_num = preg_replace('/\s+/', '', $string);
            $efiling_num_data = [
                'efiling_no' => $efiling_num,
                'efiling_year' => $result['efiling_year'],
                'efiling_for_type_id' => isset(getSessionData('efiling_for_details')['efiling_for_type_id'])?getSessionData('efiling_for_details')['efiling_for_type_id']:NULL,
                'efiling_for_id' =>  isset(getSessionData('efiling_for_details')['efiling_for_id'])?getSessionData('efiling_for_details')['efiling_for_id']:NULL,
                'ref_m_efiled_type_id' => $filing_type,
                'efiling_for_type_id' => E_FILING_TYPE_CAVEAT,
                'efiling_for_id' => 1,
                'created_by' => $created_by,
                'create_on' => $curr_dt_time,
                'create_by_ip' => $_SERVER['REMOTE_ADDR'],
                'sub_created_by' => $sub_created_by
            ];
            $result['registration_id'] = $this->add_efiling_nums($efiling_num_data);
            if (!empty($result['registration_id'])) {
                $data['ref_m_efiling_nums_registration_id'] = $result['registration_id'];
                $result = [
                    'registration_id' => $result['registration_id'],
                    'efiling_no' => $efiling_num,
                    'stage_id' => Draft_Stage,
                    'ref_m_efiled_type_id' => E_FILING_TYPE_CAVEAT
                ];
                $session = session();
                $session ->set('efiling_details', $result);
                if(!empty(getSessionData('efiling_for_details')['adv_code']) && !empty(getSessionData('efiling_for_details')['adv_name'])) {
                    $data['pet_adv_cd'] = getSessionData('efiling_for_details')['adv_code'];
                    $data['pet_adv'] = getSessionData('efiling_for_details')['adv_name'];
                    $data['pet_inperson'] = NULL;
                } else {
                    $data['pet_inperson'] = 'Y';
                }
            }
            $builder = $this->db->table('tbl_efiling_caveat');
            $builder->insert($data);
            if ($this->db->insertID()) {
                $data3 = [
                    'registration_id' => isset($result['registration_id'])?$result['registration_id']:'',
                    'stage_id' => $stage_id,
                    'activated_on' => date('Y-m-d',strtotime($curr_dt_time)),
                    'activated_by' => getSessionData('login')['id'],
                    'activated_by_ip' => $_SERVER['REMOTE_ADDR']
                ];        
                $builder = $this->db->table('tbl_efiling_case_status');
                $builder->insert($data3);                
                if ($this->db->insertID()) {                
                    $status = $this->update_breadcrumbs(isset($result['registration_id'])?$result['registration_id']:'', CAVEAT_BREAD_CAVEATOR);                
                    if ($status) {
                        $this->update_efiling_num_status($result['registration_id'], $curr_dt_time, Draft_Stage);
                        $user_name = getSessionData('login')['first_name'] . ' ' . getSessionData('login')['last_name'];
                        $sentSMS = "eFiling No. " . efile_preview($efiling_num) . " generated from your efiling account & still pending for final submit. - Supreme Court of India";
                        $subject = "eFiling No. " . efile_preview($efiling_num) . " generated from your efiling account";
                        $Petsubject = "eFiling No. " . efile_preview($efiling_num) . " generated for your petition";
                        $sentPetSMS = "eFiling No. " . efile_preview($efiling_num) . " is generated for your petition filed by your Advocate " . $user_name . " and still pending for final submission.";
                        if (getSessionData('login')['ref_m_usertype_id'] == USER_ADVOCATE) {
                            $pet_user_name = $data['pet_name'];
                            if (!empty($data['pet_mobile'])) {
                                // send_petitioner_mobile_sms($data['pet_mobile'], $sentPetSMS);
                            }
                            if (!empty($data['pet_email'])) {
                                // send_petitioner_mail_msg($data['pet_email'], $Petsubject, $sentPetSMS, $pet_user_name);
                            }
                        }
                        send_mobile_sms(getSessionData('login')['mobile_number'], $sentSMS, SCISMS_Efiling_No_Generated);
                        send_mail_msg(getSessionData('login')['emailid'], $subject, $sentSMS, $user_name);
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

    function update_efiling_num_status($registration_id, $curr_dt_time, $next_stage, $curr_stage = NULL) {
        $deactivate_curr_stage = [
            'stage_id' => $curr_stage,
            'deactivated_on' => $curr_dt_time,
            'is_active' => FALSE,
            'updated_by' => getSessionData('login')['id'],
            'updated_by_ip' => $_SERVER['REMOTE_ADDR'],
        ];
        $activate_next_stage = [
            'registration_id' => $registration_id,
            'stage_id' => $next_stage,
            'is_active' => TRUE,
            'activated_on' => $curr_dt_time,
            'activated_by' => getSessionData('login')['id'],
            'activated_by_ip' => $_SERVER['REMOTE_ADDR'],
        ];
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        if ($curr_stage !== NULL) {
            $builder->where('registration_id', $registration_id);
            $builder->where('is_active', TRUE);
            $builder->update($deactivate_curr_stage);
            if ($this->db->affectedRows() > 0) {
                $builder = $this->db->table('efil.tbl_efiling_num_status');
                $builder->insert($activate_next_stage);
                if ($builder->get()->resultID) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            $builder = $this->db->table('efil.tbl_efiling_num_status');
            $builder->insert($activate_next_stage);
            if ($builder->get()->resultID) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
    
    function gen_efiling_number() {
        $builder = $this->db->table('efil.m_tbl_efiling_no')
            ->select('caveat_efiling_no, caveat_efiling_year')
            ->where('entry_for_type', getSessionData('estab_details')['efiling_for_type_id'])
            ->where('ref_m_establishment_id',getSessionData('estab_details')['efiling_for_id']);    
        $row = $builder->get()->getRowArray();
        $p_efiling_num = $row['caveat_efiling_no'];
        $year = $row['caveat_efiling_year'];        
        if ($year < date('Y')) {
            $newYear = date('Y');
            $update_data = [
                'caveat_efiling_no' => 1,
                'caveat_efiling_year' => $newYear,
                'caveat_updated_by' => getSessionData('login')['id'],
                'caveat_updated_on' => date('Y-m-d H:i:s'),
                'caveat_updated_by_ip' => $_SERVER['REMOTE_ADDR']
            ];            
            $builder->where('caveat_efiling_no', $p_efiling_num)
                ->where('caveat_efiling_year', $year)
                ->update($update_data);            
            if ($this->db->affectedRows() > 0) {
                return [
                    'efiling_num' => 1,
                    'efiling_year' => $newYear
                ];
            } else {
                return $this->gen_efiling_number();
            }
        } else {
            $gen_efiling_num = $p_efiling_num + 1;
            $efiling_num_info = [
                'caveat_efiling_no' => $gen_efiling_num,
                'caveat_updated_by' => getSessionData('login')['id'],
                'caveat_updated_on' => date('Y-m-d H:i:s'),
                'caveat_updated_by_ip' => $_SERVER['REMOTE_ADDR']
            ];            
            $builder->where('caveat_efiling_no', $p_efiling_num)
                ->update($efiling_num_info);            
            if ($this->db->affectedRows() > 0) {
                return [
                    'efiling_num' => $gen_efiling_num,
                    'efiling_year' => $year
                ];
            } else {
                return $this->gen_efiling_number();
            }
        }
    }

    function add_efiling_nums($data) {
        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->INSERT($data);
        if ($this->db->insertID()) {
            return $this->db->insertID();
        } else {
            return false;
        }
    }

    function update_caveators($registration_id, $data, $cis_masters_values) {
        $this->db->transStart();
        try {
            $builder = $this->db->table('tbl_efiling_caveat');
            $builder->where('ref_m_efiling_nums_registration_id', $registration_id);
            $builder->update($data);    
            $this->db->transCommit();
            return true;
        } catch (\Exception $e) {
            $this->db->transRollback();
            return false;
        }
    }
    
    function update_cis_master_values($registration_id, $data) {
        $builder = $this->db->table('tbl_cis_masters_values');
        $builder->WHERE('registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $builder = $this->db->table('tbl_cis_masters_values');
            $builder->WHERE('registration_id', $registration_id);
            $result = $builder->UPDATE($data);
            if ($this->db->affectedRows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $cis_masters_values = array(
                'registration_id' => $registration_id,
                'pet_org_name' => $data['pet_org_name'],
                'pet_relation' => $data['pet_relation'],
                'pet_state_name' => $data['pet_state_name'],
                'pet_distt_name' => $data['pet_distt_name'],
                'pet_taluka_name' => $data['pet_taluka_name'],
                'pet_town_name' => $data['pet_town_name'],
                'pet_ward_name' => $data['pet_ward_name'],
                'pet_village_name' => $data['pet_village_name'],
            );
            $builder = $this->db->table('tbl_cis_masters_values');
            $result = $builder->INSERT($cis_masters_values);
            if ($this->db->insertID()) {
                return true;
            } else {
                return false;
            }
        }
    }  

    function update_breadcrumbs($registration_id, $breadcrumb_step) {
        $efling_details_status = isset(getSessionData('efiling_details')['breadcrumb_status'])?getSessionData('efiling_details')['breadcrumb_status']:'';
        $old_breadcrumbs = $efling_details_status . ',' . $breadcrumb_step;
        $old_breadcrumbs_array = explode(',', $old_breadcrumbs);
        $new_breadcrumbs_array = array_unique($old_breadcrumbs_array);
        $session = session();
        sort($new_breadcrumbs_array);
        $new_breadcrumbs = implode(',', $new_breadcrumbs_array);    
        $builder = $this->db->table('efil.tbl_efiling_nums');
        $result = $builder->where('registration_id', $registration_id)->update(['breadcrumb_status' => $new_breadcrumbs]);      
        if ($this->db->affectedRows() > 0) {            
            $session->set([
                'efiling_details' => [
                    'breadcrumb_status' => $new_breadcrumbs
                ]
            ]);
            return TRUE;
        } else {
            return FALSE;
        }         
    }

}