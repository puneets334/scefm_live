<?php

namespace App\Models\AIAssisted;
use CodeIgniter\Model;

class CommonCasewithAIModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function get_uploaded_pdf_file_IITM($id) {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs_jsonai');
        $builder->SELECT("*");
        $builder->WHERE('id', $id);
        $builder->WHERE('is_deleted', FALSE);
        $builder->LIMIT(1);
        $query = $builder->get();
        return  $query->getResultArray();
    }

    public function get_json_data_extract_empt_details() {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs_jsonai');
        $builder->SELECT("*");
        $builder->WHERE('is_active_iitm', true);
        $builder->WHERE('is_deleted', FALSE);
        $builder->WHERE('iitm_api_json is null');
        $builder->orderBy('uploaded_on','DESC');
        $query = $builder->get();
        return  $query->getResultArray();
    }

    function upload_pdfs($data,$file_temp_name) {
        $establishment_code = 'tempDataExtractPDF/';
        $this->db->transStart();
        $file_uploaded_dir = 'uploaded_docs/' . $establishment_code;
        if (!is_dir($file_uploaded_dir)) {
            $uold = umask(0);
            if (mkdir('uploaded_docs/' . $establishment_code, 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($file_uploaded_dir . '/index.html', $html);
            }
            umask($uold);
        }
        $filename = $data['doc_title'];
        $filename = str_replace(' ', '_', $filename);
        $filename = preg_replace('/[^A-Za-z0-9\_]/', '', $filename);
        $filename = $filename .'_'.date('YmdHis'). '.pdf';
        $result = $this->upload_file($file_uploaded_dir, $filename, $file_temp_name);
        $file_path = getcwd() . '/' . $file_uploaded_dir . $filename;
        $page_no = (int)@exec('pdfinfo ' . $file_path . ' | awk \'/Pages/ {print $2}\'', $output);
        $data2 = array('page_no' => $page_no, 'file_name' => $filename, 'file_path' => $file_uploaded_dir . $filename);
        $merge_array_data = array_merge($data, $data2);
        if ($result) {
            $builder = $this->db->table('efil.tbl_uploaded_pdfs_jsonai');
            $builder->INSERT($merge_array_data);
            if ($this->db->insertID()) {
                $this->db->transComplete();
            }
            if ($this->db->transStatus() === FALSE) {
                unlink($file_uploaded_dir . '/' . $file_temp_name);
                return 'trans_failed';
            } else {
                return $this->db->insertID();
            }
        } else {
            return 'upload_fail';
        }
    }

    function upload_file($file_uploaded_dir, $filename, $file) {
        $uploaded = move_uploaded_file($file, "$file_uploaded_dir/$filename");
        if ($uploaded) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function upload_pdfs_update($id,$data) {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs_jsonai');
        $builder->WHERE('id', $id);
        $builder->WHERE('is_deleted', FALSE);
        $builder->set($data);
        $builder->UPDATE();
        if($this->db->affectedRows() > 0) {
            $this->get_casewithAI_data_extract($id,'API');
            return TRUE;
        }
        return false;
    }

    function get_casewithAI_data_extract($id,$type='registration_id') {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs_jsonai');
        $builder->SELECT("*");
        if (!empty($id) && $type!='registration_id') { $builder->WHERE('id', $id); }
        if (!empty($id) && $type=='registration_id') { $builder->WHERE('registration_id', $id); }
        $builder->WHERE('is_active_iitm',true);
        $builder->orderBy('id','DESC');
        $builder->LIMIT(1);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $response_result= $query->getResultArray();
            if (!empty($response_result)) {
                $decoded_data=json_decode($response_result[0]['iitm_api_json'],TRUE);
                if (!empty($decoded_data)) {
                    $merge_array_data = array_merge(array('id'=>$response_result[0]['id'],'registration_id'=>$response_result[0]['registration_id'],'efiling_no'=>$response_result[0]['efiling_no'],'sc_case_type'=>$response_result[0]['sc_case_type']), $decoded_data);
                    $session_data_extract = array($merge_array_data);
                    setSessionData('casewithAI', $session_data_extract);
                    return $session_data_extract;
                }
                return $response_result;
            }
        } else {
            return  $query->getResultArray();
        }
    }

    public function get_casewithAI_data_extract_details($id) {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs_jsonai');
        $builder->SELECT("*");        
        $builder->WHERE('id', $id);
        $builder->WHERE('is_deleted', FALSE);
        $builder->LIMIT(1);
        $query = $builder->get();
        return  $query->getResultArray();
    }

    public function get_high_courts_id($high_court_name) {
        $builder = $this->db->table('efil.m_tbl_high_courts_bench');
        $builder->DISTINCT();
        $builder->SELECT('hc_id, name');
        $builder->WHERE('bench_id is null and est_code is null');
        $builder->like('name',$high_court_name,'','!',true);
        $builder->LIMIT(1);
        $query= $builder->get();
        $result= $query->getResultArray();
        return $result;
    }

    public function get_sci_case_type() {
        $builder = $this->db->table('icmis.casetype');
        $builder->SELECT("casecode, casename");        
        $builder->WHERE('display', 'Y');
        $builder->whereIn('casecode',array('1','2'));
        $builder->orderBy("casename", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    public function uploadPdfIntomain($dataArr,$registration_id) {
        if (!empty($dataArr) && !empty($registration_id)) {
            $ref_m_efiled_type_id=isset($_SESSION['efiling_details']) ? $_SESSION['efiling_details']['ref_m_efiled_type_id']:null;
            $curr_stage_id=isset($_SESSION['efiling_details']) ? $_SESSION['efiling_details']['stage_id']:null;
            $pspdfkit_document_id='';
            $uploaded_on = date('Y-m-d H:i:s');
            $sub_created_by = 0;
            $uploaded_by = $_SESSION['login']['id'];
            $breadcrumb_step_no = NEW_CASE_UPLOAD_DOCUMENT;
            $doc_title=$dataArr['doc_title'];
            $page_no = $dataArr['page_no'];
            $path_main_file = $dataArr['file_path'];
            $data = array(
                'registration_id' => $registration_id,
                'efiled_type_id' => $ref_m_efiled_type_id,
                'file_size' => $dataArr['file_size'],
                'file_type' => $dataArr['file_type'],
                'page_no' => $dataArr['page_no'],
                'doc_title' => $doc_title,
                'doc_hashed_value' => $dataArr['doc_hashed_value'],
                'uploaded_by' => $uploaded_by,
                'uploaded_on' => $uploaded_on,
                'upload_ip_address' => getClientIP(),
                'sub_created_by' => $sub_created_by,
                'pspdfkit_document_id'=>$pspdfkit_document_id
            );
            $establishment_code = $_SESSION['estab_details']['estab_code'];
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $est_dir = 'uploaded_docs/' . $establishment_code;
            if (!is_dir($est_dir)) {
                $uold = umask(0);
                if (mkdir('uploaded_docs/' . $establishment_code, 0777, true)) {
                    $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                    write_file($est_dir . '/index.html', $html);
                }
                umask($uold);
            }
            $efil_num_dir = 'uploaded_docs/' . $establishment_code . '/' . $_SESSION['efiling_details']['efiling_no'];
            if (!is_dir($efil_num_dir)) {
                $uold = umask(0);
                if (mkdir($efil_num_dir, 0777, true)) {
                    $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                    write_file($efil_num_dir . '/index.html', $html);
                }
                umask($uold);
            }
            $file_uploaded_dir = 'uploaded_docs/' . $establishment_code . '/' . $_SESSION['efiling_details']['efiling_no'] . '/docs/';
            if (!is_dir($file_uploaded_dir)) {
                $uold = umask(0);
                if (mkdir('uploaded_docs/' . $establishment_code . '/' . $_SESSION['efiling_details']['efiling_no'] . '/docs/', 0777, true)) {
                    $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                    write_file($file_uploaded_dir . '/index.html', $html);
                }
                umask($uold);
            }
            $filename = $doc_title;
            $filename = str_replace(' ', '_', $filename);
            $filename = preg_replace('/[^A-Za-z0-9\_]/', '', $filename);
            $filename = $_SESSION['efiling_details']['efiling_no'] . '_' . $filename .'_'.date('YmdHis'). '.pdf';
            $data2 = array('page_no' => $page_no, 'file_name' => $filename, 'file_path' => $file_uploaded_dir . $filename);
            $merge_array_data = array_merge($data, $data2);
            $new_file_path = $file_uploaded_dir.$filename;
            if (file_exists("$path_main_file")) {
                copy($path_main_file, $new_file_path);
                if (file_exists("$new_file_path")) {
                    $builder = $this->db->table('efil.tbl_uploaded_pdfs');
                    $builder->INSERT($merge_array_data);
                    if ($this->db->insertID()) {
                        unlink($path_main_file);
                        $this->db->transComplete();
                    }
                    if ($this->db->transStatus() === FALSE) {
                        return false;
                        return 'trans_failed';
                    } else {
                        return true;
                        return 'trans_success';
                    }
                    return true;
                }
            }
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
        $builder->set(array('breadcrumb_status' => $new_breadcrumbs));
        $builder->UPDATE();
        if ($this->db->affectedRows() > 0) {
            $_SESSION['efiling_details']['breadcrumb_status'] = $new_breadcrumbs;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_uploaded_pdfs($id) {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs_jsonai json');
        $builder->SELECT("json.*,tens.stage_id,tens.is_active as efiling_is_active");        
        $builder->join('efil.tbl_efiling_num_status tens', "json.registration_id =tens.registration_id  and tens.is_active=".$this->db->escape('true'),'left');
        $builder->WHERE('json.uploaded_by', $id);
        $builder->WHERE('json.is_deleted', FALSE);
        $builder->WHERE('json.api_stage_id', 1);
        $builder->orderBy('json.iitm_api_json_updated_on','DESC');
        $query = $builder->get();
        if ($query === false) {
            $error = $this->db->error();
            $result = [];
        } else {
            $result = $query->getResultArray();
        }
        return $result;
    }

}