<?php

namespace App\Domains\Auth\Repository;

use App\Domains\Auth\Entities\User\BlockUserLoginTemporary;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

interface BlockUserLoginTemporaryRepositoryInterface
{
    public function getQuery(array $columnSelects = [], array $filters = []): Builder;

    public function save(string $ip, string $userId, Carbon $expiredAt): void;

    public function findByUserAndIp(string $ip, string $userId): ?BlockUserLoginTemporary;
}
