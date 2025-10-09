<?php
namespace App\Models\Supplements;

use CodeIgniter\Model;
class SupplementModel extends Model {

    function __construct() {
        parent::__construct();
    }
    public function search_case_or_parties_by_diaryNo($diaryNo,$diaryYear,$response_type=null,$party=null)
    {
        $diary_data = $this->efiling_webservices->get_case_diary_details_from_SCIS($diaryNo, $diaryYear);

        if ($diary_data != false) {
            $case_data = $diary_data->case_details[0];
            $parties = $diary_data->case_details[0]->parties;
            if ($party!='P') {
                if (!empty($case_data->active_casetype_id) && $case_data->active_casetype_id !=null && $case_data->active_casetype_id !=0) {
                    $efiling_detailsFinal = array(
                        'registration_id' => $case_data->diary_no . $case_data->diary_year,
                        'diary_no' => $case_data->diary_no,
                        'diary_year' => $case_data->diary_year,
                        'case_num' => ltrim(substr($case_data->active_fil_no, -6), '0'),
                        'case_year' => $case_data->active_reg_year,
                        'case_type_name' => $case_data->reg_no_display,
                        'pet_name' => $case_data->pet_name,
                        'res_name' => $case_data->res_name,
                        'pno' => $case_data->pno,
                        'rno' => $case_data->rno,
                        'date_of_decision' => $case_data->ord_dt,
                        'advocates' => $case_data->advocates,
                        'case_grp' => $case_data->case_grp,
                        'cause_title' => $case_data->cause_title,
                        'case_status' => $case_data->c_status,
                        'sc_case_type_id' => $case_data->active_casetype_id,
                        'casename' => $case_data->casename,
                        'nature' => $case_data->nature,
                        'database_type' => 'I',
                        'response_type' => $response_type
                    );
                }else{
                    return 'active casetype id is required';exit();
                }
               // echo  '<pre>'; print_r($efiling_detailsFinal); exit();
                session()->set(array('efiling_details' => $efiling_detailsFinal));
            }
            return $parties;

        }else{
            return false;
        }
    }
    public function get_efiling_num_basic_Details_pdf($search_type,$efiling_no=null,$diary_no=null,$diary_year=null,$case_no=null,$case_year=null,$case_type_id=null,$response_type=null)
    {

		$builder = $this->db->table('efil.tbl_efiling_nums as en');
        $builder->SELECT(array('en.efiling_for_type_id', 'en.efiling_for_id', 'en.ref_m_efiled_type_id',
            'en.efiling_no', 'en.efiling_year', 'en.registration_id', 'en.allocated_on', 'en.create_on',
            'et.efiling_type','cs.stage_id', 'cs.activated_on', 'en.sub_created_by',
            'new_case_cd.cause_title', 'new_case_cd.sc_diary_num as diary_no', 'new_case_cd.sc_diary_year as diary_year', 'new_case_cd.sc_diary_date', 'new_case_cd.sc_display_num', 'new_case_cd.sc_reg_date',
            'lcd.case_num', 'lcd.case_year','lcd.case_type_id','lcd.court_type',
            'users.first_name','users.last_name','users.moblie_number','users.emailid'
        ));
        $builder->JOIN('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id');
        $builder->JOIN('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $builder->JOIN('efil.tbl_case_details as new_case_cd', 'en.registration_id = new_case_cd.registration_id', 'left');

        $builder->JOIN('efil.tbl_users users', 'users.id=en.created_by','left');
        $builder->JOIN('efil.tbl_user_types ut', 'ut.id=users.ref_m_usertype_id');
        $builder->JOIN('efil.tbl_lower_court_details lcd', 'en.registration_id=lcd.registration_id','left');

        if(!empty($search_type) && $search_type!=null && $search_type=='register' && $search_type!='efilingNO' && $search_type!='diary') {
            if(!empty($case_no) && $case_no!=null) {
                $$builder->WHERE('lcd.case_num',$case_no);
            }
            if(!empty($case_year) && $case_year!=null) {
                $builder->WHERE('lcd.case_year',$case_year);
            }
            if(!empty($case_type_id) && $case_type_id!=null) {
                $builder->WHERE('lcd.case_type_id',$case_type_id);
            }
            $builder->WHERE('lcd.court_type','4');
            //$this->db->WHERE('cast(lcd.court_type as integer)',4);

        }else if(!empty($search_type) && $search_type!=null && $search_type=='diary' && $search_type!='efilingNO') {
            if(!empty($diary_no) && $diary_no!=null) {
                $builder->WHERE('new_case_cd.sc_diary_num',$diary_no);
            }
            if(!empty($diary_year) && $diary_year!=null) {
                $builder->WHERE('new_case_cd.sc_diary_year',$diary_year);
            }

        }else if(!empty($search_type) && $search_type!=null && $search_type=='efilingNO' && $search_type!='diary') {
            if(!empty($efiling_no) && $efiling_no!=null) {
                //$this->db->LIKE('en.efiling_no',$efiling_no);
                $builder->WHERE('en.efiling_no',$efiling_no);
            }

        }


        $builder->WHERE('cs.is_deleted', 'FALSE');
        $builder->WHERE('cs.is_active', 'TRUE');
        $builder->WHERE('en.is_active', 'TRUE');
        // $this->db->WHERE('cs.stage_id',9);
        $builder->orderBy('cs.activated_on');
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {

            $efiling_details = $query->getResultArray();
            //echo "<pre>";print_r($efiling_details);exit();
            $datamain=array(['database_type' =>'E','response_type'=>$response_type]);
            $efiling_detailsFinal = array_merge($datamain[0], $efiling_details[0]);

            session()->set(array('efiling_details' => $efiling_detailsFinal));

            return $efiling_details;
        } else {
            return false;
        }
    }
    //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX START XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    public function get_efiling_num_basic_Details_pdf_bkp($efiling_NO) {

		$builder = $this->db->table('efil.tbl_efiling_nums as en');

        $builder->SELECT("en.registration_id, en.efiling_no, en.efiling_year, en.ref_m_efiled_type_id, et.efiling_type,
                en.efiling_for_type_id, en.efiling_for_id, 
                en.breadcrumb_status, en.signed_method, en.allocated_to,
                en.created_by, en.sub_created_by,
                case when en.ref_m_efiled_type_id=1 then tcd.sc_diary_num else misc_ia.diary_no end as diary_no,
                case when en.ref_m_efiled_type_id=1 then tcd.sc_diary_year else misc_ia.diary_year end as diary_year,
                cs.stage_id, cs.activated_on,
                (select payment_status from efil.tbl_court_fee_payment where registration_id = en.registration_id order by id desc limit 1 )",false);

        $builder->JOIN('efil"."tbl_misc_docs_ia as misc_ia', 'en.registration_id = misc_ia.registration_id', 'left');
        $builder->JOIN('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id');
        $builder->JOIN('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id = et.id');
        $builder->JOIN('efil.tbl_case_details tcd','en.registration_id=tcd.registration_id','left');
        $builder->WHERE('cs.is_active', 'TRUE');
        $builder->WHERE('cs.is_deleted', 'FALSE');
        $builder->WHERE('en.is_active', 'TRUE');
        $builder->WHERE('en.is_deleted', 'FALSE');
        $builder->WHERE('en.efiling_no', $efiling_NO);
        $query = $builder->get();
        // echo $this->db->last_query(); exit;
        if ($query->getNumRows() >= 1) {

            $efiling_details = $query->getResultArray();
            $datamain=array(['no_of_petitioners' =>0,'no_of_respondents' =>0,]);

            $efiling_detailsFinal = array_merge($datamain[0], $efiling_details[0]);

            session()->set(array('efiling_details' => $efiling_detailsFinal));



            return TRUE;
        } else {
            return false;
        }
    }//END of function get_efiling_num_basic_Details_pdf()..


    //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX END XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    function upsert($data_array){
		
		$builder = $this->db->table('efil.tbl_check_list');

        $query = $builder->get('efil.tbl_check_list');
        $row = $query->getRowArray();
        if(!empty($row) && $row['is_signed']=='f'){
            $data_array['updated_on']=date('Y-m-d H:i:s');
            $data_array['updated_by']='1';
            $builder->set($data_array);
            $builder->where('registration_id', $row['registration_id']);
            return $builder->update();
        }
        else if(empty($row)){
			$builder = $this->db->table('efil.tbl_check_list');
            return $builder->insert($data_array);
        }
        else if($row['is_signed']=='t'){
            return 'signed';
        }
        else
            return 'duplicate_key';
    }

