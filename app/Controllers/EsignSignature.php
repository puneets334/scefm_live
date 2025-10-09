<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\Newcase\NewCaseModel;
use App\Models\NewCase\DropdownListModel;
use App\Models\Newcase\GetDetailsModel;
use App\Models\Affirmation\AffirmationModel;
use App\Models\Affirmation\EsignSignatureModel;

class EsignSignature extends BaseController {

    protected $CommonModel;
    protected $NewCaseModel;
    protected $DropdownListModel;
    protected $GetDetailsModel;
    protected $AffirmationModel;
    protected $EsignSignatureModel;

    public function __construct() {
        parent::__construct();

        $this->CommonModel = new CommonModel();
        $this->NewCaseModel = new NewCaseModel();
        $this->DropdownListModel = new DropdownListModel();
        $this->GetDetailsModel = new GetDetailsModel();
        $this->AffirmationModel = new AffirmationModel();
        $this->EsignSignatureModel = new EsignSignatureModel();    

        helper(['file']);
        library(['TCPDF']);
        // $this->load->library('TCPDF');
        // $this->load->helper('file');
        require_once APPPATH . 'third_party/eSign/XMLSecEnc.php';
        require_once APPPATH . 'third_party/eSign/XMLSecurityDSig.php';
        require_once APPPATH . 'third_party/eSign/XMLSecurityKey.php';
    }

    public function get_client_ip() {
        $ipaddress = '';
        if (HTTP_CLIENT_IP)
            $ipaddress = HTTP_CLIENT_IP;
        else if (HTTP_X_FORWARDED_FOR)
            $ipaddress = HTTP_X_FORWARDED_FOR;
        else if (HTTP_X_FORWARDED)
            $ipaddress = HTTP_X_FORWARDED;
        else if (HTTP_FORWARDED_FOR)
            $ipaddress = HTTP_FORWARDED_FOR;
        else if (HTTP_FORWARDED)
            $ipaddress = HTTP_FORWARDED;
        else if (REMOTE_ADDR)
            $ipaddress = REMOTE_ADDR;
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    function esign_docs() {
        
        unset($_SESSION['MOB_OTP_VERIFY_UPLOADED_DOCS']);
        if (!($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (!(isset($_SESSION['efiling_details']) && !empty($_SESSION['efiling_details']))) {
            redirect('dashboard');
            exit(0);
        }

        if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
            $redirectURL = 'affirmation';
        } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
            $redirectURL = 'miscellaneousFiling/affirmation';
        } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_DEFICIT_COURT_FEE) {
            $redirectURL = 'Deficit_court_fee/affirmation';
        } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
            $redirectURL = 'IA/affirmation';
        } else {
            redirect('dashboard');
            exit(0);
        }


        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $efiling_num = $_SESSION['efiling_details']['efiling_no'];
        $est_code = $_SESSION['estab_details']['estab_code'];

