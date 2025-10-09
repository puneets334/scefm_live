<?php

namespace App\Models\NewCaseQF\DocumentIndex;

use CodeIgniter\Model;

class DocumentIndex_DropDown_model extends Model
{

    function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
    }

    public function get_uploaded_pdfs($registration_id)
    {
        $builder = $this->db->table('tbl_uploaded_pdfs docs');
        $builder->select('docs.doc_id, docs.doc_title, docs.file_name, docs.page_no, 
                      docs.uploaded_by, docs.uploaded_on, docs.upload_ip_address, docs.file_path, 
                      docs.doc_title, docs.doc_hashed_value, docs.pspdfkit_document_id');
        $builder->where('docs.registration_id', $registration_id);
        $builder->where('docs.is_deleted', false);
        $builder->where('docs.is_active', true);
        $builder->orderBy('docs.doc_id');

        $query = $builder->get();

        if ($query->getResult()) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    public function get_document_type($ia_doc_type = null)
    {
        $builder = $this->db->table('docmaster');
        $builder->select('*');
        $builder->where('doccode1', 0);
        $builder->where("display != 'N'");

        if ($ia_doc_type != null) {
            $builder->where('doccode', $ia_doc_type);
        }

        if (session()->has('efiling_details.ref_m_efiled_type_id')) {
            $efiled_type_id = session('efiling_details.ref_m_efiled_type_id');

            if ($efiled_type_id == E_FILING_TYPE_CAVEAT) {
                $builder->whereIn('doccode', [118, 8]);
            } elseif ($efiled_type_id == E_FILING_TYPE_MISC_DOCS || $efiled_type_id == E_FILING_TYPE_IA) {
                $builder->whereNotIn('doccode', [118]);
            }
        }

        $builder->orderBy('docdesc');
        $query = $builder->get();

        if ($query->getResult()) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    public function get_sub_document_type($doc_code, $doccode1 = null)
    {
        $builder = $this->db->table('docmaster');
        $builder->select('*');
        $builder->where('doccode', $doc_code);

        if (!empty($doccode1) && $doccode1 !== null) {
            $builder->where('doccode1', $doccode1);
        }

        $builder->whereNotIn('doccode1', [0]);
        $builder->where("display != 'N'");

        if (session()->has('efiling_details.ref_m_efiled_type_id')) {
            $efiled_type_id = session('efiling_details.ref_m_efiled_type_id');

            if ($efiled_type_id == E_FILING_TYPE_CAVEAT) {
                $builder->where('doccode1', 501);
            }
        }

        $builder->orderBy('docdesc');
        $query = $builder->get();

        if ($query->getResult()) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
}
