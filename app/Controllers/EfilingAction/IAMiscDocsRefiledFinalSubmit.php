<?php

namespace App\Controllers\EfilingAction;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\shareDoc\ShareDocsModel;
use App\Models\NewCase\ViewModel;
use App\Libraries\webservices\Efiling_webservices;
use stdClass;

class IAMiscDocsRefiledFinalSubmit extends BaseController
{

    protected $Common_model;
    protected $Share_docs_model;
    protected $efiling_webservices;
    protected $View_model;
    protected $encrypt;

    public function __construct()
    {
        parent::__construct();
        $this->Common_model = new CommonModel();
        $this->Share_docs_model = new ShareDocsModel();
        $this->View_model = new ViewModel();
        $this->efiling_webservices = new Efiling_webservices();
        $this->encrypt = \Config\Services::encrypter();
    }

    public function index()
    {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_DEPARTMENT, AMICUS_CURIAE_USER);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
        if (empty($_SESSION['efiling_details']) || empty($_SESSION['estab_details'])) {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $marked_defect_tobe_shown_stages = array(I_B_Defected_Stage, I_B_Rejected_Stage);
        if (in_array($_SESSION['efiling_details']['stage_id'], $marked_defect_tobe_shown_stages)) {
            $result_icmis = $this->Common_model->get_ia_docs_cis_defects_remarks($registration_id, FALSE);
            if (isset($result_icmis) && !empty($result_icmis)) {
                foreach ($result_icmis as $re) {
                    $aor_cured = (isset($re->aor_cured) && !empty($re->aor_cured)) ? $re->aor_cured : "f";
                    if ($aor_cured == 'f') {
                        $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> Please Mark All Defects Cured Before Final Submit..</div>');
                        return redirect()->to(base_url('documentIndex'));
                        exit();
                    }
                }
                // isRefilingCompulsoryIADefect($_SESSION['efiling_details']['registration_id'], $_SESSION['efiling_details']['stage_id']);
            }
        }
        if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_DEFICIT_COURT_FEE && (bool) $_SESSION['estab_details']['enable_payment_gateway']) {
            $next_stage = Transfer_to_IB_Stage;
        } elseif ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
            $next_stage = Draft_Stage;
        } elseif ($_SESSION['efiling_details']['stage_id'] == Draft_Stage) {
            $next_stage = Initial_Approaval_Pending_Stage;
        } elseif ($_SESSION['efiling_details']['stage_id'] == Initial_Defected_Stage) {
            $next_stage = Initial_Defects_Cured_Stage;
        } elseif ($_SESSION['efiling_details']['stage_id'] == DEFICIT_COURT_FEE) {
            $next_stage = DEFICIT_COURT_FEE_PAID;
        } elseif ($_SESSION['efiling_details']['stage_id'] == I_B_Defected_Stage) {
            $next_stage = I_B_Defects_Cured_Stage;
        } elseif ($_SESSION['efiling_details']['stage_id'] == I_B_Rejected_Stage || $_SESSION['efiling_details']['stage_id'] == E_REJECTED_STAGE) {
            $next_stage = Initial_Defects_Cured_Stage;
        } else {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Invalid Action.</div>');
            return redirect()->to(base_url('dashboard'));
        }
        $result = $this->Common_model->updateCaseStatus($registration_id, $next_stage);
        if ($result) {
            if (!empty($_SESSION['efiling_details']['registration_id'])) {
                // $this->updatecrontableforhashing($registration_id, $_SESSION['efiling_details']['efiling_no']);
                $diary_id = $_SESSION['efiling_details']['diary_no'] . $_SESSION['efiling_details']['diary_year'];
                $efiling_no = $_SESSION['efiling_details']['efiling_no'];
                $response = $this->autoTransferToScrutinyIAMiscDocs($registration_id, $_SESSION['efiling_details']['diary_no'], $_SESSION['efiling_details']['diary_year'], $_SESSION['efiling_details']['efiling_no']); //comment this line to disable auto scrutiny - kbp 02082023
                $response = (array)json_decode($response, true);
                if ($response['status'] == 'success') {
                    $this->session->setFlashdata('msg', '<div style="font-size=24px;font-weight: bolder" class="alert alert-success text-center font-weight-bold"> E-filing number ' . $efiling_no . ' has been re-filed successfully.</div>');
                    $_SESSION['efiling_details']['stage_id'] = Initial_Approaval_Pending_Stage;
                } else {
                    $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center text-bg"> ' . $response['message'] . '!!</div>');
                }
                if ($_SESSION['efiling_details']['efiling_type'] == 'IA') {
                    return redirect()->to(base_url('IA/view'));
                } else {
                    return redirect()->to(base_url('miscellaneous_docs/view'));
                }
                exit(0);
            }
        }
    }

    // Auto Transfer to scrutiny - start
    public function autoTransferToScrutinyIAMiscDocs($registration_id = null, $diary_no = null, $diary_year = null, $efiling_no = null)
    {
        $output = array();
        $registration_id = !empty($registration_id) ? (int)$registration_id : NULL;
        $diary_no = !empty($diary_no) ? (int)$diary_no : NULL;
        $diary_year = !empty($diary_year) ? (int)$diary_year : NULL;
        $efiling_no = !empty($efiling_no) ? $efiling_no : NULL;
        if (isset($registration_id) && !empty($registration_id) && !empty($diary_no) && !empty($diary_year) && !empty($efiling_no)) {
            //go to scrutiny
            $diary_no = !empty($diary_no) ? (int)$diary_no : NULL;
            if (isset($diary_no) && !empty($diary_no)) {
                $diary_id = $diary_no . $diary_year;
                #from AOR to fdr for scrutiny
                $efiling_no = $_SESSION['efiling_details']['efiling_no'];
                $result =  $this->setReturnedByAdvocate($diary_id, $efiling_no, $registration_id);
                if ((isset($result) && !empty($result))) {
                    $next_stage = I_B_Approval_Pending_Admin_Stage;
                    $_SESSION['efiling_details']['stage_id'] = I_B_Approval_Pending_Admin_Stage;
                    //$this->Common_model->updateCaseStatus($registration_id, $next_stage);
                    $update_data = array(
                        'deactivated_on' => date('Y-m-d H:i:s'),
                        'is_active' => FALSE,
                        'updated_by' => $_SESSION['login']['id'],
                        'updated_by_ip' => getClientIP()
                    );
                    $this->db->table('efil.tbl_efiling_num_status');
                    $this->db->WHERE('registration_id', $registration_id);
                    $this->db->WHERE('is_active', TRUE);
                    $this->db->SET($update_data);
                    $this->db->UPDATE();
                    if ($this->db->affected_rows() > 0) {
                        $insert_data = array(
                            'registration_id' => $registration_id,
                            'stage_id' => $next_stage,
                            'activated_on' => date('Y-m-d H:i:s'),
                            'is_active' => TRUE,
                            'activated_by' => $_SESSION['login']['id'],
                            'activated_by_ip' => getClientIP()
                        );
                        $this->db->INSERT('efil.tbl_efiling_num_status', $insert_data);
                    }
                    $output['status'] = "success";
                    log_message('CUSTOM', "File has been transfer to scrutiny.");
                    $output['message'] = "File has been transfer to scrutiny.";
                } else {
                    $output['status'] = "error";
                    log_message('CUSTOM', "Something went wrong while updation of refiled case,Please try again later.");
                    $output['message'] = "Something went wrong,Please try again later.";
                }
            }
        }
        return json_encode($output);
    }

    private function setReturnedByAdvocate($diary_id, $efiling_no, $registration_id = null)
    {
        $result_icmis = $this->Common_model->get_ia_docs_cis_defects_remarks($registration_id, FALSE);
        $registration_id = !empty($registration_id) ? $registration_id : NULL;
        $efiled_docs_list = $this->View_model->get_index_items_list($registration_id, 1);
        $docTempArr = array();
        $output = false;
        if (isset($efiled_docs_list) && !empty($efiled_docs_list)) {
            foreach ($efiled_docs_list as $k => $v) {
                $docTempArr[] = (object)$v;
            }
        }
        $documents = !empty($efiled_docs_list[0]) ? $docTempArr : array(new stdClass());
        if (count($documents) > 0) {
            $created_by = !empty($documents[0]->uploaded_by) ? $documents[0]->uploaded_by : NULL;
        }
        $pet_adv_id = 0;
        if (isset($created_by) && !empty($created_by)) {
            $userData = $this->Common_model->getUserDetailsById($created_by);
            if (isset($userData) && !empty($userData)) {
                $adv_sci_bar_id = !empty($userData[0]['adv_sci_bar_id']) ? (int)$userData[0]['adv_sci_bar_id'] : NULL;
                if (isset($adv_sci_bar_id) && !empty($adv_sci_bar_id)) {
                    $getBarData = $this->Common_model->getBarDetailsById($adv_sci_bar_id);
                    $bar_id = !empty($getBarData[0]['bar_id']) ? $getBarData[0]['bar_id'] : NULL;
                    // $pet_adv_id = !empty($getBarData[0]['pp']) ? PETITIONER_IN_PERSON : $bar_id;
                    // This portion of code is commented on 13052025, as PP field has no significant for AOR or PIP in the BAR table as per ICMIS team
                    $pet_adv_id = !empty($bar_id) ? $bar_id : PETITIONER_IN_PERSON;
                } else {
                    $pet_adv_id = PETITIONER_IN_PERSON;
                }
            }
        }
        $assoc_arr = array('diary_id' => $diary_id, 'efiling_no' => $efiling_no, 'pet_adv_id' => $pet_adv_id, 'documents' => $documents, 'defect_list_icmis' => $result_icmis);
        $assoc_json = json_encode($assoc_arr);
        $key = config('Encryption')->key;
        $encrypted_string = $this->encrypt->encrypt($assoc_json, $key);
        // $key = $this->config->item('encryption_key');
        // $encrypted_string = $this->encrypt->encode($assoc_json, $key);
        $output = $this->efiling_webservices->updateDefectRefiledIAData($encrypted_string);
        return $output;
    }
    // Auto Transfer to scrutiny - end
}
