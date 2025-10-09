<?php

namespace App\Models\AdminReport;
use CodeIgniter\Model;

class AdminReportModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function getCountData($params=array()) {
        $output= false;
        if(isset($params['from_date']) && !empty($params['from_date']) && isset($params['to_date']) && !empty($params['to_date']) && isset($params['user_type']) && !empty($params['user_type']) && isset($params['type']) && !empty($params['type']) && isset($params['file_type_id']) && !empty($params['file_type_id'])) {
            $user_type = (int)$params['user_type'];
            $fileTypeIds = implode(',',$params['file_type_id']);
            $type = $params['type'];
            $builder = $this->db->table('efil.tbl_efiling_nums en');
            $builder->select('SUM( CASE WHEN en.ref_m_efiled_type_id = '.E_FILING_TYPE_NEW_CASE.' THEN 1 ELSE 0 END) as new,
                SUM( CASE WHEN en.ref_m_efiled_type_id = '.E_FILING_TYPE_MISC_DOCS.' THEN 1 ELSE 0 END) as misc,
                SUM( CASE WHEN en.ref_m_efiled_type_id = '.E_FILING_TYPE_IA.' THEN 1 ELSE 0 END) as ia,
                SUM( CASE WHEN en.ref_m_efiled_type_id = '.E_FILING_TYPE_CAVEAT.' THEN 1 ELSE 0 END) as caveat,
                SUM( CASE WHEN en.ref_m_efiled_type_id IN ('.$fileTypeIds.') THEN 1 ELSE 0 END) as all
            ');
            $builder->join('efil.tbl_efiling_num_status ens','en.registration_id=ens.registration_id','inner');
            $builder->whereIn('en.ref_m_efiled_type_id',$params['file_type_id']);
            if($type!='file_pending_diary_or_document') {
                if(!empty($params['from_date']) && $params['from_date'] !=NULL) {
                    $builder->where('DATE(ens.activated_on) >=', $params['from_date']);
                }
                if(!empty($params['to_date']) && $params['to_date'] !=null) {
                    $builder->where('DATE(ens.activated_on) <=', $params['to_date']); 
                }
            }
            if($type!='file_pending_diary_or_document') {
                $builder->where('DATE(ens.activated_on) >=',$params['from_date']);
                $builder->where('DATE(ens.activated_on) <=',$params['to_date']);
            }
            $builder->where('en.is_active',TRUE);
            $builder->where('ens.is_deleted',FALSE);
            $builder->where("(ens.is_active =TRUE OR ens.is_active = FALSE)");
            switch ($user_type) {
                case USER_EFILING_ADMIN :
                    switch ($type) {
                        case 'file_allocated':
                            if(isset($params['allocated_to']) && !empty($params['allocated_to'])) {
                                $builder->where('en.allocated_to',$params['allocated_to']);
                            } else {
                                $builder->where('en.allocated_to IS NOT NULL');
                                $builder->where('en.allocated_to !=',0);
                            }
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_approved':
                            if(isset($params['allocated_to']) && !empty($params['allocated_to'])) {
                                $builder->where('en.allocated_to',$params['allocated_to']);
                            } else {
                                $builder->where('en.allocated_to IS NOT NULL');
                                $builder->where('en.allocated_to !=',0);
                            }
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_rejected':
                            if(isset($params['allocated_to']) && !empty($params['allocated_to'])) {
                                $builder->where('en.allocated_to',$params['allocated_to']);
                            } else {
                                $builder->where('en.allocated_to IS NOT NULL');
                                $builder->where('en.allocated_to !=',0);
                            }
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_diaries':
                            if(isset($params['allocated_to']) && !empty($params['allocated_to'])){
                                $builder->where('en.allocated_to',$params['allocated_to']);
                            } else {
                                $builder->where('en.allocated_to IS NOT NULL');
                                $builder->where('en.allocated_to !=',0);
                            }
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_pending_diary_or_document':
                            if(isset($params['allocated_to']) && !empty($params['allocated_to'])) {
                                $builder->where('en.allocated_to',$params['allocated_to']);
                            } else {
                                $builder->where('en.allocated_to IS NOT NULL');
                                $builder->where('en.allocated_to !=',0);
                            }
                            $builder->whereIN('ens.stage_id',array('2','3','4'));
                            $builder->where('ens.is_active',TRUE);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        default:
                    }
                    break;
                case USER_ADMIN :
                    switch ($type) {
                        case 'file_allocated':
                            $builder->where('en.allocated_to',$params['login_user_id']);
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_approved':
                            $builder->where('en.allocated_to',$params['login_user_id']);
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_rejected':
                            $builder->where('en.allocated_to',$params['login_user_id']);
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_diaries':
                            $builder->where('en.allocated_to',$params['login_user_id']);
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_pending_diary_or_document':
                            $builder->where('en.allocated_to',$params['login_user_id']);
                            $builder->whereIN('ens.stage_id',array('2','3','4'));
                            $builder->where('ens.is_active',TRUE);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        default:
                    }
                    break;
                default:
            }
        }
        return $output;
    }

