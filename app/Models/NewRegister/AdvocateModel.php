<?php

namespace App\Models\NewRegister;

use CodeIgniter\Model;

class AdvocateModel extends Model
{

    protected $table = 'efil.tbl_users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'userid', 'first_name', 'm_address1', 'm_city', 'created_on', 'ref_m_usertype_id', 'm_pincode', 'is_active'];

    public function get_advocate_details($account_status = null)
    {
        $builder = $this->db->table($this->table)
            ->select('id, userid, first_name, m_address1, m_city, created_on, ref_m_usertype_id, m_pincode');

        if (!empty($account_status)) {
            $builder->where('account_status', $account_status);
        }

        $builder->limit(500);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return false;
        }
    }



    // public function get_details_by_id($id) {
    //     $this->db->SELECT('*');
    //     $this->db->FROM('efil.tbl_users');
    //     $this->db->WHERE('id', $id);
    //     $query = $this->db->get();
    //     if ($query->num_rows() >= 1) {
    //         $result = $query->result();
    //         return $result;
    //     } else {
    //         return false;
    //     }
    // }


    public function get_details_by_id($id)
    {
        $builder = $this->db->table('efil.tbl_users');
        $builder->SELECT('*');
        $builder->WHERE('id',$id);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }

        // return $this->where('id', $id)->first();
    }



    public function activateById($id)
    {
        $user = $this->find($id); // Get user data by ID

        if (!$user) {
            return false; // User not found, return false
        }

        $this->set('is_active', 1)
            ->where('id', $id)
            ->update();

        return $this->affectedRows() > 0;
    }

    public function deactivateById($id)
    {
        $user = $this->find($id); // Get user data by ID

        if (!$user) {
            return false; // User not found, return false
        }

        $this->set('is_active', 0)
            ->where('id', $id)
            ->update();

        return $this->affectedRows() > 0;
    }
}
