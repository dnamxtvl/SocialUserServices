<?php

namespace App\Listeners;

use App\Domains\Auth\DTOs\SaveEmailVerifyOTPDTO;
use App\Domains\Auth\Repository\EmailVerifyOTPRepositoryInterface;
use App\Infrastructure\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Password;

readonly class SendEmailVerifyOTPNotification implements ShouldQueue
{
    public function __construct(
        private EmailVerifyOTPRepositoryInterface $emailVerifyOTPRepository,
    ) {
    }

    public function handle($event): void
    {
        $user = $event->user;
        /** @var User $user */
        $saveEmailVerifyDTO = new SaveEmailVerifyOTPDTO(
            code: $event->verifyCode,
            userId: $user->id,
            expiredAt: now()->addHour(),
            type: $event->type,
            token: Password::createToken(user: $user)
        );
        $this->emailVerifyOTPRepository->save(saveEmailVerify: $saveEmailVerifyDTO);
        if ($event->user instanceof MustVerifyEmail && ! $event->user->hasVerifiedEmail()) {
            //$user->sendEmailVerifyNotification(verifyCode: $event->verifyCode);
        }
    }
}
