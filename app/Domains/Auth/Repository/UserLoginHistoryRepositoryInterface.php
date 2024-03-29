<?php

namespace App\Domains\Auth\Repository;

use App\Domains\Auth\DTOs\SaveUserLoginHistoryDTO;
use Illuminate\Database\Eloquent\Builder;

interface UserLoginHistoryRepositoryInterface
{
    public function getQuery(array $columnSelects = [], array $filters = []): Builder;

    public function save(SaveUserLoginHistoryDTO $saveUserLoginHistoryDTO): void;
}
