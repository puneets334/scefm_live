<?php
namespace App\Controllers\Login;
use App\Controllers\BaseController;
use App\Models\Login\LoginModel;

class Logout extends BaseController {
    protected $session;
    protected $Login_model;
    public function __construct() {
        parent::__construct();
        $this->Login_model = new LoginModel();
        $this->session = \Config\Services::session();
    }

    public function index() {
        $this->logUser($action = 'logout');
        log_message('info', 'User Logged out at ' . date('d-m-Y') . ' at ' . date("h:i:s A") .getClientIP() . '</b><br>User Agent: <b>' . $_SERVER['HTTP_USER_AGENT']);
        if (JANPARICHAY_CLIENT_ACTIVE) {
            janparichay_logout_all_apps();
        } else {
            session()->destroy();
        }
        $cache = \Config\Services::cache();
        $cache->clean();
        return response()->redirect(base_url('/'));
    }

    private function logUser($action) {
        $data['log_id'] = (!empty(getSessionData('login')) && isset(getSessionData('login')['log_id'])) ? getSessionData('login')['log_id'] : '';
        $data['logout_time'] = date('Y-m-d H:i:s');
        $this->Login_model->logUser($action, $data);
    }

}

?>
