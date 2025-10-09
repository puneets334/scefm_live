<?php
namespace App\Controllers\Cron;
use App\Controllers\BaseController;

use App\Libraries\webservices\Efiling_webservices;
use App\Models\Cron\DefaultModel;
use App\Models\GetCISStatus\GetCISStatusModel;
use App\Models\AdminDashboard\StageListModel;

class Isdisposed extends BaseController {

    protected $Default_model;
    protected $StageList_model;
    protected $Get_CIS_Status_model;
    protected $efiling_webservices; 

    public function __construct() {
        // Not for Live
        exit('Not for Live');
        parent::__construct();
        $this->Default_model = new DefaultModel(); 
        $this->StageList_model = new StageListModel(); 
        $this->Get_CIS_Status_model = new GetCISStatusModel(); 
        $this->efiling_webservices = new Efiling_webservices();
    }

    public function index()
    {
        // Not for Live
        exit('Not for Live');
        $loop_for_stage_flag = [I_B_Defected_Stage]; //Defects Cured stage
        $scrutiny_result = $this->Default_model->get_efiled_nums_stage_wise_list_admin_cronIsDisposed($loop_for_stage_flag, env('ADMIN_FOR_TYPE_ID'), env('ADMIN_FOR_ID'));

        if ($scrutiny_result) {
            $case_chunks=array_chunk($scrutiny_result, 20);
            foreach($case_chunks as $index=>$chunk) {
                $data = $this->efiling_webservices->get_new_case_efilingIsDisposed($chunk);
                if ($data) {

                    $curr_dt = date('Y-m-d H:i:s');
                    foreach ($data->consumed_data as $response) {
                        if ($response->status == 'A' && (strtoupper($response->c_status) == 'D')) {
                            echo '<pre>';print_r($response);//exit();
                            $objections_status = NULL;
                            $documents_status = NULL;
                            $next_stage = E_Filed_Stage;
                            $d_no = explode('/', $response->diary_no);
                            $diary_no = $d_no[0];
                            $diary_year = $d_no[1];
                            $diary_date = $response->diary_generated_on;
                            $case_details[0] = array(
                                'registration_id' => $response->registration_id,
                                'updated_by' => env('AUTO_UPDATE_CRON_USER'),
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
                            echo "<br>case details:<pre>";
                            print_r($case_details);

                            echo "<br>obj insert status1 <pre>:";
                            print_r($objections_status[1]);
                            echo "<br>obj update status2 <pre>:";
                            print_r($objections_status[2]);
                            echo "<br>";
                            echo "<br>----------<br>";


                            $update_status = $this->Default_model->update_icmis_case_status($response->registration_id, $next_stage, $curr_dt, $case_details, $objections_status[1], $objections_status[2]);
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
            }
        } else {
            echo "No Records Found for scrutiny";
        }

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
                    'updated_by' => env('AUTO_UPDATE_CRON_USER'),
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
                        'created_by' => env('AUTO_UPDATE_CRON_USER'),
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
