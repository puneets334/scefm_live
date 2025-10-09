<?php

namespace App\Models\NewCaseQF;

use CodeIgniter\Model;

class Dropdown_list_model extends Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $db = \Config\Database::connect();
    }

    //Code by Mohit Jain dt:27/04/2020
    public function insert_to_table($table, $data_array)
    {
        $this->db->table($table)->insert($data_array);

        return $this->db->affectedRows(); // Return the number of affected rows after insert
    }

    public function get_states_list()
    {
        $builder = $this->db->table('icmis.ref_agency_state');
        $builder->select('cmis_state_id, agency_state')
            ->where('is_deleted', 'False')
            ->where('id !=', 9999)
            ->orderBy('agency_state', 'asc');

        $query = $builder->get();
        return $query->getResult();
    }

    public function getCountryList()
    {
        $builder = $this->db->table('country'); // Specify the table name

        $builder->select('id, country_name, country_code, short_description'); // Select the columns
        $builder->where('display', 'Y'); // Add a WHERE clause
        $builder->orderBy('country_name', 'ASC'); // Add an ORDER BY clause

        $query = $builder->get(); // Execute the query

        return $query->getResult(); // Return the result as an array of objects
    }


    function get_address_state_list()
    {
        $this->db->SELECT('id_no as cmis_state_id, name as agency_state', false);
        $this->db->FROM('icmis.state');
        $this->db->WHERE('district_code =0 AND sub_dist_code =0 AND village_code =0 AND display = \'Y\' AND sci_state_id !=0', NULL, false);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->result();
    }

    function get_districts_list($state_id)
    {
        $this->db->SELECT("st2.id_no,st2.name");
        $this->db->FROM('icmis.state st1');
        $this->db->JOIN('icmis.state st2', 'st1.state_code = st2.state_code');
        $this->db->WHERE('st1.id_no', $state_id);
        $this->db->WHERE('st2.district_code !=0');
        $this->db->WHERE('st2.sub_dist_code = 0');
        $this->db->WHERE('st2.village_code = 0');
        $this->db->WHERE('st2.display', 'Y');
        $this->db->WHERE('st1.display', 'Y');
        $this->db->WHERE('st2.display', 'Y');
        $this->db->ORDER_BY("st2.name", "asc");
        $query = $this->db->get();
        return $query->result();
    }
    function getPincodeDetails($pincode)
    {
        $this->db->SELECT('*');
        $this->db->FROM('icmis.pincode_district_mapping');
        $this->db->WHERE('pincode', $pincode);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            $result = $query->result_array();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_departments_list($dept_type)
    {

        switch ($dept_type) {
            case D1:
                $dept_type = 'S';
                break;
            case D2:
                $dept_type = 'C';
                break;
            case D3:
                $dept_type = 'O';
                break;
            default:
                $dept_type = '';
        }

        $this->db->SELECT("deptcode, deptname");
        $this->db->FROM('icmis.deptt');

        if ($dept_type == 'S' || $dept_type == 'C') {
            $this->db->WHERE('deptype', $dept_type);
        } elseif ($dept_type == 'O') {
            $this->db->WHERE_NOT_IN('deptype', array('S', 'C'));
        } else {
            return false;
        }
        $this->db->WHERE('display', 'Y');
        $this->db->ORDER_BY("deptname", "asc");
        $query = $this->db->get();
        return $query->result();
    }

    function get_posts_list()
    {

        $this->db->SELECT("a.authcode, a.authdesc");
        $this->db->FROM('icmis.authority a');
        $this->db->WHERE('a.display', 'Y');
        $this->db->ORDER_BY("a.authdesc", "asc");
        $query = $this->db->get();
        return $query->result();
    }

    function get_sci_case_type()
    {
        $this->db->SELECT("casecode, casename");
        $this->db->FROM('icmis.casetype');
        $this->db->WHERE('display', 'Y');
        $this->db->ORDER_BY("casename", "asc");
        $query = $this->db->get();
        return $query->result();
    }
    function getSciCaseTypeOrderById()
    {
        $this->db->SELECT("casecode, casename");
        $this->db->FROM('icmis.casetype');
        $this->db->WHERE('display', 'Y');
        $this->db->ORDER_BY("casecode", "asc");
        $query = $this->db->get();
        return $query->result();
    }


    function get_sci_case_type_name($id)
    {
        $this->db->SELECT("casename");
        $this->db->FROM('icmis.casetype');
        $this->db->WHERE('display', 'Y');
        $this->db->WHERE('casecode', $id);
        $this->db->ORDER_BY("casename", "asc");
        $query = $this->db->get();
        return $query->result();
    }

    function get_main_subject_category()
    {
        $this->db->SELECT("id, subcode1, sub_name1,category_sc_old");
        $this->db->FROM('icmis.submaster');
        $this->db->WHERE('subcode1 !=0 ');
        $this->db->WHERE('subcode2 = 0');
        $this->db->WHERE('subcode3 = 0');
        $this->db->WHERE('subcode4 = 0');
        $this->db->WHERE('display', 'Y');
        $this->db->WHERE('flag_use', 'S');
        $this->db->WHERE('flag', 's');
        $this->db->ORDER_BY("sub_name1", "asc");
        $query = $this->db->get();
        return $query->result();
    }

    function get_main_subject_category_name($id)
    {
        $this->db->SELECT("sub_name1,sub_name4");
        $this->db->FROM('icmis.submaster');
        //$this->db->WHERE('subcode1 !=0 ');
        //$this->db->WHERE('subcode2 = 0');
        //$this->db->WHERE('subcode3 = 0');
        //$this->db->WHERE('subcode4 = 0');
        //$this->db->WHERE('display', 'Y');
        $this->db->WHERE('id', $id);
        $this->db->ORDER_BY("sub_name1", "asc");
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        return $query->result();
    }

    function get_sub_category($sub_cat_1, $sub_cat_2, $sub_cat_3, $sub_cat_4)
    {

        if ($sub_cat_2 == 0 && $sub_cat_3 == 0 && $sub_cat_4 == 0) {
            $this->db->SELECT("id, subcode1, subcode2, sub_name4 as sub_cat_name,category_sc_old");
        } elseif ($sub_cat_3 == 0 && $sub_cat_4 == 0) {
            $this->db->SELECT("id, subcode1, subcode2, subcode3, sub_name4 as sub_cat_name,category_sc_old");
        } elseif ($sub_cat_4 == 0) {
            $this->db->SELECT("id, subcode1, subcode2, subcode3, subcode4, sub_name4 as sub_cat_name,category_sc_old");
        } else {
            $this->db->SELECT("id, subcode1, subcode2, subcode3, subcode4, sub_name4 as sub_cat_name,category_sc_old");
        }

        $this->db->FROM('icmis.submaster');

        $this->db->WHERE('subcode1', $sub_cat_1);

        if ($sub_cat_2 == 0 && $sub_cat_3 == 0 && $sub_cat_4 == 0) {
            $this->db->WHERE("subcode2 != $sub_cat_2");
            $this->db->WHERE('subcode3', $sub_cat_3);
            $this->db->WHERE('subcode4', $sub_cat_4);
        } elseif ($sub_cat_3 == 0 && $sub_cat_4 == 0) {
            $this->db->WHERE('subcode2', $sub_cat_2);
            $this->db->WHERE("subcode3 != $sub_cat_3");
            $this->db->WHERE('subcode4', $sub_cat_4);
        } elseif ($sub_cat_4 == 0) {
            $this->db->WHERE('subcode2', $sub_cat_2);
            $this->db->WHERE('subcode3', $sub_cat_3);
            $this->db->WHERE("subcode4 != $sub_cat_4");
        } /*else {
            $this->db->WHERE('subcode2', $sub_cat_2);
            $this->db->WHERE('subcode3', $sub_cat_3);
            $this->db->WHERE('subcode4', $sub_cat_4);
        }*/
        $this->db->WHERE('display', 'Y');
        $this->db->WHERE('flag', 's');
        $this->db->WHERE('flag_use', 'S');
        //$this->db->ORDER_BY("sub_cat_name", "asc");
        $this->db->ORDER_BY('subcode1, subcode2');

        //$this->db->ORDER_BY("sub", "asc");

        $query = $this->db->get();
        // $this->db->last_query();die;
        return $query->result();
    }

    function get_acts_list()
    {
        // acts list to show on act-sections page
        $this->db->SELECT("id act_id, act_name, year act_year");
        $this->db->FROM('icmis.act_master');
        $this->db->WHERE('display', 'Y');
        $this->db->ORDER_BY("id", "asc");
        $query = $this->db->get();
        return $query->result();
    }

    function get_high_court_list()
    {

        $this->db->SELECT("*");
        $this->db->FROM('efil.m_tbl_high_courts');
        $this->db->WHERE('is_active', TRUE);
        $this->db->ORDER_BY("hc_name", "ASC");
        $query = $this->db->get();
        return $query->result();
    }

    function get_high_court_code($high_court_id)
    {

        $this->db->SELECT("*");
        $this->db->FROM('efil.m_tbl_high_courts');
        $this->db->WHERE('is_active', TRUE);
        $this->db->WHERE('id', $high_court_id);
        $this->db->ORDER_BY("hc_name", "ASC");
        $query = $this->db->get();
        return $query->result();
    }

    function get_eshtablishment_code($establishment_id)
    {

        $this->db->SELECT("id,estname,est_code,state_code");
        $this->db->FROM('efil.m_tbl_establishments');
        $this->db->WHERE('id', $establishment_id);
        $this->db->WHERE('display', 'Y');
        $this->db->ORDER_BY("estname", "asc");
        $query = $this->db->get();
        return $query->result();
    }
    function get_high_state_court_list()
    {
        $this->db->SELECT("*");
        $this->db->FROM('efil.m_tbl_high_courts hc');
        $this->db->JOIN('efil.m_tbl_state st', 'st.state_id=hc.state_code', 'LEFT');
        $this->db->WHERE('hc.is_active', TRUE);

        $this->db->ORDER_BY("hc_name", "ASC");
        $query = $this->db->get();

        return $query->result();
    }

    //Code by Mohit Jain dt:27/04/2020
    function high_courts()
    {
        $query = $this->db->select('hc_id, name')->where('bench_id is null and est_code is null')->get('efil.m_tbl_high_courts_bench');
        return $query->result_array();
    }

    function hc_bench($hc_id, $type = 1)
    {
        if ($type == 1)
            $this->db->where('hc_id', $hc_id);
        $query = $this->db->select('hc_id, bench_id, est_code,name')
            ->where('bench_id is not null and est_code is not null')
            ->get('efil.m_tbl_high_courts_bench');
        return $query->result_array();
    }

    function hc_case_types($est_code)
    {
        $alt_est_code = substr($est_code, 0, 4) . '00';
        $query = $this->db->select('est_code, case_type, type_name')
            ->where('case_type is not null and est_code is not null')->where_in('est_code', array($est_code, $alt_est_code))
            ->get('efil.m_tbl_high_courts_case_types');
        return $query->result_array();
    }


    function icmis_states()
    {
        $query = $this->db->select('id_no as hc_id, name')->where("district_code =0 AND sub_dist_code =0 AND village_code =0 AND display = 'Y' AND sci_state_id !=0")->order_by('name')->get('icmis.state');
        return $query->result_array();
    }

    function icmis_state_agencies($state_id, $court_type)
    {
        if (trim($court_type) == '5') {
            $query = $this->db->select('id, agency_name, short_agency_name')->where("is_deleted = 'f' AND agency_or_court  in('2','5','6') AND cmis_state_id = '$state_id'")->order_by('trim(agency_name)')->get('icmis.ref_agency_code');
            return $query->result_array();
        }
    }

    function icmis_state_agencies_case_types($agency_id, $court_type)
    {
        $query = $this->db->select('lccasecode case_type, type_sname type_name')->where("display = 'Y' AND cmis_state_id = (select cmis_state_id from icmis.ref_agency_code where id=$agency_id) AND ref_agency_code_id !=0 and  type_sname!=''")->order_by('lccasename')->get('icmis.lc_hc_casetype');
        return $query->result_array();
    }

    function get_district_court_establishments()
    {
        $query = $this->db->distinct('est_code')->get('efil.m_tbl_district_courts_establishments');
        return $query->result_array();
    }
    function getStateCentralDept()
    {
        $this->db->select('deptcode, deptname');
        $this->db->from('icmis.view_state_in_name');
        $this->db->order_by('deptname', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }
    public function getPoliceStationList($state_id, $district_id)
    {
        $output = false;
        if (isset($state_id) && !empty($state_id) && isset($district_id) && !empty($district_id)) {
            $this->db->SELECT('policestncd,policestndesc');
            $this->db->FROM('icmis.police');
            $this->db->WHERE('cmis_state_id', (int)$state_id);
            $this->db->WHERE('cmis_district_id', (int)$district_id);
            $this->db->WHERE('display', 'Y');
            $this->db->order_by('policestndesc', 'ASC');
            $query = $this->db->get();
            $output = $query->result();
        }
        return $output;
    }
}
