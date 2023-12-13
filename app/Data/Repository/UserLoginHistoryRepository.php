<?php

namespace App\Data\Repository;

use App\Domains\Auth\DTOs\SaveUserLoginHistoryDTO;
use App\Domains\Auth\Repository\UserLoginHistoryRepositoryInterface;
use App\Models\UserLoginHistory;

class UserLoginHistoryRepository implements UserLoginHistoryRepositoryInterface
{
    public function save(SaveUserLoginHistoryDTO $saveUserLoginHistoryDTO): void
    {
        $userLoginHistory = new UserLoginHistory();

        $userLoginHistory->ip = $saveUserLoginHistoryDTO->getIp();
        $userLoginHistory->user_id = $saveUserLoginHistoryDTO->getUserId();
        $userLoginHistory->device = $saveUserLoginHistoryDTO->getDevice();
        $userLoginHistory->save();
    }
}