    public function getFileStatusWithFileType($params=array()) {
        $output = false;
        if (isset($params['from_date']) && !empty($params['from_date']) && isset($params['to_date']) && !empty($params['to_date']) && isset($params['user_type']) && !empty($params['user_type']) && isset($params['type']) && !empty($params['type'])) {
            $user_type = (int)$params['user_type'];
            $type = $params['type'];
            $builder = $this->db->table('efil.tbl_efiling_nums en');
            $builder->select('en.registration_id ,en.ref_m_efiled_type_id,en.efiling_no,et.efiling_type,ens.stage_id,en.allocated_to,mtds.user_stage_name,
                ens.activated_on,
                CASE
                WHEN en.ref_m_efiled_type_id = '.E_FILING_TYPE_NEW_CASE.' THEN tcd.cause_title
                WHEN en.ref_m_efiled_type_id = '.E_FILING_TYPE_MISC_DOCS.' THEN NULL
                WHEN en.ref_m_efiled_type_id = '.E_FILING_TYPE_IA.' THEN NULL
                WHEN en.ref_m_efiled_type_id = '.E_FILING_TYPE_CAVEAT.' THEN CONCAT(tec.pet_name, \' Vs. \', tec.res_name)
                ELSE NULL END
                as cause_title  ,
                CASE
                WHEN en.ref_m_efiled_type_id = '.E_FILING_TYPE_NEW_CASE.' THEN CONCAT(tcd.sc_diary_num,tcd.sc_diary_year)
                WHEN en.ref_m_efiled_type_id = '.E_FILING_TYPE_CAVEAT.' THEN  CONCAT(tec.caveat_num,tec.caveat_year) 
                ELSE NULL END as diaryCaveatNo,CONCAT(sc_case.diary_no,sc_case.diary_year) as misc_docs_ia_diaryNo    
            ');
            $builder->join('efil.tbl_efiling_num_status ens', 'en.registration_id=ens.registration_id', 'inner');
            $builder->join('efil.tbl_case_details tcd','en.registration_id=tcd.registration_id','left');
            $builder->join('public.tbl_efiling_caveat tec','en.registration_id=tec.ref_m_efiling_nums_registration_id','left');
            $builder->join('efil.m_tbl_dashboard_stages mtds ','ens.stage_id=mtds.stage_id','inner');
            $builder->join('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
            $builder->join('efil.tbl_misc_docs_ia as mdia', 'en.registration_id = mdia.registration_id', 'left');
            $builder->join('efil.tbl_sci_cases as sc_case', 'sc_case.diary_no=mdia.diary_no AND sc_case.diary_year = mdia.diary_year', 'left');
            $builder->whereIN('en.ref_m_efiled_type_id', $params['file_type_id']);
            if($type!='file_pending_diary_or_document'){
                if(!empty($params['from_date']) && $params['from_date'] !=NULL) {
                    $builder->where('DATE(ens.activated_on) >=', $params['from_date']);
                }
                if(!empty($params['to_date']) && $params['to_date'] !=null) {
                    $builder->where('DATE(ens.activated_on) <=', $params['to_date']); 
                }
            }
            $builder->where('ens.is_deleted', FALSE);
            $builder->where('en.is_active', TRUE);
            $builder->where("(ens.is_active =TRUE OR ens.is_active = FALSE)");
            switch ($user_type) {
                case USER_EFILING_ADMIN :
                    switch ($type) {
                        case 'file_allocated':
                            if(isset($params['allocated_to']) && !empty($params['allocated_to'])) {
                                $builder->where('en.allocated_to',$params['allocated_to']);
                            } else {
                                $builder->where('en.allocated_to IS NOT NULL');
                                $builder->where('en.allocated_to !=',0);
                            }
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_approved':
                            if(isset($params['allocated_to']) && !empty($params['allocated_to'])) {
                                $builder->where('en.allocated_to',$params['allocated_to']);
                            } else {
                                $builder->where('en.allocated_to IS NOT NULL');
                                $builder->where('en.allocated_to !=',0);
                            }
                            $builder->where('ens.stage_id', $params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_rejected':
                            if(isset($params['allocated_to']) && !empty($params['allocated_to'])) {
                                $builder->where('en.allocated_to',$params['allocated_to']);
                            } else {
                                $builder->where('en.allocated_to IS NOT NULL');
                                $builder->where('en.allocated_to !=',0);
                            }
                            $builder->where('ens.stage_id', $params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_diaries':
                            if(isset($params['allocated_to']) && !empty($params['allocated_to'])){
                                $builder->where('en.allocated_to',$params['allocated_to']);
                            } else {
                                $builder->where('en.allocated_to IS NOT NULL');
                                $builder->where('en.allocated_to !=',0);
                            }
                            $builder->where('ens.stage_id', $params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_pending_diary_or_document':
                            if(isset($params['allocated_to']) && !empty($params['allocated_to'])){
                                $builder->where('en.allocated_to',$params['allocated_to']);
                            } else {
                                $builder->where('en.allocated_to IS NOT NULL');
                                $builder->where('en.allocated_to !=',0);
                            }
                            $builder->whereIN('ens.stage_id',array('2','3','4'));
                            $builder->where('ens.is_active',TRUE);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        default:
                    }
                    break;
                case USER_ADMIN :
                    switch ($type) {
                        case 'file_allocated':
                            $builder->where('en.allocated_to',$params['login_user_id']);
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_approved':
                            $builder->where('en.allocated_to',$params['login_user_id']);
                            $builder->where('ens.stage_id', $params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_rejected':
                            $builder->where('en.allocated_to',$params['login_user_id']);
                            $builder->where('ens.stage_id', $params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_diaries':
                            $builder->where('en.allocated_to',$params['login_user_id']);
                            $builder->where('ens.stage_id', $params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_pending_diary_or_document':
                            $builder->where('en.allocated_to',$params['login_user_id']);
                            $builder->whereIN('ens.stage_id',array('2','3','4'));
                            $builder->where('ens.is_active',TRUE);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        default:
                    }
                    break;
                default:
            }
            return $output;
        }
    }

    public function getAllocatedUserDetails() {
        $output = false;
        $builder = $this->db->table('efil.tbl_efiling_nums en');
        $builder->select('en.allocated_to,CONCAT(u.first_name,\'\' , u.last_name) as user_name');
        $builder->join('efil.tbl_users u','en.allocated_to = u.id','INNER');
        $builder->where('en.allocated_to IS NOT NULL');
        $builder->where('en.allocated_to !=',0);
        $builder->groupBy(['en.allocated_to','u.first_name','u.last_name']);
        $query = $builder->get();
        $output = $query->getResultArray();
        return $output;
    }

    public function getCountDataNew($params=array()) {
        $output= false;
        if(isset($params['file_type_id']) && !empty($params['file_type_id'])) {
            $user_type = (int)$params['user_type'];
            $fileTypeIds = implode(',',$params['file_type_id']);
            $type = $params['type'];
            $builder = $this->db->table('efil.tbl_efiling_nums en');
            $builder->join('efil.tbl_efiling_num_status ens','en.registration_id=ens.registration_id','inner');
            $builder->whereIN('en.ref_m_efiled_type_id',$params['file_type_id']);
            $builder->where('en.is_active',TRUE);
            $builder->where('ens.is_deleted',FALSE);
            $builder->where("(ens.is_active =TRUE OR ens.is_active = FALSE)");
            switch ($user_type) {
                case USER_EFILING_ADMIN :
                    switch ($type) {
                        case 'file_allocated':
                            if(isset($params['allocated_to']) && !empty($params['allocated_to'])){
                                $builder->where('en.allocated_to',$params['allocated_to']);
                            } else {
                                $builder->where('en.allocated_to IS NOT NULL');
                                $builder->where('en.allocated_to !=',0);
                            }
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_approved':
                            if(isset($params['allocated_to']) && !empty($params['allocated_to'])){
                                $builder->where('en.allocated_to',$params['allocated_to']);
                            } else {
                                $builder->where('en.allocated_to IS NOT NULL');
                                $builder->where('en.allocated_to !=',0);
                            }
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_rejected':
                            if(isset($params['allocated_to']) && !empty($params['allocated_to'])){
                                $builder->where('en.allocated_to',$params['allocated_to']);
                            } else {
                                $builder->where('en.allocated_to IS NOT NULL');
                                $builder->where('en.allocated_to !=',0);
                            }
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_diaries':
                            if(isset($params['allocated_to']) && !empty($params['allocated_to'])){
                                $builder->where('en.allocated_to',$params['allocated_to']);
                            } else {
                                $builder->where('en.allocated_to IS NOT NULL');
                                $builder->where('en.allocated_to !=',0);
                            }
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        default:
                    }
                    break;
                case USER_ADMIN :
                    switch ($type) {
                        case 'file_allocated':
                            $builder->where('en.allocated_to',$params['login_user_id']);
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_approved':
                            $builder->where('en.allocated_to',$params['login_user_id']);
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_rejected':
                            $builder->where('en.allocated_to',$params['login_user_id']);
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_diaries':
                            $builder->where('en.allocated_to',$params['login_user_id']);
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        default:
                    }
                    break;
                default:
            }
        }
        return $output;
    }

}