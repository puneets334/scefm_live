<?php

namespace App\Models\MyCases;

use CodeIgniter\Model;

class CitationNotesModel extends Model
{

    function __construct()
    {
        parent::__construct();
        $db      = \Config\Database::connect();
    }

    public function add_citation_n_notes($description)
    {
        $insertData = [
            'description' => $description
        ];

        $this->db->table('efil.tbl_adv_citation_and_notes')->insert($insertData);

        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function add_case_contact_data($data)
    {
        $this->db->table('efil.tbl_case_contacts')->insert($data);

        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function update_case_contact_data($data, $contact_id)
    {
        $this->db->table('efil.tbl_case_contacts')->where('id', $contact_id)->update($data);

        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_citation_n_notes($type, $date, $diary_no)
    {
        $updation_data = ['is_active' => false];

        $this->db->table('efil.tbl_adv_citation_and_notes')
            ->where('desc_type', $type)
            ->where('created_date', $date)
            ->where('diary_no', $diary_no)
            ->where('advocate_id', $_SESSION['login']['id'])
            ->update($updation_data);

        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function update_citation_n_notes($id, $updation_data)
    {
        $this->db->table('efil.tbl_adv_citation_and_notes')
            ->where('id', $id)
            ->update($updation_data);

        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_case_contact_data($id, $diary_no)
    {
        $query = $this->db->table('efil.tbl_case_contacts')
            ->select('*')
            ->where('diary_no', $diary_no)
            ->where('userid', $id)
            ->where('is_deleted', false)
            ->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    // public function delete_contacts($id)
    // {
    //     $updation_data = [
    //         'deleted_by' => $_SESSION['login']['id'],
    //         'deleted_on' => date('Y-m-d H:i:s'),
    //         'delete_ip' => getClientIP(),
    //         'is_deleted' => true
    //     ];

    //     $this->db->table('efil.tbl_case_contacts')
    //         ->where('id', $id)
    //         ->update($updation_data);

    //     return $this->db->affectedRows() > 0;
    // }

    public function delete_contacts($id)
    {
        $session = session();
        $updation_data = [
            'deleted_by' => $session->get('login.id'),
            'deleted_on' => date('Y-m-d H:i:s'),
            'delete_ip' => getClientIP(),
            'is_deleted' => true
        ];

        $this->db->table('efil.tbl_case_contacts')
            ->where('id', $id)
            ->update($updation_data);

        return $this->db->affectedRows() > 0;
    }


    public function get_cnr_case_contact_data($id)
    {
        $query = $this->db->table('efil.tbl_case_contacts')
            ->select('*')
            ->where('id', $id)
            ->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_citation_and_notes_list($type, $diary_no)
    {
        $query = $this->db->table('efil.tbl_adv_citation_and_notes')
            ->select('id, description, created_date, diary_no')
            ->where('advocate_id', $_SESSION['login']['id'])
            ->where('diary_no', $diary_no)
            ->where('desc_type', $type)
            ->where('is_active', true)
            ->orderBy('id', 'DESC')
            ->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_aor_contact()
    {
        $query = $this->db->table('icmis.bar')
            ->select('name, email, mobile')
            ->where("email !=", '')
            ->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
}
