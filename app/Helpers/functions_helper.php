<?php
//echo APPPATH;
//die;
//require_once '/opt/supremeCourtNew/app/ThirdParty/eSign/XMLSecurityDSig.php';
//use App\ThirdParty\eSign\XMLSecurityDSig;
use App\Models\Affirmation\AffirmationModel;
use App\Models\AppearingFor\AppearingForModel;
use \App\Models\Common\CommonModel;
use App\Models\NewCase\GetDetailsModel;
use App\Models\Caveat\ViewModel;
use App\Models\Login\LoginModel;
use App\Models\Supplements\SupplementModel;
use App\Models\Vacation\VacationAdvanceModel;
use App\Models\NewCase\DropdownListModel;
use GuzzleHttp\Client;
use Config\Database;
helper('view');

use eftec\bladeone\BladeOne;
use GuzzleHttp\Exception\GuzzleException;
use App\Libraries\webservices\Ecoping_webservices;
use App\Models\VacationSupp\VacationAdvanceSuppModel;
//use App\ThirdParty\eSign\XMLSecurityKey;
use CodeIgniter\View\RendererInterface;
//use RobRichards\XMLSecLibs\XMLSecurityDSig;
//use RobRichards\XMLSecLibs\XMLSecurityKey;
//use RobRichards\XMLSecLibs\XMLSecEnc;
use App\ThirdParty\src\XMLSecurityDSig;
use App\ThirdParty\src\XMLSecurityKey;
use App\ThirdParty\src\XMLSecEnc;
//use Mpdf;
if (!function_exists('pr')) {
    function pr($request)
    {
        echo '<pre>';
        print_r($request);
        echo "<br>";
        die;
    }
}

if (!function_exists('pra')) {
    function pra($request)
    {
        pr($request->toArray());
    }
}

function setSessionData($key, $value)
{
    $session = \Config\Services::session();
    $session->set($key, $value);
}

function getSessionData($key)
{
    $session = \Config\Services::session();
    return $session->get($key);
}

function captcha_key($key = null)
{
    //$captcha = rand(1000, 999999);
    //$captcha = generateRandomString(6);
    $length = 6;
    $captcha = substr(str_shuffle(str_repeat($x = '23456789abdefghmnpqrtzABDEFGHLMNPQRSTWXYZ', ceil($length / strlen($x)))), 1, $length);

    // The captcha will be stored
    // for the session
    // $_SESSION["captcha"] = $captcha;
    setSessionData('captcha', $captcha);


    // Generate a 70x24 standard captcha image
    $im = imagecreatetruecolor(70, 30);

    // Blue color
    $bg = imagecolorallocate($im, 22, 86, 165);

    // White color
    $fg = imagecolorallocate($im, 255, 255, 255);

    // Give the image a blue background
    imagefill($im, 0, 0, $bg);

    // Print the captcha text in the image
    // with random position & size
    imagestring($im, rand(10, 7), rand(10, 7), rand(10, 7), $captcha, $fg);

    // VERY IMPORTANT: Prevent any Browser Cache!!
    header("Cache-Control: no-store,no-cache, must-revalidate");

    // The PHP-file will be rendered as image
    header('Content-type: image/png');

    // Finally output the captcha as
    // PNG image the browser
    imagepng($im);

    // Free memory
    imagedestroy($im);

    // return imagepng($im);
}

function get_new_captcha()
{
    return $_SESSION["captcha"];
    /*  $length=6;
      //$random_number = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
      $random_number = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);

      $vals = array(
          'word' => $random_number,
          'img_path' => './captcha/',
          'img_url' => base_url() . 'captcha/',
          'font_path' => './path/to/fonts/texb.ttf',
          'img_width' => '120',
          'img_height' => 30,
          'expiration' => 7200,
          'word_length' => 10,
          'font_size' => 13,
          'img_id' => 'Imageid',
          'pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
          // White background and border, black text and red grid
          'colors' => array(
              'background' => array(255, 255, 255),
              'border' => array(255, 255, 255),
              'text' => array(0, 0, 0),
              //'grid' => array(255, 40, 40)
          )
      );

      //$data['captcha'] = create_captcha($vals);
      $data['captcha'] =$vals;

      return $data['captcha'];*/
}

if (!function_exists('isValidPDF')) {

    function isValidPDF($doc)
    {
        $msg = '';
        $doc_signed_used = '';
        if ($_FILES[$doc]['type'] != 'application/pdf') {
            $msg = 'only PDF are allowed in document upload!';
            return $msg;
        }
        if (mime_content_type($_FILES[$doc]['tmp_name']) != 'application/pdf') {
            $msg = 'only PDF are allowed in document upload!';
            return $msg;
        }
        if (substr_count($_FILES[$doc]['name'], '.') > 1) {
            $msg = 'No double extension allowed in pdf document!';
            return $msg;
        }
        if (preg_match("/[^0-9a-zA-Z\s.,-_ ]/i", $_FILES[$doc]['name'])) {

            $msg = 'PDF file name max. length can be 45 characters only. PDF file name may contain digits, characters, spaces, hyphens and underscores.';
            return $msg;
        }
        if (strlen($_FILES[$doc]['name']) > File_FIELD_LENGTH) {
            $msg = 'PDF file name max. length can be 45 characters only. PDF file name may contain digits, characters, spaces, hyphens and underscores.';
            return $msg;
        }
        if ($_FILES[$doc]['size'] > UPLOADED_FILE_SIZE) {
            $file_size = (UPLOADED_FILE_SIZE / 1024) / 1024;
            $msg = 'PDF uploaded should be less than ' . $file_size . ' MB !';
            return $msg;
        }
        if (isset(getSessionData('efiling_details')['doc_signed_used']) && !empty(getSessionData('efiling_details')['doc_signed_used'])) {
            $doc_signed_used = getSessionData('efiling_details')['doc_signed_used'];
        }
        if ($doc_signed_used == SIGNED_DIGITALLY_TOKEN) {
            $result = isDocumentSigned($_FILES[$doc]['tmp_name'], 'adbe.pkcs7.detached');
            if ($result != TRUE || $result != 1) {
                $msg = 'Please upload digitally signed document only!';
                return $msg;
            }
        }
    }
}

function isDocumentSigned($file, $string)
{

    $handle = fopen($file, 'r');
    $valid = false; // init as false
    while (($buffer = fgets($handle)) !== false) {
        if (strpos($buffer, $string) !== false) {
            $valid = TRUE;
            break; // Once you find the string, you should break out the loop.
        }
    }
    fclose($handle);
    return $valid;
}

if (!function_exists('isValidImage')) {

    function isValidImage($image)
    {

        if ($_FILES[$image]['type'] != 'image/jpeg') {
            $msg = '<div class="alert alert-danger text-center">only Jpeg are allowed in photo  upload!</div>';
            return $msg;
        }
        if (mime_content_type($_FILES[$image]['tmp_name']) != 'image/jpeg') {
            $msg = '<div class="alert alert-danger text-center">only Jpeg are allowed in photo  upload!</div>';
            return $msg;
        }
        if (substr_count($_FILES[$image]['name'], '.') > 1) {
            $msg = '<div class="alert alert-danger text-center">No double extension allowed in photo  upload!</div>';
            return $msg;
        }
    }
}

if (!function_exists('script_remove')) {

    function script_remove($term)
    {

        //return trim(pg_escape_string(strip_tags($term, '<table><thead><tr><td><tbody><tfoot><th><b><i><ol><li><br><br/><a>')));
        return trim(strip_tags($term ?? '', '<table><thead><tr><td><tbody><tfoot><th><b><i><ol><li><br><br/><a>'));
    }
}
if (!function_exists('escape_data')) {

    function escape_data($post)
    {
        return trim(strip_tags($post ?? ''));
    }
}

function echo_data($value)
{
    $data = htmlentities(script_remove($value ?? ''), ENT_COMPAT);
    echo $value = str_replace("''", "'", $data);
}

function check_and_get_utf8($string)
{
    if (preg_match('%^(?:
      [\x09\x0A\x0D\x20-\x7E]            # ASCII
    | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
    | \xE0[\xA0-\xBF][\x80-\xBF]         # excluding overlongs
    | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
    | \xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates
    | \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
    | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
    | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
)*$%xs', $string))
        return $string;
    else
        return iconv('CP1252', 'UTF-8', $string);
}

if (!function_exists('isDate')) {

    function isDate($date)
    {
        if (preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-(20([1-9][0-9]))$/", $date)) {
            return $date;
        } else {
            return false;
        }
    }
}
if (!function_exists('check_date')) {

    function check_date($date)
    {
        if (preg_match('/^((20([1-9][0-9]))-(0[1-9]|1[0-2])-0[1-9]|[1-2][0-9]|3[0-1]) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/', $date))
            return $date;
        else
            return false;
    }
}
if (!function_exists('getfiletype')) {

    function getfiletype($test)
    {
        if (testsig($test, "25 50 44 46")) {
            return "pdf";
        } else {
            return false;
        }
    }
}
if (!function_exists('count_page')) {

    function count_page($file)
    {
        $localPdfReader = new java("com.itextpdf.text.pdf.PdfReader", $file);
        $number_of_pages = $localPdfReader->getNumberOfPages();
        return $number_of_pages;
    }
}
function validate_date($posted_data, $field_profile)
{
    $err_msg = '';
    if ($field_profile['is_required'] == 't' && empty($posted_data)) {
        $err_msg = $field_profile['frm_field_label'] . " can not be blank";
        if ($field_profile['is_gen_err_msg'] == 't') {
            return $err_msg;
        } else {
            return $field_profile['err_msg'];
        }
    } elseif (!empty($posted_data)) {
        $pattern = $field_profile['preg_match'];
        if (!preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/", $posted_data)) {
            return $field_profile['err_msg'];
        }
    }
}

function validate_pancard($posted_data, $field_profile)
{
    $err_msg = '';
    if ($field_profile['is_required'] == 't' && empty($posted_data)) {
        $err_msg = $field_profile['frm_field_label'] . " can not be blank";
        if ($field_profile['is_gen_err_msg'] == 't') {
            return $err_msg;
        } else {
            return $field_profile['err_msg'];
        }
    } elseif (!empty($posted_data)) {
        $pattern = $field_profile['preg_match'];
        if (!preg_match("/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/", $posted_data)) {
            return $field_profile['err_msg'];
        }
    }
}

function validate_passport($posted_data, $field_profile)
{
    $err_msg = '';
    if ($field_profile['is_required'] == 't' && empty($posted_data)) {
        $err_msg = $field_profile['frm_field_label'] . " can not be blank";
        if ($field_profile['is_gen_err_msg'] == 't') {
            return $err_msg;
        } else {
            return $field_profile['err_msg'];
        }
    } elseif (!empty($posted_data)) {
        $pattern = $field_profile['preg_match'];
        if (!preg_match("/^([a-zA-Z]){1}([0-9]){7}?$/", $posted_data)) {
            return $field_profile['err_msg'];
        }
    }
}

function validate_fax_and_phone($posted_data, $field_profile)
{
    $err_msg = '';
    if ($field_profile['is_required'] == 't' && empty($posted_data)) {
        $err_msg = $field_profile['frm_field_label'] . " can not be blank";
        if ($field_profile['is_gen_err_msg'] == 't') {
            return $err_msg;
        } else {
            return $field_profile['err_msg'];
        }
    } elseif (!empty($posted_data)) {
        $pattern = $field_profile['preg_match'];
        if (!preg_match("/^[0-9]{3}-[0-9]{8}$/", $posted_data)) {
            return $field_profile['err_msg'];
        }
    }
}

function getStringOfAmount($number)
{
    $hyphen = '-';
    $conjunction = ' and ';
    $separator = ', ';
    $negative = 'negative ';
    $decimal = ' point ';
    $dictionary = array(
        0 => 'zero',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
        20 => 'twenty',
        30 => 'thirty',
        40 => 'fourty',
        50 => 'fifty',
        60 => 'sixty',
        70 => 'seventy',
        80 => 'eighty',
        90 => 'ninety',
        100 => 'hundred',
        1000 => 'thousand',
        1000000 => 'million',
        1000000000 => 'billion',
        1000000000000 => 'trillion',
        1000000000000000 => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );
    foreach ($dictionary as $key => $dict) {
        $newdictionary[$key] = ucfirst($dict);
    }
    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'getStringOfAmount only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . getStringOfAmount(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $newdictionary[$number];
            break;
        case $number < 100:
            $tens = ((int)($number / 10)) * 10;
            $units = $number % 10;
            $string = $newdictionary[$tens];
            if ($units) {
                $string .= $hyphen . $newdictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds = $number / 100;
            $remainder = $number % 100;
            $string = $newdictionary[$hundreds] . ' ' . $newdictionary[100];
            if ($remainder) {
                $string .= $conjunction . getStringOfAmount($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int)($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = getStringOfAmount($numBaseUnits) . ' ' . $newdictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= getStringOfAmount($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string)$fraction) as $number) {
            $words[] = $newdictionary[$number];
        }
        $string .= ucwords(implode(' ', $words));
    }

    return $string;
}

function send_mail_msg($to_email, $subject, $message, $to_user_name="") {
    return true;exit;
    if (!empty($to_user_name) && $to_user_name != null) {
       //return relay_mail_api_through_60_server(array($to_email), $subject, $message, $to_user_name);
       return relay_mail_api_through_jio_cloud_server(array($to_email), $subject, $message, $to_user_name);
    } else {
       //return relay_mail_api_through_60_server(array($to_email), $subject, $message);
       return relay_mail_api_through_jio_cloud_server(array($to_email), $subject, $message);
    }
}

function send_petitioner_mail_msg($to_email, $subject, $message, $to_user_name)
{
    if (empty($to_email)) {
        return true;
    }
    $url = EADMINSCI_URI . "/stealth-a-push-mail-gw?toIds=" . rawurlencode($to_email) . "&subject=" . rawurlencode($subject) . "&msg=" . rawurlencode($message) . "&typeId=" . SMS_EMAIL_API_USER;
    $result = (array)json_decode(file_get_contents($url));
}

function relay_mail_api_through_60_server($to_email, $subject, $message, $to_user_name = "")
{
    $result = '';
    foreach ($to_email as $val_email) {
        $to_email = $val_email;
        // $ci = &get_instance();
        $email_message = '';
        // pr(getSessionData('adv_details'));
        if (getSessionData('citation_mail') != '' && getSessionData('citation_mail') == 'Citation') {

            $search_by = getSessionData('citation_data')[1]['search_by'];
            if ($search_by == 'J') {
                $page_no = getSessionData('citation_data')[1]['page_no'];
                $volume = getSessionData('citation_data')[1]['volume'];
                $journal_year = getSessionData('citation_data')[1]['journal_year'];
                $journal = getSessionData('citation_data')[1]['journal'];
            } else {
                $page_no = getSessionData('citation_data')[0]['page_no'];
                $volume = getSessionData('citation_data')[0]['volume'];
                $journal_year = getSessionData('citation_data')[0]['journal_year'];
                $journal = getSessionData('citation_data')[0]['journal'];
            }
            $given_by = getSessionData('login')['first_name'];
            //$this->session->userdata['login']['first_name']

            $case_data_info = array(
                'diary_no' => getSessionData('case_data')[0]['diary_no'], 'registation_no' => getSessionData('case_data')[0]['reg_no_display'], 'cause_title' => getSessionData('case_data')[0]['cause_title'], 'dairy_yr' => getSessionData('case_data')[0]['diary_year'],
                'title' => getSessionData('citation_data')[0]['title'], 'pubname' =>
                getSessionData('citation_data')[0]['pubnm'], 'pubyear' => getSessionData('citation_data')[0]['pubyr'], 'subject' => getSessionData('citation_data')[0]['sub'],
                'listing_date' => getSessionData('citation_data')[0]['listing_date'], 'page_no' => $page_no, 'volume' => $volume, 'journal_year' => $journal_year, 'journal' => $journal, 'given_by' => $given_by
            );
            /*print_r($case_data_info);
            exit();*/

            $email_message = view('templates/email/citation_mail', $case_data_info);
        } elseif (!empty(getSessionData('adv_details')) && !empty(getSessionData('adv_details')['first_name']) && !empty(getSessionData('adv_details')['last_name'])) {
            $data = array(
                'first_name' => $_SESSION['adv_details']['first_name'],
                'last_name' => getSessionData('adv_details')['last_name'],
                'message' => $message
            );
            $email_message = view('templates/email/password_reset', $data);
        } elseif ($to_user_name == 'adjournment') {
            $email_message = ($message);
        } elseif ($to_user_name == 'arguing_counsel') {
            $email_message = ($message);
        } elseif (getSessionData('login') != '' && getSessionData('login')['first_name'] != '' && getSessionData('login')['last_name']) {
            $data = array(
                'first_name' => getSessionData('login')['first_name'],
                'last_name' => getSessionData('login')['last_name'],
                'message' => $message
            );
            $email_message = view('templates/email/html_mail', $data);
        } else {
            $email_message = ($message);
        }
        $files = array();
        //$string = base64_encode(json_encode(array("allowed_key" => "DZFYAb6p9j", "sender" => "eFiling-Supreme Court | SC-eFM<noreply-efiling@sci.gov.in>", "mailTo" => $to_email, "subject" => $subject, "message" => $email_message, "files" => $files)));
        //$string = base64_encode(json_encode(array("allowed_key" => "xFgKPb9a8z", "sender" => "eService", "mailTo" => $to_email, "subject" => $subject, "message" => $email_message, "files" => $files)));
        $string = base64_encode(json_encode(array("allowed_key" => "cKLKqvPlW8", "sender" => "eService", "mailTo" => $to_email, "subject" => $subject, "message" => $email_message, "files" => $files)));
        $content = http_build_query(array('a' => $string));
        $context = stream_context_create(array('http' => array('method' => 'POST', 'content' => $content,)));
        $json_return = @file_get_contents(NEW_MAIL_SERVER_HOST, false, $context);
        $json2 = json_decode($json_return);
        //echo $json_return;
        // pr($json2);
        //$response = json2["status"];
        if (!empty($json2)) {
            $response = $json2->status;
        }
        if ($json2) {
            if ($response == 'success') {
                $result = 'success';
            } else {
                $result = 'error';
            }
        }
        return $result;
    }
}

function relay_mail_api($to_email, $subject, $message, $to_user_name = "")
{
    foreach ($to_email as $val_email) {
        $to_email = $val_email;

        // $ci = &get_instance();
        // $ci->load->library('email');
        $email = \Config\Services::email();
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.mail.gov.in',
            'smtp_port' => 465,
            'smtp_user' => 'causelists@nic.in',
            'smtp_pass' => 'eCourts*1234',
            'mailtype' => 'html',
            'charset' => 'utf-8'
        );

        /*$config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com',
            'smtp_port' => 465,
            'smtp_user' => 'scieservice@gmail.com',
            'smtp_pass' => 'eService@29031990',
            'mailtype' => 'html',
            'charset' => 'utf-8'
        );*/

        $email->initialize($config);
        // $email->mailType("html");
        $email->setNewline("\r\n");
        $email->setTo($to_email);
        $email->setFrom('causelists@nic.in', "Supreme Court of India");
        //$ci->email->from('scieservice@gmail.com', "Supreme Court of India");
        //$ci->email->bcc('info-ecommittee@aij.gov.in', "Info eCommittee");
        $email->setSubject($subject);

        if (getSessionData('citation_mail') == 'Citation') {

            $search_by = getSessionData('citation_data')[1]['search_by'];
            if ($search_by == 'J') {
                $page_no = getSessionData('citation_data')[1]['page_no'];
                $volume = getSessionData('citation_data')[1]['volume'];
                $journal_year = getSessionData('citation_data')[1]['journal_year'];
                $journal = getSessionData('citation_data')[1]['journal'];
            } else {
                $page_no = getSessionData('citation_data')[0]['page_no'];
                $volume = getSessionData('citation_data')[0]['volume'];
                $journal_year = getSessionData('citation_data')[0]['journal_year'];
                $journal = getSessionData('citation_data')[0]['journal'];
            }
            $given_by = getSessionData('login')['first_name'];
            //$this->session->userdata['login']['first_name']

            $case_data_info = array(
                'diary_no' => getSessionData('case_data')[0]['diary_no'], 'registation_no' => getSessionData('case_data')[0]['reg_no_display'], 'cause_title' => getSessionData('case_data')[0]['cause_title'], 'dairy_yr' => getSessionData('case_data')[0]['diary_year'],
                'title' => getSessionData('citation_data')[0]['title'], 'pubname' =>
                getSessionData('citation_data')[0]['pubnm'], 'pubyear' => getSessionData('citation_data')[0]['pubyr'], 'subject' => getSessionData('citation_data')[0]['sub'],
                'listing_date' => getSessionData('citation_data')[0]['listing_date'], 'page_no' => $page_no, 'volume' => $volume, 'journal_year' => $journal_year, 'journal' => $journal, 'given_by' => $given_by
            );
            /*print_r($case_data_info);
            exit();*/

            $email->setMessage(render('templates/email/citation_mail', $case_data_info, true));
        } elseif (getSessionData('adv_details') != '' && getSessionData('adv_details')['first_name'] != '' && getSessionData('adv_details')['last_name'] != '') {
            $data = array(
                'first_name' => getSessionData('adv_details')['first_name'],
                'last_name' => getSessionData('adv_details')['last_name'],
                'message' => $message
            );
            $email->setMessage(render('templates/email/password_reset', $data, true));
        } elseif ($to_user_name == 'adjournment') {
            $email->setMessage($message);
        } elseif ($to_user_name == 'arguing_counsel') {
            $email->setMessage($message);
        } elseif (getSessionData('login')['first_name'] != '' && getSessionData('login')['last_name'] != '') {
            $data = array(
                'first_name' => getSessionData('login')['first_name'],
                'last_name' => getSessionData('login')['last_name'],
                'message' => $message
            );
            $email->setMessage(render('templates/email/html_mail', $data, true));
        } else {
            $email->setMessage($message);
        }

        $response = $email->send();
        if ($response) {
            $result = 'success';
        } else {
            $result = 'error';
        }
    } //End of foreach loop..
    return $result;
}

function relay_doc_mail_api($to_email, $subject, $message)
{
    // $ci = &get_instance();
    // $ci->load->library('email');
    $email = \Config\Services::email();
    $config = array(
        'protocol' => 'smtp',
        'smtp_host' => 'ssl://smtp.mail.gov.in',
        'smtp_port' => 465,
        'smtp_user' => 'causelists@nic.in',
        'smtp_pass' => 'eCourts*1234',
        'mailtype' => 'html',
        'charset' => 'utf-8'
    );
    $email->initialize($config);
    $email->setMailType("text");
    $email->setNewline("\r\n");
    $email->setTo($to_email);
    $email->setFrom('causelists@nic.in', "Supreme Court of India");
    $email->setSubject($subject);
    $email->setMessage($message);
    $response = $email->send();
    if ($response) {
        $result = 'success';
    } else {
        $result = 'error';
    }
    return $result;
}

function send_mobile_sms($mobile_no, $sentSMS, $templateId = SCISMS_GENERIC_TEMPLATE)
{
    return true;exit;
    $result = sendSMS(SMS_EMAIL_API_USER, $mobile_no, $sentSMS, $templateId);
    // return json_decode($result);
    return $result;
}

function send_petitioner_mobile_sms($mobile_no, $sentSMS)
{
    if (empty($mobile_no)) {
        return true;
    }
    $result = sendSMS(SMS_EMAIL_API_USER, $mobile_no, $sentSMS);
    return json_decode($result);
}

function remove_selected_tags($data, $tags_array)
{
    $tags_to_strip = $tags_array;
    foreach ($tags_to_strip as $tag) {
        $data = preg_replace("/<\\/?" . $tag . "(.|\\s)*?>/", ' ', $data);
    }
    return $data;
}

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function validatePostedDate($posted_data)
{
    if (!preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/", $posted_data)) {
        return FALSE;
    }
}

