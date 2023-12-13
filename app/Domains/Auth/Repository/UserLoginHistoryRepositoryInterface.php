<?php

namespace App\Domains\Auth\Repository;

use App\Domains\Auth\DTOs\SaveUserLoginHistoryDTO;

interface UserLoginHistoryRepositoryInterface
{
    public function save(SaveUserLoginHistoryDTO $saveUserLoginHistoryDTO): void;
}
