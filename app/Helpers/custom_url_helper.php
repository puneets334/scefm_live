<?php

function custom_url_encryption($url_val)
{
    $str_len = strlen($url_val);
    $array_value = $str_len > 1 ? str_split($url_val) : [$url_val];

    $str_value = [
        '0' => '36', '1' => '92', '2' => '73', '3' => '32', '4' => '48', '5' => '43', '6' => '11', '7' => '84',
        '8' => '33', '9' => '17', 'a' => '89', 'b' => '98', 'c' => '97', 'd' => '86', 'e' => '55', 'f' => '49',
        'g' => '39', 'h' => '29', 'i' => '52', 'j' => '50', 'k' => '61', 'l' => '72', 'm' => '83', 'n' => '34',
        'o' => '15', 'p' => '60', 'q' => '70', 'r' => '78', 's' => '69', 't' => '25', 'u' => '41', 'v' => '62',
        'w' => '81', 'x' => '22', 'y' => '95', 'z' => '76', 'A' => '44', 'B' => '26', 'C' => '23', 'D' => '35',
        'E' => '42', 'F' => '12', 'G' => '16', 'H' => '93', 'I' => '87', 'J' => '91', 'K' => '31', 'L' => '47',
        'M' => '45', 'N' => '64', 'O' => '57', 'P' => '68', 'Q' => '51', 'R' => '20', 'S' => '59', 'T' => '56',
        'U' => '30', 'V' => '99', 'W' => '88', 'X' => '77', 'Y' => '66', 'Z' => '79', '#' => '82', '$' => '85',
        '_' => '90', ' ' => '71', '(' => '67', ')' => '10', ':' => '74', ',' => '75', '/' => '27', '.' => '28',
        '[' => '24', ']' => '21', '@' => '37', '&' => '54', '-' => '38'
    ];

    $return_value = '';
    foreach ($array_value as $char) {
        $return_value .= $str_value[$char];
    }

    $check_sum = getSessionData('login')['login_active_session'];
    $checksum = strtoupper(hash_hmac('sha256', $return_value, $check_sum, false));
    return $return_value . '.' . $checksum;
}

function custom_url_decryption($url_val)
{
    list($encoded, $checksum) = explode('.', $url_val, 2);

    if (empty($checksum)) {
        return false;
    }

    $login_id = getSessionData('login')['login_active_session'];
    $res_checksum = strtoupper(hash_hmac('sha256', $encoded, $login_id, false));

    if ($res_checksum !== $checksum) {
        return false;
    }

    $str_len = strlen($encoded);
    $array_string = str_split($encoded);

    $str_value = [
        '0' => '36', '1' => '92', '2' => '73', '3' => '32', '4' => '48', '5' => '43', '6' => '11', '7' => '84',
        '8' => '33', '9' => '17', 'a' => '89', 'b' => '98', 'c' => '97', 'd' => '86', 'e' => '55', 'f' => '49',
        'g' => '39', 'h' => '29', 'i' => '52', 'j' => '50', 'k' => '61', 'l' => '72', 'm' => '83', 'n' => '34',
        'o' => '15', 'p' => '60', 'q' => '70', 'r' => '78', 's' => '69', 't' => '25', 'u' => '41', 'v' => '62',
        'w' => '81', 'x' => '22', 'y' => '95', 'z' => '76', 'A' => '44', 'B' => '26', 'C' => '23', 'D' => '35',
        'E' => '42', 'F' => '12', 'G' => '16', 'H' => '93', 'I' => '87', 'J' => '91', 'K' => '31', 'L' => '47',
        'M' => '45', 'N' => '64', 'O' => '57', 'P' => '68', 'Q' => '51', 'R' => '20', 'S' => '59', 'T' => '56',
        'U' => '30', 'V' => '99', 'W' => '88', 'X' => '77', 'Y' => '66', 'Z' => '79', '#' => '82', '$' => '85',
        '_' => '90', ' ' => '71', '(' => '67', ')' => '10', ':' => '74', ',' => '75', '/' => '27', '.' => '28',
        '[' => '24', ']' => '21', '@' => '37', '&' => '54', '-' => '38'
    ];

    $string_flip = array_flip($str_value);

    if ($str_len > 2) {
        $num_pair = $str_len / 2;
        $l_num_pair = 0;
        $array_value = [];

        for ($j = 0; $j < $num_pair; $j++) {
            $up_num_pair = $j != 0 ? $l_num_pair + 2 : $j + 2;
            $val = '';

            for ($i = $l_num_pair; $i < $up_num_pair; $i++) {
                $val .= $array_string[$i];
            }

            $l_num_pair = $up_num_pair;
            $array_value[] = $val;
        }
    } else {
        $array_value = [$encoded];
    }

    $return_value = '';
    foreach ($array_value as $value) {
        $return_value .= $string_flip[$value];
    }

    return $return_value;
}
