<?php

namespace App\Models\AdminDashboard;

use CodeIgniter\Model;

class EfileStageModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function getEfileListWithStage($efileno='') {
        $builder = $this->db->table('efil.tbl_efiling_nums ten');
        $builder->SELECT("
            mtds.user_stage_name,
            max(ten.efiling_no) as efiling_no,
            max(ten.efiling_year) as efiling_year,
            max(ten.created_by) as created_by,
            max(ten.create_on) as create_on,
            max(tens.stage_id) as stage_id,
            max(tens.activated_on) as activated_on,
            max(tens.activated_by) as activated_by,
            bool_or(tens.is_active) as is_active,
            max(ten.registration_id) as registration_id,
            max(tu.first_name || ' ' || tu.last_name) as full_name,
            max(tu1.first_name || ' ' || tu1.last_name) as activated_full_name
            "
        );
        $builder->join('efil.tbl_efiling_num_status tens','ten.registration_id=tens.registration_id');
        $builder->join('efil.m_tbl_dashboard_stages mtds','tens.stage_id=mtds.stage_id','left');
        $builder->join('efil.tbl_users tu','ten.created_by=tu.id','left');
        $builder->join('efil.tbl_users tu1','tens.activated_by=tu.id','left');
        if(!empty($efileno)){
            $builder->where('ten.efiling_no', $efileno);
        }
        $builder->groupBy("mtds.user_stage_name");
        $builder->orderBy('activated_on','desc');
        $builder->limit(2);
        $query = $builder->get();
        return $query->getResultObject();
    }

    public function updateStageData($data,$obj_cases,$efile_obj_cases) {
        $tbl_efiling_num_status_update_data = [
            'deactivated_on' => $data->datetime, 
            'is_active'      => false, 
            'updated_by_ip'  => $data->remote_addr
        ];
        $tbl_efiling_num_status_data = [
            'registration_id'   => $data->registration_id,
            'stage_id'          => $data->stage_id,
            'activated_on'      => $data->datetime,
            'deactivated_on'    => NULL,
            'is_active'         => true,
            'activated_by'      => NULL,
            'updated_by'        => NULL,
            'activated_by_ip'   => $data->remote_addr,
            'updated_by_ip'     => NULL,
            'is_deleted'        => false,
            'deleted_by'        => NULL,
            'deleted_on'        => NULL,
            'deleted_by_ip'     => NULL,
            'remark'            => NULL,
        ];
        $data_stage_logs = [
            'status_id'         => $data->status_id,
            'registration_id'   => $data->registration_id,
            'stage_id'          => $data->stage_id,
            'activated_on'      => $data->activated_on,
            'deactivated_on'    => $data->deactivated_on,
            'is_active'         => $data->is_active,
            'activated_by'      => $data->activated_by,
            'updated_by'        => $data->updated_by,
            'activated_by_ip'   => $data->activated_by_ip,
            'updated_by_ip'     => $data->updated_by_ip,
            'is_deleted'        => $data->is_deleted,
            'deleted_by'        => $data->deleted_by,
            'deleted_on'        => $data->deleted_on,
            'deleted_by_ip'     => $data->deleted_by_ip,
            'remarks'           => $data->remarks,
            'stage_change_at'   => $data->datetime,
            'stage_change_by'   => $data->loginid,
            'stage_change_by_ip'=> $data->remote_addr
        ];
        $this->db->transStart();
        $builder = $this->db->table('efil.tbl_efiling_num_status_change_stage_logs');
        $builder->insert($data_stage_logs);
        $builder1 = $this->db->table('efil.tbl_efiling_num_status');
        $builder->where('registration_id', $data->registration_id);
        $builder->where('is_active', true);
        $builder->update($tbl_efiling_num_status_update_data);
        $builder->insert($tbl_efiling_num_status_data);
        foreach($efile_obj_cases as $key => $result) {
            $builder2 = $this->db->table('efil.tbl_icmis_objections');
            $builder->set('obj_removed_date',$obj_cases[$key]->rm_dt);
            $builder->set('updated_by', $_SESSION['login']['id']);
            $builder->set('update_ip',getClientIP());
            $builder->set('updated_on', date('Y-m-d H:i:s'));
            $builder->where('id',$result->id);
            $builder->update();
        }
        $this->db->transComplete();
        if($this->db->transStatus() === FALSE) {
            return false;
        } else{
            return true;
        }
    }

    public function getStageList() {
        $builder = $this->db->table('efil.m_tbl_dashboard_stages mtds');
        $builder->select("mtds.stage_id,mtds.user_stage_name");
        $builder->whereIn('mtds.stage_id', [12,1]);
        $builder->where('mtds.is_active', true);
        $builder->orderBy('mtds.user_stage_name');
        $query = $builder->get();
        return $query->getResultObject();
    }

    public function getEfileStageData($efiling_no) {
        $builder = $this->db->table('efil.tbl_efiling_nums ten');
        $builder->SELECT("
            mtds.user_stage_name,
            max(tens.status_id) as status_id,
            max(ten.registration_id) as registration_id,
            max(tens.stage_id) as stage_id,
            max(tens.activated_on) as activated_on,
            max(ten.efiling_no) as efiling_no,
            max(tens.deactivated_on) as deactivated_on,
            bool_or(tens.is_active) as is_active,
            max(tens.activated_by) as activated_by,
            max(tens.updated_by) as updated_by,
            max(tens.activated_by_ip) as activated_by_ip,
            max(tens.updated_by_ip) as updated_by_ip,
            bool_or(tens.is_deleted) as is_deleted,
            max(tens.deleted_by) as deleted_by,
            max(tens.deleted_on) as deleted_on,
            max(tens.deleted_by_ip) as deleted_by_ip,
            "
        );
        $builder->join('efil.tbl_efiling_num_status tens','ten.registration_id=tens.registration_id');
        $builder->join('efil.m_tbl_dashboard_stages mtds','tens.stage_id=mtds.stage_id','left');
        $builder->where('ten.efiling_no', $efiling_no);
        $builder->groupBy("mtds.user_stage_name");
        $builder->orderBy('activated_on','desc');
        $builder->limit(1);
        $query = $builder->get();
        return $query->getRow();
    }

    public function getObjections($registration_id) {
        $builder = $this->db->table('efil.tbl_icmis_objections');
        $builder->select("id");
        $builder->where('registration_id', $registration_id);
        $query      = $builder->get();
        $obj_result = $query->getResultObject();
        if(empty($obj_result)) {
            return false;
        } else{
            return $obj_result;
        }
    }

}