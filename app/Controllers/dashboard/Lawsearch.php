<?php
namespace App\Controllers;

class Lawsearch extends BaseController {

    public function __construct() {
        parent::__construct();        
    }

    public function index() {
        
        $users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_PDE);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $users_array)) {
            redirect('login');
            exit(0);            
        } 
        
        $url = 'https://indiacode.nic.in/handle/123456789/1362//simple-search?query=' . urlencode($_POST['search_case']) . '&btngo=&searchradio=acts';
        /*echo'<script>
            window.open("https://indiacode.nic.in/handle/123456789/1362//simple-search?query=' . urlencode($_POST['search_case']) . '&btngo=&searchradio=acts");
            window.location.href="'.$_SERVER['HTTP_REFERER'].'";
          </script>';*/
        echo'<script>
            window.open("'.$url.'", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=500,width=700,height=700");
            window.location.href="'.isset($_SERVER['HTTP_REFERER']).'";
          </script>';
        
        
    }

}

?>
