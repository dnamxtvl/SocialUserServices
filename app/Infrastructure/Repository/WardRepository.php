<?php

namespace App\Infrastructure\Repository;

use App\Domains\Auth\Entities\City\Ward as WardDomain;
use App\Domains\Auth\Repository\WardRepositoryInterface;
use App\Infrastructure\Models\Ward;

readonly class WardRepository implements WardRepositoryInterface
{
    public function __construct(
        private Ward $ward
    ) {
    }

    public function findById(int $id): ?WardDomain
    {
        $ward = $this->ward->query()->find(id: $id);

        return is_null($ward) ? null : new WardDomain(
            id: $ward->getAttribute('id'),
            name: $ward->getAttribute('name'),
            districtId: $ward->getAttribute('district_id')
        );
    }
}
