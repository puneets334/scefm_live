<?php

namespace App\Controllers\IA;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\IA\ViewModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\Affirmation\AffirmationModel;
use App\Models\UploadDocuments\UploadDocsModel;
use App\Models\IA\GetDetailsModel;

class View extends BaseController
{
    protected $CommonModel;
    protected $ViewModel;
    protected $GetDetailsModel;
    protected $AffirmationModel;
    protected $UploadDocsModel;
    protected $efiling_webservices;

    public function __construct()
    {
        parent::__construct();
        if (empty($this->session->get('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $this->CommonModel = new CommonModel();
        $this->ViewModel = new ViewModel();
        $this->GetDetailsModel = new GetDetailsModel();
        $this->AffirmationModel = new AffirmationModel();
        $this->UploadDocsModel = new UploadDocsModel();
        $this->efiling_webservices = new Efiling_webservices();
    }

    function index()
    {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_ADMIN, USER_ADMIN_READ_ONLY, USER_EFILING_ADMIN, USER_CLERK,AMICUS_CURIAE_USER);
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return response()->redirect(base_url('/')); 
        }
        if (isset(getSessionData('efiling_details')['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {
            $registration_id = getSessionData('efiling_details')['registration_id'];
            // $this->CommonModel->get_efiling_num_basic_Details($registration_id);
            $data['case_details'] = $this->GetDetailsModel->get_case_details($registration_id);
            $diary_number = '';
            $diary_year = '';
            $data['case_listing_details'] = '';
            if (!empty($data['case_details'])) {
                $diary_number = $data['case_details'][0]['diary_no'];
                $diary_year = $data['case_details'][0]['diary_year'];
                if (!empty($diary_number)) {
                    $data['case_listing_details'] = $this->efiling_webservices->getCaseListedDetail($diary_number, $diary_year);
                    $data['details'] = $this->efiling_webservices->getCISData($diary_number . $diary_year);
                }
            }
            $data['filing_for_details'] = $this->ViewModel->get_filing_for_parties($registration_id);
            $data['efiled_docs_list'] = $this->ViewModel->get_index_items_list($registration_id);
            $data['payment_details'] = $this->ViewModel->get_payment_details($registration_id);
            $data['uploaded_docs'] = $this->UploadDocsModel->get_uploaded_pdfs($registration_id);
            $data['esigned_docs_details'] = $this->AffirmationModel->get_esign_doc_details($registration_id);
            $creaedBy = !empty($data['case_details'][0]['created_by']) ? $data['case_details'][0]['created_by'] : NULL;
            if (isset($creaedBy) && !empty($creaedBy)) {
                $params = array();
                $params['table_name'] = 'efil.tbl_users';
                $params['whereFieldName'] = 'id';
                $params['whereFieldValue'] = (int)$creaedBy;
                $params['is_active'] = '1';
                $userData = $this->CommonModel->getData($params);
                if (isset($userData) && !empty($userData)) {
                    $adv_sci_bar_id = !empty($userData[0]->adv_sci_bar_id) ? (int)$userData[0]->adv_sci_bar_id : NULL;
                    if (isset($adv_sci_bar_id) && !empty($adv_sci_bar_id)) {
                        $getBarData = $this->CommonModel->getBarDetailsById($adv_sci_bar_id);
                        $data['filedByData'] = !empty($getBarData) ?  $getBarData[0] : NULL;
                    }
                }
            }
            return $this->render('IA.ia_preview', $data);
            /*return render('IA/ia_preview');
            $this->load->view('templates/header');
            $this->load->view('IA/ia_preview', $data); 
            $this->load->view('templates/footer');*/
        } else {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        /* $data['other_data_applying'] = $this->GetDetailsModel->ia_other_data($registration_id, 1, E_FILING_TYPE_MISC_DOCS);
        $data['uploaded_docs'] = $this->GetDetailsModel->get_uploaded_documents($registration_id);
        $data['payment_details'] = $this->GetDetailsModel->get_total_payment_detail($registration_id);
        $data['certificate_adv'] = $this->GetDetailsModel->get_esign_doc_adv($registration_id);
        $data['certificate_pet'] = $this->GetDetailsModel->get_esign_doc_pet($registration_id);
        $data['verified_docs'] = $this->GetDetailsModel->get_everified_doc($registration_id);
        $data['efiled_by_user'] = $this->GetDetailsModel->get_efiled_by_user($data['result'][0]['created_by']);
        $efiling_submitted_on = $this->GetDetailsModel->get_submitted_on($registration_id);
        $data['submitted_on'] = $efiling_submitted_on[0]['activated_on'];
        if (!empty($data['certificate_adv'])) {
            $data['certificate'] = $data['certificate_adv'];
        } else {
            $data['certificate'] = $data['certificate_pet'];
        } */
    }

}