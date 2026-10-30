<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Har permission ke liye Gate define karein
        // (config/cache se list lo taake har request DB hit na ho)
        $permissionNames = cache()->rememberForever('permission_names', function () {
            return Permission::pluck('name')->toArray();
        });

        foreach ($permissionNames as $name) {
            Gate::define($name, function ($user) use ($name) {
                return $user->hasPermission($name);
            });
        }

        // Super admin bypass (user id 1)
        Gate::before(function ($user, $ability) {
            if ($user->id === 1) {
                return true;
            }
        });
    }
}