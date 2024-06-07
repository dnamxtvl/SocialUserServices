<?php

namespace App\Domains\Auth\Repository;

use App\Domains\Auth\Entities\City\City;

interface CityRepositoryInterface
{
    public function findById(int $id): ?City;
}
