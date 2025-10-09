<?php
// if ( ! defined('BASEPATH')) exit('No direct script access allowed');

namespace App\Models\PhysicalHearing;

use CodeIgniter\Model;
use Config\Database;

class ConsentModel extends Model
{

    protected $DBGroup = 'default';
    protected $sci_cmis_final;
    protected $physical_hearing;

    function __construct()
    {
        // Call the Model constructor
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

    function InsertLog($tableFrom,$TableTo,$diary_no,$list_number,$list_year, $court_no)
    {
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $sql = "insert into physical_hearing_advocate_consent_log (diary_no, conn_key, is_fixed, next_dt, list_number, list_year, updated_on, is_deleted, updated_by, updated_from_ip, vacation_list_year, mainhead, consent, advocate_id, created_at, court_no) (select diary_no, conn_key, is_fixed, next_dt, list_number, list_year, updated_on, is_deleted, updated_by, updated_from_ip, vacation_list_year, mainhead, consent, advocate_id, created_at, court_no from physical_hearing_advocate_consent where diary_no=? and list_number=? and list_year=? and court_no=?)";
        $physical_hearing_db->query($sql, array($diary_no,$list_number,$list_year, $court_no));
        //echo $physical_hearing_db->last_query();exit();
        $insert_id = $physical_hearing_db->insert_id();
        return $insert_id;
    }
    function save($table=null, $data=null):bool
    {
        // $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $builder = $this->physical_hearing->table($table);
        if($builder->insert($data))
            return 1;
        else
            return 0;
    }

    function update($table=null, $data=null, $condition_array=null):bool
    {
        // $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        // $physical_hearing_db->where($condition_array)->set($table, $data)->update();
        $builder = $this->physical_hearing->table($table);
        if($builder->WHERE($condition_array)->set($data)->update())
            return 1;
        else
            return 0;
    }

    function getCasesForConsent($aor_id){
        $sql="SELECT phcr.id,phcr.diary_no,m.reg_no_display,m.pet_name,m.res_name,phcr.consent,case when phcr.consent='N' then true else false end as if_changeable
            FROM physical_hearing_consent_required phcr inner join advocate a on phcr.diary_no=a.diary_no
            inner join main m on phcr.diary_no=m.diary_no
            where phcr.is_deleted='f' and a.advocate_id=?;";
        $query = $this->physical_hearing->query($sql, array($aor_id));
        //echo $this->db->last_query();exit();
        return $query->getResultArray();
    }

    function get_advocate_last_updated_consent($diary_no,$list_number,$list_year,$advocate_id, $court_no)
    {
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $sql="SELECT diary_no,list_number, list_year, updated_on, consent, advocate_id,court_no,'A' as source
            FROM physical_hearing_advocate_consent phac
            where phac.is_deleted='f' and phac.diary_no=? and phac.list_number=? and phac.list_year=? and advocate_id=? and court_no=?";
        $query = $physical_hearing_db->query($sql, array($diary_no,$list_number,$list_year,$advocate_id, $court_no));
        //echo $physical_hearing_db->last_query().'<br>';
        return $query->result_array();
    }


    function getCaseDetailsByid($id){
        $sql="SELECT phcr.id,phcr.diary_no,phcr.conn_key,phcr.consent,
            case when phcr.consent='N' then true else false end as if_changeable
            FROM physical_hearing_consent_required phcr         
            where phcr.is_deleted=? and phcr.id=?";
        $query = $this->physical_hearing->query($sql, array('f',$id));
        return $query->getResultArray();
    }
    function getAORConsentDetails($diary_no,$list_number,$list_year,$advocateId, $court_no){
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $physical_hearing_db->select('diary_no, conn_key, is_fixed, next_dt, list_number, list_year, updated_on, is_deleted, updated_by, updated_from_ip, vacation_list_year, mainhead, consent, advocate_id, created_at, court_no');
        $physical_hearing_db->from('physical_hearing_advocate_consent');
        $physical_hearing_db->where('diary_no',$diary_no);
        $physical_hearing_db->where('list_number',$list_number);
        $physical_hearing_db->where('list_year',$list_year);
        $physical_hearing_db->where('advocate_id',$advocateId);
        $physical_hearing_db->where('court_no',$court_no);
        $query=$physical_hearing_db->get();
        //echo $physical_hearing_db->last_query();
        return $query->result_array();
    }


    function getAdvocatePhysicalConsentSummary($advocateId, $list_number, $list_year, $court_no=null){
        $physical_hearing_db = $this->load->database('physical_hearing', TRUE);
        $physical_hearing_db->select('court_no,SUM(case when consent="P" then case_count else 0 end) as total_physical,SUM(case when consent="V" then case_count else 0 end) as total_virtual,group_concat(CASE WHEN consent = "P" THEN consent_for_diary_nos  END) as all_physical_diary_nos, group_concat(CASE WHEN consent = "V" THEN consent_for_diary_nos  END) as all_virtual_diary_nos');
        $physical_hearing_db->from('physical_hearing_advocate_consent phac');
        $physical_hearing_db->where('list_number',$list_number);
        $physical_hearing_db->where('list_year',$list_year);
        $physical_hearing_db->where('advocate_id',$advocateId);
        $physical_hearing_db->where('((phac.next_dt is null) or (phac.next_dt>="'.date('Y-m-d').'"))');
        #$physical_hearing_db->where('consent','P');
        $physical_hearing_db->where('is_deleted','f');
        if(!is_null($court_no))
            $physical_hearing_db->where('court_no',$court_no);
        $physical_hearing_db->group_by('court_no');
        $query=$physical_hearing_db->get();
        // echo $physical_hearing_db->last_query().'<br>';
        return $query->result_array();
    }


    function getTentativeAttendeeListForMail($list_no,$list_year,$daily_list_matters,$list_date=null)
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
        $query = $this->physical_hearing->query($sql);
        return $query->getResultArray();
    }

    function getAorCode($advocate_id)
    {
        $builder = $this->db->table('bar b');
        $builder->where('bar_id',$advocate_id);
        $builder->where('if_aor','Y');
        $builder->where('if_sen','N');
        $builder->where('isdead','N');
        $query=$builder->get();
        return $query->getResultArray();
    }

    public function getAdvocateConsentDetails($date)
	{
		if (!empty($date)) {
			$date = date('Y-m-d', strtotime($date));
		} else {
			$date = date("Y-m-d");
		}
		$builder = $this->physical_hearing->table('physical_hearing.physical_hearing_advocate_vc_consent as p');
		$builder->where('p.is_deleted','f');
		$builder->where('p.consent','V');
		$builder->where('p.next_dt', $date);
		$query = $builder->get();
        if ($query === false) {
            log_message('error', 'Query failed: ' . $builder->getCompiledSelect());
            return [];  // Or return an appropriate error message
        }
        return $query->getResultArray();
	}

}