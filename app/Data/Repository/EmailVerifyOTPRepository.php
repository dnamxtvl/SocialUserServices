<?php

namespace App\Data\Repository;

use App\Data\Pipelines\EmailVerifyOTP\VerifyCodeOTPFilter;
use App\Data\Pipelines\Global\TypeFilter;
use App\Data\Pipelines\Global\UserIdFilter;
use App\Domains\Auth\DTOs\SaveEmailVerifyOTPDTO;
use App\Domains\Auth\Repository\EmailVerifyOTPRepositoryInterface;
use App\Models\EmailVerifyOTO;
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
                new TypeFilter(filters: $filters),
            ])
            ->thenReturn();
    }

    public function save(SaveEmailVerifyOTPDTO $saveEmailVerify): Model
    {
        $emailVerifyOtp = new EmailVerifyOTO();

        $emailVerifyOtp->code = $saveEmailVerify->getCode();
        $emailVerifyOtp->user_id = $saveEmailVerify->getUserId();
        $emailVerifyOtp->expired_at = $saveEmailVerify->getExpiredAt();
        $emailVerifyOtp->type = $saveEmailVerify->getType();
        $emailVerifyOtp->token = $saveEmailVerify->getToken();
        $emailVerifyOtp->save();

        return $emailVerifyOtp;
    }

    public function findById(string $emailVerifyOtpId): ?Model
    {
        return $this->emailVerifyOtp->query()->find(id: $emailVerifyOtpId);
    }
}
