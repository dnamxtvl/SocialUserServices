<?php

namespace App\Infrastructure\Pipelines\User;

use Illuminate\Database\Eloquent\Builder;

readonly class EmailFilter
{
    public function __construct(
        private array $filters,
    ) {
    }

    public function handle(Builder $query, $next)
    {
        if (isset($this->filters['email'])) {
            $query->where('email', $this->filters['email']);
        }

        return $next($query);
    }
}
