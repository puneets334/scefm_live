<?php

namespace App\Models\GenerateTemplate;

use CodeIgniter\Model;

class GenerateTemplateModel extends Model {

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

    function get_templates() {
        $builder = $this->db->table('efil.tbl_templates');
        $builder->SELECT("id, name,variables");
        $builder->WHERE('is_active', true);
        $builder->orderBy("name", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    function get_template($template_id) {
        $builder = $this->db->table('efil.tbl_static_templates as tst');
        $builder->select("tst.id, tst.template_text, td.name");
        $builder->join('efil.tbl_templates td','tst.template_id=td.id');
        $builder->where('tst.template_id', $template_id);
        $builder->where('tst.is_active', true);
        $query = $builder->get();
        return $query->getRow();
    }

}