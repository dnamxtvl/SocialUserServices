<?php

namespace App\Features;

use App\Domains\Auth\Enums\AuthExceptionEnum;
use App\Domains\Auth\Enums\TypeCodeOTPEnum;
use App\Domains\Auth\Jobs\CheckIsValidVerifyEmailOTPJob;
use App\Domains\User\Enums\UserExceptionEnum;
use App\Domains\User\Enums\UserStatusEnum;
use App\Domains\User\Exceptions\AccountClosedException;
use App\Domains\User\Exceptions\UserNotFoundException;
use App\Domains\User\Repository\UserRepositoryInterface;
use App\Helpers\Command;
use App\Infrastructure\Models\User;
use Illuminate\Http\JsonResponse;
use Throwable;

class VerifyOTPForgotPasswordFeature extends Command
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

            /** @var User $user */
            if ($user->status === UserStatusEnum::CLOSE_ACCOUNT->value) {
                throw new AccountClosedException(code: AuthExceptionEnum::ACCOUNT_CLOSED->value);
            }

            $emailVerifyOTP = $this->dispatchSync(new CheckIsValidVerifyEmailOTPJob(
                user: $user,
                verifyCode: $this->code,
                typeOTP: TypeCodeOTPEnum::FORGET_PASSWORD
            ));

            return $this->respondWithJson(content: $emailVerifyOTP->toArray());
        } catch (Throwable $exception) {
            return $this->respondWithJsonError(e: $exception);
        }
    }
}
