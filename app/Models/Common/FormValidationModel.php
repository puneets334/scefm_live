<?php

namespace App\Models\Common;
use CodeIgniter\Model;

class FormValidationModel extends Model {

    protected $validation;

    function __construct() {
        parent::__construct();
        $this->validation = \Config\Services::validation();
    }

    function validate_alpha_numeric_space_dot_hyphen_apostrophe_atrateof_comma_parenthesis($str) {
        if (!preg_match('/^[a-zA-Z0-9. \'-@,()]+$/', $str)) {
            $this->form_validation->set_message('validate_alpha_numeric_space_dot_hyphen_apostrophe_atrateof_comma_parenthesis', 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), space( ), apostrophe(\') and at rate of(@), comma(,), parenthesis of ().<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_alpha_numeric_space_dot_hyphen_underscore($str) {
        if (!preg_match('/^[a-zA-Z0-9.- _]+$/', $str)) {
            $this->form_validation->set_message('alpha_numeric_space_dot_hyphen_underscore_callable', 'The {field} can have only A-Z a-z 0-9 dot(.) hyphen(-) underscore(_) space( ).<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_alpha_numeric_space_dot_hyphen($str) {
        if (!preg_match('/^[a-zA-Z0-9. -]+$/', $str)) {
            $this->form_validation->set_message('alpha_numeric_space_dot_hyphen_callable', 'The {field} can have only A-Z a-z 0-9 dot(.) hyphen(-) space( ).<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_alpha_numeric_space_dot($str) {
        if (!preg_match('/^[a-zA-Z0-9 .]+$/', $str)) {
            $this->form_validation->set_message('alpha_numeric_space_dot_callable', 'The {field} can have only A-Z a-z 0-9 dot(.) space( ).<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_alpha_numeric_space($str) {
        if (!preg_match('/^[a-zA-Z 0-9]+$/', $str)) {
            $this->form_validation->set_message('alpha_numeric_space_callable', 'The {field} can have only A-Z a-z 0-9 space( ).<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_alpha_numeric($str) {
        if (!preg_match('/^[a-zA-Z0-9]+$/', $str)) {
            $this->form_validation->set_message('alpha_numeric_callable', 'The {field} can have only A-Z a-z 0-9. <br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_date_dd_mm_yyyy($str) {
        if (!preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/", $str)) {
            $this->form_validation->set_message('dd_mm_yyyy_date_callable', 'The {field} valid format dd-mm-yyyy.<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_encrypted_value($str) {
        if (isset($str) && !empty($str)) {
            $str = url_decryption($str);
            if ($str == '') {
                $this->form_validation->set_message('encypted_callable', 'The {field} value is tempered.<br>');
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }

}