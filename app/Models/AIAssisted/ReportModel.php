<?php
namespace App\Models\AIAssisted;
use CodeIgniter\Model;

class ReportModel extends Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    public function get_sci_case_type() {
        $builder = $this->db->table('icmis.casetype');
        $builder->SELECT("casecode, casename");
        
        $builder->WHERE('display', 'Y');
        $builder->whereIn('casecode',array('1','2'));
        $builder->orderBy("casename", "asc");
        $query = $builder->get();
        return $query->getResult();
    }
    function get_stage($registration_id=null) {
        if(!empty($registration_id) && $registration_id!=null){
            $builder = $this->db->table('efil.tbl_efiling_num_status');
            $builder->SELECT('registration_id, efil.tbl_efiling_num_status.stage_id, m_tbl_dashboard_stages.user_stage_name, m_tbl_dashboard_stages.admin_stage_name,m_tbl_dashboard_stages.meant_for');
            
            $builder->JOIN('efil.m_tbl_dashboard_stages', 'm_tbl_dashboard_stages.stage_id =  efil.tbl_efiling_num_status.stage_id');
            $builder->WHERE('efil.tbl_efiling_num_status.registration_id', $registration_id);
            $builder->WHERE('efil.tbl_efiling_num_status.is_active', TRUE);
            $builder->orderBy('efil.tbl_efiling_num_status.status_id', 'DESC');
            $builder->LIMIT(1);
        }else{
            $builder = $this->db->table('efil.m_tbl_dashboard_stages');
            $builder->SELECT('efil.m_tbl_dashboard_stages.stage_id,m_tbl_dashboard_stages.user_stage_name,m_tbl_dashboard_stages.admin_stage_name,m_tbl_dashboard_stages.meant_for');
            $builder->WHERE('efil.m_tbl_dashboard_stages.is_active', TRUE);
            $builder->WHERE('efil.m_tbl_dashboard_stages.portal is not null', '',false);
            $builder->orderBy('efil.m_tbl_dashboard_stages.admin_stage_name', 'ASCE');
        }
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_efiling_type($efiling_type_id=null) {
        $builder = $this->db->table('efil.m_tbl_efiling_type et');
        $builder->SELECT('et.id,et.efiling_type');
        
        if(!empty($efiling_type_id) && $efiling_type_id!=null){ 
            $builder->WHERE('et.id',$efiling_type_id); 
        }
        $builder->WHERE('et.is_active', TRUE);
        $builder->whereIn('et.id',array('1'));
        $builder->orderBy('et.efiling_type', 'ASCE');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
    function get_user_types($user_id=null) {
        $builder = $this->db->table('efil.tbl_user_types ut');
        $builder->SELECT('ut.id,ut.user_type');
        
        if(!empty($user_id) && $user_id!=null){ 
            $builder->WHERE('ut.id', $user_id); 
        }
        $builder->WHERE('ut.is_deleted', FALSE);
        $builder->whereIn('ut.id',array('1','2','12'));
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }
    function get_diary_no_search($q)
    {
        $builder = $this->db->table('efil.tbl_sci_cases');
        $builder->DISTINCT();
        $builder->SELECT('diary_no');
        $builder->LIKE('diary_no', $q);
        $query= $builder->get();
        if($query->getNumRows() > 0)
        {
            foreach ($query->getResultArray() as $row)
            {
                $row_set[] = htmlentities(stripslashes($row['diary_no']));
            }
            echo json_encode($row_set);
        }
    }
    function get_efiling_no_search($q)
    {
        $builder = $this->db->table('efil.tbl_efiling_nums en');
        $builder->DISTINCT();
        $builder->SELECT('en.efiling_no');
        $builder->LIKE('en.efiling_no', $q);
        $builder->WHERE('en.is_active', TRUE);
        $builder->WHERE('en.is_deleted', FALSE);
        $query= $builder->get();
        if($query->getNumRows() > 0)
        {
            foreach ($query->getResultArray() as $row)
            {
                $row_set[] = htmlentities(stripslashes($row['efiling_no']));
            }
            echo json_encode($row_set);
        }
    }




    //  START : Function used to get AI-Assisted efiled types wise list of efiling nums from admin dashboard
    public function get_aiassisted_case_report_list($search_type,$ActionFiledOn,$DateRange,$stage_ids,$filing_type_id=null,$users_id=null,$diary_no=null,$diary_year=null,$efiling_no=null,$efiling_year=null,$admin_for_type_id=null,$admin_for_id=null,$status_type='P')
    {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs_jsonai as tupj');
        $builder->select([
            'tupj.id',
            'tupj.uploaded_on',
            'ct.casename as case_type',
            'ten.registration_id',
            'ten.efiling_no',
            "CONCAT(tcd.sc_diary_num, '/', tcd.sc_diary_year) AS diary_no",
            'tcd.sc_diary_num',
            'tcd.sc_diary_year',
            'tcd.cause_title AS ecase_cause_title',
            'tcd.sc_diary_date',
            'tcd.sc_display_num',
            'tcd.sc_reg_date',
            'sc_case.reg_no_display',
            'sc_case.cause_title',
            'sc_case.diary_no',
            'sc_case.diary_year',
            'ten.create_on',
            'mtet.efiling_type',
            'tu.aor_code',
            "CONCAT(tu.first_name, ' ', tu.last_name) AS filed_by",
            'tu.moblie_number AS mobile_number',
            'tu.emailid',
            'tens.stage_id',
            'mtds.user_stage_name',
            "CASE 
        WHEN tens.stage_id = 1 THEN 'Draft'   
        WHEN tens.stage_id = 25 THEN 'Trashed Case'
        WHEN tens.stage_id IN (2,4,5,6,7,8,9,10,12) THEN 'Case E-Filed'
        WHEN tens.stage_id IS NULL OR tens.stage_id = 0 OR CAST(tens.stage_id AS TEXT) = '' THEN 'AIAssisted'
        ELSE 'Unknown'
    END AS current_status",
            "CASE 
        WHEN tup.file_path IS NOT NULL AND tup.file_path != '' THEN CONCAT('http://10.192.105.105:91/', tup.file_path)   
        ELSE CONCAT('http://10.192.105.105:91/', tupj.file_path)
    END AS path",
            'tupj.is_active_efiling',
            'tupj.iitm_api_json',
            'tupj.iitm_api_json_defect',
            'ten.efiling_for_type_id', 'ten.efiling_for_id', 'ten.ref_m_efiled_type_id',
            'tu.ref_m_usertype_id'
        ]);
        $builder->join('icmis.casetype as ct', "tupj.sc_case_type = ct.casecode AND ct.display = 'Y'", 'inner');
        $builder->join('efil.tbl_efiling_nums as ten', "tupj.registration_id = ten.registration_id AND ten.is_active = 'TRUE' AND ten.is_deleted ='FALSE'", 'left');
        $builder->join('efil.tbl_case_details as tcd', "ten.registration_id = tcd.registration_id AND tcd.is_deleted = 'FALSE'", 'left');
        $builder->join('efil.tbl_users as tu', 'tupj.uploaded_by = tu.id', 'inner');
        $subQuery = "(SELECT DISTINCT ON (registration_id) * 
              FROM efil.tbl_uploaded_pdfs 
              WHERE is_deleted = 'FALSE' AND is_active = 'TRUE' 
              ORDER BY registration_id, uploaded_on ASC) as tup";
        $builder->join($subQuery, 'tupj.registration_id = tup.registration_id', 'left');
        $builder->join('efil.m_tbl_efiling_type as mtet', "mtet.id = ten.ref_m_efiled_type_id AND mtet.is_active ='TRUE'", 'left');
        $builder->join('efil.tbl_efiling_num_status as tens', "ten.registration_id = tens.registration_id AND tens.is_deleted ='FALSE' AND tens.is_active ='TRUE'", 'left');
        $builder->join('efil.m_tbl_dashboard_stages as mtds', "tens.stage_id = mtds.stage_id AND mtds.is_active = 'TRUE'", 'left');
        $builder->JOIN('efil.tbl_sci_cases as sc_case', 'sc_case.diary_no=tcd.sc_diary_num AND sc_case.diary_year = tcd.sc_diary_year', 'left');
        $builder->where('tu.is_active', '1');
        $builder->where('tu.is_deleted', 'FALSE');
        $builder->where('tupj.is_deleted', 'FALSE');

        if(!empty($search_type) && $search_type!=null && $search_type== 'All' && $search_type!='Diary' && $search_type!='efiling') {

            if (!empty($filing_type_id) && $filing_type_id != null && $filing_type_id != 'All') {
                $builder->WHERE('tupj.sc_case_type', $filing_type_id);
            }
            if (!empty($users_id) && $users_id != null && $users_id != 'All') {
                $builder->WHERE('tu.ref_m_usertype_id', $users_id);
            }

            if ($status_type=='C') {
                $builder->whereIn('tens.stage_id',array(2,4,5,6,7,8,9,10,12));
            }elseif ($status_type=='w') {
                $builder->whereNotIn('tens.stage_id',array(2,4,5,6,7,8,9,10,12));
                $builder->orWhere('tens.stage_id is null');
            }
           if (!empty($stage_ids[0]) && $stage_ids[0] != null && $stage_ids[0] != 'All') {
                $builder->whereIn('tens.stage_id', $stage_ids[0]);
            }
        }elseif(!empty($search_type) && $search_type!=null && $search_type=='Diary' && $search_type!='efiling' && $search_type!= 'All') {
            if(!empty($diary_no) && $diary_no!=null) {
                $builder->WHERE('tcd.sc_diary_num',$diary_no);
            }
            if(!empty($diary_year) && $diary_year!=null) {
                $builder->WHERE('tcd.sc_diary_year',$diary_year);
            }

        }elseif(!empty($search_type) && $search_type!=null && $search_type=='efiling' && $search_type!='Diary' && $search_type!= 'All') {
            if(!empty($efiling_no) && $efiling_no!=null) {
                $builder->LIKE('ten.efiling_no',$efiling_no);
            }
            if(!empty($efiling_year) && $efiling_year!=null) {
                $builder->WHERE('ten.efiling_year',$efiling_year);
            }
        }
        //$this->db->order_by('filed_by', 'ASC');
        $builder->whereNotIn('tupj.uploaded_by',array(6282));
        $builder->orderBy('tupj.uploaded_on', 'desc');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }


    }

//End Call by Ajax AIAssisted Case


}
