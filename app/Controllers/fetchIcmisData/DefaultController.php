<?php
namespace App\Controllers;

class DefaultController extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->load->database();
       // $this->load->model('miscellaneous_docs/Get_details_model');
        $this->load->model('fetchIcmisData/fetchDataModel');
        $this->load->library('webservices/efiling_webservices');
        ///$this->load->model('fetchIcmisData/fetchDataModel');
    }
    public function index(){
        extract($_REQUEST);
        if(isset($requestType) && $requestType=='party'){
            $timestamp=$this->fetchDataModel->getMaxTimestamp();
            //$timestamp='2017-01-01';
            echo "Timestamp value is: ".$timestamp;
            $advPartyDetails=$this->efiling_webservices->getAdvPartyMapping($timestamp);
            //print_r($advPartyDetails);
            foreach($advPartyDetails as $index=>$detail){
                echo "Value is:".$index;
                $result=$this->fetchDataModel->updateAdvocatePartyDetails($detail);
            }
        }
        else{
            $aorDetails=$this->efiling_webservices->getBarTable();
            foreach($aorDetails as $detail){
                //var_dump($detail);
                $result = $this->fetchDataModel->insertRecord($detail,'efil.tbl_users_new');
                echo $result."<br/>";
            }
        }
    }
    public function test(){
        echo "test here.";
    }

}

?>
