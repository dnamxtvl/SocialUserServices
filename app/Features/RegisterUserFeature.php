<?php

namespace App\Features;

use App\Domains\Auth\Enums\TypeCodeOTPEnum;
use App\Domains\Auth\Jobs\CreateEmailVerifyOTPJob;
use App\Domains\User\DTOs\RegisterUserParamsDTO;
use App\Domains\Auth\Jobs\SaveUserRegisterJob;
use App\Helpers\Command;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class RegisterUserFeature extends Command
{
    public function __construct(
        private readonly RegisterUserParamsDTO $registerUserParams,
    ) {
    }

    public function handle(): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newUser = $this->dispatchSync(new SaveUserRegisterJob(
                registerUserParams: $this->registerUserParams
            ));

            $this->dispatchSync(new CreateEmailVerifyOTPJob(user: $newUser, type: TypeCodeOTPEnum::VERIFY_EMAIL));

            DB::commit();

            return $this->respondWithJson(content: $newUser->toArray());
        } catch (Throwable $exception) {
            DB::rollBack();

            return $this->respondWithJsonError(e: $exception);
        }
    }
}
