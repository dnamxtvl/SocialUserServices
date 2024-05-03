<?php

namespace App\Application\Providers;

use App\Infrastructure\Observers\UserForgotPassWordLogObserver;
use App\Infrastructure\Observers\UserLoginHistoryObserver;
use App\Infrastructure\Repository\BlockUserLoginTemporaryRepository;
use App\Infrastructure\Repository\EmailVerifyOTPRepository;
use App\Infrastructure\Repository\UserForgotPasswordLogRepository;
use App\Infrastructure\Repository\UserLoginHistoryRepository;
use App\Infrastructure\Repository\UserRepository;
use App\Domains\Auth\Repository\BlockUserLoginTemporaryRepositoryInterface;
use App\Domains\Auth\Repository\EmailVerifyOTPRepositoryInterface;
use App\Domains\Auth\Repository\UserForgotPasswordLogRepositoryInterface;
use App\Domains\Auth\Repository\UserLoginHistoryRepositoryInterface;
use App\Domains\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Models\UserForgotPasswordLog;
use App\Infrastructure\Models\UserLoginHistory;
use Illuminate\Database\Connection;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use SebastianBergmann\Invoker\TimeoutException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserLoginHistoryRepositoryInterface::class, UserLoginHistoryRepository::class);
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(EmailVerifyOTPRepositoryInterface::class, EmailVerifyOTPRepository::class);
        $this->app->singleton(UserForgotPasswordLogRepositoryInterface::class, UserForgotPasswordLogRepository::class);
        $this->app->singleton(BlockUserLoginTemporaryRepositoryInterface::class, BlockUserLoginTemporaryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DB::whenQueryingForLongerThan(config('app.max_query_timeout'), function (Connection $connection,  QueryExecuted $event) {
            Log::error(message: $event->sql . ' timeout ' . $event->time . ' in database ' . $event->connectionName);
        });
        UserForgotPasswordLog::observe(UserForgotPassWordLogObserver::class);
        UserLoginHistory::observe(UserLoginHistoryObserver::class);

        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Database\\Factories\\'.class_basename($modelName).'Factory';
        });
    }
}
