<?php

// if (!defined('BASEPATH')) exit('No direct script access allowed');

namespace App\Controllers\Resources;

use App\Controllers\BaseController;
use App\Models\Assistance\NoticeCiruclarsModel;

class HandBookOldEfiling extends BaseController
{

    protected $NoticeCiruclarsModel;

    public function __construct()
    {
        parent::__construct();
        // $this->load->helper();
        // $this->load->model('assistance/NoticeCiruclarsModel');
        // $this->load->library('form_validation');
        // $this->load->helper('file');
        $this->NoticeCiruclarsModel = new NoticeCiruclarsModel();
        helper(['file']);
    }

    public function index()
    {
        // $data['notice_circualrs'] = $this->NoticeCiruclarsModel->notice_circulars_list();
        // $this->load->view('templates/header');
        // $this->load->view('resources/hand_book_old_efiling', $data);
        // $this->load->view('templates/footer');
        $data['notice_circualrs'] = $this->NoticeCiruclarsModel->notice_circulars_list();
        $this->render('resources.hand_book_old_efiling', compact('data'));
    }
}