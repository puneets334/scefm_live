<?php

namespace App\Controllers\Caveat;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\NewCase\DropdownListModel;
use App\Models\Caveat\SubordinateCourtModel;
use App\Models\Clerk\ClerkModel;

class DefaultController extends BaseController {

    protected $session;
    protected $db;
    protected $Common_model;
    protected $Dropdown_list_model;
    protected $Subordinate_court_model;
    protected $Clerk_model;

    public function __construct() {
        $this->session = \Config\Services::session();
        parent::__construct();
        if (empty($this->session->get('login'))) {
            return response()->redirect(base_url('/'));
            exit(0);
        } else {
            is_user_status();
        }
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT, USER_ADMIN, USER_ADMIN_READ_ONLY, USER_EFILING_ADMIN, SR_ADVOCATE, ARGUING_COUNSEL);
        if (!in_array($this->session->get('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return response()->redirect(base_url('/'));
            // exit(0);
        }
        $this->Dropdown_list_model = new DropdownListModel();
        $this->Subordinate_court_model = new SubordinateCourtModel();
        $this->Common_model = new CommonModel();
        $this->Clerk_model = new ClerkModel();
    }

    public function index() {
        $allowedUsers = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        $data = array();
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowedUsers)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $allowed_stages = array(Draft_Stage, Initial_Defected_Stage, E_REJECTED_STAGE);
        if (!empty($_SESSION['efiling_details']['stage_id']) && !in_array($_SESSION['efiling_details']['stage_id'], $allowed_stages)) {
            return redirect()->to(base_url('caveat/view'));
            exit(0);
        }
        if(!empty(getSessionData('efiling_details')['registration_id'])){
            $registration_id = (int)getSessionData('efiling_details')['registration_id'];
            if ($_SESSION['efiling_details']['stage_id'] == Initial_Defected_Stage || $_SESSION['efiling_details']['stage_id'] == I_B_Defected_Stage || $_SESSION['efiling_details']['stage_id'] == Draft_Stage) {
                $this->Subordinate_court_model->remove_breadcrumb($registration_id,CAVEAT_BREAD_VIEW);
            }
            $arr = array();
            $arr['registration_id'] = $registration_id;
            $arr['step']= 1;
            $response = $this->Common_model->getCaveatDataByRegistrationId($arr);
            $state_id = isset($response[0]['state_id']) ? (int)$response[0]['state_id'] : null;

            if(isset($response) && !empty($response)){
                $state_id = !empty($response[0]['state_id']) ? (int)$response[0]['state_id'] : NULL;
                $data['district_list'] = $this->Dropdown_list_model->get_districts_list($state_id);
            }
            $data['caveator_details'] = $response;
            if($_SESSION['login']['ref_m_usertype_id'] == USER_CLERK){
                $data['selected_aor'] = $this->Clerk_model->selected_aor_for_case($registration_id);
            }
        }
        if($_SESSION['login']['ref_m_usertype_id'] == USER_CLERK ){
            $data['clerk_aor'] = $this->Clerk_model->get_clerk_aor($_SESSION['login']['id']);
        }
        // $data['state_list'] = $this->Dropdown_list_model->get_states_list();
        $data['state_list'] = $this->Dropdown_list_model->get_address_state_list();
        $data['sc_case_type'] = $this->Dropdown_list_model->getSciCaseTypeOrderById();
        if(!empty($response[0]['orgid']) && $response[0]['orgid'] !='I'){
            $party_is = trim($response[0]['orgid']);
            $data['dept_list'] = $this->Dropdown_list_model->get_departments_list($party_is);
            $data['post_list'] = $this->Dropdown_list_model->get_posts_list();
        }
        $data['case_type_pet_title'] = "Caveator";
        $this->render('caveat.caveat_view', $data);
    }

    public function processing($id = NULL) {
        if ($id !== NULL) {
            unset($_SESSION['estab_details']);
            $id = url_decryption($id);
            $InputArray = explode('#', $id); // 0=>registration_id, 1=>type, 2=>stage
            if (empty($InputArray[0]) && empty($InputArray[1]) && empty($InputArray[2])) {
                return redirect()->to(base_url('/'));
            }
            $registration_id = $InputArray[0];
            $efiling_details = getSessionData('efiling_details') ?? [];
            $efiling_details['registration_id'] = $registration_id;
            $session = session();
            setSessionData('efiling_details', $efiling_details);
            $this->Common_model->get_efiling_num_basic_details(trim($InputArray[0]));
            $this->Common_model->get_establishment_details();
            $allowed_stages = [Draft_Stage, Initial_Defected_Stage, E_REJECTED_STAGE];
            if (isset(getSessionData('efiling_details')['stage_id']) && !empty(getSessionData('efiling_details')['stage_id']) && !in_array(getSessionData('efiling_details')['stage_id'], $allowed_stages)) {
                return redirect()->to(base_url('caveat/view'));
            }
        } else {
            if (isset($_SESSION['efiling_for_details']) && !empty($_SESSION['efiling_for_details'])) {
                $this->Common_model->get_establishment_details($_SESSION['efiling_for_details']['efiling_for_type_id'], $_SESSION['efiling_for_details']['efiling_for_id']);
                return redirect()->to(base_url('caveat/caveator'));
                exit(0);
            } else {
                return redirect()->to(base_url('whereToFile/caveat'));
                exit(0);
            }
        }        
        $allowed_admin_users = array(USER_ADMIN, USER_ACTION_ADMIN,USER_ADMIN_READ_ONLY,USER_EFILING_ADMIN);
        $allowed_users = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if ((in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_admin_users) || (!empty($_SESSION['efiling_details']['stage_id']))) && (!in_array($_SESSION['efiling_details']['stage_id'], $allowed_stages))) {
            return redirect()->to(base_url('caveat/view'));
            exit(0);
        } else if(in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users) || in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_admin_users)){
            switch (max(explode(',', $_SESSION['efiling_details']['breadcrumb_status']))) {
                case CAVEAT_BREAD_CAVEATOR:
                   return redirect()->to(base_url('caveat'));
                   break;
                case CAVEAT_BREAD_CAVEATEE:
                    return redirect()->to(base_url('caveat/caveatee'));
                    break;
                case CAVEAT_BREAD_EXTRA_PARTY:
                    return redirect()->to(base_url('caveat/extra_party'));
                    break;
                case CAVEAT_BREAD_SUBORDINATE_COURT:
                    return redirect()->to(base_url('caveat/subordinate_court'));
                    break;
                case CAVEAT_UPLOAD_DOCUMENT:
                    return redirect()->to(base_url('uploadDocuments'));
                    break;
                case CAVEAT_BREAD_DOC_INDEX:
                    return redirect()->to(base_url('documentIndex'));
                    break;
                case CAVEAT_BREAD_COURT_FEE:
                    return redirect()->to(base_url('newcase/courtFee'));
                    break;
                case CAVEAT_BREAD_VIEW:
                    return redirect()->to(base_url('caveat/view'));
                    break;
                default:
                    return redirect()->to(base_url('caveat'));
            }
        } else {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
    }

}