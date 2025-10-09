<?php

namespace App\Models\Consume;

use CodeIgniter\Model;

class ConsumeDataModel1 extends Model
{

    private $config;
    private $newdb;

    function __construct()
    {
        parent::__construct();
    }

    public function dbConfig($ip, $user, $pass, $dbname, $port)
    {
        $myconfig = array(
            'dsn' => '',
            'hostname' => $ip,
            'username' => $user,
            'password' => $pass,
            'database' => $dbname,
            'dbdriver' => 'postgre',
            'dbprefix' => '',
            'pconnect' => FALSE,
            'db_debug' => TRUE,
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci',
            'swap_pre' => '',
            'encrypt' => FALSE,
            'compress' => FALSE,
            'stricton' => FALSE,
            'failover' => array(),
            'save_queries' => TRUE,
            'port' => $port
        );
        return $myconfig;
    }

    function get_db_dsn($state_code)
    {

        $builder = $this->db->table('tbl_efling_api_users rc');

        $builder->SELECT("rc.*");
        $builder->WHERE('rc.state_code', $state_code);
        $query = $builder->get();
        $db_dsn = $query->getResult();
        if ($query->getNumRows() >= 1) {
            $this->config = $this->dbConfig($db_dsn[0]->instance_db_ip, $db_dsn[0]->instance_db_user, $db_dsn[0]->instance_db_pass, $db_dsn[0]->instance_db_name, $db_dsn[0]->instance_db_port);
        } else {
            return 'error';
        }
    }
    
