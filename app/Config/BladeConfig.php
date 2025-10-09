<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use App\Services\BladeService;

class BladeConfig extends BaseService
{
    public static function blade($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('blade');
        }

        return BladeService::getInstance();
    }
}
