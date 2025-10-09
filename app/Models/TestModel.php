<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 19/3/21
 * Time: 4:35 PM
 */

class TestModel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_subordinate_court_without_cis_master($registration_id) {
        $output = false;
        if(isset($registration_id) && !empty($registration_id)){
            $this->db->distinct('st.state_name');
            $this->db->SELECT('elc.*,st.state_name,mtdce.estab_name,mtdce.district_name,hcb.name bench_name,hcb.cmis_state_id,hcb.ref_agency_code_id,thcb.name high_court_name,
                               ras.agency_state,concat(rac.short_agency_name, rac.agency_name) agency_name ,lhc.type_sname case_name ');
            $this->db->FROM('etrial_lower_court elc');
            $this->db->JOIN('efil.m_tbl_district_courts_establishments st', 'elc.lower_state_id = st.state_code','left');
            $this->db->JOIN('efil.m_tbl_district_courts_establishments mtdce', 'elc.lower_court_code = mtdce.estab_code','left');
            $this->db->JOIN('efil.m_tbl_high_courts_bench hcb', 'elc.bench_code = hcb.est_code','left');
            $this->db->JOIN('efil.m_tbl_high_courts_bench thcb','elc.court_id = thcb.hc_id AND thcb.est_code is NULL','left');
            $this->db->JOIN('icmis.ref_agency_state ras','elc.lower_state_id = ras.cmis_state_id ','left');
            $this->db->JOIN('icmis.ref_agency_code rac','elc.lower_dist_code = rac.id ','left');
            $this->db->JOIN('icmis.lc_hc_casetype lhc','elc.case_type = lhc.lccasecode ','left');
            $this->db->WHERE('elc.registration_id', $registration_id);
            $this->db->WHERE('elc.is_deleted', FALSE);
            $query = $this->db->get();
            echo $this->db->last_query();exit;
            $output = $query->result_array();
        }
        return $output ;
    }

}