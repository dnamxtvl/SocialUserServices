<?php

namespace App\Infrastructure\Repository;

use App\Infrastructure\Pipelines\Global\TypeFilter;
use App\Infrastructure\Pipelines\Global\UserIdFilter;
use App\Domains\Auth\DTOs\SaveUserLoginHistoryDTO;
use App\Domains\Auth\Repository\UserLoginHistoryRepositoryInterface;
use App\Infrastructure\Models\User;
use App\Infrastructure\Models\UserLoginHistory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

class UserLoginHistoryRepository implements UserLoginHistoryRepositoryInterface
{
    public function __construct(
        private readonly UserLoginHistory $userLoginHistory
    ) {
    }

    public function getQuery(array $columnSelects = [], array $filters = []): Builder
    {
        $query = $this->userLoginHistory->query();
        if (count($columnSelects)) {
            $query->select($columnSelects);
        }

        return app(Pipeline::class)
            ->send($query)
            ->through([
                new UserIdFilter(filters: $filters),
                new TypeFilter(filters: $filters),
            ])
            ->thenReturn();
    }

    public function save(SaveUserLoginHistoryDTO $saveUserLoginHistoryDTO): void
    {
        $userLoginHistory = new UserLoginHistory();

        $user = $saveUserLoginHistoryDTO->getUser();
        /**
         * @var User $user
         **/
        $userLoginHistory->ip = $saveUserLoginHistoryDTO->getIp();
        $userLoginHistory->user_id = $user->id;
        $userLoginHistory->device = $saveUserLoginHistoryDTO->getDevice();
        $userLoginHistory->type = $saveUserLoginHistoryDTO->getType()->value;
        $userLoginHistory->save();
    }
}
