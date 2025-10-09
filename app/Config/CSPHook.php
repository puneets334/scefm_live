<?php

namespace App\Config;

use CodeIgniter\HTTP\ResponseInterface;

/**
 * CSP Hook to add Content Security Policy headers to the response
 */
class CSPHook
{
    /**
     * Adds CSP headers to the response after the controller is executed
     */
    public function addCSPHeaders(): void
    {
        // Get the response object
        $response = service('response');

        // Check if CSP is enabled
        if (config('App')->CSPEnabled) {
            $csp = $response->getCSP();

            // Allow all local JavaScript files (including assets/newAdmin/)
            $csp->addScriptSrc("'self'");

            // Allow all local CSS files (including assets/newAdmin/)
            $csp->addStyleSrc("'self'");

            // Allow images from self only (optional, for completeness)
            $csp->addImageSrc("'self'");

            // Set default source to self for other unspecified resources
            $csp->setDefaultSrc("'self'");

            // Optional: Set a report URI for CSP violations
            $csp->setReportURI('https://your-report-uri.com/report');

            // Optional: Enable report-only mode for testing
            // $csp->reportOnly(true);

            // Generate nonces for inline scripts/styles if needed
            $csp->generateNonces($response);
        }
    }
}