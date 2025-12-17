<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        // Super Admin bypass: grant all permissions to users with 'superadmin' role
        Gate::before(function ($user, $ability) {
            try {
                if (method_exists($user, 'hasRole') && $user->hasRole('superadmin')) {
                    return true;
                }
            } catch (\Throwable $e) {
                // ignore if not applicable
            }
        });
    }
}
