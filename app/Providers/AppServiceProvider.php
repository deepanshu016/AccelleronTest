<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\RateLimiter;
use App\Repositories\Contracts\EventRepositoryInterface;
use App\Repositories\Contracts\EventBookingRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\EventBookingService;
use App\Services\EventService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(EventBookingRepositoryInterface::class, EventBookingService::class);
        $this->app->bind(EventRepositoryInterface::class, EventService::class);
        $this->app->bind(UserRepositoryInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(2)->by($request->ip());
        });

    }
}
