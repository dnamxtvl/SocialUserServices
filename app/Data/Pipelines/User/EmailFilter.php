<?php

namespace App\Data\Pipelines\User;

use Illuminate\Database\Eloquent\Builder;

class EmailFilter
{
    public function __construct(
        private readonly array $filters,
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
