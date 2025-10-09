<?php
namespace App\Models\OldCaseRefiling;

use CodeIgniter\Model;
class Esign_docs_model extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    function table_data($table, $condition='1=1')
    {
        $builder = $this->db->table($table);
        $builder->where($condition)->orderBy(1);
        $query= $builder->get();
        return $query->getResultArray();
    }

    function update_data($table, $data, $condition)
    {
        $builder = $this->db->table($table);
        $builder->where($condition);
        $builder->set($data);
        return $builder->update();
    }

    function insert_data($table, $data)
    {
        $builder = $this->db->table($table);
        return $builder->insert($data);
    }

}
