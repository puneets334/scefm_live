<?php

namespace App\Models\Vakalatnama;
use CodeIgniter\Model;
use App\Libraries\webservices\Efiling_webservices;

class VakalatnamaModel extends Model {

    protected $efiling_webservices;

    function __construct() {
        parent::__construct();
        $this->efiling_webservices = new Efiling_webservices();
    }

    function get_user_types() {
        $builder = $this->db->table('efil.tbl_users u');
        $builder->SELECT('u.id,ut.user_type,first_name,last_name,moblie_number,emailid');
        $builder->JOIN('efil.tbl_user_types ut', 'u.ref_m_usertype_id=ut.id');
        $builder->WHERE('u.is_deleted', FALSE);
        $builder->WHERE('ut.is_deleted', FALSE);
        $builder->whereIn('ut.id', array('1'));
        $query = $builder->get();
        if ($query->getNumRows()) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    public function search_vakalatnama_or_parties_by_diaryNo($diaryNo, $diaryYear, $party = null) {
        $diary_data = $this->efiling_webservices->get_case_diary_details_from_SCIS($diaryNo, $diaryYear);
        if ($diary_data != false) {
            $case_data = $diary_data->case_details[0];
            $parties = $diary_data->case_details[0]->parties;
            if ($party != 'P') {
                $efiling_detailsFinal = array(
                    'registration_id' => $case_data->diary_no . $case_data->diary_year,
                    'diary_no' => $case_data->diary_no,
                    'diary_year' => $case_data->diary_year,
                    'case_num' => ltrim(substr($case_data->active_fil_no, -6), '0'),
                    'case_year' => $case_data->active_reg_year,
                    'case_type_name' => $case_data->reg_no_display,
                    'pet_name' => $case_data->pet_name,
                    'res_name' => $case_data->res_name,
                    'pno' => $case_data->pno,
                    'rno' => $case_data->rno,
                    'date_of_decision' => $case_data->ord_dt,
                    'advocates' => $case_data->advocates,
                    'case_grp' => $case_data->case_grp,
                    'cause_title' => $case_data->cause_title,
                    'case_status' => $case_data->c_status,
                    'database_type' => 'I'
                );
                session()->set(['efiling_details' => $efiling_detailsFinal]);
            }
            return $parties;
        } else {
            return false;
        }
    }

    public function get_vakalatnama_parties_details($registration_id, $vakalatnama_id, $p_r_type) {
        $builder = $this->db->table('.tbl_vakalatnama_parties as vp');
        $builder->SELECT(array('vp.*'));
        $builder->WHERE('vp.registration_id', $registration_id);
        $builder->WHERE('vp.vakalatnama_id', $vakalatnama_id);
        $builder->WHERE('vp.p_r_type', $p_r_type);
        $builder->WHERE('vp.created_by', $_SESSION['login']['id']);
        $builder->WHERE('vp.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_case_parties_details_vakalatnama($registration_id, $details_of_party) {
        if (!empty(session()->get('efiling_details')['database_type']) && session()->get('efiling_details')['database_type'] == 'I') {
            return  $this->search_vakalatnama_or_parties_by_diaryNo(session()->get('efiling_details')['diary_no'], session()->get('efiling_details')['diary_year'], 'P');
        }
        if ($details_of_party['view_lr_list']) {
            $lr_params = ', lr_of.party_name lr_of_name, lr_of.relation lr_of_relation, lr_of.relative_name lr_of_relative_name';
        } else {
            $lr_params = '';
        }
        $builder = $this->db->table('efil.tbl_case_parties cp');
        $builder->SELECT("cp.id, cp.registration_id,cp.m_a_type,cp.p_r_type as pet_res,cp.email_id as email,cp.party_name as partyname,
            cp.party_no as sr_no_show,cp.relation, cp.relative_name, cp.party_age, cp.gender,
            cp.party_dob, cp.have_legal_heir, cp.parent_id, cp.party_id, cp.is_org,
            cp.party_type, cp.org_state_id, cp.org_state_name, cp.org_state_not_in_list,
            cp.org_dept_id, cp.org_dept_name, cp.org_dept_not_in_list,
            cp.org_post_id, cp.org_post_name, cp.org_post_not_in_list,
            cp.address, cp.city, cp.district_id, cp.state_id, cp.pincode,
            cp.mobile_num,cp.lrs_remarks_id ,lrs.lrs_remark,dist.name addr_dist_name, a.authdesc,cp.is_dead_minor,
            d.deptname ,st.agency_state addr_state_name,vst.deptname fetch_org_state_name" . $lr_params);
        $builder->JOIN('icmis.ref_agency_state st', 'cp.state_id = st.cmis_state_id', 'left');
        $builder->JOIN('icmis.view_state_in_name vst', 'cp.org_state_id = vst.deptcode', 'left');
        $builder->JOIN('icmis.state dist', 'cp.district_id = dist.id_no', 'left');
        $builder->JOIN('icmis.deptt d', 'cp.org_dept_id=d.deptcode', 'left');
        $builder->JOIN('icmis.authority a', 'cp.org_post_id=a.authcode', 'left');
        $builder->JOIN('efil.m_tbl_lrs_remarks lrs', 'cp.lrs_remarks_id = lrs.id', 'left');
        if (isset($details_of_party['view_lr_list']) && !empty($details_of_party['view_lr_list'])) {
            $builder->JOIN('efil.tbl_case_parties lr_of', 'cast(cp.parent_id as varchar) = lr_of.party_id and cp.p_r_type = lr_of.p_r_type');
        }
        if (isset($details_of_party['p_r_type']) && !empty($details_of_party['p_r_type'])) {
            $$builder->WHERE('cp.p_r_type', $details_of_party['p_r_type']);
        }
        if (isset($details_of_party['m_a_type']) && !empty($details_of_party['m_a_type'])) {
            $builder->WHERE('cp.m_a_type', $details_of_party['m_a_type']);
            $builder->WHERE('cp.parent_id IS NULL');
        }
        if (isset($details_of_party['party_id']) && !empty($details_of_party['party_id'])) {
            $builder->WHERE('cp.id', $details_of_party['party_id']);
        }
        $builder->WHERE('cp.registration_id', $registration_id);
        if (isset($details_of_party['view_lr_list']) && !empty($details_of_party['view_lr_list'])) {
            $builder->WHERE('lr_of.registration_id', $registration_id);
            $builder->WHERE('lr_of.is_deleted', FALSE);
        }
        $builder->WHERE('cp.is_deleted', FALSE);
        $builder->orderBy("cp.party_no", "asc");
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $party_details = $query->getResult();
            return $party_details;
        } else {
            return FALSE;
        }
    }

    public function get_vakalatnama_search($search_type, $efiling_no = null, $diary_no = null, $diary_year = null, $case_no = null, $case_year = null, $case_type_id = null) {
        $builder = $this->db->table('efil.tbl_efiling_nums as en');
        $builder->SELECT(array(
            'en.efiling_for_type_id', 'en.efiling_for_id', 'en.ref_m_efiled_type_id',
            'en.efiling_no', 'en.efiling_year', 'en.registration_id', 'en.allocated_on', 'en.create_on',
            'et.efiling_type', 'cs.stage_id', 'cs.activated_on', 'en.sub_created_by',
            'new_case_cd.cause_title', 'new_case_cd.sc_diary_num as diary_no', 'new_case_cd.sc_diary_year as diary_year', 'new_case_cd.sc_diary_date', 'new_case_cd.sc_display_num', 'new_case_cd.sc_reg_date',
            'lcd.case_num', 'lcd.case_year', 'lcd.case_type_id', 'lcd.court_type',
            'users.first_name', 'users.last_name', 'users.moblie_number', 'users.emailid'
        ));
        $builder->JOIN('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id');
        $builder->JOIN('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $builder->JOIN('efil.tbl_case_details as new_case_cd', 'en.registration_id = new_case_cd.registration_id', 'left');
        $builder->JOIN('efil.tbl_users users', 'users.id=en.created_by', 'left');
        $builder->JOIN('efil.tbl_user_types ut', 'ut.id=users.ref_m_usertype_id');
        $builder->JOIN('efil.tbl_lower_court_details lcd', 'en.registration_id=lcd.registration_id', 'left');
        if (!empty($search_type) && $search_type != null && $search_type == 'register' && $search_type != 'efilingNO' && $search_type != 'diary') {
            if (!empty($case_no) && $case_no != null) {
                $builder->WHERE('lcd.case_num', $case_no);
            }
            if (!empty($case_year) && $case_year != null) {
                $builder->WHERE('lcd.case_year', $case_year);
            }
            if (!empty($case_type_id) && $case_type_id != null) {
                $builder->WHERE('lcd.case_type_id', $case_type_id);
            }
            $builder->WHERE('lcd.court_type', '4');
        } else if (!empty($search_type) && $search_type != null && $search_type == 'diary' && $search_type != 'efilingNO') {
            if (!empty($diary_no) && $diary_no != null) {
                $builder->WHERE('new_case_cd.sc_diary_num', $diary_no);
            }
            if (!empty($diary_year) && $diary_year != null) {
                $builder->WHERE('new_case_cd.sc_diary_year', $diary_year);
            }
        } else if (!empty($search_type) && $search_type != null && $search_type == 'efilingNO' && $search_type != 'diary') {
            if (!empty($efiling_no) && $efiling_no != null) {
                $builder->WHERE('en.efiling_no', $efiling_no);
            }
        }
        $builder->WHERE('cs.is_active', 'TRUE');
        $builder->WHERE('en.is_active', 'TRUE');
        $builder->orderBy('cs.activated_on');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $efiling_details = $query->getResultArray();
            $datamain = array(['database_type' => 'E']);
            $efiling_detailsFinal = array_merge($datamain[0], $efiling_details[0]);
            $this->session->set_userdata(array('efiling_details' => $efiling_detailsFinal));
            return $efiling_details;
        } else {
            return false;
        }
    }

    public function get_vakalatnama_details($registration_id, $vakalatnama_id = null, $p_r_type = null) {
        $builder = $this->db->table('vakalat.tbl_vakalatnama_parties tvp');
        $builder->select('array_to_json(array_agg(row_to_json(tvp)))');
        $builder->where('vakalatnama_id = v.id and tvp.is_deleted = false');
        $subquery = $builder->getCompiledSelect();
        $builder = $this->db->table('vakalat.tbl_vakalatnama as v');
        $builder->SELECT("v.* ,users.first_name, users.last_name, users.moblie_number, users.emailid, ($subquery) as parties");
        $builder->JOIN('efil.tbl_users users', 'users.id=v.myself_adv_id');
        $builder->WHERE('v.myself_adv_id', session()->get('login')['id']);
        $builder->WHERE('v.registration_id', $registration_id);
        if (!empty($vakalatnama_id) && $vakalatnama_id != null) {
            $builder->WHERE('v.id', $vakalatnama_id);
        }
        if (!empty($p_r_type) && $p_r_type != null) {
            $builder->WHERE('v.p_r_type', $p_r_type);
        }
        $builder->WHERE('v.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function add_or_update($data, $vakatalnama_id = null) {
        if (!empty($vakatalnama_id) && $vakatalnama_id != null) {
            $builder = $this->db->table('vakalat.tbl_vakalatnama');
            $builder->SET($data);
            $builder->UPDATE();
            $builder->WHERE('id', $vakatalnama_id);
            if ($this->db->affectedRows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            $builder = $this->db->table('vakalat.tbl_vakalatnama');
            $builder->INSERT($data);
            return $insert_id =  $this->db->insertID();
        }
    }

    function add_party($registration_id, $vakatalnama_id, $p_r_type, $data, $type) {
        $created_on = date('Y-m-d H:i:s');
        if ($type == 'update' || $type == 'insert') {
            foreach ($data as $row) {
                $is_party = $this->is_vakalatnama_parties_exist($registration_id, $vakatalnama_id, $p_r_type, $row['party_id'], $row['party_name']);
                if ($is_party == false) {
                    $party_data_add = array(
                        'vakalatnama_id' => $vakatalnama_id,
                        'registration_id' => $registration_id,
                        'party_id' => $row['party_id'],
                        'party_name' => $row['party_name'],
                        'p_r_type' => $p_r_type,
                        'party_no' => $row['party_no'],
                        'created_on' => $created_on,
                        'created_on_ip' => getClientIP(),
                        'party_email_id' => $row['party_email_id'],
                        'created_by' => session()->get('login')['id'],
                        'database_type' => session()->get('efiling_details')['database_type'],
                    );
                    if (!empty($party_data_add) && $party_data_add != null) {
                        $builder = $this->db->table('vakalat.tbl_vakalatnama_parties');
                        $builder->INSERT($party_data_add);
                    }
                }
            }
            return TRUE;
        } else {
            return $false = 'Something Wrong not update or insert, Please check type of flag then try again later.';
        }
    }

    public function is_vakalatnama_parties_exist($registration_id, $vakalatnama_id, $p_r_type, $party_id, $party_name) {
        $builder = $this->db->table('vakalat.tbl_vakalatnama_parties as vp');
        $builder->SELECT('vp.*');
        $builder->WHERE('vp.registration_id', $registration_id);
        $builder->WHERE('vp.party_id', $party_id);
        $builder->WHERE('vp.p_r_type', $p_r_type);
        $builder->WHERE('vp.created_by', session()->get('login')['id']);
        $builder->WHERE('vp.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $builder = $this->db->table('vakalat.tbl_vakalatnama');
            $builder->WHERE('id', $vakalatnama_id);
            $builder->DELETE();
            session()->setFlashdata('msg', '<div class="alert alert-danger text-center">This party "' . $party_name . '" is already exist check then try again!.</div>');
            redirect('vakalatnama/dashboard/add');
            return TRUE;
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function is_vakalatnama_parties($registration_id, $p_r_type, $party_id) {
        $builder = $this->db->table('vakalat.tbl_vakalatnama_parties as vp');
        $builder->SELECT('vp.*');
        $builder->JOIN('vakalat.tbl_vakalatnama v', 'vp.vakalatnama_id=v.id');
        $builder->WHERE('vp.registration_id', $registration_id);
        $builder->WHERE('vp.party_id', $party_id);
        $builder->WHERE('vp.p_r_type', $p_r_type);
        $builder->WHERE('vp.created_by', session()->get('login')['id']);
        $builder->WHERE('vp.is_deleted', FALSE);
        $builder->WHERE('v.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function is_vakalatnama_parties_update($data, $id) {
        $builder = $this->db->table('vakalat.tbl_vakalatnama_parties');
        $builder->WHERE('id', $id);
        $builder->SET($data);
        $builder->UPDATE();
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function vakalatnama_party_delete($party_id, $registration_id = null, $vakalatnama_id = null) {
        $party_data_update = array(
            'is_deleted' => TRUE,
            'updated_on_ip' => getClientIP(),
            'updated_on' => date('Y-m-d H:i:s')
        );
        $builder = $this->db->table('vakalat.tbl_vakalatnama_parties');
        $builder->WHERE('party_id', $party_id);
        if (!empty($registration_id) && $registration_id != null) {
            $builder->WHERE('registration_id', $registration_id);
        }
        if (!empty($vakalatnama_id) && $vakalatnama_id != null) {
            $builder->WHERE('id', $vakalatnama_id);
        }
        $builder->SET($party_data_update);
        $builder->UPDATE();
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_vakalatnama_aor_list($registration_id, $vakalatnama_id) {
        $builder = $this->db->table('vakalat.tbl_vakalatnama_aor as va');
        $builder->SELECT(array('va.*', 'users.first_name', 'users.last_name', 'users.moblie_number', 'users.emailid'));
        $builder->JOIN('efil.tbl_users users', 'users.id=va.aor_code');
        $builder->WHERE('va.registration_id', $registration_id);
        $builder->WHERE('va.vakalatnama_id', $vakalatnama_id);
        $builder->WHERE('va.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

}