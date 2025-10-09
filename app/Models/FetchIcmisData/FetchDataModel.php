<?php

namespace App\Models\FetchIcmisData;
use CodeIgniter\Model;

class FetchDataModel extends Model {

    function __construct() {
        parent::__construct();
        $db = \Config\Database::connect();
    }

    function insertRecord($data, $tableName) {
        $builder = $this->db->table($tableName);
        $builder->insert($data);
    }

    function get_existing_citations($caseid) {
        $builder = $this->db->table('efil.tbl_citation tc');
        $builder->select('tc.*');
        $builder->join('efil.tbl_sci_cases tsc', 'tc.tbl_sci_cases_id=tsc.id');
        $builder->where('tsc.id', $caseid);
        $builder->where('tc.is_deleted', false);
        $builder->orderBy("listing_date", "desc");
        $query = $builder->get();
        return $query->getResult();
    }

    function updateRecord($data, $tableName, $id) {
        $builder = $this->db->table($tableName);
        $builder->where('id', $id);
        $builder->update($data);
    }

    function updateAdvocatePartyDetails($details) {
        $userid = $this->getUserIdByAdvId($details->advocate_id);
        $this->saveAdvocatePartyDetails($userid, $details);
        $this->saveAppearingForDetails($userid, $details);
    }

    function getMaxTimestamp() {
        $builder = $this->db->table('efil.tbl_case_contacts');
        $builder->select('max(updated_at) as updated_at');
        $query = $builder->get();
        $result = $query->getRow();
        return $result->updated_at;
    }

    function saveAdvocatePartyDetails($userId, $details) {
        $builder = $this->db->table('efil.tbl_case_contacts');
        $dataToInsert = array(
            'userid' => $userId, 'diary_no' => $details->diary_no,
            'p_name' => $details->names, 'contact_type' => $details->pet_res,
            'created_by' => 1, 'created_on' => date('Y-m-d H:i:s'), 'partyid' => $details->pet_res_no,
            'is_deleted' => false, 'updated_at' => $details->updated_at
        );
        $dataToUpdate = array('p_name' => $details->names, 'partyid' => $details->pet_res_no, 'updated_by' => 1, 'updated_on' => date('Y-m-d H:i:s'), 'updated_at' => $details->updated_at);
        $builder->where('userid', $userId);
        $builder->where('diary_no', $details->diary_no);
        $builder->where('contact_type', $details->pet_res);
        $q = $builder->get();
        if ($q->getNumRows() > 0) {
            $builder = $this->db->table('efil.tbl_case_contacts');
            $builder->where('userid', $userId);
            $builder->where('diary_no', $details->diary_no);
            $builder->where('contact_type', $details->pet_res)->groupStart()
                ->where('p_name!=', $details->names)
                ->orWhere('partyid!=', $details->pet_res_no)
                ->groupEnd();
            $builder->update($dataToUpdate);
            echo "<br/>Rows Affected for Diary " . $details->diary_no . " is:" . $this->db->affectedRows();
        } else {
            $builder->insert($dataToInsert);
            echo "<br/>Diary No: " . $details->diary_no . " inserted.";
        }
    }

    function saveAppearingForDetails($userId, $details) {
        $dataToInsert = array(
            'diary_num' => $details->diary_number, 'diary_year' => $details->diary_year, 'userid' => $userId,
            'partytype' => $details->pet_res, 'appearing_for' => $details->pet_res_no,
            'created_by' => 1, 'created_on' => date('Y-m-d H:i:s'), 'is_deleted' => false
        );
        $dataToUpdate = array('appearing_for' => $details->pet_res_no, 'updated_by' => 1, 'updated_on' => date('Y-m-d H:i:s'));
        $builder = $this->db->table('efil.tbl_appearing_for');
        $builder->where('userid', $userId);
        $builder->where('diary_num', $details->diary_number);
        $builder->where('diary_year', $details->diary_year);
        $builder->where('partytype', $details->pet_res);
        $q = $builder->get();
        if ($q->getNumRows() > 0) {
            $builder = $this->db->table('efil.tbl_appearing_for');
            $builder->where('userid', $userId);
            $builder->where('diary_num', $details->diary_number);
            $builder->where('diary_year', $details->diary_year);
            $builder->where('appearing_for!=', $details->pet_res_no);
            $builder->update($dataToUpdate);
            echo "<br/>Rows Affected for Diary " . $details->diary_no . " is:" . $this->db->affectedRows();
        } else {
            $builder->insert($dataToInsert);
            echo "<br/>Diary No: " . $details->diary_no . " inserted.";
        }
    }

    function getUserIdByAdvId($advocateId) {
        $builder = $this->db->table('efil.tbl_users');
        $builder->select('id');
        $builder->where('adv_sci_bar_id', $advocateId);
        $builder->where('is_deleted', false);
        $builder->limit(1);
        $query = $builder->get();
        $result = $query->getRow();
        return $result->id;
    }

}