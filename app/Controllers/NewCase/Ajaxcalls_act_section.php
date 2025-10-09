<?php
namespace App\Controllers;

class Ajaxcalls_act_section extends BaseController {

    public function __construct() {
        parent::__construct();

        $this->load->model('newcase/Act_sections_model');
    }


    function get_act_sections_list() {

        $registration_id = $_SESSION['efiling_details']['registration_id'];

        if (!empty($registration_id)) {
            $act_sections_list = $this->Act_sections_model->get_act_sections_list($registration_id);
            //echo "<pre>"; print_r($act_sections_list); die;
        } else {
            $act_sections_list = NULL;
        }
        $i = 1;
        $act_section_data .= '<div class="col-md-12 col-sm-12 col-xs-12"> 
          <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
          <thead>
            <tr class="success">
                <th width="3%">#</th>
                    <th>Title</th>
                    <th width="10%">Index</th>
                    <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>';
        if (!empty($act_sections_list)) {

            $indx = '';
            foreach ($act_sections_list as $act_list) {
    /*Changes Started on 11 September 2020*/

                /*$act_section_data .= '<tr>
                        <td>' . $i++ . '</td>
                        <td>' . $act_list->act_name . ' (' . $act_list->act_year . ')</td>
                        <td width="9%">' . $act_list->act_section . '</td>
                        <td width="10%"> <a onclick = "delete_act_section(' . "'" . escape_data(url_encryption(trim($act_list->id))) . "'" . ')"class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a></td>
                  </tr>';*/

                if($act_list->is_approved=='Y')
                {
                    $act_section_data .= '<tr>
                        <td>' . $i++ . '</td>
                        <td>' . $act_list->act_name . ' (' . $act_list->act_year . ')</td>
                        <td width="9%">' . $act_list->act_section . '</td>
                        <td width="10%"> <a onclick = "delete_act_section(' . "'" . escape_data(url_encryption(trim($act_list->id))) . "'" . ')"class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a></td>
                  </tr>';

                }
                else
                {
                    $actYear = !empty($act_list->act_year) ? '(' . $act_list->act_year . ')' : '';

                    $act_section_data .= '<tr>
                        <td>' . $i++ . '</td>
                        <td>' . $act_list->act_name . $actYear .'<b>'."--Pending for Approval".'</b></td>
                        <td width="9%">' . $act_list->act_section . '</td>
                        <td width="10%"> <a onclick = "delete_act_section(' . "'" . escape_data(url_encryption(trim($act_list->id))) . "'" . ')"class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a></td>
                  </tr>';
                }

                /* end of changes*/
            }
        } else {
            $act_section_data .= '<tr><td colspan="4" class="text-center">No record found.</td></tr>';
        }
        $act_section_data .= '</tbody></table></div>';

        echo $act_section_data;
    }

    public function delete() {

        $delete_id = url_decryption($_POST['delete_id']);

        if (empty($delete_id)) {

            $_SESSION['MSG'] = message_show("fail", "Invalid ID."); // this msg is not being displayed. checking required
            redirect('dashboard');
            exit(0);
        }

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('adminDashboard');
            exit(0);
        }

        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            redirect('dashboard');
            exit(0);
        }

        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {

            $registration_id = $_SESSION['efiling_details']['registration_id'];

            $delete_status = $this->Act_sections_model->delete_act_sections($registration_id, $delete_id);

            if ($delete_status) {
                reset_affirmation($registration_id);
                echo '2@@@ Act - Section deleted successfully';
            } else {
                echo '1@@@ Some error ! Please Try again.';
            }
        }
    }


    /**changes done on 05 September 2020*/
    public function decryptActId()
    {
        $act_id = escape_data(url_decryption($this->input->post("act_id")));
        echo $act_id;
        return;
    }
    /*End of changes*/


    public function get_master_acts_list() {
        $actArr= trim($_POST['actArr']);
        $actArrData=explode('@@@',$actArr);

        $act_id = url_decryption($actArrData[1]);
        $act_name = trim($actArrData[2]);
        $act_year = trim($actArrData[3]);
        $acts_list = $this->Act_sections_model->get_master_acts_list();
        $dropDownOptions = '';
        $dropDownOptions .= '<option selected="selected" style="display:none;" value="' . escape_data(url_encryption(trim($act_id))) . '">' . escape_data(strtoupper($act_name . ' (' . $act_year . ')')).'</option>';
        foreach ($acts_list as $dataRes) {
            $sel = ($dataRes->act_id== $act_id) ? 'selected=selected' : '';
            $dropDownOptions .= '<option '.$sel.'  value="' . escape_data(url_encryption(trim($dataRes->act_id))) . '">' . escape_data(strtoupper($dataRes->act_name . ' (' . $dataRes->act_year . ')')).'</option>';
        }
        echo '2@@@'.$dropDownOptions.'@@@'.$actArr;
        // echo $dropDownOptions;
    }
    public function approve_act_section(){

        $this->form_validation->set_rules('master_act_id', 'Act id', 'required|trim|validate_encrypted_value');
        $this->form_validation->set_error_delimiters('<br/>', '');


        if (!$this->form_validation->run()) {
            echo '3@@@';
            echo form_error('master_act_id'); exit(0);

        }
        $master_act_id = escape_data(url_decryption($this->input->post("master_act_id")));

        if (!empty($master_act_id) && $master_act_id != null){
            $curr_dt_time = date('Y-m-d H:i:s');
            $master_act_section = array(
                'is_approved' => 'Y'
            );
            //echo '<pre>';print_r($master_act_section);echo '</pre>';exit();
            $act_id_created = $this->Act_sections_model->update_master_acts($master_act_section, $master_act_id);
            if ($act_id_created) {

                echo '2@@@' . htmlentities('Act - Section approved successfully', ENT_QUOTES) . '@@@' . base_url('newcase/actSections');

            }

         } else {
                echo '1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
                }

    }
    public function update_act_section()
    {
        $case_acts_id = escape_data(url_decryption($this->input->post("case_acts")));
        $act_id = escape_data(url_decryption($this->input->post("act_id")));
        $id_approval = escape_data(url_decryption($this->input->post("id_approval")));

        $act_section = strtoupper(escape_data($this->input->post("section_1")));
        if (isset($_POST['section_2']) && !empty($_POST['section_2'])) {
            $act_section .= '(' . strtoupper(escape_data($this->input->post("section_2"))) . ')';
        }
        if (isset($_POST['section_3']) && !empty($_POST['section_3'])) {
            $act_section .= '(' . strtoupper(escape_data($this->input->post("section_3"))) . ')';
        }
        if (isset($_POST['section_4']) && !empty($_POST['section_4'])) {
            $act_section .= '(' . strtoupper(escape_data($this->input->post("section_4"))) . ')';
        }

        $curr_dt_time = date('Y-m-d H:i:s');

        if (!empty($id_approval) && $id_approval != null && $case_acts_id !=null) {
            $curr_dt_time = date('Y-m-d H:i:s');
            $act_sections_details = array(
                'act_id' =>$case_acts_id,
                'act_section' => $act_section,
            );
           //echo '<pre>';print_r($act_sections_details);echo '</pre>';exit();
            $this->Act_sections_model->update_case_act_sections($act_sections_details, $id_approval);

             echo $result='2@@@' . htmlentities('Act - Section Updated successfully', ENT_QUOTES) . '@@@' . base_url('newcase/actSections');
           // return ($result); exit();

        } else {
            echo $result='1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
            return ($result); exit();
            exit();
        }

    }
}
