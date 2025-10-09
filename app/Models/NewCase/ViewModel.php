<?php

namespace App\Models\NewCase;

use CodeIgniter\Model;

class ViewModel extends Model
{
    protected $session;

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    // function get_payment_details_old($registration_id)
    // {
    // $this->db->SELECT('cfp.*,cd.sc_diary_num,cd.sc_diary_year,cd.sc_diary_date');
    // $this->db->FROM('efil.tbl_court_fee_payment cfp');
    // $this->db->JOIN('efil.tbl_case_details cd', 'cfp.registration_id=cd.registration_id','LEFT');
    // $this->db->WHERE('cfp.registration_id', $registration_id);        
    // $this->db->WHERE('cfp.is_deleted', FALSE);
    // $this->db->ORDER_BY('cfp.id', 'DESC');
    // $query = $this->db->get();
    // if ($query->num_rows() >= 1) {
    //     $result = $query->result_array();
    //     return $result;
    // } else {
    //     return FALSE;
    // }        
    // }

    function get_payment_details($registration_id)
    {
        $builder = $this->db->table('efil.tbl_court_fee_payment cfp');
        $builder->select('cfp.*,cd.sc_diary_num,cd.sc_diary_year,cd.sc_diary_date');
        $builder->join('efil.tbl_case_details cd', 'cfp.registration_id=cd.registration_id', 'LEFT');
        $builder->where('cfp.registration_id', $registration_id);
        $builder->where('cfp.is_deleted', FALSE);
        $builder->orderBy('cfp.id', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    // function get_index_items_list_old($registration_id,$re_filed=0)
    // {
    // $this->db->SELECT('DISTINCT ed.*,dm.docdesc',FALSE); //,sub_dm.docdesc
    // $this->db->FROM('efil.tbl_efiled_docs ed');
    // $this->db->JOIN('icmis.docmaster dm', 'ed.doc_type_id = dm.doccode AND ed.sub_doc_type_id = dm.doccode1 and dm.display!=\'N\'');
    // //$this->db->JOIN('icmis.docmaster sub_dm', 'ed.sub_doc_type_id = sub_dm.doccode1', 'LEFT');
    // $this->db->WHERE('ed.registration_id', $registration_id);
    // $this->db->WHERE('ed.is_active', TRUE);
    // $this->db->WHERE('ed.is_deleted', FALSE);
    // if($re_filed!=0){
    //     //icmis_docnum is null and icmis_docyear is null and doc_type_id =8
    //     $this->db->where('ed.icmis_docnum IS NULL', null, false);
    //     $this->db->where('ed.icmis_docyear IS NULL', null, false);
    //     $this->db->where('ed.doc_type_id',8);
    // }
    // $this->db->ORDER_BY('ed.index_no');
    // $query = $this->db->get();
    // if ($query->num_rows() >= 1) {
    //     $result = $query->result_array();
    //     return $result;
    // } else {
    //     return FALSE;
    // }
    // }

    function get_index_items_list($registration_id)
    {
        // function get_index_items_list($registration_id) {
        if (empty($registration_id)) {
            return false;
        }
        $builder = $this->db->table('efil.tbl_efiled_docs ed');
        $builder->select('DISTINCT ed.*, dm.docdesc', false);
        $builder->join('icmis.docmaster dm', 'ed.doc_type_id = dm.doccode AND ed.sub_doc_type_id = dm.doccode1 and dm.display != \'N\'');
        // $builder->join('icmis.docmaster sub_dm', 'ed.sub_doc_type_id = sub_dm.doccode1', 'LEFT');
        $builder->where('ed.registration_id', $registration_id);
        $builder->where('ed.is_active', true);
        $builder->where('ed.is_deleted', false);
        $builder->orderBy('ed.index_no');
        $query = $builder->get();
        // The following part will never be reached
        if ($query->getNumRows() >= 1) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    public function get_index_item_file($doc_id)
    {
        // used to show index pdf file on click of index item list
        $builder = $this->db->table('efil.tbl_efiled_docs ed');
        $builder->SELECT('ed.file_path, ed.file_name, ed.doc_title');
        // $builder->FROM();
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

    public function testData()
    {
        return 1;
    }
}