<?php

namespace App\Features;

use App\Helpers\Command;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LogoutFeature extends Command
{
    public function handle(): JsonResponse
    {
        Auth::user()->token()->revoke();

        return $this->respondWithJson(content: []);
    }
}
