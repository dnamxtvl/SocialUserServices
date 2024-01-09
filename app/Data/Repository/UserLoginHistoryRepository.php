<?php

namespace App\Data\Repository;

use App\Domains\Auth\DTOs\SaveUserLoginHistoryDTO;
use App\Domains\Auth\Repository\UserLoginHistoryRepositoryInterface;
use App\Models\User;
use App\Models\UserLoginHistory;

class UserLoginHistoryRepository implements UserLoginHistoryRepositoryInterface
{
    public function save(SaveUserLoginHistoryDTO $saveUserLoginHistoryDTO): void
    {
        $userLoginHistory = new UserLoginHistory();

        $user = $saveUserLoginHistoryDTO->getUser();
        /**
         * @var User $user
         **/
        $userLoginHistory->ip = $saveUserLoginHistoryDTO->getIp();
        $userLoginHistory->user_id = $user->id;
        $userLoginHistory->device = $saveUserLoginHistoryDTO->getDevice();
        $userLoginHistory->save();
    }
}
