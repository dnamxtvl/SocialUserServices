<?php

namespace App\Domains\Auth\Entities\User;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

readonly class BlockUserLoginTemporary
{
    public function __construct(
        private string $userId,
        private Carbon $expiredAt,
        private ?Carbon $createdAt = null,
        private ?string $id = null,
        private ?string $ip = null,
    ) {
        $this->validateIp(ip: $this->ip);
    }

    private function validateIp(?string $ip): void
    {
        if (! is_null($ip)) {
            $validate = Validator::make(data: ['ip' => $ip], rules: ['ip' => 'ip']);

            if ($validate->fails()) {
                throw new InvalidArgumentException(message: 'Ip không hợp lệ.');
            }
        }
    }

    public function getExpiredAt(): Carbon
    {
        return $this->expiredAt;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->createdAt;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }
}
