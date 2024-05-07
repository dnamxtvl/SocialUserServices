<?php

namespace App\Application\UseCases;

use App\Application\Command;
use App\Domains\Auth\Entities\User\User as UserDomain;
use App\Domains\Auth\Repository\CityRepositoryInterface;
use App\Domains\Auth\Repository\DistrictRepositoryInterface;
use App\Domains\Auth\Repository\WardRepositoryInterface;
use App\Domains\Auth\ValueObjects\Email;
use App\Domains\Auth\ValueObjects\Residence;
use App\Domains\Auth\ValueObjects\StatusActive;
use App\Domains\Auth\ValueObjects\Worker;
use App\Domains\User\DTOs\RegisterUserParamsDTO;
use App\Domains\User\Enums\UserStatusEnum;
use App\Domains\User\Repository\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class RegisterUserUseCase extends Command
{
    public function __construct(
        private readonly RegisterUserParamsDTO $registerUserParams,
    ) {
    }

    public function handle(
        CityRepositoryInterface $cityRepository,
        DistrictRepositoryInterface $districtRepository,
        WardRepositoryInterface $wardRepository,
        UserRepositoryInterface $userRepository
    ): JsonResponse {
        try {
            $fromCity = $cityRepository->findById($this->registerUserParams->getFromCityId());
            $fromDistrict = $districtRepository->findById($this->registerUserParams->getFromDistrictId());
            $fromWard = $wardRepository->findById($this->registerUserParams->getFromWardId());
            $currentCity = $cityRepository->findById($this->registerUserParams->getCurrentCityId());
            $currentDistrict = $districtRepository->findById($this->registerUserParams->getCurrentDistrictId());
            $currentWard = $wardRepository->findById($this->registerUserParams->getCurrentWardId());

            if (is_null($fromCity) || is_null($fromDistrict) || is_null($fromWard) ||
                is_null($currentCity) || is_null($currentDistrict) || is_null($currentWard)
            ) {
                throw new NotFoundHttpException('Địa điểm không hợp lệ');
            }

            if (! $fromCity->isDistrictOfCity(district: $fromDistrict) || ! $fromDistrict->isWardOfDistrict(ward: $fromWard) ||
                ! $currentCity->isDistrictOfCity(district: $currentDistrict) || ! $currentDistrict->isWardOfDistrict(ward: $currentWard)
            ) {
                throw new NotFoundHttpException('Địa điểm không hợp lệ');
            }
            Log::info('Chuẩn bị đăng ký user có email '.$this->registerUserParams->getEmail());
            $user = new UserDomain(
                identityId: $this->registerUserParams->getIdentityId(),
                userCode: $userRepository->getMaxUserCode() + 1,
                firstName: $this->registerUserParams->getFirstname(),
                lastName: $this->registerUserParams->getLastname(),
                email: new Email(email: $this->registerUserParams->getEmail()),
                typeAccount: $this->registerUserParams->getTypeAccount(),
                gender: $this->registerUserParams->getGender(),
                status: UserStatusEnum::NOT_VERIFIED,
                birthday: Carbon::createFromDate(
                    year: $this->registerUserParams->getYearOfBirth(),
                    month: $this->registerUserParams->getMonthOfBirth(),
                    day: $this->registerUserParams->getDayOfBirth()
                ),
                placeOfBirth: new Residence(cityId: $fromCity->getId(), districtId: $fromDistrict->getId(), wardId: $fromWard->getId()),
                statusActive: new StatusActive(isActive: false),
                job: new Worker(
                    organizationId: $this->registerUserParams->getOrganizationId(),
                    unitRoomId: $this->registerUserParams->getUnitRoomId(),
                ),
                password: $this->registerUserParams->getPassword(),
                currentResidence: new Residence(cityId: $currentCity->getId(), districtId: $currentDistrict->getId(), wardId: $currentWard->getId())
            );
            $newUser = $userRepository->save(userDomain: $user);

            return $this->respondWithJson(content: $newUser->toArray());
        } catch (Throwable $exception) {
            return $this->respondWithJsonError(e: $exception);
        }
    }
}
