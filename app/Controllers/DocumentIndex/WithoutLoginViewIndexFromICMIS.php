<?php
namespace App\Controllers;

class WithoutLoginViewIndexFromICMIS extends BaseController {

    public function __construct() {
        parent::__construct();


        /*$this->load->model('common/common_model');
        $this->load->model('common/Form_validation_model');
        $this->load->model('documentIndex/documentIndex_model');*/
        $this->load->model('documentIndex/DocumentIndex_Select_model');
        //$this->load->library('encrypt');
        //$this->load->library('encryption');
    }

    public function _remap($param = NULL) {


        if ($param == 'index') {

            echo "Invalid Access hulala!";
            exit(0);
        } else {
            $this->index($param);
        }
    }

    public function index($doc_id)
    {

       // $key = $this->config->item('encryption_key');
       // $decrypted_string = $this->encrypt->decode($doc_id , $key);
        //$decrypted_string = $this->encryption->decrypt($doc_id);
        $decrypted_string=base64_decode(urldecode($doc_id));



       // var_dump($decrypted_string);exit();


        $doc_details = $this->DocumentIndex_Select_model->get_index_item_file($decrypted_string);


        $file_partial_path = $doc_details[0]['file_path'];
        $file_name = $file_partial_path . $doc_details[0]['file_name'];
       // $doc_title = $_SESSION['efiling_details']['efiling_no'] . '_' . str_replace(' ', '_', $doc_details[0]['doc_title']) . '.pdf';

        $doc_title = str_replace(' ', '_', $doc_details[0]['doc_title']) . '.pdf';

        if (file_exists($file_name)) {

            header("Content-Type: application/pdf");
            header("Content-Disposition:inline;filename = $doc_title");
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            @readfile($file_name);
            echo $file_name;
        } else {
            echo "File does not exists !";
            exit(0);
        }

    }//End of function...

    


}
