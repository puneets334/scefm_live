<?php
namespace App\Controllers;
use \VektorMuhammadLutfi\CodeIgniterLogViewer\CILogViewer;

class LogViewerController extends BaseController
{
    public function index() {
        $logViewer = new CILogViewer();
        return $logViewer->showLogs();
    }
}