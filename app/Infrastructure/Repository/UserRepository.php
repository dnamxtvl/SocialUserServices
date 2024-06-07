<?php

namespace App\Infrastructure\Repository;

use App\Domains\Auth\Entities\User\User as UserDomain;
use App\Domains\Auth\ValueObjects\Email;
use App\Domains\Auth\ValueObjects\Residence;
use App\Domains\Auth\ValueObjects\StatusActive;
use App\Domains\Auth\ValueObjects\Worker;
use App\Domains\User\Enums\TypeAccountEnum;
use App\Domains\User\Enums\UserExceptionEnum;
use App\Domains\User\Enums\UserGenderEnums;
use App\Domains\User\Enums\UserStatusActiveUnum;
use App\Domains\User\Enums\UserStatusEnum;
use App\Domains\User\Exceptions\UserNotFoundException;
use App\Domains\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Models\User;
use App\Infrastructure\Pipelines\User\EmailFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;

readonly class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private User $user
    ) {
    }

    public function getQuery(array $columnSelects = [], array $filters = []): Builder
    {
        $query = $this->user->query();
        if (count($columnSelects)) {
            $query->select($columnSelects);
        }

        return app(Pipeline::class)
            ->send($query)
            ->through([
                new EmailFilter(filters: $filters),
            ])
            ->thenReturn();
    }

    public function findById(string $userId): ?UserDomain
    {
        $user = $this->user->query()->find(id: $userId);

        /** @var ?User $user */
        return is_null($user) ? null : $this->mappingUserEloquentToDomain(user: $user);
    }

    public function save(UserDomain $userDomain): UserDomain
    {
        if (is_null($userDomain->getId())) {
            $user = new User();
        } else {
            $user = $this->user->query()->find(id: $userDomain->getId());
            if (is_null($user)) {
                throw new UserNotFoundException(code: UserExceptionEnum::USER_NOT_FOUND_WHEN_UPDATE_IN_REPO->value);
            }
        }

        $user->fill(attributes: $userDomain->toArray());
        $user->save();

        return $userDomain;
    }

    public function getMaxUserCode(): int
    {
        return (int) $this->user->query()->max('user_code');
    }

    public function findByEmail(string $email): ?UserDomain
    {
        $user = $this->user->query()->where('email', $email)->first();

        /** @var ?User $user */
        return is_null($user) ? null : $this->mappingUserEloquentToDomain(user: $user);
    }

    public function findByIdEloquent(UserDomain $userDomain): ?Model
    {
        return new User($userDomain->toArray());
    }

    private function mappingUserEloquentToDomain(User $user): UserDomain
    {
        return new UserDomain(
            identityId: $user->identity_id,
            userCode: $user->user_code,
            firstName: $user->first_name,
            lastName: $user->last_name,
            email: new Email($user->email),
            typeAccount: TypeAccountEnum::tryFrom($user->type_account),
            gender: UserGenderEnums::tryFrom($user->gender),
            status: UserStatusEnum::tryFrom($user->status),
            birthday: Carbon::createFromDate(year: $user->year_of_birth, month: $user->month_of_birth, day: $user->day_of_birth),
            placeOfBirth: new Residence(
                cityId: $user->from_city_id,
                districtId: $user->from_district_id,
                wardId: $user->from_ward_id
            ),
            statusActive: new StatusActive(
                isActive: $user->status_active === UserStatusActiveUnum::ONLINE->value,
                latestLogin: $user->latest_login,
                latestIpLogin: $user->latest_ip_login, latestActiveAt: $user->latest_active_at
            ),
            job: new Worker(
                organizationId: $user->organization_id,
                unitRoomId: $user->unit_room_id,
                positionId: $user->position_id,
                jobId: $user->job_id
            ),
            password: $user->password,
            id: $user->id,
            emailVerifiedAt: $user->email_verified_at,
            currentResidence: new Residence(
                cityId: $user->current_city_id,
                districtId: $user->current_district_id,
                wardId: $user->current_ward_id
            ),
            createdAt: $user->created_at
        );
    }
}
