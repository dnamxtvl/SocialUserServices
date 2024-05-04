<?php

namespace App\Domains\User\Enums;

enum TypeAccountEnum: int
{
    case SYSTEM = 0;
    case ORGANIZATION = 1;
    case CHILD = 2;
    case PARENT = 3;
}
