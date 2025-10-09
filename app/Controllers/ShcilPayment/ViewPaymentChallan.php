<?php
namespace App\Controllers\ShcilPayment;
use App\Controllers\BaseController;

class ViewPaymentChallan extends BaseController {

    public function __construct() {
        parent::__construct();
        //$this->load->model('shcilPayment/Payment_model');
    }
    
    // public function _remap($param = NULL) {
        
    //     if($this->uri->total_segments()== 3){             
    //          $this->index($this->uri->segment(3));
    //     }
    // }

    public function index() {

        /*$allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
        if (in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            $redirectURL = 'dashboard';
        } elseif ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN_READ_ONLY || $_SESSION['login']['ref_m_usertype_id'] == USER_EFILING_ADMIN) {
            $redirectURL = 'adminDashboard';
        } else {
            redirect('login');
            exit(0);
        }*/
        $segment = service('uri');
        // pr(url_decryption($segment->getSegment(3)));
        // pr(explode('$$',url_decryption($segment->getSegment(3))));
        $shcilrefno = explode('$$',url_decryption($segment->getSegment(3)));
        //  0 => order_no , 1 => received_amt
        if (empty($shcilrefno[0]) || empty($shcilrefno[1])) {
            echo 'ERROR|Data tempered';
            $_SESSION['MSG'] = message_show("fail", htmlentities('Data tempered .', ENT_QUOTES));
            //redirect($redirectURL);
            return redirect()->to(base_url('/'));
            exit(0);
        }

        $otherInfo = array(
            'EST_CODE' => 'SCIN01',
            'EST_NAME' => 'SUPREME COURT OF INDIA');

        $addInfo = json_encode($otherInfo);
        $post_param = "userid=" . STOCK_HOLDING_LOGIN . "&shcilrefno=" . trim($shcilrefno[0]) . "&amt=" . trim($shcilrefno[1]) . "&addInfo=" . $addInfo;
        $url = STOCK_HOLDING_PAYMENT_CHALLAN_URL.'?'.$post_param;
        return redirect()->to($url);
        // header('Location: '.$url);
        //echo '<script>window.open("''")</script>';

        /*$ch = curl_init($url);

        //curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $post_param);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        header("Content-Type: application/pdf");
        header("Content-Disposition:attachment;filename=CHALLAN_" . $shcilrefno[0] . ".pdf");
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        echo $result;

        curl_close($ch);*/
    }

}
