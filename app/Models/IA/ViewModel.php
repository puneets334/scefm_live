<?php

namespace App\Models\IA;
use CodeIgniter\Model;

class ViewModel extends Model {

    function __construct() {
        parent::__construct();
        $db = \Config\Database::connect();  
    }
    
    function get_payment_details($registration_id) {
        $builder = $this->db->table('efil.tbl_court_fee_payment cfp');  
        $builder->SELECT('cfp.*,cd.sc_diary_num,cd.sc_diary_year,cd.sc_diary_date');
        $builder->JOIN('efil.tbl_case_details cd', 'cfp.registration_id=cd.registration_id','LEFT');
        $builder->WHERE('cfp.registration_id', $registration_id);
        $builder->WHERE('cfp.is_deleted', FALSE);
        $builder->orderBy('cfp.id', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }       
    
    function get_filing_for_parties($registration_id) {
        $builder = $this->db->table('efil.tbl_misc_docs_ia misc_ia');  
        $builder->SELECT("sc.*, misc_ia.p_r_type, misc_ia.filing_for_parties");
        $builder->JOIN('efil.tbl_sci_cases sc', 'misc_ia.diary_no = sc.diary_no AND misc_ia.diary_year = sc.diary_year');
        $builder->WHERE('misc_ia.registration_id', $registration_id);
        $builder->WHERE('misc_ia.is_deleted', FALSE);        
        $builder->WHERE('sc.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }
    
    function get_index_items_list($registration_id) {
        $builder = $this->db->table('efil.tbl_efiled_docs ed');  
        $builder->SELECT('ed.*,dm.docdesc');
        $builder->JOIN('icmis.docmaster dm', 'ed.doc_type_id = dm.doccode AND ed.sub_doc_type_id = dm.doccode1 and dm.display!=\'N\'');
        $builder->WHERE('ed.registration_id', $registration_id);
        $builder->WHERE('ed.is_active', TRUE);
        $builder->WHERE('ed.is_deleted', FALSE);
        $builder->orderBy('ed.index_no');
        $query = $builder->get(); 
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function get_index_item_file($doc_id) {
        $builder = $this->db->table('efil.tbl_efiled_docs ed');  
        $builder->SELECT('ed.file_path, ed.file_name, ed.doc_title');
        $builder->WHERE('ed.doc_id', $doc_id);
        $builder->WHERE('ed.is_active', TRUE);
        $builder->WHERE('ed.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }    
    
}