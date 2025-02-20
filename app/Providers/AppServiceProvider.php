<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

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
        Role::updated(fn() => Artisan::call('permission:cache-reset'));
        Permission::updated(fn() => Artisan::call('permission:cache-reset'));
    }
}
