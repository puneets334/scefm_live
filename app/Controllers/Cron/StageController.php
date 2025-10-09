<?php

namespace App\Controllers\Cron;

use App\Controllers\BaseController;

use App\Libraries\webservices\Efiling_webservices;
use App\Models\AdminDashboard\StageListModel;
use App\Models\Cron\DefaultModel;
use App\Models\GetCISStatus\GetCISStatusModel;

class StageController extends BaseController
{
    protected $Default_model;
    protected $StageList_model;
    protected $Get_CIS_Status_model;
    protected $efiling_webservices;

    public function __construct()
    {
        parent::__construct();
        $this->Default_model = new DefaultModel();
        $this->StageList_model = new StageListModel();
        $this->Get_CIS_Status_model = new GetCISStatusModel();
        $this->efiling_webservices = new Efiling_webservices();
    }

    public function index()
    {
        $loop_for_stage_flag = I_B_Defects_Cured_Stage; // Defects Cured stage 
        $scrutiny_result = $this->Default_model->get_efiled_nums_stage_wise_list_admin_cron($loop_for_stage_flag, ADMIN_FOR_TYPE_ID, ADMIN_FOR_ID);
        if ($scrutiny_result) {
            $data = $this->efiling_webservices->get_new_case_efiling_scrutiny_cron_SCIS($scrutiny_result);
            if ($data) {
                $curr_dt = date('Y-m-d H:i:s');
                foreach ($data->consumed_data as $response) {
                    if ($response->status == 'A') {
                        $objections_status = NULL;
                        $documents_status = NULL;
                        $next_stage = 0;
                        $d_no = explode('/', $response->diary_no);
                        $diary_no = $d_no[0];
                        $diary_year = $d_no[1];
                        $diary_date = $response->diary_generated_on;
                        $case_details[0] = array(
                            'registration_id' => $response->registration_id,
                            'sc_diary_num' => $diary_no,
                            'sc_diary_year' => $diary_year,
                            'sc_diary_date' => $diary_date,
                            'updated_by' => AUTO_UPDATE_CRON_USER,
                            'updated_on' => $curr_dt,
                            'updated_by_ip' => getClientIP(),
                        );
                        if ($response->verification_date != '') {
                            echo "<br>verfication_date " . $response->verification_date;
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

                                $objections_status = $this->get_icmis_objections_status($response->registration_id, $cis_objections, $curr_dt);
                            }
                            $next_stage = E_Filed_Stage;
                        }
                        echo "<br><br>" . "Reg id " . $response->registration_id;
                        echo "<br>" . "next stage " . $next_stage;
                        echo "<br>" . "curr_dt " . $curr_dt;
                        echo "<br>case details:";
                        print_r($case_details);
                        echo "<br>obj status1 :";
                        isset($objections_status[1]) ? print_r($objections_status[1]) : NULL;
                        echo "<br>obj status2 :";
                        isset($objections_status[2]) ? print_r($objections_status[2]) : NULL;
                        echo "<br>";
                        echo "<br>----------<br>";
                        $update_status = $this->Default_model->update_icmis_case_status($response->registration_id, $next_stage, $curr_dt, $case_details, isset($objections_status[1]) ? $objections_status[1] : NULL, isset($objections_status[2]) ? $objections_status[2] : NULL);
                        if ($update_status) {
                            if ($next_stage) {
                                if ($next_stage == E_Filed_Stage) {
                                    echo "eFiling No. " . $response->efiling_no . " updated to Efiled Stage.";
                                }
                            }
                        }
                    }
                }
            } else {
                echo "No Records Found";
            }
        } else {
            echo "No Records Found for scrutiny";
        }
    }

    function get_icmis_documents_status($registration_id, $cis_documents, $ia_only, $check_verify, $curr_dt)
    {
        $efil_docs = $temp_efil_docs = $this->Get_CIS_Status_model->get_efiled_docs_list($registration_id, $ia_only);
        $i = 0;
        foreach ($temp_efil_docs as $e_doc) {
            $temp_efil_docs[$i]['doc_id'] = encrypt_doc_id($e_doc['doc_id']);
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

    function get_icmis_objections_status($registration_id, $cis_objections, $curr_dt)
    {
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
                    'updated_by' => AUTO_UPDATE_CRON_USER,
                    'updated_on' => $curr_dt,
                    'update_ip' => getClientIP()
                );
            } else {
                if ($obj_removed_date == NULL) {
                    $insert_objections[] = array(
                        'registration_id' => $registration_id,
                        'obj_id' => $cis_objection->org_id,
                        'remarks' => $cis_objection->remark,
                        'obj_prepare_date' => $cis_objection->save_dt,
                        'obj_removed_date' => $obj_removed_date,
                        'created_by' => AUTO_UPDATE_CRON_USER,
                        'created_on' => $curr_dt,
                        'create_ip' => getClientIP()
                    );
                }
            }
        }
        return array($objections_pending, $insert_objections, $update_objections);
    }
}
