<?php

namespace App\Models\NewCase;
use CodeIgniter\Model;

class AdditionalAdv_model extends Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_aors_list() {
        $builder = $this->db->table('icmis.bar bar')
            ->SELECT('bar_id, title, name, aor_code')
            ->WHERE('if_aor', 'Y')
            ->WHERE('isdead', 'N')
            ->orderBy('name', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return 0;
        }
    }

    function get_saved_aors_list($registration_id) {
        $builder = $this->db->table('efil.tbl_case_advocates adv')
            ->SELECT('adv.id, adv.registration_id, bar.title, bar.name, bar.aor_code')
            ->JOIN('icmis.bar bar', 'bar.bar_id = adv.adv_bar_id AND bar.aor_code = adv.adv_code')
            ->WHERE('registration_id', $registration_id)
            ->WHERE('adv.is_deleted', FALSE)
            ->orderBy('adv.id', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return 0;
        }
    }

    function insert_additional_case_advocates($data_to_save) {
        $this->db->transStart();
        $builder = $this->db->table('efil.tbl_case_advocates')->INSERT($data_to_save);
        if ($this->db->insertID()) {
            $status = $this->update_breadcrumbs($data_to_save['registration_id'], NEW_CASE_ADDITIONAL_ADV);
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

    function delete_additional_advocate($id, $registration_id) {
        $data = array(
            'is_deleted' => TRUE,
            'deleted_by' => getSessionData('login')['id'],
            'deleted_on' => date('Y-m-d H:i:s'),
            'deleted_by_ip' => getClientIP()
        );
        $builder = $this->db->table('efil.tbl_case_advocates')->WHERE('id', $id)->WHERE('registration_id', $registration_id)->UPDATE($data);
        if ($this->db->affectedRows() >= 1) {
            return TRUE;
        } else {
            return FALSE;
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