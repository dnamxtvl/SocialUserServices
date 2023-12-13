<?php

namespace App\Http\Controllers;

use App\Features\DTOs\LoginParamsDTO;
use App\Features\LoginFeature;
use App\Features\LogoutFeature;
use App\Http\Requests\LoginRequest;
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

    public function logout(): JsonResponse
    {
        return $this->dispatchSync(new LogoutFeature());
    }
}
