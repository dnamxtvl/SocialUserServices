<?php

namespace App\Presentation\Controllers;

use App\Domains\Auth\DTOs\UserDeviceInformationDTO;
use App\Domains\User\DTOs\RegisterUserParamsDTO;
use App\Domains\User\Enums\TypeAccountEnum;
use App\Domains\User\Enums\UserGenderEnums;
use App\Presentation\DTOs\LoginParamsDTO;
use App\Application\UseCases\ForgotPasswordUseCase;
use App\Application\UseCases\LoginUseCase;
use App\Application\UseCases\LogoutUseCase;
use App\Application\UseCases\RegisterUserUseCase;
use App\Application\UseCases\ResendOTPForgotPasswordUseCase;
use App\Application\UseCases\ResendVerificationNotificationUseCase;
use App\Application\UseCases\SetNewPasswordAfterForgotUseCase;
use App\Application\UseCases\VerifyOTPAfterLoginUseCase;
use App\Application\UseCases\VerifyOTPAfterRegisterUseCase;
use App\Application\UseCases\VerifyOTPForgotPasswordUseCase;
use App\Presentation\Requests\ForgotPasswordRequest;
use App\Presentation\Requests\LoginRequest;
use App\Presentation\Requests\RegisterUserRequest;
use App\Presentation\Requests\SetNewPasswordAfterForgotRequest;
use App\Presentation\Requests\VerifyEmailOTPAfterLoginRequest;
use App\Presentation\Requests\VerifyEmailOTPRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $userDeviceInformation = new UserDeviceInformationDTO(
            ip: $request->ip(),
            device: $request->header('User-Agent')
        );

        $loginParams = new LoginParamsDTO(
            email: $request->input('email'),
            password: $request->input('password'),
            rememberMe: $request->input('remember_me'),
        );

        return $this->dispatchSync(new LoginUseCase(
            loginParams: $loginParams,
            userDeviceInformation: $userDeviceInformation
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
            gender: UserGenderEnums::from($request->input('gender')),
            fromCityId: $request->input('from_city_id'),
            fromDistrictId: $request->input('from_district_id'),
            fromWardId: $request->input('from_ward_id'),
            currentCityId: $request->input('current_city_id'),
            currentDistrictId: $request->input('current_district_id'),
            currentWardId: $request->input('current_ward_id'),
            typeAccount: TypeAccountEnum::from($request->input('type_account')),
            organizationId: $request->input('organization_id'),
            unitRoomId: $request->input('unit_room_id'),
        );

        return $this->dispatchSync(new RegisterUserUseCase(registerUserParams: $registerUserParams));
    }

    public function verifyOTPAfterRegister(VerifyEmailOTPRequest $request): JsonResponse
    {
        return $this->dispatchSync(new VerifyOTPAfterRegisterUseCase(
            userId: $request->input('user_id'),
            code: $request->input('verify_code')
        ));
    }

    public function verifyOTPAfterLogin(VerifyEmailOTPAfterLoginRequest $request): JsonResponse
    {
        return $this->dispatchSync(new VerifyOTPAfterLoginUseCase(
            email: $request->input('email'), code: $request->input('verify_code')
        ));
    }

    public function resendVerificationNotification(string $userId): JsonResponse
    {
        return $this->dispatchSync(new ResendVerificationNotificationUseCase(userId: $userId));
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $userDeviceInformation = new UserDeviceInformationDTO(
            ip: $request->ip(),
            device: $request->header('User-Agent')
        );

        return $this->dispatchSync(new ForgotPasswordUseCase(
            email: $request->input('email'),
            userDeviceInformation: $userDeviceInformation
        ));
    }

    public function verifyOTPForgotPassword(VerifyEmailOTPRequest $request): JsonResponse
    {
        return $this->dispatchSync(new VerifyOTPForgotPasswordUseCase(
            userId: $request->input('user_id'),
            code: $request->input('verify_code')
        ));
    }

    public function resendOTPForgotPassword(string $userId): JsonResponse
    {
        return $this->dispatchSync(new ResendOTPForgotPasswordUseCase(userId: $userId));
    }

    public function setNewPasswordAfterForgot(SetNewPasswordAfterForgotRequest $request): JsonResponse
    {
        $userDeviceInformation = new UserDeviceInformationDTO(
            ip: $request->ip(),
            device: $request->header('User-Agent')
        );

        return $this->dispatchSync(new SetNewPasswordAfterForgotUseCase(
            otpId: $request->input('otp_id'),
            password: $request->input('password'),
            token: $request->input('token'),
            userDeviceInformation: $userDeviceInformation
        ));
    }

    public function logout(): JsonResponse
    {
        return $this->dispatchSync(new LogoutUseCase());
    }
}
