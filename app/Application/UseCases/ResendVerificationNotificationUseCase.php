<?php

namespace App\Application\UseCases;

use App\Application\Command;
use App\Domains\Auth\Enums\AuthExceptionEnum;
use App\Domains\Auth\Enums\TypeCodeOTPEnum;
use App\Domains\Auth\Exceptions\EmailVerifiedException;
use App\Domains\User\Enums\UserExceptionEnum;
use App\Domains\User\Exceptions\UserNotFoundException;
use App\Domains\User\Repository\UserRepositoryInterface;
use App\Events\EmailNotVerifyEvent;
use Illuminate\Http\JsonResponse;
use Throwable;

class ResendVerificationNotificationUseCase extends Command
{
    public function __construct(
        private readonly string $userId,
    ) {
    }

    public function handle(UserRepositoryInterface $userRepository): JsonResponse
    {
        try {
            $user = $userRepository->findById(userId: $this->userId);
            if (is_null($user)) {
                throw new UserNotFoundException(code: UserExceptionEnum::USER_NOT_FOUND_WHEN_VERIFY_OTP->value);
            }

            if (! is_null($user->getEmailVerifiedAt())) {
                throw new EmailVerifiedException(code: AuthExceptionEnum::EMAIL_VERIFIED->value);
            }
            $code = rand(config('validation.verify_code.min_value'), config('validation.verify_code.max_value'));
            event(new EmailNotVerifyEvent(user: $user->toEloquent(), verifyCode: $code, type: TypeCodeOTPEnum::VERIFY_EMAIL));

            return $this->respondWithJson(content: []);
        } catch (Throwable $exception) {
            return $this->respondWithJsonError(e: $exception);
        }
    }
}
