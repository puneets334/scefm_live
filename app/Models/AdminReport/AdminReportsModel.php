<?php

namespace App\Models\AdminReport;

use Config\Database;
use CodeIgniter\Model;
class AdminReportsModel extends Model {
    protected $readOnlyDb;
    function __construct() {
        $this->readOnlyDb = Database::connect('readOnlyDb');
        parent::__construct();
    }

    public function getCountData($params=array()) {
        $output= false;
        if(isset($params['from_date']) && !empty($params['from_date']) && isset($params['to_date']) && !empty($params['to_date']) && isset($params['user_type']) && !empty($params['user_type']) && isset($params['type']) && !empty($params['type']) && isset($params['file_type_id']) && !empty($params['file_type_id'])) {
            $user_type = (int)$params['user_type'];
            $fileTypeIds = implode(',',$params['file_type_id']);
            $type = $params['type'];
            $builder = $this->db->table('efil.tbl_efiling_nums en');
            $builder->select('SUM(CASE WHEN en.ref_m_efiled_type_id = ' . E_FILING_TYPE_NEW_CASE . ' THEN 1 ELSE 0 END) as new,
                SUM(CASE WHEN en.ref_m_efiled_type_id = ' . E_FILING_TYPE_MISC_DOCS . ' THEN 1 ELSE 0 END) as misc,
                SUM(CASE WHEN en.ref_m_efiled_type_id = ' . E_FILING_TYPE_IA . ' THEN 1 ELSE 0 END) as ia,
                SUM(CASE WHEN en.ref_m_efiled_type_id = ' . E_FILING_TYPE_CAVEAT . ' THEN 1 ELSE 0 END) as caveat,
                SUM(CASE WHEN en.ref_m_efiled_type_id IN (' . $fileTypeIds . ') THEN 1 ELSE 0 END) as all');
            $builder->join('efil.tbl_efiling_num_status ens', 'en.registration_id = ens.registration_id', 'inner');
            $builder->whereIn('en.ref_m_efiled_type_id', $params['file_type_id']);
            $builder->where('DATE(ens.activated_on) >=', $params['from_date']);
            $builder->where('DATE(ens.activated_on) <=', $params['to_date']);
            $builder->where('en.is_active', true);
            $builder->where('ens.is_deleted', false);
            $builder->where('(ens.is_active = true OR ens.is_active = false)');
            switch ($user_type) {
                case USER_EFILING_ADMIN :
                    switch ($type) {
                        case 'file_allocated':
                            if(!empty($params['allocated_to'])) {
                                $builder->WHERE('en.allocated_to',$params['allocated_to']);
                            } else {
                                $builder->where('en.allocated_to IS NOT NULL');
                                $builder->where('en.allocated_to !=',0);
                            }
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_approved':
                            if(!empty($params['allocated_to'])) {
                                $builder->where('en.allocated_to',$params['allocated_to']);                                
                            } else {
                                $builder->where('en.allocated_to IS NOT NULL');
                                $builder->where('en.allocated_to IS NOT NULL');
                            }
                            $builder->where('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_rejected':
                            if(!empty($params['allocated_to'])) {
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
                            if(!empty($params['allocated_to'])) {
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

    public function getFileStatusWithFileType($params=array()) {
        $output = false;
        if (isset($params['from_date']) && !empty($params['from_date']) && isset($params['to_date']) && !empty($params['to_date'])
            && isset($params['user_type']) && !empty($params['user_type']) && isset($params['type']) && !empty($params['type'])) {
            $user_type = (int)$params['user_type'];
            $type = $params['type'];
            $builder = $this->readOnlyDb->table('efil.tbl_efiling_nums en');
            $builder->SELECT('en.registration_id ,en.ref_m_efiled_type_id,en.efiling_no,et.efiling_type,ens.stage_id,en.allocated_to,mtds.user_stage_name,
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
            
            $builder->JOIN('efil.tbl_efiling_num_status ens', 'en.registration_id=ens.registration_id', 'inner');
            $builder->JOIN('efil.tbl_case_details tcd','en.registration_id=tcd.registration_id','left');
            $builder->JOIN('public.tbl_efiling_caveat tec','en.registration_id=tec.ref_m_efiling_nums_registration_id','left');
            $builder->JOIN('efil.m_tbl_dashboard_stages mtds ','ens.stage_id=mtds.stage_id','inner');
            $builder->JOIN('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
            $builder->JOIN('efil.tbl_misc_docs_ia as mdia', 'en.registration_id = mdia.registration_id', 'left');
            $builder->JOIN('efil.tbl_sci_cases as sc_case', 'sc_case.diary_no=mdia.diary_no AND sc_case.diary_year = mdia.diary_year', 'left');

            $builder->whereIn('en.ref_m_efiled_type_id', $params['file_type_id']);
            $builder->WHERE('DATE(ens.activated_on) >=', $params['from_date']);
            $builder->WHERE('DATE(ens.activated_on) <=', $params['to_date']);
            $builder->WHERE('ens.is_deleted', FALSE);
            $builder->WHERE('en.is_active', TRUE);
            $builder->WHERE("(ens.is_active =TRUE OR ens.is_active = FALSE)");
            switch ($user_type) {
                case USER_EFILING_ADMIN :
                    switch ($type) {
                        case 'file_allocated':
                            if(isset($params['allocated_to']) && !empty($params['allocated_to'])){
                                $builder->WHERE('en.allocated_to',$params['allocated_to']);
                            }
                            else{
                                $builder->WHERE('en.allocated_to IS NOT NULL');
                                $builder->WHERE('en.allocated_to !=',0);
                            }
                            $builder->WHERE('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_approved':
                            if(isset($params['allocated_to']) && !empty($params['allocated_to'])){
                                $builder->WHERE('en.allocated_to',$params['allocated_to']);
                            }
                            else{
                                $builder->WHERE('en.allocated_to IS NOT NULL');
                                $builder->WHERE('en.allocated_to !=',0);
                            }
                            $builder->WHERE('ens.stage_id', $params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_rejected':
                            if(isset($params['allocated_to']) && !empty($params['allocated_to'])){
                                $builder->WHERE('en.allocated_to',$params['allocated_to']);
                            }
                            else{
                                $builder->WHERE('en.allocated_to IS NOT NULL');
                                $builder->WHERE('en.allocated_to !=',0);
                            }
                            $builder->WHERE('ens.stage_id', $params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_diaries':
                            if(isset($params['allocated_to']) && !empty($params['allocated_to'])){
                                $builder->WHERE('en.allocated_to',$params['allocated_to']);
                            }
                            else{
                                $builder->WHERE('en.allocated_to IS NOT NULL');
                                $builder->WHERE('en.allocated_to !=',0);
                            }
                            $builder->WHERE('ens.stage_id', $params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        default:

                    }
                    break;
                case USER_ADMIN :
                    switch ($type) {
                        case 'file_allocated':
                            $builder->WHERE('en.allocated_to',$params['login_user_id']);
                            $builder->WHERE('ens.stage_id',$params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_approved':
                            $builder->WHERE('en.allocated_to',$params['login_user_id']);
                            $builder->WHERE('ens.stage_id', $params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_rejected':
                            $builder->WHERE('en.allocated_to',$params['login_user_id']);
                            $builder->WHERE('ens.stage_id', $params['stage_id']);
                            $query = $builder->get();
                            $output = $query->getResultArray();
                            break;
                        case 'file_diaries':
                            $builder->WHERE('en.allocated_to',$params['login_user_id']);
                            $builder->WHERE('ens.stage_id', $params['stage_id']);
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
        $builder->select('en.allocated_to, CONCAT(u.first_name, \' \', u.last_name) as user_name');
        $builder->join('efil.tbl_users u', 'en.allocated_to = u.id', 'INNER');
        $builder->where('en.allocated_to IS NOT NULL');
        $builder->where('en.allocated_to !=', 0);
        $builder->groupBy(['en.allocated_to', 'u.first_name', 'u.last_name']);
        $query = $builder->get();
        $output = $query->getResultArray();
        return $output;
    }

    public function getCountDataNew($params=array()) {
        $output= false;
        if(isset($params['from_date']) && !empty($params['from_date']) && isset($params['to_date']) && !empty($params['to_date']) && isset($params['user_type']) && !empty($params['user_type']) && isset($params['type']) && !empty($params['type']) && isset($params['file_type_id']) && !empty($params['file_type_id'])) {
            $fileTypeIds = implode(',',$params['file_type_id']);
            $fileTypeIds = array_map('intval', explode(',', $fileTypeIds));
            $user_type = (int)$params['user_type'];
            $type = $params['type'];
            $fileTypeIds1 = $params['file_type_id'];
            array_shift($fileTypeIds1);
            $fileTypeIds1 = implode(',',$fileTypeIds1);
            // $this->readOnlyDb->select("
            // SUM(CASE WHEN ref_m_efiled_type_id =".E_FILING_TYPE_NEW_CASE." AND sc_diary_num IS NOT NULL THEN 1 ELSE 0 END) as new,
            // SUM(CASE WHEN ref_m_efiled_type_id = ".E_FILING_TYPE_MISC_DOCS." THEN 1 ELSE 0 END) as misc,
            // SUM(CASE WHEN ref_m_efiled_type_id = ".E_FILING_TYPE_IA." THEN 1 ELSE 0 END) as ia,
            // SUM(CASE WHEN ref_m_efiled_type_id = ".E_FILING_TYPE_CAVEAT." THEN 1 ELSE 0 END) as caveat,
            // SUM(CASE WHEN (ref_m_efiled_type_id = ".E_FILING_TYPE_NEW_CASE." AND sc_diary_num IS NOT NULL) OR ref_m_efiled_type_id IN ($fileTypeIds1) THEN 1 ELSE 0 END) as all
            // ");
            //  $this->readOnlyDb->from("
            // (SELECT DISTINCT en.ref_m_efiled_type_id, tcd.sc_diary_num, en.efiling_no
            //  FROM efil.tbl_efiling_nums en
            //  INNER JOIN efil.tbl_efiling_num_status ens ON en.registration_id = ens.registration_id
            //  LEFT JOIN efil.tbl_case_details tcd ON en.registration_id = tcd.registration_id
            //  WHERE en.ref_m_efiled_type_id IN ($fileTypeIds)
            //    AND DATE(ens.activated_on) >= '".$params['from_date']."'
            //    AND DATE(ens.activated_on) <= '".$params['to_date']."'
            //    AND en.is_active = TRUE
            //    AND ens.is_deleted = FALSE
            //    AND ens.stage_id = '".$params['stage_id']."'
            //  ) aa");
            // $query =$this->readOnlyDb->get();
            // $output = $query->result_array();

            // Assuming $fileTypeIds and $fileTypeIds1 are comma-separated strings of IDs
            // and $params contains 'from_date', 'to_date', and 'stage_id'

            $builder = $this->readOnlyDb->table('efil.tbl_efiling_nums en');

            $builder->select("
                SUM(CASE WHEN en.ref_m_efiled_type_id = " . E_FILING_TYPE_NEW_CASE . " AND tcd.sc_diary_num IS NOT NULL THEN 1 ELSE 0 END) as new,
                SUM(CASE WHEN en.ref_m_efiled_type_id = " . E_FILING_TYPE_MISC_DOCS . " THEN 1 ELSE 0 END) as misc,
                SUM(CASE WHEN en.ref_m_efiled_type_id = " . E_FILING_TYPE_IA . " THEN 1 ELSE 0 END) as ia,
                SUM(CASE WHEN en.ref_m_efiled_type_id = " . E_FILING_TYPE_CAVEAT . " THEN 1 ELSE 0 END) as caveat,
                SUM(CASE WHEN (en.ref_m_efiled_type_id = " . E_FILING_TYPE_NEW_CASE . " AND tcd.sc_diary_num IS NOT NULL) OR en.ref_m_efiled_type_id IN ($fileTypeIds1) THEN 1 ELSE 0 END) as all
            ")
            ->distinct()
            ->join('efil.tbl_efiling_num_status ens', 'en.registration_id = ens.registration_id', 'inner')
            ->join('efil.tbl_case_details tcd', 'en.registration_id = tcd.registration_id', 'left')
            ->whereIn('en.ref_m_efiled_type_id', $fileTypeIds)
            ->where('DATE(ens.activated_on) >=', $params['from_date'])
            ->where('DATE(ens.activated_on) <=', $params['to_date'])
            ->where('en.is_active', TRUE)
            ->where('ens.is_deleted', FALSE)
            ->where('ens.stage_id', $params['stage_id']);
            $query = $builder->get();
            $output = $query->getResultArray();
        }
        return $output;
    }

    public function getGovtCountDataNew($params=array()) {
        $output= false;
        if(isset($params['from_date']) && !empty($params['from_date']) && isset($params['to_date']) && !empty($params['to_date']) && isset($params['user_type']) && !empty($params['user_type']) && isset($params['type']) && !empty($params['type']) && isset($params['file_type_id']) && !empty($params['file_type_id'])) {
            $fileTypeIds = implode(',',$params['file_type_id']);
            $user_type = (int)$params['user_type'];
            $type = $params['type'];
            $fileTypeIds1 = $params['file_type_id'];
            array_shift($fileTypeIds1);
            $fileTypeIds1 = implode(',',$fileTypeIds1);
            $sql="select
            SUM(CASE WHEN ref_m_efiled_type_id =".E_FILING_TYPE_NEW_CASE." AND sc_diary_num IS NOT NULL THEN 1 ELSE 0 END) as new,
            SUM(CASE WHEN ref_m_efiled_type_id = ".E_FILING_TYPE_MISC_DOCS." THEN 1 ELSE 0 END) as misc,
            SUM(CASE WHEN ref_m_efiled_type_id = ".E_FILING_TYPE_IA." THEN 1 ELSE 0 END) as ia,
            SUM(CASE WHEN ref_m_efiled_type_id = ".E_FILING_TYPE_CAVEAT." THEN 1 ELSE 0 END) as caveat,
            SUM(CASE WHEN (ref_m_efiled_type_id = ".E_FILING_TYPE_NEW_CASE." AND sc_diary_num IS NOT NULL) OR ref_m_efiled_type_id IN ($fileTypeIds1) THEN 1 ELSE 0 END) as all
            FROM
            (select distinct en.ref_m_efiled_type_id,a.sc_diary_num,en.efiling_no from
            (select  registration_id,ref_m_efiled_type_id,null sc_diary_num from efil.tbl_misc_docs_ia ia where is_govt_filing!=0 and ref_m_efiled_type_id in (2,4)
            union
            select  registration_id, 1 as ref_m_efiled_type_id,sc_diary_num from efil.tbl_case_details where is_govt_filing!=0
            union
            select ref_m_efiling_nums_registration_id, 12 as ref_m_efiled_type_id,null from public.tbl_efiling_caveat where is_govt_filing!=0
            )a
            join efil.tbl_efiling_nums en on a.registration_id=en.registration_id
            inner join efil.tbl_efiling_num_status ens on en.registration_id = ens.registration_id
            left join efil.tbl_case_details tcd on en.registration_id=tcd.registration_id
            WHERE en.ref_m_efiled_type_id IN ($fileTypeIds)
               AND DATE(ens.activated_on) >= '".$params['from_date']."'
               AND DATE(ens.activated_on) <= '".$params['to_date']."'
              and en.is_active = true
              and ens.is_deleted = false
              AND ens.stage_id = '".$params['stage_id']."'
             ) aa";
            $query =$this->db->query($sql);
            $output = $query->getResultArray();
        }
        return $output;
    }

}