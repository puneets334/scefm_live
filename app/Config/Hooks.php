<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;

class Hooks extends BaseConfig
{
    public array $hooks = [
        'post_controller' => [
            [
                'class'    => 'App\Config\CSPHook',
                'function' => 'addCSPHeaders',
                'filename' => 'CSPHook.php',
                'filepath' => 'Hooks',
            ],
        ],
    ];
}