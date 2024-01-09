<?php

namespace App\Domains\Auth\Repository;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface EmailVerifyOTPRepositoryInterface
{
    public function getQuery(array $columnSelects = [], array $filters = []): Builder;

    public function save(string $code, string $userId, Carbon $expiredAt): Model;
}