    function get_cases_parties($time_stamp = NULL)
    {

        $efiling_for_type_id = 3;
        $efiling_for_id = 1;

        $time_stamp = urldecode($time_stamp);
        if ($time_stamp != 'NULL') {
            $time_stamp = " AND cs.activated_on > '" . $time_stamp . "' :: timestamp without time zone ";
        } else {
            $time_stamp = '';
        }

        $sql = "SELECT en.efiling_no,
                 en.efiling_year,
                 cs.activated_on created_on,
                 ec.p_r_type,
                 ec.m_a_type,
                 ec.party_no,
                 ec.party_name,
                 ec.relation,
                 ec.relative_name,
                 ec.party_age,
                 ec.gender,
                 ec.party_dob,
                 ec.legal_heir,
                 ec.parent_id,
                 ec.party_id,
                 ec.is_org,
                 ec.org_type,
                 ec.org_state_id,
                 ec.org_state_name,
                 ec.org_state_not_in_list,
                 ec.org_dept_id,
                 ec.org_dept_name,
                 ec.org_dept_not_in_list,
                 ec.org_post_id,
                 ec.org_post_name,
                 ec.org_post_not_in_list,
                 ec.address,
                 ec.city,
                 ec.district_id,
                 ec.state_id,
                 ec.pincode,
                 ec.mobile_num,
                 ec.email_id

                 FROM efil.tbl_efiling_nums en
                 JOIN efil.tbl_case_parties ec on en.registration_id = ec.registration_id
                 JOIN efil.tbl_efiling_num_status cs on en.registration_id = cs.registration_id
                 JOIN (SELECT registration_id, deactivated_on from efil.tbl_efiling_num_status cs1 where cs1.stage_id = 1) cs1 on en.registration_id = cs1.registration_id
                 WHERE en.efiling_for_type_id = $efiling_for_type_id and en.efiling_for_id = $efiling_for_id and cs.is_active = 'TRUE' and cs.stage_id = " . Draft_Stage . $time_stamp . "

                 ORDER BY cs.activated_on, ec.party_no";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $parties_result = $query->getResultArray();
            return $parties_result;
        } else {
            return null;
        }
    }
    
    function get_cases_details($time_stamp = NULL)
    {

        $efiling_for_type_id = 3;
        $efiling_for_id = 1;

        $time_stamp = urldecode($time_stamp);
        if ($time_stamp != 'NULL') {
            $time_stamp = " AND cs.activated_on > '" . $time_stamp . "' :: timestamp without time zone ";
        } else {
            $time_stamp = '';
        }

        $sql = "SELECT en.efiling_no, 
                en.efiling_year,
                cs.activated_on created_on,
                cd.sc_case_type_id,
                cd.sc_sp_case_type_id,                
                cd.subject_cat,                
                cd.keywords,
                cd.question_of_law,
                cd.grounds,
                cd.grounds_interim,
                cd.main_prayer,
                cd.interim_relief,
                cd.jail_petition_date
                      
                FROM efil.tbl_efiling_nums en
                JOIN efil.tbl_case_details cd on en.registration_id = cd.registration_id
                JOIN efil.tbl_efiling_num_status cs on en.registration_id = cs.registration_id
                JOIN (SELECT registration_id, deactivated_on from efil.tbl_efiling_num_status cs1 where cs1.stage_id = 1) cs1 on en.registration_id = cs1.registration_id
                WHERE en.efiling_for_type_id = $efiling_for_type_id and en.efiling_for_id = $efiling_for_id and cs.is_active = 'TRUE' and cs.stage_id = " . Draft_Stage . $time_stamp . "

                ORDER BY cs.activated_on";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $parties_result = $query->getResultArray();
            return $parties_result;
        } else {
            return null;
        }
    }

    function get_cases_act_sections($time_stamp = NULL)
    {

        $efiling_for_type_id = 3;
        $efiling_for_id = 1;

        $time_stamp = urldecode($time_stamp);
        if ($time_stamp != 'NULL') {
            $time_stamp = " AND cs.activated_on > '" . $time_stamp . "' :: timestamp without time zone ";
        } else {
            $time_stamp = '';
        }

        $sql = "SELECT en.efiling_no, 
                en.efiling_year,
                cs.activated_on created_on,
                act.act_id,
                act.act_section
                
                FROM efil.tbl_efiling_nums en
                JOIN efil.tbl_act_sections act on en.registration_id = act.registration_id
                JOIN efil.tbl_efiling_num_status cs on en.registration_id = cs.registration_id
                JOIN (SELECT registration_id, deactivated_on from efil.tbl_efiling_num_status cs1 where cs1.stage_id = 1) cs1 on en.registration_id = cs1.registration_id
                WHERE en.efiling_for_type_id = $efiling_for_type_id and en.efiling_for_id = $efiling_for_id and cs.is_active = 'TRUE' and cs.stage_id = " . Draft_Stage . $time_stamp . "

                ORDER BY cs.activated_on";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $parties_result = $query->getResultArray();
            return $parties_result;
        } else {
            return null;
        }
    }

    function get_cases_lower_case_details($time_stamp = NULL)
    {

        $efiling_for_type_id = 3;
        $efiling_for_id = 1;

        $time_stamp = urldecode($time_stamp);
        if ($time_stamp != 'NULL') {
            $time_stamp = " AND cs.activated_on > '" . $time_stamp . "' :: timestamp without time zone ";
        } else {
            $time_stamp = '';
        }

        $sql = "SELECT en.efiling_no, 
                en.efiling_year,
                cs.activated_on created_on,
                lc.court_type,
                lc.state_id,
                lc.district_id,
                lc.agency_code,
                lc.case_type_id,
                lc.case_num,
                lc.case_year,
                lc.cnr_num,
                lc.impugned_order_date,
                lc.is_judgment_challenged,
                lc.judgment_type
                
                FROM efil.tbl_efiling_nums en
                JOIN efil.tbl_lower_court_details lc on en.registration_id = lc.registration_id
                JOIN efil.tbl_efiling_num_status cs on en.registration_id = cs.registration_id
                JOIN (SELECT registration_id, deactivated_on from efil.tbl_efiling_num_status cs1 where cs1.stage_id = 1) cs1 on en.registration_id = cs1.registration_id
                WHERE en.efiling_for_type_id = $efiling_for_type_id and en.efiling_for_id = $efiling_for_id and cs.is_active = 'TRUE' and cs.stage_id = " . Draft_Stage . $time_stamp . "

                ORDER BY cs.activated_on";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $parties_result = $query->getResultArray();
            return $parties_result;
        } else {
            return null;
        }
    }

    function get_cases_advocates($time_stamp = NULL)
    {

        $efiling_for_type_id = 3;
        $efiling_for_id = 1;

        $time_stamp = urldecode($time_stamp);
        if ($time_stamp != 'NULL') {
            $time_stamp = " AND cs.activated_on > '" . $time_stamp . "' :: timestamp without time zone ";
        } else {
            $time_stamp = '';
        }

        $sql = "SELECT en.efiling_no, 
                en.efiling_year,
                cs.activated_on created_on,
                adv.adv_bar_id,
                adv.m_a_adv_type,
                adv.for_p_r_a,
                adv.for_party_no
                
                FROM efil.tbl_efiling_nums en
                JOIN efil.tbl_case_advocates adv on en.registration_id = adv.registration_id
                JOIN efil.tbl_efiling_num_status cs on en.registration_id = cs.registration_id
                JOIN (SELECT registration_id, deactivated_on from efil.tbl_efiling_num_status cs1 where cs1.stage_id = 1) cs1 on en.registration_id = cs1.registration_id
                WHERE en.efiling_for_type_id = $efiling_for_type_id and en.efiling_for_id = $efiling_for_id and cs.is_active = 'TRUE' and cs.stage_id = " . Draft_Stage . $time_stamp . "

                ORDER BY cs.activated_on, adv.id";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $parties_result = $query->getResultArray();
            return $parties_result;
        } else {
            return null;
        }
    }

    function get_case_docs_details($time_stamp = NULL)
    {

        $efiling_for_type_id = 3;
        $efiling_for_id = 1;

        $time_stamp = urldecode($time_stamp);
        if ($time_stamp != 'NULL') {
            $time_stamp = " AND cs.activated_on > '" . $time_stamp . "' :: timestamp without time zone ";
        } else {
            $time_stamp = '';
        }

        $sql = "(SELECT 
                en.efiling_no,
                en.efiling_year,
                cs.activated_on created_on,
                ed.index_no,
                ed.doc_id,                
                ed.doc_type_id, 
                ed.sub_doc_type_id,
                ed.file_name,
                ed.doc_title,      
                ed.file_path,
                ed.file_size,                
                ed.page_no,
                ed.no_of_copies,
                ed.doc_hashed_value,
                ed.refiled_new refiled,                             
                ed.uploaded_on,
                ed.is_deleted
                FROM efil.tbl_efiling_nums en
                JOIN efil.tbl_efiled_docs ed on en.registration_id = ed.registration_id
                JOIN efil.tbl_efiling_num_status cs on en.registration_id = cs.registration_id
                WHERE  cs.is_active = 'TRUE' AND cs.stage_id IN (" . Draft_Stage . "," . E_Filed_Stage . "," . Document_E_Filed . "," . IA_E_Filed . ")
                AND ed.is_active = 'TRUE' AND ed.efiled_type_id IN (" . E_FILING_TYPE_NEW_CASE . "," . E_FILING_TYPE_MISC_DOCS . "," . E_FILING_TYPE_IA . ")
                ORDER BY cs.activated_on, ed.index_no, ed.uploaded_on DESC )";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $docs_res = $query->getResultArray();
            return $docs_res;
        } else {
            return NULL;
        }
    }

    function view_docs($doc_id)
    {
        $builder = $this->db->table('efil.tbl_efiled_docs ed');
        $builder->SELECT('ed.file_name,ed.file_path');
        $builder->WHERE('ed.doc_id', $doc_id);
        $query = $builder->get();

        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }
    
}