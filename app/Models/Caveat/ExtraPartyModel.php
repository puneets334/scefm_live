<?php

namespace App\Models\Caveat;
use CodeIgniter\Model;

class ExtraPartyModel extends Model {

    function __construct() {
        parent::__construct();
    }

    function add_extra_party($data, $registration_id, $data_extra_pet_n_res) {
        $builder = $this->db->table('tbl_efiling_civil_extra_party');
        $this->db->transStart();
        $builder->INSERT($data); 
        if ($this->db->insertID()) {
			$builder = $this->db->table('tbl_efiling_caveat');
            $builder->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
            $builder->UPDATE($data_extra_pet_n_res);
            if ($this->db->affectedRows()) {
                $status = $this->update_breadcrumbs($registration_id, CAVEAT_BREAD_EXTRA_PARTY);
                if ($status) {
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

    function update_extra_party($id, $data, $registration_id) {
		$builder = $this->db->table('tbl_efiling_civil_extra_party');
        $builder->WHERE('id', $id);
        $builder->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $builder->UPDATE($data);
        if ($this->db->affectedRows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_cis_master_values($registration_id, $data) {
		$builder = $this->db->table('tbl_cis_masters_values');
        $builder->WHERE('registration_id', $registration_id);
        $result = $builder->UPDATE($data);
        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function update_breadcrumbs($registration_id, $breadcrumb_step) {
		$efiling_data = getSessionData('efiling_details')['breadcrumb_status'];
        $old_breadcrumbs = $efiling_data . ',' . $breadcrumb_step;
        $old_breadcrumbs_array = explode(',', $old_breadcrumbs);
        $new_breadcrumbs_array = array_unique($old_breadcrumbs_array);
        sort($new_breadcrumbs_array);
        $new_breadcrumbs = implode(',', $new_breadcrumbs_array);
		$builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->WHERE('registration_id', $registration_id);
        $builder->UPDATE(array('breadcrumb_status' => $new_breadcrumbs));
        if ($this->db->affectedRows() > 0) {
            getSessionData('efiling_details')['breadcrumb_status'] = $new_breadcrumbs;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_extra_parties_list($registration_id) {
        $output = false;
        if(isset($registration_id) && !empty($registration_id)){
    		$builder = $this->db->table('efil.tbl_efiling_nums en');
            $builder->SELECT("en.*,ec.*,
            idep.deptname as extra_party_org_dept_name,piaut.authdesc as extra_party_org_post_name,pivs.deptname as extra_party_org_state_name,
            (case when ec.orgid = 'I' then 'Individual'   
            else case when ec.orgid = 'D1' then 'State Department'
            else case when ec.orgid = 'D2' then 'Central Department'
            else case when ec.orgid = 'D3' then 'Other Organisation'
            end end end end )as extra_party_is,
            (case when ec.type = '1' then 'Caveator'   
            else case when ec.type = '2' then 'Caveatee'
            end end )as extra_party_type
            ");
            $builder->JOIN('public.tbl_efiling_civil_extra_party ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id');
            $builder->JOIN('icmis.deptt idep', "ec.extra_party_org_dept = idep.deptcode and idep.display='Y'","left");
            $builder->JOIN('icmis.authority piaut', "ec.extra_party_org_post = piaut.authcode and piaut.display='Y'","left");
            $builder->JOIN('icmis.view_state_in_name pivs', "ec.extra_party_org_state = pivs.deptcode","left");
            $builder->WHERE('en.registration_id', $registration_id);
            $builder->WHERE('en.is_active', TRUE);
            $builder->WHERE('ec.display', 'Y');
            $builder->WHERE('ec.parentid', NULL);
            $builder->orderBy("ec.type", "asc");
            $builder->orderBy("cast(party_id as float)", "asc");
            $query = $builder->get();
            if ($query->getNumRows() >= 1) {
                $output = $query->getResult();
                return $output;
            } else {
                $output= false;
            }
        }
        return $output;
    }

    function get_extra_party_details($party_id) {
		$builder = $this->db->table('efil.tbl_efiling_nums en');
        $builder->SELECT("en.*,ec.*,
        idep.deptname as extra_party_org_dept_name,piaut.authdesc as extra_party_org_post_name,pivs.deptname as extra_party_org_state_name,
        (case when ec.orgid = 'I' then 'Individual'   
        else case when ec.orgid = 'D1' then 'State Department'
        else case when ec.orgid = 'D2' then 'Central Department'
        else case when ec.orgid = 'D3' then 'Other Organisation'
        end end end end )as extra_party_is,
        (case when ec.type = '1' then 'Caveator'   
        else case when ec.type = '2' then 'Caveatee'
        end end )as extra_party_type
        ");
        $builder->JOIN('public.tbl_efiling_civil_extra_party ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id');
        $builder->JOIN('icmis.deptt idep', "ec.extra_party_org_dept = idep.deptcode and idep.display='Y'","left");
        $builder->JOIN('icmis.authority piaut', "ec.extra_party_org_post = piaut.authcode and piaut.display='Y'","left");
        $builder->JOIN('icmis.view_state_in_name pivs', "ec.extra_party_org_state = pivs.deptcode","left");
        $builder->WHERE('ec.id', $party_id);
        $builder->WHERE('en.registration_id', $_SESSION['efiling_details']['registration_id']);
        $builder->WHERE('en.is_active', TRUE);
        $builder->WHERE('ec.display', 'Y');
        $builder->WHERE('ec.parentid', NULL);
        $builder->orderBy("ec.type", "asc");
        $builder->orderBy("ec.party_id", "asc");
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function get_max_party_id($reg_id, $type, $lrs = NULL) {
		$builder = $this->db->table('tbl_efiling_civil_extra_party');
        $builder->SELECT('max(party_id) AS party_id');
        $builder->WHERE('type', $type);
        if ($lrs == 0 || $lrs) {
            $$builder->WHERE('parentid', $lrs);
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

    function get_max_party_no($reg_id,$type) {
		$builder = $this->db->table('tbl_efiling_civil_extra_party');
        $builder->SELECT('max(party_no) AS party_no');
        $builder->WHERE('type', $type);
        $builder->WHERE('ref_m_efiling_nums_registration_id', $reg_id);
        $builder->WHERE('display', 'Y');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        }
    }

    function get_extra_party_count($reg_id, $type) {
		$builder = $this->db->table('tbl_efiling_civil_extra_party');
        $builder->SELECT('count(CASE WHEN parentid = 0 THEN 1 END)-1 AS main_party_lrs,
        count(CASE WHEN parentid != 0 or parentid IS NULL THEN 1 END)
        as extry_party_lrs');
        $builder->WHERE('display', 'Y');
        $builder->WHERE('legal_heir', 'N');
        $builder->WHERE('type', $type);
        $builder->WHERE('ref_m_efiling_nums_registration_id', $reg_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function fetch_e_party_details($id) {
		$builder = $this->db->table('tbl_efiling_civil_extra_party');
        $builder->SELECT('*');
        $builder->WHERE('id', $id);
        $builder->WHERE('ref_m_efiling_nums_registration_id', $_SESSION['efiling_details']['registration_id']);
        $query = $builder->get();
        return $query->getResultArray();
    }
 
    function get_pet_n_res_count_civil($registration_id) {
		$builder = $this->db->table('tbl_efiling_caveat');
        $builder->SELECT('pet_extracount,res_extracount');
        $builder->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $count = $query->getResultArray();
            return $count;
        } else {
            return 0;
        }
    }
    
    function delete_extra_party($id, $type, $extra_party_type, $extra_party_party_no) {
		$builder = $this->db->table('tbl_efiling_civil_extra_party');
        $update_lrs_status = array('display' => 'N');
        $builder->WHERE('id', $id);
        $builder->WHERE('ref_m_efiling_nums_registration_id', $_SESSION['efiling_details']['registration_id']);
        $builder->UPDATE($update_lrs_status);
        if ($this->db->affectedRows() > 0) {
            $extra_party_count = $this->get_pet_n_res_count_civil($_SESSION['efiling_details']['registration_id']);
            if ($type == '1') {
                $data_extra_pet_n_res = array('pet_extracount' => $extra_party_count[0]['pet_extracount'] - 1);
            } elseif ($type == '2') {
                $data_extra_pet_n_res = array('res_extracount' => $extra_party_count[0]['res_extracount'] - 1);
            }
			$builder = $this->db->table('tbl_efiling_caveat');
            $builder->WHERE('ref_m_efiling_nums_registration_id', $_SESSION['efiling_details']['registration_id']);
            $builder->UPDATE($data_extra_pet_n_res);
            if ($this->db->affectedRows() > 0) {
                if ($extra_party_type == '1' && !empty($extra_party_party_no)) {
					$builder = $this->db->table('tbl_efiling_civil_extra_party');
                    $builder->WHERE('parentid', $extra_party_party_no);
                    $builder->WHERE('ref_m_efiling_nums_registration_id', $_SESSION['efiling_details']['registration_id']);
                    $builder->UPDATE($update_lrs_status);
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
		$builder = $this->db->table('tbl_efiling_civil_extra_party');
        $builder->SELECT('max(party_no) AS party_no');
        $builder->WHERE('ref_m_efiling_nums_registration_id', $reg_id);
        $builder->WHERE('display', 'Y');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        }
    }

}