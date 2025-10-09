<?php

namespace App\Controllers\Appearance;

use App\Controllers\BaseController;
use App\Models\Appearance\AppearanceModel;
use App\Models\Profile\ProfileModel;

class ReportController extends BaseController
{

    protected $request;
    protected $AppearanceModel;
    protected $ProfileModel;
    protected $validation;
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->AppearanceModel = new AppearanceModel();
        $this->ProfileModel = new ProfileModel();
        $this->request = \Config\Services::request();
        $this->validation = \Config\Services::validation();
        $this->db = \Config\Database::connect('e_services');
        // if (empty(getSessionData('login'))) {
        //     return response()->redirect(base_url('/'));
        // } else {
        //     is_user_status();
        // }
    }

    public function index($request = null)
    {
        $otpRecords = [];
        $diaryRecords = [];
        $finalQuery = '';
        $totalRecords = 0;
        $reportType = $this->request->getGet('report_type', FILTER_SANITIZE_STRING) ?? 'otp';
        $perPage = $this->request->getGet('per_page', FILTER_SANITIZE_STRING) ?? 100;
        // OTP type
        if ($reportType === 'otp' && ($this->request->getGet('otp_mobile') || $this->request->getGet('otp_email') || $this->request->getGet('otp_login_time'))) {
            $data = [
                'mobile' => !empty($this->request->getGet('otp_mobile')) ? $this->request->getGet('otp_mobile', FILTER_SANITIZE_STRING) : '',
                'email' => !empty($this->request->getGet('otp_email')) ? $this->request->getGet('otp_email', FILTER_SANITIZE_STRING) : '',
                'loginDate' => !empty($this->request->getGet('otp_login_time')) ? $this->request->getGet('otp_login_time', FILTER_SANITIZE_STRING) : '',
            ];
            $otpRecords = $this->ProfileModel->getLoginLogDetails($data);
        }
        // diary type
        if ($reportType === 'diary' && ($this->request->getVar('diary_no') || $this->request->getVar('list_dates') || $this->request->getVar('aor_code'))) {
            $rules = [
                'list_dates' => ['permit_empty', 'string'],
                'aor_code'   => ['permit_empty', 'string'],
                'diary_no'   => ['permit_empty', 'string'],
            ];
            if (!$this->validation->setRules($rules)->run($this->request->getVar())) {
                return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
            }
            $listDates = $this->request->getVar('list_dates') ? explode(',', $this->request->getVar('list_dates')) : [];
            if (count($listDates) > 1 && empty($this->request->getVar('aor_code')) && empty($this->request->getVar('diary_no'))) {
                $this->validation->setError('aor_code', 'Either Aor AOR Code or Diary No is required when multiple list dates are selected.');
                return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
            }
            $diaryBuilder = $this->db->table('appearing_in_diary');
            if ($this->request->getVar('diary_no')) {
                $diaryBuilder->where('diary_no', $this->request->getVar('diary_no'));
            }
            if ($this->request->getVar('list_dates')) {
                $dates = explode(',', $this->request->getVar('list_dates'));
                $formattedDates = array_map(function ($date) {
                    return date('Y-m-d', strtotime($date));
                }, $dates);
                if (count($formattedDates) === 1) {
                    $diaryBuilder->where('list_date', $formattedDates[0]);
                } elseif (count($formattedDates) > 1) {
                    $diaryBuilder->whereIn('list_date', $formattedDates);
                }
            }
            if ($this->request->getVar('aor_code')) {
                $diaryBuilder->where('aor_code', $this->request->getVar('aor_code'));
            }
            $totalRecords = $diaryBuilder->countAllResults(false);
            if ($perPage === 'all') {
                $diaryRecords = $diaryBuilder->orderBy('entry_date', 'DESC')->get()->getResult();
            } else {
                $diaryBuilder->orderBy('entry_date', 'DESC');
            }
            $diaryRecords = $diaryBuilder->get()->getResult();
        }
        // Submission logs type
        if ($reportType === 'submissionlogs' && ($this->request->getVar('diary_no') || $this->request->getVar('list_dates') || $this->request->getVar('entry_dates') || $this->request->getVar('aor_code') || $this->request->getVar('advocate_name'))) {
            $rules = [
                'list_dates'  => ['permit_empty', 'string'],
                'entry_dates' => ['permit_empty', 'string'],
                'aor_code'    => ['permit_empty', 'string'],
                'diary_no'    => ['permit_empty', 'string'],
                'action_type' => ['permit_empty', 'string'],
            ];
            if (!$this->validation->setRules($rules)->run($this->request->getVar())) {
                return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
            }
            $listDates = $this->request->getVar('list_dates') ? explode(',', $this->request->getVar('list_dates')) : [];
            if (count($listDates) > 1 && empty($this->request->getVar('aor_code')) && empty($this->request->getVar('diary_no'))) {
                $this->validation->setError('aor_code', 'Either Aor AOR Code or Diary No is required when multiple list dates are selected.');
                return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
            }         
            $diaryQuery = $this->db->table('appearing_in_diary_log');
            if ($this->request->getVar('diary_no')) {
                $diaryQuery->where('diary_no', $this->request->getVar('diary_no'));
            }
            if ($this->request->getVar('list_dates')) {
                $dates = explode(',', $this->request->getVar('list_dates'));
                $formattedDates = array_map(function ($date) {
                    return date('Y-m-d', strtotime($date));
                }, $dates);
                if (count($formattedDates) === 1) {
                    $diaryQuery->where('list_date', $formattedDates[0]);
                } elseif (count($formattedDates) > 1) {
                    $diaryQuery->whereIn('list_date', $formattedDates);
                }
            }
            if ($this->request->getVar('entry_dates')) {
                $dates = explode(',', $this->request->getVar('entry_dates'));
                $formattedEntryDates = array_map(function ($date) {
                    return date('Y-m-d', strtotime($date));
                }, $dates);
                if (count($formattedEntryDates) === 1) {
                    // $diaryQuery->where('entry_date', $formattedEntryDates[0]);
                    $diaryQuery->where('entry_date >=', $formattedEntryDates[0] . ' 00:00:00');
                    $diaryQuery->where('entry_date <=', $formattedEntryDates[0] . ' 23:59:59');
                } elseif (count($formattedEntryDates) > 1) {
                    // $diaryQuery->whereIn("entry_date", $formattedEntryDates);
                    $diaryQuery->whereIn('entry_date >=', $formattedEntryDates . ' 00:00:00');
                    $diaryQuery->whereIn('entry_date <=', $formattedEntryDates . ' 23:59:59');
                }
            }
            if ($this->request->getVar('aor_code')) {
                $diaryQuery->where('aor_code', $this->request->getVar('aor_code'));
            }
            if ($this->request->getVar('advocate_name')) {
                $diaryQuery->where('advocate_name', 'like', '%' . $this->request->getVar('advocate_name') . '%');
            }
            if ($this->request->getVar('action_type')) {
                $diaryQuery->where('action_type', $this->request->getVar('action_type'));
            }
            $totalRecords = $diaryQuery->countAllResults(false);
            if ($perPage === 'all') {
                $diaryRecords = $diaryQuery->orderBy('action_timestamp', 'desc')->get()->getResult();
            } else {
                $diaryRecords = $diaryQuery->orderBy('action_timestamp', 'desc');
            }
            // pr($diaryQuery->getCompiledSelect());
            $diaryRecords = $diaryQuery->get()->getResult();
        }
        if ($reportType === 'otp' && ($this->request->getGet('otp_mobile') || $this->request->getGet('otp_email') || $this->request->getGet('otp_login_time'))) {
            return $this->render('appearance.reports.partials.otp-report', @compact('otpRecords', 'request'));
        } elseif ($reportType === 'diary' && ($this->request->getGet('diary_no') || $this->request->getGet('list_dates') || $this->request->getGet('aor_code'))) {
            return $this->render('appearance.reports.partials.diary-report', @compact('diaryRecords', 'request'));
        } elseif ($reportType === 'submissionlogs' && ($this->request->getGet('diary_no') || $this->request->getGet('list_dates') || $this->request->getGet('entry_dates') || $this->request->getGet('aor_code') || $this->request->getGet('advocate_name'))) {
            return $this->render('appearance.reports.partials.submission-logs-report', @compact('diaryRecords', 'request'));
        } else {
            return $this->render('appearance.reports.combined', @compact('otpRecords', 'diaryRecords', 'request'));
        }
    }
}
