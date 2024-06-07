<?php

namespace App\Infrastructure\Repository;

use App\Domains\Auth\Entities\User\BlockUserLoginTemporary as BlockUserLoginTemporaryDomain;
use App\Domains\Auth\Repository\BlockUserLoginTemporaryRepositoryInterface;
use App\Infrastructure\Models\BlockUserLoginTemporary;
use App\Infrastructure\Pipelines\Global\UserIdFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

readonly class BlockUserLoginTemporaryRepository implements BlockUserLoginTemporaryRepositoryInterface
{
    public function __construct(
        private BlockUserLoginTemporary $blockUserLoginTemporary
    ) {
    }

    public function getQuery(array $columnSelects = [], array $filters = []): Builder
    {
        $query = $this->blockUserLoginTemporary->query();
        if (count($columnSelects)) {
            $query->select($columnSelects);
        }

        return app(Pipeline::class)
            ->send($query)
            ->through([
                new UserIdFilter(filters: $filters),
            ])
            ->thenReturn();
    }

    public function save(string $ip, string $userId, Carbon $expiredAt): void
    {
        $blockUserLoginTemporary = new BlockUserLoginTemporary();

        $blockUserLoginTemporary->ip = $ip;
        $blockUserLoginTemporary->user_id = $userId;
        $blockUserLoginTemporary->expired_at = $expiredAt;
        $blockUserLoginTemporary->save();
    }

    public function findByUserAndIp(string $ip, string $userId): ?BlockUserLoginTemporaryDomain
    {
        $blockUser = $this->blockUserLoginTemporary->query()
            ->where('ip', $ip)
            ->where('user_id', $userId)
            ->first();

        /** @var BlockUserLoginTemporary $blockUser */
        return is_null($blockUser) ? null : new BlockUserLoginTemporaryDomain(
            userId: $blockUser->user_id,
            expiredAt: $blockUser->expired_at,
            createdAt: $blockUser->created_at, id: $blockUser->id,
            ip: $blockUser->ip
        );
    }
}
