<?php

namespace App\Domains\User\Repository;

use App\Domains\User\DTOs\RegisterUserParamsDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    public function getQuery(array $columnSelects = [], array $filters = []): Builder;

    public function findById(string $userId): ?Model;

    public function save(RegisterUserParamsDTO $registerUserParams): Model;
}
