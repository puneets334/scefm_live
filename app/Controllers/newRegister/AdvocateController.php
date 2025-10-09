<?php

// if (!defined('BASEPATH'))
//     exit('No direct script access allowed');

namespace App\Controllers\NewRegister;

use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\NewRegister\Advocate_model;
use App\Models\FilingAdmin\FilingAdminModel;
use App\Models\SuperAdmin\SuperAdminModel;
use Hashids\Hashids;


class AdvocateController extends BaseController
{
    protected $Advocate_model;
    public function __construct()
    {

        parent::__construct();
        // $this->load->model('newregister/Advocate_model');
        $this->Advocate_model = new Advocate_model;
    }


    public function index()
    {
       
        $allowed_users = array(USER_ADMIN);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users)) {
            redirect('login');
            exit(0);
        }
        $data['advocate_details'] = $this->Advocate_model->get_advocate_details();
        return $this->render('newregister.new_register_view', $data);
    }




    public function view($id)
    {

        $allowed_users = array(USER_ADMIN);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users)) {
            redirect('login');
            exit(0);
        }

        // Decode the ID
        $hashids = new Hashids();
        $decodedId = $hashids->decode($id);

        // Check if decoding was successful
        if (empty($decodedId)) {
            return redirect()->to('error_page'); // Redirect to an error page if decoding fails
        }

        $data['profile'] = $this->Advocate_model->get_details_by_id($decodedId[0]); // Use the first element of the decoded array
        $data['uri_segment'] = $id;


        return $this->render('newregister.user_info_view', $data);
    }






    public function activate($id)
    {

        $hashids = new Hashids();
        $decodedId = $hashids->decode($id);
        if (empty($decodedId)) {
            return redirect()->to('error_page');
        }


        $decodedId = $decodedId[0];

        $advocateModel = new Advocate_model();
        $result = $advocateModel->activateById($decodedId);

        if ($result) {
            $this->session->setFlashdata('msg', '<div class="alert alert-success alert-dismissible text-center flashmessage" style="padding: 0px;" role="alert">Advocate Activate Successful!</div>');
        } else {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage" style="padding: 0px;" role="alert">Not Activate!</div>');
        }

        return redirect()->to('NewRegister/Advocate/view/' . $id);
    }

    public function deactivate($id)
    {

        $hashids = new Hashids();
        $decodedId = $hashids->decode($id);
        if (empty($decodedId)) {
            return redirect()->to('error_page');
        }
        $decodedId = $decodedId[0];

        $advocateModel = new Advocate_model();
        $result = $advocateModel->deactivateById($decodedId);

        if ($result) {
            $this->session->setFlashdata('deactivate_msg', '<div class="alert  alert-dismissible text-center flashmessage" style="padding: 0px;" role="alert">Advocate Deactivate Successful!</div>');
        } else {
            $this->session->setFlashdata('deactivate_msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage" style="padding: 0px;" role="alert">Not Deactivate!</div>');
        }

        return redirect()->to('NewRegister/Advocate/view/' . $id);
    }



    function users($account_status)
    {
        $data['advocate_details'] = $this->Advocate_model->get_advocate_details(url_decryption($account_status));

        $this->load->view('templates/header');
        $this->load->view('newregister/new_register_view', $data);
        $this->load->view('templates/footer');
    }
}
