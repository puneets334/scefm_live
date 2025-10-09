<?php

namespace App\Controllers\AdminReport;

use App\Controllers\BaseController;
use App\Models\AdminReport\AdminSearchModel;
use App\Models\NewCase\DropdownListModel;
use App\Libraries\Zip;
use DateTime;

class Search extends BaseController
{
    protected $AdminSearchModel;
    protected $Dropdown_list_model;

    public function __construct()
    {
        parent::__construct();
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $this->AdminSearchModel = new AdminSearchModel();
        $this->Dropdown_list_model = new DropdownListModel();
        helper(['url']);
        ini_set('memory_limit', '51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        ini_set('max_execution_time', 0);
        error_reporting(2);
    }

    public function index()
    {
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $allowed_users_array = array(USER_ADMIN, USER_ADMIN_READ_ONLY);
        if (!empty(getSessionData('login')) && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }
        $data['sc_case_type'] = $this->Dropdown_list_model->get_sci_case_type();
        $this->render('adminReport.search', $data);
    }

    public function get_list_doc_fromDate_toDate()
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $data = array();
        $params['from_date'] = '';
        $params['to_date'] = '';
        $output = array();
        $validatinError = true;
        if (!empty($this->request->getPost('from_date'))) {
            $from_date = !empty($this->request->getPost('from_date')) ? date('Y-m-d', strtotime($this->request->getPost('from_date'))) : NULL;
            $params['from_date'] = $from_date;
        } else {
            $output['status'] = 'error';
            $output['id'] = 'from_date';
            $output['msg'] = 'Please select from date.';
            $validatinError = false;
            echo "1@@@" . 'Please select from date.';
            exit(0);
        }
        if (!empty($this->request->getPost('to_date'))) {
            $to_date_str = $this->request->getPost('to_date');
            $to_date = !empty($to_date_str) ? DateTime::createFromFormat('d/m/Y', $to_date_str) : NULL;
            if ($to_date) {
                $to_date = $to_date->format('Y-m-d');
            } else {
                $to_date = NULL; // Or handle the error as needed
            }
            $params['to_date'] = $to_date;
        } else {
            $output['status'] = 'error';
            $output['id'] = 'to_date';
            $output['msg'] = 'Please select to date.';
            $validatinError = false;
            echo "1@@@" . 'Please select to date.';
            exit(0);
        }
        $params['sc_case_type'] = $this->request->getPost('sc_case_type');
        $userNameArr = array();
        $doc_list = $this->AdminSearchModel->get_list_doc_fromDate_toDate($params);
        $data['doc_list'] = $doc_list;
        if (!empty($doc_list)) {
            $efilingcase = 'efilingcase';
            $est_dir = 'uploaded_docs/' . $efilingcase;
            if (!is_dir($est_dir)) {
                $uold = umask(0);
                if (!is_dir($efilingcase)) {
                    mkdir($efilingcase, 0777, true);
                }
                umask($uold);
            }
            foreach ($doc_list as $row) {
                $diarized_on = !empty($row['diarized_on']) ? date('d_m_Y', strtotime(str_replace('/', '_', $row['diarized_on']))) : '';
                $sc_diary_number = $row['sc_diary_num'] . $row['sc_diary_year'];
                $casetype = $row['casetype'];
                $path_main_file = $row['ducs_path'];
                if (!empty($diarized_on) && !empty($sc_diary_number) && !empty($casetype) && !empty($path_main_file)) {
                    $date_diarized_on_dir = $est_dir . '/' . $diarized_on;
                    $casetype_dir = $est_dir . '/' . $diarized_on . '/' . $casetype;
                    $sc_diary_number_dir = $est_dir . '/' . $diarized_on . '/' . $casetype . '/' . $sc_diary_number;
                    $path_main_fileExp = explode('/', $path_main_file);
                    $file_name = $path_main_fileExp[4];
                    $new_file_path = $sc_diary_number_dir . '/' . $file_name;
                    if (file_exists($path_main_file)) {
                        if (!is_dir($date_diarized_on_dir)) {
                            $uold = umask(0);
                            mkdir($date_diarized_on_dir, 0777, true);
                            umask($uold);
                        }
                        if (!is_dir($casetype_dir)) {
                            $uold = umask(0);
                            mkdir($casetype_dir, 0777, true);
                            umask($uold);
                        }
                        if (!is_dir($sc_diary_number_dir)) {
                            $uold = umask(0);
                            mkdir($sc_diary_number_dir, 0777, true);
                            umask($uold);
                        }
                        copy($path_main_file, $new_file_path);
                    }
                }
            }
            // File name
            $zipFolderName = 'efilingcase.zip';
            $folderPath = 'uploaded_docs/efilingcase/';
            $zipFileName = 'uploaded_docs/efilingcase.zip';
            // Use the zip command-line utility to create the archive
            if (is_dir($folderPath)) {
                $zip = new \ZipArchive();
                if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                    $this->addFilesToZip($zip, $folderPath);
                    $exitCode = $zip->close();
                    if ($exitCode === true) {
                        foreach ($doc_list as $row) {
                            $diarized_on = !empty($row['diarized_on']) ? date('d_m_Y', strtotime(str_replace('/', '_', $row['diarized_on']))) : '';
                            $sc_diary_number = $row['sc_diary_num'] . $row['sc_diary_year'];
                            $casetype = $row['casetype'];
                            $path_main_file = $row['ducs_path'];
                            if (!empty($diarized_on) && !empty($sc_diary_number) && !empty($casetype) && !empty($path_main_file)) {
                                $date_diarized_on_dir = $est_dir . '/' . $diarized_on;
                                $casetype_dir = $est_dir . '/' . $diarized_on . '/' . $casetype;
                                $sc_diary_number_dir = $est_dir . '/' . $diarized_on . '/' . $casetype . '/' . $sc_diary_number;
                                $path_main_fileExp = explode('/', $path_main_file);
                                $file_name = $path_main_fileExp[4];
                                $new_file_path = $sc_diary_number_dir . '/' . $file_name;
                                if (file_exists($new_file_path)) {
                                    unlink($new_file_path);
                                }
                                if (empty($sc_diary_number_dir)) {
                                    rmdir($sc_diary_number_dir);
                                }
                                if (empty($casetype_dir)) {
                                    rmdir($casetype_dir);
                                }
                                if (empty($date_diarized_on_dir)) {
                                    rmdir($date_diarized_on_dir);
                                }
                            }
                        }
                        $this->download();
                        echo "200@@@" . 'Archive created successfully.';
                        exit(0);
                    } else {
                        $error = '';
                        foreach ($output as $line) {
                            $error .= "<br>$line";
                        }
                        echo "1@@@" . $error . 'Error creating the archive.';
                        exit(0);
                    }
                } else {
                    echo "Failed to open ZIP file.";
                    exit(0);
                }
            }
        } else {
            echo "3@@@" . 'Data not found.';
            exit(0);
        }
    }

    function download()
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $zipFolderName = "efilingcase.zip";
        $folderPath = 'uploaded_docs/efilingcase.zip';
        if (file_exists($folderPath)) {
            // adjust the below absolute file path according to the folder you have downloaded
            // the zip file
            // I have downloaded the zip file to the current folder
            $absoluteFilePath = $folderPath;
            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private', false);
            // content-type has to be defined according to the file extension (filetype)
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . basename($zipFolderName) . '";');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($absoluteFilePath));
            readfile($absoluteFilePath);
            if (unlink($folderPath)) {
                //echo "success";
            } else {
                //echo "Failure";
            }
            return redirect()->to(base_url("adminReport/search"));
            exit();
        } else {
            echo 'filename not exist =' . $zipFolderName;
        }
    }

    function download_delete()
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $zipFolderName = "efilingcase.zip";
        $folderPath = 'uploaded_docs/efilingcase.zip';
        if (file_exists($folderPath)) {
            if (unlink($folderPath)) {
                echo "successfully deleted efilingcase.zip";
            } else {
                echo "Failure";
            }
        } else {
            echo 'filename not exist =' . $zipFolderName;
        }
    }

    private function addFilesToZip(\ZipArchive $zip, $dir, $basePath = '')
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $handle = opendir($dir);
        while (false !== ($entry = readdir($handle))) {
            if ($entry == '.' || $entry == '..') {
                continue;
            }
            $fullPath = $dir . '/' . $entry;
            $localPath = $basePath . ($basePath ? '/' : '') . $entry;
            if (is_dir($fullPath)) {
                $this->addFilesToZip($zip, $fullPath, $localPath);
            } else {
                if (file_exists($fullPath)) {
                    $zip->addFile($fullPath, $localPath);
                } else {
                    echo "File not found: $fullPath";
                }
            }
        }
        closedir($handle);
    }
}