function url_encryption($url_val)
{
    $encrypt = $url_val;
    $str_len = strlen($url_val);
    if ($str_len > 1) {
        $array_value = str_split($url_val);
    } else {
        $array_value = array($url_val);
    }


    $str_value = array(
        '0' => '36', '1' => '92', '2' => '73', '3' => '32',
        '4' => '48', '5' => '43', '6' => '11', '7' => '84', '8' => '33', '9' => '17',
        'a' => '89', 'b' => '98', 'c' => '97', 'd' => '86', 'e' => '55',
        'f' => '49', 'g' => '39', 'h' => '29', 'i' => '52', 'j' => '50',
        'k' => '61', 'l' => '72', 'm' => '83', 'n' => '34', 'o' => '15',
        'p' => '60', 'q' => '70', 'r' => '78', 's' => '69', 't' => '25',
        'u' => '41', 'v' => '62', 'w' => '81', 'x' => '22', 'y' => '95',
        'z' => '76', 'A' => '44', 'B' => '26', 'C' => '23', 'D' => '35',
        'E' => '42', 'F' => '12', 'G' => '16', 'H' => '93', 'I' => '87',
        'J' => '91', 'K' => '31', 'L' => '47', 'M' => '45', 'N' => '64',
        'O' => '57', 'P' => '68', 'Q' => '51', 'R' => '20', 'S' => '59',
        'T' => '56', 'U' => '30', 'V' => '99', 'W' => '88', 'X' => '77',
        'Y' => '66', 'Z' => '79', '#' => '82', '$' => '85', '_' => '90',
        ' ' => '71', '(' => '67', ')' => '10', ':' => '74', ',' => '75',
        '/' => '27', '.' => '28', '[' => '24', ']' => '21', '@' => '37',
        '&' => '54', '-' => '38', '~' => '58'
    );
    //''=> 58 ,'63' ,65 , 13 ,14 ,18 ,19 Use for another
    $return_value = '';

    for ($i = 0; $i < count($array_value); $i++) {
        // $return_value .= $str_value[$array_value[$i]];
        $return_value .= $str_value[$array_value[$i]] ?? '';
    }
    $check_sum = !empty(getSessionData('login')) ? getSessionData('login')['login_active_session'] : '';

    $string = $return_value;
    //$checksum = hash_hmac('crc32b', $string, $check_sum, false); // working php7.1
    $checksum = hash_hmac('sha256', $string, $check_sum, false);
    $checksum = strtoupper($checksum);
    $result = $return_value . '.' . $checksum;

    //-----------END :ENCRYPT CHECKSUM IN URL----------------------//

    return $result;
}

function url_decryption($url_val)
{
    $check_sum_str = explode('.', $url_val);
    if (!empty($check_sum_str[1])) {
        $str_len = strlen($check_sum_str[0]);
        $array_string = str_split($check_sum_str[0]);

        // Load session library
        $session = \Config\Services::session();
        $login_id = !empty($session->get('login')) ? $session->get('login')['login_active_session'] : '';
        $up_num_pair = 0;
        $res_checksum = strtoupper(hash_hmac('sha256', $check_sum_str[0], $login_id, false));
        // if ($res_checksum == $check_sum_str[1]) {
            if ($str_len > 2) {
                $num_pair = $str_len / 2;
                $l_num_pair = 0;
                for ($j = 0; $j < $num_pair; $j++) {
                    if ($j != 0) {
                        $l_num_pair = $up_num_pair;
                        $up_num_pair = $l_num_pair + 2;
                    } else {
                        $up_num_pair = $j + 2;
                    }
                    $val = '';
                    for ($i = $l_num_pair; $i < $up_num_pair; $i++) {
                        $val .= $array_string[$i];
                    }
                    $arr_val[$j] = $val;
                }
                $array_value = $arr_val;
            } else {
                $array_value = array($check_sum_str[0]);
            }
            $str_value = [
                '0' =>
                '36', '1' => '92', '2' => '73', '3' => '32',
                '4' => '48', '5' => '43', '6' => '11', '7' => '84', '8' => '33', '9' => '17',
                'a' => '89', 'b' => '98', 'c' => '97', 'd' => '86', 'e' => '55',
                'f' => '49', 'g' => '39', 'h' => '29', 'i' => '52', 'j' => '50',
                'k' => '61', 'l' => '72', 'm' => '83', 'n' => '34', 'o' => '15',
                'p' => '60', 'q' => '70', 'r' => '78', 's' => '69', 't' => '25',
                'u' => '41', 'v' => '62', 'w' => '81', 'x' => '22', 'y' => '95',
                'z' => '76', 'A' => '44', 'B' => '26', 'C' => '23', 'D' => '35',
                'E' => '42', 'F' => '12', 'G' => '16', 'H' => '93', 'I' => '87',
                'J' => '91', 'K' => '31', 'L' => '47', 'M' => '45', 'N' => '64',
                'O' => '57', 'P' => '68', 'Q' => '51', 'R' => '20', 'S' => '59',
                'T' => '56', 'U' => '30', 'V' => '99', 'W' => '88', 'X' => '77',
                'Y' => '66', 'Z' => '79', '#' => '82', '$' => '85', '_' => '90',
                ' ' => '71', '(' => '67', ')' => '10', ':' => '74', ',' => '75',
                '/' => '27', '.' => '28', '[' => '24', ']' => '21', '@' => '37',
                '&' => '54', '-' => '38'
            ];

            $string_flip = array_flip($str_value);

            $return_value = '';
            foreach ($array_value as $value) {
                $return_value .= $string_flip[$value];
            }
            return $return_value;
        // }
    }
    return null; // Return null if decryption fails
}

// vinit code

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
} // vinit code end function
function cino_decrypt($string, $secret_key)
{
    $secret_iv = 'nic@punemaharashtraindia';
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = substr(hash('sha1', $secret_key), 0, 32);
    $iv = substr(hash('sha1', $secret_iv), 0, 16);
    $output = openssl_decrypt($string, $encrypt_method, $key, 0, $iv);
    return $output;
}
// // Encrypt data using the public key 
// function encrypt($data, $publicKey)
// { // Encrypt the data using the public key 
//     openssl_public_encrypt($data, $encryptedData, $publicKey);
//     // Return encrypted data return
//     $encryptedData;
// } // Decrypt data using the private key 
// function decrypt($data, $privateKey)
// { // Decrypt the data using the private key 
//     openssl_private_decrypt($data, $decryptedData, $privateKey); // Return decrypted data 
//     return $decryptedData;
// }
function generate_public_private_key()
{ // Set the key parameters
    $config = array(
        "digest_alg" => "sha512",
        "private_key_bits" => 512,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    );

    // Create the private and public key
    $res = openssl_pkey_new($config);
    // Extract the private key from $res to $privKey
    openssl_pkey_export($res, $privKey);

    // Extract the public key from $res to $pubKey
    $pubKey = openssl_pkey_get_details($res);

    return array(
        'private' => $privKey,
        'public' => $pubKey["key"],
        'type' => $config,
    );
}

function get_court_type($efiling_for_type_id)
{
    if ($efiling_for_type_id == E_FILING_FOR_HIGHCOURT) {
        return ENABLE_WEBSERVICE_HC;
    } elseif ($efiling_for_type_id == E_FILING_FOR_ESTABLISHMENT) {
        return ENABLE_WEBSERVICE_ESTAB;
    }
}

function pingDomain($domain)
{
    $starttime = microtime(true);
    $file = fsockopen($domain, 80, $errno, $errstr, 10);
    $stoptime = microtime(true);
    $status = 0;

    if (!$file)
        $status = -1; // Site is down
    else {
        fclose($file);
        $status = ($stoptime - $starttime) * 1000;
        $status = floor($status);
    }
    return $status;
}

function cin_preview($cin)
{

    if (strlen($cin) == 16) {
        return substr($cin, 0, 6) . '-' . substr($cin, 6, 6) . '-' . substr($cin, 12, 4);
    } else {
        return efile_preview($cin);
    }
}

function efile_preview($efile_no)
{
    return substr($efile_no, 0, 2) . '-' . substr($efile_no, 2, 6) . '-' . substr($efile_no, 8, -4) . '-' . substr($efile_no, -4);
}

function validate_number($field_value, $is_required, $field_min_length, $field_max_length, $field_label)
{

    $ci = &get_instance();
    $ci->load->library('form_validation');

    $data = array('field_name' => $field_value);

    $ci->form_validation->set_data($data);
    if ($is_required == 't') {
        $ci->form_validation->set_rules('field_name', $field_label, 'required|min_length[' . $field_min_length .
            ']|max_length[' . $field_max_length . ']|is_natural_no_zero');
    } else {
        $ci->form_validation->set_rules('field_name', $field_label, 'min_length[' . $field_min_length .
            ']|max_length[' . $field_max_length . ']|is_natural_no_zero');
    }

    if ($ci->form_validation->run() == FALSE) {
        return array('response' => false, 'msg' => $ci->form_validation->error_array('field_name'));
    } else {
        return array('response' => true);
    }
}

// function validate_names($field_value, $is_required, $field_min_length, $field_max_length, $field_label)
// {

//     $ci = &get_instance();
//     $ci->load->library('form_validation');


//     $data = array('field_name' => $field_value);
//     $ci->form_validation->set_data($data);
//     if ($is_required == 't') {
//         $ci->form_validation->set_rules('field_name', $field_label, 'required|min_length[' . $field_min_length .
//             ']|max_length[' . $field_max_length . ']|regex_match[' . VALIDATION_PREG_MATCH . ']');
//     } else {
//         $ci->form_validation->set_rules('field_name', $field_label, 'min_length[' . $field_min_length .
//             ']|max_length[' . $field_max_length . ']|regex_match[' . VALIDATION_PREG_MATCH . ']');
//     }

//     if ($ci->form_validation->run() == FALSE) {
//         return array('response' => false, 'msg' => $ci->form_validation->error_array('field_name'));
//     } else {
//         return array('response' => true);
//     }
// }

function validate_names($field_value, $is_required, $field_min_length, $field_max_length, $field_label)
{
    $validation = \Config\Services::validation();

    $rules = 'min_length[' . $field_min_length . ']|max_length[' . $field_max_length . ']|regex_match[' . VALIDATION_PREG_MATCH . ']';
    if ($is_required == 't') {
        $rules = 'required|' . $rules;
    }

    $data = ['field_name' => $field_value];
    $validation->setRules(['field_name' => [
        'label' => $field_label,
        'rules' => $rules
    ]]);

    if (!$validation->run($data)) {
        return ['response' => false, 'msg' => $validation->getErrors()];
    } else {
        return ['response' => true];
    }
}

function validate_names_for_cis_master(
    $field_value,
    $is_required,
    $field_min_length,
    $field_max_length,
    $field_label
) {

    $ci = &get_instance();
    $ci->load->library('form_validation');


    $data = array('field_name' => $field_value);
    $ci->form_validation->set_data($data);
    if ($is_required == 't') {
        $ci->form_validation->set_rules('field_name', $field_label, 'required|min_length[' . $field_min_length .
            ']|max_length[' . $field_max_length . ']|regex_match[/^[0-9a-zA-Z:,\/._\[\])@#&(; -]+$/]');
    } else {
        $ci->form_validation->set_rules('field_name', $field_label, 'min_length[' . $field_min_length .
            ']|max_length[' . $field_max_length . ']|regex_match[/^[0-9a-zA-Z:,\/._\[\])@#&(; -]+$/]');
    }

    if ($ci->form_validation->run() == FALSE) {
        return array('response' => false, 'msg' => $ci->form_validation->error_array('field_name'));
    } else {
        return array('response' => true);
    }
}

function validate_alphanumeric_with_space_n_spl_chars(
    $field_value,
    $is_required,
    $field_min_length,
    $field_max_length,
    $field_label
) {

    $ci = &get_instance();
    $ci->load->library('form_validation');

    $data = array('field_name' => $field_value);
    $ci->form_validation->set_data($data);
    if ($is_required == 't') {
        $ci->form_validation->set_rules('field_name', $field_label, 'required|min_length[' . $field_min_length .
            ']|max_length[' . $field_max_length . ']|regex_match[/^[A-z ]+$/]');
    } else {
        $ci->form_validation->set_rules('field_name', $field_label, 'min_length[' . $field_min_length .
            ']|max_length[' . $field_max_length . ']|regex_match[/^[A-z ]+$/]');
    }

    if ($ci->form_validation->run() == FALSE) {
        return array('response' => false, 'msg' => $ci->form_validation->error_array('field_name'));
    } else {
        return array('response' => true);
    }
}

function remark_preview($reg_id, $current_stage_id)
{
    // echo "Registration: ".$reg_id. 'and current stage:'. $current_stage_id;
    $Common_model = new CommonModel();
    // $result_initial = $Common_model->get_intials_defects_remarks($reg_id, $current_stage_id);

    $result_initial = $Common_model->get_intials_defects_remarks($reg_id, $current_stage_id);

    $result_icmis = $Common_model->get_cis_defects_remarks($reg_id, FALSE);

    $defects['pdfdefects'] = $Common_model->get_pdf_defects_remarks($reg_id);
    if (isset($result_icmis) && !empty($result_icmis)) {

        $total_aor_cured=sizeof($result_icmis); $acr=0;$checkedAll='';
        foreach ($result_icmis as $row) { $is_aor_cured = (isset($row['aor_cured']) && !empty($row['aor_cured'])) ? $row['aor_cured'] : "f";
            if ($is_aor_cured == "t") {  $acr++;  }
        }
        if ($total_aor_cured == $acr) { $checkedAll = "checked"; }

        $msg = '<div class="alert table-responsive-sm">';
        $msg .= '<table id="datatable-defects" class="table table-striped custom-table"
//                     cellspacing="0" width="100%">'
            . '<thead class="success"><th>Mark Cured<br/><input type="checkbox" id="checkAll" '.$checkedAll.'></th><th>#</th><th>Defect Description</th><th>Defect Remark</th><th>Prepare Dt.</th><th>Remove Dt.</th><th>Index Title</th><th>Defective Page No.</th></thead>'
            . '<tbody style="border-color: #ebccd1;background-color: #f2dede;color: #a94442;">';
        $i = 1;
        foreach ($result_icmis as $re) {
            $prep_dt = (isset($re['obj_prepare_date']) && !empty($re['obj_prepare_date'])) ? date('d-M-Y H:i:s', strtotime($re['obj_prepare_date'])) : null;
            $remove_dt = (isset($re['obj_removed_date']) && !empty($re['obj_removed_date'])) ? date('d-M-Y H:i:s', strtotime($re['obj_removed_date'])) : null;
            $pspdfdocumentid = (isset($re['pspdfkit_document_id']) && !empty($re['pspdfkit_document_id'])) ? $re['pspdfkit_document_id'] : null;
            $aor_cured = (isset($re['aor_cured']) && !empty($re['aor_cured'])) ? $re['aor_cured'] : "f";
            $checked = "";
            $markdefectclass = "";
            if ($aor_cured == "t") {
                $checked = "checked";
                $markdefectclass = "curemarked";
            }

            $link_url = base_url('documentIndex?pspdfkitdocumentid=' . $pspdfdocumentid . '&tobemodifiedpagesraw=');
            $tobemodifiedpagesdisplay = (isset($re['to_be_modified_pspdfkit_document_pages_raw']) && !empty($re['to_be_modified_pspdfkit_document_pages_raw'])) ? $re['to_be_modified_pspdfkit_document_pages_raw'] : null;
            $pdf_title = '';
            $pdf_pages_total = '';

            if ($pspdfdocumentid != null) {
                $pdf_info = $Common_model->getPdfInfo($pspdfdocumentid);
                if (!empty($pdf_info)) {
                    $pdf_title = $pdf_info->doc_title;
                    $pdf_pages_total = $pdf_info->page_no;
                }
            }
            $tobemodifiedpagesdisplaytext = '<span title="sequence no.s of (Total Pages)">' . $tobemodifiedpagesdisplay . ' of (' . $pdf_pages_total . '-pages)' . '</span>';
            $tobemodifiedpagesdisplaytextdisplay = (!empty($tobemodifiedpagesdisplay)) ? $tobemodifiedpagesdisplaytext : "";

            $msg .= '<tr id="row' . $re['id'] . '" class="' . $markdefectclass . '  setCuredDefectAllToggle">';
            $msg .= '<td>' . '<input type="checkbox" ' . $checked . ' id="' . $re['id'] . '" onchange="setCuredDefect(this.id)"  name="setCuredDefectAll[]" class="setCuredDefectAll" value="'.$re['id'].'">' . '</td>';
            $msg .= '<td>' . $i++ . '</td>';
            $msg .= '<td>' . escape_data($re['objdesc']) . '</td>';
            $msg .= '<td>' . escape_data($re['remarks']) . '</td>';
            $msg .= '<td>' . escape_data($prep_dt) . '</td>';
            $msg .= '<td>' . escape_data($remove_dt) . '</td>';
            $msg .= '<td>' . '<a href="' . $link_url . '">' . $pdf_title . '</span></a>' . '</td>';
            $msg .= '<td>' . $tobemodifiedpagesdisplaytextdisplay . '</td>';
            $msg .= '</tr>';
        }
        $msg .= '</tbody></table>';
        $msg .= '</div>';

        if (!empty($defects['pdfdefects'])) {
            $connecter = '';
            $msg .= '<div>';
            $msg .= "<b>NOTE- You also have pdf defects mentioned in ";
            foreach ($defects['pdfdefects'] as $key => $val) {
                $msg .= $connecter . "1 file on pageno- " . implode(",", $val);
                $connecter = " AND ";
            }
            $msg .= '</b>';
            $msg .= '</div>';
        }
        
        echo $msg;
    } elseif (isset($result_initial) && !empty($result_initial)) {
        $msg = '<div class="alert" style="border-color: #ebccd1;background-color: #f2dede;color: #a94442;">';
        $msg .= '<p><strong>Defect Raised On : </strong>' . date('d-m-Y H:i:s', strtotime($result_initial['defect_date'])) . '</p>';
        $msg .= '<p><strong>Defects : </strong>' . script_remove($result_initial['defect_remark']) . '</p>';
        if ($result_initial['defect_cured_date'] ?? $result_initial['defect_cured_date'] != NULL) {
            $msg .= '<p><strong>Defect Cured On : </strong>' . htmlentities(date('d-m-Y H:i:s', strtotime($result_initial['defect_cured_date'])), ENT_QUOTES) . '</p>';
        }
        $msg .= '</div>';
        echo $msg;
    } else {
        return false;
    }
}

// function remark_preview($reg_id, $current_stage_id)
// {
//     // echo "Registration: ".$reg_id. 'and current stage:'. $current_stage_id;
//     $Common_model = new CommonModel();
//     $result_initial = $Common_model->get_intials_defects_remarks($reg_id, $current_stage_id);

//     $result_icmis = $Common_model->get_cis_defects_remarks($reg_id, FALSE);

//     $defects['pdfdefects'] = $Common_model->get_pdf_defects_remarks($reg_id);
//     // echo 'saurabh'; pr($defects);
//     //echo "<pre>";    print_r($result_icmis); echo "</pre>";
//     if (isset($result_icmis) && !empty($result_icmis)) {
//         $msg = '<div class="alert table-responsive-sm"><div class="table-sec">
//                     <div class="table-responsive">';
//         $msg .= '<table id="datatable-defects" class="table table-striped custom-table"
//                     cellspacing="0" width="100%">'
//             . '<thead class="success">
//                         <th>Mark Cured<br /><input type="checkbox" id="checkAll" onclick="checkAll()"/></th>
//                         <th>#</th>
//                         <th>Defect Description</th>
//                         <th>Defect Remark</th>
//                         <th>Prepare Dt.</th>
//                         <th>Remove Dt.</th>
//                         <th>Index Title</th>
//                         <th>Defective Page No.</th>
//                     </thead>'
//             . '<tbody style="border-color: #ebccd1;background-color: #f2dede;color: #a94442;">';
//         $i = 1;
       
//         foreach ($result_icmis as $re) {
//             // pr($re);
//             $prep_dt = (isset($re['obj_prepare_date']) && !empty($re['obj_prepare_date'])) ? date(
//                 'd-M-Y H:i:s',
//                 strtotime($re['obj_prepare_date'])
//             ) : null;
//             $remove_dt = (isset($re['obj_removed_date']) && !empty($re['obj_removed_date'])) ? date('d-M-Y
//                         H:i:s', strtotime($re['obj_removed_date'])) : null;
//             $pspdfdocumentid = (isset($re['pspdfkit_document_id']) && !empty($re['pspdfkit_document_id'])) ?
//                 $re['pspdfkit_document_id'] : null;
//             $aor_cured = (isset($re['aor_cured']) && !empty($re['aor_cured'])) ? $re['aor_cured'] : "f";
//             $checked = "";
//             $markdefectclass = "";
//             if ($aor_cured == "t") {
//                 $checked = "checked";
//                 $markdefectclass = "curemarked";
//             }

//             $link_url = base_url('documentIndex?pspdfkitdocumentid=' . $pspdfdocumentid .
//                 '&tobemodifiedpagesraw=');
//             $tobemodifiedpagesdisplay = (isset($re['to_be_modified_pspdfkit_document_pages_raw']) &&
//                 !empty($re['to_be_modified_pspdfkit_document_pages_raw'])) ?
//                 $re['to_be_modified_pspdfkit_document_pages_raw'] : null;
//             $pdf_title = '';
//             $pdf_pages_total = '';

//             if ($pspdfdocumentid != null) {
//                 $pdf_info = $Common_model->getPdfInfo($pspdfdocumentid);
//                 if (!empty($pdf_info)) {
//                     $pdf_title = $pdf_info->doc_title;
//                     $pdf_pages_total = $pdf_info->page_no;
//                 }
//             }
//             $tobemodifiedpagesdisplaytext = '<span title="sequence no.s of (Total Pages)">' .
//                 $tobemodifiedpagesdisplay . ' of (' . $pdf_pages_total . '-pages)' . '</span>';
//             $tobemodifiedpagesdisplaytextdisplay = (!empty($tobemodifiedpagesdisplay)) ?
//                 $tobemodifiedpagesdisplaytext : "";

//             $msg .= '<tr id="row' . $re['id'] . '" class="' . $markdefectclass . '">';
//             $msg .= '<td>' . '<input type="checkbox" ' . $checked . ' id="' . $re['id'] . '"
//                                     onchange="setCuredDefect(this.id)" class="checkOneByOne">' . '</td>';
//             $msg .= '<td>' . $i++ . '</td>';
//             $msg .= '<td>' . escape_data($re['objdesc']) . '</td>';
//             $msg .= '<td>' . escape_data($re['remarks']) . '</td>';
//             $msg .= '<td>' . escape_data($prep_dt) . '</td>';
//             $msg .= '<td>' . escape_data($remove_dt) . '</td>';
//             $msg .= '<td>' . '<a href="' . $link_url . '">' . $pdf_title . '</span></a>' . '</td>';
//             $msg .= '<td>' . $tobemodifiedpagesdisplaytextdisplay . '</td>';
//             $msg .= '</tr>';
//         }
//         $msg .= '</tbody>
//                 </table>';
//         $msg .= '</div>';
//         if (!empty($defects['pdfdefects'])) {
//             $connecter = '';
//             $msg .= '<div>';
//             $msg .= "<b>NOTE- You also have pdf defects mentioned in ";
//             foreach ($defects['pdfdefects'] as $key => $val) {
//                 $msg .= $connecter . "1 file on pageno- " . implode(",", $val);
//                 $connecter = " AND ";
//             }
//             $msg .= '</b>';
//             $msg .= '</div>';
//         }
//         $msg .= '</div>';
//         $msg .= '</div>';


//         echo $msg;
        
//     } elseif (isset($result_initial) && !empty($result_initial)) { 
        
//         $msg = '<div class="alert" style="border-color: #ebccd1;background-color: #f2dede;color: #a94442;">';
//         $msg .= '<p><strong>Defect Raised On : </strong>' . date(
//             'd-m-Y H:i:s', strtotime($result_initial['defect_date'] ?? $result_initial['defect_date'] )
//         ) . '
//                 <p>';
//         $msg .= '
//                 <p><strong>Defects :</strong>
//                 <p>';
//         $msg .= '
//                 <p>' . script_remove($result_initial['defect_remark'] ?? $result_initial['defect_remark']) . '
//                 <p>';
//         if ($result_initial['defect_cured_date'] ?? $result_initial['defect_cured_date'] != NULL) {
//             $msg .= '[
//                 <p] align="right"><strong>Defect Cured On : </strong>' . htmlentities(date(
//                 'd-m-Y H:i:s', strtotime($result_initial['defect_cured_date'])
//             ), ENT_QUOTES) . '
//                 <p>';
//         }

//         $msg .= '
//             </div>';
//         echo $msg;
//     } else {
//         return false;
//     }

// }

