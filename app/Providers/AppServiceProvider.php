<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Api\AuthApiService;
use App\Services\Api\AdminApiService;
use App\Services\Api\UserApiService;
use App\Services\Api\ReportsApiService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AuthApiService::class);
        $this->app->singleton(AdminApiService::class);
        $this->app->singleton(UserApiService::class);
        $this->app->singleton(ReportsApiService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
