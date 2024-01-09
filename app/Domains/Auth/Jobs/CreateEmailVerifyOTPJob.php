<?php

namespace App\Domains\Auth\Jobs;

use App\Domains\Auth\Events\RegistedUserEvent;
use App\Domains\Auth\Repository\EmailVerifyOTPRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CreateEmailVerifyOTPJob
{
    public function __construct(
        private readonly ?Model $user
    ) {
    }

    public function handle(EmailVerifyOTPRepositoryInterface $emailVerifyOTPRepository): void
    {
        $code = rand(config('validation.verify_code.min_value'), config('validation.verify_code.max_value'));
        $expiredAt = now()->addHour();
        $user = $this->user;
        /**
         * @var User $user
         **/
        $emailVerifyOTPRepository->save(code: (string) $code, userId: $user->id, expiredAt: $expiredAt);
//        event(new RegistedUserEvent(user: $user, verifyCode: $code));
    }
}
