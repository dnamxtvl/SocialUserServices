<?php

namespace App\Domains\User\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

class AccountClosedException extends AuthorizationException
{
    public function __construct(string $message = 'Tài khoản đã bị đóng,vui lòng liên hệ Admin!', int $code = 0)
    {
        parent::__construct(message: $message, code: $code);
    }
}
