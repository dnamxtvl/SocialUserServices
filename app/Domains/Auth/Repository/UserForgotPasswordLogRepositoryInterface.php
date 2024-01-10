<?php

namespace App\Domains\Auth\Repository;

use App\Domains\Auth\DTOs\SaveUserForgotPasswordLogDTO;

interface UserForgotPasswordLogRepositoryInterface
{
    public function save(SaveUserForgotPasswordLogDTO $saveUserForgotPasswordLog): void;
}
