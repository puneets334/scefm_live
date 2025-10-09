<?php

namespace App\Models\Filehashing;
use CodeIgniter\Model;

class PdfHashTasksModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function save_pdf_hash_task($registration_id, $efiling_no, $mapped_pspdfkit_document_id, $pspdfkit_document_id, $max_submit_count, $title) {
        $data = array('registration_id'=>$registration_id,'efiling_no'=>$efiling_no,'mapped_pspdfkit_document_id'=>$mapped_pspdfkit_document_id,'pspdfkit_document_id'=>$pspdfkit_document_id, 'submit_count'=>$max_submit_count, 'doc_title'=>$title);
        $builder = $this->db->table('efil.tbl_pdf_hash_tasks');
        $builder->INSERT($data);
        if ($this->db->insertID()) {
            return true;
        } else {
            return false;
        }
    }

    public function get_max_submit_count($efiling_no) {
        $builder = $this->db->table('efil.tbl_pdf_hash_tasks tasks');
        $builder->SELECT('MAX(tasks.submit_count) as submit_count');
        $builder->WHERE('tasks.efiling_no',$efiling_no);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return (int)$result[0]['submit_count'];
        } else {
            return 0;
        }
    }
    
}