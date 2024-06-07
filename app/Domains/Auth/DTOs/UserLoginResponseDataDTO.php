<?php

namespace App\Domains\Auth\DTOs;

use App\Domains\Auth\Entities\User\User;
use Carbon\Carbon;

readonly class UserLoginResponseDataDTO
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private ?User $user,
        private string $token,
        private Carbon $expiresAt,
    ) {
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function toArray(): array
    {
        return [
            'user' => $this->user->toArray(),
            'token' => $this->token,
            'expires_at' => $this->expiresAt->toDateTimeString(),
        ];
    }
}
