<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Search_result extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('search/Search_model');
    }

    public function index() {

        $array_users = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_ADMIN);
        if (!in_array($this->session->userdata['login']['ref_m_usertype_id'], $array_users)) {
            redirect('login');
            exit(0);
        }

        if (!isset($_POST['search'])) {
            $_SESSION['MSG'] = message_show("fail", "Search key not set!");
            redirect('login');
            exit(0);
        }

        $this->session->unset_userdata('search_key');
        $search_key = str_replace('-', '', $_POST['search']);
        if (preg_match('/[^0-9a-zA-Z\s.&_\/]/i', trim($search_key))) {
            $_SESSION['MSG'] = message_show("fail", "Please enter correct search key!");
            redirect('login');
            exit(0);
        } else {
            $search_key = array('search_key' => $search_key);
            $this->session->set_userdata($search_key);
            $search = $this->session->userdata['search_key'];
            if (isset($search)) {
                if ($this->session->userdata['login']['ref_m_usertype_id'] == USER_ADMIN) {
                    $data['result_data'] = $this->Search_model->get_search_results_admin($search);
                    $data['reason_list'] = $this->Search_model->get_reason_list($search);
                    $this->load->view('templates/admin_header');
                    $this->load->view('search/search_view_admin', $data);
                    $this->load->view('templates/footer');
                } else {
                    $created_by = $this->session->userdata['login']['id'];
                    $data['result_data'] = $this->Search_model->get_search_results_user($created_by, $search);
                    $this->load->view('templates/header');
                    $this->load->view('search/search_view_user', $data);
                    $this->load->view('templates/footer');
                }
            } else {
                $_SESSION['MSG'] = message_show("fail", "Search key not set!");
                redirect('login');
                exit(0);
            }
        }
    }

    public function search_efiling() {
        echo '<script>
            window.open("https://indiacode.nic.in/handle/123456789/1362//simple-search?query=' . urlencode($_POST['search_case']) . '&btngo=&searchradio=acts");
            window.location.href="' . $_SERVER['HTTP_REFERER'] . '";
          </script>';
    }

}

?>