<?php

namespace App\Domains\Auth\Entities\User;

use App\Domains\Auth\DTOs\UserDeviceInformationDTO;
use App\Domains\Auth\DTOs\UserLoginResponseDataDTO;
use App\Domains\Auth\Enums\AuthExceptionEnum;
use App\Domains\Auth\Enums\TypeCodeOTPEnum;
use App\Domains\Auth\Enums\TypeUserHistoryLoginEnum;
use App\Domains\Auth\Exceptions\EmailVerifiedException;
use App\Domains\Auth\Exceptions\InvalidOTPException;
use App\Domains\Auth\Repository\BlockUserLoginTemporaryRepositoryInterface;
use App\Domains\Auth\Repository\EmailVerifyOTPRepositoryInterface;
use App\Domains\Auth\Repository\UserLoginHistoryRepositoryInterface;
use App\Domains\Auth\ValueObjects\Email;
use App\Domains\Auth\ValueObjects\Residence;
use App\Domains\Auth\ValueObjects\StatusActive;
use App\Domains\Auth\ValueObjects\Worker;
use App\Domains\User\Enums\TypeAccountEnum;
use App\Domains\User\Enums\UserGenderEnums;
use App\Domains\User\Enums\UserRelationshipStatusEnum;
use App\Domains\User\Enums\UserStatusEnum;
use App\Domains\User\Repository\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Laravel\Passport\Token;

class User
{
    public function __construct(
        private ?string $identityId,
        private string $userCode,
        private string $firstName,
        private string $lastName,
        private Email $email,
        private TypeAccountEnum $typeAccount,
        private UserGenderEnums $gender,
        private UserStatusEnum $status,
        private Carbon $birthday,
        private Residence $placeOfBirth,
        private StatusActive $statusActive,
        private Worker $job,
        private ?string $aboutMe = null,
        private ?string $password = null,
        private ?string $id = null,
        private ?Carbon $emailVerifiedAt = null,
        private ?string $address = null,
        private ?string $phoneNumber = null,
        private ?string $avatar = null,
        private ?string $backgroundProfile = null,
        private ?Residence $currentResidence = null,
        private ?Carbon $createdAt = null,
        private readonly ?UserRelationshipStatusEnum $relationshipStatus = null,
    ) {
        $this->validateIdentityId(identityId: $this->identityId);
        $this->validatePassword(password: $this->password);
    }

    private function validatePassword(?string $password): void
    {
        if (Str::length($password)) {
            if (Str::length($password) < config('validation.password.min_length') ||
                Str::length($password) > config('validation.password.max_length')
            ) {
                throw new InvalidArgumentException('Password phải từ 8-30 ký tự.');
            }

            $this->password = Hash::make(value: $this->password);
        }
    }

