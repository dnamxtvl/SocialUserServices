<?php

namespace App\Domains\User\Repository;

use App\Domains\Auth\Entities\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    public function getQuery(array $columnSelects = [], array $filters = []): Builder;

    public function findById(string $userId): ?User;

    public function save(User $userDomain): User;

    public function getMaxUserCode(): int;

    public function findByEmail(string $email): ?User;

    public function findByIdEloquent(User $userDomain): ?Model;
}
