<?php

namespace App\Domains\Auth\ValueObjects;

readonly class Worker
{
    public function __construct(
        private int $organizationId,
        private int $unitRoomId,
        private ?int $positionId = null,
        private ?int $jobId = null,
    ) {
    }

    public function getJobId(): ?int
    {
        return $this->jobId;
    }

    public function getOrganizationId(): int
    {
        return $this->organizationId;
    }

    public function getUnitRoomId(): int
    {
        return $this->unitRoomId;
    }

    public function getPositionId(): ?int
    {
        return $this->positionId;
    }
}
