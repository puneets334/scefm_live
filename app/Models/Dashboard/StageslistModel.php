<?php

namespace App\Models\Dashboard;
use CodeIgniter\Model;

class StageslistModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function get_efilied_nums_stage_wise_list($stageIds, $createdBy, $notIn = 0 , $limit = null, $offset= null, $searchValue=null) {
        $searchValue = !empty($searchValue) ? strtolower($searchValue) : '';
        $builder = $this->db->table('efil.tbl_efiling_nums as en');
        $builder->select([
            'mtds.user_stage_name', 'en.efiling_for_type_id', 'en.efiling_for_id', 'en.ref_m_efiled_type_id',
            'en.efiling_no', 'en.efiling_year', 'en.registration_id', 'en.allocated_on',
            'et.efiling_type', 'cs.stage_id', 'cs.activated_on', 'en.sub_created_by',
            'new_case_cd.cause_title as ecase_cause_title', 'new_case_cd.sc_diary_num', 'new_case_cd.sc_diary_year',
            'new_case_cd.sc_diary_date', 'new_case_cd.sc_display_num', 'new_case_cd.sc_reg_date',
            'sc_case.diary_no', 'sc_case.diary_year', 'sc_case.reg_no_display', 'sc_case.cause_title',
            'ec.pet_name', 'ec.res_name', 'ec.orgid', 'ec.resorgid', 'ec.ref_m_efiling_nums_registration_id as caveat_reg',
            'users.first_name', 'users.last_name',
            'allocated_users.first_name as allocated_user_first_name', 'allocated_users.last_name as allocated_user_last_name',
            'allocated_users.id as allocated_to_user_id', 'tea.allocated_on as allocated_to_da_on',
            '(CASE WHEN en.ref_m_efiled_type_id IN (1,3,5) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'2\',\'3\']) THEN \'Basic Detail\' 
            WHEN en.ref_m_efiled_type_id IN (1,5) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'6\']) THEN \'Earlier Court\' 
            WHEN en.ref_m_efiled_type_id IN (1,5) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'7\']) THEN \'Earlier Court\' 
            WHEN en.ref_m_efiled_type_id IN (1,5) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'9\']) THEN \'Index\' 
            WHEN en.ref_m_efiled_type_id IN (1,5) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'10\']) THEN \'Payment\'
            WHEN en.ref_m_efiled_type_id IN (2,4) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'2\']) THEN \'Appearing For\' 
            WHEN en.ref_m_efiled_type_id IN (2,4) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'5\']) THEN \'Index\' 
            WHEN en.ref_m_efiled_type_id IN (2,4) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\''.NEW_CASE_COURT_FEE.'\']) THEN \'Court Fee\' 
            WHEN en.ref_m_efiled_type_id IN (2) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'7\']) THEN \'Share Document\' 
            WHEN ( (en.ref_m_efiled_type_id IN (1,5) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'13\'])) 
                    OR (en.ref_m_efiled_type_id IN (2,4) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'9\'])) 
            ) THEN \'Final Submit\' 
        END) as pendingStage',
            'ec.caveat_num', 'ec.caveat_year',
            '(SELECT CONCAT(department_name, \' <br>(\', ministry_name, \')\') FROM efil.department_filings df 
            JOIN efil.m_departments md ON md.id = df.ref_department_id 
            WHERE registration_id=en.registration_id) as dept_file',
            '(SELECT \'Entered by Clerk\' FROM efil.clerk_filings WHERE registration_id=en.registration_id) as clerk_file',
            'jsonai.registration_id as registration_id_jsonai','jsonai.id as id_jsonai'
        ]);
        $builder->join('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id');
        $builder->join('public.tbl_efiling_caveat as ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id', 'left');
        $builder->join('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $builder->join('efil.tbl_case_details as new_case_cd', 'en.registration_id = new_case_cd.registration_id', 'left');
        $builder->join('efil.tbl_misc_docs_ia as mdia', 'en.registration_id = mdia.registration_id', 'left');
        $builder->join('efil.tbl_sci_cases as sc_case', 'sc_case.diary_no=mdia.diary_no AND sc_case.diary_year = mdia.diary_year', 'left');
        $builder->join('efil.tbl_users as users', 'en.created_by=users.id', 'left');
        $builder->join('efil.m_tbl_dashboard_stages as mtds', 'cs.stage_id = mtds.stage_id ', 'left');
        $builder->join('(SELECT * FROM efil.tbl_efiling_allocation tea ORDER BY tea.allocated_on DESC LIMIT 1) as tea', 'en.registration_id = tea.registration_id', 'left');
        $builder->join('efil.tbl_users as allocated_users', 'tea.admin_id=allocated_users.id', 'left');
        $builder->join('efil.tbl_uploaded_pdfs_jsonai as jsonai', "en.registration_id = jsonai.registration_id AND jsonai.iitm_api_json is not null AND jsonai.is_deleted ='false'", 'left');
        $builder->where('cs.is_active', 'TRUE');
        $builder->where('en.is_active', 'TRUE');
        $builder->where('en.is_deleted', 'FALSE');
        $builder->whereIn('en.ref_m_efiled_type_id', [1, 2, 4, 8, 9, 12, 13]);
        if ($notIn == 0) {
            $builder->whereIn('cs.stage_id', $stageIds);
        } else {
            $builder->whereNotIn('cs.stage_id', $stageIds);
        }
        $builder->groupStart();
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
            $builder->where('en.sub_created_by', $createdBy);
        } else {
            $builder->where('en.created_by', $createdBy);
        }
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE && !in_array(4, $stageIds)) {
            $builder->orWhere('en.registration_id IN (SELECT registration_id FROM efil.department_filings WHERE aor_code::varchar=(SELECT aor_code FROM efil.tbl_users WHERE id=' . $this->db->escape($createdBy) . '))');
            $builder->orWhere('en.registration_id IN (SELECT registration_id FROM efil.clerk_filings WHERE aor_code::varchar=(SELECT aor_code FROM efil.tbl_users WHERE id=' . $this->db->escape($createdBy) . '))');
        }
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT && !in_array(4, $stageIds)) {
            $builder->orWhere('en.registration_id IN (SELECT registration_id FROM efil.department_filings WHERE ref_department_id=' . $this->db->escape($_SESSION['login']['department_id']) . ')');
        }
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_CLERK && !in_array(4, $stageIds)) {
            
        }
        $builder->groupEnd();
        if ($limit !== null) {
            $builder->limit($limit,$offset);
        }
        
        if($searchValue !== null || $searchValue != ''){
            $builder->groupStart();
            
            if(isDateString($searchValue)){
                $date = parseDate($searchValue);
                $builder->where('cs.activated_on >=', $date.' 00:00:00');
                $builder->where('cs.activated_on <=', $date.' 23:59:59');
            }else{
                $builder->where('lower(en.efiling_no)', $searchValue);
                $builder->orLike('lower(en.efiling_no)', $searchValue);
                $builder->orWhere('sc_case.diary_no', $searchValue);
                $builder->orLike('sc_case.diary_no', $searchValue);
                $builder->orWhere('sc_case.diary_year', $searchValue);
                $builder->orLike('sc_case.diary_year', $searchValue);
                $builder->orWhere('lower(mtds.user_stage_name)', $searchValue);
                $builder->orLike('lower(mtds.user_stage_name)', $searchValue);
                $builder->orWhere('lower(sc_case.reg_no_display)', $searchValue);
                $builder->orLike('lower(sc_case.reg_no_display)', $searchValue);
                $builder->orWhere('lower(sc_case.cause_title)', $searchValue);
                $builder->orLike('lower(sc_case.cause_title)', $searchValue);
                $builder->orLike('lower(et.efiling_type)', $searchValue);
            }

            $builder->groupEnd();
        }
        
        $builder->orderBy('cs.activated_on', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    public function get_day_wise_case_details($stageIds, $createdBy, $start=null, $end=null, $notIn = 0) {
        $builder = $this->db->table('efil.tbl_efiling_nums as en');
        $builder->select([
            'mtds.user_stage_name', 'en.efiling_for_type_id', 'en.efiling_for_id', 'en.ref_m_efiled_type_id',
            'en.efiling_no', 'en.efiling_year', 'en.registration_id', 'en.allocated_on',
            'et.efiling_type', 'cs.stage_id', 'cs.activated_on', 'en.sub_created_by',
            'new_case_cd.cause_title as ecase_cause_title', 'new_case_cd.sc_diary_num', 'new_case_cd.sc_diary_year',
            'new_case_cd.sc_diary_date', 'new_case_cd.sc_display_num', 'new_case_cd.sc_reg_date',
            'sc_case.diary_no', 'sc_case.diary_year', 'sc_case.reg_no_display', 'sc_case.cause_title',
            'ec.pet_name', 'ec.res_name', 'ec.orgid', 'ec.resorgid', 'ec.ref_m_efiling_nums_registration_id as caveat_reg',
            'users.first_name', 'users.last_name',
            'allocated_users.first_name as allocated_user_first_name', 'allocated_users.last_name as allocated_user_last_name',
            'allocated_users.id as allocated_to_user_id', 'tea.allocated_on as allocated_to_da_on',
            '(CASE WHEN en.ref_m_efiled_type_id IN (1,3,5) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'2\',\'3\']) THEN \'Basic Detail\' 
            WHEN en.ref_m_efiled_type_id IN (1,5) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'6\']) THEN \'Act Section\' 
            WHEN en.ref_m_efiled_type_id IN (1,5) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'7\']) THEN \'Earlier Court\' 
            WHEN en.ref_m_efiled_type_id IN (1,5) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'9\']) THEN \'Index\' 
            WHEN en.ref_m_efiled_type_id IN (1,5) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'10\']) THEN \'Payment\'
            WHEN en.ref_m_efiled_type_id IN (2,4) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'2\']) THEN \'Appearing For\' 
            WHEN en.ref_m_efiled_type_id IN (2,4) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'5\']) THEN \'Index\' 
            WHEN en.ref_m_efiled_type_id IN (2,4) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\''.NEW_CASE_COURT_FEE.'\']) THEN \'Court Fee\' 
            WHEN en.ref_m_efiled_type_id IN (2) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'7\']) THEN \'Share Document\' 
            WHEN ( (en.ref_m_efiled_type_id IN (1,5) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'13\'])) 
                    OR (en.ref_m_efiled_type_id IN (2,4) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'9\'])) 
            ) THEN \'Final Submit\' 
        END) as pendingStage',
            'ec.caveat_num', 'ec.caveat_year',
            '(SELECT CONCAT(department_name, \' <br>(\', ministry_name, \')\') FROM efil.department_filings df 
            JOIN efil.m_departments md ON md.id = df.ref_department_id 
            WHERE registration_id=en.registration_id) as dept_file',
            '(SELECT \'Entered by Clerk\' FROM efil.clerk_filings WHERE registration_id=en.registration_id) as clerk_file'
        ]);
        $builder->join('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id');
        $builder->join('public.tbl_efiling_caveat as ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id', 'left');
        $builder->join('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $builder->join('efil.tbl_case_details as new_case_cd', 'en.registration_id = new_case_cd.registration_id', 'left');
        $builder->join('efil.tbl_misc_docs_ia as mdia', 'en.registration_id = mdia.registration_id', 'left');
        $builder->join('efil.tbl_sci_cases as sc_case', 'sc_case.diary_no=mdia.diary_no AND sc_case.diary_year = mdia.diary_year', 'left');
        $builder->join('efil.tbl_users as users', 'en.created_by=users.id', 'left');
        $builder->join('efil.m_tbl_dashboard_stages as mtds', 'cs.stage_id = mtds.stage_id ', 'left');
        $builder->join('(SELECT * FROM efil.tbl_efiling_allocation tea ORDER BY tea.allocated_on DESC LIMIT 1) as tea', 'en.registration_id = tea.registration_id', 'left');
        $builder->join('efil.tbl_users as allocated_users', 'tea.admin_id=allocated_users.id', 'left');
        $builder->join('efil.tbl_uploaded_pdfs_jsonai as jsonai', "en.registration_id = jsonai.registration_id AND jsonai.iitm_api_json is not null AND jsonai.is_deleted ='false'", 'left');
        $builder->where('cs.is_active', 'TRUE');
        $builder->where('en.is_active', 'TRUE');
        $builder->where('en.is_deleted', 'FALSE');
        $builder->whereIn('en.ref_m_efiled_type_id', [1, 2, 4, 8, 9, 12, 13]);
        if(!empty($start) && !empty($end)) {
            $builder->where('cs.activated_on >=', $start);
            $builder->where('cs.activated_on <=', $end);
        }
        if ($notIn == 0) {
            $builder->whereIn('cs.stage_id', $stageIds);
        } else {
            $builder->whereNotIn('cs.stage_id', $stageIds);
        }
        $builder->groupStart();
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
            $builder->where('en.sub_created_by', $createdBy);
        } else {
            $builder->where('en.created_by', $createdBy);
        }
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE && !in_array(4, $stageIds)) {
            $builder->orWhere('en.registration_id IN (SELECT registration_id FROM efil.department_filings WHERE aor_code::varchar=(SELECT aor_code FROM efil.tbl_users WHERE id=' . $this->db->escape($createdBy) . '))');
            $builder->orWhere('en.registration_id IN (SELECT registration_id FROM efil.clerk_filings WHERE aor_code::varchar=(SELECT aor_code FROM efil.tbl_users WHERE id=' . $this->db->escape($createdBy) . '))');
        }
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT && !in_array(4, $stageIds)) {
            $builder->orWhere('en.registration_id IN (SELECT registration_id FROM efil.department_filings WHERE ref_department_id=' . $this->db->escape($_SESSION['login']['department_id']) . ')');
        }
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_CLERK && !in_array(4, $stageIds)) {
            
        }
        $builder->groupEnd();
        $builder->orderBy('cs.activated_on', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    public function get_efilied_nums_submitted_list($stages, $createdBy) {
        $builder = $this->db->table('tbl_efiling_nums as en');
        $builder->select([
            'en.efiling_for_type_id', 'en.efiling_no', 'en.ref_m_efiled_type_id', 'en.efiling_year',
            'en.registration_id', 'et.efiling_type', 'cs.stage_id', 'cs.activated_on',
            'new_case_cd.cause_title as ecase_cause_title', 'new_case_cd.sc_diary_num', 'new_case_cd.sc_diary_date',
            'new_case_cd.sc_display_num', 'new_case_cd.sc_reg_date', 'sc_case.diary_no',
            'sc_case.diary_year', 'sc_case.reg_no_display', 'sc_case.cause_title'
        ]);
        $builder->join('tbl_efiling_case_status as cs', 'en.registration_id = cs.registration_id');
        $builder->join('m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id = et.id');
        $builder->join('tbl_efiling_civil as ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id', 'left');
        $builder->join('tbl_misc_doc_filing as ms', 'ms.ref_m_efiling_nums_registration_id = en.registration_id', 'left');
        $builder->where('cs.is_active', 'TRUE');
        $builder->where('en.is_active', 'TRUE');
        $builder->whereNotIn('cs.stage_id', $stages);
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
            $builder->where('en.sub_created_by', $createdBy);
        } else {
            $builder->where('en.created_by', $createdBy);
        }
        $builder->orderBy('cs.activated_on', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

}