function encryptData($data)
{

    $iv = base64_decode('q83vmHZUMhCrze+YdlQyEA==');
    $key = base64_decode('VniUMhD97LpWeJQyEP3sug==');
    $plaintext = mcrypt_encrypt(
        MCRYPT_RIJNDAEL_128,
        $key,
        pkcs5_pad(json_encode($data), 16),
        MCRYPT_MODE_CBC,
        $iv
    );
    return base64_encode($plaintext);
}

function pkcs5_pad($text, $blocksize)
{

    $pad = $blocksize - (strlen($text) % $blocksize);
    return $text . str_repeat(chr($pad), $pad);
}

function decryptData($data)
{

    $key = pack('H*', "4133b1119985d9c3add0884d8cdd944c");
    $iv_dec = pack('H*', "3cf3cf3cf3cf3cf3cf3cf3cf3cf3cf3c");

    $ciphertext_dec = base64_decode($data);
    $plain_text = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
    $plain_text = trim($plain_text, "\0..\32");
    return $plain_text;
}

function curl_get_contents($url, $headers = [], $if_return_metadata = false)
{
    // Initiate the curl session
    $ch = curl_init();
    // Set the URL
    curl_setopt($ch, CURLOPT_URL, $url);
    // Removes the headers from the output
    curl_setopt($ch, CURLOPT_HEADER, 0);
    // Return the output instead of displaying it directly
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // Execute the curl session
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ($headers ?: array('User-Agent: AutoBot')));

    $output = curl_exec($ch);

    if (!curl_errno($ch)) {
        $info = curl_getinfo($ch);
        $response_code = $info['http_code'];
    }
    // Close the curl session
    curl_close($ch);
    // Return the output as a variable
    if ($if_return_metadata) {
        return [$output, $response_code];
    }
    return $output;
}

function captcha_generate()
{
    // $ci = &get_instance();
    // $ci->load->library('session');
    $session = \Config\Services::session();
    $random_number = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
    $vals = array(
        'word' => $random_number,
        'img_path' => './captcha/',
        'img_url' => base_url('captcha/'),
        'font_path' => './path/to/fonts/texb.ttf',
        'img_width' => '120',
        'img_height' => 30,
        'expiration' => 7200,
        'word_length' => 20,
        'font_size' => 16,
        'img_id' => 'Imageid',
        'pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'colors' => array(
            'background' => array(255, 255, 255),
            'border' => array(255, 255, 255),
            'text' => array(0, 0, 0),
            // 'grid' => array(255, 40, 40)
        )
    );
    $captcha = create_captcha($vals);
    $session->set('captchaWord', $captcha['word']);
    return $captcha;
}

function captcha_generate2()
{
    // $ci = &get_instance();
    // $ci->load->library('session');
    $session = \Config\Services::session();
    $random_number = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
    $vals = array(
        'word' => $random_number,
        'img_path' => './captcha/',
        'img_url' => base_url('captcha/'),
        'font_path' => './path/to/fonts/texb.ttf',
        'img_width' => '120',
        'img_height' => 30,
        'expiration' => 7200,
        'word_length' => 20,
        'font_size' => 16,
        'img_id' => 'Imageid',
        'pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'colors' => array(
            'background' => array(255, 255, 255),
            'border' => array(255, 255, 255),
            'text' => array(0, 0, 0),
            //'grid' => array(255, 40, 40)
        )
    );
    $captcha2 = create_captcha($vals);
    $session->set('captchaWord2', $captcha2['word']);
    return $captcha2;
}

function epay_encrypt($data)
{

    $authKey = "0egmkz2pLIVi";

    $iv = "abcdef987654";

    if (VC_LIVESERVER == 'N') {
        $encrypt = openssl_encrypt(json_encode($data), 'AES-128-CBC', $authKey, 'OPENSSL_RAW_DATA', $iv);
    } else {
        $encrypt = openssl_encrypt(json_encode($data), 'AES-128-CBC', $authKey, OPENSSL_RAW_DATA, $iv);
    }
    $EncryptTxt = base64_encode($encrypt);

    return $EncryptTxt;
}

function help_id_url($url)
{
    return $url;
}

function nob(&$str)
{
    if (isset($str)) {
        $str = trim($str);
        if ($str == null || strtolower($str) == 'null' || $str == '')
            return true;
        else
            return false;
    } else {
        return true;
    }
}

function Notification($h, $d, $s, $t, $c, $ms, $jl)
{
    $h = '';
    $d = '';
    $s = '';
    $t = '';
    $c = '';
    $ms = 0;
    $jl = '';
    $h = $h;
    $d = $d;
    $s = $s;
    $t = $t;
    $c = $c;
    $ms = $ms;
    $jl = $jl;
}

function pingHost($host, $port, $timeOut)
{
    $notifyList = array();
    $fp = @fsockopen($host, $port, $errNo, $errStr, $timeOut);
    if (!is_resource($fp)) {
        $notifyList = Notification(
            'Sorry!',
            'We are unable to connect to <b>' . $host . ' @ ' . $port . '</b>.
            Please contact Computer Cell. [' . $errStr . '(' . $errNo . ')]',
            Status::ERROR,
            Type::BAR,
            Closeable::YES,
            0,
            ''
        );
        return false;
    } else {
        fclose($fp);
        return true;
    }
}

function doPushSms($mobileNos, $msg, $typeId, $roleIdCsv = '', $templateId = null)
{
    $myUserId = '1111';
    $myAccessId = '2222afafafasds';
    $statusFlag = 'all';
    $mnSplit = explode(',', $mobileNos);
    $mnBatch = array();
    $mnsKey = 0;
    $mnbKey = -1;
    $mnBatch[0] = '';
    foreach ($mnSplit as $mnsKey => $value) {
        if (($mnsKey % 50) == 0) {
            $mnbKey++;
            $mnBatch[$mnbKey] = '';
        }
        if (!nob($value)) {
            $mnBatch[$mnbKey] .= trim($value) . ',';
        }
    }
    foreach ($mnBatch as $mn) {
        try {
            if (pingHost('10.25.78.96', '80', 3)) {
                $mn = rtrim($mn, ",");
                $surl = 'http://10.25.78.96/sms_api/request.php?mobileno=' . $mn . '&smstext=' . rawurlencode($msg .
                    (empty($templateId) ? ' - SUPREME COURT OF INDIA' : '')) . '&rcode=16314056735530425746246&tid=' .
                    ($templateId ?: 1107161579081748207);
                $surlResponse = @file_get_contents($surl);
                echo $surlResponse;
                exit();
                //echo 'Template Id: '.($templateId ?: 1107161243622980738).'<br>';
                //echo 'Mobile Numbers:'.$mn.'<br>';
                //echo 'Message:'.$msg.'<br>';
                //echo $surl.'<br>';
                //echo 'Response:'.$surlResponse.'<br><br>';
                if (strpos(strtolower($surlResponse), 'accepted') !== false) {
                    //if($this->startsWith($surlResponse,'200')){
                    //if (true) {
                    $query = 'insert into eadmin.t_sms_logs (type_id,mobile_nos,message,by_user_id,access_id) values
            (?,?,?,?,?)';
                    $qv = array($typeId, $mn, $msg, $myUserId, $myAccessId);
                    //$this->pushSingle(1, $qv, '', '', '', '', '');
                } else {
                    $statusFlag = 'some';
                }
            } else {
                $statusFlag = 'none';
            }
        } catch (Exception $e) {
        }
        sleep(0.5);
    }
    /*
            if($this->isNewInstance){
            $this->finish();
            }*/
    return $statusFlag;
}
// function sendSMS($typeId = "38", $mobileNo = "", $smsText = "", $templateId = "")
// {
//     if (
//         isset($_SESSION['last_sms_ip']) && $_SESSION['last_sms'] + SMS_RESEND_LIMIT > time() &&
//         $_SESSION['last_sms_template'] == $templateId && $_SESSION['last_sms_ip'] == get_client_ip()
//     ) {
//         die('Please wait ' . SMS_RESEND_LIMIT . ' seconds and then try again.'); // code to stop sms flooding
//     } else {
//         $_SESSION['last_sms'] = time();
//         $_SESSION['last_sms_template'] = $templateId;
//         $_SESSION['last_sms_ip'] = get_client_ip();

//         if (trim($mobileNo) == "") {
//             return "Blank Mobile No.";
//         }
//         date_default_timezone_set('Asia/Kolkata');
//         $sms_url = EADMINSCI_URI . '/a-push-sms-gw?mobileNos=' . $mobileNo . '&message=' . rawurlencode($smsText) .
//             '&typeId=' . $typeId . '&templateId=' . $templateId .
//             '&myUserId=NIC001001&myAccessId=root&authCod=sldkfjsklf126534__sdgdg-sf154ncvbvziu789asdsagd1235';
//         $sms_response = @file_get_contents($sms_url);
//         //print_r($sms_response);
//         $json = json_decode($sms_response);
//         // echo '<pre>Hello_'; print_r($json); die();
//         if (!empty($json) && $json->{'responseFlag'} == "success") {
//             //return "success";
//             $rslt_sms = "success";
//         } else {
//             //return "error";
//             $rslt_sms = "error";
//         }
//         if (!$sms_response) {
//             $sms_response = NULL;
//         }

//         if ($rslt_sms != '') {
//             if (isset($_SESSION['efiling_details']['registration_id']) && isset($_SESSION['login']['id'])) {
//                 $registrationId = $_SESSION['efiling_details']['registration_id'];
//                 $created_byId = $_SESSION['login']['id'];
//             } else if (isset($_SESSION['login']['id'])) {
//                 $registrationId = NULL;
//                 $created_byId = $_SESSION['login']['id'];
//             } else {
//                 $registrationId = NULL;
//                 $created_byId = NULL;
//             }
//             if ($rslt_sms == 'success') {
//                 $staus = TRUE;
//             } else {
//                 $staus = FALSE;
//             }
//             $efiling_sms_details = array(
//                 'mobile_no' => $mobileNo,
//                 'msg' => $smsText,
//                 'type_id' => $typeId,
//                 'sent_on' => date('Y-m-d H:i:s'),
//                 'sms_get_status' => $sms_response,
//                 'status' => $staus,
//                 'registration_id' => $registrationId,
//                 'created_by' => $created_byId
//             );
//             // $ci = &get_instance();
//             // $ci->load->model('common/Common_model');
//             // $ci->load->library('session');
//             $common_model = new \App\Models\Common\CommonModel();
//             $db_response_sms = $common_model->insert_efiling_sms_dtl($efiling_sms_details);
//             return $rslt_sms;
//         } //end of if condition $rslt_sms..
//     }
// }

function send_otp($text, $type_id = 38, $send_otp_mail = 0, $control_flag = 0)
{
    // 09/11/2020
    // $otp=get_new_captcha();
    $digit = 6;
    $otp = numericOTP($digit);
    date_default_timezone_set('Asia/Kolkata');
    $time1 = date("H:i");
    $endTime = date("H:i", strtotime('+15 minutes', strtotime($time1)));
    $mobile = $_SESSION['login']['mobile_number'];
    // $smsText="OTP for ".$text." is ".$otp['word']." and is valid till ".$endTime.". Do not share the OTP with anyone.";
    $smsText = "OTP for " . $text . " is " . $otp . " and is valid till " . $endTime . ". Do not share the OTP with anyone. - Supreme Court of India";
   
    if ($control_flag == 1)
        sendSMS(38, $mobile, $smsText, SCISMS_Ementioning_OTP);
    else
        sendSMS($type_id, $mobile, $smsText, SCISMS_Ementioning_OTP);

    if ($send_otp_mail)
        send_mail_msg($_SESSION['login']['emailid'], $text . ' OTP', $smsText, '');
    $otp_details = array(
        'type_id' => $type_id,
        'mobile' => $mobile,
        'sms_text' => $smsText,
        'updated_on' => date('Y-m-d H:i:s'),
        'updated_by' => $_SESSION['login']['id'],
        'updated_by_ip' => $_SERVER['REMOTE_ADDR'],
        'tbl_efiling_num_id' => $_SESSION['efiling_details']['registration_id'],
        //        'captcha'=>$otp['word'],
        'captcha' => $otp,
        'start_time' => date('H:i:s'),
        'end_time' => $endTime,
        'validate_status' => 'A'
    );
    // $ci = &get_instance();
    // $ci->load->model('common/Common_model');
    // $ci->load->library('session');
    $Common_model = new CommonModel();
    $db_response = $Common_model->insert_otp($otp_details);
    if (!$db_response)
        echo "2@@@Some Error ! Please try after some time.";
    //    else
    //        echo $otp;exit;
}

function resend_otp($type_id, $text, $send_otp_mail = 0)
{
    date_default_timezone_set('Asia/Kolkata');
    $time1 = date("H:i");
    $endTime = date("H:i", strtotime('+15 minutes', strtotime($time1)));
    $updated_by = $_SESSION['login']['id'];
    $mobile = $_SESSION['login']['mobile_number'];
    $efiling_num = $_SESSION['efiling_details']['registration_id'];
    // $ci = &get_instance();
    // $ci->load->model('common/Common_model');
    // $ci->load->library('session');
    $Common_model = new CommonModel();
    $otp_data = $Common_model->get_otp_details($type_id, $updated_by, $mobile, $efiling_num);
    if ($time1 >= $otp_data[0]['end_time']) {
        $response = $Common_model->update_otp_details($type_id, $updated_by, $mobile, $efiling_num, 'E');
        if ($response)
            send_otp($type_id, $text, $send_otp_mail);
    } else {
        $sms_text = $otp_data[0]['sms_text'];
        sendSMS($type_id, $mobile, $sms_text);
    }
}

function validate_otp($type_id, $otp)
{
    $updated_by = $_SESSION['login']['id'];
    $mobile = $_SESSION['login']['mobile_number'];
    $efiling_num = $_SESSION['efiling_details']['registration_id'];
    date_default_timezone_set('Asia/Kolkata');
    $time1 = date("H:i");
    // $ci = &get_instance();
    // $ci->load->model('common/Common_model');
    // $ci->load->library('session');
    $Common_model = new CommonModel();
    $otp_data = $Common_model->get_otp_details($type_id, $updated_by, $mobile, $efiling_num);
    if ($time1 > $otp_data[0]['end_time'])
        return false;
    else {
        if ($otp_data[0]['captcha'] == $otp) {
            $response = $Common_model->update_otp_details($type_id, $updated_by, $mobile, $efiling_num, 'V');
            return true;
        } else if ($otp_data[0]['captcha'] != $otp)
            return false;
    }
}

function showEfilingData($request = [])
{ //todo:sync this function to development branch
    $diaryIds = null;
    $advocateIds = null;
    $documentTypes = null;
    $filingDateRange = null;
    $pendingFilingStages = null;
    //pendingfilingstage is breadcrumbs(1-7) value and documenttype is ia4/miscdoc2/case1/deficitcourtfee3/--not give mentioning8
    extract($request ?: $_GET);
    $data = array();
    $error = array();
    $warning = array();
    $data['errors'] = $error;
    $data['warnings'] = $warning;
    // $ci = &get_instance();
    // $ci->load->model('common/Common_model');
    $Common_model = new CommonModel();
    $details = $Common_model->showEfilingData($advocateIds, $filingDateRange, $pendingFilingStages, $documentTypes, $diaryIds);
    if (count($details)) {
        /* for($i=0;$i<count($details);$i++) {
            // echo "Status   ".$details[$i]['breadcrumb_status'];
         }*/

        $data['data'] = $details;
    }
    return json_encode($data);
}

function showEfiledDocuments($diaryNos)
{
    $data = array();
    // $ci = &get_instance();
    // $ci->load->model('common/Common_model');
    $Common_model = new CommonModel();
    $details = $Common_model->showEfilingDocuments($diaryNos);
    $data['data'] = $details;
    echo json_encode($data);
}

function mentioningRequests()
{
    $diaryIds = null;
    $advocateIds = null;
    $filingDateRange = null;
    $status = null;
    //status is approval status A:accepted R:rejected P:pending for decision
    extract($_GET);
    $data = array();
    $error = array();
    $warning = array();
    $data['errors'] = $error;
    $data['warnings'] = $warning;
    // $ci = &get_instance();
    // $ci->load->model('common/Common_model');
    $Common_model = new CommonModel();
    $details = $Common_model->mentioningRequests($advocateIds, $filingDateRange, $status, $diaryIds);
    if (count($details)) {
        $data['data'] = $details;
    }
    echo json_encode($data);
}

function converDateRangeTodmY($dateRange)
{
    $result = array();
    $dates = explode(',', str_replace(')', '', str_replace('[', '', $dateRange)));
    foreach ($dates as $date) {
        array_push($result, date("d-m-Y", strtotime($date)));
    }
    return $result;
}

function generate_qr_code($params)
{
    $fgc_context = array(
        'http' => array(
            'user_agent' => 'Mozilla',
        ),
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    );
    return json_decode(file_get_contents((COURT_ASSIST_URI . '/qr_code/generate?' . http_build_query($params)), false, stream_context_create($fgc_context)));
}

function get_client_ip()
{
    $ipaddress = '';
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function getJournalName($key)
{
    $journalMaster = '{"1":"AIR","2":"SCR","3":"SCC","4":"JT","5":"SC"}';
    $jsonArray = json_decode($journalMaster, true);
    return $jsonArray[$key];
}

function is_logged_in()
{
    return (isset($_SESSION['login']) && !empty($_SESSION['login']));
}

function is_recaptcha_valid($token)
{
    return true; //TODO:: Remove when go live
    try {
        $validation_result = @json_decode(@file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, stream_context_create(array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query(array(
                    'response' => $token,
                    'secret' => '6LfE13AUAAAAAMic08Z-XTyBFIYxMNtQ31l0Th2G'
                )),
            ),
        ))));
        if (count($validation_result) > 0) {
            if ($validation_result->success) {
                if ($validation_result->score >= 0.5) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    } catch (Exception $e) {
    }

    return false;
}

function curl_post_contents($url, $params)
{
    // Initiate the curl session
    $ch = curl_init();
    // Set the URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($params));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    // Removes the headers from the output
    curl_setopt($ch, CURLOPT_HEADER, 0);
    // Return the output instead of displaying it directly
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // Execute the curl session
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: AutoBot',));

    $output = curl_exec($ch);
    // Close the curl session
    curl_close($ch);
    // Return the output as a variable
    return $output;
}

