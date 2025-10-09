<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\NocVakalatnamaModel;

class NocVakalatnama extends BaseController
{
    protected $NocVakalatnama_model;
    protected $slice;
    protected $agent;

    public function __construct()
    {
        parent::__construct();
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $this->NocVakalatnama_model = new NocVakalatnamaModel();
        $this->session = \Config\Services::session();
        $this->agent = \Config\Services::request()->getUserAgent();
    }

    public function index()
    {
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $allowed_users_array = array(USER_EFILING_ADMIN, USER_ADMIN);
        if (empty(getSessionData('login')) || !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('superAdmin'));
            exit(0);
        }
        $data = [];
        $case_details = '';
        $effilingNumber = '';
        if (isset($_POST['e_filing_num']) && !empty($_POST['e_filing_num'])) {
            $effilingNumber = $_POST['e_filing_num'];
        }
        if (isset($_POST['e_filing_num']) && !is_null($_POST['e_filing_num'])) {
            $data['case_details'] = $this->NocVakalatnama_model->get_case_details(trim($_POST['e_filing_num']));
            if (empty($data['case_details'])) {
                $data['effilingNumber'] = $effilingNumber;
                $this->session->setFlashdata('message', "<div class='alert alert-danger' role='alert'>No Result Found.</div>");
                return $this->render('noc_vakalatnama.get_efiling_no', $data);
                exit(0);
            } else {
                if ($data['case_details']) {
                    $data['aor_list'] = $this->NocVakalatnama_model->get_aor_users();
                }
            }
        }
        if (isset($_POST['registration_id'], $_POST['new_aor']) && !is_null($_POST['registration_id']) && !is_null($_POST['new_aor'])) {
            $result = $this->NocVakalatnama_model->update_aor_in_case(trim($_POST['registration_id']), $_POST['new_aor']);
            if ($result) {
                $this->session->setFlashdata('message', "<div class='alert alert-success' role='alert'>Status Updated Successfully .</div>");
            } else {
                $this->session->setFlashdata('message', "<div class='alert alert-danger' role='alert'>Something Wrong! Please Try again letter.</div>");
            }
        }
        $data['effilingNumber'] = $effilingNumber;
        return $this->render('noc_vakalatnama.get_efiling_no', $data);
    }

    public function get_transferred_cases()
    {
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        if (isset(getSessionData('login')['ref_m_usertype_id']) && getSessionData('login')['ref_m_usertype_id'] == USER_ADVOCATE) {
            $advocate_id = getSessionData('login')['id'];
            $get_transferred_cases = $this->NocVakalatnama_model->get_transferred_cases($advocate_id);
            $data['get_transferred_cases'] = $get_transferred_cases;
            return $this->render('responsive_variant.noc_vakalatnama.index', $data);
        } else {
            http_response_code(404);
        }
    }
}
