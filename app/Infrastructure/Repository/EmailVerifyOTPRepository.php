<?php

namespace App\Infrastructure\Repository;

use App\Domains\Auth\DTOs\SaveEmailVerifyOTPDTO;
use App\Domains\Auth\Entities\User\EmailVerifyOTP as EmailVerifyOTPDomain;
use App\Domains\Auth\Enums\TypeCodeOTPEnum;
use App\Domains\Auth\Repository\EmailVerifyOTPRepositoryInterface;
use App\Infrastructure\Models\EmailVerifyOTP;
use App\Infrastructure\Pipelines\EmailVerifyOTP\VerifyCodeOTPFilter;
use App\Infrastructure\Pipelines\Global\TypeFilter;
use App\Infrastructure\Pipelines\Global\UserIdFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;

readonly class EmailVerifyOTPRepository implements EmailVerifyOTPRepositoryInterface
{
    public function __construct(
        private EmailVerifyOTP $emailVerifyOtp
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
        $emailVerifyOtp = new EmailVerifyOTP();

        $emailVerifyOtp->code = $saveEmailVerify->getCode();
        $emailVerifyOtp->user_id = $saveEmailVerify->getUserId();
        $emailVerifyOtp->expired_at = $saveEmailVerify->getExpiredAt();
        $emailVerifyOtp->type = $saveEmailVerify->getType();
        $emailVerifyOtp->token = $saveEmailVerify->getToken();
        $emailVerifyOtp->save();

        return $emailVerifyOtp;
    }

    public function findById(string $emailVerifyOtpId): ?EmailVerifyOTPDomain
    {
        $emailVerifyOtp = $this->emailVerifyOtp->query()->find(id: $emailVerifyOtpId);

        /** @var EmailVerifyOTP $emailVerifyOtp */
        return is_null($emailVerifyOtp) ? null : $this->mappingEmailVerifyOTPEloquentToDomain(emailVerifyOtp: $emailVerifyOtp);
    }

    public function findByCondition(array $filters = []): ?EmailVerifyOTPDomain
    {
        /** @var EmailVerifyOTP $emailVerifyOtp */
        $emailVerifyOtp = $this->getQuery(filters: $filters)->first();

        return is_null($emailVerifyOtp) ? null : $this->mappingEmailVerifyOTPEloquentToDomain(emailVerifyOtp: $emailVerifyOtp);
    }

    public function deleteByUserIdAndType(string $userId, TypeCodeOTPEnum $type): bool
    {
        return $this->emailVerifyOtp->query()
            ->where('user_id', $userId)
            ->where('type', $type->value)
            ->delete();
    }

    public function mappingEmailVerifyOTPEloquentToDomain(EmailVerifyOTP $emailVerifyOtp): EmailVerifyOTPDomain
    {
        return new EmailVerifyOTPDomain(
            id: $emailVerifyOtp->id,
            code: $emailVerifyOtp->code,
            userId: $emailVerifyOtp->user_id,
            expiredAt: $emailVerifyOtp->expired_at,
            token: $emailVerifyOtp->token,
            type: TypeCodeOTPEnum::from($emailVerifyOtp->type)
        );
    }
}
