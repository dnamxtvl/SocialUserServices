<?php

namespace App\Domains\Auth\Jobs;

use App\Domains\Auth\DTOs\SaveUserLoginHistoryDTO;
use App\Domains\Auth\Repository\UserLoginHistoryRepositoryInterface;
use App\Infrastructure\Models\User;

class SaveUserLoginHistoryJob
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private readonly SaveUserLoginHistoryDTO $saveUserLoginHistoryDTO
    ) {
    }

    public function handle(UserLoginHistoryRepositoryInterface $userLoginHistoryRepository): void
    {
        $currentUser = $this->saveUserLoginHistoryDTO->getUser();

        /** @var User $currentUser */
        if ($currentUser->latest_ip_login != $this->saveUserLoginHistoryDTO->getIp() ||
            ! $currentUser->userLoginHistories()->count()) {
            $userLoginHistoryRepository->save(saveUserLoginHistoryDTO: $this->saveUserLoginHistoryDTO);
        }

        $currentUser->latest_ip_login = $this->saveUserLoginHistoryDTO->getIp();
        $currentUser->latest_login = now();
        $currentUser->save();
    }
}
