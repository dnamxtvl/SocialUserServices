<?php

namespace App\Domains\Auth\Exceptions;

use Laravel\Horizon\Exceptions\ForbiddenException;
use Symfony\Component\HttpFoundation\Response;

class EmailNotVerifyException extends ForbiddenException
{
    public function __construct(string $message = 'Tài khoản chưa được xác thực, chúng tôi đã gửi otp xác thực đến email của bạn', int $code = 0)
    {
        parent::__construct(statusCode: Response::HTTP_FORBIDDEN, message: $message, code: $code);
    }
}
