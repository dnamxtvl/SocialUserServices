<?php

namespace App\Infrastructure\Repository;

use App\Domains\Auth\Entities\City\City as CityDomain;
use App\Domains\Auth\Repository\CityRepositoryInterface;
use App\Infrastructure\Models\City;

readonly class CityRepository implements CityRepositoryInterface
{
    public function __construct(
        private City $city
    ) {
    }

    public function findById(int $id): ?CityDomain
    {
        $city = $this->city->query()->find(id: $id);

        return is_null($city) ? null : new CityDomain(
            id: $city->getAttribute('id'),
            name: $city->getAttribute('name')
        );
    }
}
