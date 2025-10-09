<?php
namespace App\Models\NewCase;

use CodeIgniter\Model;

class IA_model extends Model {

    function __construct() {
        parent::__construct();
    }

    public function save_pdf_details($data, $data_2, $doc_id, $breadcrumb_step_no, $breadcrumb_to_remove) {
       // print_r(func_get_args());die;
        $this->db->trans_start();
        $this->db->INSERT('efil.tbl_efiled_docs', $data);
        if ($this->db->insert_id()) {

            $this->db->WHERE('doc_id', $doc_id);
            $this->db->UPDATE('efil.tbl_uploaded_pdfs', $data_2);

            if ($this->db->affected_rows() > 0) {
                $status = $this->update_breadcrumbs($_SESSION['efiling_details']['registration_id'], $breadcrumb_step_no);
                
                $status = $this->reset_affirmation($_SESSION['efiling_details']['registration_id'], array('is_data_valid' => FALSE), $breadcrumb_to_remove);
                
                $this->db->trans_complete();
            }
        }

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function delete_index($index_id, $breadcrumb_to_remove) {

        $this->db->WHERE('doc_id', $index_id);
        $this->db->WHERE('uploaded_by', $_SESSION['login']['id']);
        $this->db->UPDATE('efil.tbl_efiled_docs', array('is_deleted' => TRUE));
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }

        if (!isset($_SESSION['efiling_details']['registration_id']) || empty($_SESSION['efiling_details']['registration_id'])) {
            return FALSE;
        }

        $reg_id = $_SESSION['efiling_details']['registration_id'];

        $this->db->SELECT('pdfs.file_path, pdfs.file_name, left_pdfs_count');
        $this->db->FROM('efil.tbl_efiled_docs pdfs');
        $this->db->JOIN('( SELECT registration_id, count(doc_id) left_pdfs_count '
                . 'FROM efil.tbl_efiled_docs WHERE registration_id = ' . $reg_id . ' AND is_deleted IS FALSE'
                . ' GROUP BY registration_id ) left_pdfs', 'pdfs.registration_id = left_pdfs.registration_id', 'LEFT');
        $this->db->WHERE('pdfs.doc_id', $pdf_id);
        $this->db->WHERE('pdfs.registration_id', $reg_id);
        $this->db->WHERE('pdfs.uploaded_by', $_SESSION['login']['id']);
        $this->db->WHERE('pdfs.is_deleted', FALSE);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
        } else {
            return FALSE;
        }

        $this->db->trans_start();

