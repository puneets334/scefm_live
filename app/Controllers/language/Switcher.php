<?php
namespace App\Controllers;

class Switcher extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function _remap($param = NULL) {
        $this->index($param);
    }

    function index($language = "") {

        $language = ($language != "") ? $language : "english";
        $this->session->set_userdata('site_lang', $language);
        redirect($_SERVER['HTTP_REFERER']);
    }

}
