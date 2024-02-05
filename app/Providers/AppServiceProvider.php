<?php

namespace App\Providers;

use App\Data\Observers\UserForgotPassWordLogObserver;
use App\Data\Observers\UserLoginHistoryObserver;
use App\Data\Repository\BlockUserLoginTemporaryRepository;
use App\Data\Repository\EmailVerifyOTPRepository;
use App\Data\Repository\UserForgotPasswordLogRepository;
use App\Data\Repository\UserLoginHistoryRepository;
use App\Data\Repository\UserRepository;
use App\Domains\Auth\Repository\BlockUserLoginTemporaryRepositoryInterface;
use App\Domains\Auth\Repository\EmailVerifyOTPRepositoryInterface;
use App\Domains\Auth\Repository\UserForgotPasswordLogRepositoryInterface;
use App\Domains\Auth\Repository\UserLoginHistoryRepositoryInterface;
use App\Domains\User\Repository\UserRepositoryInterface;
use App\Data\Models\UserForgotPasswordLog;
use App\Data\Models\UserLoginHistory;
use Illuminate\Support\Facades\DB;
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
        DB::whenQueryingForLongerThan(config('app.max_query_timeout'), function () {
            throw new TimeoutException('Database mất quá nhiều thời gian phản hồi.');
        });
        UserForgotPasswordLog::observe(UserForgotPassWordLogObserver::class);
        UserLoginHistory::observe(UserLoginHistoryObserver::class);

        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Database\\Factories\\'.class_basename($modelName).'Factory';
        });
    }
}
