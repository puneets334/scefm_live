<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dropdown_list extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('common/Dropdown_list_model');
    }

    public function get_high_court_code() {
        $high_court_id = url_decryption(escape_data($_POST['high_court_id']));
        if (isset($high_court_id) && strlen($high_court_id) <= INTEGER_FIELD_LENGTH) {
            if (!empty($high_court_id)) {
                $result = $this->Dropdown_list_model->get_highcourt_code($high_court_id);
                if (count($result)) {
                    $payment_type = '<option value="">Select</option>';
                    foreach ($result as $dataRel) {
                        $enable_e_payment = explode("$", $dataRel->enable_e_payment);
                        foreach ($enable_e_payment as $payment) {
                            if ($payment == 'F') {
                                $type = "Fine";
                            }
                            if ($payment == 'P') {
                                $type = "Penalty";
                            }
                            if ($payment == 'C') {
                                $type = "Court Fee";
                            }if ($payment == 'O') {
                                $type = "Other";
                            }
                            if ($payment == 'J') {
                                $type = "Judicial Deposit";
                            }


                            $payment_type .= "<option value='" . url_encryption($payment) . "'>" . $type . "</option>";
                        }
                        echo $value = $dataRel->hc_code . '@@@' . $dataRel->state_code . '@@@' . $payment_type;
                    }
                }
            }
        }
    }

    public function get_estab_code() {
        if (isset($_POST['establishment_id'])) {
            if (!empty($_POST['establishment_id'])) {

                $establishment_id = url_decryption(escape_data($_POST['establishment_id']));
                $result = $this->Dropdown_list_model->get_eshtablishment_code($establishment_id);

                $payment_type = '<option value="">Select</option>';

                if (count($result)) {

                    foreach ($result as $dataRel) {
                        $enable_e_payment = explode("$", $dataRel->enable_e_payment);
                        foreach ($enable_e_payment as $payment) {
                            if ($payment == 'F') {
                                $type = "Fine";
                            }
                            if ($payment == 'P') {
                                $type = "Penalty";
                            }
                            if ($payment == 'C') {
                                $type = "Court Fee";
                            }if ($payment == 'O') {
                                $type = "Other";
                            }
                            if ($payment == 'J') {
                                $type = "Judicial Deposit";
                            }


                            $payment_type .= "<option value='" . url_encryption($payment) . "'>" . $type . "</option>";
                        }
                        echo $value = $dataRel->est_code . '@@@' . $dataRel->state_code . '@@@' . $payment_type;
                    }
                }
            }
        }
    }

    public function get_district_list() {
        if (isset($_POST['state_id']) && preg_match("/^[0-9]*$/", $_POST['state_id']) && isset($_POST['efiling_for_type_id']) && preg_match("/^[0-9]*$/", $_POST['efiling_for_type_id']) && isset($_POST['efiling_for_id']) && preg_match("/^[0-9]*$/", $_POST['efiling_for_id']) && strlen($_POST['state_id']) <= INTEGER_FIELD_LENGTH && strlen($_POST['efiling_for_type_id']) <= INTEGER_FIELD_LENGTH && strlen($_POST['efiling_for_id']) <= INTEGER_FIELD_LENGTH) {
            if (!empty($_POST['state_id'])) {
                $state_id = escape_data($_POST['state_id']);
                $efiling_for_type_id = escape_data($_POST['efiling_for_type_id']);
                $efiling_for_id = escape_data($_POST['efiling_for_id']);
                $result = $this->Dropdown_list_model->district_list_address($state_id, $efiling_for_id, $efiling_for_type_id);
                if (count($result)) {
                    echo '<option value=""> Select District</option>';
                    foreach ($result as $dataActs) {
                        $value = $dataActs->id;
                        echo '<option value="' . htmlentities($value, ENT_QUOTES) . '">' . '' . htmlentities(strtoupper($dataActs->dist_name), ENT_QUOTES) . '</option>';
                    }
                } else {
                    echo '<option value=""> Select District </option>';
                }
            } else {
                echo '<option value=""> Select District </option>';
            }
        } else {
            echo '<option value=""> Select District</option>';
        }
    }

    public function get_district_list_y() {
        $state_id = url_decryption(escape_data($_POST['state_id']));
        if (isset($state_id) && preg_match("/^[0-9]*$/", $state_id) && strlen($state_id) <= INTEGER_FIELD_LENGTH) {
            if (!empty($_POST['state_id'])) {
                $result = $this->Dropdown_list_model->get_district_list($state_id);
                if (count($result)) {
                    echo '<option value=""> Select District</option>';
                    foreach ($result as $dataActs) {
                        $value = $dataActs->id;
                        echo '<option value="' . htmlentities(url_encryption($value), ENT_QUOTES) . '">' . '' . htmlentities(strtoupper($dataActs->dist_name), ENT_QUOTES) . '</option>';
                    }
                } else {
                    echo '<option value=""> Select District </option>';
                }
            } else {
                echo '<option value=""> Select District </option>';
            }
        } else {
            echo '<option value=""> Select District</option>';
        }
    }

    public function get_district_list_with_code() {
        if (!empty($_POST['mystate_id'])) {
            $state = explode('#$', url_decryption($_POST['mystate_id']));
            $state_id = $state[0];
        } else {
            $state_id = url_decryption(escape_data($_POST['state_id']));
        }
        if (isset($state_id) && preg_match("/^[0-9]*$/", $state_id) && strlen($state_id) <= INTEGER_FIELD_LENGTH) {
            if (!empty($_POST['state_id']) || !empty($_POST['mystate_id'])) {
                $result = $this->Dropdown_list_model->get_district_list($state_id);
                if (count($result)) {
                    echo '<option value=""> Select District</option>';
                    foreach ($result as $dataActs) {
                        $value = $dataActs->id . '#$' . $dataActs->dist_code;
                        echo '<option value="' . htmlentities(url_encryption($value), ENT_QUOTES) . '">' . '' . htmlentities(strtoupper($dataActs->dist_name), ENT_QUOTES) . '</option>';
                    }
                } else {
                    echo '<option value=""> Select District </option>';
                }
            } else {
                echo '<option value=""> Select District </option>';
            }
        } else {
            echo '<option value=""> Select District</option>';
        }
    }

    public function eshtablishment_list_by_dist_code() {

        $distt_id_array = explode('#$', url_decryption($_POST['get_distt_id']));
        $ref_m_distt_id = $distt_id_array[0];
        $distt_code = $distt_id_array[1];
        if (isset($ref_m_distt_id) && preg_match("/^[0-9]*$/", $ref_m_distt_id) && strlen($ref_m_distt_id) <= INTEGER_FIELD_LENGTH) {
            if (!empty($ref_m_distt_id)) {
                $result = $this->Dropdown_list_model->get_eshtablishment_list($ref_m_distt_id);
                if (count($result)) {
                    echo '<option value=""> Select Establishment </option>';
                    foreach ($result as $dataActs) {
                        $value = $dataActs->id;
                        echo '<option value="' . htmlentities(url_encryption($value), ENT_QUOTES) . '">' . '' . htmlentities(strtoupper($dataActs->estname), ENT_QUOTES) . '</option>';
                    }
                } else {
                    echo '<option value=""> Select Establishment </option>';
                }
            }
        } else {
            echo '<option value=""> Select  Establishment</option>';
        }
    }

    public function get_eshtablishment_list() {
        $ref_m_distt_id = url_decryption($_POST['get_distt_id']);

        if (isset($ref_m_distt_id) && preg_match("/^[0-9]*$/", $ref_m_distt_id) && strlen($ref_m_distt_id) <= INTEGER_FIELD_LENGTH) {
            if (!empty($ref_m_distt_id)) {
                $result = $this->Dropdown_list_model->get_eshtablishment_list($ref_m_distt_id);
                if (count($result)) {
                    echo '<option value=""> Select Establishment </option>';
                    foreach ($result as $dataActs) {
                        $value = $dataActs->id;
                        echo '<option value="' . htmlentities(url_encryption($value), ENT_QUOTES) . '">' . '' . htmlentities(strtoupper($dataActs->estname), ENT_QUOTES) . '</option>';
                    }
                } else {
                    echo '<option value=""> Select Establishment </option>';
                }
            }
        } else {
            echo '<option value=""> Select  Establishment</option>';
        }
    }

    public function get_benchcourt_list() {

        $hc_id = url_decryption(escape_data($_POST['hc_id']));
        $result = $this->Dropdown_list_model->get_bench_court_list($hc_id);
        if (count($result)) {
            echo '<option value=""> Select</option>';
            foreach ($result as $databench) {
                $value = $databench['id'];
                echo '<option value="' . htmlentities(url_encryption($value), ENT_QUOTES) . '">' . '' . htmlentities($databench['hc_name'], ENT_QUOTES) . '</option>';
            }
        } else {
            echo 0;
        }
    }

    public function get_state_list() {

        $result = $this->Dropdown_list_model->get_state_list_ajax();
        if (count($result)) {
            echo '<option value=""> Select</option>';
            foreach ($result as $dataActs) {
                $value = $dataActs->state_id;
                echo '<option value="' . htmlentities($value, ENT_QUOTES) . '">' . '' . htmlentities($dataActs->state, ENT_QUOTES) . '</option>';
            }
        } else {
            echo '<option value=""> Select</option>';
        }
    }

    public function count_pet_and_res() {
        if (isset($_POST['count_pet_and_res']) && preg_match("/^[0-9]*$/", $_POST['count_pet_and_res']) && strlen($_POST['count_pet_and_res']) <= INTEGER_FIELD_LENGTH) {
            if (!empty($_POST['count_pet_and_res'])) {
                $reg_id = $_POST['count_pet_and_res'];
                $result = $this->Dropdown_list_model->count_pet_and_res($reg_id);
                echo htmlentities($result[0]->pet_extracount, ENT_QUOTES) . '@@@' . htmlentities($result[0]->res_extracount, ENT_QUOTES);
            } else {
                
            }
        }
    }

    public function filing_type() {
        if (isset($_POST['filing_type'])) {
            if (!empty($_POST['filing_type'])) {
                $result = url_decryption(escape_data($_POST['filing_type']));
                if ($result) {
                    echo $result;
                } else {
                    echo 'type_error';
                }
            }
        }
    }

    public function eshtablishment_list_checkbox() {
        $ref_m_distt_id = url_decryption($_POST['get_distt_id']);
        if (isset($ref_m_distt_id) && preg_match("/^[0-9]*$/", $ref_m_distt_id) && strlen($ref_m_distt_id) <= INTEGER_FIELD_LENGTH) {
            if (!empty($ref_m_distt_id)) {
                $result = $this->Dropdown_list_model->get_eshtablishment_list1($ref_m_distt_id);
                if (count($result)) {
                    echo '<label class="control-label col-sm-5 input-sm">Court Establishment <span style="color: red">*</span> :</label>';
                    echo '<div class="col-sm-7">';
                    foreach ($result as $dataActs) {
                        $value = $dataActs->id;
                        echo '<input type="checkbox" id="reg_court_complex_id" name="reg_court_complex_id[]" value="' . url_encryption(trim($value)) . '"/> ' . strtoupper($dataActs->estname) . '</br>';
                    }
                    echo '</div>';
                } else {
                    echo '<label class="col-md-5 col-md-offset-5"> Complex Not Available</label>';
                }
            }
        } else {
            echo '<label class="col-md-5 col-md-offset-5"> Complex Not Available</label>';
        }
    }

    public function all_master_data_based_on_state() {
        $state_id = url_decryption(escape_data($_POST['state_id']));
        if (isset($state_id) && preg_match("/^[0-9]*$/", $state_id) && strlen($state_id) <= INTEGER_FIELD_LENGTH) {
            if (!empty($_POST['state_id'])) {
                $result = $this->Dropdown_list_model->get_district_list($state_id);
                if (count($result)) {
                    echo '<option value=""> Select District</option>';
                    foreach ($result as $dataActs) {
                        $value = $dataActs->id . '#$' . $dataActs->dist_code;
                        echo '<option value="' . htmlentities(url_encryption($value), ENT_QUOTES) . '">' . '' . htmlentities(strtoupper($dataActs->dist_name), ENT_QUOTES) . '</option>';
                    }
                } else {
                    echo '<option value=""> Select District </option>';
                }
            } else {
                echo '<option value=""> Select District </option>';
            }
        } else {
            echo '<option value=""> Select District</option>';
        }
    }

    public function all_master_data_based_on_district() {

        $distt_id_array = explode('#$', url_decryption($_POST['get_distt_id']));
        $ref_m_distt_id = $distt_id_array[0];
        $distt_code = $distt_id_array[1];
        if (isset($ref_m_distt_id) && preg_match("/^[0-9]*$/", $ref_m_distt_id) && strlen($ref_m_distt_id) <= INTEGER_FIELD_LENGTH) {
            if (!empty($ref_m_distt_id)) {
                $result = $this->Dropdown_list_model->get_eshtablishment_list($ref_m_distt_id);
                if (count($result)) {
                    echo '<option value=""> Select Establishment </option>';
                    foreach ($result as $dataActs) {
                        $value = $dataActs->id . '#$' . $dataActs->est_code . '#$' . $dataActs->state_code;
                        echo '<option value="' . htmlentities(url_encryption($value), ENT_QUOTES) . '">' . '' . htmlentities(strtoupper($dataActs->estname), ENT_QUOTES) . '</option>';
                    }
                } else {
                    echo '<option value=""> Select Establishment </option>';
                }
            }
        } else {
            echo '<option value=""> Select  Establishment</option>';
        }
    }

    public function all_master_data_based_on_establishment() {

        $distt_id_array = explode('#$', url_decryption($_POST['get_distt_id']));
        $ref_m_distt_id = $distt_id_array[0];
        $distt_code = $distt_id_array[1];
        if (isset($ref_m_distt_id) && preg_match("/^[0-9]*$/", $ref_m_distt_id) && strlen($ref_m_distt_id) <= INTEGER_FIELD_LENGTH) {
            if (!empty($ref_m_distt_id)) {
                $result = $this->Dropdown_list_model->get_eshtablishment_list($ref_m_distt_id);
                if (count($result)) {
                    echo '<option value=""> Select Establishment </option>';
                    foreach ($result as $dataActs) {
                        $value = $dataActs->id;
                        echo '<option value="' . htmlentities(url_encryption($value), ENT_QUOTES) . '">' . '' . htmlentities(strtoupper($dataActs->estname), ENT_QUOTES) . '</option>';
                    }
                } else {
                    echo '<option value=""> Select Establishment </option>';
                }
            }
        } else {
            echo '<option value=""> Select  Establishment</option>';
        }
    }

    public function get_payment_type() {
        $court_data = explode("#", url_decryption($_POST['court_data']));
        if ($court_data[0] == 1) {

            $payment_type = '<option value="">Select</option>';

            $enable_e_payment = explode("$", $court_data[5]);
            foreach ($enable_e_payment as $payment) {
                if ($payment == 'F') {
                    $type = "Fine";
                }
                if ($payment == 'P') {
                    $type = "Penalty";
                }
                if ($payment == 'C') {
                    $type = "Court Fee";
                }if ($payment == 'O') {
                    $type = "Other";
                }
                if ($payment == 'J') {
                    $type = "Judicial Deposit";
                }


                $payment_type .= "<option value='" . url_encryption($payment) . "'>" . $type . "</option>";
            }
        }
        if ($court_data[0] == 2) {

            $payment_type = '<option value="">Select</option>';

            $enable_e_payment = explode("$", $court_data[12]);
            foreach ($enable_e_payment as $payment) {
                if ($payment == 'F') {
                    $type = "Fine";
                }
                if ($payment == 'P') {
                    $type = "Penalty";
                }
                if ($payment == 'C') {
                    $type = "Court Fee";
                }if ($payment == 'O') {
                    $type = "Other";
                }
                if ($payment == 'J') {
                    $type = "Judicial Deposit";
                }


                $payment_type .= "<option value='" . url_encryption($payment) . "'>" . $type . "</option>";
            }
        }echo $payment_type;
    }

    public function eshtablishment_list() {

        $distt_id_array = explode('#$', url_decryption($_POST['get_distt_id']));
        $ref_m_distt_id = $distt_id_array[0];
        $distt_code = $distt_id_array[1];
        if (isset($ref_m_distt_id) && preg_match("/^[0-9]*$/", $ref_m_distt_id) && strlen($ref_m_distt_id) <= INTEGER_FIELD_LENGTH) {
            if (!empty($ref_m_distt_id)) {
                $result = $this->Dropdown_list_model->get_eshtablishment_list($ref_m_distt_id);
                if (count($result)) {
                    foreach ($result as $dataActs) {
                        $value .= $dataActs->id . '#';
                    }echo !empty($value) ? url_encryption(rtrim($value, "#")) : '';
                }
            }
        }
    }

    public function get_estab_code_list() {
        $ref_m_distt = explode("#$", url_decryption($_POST['get_distt_id']));
        $ref_m_distt_id = $ref_m_distt[0];
        if (isset($ref_m_distt_id) && preg_match("/^[0-9]*$/", $ref_m_distt_id) && strlen($ref_m_distt_id) <= INTEGER_FIELD_LENGTH) {
            if (!empty($ref_m_distt_id)) {
                $result = $this->Dropdown_list_model->get_eshtablishment_list($ref_m_distt_id);
                if (count($result)) {
                    echo '<option value=""> Select Establishment </option>';
                    foreach ($result as $dataActs) {
                        $value = $dataActs->id . '#$' . strtoupper($dataActs->est_code) . '#$' . strtoupper($dataActs->estname);
                        echo '<option value="' . htmlentities(url_encryption($value), ENT_QUOTES) . '">' . '' . htmlentities(strtoupper($dataActs->estname), ENT_QUOTES) . '</option>';
                    }
                } else {
                    echo '<option value=""> Select Establishment </option>';
                }
            }
        } else {
            echo '<option value=""> Select  Establishment</option>';
        }
    }

}

?>