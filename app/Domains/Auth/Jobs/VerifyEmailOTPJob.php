<?php

namespace App\Domains\Auth\Jobs;

use App\Domains\Auth\Enums\AuthExceptionEnum;
use App\Domains\Auth\Exceptions\InvalidOTPException;
use App\Domains\Auth\Exceptions\OTPExpiredException;
use App\Domains\Auth\Repository\EmailVerifyOTPRepositoryInterface;
use App\Models\EmailVerifyOTO;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class VerifyEmailOTPJob
{
    public function __construct(
        private readonly Model $user,
        private readonly string $verifyCode
    ) {
    }

    public function handle(EmailVerifyOTPRepositoryInterface $emailVerifyOTPRepository): void
    {
        $user = $this->user;
        /** @var User $user */
        $emailVerifyOTP = $emailVerifyOTPRepository->getQuery(
            filters: [
                'user_id' => $user->id,
                'code' => $this->verifyCode,
            ]
        )->first();

        if (is_null($emailVerifyOTP)) {
            throw new InvalidOTPException(code: AuthExceptionEnum::INVALID_CODE->value);
        }

        /** @var EmailVerifyOTO $emailVerifyOTP */
        if (now()->gt(date: $emailVerifyOTP->expired_at)) {
            throw new OTPExpiredException(code: AuthExceptionEnum::OTP_EXPIRED->value);
        }

        $user->email_verified_at = now();
        $user->save();

        $user->emailVerifyOTPs()->delete();
    }
}
