<?php

namespace App\Domains\Auth\Enums;

enum TypeForgotPasswordLogEnum: int
{
    case USER_REQUEST_FORGOT_PASSWORD = 1;

    case PASSWORD_CHANGED = 2;
}
