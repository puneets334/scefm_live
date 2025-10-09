<?php
namespace App\Controllers\CaseDetails;
use App\Controllers\BaseController;
use App\Models\MiscellaneousDocs\GetDetailsModel;


class DefaultController extends BaseController {
protected $GetDetailsModel;
    public function __construct() {
        parent::__construct();
        $this->GetDetailsModel= new GetDetailsModel();
        unset($_SESSION['parties_list']);
    }

    public function index() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK,AMICUS_CURIAE_USER);

        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('dashboard'));
        }

        $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage,CITATION_E_FILED,MENTIONING_E_FILED,CERTIFICATE_E_FILED);
        if (!empty(getSessionData('efiling_details')) && !in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
            return redirect()->to(base_url('miscellaneous_docs/view'));

        }
        if (isset(getSessionData('efiling_details')['registration_id']) && !empty(getSessionData('efiling_details')['registration_id'])) {
            $registration_id = getSessionData('efiling_details')['registration_id'];
            $data['case_details'] = $this->GetDetailsModel->get_case_details($registration_id);
            $this->render('case_details.case_details_view', $data);

        } else {
            return redirect()->to(base_url('miscellaneous_docs/view'));
 }
    }

}

?>
