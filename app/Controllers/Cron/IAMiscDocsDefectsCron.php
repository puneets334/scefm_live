<?php
namespace App\Controllers\Cron;
use App\Controllers\BaseController;

use App\Libraries\webservices\Efiling_webservices;
use App\Models\Cron\AiMiscDeftectsCronModel;
use App\Models\getAiMiscDefectsCisStatus\GetAiMiscDefectsCisStatusModel; 
use App\Models\AdminDashboard\StageListModel;

class IAMiscDocsDefectsCron extends BaseController {

    protected $AiMiscDeftectsCron_model;
    protected $StageList_model;
    protected $Get_AiMiscDefects_CIS_Status_model;
    protected $efiling_webservices; 


    public function __construct() {

        parent::__construct();

        $this->AiMiscDeftectsCron_model = new AiMiscDeftectsCronModel(); 
        $this->Get_AiMiscDefects_CIS_Status_model = new GetAiMiscDefectsCisStatusModel();
        $this->StageList_model = new StageListModel();
        $this->efiling_webservices = new Efiling_webservices();
    }
    public function index($efiling_no=null,$date=null)
    {
        $date=date('Y-m-d');
        if (isset($_GET['efiling_no']) && !empty($_GET['efiling_no'])){
            $efiling_no=$_GET['efiling_no'];
        }
        if (isset($_GET['date']) && !empty($_GET['date'])){
            $date=$_GET['date'];
        }

        $cronDetails = [
            "cron_type"  => 'ia_docs_MiscDocs',
            'started_at' => date('Y-m-d H:i:s')
        ];

        $cronDetailsId=$this->AiMiscDeftectsCron_model->insertInDBwithInsertedId('efil.tbl_cron_details',$cronDetails);

        $efiling_nosdata['efiling_no']=$efiling_no;
        $efiling_nosdata['date']=$date;
        $data = $this->efiling_webservices->BulkGetIaMiscScrutinyDefects($efiling_nosdata);

                        if (isset($data) && isset($data->consumed_data) && !empty($data->consumed_data)) {
                            $curr_dt = date('Y-m-d H:i:s');
                            foreach ($data->consumed_data as $response) {
                                if ($response->objection_flag == 'Y') {
                                    $stage_registration_data = $this->Get_AiMiscDefects_CIS_Status_model->get_ai_misc_stage_and_registration_id($response->efiling_no);
                                    if (!empty($stage_registration_data)) {
                                        echo '<pre>';print_r($response);
                                         $registration_id=$stage_registration_data[0]['registration_id'];
                                         $stage_id=$stage_registration_data[0]['stage_id'];
                                        echo 'SC-eFM Efiling Details :<pre>'; print_r($stage_registration_data);
                                        $objections_status = [];
                                        $next_stage = 0;
                                        $d_no = explode('/', $response->diary_no);
                                        $diary_no = $d_no[0];
                                        $diary_year = $d_no[1];
                                        $diary_date = $response->diary_generated_on;

                                        $case_details[0] = [
                                            'registration_id' => $registration_id,
                                            'updated_by' => env('AUTO_UPDATE_CRON_USER'),
                                            'updated_on' => $curr_dt,
                                            'updated_by_ip' => getClientIP(),
                                        ];

                                        $stage_update_timestamp = $this->Get_AiMiscDefects_CIS_Status_model->get_ai_misc_stage_update_timestamp($registration_id, $stage_id);

                                        if (isset($response->objection_flag) && $response->objection_flag == 'Y') {
                                            $cis_objections = $response->objections;
                                            $objections_status = $this->get_icmis_ai_misc_objections_status($registration_id, $cis_objections, $curr_dt);
                                            if ($objections_status[0] == TRUE && ($objections_status[1] || $objections_status[2]) && $objections_status[3] == 0) {
                                                $next_stage = I_B_Defected_Stage;
                                            } elseif ($objections_status[3] == E_Filed_Stage) {
                                                $next_stage = E_Filed_Stage;
                                            }
                                        } else {
                                            $next_stage =$stage_id;  // Document_E_Filed; // 14=> Misc. Document E-Filed or IA both are same stage
                                        }

                                        echo "<br><br>" . "Reg id " . $registration_id;
                                        echo "<br>" . "next stage " . $next_stage;
                                        echo "<br>" . "curr_dt " . $curr_dt;
                                        echo "<br>case details:";
                                        echo '<pre>';
                                        print_r($case_details);


                                        echo "Objection 1: <br>";
                                        print_r($objections_status[1]);
                                        echo "<br>Objection 2: <br>";
                                        print_r($objections_status[2]);

                                        $update_status = $this->AiMiscDeftectsCron_model->update_ai_misc_icmis_case_status($registration_id, $next_stage, $curr_dt, $case_details, $objections_status[1], $objections_status[2]);
                                        if ($update_status) {
                                            if ($next_stage) {
                                                if ($next_stage == I_B_Defected_Stage) {
                                                    echo "eFiling No. " . $response->efiling_no . " updated to Defective Stage.";
                                                } elseif ($next_stage == IA_E_Filed) {
                                                    echo "eFiling No. " . $response->efiling_no . " updated to Pending Scrutiny Stage.";
                                                } elseif ($next_stage == E_Filed_Stage) {
                                                    echo "eFiling No. " . $response->efiling_no . " updated to Efiled Stage.";
                                                }
                                            }
                                        }
                                    } else {
                                        echo "eFiling No. :$response->efiling_no already updated to Efiled Stage.<br/>";
                                    }
                                }
                            }
                        } else {
                            echo "No Records Found<br/>";
                        }

        $this->AiMiscDeftectsCron_model->updateCronDetails($cronDetailsId);
    }

