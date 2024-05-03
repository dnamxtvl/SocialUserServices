<?php

namespace App\Infrastructure\Pipelines\EmailVerifyOTP;

use Illuminate\Database\Eloquent\Builder;

class VerifyCodeOTPFilter
{
    public function __construct(
        private readonly array $filters,
    ) {
    }

    public function handle(Builder $query, $next)
    {
        if (isset($this->filters['code'])) {
            $query->where('code', $this->filters['code']);
        }

        return $next($query);
    }
}
