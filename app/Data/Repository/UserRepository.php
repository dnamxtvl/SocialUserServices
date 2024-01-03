<?php

namespace App\Data\Repository;

use App\Domains\User\DTOs\RegisterUserParamsDTO;
use App\Domains\User\Enums\UserStatusEnum;
use App\Domains\User\Repository\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
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