        if (isset($registration_id) && $registration_id != NULL) {

            if ($_POST['name_as_on_aadhar'] || $_POST['adv_name_as_on_aadhar_2']) {

                // START CASE WHEN E-SIGNING OF Deficit Fee RECEIPT AND ON NEW CASE WHEN ADVOCATE IS SIGNING HIS OWN AFFIRMATION

                if ($_POST['name_as_on_aadhar']) {
                    $name_as_on_aadhar = $_POST['name_as_on_aadhar'];
                } elseif ($_POST['adv_name_as_on_aadhar_2']) {
                    $name_as_on_aadhar = $_POST['adv_name_as_on_aadhar_2'];
                }

                if (!isset($name_as_on_aadhar) || $name_as_on_aadhar == '' || strlen($name_as_on_aadhar) > 50 || preg_match('/[^a-zA-Z\s.]/i', $name_as_on_aadhar)) {
                    $_SESSION['MSG'] = message_show("fail", 'Name as on Aadhar is required, should be less than 50 characters and no special characters allowed !');
                    redirect($redirectURL);
                    exit(0);
                }

                // STARTS eSIGNING Deficit Fee RECEIPT

                if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_DEFICIT_COURT_FEE) {

                    // $payment_details = $this->Stageslist_model->get_total_payment_detail($registration_id);
                    $doc_id = $payment_details[0]['id'];

                    if (empty($doc_id) && !preg_match("/^[0-9]*$/", $doc_id) && strlen($doc_id) > INTEGER_FIELD_LENGTH) {
                        $_SESSION['MSG'] = message_show("fail", 'Invalid Id . Please try again !');
                        redirect($redirectURL);
                        exit(0);
                    }

                    //  $receipts_upload = $this->Stageslist_model->get_court_fees_receipts($doc_id, $registration_id);
                    //--UNSIGNED DOCUMENT DETAILS---------//
                    $receipts_name = $receipts_upload[0]->receipt_name;
                    $unsigned_pdf_full_path = getcwd() . '/uploaded_docs/' . $est_code . '/' . $efiling_num . '/court_fee/';
                    $unsigned_pdf_full_path_with_file_name = $unsigned_pdf_full_path . $receipts_name;
                    $unsigned_pdf_partial_path = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/court_fee/';
                    $unsigned_pdf_partial_path_with_file_name = $unsigned_pdf_partial_path . $receipts_name;
                    $unsigned_pdf_file_name = $receipts_name;
                    $unsigned_pdf_sha_256_hash = hash_file('sha256', $unsigned_pdf_partial_path_with_file_name);
                    //--UNSIGNED DOCUMENT DETAILS END---------//
                    //--SIGNED DOCUMENT DETAILS---------//
                    $signed_pdf_full_path = getcwd() . '/uploaded_docs/' . $est_code . '/' . $efiling_num . '/court_fee/';
                    $signed_pdf_partial_path = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/court_fee/';
                    $signed_pdf_full_path_with_file_name = $signed_pdf_full_path . 'eSign_' . $receipts_name;
                    $signed_pdf_partial_path_with_file_name = $signed_pdf_partial_path . 'eSign_' . $receipts_name;
                    $signed_pdf_file_name = 'eSign_' . $efiling_num . '_' . $receipts_name;

                    //--SIGNED DOCUMENT DETAILS END---------//

                    $signed_by = ESIGNED_DOCS_BY_ADV4;

                    // ENDS eSIGNING Deficit Fee RECEIPT
                } else {

                    // STARTS ESIGNING BY ADVOCATE AADHAAR
                    //--UNSIGNED DOCUMENT DETAILS---------//
                    $unsigned_pdf_full_path = getcwd() . '/uploaded_docs/' . $est_code . '/' . $efiling_num . '/unsigned_pdf/';
                    $unsigned_pdf_full_path_with_file_name = $unsigned_pdf_full_path . $efiling_num . '_adv_oath.pdf';
                    $unsigned_pdf_partial_path = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/unsigned_pdf/';
                    $unsigned_pdf_partial_path_with_file_name = $unsigned_pdf_partial_path . $efiling_num . '_adv_oath.pdf';
                    $unsigned_pdf_file_name = $efiling_num . '_adv_oath.pdf';
                    $unsigned_pdf_sha_256_hash = hash_file('sha256', $unsigned_pdf_partial_path_with_file_name);
                    //--UNSIGNED DOCUMENT DETAILS END---------//
                    //--SIGNED DOCUMENT DETAILS---------//
                    $signed_pdf_full_path = getcwd() . '/uploaded_docs/' . $est_code . '/' . $efiling_num . '/signed_pdfs/';
                    $signed_pdf_partial_path = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/signed_pdfs/';
                    $signed_pdf_full_path_with_file_name = $signed_pdf_full_path . $efiling_num . '_adv_oath.pdf';
                    $signed_pdf_partial_path_with_file_name = $signed_pdf_partial_path . $efiling_num . '_adv_oath.pdf';
                    $signed_pdf_file_name = $efiling_num . '_adv_oath.pdf';
                    //--SIGNED DOCUMENT DETAILS END---------//

                    $signed_by = ESIGNED_DOCS_BY_ADV3;

                    // ENDS ESIGNING BY ADVOCATE AADHAAR
                }

                // START CASE WHEN E-SIGNING OF Deficit Fee RECEIPT AND ON NEW CASE WHEN ADVOCATE IS SIGNING HIS OWN AFFIRMATION
            } elseif ((isset($_POST['pet_name_as_on_aadhar'])) || (isset($_POST['adv_name_as_on_aadhar']) )) {

                if ($_POST['pet_name_as_on_aadhar']) {
                    $name_as_on_aadhar = $_POST['pet_name_as_on_aadhar'];
                    $signed_by = ESIGNED_DOCS_BY_PET;
                } elseif (adv_name_as_on_aadhar) {
                    $name_as_on_aadhar = $_POST['adv_name_as_on_aadhar'];
                    $signed_by = ESIGNED_DOCS_BY_ADV;
                }

                if (!isset($name_as_on_aadhar) || $name_as_on_aadhar == '' || strlen($name_as_on_aadhar) > 50 || preg_match('/[^a-zA-Z\s.]/i', $name_as_on_aadhar)) {
                    $_SESSION['MSG'] = message_show("fail", 'Name as on Aadhaar is required, should be less than 50 characters and no special characters allowed !');
                    redirect($redirectURL);
                    exit(0);
                }

                //--UNSIGNED DOCUMENT DETAILS---------//
                $unsigned_pdf_full_path = getcwd() . '/uploaded_docs/' . $est_code . '/' . $efiling_num . '/unsigned_pdf/';
                $unsigned_pdf_full_path_with_file_name = $unsigned_pdf_full_path . $efiling_num . '_pet_affirmation.pdf';
                $unsigned_pdf_partial_path = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/unsigned_pdf/';
                $unsigned_pdf_partial_path_with_file_name = $unsigned_pdf_partial_path . $efiling_num . '_pet_affirmation.pdf';
                $unsigned_pdf_file_name = $efiling_num . '_pet_affirmation.pdf';
                $unsigned_pdf_sha_256_hash = hash_file('sha256', $unsigned_pdf_partial_path_with_file_name);
                //--UNSIGNED DOCUMENT DETAILS END---------//
                //--SIGNED DOCUMENT DETAILS---------//
                $signed_pdf_full_path = getcwd() . '/uploaded_docs/' . $est_code . '/' . $efiling_num . '/signed_pdfs/';
                $signed_pdf_partial_path = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/signed_pdfs/';
                $signed_pdf_full_path_with_file_name = $signed_pdf_full_path . $efiling_num . '_pet_affirmation.pdf';
                $signed_pdf_partial_path_with_file_name = $signed_pdf_partial_path . $efiling_num . '_pet_affirmation.pdf';
                $signed_pdf_file_name = $efiling_num . '_pet_affirmation.pdf';

                //--SIGNED DOCUMENT DETAILS END---------//
            }