function curl_request($url, $data, $isJson = 'false', $method = "POST", $token = '')
{
    $curl = curl_init();
    if ($isJson)
        $content = 'Content-Type:application/json';
    else
        $content = "Content-Type: text/plain";

    if (!empty($token))
        $token = "Authorization: Bearer $token";

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => array($content, $token),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function get_challanged_sc_base_case_details($registration_id, $sc_case_type_id_main=null)
{
    // get the lower court diary no of the base case

    $Get_details_model = new GetDetailsModel();

    $base_disposed_cases = $Get_details_model->get_subordinate_court_details($registration_id);
    $lower_court_type = (!empty($base_disposed_cases) && is_array($base_disposed_cases)) ? $base_disposed_cases[0]['court_type'] : '';
    $subject_category = null;
    $subcode1 = null;
    $sc_case_type_id = null;
    if ($lower_court_type == 4)  //court type=4 belongs to supreme court
    {
        $case_type_id = (!empty($base_disposed_cases) && is_array($base_disposed_cases)) ? $base_disposed_cases[0]['case_type_id'] : '';
        $case_no = (!empty($base_disposed_cases) && is_array($base_disposed_cases)) ? $base_disposed_cases[0]['case_num'] : '';
        $case_year = (!empty($base_disposed_cases) && is_array($base_disposed_cases)) ? $base_disposed_cases[0]['case_year'] : '';
        $case_type_id = urlencode($case_type_id);
        $case_no = urlencode($case_no);
        $case_year = urlencode($case_year);
        // $case_data = json_decode(curl_get_contents(ICMIS_SERVICE_URL . "/ConsumedData/caseDetails/?searchBy=C&caseTypeId=$case_type_id&caseNo=$case_no&caseYear=$case_year"));
        $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => ICMIS_SERVICE_URL . "/ConsumedData/caseDetails/?searchBy=C&caseTypeId=$case_type_id&caseNo=$case_no&caseYear=$case_year",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $case_data_d = curl_exec($curl);
            curl_close($curl);
            $case_data = json_decode($case_data_d);
        $case_data = !empty($case_data) ? $case_data->case_details[0] : '';
        $base_disposed_case_diary_no = !empty($case_data) ? $case_data->diary_no . $case_data->diary_year : '';
        if(!empty($base_disposed_case_diary_no)){
            $bdcdetails = file_get_contents(ICMIS_SERVICE_URL . '/ConsumedData/getCaseDetails?diaryNo=' . $base_disposed_case_diary_no);
            $bdcdetailsData = json_decode($bdcdetails);
            // get the submaster id of the base case
            $base_case_subject_category_details = !empty($bdcdetailsData) ? $bdcdetailsData->details : '';
            //  pr($base_case_subject_category_details);

            $subject_category = !empty($base_case_subject_category_details) ? $base_case_subject_category_details[0]->submaster_id : '';
            $subcode1 = !empty($base_case_subject_category_details) ? $base_case_subject_category_details[0]->subcode1 : '';
            $sc_case_type_id = $case_type_id;
            if ($case_type_id==31){ $base_disposed_case_diary_no=$case_no.$case_year; }
            // if ($sc_case_type_id_main==9 || $sc_case_type_id_main==25){
            //     $get_case_details_RP = json_decode(file_get_contents(ICMIS_SERVICE_URL . '/ConsumedData/getCaseDetailsByreview_petition?diaryNo=' . $base_disposed_case_diary_no))->details;
            //      if (isset($get_case_details_RP[0]->ref_new_case_type_id) && !empty($get_case_details_RP[0]->ref_new_case_type_id)){
            //          $sc_case_type_id=$get_case_details_RP[0]->ref_new_case_type_id;
            //      }
            // }
            if ($sc_case_type_id_main == 9 || $sc_case_type_id_main == 25 || $sc_case_type_id_main == 39) {
                $get_case_details_RP = json_decode(file_get_contents(ICMIS_SERVICE_URL . '/ConsumedData/getCaseDetailsByreview_petition?diaryNo=' . $base_disposed_case_diary_no))->details;
                if (isset($get_case_details_RP[0]->ref_new_case_type_id) && !empty($get_case_details_RP[0]->ref_new_case_type_id)) {
                    $sc_case_type_id = $get_case_details_RP[0]->ref_new_case_type_id;
                } else if ($sc_case_type_id_main == 39) {
                    $sc_case_type_id = $sc_case_type_id_main;
                }
            }
        }
    }
    return array('submaster_id' => $subject_category, 'subcode1' => $subcode1, 'case_type_id' => $sc_case_type_id, 'lower_court_type' => $lower_court_type);
}

function calculate_court_fee($registration_id = null, $request_type = null, $within_or_outside_delhi_status_for_affidavit = null, $subject_category_1802_status = null, $is_affidavit_in_form_of_reply_flag = null)
{
    $Common_model = new CommonModel();
    $Get_details_model = new GetDetailsModel();

    $total_court_fee = 0;

    $if_sclsc = (!empty(getSessionData('efiling_details')['if_sclsc']) && (getSessionData('efiling_details')['if_sclsc'] == 1)) ? getSessionData('efiling_details')['if_sclsc'] : 0; //Code added for to make 0 court fee for SCLSC cases on 25072023 by Anshu and KBP
    if (!empty(getSessionData('efiling_details')['if_sclsc']) && (getSessionData('efiling_details')['if_sclsc'] == 1)) {
        $total_court_fee = 0;
        return $total_court_fee;
    } else {
        if (empty($registration_id))
            $registration_id = getSessionData('efiling_details')['registration_id'];
        else
            $registration_id = $registration_id;

        //echo $registration_id;


        if (!empty($registration_id)) {
            $court_fee_calculation_param1 = $Common_model->get_subject_category_casetype_court_fee($registration_id);
            $court_fee_calculation_param3 = $Common_model->get_ia_or_misc_doc_court_fee($registration_id, null, null);
            if (!empty($court_fee_calculation_param3)) {
                $all_added_docs_and_ias = array_column($court_fee_calculation_param3, 'doccode');
            } else {
                $all_added_docs_and_ias = array('doccode');
            }

            if (in_array(getSessionData('efiling_details')['ref_m_efiled_type_id'], array(E_FILING_TYPE_NEW_CASE, E_FILING_TYPE_CAVEAT))) {
                $base_disposed_cases = $Get_details_model->get_subordinate_court_details($registration_id);
                $lower_court_type = !empty($base_disposed_cases) ? $base_disposed_cases[0]['court_type'] : '';
                $lower_court_case_type_id = !empty($base_disposed_cases) ? $base_disposed_cases[0]['case_type_id'] : '';
                $establishment_code = !empty($base_disposed_cases) ? $base_disposed_cases[0]['estab_code'] : '';
            } else {
                $base_disposed_cases = '';
                $lower_court_type = 0;
                $lower_court_case_type_id = 0;
                $establishment_code = 0;
            }
            
            $sc_case_type_id = (is_array($court_fee_calculation_param1) && isset($court_fee_calculation_param1[0]['sc_case_type_id'])) ? $court_fee_calculation_param1[0]['sc_case_type_id'] : '';
            $case_nature = $court_fee_calculation_param1[0]['nature'] ?? '';
            // if ($sc_case_type_id == '9' || $sc_case_type_id == '25') // MA removed from the above condition on 17-07-2023 by KBPUJARI and re-modify Anshu 04-04-2025// for Review Petition , Curative Petition and MA : Court Fee is to be paid same as having paid in the Main/base Matter
            if ($sc_case_type_id == '9' || $sc_case_type_id == '25' || $sc_case_type_id == '39') // MA removed from the above condition on 17-07-2023 by KBPUJARI and re-modify Anshu 04-04-2025// for Review Petition , Curative Petition and MA : Court Fee is to be paid same as having paid in the Main/base Matter
            {
                $base_case_subject_category_details = get_challanged_sc_base_case_details($registration_id,$sc_case_type_id);
                $subject_category = $base_case_subject_category_details['submaster_id'];
                $subcode1 = $base_case_subject_category_details['subcode1'];
                $sc_case_type_id = $base_case_subject_category_details['case_type_id'];
                // if ($sc_case_type_id==2 || $sc_case_type_id==4 || $sc_case_type_id==10){
                //     $case_nature = 'R';
                // }
                $case_type_id_CRL = $base_case_subject_category_details['case_type_id'];
                $allowed_case_type_id_CRL = array(2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 26, 28, 33, 35, 36, 41); // ALl Criminal Casetype
                if (in_array($case_type_id_CRL, $allowed_case_type_id_CRL)) {
                    $case_nature = 'R';
                    $sc_case_type_id = $case_type_id_CRL;
                } else if ($sc_case_type_id == 39) {
                    $sc_case_type_id = $sc_case_type_id;
                } else {
                    $sc_case_type_id = $case_type_id_CRL;
                }
            } else {
                $subject_category = is_array($court_fee_calculation_param1) ? $court_fee_calculation_param1[0]['subject_cat'] : ''; //submaster id
                $subcode1 = is_array($court_fee_calculation_param1) ? $court_fee_calculation_param1[0]['subcode1'] : '';
                $sc_case_type_id = is_array($court_fee_calculation_param1) ? $court_fee_calculation_param1[0]['sc_case_type_id'] : '';
            }
            if ($request_type == '1') {
                if ($sc_case_type_id == 19) {
                    $case_nature = 'R'; //if the contempt petition casetype is selected then it can be treated as criminal matters thus no doc fees will be applicable
                }
                //var_dump($court_fee_calculation_param1);
                if (!empty($court_fee_calculation_param1)) {
                    $no_of_lower_court_order_challanged = $court_fee_calculation_param1[0]['order_challanged'];

                    if (rtrim($case_nature) == 'C') // court fee only applicable for civil matters and 0 for criminal matter based on its casetype
                    {
                        // if ($court_fee_calculation_param1[0]['sc_case_type_id'] == '9' || $court_fee_calculation_param1[0]['sc_case_type_id'] == '25') // MA removed from the above condition on 17-07-2023 by KBPUJARI
                        // // for Review Petition , Curative Petition and MA : Court Fee is to be paid same as having paid in the Main/base Matter
                        // {
                        //     //|| ($court_fee_calculation_param1[0]['sc_case_type_id']=='19' && $lower_court_type!=4)
                        //     $base_case_subject_category_details = get_challanged_sc_base_case_details($registration_id);
                        //     // pr($base_case_subject_category_details);
                        //     $subject_category = $base_case_subject_category_details['subject_category'];
                        //     $subcode1 = $base_case_subject_category_details['subcode1'];
                        //     $sc_case_type_id = $base_case_subject_category_details['case_type_id'];
                        // } else {
                        //     $subject_category = $court_fee_calculation_param1[0]['subject_cat']; //submaster id
                        //     $subcode1 = $court_fee_calculation_param1[0]['subcode1'];
                        //     $sc_case_type_id = $court_fee_calculation_param1[0]['sc_case_type_id'];
                        // }

                        //echo $subject_category.'#'.$subcode1.'#'.$sc_case_type_id;
                        $court_fee_helper_flag = $court_fee_calculation_param1[0]['court_fee_calculation_helper_flag'];
                        $case_total_petitioners = $court_fee_calculation_param1[0]['total_petitioners'];

                        //subject category condition:start
                        //var_dump($court_fee_calculation_param1);
                        if ($subcode1 == '8' && $sc_case_type_id != '7' && $sc_case_type_id != '23' && $sc_case_type_id != '24' && $sc_case_type_id != '1' && $sc_case_type_id != '3') // for Letter Petition & Pil Matters:130(except submaster id:133) court fee will fixed as 500 * no of petitioners filing the matters. changed on 26/04/2021
                        {
                            if ($subject_category != '133') {
                                $total_court_fee = 500 * (int)$case_total_petitioners;
                            } else
                                $total_court_fee = (int)$total_court_fee + 1500;
                        } elseif ($sc_case_type_id == '1') //SLP
                        {
                            if ($subcode1 == '8' || $lower_court_case_type_id == '5' || $lower_court_case_type_id == '7' || $establishment_code == 'NCDRC') {
                                $total_court_fee = 1500 * (int)$no_of_lower_court_order_challanged;
                            } elseif ($establishment_code == 'NCDRC') {
                                $total_court_fee = 5000;
                            } elseif ($lower_court_case_type_id == '19' && $lower_court_type == 4) // if lowercourt casetype is contempt petition and lowercourt is supreme court then 0 court fee will be charged
                            {
                                $total_court_fee = 0;
                            } else {
                                $total_court_fee = (int)$total_court_fee + $court_fee_calculation_param1[0]['court_fee'];
                            }
                        } elseif ($sc_case_type_id == '3') //civil appeal
                        {

                            // if ($establishment_code == 'NGTD' || $establishment_code == 'AFT') //490506=Natioan green tribunal , 226817=Armed force tribunal
                            if ($establishment_code == 'NGTD' || $establishment_code == 'AFT' || $establishment_code == 'NGTWB') // 490506 = Natioan green tribunal , 226817 = Armed force tribunal, 21945 = NGTWB (NATIONAL GREEN TRIBUNAL, EASTERN ZONE BENCH, KOLKATA)
                            {
                                $total_court_fee = 1500;
                            } elseif ($establishment_code == 'NCDRC') {
                                $total_court_fee = 5000;
                            } else {
                                $total_court_fee = (int)$total_court_fee + 5000;
                            }
                        } elseif ($sc_case_type_id == '23') // election petition
                        {
                            $total_court_fee = (int)$total_court_fee + 20000;
                        } elseif ($sc_case_type_id == '24') // arbitration petition
                        {
                            $total_court_fee = (int)$total_court_fee + 5000;
                        } elseif ($sc_case_type_id == '5') // writ petition
                        {
                            $total_court_fee = 500 * (int)$case_total_petitioners;
                        } elseif ($sc_case_type_id == '7') // for the casetype-Transfer Petition - if matrimonial then 500 else 2500 court fee will be charged . subject category court fee will not be applicable in the above case as well as for the subject category 1802. Changes done on 16/06/2021.
                        {
                            if ($court_fee_helper_flag == 'Y')
                                $total_court_fee = (int)$total_court_fee + 500; // 500 for matrimonial case
                            else
                                $total_court_fee = (int)$total_court_fee + 2500;
                        } elseif ($sc_case_type_id == '19')  // 0 court fee will be applicable for the Contempt petition (Civil) nature cases - self case of supreme court
                        {
                            //if($lower_court_type=='4')
                            $total_court_fee = 0;
                        } elseif ($sc_case_type_id == '39')  // 100 court fee will be applicable for the MA added by kbp on 17072023
                        {
                            $total_court_fee = 100;
                        } else {
                            switch ($subject_category) {
                                case '133': {
                                        $total_court_fee = (int)$total_court_fee + 1500;
                                        break;
                                    } //letter petition special category: SLPs filed against judgments / orders passed by the High Courts in Writ Petitions filed as PIL
                                case '134': {
                                        $total_court_fee = (int)$total_court_fee + 20000;
                                        break;
                                    } // eletion matter :Matters challenging election of President & Vice-President of India
                                case '144': {
                                        $total_court_fee = (int)$total_court_fee + 20000;
                                        break;
                                    } // election matter : Appeals u/s 116 A of Representation of People Act, 1951.
                                case '222': { // Ordinary Civil Matters : T.P. Under Section 25 of the C.P.C.
                                        if ($court_fee_helper_flag == 'Y')
                                            $total_court_fee = (int)$total_court_fee + 500; // 500 for matrimonial case
                                        else
                                            $total_court_fee = (int)$total_court_fee + 2500;
                                        break;
                                    }
                                case '164': { //for the subject category "HABEAS CORPUS MATTERS" : Nature of the Matter will be Criminal Only so the court fee will be 0.if it is file as civil then the court fee will be calculated as:( changes done on 26/04/21)
                                        if ($sc_case_type_id == '5')
                                            $total_court_fee = (int)$total_court_fee + 500;
                                        else
                                            $total_court_fee = (int)$total_court_fee + 1500;
                                        break;
                                    }
                                case '200': {
                                        $total_court_fee = (int)$total_court_fee + 5000;
                                        break;
                                    } //Appeal Against Orders Of Statutory Bodies:Bar Council of India
                                case '224': {
                                        $total_court_fee = (int)$total_court_fee + 2500;
                                        break;
                                    } //Ordinary Civil Matters:Original Civil Suit under Article 131 of the Constitution of India
                                case '298': {
                                        $total_court_fee = (int)$total_court_fee + 5000;
                                        break;
                                    } //Matters Relating To Consumer Protection
                                default: {
                                        $total_court_fee = (int)$total_court_fee + $court_fee_calculation_param1[0]['court_fee'];
                                        break;
                                    }
                            }
                        }

                        // no_of_lower_court_order_challanged
                        if ($no_of_lower_court_order_challanged > 0 && $sc_case_type_id != '5' && $subcode1 != '8') {
                            $total_court_fee = (int)$total_court_fee * (int)$no_of_lower_court_order_challanged;
                        }

                        // vakalatnama condition - start
                        //if vakalatnama added from indexing then defualt one will not applicable
                        if (!in_array('12', $all_added_docs_and_ias) && !in_array('13', $all_added_docs_and_ias)) {
                            if ($sc_case_type_id != 39) {
                                $court_fee_calculation_param2 = $Common_model->get_ia_or_misc_doc_court_fee(null, 12, 0);
                                if (rtrim($case_nature) == 'C') {
                                    if ($sc_case_type_id == '5')  // for writ petition
                                    {
                                        $total_court_fee = (int)$total_court_fee + (int)$court_fee_calculation_param2[0]['docfee'] * (int)$case_total_petitioners;
                                    } else {
                                        if ($no_of_lower_court_order_challanged > 0) {
                                            $total_court_fee = (int)$total_court_fee + (int)$court_fee_calculation_param2[0]['docfee'] * (int)$no_of_lower_court_order_challanged;
                                        } else {
                                            $total_court_fee = $total_court_fee + (int)$court_fee_calculation_param2[0]['docfee'];
                                        }
                                    }
                                }
                            }
                        }
                        //vakalatnama condition - end
                    }
                }
            }

            if ($request_type == '2') // IA & Misc. doc fees
            { 
                //pr('IA & Misc. doc fees');
                $court_fee_calculation_param3 = $Common_model->get_ia_or_misc_doc_court_fee($registration_id, null, null); // retrieve the court fee
                $diary_no = !empty($court_fee_calculation_param3) ? (int)$court_fee_calculation_param3[0]['diary_no'] . (int)$court_fee_calculation_param3[0]['diary_year'] : '';
                //uncheck the following line:when we further check the case nature based on ICMIS -main table case:case_group
                /*if (empty($diary_no) || $diary_no == 00) {
                    $diary_no = (int)$court_fee_calculation_param3[0]['sc_diary_num'] . (int)$court_fee_calculation_param3[0]['sc_diary_year'];
                }*/

                if (empty($case_nature)) {
                    $case_nature = $court_fee_calculation_param1[0]['nature'] ?? '';
                }
                if (empty($case_nature) && !empty($diary_no)) {
                    $case_nature = file_get_contents(ICMIS_SERVICE_URL . '/ConsumedData/caseNature?diaryNo=' . $diary_no);
                }
 //echo 'case_nature='.$case_nature;exit;
                //uncheck the following line:when we require to further check the case nature based on ICMIS -main table case:case_group
                /*$case_nature_from_icmis = file_get_contents(ICMIS_SERVICE_URL . '/ConsumedData/caseNature?diaryNo=' . $diary_no);
                echo 'For Diary : ' . $diary_no . ', From efiling' . $case_nature . ' from ICMIS:' . $case_nature_from_icmis;
                if ($case_nature_from_icmis == 'R') {
                    $case_nature = 'R';
                }*/
                //Code added by kbpujari as on 08052024 for checking the case nature based on selected subject category
                $case_nature_from_icmis = getCaseDetailsWithNatureBasedOnSubjectCategory($registration_id);
                if ($case_nature_from_icmis == 'R') {
                    $case_nature = 'R';
                }

                //TODO code for getting details of SC case nature which entry is done as lower court case if :done by kbp on 08122023
                if (rtrim($case_nature) == 'C' || empty($case_nature)) {
                    //echo ICMIS_SERVICE_URL . '/ConsumedData/getCaseLowerCtDetailsWithCaseNature?diary_no'.$diary_no;exit();
                    $case_lct_details = file_get_contents(ICMIS_SERVICE_URL . '/ConsumedData/getCaseLowerCtDetailsWithCaseNature?diary_no=' . $diary_no);
                    $case_lct_details = $case_lct_details ? json_decode($case_lct_details) : null;
                    if (!empty($case_lct_details[0]->base_case_catetype_id)) {
                        if ($case_lct_details[0]->base_case_catetype_id == 39); // Condition will be checked only for MA cases(i.e casetype id : 39)
                        {
                            $ma_lower_case_case_group = ($case_lct_details[0]->lct_case_case_grp) ? $case_lct_details[0]->lct_case_case_grp : null;
                            if ($ma_lower_case_case_group == 'R')
                                $case_nature = 'R';
                        }
                    }
                }
                
                if ($sc_case_type_id == 19) {
                    $case_nature = 'R'; //if the contempt petition casetype is selected then it can be treated as criminal matters thus no doc fees will be applicable
                }


                //echo $case_nature.'#'.getSessionData('efiling_details')['ref_m_efiled_type_id'];

                if (!empty($court_fee_calculation_param1) || getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_IA || getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS || getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT || getSessionData('efiling_details')['ref_m_efiled_type_id'] == OLD_CASES_REFILING) {
                    if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
                        $arr = array();
                        $arr['registration_id'] = $registration_id;
                        $arr['step'] = 6;
                        $caveat_details = $Common_model->getCaveatDataByRegistrationId($arr);
                        if (!empty($caveat_details[0]))
                            $case_nature = $caveat_details[0]['nature'];
                    }
                    
                    if ((rtrim($case_nature) == 'C' && getSessionData('efiling_details')['ref_m_efiled_type_id'] != E_FILING_TYPE_CAVEAT && getSessionData('efiling_details')['ref_m_efiled_type_id'] != E_FILING_TYPE_IA) || (rtrim($case_nature) == 'C' && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) || (rtrim($case_nature) == 'C' && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) || (rtrim($case_nature) == 'C' && getSessionData('efiling_details')['ref_m_efiled_type_id'] == OLD_CASES_REFILING) && $sc_case_type_id != '19') // CHNAGE BY KBPUJARI ON 28062023 TO MAKE 0 COURT FEE FOR THE CAVEAT FILING IF THE CASE TYPE IS SELECTED AS CRIMINAL
                    //if($case_nature == 'C' || getSessionData('efiling_details')['ref_m_efiled_type_id']==4 || getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT && $sc_case_type_id!='19') // court fee only applicable for civil matters and 0 for criminal matter based on its casetype
                    {

                        $no_of_lower_court_order_challanged_for_caveat = !empty($court_fee_calculation_param3) ? $court_fee_calculation_param3[0]['trial_court_order_challanged_for_caveat'] : '';
                        // var_dump($court_fee_calculation_param3);
                        if (is_array($court_fee_calculation_param3)) {
                            foreach ($court_fee_calculation_param3 as $row) {
                                $doc = (int)$row['doccode'] . (int)$row['doccode1'];
                                $no_of_affidavit_copies = $row['no_of_affidavit_copies'];
                                $doc_list_with_letter_of_inspection_file = array(150); // Doc:letter
                                $doc_list_no_of_non_party_appellant = array(895);
                                $doc_list_with_affidavit = array(20, 50, 190); //Doc:REPLY,REPORT and ADDITIONAL DOCUMENT
                                $doc_list_of_affidavit_attested_place = array(30, 60, 90, 110, 170, 220); //REJOINDER AFFIDAVIT,COUNTER AFFIDAVIT,ADDITIONAL AFFIDAVIT,AFFIDAVIT,AFFIDAVIT OF SERVICE and AFFIDAVIT OF UNDERTAKING
                                $doc_list_for_per_petition_calculation = array(84); //Doc:LEGAL HEIRS TO BE BROUGHT ON RECORD
                                $doc_list_for_per_lower_court_order_challanged_number = array(85, 86, 87, 817, 120, 827, 828, 829, 830, 831, 835, 8328, 130); //Doc:INTERVENTION APPLICATION,ADDITION / DELETION / MODIFICATION  PARTIES,AMENDMENT IN RECORD,EXEMPTION FROM FILING C/C OF THE IMPUGNED JUDGMENT,VAKALATNAMA,STAY APPLICATION,CONDONATION OF DELAY IN FILING,APPLICATION FOR SUBSTITUTION,SETTING ASIDE AN ABATEMENT,COMPROMISE R.3 O.23,DISPENSING WITH SERVICE OF notice,PERMISSION TO FILE PETITION (SLP/TP/WP/..),EXEMPTION FROM FILING TRIAL COURT order and VAKALATNAMA AND MEMO OF APPEARANCE
                                $doc_list_of_zero_doc_fee_for_criminal_matter = array(842, 8120, 8137, 8144, 8152, 8153, 8160, 8252, 8271, 8302, 8324, 8346, 8368, 8385);

                                if (in_array((int)$doc, $doc_list_with_affidavit, TRUE)) {
                                    if ($row['court_fee_calculation_helper_flag'] == 'Y') //DOC submit with Affidavit flag updated from
                                        $total_court_fee = (int)$total_court_fee + (int)$row['docfee'];
                                    else
                                        $total_court_fee = (int)$total_court_fee + 0;
                                } elseif (in_array((int)$doc, $doc_list_of_affidavit_attested_place, TRUE)) {
                                    /*if($row['court_fee_calculation_helper_flag']=='Y') //DOC submit with Affidavit attested within Delhi
                                        $total_court_fee=(int)$total_court_fee+(int)$row['docfee'];
                                    else
                                        $total_court_fee=(int)$total_court_fee+0;*/

                                    if ($doc == '11' && $sc_case_type_id == '19')  //For Affidavit 0 court fee will be applicable for the Affidavit id - Contempt petition (Civil) nature cases                            {
                                        $total_court_fee = 0;
                                    else {
                                        if ($no_of_affidavit_copies > 0) {
                                            $total_court_fee = (int)$total_court_fee + (int)$row['docfee'] * $no_of_affidavit_copies;
                                        } else {
                                            $total_court_fee = $total_court_fee + (int)$row['docfee'];
                                        }
                                    }
                                } elseif (in_array((int)$doc, $doc_list_with_letter_of_inspection_file, TRUE)) {
                                    if ($row['court_fee_calculation_helper_flag'] == 'Y') //DOC submit with Affidavit flag updated from
                                        $total_court_fee = (int)$total_court_fee + (int)$row['docfee'];
                                    else
                                        $total_court_fee = (int)$total_court_fee + 0;
                                } elseif (in_array((int)$doc, $doc_list_for_per_petition_calculation, TRUE)) {
                                    $total_court_fee = $total_court_fee + (int)$row['docfee'] * (int)$row['total_petitioners'];
                                } elseif (in_array((int)$doc, $doc_list_for_per_lower_court_order_challanged_number, TRUE)) {
                                    if ($row['order_challanged'] > 0) {
                                        $total_court_fee = (int)$total_court_fee + (int)$row['docfee'] * (int)$row['order_challanged'];
                                    } else {
                                        $total_court_fee = (int)$total_court_fee + (int)$row['docfee'];
                                    }
                                } elseif (in_array((int)$doc, $doc_list_of_zero_doc_fee_for_criminal_matter, TRUE)) {
                                    if (rtrim($case_nature) == 'C') //these documents fee only applicable for civil matters
                                        $total_court_fee = (int)$total_court_fee + (int)$row['docfee'];
                                    else
                                        $total_court_fee = (int)$total_court_fee + 0;
                                } elseif (in_array((int)$doc, $doc_list_no_of_non_party_appellant, TRUE)) {
                                    if (rtrim($case_nature) == 'C') {
                                        if ((int)$row['no_of_petitioner_appellant'] > 0) {
                                            $total_court_fee = (int)$total_court_fee + (int)$row['docfee'] * (int)$row['no_of_petitioner_appellant'];
                                        } else {
                                            $total_court_fee = (int)$total_court_fee + (int)$row['docfee'];
                                        }
                                    } else
                                        $total_court_fee = (int)$total_court_fee + 0;
                                } else {

                                    $total_court_fee = (int)$total_court_fee + (int)$row['docfee'];
                                }
                            }
                        }

                        if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
                            $court_fee_calculation_param2 = $Common_model->get_ia_or_misc_doc_court_fee(null, 12, 0); //vakalatnama will be automatically added with caveat and multiple of each lower court order challanged
                            if ($no_of_lower_court_order_challanged_for_caveat > 0) {
                                $total_court_fee = (int)$total_court_fee + (int)$court_fee_calculation_param2[0]['docfee'] * (int)$no_of_lower_court_order_challanged_for_caveat;
                            } else {
                                $total_court_fee = $total_court_fee + (int)$court_fee_calculation_param2[0]['docfee'];
                            }
                        }

                        $case_if_sclsc_status = file_get_contents(ICMIS_SERVICE_URL . '/ConsumedData/checkCaseIfSCLSCStatus?diaryNo=' . $diary_no);
                        if ($case_if_sclsc_status == 1) // code added on 19082023 by kbp for making court fee 0 : when any document/is filed in the if_sclsc case
                        {
                            $total_court_fee = 0;
                        }
                        //add affidavit bydefault if not added from indexing as docs code committed by kbpujari on 14022023
                        /*
                            if(!(in_array('11', $all_added_docs_and_ias)))
                            {
                                $court_fee_calculation_param4=$ci->Common_model->get_ia_or_misc_doc_court_fee(null,11,0); // retrieve the court fee for affidavit(court:fee-Rs.20) mandatory by default except  0 for the contempt petition and Criminal cases

                                if ($case_nature == 'C') {
                                    if ((int)$no_of_affidavit_copies > 0) {
                                        $total_court_fee = $total_court_fee + (int)$court_fee_calculation_param4[0]['docfee'] * $no_of_affidavit_copies;
                                    }
                                    else
                                    {
                                        $total_court_fee = $total_court_fee + (int)$court_fee_calculation_param4[0]['docfee'];
                                    }

                                }
                            }*/
                    }
                }
            }
        }
    }

    return $total_court_fee;
}

