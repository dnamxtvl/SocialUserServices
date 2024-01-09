<?php

namespace App\Domains\Auth\Exceptions;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EmailNotVerifyException extends BadRequestHttpException
{
    public function __construct(string $message = 'Tài khoản chưa được xác thực, chúng tôi đã gửi otp xác thực đến email của bạn', int $code = 0)
    {
        parent::__construct(message: $message, code: $code);
    }
}
