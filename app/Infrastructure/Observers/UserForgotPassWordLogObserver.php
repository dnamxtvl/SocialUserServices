<?php

namespace App\Infrastructure\Observers;

use App\Domains\Auth\Enums\TypeCodeOTPEnum;
use App\Events\ForgotPasswordEvent;
use App\Infrastructure\Models\UserForgotPasswordLog;
use Stevebauman\Location\Facades\Location;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class UserForgotPassWordLogObserver
{
    public function __construct(
    ) {

    }

    /**
     * Handle the UserForgotPasswordLog "creating" event.
     */
    public function creating(UserForgotPasswordLog $userForgotPasswordLog): void
    {
        $location = Location::get($userForgotPasswordLog->ip);
        if ($location) {
            $userForgotPasswordLog->longitude = $location->longitude;
            $userForgotPasswordLog->latitude = $location->latitude;
            $userForgotPasswordLog->country_name = $location->countryName;
            $userForgotPasswordLog->city_name = $location->cityName;
        }
    }

    public function created(UserForgotPasswordLog $userForgotPasswordLog): void
    {
        $user = $userForgotPasswordLog->user;
        if (is_null($user)) {
            throw new NotFoundHttpException('User đã bị xóa');
        }
        $code = rand(config('validation.verify_code.min_value'), config('validation.verify_code.max_value'));
        event(new ForgotPasswordEvent(user: $user, verifyCode: $code, type: TypeCodeOTPEnum::FORGET_PASSWORD));
    }
}
