<?php

namespace App\Models\NewCaseQF;

use CodeIgniter\Model;

class Act_sections_model extends Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $db      = \Config\Database::connect();
    }


    public function get_master_acts_list()
    {
        $db = \Config\Database::connect(); // Get the database connection

        $builder = $db->table('icmis.act_master');
        $builder->select('id as act_id, act_name, year as act_year'); // Select specific columns with aliases
        $builder->where('is_approved', 'Y'); // Apply the WHERE condition
        $builder->where('display', 'Y'); // Apply another WHERE condition
        $builder->orderBy('id', 'asc'); // Order the results by ID in ascending order

        $query = $builder->get(); // Execute the query

        return $query->getResultArray(); // Return the result as an array of arrays
    }


    /*changes started on 07 September 2020*/
    //changes done here were reverted on 10 September to accommodate other changes

    public function add_case_act_sections($registration_id, $data, $breadcrumb_step)
    {
        $this->db->transStart(); // Start transaction

        // Update breadcrumbs
        $this->update_breadcrumbs($registration_id, $breadcrumb_step);

        // Insert data into table
        $builder = $this->db->table('efil.tbl_act_sections');
        $builder->insert($data);

        $this->db->transComplete(); // Complete transaction

        if ($this->db->transStatus() === FALSE) {
            return FALSE; // Transaction failed
        } else {
            return $this->db->insertID(); // Return the inserted ID
        }
    }


    //new function added to add act in master table on 10 September 2020
    public function add_master_acts_list($data)
    {
        $this->db->transStart(); // Start transaction

        $this->db->insert('icmis.act_master', $data); // Insert data into 'icmis.act_master'

        $this->db->transComplete(); // Complete transaction

        if ($this->db->transStatus() === FALSE) {
            return FALSE; // Transaction failed
        } else {
            // Transaction succeeded, fetch the inserted act_id
            $this->db->selectMax('id as act_id');
            $this->db->from('icmis.act_master');
            $this->db->where($data);
            $this->db->groupBy('act_name, act_name_h, year, actno, state_id, display, old_id, old_act_code, is_approved');
            $query = $this->db->get();

            if ($query->getNumRows() > 0) {
                $row = $query->getRow();
                $act_id = $row->act_id;
                return $act_id;
            } else {
                return FALSE;
            }
        }
    }

    /*
    function add_case_act_sections($registration_id, $data, $breadcrumb_step,$act_id) {

        $this->db->trans_start();
        $this->update_breadcrumbs($registration_id, $breadcrumb_step);
        if($act_id=='others')
        {
            $this->db->INSERT('icmis.act_master_temp', $data);
        }else
        {
            $this->db->INSERT('efil.tbl_act_sections', $data);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return $this->db->insert_id();
        }
    }*/

    /*changes end*/

    public function get_act_sections_list($registration_id)
    {
        $builder = $this->db->table('efil.tbl_act_sections act'); // Define the table using Query Builder

        $builder->select('DISTINCT act.id, act.act_id, act_m.act_name, act_m.year AS act_year, act.act_section, act_m.is_approved', false); // Select statement with DISTINCT and false to prevent CI4 from quoting fields

        $builder->join('icmis.act_master act_m', 'act.act_id = act_m.id'); // Join with act_master table

        $builder->where('act.registration_id', $registration_id); // Where clause for registration_id
        $builder->where('act.is_deleted', FALSE); // Where clause for is_deleted column in tbl_act_sections
        $builder->where('act_m.display', 'Y'); // Where clause for display column in act_master

        $builder->orderBy('act.id', 'asc'); // Order by id in ascending order

        $query = $builder->get(); // Execute the query

        return $query->getResult(); // Return the result as an array of objects
    }


    public function delete_act_sections($registration_id, $act_id)
    {
        $curr_dt_time = date('Y-m-d H:i:s');
        $data = [
            'is_deleted' => true,
            'deleted_by' => session()->get('login')['id'], // Assuming session() helper is loaded in CI4
            'deleted_on' => $curr_dt_time,
            'deleted_by_ip' => $this->request->getIPAddress() // Using CI4's Request service to get client IP
        ];

        $builder = $this->db->table('efil.tbl_act_sections'); // Initialize the Query Builder for tbl_act_sections

        $builder->where('registration_id', $registration_id); // Where clause for registration_id
        $builder->where('id', $act_id); // Where clause for act_id
        $builder->where('is_deleted', false); // Ensure record is not already deleted
        $builder->update($data); // Perform the update with the prepared data

        if ($builder->affectedRows() > 0) {
            return true; // Return true if any rows were affected
        } else {
            return false; // Return false if no rows were affected
        }
    }


    public function update_breadcrumbs($registration_id, $step_no)
    {
        // Access session service to update breadcrumb status
        $session = session();

        // Retrieve existing breadcrumb status from session
        $old_breadcrumbs = $session->get('efiling_details.breadcrumb_status', '');

        // Combine old breadcrumbs with new step number
        $old_breadcrumbs .= ',' . $step_no;

        // Convert to array and remove duplicates
        $old_breadcrumbs_array = array_unique(array_filter(explode(',', $old_breadcrumbs)));

        // Sort the array
        sort($old_breadcrumbs_array);

        // Construct new breadcrumb status string
        $new_breadcrumbs = implode(',', $old_breadcrumbs_array);

        // Update breadcrumb status in session
        $session->set('efiling_details.breadcrumb_status', $new_breadcrumbs);

        // Initialize Query Builder for tbl_efiling_nums table
        $builder = $this->db->table('efil.tbl_efiling_nums');

        // Set WHERE condition
        $builder->where('registration_id', $registration_id);

        // Update breadcrumb_status field with new breadcrumbs
        $builder->update(['breadcrumb_status' => $new_breadcrumbs]);

        // Check if any rows were affected
        if ($builder->affectedRows() > 0) {
            return true; // Return true if update was successful
        } else {
            return false; // Return false if no rows were affected
        }
    }

    public function update_master_acts($master_act_section, $act_id)
    {
        // Get database connection
        $db = \Config\Database::connect();

        // Initialize Query Builder for act_master table
        $builder = $db->table('icmis.act_master');

        // Set WHERE condition
        $builder->where('id', $act_id);

        // Update master_act_section data
        $builder->update($master_act_section);

        // Check if any rows were affected
        if ($builder->affectedRows() > 0) {
            return true; // Return true if update was successful
        } else {
            return false; // Return false if no rows were affected
        }
    }


    public function update_case_act_sections($act_sections_details, $id_approval)
    {
        // Initialize Query Builder for tbl_act_sections table
        $builder = $this->db->table('efil.tbl_act_sections');

        // Set WHERE condition
        $builder->where('id', $id_approval);

        // Update act_sections_details data
        $builder->update($act_sections_details);

        // Check if any rows were affected
        if ($builder->affectedRows() > 0) {
            return true; // Return true if update was successful
        } else {
            return false; // Return false if no rows were affected
        }
    }
}