    function get_icmis_documents_status($registration_id, $cis_documents, $ia_only, $check_verify, $curr_dt) {

        $efil_docs = $temp_efil_docs = $this->Get_AiMiscDefects_CIS_Status_model->get_efiled_ai_misc_docs_list($registration_id, $ia_only);
        $i         = 0;
        foreach ($temp_efil_docs as $e_doc) {
            $temp_efil_docs[$i]['doc_id'] = encrypt_doc_id($e_doc['doc_id']);
            $i++;
        }

        $update_documents  = [];
        $documents_pending = FALSE;

        foreach ($cis_documents as $doc) {

            $key = array_keys(array_column($temp_efil_docs, 'doc_id'), $doc->doc_id);
            $verified_on_date = ($doc->verified_on == '0000-00-00 00:00:00') ? NULL : $doc->verified_on;
            $disposal_date    = ($doc->dispose_date == '0000-00-00') ? NULL : $doc->dispose_date;

            if($check_verify) {
                if ($doc->verified_on == '0000-00-00 00:00:00' && $documents_pending == FALSE) {
                    $documents_pending = TRUE;
                }
            }else {
                if ($doc->docnum == NULL) {
                    $documents_pending = TRUE;
                }
            }
            if ($key) {
                $efil_doc_id = $efil_docs[$key[0]]['doc_id'];
                $update_documents[] = [
                    'doc_id' => $efil_doc_id,
                    'icmis_diary_no'     => $doc->diary_no,
                    'icmis_doccode'      => $doc->doccode,
                    'icmis_doccode1'     => $doc->doccode1,
                    'icmis_docnum'       => $doc->docnum,
                    'icmis_docyear'      => $doc->docyear,
                    'icmis_other1'       => $doc->other1,
                    'icmis_iastat'       => $doc->iastat,
                    'icmis_verified'     => $doc->verified,
                    'icmis_verified_on'  => $verified_on_date,
                    'icmis_dispose_date' => $disposal_date,
                    'updated_by'         => $_SESSION['login']['id'],
                    'updated_on'         => $curr_dt,
                    'update_ip_address'  => getClientIP(),
                ];
            }
        }

        return array($documents_pending, $update_documents);
    }

    function get_icmis_ai_misc_objections_status($registration_id, $cis_objections, $curr_dt) {

        $old_objections     = $this->Get_AiMiscDefects_CIS_Status_model->get_icmis_ai_misc_objections_list($registration_id);
        $insert_objections  = [];
        $update_objections  = [];
        $objections_pending = FALSE;
        $AllDefectedMarkedCured = 0;
        $total_count_index=0;
        $total_count_objections=0;
         if (isset($cis_objections) && !empty($cis_objections)){ $total_count_objections=sizeof($cis_objections); }
        foreach ($cis_objections as $cis_objection) {
            $total_count_index++;
            $key              = !empty($old_objections) ? array_keys(array_column($old_objections, 'obj_id'), $cis_objection->org_id) : '';
            $key_remarks      = !empty($old_objections) ? array_keys(array_column($old_objections, 'remarks'), $cis_objection->remark) : '';
            $obj_removed_date = ($cis_objection->rm_dt == '0000-00-00 00:00:00') ? NULL : $cis_objection->rm_dt;

            if ($cis_objection->rm_dt == '0000-00-00 00:00:00' && $objections_pending == FALSE) {
                $objections_pending = TRUE;
            }
            if (!empty($obj_removed_date) && $total_count_index==$total_count_objections){
                $AllDefectedMarkedCured=$total_count_index;
             }
            if ($key && $key_remarks) {
                $efil_obj_id = $old_objections[$key[0]]['id'];
                $update_objections[] = [
                    'id'               => $efil_obj_id,
                    'obj_id'           => $cis_objection->org_id,
                    'remarks'          => $cis_objection->remark,
                    'obj_prepare_date' => $cis_objection->save_dt,
                    'obj_removed_date' => $obj_removed_date,
                    'updated_by'       => env('AUTO_UPDATE_CRON_USER'),
                    'updated_on'       => $curr_dt,
                    'update_ip'        => getClientIP()
                ];
            }else {
                if($obj_removed_date == NULL) {
                    $insert_objections[] = [
                        'registration_id'  => $registration_id,
                        'obj_id'           => $cis_objection->org_id,
                        'remarks'          => $cis_objection->remark,
                        'obj_prepare_date' => $cis_objection->save_dt,
                        'obj_removed_date' => $obj_removed_date,
                        'created_by'       => env('AUTO_UPDATE_CRON_USER'),
                        'created_on'       => $curr_dt,
                        'create_ip'        => getClientIP()
                    ];
                }
            }
        }
        if (!empty($AllDefectedMarkedCured) && $total_count_index==$total_count_objections){
            $AllDefectedMarkedCured=E_Filed_Stage;
        }
        return array($objections_pending, $insert_objections, $update_objections,$AllDefectedMarkedCured);
    }
}
?>
