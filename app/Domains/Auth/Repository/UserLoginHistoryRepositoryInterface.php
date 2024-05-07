<?php

namespace App\Domains\Auth\Repository;

use App\Domains\Auth\Entities\User\UserLoginHistory;
use Illuminate\Database\Eloquent\Builder;

interface UserLoginHistoryRepositoryInterface
{
    public function getQuery(array $columnSelects = [], array $filters = []): Builder;

    public function save(UserLoginHistory $userLoginHistoryDomain): void;
}
