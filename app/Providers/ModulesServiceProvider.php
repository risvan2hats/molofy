<?php

namespace App\Providers;

use App\Common\Services\ModuleRouteService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    /**
     * Register application module services.
     */
    public function register(): void
    {
        $this->app->singleton(ModuleRouteService::class);
    }

    /**
     * Bootstrap application modules.
     */
    public function boot(): void
    {
        $modulesPath = app_path('Modules');

        if (!File::isDirectory($modulesPath)) {
            return;
        }

        foreach (File::directories($modulesPath) as $modulePath) {
            $migrationsPath = $modulePath . '/Database/Migrations';

            if (File::isDirectory($migrationsPath)) {
                $this->loadMigrationsFrom($migrationsPath);
            }
        }

        $this->app->booted(function () {
            $this->app
                ->make(ModuleRouteService::class)
                ->register();
        });
    }
}
