<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    /**
     * The directory that holds the Migrations and Seeds directories.
     */
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    /**
     * Lets you choose which connection group to use if no other is specified.
     */
    public string $defaultGroup = 'default';

    /**
     * The default database connection.
     *
     * @var array<string, mixed>
     */
    public array $default = [
        'DSN'          => '',
        'hostname'     => '10.192.105.208',
        'username'     => 'sci',
        'password'     => 'Efiling#uat208',
        'database'     => 'efiling_near',
        'DBDriver'     => 'Postgre',
        'cacheOn'       => false,
        'cacheDir' => '',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => (ENVIRONMENT !== 'development'),
        'charset'      => 'utf8',
        'DBCollat'     => 'utf8_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 5432,
        'numberNative' => false,
        'dateFormat'   => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    public array $readOnlyDb = [
        'DSN'          => '',
        'hostname'     => '10.192.105.208',
        'username'     => 'sci',
        'password'     => 'Efiling#uat208',
        'database'     => 'efiling_near',
        'DBDriver'     => 'Postgre',
        'cacheOn'       => false,
        'cacheDir' => '',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => (ENVIRONMENT !== 'development'),
        'charset'      => 'utf8',
        'DBCollat'     => 'utf8_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 5432,
        'numberNative' => false,
        'dateFormat'   => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    public array $sci_cmis_final = [
        'DSN'          => '',
        'hostname' => '10.192.105.149',
        'username' => 'readonly_user',
        'password' => 'sci_cmis@1234',
        'database' => 'sci_cmis_final',
        'DBDriver'     => 'MySQLi',
        'cacheOn'       => false,
        'cacheDir' => '',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => (ENVIRONMENT !== 'development'),
        'charset'      => 'utf8',
        'DBCollat'     => 'utf8_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 3306,
        'numberNative' => false,
        'dateFormat'   => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    // public array $sci_cmis_final = [
    //     'DSN'          => '',
    //     'hostname'     => '10.25.78.68',
    //     'username'     => 'postgres',
    //     'password'     => 'postgres',
    //     'database'     => 'sci_cmis_final_09_08_june',
    //     // 'database'     => 'sci_cmis_final_09_08',
    //     'DBDriver'     => 'Postgre',
    //     'cacheOn'       => false,
    //     'cacheDir' => '',
    //     'DBPrefix'     => '',
    //     'pConnect'     => false,
    //     'DBDebug'      => (ENVIRONMENT !== 'development'),
    //     'charset'      => 'utf8',
    //     'DBCollat'     => 'utf8_general_ci',
    //     'swapPre'      => '',
    //     'encrypt'      => false,
    //     'compress'     => false,
    //     'strictOn'     => false,
    //     'failover'     => [],
    //     'port'         => 5432,
    //     'numberNative' => false,
    //     'dateFormat'   => [
    //         'date'     => 'Y-m-d',
    //         'datetime' => 'Y-m-d H:i:s',
    //         'time'     => 'H:i:s',
    //     ],
    // ];

    public array $e_services = [
        'DSN'          => '',
        'hostname'     => '10.192.105.208',
        'username'     => 'sci',
        'password'     => 'Efiling#uat208',
        'database'     => 'e_services',
        'DBDriver'     => 'Postgre',
        'cacheOn'       => false,
        'cacheDir' => '',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => (ENVIRONMENT !== 'development'),
        'charset'      => 'utf8',
        'DBCollat'     => 'utf8_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 5432,
        'numberNative' => false,
        'dateFormat'   => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    public array $physical_hearing = [
        'dsn'  => '',
        'hostname'     => '10.192.105.208',
        'username'     => 'sci',
        'password'     => 'Efiling#uat208',
        'database' => 'physical_hearing',
        'DBDriver' => 'Postgre',
        'DBPrefix' => '',
        'pConnect' => FALSE,
        'DBDebug' => (ENVIRONMENT !== 'development'),
        'cacheOn' => FALSE,
        'cacheDir' => '',
        'charset' => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre' => '',
        'encrypt' => FALSE,
        'compress' => FALSE,
        'strictOn' => FALSE,
        'failover' => array(),
        'port'         => 5432,
        'numberNative' => false,
        'save_queries' => TRUE,
        'dateFormat'   => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
        'schema' => 'physical_hearing'
    ];

    public array $appearance = [
        'DSN' => '',
        'hostname'     => '10.192.105.208',
        'username'     => 'sci',
        'password'     => 'Efiling#uat208',
        'database' => 'db_appreance',
        'DBDriver' => 'Postgre',
        'cacheOn' => false,
        'cacheDir' => '',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug' => (ENVIRONMENT !== 'development'),
        'charset' => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre' => '',
        'encrypt' => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port' => 5432,
        'numberNative' => false,
        'dateFormat' => [
            'date' => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time' => 'H:i:s',
        ],
    ];

    //    /**
    //     * Sample database connection for SQLite3.
    //     *
    //     * @var array<string, mixed>
    //     */
    //    public array $default = [
    //        'database'    => 'database.db',
    //        'DBDriver'    => 'SQLite3',
    //        'DBPrefix'    => '',
    //        'DBDebug'     => true,
    //        'swapPre'     => '',
    //        'failover'    => [],
    //        'foreignKeys' => true,
    //        'busyTimeout' => 1000,
    //        'dateFormat'  => [
    //            'date'     => 'Y-m-d',
    //            'datetime' => 'Y-m-d H:i:s',
    //            'time'     => 'H:i:s',
    //        ],
    //    ];

    //    /**
    //     * Sample database connection for Postgre.
    //     *
    //     * @var array<string, mixed>
    //     */
    //    public array $default = [
    //        'DSN'        => '',
    //        'hostname'   => 'localhost',
    //        'username'   => 'root',
    //        'password'   => 'root',
    //        'database'   => 'ci4',
    //        'schema'     => 'public',
    //        'DBDriver'   => 'Postgre',
    //        'DBPrefix'   => '',
    //        'pConnect'   => false,
    //        'DBDebug'    => true,
    //        'charset'    => 'utf8',
    //        'swapPre'    => '',
    //        'failover'   => [],
    //        'port'       => 5432,
    //        'dateFormat' => [
    //            'date'     => 'Y-m-d',
    //            'datetime' => 'Y-m-d H:i:s',
    //            'time'     => 'H:i:s',
    //        ],
    //    ];

    //    /**
    //     * Sample database connection for SQLSRV.
    //     *
    //     * @var array<string, mixed>
    //     */
    //    public array $default = [
    //        'DSN'        => '',
    //        'hostname'   => 'localhost',
    //        'username'   => 'root',
    //        'password'   => 'root',
    //        'database'   => 'ci4',
    //        'schema'     => 'dbo',
    //        'DBDriver'   => 'SQLSRV',
    //        'DBPrefix'   => '',
    //        'pConnect'   => false,
    //        'DBDebug'    => true,
    //        'charset'    => 'utf8',
    //        'swapPre'    => '',
    //        'encrypt'    => false,
    //        'failover'   => [],
    //        'port'       => 1433,
    //        'dateFormat' => [
    //            'date'     => 'Y-m-d',
    //            'datetime' => 'Y-m-d H:i:s',
    //            'time'     => 'H:i:s',
    //        ],
    //    ];

    //    /**
    //     * Sample database connection for OCI8.
    //     *
    //     * You may need the following environment variables:
    //     *   NLS_LANG                = 'AMERICAN_AMERICA.UTF8'
    //     *   NLS_DATE_FORMAT         = 'YYYY-MM-DD HH24:MI:SS'
    //     *   NLS_TIMESTAMP_FORMAT    = 'YYYY-MM-DD HH24:MI:SS'
    //     *   NLS_TIMESTAMP_TZ_FORMAT = 'YYYY-MM-DD HH24:MI:SS'
    //     *
    //     * @var array<string, mixed>
    //     */
    //    public array $default = [
    //        'DSN'        => 'localhost:1521/XEPDB1',
    //        'username'   => 'root',
    //        'password'   => 'root',
    //        'DBDriver'   => 'OCI8',
    //        'DBPrefix'   => '',
    //        'pConnect'   => false,
    //        'DBDebug'    => true,
    //        'charset'    => 'AL32UTF8',
    //        'swapPre'    => '',
    //        'failover'   => [],
    //        'dateFormat' => [
    //            'date'     => 'Y-m-d',
    //            'datetime' => 'Y-m-d H:i:s',
    //            'time'     => 'H:i:s',
    //        ],
    //    ];

    /**
     * This database connection is used when running PHPUnit database tests.
     *
     * @var array<string, mixed>
     */
    public array $tests = [
        'DSN'         => '',
        'hostname'    => '127.0.0.1',
        'username'    => '',
        'password'    => '',
        'database'    => ':memory:',
        'DBDriver'    => 'SQLite3',
        'DBPrefix'    => 'db_',  // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS
        'pConnect'    => false,
        'DBDebug'     => true,
        'charset'     => 'utf8',
        'DBCollat'    => '',
        'swapPre'     => '',
        'encrypt'     => false,
        'compress'    => false,
        'strictOn'    => false,
        'failover'    => [],
        'port'        => 3306,
        'foreignKeys' => true,
        'busyTimeout' => 1000,
        'dateFormat'  => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    public function __construct()
    {
        parent::__construct();
        
        // Ensure that we always set the database group to 'tests' if
        // we are currently running an automated test suite, so that
        // we don't overwrite live data on accident.
        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
        }

        /*****start-Session Hijacking prevention*****/
        // if(isset($_SESSION['ipaddress']) && isset($_SESSION['useragent']) && isset($_SESSION['lastaccess'])){
        //     if ($_SERVER['REMOTE_ADDR'] != $_SESSION['ipaddress'])
        //     { session_unset(); session_destroy();
        //     } // Does user agent match?
        //     if ($_SERVER['HTTP_USER_AGENT'] != $_SESSION['useragent'])
        //     { session_unset(); session_destroy(); }
        //     // Is the last access over an hour ago?
        //     if (time() > ($_SESSION['lastaccess'] + 3600))
        //     {
        //         session_unset(); session_destroy();
        //     }
        //     else
        //     { $_SESSION['lastaccess'] = time(); }
        // }
        // else{

        //     $_SESSION['ipaddress']=$_SERVER['REMOTE_ADDR'];
        //     $_SESSION['useragent']=$_SERVER['HTTP_USER_AGENT'];
        //     $_SESSION['lastaccess']=time();
        // }
        /*****end-Session Hijacking prevention*****/
    }
}
