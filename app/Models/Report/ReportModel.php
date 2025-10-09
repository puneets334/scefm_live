<?php

namespace App\Models\Report;

use CodeIgniter\Model;

class ReportModel extends Model
{
    protected $table = 'tbl_users';
    protected $session;
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->session = \Config\Services::session();
    }

    function get_stage($registration_id = null)
    {
        if (!empty($registration_id) && $registration_id != null) {
            $builder =  $this->db->table('efil.tbl_efiling_num_status');
            $builder->SELECT('registration_id, efil.tbl_efiling_num_status.stage_id, m_tbl_dashboard_stages.user_stage_name, m_tbl_dashboard_stages.admin_stage_name,m_tbl_dashboard_stages.meant_for');
            $builder->JOIN('efil.m_tbl_dashboard_stages', 'm_tbl_dashboard_stages.stage_id =  efil.tbl_efiling_num_status.stage_id');
            $builder->WHERE('efil.tbl_efiling_num_status.registration_id', $registration_id);
            $builder->WHERE('efil.tbl_efiling_num_status.is_active', TRUE);
            $builder->orderBy('efil.tbl_efiling_num_status.status_id', 'DESC');
            $builder->LIMIT(1);
        } else {
            $builder =  $this->db->table('efil.m_tbl_dashboard_stages');
            $builder->SELECT('efil.m_tbl_dashboard_stages.stage_id,m_tbl_dashboard_stages.user_stage_name,m_tbl_dashboard_stages.admin_stage_name,m_tbl_dashboard_stages.meant_for');
            $builder->WHERE('efil.m_tbl_dashboard_stages.is_active', TRUE);
            $builder->WHERE('efil.m_tbl_dashboard_stages.portal is not null', '', false);
            //$this->db->WHERE('efil.m_tbl_dashboard_stages.stage_id !=',24,25,1);
            $builder->whereNotIn('efil.m_tbl_dashboard_stages.stage_id', array('1', '22', '24'));
            $builder->orderBy('efil.m_tbl_dashboard_stages.admin_stage_name', 'ASCE');
        }
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function getUserTypeWisePendency()
    {
        $builder =  $this->db->table('efil.tbl_efiling_nums as en');
        $builder->select(array('ds.meant_for', 'count(1) as efiled_cases'));
        $builder->join('efil.tbl_efiling_num_status as cs', '(en.registration_id = cs.registration_id) and (cs.is_active = true)');
        $builder->join('efil.m_tbl_dashboard_stages ds', '(ds.stage_id =cs.stage_id) and (ds.portal is not null)');
        $builder->where('en.is_active', 'TRUE');
        $builder->whereNotIn('cs.stage_id', array('1', '22', '24', '25'));
        $builder->whereIn('en.ref_m_efiled_type_id', array('1', '2', '4', '12'));
        $builder->whereIn('ds.meant_for', array('A', 'R'));
        $builder->groupBy('ds.meant_for');
        $query = $builder->get();
        //echo $this->db->last_query();//exit();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function getDayWisePendency()
    {
        $sql = "select meant_for,
            count(1) as total, sum(case when pending_since = 0 then 1 else 0 end) zero_day,
            sum(case when pending_since = 1 then 1 else 0 end) one_day, 
            sum(case when pending_since = 2 then 1 else 0 end) two_day,
            sum(case when pending_since = 3 then 1 else 0 end) three_day,
            sum(case when pending_since > 3 then 1 else 0 end) more_than_three_day
            from(
                    select ds.meant_for,en.efiling_no,TRUNC(EXTRACT(EPOCH FROM (current_timestamp - cs.activated_on))/86400) as pending_since
                    from efil.tbl_efiling_nums as en 
                    inner join efil.tbl_efiling_num_status as cs on en.registration_id = cs.registration_id and cs.is_active = true
                    inner join efil.m_tbl_dashboard_stages ds on ds.stage_id =cs.stage_id 
                    and ds.meant_for in ('R')
                    where cs.stage_id not in (1,22,24,25) and en.is_active is TRUE and en.ref_m_efiled_type_id in (1,2,4,12)
                ) as inquery
            group by meant_for;";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }


    function getPendencyBifurcation()
    {
        $sql = "SELECT ms.admin_stage_name,ms.meant_for,count(*) as total
            FROM efil.tbl_efiling_nums as en INNER JOIN efil.tbl_efiling_num_status as cs
            ON en.registration_id = cs.registration_id
            INNER JOIN efil.m_tbl_dashboard_stages ms on cs.stage_id=ms.stage_id and ms.meant_for='R'
            WHERE cs.is_active = 'TRUE' AND en.is_active = 'TRUE' 
            group by ms.admin_stage_name,ms.meant_for order by 1";
        $query = $this->db->query($sql);
        // echo $this->db->last_query(); exit();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function getLastUpdatedCron()
    {
        $builder =  $this->db->table('efil.tbl_cron_details');
        $sql = "select max(completed_at) as last_updated_at from efil.tbl_cron_details where cron_type='scrutiny_status'";
        $builder->select('max(completed_at) as last_updated_at');
        $builder->where('cron_type', 'scrutiny_status');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $row = $query->getRow();
            return $row->last_updated_at;
        } else {
            return 0;
        }
    }

    function get_efiling_type($efiling_type_id = null)
    {
        $builder =  $this->db->table('efil.m_tbl_efiling_type et');
        $builder->select('et.id,et.efiling_type');
        if (!empty($efiling_type_id) && $efiling_type_id != null) {
            $builder->where('et.id', $efiling_type_id);
        }
        $builder->where('et.is_active', TRUE);
        $builder->whereIn('et.id', array('1', '2', '4', '12'));
        $builder->orderBy('et.efiling_type', 'ASCE');
        $query =  $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function get_user_types($user_id = null)
    {
        $builder = $this->db->table('efil.tbl_user_types ut');
        $builder->select('ut.id,ut.user_type');
        if (!empty($user_id) && $user_id != null) {
            $builder->where('ut.id', $user_id);
        }
        $builder->where('ut.is_deleted', FALSE);
        $builder->whereIn('ut.id', array('1', '2', '12'));
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    function get_sci_case_type()
    {
        $builder = $this->db->table("icmis.casetype");
        $builder->select("casecode, casename");
        $builder->where('display', 'Y');
        $builder->orderBy("casename", "asc");
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    function get_diary_no_search($q)
    {
        $builder = $this->db->table("efil.tbl_sci_cases");
        $builder->distinct();
        $builder->select('diary_no');
        $builder->like('diary_no', $q);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            foreach ($query->getResult() as $row) {
                $row_set[] = htmlentities(stripslashes($row['diary_no']));
            }
            echo json_encode($row_set);
        }
    }

    function get_efiling_no_search($q)
    {
        $builder = $this->db->table("efil.tbl_efiling_nums en");
        $builder->distinct();
        $builder->select('en.efiling_no');
        $builder->like('en.efiling_no', $q);
        $builder->where('en.is_active', TRUE);
        $builder->where('en.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            foreach ($query->getResult() as $row) {
                $row_set[] = htmlentities(stripslashes($row['efiling_no']));
            }
            echo json_encode($row_set);
        }
    }

    //  START : Function used to get efiled types wise list of efiling nums from admin dashboard

    public function get_report_list($search_type, $ActionFiledOn, $DateRange, $stage_ids, $filing_type_id = null, $users_id = null, $diary_no = null, $diary_year = null, $efiling_no = null, $efiling_year = null, $admin_for_type_id = null, $admin_for_id = null, $status_type = 'P')
    {
        date_default_timezone_set('Asia/Kolkata');
        $subquery = $this->db->table('efil.tbl_efiled_docs')->select('registration_id')
            ->select("array_to_string(array_agg(icmis_docnum || '/' || icmis_docyear ORDER BY icmis_docnum, icmis_docyear), ',') AS icmis_docnum_year", FALSE)
            ->select('ARRAY_AGG(icmis_docnum) AS icmis_docnum', FALSE)
            ->select('ARRAY_AGG(icmis_docyear) AS icmis_docyear', FALSE)
            
            ->where('is_active', TRUE)
            ->where('is_deleted', FALSE)
            ->where('icmis_docnum !=', '')
            ->where('icmis_docyear !=', '')
            ->groupBy('registration_id')
            ->getCompiledSelect();
        $builder = $this->db->table('efil.tbl_efiling_nums as en');
        $builder->DISTINCT();
        $builder->SELECT(array('en.efiling_for_type_id', 'en.efiling_for_id', 'en.ref_m_efiled_type_id',
            'en.efiling_no', 'en.efiling_year', 'en.registration_id', 'en.allocated_on','en.create_on',
            'et.efiling_type',
            'cs.stage_id','ds.admin_stage_name','ds.portal','ds.meant_for','cs.activated_on', 'en.sub_created_by',
            'new_case_cd.cause_title ecase_cause_title', 'new_case_cd.sc_diary_num', 'new_case_cd.sc_diary_year', 'new_case_cd.sc_diary_date', 'new_case_cd.sc_display_num', 'new_case_cd.sc_reg_date',
            'sc_case.diary_no', 'sc_case.diary_year', 'sc_case.reg_no_display', 'sc_case.cause_title', 'mdia.diary_no icmis_diary_no', 'mdia.diary_year icmis_diary_year'
        , 'ec.pet_name', 'ec.res_name', 'ec.ref_m_efiling_nums_registration_id caveat_reg', 'ec.orgid', 'ec.resorgid','users.first_name', 'users.aor_code', 'users.ref_m_usertype_id' ,'ut.user_type', 'admin_users.first_name allocated_to_user','admin_users.emp_id', 'ds.user_stage_name'
            //, 'max(cs1.activated_on) as filed_on'
        , 'TRUNC(EXTRACT(EPOCH FROM (current_timestamp - cs.activated_on))/86400) as pending_since','ec.caveat_num',
            'ec.caveat_year','ec.caveat_num_date','efdia.icmis_docnum','efdia.icmis_docyear','icmis_docnum_year'
        ));
        
        $builder->JOIN('efil.tbl_efiling_num_status as cs', '(en.registration_id = cs.registration_id) and (cs.is_active = true)');
        //$this->db->JOIN('efil.tbl_efiling_num_status as cs1', 'en.registration_id = cs1.registration_id and cs1.stage_id='.New_Filing_Stage,'left');
        $builder->JOIN('efil.m_tbl_dashboard_stages ds', '(ds.stage_id =cs.stage_id) and (ds.portal is not null)');

        $builder->JOIN('public.tbl_efiling_caveat as ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id', 'left');

        $builder->JOIN('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $builder->JOIN('efil.tbl_case_details as new_case_cd', 'en.registration_id = new_case_cd.registration_id', 'left');
        $builder->JOIN('efil.tbl_misc_docs_ia as mdia', 'en.registration_id = mdia.registration_id', 'left');
        //$this->db->JOIN('efil.tbl_efiled_docs as efdia', '(en.registration_id = efdia.registration_id)  and (efdia.is_active = true)', 'left');
        $builder->JOIN("($subquery) as efdia", "(en.registration_id = efdia.registration_id)", 'left');
        $builder->JOIN('efil.tbl_sci_cases as sc_case', 'sc_case.diary_no=mdia.diary_no AND sc_case.diary_year = mdia.diary_year', 'left');


        $builder->JOIN('efil.tbl_users users', 'users.id=en.created_by', 'left');
        $builder->JOIN('efil.tbl_users admin_users', 'admin_users.id=en.allocated_to', 'left');
        $builder->JOIN('efil.tbl_user_types ut', 'ut.id=users.ref_m_usertype_id');
        //$this->db->WHERE('cs.is_active', 'TRUE');
        $builder->WHERE('en.is_active', 'TRUE');
        $builder->whereIn('en.ref_m_efiled_type_id',array('1','2','4','12'));


        if(!empty($search_type) && $search_type!=null && $search_type== 'All' && $search_type!='Diary' && $search_type!='efiling') {
            if($status_type=='C'){
                $builder->WHERE('ds.portal', 'C');
                $builder->whereIn('ds.meant_for',array('C'));
            }
            elseif ($status_type=='P'){
                $builder->whereNotIn('ds.portal',array('C'));
                $builder->whereIn('ds.meant_for',array('A','R'));
            }
            if (!empty($filing_type_id) && $filing_type_id != null && $filing_type_id != 'All') {
                $builder->WHERE('en.ref_m_efiled_type_id', $filing_type_id);
            }
            if (!empty($users_id) && $users_id != null && $users_id != 'All') {
                $builder->WHERE('ut.id', $users_id);
            }
            if (!empty($ActionFiledOn) && $ActionFiledOn != 'All' && $ActionFiledOn == 'Action') {
                $dates = explode('to', $DateRange);
                $fromDateF = $dates[0];
                $toDateF = $dates[1];
                $fromDate = date("Y-m-d H:i:s", strtotime($fromDateF));
                $toDate = date("Y-m-d H:i:s", strtotime($toDateF));
                if ($fromDate != null && $toDate != null && $status_type=='C') {
                    $builder->WHERE('en.create_on >=', $fromDate);
                    $builder->WHERE('en.create_on <=', $toDate);
                }
            } else if (!empty($ActionFiledOn) && $ActionFiledOn != 'All' && $ActionFiledOn == 'FiledOn') {
                $dates = explode('to', $DateRange);
                $fromDateF = $dates[0];
                $toDateF = $dates[1];

                $fromDate = date("Y-m-d H:i:s", strtotime($fromDateF));
                $toDate = date("Y-m-d H:i:s", strtotime($toDateF));
                if ($fromDate != null && $toDate != null && $status_type=='C') {
                    $builder->WHERE('cs.activated_on >=', $fromDate);
                    $builder->WHERE('cs.activated_on <=', $toDate);
                }
            }

            if (!empty($stage_ids[0]) && $stage_ids[0] != null && $stage_ids[0] != 'All') {
                /* if (!in_array(LODGING_STAGE, $stage_ids) && !in_array(DELETE_AND_LODGING_STAGE, $stage_ids)) {
                     $this->db->WHERE('en.allocated_to', $_SESSION['login']['id']);
                 }*/

                $builder->whereIn('cs.stage_id', (array)$stage_ids[0]);
            }

        }elseif(!empty($search_type) && $search_type!=null && $search_type=='Diary' && $search_type!='efiling' && $search_type!= 'All') {
            if(!empty($diary_no) && $diary_no!=null) {
                $builder->WHERE('new_case_cd.sc_diary_num',$diary_no);
            }
            if(!empty($diary_year) && $diary_year!=null) {
                $builder->WHERE('new_case_cd.sc_diary_year',$diary_year);
            }

        }elseif(!empty($search_type) && $search_type!=null && $search_type=='efiling' && $search_type!='Diary' && $search_type!= 'All') {
            if(!empty($efiling_no) && $efiling_no!=null) {
                $builder->LIKE('en.efiling_no',$efiling_no);
            }
            if(!empty($efiling_year) && $efiling_year!=null) {
                $builder->WHERE('en.efiling_year',$efiling_year);
            }
        }


        if (!empty($admin_for_type_id) && $admin_for_type_id != null && $admin_for_type_id != 'All') {
            $where = '(en.efiling_for_type_id=' . $admin_for_type_id . ' or en.efiling_for_type_id = ' . E_FILING_TYPE_CAVEAT . ')';
            $builder->WHERE($where);
        }
        if (!empty($admin_for_id) && $admin_for_id != null && $admin_for_id != 'All') {
            $builder->WHERE('en.efiling_for_id', $admin_for_id);
        }

        $builder->whereNotIn('cs.stage_id',array('1','22','24','25'));
        //$this->db->ORDER_BY('cs.activated_on');
        $builder->orderBy('pending_since','DESC');
        $query = $builder->get();
        //echo $this->db->last_query();//exit();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    // START : Function used to get efiled types wise list of efiling nums from without login dashboard

    public function get_efiling_search_list($ActionFiledOn, $DateRange, $stage_ids, $filing_type_id = null, $users_id = null, $diary_no = null, $efiling_no = null, $efiling_year = null, $admin_for_type_id = null, $admin_for_id = null)
    {
        date_default_timezone_set('Asia/Kolkata');
        $builder = $this->db->table('efil.tbl_efiling_nums as en');
        $builder->SELECT(array(
            'en.efiling_for_type_id', 'en.efiling_for_id', 'en.ref_m_efiled_type_id',
            'en.efiling_no', 'en.efiling_year', 'en.registration_id', 'en.allocated_on', 'en.create_on',
            'et.efiling_type',
            'cs.stage_id', 'ds.admin_stage_name', 'cs.activated_on', 'en.sub_created_by',
            'new_case_cd.cause_title ecase_cause_title', 'new_case_cd.sc_diary_num', 'new_case_cd.sc_diary_year', 'new_case_cd.sc_diary_date', 'new_case_cd.sc_display_num', 'new_case_cd.sc_reg_date',
            'sc_case.diary_no', 'sc_case.diary_year', 'sc_case.reg_no_display', 'sc_case.cause_title', 'mdia.diary_no icmis_diary_no', 'mdia.diary_year icmis_diary_year', 'ec.pet_name', 'ec.res_name', 'ec.ref_m_efiling_nums_registration_id caveat_reg', 'ec.orgid', 'ec.resorgid'
        ));
        // $builder->FROM();
        $builder->JOIN('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id');
        $builder->JOIN('efil.m_tbl_dashboard_stages ds', 'ds.stage_id =cs.stage_id');
        $builder->JOIN('public.tbl_efiling_caveat as ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id', 'left');
        $builder->JOIN('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $builder->JOIN('efil.tbl_case_details as new_case_cd', 'en.registration_id = new_case_cd.registration_id', 'left');
        $builder->JOIN('efil.tbl_misc_docs_ia as mdia', 'en.registration_id = mdia.registration_id', 'left');
        $builder->JOIN('efil.tbl_sci_cases as sc_case', 'sc_case.diary_no=mdia.diary_no AND sc_case.diary_year = mdia.diary_year', 'left');
        $builder->JOIN('efil.tbl_users users', 'users.id=en.created_by', 'left');
        $builder->JOIN('efil.tbl_user_types ut', 'ut.id=users.ref_m_usertype_id');
        $builder->WHERE('cs.is_active', 'TRUE');
        $builder->WHERE('en.is_active', 'TRUE');
        $builder->WHERE('cs.is_deleted', 'FALSE');
        // $builder->WHERE_IN('en.ref_m_efiled_type_id',array('1','2','4','12'));
        if (!empty($diary_no) && $diary_no != null) {
            // $builder->WHERE('sc_case.diary_no',$diary_no);
            $builder->LIKE('sc_case.diary_no', $diary_no);
        }
        if (!empty($efiling_no) && $efiling_no != null) {
            // $efiling_no=str_pad($efiling_no, 5, '0', STR_PAD_LEFT);
            $builder->LIKE('en.efiling_no', $efiling_no . $efiling_year);
        }
        if (!empty($efiling_year) && $efiling_year != null) {
            //$builder->LIKE('cast(en.efiling_year as char)',$efiling_year);
            $builder->where('en.efiling_year', $efiling_year);
        }
        $builder->whereNotIn('cs.stage_id', array('1', '22', '24', '25'));
        $builder->orderBy('cs.activated_on');
        $query = $builder->get();
        // echo $builder->last_query();exit();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function get_registration_id_efiling_type($efilno)
    {
        $builder = $this->db->table('efil.tbl_efiling_nums as en');
        $builder->SELECT('en.*,et.efiling_type,cs.stage_id');
        // $builder->FROM();
        $builder->JOIN('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id', 'left');
        $builder->JOIN('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $builder->WHERE('en.efiling_no', $efilno);
        $builder->orderBy('cs.activated_on');
        // $builder->LIMIT(1);
        $query = $builder->get();
        $result = $query->getResult();
        if ($query->getNumRows() >= 1) {
            return $z = $result[0];
            // return $z[0]['registration_id'];
        } else {
            return false;
        }
    }

    public function get_efilingByefiling_no($efiling_no)
    {
        date_default_timezone_set('Asia/Kolkata');
        $builder = $this->db->table('efil.tbl_efiling_nums as en');
        $builder->SELECT(array(
            'en.efiling_for_type_id', 'en.efiling_for_id', 'en.ref_m_efiled_type_id',
            'en.efiling_no', 'en.efiling_year', 'en.registration_id', 'en.allocated_on', 'en.create_on',
            'et.efiling_type',
            'cs.stage_id', 'ds.admin_stage_name', 'cs.activated_on', 'en.sub_created_by',
            'new_case_cd.cause_title ecase_cause_title', 'new_case_cd.sc_diary_num', 'new_case_cd.sc_diary_year', 'new_case_cd.sc_diary_date', 'new_case_cd.sc_display_num', 'new_case_cd.sc_reg_date',
            'sc_case.diary_no', 'sc_case.diary_year', 'sc_case.reg_no_display', 'sc_case.cause_title', 'mdia.diary_no icmis_diary_no', 'mdia.diary_year icmis_diary_year', 'ec.pet_name', 'ec.res_name', 'ec.ref_m_efiling_nums_registration_id caveat_reg', 'ec.orgid', 'ec.resorgid'
        ));
        // $builder->FROM('efil.tbl_efiling_nums as en');
        $builder->JOIN('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id');
        $builder->JOIN('efil.m_tbl_dashboard_stages ds', 'ds.stage_id =cs.stage_id');
        $builder->JOIN('public.tbl_efiling_caveat as ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id', 'left');
        $builder->JOIN('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $builder->JOIN('efil.tbl_case_details as new_case_cd', 'en.registration_id = new_case_cd.registration_id', 'left');
        $builder->JOIN('efil.tbl_misc_docs_ia as mdia', 'en.registration_id = mdia.registration_id', 'left');
        $builder->JOIN('efil.tbl_sci_cases as sc_case', 'sc_case.diary_no=mdia.diary_no AND sc_case.diary_year = mdia.diary_year', 'left');
        $builder->JOIN('efil.tbl_users users', 'users.id=en.created_by', 'left');
        $builder->JOIN('efil.tbl_user_types ut', 'ut.id=users.ref_m_usertype_id');
        $builder->WHERE('cs.is_active', 'TRUE');
        $builder->WHERE('en.is_active', 'TRUE');
        $builder->WHERE('cs.is_deleted', 'FALSE');
        $builder->WHERE('en.efiling_no', $efiling_no);
        $builder->whereNotIn('cs.stage_id', array('1', '22', '24', '25'));
        $builder->orderBy('cs.activated_on');
        $query = $builder->get();
        // echo $this->db->last_query();exit();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    public function get_read_only_report_list($stage_ids)
    {
        date_default_timezone_set('Asia/Kolkata');
        $builder = $this->db->table('efil.tbl_efiling_nums as en');
        $builder->SELECT(array(
            'en.efiling_for_type_id', 'en.efiling_for_id', 'en.ref_m_efiled_type_id',
            'en.efiling_no', 'en.efiling_year', 'en.registration_id', 'en.allocated_on', 'en.create_on',
            'et.efiling_type',
            'cs.stage_id', 'ds.admin_stage_name', 'ds.portal', 'ds.meant_for', 'cs.activated_on', 'en.sub_created_by',
            'new_case_cd.cause_title ecase_cause_title', 'new_case_cd.sc_diary_num', 'new_case_cd.sc_diary_year', 'new_case_cd.sc_diary_date', 'new_case_cd.sc_display_num', 'new_case_cd.sc_reg_date',
            'sc_case.diary_no', 'sc_case.diary_year', 'sc_case.reg_no_display', 'sc_case.cause_title', 'mdia.diary_no icmis_diary_no', 'mdia.diary_year icmis_diary_year', 'ec.pet_name', 'ec.res_name', 'ec.ref_m_efiling_nums_registration_id caveat_reg', 'ec.orgid', 'ec.resorgid', 'users.first_name', 'users.aor_code', 'users.ref_m_usertype_id', 'ut.user_type', 'admin_users.first_name allocated_to_user', 'admin_users.emp_id', 'ds.user_stage_name'
            //, 'max(cs1.activated_on) as filed_on'
            , 'TRUNC(EXTRACT(EPOCH FROM (current_timestamp - cs.activated_on))/86400) as pending_since'
        ));
        // $builder->FROM();
        $builder->JOIN('efil.tbl_efiling_num_status as cs', '(en.registration_id = cs.registration_id) and (cs.is_active = true)');
        $builder->JOIN('efil.m_tbl_dashboard_stages ds', '(ds.stage_id =cs.stage_id) and (ds.portal is not null)');
        $builder->JOIN('public.tbl_efiling_caveat as ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id', 'left');
        $builder->JOIN('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $builder->JOIN('efil.tbl_case_details as new_case_cd', 'en.registration_id = new_case_cd.registration_id', 'left');
        $builder->JOIN('efil.tbl_misc_docs_ia as mdia', 'en.registration_id = mdia.registration_id', 'left');
        $builder->JOIN('efil.tbl_sci_cases as sc_case', 'sc_case.diary_no=mdia.diary_no AND sc_case.diary_year = mdia.diary_year', 'left');
        $builder->JOIN('efil.tbl_users users', 'users.id=en.created_by', 'left');
        $builder->JOIN('efil.tbl_users admin_users', 'admin_users.id=en.allocated_to', 'left');
        $builder->JOIN('efil.tbl_user_types ut', 'ut.id=users.ref_m_usertype_id');
        $builder->WHERE('en.is_active', 'TRUE');
        $builder->whereIn('en.ref_m_efiled_type_id', array('1', '2', '4', '12'));
        if (!empty($stage_ids[0]) && $stage_ids[0] != null && $stage_ids[0] != 'All') {
            $builder->whereIn('cs.stage_id', (array)$stage_ids[0]);
        }
        $builder->whereNotIn('cs.stage_id', array('1', '22', '24', '25'));
        // $builder->ORDER_BY('cs.activated_on');
        $builder->orderBy('pending_since', 'DESC');
        $query = $builder->get();
        // echo $builder->last_query();exit();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }
}