<?php

namespace App\Domains\Auth\Enums;

enum TypeCodeOTPEnum: int
{
    case VERIFY_EMAIL = 1;

    case FORGET_PASSWORD = 2;
}
