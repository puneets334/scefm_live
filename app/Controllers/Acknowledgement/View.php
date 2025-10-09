<?php

namespace App\Controllers\Acknowledgement;

use App\Controllers\BaseController;
use App\Models\Acknowledgement\AcknowledgementModel;
use App\Models\Common\CommonModel;
use App\Models\History\HistoryModel;
use App\Models\MiscellaneousDocs\GetDetailsModel;
use PDFDOC;
use TCPDF;

class view extends BaseController
{

    protected $History_model;
    protected $Common_model;
    protected $Get_details_model;
    protected $Acknowledgement_model;
    protected $PDFDOC;

    public function __construct()
    {
        parent::__construct();
        $this->History_model = new HistoryModel();
        $this->Common_model = new CommonModel();
        $this->Get_details_model = new GetDetailsModel();
        $this->Acknowledgement_model = new AcknowledgementModel();
    }

    public function index() {
        if (!($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON)) {
            return false;
        }
        if (empty($_SESSION['efiling_details']['registration_id'])) {
            return redirect()->to(base_url('/'));
        }
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $efiling_allocated_data = $this->Acknowledgement_model->get_allocated_to_details($registration_id);
        $efiling_submitted_on = $this->Acknowledgement_model->get_submitted_on($registration_id);
        $data['allocated_to'] = $efiling_allocated_data[0]['admin_name'];
        $data['submitted_on'] = $efiling_submitted_on[0]['activated_on'];
        $data['current_status'] = $efiling_submitted_on[0]['stage_id'];
        $qr_code_base64 = generate_qr_code(['data' => 'dkhjsdfkh'])->base64;
        $img_encoded = 'data:image/png;base64,' . $qr_code_base64;
        $img = '<img src="@' . preg_replace('#^data:image/[^;]+;base64,#', '', $img_encoded) . '"';
        if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] != E_FILING_TYPE_CDE) {
            $payment_type = $this->Acknowledgement_model->get_payment_status($registration_id);
        }
        $count_number_of_fee_pay = !empty($payment_type) ? count($payment_type) : 0;
        $fee_payment_mode_and_fee = '';
        $total_fee_paid = 0;
        if(is_array($payment_type)){
            foreach ($payment_type as $dataRes) {
                if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] != E_FILING_TYPE_CDE) {
                    if (isset($dataRes['payment_type']) && $dataRes['payment_type'] == PAYMENT_THROUGH_RECEIPT) {
                        $fee_payment_mode = 'Offline';
                        if (isset($dataRes['payment_method_name']) && !empty($dataRes['payment_method_name'])) {
                            $payment_method = '( ' . $dataRes['payment_method_name'] . ' )';
                        }
                        if ($dataRes['payment_method_code'] == PAYMENT_METHOD_CODE_STAMP || empty($dataRes['payment_method_code'])) {
                            $fee_payment_mode_and_fee .= 'Court Fee: Rs. ' . $dataRes['user_declared_court_fee'] . '<br>Printing Charges:  Rs.' . $dataRes['printing_total'] . '<br> Total:  Rs. ' . $dataRes['received_amt'] . ' ( Offline' . $payment_method . ' )<br>';
                        } else {
                            $fee_payment_mode_and_fee .= 'Court Fee: Rs. ' . $dataRes['user_declared_court_fee'] . '<br>Printing Charges:  Rs.' . $dataRes['printing_total'] . '<br> Total: Rs. ' . $dataRes['received_amt'] . ' ( Online ' . ' ( Receipts no. : ' . $dataRes['grn_number'] . ' ))<br>';
                        }
                        $total_fee_paid += $dataRes['received_amt'];
                    } elseif ($dataRes['payment_mode'] == 'online') {                        
                        if (isset($dataRes['payment_mode_name']) && !empty($dataRes['payment_mode_name'])) {
                            $payment_method = '( ' . $dataRes['payment_mode_name' ?? ''] . ' )';
                        }
                        if (!empty($dataRes['txnDate'])) {
                            $payment_date_time = ' Date :' . date('d-m-Y H:i:s A', strtotime($dataRes['txnDate'])) . '<br>';
                        } else {
                            $payment_date_time = '<br>';
                        }
                        if ($dataRes['payment_status'] == 'Y') {
                            $fee_payment_mode_and_fee .= 'Court Fee: Rs. ' . $dataRes['user_declared_court_fee'] . '<br>Printing Charges:  Rs.' . $dataRes['printing_total'] . '<br> Total:  Rs. ' . $dataRes['received_amt'] . ' ( Online' . ' ( Receipts no. : ' . $dataRes['shcilpmtref'] . ' ))' . $payment_date_time;
                        }
                        if ($dataRes['payment_status'] == 'Y') {
                            $total_fee_paid += $dataRes['received_amt'];
                        }
                    }
                }
            }
        }
        $fee_payment_mode_and_fee = !empty($fee_payment_mode_and_fee) ? rtrim($fee_payment_mode_and_fee, '<br>') : '';
        if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
            $new_case_details = $this->Acknowledgement_model->get_new_case_details($registration_id);
            $title = explode('Vs.', $new_case_details[0]['cause_title']);
            $data['view_data'] = array(
                'efiling_name' => !empty($_SESSION['efiling_details']['efiling_for_name']) ? $_SESSION['efiling_details']['efiling_for_name'] : '',
                'efiling_type' => 'New Case',
                'efiling_no' => efile_preview($_SESSION['efiling_details']['efiling_no']),
                'create_on' => isset($efiling_civil_data) ? date('d-m-Y H:i:s A', strtotime($efiling_civil_data[0]['create_on'])) : '',
                'pet_name' => $title[0],
                'res_name' => $title[1],
                'total_ia' => !empty($get_IA) ? $get_IA : '',
                'ref_file_no' => 'NA',
                'payment_type' => $payment_type[0]['payment_type'] ?? '',
                'payment_method_code' => $payment_type[0]['payment_method_code'] ?? '',
                'payment_details' => isset($payment_type) ? $fee_payment_mode_and_fee : '',
                'count_number_of_fee_pay' => $count_number_of_fee_pay,
                'total_amount' => $total_fee_paid,
                'urgent' => isset($efiling_civil_data) ? $efiling_civil_data[0]['urgent'] : '',
                'bench_name' => !empty($efiling_civil_master) ? $efiling_civil_master[0]->bench_name : '',
                'casename' => $new_case_details[0]['casename'],
                'cdeval' => $new_case_details[0]['ifcde'],
                'data_for_qr' => $new_case_details[0]['security_code'],
            );
            if (($data['view_data']['cdeval']) == 1) {
                $efil_no = str_replace('-', '', $data['view_data']['efiling_no']);
                $qrtext = $_SESSION['efiling_details']['registration_id'] . "-" . $efil_no . "-" . ($data['view_data']['data_for_qr']);
                $qr_code_base64 = generate_qr_code(['data' => $qrtext])->base64;
                $imag_base64_en = 'data:image/png;base64' . ',' . $qr_code_base64;
                $image_cntent = file_get_contents($imag_base64_en);
                $path = tempnam(sys_get_temp_dir(), 'prefix');
                file_put_contents($path, $image_cntent);
                $img = '<img width="100" height="100" src="' . $path . '">';
            }
            $file_name_prefix = 'NEW_CASE_';
        }
        if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
            $case_details = $this->Get_details_model->get_case_details($registration_id);
            $title = explode('VS.', $case_details[0]['cause_title']);           
            $data['view_data'] = array(
                'efiling_name' => !empty($_SESSION['efiling_details']['efiling_for_name']) ? $_SESSION['efiling_details']['efiling_for_name'] : '',
                'efiling_type' => 'Misc. Document',
                'sc_case' => $case_details[0]['reg_no_display'] ? $case_details[0]['reg_no_display'] : 'D. No.: ' . $case_details[0]['diary_no'] . '/' . $case_details[0]['diary_year'],
                'efiling_no' => efile_preview($_SESSION['efiling_details']['efiling_no']),
                'create_on' => isset($efiling_civil_data) ? date('d-m-Y H:i:s A', strtotime($efiling_civil_data[0]['create_on'])) : '',
                'pet_name' => $title[0],
                'res_name' =>  $title[1],
                'total_ia' => 'NA',
                'ref_file_no' => 'NA',                
                'payment_type' => $payment_type[0]['payment_type'] ?? '',
                'payment_method_code' => $payment_type[0]['payment_method_code'] ?? '',
                'payment_details' => $fee_payment_mode_and_fee ?? '',
                'count_number_of_fee_pay' => $count_number_of_fee_pay ?? '',
                'total_amount' => $total_fee_paid ?? '',
                's' => $img
            );
            $file_name_prefix = 'MISC_DOCS_';
        }
        if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_DEFICIT_COURT_FEE) {

            $case_details = $this->Get_details_model->get_case_details($registration_id);
            $title = explode('VS.', $case_details[0]['cause_title']);
            $data['view_data'] = array(
                'efiling_name' => $_SESSION['efiling_details']['efiling_for_name'] ?? '',
                'efiling_type' => 'Deficit Court Fee',
                'sc_case' => $case_details[0]['reg_no_display'] ? $case_details[0]['reg_no_display'] : 'D. No.: ' . $case_details[0]['diary_no'] . '/' . $case_details[0]['diary_year'],
                'efiling_no' => efile_preview($_SESSION['efiling_details']['efiling_no']),
                'create_on' => date('d-m-Y', strtotime($_SESSION['efiling_details']['create_on'])),
                'pet_name' => $title[0],
                'res_name' =>  $title[1],
                'total_ia' => 'NA',
                'ref_file_no' => cin_preview($_SESSION['cnr_details']['cnr_num']),
                'payment_type' => $payment_type[0]['payment_type'],
                'payment_method_code' => $payment_type[0]['payment_method_code'],
                'payment_details' => $fee_payment_mode_and_fee,
                'count_number_of_fee_pay' => $count_number_of_fee_pay,
                'total_amount' => $total_fee_paid,
                's' => $img
            );

            $file_name_prefix = 'DEFICIT_COURT_FEE_';
        }
        if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
            $case_details = $this->Get_details_model->get_case_details($registration_id);
            $title = explode('VS.', $case_details[0]['cause_title']);
            $data['view_data'] = array(
                'efiling_name' => $_SESSION['efiling_details']['efiling_for_name'] ?? '',
                'efiling_type' => 'I.A.',
                'sc_case' => $case_details[0]['reg_no_display'] ? $case_details[0]['reg_no_display'] : 'D. No.: ' . $case_details[0]['diary_no'] . '/' . $case_details[0]['diary_year'],
                'efiling_no' => efile_preview($_SESSION['efiling_details']['efiling_no']),
                'create_on' => isset($_SESSION['efiling_details']['create_on']) ?? date('d-m-Y', strtotime($_SESSION['efiling_details']['create_on'])),
                'pet_name' => $title[0],
                'res_name' =>  $title[1],
                'total_ia' => 'NA',
                'payment_type' => $payment_type[0]['payment_type'] ?? '',
                'payment_method_code' => $payment_type[0]['payment_method_code'] ?? '',
                'payment_details' => $fee_payment_mode_and_fee,
                'count_number_of_fee_pay' => $count_number_of_fee_pay,
                'total_amount' => $total_fee_paid,
                's' => $img
            );
            $file_name_prefix = 'IA_';
        }
        if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
            $case_details = $this->Acknowledgement_model->get_caveat_details($registration_id);
            $title = explode('VS.', $case_details[0]['cause_title']);
            $data['view_data'] = array(
                'efiling_name' => $_SESSION['efiling_details']['efiling_for_name'] ?? '',
                'efiling_type' => 'CAVEAT',
                'sc_case' => $case_details[0]['reg_no_display'] ? $case_details[0]['reg_no_display'] : 'D. No.: ' . $case_details[0]['diary_no'] . '/' . $case_details[0]['diary_year'],
                'efiling_no' => efile_preview($_SESSION['efiling_details']['efiling_no']),
                'create_on' => isset($_SESSION['efiling_details']['create_on']) ?? date('d-m-Y', strtotime($_SESSION['efiling_details']['create_on'])),
                'pet_name' => $title[0],
                'res_name' =>  $title[1],
                'total_ia' => 'NA',
                'payment_type' => $payment_type[0]['payment_type'] ?? '',
                'payment_method_code' => $payment_type[0]['payment_method_code'] ?? '',
                'payment_details' => $fee_payment_mode_and_fee,
                'count_number_of_fee_pay' => $count_number_of_fee_pay,
                'total_amount' => $total_fee_paid,
                's' => $img
            );
            $file_name_prefix = 'CAVEATE_';
        }
        $content = view('acknowledgement/case_preview_pdf', $data);

        ob_start();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(TRUE);
        $pdf->SetPrintFooter(TRUE);
        $pdf->SetAuthor('Supreme Court of India');
        $pdf->SetTitle('Supreme Court of India');
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
        $output_file_name = $file_name_prefix ?? '' . $_SESSION['efiling_details']['efiling_no'] . ".pdf";
        $pdf->writeHTML($content . '', true, false, false, false, '');
        $pdf->lastPage();
        ob_end_clean();
        $response = $this->Acknowledgement_model->get_payment_details($_SESSION['efiling_details']['registration_id']);
        if ($response) {
            $i = 1;
        }
        $pdf->Output($output_file_name, 'I');
        exit(0);
    }
}
