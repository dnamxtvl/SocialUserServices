<?php

namespace App\Domains\Auth\Repository;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

interface BlockUserLoginTemporaryRepositoryInterface
{
    public function getQuery(array $columnSelects = [], array $filters = []): Builder;

    public function save(string $ip, string $userId, Carbon $expiredAt): void;
}
