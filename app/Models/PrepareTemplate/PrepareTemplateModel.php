<?php

namespace App\Models\PrepareTemplate;

use CodeIgniter\Model;

class PrepareTemplateModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function generateTemplateRecords($efileno) {
        $builder = $this->db->table('efil.tbl_efiling_nums ten');
        $builder->SELECT("
            ten.efiling_no,
            ten.efiling_year,
            ten.registration_id,
            tcp.party_name,
            tcp.p_r_type
            "
        );
        $builder->join('efil.tbl_case_parties tcp','ten.registration_id=tcp.registration_id');
        $builder->where('ten.efiling_no', $efileno);
        $builder->where('tcp.m_a_type','M');
        $query = $builder->get();
        return $query->getResultObject();
    }

    function get_templates() {
        $builder = $this->db->table('efil.tbl_templates');
        $builder->SELECT("id, name,variables");
        $builder->WHERE('is_active', true);
        $builder->orderBy("name", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    function get_template_by_id($template_id) {
        $builder = $this->db->table('efil.tbl_templates');
        $builder->select("id, name,variables");
        $builder->where('is_active', true);
        $builder->where('id',$template_id);
        $builder->orderBy("name", "asc");
        $query = $builder->get();
        return $query->getRow();
    }

    function save_template($data) {
        $builder = $this->db->table('efil.tbl_prepared_templates');
        $builder->where('template_id',$data['template_id']);
        $q = $builder->get();
        if($q->getNumRows()>0) {
            $builder->where('template_id',$data['template_id']);
            $builder->set($data);
            $builder->update();
        } else{
            $builder->insert($data);
        }
        if ($this->db->affectedRows()>0) {
            return true;
        } else{
            return false;
        } 
    }

    function get_template($template_id) {
        $builder = $this->db->table('efil.tbl_prepared_templates');
        $builder->SELECT("id, template_text");
        $builder->WHERE('template_id', $template_id);
        $builder->WHERE('is_active', true);
        $query = $builder->get();
        return $query->getRow();
    }

    public function preparedTemplateRecords($efileno) {
        $builder = $this->db->table('efil.tbl_efiling_nums ten');
        $builder->SELECT("
            ten.efiling_no,
            ten.efiling_year,
            ten.registration_id,
            tcp.party_name,
            tcp.p_r_type,
            tcd.cause_title
            "
        );
        $builder->join('efil.tbl_case_parties tcp','ten.registration_id=tcp.registration_id');
        $builder->join('efil.tbl_case_details tcd','ten.registration_id=tcd.registration_id');
        $builder->where('ten.efiling_no', $efileno);
        $builder->where('tcp.m_a_type','M');
        $query = $builder->get();
        return $query->getResultObject();
    }

}