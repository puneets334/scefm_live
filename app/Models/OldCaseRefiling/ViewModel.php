<?php
namespace App\Models\OldCaseRefiling;

use CodeIgniter\Model;

class ViewModel extends Model {

    function __construct() {
// Call the Model constructor
        parent::__construct();
        $db      = \Config\Database::connect();

    }
    
    function get_payment_details($registration_id){
        $builder = $this->db->table('efil.tbl_court_fee_payment cfp');
        $builder->SELECT('*');       
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
       
    
    function get_filing_for_parties($registration_id){
        $builder = $this->db->table('efil.tbl_misc_docs_ia misc_ia');
        $builder->SELECT("sc.*, misc_ia.p_r_type, misc_ia.filing_for_parties");
        $builder->JOIN('efil.tbl_sci_cases sc', 'misc_ia.diary_no = sc.diary_no AND misc_ia.diary_year = sc.diary_year');
        $builder->WHERE('misc_ia.registration_id', $registration_id);
        $builder->WHERE('misc_ia.is_deleted', FALSE);        
        $builder->WHERE('sc.is_deleted', FALSE);
        $query = $builder->get(); //echo  $this->db->last_query();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }
    
    function get_index_items_list($registration_id) {
        $builder = $this->db->table('efil.tbl_efiled_docs ed');
        $builder->SELECT('ed.*,dm.docdesc'); //,sub_dm.docdesc
        $builder->JOIN('icmis.docmaster dm', 'ed.doc_type_id = dm.doccode AND ed.sub_doc_type_id = dm.doccode1 and dm.display!=\'N\'');
        //$this->db->JOIN('icmis.docmaster sub_dm', 'ed.sub_doc_type_id = sub_dm.doccode1', 'LEFT');
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
// used to show index pdf file on click of index item list
        $builder = $this->db->table('efil.tbl_efiled_docs ed'); 
        $builder->SELECT('ed.file_path, ed.file_name, ed.doc_title');
        $builder->WHERE('ed.doc_id', $doc_id);
        $builder->WHERE('ed.is_active', TRUE);
        $builder->WHERE('ed.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function get_share_email_details_user($registration_id) {
        $builder = $this->db->table('efil.tbl_doc_share_email dse'); 
        $builder->SELECT("dse.*,usr.first_name");
        $builder->join('efil.tbl_users usr', 'usr.id = dse.updated_by','left');
        $builder->WHERE('registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return false;
        }
    }//End of function get_share_email_details_user()..
    
    
}
