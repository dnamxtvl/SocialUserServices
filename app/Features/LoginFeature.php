<?php

namespace App\Features;

use App\Domains\Auth\DTOs\SaveUserLoginHistoryDTO;
use App\Domains\Auth\DTOs\UserDeviceInformationDTO;
use App\Domains\Auth\Enums\TypeUserHistoryLoginEnum;
use App\Domains\Auth\Exceptions\EmailNotVerifyException;
use App\Domains\Auth\Jobs\SaveUserLoginHistoryJob;
use App\Features\DTOs\LoginParamsDTO;
use App\Helpers\Command;
use App\Operations\LoginOperation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class LoginFeature extends Command
{
    public function __construct(
        private readonly LoginParamsDTO $loginParams,
        private readonly UserDeviceInformationDTO $userDeviceInformation
    ) {
    }

    public function handle(): JsonResponse
    {
        DB::beginTransaction();
        try {
            $loginJobData = $this->dispatchSync(new LoginOperation(
                loginParams: $this->loginParams,
                userDeviceInformation: $this->userDeviceInformation
            ));

            $saveUserLoginHistory = new SaveUserLoginHistoryDTO(
                user: $loginJobData->getUser(),
                ip: $this->userDeviceInformation->getIp(),
                device: $this->userDeviceInformation->getDevice(),
                type: TypeUserHistoryLoginEnum::LOGIN_SUCCESS_NEW_IP
            );
            $this->dispatchSync(new SaveUserLoginHistoryJob(saveUserLoginHistoryDTO: $saveUserLoginHistory));

            DB::commit();

            return $this->respondWithJson(content: $loginJobData->toArray());
        } catch (Throwable $exception) {
            if ($exception instanceof EmailNotVerifyException || $exception instanceof UnauthorizedHttpException) {
                DB::commit();
            } else {
                DB::rollBack();
            }

            return $this->respondWithJsonError(e: $exception);
        }
    }
}
