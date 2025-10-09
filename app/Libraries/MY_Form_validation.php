<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

    function __construct($config = array()) {
        parent::__construct($config);
        $this->CI = & get_instance();
    }

    function encrypt_check($str) {
        $str = url_decryption($str);
        if ($str == '') {
            $this->CI->form_validation->set_message('encrypt_check', lang('form_validation_invalid') . '<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function is_required() {
        $idiom = $this->CI->session->get_userdata('language');
        if ($idiom) {
            $this->CI->lang->load('form_validation', $idiom['site_lang']);
            return TRUE;
        }
    }
    
    function validate_alpha_numeric_hyphen($str) {

        if (!preg_match('/^[a-zA-Z0-9-]+$/', $str)) {
            $this->CI->form_validation->set_message('validate_alpha_numeric_hyphen', 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), underscore(_), space( ).<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function validate_alpha_numeric_space_dot_hyphen_underscore_slash($str) {
// THIS FUNCTION IS NOT WORKING -- VALIDATE FORWARD SLASH
        if (!preg_match('/^[a-zA-Z0-9.- _\\/-]+$/', $str)) {
            $this->CI->form_validation->set_message('validate_alpha_numeric_space_dot_hyphen_underscore_slash', 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), underscore(_), space( ).<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }


    function validate_alpha_numeric_space_dot_hyphen_underscore($str) {

        if (!preg_match('/^[a-zA-Z0-9.- _]+$/', $str)) {
            $this->CI->form_validation->set_message('validate_alpha_numeric_space_dot_hyphen_underscore', 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), underscore(_), space( ).<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_alpha_numeric_space_dot_hyphen($str) {

        if (!preg_match('/^[a-zA-Z0-9. -]+$/', $str)) {
            $this->CI->form_validation->set_message('validate_alpha_numeric_space_dot_hyphen', 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), space( ).<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    function validate_alpha_numeric_space_dot_hyphen_apostrophe($str) {

        if (!preg_match('/^[a-zA-Z0-9. \'-]+$/', $str)) {
            $this->CI->form_validation->set_message('validate_alpha_numeric_space_dot_hyphen_apostrophe', 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), space( ) and apostrophe(\').<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    function validate_alpha_numeric_space_dot_hyphen_apostrophe_atrateof_comma_parenthesis($str) {

        if (!preg_match('/^[a-zA-Z0-9. \'-@,()]+$/', $str)) {
            $this->CI->form_validation->set_message('validate_alpha_numeric_space_dot_hyphen_apostrophe_atrateof_comma_parenthesis', 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), space( ), apostrophe(\') and at rate of(@), comma(,), parenthesis of ().<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    function validate_alpha_numeric_single_double_quotes_bracket_with_special_characters($str) {
        $pattern = '/^[a-zA-Z0-9.@,-\\/\()\'\"  ]+$/';
        if (!preg_match($pattern, $str)) {
            $this->CI->form_validation->set_message('validate_alpha_numeric_single_double_quotes_bracket_with_special_characters', 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), space( ),slash(/),comma(,),  brackets ( () ), single quotes (\'),double quotes (") .<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    function validate_alpha_numeric_space_dot_hyphen_apostrophe_atrateof($str) {

        if (!preg_match('/^[a-zA-Z0-9. \'-@]+$/', $str)) {
            $this->CI->form_validation->set_message('validate_alpha_numeric_space_dot_hyphen_apostrophe_atrateof', 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), space( ), apostrophe(\') and at rate of(@).<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    function validate_alpha_numeric_space_dot_hyphen_comma($str) {

        if (!preg_match('/^[a-zA-Z0-9., -]+$/', $str)) {
            $this->CI->form_validation->set_message('validate_alpha_numeric_space_dot_hyphen_comma', 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), space( ), comma(,).<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    function validate_alpha_numeric_space_dot_hyphen_comma_slash($str) {

        if (!preg_match('/^[a-zA-Z0-9., \\/-]+$/', $str)) {
            $this->CI->form_validation->set_message('validate_alpha_numeric_space_dot_hyphen_comma_slash', 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), space( ), comma(,),slash(/).<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_alpha_numeric_with_special_characters($str) {

        if (!preg_match('/^[a-zA-Z0-9.,;( )? ! \'-]+$/', $str)) {
            $this->CI->form_validation->set_message('validate_alpha_numeric_with_special_characters', 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), space( ), comma(,), semicolon(;), brackets ( () ), question mark (?) .<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }

//    function validate_alpha_numeric_single_double_quotes_bracket_with_special_characters($str) {
//        $pattern = '/^[a-zA-Z0-9.,-\\/\()\'\"  ]+$/';
//        if (!preg_match($pattern, $str)) {
//            $this->CI->form_validation->set_message('validate_alpha_numeric_single_double_quotes_bracket_with_special_characters', 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), space( ),slash(/),comma(,),  brackets ( () ), single quotes (\'),double quotes (") .<br>');
//            return FALSE;
//        } else {
//            return TRUE;
//        }
//    }

    function validate_alpha_numeric_space_dot($str) {

        if (!preg_match('/^[a-zA-Z0-9 .]+$/', $str)) {
            $this->CI->form_validation->set_message('validate_alpha_numeric_space_dot', 'The {field} can have only A-Z, a-z, 0-9, dot(.), space( ).<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_alpha_numeric_space($str) {

        if (!preg_match('/^[a-zA-Z 0-9]+$/', $str)) {
            $this->CI->form_validation->set_message('validate_alpha_numeric_space', 'The {field} can have only A-Z, a-z, 0-9, space( ).<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_alpha_numeric($str) {

        if (!preg_match('/^[a-zA-Z0-9]+$/', $str)) {
            $this->CI->form_validation->set_message('validate_alpha_numeric', 'The {field} can have only A-Z, a-z, 0-9. <br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function validate_alphabatic($str) {

        if (!preg_match('/^[a-zA-Z]+$/', $str)) {
            $this->CI->form_validation->set_message('validate_alphabatic', 'The {field} can have only A-Z, a-z. <br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function validate_alphabatic_with_space($str) {

        if (!preg_match('/^[a-zA-Z ]+$/', $str)) {
            $this->CI->form_validation->set_message('validate_alphabatic_with_space', 'The {field} can have only A-Z, a-z, space( ). <br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function validate_alphabatic_with_dot($str) {

        if (!preg_match('/^[a-zA-Z.]+$/', $str)) {
            $this->CI->form_validation->set_message('validate_alphabatic_with_dot', 'The {field} can have only A-Z, a-z, dot(.). <br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_date_dd_mm_yyyy($str) {

        if (!preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/", $str)) {
            $this->CI->form_validation->set_message('validate_date_dd_mm_yyyy', 'The {field} valid format dd-mm-yyyy.<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_encrypted_value($str) {

        if (isset($str) && !empty($str)) {
            $str = url_decryption($str);

            if ($str == '') {
                $this->CI->form_validation->set_message('validate_encrypted_value', 'The {field} value is tempered.<br>');
                return FALSE;
            } else {
                return TRUE;
            }
        }else{
            return TRUE;
        }
    }
    

    function user_name_pattern($user_id) {

        if ($user_id == '') {
            $this->CI->form_validation->set_message('user_name_pattern', 'User ID could not be blank.');
            return FALSE;
        } elseif (preg_match('/[^A-Za-z0-9-]/i', $user_id)) {
            $this->CI->form_validation->set_message('user_name_pattern', 'User ID could not in correct format.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function password_pattern($password) {

        if ($password == '') {
            $this->CI->form_validation->set_message('password_pattern', 'Password could not be blank.');
            return FALSE;
        } elseif (preg_match('/[^A-Za-z0-9!@#$]/i', $password)) {
            $this->CI->form_validation->set_message('password_pattern', 'Password could not in correct format.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function captcha_pattern($captcha) {

        if ($captcha == '') {
            $this->CI->form_validation->set_message('captcha_pattern', 'Captcha could not be blank.');
            return FALSE;
        } elseif (preg_match('/[^0-9]/i', $captcha)) {
            $this->CI->form_validation->set_message('captcha_pattern', 'Incorrect captcha.');
            return FALSE;
        } elseif ($this->CI->session->userdata['captchaWord'] != $captcha) {
            $this->CI->form_validation->set_message('captcha_pattern', 'Captcha could not match.');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    function validate_alphacharacters($str) {
        if (!preg_match('/^[a-zA-Z` ]+$/', $str)) {
            $this->CI->form_validation->set_message('validate_alphacharacters', 'The {field} can have only A-Z a-z`<br>');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
