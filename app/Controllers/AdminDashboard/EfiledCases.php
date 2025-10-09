<?php

namespace App\Controllers\AdminDashboard;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\AdminDashboard\EfiledCasesModel;
use App\Models\NewCase\Stageslist_model;

class EfiledCases extends BaseController {

    protected $Common_model;
    protected $Stageslist_model;
    protected $Efiled_cases_model;
    protected $efiling_webservices;

    public function __construct() {
        parent::__construct();
        $this->Efiled_cases_model = new EfiledCasesModel();
        $this->Stageslist_model = new Stageslist_model();
        $this->Common_model = new CommonModel();
        $this->efiling_webservices = new Efiling_webservices();
        $ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $refData = isset($ref) ? parse_url($ref) : '';
        $allowed_hosts = array($_SERVER['SERVER_NAME']);
        if ($refData['host'] != $_SERVER['SERVER_NAME'] && $refData['host'] != NULL) {
            if (!empty($refData['host']) && !in_array($refData['host'], $allowed_hosts) && $refData['host'] != NULL) {
                echo header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
                echo ' 400 BAD REQUEST ';
                exit(0);
            }
            if (count($_POST) > 0) {
                array_shift($allowed_hosts);
                if (!empty($refData['host']) && in_array($refData['host'], $allowed_hosts) && $refData['host'] != NULL) {
                    
                } elseif ($_POST['CSRF_TOKEN'] != $_SESSION['csrf_to_be_checked']) {
                    echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Error</title><style type="text/css"> ::selection { background-color: #E13300; color: white; } ::-moz-selection { background-color: #E13300; color: white; } body {  background-color: #fff;  margin: 40px;  font: 13px/20px normal Helvetica, Arial, sans-serif;  color: #4F5155;}a {  color: #003399;  background-color: transparent;  font-weight: normal;}h1 {  color: #444;  background-color: transparent;  border-bottom: 1px solid #D0D0D0;  font-size: 19px;  font-weight: normal;  margin: 0 0 14px 0;  padding: 14px 15px 10px 15px;}code {  font-family: Consolas, Monaco, Courier New, Courier, monospace;  font-size: 12px;  background-color: #f9f9f9;  border: 1px solid #D0D0D0;  color: #002166;  display: block;  margin: 14px 0 14px 0;  padding: 12px 10px 12px 10px;}#container {  margin: 10px;  border: 1px solid #D0D0D0;  box-shadow: 0 0 8px #D0D0D0;}p {  margin: 12px 15px 12px 15px;}</style></head><body>  <div id="container">    <h1>An Error Was Encountered</h1>    <p>The action you have requested is not allowed.</p>  </div></body></html>';
                    exit(0);
                }
            }
            $_SESSION['csrf_to_be_checked'] = $this->security->get_csrf_hash();
        } else {
            if (!isset($this->session->userdata['login'])) {
                $allowed_IP_access = array('10.26.57.224', '10.247.163.203', '10.247.163.207', '10.247.163.208', '10.247.163.216');
                if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_IP_access)) {
                    echo header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
                    echo ' 400 BAD REQUEST ';
                    exit(0);
                }
            } else {
                if (count($_POST) > 0) {
                    if ($_POST['CSRF_TOKEN'] != $_SESSION['csrf_to_be_checked']) {
                        echo 'Some Error ! The action you have requested is not allowed.';
                        exit(0);
                    }
                } else {
                    echo "Correct Data is not posted";
                    exit(0);
                }
            }
            $_SESSION['csrf_to_be_checked'] = $this->security->get_csrf_hash();
        }
    }

    public function index() {
        
    }

    public function get_status_from_CIS($cron_request = null) {
        $admin_update = false;
        $cron_update = false;
        $accepted_count = $already_accepted_count = $rejected_count = $objection_count = $already_objection_count = $pending_count = $efiled_count = $not_found_count = array();
        $ia_data_counts = $accepted_ia_count = $already_accepted_ia_count = $rejected_ia_count = $objection_ia_count = $pending_ia_count = $efiled_ia_count = $not_found_ia_count = '';
        if (isset($cron_request) && $cron_request == 'updateforAll') {
            $cron_update = true;
        } elseif (empty($_POST['form_submit']) && $_SESSION['login']['ref_m_usertype_id'] != USER_ADMIN && $_SESSION['login']['ref_m_usertype_id'] != USER_ACTION_ADMIN) {
            echo "Unauthorised Access.";
            exit(0);
        } elseif (url_decryption($_POST['form_submit']) == 'updateforAdmin') {
            $admin_update = true;
        } else {
            $id = url_decryption($_POST['form_submit']);
            $InputArrray = explode('#', $id);   //0=>registration_id,1=>type,2=>stage
            if (!isset($_POST['form_submit'])) {
                echo "Invalid Input";
                exit(0);
            }
            if (empty($InputArrray[0]) || empty($InputArrray[1]) || empty($InputArrray[2])) {
                echo "Invalid Input";
                exit(0);
            }
            if (!is_numeric($InputArrray[0]) && !is_numeric($InputArrray[1]) && !is_numeric($InputArrray[2])) {
                echo "Invalid Input";
                exit(0);
            }
        }
        if ($cron_update) {
            $estblishments_list = $this->Stageslist_model->get_establishments_list_for_cron();
        } elseif ($admin_update) {
            $estblishments_list = $this->Stageslist_model->get_establishments_of_admin($_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
        }
        $efiling_nums_new_case = array();
        $estblishment_new_case_data = array();
        $estblishment_new_case_data_count = 0;
        $total_efiling_nums = 0;
        if ($cron_update || $admin_update) {
            foreach ($estblishments_list as $estblishment) {
                $available_new_case_efiling_nums = $this->Stageslist_model->get_efilied_nums_cis_admin(array(Transfer_to_CIS_Stage, I_B_Approval_Pending_Admin_Stage, I_B_Defects_Cured_Stage), $estblishment->establishment_type, $estblishment->estab_id, E_FILING_TYPE_NEW_CASE);
                $available_IA_efiling_nums = $this->Stageslist_model->get_efilied_nums_cis_admin(array(Transfer_to_CIS_Stage, I_B_Approval_Pending_Admin_Stage, I_B_Defects_Cured_Stage), $estblishment->establishment_type, $estblishment->estab_id, E_FILING_TYPE_IA);
                if ((isset($available_new_case_efiling_nums) && !empty($available_new_case_efiling_nums)) || (isset($available_IA_efiling_nums) && !empty($available_IA_efiling_nums))) {
                    if (isset($available_new_case_efiling_nums) && !empty($available_new_case_efiling_nums)) {
                        $count_new_case_cis = count($available_new_case_efiling_nums);
                        $new_case_data = $this->call_new_case_cis_status_webservice($estblishment->estab_code, $estblishment->establishment_type, $available_new_case_efiling_nums, $estblishment->state_code, $count_new_case_cis);
                        if (!empty($new_case_data[0])) {
                            $total_efiling_nums += $count_new_case_cis;
                            $efiling_nums_new_case = array_merge($efiling_nums_new_case, $available_new_case_efiling_nums);
                            $estblishment_new_case_data[$estblishment_new_case_data_count] = $new_case_data;
                            $estblishment_new_case_data_count++;
                        }
                    }
                } else {
                    continue;
                }
            }
        } else {
            $registration_id = $InputArrray[0];
            $efile_type_id = $InputArrray[1];
            $stage_id = $InputArrray[2];
            if ($efile_type_id == E_FILING_TYPE_NEW_CASE) {
                $efiling_nums = $efiling_nums_new_case = $this->Stageslist_model->get_efiling_num_details($registration_id);
            }
            $establishment_details = $this->Common_model->get_establishment_details($_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
            if (isset($establishment_details) && !empty($establishment_details) && isset($efiling_nums) && !empty($efiling_nums)) {
                if ($efile_type_id == E_FILING_TYPE_NEW_CASE) {
                    $new_case_data = $this->call_new_case_cis_status_webservice($_SESSION['estab_details']['est_code'], $efiling_nums[0]['efiling_for_type_id'], $efiling_nums, $_SESSION['estab_details']['state_code'], 1);
                    if (!empty($new_case_data[0])) {
                        $estblishment_new_case_data[$estblishment_new_case_data_count] = $new_case_data;
                        $estblishment_new_case_data_count++;
                    }
                } else {
                    echo "Invalid Input";
                    exit(0);
                }
            }
        }
        $current_date_n_time = date('Y-m-d H:i:s');
        if (isset($estblishment_new_case_data) && !empty($estblishment_new_case_data)) {
            $new_case_records = $this->manage_records_for_NewCase($estblishment_new_case_data, $efiling_nums_new_case, $current_date_n_time, $cron_update, $admin_update);
            $accepted_count += $new_case_records['new_case_records_count']['accepted_count'];
            $already_accepted_count += $new_case_records['new_case_records_count']['already_accepted_count'];
            $rejected_count += $new_case_records['new_case_records_count']['rejected_count'];
            $objection_count += $new_case_records['new_case_records_count']['objection_count'];
            $already_objection_count += $new_case_records['new_case_records_count']['already_objection_count'];
            $pending_count += $new_case_records['new_case_records_count']['pending_count'];
            $efiled_count += $new_case_records['new_case_records_count']['efiled_count'];
            $not_found_count += $new_case_records['new_case_records_count']['not_found_count'];
            $case_data_counts = '<b>Total New Cases</b> - ' . count($efiling_nums_new_case) . '@@@<br><b>Efiled</b> - ' . $efiled_count .
                    '<br><b>Pending Scrutiny</b> -  ' . $accepted_count . '<br><b>Already At Pending Scrutiny</b> -  ' . $already_accepted_count . '<br><b>Objections</b> -  ' . $objection_count .
                    '<br><b>Already At Objections</b> -  ' . $already_objection_count . '<br><b>Action Pendings</b> - ' . $pending_count . '<br><b>Rejected</b> - ' . $rejected_count .
                    '<br><b>Not Found</b> - ' . $not_found_count;
        }
        if ((isset($estblishment_new_case_data) && !empty($estblishment_new_case_data))) {
            $update_status = $this->Efiled_cases_model->update_multi_records_CIS_Status($new_case_records, $efiling_nums_new_case, $current_date_n_time, $cron_update);
        } else {
            $update_status = FALSE;
        }
        if ($update_status) {
            if (!empty($new_case_records['sms_mail_to_send'])) {
                foreach ($new_case_records['sms_mail_to_send'] as $msg) {
                    send_mobile_sms($msg['user_mobile'], $msg['sms']);
                    send_mail_msg($msg['emailid'], $msg['subject'], $msg['sms'], $msg['username']);
                    if ($msg['usertype'] == USER_ADVOCATE) {
                        if (!empty($msg['pet_subject'])) {
                            $sms = $msg['sms'];
                            $msg = $msg['pet_subject'];
                        } else {
                            $sms = $msg['sms'];
                            $msg = $msg['subject'];
                        }
                        send_petitioner_mobile_sms($msg['pet_mobile'], $sms);
                        send_petitioner_mail_msg($msg['pet_email'], $msg, $sms, $msg['pet_name']);
                        $pet_exp = substr($msg['pet_exp'], 1, -1);
                        $extraPartydetail = explode('","', $pet_exp);
                        for ($i = 0; $i < count($extraPartydetail); $i++) {
                            $expdetail = explode('##', $extraPartydetail[$i]);
                            $exp_name = $expdetail[2];
                            $exp_email = $expdetail[3];
                            $exp_mobile = $expdetail[4];
                            send_petitioner_mobile_sms($exp_mobile, $msg['sms']);
                            send_petitioner_mail_msg($exp_email, $msg['subject'], $msg['sms'], $exp_name);
                        }
                    }
                }
            }
            if ($admin_update || $cron_update) {
                echo '2@@@' . $case_data_counts . $ia_data_counts;
            } else {
                if ($accepted_count || $accepted_ia_count) {
                    echo 'Efiling number ' . efile_preview($efiling_nums[0]['efiling_no']) . ' is pending for scrutiny. ';
                } elseif ($already_accepted_count || $already_accepted_ia_count) {
                    echo 'For Efiling number ' . efile_preview($efiling_nums[0]['efiling_no']) . ' no status chagned in ICMIS. ';
                } elseif ($rejected_count || $rejected_ia_count) {
                    echo 'Efiling number ' . efile_preview($efiling_nums[0]['efiling_no']) . ' is rejected from ICMIS. ';
                } elseif ($objection_count || $objection_ia_count) {
                    echo 'Efiling number ' . efile_preview($efiling_nums[0]['efiling_no']) . ' is found defective in ICMIS and transfered to defective stage.';
                } elseif ($pending_count || $pending_ia_count) {
                    echo 'Efiling number ' . efile_preview($efiling_nums[0]['efiling_no']) . ' is pending for action in ICMIS. ';
                } elseif ($efiled_count || $efiled_ia_count) {
                    echo 'Efiling number ' . efile_preview($efiling_nums[0]['efiling_no']) . ' is registered and transfered to efiled stage.';
                } elseif ($not_found_count || $not_found_ia_count) {
                    echo 'Efiling number ' . efile_preview($efiling_nums[0]['efiling_no']) . ' not found in ICMIS tables. ';
                }
            }
        } else {
            echo '1@@@' . htmlentities('Some things went wrong. Please Try again', ENT_QUOTES);
        }
    }

    function manage_records_for_NewCase($estblishment_data, $efiling_nums, $current_date_n_time, $cron_update, $admin_update) {
        $accepted_count = 0;
        $already_accepted_count = 0;
        $rejected_count = 0;
        $objection_count = 0;
        $already_objection_count = 0;
        $pending_count = 0;
        $efiled_count = 0;
        $not_found_count = 0;
        $case_data_update_count = 0;
        $stage_update_count = 0;
        $defect_insert_count = 0;
        $defect_update_count = 0;
        $sms_mail_count = 0;
        $l = 0;
        $case_data_to_update = array();
        $defects_to_update = array();
        $defects_to_insert = array();
        $stage_to_update = array();
        $sms_mail_to_send = array();
        $m = 0;
        $case_details = [];
        if (!(isset($_SESSION['login']['id']) && !empty($_SESSION['login']['id']))) {
            $approved_by = '000';
        } else {
            $approved_by = $_SESSION['login']['id'];
        }
        foreach ($estblishment_data as $estab_data) {
            $status = $estab_data[$m];
            $case_display_no = !($status->reg_no_display == 'NULL') ? $status->reg_no_display : null;
            if ($status->efiling_no == $efiling_nums[$l]['efiling_no']) {
                $registration_id = $efiling_nums[$l]['registration_id'];
                if ($status->status == 'A') {
                    $case_diary_no = !($status->diary_no == 'NULL') ? $status->diary_no : null;
                    $case_diary_date = !($status->diary_generated_on == 'NULL') ? $status->diary_generated_on : null;
                    $case_details = array('sc_diary_num' => $case_diary_no, 'sc_diary_date' => $case_diary_date, 'registration_id' => $registration_id);                    
                    if($status->objection_flag == 'N' && $status->verification_date != NULL){
                        
                    }elseif($status->objection_flag == 'Y' && $status->objections != NULL && $status->verification_date != NULL){
                        
                    }elseif ($status->objection_flag == 'Y' && $status->objections != NULL) {
                        if ($efiling_nums[$l]['stage_id'] == I_B_Defects_Cured_Stage) {
                            $previous_defects = $this->Common_model->get_intials_defects_remarks($efiling_nums[$l]['registration_id'], $efiling_nums[$l]['stage_id']);
                        }
                        $defect_date = $obj_scrutiny_date = !($status->scruitiny_date == 'NULL') ? $status->scruitiny_date : null;
                        $cis_objections = $status->objections;
                        $objsr_no = 0;
                        $objections = '';
                        $cis_obj = array();
                        foreach ($cis_objections as $cis_objection) {                            
                            if ($cis_objection != '') {
                                $objsr_no++;
                                $objections .= $cis_objection->org_id.'$$'.$cis_objection->save_dt.'$$'.$cis_objection->rm_dt.'$$'.$cis_objection->remark;
                            }
                        }
                        if ($efiling_nums[$l]['stage_id'] == I_B_Defects_Cured_Stage) {
                            $defects_compare_result = strcmp($objections, $previous_defects->defect_remark);
                            if (!$defects_compare_result) {
                                if ($cron_update || $admin_update) {
                                    $already_objection_count++;
                                    $l++;
                                    continue;
                                } else {
                                    echo "Objections are same in CIS to that of previously notified defects; Please update there.";
                                    exit(0);
                                }
                            }
                            $defects_to_update[$defect_update_count] = array(
                                'initial_defects_id' => $efiling_nums[$l]['defects_id'],
                                'is_approved' => TRUE,
                                'scrutiny_date' => $obj_scrutiny_date,
                                'still_defective' => TRUE,
                                'approve_date' => $current_date_n_time,
                                'approved_by' => $approved_by
                            );
                            $defect_update_count++;
                        }
                        $insert_defect = array(
                            'registration_id' => $registration_id,
                            'defect_remark' => $objections,
                            'scrutiny_date' => $obj_scrutiny_date,
                            'defect_date'=>$defect_date,
                            'updated_by' => $approved_by,
                            'ip_address' => $_SERVER['REMOTE_ADDR']
                        );
                        $case_data_to_update[$case_data_update_count] = $case_details;
                        $stage = array('registration_id' => $registration_id, 'stage_id' => I_B_Defected_Stage);
                        $defects_to_insert[$defect_insert_count] = $insert_defect;
                        $stage_to_update[$stage_update_count] = $stage;
                        $objection_count++;
                        $case_data_update_count++;
                        $stage_update_count++;
                        $defect_insert_count++;
                        $sms_mail_count++;
                    } else {
                        if ($efiling_nums[$l]['stage_id'] == I_B_Approval_Pending_Admin_Stage) {
                            $already_accepted_count++;
                            $l++;
                            continue;
                        }
                        $stage = I_B_Approval_Pending_Admin_Stage;
                        $case_data_to_update[$case_data_update_count] = $case_details;
                        $stage = array('registration_id' => $registration_id, 'stage_id' => I_B_Approval_Pending_Admin_Stage);
                        $stage_to_update[$stage_update_count] = $stage;
                        $accepted_count++;
                        $case_data_update_count++;
                        $stage_update_count++;
                        $sms_mail_count++;
                    }
                } elseif ($status->status == 'R') {
                    if (!(isset($_SESSION['login']['id']) && !empty($_SESSION['login']['id']))) {
                        $approved_by = '000';
                    } else {
                        $approved_by = $_SESSION['login']['id'];
                    }
                    $insert_defect = array(
                        'registration_id' => $registration_id,
                        'defect_remark' => $status->MESSAGE,
                        'defect_date' => $current_date_n_time,
                        'updated_by' => $approved_by,
                        'ip_address' => $_SERVER['REMOTE_ADDR']
                    );
                    $case_data_to_update[$case_data_update_count] = $case_details;
                    $defects_to_insert[$defect_insert_count] = $insert_defect;
                    $stage = array('registration_id' => $registration_id, 'stage_id' => E_REJECTED_STAGE);
                    $stage_to_update[$stage_update_count] = $stage;
                    $rejected_count++;
                    $case_data_update_count++;
                    $stage_update_count++;
                    $defect_insert_count++;
                    $sms_mail_count++;
                } elseif ($status->status == 'P') {
                    $pending_count++;
                } elseif ($status->status == 'N') {
                    $not_found_count++;
                }
            }
            $l++;
            $m++;
        }
        $new_case_records_count = array('accepted_count' => $accepted_count, 'already_accepted_count' => $already_accepted_count, 'rejected_count' => $rejected_count,
            'objection_count' => $objection_count, 'already_objection_count' => $already_objection_count,
            'pending_count' => $pending_count, 'efiled_count' => $efiled_count, 'not_found_count' => $not_found_count, 'sms_mail_count' => $sms_mail_count);
        $new_case_records = array('case_data_to_update' => $case_data_to_update, 'defects_to_insert' => $defects_to_insert, 'stage_to_update' => $stage_to_update,
            'defects_to_update' => $defects_to_update, 'sms_mail_to_send' => $sms_mail_to_send, 'new_case_records_count' => $new_case_records_count);
        return $new_case_records;
    }

    function call_new_case_cis_status_webservice($establishment_code, $efiling_for_type_id, $available_efiling_nums, $establishment_state_code, $count_cis) {
        $multi_record_fetch_flag = 2;
        $batch_count = floor($count_cis / CIS_Batches);
        $batch_remainder = $count_cis % CIS_Batches;
        $left_value = 0;
        $right_value = CIS_Batches - 1;
        for ($i = 0; $i < $batch_count; $i++) {
            $efiling_nums_str = '';
            for ($k = $left_value; $k <= $right_value; $k++) {
                $efiling_nums_str .= $available_efiling_nums[$k]['efiling_no'] . ',';
            }
            $efiling_nums_str = !empty($efiling_nums_str) ? rtrim($efiling_nums_str, ',') : '';
            $left_value = $right_value + 1;
            $right_value = $right_value + CIS_Batches;
            $array_name = $i;
            $data[$array_name] = $this->efiling_webservices->get_new_case_efiling_nums_status_from_SCIS($efiling_nums_str);
        }
        if ($batch_remainder > 0) {
            $efiling_nums_str = '';
            $left_value = $batch_count * CIS_Batches;
            $right_value = $left_value + $batch_remainder - 1;
            for ($k = $left_value; $k <= $right_value; $k++) {
                $efiling_nums_str .= $available_efiling_nums[$k]['efiling_no'] . ',';
            }
            $efiling_nums_str = !empty($efiling_nums_str) ? rtrim($efiling_nums_str, ',') : '';
            if ($batch_remainder == 1) {
                $data[$i] = $this->efiling_webservices->get_new_case_efiling_nums_status_from_SCIS($efiling_nums_str);
                return $data[$i]->consumed_data;
            } else {
                $data[$i] = $this->efiling_webservices->get_new_case_efiling_nums_status_from_SCIS($efiling_nums_str);
            }
        }
    }

}