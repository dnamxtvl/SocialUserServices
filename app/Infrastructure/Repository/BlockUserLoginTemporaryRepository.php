<?php

namespace App\Infrastructure\Repository;

use App\Infrastructure\Pipelines\Global\UserIdFilter;
use App\Domains\Auth\Repository\BlockUserLoginTemporaryRepositoryInterface;
use App\Infrastructure\Models\BlockUserLoginTemporary;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

class BlockUserLoginTemporaryRepository implements BlockUserLoginTemporaryRepositoryInterface
{
    public function __construct(
        private readonly BlockUserLoginTemporary $blockUserLoginTemporary
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
}
