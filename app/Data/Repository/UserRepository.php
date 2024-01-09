<?php

namespace App\Data\Repository;

use App\Data\Pipelines\User\EmailFilter;
use App\Domains\User\DTOs\RegisterUserParamsDTO;
use App\Domains\User\Enums\UserStatusEnum;
use App\Domains\User\Repository\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly User $user
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

    public function findById(string $userId): ?Model
    {
        return $this->user->query()->find(id: $userId);
    }

    public function save(RegisterUserParamsDTO $registerUserParams): Model
    {
        $user = new User();

        $user->first_name = $registerUserParams->getFirstname();
        $user->last_name = $registerUserParams->getLastname();
        $user->email = $registerUserParams->getEmail();
        $user->password = Hash::make($registerUserParams->getPassword());
        $user->day_of_birth = $registerUserParams->getDayOfBirth();
        $user->month_of_birth = $registerUserParams->getMonthOfBirth();
        $user->year_of_birth = $registerUserParams->getYearOfBirth();
        $user->gender = $registerUserParams->getGender()->value;
        $user->status = UserStatusEnum::NOT_VERIFIED->value;
        $user->save();

        return $user;
    }
}
