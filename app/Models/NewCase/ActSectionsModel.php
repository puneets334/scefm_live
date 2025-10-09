<?php

namespace App\Models\NewCase;

use CodeIgniter\Model;

class ActSectionsModel extends Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_master_acts_list()
    {
        // acts list to show on act-sections page
        $builder = $this->db->table('icmis.act_master');
        $builder->SELECT("id act_id, act_name, year act_year");
        // $builder->FROM();
        /*change done on 11 September 2020*/
        $builder->WHERE('is_approved', 'Y');
        /*end of changes*/
        $builder->WHERE('display', 'Y');
        /*end of changes*/
        $builder->orderBy("id", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    /*changes started on 07 September 2020*/
    //changes done here were reverted on 10 September to accommodate other changes

    function add_case_act_sections($registration_id, $data, $breadcrumb_step)
    {
        $this->db->transStart();
        $this->update_breadcrumbs($registration_id, $breadcrumb_step);
        $builder = $this->db->table('efil.tbl_act_sections');
        $builder->insert($data);
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return $this->db->insertID();
        }
    }

    //new function added to add act in master table on 10 September 2020
    function add_master_acts_list($data)
    {
        $this->db->transStart();
        $builder = $this->db->table('icmis.act_master');
        $builder->INSERT($data);
        $this->db->transComplete();
        if ($this->db->insertID()) {
            $builder = $this->db->table('icmis.act_master');
            $builder->SELECT("max(id) as act_id");
            // $builder->FROM();
            $builder->WHERE($data);
            $builder->groupBy("act_name,act_name_h,year,actno,state_id,display,old_id,old_act_code,is_approved");
            $query = $builder->get();
            // echo $this->db->last_query();
            $row = $query->getRow();
            $act_id = $row->act_id;
            return $act_id;
        }
    }

    /* function add_case_act_sections($registration_id, $data, $breadcrumb_step,$act_id) {
        $this->db->trans_start();
        $this->update_breadcrumbs($registration_id, $breadcrumb_step);
        if($act_id=='others')
        {
            $this->db->INSERT('icmis.act_master_temp', $data);
        } else {
            $this->db->INSERT('efil.tbl_act_sections', $data);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return $this->db->insert_id();
        }
    }*/

    /*changes end*/
    // function get_act_sections_list($registration_id) {
    // $this->db->SELECT("distinct act.id,act.act_id ,act_m.act_name, act_m.year act_year, act.act_section,act_m.is_approved",false);

    // $this->db->FROM('efil.tbl_act_sections act');
    // $this->db->JOIN('icmis.act_master act_m', 'act.act_id = act_m.id');
    // $this->db->WHERE('act.registration_id', $registration_id);
    // $this->db->WHERE('act.is_deleted', FALSE);
    // $this->db->WHERE('act_m.display', 'Y');
    // $this->db->ORDER_BY("act.id", "asc");
    // $query = $this->db->get();
    // return $query->result();
    // }

    function get_act_sections_list($registration_id)
    {
        $db = \Config\Database::connect();
        // $builder = $db->table('efil.tbl_act_sections act');
        // $builder->select('distinct act.id, act.act_id, act_m.act_name, act_m.year as act_year, act.act_section, act_m.is_approved', false);
        // $builder->join('icmis.act_master act_m', 'act.act_id = act_m.id');
        // $builder->where('act.registration_id', $registration_id);
        // $builder->where('act.is_deleted', false);
        // $builder->where('act_m.display', 'Y');
        // $builder->orderBy('act.id', 'asc');
        // $sql = $builder->getCompiledSelect();
$sql = "SELECT distinct act.id, act.act_id, act_m.act_name, act_m.year as act_year, act.act_section, act_m.is_approved
FROM efil.tbl_act_sections act
JOIN icmis.act_master act_m ON act.act_id = act_m.id
WHERE act.registration_id = '".$registration_id."'
AND act.is_deleted = FALSE
AND act_m.display = 'Y'
ORDER BY act.id ASC";
        $query = $this->db->query($sql);
        return $query->getResult();
    }

    function delete_act_sections($registration_id, $act_id)
    {
        $curr_dt_time = date('Y-m-d H:i:s');
        $data = array(
            'is_deleted' => TRUE,
            'deleted_by' => $_SESSION['login']['id'],
            'deleted_on' => $curr_dt_time,
            'deleted_by_ip' => getClientIP()
        );
        $builder = $this->db->table('efil.tbl_act_sections');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('id', $act_id);
        $builder->WHERE('is_deleted', FALSE);
        $builder->UPDATE($data);
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_breadcrumbs($registration_id, $step_no)
    {
        $old_breadcrumbs = $_SESSION['efiling_details']['breadcrumb_status'] . ',' . $step_no;
        $old_breadcrumbs_array = explode(',', $old_breadcrumbs);
        $new_breadcrumbs_array = array_unique($old_breadcrumbs_array);
        sort($new_breadcrumbs_array);
        $new_breadcrumbs = implode(',', $new_breadcrumbs_array);
        $_SESSION['efiling_details']['breadcrumb_status'] = $new_breadcrumbs;
        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->WHERE('registration_id', $registration_id);
        $builder->UPDATE(array('breadcrumb_status' => $new_breadcrumbs));
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_master_acts($master_act_section, $act_id)
    {
        $builder = $this->db->table('icmis.act_master');
        $builder->WHERE('id', $act_id);
        $builder->UPDATE($master_act_section);
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_case_act_sections($act_sections_details, $id_approval)
    {
        $builder = $this->db->table('efil.tbl_act_sections');
        $builder->WHERE('id', $id_approval);
        $builder->UPDATE($act_sections_details);
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}