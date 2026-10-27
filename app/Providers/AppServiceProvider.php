<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Load app settings into config so Blade can access easily
        if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
            $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
            config(['app.settings' => $settings]);
        }
    }
}
// <?php

// namespace App\Providers;

// use Illuminate\Support\ServiceProvider;
// use Illuminate\Pagination\Paginator;

// class AppServiceProvider extends ServiceProvider
// {
//     /**
//      * Register any application services.
//      */
//     public function register(): void
//     {
//         // Paginator::useBootstrapFive();
//         Paginator::useBootstrapFour();
//     }

//     /**
//      * Bootstrap any application services.
//      */
//     public function boot(): void
//     {
//         //
//     }
// }
