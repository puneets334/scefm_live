<?php

namespace App\Models\DocumentIndex;
use CodeIgniter\Model;

class DocumentIndexDropDownModel extends Model {

    function __construct() {
        parent::__construct();
    }

    function get_uploaded_pdfs($registration_id) {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs docs');
        $builder->SELECT('docs.doc_id,docs.doc_title,
                           docs.file_name,docs.page_no,
                           docs.uploaded_by,docs.uploaded_on,docs.upload_ip_address,docs.file_path,docs.doc_title,
                           docs.doc_hashed_value,docs.pspdfkit_document_id');
        $builder->WHERE('docs.registration_id', $registration_id);
        $builder->WHERE('docs.is_deleted', FALSE);
        $builder->WHERE('docs.is_active', TRUE);
        $builder->orderBy('docs.doc_id');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_document_type($ia_doc_type = NULL) {
        $builder = $this->db->table('icmis.docmaster');
        $builder->SELECT('*');
        if ($ia_doc_type != NULL) {
            $builder->WHERE('doccode', $ia_doc_type);
        } 
        if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
            if($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE) {
                $builder->whereIn('doccode', array(118));
            } else {
                $builder->whereIn('doccode', array(118,8));
            }
        }
        if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS || getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
            $builder->whereNotIn('doccode', array(118));
        }
        if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT || getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
            $builder->WHERE("display != 'N'" );
        } else {
            $builder->WHERE("display = 'Y'" );
        }
        $builder->WHERE('doccode1', 0);
        $builder->orderBy('docdesc');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray(); 
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_sub_document_type($doc_code,$doccode1=null) {
        $builder = $this->db->table('icmis.docmaster');
        $builder->SELECT('*');
        $builder->WHERE('doccode', $doc_code);
        if (!empty($doccode1) && $doccode1 !=null) {
            $builder->WHERE('doccode1',$doccode1);
        }
        $builder->whereNotIn('doccode1', [0]);
        $builder->WHERE("display = 'Y'" );
        if(getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
            $builder->WHERE('doccode1', 501);
        }
        if(getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
            $builder->whereNotIn('doccode1', [0, 19]);
        }
        $builder->orderBy('docdesc');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

}