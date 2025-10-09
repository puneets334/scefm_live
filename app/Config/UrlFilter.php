<?php

namespace Config;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class UrlFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $uri = $request->getUri();
        $segments = $uri->getSegments();

        // Sanitize each segment
        foreach ($segments as $key => $segment) {
            $cleanSegment = preg_replace('/[^a-zA-Z0-9~%.:_&?-]/', '', $segment);
            if ($segment !== $cleanSegment) {
                log_message('error', 'Malicious URI detected: ' . $uri->getPath());
                return redirect()->to('/');
                // throw new \CodeIgniter\HTTP\Exceptions\HTTPException('Invalid URI characters', 400);
            }
        }
        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the request
    }
}