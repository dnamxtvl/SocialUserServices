<?php

namespace App\Domains\Auth\ValueObjects;

readonly class Residence
{
    public function __construct(
        private int $cityId,
        private int $districtId,
        private int $wardId
    ) {
    }

    public function getCityId(): int
    {
        return $this->cityId;
    }

    public function getDistrictId(): int
    {
        return $this->districtId;
    }

    public function getWardId(): int
    {
        return $this->wardId;
    }
}
