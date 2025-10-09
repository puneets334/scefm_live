<?php

namespace App\Models\Assistance;
use CodeIgniter\Model;

class NoticeModel extends Model {

    function __construct() {
        parent::__construct();
    }

    function insert_notice($array, $file_temp_name) {
        $builder = $this->db->table('efil.tbl_news');
        $builder->INSERT($array);
        if ($this->db->insertID()) {
            if (!empty($file_temp_name)) {
                $file_uploaded_dir = 'Notice/';
                $filename = $array['file_name'];
                if (!is_dir($file_uploaded_dir)) {
                    $uold = umask(0);
                    if (mkdir($file_uploaded_dir, 0777, true)) {
                        $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                        write_file($file_uploaded_dir . '/index.html', $html);
                    }
                    umask($uold);
                }
                $result = $this->upload_file($file_uploaded_dir, $filename, $file_temp_name);
                if ($result) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    function update_notice_by_id($id, $created_by, $data_update, $file_temp_name) {
        $builder = $this->db->table('efil.tbl_news');
        $builder->WHERE('created_by', $created_by);
        $builder->WHERE('id', $id);
        $builder->WHERE('is_notice', TRUE);
        $builder->UPDATE($data_update);
        if ($this->db->affectedRows() > 0) {
            if (!empty($file_temp_name)) {
                $file_uploaded_dir = 'Notice/';
                $filename = $data_update['file_name'];
                if (!is_dir($file_uploaded_dir)) {
                    $uold = umask(0);
                    if (mkdir($file_uploaded_dir, 0777, true)) {
                        $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                        write_file($file_uploaded_dir . '/index.html', $html);
                    }
                    umask($uold);
                }
                $result = $this->upload_file($file_uploaded_dir, $filename, $file_temp_name);
                if ($result) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return TRUE;
            }
        } else {
            return FALSE;
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

    function notice_list() {
        $builder = $this->db->table('efil.tbl_news');
        $builder->SELECT('*');
        $builder->WHERE('created_by', $_SESSION['login']['id']);
        $builder->WHERE('is_notice', TRUE);
        $builder->orderBy('id', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function get_notice_by_id($id, $created_by) {
        $builder = $this->db->table('efil.tbl_news');
        $builder->SELECT('*');
        $builder->WHERE('created_by', $created_by);
        $builder->WHERE('id', $id);
        $builder->WHERE('is_notice', TRUE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function update_status_notice_by_id($id, $created_by, $data_update) {
        $builder = $this->db->table('efil.tbl_news');
        $builder->WHERE('created_by', $created_by);
        $builder->WHERE('id', $id);
        $builder->UPDATE($data_update);
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function view_notice_pdf($id) {
        $builder = $this->db->table('efil.tbl_news');
        $builder->SELECT('file_name,file_uploaded_path');
        $builder->WHERE('id', $id);
        $builder->WHERE('is_notice', TRUE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function notice_list_by_category() {
        if (!empty($_SESSION['login']['state_code'])) {
            $state_code = $_SESSION['login']['m_state_id'];
        } else {
            $state_code = $_SESSION['login']['m_state_id'];
        }
        $builder = $this->db->table('efil.tbl_news');
        $builder->SELECT('*');
        $builder->WHERE('is_deleted', TRUE);
        $builder->WHERE('is_notice', TRUE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

}