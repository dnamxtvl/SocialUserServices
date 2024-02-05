<?php

namespace App\Domains\Auth\Jobs;

use App\Domains\Auth\Enums\AuthExceptionEnum;
use App\Domains\Auth\Enums\TypeCodeOTPEnum;
use App\Domains\Auth\Exceptions\InvalidOTPException;
use App\Domains\Auth\Exceptions\OTPExpiredException;
use App\Domains\Auth\Repository\EmailVerifyOTPRepositoryInterface;
use App\Data\Models\EmailVerifyOTO;
use App\Data\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckIsValidVerifyEmailOTPJob
{
    public function __construct(
        private readonly Model $user,
        private readonly string $verifyCode,
        private readonly TypeCodeOTPEnum $typeOTP
    ) {
    }

    public function handle(EmailVerifyOTPRepositoryInterface $emailVerifyOTPRepository): Model
    {
        $user = $this->user;
        /** @var User $user */
        $emailVerifyOTP = $emailVerifyOTPRepository->getQuery(
            filters: [
                'user_id' => $user->id,
                'code' => $this->verifyCode,
                'type' => $this->typeOTP->value,
            ]
        )->first();

        if (is_null($emailVerifyOTP)) {
            throw new InvalidOTPException(code: AuthExceptionEnum::INVALID_CODE->value);
        }

        /** @var EmailVerifyOTO $emailVerifyOTP */
        if (now()->gt(date: $emailVerifyOTP->expired_at)) {
            throw new OTPExpiredException(code: AuthExceptionEnum::OTP_EXPIRED->value);
        }

        if (! Password::tokenExists(user: $user, token: $emailVerifyOTP->token)) {
            throw new NotFoundHttpException(message: 'Token không tồn tại hoặc đã hết hạn!');
        }

        return $emailVerifyOTP;
    }
}
