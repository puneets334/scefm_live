<?php

// if (!defined('BASEPATH')) exit('No direct script access allowed');

namespace App\Controllers\Resources;

use App\Controllers\BaseController;
use App\Models\Assistance\NoticeCiruclarsModel;

class VideoTutorial extends BaseController
{

    protected $NoticeCiruclarsModel;

    public function __construct()
    {
        parent::__construct();
        // $this->load->helper();
        // //load the login model
        // $this->load->model('assistance/NoticeCiruclarsModel');
        // $this->load->library('form_validation');
        // $this->load->helper('file');
        $this->NoticeCiruclarsModel = new NoticeCiruclarsModel();
        helper(['file']);
    }

    public function index($tab = null)
    {
        // $this->slice->view('responsive_variant.resources.index', compact('tab'));
        $this->render('responsive_variant.resources.index', compact('tab'));
    }

    public function view()
    {
        $data['notice_circualrs'] = $this->NoticeCiruclarsModel->notice_circulars_list();
        // $this->load->view('templates/header');
        // $this->load->view('resources/video_tutorial', $data);
        // $this->load->view('templates/footer');
        $this->render('resources.video_tutorial', compact('data'));
    }
}