        $data = array('is_deleted' => TRUE, 'deleted_by' => $_SESSION['login']['id'], 'deleted_on' => date('Y-m-d H:i:s'), 'delete_ip' => getClientIP());
        $this->db->WHERE('registration_id', $reg_id);
        $this->db->WHERE('doc_id', $pdf_id);
        $this->db->WHERE('uploaded_by', $_SESSION['login']['id']);
        $this->db->UPDATE('efil.tbl_efiled_docs', $data);
        if ($this->db->affected_rows() == 1) {

            $file_delete_status = unlink($result[0]->file_path . $result[0]->file_name);

            if (!$file_delete_status) {
                return FALSE;
            }

            $status = $this->reset_affirmation($reg_id, array('is_data_valid' => FALSE), $breadcrumb_to_remove[1]);

            if (($result[0]->left_pdfs_count - 1) == 0) {

                $status = $this->remove_breadcrumb($reg_id, $breadcrumb_to_remove[0]);

                if ($status) {
                    $this->db->trans_complete();
                }
            } else {
                $this->db->trans_complete();
            }
        }
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function get_index_items_list($registration_id) {

        $this->db->SELECT('ed.*,dm.docdesc'); //,sub_dm.docdesc
        $this->db->FROM('efil.tbl_efiled_docs ed');
        $this->db->JOIN('icmis.docmaster dm', 'ed.doc_type_id = dm.doccode AND ed.sub_doc_type_id = dm.doccode1 and dm.display!=\'N\'');
        
        $this->db->WHERE('ed.registration_id', $registration_id);
        $this->db->WHERE('ed.doc_type_id', 8);
        $this->db->WHERE('ed.is_active', TRUE);
        $this->db->WHERE('ed.is_deleted', FALSE);
        $this->db->ORDER_BY('ed.index_no');
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            $result = $query->result_array();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function get_index_item_file($doc_id) {
// used to show index pdf file on click of index item list
        $this->db->SELECT('ed.file_path, ed.file_name, ed.doc_title');
        $this->db->FROM('efil.tbl_efiled_docs ed');
        $this->db->WHERE('ed.doc_id', $doc_id);
        $this->db->WHERE('ed.is_active', TRUE);
        $this->db->WHERE('ed.is_deleted', FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result;
        } else {
            return FALSE;
        }
    }
    
    function get_uploaded_pdfs($registration_id) {
// SELECT PDFS LIST FOR DROP DOWN TO SELECT TO SPLIT
        $this->db->SELECT('docs.doc_id,docs.doc_title,
                           docs.file_name,docs.page_no,
                           docs.uploaded_by,docs.uploaded_on,docs.upload_ip_address,docs.file_path,docs.doc_title,
                           docs.doc_hashed_value');
        $this->db->FROM('efil.tbl_uploaded_pdfs docs');
        $this->db->WHERE('docs.registration_id', $registration_id);
        $this->db->WHERE('docs.is_deleted', FALSE);
        $this->db->ORDER_BY('docs.doc_id');
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            $result = $query->result_array();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_document_type($ia_doc_type = NULL) {

        $this->db->SELECT('*');
        $this->db->FROM('icmis.docmaster');
        if ($ia_doc_type != NULL) {
            $this->db->WHERE('doccode', $ia_doc_type);
        } else {
            $this->db->WHERE_NOT_IN('doccode', 0);
        }
        $this->db->WHERE('doccode1', 0);
        $this->db->WHERE("display != 'N'" );
        $this->db->ORDER_BY('docdesc');
        $query = $this->db->get(); //echo $this->db->last_query(); die;
        if ($query->num_rows() >= 1) {
            $result = $query->result_array(); 
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_sub_document_type($doc_code) {

        $this->db->SELECT('*');
        $this->db->FROM('icmis.docmaster');
        $this->db->WHERE('doccode', $doc_code);
        $this->db->WHERE_NOT_IN('doccode1', 0);
        $this->db->WHERE("display != 'N'" );
        $this->db->ORDER_BY('docdesc');
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            $result = $query->result_array();
            return $result;
        } else {
            return FALSE;
        }
    }
    
    public function get_original_pdf_details($doc_id) {

        $this->db->SELECT('max_index_no, up.file_path, up.page_no');
        $this->db->FROM('efil.tbl_uploaded_pdfs up');
        $this->db->JOIN('( SELECT max(ed.index_no) max_index_no FROM efil.tbl_efiled_docs ed WHERE ed.registration_id = registration_id ) a', '1 = 1', 'left');
        $this->db->WHERE('up.doc_id', $doc_id);
        $this->db->WHERE('up.uploaded_by', $_SESSION['login']['id']);
        $query = $this->db->get(); 
        if ($query->num_rows() >= 1) {
            $result = $query->result_array();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function current_working_file($case_file_id, $case_file_pdf_id) {

        $this->db->SELECT('docs.*,doc_cat.doc_category,pdf.efiling_no,pdf.file_partial_path,pdf.is_imported');
        $this->db->FROM('tbl_e_case_file_documents docs');
        $this->db->JOIN('m_tbl_casefile_doc_category doc_cat', 'docs.e_casefile_doc_cat_id = doc_cat.id');
        $this->db->JOIN('tbl_e_case_file_pdfs pdf', 'docs.case_file_pdf_id = pdf.id', 'LEFT');
        $this->db->WHERE('docs.e_casefile_id', $case_file_id);
        $this->db->WHERE('docs.case_file_pdf_id', $case_file_pdf_id);
        $this->db->WHERE('docs.created_by', $_SESSION['login']['id']);
        $this->db->WHERE('docs.is_deleted', FALSE);
        $this->db->ORDER_BY('docs.id', 'ASC');
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            $result = $query->result_array();
            return $result;
        } else {
            return FALSE;
        }
    }
    
    function reset_affirmation($reg_id, $update_data, $breadcrumb_to_remove) {


        $this->db->WHERE('ref_registration_id', $reg_id);
        $this->db->WHERE('is_data_valid', TRUE);
        $this->db->UPDATE('efil.esign_logs', $update_data);

        if ($this->db->affected_rows() >= 1) {

            $status = $this->remove_breadcrumb($reg_id, $breadcrumb_to_remove);
            return $status;
        } else {
            return FALSE;
        }
    }
    
    

    function update_breadcrumbs($registration_id, $step_no) {

        $old_breadcrumbs = $_SESSION['efiling_details']['breadcrumb_status'] . ',' . $step_no;
        $old_breadcrumbs_array = explode(',', $old_breadcrumbs);
        $new_breadcrumbs_array = array_unique($old_breadcrumbs_array);

        sort($new_breadcrumbs_array);
        $new_breadcrumbs = implode(',', $new_breadcrumbs_array);

        $this->db->WHERE('registration_id', $registration_id);
        $this->db->UPDATE('efil.tbl_efiling_nums', array('breadcrumb_status' => $new_breadcrumbs));

        if ($this->db->affected_rows() > 0) {
            $_SESSION['efiling_details']['breadcrumb_status'] = $new_breadcrumbs;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function remove_breadcrumb($registration_id, $breadcrumb_to_remove) {

        $breadcrumbs_array = explode(',', $_SESSION['efiling_details']['breadcrumb_status']);

        if (in_array($breadcrumb_to_remove, $breadcrumbs_array)) {


            $index = array_search($breadcrumb_to_remove, $breadcrumbs_array);
            if ($index !== false) {
                unset($breadcrumbs_array[$index]);
            }

            $new_breadcrumbs = implode(',', $breadcrumbs_array);

            $this->db->WHERE('registration_id', $registration_id);
            $this->db->UPDATE('efil.tbl_efiling_nums', array('breadcrumb_status' => $new_breadcrumbs));

            if ($this->db->affected_rows() > 0) {
                $_SESSION['efiling_details']['breadcrumb_status'] = $new_breadcrumbs;
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

}
