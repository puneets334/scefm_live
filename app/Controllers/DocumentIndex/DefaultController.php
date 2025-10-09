<?php

namespace App\Controllers\DocumentIndex;

use App\Controllers\BaseController;
use App\Models\DocumentIndex\DocumentIndexDropDownModel;
use App\Models\DocumentIndex\DocumentIndexModel;
use App\Models\DocumentIndex\DocumentIndexSelectModel;
use App\Models\NewCase\GetDetailsModel;
use App\Models\ShcilPayment\PaymentModel;
use App\Models\UploadDocuments\UploadDocsModel;
use GuzzleHttp\Exception\GuzzleException;

class DefaultController extends BaseController
{
    protected $UploadDocs_model;
    protected $DocumentIndex_model;
    protected $DocumentIndex_Select_model;
    protected $DocumentIndex_DropDown_model;
    protected $Payment_model;
    protected $Get_details_model;
    protected $request;
    protected $validation;

    public function __construct()
    {
        parent::__construct();
        $this->UploadDocs_model = new UploadDocsModel();
        $this->DocumentIndex_model = new DocumentIndexModel();
        $this->DocumentIndex_Select_model = new DocumentIndexSelectModel();
        $this->DocumentIndex_DropDown_model = new DocumentIndexDropDownModel();
        $this->Payment_model = new PaymentModel();
        $this->Get_details_model = new GetDetailsModel();
        $this->request = \Config\Services::request();
        $this->validation = \Config\Services::validation();
    }

