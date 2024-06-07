<?php

namespace App\Domains\User\Enums;

enum UserRelationshipStatusEnum: int
{
    case SINGLE = 0;
    case MARRIED = 1;
    case DATING = 2;
    case LIVE_TOGETHER = 3;
}
