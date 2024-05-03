<?php

namespace App\Features;

use App\Domains\Auth\DTOs\SaveUserForgotPasswordLogDTO;
use App\Domains\Auth\DTOs\UserDeviceInformationDTO;
use App\Domains\Auth\Enums\AuthExceptionEnum;
use App\Domains\Auth\Enums\TypeCodeOTPEnum;
use App\Domains\Auth\Enums\TypeForgotPasswordLogEnum;
use App\Domains\Auth\Jobs\CreateEmailVerifyOTPJob;
use App\Domains\Auth\Repository\UserForgotPasswordLogRepositoryInterface;
use App\Domains\User\Enums\UserExceptionEnum;
use App\Domains\User\Enums\UserStatusEnum;
use App\Domains\User\Exceptions\AccountClosedException;
use App\Domains\User\Exceptions\UserNotFoundException;
use App\Domains\User\Repository\UserRepositoryInterface;
use App\Helpers\Command;
use App\Infrastructure\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class ForgotPasswordFeature extends Command
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
        DB::beginTransaction();
        try {
            $user = $userRepository->getQuery(filters: ['email' => $this->email])->first();
            if (is_null($user)) {
                throw new UserNotFoundException(code: UserExceptionEnum::USER_NOT_FOUND_WHEN_VERIFY_OTP->value);
            }

            /** @var User $user */
            if ($user->status === UserStatusEnum::CLOSE_ACCOUNT->value) {
                throw new AccountClosedException(code: AuthExceptionEnum::ACCOUNT_CLOSED->value);
            }

            $this->dispatchSync(new CreateEmailVerifyOTPJob(user: $user, type: TypeCodeOTPEnum::FORGET_PASSWORD));
            $saveUserForgotPassword = new SaveUserForgotPasswordLogDTO(
                userId: $user->id,
                ip: $this->userDeviceInformation->getIp(),
                device: $this->userDeviceInformation->getDevice(),
                type: TypeForgotPasswordLogEnum::USER_REQUEST_FORGOT_PASSWORD
            );
            $userForgotPasswordLogRepository->save(saveUserForgotPasswordLog: $saveUserForgotPassword);

            DB::commit();

            return $this->respondWithJson(content: $user->toArray());
        } catch (Throwable $exception) {
            DB::rollBack();

            return $this->respondWithJsonError(e: $exception);
        }
    }
}
