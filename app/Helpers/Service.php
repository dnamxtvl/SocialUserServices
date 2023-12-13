<?php

namespace App\Helpers;

use Illuminate\Foundation\Bus\DispatchesJobs;

class Service
{
    use DispatchesJobs,
        RespondWithJsonTrait,
        RespondWithJsonErrorTrait;
}
