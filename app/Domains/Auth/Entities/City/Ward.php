<?php

namespace App\Domains\Auth\Entities\City;

readonly class Ward
{
    public function __construct(
        private int $id,
        private string $name,
        private int $districtId
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

    public function getDistrictId(): int
    {
        return $this->districtId;
    }
}
