<?php
namespace App\Controllers;

class View extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('mentioning/Get_details_model');
        $this->load->model('mentioning/View_model');
        $this->load->model('affirmation/Affirmation_model');
    }

    function index() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_ADMIN, USER_CLERK);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }

        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {

            $registration_id = $_SESSION['efiling_details']['registration_id'];
            
            $data['case_details'] = $this->Get_details_model->get_case_details($registration_id);
            
            $data['filing_for_details'] = $this->View_model->get_filing_for_parties($registration_id);
            
           // $data['efiled_docs_list'] = $this->View_model->get_index_items_list($registration_id);
            
            $data['uploaded_docs'] = $this->View_model->get_uploaded_pdfs($registration_id);
            
            $data['order_text'] = $this->View_model->get_mentioning_orders($registration_id);
            
            $data['payment_details'] = $this->View_model->get_payment_details($registration_id);
            
            $data['esigned_docs_details'] = $this->Affirmation_model->get_esign_doc_details($registration_id);
            
            $this->load->view('templates/header');
            $this->load->view('mentioning/mentioning_preview', $data);
            $this->load->view('templates/footer');
        } else {
            redirect('login');
            exit(0);
        }
    }

}
