<?php
namespace App\Models\NewCaseQF;

use CodeIgniter\Model;

class New_case_model extends Model {

    function __construct() {
// Call the Model constructor
        parent::__construct();
    }

    function add_petitioners($data, $petmobile, $pet_email, $cis_masters_values) {
        $this->db->trans_start();
        if (isset($_SESSION['estab_details']['hc_code'])) {
            $est_code = $_SESSION['estab_details']['hc_code'];
        } else {
            $est_code = $_SESSION['estab_details']['est_code'];
        }
        $curr_dt_time = date('Y-m-d H:i:s');
        if (empty($est_code)) {
            return FALSE;
        }

        if ($_SESSION['wants_to_file'] == E_FILING_TYPE_NEW_CASE) {
            $result = $this->gen_efiling_number();
            $num_pre_fix = "EC";
            $filing_type = E_FILING_TYPE_NEW_CASE;
        } elseif ($_SESSION['wants_to_file'] == E_FILING_TYPE_CDE) {
            $result = $this->gen_case_data_entry_number();
            $num_pre_fix = "DE";
            $filing_type = E_FILING_TYPE_CDE;
        }
        $stage_id = Draft_Stage;
        if ($result) {
            if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
                $created_by = 0;
                $sub_created_by = $_SESSION['login']['id'];
            } else {
                $created_by = $_SESSION['login']['id'];
                $sub_created_by = 0;
            }
            $efiling = sprintf("%'.05d\n", $result['efiling_num']);
            $string = $num_pre_fix . $est_code . $efiling . $result['efiling_year'];
            $efiling_num = preg_replace('/\s+/', '', $string);
            $efiling_num_data = array('efiling_no' => $efiling_num,
                'efiling_year' => $result['efiling_year'],
                'efiling_for_type_id' => $_SESSION['efiling_for_details']['efiling_for_type_id'],
                'efiling_for_id' => $_SESSION['efiling_for_details']['efiling_for_id'],
                'ref_m_efiled_type_id' => $filing_type,
                'created_by' => $created_by,
                'create_on' => $curr_dt_time,
                'create_by_ip' => getClientIP(),
                'sub_created_by' => $sub_created_by
            );
            $result['registration_id'] = $this->add_efiling_nums($efiling_num_data);
            if (isset($result['registration_id']) && !empty($result['registration_id'])) {

                $data['ref_m_efiling_nums_registration_id'] = $result['registration_id'];
                $result = array('registration_id' => $result['registration_id'], 'efiling_no' => $efiling_num);
                $this->session->set_userdata('efiling_details', $result);

                if (!empty($_SESSION['efiling_for_details']['adv_code']) && !empty($_SESSION['efiling_for_details']['adv_name'])) {
                    $data['pet_adv_cd'] = $_SESSION['efiling_for_details']['adv_code'];
                    $data['pet_adv'] = $_SESSION['efiling_for_details']['adv_name'];
                    $data['pet_inperson'] = NULL;
                } else {
                    $data['pet_inperson'] = 'Y';
                }
                if (!$_SESSION['efiling_for_details']['benchId'] == '') {
                    $bench_id = $_SESSION['efiling_for_details']['benchId'];
                } else {
                    $bench_id = 0;
                }

                $data['ci_cri'] = $_SESSION['efiling_for_details']['ci_cri'];
                $data['matter_type'] = $_SESSION['efiling_for_details']['matter_type'];
                $data['macp'] = $_SESSION['efiling_for_details']['macp'];
                $data['filcase_type'] = $_SESSION['efiling_for_details']['case_type'];
                $data['case_type_pet_title'] = $_SESSION['efiling_for_details']['case_type_pet_title'];
                $data['case_type_res_title'] = $_SESSION['efiling_for_details']['case_type_res_title'];
                $data['urgent'] = $_SESSION['efiling_for_details']['urgent'];
                $data['bench_type'] = $bench_id;

                $cis_masters_values['filcase_type_name'] = $_SESSION['efiling_for_details']['case_type_name'];
                $cis_masters_values['bench_name'] = $_SESSION['efiling_for_details']['benchName'];

                $this->db->INSERT('tbl_efiling_civil', $data);
                if ($this->db->insert_id()) {
                    $success = $this->update_cis_master_values($result['registration_id'], $cis_masters_values);
                    if ($success) {
                        $data3 = array(
                            'registration_id' => $result['registration_id'],
                            'stage_id' => $stage_id,
                            'activated_on' => $curr_dt_time,
                            'activated_by' => $this->session->userdata['login']['id'],
                            'activated_by_ip' => getClientIP(),
                        );
                        $this->db->INSERT('tbl_efiling_case_status', $data3);
                        if ($this->db->insert_id()) {

                            $sentSMS = "Efiling No. " . efile_preview($efiling_num) . " generated from your efiling account & still pending for final submit. - Supreme Court of India";
                            $subject = "Efiling No. " . efile_preview($efiling_num) . " generated from your efiling account";
                            $Petsubject = "Efiling No. " . efile_preview($efiling_num) . " generated from for your petition";
                            $sentPetSMS = "Efiling No. " . efile_preview($efiling_num) . "   is generated for your petition filed by your Advocate" . $user_name . " and still pending for final submission.";
                            $user_name = $this->session->userdata['login']['first_name'] . ' ' . $this->session->userdata['login']['last_name'];

                            if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE) {

                                $pet_user_name = $data['pet_name'];
                                if (!empty($data['pet_mobile'])) {
                                    send_petitioner_mobile_sms($data['pet_mobile'], $sentPetSMS);
                                }if (!empty($data['pet_email'])) {
                                    send_petitioner_mail_msg($data['pet_email'], $Petsubject, $sentPetSMS, $pet_user_name);
                                }
                            }
                            send_mobile_sms($_SESSION['login']['mobile_number'], $sentSMS);
                            send_mail_msg($_SESSION['login']['emailid'], $subject, $sentSMS, $user_name);

                            $this->db->trans_complete();
                        }
                    }
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return $result;
        }
    }

