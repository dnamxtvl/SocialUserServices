<?php

namespace App\Data\Repository;

use App\Data\Pipelines\EmailVerifyOTP\VerifyCodeOTPFilter;
use App\Data\Pipelines\Global\UserIdFilter;
use App\Domains\Auth\Repository\EmailVerifyOTPRepositoryInterface;
use App\Models\EmailVerifyOTO;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;

class EmailVerifyOTPRepository implements EmailVerifyOTPRepositoryInterface
{
    public function __construct(
        private readonly EmailVerifyOTO $emailVerifyOtp
    ) {
    }

    public function getQuery(array $columnSelects = [], array $filters = []): Builder
    {
        $query = $this->emailVerifyOtp->query();
        if (count($columnSelects)) {
            $query->select($columnSelects);
        }

        return app(Pipeline::class)
            ->send($query)
            ->through([
                new VerifyCodeOTPFilter(filters: $filters),
                new UserIdFilter(filters: $filters),
            ])
            ->thenReturn();
    }

    public function save(string $code, string $userId, Carbon $expiredAt): Model
    {
        $emailVerifyOtp = new EmailVerifyOTO();

        $emailVerifyOtp->code = $code;
        $emailVerifyOtp->user_id = $userId;
        $emailVerifyOtp->expired_at = $expiredAt;
        $emailVerifyOtp->save();

        return $emailVerifyOtp;
    }
}
