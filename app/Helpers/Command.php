<?php

namespace App\Helpers;

use Illuminate\Foundation\Bus\DispatchesJobs;

class Command
{
    use DispatchesJobs,
        RespondWithJsonErrorTrait,
        RespondWithJsonTrait;
}
