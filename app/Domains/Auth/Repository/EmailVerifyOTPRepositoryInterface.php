<?php

namespace App\Domains\Auth\Repository;

use App\Domains\Auth\DTOs\SaveEmailVerifyOTPDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface EmailVerifyOTPRepositoryInterface
{
    public function getQuery(array $columnSelects = [], array $filters = []): Builder;

    public function save(SaveEmailVerifyOTPDTO $saveEmailVerify): Model;

    public function findById(string $emailVerifyOtpId): ?Model;
}
