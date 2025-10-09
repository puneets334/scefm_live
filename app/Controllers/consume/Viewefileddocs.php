<?php
namespace App\Controllers;

class Viewefileddocs extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->helper('functions');
        $this->load->model('consume/Consume_data_model');
        require_once APPPATH . 'third_party/SBI/Crypt/AES.php';
        require_once APPPATH . 'third_party/cg/AesCipher.php';

        $ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $refData = parse_url($ref);
        if ($refData['host'] != $_SERVER['SERVER_NAME'] && $refData['host'] != NULL) {

            redirect("login");
            exit(0);
        }
        if (count($_POST) > 0) {
            if ($_POST['CSRF_TOKEN'] != $_SESSION['csrf_to_be_checked']) {
                echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Error</title><style type="text/css"> ::selection { background-color: #E13300; color: white; } ::-moz-selection { background-color: #E13300; color: white; } body {  background-color: #fff;  margin: 40px;  font: 13px/20px normal Helvetica, Arial, sans-serif;  color: #4F5155;}a {  color: #003399;  background-color: transparent;  font-weight: normal;}h1 {  color: #444;  background-color: transparent;  border-bottom: 1px solid #D0D0D0;  font-size: 19px;  font-weight: normal;  margin: 0 0 14px 0;  padding: 14px 15px 10px 15px;}code {  font-family: Consolas, Monaco, Courier New, Courier, monospace;  font-size: 12px;  background-color: #f9f9f9;  border: 1px solid #D0D0D0;  color: #002166;  display: block;  margin: 14px 0 14px 0;  padding: 12px 10px 12px 10px;}#container {  margin: 10px;  border: 1px solid #D0D0D0;  box-shadow: 0 0 8px #D0D0D0;}p {  margin: 12px 15px 12px 15px;}</style></head><body>  <div id="container">    <h1>An Error Was Encountered</h1>    <p>The action you have requested is not allowed.</p>  </div></body></html>';
                exit(0);
            }
        }
        $_SESSION['csrf_to_be_checked'] = $this->security->get_csrf_hash();
    }

    public function _remap($doc_id) { 
        $this->view_docs($doc_id);
    }

    public function index() {
        //$this->view_docs('1LuQfuGKaUHu-S.vNYlF-A::');
    }

    public function view_docs($doc_id) {


        //---START : Decryption ID for view document and affirmation-----//
        $doc_id = str_replace('-', '/', $doc_id);
        $doc_id = str_replace(':', '=', $doc_id);
        $doc_id = str_replace('.', '+', $doc_id);
        $aes = new Crypt_AES();
        $secret = base64_decode(SBI_PAYMENT_DOUBLE_VARIFICATION_SECRET_KEY);
        $aes->setKey($secret);
        $EncryptTrans = $aes->decrypt(base64_decode($doc_id));
        $array_val = explode('|', $EncryptTrans);
        $responce_var = 'doc_id|type';
        $array_name = explode('|', $responce_var);
        $results = array_combine($array_name, $array_val);

       $doc_id = htmlentities(trim($results['doc_id']), ENT_QUOTES); 
       $type = htmlentities(trim($results['type']), ENT_QUOTES); 
        //---END : Decryption ID for view document and affirmation-----//

        if (!preg_match("/^[a-zA-Z0-9]*$/", $doc_id)) {
            echo "Wrong document id !";
            exit(0);
        }
        if ($type != '1' && $type != '2') {
            echo "Wrong file type to view file!";
            exit(0);
        }


        if ($type == '1') {
            if (filter_var($doc_id, FILTER_VALIDATE_INT)) {

                $view_docs = $this->Consume_data_model->view_docs($doc_id); //echo "<pre>"; print_r($view_docs); 
                if (!empty($view_docs)) {
                    if (isset($view_docs) && !empty($view_docs)) {

                        $file_name = $view_docs[0]->file_name; 
                        $file_path = $view_docs[0]->file_path;
                        //$get_app_server = 'http://127.0.0.1/sci_new';
                        
                      $file = base_url().$file_path.$file_name;

                        header("Content-Type: application/pdf");
                        header("Content-Disposition:inline;filename=$file_name");
                        header('Content-Transfer-Encoding: binary');
                        header('Accept-Ranges: bytes');
                        @readfile($file);
                        echo $file;
                    }
                } else {
                    echo 'Unauthorized Access !';
                    exit(0);
                }
            } else {
                echo "Invalid document id !";
                exit(0);
            }
        }
    }    
}
