<?php

namespace App\Models\NewCase;

use CodeIgniter\Model;

class CourtFeeModel extends Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_uploaded_pages_count($registration_id)
    {
        $builder = $this->db->table('efil.tbl_uploaded_pdfs docs');
        $builder->SELECT('SUM(docs.page_no) uploaded_pages_count');
        $builder->WHERE('docs.registration_id', $registration_id);
        $builder->WHERE('docs.is_deleted', FALSE);
        $builder->groupBy('docs.registration_id', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResult();
            return $result[0]->uploaded_pages_count;
        } else {
            return 0;
        }
    }

    function get_payment_details($registration_id)
    {
        $builder = $this->db->table('efil.tbl_court_fee_payment cfp');
        $builder->SELECT('*');
        $builder->WHERE('cfp.registration_id', $registration_id);
        $builder->WHERE('cfp.is_deleted', FALSE);
        $builder->orderBy('cfp.id', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function insert_pg_request($data_to_save)
    {
        $this->db->table('efil.tbl_court_fee_payment')->INSERT($data_to_save);
        if ($this->db->insertID()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_court_fee_details($reg_id)
    {
        if (!is_numeric($reg_id)) {
            return false;
        }
        $sql = "SELECT  cf.court_fee, ct.casename, subj.sub_name4, orders_challendged, cd.*
            FROM efil.tbl_case_details cd 
            LEFT JOIN icmis.casetype ct ON ct.casecode = cd.sc_case_type_id
            -- LEFT JOIN icmis.submaster subj ON subj.id = cd.subject_cat
            LEFT JOIN icmis.submaster subj ON subj.id = cd.subject_cat AND subj.is_old='N' AND subj.display='Y' AND subj.is_3j=0 AND subj.is_5j=0 AND subj.is_7j=0 AND subj.is_9j=0 AND subj.is_11j=0
            LEFT JOIN icmis.m_court_fee cf ON cf.casetype_id = ct.casecode AND subj.id = cf.submaster_id
            LEFT JOIN (SELECT count(id) orders_challendged FROM efil.tbl_lower_court_details lc 
            WHERE lc.registration_id = $reg_id AND is_judgment_challenged IS true AND is_deleted IS false)a ON 1=1
            WHERE cd.registration_id = '" . $reg_id . "' ";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $res = $query->getResultArray();
        }
        return $res;
    }

    function save_court_fee_details($reg_id)
    {
        $sql = "insert into tbl_court_fee_payment set registration_id='', entry_for_type_id='', entry_for_id='', user_declared_court_fee='', user_declared_total_amt='', received_amt='', gras_payment_status=''";
        $query = $this->db->query($sql);
        return $this->db->insertID();
    }

    function get_challan_details($challan_no)
    {
        $builder = $this->db->table('efil.tbl_court_fee_payment cfp');
        $builder->SELECT('*');
        $builder->WHERE('cfp.transaction_num', trim($challan_no));
        $builder->WHERE('cfp.is_deleted', FALSE);
        $builder->orderBy('cfp.id', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }
}
