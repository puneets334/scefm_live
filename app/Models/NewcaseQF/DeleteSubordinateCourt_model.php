<?php

namespace App\Models\NewCaseQF;

use CodeIgniter\Model;

class DeleteSubordinateCourt_model extends Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $db = \Config\Database::connect();
    }

    public function delete_case_subordinate_court($registration_id, $id, $firData = null)
    {
        $curr_dt_time = date('Y-m-d H:i:s');
        $data = [
            'is_deleted' => true,
            'deleted_by' => session()->get('login')['id'], // Adjust session handling as per CI4
            'deleted_on' => $curr_dt_time,
            'deleted_by_ip' => $this->request->getIPAddress(), // Assuming getClientIP() is replaced by CI4 method
        ];

        $this->db->where('registration_id', $registration_id)
            ->where('id', $id)
            ->where('is_deleted', false)
            ->update('tbl_lower_court_details', $data);

        if ($this->db->affectedRows() > 0) {
            if (!empty($firData)) {
                $fir_data = [
                    'is_deleted' => true,
                    'updated_by' => session()->get('login')['id'], // Adjust session handling
                    'updated_on' => $curr_dt_time,
                    'updated_by_ip' => $this->request->getIPAddress(),
                    'status' => false,
                ];

                $this->db->where('registration_id', $registration_id)
                    ->where('ref_tbl_lower_court_details_id', $id)
                    ->where('is_deleted', false)
                    ->update('tbl_fir_details', $fir_data);

                if ($this->db->affectedRows() > 0) {
                    return true;
                } else {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }
}
