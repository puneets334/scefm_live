<?php

namespace App\Models\NewCaseQF;

use CodeIgniter\Model;

class AdditionalAdv_model extends Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $db      = \Config\Database::connect();
    }

    public function get_aors_list()
    {
        // Initialize Query Builder
        $builder = $this->db->table('icmis.bar');

        // Select columns
        $builder->select('bar_id, title, name, aor_code');

        // WHERE conditions
        $builder->where('if_aor', 'Y');
        $builder->where('isdead', 'N');

        // ORDER BY clause
        $builder->orderBy('name', 'ASC');

        // Execute the query
        $query = $builder->get();

        // Check if rows are returned
        if ($query->getNumRows() > 0) {
            return $query->getResult(); // Return results as objects
        } else {
            return []; // Return an empty array if no rows found
        }
    }


    public function get_saved_aors_list($registration_id)
    {
        // Initialize Query Builder
        $builder = $this->db->table('efil.tbl_case_advocates adv');

        // Select columns
        $builder->select('adv.id, adv.registration_id, bar.title, bar.name, bar.aor_code');

        // Join with 'icmis.bar' table
        $builder->join('icmis.bar bar', 'bar.bar_id = adv.adv_bar_id AND bar.aor_code = adv.adv_code');

        // WHERE conditions
        $builder->where('adv.registration_id', $registration_id);
        $builder->where('adv.is_deleted', false); // assuming 'is_deleted' is boolean

        // ORDER BY clause
        $builder->orderBy('adv.id', 'ASC');

        // Execute the query
        $query = $builder->get();

        // Check if rows are returned
        if ($query->getNumRows() > 0) {
            return $query->getResult(); // Return results as objects
        } else {
            return []; // Return an empty array if no rows found
        }
    }


    public function insert_additional_case_advocates($data_to_save)
    {
        // Start transaction
        $this->db->transStart();

        // Insert data into 'efil.tbl_case_advocates' table
        $builder = $this->db->table('efil.tbl_case_advocates');
        $builder->insert($data_to_save);

        // Check if insertion was successful
        if ($builder->insertID()) {
            // Update breadcrumbs status
            $status = $this->update_breadcrumbs($data_to_save['registration_id'], NEW_CASE_ADDITIONAL_ADV);

            // Complete transaction if updating breadcrumbs was successful
            if ($status) {
                $this->db->transComplete();
            }
        }

        // Check transaction status
        if ($this->db->transStatus() === false) {
            // Transaction failed
            return false;
        } else {
            // Transaction succeeded
            return true;
        }
    }

    public function delete_additional_advocate($id, $registration_id)
    {
        // Data to update
        $data = [
            'is_deleted' => true,
            'deleted_by' => session()->get('login')['id'],
            'deleted_on' => date('Y-m-d H:i:s'),
            'deleted_by_ip' => getClientIP()
        ];

        // Start transaction
        $this->db->transStart();

        // Update 'efil.tbl_case_advocates' table
        $builder = $this->db->table('efil.tbl_case_advocates');
        $builder->where('id', $id);
        $builder->where('registration_id', $registration_id);
        $builder->update($data);

        // Check if update was successful
        if ($builder->affectedRows() > 0) {
            // Update breadcrumbs status
            $status = $this->update_breadcrumbs($registration_id, DELETE_CASE_ADDITIONAL_ADV);

            // Complete transaction if updating breadcrumbs was successful
            if ($status) {
                $this->db->transComplete();
                return true;
            }
        }

        // Rollback transaction on failure
        $this->db->transRollback();
        return false;
    }


    public function update_breadcrumbs($registration_id, $step_no)
    {
        // Get current breadcrumb status from session
        $old_breadcrumbs = $_SESSION['efiling_details']['breadcrumb_status'] ?? '';
        $old_breadcrumbs_array = explode(',', $old_breadcrumbs);

        // Add new step number to breadcrumb array
        $old_breadcrumbs_array[] = $step_no;

        // Remove duplicates and sort the array
        $new_breadcrumbs_array = array_unique($old_breadcrumbs_array);
        sort($new_breadcrumbs_array);

        // Create new breadcrumb string
        $new_breadcrumbs = implode(',', $new_breadcrumbs_array);

        // Update session breadcrumb status
        $_SESSION['efiling_details']['breadcrumb_status'] = $new_breadcrumbs;

        // Update database breadcrumb status
        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->where('registration_id', $registration_id);
        $builder->update(['breadcrumb_status' => $new_breadcrumbs]);

        // Check if update was successful
        if ($builder->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
