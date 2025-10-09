<?php
namespace App\Controllers\Newcase;

use App\Controllers\ShilclientController;
class FeeVerifyLock_Controller extends ShilclientController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function feeVeryLock(){
        $postData = json_decode(file_get_contents('php://input'), true);
        $type = !empty($postData['type']) ? $postData['type'] : NULL;
        $receiptNumber = !empty($postData['receiptNumber']) ? $postData['receiptNumber'] : NULL;
        $diaryNo = !empty($postData['diaryNo']) ? $postData['diaryNo'] : NULL;
        $diaryYear = !empty($postData['diaryYear']) ? $postData['diaryYear'] : NULL;
        $output = false;
        $result ='';
        $status =false;
        if(isset($type) && !empty($type)){
            switch ($type){
                case 'verify' :
                    if(isset($receiptNumber) && !empty($receiptNumber)){
                        $result = $this->getStatus($receiptNumber);
                        if(isset($result) && !empty($result)){
                            $status = true;
                            $output = array('status'=>$status,'res'=>$result);
                        }
                        else{
                            $output = array('status'=>$status,'res'=>$result);
                        }
                    }
                    else{
                        $output = array('status'=>$status,'res'=>$result);
                    }
                break;
                case 'lock':
                    if(isset($diaryNo) && !empty($diaryNo) && isset($receiptNumber) && !empty($receiptNumber) && isset($diaryYear) && !empty($diaryYear)){
                        $result = $this->lockCertificate($diaryNo,$receiptNumber,$diaryYear);
                            if(isset($result) && !empty($result)){
                                $status = true;
                                $output = array('status'=>$status,'res'=>$result);
                            }
                            else{
                                $output = array('status'=>$status,'res'=>$result);
                            }
                    }
                    else{
                        $output = array('status'=>$status,'res'=>$result);
                    }
                break;
                default :
                    $output = array('status'=>$status,'res'=>$result);
            }
        }
        echo json_encode($output);
        exit(0);
    }

}