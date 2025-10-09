<?php

namespace App\Models\NewCase;
use CodeIgniter\Model;

class AdditionalCaseDetails_model extends Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_keywords_list() {
        $builder = $this->db->table('icmis.ref_keyword')
            ->SELECT('id, keyword_description')
            ->WHERE('is_deleted', 'f')
            ->orderBy('keyword_description', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return FALSE;
        }
    }

    function get_saved_keywords_list($registration_id) {
        $builder = $this->db->table('icmis.ref_keyword')
            ->SELECT('id, keyword_description')
            ->whereIn('cast(id as varchar)', "SELECT unnest(string_to_array(keywords, ',')) FROM efil.tbl_case_details WHERE registration_id = $registration_id  ", FALSE)
            ->orderBy('keyword_description', 'ASC');
        $query = $builder->get();  //echo $this->db->last_query(); 
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return FALSE;
        }
    }

    function get_saved_additional_info($registration_id) {
        $builder = $this->db->table('efil.tbl_case_details')
            ->SELECT('registration_id, keywords, question_of_law, grounds, grounds_interim, main_prayer, interim_relief')
            ->WHERE('registration_id', $registration_id)
            ->WHERE('is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return 0;
        }
    }

    function update_additional_CaseDetails($case_details, $registration_id) {
        $this->db->transStart();
        $builder = $this->db->table('efil.tbl_case_details')->WHERE('registration_id', $registration_id)->UPDATE($case_details);
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

    function update_CaseDetails_keywords($keywords, $registration_id) {
        $this->db->transStart();
        $builder = $this->db->table('efil.tbl_case_details')->WHERE('registration_id', $registration_id)->UPDATE($keywords);
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

    function update_breadcrumbs($registration_id, $step_no) {
        $old_breadcrumbs = getSessionData('efiling_details')['breadcrumb_status'] . ',' . $step_no;
        $old_breadcrumbs_array = explode(',', $old_breadcrumbs);
        $new_breadcrumbs_array = array_unique($old_breadcrumbs_array);
        sort($new_breadcrumbs_array);
        $new_breadcrumbs = implode(',', $new_breadcrumbs_array);
        setSessionData('efiling_details'['breadcrumb_status'], $new_breadcrumbs);
        $builder = $this->db->table('efil.tbl_efiling_nums')->WHERE('registration_id', $registration_id)->UPDATE(array('breadcrumb_status' => $new_breadcrumbs));
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}