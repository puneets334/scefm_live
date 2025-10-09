<?php

namespace App\Controllers\Appearance;
use App\Controllers\BaseController;
use App\Models\Appearance\AppearanceModel;
use Carbon\Carbon;
use CodeIgniter\HTTP\Request;

class AppearanceController extends BaseController
{

    protected $request;   
    protected $AppearanceModel;
    protected $validation;

    public function __construct()
    {
        parent::__construct();
        $this->AppearanceModel = new AppearanceModel();            
        $this->request = \Config\Services::request();
        $this->validation = \Config\Services::validation();
        // if(empty(getSessionData('login'))) {
        //     return response()->redirect(base_url('/')); 
        // } else {
        //     is_user_status();
        // }
    }

    public function appearance_report($request = null) {
        $request = $this->request;
        [$todaySubmissions, $todaySelecteddate] = $this->today_submissions();
        if ($request->getMethod() == 'POST') {
            $rules = [
                'cause_list_from_date' => [
                    'rules' => 'required|date',
                    'errors' => [
                        'before_or_equal' => 'The from date must be before or equal to the to date.'
                    ]
                ],
                'cause_list_to_date' => [
                    'rules' => 'required|date',
                    'errors' => [
                        'after_or_equal' => 'The to date must be after or equal to the from date.'
                    ]
                ],
            ];
            $this->validation->setRules($rules);
            if (!$this->validation->withRequest($request)->run()) {
                return $this->response->setJSON(['status' => 'error', 'data' => $this->validation->getErrors()]);
            }
            $cause_list_from_date = date('Y-m-d', strtotime($this->request->getPost('cause_list_from_date')));
            $cause_list_to_date   = date('Y-m-d', strtotime($this->request->getPost('cause_list_to_date')));
            $totalSubmissions     = number_format($this->AppearanceModel->getTotalAppearings($cause_list_from_date,$cause_list_to_date));            
            return response()->setJSON([
                'success'            => true,
                'today_submissions'  => $todaySubmissions,
                'total_submissions'  => $totalSubmissions,
                'today_selected_date'=> date('d-m-Y',strtotime($todaySelecteddate))
            ]);
        } else {
            $heading              = "Appearance Slips At a Glance Since(".APPEARANCE_SUBMISSION.")";
            $cause_list_from_date = date('Y-m-d', strtotime(APPEARANCE_SUBMISSION));
            $cause_list_to_date   = date('Y-m-d');
            $totalSubmissions     = number_format($this->AppearanceModel->getTotalAppearings($cause_list_from_date,$cause_list_to_date));
            $weeklySubmissions    = $this->AppearanceModel->currentWeekLastWeekSubmissions();
            $today_selected_date  = date('d-m-Y',strtotime($todaySelecteddate));
            return $this->render('appearance.appearance_report', @compact('heading','cause_list_from_date','cause_list_to_date',
            'totalSubmissions','todaySubmissions','weeklySubmissions','today_selected_date'));
        }
    }

    private function today_submissions() {
        $date_num = 0;
        $hasTodaySubmissions = false;
        $todaySubmissions = 0;
        $selectedDate = '';
        while(!$hasTodaySubmissions){
            $todayDate = Carbon::now()->subDay($date_num)->format('Y-m-d');
            $todaySubmissions = number_format($this->AppearanceModel->getTotalAppearings($todayDate));
            $date_num++;
            if($todaySubmissions>0){
                $selectedDate = $todayDate;
                $hasTodaySubmissions = true; 
            }
        }
        return [$todaySubmissions,$selectedDate];
    }

    public function send_report_mail() {
        $totalSubmissions  = number_format($this->AppearanceModel->getTotalAppearings(date('Y-m-d', strtotime(APPEARANCE_SUBMISSION)),date('Y-m-d')));
        [$todaySubmissions,$todaySelecteddate] = $this->today_submissions();
        $weeklySubmissions = $this->AppearanceModel->currentWeekLastWeekSubmissions();
        $message      = $this->render('mails.appearance_report_mail', @compact('totalSubmissions', 'todaySubmissions', 'weeklySubmissions', 'todaySelecteddate'));
        $subject           = "Appearance Slips Submitted Between ".APPEARANCE_SUBMISSION." To ".date('Y-m-d');
        $to                = explode(',', SEND_TO_APPEARING_REPORT);
        $response          = sendMailJioCron($to,$subject,$message,[]);
        print_r($response);
        die;
    }

}