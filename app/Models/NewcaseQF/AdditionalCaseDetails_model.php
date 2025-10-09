<?php

namespace App\Models\NewCaseQF;

use CodeIgniter\Model;

class AdditionalCaseDetails_model extends Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $db = \Config\Database::connect();
    }

    public function get_keywords_list()
    {
        $builder = $this->db->table('icmis.ref_keyword');
        $builder->select('id, keyword_description');
        $builder->where('is_deleted', 'f');
        $builder->orderBy('keyword_description', 'ASC');

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    public function get_saved_keywords_list($registration_id)
    {
        $subQuery = "SELECT unnest(string_to_array(keywords, ',')) FROM efil.tbl_case_details WHERE registration_id = ?";

        $builder = $this->db->table('icmis.ref_keyword');
        $builder->select('id, keyword_description');
        $builder->whereIn("cast(id as varchar)", $subQuery, false, [$registration_id]);
        $builder->orderBy('keyword_description', 'ASC');

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }


    public function get_saved_additional_info($registration_id)
    {
        $builder = $this->db->table('efil.tbl_case_details');
        $builder->select('registration_id, keywords, question_of_law, grounds, grounds_interim, main_prayer, interim_relief');
        $builder->where('registration_id', $registration_id);
        $builder->where('is_deleted', FALSE);

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    public function update_additional_CaseDetails($case_details, $registration_id)
    {
        $this->db->transStart();

        $builder = $this->db->table('efil.tbl_case_details');
        $builder->where('registration_id', $registration_id);
        $builder->update($case_details);

        if ($this->db->affectedRows() == 1) {
            $status = $this->update_breadcrumbs($registration_id, NEW_CASE_ADDITIONAL_INFO);

            if ($status) {
                $thia->db->transComplete();
            }
        }

        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }


    public function update_CaseDetails_keywords($keywords, $registration_id)
    {
        $this->db->transStart();

        $builder = $this->db->table('efil.tbl_case_details');
        $builder->where('registration_id', $registration_id);
        $builder->update($keywords);

        if ($this->db->affectedRows() == 1) {
            $status = $this->update_breadcrumbs($registration_id, NEW_CASE_ADDITIONAL_INFO);

            if ($status) {
                $this->db->transComplete();
            }
        }

        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }


    public function update_breadcrumbs($registration_id, $step_no)
    {
        // Update session variable
        $old_breadcrumbs = $_SESSION['efiling_details']['breadcrumb_status'] . ',' . $step_no;
        $old_breadcrumbs_array = explode(',', $old_breadcrumbs);
        $new_breadcrumbs_array = array_unique($old_breadcrumbs_array);
        sort($new_breadcrumbs_array);
        $new_breadcrumbs = implode(',', $new_breadcrumbs_array);
        $_SESSION['efiling_details']['breadcrumb_status'] = $new_breadcrumbs;

        // Update database table
        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->where('registration_id', $registration_id);
        $builder->update(['breadcrumb_status' => $new_breadcrumbs]);

        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
