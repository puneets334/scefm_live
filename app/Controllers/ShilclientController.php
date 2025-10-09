<?php
namespace App\Controllers;

use DOMDocument;
use PHPUnit\Framework\Exception;
use SimpleXMLElement;
use SoapClient; 
use Config\Database;
class ShilclientController extends BaseController
{
    //private $wsdl = "https://www.shcilestamp.com/SecfDHWS/EcfSWS?wsdl";  //URL given by NIC
    // private $wsdl = "https://dr.shcileservices.com/SecfDHWS/EcfSWS?wsdl"; //Testing URL
    private $wsdl = "https://www.shcileservices.com/SecfDHWS/EcfSWS?wsdl"; // Production URL
    private $username = 'ecfnicsws';
    private $locking_username = 'dlecfsupco';
    private $password = '96563666E69637377739';
    public function __construct(){
        ini_set('max_execution_time', -1); 
        parent::__construct();
       //$this->load->library("Nusoap_library");

    }
    private function request_certificate_xml($data_array){
        $domDoc = new DOMDocument();
        $domDoc->encoding = 'utf-8';
        $domDoc->xmlVersion = '1.0';
        $domDoc->formatOutput = true;
        $root = $domDoc->createElement('ECFWTXN');
        $node_txn_hdr = $domDoc->createElement('TXNHDR');
        $child_node_txn_msg_ver = $domDoc->createElement('MSGVER', 'ECFSHCIL001');
        $node_txn_hdr->appendChild($child_node_txn_msg_ver);
        $child_node_msg_type = $domDoc->createElement('MSGTP', 'CERTRQ');
        $node_txn_hdr->appendChild($child_node_msg_type);
        $child_node_timestamp = $domDoc->createElement('SENDTM', date('YmdH:i:s'));
        $node_txn_hdr->appendChild($child_node_timestamp);
        $root->appendChild($node_txn_hdr);

        $node_cert_request_detail = $domDoc->createElement('CERTRQDTL');
        $child_node_reciept_no = $domDoc->createElement('RCPTNO', $data_array['certificate_number']);
        $node_cert_request_detail->appendChild($child_node_reciept_no);
        $root->appendChild($node_cert_request_detail);
        $domDoc->appendChild($root);
        return $domDoc->saveXML();
    }
    private function lock_certificate_xml($data_array){
        $domDoc = new DOMDocument();
        $domDoc->encoding = 'utf-8';
        $domDoc->xmlVersion = '1.0';
        $domDoc->formatOutput = true;
        $root = $domDoc->createElement('ECFWTXN');
        $node_txn_hdr = $domDoc->createElement('TXNHDR');
        $child_node_txn_msg_ver = $domDoc->createElement('MSGVER', 'ECFSHCIL001');
        $node_txn_hdr->appendChild($child_node_txn_msg_ver);
        $child_node_msg_type = $domDoc->createElement('MSGTP', 'LOCKRQ');
        $node_txn_hdr->appendChild($child_node_msg_type);
        $child_node_timestamp = $domDoc->createElement('SENDTM', date('YmdH:i:s'));
        $node_txn_hdr->appendChild($child_node_timestamp);
        $child_node_lock_user = $domDoc->createElement('LCKUSR', $data_array['lock_user']);
        $node_txn_hdr->appendChild($child_node_lock_user);
        $child_node_diary_no = $domDoc->createElement('DIRNO', $data_array['diary_no']);
        $node_txn_hdr->appendChild($child_node_diary_no);

        $count_request = sizeof($data_array['lock_certificates_array']);
        $child_node_total_cert = $domDoc->createElement('CERTTOT', $count_request);
        $node_txn_hdr->appendChild($child_node_total_cert);
        $root->appendChild($node_txn_hdr);

        $node_lock_txn = $domDoc->createElement('LOCKTXN');
        for($i=0; $i<$count_request; $i++){
            $node_lock_req_detail = $domDoc->createElement('LOCKRQDTL');
            $child_node_sno = $domDoc->createElement('SRNO', ($i+1));
            $node_lock_req_detail->appendChild($child_node_sno);
            $child_node_rcptno = $domDoc->createElement('RCPTNO', $data_array['lock_certificates_array'][$i]['reciept_no']);
            $node_lock_req_detail->appendChild($child_node_rcptno);
            $child_node_diryear = $domDoc->createElement('DIRYEAR', $data_array['lock_certificates_array'][$i]['diryear']);
            $node_lock_req_detail->appendChild($child_node_diryear);
            $node_lock_txn->appendChild($node_lock_req_detail);
        }
        $root->appendChild($node_lock_txn);

        $domDoc->appendChild($root);
        return $domDoc->saveXML();
    }
    public function get_response($method, $data_array){
        try{
            $options = array(
                'soap_version'=>SOAP_1_1,
                'exceptions'=>true,
                'trace'=>1,
                'cache_wsdl'=>WSDL_CACHE_NONE
            );
            $client = new SoapClient($this->wsdl, $options);
        }
        catch (Exception $e) {
            /*echo "<h2>Exception Error!</h2>";
            echo $e->getMessage();*/
            return "<h2>Exception Error!</h2>".$e->getMessage();
        }

        try {
            if($method=='request'){
                //print_r($this->request_certificate_xml($data_array));
                $this->request_certificate_xml($data_array);
                $response=$client->ecfreq(array("v_Usr"=>"$this->username", "v_Pwd"=>"$this->password", "v_Ecf_Req"=>$this->request_certificate_xml($data_array)));
            }
            else if($method=='lock'){
                //print_r($this->lock_certificate_xml($data_array));
                $this->lock_certificate_xml($data_array);
                $response=$client->ecflock(array("v_Usr"=>"$this->username", "v_Pwd"=>"$this->password", "v_Ecf_Lock_Req"=>$this->lock_certificate_xml($data_array)));
            }
        }
        catch (Exception $e){
           // echo 'Caught exception: ',  $e->getMessage(), "\n";
            return 'Caught exception: '.','.$e->getMessage().','."\n";
        }
        return $response->return;
    }
    public function show_certificate_details($cert_response){
        $cert_response = new SimpleXMLElement($cert_response);
        //var_dump($cert_response);
        if($cert_response->CERTRPDTL->RPSTATUS == "SUCCESS"){
            /*echo 'Receipt No: '. $cert_response->CERTRPDTL->RCPTNO.'<br/>';
            echo 'Request Status: ' . $cert_response->CERTRPDTL->RPSTATUS .'<br/>';
            echo 'Status: ' . $cert_response->CERTRPDTL->STATUS .'<br/>';
            echo 'Name: '. $cert_response->CERTRPDTL->CFLNAME.'<br/>';
            echo 'Reciept Number: ' . $cert_response->CERTRPDTL->RCPTNO .'<br/>';
            echo 'Date of Issue: ' . $cert_response->CERTRPDTL->DTISSUE .'<br/>';
            echo 'Certificate Amount: ' . $cert_response->CERTRPDTL->CFAMT .'<br/>';*/
            return 'Receipt No: '. $cert_response->CERTRPDTL->RCPTNO.'<br/>'.
            'Request Status: ' . $cert_response->CERTRPDTL->RPSTATUS .'<br/>'.
            'Status: ' . $cert_response->CERTRPDTL->STATUS .'<br/>'.
            'Name: '. $cert_response->CERTRPDTL->CFLNAME.'<br/>'.
            'Reciept Number: ' . $cert_response->CERTRPDTL->RCPTNO .'<br/>'.
            'Date of Issue: ' . $cert_response->CERTRPDTL->DTISSUE .'<br/>'.
            'Certificate Amount: ' . $cert_response->CERTRPDTL->CFAMT .'<br/>';
        }
        else{
            return "Error: ".$cert_response->CERTRPDTL->RPSTATUS;
        }
    }
    public function getStatus($certificate_number){
        $cert_response = $this->get_response('request', array('certificate_number'=>$certificate_number));

        //libxml_use_internal_errors(true);
        $xml = @simplexml_load_string($cert_response, "SimpleXMLElement", LIBXML_NOCDATA);

        if($xml === false){
            return 0;
            //$errors = libxml_get_errors();
            //echo 'Errors are'.var_export($errors, true);
        }
        $this->saveVerificationStatus($cert_response,$certificate_number);
        return $xml; // $json = json_encode($xml);
    }
    public function lockCertificate($diary_no,$receipt_number,$diary_year){
        //$lock_response = $this->get_response('lock', array('lock_user'=>'dllcksupcousr', 'diary_no'=>$diary_no, 'lock_certificates_array'=>array(array('reciept_no'=>$receipt_number, 'diryear'=>$diary_year) )));
        $lock_response = $this->get_response('lock', array('lock_user'=>$this->locking_username, 'diary_no'=>$diary_no.$diary_year, 'lock_certificates_array'=>array(array('reciept_no'=>$receipt_number, 'diryear'=>$diary_year) )));
        $xml = simplexml_load_string($lock_response, "SimpleXMLElement", LIBXML_NOCDATA);
        $this->saveLockingStatus($lock_response,$receipt_number);
        return $xml;
        $LOCKTXN_xml=$xml->LOCKTXN; $LOCKTXN_JsonE_xml= json_encode($LOCKTXN_xml); $LOCKTXN_JsonD_xml= json_decode($LOCKTXN_JsonE_xml);
        $LOCKTXN_data=array();
        foreach ($LOCKTXN_JsonD_xml as $key => $value) { $LOCKTXN_data=array('SRNO'=>$value->SRNO,'RCPTNO'=>$value->RCPTNO,'CFLNAME'=>$value->CFLNAME,'DIRYEA'=>$value->DIRYEAR,'RPSTATUS'=>$value->RPSTATUS);}
        //return $LOCKTXN_data; //
        return $xml; // $json = json_encode($xml);
    }
    private function saveVerificationStatus($cert_response,$certificate_number){
        $cert_response = new SimpleXMLElement($cert_response);
        $status=$cert_response->CERTRPDTL->RPSTATUS;
        //var_dump($status);exit();
        $ifVerified='f';
        if($status=="SUCCESS"){
            $ifVerified='t';
        }
        if (!empty($status) && $status!=null && $status=="SUCCESS" && !empty($certificate_number) && $certificate_number!=null){
        $dataFee=array(
            'is_verified'=>$ifVerified,
            'verification_status'=>$status,
            'verified_on'=>date('Y-m-d H:i:s'),
            );
            $db = Database::connect();
            $builder = $db->table('efil.tbl_court_fee_payment');
            $builder->WHERE('transaction_num', $certificate_number);
            $builder->SET($dataFee);
            $result= $builder->UPDATE();
            if($result){
                return true;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
        //var_dump($result);exit();
    }
    private function saveLockingStatus($lock_response,$receipt_number){

        $lock_response = new SimpleXMLElement($lock_response);
        //$status=$lock_response->CERTRPDTL->RPSTATUS;
        $RPSTATUS=null;
        $RCPTNO=null;
        $LOCKTXN=$lock_response->LOCKTXN;
        $LOCKTXN_JsonE= json_encode($LOCKTXN);
        $LOCKTXN_JsonD= json_decode($LOCKTXN_JsonE);
        foreach ($LOCKTXN_JsonD as $key => $value) {
             $SRNO=$value->SRNO;
             $RCPTNO=$value->RCPTNO;
             $CFLNAME=$value->CFLNAME;
             $DIRYEAR=$value->DIRYEAR;
             $RPSTATUS=$value->RPSTATUS;
        }
        $ifLocked='f';

        if($RPSTATUS=="SUCCESS" && $receipt_number==$RCPTNO){ //when repeat lock then true if($RPSTATUS=="Receipt is already locked." && $receipt_number==$RCPTNO){
            $ifLocked='t';
        }else if($RPSTATUS=="Receipt is already locked." && $receipt_number==$RCPTNO){
            $ifLocked='t'; //$RPSTATUS="SUCCESS";
        }
        if (!empty($RPSTATUS) && $RPSTATUS!=null && !empty($receipt_number) && $receipt_number!=null && !empty($RCPTNO) && $RCPTNO!=null && $receipt_number==$RCPTNO){
            $dataFee=array(
                'is_locked'=>$ifLocked,
                'locking_status'=>$RPSTATUS,
                'locked_on'=>date('Y-m-d H:i:s'),
            );

            $db = Database::connect();
            $builder = $db->table('efil.tbl_court_fee_payment');
            $builder->WHERE('transaction_num', $receipt_number);
            $builder->SET($dataFee);
            $result= $builder->UPDATE();
            if($db->affectedRows() > 0){
                return true;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
        //var_dump($result);exit();
    }
//    private function saveLockingStatus($lock_response,$receipt_number){
//        $cert_response = new SimpleXMLElement($lock_response);
//        //echo "Lock status is: ".$cert_response->LOCKTXN->LOCKRPDTL->RPSTATUS;
//        $status=$cert_response->LOCKTXN->LOCKRPDTL->RPSTATUS;
//        $ifLocked='f';
//        if($status=="SUCCESS"){
//            $ifLocked='t';
//        }
//        $sql="update efiling_court_fee set is_locked='$ifLocked',locking_status='$status',locked_on=CURRENT_TIMESTAMP() where transaction_num='". $receipt_number ."'";
//        mysql_query($sql);
//    }
}