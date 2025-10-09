<?php
namespace App\Models\RegistrarActionDashboard;

use CodeIgniter\Model;
class RegistrarActionDashboardModel extends Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    public function get_mentioning_count() {
        $builder = $this->db->table('efil.tbl_mentioning_requests tmr');
        // START : Function used to count total efiled nums stage wise on admin dashboard
        $builder->SELECT('sum(case when tmr.approval_status is null OR tmr.approval_status = \'\' then 1 else 0 end) as pending_requests,
        sum(case when ((tmr.approval_status is null OR tmr.approval_status = \'\') AND (tmr.request_type=\'M\')) then 1 else 0 end) as pending_requests_urgency_letter,
        sum(case when ((tmr.approval_status is null OR tmr.approval_status = \'\') AND (tmr.request_type=\'O\')) then 1 else 0 end) as pending_requests_oral,
        sum(case when tmr.approval_status=\'a\' then 1 else 0 end) as approved_requests,
        sum(case when ((tmr.approval_status=\'a\') AND (tmr.request_type=\'M\')) then 1 else 0 end) as approved_requests_urgemcy_letter,
        sum(case when ((tmr.approval_status=\'a\') AND (tmr.request_type=\'O\')) then 1 else 0 end) as approved_requests_oral,
        sum(case when tmr.approval_status=\'r\' then 1 else 0 end) as rejected_requests,
        sum(case when ((tmr.approval_status=\'r\') AND (tmr.request_type=\'M\')) then 1 else 0 end) as rejected_requests_urgency_letter,
        sum(case when ((tmr.approval_status=\'r\') AND (tmr.request_type=\'O\')) then 1 else 0 end) as rejected_requests_oral,
        sum(case when tmr.approval_status=\'w\' then 1 else 0 end) as approval_awaited_requests,
        sum(case when ((tmr.approval_status=\'w\' ) AND (tmr.request_type=\'M\')) then 1 else 0 end) as approval_awaited_requests_urgency_letter,
        sum(case when ((tmr.approval_status=\'w\' ) AND (tmr.request_type=\'O\')) then 1 else 0 end) as approval_awaited_requests_urgency_oral,
        sum(case when tmr.request_type=\'M\' then 1 else 0 end) as all_urgency_letter,
        sum(case when tmr.request_type=\'O\' then 1 else 0 end) as all_oral,
        count(1) as all');
        $builder->where('tmr.is_deleted',false);
        $query = $builder->get(); //echo $this->db->last_query();die;
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    public function get_mentioning_details($status='P',$requestType=null) {
        $builder = $this->db->table('efil.tbl_mentioning_requests tmr');
        $builder->SELECT('tu.first_name,concat(tcs.diary_no ,tcs.diary_year) as diaryId,tcs.diary_no ,tcs.diary_year, tcs.reg_no_display , tcs.cause_title,j.jname,tmr.*');
        $builder->where('tmr.is_deleted',false);
        $builder->JOIN('efil.tbl_sci_cases tcs', 'tmr.tbl_sci_cases_id =tcs.id', 'INNER');
        $builder->JOIN('icmis.judge j', 'tmr.mentioned_before_judge=j.jcode', 'LEFT');
        $builder->JOIN('efil.tbl_users tu', 'tmr.mentioned_by=tu.id', 'LEFT');
        // new mentioning
        if(isset($status) && !empty($status) &&  $status =='P' && isset($requestType) && !empty($requestType) && $requestType == 'M' ){
            $builder->where('tmr.approval_status is null',NULL,false);
            $builder->where('tmr.request_type',$requestType);
        } else if(isset($status) && !empty($status) &&  $status =='P' && isset($requestType) && !empty($requestType) && $requestType == 'O' ) {
            $builder->where('tmr.approval_status is null',NULL,false);
            $builder->where('tmr.request_type',$requestType);
        } else if(isset($status) && !empty($status) &&  $status =='P' ) {
            $builder->where('tmr.approval_status is null',NULL,false);
        }
        // approval awaited
        else  if(isset($status) && !empty($status) &&  $status =='w' && isset($requestType) && !empty($requestType) && $requestType == 'M' ){
            $builder->where('tmr.approval_status',$status);
            $builder->where('tmr.request_type',$requestType);
        } else if(isset($status) && !empty($status) &&  $status =='w' && isset($requestType) && !empty($requestType) && $requestType == 'O' ) {
            $builder->where('tmr.approval_status',$status);
            $builder->where('tmr.request_type',$requestType);
        } else if(isset($status) && !empty($status) &&  $status =='w' ) {
            $builder->where('tmr.approval_status',$status);
        }
        // approved
        else  if(isset($status) && !empty($status) &&  $status =='a' && isset($requestType) && !empty($requestType) && $requestType == 'M' ) {
            $builder->where('tmr.approval_status',$status);
            $builder->where('tmr.request_type',$requestType);
        } else if(isset($status) && !empty($status) &&  $status =='a' && isset($requestType) && !empty($requestType) && $requestType == 'O' ) {
            $builder->where('tmr.approval_status',$status);
            $builder->where('tmr.request_type',$requestType);
        } else if(isset($status) && !empty($status) &&  $status =='a' ) {
            $builder->where('tmr.approval_status',$status);
        }
        //rejected
        else  if(isset($status) && !empty($status) &&  $status =='r' && isset($requestType) && !empty($requestType) && $requestType == 'M' ) {
            $builder->where('tmr.approval_status',$status);
            $builder->where('tmr.request_type',$requestType);
        } else if(isset($status) && !empty($status) &&  $status =='r' && isset($requestType) && !empty($requestType) && $requestType == 'O' ) {
            $builder->where('tmr.approval_status',$status);
            $builder->where('tmr.request_type',$requestType);
        } else if(isset($status) && !empty($status) &&  $status =='r' ) {
            $builder->where('tmr.approval_status',$status);
        }
        //all
        else  if($status == 0 && isset($requestType) && !empty($requestType) && $requestType == 'M' ) {
            $builder->where('tmr.request_type',$requestType);
        } else if($status == 0 && isset($requestType) && !empty($requestType) && $requestType == 'O' ) {
            $builder->where('tmr.request_type',$requestType);
        }
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    public function doSaveAction($id, $actionType, $approvedDate = "", $approvalAwaitedRemarks = "") {
        $session = session();
        $userId = $session->get('login')['id'];
        $currentDateTime = date("Y-m-d H:i:s");
        if ($actionType == "a") {
            $data = [
                'approval_status' => $actionType,
                'approved_for_date' => $approvedDate,
                'action_taken_by' => $userId,
                'action_taken_on' => $currentDateTime,
                'updated_on' => $currentDateTime
            ];
        } elseif ($actionType == "w") {
            $data = [
                'approval_status' => $actionType,
                'action_taken_by' => $userId,
                'approval_awaited_remarks' => $approvalAwaitedRemarks,
                'action_taken_on' => $currentDateTime,
                'updated_on' => $currentDateTime
            ];
        } elseif ($actionType == "r") {
            $data = [
                'approval_status' => $actionType,
                'action_taken_by' => $userId,
                'action_taken_on' => $currentDateTime,
                'updated_on' => $currentDateTime
            ];
        }
        $builder = $this->db->table('efil.tbl_mentioning_requests');
        $builder->where('id', $id);
        $builder->set($data);
        $builder->update();
        return $this->db->affectedRows();
    }

    // Function used to count total efiled nums stage wise on user dashboard
    public function get_efilied_nums_stage_wise_count() {
        // START: Function used to count total efiled nums stage wise on admin dashboard 
        $session = session();
        $created_by = $session->get('login')['id'];
        $admin_for_type_id = $session->get('login')['admin_for_type_id'];
        $admin_for_id = $session->get('login')['admin_for_id'];
        $stage_ids = [
            // Add the stage IDs that need to be checked here
            LODGING_STAGE, DELETE_AND_LODGING_STAGE
        ];
        $builder = $this->db->table('efil.tbl_efiling_nums as en');
        $builder->select('
            COUNT(CASE WHEN stage_id = ' . New_Filing_Stage . ' THEN 1 END) as total_new_efiling,
            COUNT(CASE WHEN stage_id = ' . DEFICIT_COURT_FEE . ' THEN 1 END) as deficit_crt_fee,
            COUNT(CASE WHEN stage_id = ' . Initial_Defected_Stage . ' THEN 1 END) as total_not_accepted,
            COUNT(CASE WHEN stage_id = ' . Transfer_to_CIS_Stage . ' THEN 1 END) as total_available_for_cis,
            COUNT(CASE WHEN stage_id = ' . Get_From_CIS_Stage . ' THEN 1 END) as total_get_from_cis,
            COUNT(CASE WHEN stage_id = ' . Initial_Defects_Cured_Stage . ' OR stage_id = ' . DEFICIT_COURT_FEE_PAID . ' THEN 1 END) as total_refiled_cases,
            COUNT(CASE WHEN stage_id = ' . Transfer_to_IB_Stage . ' THEN 1 END) as total_transfer_to_efiling_sec,
            COUNT(CASE WHEN stage_id = ' . I_B_Approval_Pending_Admin_Stage . ' THEN 1 END) as total_pending_scrutiny,
            COUNT(CASE WHEN stage_id = ' . I_B_Defected_Stage . ' THEN 1 END) as total_waiting_defect_cured,
            COUNT(CASE WHEN stage_id = ' . I_B_Rejected_Stage . ' OR stage_id = ' . E_REJECTED_STAGE . ' THEN 1 END) as total_rejected,
            COUNT(CASE WHEN stage_id = ' . I_B_Defects_Cured_Stage . ' THEN 1 END) as total_defect_cured,
            COUNT(CASE WHEN stage_id = ' . LODGING_STAGE . ' OR stage_id = ' . DELETE_AND_LODGING_STAGE . ' THEN 1 END) as total_lodged_cases,
            COUNT(CASE WHEN (stage_id = ' . E_Filed_Stage . ' AND en.ref_m_efiled_type_id = ' . E_FILING_TYPE_NEW_CASE . ') OR (stage_id = ' . CDE_ACCEPTED_STAGE . ' AND en.ref_m_efiled_type_id = ' . E_FILING_TYPE_CDE . ') THEN 1 END) as total_efiled_cases,
            COUNT(CASE WHEN stage_id = ' . Document_E_Filed . ' AND en.ref_m_efiled_type_id = ' . E_FILING_TYPE_MISC_DOCS . ' THEN 1 END) as total_efiled_docs,
            COUNT(CASE WHEN stage_id = ' . DEFICIT_COURT_FEE_E_FILED . ' AND en.ref_m_efiled_type_id = ' . E_FILING_TYPE_DEFICIT_COURT_FEE . ' THEN 1 END) as total_efiled_deficit,
            COUNT(CASE WHEN stage_id = ' . IA_E_Filed . ' AND en.ref_m_efiled_type_id = ' . E_FILING_TYPE_IA . ' THEN 1 END) as total_efiled_ia
        ');
        $builder->join('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id', 'inner');
        $builder->where('cs.is_active', true);
        $builder->where('en.is_active', true);
        $builder->where('en.efiling_for_type_id', $admin_for_type_id);
        $builder->where('en.efiling_for_id', $admin_for_id);
        if (!in_array(LODGING_STAGE, $stage_ids) && !in_array(DELETE_AND_LODGING_STAGE, $stage_ids)) {
            $builder->where('en.allocated_to', $created_by);
        }
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    // Function used to count total efiled nums stage wise on user dashboard
    // Function used to count total submitted efiled num on user dashboard
    public function get_efilied_nums_submitted_count($stages) {
        $created_by = $this->session->userdata['login']['id'];
        $builder = $this->db->table('tbl_efiling_nums as en');
        $builder->SELECT('COUNT(en.registration_id) as count');
        $builder->JOIN('tbl_efiling_case_status as cs', 'en.registration_id=cs.registration_id');
        $builder->WHERE('cs.is_active', 'TRUE');
        $builder->whereNotIn('cs.stage_id', $stages);
        $builder->WHERE('en.created_by', $created_by);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    // Function used to count total submitted efiled num on user dashboard
    // Function used to count total efiled type wise on user dashboard
    public function getSearchResults($created_by, $search) {
        // Trim the search string
        $search = trim($search);
        // Initialize the session
        $session = session();
        $user_type_id = $session->get('login')['ref_m_usertype_id'];
        // Define the base query
        $builder = $this->db->table('tbl_efiling_nums as en');
        // Select the necessary fields
        $builder->select([
            'en.efiling_for_type_id', 'en.efiling_for_id', 'en.registration_id', 'en.efiling_no', 'en.efiling_year', 'en.ref_m_efiled_type_id',
            'en.created_by', 'et.efiling_type', 'cs.stage_id', 'cs.activated_on', 'ds.user_stage_name',
            'ec.cino', 'ec.pet_name', 'ec.res_name', 'ec.fil_case_type_name', 'ec.fil_no', 'ec.fil_year',
            'ec.reg_case_type_name', 'ec.reg_no', 'ec.reg_year',
            'ia.ia_fil_case_type_name', 'ia.ia_filno', 'ia.ia_filyear', 'ia.ia_reg_case_type_name', 'ia.ia_regno', 'ia.ia_regyear', 'ia.cino ia_cnr_num',
            'ms.case_type_name', 'ms.cnr_num', 'ms.fil_no as misc_fil_no', 'ms.fil_year as misc_fil_year', 'ms.reg_no as misc_reg_no', 'ms.reg_year as misc_reg_year',
            'ms.cause_title', 'ms.efiling_case_reg_id'
        ]);
        // Join the necessary tables
        $builder->join('tbl_efiling_case_status as cs', 'en.registration_id=cs.registration_id', 'left');
        $builder->join('m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id', 'left');
        $builder->join('tbl_efiling_civil as ec', 'en.registration_id=ec.ref_m_efiling_nums_registration_id', 'left');
        $builder->join('tbl_misc_doc_filing as ms', 'en.registration_id=ms.ref_m_efiling_nums_registration_id', 'left');
        $builder->join('eia_filing as ia', 'ia.registration_id = en.registration_id', 'left');
        $builder->join('m_tbl_dashboard_stages as ds', 'cs.stage_id = ds.stage_id', 'left');
        $builder->join('dynamic_users_table as users', 'users.id=en.created_by', 'left');
        // Set the WHERE conditions
        $builder->where('en.is_active', true);
        if ($user_type_id == USER_DEPARTMENT || $user_type_id == USER_CLERK) {
            $builder->where('en.sub_created_by', $created_by);
        } else {
            $builder->where('en.created_by', $created_by);
        }
        if (!empty($search)) {
            $condition = "
                (cs.is_active = TRUE AND en.sub_created_by = :created_by: AND en.efiling_no ILIKE '%" . $search . "%')
                OR
                (cs.is_active = TRUE AND en.sub_created_by = :created_by: AND cast(en.efiling_year as char) ILIKE '%" . $search . "%')
                OR
                (cs.is_active = TRUE AND en.sub_created_by = :created_by: AND ec.pet_name ILIKE '%" . $search . "%')
                OR
                (cs.is_active = TRUE AND en.sub_created_by = :created_by: AND ec.res_name ILIKE '%" . $search . "%')
                OR
                (cs.is_active = TRUE AND en.sub_created_by = :created_by: AND ms.cause_title ILIKE '%" . $search . "%')
            ";
            $builder->where($condition, null, false);
        }
        // Set the order
        $builder->orderBy('cs.activated_on', 'ASC');
        // Execute the query
        $query = $builder->get();
        // Return the result
        return $query->getResult();
    }

}