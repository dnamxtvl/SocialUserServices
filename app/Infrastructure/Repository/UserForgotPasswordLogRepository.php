<?php

namespace App\Infrastructure\Repository;

use App\Domains\Auth\Entities\User\UserForgotPasswordLog as UserForgotPasswordLogDomain;
use App\Domains\Auth\Repository\UserForgotPasswordLogRepositoryInterface;
use App\Infrastructure\Models\UserForgotPasswordLog;

class UserForgotPasswordLogRepository implements UserForgotPasswordLogRepositoryInterface
{
    public function save(UserForgotPasswordLogDomain $forgotPasswordLog): void
    {
        $userForgotPasswordLog = new UserForgotPasswordLog();

        $userForgotPasswordLog->user_id = $forgotPasswordLog->getUserId();
        $userForgotPasswordLog->ip = $forgotPasswordLog->getIp();
        $userForgotPasswordLog->device = $forgotPasswordLog->getDevice();
        $userForgotPasswordLog->type = $forgotPasswordLog->getType()->value;

        $userForgotPasswordLog->save();
    }
}
