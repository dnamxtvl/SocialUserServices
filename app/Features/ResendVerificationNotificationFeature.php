<?php

namespace App\Features;

use App\Domains\Auth\Enums\AuthExceptionEnum;
use App\Domains\Auth\Enums\TypeCodeOTPEnum;
use App\Domains\Auth\Exceptions\EmailVerifiedException;
use App\Domains\Auth\Jobs\CreateEmailVerifyOTPJob;
use App\Domains\User\Enums\UserExceptionEnum;
use App\Domains\User\Exceptions\UserNotFoundException;
use App\Domains\User\Repository\UserRepositoryInterface;
use App\Helpers\Command;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Throwable;

class ResendVerificationNotificationFeature extends Command
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

            /** @var User $user */
            if ($user->hasVerifiedEmail()) {
                throw new EmailVerifiedException(code: AuthExceptionEnum::EMAIL_VERIFIED->value);
            }

            $this->dispatchSync(new CreateEmailVerifyOTPJob(user: $user, type: TypeCodeOTPEnum::VERIFY_EMAIL));

            return $this->respondWithJson(content: []);
        } catch (Throwable $exception) {
            return $this->respondWithJsonError(e: $exception);
        }
    }
}
