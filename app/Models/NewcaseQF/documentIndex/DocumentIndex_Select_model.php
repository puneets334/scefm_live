<?php

namespace App\Models\NewCaseQF\DocumentIndex;

use CodeIgniter\Model;

class DocumentIndex_Select_model extends Model
{

    function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
    }

    public function get_original_pdf_details($doc_id)
    {
        // Build query using Query Builder
        $builder = $this->db->table('tbl_uploaded_pdfs up');
        $builder->select('max_index_no, up.file_path, up.page_no');
        $builder->join('(SELECT MAX(ed.index_no) AS max_index_no FROM tbl_efiled_docs ed WHERE ed.registration_id = up.registration_id) a', '1 = 1', 'left');
        $builder->where('up.doc_id', $doc_id);
        $builder->where('up.uploaded_by', $_SESSION['login']['id']);

        // Execute query
        $query = $builder->get();

        // Check if results are found
        if ($query->getNumRows() >= 1) {
            // Fetch all results and return them as an array of objects
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    public function current_working_file($case_file_id, $case_file_pdf_id)
    {
        // Build query using Query Builder
        $builder = $this->db->table('tbl_e_case_file_documents docs');
        $builder->select('docs.*, doc_cat.doc_category, pdf.efiling_no, pdf.file_partial_path, pdf.is_imported');
        $builder->join('m_tbl_casefile_doc_category doc_cat', 'docs.e_casefile_doc_cat_id = doc_cat.id');
        $builder->join('tbl_e_case_file_pdfs pdf', 'docs.case_file_pdf_id = pdf.id', 'left');
        $builder->where('docs.e_casefile_id', $case_file_id);
        $builder->where('docs.case_file_pdf_id', $case_file_pdf_id);
        $builder->where('docs.created_by', $_SESSION['login']['id']);
        $builder->where('docs.is_deleted', FALSE);
        $builder->orderBy('docs.id', 'ASC');

        // Execute query
        $query = $builder->get();

        // Check if results are found
        if ($query->getNumRows() >= 1) {
            // Fetch all results and return them as an array of associative arrays
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    public function get_index_items_list($registration_id)
    {
        // Initialize Query Builder
        $builder = $this->db->table('efil.tbl_efiled_docs ed');

        // Select columns to retrieve
        $builder->select('ed.*, dm.docdesc');

        // Perform JOIN with 'icmis.docmaster' table to fetch document description
        $builder->join('icmis.docmaster dm', 'ed.doc_type_id = dm.doccode AND ed.sub_doc_type_id = dm.doccode1');
        $builder->where('dm.display !=', 'N'); // Exclude documents with display 'N'

        // Set WHERE conditions
        $builder->where('ed.registration_id', $registration_id);
        $builder->where('ed.is_active', TRUE);
        $builder->where('ed.is_deleted', FALSE);

        // Order the results by 'index_no'
        $builder->orderBy('ed.index_no');

        // Execute the query
        $query = $builder->get();

        // Check if results are found
        if ($query->getNumRows() >= 1) {
            // Fetch all results and return them as an array of associative arrays
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    public function get_index_details($registration_id, $doc_id)
    {
        // Initialize Query Builder
        $builder = $this->db->table('efil.tbl_efiled_docs ed');

        // Select columns to retrieve
        $builder->select('ed.*, tup.page_no as total_pdf_pages, dm.docdesc');

        // Perform JOIN with 'icmis.docmaster' and 'efil.tbl_uploaded_pdfs' tables
        $builder->join('icmis.docmaster dm', 'ed.doc_type_id = dm.doccode AND ed.sub_doc_type_id = dm.doccode1');
        $builder->join('efil.tbl_uploaded_pdfs tup', 'ed.pdf_id = tup.doc_id');

        // Set WHERE conditions
        $builder->where('ed.registration_id', $registration_id);
        $builder->where('ed.is_active', TRUE);
        $builder->where('tup.is_active', TRUE);
        $builder->where('ed.is_deleted', FALSE);
        $builder->where('tup.is_deleted', FALSE);
        $builder->where('ed.doc_id', $doc_id);

        // Execute the query
        $query = $builder->get();

        // Check if results are found
        if ($query->getNumRows() >= 1) {
            // Fetch all results and return them as an array of associative arrays
            return $query->getResultArray();
        } else {
            return false;
        }
    }



    public function get_index_item_file($doc_id)
    {
        // Initialize Query Builder
        $builder = $this->db->table('efil.tbl_efiled_docs ed');

        // Select columns to retrieve
        $builder->select('ed.file_path, ed.file_name, ed.doc_title');

        // Set WHERE conditions
        $builder->where('ed.doc_id', $doc_id);
        $builder->where('ed.is_active', TRUE);
        $builder->where('ed.is_deleted', FALSE);

        // Execute the query
        $query = $builder->get();

        // Check if results are found
        if ($query->getNumRows() > 0) {
            // Fetch all results and return them as an array of associative arrays
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    //Code added on 6 nov 2020
    public function unfilled_pdf_pages_for_index($registration_id)
    {
        // Subquery to calculate total and pgcount
        $subquery = $this->db->table('efil.tbl_efiled_docs')
            ->select('registration_id, pdf_id, sum(page_no) as total, sum((end_page - st_page) + 1) as pgcount')
            ->where('is_deleted', 'f')
            ->where('registration_id', $registration_id)
            ->groupBy('registration_id, pdf_id')
            ->get();

        // Main query to fetch unfilled PDF pages
        $query = $this->db->table('efil.tbl_uploaded_pdfs a')
            ->select('a.registration_id, a.doc_id, a.page_no, a.doc_title, NULL as pdf_id, b.total, b.pgcount')
            ->where('a.is_deleted', 'f')
            ->where('a.registration_id', $registration_id)
            ->join('(' . $subquery . ') b', 'a.doc_id = b.pdf_id', 'left')
            ->where('(a.page_no != b.total OR a.page_no != b.pgcount)', NULL, FALSE)
            ->select('registration_id, doc_id, NULL as page_no, doc_title, NULL as pdf_id, NULL as total, NULL as pgcount', FALSE)
            ->where('is_deleted', 'f')
            ->where('doc_id NOT IN (' . $subquery->getCompiledSelect() . ')', NULL, FALSE)
            ->where('registration_id', $registration_id)
            ->get();

        // Check if results are found
        if ($query->getNumRows() > 0) {
            // Return results as an array of associative arrays
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    public function is_index_created($registration_id)
    {
        $query = $this->db->table('efil.tbl_efiled_docs ed')
            ->select('ed.registration_id, ed.doc_type_id')
            ->where('ed.registration_id', $registration_id)
            ->where('ed.is_active', true)
            ->where('ed.is_deleted', false)
            ->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    //Function used to get old page count before edit index . code added on 2 dec 20
    public function previous_index_file_count($pspdfkit_document_id)
    {
        $query = $this->db->table('efil.tbl_efiled_docs')
            ->where('is_deleted', false)
            ->where('pspdfkit_document_id', $pspdfkit_document_id)
            ->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    //Function used to get old page count before edit index . code added on 2 dec 20
    public function delete_beforedit_index_file($doc_id)
    {
        $query = $this->db->table('efil.tbl_efiled_docs')
            ->where('is_deleted', false)
            ->where('doc_id', $doc_id)
            ->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function is_check_index_item_file($registration_id, $doc_type_id, $sub_doc_type_id)
    {
        $query = $this->db->table('efil.tbl_efiled_docs')
            ->select('file_path, file_name, doc_title')
            ->where('registration_id', $registration_id)
            ->where('doc_type_id', $doc_type_id)
            ->where('sub_doc_type_id', $sub_doc_type_id)
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
}
