<?php 
namespace Config;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class XSSFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
       {
           // Sanitize GET data
           if ($request->getMethod() === 'get') {
               $_GET = $this->sanitizeInput($_GET);
           }
           // Sanitize POST data
           if ($request->getMethod() === 'post') {
               $_POST = $this->sanitizeInput($_POST);
           }
           // Sanitize COOKIE data
           $_COOKIE = $this->sanitizeInput($_COOKIE);
       }
       private function sanitizeInput(array $data): array
       {
           return array_map(function($value) {
               return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
           }, $data);
       }
       public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
       {
           // Do something after the request
       }
}