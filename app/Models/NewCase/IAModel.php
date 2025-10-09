<?php

namespace App\Models\NewCase;
use CodeIgniter\Model;

class IAModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function save_pdf_details($data, $data_2, $doc_id, $breadcrumb_step_no, $breadcrumb_to_remove) {
        // print_r(func_get_args());die;
        $this->db->transStart();
        $builder = $this->db->table('efil.tbl_efiled_docs')->INSERT($data);
        if ($this->db->insertID()) {
            $builder1 = $this->db->table('efil.tbl_uploaded_pdfs')->WHERE('doc_id', $doc_id)->UPDATE($data_2);
            if ($this->db->affectedRows() > 0) {
                $status = $this->update_breadcrumbs(getSessionData('efiling_details')['registration_id'], $breadcrumb_step_no);
                $status = $this->reset_affirmation(getSessionData('efiling_details')['registration_id'], array('is_data_valid' => FALSE), $breadcrumb_to_remove);
                $this->db->transComplete();
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function delete_index($index_id, $breadcrumb_to_remove) {
        $builder = $this->db->table('efil.tbl_efiled_docs')
            ->WHERE('doc_id', $index_id)
            ->WHERE('uploaded_by', getSessionData('login')['id'])
            ->UPDATE(array('is_deleted' => TRUE));
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
        if (!isset(getSessionData('efiling_details')['registration_id']) || empty(getSessionData('efiling_details')['registration_id'])) {
            return FALSE;
        }
        $reg_id = getSessionData('efiling_details')['registration_id'];
        $builder1 = $this->db->table('efil.tbl_efiled_docs pdfs')
            ->SELECT('pdfs.file_path, pdfs.file_name, left_pdfs_count')
            ->JOIN('( SELECT registration_id, count(doc_id) left_pdfs_count '
                . 'FROM efil.tbl_efiled_docs WHERE registration_id = ' . $reg_id . ' AND is_deleted IS FALSE'
                . ' GROUP BY registration_id ) left_pdfs', 'pdfs.registration_id = left_pdfs.registration_id', 'LEFT')
            ->WHERE('pdfs.doc_id', $index_id)
            ->WHERE('pdfs.registration_id', $reg_id)
            ->WHERE('pdfs.uploaded_by', getSessionData('login')['id'])
            ->WHERE('pdfs.is_deleted', FALSE);
        $query = $builder1->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
        } else {
            return FALSE;
        }
        $this->db->transStart();
        $data = array('is_deleted' => TRUE, 'deleted_by' => getSessionData('login')['id'], 'deleted_on' => date('Y-m-d H:i:s'), 'delete_ip' => getClientIP());
        $builder2 = $this->db->table('efil.tbl_efiled_docs')
            ->WHERE('registration_id', $reg_id)
            ->WHERE('doc_id', $index_id)
            ->WHERE('uploaded_by', getSessionData('login')['id'])
            ->UPDATE($data);
        if ($this->db->affectedRows() == 1) {
            $file_delete_status = unlink($result[0]->file_path . $result[0]->file_name);
            if (!$file_delete_status) {
                return FALSE;
            }
            $status = $this->reset_affirmation($reg_id, array('is_data_valid' => FALSE), $breadcrumb_to_remove[1]);
            if (($result[0]->left_pdfs_count - 1) == 0) {
                $status = $this->remove_breadcrumb($reg_id, $breadcrumb_to_remove[0]);
                if ($status) {
                    $this->db->transComplete();
                }
            } else {
                $this->db->transComplete();
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function get_index_items_list($registration_id) {
        $builder = $this->db->table('efil.tbl_efiled_docs ed')
            ->SELECT('ed.*,dm.docdesc') //,sub_dm.docdesc
            ->JOIN('icmis.docmaster dm', 'ed.doc_type_id = dm.doccode AND ed.sub_doc_type_id = dm.doccode1 and dm.display!=\'N\'')
            ->WHERE('ed.registration_id', $registration_id)
            ->WHERE('ed.doc_type_id', 8)
            ->WHERE('ed.is_active', TRUE)
            ->WHERE('ed.is_deleted', FALSE)
            ->orderBy('ed.index_no');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function get_index_item_file($doc_id) {
        // used to show index pdf file on click of index item list
        $builder = $this->db->table('efil.tbl_efiled_docs ed')
            ->SELECT('ed.file_path, ed.file_name, ed.doc_title')
            ->WHERE('ed.doc_id', $doc_id)
            ->WHERE('ed.is_active', TRUE)
            ->WHERE('ed.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }
    
    function get_uploaded_pdfs($registration_id) {
        // SELECT PDFS LIST FOR DROP DOWN TO SELECT TO SPLIT
        $builder = $this->db->table('efil.tbl_uploaded_pdfs docs')
            ->SELECT('docs.doc_id,docs.doc_title,
                docs.file_name,docs.page_no,
                docs.uploaded_by,docs.uploaded_on,docs.upload_ip_address,docs.file_path,docs.doc_title,
                docs.doc_hashed_value')
            ->WHERE('docs.registration_id', $registration_id)
            ->WHERE('docs.is_deleted', FALSE)
            ->orderBy('docs.doc_id');
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
        } else {
            $builder->whereNotIn('doccode', 0);
        }
        $builder->WHERE('doccode1', 0);
        $builder->WHERE("display != 'N'" );
        $builder->orderBy('docdesc');
        $query = $builder->get(); //echo $this->db->last_query(); die;
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray(); 
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_sub_document_type($doc_code) {
        // $builder = $this->db->table('icmis.docmaster')
        //     ->SELECT('*')
        //     ->WHERE('doccode', $doc_code)
        //     ->whereNotIn('doccode1', 0)
        //     ->WHERE("display != 'N'" )
        //     ->orderBy('docdesc');
        // $query = $builder->get();
        // if ($query->getNumRows() >= 1) {
        //     $result = $query->getResultArray();
        //     return $result;
        // } else {
        //     return FALSE;
        // }
         // Build the query using the query builder
         $builder = $this->db->table('icmis.docmaster'); // Get the table object

         $builder->select('*'); // Select all columns
         $builder->where('doccode', $doc_code); // Add WHERE condition for doccode
         $builder->whereNotIn('doccode1', [0]); // Add WHERE_NOT_IN condition for doccode1
         $builder->where("display != 'N'"); // Add WHERE condition for display column
         $builder->orderBy('docdesc'); // Add ORDER BY condition for docdesc
 
         // Execute the query
         $query = $builder->get();
 
         // Check if the query returned results
         if ($query->getNumRows() >= 1) {
             return $query->getResultArray(); // Return results as an array
         } else {
             return false; // Return false if no rows found
         }
    }
    
    public function get_original_pdf_details($doc_id) {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs up')
        ->SELECT('max_index_no, up.file_path, up.page_no')
        ->JOIN('( SELECT max(ed.index_no) max_index_no FROM efil.tbl_efiled_docs ed WHERE ed.registration_id = registration_id ) a', '1 = 1', 'left')
        ->WHERE('up.doc_id', $doc_id)
        ->WHERE('up.uploaded_by', getSessionData('login')['id']);
        $query = $builder->get(); 
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function current_working_file($case_file_id, $case_file_pdf_id) {
        $builder = $this->db->table('tbl_e_case_file_documents docs')
            ->SELECT('docs.*,doc_cat.doc_category,pdf.efiling_no,pdf.file_partial_path,pdf.is_imported')
            ->JOIN('m_tbl_casefile_doc_category doc_cat', 'docs.e_casefile_doc_cat_id = doc_cat.id')
            ->JOIN('tbl_e_case_file_pdfs pdf', 'docs.case_file_pdf_id = pdf.id', 'LEFT')
            ->WHERE('docs.e_casefile_id', $case_file_id)
            ->WHERE('docs.case_file_pdf_id', $case_file_pdf_id)
            ->WHERE('docs.created_by', getSessionData('login')['id'])
            ->WHERE('docs.is_deleted', FALSE)
            ->orderBy('docs.id', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }
    
    function reset_affirmation($reg_id, $update_data, $breadcrumb_to_remove) {
        $builder = $this->db->table('efil.esign_logs')->WHERE('ref_registration_id', $reg_id)->WHERE('is_data_valid', TRUE)->UPDATE($update_data);
        if ($this->db->affectedRows() >= 1) {
            $status = $this->remove_breadcrumb($reg_id, $breadcrumb_to_remove);
            return $status;
        } else {
            return FALSE;
        }
    }
    
    function update_breadcrumbs($registration_id, $step_no) {
        $old_breadcrumbs = getSessionData('efiling_details')['breadcrumb_status'] . ',' . $step_no;
        $old_breadcrumbs_array = explode(',', $old_breadcrumbs);
        $new_breadcrumbs_array = array_unique($old_breadcrumbs_array);
        sort($new_breadcrumbs_array);
        $new_breadcrumbs = implode(',', $new_breadcrumbs_array);
        $builder = $this->db->table('efil.tbl_efiling_nums')->WHERE('registration_id', $registration_id)->UPDATE(array('breadcrumb_status' => $new_breadcrumbs));
        if ($this->db->affectedRows() > 0) {
            getSessionData('efiling_details')['breadcrumb_status'] = $new_breadcrumbs;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function remove_breadcrumb($registration_id, $breadcrumb_to_remove) {
        $breadcrumbs_array = explode(',', getSessionData('efiling_details')['breadcrumb_status']);
        if (in_array($breadcrumb_to_remove, $breadcrumbs_array)) {
            $index = array_search($breadcrumb_to_remove, $breadcrumbs_array);
            if ($index !== false) {
                unset($breadcrumbs_array[$index]);
            }
            $new_breadcrumbs = implode(',', $breadcrumbs_array);
            $builder = $this->db->table('efil.tbl_efiling_nums')->WHERE('registration_id', $registration_id)->UPDATE(array('breadcrumb_status' => $new_breadcrumbs));
            if ($this->db->affectedRows() > 0) {
                getSessionData('efiling_details')['breadcrumb_status'] = $new_breadcrumbs;
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

}