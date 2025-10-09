<?php
namespace App\Models\Search;

use CodeIgniter\Model;
class Search_model extends Model {

    function __construct() {
        parent::__construct();
    }

    public function get_search_results_user($created_by, $search) {

    $search = trim($search);
    $session = session();
    $user_type_id = $session->get('login')['ref_m_usertype_id'];
    $condition = '';

    if (!empty($search)) {
        $base_condition = "(cs.is_active = TRUE AND %s ='$created_by' AND (en.efiling_no ILIKE '%" . $search . "%' 
                            OR cast(en.efiling_year as char) ILIKE '%" . $search . "%'
                            OR cd.cause_title ILIKE '%" . $search . "%'
                            OR cd.sc_diary_num ILIKE '%" . $search . "%'
                            OR cd.sc_display_num ILIKE '%" . $search . "%'
                            OR sc.cause_title ILIKE '%" . $search . "%'
                            OR misc_ia.diary_no ILIKE '%" . $search . "%'
                            OR sc.reg_no_display ILIKE '%" . $search . "%'))";

        if ($user_type_id == USER_DEPARTMENT || $user_type_id == USER_CLERK) {
            $condition = sprintf($base_condition, 'en.sub_created_by');
        } else {
            $condition = sprintf($base_condition, 'en.created_by');
        }
    }

    $builder = $this->db->table('efil.tbl_efiling_nums as en');
    
    $builder->select([
        'en.efiling_for_type_id', 'en.efiling_for_id', 'en.registration_id', 'en.efiling_no', 'en.efiling_year', 'en.ref_m_efiled_type_id',
        'en.created_by', 'et.efiling_type', 'cs.stage_id', 'cs.activated_on', 'ds.user_stage_name',
        'cd.sc_diary_num', 'cd.sc_diary_year', 'cd.sc_display_num', 'cd.cause_title as ecase_cause_title',
        'misc_ia.diary_no', 'misc_ia.diary_year', 'sc.reg_no_display', 'sc.cause_title'
    ]);
    
    $builder->join('efil.tbl_efiling_num_status as cs', 'en.registration_id=cs.registration_id', 'left');
    $builder->join('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id', 'left');
    $builder->join('efil.tbl_case_details as cd', 'en.registration_id = cd.registration_id', 'left');
    $builder->join('efil.tbl_misc_docs_ia as misc_ia', 'en.registration_id = misc_ia.registration_id', 'left');
    $builder->join('efil.tbl_sci_cases as sc', 'misc_ia.diary_no = sc.diary_no AND misc_ia.diary_year = sc.diary_year', 'left');
    $builder->join('efil.m_tbl_dashboard_stages as ds', 'cs.stage_id = ds.stage_id', 'left');
    $builder->join('efil.tbl_users as users', 'users.id = en.created_by', 'left');
    
    $builder->where('en.is_active', true);
    
    if ($user_type_id == USER_CLERK) {
        $builder->where('en.sub_created_by', $created_by);
    } else {
        $builder->where('en.created_by', $created_by);
    }

    if (!empty($condition)) {
        $builder->where($condition, null, false);
    }
    
    $builder->orderBy('cs.activated_on', 'ASC');
    
    $query = $builder->get();
    
    return $query->getResult();
    }

    // START :Function used to find search results from search box for admin

    public function get_search_results_admin($search) {
    
    $search = trim($search);
    $session = session();
    $admin_for_id = $session->get('login')['admin_for_id'];
    $admin_for_type_id = $session->get('login')['admin_for_type_id'];
    $condition = '';

    if (!empty($search)) {
        $condition = "(cs.is_active = TRUE AND cs.updated_by IS NULL AND (en.efiling_no ILIKE '%$search%' 
                      OR cast(en.efiling_year as char) ILIKE '%$search%' 
                      OR cd.cause_title ILIKE '%$search%' 
                      OR cd.sc_diary_num ILIKE '%$search%' 
                      OR cd.sc_display_num ILIKE '%$search%' 
                      OR misc_ia.diary_no ILIKE '%$search%' 
                      OR sc.reg_no_display ILIKE '%$search%' 
                      OR sc.cause_title ILIKE '%$search%'))";
    }

    
    $builder = $this->db->table('efil.tbl_efiling_nums as en');

    $builder->select([
        'en.efiling_for_type_id', 'en.efiling_for_id', 'en.registration_id', 'en.efiling_no', 'en.efiling_year', 'en.ref_m_efiled_type_id',
        'et.efiling_type', 'cs.stage_id', 'cs.activated_on', 'ds.admin_stage_name',
        'cd.sc_diary_num', 'cd.sc_diary_year', 'cd.sc_display_num', 'cd.cause_title as ecase_cause_title',
        'misc_ia.diary_no', 'misc_ia.diary_year', 'sc.reg_no_display', 'sc.cause_title',
        'users.id as admin_user_id', 'users.userid as admin_user_name', 'users.ref_m_usertype_id', "concat(users.first_name,' ',users.last_name) as admin_name"
    ]);

    $builder->join('efil.tbl_efiling_num_status as cs', 'en.registration_id=cs.registration_id', 'left');
    $builder->join('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id', 'left');
    $builder->join('efil.tbl_case_details as cd', 'en.registration_id = cd.registration_id', 'left');
    $builder->join('efil.tbl_misc_docs_ia as misc_ia', 'en.registration_id = misc_ia.registration_id', 'left');
    $builder->join('efil.tbl_sci_cases as sc', 'misc_ia.diary_no = sc.diary_no AND misc_ia.diary_year = sc.diary_year', 'left');
    $builder->join('efil.m_tbl_dashboard_stages as ds', 'cs.stage_id = ds.stage_id', 'left');
    $builder->join('efil.tbl_users as users', 'users.id=en.allocated_to', 'left');

    $builder->whereNotIn('cs.stage_id', [Draft_Stage]);
    $builder->where('en.is_active', TRUE);
    $builder->where('en.efiling_for_id', $admin_for_id);
    $builder->where('en.efiling_for_type_id', $admin_for_type_id);

    if (!empty($condition)) {
        $builder->where($condition, null, false);
    }

    $builder->orderBy('cs.activated_on', 'ASC');

    $query = $builder->get();

    return $query->getResult();
    }

    // END :Function used to find search results from search box

    function get_reason_list() {
        
        $builder = $this->db->table('efil.m_tbl_allocation_reason');

        $builder->select("reason");
        $builder->where('is_active', TRUE);
        $builder->orderBy('reason', 'ASC');
    
        $query = $builder->get();
    
        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return false;
        }
    }

}
