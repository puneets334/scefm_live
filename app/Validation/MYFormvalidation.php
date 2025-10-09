<?php
namespace App\Validation;

class MYFormvalidation {

    // function __construct($config = array()) {
    //     parent::__construct($config);
    //     $this->CI = & get_instance();
    // }

    function encryptCheck(?string $str = NULL, ?string &$error = null): bool{
        $str = url_decryption($str);
        if ($str == '') {
            $error = lang('form_validation_invalid');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    // public function is_required() {
    //     $idiom = $this->CI->session->get_userdata('language');
    //     if ($idiom) {
    //         $this->CI->lang->load('form_validation', $idiom['site_lang']);
    //         return TRUE;
    //     }
    // }
    
    function validateAlphaNumericHyphen(?string $str = NULL, ?string &$error = null): bool{

        if (!preg_match('/^[a-zA-Z0-9-]+$/', $str)) {
            $error = 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), underscore(_), space( ).';
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function validate_alpha_numeric_space_dot_hyphen_underscore_slash(?string $str = NULL, ?string &$error = null): bool{
// THIS FUNCTION IS NOT WORKING -- VALIDATE FORWARD SLASH
        if (!preg_match('/^[a-zA-Z0-9.- _\\/-]+$/', $str)) {
            $error = 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), underscore(_), space( ).';
            return FALSE;
        } else {
            return TRUE;
        }
    }


    function validate_alpha_numeric_space_dot_hyphen_underscore(?string $str = NULL, ?string &$error = null): bool{

        if (!preg_match('/^[a-zA-Z0-9.- _]+$/', $str)) {
            $error = 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), underscore(_), space( ).';
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_alpha_numeric_space_dot_hyphen(?string $str = NULL, ?string &$error = null): bool{

        if (!preg_match('/^[a-zA-Z0-9. -\\/]+$/', $str)) {
            $error = 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), space( ), slash(/).';
            return FALSE;
        } else {
            return TRUE;
        }
    }
    function validate_alpha_numeric_space_dot_hyphen_apostrophe(?string $str = NULL, ?string &$error = null): bool{

        if (!preg_match('/^[a-zA-Z0-9. \'-]+$/', $str)) {
            $error = 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), space( ) and apostrophe(\').';
            return FALSE;
        } else {
            return TRUE;
        }
    }
    function validate_alpha_numeric_space_dot_hyphen_apostrophe_atrateof_comma_parenthesis(?string $str = NULL, ?string &$error = null): bool{

        if (!preg_match('/^[a-zA-Z0-9. \'-@,()]+$/', $str)) {
            $error = 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), space( ), apostrophe(\') and at rate of(@), comma(,), parenthesis of ().';
            return FALSE;
        } else {
            return TRUE;
        }
    }
    function validateAlphaNumericSingleDoubleQuotesBracketWithSpecialCharacters(?string $str = NULL, ?string &$error = null): bool{
        log_message('debug',$str);
        $pattern = '/^[a-zA-Z0-9.@,-\\/\()\'\"  ]+$/';
        if (!preg_match($pattern, $str)) {
            $error = 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), space( ),slash(/),comma(,),  brackets ( () ), single quotes (\'),double quotes (") .';
            return FALSE;
        } else {
            return TRUE;
        }
    }
    function validate_alpha_numeric_space_dot_hyphen_apostrophe_atrateof(?string $str = NULL, ?string &$error = null): bool{

        if (!preg_match('/^[a-zA-Z0-9. \'-@]+$/', $str)) {
            $error = 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), space( ), apostrophe(\') and at rate of(@).';
            return FALSE;
        } else {
            return TRUE;
        }
    }
    function validate_alpha_numeric_space_dot_hyphen_comma(?string $str = NULL, ?string &$error = null): bool{

        if (!preg_match('/^[a-zA-Z0-9., -]+$/', $str)) {
            $error = 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), space( ), comma(,).';
            return FALSE;
        } else {
            return TRUE;
        }
    }
    function validate_alpha_numeric_space_dot_hyphen_comma_slash(?string $str = NULL, ?string &$error = null): bool{

        if (!preg_match('/^[a-zA-Z0-9., \\/-]+$/', $str)) {
            $error = 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), space( ), comma(,),slash(/).';
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_alpha_numeric_with_special_characters(?string $str = NULL, ?string &$error = null): bool{

        if (!preg_match('/^[a-zA-Z0-9.,;( )? ! \'-]+$/', $str)) {
            $error = 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), space( ), comma(,), semicolon(;), brackets ( () ), question mark (?).';
            return FALSE;
        } else {
            return TRUE;
        }
    }

//    function validate_alpha_numeric_single_double_quotes_bracket_with_special_characters(?string $str = NULL, ?string &$error = null): bool{
//        $pattern = '/^[a-zA-Z0-9.,-\\/\()\'\"  ]+$/';
//        if (!preg_match($pattern, $str)) {
//            $this->CI->form_validation->set_message('validate_alpha_numeric_single_double_quotes_bracket_with_special_characters', 'The {field} can have only A-Z, a-z, 0-9, dot(.), hyphen(-), space( ),slash(/),comma(,),  brackets ( () ), single quotes (\'),double quotes (") .<br>');
//            return FALSE;
//        } else {
//            return TRUE;
//        }
//    }

    function validate_alpha_numeric_space_dot(?string $str = NULL, ?string &$error = null): bool{

        if (!preg_match('/^[a-zA-Z0-9 .]+$/', $str)) {
            $error = 'The {field} can have only A-Z, a-z, 0-9, dot(.), space( ).';
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_alpha_numeric_space(?string $str = NULL, ?string &$error = null): bool{

        if (!preg_match('/^[a-zA-Z 0-9]+$/', $str)) {
            $error = 'The {field} can have only A-Z, a-z, 0-9, space( ).';
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_alpha_numeric(?string $str = NULL, ?string &$error = null): bool{

        if (!preg_match('/^[a-zA-Z0-9]+$/', $str)) {
            $error = 'The {field} can have only A-Z, a-z, 0-9.';
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function validate_alphabatic(?string $str = NULL, ?string &$error = null): bool{

        if (!preg_match('/^[a-zA-Z]+$/', $str)) {
            'The {field} can have only A-Z, a-z. ';
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function validate_alphabatic_with_space(?string $str = NULL, ?string &$error = null): bool{

        if (!preg_match('/^[a-zA-Z ]+$/', $str)) {
            $error = 'The {field} can have only A-Z, a-z, space( ).';
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function validate_alphabatic_with_dot(?string $str = NULL, ?string &$error = null): bool{

        if (!preg_match('/^[a-zA-Z.]+$/', $str)) {
            $error = 'The {field} can have only A-Z, a-z, dot(.).';
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_date_dd_mm_yyyy(?string $str = NULL, ?string &$error = null): bool{

        if (!preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/", $str)) {
            $error = 'The {field} valid format dd-mm-yyyy.';
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_encrypted_value(?string $str = NULL, ?string &$error = null): bool
    {

        if (isset($str) && !empty($str)) {
            $str = url_decryption($str);

            if ($str == '') {
                $error = 'The {field} value is tempered.';
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
            $error = 'User ID could not be blank.';
            return FALSE;
        } elseif (preg_match('/[^A-Za-z0-9-]/i', $user_id)) {
            $error = 'User ID could not in correct format.';
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function password_pattern($password) {

        if ($password == '') {
            'Password could not be blank.';
            return FALSE;
        } elseif (preg_match('/[^A-Za-z0-9!@#$]/i', $password)) {
            'Password could not in correct format.';
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function captcha_pattern($captcha) {

        if ($captcha == '') {
            $error = 'Captcha could not be blank.';
            return FALSE;
        } elseif (preg_match('/[^0-9]/i', $captcha)) {
            $error = 'Incorrect captcha.';
            return FALSE;
        } elseif (getSessionData('captchaWord') != $captcha) {
            $error = 'Captcha could not match.';
            return FALSE;
        } else {
            return TRUE;
        }
    }
    function validate_alphacharacters(?string $str = NULL, ?string &$error = null): bool{
        if (!preg_match('/^[a-zA-Z` ]+$/', $str)) {
            $error = 'The {field} can have only A-Z a-z';
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function before_or_equal(string $value, string $field, array $data): bool
    {
        if (!isset($data[$field]) || empty($data[$field])) {
            return true; // If the comparison field is empty, skip validation or handle as needed
        }

        $date1 = strtotime($value);
        $date2 = strtotime($data[$field]);

        return $date1 <= $date2;
    }

    /**
     * Validates that the first date is after or equal to the second date.
     *
     * @param string $value The value of the field being validated
     * @param string $field The name of the field to compare against
     * @param array $data All input data
     * @return bool
     */
    public function after_or_equal(string $value, string $field, array $data): bool
    {
        if (!isset($data[$field]) || empty($data[$field])) {
            return true; // If the comparison field is empty, skip validation or handle as needed
        }

        $date1 = strtotime($value);
        $date2 = strtotime($data[$field]);

        return $date1 >= $date2;
    }

}
