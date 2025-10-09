<?php

namespace App\Controllers\History;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\History\HistoryModel;
use App\Models\MiscellaneousDocs\GetDetailsModel;
use App\Models\UploadDocuments\UploadDocsModel;
use App\Models\NewCase\ViewModel;
use App\Models\Caveat\ViewModel as CaveatViewModel;

class EfiledCase extends BaseController
{

    protected $History_model;
    protected $Common_model;
    protected $Get_details_model;
    protected $UploadDocsModel;
    protected $ViewModel;
    protected $CaveatViewModel;

    public function __construct()
    {
        parent::__construct();
        $this->History_model = new HistoryModel();
        $this->Common_model = new CommonModel();
        $this->Get_details_model = new GetDetailsModel();
        $this->UploadDocsModel = new UploadDocsModel();
        $this->ViewModel = new ViewModel();
        $this->CaveatViewModel = new CaveatViewModel();
    }

    public function index()
    {
        if (isset($this->session->userdata['login']['id'])) {
            return redirect()->to(base_url('/'));
        }
        // unset($_SESSION['breadcrumb_enable']);
    }

    public function view()
    {
        if (getSessionData('login')['ref_m_usertype_id'] == USER_PDE || getSessionData('login')['ref_m_usertype_id'] == USER_IN_PERSON || getSessionData('login')['ref_m_usertype_id'] == USER_ADVOCATE || getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN || getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN_READ_ONLY || getSessionData('login')['ref_m_usertype_id'] == USER_EFILING_ADMIN || getSessionData('login')['ref_m_usertype_id'] == USER_ACTION_ADMIN || getSessionData('login')['ref_m_usertype_id'] == USER_CLERK || getSessionData('login')['ref_m_usertype_id'] == USER_DEPARTMENT || getSessionData('login')['ref_m_usertype_id'] == AMICUS_CURIAE_USER) {
            if (isset(getSessionData('efiling_details')['registration_id'])) {
                $regid = getSessionData('efiling_details')['registration_id'];
                $type = getSessionData('efiling_details')['ref_m_efiled_type_id'];
                $remark = $this->History_model->get_intials_defects_for_history($regid);
                if ($type == E_FILING_TYPE_MISC_DOCS || $type == E_FILING_TYPE_IA || $type == E_FILING_TYPE_DEFICIT_COURT_FEE) {
                    // $cnr_details = $this->Common_model->get_CNR_Details($regid);
                    $case_details = $this->Get_details_model->get_case_details($regid);
                    if ($type == E_FILING_TYPE_MISC_DOCS || $type == E_FILING_TYPE_IA) {
                        $uploaded_docs = $this->Common_model->get_uploaded_documents_efile_history($regid);
                        $uploaded_docs_name = $this->UploadDocsModel->get_uploaded_pdfs($regid);
                    }
                    $created_by = getSessionData('efiling_details')['created_by'];
                } elseif ($type == E_FILING_TYPE_NEW_CASE) {
                    $result = $this->History_model->get_new_case_details($regid);
                    // $uploaded_docs = $this->UploadDocsModel->get_uploaded_pdfs($regid);
                    $uploaded_docs = $this->Common_model->get_uploaded_documents_efile_history($regid);
                    $uploaded_docs_name = $this->UploadDocsModel->get_uploaded_pdfs($regid);
                    $created_by = $result[0]['created_by'];
                } elseif ($type ==  E_FILING_TYPE_CAVEAT) {
                    $uploaded_docs = $this->CaveatViewModel->get_caveat_index_items_list($regid);
                    $uploaded_docs_name = $this->UploadDocsModel->get_uploaded_pdfs($regid);
                    $created_by = getSessionData('efiling_details')['created_by'];
                }
                $efiled_by_user = $this->History_model->get_efiled_by_user($created_by);
                $stage = $this->History_model->get_stages($regid);
                $payment_details = $this->History_model->get_court_fee_payment_history($regid);
                $allocation_details = $this->History_model->get_allocated_history($regid);
                return render('history.efiled_history', @compact('remark', 'case_details', 'uploaded_docs', 'uploaded_docs_name', 'result', 'efiled_by_user', 'stage', 'payment_details', 'allocation_details'));
            } else {
                return redirect()->to(base_url('/'));
            }
        } else {
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    public function user_info($user_id, $user_type)
    {
        if (getSessionData('login')['ref_m_usertype_id'] == USER_IN_PERSON || getSessionData('login')['ref_m_usertype_id'] == USER_ADVOCATE || getSessionData('login')['ref_m_usertype_id'] == USER_MASTER_ADMIN || getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN || getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN_READ_ONLY  || getSessionData('login')['ref_m_usertype_id'] == USER_ACTION_ADMIN || getSessionData('login')['ref_m_usertype_id'] == AMICUS_CURIAE_USER) {
            $user_id = url_decryption($user_id);
            $user_type = url_decryption($user_type);
            if ($user_type != USER_IN_PERSON && $user_type != USER_ADVOCATE) {
                $this->session->setFlashdata('err_msg', 'Invalid Id !');
                return redirect()->to(base_url('admin/new_active_user'));
                exit(0);
            }
            if ($user_id) {
                if (!preg_match("/^[0-9]*$/", $user_id) || (strlen($user_id) > INTEGER_FIELD_LENGTH)) {
                    $this->session->setFlashdata('err_msg', 'Please choose valid advocate !');
                    return redirect()->to(base_url('admin/new_active_user'));
                    exit(0);
                }
            } else {
                $this->session->setFlashdata('err_msg', 'Please choose valid advocate !');
                return redirect()->to(base_url('admin/new_active_user'));
                exit(0);
            }
            $data['advocate_info'] = $this->History_model->get_efiled_by_user($user_id);
            $this->load->view('templates/admin_header');
            $this->load->view('history/advocate_info', $data);
            $this->load->view('templates/footer');
        } else {
            return redirect()->to(base_url('/'));
        }
    }

    public function showPdfDoc($pdf_id)
    {
        if ($this->session->userdata['login']) {
            $pdf_id = url_decryption(escape_data($pdf_id));
            if (filter_var($pdf_id, FILTER_VALIDATE_INT)) {
                $data['pdf'] = $this->History_model->getPdfDoc($pdf_id, getSessionData('login')['id']);
                if (empty($data['pdf'])) {
                    $data['msg'] = 'You are not allowed';
                    $this->load->view('history/showPdfDoc_view', $data);
                } else {
                    $this->load->view('history/showPdfDoc_view', $data);
                }
            } else {
                $data['msg'] = 'You are not allowed';
                $this->load->view('history/showPdfDoc_view', $data);
            }
        } else {
            return redirect()->to(base_url('/'));
        }
    }

    public function show_pdf_to_admin($pdf_id)
    {
        if ($this->session->userdata['login']) {
            $pdf_id = url_decryption(escape_data($pdf_id));
            if (filter_var($pdf_id, FILTER_VALIDATE_INT)) {
                $data['pdf'] = $this->History_model->get_pdf_for_admin($pdf_id);
                if (empty($data['pdf'])) {
                    $data['msg'] = 'You are not allowed';
                    $this->load->view('new_case_file/showPdfDoc_view', $data);
                } else {
                    $this->load->view('history/showPdfDoc_view', $data);
                }
            } else {
                $data['msg'] = 'You are not allowed';
                $this->load->view('history/showPdfDoc_view', $data);
            }
        } else {
            return redirect()->to(base_url('/'));
        }
    }
}
