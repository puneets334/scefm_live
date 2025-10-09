<?php

namespace App\Controllers\Caveat;

use App\Controllers\BaseController;
use App\Models\Caveat\ViewModel;
use App\Models\UploadDocuments\UploadDocsModel;
use App\Models\Common\CommonModel;
use App\Models\NewCase\GetDetailsModel;
use App\Libraries\webservices\Efiling_webservices;
use TCPDF;

class View extends BaseController
{

    protected $View_model;
    protected $UploadDocs_model;
    protected $Caveator_model;
    protected $GetDetailsModel;
    protected $Common_model;
    protected $request;
    protected $validation;
    protected $session;
    protected $efiling_webservices;

    public function __construct()
    {
        parent::__construct();
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $this->request = \Config\Services::request();
        $this->View_model = new ViewModel();
        $this->UploadDocs_model = new UploadDocsModel();
        $this->GetDetailsModel = new GetDetailsModel();
        $this->Common_model = new CommonModel();
        $this->efiling_webservices = new Efiling_webservices();
    }

    public function index()
    {
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $this->check_caveator_info();
        if (!empty(getSessionData('efiling_details')) && (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT)) {
            $registration_id = getSessionData('efiling_details')['registration_id'];
        } else {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
        $data['efiling_civil_data'] = $this->View_model->get_efiling_civil_details($registration_id);
        $session = session();
        $session->set([
            'breadcrumb_enable' => [
                'efiling_type' => $data['efiling_civil_data'][0]['ref_m_efiled_type_id']
            ]
        ]);
        $data['extra_party_details'] = $this->View_model->get_extra_party_preview_details($registration_id);
        $data['uploaded_docs'] = $this->UploadDocs_model->get_uploaded_pdfs($registration_id);
        $subordinate_data = $this->View_model->get_sub_qj_hc_court_details($registration_id);
        $creaedBy = !empty($data['efiling_civil_data'][0]['created_by']) ? $data['efiling_civil_data'][0]['created_by'] : NULL;
        if (isset($creaedBy) && !empty($creaedBy)) {
            $params = array();
            $params['table_name'] = 'efil.tbl_users';
            $params['whereFieldName'] = 'id';
            $params['whereFieldValue'] = (int)$creaedBy;
            $params['is_active'] = '1';
            $userData = $this->Common_model->getData($params);
            if (isset($userData) && !empty($userData)) {
                $adv_sci_bar_id = !empty($userData[0]->adv_sci_bar_id) ? (int)$userData[0]->adv_sci_bar_id : NULL;
                if (isset($adv_sci_bar_id) && !empty($adv_sci_bar_id)) {
                    $getBarData = $this->Common_model->getBarDetailsById($adv_sci_bar_id);
                    $data['filedByData'] = !empty($getBarData) ?  $getBarData[0] : NULL;
                }
            }
        }
        $data['subordinate_court_details'] = $this->GetDetailsModel->get_subordinate_court_details($registration_id);
        $data['efiled_docs_list'] = $this->View_model->get_caveat_index_items_list($registration_id);
        $data['payment_details'] = $this->View_model->get_payment_details($registration_id);
        return $this->render('caveat.caveat_view', $data);
    }

    function check_caveator_info()
    {
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        if (isset(getSessionData('efiling_for_details')['case_type_pet_title']) && !empty(getSessionData('efiling_for_details')['case_type_pet_title'])) {
            $case_type_pet_title = htmlentities(getSessionData('efiling_for_details')['case_type_pet_title'], ENT_QUOTES);
        } else {
            $case_type_pet_title = htmlentities('Caveator', ENT_QUOTES);
        }
        if (isset($_SESSION['efiling_details']) && $_SESSION['efiling_details'] != '' && !in_array(CAVEAT_BREAD_CAVEATOR, explode(',', $_SESSION['efiling_details']['breadcrumb_status']))) {
            $_SESSION['MSG'] = message_show("fail", 'Please complete <b>" ' . $case_type_pet_title . ' "</b> section.');
            return redirect()->to(base_url('caveat/caveator'));
            exit(0);
        }
    }

    public function pdf()
    {
        $this->check_caveator_info();
        if (isset($_SESSION['efiling_details']) && !empty($_SESSION['efiling_details']) && ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT || $_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_CDE)) {
            $registration_id = $_SESSION['efiling_details']['registration_id'];
        } else {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
        $data['efiling_civil_data'] = $this->View_model->get_efiling_civil_details($registration_id);
        $_SESSION['breadcrumb_enable']['efiling_type'] = $data['efiling_civil_data'][0]['ref_m_efiled_type_id'];
        $data['efiling_civil_master_data'] = $this->View_model->get_efiling_civil_master_value($registration_id);
        $data['efiled_efiling_details'] = $this->View_model->get_efiled_efiling_details($registration_id);
        $data['payment_details'] = $this->View_model->get_payment_details($registration_id);
        $data['extra_party_details'] = $this->View_model->get_extra_party_preview_details($registration_id);
        $data['subordinate_court_data'] = $this->View_model->get_sub_qj_hc_court_details($registration_id);
        $data['org_list'] = $this->efiling_webservices->getOrgname($_SESSION['estab_details']['est_code'], $_SESSION['estab_details']['efiling_for_type_id'], $_SESSION['estab_details']['state_code'], $_SESSION['estab_details']['state_code']);
        $data['objection_list'] = $this->efiling_webservices->getObjection($_SESSION['estab_details']['est_code'], $_SESSION['estab_details']['efiling_for_type_id'], $_SESSION['estab_details']['state_code'], $_SESSION['estab_details']['state_code']);
        $content = '<style>' . file_get_contents(base_url('assets/vendors/bootstrap/dist/css/bootstrap.min.css')) . '</style>';
        $content = '<style>' . file_get_contents(base_url('assets/vendors/font-awesome/css/font-awesome.min.css')) . '</style>';
        $content = $this->load->view('caveat/caveat_details_pdf', $data, TRUE);
        ob_start();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(FALSE);
        $pdf->SetPrintFooter(TRUE);
        $pdf->SetAuthor('eCommittee, SCI');
        $pdf->SetTitle('eCommittee, SCI');
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->SetFont('helvetica', '', 8, '', true);
        $pdf->AddPage();
        $output_file_name = "CDENO_" . $_SESSION['efiling_details']['efiling_no'] . ".pdf";
        $pdf->writeHTML($content . '', true, false, false, false, '');
        $pdf->lastPage();
        ob_end_clean();
        $pdf->Output($output_file_name, 'I');
    }

    public function doc()
    {
        $this->check_caveator_info();
        if (isset($_SESSION['efiling_details']) && !empty($_SESSION['efiling_details']) && ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT || $_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_CDE)) {
            $registration_id = $_SESSION['efiling_details']['registration_id'];
        } else {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
        $data['efiling_civil_data'] = $this->View_model->get_efiling_civil_details($registration_id);
        $_SESSION['breadcrumb_enable']['efiling_type'] = $data['efiling_civil_data'][0]['ref_m_efiled_type_id'];
        $data['efiling_civil_master_data'] = $this->View_model->get_efiling_civil_master_value($registration_id);
        $data['efiled_efiling_details'] = $this->View_model->get_efiled_efiling_details($registration_id);
        $data['extra_party_details'] = $this->View_model->get_extra_party_preview_details($registration_id);
        $data['subordinate_court_data'] = $this->View_model->get_sub_qj_hc_court_details($registration_id);
        $data['efiled_docs_list'] = $this->View_model->get_index_items_list($registration_id);
        $data['payment_details'] = $this->View_model->get_payment_details($registration_id);
        $content = '<style>' . file_get_contents(base_url('assets/vendors/bootstrap/dist/css/bootstrap.min.css')) . '</style>';
        $content = '<style>' . file_get_contents(base_url('assets/vendors/font-awesome/css/font-awesome.min.css')) . '</style>';
        $content = $this->load->view('caveat/caveat_details_doc', $data, TRUE);
        header("Content-Type: application/vnd.docx");
        header("Content-Disposition: attachment; filename=efiling\"" . $_SESSION['efiling_details']['efiling_no'] . ".docx");
        echo $content;
    }
}
