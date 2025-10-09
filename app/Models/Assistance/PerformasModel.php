<?php

namespace App\Models\Assistance;
use CodeIgniter\Model;

class PerformasModel extends Model {

    function __construct() {
        parent::__construct();
    }

    function insert_news($array, $file_temp_name) {
        $builder = $this->db->table('efil.tbl_performas');
        $query = $builder->insert($array);
        if ($this->db->insertID()) {
            if (!empty($file_temp_name)) {
                $file_uploaded_dir = 'news/';
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

    function update_news_by_id($id, $data_update, $file_temp_name = null) {
        $builder = $this->db->table('efil.tbl_performas');
        $builder->where('id', $id);
        $query = $builder->update($data_update);
        if ($this->db->affectedRows() > 0) {
            if (!empty($file_temp_name)) {
                $file_uploaded_dir = 'news/';
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

    function notice_circulars_list() {
        $builder = $this->db->table('efil.tbl_performas');
        $builder->select('*');
        $builder->where('is_deleted', FALSE);
        $builder->orderBy('id', 'DESC');
        $query = $builder->get();
        $output = $query->getResultArray();
        return $output;
    }

    function get_news_by_id($id) {
        $builder = $this->db->table('efil.tbl_performas');
        $builder->select('*');
        $builder->where('id', $id);
        $query = $builder->get();
        if (count($query->getResult()) > 0) {
            $output = $query->getResultArray();
            return $output;
        } else {
            return false;
        }
    }
    
}