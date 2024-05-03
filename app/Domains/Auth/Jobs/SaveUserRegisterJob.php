<?php

namespace App\Domains\Auth\Jobs;

use App\Domains\User\DTOs\RegisterUserParamsDTO;
use App\Domains\User\Repository\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

readonly class SaveUserRegisterJob
{
    public function __construct(
        private RegisterUserParamsDTO $registerUserParams,
    ) {
    }

    public function handle(UserRepositoryInterface $userRepository): Model
    {
        Log::info('Chuẩn bị đăng ký user có email '.$this->registerUserParams->getEmail());

        return $userRepository->save(registerUserParams: $this->registerUserParams);
    }
}
