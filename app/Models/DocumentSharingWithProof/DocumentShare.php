<?php

namespace App\Models\DocumentSharingWithProof;
use CodeIgniter\Model;

class DocumentShare extends Model {

    function __construct() {
        parent :: __construct();
    }

    function genDocumentSharingEfilingNumber() {
        $builder = $this->db->table('efil.m_tbl_efiling_no');
        $builder->SELECT('doc_sharing_efiling_no,doc_sharing_efiling_year');
        $builder->WHERE('entry_for_type', $_SESSION['estab_details']['efiling_for_type_id']);
        $builder->WHERE('ref_m_establishment_id', $_SESSION['estab_details']['efiling_for_id']);
        $query = $builder->get();
        $row = $query->getResultArray();
        $p_efiling_num = $row[0]['doc_sharing_efiling_no'];
        $year = $row[0]['doc_sharing_efiling_year'];
        if ($year < date('Y')) {
            $newYear = date('Y');
            $update_data = array(
                'doc_sharing_efiling_no' => 1,
                'doc_sharing_efiling_year' => $newYear,
                'doc_sharing_updated_by' => $_SESSION['login']['id'],
                'doc_sharing_updated_on' => date('Y-m-d H:i:s'),
                'doc_sharing_updated_by_ip' => getClientIP()
            );
            $builder->WHERE('doc_sharing_efiling_no', $p_efiling_num);
            $builder->WHERE('doc_sharing_efiling_year', $year);
            $builder->WHERE('entry_for_type', $_SESSION['estab_details']['efiling_for_type_id']);
            $builder->WHERE('ref_m_establishment_id', $_SESSION['estab_details']['efiling_for_id']);
            $builder->UPDATE($update_data);
            if ($this->db->affectedRows() > 0) {
                $data['doc_sharing_efiling_no'] = 1;
                $data['doc_sharing_efiling_year'] = $newYear;
                return $data;
            } else {
                $this->genDocumentSharingEfilingNumber();
            }
        } else {
            $gen_efiling_num = $p_efiling_num + 1;
            $efiling_num_info = array(
                'doc_sharing_efiling_no' => $gen_efiling_num,
                'doc_sharing_updated_by' => $_SESSION['login']['id'],
                'doc_sharing_updated_on' => date('Y-m-d H:i:s'),
                'doc_sharing_updated_by_ip' => getClientIP()
            );
            $builder->WHERE('doc_sharing_efiling_no', $p_efiling_num);
            $builder->WHERE('entry_for_type', $_SESSION['estab_details']['efiling_for_type_id']);
            $builder->WHERE('ref_m_establishment_id', $_SESSION['estab_details']['efiling_for_id']);
            $result = $builder->UPDATE($efiling_num_info);
            if ($this->db->affectedRows() > 0) {
                $data['doc_sharing_efiling_no'] = $gen_efiling_num;
                $data['doc_sharing_efiling_year'] = $year;
                return $data;
            } else {
                $this->genDocumentSharingEfilingNumber();
            }
        }
    }

    public function UserListByIdArr($params=array()) {
        $output= false;
        if(isset($params['idArr']) && !empty($params['idArr'])) {
            $builder = $this->db->table('efil.tbl_users');
            $builder->SELECT('id,moblie_number, first_name,emailid');
            $builder->whereIn('id',$params['idArr']);
            $builder->WHERE('is_active','1');
            $query = $builder->get();
            $output = $query->getResultArray();
        }
        return $output;
    }

    public  function insertBatchData($table,$batchArray) {
        $output= false;
        if(isset($table) && !empty($table) && isset($batchArray) && !empty($batchArray)) {
            $builder = $this->db->table($table);
            $builder->insertBatch($batchArray);
            if ($this->db->insertID()) {
                $output = $this->db->insertID();
            }
        }
        return $output;
    }

    public function docShareDetailsByDiaryNo($params = array()) {
        $output = false;
        if(isset($params['user_id']) && !empty($params['user_id'])) {
            $builder = $this->db->table('efil.tbl_doc_share_from f');
            $builder->SELECT('f.diary_no,trim(array_agg(f.shared_uniq_id)::text,\'{}\') shared_uniq_id,trim(array_agg(f.share_doc_id)::text,\'{}\') share_doc_id');
            $builder->WHERE('f.user_id',$params['user_id']);
            $builder->WHERE('f.is_active','1');
            $builder->groupBy('f.diary_no');
            $query = $builder->get();
            $output = $query->getResultArray();
        }
        return $output;
    }

