<?php
// if ( ! defined('BASEPATH')) exit('No direct script access allowed');

namespace App\Models\PhysicalHearing;

use CodeIgniter\Model;

class AppearanceModel extends Model
{
    // protected $DBGroup = 'physical_hearing';
    
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $db = \Config\Database::connect();
    }

    function save($table=null, $data=null):bool
    {
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        if($physical_hearing_db->insert($table, $data))
            return 1;
        else
            return 0;
    }

    function update($table=null, $data=null, $condition_array=null):bool
    {
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        // $physical_hearing_db->where($condition_array)->set($table, $data)->update();
        if($physical_hearing_db->where($condition_array)->set($table, $data)->update())
            return 1;
        else
            return 0;
        // $builder = $this->db->table($table); // Get the query builder instance for the specified table
        // // Apply the condition
        // foreach ($condition_array as $key => $value) {
        //     $builder->where($key, $value);
        // }
        // // Perform the update operation
        // if ($builder->set($data)->update()) {
        //     return 1; // Update successful
        // } else {
        //     return 0; // Update failed
        // }
    }

    function get_master($table, $condition){
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $query = $physical_hearing_db->get_where($table, $condition);
        return $query->result_array();
    }
    function dbInsert($data,$tablename)
    {
        $output = false;
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        if(isset($data) && !empty($data) && isset($tablename) && !empty($tablename)){
            $physical_hearing_db->insert($tablename, $data);
            $output =  $physical_hearing_db->insert_id();
        }
        return $output;

    }

    function InsertLog($tableFrom,$TableTo,$diary_no,$next_dt,$roster_id, $court_no,$advocate_id)
    {
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $sql = "insert into physical_hearing_advocate_vc_consent_log (diary_no, next_dt, roster_id,  updated_on, is_deleted, updated_by, updated_from_ip, is_fixed, mainhead, consent, advocate_id, created_at, court_no, consent_for_diary_nos, case_count) (select diary_no, next_dt, roster_id,  updated_on, is_deleted, updated_by, updated_from_ip, is_fixed, mainhead, consent, advocate_id, created_at, court_no, consent_for_diary_nos, case_count from physical_hearing_advocate_vc_consent where diary_no=? and next_dt=? and roster_id=? and court_no=? and advocate_id=?)";
        $physical_hearing_db->query($sql, array($diary_no,$next_dt,$roster_id, $court_no, $advocate_id));
        //echo $physical_hearing_db->last_query();exit();
        $insert_id = $physical_hearing_db->insert_id();
        return $insert_id;
    }


    function getCasesForConsent($aor_id){
        $sql="SELECT phcr.id,phcr.diary_no,m.reg_no_display,m.pet_name,m.res_name,phcr.consent,case when phcr.consent='N' then true else false end as if_changeable
            FROM physical_hearing_consent_required phcr inner join advocate a on phcr.diary_no=a.diary_no
            inner join main m on phcr.diary_no=m.diary_no
            where phcr.is_deleted='f' and a.advocate_id=?;";
        $query = $this->db->query($sql, array($aor_id));
        //echo $this->db->last_query();exit();
        return $query->getResultArray();
    }

    function get_advocate_last_updated_consent($diary_no,$next_dt,$roster_id,$advocate_id, $court_no)
    {
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $sql="SELECT diary_no,next_dt, roster_id, updated_on, consent, advocate_id,court_no,'A' as source
            FROM physical_hearing_advocate_vc_consent phac
            where phac.is_deleted='f' and phac.diary_no=? and phac.next_dt=? and phac.roster_id=? and advocate_id=? and court_no=?";
        $query = $physical_hearing_db->query($sql, array($diary_no,$next_dt,$roster_id,$advocate_id, $court_no));
        //echo $physical_hearing_db->last_query().'<br>';
        return $query->result_array();
    }


    function getCaseDetailsByid($id){
        $sql="SELECT phcr.id,phcr.diary_no,phcr.conn_key,phcr.consent,
            case when phcr.consent='N' then true else false end as if_changeable
            FROM physical_hearing_consent_required phcr         
            where phcr.is_deleted=? and phcr.id=?";
        $query = $this->db->query($sql, array('f',$id));
        return $query->getResultArray();
    }


    function getAORConsentDetails($diary_no,$next_dt,$roster_id,$advocateId, $court_no){
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $physical_hearing_db->select('diary_no, next_dt, roster_id, updated_on, is_deleted, updated_by, updated_from_ip,  mainhead, consent, advocate_id, created_at, court_no');
        $physical_hearing_db->from('physical_hearing_advocate_vc_consent');
        $physical_hearing_db->where('diary_no',$diary_no);
        $physical_hearing_db->where('next_dt',$next_dt);
        $physical_hearing_db->where('roster_id',$roster_id);
        $physical_hearing_db->where('advocate_id',$advocateId);
        $physical_hearing_db->where('court_no',$court_no);
        $query=$physical_hearing_db->get();
        //echo $physical_hearing_db->last_query();
        return $query->result_array();
    }


    function getAdvocateVCConsentSummary($advocate_id, $next_dt, $court_no=null){
        $next_dt=str_replace(array('\''), '',  (implode($next_dt)));
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $sql = "select sum(case_count) as vc_count
from physical_hearing_advocate_vc_consent
where next_dt=? and court_no = ? and advocate_id=? 
and is_deleted='f' and consent='V'";
        $query = $physical_hearing_db->query($sql, array($next_dt,$court_no,$advocate_id));
        //echo $physical_hearing_db->last_query();
        return $query->result_array();
    }


    function getTentativeAttendeeListForMail($list_no,$list_year,$list_date=null,$daily_list_matters)
    {
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $physical_hearing_db->select('.ad.id,ad.next_dt,ad.court_no,ad.diary_no, ad.case_number,ad.list_number,ad.list_year,
                                    ref_attendee_type_id,rat.description,rat.is_seat_allocated,ad.name,ad.email_id,
                                    ad.mobile, ad.created_on,ad.created_by_advocate_id,ad.secure_gate_visit_id,ad.secure_gate_visitor_passes');
        $physical_hearing_db->from('attendee_details ad');
        $physical_hearing_db->JOIN('ref_attendee_type rat', 'ad.ref_attendee_type_id = rat.id', 'left');
        $physical_hearing_db->where('ad.list_number',$list_no);
        $physical_hearing_db->where('ad.list_year',$list_year);
        $physical_hearing_db->where('ad.display','Y');
        # $physical_hearing_db->group_by("mobile,email_id,court_no,list_number,list_year");
        $physical_hearing_db->order_by("ad.court_no,diary_no");

        if(!empty($list_date))
            $physical_hearing_db->where('ad.next_dt',$list_date);

        $physical_hearing_db->where_in('ad.diary_no', $daily_list_matters);

        $query=$physical_hearing_db->get();

        //echo $physical_hearing_db->last_query(); exit();
        return $query->result_array();

    }

    function copy_attendee($diary_no, $bar_id, $new_list_no, $new_list_yr, $new_court_no){

        $sql = "insert into physical_hearing.attendee_details(diary_no, case_number, list_number, list_year, ref_attendee_type_id, name, email_id, mobile, display, created_by_advocate_id, court_no) 
    (select * from(
    select ad.diary_no,  ad.case_number, $new_list_no, $new_list_yr, ad.ref_attendee_type_id,ad.name, ad.email_id,
    ad.mobile, 'Y' as display, ad.created_by_advocate_id, $new_court_no
    from physical_hearing.attendee_details ad join 
    (SELECT list_year, list_number, next_dt, created_by_advocate_id, diary_no
    FROM physical_hearing.attendee_details
    where diary_no = $diary_no and created_by_advocate_id=$bar_id and display='Y'
    AND next_dt<='".date('Y-m-d')."'
    order by list_year desc, list_number desc, next_dt desc limit 1)last_list on ad.list_year=last_list.list_year and 
    ad.list_number=last_list.list_number and ad.created_by_advocate_id=last_list.created_by_advocate_id 
    and ad.diary_no=last_list.diary_no
    where ad.diary_no = $diary_no and ad.created_by_advocate_id=$bar_id and ad.display='Y')attendee_data)";
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    function getAorCode($advocate_id)
    {
        $builder = $this->db->table('bar b');
        $builder->where('bar_id',$advocate_id);
        $builder->where('if_aor','Y');
        $builder->where('if_sen','N');
        $builder->where('isdead','N');
        $query = $builder->get();
        return $query->getResultArray();
    }


    function getFirstNMDWorkingDayOfWeek()
    {
        $sql="select working_date from sc_working_days where is_nmd=1 and is_holiday=0 and display='Y' and
              working_date>=(SELECT case when DAYOFWEEK(curdate())<=4 then DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY) 
              else date(curdate() - interval weekday(curdate()) day + interval 1 week) end) order by working_date asc limit 1";
        $query = $this->db->query($sql);
        // echo $this->db->last_query();//exit(0);

        return $query->getResultArray();

    }

    function available_court_list($listing_days)
    {
        $listing_days=implode(',',$listing_days);
        $sql="select  distinct case when courtno>=60 then courtno-60  when courtno>=30 then courtno-30 else courtno end as court_no
                from heardt h 
                left join cl_printed p on p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'
                 inner join roster r on h.roster_id=r.id and r.display='Y' 
                 inner join roster_bench rb on r.bench_id=rb.id 
                 inner join master_bench mb on rb.bench_id=mb.id inner join advocate a on h.diary_no=a.diary_no and a.display='Y'
                where p.id is not null and h.next_dt in ($listing_days) order by 1";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();exit(0);

        return $query->getResultArray();
    }

    function freezed_court_list($listing_days){
        $listing_days=implode(',',$listing_days);
        $sql="select  distinct case when courtno>=60 then courtno-60 when courtno>=30 then courtno-30 else courtno end as court_no
                from heardt h 
                left join cl_printed p on p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'
                 inner join roster r on h.roster_id=r.id and r.display='Y' 
                 inner join roster_bench rb on r.bench_id=rb.id 
                 inner join master_bench mb on rb.bench_id=mb.id inner join advocate a on h.diary_no=a.diary_no and a.display='Y'
                where p.id is not null and h.next_dt in ($listing_days) order by 1";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();exit(0);

        return $query->getResultArray();
    }


    function getListedAdvocateMatters($advocateId,$listingDate,$court=null){

        $listingDate=implode(',',$listingDate);
        $where_condition=(!empty($court))?'courtno in (?,?,?)':'1=1';
        $sql="select next_dt, (case when courtno>=60 then courtno-60 when courtno>=30 then courtno-30 else courtno end) as court_no_display, count(1) as total_cases, courtno
from(
select h.*,mb.board_type_mb,r.courtno 
from 
(select 
h.diary_no, m.conn_key,CASE
                  WHEN ( m.diary_no = m.conn_key
                          OR m.conn_key = ''
                          OR m.conn_key IS NULL
                          OR m.conn_key = '0' ) THEN m.diary_no
                  ELSE m.conn_key
                END as main_case_diary_no,case when (m.diary_no = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') then 'M' else 'C' end as main_connected,  h.next_dt, h.mainhead, h.brd_slno, h.clno, h.roster_id, h.judges, h.main_supp_flag, m.reg_no_display  ,h.board_type
,m.active_casetype_id,Concat(Ifnull(m.reg_no_display, ''), ' @ ', Concat(
                Left(m.diary_no, Length(m.diary_no) - 4), '/',
                Substring(m.diary_no, -4))) AS
                case_no,
                Concat(m.pet_name, ' Vs. ', m.res_name)
                AS cause_title from main m 
inner join heardt h on m.diary_no = h.diary_no 
where 
(h.next_dt >=curdate() and h.next_dt in ($listingDate)) and h.main_supp_flag in (1,2) 
union 
select 
 h.diary_no, m.conn_key,CASE
                  WHEN ( m.diary_no = m.conn_key
                          OR m.conn_key = ''
                          OR m.conn_key IS NULL
                          OR m.conn_key = '0' ) THEN m.diary_no
                  ELSE m.conn_key
                END as main_case_diary_no,case when (m.diary_no = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') then 'M' else 'C' end as main_connected, 
h.next_dt, h.mainhead, h.brd_slno, h.clno, h.roster_id, h.judges, h.main_supp_flag, m.reg_no_display ,h.board_type
, m.active_casetype_id,Concat(Ifnull(m.reg_no_display, ''), ' @ ', Concat(
                Left(m.diary_no, Length(m.diary_no) - 4), '/',
                Substring(m.diary_no, -4))) AS
                case_no,
                Concat(m.pet_name, ' Vs. ', m.res_name)
                AS cause_title from main m 
inner join last_heardt h on m.diary_no = h.diary_no 
where (h.next_dt >=curdate() and h.next_dt in ($listingDate))
and h.main_supp_flag in (1,2) 
and (h.bench_flag = '' or h.bench_flag is null) 
) h
left join cl_printed p on p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'
 inner join roster r on h.roster_id=r.id and r.display='Y' 
 inner join roster_bench rb on r.bench_id=rb.id 
 inner join master_bench mb on rb.bench_id=mb.id inner join advocate a on h.diary_no=a.diary_no and a.display='Y'
where p.id is not null and a.advocate_id=? and $where_condition
group by diary_no, roster_id 
)n1
group by next_dt, court_no_display order by next_dt, court_no_display;";
        $condition=(!empty($court))?array($advocateId, $court,($court+30),($court+60)):array($advocateId);

        $query = $this->db->query($sql,$condition);
        // echo $this->db->last_query();exit(0);

        return $query->getResultArray();

    }

    function getFutureListedMatters($advocateId,$listingDate,$court=null){

        $listingDate=implode(',',$listingDate);
        $where_condition=(!empty($court))?'courtno in (?,?,?)':'1=1';

        $sql="select distinct main_case_diary_no as diary_no, group_concat(concat(case_no,' (',CAST(main_connected AS CHAR CHARACTER SET utf8) ), ')') as case_no,
cause_title,n1.courtno as court_no, count(1) as case_count,n1.next_dt,n1.roster_id, n1.main_connected,n1.reg_no_display as main_case_reg_no, group_concat(n1.diary_no) as consent_diaries,
(case when courtno>=60 then courtno-60 when courtno>=30 then courtno-30 else courtno end) as court_no_display
from(
select h.*,mb.board_type_mb,r.courtno 
from 
(select 
h.diary_no, m.conn_key,CASE
                  WHEN ( m.diary_no = m.conn_key
                          OR m.conn_key = ''
                          OR m.conn_key IS NULL
                          OR m.conn_key = '0' ) THEN m.diary_no
                  ELSE m.conn_key
                END as main_case_diary_no,case when (m.diary_no = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') then 'M' else 'C' end as main_connected,  h.next_dt, h.mainhead, h.brd_slno, h.clno, h.roster_id, h.judges, h.main_supp_flag, m.reg_no_display  ,h.board_type
,m.active_casetype_id,Concat(Ifnull(m.reg_no_display, ''), ' @ ', Concat(
                Left(m.diary_no, Length(m.diary_no) - 4), '/',
                Substring(m.diary_no, -4))) AS
                case_no,
                Concat(m.pet_name, ' Vs. ', m.res_name)
                AS cause_title from main m 
inner join heardt h on m.diary_no = h.diary_no 
where 
(h.next_dt >=curdate() and h.next_dt in ($listingDate)) and h.main_supp_flag in (1,2) 
union 
select 
 h.diary_no, m.conn_key,CASE
                  WHEN ( m.diary_no = m.conn_key
                          OR m.conn_key = ''
                          OR m.conn_key IS NULL
                          OR m.conn_key = '0' ) THEN m.diary_no
                  ELSE m.conn_key
                END as main_case_diary_no,case when (m.diary_no = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') then 'M' else 'C' end as main_connected, 
h.next_dt, h.mainhead, h.brd_slno, h.clno, h.roster_id, h.judges, h.main_supp_flag, m.reg_no_display ,h.board_type
, m.active_casetype_id,Concat(Ifnull(m.reg_no_display, ''), ' @ ', Concat(
                Left(m.diary_no, Length(m.diary_no) - 4), '/',
                Substring(m.diary_no, -4))) AS
                case_no,
                Concat(m.pet_name, ' Vs. ', m.res_name)
                AS cause_title from main m 
inner join last_heardt h on m.diary_no = h.diary_no 
where (h.next_dt >=curdate() and h.next_dt in ($listingDate))
and h.main_supp_flag in (1,2) 
and (h.bench_flag = '' or h.bench_flag is null) 
) h
left join cl_printed p on p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'
 inner join roster r on h.roster_id=r.id and r.display='Y' 
 inner join roster_bench rb on r.bench_id=rb.id 
 inner join master_bench mb on rb.bench_id=mb.id inner join advocate a on h.diary_no=a.diary_no and a.display='Y'
where p.id is not null and a.advocate_id=? and $where_condition
group by diary_no, roster_id 
)n1
group by main_case_diary_no
                ";
        $condition=(!empty($court))?array($advocateId, $court,($court+30),($court+60)):array($advocateId);
        $query = $this->db->query($sql,$condition);
        //echo $this->db->last_query();
        return $query->getResultArray();

    }

}