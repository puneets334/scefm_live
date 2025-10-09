<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//require_once(ESIGN_JAVA_BRIDGE_URI);

class IA extends CI_Controller {

    public function __construct() {
        parent::__construct();

        require_once APPPATH . 'third_party/SBI/Crypt/AES.php';
        require_once APPPATH . 'third_party/cg/AesCipher.php';

        $this->load->model('newcase/IA_model');

        $this->load->helper('file');
    }

    public function index() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            $_SESSION['MSG'] = message_show("fail", 'Unauthorised Access !');
            redirect('adminDashboard');
            exit(0);
        }

        if (!in_array(NEW_CASE_INDEX, explode(',', $_SESSION['efiling_details']['breadcrumb_status']))) {
            $_SESSION['MSG'] = message_show("fail", "Please complete ' Index ' details.");
            log_message('CUSTOM', "Please complete ' Index ' details.");
            redirect('documentIndex');
            exit(0);
        }

        $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            $_SESSION['MSG'] = message_show("fail", "Invalid Stage.");
            redirect('newcase/view');
            exit(0);
        }

        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {

            $registration_id = $_SESSION['efiling_details']['registration_id'];

            $data['uploaded_pdf'] = $this->IA_model->get_uploaded_pdfs($registration_id);

            $ia_doc_type = 8;
            $data['doc_type'] = $this->IA_model->get_document_type($ia_doc_type);

            $data['efiled_IA_list'] = $this->IA_model->get_index_items_list($registration_id);
        }

        $this->load->view('templates/header');
        $this->load->view('newcase/new_case_view', $data);
        $this->load->view('templates/footer');
    }

    function add_index_item() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            $_SESSION['MSG'] = message_show("fail", 'Unauthorised Access !');
            redirect('adminDashboard');
            exit(0);
        }

        if (!isset($_POST) && empty($_POST)) {
            echo '1@@@' . htmlentities('Invalid post.', ENT_QUOTES);
            exit(0);
        }

        $this->form_validation->set_rules('pdfs_list', 'PDF File ', 'required|trim|validate_encrypted_value');
        $this->form_validation->set_rules('doc_type', 'Document Type', 'required|trim|validate_encrypted_value');
        $this->form_validation->set_rules('sub_doc_type', 'Sub Document Type', 'trim|validate_encrypted_value');
        /* if (isset($_POST['sub_doc_type']) && !empty($_POST['sub_doc_type'])) {
          $this->form_validation->set_rules('sub_doc_type', 'Sub Document Type', 'required|trim|validate_encrypted_value');
          } else {
          $this->form_validation->set_rules('sub_doc_type', 'Sub Document Type', 'trim');
          } */

        $this->form_validation->set_rules('doc_title', 'Document Title', 'required|trim|min_length[3]|max_length[99]|validate_alpha_numeric_space_dot_hyphen');
        $this->form_validation->set_rules('page_no_from', 'Page No. From ', 'required|trim|min_length[1]|max_length[3]|is_natural_no_zero');
        $this->form_validation->set_rules('page_no_to', 'Page No. To ', 'required|trim|min_length[1]|max_length[3]|is_natural_no_zero');

        $this->form_validation->set_error_delimiters('<br/>', '');
        if (!$this->form_validation->run()) {
            echo '3@@@'.form_error('doc_upload') . form_error('doc_type') . form_error('sub_doc_type') . form_error('doc_title') . form_error('page_no_from') . form_error('page_no_to');
            exit(0);
        }

        $pdf_id = escape_data(url_decryption($this->input->post("pdfs_list")));
        $doc_type = escape_data(url_decryption($this->input->post("doc_type")));
        $doc_title = strtoupper(escape_data($this->input->post("doc_title")));
        $page_no_from = escape_data($this->input->post("page_no_from"));
        $page_no_to = escape_data($this->input->post("page_no_to"));

        $sub_doc_type = escape_data(url_decryption($this->input->post("sub_doc_type")));
        if (empty($sub_doc_type)) {
            $sub_doc_type = 0;
        }

        $doc_total_pages = $page_no_to - $page_no_from + 1;

        $original_pdf_details = $this->IA_model->get_original_pdf_details($pdf_id);

        $split_pdf_details = $this->split_pdf($page_no_from, $page_no_to, $doc_total_pages, $doc_title, $original_pdf_details[0]['file_path']);
        if (empty($split_pdf_details)) {
            echo '1@@@' . htmlentities('PDF split error!', ENT_QUOTES);
            exit(0);
        } else {
            $pdf_details_array = explode('@@@', $split_pdf_details);
        }//print($pdf_details_array);
        $data = array(
            'registration_id' => $_SESSION['efiling_details']['registration_id'],
            'efiled_type_id' => $_SESSION['efiling_details']['ref_m_efiled_type_id'],
            'pdf_id' => $pdf_id,
            'doc_type_id' => $doc_type,
            'sub_doc_type_id' => $sub_doc_type,
            'doc_title' => strtoupper($doc_title),
            'page_no' => $doc_total_pages,
            'st_page' => $page_no_from,
            'end_page' => $page_no_to,
            'no_of_copies' => $doc_total_pages,
            'uploaded_by' => $_SESSION['login']['id'],
            'uploaded_on' => date('Y-m-d H:i:s'),
            'upload_ip_address' => getClientIP(),
            'doc_hashed_value' => $pdf_details_array[2],
            'file_name' => $pdf_details_array[0],
            'file_path' => $pdf_details_array[1],
            'file_size' => $pdf_details_array[3],
            'file_type' => 'application/pdf',
            'index_no' => $original_pdf_details[0]['max_index_no'] + 1,
            'upload_stage_id' => $_SESSION['efiling_details']['stage_id']
        );

        $data_2 = array('last_page' => $page_no_to);
        $last_page = $page_no_to + 1;

        if ($original_pdf_details[0]['page_no'] > $last_page) {
            $last_page_doc = $last_page;
        } elseif ($original_pdf_details[0]['page_no'] == $last_page) {
            $last_page_doc = 'all_page_done';
        } else {
            $last_page_doc = $original_pdf_details[0]['page_no'];
        }

        $breadcrumb_step_no = NEW_CASE_IA;
        $breadcrumb_to_remove = NEW_CASE_AFFIRMATION;


        $result = $this->IA_model->save_pdf_details($data, $data_2, $pdf_id, $breadcrumb_step_no, $breadcrumb_to_remove);
        if ($result) {

            $data['efiled_IA_list'] = $this->IA_model->get_index_items_list($_SESSION['efiling_details']['registration_id']);

            $index_data = $this->load->view('newcase/ia_items_list_view', $data, TRUE);

            echo '2@@@' . htmlentities($last_page_doc, ENT_QUOTES) . '@@@' . htmlentities('Index Item entry done successfully.', ENT_QUOTES) . '@@@' . $index_data;
            exit(0);
        } else {
            echo '1@@@' . htmlentities('Failed. Please try again.', ENT_QUOTES);
            exit(0);
        }
    }

    private function split_pdf($page_start, $page_end, $total_pages, $new_file_title, $original_file) {

        $efiling_no = $_SESSION['efiling_details']['efiling_no'];
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

        $new_file = getcwd() . '/' . $file_uploaded_dir . $efiling_no . '_' . $new_file_title . '-' . $current_time . '.pdf';
        $new_file_name = $efiling_no . '_' . $new_file_title . '-' . $current_time . '.pdf';

        if (!file_exists($new_file)) {

            $pdf_hash_object = new java("sci.pdf.PDFSplit");
            $documentHash = $pdf_hash_object->split($total_pages, $page_start, $page_end, $original_file, $new_file);
            $new_file . $new_file_hash_value = hash_file('sha256', $new_file);
            $new_file_size = filesize($new_file);
            chmod($new_file, 0644);

            return $new_file_name . '@@@' . $file_uploaded_dir . '@@@' . $new_file_hash_value . '@@@' . $new_file_size;
        } else {
            return FALSE;
        }
    }

    public function load_pdf_viewer() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }

        $pdf_id = url_decryption($_POST['document']);
        if (!empty($pdf_id)) {
            $result = $this->IA_model->get_original_pdf_details($pdf_id);

            $last_page = $result[0]['last_page'] + 1;
            $pdf_total_page = $result[0]['page_no'];
            if ($pdf_total_page > $last_page) {
                $last_page_no = $last_page;
            } else if ($pdf_total_page + 1 == $last_page) {
                $last_page_no = 'all_page_done';
            } elseif ($result[0]['last_page'] == $pdf_total_page) {
                $last_page_no = 'all_page_done';
            } else {
                $last_page_no = $pdf_total_page;
            }

            $_SESSION['case_details']['case_pdf_id'] = $result[0]['id'];
            $pdf_url = base_url($result[0]['file_path']) . '#page=' . $last_page_no;

            echo '2@@@' . $last_page_no . '@@@' . $pdf_total_page . '@@@<i class="fa fa-file-pdf-o" style="color:red;"></i> ' . '@@@' . $pdf_url;
        } else {
            echo '1@@@Invalid document Id.';
        }
    }

    public function get_doc_type() {
// MAKE SUB INDEX ITEM DROP DOWN ITEMS

        $doc_type_code = url_decryption($_POST['doc_type_code']);

        if (empty($doc_type_code) || !preg_match("/^[0-9]*$/", $doc_type_code)) {
            return FALSE;
        }

        $doc_res = $this->IA_model->get_sub_document_type($doc_type_code);

        $dropDownOptions = '<option value="">Select Index Sub Item</option>';
        foreach ($doc_res as $doc_list) {
            $dropDownOptions .= '<option value="' . escape_data(url_encryption($doc_list['doccode1'])) . '">' . escape_data($doc_list['docdesc']) . '</option>';
        }
        echo $dropDownOptions;
    }

    public function delete_index() {
// DELETES INDEX ENTRY
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }

        $input_array = explode('$$', url_decryption(escape_data($this->input->post("form_submit_val"))));

        if (count($input_array) != 2) {
            echo '1@@@Invalid Action';
        }

        $doc_id = $input_array[0];
        $registration_id = $input_array[1];

        if (empty($doc_id) || $registration_id != $_SESSION['efiling_details']['registration_id']) {
            echo '1@@@Invalid attempt.';
            exit(0);
        }

        $breadcrumb_to_remove = array(NEW_CASE_IA, NEW_CASE_AFFIRMATION);

        if (!empty($doc_id)) {
            $last_page = $this->IA_model->delete_index($doc_id, $breadcrumb_to_remove);
            if (!empty($last_page)) {

                $data['efiled_IA_list'] = $this->IA_model->get_index_items_list($_SESSION['efiling_details']['registration_id']);

                $index_data = $this->load->view('newcase/ia_items_list_view', $data, TRUE);

                echo '2@@@' . htmlentities('Entry deleted successfully.', ENT_QUOTES) . '@@@' . $index_data;
                exit(0);
            } else {
                echo '1@@@' . htmlentities('Failed to delete. Please try again.', ENT_QUOTES);
                exit(0);
            }
        } else {
            echo '1@@@' . htmlentities('Invalid Index id.', ENT_QUOTES);
            exit(0);
        }
    }

    public function viewIA($doc_id) {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_ADMIN);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }

        $doc_id = url_decryption($doc_id);
        if (empty($doc_id) || !preg_match("/^[0-9]*$/", $doc_id)) {
            echo "Invalid Access !";
            exit(0);
        }

        $doc_details = $this->IA_model->get_index_item_file($doc_id);
        $file_partial_path = $doc_details[0]['file_path'];
        $file_name = $file_partial_path . $doc_details[0]['file_name'];
        $doc_title = $_SESSION['efiling_details']['efiling_no'] . '_' . str_replace(' ', '_', $doc_details[0]['doc_title']) . '.pdf';

        if (file_exists($file_name)) {

            header("Content-Type: application/pdf");
            header("Content-Disposition:inline;filename = $doc_title");
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            @readfile($file_name);
            echo $file_name;
        } else {
            echo "File does not exists !";
            exit(0);
        }
    }

    public function view_icmis_pdf($doc_id) {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_ADMIN);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }

        $doc_id = url_decryption($doc_id);
        if (empty($doc_id) || !preg_match("/^[0-9]*$/", $doc_id)) {
            echo "Invalid Access !";
            exit(0);
        }

        //$doc_id = 'SXVnNlRrTjdXQzVOMWc3NkJ6YkNqQTo6';
        $doc_id = base64_encode($this->encrypt_doc_id($doc_id));

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => PSPDFKIT_SERVER_URI."/api/documents/" . $doc_id . "/pdf",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array("Authorization: Token token=\"secret\""),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if (!empty($response)) {

            header("Content-Type: application/pdf");
            header("Content-Disposition:inline;filename = $doc_title");
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            echo $response;
        } else {
            echo "File does not exists !";
            exit(0);
        }
    }

    function encrypt_doc_id($doc_id) {

        $doc_parameter = $doc_id;

        $aes = new Crypt_AES();
        $secret = base64_decode(SBI_PAYMENT_DOUBLE_VARIFICATION_SECRET_KEY);
        $aes->setKey($secret);
        $encrypted_doc_id = base64_encode($aes->encrypt($doc_parameter));
        $encrypted_doc_id = str_replace('/', '-', $encrypted_doc_id);
        $encrypted_doc_id = str_replace('=', ':', $encrypted_doc_id);
        $encrypted_doc_id = str_replace('+', '.', $encrypted_doc_id);

        return $encrypted_doc_id;
    }

}
