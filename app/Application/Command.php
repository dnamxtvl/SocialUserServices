<?php

namespace App\Application;

use App\Application\Responses\RespondWithJsonErrorTrait;
use App\Application\Responses\RespondWithJsonTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Command
{
    use DispatchesJobs,
        RespondWithJsonErrorTrait,
        RespondWithJsonTrait;
}
