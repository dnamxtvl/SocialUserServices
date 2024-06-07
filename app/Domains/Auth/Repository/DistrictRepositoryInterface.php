<?php

namespace App\Domains\Auth\Repository;

use App\Domains\Auth\Entities\City\District;

interface DistrictRepositoryInterface
{
    public function findById(int $id): ?District;
}
