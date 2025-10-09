<?php
namespace App\Controllers\Cron;
use App\Controllers\BaseController; 
use App\Models\AdminDashboard\StageListModel;
use App\Models\Cron\DefaultModel;
use App\Models\GetCISStatus\GetCISStatusModel;
use App\Libraries\webservices\Efiling_webservices;

use Config\Services;
class DefaultController extends BaseController {
    protected $Default_model;
    protected $StageList_model;
    protected $Get_CIS_Status_model;
    protected $efiling_webservices;
    public function __construct() {
        parent::__construct();
        $this->Default_model = new DefaultModel();
        $this->StageList_model = new StageListModel();
        $this->Get_CIS_Status_model = new GetCISStatusModel();
        $this->efiling_webservices = new Efiling_webservices();
        helper(['form', 'url', 'html', 'functions']); 
 
        $this->session = Services::session();
    }

    public function index()
    {
        $diary_no=isset($_REQUEST['diary_no']) && !empty($_REQUEST['diary_no']) ? $_REQUEST['diary_no'] :'All';
        //E_FILING_TYPE_NEW_CASE
        //I_B_Approval_Pending_Admin_Stage
        $cronDetails= array("cron_type"=>'scrutiny_status','started_at'=>date('Y-m-d H:i:s'));

        $cronDetailsId=$this->Default_model->insertInDBwithInsertedId('efil.tbl_cron_details',$cronDetails);
 
        for ($i = 1; $i <= 1; $i++) {
            if ($i == 1) {
                $loop_for_stage_flag = I_B_Approval_Pending_Admin_Stage; //scrutiny pending
            }
            else if ($i == 2) {
                $loop_for_stage_flag = I_B_Defected_Stage;//defect notified
            }
            $scrutiny_result = $this->Default_model->get_efiled_nums_stage_wise_list_admin_cron($loop_for_stage_flag, ADMIN_FOR_TYPE_ID, ADMIN_FOR_ID, $diary_no);
            if ($scrutiny_result) {
                $case_chunks=array_chunk($scrutiny_result, 100);

                foreach($case_chunks as $index=>$chunk){
                    $data = $this->efiling_webservices->get_new_case_efiling_scrutiny_cron_SCIS($chunk);
                    
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

                                // if (!empty($response->verification_date) || (isset($response->fil_trap_data[0]) && $response->fil_trap_data[0]->remarks=='SCR -> CAT') || (isset($response->fil_trap_data[0]) && $response->fil_trap_data[0]->remarks=='CAT -> IB-Ex') || (isset($response->fil_trap_data[0]) && $response->fil_trap_data[0]->remarks=='AUTO -> CAT') || (isset($response->fil_trap_data[0]) && $response->fil_trap_data[0]->remarks=='CAT -> TAG')) {
                                if ($response->verification_date != '' || (isset($response->fil_trap_data[0]) && ($response->fil_trap_data[0]->remarks=='SCR -> CAT' || $response->fil_trap_data[0]->remarks=='CAT -> IB-Ex' || $response->fil_trap_data[0]->remarks=='AUTO -> CAT' || $response->fil_trap_data[0]->remarks=='CAT -> TAG' || $response->fil_trap_data[0]->remarks=='PBPS -> CAT' || $response->fil_trap_data[0]->remarks=='VRFY -> IB-Ex' || $response->fil_trap_data[0]->remarks=='TAG -> VRFY' || $response->fil_trap_data[0]->remarks=='CAT -> VRFY' || $response->fil_trap_data[0]->remarks=='SCR -> PBPS'))) {

                                    $reg_no_display = $response->reg_no_display;
                                    $reg_date = $response->registration_date;
                                    if ($reg_date == NULL ||$reg_date == '0000-00-00 00:00:00') {
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
                                } else {
                                   

                                    $stage_update_timestamp = $this->Get_CIS_Status_model->get_stage_update_timestamp($response->registration_id, $loop_for_stage_flag);
                                    if ($response->objection_flag == 'Y') {
                                        $filTrapRemarks= isset($response->fil_trap_data[0]->remarks) ? $response->fil_trap_data[0]->remarks : '';
                                        $filTrapDispDt= isset($response->fil_trap_data[0]->disp_dt) ? $response->fil_trap_data[0]->disp_dt : '';
                                        if ($stage_update_timestamp[0]['activated_on'] >= $response->last_defect_notified_date && $filTrapRemarks!='FDR -> AOR') {
                                            echo "eFiling No. " . $response->efiling_no . " action is still pending for scrutiny.";
                                            continue;
                                        }

                                        $cis_objections = $response->objections;
                                        $objections_status = $this->get_icmis_objections_status($response->registration_id, $cis_objections, $curr_dt);
                                        if((isset($objections_status[0]) && $objections_status[0] == TRUE && ($objections_status[1] || $objections_status[2]))){
                                            $next_stage = I_B_Defected_Stage;
                                        }
                                    } else {
                                        $next_stage = I_B_Approval_Pending_Admin_Stage;
                                    }
                                }
                                echo "<br><br>" . "Reg id " . $response->registration_id;
                                echo "<br>" . "next stage " . $next_stage;
                                echo "<br>" . "curr_dt " . $curr_dt;
                                echo "<br>case details:";
                                print_r($case_details);

                                echo "<br>obj status1 :";
                                print_r(isset($objections_status[1]) ? $objections_status[1] : NULL);
                                echo "<br>obj status2 :";
                                print_r(isset($objections_status[2]) ? $objections_status[2] : NULL);
                                echo "<br>";
                                echo "<br>----------<br>";
                                echo "Objection 1: <br>";
                                var_dump(isset($objections_status[1]) ? $objections_status[1] : NULL);
                                echo "<br>Objection 2: <br>";
                                var_dump(isset($objections_status[2]) ? $objections_status[2] : NULL);
                                $update_status = $this->Default_model->update_icmis_case_status($response->registration_id, $next_stage, $curr_dt, $case_details, isset($objections_status[1]) ? $objections_status[1] : [], isset($objections_status[2]) ? $objections_status[2] : []);

                                if ($update_status) {
                                    if ($next_stage) {
                                        if ($next_stage == I_B_Defected_Stage) {
                                            echo "eFiling No. " . $response->efiling_no . " updated to Defective Stage. <br>";
                                        } elseif ($next_stage == I_B_Approval_Pending_Admin_Stage) {
                                            echo "eFiling No. " . $response->efiling_no . " updated to Pending Scrutiny Stage.<br>";
                                        } elseif ($next_stage == E_Filed_Stage) {
                                            echo "eFiling No. " . $response->efiling_no . " updated to Efiled Stage.<br>";
                                        }
                                    }
                                }
                            }


                        }


                    } else {
                        echo "No Records Found1";
                    } 
                } 
            } else {
                echo "No Records Found for scrutiny";
            }
        }
        $this->Default_model->updateCronDetails($cronDetailsId);
        die;
    }

    function get_icmis_documents_status($registration_id, $cis_documents, $ia_only, $check_verify, $curr_dt) {

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

            $verified_on_date = ($doc->verified_on == '0000-00-00 00:00:00' || $doc->verified_on == NULL) ? NULL : $doc->verified_on;
            $disposal_date = ($doc->dispose_date == '0000-00-00' || $doc->dispose_date ==  NULL) ? NULL : $doc->dispose_date;

            if ($check_verify) {
                if (($doc->verified_on == '0000-00-00 00:00:00' || $doc->verified_on == NULL) && $documents_pending == FALSE) {
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
            $key = !empty($old_objections) ? array_keys(array_column($old_objections, 'obj_id'), $cis_objection->org_id) : '';
            $key_remarks =!empty($old_objections) ? array_keys(array_column($old_objections, 'remarks'), $cis_objection->remark) : '';
            $obj_removed_date = ($cis_objection->rm_dt == '0000-00-00 00:00:00' || $cis_objection->rm_dt == NULL) ? NULL : $cis_objection->rm_dt;

            if (($cis_objection->rm_dt == '0000-00-00 00:00:00' || $cis_objection->rm_dt == NULL) && $objections_pending == FALSE) {
                $objections_pending = TRUE;
            }

            if ($key && $key_remarks) {

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
?>
