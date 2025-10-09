<?php

namespace App\Models\PhysicalHearing;

use CodeIgniter\Model;
use Config\Database;
use Config\Services;

class ConsentVCModel extends Model
{

    protected $DBGroup = 'default';
    protected $sci_cmis_final;
    protected $physical_hearing;

    function __construct()
    {
      parent::__construct();
      $db = \Config\Database::connect();
      $this->sci_cmis_final = Database::connect('sci_cmis_final');
      $this->physical_hearing = Database::connect('physical_hearing');
    }

    function save($table=null, $data=null):bool
    {
      $builder = $this->physical_hearing->table($table);
      if($builder->insert($data))
        return 1;
      else
        return 0;
    }

    function update($table=null, $data=null, $condition_array=null):bool
    {
      //$physical_hearing_db->where($condition_array)->set($table, $data)->update();
      $builder = $this->physical_hearing->table($table);
      if($builder->where($condition_array)->set($data)->update())
        return 1;
      else
        return 0;
    }

    function get_master($table, $condition)
    {
      $builder = $this->db->table($table);
      $query = $builder->getWhere($condition);
      return $query->getResultArray();
    }

    function dbInsert($data,$tablename)
    {
      $output = false;
      if(isset($data) && !empty($data) && isset($tablename) && !empty($tablename)){
        $builder = $this->db->table($tablename);
        $builder->insert($data);
        $output =  $this->physical_hearing->insertId();
      }
      return $output;
    }

    function InsertLog($tableFrom,$TableTo,$diary_no,$next_dt,$roster_id, $court_no,$advocate_id)
    {
      $sql = "insert into physical_hearing.physical_hearing_advocate_vc_consent_log (diary_no, next_dt, roster_id,  updated_on, is_deleted, updated_by, updated_from_ip, is_fixed, mainhead, consent, advocate_id, created_at, court_no, consent_for_diary_nos, case_count) (select diary_no, next_dt, roster_id,  updated_on, is_deleted, updated_by, updated_from_ip, is_fixed, mainhead, consent, advocate_id, created_at, court_no, consent_for_diary_nos, case_count from physical_hearing_advocate_vc_consent where diary_no=? and next_dt=? and roster_id=? and court_no=? and advocate_id=?)";
      $this->physical_hearing->query($sql, array($diary_no,$next_dt,$roster_id, $court_no, $advocate_id));
      $insert_id = $this->physical_hearing->insertID();
      return $insert_id;
    }

    function getCasesForConsent($aor_id)
    {
      // $sql="SELECT phcr.id,phcr.diary_no,m.reg_no_display,m.pet_name,m.res_name,phcr.consent,case when phcr.consent='N' then true else false end as if_changeable
      //     FROM public.physical_hearing_consent_required phcr
      //     inner join public.advocate a on phcr.diary_no=a.diary_no
      //     inner join public.main m on phcr.diary_no=m.diary_no
      //     where phcr.is_deleted='f' and a.advocate_id=?;";
      // $query = $this->sci_cmis_final->query($sql, array($aor_id));
      // return $query->getResultArray();

      $sql="SELECT phcr.id,phcr.diary_no,m.reg_no_display,m.pet_name,m.res_name,phcr.consent,case when phcr.consent='N' then true else false end as if_changeable
          FROM physical_hearing_consent_required phcr
          inner join advocate a on phcr.diary_no=a.diary_no
          inner join main m on phcr.diary_no=m.diary_no
          where phcr.is_deleted='f' and a.advocate_id=?;";
      $query = $this->sci_cmis_final->query($sql, array($aor_id));
      return $query->getResultArray();
    }

    function get_advocate_last_updated_consent($diary_no,$next_dt,$roster_id,$advocate_id, $court_no)
    {
      $sql="SELECT diary_no,next_dt, roster_id, updated_on, consent, advocate_id,court_no,'A' as source
          FROM physical_hearing.physical_hearing_advocate_vc_consent phac
          where phac.is_deleted='f' and phac.diary_no=? and phac.next_dt=? and phac.roster_id=? and advocate_id=? and court_no=?";
      $query = $this->physical_hearing->query($sql, array($diary_no,$next_dt,$roster_id,$advocate_id, $court_no));
      return $query->getResultArray();
    }

    function getCaseDetailsByid($id)
    {
      // $sql="SELECT phcr.id,phcr.diary_no,phcr.conn_key,phcr.consent,
      //     case when phcr.consent='N' then true else false end as if_changeable
      //     FROM public.physical_hearing_consent_required phcr         
      //     where phcr.is_deleted=? and phcr.id=?";
      // $query = $this->sci_cmis_final->query($sql, array('f',$id));
      // return $query->getResultArray();

      $sql="SELECT phcr.id,phcr.diary_no,phcr.conn_key,phcr.consent,
          case when phcr.consent='N' then true else false end as if_changeable
          FROM physical_hearing_consent_required phcr         
          where phcr.is_deleted=? and phcr.id=?";
      $query = $this->sci_cmis_final->query($sql, array('f',$id));
      return $query->getResultArray();
    }

