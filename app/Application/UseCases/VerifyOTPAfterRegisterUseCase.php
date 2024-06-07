<?php

namespace App\Application\UseCases;

use App\Application\Command;
use App\Domains\Auth\Enums\TypeCodeOTPEnum;
use App\Domains\User\Enums\UserExceptionEnum;
use App\Domains\User\Exceptions\UserNotFoundException;
use App\Domains\User\Repository\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class VerifyOTPAfterRegisterUseCase extends Command
{
    public function __construct(
        private readonly string $userId,
        private readonly string $code
    ) {
    }

    public function handle(UserRepositoryInterface $userRepository): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = $userRepository->findById(userId: $this->userId);
            if (is_null($user)) {
                throw new UserNotFoundException(code: UserExceptionEnum::USER_NOT_FOUND_WHEN_VERIFY_OTP->value);
            }
            $user->verifyEmailOTP(code: $this->code, type: TypeCodeOTPEnum::VERIFY_EMAIL);
            DB::commit();

            return $this->respondWithJson(content: []);
        } catch (Throwable $exception) {
            DB::rollBack();

            return $this->respondWithJsonError(e: $exception);
        }
    }
}
