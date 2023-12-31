<?php

namespace App\Http\Controllers;

use App\Domains\User\DTOs\RegisterUserParamsDTO;
use App\Domains\User\Enums\UserGenderEnums;
use App\Features\DTOs\LoginParamsDTO;
use App\Features\LoginFeature;
use App\Features\LogoutFeature;
use App\Features\RegisterUserFeature;
use App\Features\ResendVerificationNotificationFeature;
use App\Features\VerifyOTPAfterLoginFeature;
use App\Features\VerifyOTPAfterRegisterFeature;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\VerifyEmailOTPAfterLoginRequest;
use App\Http\Requests\VerifyEmailOTPRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $loginParams = new LoginParamsDTO(
            email: $request->input('email'),
            password: $request->input('password'),
            rememberMe: $request->input('remember_me'),
        );

        return $this->dispatchSync(new LoginFeature(
            loginParams: $loginParams,
            ip: $request->ip(),
            device: $request->header('User-Agent')
        ));
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
            email: $request->input('email'), code: $request->input('code')
        ));
    }

    public function resendVerificationNotification(string $userId): JsonResponse
    {
        return $this->dispatchSync(new ResendVerificationNotificationFeature(userId: $userId));
    }

    public function logout(): JsonResponse
    {
        return $this->dispatchSync(new LogoutFeature());
    }
}