    function getAORConsentDetails($diary_no,$next_dt,$roster_id,$advocateId, $court_no){
      // $this->physical_hearing->select('diary_no, next_dt, roster_id, updated_on, is_deleted, updated_by, updated_from_ip,  mainhead, consent, advocate_id, created_at, court_no');
      // $this->physical_hearing->from('physical_hearing_advocate_vc_consent');
      // $this->physical_hearing->where('diary_no',$diary_no);
      // $this->physical_hearing->where('next_dt',$next_dt);
      // $this->physical_hearing->where('roster_id',$roster_id);
      // $this->physical_hearing->where('advocate_id',$advocateId);
      // $this->physical_hearing->where('court_no',$court_no);
      // $query=$this->physical_hearing->get();
      $sql = "SELECT 
        diary_no, 
        next_dt, 
        roster_id, 
        updated_on, 
        is_deleted, 
        updated_by, 
        updated_from_ip, 
        mainhead, 
        consent, 
        advocate_id, 
        created_at, 
        court_no 
      FROM 
        physical_hearing.physical_hearing_advocate_vc_consent AS phavc 
      WHERE 
        cast(phavc.diary_no as BIGINT) = $diary_no 
        AND phavc.next_dt = DATE '".$next_dt."' 
        AND phavc.roster_id = $roster_id 
        AND phavc.advocate_id = $advocateId 
        AND phavc.court_no = $court_no;
      ";
      $query = $this->physical_hearing->query($sql);
      return $query->getResultArray();
    }

    function getAdvocateVCConsentSummary($advocate_id, $next_dt, $court_no=null){
      $next_dt=str_replace(array('\''), '',  (implode((array)$next_dt)));
      // $sql = "select sum(case_count) as vc_count
      //     from physical_hearing.physical_hearing_advocate_vc_consent
      //     where next_dt=? and court_no = ? and advocate_id=? 
      //     and is_deleted='f' and consent='V'";
      $sql = "select sum(case_count) as vc_count from physical_hearing.physical_hearing_advocate_vc_consent where next_dt= DATE '".$next_dt."' and court_no = $court_no and advocate_id=$advocate_id and is_deleted='f' and consent='V'";
      $query = $this->physical_hearing->query($sql, array($next_dt,$court_no,$advocate_id));
      return $query->getResultArray();
    }

    function getTentativeAttendeeListForMail($list_no,$list_year,$daily_list_matters,$list_date=null)
    {
      $builder = $this->physical_hearing->table('attendee_details ad');
      $builder->select('.ad.id,ad.next_dt,ad.court_no,ad.diary_no, ad.case_number,ad.list_number,ad.list_year, ref_attendee_type_id,rat.description,rat.is_seat_allocated,ad.name,ad.email_id, ad.mobile, ad.created_on,ad.created_by_advocate_id,ad.secure_gate_visit_id,ad.secure_gate_visitor_passes');
      $builder->JOIN('ref_attendee_type rat', 'ad.ref_attendee_type_id = rat.id', 'left');
      $builder->where('ad.list_number',$list_no);
      $builder->where('ad.list_year',$list_year);
      $builder->where('ad.display','Y');
      # $this->physical_hearing->group_by("mobile,email_id,court_no,list_number,list_year");
      $builder->orderBy("ad.court_no,diary_no");
      if(!empty($list_date))
          $builder->where('ad.next_dt',$list_date);
      $builder->whereIn('ad.diary_no', $daily_list_matters);
      $query=$builder->get();
      return $query->getResultArray();
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
      $builder = $this->db->table('bar b')
        ->where('bar_id',$advocate_id)
        ->where('if_aor','Y')
        ->where('if_sen','N')
        ->where('isdead','N');
      $query = $builder->get();
      return $query->getResultArray();
    }

