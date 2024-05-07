<?php

namespace App\Application\UseCases;

use App\Application\Command;
use App\Infrastructure\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LogoutUseCase extends Command
{
    public function handle(): JsonResponse
    {
        $user = Auth::user();
        /** @var User $user */
        $user->token()->revoke();

        return $this->respondWithJson(content: []);
    }
}