    function add_respondent($registration_id, $data, $cis_masters_values) {
        $this->db->trans_start();
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $this->db->UPDATE('tbl_efiling_civil', $data);
        if ($this->db->affected_rows() > 0) {

            if ($cis_masters_values != NULL) {
                $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                if ($success) {
                    $this->db->trans_complete();
                }
            } else {
                $this->db->trans_complete();
            }
        }

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_petitioners($registration_id, $data, $cis_masters_values) {
        $this->db->trans_start();
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $this->db->UPDATE('tbl_efiling_civil', $data);
        if ($this->db->affected_rows() > 0) {
            if ($cis_masters_values != NULL) {
                $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                if ($success) {
                    $this->db->trans_complete();
                }
            } else {
                $this->db->trans_complete();
            }
        }
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function add_extra_information($registration_id, $data, $cis_masters_values) {
        $this->db->trans_start();
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $this->db->UPDATE('tbl_efiling_civil', $data);
        if ($this->db->affected_rows() > 0) {

            if ($cis_masters_values != NULL) {
                $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                if ($success) {
                    $this->db->trans_complete();
                }
            } else {
                $this->db->trans_complete();
            }
        }
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function add_subordinate($registration_id, $data, $cis_masters_values) {
        $this->db->trans_start();
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $this->db->UPDATE('tbl_efiling_civil', $data);
        if ($this->db->affected_rows() > 0) {
            $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
            if ($success) {
                $this->db->trans_complete();
            }
        }
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function add_act_section($registration_id, $data, $cis_masters_values) {

        $enable_reconsume = array(I_B_Rejected_Stage);
        if (in_array($_SESSION['efiling_details']['stage_id'], $enable_reconsume)) {
            $reconsume = TRUE;
        }

        if (count($data['act']) > 4) {
            $i = 1;
            foreach ($data['act'] as $k => $d1) {
                if ($i <= 4) {
                    $data1[$k] = $d1;
                } else {
                    $data2[$k] = $d1;
                }
                $i++;
            }
            $j = 1;
            foreach ($data['section'] as $k => $d1) {
                if ($j <= 4) {
                    $data1[$k] = $d1;
                } else {
                    $data2[$k] = $d1;
                }
                $j++;
            }
            $k = 0;
            $l = 0;
            foreach ($data2 as $k2 => $d2) {
                if (strstr($k2, 'under_act')) {
                    $temp['act'][$k] = $d2;
                    $k++;
                }
                if (strstr($k2, 'under_sec')) {
                    $temp['section'][$l] = $d2;
                    $l++;
                }
            }

            for ($l = 0, $sr = 5; $l < count($temp['act']); $l++, $sr++) {
                $tempdata[$l] = array(
                    'ref_m_efiling_nums_registration_id' => $registration_id,
                    'serialno' => $sr,
                    'acts' => $temp['act'][$l],
                    'section' => $temp['section'][$l],
                    'display' => 'Y',
                    'reconsume' => $reconsume
                );
            }
            $data2 = $tempdata;
        } else {
            foreach ($data['act'] as $k => $d1) {
                $temp[$k] = $d1;
            }
            foreach ($data['section'] as $k => $d1) {
                $temp[$k] = $d1;
            }
            $act_count = count($data['act']);
            if ($act_count == 1) {
                for ($i = 2; $i <= 4; $i++) {
                    $temp['under_act' . $i] = 0;
                    $temp['under_sec' . $i] = NULL;
                }
            } elseif ($act_count == 2) {
                for ($i = 3; $i <= 4; $i++) {
                    $temp['under_act' . $i] = 0;
                    $temp['under_sec' . $i] = NULL;
                }
            } elseif ($act_count == 3) {
                for ($i = 4; $i <= 4; $i++) {
                    $temp['under_act' . $i] = 0;
                    $temp['under_sec' . $i] = NULL;
                }
            }
            $temp['reconsume'] = $reconsume;
            $data1 = $temp;
        }

        $this->db->trans_start();
        $this->db->DELETE('tbl_extraact', array('ref_m_efiling_nums_registration_id' => $registration_id));
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $this->db->UPDATE('tbl_efiling_civil', $data1);
        if ($this->db->affected_rows() > 0) {
            if (isset($data2) && !empty($data2)) {
                $affected_rows = $this->db->insert_batch('tbl_extraact', $data2);
                if ($affected_rows) {
                    $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                    if ($success) {
                        $this->db->trans_complete();
                    }
                }
            } else {
                $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                if ($success) {
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

    function add_mvc($data) {
        $this->db->trans_start();
        $this->db->INSERT('tbl_mvc_t', $data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_mvc($registration_id, $data) {
        $this->db->trans_start();
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $this->db->UPDATE('tbl_mvc_t', $data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function add_extra_party($data, $registration_id, $data_extra_pet_n_res) {
        $this->db->trans_start();
        $this->db->INSERT('tbl_efiling_civil_extra_party', $data);
        if ($this->db->insert_id()) {
            $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
            $this->db->UPDATE('tbl_efiling_civil', $data_extra_pet_n_res);
            if ($this->db->affected_rows()) {

                $update_lrs = array('legal_heir' => 'Y');
                $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
                $this->db->WHERE('party_no', $data['parentid']);
                $this->db->UPDATE('tbl_efiling_civil_extra_party', $update_lrs);

                if ($this->db->affected_rows()) {
                    $this->db->trans_complete();
                } else {
                    $this->db->trans_complete();
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

    function update_extra_party($id, $data, $registration_id) {
        $this->db->trans_start();
        $this->db->WHERE('id', $id);
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $this->db->UPDATE('tbl_efiling_civil_extra_party', $data);
        if ($this->db->affected_rows()) {
            //$this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
            //$this->db->UPDATE('tbl_efiling_civil', $data_extra_pet_n_res);

            if ($this->db->affected_rows()) {
                $this->db->trans_complete();
            }
        } if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function add_efiling_nums($data) {
        $this->db->INSERT('tbl_efiling_nums', $data);
        if ($this->db->insert_id()) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    function get_Uploaded_Documents($reg_id) {

        $this->db->SELECT('docs.doc_id,docs.doc_type_id,docs.doc_type_name,docs.sub_doc_type_id,
                           docs.file_name,docs.page_no,docs.no_of_copies,docs.doc_type_name,docs.is_admin_checked,
                           docs.uplodaded_by,docs.uploaded_on,docs.upload_ip_address,docs.file_uploaded_path,docs.doc_title,
                           docs.doc_hashed_value');
        $query = $this->db->FROM('tbl_efiled_docs docs');
        $this->db->WHERE('docs.ref_m_efiling_nums_registration_id', $reg_id);
        $this->db->ORDER_BY('docs.doc_id');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    function getPdfDoc($pdf_id, $uploaded_by) {
        $this->db->SELECT('tbl_efiled_docs.file_contents');
        $query = $this->db->FROM('tbl_efiled_docs');
        $this->db->WHERE('tbl_efiled_docs.doc_id', $pdf_id);
        $this->db->WHERE('tbl_efiled_docs.uplodaded_by', $uploaded_by);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;
        } else {
            return false;
        }
    }

    function deletePdfDoc($pdf_id) {
        $this->db->delete('tbl_efiled_docs', array('doc_id' => $pdf_id));
        if ($this->db->affected_rows()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function delete_extra_party($id, $type, $extra_party_type, $extra_party_party_no) {
        
        $update_lrs_status = array('display' => 'N');
        $this->db->WHERE('id', $id);
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $_SESSION['efiling_details']['registration_id']);
        $this->db->UPDATE('tbl_efiling_civil_extra_party', $update_lrs_status);
        if ($this->db->affected_rows() > 0) {
            $extra_party_count = $this->get_pet_n_res_count_civil($_SESSION['efiling_details']['registration_id']);
            if ($type == '1') {
                $data_extra_pet_n_res = array('pet_extracount' => $extra_party_count[0]['pet_extracount'] - 1);
            } elseif ($type == '2') {
                $data_extra_pet_n_res = array('res_extracount' => $extra_party_count[0]['res_extracount'] - 1);
            }

            $this->db->WHERE('ref_m_efiling_nums_registration_id', $_SESSION['efiling_details']['registration_id']);
            $this->db->UPDATE('tbl_efiling_civil', $data_extra_pet_n_res);

            if ($this->db->affected_rows() > 0) {
                if ($extra_party_type == '1' && !empty($extra_party_party_no)) {
                    $this->db->WHERE('parentid', $extra_party_party_no);
                    $this->db->WHERE('ref_m_efiling_nums_registration_id', $_SESSION['efiling_details']['registration_id']);
                    $this->db->UPDATE('tbl_efiling_civil_extra_party', $update_lrs_status);
                    if ($this->db->affected_rows() >= 0) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                } else {
                    return TRUE;
                }
            }

            return TRUE;
        } else {
            return FALSE;
        }
    }

    function assign_party_no($reg_id) {

        $this->db->SELECT('max(party_no) AS party_no');
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $reg_id);
        $this->db->WHERE('display', 'Y');
        $this->db->FROM('tbl_efiling_civil_extra_party');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result;
        }
    }

    function get_max_party_id($reg_id, $type, $lrs = NULL) {

        $this->db->SELECT('max(party_id::integer) AS party_id');
        $this->db->WHERE('type', $type);
        if ($lrs == 0 || $lrs) {
            $this->db->WHERE('parentid', $lrs);
        } else {
            $this->db->WHERE('parentid', NULL);
        }
        $this->db->WHERE('display', 'Y');
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $reg_id);
        $this->db->FROM('tbl_efiling_civil_extra_party');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    function get_extra_party_count($reg_id, $type) {

        $this->db->SELECT('count(CASE WHEN parentid = 0 THEN 1 END)-1 AS main_party_lrs,
                           count(CASE WHEN parentid != 0 or parentid IS NULL THEN 1 END)
                           as extry_party_lrs');
        $this->db->WHERE('display', 'Y');
        $this->db->WHERE('legal_heir', 'N');
        $this->db->WHERE('type', $type);
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $reg_id);
        $this->db->FROM('tbl_efiling_civil_extra_party');
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result;
        } else {
            return FALSE;
        }
    }

    function gen_efiling_number() {
        $this->db->SELECT('efiling_no,efiling_year');
        $this->db->WHERE('entry_for_type', $_SESSION['estab_details']['efiling_for_type_id']);
        $this->db->WHERE('ref_m_establishment_id', $_SESSION['estab_details']['efiling_for_id']);
        $this->db->FROM('m_tbl_efiling_no');
        $query = $this->db->get();
        $row = $query->result_array();

        $p_efiling_num = $row[0]['efiling_no'];
        $year = $row[0]['efiling_year'];
        if ($year < date('Y')) {
            $newYear = date('Y');
            $update_data = array('efiling_no' => 1,
                'efiling_year' => $newYear,
                'updated_by' => $this->session->userdata['login']['id'],
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by_ip' => getClientIP());
            $this->db->WHERE('efiling_no', $p_efiling_num);
            $this->db->WHERE('efiling_year', $year);
            $this->db->WHERE('entry_for_type', $_SESSION['estab_details']['efiling_for_type_id']);
            $this->db->WHERE('ref_m_establishment_id', $_SESSION['estab_details']['efiling_for_id']);
            $this->db->UPDATE('m_tbl_efiling_no', $update_data);
            if ($this->db->affected_rows() > 0) {
                $data['efiling_num'] = 1;
                $data['efiling_year'] = $newYear;
                return $data;
            } else {
                $this->gen_efiling_number();
            }
        } else {
            $gen_efiling_num = $p_efiling_num + 1;
            $efiling_num_info = array('efiling_no' => $gen_efiling_num,
                'updated_by' => $this->session->userdata['login']['id'],
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by_ip' => getClientIP());
            $this->db->WHERE('efiling_no', $p_efiling_num);
            $this->db->WHERE('entry_for_type', $_SESSION['estab_details']['efiling_for_type_id']);
            $this->db->WHERE('ref_m_establishment_id', $_SESSION['estab_details']['efiling_for_id']);
            $result = $this->db->UPDATE('m_tbl_efiling_no', $efiling_num_info);
            if ($this->db->affected_rows() > 0) {
                $data['efiling_num'] = $gen_efiling_num;
                $data['efiling_year'] = $year;
                return $data;
            } else {
                $this->gen_efiling_number();
            }
        }
    }

    function gen_case_data_entry_number() {
        $this->db->SELECT('case_data_entry_no,case_data_entry_year');
        $this->db->WHERE('entry_for_type', $_SESSION['estab_details']['efiling_for_type_id']);
        $this->db->WHERE('ref_m_establishment_id', $_SESSION['estab_details']['efiling_for_id']);
        $this->db->FROM('m_tbl_efiling_no');
        $query = $this->db->get();
        $row = $query->result_array();

        $p_efiling_num = $row[0]['case_data_entry_no'];
        $year = $row[0]['case_data_entry_year'];
        if ($year < date('Y')) {
            $newYear = date('Y');
            $update_data = array('case_data_entry_no' => 1,
                'case_data_entry_year' => $newYear,
                'updated_by' => $this->session->userdata['login']['id'],
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by_ip' => getClientIP());
            $this->db->WHERE('case_data_entry_no', $p_efiling_num);
            $this->db->WHERE('case_data_entry_year', $year);
            $this->db->WHERE('entry_for_type', $_SESSION['estab_details']['efiling_for_type_id']);
            $this->db->WHERE('ref_m_establishment_id', $_SESSION['estab_details']['efiling_for_id']);
            $this->db->UPDATE('m_tbl_efiling_no', $update_data);
            if ($this->db->affected_rows() > 0) {
                $data['efiling_num'] = 1;
                $data['efiling_year'] = $newYear;
                return $data;
            } else {
                $this->gen_efiling_number();
            }
        } else {
            $gen_efiling_num = $p_efiling_num + 1;
            $efiling_num_info = array('case_data_entry_no' => $gen_efiling_num,
                'updated_by' => $this->session->userdata['login']['id'],
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by_ip' => getClientIP());
            $this->db->WHERE('case_data_entry_no', $p_efiling_num);
            $this->db->WHERE('entry_for_type', $_SESSION['estab_details']['efiling_for_type_id']);
            $this->db->WHERE('ref_m_establishment_id', $_SESSION['estab_details']['efiling_for_id']);
            $result = $this->db->UPDATE('m_tbl_efiling_no', $efiling_num_info);
            if ($this->db->affected_rows() > 0) {
                $data['efiling_num'] = $gen_efiling_num;
                $data['efiling_year'] = $year;
                return $data;
            } else {
                $this->gen_efiling_number();
            }
        }
    }

    function get_efiling_civil_details($registration_id) {
        $this->db->SELECT("tbl_efiling_nums.*,tbl_efiling_civil.*,(CASE WHEN tbl_efiling_nums.efiling_for_type_id =" . E_FILING_FOR_ESTABLISHMENT . " THEN (SELECT concat(estname,', ',dist_name,', ',state)  FROM m_tbl_establishments est
                LEFT JOIN m_tbl_state st on est.state_code = st.state_id
                LEFT JOIN m_tbl_districts dist on est.ref_m_tbl_districts_id = dist.id
                WHERE est.id = tbl_efiling_nums.efiling_for_id ) 
                ELSE (select concat(hc_name,' High Court') FROM m_tbl_high_courts hc
                WHERE hc.id = tbl_efiling_nums.efiling_for_id) END ) 
                as efiling_for_name");
        $this->db->FROM('tbl_efiling_nums');
        $this->db->JOIN('tbl_efiling_civil', 'tbl_efiling_nums.registration_id = tbl_efiling_civil.ref_m_efiling_nums_registration_id');
        $this->db->WHERE('tbl_efiling_nums.registration_id', $registration_id);
        $this->db->WHERE('tbl_efiling_nums.is_active', TRUE);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            $result = $query->result_array();
            $this->db->SELECT("tbl_extraact.*");
            $this->db->FROM('tbl_extraact');
            $this->db->WHERE('tbl_extraact.ref_m_efiling_nums_registration_id', $registration_id);
            $this->db->WHERE('tbl_extraact.display', 'Y');
            $query2 = $this->db->get();
            if ($query2->num_rows() >= 1) {
                $result2 = $query2->result_array();
                $i = 5;
                foreach ($result2 as $res) {
                    $result[0]['under_act' . $i] = $res['acts'];
                    $result[0]['under_sec' . $i] = $res['section'];
                    $i++;
                }
            }
            return $result;
        } else {
            return false;
        }
    }

    function get_mvc_details($registration_id) {
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $query = $this->db->get('tbl_mvc_t', 1);
        return $query->result_array();
    }

    function get_extra_party_details($registration_id) {
        $this->db->SELECT('*');
        $this->db->FROM('tbl_efiling_civil_extra_party');
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $this->db->WHERE('parentid', NULL);
        $this->db->WHERE('display', 'Y');
        //$this->db->ORDER_BY("party_no", "asc");
        $this->db->ORDER_BY('type');
        $this->db->ORDER_BY('cast(party_id as float)');
        $query = $this->db->get();

        return $query->result();
    }

    function get_LRs_details($registration_id) {

        $sql_query = "SELECT * FROM 
                          (SELECT parent_party.name parent_name, ecep.id, ecep.ref_m_efiling_nums_registration_id, ecep.party_no, ecep.parentid, ecep.name,ecep.father_name,
                             ecep.extra_party_relation_name, ecep.extra_not_in_list_org, ecep.extra_party_org_name,ecep.address,ecep.extra_party_village_name,ecep.extra_party_ward_name,ecep.extra_party_town_name,
                             ecep.extra_party_taluka_name,ecep.extra_party_distt_name,ecep.extra_party_state_name,ecep.pincode,ecep.pet_email,ecep.pet_mobile,ecep.type,ecep.other_info_flag,ecep.passportno,ecep.extra_party_o_state_name,
                             ecep.panno,ecep.pet_phone,ecep.pet_fax,ecep.country,ecep.pet_nationality,ecep.pet_occu,ecep.extra_party_o_village_name,ecep.extra_party_o_ward_name,ecep.extra_party_o_town_name,
                             ecep.extra_party_o_taluka_name,ecep.extra_party_o_taluka_name,ecep.extra_party_o_distt_name,ecep.altaddress
                            FROM tbl_efiling_civil_extra_party ecep 
                            LEFT JOIN tbl_efiling_civil_extra_party parent_party  on parent_party.party_no = ecep.parentid
                            WHERE ecep.ref_m_efiling_nums_registration_id = " . $registration_id . " and parent_party.ref_m_efiling_nums_registration_id = " . $registration_id . "
                            AND ecep.parentid IS NOT NULL AND ecep.display = 'Y' 

                            UNION

                            SELECT parent_party.pet_name parent_name, ecep.id, ecep.ref_m_efiling_nums_registration_id, ecep.party_no, ecep.parentid, ecep.name,ecep.father_name,
                             ecep.extra_party_relation_name, ecep.extra_not_in_list_org, ecep.extra_party_org_name,ecep.address,ecep.extra_party_village_name,ecep.extra_party_ward_name,ecep.extra_party_town_name,
                             ecep.extra_party_taluka_name,ecep.extra_party_distt_name,ecep.extra_party_state_name,ecep.pincode,ecep.pet_email,ecep.pet_mobile,ecep.type,ecep.other_info_flag,ecep.passportno,ecep.extra_party_o_state_name,
                             ecep.panno,ecep.pet_phone,ecep.pet_fax,ecep.country,ecep.pet_nationality,ecep.pet_occu,ecep.extra_party_o_village_name,ecep.extra_party_o_ward_name,ecep.extra_party_o_town_name,
                             ecep.extra_party_o_taluka_name,ecep.extra_party_o_taluka_name,ecep.extra_party_o_distt_name,ecep.altaddress
                            FROM tbl_efiling_civil_extra_party ecep 
                            JOIN tbl_efiling_civil parent_party  on parent_party.ref_m_efiling_nums_registration_id = ecep.ref_m_efiling_nums_registration_id
                            WHERE ecep.ref_m_efiling_nums_registration_id = " . $registration_id . "
                            AND ecep.parentid = 0 AND ecep.type = 1 AND ecep.display = 'Y'

                            UNION

                            SELECT parent_party.res_name parent_name, ecep.id, ecep.ref_m_efiling_nums_registration_id, ecep.party_no, ecep.parentid, ecep.name,ecep.father_name,
                             ecep.extra_party_relation_name, ecep.extra_not_in_list_org, ecep.extra_party_org_name,ecep.address,ecep.extra_party_village_name,ecep.extra_party_ward_name,ecep.extra_party_town_name,
                             ecep.extra_party_taluka_name,ecep.extra_party_distt_name,ecep.extra_party_state_name,ecep.pincode,ecep.pet_email,ecep.pet_mobile,ecep.type,ecep.other_info_flag,ecep.passportno,ecep.extra_party_o_state_name,
                             ecep.panno,ecep.pet_phone,ecep.pet_fax,ecep.country,ecep.pet_nationality,ecep.pet_occu,ecep.extra_party_o_village_name,ecep.extra_party_o_ward_name,ecep.extra_party_o_town_name,
                             ecep.extra_party_o_taluka_name,ecep.extra_party_o_taluka_name,ecep.extra_party_o_distt_name,ecep.altaddress
                            FROM tbl_efiling_civil_extra_party ecep 
                            JOIN tbl_efiling_civil parent_party  on parent_party.ref_m_efiling_nums_registration_id = ecep.ref_m_efiling_nums_registration_id
                            WHERE ecep.ref_m_efiling_nums_registration_id = " . $registration_id . "
                            AND ecep.parentid = 0 AND ecep.type = 2 AND ecep.display = 'Y') a 
                            ORDER BY a.id, a.party_no, a.parentid ASC";

        $query = $this->db->query($sql_query);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function get_extra_party_for_payment($registration_id) {
        $this->db->SELECT('id,party_no,orgid,name');
        $this->db->FROM('tbl_efiling_civil_extra_party');
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $this->db->WHERE('type', 1);
        $this->db->ORDER_BY("id", "asc");
        $query = $this->db->get();
        return $query->result();
    }

    function approve_case($registration_id, $filing_type) {
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
            $checked_data = array('is_admin_checked' => TRUE);
            $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
            $this->db->UPDATE('tbl_efiled_docs', $checked_data);

//if ($this->db->affected_rows() > 0) {

            $next_stage = '';
            if ($filing_type == E_FILING_TYPE_NEW_CASE || $filing_type == E_FILING_TYPE_CDE) {
                $next_stage = Transfer_to_IB_Stage;
            } elseif ($filing_type == E_FILING_TYPE_MISC_DOCS) {
                $next_stage = Transfer_to_IB_Stage;
            } elseif ($filing_type == E_FILING_TYPE_DEFICIT_COURT_FEE) {
                $next_stage = Transfer_to_IB_Stage;
            } elseif ($filing_type == E_FILING_TYPE_IA) {
                $next_stage = Transfer_to_IB_Stage;
            }
            $insert_data = array(
                'registration_id' => $registration_id,
                'stage_id' => $next_stage,
                'activated_on' => date('Y-m-d H:i:s'),
                'is_active' => TRUE,
                'activated_by' => $_SESSION['login']['id'],
                'activated_by_ip' => getClientIP()
            );
            $this->db->INSERT('tbl_efiling_case_status', $insert_data);
            if ($this->db->insert_id()) {

                $initial_defect_exits = FALSE;
                $initial_defects_update_status = FALSE;

                $this->db->SELECT('max(initial_defects_id) max_id');
                $this->db->FROM('tbl_initial_defects');
                $this->db->WHERE('registration_id', $registration_id);
                $this->db->WHERE('is_defect_cured', TRUE);
                $this->db->WHERE('is_approved', FALSE);
                $query = $this->db->get();
                $query_result = $query->result();
                $initial_defect_max_id = $query_result[0]->max_id;

                if ($initial_defect_max_id != '' && $initial_defect_max_id > 0) {

                    $initial_defect_exits = TRUE;

                    $update_defect_data = array(
                        'is_approved' => TRUE,
                        'approve_date' => date('Y-m-d H:i:s'),
                        'approved_by' => $_SESSION['login']['id']
                    );
                    $this->db->WHERE('registration_id', $registration_id);
                    $this->db->WHERE('is_defect_cured', TRUE);
                    $this->db->WHERE('is_approved', FALSE);
                    $this->db->WHERE('initial_defects_id', $initial_defect_max_id);
                    $this->db->UPDATE('tbl_initial_defects', $update_defect_data);
                    if ($this->db->affected_rows() > 0) {
                        $initial_defects_update_status = TRUE;
                    }
                }

                $this->db->SELECT('count(id) cnt');
                $this->db->FROM('tbl_court_fee_payment');
                $this->db->WHERE('registration_id', $registration_id);
                $this->db->WHERE('is_active', TRUE);
                $this->db->WHERE('payment_verified_date', NULL);
                $this->db->WHERE('payment_verified_by', NULL);
                $query = $this->db->get();

                $query_result = $query->result();
                $crt_fee_payment_cnt = $query_result[0]->cnt;

                $crt_fee_payment_exits = FALSE;
                $crt_fee_payment_update_status = FALSE;

                if ($crt_fee_payment_cnt > 0) {
                    $crt_fee_payment_exits = TRUE;
                    $fee_data = array(
                        'payment_verified_date' => date('Y-m-d H:i:s'),
                        'payment_verified_by' => $_SESSION['login']['id']
                    );
                    $this->db->WHERE('registration_id', $registration_id);
                    $this->db->WHERE('payment_verified_by', NULL);
                    $this->db->WHERE('payment_verified_date', NULL);
                    $this->db->WHERE('is_active', TRUE);
                    $this->db->UPDATE('tbl_court_fee_payment', $fee_data);
                    if ($this->db->affected_rows() > 0) {
                        $crt_fee_payment_update_status = TRUE;
                    }
                }
                if ($crt_fee_payment_exits && $crt_fee_payment_update_status && $initial_defect_exits && $initial_defects_update_status) {
                    $this->db->trans_complete();
                } elseif (!$crt_fee_payment_exits && !$crt_fee_payment_update_status && $initial_defect_exits && $initial_defects_update_status) {
                    $this->db->trans_complete();
                } elseif ($crt_fee_payment_exits && $crt_fee_payment_update_status) {
                    $this->db->trans_complete();
                } elseif (!$crt_fee_payment_exits && !$crt_fee_payment_update_status && !$initial_defect_exits && !$initial_defects_update_status) {
                    $this->db->trans_complete();
                }
            }
// }
        }
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function disapprove_case($registration_id, $remark, $crt_fee_status, $def_crt_fee = NULL) {
        if ($crt_fee_status == 3) {
            $remark .= "<br/><br/><br/>Court Fee Deficited By : " . $def_crt_fee . " Rs.";
        }
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
            $checked_data = array('is_admin_checked' => TRUE);
            $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
            $this->db->UPDATE('tbl_efiled_docs', $checked_data);

//if ($this->db->affected_rows() > 0) {

            $insert_data = array(
                'registration_id' => $registration_id,
                'stage_id' => Initial_Defected_Stage,
                'activated_on' => date('Y-m-d H:i:s'),
                'is_active' => TRUE,
                'activated_by' => $_SESSION['login']['id'],
                'activated_by_ip' => getClientIP()
            );
            $this->db->INSERT('tbl_efiling_case_status', $insert_data);

            if ($this->db->insert_id()) {

                $initial_defect_exits = FALSE;
                $initial_defects_update_status = FALSE;

                $this->db->SELECT('max(initial_defects_id) max_id');
                $this->db->FROM('tbl_initial_defects');
                $this->db->WHERE('registration_id', $registration_id);
                $this->db->WHERE('is_defect_cured', TRUE);
                $this->db->WHERE('is_approved', FALSE);
                $query = $this->db->get();
                $query_result = $query->result();
                $initial_defect_max_id = $query_result[0]->max_id;

                if ($initial_defect_max_id != '' && $initial_defect_max_id > 0) {

                    $initial_defect_exits = TRUE;

                    $update_defect_data = array(
                        'is_approved' => TRUE,
                        'still_defective' => TRUE,
                        'approve_date' => date('Y-m-d H:i:s'),
                        'approved_by' => $_SESSION['login']['id']
                    );
                    $this->db->WHERE('registration_id', $registration_id);
                    $this->db->WHERE('is_defect_cured', TRUE);
                    $this->db->WHERE('is_approved', FALSE);
                    $this->db->WHERE('initial_defects_id', $initial_defect_max_id);
                    $this->db->UPDATE('tbl_initial_defects', $update_defect_data);
                    if ($this->db->affected_rows() > 0) {
                        $initial_defects_update_status = TRUE;
                    }
                }

                $initial_defects_insert_status = FALSE;
                if (($initial_defect_exits && $initial_defects_update_status) || (!$initial_defect_exits && !$initial_defects_update_status)) {

                    $insert = array(
                        'registration_id' => $registration_id,
                        'defect_remark' => $remark,
                        'defect_date' => date('Y-m-d H:i:s'),
                        'is_active' => TRUE,
                        'updated_by' => $_SESSION['login']['id'],
                        'ip_address' => getClientIP()
                    );
                    $this->db->INSERT('tbl_initial_defects', $insert);

                    if ($this->db->insert_id()) {
                        $initial_defects_insert_status = TRUE;
                    }
                }

                $this->db->SELECT('max(id) max_id');
                $this->db->FROM('tbl_court_fee_payment');
                $this->db->WHERE('registration_id', $registration_id);
                $this->db->WHERE('is_active', TRUE);
                $query = $this->db->get();
                $query_result = $query->result();
                $crt_fee_payment_max_id = $query_result[0]->max_id;

                $crt_fee_payment_exits = FALSE;
                $crt_fee_payment_update_status = FALSE;
                if ($crt_fee_payment_max_id != '' && $crt_fee_payment_max_id > 0) {

                    $crt_fee_payment_exits = TRUE;

                    if ($crt_fee_status == 1 || $crt_fee_status == '') {
                        $fee_data = array(
                            'payment_verified_date' => date('Y-m-d H:i:s'),
                            'payment_verified_by' => $_SESSION['login']['id'],
                            'is_payment_defective' => FALSE,
                            'is_payment_defecit' => FALSE,
                            'defecit_court_fee' => NULL
                        );
                        $this->db->WHERE('registration_id', $registration_id);
                        $this->db->WHERE('id', $crt_fee_payment_max_id);
                        $this->db->WHERE('is_active', TRUE);
                        $this->db->UPDATE('tbl_court_fee_payment', $fee_data);
                        if ($this->db->affected_rows() > 0) {
                            $crt_fee_payment_update_status = TRUE;
                        }
                        $crt_fee_payment_update_status = TRUE;
                    } elseif ($crt_fee_status == 2) {

                        if (!(bool) $_SESSION['estab_details']['enable_payment_gateway']) {
                            $fee_data = array(
                                'is_payment_defective' => TRUE,
                                'is_payment_defecit' => FALSE,
                                'defecit_court_fee' => NULL,
                                'payment_verified_date' => date('Y-m-d H:i:s'),
                                'payment_verified_by' => $_SESSION['login']['id']
                            );
                            $this->db->WHERE('registration_id', $registration_id);

                            $this->db->WHERE('id', $crt_fee_payment_max_id);
                            $this->db->WHERE('is_active', TRUE);
                            $this->db->UPDATE('tbl_court_fee_payment', $fee_data);
                            if ($this->db->affected_rows() > 0) {
                                $crt_fee_payment_update_status = TRUE;
                            }
                        }
                    } elseif ($crt_fee_status == 3) {

                        $fee_data = array(
                            'is_payment_defecit' => TRUE,
                            'defecit_court_fee' => $def_crt_fee,
                            'payment_verified_date' => date('Y-m-d H:i:s'),
                            'payment_verified_by' => $_SESSION['login']['id']
                        );
                        $this->db->WHERE('registration_id', $registration_id);
                        $this->db->WHERE('is_active', TRUE);

                        $this->db->WHERE('id', $crt_fee_payment_max_id);
                        $this->db->UPDATE('tbl_court_fee_payment', $fee_data);

                        if ($this->db->affected_rows() > 0) {
                            $crt_fee_payment_update_status = TRUE;
                        }
                    }
                }
                if ($crt_fee_payment_exits && $crt_fee_payment_update_status && $initial_defect_exits && $initial_defects_update_status && $initial_defects_insert_status) {
                    $this->db->trans_complete();
                } elseif (!$crt_fee_payment_exits && !$crt_fee_payment_update_status && $initial_defect_exits && $initial_defects_update_status && $initial_defects_insert_status) {
                    $this->db->trans_complete();
                } elseif ($crt_fee_payment_exits && $crt_fee_payment_update_status && !$initial_defect_exits && !$initial_defects_update_status && $initial_defects_insert_status) {
                    $this->db->trans_complete();
                } elseif (!$crt_fee_payment_exits && !$crt_fee_payment_update_status && !$initial_defect_exits && !$initial_defects_update_status && $initial_defects_insert_status) {
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

    function fetch_e_party_details($id) {
        $this->db->SELECT('*');
        $this->db->FROM('tbl_efiling_civil_extra_party');
        $this->db->WHERE('id', $id);
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $this->session->userdata['efiling_details']['registration_id']);
        $query = $this->db->get();
        return $query->result_array();
    }

    function update_cis_master_values($registration_id, $data) {
        $this->db->WHERE('registration_id', $registration_id);
        $query = $this->db->get('tbl_cis_masters_values');
        if ($query->num_rows() > 0) {
            $this->db->WHERE('registration_id', $registration_id);
            $result = $this->db->UPDATE('tbl_cis_masters_values', $data);
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $cis_masters_values = array(
                'registration_id' => $registration_id,
                'pet_org_name' => $data['pet_org_name'],
                'filcase_type_name' => $data['filcase_type_name'],
                'pet_caste_name' => $data['pet_caste_name'],
                'pet_state_name' => $data['pet_state_name'],
                'pet_distt_name' => $data['pet_distt_name'],
                'pet_taluka_name' => $data['pet_taluka_name'],
                'pet_town_name' => $data['pet_town_name'],
                'pet_ward_name' => $data['pet_ward_name'],
                'pet_village_name' => $data['pet_village_name'],
                'pet_ps_name' => $data['pet_ps_name'],
                'police_st_state_name' => $data['police_state_name'],
                'police_st_dist_name' => $data['police_distt_name'],
                'police_station_name' => $data['police_station_name'],
                'fir_type_name' => $data['fir_type_name'],
                'investigation_agency' => $data['investigation_agency'],
                'trials_name' => $data['trials_name'],
                'ia_case_type_name' => $data['ia_case_type_name'],
                'ia_act_name' => $data['ia_act_name'],
                'ia_classification_name' => $data['ia_classification_name'],
                'ia_prayer_name' => $data['ia_prayer_name'],
                'ia_purpose_name' => $data['ia_purpose_name'],
                'bench_name' => $data['bench_name'],
                'high_court_case_type' => $data['high_court_case_type'],
                'pet_relation' => $data['pet_relation']
            );
            $result = $this->db->INSERT('tbl_cis_masters_values', $cis_masters_values);
            if ($this->db->insert_id()) {
                return true;
            } else {
                return false;
            }
        }
    }

//connect remote db
    function get_efiling_for_details_on_reg_id($registration_id) {
        $this->db->SELECT("efiling_for_type_id,efiling_for_id");
        $this->db->FROM('tbl_efiling_nums');
        $this->db->WHERE('registration_id', $registration_id);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {

            $result = $query->result();
            return $result;
        } else {
            return false;
        }
    }

    function get_hc_est_code($entry_for_type_id, $entry_for_id) {

        if ($entry_for_type_id == ENTRY_TYPE_FOR_HIGHCOURT) {
            $this->db->SELECT("*");
            $this->db->FROM('m_tbl_high_courts');
            $this->db->WHERE('id', $entry_for_id);
            $query = $this->db->get();
            if ($query->num_rows() >= 1) {

                $result = $query->result();

                $data = array('est_code' => $result[0]->hc_code);

                $this->session->set_userdata(array('national_code' => $data));

                return $result;
            } else {
                return false;
            }
        } elseif ($entry_for_type_id == ENTRY_TYPE_FOR_ESTABLISHMENT) {
            $this->db->SELECT("*");
            $this->db->FROM('m_tbl_establishments');
            $this->db->WHERE('id', $entry_for_id);
            $query = $this->db->get();
            if ($query->num_rows() >= 1) {

                $result = $query->result();

                $data = array('est_code' => $result[0]->est_code);

                $this->session->set_userdata(array('national_code' => $data));

                return $result;
            } else {
                return false;
            }
        } else {
            return FALSE;
        }
    }

    function get_efiled_by_user($user_id) {
        $this->db->SELECT('id,ref_m_usertype_id,first_name, last_name, moblie_number, emailid');
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

    function get_receipts_doc($pdf_id) {
        $this->db->SELECT('receipt_name,reciept_uploaded_path');
        $this->db->FROM('tbl_court_fee_payment');
        $this->db->WHERE('id', $pdf_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;
        } else {
            return false;
        }
    }

    function update_deficitcourtfee($registration_id, $def_crt_fee) {
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
                'stage_id' => DEFICIT_COURT_FEE,
                'activated_on' => date('Y-m-d H:i:s'),
                'is_active' => TRUE,
                'activated_by' => $_SESSION['login']['id'],
                'activated_by_ip' => getClientIP()
            );
            $this->db->INSERT('tbl_efiling_case_status', $insert_data);

            if ($this->db->insert_id()) {

                $this->db->SELECT('max(id) max_id');
                $this->db->FROM('tbl_court_fee_payment');
                $this->db->WHERE('registration_id', $registration_id);
                $this->db->WHERE('is_active', TRUE);

                $query = $this->db->get();
                $query_result = $query->result();
                $crt_fee_payment_max_id = $query_result[0]->max_id;

                $crt_fee_payment_exits = FALSE;
                $crt_fee_payment_update_status = FALSE;
                if ($crt_fee_payment_max_id != '' && $crt_fee_payment_max_id > 0) {

                    $crt_fee_payment_exits = TRUE;

                    $fee_data = array(
                        'is_payment_defective' => FALSE,
                        'is_payment_defecit' => TRUE,
                        'defecit_court_fee' => $def_crt_fee,
                        'payment_verified_date' => date('Y-m-d H:i:s'),
                        'payment_verified_by' => $_SESSION['login']['id']
                    );
                    $this->db->WHERE('registration_id', $registration_id);
                    $this->db->WHERE('is_active', TRUE);

                    $this->db->WHERE('id', $crt_fee_payment_max_id);
                    $this->db->UPDATE('tbl_court_fee_payment', $fee_data);

                    if ($this->db->affected_rows() > 0) {
                        $crt_fee_payment_update_status = TRUE;
                    }
                }

                $initial_defect_exits = FALSE;
                $initial_defects_update_status = FALSE;

                $this->db->SELECT('max(initial_defects_id) max_id');
                $this->db->FROM('tbl_initial_defects');
                $this->db->WHERE('registration_id', $registration_id);
                $this->db->WHERE('is_defect_cured', TRUE);
                $this->db->WHERE('is_approved', FALSE);
                $query = $this->db->get();
                $query_result = $query->result();
                $initial_defect_max_id = $query_result[0]->max_id;

                if ($initial_defect_max_id != '' && $initial_defect_max_id > 0) {

                    $initial_defect_exits = TRUE;

                    $update_defect_data = array(
                        'is_approved' => TRUE,
                        'still_defective' => TRUE,
                        'approve_date' => date('Y-m-d H:i:s'),
                        'approved_by' => $_SESSION['login']['id']
                    );
                    $this->db->WHERE('registration_id', $registration_id);
                    $this->db->WHERE('is_defect_cured', TRUE);
                    $this->db->WHERE('is_approved', FALSE);
                    $this->db->WHERE('initial_defects_id', $initial_defect_max_id);
                    $this->db->UPDATE('tbl_initial_defects', $update_defect_data);
                    if ($this->db->affected_rows() > 0) {
                        $initial_defects_update_status = TRUE;
                    }
                }

                $initial_defects_insert_status = FALSE;
                if (($initial_defect_exits && $initial_defects_update_status) || (!$initial_defect_exits && !$initial_defects_update_status)) {

                    $insert = array(
                        'registration_id' => $registration_id,
                        'defect_remark' => "Deficit fee has to be paid Rupees :" . $def_crt_fee,
                        'defect_date' => date('Y-m-d H:i:s'),
                        'is_active' => TRUE,
                        'updated_by' => $_SESSION['login']['id'],
                        'ip_address' => getClientIP()
                    );
                    $this->db->INSERT('tbl_initial_defects', $insert);

                    if ($this->db->insert_id()) {
                        $initial_defects_insert_status = TRUE;
                    }
                }

                if ($crt_fee_payment_exits && $crt_fee_payment_update_status && $initial_defect_exits && $initial_defects_update_status && $initial_defects_insert_status) {
                    $this->db->trans_complete();
                } elseif (!$crt_fee_payment_exits && !$crt_fee_payment_update_status && $initial_defect_exits && $initial_defects_update_status && $initial_defects_insert_status) {
                    $this->db->trans_complete();
                } elseif ($crt_fee_payment_exits && $crt_fee_payment_update_status && !$initial_defect_exits && !$initial_defects_update_status && $initial_defects_insert_status) {
                    $this->db->trans_complete();
                } elseif (!$crt_fee_payment_exits && !$crt_fee_payment_update_status && !$initial_defect_exits && !$initial_defects_update_status && $initial_defects_insert_status) {
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

    function get_pet_n_res_count_civil($registration_id) {

        $this->db->SELECT('pet_extracount,res_extracount');
        $this->db->FROM('tbl_efiling_civil');
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $count = $query->result_array();
            return $count;
        } else {
            return 0;
        }
    }

    function get_efiling_for_details($efiling_for_type_id, $efiling_for_id) {
        if ($efiling_for_type_id == E_FILING_FOR_ESTABLISHMENT) {
            $this->db->SELECT("id,estname,est_code, ip_details, sms_gateway_url, establishment_email_id,
                    email_user_name, email_pwd, mail_host, enable_payment_gateway, is_charging_printing_cost, printing_cost,payment_gateway_params,pg_request_function,pg_response_function,sms_credentials,state_code");
            $this->db->FROM('m_tbl_establishments');
            $this->db->WHERE('id', $efiling_for_id);
            $this->db->WHERE('display', 'Y');
            $this->db->ORDER_BY("estname", "asc");
        } elseif ($efiling_for_type_id == E_FILING_FOR_HIGHCOURT) {
            $this->db->SELECT("id,hc_name estname,hc_code est_code, ip_details, sms_gateway_url, establishment_email_id,
                    email_user_name, email_pwd, mail_host, enable_payment_gateway, is_charging_printing_cost, printing_cost,payment_gateway_params,pg_request_function,pg_response_function,sms_credentials,state_code");
            $this->db->FROM('m_tbl_high_courts');
            $this->db->WHERE('id', $efiling_for_id);
            $this->db->WHERE('is_active', TRUE);
            $this->db->ORDER_BY("hc_name", "asc");
        }
        $query = $this->db->get();
        return $query->result();
    }

    function get_doc_signature_status($reg_id) {
        $sql = " SELECT * from esign_logs WHERE ( ref_registration_id = '" . $reg_id . "' AND is_data_valid = 'TRUE' AND ( type = 1 or type = 2 ) )";
        $query = $this->db->query($sql);
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE) {

            $sql2 = " SELECT * from esign_logs WHERE ( ref_registration_id = '" . $reg_id . "' AND is_data_valid = 'TRUE' AND ( type = 3 ) )";
            $query2 = $this->db->query($sql2);
        }
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE) {
            if (($query->num_rows() == 1) && ($query2->num_rows() == 1)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else if ($_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON) {
            if (($query->num_rows() == 1)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function get_esigned_file_size($txn_id, $reg_id) {

        $sql = "SELECT * FROM esign_logs WHERE ref_registration_id = '" . $reg_id . "' AND request_txn_num ='" . $txn_id . "'";
        $query = $this->db->query($sql);
        if (($query->num_rows())) {
            $result = $query->result();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_esign_doc_pet($reg_id) {

        $sql = "SELECT * from esign_logs WHERE ( ref_registration_id = '" . $reg_id . "' AND is_data_valid = 'TRUE' AND (  type = " . ESIGNED_DOCS_BY_PET .
                "or type = " . ESIGNED_DOCS_BY_ADV2 . "  ) )";
        $query = $this->db->query($sql);
        if (($query->num_rows())) {
            $result = $query->result();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_esign_doc_adv($reg_id) {

        $sql = " SELECT * from esign_logs WHERE ( ref_registration_id = '" . $reg_id . "' AND is_data_valid = 'TRUE' AND (  type = " . ESIGNED_DOCS_BY_ADV3 . ") )";
        $query = $this->db->query($sql);
        if (($query->num_rows())) {
            $result = $query->result();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_everified_doc($reg_id) {

        $sql = " SELECT * from esign_logs WHERE ( ref_registration_id = '" . $reg_id . "' AND is_data_valid = 'TRUE' AND (  type = " . EVERIFIED_DOCS_BY_MOB_OTP . ") )";
        $query = $this->db->query($sql);
        if (($query->num_rows())) {
            $result = $query->result();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_esign_failure_count($reg_id) {
        $sql = "SELECT count(errcode) errcode_count FROM esign_logs WHERE ref_registration_id = '" . $reg_id . "' AND is_data_valid = 'FALSE' AND errcode !='NA'
                    AND (  type = " . ESIGNED_DOCS_BY_PET . " OR type = " . ESIGNED_DOCS_BY_ADV2 . " OR type = " . ESIGNED_DOCS_BY_ADV3 . "  ) GROUP BY errcode";
        $query = $this->db->query($sql);
        if (($query->num_rows())) {
            $result = $query->result();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_state_max_establishment_id($state_id) {

        $this->db->SELECT('max(estab.id) max_id');
        $this->db->FROM('m_tbl_establishments estab');
        $this->db->JOIN('m_tbl_districts dist', 'estab.ref_m_tbl_districts_id=dist.id');
        $this->db->WHERE('ref_m_tbl_states_id', $state_id);
        $this->db->WHERE('dist.display', 'Y');
        $this->db->WHERE('estab.display', 'Y');
        $this->db->ORDER_BY('estab.id');
        $this->db->LIMIT(1);
        $query = $this->db->get();
        $query_result = $query->result();
        $estab_max_id = $query_result[0]->max_id;
        return $estab_max_id;
    }

    function get_signed_details($registration_id) {
        $this->db->SELECT('doc_signed_used,matter_type,macp');
        $this->db->FROM('tbl_efiling_civil');
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_org_name($registration_id) {
        $this->db->SELECT("pet_org_name");
        $this->db->FROM('tbl_cis_masters_values');
        $this->db->WHERE('registration_id', $registration_id);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            $result = $query->result_array();
            return $result;
        } else {
            return false;
        }
    }

    function get_national_code($reg_id) {

        if (!is_numeric($reg_id)) {
            return false;
        }

        $sql = " select *
                 from
                 tbl_efiling_nums en
                 left join m_tbl_establishments est on est.id = en.efiling_for_id and en.efiling_for_type_id = 2
                 left join m_tbl_high_courts hc on hc.id = en.efiling_for_id and en.efiling_for_type_id = 1 and hc.is_active = 'TRUE'
                 where en.registration_id = '" . $reg_id . "' ";

        $query = $this->db->query($sql);
        if ($query->num_rows() == 1) {
            $res = $query->result_array();


            if ($res[0]['efiling_for_type_id'] == ENTRY_TYPE_FOR_HIGHCOURT) {
                if ($res[0]['hc_code'] != NULL && $res[0]['hc_code'] != '') {
                    return $res[0]['hc_code'];
                } else {
                    return false;
                }
            } else if ($res[0]['efiling_for_type_id'] == ENTRY_TYPE_FOR_ESTABLISHMENT) {
                if ($res[0]['est_code'] != NULL && $res[0]['est_code'] != '') {
                    return $res[0]['est_code'];
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function get_max_item_number($id, $registration_id) {

        $sql = "select count(*) as count from tbl_efiling_mvc where updated_by_user_id = '" . $id . "' AND ref_m_efiling_nums_registration_id = '" . $registration_id . "' ";

        $query = $this->db->query($sql);

        $res = $query->result_array();

        return $res[0]['count'] + 1;
    }

    function mvc_add_data($data) {
        $this->db->insert('tbl_efiling_mvc', $data);
        if ($this->db->insert_id()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_mvc_data($id) {
        if (is_numeric($id)) {
            $sql = "select * from tbl_efiling_mvc where id = " . $id;
            $query = $this->db->query($sql);
            $res = $query->result_array();
            return $res;
        } else {
            return NULL;
        }
    }

    function get_mvc_total_applications($id) {

        $sql = "select * from tbl_efiling_mvc where ref_m_efiling_nums_registration_id = '" . $id . "' ORDER BY id ASC";
        $query = $this->db->query($sql);
        $res = $query->result();
        return $res;
    }

    function mvc_update_data($data, $id) {
        $this->db->WHERE('id', $id);
        $this->db->UPDATE('tbl_efiling_mvc', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_application_data($table_id, $login_id, $efiling_for_type_id) {
        if ($efiling_for_type_id == E_FILING_FOR_HIGHCOURT) {

            $this->db->SELECT('mvc.*,hc.hc_name estab_name');
            $this->db->FROM('tbl_efiling_mvc mvc');
            $this->db->JOIN('tbl_efiling_nums en', 'en.registration_id = mvc.ref_m_efiling_nums_registration_id', 'left');
            $this->db->JOIN('m_tbl_high_courts hc', 'hc.id = en.efiling_for_id', 'left');
        } elseif ($efiling_for_type_id == E_FILING_FOR_ESTABLISHMENT) {

            $this->db->SELECT('mvc.*,estab.estname estab_name');
            $this->db->FROM('tbl_efiling_mvc mvc');
            $this->db->JOIN('tbl_efiling_nums en', 'en.registration_id = mvc.ref_m_efiling_nums_registration_id', 'left');
            $this->db->JOIN('m_tbl_establishments estab', 'estab.id = en.efiling_for_id', 'left');
        }
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON) {
            $this->db->WHERE('mvc.updated_by_user_id', $login_id);
        }


        $this->db->WHERE('mvc.id', $table_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $res = $query->result_array();

            return $res;
        } else {
            return false;
        }
    }

    function get_petitioner_data($registration_id) {
        if (is_numeric($registration_id)) {

            $sql = "select * from tbl_efiling_civil where ref_m_efiling_nums_registration_id = " . $registration_id;

            $query = $this->db->query($sql);

            $res = $query->result_array();

            return $res;
        } else {
            return false;
        }
    }

    function register_adv_bar_no($login_id, $hc_id, $court_type) {

        $data = array('login_id' => $login_id, 'high_court_id' => $hc_id, 'efiling_for_type_id' => $court_type, 'add_date' => date('Y-m-d H:i:s'), 'is_register' => 'N');

        $this->db->SELECT('login_id,high_court_id');
        $this->db->FROM('tbl_advocate_register');
        $this->db->WHERE('login_id', $login_id);
        $this->db->WHERE('high_court_id', $hc_id);
        $this->db->WHERE('efiling_for_type_id', $court_type);
        $this->db->WHERE('is_active', TRUE);
        $query = $this->db->get();
        $row = $query->result_array();
        if ($query->num_rows() == 1) {


            return TRUE;
        } else {
            $this->db->insert('tbl_advocate_register', $data);
            return TRUE;
        }
    }

    function save_main_matter_data($registration_id, $data) {
        $this->db->trans_start();
        $this->db->WHERE('registration_id', $registration_id);
        $result = $this->db->UPDATE('tbl_cis_masters_values', $data);
        if ($this->db->affected_rows() > 0) {
            $main_matter_cino = array('main_matter_cino' => $data['main_matter_cnr_num']);
            $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
            $result = $this->db->UPDATE('tbl_efiling_civil', $main_matter_cino);
            if ($this->db->affected_rows() > 0) {
                $this->db->trans_complete();
            }
        }
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function get_main_matter_details($registration_id) {
        $this->db->SELECT('registration_id,main_matter_cnr_num,main_matter_case_type_name ,main_matter_case_num,main_matter_case_year,main_matter_pet_name,main_matter_res_name,main_matter_court_name');
        $this->db->FROM('tbl_cis_masters_values');
        $this->db->WHERE('registration_id', $registration_id);
        $this->db->ORDER_BY("id", "asc");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        } else {
            return FALSE;
        }
    }

    function delete_main_matter_data($registration_id) {
        $data = array(
            'main_matter_cnr_num' => NULL,
            'main_matter_case_num' => NULL,
            'main_matter_case_year' => NULL,
            'main_matter_case_type_name' => NULL,
            'main_matter_pet_name' => NULL,
            'main_matter_res_name' => NULL
        );
        $this->db->trans_start();
        $this->db->WHERE('registration_id', $registration_id);
        $this->db->UPDATE('tbl_cis_masters_values', $data);
        if ($this->db->affected_rows() > 0) {
            $main_matter_cino = array('main_matter_cino' => NULL);
            $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
            $result = $this->db->UPDATE('tbl_efiling_civil', $main_matter_cino);
            if ($this->db->affected_rows() > 0) {
                $this->db->trans_complete();
            }
        }
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function add_police_station($registration_id, $data, $cis_masters_values) {
        $this->db->trans_start();
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $this->db->UPDATE('tbl_efiling_civil', $data);
        if ($this->db->affected_rows() > 0) {

            if ($cis_masters_values != NULL) {
                $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                if ($success) {
                    $this->db->trans_complete();
                }
            } else {
                $this->db->trans_complete();
            }
        }

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function get_efiling_civil_master_value($registration_id) {
        $this->db->SELECT('*');
        $this->db->FROM('tbl_cis_masters_values');
        $this->db->WHERE('registration_id', $registration_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_IA_No($registration_id) {
        $this->db->SELECT('count(id)');
        $this->db->FROM('tbl_misc_doc_filing as en');
        $this->db->where('efiling_case_reg_id', $registration_id);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result[0]->count;
        } else {
            return false;
        }
    }

    function get_allocated_to_details($registration_id) {
        $this->db->SELECT("tbl_efiling_nums.*,concat(users.first_name,' ',users.last_name) as admin_name,(CASE WHEN tbl_efiling_nums.efiling_for_type_id =" . E_FILING_FOR_ESTABLISHMENT . " THEN (SELECT concat(estname,', ',dist_name,', ',state)  FROM m_tbl_establishments est
                LEFT JOIN m_tbl_state st on est.state_code = st.state_id
                LEFT JOIN m_tbl_districts dist on est.ref_m_tbl_districts_id = dist.id
                WHERE est.id = tbl_efiling_nums.efiling_for_id ) 
                ELSE (select concat(hc_name,' High Court') FROM m_tbl_high_courts hc
                WHERE hc.id = tbl_efiling_nums.efiling_for_id) END ) 
                as efiling_for_name");
        $this->db->FROM('tbl_efiling_nums');
        $this->db->JOIN(dynamic_users_table, 'users.id = tbl_efiling_nums.allocated_to', 'LEFT');
        $this->db->WHERE('tbl_efiling_nums.registration_id', $registration_id);
        $this->db->WHERE('tbl_efiling_nums.is_active', TRUE);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            $result = $query->result_array();

            return $result;
        } else {
            return false;
        }
    }

    function get_submitted_on($registration_id) {
        $array = array(Initial_Approaval_Pending_Stage, I_B_Defects_Cured_Stage, Initial_Defects_Cured_Stage);
        $this->db->SELECT("*");
        $this->db->FROM('tbl_efiling_case_status');
        $this->db->WHERE('tbl_efiling_case_status.registration_id', $registration_id);
        $this->db->WHERE_IN('tbl_efiling_case_status.stage_id', $array);
        $this->db->ORDER_BY('status_id', 'DESC');
        $this->db->limit(1, 0);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            $result = $query->result_array();

            return $result;
        } else {
            return false;
        }
    }

    function add_subordinate_court_info($registration_id, $appllate, $trial, $cis_masters_values, $trial_cis_masters_values) {

        $this->db->trans_start();

        $update_date = array('is_deleted' => TRUE);
        $this->db->WHERE('registration_id', $registration_id);
        $this->db->WHERE('is_deleted', FALSE);
        $this->db->UPDATE('etrial_lower_court', $update_date);
        if (!empty($appllate)) {
            $this->db->INSERT('etrial_lower_court', $appllate);
        }if (!empty($trial)) {
            $this->db->INSERT('etrial_lower_court', $trial);
        }

        if ($this->db->insert_id()) {
            if (!empty($cis_masters_values)) {
                $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                if ($success) {
                    $this->db->trans_complete();
                }
            } else {
                $this->db->trans_complete();
            }

            if (!empty($trial_cis_masters_values)) {
                $success = $this->update_cis_master_values($registration_id, $trial_cis_masters_values);
                if ($success) {
                    $this->db->trans_complete();
                }
            } else {
                $this->db->trans_complete();
            }
        }

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function add_high_court_info($registration_id, $data, $cis_masters_values) {

        $this->db->trans_start();

        $update_date = array('is_deleted' => TRUE);
        $this->db->WHERE('registration_id', $registration_id);
        $this->db->WHERE('is_deleted', FALSE);
        $this->db->WHERE_NOT_IN('id', $ids);
        $this->db->UPDATE('etrial_lower_court', $update_date);

        $this->db->INSERT('etrial_lower_court', $data);
        if ($this->db->insert_id()) {
            if (!empty($cis_masters_values)) {
                $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                if ($success) {
                    $this->db->trans_complete();
                }
            } else {
                $this->db->trans_complete();
            }
        }

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_subordinate_court_info($id, $registration_id, $data, $cis_masters_values) {
        $this->db->trans_start();

        $this->db->WHERE('registration_id', $registration_id);
        $this->db->WHERE('id', $id);
        $this->db->UPDATE('etrial_lower_court', $data);

        if ($this->db->affected_rows() > 0) {
            if (!empty($cis_masters_values)) {
                $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                if ($success) {
                    $this->db->trans_complete();
                }
            } else {
                $this->db->trans_complete();
            }
        }

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function get_lower_trial_court_details($registration_id, $form_type, $court_type) {
        $this->db->SELECT('*');
        $this->db->FROM('etrial_lower_court');
        $this->db->WHERE('registration_id', $registration_id);
        $this->db->WHERE('sub_qj_high', $form_type);
        $this->db->WHERE('lower_trial', $court_type);
        $this->db->WHERE('is_deleted', FALSE);
        $this->db->ORDER_BY("id", "asc");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result;
        } else {
            return false;
        }
    }

    function get_hc_details_subordinate_court($registration_id, $form_type, $court_type) {
        $this->db->SELECT('elc.*,cis_mater.high_court_case_type');
        $this->db->FROM('etrial_lower_court elc');
        $this->db->JOIN('tbl_cis_masters_values cis_mater', 'elc.registration_id = cis_mater.registration_id', 'LEFT');
        $this->db->WHERE('elc.registration_id', $registration_id);
        $this->db->WHERE('elc.sub_qj_high', $form_type);
        $this->db->WHERE('elc.lower_trial', $court_type);
        $this->db->WHERE('elc.is_deleted', FALSE);
        $this->db->ORDER_BY("elc.id", "asc");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result;
        } else {
            return false;
        }
    }

    function get_sub_qj_hc_court_details($registration_id) {

        $this->db->SELECT('*');
        $this->db->FROM('etrial_lower_court');
        $this->db->WHERE('registration_id', $registration_id);
        $this->db->WHERE('is_deleted', FALSE);
        $this->db->ORDER_BY("id", "asc");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result;
        } else {
            return false;
        }
    }

    function update_organisation_value($registration_id, $data, $cis_masters_values) {
        $this->db->trans_start();
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $this->db->UPDATE('tbl_efiling_civil', $data);
        if ($this->db->affected_rows() > 0) {

            if ($cis_masters_values != NULL) {
                $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                if ($success) {
                    $this->db->trans_complete();
                }
            } else {
                $this->db->trans_complete();
            }
        }
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_extra_party_org_value($registration_id, $id, $type, $data) {
        $this->db->trans_start();
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $this->db->WHERE('id', $id);
        $this->db->WHERE('type', $type);
        $this->db->UPDATE('tbl_efiling_civil_extra_party', $data);
        if ($this->db->affected_rows() > 0) {
            $this->db->trans_complete();
        }
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function new_case_org_details($registration_id) {
         $this->db->SELECT('ec.orgid,ec.not_in_list_org,ec.res_not_in_list_org,ec.resorgid,extra_party.name,extra_party.type,extra_party.orgid extra_party_orgid,extra_party.extra_not_in_list_org');
        $this->db->FROM('tbl_efiling_civil ec');
        $this->db->JOIN('tbl_efiling_civil_extra_party extra_party', 'ec.ref_m_efiling_nums_registration_id = extra_party.ref_m_efiling_nums_registration_id', 'LEFT');
        $this->db->WHERE('ec.ref_m_efiling_nums_registration_id', $registration_id);
        $this->db->WHERE('extra_party.display', 'Y');
        $this->db->ORDER_BY('type', 'ASC');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result;
        } else {
            return FALSE;
        }
      }

    function add_case_detail($registration_id, $cis_masters_values, $data) {

        $this->db->trans_start();
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $registration_id);
        $this->db->UPDATE('tbl_efiling_civil', $data);
        if ($this->db->affected_rows() > 0) {

            if ($cis_masters_values != NULL) {
                $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
                if ($success) {
                    $this->db->trans_complete();
                }
            } else {
                $this->db->trans_complete();
            }
        }

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function reset_subordinate_court_data($registration_id) {
        $this->db->trans_start();
        $data = array('is_deleted' => TRUE);

        $this->db->WHERE('registration_id', $registration_id);
        $this->db->WHERE('is_deleted', FALSE);
        $this->db->UPDATE('etrial_lower_court', $data);
        if ($this->db->affected_rows() > 0) {

            $cis_masters_values = array(
                'app_court_state_name' => NULL,
                'app_court_distt_name' => NULL,
                'app_court_sub_court_name' => NULL,
                'app_court_sub_case_type' => NULL,
                'trial_court_state_name' => NULL,
                'trial_court_distt_name' => NULL,
                'trial_court_sub_court_name' => NULL,
                'trial_court_case_type' => NULL,
                'high_court_case_type' => NULL
            );

            $success = $this->update_cis_master_values($registration_id, $cis_masters_values);
            if ($success) {
                $this->db->trans_complete();
            }
        }

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function delete_mvc_details($id) {

        $this->db->WHERE('id', $id);
        $this->db->WHERE('ref_m_efiling_nums_registration_id', $_SESSION['efiling_details']['registration_id']);
        $this->db->DELETE('tbl_efiling_mvc');
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_pet_and_res_list($registration_id, $type) {

        $this->db->SELECT('cl.id civilid,pet_name,res_name,exp.id expid,name,lname,extra_party_org_name,pet_legal_heir,res_legal_heir,party_no,party_id,type,exp.display,exp.parentid');
        $this->db->FROM('tbl_efiling_civil cl');
        $this->db->JOIN('tbl_efiling_civil_extra_party exp', "cl.ref_m_efiling_nums_registration_id = exp.ref_m_efiling_nums_registration_id", 'LEFT');
        $this->db->WHERE('cl.ref_m_efiling_nums_registration_id', $registration_id);
        $this->db->WHERE('cl.display', 'Y');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_extra_parties_without_lrs($registration_id, $shuffle_party_type) {
        $this->db->SELECT('id,type, party_no, name, parentid, party_id, legal_heir');
        $this->db->FROM('tbl_efiling_civil_extra_party ex');
        $this->db->WHERE('ex.ref_m_efiling_nums_registration_id', $registration_id);
        $this->db->WHERE('ex.parentid IS NULL');
        $this->db->WHERE('ex.type', $shuffle_party_type);
        $this->db->ORDER_BY('type');
        $this->db->ORDER_BY('cast(party_id as float)');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_extra_parties_with_lrs($registration_id, $shuffle_party_type) {
        $this->db->SELECT('id,type, party_no, name, parentid, party_id, legal_heir');
        $this->db->FROM('tbl_efiling_civil_extra_party ex');
        $this->db->WHERE('ex.ref_m_efiling_nums_registration_id', $registration_id);
        $this->db->WHERE('ex.type', $shuffle_party_type);
        //$this->db->WHERE('ex.display','Y');        
        $this->db->ORDER_BY('type');
        $this->db->ORDER_BY('cast(party_id as float)');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        } else {
            return FALSE;
        }
    }

    function update_extra_party_position($data, $parent_data) {
        $this->db->trans_start();
        $this->db->update_batch('tbl_efiling_civil_extra_party', $data, 'id');
        if ($this->db->affected_rows() > 0) {
            if (!empty($parent_data)) {
                $this->db->update_batch('tbl_efiling_civil_extra_party', $parent_data, 'id');
            }
            $this->db->trans_complete();
        }
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function add_efiled_case($data, $pet_mobile, $pet_email, $cis_masters_values, $estab_code, $state_code, $efiling_for_type_id, $entry_for_id, $breadcrumb, $extra_party_details, $efiling_civil_data, $subordinate_court_data, $mvc_details) {

        $this->db->trans_start();

        if (isset($_SESSION['estab_details']['hc_code'])) {
            $efiling_for_type_id = ENTRY_TYPE_FOR_HIGHCOURT;
            $est_code = $_SESSION['estab_details']['hc_code'];
        } else {
            $efiling_for_type_id = ENTRY_TYPE_FOR_ESTABLISHMENT;
            $est_code = $_SESSION['estab_details']['est_code'];
        }
        $curr_dt_time = date('Y-m-d H:i:s');
        if (empty($est_code)) {
            return FALSE;
        }

        //  if ($_SESSION['wants_to_file'] == E_FILING_TYPE_NEW_CASE) {
        $result = $this->gen_share_efiling_number($est_code, $efiling_for_type_id, $entry_for_id);


        $num_pre_fix = "EC";
        $filing_type = E_FILING_TYPE_NEW_CASE;

        $stage_id = Draft_Stage;
        if ($result) {
            if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
                $created_by = 0;
                $sub_created_by = $_SESSION['login']['id'];
            } else {
                $created_by = $_SESSION['login']['id'];
                $sub_created_by = 0;
            }
            $efiling = sprintf("%'.05d\n", $result['efiling_num']);
            $string = $num_pre_fix . $est_code . $efiling . $result['efiling_year'];
            $efiling_num = preg_replace('/\s+/', '', $string);
            $efiling_num_data = array('efiling_no' => $efiling_num,
                'efiling_year' => $result['efiling_year'],
                'efiling_for_type_id' => $efiling_for_type_id,
                'efiling_for_id' => $entry_for_id,
                'ref_m_efiled_type_id' => $filing_type,
                'created_by' => $created_by,
                'create_on' => $curr_dt_time,
                'create_by_ip' => getClientIP(),
                'sub_created_by' => $sub_created_by,
                'breadcrumb_status' => $breadcrumb
            );
            $result['registration_id'] = $this->add_efiling_nums($efiling_num_data);
            if (isset($result['registration_id']) && !empty($result['registration_id'])) {

                $data['ref_m_efiling_nums_registration_id'] = $result['registration_id'];
                $result = array('registration_id' => $result['registration_id'], 'efiling_no' => $efiling_num);
                $this->session->set_userdata('efiling_details', $result);

                

                $cis_masters_values['filcase_type_name'] = $_SESSION['efiling_for_details']['case_type_name'];
                $cis_masters_values['bench_name'] = $_SESSION['efiling_for_details']['benchName'];

                $this->db->INSERT('tbl_efiling_civil', $data);

                if ($this->db->insert_id()) {
                    $registration_id = $result['registration_id'];
                    if(count($extra_party_details)>0){
                    foreach ($extra_party_details as $extra_party) {
                        $extra_party_data[] = array(
                            'ref_m_efiling_nums_registration_id' => $registration_id,
                            'orgid' => $extra_party->orgid,
                            'name' => $extra_party->name,
                            'address' => $extra_party->address,
                            'pet_age' => $extra_party->pet_age,
                            'father_name' => $extra_party->father_name,
                            'father_flag' => $extra_party->father_flag,
                            'pet_religion' => $extra_party->pet_religion,
                            'pet_caste' => $extra_party->pet_caste,
                            'pet_sex' => $extra_party->pet_sex,
                            'type' => $extra_party->type,
                            'pet_occu' => $extra_party->pet_occu,
                            'pet_email' => $extra_party->pet_email,
                            'pet_mobile' => $extra_party->pet_mobile,
                            'state_id' => $extra_party->state_id,
                            'dist_code' => $extra_party->dist_code,
                            'taluka_code' => $extra_party->taluka_code,
                            'village_code' => $extra_party->village_code,
                            'town_code' => $extra_party->town_code,
                            'ward_code' => $extra_party->ward_code,
                            'pincode' => $extra_party->pincode,
                            'police_st_code' => $extra_party->police_st_code,
                            'other_info_flag' => $extra_party->other_info_flag,
                            'performaresflag' => $extra_party->performaresflag,
                            'passportno' => $extra_party->passportno,
                            'panno' => $extra_party->panno,
                            'pet_phone' => $extra_party->pet_phone,
                            'pet_fax' => $extra_party->pet_fax,
                            'country' => $extra_party->country,
                            'pet_nationality' => $extra_party->pet_nationality,
                            'altaddress' => $extra_party->altaddress,
                            'altstate_id' => $extra_party->altstate_id,
                            'altdist_code' => $extra_party->altdist_code,
                            'alttaluka_code' => $extra_party->alttaluka_code,
                            'altvillage_code' => $extra_party->altvillage_code,
                            'alttown_code' => $extra_party->alttown_code,
                            'altward_code' => $extra_party->altward_code,
                            'extra_party_org_name' => $extra_party->extra_party_org_name,
                            'extra_party_caste_name' => $extra_party->extra_party_caste_name,
                            'extra_party_state_name' => $extra_party->extra_party_state_name,
                            'extra_party_distt_name' => $extra_party->extra_party_distt_name,
                            'extra_party_taluka_name' => $extra_party->extra_party_taluka_name,
                            'extra_party_town_name' => $extra_party->extra_party_town_name,
                            'extra_party_ward_name' => $extra_party->extra_party_ward_name,
                            'extra_party_village_name' => $extra_party->extra_party_village_name,
                            'extra_party_ps_name' => $extra_party->extra_party_ps_name,
                            'extra_party_o_state_name' => $extra_party->extra_party_o_state_name,
                            'extra_party_o_distt_name' => $extra_party->extra_party_o_distt_name,
                            'extra_party_o_taluka_name' => $extra_party->extra_party_o_taluka_name,
                            'extra_party_o_town_name' => $extra_party->extra_party_o_town_name,
                            'extra_party_o_ward_name' => $extra_party->extra_party_o_ward_name,
                            'extra_party_o_village_name' => $extra_party->extra_party_o_village_name,
                            'extra_not_in_list_org' => $extra_party->extra_not_in_list_org,
                            'extra_party_religion_name' => $extra_party->extra_party_religion_name,
                            'extra_party_relation_name' => $extra_party->extra_party_relation_name,
                            'party_id' => $extra_party->party_id,
                            'parentid' => $extra_party->parentid,
                            'reconsume' => $extra_party->reconsume
                        );
                    }$add_extra_party = $this->add_efiled_extra_party($extra_party_data);
                    }if(count($efiling_civil_data[0]->serialno)){
                    foreach ($efiling_civil_data as $efiling_civil) {
                        $act_data[] = array(
                            'ref_m_efiling_nums_registration_id' => $registration_id,
                            'serialno' => $efiling_civil->serialno,
                            'case_no' => $efiling_civil->case_no,
                            'acts' => $efiling_civil->acts,
                            'section' => $efiling_civil->section,
                            'display' => $efiling_civil->display,
                            'reconsume' => $efiling_civil->reconsume
                        );
                        
                    }$add_act = $this->add_act_data($act_data);
                    }
                    if((!empty($subordinate_court_data[0]['registration_id'])) ){
                        //echo "dsd".count($subordinate_court_data);die;
                    $court_data = array('lower_state_id' => $subordinate_court_data[0]['lower_state_id'],
                        'lower_dist_code' => $subordinate_court_data[0]['lower_dist_code'],
                        'lower_court_code' => $subordinate_court_data[0]['lower_court_code'],
                        'lower_cino' => $subordinate_court_data[0]['lower_cino'],
                        'lower_judge_name' => $subordinate_court_data[0]['lower_judge_name'],
                        'lower_court' => $subordinate_court_data[0]['lower_court'],
                        'filing_case' => $subordinate_court_data[0]['filing_case'],
                        'lower_court_dec_dt' => $subordinate_court_data[0]['lower_court_dec_dt'],
                        'lower_dist_code' => $subordinate_court_data[0]['lower_dist_code'],
                        'lregis_date' => $subordinate_court_data[0]['lregis_date'],
                        'lcc_applied_date' => $subordinate_court_data[0]['lcc_applied_date'],
                        'lcc_received_date' => $subordinate_court_data[0]['lcc_received_date'],
                        ' case_no' => $subordinate_court_data[0]['case_no'],
                        'filing_no' => $subordinate_court_data[0]['filing_no'],
                        'lowerjocode' => $subordinate_court_data[0]['lowerjocode'],
                        'lower_taluka_code' => $subordinate_court_data[0]['lower_taluka_code'],
                        'lower_state_id' => $subordinate_court_data[0]['lower_state_id'],
                        'amd' => $subordinate_court_data[0]['amd'],
                        'qjnumber' => $subordinate_court_data[0]['qjnumber'],
                        'case_ref_no' => $subordinate_court_data[0]['case_ref_no'],
                        'date_of_order' => $subordinate_court_data[0]['date_of_order'],
                        'ljudid' => $subordinate_court_data[0]['ljudid'],
                        'judg_local_lang' => $subordinate_court_data[0]['judg_local_lang'],
                        'langflag' => $subordinate_court_data[0]['langflag'],
                        'oagainst' => $subordinate_court_data[0]['oagainst'],
                        'lower_trial' => $subordinate_court_data[0]['lower_trial'],
                        'sub_qj_high' => $subordinate_court_data[0]['sub_qj_high'],
                        'efilno' => $subordinate_court_data[0]['efilno'],
                        'registration_id' => $registration_id,
                        ' is_deleted' => $subordinate_court_data[0]['is_deleted'],
                        'reconsume' => $subordinate_court_data[0]['reconsume'],
                    );
                     $add_sub_court = $this->add_efiled_sub_court($court_data);
                    }
                    if(count($mvc_details)>0){
                    foreach($mvc_details as $mvc){
                    $mvc_data[] = array(
                    'ref_m_efiling_nums_registration_id'=>$registration_id,
                    'other_po_stn'=>$mvc->other_po_stn,
                    'case_no'=>$mvc->case_no,
                    'item_no'=>$mvc->item_no,
                    'police_stn_code'=>$mvc->police_stn_code,
                    'other_police_stn'=>$mvc->other_police_stn,
                    'fir_no'=>$mvc->fir_no,
                    'year'=>$mvc->year,
                    'accident_date'=>$mvc->accident_date,
                    'accident_place'=>$mvc->accident_place,
                    'compensation'=>$mvc->compensation,
                    'insurance_company'=>$mvc->insurance_company,
                    'vehicle_type'=>$mvc->vehicle_type,
                    'vehicle_regn_no'=>$mvc->vehicle_regn_no,
                    'display'=>$mvc->display,
                    'accident_time'=>$mvc->accident_time,
                    'dist_code'=>$mvc->dist_code,
                    'taluka_code'=>$mvc->taluka_code,
                    'fir_type_code'=>$mvc->fir_type_code,
                    'driving_license'=>$mvc->driving_license,
                    'issuing_authority'=>$mvc->issuing_authority,
                    'owner_name'=>$mvc->owner_name,
                    'lowner_name'=>$mvc->lowner_name,
                    'lissuing_authority'=>$mvc->lowner_name,
                    'laccident_place'=>$mvc->laccident_place,
                    'cino'=>$mvc->cino,
                    'efilno'=>$mvc->efilno,
                    'econfirm'=>$mvc->econfirm,
                    'state_id'=>$mvc->state_id,
                    'state_id_name'=>$mvc->state_id,
                    'dist_code_name'=>$mvc->dist_code_name,
                    'taluka_code_name'=>$mvc->taluka_code_name,
                    'police_stn_code_name'=>$mvc->police_stn_code_name,
                    'injurytype'=>$mvc->injurytype,
                    'father_husband_relation_of_petitioner'=>$mvc->father_husband_relation_of_petitioner,
                    'father_husband_title_of_petitioner'=>$mvc->father_husband_title_of_petitioner,
                    'father_husband_name_of_petitioner'=>$mvc->father_husband_name_of_petitioner,
                   'petitioner_filing_on_behalf_of'=>$mvc->petitioner_filing_on_behalf_of,
                    'party_name'=>$mvc->party_name,
                    'father_husband_relation_of_party'=>$mvc->father_husband_relation_of_party,
                    'father_husband_title_of_party'=>$mvc->father_husband_title_of_party,
                    'father_husband_name_of_party'=>$mvc->father_husband_name_of_party,
                    'address4'=>$mvc->address4,
                    'age4'=>$mvc->age4,
                    'occupation4'=>$mvc->occupation4,
                    'name_and_address4'=>$mvc->name_and_address4,
                    'monthly_income4'=>$mvc->monthly_income4,
                    'name_age_dependents4'=>$mvc->name_age_dependents4,
                    'details_property_damaged4'=>$mvc->details_property_damaged4,
                    'does_person_pay_income_tax4'=>$mvc->does_person_pay_income_tax4,
                   ' was_vechile_involved4'=>$mvc->was_vechile_involved4,
                    'was_vechile_involved_full_description4'=>$mvc->was_vechile_involved_full_description4,
                    'name_medical_officer4'=>$mvc->name_medical_officer4,
                    'period_of_treatment4'=>$mvc->period_of_treatment4,
                   'name_address_owner_vehicle4'=>$mvc->name_address_owner_vehicle4,
                    'name_address_insurer_vehicle4'=>$mvc->name_address_insurer_vehicle4,
                    'has_any_claim_lodged4'=>$mvc->has_any_claim_lodged4,
                    'has_any_claim_lodged_if_yes_detail4'=>$mvc->has_any_claim_lodged_if_yes_detail4,
                    'relationship_with_deceased4'=>$mvc->relationship_with_deceased4,
                    'title_to_property_deceased4'=>$mvc->title_to_property_deceased4,
                    'any_other_information'=>$mvc->any_other_information,
                    'have_not_filed_any_other_application'=>$mvc->have_not_filed_any_other_application,
                    'updated_by_user_id'=>$mvc->updated_by_user_id,
                    'updated_datetime'=>$mvc->updated_datetime,
                    'fir_type_code_name'=>$mvc->fir_type_code_name,
                    'reconsume'=>$mvc->reconsume,

                    );

                    }
                     $add_mvc = $this->add_efiled_mvc($mvc_data);
                    }
                    
                    
                   
                   
                    $success = $this->update_cis_master_values($result['registration_id'], $cis_masters_values);


                    if ($success) {
                        $data3 = array(
                            'registration_id' => $result['registration_id'],
                            'stage_id' => $stage_id,
                            'activated_on' => $curr_dt_time,
                            'activated_by' => $this->session->userdata['login']['id'],
                            'activated_by_ip' => getClientIP(),
                        );
                        $this->db->INSERT('tbl_efiling_case_status', $data3);
                        if ($this->db->insert_id()) {

                            $sentSMS = "Efiling No. " . efile_preview($efiling_num) . " generated from your efiling account & still pending for final submit. - Supreme Court of India";
                            $subject = "Efiling No. " . efile_preview($efiling_num) . " generated from your efiling account";
                            $Petsubject = "Efiling No. " . efile_preview($efiling_num) . " generated from for your petition";
                            $sentPetSMS = "Efiling No. " . efile_preview($efiling_num) . "   is generated for your petition filed by your Advocate" . $user_name . " and still pending for final submission.";
                            $user_name = $this->session->userdata['login']['first_name'] . ' ' . $this->session->userdata['login']['last_name'];

                            if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE) {

                                $pet_user_name = $data['pet_name'];
                                if (!empty($data['pet_mobile'])) {
                                    send_petitioner_mobile_sms($data['pet_mobile'], $sentPetSMS);
                                }if (!empty($data['pet_email'])) {
                                    send_petitioner_mail_msg($data['pet_email'], $Petsubject, $sentPetSMS, $pet_user_name);
                                }
                            }
                            send_mobile_sms($_SESSION['login']['mobile_number'], $sentSMS);
                            send_mail_msg($_SESSION['login']['emailid'], $subject, $sentSMS, $user_name);

                            $this->db->trans_complete();
                        }
                    }
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return $result;
        }
    }

    function gen_share_efiling_number($est_code, $efiling_for_type_id, $entry_for_id) {

        $this->db->SELECT('efiling_no,efiling_year');
        $this->db->WHERE('entry_for_type', $efiling_for_type_id);
        $this->db->WHERE('ref_m_establishment_id', $entry_for_id);
        $this->db->FROM('m_tbl_efiling_no');
        $query = $this->db->get();
        $row = $query->result_array();

        $p_efiling_num = $row[0]['efiling_no'];
        $year = $row[0]['efiling_year'];
        if ($year < date('Y')) {
            $newYear = date('Y');
            $update_data = array('efiling_no' => 1,
                'efiling_year' => $newYear,
                'updated_by' => $this->session->userdata['login']['id'],
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by_ip' => getClientIP());
            $this->db->WHERE('efiling_no', $p_efiling_num);
            $this->db->WHERE('efiling_year', $year);
            $this->db->WHERE('entry_for_type', $efiling_for_type_id);
            $this->db->WHERE('ref_m_establishment_id', $entry_for_id);
            $this->db->UPDATE('m_tbl_efiling_no', $update_data);
            if ($this->db->affected_rows() > 0) {
                $data['efiling_num'] = 1;
                $data['efiling_year'] = $newYear;
                return $data;
            } else {
                $this->gen_share_efiling_number();
            }
        } else {
            $gen_efiling_num = $p_efiling_num + 1;
            $efiling_num_info = array('efiling_no' => $gen_efiling_num,
                'updated_by' => $this->session->userdata['login']['id'],
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by_ip' => getClientIP());
            $this->db->WHERE('efiling_no', $p_efiling_num);
            $this->db->WHERE('entry_for_type', $efiling_for_type_id);
            $this->db->WHERE('ref_m_establishment_id', $entry_for_id);
            $result = $this->db->UPDATE('m_tbl_efiling_no', $efiling_num_info);
            if ($this->db->affected_rows() > 0) {
                $data['efiling_num'] = $gen_efiling_num;
                $data['efiling_year'] = $year;
                return $data;
            } else {
                $this->gen_share_efiling_number();
            }
        }
    }

    function add_efiled_extra_party($data) {
        $this->db->INSERT_BATCH('tbl_efiling_civil_extra_party', $data);
        if ($this->db->insert_id()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function add_act_data($data) {
        $this->db->INSERT_BATCH('tbl_extraact', $data);
        if ($this->db->insert_id()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function add_court_data($data) {
        $this->db->INSERT_BATCH('tbl_extraact', $data);
        if ($this->db->insert_id()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function add_efiled_sub_court($data) {
        $this->db->INSERT('etrial_lower_court', $data);
        if ($this->db->insert_id()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function add_efiled_mvc($data) {
        $this->db->INSERT_BATCH('tbl_efiling_mvc', $data);
        if ($this->db->insert_id()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

?>
