<?php

namespace App\Domains\Auth\Entities\City;

readonly class District
{
    public function __construct(
        private int $id,
        private string $name,
        private int $cityId,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCityId(): int
    {
        return $this->cityId;
    }

    public function isWardOfDistrict(Ward $ward): bool
    {
        return $this->id === $ward->getDistrictId();
    }
}
