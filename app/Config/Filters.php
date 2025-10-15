<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array<string, class-string|list<class-string>>
     *
     * [filter_name => classname]
     * or [filter_name => [classname1, classname2, ...]]
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
        'XSSFilter' => XSSFilter::class,
        'urlfilter' => UrlFilter::class,
    ];

    /**
     * List of special required filters.
     *
     * The filters listed here are special. They are applied before and after
     * other kinds of filters, and always applied even if a route does not exist.
     *
     * Filters set by default provide framework functionality. If removed,
     * those functions will no longer work.
     *
     * @see https://codeigniter.com/user_guide/incoming/filters.html#provided-filters
     *
     * @var array{before: list<string>, after: list<string>}
     */
    public array $required = [
        'before' => [
            'forcehttps', // Force Global Secure Requests
            'pagecache',  // Web Page Caching
        ],
        'after' => [
            'pagecache',   // Web Page Caching
            'performance', // Performance Metrics
            // 'toolbar',     // Debug Toolbar
        ],
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array<string, array<string, array<string, string>>>|array<string, list<string>>
     */
    public array $globals = [
        'before' => [
            // 'honeypot',
            'csrf' => ['except' => ['shcilPayment/paymentResponse','shcilPayment/paymentCheckStatus','newcase/Ajaxcalls/getAllFilingDetailsByRegistrationId','newcase/Ajaxcalls/updateDiaryDetails','newcase/Ajaxcalls/getAddressByPincode','newcase/Ajaxcalls/get_districts','newcase/Ajaxcalls/getSelectedDistricts','newcase/Ajaxcalls/getSelectedDistricts','newcase/Ajaxcalls_subordinate_court/[a-z_]+','affirmation/Esign_signature/advocate_esign_response','documentIndex/Ajaxcalls/get_index_type','documentIndex/Ajaxcalls/get_doc_type','documentIndex/Ajaxcalls/get_sub_doc_type_check', 'newcase/FeeVerifyLock_Controller/feeVeryLock','case_status/defaultController/showCaseStatus','documentIndex/Ajaxcalls/markCuredDefect','mycases/citation_notes/add_citation_mycases','mycases/citation_notes/get_citation_and_notes_list','mycases/citation_notes/delete_citation_n_notes','mycases/citation_notes/update_citation_mycases','mycases/citation_notes/add_notes_mycases','mycases/citation_notes/update_notes_mycases','mycases/citation_notes/get_contact_list','mycases/citation_notes/add_case_contact','mycases/citation_notes/delete_contacts','mycases/citation_notes/case_contact','mycases/citation_notes/update_case_contacts','mycases/citation_notes/send_sms_and_mail','newcase/Ajaxcalls/assignSrAdvocate','newcase/Ajaxcalls/deleteSrAdvocate','shareDoc/Ajaxcalls_doc_share/add_share_email','deficitCourtFee/DefaultController/record_data_deficit_insrt','deficitCourtFee/DefaultController/record_data_deficit_insrt_paid','register/AdvSignUp/add_advocate','shcilPayment/paymentCheckStatus','admin/EfilingAction/updateDocumentNumber','register/AdvSignUp/upload_photo','uploadDocuments/DefaultController/upload_pdf','uploadDocuments/DefaultController/deletePDF','newcase/AutoDiaryGeneration','newcase/AutoDiaryGeneration/updateOldEfilingRefiledCase','newcase/AutoDiaryGeneration/updateRefiledCase']],
            // 'invalidchars',
            'XSSFilter',
            'urlfilter',
        ],
        'after' => [
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'POST' => ['foo', 'bar']
     *
     * If you use this, you should disable auto-routing because auto-routing
     * permits any HTTP method to access a controller. Accessing the controller
     * with a method you don't expect could bypass the filter.
     *
     * @var array<string, list<string>>
     */
    public array $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array<string, array<string, list<string>>>
     */
    public array $filters = [];
}
