<?php

namespace App\Infrastructure\Repository;

use App\Domains\Auth\Entities\City\District as DistrictDomain;
use App\Domains\Auth\Repository\DistrictRepositoryInterface;
use App\Infrastructure\Models\District;

readonly class DistrictRepository implements DistrictRepositoryInterface
{
    public function __construct(
        private District $district
    ) {
    }

    public function findById(int $id): ?DistrictDomain
    {
        $district = $this->district->query()->find(id: $id);

        return is_null($district) ? null : new DistrictDomain(
            id: $district->getAttribute('id'),
            name: $district->getAttribute('name'),
            cityId: $district->getAttribute('city_id')
        );
    }
}
