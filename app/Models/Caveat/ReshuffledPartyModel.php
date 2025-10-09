<?php

namespace App\Models\Caveat;
use CodeIgniter\Model;

class ReshuffledPartyModel extends Model {

    function __construct() {
        parent::__construct();
    }

    function get_extra_parties_without_lrs($registration_id, $shuffle_party_type) {
        $builder = $this->db->table('tbl_efiling_civil_extra_party ex');
        $builder->SELECT('id,type, party_no, name, parentid, party_id, legal_heir');
        $builder->WHERE('ex.ref_m_efiling_nums_registration_id', $registration_id);
        $builder->WHERE('ex.parentid IS NULL');
        $builder->WHERE('ex.type', $shuffle_party_type);
        $builder->orderBy('type');
        $builder->orderBy('cast(party_id as float)');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_extra_parties_with_lrs($registration_id, $shuffle_party_type) {
        $builder = $this->db->table('tbl_efiling_civil_extra_party ex');
        $builder->SELECT('id,type, party_no, name, parentid, party_id, legal_heir');
        $builder->WHERE('ex.ref_m_efiling_nums_registration_id', $registration_id);
        $builder->WHERE('ex.type', $shuffle_party_type);
        $builder->orderBy('type');
        $builder->orderBy('cast(party_id as float)');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = $query->getResult();
            return $result;
        } else {
            return FALSE;
        }
    }

    function update_extra_party_position($data, $parent_data) {
        $this->db->transStart();
        $builder = $this->db->table('tbl_efiling_civil_extra_party');
        $builder->updateBatch($data, 'id');
        if ($this->db->affectedRows() > 0) {
            if (!empty($parent_data)) {
                $builder->updateBatch($parent_data, 'id');
            }
            $this->db->transComplete();
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

}