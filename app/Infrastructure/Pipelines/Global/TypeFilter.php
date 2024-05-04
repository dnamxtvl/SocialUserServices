<?php

namespace App\Infrastructure\Pipelines\Global;

use Illuminate\Database\Eloquent\Builder;

class TypeFilter
{
    public function __construct(
        private readonly array $filters,
    ) {
    }

    public function handle(Builder $query, $next)
    {
        if (isset($this->filters['type'])) {
            $query->where('type', $this->filters['type']);
        }

        return $next($query);
    }
}
