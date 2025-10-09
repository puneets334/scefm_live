<?php

namespace App\Controllers\Affirmation;

use App\Controllers\BaseController;
use App\Models\Affirmation\AffirmationModel;
use PdfToText;

class DigitalSign extends BaseController {

    protected $Affirmation_model;

    public function __construct() {
        parent::__construct();
        $this->Affirmation_model = new AffirmationModel();
    }

    public function index() {
        return redirect()->to(base_url('affirmation'));
        exit(0);
    }

    public function upload_digitally_signed_doc() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK,JAIL_SUPERINTENDENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            $_SESSION['MSG'] = message_show("fail", 'Unauthorised Access !');
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }
        $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            $_SESSION['MSG'] = message_show("fail", 'Invalid Stage.');
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $efiling_num = $_SESSION['efiling_details']['efiling_no'];
        $est_code = $_SESSION['estab_details']['estab_code'];
        $data['esigned_docs_details'] = $this->Affirmation_model->get_esign_doc_details($registration_id);
        $redirect_url = 'affirmation';
        if (isset($_FILES['adv_oath_esign_pdf']) && !empty($_FILES['adv_oath_esign_pdf']) && isset($registration_id)) {
            if ($msg = isValidPDF('adv_oath_esign_pdf', TRUE)) {
                $this->session->setFlashData('msg', $msg);
                return redirect()->to(base_url($redirect_url));
                exit(0);
            }            
            $pdf 	=  new PdfToText ( $_FILES['adv_oath_esign_pdf']['tmp_name']) ;
            // Commented for demo purpose only.
            if (strpos(str_replace( '-', '', $pdf -> Text), $_SESSION['efiling_details']['efiling_no']) == false) {
                $msg = '<center><p style="background: #f2dede;border: #f2dede;color: black;">Please upload attached Advocate certificate after digitally signing.</p></center>';
                $this->session->setFlashData('msg', $msg);
                return redirect()->to(base_url($redirect_url));
                exit(0);
            }
            $result = isDocumentSigned($_FILES['adv_oath_esign_pdf']['tmp_name'], 'adbe.pkcs7.detached');
            if ($result != TRUE || $result != 1) {
                $msg = '<center><p style="background: #f2dede;border: #f2dede;color: black;">Please upload digitally signed document only!</p></center>';
                $this->session->setFlashData('msg', $msg);
                return redirect()->to(base_url($redirect_url));
                exit(0);
            }
            $signed_pdf_dir = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/signed_pdfs/';
            if (!is_dir($signed_pdf_dir)) {
                $uold = umask(0);
                if (mkdir($signed_pdf_dir, 0777, true)) {
                    $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                    write_file($signed_pdf_dir . 'index.html', $html);
                }
                umask($uold);
            }
            $signed_pdf_file_name = $efiling_num . '-' . date("Ymd") . '-' . date("His") . '_Advocate_SignedCertificate.pdf';
            $signed_pdf_full_path = getcwd() . '/uploaded_docs/' . $est_code . '/' . $efiling_num . '/signed_pdfs/';
            $signed_pdf_partial_path = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/signed_pdfs/';
            $signed_pdf_full_path_with_file_name = $signed_pdf_full_path . $signed_pdf_file_name;
            $signed_pdf_partial_path_with_file_name = $signed_pdf_partial_path . $signed_pdf_file_name;
            $upload_status = move_uploaded_file($_FILES['adv_oath_esign_pdf']['tmp_name'], $signed_pdf_full_path_with_file_name);            
            if ($upload_status) {
                $pet_data = array(
                    'ref_registration_id' => $registration_id,
                    'ref_efiling_no' => $efiling_num,
                    'type' => ESIGNED_DOCS_BY_ADV,
                    'signed_pdf_full_path' => $signed_pdf_full_path,
                    'signed_pdf_full_path_with_file_name' => $signed_pdf_full_path_with_file_name,
                    'signed_pdf_partial_path' => $signed_pdf_partial_path,
                    'signed_pdf_partial_path_with_file_name' => $signed_pdf_partial_path_with_file_name,
                    'signed_pdf_file_name' => $signed_pdf_file_name,
                    'is_data_valid' => TRUE,
                    'signed_type' => 1
                );
                if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
                    $breadcrumbs_to_update = NEW_CASE_AFFIRMATION;
                } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
                    $breadcrumbs_to_update = MISC_BREAD_AFFIRMATION;
                } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
                    $breadcrumbs_to_update = IA_BREAD_AFFIRMATION;
                } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MENTIONING) {
                    $breadcrumbs_to_update = MEN_BREAD_AFFIRMATION;
                } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_JAIL_PETITION) {
                    $breadcrumbs_to_update = JAIL_PETITION_AFFIRMATION;
                }
                $result = $this->Affirmation_model->upload_pet_adv_esign_docs($pet_data, $registration_id, $breadcrumbs_to_update);
                if ($result) {
                    $_SESSION['MSG'] = message_show("success", 'Certificate uploaded successfully !');
                    log_message('CUSTOM', "Certificate uploaded successfully !");
                    return redirect()->to(base_url($redirect_url));
                    exit(0);
                } else{
                    $_SESSION['MSG'] = message_show("fail", 'Failed to save certificate !');
                    log_message('CUSTOM', "Failed to save certificate !");
                    return redirect()->to(base_url($redirect_url));
                    exit(0);
                }
            }
        } else{
            $_SESSION['MSG'] = message_show("fail", 'Failed to upload certificate !');
            log_message('CUSTOM', "Failed to upload certificate !");
            return redirect()->to(base_url($redirect_url));
            exit(0);
        }
    }

}