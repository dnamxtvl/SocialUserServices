<?php

namespace App\Domains\Auth\DTOs;

use Illuminate\Database\Eloquent\Model;

class SaveUserLoginHistoryDTO
{
    public function __construct(
        private readonly Model $user,
        private readonly string $ip,
        private readonly string $device,
        private readonly ?string $longitude = null,
        private readonly ?string $latitude = null,
    ) {
    }

    public function getUser(): Model
    {
        return $this->user;
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
}
