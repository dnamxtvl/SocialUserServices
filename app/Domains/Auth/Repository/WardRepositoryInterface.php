<?php

namespace App\Domains\Auth\Repository;

use App\Domains\Auth\Entities\City\Ward;

interface WardRepositoryInterface
{
    public function findById(int $id): ?Ward;
}
