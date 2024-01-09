<?php

namespace App\Features;

use App\Domains\Auth\DTOs\SaveUserLoginHistoryDTO;
use App\Domains\Auth\Exceptions\EmailNotVerifyException;
use App\Domains\Auth\Jobs\SaveUserLoginHistoryJob;
use App\Features\DTOs\LoginParamsDTO;
use App\Helpers\Command;
use App\Operations\LoginOperation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class LoginFeature extends Command
{
    public function __construct(
        private readonly LoginParamsDTO $loginParams,
        private readonly ?string $ip,
        private readonly string $device,
    ) {
    }

    public function handle(): JsonResponse
    {
        DB::beginTransaction();
        try {
            $loginJobData = $this->dispatchSync(new LoginOperation(
                email: $this->loginParams->getEmail(),
                password: $this->loginParams->getPassword(),
                rememberMe: $this->loginParams->getRememberMe()
            ));

            $saveUserLoginHistory = new SaveUserLoginHistoryDTO(
                user: $loginJobData->getUser(),
                ip: $this->ip,
                device: $this->device,
            );
            $this->dispatchSync(new SaveUserLoginHistoryJob(saveUserLoginHistoryDTO: $saveUserLoginHistory));

            DB::commit();

            return $this->respondWithJson(content: $loginJobData->toArray());
        } catch (Throwable $exception) {
            if ($exception instanceof EmailNotVerifyException) {
                DB::commit();
            } else {
                DB::rollBack();
            }

            return $this->respondWithJsonError(e: $exception);
        }
    }
}
