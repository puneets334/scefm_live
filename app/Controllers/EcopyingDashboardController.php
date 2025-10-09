<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\AdjournmentLetter\AdjournmentModel;
use App\Models\Dashboard\StageslistModel;
use App\Models\Report\ReportModel;
use App\Models\Certificate\CertificateModel;
use App\Models\Mentioning\MentioningModel;
use App\Models\Citation\CitationModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Ecoping_webservices;
use Exception;
use stdClass;

class EcopyingDashboardController extends BaseController
{

    protected $CommonModel;
    protected $AdjournmentModel;
    protected $StageslistModel;
    protected $db;
    protected $ReportModel;
    protected $CertificateModel;
    protected $MentioningModel;
    protected $CitationModel;
    protected $efiling_webservices;
    protected $ecoping_webservices;
    public function __construct()
    {
        parent::__construct();
        $dbs = \Config\Database::connect();
        $this->db = $dbs->connect();
        $this->CommonModel = new CommonModel();
        $this->AdjournmentModel = new AdjournmentModel();
        $this->StageslistModel = new StageslistModel();
        $this->ReportModel = new ReportModel();
        $this->CertificateModel = new CertificateModel();
        $this->MentioningModel = new MentioningModel();
        $this->CitationModel = new CitationModel();
        $this->efiling_webservices = new Efiling_webservices();
        $this->ecoping_webservices = new Ecoping_webservices();
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        }
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT, USER_ADMIN, USER_ADMIN_READ_ONLY, USER_EFILING_ADMIN, SR_ADVOCATE, ARGUING_COUNSEL,AMICUS_CURIAE_USER,AUTHENTICATED_BY_AOR,APPEARING_COUNCIL, ARGUING_COUNSEL);
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return response()->redirect(base_url('ecopying_dashboard'));
            exit(0);
        }
    }

    public function ecopying_dashboard()
    {
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        }
        
        $allowed_users_array = array(AUTHENTICATED_BY_AOR,APPEARING_COUNCIL, ARGUING_COUNSEL);
        if(getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return response()->redirect(base_url('/'));
            exit(0);
        } 
    
        $advocate_id = getSessionData('login')['adv_sci_bar_id'];
        $sr_advocate_data = '';
        $mobile = $_SESSION['login']['mobile_number'];
        $email = $_SESSION['login']['emailid'];        
        $online=$this->ecoping_webservices->online($email,$mobile);
        $offline=$this->ecoping_webservices->offline($email,$mobile);
        $request=$this->ecoping_webservices->requests($email,$mobile);
        
        return $this->render('responsive_variant.dashboard.ecopying_dashboard', @compact('online','offline','request'));
    }
    
}