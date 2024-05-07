<?php

namespace App\Domains\User\Enums;

enum UserStatusActiveUnum: int
{
    case ONLINE = 1;
    case OFFLINE = 0;
}
