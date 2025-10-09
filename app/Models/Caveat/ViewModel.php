<?php

namespace App\Models\Caveat;
use CodeIgniter\Model;

class ViewModel extends Model {

    function __construct() {
        parent::__construct();
    }

    function get_efiling_civil_details($registration_id) {
        $builder = $this->db->table('efil.tbl_efiling_nums en');
        $builder->SELECT("en.*,ec.*,
        idep.deptname as org_dept_name,piaut.authdesc as org_post_name,pivs.deptname as org_state_name,
        ridep.deptname as res_org_dept_name,riaut.authdesc as res_org_post_name,rivs.deptname as res_org_state_name,
        (case when ec.orgid = 'I' then 'Individual'   
        else case when ec.orgid = 'D1' then 'State Department'
        else case when ec.orgid = 'D2' then 'Central Department'
        else case when ec.orgid = 'D3' then 'Other Organisation'
        end end end end )as pet_party_is,
        (case when ec.resorgid = 'I' then 'Individual'   
        else case when ec.resorgid = 'D1' then 'State Department'
        else case when ec.resorgid = 'D2' then 'Central Department'
        else case when ec.resorgid = 'D3' then 'Other Organisation'
        end end end end )as res_party_is
        ");
        $builder->JOIN('public.tbl_efiling_caveat ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id');
        $builder->JOIN('icmis.deptt idep', "ec.org_dept = idep.deptcode and idep.display='Y'", "left");
        $builder->JOIN('icmis.deptt ridep', "ec.res_org_dept = ridep.deptcode and ridep.display='Y'", "left");
        $builder->JOIN('icmis.authority piaut', "ec.org_post = piaut.authcode and piaut.display='Y'", "left");
        $builder->JOIN('icmis.authority riaut', "ec.res_org_post = riaut.authcode and riaut.display='Y'", "left");
        $builder->JOIN('icmis.view_state_in_name pivs', "ec.org_state = pivs.deptcode", "left");
        $builder->JOIN('icmis.view_state_in_name rivs', "ec.res_org_state = rivs.deptcode", "left");
        $builder->WHERE('en.registration_id', $registration_id);
        $builder->WHERE('en.is_active', TRUE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function get_efiling_civil_master_value($registration_id) {
        $builder = $this->db->table('tbl_cis_masters_values');
        $builder->SELECT('*');
        $builder->WHERE('registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_uploaded_documents($registration_id) {
        $builder = $this->db->table('tbl_efiled_docs docs');
        $builder->SELECT('*');
        $builder->WHERE('docs.registration_id', $registration_id);
        $builder->orderBy('docs.doc_id');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function get_extra_party_preview_details($regid) {
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
        $builder->JOIN('icmis.deptt idep', "ec.extra_party_org_dept = idep.deptcode and idep.display='Y'", "left");
        $builder->JOIN('icmis.authority piaut', "ec.extra_party_org_post = piaut.authcode and piaut.display='Y'", "left");
        $builder->JOIN('icmis.view_state_in_name pivs', "ec.extra_party_org_state = pivs.deptcode", "left");
        $builder->WHERE('en.registration_id', $regid);
        $builder->WHERE('en.is_active', TRUE);
        $builder->WHERE('ec.display', 'Y');
        $builder->WHERE('ec.parentid', NULL);
        $builder->orderBy("ec.type", "asc");
        $builder->orderBy("ec.party_id", "asc");
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    function get_sub_qj_hc_court_details($registration_id, $id = null) {
        $builder = $this->db->table('public.etrial_lower_court elc');
        $builder->distinct('mdce.state_name');
        $builder->SELECT("case when elc.sub_qj_high=3 then '1'  when elc.sub_qj_high=1 then '3' when elc.sub_qj_high=5 then '5' else '1'  end as court_type, 
         elc.*,mdce.state_name,mdce.district_name,mdce.estab_name,hcb.cmis_state_id,hcb.ref_agency_code_id , 
         ras.agency_state,concat(rac.short_agency_name,rac.agency_name) agency_name ,lhc.type_sname case_name ");
        $builder->join('efil.m_tbl_district_courts_establishments mce', 'elc.lower_state_id = mce.state_code AND elc.lower_dist_code = mce.district_code', 'left');
        $builder->join('efil.m_tbl_district_courts_establishments mdce', 'elc.lower_court_code = mdce.estab_code', 'left');
        $builder->join('efil.m_tbl_high_courts_bench hcb', 'elc.bench_code = hcb.est_code', 'left');
        $builder->JOIN('icmis.ref_agency_state ras', 'elc.lower_state_id = ras.cmis_state_id ', 'left');
        $builder->JOIN('icmis.ref_agency_code rac', 'elc.lower_dist_code = rac.id ', 'left');
        $builder->JOIN('icmis.lc_hc_casetype lhc', 'elc.case_type = lhc.lccasecode ', 'left');
        $builder->WHERE('elc.registration_id', $registration_id);
        if (!empty($id) && $id != null) {
            $builder->WHERE('elc.id', $id);
        }
        $builder->WHERE('elc.is_deleted', FALSE);
        $builder->orderBy("elc.id", "asc");
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function getSubCourtDetails($registration_id) {
        $builder = $this->db->table('public.etrial_lower_court elc');
        $builder->SELECT("case when elc.sub_qj_high=3 then '1'  when elc.sub_qj_high=1 then '3' when elc.sub_qj_high=5 then '5' else '1'  end as court_type,
            elc.*,mdce.state_name,mdce.district_name,mdce.estab_name,
            (case when elc.sub_qj_high = '3' then hcb.cmis_state_id   
            else case when elc.sub_qj_high = '1' then mdce.cmis_id_no
                else case when elc.sub_qj_high = '5' then rac.cmis_state_id
            end end end)as cmis_state_id,
            (case when elc.sub_qj_high = '3' then hcb.ref_agency_code_id   
            else case when elc.sub_qj_high = '1' then mdce.id
            else case when elc.sub_qj_high = '5' then rac.id
            end end end)as ref_agency_code_id             
        ");
        $builder->join('efil.m_tbl_district_courts_establishments mce', 'elc.lower_state_id = mce.state_code AND elc.lower_dist_code = mce.district_code', 'left');
        $builder->join('efil.m_tbl_district_courts_establishments mdce', 'elc.lower_court_code = mdce.estab_code', 'left');
        $builder->join('efil.m_tbl_high_courts_bench hcb', 'elc.bench_code = hcb.est_code', 'left');
        $builder->join('icmis.ref_agency_code rac', 'elc.lower_state_id = rac.cmis_state_id and elc.lower_dist_code = rac.id', 'left');
        $builder->WHERE('elc.registration_id', $registration_id);
        $builder->WHERE('elc.is_deleted', FALSE);
        $builder->orderBy("elc.id", "asc");
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function get_index_items_list($registration_id) {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs ed');
        $builder->SELECT('ed.*');
        $builder->WHERE('ed.registration_id', $registration_id);
        $builder->WHERE('ed.is_active', TRUE);
        $builder->WHERE('ed.is_deleted', FALSE);
        $builder->orderBy('ed.doc_id');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function get_index_item_file($doc_id) {
        $builder = $this->db->table('tbl_efiled_docs ed');
        $builder->SELECT('ed.file_path, ed.file_name, ed.doc_title');
        $builder->WHERE('ed.doc_id', $doc_id);
        $builder->WHERE('ed.is_active', TRUE);
        $builder->WHERE('ed.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_caveat_index_items_list($registration_id, $re_filed = 0) {
        $builder = $this->db->table('efil.tbl_efiled_docs ed');
        $builder->SELECT('DISTINCT ed.*,dm.docdesc', FALSE);
        $builder->JOIN('icmis.docmaster dm', 'ed.doc_type_id = dm.doccode AND ed.sub_doc_type_id = dm.doccode1 and dm.display!=\'N\'');
        $builder->WHERE('ed.registration_id', $registration_id);
        $builder->WHERE('ed.is_active', TRUE);
        $builder->WHERE('ed.is_deleted', FALSE);
        if ($re_filed != 0) {
            $builder->where('ed.icmis_docnum IS NULL', null, false);
            $builder->where('ed.icmis_docyear IS NULL', null, false);
            $builder->where('ed.doc_type_id', 8);
        }
        $builder->orderBy('ed.index_no');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_payment_details($registration_id) {
        $builder = $this->db->table('efil.tbl_court_fee_payment cfp');
        $builder->SELECT('cfp.*,ed.caveat_year,ed.caveat_num,ed.caveat_num_date');
        $builder->JOIN('public.tbl_efiling_caveat ed', 'cfp.registration_id=ed.ref_m_efiling_nums_registration_id', 'LEFT');
        $builder->WHERE('cfp.registration_id', $registration_id);
        $builder->WHERE('cfp.is_deleted', FALSE);
        $builder->orderBy('cfp.id', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_efiled_efiling_details($registration_id) {
        $array = array(Draft_Stage);
        $builder = $this->db->table('tbl_efiling_nums en');
        $builder->SELECT("en.*,cs.*,efiled_by.id,efiled_by.ref_m_usertype_id,efiled_by.first_name, efiled_by.last_name, efiled_by.moblie_number, efiled_by.emailid,
            concat(users.first_name,' ',users.last_name) as admin_name,
            (CASE WHEN en.efiling_for_type_id =" . E_FILING_FOR_ESTABLISHMENT . " THEN (SELECT concat(estname,', ',dist_name,', ',state)  
                FROM m_tbl_establishments est
                    LEFT JOIN m_tbl_state st on est.state_code = st.state_id
                    LEFT JOIN m_tbl_districts dist on est.ref_m_tbl_districts_id = dist.id
            WHERE est.id = en.efiling_for_id ) 
            ELSE (SELECT concat(hc_name,' High Court') FROM m_tbl_high_courts hc
            WHERE hc.id = en.efiling_for_id) END ) 
            as efiling_for_name");
        $builder->JOIN('users', 'users.id = en.allocated_to', 'LEFT');
        $builder->JOIN('users efiled_by', 'efiled_by.id = en.created_by', 'LEFT');
        $builder->JOIN('tbl_efiling_case_status cs', 'en.registration_id = cs.registration_id', 'LEFT');
        $builder->whereNotIn('cs.stage_id', $array);
        $builder->WHERE('en.registration_id', $registration_id);
        $builder->WHERE('en.is_active', TRUE);
        $builder->orderBy('cs.status_id', 'DESC');
        $builder->LIMIT(1, 0);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    public function getCourtDetails($params = array()) {
        $output = false;
        if (isset($params['type']) && !empty($params['type'])) {
            $type = (int)$params['type'];
            switch ($type) {
                case 1:
                    $builder = $this->db->table('efil.m_tbl_high_courts_bench mhcb');
                    $builder->select('mhcb.hc_id,mhcb.name');
                    if (isset($params['hc_id']) && !empty($params['hc_id'])) {
                        $builder->where('mhcb.hc_id', $params['hc_id']);
                        $builder->where('mhcb.bench_id IS NULL');
                        $builder->where('mhcb.est_code IS NULL');
                    }
                    $query = $builder->get();
                    $output = $query->getResultArray();
                    break;
                case 2:
                    $builder = $this->db->table('efil.m_tbl_high_courts_bench mhcb');
                    $builder->select('mhcb.hc_id,mhcb.name');
                    if (isset($params['hc_id']) && !empty($params['hc_id'])) {
                        $builder->where('mhcb.hc_id', $params['hc_id']);
                    }
                    if (isset($params['est_code']) && !empty($params['est_code'])) {
                        $builder->where('mhcb.est_code', $params['est_code']);
                    }
                    $query = $builder->get();
                    $output = $query->getResultArray();
                    break;
                case 3:
                    $builder = $this->db->table('efil.m_tbl_high_courts_case_types mthcc');
                    $builder->select('mthcc.id,mthcc.type_name');
                    if (isset($params['id']) && !empty($params['id'])) {
                        $builder->where('mthcc.id', $params['id']);
                    }
                    if (isset($params['est_code']) && !empty($params['est_code'])) {
                        $builder->where('mthcc.est_code', $params['est_code']);
                    }
                    $query = $builder->get();
                    $output = $query->getResultArray();
                    break;
                default:
                $output = false;
            }
        }
        return $output;
    }

}