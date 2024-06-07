<?php

namespace App\Domains\User\DTOs;

use App\Domains\User\Enums\TypeAccountEnum;
use App\Domains\User\Enums\UserGenderEnums;

readonly class RegisterUserParamsDTO
{
    public function __construct(
        private string $firstname,
        private string $lastname,
        private string $email,
        private string $password,
        private int $dayOfBirth,
        private int $monthOfBirth,
        private int $yearOfBirth,
        private UserGenderEnums $gender,
        private int $fromCityId,
        private int $fromDistrictId,
        private int $fromWardId,
        private int $currentCityId,
        private int $currentDistrictId,
        private int $currentWardId,
        private TypeAccountEnum $typeAccount,
        private int $organizationId,
        private int $unitRoomId,
        private ?string $identityId = null,
    ) {
    }

    public function getIdentityId(): ?string
    {
        return $this->identityId;
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

    public function getFromCityId(): int
    {
        return $this->fromCityId;
    }

    public function getFromDistrictId(): int
    {
        return $this->fromDistrictId;
    }

    public function getFromWardId(): int
    {
        return $this->fromWardId;
    }

    public function getCurrentCityId(): int
    {
        return $this->currentCityId;
    }

    public function getCurrentDistrictId(): int
    {
        return $this->currentDistrictId;
    }

    public function getCurrentWardId(): int
    {
        return $this->currentWardId;
    }

    public function getTypeAccount(): TypeAccountEnum
    {
        return $this->typeAccount;
    }

    public function getOrganizationId(): int
    {
        return $this->organizationId;
    }

    public function getUnitRoomId(): int
    {
        return $this->unitRoomId;
    }
}
