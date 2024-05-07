<?php

namespace App\Domains\Auth\Entities\City;

readonly class City
{
    public function __construct(
        private int $id,
        private string $name
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

    public function isDistrictOfCity(District $district): bool
    {
        return $this->id === $district->getCityId();
    }
}
