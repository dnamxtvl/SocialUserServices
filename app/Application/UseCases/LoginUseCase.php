<?php

namespace App\Application\UseCases;

use App\Application\Command;
use App\Domains\Auth\DTOs\UserDeviceInformationDTO;
use App\Domains\Auth\Entities\User\UserLoginHistory;
use App\Domains\Auth\Enums\AuthExceptionEnum;
use App\Domains\Auth\Enums\TypeCodeOTPEnum;
use App\Domains\Auth\Enums\TypeUserHistoryLoginEnum;
use App\Domains\Auth\Exceptions\EmailNotVerifyException;
use App\Domains\Auth\Exceptions\LoginWrongPasswordManyException;
use App\Domains\Auth\Repository\UserLoginHistoryRepositoryInterface;
use App\Domains\User\Enums\UserStatusEnum;
use App\Domains\User\Exceptions\AccountClosedException;
use App\Domains\User\Repository\UserRepositoryInterface;
use App\Events\EmailNotVerifyEvent;
use App\Infrastructure\Models\User;
use App\Presentation\DTOs\LoginParamsDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class LoginUseCase extends Command
{
    public function __construct(
        private readonly LoginParamsDTO $loginParams,
        private readonly UserDeviceInformationDTO $userDeviceInformation
    ) {
    }

    public function handle(
        UserRepositoryInterface $userRepository,
        UserLoginHistoryRepositoryInterface $userLoginHistoryRepository
    ): JsonResponse {
        try {
            $credentials = [
                'email' => $this->loginParams->getEmail(),
                'password' => $this->loginParams->getPassword(),
            ];
            $user = $userRepository->findByEmail(email: $this->loginParams->getEmail());
            if ($user && $user->checkIsBlockUserLogin(ip: $this->userDeviceInformation->getIp())) {
                throw new LoginWrongPasswordManyException(
                    code: AuthExceptionEnum::LOGIN_WRONG_PASSWORD_MANY->value
                );
            }

            if (! Auth::attempt(credentials: $credentials, remember: $this->loginParams->getRememberMe())) {
                if ($user) {
                    $user->userLoginWrongPasswordAction(userDeviceInformation: $this->userDeviceInformation);
                }
                throw new UnauthorizedHttpException(challenge: 'Invalid Argument', message: 'Sai email hoặc mật khẩu!');
            }

            /** @var User $userEloquent */
            $userEloquent = Auth::user();
            if (! $userEloquent->hasVerifiedEmail()) {
                $code = rand(config('validation.verify_code.min_value'), config('validation.verify_code.max_value'));
                event(new EmailNotVerifyEvent(user: $userEloquent, verifyCode: $code, type: TypeCodeOTPEnum::VERIFY_EMAIL));
                throw new EmailNotVerifyException(code: AuthExceptionEnum::EMAIL_NOT_VERIFY->value);
            }

            $userEloquent->latest_ip_login = $this->userDeviceInformation->getIp();
            $userEloquent->latest_login = now();
            $userEloquent->save();

            if ($userEloquent->latest_ip_login != $this->userDeviceInformation->getIp() ||
                ! $userEloquent->userLoginHistories()->count()) {
                $userLoginHistory = new UserLoginHistory(
                    userId: $userEloquent->id,
                    ip: $this->userDeviceInformation->getIp(),
                    device: $this->userDeviceInformation->getDevice(),
                    type: TypeUserHistoryLoginEnum::LOGIN_SUCCESS_NEW_IP
                );
                $userLoginHistoryRepository->save(userLoginHistoryDomain: $userLoginHistory);
            }

            if ($userEloquent->status === UserStatusEnum::CLOSE_ACCOUNT->value) {
                throw new AccountClosedException(code: AuthExceptionEnum::ACCOUNT_CLOSED->value);
            }

            $user->setId(id: $userEloquent->id);
            $loginJobData = $user->createTokenLoginJwt(rememberMe: $this->loginParams->getRememberMe());

            return $this->respondWithJson(content: $loginJobData->toArray());
        } catch (Throwable $exception) {
            return $this->respondWithJsonError(e: $exception);
        }
    }
}
