<?php

namespace App\Models\NewCaseQF;

use CodeIgniter\Model;

class Court_Fee_model extends Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $db = \Config\Database::connect();
    }



    function get_uploaded_pages_count($registration_id)
    {
        /*changes started on 08 September 2020
        Earlier code were written to fetch only those pages which were indexed*/

        /*$this->db->SELECT('SUM(docs.page_no) uploaded_pages_count');
        $this->db->FROM('efil.tbl_efiled_docs docs');
        $this->db->WHERE('docs.registration_id', $registration_id);        
        $this->db->WHERE('docs.is_deleted', FALSE);
        $this->db->GROUP_BY('docs.registration_id', 'ASC');*/

        $builder = $this->db->table('efil.tbl_uploaded_pdfs docs');
        $builder->select('SUM(docs.page_no) as uploaded_pages_count');
        $builder->where('docs.registration_id', $registration_id);
        $builder->where('docs.is_deleted', FALSE);
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
    $builder->where('cfp.is_deleted', FALSE);
    $builder->orderBy('cfp.id', 'DESC');

    $query = $builder->get();

    if ($query->getNumRows() >= 1) {
        return $query->getResultArray();
    } else {
        return FALSE;
    }
}


public function insert_pg_request($data_to_save)
{
    $builder = $this->db->table('efil.tbl_court_fee_payment');
    $builder->insert($data_to_save);

    if ($builder->affectedRows() > 0) {
        return true;
    } else {
        return false;
    }
}

public function get_court_fee_details($reg_id)
{
    // Validate input
    if (!is_numeric($reg_id)) {
        return false;
    }

    // Build the query using Query Builder
    $builder = $this->db->table('efil.tbl_case_details cd');
    $builder->select('cf.court_fee, ct.casename, subj.sub_name4, orders_challendged, cd.*');
    $builder->join('icmis.casetype ct', 'ct.casecode = cd.sc_case_type_id', 'LEFT');
    $builder->join('icmis.submaster subj', 'subj.id = cd.subject_cat', 'LEFT');
    $builder->join('icmis.m_court_fee cf', 'cf.casetype_id = ct.casecode AND subj.id = cf.submaster_id', 'LEFT');
    $builder->join('(SELECT COUNT(id) AS orders_challendged FROM efil.tbl_lower_court_details 
                    WHERE registration_id = ' . $reg_id . ' AND is_judgment_challenged = true AND is_deleted = false) a', '1=1', 'LEFT');
    $builder->where('cd.registration_id', $reg_id);

    // Execute the query
    $query = $builder->get();

    // Check if there are results
    if ($query->getNumRows() >= 1) {
        return $query->getResultArray();
    } else {
        return false;
    }
}


function save_court_fee_details($reg_id)
{
    // Prepare the data to be inserted
    $data = array(
        'registration_id' => $reg_id,
        'entry_for_type_id' => '',
        'entry_for_id' => '',
        'user_declared_court_fee' => '',
        'user_declared_total_amt' => '',
        'received_amt' => '',
        'gras_payment_status' => ''
    );

    // Insert data into the table using Query Builder
    $this->db->insert('tbl_court_fee_payment', $data);

    // Return the insert ID
    return $this->db->insertID();
}

}
