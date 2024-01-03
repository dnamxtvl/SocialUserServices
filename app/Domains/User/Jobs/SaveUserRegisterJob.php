<?php

namespace App\Domains\User\Jobs;

use App\Domains\User\DTOs\RegisterUserParamsDTO;
use App\Domains\User\Repository\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class SaveUserRegisterJob
{
    public function __construct(
        private readonly RegisterUserParamsDTO $registerUserParams,
    ) {
    }

    public function handle(UserRepositoryInterface $userRepository): Model
    {
        return $userRepository->save(registerUserParams: $this->registerUserParams);
    }
}
