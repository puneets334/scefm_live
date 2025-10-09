<?php

namespace App\Models\UploadDocuments;

use CodeIgniter\Model;

class UploadDocsModel extends Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        helper('file');
    }

    function upload_pdfs($data, $file_temp_name, $breadcrumb_step_no)
    {

        $establishment_code = getSessionData('estab_details')['estab_code'];
        $registration_id = getSessionData('efiling_details')['registration_id'];

        $this->db->transStart();

        $est_dir = 'uploaded_docs/' . $establishment_code;
        if (!is_dir($est_dir)) {
            $uold = umask(0);
            if (mkdir('uploaded_docs/' . $establishment_code, 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($est_dir . '/index.html', $html);
            }
            umask($uold);
        }

        $efil_num_dir = 'uploaded_docs/' . $establishment_code . '/' . getSessionData('efiling_details')['efiling_no'];
        if (!is_dir($efil_num_dir)) {
            $uold = umask(0);
            if (mkdir($efil_num_dir, 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($efil_num_dir . '/index.html', $html);
            }
            umask($uold);
        }

        $file_uploaded_dir = 'uploaded_docs/' . $establishment_code . '/' . getSessionData('efiling_details')['efiling_no'] . '/docs/';
        // pr($file_uploaded_dir);
        if (!is_dir($file_uploaded_dir)) {
            $uold = umask(0);
            if (mkdir('uploaded_docs/' . $establishment_code . '/' . getSessionData('efiling_details')['efiling_no'] . '/docs/', 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($file_uploaded_dir . '/index.html', $html);
            }
            umask($uold);
        }

        //-----START :Modify File Name-----//

        $filename = $data['doc_title'];
        $filename = str_replace(' ', '_', $filename); // Replaces all spaces with underscore.
        $filename = preg_replace('/[^A-Za-z0-9\_]/', '', $filename); // Removes special chars.
        $filename = $_SESSION['efiling_details']['efiling_no'] . '_' . $filename . '_' . date('YmdHis') . '.pdf';

        $result = $this->upload_file($file_uploaded_dir, $filename, $file_temp_name);

        //--------END : Modify File Name----------------------//

        $file_path = getcwd() . '/' . $file_uploaded_dir . $filename;
        $page_no = (int)@exec('pdfinfo ' . $file_path . ' | awk \'/Pages/ {print $2}\'', $output); //poppler-utils variant
        $data2 = array('page_no' => $page_no, 'file_name' => $filename, 'file_path' => $file_uploaded_dir . $filename);

        $merge_array_data = array_merge($data, $data2);
        // pr($result);

        if ($result) {
            $builder = $this->db->table('efil.tbl_uploaded_pdfs');
            $builder->INSERT($merge_array_data);

            if ($this->db->insertID()) {

                $status = $this->update_breadcrumbs($registration_id, $breadcrumb_step_no);
                if ($status) {
                    $this->db->transComplete();
                }
                reset_affirmation($registration_id);
            }
            if ($this->db->transStatus() === FALSE) {
                unlink($file_uploaded_dir . '/' . $file_temp_name);
                return 'trans_failed';
            } else {
                return 'trans_success';
            }
        } else {
            return 'upload_fail';
        }
    }

    function upload_file($file_uploaded_dir, $filename, $file)
    {

        $uploaded = move_uploaded_file($file, "$file_uploaded_dir/$filename");

        if ($uploaded) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_uploaded_pdfs($registration_id) {
       $session = session();
        $builder = $this->db->table('efil.tbl_uploaded_pdfs docs');
        $builder->select('docs.doc_id, docs.doc_title, docs.file_name, docs.page_no, docs.uploaded_by, docs.uploaded_on, docs.upload_ip_address, docs.file_path, docs.doc_title, docs.doc_hashed_value
        ');
        if ((isset(getSessionData('login')['ref_m_usertype_id']) || !empty(getSessionData('login')['ref_m_usertype_id'])) && getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN) {
            $subquery = $this->db->table('efil.tbl_efiling_num_status')
                ->select('*')
                ->where('registration_id', $registration_id)
                ->orderBy('activated_on', 'desc')
                ->limit(1);
            $builder->join('(' . $subquery->getCompiledSelect() . ') tensf', 'docs.registration_id = tensf.registration_id', 'left');
        }
        $builder->where('docs.registration_id', $registration_id);
        $builder->where('docs.is_deleted', false);
        if ((isset(getSessionData('login')['ref_m_usertype_id']) || !empty(getSessionData('login')['ref_m_usertype_id'])) && getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN) {
            $builder->where('docs.uploaded_on <=', 'tensf.activated_on', false);
        }
        $builder->orderBy('docs.doc_id');
        $query = $builder->get();     
        if ($query && $query->getNumRows() >= 1) {
            return $query->getResultArray();            
        } else {
            return false;
        }
    }

    function get_deleted_uploaded_pdfs_while_refiling($registration_id) {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs docs');
        $builder->select('docs.doc_id, docs.doc_title, docs.file_name, docs.page_no, docs.uploaded_by, docs.uploaded_on, docs.upload_ip_address, docs.file_path, docs.doc_title, docs.doc_hashed_value, tens.activated_on, docs.deleted_on, docs.is_deleted');
        $builder->join('efil.tbl_efiling_num_status tens', 'docs.registration_id = tens.registration_id AND (tens.stage_id = 2 OR tens.stage_id = 11)', 'left');
        $builder->where('docs.registration_id', $registration_id);
        $builder->where('docs.is_deleted', true);
        $builder->where('docs.deleted_on > tens.activated_on');
        $builder->orderBy('docs.doc_id');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
    
    function get_uploaded_pdfs_for_hash($registration_id) {
        $builder = $this->db->table('efil.tbl_efiled_docs docs');
        $builder->select('docs.doc_id,docs.doc_title,
            docs.file_name,docs.page_no,
            docs.uploaded_by,docs.uploaded_on,docs.upload_ip_address,docs.file_path,docs.doc_title,
            docs.doc_hashed_value');
        $builder->where('docs.registration_id', $registration_id);
        $builder->where('docs.is_deleted', false);
        $builder->orderBy('docs.doc_id');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_uploaded_pdf_file($doc_id) {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs pdf');
        $builder->SELECT('pdf.file_path, pdf.file_name, pdf.doc_title');
        $builder->WHERE('pdf.doc_id', $doc_id);
        $builder->WHERE('pdf.is_active', TRUE);
        $builder->WHERE('pdf.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function get_deleted_pdf_files($doc_id) {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs pdf');
        $builder->SELECT('pdf.file_path, pdf.file_name, pdf.doc_title');
        $builder->FROM('efil.tbl_uploaded_pdfs pdf');
        $builder->WHERE('pdf.doc_id', $doc_id);
        $builder->WHERE('pdf.is_active', TRUE);
        $builder->WHERE('pdf.is_deleted', TRUE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function deletePdfDoc($pdf_id, $reg_id, $breadcrumb_to_remove) {
        if (!isset($_SESSION['efiling_details']['registration_id']) || empty($_SESSION['efiling_details']['registration_id'])) {
            return FALSE;
        }
        $uploaded_by=$_SESSION['login']['id'];
        if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {
            $aorData = getAordetails_ifFiledByClerk($reg_id);
            if (isset($aorData) && !empty($aorData)) {
                $uploaded_by = !empty($aorData) ? $aorData[0]->id : 0;
            }
        }
        $builder = $this->db->table('efil.tbl_uploaded_pdfs pdfs');
        $builder->SELECT('pdfs.file_path, left_pdfs_count');
        $builder->JOIN('( SELECT registration_id, count(doc_id) left_pdfs_count '
            . 'FROM efil.tbl_uploaded_pdfs WHERE registration_id = '.$reg_id.' AND is_deleted IS FALSE'
            . ' GROUP BY registration_id ) left_pdfs', 'pdfs.registration_id = left_pdfs.registration_id', 'LEFT');
        $builder->WHERE('pdfs.doc_id', $pdf_id);
        $builder->WHERE('pdfs.registration_id', $reg_id);        
        $builder->WHERE('pdfs.uploaded_by', $uploaded_by);
        $builder->WHERE('pdfs.is_deleted', FALSE);
        $query = $builder->get(); 
        if ($query->getNumRows()) {
            $result = $query->getResult();
            $breadcrumbs_array = explode(',', $_SESSION['efiling_details']['breadcrumb_status']);
            if (!(in_array(CAVEAT_BREAD_UPLOAD_DOC, $breadcrumbs_array))) {
                $already_uploaded_document_update_breadcrumb_status = $this->update_breadcrumbs($_SESSION['efiling_details']['registration_id'], CAVEAT_BREAD_UPLOAD_DOC);
            }
        } else {
            return FALSE;
        }
        $this->db->transStart();
        $data = array('is_deleted' => TRUE, 'deleted_by' =>$uploaded_by, 'deleted_on' => date('Y-m-d H:i:s'), 'delete_ip' => getClientIP());
        $builder1 = $this->db->table('efil.tbl_uploaded_pdfs');
        $builder1->WHERE('registration_id', $reg_id);
        $builder1->WHERE('doc_id', $pdf_id);
        $builder1->WHERE('uploaded_by', $uploaded_by);
        $builder1->SET($data);
        $builder1->UPDATE();
        if($this->db->affectedRows() == 1) {
            $builder2 = $this->db->table('efil.tbl_efiled_docs ted');
            $builder2->SELECT('ted.doc_id, ted.registration_id');
            $builder2->WHERE('ted.pdf_id', $pdf_id);
            $builder2->WHERE('ted.registration_id', $reg_id);
            $builder2->WHERE('ted.uploaded_by', $uploaded_by);
            $builder2->WHERE('ted.is_deleted', FALSE);
            $query_pdf_indexes = $builder2->get();
            if ($query_pdf_indexes->getNumRows()) {
                $builder3 = $this->db->table('efil.tbl_efiled_docs');
                $builder3->WHERE('registration_id', $reg_id);
                $builder3->WHERE('pdf_id', $pdf_id);
                $builder3->WHERE('uploaded_by', $uploaded_by);
                $builder3->SET($data);
                $builder3->UPDATE();
            }
            if(file_exists($result[0]->file_path)) {
                if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE and in_array($_SESSION['efiling_details']['stage_id'], array(Draft_Stage))) {
                    $file_delete_status = unlink($result[0]->file_path);
                } else {
                    $file_delete_status = TRUE;
                }
                if(!$file_delete_status) {
                    return FALSE;
                }
            }
            if(($result[0]->left_pdfs_count - 1) == 0) {
                $status = $this->remove_breadcrumb($reg_id, $breadcrumb_to_remove);
                if($status){
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

    function update_breadcrumbs($registration_id, $step_no) {
        $old_breadcrumbs = getSessionData('efiling_details')['breadcrumb_status'] . ',' . $step_no;
        $old_breadcrumbs_array = explode(',', $old_breadcrumbs);
        $new_breadcrumbs_array = array_unique($old_breadcrumbs_array);
        $builder = $this->db->table('efil.tbl_efiling_nums');
        sort($new_breadcrumbs_array);
        $new_breadcrumbs = implode(',', $new_breadcrumbs_array);
        $builder->WHERE('registration_id', $registration_id);
        $builder->SET(array('breadcrumb_status' => $new_breadcrumbs));
        $builder->UPDATE();
        if ($this->db->affectedRows() > 0) {
            getSessionData('efiling_details')['breadcrumb_status'] = $new_breadcrumbs;
            $setD = getSessionData('efiling_details');
            $setD['breadcrumb_status'] = $new_breadcrumbs;
            setSessionData('efiling_details', $setD);
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
            $builder = $this->db->table('efil.tbl_efiling_nums');
            $builder->WHERE('registration_id', $registration_id);
            $builder->SET(array('breadcrumb_status' => $new_breadcrumbs));
            $builder->UPDATE();
            if ($this->db->affectedRows() >  0) {
                getSessionData('efiling_details')['breadcrumb_status'] = $new_breadcrumbs;
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function isPdfDoc($pdf_id, $registration_id) {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs');
        $builder->SELECT('*');
        $builder->WHERE(['registration_id' => $registration_id, 'doc_id' => $pdf_id, 'is_deleted' => false, 'tbl_court_fee_payment_id IS NOT NULL' => null]);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

}