<?php

namespace App\Features;

use App\Domains\Auth\Jobs\LoginJob;
use App\Domains\User\DTOs\RegisterUserParamsDTO;
use App\Domains\User\Jobs\SaveUserRegisterJob;
use App\Helpers\Command;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Throwable;
use Illuminate\Auth\Events\Registered;

class RegisterUserFeature extends Command
{
    public function __construct(
        private readonly RegisterUserParamsDTO $registerUserParams,
    ) {
    }

    public function handle(): JsonResponse
    {
        try {
            $newUser = $this->dispatchSync(new SaveUserRegisterJob(
                registerUserParams: $this->registerUserParams
            ));

            $loginJobData = $this->dispatchSync(new LoginJob(
                email: $this->registerUserParams->getEmail(),
                password: $this->registerUserParams->getPassword(),
                rememberMe: false
            ));
            event(new Registered($newUser));

            return $this->respondWithJson(content: $loginJobData->toArray());
        } catch (Throwable $exception) {
            return $this->respondWithJsonError(e: $exception);
        }
    }
}
