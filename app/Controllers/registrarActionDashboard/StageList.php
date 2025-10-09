<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class StageList extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //$this->load->model('dashboard/Dashboard_model');
        $this->load->model('adminDashboard/StageList_model');
        
        unset($_SESSION['efiling_details']);
        unset($_SESSION['estab_details']);
        unset($_SESSION['case_table_ids']);

        unset($_SESSION['search_case_data']);

        unset($_SESSION['form_data']);
        unset($_SESSION['efiling_user_detail']);
        unset($_SESSION['pdf_signed_details']);
        unset($_SESSION['matter_type']);
        unset($_SESSION['crt_fee_and_esign_add']);
        unset($_SESSION['mobile_no_for_updation']);
        unset($_SESSION['email_id_for_updation']);
        unset($_SESSION['search_key']);
    }
    
    public function _remap($param = NULL) {

        if ($param == 'index') {
            $this->index(NULL);
        } else {
            $this->index($param);
        }
    }

    public function index($stages) {        
        
        $users_array = array(USER_ADMIN);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $users_array)) {
            redirect('login');
            exit(0);
        }

        $stages = url_decryption($stages); 
        if (!preg_match("/^[0-9]*$/", $stages)) {
            redirect('dashboard');
            exit(0);
        }

        if ($stages != '') {
            $data['stages'] = $stages;
            if ($stages == New_Filing_Stage) {
                $data['tabs_heading'] = "New Filing";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Submitted On', 'Action');
                $data['result'] = $this->StageList_model->get_efilied_nums_stage_wise_list_admin(array(New_Filing_Stage), $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
                //echo "<pre>"; print_r($data['result']); echo "</pre>";
            }
            if ($stages == DEFICIT_COURT_FEE) {
                $data['tabs_heading'] = " Pay Deficit Fee";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Updated On');
                $data['result'] = $this->StageList_model->get_efilied_nums_stage_wise_list_admin(array(DEFICIT_COURT_FEE), $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
            }
            if ($stages == Initial_Defected_Stage) {
                $data['tabs_heading'] = "Initially Defective";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Defect Raised On');
                $data['result'] = $this->StageList_model->get_efilied_nums_stage_wise_list_admin(array(Initial_Defected_Stage), $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
            }
            if ($stages == Transfer_to_CIS_Stage) {
                $data['tabs_heading'] = "Get ICMIS Status";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Updated On', 'Action');
                $data['result'] = $this->StageList_model->get_efilied_nums_stage_wise_list_admin(array(Transfer_to_CIS_Stage), $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
            }
            if ($stages == Get_From_CIS_Stage) {
                $data['tabs_heading'] = "Get From ICMIS";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Updated On', 'Action');
                $data['result'] = $this->StageList_model->get_efilied_nums_stage_wise_list_admin(array(Get_From_CIS_Stage), $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
            }
            if ($stages == Initial_Defects_Cured_Stage) {
                $data['tabs_heading'] = "Complied Objections";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Complied On', 'Action');
                $data['result'] = $this->StageList_model->get_efilied_nums_stage_wise_list_admin(array(Initial_Defects_Cured_Stage, DEFICIT_COURT_FEE_PAID), $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
            }
            if ($stages == Transfer_to_IB_Stage) {
                $data['tabs_heading'] = "Transfer to ICMIS";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Updated On', 'Action');
                $data['result'] = $this->StageList_model->get_efilied_nums_stage_wise_list_admin(array(Transfer_to_IB_Stage), $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
            }
            if ($stages == I_B_Approval_Pending_Admin_Stage) {
                $data['tabs_heading'] = "Pending Scrutiny";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Updated On', 'Action');
                $data['result'] = $this->StageList_model->get_efilied_nums_stage_wise_list_admin(array(I_B_Approval_Pending_Admin_Stage), $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);                
            }
            if ($stages == I_B_Defected_Stage) {
                $data['tabs_heading'] = "Waiting Defects To be Cured";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Defect Raised On');
                $data['result'] = $this->StageList_model->get_efilied_nums_stage_wise_list_admin(array(I_B_Defected_Stage), $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
            }
            if ($stages == I_B_Rejected_Stage) {
                $data['tabs_heading'] = "Rejected E-Filing No's";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Rejected On', 'Rejected From');
                $data['result'] = $this->StageList_model->get_efilied_nums_stage_wise_list_admin(array(I_B_Rejected_Stage, E_REJECTED_STAGE), $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
            }
            if ($stages == I_B_Defects_Cured_Stage) {
                $data['tabs_heading'] = "Defects Cured";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Cured On', 'Check Status');
                $data['result'] = $this->StageList_model->get_efilied_nums_stage_wise_list_admin(array(I_B_Defects_Cured_Stage), $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
            }

            if ($stages == E_Filed_Stage) {
                if(ENABLE_EFILING && ENABLE_CASE_DATA_ENTRY){
                    $data['tabs_heading'] = "eFiled Cases And Accepted CDE";
                    $lbl_efiling_no = 'eFiling/CDE No.';
                }elseif (ENABLE_EFILING) {
                    $data['tabs_heading'] = "E-Filled Cases";
                    $lbl_efiling_no = 'eFiling No.';
                }elseif (ENABLE_CASE_DATA_ENTRY) {
                    $data['tabs_heading'] = "Accepted CDE";
                    $lbl_efiling_no = 'CDE No.';
                }
                
                $data['tab_head'] = array('#', $lbl_efiling_no, 'Type', 'Case Details', 'Updated on');
                //$data['result'] = $this->StageList_model->get_efiled_list_admin(array(E_Filed_Stage, CDE_ACCEPTED_STAGE), array(E_FILING_TYPE_NEW_CASE, E_FILING_TYPE_CDE), $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
            }

            if ($stages == Document_E_Filed) {
                $data['tabs_heading'] = "E-Filled Documents";
                $data['tab_head'] = array('#', 'eFiling No.', 'Case Details', 'Updated On');
                //$data['result'] = $this->StageList_model->get_efiled_list_admin(array($stages), array(E_FILING_TYPE_MISC_DOCS), $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
            }
            if ($stages == DEFICIT_COURT_FEE_E_FILED) {
                $data['tabs_heading'] = "Paid Deficit Fee";
                $data['tab_head'] = array('#', 'eFiling No.', 'Case Details', 'Updated On');
                //$data['result'] = $this->StageList_model->get_efiled_list_admin(array($stages), array(E_FILING_TYPE_DEFICIT_COURT_FEE), $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
            }

            if ($stages == LODGING_STAGE || $stages == DELETE_AND_LODGING_STAGE) {
                $data['tabs_heading'] = "Idle/Unprocessed e-Filing";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Status', 'Updated On');
                //$data['result'] = $this->StageList_model->get_efilied_nums_stage_wise_list_admin(array(LODGING_STAGE, DELETE_AND_LODGING_STAGE), $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
            }
            if ($stages == IA_E_Filed) {
                $data['tabs_heading'] = "E-Filled IA";
                $data['tab_head'] = array('#', 'eFiling No.', 'Case Details', 'Updated On');
                //$data['result'] = $this->StageList_model->get_efiled_list_admin(array($stages), array(E_FILING_TYPE_IA), $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
            }

            $this->load->view('templates/admin_header');
            $this->load->view('adminDashboard/admin_stage_list_view', $data);
            $this->load->view('templates/footer');
        } else {
            redirect('adminDashboard');
            exit(0);
        }
    }
    
    
}

?>