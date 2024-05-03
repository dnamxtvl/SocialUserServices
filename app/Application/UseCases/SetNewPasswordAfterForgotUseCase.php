<?php

namespace App\Application\UseCases;

use App\Domains\Auth\DTOs\UserDeviceInformationDTO;
use App\Domains\Auth\Enums\AuthExceptionEnum;
use App\Domains\Auth\Exceptions\OTPNotFoundException;
use App\Domains\Auth\Jobs\ChangeUserPasswordJob;
use App\Domains\Auth\Repository\EmailVerifyOTPRepositoryInterface;
use App\Domains\User\Enums\UserExceptionEnum;
use App\Domains\User\Exceptions\UserNotFoundException;
use App\Application\Command;
use App\Infrastructure\Models\EmailVerifyOTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class SetNewPasswordAfterForgotUseCase extends Command
{
    public function __construct(
        private readonly string $otpId,
        private readonly string $password,
        private readonly string $token,
        private readonly UserDeviceInformationDTO $userDeviceInformation
    ) {
    }

    public function handle(EmailVerifyOTPRepositoryInterface $emailVerifyOTPRepository): JsonResponse
    {
        DB::beginTransaction();
        try {
            $emailVerifyOTP = $emailVerifyOTPRepository->findById(emailVerifyOtpId: $this->otpId);

            if (is_null($emailVerifyOTP)) {
                throw new OTPNotFoundException(code: AuthExceptionEnum::OTP_NOT_FOUND->value);
            }
            /** @var EmailVerifyOTO $emailVerifyOTP */
            if ($emailVerifyOTP->token != $this->token) {
                throw new OTPNotFoundException(code: AuthExceptionEnum::OTP_NOT_FOUND->value);
            }

            if (! $emailVerifyOTP->user) {
                throw new UserNotFoundException(code: UserExceptionEnum::USER_NOT_FOUND_WHEN_SET_NEW_PASSWORD->value);
            }

            if (! Password::tokenExists(user: $emailVerifyOTP->user, token: $this->token)) {
                throw new NotFoundHttpException(message: 'Token không tồn tại hoặc đã hết hạn!');
            }

            $this->dispatchSync(new ChangeUserPasswordJob(
                emailVerifyOTP: $emailVerifyOTP,
                password: $this->password,
                userDeviceInformation: $this->userDeviceInformation
            ));
            DB::commit();

            return $this->respondWithJson(content: []);
        } catch (Throwable $exception) {
            DB::rollBack();

            return $this->respondWithJsonError(e: $exception);
        }
    }
}
