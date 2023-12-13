<?php

namespace App\Features;

use App\Domains\Auth\DTOs\SaveUserLoginHistoryDTO;
use App\Domains\Auth\Jobs\LoginJob;
use App\Domains\Auth\Jobs\SaveUserLoginHistoryJob;
use App\Features\DTOs\LoginParamsDTO;
use App\Helpers\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class LoginFeature extends Service
{
    public function __construct(
        private readonly LoginParamsDTO $loginParams,
        private readonly ?string $ip,
        private readonly string $device,
    ) {}

    public function handle(): JsonResponse
    {
        DB::beginTransaction();
        try {
            $loginJobData = $this->dispatchSync(new LoginJob(
                email: $this->loginParams->getEmail(),
                password: $this->loginParams->getPassword(),
                rememberMe: $this->loginParams->getRememberMe()
            ));

            $saveUserLoginHistory = new SaveUserLoginHistoryDTO(
                userId: $loginJobData->getUser()->id,
                ip: $this->ip,
                device: $this->device,
            );
            $this->dispatchSync(new SaveUserLoginHistoryJob(saveUserLoginHistoryDTO: $saveUserLoginHistory));

            DB::commit();
            return $this->respondWithJson(content: $loginJobData->toArray());
        } catch (Throwable $exception) {
            DB::rollBack();
            return $this->respondWithJsonError(e: $exception);
        }
    }
}
