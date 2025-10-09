<?php

namespace App\Models\AdjournmentLetter;

use CodeIgniter\Model;

class AdjournmentModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    function getAdjournmentRequests($advocateId = "", $diaryId = "", $listedOn = "", $filedByOther = "", $listed_dateF = "")
    {
        $today_date = date('Y-m-d');
        $builder = $this->db->table('efil.tbl_adjournment_details tad');
        if (!empty($listed_dateF) && $listed_dateF != null) {
            $builder->SELECT('distinct tad.listed_date', false);
        } else {
            $builder->SELECT('distinct tad.*,tu.first_name,tard.tbl_adjournment_details_id,tard.consent,tard.id as doc_id,reply.first_name as reply_first_name,tard.created_by as reply_created_by', false);
        }
        $builder->JOIN('efil.tbl_adjournment_reply_details tard', 'tad.id=tard.tbl_adjournment_details_id', 'left');
        $builder->JOIN('efil.tbl_users tu', 'tad.created_by=tu.id', 'left');
        $builder->JOIN('efil.tbl_users reply', 'tard.created_by=reply.id', 'left');
        if (!empty($diaryId)) {
            if (is_array($diaryId)) {
                $builder->WHEREIN('tad.diary_number', $diaryId);
            } else {
                $builder->WHERE('tad.diary_number', $diaryId);
            }
        }
        if (!empty($filedByOther)) {
            $builder->WHERE('tad.created_by!=', $advocateId);
        } else if (!empty($advocateId) && $advocateId != null) {
            $builder->WHERE('tad.created_by', $advocateId);
        }
        if (!empty($listedOn) && $listedOn != null) {
            $builder->WHERE('tad.listed_date', $listedOn);
        }
        $builder->WHERE('tad.is_deleted', false);
        $query = $builder->get();
        $result = $query->getResult();
        if (!empty($listed_dateF) && $listed_dateF != null) {
            return $result;
        }
        if (!empty($result)) {
            $final_data = array();
            foreach ($result as $row) {
                $results_data['case_number'] = $row->case_number;
                $results_data['diary_number'] = $row->diary_number;
                $results_data['listed_date'] = $row->listed_date;
                $results_data['item_number'] = $row->item_number;
                $results_data['court_number'] = $row->court_number;
                $results_data['id']          = $row->id;
                $results_data['first_name']  = $row->first_name;
                $results_data['created_by']  = $row->created_by;
                $results_data['created_at']  = $row->created_at;
                $results_data['doc_id']      = $row->doc_id;
                $results_data['tbl_adjournment_details_id'] = $row->tbl_adjournment_details_id;
                if (!empty($row->doc_id) && $row->doc_id != null) {
                    $results_data['reply_list'] = $this->get_adjournment_Others("", "", $row->tbl_adjournment_details_id);
                } else {
                    $results_data['reply_list'] = [];
                }
                $final_data[] = (object)$results_data;
            }
            return $final_data;
        } else {
            $final_data = array();
            return $final_data;
        }
    }

    public function get_adjournment_Others($doc_id = null, $reply_user = null, $tbl_adjournment_details_id = null)
    {
        $builder = $this->db->table('efil.tbl_adjournment_reply_details tard');
        $builder->SELECT('tard.tbl_adjournment_details_id,tard.consent,tard.id as doc_id,reply.first_name as reply_first_name,tard.created_by as reply_created_by,tard.created_at');
        $builder->JOIN('efil.tbl_users reply', 'tard.created_by=reply.id', 'left');
        if (!empty($doc_id) && $doc_id != null) {
            $builder->WHERE('tard.id', $doc_id);
        }
        if (!empty($tbl_adjournment_details_id) && $tbl_adjournment_details_id != null) {
            $builder->WHERE('tard.tbl_adjournment_details_id', $tbl_adjournment_details_id);
        }
        if (!empty($reply_user) && $reply_user != null) {
            $builder->WHERE('tard.created_by', $doc_id);
        }
        $builder->WHERE('tard.is_deleted', false);
        $builder->ORDERBY('tard.id', 'asc');
        $query = $builder->get();
        return $result = $query->getResult();
    }

    function upload_pdfs($data, $file_temp_name)
    {
        $this->db->transStart();
        $est_dir = 'uploaded_docs/adjournment_letters';
        if (!is_dir($est_dir)) {
            $uold = umask(0);
            if (mkdir('uploaded_docs/adjournment_letters', 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($est_dir . '/index.html', $html);
            }
            umask($uold);
        }
        $efil_num_dir = 'uploaded_docs/adjournment_letters' .  '/' . $_SESSION['login']['id'];
        if (!is_dir($efil_num_dir)) {
            $uold = umask(0);
            if (mkdir($efil_num_dir, 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($efil_num_dir . '/index.html', $html);
            }
            umask($uold);
        }
        $file_uploaded_dir = 'uploaded_docs/adjournment_letters' .  '/' . $_SESSION['login']['id'] . '/docs/';
        if (!is_dir($file_uploaded_dir)) {
            $uold = umask(0);
            if (mkdir('uploaded_docs/adjournment_letters' .  '/' . $_SESSION['login']['id'] . '/docs/', 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($file_uploaded_dir . '/index.html', $html);
            }
            umask($uold);
        }
        $filename = $data['diary_number'] . $data['listed_date'] . '-' . $data['created_by'];
        $filename = $filename . '.pdf';
        $result = $this->upload_file($file_uploaded_dir, $filename, $file_temp_name);
        $file_path = getcwd() . '/' . $file_uploaded_dir . $filename;
        $page_no = (int)@exec('pdfinfo ' . $file_path . ' | awk \'/Pages/ {print $2}\'', $output);
        $data2 = array('page_no' => $page_no, 'file_name' => $filename, 'file_path' => $file_uploaded_dir . $filename);
        $dataF = array('diary_number' => $data['diary_number'], 'listed_date' => $data['listed_date'], 'case_number' => $data['case_number'], 'court_number' => $data['court_number'], 'item_number' => $data['item_number'], 'created_by' => $data['created_by'], 'doc_hashed_value' => $data['doc_hashed_value']);
        $merge_array_data = array_merge($dataF, $data2);
        if ($result) {
            $builder = $this->db->table('efil.tbl_adjournment_details');
            $query = $builder->INSERT($merge_array_data);
            if ($this->db->insertID()) {
                $this->db->transComplete();
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

    function upload_pdfs_Others($data, $file_temp_name)
    {
        $this->db->transStart();
        $est_dir = 'uploaded_docs/adjournment_letters/reply';
        if (!is_dir($est_dir)) {
            $uold = umask(0);
            if (mkdir('uploaded_docs/adjournment_letters/reply', 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($est_dir . '/index.html', $html);
            }
            umask($uold);
        }
        $efil_num_dir = 'uploaded_docs/adjournment_letters/reply' .  '/' . $_SESSION['login']['id'];
        if (!is_dir($efil_num_dir)) {
            $uold = umask(0);
            if (mkdir($efil_num_dir, 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($efil_num_dir . '/index.html', $html);
            }
            umask($uold);
        }
        $file_uploaded_dir = 'uploaded_docs/adjournment_letters/reply' .  '/' . $_SESSION['login']['id'] . '/docs/';
        if (!is_dir($file_uploaded_dir)) {
            $uold = umask(0);
            if (mkdir('uploaded_docs/adjournment_letters/reply' .  '/' . $_SESSION['login']['id'] . '/docs/', 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($file_uploaded_dir . '/index.html', $html);
            }
            umask($uold);
        }
        $filename = $data['diary_number'] . $data['listed_date'] . '-' . $data['created_by'];
        $filename = $filename . '.pdf';
        $dataF = array('tbl_adjournment_details_id' => $data['tbl_adjournment_details_id'], 'created_by' => $data['created_by'], 'consent' => $data['consent']);
        if (!empty($data['consent']) && $data['consent'] == 'O') {
            $result = $this->upload_file($file_uploaded_dir, $filename, $file_temp_name);
            $file_path = getcwd() . '/' . $file_uploaded_dir . $filename;
            $page_no = (int)@exec('pdfinfo ' . $file_path . ' | awk \'/Pages/ {print $2}\'', $output);
            $data2 = array('page_no' => $page_no, 'file_name' => $filename, 'file_path' => $file_uploaded_dir . $filename);
            $merge_array_data = array_merge($dataF, $data2);
        } else {
            $merge_array_data = $dataF;
            $result = true;
        }
        if ($result) {
            $builder = $this->db->table('efil.tbl_adjournment_reply_details');
            $query = $builder->INSERT($merge_array_data);
            $insert_id = $this->db->insertID();
            if ($this->db->insertID()) {
                $this->db->transComplete();
            }
            if ($this->db->transStatus() === FALSE) {
                unlink($file_uploaded_dir . '/' . $file_temp_name);
                return 'trans_failed';
            } else {
                $trans_success_return = 'trans_success' . '/' . $insert_id;
                return $trans_success_return;
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

    public function get_uploaded_pdf_file($doc_id)
    {
        $builder = $this->db->table('efil.tbl_adjournment_details');
        $builder->SELECT('file_path, file_name');
        $builder->WHERE('id', $doc_id);
        $builder->WHERE('is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function get_uploaded_pdf_file_Others($doc_id, $reply_user = null)
    {
        $builder = $this->db->table('efil.tbl_adjournment_reply_details');
        $builder->SELECT('file_path, file_name');
        $builder->WHERE('id', $doc_id);
        if (!empty($reply_user) && $reply_user != null) {
            $builder->WHERE('created_by', $doc_id);
        }
        $builder->WHERE('is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }
    
}