function getCaseDetailsWithNatureBasedOnSubjectCategory($registration_id)
{
    $case_nature = '';
    // $ci = &get_instance();
    // $ci->load->model('newcase/Common_model');

    $Common_model = new CommonModel();
    $case_details_with_subject_category = $Common_model->get_case_details_with_selected_subject_category($registration_id);
    if (!empty($case_details_with_subject_category)) {
        $selected_subject_category = $case_details_with_subject_category[0]['subcode1'];
        if ($selected_subject_category == 14) // is the subcode
            $case_nature = 'R';
    }
    return $case_nature;
}

function get_pspdfkit_jwt($document_id, $permissions = ['read-document'])
{
    //$ci = &get_instance();
    //$ci->load->library('FirebasePhpJwt');

    $payload = array(
        "exp" => 2454838399,
        "document_id" => $document_id,
        'permissions' => $permissions
    );

    return \Firebase\JWT\JWT::encode($payload, file_get_contents('assets/keys/oauth-private.key'), 'RS256');
}

if (!function_exists("numericOTP")) {
    function numericOTP($n = null)
    {
        $generator = "1357902468";
        $result = "";
        if (isset($n) && !empty($n)) {
            for ($i = 1; $i <= $n; $i++) {
                $result .= substr($generator, (rand() % (strlen($generator))), 1);
            }
        }
        return $result;
    }
}
function uploadDocumentToPSPDFKit($content)
{
    $pspdfkit_document = [];
    try {
        $client = new Client();
        $response = $client->post(
            //  PSPDFKIT_SERVER_URI . '/api/documents',
            PSPDFKIT_SERVER_URI . '/api/documents',
            [
                'headers' => [
                    'Authorization' => 'Token token="secret"',
                    'Content-Length' => 0,
                    'Content-Type' => 'application/pdf',
                    'Transfer-Encoding' => 'chunked',
                    'cache-control' => 'no-cache',
                ],
                'http_errors' => false,
                'body' => $content
            ]
        );
        if ($response->getStatusCode() == 200) {
            $pspdfkit_document = json_decode($response->getBody()->getContents(), true)['data'];
        }
    } catch (GuzzleException $e) {
        //$if_save_case_file_paper_book = false;
    }
    return $pspdfkit_document;
}

function uploadDocumentToPSPDFKitAlt($contents, $document_id = null, $if_overwrite_existing_document = null)
{
    $pspdfkit_document = [];
    $params = [];
    if (!empty($document_id)) {
        $params[] = ['name' => 'document_id', 'contents' => $document_id];
    }
    if ($if_overwrite_existing_document !== null) {
        $params[] = ['name' => 'overwrite_existing_document', 'contents' => $if_overwrite_existing_document];
    }
    try {
        $client = new Client();
        $response = $client->post(
            //  PSPDFKIT_SERVER_URI . '/api/documents',
            PSPDFKIT_SERVER_URI . '/api/documents',
            [
                'headers' => [
                    'Authorization' => 'Token token="secret"',
                    'Content-Length' => 0,
                    'accept: application/json',
                ],
                'http_errors' => false,
                'multipart' => array_merge(
                    [
                        ['name' => 'file', 'contents' => $contents, 'filename' => 'file'],
                    ],
                    $params
                )
            ]
        );
        if ($response->getStatusCode() == 200) {
            $pspdfkit_document = json_decode($response->getBody()->getContents(), true)['data'];
        }
    } catch (GuzzleException $e) {
        //$if_save_case_file_paper_book = false;
    }
    return $pspdfkit_document;
}

function copyDocument($document_id)
{
    return $pspdfkit_document['document_id'] = null;
    $pspdfkit_document = [];
    try {
        $client = new Client();
        $response = $client->post(
            //PSPDFKIT_SERVER_URI . '/api/documents/'.$document_id,
            PSPDFKIT_SERVER_URI . '/api/copy_document/',
            [
                'headers' => [
                    'Authorization' => 'Token token="secret"',
                ],
                'http_errors' => false,
                GuzzleHttp\RequestOptions::JSON => ['document_id' => $document_id]
            ]
        );
        if ($response->getStatusCode() == 200) {
            $pspdfkit_document = json_decode($response->getBody()->getContents(), true)['data'];
        }
    } catch (GuzzleException $e) {
        //$if_save_case_file_paper_book = false;
    }
    return $pspdfkit_document;
}

function get_extra_party_P_or_R($type_id)
{
    $registration_id = getSessionData('efiling_details')['registration_id'];
    $Get_details_model = new GetDetailsModel();
    return $Get_details_model->get_no_of_extra_party($registration_id, $type_id);
}

function check_party($type_id = null)
{
    return true;
    $registration_id = $_SESSION['efiling_details']['registration_id'];
    // $ci = &get_instance();
    // $ci->load->model('newcase/Get_details_model');
    // $ci->load->library('session');
    $Get_details_model = new GetDetailsModel();
    $StageArray = explode(',', $_SESSION['efiling_details']['breadcrumb_status']);
    $total_petitioners = 0;
    $total_respondents = 0;
    $breadcrumb_status = count($StageArray);
    $step = 11;

    $total_petitioners = $Get_details_model->get_no_of_extra_party($registration_id, 'P');

    $total_respondents = $Get_details_model->get_no_of_extra_party($registration_id, 'R');
    if (!empty($type_id) && $type_id != null && $type_id = 'P') {
        return $total_petitioners;
    } else if (!empty($type_id) && $type_id != null && $type_id = 'R') {
        return $total_respondents;
    }
    if ($_SESSION['efiling_details']['no_of_petitioners'] <= $total_petitioners && $_SESSION['efiling_details']['no_of_respondents'] <= $total_respondents) {
        return true;
    } else {
        return false;
    }


    return $Get_details_model->get_no_of_extra_party($registration_id, $type_id);
}

function compare_multi_Arrays($array1, $array2)
{
    $result = array("more" => array(), "less" => array(), "diff" => array());
    foreach ($array1 as $k => $v) {
        if (is_array($v) && isset($array2[$k]) && is_array($array2[$k])) {
            $sub_result = compare_multi_Arrays($v, $array2[$k]);
            //merge results
            foreach (array_keys($sub_result) as $key) {
                if (!empty($sub_result[$key])) {
                    $result[$key] = array_merge_recursive($result[$key], array($k => $sub_result[$key]));
                }
            }
        } else {
            if (isset($array2[$k])) {
                if ($v !== $array2[$k]) {
                    $result["diff"][$k] = array("from" => $v, "to" => $array2[$k]);
                }
            } else {
                $result["more"][$k] = $v;
            }
        }
    }
    foreach ($array2 as $k => $v) {
        if (!isset($array1[$k])) {
            $result["less"][$k] = $v;
        }
    }
    return $result;
}

function multi_diff($arr1, $arr2)
{
    $result = array();
    foreach ($arr1 as $k => $v) {
        if (!isset($arr2[$k])) {
            $result[$k] = $v;
        } else {
            if (is_array($v) && is_array($arr2[$k])) {
                $diff = multi_diff($v, $arr2[$k]);
                if (!empty($diff))
                    $result[$k] = $diff;
            }
        }
    }
    return $result;
}

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function getPspdfkitPagesCount($pspdfkit_document_id)
{
    $info_contents = file_get_contents(PSPDFKIT_SERVER_URI . "/api/documents/$pspdfkit_document_id/document_info", false, stream_context_create([
        'http' => [
            'header' => "Authorization: Token token=\"secret\"",
        ],
    ]));
    $info_contents_obj = json_decode($info_contents);
    $pages = $info_contents_obj->data->pageCount;
    return (int)$pages;
}

function showOCRAlert($pspdfkit_document_id)
{
    //$pspdfkit_document_id = '7KRV51P0HWHNMW52GFTQ11ZWMK';
    $pagescount = getPspdfkitPagesCount($pspdfkit_document_id);
    if ($pagescount <= MIN_PAGES_FOR_OCR_CHECK) {
        return false;
    }
    $pageindexarr = getPageIndexesNeedingOCR($pspdfkit_document_id, $pagescount);
    $pages_needing_ocr_count = count($pageindexarr);
    $pages_needing_ocr_PERCENT = (int)(($pages_needing_ocr_count / $pagescount) * 100);
    if ($pages_needing_ocr_PERCENT > PERCENT_MIN_PAGES_FOR_OCR) {
        return true;
    } else {
        return false;
    }
}

function getPageIndexesNeedingOCR($pspdfkit_document_id, $no_of_pages)
{
    $page_index_needing_ocr_arr = array();
    for ($i = 0; $i < (int)$no_of_pages; $i++) {
        $page_needs_ocr = true;
        $page_contents_json = getPspdfkitPageContentsJson($pspdfkit_document_id, $i);
        $obj = json_decode($page_contents_json);
        if (!empty($obj->textLines)) {
            foreach (@$obj->textLines as $line) {
                if (@$line->contents == '' || @$line->contents == null) {
                    continue;
                } else {
                    $page_needs_ocr = false;
                    break;
                }
            }
        }
        if ($page_needs_ocr == true) {
            array_push($page_index_needing_ocr_arr, $i);
        }
    }
    return $page_index_needing_ocr_arr;
}

function getPspdfkitPageContentsJson($pspdfkit_document_id, $page_index)
{
    $page_contents_json = file_get_contents(PSPDFKIT_SERVER_URI . "/api/documents/$pspdfkit_document_id/pages/$page_index/text", false, stream_context_create([
        'http' => [
            'header' => "Authorization: Token token=\"secret\"",
        ],
    ]));
    return $page_contents_json;
}

//Anshu on 28 May 21
function get_affidavit_pdf_details($sc_case_type_id)
{
    // $ci = &get_instance();
    // $ci->load->model('newcase/Get_details_model');
    // $ci->load->model('appearing_for/Appearing_for_model');
    // $ci->load->library('session');
    $Appearing_for_model = new AppearingForModel();
    $supplement_model = new SupplementModel();
    $session = \Config\Services::session();
    $registration_id = $_SESSION['efiling_details']['registration_id'];
    $parties = $supplement_model->get_signers_list($registration_id);
    $data = array();
    if (!empty($sc_case_type_id)) {
        if ($_SESSION['efiling_details']['database_type'] == 'E') {
            if ($sc_case_type_id == 12 || $sc_case_type_id == 13) {
                //echo 'area parties IA';
                $parties = $Appearing_for_model->get_case_parties_list($registration_id);
                $data = array([
                    'petitioner_name' => $parties[0]['p_partyname'],
                    'respondent_name' => $parties[0]['r_partyname'],
                ]);
            } else {
                //echo 'area parties new case';
                $relation = '';
                if (!empty($parties[0]['relation'])) {
                    $relation = $parties[0]['relation'] . '/o';
                }
                $data = array([
                    'petitioner_name' => $parties[0]['party_name'],
                    'relation' => $relation,
                    'relative_name' => $parties[0]['relative_name'],
                    'party_age' => $parties[0]['party_age'],
                    'petitioner_address' => $parties[0]['party_address'],
                ]);
            }
        } else if ($_SESSION['efiling_details']['database_type'] == 'I') {
            $data = array([
                'petitioner_name' => $_SESSION['efiling_details']['pet_name'],
                'respondent_name' => $_SESSION['efiling_details']['res_name'],
                'case_num' => $_SESSION['efiling_details']['case_num'],
                'case_year' => $_SESSION['efiling_details']['case_year'],
                'relation' => '',
                'relative_name' => '',
                'party_age' => '',
                'petitioner_address' => '',
            ]);
        }
        return $data;
    } else {
        echo 'required sc case type id';
        exit();
    }
}

function shareDocToEmail($params = array())
{
    $result = false;
    if (
        isset($params['email']) && !empty($params['email']) && isset($params['file_url']) && !empty($params['file_url']) &&
        isset($params['subject']) && !empty($params['subject']) && isset($params['message']) && !empty($params['message'])
    ) {
        $to_email = $params['email'];
        $file_url = $params['file_url'];
        $subject = $params['subject'];
        $message = $params['message'];
        // $ci = &get_instance();
        // $ci->load->library('email');
        $email = \Config\Services::email();
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.mail.gov.in',
            'smtp_port' => 465,
            'smtp_user' => 'causelists@nic.in',
            'smtp_pass' => 'eCourts*1234',
            'mailtype' => 'html',
            'charset' => 'utf-8'
        );
        $email->initialize($config);
        $email->setMailType("html");
        $email->setNewline("\r\n");
        $email->setTo($to_email);
        $email->setFrom('causelists@nic.in', "Supreme Court of India");
        $email->setSubject($subject);
        $email->setMessage($message);
        $response = $email->send();
        if ($response) {
            $result = 'success';
        } else {
            $result = 'error';
        }
    }
    return $result;
}

function reset_affirmation($registration_id = null)
{
    $Affirmation_model = new AffirmationModel();

    $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, JAIL_SUPERINTENDENT);
    if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
        return $response = 'Unauthorised Access !';
    }

    $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
    if (!in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
        return $response = 'Invalid Stage.';
    }

    if (!empty($registration_id) && $registration_id != null) {
        $registration_id = $registration_id;
    } else {
        $registration_id = getSessionData('efiling_details')['registration_id'];
    }
    $docid = '';
    $esigned_docs_details = $Affirmation_model->get_esign_doc_details($registration_id);
    if (!empty($esigned_docs_details) && $esigned_docs_details != FALSE) {
        $docid = $esigned_docs_details[0]->id;
    }
    if (empty($docid)) {
        return $response = 'Invalid Input !';
    }
    //$type = ESIGNED_DOCS_BY_ADV;

    $update_data = array('is_data_valid' => FALSE);
    if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
        $breadcrumb_to_remove = NEW_CASE_AFFIRMATION;
    } elseif (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
        $breadcrumb_to_remove = MISC_BREAD_AFFIRMATION;
    } elseif (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
        $breadcrumb_to_remove = IA_BREAD_AFFIRMATION;
    } elseif (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_MENTIONING) {
        $breadcrumb_to_remove = MEN_BREAD_AFFIRMATION;
    } elseif (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_JAIL_PETITION) {
        $breadcrumb_to_remove = JAIL_PETITION_AFFIRMATION;
    }

    $response = $Affirmation_model->reset_affirmation($docid, $registration_id, $update_data, $breadcrumb_to_remove);
    return true;
}

function logged_in_check_user_type($user_type)
{
    //$allowed_users_array = array(USER_ADMIN, USER_EFILING_ADMIN,USER_SUPER_ADMIN, USER_ADMIN_READ_ONLY); //For Admin Portal
    //$allowed_users_array = array(USER_ADVOCATE,USER_IN_PERSON,ARGUING_COUNSEL,SR_ADVOCATE); //For Advocate Portal
    // $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, ARGUING_COUNSEL, SR_ADVOCATE, USER_ADMIN, USER_EFILING_ADMIN, USER_SUPER_ADMIN, USER_DEPARTMENT, USER_CLERK, USER_ADMIN_READ_ONLY, AMICUS_CURIAE_USER); //For Advocate Portal and Admin Portal
    // $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, ARGUING_COUNSEL, SR_ADVOCATE, USER_ADMIN, USER_EFILING_ADMIN, USER_SUPER_ADMIN, USER_DEPARTMENT, USER_ADMIN_READ_ONLY, AMICUS_CURIAE_USER); //For Advocate Portal and Admin Portal

    $allowed_users_array = array(USER_ADVOCATE); //For Advocate Portal
    if (!in_array($user_type, $allowed_users_array)) {
        return true;
    }
    return false;
}

function is_user_status($loginid = null)
{
    if(empty(getSessionData('login'))){
        return response()->redirect(base_url('/'));
        exit(0);
    }
    $request = service('request');
    if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {
        is_clerk_aor($_SESSION['login']['id'],$_SESSION['login']['ref_m_usertype_id'],'Y');
    }
    $time1 = date("H:i");
    /*   $ci = &get_instance();
    $ci->load->model('login/Login_model');
    $ci->load->library('session'); */
    if (!empty($loginid) && $loginid != null) {
        $loginid = $loginid;
    } else {
        $loginid = getSessionData('login')['id'];
    }
    $login_model = new \App\Models\Login\LoginModel();
    $result = $login_model->is_user_status($loginid, getSessionData('login')['ref_m_usertype_id']);
   
    if (!empty($result) && $result != FALSE) {

        // if(isset($result->session_detail) && !empty($result->session_detail)){
        //     $loggedUser_session_detail = json_decode($result->session_detail);
        //     $session_val = $loggedUser_session_detail->ci_session_efiling;
        //     $cookieVal = $loggedUser_session_detail->csrf_cookie_name;
        //     if(isset($result->logout_time) && !empty($result->logout_time)){
        //         return response()->redirect(base_url('logout'));
        //     }
        // }
        // $loggedUser_session_detail = !empty($result->session_detail) ? json_decode($result->session_detail) : '';
        // if (isset($loggedUser_session_detail->ci_session_efiling) && $loggedUser_session_detail->ci_session_efiling === session()->get('ci_session_efiling')) {
        //     $storedUserAgent = $result->user_agent ?? '';
        //     $storedIpAddress = $result->ip_address ?? '';

        //     if ($storedUserAgent !== $request->getUserAgent()->getAgentString() || $storedIpAddress !== $request->getIPAddress()) {
        //         session()->destroy();
        //         return response()->redirect(base_url('logout'));
        //         exit(0);
        //     }
        // }
        $lid = 0;
        if(!empty(getSessionData('login')) && isset(getSessionData('login')['id'])){
            $lid = getSessionData('login')['id'];
        }
        // if (session()->has('user_id')) {
        //     if (
        //         preg_replace('/\s+/', '', session()->get('user_agent')) !== preg_replace('/\s+/', '', $request->getUserAgent()->getAgentString()) ||
        //         session()->get('ip_address') !== $request->getIPAddress()
        //     ) {
        //         return response()->redirect(base_url('logout'));
        //         exit(0);
        //     }
        // }elseif(!session()->has('user_id')){
        //     return response()->redirect(base_url('logout'));
        //     exit(0);
        // }

        // $user_token = $lid.$result->ip_address.getSessionData('login_salt');
        // $session_user_token = getSessionData('usertoken');
        // if($user_token !== $session_user_token){
        //     return response()->redirect(base_url('logout'));
        // }

        // if(isset($result->logout_time) && !empty($result->logout_time) && $result->logout_time != null){
        //     return response()->redirect(base_url('logout'));
        // }
        if (($result->referrer == getSessionData('login')['ref_m_usertype_id']) && ($result->processid != getSessionData('login')['processid'])) {
            return response()->redirect(base_url('logout'));
            exit(0);
        } else {
            return false;
        }
    }
}

// function is_user_status($loginid = null)
// {
//     if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {
//         is_clerk_aor($_SESSION['login']['id'],$_SESSION['login']['ref_m_usertype_id'],'Y');
//     }

//     //return false;
//     $time1 = date("H:i");
//     $login_model = new \App\Models\Login\LoginModel();
    
//     if (!empty($loginid) && $loginid != null) {
//         $loginid = $loginid;
//     } else {
//         $loginid = $_SESSION['login']['id'];
//     }
//     $result = $login_model->is_user_status($loginid, getSessionData('login')['ref_m_usertype_id']);
//     if (!empty($result) && $result != FALSE) {
//         // echo '<pre>_1_'; print_r($result); echo '<br>_2_'; pr($_SESSION['login']);
//         if ($result->referrer == $_SESSION['login']['ref_m_usertype_id'] && $result->processid != $_SESSION['login']['processid']) {
//             return response()->redirect(base_url('logout'));
//         } else {
//             return false;
//         }
//     }
//     // if (($result->referrer == getSessionData('login')['ref_m_usertype_id']) && ($result->processid != getSessionData('login')['processid'])) {
//     //     return response()->redirect(base_url('logout'));
//     // } else {
//     //     return false;
//     // }
// }

function getPendingCourtFee_old_working_till_101072023()
{
    // $ci = &get_instance();
    // $ci->load->model('common/Common_model');
    $Common_model = new CommonModel();
    $court_fee_part1 = calculate_court_fee(null, 1, null, 'O');
    $court_fee_part2 = calculate_court_fee(null, 2, null, null);

    $total_court_fee = (int)$court_fee_part1 + (int)$court_fee_part2;

    $already_paid_payment = $Common_model->get_already_paid_court_fee($_SESSION['efiling_details']['registration_id']);

    if (!empty($already_paid_payment[0]['court_fee_already_paid']))
        $total_pending_court_fee = $total_court_fee - (int)$already_paid_payment[0]['court_fee_already_paid'];
    else
        $total_pending_court_fee = $total_court_fee;

    return $total_pending_court_fee;
}

function getPendingCourtFee()
{
    $Common_model = new CommonModel();
    $registration_id = (!empty(getSessionData('efiling_details')) && isset(getSessionData('efiling_details')['registration_id'])) ? getSessionData('efiling_details')['registration_id'] : '';
    $total_pending_court_fee = 0;
    if (!empty($registration_id))
    {
        $if_sclsc = (!empty(getSessionData('efiling_details')['if_sclsc']) && (getSessionData('efiling_details')['if_sclsc'] == 1)) ? getSessionData('efiling_details')['if_sclsc'] : 0; //Code added for to make 0 court fee for SCLSC cases on 25072023 by Anshu and KBP
        if (!empty(getSessionData('efiling_details')['if_sclsc']) && (getSessionData('efiling_details')['if_sclsc'] == 1))
        {
            $total_pending_court_fee = 0;
        }
        else
        {
            if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) 
            {
                $arr = array();
                $arr['registration_id'] = $registration_id;
                $arr['step'] = 6;
                $caveat_details = $Common_model->getCaveatDataByRegistrationId($arr);
                if (!empty($caveat_details[0]))
                    $case_nature = $caveat_details[0]['nature'];
                // pr($case_nature);
            } else if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_IA || getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS || getSessionData('efiling_details')['ref_m_efiled_type_id'] == OLD_CASES_REFILING)
                 {
                $court_fee_calculation_param3 = $Common_model->get_ia_or_misc_doc_court_fee($registration_id, null, null); // retrieve the court fee
                $case_nature = (!empty($court_fee_calculation_param3)) ? $court_fee_calculation_param3[0]['nature'] : null;
                if (isset($case_nature)&& empty($case_nature)) {
                    // pr($case_nature);
                    $diary_no = (int)$court_fee_calculation_param3[0]['diary_no'] . (int)$court_fee_calculation_param3[0]['diary_year'];
                    $case_nature = file_get_contents(ICMIS_SERVICE_URL . '/ConsumedData/caseNature?diaryNo=' . $diary_no);
                }
            } else {
                $court_fee_calculation_param1 = $Common_model->get_subject_category_casetype_court_fee($registration_id);
                $case_nature = !empty($court_fee_calculation_param1) ? $court_fee_calculation_param1[0]['nature'] : '';
            }
          
            if (( isset($case_nature)  &&  $case_nature == 'C' && getSessionData('efiling_details')['ref_m_efiled_type_id'] != E_FILING_TYPE_CAVEAT && getSessionData('efiling_details')['ref_m_efiled_type_id'] != E_FILING_TYPE_IA) || ( isset($case_nature)  &&    $case_nature == 'C' && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) || ( isset($case_nature)  &&      $case_nature == 'C' && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) || ( isset($case_nature)  &&  $case_nature == 'C' && getSessionData('efiling_details')['ref_m_efiled_type_id'] == OLD_CASES_REFILING)) // CHNAGE BY KBPUJARI ON 10072023 TO MAKE 0 COURT FEE FOR THE CAVEAT FILING IF THE CASE TYPE IS SELECTED AS CRIMINAL
            {

               



                $court_fee_part1 = calculate_court_fee(null, 1, null, 'O');
                $court_fee_part2 = calculate_court_fee(null, 2, null, null);

                $total_court_fee = (int)$court_fee_part1 + (int)$court_fee_part2;

                $already_paid_payment = $Common_model->get_already_paid_court_fee(getSessionData('efiling_details')['registration_id']);

                if (!empty($already_paid_payment[0]['court_fee_already_paid']))
                    $total_pending_court_fee = $total_court_fee - (int)$already_paid_payment[0]['court_fee_already_paid'];
                else
                    $total_pending_court_fee = $total_court_fee;
            } else
                $total_pending_court_fee = 0;
        }
    }
    return $total_pending_court_fee;
}

