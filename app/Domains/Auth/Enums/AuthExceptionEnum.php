<?php

namespace App\Domains\Auth\Enums;

enum AuthExceptionEnum: int
{
    case INVALID_CODE = 8445338;

    case OTP_EXPIRED = 8422338;

    case EMAIL_VERIFIED = 8499338;

    case ACCOUNT_CLOSED = 3463368;

    case EMAIL_NOT_VERIFY = 3453628;
}
