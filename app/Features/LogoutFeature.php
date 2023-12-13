<?php

namespace App\Features;

use App\Helpers\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LogoutFeature extends Service
{
    public function handle(): JsonResponse
    {
        Auth::user()->token()->revoke();

        return $this->respondWithJson(content: []);
    }
}