function send_mail_cron($data)
{
    return true; exit(); // remove this line before going live
    if (!empty($data)) {
        foreach ($data['to_email'] as $val_email) {
            $to_email = $val_email;
            $subject = $data['subject'];
            //$message = $data['message'];
            // $ci = &get_instance();
            // $ci->load->library('email');

            $email = \Config\Services::email();

            //allowed_key=nTcPhj123h for ip=10.25.78.8
            //allowed_key=tNuI321stu for ip=10.40.186.150
            $email_message = (render('templates.email.reports', $data));
            // $email_message = ($this->render('templates.email.reports', $data, true));
            $files = array();
            //$string = base64_encode(json_encode(array("allowed_key" => "tNuI321stu", "sender" => "eService", "mailTo" => $to_email, "subject" => $subject, "message" => $email_message, "files" => $files)));
            $string = base64_encode(json_encode(array("allowed_key" => LIVE_EMAIL_KEY, "sender" => "SCeFM", "mailTo" => $to_email, "subject" => $subject, "message" => $email_message, "files" => $files)));
            $content = http_build_query(array('a' => $string));
            $context = stream_context_create(array('http' => array('method' => 'POST', 'content' => $content,)));
            $json_return = @file_get_contents(NEW_MAIL_SERVER_HOST, true, $context);
            $json2 = json_decode($json_return);
            if ($json2) {
                $response = $json2->status;
                if ($response == 'success') {
                    $result = 'success';
                } else {
                    $result = 'error';
                }
            }
        }
        return $result;
    } //End of foreach loop..
    // $result = 'Data not found';
    // return $result;
}

function get_count_days_FromDate_Todate($from_date, $to_date)
{
    //Assign a date in 'Y-m-d' format
    //$from_date = date('2023-06-20');
    //$to_date = date('2023-07-19');
    $from_date = date('Y-m-d', strtotime($from_date));
    $to_date = date('Y-m-d', strtotime($to_date));
    //Calculate the date difference based on timestamp values
    $difference = strtotime($to_date) - strtotime($from_date);
    //Calculate difference in days
    return $days = abs($difference / (60 * 60) / 24);
    //Print the date difference in days
    //echo "<br/><h3>The difference between ".$from_date." and ".$to_date." is ".$days." days.</h3>";

}

function isRefilingCompulsoryIADefect($reg_id, $current_stage_id)
{
    $Common_model = new CommonModel();
    $session = \Config\Services::session();
    $result_initial = $Common_model->get_intials_defects_remarks($reg_id, $current_stage_id);
    $result_icmis = $Common_model->get_cis_defects_remarks($reg_id, FALSE);
    
    $defects['pdfdefects'] = $Common_model->get_pdf_defects_remarks($reg_id);
    foreach ($result_icmis as $re) {
        $prep_dt = (isset($re['obj_prepare_date']) && !empty($re['obj_prepare_date'])) ? date('Y-m-d', strtotime($re['obj_prepare_date'])) : null;
        $remove_dt = (isset($re['obj_removed_date']) && !empty($re['obj_removed_date'])) ? date('Y-m-d', strtotime($re['obj_removed_date'])) : null;
        $pspdfdocumentid = (isset($re['pspdfkit_document_id']) && !empty($re['pspdfkit_document_id'])) ? $re['pspdfkit_document_id'] : null;
        $aor_cured = (isset($re['aor_cured']) && !empty($re['aor_cured'])) ? $re['aor_cured'] : "f";
        $checked = "";
        $markdefectclass = "";
        if ($aor_cured == "t") {
            $checked = "checked";
            $markdefectclass = "curemarked";
        }
        $from_date = $prep_dt;
        $defect_date_was_more_than28days = 28;
        // New logic for defect cure is written by kbpujari on 30/10/2023 as per defect removal logic provided by Vandana :start
        $defect_removal_max_date = date('Y-m-d', strtotime($from_date . ' + ' . $defect_date_was_more_than28days . ' days'));
        $max_allowable_defect_cured_date = file_get_contents(ICMIS_SERVICE_URL . '/ConsumedData/checkAndGetMaxDateForCuringDefectWhileRefilingTheCase/?allow_defect_remove_max_date=' . $defect_removal_max_date);
        $today_date = date('Y-m-d');
        $maxDate = json_decode($max_allowable_defect_cured_date);
        if ($maxDate->date_for_curing_defect_while_refiling < $today_date) {
            $result = $Common_model->isRefilingCompulsoryIADefect($reg_id, FALSE);
            if ($result != FALSE && !empty($result)) {
                return true;
            } else {
                return false;
            }
        }else{
            return true;
        }
        // if ($maxDate->date_for_curing_defect_while_refiling >= $today_date) {
        //     $max_allowable_defect_cured_date=(!empty($max_allowable_defect_cured_data) ? $maxDate->date_for_curing_defect_while_refiling:null);
        // } else {
        //     //echo "beyond > 28 days  than .$max_allowable_defect_cured_date";exit();
        //     $result = $Common_model->isRefilingCompulsoryIADefect($reg_id, FALSE);
        //     if ($result != FALSE && !empty($result)) {
        //         return true;
        //     } else {
        //         return false;
        //     }
        // }
        // New logic for defect cure is written by kbpujari on 30/10/2023 as per defect removal logic provided by Vandana :end

        //The following old logic for curing defect code commented on 30/11/2023 by kbpujari which is checking only 28 days count
        /*$get_total_days=get_count_days_FromDate_Todate($from_date,$to_date);

        if($get_total_days > $defect_date_was_more_than28days){
            $result = $Common_model->isRefilingCompulsoryIADefect($reg_id, FALSE);
            if($result !=FALSE && !empty($result)){
                return true;
            }else{
                $ci->session->set_flashdata('msg', '<div class="alert alert-danger text-center">This case is defective since more than 28 days so filing of IA i.e. Condonation of delay in refiling application is compulsory.</div>');
                redirect('documentIndex');exit();
            }

        }else{
            return true;
        }*/
    }
}

function addPrefixIfAbsent($number)
{
    if (substr($number, 0, 2) !== '91') {
        return '91' . $number;
    }
    return $number;
}

function send_whatsapp_message($registration_id = null, $efiling_number = null, $sms_text = null)
{
    return true; exit;
    $cause_title='';
    $date='';
    $Common_model = new CommonModel();
    $efiling_num_details = null;
    if (!empty($registration_id)) {
        $efiling_num_details = $Common_model->get_case_details_for_whatsapp_messages($registration_id);
    }
    if (!empty($efiling_num_details)) {
        $cause_title = (!empty($efiling_num_details[0]->cause_title)) ? $efiling_num_details[0]->cause_title : null;
        $date = (!empty($efiling_num_details[0]->filing_date)) ? $efiling_num_details[0]->filing_date : null;
        $aor_mobile_number = (!empty($efiling_num_details[0]->aor_mobile_number)) ? $efiling_num_details[0]->aor_mobile_number : null;
        $user_first_name = (!empty($efiling_num_details[0]->first_name)) ? $efiling_num_details[0]->first_name : null;
        $user_login_id = (!empty($efiling_num_details[0]->login_id)) ? $efiling_num_details[0]->login_id : null;
        $user_employee_code = (!empty($efiling_num_details[0]->employee_code)) ? $efiling_num_details[0]->employee_code : null;
    }

    $efiling_no = 'Efiling no.' . $efiling_number;
    $status = $sms_text;

    $sms_params = array($cause_title, $efiling_no, $date, $status);
    $purpose = 'Registration';
    $module = 'SCeFM';
    $templateCode = "sc_efm::case::status";
    $purpose = 'Registration';
    $module = 'SCeFM';
    $templateCode = "sc_efm::case::status";
    //$wh_mobileno = array("919871922703","919810003580","919540028941","919881397172","918763332660","919630100950","919987508833","919341218677","918813888057");
    // $wh_mobileno = array("919711475023", "918586850444", "919891713636", "919525555516");
    $wh_mobileno = array("916260380102", "919450523639", "918788735225", "919525555516");

    //un-comment the following two lines in the production on the event of go-live!!!!
    /*
        $extra_mobile_number_with_prefix = (!empty($aor_mobile_number)) ? addPrefixIfAbsent($aor_mobile_number) : null;
        $wh_mobileno[] = $extra_mobile_number_with_prefix;
    */

    $created_by_user = array(
        "name" => (!empty($_SESSION['login']['first_name'])) ? $_SESSION['login']['first_name'] : $user_first_name,
        "id" => (!empty($_SESSION['login']['id'])) ? $_SESSION['login']['id'] : $user_login_id,
        "employeeCode" => (!empty($_SESSION['login']['userid'])) ? $_SESSION['login']['userid'] : $user_employee_code,
        "organizationName" => 'AOR'
    );
    $response = send_sms_whatsapp_through_uni_notify(1, $wh_mobileno, $templateCode, $sms_params, null, $purpose, $created_by_user, $module, 'SCeFM', null, null);
}

function send_whatsapp_message_test($registration_id = null, $efiling_number = null, $sms_text = null)
{
    $Common_model = new CommonModel();
    $cause_title = 'Unknown Case';
    $date = date('Y-m-d');
    $efiling_no = 'Efiling no. Unknown';
    $status = 'Unknown Status';

    if (!empty($registration_id)) {
        $efiling_num_details = $Common_model->get_case_details_for_whatsapp_messages($registration_id);
        if (!empty($efiling_num_details) && isset($efiling_num_details[0])) {
            $cause_title = !empty($efiling_num_details[0]['cause_title']) ? $efiling_num_details[0]['cause_title'] : 'Unknown Case';
            $date = !empty($efiling_num_details[0]['filing_date']) ? $efiling_num_details[0]['filing_date'] : date('Y-m-d');
        }
    }

    $efiling_no = !empty($efiling_number) ? 'Efiling no.' . $efiling_number : 'Efiling no. Unknown';
    $status = !empty($sms_text) ? $sms_text : 'Pending'; // Fallback if $sms_text is empty

    $sms_params = array($cause_title, $efiling_no, $date, $status);
    $purpose = 'Registration';
    $module = 'SCeFM';
    $templateCode = "sc_efm::case::status";
    $wh_mobileno = array("916260380102");
    $created_by_user = array("name" => "Admin", "id" => '99', "employeeCode" => '999', "organizationName" => 'AOR');

    // Debug the params
    var_dump($sms_params);

    $response = send_sms_whatsapp_through_uni_notify(1, $wh_mobileno, $templateCode, $sms_params, null, $purpose, $created_by_user, $module, 'SCeFM', null, null);

    pr($response);
}

function send_sms_whatsapp_through_uni_notify($api_type = null, $mobile_nos = [], $templateCode = null, $sms_params = [], $scheduledAt = null, $purpose = null, $created_by_user = [], $module = null, $project = null, $file_name = null, $file_url = null)
{
    $sms_request_filters = [
        "providerCode" => "wa",
        "recipients" => [
            "mobileNumbers" => $mobile_nos
        ],
        "templateCode" => $templateCode,
        "templateVariables" => $sms_params,
        "scheduledAt" => $scheduledAt,
        "purpose" => $purpose,
        "createdByUser" => $created_by_user,
        "module" => $module,
        "project" => $project
    ];

    if ($api_type == 2) {
        $fileArray = [
            "files" => [
                [
                    "name" => $file_name,
                    "url" => $file_url
                ]
            ]
        ];
        array_push($sms_request_filters, $fileArray);
    }
    //echo json_encode($sms_request_filters);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://10.25.78.111:36521/api/v1/send', //production
        // CURLOPT_URL => 'http://10.25.78.70:36521/api/v1/send',  //for staging
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('data' => json_encode($sms_request_filters)),
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer jnoindi2kl30smn0vn2mao03z'
            // 'Authorization: Bearer 65fd91a2fc44c5e6eec78e03'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    log_message('info','w app msg sent');
    return $response;
}

function is_vacation_advance_list_duration()
{
    if (!empty($loginid) && $loginid != null) {
        $loginid = $loginid;
    } else{
        $loginid = getSessionData('login')['id'];
    }
    $Vacation_advance_model = new \App\Models\Vacation\VacationAdvanceModel();
    $is_vacation_advance_list_duration = $Vacation_advance_model->is_vacation_advance_list_duration();
    return $is_vacation_advance_list_duration;
}

// function is_vacation_advance_list_duration_m()
// {
//     // $ci = &get_instance();
//     // $ci->load->model('vacation/Vacation_advance_model_m');
//     // $ci->load->library('session');
//     $Vacation_advance_model_m = new VacationAdvanceModel();
//     $is_vacation_advance_list_duration = $Vacation_advance_model_m->is_vacation_advance_list_duration();
//     return $is_vacation_advance_list_duration;
// }

