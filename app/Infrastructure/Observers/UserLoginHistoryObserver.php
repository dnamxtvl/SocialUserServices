<?php

namespace App\Infrastructure\Observers;

use App\Infrastructure\Models\UserLoginHistory;
use Stevebauman\Location\Facades\Location;

class UserLoginHistoryObserver
{
    /**
     * Handle the UserHistoryLogin "creating" event.
     */
    public function creating(UserLoginHistory $userLoginHistory): void
    {
        $location = Location::get($userLoginHistory->ip);
        if ($location) {
            $userLoginHistory->longitude = $location->longitude;
            $userLoginHistory->latitude = $location->latitude;
            $userLoginHistory->country_name = $location->countryName;
            $userLoginHistory->city_name = $location->cityName;
        }
    }
}