    function get_data($registration_id){
		$builder = $this->db->table('efil.tbl_check_list');
        $builder->where('registration_id', $registration_id);
		$query = $builder->get();
        return $query->getResultArray();
    }

    function table_data($table, $condition='1=1'){
		$builder = $this->db->table($table);
        $builder->where($condition);
		$builder->orderBy(1);
		$query = $builder->get();
        return $query->getResultArray();
    }

    function update_data($table, $data, $condition){
		$builder = $this->db->table($table);
        $builder->where($condition);
		$builder->set($data);
		$query = $builder->update();
		return $query; 
    }

    function get_signers_list($registration_id){
		
		$builder = $this->db->table('efil.tbl_case_parties cp');
        $builder->SELECT("cp.id,cp.registration_id,cp.m_a_type,cp.party_no,cp.p_r_type,cp.email_id,cp.address as party_address,cp.party_age,cp.relation,cp.relative_name,cp.pincode,d.deptname,
      st.agency_state state_name,
      case when trim(cp.party_type)!='' then cp.party_name
      else concat(case when cp.org_post_not_in_list='t' then cp.org_post_name else a.authdesc end,', ',
      case when cp.org_dept_not_in_list='t' then cp.org_dept_name else d.deptname end
      ) end party_name");
        $builder->JOIN('icmis.ref_agency_state st','cp.state_id=st.cmis_state_id', 'left');
        $builder->JOIN('icmis.deptt d','cp.org_dept_id=d.deptcode', 'left');
        $builder->JOIN('icmis.authority a','cp.org_post_id=a.authcode', 'left');
        //$this->db->JOIN('','', 'left');
        $builder->WHERE(array('cp.registration_id'=>$registration_id, 'cp.is_deleted'=>'false', 'cp.m_a_type'=>'M'));
        $builder->orderBy('cp.p_r_type asc');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }



    // Anshu
    function insert_update_supplements($data,$sc_case_type_id,$deponent_type,$registration_id=null)
    {
        if(!empty($registration_id) && $registration_id!=null){
			$builder = $this->db->table('efil.tbl_affidavits');
            $is_update=$this->get_supplements($registration_id,$sc_case_type_id,$deponent_type,'is_update');
            if ($is_update[0]['is_deleted']=='f' && $is_update[0]['is_signed']=='f') {
                $builder->WHERE('registration_id', $registration_id);
                $builder->WHERE('is_deleted', 'f');
                $builder->WHERE('ref_affidavit_formats', $is_update[0]['ref_affidavit_formats']);
                //$this->db->WHERE('is_signed', 'f');
                $builder->UPDATE($data);
                return true;
            }else{ return false;   }
        }else{
            $is_deleted=$this->get_supplements($_SESSION['efiling_details']['registration_id'],$sc_case_type_id,$deponent_type,'is_deleted');
            if (empty($is_deleted) || $is_deleted[0]['is_deleted']=='t'){
				$builder = $this->db->table('efil.tbl_affidavits');
                $builder->insert('efil.tbl_affidavits',$data);
                $last_id = $this->db->insertID();
                return $last_id;
            }else{ return false; }

        }

    }

    function get_supplements($registration_id,$sc_case_type_id,$deponent_type,$type=null)
    {  //only review and IA Court Type:Supreme Court=4
		$builder = $this->db->table('efil.tbl_affidavits ta');
        $builder->SELECT('ta.*,maf.id as m_id,maf.deponent_type,maf.sc_case_type_id,ct.casename,ct.nature,maf.header,maf.deponent,maf.description,maf.verification,maf.is_valid');
        $builder->JOIN('efil.m_affidavit_formats maf','ta.ref_affidavit_formats=maf.id');
        $builder->JOIN('icmis.casetype ct','maf.sc_case_type_id=ct.sc_case_type_code');
        $builder->WHERE('ta.registration_id',$registration_id);
        $builder->WHERE('maf.sc_case_type_id',$sc_case_type_id);
        if (!empty($deponent_type) && $deponent_type !='check'){ $builder->WHERE('maf.deponent_type',$deponent_type);}
        if(!empty($type) && $type!=null && $type=='pdf'){
            //$this->db->WHERE('ta.is_signed', 't');
            $builder->WHERE('ta.is_deleted', 'f');
        }else if(!empty($type) && $type!=null && $type=='is_deleted'){
            $builder->WHERE('ta.is_deleted', 't');
        }else if(!empty($type) && $type!=null && $type=='is_update'){
            $builder->WHERE('ta.is_signed', 'f');
            $builder->WHERE('ta.is_deleted', 'f');
        }else{
            $builder->WHERE('ta.is_deleted', 'f');
        }
        $builder->WHERE('ct.display', 'Y');
        $builder->LIMIT(1);
        $query=$builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function affidavit_template($sc_case_type_id,$deponent_type){
		$builder = $this->db->table('efil.m_affidavit_formats maf');
        $builder->SELECT("maf.*,ct.casename,ct.nature");
        $builder->JOIN('icmis.casetype ct','maf.sc_case_type_id=ct.sc_case_type_code');
        $builder->WHERE('maf.sc_case_type_id',$sc_case_type_id);
        $builder->WHERE('maf.deponent_type',$deponent_type);
        $builder->WHERE('ct.display', 'Y');
        $builder->LIMIT(1);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function check_case_number_year_by_registration_id($registration_id,$slp_type_civil_criminal_id){
		$builder = $this->db->table('efil.tbl_lower_court_details lcd');
        $builder->SELECT("ct.casename,ct.nature,lcd.casetype_name as slp_name,lcd.court_type,lcd.case_type_id,lcd.case_num,lcd.case_year");
        $builder->JOIN('icmis.casetype ct','lcd.case_type_id=ct.sc_case_type_code');
        $builder->WHERE('lcd.registration_id',$registration_id);
        $builder->WHERE('lcd.case_type_id',$slp_type_civil_criminal_id);
        $builder->WHERE('ct.display', 'Y');
        $builder->WHERE('cast(lcd.court_type as integer)=4');
        $builder->LIMIT(1);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }
    
    function get_affidavits_filled_data($registration_id, $sc_case_type_id){
        $builder = $this->db->table('efil.tbl_affidavits ta');
        $builder->JOIN('efil.m_affidavit_formats maf','maf.id=ta.ref_affidavit_formats', 'left');
        $builder->WHERE('ta.registration_id',$registration_id);
        $builder->WHERE('maf.sc_case_type_id',$sc_case_type_id);
        $builder->WHERE('maf.is_valid', 't');
        $query = $builder->get();
        //echo $this->db->last_query();
        return $query->getResultArray();
    }
    
    function case_details($registration_id){
		$builder = $this->db->table('efil.tbl_case_details tcd');
        $builder->JOIN('icmis.casetype c','c.casecode =tcd.sc_case_type_id ', 'left');
        $builder->WHERE('tcd.registration_id',$registration_id);
        $query = $builder->get();
        return $query->getResultArray();
    }

    function cancel_pdfdata_checklist($reg_ID,$data_cancel){
        //rounak mishra
		$builder = $this->db->table('efil.tbl_check_list');

        $builder->WHERE('registration_id', $reg_ID);
        $builder->WHERE('is_deletd', false);
        $builder->SET($data_cancel);
        $builder->UPDATE();

        if ($this->db->affectedRows() > 0) {

            return TRUE;
        } else {
            return FALSE;
        }
    }
}