    private function validateIdentityId(?string $identityId): void
    {
        if ($this->typeAccount != TypeAccountEnum::CHILD && is_null($identityId)) {
            throw new InvalidArgumentException('Identity id đang để trống.');
        }

        if (! is_null($identityId) && Str::length($identityId) != config('validation.identity_id.length')) {
            throw new InvalidArgumentException('Identity không hợp lệ.');
        }
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIdentityId(): ?string
    {
        return $this->identityId;
    }

    public function getUserCode(): string
    {
        return $this->userCode;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getEmailVerifiedAt(): ?Carbon
    {
        return $this->emailVerifiedAt;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function getBackgroundProfile(): ?string
    {
        return $this->backgroundProfile;
    }

    public function getBirthday(): ?Carbon
    {
        return $this->birthday;
    }

    public function getPlaceOfBirth(): ?Residence
    {
        return $this->placeOfBirth;
    }

    public function getCurrentResidence(): ?Residence
    {
        return $this->currentResidence;
    }

    public function getTypeAccount(): TypeAccountEnum
    {
        return $this->typeAccount;
    }

    public function getJob(): Worker
    {
        return $this->job;
    }

    public function getGender(): UserGenderEnums
    {
        return $this->gender;
    }

    public function getAboutMe(): ?string
    {
        return $this->aboutMe;
    }

    public function getStatus(): UserStatusEnum
    {
        return $this->status;
    }

    public function getStatusActive(): StatusActive
    {
        return $this->statusActive;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->createdAt;
    }

    public function setIdentityId(string $identityId): void
    {
        $this->identityId = $identityId;
    }

    public function setUserCode(string $userCode): void
    {
        $this->userCode = $userCode;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    public function setEmailVerifiedAt(?Carbon $emailVerifiedAt): void
    {
        $this->emailVerifiedAt = $emailVerifiedAt;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function setBackgroundProfile(?string $backgroundProfile): void
    {
        $this->backgroundProfile = $backgroundProfile;
    }

    public function setBirthday(?Carbon $birthday): void
    {
        $this->birthday = $birthday;
    }

    public function setPlaceOfBirth(?Residence $placeOfBirth): void
    {
        $this->placeOfBirth = $placeOfBirth;
    }

    public function setCurrentResidence(?Residence $currentResidence): void
    {
        $this->currentResidence = $currentResidence;
    }

    public function setTypeAccount(TypeAccountEnum $typeAccount): void
    {
        $this->typeAccount = $typeAccount;
    }

    public function setJob(?Worker $job): void
    {
        $this->job = $job;
    }

    public function setGender(UserGenderEnums $gender): void
    {
        $this->gender = $gender;
    }

    public function setAboutMe(?string $aboutMe): void
    {
        $this->aboutMe = $aboutMe;
    }

    public function getRelationshipStatus(): ?UserRelationshipStatusEnum
    {
        return $this->relationshipStatus;
    }

    public function setStatus(UserStatusEnum $status): void
    {
        $this->status = $status;
    }

    public function setStatusActive(StatusActive $statusActive): void
    {
        $this->statusActive = $statusActive;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setCreatedAt(Carbon $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'identity_id' => $this->getIdentityId(),
            'user_code' => $this->getUserCode(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'email' => $this->getEmail()->getStringEmail(),
            'email_verified_at' => $this->getEmailVerifiedAt(),
            'address' => $this->getAddress(),
            'phone_number' => $this->getPhoneNumber(),
            'avatar' => $this->getAvatar(),
            'password' => $this->getPassword(),
            'background_profile' => $this->getBackgroundProfile(),
            'from_ward_id' => $this->getPlaceOfBirth()->getWardId(),
            'from_district_id' => $this->getPlaceOfBirth()->getDistrictId(),
            'from_city_id' => $this->getPlaceOfBirth()->getCityId(),
            'current_ward_id' => $this->getCurrentResidence()?->getWardId(),
            'current_district_id' => $this->getCurrentResidence()?->getDistrictId(),
            'current_city_id' => $this->getCurrentResidence()?->getCityId(),
            'type_account' => $this->getTypeAccount()->value,
            'job_id' => $this->getJob()->getJobId(),
            'organization_id' => $this->getJob()->getOrganizationId(),
            'unit_room_id' => $this->getJob()->getUnitRoomId(),
            'position_id' => $this->getJob()->getPositionId(),
            'relationship_status' => $this->getRelationshipStatus()?->value,
            'gender' => $this->getGender()->value,
            'day_of_birth' => $this->getBirthday()->day,
            'month_of_birth' => $this->getBirthday()->month,
            'year_of_birth' => $this->getBirthday()->year,
            'about_me' => $this->getAboutMe(),
            'latest_login' => $this->getStatusActive()->getLatestLogin(),
            'latest_ip_login' => $this->getStatusActive()->getLatestIpLogin(),
            'last_activity_at' => $this->getStatusActive()->getLatestActiveAt(),
            'status' => $this->getStatus()->value,
            'status_active' => $this->getStatusActive()->getIsActive(),
        ];
    }

    /**
     * @throws BindingResolutionException
     */
    public function checkIsBlockUserLogin(string $ip): bool
    {
        $isBlock = false;
        $userBlockLoginTemporaryRepository = app()->make(BlockUserLoginTemporaryRepositoryInterface::class);
        $lockUserLoginTemporary = $userBlockLoginTemporaryRepository->findByUserAndIp(
            ip: $ip,
            userId: $this->id
        );
        if ($lockUserLoginTemporary && ! now()->gt(date: $lockUserLoginTemporary->getExpiredAt())) {
            $isBlock = true;
        }

        return $isBlock;
    }

    /**
     * @throws BindingResolutionException
     */
    public function userLoginWrongPasswordAction(UserDeviceInformationDTO $userDeviceInformation): void
    {
        $userLoginHistory = new UserLoginHistory(
            userId: $this->id,
            ip: $userDeviceInformation->getIp(),
            device: $userDeviceInformation->getDevice(),
            type: TypeUserHistoryLoginEnum::WRONG_PASSWORD
        );
        $userLoginHistoryRepository = app()->make(UserLoginHistoryRepositoryInterface::class);
        $userLoginHistoryRepository->save(userLoginHistoryDomain: $userLoginHistory);

        $countLoginWrongPassword = $userLoginHistoryRepository->getQuery(
            filters: [
                'ip' => $userDeviceInformation->getIp(),
                'type' => TypeUserHistoryLoginEnum::WRONG_PASSWORD->value,
                'user_id' => $this->id,
            ]
        )
            ->where('created_at', '>', now()->subHour())
            ->count();

        if ($countLoginWrongPassword >= config('auth.max_wrong_password')) {
            app()->make(BlockUserLoginTemporaryRepositoryInterface::class)->save(
                ip: $userDeviceInformation->getIp(),
                userId: $this->id,
                expiredAt: now()->addHour()
            );
        }
    }

    public function createTokenLoginJwt(bool $rememberMe = false): UserLoginResponseDataDTO
    {
        $user = Auth::user();
        /** @var \App\Infrastructure\Models\User $user */
        $tokenResult = $user->createToken('API Token');
        $token = $tokenResult->token;
        /** @var Token $token */
        if ($rememberMe) {
            $token->setAttribute('expires_at', now()->addWeek());
            $token->save();
        }

        return new UserLoginResponseDataDTO(
            user: $this,
            token: $tokenResult->accessToken,
            expiresAt: Carbon::parse($token->getAttribute(/** @lang text */ 'expires_at'))
        );
    }

    /**
     * @throws BindingResolutionException
     */
    public function toEloquent(): ?Model
    {
        return app()->make(UserRepositoryInterface::class)->findByIdEloquent(userDomain: $this);
    }

    /**
     * @throws BindingResolutionException
     */
    public function verifyEmailOTP(string $code, TypeCodeOTPEnum $type): EmailVerifyOTP
    {
        $emailVerifyOTPRepository = app()->make(EmailVerifyOTPRepositoryInterface::class);
        $userRepository = app()->make(UserRepositoryInterface::class);

        if ($type == TypeCodeOTPEnum::VERIFY_EMAIL && ! is_null($this->getEmailVerifiedAt())) {
            throw new EmailVerifiedException(code: AuthExceptionEnum::EMAIL_VERIFIED->value);
        }

        $emailVerifyOTP = $emailVerifyOTPRepository->findByCondition(
            filters: ['user_id' => $this->getId(), 'code' => $code, 'type' => $type->value]
        );

        if (is_null($emailVerifyOTP)) {
            throw new InvalidOTPException(code: AuthExceptionEnum::INVALID_CODE->value);
        }
        /** @var EmailVerifyOTP $emailVerifyOTP */
        $emailVerifyOTP->isValidOTP(user: $this);
        if ($type == TypeCodeOTPEnum::VERIFY_EMAIL) {
            $this->setEmailVerifiedAt(emailVerifiedAt: now());
            $userRepository->save(userDomain: $this);
        }
        $emailVerifyOTPRepository->deleteByUserIdAndType(userId: $this->getId(), type: $type);

        return $emailVerifyOTP;
    }
}
