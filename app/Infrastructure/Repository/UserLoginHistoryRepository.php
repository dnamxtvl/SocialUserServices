<?php

namespace App\Infrastructure\Repository;

use App\Domains\Auth\Entities\User\UserLoginHistory as UserLoginHistoryDomain;
use App\Domains\Auth\Repository\UserLoginHistoryRepositoryInterface;
use App\Infrastructure\Models\UserLoginHistory;
use App\Infrastructure\Pipelines\Global\IpFilter;
use App\Infrastructure\Pipelines\Global\TypeFilter;
use App\Infrastructure\Pipelines\Global\UserIdFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

readonly class UserLoginHistoryRepository implements UserLoginHistoryRepositoryInterface
{
    public function __construct(
        private UserLoginHistory $userLoginHistory
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
                new IpFilter(filters: $filters),
            ])
            ->thenReturn();
    }

    public function save(UserLoginHistoryDomain $userLoginHistoryDomain): void
    {
        $userLoginHistory = new UserLoginHistory();

        $userLoginHistory->ip = $userLoginHistoryDomain->getIp();
        $userLoginHistory->user_id = $userLoginHistoryDomain->getUserId();
        $userLoginHistory->device = $userLoginHistoryDomain->getDevice();
        $userLoginHistory->type = $userLoginHistoryDomain->getType()->value;
        $userLoginHistory->save();
    }
}
