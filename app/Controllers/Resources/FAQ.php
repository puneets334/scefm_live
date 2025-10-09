<?php

namespace App\Controllers\Resources;
use App\Controllers\BaseController;
use App\Models\Assistance\NoticeCiruclarsModel;

class FAQ extends BaseController {

    protected $Notice_ciruclars_model;

    public function __construct() {
        parent::__construct();
        $this->Notice_ciruclars_model = new NoticeCiruclarsModel();
        helper(['file']);
    }

    public function index() {
        $data['notice_circualrs'] = $this->Notice_ciruclars_model->notice_circulars_list();
        $this->render('resources.FAQ', compact('data'));
    }

}