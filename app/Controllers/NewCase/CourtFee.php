<?php

namespace App\Controllers\Newcase;

use App\Controllers\ShilclientController;
use App\Models\Common\CommonModel;
use App\Models\DocumentIndex\DocumentIndexSelectModel;
use App\Models\NewCase\CourtFeeModel;
use App\Models\NewCase\GetDetailsModel;
use App\Models\ShcilPayment\PaymentModel;

class CourtFee extends ShilclientController
{

    protected $Common_model;
    protected $Get_details_model;
    protected $Court_Fee_model;
    protected $DocumentIndex_Select_model;
    protected $Payment_model;
    protected $request;
    protected $validation;

    public function __construct()
    {
        parent::__construct();

        $this->Common_model = new CommonModel();
        $this->Get_details_model = new GetDetailsModel();
        $this->Court_Fee_model = new CourtFeeModel();
        $this->DocumentIndex_Select_model = new DocumentIndexSelectModel();
        $this->Payment_model = new PaymentModel();
        $this->request = \Config\Services::request();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        if (check_party() != true) {  //func added on 15 JUN 2021
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Please enter every party details before moving to further tabs.</div>');
            return redirect()->to(base_url('newcase/extra_party'));
        }
        //func added on 6 nov 2020
        if (isset(getSessionData('efiling_details')['registration_id']) && !empty(getSessionData('efiling_details')['registration_id'])) {

            $registration_id = getSessionData('efiling_details')['registration_id'];
            //$index_pdf_details = $this->DocumentIndex_Select_model->unfilled_pdf_pages_for_index($registration_id);
            $index_pdf_details = $this->DocumentIndex_Select_model->is_index_created($registration_id);
            if (!empty($index_pdf_details)) {

                $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
                if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
                    return redirect()->to(base_url('/'));
                }

                $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
                if (!empty(getSessionData('efiling_details')['stage_id']) && !in_array(getSessionData('efiling_details')['stage_id'], $stages_array) && getSessionData('efiling_details')['efiling_type'] == 'new_case') {
                    return redirect()->to(base_url('newcase/view'));
                } else if (!empty(getSessionData('efiling_details')['stage_id']) && !in_array(getSessionData('efiling_details')['stage_id'], $stages_array) && getSessionData('efiling_details')['efiling_type'] == 'CAVEAT') {
                    return redirect()->to(base_url('caveat/view'));
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
                    $total_court_fee = 0;
                    $registration_id = getSessionData('efiling_details')['registration_id'];
                    //todo change code when user change case type from criminal to civil
                    $pending_court_fee = getPendingCourtFee();
                    if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
                        $breadcrumb_to_update = CAVEAT_BREAD_COURT_FEE;
                    } else {
                        $breadcrumb_to_update = NEW_CASE_COURT_FEE;
                    }
                    if ($pending_court_fee > 0) {
                        $update_courtfee_breadcrumb_status = $this->Payment_model->remove_breadcrumb($registration_id, $breadcrumb_to_update);
                    } else {
                        $update_courtfee_breadcrumb_status = $this->Payment_model->update_breadcrumbs($registration_id, $breadcrumb_to_update);
                    }
                    $data['uploaded_pages_count'] = $this->Court_Fee_model->get_uploaded_pages_count($registration_id);
                    $data['payment_details'] = $this->Court_Fee_model->get_payment_details($registration_id);
                    $data['court_fee_bifurcation'] = $this->Common_model->get_court_fee_bifurcation($registration_id);
                    $data['court_fee_list1'] = $court_fee_calculation_param1 = $this->Common_model->get_subject_category_casetype_court_fee($registration_id);
                    $data['court_fee_list2'] = $this->Common_model->get_ia_or_misc_doc_court_fee(null, 12, 0);
                    $data['court_fee_list3'] = $this->Common_model->get_ia_or_misc_doc_court_fee($registration_id, null, null);
                    $data['court_fee_list4'] = $this->Common_model->get_ia_or_misc_doc_court_fee(null, 11, 0);

                    $court_fee_part1 = calculate_court_fee(null, 1, null, 'O');
                    $court_fee_part2 = calculate_court_fee(null, 2, null, null);

                    //echo $payment_extra_fee;

                    //echo $court_fee_part2;
                    $total_court_fee = (int)$court_fee_part1 + (int)$court_fee_part2;
                    $data['court_fee'] = $total_court_fee;
                }

                //echo '<pre>';print_r($_SESSION['estab_details']); echo '</pre>';exit();
                //echo '<pre>';print_r($data['court_fee']); echo '</pre>';//exit();
                // $this->load->view('templates/header');
                // $this->load->view('newcase/new_case_view', $data);
                // $this->load->view('templates/footer');

                return $this->render('newcase.new_case_view', $data);
            } else {
                $updateData = "";
                // $updateData = "";
                // foreach ($index_pdf_details as $val) {
                //     $updateData .= $val['doc_title'] . " , ";
                // }

                // $updateData = rtrim($updateData, " , ");
                //  echo htmlentities("Index of file " . $val['doc_title'] . ' not completed ', ENT_QUOTES);
                echo "<script>alert('$updateData Pdf file index is not complete');
window.location.href='" . base_url() . "documentIndex';</script>";
            }
        }
    }


    public function add_court_fee_details()
    {

        //echo "<pre>";       print_r($_SESSION);die;


        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);

        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            $_SESSION['MSG'] = 'Unauthorised Access !';
            return redirect()->to(base_url('/'));
            exit(0);
        }

        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
            $_SESSION['MSG'] = 'Invalid Stage.';
            return redirect()->to(base_url('newcase/view'));
            exit(0);
        }
        if($_POST['user_declared_extra_fee'] == ''){
            session()->setFlashdata('validation_error', 'Want to pay more Court Fee can not be blank!');
            return redirect()->to(base_url('newcase/courtFee'));
            exit(0);
        }
        $this->validation->setRules([
            "print_fee_details" => [
                "label" => "Printing Details",
                "rules" => "required|trim"
            ],
            "usr_court_fee" => [
                "label" => "Court Fee",
                "rules" => "required|trim|min_length[1]|max_length[5]|is_natural"
            ],
            "user_declared_extra_fee" => [
                "label" => "want to pay more Court Fee",
                "rules" => "required|trim|min_length[1]|max_length[5]|is_natural"
            ],
        ]);
        if ($this->validation->withRequest($this->request)->run() === FALSE) {

            $_SESSION['MSG'] = $this->validation->getError('print_fee_details') . $this->validation->getError('usr_court_fee');
            return redirect()->to(base_url('newcase/courtFee'));
            exit(0);
        }

        $registration_id = getSessionData('efiling_details')['registration_id'];


        $user_declared_extra_fee = escape_data($_POST['user_declared_extra_fee']);
        $print_fee_details = escape_data($_POST['print_fee_details']);
        $print_fee_details = explode('$$', url_decryption($print_fee_details));

        if (count($print_fee_details) != 5) {
            $_SESSION['MSG'] = 'Printing Fee details tempered';
            return redirect()->to(base_url('newcase/courtFee'));
            exit(0);
        }

        $order_no = rand(1001, 9999) . date("yhmids");
        $order_date = date('Y-m-d H:i:s');

        $court_fee_part1 = calculate_court_fee(null, 1, null, 'O');
        $court_fee_part2 = calculate_court_fee(null, 2, null, null);

        $total_court_fee = (int)$court_fee_part1 + (int)$court_fee_part2;

        $already_paid_payment = $this->Common_model->get_already_paid_court_fee($registration_id);

        if (!empty($already_paid_payment[0]['court_fee_already_paid']))
            $total_court_fee = $total_court_fee - (int)$already_paid_payment[0]['court_fee_already_paid'];

        /* Code changed by Mr.Anshu on 23082024 to redirect the user to dashboard, if user want to process the case via court fee tempering : start*/
        if (!empty($total_court_fee) && $_SESSION['efiling_details']['is_govt_filing'] !=1 && $_POST['usr_court_fee'] < $total_court_fee){
            $court_fee_aler = 'The unapproved court fee of : ₹ '.$_POST['usr_court_fee'].'  will be rejected, and the amount owed should be changed to the : ₹'.$total_court_fee.' minimum.';
            $_SESSION['paymentError'] = $court_fee_aler;
            $this->session->setFlashdata('paymentError', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a > <p style="text-align: center;">'.$court_fee_aler.'</p> </div>');
            // return $this->response->setBody("<script>window.top.location.href = '" . base_url('redirect_on_login') . "';</script>")->setContentType('text/html');
            return redirect()->back();
            exit();
        }
        /* Code changed by Mr.Anshu on 23082024 to redirect the user to dashboard, if user want to process the case via court fee tempering : end*/

        /*$data_to_save = array(
            'registration_id' => $registration_id,            
            'entry_for_type_id' => getSessionData('efiling_details')['efiling_for_type_id'],
            'entry_for_id' => getSessionData('efiling_details')['efiling_for_id'],
            'uploaded_pages' => $print_fee_details[0],
            'per_page_charges' => $_SESSION['estab_details']['printing_cost'],
            'no_of_copies' => 4,
            'user_declared_court_fee' => escape_data($_POST['usr_court_fee']),
            'printing_total' => escape_data($print_fee_details[1]),
            'user_declared_total_amt' => escape_data($_POST['usr_court_fee']) + $print_fee_details[1],
            'received_amt' => escape_data($_POST['usr_court_fee']) + $print_fee_details[1],
            'order_no' => $order_no,
            'order_date' => $order_date,
            'payment_stage_id' => getSessionData('efiling_details')['stage_id'],
            'payment_mode' => 'online',
            'payment_mode_name' => 'SHCIL'
        );*/
        //comment 16 march 2021 by akg
        /*$data_to_save = array(
           'registration_id' => $registration_id,
           'entry_for_type_id' => !empty(getSessionData('efiling_details')['efiling_for_type_id']) ? (int)getSessionData('efiling_details')['efiling_for_type_id'] : (int)getSessionData('efiling_details')['ref_m_efiled_type_id'],
           'entry_for_id' => !empty(getSessionData('efiling_details')['efiling_for_id']) ? (int)getSessionData('efiling_details')['efiling_for_id'] : 1,
           'uploaded_pages' => $print_fee_details[0],
           'per_page_charges' => $_SESSION['estab_details']['printing_cost'],
           'no_of_copies' => 4,
            'user_declared_extra_fee' => escape_data($user_declared_extra_fee),
           'user_declared_court_fee' => escape_data($total_court_fee),
           'printing_total' => escape_data($print_fee_details[1]),
           'user_declared_total_amt' => escape_data($total_court_fee) + $print_fee_details[1] + $user_declared_extra_fee,
           'received_amt' => escape_data($total_court_fee) + $print_fee_details[1] + $user_declared_extra_fee,
           'order_no' => $order_no,
           'order_date' => $order_date,
           'payment_stage_id' => getSessionData('efiling_details')['stage_id'],
           'payment_mode' => 'online',
           'payment_mode_name' => 'SHCIL'
       );*/

        $data_to_save = array(
            'registration_id' => $registration_id,
            'entry_for_type_id' => !empty(getSessionData('efiling_details')['efiling_for_type_id']) ? (int)getSessionData('efiling_details')['efiling_for_type_id'] : (int)getSessionData('efiling_details')['ref_m_efiled_type_id'],
            'entry_for_id' => !empty(getSessionData('efiling_details')['efiling_for_id']) ? (int)getSessionData('efiling_details')['efiling_for_id'] : 1,
            'uploaded_pages' => $print_fee_details[0],
            'per_page_charges' => $_SESSION['estab_details']['printing_cost'],
            'no_of_copies' => 4,
            'user_declared_extra_fee' => escape_data($user_declared_extra_fee),
            'user_declared_court_fee' => escape_data($print_fee_details[4]),
            'printing_total' => escape_data($print_fee_details[3]),
            'user_declared_total_amt' => escape_data($_POST['usr_court_fee']),
            'received_amt' => escape_data($_POST['usr_court_fee']),
            'order_no' => $order_no,
            'order_date' => $order_date,
            'payment_stage_id' => getSessionData('efiling_details')['stage_id'],
            'payment_mode' => 'online',
            'payment_mode_name' => 'SHCIL'
        );
        $status = $this->Court_Fee_model->insert_pg_request($data_to_save);
        // unset($_SESSION['pg_request_payment_details']);
        if ($status) {
            //comment 16 march 2021 by akg
            /* $_SESSION['pg_request_payment_details'] = array(
                'user_declared_court_fee' => escape_data($total_court_fee),
                'printing_total' => escape_data($print_fee_details[1]),
                'user_declared_total_amt' => escape_data($total_court_fee) + $print_fee_details[1] + $user_declared_extra_fee,
                'order_no' => $order_no,
                'order_date' => $order_date
            );*/
            $_SESSION['pg_request_payment_details'] = array(
                'user_declared_court_fee' => (escape_data($print_fee_details[4])) + (escape_data($user_declared_extra_fee)),
                'printing_total' => escape_data($print_fee_details[3]),
                'user_declared_total_amt' => escape_data($_POST['usr_court_fee']),
                'order_no' => $order_no,
                'order_date' => $order_date
            );        //echo '<pre>';print_r($_SESSION['pg_request_payment_details']); echo '</pre>'; exit();
            reset_affirmation($registration_id);
            return redirect()->to(base_url('shcilPayment/paymentRequest'));
            exit(0);
        }
    }

    public function add_govt_court_fee_details()
    {

        //echo "<pre>";       print_r($_SESSION);die;


        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);

        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            $_SESSION['MSG'] = 'Unauthorised Access !';
            return response()->redirect(base_url('/')); 
        }

        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
            $_SESSION['MSG'] = 'Invalid Stage.';
            return redirect()->to(base_url('newcase/view'));
            exit(0);
        }
        $this->validation->setRules([
            "challan_no" => [
                "label" => "Challan Number",
                "rules" => "required|trim|min_length[10]|max_length[18]"
            ],
        ]);
        if ($this->validation->withRequest($this->request)->run() === FALSE) {

            $_SESSION['MSG'] = $this->validation->getError('print_fee_details') . $this->validation->getError('usr_court_fee');
            return redirect()->to(base_url('newcase/courtFee'));
            exit(0);
        }

        //check if challan no is already entered.
        $challan_details = $this->Court_Fee_model->get_challan_details($_POST['challan_no']);
        if ($challan_details) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Challan Number has already been used in another e-filed application.</div>');
            return redirect()->to(base_url('newcase/courtFee'));
        }

        $registration_id = getSessionData('efiling_details')['registration_id'];
        $order_no = rand(1001, 9999) . date("yhmids");
        $order_date = date('Y-m-d H:i:s');

        $result = $this->getStatus(strtoupper(trim($_POST['challan_no'])));

        //var_dump($_FILES);exit();
        $establishment_code = $_SESSION['estab_details']['estab_code'];
        $file_uploaded_dir = 'uploaded_docs/' . $establishment_code . '/' . getSessionData('efiling_details')['efiling_no'] . '/challan/';
        if (!is_dir($file_uploaded_dir)) {
            $uold = umask(0);
            if (mkdir('uploaded_docs/' . $establishment_code . '/' . getSessionData('efiling_details')['efiling_no'] . '/challan/', 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($file_uploaded_dir . '/index.html', $html);
            }
            umask($uold);
        }
        if ($msg = isValidPDF('challan_file')) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">' . $msg . '</div>');
            return redirect()->to(base_url('newcase/courtFee'));
        }
        $filename = 'Challan';
        $filename = str_replace(' ', '_', $filename); // Replaces all spaces with underscore.
        $filename = preg_replace('/[^A-Za-z0-9\_]/', '', $filename); // Removes special chars.
        $filename = getSessionData('efiling_details')['efiling_no'] . '_' . $filename . '_' . date('YmdHis') . '.pdf';
        $uploaded = move_uploaded_file($_FILES['challan_file']['tmp_name'], "$file_uploaded_dir/$filename");
        if ($uploaded) {
            //if (1) {
            $breadcrumb_to_update = NEW_CASE_COURT_FEE;
            if (strtoupper(trim($result->CERTRPDTL->RPSTATUS)) == 'SUCCESS') {
                $data_to_save = array(
                    'registration_id' => $registration_id,
                    'transaction_num' => $_POST['challan_no'],
                    'bank_name' => 'Paid Offline',
                    'payment_status' => 'Y',
                    'entry_for_type_id' => !empty(getSessionData('efiling_details')['efiling_for_type_id']) ? (int)getSessionData('efiling_details')['efiling_for_type_id'] : (int)getSessionData('efiling_details')['ref_m_efiled_type_id'],
                    'entry_for_id' => !empty(getSessionData('efiling_details')['efiling_for_id']) ? (int)getSessionData('efiling_details')['efiling_for_id'] : 1,
                    'uploaded_pages' => 0,
                    'per_page_charges' => $_SESSION['estab_details']['printing_cost'],
                    'no_of_copies' => 0,
                    'user_declared_extra_fee' => 0,
                    'user_declared_court_fee' => $result->CERTRPDTL->CFAMT,
                    'printing_total' => 0,
                    'user_declared_total_amt' => $result->CERTRPDTL->CFAMT,
                    'received_amt' => $result->CERTRPDTL->CFAMT,
                    'order_no' => $order_no,
                    'order_date' => $order_date,
                    'payment_stage_id' => getSessionData('efiling_details')['stage_id'],
                    'payment_mode' => 'Offline',
                    'payment_mode_name' => 'SHCIL'
                );
                $status = $this->Court_Fee_model->insert_pg_request($data_to_save);
                $update_courtfee_breadcrumb_status = $this->Payment_model->update_breadcrumbs($registration_id, $breadcrumb_to_update);
                unset($_SESSION['pg_request_payment_details']);
                if ($status) {
                    reset_affirmation($registration_id);
                }
            } else {
                $update_courtfee_breadcrumb_status = $this->Payment_model->remove_breadcrumb($registration_id, $breadcrumb_to_update);
                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Please Enter the correct Challan Number.</div>');
            }
        } else {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">File Upload failed. Please try again.</div>');
        }
        return redirect()->to(base_url('newcase/courtFee'));
    }

    public function checkFeeStatus(){
        if (isset(getSessionData('efiling_details')['registration_id']) && !empty(getSessionData('efiling_details')['registration_id'])) {

            $registration_id = getSessionData('efiling_details')['registration_id'];
            $payment_details = $this->Court_Fee_model->get_payment_details($registration_id);
            $uploaded_pages_count = $this->Court_Fee_model->get_uploaded_pages_count($registration_id);
            $printing_cost = getSessionData('estab_details')['printing_cost'];

            $court_fee_part1 = calculate_court_fee(null, 1, null, 'O');
            $court_fee_part2 = calculate_court_fee(null, 2, null, null);
            $total_court_fee = (int)$court_fee_part1 + (int)$court_fee_part2;
            $court_fee = $total_court_fee;

            $court_fee_already_paid = 0;
            $user_declared_extra_fee = 0;
            $user_declared_court_fee = 0;
            $uploaded_pages = 0;
            $printing_cost_already_paid = 0;
            if (is_array($payment_details) && count($payment_details) > 0) {
                foreach ($payment_details as $payment) {
                    if ($payment['payment_status'] == 'Y') {
                        $court_fee_already_paid = $court_fee_already_paid + (int)$payment['received_amt'];
                        $user_declared_court_fee = $user_declared_court_fee + (int)$payment['user_declared_court_fee'];
                        $user_declared_extra_fee = $user_declared_extra_fee + (int)$payment['user_declared_extra_fee'];
                        $uploaded_pages = $uploaded_pages + (int)$payment['uploaded_pages'];
                        $printing_cost_already_paid = $printing_cost_already_paid + (int)$payment['printing_total'];
                    }
                }
            }
            $court_fee_already_paid_without_extra_fee = $court_fee_already_paid - $user_declared_extra_fee;
            $uploaded_pages_count_pending = $uploaded_pages_count - $uploaded_pages;
            $printing_cost_to_be_paid = (int)$uploaded_pages_count_pending * (float)$printing_cost;
            $printing_cost_total = (int)$printing_cost_to_be_paid + (int)$printing_cost_already_paid;
            $total_court_fee = (int)$court_fee + (int)$printing_cost_to_be_paid + (int)$printing_cost_already_paid;
            $user_declared_court_fee = $court_fee - $user_declared_court_fee;
            $pending_court_fee = $total_court_fee - $court_fee_already_paid_without_extra_fee;
            if($pending_court_fee > 0){
                echo $pending_court_fee;
            }
        }
    }
}
