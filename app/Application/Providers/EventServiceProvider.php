<?php

namespace App\Application\Providers;

use App\Events\EmailNotVerifyEvent;
use App\Events\ForgotPasswordEvent;
use App\Events\RegistedUserEvent;
use App\Listeners\SendEmailVerifyOTPNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        RegistedUserEvent::class => [
            SendEmailVerifyOTPNotification::class,
        ],
        EmailNotVerifyEvent::class => [
            SendEmailVerifyOTPNotification::class,
        ],
        ForgotPasswordEvent::class => [
            SendEmailVerifyOTPNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
