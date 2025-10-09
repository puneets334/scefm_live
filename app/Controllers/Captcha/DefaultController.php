<?php

namespace App\Controllers\Captcha;

use App\Controllers\BaseController;

class DefaultController extends BaseController {

    public function index() {
        captcha_key();
    }

    public function get_code()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $captcha_code=$_SESSION['captcha'];
        if ($captcha_code && !empty($captcha_code) && $captcha_code !=null && strlen($captcha_code) !=0) {
            echo $captcha_code; exit();
        }else{
            echo '0';exit();
        }

    }

}

?>
