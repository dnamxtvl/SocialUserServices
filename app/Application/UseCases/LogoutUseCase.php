<?php

namespace App\Application\UseCases;

use App\Application\Command;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LogoutUseCase extends Command
{
    public function handle(): JsonResponse
    {
        Auth::user()->token()->revoke();

        return $this->respondWithJson(content: []);
    }
}