    function getFirstNMDWorkingDayOfWeek()
    {
      $sql="select working_date from sc_working_days where is_nmd=1 and is_holiday=0 and display='Y' and
            working_date>=(SELECT case when DAYOFWEEK(curdate())<=4 then DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY) 
            else date(curdate() - interval weekday(curdate()) day + interval 1 week) end) order by working_date asc limit 1";
      $query = $this->db->query($sql);
      return $query->getResultArray();
    }

    function getNextMDWorkingDayOfWeek($show_next_misc_date_cases=null,$isListingWithinSummerVacation=null) {
      if(!empty($show_next_misc_date_cases))
          $condition=' working_date > CURRENT_DATE ';
      else
          $condition=' working_date >= CURRENT_DATE ';
      if(!empty($isListingWithinSummerVacation))
          $condition1=" 1=1 and holiday_description like '%SUMMER VACATION%' ";
      else
          $condition1=" is_nmd=0 and is_holiday=0 ";
      $sql="select working_date from sc_working_days where is_nmd=0 and is_holiday=0 and display='Y' and working_date>=(SELECT case when DAYOFWEEK(curdate())<=5 then DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY) else date(curdate() - interval weekday(curdate()) day + interval 1 week) end) AND  working_date >= date(now()) order by working_date asc limit 1";
      // $sql="select working_date from master.sc_working_days where $condition1 and display='Y' and $condition order by working_date asc limit 1";
      $query = $this->sci_cmis_final->query($sql);
      return $query->getResultArray();
    }

    function available_court_list($listing_days)
    {
		  // $listing_days=implode(',',$listing_days);
      //   $sql="select  distinct case when courtno>=60 then courtno-60  when courtno>=30 then courtno-30 else courtno end as court_no
      //       from public.heardt h
      //       left join public.cl_printed p on p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'
      //       inner join master.roster r on h.roster_id=r.id and r.display='Y'
      //       inner join master.roster_bench rb on r.bench_id=rb.id
      //       inner join master.master_bench mb on rb.bench_id=mb.id
      //       inner join public.advocate a on cast(h.diary_no as BIGINT) = cast(a.diary_no as BIGINT) and a.display='Y'
      //       where p.id is not null and h.next_dt IN (DATE '".$listing_days."') order by 1";
      //   $query = $this->sci_cmis_final->query($sql);
      //   return $query->getResultArray();

      $listing_days=implode(',',$listing_days);
      $sql="select  distinct case when courtno>=60 then courtno-60  when courtno>=30 then courtno-30 else courtno end as court_no
        from heardt h
        left join cl_printed p on p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'
        inner join roster r on h.roster_id=r.id and r.display='Y'
        inner join roster_bench rb on r.bench_id=rb.id
        inner join master_bench mb on rb.bench_id=mb.id
        inner join advocate a on cast(h.diary_no as unsigned) = cast(a.diary_no as unsigned) and a.display='Y'
        where p.id is not null and h.next_dt IN (DATE '".$listing_days."') order by 1";
      $query = $this->sci_cmis_final->query($sql);
      return $query->getResultArray();
    }

    function freezed_court_list($listing_days)
    {
      // $listing_days=implode(',',$listing_days);
      // $sql="select  distinct case when courtno>=60 then courtno-60 when courtno>=30 then courtno-30 else courtno end as court_no
      //     from public.heardt h 
      //     left join public.cl_printed p on p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'
      //     inner join master.roster r on h.roster_id=r.id and r.display='Y' 
      //     inner join master.roster_bench rb on r.bench_id=rb.id 
      //     inner join master.master_bench mb on rb.bench_id=mb.id
      //     inner join public.advocate a on cast(h.diary_no as BIGINT) = cast(a.diary_no as BIGINT) and a.display='Y'
      //     where p.id is not null and h.next_dt in (DATE '".$listing_days."') order by 1";
      // $query = $this->sci_cmis_final->query($sql);
      // return $query->getResultArray();

      $listing_days=implode(',',$listing_days);
      $sql="select  distinct case when courtno>=60 then courtno-60 when courtno>=30 then courtno-30 else courtno end as court_no
          from heardt h 
          left join cl_printed p on p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'
          inner join roster r on h.roster_id=r.id and r.display='Y' 
          inner join roster_bench rb on r.bench_id=rb.id 
          inner join master_bench mb on rb.bench_id=mb.id
          inner join advocate a on cast(h.diary_no as unsigned) = cast(a.diary_no as unsigned) and a.display='Y'
          where p.id is not null and h.next_dt in (DATE '".$listing_days."') order by 1";
      $query = $this->sci_cmis_final->query($sql);
      return $query->getResultArray();
    }

    function getListedAdvocateMatters($advocateId,$listingDate,$court=null,$requestFor=null){
      $curdate='2024-01-01';//curdate()
      $listing_date_condition="";
      if(empty($requestFor))
          $listing_date_condition=" and h.next_dt in (". implode(',',$listingDate).")";


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
  (h.next_dt >='$curdate' $listing_date_condition) and h.main_supp_flag in (1,2) 
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
  where (h.next_dt >='$curdate' $listing_date_condition)
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
  group by next_dt, court_no_display order by next_dt, court_no_display, courtno;";
      $condition=(!empty($court))?array($advocateId, $court,($court+30),($court+60)):array($advocateId);

      $query = $this->sci_cmis_final->query($sql,$condition);

      return $query->getResultArray();

  }

  function getFutureListedMatters($advocateId,$listingDate,$court=null){
      $curdate='2024-01-01';//curdate()
      $listingDate=implode(',',$listingDate);
      $where_condition=(!empty($court))?'courtno in (?,?,?)':'1=1';

      $sql="select distinct main_case_diary_no as diary_no, group_concat(concat(case_no,' (',CAST(main_connected AS CHAR CHARACTER SET utf8) ), ')') as case_no,
cause_title,n1.courtno as court_no,n1.brd_slno as item_no, count(1) as case_count,n1.next_dt,n1.roster_id, n1.main_connected,n1.reg_no_display as main_case_reg_no, group_concat(n1.diary_no) as consent_diaries,
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
(h.next_dt >='$curdate' and h.next_dt in ($listingDate)) and h.main_supp_flag in (1,2) 
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
where (h.next_dt >='$curdate' and h.next_dt in ($listingDate))
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
      $query = $this->sci_cmis_final->query($sql,$condition);
      return $query->getResultArray();

  }
}