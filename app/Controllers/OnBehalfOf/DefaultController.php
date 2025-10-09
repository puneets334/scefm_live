<?php

namespace App\Controllers\OnBehalfOf;

use App\Controllers\BaseController;
use App\Models\OnBehalfOf\OnBehalfOfModel;
use App\Models\Clerk\ClerkModel;

class DefaultController extends BaseController
{

    protected $OnBehalfOfModel;
    protected $validation;
    protected $request;
    protected $Clerk_model;

    public function __construct() {
        parent::__construct();
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else{
            is_user_status();
        }
        error_reporting(0);
        //$this->load->model('miscellaneous_docs/Get_details_model');
        $this->OnBehalfOfModel = new OnBehalfOfModel();
        $this->validation = \Config\Services::validation();
        $this->request = \Config\Services::request();
        $this->Clerk_model = new ClerkModel();
    }

    public function index() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT,AMICUS_CURIAE_USER);
        if (empty(getSessionData('login')) || !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('/'));
        }
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!empty(getSessionData('efiling_details')) && !in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
            if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
                return redirect()->to(base_url('miscellaneous_docs/view'));
            } elseif (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
                return redirect()->to(base_url('IA/view'));
            }
        }
        if (isset(getSessionData('efiling_details')['registration_id']) && !empty(getSessionData('efiling_details')['registration_id'])) {
            $registration_id = getSessionData('efiling_details')['registration_id'];
            $data['parties_details'] = $this->OnBehalfOfModel->get_case_parties_list($registration_id);
            $data['appearing_for_details'] = $this->OnBehalfOfModel->get_appearing_for_details($registration_id, $_SESSION['login']['id']);
            if($_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
                $data['clerk_aor_details'] = $this->Clerk_model->get_clerk_aor_details($registration_id);
            }
            $this->render('on_behalf_of.on_behalf_of_view', $data);
        } else {
            return redirect()->to(base_url('miscellaneous_docs/view'));
        }
    }

    public function save_filing_for() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT,AMICUS_CURIAE_USER);
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            echo '2@@@' . htmlentities('Unauthorised Access!', ENT_QUOTES);
            exit(0);
        }
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!empty(getSessionData('efiling_details')) && !in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
            echo '2@@@' . htmlentities('Unauthorised Access!', ENT_QUOTES);
            exit(0);
        }
        $this->validation->setRules([
            "selected_party" => [
                "label" => "Select Party",
                "rules" => "required|trim|validate_encrypted_value"
            ]
        ]);
        //    if ($this->validation->withRequest($this->request)->run() === FALSE) {
        //         $data = [
        //             'validation' => $this->validator,
        //         ];
        //     }
        // $this->form_validation->set_error_delimiters('<br/>', '');
        // if (!$this->form_validation->run()) {
        //     echo '3@@@';
        //     echo form_error('party_name[]') . form_error('selected_party[]');
        //     exit(0);
        // }
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $parties_selected = [];
        $parties_selected = $_POST['selected_party'];
        /*if(count($parties_selected)<=0){
            echo "2@@@Some Error ! Select at least one party for whome filing.";
            exit(0);
        }*/
        $filing_for = NULL;        
        foreach ($parties_selected as $seleced_party) {
            $selected_parties_details = url_decryption($seleced_party);
            $selected_parties_details = explode('$$$', $selected_parties_details);
            if(count($selected_parties_details) != 3){
                exit(0);
            }
            $parties_sr_no = $selected_parties_details[1];
            $filing_for .= $parties_sr_no . '$$';            
            $party_type = $selected_parties_details[2];
        }
        $is_govt_filing=!empty(trim($this->request->getPost("is_govt_filing"))) ? 1 : 0;
        if($_SESSION['login']['ref_m_usertype_id'] == USER_CLERK){
            $clerk_aor_details=$this->Clerk_model->get_clerk_aor_details($registration_id);
            if(isset($clerk_aor_details) && !empty($clerk_aor_details) && $clerk_aor_details[0]['ref_department_id']==1) {
                $is_govt_filing=$is_govt_filing;
            } else{
                $is_govt_filing=0;
            }
        }
        $update_filing_for_detail = array(
            'filing_for_parties' => rtrim($filing_for, '$$'),
            'p_r_type' => $party_type,
            'updated_by' => $_SESSION['login']['id'],
            'updated_by_ip' => getClientIP(),
            'updated_on' => date('Y-m-d H:i:s'),
            'is_govt_filing' => $is_govt_filing
        );
        $update_status = $this->OnBehalfOfModel->update_filing_for($update_filing_for_detail, $registration_id);
        if ($update_status) {
            if($update_filing_for_detail['is_govt_filing']==1)
                $_SESSION['efiling_details']['doc_govt_filing']=1;
            else
                $_SESSION['efiling_details']['doc_govt_filing']=0;
            echo "1@@@Filing for updated successfully.";
        } else {
            echo "2@@@Some Error ! Please try after some time.";
        }           
    }

}