<?php

namespace App\Infrastructure\Pipelines\Global;

use Illuminate\Database\Eloquent\Builder;

class UserIdFilter
{
    public function __construct(
        private readonly array $filters,
    ) {
    }

    public function handle(Builder $query, $next)
    {
        if (isset($this->filters['user_id'])) {
            $query->where('user_id', $this->filters['user_id']);
        }

        return $next($query);
    }
}
