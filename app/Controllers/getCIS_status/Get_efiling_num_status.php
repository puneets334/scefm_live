<?php
namespace App\Controllers;

class Get_efiling_num_status extends BaseController {

    public function __construct() {
        parent::__construct();
        //$this->load->model('dashboard/Dashboard_model');
        $this->load->model('adminDashboard/StageList_model');

        require_once APPPATH . 'third_party/SBI/Crypt/AES.php';
        require_once APPPATH . 'third_party/cg/AesCipher.php';

        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('functions'); // loading custom functions
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->helper('captcha');
        //$this->load->library('TCPDF');
        date_default_timezone_set('Asia/Kolkata');

        //load the login model
        $this->load->model('getCIS_status/Get_CIS_Status_model');

        $this->load->library('webservices/efiling_webservices');
    }

    public function _remap($param = NULL) {

        if ($param == 'index') {
            $this->index(NULL);
        } else {
            redirect('login');
            exit(0);
        }
    }

    public function index() {

        $users_array = array(USER_ADMIN);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $users_array)) {
            redirect('login');
            exit(0);
        }

        $request_for = explode('#', url_decryption($_POST['form_submit']));

        if ($request_for[1] == E_FILING_TYPE_NEW_CASE) {
            $this->get_cis_status_for_new_case($request_for[0], $request_for[3], $request_for[2] );
        } elseif ($request_for[1] == E_FILING_TYPE_MISC_DOCS) {
            $this->get_cis_status_for_misc_doc($request_for[0], $request_for[3], $request_for[2]);
        } elseif ($request_for[1] == E_FILING_TYPE_IA) {
            $this->get_cis_status_for_ia($request_for[0], $request_for[3], $request_for[2]);
        } else {
            exit(0);
        }
    }

    function get_cis_status_for_new_case($registration_id, $efiling_num, $curr_stage) {
        //echo $registration_id.'--'.$efiling_num;
        //$efiling_num = 'ECSCIN01000022020';
        $curr_dt = date('Y-m-d H:i:s');
        $data = $this->efiling_webservices->get_new_case_efiling_nums_status_from_SCIS($efiling_num);
        $response = $data->consumed_data[0];
        // print_r($response);      //  die;

        if ($response->efiling_no != $efiling_num) {
            echo "Invalid Number";
            exit(0);
        }

        if ($response->status == 'N') {
            echo "eFiling number " . efile_preview($efiling_num) . " not found in ICMIS";
            exit(0);
        }

        if ($response->status == 'R') {
            echo "eFiling number " . efile_preview($efiling_num) . " rejected in ICMIS";
            exit(0);
        }

        if ($response->status == 'P') {
            echo "eFiling number " . efile_preview($efiling_num) . " Pending for Action in ICMIS";
            exit(0);
        }

        if ($response->status == 'A') {

            $objections_status = NULL;
            $documents_status = NULL;

            $next_stage = 0;
            $d_no = explode('/', $response->diary_no);
            $diary_no = $d_no[0];
            $diary_year = $d_no[1];
            $diary_date = $response->diary_generated_on;

            $case_details[0] = array(
                'registration_id' => $registration_id,
                'sc_diary_num' => $diary_no,
                'sc_diary_year' => $diary_year,
                'sc_diary_date' => $diary_date,
                'updated_by' => $_SESSION['login']['id'],
                'updated_on' => $curr_dt,
                'updated_by_ip' => getClientIP(),
            );

            if ($response->verification_date != '') {
                $reg_no_display = $response->reg_no_display;
                $reg_date = $response->registration_date;
                if ($reg_date == '0000-00-00 00:00:00') {
                    $reg_date = null;
                }
                $case_details1[0] = array(
                    'sc_display_num' => $reg_no_display,
                    'sc_reg_date' => $reg_date,
                    'verification_date' => $response->verification_date
                );

                $case_details[0] = array_merge($case_details1[0], $case_details[0]);

                if ($response->objection_flag == 'Y') {

                    $cis_objections = $response->objections;

                    $objections_status = $this->get_icmis_objections_status($registration_id, $cis_objections, $curr_dt);
                    // $objections_status => 0 -> objections pending; 1 -> objections insert; 2 -> update objections
                }

                $cis_documents = $response->doc_details;

                $documents_status = $this->get_icmis_documents_status($registration_id, $cis_documents, TRUE, FALSE, $curr_dt);
                // $objections_status => 0 -> documents pending; 1 -> documents update status;                
                //print_r($documents_status);                die;

                if ($documents_status[0]) {
                    $next_stage = I_B_Approval_Pending_Admin_Stage;
                } else {
                    $next_stage = E_Filed_Stage;
                }
            } else {

                $stage_update_timestamp = $this->Get_CIS_Status_model->get_stage_update_timestamp($registration_id, $curr_stage);
                
                if ($response->objection_flag == 'Y') {
                    
                    if($stage_update_timestamp[0]['activated_on'] >= $response->last_defect_notified_date){
                        echo "eFiling No. " . $efiling_num . " action is still pending for scrutiny.";
                            exit(0);
                    }

                    $cis_objections = $response->objections;

                    $objections_status = $this->get_icmis_objections_status($registration_id, $cis_objections, $curr_dt);
                    // $objections_status => 0 -> objections pending; 1 -> objections insert; 2 -> update objections

                    if ($objections_status[0] == TRUE && ($objections_status[1] || $objections_status[2])) {
                        $next_stage = I_B_Defected_Stage;
                    }
                } else {
                    $next_stage = I_B_Approval_Pending_Admin_Stage;
                }
                
                $cis_documents = $response->doc_details;

                $documents_status = $this->get_icmis_documents_status($registration_id, $cis_documents, TRUE, FALSE, $curr_dt);
            }
            
                // $objections_status => 0 -> documents pending; 1 -> documents update status;                
                //print_r($documents_status);                die;
                
                //print_r($objections_status);die;
                // $objections_status => 0 -> objections pending; 1 -> objections insert; 2 -> update objections
                $update_status = $this->Get_CIS_Status_model->update_icmis_case_status($registration_id, $next_stage, $curr_dt, $case_details, $objections_status[1], $objections_status[2], $documents_status[1]);
                if ($update_status) {
                    if ($next_stage) {
                        if ($next_stage == I_B_Defected_Stage) {
                            echo "eFiling No. " . $efiling_num . " updated to Defective Stage.";
                            exit(0);
                        } elseif ($next_stage == I_B_Approval_Pending_Admin_Stage) {
                            echo "eFiling No. " . $efiling_num . " updated to Pending Scrutiny Stage.";
                            exit(0);
                        } elseif ($next_stage == E_Filed_Stage) {
                            echo "eFiling No. " . $efiling_num . " updated to Efiled Stage.";
                            exit(0);
                        }
                    }
                }
        }
    }

    function get_cis_status_for_misc_doc($registration_id, $efiling_num , $curr_stage) {
        $data = $this->efiling_webservices->get_misc_doc_ia_efiling_nums_status_from_SCIS($efiling_num);
        //$response = $data->consumed_data[0];
        $response = $data[0];
        //print_r($response);die;
$curr_dt = date('Y-m-d H:i:s');


        if ($response->efiling_no != $efiling_num) {
            echo "Invalid Number";
            exit(0);
        }

        if ($response->status == 'N') {
            echo "eFiling number " . efile_preview($efiling_num) . " not found in ICMIS";
            exit(0);
        }

        if ($response->status == 'R') {
            echo "eFiling number " . efile_preview($efiling_num) . " rejected in ICMIS";
            exit(0);
        }

        if ($response->status == 'P') {
            echo "eFiling number " . efile_preview($efiling_num) . " Pending for Action in ICMIS";
            exit(0);
        }

        if ($response->status == 'A') {

            $cis_documents = $response->doc_details;

            $documents_status = $this->get_icmis_documents_status($registration_id, $cis_documents, FALSE, FALSE, $curr_dt);
            // $objections_status => 0 -> documents pending; 1 -> documents update status;  

            if ($documents_status[0]) {
                $next_stage = I_B_Approval_Pending_Admin_Stage;
            } else {
                $next_stage = Document_E_Filed;
            }

            $update_status = $this->Get_CIS_Status_model->update_misc_doc_ia_status($registration_id, $next_stage, $curr_dt, $objections_status[1], $objections_status[2], $documents_status[1]);
            if ($update_status) {
                if ($next_stage) {
                    if ($next_stage == I_B_Defected_Stage) {
                        echo "eFiling No. " . $efiling_num . " updated to Defective Stage.";
                        exit(0);
                    } elseif ($next_stage == I_B_Approval_Pending_Admin_Stage) {
                        echo "eFiling No. " . $efiling_num . " updated to Pending Scrutiny Stage.";
                        exit(0);
                    } elseif ($next_stage == Document_E_Filed) {
                        echo "eFiling No. " . $efiling_num . " updated to Efiled Stage.";
                        exit(0);
                    }
                }
            }
        }
    }

    function get_cis_status_for_ia($registration_id, $efiling_num, $curr_stage) {
        $data = $this->efiling_webservices->get_misc_doc_ia_efiling_nums_status_from_SCIS($efiling_num);
        //$response = $data->consumed_data[0];
        $response = $data[0];
        //print_r($response);
       //die;
$curr_dt = date('Y-m-d H:i:s');

        if ($response->efiling_no != $efiling_num) {
            echo "Invalid Number";
            exit(0);
        }

        if ($response->status == 'N') {
            echo "eFiling number " . efile_preview($efiling_num) . " not found in ICMIS";
            exit(0);
        }

        if ($response->status == 'R') {
            echo "eFiling number " . efile_preview($efiling_num) . " rejected in ICMIS";
            exit(0);
        }

        if ($response->status == 'P') {
            echo "eFiling number " . efile_preview($efiling_num) . " Pending for Action in ICMIS";
            exit(0);
        }

        if ($response->status == 'A') {

            $cis_documents = $response->doc_details;

            $documents_status = $this->get_icmis_documents_status($registration_id, $cis_documents, TRUE, FALSE, $curr_dt);
            // $objections_status => 0 -> documents pending; 1 -> documents update status; 
            //echo "<pre>"; print_r($documents_status); die;
            if ($documents_status[0]) {
                $next_stage = I_B_Approval_Pending_Admin_Stage;
            } else {
                $next_stage = IA_E_Filed;
            }

            $update_status = $this->Get_CIS_Status_model->update_misc_doc_ia_status($registration_id, $next_stage, $curr_dt, $objections_status[1], $objections_status[2], $documents_status[1]);
            if ($update_status) {
                if ($next_stage) {
                    if ($next_stage == I_B_Defected_Stage) {
                        echo "eFiling No. " . $efiling_num . " updated to Defective Stage.";
                        exit(0);
                    } elseif ($next_stage == I_B_Approval_Pending_Admin_Stage) {
                        echo "eFiling No. " . $efiling_num . " updated to Pending Scrutiny Stage.";
                        exit(0);
                    } elseif ($next_stage == IA_E_Filed) {
                        echo "eFiling No. " . $efiling_num . " updated to Efiled Stage.";
                        exit(0);
                    }
                }
            }
        }
    }

    function get_icmis_documents_status($registration_id, $cis_documents, $ia_only, $check_verify, $curr_dt) {

        $efil_docs = $temp_efil_docs = $this->Get_CIS_Status_model->get_efiled_docs_list($registration_id, $ia_only);
        $i = 0;

        foreach ($temp_efil_docs as $e_doc) { 
            $temp_efil_docs[$i]['doc_id'] = $this->encrypt_doc_id($e_doc['doc_id']);
            $i++;
        }

        $update_documents = array();

        $documents_pending = FALSE;

        foreach ($cis_documents as $doc) {

            $key = array_keys(array_column($temp_efil_docs, 'doc_id'), $doc->doc_id);

            $verified_on_date = ($doc->verified_on == '0000-00-00 00:00:00') ? NULL : $doc->verified_on;
            $disposal_date = ($doc->dispose_date == '0000-00-00') ? NULL : $doc->dispose_date;

            if ($check_verify) {
                if ($doc->verified_on == '0000-00-00 00:00:00' && $documents_pending == FALSE) {
                    $documents_pending = TRUE;
                }
            } else {
                if ($doc->docnum == NULL) {
                    $documents_pending = TRUE;
                }
            }

            if ($key) {

                $efil_doc_id = $efil_docs[$key[0]]['doc_id'];

                $update_documents[] = array(
                    'doc_id' => $efil_doc_id,
                    'icmis_diary_no' => $doc->diary_no,
                    'icmis_doccode' => $doc->doccode,
                    'icmis_doccode1' => $doc->doccode1,
                    'icmis_docnum' => $doc->docnum,
                    'icmis_docyear' => $doc->docyear,
                    'icmis_other1' => $doc->other1,
                    'icmis_iastat' => $doc->iastat,
                    'icmis_verified' => $doc->verified,
                    'icmis_verified_on' => $verified_on_date,
                    'icmis_dispose_date' => $disposal_date,
                    'updated_by' => $_SESSION['login']['id'],
                    'updated_on' => $curr_dt,
                    'update_ip_address' => getClientIP(),
                );
            }
        }

        return array($documents_pending, $update_documents);
    }

    function get_icmis_objections_status($registration_id, $cis_objections, $curr_dt) {

        $old_objections = $this->Get_CIS_Status_model->get_icmis_objections_list($registration_id);

        $insert_objections = array();
        $update_objections = array();

        $objections_pending = FALSE;

        foreach ($cis_objections as $cis_objection) {

            $key = array_keys(array_column($old_objections, 'obj_id'), $cis_objection->org_id);

            $obj_removed_date = ($cis_objection->rm_dt == '0000-00-00 00:00:00') ? NULL : $cis_objection->rm_dt;

            if ($cis_objection->rm_dt == '0000-00-00 00:00:00' && $objections_pending == FALSE) {
                $objections_pending = TRUE;
            }

            if ($key) {

                $efil_obj_id = $old_objections[$key[0]]['id'];

                $update_objections[] = array(
                    'id' => $efil_obj_id,
                    'obj_id' => $cis_objection->org_id,
                    'remarks' => $cis_objection->remark,
                    'obj_prepare_date' => $cis_objection->save_dt,
                    'obj_removed_date' => $obj_removed_date,
                    'updated_by' => $_SESSION['login']['id'],
                    'updated_on' => $curr_dt,
                    'update_ip' => $_SERVER['REMOTE_ADDR'],
                    'pspdfkit_document_id' => $cis_objection->pspdfkit_document_id,
                    'to_be_modified_pspdfkit_document_pages_raw' => $cis_objection->to_be_modified_pspdfkit_document_pages_raw,
                    'to_be_modified_pspdfkit_document_pages_parsed' => $cis_objection->to_be_modified_pspdfkit_document_pages_parsed,
                );
            } else {
                if ($obj_removed_date == NULL) {

                    $insert_objections[] = array(
                        'registration_id' => $registration_id,
                        'obj_id' => $cis_objection->org_id,
                        'remarks' => $cis_objection->remark,
                        'obj_prepare_date' => $cis_objection->save_dt,
                        'obj_removed_date' => $obj_removed_date,
                        'created_by' => $_SESSION['login']['id'],
                        'created_on' => $curr_dt,
                        'create_ip' => $_SERVER['REMOTE_ADDR'],
                        'pspdfkit_document_id' => $cis_objection->pspdfkit_document_id,
                        'to_be_modified_pspdfkit_document_pages_raw' => $cis_objection->to_be_modified_pspdfkit_document_pages_raw,
                        'to_be_modified_pspdfkit_document_pages_parsed' => $cis_objection->to_be_modified_pspdfkit_document_pages_parsed,
                    );
                }
            }
        }

        return array($objections_pending, $insert_objections, $update_objections);
    }

    function encrypt_doc_id($doc_id) {

        $doc_parameter = $doc_id . '|1';

        $aes = new Crypt_AES();
        $secret = base64_decode(SBI_PAYMENT_DOUBLE_VARIFICATION_SECRET_KEY);
        $aes->setKey($secret);
        $encrypted_doc_id = base64_encode($aes->encrypt($doc_parameter));
        $encrypted_doc_id = str_replace('/', '-', $encrypted_doc_id);
        $encrypted_doc_id = str_replace('=', ':', $encrypted_doc_id);
        $encrypted_doc_id = str_replace('+', '.', $encrypted_doc_id);

        return $encrypted_doc_id;
    }




}




?>