<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

use Illuminate\Session\DatabaseSessionHandler;
use Illuminate\Support\Facades\Session;

// Custom handler jo guard-aware ho

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (file_exists(app_path('helpers.php'))) {
            require_once app_path('helpers.php');
        }
    }

    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            if ($user->id === 1) {
                return true;
            }

            if ($ability !== 'admin.dashboard' && str_contains($ability, '.') && Schema::hasTable('permissions')) {
                return $user->hasPermission($ability);
            }
        });

        Gate::define('admin.dashboard', function ($user) {
            return Schema::hasTable('permissions') && $user->permissions()->exists();
        });
    }
}