<?php

namespace App\Controllers\IA;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\ShcilPayment\PaymentModel;
use App\Models\IA\CourtFeeModel;
use App\Models\DocumentIndex\DocumentIndexSelectModel;

class CourtFee extends BaseController
{

    protected $Common_model;
    protected $Payment_model;
    protected $Court_Fee_model;
    protected $DocumentIndex_Select_model;
    protected $request;
    protected $validation;

    public function __construct()
    {
        parent::__construct();
        $this->Common_model = new CommonModel();
        $this->Payment_model = new PaymentModel();
        $this->Court_Fee_model = new CourtFeeModel();
        $this->DocumentIndex_Select_model = new DocumentIndexSelectModel();
        $this->request = \Config\Services::request();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        //func added on 11 nov 2020
        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            //$index_pdf_details = $this->DocumentIndex_Select_model->unfilled_pdf_pages_for_index($registration_id);
            $index_pdf_details = $this->DocumentIndex_Select_model->is_index_created($registration_id);
            if (!empty($index_pdf_details)) {
                $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, AMICUS_CURIAE_USER);
                if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
                    return redirect()->to(base_url('/'));
                }
                $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
                if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
                    return redirect()->to(base_url('IA/view'));
                }
                $stages_array_defected = array(I_B_Defected_Stage);
                if (in_array($_SESSION['efiling_details']['stage_id'], $stages_array_defected)) {
                    return redirect()->to(base_url('IA/view'));
                }
                if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {
                    $registration_id = $_SESSION['efiling_details']['registration_id'];
                    //todo change code when user change doctype which has more than 0 court fee
                    //    Commentted By Amit Mishra
                    $pending_court_fee = getPendingCourtFee();
                    // $pending_court_fee=0;
                    $breadcrumb_to_update = IA_BREAD_COURT_FEE;
                    if ($pending_court_fee > 0) {
                        $update_courtfee_breadcrumb_status = $this->Payment_model->remove_breadcrumb($registration_id, $breadcrumb_to_update);
                    } else {
                        $update_courtfee_breadcrumb_status = $this->Payment_model->update_breadcrumbs($registration_id, $breadcrumb_to_update);
                    }
                    $data['uploaded_pages_count'] = $this->Court_Fee_model->get_uploaded_pages_count($registration_id);
                    $data['payment_details'] = $this->Court_Fee_model->get_payment_details($registration_id);
                    // $data['court_fee_bifurcation']=$this->Common_model->get_court_fee_bifurcation($registration_id);
                    $data['court_fee_list3'] = $this->Common_model->get_ia_or_misc_doc_court_fee($registration_id, null, null);
                    $court_fee_part2 = calculate_court_fee(null, 2, 'wd', null);
                    // $court_fee_part1 = calculate_court_fee(null, 1, null, 'O');
                    $total_court_fee = (int)$court_fee_part2;
                    // $total_court_fee=0;
                    $data['court_fee'] = $total_court_fee;
                }
                return $this->render('IA.ia_view', $data);
            } else {
                $updateData = "";
                // if(is_array($index_pdf_details))
                // {
                //     foreach ($index_pdf_details as $val) {
                //         $updateData .= $val['doc_title'] . " , ";
                //     }
                // }                
                // $updateData= !empty($updateData) ? rtrim($updateData, " , ") : '';
                echo "<script>alert('$updateData Pdf file index is not complete'); window.location.href='" . base_url() . "documentIndex';</script>";
                // log_message('CUSTOM', " Court Fee Module - Pdf file index is not complete");
                exit(0);
            }
        }
    }

    public function add_court_fee_details()
    {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            $_SESSION['MSG'] = 'Unauthorised Access !';
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            $_SESSION['MSG'] = 'Invalid Stage.';
            return redirect()->to(base_url('IA/view'));
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
                "label" => "want to more pay Court Fee",
                "rules" => "required|trim|min_length[1]|max_length[5]|is_natural"
            ],
        ]);
        if ($this->validation->withRequest($this->request)->run() === FALSE) {
            $_SESSION['MSG'] = $this->validation->getError('print_fee_details') . $this->validation->getError('usr_court_fee');
            return redirect()->to(base_url('IA/courtFee'));
            exit(0);
        }
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $user_declared_extra_fee = escape_data($_POST['user_declared_extra_fee']);
        $print_fee_details = escape_data($_POST['print_fee_details']);
        $print_fee_details = explode('$$', url_decryption($print_fee_details));
        if (count($print_fee_details) != 5) {
            $_SESSION['MSG'] = 'Printing Fee details tempered';
            return redirect()->to(base_url('IA/courtFee'));
            exit(0);
        }
        $order_no = rand(1001, 9999) . date("yhmids");
        $order_date = date('Y-m-d H:i:s');
        $court_fee_part2 = calculate_court_fee(null, 2, 'wd', null);
        $total_court_fee = (int)$court_fee_part2;
        $already_paid_payment = $this->Common_model->get_already_paid_court_fee($registration_id);
        if (!empty($already_paid_payment[0]['court_fee_already_paid']))
            $total_court_fee = $total_court_fee - (int)$already_paid_payment[0]['court_fee_already_paid'];
        /* Code changed by Mr.Anshu on 23082024 to redirect the user to dashboard, if user want to process the case via court fee tempering : start*/
        if (!empty($total_court_fee) && $_SESSION['efiling_details']['is_govt_filing'] != 1 && $_POST['usr_court_fee'] < $total_court_fee) {
            $court_fee_aler = 'The unapproved court fee of : ₹ ' . $_POST['usr_court_fee'] . '  will be rejected, and the amount owed should be changed to the : ₹' . $total_court_fee . ' minimum.';
            $_SESSION['paymentError'] = $court_fee_aler;
            $this->session->setFlashdata('paymentError', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a > <p style="text-align: center;">'.$court_fee_aler.'</p> </div>');
            // return $this->response->setBody("<script>window.top.location.href = '" . base_url('redirect_on_login') . "';</script>")->setContentType('text/html');
            return redirect()->back();
            exit();
        }
        /* Code changed by Mr.Anshu on 23082024 to redirect the user to dashboard, if user want to process the case via court fee tempering : end*/
        /*$data_to_save = array(
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
        );*/
        //comment 16 march 2021 by akg
        /*$data_to_save = array(
            'registration_id' => $registration_id,
            'entry_for_type_id' => $_SESSION['efiling_details']['efiling_for_type_id'],
            'entry_for_id' => $_SESSION['efiling_details']['efiling_for_id'],
            'uploaded_pages' => $print_fee_details[0],
            'per_page_charges' => $_SESSION['estab_details']['printing_cost'],
            'no_of_copies' => 4,
            'user_declared_court_fee' => escape_data($total_court_fee),
            'printing_total' => escape_data($print_fee_details[1]),
            'user_declared_total_amt' => escape_data($total_court_fee) + $print_fee_details[1],
            'received_amt' => escape_data($total_court_fee) + $print_fee_details[1],
            'order_no' => $order_no,
            'order_date' => $order_date,
            'payment_stage_id' => $_SESSION['efiling_details']['stage_id'],
            'payment_mode' => 'online',
            'payment_mode_name' => 'SHCIL'
        );*/
        $data_to_save = array(
            'registration_id' => $registration_id,
            'entry_for_type_id' => $_SESSION['efiling_details']['efiling_for_type_id'],
            'entry_for_id' => $_SESSION['efiling_details']['efiling_for_id'],
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
            'payment_stage_id' => $_SESSION['efiling_details']['stage_id'],
            'payment_mode' => 'online',
            'payment_mode_name' => 'SHCIL'
        );
        $status = $this->Court_Fee_model->insert_pg_request($data_to_save);
        unset($_SESSION['pg_request_payment_details']);
        if ($status) {
            //comment 16 march 2021 by akg
            /*$_SESSION['pg_request_payment_details'] = array(
                'user_declared_court_fee' => escape_data($total_court_fee),
                'printing_total' => escape_data($print_fee_details[1]),
                'user_declared_total_amt' => escape_data($total_court_fee) + $print_fee_details[1],
                'order_no' => $order_no,
                'order_date' => $order_date
            ); */
            $_SESSION['pg_request_payment_details'] = array(
                'user_declared_court_fee' => (escape_data($print_fee_details[4])) + (escape_data($user_declared_extra_fee)),
                'printing_total' => escape_data($print_fee_details[3]),
                'user_declared_total_amt' => escape_data($_POST['usr_court_fee']),
                'order_no' => $order_no,
                'order_date' => $order_date
            );
            return redirect()->to(base_url('shcilPayment/paymentRequest'));
            exit(0);
        }
    }
}
