<?php

namespace App\Models\DocumentIndex;
use CodeIgniter\Model;

class DocumentIndexSelectModel extends Model {

    function __construct() {
        parent::__construct();
    }
    
    public function get_original_pdf_details($doc_id) {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs up');
        $builder->SELECT('max_index_no, up.file_path, up.page_no');
        $builder->JOIN('( SELECT max(ed.index_no) max_index_no FROM efil.tbl_efiled_docs ed WHERE ed.registration_id = registration_id ) a', '1 = 1', 'left');
        $builder->WHERE('up.doc_id', $doc_id);
        $builder->WHERE('up.uploaded_by', $_SESSION['login']['id']);
        $query = $builder->get(); 
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function current_working_file($case_file_id, $case_file_pdf_id) {
        $builder = $this->db->table('tbl_e_case_file_documents docs');
        $builder->SELECT('docs.*,doc_cat.doc_category,pdf.efiling_no,pdf.file_partial_path,pdf.is_imported');
        $builder->JOIN('m_tbl_casefile_doc_category doc_cat', 'docs.e_casefile_doc_cat_id = doc_cat.id');
        $builder->JOIN('tbl_e_case_file_pdfs pdf', 'docs.case_file_pdf_id = pdf.id', 'LEFT');
        $builder->WHERE('docs.e_casefile_id', $case_file_id);
        $builder->WHERE('docs.case_file_pdf_id', $case_file_pdf_id);
        $builder->WHERE('docs.created_by', $_SESSION['login']['id']);
        $builder->WHERE('docs.is_deleted', FALSE);
        $builder->orderBy('docs.id', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_path_size_name($registration_id) {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs ed');
        $builder->SELECT('file_name,file_path,doc_title,doc_hashed_value,file_size');  
        $builder->WHERE('ed.registration_id', $registration_id);
        $builder->WHERE('ed.is_active', TRUE);
        $builder->WHERE('ed.is_deleted', FALSE); 
        $builder->orderBy('ed.doc_id', 'desc'); 
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_index_items_list($registration_id) {
        $builder = $this->db->table('efil.tbl_efiled_docs ed');
        $builder->SELECT('ed.*,dm.docdesc');
        $builder->JOIN('icmis.docmaster dm', 'ed.doc_type_id = dm.doccode AND ed.sub_doc_type_id = dm.doccode1 and dm.display !=\'N\'');
        $builder->WHERE('ed.registration_id', $registration_id);
        $builder->WHERE('ed.is_active', TRUE);
        $builder->WHERE('ed.is_deleted', FALSE);
        $builder->orderBy('ed.index_no');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_index_details($registration_id,$doc_id) {
        $builder = $this->db->table('efil.tbl_efiled_docs ed');
        $builder->SELECT('ed.*,tup.page_no as total_pdf_pages,dm.docdesc');
        $builder->JOIN('icmis.docmaster dm', 'ed.doc_type_id = dm.doccode AND ed.sub_doc_type_id = dm.doccode1 and dm.display!=\'N\'');
        $builder->JOIN('efil.tbl_uploaded_pdfs tup', 'ed.pdf_id=tup.doc_id');
        $builder->WHERE('ed.registration_id', $registration_id);
        $builder->WHERE('ed.is_active', TRUE)->where('tup.is_active', TRUE);
        $builder->WHERE('ed.is_deleted', FALSE)->WHERE('tup.is_deleted', FALSE);
        if(!empty($doc_id)){
            $builder->WHERE('ed.doc_id',$doc_id);
        }
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function get_index_item_file($doc_id) {
        $builder = $this->db->table('efil.tbl_efiled_docs ed');
        $builder->SELECT('ed.file_path, ed.file_name, ed.doc_title');
        $builder->WHERE('ed.doc_id', $doc_id);
        $builder->WHERE('ed.is_active', TRUE);
        $builder->WHERE('ed.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }
    
    public function unfilled_pdf_pages_for_index($registration_id) {
        $sql="select * from (SELECT registration_id ,doc_id,page_no,doc_title FROM efil.tbl_uploaded_pdfs where  is_deleted='f' and registration_id=$registration_id) as a
        join (select registration_id,pdf_id,sum(page_no) as total,sum((end_page-st_page)+1) as pgcount from efil.tbl_efiled_docs where is_deleted='f' and registration_id=$registration_id GROUP BY 
        registration_id,pdf_id) as b on a.doc_id=b.pdf_id where a.page_no != b.total or a.page_no != b.pgcount
        union 
        SELECT registration_id,doc_id,null page_no,doc_title,null registration_id,null pdf_id,null total,null pgcount FROM efil.tbl_uploaded_pdfs where  is_deleted='f' and doc_id not in (select distinct pdf_id from efil.tbl_efiled_docs where  is_deleted='f' 
        and registration_id=$registration_id) and registration_id=$registration_id ";
        $query = $this->db->query($sql);
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function is_index_created($registration_id) {
        $builder = $this->db->table('efil.tbl_efiled_docs ed');
        $builder->SELECT('ed.registration_id, ed.doc_type_id');
        $builder->WHERE('ed.registration_id', $registration_id);
        $builder->WHERE('ed.is_active', TRUE);
        $builder->WHERE('ed.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }
    
    public function previous_index_file_count($pspdfkit_document_id) {
        $index_pg_diff_count="select * from efil.tbl_efiled_docs where is_deleted='f' and pspdfkit_document_id='$pspdfkit_document_id'";
        $query = $this->db->query($index_pg_diff_count);
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }
    
    public function delete_beforedit_index_file($doc_id) {
        $index_pg_diff_count="select * from efil.tbl_efiled_docs where is_deleted='f' and doc_id='$doc_id'";
        $query = $this->db->query($index_pg_diff_count);
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function is_check_index_item_file($registration_id,$doc_type_id,$sub_doc_type_id) {
        $builder = $this->db->table('efil.tbl_efiled_docs ed');
        $builder->SELECT('ed.file_path, ed.file_name, ed.doc_title');
        $builder->WHERE('ed.registration_id', $registration_id);
        $builder->WHERE('ed.doc_type_id', $doc_type_id);
        $builder->WHERE('ed.sub_doc_type_id', $sub_doc_type_id);
        $builder->WHERE('ed.is_active', TRUE);
        $builder->WHERE('ed.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

}