    public function getFromUserDocShareDetails($params = array()) {
        $output = false;
        if(isset($params['user_id']) && !empty($params['user_id']) && !empty($params['share_doc_id']) && isset($params['share_doc_id'])) {
            $builder = $this->db->table('efil.tbl_doc_share_from f');
            $builder->DISTINCT();
            $builder->SELECT('f.user_id as from_user_id,f.created_on,t.doc_id,t.sub_doc_id,ed.registration_id,ed.doc_title,f.created_ip ,
            f.diary_no ,f.diary_year ,f.case_type ,f.case_no ,f.case_year ,
            t.share_doc_id,tu.aor_code,concat(tu.first_name,tu.last_name) as name');
            $builder->JOIN('efil.tbl_doc_share_to t','f.id = t.doc_share_from_id');
            $builder->JOIN('efil.tbl_users tu','tu.id = f.user_id','left');
            $builder->JOIN('efil.tbl_efiled_docs ed','ed.doc_id = t.share_doc_id');
            $builder->WHERE('t.is_active','1');
            $builder->WHERE('ed.is_deleted is false');
            $builder->whereIn('t.user_id ',$params['user_id']);
            $builder->WHERE('t.share_doc_id ',(int)$params['share_doc_id']);
            $query = $builder->get();
            $output = $query->getResultArray();
        }
        return $output;
    }

    public function getUsersDetailsByShareUniqueId($params = array()) {
        $output = false;
        if(isset($params['shared_uniq_id']) && !empty($params['shared_uniq_id'])) {
            $builder = $this->db->table('efil.tbl_doc_share_to t');
            $builder->SELECT('t.*,tu.ref_m_usertype_id');
            $builder->JOIN('efil.tbl_users tu','on t.user_id = tu.id','left');
            $builder->WHERE('t.shared_uniq_id',$params['shared_uniq_id']);
            $builder->WHERE('t.is_active',true);
            $builder->orderBy('t.name','ASC');
            $query = $builder->get();
            $output = $query->getResultArray();
        }
        return $output;
    }

    public function getShareDocumentDetails($params=array()) {
        $output = false;
        $builder = $this->db->table('icmis.docmaster d');
        if(isset($params['doc_id']) && !empty($params['doc_id']) && isset($params['sub_doc_id']) && !empty($params['sub_doc_id'])) {
            $builder->SELECT('d.doccode,d.doccode1,d.docdesc');
            $builder->WHERE('d.doccode',$params['doc_id']);
            $builder->WHERE('d.doccode1',$params['sub_doc_id']);
            $builder->WHERE('d.doccode1 !=',0);
            $builder->WHERE('d.display !=','N');
            $query = $builder->get();
            $output = $query->getResultArray();
        } else  if(isset($params['doc_id']) && !empty($params['doc_id'])) {
            $builder->SELECT('d.doccode,d.doccode1,d.docdesc');
            $builder->WHERE('d.doccode',$params['doc_id']);
            $builder->WHERE('d.doccode1',0);
            $builder->WHERE('d.display !=','N');
            $query = $builder->get();
            $output = $query->getResultArray();
        }
        return $output;
    }

    function getEfiledDoc($icmis_diary_no) {
        $output = false;
        if(isset($icmis_diary_no) && !empty($icmis_diary_no)) {
            $builder = $this->db->table('efil.tbl_efiled_docs ed');
            $builder->SELECT('ed.doc_id,ed.doc_title,ed.file_name,ed.file_path,ed.doc_title,ed.doc_type_id,ed.sub_doc_type_id,ed.uploaded_on');
            $builder->WHERE('ed.icmis_diary_no', $icmis_diary_no);
            $builder->WHERE('ed.is_deleted', FALSE);
            $builder->WHERE('ed.is_active', TRUE);
            $builder->orderBy('ed.doc_id');
            $query = $builder->get();
            $output = $query->getResultArray();
        }
        return $output;
    }

    function getDocDetailsByDocIdArr($params = array()) {
        $output = false;
        if(isset($params['docIdArr']) && !empty($params['docIdArr']) && count($params['docIdArr'])>0) {
            $builder = $this->db->table('efil.tbl_efiled_docs ted');
            $builder->SELECT('ted.doc_id,ted.registration_id,ted.efiled_type_id,ted.doc_type_id,ted.sub_doc_type_id,ted.file_name,ted.file_path
            ,ted.doc_title,ted.uploaded_by,ted.uploaded_on,tu.aor_code,tu.first_name,tu.last_name');
            $builder->JOIN('efil.tbl_users tu', 'ted.uploaded_by=tu.id');
            $builder->whereIn('doc_id', $params['docIdArr']);
            $query = $builder->get();
            $output =  $query->getResultArray();
        }
        return $output;
    }

    public function geShareDetailsGroupWose($params = array()) {
        $output = false;
        if(isset($params['shared_uniq_id']) && !empty($params['shared_uniq_id'])) {
            $builder = $this->db->table('efil.tbl_doc_share_to t');
            $builder->SELECT('t.*');
            $builder->JOIN('efil.tbl_doc_share_from f', 'f.id=t.doc_share_from_id');
            $builder->JOIN('efil.tbl_users tu', 't.user_id=tu.id','left');
            $builder->WHERE('t.shared_uniq_id', (int)$params['shared_uniq_id']);
            $builder->WHERE('t.is_active',true);
            $builder->WHERE('f.is_active',true);
            $query = $builder->get();
            $output =  $query->getResult();
        }
        return $output;
    }

    function getEfiledDocByDocId($params = array()) {
        $output = false;
        if(isset($params['docIdArr']) && !empty($params['docIdArr'])) {
            $builder = $this->db->table('efil.tbl_efiled_docs ed');
            $builder->SELECT('ed.doc_id,ed.doc_title,ed.file_name,ed.file_path,ed.doc_title,ed.doc_type_id,ed.sub_doc_type_id,ed.uploaded_on');
            $builder->whereIn('ed.doc_id', $params['docIdArr']);
            $builder->WHERE('ed.is_deleted', FALSE);
            $builder->WHERE('ed.is_active', TRUE);
            $builder->orderBy('ed.doc_id');
            $query = $builder->get();
            $output = $query->getResultArray();
        }
        return $output;
    }

    public function getFromUser($params = array()) {
        $output = false;
        if(isset($params['shared_uniq_id']) && !empty($params['shared_uniq_id']) && isset($params['user_id']) && !empty($params['user_id'])) {
            $builder = $this->db->table('efil.tbl_doc_share_from f');
            $builder->SELECT('f.*');
            $builder->WHERE('f.shared_uniq_id',(int)$params['shared_uniq_id']);
            $builder->WHERE('f.is_active',true);
            $builder->WHERE('f.user_id',(int)$params['user_id']);
            $query = $builder->get();
            $output = $query->getResult();
        }
        return $output;
    }

}