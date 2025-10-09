<?php

namespace App\Controllers\Resources;
use App\Controllers\BaseController;
use App\Models\AdjournmentLetter\AdjournmentModel;
use App\Models\Dashboard\StageslistModel;

class DefaultController extends BaseController {

    protected $Adjournment_model;
    protected $Stageslist_model;

    public function __construct() {
        parent::__construct();
        $this->Adjournment_model = new AdjournmentModel();
        $this->Stageslist_model = new StageslistModel();
        // $this->load->library('slice');
        // $this->load->model('adjournment_letter/Adjournment_model');
        // $this->load->model('dashboard/Stageslist_model');
    }

    public function index($tab = null) {
        if($tab == 'FAQs') {
            $this->render('resources.FAQ', compact('tab'));
        } else{
            $this->render('responsive_variant.resources.index', compact('tab'));
        }
    }

    public function view($tab = null) {
        $this->render('responsive_variant.resources.index', compact('tab'));
    }

}