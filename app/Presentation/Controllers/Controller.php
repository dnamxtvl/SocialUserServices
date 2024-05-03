<?php

namespace App\Presentation\Controllers;

use App\Helpers\RespondWithJsonTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, RespondWithJsonTrait, ValidatesRequests;
}
