<?php

namespace App\Services;

use Jenssegers\Blade\Blade;

class BladeService
{
    public static function getInstance()
    {
        $paths = [APPPATH . 'Views']; // Define the paths to your views
        $cachePath = WRITEPATH . 'cache/blade'; // Define the path for cache

        return new Blade($paths, $cachePath);
    }
}