            $file_details = array(
                'ref_registration_id' => $registration_id,
                'ref_efiling_no' => $efiling_num,
                'type' => $signed_by,
                'register_updated_date_time' => date('Y-m-d H:i:s'),
                'unsigned_pdf_full_path' => $unsigned_pdf_full_path,
                'unsigned_pdf_full_path_with_file_name' => $unsigned_pdf_full_path_with_file_name,
                'unsigned_pdf_partial_path' => $unsigned_pdf_partial_path,
                'unsigned_pdf_partial_path_with_file_name' => $unsigned_pdf_partial_path_with_file_name,
                'unsigned_pdf_file_name' => $unsigned_pdf_file_name,
                'unsigned_pdf_sha_256_hash' => $unsigned_pdf_sha_256_hash,
                'signed_pdf_full_path' => $signed_pdf_full_path,
                'signed_pdf_full_path_with_file_name' => $signed_pdf_full_path_with_file_name,
                'signed_pdf_partial_path' => $signed_pdf_partial_path,
                'signed_pdf_partial_path_with_file_name' => $signed_pdf_partial_path_with_file_name,
                'signed_pdf_file_name' => $signed_pdf_file_name,
                'name_as_on_aadhar' => $name_as_on_aadhar
            );


            $signed_pdf_dir = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/signed_pdfs/';

            if (!is_dir($signed_pdf_dir)) {
                $uold = umask(0);
                if (mkdir($signed_pdf_dir, 0777, true)) {
                    $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                    write_file($signed_pdf_dir . 'index.html', $html);
                }
                umask($uold);
            }