function getClientIP()
{
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function idEncryptionCustom($id) {
    $key = openssl_random_pseudo_bytes(32); // Generate a 256-bit key for AES-256 encryption
    $key = bin2hex($key);
    $method = "AES-256-CBC";
    $key = "supremeCourtCI4KeyForEncryption";
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
    $encryptedID = openssl_encrypt($id, $method, $key, 0, $iv);
   /*  $decryptedID1 = openssl_decrypt($encryptedID, $method, $key, 0, $iv);
    echo $decryptedID1; die; */
    return $encryptedID;
}

// function idDecryptionCustom($encryptedID) {
//     $key2 = openssl_random_pseudo_bytes(32); // Generate a 256-bit key for AES-256 encryption
//     $key2 = bin2hex($key2);
//     $method = "AES-256-CBC";
//     //$key2 = "supremeCourtCI4KeyForDecryption"; // Don't hardcode, use the generated key
//     $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
//     $decrypted = openssl_decrypt($encryptedID, $method, $key2, 0, $iv);
//     //echo $decrypted; die;
//     return $decrypted;
// }

function integerEncreption($id)
{
    $encrypter = \Config\Services::encrypter();
    $userIDString = $id;
    $encryptedID = $encrypter->encrypt($userIDString);
    return $encryptedID;
}

function integerDecreption($id)
{
    $encrypter = \Config\Services::encrypter();
    $decryptedID = $encrypter->decrypt($id);
    $decryptedID = (int) $decryptedID;
    return $decryptedID;
}

function stringEncreption($id)
{
    // $encrypter = \Config\Services::encrypter();
    // $encryptedID = $encrypter->encrypt($id);
    // return $encryptedID;
    $encrypter = \Config\Services::encrypter();
    $enc_id=bin2hex($encrypter->encrypt($id));
    return $enc_id;
}

function stringDecreption($id)
{
    // $encrypter = \Config\Services::encrypter();
    // $decryptedID = $encrypter->decrypt($id);
    // $decryptedID = $decryptedID;
    // return $decryptedID;
    $encrypter = \Config\Services::encrypter();
    $dec_id=$encrypter->decrypt(hex2bin($id));
    return $dec_id;
}

function iEncrypt($id){
    $key = config('Encryption')->key;
    $encrypted = encrypt($id, $key);
    return $encrypted;
}
function iDecrypt($id){
    $key = config('Encryption')->key;
    $encrypted = decrypt($id, $key);
    return $encrypted;
}

function encrypt($data, $key) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decrypt($data, $key) {
    list($encryptedData, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encryptedData, 'aes-256-cbc', $key, 0, $iv);
}

function render($view, $data = [])
{
    $views = APPPATH . 'Views';
    $cache = WRITEPATH . 'cache';

    if (ENVIRONMENT === 'production') {
        $templateEngine = new BladeOne(
            $views,
            $cache,
            BladeOne::MODE_AUTO
        );
    } else {
        $templateEngine = new BladeOne(
            $views,
            $cache,
            BladeOne::MODE_DEBUG
        );
    }

    $templateEngine->pipeEnable = true;
    $templateEngine->setBaseUrl(base_url());
    $views = APPPATH . 'Views'; // Path to your views directory
    $cache = WRITEPATH . 'cache'; // Path to your cache directory

    $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

    echo $blade->run($view, $data);
}

if (!function_exists('getEfilingCivilDetails')) {
    function getEfilingCivilDetails($registration_id)
    {
        $ViewModel = new ViewModel();
        $efiling_civil_data = $ViewModel->get_efiling_civil_details($registration_id);
        // pr($efiling_civil_data);
        return $efiling_civil_data;
    }
}

function message_show($type, $msg) {
    if ($type == 'fail') {
        $preTable = "<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp;$msg    <span class='close' onclick=hideMessageDiv()>X</span></p>";
    } elseif ($type == 'success') {
        $preTable = "<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp;$msg  <span class='close' onclick=hideMessageDiv()>X</span></p>";
    }
    return $preTable;
}

function article_tracking_offline($articlenumber){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->articleTrackingOffline($articlenumber);
    //return $rate;
    return $result;
}

function getCopySearchResult($row){
    
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->getCopySearchResult($row);
    return $result;
}

function getCopyStatusResult($row,$asset_type_flag){
    
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->getCopyStatusResult($row,$asset_type_flag);
    return $result;
}

function getCopyBarcode($row){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->getCopyBarcode($row['id']);
    return $result; 
}

function getCopyApplication($row){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->getCopyApplication($row['id']);
    return $result;
}

function getCopyRequest($row){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->getCopyRequest($row['id']);
    return $result;

}

function copyFormSentOn($row){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->copyFormSentOn($row['id']);
    return $result;
}

function eCopyingGetDiaryNo($ct, $cn, $cy){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->eCopyingGetDiaryNo($ct,$cn,$cy);
    
    return $result;   
}

function eCopyingCheckDiaryNo($ct, $cn, $cy){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->eCopyingCheckDiaryNo($ct,$cn,$cy);
    return $result;
}

function eCopyingGetFileDetails($diary_no){
    

    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->eCopyingGetFileDetails($diary_no);
    return $result;
}

function getStatementVideo($mobile, $email){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->getStatementVideo($mobile, $email);
    return $result;
}

function getStatementImage($mobile, $email){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->getStatementImage($mobile, $email);
    return $result;
}

function getStatementIdProof($mobile, $email){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->getStatementIdProof($mobile, $email);
    return $result;
}

function eCopyingStatementCheck($mobile, $email){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->eCopyingStatementCheck($mobile, $email);
    return $result;
}


function eCopyingCheckMaxDigitalRequest($mobile, $email){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->eCopyingCheckMaxDigitalRequest($mobile, $email);
    return $result;
}

function eCopyingCopyStatus($diary_no, $check_asset_type, $mobile, $email){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->eCopyingCopyStatus($diary_no, $check_asset_type, $mobile, $email);
    return $result;
}

function eCopyingGetBar($diary_no, $mobile){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->eCopyingGetBar($diary_no, $mobile);
    return $result;
}

function getBailApplied($diary_no, $mobile, $email)
{
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->getBailApplied($diary_no, $mobile, $email);
    return $result;
}

function eCopyingGetCopyDetails($condition, $third_party_sub_qry, $OLD_ROP){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->eCopyingGetCopyDetails($condition, $third_party_sub_qry, $OLD_ROP);
    
    return $result;
}

function eCopyingGetGroupConcat($main_case){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->eCopyingGetGroupConcat($main_case);
    return $result;
}

function getIsPreviuslyApplied($copy_category, $diary_no, $mobile, $email, $order_type, $order_date)
{
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->getIsPreviuslyApplied($copy_category, $diary_no, $mobile, $email, $order_type, $order_date);
    return $result;   
}

function getUserAddress($mobile, $email)
{
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->getUserAddress($mobile, $email);
    return $result;
}

function eCopyingOtpVerification($email){
    
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->eCopyingOtpVerification($email);
    return $result;
}

function eCopyingGetBarDetails($bar_id){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->eCopyingGetBarDetails($bar_id);
    return $result;
}
 
function eCopyingGetCopyCategory(){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->getCategory();
    return $result;
}

function copying_weight_calculator($total_pages,$total_red_wrappers){
    $weight = 0;
    if($total_pages >= 1 and $total_pages <=5){
        //envelop no. 5 & addtional 1 gram for glue/pinup and barcode sticker
        $weight = 3 + 1;
    }
    else if($total_pages >= 6 and $total_pages <=8){
        //envelop no. 6 & addtional 2 gram for glue/pinup and barcode sticker
        $weight = 6 + 2;
    }
    else if($total_pages >= 9 and $total_pages <=10){
        //envelop no. 7 & addtional 3 gram for glue/pinup and barcode sticker
        $weight = 12 + 3;
    }
    else if($total_pages >= 11 and $total_pages <=20){
        //envelop no. A4 & addtional 4 gram for glue/pinup and barcode sticker
        $weight = 20 + 4;
    }
    else if($total_pages >= 21 and $total_pages <=500){
        //envelop no. 8 & addtional 5 gram for glue/pinup and barcode sticker
        $weight = 35 + 5;
    }
    else{
        //envelop no. 8 for above 500 pages & addtional 5 gram for glue/pinup and barcode sticker
        $additional_weight_times = ceil($total_pages / 500);
        $weight = (35+5) * $additional_weight_times;
    }
    //75 gsm page equal to 4 gram and wrap has 2 gram of weight
    $weight += ($total_pages * 4) + ($total_red_wrappers * 2);
    return $weight;
}

function speed_post_tariff_calc_online($weight,$desitnation_pincode){
    $myObj = (object)[];
    $myObj->service = "SP";
    $myObj->sourcepin = 110001;
    $myObj->destinationpin = $desitnation_pincode;
    $myObj->weight = $weight;
    $myObj->length = "0";
    $myObj->breadth = "0";
    $myObj->height = "0";
    
    //$url = "http://data.cept.gov.in/dop/api/values/gettariff";    
    $url = CEPT_GOV_IN;
    //$url = "https://uat.cept.gov.in/tariff/api/values/gettariff";
     
    $content = json_encode($myObj);
    
    
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //Tell cURL that it should only spend 10 seconds
    //trying to connect to the URL in question.
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    //A given cURL operation should only take
    //30 seconds max.
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
     
    
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
    
    $json_response = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
   
    if($status == 200 || $status == 201) {    
        $response_array = json_decode($json_response);  
        $response = json_encode($response_array[0]);      
    }
    else if($status == 0){
        //$error_type = "Network Issue";
        $response = json_encode(speed_post_tariff_calc_offline($weight,$desitnation_pincode)); //json_encode(array("Validation Status" => $error_type));
    }
    else{
        $error_type = "Network Issue";
        $response = json_encode(array("Validation Status" => $error_type));    
    }
    curl_close($curl);

    return $response;
}

function speed_post_tariff_calc_offline($weight,$desitnation_pincode){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->speedPostTariffCalcOffline($weight,$desitnation_pincode);
    return $result;
    
}

function eCopyingAvailableAllowedRequests($mobile, $email){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->eCopyingAvailableAllowedRequests($mobile,$email);
    return $result;
}

function eCopyingGetDocumentType($third_party_sub_qry){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->eCopyingGetDocumentType($third_party_sub_qry);
    return $result;  
}

function createCRN($service_user_id){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->createCRN($service_user_id);
    return $result;
}

function insert_copying_application_online($dataArray){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->insert_copying_application_online($dataArray);
    return $result;
}

function insert_copying_application_documents_online($dataArray) {
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->insert_copying_application_documents_online($dataArray);
    return $result;
}

function sci_send_sms($mobile,$cnt,$from_adr,$template_id) {
    $status = '';
    $ecoping_webservices=new Ecoping_webservices();
    $db2 = Database::connect('e_services'); // Connect to the 'e_services' database
    if(empty($mobile)) {
        $status = " Mobile No. Empty.";
    } else if(empty($cnt)) {
        $status = " Message content Empty.";
    } else if(strlen($cnt) > 320) {
        $status = " Message length should be less than 320 characters.";
    } else if(empty($from_adr)) {
        $status = " Sender Information Empty";
    } else if(strlen($mobile) != '10') {
        $status = " Not a Proper Mobile No.";
    } else if(!is_numeric($mobile)) {
        $status = "Mobile number contains invalid value.";
    } else{
        $url = SCISMS_URL;
        $post_fields = [
            "providerCode" => "sms",
            "recipients" => [
                "mobileNumbers" => [
                    "$mobile"
                ]
            ],
            "templateCode" => "$template_id",
            "body" => "$cnt",
            "scheduledAt" => null,
            "purpose" => "SCI-eCopying",
            "createdByUser" => [
                "id" => "44562",
                "name" => "Abhishek",
                "employeeCode" => "564863",
                "organizationName" => "SSK Infotech"
            ],            
            "module" => "listing",
            "project" => "icmis"
        ];
        $fields_string = http_build_query($post_fields);
        //open connection
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json',
            'Authorization: Bearer sdfmsdbfjh327654t3ufb58'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
        //execute post
        $result = curl_exec($ch);
        $json = json_decode($result);
        if(empty($json->errors)){
            $dataArr = array(
                "mobile" => $mobile,
                "msg" => trim($cnt),
                "table_name" => trim($from_adr),
                "c_status" => 'Y',
                "ent_time" => date('Y-m-d H:i:s'),
                "update_time" => date('Y-m-d H:i:s'),
                "template_id" => trim($template_id)
            );
            $result=$ecoping_webservices->saveSMSData($dataArr);
            //$builder = $db2->table('sms_pool');
            if ($result) {
                $status = 'success';
            } else{
                $status = 'Error:Unable to Insert Records';
            }
        } else{
            $status = "Error:Try again later";
        }
    }
    return json_encode(array("Status" => $status));
}

function eCopyingGetCasetoryById($id) {
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->eCopyingGetCasetoryById($id);
    // $builder->where('to_date', '0000-00-00');
    //$query = $builder->get();
    return $result;
}

function insert_user_assets($dataArray) {
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->insert_user_assets($dataArray);
    if ($result) {
        $status = 'success';
    } else{
        $status = 'Error:Unable to Insert Records';
    }
    return json_encode(array("Status" => $status));
}

function bharatKoshRequest($reqeust)
{
    //$xml = new SimpleXMLElement('<BharatKoshPayment DepartmentCode="22" Version="1.0"/>'); //uat server
    $xml = new SimpleXMLElement('<BharatKoshPayment DepartmentCode="022" Version="1.0"/>');//production server
    // $xml = new SimpleXMLElement('<BharatKoshPayment DepartmentCode='.DEPARTMENT_CODE_LIVE.' Version="1.0"/>');//production server

    $submit = $xml->addChild('Submit');
    $order_batch = $submit->addChild('OrderBatch');
    $order_batch->addAttribute('TotalAmount', "$reqeust[OrderBatchTotalAmount]");
    $order_batch->addAttribute('Transactions', "$reqeust[OrderBatchTransactions]");
    //$order_batch->addAttribute('Transactions', "2");
    $order_batch->addAttribute('merchantBatchCode', "$reqeust[OrderBatchMerchantBatchCode]");

    if($reqeust['OrderBatchTransactions'] == 1){
    $order = $order_batch->addChild('Order');
    $order->addAttribute('InstallationId', "$reqeust[InstallationId]");//given by pfms is unique for sci
    //$order->addAttribute('OrderCode', "$reqeust[OrderBatchMerchantBatchCode]");
    $order->addAttribute('OrderCode', "$reqeust[OrderBatchMerchantBatchCode]");

    $cart = $order->addChild('CartDetails');
    $cart->addChild('Description', "$reqeust[CartDescription]");
    //$cart->addChild('Description');
    //$cart->addChild('Amount CurrencyCode="INR" exponent="0" value="1"');
    $cart->addChild('Amount CurrencyCode="INR" exponent="2" value="' . $reqeust['OrderBatchTotalAmount'] . '"');
    $cart->addChild('OrderContent', "$reqeust[OrderContent]"); //also knows purposeId //OrderContent different for each head
    $cart->addChild('PaymentTypeId', "$reqeust[PaymentTypeId]");
    $cart->addChild('PAOCode', "$reqeust[PAOCode]");
    $cart->addChild('DDOCode', "$reqeust[DDOCode]");

    //repeating code
    $pay_method_mask = $order->addChild('PaymentMethodMask');
    $pay_method_mask->addChild('Include Code="'.$reqeust['PaymentMethodMode'].'"');

    $shopper = $order->addChild('Shopper');
    $shopper->addChild('ShopperEmailAddress', "$reqeust[ShopperEmailAddress]");
    $shopper->addChild('ShopperEmailAddress');

    $shipping = $order->addChild('ShippingAddress');
    $shipping_address = $shipping->addChild('Address');
    $shipping_address->addChild('FirstName', "$reqeust[ShippingFirstName]");
    $shipping_address->addChild('LastName', "$reqeust[ShippingLastName]");
    $shipping_address->addChild('Address1', "$reqeust[ShippingAddress1]");
    $shipping_address->addChild('Address2', "$reqeust[ShippingAddress2]");

    $shipping_address->addChild('PostalCode', "$reqeust[ShippingPostalCode]");
    $shipping_address->addChild('City', "$reqeust[ShippingCity]");
    $shipping_address->addChild('StateRegion', "$reqeust[ShippingStateRegion]");
    $shipping_address->addChild('State', "$reqeust[ShippingState]");
    $shipping_address->addChild('CountryCode', "$reqeust[ShippingCountryCode]");
    $shipping_address->addChild('MobileNumber', "$reqeust[ShippingMobileNumber]");

    $billing = $order->addChild('BillingAddress');
    $billing_address = $billing->addChild('Address');
    $billing_address->addChild('FirstName', "$reqeust[BillingFirstName]");
    $billing_address->addChild('LastName', "$reqeust[BillingLastName]");
    $billing_address->addChild('Address1', "$reqeust[BillingAddress1]");
    $billing_address->addChild('Address2', "$reqeust[BillingAddress2]");
    $billing_address->addChild('PostalCode', "$reqeust[BillingPostalCode]");
    $billing_address->addChild('City', "$reqeust[BillingCity]");
    $billing_address->addChild('StateRegion', "$reqeust[BillingStateRegion]");
    $billing_address->addChild('State', "$reqeust[BillingState]");
    $billing_address->addChild('CountryCode', "$reqeust[BillingCountryCode]");
    $billing_address->addChild('MobileNumber', "$reqeust[BillingMobileNumber]");

    $order->addChild('StatementNarrative');
    }
    else{
        $multiHeadArray = $reqeust['MultiHeadArray'];
       // var_dump($reqeust[MultiHeadArray]);
        for($k=0;$k<$reqeust['OrderBatchTransactions'];$k++){

//               echo $multiHeadArray[$k]['ChildCartDescription'];

                $ChildAmount = $multiHeadArray[$k]['ChildAmount'];
                $ChildOrderCode = $multiHeadArray[$k]['ChildOrderCode'];
                $ChildCartDescription = $multiHeadArray[$k]['ChildCartDescription'];
                $ChildOrderContent = $multiHeadArray[$k]['ChildOrderContent'];
                $ChildPaymentTypeId = $multiHeadArray[$k]['ChildPaymentTypeId'];

                $order = $order_batch->addChild('Order');

                $order->addAttribute('InstallationId', "$reqeust[InstallationId]");//given by loba to pfms
                if($k==0){
                $order->addAttribute('OrderCode', $reqeust['OrderBatchMerchantBatchCode']);
                }
                else{
                    $order->addAttribute('OrderCode', $ChildOrderCode);
                }
                $cart = $order->addChild('CartDetails');
                $cart->addChild('Description', "$ChildCartDescription");
                //$cart->addChild('Description');
                $cart->addChild('Amount CurrencyCode="INR" exponent="2" value="' . $ChildAmount . '"');
                $cart->addChild('OrderContent', "$ChildOrderContent"); //also knows purposeId //OrderContent different for each head
                $cart->addChild('PaymentTypeId', "$ChildPaymentTypeId");



                $cart->addChild('PAOCode', "$reqeust[PAOCode]");
                $cart->addChild('DDOCode', "$reqeust[DDOCode]");


                $pay_method_mask = $order->addChild('PaymentMethodMask');
                $pay_method_mask->addChild('Include Code="'.$reqeust['PaymentMethodMode'].'"');

                $shopper = $order->addChild('Shopper');
                $shopper->addChild('ShopperEmailAddress', "$reqeust[ShopperEmailAddress]");
                $shopper->addChild('ShopperEmailAddress');

                $shippingFirstName = str_replace(['&', '.', '-', '/'], ' ',$reqeust['ShippingFirstName']);
                $shipping = $order->addChild('ShippingAddress');
                $shipping_address = $shipping->addChild('Address');
                $shipping_address->addChild('FirstName', "$shippingFirstName");
                $shipping_address->addChild('LastName', "$reqeust[ShippingLastName]");
                $shipping_address->addChild('Address1', "$reqeust[ShippingAddress1]");
                $shipping_address->addChild('Address2', "$reqeust[ShippingAddress2]");

                $shipping_address->addChild('PostalCode', "$reqeust[ShippingPostalCode]");
                $shipping_address->addChild('City', "$reqeust[ShippingCity]");
                $shipping_address->addChild('StateRegion', "$reqeust[ShippingStateRegion]");
                $shipping_address->addChild('State', "$reqeust[ShippingState]");
                $shipping_address->addChild('CountryCode', "$reqeust[ShippingCountryCode]");
                $shipping_address->addChild('MobileNumber', "$reqeust[ShippingMobileNumber]");

                $billing = $order->addChild('BillingAddress');
                $billing_address = $billing->addChild('Address');
                $billing_address->addChild('FirstName', "$reqeust[BillingFirstName]");
                $billing_address->addChild('LastName', "$reqeust[BillingLastName]");
                $billing_address->addChild('Address1', "$reqeust[BillingAddress1]");
                $billing_address->addChild('Address2', "$reqeust[BillingAddress2]");
                $billing_address->addChild('PostalCode', "$reqeust[BillingPostalCode]");
                $billing_address->addChild('City', "$reqeust[BillingCity]");
                $billing_address->addChild('StateRegion', "$reqeust[BillingStateRegion]");
                $billing_address->addChild('State', "$reqeust[BillingState]");
                $billing_address->addChild('CountryCode', "$reqeust[BillingCountryCode]");
                $billing_address->addChild('MobileNumber', "$reqeust[BillingMobileNumber]");

                $order->addChild('StatementNarrative');
        }
    }


















    $xmlString = $xml->asXML();

    // Load the XML to be signed
    $doc = new DOMDocument('1.0', 'utf-8');
    $doc->encoding = 'utf-8';
    $doc->formatOutput = false;
    $doc->preserveWhiteSpace = true;
    $doc->loadXML($xmlString);
// Create a new Security object
    $objDSig = new XMLSecurityDSig("");
// Use the c14n exclusive canonicalization
    $objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);
//$options['prefix'] = '';
//$options['prefix_ns'] = '';
    $options['force_uri'] = TRUE;
//$options['id_name'] = 'ID';
// Sign using SHA1
    $objDSig->addReference($doc, XMLSecurityDSig::SHA1, array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'), $options);
// Create a new (private) Security key
    $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type' => 'private'));
//If key has a passphrase, set it using
    //$objKey->passphrase = 'Savan@#2020';
    $objKey->passphrase = '12345678';
// Load the private key
    //$objKey->loadKey('privatekey_10082020.pem', TRUE);
    $objKey->loadKey('cert-sci.pem', TRUE);

// Sign the XML file
    $objDSig->sign($objKey);
// Add the associated public key to the signature
    $objDSig->add509Cert(file_get_contents('cert-sci.pem'), true, false, array('issuerSerial' => true));
    //$objDSig->add509Cert(file_get_contents('publiccert_10082020.pem'), true, false, array('issuerSerial' => true));
// Append the signature to the XML
    $objDSig->appendSignature($doc->documentElement);
// Save the signed XML
    $signedXML = $doc->saveXML();
    return $signedXML_encode64 = base64_encode($signedXML);
}
function bharatKoshResponse($responseXML)
{
    $doc = new DOMDocument('1.0', 'utf-8');
//foreach ($arTests AS $testName=>$testFile) {
    $doc->encoding = 'utf-8';
    $doc->formatOutput = false;
    $doc->preserveWhiteSpace = true;
    $doc->loadXML($responseXML);
//$doc->load("$xmlString");
    $objXMLSecDSig = new XMLSecurityDSig();

    $objDSig = $objXMLSecDSig->locateSignature($doc);
    if (! $objDSig) {
        throw new Exception("Cannot locate Signature Node");
    }
    $objXMLSecDSig->canonicalizeSignedInfo();
    $objXMLSecDSig->idKeys = array('wsu:Id');
    $objXMLSecDSig->idNS = array('wsu'=>'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd');

    $retVal = $objXMLSecDSig->validateReference();

    if (! $retVal) {
        throw new Exception("Reference Validation Failed");
    }

    $objKey = $objXMLSecDSig->locateKey();
    if (! $objKey ) {
        throw new Exception("We have no idea about the key");
    }
    $key = NULL;

    $objKeyInfo = XMLSecEnc::staticLocateKeyInfo($objKey, $objDSig);

    if (! $objKeyInfo->key && empty($key)) {
        $objKey->loadKey('PFMS.pem', TRUE);
    }

    //print $testName.": ";
    if ($objXMLSecDSig->verify($objKey) === 1) {
        return "Signature validated";
    } else {
        return "Failure";
    }
}

function bharaKoshDataServiceRequest($data){
    
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->bharatKoshRequest($data);
    return $result;
}
function bharaKoshDataBatchServiceRequest($data){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->BharatKoshBatchRequest($data);
    return $result; 
}
function encrypt_doc_id($doc_id) {
    $doc_parameter = $doc_id . '|1';
    $aes = new Crypt_AES();
    $secret = base64_decode(env('SBI_PAYMENT_DOUBLE_VARIFICATION_SECRET_KEY'));
    $aes->setKey($secret);
    $encrypted_doc_id = base64_encode($aes->encrypt($doc_parameter));
    $encrypted_doc_id = str_replace('/', '-', $encrypted_doc_id);
    $encrypted_doc_id = str_replace('=', ':', $encrypted_doc_id);
    $encrypted_doc_id = str_replace('+', '.', $encrypted_doc_id);
    return $encrypted_doc_id;
}

if (!function_exists('isJSON')) {
    function isJSON($string){
        return (is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE)) ? true : false;
    }
}

function remark_preview_ia_docs($reg_id, $current_stage_id)
{
    // echo "Registration: ".$reg_id. 'and current stage:'. $current_stage_id;
    // $ci = &get_instance();
    // $ci->load->model('common/Common_model');
    // $ci->load->library('session');
    $msg = '';
    $Common_model = new \App\Models\Common\CommonModel;
    $result_initial = $Common_model->get_ia_docs_intials_defects_remarks($reg_id, $current_stage_id);
    $result_icmis = $Common_model->get_ia_docs_cis_defects_remarks($reg_id, FALSE);
    $defects['pdfdefects'] = $Common_model->get_ia_docs_pdf_defects_remarks($reg_id);
    if (isset($result_icmis) && !empty($result_icmis)) {
        $total_aor_cured=sizeof($result_icmis); $acr=0;$checkedAll='';
        foreach ($result_icmis as $row) { $is_aor_cured = (isset($row->aor_cured) && !empty($row->aor_cured)) ? $row->aor_cured : "f";
            if ($is_aor_cured == "t") {  $acr++;  }
        }
        if ($total_aor_cured == $acr) { $checkedAll = "checked"; }
        $msg = '<div class="alert table-responsive-sm">';
        $msg .= '<table id="datatable-defects" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" >'
            . '<thead class="success"><th>Mark Cured<br/><input type="checkbox" id="checkAll" '.$checkedAll.'></th><th>#</th><th>Defect Description</th><th>Defect Remark</th><th>Prepare Dt.</th><th>Remove Dt.</th><th>Index Title</th><th>Defective Page No.</th></thead>'
            . '<tbody style="border-color: #ebccd1;background-color: #f2dede;color: #a94442;">';
        $i = 1;
        foreach ($result_icmis as $re) {
            $prep_dt = (isset($re->obj_prepare_date) && !empty($re->obj_prepare_date)) ? date('d-M-Y H:i:s', strtotime($re->obj_prepare_date)) : null;
            $remove_dt = (isset($re->obj_removed_date) && !empty($re->obj_removed_date)) ? date('d-M-Y H:i:s', strtotime($re->obj_removed_date)) : null;
            $pspdfdocumentid = (isset($re->pspdfkit_document_id) && !empty($re->pspdfkit_document_id)) ? $re->pspdfkit_document_id : null;
            $aor_cured = (isset($re->aor_cured) && !empty($re->aor_cured)) ? $re->aor_cured : "f";
            $checked = "";
            $markdefectclass = "";
            if ($aor_cured == "t") {
                $checked = "checked";
                $markdefectclass = "curemarked";
            }
            $link_url = base_url('documentIndex?pspdfkitdocumentid=' . $pspdfdocumentid . '&tobemodifiedpagesraw=');
            $tobemodifiedpagesdisplay = (isset($re->to_be_modified_pspdfkit_document_pages_raw) && !empty($re->to_be_modified_pspdfkit_document_pages_raw)) ? $re->to_be_modified_pspdfkit_document_pages_raw : null;
            $pdf_title = '';
            $pdf_pages_total = '';
            if ($pspdfdocumentid != null) {
                $pdf_info = $Common_model->getPdfInfo($pspdfdocumentid);
                if (!empty($pdf_info)) {
                    $pdf_title = $pdf_info->doc_title;
                    $pdf_pages_total = $pdf_info->page_no;
                }
            }
            $tobemodifiedpagesdisplaytext = '<span title="sequence no.s of (Total Pages)">' . $tobemodifiedpagesdisplay . ' of (' . $pdf_pages_total . '-pages)' . '</span>';
            $tobemodifiedpagesdisplaytextdisplay = (!empty($tobemodifiedpagesdisplay)) ? $tobemodifiedpagesdisplaytext : "";
            $msg .= '<tr id="row' . $re->id . '" class="' . $markdefectclass . '  setCuredDefectAllToggle">';
            $msg .= '<td>' . '<input type="checkbox" ' . $checked . ' id="' . $re->id . '" onchange="setCuredDefect(this.id)"  name="setCuredDefectAll[]" class="setCuredDefectAll" value="'.$re->id.'">' . '</td>';
            $msg .= '<td>' . $i++ . '</td>';
            $msg .= '<td>' . escape_data($re->objdesc) . '</td>';
            $msg .= '<td>' . escape_data($re->remarks) . '</td>';
            $msg .= '<td>' . escape_data($prep_dt) . '</td>';
            $msg .= '<td>' . escape_data($remove_dt) . '</td>';
            $msg .= '<td>' . '<a href="' . $link_url . '">' . $pdf_title . '</span></a>' . '</td>';
            $msg .= '<td>' . $tobemodifiedpagesdisplaytextdisplay . '</td>';
            $msg .= '</tr>';
        }
        $msg .= '</tbody></table>';
        $msg .= '</div>';
        if (!empty($defects['pdfdefects'])) {
            $connecter = '';
            $msg .= '<div>';
            $msg .= "<b>NOTE- You also have pdf defects mentioned in ";
            foreach ($defects['pdfdefects'] as $key => $val) {
                $msg .= $connecter . "1 file on pageno- " . implode(",", $val);
                $connecter = " AND ";
            }
            $msg .= '</b>';
            $msg .= '</div>';
        }
        $msg .= '<script type="text/javascript">';
        $msg .= '$(document).ready(function () {';
        $msg .= 'var oTable = $("#datatable-defects").dataTable();';
        $msg .= '$("#checkAll").click(function() {';
        $msg .= 'var isChecked = $(this).prop("checked");';
        $msg .= 'oTable.$("input[type=\'checkbox\']").prop("checked", isChecked);';
        $msg .= 'var setCuredDefectAllValues = [];';
        $msg .= 'if (isChecked) { oTable.$("input.setCuredDefectAll:checked").each(function () { setCuredDefectAllValues.push($(this).val()); }); }';
        $msg .= 'var CSRF_TOKEN_VALUE = $(\'[name="CSRF_TOKEN"]\').val();';
        $msg .= '$.ajax({';
        $msg .= 'type: \'GET\',';
        $msg .= 'url: \'' . base_url("documentIndex/Ajaxcalls/markCuredDefectIAMiscDocs") . '\',';
        $msg .= 'data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, objectionId: setCuredDefectAllValues, val:true,type:\'All\'},';
        $msg .= 'success: function () {';
        $msg .= 'if(setCuredDefectAllValues.length===0){';
        $msg .= '$(".setCuredDefectAllToggle").removeClass("curemarked");';
        $msg .= '}else{';
        $msg .= '$(".setCuredDefectAllToggle").addClass("curemarked");';
        $msg .= '}';
        $msg .= '}';
        $msg .= '});';
        $msg .= '});';
        $msg .= '});';
        $msg .= 'function setCuredDefect(id) {';
        $msg .= 'var CSRF_TOKEN_VALUE = $(\'[name="CSRF_TOKEN"]\').val();';
        $msg .= 'var value = $("#" + id).is(":checked"); ';
        $msg .= '$.ajax({';
        $msg .= 'type: \'GET\',';
        $msg .= 'url: \'' . base_url("documentIndex/Ajaxcalls/markCuredDefectIAMiscDocs") . '\',';
        $msg .= 'data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, objectionId: id, val:value,type:\'One\'},';
        $msg .= 'success: function () {';
        $msg .= '$("#row"+id).toggleClass("curemarked")';
        $msg .= '';
        $msg .= '},';
        $msg .= 'error: function () {alert("failed");}';
        $msg .= '});';
        $msg .= '}';
        $msg .= '</script>';
        $msg .= '<style>';
        $msg .= '.curemarked {';
        $msg .= ' font-weight: bold; text-decoration: line-through;';
        $msg .= '}';
        $msg .= '</style>';
        return $msg;
    } elseif (isset($result_initial) && !empty($result_initial)) {
        $msg = '<div class="alert" style="border-color: #ebccd1;background-color: #f2dede;color: #a94442;">';
        $msg .= '<p><strong>Defect Raised On : </strong>' . date('d-m-Y H:i:s', strtotime($result_initial->defect_date)) . '</p>';
        $msg .= '<p><strong>Defects : </strong>' . script_remove($result_initial->defect_remark) . '</p>';
        if ($result_initial->defect_cured_date != NULL) {
            $msg .= '<p><strong>Defect Cured On : </strong>' . htmlentities(date('d-m-Y H:i:s', strtotime($result_initial->defect_cured_date)), ENT_QUOTES) . '</p>';
        }
        $msg .= '</div>';
        return $msg;
    } else{
        return false;
    }
}

function is_certified_copy_details($ref_tbl_lower_court_details_id,$registration_id) {
    $db = \Config\Database::connect();
    $response = array();
    if (isset($ref_tbl_lower_court_details_id) && !empty($ref_tbl_lower_court_details_id) && isset($registration_id) && !empty($registration_id)){
        // $ci = &get_instance();
        // $ci->load->model('login/Login_model');
        $loginModel = new LoginModel();  
        $db = Database::connect();
        $builder = $db->table('efil.tbl_certified_copy_details');
        $builder->SELECT("*");
        $builder->WHERE('ref_tbl_lower_court_details_id', $ref_tbl_lower_court_details_id);
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('is_deleted',false);
        $query = $builder->get();
        $response = $query->getResultArray();
    }
    return $response;
}

function getAordetailsByAORCODE($aor_code) {
    $db = \Config\Database::connect();
    $response = array();
    if (isset($aor_code) && !empty($aor_code)) {
        // $ci = &get_instance();
        // $ci->load->model('common/Common_model');
        $builder = $db->table('efil.tbl_users');
        $builder->select("*");
        $builder->WHERE('is_deleted',false);
        $builder->WHERE('is_active','1');
        $builder->WHERE('cast(aor_code as int)=',$aor_code);
        $query = $builder->get();
        $response = $query->getResult();
    }
    return $response;
}

function is_AORGovernment($aor_code) {
    $db = \Config\Database::connect();
    $response = array();
    if (isset($aor_code) && !empty($aor_code)) {
        // $ci = &get_instance();
        // $ci->load->model('login/Login_model');
        $builder = $db->table('efil.tbl_users');
        $builder->SELECT("*");
        $builder->WHERE('cast(aor_code as int)=', $aor_code);
        $builder->WHERE('is_deleted',false);
        $builder->WHERE('is_active','1');
        $builder->WHERE('ref_department_id',1);
        $query = $builder->get();
        $response = $query->getResult();
    }
    return $response;
}

function is_clerk_aor($clerk_id,$ref_m_usertype_id,$is_user_status='N') {
    $db = \Config\Database::connect();
    $session = \Config\Services::session();
    $response = array();
    if ($ref_m_usertype_id==USER_CLERK) {
        if (isset($clerk_id) && !empty($clerk_id)) {
            // $ci = &get_instance();
            // $ci->load->model('login/Login_model');
            // $ci->load->library('session');
            $builder = $db->table('efil.aor_clerk ac');
            $builder->select('ac.*,users.first_name,users.ref_department_id,users.is_active');
            $builder->join('efil.tbl_users users', 'ac.ref_user_id=users.id');
            $builder->WHERE('ac.ref_user_id', $clerk_id);
            $builder->WHERE('ac.to_date is null');
            $builder->WHERE('users.is_deleted', false);
            $builder->WHERE('users.is_active', '1');
            $query = $builder->get();
            if ($query->getNumRows() >= 1) {
                $response = $query->getResult();
            } else{
                $session->setFlashdata('msg', '<span class="text-danger">You are not authorized !!</span>');
                if ($is_user_status=='N') {
                    return redirect()->to(base_url('login'));
                } else{
                    return redirect()->to(base_url('login/logout'));
                }
                exit(0);
            }
        }
    }
    return $response;
}

