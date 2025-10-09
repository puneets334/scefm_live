<?php

namespace App\Models\PhysicalHearing;

use CodeIgniter\Model;
use Config\Database;

class HearingModel extends Model
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

    function get_master($table, $condition){

        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $query = $physical_hearing_db->get_where($table, $condition);
        return $query->result_array();
    }

    function save($table=null, $data=null):bool
    {
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        if($physical_hearing_db->insert($table, $data))
            return $physical_hearing_db->insert_id();
        else
            return 0;
    }

    function update($table=null, $data=null, $condition_array=null):bool
    {
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        if($physical_hearing_db->where($condition_array)->set($table, $data)->update())
            return 1;
        else
            return 0;
    }

    function aorCount($diary_no){
        // $sql="select count(distinct a.advocate_id) advocate_count
        //     from public.main m
        //     left outer join conct ct on cast(m.conn_key as BIGINT) = cast(ct.conn_key as BIGINT) 
        //     inner join advocate a on cast(m.diary_no as BIGINT) = cast(a.diary_no as BIGINT) 
        //     and a.display = 'Y' 
        //     inner join master.bar b on a.advocate_id = b.bar_id
        //     where (ct.list='Y' or ct.list is null) 
        //     and b.if_aor='Y' AND b.isdead='N' and m.diary_no=?";
        // $query = $this->sci_cmis_final->query($sql, array($diary_no));
        // return $query->getResultArray();

        $sql="select count(distinct a.advocate_id) advocate_count
            from main m
            left outer join conct ct on cast(m.conn_key as unsigned) = cast(ct.conn_key as unsigned) 
            inner join advocate a on cast(m.diary_no as unsigned) = cast(a.diary_no as unsigned) 
            and a.display = 'Y' 
            inner join bar b on a.advocate_id = b.bar_id
            where (ct.list='Y' or ct.list is null) 
            and b.if_aor='Y' AND b.isdead='N' and m.diary_no=?";
        $query = $this->sci_cmis_final->query($sql, array($diary_no));
        return $query->getResultArray();
    }
    
    function getCounselEntryForToday($diary_no,$roster_id,$mobile, $court_no)
    {
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $sql="select count(id) as counsel_entry from attendee_details where display='Y' 
              and diary_no=? and roster_id=? and mobile=? and court_no=?";
        $query = $physical_hearing_db->query($sql, array($diary_no,$roster_id,$mobile, $court_no));
        return $query->result_array();

    }

    function courtCapacity($court_no=null){
        /*$physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $sql="select * from ref_court_seating_capacity where (court_number=? or alternate_court_number=?) and display='Y' ";
        $query = $physical_hearing_db->query($sql, array($court_no, $court_no));
        return $query->result_array();*/
        $court_capacity=array(array('seating_capacity'=>'20'));
        return $court_capacity;
    }

    function get_case_listed_in_daily_list($diary_no)
    {
        // $current_date = date("Y-m-d");
        // $sql = "select 
        //     a.diary_no, 
        //     a.next_dt 
        //     from 
        //     (
        //         select 
        //         diary_no, 
        //         next_dt, 
        //         clno, 
        //         roster_id, 
        //         judges, 
        //         mainhead 
        //         from 
        //         public.heardt 
        //         where 
        //         clno != 0 
        //         and clno is not null 
        //         and brd_slno != 0 
        //         and brd_slno is not null 
        //         and roster_id != 0 
        //         and roster_id is not null 
        //         and next_dt >= DATE '".$current_date."' 
        //         and diary_no = '".$diary_no."'
        //     ) a 
        //     INNER JOIN cl_printed p ON p.next_dt = a.next_dt 
        //     AND p.m_f = a.mainhead 
        //     AND p.part = a.clno 
        //     AND p.roster_id = a.roster_id 
        //     AND p.display = 'Y' 
        //     group by 
        //     diary_no, 
        //     a.next_dt";
        // $query = $this->sci_cmis_final->query($sql, array($current_date,$diary_no));
        // return $query->getResultArray();

        $current_date = date("Y-m-d");
        $sql="select a.diary_no,a.next_dt from (select  diary_no,next_dt, clno, roster_id, judges, mainhead 
            from heardt where clno!=0 and clno is not null and brd_slno!=0 and brd_slno is not null
            and roster_id!=0 and roster_id is not null 
            and next_dt >= ? and diary_no = ?) a 
            INNER JOIN cl_printed p ON p.next_dt = a.next_dt AND p.m_f = a.mainhead AND p.part = a.clno AND p.roster_id = a.roster_id AND p.display = 'Y'
            group by diary_no";
        $query = $this->sci_cmis_final->query($sql, array($current_date,$diary_no));
        // echo $this->db->last_query();exit(0);
        return $query->getResultArray();
    }

    function seatsAllocatedCount($roster_id,$diary_no, $adv_id, $court_no){
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $sql="  select sum((case when is_seat_allocated='Y' then 1 else 0 end))seat_allocated, sum((case when is_seat_allocated='N' then 1 else 0 end)) law_clerk
                from attendee_details ad
                left join ref_attendee_type rat on rat.id=ad.ref_attendee_type_id
                where diary_no=? and ad.roster_id=? and ad.display='Y' and created_by_advocate_id=?
                and rat.display='Y' and is_internal='N' and ad.court_no=?";
        $query = $physical_hearing_db->query($sql, array($diary_no,$roster_id,$adv_id,$court_no));
        //echo $this->db->last_query();exit(0);
        return $query->result_array();
    }


    function getFutureListedMatters($advocateId,$court=null){
        $where_condition=(!empty($court))?'h.courtno=?':'1=1';
        /*$this->db->select('distinct h.next_dt as listing_date,r.courtno as court_number,h.brd_slno as item_number ,concat(ifnull(m.reg_no_display,\'\'),\' @ \',
                concat(LEFT(m.diary_no,length(m.diary_no)-4), \'/\',SUBSTRING(m.diary_no,-4))
                ) as case_no,concat(m.pet_name,\' Vs. \',m.res_name) as cause_title, h.roster_id, m.diary_no,
                case when (m.diary_no = m.conn_key OR m.conn_key = \'\' OR m.conn_key IS NULL OR m.conn_key = \'0\') then \'M\' else \'C\' end as main_connected ',FALSE);
        $this->db->from('heardt h');
        $this->db->join('main m','h.diary_no=m.diary_no');
        $this->db->join('advocate a','m.diary_no=a.diary_no');
        $this->db->join('roster r','h.roster_id=r.id');
        $this->db->join('cl_printed cp','h.roster_id=cp.roster_id and h.next_dt=cp.next_dt and h.brd_slno between cp.from_brd_no and cp.to_brd_no AND h.clno=cp.part');
        if(!empty($listDate))
            $this->db->where('h.next_dt',$listDate);
        else
            $this->db->where('h.next_dt>=',"'".date('Y-m-d')."'",FALSE);

        $this->db->where('h.board_type','J');
        $this->db->where('h.main_supp_flag in (1,2)',NULL,false);
        $this->db->where('a.advocate_id',$advocateId);
        $this->db->order_by('listing_date,court_number,item_number');
        $this->db->limit(10);
        $query = $this->db->get();

        $sql = $this->db->where(array('is_active'=>'t'))->where("to_date>='".date('Y-m-d')."'")->order_by('list_year DESC, list_number DESC')->get_compiled_select('hybrid_physical_hearing_consent_freeze');

        $this->db->select('DISTINCT wl.diary_no,concat(ifnull(m.reg_no_display,\'\'),\' @ \',
                concat(LEFT(m.diary_no,length(m.diary_no)-4), \'/\',SUBSTRING(m.diary_no,-4))
                ) as case_no,concat(m.pet_name,\' Vs. \',m.res_name) as cause_title,
                hphc.list_number,hphc.list_year,hphc.court_no,
                hphc.consent, hphc.hearing_from_time, hphc.hearing_to_time,
                 h1.next_dt, h1.mainhead, h1.board_type,
                case when (m.diary_no = m.conn_key OR m.conn_key = \'\' OR m.conn_key IS NULL OR m.conn_key = \'0\') then \'M\' else \'C\' end as main_connected ',FALSE);
        $this->db->from('weekly_list wl');
        $this->db->join('hybrid_physical_hearing_consent hphc','wl.diary_no = hphc.diary_no','left');
        $this->db->join("($sql) hphcf",'hphc.list_type_id=hphcf.list_type_id AND hphc.list_number=hphcf.list_number AND hphc.list_year=hphcf.list_year and hphc.court_no=hphcf.court_no');
        $this->db->join('main m','wl.diary_no=m.diary_no','left');
        $this->db->join('heardt h1','h1.diary_no = m.diary_no','left');
        $this->db->join('conct ct','m.diary_no=ct.diary_no and ct.list="Y"','left');
        $this->db->join('advocate a','m.diary_no=a.diary_no','left');
        $this->db->where('hphcf.to_date >=',"'".date('Y-m-d')."'",FALSE);
        if(!empty($court))
            $this->db->where("hphc.court_no",trim($court));
        //$this->db->where('a.advocate_id',$advocateId);
        $this->db->order_by('hphcf.court_no');
        $query_result = $this->db->get();*/

        /*$sql="select DISTINCT h.diary_no,
               CONCAT(IFNULL(m.reg_no_display, ''),
                       ' @ ',
                       CONCAT(LEFT(m.diary_no, LENGTH(m.diary_no) - 4),
                               '/',
                               SUBSTRING(m.diary_no, - 4))) AS case_no,
               CONCAT(m.pet_name, ' Vs. ', m.res_name) AS cause_title,
                h.weekly_no as list_number,
               h.weekly_year as list_year,
               h.courtno as court_no,
               h.weekly_no as list_number,
               h.weekly_year as list_year,
               hphc.consent,
               hphc.hearing_from_time,
               hphc.hearing_to_time,
               h1.next_dt,
               h1.mainhead,
               h1.board_type,
               CASE
                   WHEN
                       (m.diary_no = m.conn_key
                           OR m.conn_key = ''
                           OR m.conn_key IS NULL
                           OR m.conn_key = '0')
                   THEN
                       'M'
                   ELSE 'C'
               END AS main_connected, h.* from
               (select wl1.* from weekly_list wl1
               inner join (
               SELECT max(weekly_no) max_weekly_no, max(weekly_year) max_weekly_year
                FROM weekly_list where (year(curdate()) = weekly_year OR (year(curdate()) + 1) = weekly_year)
               ) wl2 on wl2.max_weekly_no = wl1.weekly_no and wl2.max_weekly_year = wl1.weekly_year
               where courtno > 0) h
               inner join main m on m.diary_no = h.diary_no
               left join heardt h1 on h1.diary_no = m.diary_no
               LEFT JOIN conct ct on m.diary_no=ct.diary_no and ct.list='Y'
               left join hybrid_physical_hearing_consent hphc on hphc.diary_no = m.diary_no
               left join advocate a on m.diary_no=a.diary_no
               where m.c_status = 'P'
               and $where_condition and a.advocate_id=?
               group by m.diary_no
               ORDER BY LENGTH(h.judges_code) DESC, h.next_dt, h.item_no,
               if(h.conn_key=h.diary_no,'0000-00-00',99) ASC,
               if(ct.ent_dt is not null,ct.ent_dt,999) ASC,
               cast(SUBSTRING(m.diary_no,-4) as signed) ASC,
               cast(LEFT(m.diary_no,length(m.diary_no)-4) as signed ) ASC
               ";*/

        //group_concat(concat(case_no,'<style="color:green;">(',CAST(main_connected AS CHAR CHARACTER SET utf8) ), ')</style><br>') as case_no

        $sub_sql = $this->db->table('hybrid_physical_hearing_consent_freeze')->where(array('is_active'=>'t'))->where("to_date>='".date('Y-m-d')."'")->orderBy('list_year DESC, list_number DESC')->getCompiledSelect();

         $sql="select distinct main_case_diary_no as diary_no, group_concat(concat(case_no,' (',CAST(main_connected AS CHAR CHARACTER SET utf8) ), ')') as case_no,
Concat(mm.pet_name, ' Vs. ', mm.res_name)
                AS cause_title,zz.court_no, count(1) as case_count,zz.consent,zz.list_number, zz.list_year, zz.main_connected,mm.reg_no_display as main_case_reg_no, group_concat(zz.diary_no) as consent_diaries 
from( 
SELECT DISTINCT h.diary_no,
                Concat(Ifnull(m.reg_no_display, ''), ' @ ', Concat(
                Left(m.diary_no, Length(m.diary_no) - 4), '/',
                Substring(m.diary_no, -4))) AS
                case_no,
                Concat(m.pet_name, ' Vs. ', m.res_name)
                AS cause_title,
                h.weekly_no
                AS list_number,
                h.weekly_year
                AS list_year,
                h.courtno
                AS court_no,
                hphc.consent,
                hphc.hearing_from_time,
                hphc.hearing_to_time,
                h1.next_dt,
                h1.mainhead,
                h1.board_type,
                CASE
                  WHEN ( m.diary_no = m.conn_key
                          OR m.conn_key = ''
                          OR m.conn_key IS NULL
                          OR m.conn_key = '0' ) THEN m.diary_no
                  ELSE m.conn_key
                END as main_case_diary_no,
                CASE
                  WHEN ( m.diary_no = m.conn_key
                          OR m.conn_key = ''
                          OR m.conn_key IS NULL
                          OR m.conn_key = '0' ) THEN 'M'
                  ELSE 'C'
                END
                AS main_connected,
                 h.to_date
FROM   (select wl1.*,hphc.to_date from weekly_list wl1
                inner join (
                SELECT max(weekly_no) max_weekly_no, max(weekly_year) max_weekly_year
                 FROM weekly_list where (year(curdate()) = weekly_year OR (year(curdate()) + 1) = weekly_year)
                ) wl2 on wl2.max_weekly_no = wl1.weekly_no and wl2.max_weekly_year = wl1.weekly_year
                inner join hybrid_physical_hearing_consent_freeze hphc on wl2.max_weekly_no=hphc.list_number 
          and  wl2.max_weekly_year=hphc.list_year and hphc.is_active='t' and hphc.court_no=wl1.courtno
                where courtno > 0 and to_date>='".date('Y-m-d')."') h
                inner join main m on m.diary_no = h.diary_no
                left join heardt h1 on h1.diary_no = m.diary_no
                LEFT JOIN conct ct on m.diary_no=ct.diary_no and ct.list='Y'
                left join hybrid_physical_hearing_consent hphc on (hphc.diary_no = m.diary_no and hphc.to_dt>='".date('Y-m-d')."')
                left join advocate a on (m.diary_no=a.diary_no and a.display='Y')
                
                where m.c_status = 'P' 
                and $where_condition and a.advocate_id=?                
                group by m.diary_no
                ORDER BY LENGTH(h.judges_code) DESC, h.next_dt, h.item_no, 
                if(h.conn_key=h.diary_no,'0000-00-00',99) ASC, 
                if(ct.ent_dt is not null,ct.ent_dt,999) ASC,
                cast(SUBSTRING(m.diary_no,-4) as signed) ASC, 
                cast(LEFT(m.diary_no,length(m.diary_no)-4) as signed ) ASC )zz
left join main mm on mm.diary_no=zz.main_case_diary_no          
group by main_case_diary_no order by zz.court_no";
        $condition=(!empty($court))?array($court,$advocateId):array($advocateId);
        $query = $this->db->query($sql,$condition);

        //and hphcf.to_date >='".date('Y-m-d')."'

        // echo $this->db->last_query();
        return $query->getResultArray();


        /*if(!empty($query_result)){
            return $query_result->result_array();
        }
        else
        {
            return null;
        }*/
    }

    function getAttendeeList($diary_no,$roster_id,$aor_id, $court_no){
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $sql="select ad.id,ad.roster_id,ad.court_no,ad.item_no,ad.diary_no,ad.name,rat.description attendee_type,ad.email_id,ad.mobile,ad.display,ad.ref_attendee_type_id, ad.court_no
              from attendee_details ad left join ref_attendee_type rat on ad.ref_attendee_type_id=rat.id
              where ad.display='Y' and ad.diary_no=? and ad.roster_id=? and ad.created_by_advocate_id=?  and ad.court_no=? and ad.ref_attendee_type_id !=9 and ((ad.next_dt is null) or (ad.next_dt>='".date('Y-m-d')."'))";
        $query = $physical_hearing_db->query($sql, array($diary_no, $roster_id,$aor_id, $court_no));
       // echo $physical_hearing_db->last_query();
        return $query->result_array();
    }
    function getAttendee(){
        $physical_hearing = $this->load->database('physical_hearing',TRUE);
        $physical_hearing->select('id,description');
        $physical_hearing->from('ref_attendee_type');
        $query = $physical_hearing->get();
        return $query->result_array();
    }
    function getSelfEntry($diary_no,$roster_id,$aor_id, $court_no){
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $sql="select ad.id,ad.roster_id,ad.court_no,ad.item_no,ad.diary_no,ad.name,rat.description attendee_type,ad.email_id,ad.mobile,ad.display,ad.ref_attendee_type_id 
              from attendee_details ad left join ref_attendee_type rat on ad.ref_attendee_type_id=rat.id
              where ad.diary_no=? and ad.roster_id=? and ad.created_by_advocate_id=? and ad.court_no=? and ad.ref_attendee_type_id=?";
        $query = $physical_hearing_db->query($sql, array($diary_no,$roster_id,$aor_id,$court_no,9));
        return $query->result_array();
    }

    function checkActiveAttendeeEntry($id){
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $sql="select ad.* from attendee_details ad  where ad.id=? ";
        $query = $physical_hearing_db->query($sql, array($id));
        return $query->result_array();
    }
    function getActiveAttedeeCount($diary_no,$roster_id,$advocate_id, $court_no){
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $sql="SELECT count(*) as total_attendee FROM physical_hearing.attendee_details  where diary_no=? and roster_id=? and created_by_advocate_id=? and court_no=? and display='Y' and ref_attendee_type_id!=5";
        $query = $physical_hearing_db->query($sql, array($diary_no,$roster_id,$advocate_id, $court_no));
        return $query->result_array();
    }

    function freezed_court_list(){
        $sql = $this->db->table('hybrid_physical_hearing_consent_freeze')->where(array('is_active'=>'t'))->where("to_date>='".date('Y-m-d')."'")->orderBy('list_year DESC, list_number DESC')->getCompiledSelect();
        $builder = $this->db->table('court_ip ci')->select('distinct(ci.court_no)')->join("($sql) freeze", "ci.court_no=freeze.court_no", "left")->where('ci.court_no<20')->where('freeze.is_active','t')->orderBy('ci.court_no');
        $query_result = $builder->get();
        //echo $this->db->last_query();exit(0);
        if(!empty($query_result)){
            return $query_result->getResultArray();
        }
        else{
            return null;
        }
    }

    function unfreezed_court_list(){
        $sql = $this->db->table('hybrid_physical_hearing_consent_freeze')->where(array('is_active'=>'t'))->where("to_date>='".date('Y-m-d')."'")->orderBy('list_year DESC, list_number DESC')->getCompiledSelect();
        $builder = $this->db->table('court_ip ci')->select('ci.court_no')->join("($sql) freeze", "ci.court_no=freeze.court_no", "left")->where('ci.court_no<20')->where('freeze.id is null')->orderBy('ci.court_no');
        $query_result = $$builder->get();
        if(!empty($query_result)){
            return $query_result->result_array();
        }
        else{
            return null;
        }
    }
    function available_court_list_in_weekly()
    {
        $sql="select distinct wl.courtno as court_no from weekly_list wl inner join
            (SELECT 
                weekly_year, MAX(weekly_no) AS weekly_no FROM weekly_list GROUP BY weekly_year
                HAVING weekly_year = (SELECT  MAX(weekly_year) FROM weekly_list)) aa 
                on aa.weekly_year=wl.weekly_year and aa.weekly_no=wl.weekly_no order by courtno";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();//exit(0);

        return $query->getResultArray();
    }

    function weekly_list_number(){
        //$sql = $this->db->where(array('is_active'=>'t'))->where("to_date>='".date('Y-m-d')."'")->order_by('list_year DESC, list_number DESC')->limit('1')->get('hybrid_physical_hearing_consent_freeze');
        /*SELECT max(weekly_no) max_weekly_no, max(weekly_year) max_weekly_year FROM weekly_list
        where (year(curdate()) = weekly_year OR (year(curdate()) + 1) = weekly_year)*/

        $sql = $this->db->table('weekly_list')->where("to_dt>='".date('Y-m-d')."'")->orderBy('weekly_year DESC, weekly_no DESC')->limit('1')->get();
        return $sql->getResultArray();
        // echo $this->db->last_query();exit(0);
    }

    /* old logic
    function getListedAdvocateMatters($advocateId,$court=null){
        $sql = $this->db->where(array('is_active'=>'t'))->where("to_date>='".date('Y-m-d')."'")->order_by('list_year DESC, list_number DESC')->get_compiled_select('hybrid_physical_hearing_consent_freeze');
        $this->db->select('hphc.court_no, hphc.list_number,hphc.list_year, count(DISTINCT hphc.diary_no) as total_cases');
        $this->db->from('hybrid_physical_hearing_consent hphc');
        $this->db->join("($sql) hphcf",'hphc.list_type_id=hphcf.list_type_id AND hphc.list_number=hphcf.list_number AND hphc.list_year=hphcf.list_year and hphc.court_no=hphcf.court_no');
        $this->db->join('main m','hphc.diary_no=m.diary_no');
        $this->db->join('advocate a','m.diary_no=a.diary_no');
        $this->db->where('hphcf.to_date >=',"'".date('Y-m-d')."'",FALSE);
        if(!empty($court))
            $this->db->where("hphc.court_no",trim($court));
        $this->db->where('a.advocate_id',$advocateId);
        $this->db->group_by("hphc.court_no, hphc.list_number,hphc.list_year");
        $this->db->order_by('hphcf.court_no');
        $query_result = $this->db->get();
        //echo $this->db->last_query();exit(0);

        if(!empty($query_result)){
            return $query_result->result_array();
        }
        else
        {
            return null;
        }
    }*/

    /* version 2 logic
     function getListedAdvocateMatters($advocateId,$court=null){
          $sql = $this->db->where(array('is_active'=>'t'))->where("to_date>='".date('Y-m-d')."'")->order_by('list_year DESC, list_number DESC')->get_compiled_select('hybrid_physical_hearing_consent_freeze');
          $this->db->select('hphc.court_no, hphc.list_number,hphc.list_year, count(DISTINCT hphc.diary_no) as total_cases');
          $this->db->from('hybrid_physical_hearing_consent hphc');
          $this->db->join("($sql) hphcf",'hphc.list_type_id=hphcf.list_type_id AND hphc.list_number=hphcf.list_number AND hphc.list_year=hphcf.list_year and hphc.court_no=hphcf.court_no');
          $this->db->join('main m','hphc.diary_no=m.diary_no');
          $this->db->join('advocate a','m.diary_no=a.diary_no');
          $this->db->where('hphcf.to_date >=',"'".date('Y-m-d')."'",FALSE);
          if(!empty($court))
              $this->db->where("hphc.court_no",trim($court));
            $this->db->where('a.advocate_id',$advocateId);
          $this->db->group_by("hphc.court_no, hphc.list_number,hphc.list_year");
          $this->db->order_by('hphcf.court_no');
          $query_result = $this->db->get();
          echo $this->db->last_query();exit(0);

          if(!empty($query_result)){
              return $query_result->result_array();
          }
          else
          {
              return null;
          }
      }*/

    function getListedAdvocateMatters($advocateId,$court=null){
        $sub_sql = $this->db->table('hybrid_physical_hearing_consent_freeze')->where(array('is_active'=>'t'))->where("to_date>='".date('Y-m-d')."'")->orderBy('list_year DESC, list_number DESC')->getCompiledSelect();
        $where_condition=(!empty($court))?'f.courtno=?':'1=1';
        $sql="select	 f.courtno as court_no,f.weekly_no as list_number,
				f.weekly_year as  list_year,
				group_concat(
    case when f.consent = 'P' then f.diary_no  end
  ) as all_court_directed_physical_concent_diary_nos, 
  group_concat(
    case when f.consent = 'V' then f.diary_no  end
  ) as all_court_directed_virtual_concent_diary_nos, 
  group_concat(
    case when f.consent = 'H' then f.diary_no end
  ) as all_court_directed_hybrid_concent_diary_nos,
				count(distinct f.diary_no) as total_cases,
                sum(case when f.consent='P' then 1 else 0 end) as total_court_directed_physical_concent,
                sum(case when f.consent='V' THEN 1 else 0 end) as total_court_directed_virtual_concent,
                sum(case when f.consent='H' THEN 1 else 0 end) as total_court_directed_Hybrid_concent,
                sum(case when (f.consent='' OR f.consent is null)  THEN 1 else 0 end) as advocate_consent_pending
                from
                (
				select   
                hphc.consent,
                hphc.hearing_from_time,
                hphc.hearing_to_time,               
                CASE
                    WHEN
                        (m.diary_no = m.conn_key
                            OR m.conn_key = ''
                            OR m.conn_key IS NULL
                            OR m.conn_key = '0')
                    THEN
                        'M'
                    ELSE 'C'
                END AS main_connected, h.* from 
                (select wl1.*,hphcf.to_date from weekly_list wl1
                inner join (
                SELECT max(weekly_no) max_weekly_no, max(weekly_year) max_weekly_year
                 FROM weekly_list where (year(curdate()) = weekly_year OR (year(curdate()) + 1) = weekly_year)
                ) wl2 on wl2.max_weekly_no = wl1.weekly_no and wl2.max_weekly_year = wl1.weekly_year
                inner join hybrid_physical_hearing_consent_freeze hphcf on wl2.max_weekly_no=hphcf.list_number 
          and  wl2.max_weekly_year=hphcf.list_year and hphcf.is_active='t' and hphcf.court_no=wl1.courtno
                where courtno > 0 and to_date>='".date('Y-m-d')."') h
                inner join main m on m.diary_no = h.diary_no
                left join heardt h1 on h1.diary_no = m.diary_no
                LEFT JOIN conct ct on m.diary_no=ct.diary_no and ct.list='Y'
                left join hybrid_physical_hearing_consent hphc on (hphc.diary_no = m.diary_no and hphc.to_dt>='".date('Y-m-d')."')
                left join advocate a on (m.diary_no=a.diary_no and a.display='Y')
               
                where m.c_status = 'P'  and a.advocate_id=?
                group by m.diary_no
                ORDER BY LENGTH(h.judges_code) DESC, h.next_dt, h.item_no, 
                if(h.conn_key=h.diary_no,'0000-00-00',99) ASC, 
                if(ct.ent_dt is not null,ct.ent_dt,999) ASC,
                cast(SUBSTRING(m.diary_no,-4) as signed) ASC, 
                cast(LEFT(m.diary_no,length(m.diary_no)-4) as signed ) ASC
                )f where $where_condition
                group by courtno,weekly_no,weekly_year";
        $condition=(!empty($court))?array($advocateId,$court):array($advocateId);

        $query = $this->db->query($sql,$condition);
        // echo $this->db->last_query();exit(0);

        return $query->getResultArray();

    }
    function get_court_directed_last_updated_consent($diary_no,$list_number,$list_year, $court_no)
    {
        //$physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $sql="SELECT CASE
                  WHEN (hphc.diary_no = hphc.conn_key
                          OR hphc.conn_key = ''
                          OR hphc.conn_key IS NULL
                          OR hphc.conn_key = '0' ) THEN hphc.diary_no
                  ELSE hphc.conn_key
                END as diary_no,list_number, list_year, entry_date as updated_on, consent, null as advocate_id,court_no,
                'C' as source
            FROM hybrid_physical_hearing_consent hphc
            where  hphc.diary_no=? and hphc.list_number=? and hphc.list_year=?  and hphc.court_no=?";
        $query = $this->db->query($sql, array($diary_no,$list_number,$list_year,$court_no));
        //echo $this->db->last_query();exit();
        return $query->getResultArray();
    }

    function get_consent_received_from_advocate_all_cases($list_number,$list_year,$next_date,$today_listed_main_cases)
    {
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $physical_hearing_db->query('SET SESSION group_concat_max_len=1000000');
        $search_for_cases=implode(",", $today_listed_main_cases);

        /*$sql="SELECT group_concat(consent_for_diary_nos) as cases_list
            FROM physical_hearing_advocate_consent phac
            where  phac.list_number=? and phac.list_year=? and phac.next_dt=?";*/

        $sql="select group_concat(adv) as consent_received_cases_advocates from 
                (
                SELECT concat(phac.diary_no,'_',phac.advocate_id) as adv 
                FROM physical_hearing_advocate_consent phac where phac.list_number=?
                and phac.list_year=? and phac.next_dt=? and phac.is_deleted='f'  and phac.diary_no in ($search_for_cases)
                group by phac.diary_no,phac.advocate_id 
                )adv1";

        $query = $physical_hearing_db->query($sql, array($list_number,$list_year,$next_date));
        //echo $physical_hearing_db->last_query();exit();
        return $query->result_array();
    }

    function get_consent_not_received_cases_advocate_mobile_nos($consent_pending_from_advocates_list)
    {
        $consent_pending_from_advocates=implode(",", $consent_pending_from_advocates_list);
        $this->db->query('SET SESSION group_concat_max_len=1000000');

        $sql="select group_concat(distinct(b.mobile)) as mobile_nos from advocate a
                inner join bar b on a.advocate_id=b.bar_id
                where b.if_sen='N' and b.if_aor='Y' and b.isdead='N'
                and a.display='Y'  and a.advocate_id in ($consent_pending_from_advocates)";
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    function get_consent_received_from_advocate_main_cases($list_number,$list_year,$next_date=null)
    {
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        // $this->db->query('SET SESSION group_concat_max_len=1000000');

        $sql="SELECT distinct phac.diary_no,list_number,list_year,court_no,advocate_id
            FROM physical_hearing_advocate_consent phac
            where  phac.list_number=? and phac.list_year=? and next_dt is NULL ";
        $query = $physical_hearing_db->query($sql, array($list_number,$list_year));
        //echo $physical_hearing_db->last_query();exit();
        return $query->result_array();

    }

    function get_case_all_attendee_added_by_advocate_with_blank_next_dt($list_number,$list_year,$next_date=null)
    {
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        //$this->db->query('SET SESSION group_concat_max_len=1000000');

        $sql="SELECT diary_no,name,email_id,mobile,created_by_advocate_id,court_no,list_number,list_year
            FROM attendee_details ad
            where  ad.list_number=? and ad.list_year=? and ad.display='Y' and next_dt is NULL";
        $query = $physical_hearing_db->query($sql, array($list_number,$list_year));
        //echo $physical_hearing_db->last_query();exit();

        return $query->result_array();
    }

    function get_list_all_advocate_of_today_daily_list_cases($today_list_main_cases)
    {
        $search_for_cases=implode(",", $today_list_main_cases);
        $this->db->query('SET SESSION group_concat_max_len=10000000');

        $sql="select group_concat(adv) as case_all_advocates from 
                (select concat(a.diary_no,'_',a.advocate_id) as adv
from advocate a inner join bar b on a.advocate_id=b.bar_id               
				where b.if_sen='N' and b.if_aor='Y' and b.isdead='N'
                and a.display='Y'  and a.diary_no in ($search_for_cases)
                group by a.diary_no,a.advocate_id)all_adv";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();exit();
        return $query->getResultArray();

    }


}
