<?php

namespace App\Models\AppearingFor;

use CodeIgniter\Model;

class AppearingForModel extends Model {

    function __construct() {
        parent::__construct();
        $db = \Config\Database::connect();
    }

    function get_case_parties_list($registration_id) {
        $this->db->transStart();
        $builder = $this->db->table('efil.tbl_efiling_nums en');
        $builder->SELECT("en.*,sc.*, misc_ia.p_r_type, misc_ia.intervenor_name, misc_ia.filing_for_parties");
        $builder->JOIN('efil.tbl_misc_docs_ia misc_ia', 'en.registration_id = misc_ia.registration_id');
        $builder->JOIN('efil.tbl_sci_cases sc', 'misc_ia.diary_no = sc.diary_no AND misc_ia.diary_year = sc.diary_year');
        $builder->WHERE('en.registration_id', $registration_id);
        $builder->WHERE('en.is_deleted', FALSE);
        $builder->WHERE('misc_ia.is_deleted', FALSE);
        $builder->WHERE('sc.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_appearing_for_details($registration_id, $advocate_id) {
        $builder = $this->db->table('efil.tbl_appearing_for appearing');
        $builder->SELECT("appearing.*, cc.id contact_tbl_id,cc.p_name,cc.p_email,cc.p_mobile,cc.contact_type, cc.partyid");
        $builder->JOIN('efil.tbl_misc_docs_ia misc_ia', 'misc_ia.diary_no = appearing.diary_num AND misc_ia.diary_year = appearing.diary_year');
        $builder->JOIN('efil.tbl_case_contacts cc', 'concat(misc_ia.diary_no,misc_ia.diary_year) = cc.diary_no');
        $builder->WHERE('misc_ia.registration_id', $registration_id);
        $builder->WHERE('appearing.userid', $advocate_id);
        $builder->WHERE('appearing.is_deleted', FALSE);
        $builder->WHERE('misc_ia.is_deleted', FALSE);
        $builder->WHERE('cc.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function add_appearing_for($appearing_party_detail, $contact_details, $registration_id) {
        $builder = $this->db->table('efil.tbl_appearing_for');
        $builder->INSERT($appearing_party_detail);
        if ($this->db->insertID()) {
            if (!empty($contact_details)) {
                $builder = $this->db->table('efil.tbl_case_contacts');
                $builder->INSERT($contact_details);
            }
            $this->update_breadcrumbs($registration_id, MISC_BREAD_APPEARING_FOR);
            $this->db->transComplete();
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function update_appearing_for($update_appearing_party_detail, $appearing_for_tbl_id, $update_contact_details, $contact_tbl_id, $registration_id = "") {
        $this->db->transStart();
        $builder = $this->db->table('efil.tbl_appearing_for');
        $builder->WHERE('userid', getSessionData('login')['id']);
        $builder->WHERE('id', $appearing_for_tbl_id);
        $builder->UPDATE($update_appearing_party_detail);
        if (!empty($update_contact_details)) {
            $builder = $this->db->table('efil.tbl_case_contacts');
            $builder->WHERE('userid', getSessionData('login')['id']);
            $builder->WHERE('id', $contact_tbl_id);
            $builder->UPDATE($update_contact_details);
        }
        if (!empty($registration_id)) {
            $this->update_breadcrumbs($registration_id, MISC_BREAD_APPEARING_FOR);
        }
        $this->db->transComplete();
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
        // getSessionData('efiling_details')['breadcrumb_status'] = $new_breadcrumbs;
        $setD = getSessionData('efiling_details');
        $setD['breadcrumb_status'] = $new_breadcrumbs;
        setSessionData('efiling_details', $setD);
        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->WHERE('registration_id', $registration_id);
        $builder->UPDATE(array('breadcrumb_status' => $new_breadcrumbs));
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}