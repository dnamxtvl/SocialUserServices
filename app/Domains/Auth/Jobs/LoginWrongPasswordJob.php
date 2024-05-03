<?php

namespace App\Domains\Auth\Jobs;

use App\Domains\Auth\DTOs\SaveUserLoginHistoryDTO;
use App\Domains\Auth\DTOs\UserDeviceInformationDTO;
use App\Domains\Auth\Enums\TypeUserHistoryLoginEnum;
use App\Domains\Auth\Repository\BlockUserLoginTemporaryRepositoryInterface;
use App\Domains\Auth\Repository\UserLoginHistoryRepositoryInterface;
use App\Domains\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Models\User;

class LoginWrongPasswordJob
{
    public function __construct(
        private readonly string $email,
        private readonly UserDeviceInformationDTO $userDeviceInformation
    ) {
    }

    public function handle(
        UserRepositoryInterface $userRepository,
        UserLoginHistoryRepositoryInterface $userLoginHistoryRepository,
        BlockUserLoginTemporaryRepositoryInterface $blockUserLoginTemporaryRepository
    ): void {
        $user = $userRepository->getQuery(filters: ['email' => $this->email])->first();
        /** @var User $user */
        if ($user) {
            $saveUserLoginHistory = new SaveUserLoginHistoryDTO(
                user: $user,
                ip: $this->userDeviceInformation->getIp(),
                device: $this->userDeviceInformation->getDevice(),
                type: TypeUserHistoryLoginEnum::WRONG_PASSWORD
            );
            $userLoginHistoryRepository->save(saveUserLoginHistoryDTO: $saveUserLoginHistory);

            $countLoginWrongPassword = $user->userLoginHistories()
                ->where('ip', $this->userDeviceInformation->getIp())
                ->where('type', TypeUserHistoryLoginEnum::WRONG_PASSWORD->value)
                ->where('created_at', '>', now()->subHour())
                ->count();

            if ($countLoginWrongPassword >= config('auth.max_wrong_password')) {
                $blockUserLoginTemporaryRepository->save(
                    ip: $this->userDeviceInformation->getIp(),
                    userId: $user->id,
                    expiredAt: now()->addHour()
                );
            }
        }
    }
}
