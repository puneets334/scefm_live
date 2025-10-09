<?php
namespace App\Models\NewCaseQF;

use CodeIgniter\Model;
class Get_details_model extends Model
{

    function __construct()
    {
// Call the Model constructor
        parent::__construct();
    }

    function get_case_parties_details($registration_id, $details_of_party)
    {

        if ($details_of_party['view_lr_list']) {
            $lr_params = ', lr_of.party_name lr_of_name, lr_of.relation lr_of_relation, lr_of.relative_name lr_of_relative_name';
        } else {
            $lr_params = '';
        }
        $this->db->SELECT("cp.id, cp.registration_id, cp.p_r_type, cp.m_a_type, cp.party_no,    
                        cp.party_name, cp.relation, cp.relative_name, cp.party_age, cp.gender,
                        cp.party_dob, cp.have_legal_heir, cp.parent_id, cp.party_id, cp.is_org,
                        cp.party_type, cp.org_state_id, cp.org_state_name, cp.org_state_not_in_list,
                        cp.org_dept_id, cp.org_dept_name, cp.org_dept_not_in_list,
                        cp.org_post_id, cp.org_post_name, cp.org_post_not_in_list,
                        cp.address, cp.city, cp.district_id, cp.state_id, cp.pincode,cp.country_id,
                        cp.mobile_num, cp.email_id,cp.lrs_remarks_id ,lrs.lrs_remark,dist.name addr_dist_name, a.authdesc,cp.is_dead_minor,
                        d.deptname ,st.agency_state addr_state_name,vst.deptname fetch_org_state_name" . $lr_params);

        $this->db->FROM('efil.tbl_case_parties cp');
        $this->db->JOIN('icmis.ref_agency_state st', 'cp.state_id = st.cmis_state_id', 'left');
        $this->db->JOIN('icmis.view_state_in_name vst', 'cp.org_state_id = vst.deptcode', 'left');
        $this->db->JOIN('icmis.state dist', 'cp.district_id = dist.id_no', 'left');
        $this->db->JOIN('icmis.deptt d', 'cp.org_dept_id=d.deptcode', 'left');
        $this->db->JOIN('icmis.authority a', 'cp.org_post_id=a.authcode', 'left');
        $this->db->JOIN('efil.m_tbl_lrs_remarks lrs', 'cp.lrs_remarks_id = lrs.id', 'left');


        if (isset($details_of_party['view_lr_list']) && !empty($details_of_party['view_lr_list'])) {

            $this->db->JOIN('efil.tbl_case_parties lr_of', 'cast(cp.parent_id as varchar) = lr_of.party_id and cp.p_r_type = lr_of.p_r_type');
            //$this->db->WHERE('p_r_type', $details_of_party['p_r_type']);
        }


        if (isset($details_of_party['p_r_type']) && !empty($details_of_party['p_r_type'])) {
            $this->db->WHERE('cp.p_r_type', $details_of_party['p_r_type']);
        }
        if (isset($details_of_party['m_a_type']) && !empty($details_of_party['m_a_type'])) {
            $this->db->WHERE('cp.m_a_type', $details_of_party['m_a_type']);
            $this->db->WHERE('cp.parent_id IS NULL');
        }
        if (isset($details_of_party['party_id']) && !empty($details_of_party['party_id'])) {
            $this->db->WHERE('cp.id', $details_of_party['party_id']);
        }

        $this->db->WHERE('cp.registration_id', $registration_id);
        if (isset($details_of_party['view_lr_list']) && !empty($details_of_party['view_lr_list'])) {
            $this->db->WHERE('lr_of.registration_id', $registration_id);
            $this->db->WHERE('lr_of.is_deleted', FALSE);
        }
        $this->db->WHERE('cp.is_deleted', FALSE);
//        $this->db->WHERE('st.id !=',9999);
        $this->db->ORDER_BY("cp.party_no", "asc");

        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() >= 1) {

            $party_details = $query->result_array();
            return $party_details;
        } else {
            return FALSE;
        }
    }

//    function get_case_parties_details($registration_id, $details_of_party) {
//
//        if ($details_of_party['view_lr_list']) {
//            $lr_params = ', lr_of.party_name lr_of_name, lr_of.relation lr_of_relation, lr_of.relative_name lr_of_relative_name';
//        } else {
//            $lr_params = '';
//        }
//
//        $select = "case when is_org='true' then concat(t_post_name,', ',t_dept_name,', ' ,t_org_name) else party_name end as final_party_name,*
//            from
//            (SELECT cp.id, cp.registration_id, cp.p_r_type, cp.m_a_type, cp.party_no,party_name,
//             case when (org_state_not_in_list='true')
//            then cp.org_state_name else (SELECT deptname FROM icmis.deptt WHERE deptcode=cp.org_state_id) end as t_org_name,
//            case when org_dept_not_in_list='true'
//            then cp.org_dept_name else (SELECT deptname FROM icmis.deptt WHERE deptcode=cp.org_dept_id) end as t_dept_name,
//            case when (org_post_not_in_list='true')
//            then cp.org_post_name else (SELECT post_name FROM icmis.post_t WHERE post_code=cp.org_post_id) end as t_post_name,
//            cp.relation, cp.relative_name, cp.party_age, cp.gender, 
//            cp.party_dob, cp.have_legal_heir, cp.parent_id, cp.party_id, cp.is_org, 
//            cp.party_type, cp.org_state_id, cp.org_state_name, cp.org_state_not_in_list,
//            cp.org_dept_id, cp.org_dept_name, cp.org_dept_not_in_list, cp.org_post_id,
//            cp.org_post_name, cp.org_post_not_in_list, cp.address, cp.city, cp.district_id, 
//            cp.state_id, cp.pincode, cp.mobile_num, cp.email_id, dist.name addr_dist_name, 
//            st.agency_state addr_state_name " . $lr_params;
//
//        $this->db->SELECT($select);
//        $this->db->FROM('efil.tbl_case_parties cp');
//        $this->db->JOIN('icmis.ref_agency_state st', 'cp.state_id = st.cmis_state_id');
//        $this->db->JOIN('icmis.state dist', 'cp.district_id = dist.id_no');
//
//        if (isset($details_of_party['view_lr_list']) && !empty($details_of_party['view_lr_list'])) { 
//            $this->db->JOIN('efil.tbl_case_parties lr_of', 'cast(cp.parent_id as varchar) = lr_of.party_id');
//            //$this->db->WHERE('p_r_type', $details_of_party['p_r_type']);
//        }
//
//
//        if (isset($details_of_party['p_r_type']) && !empty($details_of_party['p_r_type'])) {
//            $this->db->WHERE('cp.p_r_type', $details_of_party['p_r_type']);
//        }
//        if (isset($details_of_party['m_a_type']) && !empty($details_of_party['m_a_type'])) {
//            $this->db->WHERE('cp.m_a_type', $details_of_party['m_a_type']);
//        }
//        if (isset($details_of_party['party_id']) && !empty($details_of_party['party_id'])) {
//            $this->db->WHERE('cp.id', $details_of_party['party_id']);
//        }
//
//        $this->db->WHERE('cp.registration_id', $registration_id);
//        if (isset($details_of_party['view_lr_list']) && !empty($details_of_party['view_lr_list'])) {
//            $this->db->WHERE('lr_of.registration_id', $registration_id);
//            $this->db->WHERE('lr_of.is_deleted', FALSE);
//        }
//        $this->db->WHERE('cp.is_deleted',FALSE);
//        
//        $this->db->ORDER_BY("cp.party_no", 'asc'); 
//        
//        $query = $this->db->get(); 
//        if ($query->num_rows() >= 1) {
//
//            $party_details = $query->result_array();
//            return $party_details;
//        } else {
//            return FALSE;
//        }
//    }
    function get_no_of_extra_party($registration_id, $p_r_type)
    {

        $this->db->select('registration_id,p_r_type,is_deleted');
        $this->db->FROM('efil.tbl_case_parties');
        $this->db->WHERE('registration_id', $registration_id);
        $this->db->WHERE('p_r_type', $p_r_type);
        $this->db->WHERE('is_deleted', FALSE);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            $new_case_parties = $query->result();
            $new_case_parties_result = count($new_case_parties);
            return $new_case_parties_result;
        } else {
            $f = 0;
            return $f;
        }
        return $this->db->count_all_results();
    }

    function get_max_party_num_n_id($registration_id, $p_r_type, $lr_of_party_id = null)
    {

        if (isset($lr_of_party_id) && !empty($lr_of_party_id)) {
            $sql = "SELECT
                    (SELECT max(party_no) FROM efil.tbl_case_parties WHERE registration_id = $registration_id AND p_r_type = '$p_r_type' AND is_deleted IS FALSE) max_party_no,
                    (SELECT max(party_id) FROM efil.tbl_case_parties WHERE registration_id = $registration_id AND p_r_type = '$p_r_type' AND parent_id IS NULL
                        AND is_deleted IS FALSE) max_party_id,
                    (SELECT party_id FROM efil.tbl_case_parties WHERE registration_id = $registration_id AND p_r_type = '$p_r_type' AND parent_id = '$lr_of_party_id' "
                . "AND is_deleted IS FALSE ORDER BY id DESC LIMIT 1) max_party_id_of_lr";
        } else {
            $sql = "SELECT
                    (SELECT max(party_no) FROM efil.tbl_case_parties WHERE registration_id = $registration_id AND p_r_type = '$p_r_type' AND is_deleted IS FALSE) max_party_no,
                    (SELECT max(party_id) FROM efil.tbl_case_parties WHERE registration_id = $registration_id AND p_r_type = '$p_r_type' AND parent_id IS NULL
                        AND is_deleted IS FALSE) max_party_id,
                    (SELECT NULL) max_party_id_of_lr";
        }

        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function get_case_table_ids($registration_id)
    {
        $sql = "SELECT
                    (SELECT id FROM efil.tbl_case_parties WHERE registration_id = $registration_id AND p_r_type = 'P' AND m_a_type = 'M' AND is_deleted IS FALSE) m_petitioner_id,
                    (SELECT id FROM efil.tbl_case_parties WHERE registration_id = $registration_id AND p_r_type = 'R' AND m_a_type = 'M' AND is_deleted IS FALSE) m_respondent_id,
                    (SELECT id FROM efil.tbl_case_details WHERE registration_id = $registration_id) case_details_id";

        $query = $this->db->query($sql);

        $result = $query->result_array();

        $_SESSION['case_table_ids'] = $result[0];
    }

    function get_new_case_details($registration_id)
    {

        $this->db->SELECT("cd.*,en.efiling_no");
        $this->db->FROM('efil.tbl_case_details cd');
        $this->db->JOIN('efil.tbl_efiling_nums en', 'cd.registration_id=en.registration_id', 'left');
        $this->db->WHERE('cd.registration_id', $registration_id);
        $this->db->WHERE('cd.is_deleted', FALSE);

        $query = $this->db->get();

        if ($query->num_rows() >= 1) {

            $new_case_details = $query->result_array();
            return $new_case_details;
        } else {
            return FALSE;
        }
    }

    function get_case_parties($registration_id)
    {

        $this->db->SELECT("*");
        $this->db->FROM('efil.tbl_case_parties cd');
        $this->db->WHERE('cd.registration_id', $registration_id);
        $this->db->WHERE('cd.is_deleted', FALSE);

        $query = $this->db->get();

        if ($query->num_rows() >= 1) {

            $new_case_details = $query->result_array();
            return $new_case_details;
        } else {
            return FALSE;
        }
    }

    function get_subordinate_court_details($registration_id, $id = null)
    {

       /* $this->db->SELECT("cd.*,hcb.name as bench_name, fir.fir_no, fir.fir_year, fir.police_station_name, fir.district_name as fir_district_name, fir.state_name as fir_state_name,fir.complete_fir_no");
        $this->db->FROM('efil.tbl_lower_court_details cd');
        $this->db->JOIN('efil.tbl_fir_details fir', "fir.registration_id=cd.registration_id and fir.ref_tbl_lower_court_details_id=cd.id", "left outer");
        $this->db->JOIN('efil.m_tbl_high_courts_bench hcb', "cd.estab_code=hcb.est_code", "left outer");
        $this->db->WHERE('cd.registration_id', $registration_id);
        if (!empty($id) && $id != null) {
            $this->db->WHERE('cd.id', $id);
        }
        $this->db->WHERE('cd.is_deleted', FALSE);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            $new_case_details = $query->result_array();
            return $new_case_details;
        } else {
            return FALSE;
        }*/
       //changed By Anshu 1feb23
        $this->db->SELECT("cd.*,hcb.name as bench_name,hcb.cmis_state_id , hcb.ref_agency_code_id, fir.fir_no, fir.fir_year, fir.police_station_name, fir.district_name as fir_district_name, fir.state_name as fir_state_name,fir.complete_fir_no");
        $this->db->FROM('efil.tbl_lower_court_details cd');
        $this->db->JOIN('efil.tbl_fir_details fir', "fir.registration_id=cd.registration_id and fir.ref_tbl_lower_court_details_id=cd.id", "left outer");
        $this->db->JOIN('efil.m_tbl_high_courts_bench hcb', "cd.estab_code=hcb.est_code", "left outer");
        $this->db->WHERE('cd.registration_id', $registration_id);
        if (!empty($id) && $id != null) {
            $this->db->WHERE('cd.id', $id);
        }
        $this->db->WHERE('cd.is_deleted', FALSE);
        $query = $this->db->get();
        // echo $this->db->last_query();
        if ($query->num_rows() >= 1) {
            $new_case_details = $query->result_array();
            // var_dump($new_case_details);
            return $new_case_details;
        } else {
            return FALSE;
        }
    }

    function getSubordinateCourtData($registration_id)
    {
        $this->db->distinct()->SELECT("cd.* ,
            (case when cd.court_type = '1' then hcb.cmis_state_id   
            else case when cd.court_type = '3' then dce.cmis_id_no
            else case when cd.court_type = '5' then rac.cmis_state_id
            else case when cd.court_type = '4' then 0 ::integer
            else case when cd.court_type is null  then 0 ::integer
            end end end end end )as cmis_state_id,
            (case when cd.court_type = '1' then hcb.ref_agency_code_id   
            else case when cd.court_type = '3' then dce.id
            else case when cd.court_type = '5' then rac.id
            else case when cd.court_type = '4' then 0 ::integer
            else case when cd.court_type is null  then 0 ::integer
            end end end end end )as ref_agency_code_id       
             ");
        $this->db->FROM('efil.tbl_lower_court_details cd');
        $this->db->JOIN('efil.m_tbl_high_courts_bench hcb', 'cd.estab_code=hcb.est_code', 'left');
        $this->db->JOIN('efil.m_tbl_district_courts_establishments dce', 'cd.estab_code=dce.estab_code', 'left');
        $this->db->JOIN('icmis.ref_agency_code rac', "cd.estab_code=rac.short_agency_name and cd.state_id=rac.cmis_state_id and cd.estab_code !=''", "left");
        $this->db->WHERE('cd.registration_id', $registration_id);
        $this->db->WHERE('cd.is_deleted', FALSE);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            $new_case_details = $query->result_array();
            return $new_case_details;
        } else {
            return FALSE;
        }
    }

    public function getTotalIsDeadMinor($params = array())
    {
        $output = false;
        if (isset($params['registration_id']) && !empty($params['registration_id'])) {
            if (isset($params['total']) && !empty($params['total'])) {
                $this->db->SELECT('count(tcp.id) total');
                $this->db->FROM('efil.tbl_case_parties tcp');
                $this->db->WHERE('tcp.registration_id', (int)$params['registration_id']);
                if (isset($params['is_dead_minor']) && !empty($params['is_dead_minor'])) {
                    $this->db->WHERE('tcp.is_dead_minor', true);
                }
                if (isset($params['is_deleted']) && !empty($params['is_deleted'])) {
                    $this->db->WHERE('tcp.is_deleted', false);
                }
                if (isset($params['is_dead_file_status']) && !empty($params['is_dead_file_status'])) {
                    $this->db->WHERE('tcp.is_dead_file_status', false);
                }
                $query = $this->db->get();
                $output = $query->result();
            } else {
                $this->db->SELECT('tcp.id,tcp.registration_id');
                $this->db->FROM('efil.tbl_case_parties tcp');
                $this->db->WHERE('tcp.registration_id', (int)$params['registration_id']);
                if (isset($params['is_dead_minor']) && !empty($params['is_dead_minor'])) {
                    $this->db->WHERE('tcp.is_dead_minor', true);
                }
                if (isset($params['is_deleted']) && !empty($params['is_deleted'])) {
                    $this->db->WHERE('tcp.is_deleted', false);
                }
                if (isset($params['is_dead_file_status']) && !empty($params['is_dead_file_status'])) {
                    $this->db->WHERE('tcp.is_dead_file_status', false);
                }
                $query = $this->db->get();
                $output = $query->result();
            }
        }
        return $output;
    }

        //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

        function get_lrs_remarks_details()
        {

            $this->db->SELECT("*");
            $this->db->FROM('efil.m_tbl_lrs_remarks lrs');
            $this->db->WHERE('lrs.is_deleted', FALSE);

            $query = $this->db->get();

            if ($query->num_rows() >= 1) {

                $lrs_details = $query->result_array();
                return $lrs_details;
            } else {
                return FALSE;
            }
        }//End of function get_lrs_remarks_details()..


        //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX


    }


