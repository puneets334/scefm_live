<?php
namespace App\Controllers;

class DefaultController extends BaseController {

    public function __construct() {
        parent::__construct();        
    }

    public function index() {
        redirect('dashboard');
        exit(0);
    }

   

}
