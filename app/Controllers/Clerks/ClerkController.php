<?php

namespace App\Controllers\Clerks;

use App\Controllers\BaseController;
use App\Models\Clerk\ClerkModel;

class ClerkController extends BaseController
{

    public $add_limit_str;
    public $add_clerk_limit;
    protected $Clerk_model;
    protected $encrypt;
    protected $validation;
    protected $request;

    public function __construct()
    {
        parent::__construct();
        $this->Clerk_model = new ClerkModel();
        $this->encrypt = \Config\Services::encrypter();
        $this->validation = \Config\Services::validation();
        $this->request = \Config\Services::request();
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        if (getSessionData('login')['ref_m_usertype_id'] != USER_ADVOCATE) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (empty(getSessionData('login')['department_id'])) {
            $this->add_limit_str = "You can engage only " . NON_GVT_AOR_LIMIT . " clerk(s).";
            $this->add_clerk_limit = NON_GVT_AOR_LIMIT;
        } else {
            $this->add_limit_str = "You can engage only " . GVT_AOR_LIMIT . " clerk(s).";
            $this->add_clerk_limit = GVT_AOR_LIMIT;
        }
    }

    public function add_clerk()
    {
        return response()->redirect(base_url('/'));
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $data                    = [];
        $clerks                  = $this->Clerk_model->getClerks();
        $data['clerks']          = $clerks;
        $data['add_limit_str']   = $this->add_limit_str;
        $data['add_clerk_limit'] = $this->add_clerk_limit;
        $limit_records           = $this->Clerk_model->add_clerks_count();
        $data['can_add_clerk']   = true;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($limit_records >= $this->add_clerk_limit) {
                $data['can_add_clerk'] = false;
                $this->session->setFlashdata('success', "You can't add more clerk, limit has been reached.");
                return redirect()->to(base_url('clerks/Clerk_Controller/add_clerk'));
                exit(0);
            }
            $first_name  = $this->request->getPost('first_name');
            $last_name   = $this->request->getPost('last_name');
            $mobile_no   = $this->request->getPost('mobile_no');
            $email_id    = strtoupper($this->request->getPost('email_id'));
            $gender      = $this->request->getPost('gender');
            $total_associations = $this->Clerk_model->total_clerk_associations($mobile_no, $email_id); //find the no of assications with aor
            if (isset($total_associations) && (count($total_associations) >= CLERK_ASSOCIATIONS)) {
                $association_message = 'Clerk is already associated with ';
                foreach ($total_associations as $key => $association) {
                    $name = ucwords(strtolower($association->aor_first_name . ' ' . $association->aor_last_name));
                    if ($key > 0) {
                        $association_message .= ' and ' . $name . ' (' . $association->aor_code . ').';
                    } else {
                        $association_message .= $name . ' (' . $association->aor_code . ') ';
                    }
                }
                $this->session->setFlashdata('error', $association_message);
                return redirect()->to(base_url('clerks/Clerk_Controller/add_clerk'));
                exit(0);
            }
            $this->session->setFlashdata('first_name', $first_name);
            $this->session->setFlashdata('last_name', $last_name);
            $this->session->setFlashdata('mobile_no', $mobile_no);
            $this->session->setFlashdata('email_id', $email_id);
            $this->session->setFlashdata('gender', $gender);
            $error_message_array  = [];
            $error_message_string = '';
            $validations = [];
            $validations += [
                "first_name" => [
                    "label" => "First Name",
                    "rules" => "required|min_length[3]|max_length[99]|validateAlphaNumericSingleDoubleQuotesBracketWithSpecialCharacters"
                ],
                "last_name" => [
                    "label" => "Last Name",
                    "rules" => "min_length[3]|max_length[99]|validateAlphaNumericSingleDoubleQuotesBracketWithSpecialCharacters"
                ],
                "email_id" => [
                    "label" => "Email",
                    "rules" => "min_length[6]|max_length[49]|valid_email|required"
                ],
                "mobile_no" => [
                    "label" => "Mobile number",
                    "rules" => "required|exact_length[10]|is_natural_no_zero"
                ],
                "gender" => [
                    "label" => "Gender",
                    "rules" => "required|in_list[M,F,O]"
                ],
            ];
            $this->validation->setRules($validations);
            if ($this->validation->withRequest($this->request)->run() === FALSE) {
                $errors = $this->validation->getErrors();
                foreach ($errors as $field => $error) {
                    if (strpos($error, '{field}')) {
                        $this->session->setFlashdata('input', '<div class="text-danger">' . $errors[$field] . '</div>');
                    }
                }
                if (count($errors) > 0) {
                    return redirect()->to(base_url('clerks/Clerk_Controller/add_clerk'));
                    exit(0);
                }
            }
            $user_already_exist = $this->Clerk_model->check_user_already_exists($mobile_no, $email_id);
            if (isset($user_already_exist)) {
                if ($user_already_exist->ref_m_usertype_id != USER_CLERK) {
                    $this->session->setFlashdata('error', 'This user is not a clerk, you can associate only clerk.');
                    return redirect()->to(base_url('clerks/Clerk_Controller/add_clerk'));
                    exit(0);
                }
                $clerk_already_exist = $this->Clerk_model->check_clerk_already_exists($user_already_exist->id);
                if ($clerk_already_exist) {
                    $already_associated_msg = '';
                    if ($user_already_exist->emailid == $email_id) {
                        $already_associated_msg = "Clerk is already associated with the email $email_id.";
                    } elseif ($user_already_exist->moblie_number == $mobile_no || $user_already_exist->userid == $mobile_no) {
                        $already_associated_msg = "Clerk is already associated with the mobile number $mobile_no.";
                    }
                    $this->session->setFlashdata('error', $already_associated_msg);
                    return redirect()->to(base_url('clerks/Clerk_Controller/add_clerk'));
                    exit(0);
                } else {
                    $response = $this->Clerk_model->add_only_clerk($user_already_exist->id);
                }
            } else {
                $one_time_password = 'Test@4321';
                $_SESSION['user_created_password'] = $one_time_password;
                $data = [
                    'userid'            => $mobile_no,
                    'password'          => hash('sha256', $one_time_password),
                    'ref_m_usertype_id' => USER_CLERK,
                    'first_name'        => strtoupper($first_name),
                    'last_name'         => strtoupper($last_name),
                    'moblie_number'     => $mobile_no,
                    'emailid'           => strtoupper($email_id),
                    'adv_sci_bar_id'    => NULL,
                    'aor_code'          => NULL,
                    'bar_reg_no'        => NULL,
                    'gender'            => $gender,
                    'photo_path'        => '',
                    'account_status'    => 0,
                    'is_active'         => 1,
                    'refresh_token'     => NULL,
                    'create_ip'         => get_client_ip(),
                    'is_first_pwd_reset' => false,
                ];
                $response = $this->Clerk_model->addClerk($data);
            }
            if ($response) {
                $name = $first_name . ' ' . $last_name;
                $this->session->setFlashdata('first_name', '');
                $this->session->setFlashdata('last_name', '');
                $this->session->setFlashdata('mobile_no', '');
                $this->session->setFlashdata('email_id', '');
                $this->session->setFlashdata('gender', '');
                $this->session->setFlashdata('success', "Clerk added successfully.");
                return redirect()->to(base_url('clerks/Clerk_Controller/add_clerk'));
            } else {
                $this->session->setFlashdata('error', "Something went wrong.");
                return redirect()->to(base_url('clerks/Clerk_Controller/add_clerk'));
            }
        }
        $this->render('clerk.add_clerk', $data);
    }

    public function engaged_disengaged_clerk()
    {
        $limit_records = $this->Clerk_model->add_clerks_count();
        $data = [];
        $ref_user_id = $this->request->getPost('ref_user_id');
        $is_engaged  = $this->request->getPost('is_engaged');
        $data['ref_user_id'] = $ref_user_id;
        $data['is_engaged']  = $is_engaged;
        if (!$is_engaged) {
            if ($limit_records >= $this->add_clerk_limit) {
                $this->session->setFlashdata('error', "You can't engage more clerk, limit has been reached.");
                return redirect()->to(base_url('clerks/Clerk_Controller/add_clerk'));
                exit(0);
            }
            $total_associations = $this->Clerk_model->total_clerk_associations_by_id($ref_user_id); //find the no of assications with aor
            if (isset($total_associations) && count($total_associations) >= CLERK_ASSOCIATIONS) {
                $association_message = 'Clerk is already associated with ';
                foreach ($total_associations as $key => $association) {
                    $name = ucwords(strtolower($association->first_name . ' ' . $association->last_name));
                    if ($key > 0) {
                        $association_message .= ' and ' . $name . ' (' . $association->aor_code . ').';
                    } else {
                        $association_message .= $name . ' (' . $association->aor_code . ') ';
                    }
                }
                $this->session->setFlashdata('error', $association_message);
                return redirect()->to(base_url('clerks/Clerk_Controller/add_clerk'));
                exit(0);
            }
        }
        $response = $this->Clerk_model->engaged_disengaged_clerk($data);
        if ($response) {
            if ($is_engaged) {
                $this->session->setFlashdata('success', "Clerk disengaged successfully.");
            } else {
                $this->session->setFlashdata('success', "Clerk engaged successfully.");
            }
            return redirect()->to(base_url('clerks/Clerk_Controller/add_clerk'));
        } else {
            $this->session->setFlashdata('error', "Something went wrong.");
            return redirect()->to(base_url('clerks/Clerk_Controller/add_clerk'));
        }
    }

    private function generateWebLink($id, $registrationCode)
    {
        $webLinkData = [
            'id' => $id,
            'registration_code' => $registrationCode
        ];
        $webLinkData = json_encode($webLinkData);
        $key = config('Encryption')->key;
        $encrypted_string = $this->encrypt->encrypt($webLinkData, $key);
        return base_url('/' . base64_encode($encrypted_string));
    }
}
