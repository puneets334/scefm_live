<?php

namespace App\Models\DocumentIndex;
use CodeIgniter\Model;
use GuzzleHttp\Exception\GuzzleException;

class DocumentIndexModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function save_pdf_details($data, $data_2, $doc_id, $breadcrumb_step_no, $breadcrumb_to_remove,$index_id=null) {
        if($index_id) {
            $this->db->transStart();
            $builder = $this->db->table('efil.tbl_efiled_docs');
            $builder->WHERE('doc_id', $index_id);
            $builder->WHERE('is_deleted', FALSE);
            $builder->UPDATE($data);
            if ($this->db->affectedRows() > 0) {
                $builder1 = $this->db->table('efil.tbl_uploaded_pdfs');
                $builder1->WHERE('doc_id', $doc_id);
                $builder1->UPDATE($data_2);
                $this->db->transComplete();
            }
        } else {
            $this->db->transStart();
            $builder = $this->db->table('efil.tbl_efiled_docs');
            $builder->INSERT($data);
            if ($this->db->insertID()) {
                $status = $this->update_breadcrumbs($_SESSION['efiling_details']['registration_id'], $breadcrumb_step_no);
                $status = $this->remove_breadcrumb($_SESSION['efiling_details']['registration_id'], $breadcrumb_to_remove);
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
        if (!isset($_SESSION['efiling_details']['registration_id']) || empty($_SESSION['efiling_details']['registration_id'])) {
            return FALSE;
        }
        $reg_id = $_SESSION['efiling_details']['registration_id'];
        $uploaded_by=$_SESSION['login']['id'];
        if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {
            $aorData = getAordetails_ifFiledByClerk($reg_id);
            if (isset($aorData) && !empty($aorData)) {
                $uploaded_by=!empty($aorData) ? $aorData[0]->id  : 0;
            }
        }
        $builder = $this->db->table('efil.tbl_efiled_docs pdfs');
        $builder->SELECT('pdfs.file_path, pdfs.file_name, left_pdfs_count');
        $builder->JOIN('( SELECT registration_id, count(doc_id) left_pdfs_count '
            . 'FROM efil.tbl_efiled_docs WHERE registration_id = ' . $reg_id . ' AND is_deleted IS FALSE'
            . ' GROUP BY registration_id ) left_pdfs', 'pdfs.registration_id = left_pdfs.registration_id', 'LEFT');
        $builder->WHERE('pdfs.doc_id', $index_id);
        $builder->WHERE('pdfs.registration_id', $reg_id);
        $builder->WHERE('pdfs.uploaded_by', $uploaded_by);
        $builder->WHERE('pdfs.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
        } else {
            return FALSE;
        }
        $this->db->transStart();
        $data = array('is_deleted' => TRUE, 'deleted_by' => $uploaded_by, 'deleted_on' => date('Y-m-d H:i:s'), 'delete_ip' => $_SERVER['REMOTE_ADDR']);
        $builder1 = $this->db->table('efil.tbl_efiled_docs');
        $builder1->WHERE('registration_id', $reg_id);
        $builder1->WHERE('doc_id', $index_id);
        $builder1->WHERE('uploaded_by', $uploaded_by);
        $builder1->UPDATE($data);
        if ($this->db->affectedRows() == 1) {
            $status = $this->remove_breadcrumb($reg_id, $breadcrumb_to_remove[1]);
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
        } else{
            return TRUE;
        }
    }

    public function delete_index_by_UploadFile_Pdf_Id($pdf_id, $breadcrumb_to_remove) {
        if (!isset($_SESSION['efiling_details']['registration_id']) || empty($_SESSION['efiling_details']['registration_id'])) {
            return FALSE;
        }
        $get_path = $this->getindexfiledetails($pdf_id);
        $reg_id = $_SESSION['efiling_details']['registration_id'];
        $uploaded_by=$_SESSION['login']['id'];
        if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {
            $aorData = getAordetails_ifFiledByClerk($reg_id);
            if (isset($aorData) && !empty($aorData)) {
                $uploaded_by=!empty($aorData) ? $aorData[0]->id  : 0;
            }
        }
        $builder = $this->db->table('efil.tbl_efiled_docs pdfs');
        $builder->SELECT('pdfs.file_path, pdfs.file_name, left_pdfs_count');
        $builder->JOIN('( SELECT registration_id, count(doc_id) left_pdfs_count '
            . 'FROM efil.tbl_efiled_docs WHERE registration_id = ' . $reg_id . ' AND is_deleted IS FALSE and is_active is TRUE'
            . ' GROUP BY registration_id ) left_pdfs', 'pdfs.registration_id = left_pdfs.registration_id', 'LEFT');
        $builder->WHERE('pdfs.pdf_id', $pdf_id);
        $builder->WHERE('pdfs.registration_id', $reg_id);
        $builder->WHERE('pdfs.uploaded_by', $uploaded_by);
        $builder->WHERE('pdfs.is_deleted', FALSE)->WHERE('pdfs.is_active', TRUE);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
        } else {
            return FALSE;
        }
        $this->db->transStart();
        foreach ($get_path as  $path) {
            if(!empty($path->file_path) && !empty($path->file_name)) {
                $file_delete_status = unlink($path->file_path . $path->file_name);
                if(!$file_delete_status) {
                    return FALSE;
                }
            }
        }
        $data = array('is_deleted' => TRUE, 'deleted_by' => $uploaded_by, 'deleted_on' => date('Y-m-d H:i:s'), 'delete_ip' => $_SERVER['REMOTE_ADDR']);
        $builder1 = $this->db->table('efil.tbl_efiled_docs');
        $builder1->WHERE('registration_id', $reg_id);
        $builder1->WHERE('pdf_id', $pdf_id);
        $builder1->WHERE('uploaded_by', $uploaded_by);
        $builder1->UPDATE($data);
        if ($this->db->affectedRows() >= 1) {
            $status = $this->remove_breadcrumb($reg_id, $breadcrumb_to_remove[1]);
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

    function update_breadcrumbs($registration_id, $step_no) {
        $old_breadcrumbs = $_SESSION['efiling_details']['breadcrumb_status'] . ',' . $step_no;
        $old_breadcrumbs_array = explode(',', $old_breadcrumbs);
        $new_breadcrumbs_array = array_unique($old_breadcrumbs_array);
        sort($new_breadcrumbs_array);
        $new_breadcrumbs = implode(',', $new_breadcrumbs_array);
        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->WHERE('registration_id', $registration_id);
        $builder->UPDATE(array('breadcrumb_status' => $new_breadcrumbs));
        if ($this->db->affectedRows() > 0) {
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
            $builder = $this->db->table('efil.tbl_efiling_nums');
            $builder->WHERE('registration_id', $registration_id);
            $builder->UPDATE(array('breadcrumb_status' => $new_breadcrumbs));
            if ($this->db->affectedRows() > 0) {
                $_SESSION['efiling_details']['breadcrumb_status'] = $new_breadcrumbs;
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    public function getPSPdfKitDocumentId_fromIndexedDocId($indexid) {
        $reg_id = $_SESSION['efiling_details']['registration_id'];
        $uploaded_by=$_SESSION['login']['id'];
        if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK]) && isset($reg_id) && !empty($reg_id)) {
            $aorData = getAordetails_ifFiledByClerk($reg_id);
            if (isset($aorData) && !empty($aorData)) {
                $uploaded_by=!empty($aorData) ? $aorData[0]->id  : 0;
            }
        }
        $builder = $this->db->table('efil.tbl_efiled_docs pdfs');
        $builder->SELECT('pdfs.pspdfkit_document_id');
        $builder->WHERE('pdfs.doc_id', $indexid);
        $builder->WHERE('pdfs.uploaded_by', $uploaded_by);
        $builder->WHERE('pdfs.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result[0]->pspdfkit_document_id;
        } else {
            return null;
        }
    }

    public function getPSPdfKitDocumentId_fromUploadedPdfId($pdfid) {
        $reg_id = $_SESSION['efiling_details']['registration_id'];
        $uploaded_by=$_SESSION['login']['id'];
        if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK]) && isset($reg_id) && !empty($reg_id)) {
            $aorData = getAordetails_ifFiledByClerk($reg_id);
            if (isset($aorData) && !empty($aorData)) {
                $uploaded_by=!empty($aorData) ? $aorData[0]->id  : 0;
            }
        }
        $builder = $this->db->table('efil.tbl_efiled_docs pdfs');
        $builder->SELECT('pdfs.pspdfkit_document_id');
        $builder->WHERE('pdfs.pdf_id', $pdfid);
        $builder->WHERE('pdfs.uploaded_by', $uploaded_by);
        $builder->WHERE('pdfs.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else {
            return null;
        }
    }

    public function setDuplicatePSPdfKitDocument($registration_id, $old_pspdfkit_document_id, $new_pspdfkit_document_id){
        $data = array('pspdfkit_document_id' => $new_pspdfkit_document_id);
        $builder = $this->db->table('efil.tbl_efiled_docs');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('pspdfkit_document_id', $old_pspdfkit_document_id);
        $builder->WHERE('is_active', TRUE);
        $builder->WHERE('is_deleted', FALSE);
        $builder->UPDATE($data);
    }

    public  function deleteDocument($document_id) {
        $pspdfkit_document = false;
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->delete(
                PSPDFKIT_SERVER_URI.'/api/documents/'.$document_id,
                [
                    'headers' => [
                        'Authorization' => 'Token token="secret"',
                    ],
                    'http_errors' => false
                ]
            );
            if($response->getStatusCode() == 200) {
                $pspdfkit_document = true;
            }
        } catch (GuzzleException $e) {
            
        }
        return $pspdfkit_document;
    }

    public function getIndexedDocFromPspdfkitDocumentId($pspdfkit_document_id) {
        $query = $this->db->table('efil.tbl_efiled_docs pdfs')
            ->SELECT('pdfs.*')
            ->WHERE('pdfs.pspdfkit_document_id', $pspdfkit_document_id)
            ->WHERE('pdfs.is_active', true)
            ->WHERE('pdfs.is_deleted', FALSE)
            ->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result[0];
        } else{
            return null;
        }
    }
    
    public function getindexfiledetails($pdfid) {
        $reg_id = $_SESSION['efiling_details']['registration_id'];
        $uploaded_by=$_SESSION['login']['id'];
        if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK]) && isset($reg_id) && !empty($reg_id)) {
            $aorData = getAordetails_ifFiledByClerk($reg_id);
            if (isset($aorData) && !empty($aorData)) {
                $uploaded_by=!empty($aorData) ? $aorData[0]->id  : 0;
            }
        }
        $builder = $this->db->table('efil.tbl_efiled_docs pdfs');
        $builder->SELECT('pdfs.*');
        $builder->WHERE('pdfs.pdf_id', $pdfid);
        $builder->WHERE('pdfs.uploaded_by', $uploaded_by);
        $builder->WHERE('pdfs.is_deleted', FALSE);
        $builder->orderBy('st_page', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else{
            return null;
        }
    }
    
    public function getmasterfiledetails($pdfid) {
        $reg_id = $_SESSION['efiling_details']['registration_id'];
        $uploaded_by=$_SESSION['login']['id'];
        if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK]) && isset($reg_id) && !empty($reg_id)) {
            $aorData = getAordetails_ifFiledByClerk($reg_id);
            if (isset($aorData) && !empty($aorData)) {
                $uploaded_by=!empty($aorData) ? $aorData[0]->id  : 0;
            }
        }
        $builder = $this->db->table('efil.tbl_uploaded_pdfs pdfs');
        $builder->SELECT('pdfs.pspdfkit_document_id,pdfs.file_name,pdfs.file_path,pdfs.page_no');
        $builder->WHERE('pdfs.doc_id', $pdfid);
        $builder->WHERE('pdfs.uploaded_by', $uploaded_by);
        $builder->WHERE('pdfs.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else{
            return null;
        }
    }
    
    public function getindexfiledetails_perindex($pdfid,$index_id) {
        $reg_id = $_SESSION['efiling_details']['registration_id'];
        $uploaded_by=$_SESSION['login']['id'];
        if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK]) && isset($reg_id) && !empty($reg_id)) {
            $aorData = getAordetails_ifFiledByClerk($reg_id);
            if (isset($aorData) && !empty($aorData)) {
                $uploaded_by=!empty($aorData) ? $aorData[0]->id  : 0;
            }
        }
        $builder = $this->db->table('efil.tbl_efiled_docs pdfs');
        $builder->SELECT('pdfs.*');
        $builder->WHERE('pdfs.pdf_id', $pdfid);
        $builder->WHERE('pdfs.uploaded_by', $uploaded_by);
        $builder->WHERE('pdfs.is_deleted', FALSE);
        $builder->WHERE('pdfs.is_active', TRUE);
        $builder->WHERE('doc_id!=', $index_id);
        $builder->orderBy('st_page', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else{
            return null;
        }
    }

}