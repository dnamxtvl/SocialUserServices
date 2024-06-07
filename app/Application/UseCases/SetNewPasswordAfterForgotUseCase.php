<?php

namespace App\Application\UseCases;

use App\Application\Command;
use App\Domains\Auth\DTOs\UserDeviceInformationDTO;
use App\Domains\Auth\Entities\User\UserForgotPasswordLog;
use App\Domains\Auth\Enums\TypeForgotPasswordLogEnum;
use App\Domains\Auth\Repository\UserForgotPasswordLogRepositoryInterface;
use App\Domains\User\Enums\UserExceptionEnum;
use App\Domains\User\Exceptions\UserNotFoundException;
use App\Domains\User\Repository\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class SetNewPasswordAfterForgotUseCase extends Command
{
    public function __construct(
        private readonly string $userId,
        private readonly string $password,
        private readonly string $token,
        private readonly UserDeviceInformationDTO $userDeviceInformation
    ) {
    }

    public function handle(
        UserRepositoryInterface $userRepository,
        UserForgotPasswordLogRepositoryInterface $userForgotPasswordLogRepository,
    ): JsonResponse {
        DB::beginTransaction();
        try {
            $user = $userRepository->findById(userId: $this->userId);
            if (is_null($user)) {
                throw new UserNotFoundException(code: UserExceptionEnum::USER_NOT_FOUND_WHEN_VERIFY_OTP->value);
            }

            if (! Password::tokenExists(user: $user->toEloquent(), token: $this->token)) {
                throw new NotFoundHttpException(message: 'Token không tồn tại hoặc đã hết hạn!');
            }

            $user->setPassword(password: Hash::make(value: $this->password));
            $userRepository->save(userDomain: $user);
            $userChangePassword = new UserForgotPasswordLog(
                userId: $user->getId(),
                ip: $this->userDeviceInformation->getIp(),
                device: $this->userDeviceInformation->getDevice(),
                type: TypeForgotPasswordLogEnum::PASSWORD_CHANGED
            );
            $userForgotPasswordLogRepository->save(forgotPasswordLog: $userChangePassword);
            Password::deleteToken(user: $user->toEloquent());
            DB::commit();

            return $this->respondWithJson(content: []);
        } catch (Throwable $exception) {
            DB::rollBack();

            return $this->respondWithJsonError(e: $exception);
        }
    }
}
