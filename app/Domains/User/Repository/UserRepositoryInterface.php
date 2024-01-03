<?php

namespace App\Domains\User\Repository;

use App\Domains\User\DTOs\RegisterUserParamsDTO;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    public function save(RegisterUserParamsDTO $registerUserParams): Model;
}
