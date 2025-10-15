<?php

namespace App\Models\NewCase;

use CodeIgniter\Model;

class ChecklistModel extends Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_checklist_data_by_registration_id($registration_id)
    {
        $builder = $this->db->table('efil.tbl_check_list_transaction');
        $builder->SELECT('*');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('created_by', getSessionData('login')['userid']);
        $builder->WHERE('cat_type', 'CA');
        $builder->orderBy('ref_m_check_list_new_id', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return NULL;
        }
  }

    function get_checklist_data_by_registration_id_pil($registration_id)
    {
        $builder = $this->db->table('efil.tbl_check_list_transaction');
        $builder->SELECT('*');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('cat_type', 'IL');
        $builder->WHERE('created_by', getSessionData('login')['userid']);
        $builder->orderBy('ref_m_check_list_new_id', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return NULL;
        }
    }

    function get_checklist_data_by_registration_id_annexure($registration_id)
    {
        $builder = $this->db->table('efil.tbl_check_list_transaction');
        $builder->SELECT('*');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('cat_type', 'D');
        $builder->WHERE('created_by', getSessionData('login')['userid']);
        $builder->orderBy('ref_m_check_list_new_id', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return NULL;
        }
    }

    function get_checklist_data_by_efiling_for_type_id($type_id)
    {
        $builder = $this->db->table('efil.m_checklist_new mcn');
        $builder->SELECT('*');
        $builder->WHERE('mcn.sc_case_type_id', $type_id);
        $builder->WHERE('mcn.is_deleted', 'false');
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            $builder = $this->db->table('efil.m_checklist_new mcn');
            $builder->SELECT('*');
            $builder->WHERE('mcn.sc_case_type_id', 999999);
            $builder->WHERE('mcn.is_deleted', 'false');
            $builder->orderBy('id', 'ASC');
            $query = $builder->get();
            $result = $query->getResultArray();
            return $result;
        }
    }

    function get_checklist_data_by_efiling_for_sub_cat_id($type_id)
    {
        $builder = $this->db->table('efil.m_checklist_new mcn');
        $builder->SELECT('*');
        $builder->WHERE('mcn.sub_cat_id', $type_id);
        $builder->WHERE('mcn.is_deleted', 'false');
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return NULL;
        }
    }

    function checkkist_resp($question_id, $registration_id)
    {
        $builder = $this->db->table('efil.tbl_check_list_transaction');
        $builder->SELECT('*');
        $builder->WHERE('question_id', $question_id);
        $builder->WHERE('registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getRowArray();
            return $result;
        } else {
            return NULL;
        }
    }    
    
    function checkkist_question_by_id($ref_m_check_list_new_id)
    {
        $builder = $this->db->table('efil.m_checklist_new');
        $builder->SELECT('*');
        $builder->WHERE('id', $ref_m_check_list_new_id);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getRow();
            return $result;
        } else {
            return NULL;
        }
    }

    public function insert_checks($insert_data)
    {
        
        $builder = $this->db->table('efil.tbl_check_list_transaction');
        $insert = $builder->insertBatch($insert_data);
        return $insert;
    }

    public function update_checks($update_data, $registration_id)
    {
        $builder = $this->db->table('efil.tbl_check_list_transaction');
        $builder->distinct();
        $builder->SELECT('ref_m_check_list_new_id');
        $builder->WHERE('registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
        } else {
            $result = array();
        }
        if (!empty($result)) {
            $existing_ids = array_column($result, 'ref_m_check_list_new_id');
        } else {
            $existing_ids = array();
        }
        $new_enrty_details = array();
        foreach ($update_data as $update) {
            if (in_array($update['ref_m_check_list_new_id'], $existing_ids)) {
                $builder = $this->db->table('efil.tbl_check_list_transaction');
                $builder->distinct();
                $builder->SELECT('*');
                $builder->WHERE('registration_id', $update['registration_id']);
                $builder->WHERE('question_no', $update['question_no']);
                $builder->WHERE('sub_question_no', $update['sub_question_no']);
                $builder->WHERE('created_by', getSessionData('login')['userid']);
                $builder->WHERE('ref_m_check_list_new_id', $update['ref_m_check_list_new_id']);
                $query = $builder->get();
                if ($query->getNumRows() >= 1) {
                    $result = $query->getRowArray();
                    if ($result['answer'] != $update['answer']) {
                        $builder = $this->db->table('efil.tbl_check_list_transaction');
                        $builder->WHERE('id', $result['id']);
                        $builder->SET(array('answer' => $update['answer']));
                        if ($builder->update()) {
                            $new_enrty_details[] = $update['ref_m_check_list_new_id'] . '-U';
                            $log_histroy =  array(
                                'transaction_id' => $result['id'],
                                'registration_id' => $update['registration_id'],
                                'question_no' => $update['question_no'],
                                'sub_question_no' => $update['sub_question_no'],
                                'answer' => $update['answer'],
                                'created_by' => getSessionData('login')['userid'],
                                'created_at' => date('Y-m-d H:i:s')
                            );
                            $builder = $this->db->table('efil.tbl_check_list_transaction_logs');
                            $builder->insert($log_histroy);
                            $new_enrty_details[] = $update['ref_m_check_list_new_id'] . '-U';
                        }
                    }
                    // } else {
                    //     $data = [
                    //         'registration_id' => $registration_id,
                    //         'question_no' => $update['question_no'],
                    //         'sub_question_no' => $update['sub_question_no'],
                    //         'answer' => $update['answer'],
                    //         'created_by' => getSessionData('login')['userid'],
                    //         'created_at' => date('Y-m-d H:i:s'),
                    //         'ref_m_check_list_new_id' => $update['ref_m_check_list_new_id']
                    //     ];
                    //     $builder = $this->db->table('efil.tbl_check_list_transaction');
                    //     $builder->insert($data);
                    //     $new_enrty_details[] = $update['ref_m_check_list_new_id'];
                    // }
                }
            }
        }
        return $new_enrty_details;
    }

    function get_annexure_data()
    {
        $builder = $this->db->table('efil.m_checklist_new mcn');
        $builder->SELECT('*');
        $builder->WHERE('mcn.annexure', 'D');
        $builder->WHERE('mcn.is_deleted', 'false');
        $builder->orderBy('mcn.id', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return NULL;
        }
    }

    function get_sci_case_type_name_by_id($id)
    {
        $builder = $this->db->table("icmis.casetype");
        $builder->select("casename");
        $builder->where('display', 'Y');
        $builder->where('casecode', $id);
        $query = $builder->get();
        $result = $query->getRow();
        return $result;
    }
}