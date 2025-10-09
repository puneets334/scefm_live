<?php

namespace App\Models\MiscellaneousDocs;

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
        $builder=$this->db->table('efil.tbl_court_fee_payment cfp');
            $builder->select('*')
            ->where('cfp.registration_id', $registration_id)
            ->where('cfp.is_deleted', false)
            ->orderBy('cfp.id', 'DESC');
            $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    public function get_filing_for_parties($registration_id)
    {
        $query = $this->db->table('efil.tbl_misc_docs_ia misc_ia')
            ->select('sc.*, misc_ia.p_r_type, misc_ia.filing_for_parties')
            ->join('efil.tbl_sci_cases sc', 'misc_ia.diary_no = sc.diary_no AND misc_ia.diary_year = sc.diary_year')
            ->where('misc_ia.registration_id', $registration_id)
            ->where('misc_ia.is_deleted', false)
            ->where('sc.is_deleted', false)
            ->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_index_items_list($registration_id)
    {
        $query = $this->db->table('efil.tbl_efiled_docs ed')
            ->select('ed.*, dm.docdesc')
            ->join('icmis.docmaster dm', 'ed.doc_type_id = dm.doccode AND ed.sub_doc_type_id = dm.doccode1 and dm.display != \'N\'')
            ->where('ed.registration_id', $registration_id)
            ->where('ed.is_active', true)
            ->where('ed.is_deleted', false)
            ->orderBy('ed.index_no')
            ->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    public function get_index_item_file($doc_id)
    {
        $query = $this->db->table('efil.tbl_efiled_docs ed')
            ->select('ed.file_path, ed.file_name, ed.doc_title')
            ->where('ed.doc_id', $doc_id)
            ->where('ed.is_active', true)
            ->where('ed.is_deleted', false)
            ->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_share_email_details_user($registration_id)
    {
        $query = $this->db->table('efil.tbl_doc_share_email dse')
            ->select('dse.*, usr.first_name')
            ->join('efil.tbl_users usr', 'usr.id = dse.updated_by', 'left')
            ->where('registration_id', $registration_id)
            ->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
    //End of function get_share_email_details_user()..


}
