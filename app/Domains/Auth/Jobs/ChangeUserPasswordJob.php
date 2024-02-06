<?php

namespace App\Domains\Auth\Jobs;

use App\Domains\Auth\DTOs\SaveUserForgotPasswordLogDTO;
use App\Domains\Auth\DTOs\UserDeviceInformationDTO;
use App\Domains\Auth\Enums\TypeForgotPasswordLogEnum;
use App\Domains\Auth\Repository\UserForgotPasswordLogRepositoryInterface;
use App\Data\Models\EmailVerifyOTO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ChangeUserPasswordJob
{
    public function __construct(
        private readonly Model $emailVerifyOTP,
        private readonly string $password,
        private readonly UserDeviceInformationDTO $userDeviceInformation
    ) {
    }

    public function handle(UserForgotPasswordLogRepositoryInterface $userForgotPasswordLogRepository): void
    {
        /** @var EmailVerifyOTO $emailVerifyOTP */
        $emailVerifyOTP = $this->emailVerifyOTP;
        $emailVerifyOTP->user()->update(['password' => Hash::make($this->password)]);
        $saveUserForgotPassword = new SaveUserForgotPasswordLogDTO(
            userId: $emailVerifyOTP->user->id,
            ip: $this->userDeviceInformation->getIp(),
            device: $this->userDeviceInformation->getDevice(),
            type: TypeForgotPasswordLogEnum::PASSWORD_CHANGED
        );

        $userForgotPasswordLogRepository->save(saveUserForgotPasswordLog: $saveUserForgotPassword);
        $emailVerifyOTP->delete();
        Password::deleteToken(user: $emailVerifyOTP->user);
    }
}
