<?php

namespace App\Controllers\CaseStatus;

use App\Controllers\BaseController;

class DefaultController extends BaseController {

    protected $session;
    protected $db;
    protected $slice;

    public function __construct() {

        $this->session = \Config\Services::session();
        $this->slice = [];
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT, USER_PDE);
        $this->showCaseStatus();
        if (in_array($_SESSION['login']['ref_m_usertype_id'], $users_array)) {
            $this->render('case_status.case_status_view');
        } else {
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    public function showCaseStatusCertificate()
    {
        $request_no=$_POST['request_no'];
        if (!empty($request_no) && $request_no!=null) {
            $recent_certificate_str = file_get_contents(API_PRISON.'/certificate_status/'.$request_no.'/SCIN01/1');
            $recent_certificate = json_decode($recent_certificate_str);
            $certificateData = $recent_certificate->data;
            $certificate_Message = $certificateData[0]->Message;
            if (!empty($certificate_Message)) {
                echo "1@@@".$certificate_Message;
            } else {
                echo "2@@@Some Error ! Please try after some time.";
            }
        } else {
            echo "2@@@Some Error ! Please try after some time.";
        }
    }

    public function showCaseStatus() {
        $users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT, USER_PDE);
        $diary_no = '';
        $diary_year = '';
        if (in_array($_SESSION['login']['ref_m_usertype_id'], $users_array)) {
            if(!empty($_POST["diary_no"])) {
                if(!empty($_POST["diary_year"])) {
                    $diary_no = escape_data($this->request->getPost("diary_no"));
                    $diary_year = escape_data($this->request->getPost("diary_year"));
                } else{
                    $diary_no = escape_data(SUBSTR($this->request->getPost("diary_no"), 0, strlen($this->request->getPost("diary_no")) - 4));
                    $diary_year = escape_data(SUBSTR($this->request->getPost("diary_no"), - 4));
                }
                $view_path = CASE_STATUS_API.'?d_no='.$diary_no.'&d_yr='.$diary_year;
                $case_status_content = file_get_contents($view_path);
                if(empty($case_status_content))
                    $case_status_content='Data Not Found';
            } else{
                $case_status_content='<p>Data Not Found</p>';
            }
            echo $case_status_content;
        } else {
            echo "Action Not Allowed";
            // return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    public function showPaperBook()
    {
        $users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT, USER_PDE,SR_ADVOCATE);
        $diary_no='';
        $diary_year='';
        if (in_array($_SESSION['login']['ref_m_usertype_id'], $users_array)) {
            if(!empty($_POST["diary_no"]) ) {
                if(!empty($_POST["diary_year"])) {
                    $diary_no=escape_data($this->request->getPost("diary_no"));
                    $diary_year=escape_data($this->request->getPost("diary_year"));
                } else {
                    $diary_no = escape_data(SUBSTR($this->request->getPost("diary_no"), 0, strlen($this->request->getPost("diary_no")) - 4));
                    $diary_year=escape_data(SUBSTR($this->request->getPost("diary_no"), - 4));
                }
                $diaryId=$diary_no.$diary_year;
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => CASE_PAPER_BOOK_API.$diaryId,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                ));
                $case_paper_book_content = curl_exec($curl);
                curl_close($curl);
                if (empty($case_paper_book_content))
                    $case_paper_book_content = 'Data Not Found';
            } else {
                $case_paper_book_content='<p>Data Not Found</p>';
            }
            echo $case_paper_book_content;
        } else {
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    public function get_case_status_other_tab_data()
    {
        $url='';
        $case_status_tab_content='';
        if(!empty($_POST["diaryno"]) && !empty($_POST["according_id"])) {
            $diary_no = escape_data($this->request->getPost("diaryno"));
            $accordingId = escape_data($this->request->getPost("according_id"));
            $accordingNt = escape_data($this->request->getPost("according_nt"));
            if($accordingId==($accordingNt+2)) $url="/get_indexing.php";
            if($accordingId==($accordingNt+3)) $url="/get_earlier_court.php";
            if($accordingId==($accordingNt+4)) $url="/get_connected.php";
            if($accordingId==($accordingNt+5)) $url="/get_listings.php";
            if($accordingId==($accordingNt+6)) $url="/get_ia.php";
            if($accordingId==($accordingNt+7)) $url="/get_court_fees.php";
            if($accordingId==($accordingNt+8)) $url="/get_notices.php";
            if($accordingId==($accordingNt+9)) $url="/get_defect.php";
            if($accordingId==($accordingNt+10)) $url="/get_judgement_order.php";
            if($accordingId==($accordingNt+11)) $url="/get_adjustment.php";
            if($accordingId==($accordingNt+12)) $url="/get_mention_memo.php";
            if($accordingId==($accordingNt+13)) $url="/get_restore.php";
            if($accordingId==($accordingNt+14)) $url="/get_drop.php";
            if($accordingId==($accordingNt+15)) $url="/get_appearance.php";
            if($accordingId==($accordingNt+16)) $url="/get_office_report.php";
            if($accordingId==($accordingNt+17)) $url="/get_similarities.php";
            if($accordingId==($accordingNt+18)) $url="/get_caveat.php";
            if($accordingId>1) {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => CASE_STATUS_ADDON_API . $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array('diaryno' => $diary_no),
                ));
                $case_status_tab_content = curl_exec($curl);
                curl_close($curl);
                if (empty($case_status_tab_content))
                    $case_status_tab_content = 'Data Not Found';
            }
        }
        echo $case_status_tab_content;
    }

}