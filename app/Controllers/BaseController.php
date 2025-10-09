<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\login\Login_model;
use Psr\Log\LoggerInterface;
use eftec\bladeone\BladeOne;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    private $templateEngine;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;
    protected $db;
    protected $load;
    protected $security;

    public function __construct() {
        $dbs = \Config\Database::connect();
        $this->db = $dbs->connect();
        $this->session = \Config\Services::session();
        // ini_set('MAX_EXECUTION_TIME', -1);
        // ini_set('memory_limit', '-1');
    }
    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // E.g.: $this->session = \Config\Services::session();
        $views = APPPATH . 'Views';
        $cache = WRITEPATH . 'cache';

        if (ENVIRONMENT === 'production') {
            $this->templateEngine = new BladeOne(
                $views,
                $cache,
                BladeOne::MODE_AUTO
            );
        } else {
            $this->templateEngine = new BladeOne(
                $views,
                $cache,
                BladeOne::MODE_DEBUG
            );
        }

        $this->templateEngine->pipeEnable = true;
        $this->templateEngine->setBaseUrl(base_url());

        $this->checkSession();

        // $ref = isset($_SERVER['HTTP_REFERER']);
        // $refData = parse_url($ref);
        // if (isset($refData['host']) != $_SERVER['SERVER_NAME'] && isset($refData['host']) != NULL) {
        //     redirect("login");
        //     exit(0);
        // }
        // if (count($_POST) > 0) {
        //     print_r($_POST);die;
        //     if ($_POST['CSRF_TOKEN'] != $_SESSION['csrf_to_be_checked']) {
        //         $this->session->set_flashdata('msg', 'Captcha expired !');
        //         redirect('login');
        //         exit(0);
        //     }
        // }
    }

    /**
     * This method render the template.
     *
     * @param string $filename - the filename of template.
     * @param array $params - the data with context of the template.
     * @return string
     */
    /* public function render(string $filename, array $params = []): string
    {
        try {
            // Render the template.
            return $this->templateEngine->run($filename, $params);
        } catch (\Throwable $e) {
            if (ENVIRONMENT === 'production') {
                // Save error in file log
                log_message('error', $e->getTraceAsString());

                throw PageNotFoundException::forPageNotFound();
            }

            // Show error in the current page
            return '<pre>' . $e->getTraceAsString() . '</pre>' . PHP_EOL . $e->getMessage();
        }
    } */
	
    protected function prepareViewData(array $data): array
    {
        $escapedData = [];
        foreach ($data as $key => $value) {
            $escapedData[$key] = is_string($value) ? esc($value) : $value;
        }
        return $escapedData;
    }

	public function render($view, $data = [])
    {
        $views = APPPATH . 'Views'; // Path to your views directory
        $cache = WRITEPATH . 'cache'; // Path to your cache directory

        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

        echo $blade->run($view, $this->prepareViewData($data));
    }

    protected function checkSession()
    {
        $session = session();
        $lastActivity = $session->get('last_activity');
        $timeout = 900; // 15 minutes in seconds
        
        if ($lastActivity && (time() - $lastActivity) > $timeout) {
            return redirect()->to('logout');
        } else {
            $session->set('last_activity', time());
        }
        
    }
	
    public function csrftoken()
    {
        return json_encode(['CSRF_TOKEN' => csrf_token(), 'CSRF_TOKEN_VALUE' => csrf_hash()]);
    }
	
}
