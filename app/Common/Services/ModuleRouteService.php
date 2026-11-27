<?php

namespace App\Common\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class ModuleRouteService
{
    /**
     * Register routes from all available modules.
     */
    public function register(): void
    {
        $modulesPath = app_path('Modules');

        if (!File::isDirectory($modulesPath)) {
            return;
        }

        foreach (File::directories($modulesPath) as $modulePath) {
            $routePath = $modulePath . '/Routes/web.php';

            if (!File::exists($routePath)) {
                continue;
            }

            Route::middleware('web')->group($routePath);
        }
    }
}
