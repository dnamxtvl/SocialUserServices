<?php

namespace App\Domains\Auth\Entities\User;

use App\Domains\Auth\Enums\AuthExceptionEnum;
use App\Domains\Auth\Enums\TypeCodeOTPEnum;
use App\Domains\Auth\Exceptions\OTPExpiredException;
use App\Infrastructure\Models\User as UserEloquent;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class EmailVerifyOTP
{
    public function __construct(
        private string $id,
        private string $code,
        private string $userId,
        private Carbon $expiredAt,
        private string $token,
        private TypeCodeOTPEnum $type
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpiredAt(): Carbon
    {
        return $this->expiredAt;
    }

    public function getType(): TypeCodeOTPEnum
    {
        return $this->type;
    }

    /**
     * @throws BindingResolutionException
     */
    public function isValidOTP(User $user): void
    {
        if (now()->gt(date: $this->expiredAt)) {
            throw new OTPExpiredException(code: AuthExceptionEnum::OTP_EXPIRED->value);
        }
        $userEloquent = $user->toEloquent();
        /** @var ?UserEloquent $userEloquent */
        if (! Password::tokenExists(user: $userEloquent, token: $this->token)) {
            throw new NotFoundHttpException(message: 'Token không tồn tại hoặc đã hết hạn!');
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'code' => $this->getCode(),
            'user_id' => $this->getUserId(),
            'expired_at' => $this->getExpiredAt(),
            'token' => $this->getToken(),
            'type' => $this->getType()->value,
        ];
    }
}
