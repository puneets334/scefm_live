<?php
namespace App\Controllers;

class CourtFee extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('mentioning/Court_Fee_model');
    }

    public function index() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }

        $stages_array = array('', Draft_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            redirect('mentioning/view');
            exit(0);
        }

        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {

            $registration_id = $_SESSION['efiling_details']['registration_id'];

            $data['uploaded_pages_count'] = $this->Court_Fee_model->get_uploaded_pages_count($registration_id);
            
            $data['payment_details'] = $this->Court_Fee_model->get_payment_details($registration_id);
            
        }

        $this->load->view('templates/header');
        $this->load->view('mentioning/mentioning_view', $data);
        $this->load->view('templates/footer');
    }

    public function add_court_fee_details() {

        //echo "<pre>";       print_r($_SESSION);die;

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            $_SESSION['MSG'] = 'Unauthorised Access !';
            redirect('login');
            exit(0);
        }

        $stages_array = array('', Draft_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            $_SESSION['MSG'] = 'Invalid Stage.';
            redirect('mentioning/view');
            exit(0);
        }

        $this->form_validation->set_rules('print_fee_details', 'Printing Details', 'required|trim|validate_encrypted_value');
        $this->form_validation->set_rules('usr_court_fee', 'Court Fee', 'required|trim|min_length[1]|max_length[5]|is_natural');

        $this->form_validation->set_error_delimiters('<br/>', '');
        if (!$this->form_validation->run()) {

            $_SESSION['MSG'] = form_error('print_fee_details') . form_error('usr_court_fee');
            redirect('mentioning/courtFee');
            exit(0);
        }

         $registration_id = $_SESSION['efiling_details']['registration_id'];

        $print_fee_details = escape_data($_POST['print_fee_details']);
        $print_fee_details = explode('$$', url_decryption($print_fee_details));
        
        if (count($print_fee_details) != 4) {
            $_SESSION['MSG'] = 'Printing Fee details tempered';
            redirect('mentioning/courtFee');
            exit(0);
        }
        
        $order_no = rand(1001, 9999) . date("yhmids");
        $order_date = date('Y-m-d H:i:s');

        $data_to_save = array(
            'registration_id' => $registration_id,            
            'entry_for_type_id' => $_SESSION['efiling_details']['efiling_for_type_id'],
            'entry_for_id' => $_SESSION['efiling_details']['efiling_for_id'],
            'uploaded_pages' => $print_fee_details[0],
            'per_page_charges' => $_SESSION['estab_details']['printing_cost'],
            'no_of_copies' => 4,
            'user_declared_court_fee' => escape_data($_POST['usr_court_fee']),
            'printing_total' => escape_data($print_fee_details[1]),
            'user_declared_total_amt' => escape_data($_POST['usr_court_fee']) + $print_fee_details[1],
            'received_amt' => escape_data($_POST['usr_court_fee']) + $print_fee_details[1],
            'order_no' => $order_no,
            'order_date' => $order_date,
            'payment_stage_id' => $_SESSION['efiling_details']['stage_id'],
            'payment_mode' => 'online',
            'payment_mode_name' => 'SHCIL'
        );

        $status = $this->Court_Fee_model->insert_pg_request($data_to_save);
        unset($_SESSION['pg_request_payment_details']);
        if ($status) {
            $_SESSION['pg_request_payment_details'] = array(
                'user_declared_court_fee' => escape_data($_POST['usr_court_fee']),
                'printing_total' => escape_data($print_fee_details[1]),
                'user_declared_total_amt' => escape_data($_POST['usr_court_fee']) + $print_fee_details[1],
                'order_no' => $order_no,
                'order_date' => $order_date
            );           // print_r($_SESSION['pg_request_payment_details']);
           redirect('shcilPayment/paymentRequest');
            exit(0);
        }
    }

}
