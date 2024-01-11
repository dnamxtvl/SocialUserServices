<?php

namespace App\Domains\Auth\DTOs;

use App\Domains\Auth\Enums\TypeCodeOTPEnum;
use Carbon\Carbon;

class SaveEmailVerifyOTPDTO
{
    public function __construct(
        private readonly string $code,
        private readonly string $userId,
        private readonly Carbon $expiredAt,
        private readonly TypeCodeOTPEnum $type,
        private readonly string $token
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getExpiredAt(): Carbon
    {
        return $this->expiredAt;
    }

    public function getType(): TypeCodeOTPEnum
    {
        return $this->type;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
