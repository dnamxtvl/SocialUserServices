<?php

namespace App\Features;

use App\Domains\Auth\Jobs\LoginJob;
use App\Domains\User\DTOs\RegisterUserParamsDTO;
use App\Domains\User\Jobs\SaveUserRegisterJob;
use App\Helpers\Command;
use Illuminate\Http\JsonResponse;
use Throwable;

class RegisterUserFeature extends Command
{
    public function __construct(
        private readonly RegisterUserParamsDTO $registerUserParams,
    ) {
    }

    public function handle(): JsonResponse
    {
        try {
            $this->dispatchSync(new SaveUserRegisterJob(
                registerUserParams: $this->registerUserParams
            ));

            $loginJobData = $this->dispatchSync(new LoginJob(
                email: $this->registerUserParams->getEmail(),
                password: $this->registerUserParams->getPassword(),
                rememberMe: false
            ));

            return $this->respondWithJson(content: $loginJobData->toArray());
        } catch (Throwable $exception) {
            return $this->respondWithJsonError(e: $exception);
        }
    }
}
