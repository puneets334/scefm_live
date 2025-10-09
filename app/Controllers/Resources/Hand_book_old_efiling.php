<?php

// if (!defined('BASEPATH')) exit('No direct script access allowed');

namespace App\Controllers\Resources;
use App\Controllers\BaseController;
use App\Models\Assistance\Notice_ciruclars_model;

class Hand_book_old_efiling extends BaseController {

    protected $Notice_ciruclars_model;
    
    public function __construct() {
        parent::__construct();
        // $this->load->helper();
        // $this->load->model('assistance/Notice_ciruclars_model');
        // $this->load->library('form_validation');
        // $this->load->helper('file');
        $this->Notice_ciruclars_model = new Notice_ciruclars_model();
        helper(['file']);
    }

    public function index() {
        // $data['notice_circualrs'] = $this->Notice_ciruclars_model->notice_circulars_list();
        // $this->load->view('templates/header');
        // $this->load->view('resources/hand_book_old_efiling', $data);
        // $this->load->view('templates/footer');
        $data['notice_circualrs'] = $this->Notice_ciruclars_model->notice_circulars_list();
        $this->render('resources.hand_book_old_efiling', compact('data'));
    }

}