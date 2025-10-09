<?php

namespace App\Models\SuperAdmin;

use CodeIgniter\Model;

class SuperAdminModel extends Model
{

    protected $db;

    function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
    }

    public function insertData($tableName,$data)
    {
        $output = false;
        if(isset($tableName) && !empty($tableName) && isset($data) && !empty($data)){
            $builder = $this->db->table($tableName);
            $builder->insert($data);
            $output = $this->db->insertID();
        }
        return $output;
    }

    public function insertBatchData($tableName,$data)
    {
        $output = false;
        if(isset($tableName) && !empty($tableName) && isset($data) && !empty($data)){
            $builder = $this->db->table($tableName);
            $builder->insertBatch($data);
            $output = $this->db->insertID();
        }
        return $output;
    }
    
    public function getData($params = array())
    {
        $output = false;
        if(isset($params['table_name']) && !empty($params['table_name']) && !empty($params['whereFieldName']) && isset($params['whereFieldName']) && isset($params['whereFieldValue']) && !empty($params['whereFieldValue'])) {
            $builder = $this->db->table($params['table_name']);
            $builder->WHERE($params['whereFieldName'], $params['whereFieldValue']);
            if(isset($params['is_active']) && !empty($params['is_active'])) {
                $builder->where('is_active', $params['is_active']);
            }
            $query = $builder->get();
            $output = $query->getResult();
        }
        return $output;
    }

    public function updateTableData($params = array())
    {
        $output =false;
        if(isset($params['table_name']) && !empty($params['table_name']) && !empty($params['whereFieldName']) && isset($params['whereFieldName']) && isset($params['whereFieldValue']) && !empty($params['whereFieldValue']) && isset($params['updateArr']) && !empty($params['updateArr'])) {
            $this->db->WHERE($params['whereFieldName'],$params['whereFieldValue']);
            $this->db->SET($params['table_name'], $params['updateArr']);
            $query = $this->db->UPDATE();
            if($query == true) {
                $output = true;
            }
        }
        return $output;
    }

    public function getAllRecordFromTable($params = array())
    {
        $output =false;
        if(isset($params['table_name']) && !empty($params['table_name'])) {
            $builder = $this->db->table($params['table_name']);
            if(isset($params['is_active']) && !empty($params['is_active'])) {
                $builder->where('is_active',$params['is_active']);
            }
            if(isset($params['id']) && !empty($params['id'])) {
                $builder->whereIn('id',$params['id']);
            }
            $query = $builder->get();
            $output = $query->getResult();
        }
        return $output;
    }

    public function getAssignedUser($params =array())
    {
        $output =false;
        if(isset($params['user_type']) && !empty($params['user_type']) && isset($params['not_in_user_id']) && !empty($params['not_in_user_id'])) {
            $builder = $this->db->table('efil.tbl_users tu');
            $builder->select('
                tu.first_name, 
                tu.emp_id, 
                tu.attend, 
                tu.moblie_number, 
                tu.emailid, 
                tu.pp_a, 
                trim(array_agg(tfaaf.file_type_id)::text, \'{}\') as file_type_id, 
                tfaaf.user_id, 
                trim(array_agg(mtet.efiling_type)::text, \'{}\') as efiling_type
            ');
            $builder->join('efil.tbl_filing_admin_assigned_file tfaaf', 'tu.id = tfaaf.user_id', 'left');
            $builder->join('efil.m_tbl_efiling_type mtet', 'tfaaf.file_type_id = mtet.id', 'left');
            $builder->where('tu.is_active', '1');
            $builder->where('tfaaf.is_active', '1');
            $builder->where('tu.ref_m_usertype_id', $params['user_type']);
            $builder->whereNotIn('tu.id', $params['not_in_user_id']);
            $builder->groupBy('tu.first_name, tu.emp_id, tu.attend, tu.moblie_number, tu.emailid, tu.pp_a, tfaaf.user_id');
            $builder->orderBy('tfaaf.user_id', 'DESC');
            $query = $builder->get();
            $output = $query->getResult();
        }
        return $output;
    }

}