    public function index($doc_id = NULL)
    {
        $this->check_login();
        //caveat view
        if (check_party() != true) {  //func added on 15 JUN 2021
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Please enter every party details before moving to further tabs.</div>');
            return redirect()->to(base_url('newcase/extra_party'));
        }
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!empty(getSessionData('efiling_details')['stage_id']) && !in_array(getSessionData('efiling_details')['stage_id'], $stages_array) && getSessionData('efiling_details')['efiling_type'] == 'new_case') {
            return redirect()->to(base_url('newcase/view'));
        } else if (!empty(getSessionData('efiling_details')['stage_id']) && !in_array(getSessionData('efiling_details')['stage_id'], $stages_array) && getSessionData('efiling_details')['efiling_type'] == 'CAVEAT') {
            return redirect()->to(base_url('caveat/view'));
        }
        if (isset($doc_id) && !empty($doc_id)) {
            $docd_id = url_decryption($doc_id);
            $data['document_id'] = $docd_id;
        } else {
            $docd_id = null;
        }
        $view_page = '';
        if (!empty(getSessionData('efiling_details')) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
            $view_page = 'newcase.new_case_view';
            $redirectURL = 'newcase/view';
        } elseif (!empty(getSessionData('efiling_details')) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
            $view_page = 'miscellaneous_docs.miscellaneous_docs_view';
            $redirectURL = 'miscellaneous_docs/view';
        } elseif (!empty(getSessionData('efiling_details')) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
            $view_page = 'IA.ia_view';
            $redirectURL = 'IA/view';
        } else if (!empty(getSessionData('efiling_details')) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
            $view_page = 'newcase.new_case_view';
            $redirectURL = 'newcase/view';
        } else if (!empty(getSessionData('efiling_details')) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == OLD_CASES_REFILING) {
            $view_page = 'oldCaseRefiling.old_efiling_view';
            $redirectURL = 'oldCaseRefiling/view';
        }
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!empty(getSessionData('efiling_details')) && !in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
            return redirect()->to(base_url($redirectURL));
        }
        //get total is_dead_minor
        $params = array();
        $params['registration_id'] = !empty(getSessionData('efiling_details')['registration_id']) ? getSessionData('efiling_details')['registration_id'] : NULL;
        $params['is_dead_minor'] = true;
        $params['is_deleted'] = 'false';
        $params['is_dead_file_status'] = 'false';
        $params['total'] = 1;
        $isdeaddata = $this->Get_details_model->getTotalIsDeadMinor($params);
        if (isset($isdeaddata[0]->total) && !empty($isdeaddata[0]->total)) {
            $total = $isdeaddata[0]->total;
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Please fill ' . $total . ' remaining dead/minor party details</div>');
            return redirect()->to(base_url('newcase/lr_party'));
        }
        if (isset(getSessionData('efiling_details')['registration_id']) && !empty(getSessionData('efiling_details')['registration_id'])) {
            $registration_id = getSessionData('efiling_details')['registration_id'];
            $data['index_details'] = $this->DocumentIndex_Select_model->get_index_details($registration_id, $docd_id);
            $data['uploaded_pdf'] = $this->DocumentIndex_DropDown_model->get_uploaded_pdfs($registration_id);
            if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
                $ia_doc_type = 8;
                $data['doc_type'] = $this->DocumentIndex_DropDown_model->get_document_type(NULL);
            } elseif (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
                $data['doc_type'] = $this->DocumentIndex_DropDown_model->get_document_type(NULL);
            } else {
                //TODO update the document upload breadcrumb status(8), when user does not upload any document from upload document tab :27042023
                $breadcrumb_to_update = NEW_CASE_UPLOAD_DOCUMENT;
                if (!empty($data['uploaded_pdf']) && count($data['uploaded_pdf']) > 0) {
                    $update_courtfee_breadcrumb_status = $this->Payment_model->update_breadcrumbs($registration_id, $breadcrumb_to_update);
                } else {
                    $update_courtfee_breadcrumb_status = $this->Payment_model->remove_breadcrumb($registration_id, $breadcrumb_to_update);
                }
                $data['doc_type'] = $this->DocumentIndex_DropDown_model->get_document_type(NULL);
            }
            if ($data['index_details'] != '' && count($data['index_details']) > 0) {
                $data['doc_res'] = $this->DocumentIndex_DropDown_model->get_sub_document_type($data['index_details'][0]['doc_type_id']);
            } else {
                $data['doc_res'] = [];
            }
            if (isset($_REQUEST['pspdfkitdocumentid']) && !empty($_REQUEST['pspdfkitdocumentid'])) {
                $data['selected_pspdfkit_document_id'] = $_REQUEST['pspdfkitdocumentid'];
            }
            $data['uploaded_docs'] = $this->UploadDocs_model->get_uploaded_pdfs($registration_id);
        }
        return $this->render($view_page, $data);
    }

    function add_index_item($index_id = null)
    {
        if (isset($index_id) && !empty($index_id)) {
            $index_id = url_decryption($index_id);
            if (!$index_id) {
                $_SESSION['MSG'] = $this->session->setFlashdata("fail", "Invalid ID.");
                return redirect()->to(base_url('documentIndex/defaultController/index'));
            }
        }
        $this->check_login();
        $registration_id = getSessionData('efiling_details')['registration_id'];
        if ((!empty($registration_id)) && (!empty(getSessionData('efiling_details')['ref_m_efiled_type_id']))  && (!empty($_POST['caveatDocNum']) && ($_POST['caveatDocNum'] == 2)) && (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT)) {
            $efiled_docs_list = $this->DocumentIndex_Select_model->get_index_items_list($registration_id);
            if ((isset($efiled_docs_list)) && (!empty($efiled_docs_list)) && (count($efiled_docs_list) == 2)) {
                echo '3@@@' . htmlentities('Only two index file allowed.', ENT_QUOTES);
                exit(0);
            }
        } else {
            if (!isset($_POST) && empty($_POST)) {
                echo '1@@@' . htmlentities('Invalid post.', ENT_QUOTES);
                exit(0);
            }
            $court_fee_calculation_helper_flag = '';
            $if_with_affidavit = !empty($_POST["if_with_affidavit"]) ? $_POST["if_with_affidavit"] : '';
            $Attested_within_delhi = !empty($_POST["Attested_within_delhi"]) ? $_POST["Attested_within_delhi"] : '';
            $letter_of_inspection_of_file = !empty($_POST["letter_of_inspection_of_file"]) ? $_POST["letter_of_inspection_of_file"] : '';
            if (!empty($_POST["affidavitCheck"]) && empty($if_with_affidavit)) {
                $this->validation->setRules([
                    "error_affidavit_Attested" => [
                        "label" => "Affidavit",
                        "rules" => "required|trim"
                    ],
                ]);
            } elseif (!empty($_POST["affidavitCheck"]) && !empty($if_with_affidavit)) {
                $court_fee_calculation_helper_flag = $if_with_affidavit;
            }
            if (!empty($_POST["attestedCheck"]) && empty($Attested_within_delhi)) {
                $this->validation->setRules([
                    "error_affidavit_Attested" => [
                        "label" => "Attested",
                        "rules" => "required|trim"
                    ],
                ]);
            } elseif (!empty($_POST["attestedCheck"]) && !empty($Attested_within_delhi)) {
                $court_fee_calculation_helper_flag = $Attested_within_delhi;
            }
            if (!empty($_POST["inspectionLetterCheck"]) && empty($letter_of_inspection_of_file)) {
                $this->validation->setRules([
                    "error_affidavit_Attested" => [
                        "label" => "Letter of Inspection of File",
                        "rules" => "required|trim"
                    ],
                ]);
            } elseif (!empty($_POST["inspectionLetterCheck"]) && !empty($letter_of_inspection_of_file)) {
                $court_fee_calculation_helper_flag = $letter_of_inspection_of_file;
            }
            $this->validation->setRules([
                "pdfs_list" => [
                    "label" => "PDF File",
                    "rules" => "required|trim"
                ],
                "doc_type" => [
                    "label" => "Document Type",
                    "rules" => "required|trim"
                ],
            ]);
            $doc_type_valid_affi = !empty($_POST["doc_type"]) ? escape_data(url_decryption($_POST["doc_type"])) : '';
            if ($doc_type_valid_affi == 8) // select sub document type mandatory in case of IA  : code changed on 19082023 by KBP
            {
                $this->validation->setRules([
                    "sub_doc_type" => [
                        "label" => "Sub Document Type",
                        "rules" => "required|trim"
                    ],
                ]);
            }
            if (isset($doc_type_valid_affi) && $doc_type_valid_affi == 11) {
                $this->validation->setRules([
                    "no_copies" => [
                        "label" => "No Of Affidavit Copies",
                        "rules" => "required|trim|min_length[1]|max_length[3]|is_natural_no_zero"
                    ],
                ]);
            }
            $subdoc_typeID = !empty($_POST["sub_doc_type"]) ? escape_data(url_decryption($_POST["sub_doc_type"])) : '';
            if (isset($subdoc_typeID) && $subdoc_typeID == 95) {
                $this->validation->setRules([
                    "no_of_petitioner_appellant" => [
                        "label" => "No Of Petitioner Appellant",
                        "rules" => "required|trim|min_length[1]|max_length[3]|is_natural_no_zero"
                    ],
                ]);
            }
            if ($this->validation->withRequest($this->request)->run() === FALSE) {
                echo '3@@@';
                echo $this->validation->getError('pdfs_list') . $this->validation->getError('doc_upload') . $this->validation->getError('doc_type') . $this->validation->getError('sub_doc_type') . $this->validation->getError('doc_title') . $this->validation->getError('page_no_from') . $this->validation->getError('page_no_to') . $this->validation->getError('no_copies');
                exit(0);
            }
            $pdf_id = !empty($_POST["pdfs_list"]) ? url_decryption($_POST["pdfs_list"]) : '';
            $doc_type = !empty($_POST["doc_type"]) ? url_decryption($_POST["doc_type"]) : '';
            $doc_title = !empty($_POST["doc_title"]) ? $_POST["doc_title"] : '';
            $page_no_from = (!empty($_POST["page_no_from"]) ? strtoupper(escape_data($_POST["doc_title"])) : null);
            $page_no_to = (!empty($_POST["page_no_to"]) ? escape_data($_POST["page_no_to"]) : null);
            $sub_doc_type = !empty($_POST["sub_doc_type"]) ? url_decryption($_POST["sub_doc_type"]) : '';
            if (empty($sub_doc_type)) {
                $sub_doc_type = 0;
            }
            $doc_total_pages = $page_no_to - $page_no_from + 1;
            $is_check_index_file = $this->DocumentIndex_Select_model->is_check_index_item_file(getSessionData('efiling_details')['registration_id'], $doc_type, $sub_doc_type);
            if (!empty($is_check_index_file)) {
                echo '3@@@' . htmlentities('Failed. Duplicate page index not allowed.', ENT_QUOTES);
                exit(0);
            }
            switch (getSessionData('efiling_details')['ref_m_efiled_type_id']) {
                case E_FILING_TYPE_NEW_CASE:
                    $breadcrumb_step_no = NEW_CASE_INDEX;
                    $breadcrumb_to_remove = NEW_CASE_AFFIRMATION;
                    break;
                case E_FILING_TYPE_MISC_DOCS:
                    $breadcrumb_step_no = MISC_BREAD_DOC_INDEX;
                    $breadcrumb_to_remove = MISC_BREAD_AFFIRMATION;
                    break;
                case E_FILING_TYPE_IA:
                    $breadcrumb_step_no = IA_BREAD_DOC_INDEX;
                    $breadcrumb_to_remove = IA_BREAD_AFFIRMATION;
                    break;
                case E_FILING_TYPE_CAVEAT:
                    $breadcrumb_step_no = CAVEAT_BREAD_DOC_INDEX;
                    $breadcrumb_to_remove = CAVEAT_BREAD_VIEW;
                    break;
                case OLD_CASES_REFILING:
                    $breadcrumb_step_no = MISC_BREAD_DOC_INDEX;
                    $breadcrumb_to_remove = MISC_BREAD_AFFIRMATION;
                    break;
            }
            $uploaded_by = $_SESSION['login']['id'];
            if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {
                $aorData = getAordetails_ifFiledByClerk($registration_id);
                if (isset($aorData) && !empty($aorData)) {
                    $uploaded_by = !empty($aorData) ? $aorData[0]->id  : 0;
                    $sub_created_by = $_SESSION['login']['id'];
                }
            }
            if (empty($index_id)) {
                if ($doc_type == 11) {
                    $no_of_affi_copies = !empty($_POST["no_copies"]) ? $_POST["no_copies"] : '';
                } else {
                    $no_of_affi_copies = NULL;
                }
                if ($sub_doc_type == 95) {
                    $no_of_petitioner_appellant = !empty($_POST["no_of_petitioner_appellant"]) ? $_POST["no_of_petitioner_appellant"] : '';
                } else {
                    $no_of_petitioner_appellant = NULL;
                }
                $getFileDetails = $this->DocumentIndex_Select_model->get_path_size_name($registration_id);
                $file_path = explode($getFileDetails[0]['file_name'], $getFileDetails[0]['file_path']);
                $file_path = $file_path[0];
                $file_name = $getFileDetails[0]['file_name'];
                $file_size = $getFileDetails[0]['file_size'];
                $data = array(
                    'registration_id' => getSessionData('efiling_details')['registration_id'],
                    'efiled_type_id' => getSessionData('efiling_details')['ref_m_efiled_type_id'],
                    'pdf_id' => $pdf_id,
                    'doc_type_id' => $doc_type,
                    'sub_doc_type_id' => $sub_doc_type,
                    'doc_title' => $doc_title,
                    'page_no' => $doc_total_pages,
                    'st_page' => $page_no_from,
                    'end_page' => $page_no_to,
                    'no_of_copies' => $doc_total_pages,
                    'uploaded_by' => $uploaded_by,
                    'uploaded_on' => date('Y-m-d H:i:s'),
                    'upload_ip_address' => getClientIP(),
                    'doc_hashed_value' => null,
                    'file_name' => $file_name,
                    'file_path' => $file_path,
                    'file_size' => $file_size,
                    'file_type' => 'application/pdf',
                    'index_no' => null,
                    'upload_stage_id' => getSessionData('efiling_details')['stage_id'],
                    'pspdfkit_document_id' => null,
                    'court_fee_calculation_helper_flag' => $court_fee_calculation_helper_flag,
                    'no_of_affidavit_copies' => $no_of_affi_copies,
                    'no_of_petitioner_appellant' => $no_of_petitioner_appellant,
                );
                $data_2 = array('last_page' => $page_no_to);
                $last_page = $page_no_to + 1;
                $result = $this->DocumentIndex_model->save_pdf_details($data, $data_2, $pdf_id, $breadcrumb_step_no, $breadcrumb_to_remove);
                if ($result) {
                    reset_affirmation($registration_id);
                    echo '2@@@' . '@@@' . htmlentities('Index Item entry done successfully.', ENT_QUOTES);
                    exit(0);
                } else {
                    echo '1@@@' . htmlentities('Failed. Please try again.', ENT_QUOTES);
                    exit(0);
                }
            } else if (empty($index_id) && !($this->validateduplicateIndexRange($index_id, $pdf_id, $page_no_from, $page_no_to))) {
                echo '3@@@' . htmlentities('Failed. Duplicate page index not allowed.', ENT_QUOTES);
                exit(0);
            }
            if (isset($index_id) && !empty($index_id) && $this->validateduplicateIndexRange($index_id, $pdf_id, $page_no_from, $page_no_to)) {
                //code modified on 1 dec by Preeti
                if ($doc_type == 11) {
                    $no_of_affi_copies = !empty($_POST["no_copies"]) ? escape_data($_POST["no_copies"]) : '';
                } else {
                    $no_of_affi_copies = NULL;
                }
                if ($sub_doc_type == 95) {
                    $no_of_petitioner_appellant = !empty($_POST["no_of_petitioner_appellant"]) ? escape_data($_POST["no_of_petitioner_appellant"]) : '';
                } else {
                    $no_of_petitioner_appellant = NULL;
                }
                $data = array(
                    'registration_id' => getSessionData('efiling_details')['registration_id'],
                    'efiled_type_id' => getSessionData('efiling_details')['ref_m_efiled_type_id'],
                    'pdf_id' => $pdf_id,
                    'doc_type_id' => $doc_type,
                    'sub_doc_type_id' => $sub_doc_type,
                    'doc_title' => strtoupper($doc_title),
                    'page_no' => $doc_total_pages,
                    'st_page' => $page_no_from,
                    'end_page' => $page_no_to,
                    'no_of_copies' => $doc_total_pages,
                    'updated_by' => $uploaded_by,
                    'updated_on' => date('Y-m-d H:i:s'),
                    'update_ip_address' => getClientIP(),
                    'doc_hashed_value' => null,
                    'file_name' => null,
                    'file_size' => null,
                    'pspdfkit_document_id' => null,
                    'no_of_affidavit_copies' => $no_of_affi_copies,
                    'no_of_petitioner_appellant' => $no_of_petitioner_appellant,
                );
                $data_2 = array('last_page' => $page_no_to);
                $original_pdf_details = $this->DocumentIndex_Select_model->get_original_pdf_details($pdf_id);
                $last_page = $page_no_to + 1;
                if ($original_pdf_details[0]['page_no'] > $last_page) {
                    $last_page_doc = $last_page;
                } elseif ($original_pdf_details[0]['page_no'] == $last_page) {
                    $last_page_doc = 'all_page_done';
                } else {
                    $last_page_doc = $original_pdf_details[0]['page_no'];
                }
                //used to delete old index file physically code added on 14 dec 20
                $index_det = $this->DocumentIndex_Select_model->delete_beforedit_index_file($index_id);
                unlink($index_det[0]['file_path'] . $index_det[0]['file_name']);
                $result = $this->DocumentIndex_model->save_pdf_details($data, $data_2, $pdf_id, $breadcrumb_step_no, $breadcrumb_to_remove, $index_id);
                if ($result) {
                    reset_affirmation($registration_id);
                    echo '2@@@' . htmlentities($last_page_doc, ENT_QUOTES) . '@@@' . htmlentities('Index Item Updated successfully.', ENT_QUOTES);
                    reset_affirmation(getSessionData('efiling_details')['registration_id']);
                    exit(0);
                } else {
                    echo '1@@@' . htmlentities('Failed. Please try again.', ENT_QUOTES);
                    exit(0);
                }
            } else {
                echo '3@@@' . htmlentities('Failed. 1.Duplicate page index not allowed.', ENT_QUOTES);
                exit(0);
            }
        }
    }

    private function split_pdf($page_start, $page_end, $total_pages, $new_file_title, $original_file)
    {
        $efiling_no = getSessionData('efiling_details')['efiling_no'];
        $est_code = $_SESSION['estab_details']['estab_code'];
        $file_uploaded_dir = 'uploaded_docs/' . $est_code . '/' . $efiling_no . '/efiled_docs/';
        if (!is_dir($file_uploaded_dir)) {
            $uold = umask(0);
            if (mkdir('uploaded_docs/' . $est_code . '/' . $efiling_no . '/efiled_docs/', 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($file_uploaded_dir . '/index.html', $html);
            }
            umask($uold);
        }
        $current_time = time();
        $original_file = getcwd() . '/' . $original_file;
        $new_file_title = str_replace(' ', '_', $new_file_title);
        $new_file_title = $this->filterCharsFromFileName($new_file_title);
        $new_file = getcwd() . '/' . $file_uploaded_dir . $efiling_no . '_' . $new_file_title . '-' . $current_time . '.pdf';
        $new_file_name = $efiling_no . '_' . $new_file_title . '-' . $current_time . '.pdf';
        if (!file_exists($new_file)) {
            exec("qpdf $original_file --pages . $page_start-$page_end -- $new_file", $output);
            $new_file_hash_value = hash_file('sha256', $new_file);
            $new_file_size = filesize($new_file);
            return $new_file_name . '@@@' . $file_uploaded_dir . '@@@' . $new_file_hash_value . '@@@' . $new_file_size;
        } else {
            return FALSE;
        }
    }

    private function filterCharsFromFileName($filename)
    {
        return str_replace("/", "", str_replace(".", "", str_replace("(", "", str_replace(")", "", $filename))));
    }

    public function uploadDocument($content)
    {
        $pspdfkit_document = [];
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post(
                PSPDFKIT_SERVER_URI . '/api/documents',
                [
                    'headers' => [
                        'Authorization' => 'Token token="secret"',
                        'Content-Length' => 0,
                        'Content-Type' => 'application/pdf',
                        'Transfer-Encoding' => 'chunked',
                        'cache-control' => 'no-cache',
                    ],
                    'http_errors' => false,
                    'body' => $content
                ]
            );
            if ($response->getStatusCode() == 200) {
                $pspdfkit_document = json_decode($response->getBody()->getContents(), true)['data'];
            }
        } catch (GuzzleException $e) {
            //$if_save_case_file_paper_book = false;
        }
        return $pspdfkit_document;
    }

    private function check_login()
    {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT, AMICUS_CURIAE_USER);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
    }

    //Code used to validate duplicate index number added on 4 dec 2020
    public function validateduplicateIndexRange($index_id, $master_pdf_id, $st_page, $end_page)
    {
        if (empty($index_id)) {
            $index_file_details = $this->DocumentIndex_model->getindexfiledetails($master_pdf_id); //used to get index pdf's
        } else {
            $index_file_details = $this->DocumentIndex_model->getindexfiledetails_perindex($master_pdf_id, $index_id);
        }
        //code used to get range of index pages
        $index_pg_range = array();
        foreach ($index_file_details as $sub_index_file) {
            $index_pg_range = array_merge($index_pg_range, range($sub_index_file->st_page, $sub_index_file->end_page));
        }
        //check within  range code added on 10 dec 20
        $within_range = range($st_page, $end_page);
        if (in_array($st_page, $index_pg_range) || in_array($end_page, $index_pg_range) || !(count(array_intersect($within_range, $index_pg_range)) === 0)) {
            return false;
        } else {
            return true;
        }
    }

    //Code added on 6 nov 2020
    public function unfilled_pdf_pages_for_index()
    {
        if (isset(getSessionData('efiling_details')['registration_id']) && !empty(getSessionData('efiling_details')['registration_id'])) {
            $registration_id = getSessionData('efiling_details')['registration_id'];
            $index_pdf_details = $this->DocumentIndex_Select_model->unfilled_pdf_pages_for_index($registration_id);
            if (!$index_pdf_details) {
                return true;
            } else {
                $pdfname = "";
                foreach ($index_pdf_details as $val) {
                    $pdfname .= $val['doc_title'] . " , ";
                }
                $pdfname = !empty($pdfname) ? rtrim($pdfname, " , ") : '';
                echo '1@@@' . htmlentities("Error:Index of file " . $pdfname . ' not completed ', ENT_QUOTES);
                exit(0);
            }
        }
    }
}
