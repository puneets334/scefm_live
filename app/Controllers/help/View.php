<?php
namespace App\Controllers;

class View extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    function info() {

        $get_valu = help_id_url(uri_string());
        $help_page = explode('.', $get_valu);
        $data['help_page'] = $help_page[0];
        $this->load->view('help/help_view', $data);
    }

}
