<?php

namespace App\Models\MiscellaneousDocs;


use CodeIgniter\Model;

class CourtFeeModel extends Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $db      = \Config\Database::connect();
    }



    public function get_uploaded_pages_count($registration_id)
    {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs docs');
        $builder->select('SUM(docs.page_no) as uploaded_pages_count');
        $builder->where('docs.registration_id', $registration_id);
        $builder->where('docs.is_deleted', false);
        $builder->groupBy('docs.registration_id');

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getRow();
            return $result->uploaded_pages_count;
        } else {
            return 0;
        }
    }

    public function get_payment_details($registration_id)
    {
        $builder = $this->db->table('efil.tbl_court_fee_payment cfp');
        $builder->select('*');
        $builder->where('cfp.registration_id', $registration_id);
        $builder->where('cfp.is_deleted', false);
        $builder->orderBy('cfp.id', 'DESC');

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function insert_pg_request($data_to_save)
    {
        $builder = $this->db->table('efil.tbl_court_fee_payment');
        $builder->insert($data_to_save);

        if ($this->db->insertID()) {
            return true;
        } else {
            return false;
        }
    }
}
