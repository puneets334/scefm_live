<?php

namespace App\Models\NewCase;

use CodeIgniter\Model;

class DropdownListModel extends Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    // Code by Mohit Jain dt:27/04/2020
    function insert_to_table($table, $data_array)
    {
        if ($this->db->table($table)->insert($table, $data_array))
            return 1;
        else
            return 0;
    }

    // function get_states_list() {
    //     $this->db->SELECT("cmis_state_id,agency_state");
    //     $this->db->FROM('icmis.ref_agency_state');
    //     $this->db->WHERE('is_deleted', 'False');
    //     $this->db->where('id!=9999');
    //     $this->db->ORDER_BY("agency_state", "asc");
    //     $query = $this->db->get();
    //     return $query->result();
    // }

    public function get_states_list()
    {
        $builder = $this->db->table('icmis.ref_agency_state');
        $builder->select('cmis_state_id, agency_state');
        $builder->where('is_deleted', 'False');
        $builder->where('id !=', 9999);
        $builder->orderBy('agency_state', 'asc');
        $query = $builder->get();
        return $query->getResult();
    }

    public function getCountryList()
    {
        $builder = $this->db->table('icmis.country');
        $builder->SELECT('id, country_name, country_code, short_description');
        $builder->WHERE('display', 'Y');
        $builder->orderBy('country_name', 'ASC');
        $query = $builder->get();
        return $query->getResult();
    }

    function get_address_state_list()
    {
        $builder = $this->db->table('icmis.state');
        $builder->SELECT('id_no as cmis_state_id, name as agency_state', false);
        $builder->WHERE('district_code =0 AND sub_dist_code =0 AND village_code =0 AND display = \'Y\' AND sci_state_id !=0', NULL, false);
        $query = $builder->get();
        return $query->getResult();
    }

    function get_districts_list($state_id)
    {
        $builder = $this->db->table('icmis.state st1');
        $builder->SELECT("st2.id_no,st2.name");
        $builder->JOIN('icmis.state st2', 'st1.state_code = st2.state_code');
        $builder->WHERE('st1.id_no', $state_id);
        $builder->WHERE('st2.district_code !=0');
        $builder->WHERE('st2.sub_dist_code = 0');
        $builder->WHERE('st2.village_code = 0');
        $builder->WHERE('st2.display', 'Y');
        $builder->WHERE('st1.display', 'Y');
        $builder->WHERE('st2.display', 'Y');
        $builder->orderBy("st2.name", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    function getPincodeDetails($pincode)
    {
        $builder = $this->db->table('icmis.pincode_district_mapping');
        $builder->SELECT('*');
        $builder->WHERE('pincode', $pincode);
        $builder->limit(1);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_departments_list($dept_type)
    {
        switch ($dept_type) {
            case 'D1':
                $dept_type = 'S';
                break;
            case 'D2':
                $dept_type = 'C';
                break;
            case 'D3':
                $dept_type = 'O';
                break;
            default:
                $dept_type = '';
        }
        $builder = $this->db->table('icmis.deptt');
        $builder->SELECT("deptcode, deptname");
        if ($dept_type == 'S' || $dept_type == 'C') {
            $builder->WHERE('deptype', $dept_type);
        } elseif ($dept_type == 'O') {
            $builder->whereNotIn('deptype', array('S', 'C'));
        } else {
            return false;
        }
        $builder->WHERE('display', 'Y');
        $builder->orderBy("deptname", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    function get_posts_list()
    {
        $builder = $this->db->table('icmis.authority a');
        $builder->SELECT("a.authcode, a.authdesc");
        $builder->WHERE('a.display', 'Y');
        $builder->orderBy("a.authdesc", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    function get_sci_case_type()
    {
        $builder = $this->db->table("icmis.casetype");
        $builder->select("casecode, casename");
        $builder->where('display', 'Y');
        $builder->whereNotIn('casecode', array(13, 14, 9999, 15, 16));
        $builder->orderBy("casename", "asc");
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }

    // function getSciCaseTypeOrderById() {
    //     $this->db->SELECT("casecode, casename");
    //     $this->db->FROM('icmis.casetype');
    //     $this->db->WHERE('display', 'Y');
    //     $this->db->ORDER_BY("casecode", "asc");
    //     $query = $this->db->get();
    //     return $query->result();
    // }

    public function getSciCaseTypeOrderById()
    {
        $builder = $this->db->table('icmis.casetype');
        $builder->select('casecode, casename');
        $builder->where('display', 'Y');
        $builder->whereNotIn('casecode', array(13, 14, 9999, 15, 16));
        $builder->orderBy('casecode', 'asc');
        $query = $builder->get();
        return $query->getResult();
    }

    function get_sci_case_type_name($id)
    {
        // $this->db->SELECT("casename");
        // $this->db->FROM('icmis.casetype');
        // $this->db->WHERE('display', 'Y');
        // $this->db->WHERE('casecode', $id);
        // $this->db->ORDER_BY("casename", "asc");
        // $query = $this->db->get();
        // return $query->result();
        $builder = $this->db->table('icmis.casetype');
        $builder->select("casename");
        $builder->where('display', 'Y');
        $builder->where('casecode', $id);
        $builder->whereNotIn('casecode', array(13, 14, 9999, 15, 16));
        $builder->orderBy("casename", "asc");
        $query = $builder->get();
        $output = $query->getResult();
        return $output;
    }

    // function get_main_subject_category() {
    //     $builder=$this->db->table('icmis.submaster');
    //     $builder->select("id, subcode1, sub_name1,category_sc_old");
    //     $builder->where('subcode1 !=0');
    //     $builder->where('subcode2 =0');
    //     $builder->where('subcode3 =0');
    //     $builder->where('subcode4 =0');
    //     $builder->where('display', 'Y');
    //     $builder->where('flag_use', 'S');
    //     $builder->where('flag', 's');
    //     $builder->orderBy("sub_name1", "asc");
    //     $query = $builder->get();
    //     $result = $query->getResult();
    //     return $result;
    // }

    // function get_main_subject_category_name($id) {
    //     $builder=$this->db->table('icmis.submaster');
    //     $builder->SELECT("sub_name1,sub_name4");
    //     $builder->WHERE('id', $id);
    //     $builder->orderBy("sub_name1", "asc");
    //     $query = $builder->get();
    //     return $query->getResult();
    // }

    // function get_sub_category($sub_cat_1, $sub_cat_2, $sub_cat_3, $sub_cat_4) {
    //     $builder = $this->db->table('icmis.submaster');
    //     if ($sub_cat_2 == 0 && $sub_cat_3 == 0 && $sub_cat_4 == 0) {
    //         $builder->SELECT("id, subcode1, subcode2, sub_name4 as sub_cat_name,category_sc_old");
    //     } elseif ($sub_cat_3 == 0 && $sub_cat_4 == 0) {
    //         $builder->SELECT("id, subcode1, subcode2, subcode3, sub_name4 as sub_cat_name,category_sc_old");
    //     } elseif ($sub_cat_4 == 0) {
    //         $builder->SELECT("id, subcode1, subcode2, subcode3, subcode4, sub_name4 as sub_cat_name,category_sc_old");
    //     } else {
    //         $builder->SELECT("id, subcode1, subcode2, subcode3, subcode4, sub_name4 as sub_cat_name,category_sc_old");
    //     }
    //     $builder->WHERE('subcode1', $sub_cat_1);
    //     if ($sub_cat_2 == 0 && $sub_cat_3 == 0 && $sub_cat_4 == 0) {
    //         $builder->WHERE("subcode2 != $sub_cat_2");
    //         $builder->WHERE('subcode3', $sub_cat_3);
    //         $builder->WHERE('subcode4', $sub_cat_4);
    //     } elseif ($sub_cat_3 == 0 && $sub_cat_4 == 0) {
    //         $builder->WHERE('subcode2', $sub_cat_2);
    //         $builder->WHERE("subcode3 != $sub_cat_3");
    //         $builder->WHERE('subcode4', $sub_cat_4);
    //     } elseif ($sub_cat_4 == 0) {
    //         $builder->WHERE('subcode2', $sub_cat_2);
    //         $builder->WHERE('subcode3', $sub_cat_3);
    //         $builder->WHERE("subcode4 != $sub_cat_4");
    //     }
    //     $builder->WHERE('display', 'Y');
    //     $builder->WHERE('flag', 's');
    //     $builder->WHERE('flag_use', 'S');
    //     $builder->orderBy('subcode1, subcode2');
    //     //$this->db->ORDER_BY("sub", "asc");
    //     $query = $builder->get();
    //     // $this->db->last_query();die;
    //     return $query->getResult();
    // }

    function get_main_subject_category($subj_main_cat=null)
    {
        $builder = $this->db->table('icmis.submaster');
        $builder->SELECT("id, subcode1, sub_name1,category_sc_old");
        $builder->WHERE('subcode1 !=', '0');
        $builder->WHERE('subcode2', '0');
        $builder->WHERE('subcode3', '0');
        $builder->WHERE('subcode4', '0');
        // $builder->WHERE('flag_use', 'S');
        // $builder->WHERE('flag', 's');
        $builder->WHERE('display', 'Y');
        $builder->WHERE('is_old', 'N');
        $builder->WHERE('is_3j', 0);
        $builder->WHERE('is_5j', 0);
        $builder->WHERE('is_7j', 0);
        $builder->WHERE('is_9j', 0);
        $builder->WHERE('is_11j', 0);
        if (!empty($subj_main_cat)) {
            $builder->WHERE('id',$subj_main_cat);
        }
        $builder->orderBy('CAST(category_sc_old AS INTEGER)', 'ASC');
        $query = $builder->get();
        // echo $builder->last_query();die;
        return $query->getResult();
    }

    function get_main_subject_category_name($id)
    {
        $builder = $this->db->table('icmis.submaster');
        $builder->SELECT("sub_name1,sub_name4");
        // $builder->WHERE('subcode1 !=0 ');
        // $builder->WHERE('subcode2 = 0');
        // $builder->WHERE('subcode3 = 0');
        // $builder->WHERE('subcode4 = 0');
        $builder->WHERE('display', 'Y');
        $builder->WHERE('is_old', 'N');
        $builder->WHERE('is_3j', 0);
        $builder->WHERE('is_5j', 0);
        $builder->WHERE('is_7j', 0);
        $builder->WHERE('is_9j', 0);
        $builder->WHERE('is_11j', 0);
        $builder->WHERE('id', $id);
        $builder->orderBy("sub_name1", "asc");
        $query = $builder->get();
        // echo $builder->last_query();die;
        return $query->getResult();
    }

    function get_sub_category($sub_cat_1, $sub_cat_2, $sub_cat_3, $sub_cat_4)
    {
        $builder = $this->db->table('icmis.submaster');
        if ($sub_cat_2 == 0 && $sub_cat_3 == 0 && $sub_cat_4 == 0) {
            $builder->SELECT("id, subcode1, subcode2, sub_name4 as sub_cat_name,category_sc_old");
        } elseif ($sub_cat_3 == 0 && $sub_cat_4 == 0) {
            $builder->SELECT("id, subcode1, subcode2, subcode3, sub_name4 as sub_cat_name,category_sc_old");
        } elseif ($sub_cat_4 == 0) {
            $builder->SELECT("id, subcode1, subcode2, subcode3, subcode4, sub_name4 as sub_cat_name,category_sc_old");
        } else {
            $builder->SELECT("id, subcode1, subcode2, subcode3, subcode4, sub_name4 as sub_cat_name,category_sc_old");
        }
        $builder->WHERE('subcode1', $sub_cat_1);
        if ($sub_cat_2 == 0 && $sub_cat_3 == 0 && $sub_cat_4 == 0) {
            $builder->WHERE('subcode2 !=', "$sub_cat_2");
            $builder->WHERE('subcode3', $sub_cat_3);
            $builder->WHERE('subcode4', $sub_cat_4);
        } elseif ($sub_cat_3 == 0 && $sub_cat_4 == 0) {
            $builder->WHERE('subcode2', $sub_cat_2);
            $builder->WHERE('subcode3 !=', "$sub_cat_3");
            $builder->WHERE('subcode4', $sub_cat_4);
        } elseif ($sub_cat_4 == 0) {
            $builder->WHERE('subcode2', $sub_cat_2);
            $builder->WHERE('subcode3', $sub_cat_3);
            $builder->WHERE('subcode4 !=', "$sub_cat_4");
        } /*else {
            $builder->WHERE('subcode2', $sub_cat_2);
            $builder->WHERE('subcode3', $sub_cat_3);
            $builder->WHERE('subcode4', $sub_cat_4);
        }*/
        // $builder->WHERE('flag', 's');
        // $builder->WHERE('flag_use', 'S');
        $builder->WHERE('display', 'Y');
        $builder->WHERE('is_old', 'N');
        $builder->WHERE('is_3j', 0);
        $builder->WHERE('is_5j', 0);
        $builder->WHERE('is_7j', 0);
        $builder->WHERE('is_9j', 0);
        $builder->WHERE('is_11j', 0);
        // $builder->ORDER_BY("sub_cat_name", "asc");
        $builder->orderBy('subcode1, subcode2');
        // $builder->ORDER_BY("sub", "asc");
        $query = $builder->get();
        // echo $builder->last_query();die;
        return $query->getResult();
    }

    function get_acts_list()
    {
        // acts list to show on act-sections page
        $builder = $this->db->table('icmis.act_master');
        $builder->SELECT("id act_id, act_name, year act_year");
        $builder->WHERE('display', 'Y');
        $builder->orderBy("id", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    function get_high_court_list()
    {
        $builder = $this->db->table('efil.m_tbl_high_courts');
        $builder->SELECT("*");
        $builder->WHERE('is_active', TRUE);
        $builder->orderBy("hc_name", "ASC");
        $query = $builder->get();
        return $query->getResult();
    }

    function get_high_court_code($high_court_id)
    {
        $builder = $this->db->table('efil.m_tbl_high_courts');
        $builder->SELECT("*");
        $builder->WHERE('is_active', TRUE);
        $builder->WHERE('id', $high_court_id);
        $builder->orderBy("hc_name", "ASC");
        $query = $builder->get();
        return $query->getResult();
    }

    function get_eshtablishment_code($establishment_id)
    {
        $builder = $this->db->table('efil.m_tbl_establishments')
            ->SELECT("id,estname,est_code,state_code")
            ->WHERE('id', $establishment_id)
            ->WHERE('display', 'Y')
            ->orderBy("estname", "asc");
        $query = $builder->get();
        return $query->getResult();
    }
    function get_high_state_court_list()
    {
        $builder = $this->db->table('efil.m_tbl_high_courts hc')
            ->SELECT("*")
            ->JOIN('efil.m_tbl_state st', 'st.state_id=hc.state_code', 'LEFT')
            ->WHERE('hc.is_active', TRUE)
            ->orderBy("hc_name", "ASC");
        $query = $builder->get();
        return $query->getResult();
    }

    // Code by Mohit Jain dt:27/04/2020
    function high_courts()
    {
        $builder = $this->db->table('efil.m_tbl_high_courts_bench');
        $builder->select('hc_id, name');
        $builder->where('bench_id is null and est_code is null');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }

    function hc_bench($hc_id, $type = 1)
    {
        $builder = $this->db->table('efil.m_tbl_high_courts_bench');
        if ($type == 1) {
            $builder->where('hc_id', $hc_id);
        }
        $query = $builder->select('hc_id, bench_id, est_code, name')
            ->where('bench_id IS NOT NULL')
            ->where('est_code IS NOT NULL')
            ->get();
        return $query->getResultArray();
    }

    function hc_case_types($est_code)
    {
        $alt_est_code = substr($est_code, 0, 4) . '00';
        $query = $this->db->table('efil.m_tbl_high_courts_case_types')->select('est_code, case_type, type_name')
            ->where('case_type is not null and est_code is not null')->whereIn('est_code', array($est_code, $alt_est_code))
            ->get();
        return $query->getResult();
    }

    function icmis_states()
    {
        $query = $this->db->table('icmis.state')->select('id_no as hc_id, name')->where("district_code =0 AND sub_dist_code =0 AND village_code =0 AND display = 'Y' AND sci_state_id !=0")->orderBy('name', 'asc')->get();
        return $query->getResult();
    }

    function icmis_state_agencies($state_id, $court_type)
    {
        if (trim($court_type) == '5') {
            $query = $this->db->table('icmis.ref_agency_code')
                ->select('id, agency_name, short_agency_name')
                ->where("is_deleted = 'f'")
                ->whereIn('agency_or_court', ['2', '5', '6'])
                ->where('cmis_state_id', $state_id)
                ->orderBy('TRIM(agency_name)')
                ->get();
            return $query->getResultArray();
        }
    }

    function icmis_state_agencies_case_types($agency_id, $court_type)
    {
        $query = $this->db->table('icmis.lc_hc_casetype')
            ->select('lccasecode as case_type, type_sname as type_name')
            ->where("display = 'Y'")
            ->where("cmis_state_id = (select cmis_state_id from icmis.ref_agency_code where id = $agency_id)")
            ->where('ref_agency_code_id !=', 0)
            ->where("type_sname !=", '')
            ->orderBy('lccasename')
            ->get();
        return $query->getResultArray();
    }

    function get_district_court_establishments()
    {
        $builder = $this->db->table('efil.m_tbl_district_courts_establishments');
        $builder->distinct('est_code');
        $query = $builder->get();
        return $query->getResultArray();
    }

    function getStateCentralDept()
    {
        $builder = $this->db->table('icmis.view_state_in_name');
        $builder->select('deptcode, deptname');
        $builder->orderBy('deptname', 'ASC');
        $query = $builder->get();
        return $query->getResult();
    }

    public function getPoliceStationList($state_id, $district_id)
    {
        $output = false;
        if (isset($state_id) && !empty($state_id) && isset($district_id) && !empty($district_id)) {
            $builder = $this->db->table('icmis.police');
            $builder->select('policestncd,policestndesc');
            $builder->where('cmis_state_id', (int)$state_id);
            $builder->where('cmis_district_id', (int)$district_id);
            $builder->where('display', 'Y');
            $builder->orderBy('policestndesc', 'ASC');
            $query = $builder->get();
            $output = $query->getResult();
        }
        return $output;
    }

    function get_special_category()
    {
        $builder = $this->db->table('icmis.ref_special_category_filing');
        $builder->select("id, category_name");
        $builder->where('display', 'Y');
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        $output = $query->getResult();
        return $output;
    }

    public function check_submaster_category($registration_id)
    {
        $builder = $this->db->table('efil.tbl_case_details cd');
        $builder->SELECT("cd.*,en.efiling_no");
        $builder->JOIN('efil.tbl_efiling_nums en', 'cd.registration_id=en.registration_id');
        $builder->JOIN('efil.tbl_efiling_num_status tens', 'cd.registration_id=tens.registration_id');
        $builder->WHERE('cd.registration_id', $registration_id);
        $builder->WHERE('cd.is_deleted', FALSE);
        $builder->WHERE('en.is_deleted', FALSE);
        $builder->WHERE('en.is_active', true);
        $builder->WHERE('tens.is_deleted', FALSE);
        $builder->WHERE('tens.is_active', true);
        $builder->WHERE('tens.stage_id', Draft_Stage);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $new_case_details = $query->getResultArray();
            $out_put = false;
            if (!empty($new_case_details[0]['subj_sub_cat_1']) && ($new_case_details[0]['subj_sub_cat_1'] == $new_case_details[0]['subject_cat'])) {
                $is_subject_cat = $this->get_sub_subject_category($new_case_details[0]['subject_cat']);
                $is_subj_main_cat = $this->get_main_subject_category($new_case_details[0]['subj_main_cat']);
                if (empty($is_subject_cat) || empty($is_subj_main_cat)) {
                    $out_put = true;
                }
            } else {
                $is_subj_main_cat = $this->get_main_subject_category($new_case_details[0]['subj_main_cat']);
                if (empty($is_subj_main_cat)) {
                    $out_put = true;
                }
            }
            return $out_put;
        } else {
            return false;
        }
    }

    public function get_sub_subject_category($subject_cat)
    {
        $builder = $this->db->table('icmis.submaster');
        $builder->SELECT("id, subcode1,subcode2,sub_name1,sub_name4 as sub_cat_name,category_sc_old");
        $builder->WHERE('subcode1 !=', '0');
        $builder->WHERE('subcode2 !=', '0');
        $builder->WHERE('subcode3', '0');
        $builder->WHERE('subcode4', '0');
        $builder->WHERE('display', 'Y');
        $builder->WHERE('is_old', 'N');
        $builder->WHERE('is_3j', 0);
        $builder->WHERE('is_5j', 0);
        $builder->WHERE('is_7j', 0);
        $builder->WHERE('is_9j', 0);
        $builder->WHERE('is_11j', 0);
        $builder->WHERE('id', $subject_cat);
        $query = $builder->get();
        return $query->getResult();
    }


    public function getStateName($id)
    {
        $builder = $this->db->table('icmis.ref_agency_state');
        $builder->select('agency_state');
        $builder->where('cmis_state_id', $id);
        $query = $builder->get();
        $rs = $query->getRow();
        return !empty($rs) ? $rs->agency_state : '';
    }

    function getDistrictName($state_id)
    {
        $builder = $this->db->table('icmis.state');
        $builder->SELECT("name");
        // $builder->JOIN('icmis.state st2', 'st1.state_code = st2.state_code');
        $builder->WHERE('id_no', $state_id);
        $query = $builder->get();
        $rs = $query->getRow();
        return !empty($rs) ? $rs->name : '';
    }
}
