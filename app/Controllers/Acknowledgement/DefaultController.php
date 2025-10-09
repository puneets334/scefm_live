<?php

namespace App\Controllers;

class DefaultController extends BaseController {

    public function __construct() {
        parent::__construct();        
    }

    public function index() {
        return redirect()->to(base_url('dashboard'));
        exit(0);
    }
   
}