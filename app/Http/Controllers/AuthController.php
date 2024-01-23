<?php

namespace App\Http\Controllers;

use App\Domains\Auth\DTOs\UserDeviceInformationDTO;
use App\Domains\User\DTOs\RegisterUserParamsDTO;
use App\Domains\User\Enums\UserGenderEnums;
use App\Features\DTOs\LoginParamsDTO;
use App\Features\ForgotPasswordFeature;
use App\Features\LoginFeature;
use App\Features\LogoutFeature;
use App\Features\RegisterUserFeature;
use App\Features\ResendVerificationNotificationFeature;
use App\Features\SetNewPasswordAfterForgotFeature;
use App\Features\VerifyOTPAfterLoginFeature;
use App\Features\VerifyOTPAfterRegisterFeature;
use App\Features\VerifyOTPForgotPasswordFeature;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\SetNewPasswordAfterForgotRequest;
use App\Http\Requests\VerifyEmailOTPAfterLoginRequest;
use App\Http\Requests\VerifyEmailOTPRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $userDeviceInformation = new UserDeviceInformationDTO(ip: $request->ip(), device: $request->header('User-Agent'));

        $loginParams = new LoginParamsDTO(
            email: $request->input('email'),
            password: $request->input('password'),
            rememberMe: $request->input('remember_me'),
        );

        return $this->dispatchSync(new LoginFeature(loginParams: $loginParams, userDeviceInformation: $userDeviceInformation));
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $registerUserParams = new RegisterUserParamsDTO(
            firstname: $request->input('first_name'),
            lastname: $request->input('last_name'),
            email: $request->input('email'),
            password: $request->input('password'),
            dayOfBirth: $request->input('day_of_birth'),
            monthOfBirth: $request->input('month_of_birth'),
            yearOfBirth: $request->input('year_of_birth'),
            gender: UserGenderEnums::from($request->input('gender'))
        );

        return $this->dispatchSync(new RegisterUserFeature(registerUserParams: $registerUserParams));
    }

    public function verifyOTPAfterRegister(VerifyEmailOTPRequest $request): JsonResponse
    {
        return $this->dispatchSync(new VerifyOTPAfterRegisterFeature(
            userId: $request->input('user_id'),
            code: $request->input('verify_code')
        ));
    }

    public function verifyOTPAfterLogin(VerifyEmailOTPAfterLoginRequest $request): JsonResponse
    {
        return $this->dispatchSync(new VerifyOTPAfterLoginFeature(
            email: $request->input('email'), code: $request->input('verify_code')
        ));
    }

    public function resendVerificationNotification(string $userId): JsonResponse
    {
        return $this->dispatchSync(new ResendVerificationNotificationFeature(userId: $userId));
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $userDeviceInformation = new UserDeviceInformationDTO(ip: $request->ip(), device: $request->header('User-Agent'));

        return $this->dispatchSync(new ForgotPasswordFeature(
            email: $request->input('email'),
            userDeviceInformation: $userDeviceInformation
        ));
    }

    public function verifyOTPForgotPassword(VerifyEmailOTPRequest $request): JsonResponse
    {
        return $this->dispatchSync(new VerifyOTPForgotPasswordFeature(
            userId: $request->input('user_id'),
            code: $request->input('verify_code')
        ));
    }

    public function setNewPasswordAfterForgot(SetNewPasswordAfterForgotRequest $request): JsonResponse
    {
        $userDeviceInformation = new UserDeviceInformationDTO(ip: $request->ip(), device: $request->header('User-Agent'));

        return $this->dispatchSync(new SetNewPasswordAfterForgotFeature(
            otpId: $request->input('otp_id'),
            password: $request->input('password'),
            token: $request->input('token'),
            userDeviceInformation: $userDeviceInformation
        ));
    }

    public function logout(): JsonResponse
    {
        return $this->dispatchSync(new LogoutFeature());
    }
}
