<?php

namespace App\Models\NewCase;
use CodeIgniter\Model;

class DeleteSubordinateCourtModel extends Model {

    function delete_case_subordinate_court($registration_id, $id,$firData=null,$certified_copyData=null) {
        $curr_dt_time = date('Y-m-d H:i:s');
        $data = array(
            'is_deleted' => TRUE,
            'deleted_by' => $_SESSION['login']['id'],
            'deleted_on' => $curr_dt_time,
            'deleted_by_ip' => getClientIP()
        );
        $builder = $this->db->table('efil.tbl_lower_court_details')->WHERE('registration_id', $registration_id)->WHERE('id', $id)->WHERE('is_deleted', FALSE)->UPDATE($data);
        if ($this->db->affectedRows() > 0) {
            if(isset($certified_copyData) && !empty($certified_copyData)){
                $data = array(
                    'is_deleted' => TRUE,
                    'deleted_by' => $_SESSION['login']['id'],
                    'deleted_on' => $curr_dt_time,
                    'deleted_by_ip' => getClientIP(),
                    'updated_by' => $_SESSION['login']['id'],
                    'updated_on' => $curr_dt_time,
                    'updated_by_ip' => getClientIP()
                );
                // $this->db->WHERE('registration_id', $registration_id);
                // $this->db->WHERE('ref_tbl_lower_court_details_id', $id);
                // $this->db->WHERE('is_deleted', FALSE);
                // $this->db->UPDATE('efil.tbl_certified_copy_details', $data);
                $this->db->table('efil.tbl_certified_copy_details')
                 ->where('registration_id', $registration_id)
                 ->where('ref_tbl_lower_court_details_id', $id)
                 ->where('is_deleted', false)
                 ->update($data);
            }
            if(isset($firData) && !empty($firData)){
                $data = array('is_deleted' => TRUE,
                    'updated_by' => $_SESSION['login']['id'],
                    'updated_on' => $curr_dt_time,
                    'updated_by_ip' => getClientIP(),
                    'status'=>false
                );
                $builder1 = $this->db->table('efil.tbl_fir_details')->WHERE('registration_id', $registration_id)->WHERE('ref_tbl_lower_court_details_id', $id)->WHERE('is_deleted', FALSE)->UPDATE($data);
                if ($this->db->affectedRows() > 0) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

}