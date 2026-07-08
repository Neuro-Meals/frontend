<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Api\AuthApiService;
use App\Services\Api\AdminApiService;
use App\Services\Api\UserApiService;
use App\Services\Api\ReportsApiService;
use App\Services\Api\MealApiService;
use App\Services\Api\PlanApiService;
use App\Services\Api\SubscriptionApiService;
use App\Services\Api\RbacApiService;
use App\Services\Api\PaymentApiService;
use App\Services\Api\ProfileApiService;

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
        $this->app->singleton(MealApiService::class);
        $this->app->singleton(PlanApiService::class);
        $this->app->singleton(SubscriptionApiService::class);
        $this->app->singleton(RbacApiService::class);
        $this->app->singleton(PaymentApiService::class);
        $this->app->singleton(ProfileApiService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
