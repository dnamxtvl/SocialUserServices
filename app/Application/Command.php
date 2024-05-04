<?php

namespace App\Application;

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Application\Responses\RespondWithJsonErrorTrait;
use App\Application\Responses\RespondWithJsonTrait;

class Command
{
    use DispatchesJobs,
        RespondWithJsonErrorTrait,
        RespondWithJsonTrait;
}
