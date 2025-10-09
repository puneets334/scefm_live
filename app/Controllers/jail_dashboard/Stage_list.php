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

        $users_array = array(JAIL_SUPERINTENDENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $users_array)) {
            redirect('login');
            exit(0);
        }

        $stages = url_decryption($stages);
        if (!preg_match("/^[0-9]*$/", $stages)) {
            redirect('jail_dashboard');
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

            if ($stages == E_Filed_Stage) {
                
                $data['tabs_heading'] = "eFiled Cases";
                $data['tab_head'] = array('#', 'eFiling No.', 'Case Details', 'Updated on');
                //$data['result'] = $this->Stageslist_model->get_efilied_nums_submitted_list(array(E_Filed_Stage, CDE_ACCEPTED_STAGE), array(E_FILING_TYPE_NEW_CASE, E_FILING_TYPE_CDE), $created_by);
                $data['result'] = $this->Stageslist_model->get_efilied_nums_stage_wise_list(array(E_Filed_Stage, CDE_ACCEPTED_STAGE),  $created_by);
            }

            if ($stages == I_B_Rejected_Stage) {

                $data['tabs_heading'] = "Rejected No.(s)";
                $data['tab_head'] = array('#', 'eFiling No.', 'Type', 'Case Details', 'Updated On', 'Rejected From');
                $data['result'] = $this->Stageslist_model->get_efilied_nums_stage_wise_list(array(I_B_Rejected_Stage, E_REJECTED_STAGE, CDE_REJECTED_STAGE), $created_by);
            }

            $this->load->view('templates/jail_header');
            $this->load->view('jail_dashboard/user_stage_list_view', $data);
            $this->load->view('templates/footer');
        } else {
            $_SESSION['MSG'] = message_show("fail", "Invalid attempt!");
            redirect('dashboard');
            exit(0);
        }
    }
}

?>