            $this->open_cdac_esign_url($name_as_on_aadhar, $unsigned_pdf_full_path_with_file_name, $signed_pdf_full_path_with_file_name, $file_details);
        } else {
            $_SESSION['MSG'] = message_show("fail", 'Registration id not found , try again after some time !');
            redirect($redirectURL);
            exit(0);
        }
    }

    public function open_cdac_esign_url($name_on_aadhaar, $unsigned_pdf_full_path_with_file_name, $signed_pdf_full_path_with_file_name, $file_details) {

        $pdf_hash_object = new java("sci.pdfhash.PdfHash");
        $documentHash = $pdf_hash_object->getDocumentHash($unsigned_pdf_full_path_with_file_name, $signed_pdf_full_path_with_file_name, null, null, $name_on_aadhaar);
        $java_pdf_hash = $documentHash;
        $ts1 = date("Y-m-d");
        $ts2 = date("H:i:s");
        $ts = $ts1 . 'T' . $ts2;
        $app_id = $this->gen_application_number();
        $app_id = sprintf("%'.04d", $app_id);

        $txn = '016-ECOURTS-' . date("Ymd") . '-' . date("His") . '-' . ESIGN_REDIRECT_URL_CODE . $app_id;
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
    			<Esign ver="2.1" sc="Y"  ts="' . $ts . '"  txn="' . $txn . '" ekycIdType="A"   aspId="NICI-001" AuthMode="1" responseSigType="PKCS7"
                           
                         responseUrl="https://nic-esign2gateway.nic.in/esign/response?rs=' . ESIGN_RESPONSE_URL . '"> 
                          
                       <Docs><InputHash docInfo="DOC" hashAlgorithm="SHA256" id="1">' . $java_pdf_hash . '</InputHash></Docs>
                                                
                    </Esign>';

        $doc = new DOMDocument();
        $doc->loadXML($xml);
        $objDSig = new XMLSecurityDSig();
        $objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);
        $objDSig->addReference(
                $doc, XMLSecurityDSig::SHA1, array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'), array('force_uri' => true)
        );

        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type' => 'private'));
        $objKey->passphrase = 'eSignEcourts@123';
        $objKey->loadKey(APPPATH . 'third_party/eSign/ecourts.pem', TRUE);
        $objDSig->sign($objKey);
        $objDSig->add509Cert('ecourts.pem');
        $objDSig->appendSignature($doc->documentElement);
        $signed_xml = $doc->saveXML();

        // you may change these values to your own
        $secret_key = 'eCommittee#$321';
        $string = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $string = $signed_xml . '@@@' . ESIGN_RESPONSE_URL . '@@@' . $name_on_aadhaar;

        $string_data = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0));

        $this->Esign_signature_model->register_otp_generation_entry($file_details, $ts, $txn);


        echo '<p style="text-align: center">Don\'t refresh this page; Aadhaar varification is under process. Please wait ...</p>';
        $attribute = array('name' => 'esign_request_url', 'id' => 'esign_request_url', 'autocomplete' => 'off');
        echo form_open('https://efiling.ecourts.gov.in/esignature/', $attribute); //Server
        echo '<input type = "hidden" name = "esign_data" value = "' . htmlentities($string_data, ENT_QUOTES) . '" />';
        echo form_close();
        echo "<script>document.getElementById('esign_request_url').submit();</script>";
    }

    public function esign_response() {

        if (isset($_POST['xml_reponse'])) {

            $response = (array) simplexml_load_string($_POST['xml_reponse']);

            $errcode = $response['@attributes']['errCode'];
            $errmsg = $response['@attributes']['errMsg'];
            $res_code = $response['@attributes']['resCode'];
            $status = $response['@attributes']['status'];
            $rests = $response['@attributes']['ts'];
            $txn = $response['@attributes']['txn'];
            $UserX509Certificate = $response['UserX509Certificate'];
            $diges_value = $response['Signature']->SignedInfo->Reference->DigestValue;
            $X509Certificate = $response['Signature']->KeyInfo->X509Data->X509Certificate;
            $signature_value = $response['Signature']->SignatureValue;

            if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_DEFICIT_COURT_FEE) {
                $redirect_url = 'deficit_court_fee/affirmation';
            } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
                $redirect_url = 'miscellaneousFiling/affirmation';
            } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
                $redirect_url = 'affirmation';
            } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
                $redirect_url = 'IA/affirmation';
            } else {
                redirect('dashboard');
                exit(0);
            }

            $txn_status = $this->Esign_signature_model->get_sign_txn_details($txn);

            if (!empty($txn_status[0]['digestvalue'])) {
                $_SESSION['MSG'] = message_show("fail", 'Invalid attempt !');
                redirect($redirect_url);
                exit(0);
            }

            if ($status == '1') {
                $is_data_valid = TRUE;
                $signed_pdf_saved = 'Yes';
            } else {
                $is_data_valid = FALSE;
                $signed_pdf_saved = 'No';
            }

            $data = array(
                'is_data_valid' => $is_data_valid,
                'rests' => $rests,
                'txn' => $res_code,
                'errmsg' => $errmsg,
                'errcode' => $errcode,
                'status' => $status,
                'userx509certificate' => $UserX509Certificate,
                'x509certificate' => $X509Certificate,
                'signaturevalue' => $signature_value,
                'digestvalue' => $diges_value,
                'signed_pdf_saved' => $signed_pdf_saved,
                'signed_type'=>2
            );


            if ($errcode == 'NA' && $status == '1') {

                $pdf_hash_object = new java("sci.pdfhash.PdfHash");
                $esign_result = $pdf_hash_object->signDocument($_POST['xml_reponse']);

                if ($esign_result != $_POST['xml_reponse']) {
                    $errmsg1 = 'If eSign using Aadhaar will be failed two times due to any technical reasons. e-Verification using Mobile will be automatically displayed at the very same page to proceed further.';
                    if ($errmsg == '' && $errcode == '111') {
                        $_SESSION['MSG'] = message_show("fail", 'Invalid Aadhaar Number !');
                    } elseif ($errmsg == '') {

                        if (!ENABLE_EVERIFICATION_BY_MOBILE_OTP && ENABLE_EVERIFICATION_ON_ESIGN_FAIL) {
                            $_SESSION['MSG'] = message_show("fail", $errmsg1);
                        } else {
                            $_SESSION['MSG'] = message_show("fail", 'Some Error Occurred , try again after some time or contact Admin !');
                        }
                    } else {
                        if (!ENABLE_EVERIFICATION_BY_MOBILE_OTP && ENABLE_EVERIFICATION_ON_ESIGN_FAIL) {
                            $_SESSION['MSG'] = message_show("fail", $errmsg . '<br>' . $errmsg1);
                        } else {
                            $_SESSION['MSG'] = message_show("fail", $errmsg);
                        }
                    }
                    redirect($redirect_url);
                    exit(0);
                }


                $registration_id = $_SESSION['efiling_details']['registration_id'];
                if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_DEFICIT_COURT_FEE) {
                    if ($status == '1') {

                        $doc_details = $this->Stageslist_model->get_total_payment_detail($registration_id);
                        $doc_id = $doc_details[0]['id'];
                        $signed_pdf_partial_path = $doc_details[0]['signed_pdf_partial_path'];
                        $receipts_name = $doc_details[0]['receipt_name'];
                        $old_file_path = $signed_pdf_partial_path . $receipts_name;

                        $file_path = getcwd() . '/' . $signed_pdf_partial_path;
                        $filesize = filesize($file_path);
                        if (empty($filesize) || $filesize == '0' || $filesize == 0) {
                            $_SESSION['MSG'] = message_show("fail", 'Some Error Occurred , try again after some time !');
                            redirect($redirect_url);
                            exit(0);
                        }

                        $this->MiscellaneousFiling_model->update_file_name(url_encryption($doc_id), 'eSign_' . $receipts_name, $signed_pdf_saved, $old_file_path);
                    }
                }
                
                $esigned_file_size = $this->Affirmation_model->get_esigned_file_size($txn, $registration_id);

                $file_path = getcwd() . '/' . $esigned_file_size[0]->signed_pdf_partial_path_with_file_name;
                $filesize = filesize($file_path);

                if (empty($filesize) || $filesize == '0' || $filesize == 0) {
                    $_SESSION['MSG'] = message_show("fail", 'Some Error Occurred , try again after some time !');
                    redirect($redirect_url);
                    exit(0);
                }
                $result = $this->Esign_signature_model->esign_document_xml_response($txn, $data);

                $esigned_docs_details = $this->Affirmation_model->get_esign_doc_details($registration_id);

                if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_DEFICIT_COURT_FEE || $_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA || $_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {

                    if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
                        $sessoin_result = $this->Common_model->session_for_steps(MISC_BREAD_AFFIRMATION);
                    } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_DEFICIT_COURT_FEE) {
                        $sessoin_result = $this->Common_model->session_for_steps(DEFICIT_BREAD_AFFIRMATION);
                    } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
                        $sessoin_result = $this->Common_model->session_for_steps(IA_BREAD_AFFIRMATION);
                    }
                } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
                    if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE) {

                        if ($esigned_docs_details[0]->is_data_valid == 't') {
                            $sessoin_result = $this->Common_model->session_for_steps(NEW_CASE_AFFIRMATION);
                        } else {
                            $sessoin_result = TRUE;
                        }
                    } elseif ($_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON) {
                        $sessoin_result = $this->Common_model->session_for_steps(NEW_CASE_AFFIRMATION);
                    }
                }


                if ($result && $sessoin_result) {
                    unset($_SESSION['file_uploaded_done']);
                    $_SESSION['MSG'] = message_show("success", 'Document eSigned successfully !');

                    redirect($redirect_url);
                    exit(0);
                } else {
                    $_SESSION['MSG'] = message_show("fail", 'Some Error Occurred , try again after some time !');
                    redirect($redirect_url);
                    exit(0);
                }
            } else {
                $result = $this->Esign_signature_model->esign_document_xml_response($txn, $data);
                $errmsg1 = 'If eSign using Aadhaar will be failed two times due to any technical reasons. e-Verification using Mobile will be automatically displayed at the very same page to proceed further.';
                if ($errmsg == '' && $errcode == '111') {
                    $_SESSION['MSG'] = message_show("fail", 'Invalid Aadhaar Number !');
                } elseif ($errmsg == '') {

                    if (!ENABLE_EVERIFICATION_BY_MOBILE_OTP && ENABLE_EVERIFICATION_ON_ESIGN_FAIL) {
                        $_SESSION['MSG'] = message_show("fail", $errmsg1);
                    } else {
                        $_SESSION['MSG'] = message_show("fail", 'Some Error Occurred , try again after some time or contact Admin !');
                    }
                } else {
                    if (!ENABLE_EVERIFICATION_BY_MOBILE_OTP && ENABLE_EVERIFICATION_ON_ESIGN_FAIL) {
                        $_SESSION['MSG'] = message_show("fail", $errmsg . '<br>' . $errmsg1);
                    } else {
                        $_SESSION['MSG'] = message_show("fail", $errmsg);
                    }
                }
                redirect($redirect_url);
                exit(0);
            }
        } else {
            $_SESSION['MSG'] = message_show("fail", 'Some Error Occurred , try again after some time !');
            redirect($redirect_url);
            exit(0);
        }
    }

    function gen_application_number() {

        $this->db->SELECT('esign_appli_num,esign_appli_date');
        $this->db->WHERE('entry_for_type', $_SESSION['estab_details']['efiling_for_type_id']);
        $this->db->WHERE('ref_m_establishment_id', $_SESSION['estab_details']['efiling_for_id']);
        $this->db->FROM('efil.m_tbl_efiling_no');
        $query = $this->db->get();
        $row = $query->result_array();
        $pre_application_num = $row[0]['esign_appli_num'];
        $date = $row[0]['esign_appli_date'];

        if ($date < date('Y-m-d')) {
            $new_date = date('Y-m-d');
            $update_data = array('esign_appli_num' => 1,
                'esign_appli_date' => $new_date);
            $this->db->WHERE('esign_appli_num', $pre_application_num);
            $this->db->WHERE('esign_appli_date', $date);
            $this->db->WHERE('entry_for_type', $_SESSION['estab_details']['efiling_for_type_id']);
            $this->db->WHERE('ref_m_establishment_id', $_SESSION['estab_details']['efiling_for_id']);
            $this->db->UPDATE('efil.m_tbl_efiling_no', $update_data);
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                $this->gen_application_number();
            }
        } else {
            $gen_application_num = $pre_application_num + 1;
            $application_info = array('esign_appli_num' => $gen_application_num);

            $this->db->WHERE('esign_appli_num', $pre_application_num);
            $this->db->WHERE('entry_for_type', $_SESSION['estab_details']['efiling_for_type_id']);
            $this->db->WHERE('ref_m_establishment_id', $_SESSION['estab_details']['efiling_for_id']);
            $result = $this->db->UPDATE('efil.m_tbl_efiling_no', $application_info);
            if ($this->db->affected_rows() > 0) {
                return $gen_application_num;
            } else {
                $this->gen_application_number();
            }
        }
    }

}
