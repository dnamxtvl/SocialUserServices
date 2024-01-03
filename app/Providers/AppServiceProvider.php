<?php

namespace App\Providers;

use App\Data\Repository\UserLoginHistoryRepository;
use App\Data\Repository\UserRepository;
use App\Domains\Auth\Repository\UserLoginHistoryRepositoryInterface;
use App\Domains\User\Repository\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserLoginHistoryRepositoryInterface::class, UserLoginHistoryRepository::class);
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
