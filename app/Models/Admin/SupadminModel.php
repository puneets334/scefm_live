<?php

namespace App\Models\Admin;
use CodeIgniter\Model;

class SupadminModel extends Model {

    function __construct() {
        parent::__construct();
    }

    function updateCaseStatus($registration_id, $case_status, $remark) {
        $this->db->transStart();
        $update_data = array(
            'deactivated_on' => date('Y-m-d H:i:s'),
            'is_active' => FALSE,
            'updated_by' => $_SESSION['login']['id'],
            'updated_by_ip' => $_SERVER['REMOTE_ADDR'],
            'remark' => $remark
        );
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('is_active', TRUE);
        $builder->UPDATE($update_data);
        $insert_data = array(
            'registration_id' => $registration_id,
            'stage_id' => $case_status,
            'activated_on' => date('Y-m-d H:i:s'),
            'is_active' => TRUE,
            'activated_by' => $_SESSION['login']['id'],
            'activated_by_ip' => $_SERVER['REMOTE_ADDR'],
            'remark' => $remark
        );
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->INSERT($insert_data);
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function get_status_that_can_be_changed() {
        $sql = "  select cs.*,en.*,
        cs2.stage_id as last_stage_to_be_updated
        from
        (select * from efil.tbl_efiling_num_status where is_active = 'true') cs
        left join efil.tbl_efiling_nums en on en.registration_id = cs.registration_id
        left join ( select * from (select *,dense_rank() over (partition by registration_id order by activated_on desc) as row_num from efil.tbl_efiling_num_status  ) z where row_num = 2 ) cs2 on cs2.registration_id = cs.registration_id
        order by en.efiling_no ";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function search_status_that_can_be_changed($efil_no) {
        $sql_1 = "SELECT efiling_for_type_id FROM efil.tbl_efiling_nums WHERE efiling_no = '" . trim(strtoupper($efil_no)) . "' OR efiling_no = '" . trim(strtolower($efil_no)) . "' ";
        $query = $this->db->query($sql_1);
        $query_1 = $query->getResult();
        if (count($query_1) > 0) {
            $res = $query->getResultArray();
            $efiling_for_type_id = $res[0]['efiling_for_type_id'];
        }
        if ($efiling_for_type_id == E_FILING_FOR_HIGHCOURT) {
            $join = 'LEFT JOIN efil.m_tbl_high_courts hc on hc.id = en.efiling_for_id and en.efiling_for_type_id =' . $efiling_for_type_id . '';
            $where_cond = " WHERE en.efiling_no = '" . trim(strtoupper($efil_no)) . "' OR en.efiling_no = '" . trim(strtolower($efil_no)) . "'";
        } else if ($efiling_for_type_id == E_FILING_FOR_ESTABLISHMENT && $_SESSION['login']['ref_m_usertype_id'] == USER_DISTRICT_ADMIN) {
            $join = 'LEFT JOIN efil.m_tbl_establishments estab on estab.id = en.efiling_for_id and en.efiling_for_type_id =' . $efiling_for_type_id . '
            LEFT JOIN efil.m_tbl_districts district on district.id = estab.ref_m_tbl_districts_id';
            $where_cond = " WHERE  district.id = " . $_SESSION['login']['admin_for_id'] . " AND (en.efiling_no = '" . trim(strtoupper($efil_no)) . "' OR en.efiling_no = '" . trim(strtolower($efil_no)) . "')";
        } else if ($efiling_for_type_id == E_FILING_FOR_ESTABLISHMENT && $_SESSION['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN) {
           $join = 'LEFT JOIN efil.m_tbl_establishments estab on estab.id = en.efiling_for_id and en.efiling_for_type_id =' . $efiling_for_type_id;
            $where_cond = " WHERE  estab.id = " . $_SESSION['login']['admin_for_id'] . " AND (en.efiling_no = '" . trim(strtoupper($efil_no)) . "' OR en.efiling_no = '" . trim(strtolower($efil_no)) . "')";
        } else if ($efiling_for_type_id == E_FILING_FOR_ESTABLISHMENT) {
            $join = 'LEFT JOIN efil.m_tbl_establishments estab on estab.id = en.efiling_for_id and en.efiling_for_type_id =' . $efiling_for_type_id;
            $where_cond = " WHERE en.efiling_no = '" . trim(strtoupper($efil_no)) . "' OR en.efiling_no = '" . trim(strtolower($efil_no)) . "'";
        } else if($efiling_for_type_id == E_FILING_FOR_SUPREMECOURT) {
            $join = 'LEFT JOIN efil.m_tbl_establishments estab on estab.id = en.efiling_for_id and en.efiling_for_type_id =' . $efiling_for_type_id;
            $where_cond = " WHERE en.efiling_no = '" . trim(strtoupper($efil_no)) . "' OR en.efiling_no = '" . trim(strtolower($efil_no)) . "'";
        } else {
            $join = '';
            $where_cond = '';
        }
        $sql = "  SELECT cs.*,en.*,
        cs2.stage_id as last_stage_to_be_updated
        FROM
        (SELECT * FROM efil.tbl_efiling_num_status where is_active = 'true') cs
        LEFT JOIN efil.tbl_efiling_nums en on en.registration_id = cs.registration_id
        LEFT JOIN ( SELECT * FROM (select *,dense_rank() over (partition by registration_id order by activated_on desc) as row_num from efil.tbl_efiling_num_status  ) z WHERE row_num = 2 ) cs2 on cs2.registration_id = cs.registration_id
        $join  $where_cond
        ORDER BY en.efiling_no";
        $query = $this->db->query($sql);
        $query_1 = $query->getResult();
        if (count($query_1) == 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_establishment_code($estab_id) {
        $builder = $this->db->table('m_tbl_establishments');
        $builder->SELECT('est_code');
        $builder->WHERE('est_code', $estab_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    function add_establishment_detail($state_id, $dist, $distdata, $estabdata) {
        $builder = $this->db->table('m_tbl_districts dt');
        $builder->SELECT('*');
        $builder->JOIN('m_tbl_state st', 'dt.ref_m_tbl_states_id=st.state_id');
        $builder->WHERE('dt.dist_code', $dist);
        $builder->WHERE('dt.ref_m_tbl_states_id', $state_id);
        $sqlquery = $builder->get();
        $this->db->transStart();
        if ($sqlquery->getNumRows() >= 1) {
            $result = $sqlquery->getResult();
            $dist_id = $result[0]->id;
        } else {
            $builder = $this->db->table('m_tbl_districts');
            $builder->INSERT($distdata);
            $dist_id = $this->db->insertID();
        }
        if (!empty($dist_id)) {
            $distid = array('ref_m_tbl_districts_id' => $dist_id);
            $dist_data = array_merge($estabdata, $distid);
            $builder = $this->db->table('m_tbl_establishments');
            $builder->INSERT($dist_data);
            $estid = $this->db->insertID();
            if ($this->db->insertID()) {
                $p_efiling_num = 0;
                $doc_efiling_no = 0;
                $fee_efiling_no = 0;
                $ia_efiling_no = 0;
                $case_data_entry_no = 0;
                $esign_appli_num = 0;
                $newYear = date('Y');
                $efiling_data = array('efiling_no' => $p_efiling_num,
                    'entry_for_type' => E_FILING_FOR_ESTABLISHMENT,
                    'efiling_year' => $newYear,
                    'ref_m_establishment_id' => $estid,
                    'updated_by' => $this->session->userdata['login']['id'],
                    'updated_on' => date('Y-m-d H:i:s'),
                    'updated_by_ip' => $_SERVER['REMOTE_ADDR'],
                    'doc_efiling_no' => $doc_efiling_no,
                    'fee_efiling_no' => $fee_efiling_no,
                    'ia_efiling_no' => $ia_efiling_no,
                    'case_data_entry_no' => $case_data_entry_no,
                    'doc_efiling_year' => $newYear,
                    'doc_updated_by' => $this->session->userdata['login']['id'],
                    'doc_updated_on' => date('Y-m-d H:i:s'),
                    'doc_updated_by_ip' => $_SERVER['REMOTE_ADDR'],
                    'fee_efiling_year' => $newYear,
                    'fee_updated_by' => $this->session->userdata['login']['id'],
                    'fee_updated_on' => date('Y-m-d H:i:s'),
                    'fee_updated_by_ip' => $_SERVER['REMOTE_ADDR'],
                    'esign_appli_num' => $esign_appli_num,
                    'esign_appli_date' => date('Y-m-d'),
                    'ia_efiling_year' => $newYear,
                    'ia_updated_by' => $this->session->userdata['login']['id'],
                    'ia_updated_on' => date('Y-m-d H:i:s'),
                    'ia_updated_by_ip' => $_SERVER['REMOTE_ADDR'],
                    'case_data_entry_year' => $newYear,
                    'case_data_entry_updated_by' => $this->session->userdata['login']['id'],
                    'case_data_entry_updated_on' => date('Y-m-d H:i:s'),
                    'case_data_entry_updated_by_ip' => $_SERVER['REMOTE_ADDR'],
                );
                $builder = $this->db->table('m_tbl_efiling_no');
                $efiling_data = $builder->INSERT($efiling_data);
                $this->db->transComplete();
            } if ($this->db->transStatus() === FALSE) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    function get_estab_details() {
        $builder = $this->db->table('m_tbl_establishments est');
        $builder->SELECT('est.id estid,*,est.display est_display');
        $builder->JOIN('m_tbl_state st', 'est.state_code=st.state_id');
        $builder->JOIN('m_tbl_districts dist', 'dist.id=est.ref_m_tbl_districts_id');
        $builder->WHERE('created_by', $_SESSION['login']['id']);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_estabid_details($estid) {
        $builder = $this->db->table('m_tbl_establishments est');
        $builder->SELECT('est.id estid,*,est.display est_display');
        $builder->JOIN('m_tbl_state st', 'est.state_code=st.state_id');
        $builder->JOIN('m_tbl_districts dist', 'dist.id=est.ref_m_tbl_districts_id');
        $builder->WHERE('created_by', $_SESSION['login']['id']);
        $builder->WHERE('est.id', $estid);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function update_establishment_detail($estid, $estabdata) {
        $builder = $this->db->table('m_tbl_establishments');
        $builder->WHERE('id', $estid);
        $builder->UPDATE($estabdata);
        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function update_hc_detail($hcid, $hcdata) {
        $this->db->transStart();
        $builder = $this->db->table('m_tbl_high_courts');
        $builder->WHERE('id', $hcid);
        $builder->UPDATE($hcdata);
        if ($this->db->affectedRows() > 0) {
            $p_efiling_num = 0;
            $doc_efiling_no = 0;
            $fee_efiling_no = 0;
            $ia_efiling_no = 0;
            $case_data_entry_no = 0;
            $esign_appli_num = 0;
            $newYear = date('Y');
            $efiling_data = array('efiling_no' => $p_efiling_num,
                'entry_for_type' => E_FILING_FOR_ESTABLISHMENT,
                'efiling_year' => $newYear,
                'ref_m_establishment_id' => $hcid,
                'updated_by' => $this->session->userdata['login']['id'],
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by_ip' => $_SERVER['REMOTE_ADDR'],
                'doc_efiling_no' => $doc_efiling_no,
                'fee_efiling_no' => $fee_efiling_no,
                'ia_efiling_no' => $ia_efiling_no,
                'case_data_entry_no' => $case_data_entry_no,
                'doc_efiling_year' => $newYear,
                'doc_updated_by' => $this->session->userdata['login']['id'],
                'doc_updated_on' => date('Y-m-d H:i:s'),
                'doc_updated_by_ip' => $_SERVER['REMOTE_ADDR'],
                'fee_efiling_year' => $newYear,
                'fee_updated_by' => $this->session->userdata['login']['id'],
                'fee_updated_on' => date('Y-m-d H:i:s'),
                'fee_updated_by_ip' => $_SERVER['REMOTE_ADDR'],
                'esign_appli_num' => $esign_appli_num,
                'esign_appli_date' => date('Y-m-d'),
                'ia_efiling_year' => $newYear,
                'ia_updated_by' => $this->session->userdata['login']['id'],
                'ia_updated_on' => date('Y-m-d H:i:s'),
                'ia_updated_by_ip' => $_SERVER['REMOTE_ADDR'],
                'case_data_entry_year' => $newYear,
                'case_data_entry_updated_by' => $this->session->userdata['login']['id'],
                'case_data_entry_updated_on' => date('Y-m-d H:i:s'),
                'case_data_entry_updated_by_ip' => $_SERVER['REMOTE_ADDR'],
            );
            $builder = $this->db->table('m_tbl_efiling_no');
            $efiling_data = $builder->INSERT($efiling_data);
            $this->db->transComplete();
        } if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function action_taken($action_id, $status) {
        if ($status == 'Active') {
            $data = array('display' => 'Y');
            $builder = $this->db->table('m_tbl_establishments');
            $builder->WHERE('id', $action_id);
            $builder->UPDATE($data);
            if ($this->db->affectedRows() > 0) {
                return true;
            } else {
                return false;
            }
        }
        if ($status == 'Deactive') {
            $data = array('display' => 'N');
            $builder = $this->db->table('m_tbl_establishments');
            $builder->WHERE('id', $action_id);
            $builder->UPDATE($data);
            if ($this->db->affectedRows() > 0) {
                return true;
            } else {
                return false;
            }
        }
        if ($status == 'Revoke') {
            $data = array('is_live' => FALSE, 'revoked_by' => $_SESSION['login']['id'], 'revoked_by_ip' => $_SERVER['REMOTE_ADDR'], 'revoked_on' => date("Y-m-d H:i:s"));
            $builder = $this->db->table('m_tbl_establishments');
            $builder->WHERE('id', $action_id);
            $builder->UPDATE($data);
            if ($this->db->affectedRows() > 0) {
                return true;
            } else {
                return false;
            }
        }
        if ($status == 'Revoke_HC') {
            $data = array('is_live' => FALSE, 'revoked_by' => $_SESSION['login']['id'], 'revoked_by_ip' => $_SERVER['REMOTE_ADDR'], 'revoked_on' => date("Y-m-d H:i:s"));
            $builder = $this->db->table('m_tbl_high_courts');
            $builder->WHERE('id', $action_id);
            $builder->UPDATE($data);
            if ($this->db->affectedRows() > 0) {
                return true;
            } else {
                return false;
            }
        }
        if ($status == 'Deactive_HC') {
            $data = array('is_active' => FALSE);
            $builder = $this->db->table('m_tbl_high_courts');
            $builder->WHERE('id', $action_id);
            $builder->UPDATE($data);
            if ($this->db->affectedRows() > 0) {
                return true;
            } else {
                return false;
            }
        }
        if ($status == 'Active_HC') {
            $data = array('is_active' => TRUE);
            $builder = $this->db->table('m_tbl_high_courts');
            $builder->WHERE('id', $action_id);
            $builder->UPDATE($data);
            if ($this->db->affectedRows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    function get_admin_states() {
        $builder = $this->db->table('m_tbl_state');
        $builder->SELECT('*');
        $builder->WHERE('state_id', $_SESSION['login']['admin_for_id']);
        $builder->orderBy('state');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    public function get_payment_detail($entry_for_type_id, $entry_for_id) {
        $builder = $this->db->table('tbl_court_fee_payment cfp');
        $builder->SELECT("cfp.*,
            (CASE when cfp.entry_for_type_id = " . E_FILING_FOR_ESTABLISHMENT . " THEN (SELECT concat(estname,', ',dist_name,', ',state) 
            FROM m_tbl_establishments est LEFT JOIN m_tbl_state st on est.state_code= st.state_id 
            LEFT JOIN m_tbl_districts dist on est.ref_m_tbl_districts_id = dist.id WHERE est.id = cfp.entry_for_id) 
            ELSE (SELECT concat(hc_name,' High Court') FROM m_tbl_high_courts hc WHERE hc.id = cfp.entry_for_id) end ) AS estab_detail");
        $builder->WHERE('cfp.receipt_uploaded_by', $_SESSION['login']['id']);
        $builder->WHERE('cfp.entry_for_type_id', $entry_for_type_id);
        $builder->WHERE('cfp.entry_for_id', $entry_for_id);
        $builder->orderBy('cfp.id', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_live_high_court_list() {
        $builder = $this->db->table('m_tbl_high_courts hc');
        $builder->SELECT("hc.id,hc_name,hc_code");
        $builder->JOIN('efil.tbl_users', 'hc.id=users.admin_for_id AND users.ref_m_usertype_id IN (' . USER_ADMIN . ',' . USER_ACTION_ADMIN . ')');
        $builder->WHERE('hc.is_active', 'TRUE');
        $builder->WHERE('hc.is_live', 'FALSE');
        $builder->WHERE('hc.parent_hc_id', NULL);
        $builder->WHERE('users.is_active', 'TRUE');
        $builder->WHERE('hc.state_code', $_SESSION['login']['admin_for_id']);
        $builder->orderBy("hc.hc_name", "asc");
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_eshtablishment_list_live($ref_m_distt_id) {
        $builder = $this->db->table('m_tbl_establishments est');
        $builder->SELECT("*");
        $builder->JOIN('efil.tbl_users', 'est.id=users.admin_for_id AND users.ref_m_usertype_id IN (' . USER_ADMIN . ',' . USER_ACTION_ADMIN . ')');
        $builder->WHERE('ref_m_tbl_districts_id', $ref_m_distt_id);
        $builder->WHERE('display', 'Y');
        $builder->WHERE('users.is_active', 'TRUE');
        $builder->WHERE('is_live', FALSE);
        $builder->orderBy("estname", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    function update_hc_live_status($hc_id) {
        $data = array('is_live' => TRUE, 'go_live_by' => $_SESSION['login']['id'], 'go_live_by_ip' => $_SERVER['REMOTE_ADDR'], 'go_live_on' => date("Y-m-d H:i:s"));
        $builder = $this->db->table('m_tbl_high_courts');
        $builder->WHERE('id', $hc_id);
        $builder->UPDATE($data);
        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function update_establishment_live_status($establishment) {
        $data = array('is_live' => TRUE, 'go_live_by' => $_SESSION['login']['id'], 'go_live_by_ip' => $_SERVER['REMOTE_ADDR'], 'go_live_on' => date("Y-m-d H:i:s"));
        $builder = $this->db->table('m_tbl_establishments');
        $builder->WHERE('est_code', $establishment);
        $builder->UPDATE($data);
        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_establishment_payment_method_detail($est_code) {
        $builder = $this->db->table('m_tbl_establishments');
        $builder->SELECT("*");
        $builder->WHERE('est_code', $est_code);
        $builder->WHERE('display', 'Y');
        $builder->WHERE('is_live', FALSE);
        $builder->orderBy("estname", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    function get_hc_payment_method_detail($hc_id) {
        $builder = $this->db->table('m_tbl_high_courts');
        $builder->SELECT("*");
        $builder->WHERE('id', $hc_id);
        $builder->WHERE('is_active', 'TRUE');
        $builder->WHERE('parent_hc_id', NULL);
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_superadmin_high_court_list() {
        $builder = $this->db->table('m_tbl_high_courts');
        $builder->SELECT("*");
        $builder->WHERE('parent_hc_id', NULL);
        $builder->WHERE('state_code', $_SESSION['login']['admin_for_id']);
        $builder->orderBy("hc_name", "asc");
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_admin_users($account_status, $request_type) {
        $user_state_id = $_SESSION['login']['njdg_st_id'];
        $request_type = array(ACCOUNT_REQUEST_EXISTS_BUT_UPDATE, ACCOUNT_REQUEST_PRACTICE_PLACE);
        $account_status = array(ACCOUNT_STATUS_ACTIVE, ACCOUNT_STATUS_UPDATED);
        $request_status = array(ACCOUNT_REQUEST_PRACTICE_PLACE, ACCOUNT_REQUEST_EXISTS_BUT_NEW);
        $builder = $this->db->table('users u');
        $builder->SELECT('u.id,u.ref_m_usertype_id,u.first_name,u.njdg_st_id,u.userid,u.emailid,u.created_datetime,
            u.moblie_number, adv_estab.adv_user_id,adv_estab.state_code ,
            adv_estab.state_name, adv_estab.dist_name,adv_estab.estab_name,adv_estab.estab_code,adv_estab.court_type');
        $builder->JOIN('tbl_advocate_establishments adv_estab', 'adv_estab.state_code = ' . $user_state_id . ' and adv_estab.adv_user_id=u.id');
        $builder->whereIn('u.account_status', $account_status);
        $builder->whereIn('adv_estab.request_type', $request_type);
        $builder->whereIn('adv_estab.request_status', $request_status);
        $query = $builder->get();
        return $query->getResult();
    }

    function deactivate_account($account_status, $user_id) {
        $builder = $this->db->table('users');
        $builder->SELECT('*');
        $builder->WHERE('id', $user_id);
        $builder->UPDATE($account_status);
        if ($this->db->affectedRows()) {
            return true;
        } else {
            return false;
        }
    }

}