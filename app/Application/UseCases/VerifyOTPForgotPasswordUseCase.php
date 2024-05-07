<?php

namespace App\Application\UseCases;

use App\Application\Command;
use App\Domains\Auth\Enums\AuthExceptionEnum;
use App\Domains\Auth\Enums\TypeCodeOTPEnum;
use App\Domains\User\Enums\UserExceptionEnum;
use App\Domains\User\Enums\UserStatusEnum;
use App\Domains\User\Exceptions\AccountClosedException;
use App\Domains\User\Exceptions\UserNotFoundException;
use App\Domains\User\Repository\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Throwable;

class VerifyOTPForgotPasswordUseCase extends Command
{
    public function __construct(
        private readonly string $userId,
        private readonly string $code
    ) {
    }

    public function handle(UserRepositoryInterface $userRepository): JsonResponse
    {
        try {
            $user = $userRepository->findById(userId: $this->userId);
            if (is_null($user)) {
                throw new UserNotFoundException(code: UserExceptionEnum::USER_NOT_FOUND_WHEN_VERIFY_OTP->value);
            }

            if ($user->getStatus() === UserStatusEnum::CLOSE_ACCOUNT) {
                throw new AccountClosedException(code: AuthExceptionEnum::ACCOUNT_CLOSED->value);
            }

            $emailVerifyOTP = $user->verifyEmailOTP(code: $this->code, type: TypeCodeOTPEnum::FORGET_PASSWORD);

            return $this->respondWithJson(content: $emailVerifyOTP->toArray());
        } catch (Throwable $exception) {
            return $this->respondWithJsonError(e: $exception);
        }
    }
}
