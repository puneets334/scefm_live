<?php

namespace App\Models\AIAssisted;

use CodeIgniter\Model;
class IitmIcmisEfmApiJsonModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function get_uploaded_pdf_detail_list($fromDate,$toDate,$efileno='') {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs tup');
        $builder->SELECT("
            tup.registration_id,
            MAX(tup.file_name) AS file_name,
            MAX(ten.efiling_no) AS efiling_no,
            MAX(ten.ref_m_efiled_type_id) AS ref_m_efiled_type_id,
            MAX(tupj.iitm_api_json) AS iitm_api_json,
            MAX(tupj.efiling_json) AS efiling_json,
            MAX(tupj.icmis_json) AS icmis_json,
            MAX(tup.uploaded_on) AS uploaded_on"
        );
        $builder->JOIN('efil.tbl_efiling_nums ten','tup.registration_id=ten.registration_id', 'left');
        $builder->JOIN('efil.tbl_efiling_num_status tens','tup.registration_id=tens.registration_id', 'left');
        $builder->JOIN('efil.tbl_iitm_icmis_efm_api_json tupj','tup.registration_id=tupj.registration_id', 'left');
        if(isset($efileno) && !empty($efileno)) {
            $builder->WHERE('ten.efiling_no', $efileno);
        } else {
            $builder->WHERE('DATE(tup.uploaded_on) >=', $fromDate);
            $builder->WHERE('DATE(tup.uploaded_on) <=', $toDate);
        }
        $builder->WHERE('tup.is_active', true);
        $builder->WHERE('tup.is_deleted', FALSE);
        $builder->where('tens.stage_id',1);
        $builder->where('ten.ref_m_efiled_type_id',1);
        $builder->groupBy("tup.registration_id");
        $query = $builder->get();
        if ($query) {        
            return $query->getResultObject();
        } else {        
            return FALSE;
        }
    }

    public function get_uploaded_pdf_details($fromDate,$toDate,$efileno='',$service_for) {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs tup');
        $builder->SELECT("
            tup.*,
            ten.efiling_no AS efiling_no,
            ten.ref_m_efiled_type_id AS ref_m_efiled_type_id,
            tupj.iitm_api_json AS iitm_api_json,
            tupj.efiling_json AS efiling_json,
            tupj.icmis_json AS icmis_json"
        );
        $builder->JOIN('efil.tbl_efiling_nums ten','tup.registration_id=ten.registration_id', 'left');
        $builder->JOIN('efil.tbl_efiling_num_status tens','tup.registration_id=tens.registration_id', 'left');
        $builder->JOIN('efil.tbl_iitm_icmis_efm_api_json tupj','tup.registration_id=tupj.registration_id', 'left');
        if(isset($efileno) && !empty($efileno)) {
            $builder->WHERE('tupj.efiling_no', $efileno);
        } else {
            $builder->WHERE('DATE(tup.uploaded_on) >=', $fromDate);
            $builder->WHERE('DATE(tup.uploaded_on) <=', $toDate);
        }
        $builder->WHERE('tup.is_active', true);
        $builder->WHERE('tup.is_deleted', FALSE);
        $builder->where('tens.stage_id',1);
        $builder->where('ten.ref_m_efiled_type_id',1);
        if($service_for=='iitm'){
            $builder->WHERE('tupj.iitm_api_json IS NULL');
        } elseif($service_for=='efile') {
            $builder->WHERE('tupj.efiling_json IS NULL');
        } elseif($service_for=='icmis') {
            $builder->WHERE('tupj.icmis_json IS NULL');
        }
        $query = $builder->get();
        return  $query->getResultObject();
    }

    public function get_uploaded_pdf_json_data($registration_id) {
        $builder = $this->db->table('efil.tbl_iitm_icmis_efm_api_json tupj');
        $builder->SELECT("tupj.iitm_api_json,tupj.efiling_json,tupj.icmis_json");        
        $builder->WHERE('tupj.registration_id', $registration_id);
        $query = $builder->get();
        return  $query->getRow();
    }

    public function update_iitm_api_json($case_detail,$data,$operation_type) {
        if($operation_type=='update' ) {
            $builder = $this->db->table('efil.tbl_iitm_icmis_efm_api_json');
            $builder->set('iitm_api_json', $data);
            $builder->set('is_active_iitm', true);
            $builder->set('iitm_api_json_updated_on',date('Y-m-d H:i:s'));
            $builder->where('registration_id', $case_detail->registration_id);
            $builder->update();
        } else {
            $data = [
                'file_name'=>$case_detail->file_name,
                'file_path'=>$case_detail->file_path,
                'doc_title'=>$case_detail->doc_title,
                'page_no'=>$case_detail->page_no,
                'file_size'=>$case_detail->file_size,
                'file_type'=>$case_detail->file_type,
                'uploaded_on'=>$case_detail->uploaded_on,
                'uploaded_by'=>$case_detail->uploaded_by,
                'deleted_by'=>$case_detail->deleted_by,
                'deleted_on'=>$case_detail->deleted_on,
                'is_deleted'=>$case_detail->is_deleted,
                'delete_ip'=>$case_detail->delete_ip,
                'last_page'=>$case_detail->last_page,
                'total_page'=>$case_detail->total_page,
                'registration_id'=>$case_detail->registration_id,
                'efiling_no'=>$case_detail->efiling_no,
                'iitm_api_json'=>$data,
                'iitm_api_json_updated_on'=>date('Y-m-d H:i:s'),
                'api_stage_id' => 2
            ];
            $builder = $this->db->table('efil.tbl_iitm_icmis_efm_api_json');
            $builder->insert($data);
        }
    }

    public function update_efiiling_json($case_detail,$data) {
        $builder = $this->db->table('efil.tbl_iitm_icmis_efm_api_json');
        $builder->where('registration_id',$case_detail->registration_id);
        $q = $builder->get();
        if ( $q->getNumRows() > 0 ) {
            $builder = $this->db->table('efil.tbl_iitm_icmis_efm_api_json');
            $builder->set('efiling_json', $data);
            $builder->set('is_active_efiling', true);
            $builder->set('efiling_json_updated_on',date('Y-m-d H:i:s'));
            $builder->where('registration_id', $case_detail->registration_id);
            $builder->update();
        } else {
            $data = [
                'file_name'=>$case_detail->file_name,
                'file_path'=>$case_detail->file_path,
                'doc_title'=>$case_detail->doc_title,
                'page_no'=>$case_detail->page_no,
                'file_size'=>$case_detail->file_size,
                'file_type'=>$case_detail->file_type,
                'uploaded_on'=>$case_detail->uploaded_on,
                'uploaded_by'=>$case_detail->uploaded_by,
                'deleted_by'=>$case_detail->deleted_by,
                'deleted_on'=>$case_detail->deleted_on,
                'is_deleted'=>$case_detail->is_deleted,
                'delete_ip'=>$case_detail->delete_ip,
                'last_page'=>$case_detail->last_page,
                'total_page'=>$case_detail->total_page,
                'registration_id'=>$case_detail->registration_id,
                'efiling_no'=>$case_detail->efiling_no,
                'efiling_json'=>$data,
                'is_active_efiling'=>true,
                'efiling_json_updated_on'=>date('Y-m-d H:i:s'),
                'api_stage_id' => 3
            ];
            $builder = $this->db->table('efil.tbl_iitm_icmis_efm_api_json');
            $builder->insert($data);
        }
    }

    public function update_icmis_api_json($case_detail,$data) {
        $builder = $this->db->table('efil.tbl_iitm_icmis_efm_api_json');
        $builder->where('registration_id',$case_detail->registration_id);
        $q = $builder->get();
        if ( $q->getNumRows() > 0 ) {
            $builder = $this->db->table('efil.tbl_iitm_icmis_efm_api_json');
            $builder->set('icmis_json', $data);
            $builder->set('is_active_icmis', true);
            $builder->set('icmis_json_updated_on',date('Y-m-d H:i:s'));
            $builder->where('registration_id', $case_detail->registration_id);
            $builder->update();
        } else {
            $data = [
                'file_name'=>$case_detail->file_name,
                'file_path'=>$case_detail->file_path,
                'doc_title'=>$case_detail->doc_title,
                'page_no'=>$case_detail->page_no,
                'file_size'=>$case_detail->file_size,
                'file_type'=>$case_detail->file_type,
                'uploaded_on'=>$case_detail->uploaded_on,
                'uploaded_by'=>$case_detail->uploaded_by,
                'deleted_by'=>$case_detail->deleted_by,
                'deleted_on'=>$case_detail->deleted_on,
                'is_deleted'=>$case_detail->is_deleted,
                'delete_ip'=>$case_detail->delete_ip,
                'last_page'=>$case_detail->last_page,
                'total_page'=>$case_detail->total_page,
                'registration_id'=>$case_detail->registration_id,
                'efiling_no'=>$case_detail->efiling_no,
                'icmis_json'=>$data,
                'is_active_icmis'=>true,
                'icmis_json_updated_on'=>date('Y-m-d H:i:s'),
                'api_stage_id' => 4
            ];
            $builder = $this->db->table('efil.tbl_iitm_icmis_efm_api_json');
            $builder->insert($data);
        }
    }

    public function get_uploaded_pdf_detail_data($registration_id) {
        $builder = $this->db->table('efil.tbl_iitm_icmis_efm_api_json');
        $builder->where('registration_id',$registration_id);
        $query = $builder->get();
        return $query->getRow();
    }

    public function getEarlierCourtDetailByRegistrationId($registration_id) {
        if (isset($registration_id) && !empty($registration_id)) {
            $builder = $this->db->table('efil.tbl_case_details tcd ');
            $builder->SELECT("tcd.estab_code,tcd.estab_id,tcd.court_type,tcd.state_id,tcd.district_id,
                (case when tcd.court_type = '1' then hcb.cmis_state_id   
                else case when tcd.court_type = '3' then dce.cmis_id_no
                else case when tcd.court_type = '5' then rac.cmis_state_id
                else case when tcd.court_type = '4' then 0 ::integer
                else case when tcd.court_type is null  then 0 ::integer
                end end end end end )as cmis_state_id  ,
                (case when tcd.court_type = '1' then hcb.name   
                else case when tcd.court_type = '3' then dce.state_name
                else case when tcd.court_type = '5' then rac.agency_name
                else case when tcd.court_type = '4' then '0'
                else case when tcd.court_type is null  then '0'
                end end end end end )as cmis_state_name  ,
                (case when tcd.court_type = '1' then hcb.name   
                else case when tcd.court_type = '3' then dce.estab_name
                else case when tcd.court_type = '5' then rac.agency_name
                else case when tcd.court_type = '4' then '0'
                else case when tcd.court_type is null  then '0'
                end end end end end )as ref_agency_code_name,
                (case when tcd.court_type = '1' then hcb.ref_agency_code_id   
                else case when tcd.court_type = '3' then dce.id
                else case when tcd.court_type = '5' then rac.id
                else case when tcd.court_type = '4' then 0 ::integer
                else case when tcd.court_type is null  then 0 ::integer
                end end end end end )as ref_agency_code_id   
            ");            
            $builder->JOIN('efil.m_tbl_high_courts_bench hcb', 'tcd.estab_code=hcb.est_code', 'left');
            $builder->JOIN('efil.m_tbl_district_courts_establishments dce', 'tcd.state_id=dce.state_code AND tcd.district_id= dce.district_code AND dce.court_code=3', 'left');
            $builder->JOIN('icmis.ref_agency_code rac', 'tcd.state_id=rac.cmis_state_id AND tcd.estab_id=rac.id  ', 'left');
            $builder->WHERE('tcd.registration_id', $registration_id);
            $builder->WHERE('tcd.is_deleted', FALSE);
            $query = $builder->get();
            if ($query->getNumRows() >= 1) {
                $new_case_details = $query->getResultArray();
                return $new_case_details;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

}