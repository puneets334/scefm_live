<?php

namespace App\Models\Mentioning;

use CodeIgniter\Model;

class ViewModel extends Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $db      = \Config\Database::connect();
    }


    public function get_payment_details($registration_id)
    {
        $builder = $this->db->table('tbl_court_fee_payment cfp');
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



    public function get_filing_for_parties($registration_id)
    {
        $builder = $this->db->table('efil.tbl_misc_docs_ia misc_ia');
        $builder->select('sc.*, misc_ia.p_r_type, misc_ia.filing_for_parties');
        $builder->join('efil.tbl_sci_cases sc', 'misc_ia.diary_no = sc.diary_no AND misc_ia.diary_year = sc.diary_year');
        $builder->where('misc_ia.registration_id', $registration_id);
        $builder->where('misc_ia.is_deleted', false);
        $builder->where('sc.is_deleted', false);

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

  

   public function get_uploaded_pdfs($registration_id)
    {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs');
        $builder->select('doc_id, doc_title, file_name, page_no, uploaded_by, uploaded_on, upload_ip_address, file_path, doc_hashed_value');
        $builder->where('registration_id', $registration_id);
        $builder->where('is_deleted', false);
        $builder->orderBy('doc_id');

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_mentioning_orders($registration_id)
    {
        $builder = $this->db->table('efil.tbl_mentioning_orders');
        $builder->select('*');
        $builder->where('registration_id', $registration_id);
        $builder->where('is_deleted', false);
        $builder->orderBy('id');

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
}
