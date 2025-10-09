<?php

namespace App\Models\Caveat;
use CodeIgniter\Model;

class SubordinateCourtModel extends Model {
    
	protected $session;

    function __construct() {
        parent::__construct();
        $this->session = \Config\Services::session();
    }

    function get_main_matter_details($registration_id) {
        $builder = $this->db->table('tbl_cis_masters_values');
        $builder->SELECT('registration_id,main_matter_cnr_num,main_matter_case_type_name ,main_matter_case_num,main_matter_case_year,main_matter_pet_name,main_matter_res_name,main_matter_court_name');
        $builder->WHERE('registration_id', $registration_id);
        $builder->orderBy("id", "asc");
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_efiling_civil_details($registration_id) {
        $builder = $this->db->table('tbl_efiling_nums');
        $builder->SELECT("tbl_efiling_nums.*,tbl_efiling_caveat.*,
            (CASE WHEN tbl_efiling_nums.efiling_for_type_id =" . E_FILING_FOR_ESTABLISHMENT . " THEN 
                (SELECT concat(estname,', ',dist_name,', ',state)  
                FROM m_tbl_establishments est
                    LEFT JOIN m_tbl_state st on est.state_code = st.state_id
                    LEFT JOIN m_tbl_districts dist on est.ref_m_tbl_districts_id = dist.id
                    WHERE est.id = tbl_efiling_nums.efiling_for_id ) 
            ELSE (SELECT concat(hc_name,' High Court')
            FROM m_tbl_high_courts hc
            WHERE hc.id = tbl_efiling_nums.efiling_for_id) END ) 
            as efiling_for_name");
        $builder->JOIN('tbl_efiling_caveat', 'tbl_efiling_nums.registration_id = tbl_efiling_caveat.ref_m_efiling_nums_registration_id');
        $builder->WHERE('tbl_efiling_nums.registration_id', $registration_id);
        $builder->WHERE('tbl_efiling_nums.is_active', TRUE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function update_breadcrumbs($registration_id, $breadcrumb_step) {
        $session = \Config\Services::session();
        $old_breadcrumbs = getSessionData('efiling_details')['breadcrumb_status'] . ',' . $breadcrumb_step;
        $old_breadcrumbs_array = explode(',', $old_breadcrumbs);
        $new_breadcrumbs_array = array_unique($old_breadcrumbs_array);
        sort($new_breadcrumbs_array);
        $new_breadcrumbs = implode(',', $new_breadcrumbs_array);
        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->WHERE('registration_id', $registration_id);
        $builder->UPDATE(array('breadcrumb_status' => $new_breadcrumbs));
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

    function add_subordinate_court_info($registration_id, $data, $cis_masters_values) {
        $builder = $this->db->table('etrial_lower_court');
        $builder->INSERT($data);
        if ($this->db->insertID()) {
            $status = $this->update_breadcrumbs($registration_id, CAVEAT_BREAD_SUBORDINATE_COURT);
        }
        if (empty($status)) {
            return FALSE;
        } else {
            return $this->db->insertID();
        }
    }

    function update_subordinate_court_info($id, $registration_id, $data, $cis_masters_values) {
        $this->db->transStart();
        $builder = $this->db->table('etrial_lower_court');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('id', $id);
        $builder->UPDATE($data);
        if ($this->db->affectedRows() > 0) {
            $status = $this->update_breadcrumbs($registration_id, CAVEAT_BREAD_SUBORDINATE_COURT);
            if ($status) {
                if (!empty($cis_masters_values)) {
                    $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                    if ($success) {
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

    function add_subordinate($registration_id, $data, $cis_masters_values) {
        $this->db->transStart();
        $builder = $this->db->table('tbl_efiling_caveat');
        $builder->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $builder->UPDATE($data);
        if ($this->db->affectedRows() > 0) {
            $status = $this->update_breadcrumbs($registration_id, CAVEAT_BREAD_SUBORDINATE_COURT);
            if ($status) {
                if (!empty($cis_masters_values)) {
                    $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                    if ($success) {
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

    function update_cis_master_values($registration_id, $data) {
        $builder = $this->db->table('tbl_cis_masters_values');
        $builder->WHERE('registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $builder1 = $this->db->table('tbl_cis_masters_values');
            $builder1->WHERE('registration_id', $registration_id);
            $result = $builder1->UPDATE($data);
            if ($this->db->affectedRows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $cis_masters_values = array(
                'registration_id' => $registration_id,
                'pet_org_name' => $data['pet_org_name'],
                'filcase_type_name' => $data['filcase_type_name'],
                'pet_caste_name' => $data['pet_caste_name'],
                'pet_state_name' => $data['pet_state_name'],
                'pet_distt_name' => $data['pet_distt_name'],
                'pet_taluka_name' => $data['pet_taluka_name'],
                'pet_town_name' => $data['pet_town_name'],
                'pet_ward_name' => $data['pet_ward_name'],
                'pet_village_name' => $data['pet_village_name'],
                'pet_ps_name' => $data['pet_ps_name'],
                'police_st_state_name' => $data['police_state_name'],
                'police_st_dist_name' => $data['police_distt_name'],
                'police_station_name' => $data['police_station_name'],
                'fir_type_name' => $data['fir_type_name'],
                'investigation_agency' => $data['investigation_agency'],
                'trials_name' => $data['trials_name'],
                'ia_case_type_name' => $data['ia_case_type_name'],
                'ia_act_name' => $data['ia_act_name'],
                'ia_classification_name' => $data['ia_classification_name'],
                'ia_prayer_name' => $data['ia_prayer_name'],
                'ia_purpose_name' => $data['ia_purpose_name'],
                'bench_name' => $data['bench_name'],
                'high_court_case_type' => $data['high_court_case_type'],
                'pet_relation' => $data['pet_relation']
            );
            $builder2 = $this->db->table('tbl_cis_masters_values');
            $result = $builder2->INSERT($cis_masters_values);
            if ($this->db->insertID()) {
                return true;
            } else {
                return false;
            }
        }
    }

    function get_subordinate_court_details($registration_id) {
        $builder = $this->db->table('etrial_lower_court elc');
        $builder->SELECT('elc.*,cmv.app_court_state_name,cmv.app_court_distt_name,cmv.app_court_sub_court_name, cmv.trial_court_state_name,cmv.trial_court_distt_name,cmv.trial_court_sub_court_name,cmv.trial_court_case_type');
        $builder->JOIN('tbl_cis_masters_values cmv', 'cmv.registration_id = elc.registration_id');
        $builder->WHERE('cmv.registration_id', $registration_id);
        $builder->WHERE('is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function get_subordinate_court_without_cis_master($registration_id) {
        $output = false;
        if(isset($registration_id) && !empty($registration_id)) {
            $builder = $this->db->table('etrial_lower_court elc');
            $builder->distinct('st.state_name');
            $builder->SELECT('elc.*,st.state_name,mtdce.estab_name,mtdce.district_name,hcb.name bench_name,hcb.cmis_state_id,hcb.ref_agency_code_id,thcb.name high_court_name, ras.agency_state,concat(rac.short_agency_name, rac.agency_name) agency_name ,lhc.type_sname case_name,fir.state_name fir_state_name, fir.district_name fir_district_name, fir.police_station_name fir_police_station_name,fir.complete_fir_no fir_complete_fir_no');
            $builder->JOIN('efil.m_tbl_district_courts_establishments st', 'elc.lower_state_id = st.state_code','left');
            $builder->JOIN('efil.m_tbl_district_courts_establishments mtdce', 'elc.lower_court_code = mtdce.estab_code','left');
            $builder->JOIN('efil.m_tbl_high_courts_bench hcb', 'elc.bench_code = hcb.est_code','left');
            $builder->JOIN('efil.m_tbl_high_courts_bench thcb','elc.court_id = thcb.hc_id AND thcb.est_code is NULL','left');
            $builder->JOIN('icmis.ref_agency_state ras','elc.lower_state_id = ras.cmis_state_id ','left');
            $builder->JOIN('icmis.ref_agency_code rac','elc.lower_dist_code = rac.id ','left');
            $builder->JOIN('icmis.lc_hc_casetype lhc','elc.case_type = lhc.lccasecode ','left');
            $builder->JOIN('efil.tbl_fir_details fir','elc.id = fir.ref_tbl_lower_court_details_id ','left');
            $builder->WHERE('elc.registration_id', $registration_id);
            $builder->WHERE('elc.is_deleted', FALSE);
            $query = $builder->get();
            $output = $query->getResultArray();
        }
        return $output ;
    }

    function get_district($state_code,$district_code=null) {
        $builder = $this->db->table('efil.m_tbl_district_courts_establishments mtdce');
        $builder->distinct('mtdce.district_name');
        $builder->select('mtdce.district_name,mtdce.district_code');
        if (!empty($state_code) && $state_code !=null) {
            $builder->where('mtdce.state_code', $state_code);
        }
        if (!empty($district_code) && $district_code !=null) {
            $builder->where('mtdce.district_code', $district_code);
        }
        $builder->orderBy('mtdce.district_name', 'ASC');
        $query = $builder->get();
        $output = $query->getResultArray();
        return $output;
    }

    function subordinate_court_details_for_dc($registration_id) {
        $builder = $this->db->table('tbl_efiling_caveat');
        $builder->SELECT('lower_court_state, lower_court_district, lower_court_code, lower_cino, lower_court, filing_case, lower_judge_name, lower_court_dec_dt , lcc_applied_date , lcc_received_date,  ');
        $builder->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }
    
    function already_added_sub_court_info($registration_id, $appellate_trial) {
        $builder = $this->db->table('etrial_lower_court');
        $builder->SELECT('appellate_trial,sub_qj_high');
        $builder->WHERE('etrial_lower_court.registration_id', $registration_id);
        $builder->WHERE('etrial_lower_court.appellate_trial', $appellate_trial);
        $builder->WHERE('is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function check_subordinate_entry($registration_id, $ids) {
        $builder = $this->db->table('etrial_lower_court');
        $builder->SELECT('appellate_trial,sub_qj_high');
        $builder->WHERE('etrial_lower_court.registration_id', $registration_id);
        $builder->whereIn('etrial_lower_court.sub_qj_high', $ids);
        $builder->WHERE('is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function reset_subordinate_court_data($registration_id) {
        $data = array('is_deleted' => TRUE);
        $builder = $this->db->table('etrial_lower_court');
        $builder->WHERE('id', $registration_id);
        $builder->WHERE('is_deleted', FALSE);
        $builder->UPDATE($data);
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
            $success = true;
            if ($success) {
                $status = $this->remove_breadcrumb($registration_id, CAVEAT_BREAD_SUBORDINATE_COURT);
                if ($status) {
                    return TRUE;
                } else {
                    return TRUE;
                }
            }
        }
    }

    function remove_breadcrumb($registration_id, $breadcrumb_to_remove) {
        $breadcrumbs_array = explode(',', getSessionData('efiling_details')['breadcrumb_status']);
        if (in_array($breadcrumb_to_remove, $breadcrumbs_array)) {
            $index = array_search($breadcrumb_to_remove, $breadcrumbs_array);
            if ($index !== false) {
                unset($breadcrumbs_array[$index]);
            }           
            $new_breadcrumbs = implode(',', $breadcrumbs_array);
            $mergeData=array_merge(getSessionData('efiling_details'),array('breadcrumb_status' =>$new_breadcrumbs));
            setSessionData('efiling_details', $mergeData);
            $builder = $this->db->table('efil.tbl_efiling_nums');
            $builder->WHERE('registration_id', $registration_id);
            $builder->UPDATE(array('breadcrumb_status' => $new_breadcrumbs));
            if ($this->db->affectedRows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

}