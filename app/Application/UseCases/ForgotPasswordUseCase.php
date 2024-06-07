<?php

namespace App\Application\UseCases;

use App\Application\Command;
use App\Domains\Auth\DTOs\UserDeviceInformationDTO;
use App\Domains\Auth\Entities\User\UserForgotPasswordLog;
use App\Domains\Auth\Enums\AuthExceptionEnum;
use App\Domains\Auth\Enums\TypeForgotPasswordLogEnum;
use App\Domains\Auth\Repository\UserForgotPasswordLogRepositoryInterface;
use App\Domains\User\Enums\UserExceptionEnum;
use App\Domains\User\Enums\UserStatusEnum;
use App\Domains\User\Exceptions\AccountClosedException;
use App\Domains\User\Exceptions\UserNotFoundException;
use App\Domains\User\Repository\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Throwable;

class ForgotPasswordUseCase extends Command
{
    public function __construct(
        private readonly string $email,
        private readonly UserDeviceInformationDTO $userDeviceInformation
    ) {
    }

    public function handle(
        UserRepositoryInterface $userRepository,
        UserForgotPasswordLogRepositoryInterface $userForgotPasswordLogRepository
    ): JsonResponse {
        try {
            $user = $userRepository->findByEmail(email: $this->email);
            if (is_null($user)) {
                throw new UserNotFoundException(code: UserExceptionEnum::USER_NOT_FOUND_WHEN_VERIFY_OTP->value);
            }

            if ($user->getStatus() === UserStatusEnum::CLOSE_ACCOUNT) {
                throw new AccountClosedException(code: AuthExceptionEnum::ACCOUNT_CLOSED->value);
            }

            $saveUserForgotPassword = new UserForgotPasswordLog(
                userId: $user->getId(),
                ip: $this->userDeviceInformation->getIp(),
                device: $this->userDeviceInformation->getDevice(),
                type: TypeForgotPasswordLogEnum::USER_REQUEST_FORGOT_PASSWORD
            );
            $userForgotPasswordLogRepository->save(forgotPasswordLog: $saveUserForgotPassword);

            return $this->respondWithJson(content: $user->toArray());
        } catch (Throwable $exception) {
            return $this->respondWithJsonError(e: $exception);
        }
    }
}
