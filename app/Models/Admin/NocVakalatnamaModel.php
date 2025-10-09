<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class NocVakalatnamaModel extends Model
{

    function __construct()
    {
        parent::__construct();
        $db      = \Config\Database::connect();
    }

    function get_case_details($efiling_num)
    {
        $output = false;
        $builder = $this->db->table('efil.tbl_efiling_nums en');
        $builder->select('en.registration_id, cd.cause_title, tu.id, tu.first_name,efiling_no, tu.aor_code');
        $builder->join('efil.tbl_case_details cd', 'cd.registration_id = en.registration_id', 'left');
        $builder->join('efil.tbl_users tu', 'tu.id=en.created_by');
        $builder->where('efiling_no', $efiling_num);
        $query = $builder->get();
        $output = $query->getRow();
        return $output;
    }

    function get_aor_users()
    {
        $output = false;
        $builder = $this->db->table('efil.tbl_users tu');
        $builder->select('id, first_name,aor_code');
        $builder->where('ref_m_usertype_id', 1);
        $query = $builder->get();
        $output = $query->getResult();
        return $output;
    }

    function update_aor_in_case($registration_id, $new_aor)
    {
        $builder = $this->db->table('efil.tbl_efiling_nums en');
        $builder->SELECT('created_by');
        $builder->where('registration_id', $registration_id);
        $query = $builder->get();
        $result = $query->getResult();
        $existing_aor = $result[0]->created_by;
        $this->db->transStart();
        $builder = $this->db->table('efil.case_transfer_based_on_noc_vakalatnama');
        $builder->insert(['registration_id' => $registration_id, 'transfer_from_user_id' => $existing_aor, 'transfer_to_user_id' => $new_aor]);
        $this->db->table('efil.tbl_efiling_nums')
        ->set('created_by', $new_aor)
        ->where('registration_id', $registration_id)
        ->where('created_by', $existing_aor)
        ->update();
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    function get_transferred_cases($advocate_id)
    {
        $builder = $this->db->table('efil.case_transfer_based_on_noc_vakalatnama v');
        $builder->SELECT("efiling_no,efiling_year,v.updated_on, concat(tu.first_name,' ', tu.last_name)name, v.transfer_from_user_id, concat(tcd.sc_diary_num,'/', tcd.sc_diary_year)diary_no, tu.aor_code");
        $builder->join('efil.tbl_efiling_nums ten', 'ten.registration_id = v.registration_id', 'inner');
        $builder->join('efil.tbl_case_details tcd', 'tcd.registration_id=v.registration_id', 'left');
        $builder->join('efil.tbl_users tu', 'tu.id = v.transfer_to_user_id', 'left');
        $builder->where("v.is_deleted = false and v.transfer_from_user_id=$advocate_id");
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

}