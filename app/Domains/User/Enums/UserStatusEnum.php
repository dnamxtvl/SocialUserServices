<?php

namespace App\Domains\User\Enums;

enum UserStatusEnum: int
{
    case ACTIVE = 1;
    case INACTIVE = 2;
    case BLOCKED_PROFILE = 3;
    case BLOCK_MESSAGE = 4;
    case CLOSE_ACCOUNT = 5;
}
