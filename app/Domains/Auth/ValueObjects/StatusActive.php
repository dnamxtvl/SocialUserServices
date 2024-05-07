<?php

namespace App\Domains\Auth\ValueObjects;

use Carbon\Carbon;

readonly class StatusActive
{
    public function __construct(
        private bool $isActive,
        private ?Carbon $latestLogin = null,
        private ?string $latestIpLogin = null,
        private ?Carbon $latestActiveAt = null,
    ) {
    }

    public function getLatestLogin(): ?Carbon
    {
        return $this->latestLogin;
    }

    public function getLatestIpLogin(): ?string
    {
        return $this->latestIpLogin;
    }

    public function getLatestActiveAt(): ?Carbon
    {
        return $this->latestActiveAt;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }
}
