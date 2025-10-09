<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function send_email($emailId, $subject, $message, $attachment_url, $attachment_file_name){
    $fileData=array();
    $fileData[] = array( 'url'=>$attachment_url,
        'file_name'=>$attachment_file_name,
    );
    $json=json_encode($fileData);
    $url=EADMINSCI_URI."/stealth-a-push-mail-gw?toIds=".rawurlencode($emailId)."&subject=".rawurlencode($subject)."&msg=".rawurlencode($message)."&typeId=".SMS_EMAIL_API_USER."&documentsJson=".rawurlencode($json);
    $result=(array)json_decode(file_get_contents($url));
    return $result;
}

function countStringInFile($file,$string){
    $handle = fopen($file, 'r');
    $valid = 0; // init as 0
    while (($buffer = fgets($handle)) !== false) {
        if (strpos($buffer, $string) !== false) {
            $valid++;
        }
    }
    fclose($handle);
    return $valid;
}

function encrypt_request($payload){
    $encrypt=@openssl_encrypt($payload, 'AES-128-CBC', 'Kj4SscMwt1I7', OPENSSL_RAW_DATA, 'prg2mRxa1ad8foir');
    $request_str = base64_encode(base64_encode($encrypt));
    return $request_str;
}
/*
function decrypt($encrypted_string){
    $decoded_string=@openssl_decrypt($encrypted_string, 'AES-128-CBC', 'Kj4SscMwt1I7', OPENSSL_RAW_DATA, 'prg2mRxa1ad8foir');
    return $decoded_string;
}*/