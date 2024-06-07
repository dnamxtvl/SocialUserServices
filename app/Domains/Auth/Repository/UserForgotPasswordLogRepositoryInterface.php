<?php

namespace App\Domains\Auth\Repository;

use App\Domains\Auth\Entities\User\UserForgotPasswordLog;

interface UserForgotPasswordLogRepositoryInterface
{
    public function save(UserForgotPasswordLog $forgotPasswordLog): void;
}
