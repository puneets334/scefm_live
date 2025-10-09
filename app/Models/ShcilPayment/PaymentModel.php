<?php
namespace App\Models\ShcilPayment;

use CodeIgniter\Model;
class PaymentModel extends Model {

    function __construct() {
// Call the Model constructor
        parent::__construct();
    }



    public function updatePayment($registration_id, $order_ref_no, $payment_detail)
    {
        $this->db->transStart(); // Start transaction
        $builder = $this->db->table('efil.tbl_court_fee_payment');
        $builder->where('registration_id', $registration_id);
        $builder->where('order_no', $order_ref_no);
        $builder->set($payment_detail);
        $builder->update();

        if (session()->get('efiling_details.ref_m_efiled_type_id') == E_FILING_TYPE_DEFICIT_COURT_FEE) {
            $deficit_Fee_success_arr = [
                'efiling_no' => session()->get('efiling_details.efiling_no'),
                'deficit_Amt_Rec' => $payment_detail
            ];
            session()->set('deficit_Fee_success', $deficit_Fee_success_arr);
        }

        if (isset($payment_detail['payment_status']) && $payment_detail['payment_status'] == 'Y') {
            $is_already_paid_court_fee = $this->is_already_paid_court_fee($registration_id, $order_ref_no);
            if ($is_already_paid_court_fee && $is_already_paid_court_fee != false) {
                $builder = $this->db->table('efil.tbl_uploaded_pdfs');
                $builder->where('registration_id', $registration_id);
                $builder->where('uploaded_by', session()->get('login.id'));
                $builder->where('is_deleted', false);
                $builder->where('tbl_court_fee_payment_id', null);
                $builder->set(['tbl_court_fee_payment_id' => $is_already_paid_court_fee[0]['id']]);
                $builder->update();
            }

            // Determine breadcrumb updates based on efiling type
            $breadcrumb_to_update = '';
            $breadcrumb_to_remove = '';

            switch (session()->get('efiling_details.ref_m_efiled_type_id')) {
                case E_FILING_TYPE_NEW_CASE:
                    $breadcrumb_to_update = NEW_CASE_COURT_FEE;
                    $breadcrumb_to_remove = NEW_CASE_AFFIRMATION;
                    break;
                case E_FILING_TYPE_MISC_DOCS:
                    $breadcrumb_to_update = MISC_BREAD_COURT_FEE;
                    $breadcrumb_to_remove = MISC_BREAD_AFFIRMATION;
                    break;
                case E_FILING_TYPE_IA:
                    $breadcrumb_to_update = IA_BREAD_COURT_FEE;
                    $breadcrumb_to_remove = IA_BREAD_AFFIRMATION;
                    break;
                case E_FILING_TYPE_MENTIONING:
                    $breadcrumb_to_update = MEN_BREAD_COURT_FEE;
                    $breadcrumb_to_remove = MEN_BREAD_AFFIRMATION;
                    break;
                case E_FILING_TYPE_CAVEAT:
                    $breadcrumb_to_update = CAVEAT_BREAD_COURT_FEE;
                    $breadcrumb_to_remove = CAVEAT_BREAD_VIEW;
                    break;
                // Handle other cases as needed
            }

            // Update breadcrumbs
            if (!empty($breadcrumb_to_remove)) {
                $this->remove_breadcrumb($registration_id, $breadcrumb_to_remove);
            }
            if (!empty($breadcrumb_to_update)) {
                $this->update_breadcrumbs($registration_id, $breadcrumb_to_update);
            }
        }

        $this->db->transComplete(); // Complete transaction

        return $this->db->transStatus(); // Return transaction status
    }

    public function update_breadcrumbs($registration_id, $step_no)
    {
        $old_breadcrumbs = !empty(getSessionData('efiling_details')) ? getSessionData('efiling_details')['breadcrumb_status']. ',' . $step_no : '' ;
        $old_breadcrumbs_array = explode(',', $old_breadcrumbs);
        $new_breadcrumbs_array = array_unique($old_breadcrumbs_array);
        sort($new_breadcrumbs_array);
        $new_breadcrumbs = implode(',', $new_breadcrumbs_array);
        $sData = getSessionData('efiling_details');
        $mergeData=array_merge(getSessionData('efiling_details'),array('breadcrumb_status' =>$new_breadcrumbs));
        setSessionData('efiling_details', $mergeData);
        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->where('registration_id', $registration_id);
        $builder->set(array('breadcrumb_status' => $new_breadcrumbs));
        $builder->update();
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function remove_breadcrumb($registration_id, $breadcrumb_to_remove)
    {
        // Get current breadcrumbs from session
        $breadcrumbs = getSessionData('efiling_details.breadcrumb_status');
        // Convert breadcrumbs string to array
        $breadcrumbs_array = explode(',', $breadcrumbs);
        // Check if breadcrumb_to_remove exists in the array
        if (in_array($breadcrumb_to_remove, $breadcrumbs_array)) {
            // Find the index of breadcrumb_to_remove
            $index = array_search($breadcrumb_to_remove, $breadcrumbs_array);
            // Remove the breadcrumb if found
            if ($index !== false) {
                unset($breadcrumbs_array[$index]);
            }
            // Create new breadcrumbs string
            $new_breadcrumbs = implode(',', $breadcrumbs_array);
            // Perform database update
            $builder = $this->db->table('efil.tbl_efiling_nums');
            $builder->where('registration_id', $registration_id);
            $builder->set(['breadcrumb_status' => $new_breadcrumbs]);
            $builder->update();
            // Check if update was successful
            if ($this->db->affectedRows() > 0) {
                // Update session variable
                session()->set('efiling_details.breadcrumb_status', $new_breadcrumbs);
                return true;
            } else {
                return false;
            }
        }
    }

    public function is_already_paid_court_fee($registration_id, $order_no)
    {
        $builder = $this->db->table('efil.tbl_court_fee_payment');
        $builder->select("*");
        $builder->where('registration_id', $registration_id);
        $builder->where('order_no', $order_no);
        $builder->where('is_deleted', false);
        $builder->where('payment_status', 'Y');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
}
