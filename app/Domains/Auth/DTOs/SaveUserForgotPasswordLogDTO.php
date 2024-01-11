<?php

namespace App\Domains\Auth\DTOs;

use App\Domains\Auth\Enums\TypeForgotPasswordLogEnum;

class SaveUserForgotPasswordLogDTO
{
    public function __construct(
        private readonly string $userId,
        private readonly string $ip,
        private readonly string $device,
        private readonly TypeForgotPasswordLogEnum $type,
        private readonly ?string $longitude = null,
        private readonly ?string $latitude = null,
    ) {
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getDevice(): string
    {
        return $this->device;
    }

    public function getLongitude(): string
    {
        return $this->longitude;
    }

    public function getLatitude(): string
    {
        return $this->latitude;
    }

    public function getType(): TypeForgotPasswordLogEnum
    {
        return $this->type;
    }
}
