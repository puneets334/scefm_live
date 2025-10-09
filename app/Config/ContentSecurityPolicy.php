<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Stores the default settings for the ContentSecurityPolicy, if you
 * choose to use it. The values here will be read in and set as defaults
 * for the site. If needed, they can be overridden on a page-by-page basis.
 *
 * Suggested reference for explanations:
 *
 * @see https://www.html5rocks.com/en/tutorials/security/content-security-policy/
 */
class ContentSecurityPolicy extends BaseConfig
{
    public bool $reportOnly = true;
    public ?string $reportURI = null;
    public bool $upgradeInsecureRequests = false;
    public $defaultSrc;
    public array $scriptSrc = ['self', 'nonce-{csp-script-nonce}'];
    public array $styleSrc = ['self', 'nonce-{csp-style-nonce}'];
    public $imageSrc = 'self';
    public $baseURI;
    public $childSrc = 'self';
    public $connectSrc = 'self';
    public $fontSrc;
    public $formAction = 'self';
    public $frameAncestors;
    public $frameSrc;
    public $mediaSrc;
    public $objectSrc = 'self';
    public $manifestSrc;
    public $pluginTypes;
    public $sandbox;
    public string $styleNonceTag = '{csp-style-nonce}';
    public string $scriptNonceTag = '{csp-script-nonce}';
    public bool $autoNonce = true;
}
