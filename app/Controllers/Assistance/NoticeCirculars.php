<?php

namespace App\Controllers\Assistance;

use App\Controllers\BaseController;
use App\Models\Assistance\NoticeCiruclarsModel;
use App\Models\NewRegister\AdvocateModel;

class NoticeCirculars extends BaseController
{

    protected $NoticeCiruclarsModel;
    protected $AdvocateModel;
    protected $session;
    protected $config;
    protected $request;
    protected $validation;

    public function __construct()
    {
        parent::__construct();
        $this->NoticeCiruclarsModel = new NoticeCiruclarsModel();
        $this->AdvocateModel = new AdvocateModel;
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->validation = \Config\Services::validation();
        helper(['file']);
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        } else{
            is_user_status();
        }
        $allowed_users = array(USER_ADMIN, USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, SR_ADVOCATE, ARGUING_COUNSEL);
        if (empty(getSessionData('login')) || !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users)) {
            return response()->redirect(base_url('/'));
            exit(0);
        }
    }

    public function index()
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $allowed_users = array(USER_ADMIN, USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, SR_ADVOCATE, ARGUING_COUNSEL);
        if (empty(getSessionData('login')) || !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users)) {
            return response()->redirect(base_url('/'));
            exit(0);
        }
        $data['notice_circualrs'] = $this->NoticeCiruclarsModel->notice_circulars_list();
        $data['advocate_details'] = $this->AdvocateModel->get_advocate_details();
        $data['get_data'] = array();
        $this->render('assistance.notice_circulars_view', $data);
    }

    public function edit($id = NULL)
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if (empty(getSessionData('login')) || getSessionData('login')['ref_m_usertype_id'] != USER_ADMIN) {
            return response()->redirect(base_url('/'));
            exit(0);
        }
        if ($id != NULL) {
            $id = (int) url_decryption($id);
            $data['get_data'] = $this->NoticeCiruclarsModel->get_news_by_id($id);
        }
        $data['notice_circualrs'] = $this->NoticeCiruclarsModel->notice_circulars_list();
        $this->render('assistance.notice_circulars_view', $data);
    }

    public function add_notice_circurlar($edit_id = null)
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (empty(getSessionData('login')) || getSessionData('login')['ref_m_usertype_id'] != USER_ADMIN) {
            return response()->redirect(base_url('/'));
            exit(0);
        }
        $InputArray = $this->request->getPost();
        $this->validation->setRules([
            "news_title" => [
                "label" => "Title",
                "rules" => "required|trim|min_length[10]|max_length[240]|regex_match[/^[a-zA-Z0-9 .-]+$/]"
            ],
            "pdf_is" => [
                "label" => "Item",
                "rules" => "required|trim|in_list[Notice,Circular]"
            ],
        ]);
        if ($this->validation->withRequest($this->request)->run() === FALSE) {
            $this->session->setFlashdata('news_title', '<div class="text-danger">' . $this->validation->getError('news_title') . '</div>');
            $this->session->setFlashdata('news_doc', '<div class="text-danger">' . $this->validation->getError('news_doc') . '</div>');
            $this->session->setFlashdata('pdf_is', '<div class="text-danger">' . $this->validation->getError('news_view[]') . '</div>');
            $this->session->setFlashdata('deactivate_date', '<div class="text-danger">' . $this->validation->getError('deactivate_date') . '</div>');
            return redirect()->to(base_url('assistance/notice_circulars'));
            exit(0);
        } else {
            $is_notice = in_array('Notice', (array) $InputArray['pdf_is']) ? TRUE : FALSE;
            $is_circular = in_array('Circular', (array) $InputArray['pdf_is']) ? TRUE : FALSE;
            $deactivate_date = ($InputArray['deactivate_date']) ? date('Y-m-d', strtotime($InputArray['deactivate_date'])) : NULL;
            if (!empty($_FILES['news_doc']['name'])) {
                if ($msg = isValidPDF('news_doc')) {
                    $this->session->setFlashdata('MSG', $msg);
                    return redirect()->to(base_url('assistance/notice_circulars'));
                    exit(0);
                }
                $file_name = time() . rand() . ".pdf";
                $file_uploaded_path = 'news/' . $file_name;
            } elseif (empty($_FILES['news_doc']['name'])) {
                $this->session->setFlashdata('MSG', '<center><p style="background: #f2dede;border: #f2dede;color: black;">Select Pdf File is Requerd!</p></center>');
                return redirect()->to(base_url('assistance/notice_circulars'));
            } else {
                $file_name = NULL;
                $file_uploaded_path = NULL;
            }
            if ($edit_id == NULL || $edit_id == '') {
                $insert_array = array(
                    'news_title' => strtoupper(trim($InputArray['news_title'])),
                    'file_name' => $file_name,
                    'file_uploaded_path' => $file_uploaded_path,
                    'created_by' => getSessionData('login')['id'],
                    'create_by_ip' => $_SERVER['REMOTE_ADDR'],
                    'deactive_date' => $deactivate_date,
                    'is_notice' => $is_notice,
                    'is_circular' => $is_circular
                );
                $result = $this->NoticeCiruclarsModel->insert_news($insert_array, $_FILES['news_doc']['tmp_name']);
            } elseif (!empty(url_decryption($edit_id))) {
                $update_array = array(
                    'news_title' => strtoupper(trim($InputArray['news_title'])),
                    'file_name' => $file_name,
                    'file_uploaded_path' => 'news/' . $file_name,
                    'deactive_date' => $deactivate_date,
                    'is_notice' => $is_notice,
                    'is_circular' => $is_circular,
                    'updated_by' => getSessionData('login')['id'],
                    'update_date' => date('Y-m-d H:i:s'),
                    'update_by_ip' => $_SERVER['REMOTE_ADDR']
                );
                $result = $this->NoticeCiruclarsModel->update_news_by_id(url_decryption($edit_id), $update_array, $_FILES['news_doc']['tmp_name']);
            } else {
                $this->session->setFlashdata('MSG', '<div class="alert alert-danger text-center">Invalid Request.</div>');
                return redirect()->to(base_url('assistance/notice_circulars'));
                exit(0);
            }
            if ($result) {
                if (!empty(url_decryption($edit_id))) {
                    $this->session->setFlashdata('MSG', '<div class="alert alert-success text-center">Updated Successfully</div>');
                } else {
                    $this->session->setFlashdata('MSG', '<div class="alert alert-success text-center">Added Successfully</div>');
                }
                return redirect()->to(base_url('assistance/notice_circulars'));
                exit(0);
            } else {
                $this->session->setFlashdata('MSG', '<div class="alert alert-danger text-center">Some error ! Please try after some time.</div>');
                return redirect()->to(base_url('assistance/notice_circulars'));
                exit(0);
            }
        }
    }

    public function action($action)
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if (empty(getSessionData('login')) || getSessionData('login')['ref_m_usertype_id'] != USER_ADMIN) {
            return response()->redirect(base_url('/'));
            exit(0);
        }
        $action = explode('$$', url_decryption($action));
        if ($action[1] == 'D') {
            $update_array = array(
                'is_deleted' => TRUE,
                'deleted_by' => getSessionData('login')['id'],
                'deleted_on' => date('Y-m-d H:i:s'),
                'delete_ip' => $_SERVER['REMOTE_ADDR']
            );
        } elseif ($action[1] == 'D') {
            $update_array = array(
                'is_deleted' => TRUE,
                'deleted_by' => 0,
                'deleted_on' => NULL,
                'delete_ip' => NULL,
                'updated_by' => getSessionData('login')['id'],
                'update_date' => date('Y-m-d H:i:s'),
                'update_by_ip' => $_SERVER['REMOTE_ADDR']
            );
        } else {
            $this->session->setFlashdata('MSG', '<div class="alert alert-danger text-center">Invalid Request.</div>');
            return redirect()->to(base_url('assistance/notice_circulars'));
            exit(0);
        }
        $result = $this->NoticeCiruclarsModel->update_news_by_id($action[0], $update_array);
        if ($result) {
            $this->session->setFlashdata('MSG', '<div class="alert alert-success text-center">News Status Change Successfully</div>');
            return redirect()->to(base_url('assistance/notice_circulars'));
        } else {
            $this->session->setFlashdata('MSG', '<div class="alert alert-danger text-center">Something Wrong Please try Again.</div>');
            return redirect()->to(base_url('assistance/notice_circulars'));
        }
    }

    function view($id)
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $id = url_decryption($id);
        if (!$id) {
            $this->session->setFlashdata('MSG', '<div class="alert alert-danger text-center">Invalid Request.</div>');
            return redirect()->to(base_url('assistance/notice_circulars'));
            exit(0);
        } else {
            $result = $this->NoticeCiruclarsModel->get_news_by_id($id);
            if (isset($result) && !empty($result)) {
                $file_name = $result[0]['file_name'];
                $file_n = $result[0]['news_title'];
                $file_uploaded_path = base_url($result[0]['file_uploaded_path']);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $file_uploaded_path);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Temporary for testing
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Temporary for testing
                $file_content = curl_exec($ch);
                if ($file_content === false) {
                    die('cURL error: ' . curl_error($ch));
                }
                curl_close($ch);

                header("Content-Type: application/pdf");
                header("Content-Disposition: inline; filename=\"" . addslashes($file_n).".pdf");
                echo $file_content;
                exit;
            }
        }
    }
}