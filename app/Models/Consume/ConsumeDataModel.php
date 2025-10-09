<?php

namespace App\Models\Consume;

use CodeIgniter\Model;

class ConsumeDataModel extends Model
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

    public function get_db_dsn($state_code)
    {
        $builder = $this->db->table('tbl_efling_api_users rc');
        $builder->select('rc.*');
        $builder->where('rc.state_code', $state_code);
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $db_dsn = $query->getRow();
            return  $this->config = $this->dbConfig($db_dsn[0]->instance_db_ip, $db_dsn[0]->instance_db_user, $db_dsn[0]->instance_db_pass, $db_dsn[0]->instance_db_name, $db_dsn[0]->instance_db_port);
        } else {
            return 'error';
        }
    }
    
    function get_efiled_cases_data($time_stamp = NULL)
    {
        $efiling_for_type_id = 3;
        $efiling_for_id = 1;
        $ref_m_efiled_type_id = E_FILING_TYPE_NEW_CASE;

        $time_stamp = urldecode($time_stamp);
        if ($time_stamp != 'NULL') {
            $time_stamp = " AND cs.activated_on > '" . $time_stamp . "' :: timestamp without time zone ";
        } else {
            $time_stamp = '';
        }

        $sql_top = 'SELECT row_to_json(t) FROM ( ';
        $sql_bottom = " FROM efil.tbl_efiling_nums en
            JOIN efil.tbl_efiling_num_status cs on en.registration_id = cs.registration_id
                 JOIN (SELECT registration_id, deactivated_on from efil.tbl_efiling_num_status cs1 where cs1.stage_id = 1) cs1 on en.registration_id = cs1.registration_id
                 WHERE en.ref_m_efiled_type_id = $ref_m_efiled_type_id AND en.efiling_for_type_id = $efiling_for_type_id and en.efiling_for_id = $efiling_for_id and cs.is_active = 'TRUE' and cs.stage_id = " . Transfer_to_CIS_Stage . $time_stamp . "
                 ORDER BY cs.activated_on ) t";
        $top_sql_to_all = " SELECT ( SELECT array_to_json(array_agg(row_to_json(d))) FROM ( ";


        $sql_case_details = $top_sql_to_all . " SELECT en.efiling_no, en.efiling_year,en.security_code,en.ifcde,
                cs.activated_on created_on,en.created_by,
                cd.sc_case_type_id, cd.sc_sp_case_type_id, cd.subject_cat, cd.keywords, cd.question_of_law, cd.grounds,
                cd.grounds_interim, cd.main_prayer, cd.interim_relief, cd.jail_petition_date                      
                FROM efil.tbl_case_details cd WHERE en.registration_id = cd.registration_id ) d
                 ) as case_details ";

        $sql_case_parties = ", ( " . $top_sql_to_all . " SELECT en.efiling_no, en.efiling_year,
                 cs.activated_on created_on,
                 ec.p_r_type, ec.m_a_type, ec.party_no, ec.party_name, ec.relation, ec.relative_name, ec.party_age, ec.gender,
                 ec.party_dob, ec.have_legal_heir legal_heir, ec.parent_id, ec.party_id, ec.is_org, ec.party_type org_type, ec.org_state_id, ec.org_state_name,
                 ec.org_state_not_in_list, ec.org_dept_id, ec.org_dept_name, ec.org_dept_not_in_list, ec.org_post_id,
                 ec.org_post_name, ec.org_post_not_in_list, ec.address, ec.city, ec.district_id, ec.state_id,
                 ec.pincode, ec.mobile_num, ec.email_id
                 FROM efil.tbl_case_parties ec 
                 WHERE ec.registration_id = en.registration_id
                 ORDER BY ec.party_no) d
                 ) as case_parties ) ";

        $sql_act_sections = ", ( " . $top_sql_to_all . " SELECT en.efiling_no, en.efiling_year,
                cs.activated_on created_on,
                act.act_id,act.act_section                
                FROM efil.tbl_act_sections act WHERE en.registration_id = act.registration_id ) d
                 ) as case_act_sections ) ";

        $sql_case_lower_case_details = ", ( " . $top_sql_to_all . " SELECT en.efiling_no, en.efiling_year,
                cs.activated_on created_on,
                lc.court_type, 
                case when lc.court_type='1' then ihc.cmis_state_id else lc.state_id end as state_id , 
                case when lc.court_type='1' then null else lc.state_name end as state_name , 
                case when lc.court_type='1' then ihc.district_no else lc.district_id end as district_id , 
                case when lc.court_type='1' then district_name else lc.district_name end as district_name , 
                case when lc.court_type='1' then ihc.id else lc.agency_code end as agency_code ,                
                lc.estab_id, lc.estab_name, lc.estab_code,
                lc.case_type_id, lc.casetype_name, lc.case_num, lc.case_year, lc.cnr_num,
		lc.pet_name, lc.res_name,
                lc.impugned_order_date impugned_order_date, lc.is_judgment_challenged, lc.judgment_type, lc.case_status
                FROM efil.tbl_lower_court_details lc 
                LEFT join efil.m_tbl_high_courts thc on thc.hc_code =lc.estab_code   
                LEFT join icmis.icmis_high_courts ihc on thc.hc_code=ihc.est_ben_cd
                WHERE en.registration_id = lc.registration_id ) d
                 ) as case_lower_case_details ) ";

        $sql_case_advocate_details = ", ( " . $top_sql_to_all . " SELECT en.efiling_no, en.efiling_year,
                cs.activated_on created_on, 
                adv.adv_bar_id, adv.adv_code, adv.m_a_adv_type, adv.for_p_r_a, adv.for_party_no                
                FROM efil.tbl_case_advocates adv WHERE en.registration_id = adv.registration_id
                ORDER BY adv.id ) d
                 ) as case_advocate_details ) ";

        $sql_efiled_docs = ", ( " . $top_sql_to_all . " SELECT en.efiling_no, en.efiling_year,
                cs.activated_on created_on,
                adv.adv_bar_id, adv.adv_code,
                cd.sc_diary_num,
                ed.index_no, ed.doc_id, ed.pspdfkit_document_id ,ed.doc_type_id, ed.sub_doc_type_id,
                ed.file_name, ed.doc_title,     
                ed.file_size,                
                ed.page_no, ed.no_of_copies, ed.doc_hashed_value,
                ed.refiled_new refiled,                             
                ed.uploaded_on, ed.is_deleted
                FROM efil.tbl_efiled_docs ed
                LEFT JOIN efil.tbl_case_advocates adv ON adv.registration_id = ed.registration_id and adv.m_a_adv_type = 'M'
                LEFT JOIN efil.tbl_case_details cd ON cd.registration_id = ed.registration_id
                WHERE en.registration_id = ed.registration_id
                ORDER BY ed.index_no, ed.uploaded_on ) d
                 ) as case_docs_details ) ";

        $sql_court_fee = ", ( " . $top_sql_to_all . " SELECT en.efiling_no, en.efiling_year,
                cs.activated_on created_on,
                null sc_diary_num,
                cfp.user_declared_court_fee court_fee, cfp.uploaded_pages, cfp.per_page_charges, cfp.printing_total, 
                cfp.order_no, cfp.order_date, cfp.received_amt, cfp.transaction_num, cfp.transaction_date, 
                cfp.pgsptxnid, cfp.shcilpmtref, cfp.pg_response, cfp.pg_track_response                
                FROM efil.tbl_court_fee_payment cfp WHERE en.registration_id = cfp.registration_id AND cfp.is_deleted IS FALSE ) d
                 ) as court_fee ) ";



        $sql = $sql_top . $sql_case_details . $sql_case_parties . $sql_act_sections . $sql_case_lower_case_details . $sql_case_advocate_details . $sql_efiled_docs . $sql_court_fee . $sql_bottom;
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $parties_result = $query->getResultArray();
            return $parties_result;
        } else {
            return null;
        }
    }

    function get_efiled_misc_doc_ia_data($ref_m_efiled_type_id, $time_stamp = NULL)
    {
        $efiling_for_type_id = 3;
        $efiling_for_id = 1;

        $time_stamp = urldecode($time_stamp);
        if ($time_stamp != 'NULL') {
            $time_stamp = " AND cs.activated_on > '" . $time_stamp . "' :: timestamp without time zone ";
        } else {
            $time_stamp = '';
        }

        $sql_top = 'SELECT row_to_json(t) FROM ( ';
        $sql_bottom = " FROM efil.tbl_efiling_nums en
            JOIN efil.tbl_efiling_num_status cs on en.registration_id = cs.registration_id
                 JOIN (SELECT registration_id, deactivated_on from efil.tbl_efiling_num_status cs1 where cs1.stage_id = 1) cs1 on en.registration_id = cs1.registration_id
                 WHERE en.ref_m_efiled_type_id = $ref_m_efiled_type_id AND en.efiling_for_type_id = $efiling_for_type_id and en.efiling_for_id = $efiling_for_id and cs.is_active = 'TRUE' and cs.stage_id = " . Transfer_to_CIS_Stage . $time_stamp . "
                 ORDER BY cs.activated_on ) t";
        $top_sql_to_all = " SELECT ( SELECT array_to_json(array_agg(row_to_json(d))) FROM ( ";


        $sql_case_details = $top_sql_to_all . " SELECT en.efiling_no, en.efiling_year,
                cs.activated_on created_on,
                concat(cd.diary_no,cd.diary_year) diary_num
                FROM efil.tbl_misc_docs_ia cd WHERE en.registration_id = cd.registration_id ) d
                 ) as case_details ";



        $sql_filing_parties = ", ( " . $top_sql_to_all . " SELECT en.efiling_no, en.efiling_year,
                 cs.activated_on created_on,
                 misc_ia.adv_bar_id, misc_ia.adv_code, appearing.partytype, misc_ia.filing_for_parties                 
                 FROM efil.tbl_appearing_for appearing 
                 JOIN efil.tbl_misc_docs_ia misc_ia ON misc_ia.diary_no = appearing.diary_num AND misc_ia.diary_year = appearing.diary_year 
                 WHERE misc_ia.registration_id = en.registration_id ) d
                 ) as filing_for_parties ) ";


        $sql_case_advocate_details = ", ( " . $top_sql_to_all . " SELECT en.efiling_no, en.efiling_year,
                cs.activated_on created_on, 
                concat(appearing.diary_num,appearing.diary_year) diary_num,                
                misc_ia.adv_bar_id, misc_ia.adv_code, null m_a_adv_type, null for_p_r_a, 
                appearing.partytype, appearing.appearing_for for_party_no
                FROM efil.tbl_appearing_for appearing 
                JOIN efil.tbl_misc_docs_ia misc_ia ON misc_ia.diary_no = appearing.diary_num AND misc_ia.diary_year = appearing.diary_year 
                WHERE en.registration_id = misc_ia.registration_id) d
                 ) as case_advocate_details ) ";

        $sql_efiled_docs = $top_sql_to_all . " SELECT en.efiling_no, en.efiling_year,
                cs.activated_on created_on,
                misc_ia.adv_bar_id, misc_ia.adv_code,
                concat(appearing.diary_num,appearing.diary_year) sc_diary_num,
                appearing.partytype, misc_ia.filing_for_parties,
                ed.index_no, ed.doc_id, ed.pspdfkit_document_id ,ed.doc_type_id, ed.sub_doc_type_id,
                ed.file_name, ed.doc_title,     
                ed.file_size,                
                ed.page_no, ed.no_of_copies, ed.doc_hashed_value,
                ed.refiled_new refiled,                             
                ed.uploaded_on, ed.is_deleted,misc_ia.p_r_type,misc_ia.intervenor_name
                FROM efil.tbl_efiled_docs ed
                JOIN efil.tbl_misc_docs_ia misc_ia ON misc_ia.registration_id = ed.registration_id
                LEFT JOIN efil.tbl_appearing_for appearing ON misc_ia.diary_no = appearing.diary_num AND misc_ia.diary_year = appearing.diary_year                 
                WHERE en.registration_id = ed.registration_id
                AND ed.is_deleted IS FALSE
                ORDER BY ed.index_no, ed.uploaded_on ) d
                 ) as case_docs_details ";

        $sql_court_fee = ", ( " . $top_sql_to_all . " SELECT en.efiling_no, en.efiling_year,
                cs.activated_on created_on,
                concat(misc_ia.diary_no,misc_ia.diary_year) sc_diary_num,
                cfp.user_declared_court_fee court_fee, cfp.uploaded_pages, cfp.per_page_charges, cfp.printing_total, 
                cfp.order_no, cfp.order_date, cfp.received_amt, cfp.transaction_num, cfp.transaction_date, 
                cfp.pgsptxnid, cfp.shcilpmtref, cfp.pg_response, cfp.pg_track_response                
                FROM efil.tbl_court_fee_payment cfp 
                JOIN efil.tbl_misc_docs_ia misc_ia ON misc_ia.registration_id = cfp.registration_id
                WHERE en.registration_id = cfp.registration_id AND cfp.is_deleted IS FALSE ) d
                 ) as court_fee ) ";
                 
        $sql = $sql_top . $sql_efiled_docs . $sql_court_fee . $sql_bottom;
        echo $sql;
        exit();
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $parties_result = $query->getResultArray();
            return $parties_result;
        } else {
            return null;
        }
    }

    function get_re_efiled_cases_data($time_stamp = NULL)
    {

        $efiling_for_type_id = 3;
        $efiling_for_id = 1;
        $ref_m_efiled_type_id = E_FILING_TYPE_NEW_CASE;

        $time_stamp = urldecode($time_stamp);
        if ($time_stamp != 'NULL') {
            $time_stamp = " AND cs.activated_on > '" . $time_stamp . "' :: timestamp without time zone ";
        } else {
            $time_stamp = '';
        }

        $sql_top = 'SELECT row_to_json(t) FROM ( ';
        $sql_bottom = " FROM efil.tbl_efiling_nums en
            JOIN efil.tbl_efiling_num_status cs on en.registration_id = cs.registration_id
                 JOIN (SELECT registration_id, deactivated_on from efil.tbl_efiling_num_status cs1 where cs1.stage_id = 1) cs1 on en.registration_id = cs1.registration_id
                 WHERE en.ref_m_efiled_type_id = $ref_m_efiled_type_id AND en.efiling_for_type_id = $efiling_for_type_id and en.efiling_for_id = $efiling_for_id and cs.is_active = 'TRUE' and cs.stage_id = " . I_B_Defects_Cured_Stage . $time_stamp . "
                 ORDER BY cs.activated_on ) t";
        $top_sql_to_all = " SELECT ( SELECT array_to_json(array_agg(row_to_json(d))) FROM ( ";

        $sql_efiled_docs = $top_sql_to_all . " SELECT en.efiling_no, en.efiling_year,
                cs.activated_on created_on,
                adv.adv_bar_id, adv.adv_code,
                concat(cd.sc_diary_num,cd.sc_diary_year) sc_diary_num,
                ed.index_no, ed.doc_id, ed.pspdfkit_document_id ,ed.doc_type_id, ed.sub_doc_type_id,
                ed.file_name, ed.doc_title,     
                ed.file_size,                
                ed.page_no, ed.no_of_copies, ed.doc_hashed_value,
                TRUE refiled,                             
                ed.uploaded_on, ed.is_deleted
                FROM efil.tbl_efiled_docs ed
                LEFT JOIN efil.tbl_case_advocates adv ON adv.registration_id = ed.registration_id and adv.m_a_adv_type = 'M'
                LEFT JOIN efil.tbl_case_details cd ON cd.registration_id = ed.registration_id
                WHERE en.registration_id = ed.registration_id
                AND ed.is_deleted IS FALSE
                AND ed.upload_stage_id = " . I_B_Defected_Stage . "
                ORDER BY ed.index_no, ed.uploaded_on ) d
                 ) as case_docs_details  ";


        $sql_court_fee = ", ( " . $top_sql_to_all . " SELECT en.efiling_no, en.efiling_year,
                cs.activated_on created_on,
                concat(cd.sc_diary_num,cd.sc_diary_year) sc_diary_num,
                cfp.user_declared_court_fee court_fee, cfp.uploaded_pages, cfp.per_page_charges, cfp.printing_total, 
                cfp.order_no, cfp.order_date, cfp.received_amt, cfp.transaction_num, cfp.transaction_date, 
                cfp.pgsptxnid, cfp.shcilpmtref, cfp.pg_response, cfp.pg_track_response                
                FROM efil.tbl_court_fee_payment cfp
                LEFT JOIN efil.tbl_case_details cd ON cd.registration_id = cfp.registration_id
                WHERE en.registration_id = cfp.registration_id AND cfp.is_deleted IS FALSE 
                AND cfp.payment_stage_id = " . I_B_Defected_Stage . ") d
                 ) as court_fee ) ";



        $sql = $sql_top . $sql_efiled_docs . $sql_court_fee . $sql_bottom;

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $parties_result = $query->getResultArray();
            return $parties_result;
        } else {
            return null;
        }
    }

    function get_re_efiled_misc_doc_ia_data($ref_m_efiled_type_id, $time_stamp = NULL)
    {

        $efiling_for_type_id = 3;
        $efiling_for_id = 1;

        $time_stamp = urldecode($time_stamp);
        if ($time_stamp != 'NULL') {
            $time_stamp = " AND cs.activated_on > '" . $time_stamp . "' :: timestamp without time zone ";
        } else {
            $time_stamp = '';
        }

        $sql_top = 'SELECT row_to_json(t) FROM ( ';
        $sql_bottom = " FROM efil.tbl_efiling_nums en
            JOIN efil.tbl_efiling_num_status cs on en.registration_id = cs.registration_id
                 JOIN (SELECT registration_id, deactivated_on from efil.tbl_efiling_num_status cs1 where cs1.stage_id = 1) cs1 on en.registration_id = cs1.registration_id
                 WHERE en.ref_m_efiled_type_id = $ref_m_efiled_type_id AND en.efiling_for_type_id = $efiling_for_type_id and en.efiling_for_id = $efiling_for_id and cs.is_active = 'TRUE' and cs.stage_id = " . I_B_Defects_Cured_Stage . $time_stamp . "
                 ORDER BY cs.activated_on ) t";
        $top_sql_to_all = " SELECT ( SELECT array_to_json(array_agg(row_to_json(d))) FROM ( ";

        $sql_efiled_docs = $top_sql_to_all . " SELECT en.efiling_no, en.efiling_year,
                cs.activated_on created_on,
                misc_ia.adv_bar_id, misc_ia.adv_code,
                concat(appearing.diary_num,appearing.diary_year) sc_diary_num,
                appearing.partytype, misc_ia.filing_for_parties,
                ed.index_no, ed.doc_id, ed.pspdfkit_document_id ,ed.doc_type_id, ed.sub_doc_type_id,
                ed.file_name, ed.doc_title,     
                ed.file_size,                
                ed.page_no, ed.no_of_copies, ed.doc_hashed_value,
                TRUE refiled,                             
                ed.uploaded_on, ed.is_deleted
                FROM efil.tbl_efiled_docs ed
                JOIN efil.tbl_misc_docs_ia misc_ia ON misc_ia.registration_id = ed.registration_id
                JOIN efil.tbl_appearing_for appearing ON misc_ia.diary_no = appearing.diary_num AND misc_ia.diary_year = appearing.diary_year                 
                WHERE en.registration_id = ed.registration_id
                AND ed.is_deleted IS FALSE AND ed.upload_stage_id = " . I_B_Defected_Stage . "
                ORDER BY ed.index_no, ed.uploaded_on ) d
                 ) as case_docs_details ";

        $sql_court_fee = ", ( " . $top_sql_to_all . " SELECT en.efiling_no, en.efiling_year,
                cs.activated_on created_on,
                concat(misc_ia.diary_no,misc_ia.diary_year) sc_diary_num,
                cfp.user_declared_court_fee court_fee, cfp.uploaded_pages, cfp.per_page_charges, cfp.printing_total, 
                cfp.order_no, cfp.order_date, cfp.received_amt, cfp.transaction_num, cfp.transaction_date, 
                cfp.pgsptxnid, cfp.shcilpmtref, cfp.pg_response, cfp.pg_track_response                
                FROM efil.tbl_court_fee_payment cfp 
                JOIN efil.tbl_misc_docs_ia misc_ia ON misc_ia.registration_id = cfp.registration_id
                WHERE en.registration_id = cfp.registration_id AND cfp.is_deleted IS FALSE
                AND cfp.payment_stage_id = " . I_B_Defected_Stage . ") d
                 ) as court_fee ) ";

        $sql = $sql_top . $sql_efiled_docs . $sql_court_fee . $sql_bottom;
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $parties_result = $query->getResult();
            return $parties_result;
        } else {
            return null;
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

    function getNewPSPDFKIT_data($diaryno)
    {
        $registration_id = $this->getRegistrationIdByDiaryNo($diaryno);
        if (empty($registration_id)) {
            return false;
        }

        $builder = $this->db->table('efil.tbl_efiled_docs ed');
        $builder->SELECT('ed.pspdfkit_document_id, ed.doc_title as title');
        $builder->where('ed.is_deleted', FALSE);
        $builder->where('ed.is_active', TRUE);
        $builder->where('ed.registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function getRegistrationIdByDiaryNo($diaryno)
    {
        $builder = $this->db->table('efil.tbl_case_details ed');
        $builder->SELECT('ed.registration_id');
        $builder->WHERE('concat(ed.sc_diary_num,ed.sc_diary_year)', $diaryno);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            $registration_id = $result[0]->registration_id;
            return $registration_id;
        } else {
            return null;
        }
    }

    function getOldPSPDFKIT_data($diaryno)
    {
        $registration_id = $this->getRegistrationIdByDiaryNo($diaryno);
        if (empty($registration_id)) {
            return false;
        }
        $maxsubmitcount = $this->getMaxSubmitCountByRegistrationId($registration_id);
        if ($maxsubmitcount < 2) {
            return false;
        }
        $builder = $this->db->table('efil.tbl_pdf_hash_tasks et');
        $builder->SELECT('et.pspdfkit_document_id, et.doc_title as title');
        $builder->WHERE('et.registration_id', $registration_id);
        $builder->WHERE('et.submit_count', $maxsubmitcount - 1);

        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function getMaxSubmitCountByPSPdfkitDocumentId($pspdfkit_document_id)
    {
        $builder = $this->db->table('efil.tbl_pdf_hash_tasks');
        $builder->SELECT('MAX(submit_count) as submit_count');
        $builder->WHERE('pspdfkit_document_id', $pspdfkit_document_id);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            $maxsubmitcount = $result[0]->submit_count;
            return (int)$maxsubmitcount;
        } else {
            return 0;
        }
    }

    function getMaxSubmitCountByRegistrationId($registration_id)
    {
        $builder = $this->db->table('efil.tbl_pdf_hash_tasks');
        $builder->SELECT('MAX(submit_count) as submit_count');
        $builder->WHERE('registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            $maxsubmitcount = $result[0]->submit_count;
            return (int)$maxsubmitcount;
        } else {
            return 0;
        }
    }

    function getNewPages($pspdfkitid)
    {
        $builder = $this->db->table('efil.tbl_pdf_hashes a');
        $builder->SELECT('a.page_no');
        $builder->JOIN('efil.tbl_pdf_hash_tasks b', 'b.id=a.pdf_hash_tasks_id', 'INNER');
        $builder->WHERE('b.mapped_pspdfkit_document_id', $pspdfkitid);
        $builder->WHERE('a.is_new_page', 1);
        $query = $builder->get();

        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function getRemovedPages($pspdfkitid)
    {
        $builder = $this->db->table('efil.tbl_pdf_hashes a');
        $builder->SELECT('a.page_no');
        $builder->JOIN('efil.tbl_pdf_hash_tasks b', 'b.id=a.pdf_hash_tasks_id', 'INNER');
        $builder->WHERE('b.pspdfkit_document_id', $pspdfkitid);
        $builder->WHERE('a.is_removed_page', 1);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_approved_mentioning_requests($time_stamp = NULL)
    {
        $time_stamp = urldecode($time_stamp);
        $builder = $this->db->table('efil.tbl_mentioning_requests tmr');
        $builder->SELECT('tu.adv_sci_bar_id,concat(tsc.diary_no,tsc.diary_year) as diary_no,tmr.*');
        $builder->where('approval_status', 'a');
        $builder->where('action_taken_on >', $time_stamp);
        $builder->where('tmr.is_deleted', false);
        $builder->FROM('efil.tbl_mentioning_requests tmr');
        $builder->JOIN('efil.tbl_users tu', 'tu.id=tmr.mentioned_by', 'LEFT');
        $builder->JOIN('efil.tbl_sci_cases tsc', 'tsc.id=tmr.tbl_sci_cases_id', 'inner');
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_certificate_requests($time_stamp = NULL)
    {
        $builder = $this->db->table('efil.tbl_certificate_request tcr');
        $builder->SELECT('tu.adv_sci_bar_id,concat(tsc.diary_no,tsc.diary_year) as diary_no,tcr.*');
        $builder->where('tcr.updated_on >', $time_stamp);
        $builder->FROM('efil.tbl_certificate_request tcr');
        $builder->JOIN('efil.tbl_users tu', 'tu.id=tcr.updated_by', 'LEFT');
        $builder->JOIN('efil.tbl_sci_cases tsc', 'tsc.id=tcr.tbl_sci_cases_id', 'inner');
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }
    
}