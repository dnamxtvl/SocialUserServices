<?php

namespace App\Domains\Auth\Jobs;

use App\Domains\Auth\Enums\AuthExceptionEnum;
use App\Domains\Auth\Exceptions\LoginWrongPasswordManyException;
use App\Domains\User\Repository\UserRepositoryInterface;
use App\Models\BlockUserLoginTemporary;
use App\Models\User;

class CheckUserBlockLoginTemporaryJob
{
    public function __construct(
        private readonly string $email,
        private readonly string $ip
    ) {
    }

    public function handle(UserRepositoryInterface $userRepository): void
    {
        $user = $userRepository->getQuery(filters: ['email' => $this->email])->first();
        /** @var User $user */
        if ($user) {
            /** @var BlockUserLoginTemporary $lockUserLoginTemporary */
            $lockUserLoginTemporary = $user->BlockUserLoginTemporary()->where('ip', $this->ip)->first();
            if ($lockUserLoginTemporary && ! now()->gt(date: $lockUserLoginTemporary->expired_at)) {
                throw new LoginWrongPasswordManyException(code: AuthExceptionEnum::LOGIN_WRONG_PASSWORD_MANY->value);
            }
        }
    }
}
