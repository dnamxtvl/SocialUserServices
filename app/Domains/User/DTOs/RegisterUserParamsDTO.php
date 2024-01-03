<?php

namespace App\Domains\User\DTOs;

use App\Domains\User\Enums\UserGenderEnums;

class RegisterUserParamsDTO
{
    public function __construct(
        private readonly string $firstname,
        private readonly string $lastname,
        private readonly string $email,
        private readonly string $password,
        private readonly int $dayOfBirth,
        private readonly int $monthOfBirth,
        private readonly int $yearOfBirth,
        private readonly UserGenderEnums $gender,
    ) {
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getDayOfBirth(): int
    {
        return $this->dayOfBirth;
    }

    public function getMonthOfBirth(): int
    {
        return $this->monthOfBirth;
    }

    public function getYearOfBirth(): int
    {
        return $this->yearOfBirth;
    }

    public function getGender(): UserGenderEnums
    {
        return $this->gender;
    }
}
