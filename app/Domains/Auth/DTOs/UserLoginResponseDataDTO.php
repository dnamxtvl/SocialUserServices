<?php

namespace App\Domains\Auth\DTOs;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserLoginResponseDataDTO
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private readonly ?Model $user,
        private readonly string $token,
        private readonly Carbon $expiresAt,
    ) {}

    public function getUser(): ?Model
    {
        return $this->user;
    }

    public function toArray(): array
    {
        return [
            'user' => $this->user,
            'token' => $this->token,
            'expires_at' => $this->expiresAt->toDateTimeString(),
        ];
    }
}