function getAordetails_ifFiledByClerk($efiling_no_or_registration_id,$type='R') {
    $db = \Config\Database::connect();
    $response = array();
    if (isset($efiling_no_or_registration_id) && !empty($efiling_no_or_registration_id) && isset($type) && !empty($type)) {
        // $ci = &get_instance();
        // $ci->load->model('common/Common_model');
        $builder = $db->table('efil.tbl_efiling_nums ten');
        $builder->SELECT("ten.*,cf.ref_user_id,cf.aor_code as aor_code_aorclerk,users.*, aorclerk.ref_m_usertype_id as ref_m_usertype_id_aorclerk");
        $builder->join('efil.clerk_filings cf', 'cf.registration_id=ten.registration_id');
        $builder->join('efil.tbl_users users', 'cf.aor_code=cast(users.aor_code as int)');
        $builder->join('efil.tbl_users aorclerk', 'cf.ref_user_id=aorclerk.id');
        if ($type=='R') {
            $builder->WHERE('ten.registration_id', $efiling_no_or_registration_id);
        } else if ($type=='A') {
            $builder->WHERE('cf.aor_code', $efiling_no_or_registration_id);
        } else{
            $builder->WHERE('ten.efiling_no', $efiling_no_or_registration_id);
        }
        $builder->WHERE('ten.is_deleted',false);
        $builder->WHERE('users.is_deleted',false);
        $builder->WHERE('users.is_active','1');
        $query = $builder->get();
        $response= $query->getResult();
        // if (isset($response) && !empty($response)) { $response = $response[0]; }
    }
    return $response;
}

if ( ! function_exists('force_download')) {
	/**
	 * Force Download
	 *
	 * Generates headers that force a download to happen
	 *
	 * @param	string	filename
	 * @param	mixed	the data to be downloaded
	 * @param	bool	whether to try and send the actual file MIME type
	 * @return	void
	 */
	function force_download($filename = '', $data = '', $set_mime = FALSE) {
		if ($filename === '' OR $data === '') {
			return;
		} elseif ($data === NULL) {
			if ( ! @is_file($filename) OR ($filesize = @filesize($filename)) === FALSE) {
				return;
			}
			$filepath = $filename;
			$filename = explode('/', str_replace(DIRECTORY_SEPARATOR, '/', $filename));
			$filename = end($filename);
		} else{
			$filesize = strlen($data);
		}
		// Set the default MIME type to send
		$mime = 'application/octet-stream';
		$x = explode('.', $filename);
		$extension = end($x);
		if ($set_mime === TRUE) {
			if (count($x) === 1 OR $extension === '') {
				/* If we're going to detect the MIME type,
				 * we'll need a file extension.
				 */
				return;
			}
			// Load the mime types
			$mimes =& get_mimes();
			// Only change the default MIME if we can find one
			if (isset($mimes[$extension])) {
				$mime = is_array($mimes[$extension]) ? $mimes[$extension][0] : $mimes[$extension];
			}
		}
		/* It was reported that browsers on Android 2.1 (and possibly older as well)
		 * need to have the filename extension upper-cased in order to be able to
		 * download it.
		 *
		 * Reference: http://digiblog.de/2011/04/19/android-and-the-download-file-headers/
		 */
		if (count($x) !== 1 && isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Android\s(1|2\.[01])/', $_SERVER['HTTP_USER_AGENT'])) {
			$x[count($x) - 1] = strtoupper($extension);
			$filename = implode('.', $x);
		}
		if ($data === NULL && ($fp = @fopen($filepath, 'rb')) === FALSE) {
			return;
		}
		// Clean output buffer
		if (ob_get_level() !== 0 && @ob_end_clean() === FALSE) {
			@ob_clean();
		}
		// Generate the server headers
		header('Content-Type: '.$mime);
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		header('Expires: 0');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '.$filesize);
		header('Cache-Control: private, no-transform, no-store, must-revalidate');
		// If we have raw data - just dump it
		if ($data !== NULL) {
			exit($data);
		}
		// Flush 1MB chunks of data
		while ( ! feof($fp) && ($data = fread($fp, 1048576)) !== FALSE) {
			echo $data;
		}
		fclose($fp);
		exit;
	}

}

if (! function_exists('sendMailJioCron')) {
    function sendMailJioCron($to,$subject,$message,$files=[]) {
        $payload = json_encode([
            "providerCode"  => "email",
            "recipients"    => [
                "emailAddresses" => 
                [
                    "to" => $to
                ]
            ],
            "body"          => $message,
            "scheduledAt"   => null,
            "purpose"       => $subject,
            "subject"       => $subject, 
            "sender"        => [
                "name" => "AAP",
                "emailAddress" => "icmis@sci.nic.in"
            ],
            "createdByUser" => [
                "id"               => env('LIVE_EMAIL_KEY'),
                "name"             => "AAP",
                "employeeCode"     => env('LIVE_EMAIL_KEY'),
                "organizationName" => "AAP"
            ],
            "module"        => "AAP",
            "project"       => "AAP",
            "files"         => $files
        ]); 
        $ch = curl_init();
        curl_setopt_array($ch,[
            CURLOPT_URL => env('APP_ENV') == 'production' ? env('NEW_MAIL_SERVER_HOST_JIO_IP'):env('NEW_MAIL_SERVER_HOST_JIO_URL'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HEADER => 0,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>"$payload",
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json','Accept: application/json','Authorization: Bearer '.env('LIVE_EMAIL_KEY_JIO_CLOUD')
            ],
        ]);
        $response = curl_exec($ch);
        if($response === false) {
            return [
                'status' =>false,
                'message'=>curl_error($ch)
            ];
        } else {
            return [
                'status' =>true,
                'message'=>$response
            ];
        }
        curl_close($ch);
    }
}

function rand_string( $length ) {
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $size = strlen( $chars );
    $str = '';
    for( $i = 0; $i < $length; $i++ ) {
        $str .= $chars[ rand( 0, $size - 1 ) ];

    }
    return $str;
}


function get_constants_key_by_value($constants_key_value=""){
    $constantName = null;
    if (!empty($constants_key_value)){
        $constants = get_defined_constants(true);
        if (isset($constants['user'])) { foreach ($constants['user'] as $name => $value) { if ($value === $constants_key_value) { $constantName = $name;break; } } }
    }
    return $constantName;
}

function sendSMS($typeId="38",$mobileNo="",$smsText="",$templateId="")
{
    $templateId   = strval((string)$templateId); 
    $templateCode_orignal = get_constants_key_by_value($templateId);
    $templateCode = str_replace('SCISMS_','',$templateCode_orignal);

    if(in_array($templateCode,['Submit_Pending','Efiling_No_Generated'])){
        $templateCode = $templateCode.'_UPD';
    }

    $smsText = str_replace("&", "and", $smsText);
    $result = [];
    if (isset($mobileNo) && isset($smsText) && isset($templateCode))
    {
        // $mobileNo = null;
        $mobileNo = '6260380102';
        if (!is_array($mobileNo))
        { 
            $mobileNo=["$mobileNo"]; 
        }else{
            $mobileNo=["$mobileNo"];
        }
        date_default_timezone_set('Asia/Kolkata');
        $sms_datetime=date('Y-m-d H:i:s');
        $ip_address = get_client_ip();
        $Common_model = new CommonModel();
        $max_requests = 1; // Maximum number of requests
        $time_period = 1; // Time period in seconds (e.g., 1 hour)
        $request_time_time_period=date('Y-m-d H:i:s', time() - $time_period);
        $request_count = $Common_model->check_efiling_sms_mobile_no_log($mobileNo,$request_time_time_period,$ip_address);
        if ($request_count >= $max_requests) {
            // Too many requests, show an error message
            $text_msg = 'Please wait ' . SMS_RESEND_LIMIT . ' seconds and then try again!!'; // code to stop sms flooding
            session()->setFlashdata('msg',  $text_msg);
            return FALSE;
        }

        // if (!session()->get('otp_requests')) {
        //     session()->set('otp_requests', 0);
        //     session()->set('otp_first_request_time', time());
        // }

        // $currentTime = time();
        // $firstRequestTime = session()->get('otp_first_request_time');
        // $requestCount = session()->get('otp_requests');

        // if ($currentTime - $firstRequestTime > $time_period) {
        //     session()->set('otp_requests', 1);
        //     session()->set('otp_first_request_time', $currentTime);
            
        // } else {
        //     if ($requestCount >= $max_requests) {
        //         $text_msg = 'Please wait ' . SMS_RESEND_LIMIT . ' seconds and then try again!!'; // code to stop sms flooding
        //         session()->setFlashdata('msg', $text_msg);
        //         return FALSE;
        //         exit(0);
        //     }
        //     session()->set('otp_requests', $requestCount + 1);
        // }

        $requestt = service('request');
        $cachet = service('cache');
        $responset = service('response');
        $ipAddress = $requestt->getIPAddress();
        $key = 'otp_request_' . $ipAddress;

        if ($cachet->get($key) >= 3) {
            return $responset->setStatusCode(429)->setBody('Too many requests');
        }
        $cachet->save($key, ($cachet->get($key) ?? 0) + 1, 60);

        $metadata = json_encode(
            [
                "providerCode" => "sms",
                "recipients" => 
                    [
                        "mobileNumbers" => $mobileNo
                    ],
                "templateCode" => $templateCode,
                "body"         =>"$smsText",
                "scheduledAt"  => null,
                "purpose"      => "SC-eFM",
                "createdByUser"=> 
                    [
                        "id"               => LIVE_SMS_KEY_JIO_CLOUD,
                        "name"             => "SC-eFM",
                        "employeeCode"     => LIVE_SMS_KEY_JIO_CLOUD,
                        "organizationName" => "SC-eFM"
                    ],
                "module" => "SC-eFM",
                "project" => "SC-eFM"
            ]
        );
        $curl = curl_init();
        curl_setopt_array($curl,[
            CURLOPT_URL => NEW_SMS_SERVER_HOST_JIO_URL,
            CURLOPT_RETURNTRANSFER => true,CURLOPT_ENCODING => '',CURLOPT_MAXREDIRS => 10,CURLOPT_TIMEOUT => 0,CURLOPT_FOLLOWLOCATION => true,CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>"$metadata",
            CURLOPT_HTTPHEADER => array('Content-Type: application/json','Accept: application/json','Authorization: Bearer '.LIVE_SMS_KEY_JIO_CLOUD),
        ]);
        $response = curl_exec($curl);
        if ($response){
            $json2 = json_decode($response);
            if (isset($json2->data) && !empty($json2->data) && !empty($json2->data->job_batch_id))
            {
                $json2 = $json2->data->job_batch_id;
                $rslt_sms = "success";
                $data_email = array(
                    'ip_address' => get_client_ip(),
                    'mobile_no' => $mobileNo
                );
                $db_response_sms = $Common_model->insert_efiling_sms_email_dtl($data_email);
            }
            else
            {
                $json2 = false;
            }
        }else{ 
            $json2 = $response; 
        }
        curl_close($curl);

        $payload = [
            'template_id'=>$templateId,
            'templateCode_orignal'=>$templateCode_orignal,
            'templateCode'=>$templateCode
        ];

        if ($json2 != false) { 
            $result = [
                'status'=>true,
                'payload'=>$payload,
                'message'=>$response
            ];
        } 
        else { 
            $result = [
                'status'=>true,
                'payload'=>$payload,
                'message'=>$response
            ];
        }
        
    }
    else{
        $result = [
            'status'=>true,
            'message'=>'Something wrong in mobile, message or template id '
        ];
    }
    return $result;
}

function parsePBDownloadResponseHeaders($curl,$header_return){
    header($header_return);
    return strlen($header_return);
}
function downloadCasePaperBook($url, $headers = [], $if_return_metadata = false)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => false,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HEADERFUNCTION => 'parsePBDownloadResponseHeaders',
        CURLOPT_HTTPHEADER => ($headers ?: array('User-Agent: AutoBot')),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function relay_mail_api_through_jio_cloud_server($to_email, $subject, $message, $to_user_name = "")
{
    $view = service('renderer');
    foreach ($to_email as $val_email) {
        $to_email = $val_email;
        $email_message = '';

        if (isset($_SESSION['citation_mail']) == 'Citation') {

            $search_by = $_SESSION['citation_data'][1]['search_by'];
            if ($search_by == 'J') {
                $page_no = $_SESSION['citation_data'][1]['page_no'];
                $volume = $_SESSION['citation_data'][1]['volume'];
                $journal_year = $_SESSION['citation_data'][1]['journal_year'];
                $journal = $_SESSION['citation_data'][1]['journal'];

            } else {
                $page_no = $_SESSION['citation_data'][0]['page_no'];
                $volume = $_SESSION['citation_data'][0]['volume'];
                $journal_year = $_SESSION['citation_data'][0]['journal_year'];
                $journal = $_SESSION['citation_data'][0]['journal'];
            }
            $given_by = $_SESSION['login']['first_name'];
            //$this->session->userdata['login']['first_name']

            $case_data_info = array('diary_no' => $_SESSION['case_data'][0]['diary_no'], 'registation_no' => $_SESSION['case_data'][0]['reg_no_display']
            , 'cause_title' => $_SESSION['case_data'][0]['cause_title'], 'dairy_yr' => $_SESSION['case_data'][0]['diary_year'],
                'title' => $_SESSION['citation_data'][0]['title'], 'pubname' =>
                    $_SESSION['citation_data'][0]['pubnm'], 'pubyear' => $_SESSION['citation_data'][0]['pubyr'], 'subject' => $_SESSION['citation_data'][0]['sub'],
                'listing_date' => $_SESSION['citation_data'][0]['listing_date'], 'page_no' => $page_no, 'volume' => $volume, 'journal_year' => $journal_year, 'journal' => $journal, 'given_by' => $given_by);
            $v = new RendererInterface();
            $email_message = ($view->setData($case_data_info)->render('templates/email/citation_mail'));

        } elseif (isset($_SESSION['adv_details'], $_SESSION['adv_details']['first_name'], $_SESSION['adv_details']['last_name'])) {
            $data = array('first_name' => $_SESSION['adv_details']['first_name'],
                'last_name' => $_SESSION['adv_details']['last_name'],
                'message' => $message
            );
            $email_message = ($view->setData($data)->render('templates/email/password_reset'));
            // $email_message = (view('templates.email.password_reset', $data, true));
        } elseif ($to_user_name == 'adjournment') {
            $email_message = ($message);
        } elseif ($to_user_name == 'arguing_counsel') {
            $email_message = ($message);
        } elseif (isset(getSessionData('login')['first_name'], getSessionData('login')['last_name'])) {
            $data = array('first_name' => getSessionData('login')['first_name'],
                'last_name' => getSessionData('login')['last_name'],
                'message' => $message
            );
            $email_message = ($view->setData($data)->render('templates/email/html_mail'));
            // $email_message = (view('templates.email.html_mail', $data, true));
        } else {
            $email_message = ($message);
        }

        /*start security Audit  code to stop sms flooding*/
        $Common_model = new CommonModel();
        //$_SESSION['last_sms'] = time();
        $_SESSION['last_sms_to_email'] = $to_email;
        $_SESSION['last_sms_ip'] = get_client_ip();
        $ip_address = get_client_ip();
        // Define rate limit parameters
        $max_requests = 5; // Maximum number of requests
        $time_period = 5; // Time period in seconds (e.g., 1 hour)
        $request_time_time_period=date('Y-m-d H:i:s', time() - $time_period);
        $request_count = $Common_model->check_efiling_sms_email_log($to_email,$request_time_time_period,$ip_address);
        // if ($request_count >= $max_requests) {
        //     // Too many requests, show an error message
        //     $text_msg = 'Please wait ' . SMS_RESEND_LIMIT . ' seconds and then try again!'; // code to stop sms flooding
        //     session()->setFlashdata('msg', '<div class="uk-alert-danger" uk-alert> <a class="uk-alert-close" uk-close></a> <p style="text-align: center;">' . $text_msg . '</p> </div>');
        //     return FALSE;
        // }
        $reqeustt = service('request');
        $cachet = service('cache');
        $responsee = service('response');
        $ipAddress = $reqeustt->getIPAddress();
        $key = 'otp_mail_request_' . $ipAddress;
        // if ($cachet->get($key) >= 3) {
        //     return $responsee->setStatusCode(429)->setBody('Too many requests');
        // }
        // $cachet->save($key, $cachet->get($key) + 1, 60);
        /*End security Audit  code to stop sms flooding*/
        // return true; exit(); // remove this line before going live
        $json2=send_mail_JIO($to_email,$subject,$email_message);
        $response=$json2;
        $result = '';
        if ($json2) {
            if ($response == 'success') {
                $result = 'success';
                $data_email = array(
                    'ip_address' => $ip_address,
                    'email' => $to_email
                );
                $db_response_sms = $Common_model->insert_efiling_sms_email_dtl($data_email);
            } else {
                $result = 'failed';
            }
        }
        return $result;
    }
}

function send_mail_JIO($to_email,$subject,$message,$files=array())
{
    if (isset($to_email) && isset($subject) && isset($message)){
        $sms_datetime=date('Y-m-d H:i:s');
        $ip_address = get_client_ip();
        $Common_model = new CommonModel();
        $max_requests = 1; // Maximum number of requests
        $time_period = 1; // Time period in seconds (e.g., 1 hour)
        $request_time_time_period=date('Y-m-d H:i:s', time() - $time_period);
        $request_count = $Common_model->check_efiling_sms_email_log($to_email, $request_time_time_period, $ip_address);
        if ($request_count >= $max_requests) {
            // Too many requests, show an error message
            $text_msg = 'Please wait ' . SMS_RESEND_LIMIT . ' seconds and then try again!!'; // code to stop sms flooding
            session()->setFlashdata('msg',  $text_msg);
            return FALSE;
        }
        $to_email = 'punit.sharma@velocis.co.in';
        if (!is_array($to_email)){ $to_email=[$to_email]; }
        $metadata = json_encode(array("providerCode" => "email","recipients" => array("emailAddresses" => array("to" =>$to_email)),"body" =>$message,"scheduledAt" => null,"purpose" => $subject,"subject" => $subject, "sender" => array("name" => "SC-eFM","emailAddress" => "icmis@sci.nic.in"),"createdByUser" => array("id" => LIVE_EMAIL_KEY,"name" => "SC-eFM","employeeCode" => LIVE_EMAIL_KEY,"organizationName" => "SC-eFM"),"module" => "SC-eFM","project" => "SC-eFM","files" => $files));
        $curl = curl_init();
        curl_setopt_array($curl,array(
            CURLOPT_URL => NEW_MAIL_SERVER_HOST_JIO_URL,
            CURLOPT_RETURNTRANSFER => true,CURLOPT_ENCODING => '',CURLOPT_MAXREDIRS => 10,CURLOPT_TIMEOUT => 0,CURLOPT_FOLLOWLOCATION => true,CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,CURLOPT_HEADER => 0,CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>"$metadata",
            CURLOPT_HTTPHEADER => array('Content-Type: application/json','Accept: application/json','Authorization: Bearer '.LIVE_EMAIL_KEY_JIO_CLOUD),
        ));
        $response = curl_exec($curl);
        if ($response){
            $json2 = json_decode($response);
            if (isset($json2->data) && !empty($json2->data) && !empty($json2->data->job_batch_id)){
                $json2=$json2->data->job_batch_id;
            }else{ $json2=false;}
        }else{ $json2 = $response; }
        curl_close($curl);
        if ($json2!=false) { $json2 = 'success';} else { $json2 = 'failed'; }
    }else{
        $json2 = 'failed';
    }
    return $json2;
}
function countPdfPages($filePath){
$pdf=new TCPDF();
return $pdf->setSourceFile($filePath);
}
function send_mail_cron_jio($data)
{
    return true; exit(); // remove this line before going live
    $view = service('renderer');
    if (!empty($data)) {
        $to_email = $data['to_email'];
        $subject = $data['subject'];
        // $ci->load->library('email');
        // $email_message = (view('templates.email.reports', $data, true));
        $email_message = ($view->setData($data)->render('templates/email/reports'));
        $files = array();
        // $json2=send_mail_JIO($to_email,$subject,$email_message,$files);
        return $json2;
    }//End of foreach loop..
    $result = 'Data not found';
    return $result;
}
function bharatkoshSaveStatus($data){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->bharatkoshSaveStatus($data);
    return $result;
}
function getBharakoshPaymentStatus($data){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->getBharakoshPaymentStatus($data['orderCode'],$data['orderStatus']);
    return $result;
}
function getBharatKoshRequest($orderCode){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->getBharatKoshRequest($orderCode);
    return $result;  
}
function getCopyingDetails($crn){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->getCopyingDetails($crn);
    return $result;
    
}
function get_application_type($app_type){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->get_application_type($app_type);
    return $result;
    
}
function getAppearingDiaryNosOnly($cause_list_date, $aor_code){
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->getAppearingDiaryNosOnly($cause_list_date, $aor_code);
    return $result;
    
}

function eCopyingVerifiedUser($email, $mobile, $status){
    
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->eCopyingVerifiedUser($email, $mobile, $status);
    return $result;
}
function eCopyingVerifiedUserAOR($email, $mobile, $status, $bar_id){
    
    $ecoping_webservices=new Ecoping_webservices();
    $result=$ecoping_webservices->eCopyingVerifiedUserAOR($email, $mobile, $status, $bar_id);
    return $result;
}

function isDateString($value) {
    $formats = [
        'd/m/Y',
        'm/d/Y',
        'd-m-Y',
        'm-d-Y',
        'Y-m-d',
        'Y-d-m',
    ];

    foreach ($formats as $format) {
        $dateTime = DateTime::createFromFormat($format, $value);
        if ($dateTime && $dateTime->format($format) === $value) {
            return true;
        }
    }

    return false;
}

function parseDate($dateString) {
    $formats = [
        'd/m/Y',
        'm/d/Y',
        'Y-m-d',
        'Y/m/d',
        'd-m-Y',
        'm-d-Y',
    ];

    foreach ($formats as $format) {
        $dateTime = DateTime::createFromFormat($format, $dateString);
        if ($dateTime && $dateTime->format($format) === $dateString) {
            return $dateTime->format('Y-m-d');
        }
    }
    return false;
}

function setcookiepath(){
    $totalSegments = service('uri')->getTotalSegments();
    $lastSegment = service('uri')->getSegment($totalSegments);
    return $lastSegment;
    // return service('request')->getUri()->getPath();
    // return $_SERVER['REQUEST_URI'];
}

function getEcopyingDashbordData($dashboard_flag){
    $ecoping_webservices=new Ecoping_webservices();
    $mobile = $_SESSION['login']['mobile_number'];
    $email = $_SESSION['login']['emailid'];
    $result=$ecoping_webservices->getEcopyingDashbordData($dashboard_flag, $mobile, $email);
    //return $rate;
    return $result;
}

function check_submaster_category($registration_id) {
    $Dropdown_list_model = new DropdownListModel();
    $session = \Config\Services::session();
    $check_category_result = $Dropdown_list_model->check_submaster_category($registration_id);
    if ($check_category_result) {
        $session->setFlashdata('msg', '<div class="alert alert-danger text-center">The Main Category field is required, and the Sub Category 1 field is also required.</div>');
        return redirect()->to(base_url('newcase/caseDetails'));
    }
}

function is_vacation_supp_advance_list_duration()
{
    $Vacation_advance_supp_model = new VacationAdvanceSuppModel();
    $session = \Config\Services::session();
    $is_vacation_advance_list_duration = $Vacation_advance_supp_model->is_vacation_supp_advance_list_duration();
    return $is_vacation_advance_list_duration;
}


function getStateName($id){
    $Dropdown_list_model = new DropdownListModel();
    return $Dropdown_list_model->getStateName($id);
}
function getDistrictName($id){
    $Dropdown_list_model = new DropdownListModel();
    return $Dropdown_list_model->getDistrictName($id);
}