<?php

namespace App\Controllers\NewRegister;

use App\Controllers\BaseController;
use App\Models\NewRegister\AdvocateModel;

class AdvocateController extends BaseController
{

    protected $Advocate_model;

    public function __construct()
    {
        parent::__construct();
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $this->Advocate_model = new AdvocateModel;
    }

    public function index()
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $allowed_users = array(USER_ADMIN);
        if (empty(getSessionData('login')) || !in_array(getSessionData('login.ref_m_usertype_id'), $allowed_users)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $data['advocate_details'] = $this->Advocate_model->get_advocate_details();
        return $this->render('newregister.new_register_view', $data);
    }

    public function view($id)
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $allowed_users = array(USER_ADMIN);
        if (empty(getSessionData('login')) || !in_array(getSessionData('login.ref_m_usertype_id'), $allowed_users)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $decodedId = url_decryption($id);
        $id = $decodedId;
        $data['profile'] = $this->Advocate_model->get_details_by_id($id);
        $data['uri_segment'] = $id;
        return $this->render('newregister.user_info_view', $data);
    }

    public function activate($id)
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $decodedId = url_decryption($id);
        $id = $decodedId;
        if (empty($id)) {
            return redirect()->to(base_url('error_page'));
        }
        $result = $this->Advocate_model->activateById($id);
        if ($result) {
            $data['profile'] = $this->Advocate_model->get_details_by_id($id);
            $data['uri_segment'] = $id;
            $this->session->setFlashdata('msg', '<div class="alert alert-success alert-dismissible text-center flashmessage" style="padding: 0px;" role="alert">Advocate Activate Successful!</div>');
        } else {
            $data['profile'] = $this->Advocate_model->get_details_by_id($id);
            $data['uri_segment'] = $id;
            $this->session->setFlashdata('msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage" style="padding: 0px;" role="alert">Not Activate!</div>');
        }
        return $this->render('newregister.user_info_view', $data);
    }

    public function deactivate($id)
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $decodedId = url_decryption($id);
        $id = $decodedId;
        if (empty($id)) {
            return redirect()->to(base_url('error_page'));
        }
        $result = $this->Advocate_model->deactivateById($id);
        if ($result) {
            $data['profile'] = $this->Advocate_model->get_details_by_id($id);
            $data['uri_segment'] = $id;
            $this->session->setFlashdata('deactivate_msg', '<div class="alert  alert-dismissible text-center flashmessage" style="padding: 0px;" role="alert">Advocate Deactivate Successful!</div>');
        } else {
            $data['profile'] = $this->Advocate_model->get_details_by_id($id);
            $data['uri_segment'] = $id;
            $this->session->setFlashdata('deactivate_msg', '<div class="alert alert-danger alert-dismissible text-center flashmessage" style="padding: 0px;" role="alert">Not Deactivate!</div>');
        }
        return $this->render('newregister.user_info_view', $data);
    }

    function users($account_status)
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $data['advocate_details'] = $this->Advocate_model->get_advocate_details(url_decryption($account_status));
        return $this->render('newregister.new_register_view', $data);
    }
}
