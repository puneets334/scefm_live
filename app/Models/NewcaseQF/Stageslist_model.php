<?php
namespace App\Models\NewCaseQF;

use CodeIgniter\Model;
class Stageslist_model extends Model {

    function __construct() {
// Call the Model constructor
        parent::__construct();
    }

// Function used to get stage wise list of efiling nums from dashboard 

    public function get_efilied_nums_stage_wise_list($stage_ids, $created_by) {

        $this->db->SELECT(array('en.efiling_for_type_id', 'en.efiling_for_id', 'en.ref_m_efiled_type_id', 'en.efiling_no', 'en.efiling_year', 'en.registration_id', 'en.allocated_on', 'et.efiling_type',
            'cs.stage_id', 'cs.activated_on', 'en.sub_created_by',
            'ec.cino', 'ec.pet_name', 'ec.res_name', 'ec.fil_case_type_name', 'ec.fil_no', 'ec.fil_year',
            'ec.reg_case_type_name', 'ec.reg_no', 'ec.reg_year',
            'ia.ia_fil_case_type_name', 'ia.ia_filno', 'ia.ia_filyear', 'ia.ia_reg_case_type_name', 'ia.ia_regno', 'ia.ia_regyear', 'ia.cino ia_cnr_num',
            'ms.case_type_name', 'ms.cnr_num', 'ms.fil_no misc_fil_no', 'ms.fil_year misc_fil_year', 'ms.reg_no misc_reg_no', 'ms.reg_year misc_reg_year',
            'ms.cause_title', 'ms.efiling_case_reg_id', 'users.first_name', 'users.last_name', 'dp.*','cl.*',
            "(case when en.efiling_for_type_id=" . E_FILING_FOR_ESTABLISHMENT . " Then (select concat(estname,', ',dist_name,', ',state)  from m_tbl_establishments est
                left join m_tbl_state st on est.state_code= st.state_id
                left join m_tbl_districts dist on est.ref_m_tbl_districts_id =dist.id
                where est.id = en.efiling_for_id ) 
                ELSE (select concat(hc_name,' High Court') from m_tbl_high_courts hc
                where hc.id = en.efiling_for_id) end ) 
                as efiling_for_name"));
        $this->db->FROM('tbl_efiling_nums as en');
        $this->db->JOIN('tbl_efiling_case_status as cs', 'en.registration_id = cs.registration_id');
        $this->db->JOIN('m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $this->db->JOIN('tbl_efiling_civil as ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id', 'left');
        $this->db->JOIN('tbl_misc_doc_filing as ms', 'ms.ref_m_efiling_nums_registration_id=en.registration_id ', 'left');
        $this->db->JOIN('eia_filing as ia', 'ia.registration_id = en.registration_id ', 'left');
        $this->db->JOIN(dynamic_users_table, 'en.created_by=users.id', 'LEFT');
        $this->db->JOIN('m_tbl_departments dp', 'en.sub_created_by=dp.dep_user_id', 'LEFT');
        $this->db->JOIN('m_tbl_clerks cl', 'en.sub_created_by=cl.clerk_id', 'LEFT');
        $this->db->WHERE('cs.is_active', 'TRUE');

        $this->db->WHERE('en.is_active', 'TRUE');
        $this->db->WHERE_IN('cs.stage_id', $stage_ids);
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
            $this->db->WHERE('en.sub_created_by', $created_by);
        } else {
            $this->db->WHERE('en.created_by', $created_by);
        }
        $this->db->ORDER_BY('cs.activated_on', 'DESC');

        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }


    public function get_complaint_efiling_civil_extra_party($regid) {
    
    	$this->db->SELECT('count(en.id)');
    	$this->db->FROM('tbl_efiling_civil_extra_party as en');
    	$this->db->WHERE('ref_m_efiling_nums_registration_id', $regid);
    	$this->db->WHERE('type', 1);
    	$query = $this->db->get();
    	if ($query->num_rows() > 0) {
    		$result = $query->result();
    		return $result[0]->count;
    	} else {
    		return false;
    	}
    }

    public function get_accuse_efiling_civil_extra_party($regid) {

        $this->db->SELECT('count(en.id)');
        $this->db->FROM('tbl_efiling_civil_extra_party as en');
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $regid);
        $this->db->WHERE('type', 2);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result[0]->count;
        } else {
            return false;
        }
    }

