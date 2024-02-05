<?php

namespace App\Operations;

use App\Domains\Auth\DTOs\UserDeviceInformationDTO;
use App\Domains\Auth\DTOs\UserLoginResponseDataDTO;
use App\Domains\Auth\Enums\AuthExceptionEnum;
use App\Domains\Auth\Enums\TypeCodeOTPEnum;
use App\Domains\Auth\Exceptions\EmailNotVerifyException;
use App\Domains\Auth\Jobs\CheckUserBlockLoginTemporaryJob;
use App\Domains\Auth\Jobs\CreateEmailVerifyOTPJob;
use App\Domains\Auth\Jobs\CreateTokenJwtLoginJob;
use App\Domains\Auth\Jobs\LoginWrongPasswordJob;
use App\Domains\User\Enums\UserStatusEnum;
use App\Domains\User\Exceptions\AccountClosedException;
use App\Features\DTOs\LoginParamsDTO;
use App\Helpers\Command;
use App\Data\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class LoginOperation extends Command
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private readonly LoginParamsDTO $loginParams,
        private readonly UserDeviceInformationDTO $userDeviceInformation
    ) {
    }

    /**
     * Execute the job.
     *
     * @throws Exception
     */
    public function handle(): UserLoginResponseDataDTO
    {
        $credentials = [
            'email' => $this->loginParams->getEmail(),
            'password' => $this->loginParams->getPassword(),
        ];

        $this->dispatchSync(new CheckUserBlockLoginTemporaryJob(
            email: $this->loginParams->getEmail(),
            ip: $this->userDeviceInformation->getIp()
        ));

        if (! Auth::attempt(credentials:  $credentials, remember:  $this->loginParams->getRememberMe())) {
            $this->dispatchSync(new LoginWrongPasswordJob(
                email: $this->loginParams->getEmail(),
                userDeviceInformation: $this->userDeviceInformation
            ));
            throw new UnauthorizedHttpException(
                challenge: 'Invalid Argument',
                message: 'Sai email hoặc mật khẩu!'
            );
        }

        $user = Auth::user();

        /** @var User $user */
        if (! $user->hasVerifiedEmail()) {
            $this->dispatchSync(new CreateEmailVerifyOTPJob(
                user: $user,
                type: TypeCodeOTPEnum::VERIFY_EMAIL
            ));
            throw new EmailNotVerifyException(code: AuthExceptionEnum::EMAIL_NOT_VERIFY->value);
        }

        /** @var User $user */
        if ($user->status === UserStatusEnum::CLOSE_ACCOUNT->value) {
            throw new AccountClosedException(code: AuthExceptionEnum::ACCOUNT_CLOSED->value);
        }

        return $this->dispatchSync(new CreateTokenJwtLoginJob(
            user: $user,
            rememberMe: $this->loginParams->getRememberMe()
        ));
    }
}
