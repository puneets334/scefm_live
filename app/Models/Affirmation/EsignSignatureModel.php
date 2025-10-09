<?php

namespace App\Models\Affirmation;
use CodeIgniter\Model;

class EsignSignatureModel extends Model {

    function __construct() {
        parent::__construct();
        $db = \Config\Database::connect();
    }

    function insert_esign_request($data, $time_stamp, $txn) {
        $builder= $this->db->table('efil.esign_logs');
        $new_data = array_merge($data, array('request_time_stamp' => $time_stamp, 'request_txn_num' => $txn));
        $builder->INSERT( $new_data);
        $ins_id = $this->db->insertID();
        return $ins_id;
    }

    function esign_document_xml_response($txn_id, $data, $registration_id, $breadcrumb_step) {        
        $this->db->transStart();
        $builder=$this->db->table('efil.esign_logs');
        $builder->WHERE('request_txn_num', $txn_id);
        $builder->UPDATE($data);
        if ($this->db->affectedRows() > 0) {
            if ($breadcrumb_step ) { 
                $status = $this->update_breadcrumbs($registration_id, $breadcrumb_step);
                if ($status) {
                    $this->db->transComplete();
                }
            } else {
                $this->db->transComplete();
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function get_sign_txn_details($txn) {   
        $builder= $this->db->table('efil.esign_logs');
        $builder->SELECT('id,userx509certificate,digestvalue');
        $builder->FROM('efil.esign_logs');
        $builder->WHERE('request_txn_num', $txn);
        $query = $builder->get();
        if ($query->getNumRows() == 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
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
        $builder->UPDATE('efil.tbl_efiling_nums', array('breadcrumb_status' => $new_breadcrumbs));
        if ($this->db->affectedRows() > 0) {
            getSessionData('efiling_details')['breadcrumb_status'] = $new_breadcrumbs;
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    function get_signers_list($registration_id, $type) {
        $builder=$this->db->table('efil.tbl_case_parties cp');
        $builder->SELECT("cp.id,cp.registration_id,cp.m_a_type, cp.party_no,cp.p_r_type,cp.email_id, d.deptname,
        st.agency_state state_name,
        case when trim(cp.party_type)!='' then cp.party_name
        else concat(case when cp.org_post_not_in_list='t' then cp.org_post_name else a.authdesc end,', ',
        case when cp.org_dept_not_in_list='t' then cp.org_dept_name else d.deptname end
        ) end party_name");
        $builder->JOIN('icmis.ref_agency_state st','cp.state_id=st.cmis_state_id', 'left');
        $builder->JOIN('icmis.deptt d','cp.org_dept_id=d.deptcode', 'left');
        $builder->JOIN('icmis.authority a','cp.org_post_id=a.authcode', 'left');
        $builder->WHERE(array('cp.registration_id'=>$registration_id, 'cp.is_deleted'=>'false', 'cp.p_r_type'=>$type));
        $builder->orderBy('cp.m_a_type desc');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }
    
    function insert_data($table, $data) {
        $builder=$this->db->table($table);
        $builder->INSERT($data);
        $ins_id = $this->db->insertID();
        return $ins_id;
    }
    
    function insert_batch_data($table, $data) {
        $builder=$this->db->table($table);
        return $builder->insertBatch($data);
    }
    
    function get_data($table, $filter_array, $select='*', $ordering='1') {
        $builder=$this->db->table($table);
        $query = $builder->WHERE($filter_array)->SELECT($select)->orderBy($ordering)->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }    
    
    function get_join_data($table, $filter_array, $join_array, $ordering='1') {
        foreach ($join_array as $join_table)
        $builder=$this->db->table($table);
            $builder->join($join_table['table'], $join_table['condition'], $join_table['type']);
        $query = $builder->WHERE($filter_array)->orderBy($ordering)->GET();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }
    
    function update_data($table, $data_array, $filter_array) {
        $builder=$this->db->table($table);
        $builder->where($filter_array)->update($data_array);
    }

}