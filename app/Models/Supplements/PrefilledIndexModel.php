<?php
namespace App\Models\Supplements;

use CodeIgniter\Model;
class Prefilled_index_model extends Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        //$this->db = $this->load->database('db', TRUE);
    }

    /*function get_particulars_docs_data(){
        $docCd1='0';
        $this->db->SELECT('dm.doccode,dm.docdesc');
        $this->db->FROM('icmis.docmaster dm');
        $this->db->WHERE('dm.doccode1',$docCd1);
        $this->db->WHERE('dm.display', 'Y');
        $query = $this->db->get();
        //echo $this->db->last_query(); exit();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result;
        } else {
            return false;
        }
    }
    //END of function get_pdf_data_check()..*/

    public function get_pdf_store_data_indx_edit($indexID)
    {
        $session = \Config\Services::session();
        $builder = $this->db->table('efil.tbl_prefilled_index_gen');
        $builder->select('tpig.*');
        $builder->where('tpig.index_id', $indexID);
        $builder->where('tpig.created_by', $session->get('login')['id']);
        $builder->where('tpig.is_active', false);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
    //END of function indexData_Edit()..

    public function Index_pdf_data($array)
    {
        $builder = $this->db->table('efil.tbl_prefilled_index_gen');
        $builder->insertBatch($array);
        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    //End of function insert_pdf_data()..

    public function gen_indexcounter_number()
    {
        $builder = $this->db->table('efil.m_tbl_efiling_no');
        $builder->select('index_counter_no, index_counter_yr');
        $query = $builder->get();
        $row = $query->getRowArray();
        $p_efiling_num = $row['index_counter_no'];
        $year = $row['index_counter_yr'];
        if ($year < date('Y')) {
            $newYear = date('Y');
            $update_data = [
                'index_counter_no' => 1,
                'index_counter_yr' => $newYear,
                'index_update_by' => session()->get('login')['id'],
                'index_update_on' => date('Y-m-d H:i:s')
            ];
            $builder->where('index_counter_no', $p_efiling_num);
            $builder->where('index_counter_yr', $year);
            $builder->set($update_data);
            $builder->update();
            if ($this->db->affectedRows() > 0) {
                return ['index_counter_no' => 1, 'index_counter_yr' => $newYear];
            } else {
                return $this->gen_indexcounter_number(); // Retry if update fails
            }
        } else {
            $gen_efiling_num = $p_efiling_num + 1;
            $efiling_num_info = [
                'index_counter_no' => $gen_efiling_num,
                'index_update_by' => session()->get('login')['id'],
                'index_update_on' => date('Y-m-d H:i:s')
            ];
            $builder->where('index_counter_no', $p_efiling_num);
            $builder->set($efiling_num_info);
            $builder->update();
            if ($this->db->affectedRows() > 0) {
                return ['index_counter_no' => $gen_efiling_num, 'index_counter_yr' => $year];
            } else {
                return $this->gen_indexcounter_number(); // Retry if update fails
            }
        }
    }

    public function get_pdf_store_data_indx($indexID)
    {
        $builder = $this->db->table('efil.tbl_prefilled_index_gen tpig');
        $builder->select('tpig.*');
        $builder->where('tpig.index_id', $indexID);
        $builder->where('tpig.created_by', session()->get('login')['id']); // Access session securely
        $builder->where('tpig.is_active', FALSE);
        $query = $builder->get();
        // echo $builder->getCompiledSelect(); exit(); // For debugging, to see the compiled SQL query
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_pdf_store_data_mstrindx($indexID)
    {
        $builder = $this->db->table('efil.tbl_prefilled_index_gen tpig');
        $builder->select('tpig.*');
        $builder->where('tpig.index_id', $indexID);
        $builder->where('tpig.created_by', session()->get('login')['id']); // Access session securely
        $builder->where('tpig.is_active', FALSE);
        $query = $builder->get();
        // echo $builder->getCompiledSelect(); exit(); // For debugging, to see the compiled SQL query
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
    //END of function get_pdf_store_data_indx()..

    public function indx_rcd_dlt_update($indxID, $updateArray, $insertArray)
    {
        // Update existing records
        $builder = $this->db->table('efil.tbl_prefilled_index_gen');
        $builder->where('index_id', $indxID);
        $builder->set($insertArray);
        $builder->update(); // Update with $insert_array_dlt
        // Check if update was successful
        if ($this->db->affectedRows() > 0) {
            // Insert new batch of records
            $this->db->table('efil.tbl_prefilled_index_gen')->insertBatch($updateArray);
            // Check if insert was successful
            if ($this->db->affectedRows() > 0) {
                $this->db->transCommit(); // Commit transaction
                return true;
            } else {
                $this->db->transRollback(); // Rollback transaction on failure
                return false;
            }
        } else {
            $this->db->transRollback(); // Rollback transaction on failure
            return false;
        }
    }

    public function get_genrateindx_list()
    {
        $builder = $this->db->table('efil.tbl_prefilled_index_gen'); // Table name
        $builder->select('count(tpig.index_id) as count_index_id, tpig.index_id, tpig.created_on'); // SELECT clause
        $builder->where('tpig.created_by', session()->get('login')['id']); // WHERE clause
        $builder->where('tpig.is_active', FALSE); // WHERE clause
        $builder->groupBy(['index_id', 'created_on']); // GROUP BY clause
        $builder->orderBy('index_id', 'ASC'); // ORDER BY clause
        $query = $builder->get(); // Execute the query
        // Check if there are rows returned
        if ($query->getNumRows() > 0) {
            return $query->getResultArray(); // Return result as array
        } else {
            return false; // Return false if no rows found
        }
    }

}