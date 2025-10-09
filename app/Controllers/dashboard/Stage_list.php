<?php
namespace App\Controllers;

class Stage_list extends BaseController {

    public function __construct() {

        parent::__construct();
        
        $this->load->model('common/Common_model');
        $this->load->model('dashboard/Stageslist_model');

        unset($_SESSION['efiling_details']);
        unset($_SESSION['estab_details']);
    }

    public function _remap($param = NULL) {

        if ($param == 'index') {
            $this->index(NULL);
        } else {
            $this->index($param);
        }
    }

    public function index($stages) {

        $users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT, USER_PDE);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $users_array)) {
            redirect('login');
            exit(0);
        }

        $stages = url_decryption($stages);
        if (!preg_match("/^[0-9]*$/", $stages)) {
            redirect('dashboard');
            exit(0);
        }
        
        //$data['remarks'] = $this->Common_model->get_intials_defects_remarks($_SESSION['efiling_details']['registration_id'], $stages);
        
        $created_by = $this->session->userdata['login']['id'];
        
        if ($stages != '') {
            $data['stages'] = $stages;
            
            if ($stages == Draft_Stage) {

                $data['tabs_heading'] = "Draft";                
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Created On', 'Action');
                $data['result'] = $this->Stageslist_model->get_efilied_nums_stage_wise_list(array($stages), $created_by);
            }
            if ($stages == Initial_Approaval_Pending_Stage) {

                $data['tabs_heading'] = "Pending Approval";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Submitted On');
                $data['result'] = $this->Stageslist_model->get_efilied_nums_stage_wise_list(array(Initial_Approaval_Pending_Stage, Initial_Defects_Cured_Stage, DEFICIT_COURT_FEE_PAID), $created_by);
            }
            if ($stages == Initial_Defected_Stage) {

                $data['tabs_heading'] = "Waiting Re-submit";                
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Defect Raised On', 'Action');
                $data['result'] = $this->Stageslist_model->get_efilied_nums_stage_wise_list(array($stages), $created_by);
            }
            if ($stages == Initial_Approved_Stage) {
                
                $data['tabs_heading'] = "Waiting Payment";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Accepted On', 'Action');
                $data['result'] = $this->Stageslist_model->get_efilied_nums_stage_wise_list(array($stages), $created_by);
            }
            if ($stages == Pending_Payment_Acceptance) {
                
                $data['tabs_heading'] = "Pending Payment Acceptance";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Updated On');
                $data['result'] = $this->Stageslist_model->get_efilied_nums_stage_wise_list(array($stages), $created_by);
            }
            if ($stages == I_B_Approval_Pending_Stage) {

                $data['tabs_heading'] = "Pending Scrutiny";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Updated On');
                $data['result'] = $this->Stageslist_model->get_efilied_nums_stage_wise_list(array(I_B_Approval_Pending_Stage, Get_From_CIS_Stage, Transfer_to_IB_Stage, I_B_Approval_Pending_Admin_Stage, I_B_Defects_Cured_Stage), $created_by);
            }
            if ($stages == I_B_Defected_Stage) {
                
                $data['tabs_heading'] = "Waiting Defects to be Cured";                
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Defect Raised On', 'Action');
                $data['result'] = $this->Stageslist_model->get_efilied_nums_stage_wise_list(array($stages), $created_by);
            }

            if ($stages == E_Filed_Stage) {
                
                $data['tabs_heading'] = "eFiled Cases";
                $data['tab_head'] = array('#', 'eFiling No.', 'Case Details', 'Updated on');
                //$data['result'] = $this->Stageslist_model->get_efilied_nums_submitted_list(array(E_Filed_Stage, CDE_ACCEPTED_STAGE), array(E_FILING_TYPE_NEW_CASE, E_FILING_TYPE_CDE), $created_by);
                $data['result'] = $this->Stageslist_model->get_efilied_nums_stage_wise_list(array(E_Filed_Stage, CDE_ACCEPTED_STAGE),  $created_by);
            }
            if ($stages == Document_E_Filed) {
                
                $data['tabs_heading'] = "E-Filed Document";                
                $data['tab_head'] = array('#', 'eFiling No.', 'Case Details', 'Updated On');
                //$data['result'] = $this->Stageslist_model->get_efilied_nums_submitted_list(array(Document_E_Filed), array(E_FILING_TYPE_MISC_DOCS), $created_by);
                $data['result'] = $this->Stageslist_model->get_efilied_nums_stage_wise_list(array(Document_E_Filed), $created_by);
            }

            if ($stages == DEFICIT_COURT_FEE_E_FILED) {
                
                $data['tabs_heading'] = "Paid Deficit Fee";
                $data['tab_head'] = array('#', 'eFiling No.', 'Case Details', 'Updated On');
                //$data['result'] = $this->Stageslist_model->get_efilied_nums_submitted_list(array(DEFICIT_COURT_FEE_E_FILED), array(E_FILING_TYPE_DEFICIT_COURT_FEE), $created_by);
                $data['result'] = $this->Stageslist_model->get_efilied_nums_stage_wise_list(array(DEFICIT_COURT_FEE_E_FILED), $created_by);
            }

            if ($stages == I_B_Rejected_Stage) {

                $data['tabs_heading'] = "Rejected No.(s)";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Updated On', 'Rejected From');
                $data['result'] = $this->Stageslist_model->get_efilied_nums_stage_wise_list(array(I_B_Rejected_Stage, E_REJECTED_STAGE, CDE_REJECTED_STAGE), $created_by);
            }
            if ($stages == DEFICIT_COURT_FEE) {
                
                $data['tabs_heading'] = "Deficit Fee";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Accepted On', 'Action');
                $data['result'] = $this->Stageslist_model->get_efilied_nums_stage_wise_list(array($stages), $created_by);
            }
            if ($stages == LODGING_STAGE || $stages == DELETE_AND_LODGING_STAGE || $stages == TRASH_STAGE) {

                $data['tabs_heading'] = "Trashed No.(s)";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Status', 'Updated On');
                $data['result'] = $this->Stageslist_model->get_efilied_nums_stage_wise_list(array(LODGING_STAGE, DELETE_AND_LODGING_STAGE, TRASH_STAGE), $created_by);
            }
            if ($stages == IA_E_Filed) {
                
                $data['tabs_heading'] = "E-Filed IA";
                $data['tab_head'] = array('#', 'eFiling No.', 'Case Details', 'Updated On');
                //$data['result'] = $this->Stageslist_model->get_efilied_nums_submitted_list(array(IA_E_Filed), array(E_FILING_TYPE_IA), $created_by);
                $data['result'] = $this->Stageslist_model->get_efilied_nums_stage_wise_list(array(IA_E_Filed), $created_by);
            }
            
            if ($stages == MENTIONING_E_FILED) {
                
                $data['tabs_heading'] = "E-Mentioning";
                $data['tab_head'] = array('#', 'eFiling No.', 'Case Details', 'Updated On');
                //$data['result'] = $this->Stageslist_model->get_efilied_nums_submitted_list(array(MENTIONING_E_FILED), array(E_FILING_TYPE_MENTIONING), $created_by);
                $data['result'] = $this->Stageslist_model->get_efilied_nums_stage_wise_list(array(MENTIONING_E_FILED), $created_by);
            }

            $this->load->view('templates/header');
            $this->load->view('dashboard/user_stage_list_view', $data);
            $this->load->view('templates/footer');
        } else {
            $_SESSION['MSG'] = message_show("fail", "Invalid attempt!");
            redirect('dashboard');
            exit(0);
        }
    }
}

?>