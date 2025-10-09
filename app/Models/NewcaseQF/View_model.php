<?php
namespace App\Models\NewCaseQF;

use CodeIgniter\Model;
class View_model extends Model {

    function __construct() {
// Call the Model constructor
        parent::__construct();
    }
    
    function get_payment_details($registration_id){
        
        $this->db->SELECT('cfp.*,cd.sc_diary_num,cd.sc_diary_year,cd.sc_diary_date');
        $this->db->FROM('efil.tbl_court_fee_payment cfp');
        $this->db->JOIN('efil.tbl_case_details cd', 'cfp.registration_id=cd.registration_id','LEFT');
        $this->db->WHERE('cfp.registration_id', $registration_id);        
        $this->db->WHERE('cfp.is_deleted', FALSE);
        $this->db->ORDER_BY('cfp.id', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            $result = $query->result_array();
            return $result;
        } else {
            return FALSE;
        }        
    }

    function get_index_items_list($registration_id,$re_filed=0) {
        $this->db->SELECT('DISTINCT ed.*,dm.docdesc',FALSE); //,sub_dm.docdesc
        $this->db->FROM('efil.tbl_efiled_docs ed');
        $this->db->JOIN('icmis.docmaster dm', 'ed.doc_type_id = dm.doccode AND ed.sub_doc_type_id = dm.doccode1 and dm.display!=\'N\'');
        //$this->db->JOIN('icmis.docmaster sub_dm', 'ed.sub_doc_type_id = sub_dm.doccode1', 'LEFT');
        $this->db->WHERE('ed.registration_id', $registration_id);
        $this->db->WHERE('ed.is_active', TRUE);
        $this->db->WHERE('ed.is_deleted', FALSE);
        if($re_filed!=0){
            //icmis_docnum is null and icmis_docyear is null and doc_type_id =8
            $this->db->where('ed.icmis_docnum IS NULL', null, false);
            $this->db->where('ed.icmis_docyear IS NULL', null, false);
            $this->db->where('ed.doc_type_id',8);
        }
        $this->db->ORDER_BY('ed.index_no');
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            $result = $query->result_array();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function get_index_item_file($doc_id) {
// used to show index pdf file on click of index item list
        $this->db->SELECT('ed.file_path, ed.file_name, ed.doc_title');
        $this->db->FROM('efil.tbl_efiled_docs ed');
        $this->db->WHERE('ed.doc_id', $doc_id);
        $this->db->WHERE('ed.is_active', TRUE);
        $this->db->WHERE('ed.is_deleted', FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result;
        } else {
            return FALSE;
        }
    }
    public function testData(){
        return 1;
    }
    
    
}
