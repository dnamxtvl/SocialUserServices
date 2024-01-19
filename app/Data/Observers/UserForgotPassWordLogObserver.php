<?php

namespace App\Data\Observers;

use App\Data\Models\UserForgotPasswordLog;
use Stevebauman\Location\Facades\Location;

class UserForgotPassWordLogObserver
{
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
}
