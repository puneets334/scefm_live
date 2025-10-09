<?php

namespace App\Models\Affirmation;
use CodeIgniter\Model;

class AffirmationModel extends Model {

    protected $session;

    function __construct() {
        parent::__construct();
        $db      = \Config\Database::connect();  
        $this->session = \Config\Services::session();  

    }

    function view_signed_certificate($doc_id) {
        $builder = $this->db->table('efil.esign_logs el');
        $builder->SELECT('el.*');
        $builder->WHERE('el.id', $doc_id);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResult();
            return $result;
        } else {
            return false;
        }
    }

    function get_doc_signature_status($reg_id) {
        $sql = " SELECT * from efil.esign_logs WHERE ( ref_registration_id = '" . $reg_id . "' AND is_data_valid = 'TRUE' AND ( type = 1 or type = 2 ) )";
        $query = $this->db->query($sql);          
        if (getSessionData('login')['ref_m_usertype_id'] == USER_ADVOCATE) {
            $sql2 = " SELECT * from efil.esign_logs WHERE ( ref_registration_id = '" . $reg_id . "' AND is_data_valid = 'TRUE' AND ( type = 3 ) )";
            $query2 = $this->db->query($sql2);
        }
        if (getSessionData('login')['ref_m_usertype_id'] == USER_ADVOCATE) {
            if (($query->getNumRows() == 1) && ($query2->getNumRows() == 1)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else if (getSessionData('login')['ref_m_usertype_id'] == USER_IN_PERSON) {
            if (($query->getNumRows() == 1)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function get_esigned_file_size($txn_id, $reg_id) {
        $sql = "SELECT * FROM efil.esign_logs WHERE ref_registration_id = '" . $reg_id . "' AND request_txn_num ='" . $txn_id . "'";
        $query = $this->db->query($sql);
        if (($query->getNumRows())) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_esign_doc_details($reg_id) {
        $sql = " SELECT * from efil.esign_logs WHERE ref_registration_id = '" . $reg_id . "' AND is_data_valid = 'TRUE' AND   type = '" . ESIGNED_DOCS_BY_ADV . "' order by id desc ";
        $query = $this->db->query($sql);
        if (($query->getNumRows())) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function upload_pet_adv_esign_docs($data, $registration_id, $breadcrumb_to_update) {
        $this->db->transStart();
        $builder = $this->db->table('efil.esign_logs');
        $builder->insert($data);
        if ($this->db->insertID()) {
            $status = $this->update_breadcrumbs($registration_id, $breadcrumb_to_update);
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

    function get_everified_doc($reg_id) {
        $sql = " SELECT * from efil.esign_logs WHERE ( ref_registration_id = '" . $reg_id . "' AND is_data_valid = 'TRUE' AND (  type = " . EVERIFIED_DOCS_BY_MOB_OTP . ") )";
        $query = $this->db->query($sql);
        if (($query->getNumRows())) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_esign_failure_count($reg_id) {
        $sql = "SELECT count(errcode) errcode_count FROM esign_logs WHERE ref_registration_id = '" . $reg_id . "' AND is_data_valid = 'FALSE' AND errcode !='NA' AND (  type = " . ESIGNED_DOCS_BY_PET . " OR type = " . ESIGNED_DOCS_BY_ADV . ") GROUP BY errcode";
        $query = $this->db->query($sql);
        if (($query->getNumRows())) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function reset_affirmation($docid, $reg_id, $update_data, $breadcrumb_to_remove) {
        $this->db->transStart();
        $builder = $this->db->table('efil.esign_logs');
        $builder->WHERE('id', $docid);
        $builder->WHERE('ref_registration_id', $reg_id);        
        $builder->UPDATE($update_data);        
        $this->remove_breadcrumb($reg_id, $breadcrumb_to_remove);
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
        $builder=$this->db->table('efil.tbl_efiling_nums');
        $builder->WHERE('registration_id', $registration_id);
        $builder->UPDATE( array('breadcrumb_status' => $new_breadcrumbs));
        if ($this->db->affectedRows() > 0) {
            getSessionData('efiling_details')['breadcrumb_status'] = $new_breadcrumbs;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function remove_breadcrumb($registration_id, $breadcrumb_to_remove) {
        $breadcrumbs_array = explode(',', getSessionData('efiling_details')['breadcrumb_status']);
        if (in_array($breadcrumb_to_remove, $breadcrumbs_array)) {
            $index = array_search($breadcrumb_to_remove, $breadcrumbs_array);
            if ($index !== false) {
                unset($breadcrumbs_array[$index]);
            }
            $new_breadcrumbs = implode(',', $breadcrumbs_array);
            $builder = $this->db->table('efil.tbl_efiling_nums');
            $builder->WHERE('registration_id', $registration_id);
            $builder->UPDATE(array('breadcrumb_status' => $new_breadcrumbs));
            if ($this->db->affectedRows() > 0) {
                getSessionData('efiling_details')['breadcrumb_status'] = $new_breadcrumbs;
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

}