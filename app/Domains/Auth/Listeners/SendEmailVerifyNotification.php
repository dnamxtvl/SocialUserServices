<?php

namespace App\Domains\Auth\Listeners;

use App\Domains\Auth\Events\RegistedUserEvent;
use App\Infrastructure\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailVerifyNotification implements ShouldQueue
{
    public function handle(RegistedUserEvent $event): void
    {
        if ($event->user instanceof MustVerifyEmail && ! $event->user->hasVerifiedEmail()) {
            $user = $event->user;
            /** @var User $user */
            $user->sendEmailVerifyNotification(verifyCode: $event->verifyCode);
        }
    }
}
