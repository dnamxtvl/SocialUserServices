<?php

namespace App\Data\Repository;

use App\Domains\Auth\DTOs\SaveUserForgotPasswordLogDTO;
use App\Domains\Auth\Repository\UserForgotPasswordLogRepositoryInterface;
use App\Models\UserForgotPasswordLog;

class UserForgotPasswordLogRepository implements UserForgotPasswordLogRepositoryInterface
{
    public function save(SaveUserForgotPasswordLogDTO $saveUserForgotPasswordLog): void
    {
        $userForgotPasswordLog = new UserForgotPasswordLog();

        $userForgotPasswordLog->user_id = $saveUserForgotPasswordLog->getUserId();
        $userForgotPasswordLog->ip = $saveUserForgotPasswordLog->getIp();
        $userForgotPasswordLog->device = $saveUserForgotPasswordLog->getDevice();
        $userForgotPasswordLog->type = $saveUserForgotPasswordLog->getType()->value;

        $userForgotPasswordLog->save();
    }
}