// Function used to get stage wise list of efiling nums from dashboard
// Function used to get Finally Submitted list of efiling nums from dashboard

    public function get_efilied_nums_submitted_list($stages, $created_by) {

        $this->db->SELECT(array('en.efiling_for_type_id', 'en.efiling_no', 'en.ref_m_efiled_type_id', 'en.efiling_year', 'en.registration_id', 'et.efiling_type', 'cs.stage_id',
            'cs.activated_on', 'ec.pet_name', 'ms.case_type_name', 'res_name', 'ms.petitioner_name', 'ms.respondent_name'));
        $this->db->FROM('tbl_efiling_nums as en');
        $this->db->JOIN('tbl_efiling_case_status as cs', 'en.registration_id = cs.registration_id');
        $this->db->JOIN('m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $this->db->JOIN('tbl_efiling_civil as ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id', 'left');
        $this->db->JOIN('tbl_misc_doc_filing as ms', 'ms.ref_m_efiling_nums_registration_id=en.registration_id ', 'left');
        $this->db->WHERE('cs.is_active', 'TRUE');
        $this->db->WHERE('en.is_active', 'TRUE');
        $this->db->WHERE_NOT_IN('cs.stage_id', $stages);

        if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
            $this->db->WHERE('en.sub_created_by', $created_by);
        } else {
            $this->db->WHERE('en.created_by', $created_by);
        }
        $this->db->ORDER_BY('cs.activated_on', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

// Function used to get Finally Submitted list of efiling nums from dashboard
// Function used to get efiled types wise list of efiling nums from dashboard


    public function get_efiled_list($stage_ids, $efiled_type, $created_by) {
        $this->db->SELECT(array('en.efiling_for_type_id', 'en.efiling_for_id', 'en.ref_m_efiled_type_id', 'en.efiling_no', 'en.efiling_year', 'en.registration_id', 'et.efiling_type',
            'cs.stage_id', 'cs.activated_on',
            'ec.cino', 'ec.pet_name', 'ec.res_name', 'ec.fil_case_type_name', 'ec.fil_no', 'ec.fil_year',
            'ec.reg_case_type_name', 'ec.reg_no', 'ec.reg_year',
            'ms.cnr_num', 'ms.case_type_name', 'ms.fil_no misc_fil_no', 'ms.fil_year misc_fil_year', 'ms.reg_no misc_reg_no', 'ms.reg_year misc_reg_year',
            'ms.cause_title', 'ms.efiling_case_reg_id',
            'ia.ia_fil_case_type_name', 'ia.ia_filno', 'ia.ia_filyear', 'ia.ia_reg_case_type_name', 'ia.ia_regno', 'ia.ia_regyear', 'ia.cino ia_cnr_num',
            "(case when en.efiling_for_type_id =" . E_FILING_FOR_ESTABLISHMENT . " Then (select concat(estname,', ',dist_name,', ',state)  from m_tbl_establishments est
                left join m_tbl_state st on est.state_code= st.state_id
                left join m_tbl_districts dist on est.ref_m_tbl_districts_id =dist.id
                where est.id = en.efiling_for_id ) 
                ELSE (select concat(hc_name,' High Court') from m_tbl_high_courts hc
                where hc.id = en.efiling_for_id) end ) 
                as efiling_for_name"
        ));
        $this->db->FROM('tbl_efiling_nums as en');
        $this->db->JOIN('tbl_efiling_case_status as cs', 'en.registration_id = cs.registration_id');
        $this->db->JOIN('m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $this->db->JOIN('tbl_efiling_civil as ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id', 'left');
        $this->db->JOIN('tbl_misc_doc_filing as ms', 'ms.ref_m_efiling_nums_registration_id=en.registration_id ', 'left');
        $this->db->JOIN('eia_filing as ia', 'ia.registration_id = en.registration_id ', 'left');
        $this->db->WHERE('cs.is_active', 'TRUE');
        $this->db->WHERE('en.is_active', 'TRUE');
        $this->db->WHERE_IN('cs.stage_id', $stage_ids);
        $this->db->WHERE('en.created_by', $created_by);
        $this->db->WHERE_IN('en.ref_m_efiled_type_id', $efiled_type);
        $this->db->ORDER_BY('cs.activated_on', 'DESC');
        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

// Function used to get efiled types wise list of efiling nums from dashboard

    public function get_efiling_num_basic_Details($regid) {
        $this->db->SELECT(array('en.efiling_no', 'en.ref_m_efiled_type_id', 'en.efiling_year', 'en.registration_id', 'et.efiling_type', 'cs.stage_id',
            'cs.activated_on', 'ec.pet_name', 'ec.res_name'));
        $this->db->FROM('tbl_efiling_nums as en');
        $this->db->JOIN('tbl_efiling_case_status as cs', 'en.registration_id = cs.registration_id');
        $this->db->JOIN('m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $this->db->JOIN('tbl_efiling_civil as ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id', 'left');
        $this->db->WHERE('cs.is_active', 'TRUE');
        $this->db->WHERE('en.is_active', 'TRUE');
        $this->db->WHERE('en.registration_id', $regid);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_Misc_Docs_Details($regid) {
        $this->db->SELECT('misc.*,
                            docs.doc_id,docs.doc_type_id,docs.sub_doc_type_id,
                            docs.file_name,docs.page_no,docs.no_of_copies,
                            docs.is_admin_checked,
                            docs.uplodaded_by,docs.uploaded_on,docs.upload_ip_address,
                            docs.doc_type_name,docs.doc_title,docs.doc_hashed_value,efnum.efiling_no,efnum.efiling_year,efnum.ref_m_efiled_type_id');
        $query = $this->db->FROM('tbl_misc_doc_filing misc');
        $this->db->JOIN('tbl_efiled_docs docs', 'misc.ref_m_efiling_nums_registration_id=docs.ref_m_efiling_nums_registration_id', 'left');
        $this->db->JOIN('tbl_efiling_nums efnum', 'misc.ref_m_efiling_nums_registration_id=efnum.registration_id', 'left');
        $this->db->WHERE('misc.ref_m_efiling_nums_registration_id', $regid);
        $this->db->ORDER_BY('docs.uploaded_on');
        $query = $this->db->get();

        if ($query->num_rows()) {
            $result = $query->result();
            return $result;
        } else {
            return false;
        }
    }

    public function get_Misc_Docs_count($regid) {
        $this->db->SELECT('count(docs.doc_id)');
        $this->db->FROM('tbl_efiled_docs docs');
        $this->db->WHERE('docs.ref_m_efiling_nums_registration_id', $regid);
        $this->db->WHERE('docs.is_active', TRUE);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result[0]->count;
        } else {
            return false;
        }
    }

    public function get_Petitioner_Preview_Details($regid) {
        $this->db->SELECT("ec.pet_name, ec.ref_m_efiling_nums_registration_id,ec.pet_sex,ec.pet_gender, ec.pet_father_flag, ec.pet_father_name, 
                ec.pet_age, ec.pet_dob, ec.pet_extracount, ec.pet_email, ec.pet_mobile, ec.petadd, ec.pet_pincode,
                ec.pet_uid,ec.ci_cri,dn.pet_org_name, dn.pet_caste_name,dn.pet_state_name, dn.pet_distt_name ,dn.pet_taluka_name,
                dn.pet_town_name,dn.pet_ward_name, dn.pet_village_name, dn.pet_ps_name, ec.doc_signed_used,ec.matter_type,ec.macp");
        $this->db->FROM('tbl_efiling_civil as ec');
        $this->db->JOIN('tbl_cis_masters_values as dn', 'dn.registration_id=ec.ref_m_efiling_nums_registration_id', 'LEFT');
        $this->db->WHERE('ec.ref_m_efiling_nums_registration_id', $regid);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_Respondent_Preview_Details($regid) {
        $this->db->SELECT("ec.ref_m_efiling_nums_registration_id,ec.res_name, ec.res_sex,ec.res_gender, ec.res_father_flag, ec.res_father_name, 
                ec.res_age, ec.res_dob, ec.res_extracount, ec.res_email, ec.res_mobile, ec.resadd, ec.res_pincode,
                ec.res_uid,dn.res_org_name, dn.res_caste_name,dn.res_state_name, dn.res_distt_name ,dn.res_taluka_name,
                dn.res_town_name,dn.res_ward_name, dn.res_village_name, dn.res_ps_name");
        $this->db->FROM('tbl_efiling_civil as ec');
        $this->db->JOIN('tbl_cis_masters_values as dn', 'dn.registration_id=ec.ref_m_efiling_nums_registration_id', 'LEFT');
        $this->db->WHERE('ec.ref_m_efiling_nums_registration_id', $regid);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_Extra_Info_Preview_Details($regid) {
        $this->db->SELECT("ec.ref_m_efiling_nums_registration_id,ec.pet_passportno, ec.petpanno,ec.pet_fax, ec.pet_phone, ec.pet_country, 
                ec.pet_nationality, ec.pet_occu, ec.res_passportno, ec.respanno, ec.res_fax, ec.res_phone, ec.res_country,
                ec.res_nationality,ec.res_occu,ec.res_add2,ec.pet_add2,
                dn.extra_info_pet_state_name, dn.extra_info_pet_distt_name,dn.extra_info_pet_taluka_name, dn.extra_info_pet_town_name ,dn.extra_info_pet_ward_name,
                dn.extra_info_pet_village_name,dn.extra_info_res_state_name, dn.extra_info_res_distt_name, dn.extra_info_res_taluka_name,
                dn.extra_info_res_town_name, dn.extra_info_res_ward_name, dn.extra_info_res_village_name");
        $this->db->FROM('tbl_efiling_civil as ec');
        $this->db->JOIN('tbl_cis_masters_values as dn', 'dn.registration_id=ec.ref_m_efiling_nums_registration_id', 'LEFT');
        $this->db->WHERE('ec.ref_m_efiling_nums_registration_id', $regid);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_Subordinate_court_Preview_Details($regid) {
        $this->db->SELECT("ec.ref_m_efiling_nums_registration_id,ec.lower_cino, ec.lower_judge_name,ec.lower_court, ec.filing_case, ec.lower_court_dec_dt, 
                ec.lcc_applied_date, ec.lcc_received_date,dn.lower_court_name, dn.lower_court_case_type");
        $this->db->FROM('tbl_efiling_civil as ec');
        $this->db->JOIN('tbl_cis_masters_values as dn', 'dn.registration_id=ec.ref_m_efiling_nums_registration_id', 'LEFT');
        $this->db->WHERE('ec.ref_m_efiling_nums_registration_id', $regid);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_Acts_Preview_Details($regid) {
        $this->db->SELECT("ec.ref_m_efiling_nums_registration_id, ec.under_sec1,ec.under_sec2, ec.under_sec3, ec.under_sec4,
                dn.act_name_1, dn.act_name_2, dn.act_name_3, dn.act_name_4");
        $this->db->FROM('tbl_efiling_civil as ec');
        $this->db->JOIN('tbl_cis_masters_values as dn', 'dn.registration_id=ec.ref_m_efiling_nums_registration_id', 'LEFT');
        $this->db->WHERE('ec.ref_m_efiling_nums_registration_id', $regid);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_Extra_Party_Preview_Details($regid) {
        $this->db->SELECT("eparty.*");
        $this->db->FROM('tbl_efiling_civil_extra_party as eparty');
        $this->db->WHERE('eparty.ref_m_efiling_nums_registration_id', $regid);
        $this->db->WHERE('eparty.parentid', NULL);
        $this->db->WHERE('eparty.display', 'Y');
        $this->db->ORDER_BY("eparty.type", "asc");
        $this->db->ORDER_BY("cast(eparty.party_id as float)", "asc");        
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return NULL;
        }
    }

    public function get_legal_heir_Preview_Details($regid) {

        $sql_query = "SELECT * FROM 
                        (SELECT parent_party.name parent_name, ecep.*
                          FROM tbl_efiling_civil_extra_party ecep 
                          LEFT JOIN tbl_efiling_civil_extra_party parent_party  on parent_party.party_no = ecep.parentid
                          WHERE ecep.ref_m_efiling_nums_registration_id = " . $regid . " and parent_party.ref_m_efiling_nums_registration_id = " . $regid . "
                          AND ecep.parentid IS NOT NULL 
                          AND ecep.display = 'Y' 

                          UNION

                          SELECT parent_party.pet_name parent_name, ecep.*
                          FROM tbl_efiling_civil_extra_party ecep 
                          JOIN tbl_efiling_civil parent_party  on parent_party.ref_m_efiling_nums_registration_id = ecep.ref_m_efiling_nums_registration_id
                          WHERE ecep.ref_m_efiling_nums_registration_id = " . $regid . "
                          AND ecep.parentid = 0
                          and ecep.type = 1
                          AND ecep.display = 'Y'

                          UNION

                          SELECT parent_party.res_name parent_name, ecep.*
                          FROM tbl_efiling_civil_extra_party ecep 
                          JOIN tbl_efiling_civil parent_party  on parent_party.ref_m_efiling_nums_registration_id = ecep.ref_m_efiling_nums_registration_id
                          WHERE ecep.ref_m_efiling_nums_registration_id = " . $regid . "
                          AND ecep.parentid = 0
                          and ecep.type = 2
                          AND ecep.display = 'Y') a 
                          ORDER BY a.type, cast(a.party_id as float) ASC";

        $query = $this->db->query($sql_query);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return NULL;
        }
    }

    function get_efiling_num_details($registration_id) {

        $this->db->SELECT("en.*, et.efiling_type, cs.stage_id, cs.activated_on,"
                . " users.first_name, users.last_name, users.moblie_number, users.emailid, defect.defects_id");
        $this->db->FROM('efil.tbl_efiling_nums en');
        $this->db->JOIN('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id');
        $this->db->JOIN('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $this->db->JOIN('efil.tbl_users as users', 'users.id=en.created_by', 'left');
        $this->db->JOIN('(select registration_id, max(initial_defects_id) defects_id from efil.tbl_initial_defects where is_defect_cured is true and is_approved is false group by registration_id) as defect', 'defect.registration_id=en.registration_id', 'LEFT');
        $this->db->WHERE('en.registration_id', $registration_id);
        $this->db->WHERE('cs.is_active', 'TRUE');
        $this->db->WHERE('en.is_active', 'TRUE');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_intials_defects_remarks($registration_id, $current_stage_id) {
        if (empty($registration_id)) {
            return FALSE;
        }
        $this->db->SELECT('*');
        $this->db->FROM('efil.tbl_initial_defects');
        $this->db->WHERE('is_approved', FALSE);
        if (in_array($current_stage_id, array(Initial_Defected_Stage, DEFICIT_COURT_FEE, I_B_Defected_Stage, I_B_Rejected_Stage, E_REJECTED_STAGE, LODGING_STAGE, DELETE_AND_LODGING_STAGE))) {
            $this->db->WHERE('is_defect_cured', FALSE);
        } elseif (in_array($current_stage_id, array(Initial_Approaval_Pending_Stage, Initial_Defects_Cured_Stage, DEFICIT_COURT_FEE_PAID,
                    I_B_Approval_Pending_Stage, I_B_Approval_Pending_Admin_Stage, I_B_Defects_Cured_Stage))) {
            $this->db->WHERE('is_defect_cured', TRUE);
        }
        $this->db->WHERE('registration_id', $registration_id);
        $this->db->ORDER_BY('initial_defects_id', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            $arr = $query->result();
            return $arr[0];
        } else {
            return false;
        }
    }

    public function get_intials_defects_for_history($regid) {

        if (!(isset($regid) && !empty($regid))) {
            return FALSE;
        }
        $this->db->SELECT('*');
        $this->db->FROM('efil.tbl_initial_defects');
        $this->db->WHERE('registration_id', $regid);
        $this->db->ORDER_BY('defect_date', 'ASC');
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    function makePayment($payment_detail) {
        $this->db->trans_start();
        $this->db->INSERT('tbl_court_fee_payment', $payment_detail);
        if ($this->db->insert_id()) {

            if (ENABLE_JUDICIAL_STAMPS && $payment_detail['payment_method_code'] == PAYMENT_METHOD_CODE_STAMP) {
                if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
                    $breadcrumb_update_status = $this->Common_model->session_for_steps(NEW_CASE_COURT_FEE);
                } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
                    $breadcrumb_update_status = $this->Common_model->session_for_steps(MISC_BREAD_COURT_FEE);
                } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_DEFICIT_COURT_FEE) {
                    $breadcrumb_update_status = $this->Common_model->session_for_steps(DEFICIT_BREAD_COURTFEE);
                } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
                    $breadcrumb_update_status = $this->Common_model->session_for_steps(IA_BREAD_COURT_FEE);
                }
                $this->db->trans_complete();
            } else {
                $this->db->trans_complete();
            }
            if ($this->db->trans_status() === FALSE) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    public function updatePayment($registration_id, $order_ref_no, $payment_detail) {

        $this->db->trans_start();
        $this->db->WHERE('registration_id', $registration_id);
        $this->db->WHERE('order_no', $order_ref_no);
        $this->db->UPDATE('tbl_court_fee_payment', $payment_detail);

        if ($payment_detail['gras_payment_status'] == 'Y' && $payment_detail['payment_status'] == TRUE) {
            if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
                $this->Common_model->session_for_steps(NEW_CASE_COURT_FEE);
            } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
                $this->Common_model->session_for_steps(MISC_BREAD_COURT_FEE);
            } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_DEFICIT_COURT_FEE) {
                $this->Common_model->session_for_steps(DEFICIT_BREAD_COURTFEE);
            } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
                $this->Common_model->session_for_steps(IA_BREAD_COURT_FEE);
            }
        }

        $_SESSION['efiling_details']['gras_payment_status'] = $payment_detail['gras_payment_status'];
        $_SESSION['efiling_details']['is_payment_defective'] = NULL;
        $_SESSION['efiling_details']['is_payment_defecit'] = NULL;
        $_SESSION['efiling_details']['payment_verified_by'] = NULL;

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function get_efiled_by_user($user_id) {
        $this->db->SELECT('first_name, last_name, moblie_number');
        $this->db->FROM(dynamic_users_table);
        $this->db->WHERE('id', $user_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;
        } else {
            return false;
        }
    }

    function get_court_fees($registration_id) {
        $this->db->SELECT('*');
        $this->db->FROM('tbl_court_fee_payment');
        $this->db->WHERE('registration_id', $registration_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;
        } else {
            return false;
        }
    }

    function finalsubmitdeficitcourtfee($registration_id) {

        $case_status = DEFICIT_COURT_FEE_PAID;

        $this->db->trans_start();
        $update_data = array(
            'deactivated_on' => date('Y-m-d H:i:s'),
            'is_active' => FALSE,
            'updated_by' => $_SESSION['login']['id'],
            'updated_by_ip' => getClientIP()
        );

        $this->db->WHERE('registration_id', $registration_id);
        $this->db->WHERE('is_active', TRUE);
        $this->db->UPDATE('tbl_efiling_case_status', $update_data);

        $insert_data = array(
            'registration_id' => $registration_id,
            'stage_id' => $case_status,
            'activated_on' => date('Y-m-d H:i:s'),
            'is_active' => TRUE,
            'activated_by' => $_SESSION['login']['id'],
            'activated_by_ip' => getClientIP()
        );

        if ($this->db->affected_rows() > 0) {
            $this->db->INSERT('tbl_efiling_case_status', $insert_data);
            if ($this->db->insert_id()) {
                $update_trans_status = FALSE;

                $this->db->SELECT('max(initial_defects_id) max_id');
                $this->db->FROM('efil.tbl_initial_defects');
                $this->db->WHERE('registration_id', $registration_id);
                $this->db->WHERE('is_defect_cured', FALSE);
                $this->db->WHERE('is_approved', FALSE);
                $query = $this->db->get();
                $query_result = $query->result();
                $initial_defects_max_id = $query_result[0]->max_id;

                if ($initial_defects_max_id != '' && $initial_defects_max_id > 0) {

                    $update_defect_data = array(
                        'is_defect_cured' => TRUE,
                        'defect_cured_date' => date('Y-m-d H:i:s')
                    );
                    $this->db->WHERE('registration_id', $registration_id);
                    $this->db->WHERE('initial_defects_id', $initial_defects_max_id);
                    $this->db->UPDATE('efil.tbl_initial_defects', $update_defect_data);
                    if ($this->db->affected_rows() > 0) {

                        $update_trans_status = TRUE;
                    }
                } else {
                    $update_trans_status = TRUE;
                }
                if ($update_trans_status) {
                    $this->db->trans_complete();
                }
            }
        }
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function get_receipt_file($regid) {
        $this->db->SELECT('cfa.payment_receipts_file,nums.efiling_no');
        $this->db->FROM('tbl_court_fees cfa');
        $this->db->JOIN('tbl_efiling_nums as nums', 'cfa.registration_id = nums.registration_id');
        $this->db->WHERE('cfa.registration_id', $regid);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_filing_details($regid) {
        $this->db->SELECT('nums.efiling_no ,nums.efiling_year');
        $this->db->FROM('tbl_efiling_nums nums');
        $this->db->WHERE('registration_id', $regid);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function getPaymentDefect($regid) {
        $this->db->SELECT('defect_remark,defect_date');
        $this->db->FROM('tbl_payment_defects');
        $this->db->WHERE('is_defect_cured', 'FALSE');
        $this->db->WHERE('registration_id', $regid);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    function transefer_to_section($registration_id, $stage_id) {
        $this->db->trans_start();
        $update_data = array(
            'deactivated_on' => date('Y-m-d H:i:s'),
            'is_active' => FALSE,
            'updated_by' => $_SESSION['login']['id'],
            'updated_by_ip' => getClientIP()
        );

        $this->db->WHERE('registration_id', $registration_id);
        $this->db->WHERE('is_active', TRUE);
        $this->db->UPDATE('tbl_efiling_case_status', $update_data);
        $insert_data = array(
            'registration_id' => $registration_id,
            'stage_id' => $stage_id,
            'activated_on' => date('Y-m-d H:i:s'),
            'is_active' => TRUE,
            'activated_by' => $_SESSION['login']['id'],
            'activated_by_ip' => getClientIP()
        );

        if ($this->db->affected_rows() > 0) {
            $this->db->INSERT('tbl_efiling_case_status', $insert_data);
            if ($this->db->insert_id()) {
                $this->db->trans_complete();
            }
        }

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function get_total_payment_detail($regid) {

        $this->db->SELECT('*');
        $this->db->FROM('tbl_court_fee_payment');
        $this->db->WHERE('registration_id', $regid);
        $this->db->WHERE('is_active', TRUE);

        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_total_payment_detail_pdf_view($regid) {

        $this->db->SELECT('*');
        $this->db->FROM('tbl_court_fee_payment');
        $this->db->WHERE('registration_id', $regid);
        $this->db->WHERE('is_active', TRUE);
        $this->db->WHERE('is_payment_defective', FALSE);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_payment_status($regid) {

        $this->db->SELECT('*');
        $this->db->FROM('tbl_court_fee_payment');
        $this->db->WHERE('registration_id', $regid);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_payment_details($regid) {
        $this->db->SELECT('*');
        $this->db->FROM('tbl_court_fee_payment');
        $this->db->WHERE('is_active', TRUE);
        $this->db->WHERE('payment_verified_by', NULL);
        $this->db->WHERE('payment_verified_date', NULL);
        $this->db->WHERE('registration_id', $regid);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_payment_preview_details($regid) {
        $this->db->SELECT('*');
        $this->db->FROM('efi.tbl_court_fee_payment');
        $this->db->WHERE('is_active', TRUE);
        $this->db->WHERE('registration_id', $regid);
        $this->db->ORDER_BY('id', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function add_payment_details($data, $file_temp_name) {

        $efiling_num = $_SESSION['efiling_details']['efiling_no'];
        $establishment_code = $_SESSION['estab_details']['est_code'];
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        if (!empty($file_temp_name)) {
            $file_uploaded_dir = 'uploaded_docs/' . $establishment_code . '/' . $efiling_num . '/court_fee/';
            $file = $file_temp_name;
            $filename = $data['receipt_name'];
            if (!is_dir($file_uploaded_dir)) {
                $uold = umask(0);
                if (mkdir($file_uploaded_dir, 0777, true)) {
                    $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                    write_file($file_uploaded_dir . '/index.html', $html);
                }
                umask($uold);
            }

            $data2 = array('reciept_uploaded_path' => $file_uploaded_dir);
            $this->db->trans_start();
            $result = $this->upload_file($file_uploaded_dir, $filename, $file);
            $merge_array_data = array_merge($data, $data2);
        } else {
            $this->db->trans_start();
            $result = TRUE;
            $merge_array_data = $data;
        }
        if ($result) {
            if ($this->db->INSERT('tbl_court_fee_payment', $merge_array_data)) {
                if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
                    $this->Common_model->session_for_steps(NEW_CASE_COURT_FEE);
                } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
                    $this->Common_model->session_for_steps(MISC_BREAD_COURT_FEE);
                } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_DEFICIT_COURT_FEE) {
                    $this->Common_model->session_for_steps(DEFICIT_BREAD_COURTFEE);
                } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
                    $this->Common_model->session_for_steps(IA_BREAD_COURT_FEE);
                }

                $signed_status = array('is_data_valid' => FALSE);
                $this->db->WHERE('ref_registration_id', $registration_id);
                $this->db->UPDATE('esign_logs', $signed_status);

                $_SESSION['efiling_details']['gras_payment_status'] = $data['gras_payment_status'];
                $_SESSION['efiling_details']['is_payment_defective'] = NULL;
                $_SESSION['efiling_details']['is_payment_defecit'] = NULL;
                $_SESSION['efiling_details']['payment_verified_by'] = NULL;
            }
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                unlink($file_uploaded_dir . '/' . $filename);
                return 'trans_failed';
            } else {
                return 'trans_success';
            }
        } else {
            return 'upload_fail';
        }
    }

    function upload_file($file_uploaded_dir, $filename, $file) {
        $uploaded = move_uploaded_file($file, "$file_uploaded_dir/$filename");
        if ($uploaded) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function delete_payment_details($id, $uploaded_by, $registration_id) {
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
            $receipt_uploaded_by = "sub_created_by";
        } else {
            $receipt_uploaded_by = "receipt_uploaded_by";
        }
        $this->db->trans_start();
        $this->db->SELECT('tbl_court_fee_payment.*');
        $this->db->FROM('tbl_court_fee_payment');
        $this->db->WHERE('tbl_court_fee_payment.id', $id);
        $this->db->WHERE('registration_id', $registration_id);
        $this->db->WHERE($receipt_uploaded_by, $uploaded_by);
        $this->db->WHERE('payment_verified_by', NULL);
        $this->db->WHERE('payment_verified_date', NULL);
        $query = $this->db->get();

        if ($query->num_rows()) {
            $result = $query->result();

            $this->db->WHERE('id', $id);
            $this->db->WHERE($receipt_uploaded_by, $uploaded_by);
            $this->db->WHERE('registration_id', $registration_id);
            $this->db->WHERE('payment_verified_by', NULL);
            $this->db->WHERE('payment_verified_date', NULL);
            $this->db->DELETE('tbl_court_fee_payment');

            $signed_status = array('is_data_valid' => FALSE);
            $this->db->WHERE('ref_registration_id', $registration_id);
            $this->db->UPDATE('esign_logs', $signed_status);

            $_SESSION['efiling_details']['gras_payment_status'] = NULL;
            $_SESSION['efiling_details']['is_payment_defective'] = NULL;
            $_SESSION['efiling_details']['is_payment_defecit'] = NULL;
            $_SESSION['efiling_details']['payment_verified_by'] = NULL;

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                return FALSE;
            } else {
                unlink($result[0]->reciept_uploaded_path . $result[0]->receipt_name);

                $sentSMS = "Fee Receipt " . $result[0]->receipt_name . " under Efiling No. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " is deleted successfully by you.";
                $subject = "Fee Receipt under Efiling No. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " deleted by you from your efiling account.";
                $user_name = $_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'];

                send_mobile_sms($_SESSION['login']['mobile_number'], $sentSMS,EFILING_FEE_RECEIPT_DELETION);
                send_mail_msg($_SESSION['login']['emailid'], $subject, $sentSMS, $user_name);

                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    function get_current_stage($registration_id) {
        $this->db->SELECT('registration_id, tbl_efiling_case_status.stage_id, m_tbl_dashboard_stages.user_stage_name, m_tbl_dashboard_stages.admin_stage_name');
        $this->db->FROM('tbl_efiling_case_status');
        $this->db->JOIN('m_tbl_dashboard_stages', 'm_tbl_dashboard_stages.stage_id = tbl_efiling_case_status.stage_id');
        $this->db->WHERE('tbl_efiling_case_status.registration_id', $registration_id);
        $this->db->WHERE('tbl_efiling_case_status.is_active', TRUE);
        $this->db->ORDER_BY('tbl_efiling_case_status.status_id', 'DESC');
        $this->db->LIMIT(1);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function get_stages($registration_id) {
        $this->db->SELECT('*');
        $this->db->FROM('tbl_efiling_case_status');
        $this->db->JOIN('m_tbl_dashboard_stages as ds', 'tbl_efiling_case_status.stage_id=ds.stage_id', 'LEFT');
        $this->db->WHERE('tbl_efiling_case_status.registration_id', $registration_id);
        $this->db->ORDER_BY('tbl_efiling_case_status.activated_on', 'ASC');

        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_Case_detail($registration_id) {
        $this->db->SELECT(array('en.efiling_no', 'ec.cino', 'en.ref_m_efiled_type_id', 'en.efiling_year', 'en.registration_id', 'et.efiling_type', 'cs.stage_id',
            'cs.activated_on', 'ec.pet_name', 'ms.case_type_name', 'res_name', 'ms.cause_title'));
        $this->db->FROM('tbl_efiling_nums as en');
        $this->db->JOIN('tbl_efiling_case_status as cs', 'en.registration_id = cs.registration_id');
        $this->db->JOIN('m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $this->db->JOIN('tbl_efiling_civil as ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id', 'left');
        $this->db->JOIN('tbl_misc_doc_filing as ms', 'ms.ref_m_efiling_nums_registration_id=en.registration_id ', 'left');

        $this->db->WHERE('en.registration_id', $registration_id);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_Misc_Docs_Details_history($regid) {
        $this->db->SELECT('misc.*,
                            docs.doc_id,docs.doc_type_id,docs.sub_doc_type_id,
                            docs.file_name,docs.page_no,docs.no_of_copies,
                            docs.uplodaded_by,docs.uploaded_on,docs.upload_ip_address,docs.doc_title,
                            docs.doc_type_name,efnum.efiling_no,efnum.efiling_year,efnum.ref_m_efiled_type_id');
        $query = $this->db->FROM('tbl_misc_doc_filing misc');
        $this->db->JOIN('tbl_efiled_docs docs', 'misc.ref_m_efiling_nums_registration_id=docs.ref_m_efiling_nums_registration_id', 'left');
        $this->db->JOIN('tbl_efiling_nums efnum', 'docs.ref_m_efiling_nums_registration_id=efnum.registration_id', 'left');
        $this->db->WHERE('misc.ref_m_efiling_nums_registration_id', $regid);
        $this->db->ORDER_BY('docs.uploaded_on');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;
        } else {
            return false;
        }
    }

    public function get_court_fee_payment_history($regid) {
        $this->db->SELECT('*');
        $this->db->FROM('tbl_court_fee_payment');
        $this->db->WHERE('tbl_court_fee_payment.registration_id', $regid);
        $this->db->WHERE('tbl_court_fee_payment.is_active', TRUE);
        $this->db->ORDER_BY('id', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result_array();
            return $result;
        } else {
            return false;
        }
    }

    public function get_police_station_details($regid) {
        $this->db->SELECT("ec.ref_m_efiling_nums_registration_id, ec.police_private,ec.dt_chargesheet,ec.offense_date, ec.fir_year, ec.fir_no,
                ec.investofficer, ec.investofficer1, ec.beltno, ec.beltno1,ec.causeofaction,ec.fir_date, mv.police_private_challan, 
                mv.police_st_state_name, mv.police_st_dist_name,mv.police_station_name, mv.fir_type_name, mv.trials_name, mv.investigation_agency");
        $this->db->FROM('tbl_efiling_civil as ec');
        $this->db->JOIN('tbl_cis_masters_values as mv', 'mv.registration_id=ec.ref_m_efiling_nums_registration_id', 'LEFT');
        $this->db->WHERE('ec.ref_m_efiling_nums_registration_id', $regid);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    function lodges_case($registration_id, $lodges_case_reason) {
        $this->db->trans_start();
        $update_data = array(
            'deactivated_on' => date('Y-m-d H:i:s'),
            'is_active' => FALSE,
            'updated_by' => $_SESSION['login']['id'],
            'updated_by_ip' => getClientIP()
        );

        $this->db->WHERE('registration_id', $registration_id);
        $this->db->WHERE('is_active', TRUE);
        $this->db->UPDATE('tbl_efiling_case_status', $update_data);

        if ($this->db->affected_rows() > 0) {
            $insert_defect = array(
                'registration_id' => $registration_id,
                'defect_remark' => $lodges_case_reason,
                'defect_date' => date('Y-m-d H:i:s'),
                'is_defect_cured' => FALSE,
                'updated_by' => $_SESSION['login']['id'],
                'ip_address' => getClientIP()
            );
            $this->db->INSERT('efil.tbl_initial_defects', $insert_defect);

            if ($this->db->insert_id()) {
                $insert_data = array(
                    'registration_id' => $registration_id,
                    'stage_id' => LODGING_STAGE,
                    'activated_on' => date('Y-m-d H:i:s'),
                    'is_active' => TRUE,
                    'activated_by' => $_SESSION['login']['id'],
                    'activated_by_ip' => getClientIP()
                );
                $this->db->INSERT('tbl_efiling_case_status', $insert_data);
                if ($this->db->insert_id()) {
                    $this->db->trans_complete();
                }
            }
        }
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function delete_and_lodges_case($registration_id, $efiling_num, $lodges_case_reason) {
        $this->db->trans_start();
        $update_data = array(
            'deactivated_on' => date('Y-m-d H:i:s'),
            'is_active' => FALSE,
            'updated_by' => $_SESSION['login']['id'],
            'updated_by_ip' => getClientIP()
        );

        $this->db->WHERE('registration_id', $registration_id);
        $this->db->WHERE('is_active', TRUE);
        $this->db->UPDATE('tbl_efiling_case_status', $update_data);

        if ($this->db->affected_rows() > 0) {
            $insert_defect = array(
                'registration_id' => $registration_id,
                'defect_remark' => $lodges_case_reason,
                'defect_date' => date('Y-m-d H:i:s'),
                'is_defect_cured' => FALSE,
                'updated_by' => $_SESSION['login']['id'],
                'ip_address' => getClientIP()
            );
            $this->db->INSERT('efil.tbl_initial_defects', $insert_defect);

            if ($this->db->insert_id()) {
                $insert_data = array(
                    'registration_id' => $registration_id,
                    'stage_id' => DELETE_AND_LODGING_STAGE,
                    'activated_on' => date('Y-m-d H:i:s'),
                    'is_active' => TRUE,
                    'activated_by' => $_SESSION['login']['id'],
                    'activated_by_ip' => getClientIP()
                );
                $this->db->INSERT('tbl_efiling_case_status', $insert_data);
                if ($this->db->insert_id()) {

                    $est_code = $_SESSION['estab_details']['est_code'];

                    $directory = 'uploaded_docs/' . $est_code . '/' . $efiling_num;
                    $fee_directory = 'court_fee/' . $efiling_num;
                    if (is_dir($directory)) {
                        $this->recursiveRemoveDirectory($directory);
                    }
                    if (is_dir($fee_directory)) {
                        $this->recursiveRemoveDirectory($fee_directory);
                    }
                    $this->db->trans_complete();
                }
            }
        }
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function trash_case($registration_id, $efiling_num) {
        $this->db->trans_start();
        $update_data = array(
            'deactivated_on' => date('Y-m-d H:i:s'),
            'is_active' => FALSE,
            'updated_by' => $_SESSION['login']['id'],
            'updated_by_ip' => getClientIP()
        );

        $this->db->WHERE('registration_id', $registration_id);
        $this->db->WHERE('is_active', TRUE);
        $this->db->UPDATE('tbl_efiling_case_status', $update_data);

        if ($this->db->affected_rows() > 0) {

            $insert_data = array(
                'registration_id' => $registration_id,
                'stage_id' => TRASH_STAGE,
                'activated_on' => date('Y-m-d H:i:s'),
                'is_active' => TRUE,
                'activated_by' => $_SESSION['login']['id'],
                'activated_by_ip' => getClientIP()
            );
            $this->db->INSERT('tbl_efiling_case_status', $insert_data);
            if ($this->db->insert_id()) {

                $est_code = $_SESSION['estab_details']['est_code'];

                $directory = 'uploaded_docs/' . $est_code . '/' . $efiling_num;
                $fee_directory = 'court_fee/' . $efiling_num;
                $this->load->helper('file');
                if (is_dir($directory)) {

                    delete_files($directory, TRUE);
                    rmdir($directory);
                }
                if (is_dir($fee_directory)) {
                    delete_files($fee_directory, TRUE);
                    rmdir($fee_directory);
                }
                $this->db->trans_complete();
            }
        }
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function recursiveRemoveDirectory($directory) {
        foreach (glob("{$directory}/*") as $file) {
            if (is_dir($file)) {
                recursiveRemoveDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($directory);
    }

    function register_otp_generation_entry() {

        $data['register_updated_date_time'] = date('Y-m-d H:i:s');

        $this->db->INSERT('esign_logs', $data);

        $ins_id = $this->db->insert_id();

        return $ins_id;
    }

    function save_otp_generation_entry() {

        $_SESSION['otp_generation']['otp_generation_updated_date_time'] = date('Y-m-d H:i:s');

        $this->db->where('id', $_SESSION['insert_id']);

        $this->db->update('esign_logs', $_SESSION['otp_generation']);

        return true;
    }

    function save_otp_verification_entry() {

        $_SESSION['otp_verification']['otp_verification_updated_date_time'] = date('Y-m-d H:i:s');

        $this->db->where('id', $_SESSION['insert_id']);

        $this->db->update('esign_logs', $_SESSION['otp_verification']);

        return true;
    }

    
    function get_court_fees_receipts($doc_id, $registration_id) {
        $this->db->SELECT('receipt_name,reciept_uploaded_path');
        $this->db->FROM('tbl_court_fee_payment');
        $this->db->WHERE('id', $doc_id);
        $this->db->WHERE('registration_id', $registration_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;
        } else {
            return false;
        }
    }

    function get_pg_txn_log_details($order_ref_no) {

        $this->db->SELECT('txn_log_id, order_ref_no, txn_amt, pg_txn_ref_no,        
            pg_txn_date, pg_auth_status, pg_checksum_status, is_active');
        $this->db->FROM('tbl_pg_transaction_log');
        $this->db->WHERE('order_ref_no', $order_ref_no);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result_array();
            return $result;
        } else {
            return false;
        }
    }

    function update_payment_status($order_no) {

        $this->db->SELECT('*');
        $this->db->FROM('tbl_court_fee_payment');
        $this->db->WHERE('order_no', $order_no);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function check_grn_no($order_no) {

        $this->db->SELECT('*');
        $this->db->FROM('tbl_court_fee_payment');
        $this->db->WHERE('order_no', $order_no);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function assign_efiling_number($efiling_for_type_id, $efiling_for_id) {

        $query = "SELECT users.id,  users.ref_m_usertype_id, users.admin_for_type_id, users.admin_for_id, count(allocated_on) efiling_num_count
                FROM " . dynamic_users_table . "
                LEFT JOIN tbl_efiling_nums ON tbl_efiling_nums.allocated_to = users.id AND to_char(tbl_efiling_nums.allocated_on,'YYYY-MM-DD')::date ='" . date('Y-m-d') . "'
                WHERE users.admin_for_type_id='" . $efiling_for_type_id . "' AND users.admin_for_id='" . $efiling_for_id . "' AND
                users.is_active IS TRUE AND
                users.ref_m_usertype_id IN(" . USER_ADMIN . "," . USER_ACTION_ADMIN . ")                  
                GROUP BY users.id,users.ref_m_usertype_id,users.admin_for_type_id,users.admin_for_id ORDER BY efiling_num_count";


        $query = $this->db->query($query);

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function reset_affirmation_model($docid, $reg_id, $efile_no, $update_data, $type) {

        $this->db->trans_start();
        $this->db->WHERE('ref_registration_id', $reg_id);
        $this->db->WHERE('ref_efiling_no', $efile_no);
        $this->db->WHERE('type', $type);
        $this->db->UPDATE('esign_logs', $update_data);

        if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
            $aff_step_no = NEW_CASE_AFFIRMATION;
        } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
            $aff_step_no = MISC_BREAD_AFFIRMATION;
        } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
            $aff_step_no = IA_BREAD_AFFIRMATION;
        }

        $array = explode(',', $_SESSION['efiling_details']['breadcrumb_status']);
        $index = array_search($aff_step_no, $array);
        if ($index !== false) {
            unset($array[$index]);
        }
        $breadcrumbsArray = array('breadcrumb_status' => implode(',', $array));
        $_SESSION['efiling_details']['breadcrumb_status'] = implode(',', $array);
        $this->Common_model->update_breadcrumbs_status($reg_id, $breadcrumbsArray);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function efiling_no_allocated($data, $ref_m_efiled_type_id) {

        $this->db->trans_start();
        $this->db->INSERT('tbl_efiling_allocation', $data);
        if ($this->db->insert_id()) {

            $this->db->WHERE('registration_id', $data['registration_id']);
            $this->db->WHERE('ref_m_efiled_type_id', $ref_m_efiled_type_id);
            $this->db->UPDATE('tbl_efiling_nums', array('allocated_to' => $_SESSION['login']['id'], 'allocated_on' => $data['allocated_on']));

            $this->db->trans_complete();
        }
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function get_allocated_history($registration_id) {
        $this->db->SELECT("ea.*,concat(users.first_name,' ',users.last_name) as admin_name");
        $this->db->FROM('tbl_efiling_allocation ea');
        $this->db->JOIN(dynamic_users_table, 'users.id = ea.admin_id', 'LEFT');
        $this->db->WHERE('registration_id', $registration_id);
        $this->db->ORDER_BY('id', 'ASC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function get_reason_list() {
        $this->db->SELECT("reason");
        $this->db->FROM('m_tbl_allocation_reason');
        $this->db->WHERE('is_active', TRUE);
        $this->db->ORDER_BY('reason', 'ASC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    public function get_efilied_nums_cis_admin($stage_ids, $admin_for_type_id, $admin_for_id, $efiling_type) {
        $this->db->SELECT('en.efiling_for_type_id, en.efiling_for_id, en.efiling_no, en.ref_m_efiled_type_id, en.efiling_year, en.registration_id, '
                . 'et.efiling_type, '
                . 'cs.stage_id, cs.activated_on, users.first_name, users.last_name, users.moblie_number, users.emailid, defect.defects_id');
        $this->db->FROM('tbl_efiling_nums as en');
        $this->db->JOIN('tbl_efiling_case_status as cs', 'en.registration_id = cs.registration_id');
        $this->db->JOIN('m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $this->db->JOIN(dynamic_users_table, 'users.id=en.created_by', 'left');
        $this->db->JOIN('(select registration_id, max(initial_defects_id) defects_id from efil.tbl_initial_defects where is_defect_cured is true and is_approved is false group by registration_id) as defect', 'defect.registration_id=en.registration_id', 'LEFT');
        $this->db->WHERE('cs.is_active', 'TRUE');
        $this->db->WHERE('en.is_active', 'TRUE');
        $this->db->WHERE('en.efiling_for_type_id', $admin_for_type_id);
        $this->db->WHERE('en.efiling_for_id', $admin_for_id);
        $this->db->WHERE('en.ref_m_efiled_type_id', $efiling_type);
        $this->db->WHERE_IN('cs.stage_id', $stage_ids);
        $this->db->ORDER_BY('en.registration_id');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_establishments_list_for_cron() {
        $sql = "SELECT * FROM ((SELECT estab.id estab_id, " . E_FILING_FOR_ESTABLISHMENT . " establishment_type, estab.est_code estab_code, estab.state_code state_code
                FROM m_tbl_establishments estab
                WHERE estab.display = 'Y')
                UNION
                (SELECT hc.id estab_id , " . E_FILING_FOR_HIGHCOURT . " establishment_type, hc.hc_code estab_code, hc.state_code state_code
                FROM m_tbl_high_courts hc
                WHERE hc.is_active IS TRUE)
                ) establishments ORDER BY establishment_type, estab_id";

        $query = $this->db->query($sql);

        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_establishments_of_admin($admin_for_type_id, $admin_for_id) {
        if ($admin_for_type_id == E_FILING_FOR_ESTABLISHMENT) {
            $this->db->SELECT('estab.id estab_id, ' . E_FILING_FOR_ESTABLISHMENT . ' establishment_type, estab.est_code estab_code, estab.state_code state_code');
            $this->db->FROM('m_tbl_establishments as estab');
            $this->db->WHERE('estab.display', 'Y');
            $this->db->WHERE('estab.id', $admin_for_id);
        } elseif ($admin_for_type_id == E_FILING_FOR_HIGHCOURT) {
            $this->db->SELECT('hc.id estab_id , ' . E_FILING_FOR_HIGHCOURT . ' establishment_type, hc.hc_code estab_code, hc.state_code state_code');
            $this->db->FROM('m_tbl_high_courts as hc');
            $this->db->WHERE('hc.is_active', 'Y');
            $this->db->WHERE('hc.id', $admin_for_id);
        }
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_shcil_payment_details_for_cron($admin_for_type_id, $admin_for_id) {
        $sql = "SELECT * FROM ((SELECT estab.id estab_id, " . E_FILING_FOR_ESTABLISHMENT . " establishment_type, estab.est_code estab_code, estab.state_code state_code
                FROM m_tbl_establishments estab
                WHERE estab.display = 'Y')
                UNION
                (SELECT hc.id estab_id , " . E_FILING_FOR_HIGHCOURT . " establishment_type, hc.hc_code estab_code, hc.state_code state_code
                FROM m_tbl_high_courts hc
                WHERE hc.is_active IS TRUE)
                ) establishments ORDER BY establishment_type, estab_id";

        $query = $this->db->query($sql);

        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_establishment_district($est_code) {
        $this->db->SELECT('dc.*');
        $this->db->FROM('m_tbl_districts as dc');
        $this->db->JOIN('m_tbl_establishments as est', 'dc.id =est.ref_m_tbl_districts_id');
        $this->db->WHERE('est.est_code', $est_code);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_court_name($id, $efiling_for_type_id) {
        if ($efiling_for_type_id == ENTRY_TYPE_FOR_HIGHCOURT) {
            $this->db->SELECT('*');
            $this->db->FROM('m_tbl_high_courts hc');
            $this->db->WHERE('hc.id', $id);
        }if ($efiling_for_type_id = ENTRY_TYPE_FOR_ESTABLISHMENT) {
            $this->db->SELECT('*');
            $this->db->FROM('m_tbl_establishments est');
            $this->db->WHERE('est.id', $id);
        }
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_court_fee($shcilrefno) {
        $this->db->SELECT("*");
        $this->db->FROM('tbl_court_fee_payment');
        $this->db->WHERE('shcilpmtref', $shcilrefno);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_department_name($reg_id, $dep_user_id) {
        $this->db->SELECT("dp.*");
        $this->db->FROM("m_tbl_departments dp");
        $this->db->JOIN('tbl_efiling_nums as en', "dp.dep_user_id=en.sub_created_by");
        $this->db->WHERE('en.registration_id', $reg_id);
        $this->db->WHERE('en.sub_created_by', $dep_user_id);
        $this->db->WHERE('en.created_by', $_SESSION['login']['id']);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function get_clerk_name($reg_id, $user_id) {
        $this->db->SELECT("users.*");
        $this->db->FROM(dynamic_users_table);
        $this->db->JOIN('tbl_efiling_nums as en', "users.id=en.sub_created_by");
        $this->db->WHERE('en.registration_id', $reg_id);
        $this->db->WHERE('en.sub_created_by', $user_id);
        $this->db->WHERE('en.created_by', $_SESSION['login']['id']);
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

}
