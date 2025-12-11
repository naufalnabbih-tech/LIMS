<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register dynamic Gates for all permissions
        Gate::before(function ($user, $ability) {
            // If the ability check matches our permission system, use hasPermission
            if ($user->hasPermission($ability)) {
                return true;
            }
            // Return null to continue checking other gates
            return null;
        });

        // Register @permission blade directive
        Blade::if('permission', function ($permission) {
            return auth()->check() && auth()->user()->hasPermission($permission);
        });

        // Register @anypermission blade directive
        Blade::if('anypermission', function (...$permissions) {
            return auth()->check() && auth()->user()->hasAnyPermission($permissions);
        });

        // Register @allpermissions blade directive
        Blade::if('allpermissions', function (...$permissions) {
            return auth()->check() && auth()->user()->hasAllPermissions($permissions);
        });
    }
}
